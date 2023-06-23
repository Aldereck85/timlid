<?php
session_start();
$ruta_api = "../../../";
$empresa = $_SESSION["IDEmpresa"];
$userid = $_SESSION["PKUsuario"];
require_once($ruta_api.'include/db-conn.php');
$idsFacturas =  $_REQUEST["ids"];
$PKCliente =  $_REQUEST["clienteId"];
$descripcion = $_REQUEST["descripcion"];
$importe = $_REQUEST["importe"];
$sucursal;
$folioMax;
$dbh;

  $query = sprintf("SELECT id FROM sucursales where empresa_id = $empresa limit 1;");
  $stmt = $conn->prepare($query);
  $stmt->execute();
    $sucursal = $stmt->fetchAll();
    $sucursal = $sucursal[0]["id"];
    $stmt->closeCursor();

  //Saca la venta seleccionada.
    $all = $conn->prepare("SELECT PKVentaDirecta, Importe from ventas_directas where PKVentaDirecta in($idsFacturas) and empresa_id = $empresa and estatus_factura_id not in (1,2)");
    $all->execute();
    $rows = $all->rowCount();
    $venta_id = $all->fetch();

    $stmt = $conn->prepare("SELECT ifnull(sum(nc.importe),0) as importe from notas_cuentas_por_cobrar nc inner join notas_cuentas_por_cobrar_has_ventas ncv on ncv.notaCredito_id = nc.id where ncv.venta_id in($idsFacturas) and nc.empresa_id = $empresa and nc.estatus = 1;");
    $stmt->execute();
    $res = $stmt->fetch();

    if(($res['importe'] + $importe) > $venta_id['Importe']){
      $data['status']="warning";
      $data['result']="las NC se exceden del importe de la venta";
    }else{
      if($rows > 0){
        //Genera el Folio que se incertará a la NC
        $query = sprintf("SELECT folion_nota as idMax FROM notas_cuentas_por_cobrar where empresa_id = $empresa and tipo_nc = 2 ORDER BY fecha_captura desc limit 1;");
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $folioMax = $stmt->fetchAll();
        if($folioMax){
          $folioMax = $folioMax[0]["idMax"] + 1;
        }else{
          $folioMax = 1;
        }
  
        //Abre una nueva transaccion.
        $dbh = $conn->beginTransaction();
        try{
          //Crea la query
          $query = sprintf("INSERT INTO notas_cuentas_por_cobrar (
            id_Nota_Facturapi,
            folion_nota,
            num_serie_nota,
            importe,
            subtotal,
            sucursal_id,
            usuario_creo_id,
            uso_cfdi_id,
            metodo_pago_id,
            sat_tipo_ralcion_id,
            sat_moneda_id,
            estatus,
            empresa_id,
            cliente_id,
            tipo_nc,
            descripcion) values (
              0,
              :folion_nota,
              :num_serie_nota,
              :importe,
              :subtotal,
              :sucursal_id,
              :usuario_creo_id,
              0,
              0,
              0,
              :sat_moneda_id,
              1,
              :empresa_id,
              :cliente_id,
              2,
              :descripcion
            )");
            //Prepara la query
            $stmt = $conn->prepare($query);
            //Pasa los valores a la query
            $stmt->bindValue(":folion_nota",$folioMax);
            $stmt->bindValue(":num_serie_nota",'NCV');
            $stmt->bindValue(":importe",$importe);
            $stmt->bindValue(":subtotal",$importe);
            $stmt->bindValue(":sucursal_id",$sucursal);
            $stmt->bindValue(":usuario_creo_id",$userid);
            $stmt->bindValue(":sat_moneda_id",484);
            $stmt->bindValue(":empresa_id",$empresa);
            $stmt->bindValue(":cliente_id",$PKCliente);
            $stmt->bindValue(":descripcion",$descripcion);
  
          //Ejecuta la query
          $stmt->execute();
          //optiene el id de el registro recien insertado.
          $foreinKey = $conn->lastInsertId();
  
          $query5 = sprintf("INSERT INTO notas_cuentas_por_cobrar_has_ventas (
            notaCredito_id,
            venta_id) values (
              :nc_id,
              :venta_id)");
            $stmt = $conn->prepare($query5);
            $stmt->bindValue(":nc_id",$foreinKey);
            $stmt->bindValue(":venta_id",$venta_id['PKVentaDirecta']);
            $stmt->execute();
            $data['status'] = "ok";

          //Aplica los cambios 
          $conn->commit();
        }catch (Exception $e){
          $conn->rollBack();
          $data['status'] = "error";
          $data['result'] = $e->getMessage();
        }
        $_SESSION["FacEgreso"] = "1";  
      }else{
        $data['status']="warning";
        $data['result'] = 'no existe la venta';
      }
    }

    echo json_encode($data);
    
?>