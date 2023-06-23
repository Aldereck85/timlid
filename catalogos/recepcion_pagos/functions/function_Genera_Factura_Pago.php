<?php
session_start();
$ruta_api = "../../../";
$empresa = $_SESSION["IDEmpresa"];
require_once($ruta_api.'include/db-conn.php');
require_once($ruta_api.'include/functions_api_facturation.php');
require_once $ruta_api . 'vendor/facturapi/facturapi-php/src/Facturapi.php';
//función para dar formato a las cantidades
require_once('function_formatoCantidad.php');

$folioPago=$_REQUEST['folio'];
$isSubstitution = isset($_REQUEST['isSubstitution']) ? $_REQUEST['isSubstitution'] : 0;
$api = new API();
$Total=0;

//valida que el metodo de pago sea en parcialidades o diferido
$query = sprintf('SELECT f.metodo_pago, DATE_ADD(p.fecha_pago, INTERVAL 1 HOUR) as fecha_pago FROM facturacion as f
                    inner join movimientos_cuentas_bancarias_empresa as mv on mv.id_factura = f.id
                    inner join pagos as p on p.idpagos = mv.id_pago
                  where p.identificador_pago=:folioPago and p.tipo_movimiento = 0 AND p.estatus=1 and mv.estatus=1 and p.empresa_id=:empresa and f.prefactura = 0 limit 1;');
$stmt = $conn->prepare($query);
$stmt->bindValue(":folioPago",$folioPago);
$stmt->bindValue(":empresa",$empresa);
$stmt->execute();
$res=$stmt->rowCount();
$result=$stmt->fetchAll();
$stmt->closeCursor();

if($res == 1){
if($result[0]['metodo_pago'] == 3){
  $fechaPago = $result[0]['fecha_pago'];

  //valida si el pago ya ha sido timbrado o pendiente de cancelar
  $query = sprintf('SELECT id_api, estatus FROM facturas_pagos where folio_pago=:folioPago AND estatus in (1,2) and empresa_id=:empresa;');
  $stmt = $conn->prepare($query);
  $stmt->bindValue(":folioPago",$folioPago);
  $stmt->bindValue(":empresa",$empresa);
  $stmt->execute();
  $res=$stmt->rowCount();
  $complementoOld=$stmt->fetchAll();
  $stmt->closeCursor();
  
  if($res==0 || $isSubstitution == 1){
    //recuperación de los datos necesarios para facturapi
    //se recupera la key de la empresa
    $query = sprintf("select key_company_api key_company,key_user_company_api key_user from empresas where PKEmpresa = :id");
    $stmt = $conn->prepare($query);
    $stmt->bindValue(":id",$empresa);
    $stmt->execute();

    $key_company_api = $stmt->fetchAll();
    $stmt->closeCursor();

    //recupera el id del cliente mediante una factura del pago a facturar
    $query = sprintf('SELECT crf.clave ,cl.razon_social, cl.rfc, cl.PKCliente, cl.codigo_postal FROM facturacion f
    INNER JOIN clientes cl ON cl.PKCliente = f.cliente_id
    inner join movimientos_cuentas_bancarias_empresa m on m.id_factura=f.id
    inner join pagos p on p.idpagos=m.id_pago
    left join claves_regimen_fiscal as crf on cl.regimen_fiscal_id = crf.id
    WHERE p.identificador_pago=:folioPago and f.empresa_id = :empresa and p.estatus=1 limit 1;');
    $stmt = $conn->prepare($query);
    $stmt->bindValue(":folioPago",$folioPago);
    $stmt->bindValue(":empresa",$empresa);
    $stmt->execute();
    
    $cliente[0]['razon_social'] = str_replace('"', '\"', $cliente[0]['razon_social']);

    $cliente = $stmt->fetchAll();
    $cliente_Api = [
      "legal_name" => $cliente[0]['razon_social'],
      "tax_id" => $cliente[0]['rfc'],
      "tax_system" => $cliente[0]['clave'],
      "address" => [
        "zip" => strval($cliente[0]['codigo_postal'])
      ]
    ];
    $stmt->closeCursor();

    //recupera los datos de las facturas relacionadas al pago
    $query = sprintf('SELECT f.id, concat(f.serie,f.folio) as folioInternoFactura, f.uuid, mnd.Clave as clave_moneda, m.parcialidad, m.saldo_anterior, m.Deposito FROM facturacion f
    inner join movimientos_cuentas_bancarias_empresa m on m.id_factura=f.id
    inner join pagos p on p.idpagos=m.id_pago
    inner join monedas mnd on mnd.PKMoneda=f.moneda_id
    WHERE p.identificador_pago=:folioPago and f.empresa_id = :empresa and p.estatus=1;');
    $stmt = $conn->prepare($query);
    $stmt->bindValue(":folioPago",$folioPago);
    $stmt->bindValue(":empresa",$empresa);
    $stmt->execute();

    //variable que contiene la nota con los folios internos
    $foliosInternosFacturas='<h3>Folios Internos de Facturas Relacionadas</h3>';

    $facturasRelacionadas=[];
    $rows = $stmt->fetchAll();
    for ($i=0; $i< count($rows); $i++) {
      //si la parcialidad es null se envía por defecto un 1
      if($rows[$i]['parcialidad']==null || $rows[$i]['parcialidad']==""){
        $rows[$i]['parcialidad']=1;
      }

    //recupera los taxes de la factura
      $query = sprintf("SELECT
                            dpft.subtotal,
                            dpft.iva,
                            dpft.iva_exento,
                            dpft.iva_retenido,
                            dpft.ieps,
                            dpft.ieps_exento,
                            dpft.ieps_retenido,
                            dpft.ieps_monto_fijo,
                            dpft.ieps_retenido_monto_fijo,
                            /* dpft.isr,
                            dpft.isr_exento,
                            dpft.isr_monto_fijo, */
                            dpft.isr_retenido
                          /*dpft.isr_retenido_monto_fijo*/
                          from detalle_facturacion dpft
                          where factura_id = :id");
      $stmt = $conn->prepare($query);
      $stmt->bindValue(":id", $rows[$i]['id']);
      $stmt->execute();

      $impuestos_aux = $stmt->fetchAll();
      $impuestos = [];
      if(count($impuestos_aux)<1){
        $taxability = "01";
      }else{
        $taxability = "02";
      }

      foreach ($impuestos_aux as $r) {
        $r['subtotal'] = number_format($r['subtotal'], 2, '.', '');

        if ($r['iva'] !== "" && $r['iva'] !== null) {
          array_push(
            $impuestos,
            array(
              "base" => (float)$r['subtotal'],
              "type" => 'IVA',
              "rate" => ((float)$r['iva'])/100,
              "factor" => 'Tasa',
              "withholding" => false
            )
          );
        }
        if ($r['iva_exento'] !== "" && $r['iva_exento'] !== null) {
          array_push(
            $impuestos,
            array(
              "base" => (float)$r['subtotal'],
              "type" => 'IVA',
              "rate" => ((float)$r['iva_exento'])/100,
              "factor" => 'Exento',
              "withholding" => false
            )
          );
        }
        if ($r['iva_retenido'] !== "" && $r['iva_retenido'] !== null) {
          array_push(
            $impuestos,
            array(
              "base" => (float)$r['subtotal'],
              "type" => 'IVA',
              "rate" => ((float)$r['iva_retenido'])/100,
              "factor" => 'Tasa',
              "withholding" => true
            )
          );
        }
        if ($r['ieps'] !== "" && $r['ieps'] !== null) {
          array_push(
            $impuestos,
            array(
              "base" => (float)$r['subtotal'],
              "type" => 'IEPS',
              "rate" => ((float)$r['ieps'])/100,
              "factor" => 'Tasa',
              "withholding" => false
            )
          );
        }
        if ($r['ieps_retenido'] !== "" && $r['ieps_retenido'] !== null) {
          array_push(
            $impuestos,
            array(
              "base" => (float)$r['subtotal'],
              "type" => 'IEPS',
              "rate" => ((float)$r['ieps_retenido'])/100,
              "factor" => 'Tasa',
              "withholding" => true
            )
          );
        }
        if ($r['ieps_monto_fijo'] !== "" && $r['ieps_monto_fijo'] !== null) {
          array_push(
            $impuestos,
            array(
              "base" => (float)$r['subtotal'],
              "type" => 'IEPS',
              "rate" => (float)$r['ieps_monto_fijo'],
              "factor" => 'Cuota',
              "withholding" => false
            )
          );
        }
        if ($r['ieps_exento'] !== "" && $r['ieps_exento'] !== null) {
          array_push(
            $impuestos,
            array(
              "base" => (float)$r['subtotal'],
              "type" => 'IEPS',
              "rate" => ((float)$r['ieps_exento'])/100,
              "factor" => 'Exento',
              "withholding" => false
            )
          );
        }
        if ($r['ieps_retenido_monto_fijo'] !== "" && $r['ieps_retenido_monto_fijo'] !== null) {
          array_push(
            $impuestos,
            array(
              "base" => (float)$r['subtotal'],
              "type" => 'IEPS',
              "rate" => (float)$r['ieps_retenido_monto_fijo'],
              "factor" => 'Cuota',
              "withholding" => true
            )
          );
        }
        /* if ($r['isr'] !== "" && $r['isr'] !== null) {
          array_push(
            $impuestos,
            array(
              "base" => (float)$r['subtotal'],
              "type" => 'ISR',
              "rate" => (float)$r['isr'],
              "factor" => 'Tasa',
              "withholding" => false
            )
          );
        }
        if ($r['isr_exento'] !== "" && $r['isr_exento'] !== null) {
          array_push(
            $impuestos,
            array(
              "base" => (float)$r['subtotal'],
              "type" => 'ISR',
              "rate" => (float)$r['isr_exento'],
              "factor" => 'Exento',
              "withholding" => false
            )
          );
        }
        if ($r['isr_monto_fijo'] !== "" && $r['isr_monto_fijo'] !== null) {
          array_push(
            $impuestos,
            array(
              "base" => (float)$r['subtotal'],
              "type" => 'ISR',
              "rate" => (float)$r['isr_monto_fijo'],
              "factor" => 'Cuota',
              "withholding" => false
            )
          );
        } */
        if ($r['isr_retenido'] !== "" && $r['isr_retenido'] !== null) {
          array_push(
            $impuestos,
            array(
              "base" => (float)$r['subtotal'],
              "type" => 'ISR',
              "rate" => ((float)$r['isr_retenido'])/100,
              "factor" => 'Tasa',
              "withholding" => true
            )
          );
        }
        /* if ($r['isr_retenido_monto_fijo'] !== "" && $r['isr_retenido_monto_fijo'] !== null) {
          array_push(
            $impuestos,
            array(
              "base" => (float)$r['subtotal'],
              "type" => 'ISR',
              "rate" => (float)$r['isr_retenido_monto_fijo'],
              "factor" => 'Cuota',
              "withholding" => true
            )
          );
        } */
      }

      $Total=$Total+$rows[$i]['Deposito'];
      $foliosInternosFacturas .='<h5>'.$rows[$i]['folioInternoFactura'].'</h5>';
      array_push(
        $facturasRelacionadas,
        array(
          "uuid" => $rows[$i]['uuid'],
          "installment" => $rows[$i]['parcialidad'],
          "last_balance" => $rows[$i]['saldo_anterior'],
          "amount" => $rows[$i]['Deposito'],
          "taxes" => $impuestos,
          "currency" => $rows[$i]['clave_moneda'],
          "taxability" => $taxability
        )
      );
    }
    $stmt->closeCursor();


    $query = sprintf('SELECT fp.clave from formas_pago_sat fp
    inner join pagos p on p.forma_pago=fp.id
    inner join movimientos_cuentas_bancarias_empresa m on p.idpagos=m.id_pago
    inner join facturacion f on f.id=m.id_factura
    WHERE p.identificador_pago=:folioPago and p.tipo_movimiento=0 and f.empresa_id = :empresa and p.estatus=1 limit 1;');
    $stmt = $conn->prepare($query);
    $stmt->bindValue(":folioPago",$folioPago);
    $stmt->bindValue(":empresa",$empresa);
    $stmt->execute();

    $forma_pago = $stmt->fetchAll();
    $stmt->closeCursor();

    $data[] = array(
      "payment_form" => $forma_pago[0]['clave'],
      "related_documents" => $facturasRelacionadas,
      "date" => $fechaPago
    );

    $complemetos[]= array(
      "type" => "pago",
      "data" => $data,
    );

    $invoice = [
      "type" => "P",
      "customer" => $cliente_Api,
      "complements" => $complemetos,
      "pdf_custom_section" => $foliosInternosFacturas,
    ];
      $mensaje = $api->createInvoice($key_company_api[0]['key_company'],$invoice);
      $data['status']="";

      if(isset($mensaje->id)&& $mensaje->id !== "" && $mensaje->id !== null){

        $query = sprintf("insert into facturas_pagos (
                              id_api,
                              uuid,
                              fecha_timbrado,
                              cliente_id,
                              usuario_timbro,
                              estatus,
                              total_facturado,
                              empresa_id,
                              forma_pago,
                              folio_pago,
                              folio_complemento) values (
                              :id_api,
                              :uuid,
                              :fecha_timbrado,
                              :cliente_id,
                              :usuario_timbro,
                              :estatus,
                              :total_facturado,
                              :empresa_id,
                              :forma_pago,
                              :folio_pago,
                              :folio_complemento
                            )");
        $stmt = $conn->prepare($query);
        $stmt->bindValue(":id_api",$mensaje->id);
        $stmt->bindValue(":uuid",$mensaje->uuid);
        $stmt->bindValue(":fecha_timbrado",date('Y-m-d H:i:s'));
        $stmt->bindValue(":cliente_id",$cliente[0]['PKCliente']);
        $stmt->bindValue(":usuario_timbro",$_SESSION['PKUsuario']);
        $stmt->bindValue(":total_facturado",$Total);
        $stmt->bindValue(":estatus",1);
        $stmt->bindValue(":empresa_id",$empresa);
        $stmt->bindValue(":forma_pago", $forma_pago[0]['clave']);
        $stmt->bindValue(":folio_pago",  $folioPago);
        $stmt->bindValue(":folio_complemento", $mensaje->folio_number);

        try{
          $stmt->execute();
          $data['status']="ok";
          $data['result']=$mensaje->id;

          //se cancela el complemento existente en base de datos si se trata de una substuitución
          if($isSubstitution == 1){
            $query = sprintf('UPDATE facturas_pagos set estatus = 0 where id_api=:complementoOld AND estatus=1 and empresa_id=:empresa;');
            $stmt = $conn->prepare($query);
            $stmt->bindValue(":complementoOld",$complementoOld[0]['id_api']);
            $stmt->bindValue(":empresa",$empresa);
            $stmt->execute();
            $stmt->closeCursor();
            $data['complementoOld'] = $complementoOld[0]['id_api'];
          }
        }catch(exception $e){
          $data['status']="err";
          $data['result']="error: ".$e->getMessage();
        }
      }else{
        $data['status']="err";
        $data['result']="Error: ".$mensaje->message;
        $data['error']="El error es: ".$mensaje->message;
      } 
  }else{
    if((int)$complementoOld[0]['estatus'] == 1){
      $data['msg']="¡Advertencia, el pago ha sido timbrado!";
    }else{
      $data['msg']="¡Advertencia, el pago está pendiente de cancelar!";
    }
    $data['status']="fine";
    $data['result']="inaccesible";
  }
}else{
  $data['status']="warning";
  $data['result']="inaccesible";
} 
}else{
  $data['status']="warning";
  $data['result']="inaccesible";
} 

echo json_encode($data);
?>
