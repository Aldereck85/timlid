<?php

use get_data as GlobalGet_data;

session_start();
date_default_timezone_set('America/Mexico_City');
class conectar
{ //Llamado al archivo de la conexión.
  function getDb()
  {
    include "../../../include/db-conn.php";
    return $conn;
  }
}

function GetEvn()
{
    include "../../../include/db-conn.php";
    $appUrl = $_ENV['APP_URL'] ?? 'https://app.timlid.com/';
    return ['server' => $appUrl];
}

class get_data
{
  public $num_decimal = [];

  function getApiKeys()
  {
    $con = new conectar();
    $db = $con->getDb();
    $query = sprintf("select key_company_api key_company,key_user_company_api key_user from empresas where PKEmpresa = :id");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":id",$_SESSION['IDEmpresa']);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }
  function getInvoicesTable($value)
  {
    $con = new conectar();
    $db = $con->getDb();
    $envVariables = GetEvn();
    $appUrl = $envVariables['server'];
    $getData = new get_data();
    $table = "";

    $query = sprintf("SELECT 
                          f.id,
                          f.serie,
                          f.folio,
                          f.folio_prefactura,
                          cl.PKCliente,
                          cl.razon_social,
                          f.total_facturado,
                          f.estatus,
                          f.fecha_timbrado,
                          f.fecha_cancelacion,
                          f.estatus_cancelacion_api,
                          f.tipo_factura,
                          f.fecha_vencimiento,
                          f.referencia,
                          f.tipo,
                          concat(e.Nombres,' ',e.PrimerApellido,' ',e.SegundoApellido) vendedor,
						              f.prefactura
                        FROM facturacion f
                          INNER JOIN clientes cl ON f.cliente_id = cl.PKCliente
                          LEFT JOIN empleados e on f.empleado_id = e.PKEmpleado
                        WHERE f.empresa_id = :id and f.prefactura = :preinvoice");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":id", $_SESSION['IDEmpresa']);
	$stmt->bindValue(":preinvoice", $value);
    $stmt->execute();
    $array = $stmt->fetchAll();

    if (count($array) > 0) {
      foreach ($array as $r) {
        $vendedor = "";

        if ($r['tipo'] === 4) {
          $vendedor = $r['vendedor'] !== null && $r['vendedor'] !== "" ? $r['vendedor'] : "Trasladado";
        } else if ($r['tipo'] === 0 || $r['tipo'] === 5 || $r['tipo'] === 6) {
          $vendedor = $r['vendedor'] !== null && $r['vendedor'] !== "" ? $r['vendedor'] : "Sin vendedor";
        } else {
          $vendedor = $r['vendedor'];
        }

        if ($r['fecha_timbrado'] !== "" && $r['fecha_timbrado'] !== "0000-00-00 00:00:00" && $r['fecha_timbrado'] !== null) {
          $fecha_timbrado = date("d-m-Y H:i:s", strtotime($r['fecha_timbrado']));
        } else {
          $fecha_timbrado = "";
        }

        if (($r['serie'] !== "" && $r['serie'] !== null) && ($r['folio'] !== "" && $r['folio'] !== null)) {
          $folio = $r['folio'];
          $serie = $r['serie'];
        } else if($r['folio_prefactura'] !== "" && $r['folio_prefactura'] !== null){
          $folio = $r['folio_prefactura'];
          $serie = "PRF";
        } else {
          $folio = "";
          $serie = "";
        }

        
        switch ($r['estatus']) {
          case 1:
            $status = "Pendiente de pago";
            break;
          case 2:
            $status = "Parcialmente pagada";
            break;
          case 3:
            $status = "Pagada";
            break;
          case 4:
            switch ($r['estatus_cancelacion_api']) {
              case 1:
                $status = "Pendiente de pago";
                break;
              case 2:
                $status = "Cancelacion pendiente";
                break;
              case 3:
                $status = "Cancelada";
                break;
              case 4:
                $status = "Cancelacion rechazada";
                break;
              case 5:
                $status = "Cancelada por expirar el tiempo límite de la solicitud";
                break;
              default:
                $status = "Cancelada";
                break;
            }
            break;
          case 5:
            $status = "En proceso de cancelación";
            break;
        }
        
        if ($r['fecha_vencimiento'] !== null && $r['fecha_vencimiento'] !== "" && $r['fecha_vencimiento'] !== "null") {
          if (date("Y-m-d", strtotime($r['fecha_vencimiento'])) < date("Y-m-d")) {
            $estatus_vencimiento = "Vencida";
            //$fecha_vencimiento = "<span class='badge badge-danger' style='font-size:1rem;font-family: Montserrat, sans-serif'>" . date("d-m-Y", strtotime($r['fecha_vencimiento'])) . "</span>";
            $fecha_vencimiento = date("d-m-Y", strtotime($r['fecha_vencimiento']));
          } else {
            $estatus_vencimiento = "Al corriente";
            //$fecha_vencimiento = "<span class='badge badge-success' style='font-size:1rem;font-family: Montserrat, sans-serif'>" . date("d-m-Y", strtotime($r['fecha_vencimiento'])) . "</span>";
            $fecha_vencimiento = date("d-m-Y", strtotime($r['fecha_vencimiento']));
          }
        } else {
          $estatus_vencimiento = "";
          $fecha_vencimiento = "Sin fecha";
        }
        $url = (int)$value === 0 ? "detalle_factura.php?idFactura=".$r['id']."" : "agregar_facturacion.php?idFactura=".$r['id']."";
        if((int)$value === 1){
          $pdf_preinvoice = "<a id='show_pdf_preinvoice' href='#' data-id='" . $r['id'] . "'><i class='far fa-file-pdf'></i></a>";
          
        } else {
          $pdf_preinvoice = "";
        }
        //link para detalle del cliente
        //$r['razon_social'] = '<a style=\"cursor:pointer\" href=\"'.$appUrl.'catalogos/clientes/catalogos/clientes/detalles_cliente.php?c='.$r['PKCliente'].'\">'.$r['razon_social'].'</a>';
        $razonSocial = str_replace('"', '\"', $r['razon_social']);

        $tipo_factura = ($r['tipo_factura'] === 0) ? "Por documento" : "Por concepto";
        $html = "<a id='detalle_factura' href='".$url."' data-id='" . $r['id'] . "' data-prefactura='" . $r['prefactura'] . "'> " . $folio . " </a>";
        $table .= '{
            "id" : "' . $r['id'] . '",
            "serie" : "'.$serie.'",
            "folio" : "' . $html . '",
            "Razon social" : "' . $razonSocial . '",
            "Total facturado" : "$' . number_format($r['total_facturado'], 2) . '",
            "estatus" : "' . $status . '",
            "Fecha de timbrado" : "' . $fecha_timbrado . '",
            "fecha_vencimiento" : "' . $fecha_vencimiento . '",
            "estatus_vencimiento" : "' . $estatus_vencimiento . '",
            "Vendedor" : "' . $vendedor . '",
            "operaciones" : "' . $pdf_preinvoice . '"
          },';
      }
    }

    $table = substr($table, 0, strlen($table) - 1);

    $con = "";
    $stmt = "";
    $db = "";

    return '{"data":[' . $table . ']}';
  }

  function getTableInvoiceFilterDate($value,$dateini,$datefin)
  {
    $con = new conectar();
    $db = $con->getDb();
    $table = "";
    
    $dateMax = $datefin !== null && $datefin !== "" ? "'$datefin 23:59:59'": "now()";
    
    $query = sprintf("SELECT 
                          f.id,
                          f.serie,
                          f.folio,
                          cl.PKCliente,
                          cl.razon_social,
                          f.total_facturado,
                          f.estatus,
                          f.fecha_timbrado,
                          f.fecha_cancelacion,
                          f.estatus_cancelacion_api,
                          f.tipo_factura,
                          f.fecha_vencimiento,
                          f.referencia,
                          f.tipo,
                          concat(e.Nombres,' ',e.PrimerApellido,' ',e.SegundoApellido) vendedor,
						              f.prefactura
                        FROM facturacion f
                          INNER JOIN clientes cl ON f.cliente_id = cl.PKCliente
                          LEFT JOIN empleados e on f.empleado_id = e.PKEmpleado
                        WHERE f.empresa_id = :id and prefactura = :preinvoice and f.fecha_timbrado between '$dateini' and $dateMax");
    $stmt = $db->prepare($query);
    
    $stmt->bindValue(":id", $_SESSION['IDEmpresa']);
	  $stmt->bindValue(":preinvoice", $value);
    
    $stmt->execute();
    $array = $stmt->fetchAll();

    if (count($array) > 0) {
      foreach ($array as $r) {
        $vendedor = "";

        if ($r['tipo'] === 4) {
          $vendedor = $r['vendedor'] !== null && $r['vendedor'] !== "" ? $r['vendedor'] : "Trasladado";
        } else if ($r['tipo'] === 0) {
          $vendedor = $r['vendedor'] !== null && $r['vendedor'] !== "" ? $r['vendedor'] : "Sin vendedor";
        } else {
          $vendedor = $r['vendedor'];
        }

        if ($r['fecha_timbrado'] !== "" && $r['fecha_timbrado'] !== "0000-00-00 00:00:00" && $r['fecha_timbrado'] !== null) {
          $fecha_timbrado = date("d-m-Y H:i:s", strtotime($r['fecha_timbrado']));
        } else {
          $fecha_timbrado = "";
        }

        if (($r['serie'] !== "" && $r['serie'] !== null) && ($r['folio'] !== "" && $r['folio'] !== null)) {
          $folio = $r['folio'];
          $serie = $r['serie'];
        } else {
          $folio = "";
          $serie ="";
        }

        switch ($r['estatus']) {
          case 1:
            $status = "Pendiente de pago";
            break;
          case 2:
            $status = "Parcialmente pagada";
            break;
          case 3:
            $status = "Pagada";
            break;
          case 4:
            switch ($r['estatus_cancelacion_api']) {
              case 1:
                $status = "Pendiente de pago";
                break;
              case 2:
                $status = "Cancelacion pendiente";
                break;
              case 3:
                $status = "Cancelada";
                break;
              case 4:
                $status = "Cancelacion rechazada";
                break;
              case 5:
                $status = "Cancelada por expirar el tiempo límite de la solicitud";
                break;
              default:
                $status = "Cancelada";
                break;
            }
            break;
          case 5:
            $status = "En proceso de cancelación";
            break;
        }

        if ($r['fecha_vencimiento'] !== null && $r['fecha_vencimiento'] !== "" && $r['fecha_vencimiento'] !== "null") {
          if (date("Y-m-d", strtotime($r['fecha_vencimiento'])) < date("Y-m-d")) {
            $estatus_vencimiento = "Vencida";
            //$fecha_vencimiento = "<span class='badge badge-danger' style='font-size:1rem;font-family: Montserrat, sans-serif'>" . date("d-m-Y", strtotime($r['fecha_vencimiento'])) . "</span>";
            $fecha_vencimiento = date("d-m-Y", strtotime($r['fecha_vencimiento']));
          } else {
            $estatus_vencimiento = "Al corriente";
            //$fecha_vencimiento = "<span class='badge badge-success' style='font-size:1rem;font-family: Montserrat, sans-serif'>" . date("d-m-Y", strtotime($r['fecha_vencimiento'])) . "</span>";
            $fecha_vencimiento = date("d-m-Y", strtotime($r['fecha_vencimiento']));
          }
        } else {
          $estatus_vencimiento = "";
          $fecha_vencimiento = "Sin fecha";
        }

        //link para detalle del cliente
        //$r['razon_social'] = '<a style=\"cursor:pointer\" href=\"'.$appUrl.'catalogos/clientes/catalogos/clientes/detalles_cliente.php?c='.$r['PKCliente'].'\">'.$r['razon_social'].'</a>';

        $tipo_factura = ($r['tipo_factura'] === 0) ? "Por documento" : "Por concepto";
        $html = "<a id='detalle_factura' href='#' data-id='" . $r['id'] . "' data-prefactura='" . $r['prefactura'] . "'> " . $folio . " </a>";
        $table .= '{
            "id" : "' . $r['id'] . '",
            "folio" : "' . $html . '",
            "serie" : "' . $serie . '",
            "Razon social" : "' . $r['razon_social'] . '",
            "Total facturado" : "' . number_format($r['total_facturado'], 2) . '",
            "estatus" : "' . $status . '",
            "Fecha de timbrado" : "' . $fecha_timbrado . '",
            "fecha_vencimiento" : "' . $fecha_vencimiento . '",
            "estatus_vencimiento" : "' . $estatus_vencimiento . '",
            "Vendedor" : "' . $vendedor . '"
          },';
      }
    }

    $table = substr($table, 0, strlen($table) - 1);

    $con = "";
    $stmt = "";
    $db = "";

    return '{"data":[' . $table . ']}';
  }

  function getDataPreinvoicePdf($value)
  {
    $con = new conectar();
    $db = $con->getDb();

    $query = sprintf("select
                        f.folio,
                        f.serie,
                        fp.descripcion forma_pago,
                        f.metodo_pago,
                        uc.descripcion cfdi,
                        cl.razon_social,
                        f.fecha_timbrado,
                        cl.rfc,
                        f.total_facturado,
                        ms.Clave moneda
                      from facturacion f
                      inner join formas_pago_sat fp on f.forma_pago_id = fp.id
                      inner join uso_cfdi uc on f.uso_cfdi_id = uc.id
                      inner join clientes cl on f.cliente_id = cl.PKCliente
                      inner join monedas ms on f.moneda_id = ms.PKMoneda
                      where f.id = :id");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":id",$value);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }

  function getMessageClientPreinvoice($value)
  {
    $con = new conectar();
    $db = $con->getDb();

    $query = sprintf("select tipo, referencia from facturacion where id = :id");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":id",$value);
    $stmt->execute();
    $arr = $stmt->fetchAll(PDO::FETCH_OBJ);
    
    switch((int)$arr[0]->tipo){
      case 1:
        $query = sprintf("select 
                            c.NotaCliente notas_cliente, 
                            decl.Calle calle,
                            decl.Numero_exterior no_exterior,
                            decl.Numero_Interior no_interior,
                            decl.Colonia colonia,
                            decl.CP cp,
                            decl.Municipio municipio,
                            e.Estado estado,
                            decl.Contacto contacto,
                            decl.Telefono telefono
                          from facturacion f 
                            inner join cotizacion c on f.referencia = c.PKCotizacion
                            inner join direcciones_envio_cliente decl on f.cliente_id = decl.FKCliente
                            inner join estados_federativos e on decl.Estado = e.PKEstado
                          where f.id = :id");
        break;
      case 2:
        $query = sprintf("select 
                            vd.NotasCliente notas_cliente, 
                            decl.Calle calle,
                            decl.Numero_exterior no_exterior,
                            decl.Numero_Interior no_interior,
                            decl.Colonia colonia,
                            decl.CP cp,
                            decl.Municipio municipio,
                            e.Estado estado,
                            decl.Contacto contacto,
                            decl.Telefono telefono
                          from facturacion f 
                            inner join ventas_directas vd on f.referencia = vd.PKVentaDirecta
                            inner join direcciones_envio_cliente decl on f.cliente_id = decl.FKCliente
                            inner join estados_federativos e on decl.Estado = e.PKEstado
                          where f.id = :id");
        break;
      case 3:
        break;
      case 4:
        break;
      case 0:
        break;
    }

    
    $stmt = $db->prepare($query);
    $stmt->bindValue(":id",$value);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }

  function getProductsPreinvoicePdf($value)
  {
    $con = new conectar();
    $db = $con->getDb();

    $query = sprintf("select 
                        pr.ClaveInterna clave,
                        pr.Nombre producto,
                        df.cantidad,
                        df.precio,
                        df.subtotal total,
                        csu.Clave u_medida
                      from detalle_facturacion df
                      inner join productos pr on df.producto_id = pr.PKProducto
                      inner join claves_sat_unidades csu on df.unidad_medida_id = csu.PKClaveSATUnidad
                      where factura_id = :id");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":id",$value);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }

  function getPreinvoicePdf($value)
  {
    $get_data = new get_data();
    $arr = [];

    array_push($arr,
      [
        "general_data"=>$get_data->getDataPreinvoicePdf($value),
        "products"=>$get_data->getProductsPreinvoicePdf($value),
        "impuestos"=>$get_data->getTaxPreinvoicePdf($value),
        "footer_pdf"=>$get_data->getMessageClientPreinvoice($value)
      ]
    );
    return $arr;
  }

  function getTaxPreinvoicePdf($value)
  {
    $con = new conectar();
    $db = $con->getDb();
    $query = sprintf("select 
                        iva,
                        importe_iva,
                        ieps,
                        importe_ieps,
                        ieps_monto_fijo,
                        ish,
                        importe_ish,
                        iva_exento,
                        importe_iva_exento,
                        iva_retenido,
                        importe_iva_retenido,
                        isr_retenido,
                        importe_isr_retenido,
                        isn_local,
                        importe_isn_local,
                        cedular,
                        importe_cedular,
                        al_millar,
                        importe_al_millar,
                        funcion_publica,
                        importe_funcion_publica,
                        ieps_retenido,
                        importe_ieps_retenido,
                        isr_exento,
                        importe_isr_exento,
                        isr_monto_fijo,
                        isr,
                        importe_isr,
                        ieps_exento,
                        importe_ieps_exento,
                        isr_retenido_monto_fijo,
                        ieps_retenido_monto_fijo
                      from detalle_facturacion
                      where factura_id = :id");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":id",$value);
    $stmt->execute();

    $impuestos_aux = $stmt->fetchAll();
    $impuestos = "";
    $importe_impuestos = 0;
    $impuestos_aux1 = [];

    foreach ($impuestos_aux as $r) {
      if ($r['iva'] !== "" && $r['iva'] !== null) {
        array_push(
          $impuestos_aux1,
          array(
            "impuesto" => 1,
            "tasa" => $r['iva'],
            "importe" => (float)$r['importe_iva']
          )
        );
      }
      if ($r['ieps'] !== "" && $r['ieps'] !== null) {
        array_push(
          $impuestos_aux1,
          array(
            "impuesto" => 2,
            "tasa" => $r['ieps'],
            "importe" => (float)$r['importe_ieps']
          )
        );
      }
      if ($r['ieps_monto_fijo'] !== "" && $r['ieps_monto_fijo'] !== null) {
        array_push(
          $impuestos_aux1,
          array(
            "impuesto" => 3,
            "tasa" => $r['ieps'],
            "importe" => (float)$r['ieps_monto_fijo']
          )
        );
      }
      if ($r['ish'] !== "" && $r['ish'] !== null) {
        array_push(
          $impuestos_aux1,
          array(
            "impuesto" => 4,
            "tasa" => $r['ish'],
            "importe" => (float)$r['importe_ish']
          )
        );
      }
      if ($r['iva_exento'] !== "" && $r['iva_exento'] !== null) {
        array_push(
          $impuestos_aux1,
          array(
            "impuesto" => 5,
            "tasa" => $r['iva_exento'],
            "importe" => (float)$r['importe_iva_exento']
          )
        );
      }
      if ($r['iva_retenido'] !== "" && $r['iva_retenido'] !== null) {
        array_push(
          $impuestos_aux1,
          array(
            "impuesto" => 6,
            "tasa" => $r['iva_retenido'],
            "importe" => (float)$r['importe_iva_retenido']
          )
        );
      }
      if ($r['isr'] !== "" && $r['isr'] !== null) {
        array_push(
          $impuestos_aux1,
          array(
            "impuesto" => 7,
            "tasa" => $r['isr'],
            "importe" => (float)$r['importe_isr']
          )
        );
      }
      if ($r['isn_local'] !== "" && $r['isn_local'] !== null) {
        array_push(
          $impuestos_aux1,
          array(
            "impuesto" => 8,
            "tasa" => $r['isn_local'],
            "importe" => (float)$r['importe_isn_local']
          )
        );
      }
      if ($r['cedular'] !== "" && $r['cedular'] !== null) {
        array_push(
          $impuestos_aux1,
          array(
            "impuesto" => 9,
            "tasa" => $r['cedular'],
            "importe" => (float)$r['importe_cedular']
          )
        );
      }
      if ($r['al_millar'] !== "" && $r['al_millar'] !== null) {
        array_push(
          $impuestos_aux1,
          array(
            "impuesto" => 10,
            "tasa" => $r['al_millar'],
            "importe" => (float)$r['importe_al_millar']
          )
        );
      }
      if ($r['funcion_publica'] !== "" && $r['funcion_publica'] !== null) {
        array_push(
          $impuestos_aux1,
          array(
            "impuesto" => 11,
            "tasa" => $r['funcion_publica'],
            "importe" => (float)$r['importe_funcion_publica']
          )
        );
      }
      if ($r['ieps_retenido'] !== "" && $r['ieps_retenido'] !== null) {
        array_push(
          $impuestos_aux1,
          array(
            "impuesto" => 12,
            "tasa" => $r['ieps_retenido'],
            "importe" => (float)$r['importe_ieps_retenido']
          )
        );
      }
      if ($r['isr_exento'] !== "" && $r['isr_exento'] !== null) {
        array_push(
          $impuestos_aux1,
          array(
            "impuesto" => 13,
            "tasa" => $r['isr_exento'],
            "importe" => (float)$r['importe_isr_exento']
          )
        );
      }
      if ($r['isr_monto_fijo'] !== "" && $r['isr_monto_fijo'] !== null) {
        array_push(
          $impuestos_aux1,
          array(
            "impuesto" => 14,
            "tasa" => $r['isr_monto_fijo'],
            "importe" => (float)$r['isr_monto_fijo']
          )
        );
      }
      if ($r['isr'] !== "" && $r['isr'] !== null) {
        array_push(
          $impuestos_aux1,
          array(
            "impuesto" => 15,
            "tasa" => $r['isr'],
            "importe" => (float)$r['importe_isr']
          )
        );
      }
      if ($r['ieps_exento'] !== "" && $r['ieps_exento'] !== null) {
        array_push(
          $impuestos_aux1,
          array(
            "impuesto" => 16,
            "tasa" => $r['ieps_exento'],
            "importe" => (float)$r['importe_ieps_exento']
          )
        );
      }
      if ($r['isr_retenido_monto_fijo'] !== "" && $r['isr_retenido_monto_fijo'] !== null) {
        array_push(
          $impuestos_aux1,
          array(
            "impuesto" => 17,
            "tasa" => $r['isr_retenido_monto_fijo'],
            "importe" => (float)$r['isr_retenido_monto_fijo']
          )
        );
      }
      if ($r['ieps_retenido_monto_fijo'] !== "" && $r['ieps_retenido_monto_fijo'] !== null) {
        array_push(
          $impuestos_aux1,
          array(
            "impuesto" => 18,
            "tasa" => $r['ieps_retenido_monto_fijo'],
            "importe" => (float)$r['ieps_retenido_monto_fijo']
          )
        );
      }
    }

    for ($i = 0; $i < count($impuestos_aux1); $i++) {
      for ($j = $i + 1; $j < count($impuestos_aux1); $j++) {
        if ($impuestos_aux1[$i]['tasa'] == $impuestos_aux1[$j]['tasa']) {
          $impuestos_aux1[$i]['importe'] = $impuestos_aux1[$i]['importe'] + $impuestos_aux1[$j]['importe'];
          $impuestos_aux1[$j]['importe'] = 0;
        }
      }
    }

    $impuestos_aux2 = [];

    foreach ($impuestos_aux1 as $r) {
      if ($r['importe'] !== 0) {
        array_push(
          $impuestos_aux2,
          array(
            "impuesto" => $r['impuesto'],
            "tasa" => $r["tasa"],
            "importe" => $r["importe"]
          )
        );
      }
    }

    foreach ($impuestos_aux2 as $r) {
      switch ($r['impuesto']) {
        case 1:
          $impuestos .= "IVA " . $r['tasa'] . "%: " . number_format($r['importe'], 2) . "<br>";
          break;
        case 2:
          $impuestos .= "IEPS " . $r['tasa'] . "%: " . number_format($r['importe'], 2) . "<br>";
          break;
        case 3:
          $impuestos .= "IEPS (Monto fijo) " . number_format($r['importe'], 2) . "<br>";
          break;
        case 4:
          $impuestos .= "ISH " . $r['tasa'] . "%: " . number_format($r['importe'], 2) . "<br>";
          break;
        case 5:
          $impuestos .= "IVA Exento " . $r['tasa'] . "%: " . number_format($r['importe'], 2) . "<br>";
          break;
        case 6:
          $impuestos .= "IVA Retenido " . $r['tasa'] . "%: " . number_format($r['importe'], 2) . "<br>";
          break;
        case 7:
          $impuestos .= "ISR retenido " . number_format($r['importe'], 2) . "<br>";
          break;
        case 8:
          $impuestos .= "ISN (Local) " . $r['tasa'] . "%: " . number_format($r['importe'], 2) . "<br>";
          break;
        case 9:
          $impuestos .= "Cedular " . $r['tasa'] . "%: " . number_format($r['importe'], 2) . "<br>";
          break;
        case 10:
          $impuestos .= "5 al millar (Local) " . $r['tasa'] . "%: " . number_format($r['importe'], 2) . "<br>";
          break;
        case 11:
          $impuestos .= "Función Pública " . $r['tasa'] . "%: " . number_format($r['importe'], 2) . "<br>";
          break;
        case 12:
          $impuestos .= "IEPS Retenido " . $r['tasa'] . "%: " . number_format($r['importe'], 2) . "<br>";
          break;
        case 13:
          $impuestos .= "ISR Exento " . $r['tasa'] . "%: " . number_format($r['importe'], 2) . "<br>";
          break;
        case 14:
          $impuestos .= "ISR (Monto fijo) :" . number_format($r['importe'], 2) . "<br>";
          break;
        case 15:
          $impuestos .= "ISR Retenido " . $r['tasa'] . "%: " . number_format($r['importe'], 2) . "<br>";
          break;
        case 16:
          $impuestos .= "IEPS Exento " . $r['tasa'] . "%: " . number_format($r['importe'], 2) . "<br>";
          break;
        case 17:
          $impuestos .= "ISR Retenido (Monto fijo) :" . number_format($r['importe'], 2) . "<br>";
          break;
        case 18:
          $impuestos .= "IEPS Retenido (Monto fijo) :" . number_format($r['importe'], 2) . "<br>";
          break;
      }
      $importe_impuestos += $r["importe"];
    }

    return $impuestos;
  }

  function getInvoiceDetailTable($value)
  {
    $con = new conectar();
    $db = $con->getDb();
    
    $table = "";

    $query = sprintf("select                   
                          pr.PKProducto id, 
                          pr.ClaveInterna clave,
                          pr.Nombre nombre,
                          csu.Descripcion unidad_medida,
                          dpft.cantidad,
                          dpft.precio,
                          dpft.subtotal,
                          dpft.numero_lote,
                          dpft.caducidad,
                          dpft.numero_serie
                        from detalle_facturacion dpft
                          inner join productos pr on dpft.producto_id = pr.PKProducto
                          left join claves_sat_unidades csu on dpft.unidad_medida_id = csu.PKClaveSATUnidad
                        where dpft.factura_id = :id");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":id", $value);
    $stmt->execute();

    $array = $stmt->fetchAll();

    foreach ($array as $r) {
      $claveInterna = ($r['clave'] !== "" && $r['clave'] !== null) ? $r['clave'] : "S/C";

      $unidadMedida = ($r['unidad_medida'] !== "" && $r['unidad_medida'] !== null) ? $r['unidad_medida'] : "N/A";

      $descripcion = "";
      if($r['numero_lote'] !== null && $r['numero_lote'] !== '' && $r['numero_lote'] !== 'null'){
        if($r['caducidad'] !== null && $r['caducidad'] !== '' && $r['caducidad'] !== '0000-00-00'){
          $descripcion = $r['nombre'] . "<br> Lote: " . $r['numero_lote'] . "<br> Caducidad: " . $r['caducidad'];
        } else {
          $descripcion = $r['nombre'] . "<br> Lote: " . $r['numero_lote'];
        }
      } else if($r['numero_serie'] !== null && $r['numero_serie'] !== '' && $r['numero_serie'] !== 'null'){
        $descripcion = $r['nombre'] . "<br> Serie: " . $r['numero_serie'];
      } else if($r['nombre'] !== "Predeterminado"){
        $descripcion = $r['nombre'];
      } else {
        $descripcion = "Venta";
      }

      

      $table .= '{
          "clave":"' . $claveInterna . '",
          "descripcion":"' . $descripcion . '",
          "unidad_medida":"' . $unidadMedida . '",
          "cantidad":"' . $r['cantidad'] . '",
          "precio":"$ ' . number_format($r['precio'], 2) . '",
          "importe":"$ ' . number_format(($r['subtotal']), 2) . '"
        },';
    }

    $table = substr($table, 0, strlen($table) - 1);

    $con = "";
    $stmt = "";
    $db = "";

    return '{"data":[' . $table . ']}';
  }

  function getInvoiceDetail($value)
  {
    $con = new conectar();
    $db = $con->getDb();
    $envVariables = GetEvn();
    $appUrl = $envVariables['server'];
    $getData = new get_data();
    $data = [];

    $query = sprintf("select total_facturado total from facturacion where id = :id");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":id", $value);
    $stmt->execute();
    $total = $stmt->fetchAll();

    $query = sprintf("select
                          ft.id,
                          ft.tipo,
                          ft.referencia,
                          concat(ft.serie,' ',ft.folio) serie_folio,
                          ft.fecha_timbrado,
                          ft.fecha_cancelacion,
                          ft.fecha_vencimiento,
                          ft.estatus,
                          cl.PKCliente,
                          cl.razon_social,
                          cl.rfc,         
                          ef.Estado estado,
                          sum(dpft.subtotal) subtotal,
                          concat(e.Nombres,' ',e.PrimerApellido,' ',e.SegundoApellido) vendedor,
                          ft.empleado_id vendedor_id,
                          ft.metodo_pago,
                          u.nombre
                        from detalle_facturacion dpft
                          inner join facturacion ft on dpft.factura_id = ft.id
                          left join clientes cl on ft.cliente_id = cl.PKCliente
                          left join empleados e on ft.empleado_id = e.PKEmpleado
                          left join estados_federativos ef on cl.estado_id = ef.PKEstado
                          left join usuarios u on ft.usuario_timbro_id = u.id
                        where factura_id = :id");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":id", $value);
    $stmt->execute();
    $client = $stmt->fetchAll();
    

    $query = sprintf("select
                          dpft.cantidad,
                          dpft.iva,
                          dpft.importe_iva,
                          dpft.ieps,
                          dpft.importe_ieps,
                          dpft.ieps_monto_fijo,
                          dpft.ish,
                          dpft.importe_ish,
                          dpft.iva_exento,
                          dpft.importe_iva_exento,
                          dpft.iva_retenido,
                          dpft.importe_iva_retenido,
                          dpft.isr,
                          dpft.importe_isr,
                          dpft.isn_local,
                          dpft.importe_isn_local,
                          dpft.cedular,
                          dpft.importe_cedular,
                          dpft.al_millar,
                          dpft.importe_al_millar,
                          dpft.funcion_publica,
                          dpft.importe_funcion_publica,
                          dpft.ieps_retenido,
                          dpft.importe_ieps_retenido,
                          dpft.isr_exento,
                          dpft.importe_isr_exento,
                          dpft.isr_monto_fijo,
                          dpft.isr_retenido,
                          dpft.importe_isr_retenido,
                          dpft.ieps_exento,
                          dpft.importe_ieps_exento,
                          dpft.isr_retenido_monto_fijo,
                          dpft.ieps_retenido_monto_fijo
                        from detalle_facturacion dpft
                        where factura_id = :id");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":id", $value);
    $stmt->execute();

    $impuestos_aux = $stmt->fetchAll();
    $impuestos = "";
    $importe_impuestos = 0;
    $impuestos_aux1 = [];

    foreach ($impuestos_aux as $r) {
      if ($r['iva'] !== "" && $r['iva'] !== null) {
        array_push(
          $impuestos_aux1,
          array(
            "impuesto" => 1,
            "tasa" => $r['iva'],
            "importe" => (float)$r['importe_iva'],
            "cantidad" => $r['cantidad']
          )
        );
      }
      if ($r['ieps'] !== "" && $r['ieps'] !== null) {
        array_push(
          $impuestos_aux1,
          array(
            "impuesto" => 2,
            "tasa" => $r['ieps'],
            "importe" => (float)$r['importe_ieps'],
            "cantidad" => $r['cantidad']
          )
        );
      }
      if ($r['ieps_monto_fijo'] !== "" && $r['ieps_monto_fijo'] !== null) {
        array_push(
          $impuestos_aux1,
          array(
            "impuesto" => 3,
            "tasa" => $r['ieps_monto_fijo'],
            "importe" => (float)($r['ieps_monto_fijo']),
            "cantidad" => $r['cantidad']
          )
        );
      }
      if ($r['ish'] !== "" && $r['ish'] !== null) {
        array_push(
          $impuestos_aux1,
          array(
            "impuesto" => 4,
            "tasa" => $r['ish'],
            "importe" => (float)$r['importe_ish'],
            "cantidad" => $r['cantidad']
          )
        );
      }
      if ($r['iva_exento'] !== "" && $r['iva_exento'] !== null) {
        array_push(
          $impuestos_aux1,
          array(
            "impuesto" => 5,
            "tasa" => $r['iva_exento'],
            "importe" => (float)$r['importe_iva_exento'],
            "cantidad" => $r['cantidad']
          )
        );
      }
      if ($r['iva_retenido'] !== "" && $r['iva_retenido'] !== null) {
        array_push(
          $impuestos_aux1,
          array(
            "impuesto" => 6,
            "tasa" => $r['iva_retenido'],
            "importe" => (float)$r['importe_iva_retenido'],
            "cantidad" => $r['cantidad']
          )
        );
      }
      if ($r['isr'] !== "" && $r['isr'] !== null) {
        array_push(
          $impuestos_aux1,
          array(
            "impuesto" => 7,
            "tasa" => $r['isr'],
            "importe" => (float)$r['importe_isr'],
            "cantidad" => $r['cantidad']
          )
        );
      }
      if ($r['isn_local'] !== "" && $r['isn_local'] !== null) {
        array_push(
          $impuestos_aux1,
          array(
            "impuesto" => 8,
            "tasa" => $r['isn_local'],
            "importe" => (float)$r['importe_isn_local'],
            "cantidad" => $r['cantidad']
          )
        );
      }
      if ($r['cedular'] !== "" && $r['cedular'] !== null) {
        array_push(
          $impuestos_aux1,
          array(
            "impuesto" => 9,
            "tasa" => $r['cedular'],
            "importe" => (float)$r['importe_cedular'],
            "cantidad" => $r['cantidad']
          )
        );
      }
      if ($r['al_millar'] !== "" && $r['al_millar'] !== null) {
        array_push(
          $impuestos_aux1,
          array(
            "impuesto" => 10,
            "tasa" => $r['al_millar'],
            "importe" => (float)$r['importe_al_millar'],
            "cantidad" => $r['cantidad']
          )
        );
      }
      if ($r['funcion_publica'] !== "" && $r['funcion_publica'] !== null) {
        array_push(
          $impuestos_aux1,
          array(
            "impuesto" => 11,
            "tasa" => $r['funcion_publica'],
            "importe" => (float)$r['importe_funcion_publica'],
            "cantidad" => $r['cantidad']
          )
        );
      }
      if ($r['ieps_retenido'] !== "" && $r['ieps_retenido'] !== null) {
        array_push(
          $impuestos_aux1,
          array(
            "impuesto" => 12,
            "tasa" => $r['ieps_retenido'],
            "importe" => (float)$r['importe_ieps_retenido'],
            "cantidad" => $r['cantidad']
          )
        );
      }
      if ($r['isr_exento'] !== "" && $r['isr_exento'] !== null) {
        array_push(
          $impuestos_aux1,
          array(
            "impuesto" => 13,
            "tasa" => $r['isr_exento'],
            "importe" => (float)$r['importe_isr_exento'],
            "cantidad" => $r['cantidad']
          )
        );
      }
      if ($r['isr_monto_fijo'] !== "" && $r['isr_monto_fijo'] !== null) {
        array_push(
          $impuestos_aux1,
          array(
            "impuesto" => 14,
            "tasa" => $r['isr_monto_fijo'],
            "importe" => (float)$r['isr_monto_fijo'],
            "cantidad" => $r['cantidad']
          )
        );
      }
      if ($r['isr_retenido'] !== "" && $r['isr_retenido'] !== null) {
        array_push(
          $impuestos_aux1,
          array(
            "impuesto" => 15,
            "tasa" => $r['isr'],
            "importe" => (float)$r['importe_isr_retenido'],
            "cantidad" => $r['cantidad']
          )
        );
      }
      if ($r['ieps_exento'] !== "" && $r['ieps_exento'] !== null) {
        array_push(
          $impuestos_aux1,
          array(
            "impuesto" => 16,
            "tasa" => $r['ieps_exento'],
            "importe" => (float)$r['importe_ieps_exento'],
            "cantidad" => $r['cantidad']
          )
        );
      }
      if ($r['isr_retenido_monto_fijo'] !== "" && $r['isr_retenido_monto_fijo'] !== null) {
        array_push(
          $impuestos_aux1,
          array(
            "impuesto" => 17,
            "tasa" => $r['isr_retenido_monto_fijo'],
            "importe" => (float)$r['isr_retenido_monto_fijo'],
            "cantidad" => $r['cantidad']
          )
        );
      }
      if ($r['ieps_retenido_monto_fijo'] !== "" && $r['ieps_retenido_monto_fijo'] !== null) {
        array_push(
          $impuestos_aux1,
          array(
            "impuesto" => 18,
            "tasa" => $r['ieps_retenido_monto_fijo'],
            "importe" => (float)$r['ieps_retenido_monto_fijo'],
            "cantidad" => $r['cantidad']
          )
        );
      }
    }

    for ($i = 0; $i < count($impuestos_aux1); $i++) {
      for ($j = $i + 1; $j < count($impuestos_aux1); $j++) {
        if ($impuestos_aux1[$i]['tasa'] == $impuestos_aux1[$j]['tasa']) {
          $impuestos_aux1[$i]['importe'] = $impuestos_aux1[$i]['importe'] + $impuestos_aux1[$j]['importe'];
          $impuestos_aux1[$j]['importe'] = 0;
        }
      }
    }

    $impuestos_aux2 = [];

    foreach ($impuestos_aux1 as $r) {
      if ($r['importe'] !== 0) {
        array_push(
          $impuestos_aux2,
          array(
            "impuesto" => $r['impuesto'],
            "tasa" => $r["tasa"],
            "importe" => $r["importe"],
            "cantidad" => $r['cantidad']
          )
        );
      }
    }

    foreach ($impuestos_aux2 as $r) {
      switch ($r['impuesto']) {
        case 1:
          $impuestos .= "<span>IVA " . $r['tasa'] . "%: $ " . number_format($r['importe'], 2) . "</span><br>";
          break;
        case 2:
          $impuestos .= "<span>IEPS " . $r['tasa'] . "%: $ " . number_format($r['importe'], 2) . "</span><br>";
          break;
        case 3:
          $impuestos .= "<span>IEPS (Monto fijo) $ ".number_format($r['importe'], 2)." : $ " . number_format($r['importe'] * $r['cantidad'], 2) . "</span><br>";
          break;
        case 4:
          $impuestos .= "<span>>ISH " . $r['tasa'] . "%: $ " . number_format($r['importe'], 2) . "</span><br>";
          break;
        case 5:
          $impuestos .= "<span>IVA Exento " . $r['tasa'] . "%: $ " . number_format($r['importe'], 2) . "</span><br>";
          break;
        case 6:
          $impuestos .= "<span>IVA Retenido " . $r['tasa'] . "%: $ " . number_format($r['importe'], 2) . "</span><br>";
          break;
        case 7:
          $impuestos .= "<span>ISR Retenido " . $r['tasa'] . "%: $ " . number_format($r['importe'], 2) . "</span><br>";
          break;
        case 8:
          $impuestos .= "<span>ISN (Local) " . $r['tasa'] . "%: $ " . number_format($r['importe'], 2) . "</span><br>";
          break;
        case 9:
          $impuestos .= "<span>Cedular " . $r['tasa'] . "%: $ " . number_format($r['importe'], 2) . "</span><br>";
          break;
        case 10:
          $impuestos .= "<span>5 al millar (Local) " . $r['tasa'] . "%: $ " . number_format($r['importe'], 2) . "</span><br>";
          break;
        case 11:
          $impuestos .= "<span>Función Pública " . $r['tasa'] . "%: $ " . number_format($r['importe'], 2) . "</span><br>";
          break;
        case 12:
          $impuestos .= "<span>IEPS Retenido " . $r['tasa'] . "%: $ " . number_format($r['importe'], 2) . "</span><br>";
          break;
        case 13:
          $impuestos .= "<span>ISR Exento " . $r['tasa'] . "%: $ " . number_format($r['importe'], 2) . "</span><br>";
          break;
        case 14:
          $impuestos .= "<span>ISR (Monto fijo) : $ " . number_format($r['importe'], 2) . "</span><br>";
          break;
        case 15:
          $impuestos .= "<span>ISR Retenido " . $r['tasa'] . "%: $ " . number_format($r['importe'], 2) . "</span><br>";
          break;
        case 16:
          $impuestos .= "<span>IEPS Exento " . $r['tasa'] . "%: $ " . number_format($r['importe'], 2) . "</span><br>";
          break;
        case 17:
          $impuestos .= "<span>ISR Retenido (Monto fijo) : $ " . number_format($r['importe'], 2) . "</span><br>";
          break;
        case 18:
          $impuestos .= "<span>IEPS Retenido (Monto fijo) $ " . number_format($r['tasa'],2) . " : $ " . number_format(($r['tasa'] * $r['cantidad']), 2) . "</span><br>";
          break;
      }
      $importe_impuestos += $r["importe"];
    }
    //$impuestos .= "</table>";
    switch ($client[0]['estatus']) {
      case 1:
        $estatus = "Pendiente de pago";
        break;
      case 2:
        $estatus = "Parcialmente pagada";
        break;
      case 3:
        $estatus = "Pagada";
        break;
      case 4:
        $estatus = "Cancelada";
        break;
      case 5:
        $estatus = "En proceso de cancelación";
        break;
    }

    switch ($client[0]['tipo']) {
      case 0:
        $vendedor = $client[0]['vendedor'] !== null && $client[0]['vendedor'] !== "" ? $client[0]['vendedor'] : "Sin vendedor";
        $referencia = '<p><b class="textBlue">Factura por concepto</b></p>';
        break;
      case 1:
        $vendedor =  $client[0]['vendedor'];
        $ref = json_decode($client[0]['referencia']);
        if(is_array($ref)){
          $ref = $ref[0];
        }
        $query1 = sprintf("select id_cotizacion_empresa from cotizacion where PKCotizacion = :id");
        $stmt1 = $db->prepare($query1);
        $stmt1->bindValue(":id",$ref);
        $stmt1->execute();
        $aux1 = $stmt1->fetchAll();
        $referencia = '<p><b class="textBlue">Cotización: </b>'. sprintf("%011d", $aux1[0]['id_cotizacion_empresa']).' </p>';
        break;
      case 2:
        $vendedor =  $client[0]['vendedor'];
        $ref = json_decode($client[0]['referencia']);
        if(is_array($ref)){
          $ref = $ref[0];
        } 
        
        $query1 = sprintf("select Referencia from ventas_directas where PKVentaDirecta = :id");
        $stmt1 = $db->prepare($query1);
        $stmt1->bindValue(":id",$ref);
        $stmt1->execute();
        $aux1 = $stmt1->fetchAll();
        $referencia = '<p><b class="textBlue">Venta directa: </b>'. $aux1[0]['Referencia'].' </p>';
        break;
      case 3:
        $vendedor = $client[0]['vendedor'];
        $ref_aux = json_decode($client[0]['referencia']);
        //$referencia = "Salidas: " . implode(", ",$ref_aux);
        $referencia = '<p><b class="textBlue">Salidas: </b>'. $client[0]['referencia'].' </p>';
        break;
      case 4:
        $vendedor = $client[0]['vendedor'] !== null && $client[0]['vendedor'] !== "" ? $client[0]['vendedor'] : "Traslado";
        $query = sprintf("select folio from remisiones where id = :id");
        $stmt = $db->prepare($query);
        $stmt->execute([':id'=>$client[0]['referencia']]);
        $arr_ref = $stmt->fetch();
        $referencia = '<p><b class="textBlue">Remisión: </b>'. sprintf("%011d", $arr_ref['folio']).' </p>';
        break;
      case 5:
        $vendedor = $client[0]['nombre'] !== null && $client[0]['nombre'] !== "" ? $client[0]['nombre'] : "Sin vendedor";
        $query = sprintf("select folio from ticket_punto_venta where id = :id");
        $stmt = $db->prepare($query);
        $stmt->execute([':id'=>$client[0]['referencia']]);
        $arr_ref = $stmt->fetch();
        //$text_ref = gettype($arr_ref['folio']);
        // $aux_ref1 = json_decode($arr_ref['folio']);
        // for ($i=0; $i < count($aux_ref1); $i++) { 
        //   $text_ref .= $aux_ref1[$i] .", ";
        // }

        // $text_ref = substr($text_ref, 0, strlen($text_ref) - 2);
        $referencia = '<p><b class="textBlue">Ticket punto de venta </b></p>';
        break;
      }
      
     
      $fecha_vencimiento = $client[0]['fecha_vencimiento'] !== null && $client[0]['fecha_vencimiento'] !== "" && $client[0]['fecha_vencimiento'] !== "0000-00-00" && $client[0]['fecha_vencimiento'] !== "Sin fecha" ? date("d-m-Y",strtotime($client[0]['fecha_vencimiento'])) : "Sin fecha";

      $fecha_cancelacion = $client[0]['fecha_cancelacion'] !== null && $client[0]['fecha_cancelacion'] !== "" && $client[0]['fecha_cancelacion'] !== "0000-00-00" ? date("d-m-Y",strtotime($client[0]['fecha_cancelacion'])) : "";
      $razon_social = '<p><b class="textBlue">Razón social: </b><a style="cursor:pointer" href="'.$appUrl.'catalogos/clientes/catalogos/clientes/detalles_cliente.php?c='.$client[0]['PKCliente'].'">'.$client[0]['razon_social'].'</a></p>';
      $data = [
        "serie_folio" => $client[0]['serie_folio'],
        "fecha_timbrado" => date("d-m-Y H:i:s",strtotime($client[0]['fecha_timbrado'])),
        "fecha_cancelacion" => $fecha_cancelacion,
        "fecha_vencimiento" => $fecha_vencimiento,
        "cliente_id" => $client[0]['PKCliente'],
        "razon_social" => $razon_social,
        "referencia" => $referencia,
        "estatus" => $estatus,
        "id_estatus" => $client[0]['estatus'],
        "rfc" => $client[0]['rfc'],
        "estado" =>  $client[0]['estado'],
        "vendedor_id" => $client[0]['vendedor_id'],
        "vendedor" => $vendedor,
        "subtotal" => "$ " . number_format($client[0]['subtotal'],2),
        "impuestos"=> $impuestos,
        "metodo_pago"=> $client[0]['metodo_pago'],
        "total"=>"$ " . number_format($total[0]['total'],2)
      ];

      return $data;
    
    
  }

  function getInvoicesRelations($value)
  {
    $con = new conectar();
    $db = $con->getDb();

    $query = sprintf("select id, concat(serie,' ',folio, ': $ ',total_facturado) data from facturacion where empresa_id = :empresa_id and cliente_id = :cliente_id and prefactura = 0");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":empresa_id",$_SESSION['IDEmpresa']);
    $stmt->bindValue(":cliente_id",$value);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }

  function getFolioSerie()
  {
    $con = new conectar();
    $db = $con->getDb();

    $query = sprintf("select serie_inicial serie, folio_inicial folio from empresas where PKEmpresa = :id");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":id", $_SESSION['IDEmpresa']);
    $stmt->execute();

    $serieFolio = $stmt->fetchAll();

    $query = sprintf("select id, serie, folio from facturacion where empresa_id = :id and serie <> '' order by folio desc LIMIT 1");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":id", $_SESSION['IDEmpresa']);
    $stmt->execute();

    $arr = $stmt->fetchAll();

    if (count($arr) > 0) 
    {
      $serie = $arr[0]['serie'];
      $folio = str_pad(($arr[0]['folio'] + 1), 5, "0", STR_PAD_LEFT);
    } else 
    {
      $serie =  $serieFolio[0]['serie'];
      $folio = str_pad($serieFolio[0]['folio'], 5, "0", STR_PAD_LEFT);
    }

    $seriefolio = [
      "serie" => $serie,
      "folio" => $folio
    ];

    return $seriefolio;
  }

  function getFolioSeriePreinvoice($value){
    $con = new conectar();
    $db = $con->getDb();

    $query = sprintf("select folio_prefactura from facturacion where id = :id");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":id",$value);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }

  function getCfdiUse()
  {
    $con = new conectar();
    $db = $con->getDb();

    $query = sprintf("SELECT id, CONCAT(clave,' - ',descripcion) texto FROM uso_cfdi");
    $stmt = $db->prepare($query);
    $stmt->execute();

    $con = "";
    $db = "";

    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }

  function getClienteCotizaciones()
  {
    $con = new conectar();
    $db = $con->getDb();
    $clientes = [];

    $query0 = sprintf("select DISTINCT cl.PKCliente id, cl.razon_social texto from cotizacion co
                          inner join sucursales s on co.FKSucursal = s.id
                          inner join clientes cl on co.FKCliente = cl.PKCliente
                          where co.empresa_id = :empresa_id AND s.activar_inventario = 0 and estatus_factura_id = 3 and estatus_cotizacion_id = 1
                        ");
    $stmt0 = $db->prepare($query0);
    $stmt0->bindValue(":empresa_id", $_SESSION["IDEmpresa"]);
    $stmt0->execute();
    $inv = $stmt0->fetchAll();

    $query = sprintf("select distinct cl.PKCliente id, cl.razon_social texto from cotizacion co
                        inner join clientes cl on co.FKCliente = cl.PKCliente
                        where co.empresa_id = :empresa_id and estatus_factura_id = 4 and estatus_cotizacion_id = 1
                      ");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":empresa_id", $_SESSION["IDEmpresa"]);
    $stmt->execute();
    $cot = $stmt->fetchAll();

    $array = array_merge($cot, $inv);

    foreach ($array as $i => $r) {
      if (!in_array($r, $clientes)) {
        array_push($clientes, $r);
        //$clientes[$i] = $r;
      }
    }


    return $clientes;
  }

  function getCotizaciones($value)
  {
    $con = new conectar();
    $db = $con->getDb();
    $aux = [];
    $array = [];
    $query = sprintf("select DISTINCT co.PKCotizacion id, co.id_cotizacion_empresa texto from cotizacion co
                        inner join sucursales s on co.FKSucursal = s.id
                        inner join clientes cl on co.FKCliente = cl.PKCliente
                        where co.empresa_id = :empresa_id AND s.activar_inventario = 0 AND co.FKCliente = :cliente and estatus_factura_id = 3 and estatus_cotizacion_id = 1
                      ");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":empresa_id", $_SESSION["IDEmpresa"]);
    $stmt->bindValue(":cliente", $value);
    $stmt->execute();

    $inv = $stmt->fetchAll();

    $query = sprintf("SELECT PKCotizacion id, id_cotizacion_empresa texto FROM cotizacion 
                        WHERE empresa_id = :empresa_id and estatus_factura_id = 4 and estatus_cotizacion_id = 1 AND FKCliente = :cliente");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":empresa_id", $_SESSION['IDEmpresa']);
    $stmt->bindValue(":cliente", $value);
    $stmt->execute();

    $cot =  $stmt->fetchAll();

    $array = array_merge($cot, $inv);

    for ($i = 0; $i < count($array); $i++) {
      $id_cotizacion_empresa = sprintf("%011d", $array[$i]['texto']);
      $aux[$i] = [
        'id' => $array[$i]['id'],
        'texto' => $id_cotizacion_empresa
      ];
    }

    $con = "";
    $stmt = "";
    $db = "";

    return $array;
  }

  function getCotizacion($value)
  {
    $con = new conectar();
    $db = $con->getDb();

    $query = sprintf("SELECT 
                          c.PKCotizacion,
                          c.id_cotizacion_empresa,
                          c.Subtotal,
                          c.ImporteTotal,
                          cl.PKCliente,
                          cl.razon_social,
                          cl.rfc,
                          cl.regimen_fiscal_id,
                          c.NotaCliente,
                          c.NotaInterna,
                          s.id sucursal_id
                        FROM cotizacion c
                        INNER JOIN clientes cl ON c.FKCliente = cl.PKCliente
                        INNER JOIN sucursales s ON c.FKSucursal = s.id
                        WHERE c.PKCotizacion = :id");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":id", $value);
    $stmt->execute();

    $aux = $stmt->fetchAll(PDO::FETCH_OBJ);

    $id_cotizacion_empresa = sprintf("%011d", $aux[0]->id_cotizacion_empresa);
    $total = number_format($aux[0]->ImporteTotal, 2);

    $array[] = [
      'id' => $aux[0]->PKCotizacion,
      'sucursal_id' =>  $aux[0]->sucursal_id,
      'referencia' => $id_cotizacion_empresa,
      'subtotal' => $aux[0]->Subtotal,
      'total' => $total,
      'razon_social' => $aux[0]->razon_social,
      'PKCliente' => $aux[0]->PKCliente,
      'rfc' => $aux[0]->rfc,
      'regimen_fiscal_id' => $aux[0]->regimen_fiscal_id,
      'cliente' => $value,
      'nota_cliente'=>$aux[0]->NotaCliente,
      'nota_internas'=>$aux[0]->NotaInterna
    ];

    $con = "";
    $stmt = "";
    $db = "";

    return $array;
  }

  function getFormasPago()
  {
    $con = new conectar();
    $db = $con->getDb();

    $query = sprintf("SELECT id, CONCAT(clave,' - ',descripcion) texto FROM formas_pago_sat");
    $stmt = $db->prepare($query);
    $stmt->execute();

    $con = "";
    $db = "";

    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }

  function getMonedas()
  {
    $con = new conectar();
    $db = $con->getDb();

    $query = sprintf("SELECT PKMoneda id, Clave texto FROM monedas WHERE Estatus = 1");
    $stmt = $db->prepare($query);
    $stmt->execute();

    $con = "";
    $db = "";

    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }

  function getClienteVentasDirectas()
  {
    $con = new conectar();
    $db = $con->getDb();
    $clientes = [];

    $query = sprintf("SELECT DISTINCT cl.PKCliente id, cl.razon_social texto FROM ventas_directas vd
                          INNER JOIN sucursales s on vd.FKSucursal = s.id
                          INNER JOIN clientes cl ON vd.FKCliente = cl.PKCliente
                        WHERE vd.empresa_id = :empresa_id AND s.activar_inventario = 0 AND vd.estatus_factura_id = 3 AND vd.FKEstatusVenta = 1");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":empresa_id", $_SESSION["IDEmpresa"]);
    $stmt->execute();

    $inv = $stmt->fetchAll();

    $query = sprintf("SELECT DISTINCT cl.PKCliente id, cl.razon_social texto FROM ventas_directas vd
                        INNER JOIN clientes cl ON vd.FKCliente = cl.PKCliente
                        WHERE vd.empresa_id = :empresa_id AND vd.estatus_factura_id = 4 AND vd.FKEstatusVenta = 1");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":empresa_id", $_SESSION["IDEmpresa"]);
    $stmt->execute();

    $vd = $stmt->fetchAll();

    $array = array_merge($vd, $inv);

    foreach ($array as $i => $r) {
      if (!in_array($r, $clientes)) {
        array_push($clientes, $r);
      }
    }

    return $clientes;
  }

  function getVentasDirectas($value)
  {
    $con = new conectar();
    $db = $con->getDb();

    $query = sprintf("SELECT PKVentaDirecta id, Referencia texto FROM ventas_directas WHERE empresa_id = :empresa_id AND FKEstatusVenta = 1 AND estatus_factura_id = 4 AND FKCliente = :cliente");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":empresa_id", $_SESSION['IDEmpresa'], PDO::PARAM_INT);
    $stmt->bindValue(":cliente", $value);
    $stmt->execute();

    $con = "";
    $db = "";

    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }

  function getVentaDirecta($value)
  {
    $con = new conectar();
    $db = $con->getDb();

    $query = sprintf("SELECT vd.PKVentaDirecta id,
                              vd.referencia,
                              CONCAT(FORMAT(vd.Importe,2)) total,
                              CONCAT(FORMAT(vd.Subtotal,2)) subtotal,
                              c.razon_social,
                              c.rfc,
                              c.regimen_fiscal_id,
                              c.PKCliente,
                              vd.NotasCliente nota_cliente,
                              vd.NotasInternas nota_internas,
                              vd.FKSucursal sucursal_id
                        FROM ventas_directas vd
                        INNER JOIN clientes c ON vd.FKCliente = c.PKCliente
                        WHERE vd.PKVentaDirecta = :id
                      ");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":id", $value, PDO::PARAM_INT);
    $stmt->execute();

    $con = "";
    $db = "";

    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }

  function getRemision($value)
  {
    $con = new conectar();
    $db = $con->getDb();
    $array = [];

    $query = sprintf("select 
                          r.id,
                          r.folio referencia,
                          concat(FORMAT(r.subtotal,2)) total,
                          concat(FORMAT(r.total,2)) subtotal,
                          c.razon_social,
                          c.rfc,
                          c.PKCliente,
                          c.regimen_fiscal_id
                        from remisiones r
                        inner join clientes c on r.cliente_id = c.PKCliente
                        where r.id = :id
                      ");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":id", $value, PDO::PARAM_INT);
    $stmt->execute();

    $con = "";
    $db = "";

    $aux = $stmt->fetchAll();

    foreach ($aux as $r) {
      array_push(
        $array,
        array(
          "id" => $r['id'],
          "referencia" => sprintf("%011d", $r['referencia']),
          "total" => $r['total'],
          "subtotal" => $r['subtotal'],
          "razon_social" => $r['razon_social'],
          "rfc" => $r['rfc'],
          "PKCliente" => $r['PKCliente'],
          "regimen_fiscal_id" => $r['regimen_fiscal_id']
        )
      );
    }

    return $array;
  }

  function getClientePedidos()
  {
    $con = new conectar();
    $db = $con->getDb();

    $query = sprintf("SELECT DISTINCT cl.PKCliente id,cl.razon_social texto FROM orden_pedido_por_sucursales opps
                        INNER JOIN clientes cl ON opps.cliente_id = cl.PKCliente
                        WHERE opps.empresa_id = :empresa_id and 
                        opps.cliente_id <> 0 and
                        (opps.estatus_orden_pedido_id = 3 or
                        opps.estatus_orden_pedido_id = 5)");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":empresa_id", $_SESSION['IDEmpresa']);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }

  function getOrdenesPedido($value)
  {
    $con = new conectar();
    $db = $con->getDb();

    $query = sprintf("SELECT opps.id,opps.id_orden_pedido_empresa FROM orden_pedido_por_sucursales opps
                        WHERE opps.empresa_id = :empresa_id AND 
                        opps.cliente_id = :cliente and
                        (opps.estatus_orden_pedido_id = 3 or
                        opps.estatus_orden_pedido_id = 5) and estatus_factura_id != 1 order by id_orden_pedido_empresa asc");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":empresa_id", $_SESSION['IDEmpresa']);
    $stmt->bindValue(":cliente", $value);
    $stmt->execute();

    $array =  $stmt->fetchAll(PDO::FETCH_OBJ);

    if (count($array) > 0) {
      for ($i = 0; $i < count($array); $i++) {
        $id_orden_pedido_empresa = sprintf("%011d", $array[$i]->id_orden_pedido_empresa);
        $aux[$i] = [
          'id' => $array[$i]->id,
          'texto' => $id_orden_pedido_empresa
        ];
      }
    } else {
      $aux = "";
    }

    $con = "";
    $stmt = "";
    $db = "";

    return $aux;
  }

  function getOrdenPedido($value)
  {
    $con = new conectar();
    $db = $con->getDb();
    
    $data = json_decode($value);
    $empresa = $_SESSION["IDEmpresa"];
    
    $query = sprintf("select distinct * from (
                        select distinct 
                          numero_cotizacion,
                          numero_venta_directa 
                        from orden_pedido_por_sucursales o
                          inner join inventario_salida_por_sucursales s on o.id = s.orden_pedido_id
                        where s.folio_salida = :salida and o.empresa_id = :empresa
                        
                        union
    
                        select distinct
                          numero_cotizacion,
                          numero_venta_directa 
                        from orden_pedido_por_sucursales o
                          inner join movimientos_salidas_servicios_sin_inventario ssi on o.id = ssi.FKOrdenPedido
                        where ssi.FKSalida = :salida2 and o.empresa_id = :empresa2) as tabla");
    $stmt = $db->prepare($query);
    $stmt->execute([":salida"=>$data[0], ":salida2"=>$data[0], ":empresa"=>$empresa, ":empresa2"=>$empresa]);

    $arr = $stmt->fetchAll(PDO::FETCH_OBJ);
    //$id = $arr[0]->numero_cotizacion !== null && ($arr[0]->numero_cotizacion !== "" ? $arr[0]->numero_cotizacion !== null : ;
    if($arr[0]->numero_cotizacion !== null && $arr[0]->numero_cotizacion !== ""){
      $query = sprintf("select
                          c.PKCliente,
                          c.regimen_fiscal_id,
                          c.razon_social,
                          c.rfc,
                          co.NotaCliente nota_cliente,
                          co.NotaInterna nota_interna
                        from cotizacion co
                          inner join clientes c on co.FKCliente = c.PKCliente
                        where co.PKCotizacion = :id
                      ");
      $id = $arr[0]->numero_cotizacion;
    } else {
      $query = sprintf("select
                          c.PKCliente,
                          c.regimen_fiscal_id,
                          c.razon_social,
                          c.rfc,
                          vd.NotasCliente nota_cliente,
                          vd.NotasInternas nota_interna
                        from ventas_directas vd
                          inner join clientes c on vd.FKCliente = c.PKCliente
                        where vd.PKVentaDirecta = :id");
      $id = $arr[0]->numero_venta_directa;
    }
   
    $stmt = $db->prepare($query);
    $stmt->bindValue(":id", $id, PDO::PARAM_INT);
    $stmt->execute();

    $aux = $stmt->fetchAll(PDO::FETCH_OBJ);
    
    $array[] = [
      'referencia' => $value,
      'PKCliente' => $aux[0]->PKCliente,
      'regimen_fiscal_id' => $aux[0]->regimen_fiscal_id,
      'razon_social' => $aux[0]->razon_social,
      'rfc' => $aux[0]->rfc,
      'nota_cliente' => $aux[0]->nota_cliente,
      'nota_internas' => $aux[0]->nota_interna
    ];

    $con = "";
    $stmt = "";
    $db = "";

    return $array;
  }

  function getVentasOrigen($value)
  {
    $con = new conectar();
    $getData = new get_data();

    $db = $con->getDb();
    $empresa = $_SESSION["IDEmpresa"];

    $data = json_decode($value);
    $array = [];
    //$arrayFS = [];
    $arrayTotalSalidas = [];
    
    for ($i = 0; $i < count($data); $i++) {
      $query = sprintf("select distinct * from (
                          select distinct 
                            numero_venta_directa 
                          from orden_pedido_por_sucursales o
                            inner join inventario_salida_por_sucursales s on o.id = s.orden_pedido_id
                          where s.folio_salida = :salida and o.empresa_id = :empresa
                          
                          union

                          select distinct
                            numero_venta_directa 
                          from orden_pedido_por_sucursales o
                            inner join movimientos_salidas_servicios_sin_inventario ssi on o.id = ssi.FKOrdenPedido
                          where ssi.FKSalida = :salida2 and o.empresa_id = :empresa2) as tabla");
      $stmt = $db->prepare($query);
      $stmt->execute([":salida"=>$data[$i], ":salida2"=>$data[$i], ":empresa"=>$empresa, ":empresa2"=>$empresa]);

      $arr = $stmt->fetchAll(PDO::FETCH_OBJ);
      if($arr[0]->numero_venta_directa != null && $arr[0]->numero_venta_directa != ''){
        if(!in_array($arr[0]->numero_venta_directa,$array)){
          array_push(
            $array,
            $arr[0]->numero_venta_directa
          );
        }

        //$arrayFS[$data[$i]] = $arr[0]->numero_venta_directa;
        
      }

      //calcula el total del pedido
      $TotalSubtotalSalida = $getData->getTotalSubtotalSalidas(0,'["'.$data[$i].'"]',3);
      isset($arrayTotalSalidas[$arr[0]->numero_venta_directa]) ? $arrayTotalSalidas[$arr[0]->numero_venta_directa] = number_format($arrayTotalSalidas[$arr[0]->numero_venta_directa] + floatval(str_replace("$ ", "", $TotalSubtotalSalida['total'])),2,'.','') : $arrayTotalSalidas[$arr[0]->numero_venta_directa] = floatval(str_replace("$", "", $TotalSubtotalSalida['total']));
    }
      
    $result['salidas'] = $array;
    //$result['fs'] = $arrayFS;
    $result['totalFS'] = $arrayTotalSalidas;

    return $result;
  }

  function getVentasPagos($value)
  {
    $con = new conectar();
    $db = $con->getDb();

    $data = json_decode($value);
    $array = [];
    
    for ($i = 0; $i < count($data); $i++) {
      $query = sprintf("SELECT v.estatus_cuentaCobrar, v.Referencia, sum(p.deposito) as pagado
                        from ventas_directas as v
                          inner join movimientos_cuentas_bancarias_empresa as p on p.id_factura=v.PKVentaDirecta and p.tipo_CuentaCobrar = 1 and p.estatus = 1
                        where v.PKVentaDirecta = :id and v.estatus_cuentaCobrar in (2,3)");
      $stmt = $db->prepare($query);
      $stmt->execute([":id"=>$data[$i]]);

      $arr = $stmt->fetchAll(PDO::FETCH_OBJ);
      $rows = $stmt->rowCount();

      if($rows <= 0){
        return $array;
      }
      
      if($arr[0]->estatus_cuentaCobrar != null && $arr[0]->estatus_cuentaCobrar != ''){
        array_push(
          $array,
          array(
            "PK" => $data[$i],
            "Estatus" => $arr[0]->estatus_cuentaCobrar,
            "Folio" => $arr[0]->Referencia,
            "Pagado" => $arr[0]->pagado
          )
        );
      }
    }

    return $array;
  }

  function getSalidas($value)
  {
    $con = new conectar();
    $db = $con->getDb();

    $query = sprintf("SELECT DISTINCT s.folio_salida texto 
                        FROM inventario_salida_por_sucursales s 
                        INNER JOIN orden_pedido_por_sucursales o ON s.orden_pedido_id = o.id
                        WHERE orden_pedido_id = :id AND s.estatus = 0
                        
                        union
                        
                        SELECT DISTINCT ms.FKSalida texto 
                        FROM movimientos_salidas_servicios_sin_inventario ms 
                        INNER JOIN orden_pedido_por_sucursales o ON ms.FKOrdenPedido = o.id
                        WHERE ms.FKOrdenPedido = :id2 AND ms.estatus = 0
    ;");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":id", $value);
    $stmt->bindValue(":id2", $value);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }

  function getUnidadesMedida()
  {
    $con = new conectar();
    $db = $con->getDb();

    $query = sprintf("SELECT PKClaveSATUnidad id, CONCAT(Clave,' - ',Descripcion) texto FROM claves_sat_unidades");
    $stmt = $db->prepare($query);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }

  function getImpuestos($value)
  {
    $con = new conectar();
    $db = $con->getDb();

    $query = sprintf("SELECT 
                        PKImpuesto id, 
                        Nombre texto 
                      FROM impuesto 
                      WHERE 
                          FKTipoImpuesto = :id and
                          PKImpuesto <> 13 and 
                          PKImpuesto <> 14 and 
                          PKImpuesto <> 15 and 
                          PKImpuesto <> 17
                      ORDER BY Nombre ASC");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":id", $value);

    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }

  function getFactorImpuestos($value)
  {
    $con = new conectar();
    $db = $con->getDb();
    $tipoImporte = [];
    $query = sprintf("SELECT FKTipoImporte id FROM impuesto WHERE PKImpuesto = :id");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":id", $value);

    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }

  function getClienteRemisiones()
  {
    $con = new conectar();
    $db = $con->getDb();

    $query = sprintf("SELECT DISTINCT cl.PKCliente id, cl.razon_social texto FROM remisiones r
                        INNER JOIN clientes cl ON r.cliente_id = cl.PKCliente
                        WHERE r.empresa_id = :empresa_id AND r.estatus = 0");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":empresa_id", $_SESSION["IDEmpresa"]);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }

  function getRemisiones($value)
  {
    $con = new conectar();
    $db = $con->getDb();
    $array = [];

    $query = sprintf("SELECT DISTINCT r.id, r.folio texto FROM remisiones r
                        WHERE r.empresa_id = :empresa AND r.cliente_id = :cliente_id AND r.estatus = 0");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":cliente_id", $value);
    $stmt->bindValue(":empresa", $_SESSION['IDEmpresa']);
    $stmt->execute();
    $aux = $stmt->fetchAll();

    foreach ($aux as $r) {
      array_push($array, array(
        "id" => $r['id'],
        "texto" => sprintf("%011d", $r['texto'])
      ));
    }

    return $array;
  }

  function getProductosRemisionTable($value)
  {
    $con = new conectar();
    $db = $con->getDb();
    $get_data = new get_data();
    $table = "";
    $impuestos = "";

    $query0 = sprintf("select * from datos_producto_facturacion_temp where usuario_id = :usuario_id and tipo = 4 and referencia = :ref");
    $stmt0 = $db->prepare($query0);
    $stmt0->bindValue(":usuario_id", $_SESSION['PKUsuario']);
    $stmt0->bindValue(":ref", $value);
    $stmt0->execute();
    $rowCount = $stmt0->rowCount();


    if ($rowCount > 0) {
      $get_data->getTruncateTableProducts(0,4,$value);
    }

    $query = sprintf("select
                          dr.remision_id id, 
                          pr.PKProducto producto_id,
                          pr.ClaveInterna clave,
                          pr.Nombre nombre,
                          ifp.FKClaveSATUnidad,
                          dr.cantidad,
                          dr.precio precio_unitario,
                          dr.subtotal,
                          dr.importe_total total,
                          ifp.FKClaveSAT,
                          CONCAT(csu.Clave,' - ',csu.Descripcion) sat_unidad
                        from detalle_remision dr
                        inner join productos pr on dr.producto_id = pr.PKProducto
                        left join info_fiscal_productos ifp on pr.PKProducto = ifp.FKProducto
                        left join claves_sat_unidades csu on ifp.FKClaveSATUnidad = csu.PKClaveSATUnidad
                        where dr.remision_id = :id");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":id", $value);
    $stmt->execute();

    $arr = $stmt->fetchAll();

    return $get_data->getDataDocumentation($arr,4,$value);
  }

  function getProductosCotizacionTable($value)
  {
    $con = new conectar();
    $db = $con->getDb();
    $get_data = new get_data();

    $query0 = sprintf("select * from datos_producto_facturacion_temp where usuario_id = :usuario_id and tipo = 1 and referencia = :ref");
    $stmt0 = $db->prepare($query0);
    $stmt0->bindValue(":usuario_id", $_SESSION['PKUsuario']);
    $stmt0->bindValue(":ref", $value);
    $stmt0->execute();
    $rowCount = $stmt0->rowCount();

    if ($rowCount > 0) {
      $get_data->getTruncateTableProducts(0,1,$value);
    }
    
    $query = sprintf("select 
                          co.PKCotizacion id,
                          pro.PKProducto producto_id,
                          pro.ClaveInterna clave,
                          pro.Nombre nombre,
                          ifp.FKClaveSATUnidad,
                          dco.Cantidad cantidad,
                          dco.Precio precio_unitario,
                          co.Subtotal subtotal,
                          co.ImporteTotal total,
                          ifp.FKClaveSAT,
                          CONCAT(csu.Clave,' - ',csu.Descripcion) sat_unidad from cotizacion co
                        inner join detalle_cotizacion dco on co.PKCotizacion = dco.FKCotizacion
                        inner join productos pro on dco.FKProducto = pro.PKProducto
                        left join info_fiscal_productos ifp on pro.PKProducto = ifp.FKProducto
                        left join claves_sat_unidades csu on ifp.FKClaveSATUnidad = csu.PKClaveSATUnidad
                        where co.PKCotizacion = :id");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":id", $value);
    $stmt->execute();

    $arr = $stmt->fetchAll();
    
    return $get_data->getDataDocumentation($arr,1,$value);    
  }

  function getProductosVentasTable($value)
  {
    $con = new conectar();
    $db = $con->getDb();
    $get_data = new get_data();
    
    $query0 = sprintf("select * from datos_producto_facturacion_temp where usuario_id = :usuario_id and tipo = 2 and referencia = :ref");
    $stmt0 = $db->prepare($query0);
    $stmt0->bindValue(":usuario_id", $_SESSION['PKUsuario']);
    $stmt0->bindValue(":ref", $value);
    $stmt0->execute();
    $rowCount = $stmt0->rowCount();

    if ($rowCount > 0) {
      $get_data->getTruncateTableProducts(0,2,$value);
    }
    
    $query = sprintf("select
                          vd.PKVentaDirecta id,
                          pro.PKProducto producto_id,
                          pro.ClaveInterna clave,
                          pro.Nombre nombre,
                          ifp.FKClaveSATUnidad,
                          dvd.Cantidad cantidad,
                          dvd.Precio precio_unitario,
                          vd.Importe total,
                          ifp.FKClaveSAT,
                          CONCAT(csu.Clave,' - ',csu.Descripcion) sat_unidad from ventas_directas vd
                        inner join detalle_venta_directa dvd on vd.PKVentaDirecta = dvd.FKVentaDirecta
                        inner join productos pro on dvd.FKProducto = pro.PKProducto
                        left join info_fiscal_productos ifp on pro.PKProducto = ifp.FKProducto
                        left join claves_sat_unidades csu on ifp.FKClaveSATUnidad = csu.PKClaveSATUnidad
                        where vd.PKVentaDirecta = :id");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":id", $value);
    $stmt->execute();

    $arr = $stmt->fetchAll();
    
    return $get_data->getDataDocumentation($arr,2,$value);
  }

  function getProductosPedidoTable($value)
  {
    $con = new conectar();
    $db = $con->getDb();
    $get_data = new get_data();
    
    $query0 = sprintf("select id from datos_producto_facturacion_temp where usuario_id = :usuario_id and tipo = 3 and referencia = :ref");
    $stmt0 = $db->prepare($query0);
    $stmt0->bindValue(":usuario_id", $_SESSION['PKUsuario']);
    $stmt0->bindValue(":ref", $value);
    $stmt0->execute();
    $rowCount = $stmt0->rowCount();

    if ($rowCount > 0) {
      $get_data->getTruncateTableProducts(0,3,$value);
    }
    $query = sprintf("select
                          op.id id,
                          pro.PKProducto producto_id,
                          pro.ClaveInterna clave,
                          pro.Nombre nombre,
                          ifp.FKClaveSATUnidad,
                          dop.cantidad_pedida cantidad,
                          dop.cantidad_surtida,
                          COALESCE(d_cot.Precio, d_vd.Precio) precio_unitario,
                          ifp.FKClaveSAT,
                          CONCAT(csu.Clave,' - ',csu.Descripcion) sat_unidad from orden_pedido_por_sucursales op
                        inner join detalle_orden_pedido_por_sucursales dop on op.id = dop.orden_pedido_id
                        inner join productos pro on dop.producto_id = pro.PKProducto
                        left join detalle_cotizacion d_cot on (op.numero_cotizacion = d_cot.FKCotizacion and pro.PKProducto = d_cot.FKProducto)
	                      left join detalle_venta_directa d_vd on (op.numero_venta_directa = d_vd.FKVentaDirecta and pro.PKProducto = d_vd.FKProducto)
                        left join info_fiscal_productos ifp on pro.PKProducto = ifp.FKProducto
                        left join claves_sat_unidades csu on ifp.FKClaveSATUnidad = csu.PKClaveSATUnidad
                        where op.id = :id");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":id", $value);
    $stmt->execute();

    $arr = $stmt->fetchAll();
    
    return $get_data->getDataDocumentation($arr,3,$value);
  }

  function getProductosSalidas($value)
  {
    $con = new conectar();
    $db = $con->getDb();
    $get_data = new get_data();
    
    $prod = [];
    $refer = [];
    
    $data = json_decode($value);
    
    for ($i = 0; $i < count($data); $i++) {

      $query = sprintf("select 
                            sa.id id,
                            pr.PKProducto producto_id,
                            sa.clave, 
                            pr.Descripcion, 
                            sa.numero_lote lote, 
                            sa.numero_serie serie,
                            sa.cantidad, 
                            sa.caducidad,
                            ifp.FKClaveSATUnidad,
                            COALESCE(dcot.Precio, dvd.Precio) precio_unitario,
                            ifp.FKClaveSAT
                          from inventario_salida_por_sucursales sa
                            inner join productos pr on sa.clave = pr.ClaveInterna
                            inner join orden_pedido_por_sucursales op on sa.orden_pedido_id = op.id
                            left join detalle_cotizacion dcot on (op.numero_cotizacion = dcot.FKCotizacion and pr.PKProducto = dcot.FKProducto)
                            left join detalle_venta_directa dvd on (op.numero_venta_directa = dvd.FKVentaDirecta and pr.PKProducto = dvd.FKProducto)
                            left join info_fiscal_productos ifp on pr.PKProducto = ifp.FKProducto
                            left join claves_sat_unidades csu on ifp.FKClaveSATUnidad = csu.PKClaveSATUnidad
                          where folio_salida = :folio and pr.empresa_id = :id_empresa
                          
                          union

                          select 
                            sa.PKMovServ id,
                            pr.PKProducto producto_id,
                            pr.ClaveInterna clave, 
                            pr.Descripcion, 
                            '' lote, 
                            '' serie,
                            sa.cantidad_surtida, 
                            '' caducidad,
                            ifp.FKClaveSATUnidad,
                            COALESCE(dcot.Precio, dvd.Precio) precio_unitario,
                            ifp.FKClaveSAT
                          from movimientos_salidas_servicios_sin_inventario sa
                            inner join productos pr on sa.FKProducto = pr.PKProducto
                            inner join inventario_salida_por_sucursales sal on sa.FKSalida = sal.folio_salida
                            inner join orden_pedido_por_sucursales op on sal.orden_pedido_id = op.id
                            left join detalle_cotizacion dcot on (op.numero_cotizacion = dcot.FKCotizacion and pr.PKProducto = dcot.FKProducto)
                            left join detalle_venta_directa dvd on (op.numero_venta_directa = dvd.FKVentaDirecta and pr.PKProducto = dvd.FKProducto)
                            left join info_fiscal_productos ifp on pr.PKProducto = ifp.FKProducto
                            left join claves_sat_unidades csu on ifp.FKClaveSATUnidad = csu.PKClaveSATUnidad
                          where sa.FKSalida = :folio2 and pr.empresa_id = :id_empresa2;
                          ");
      $stmt = $db->prepare($query);
      $stmt->bindValue(":folio", $data[$i]);
      $stmt->bindValue(":id_empresa", $_SESSION['IDEmpresa']);
      $stmt->bindValue(":folio2", $data[$i]);
      $stmt->bindValue(":id_empresa2", $_SESSION['IDEmpresa']);
      $stmt->execute();

      $aux = $stmt->fetchAll();
      
      foreach($aux as $r){
        
        $query0 = sprintf("SELECT * from datos_producto_facturacion_temp d
                          inner join (select s.id, s.folio_salida as folio, pr.PKProducto 
                                        from inventario_salida_por_sucursales s inner join productos pr on s.clave = pr.ClaveInterna
                                      where s.id = :ref and pr.empresa_id = :id_empresa
                                        union
                                      select ms.PKMovServ as id, ms.FKSalida as folio, ms.FKProducto as PKProducto from movimientos_salidas_servicios_sin_inventario ms where ms.PKMovServ = :ref2
                                      ) as sf on sf.id = :ref3 and d.producto_id = sf.PKProducto
                          where usuario_id = :usuario_id and tipo = 3 and referencia = :ref4 and sf.folio = :folio;");
        $stmt0 = $db->prepare($query0);
        $stmt0->bindValue(":usuario_id", $_SESSION['PKUsuario']);
        $stmt0->bindValue(":ref", $r['id']);
        $stmt0->bindValue(":id_empresa", $_SESSION['IDEmpresa']);
        $stmt0->bindValue(":ref2", $r['id']);
        $stmt0->bindValue(":ref3", $r['id']);
        $stmt0->bindValue(":ref4", $r['id']);
        $stmt0->bindValue(":folio", $data[$i]);

        $stmt0->execute();
        $rowCount = $stmt0->rowCount();
        
        if ($rowCount > 0) {
          $get_data->getTruncateTableProducts(0,3,$r['id'], $data[$i]);
        }

      }

      if (count($prod) > 0) {
        for ($k = 0; $k < count($aux); $k++) {
          if (in_array($aux[$k]['producto_id'], array_column($prod, 'producto_id'))) {
            if (in_array($aux[$k]['lote'], array_column($prod, 'lote'))) {
              $prod[array_search($aux[$k]['producto_id'], array_column($prod, 'producto_id'))]['cantidad'] += $aux[$k]['cantidad'];
            } else if (in_array($aux[$k]['serie'], array_column($prod, 'serie'))) {
              $prod[array_search($aux[$k]['producto_id'], array_column($prod, 'producto_id'))]['cantidad'] += $aux[$k]['cantidad'];
            } else {
              $prod[array_search($aux[$k]['producto_id'], array_column($prod, 'producto_id'))]['cantidad'] = $aux[$k]['cantidad'];
            }
          } else {
            array_push(
              $prod,
              array(
                "id" => $aux[$k]['id'],
                "producto_id" => $aux[$k]['producto_id'],
                "clave" => $aux[$k]['clave'],
                "lote" => $aux[$k]['lote'],
                "caducidad" => $aux[$k]['caducidad'],
                "serie" => $aux[$k]['serie'],
                "cantidad" => $aux[$k]['cantidad'],
                "FKClaveSATUnidad" => $aux[$k]['FKClaveSATUnidad'],
                "precio_unitario" => $aux[$k]['precio_unitario'],
                "FKClaveSAT" => $aux[$k]['FKClaveSAT'],
                "folio" => $data[$i]
              )
            );
          }
        }
      } else {
        for ($k = 0; $k < count($aux); $k++) {
          array_push(
            $prod,
            array(
              "id" => $aux[$k]['id'],
              "producto_id" => $aux[$k]['producto_id'],
              "clave" => $aux[$k]['clave'],
              "lote" => $aux[$k]['lote'],
              "caducidad" => $aux[$k]['caducidad'],
              "serie" => $aux[$k]['serie'],
              "cantidad" => $aux[$k]['cantidad'],
              "FKClaveSATUnidad" => $aux[$k]['FKClaveSATUnidad'],
              "precio_unitario" => $aux[$k]['precio_unitario'],
              "FKClaveSAT" => $aux[$k]['FKClaveSAT'],
              "folio" => $data[$i]
            )
          );
        }
      }
    }

    foreach ($prod as $r) {
     array_push(
       $refer,
       array(
         "id" => $r['id'],
         "folio" => $r['folio'])
      );
    }
    return $get_data->getDataDocumentation($prod,3,$refer);
  }

  function getProductosEditTable($array)
  {
    $con = new conectar();
    $db = $con->getDb();
    $table = "";
    $cont = 0;
	
	  $data = json_decode($array);
	
    $cond_query = ($data->factura_concepto === "0") ? "id = :id_row and usuario_id = :usuario_id and tipo = :tipo and referencia = :ref and factura_concepto = :value" : "id = :id_row and tipo = :tipo and referencia = :ref and usuario_id = :usuario_id and factura_concepto = :value";

    $query = sprintf("select 
                          dpft.id id_row,
                          dpft.referencia,
                          pr.PKProducto id, 
                          pr.ClaveInterna clave,
                          pr.Nombre nombre,
                          dpft.unidad_medida_id id_unidad_medida,
                          csu.Descripcion unidad_medida,
                          dpft.clave_sat_id sat_id,
                          dpft.descuento_tasa,
                          dpft.importe_descuento_tasa,
                          dpft.descuento_monto_fijo,
                          dpft.cantidad cantidad_total,
                          dpft.cantidad_facturada cantidad_facturada,
                          dpft.precio_unitario,
                          dpft.total_bruto,
                          dpft.iva,
                          dpft.importe_iva,
                          dpft.ieps,
                          dpft.importe_ieps,
                          dpft.ieps_monto_fijo,
                          dpft.ish,
                          dpft.importe_ish,
                          dpft.iva_exento,
                          dpft.importe_iva_exento,
                          dpft.iva_retenido,
                          dpft.importe_iva_retenido,
                          dpft.isr_retenido,
                          dpft.importe_isr_retenido,
                          dpft.isn_local,
                          dpft.importe_isn_local,
                          dpft.cedular,
                          dpft.importe_cedular,
                          dpft.al_millar,
                          dpft.importe_al_millar,
                          dpft.funcion_publica,
                          dpft.importe_funcion_publica,
                          dpft.ieps_retenido,
                          dpft.importe_ieps_retenido,
                          dpft.isr_exento,
                          dpft.importe_isr_exento,
                          dpft.isr_monto_fijo,
                          dpft.isr,
                          dpft.importe_isr,
                          dpft.ieps_exento,
                          dpft.importe_ieps_exento,
                          dpft.isr_retenido_monto_fijo,
                          dpft.ieps_retenido_monto_fijo,
                          dpft.total_neto,
                          dpft.numero_predial,
                          dpft.numero_lote,
                          dpft.caducidad,
                          dpft.numero_serie
                        from datos_producto_facturacion_temp dpft
                          inner join productos pr on dpft.producto_id = pr.PKProducto
                          left join claves_sat_unidades csu on dpft.unidad_medida_id = csu.PKClaveSATUnidad
                          where " . $cond_query);
    $stmt = $db->prepare($query);
    if ($data->factura_concepto === "0") {
      $stmt->bindValue(":id_row", $data->id);
      $stmt->bindValue(":usuario_id", $_SESSION['PKUsuario']);
      $stmt->bindValue(":value", $data->factura_concepto);
	  $stmt->bindValue(":tipo", $data->tipo_referencia);
      $stmt->bindValue(":ref", $data->referencia);    
    } else {
      $stmt->bindValue(":usuario_id", $_SESSION['PKUsuario']);
      $stmt->bindValue(":value", $data->factura_concepto);
      $stmt->bindValue(":id_row", $data->id);
      $stmt->bindValue(":tipo", $data->tipo_referencia);
      $stmt->bindValue(":ref", $data->referencia);
    }
    $stmt->execute();

    $detalleProducto = $stmt->fetchAll();

    $alertaSat = "";

    $impuestos = "";
    foreach ($detalleProducto as $r) {

      $claveInterna = ($r['clave'] !== "" && $r['clave'] !== null) ? $r['clave'] : "S/C";

      $alertaSat = ($r['sat_id'] !== null && $r['sat_id'] !== "" && $r['sat_id'] !== 1) ? "" : '<img id=\"satAlert\" src=\"../../img/icons/ICONO ALERTA_Mesa de trabajo 1.svg\" style=\"width: 25px\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"No se asignó una clave SAT\">';

      //$sat = (count($arrSat) > 0 && $arrSat['clave_sat'] !== null && $arrSat['clave_sat'] !== "") ? $claveInterna : $alertaSat . "  " .$claveInterna;
      $idUnidadMedida = ($r['id_unidad_medida'] !== "" && $r['id_unidad_medida'] !== null) ? $r['id_unidad_medida'] : "";
      $unidadMedida = ($r['unidad_medida'] !== "" && $r['unidad_medida'] !== null) ? $r['unidad_medida'] : "N/A";
      $cantidad = $r['cantidad_facturada'];

      if ($r['iva'] !== "" && $r['iva'] !== null) {
        $impuestos .= "IVA " . $r['iva'] . "%: $ " . number_format($r['importe_iva'], 2) . "<br>";
      }
      if ($r['ieps'] !== "" && $r['ieps'] !== null) {
        $impuestos .= "IEPS " . $r['ieps'] . "%: $ " . number_format($r['importe_ieps'], 2) . "<br>";
      }
      if ($r['ieps_monto_fijo'] !== "" && $r['ieps_monto_fijo'] !== null) {
        $impuestos .= "IEPS (Monto fijo) $ ".number_format($r['ieps_monto_fijo'], 2).": $ " . number_format($r['ieps_monto_fijo'] * $r['cantidad_total'], 2) . "<br>";
      }
      if ($r['ish'] !== "" && $r['ish'] !== null) {
        $impuestos .= "ISH " . $r['ish'] . "%: $ " . number_format($r['importe_ish'], 2) . "<br>";
      }
      if ($r['iva_exento'] !== "" && $r['iva_exento'] !== null) {
        $impuestos .= "IVA Exento " . $r['iva_exento'] . "%: $ " . number_format($r['importe_iva_exento'], 2) . "<br>";
      }
      if ($r['iva_retenido'] !== "" && $r['iva_retenido'] !== null) {
        $impuestos .= "IVA Retenido " . $r['iva_retenido'] . "%: $ " . number_format($r['importe_iva_retenido'], 2) . "<br>";
      }
      if ($r['isr_retenido'] !== "" && $r['isr_retenido'] !== null) {
        $impuestos .= "ISR Retenido " . $r['isr_retenido'] . "%: $ " . number_format($r['importe_isr_retenido'], 2) . "<br>";
      }
      if ($r['isn_local'] !== "" && $r['isn_local'] !== null) {
        $impuestos .= "ISN (Local) " . $r['isn_local'] . "%: $ " . number_format($r['importe_isn_local'], 2) . "<br>";
      }
      if ($r['cedular'] !== "" && $r['cedular'] !== null) {
        $impuestos .= "Cedular " . $r['cedular'] . "%: $ " . number_format($r['importe_cedular'], 2) . "<br>";
      }
      if ($r['al_millar'] !== "" && $r['al_millar'] !== null) {
        $impuestos .= "5 al millar (Local) " . $r['al_millar'] . "%: $ " . number_format($r['importe_al_millar'], 2) . "<br>";
      }
      if ($r['funcion_publica'] !== "" && $r['funcion_publica'] !== null) {
        $impuestos .= "Función Pública " . $r['funcion_publica'] . "%: $ " . number_format($r['importe_funcion_publica'], 2) . "<br>";
      }
      if ($r['ieps_retenido'] !== "" && $r['ieps_retenido'] !== null) {
        $impuestos .= "IEPS Retenido " . $r['ieps_retenido'] . "%: $ " . number_format($r['importe_ieps_retenido'], 2) . "<br>";
      }
      if ($r['isr_exento'] !== "" && $r['isr_exento'] !== null) {
        $impuestos .= "ISR Exento " . $r['isr_exento'] . "%: $ " . number_format($r['importe_isr_exento'], 2) . "<br>";
      }
      if ($r['isr_monto_fijo'] !== "" && $r['isr_monto_fijo'] !== null) {
        $impuestos .= "ISR (Monto fijo) : $  " . number_format($r['isr_monto_fijo'], 2) . "<br>";
      }
      if ($r['isr'] !== "" && $r['isr'] !== null) {
        $impuestos .= "ISR Retenido " . $r['isr'] . "%: $ " . number_format($r['importe_isr'], 2) . "<br>";
      }
      if ($r['ieps_exento'] !== "" && $r['ieps_exento'] !== null) {
        $impuestos .= "IEPS Exento" . $r['ieps_exento'] . "%: $ " . number_format($r['importe_ieps_exento'], 2) . "<br>";
      }
      if ($r['isr_retenido_monto_fijo'] !== "" && $r['isr_retenido_monto_fijo'] !== null) {
        $impuestos .= "ISR Retenido (Monto fijo) : $ " . number_format($r['isr_retenido_monto_fijo'], 2) . "<br>";
      }
      if ($r['ieps_retenido_monto_fijo'] !== "" && $r['ieps_retenido_monto_fijo'] !== null) {
        $impuestos .= "IEPS Retenido (Monto fijo) : $ " . number_format($r['ieps_retenido_monto_fijo'], 2) . "<br>";
      }

      if ($impuestos === "") {
        $impuestos = "Sin impuestos";
      }
      if ($r['descuento_tasa'] !== null && $r['descuento_tasa'] !== "" && $r['descuento_tasa'] !== 0) {
        $descuento = "Descuento " . $r['descuento_tasa'] . "%: " . $r['importe_descuento_tasa'];
      } else if ($r['descuento_monto_fijo'] !== null && $r['descuento_monto_fijo'] !== "" && $r['descuento_tasa'] !== 0) {
        $descuento = "Descuento: " . $r['importe_descuento_tasa'];
      } else {
        $descuento = "Sin descuento";
      }

      if ($r['numero_lote'] !== "" && $r['numero_lote'] !== null) {
        if ($r['caducidad'] !== "" && $r['caducidad'] !== null && $r['caducidad'] !== "0000-00-00") {
          $descripcion = $r['nombre'] . "<br>Lote: " . $r['numero_lote'] . " Caducidad: " . $r['caducidad'];
        } else {
          $descripcion = $r['nombre'] . "<br>Lote: " . $r['numero_lote'];
        }
      } else if ($r['numero_serie'] !== "" && $r['numero_serie'] !== null) {
        $descripcion = $r['nombre'] . "<br>Serie: " . $r['numero_serie'];
      } else {
        $descripcion = $r['nombre'];
      }

      $edit = "<a class='edit' id='edit" . $r['id'] . "' data-id='" . $r['id'] . "' data-ref='" . $r['id_row'] . "' href='#' ><img src='../../img/icons/editar.svg' width='22px' data-toggle='tooltip' data-placement='right' title='Editar'>";
      $delete = "<a class='delete' id='delete" . $r['id'] . "' data-id='" . $r['id'] . "' data-ref='" . $r['id_row'] . "' href='#' style='margin-left:5px'><img src='../../img/inventarios/delete.svg' width='22px' data-toggle='tooltip' data-placement='right' title='Eliminar'>";

      $funciones = ($data->factura_concepto === "0") ? $edit : $edit . $delete;

      $descripcion = $r['numero_predial'] !== null && $r['numero_predial'] !== "" ? $descripcion . "<br>Cuenta predial: " . $r['numero_predial'] : $descripcion;
      $table .= '{
            "id":"' . $r['id'] . '",
            "edit":"' . $funciones . '",
            "clave":"' . $claveInterna . '",
            "descripcion":"' . $descripcion . '",
            "id_unidad_medida":"' . $idUnidadMedida . '",
            "sat_id":"' . $r['sat_id'] . '",
            "unidad_medida":"' . $unidadMedida . '",
            "cantidad":"' . $cantidad . '",
            "precio":"' . number_format($r['precio_unitario'], 2) . '",
            "subtotal":"' . number_format(($r['cantidad_facturada'] * $r['precio_unitario']), 2) . '",
            "impuestos":"' . $impuestos . '",
            "descuento":"' . $descuento . '",
            "importe_total":"' . number_format($r['total_neto'], 2) . '",
            "alerta":"' . $alertaSat . '"
        },';
      $cont++;
      $impuestos = "";
    }

    $table = substr($table, 0, strlen($table) - 1);

    $con = "";
    $stmt = "";
    $db = "";

    return '{"data":[' . $table . ']}';
  }

  function getDataProduct($id)
  {
    $con = new conectar();
    $db = $con->getDb();

    $query = sprintf("select 
                              dpft.id id_row,
                              pro.PKProducto id,
                              pro.ClaveInterna clave,
                              pro.Nombre nombre,
                              dpft.clave_sat_id sat_id,
                              concat(csu.Clave,' - ',csu.Descripcion) unidad_medida_texto,
                              dpft.unidad_medida_id unidad_medida,
                              concat(csa.Clave,' - ',csa.Descripcion) clave_sat_texto,
                              dpft.cantidad cantidad_total,
                              dpft.cantidad_facturada,
                              dpft.precio_unitario,
                              dpft.descuento_tasa,
                              dpft.descuento_monto_fijo
                        from datos_producto_facturacion_temp dpft
                              inner join productos pro on dpft.producto_id = pro.PKProducto
                              left join claves_sat_unidades csu on dpft.unidad_medida_id = csu.PKClaveSATUnidad
                              left join claves_sat csa on dpft.clave_sat_id = csa.PKClaveSAT
                              where dpft.id = :id_row");

    $stmt = $db->prepare($query);
    $stmt->bindValue(":id_row", $id);
    $stmt->execute();

    $detalleProducto = $stmt->fetchAll();

    if ($detalleProducto[0]['cantidad_facturada'] !== null && $detalleProducto[0]['cantidad_facturada'] !== "") {
      if ($detalleProducto[0]['cantidad_total'] !== $detalleProducto[0]['cantidad_facturada']) {
        $cantidad = (int)$detalleProducto[0]['cantidad_total'] - (int)$detalleProducto[0]['cantidad_facturada'];
      } else {
        $cantidad = $detalleProducto[0]['cantidad_facturada'];
      }
    }

    if (count($detalleProducto) > 0 && $detalleProducto[0]['descuento_tasa'] !== null && $detalleProducto[0]['descuento_tasa'] !== "") {
      $tipo_descuento = 1;
      $descuento = $detalleProducto[0]['descuento_tasa'];
    } else if (count($detalleProducto) > 0 && $detalleProducto[0]['descuento_monto_fijo'] !== null && $detalleProducto[0]['descuento_monto_fijo'] !== "") {
      $tipo_descuento = 2;
      $descuento = number_format($detalleProducto[0]['descuento_monto_fijo'], 2, ".", ",");
    } else {
      $tipo_descuento = 1;
      $descuento = 0;
    }

    $array = [
      "id" => $detalleProducto[0]['id_row'],
      "clave" => $detalleProducto[0]['clave'],
      "nombre" => $detalleProducto[0]['nombre'],
      "clave_sat" => $detalleProducto[0]['sat_id'],
      "clave_sat_texto" => $detalleProducto[0]['clave_sat_texto'],
      "unidad_medida" => $detalleProducto[0]['unidad_medida'],
      "unidad_medida_texto" => $detalleProducto[0]['unidad_medida_texto'],
      "limite_cantidad" => $detalleProducto[0]['cantidad_total'],
      "cantidad" => $cantidad,
      "precio_unitario" => $detalleProducto[0]['precio_unitario'],
      "tipo_descuento" => $tipo_descuento,
      "descuento" => $descuento
    ];

    return $array;
  }

  function getImpuestosProductosTable($value)
  {
    $con = new conectar();
    $db = $con->getDb();

    $query = sprintf("select imp.PKImpuesto id,imp.Nombre nombre,ipr.Tasa tasa,imp.FKTipoImporte tipo from cotizacion co
                        inner join detalle_cotizacion dco on co.PKCotizacion = dco.FKCotizacion
                        inner join productos pro on dco.FKProducto = pro.PKProducto
                        inner join info_fiscal_productos ifp on pro.PKProducto = ifp.FKProducto
                        inner join impuestos_productos ipr on ifp.PKInfoFiscalProducto = ipr.FKInfoFiscalProducto
                        inner join impuesto imp on ipr.FKImpuesto = imp.PKImpuesto
                      where co.PKCotizacion = :id");

    $stmt = $db->prepare($query);
    $stmt->bindValue(":id", $value);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }

  function getImpuestoTable($producto, $id)
  {
    $con = new conectar();
    $db = $con->getDb();
    $table = "";

    $query = sprintf("select 
                          iva,
                          ieps,
                          ieps_monto_fijo,
                          ish,
                          iva_exento,
                          iva_retenido,
                          isr_retenido,
                          isn_local,
                          cedular,
                          al_millar,
                          funcion_publica,
                          ieps_retenido,
                          isr_exento,
                          isr_monto_fijo,
                          isr,
                          ieps_exento,
                          isr_retenido_monto_fijo,
                          ieps_retenido_monto_fijo
                        from datos_producto_facturacion_temp
                        where id = :id");
    $stmt = $db->prepare($query);
    //$stmt->bindValue(":id",$value);
    //$stmt->bindValue(":producto",$producto);
    //$stmt->bindValue(":tipo",$tipo);
    $stmt->bindValue(":id", $id);
    $stmt->execute();

    $detalleImpuesto = $stmt->fetchAll();
    $impuestos = [];

    //iva
    if ($detalleImpuesto[0]['iva'] !== null) {
      array_push(
        $impuestos,
        ["id" => 1, "tipo" => 1, "nombre" => "IVA", "tasa" => $detalleImpuesto[0]['iva']]
      );
    }
    //ieps
    if ($detalleImpuesto[0]['ieps'] !== null) {
      array_push(
        $impuestos,
        ["id" => 2, "tipo" => 1, "nombre" => "IEPS", "tasa" => $detalleImpuesto[0]['ieps']]
      );
    }
    //ieps monto fijo
    if ($detalleImpuesto[0]['ieps_monto_fijo'] !== null) {
      array_push(
        $impuestos,
        ["id" => 3, "tipo" => 2, "nombre" => "IEPS (Monto fijo)", "tasa" => $detalleImpuesto[0]['ieps_monto_fijo']]
      );
    }
    //iva exento
    if ($detalleImpuesto[0]['iva_exento'] !== null) {
      array_push(
        $impuestos,
        ["id" => 5, "tipo" => 3, "nombre" => "IVA Exento", "tasa" => $detalleImpuesto[0]['iva_exento']]
      );
    }
    //ish
    if ($detalleImpuesto[0]['ish'] !== null) {
      array_push(
        $impuestos,
        ["id" => 4, "tipo" => 1, "nombre" => "ISH", "tasa" => $detalleImpuesto[0]['ish']]
      );
    }
    //iva retenido
    if ($detalleImpuesto[0]['iva_retenido'] !== null) {
      array_push(
        $impuestos,
        ["id" => 6, "tipo" => 1, "nombre" => "IVA Retenido", "tasa" => $detalleImpuesto[0]['iva_retenido']]
      );
    }
    //isr
    if ($detalleImpuesto[0]['isr_retenido'] !== null) {
      array_push(
        $impuestos,
        ["id" => 7, "tipo" => 1, "nombre" => "ISR Retenido", "tasa" => $detalleImpuesto[0]['isr_retenido']]
      );
    }
    //isn local
    if ($detalleImpuesto[0]['isn_local'] !== null) {
      array_push(
        $impuestos,
        ["id" => 8, "tipo" => 1, "nombre" => "ISN (Local)", "tasa" => $detalleImpuesto[0]['isn_local']]
      );
    }
    //cedular
    if ($detalleImpuesto[0]['cedular'] !== null) {
      array_push(
        $impuestos,
        ["id" => 9, "tipo" => 1, "nombre" => "Cedular", "tasa" => $detalleImpuesto[0]['cedular']]
      );
    }
    //al millar
    if ($detalleImpuesto[0]['al_millar'] !== null) {
      array_push(
        $impuestos,
        ["id" => 10, "tipo" => 1, "nombre" => "5 al millar", "tasa" => $detalleImpuesto[0]['al_millar']]
      );
    }
    //funcion publica
    if ($detalleImpuesto[0]['funcion_publica'] !== null) {
      array_push(
        $impuestos,
        ["id" => 11, "tipo" => 1, "nombre" => "Función pública", "tasa" => $detalleImpuesto[0]['funcion_publica']]
      );
    }
    //ieps retenido
    if ($detalleImpuesto[0]['ieps_retenido'] !== null) {
      array_push(
        $impuestos,
        ["id" => 12, "tipo" => 2, "nombre" => "IEPS Retenido", "tasa" => $detalleImpuesto[0]['ieps_retenido']]
      );
    }
    //isr_exento
    if ($detalleImpuesto[0]['isr_exento'] !== null) {
      array_push(
        $impuestos,
        ["id" => 13, "tipo" => 3, "nombre" => "ISR Exento", "tasa" => $detalleImpuesto[0]['isr_exento']]
      );
    }
    //isr_monto_fijo
    if ($detalleImpuesto[0]['isr_monto_fijo'] !== null) {
      array_push(
        $impuestos,
        ["id" => 14, "tipo" => 2, "nombre" => "ISR (Monto fijo)", "tasa" => $detalleImpuesto[0]['isr_monto_fijo']]
      );
    }
    //isr
    if ($detalleImpuesto[0]['isr'] !== null) {
      array_push(
        $impuestos,
        ["id" => 15, "tipo" => 1, "nombre" => "ISR", "tasa" => $detalleImpuesto[0]['isr']]
      );
    }
    //ieps_exento
    if ($detalleImpuesto[0]['ieps_exento'] !== null) {
      array_push(
        $impuestos,
        ["id" => 16, "tipo" => 3, "nombre" => "IEPS Exento", "tasa" => $detalleImpuesto[0]['ieps_exento']]
      );
    }
    //ieps_exento
    if ($detalleImpuesto[0]['isr_retenido_monto_fijo'] !== null) {
      array_push(
        $impuestos,
        ["id" => 17, "tipo" => 2, "nombre" => "ISR Retenido (Monto fijo)", "tasa" => $detalleImpuesto[0]['isr_retenido_monto_fijo']]
      );
    }
    //ieps_retenido_monto_fijo
    if ($detalleImpuesto[0]['ieps_retenido_monto_fijo'] !== null) {
      array_push(
        $impuestos,
        ["id" => 18, "tipo" => 2, "nombre" => "IEPS Retenido (Monto fijo)", "tasa" => $detalleImpuesto[0]['ieps_retenido_monto_fijo']]
      );
    }

    $cont = 0;
    foreach ($impuestos as $r) {

      $table .= '{
          "id": "' . $r['id'] . '",
          "tipo":"' . $r['tipo'] . '",
          "nombre": "' . $r['nombre'] . '",
          "tasa": "' . $r['tasa'] . '",
          "delete":"<a id=\"deleteImp' . $r['id'] . '\" data-pos=\"' . $cont . '\" data-id=\"' . $r['id'] . '\" href=\"#\"><i class=\"fas fa-trash-alt\"></i></a>"
        },';
      $cont++;
    }

    $table = substr($table, 0, strlen($table) - 1);

    $con = "";
    $stmt = "";
    $db = "";

    return '{"data":[' . $table . ']}';
  }

  function getClaveSat($prod)
  {
    $con = new conectar();
    $db = $con->getDb();

    $query = sprintf("select * from info_fiscal_productos where FKProducto = :id");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":id", $prod);
    $stmt->execute();
    $arr = $stmt->fetchAll();

    if (count($arr) > 0 && $arr[0]['FKClaveSAT'] !== 1) {
      $msj = [
        "mensaje" => 1,
        "clave_sat_id" => $arr[0]['FKClaveSAT']
      ];
    } else {
      $msj = [
        "mensaje" => 0,
        "clave_sat_id" => ""
      ];
    }
    return $msj;
  }

  function getUnidadMedida($prod)
  {
    $con = new conectar();
    $db = $con->getDb();

    $query = sprintf("select * from info_fiscal_productos where FKProducto = :id");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":id", $prod);
    $stmt->execute();
    $arr = $stmt->fetchAll();

    if (count($arr) > 0) {
      $msj = [
        "mensaje" => 1,
        "clave_sat_id" => $arr[0]['FKClaveSATUnidad']
      ];
    } else {
      $msj = [
        "mensaje" => 0,
        "clave_sat_id" => ""
      ];
    }
    return $msj;
  }

  function getProductsAllSat($value,$tipo,$ref)
  {
    $con = new conectar();
    $db = $con->getDb();

    //parámetro ref recibe json con la referencia, para los pedidos con el/los folio/s de los pedidos añadidos.
    if($ref==0){
      $data[0] = $ref;
    }else{
      $data = json_decode($ref);
    }
    
    if($tipo != 3){
      $query = sprintf("select dpft.clave_sat_id sat_id
            from datos_producto_facturacion_temp dpft
              inner join productos pr on dpft.producto_id = pr.PKProducto
              left join claves_sat_unidades csu on dpft.unidad_medida_id = csu.PKClaveSATUnidad
            where usuario_id = :usuario_id and factura_concepto = :factura_concepto and tipo = :type and referencia = :ref
              ");
      $stmt = $db->prepare($query);
      $stmt->bindValue(":usuario_id", $_SESSION['PKUsuario']);
      $stmt->bindValue(":factura_concepto", $value);
      $stmt->bindValue(":type", $tipo);
      $stmt->bindValue(":ref", $data[0]);
      $stmt->execute();

      $arr = $stmt->fetchAll();
      $rowCount = $stmt->rowCount();
      $cont = 0;

      for ($i = 0; $i < count($arr); $i++) {
        if ($arr[$i]['sat_id'] !== "" && $arr[$i]['sat_id'] !== null && $arr[$i]['sat_id'] !== 1) {
          $cont++;
        }
      }
    }else{
      $cont = 0;
      for ($i = 0; $i < count($data); $i++) {
            $query = sprintf("select 
                            sa.id id,
                            pr.PKProducto producto_id,
                            ifp.FKClaveSAT sat_id
                          from inventario_salida_por_sucursales sa
                            inner join productos pr on sa.clave = pr.ClaveInterna
                            left join info_fiscal_productos ifp on pr.PKProducto = ifp.FKProducto
                          where folio_salida = :folio and pr.empresa_id = :id_empresa
                          
                          union

                          select 
                            sa.PKMovServ id,
                            pr.PKProducto producto_id,
                            ifp.FKClaveSAT sat_id
                          from movimientos_salidas_servicios_sin_inventario sa
                            inner join productos pr on sa.FKProducto = pr.PKProducto
                            left join info_fiscal_productos ifp on pr.PKProducto = ifp.FKProducto
                          where sa.FKSalida = :folio2 and pr.empresa_id = :id_empresa2;
                          ");
          $stmt = $db->prepare($query);
          $stmt->bindValue(":folio", $data[$i]);
          $stmt->bindValue(":id_empresa", $_SESSION['IDEmpresa']);
          $stmt->bindValue(":folio2", $data[$i]);
          $stmt->bindValue(":id_empresa2", $_SESSION['IDEmpresa']);
          $stmt->execute();

          $arr = $stmt->fetchAll();
          $rowCount = $stmt->rowCount();
      
          for ($i = 0; $i < count($arr); $i++) {
            if ($arr[$i]['sat_id'] !== "" && $arr[$i]['sat_id'] !== null && $arr[$i]['sat_id'] !== 1) {
              $cont++;
            }
          }
      }
    }
    
    if ($cont === $rowCount) {
      $mensaje = 1;
    } else {
      $mensaje = 0;
    }

    return $mensaje;
  }

  function getClaveSatTable()
  {
    $con = new conectar();
    $db = $con->getDb();

    $query = sprintf("SELECT PKClaveSAT id,Clave clave, Descripcion descripcion FROM claves_sat LIMIT 100");
    $stmt = $db->prepare($query);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }

  function getClaveSatTableSearch($value)
  {
    $con = new conectar();
    $db = $con->getDb();

    $query = sprintf("SELECT PKClaveSAT id,Clave clave, Descripcion descripcion FROM claves_sat WHERE Clave LIKE :q OR Descripcion LIKE :q1 LIMIT 100");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":q", "%" . $value . "%");
    $stmt->bindValue(":q1", "%" . $value . "%");
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }

  function getClaveUnidadMedidaTable()
  {
    $con = new conectar();
    $db = $con->getDb();

    $query = sprintf("SELECT csu.PKClaveSATUnidad as id,
                      csu.Clave as clave,
                      csu.Descripcion as descripcion
                      FROM claves_sat_unidades csu order by csu.orden desc limit 100");
    $stmt = $db->prepare($query);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }

  function getClaveUnidadMedidaTableSearch($value)
  {
    $con = new conectar();
    $db = $con->getDb();

    $query = sprintf("SELECT PKClaveSATUnidad id,Clave clave, Descripcion descripcion FROM claves_sat_unidades WHERE Clave LIKE :q OR Descripcion LIKE :q1 order by orden desc LIMIT 100");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":q", "%" . $value . "%");
    $stmt->bindValue(":q1", "%" . $value . "%");
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }

  function getCancelInvoice($value, $client, $motivo, $invoice_id)
  {
    $con = new conectar();
    $db = $con->getDb();
    $ruta_api = "../../../";
    $estatus = "La factura está cancelada o en proceso de cancelación";
    $relation = "";
    $update_data = new edit_data();

    require_once $ruta_api . "include/functions_api_facturation.php";
    require_once $ruta_api . 'vendor/facturapi/facturapi-php/src/Facturapi.php';
    $api = new API();
    $mail = new get_data();

    if(is_array($value)){
      $ref = $value[0];
    } else {
      $ref = $value;
    }

    $query = sprintf("select key_company_api from empresas where PKEmpresa=:id");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":id", $_SESSION['IDEmpresa']);
    $stmt->execute();
    $arr = $stmt->fetchAll();

    if($invoice_id !== "" && $invoice_id !== null){
      $query = sprintf("select uuid from facturacion where id = :id");
      $stmt = $db->prepare($query);
      $stmt->bindValue(":id",$invoice_id);
      $stmt->execute();

      $aux_relation = $stmt->fetchAll();
      $relation = $aux_relation[0]['uuid'];
    }


    $query = sprintf("select concat(f.serie,' - ',f.folio) folio ,f.id_api,f.uuid,cl.razon_social,f.total_facturado from facturacion f
                        inner join clientes cl on f.cliente_id = cl.PKCliente 
                        where id = :id");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":id", $ref);
    $stmt->execute();
    $arr2 = $stmt->fetchAll();

    $mensaje = $api->cancelInvoice($arr[0]['key_company_api'], $arr2[0]['id_api'], $motivo,$relation);

    if (isset($mensaje->status)) {

      switch ($motivo) {
        case '01':
          $mensajeMotivo = "Comprobante emitido con errores con relación";
          break;
        case '02':
          $mensajeMotivo = "Comprobante emitido con errores sin relación";
          break;
        case '03':
          $mensajeMotivo = "No se llevó a cabo la operación";
          break;
        case '04':
          $mensajeMotivo = "Operación nominativa relacionada en la factura global";
          break;
      }

      if ($mensaje->status === "canceled") {
        $estatus = "Cancelada";
        $mail->sendEmail($client, $arr2[0]['folio'], $arr2[0]['razon_social'],  $arr2[0]['total_facturado'], $arr2[0]['uuid'], $mensajeMotivo, $estatus);

        if($invoice_id !== "" && $invoice_id !== null){
          $query = sprintf("update facturacion set estatus = 4,fecha_cancelacion = NOW(),usuario_cancelo_id = :user_id, factura_relacion = :factura_relacion where id= :id");
          $stmt = $db->prepare($query);
          $stmt->bindValue(":user_id",$_SESSION['PKUsuario']);
          $stmt->bindValue(":id", $ref);
          $stmt->bindValue(":factura_relacion", $invoice_id);
          $stmt->execute();
        } else {
          $query = sprintf("update facturacion set estatus = 4,fecha_cancelacion = NOW(),usuario_cancelo_id = :user_id where id= :id");
          $stmt = $db->prepare($query);
          $stmt->bindValue(":user_id",$_SESSION['PKUsuario']);
          $stmt->bindValue(":id", $ref);
          $stmt->execute();
        }

        $msj = 1;
      }
      if ($mensaje->status === "valid" && $mensaje->cancellation_status === "pending") {
        if($mensaje->cancellation_status === "pending"){
          $query = sprintf("update facturacion set estatus_old = estatus where id= :id");
          $stmt = $db->prepare($query);
          $stmt->bindValue(":id", $ref);
        }
        $estatus = "En proceso de cancelación. Revisar su buzón del SAT";
        $mail->sendEmail($client, $arr2[0]['folio'], $arr2[0]['razon_social'],  $arr2[0]['total_facturado'], $arr2[0]['uuid'], $mensajeMotivo, $estatus);

        if($invoice_id !== "" && $invoice_id !== null){
          $query = sprintf("update facturacion set estatus = 5,usuario_cancelo_id = :usuario_id,fecha_cancelacion = NOW(), factura_relacion = :factura_relacion where id= :id");
          $stmt = $db->prepare($query);
          $stmt->bindValue(":usuario_id",$_SESSION['PKUsuario']);
          $stmt->bindValue(":id", $ref);
          $stmt->bindValue(":factura_relacion", $invoice_id);
          $stmt->execute();
        } else {
          $query = sprintf("update facturacion set estatus = 5,usuario_cancelo_id = :usuario_id,fecha_cancelacion = NOW() where id= :id");
          $stmt = $db->prepare($query);
          $stmt->bindValue(":usuario_id",$_SESSION['PKUsuario']);
          $stmt->bindValue(":id", $ref);

          $stmt->execute();
        }
        
        $msj = 2;
      }

      $update_data->updateStatusDocumentsCancel($ref);
    } else {
      $msj = $mensaje->message;
    }

    return $msj;
  }

  function getStatusCancelInvoice()
  {

    $con = new conectar();
    $db = $con->getDb();
    $ruta_api = "../../../";
    require_once $ruta_api . "include/functions_api_facturation.php";
    require_once $ruta_api . 'vendor/facturapi/facturapi-php/src/Facturapi.php';
    $api = new API();

    $query = sprintf("select key_company_api from empresas where PKEmpresa=:id");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":id", $_SESSION['IDEmpresa']);
    $stmt->execute();
    $data_enterprise = $stmt->fetchAll(PDO::FETCH_OBJ);
    $keyCompany = $data_enterprise[0]->key_company_api;

    $query = sprintf("select id, id_api from facturacion where empresa_id = :empresa_id and estatus = 5");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":empresa_id",$_SESSION['IDEmpresa']);
    $stmt->execute();

    $arr = $stmt->fetchAll(PDO::FETCH_OBJ);

    foreach($arr as $r){
      $invoice = $api->getInvoicesRetrieve($keyCompany,$r->id_api);

      if($invoice->status === 'canceled'){
        $query = sprintf("update facturacion set estatus = 4,fecha_cancelacion = now() where id = :id");
        $stmt = $db->prepare($query);
        $stmt->bindValue(":id",$r->id);
        $stmt->execute();
      }
      if($invoice->status === 'rejected'){
        $query = sprintf("update facturacion set estatus = estatus_old where id = :id");
        $stmt = $db->prepare($query);
        $stmt->bindValue(":id",$r->id);
        $stmt->execute();
      }
    }
    
  }

  function sendEmail($user, $folio, $client, $total, $uuid, $motivo, $estatus)
  {

    require('../../../lib/phpmailer_configuration.php');

    $con = new conectar();
    $db = $con->getDb();
    $query = sprintf("SELECT valor FROM parametros_servidor WHERE parametro = 'email_contacto'");
    $stmt = $db->prepare($query);
    $stmt->execute();
    $url = $stmt->fetch();

    try {
      $origen = $_ENV['ORIGEN_MAIL'] ?? "no-reply@timlid.com";
      $usuario_envia = "Timlid";
      $mail->Sender = $origen;
      $mail->setFrom($origen, $usuario_envia);
      $mail->addReplyTo($origen, $usuario_envia);
      $mail->addAddress($user);     //Add a recipient  $user

      $mensaje = "Aviso de cancelación de la factura: 
                      <br><br>Serie y folio: " . $folio .
        "<br>UUID: " . $uuid .
        "<br> A nombre de: " . $client .
        "<br> Total: $" . number_format($total, 2) .
        "<br> Motivo: " . $motivo .
        "<br> Estatus: " . $estatus .
        "<br><br>Favor de revisar su buzón tributario para más información";
      //Content
      $mail->isHTML(true);                                  //Set email format to HTML
      $mail->Subject = utf8_decode("Timlid - Aviso de cancelación de factura");
      $mail->Body    = utf8_decode($mensaje);

      if ($mail->send()) {
        return true;
      } else {
        return false;
      }
    } catch (Exception $e) {
      //header('Location: ver_Cotizacion.php?id='.$id.'&estatus=2');
      return false;
    }
  }

  function getTotalSubtotalSalidas($value,$ref,$type)
  {
    $con = new conectar();
    $db = $con->getDb();

    $array = [];
    $impuestos = "<table class='table'><tbody>";
    $query_cond = "";
    $query_cond2 = "";
    $referencia = "";
    
    $data = json_decode($ref);

    if(is_array($data)){
      $id = $data[0];
    } else {
      $id = $data;
    }
    switch ((int)$type) {
      case 1:
        $referencia = "referencia = '" . $id . "'";
        break;
      case 2:
        $referencia = "referencia = '" . $id . "'";
        break;
      case 3:
        if(count($data) > 1){
          $query_cond .= "(";
          $query_cond2 .= "(";
          for ($i=0; $i < count($data); $i++) {
            $query_cond .= "sa.folio_salida = '$data[$i]' or ";
            $query_cond2 .= "sa.FKSalida = '$data[$i]' or ";
          }
          $query_cond = substr($query_cond, 0, strlen($query_cond) - 4);
          $query_cond2 = substr($query_cond2, 0, strlen($query_cond2) - 4);
          $query_cond .= ")";
          $query_cond2 .= ")";
        } else {
          $query_cond .= "sa.folio_salida = '$data[0]'";
          $query_cond2 .= "sa.FKSalida = '$data[0]'";
        }
        
        $query0 = sprintf("select sa.id, pr.PKProducto as prod
                            from inventario_salida_por_sucursales sa
                              inner join sucursales s on sa.sucursal_id = s.id
                              inner join productos pr on sa.clave = pr.ClaveInterna and s.empresa_id = pr.empresa_id
                            where " . $query_cond . " and s.empresa_id = :empresa_id

                            union

                            select sa.PKMovServ id, sa.FKProducto as prod
                            FROM movimientos_salidas_servicios_sin_inventario sa
                              inner join orden_pedido_por_sucursales opps on sa.FKOrdenPedido = opps.id
                              inner join sucursales s on opps.sucursal_origen_id = s.id
                            where " . $query_cond2 . " and s.empresa_id = :empresa_id2
                            ;");
        ;                    
        $stmt0 = $db->prepare($query0);
        
        $stmt0->bindValue(":empresa_id",$_SESSION['IDEmpresa']);
        $stmt0->bindValue(":empresa_id2",$_SESSION['IDEmpresa']);
        $stmt0->execute();
        $id_salida = $stmt0->fetchAll();
        
        if(count($id_salida) > 1){
          $referencia .= "(";
          for ($i=0; $i < count($id_salida); $i++) {
            $referencia .= "(referencia = '" . $id_salida[$i]['id'] . "' and producto_id = '".$id_salida[$i]['prod']. "') or ";
          }
          $referencia = substr($referencia, 0, strlen($referencia) - 4);
          $referencia .= ")";
        } else {
          $referencia .= "referencia = '" . $id_salida[0]['id'] . "'";
        }
        break;
      case 4:
        $referencia = "referencia = '" . $id . "'";
        break;
      case 0:
          $referencia = "referencia = '" . $id . "'";
        break;
    }
    

    $query = sprintf("SELECT sum(total_bruto) subtotal from datos_producto_facturacion_temp where usuario_id = :usuario_id and factura_concepto = :id and tipo =  :tipo and $referencia");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":usuario_id", $_SESSION['PKUsuario']);
    $stmt->bindValue(":id", $value);
    $stmt->bindValue(":tipo", $type);
    //$stmt->bindValue(":ref", $ref);
    $stmt->execute();
    $arr = $stmt->fetchAll();
    $rowCount = $stmt->rowCount();

    $query = sprintf("select
                        cantidad, 
                        total_bruto, 
                        iva, 
                        ieps, 
                        ieps_monto_fijo,
                        ish, 
                        iva_exento, 
                        iva_retenido, 
                        isr_retenido, 
                        isn_local, 
                        cedular, 
                        al_millar, 
                        funcion_publica,
                        ieps_retenido,
                        isr_exento,
                        isr_monto_fijo,
                        isr,
                        ieps_exento,
                        isr_retenido_monto_fijo,
                        ieps_retenido_monto_fijo
                      from datos_producto_facturacion_temp where usuario_id = :usuario_id and factura_concepto = :id and tipo = :tipo and $referencia");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":usuario_id", $_SESSION['PKUsuario']);
    $stmt->bindValue(":id", $value);
    $stmt->bindValue(":tipo", $type);
    //$stmt->bindValue(":ref", $ref);
    $stmt->execute();
    $arr1 = $stmt->fetchAll();

    $total = $arr[0]['subtotal'];

    $aux = array_values(array_unique(array_column($arr1, 'iva')));

    for ($i = 0; $i < count($aux); $i++) {
      $sum = 0;
      if ($aux[$i] !== null && $aux[$i] !== "") {
        for ($j = 0; $j < count($arr1); $j++) {
          if ($aux[$i] === $arr1[$j]['iva']) {
            $sum += ($arr1[$j]['iva'] / 100) * $arr1[$j]['total_bruto'];
            
            if($arr1[$j]['ieps_monto_fijo'] !== "" && $arr1[$j]['ieps_monto_fijo'] !== null){
              $sum += (($arr1[$j]['ieps_monto_fijo'] * $arr1[$j]['cantidad']) * ($arr1[$j]['iva'] / 100));
            }
            if($arr1[$j]['ieps'] !== "" && $arr1[$j]['ieps'] !== null){
              $sum += (($arr1[$j]['ieps'] / 100) * $arr1[$j]['total_bruto']) * ($arr1[$j]['iva'] / 100);
            }
            
          }
          
        }
        $impuestos .= "<tr><th>IVA " . $aux[$i] . "%:</th><td>$ " . number_format($sum, 2) . "</td></tr>";
      }
      $total += $sum;
    }

    $aux = array_values(array_unique(array_column($arr1, 'ieps')));

    for ($i = 0; $i < count($aux); $i++) {
      $sum = 0;
      if ($aux[$i] !== null && $aux[$i] !== "") {
        for ($j = 0; $j < count($arr1); $j++) {
          if ($aux[$i] === $arr1[$j]['ieps']) {
            $sum += ($arr1[$j]['ieps'] / 100) * $arr1[$j]['total_bruto'];
          }
        }
        $impuestos .= "<tr><th>IEPS " . $aux[$i] . "%:</th><td>$ " . number_format($sum, 2) . "</td><tr>";
      }
      $total += $sum;
    }

    $aux = array_values(array_unique(array_column($arr1, 'ieps_monto_fijo')));

    for ($i = 0; $i < count($aux); $i++) {
      $sum = 0;
      if ($aux[$i] !== null && $aux[$i] !== "") {
        for ($j = 0; $j < count($arr1); $j++) {
          if ($aux[$i] === $arr1[$j]['ieps_monto_fijo']) {
            $tasa = $arr1[$j]['ieps_monto_fijo'];
            $sum += $arr1[$j]['ieps_monto_fijo'] * $arr1[$j]['cantidad'];
          }
        }
        $impuestos .= "<tr><th>IEPS (Monto fijo) $ ".number_format($tasa, 2).": </th><td>$ " . number_format($sum, 2) . "</td><tr>";
      }
      $total += $sum;
    }

    $aux = array_values(array_unique(array_column($arr1, 'ish')));

    for ($i = 0; $i < count($aux); $i++) {
      $sum = 0;
      if ($aux[$i] !== null && $aux[$i] !== "") {
        for ($j = 0; $j < count($arr1); $j++) {
          if ($aux[$i] === $arr1[$j]['ish']) {
            $sum += ($arr1[$j]['ish'] / 100) * $arr1[$j]['total_bruto'];
          }
        }
        $impuestos .= "<tr><td>ISH " . $aux[$i] . "%: $ " . number_format($sum, 2) . "</td><tr>";
      }
      $total += $sum;
    }

    $aux = array_values(array_unique(array_column($arr1, 'iva_exento')));

    for ($i = 0; $i < count($aux); $i++) {
      $sum = 0;
      if ($aux[$i] !== null && $aux[$i] !== "") {
        for ($j = 0; $j < count($arr1); $j++) {
          if ($aux[$i] === $arr1[$j]['iva_exento']) {
            $sum = ($arr1[$j]['iva_exento'] / 100) * $arr1[$j]['total_bruto'];
          }
        }
        $impuestos .= "<tr><th>IVA Exento " . $aux[$i] . "%:</th><td>$ " . number_format($sum, 2) . "</td><tr>";
      }
    }

    $aux = array_values(array_unique(array_column($arr1, 'iva_retenido')));

    for ($i = 0; $i < count($aux); $i++) {
      $sum = 0;
      if ($aux[$i] !== null && $aux[$i] !== "") {
        for ($j = 0; $j < count($arr1); $j++) {
          if ($aux[$i] === $arr1[$j]['iva_retenido']) {
            $sum += ($arr1[$j]['iva_retenido'] / 100) * $arr1[$j]['total_bruto'];
          }
        }
        $impuestos .= "<tr><th>IVA Retenido " . $aux[$i] . "%:</th><td>$ " . number_format($sum, 2) . "</td><tr>";
      }
      $total -= $sum;
    }

    $aux = array_values(array_unique(array_column($arr1, 'isr_retenido')));

    for ($i = 0; $i < count($aux); $i++) {
      $sum = 0;
      if ($aux[$i] !== null && $aux[$i] !== "") {
        for ($j = 0; $j < count($arr1); $j++) {
          if ($aux[$i] === $arr1[$j]['isr_retenido']) {
            $sum += ($arr1[$j]['isr_retenido'] / 100) * $arr1[$j]['total_bruto'];
          }
        }
        $impuestos .= "<tr><th>ISR Retenido " . $aux[$i] . "%:</th><td>$ " . number_format($sum, 2) . "</td><tr>";
      }
      $total -= $sum;
    }

    $aux = array_values(array_unique(array_column($arr1, 'isn_local')));

    for ($i = 0; $i < count($aux); $i++) {
      $sum = 0;
      if ($aux[$i] !== null && $aux[$i] !== "") {
        for ($j = 0; $j < count($arr1); $j++) {
          if ($aux[$i] === $arr1[$j]['isn_local']) {
            $sum += ($arr1[$j]['isn_local'] / 100) * $arr1[$j]['total_bruto'];
          }
        }
        $impuestos .= "<tr><th>ISN (Local) " . $aux[$i] . "%:</th><td>$ " . number_format($sum, 2) . "</td><tr>";
      }
      $total += $sum;
    }

    $aux = array_values(array_unique(array_column($arr1, 'cedular')));

    for ($i = 0; $i < count($aux); $i++) {
      $sum = 0;
      if ($aux[$i] !== null && $aux[$i] !== "") {
        for ($j = 0; $j < count($arr1); $j++) {
          if ($aux[$i] === $arr1[$j]['cedular']) {
            $sum += ($arr1[$j]['cedular'] / 100) * $arr1[$j]['total_bruto'];
          }
        }
        $impuestos .= "<tr><th>Cedular " . $aux[$i] . "%:</th><td>$ " . number_format($sum, 2) . "</td><tr>";
      }
      $total += $sum;
    }

    $aux = array_values(array_unique(array_column($arr1, 'al_millar')));

    for ($i = 0; $i < count($aux); $i++) {
      $sum = 0;
      if ($aux[$i] !== null && $aux[$i] !== "") {
        for ($j = 0; $j < count($arr1); $j++) {
          if ($aux[$i] === $arr1[$j]['al_millar']) {
            $sum += ($arr1[$j]['al_millar'] / 100) * $arr1[$j]['total_bruto'];
          }
        }
        $impuestos .= "<tr><th>5 al millar " . $aux[$i] . "%:</th><td>$ " . number_format($sum, 2) . "</td><tr>";
      }
      $total += $sum;
    }

    $aux = array_values(array_unique(array_column($arr1, 'funcion_publica')));

    for ($i = 0; $i < count($aux); $i++) {
      $sum = 0;
      if ($aux[$i] !== null && $aux[$i] !== "") {
        for ($j = 0; $j < count($arr1); $j++) {
          if ($aux[$i] === $arr1[$j]['funcion_publica']) {
            $sum += ($arr1[$j]['funcion_publica'] / 100) * $arr1[$j]['total_bruto'];
          }
        }
        $impuestos .= "<tr><th>Funcion Pública " . $aux[$i] . "%:</th><td>$ " . number_format($sum, 2) . "</td><tr>";
      }
      $total += $sum;
    }

    $aux = array_values(array_unique(array_column($arr1, 'ieps_retenido')));

    for ($i = 0; $i < count($aux); $i++) {
      $sum = 0;
      if ($aux[$i] !== null && $aux[$i] !== "") {
        for ($j = 0; $j < count($arr1); $j++) {
          if ($aux[$i] === $arr1[$j]['ieps_retenido']) {
            
            $sum += ($arr1[$j]['ieps_retenido'] / 100) * $arr1[$j]['total_bruto'];
          }
        }
        $impuestos .= "<tr><th>IEPS Retenido " . $aux[$i] . "%:</th><td>$ " . number_format($sum, 2) . "</td><tr>";
      }
      $total -= $sum;
    }

    $aux = array_values(array_unique(array_column($arr1, 'isr_exento')));

    for ($i = 0; $i < count($aux); $i++) {
      $sum = 0;
      if ($aux[$i] !== null && $aux[$i] !== "") {
        for ($j = 0; $j < count($arr1); $j++) {
          if ($aux[$i] === $arr1[$j]['isr_exento']) {
            $sum += ($arr1[$j]['isr_exento'] / 100) * $arr1[$j]['total_bruto'];
          }
        }
        $impuestos .= "<tr><th>ISR Exento " . $aux[$i] . "%:</th><td>$ " . number_format($sum, 2) . "</td><tr>";
      }
      //$total += $sum;
    }

    $aux = array_values(array_unique(array_column($arr1, 'isr_monto_fijo')));

    for ($i = 0; $i < count($aux); $i++) {
      $sum = 0;
      if ($aux[$i] !== null && $aux[$i] !== "") {
        for ($j = 0; $j < count($arr1); $j++) {
          if ($aux[$i] === $arr1[$j]['isr_monto_fijo']) {
            $sum += $arr1[$j]['isr_monto_fijo'];
          }
        }
        $impuestos .= "<tr><th>ISR (Monto fijo):</th><td>$" . number_format($sum, 2) . "</td><tr>";
      }
      $total += $sum;
    }

    $aux = array_values(array_unique(array_column($arr1, 'isr')));

    for ($i = 0; $i < count($aux); $i++) {
      $sum = 0;
      if ($aux[$i] !== null && $aux[$i] !== "") {
        for ($j = 0; $j < count($arr1); $j++) {
          if ($aux[$i] === $arr1[$j]['isr']) {
            $sum += ($arr1[$j]['isr'] / 100) * $arr1[$j]['total_bruto'];
          }
        }
        $impuestos .= "<tr><th>ISR Retenido " . $aux[$i] . "%:</th><td>$ " . number_format($sum, 2) . "</td><tr>";
      }
      $total -= $sum;
    }

    $aux = array_values(array_unique(array_column($arr1, 'ieps_exento')));

    for ($i = 0; $i < count($aux); $i++) {
      $sum = 0;
      if ($aux[$i] !== null && $aux[$i] !== "") {
        for ($j = 0; $j < count($arr1); $j++) {
          if ($aux[$i] === $arr1[$j]['ieps_exento']) {
            $sum += $arr1[$j]['ieps_exento'];
          }
        }
        $impuestos .= "<tr><th>IEPS Exento " . $aux[$i] . "%:</th><td>$ " . number_format($sum, 2) . "</td><tr>";
      }
      //$total += $sum;
    }

    $aux = array_values(array_unique(array_column($arr1, 'isr_retenido_monto_fijo')));

    for ($i = 0; $i < count($aux); $i++) {
      $sum = 0;
      if ($aux[$i] !== null && $aux[$i] !== "") {
        for ($j = 0; $j < count($arr1); $j++) {
          if ($aux[$i] === $arr1[$j]['isr_retenido_monto_fijo']) {
            $sum += $arr1[$j]['isr_retenido_monto_fijo'];
          }
        }
        $impuestos .= "<tr><th>ISR Retenido (Monto fijo):</th><td>$ " . number_format($sum, 2) . "</td><tr>";
      }
      $total -= $sum;
    }

    $aux = array_values(array_unique(array_column($arr1, 'ieps_retenido_monto_fijo')));

    for ($i = 0; $i < count($aux); $i++) {
      $sum = 0;
      if ($aux[$i] !== null && $aux[$i] !== "") {
        for ($j = 0; $j < count($arr1); $j++) {
          if ($aux[$i] === $arr1[$j]['ieps_retenido_monto_fijo']) {
            $tasa = $arr1[$j]['ieps_retenido_monto_fijo'];            
            $sum += $arr1[$j]['ieps_retenido_monto_fijo'] * $arr1[$j]['cantidad'];
          }
        }
        $impuestos .= "<tr><th>IEPS Retenido (Monto fijo): $</th><td>" . number_format($sum, 2) . "</td><tr>";
      }
      $total -= $sum;
    }

    if($impuestos !== ""){
      //$impuestos = substr($impuestos, 0, strlen($impuestos) - 4);
      $impuestos .= "</tbody></table>";
    } else {
      $impuestos = "Sin impuestos";
    }

    $array = [
      "row_count" => $rowCount,
      "subtotal" => "$ " . number_format($arr[0]['subtotal'], 2),
      "impuestos" => $impuestos,
      "total" => "$ " . number_format($total, 2)
    ];

    return $array;
  }

  function getNumDecimal($value){
    $cont = 0;
    $num_decimals = [];
    $aux = explode(".",$value);

    if(count( $aux) > 1){
      for ($i=0; $i < strlen($aux[1]); $i++) {
        if($aux[1][$i] !== 0){
          $cont++;
        }
        
      }

    }
    
  }

  function getDecimalData($value){
    $format_number = 0;
    $cont = 0;

    $aux = explode(".",$value);

    if(count( $aux) > 1){
      for ($i=0; $i < strlen($aux[1]); $i++) {
        if($aux[1][$i] !== 0){
          $cont++;
        }
        
      }
    }

    if($cont < 3){
      $format_number = "$ " . number_format($value,2);
    } else {
      $format_number = "$ " . number_format($value,$cont);
    }

    return $format_number;
  }

  function unique_multidim_array($array, $key)
  {
    $temp_array = array();
    $i = 0;
    $key_array = array();

    foreach ($array as $val) {
      if (!in_array($val[$key], $key_array)) {
        $key_array[$i] = $val[$key];
        $temp_array[$i] = $val;
      }
      $i++;
    }
    return $temp_array;
  }

  //se añadió parametro folio para enviar el folio de la salida cuando se trate de un pedido (tipo 3)
  function getTruncateTableProducts($value,$type,$ref, $folio = 0)
  {
    $con = new conectar();
    $db = $con->getDb();

    if($type != 3 || $folio == 1){
      $query = sprintf("delete from datos_producto_facturacion_temp where usuario_id = :usuario_id and factura_concepto = :factura_concepto and tipo = :tipo and referencia = :ref");
      $stmt = $db->prepare($query);
      $stmt->bindValue(":usuario_id", $_SESSION['PKUsuario']);
      $stmt->bindValue(":factura_concepto",$value);
      $stmt->bindValue(":tipo", $type);
      $stmt->bindValue(":ref", $ref);
      return $stmt->execute();
    }else{
      $query0 = sprintf("DELETE d from datos_producto_facturacion_temp d
            inner join (select s.id, s.folio_salida as folio, pr.PKProducto 
                          from inventario_salida_por_sucursales s inner join productos pr on s.clave = pr.ClaveInterna
                        where s.id = :ref and pr.empresa_id = :id_empresa
                          union
                        select ms.PKMovServ as id, ms.FKSalida as folio, ms.FKProducto as PKProducto from movimientos_salidas_servicios_sin_inventario ms where ms.PKMovServ = :ref2
                        ) as sf on sf.id = :ref3 and d.producto_id = sf.PKProducto
            where usuario_id = :usuario_id and tipo = 3 and referencia = :ref4 and sf.folio = :folio;");
      $stmt0 = $db->prepare($query0);
      $stmt0->bindValue(":usuario_id", $_SESSION['PKUsuario']);
      $stmt0->bindValue(":ref", $ref);
      $stmt0->bindValue(":id_empresa", $_SESSION['IDEmpresa']);
      $stmt0->bindValue(":ref2", $ref);
      $stmt0->bindValue(":ref3", $ref);
      $stmt0->bindValue(":ref4", $ref);
      $stmt0->bindValue(":folio", $folio);
      return $stmt0->execute();
    }

  }

  function getClientes()
  {
    $con = new conectar();
    $db = $con->getDb();

    $query = sprintf("select PKCliente id, razon_social texto from clientes where empresa_id = :empresa_id");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":empresa_id", $_SESSION['IDEmpresa']);
    $stmt->execute();

    return $stmt->fetchAll();
  }

  function getProductos($value)
  {
    $con = new conectar();
    $db = $con->getDb();

    $query = sprintf("select p.PKProducto id, concat(p.ClaveInterna,' ',p.Nombre) texto from productos p
                        left join costo_especial_producto_cliente pc on p.PKProducto = pc.FKProducto and pc.FKCliente = :cliente_id
                        left join operaciones_producto op on p.PKProducto = op.FKProducto
                        where op.Venta = 1 and p.empresa_id = :empresa_id");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":cliente_id", $value);
    $stmt->bindValue(":empresa_id", $_SESSION['IDEmpresa']);
    $stmt->execute();

    return $stmt->fetchAll();
  }

  function getPrice($value,$client)
  {
    $con = new conectar();
    $db = $con->getDb();

    $query = sprintf("select costoEspecial price from costo_especial_producto_cliente where FKProducto = :producto_id and FKCliente = :cliente_id");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":producto_id", $value);
    $stmt->bindValue(":cliente_id", $client);
    $stmt->execute();
    $arr = $stmt->fetchAll();

    $query = sprintf("select CostoGeneral price from costo_venta_producto where FKProducto = :producto_id");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":producto_id", $value);
    $stmt->execute();
    $arr1 = $stmt->fetchAll();
    $precio = 0;

    // if (count($arr) > 0) {

    //   $precio = ($arr[0]['price'] !== null && $arr[0]['price'] !== "") ? $arr[0]['price'] : $precio;
    // } else {
    //   $precio = 0;
    // }
    if(count($arr) > 0){
        $precio =$arr[0]['price'];
    } else {
        $precio =$arr1[0]['price'];
    }

    return $precio;
  }

  function getProductosAll()
  {
    $con = new conectar();
    $db = $con->getDb();

    $query = sprintf("select p.PKProducto id, concat(p.ClaveInterna,' ',p.Nombre) texto from productos p
                        where p.empresa_id = :empresa_id");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":empresa_id", $_SESSION['IDEmpresa']);
    $stmt->execute();

    return $stmt->fetchAll();
  }

  function getPriceAll($value)
  {
    $con = new conectar();
    $db = $con->getDb();

    $query = sprintf("select CostoGeneral price from costo_venta_producto where FKProducto = :producto_id");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":producto_id", $value);
    $stmt->execute();

    $arr = $stmt->fetchAll();
    $precio = 0;

    if (count($arr) > 0) {

      $precio = ($arr[0]['price'] !== null && $arr[0]['price'] !== "") ? $arr[0]['price'] : number_format($precio, 2);
    } else {
      $precio = number_format(0, 2);
    }

    return $precio;
  }

  function getLastUsoCFDI($value)
  {
    $con = new conectar();
    $db = $con->getDb();
    $array = [];

    $query = sprintf("select uso_cfdi_id,forma_pago_id,metodo_pago from facturacion where usuario_timbro_id = :usuario_id and tipo_factura = :tipo_factura  order by id desc limit 1");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":usuario_id", $_SESSION['PKUsuario']);
    $stmt->bindValue(":tipo_factura", $value);
    $stmt->execute();

    $rowCount = $stmt->rowCount();
    $arr = $stmt->fetchAll();

    if ($rowCount > 0) {
      array_push(
        $array,
        array(
          "uso_cfdi_id" => $arr[0]["uso_cfdi_id"],
          "forma_pago_id" => $arr[0]["forma_pago_id"],
          "metodo_pago" => $arr[0]["metodo_pago"]
        )
      );
    } else {
      array_push(
        $array,
        array(
          "uso_cfdi_id" => 1,
          "forma_pago_id" => 1,
          "metodo_pago" => 1
        )
      );
    }

    return $array;
  }

  function getCuentasBancarias()
  {
    $con = new conectar();
    $db = $con->getDb();

    $query = sprintf("select PKCuenta id, Nombre texto from cuentas_bancarias_empresa where empresa_id = :id and tipo_cuenta = 1");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":id", $_SESSION['IDEmpresa']);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }

  function getVendedores()
  {
    $con = new conectar();
    $db = $con->getDb();

    $query = sprintf("select e.PKEmpleado id, concat(e.Nombres,' ',e.PrimerApellido,' ',e.SegundoApellido) texto
                        from empleados e
                          left join relacion_tipo_empleado rte on e.PKEmpleado = rte.empleado_id
                        where empresa_id = :id_empresa and rte.tipo_empleado_id = 1");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":id_empresa", $_SESSION['IDEmpresa']);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }

  function getVendedor($tipo, $referencia, $id)
  {
    $con = new conectar();
    $db = $con->getDb();
    $data = [];
    
    if(!is_array($referencia)){
      $ref = (int)$referencia;
    } else {
      if(count($referencia) > 1){
        $ref = implode(",",$referencia);
      } else {
        $ref = $referencia[0];
      }
    }

    switch ($tipo) {
      case 1:
        $query = sprintf("select e.PKEmpleado id,concat(e.Nombres,' ',e.PrimerApellido,' ',e.SegundoApellido) vendedor, e.email 
                          from cotizacion co
                            inner join empleados e on co.empleado_id = e.PKEmpleado
                          where co.PKCotizacion = :id_co");
        $stmt = $db->prepare($query);
        $stmt->bindValue(":id_co", $ref);
        $stmt->execute();

        $aux = $stmt->fetchAll();
        $vendedor = $aux[0]['vendedor'];
        $ref1 = "Cotizacion: " . $ref;
        $id_vendedor = $aux[0]['id'];
        $email_vendedor = $aux[0]['email'];
        break;
      case 2:
        $query = sprintf("select e.PKEmpleado id, concat(e.Nombres,' ',e.PrimerApellido,' ',e.SegundoApellido) vendedor, e.email
                            from ventas_directas vd
                              inner join empleados e on vd.empleado_id = e.PKEmpleado
                            where vd.PKVentaDirecta = :id_vd");
        $stmt = $db->prepare($query);
        $stmt->bindValue(":id_vd", $ref);
        $stmt->execute();

        $aux = $stmt->fetchAll();
        $vendedor = $aux[0]['vendedor'];
        $ref1 = "Venta directa: " . $ref;
        $id_vendedor = $aux[0]['id'];
        $email_vendedor = $aux[0]['email'];
        break;
      case 3:
        for($i = 0; $i < count($referencia); $i++){
          $query = sprintf("select distinct numero_cotizacion cot,numero_venta_directa vd
                            from orden_pedido_por_sucursales p 
                              left join inventario_salida_por_sucursales s on s.orden_pedido_id = p.id
                              left join movimientos_salidas_servicios_sin_inventario ms on ms.FKOrdenPedido = p.id  
                            where (s.folio_salida = :ref or ms.FKSalida = :ref2) and p.empresa_id = :emp
                          ");
          $stmt = $db->prepare($query);
          $stmt->bindValue(":ref", $referencia[$i]);
          $stmt->bindValue(":ref2", $referencia[$i]);
          $stmt->bindValue(":emp", $_SESSION['IDEmpresa']);
          $stmt->execute();

          $aux = $stmt->fetchAll();
          $ref1 = "Pedido: " . $referencia[$i];

          if ($aux[0]['cot'] !== null && $aux[0]['cot'] !== "") {
            $query = sprintf("select e.PKEmpleado id, concat(e.Nombres,' ',e.PrimerApellido,' ',e.SegundoApellido) vendedor, e.email
                                from cotizacion co
                                  inner join empleados e on co.empleado_id = e.PKEmpleado
                                where co.PKCotizacion = :id_co ");
            $stmt = $db->prepare($query);
            $stmt->bindValue(":id_co", $aux[0]['cot']);
            $stmt->execute();

            $arr = $stmt->fetchAll();

            $vendedor = $arr[0]['vendedor'];
            $id_vendedor = $arr[0]['id'];
            $email_vendedor = $arr[0]['email'];
          } else if ($aux[0]['vd'] !== null && $aux[0]['vd'] !== "") {
            $query = sprintf("select e.PKEmpleado id, concat(e.Nombres,' ',e.PrimerApellido,' ',e.SegundoApellido) vendedor, e.email
                                  from ventas_directas vd
                                    inner join empleados e on vd.empleado_id = e.PKEmpleado
                                  where vd.PKVentaDirecta = :id_vd");
            $stmt = $db->prepare($query);
            $stmt->bindValue(":id_vd", $aux[0]['vd']);
            $stmt->execute();

            $arr = $stmt->fetchAll();
            $vendedor = $arr[0]['vendedor'];
            $id_vendedor = $arr[0]['id'];
            $email_vendedor = $arr[0]['email'];
          }
      }
        break;
      case 4:
        $query = sprintf("select distinct salida_id from remisiones where id = :id");
        $stmt = $db->prepare($query);
        $stmt->bindValue(":id", $referencia);
        $stmt->execute();

        $aux = $stmt->fetchAll();
        $ref1 = "Remisión: " . $referencia;
        $aux = explode(",", $aux[0]['salida_id']);

        $query = sprintf("select distinct numero_cotizacion cot,numero_venta_directa vd
                            from inventario_salida_por_sucursales s
                            inner join orden_pedido_por_sucursales p on s.orden_pedido_id = p.id
                          where s.folio_salida = :folio and p.empresa_id = :emp
                        ");
        $stmt = $db->prepare($query);
        $stmt->bindValue(":folio", $aux[0]);
        $stmt->bindValue(":emp", $_SESSION['IDEmpresa']);
        $stmt->execute();

        $ref = $stmt->fetchAll();

        if ($ref[0]['cot'] !== null && $ref[0]['cot'] !== "") {
          $query = sprintf("select e.PKEmpleado id, concat(e.Nombres,' ',e.PrimerApellido,' ',e.SegundoApellido) vendedor, e.email
                            from cotizacion co
                              inner join empleados e on co.empleado_id = e.PKEmpleado
                            where co.PKCotizacion = :id_co ");
          $stmt = $db->prepare($query);
          $stmt->bindValue(":id_co", $ref[0]['cot']);
          $stmt->execute();

          $arr = $stmt->fetchAll();
          $vendedor = $arr[0]['vendedor'];
          $id_vendedor = $arr[0]['id'];
          $email_vendedor = $arr[0]['email'];
        } else if ($ref[0]['vd'] !== null && $ref[0]['vd'] !== "") {
          $query = sprintf("select e.PKEmpleado id, concat(e.Nombres,' ',e.PrimerApellido,' ',e.SegundoApellido) vendedor, e.email
                            from ventas_directas vd
                              inner join empleados e on vd.empleado_id = e.PKEmpleado
                            where vd.PKVentaDirecta = :id_vd");
          $stmt = $db->prepare($query);
          $stmt->bindValue(":id_vd", $ref[0]['vd']);
          $stmt->execute();

          $arr = $stmt->fetchAll();
          $vendedor = $arr[0]['vendedor'];
          $id_vendedor = $arr[0]['id'];
          $email_vendedor = $arr[0]['email'];
        } else {
          $vendedor = "Traslado";
          $id_vendedor = null;
          $email_vendedor = null;
        }
        break;
        /*
        case 0:
          $query = sprintf("select e.PKEmpleado id, concat(e.Nombres,' ',e.PrimerApellido,' ',e.SegundoApellido) vendedor
                          from facturacion f
                            left join empleados e on f.empleado_id = e.PKEmpleado
                          where f.id = :id");
          $stmt = $db->prepare($query);
          $stmt->bindValue(":id",$id);
          $stmt->execute();

          $aux = $stmt->fetchAll();

          $id_vendedor = count($aux) > 0 && $aux['vendedor'] !== null && $aux['vendedor'] !== "" ?  $aux['id'] : null;
          $vendedor = count($aux) > 0 && $aux['vendedor'] !== null && $aux['vendedor'] !== "" ?  $aux['vendedor'] : "Sin vendedor";
          $referencia = "Factura por concepto";
          break;
          */
    }

    array_push($data, array(
      "id" => $id_vendedor,
      "vendedor" => $vendedor,
      "email" => $email_vendedor,
      "referencia" => $ref1
    ));

    return json_encode($data);
  }

  function getRFCCliente($idCliente)
  {
    $con = new conectar();
    $db = $con->getDb();
    $query = 'SELECT rfc FROM clientes WHERE PKCliente = :id';
    $stmt = $db->prepare($query);

    if ($stmt->execute([':id' => $idCliente])) {
      $res = $stmt->fetch(PDO::FETCH_ASSOC);
      return ['status' => 'success', 'data' => $res['rfc']];
    }
    return ['status' => 'fail', 'message' => 'Algo salio mal.'];
  }

  function getClienteBilling($idCliente)
  {
    $con = new conectar();
    $db = $con->getDb();
    $query = 'SELECT PKCliente, razon_social, regimen_fiscal_id, RFC as rfc, codigo_postal FROM clientes WHERE PKCliente = :id';
    $stmt = $db->prepare($query);

    $stmt->execute([':id' => $idCliente]);
    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }

  function getDataDocumentation($arr,$tipo,$ref)
  {
    $con = new conectar();
    $db = $con->getDb();
    $impuestos = "";
    $table = "";
    $cont = 0;
    $detalleProducto = [];

    foreach ($arr as $r) {

      $lote = isset($r['lote']) ? $r['lote'] : "";
      $caducidad = isset($r['caducidad']) ? $r['caducidad'] : "";
      $serie = isset($r['serie']) ? $r['serie'] : "";
      $impuestos_importe = 0;
      $tasa_iva = null;
      $monto_iva = null;
      $tasa_ieps = null;
      $monto_ieps = null;
      $monto_ieps_fijo = null;
      $tasa_ish = null;
      $monto_ish = null;
      $tasa_iva_exento = null;
      $monto_iva_exento = null;
      $tasa_iva_retenido = null;
      $monto_iva_retenido = null;
      $tasa_isr_retenido = null;
      $monto_isr_retenido = null;
      $tasa_isn_local = null;
      $monto_isn_local = null;
      $tasa_cedular = null;
      $monto_cedular = null;
      $tasa_al_millar = null;
      $monto_al_millar = null;
      $tasa_funcion_publica = null;
      $monto_funcion_publica = null;
	    $tasa_ieps_retenido = null;
      $monto_ieps_retenido = null;
      $tasa_isr_exento = null;
      $monto_isr_exento = null;
      $isr_monto_fijo = null;
      $tasa_isr = null;
      $monto_isr = null;
      $tasa_ieps_exento = null;
      $monto_ieps_exento = null;
      $isr_retenido_monto_fijo = null;
      $ieps_retenido_monto_fijo = null;
      $importe = $r['cantidad'] * $r['precio_unitario'];

      $query1 = sprintf("select imp.PKImpuesto id,imp.Nombre nombre,ipr.Tasa tasa,imp.FKTipoImporte tipo from productos pr
                            inner join info_fiscal_productos ifp on pr.PKProducto = ifp.FKProducto
                            inner join impuestos_productos ipr on ifp.PKInfoFiscalProducto = ipr.FKInfoFiscalProducto
                            inner join impuesto imp on ipr.FKImpuesto = imp.PKImpuesto
                          where pr.PKProducto = :producto");
      $stmt1 = $db->prepare($query1);
      $stmt1->bindValue(":producto", $r['producto_id']);
      $stmt1->execute();

      $arr1 =  $stmt1->fetchAll();

      if (count($arr1) > 0) {
        foreach ($arr1 as $r1) {
          switch ($r1['id']) { 
            case 1:
              $tasa_iva = $r1['tasa'];
              $monto_iva = ($importe * ($r1['tasa'] / 100));            
              if($monto_ieps !== null){
                $monto_iva += $monto_ieps * ($tasa_iva / 100);
              }
              if($monto_ieps_fijo !== null){
                $monto_iva += ($r['cantidad'] * $monto_ieps_fijo) * ($tasa_iva / 100);
              }
              $impuestos_importe += $monto_iva;
              break;
            case 2:
              $tasa_ieps = $r1['tasa'];
              $monto_ieps = ($importe * ($r1['tasa'] / 100));
              if($monto_iva !== null){
                $monto_iva += $monto_ieps * ($tasa_iva / 100);
                $impuestos_importe += $monto_ieps * ($tasa_iva / 100);
              }
              $impuestos_importe += $monto_ieps;
              break;
            case 3:
              $monto_ieps_fijo = $r1['tasa'];
              if($monto_iva !== null){
                $monto_iva += ($r['cantidad'] * $r1['tasa']) * ($tasa_iva / 100);
                $impuestos_importe += ($r['cantidad'] * $r1['tasa']) * ($tasa_iva / 100);
              }
              $impuestos_importe += $r['cantidad'] * $r1['tasa'];
              break;
            case 4:
              $tasa_ish = $r1['tasa'];
              $monto_ish = ($importe * ($r1['tasa'] / 100));
              $impuestos_importe += $monto_ish;
              break;
            case 5:
              $tasa_iva_exento = $r1['tasa'];
              $monto_iva_exento = ($importe * ($r1['tasa'] / 100));
              break;
            case 6:
              $tasa_iva_retenido = $r1['tasa'];
              $monto_iva_retenido = ($importe * ($r1['tasa'] / 100));
              $impuestos_importe -= $monto_iva_retenido;
              break;
            case 7:
              $tasa_isr = $r1['tasa'];
              $monto_isr = ($importe * ($r1['tasa'] / 100));
              $impuestos_importe -= $monto_isr;
              break;
            case 8:
              $tasa_isn_local = $r1['tasa'];
              $monto_isn_local = ($importe * ($r1['tasa'] / 100));
              $impuestos_importe += $monto_isn_local;
              break;
            case 9:
              $tasa_cedular = $r1['tasa'];
              $monto_cedular = ($importe * ($r1['tasa'] / 100));
              $impuestos_importe += $monto_cedular;
              break;
            case 10:
              $tasa_al_millar = $r1['tasa'];
              $monto_al_millar = ($importe * ($r1['tasa'] / 100));
              $impuestos_importe += $monto_al_millar;
              break;
            case 11:
              $tasa_funcion_publica = $r1['tasa'];
              $monto_funcion_publica = ($importe * ($r1['tasa'] / 100));
              $impuestos_importe += $monto_funcion_publica;
              break;
			      case 12:
              $tasa_ieps_retenido = $r1['tasa'];
              $monto_ieps_retenido = ($importe * ($r1['tasa'] / 100));
              $impuestos_importe -= $monto_ieps_retenido;
              break;
            case 13:
              $tasa_isr_exento = $r1['tasa'];
              $monto_isr_exento = ($importe * ($r1['tasa'] / 100));
              break;
            case 14:
              $monto_isr_monto_fijo = ($importe * ($r1['tasa'] / 100));
              $impuestos_importe -= $monto_isr_monto_fijo;
              break;
            case 15:
              $tasa_isr_retenido = $r1['tasa'];
              $monto_isr_retenido = ($importe * ($r1['tasa'] / 100));
              $impuestos_importe -= $monto_isr_retenido;
              break;
            case 16:
              $tasa_ieps_exento = $r1['tasa'];
              $monto_ieps_exento = ($importe * ($r1['tasa'] / 100));
              break;
            case 17:
              $isr_retenido_monto_fijo = $r1['tasa'];
              $impuestos_importe -= $isr_retenido_monto_fijo;
              break;
            case 18:
              $ieps_retenido_monto_fijo = $r1['tasa'];
              $impuestos_importe -= $r['cantidad'] * $r1['tasa'];
              break;
          }
        }
        $impuestos = substr($impuestos, 0, strlen($impuestos) - 4);
      } else {
        $impuestos = "Sin impuestos";
      }
      $totalDoc = ($r['cantidad'] * $r['precio_unitario']) + $impuestos_importe;

      $query2 = sprintf("insert into datos_producto_facturacion_temp (
                            referencia,
                            tipo,
                            producto_id,
                            unidad_medida_id,
                            clave_sat_id,
                            cantidad,
                            cantidad_facturada,
                            precio_unitario,
                            total_bruto,
                            iva,
                            importe_iva,
                            ieps,
                            importe_ieps,
                            ieps_monto_fijo,
                            ish,
                            importe_ish,
                            iva_exento,
                            importe_iva_exento,
                            iva_retenido,
                            importe_iva_retenido,
                            isr_retenido,
                            importe_isr_retenido,
                            isn_local,
                            importe_isn_local,
                            cedular,
                            importe_cedular,
                            al_millar,
                            importe_al_millar,
                            funcion_publica,
                            importe_funcion_publica,
                            ieps_retenido,
                            importe_ieps_retenido,
                            isr_exento,
                            importe_isr_exento,
                            isr_monto_fijo,
                            isr,
                            importe_isr,
                            ieps_exento,
                            importe_ieps_exento,
                            isr_retenido_monto_fijo,
                            ieps_retenido_monto_fijo,
                            total_neto,
                            usuario_id,
                            numero_lote,
                            caducidad,
                            numero_serie
                          ) 
                          values (
                            :referencia,
                            :tipo,
                            :producto_id,
                            :unidad_medida_id,
                            :clave_sat_id,
                            :cantidad,
                            :cantidad_facturada,
                            :precio_unitario,
                            :total_bruto,
                            :iva,
                            :importe_iva,
                            :ieps,
                            :importe_ieps,
                            :ieps_monto_fijo,
                            :ish,
                            :importe_ish,
                            :iva_exento,
                            :importe_iva_exento,
                            :iva_retenido,
                            :importe_iva_retenido,
                            :isr_retenido,
                            :importe_isr_retenido,
                            :isn_local,
                            :importe_isn_local,
                            :cedular,
                            :importe_cedular,
                            :al_millar,
                            :importe_al_millar,
                            :funcion_publica,
                            :importe_funcion_publica,
                            :ieps_retenido,
                            :importe_ieps_retenido,
                            :isr_exento,
                            :importe_isr_exento,
                            :isr_monto_fijo,
                            :isr,
                            :importe_isr,
                            :ieps_exento,
                            :importe_ieps_exento,
                            :isr_retenido_monto_fijo,
                            :ieps_retenido_monto_fijo,                             
                            :total_neto,
                            :usuario_id,
                            :numero_lote,
                            :caducidad,
                            :numero_serie
                        )");
      $stmt2 = $db->prepare($query2);
      $stmt2->bindValue(":referencia", $r['id']);
      $stmt2->bindValue(":tipo", $tipo);
      $stmt2->bindValue(":producto_id", $r['producto_id']);
      $stmt2->bindValue(":unidad_medida_id", $r['FKClaveSATUnidad']);
      $stmt2->bindValue(":clave_sat_id", $r['FKClaveSAT']);
      $stmt2->bindValue(":cantidad", $r['cantidad']);
      $stmt2->bindValue(":cantidad_facturada", $r['cantidad']);
      $stmt2->bindValue(":precio_unitario", $r['precio_unitario']);
      $stmt2->bindValue(":total_bruto", $importe);
      $stmt2->bindValue(":iva", $tasa_iva);
      $stmt2->bindValue(":importe_iva", $monto_iva);
      $stmt2->bindValue(":ieps", $tasa_ieps);
      $stmt2->bindValue(":importe_ieps", $monto_ieps);
      $stmt2->bindValue(":ieps_monto_fijo", $monto_ieps_fijo);
      $stmt2->bindValue(":ish", $tasa_ish);
      $stmt2->bindValue(":importe_ish", $monto_ish);
      $stmt2->bindValue(":iva_exento", $tasa_iva_exento);
      $stmt2->bindValue(":importe_iva_exento", $monto_iva_exento);
      $stmt2->bindValue(":iva_retenido", $tasa_iva_retenido);
      $stmt2->bindValue(":importe_iva_retenido", $monto_iva_retenido);
      $stmt2->bindValue(":isr_retenido", $tasa_isr_retenido);
      $stmt2->bindValue(":importe_isr_retenido", $monto_isr_retenido);
      $stmt2->bindValue(":isn_local", $tasa_isn_local);
      $stmt2->bindValue(":importe_isn_local", $monto_isn_local);
      $stmt2->bindValue(":cedular", $tasa_cedular);
      $stmt2->bindValue(":importe_cedular", $monto_cedular);
      $stmt2->bindValue(":al_millar", $tasa_al_millar);
      $stmt2->bindValue(":importe_al_millar", $monto_al_millar);
      $stmt2->bindValue(":funcion_publica", $tasa_funcion_publica);
      $stmt2->bindValue(":importe_funcion_publica", $monto_funcion_publica);
	    $stmt2->bindValue(":ieps_retenido", $tasa_ieps_retenido);
      $stmt2->bindValue(":importe_ieps_retenido", $monto_ieps_retenido);
      $stmt2->bindValue(":isr_exento", $tasa_isr_exento);
      $stmt2->bindValue(":importe_isr_exento", $monto_isr_exento);
      $stmt2->bindValue(":isr_monto_fijo", $isr_monto_fijo);
      $stmt2->bindValue(":isr", $tasa_isr);
      $stmt2->bindValue(":importe_isr", $monto_isr);
      $stmt2->bindValue(":ieps_exento", $tasa_ieps_exento);
      $stmt2->bindValue(":importe_ieps_exento", $monto_ieps_exento);
      $stmt2->bindValue(":isr_retenido_monto_fijo", $isr_retenido_monto_fijo);
      $stmt2->bindValue(":ieps_retenido_monto_fijo", $ieps_retenido_monto_fijo);      
      $stmt2->bindValue(":total_neto", $totalDoc);
      $stmt2->bindValue(":usuario_id", $_SESSION['PKUsuario']);
      $stmt2->bindValue(":numero_lote", $lote);
      $stmt2->bindValue(":caducidad", $caducidad);
      $stmt2->bindValue(":numero_serie", $serie);
      $stmt2->execute();
    }
    if($tipo !== 3){
      $query3 = sprintf("select 
                            dpft.id id_row,
                            dpft.referencia,
                            pr.PKProducto id, 
                            pr.ClaveInterna clave,
                            pr.Nombre nombre,
                            dpft.unidad_medida_id id_unidad_medida,
                            csu.Descripcion unidad_medida,
                            dpft.clave_sat_id sat_id,
                            dpft.descuento_tasa,
                            dpft.importe_descuento_tasa,
                            dpft.descuento_monto_fijo,
                            dpft.cantidad cantidad_total,
                            dpft.cantidad_facturada cantidad_facturada,
                            dpft.precio_unitario,
                            dpft.total_bruto,
                            dpft.iva,
                            dpft.importe_iva,
                            dpft.ieps,
                            dpft.importe_ieps,
                            dpft.ieps_monto_fijo,
                            dpft.ish,
                            dpft.importe_ish,
                            dpft.iva_exento,
                            dpft.importe_iva_exento,
                            dpft.iva_retenido,
                            dpft.importe_iva_retenido,
                            dpft.isr_retenido,
                            dpft.importe_isr_retenido,
                            dpft.isn_local,
                            dpft.importe_isn_local,
                            dpft.cedular,
                            dpft.importe_cedular,
                            dpft.al_millar,
                            dpft.importe_al_millar,
                            dpft.funcion_publica,
                            dpft.importe_funcion_publica,
                            dpft.ieps_retenido,
                            dpft.importe_ieps_retenido,
                            dpft.isr_exento,
                            dpft.importe_isr_exento,
                            dpft.isr_monto_fijo,
                            dpft.isr,
                            dpft.importe_isr,
                            dpft.ieps_exento,
                            dpft.importe_ieps_exento,
                            dpft.isr_retenido_monto_fijo,
                            dpft.ieps_retenido_monto_fijo,
                            dpft.total_neto
                          from datos_producto_facturacion_temp dpft
                            inner join productos pr on dpft.producto_id = pr.PKProducto
                            left join claves_sat_unidades csu on dpft.unidad_medida_id = csu.PKClaveSATUnidad
                          where usuario_id = :usuario_id and factura_concepto = 0 and tipo = :tipo and referencia = :ref");
      $stmt3 = $db->prepare($query3);
      $stmt3->bindValue(":usuario_id", $_SESSION['PKUsuario']);
      $stmt3->bindValue(":tipo", $tipo);
      $stmt3->bindValue(":ref", $ref);
      $stmt3->execute();

      $detalleProducto = $stmt3->fetchAll();
    } else {
      foreach ($ref as $r) {
        $query3 = sprintf("select 
                            dpft.id id_row,
                            dpft.referencia,
                            pr.PKProducto id, 
                            pr.ClaveInterna clave,
                            pr.Nombre nombre,
                            dpft.unidad_medida_id id_unidad_medida,
                            csu.Descripcion unidad_medida,
                            dpft.clave_sat_id sat_id,
                            dpft.descuento_tasa,
                            dpft.importe_descuento_tasa,
                            dpft.descuento_monto_fijo,
                            dpft.cantidad cantidad_total,
                            dpft.cantidad_facturada cantidad_facturada,
                            dpft.precio_unitario,
                            dpft.total_bruto,
                            dpft.iva,
                            dpft.importe_iva,
                            dpft.ieps,
                            dpft.importe_ieps,
                            dpft.ieps_monto_fijo,
                            dpft.ish,
                            dpft.importe_ish,
                            dpft.iva_exento,
                            dpft.importe_iva_exento,
                            dpft.iva_retenido,
                            dpft.importe_iva_retenido,
                            dpft.isr_retenido,
                            dpft.importe_isr_retenido,
                            dpft.isn_local,
                            dpft.importe_isn_local,
                            dpft.cedular,
                            dpft.importe_cedular,
                            dpft.al_millar,
                            dpft.importe_al_millar,
                            dpft.funcion_publica,
                            dpft.importe_funcion_publica,
                            dpft.ieps_retenido,
                            dpft.importe_ieps_retenido,
                            dpft.isr_exento,
                            dpft.importe_isr_exento,
                            dpft.isr_monto_fijo,
                            dpft.isr,
                            dpft.importe_isr,
                            dpft.ieps_exento,
                            dpft.importe_ieps_exento,
                            dpft.isr_retenido_monto_fijo,
                            dpft.ieps_retenido_monto_fijo,
                            dpft.total_neto,
                            dpft.numero_lote,
                            dpft.caducidad,
                            dpft.numero_serie
                          from datos_producto_facturacion_temp dpft
                          inner join (select s.id, s.folio_salida as folio, pr.PKProducto 
                                        from inventario_salida_por_sucursales s inner join productos pr on s.clave = pr.ClaveInterna
                                      where s.id = :ref and pr.empresa_id = :empresa_id
                                        union
                                      select ms.PKMovServ as id, ms.FKSalida as folio, ms.FKProducto as PKProducto from movimientos_salidas_servicios_sin_inventario ms where ms.PKMovServ = :ref1
                                      ) as sf on sf.id = :ref2 and dpft.producto_id = sf.PKProducto
                            inner join productos pr on dpft.producto_id = pr.PKProducto
                            left join claves_sat_unidades csu on dpft.unidad_medida_id = csu.PKClaveSATUnidad
                          where usuario_id = :usuario_id and factura_concepto = 0 and tipo = :tipo and referencia = :ref3 and sf.folio = :folio");
        $stmt3 = $db->prepare($query3);
        $stmt3->bindValue(":usuario_id", $_SESSION['PKUsuario']);
        $stmt3->bindValue(":tipo", $tipo);
        $stmt3->bindValue(":ref", $r['id']);
        $stmt3->bindValue(":ref1", $r['id']);
        $stmt3->bindValue(":ref2", $r['id']);
        $stmt3->bindValue(":ref3", $r['id']);
        $stmt3->bindValue(":folio", $r['folio']);
        $stmt3->bindValue(":empresa_id", $_SESSION['IDEmpresa']);
        $stmt3->execute();

        $aux_prod = $stmt3->fetchAll();

        foreach($aux_prod as $r1){
          array_push($detalleProducto,[
            "id_row"=>$r1['id_row'],
            "referencia"=>$r1['referencia'],
            "id"=>$r1['id'],
            "clave"=>$r1['clave'],
            "nombre"=>str_replace('"', '\"', $r1['nombre']),
            "id_unidad_medida"=>$r1['id_unidad_medida'],
            "unidad_medida"=>$r1['unidad_medida'],
            "sat_id"=>$r1['sat_id'],
            "descuento_tasa"=>$r1['descuento_tasa'],
            "importe_descuento_tasa"=>$r1['importe_descuento_tasa'],
            "descuento_monto_fijo"=>$r1['descuento_monto_fijo'],
            "cantidad_total"=>$r1['cantidad_total'],
            "cantidad_facturada"=>$r1['cantidad_facturada'],
            "precio_unitario"=>$r1['precio_unitario'],
            "total_bruto"=>$r1['total_bruto'],
            "iva"=>$r1['iva'],
            "importe_iva"=>$r1['importe_iva'],
            "ieps"=>$r1['ieps'],
            "importe_ieps"=>$r1['importe_ieps'],
            "ieps_monto_fijo"=>$r1['ieps_monto_fijo'],
            "ish"=>$r1['ish'],
            "importe_ish"=>$r1['importe_ish'],
            "iva_exento"=>$r1['iva_exento'],
            "importe_iva_exento"=>$r1['importe_iva_exento'],
            "iva_retenido"=>$r1['iva_retenido'],
            "importe_iva_retenido"=>$r1['importe_iva_retenido'],
            "isr_retenido"=>$r1['isr_retenido'],
            "importe_isr_retenido"=>$r1['importe_isr_retenido'],
            "isn_local"=>$r1['isn_local'],
            "importe_isn_local"=>$r1['importe_isn_local'],
            "cedular"=>$r1['cedular'],
            "importe_cedular"=>$r1['importe_cedular'],
            "al_millar"=>$r1['al_millar'],
            "importe_al_millar"=>$r1['importe_al_millar'],
            "funcion_publica"=>$r1['funcion_publica'],
            "importe_funcion_publica"=>$r1['importe_funcion_publica'],
            "ieps_retenido"=>$r1['ieps_retenido'],
            "importe_ieps_retenido"=>$r1['importe_ieps_retenido'],
            "isr_exento"=>$r1['isr_exento'],
            "importe_isr_exento"=>$r1['importe_isr_exento'],
            "isr_monto_fijo"=>$r1['isr_monto_fijo'],
            "isr"=>$r1['isr'],
            "importe_isr"=>$r1['importe_isr'],
            "ieps_exento"=>$r1['ieps_exento'],
            "importe_ieps_exento"=>$r1['importe_ieps_exento'],
            "isr_retenido_monto_fijo"=>$r1['isr_retenido_monto_fijo'],
            "ieps_retenido_monto_fijo"=>$r1['ieps_retenido_monto_fijo'],
            "total_neto"=>$r1['total_neto'],
            "lote"=>$r1['numero_lote'],
            "caducidad"=>$r1['caducidad'],
            "serie"=>$r1['numero_serie'],
          ]);
        }

      }
    }

    $alertaSat = "";

    foreach ($detalleProducto as $r) {
      $impuestos = "";
      $claveInterna = ($r['clave'] !== "" && $r['clave'] !== null) ? $r['clave'] : "S/C";

      $alertaSat = ($r['sat_id'] !== null && $r['sat_id'] !== "" && $r['sat_id'] !== 1) ? "" : '<img id=\"satAlert\" src=\"../../img/icons/ICONO ALERTA_Mesa de trabajo 1.svg\" style=\"width: 25px\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"No se asignó una clave SAT\">';

      $idUnidadMedida = ($r['id_unidad_medida'] !== "" && $r['id_unidad_medida'] !== null) ? $r['id_unidad_medida'] : "";
      $unidadMedida = ($r['unidad_medida'] !== "" && $r['unidad_medida'] !== null) ? $r['unidad_medida'] : "N/A";
      $cantidad = $r['cantidad_facturada'];

      if ($r['iva'] !== "" && $r['iva'] !== null) {
        $impuestos .= "IVA " . " " . $r['iva'] . "%: $ " . number_format($r['importe_iva'], 2) . "<br>";
      }
      if ($r['ieps'] !== "" && $r['ieps'] !== null) {
        $impuestos .= "IEPS " . " " . $r['ieps'] . "%: $ " . number_format($r['importe_ieps'], 2) . "<br>";
      }
      if ($r['ieps_monto_fijo'] !== "" && $r['ieps_monto_fijo'] !== null) {
        $impuestos .= "IEPS (Monto fijo) $ ".number_format($r['ieps_monto_fijo'], 2)." : $ "  . number_format($r['ieps_monto_fijo'] * $r['cantidad_total'], 2) . "<br>";
      }
      if ($r['ish'] !== "" && $r['ish'] !== null) {
        $impuestos .= "ISH " . " " . $r['ish'] . "%: $ " . number_format($r['importe_ish'], 2) . "<br>";
      }
      if ($r['iva_exento'] !== "" && $r['iva_exento'] !== null) {
        $impuestos .= "IVA Exento " . " " . $r['iva_exento'] . "%: $ " . number_format($r['importe_iva_exento'], 2) . "<br>";
      }
      if ($r['iva_retenido'] !== "" && $r['iva_retenido'] !== null) {
        $impuestos .= "IVA Retenido " . " " . $r['iva_retenido'] . "%: $ " . number_format($r['importe_iva_retenido'], 2) . "<br>";
      }
      if ($r['isr'] !== "" && $r['isr'] !== null) {
        $impuestos .= "ISR Retenido " . " " . $r['isr'] . "%: $ " . number_format($r['importe_isr'], 2) . "<br>";
      }
      if ($r['isn_local'] !== "" && $r['isn_local'] !== null) {
        $impuestos .= "ISN (Local) " . " " . $r['isn_local'] . "%: $ " . number_format($r['importe_isn_local'], 2) . "<br>";
      }
      if ($r['cedular'] !== "" && $r['cedular'] !== null) {
        $impuestos .= "Cedular " . " " . $r['cedular'] . "%: $ " . number_format($r['importe_cedular'], 2) . "<br>";
      }
      if ($r['al_millar'] !== "" && $r['al_millar'] !== null) {
        $impuestos .= "5 al millar (Local) " . " " . $r['al_millar'] . "%: $ " . number_format($r['importe_al_millar'], 2) . "<br>";
      }
      if ($r['funcion_publica'] !== "" && $r['funcion_publica'] !== null) {
        $impuestos .= "Función Pública " . " " . $r['funcion_publica'] . "%: $ " . number_format($r['importe_funcion_publica'], 2) . "<br>";
	  }
      if ($r['ieps_retenido'] !== "" && $r['ieps_retenido'] !== null) {
        $impuestos .= "IEPS Retenido " . $r['ieps_retenido'] . "%: $ " . number_format($r['importe_ieps_retenido'], 2) . "<br>";
      }
      if ($r['isr_exento'] !== "" && $r['isr_exento'] !== null) {
        $impuestos .= "ISR Exento " . $r['isr_exento'] . "%: $ " . number_format($r['importe_isr_exento'], 2) . "<br>";
      }
      if ($r['isr_monto_fijo'] !== "" && $r['isr_monto_fijo'] !== null) {
        $impuestos .= "ISR (Monto fijo) : " . number_format($r['isr_monto_fijo'], 2) . "<br>";
      }
      if ($r['isr_retenido'] !== "" && $r['isr_retenido'] !== null) {
        $impuestos .= "ISR Retenido " . $r['isr_retenido'] . "%: $ " . number_format($r['importe_isr_retenido'], 2) . "<br>";
      }
      if ($r['ieps_exento'] !== "" && $r['ieps_exento'] !== null) {
        $impuestos .= "ISR Exento" . $r['ieps_exento'] . "%: $ " . number_format($r['importe_ieps_exento'], 2) . "<br>";
      }
      if ($r['isr_retenido_monto_fijo'] !== "" && $r['isr_retenido_monto_fijo'] !== null) {
        $impuestos .= "ISR Retenido (Monto fijo): $ " . number_format($r['isr_retenido_monto_fijo'], 2) . "<br>";
      }
      if ($r['ieps_retenido_monto_fijo'] !== "" && $r['ieps_retenido_monto_fijo'] !== null) {
        $impuestos .= "IEPS Retenido (Monto fijo): $ " . number_format($r['ieps_retenido_monto_fijo'], 2) . "<br>";
      }

      if ($impuestos === "") {
        $impuestos = "Sin impuestos";
      }
      $descripcion = "";
      $impuestos .= "</tbody></table>";
      if ($r['descuento_tasa'] !== null && $r['descuento_tasa'] !== "") {
        $descuento = "Descuento " . $r['descuento_tasa'] . "%: " . $r['importe_descuento_tasa'];
      } else if ($r['descuento_monto_fijo'] !== null && $r['descuento_monto_fijo'] !== "") {
        $descuento = "Descuento: " . $r['importe_descuento_tasa'];
      } else {
        $descuento = "Sin descuento";
      }

      if(isset($r['lote']) && $r['lote'] !== null && $r['lote'] !== ""){
        if(isset($r['caducidad']) && $r['caducidad'] !== null && $r['caducidad'] !== ""){
          $descripcion = str_replace('"', '\"', $r['nombre']) . "<br> Lote: " . $r['lote'] . " Caducidad: " . $r['caducidad'];
        } else {
          $descripcion = str_replace('"', '\"', $r['nombre']) . "<br> Lote: " . $r['lote'];
        }
      } else if(isset($r['serie']) && $r['serie'] !== null && $r['serie'] !== ""){
        $descripcion = str_replace('"', '\"', $r['nombre']) . "<br> Serie: " . $r['serie'];
      } else {
        $descripcion = str_replace('"', '\"', $r['nombre']);
      }

      $edit = "<a id='edit" . $r['id'] . "' data-id='" . $r['id'] . "'  data-ref='" . $r['id_row'] . "' href='#' ><img src='../../img/icons/editar.svg' width='22px' data-toggle='tooltip' data-placement='right' title='Editar'>";
      $table .= '{
            "id":"' . $r['id'] . '",
            "edit":"' . $edit . '",
            "clave":"' . $claveInterna . '",
            "descripcion":"' . $descripcion . '",
            "id_unidad_medida":"' . $idUnidadMedida . '",
            "sat_id":"' . $r['sat_id'] . '",
            "unidad_medida":"' . $unidadMedida . '",
            "cantidad":"' . $cantidad . '",
            "precio":"$ ' . number_format($r['precio_unitario'], 2) . '",
            "subtotal":"$ ' . number_format(($r['cantidad_facturada'] * $r['precio_unitario']), 2) . '",
            "impuestos":"' . $impuestos . '",
            "descuento":"' . $descuento . '",
            "importe_total":"$ ' . number_format($r['total_neto'], 2) . '",
            "alerta":"' . $alertaSat . '"
        },';
      $cont++;
    }

    $table = substr($table, 0, strlen($table) - 1);

    $con = "";
    $stmt = "";
    $db = "";

    return '{"data":[' . $table . ']}';
  }

  function getIdSalidaForFolio($value)
  {
    $con = new conectar();
    $db = $con->getDb();
    $empresa = $_SESSION['IDEmpresa'];
    $id_ref = [];
    $id_fol = [];
    if(is_array($value)){
      for ($i=0; $i < count($value); $i++) { 
        $query = sprintf("select id from 
                        ( select i.id from inventario_salida_por_sucursales as i
                            inner join orden_pedido_por_sucursales as o on o.id=i.orden_pedido_id
                          where folio_salida = :folio and o.empresa_id = :empresa
                            union
                          select PKMovServ as id from movimientos_salidas_servicios_sin_inventario as m 
                            inner join orden_pedido_por_sucursales as o on o.id=m.FKOrdenPedido
                          where FKSalida = :folio2 and o.empresa_id = :empresa2) as tabla");
        $stmt = $db->prepare($query);
        $stmt->bindValue(":folio",$value[$i]);
        $stmt->bindValue(":folio2",$value[$i]);
        $stmt->bindValue(":empresa",$empresa);
        $stmt->bindValue(":empresa2",$empresa);
        $stmt->execute();
        $aux_id_ref = $stmt->fetchAll();

        foreach ($aux_id_ref as $r) {
          array_push($id_ref,$r['id']);
          array_push($id_fol,$value[$i]);
        }
      }
    } else if(gettype($value) === "string"){
      
      $query = sprintf("select id from 
      ( select i.id from inventario_salida_por_sucursales as i
          inner join orden_pedido_por_sucursales as o on o.id=i.orden_pedido_id
        where folio_salida = :folio and o.empresa_id = :empresa
          union
        select PKMovServ as id from movimientos_salidas_servicios_sin_inventario as m 
          inner join orden_pedido_por_sucursales as o on o.id=m.FKOrdenPedido
        where FKSalida = :folio2 and o.empresa_id = :empresa2) as tabla");
      $stmt = $db->prepare($query);
      $stmt->bindValue(":folio",$value);
      $stmt->bindValue(":folio2",$value);      
      $stmt->bindValue(":empresa",$empresa);
      $stmt->bindValue(":empresa2",$empresa);
      $stmt->execute();
      $aux_id_ref = $stmt->fetchAll();

      foreach ($aux_id_ref as $r) {
        array_push($id_ref,$r['id']);
        array_push($id_fol,$value);
      }
    }
    $response['salidas_id']= $id_ref;
    $response['salidas_folio'] = $id_fol;
    return $response;
  }

  function getCreditNote($value)
  {
    $con = new conectar();
    $db = $con->getDb();

    $query = sprintf("SELECT * from notas_cuentas_por_cobrar_has_facturacion ncf inner join notas_cuentas_por_cobrar nc on nc.id = ncf.notas_cuentas_por_cobrar_id where ncf.facturacion_id = :id and nc.estatus = 1");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":id",$value);
    $stmt->execute();

    return $stmt->rowCount();
  }

  function getAddressInvoiceClient($client_id,$addres_invoice){
    $con = new conectar();
    $db = $con->getDb();
    $arr = [];
    
    if($addres_invoice !== null && $addres_invoice !== ""){
      $query = sprintf("select 
                          cl.Email email,
                          cl.razon_social,
                          cl.rfc,
                          crf.clave regimen_fiscal,
                          cl.codigo_postal cp
                        from clientes cl
                          left join claves_regimen_fiscal crf on cl.regimen_fiscal_id = crf.id
                        where PKCliente = :id");
      $stmt = $db->prepare($query);
      $stmt->execute([":id"=>$client_id]);
      $arr = $stmt->fetchAll(PDO::FETCH_OBJ);
    } else {
      $query = sprintf("select 
                          dcl.Email email,
                          cl.razon_social,
                          cl.rfc,
                          crf.clave regimen_fiscal,
                          dcl.CP cp
                        from direcciones_envio_cliente dcl
                          left join clientes cl on dcl.FKCliente = cl.PKCliente
                          left join claves_regimen_fiscal crf on cl.regimen_fiscal_id = crf.id
                        where dcl.FKCliente = :client_id and dcl.PKDireccionEnvioCliente = :address_invoice");
      $stmt = $db->prepare($query);
      $stmt->execute([":id"=>$client_id,":address_invoice"=>$addres_invoice]);
      $arr = $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    return $arr;
  }

  function getClientInvoice($type,$ref,$id)
  {
    $con = new conectar();
    $db = $con->getDb();

    switch((int)$type){
      case 1:

        if(is_array($ref)){
          $ref1 = $ref[0];
        } else {
          $ref1 = $ref;
        }

        $query = sprintf("SELECT 
                            cl.PKCliente id,
                            cl.razon_social,
                            cl.NombreComercial nombre_comercial,
                            cl.RFC rfc,
                            cl.email,
                            cl.codigo_postal cp,
                            co.notaCliente notaCliente,
                            denc.Contacto,
                            denc.Telefono,
                            denc.Calle,
                            denc.Sucursal,
                            denc.Numero_exterior,
                            denc.Numero_Interior,
                            denc.Municipio,
                            denc.Colonia,
                            denc.CP,
                            ef.Estado,
                            co.empleado_id,
                            cl.Dias_credito dias_credito,
                            crf.clave regimen_fiscal
                          FROM cotizacion co
                            INNER JOIN clientes cl ON co.FKCliente = cl.PKCliente
                            LEFT JOIN direcciones_envio_cliente denc on co.direccion_entrega_id = denc.PKDireccionEnvioCliente
                            LEFT JOIN estados_federativos ef on denc.Estado = ef.PKEstado
                            LEFT JOIN claves_regimen_fiscal crf on cl.regimen_fiscal_id = crf.id
                          WHERE co.PKCotizacion = :id");
        break;
      case 2:

        if(is_array($ref)){
          $ref1 = $ref[0];
        } else {
          $ref1 = $ref;
        }

        $query = sprintf("SELECT 
                            cl.PKCliente id,
                            cl.razon_social,
                            cl.NombreComercial nombre_comercial,
                            cl.RFC rfc,
                            cl.email,
                            cl.codigo_postal cp,
                            vd.notasCliente notaCliente,
                            denc.Contacto,
                            denc.Telefono,
                            denc.Calle,
                            denc.Sucursal,
                            denc.Numero_exterior,
                            denc.Numero_Interior,
                            denc.Municipio,
                            denc.Colonia,
                            denc.CP,
                            ef.Estado,
                            vd.empleado_id,
                            cl.Dias_credito dias_credito,
                            crf.clave regimen_fiscal
                          FROM ventas_directas vd
                            INNER JOIN clientes cl ON vd.FKCliente = cl.PKCliente
                            LEFT JOIN direcciones_envio_cliente denc on vd.direccion_entrega_id = denc.PKDireccionEnvioCliente
                            LEFT JOIN estados_federativos ef on denc.Estado = ef.PKEstado
                            LEFT JOIN claves_regimen_fiscal crf on cl.regimen_fiscal_id = crf.id
                          WHERE vd.PKVentaDirecta = :id");
        break;
      case 3:
        if(is_array($ref)){
          $ref1 = $ref[0];
        } else {
          $ref1 = $ref;
        }

        $query_aux = sprintf("select distinct 
                      o.numero_cotizacion,
                      o.numero_venta_directa 
                    from orden_pedido_por_sucursales o
                      left join inventario_salida_por_sucursales s on o.id = s.orden_pedido_id
                      left join movimientos_salidas_servicios_sin_inventario msssi on o.id = msssi.FKOrdenPedido
                    where (s.folio_salida = :folio or msssi.FKSalida = :folio2) and o.empresa_id = :empresa_id");
        $stmt_aux = $db->prepare($query_aux);
        $stmt_aux->execute(
          [
            ":folio"=>$ref1,
            ":folio2"=>$ref1,
            ":empresa_id"=>$_SESSION['IDEmpresa']
          ]
        );
        $arr = $stmt_aux->fetchAll(PDO::FETCH_OBJ);

        if($arr[0]->numero_cotizacion !== null && $arr[0]->numero_cotizacion !== ""){
          $query = sprintf("SELECT 
                            cl.PKCliente id,
                            cl.razon_social,
                            cl.NombreComercial nombre_comercial,
                            cl.RFC rfc,
                            cl.email,
                            cl.codigo_postal cp,
                            co.notaCliente notaCliente,
                            denc.Contacto,
                            denc.Telefono,
                            denc.Calle,
                            denc.Sucursal,
                            denc.Numero_exterior,
                            denc.Numero_Interior,
                            denc.Municipio,
                            denc.Colonia,
                            denc.CP,
                            ef.Estado,
                            co.empleado_id,
                            cl.Dias_credito dias_credito,
                            crf.clave regimen_fiscal
                          FROM cotizacion co
                            INNER JOIN clientes cl ON co.FKCliente = cl.PKCliente
                            LEFT JOIN direcciones_envio_cliente denc on co.direccion_entrega_id = denc.PKDireccionEnvioCliente
                            LEFT JOIN estados_federativos ef on denc.Estado = ef.PKEstado
                            LEFT JOIN claves_regimen_fiscal crf on cl.regimen_fiscal_id = crf.id
                          WHERE co.PKCotizacion = :id");
          $ref1= $arr[0]->numero_cotizacion;
        } else if($arr[0]->numero_venta_directa !== null && $arr[0]->numero_venta_directa !== ""){
          $query = sprintf("SELECT 
                            cl.PKCliente id,
                            cl.razon_social,
                            cl.NombreComercial nombre_comercial,
                            cl.RFC rfc,
                            cl.email,
                            cl.codigo_postal cp,
                            vd.notasCliente notaCliente,
                            denc.Contacto,
                            denc.Telefono,
                            denc.Calle,
                            denc.Sucursal,
                            denc.Numero_exterior,
                            denc.Numero_Interior,
                            denc.Municipio,
                            denc.Colonia,
                            denc.CP,
                            ef.Estado,
                            vd.empleado_id,
                            cl.Dias_credito dias_credito,
                            crf.clave regimen_fiscal
                          FROM ventas_directas vd
                            INNER JOIN clientes cl ON vd.FKCliente = cl.PKCliente
                            LEFT JOIN direcciones_envio_cliente denc on vd.direccion_entrega_id = denc.PKDireccionEnvioCliente
                            LEFT JOIN estados_federativos ef on denc.Estado = ef.PKEstado
                            LEFT JOIN claves_regimen_fiscal crf on cl.regimen_fiscal_id = crf.id
                          WHERE vd.PKVentaDirecta = :id");
          $ref1= $arr[0]->numero_venta_directa;
        }
        break;
      case 4:
        $ref1 = $id;
        $query = sprintf("SELECT 
                            cl.PKCliente id,
                            cl.razon_social,
                            cl.NombreComercial nombre_comercial,
                            cl.RFC rfc,
                            cl.email,
                            cl.codigo_postal cp,
                            denc.Contacto,
                            denc.Telefono,
                            denc.Calle,
                            denc.Sucursal,
                            denc.Numero_exterior,
                            denc.Numero_Interior,
                            denc.Municipio,
                            denc.Colonia,
                            cl.Dias_credito dias_credito,
                            crf.clave regimen_fiscal
                          FROM clientes cl
                            LEFT JOIN claves_regimen_fiscal crf on cl.regimen_fiscal_id = crf.id
                            LEFT JOIN direcciones_envio_cliente denc on cl.PKCliente = denc.FKCliente
                          WHERE cl.PKCliente = :id");
        break;
      case 0:
        if(is_array($id)){
          $ref1 = $id[0];
        } else {
          $ref1 = $id;
        }
        
        $query = sprintf("SELECT 
                            cl.PKCliente id,
                            cl.razon_social,
                            cl.NombreComercial nombre_comercial,
                            cl.RFC rfc,
                            cl.email,
                            cl.codigo_postal cp,
                            denc.Contacto,
                            denc.Telefono,
                            denc.Calle,
                            denc.Sucursal,
                            denc.Numero_exterior,
                            denc.Numero_Interior,
                            denc.Municipio,
                            denc.Colonia,
                            crf.clave regimen_fiscal
                          FROM clientes cl
                            LEFT JOIN claves_regimen_fiscal crf on cl.regimen_fiscal_id = crf.id
                            LEFT JOIN direcciones_envio_cliente denc on cl.PKCliente = denc.FKCliente
                          WHERE cl.PKCliente = :id");
        break;
    }
      
      $stmt = $db->prepare($query);
      $stmt->bindValue(":id",$ref1);
      $stmt->execute();
    
     
      return $stmt->fetchAll();
  }
  /*
  function getLocalTaxProductosTemp($products){
    $impuestos_local = [];

    foreach($products as $r){
      if ($r['ish'] !== null && $r['ish'] !== "") {
        array_push(
          $impuestos_local,
          [
            "withholding" => false,
            "factor" => "Tasa",
            "type" => "ISH",
            "rate" => ($r['ish'] / 100)
          ]
        );
      }
      if ($r['isn_local'] !== null && $r['isn_local'] !== "") {
        array_push(
          $impuestos_local,
          [
            "withholding" => false,
            "factor" => "Tasa",
            "type" => "ISN",
            "rate" => ($r['isn_local'] / 100)
          ]
        );
      }
      if ($r['cedular'] !== null && $r['cedular'] !== "") {
        array_push(
          $impuestos_local,
          [
            "withholding" => false,
            "factor" => "Tasa",
            "type" => "Cedular",
            "rate" => ($r['cedular'] / 100)
          ]
        );
      }
      if ($r['al_millar'] !== null && $r['al_millar'] !== "") {
        array_push(
          $impuestos_local,
          [
            "withholding" => false,
            "factor" => "Tasa",
            "type" => "5 al millar",
            "rate" => ($r['al_millar'] / 100)
          ]
        );
      }
      if ($r['funcion_publica'] !== null && $r['funcion_publica'] !== "") {
        array_push(
          $impuestos_local,
          [
            "withholding" => false,
            "factor" => "Tasa",
            "type" => "Funcion publica",
            "rate" => ($r['funcion_publica'] / 100)
          ]
        );
      }
    }

    return $impuestos_local;
  }
  */
  function getTaxProductosTemp($products,$array)
  {
    $impuestos = [];
    $impuestos_local = [];
    $referencias = [];
    $descripcion = "";
    $cont = 0;
    $items = [];
    $importe_descuento_total = 0;
    
    for ($i = 0; $i < count($products); $i++) {
      if ($products[$i]['importe_descuento_tasa'] !== "" && $products[$i]['importe_descuento_tasa'] !== null) {
        $importe_descuento_total += $products[$i]['importe_descuento_tasa'];
      }
      if ($products[$i]['descuento_monto_fijo'] !== "" && $products[$i]['descuento_monto_fijo'] !== null) {
        $importe_descuento_total += $products[$i]['descuento_monto_fijo'];
      }
    }

    foreach($products as $r){
      $impuestos = [];
      $impuestos_local = [];
      if ($r['iva'] !== null && $r['iva'] !== "") {
        array_push(
          $impuestos,
          [
            "withholding" => false,
            "factor" => "Tasa",
            "type" => "IVA",
            "rate" => ($r['iva'] / 100)
          ]
        );
      }
      if ($r['ieps'] !== null && $r['ieps'] !== "") {
        array_push(
          $impuestos,
          [
            "withholding" => false,
            "factor" => "Tasa",
            "type" => "IEPS",
            "rate" => ($r['ieps'] / 100)
          ]
        );
      }
      if ($r['ieps_monto_fijo'] !== null && $r['ieps_monto_fijo'] !== "") {
        array_push(
          $impuestos,
          [
            "withholding" => false,
            "factor" => "Cuota",
            "type" => "IEPS",
            "rate" => $r['ieps_monto_fijo']
          ]
        );
      }
     
      if ($r['iva_exento'] !== null && $r['iva_exento'] !== "") {
        array_push(
          $impuestos,
          [
            "withholding" => false,
            "factor" => "Exento",
            "type" => "IVA",
            "rate" => ($r['iva_exento'] / 100)
          ]
        );
      }
      if ($r['iva_retenido'] !== null && $r['iva_retenido'] !== "") {
        array_push(
          $impuestos,
          [
            "withholding" => true,
            "factor" => "Tasa",
            "type" => "IVA",
            "rate" => ($r['iva_retenido'] / 100)
          ]
        );
      }
      if ($r['isr_retenido'] !== null && $r['isr_retenido'] !== "") {
        array_push(
          $impuestos,
          [
            "withholding" => true,
            "factor" => "Tasa",
            "type" => "ISR",
            "rate" => ($r['isr_retenido'] / 100)
          ]
        );
      }
      if ($r['ieps_retenido'] !== null && $r['ieps_retenido'] !== "") {
        array_push(
          $impuestos,
          [
            "withholding" => true,
            "factor" => "Tasa",
            "type" => "IEPS",
            "rate" => ($r['ieps_retenido'] / 100)
          ]
        );
      }
      if ($r['isr_exento'] !== null && $r['isr_exento'] !== "") {
        array_push(
          $impuestos,
          [
            "withholding" => false,
            "factor" => "Exento",
            "type" => "ISR",
            "rate" => ($r['isr_exento'] / 100)
          ]
        );
      }

      if ($r['isr_monto_fijo'] !== null && $r['isr_monto_fijo'] !== "") {
        array_push(
          $impuestos,
          [
            "withholding" => false,
            "factor" => "Cuota",
            "type" => "ISR",
            "rate" => $r['isr_monto_fijo']
          ]
        );
      }
      if ($r['isr'] !== null && $r['isr'] !== "") {
        array_push(
          $impuestos,
          [
            "withholding" => true,
            "factor" => "Tasa",
            "type" => "ISR",
            "rate" => ($r['isr'] / 100)
          ]
        );
      }
      if ($r['ieps_exento'] !== null && $r['ieps_exento'] !== "") {
        array_push(
          $impuestos,
          [
            "withholding" => false,
            "factor" => "Exento",
            "type" => "IEPS",
            "rate" => ($r['ieps_exento'] / 100)
          ]
        );
      }

      if ($r['isr_retenido_monto_fijo'] !== null && $r['isr_retenido_monto_fijo'] !== "") {
        array_push(
          $impuestos,
          [
            "withholding" => true,
            "factor" => "Cuota",
            "type" => "IEPS",
            "rate" => $r['isr_retenido_monto_fijo']
          ]
        );
      }

      if ($r['ieps_retenido_monto_fijo'] !== null && $r['ieps_retenido_monto_fijo'] !== "") {
        array_push(
          $impuestos,
          [
            "withholding" => true,
            "factor" => "Cuota",
            "type" => "IEPS",
            "rate" => $r['ieps_retenido_monto_fijo']
          ]
        );
      }
      if ($r['ish'] !== null && $r['ish'] !== "") {
        array_push(
          $impuestos_local,
          [
            "withholding" => false,
            
            "type" => "ISH",
            "rate" => ($r['ish'] / 100)
          ]
        );
      }
      if ($r['isn_local'] !== null && $r['isn_local'] !== "") {
        array_push(
          $impuestos_local,
          [
            "withholding" => false,
            
            "type" => "ISN",
            "rate" => ($r['isn_local'] / 100)
          ]
        );
      }
      if ($r['cedular'] !== null && $r['cedular'] !== "") {
        array_push(
          $impuestos_local,
          [
            "withholding" => false,
            
            "type" => "Cedular",
            "rate" => ($r['cedular'] / 100)
          ]
        );
      }
      if ($r['al_millar'] !== null && $r['al_millar'] !== "") {
        array_push(
          $impuestos_local,
          [
            "withholding" => false,
            
            "type" => "5 al millar",
            "rate" => ($r['al_millar'] / 100)
          ]
        );
      }
      if ($r['funcion_publica'] !== null && $r['funcion_publica'] !== "") {
        array_push(
          $impuestos_local,
          [
            "withholding" => false,
            
            "type" => "Funcion publica",
            "rate" => ($r['funcion_publica'] / 100)
          ]
        );
      }
      
      if ($r['unit_key'] !== "" && $r['unit_key'] !== null && $r['unit_key'] !== "S/C") {
        $unit_key = $r['unit_key'];
        $unit_name = $r['unit_name'];
      } else {
        $unit_key = "H87";
        $unit_name = "Pieza";
      }
      //01010101
      if ($r['product_key'] !== "" && $r['product_key'] !== null && $r['product_key'] !== "S/C") {
        $product_key = $r['product_key'];
      } else {
        $product_key = "01010101";
      }

      if ($r['numero_lote'] !== null && $r['numero_lote'] !== "") {
        if ($r['caducidad'] !== null && $r['caducidad'] !== "") {
          $descripcion = $r['description'] . " Lote: " . $r['numero_lote'] . " Caducidad: " . $r['caducidad'];
        } else {
          $descripcion = $r['description'] . " Lote: " . $r['numero_lote'];
        }
      } else if ($r['numero_serie'] !== null && $r['numero_serie'] !== "") {
        $descripcion = $r['description'] . " Serie: " . $r['numero_serie'];
      } else {
        $descripcion = $r['description'];
      }
      
      $productos_api[] = [
        "description" => $descripcion,
        "product_key" => $product_key,
        "price" => $r['price'],
        "sku" => $r['sku'],
        "unit_name" => $unit_name,
        "unit_key" => $unit_key,
        "tax_included" => false,
        "taxes" => $impuestos,
        "local_taxes" => $impuestos_local
      ];
      
      if ($r['numero_predial'] !== "" && $r['numero_predial'] !== null) {

        $cadena_predial = "";

        for ($j = 0; $j < strlen($r['numero_predial']); $j++) {
          if (ctype_alpha($r['numero_predial'][$j])) {
            $cadena_predial .= "0";
          } else {
            $cadena_predial .= $r['numero_predial'][$j];
          }
        }

        $items[] = [
          "quantity" => $r['cantidad'],
          "discount" => $importe_descuento_total,
          "product" => $productos_api[$cont],
          "property_tax_account" => $cadena_predial
        ];
      } else {
        $items[] = [
          "quantity" => $r['cantidad'],
          "discount" => $importe_descuento_total,
          "product" => $productos_api[$cont],
        ];
      }
      /*
      if ($array['tipoDocumento'] === "3" || $array['tipoDocumento'] === "4") {
        if (!in_array($r['referencia'], $referencias)) {
          array_push($referencias, $r[$i]['referencia']);
        }
      }*/
      $cont++;
    }
    
    return $items;
  }

  function getProductsInvoiceTemp($type,$referencia)
  {
    $con = new conectar();
    $db = $con->getDb();
    $get_data = new get_data();
    $prod = [];
    
    if((int)$type !== 3){
      if(is_array($referencia)){
        $ref = implode(",",$referencia);
      } else {
        $ref = $referencia;
      }
    } else {
      $aux = $get_data->getIdSalidaForFolio($referencia);
      $ref = $aux['salidas_id'];
    }
   
    if((int)$type !== 3){
      $query = sprintf("select
                          pr.PKProducto id,
                          dpft.referencia,
                          pr.Nombre description,
                          cst.Clave product_key,
                          dpft.precio_unitario price,
                          pr.ClaveInterna sku,
                          dpft.iva,
                          dpft.importe_iva,
                          dpft.ieps,
                          dpft.importe_ieps,
                          dpft.ieps_monto_fijo,
                          dpft.ish,
                          dpft.importe_ish,
                          dpft.iva_exento,
                          dpft.importe_iva_exento,
                          dpft.iva_retenido,
                          dpft.importe_iva_retenido,
                          dpft.isr_retenido,
                          dpft.importe_isr_retenido,
                          dpft.isn_local,
                          dpft.importe_isn_local,
                          dpft.cedular,
                          dpft.importe_cedular,
                          dpft.al_millar,
                          dpft.importe_al_millar,
                          dpft.funcion_publica,
                          dpft.importe_funcion_publica,
                          dpft.ieps_retenido,
                          dpft.importe_ieps_retenido,
                          dpft.isr_exento,
                          dpft.importe_isr_exento,
                          dpft.isr_monto_fijo,
                          dpft.isr,
                          dpft.importe_isr,
                          dpft.ieps_exento,
                          dpft.importe_ieps_exento,
                          dpft.isr_retenido_monto_fijo,
                          dpft.ieps_retenido_monto_fijo,
                          dpft.importe_descuento_tasa,
                          dpft.descuento_monto_fijo,
                          dpft.unidad_medida_id,
                          csu.Clave unit_key,
                          csu.Descripcion unit_name,
                          dpft.cantidad,
                          dpft.producto_id,
                          dpft.clave_sat_id,
                          dpft.numero_predial,
                          dpft.numero_lote,
                          dpft.caducidad,
                          dpft.numero_serie,
                          dpft.total_neto,
                          dpft.factura_concepto
                        from datos_producto_facturacion_temp dpft
                        inner join productos pr on dpft.producto_id = pr.PKProducto
                        left join claves_sat_unidades csu on dpft.unidad_medida_id = csu.PKClaveSATUnidad
                        left join claves_sat cst on dpft.clave_sat_id = cst.PKClaveSAT
                        where usuario_id = :usuario_id and tipo = :tipo and referencia = :referencia");
      $stmt = $db->prepare($query);
      $stmt->bindValue(":usuario_id", $_SESSION['PKUsuario']);
      $stmt->bindValue(":tipo", $type);
      $stmt->bindValue(":referencia", $ref);
      $stmt->execute();

      $prod = $stmt->fetchAll();
    } else {
      
      for ($i=0; $i < count($ref); $i++) {

        $query = sprintf("select
                          pr.PKProducto id,
                          dpft.referencia,
                          pr.Nombre description,
                          cst.Clave product_key,
                          dpft.precio_unitario price,
                          pr.ClaveInterna sku,
                          dpft.iva,
                          dpft.importe_iva,
                          dpft.ieps,
                          dpft.importe_ieps,
                          dpft.ieps_monto_fijo,
                          dpft.ish,
                          dpft.importe_ish,
                          dpft.iva_exento,
                          dpft.importe_iva_exento,
                          dpft.iva_retenido,
                          dpft.importe_iva_retenido,
                          dpft.isr_retenido,
                          dpft.importe_isr_retenido,
                          dpft.isn_local,
                          dpft.importe_isn_local,
                          dpft.cedular,
                          dpft.importe_cedular,
                          dpft.al_millar,
                          dpft.importe_al_millar,
                          dpft.funcion_publica,
                          dpft.importe_funcion_publica,
                          dpft.ieps_retenido,
                          dpft.importe_ieps_retenido,
                          dpft.isr_exento,
                          dpft.importe_isr_exento,
                          dpft.isr_monto_fijo,
                          dpft.isr,
                          dpft.importe_isr,
                          dpft.ieps_exento,
                          dpft.importe_ieps_exento,
                          dpft.isr_retenido_monto_fijo,
                          dpft.ieps_retenido_monto_fijo,
                          dpft.importe_descuento_tasa,
                          dpft.descuento_monto_fijo,
                          dpft.unidad_medida_id,
                          csu.Clave unit_key,
                          csu.Descripcion unit_name,
                          dpft.cantidad,
                          dpft.producto_id,
                          dpft.clave_sat_id,
                          dpft.numero_predial,
                          dpft.numero_lote,
                          dpft.caducidad,
                          dpft.numero_serie,
                          dpft.total_neto
                        from datos_producto_facturacion_temp dpft
                        inner join (select s.id, s.folio_salida as folio, pr.PKProducto 
                                        from inventario_salida_por_sucursales s inner join productos pr on s.clave = pr.ClaveInterna
                                      where s.id = :referencia1 and pr.empresa_id = :empresa_id
                                        union
                                      select ms.PKMovServ as id, ms.FKSalida as folio, ms.FKProducto as PKProducto from movimientos_salidas_servicios_sin_inventario ms where ms.PKMovServ = :referencia2
                                      ) as sf on sf.id = :referencia3 and dpft.producto_id = sf.PKProducto
                        inner join productos pr on dpft.producto_id = pr.PKProducto
                        left join claves_sat_unidades csu on dpft.unidad_medida_id = csu.PKClaveSATUnidad
                        left join claves_sat cst on dpft.clave_sat_id = cst.PKClaveSAT
                        where usuario_id = :usuario_id and tipo = :tipo and referencia = :referencia4 and sf.folio = :folio");
      $stmt = $db->prepare($query);
      $stmt->bindValue(":usuario_id", $_SESSION['PKUsuario']);
      $stmt->bindValue(":tipo", $type);
      $stmt->bindValue(":referencia1", $ref[$i]);
      $stmt->bindValue(":referencia2", $ref[$i]);
      $stmt->bindValue(":referencia3", $ref[$i]);
      $stmt->bindValue(":referencia4", $ref[$i]);
      $stmt->bindValue(":folio", $aux['salidas_folio'][$i]);
      $stmt->bindValue(":empresa_id", $_SESSION['IDEmpresa']);
      $stmt->execute();

        $aux_prod = $stmt->fetchAll();

        foreach($aux_prod as $r1){
          array_push($prod,[
            "referencia"=>$r1['referencia'],
            "description"=>$r1['description'],
            "product_key"=>$r1['product_key'],
            "price"=>$r1['price'],
            "sku"=>$r1['sku'],
            "iva"=>$r1['iva'],
            "importe_iva"=>$r1['importe_iva'],
            "ieps"=>$r1['ieps'],
            "importe_ieps"=>$r1['importe_ieps'],
            "ieps_monto_fijo"=>$r1['ieps_monto_fijo'],
            "ish"=>$r1['ish'],
            "importe_ish"=>$r1['importe_ish'],
            "iva_exento"=>$r1['iva_exento'],
            "importe_iva_exento"=>$r1['importe_iva_exento'],
            "iva_retenido"=>$r1['iva_retenido'],
            "importe_iva_retenido"=>$r1['importe_iva_retenido'],
            "isr_retenido"=>$r1['isr_retenido'],
            "importe_isr_retenido"=>$r1['importe_isr_retenido'],
            "isn_local"=>$r1['isn_local'],
            "importe_isn_local"=>$r1['importe_isn_local'],
            "cedular"=>$r1['cedular'],
            "importe_cedular"=>$r1['importe_cedular'],
            "al_millar"=>$r1['al_millar'],
            "importe_al_millar"=>$r1['importe_al_millar'],
            "funcion_publica"=>$r1['funcion_publica'],
            "importe_funcion_publica"=>$r1['importe_funcion_publica'],
            "ieps_retenido"=>$r1['ieps_retenido'],
            "importe_ieps_retenido"=>$r1['importe_ieps_retenido'],
            "isr_exento"=>$r1['isr_exento'],
            "importe_isr_exento"=>$r1['importe_isr_exento'],
            "isr_monto_fijo"=>$r1['isr_monto_fijo'],
            "isr"=>$r1['isr'],
            "importe_isr"=>$r1['importe_isr'],
            "ieps_exento"=>$r1['ieps_exento'],
            "importe_ieps_exento"=>$r1['importe_ieps_exento'],
            "isr_retenido_monto_fijo"=>$r1['isr_retenido_monto_fijo'],
            "ieps_retenido_monto_fijo"=>$r1['ieps_retenido_monto_fijo'],
            "importe_descuento_tasa"=>$r1['importe_descuento_tasa'],
            "descuento_monto_fijo"=>$r1['descuento_monto_fijo'],
            "unidad_medida_id"=>$r1['unidad_medida_id'],
            "unit_key"=>$r1['unit_key'],
            "unit_name"=>$r1['unit_name'],
            "cantidad"=>$r1['cantidad'],
            "producto_id"=>$r1['producto_id'],
            "clave_sat_id"=>$r1['clave_sat_id'],
            "numero_predial"=>$r1['numero_predial'],
            "numero_lote"=>$r1['numero_lote'],
            "caducidad"=>$r1['caducidad'],
            "numero_serie"=>$r1['numero_serie'],
            "total_neto"=>$r1['total_neto']
          ]);
        }
      }
    }
    return $prod;
  }

  function getDireccionEnvioCotizacion($value)
  {
    $con = new conectar();
    $db = $con->getDb();
    
    $query = sprintf("select 
                        concat(d.Sucursal,' - ',d.Calle,' ',d.Numero_exterior,' Int. ',d.Numero_interior,', ',d.colonia,', C.P. ',d.CP,' ',d.Municipio,', ',e.Estado,', ',p.Pais) direccion
                      from cotizacion c 
                      left join direcciones_envio_cliente d on c.direccion_entrega_id = d.PKDireccionEnvioCliente
                      left join estados_federativos e on d.Estado = e.PKEstado
                      left join paises p on e.FKPais = p.PKPais
                      where c.empresa_id = :empresa_id and c.PKCotizacion = :cot");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":empresa_id", $_SESSION['IDEmpresa']);
    $stmt->bindValue(":cot", $value);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }

  function getDireccionEnvioVentas($value)
  {
    $con = new conectar();
    $db = $con->getDb();
    
    $query = sprintf("select 
                        concat(d.Sucursal,' - ',d.Calle,' ',d.Numero_exterior,' Int. ',d.Numero_interior,', ',d.colonia,', C.P. ',d.CP,' ',d.Municipio,', ',e.Estado,', ',p.Pais) direccion
                      from ventas_directas v
                      left join direcciones_envio_cliente d on v.direccion_entrega_id = d.PKDireccionEnvioCliente
                      left join estados_federativos e on d.Estado = e.PKEstado
                      left join paises p on e.FKPais = p.PKPais
                      where v.empresa_id = :empresa_id and v.PKVentaDirecta = :venta");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":empresa_id", $_SESSION['IDEmpresa']);
    $stmt->bindValue(":venta", $value);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }

  function getFormatInvoice($array,$clientPG,$data)
  {
    $con = new conectar();
    $db = $con->getDb();
    $get_data = new get_data();
    
    $dirEnvioCliente = "";
    if((int)$array['tipoDocumento'] !== 3){
      if(is_array($array['idDocumento'])){
        $ref1 = implode(",",$array['idDocumento']);
      } else {
        $ref1 = strval($array['idDocumento']);
      }
    } else {
      $ref1 = $array['idDocumento'];
    }
    
    if($array['tipoDocumento'] === "1"){
      $dirEnvioCliente = $get_data->getDireccionEnvioCotizacion((int)$ref1)[0]->direccion;
    } else if($array['tipoDocumento'] === "2"){
      $dirEnvioCliente = $get_data->getDireccionEnvioVentas((int)$ref1)[0]->direccion;
    }

    $query = sprintf("select clave from formas_pago_sat where id = :id");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":id", $array['formaPago']);
    $stmt->execute();

    $forma_pago = $stmt->fetchAll();

    $query = sprintf("select clave from uso_cfdi where id = :id");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":id", $array['usoCfdi']);
    $stmt->execute();

    $uso_cfdi = $stmt->fetchAll();

    $query = sprintf("select Clave clave from monedas where PKMoneda = :id");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":id", $array['moneda']);
    $stmt->execute();

    $moneda = $stmt->fetchAll();

    $id_cliente = $array['cliente'] !== "" && $array['cliente'] !== null ? $array['cliente'] : "";

    $cliente = $get_data->getClientInvoice($array['tipoDocumento'],$ref1,$id_cliente);

    if($array['tipoDocumento'] === "1" || $array['tipoDocumento'] === "2" || $array['tipoDocumento'] === "3"){
      // $numInterior = $cliente[0]['Numero_Interior'] !== "" && $cliente[0]['Numero_Interior'] !== null ? "Int. " . $cliente[0]['Numero_Interior'] : "";
      // $dirEnvioCliente = $cliente[0]['Sucursal'] ." - ". $cliente[0]['Calle'] . " " . $cliente[0]['Numero_exterior'] . " " . $numInterior . " " . $cliente[0]['Colonia'] . " C.P. " . $cliente[0]['CP'] . " " . $cliente[0]['Municipio'] . ", " . $cliente[0]['Estado'];
      $notaCliente = $cliente[0]['notaCliente'] !== "" && $cliente[0]['notaCliente'] !== null ? $cliente[0]['notaCliente'] : ""; 
      $contactoCliente = $cliente[0]['Contacto'] !== "" && $cliente[0]['Contacto'] !== null ? $cliente[0]['Contacto'] : "";
      $telefonoCliente = $cliente[0]['Telefono'] !=0 && $cliente[0]['Telefono'] !== null ? $cliente[0]['Telefono'] : "";
    } else if($array['tipoDocumento'] === "0") {
      $notaCliente = $data;
    }

    $productos = $get_data->getProductsInvoiceTemp($array['tipoDocumento'],$ref1);

    $productos_impuestos =  $get_data->getTaxProductosTemp($productos,$array);
    if($cliente[0]['rfc'] !== 'XAXX010101000'){
      $clienteRC = $cliente[0]['razon_social'];
    } else {
      $clienteRC = $clientPG !== "" ? $clientPG : $cliente[0]['razon_social'];
    }
    
    $cliente_api = [
      "email" => $cliente[0]['email'],
      "legal_name" => $clienteRC,
      "tax_id" => $cliente[0]['rfc'],
      "tax_system"=>strval($cliente[0]['regimen_fiscal']),
      "address" => array(
        "zip" => strval($cliente[0]['cp']),
        "country"=>"MEX"
      )
    ];
    
    $pdf_custom_section = "";
    $fechaEmision = $array['fechaEmision'] === date("Y-m-d") ? date("c") : date("c", strtotime($array['fechaEmision'] . " 23:59:59"));
    if($array['tipoDocumento'] === "1" || $array['tipoDocumento'] === "2" || $array['tipoDocumento'] === "3"){
      $pdfDireccionEnvio = $dirEnvioCliente !== null && $dirEnvioCliente !== "" ? "<p>Dirección de envío: " . $dirEnvioCliente . "</p>" : "";
      $pdfNotasCliente = $notaCliente !== "" && $notaCliente !== null ? "<p>Nota cliente: " . $notaCliente . "</p>" : "";
      $pdfContactoCliente = $contactoCliente !== "" && $contactoCliente !== null ? "<p>Atención: " . $contactoCliente . "</p>" : "";
      $pdfTelefonoCliente = $telefonoCliente !== "" && $telefonoCliente !== null ? "<p>Teléfono: " . $telefonoCliente . "</p>" : "";
      $pdf_custom_section = $pdfNotasCliente . $pdfDireccionEnvio . $pdfContactoCliente . $pdfTelefonoCliente;
    } else if($array['tipoDocumento'] === "0") {
      $pdfNotasCliente = $notaCliente !== "" && $notaCliente !== null ? "<p>Nota cliente: " . $notaCliente . "</p>" : "";
      $pdf_custom_section = $pdfNotasCliente;
    }
    $folio_serie = $get_data->getFolioSerie();

    $invoice = array(
      "customer" => $cliente_api,
      "items" => $productos_impuestos,
      "payment_form" => $forma_pago[0]['clave'],
      "type"=>"I",
      "payment_method" => $array['metodoPago'],
      "use" => $uso_cfdi[0]['clave'],
      "folio_number" => $folio_serie['folio'],
      "series" => $folio_serie['serie'],
      "external_id" => json_encode($ref1),
      "currency" => $moneda[0]['clave'],
      "date" => $fechaEmision,
      "pdf_custom_section" => $pdf_custom_section
    );
    //echo json_encode($invoice);
    return $invoice;
  }

  function getFolioSeriePrefectura()
  {
    $con = new conectar();
    $db = $con->getDb();

    $query = sprintf("select max(folio_prefactura) folio_prefactura from facturacion where empresa_id = :empresa_id");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":empresa_id", $_SESSION['IDEmpresa']);
    $stmt->execute();

    $arr = $stmt->fetchAll();

    if(count($arr) > 0){
      $folio = $arr[0]['folio_prefactura'] !== null && $arr[0]['folio_prefactura'] !== "" ? ((int)$arr[0]['folio_prefactura'] + 1) : 1;
    } else {
      $folio = 1;
    }
    return $folio;
  }

  function getTotalPrefactura($array)
  {
    $get_data = new get_data();
    $productos = $get_data->getProductsInvoiceTemp($array['tipoDocumento'],$array['idDocumento']);
    $total = 0;

    foreach($productos as $r){
      $total += $r['total_neto'];
    }
   
    return $total;
  }

  function getDataPreinvoice($value)
  {
    $con = new conectar();
    $db = $con->getDb();
    $arr = [];

    $query =sprintf("select tipo, referencia from facturacion where id = :id");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":id",$value);
    $stmt->execute();

    $data = $stmt->fetchAll();

    switch ((int)$data[0]['tipo']) {
      case 1:
        $query = sprintf("select
                          cl.PKCliente,
                          cl.rfc,
                          cl.razon_social,
                          f.tipo,
                          co.id_cotizacion_empresa referencia,
                          f.forma_pago_id, 
                          f.metodo_pago,
                          f.uso_cfdi_id,
                          f.moneda_id
                        from facturacion f
                          inner join cotizacion co on f.referencia = co.PKCotizacion
                          inner join clientes cl on f.cliente_id = cl.PKCliente
                        where f.id = :id");
        break;
      case 2:
        $query = sprintf("select
                          cl.PKCliente,
                          cl.rfc,
                          cl.razon_social,
                          f.tipo,
                          vd.Referencia referencia,
                          f.forma_pago_id, 
                          f.metodo_pago,
                          f.uso_cfdi_id,
                          f.moneda_id
                        from facturacion f
                          inner join ventas_directas vd on f.referencia = vd.PKVentaDirecta
                          inner join clientes cl on f.cliente_id = cl.PKCliente
                        where f.id = :id");
        break;
      case 3:
        $query = sprintf("select
                          cl.PKCliente,
                          cl.rfc,
                          cl.razon_social,
                          f.tipo,
                          f.referencia,
                          f.forma_pago_id, 
                          f.metodo_pago,
                          f.uso_cfdi_id,
                          f.moneda_id
                        from facturacion f
                          inner join clientes cl on f.cliente_id = cl.PKCliente
                        where f.id = :id");
        break;
      case 4:
        $query = sprintf("select
                          cl.PKCliente,
                          cl.rfc,
                          cl.razon_social,
                          f.tipo,
                          r.folio,
                          f.forma_pago_id, 
                          f.metodo_pago,
                          f.uso_cfdi_id,
                          f.moneda_id
                        from facturacion f
                          inner join remisiones r on f.referencia = r.id
                          inner join clientes cl on f.cliente_id = cl.PKCliente
                        where f.id = :id");
        break;
      case 0:
        $query = sprintf("select
                          cl.PKCliente,
                          cl.rfc,
                          cl.razon_social,
                          f.tipo,
                          f.referencia,
                          f.forma_pago_id, 
                          f.metodo_pago,
                          f.uso_cfdi_id,
                          f.moneda_id
                        from facturacion f
                          inner join clientes cl on f.cliente_id = cl.PKCliente
                        where f.id = :id");
        break;
      
    }
    $stmt = $db->prepare($query);
    $stmt->bindValue(":id",$value);
    $stmt->execute();

    $cliente = $stmt->fetchAll();

    array_push($arr,[
      "tipo"=>$data[0]['tipo'],
      "referencia"=>$data[0]['referencia'],
      "data_cliente"=>$cliente
    ]);

    return $arr;
  }

  function getProductosPrefactura($value)
  {
    $con = new conectar();
    $db = $con->getDb();
    $get_data = new get_data();
    $table ="";
    $cont = "";

    $query =sprintf("select tipo, referencia from facturacion where id = :id");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":id",$value);
    $stmt->execute();
    $data = $stmt->fetchAll();

    $tipo = $data[0]['tipo'];
    $referencia = $data[0]['referencia'];

    $query = sprintf("select 
                        dt.factura_id id,
                        dt.producto_id,
                        pro.ClaveInterna clave,
                        pro.Nombre nombre,
                        ifp.FKClaveSATUnidad,
                        dt.cantidad,
                        dt.precio precio_unitario,
                        dt.subtotal,
                        (dt.cantidad * dt.precio) total,
                        ifp.FKClaveSAT,
                        CONCAT(csu.Clave,' - ',csu.Descripcion) sat_unidad,
                        dt.iva,
                        dt.importe_iva,
                        dt.ieps,
                        dt.importe_ieps,
                        dt.ieps_monto_fijo,
                        dt.ish,
                        dt.importe_ish,
                        dt.iva_exento,
                        dt.importe_iva_exento,
                        dt.iva_retenido,
                        dt.importe_iva_retenido,
                        dt.isr_retenido,
                        dt.importe_isr_retenido,
                        dt.isn_local,
                        dt.importe_isn_local,
                        dt.cedular,
                        dt.importe_cedular,
                        dt.al_millar,
                        dt.importe_al_millar,
                        dt.funcion_publica,
                        dt.importe_funcion_publica,
                        dt.ieps_retenido,
                        dt.importe_ieps_retenido,
                        dt.isr_exento,
                        dt.importe_isr_exento,
                        dt.isr_monto_fijo,
                        dt.isr,
                        dt.importe_isr,
                        dt.ieps_exento,
                        dt.importe_ieps_exento,
                        dt.isr_retenido_monto_fijo,
                        dt.ieps_retenido_monto_fijo
                      from detalle_facturacion dt
                    inner join productos pro on dt.producto_id = pro.PKProducto
                    left join info_fiscal_productos ifp on pro.PKProducto = ifp.FKProducto
                    left join claves_sat_unidades csu on ifp.FKClaveSATUnidad = csu.PKClaveSATUnidad
                    where dt.factura_id = :id");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":id",$value);
    $stmt->execute();
    $productos = $stmt->fetchAll();

    $query0 = sprintf("select * from datos_producto_facturacion_temp where usuario_id = :usuario_id and tipo = :tipo and referencia = :ref");
    $stmt0 = $db->prepare($query0);
    $stmt0->bindValue(":usuario_id", $_SESSION['PKUsuario']);
    $stmt0->bindValue(":tipo", $tipo);
    $stmt0->bindValue(":ref", $referencia);
    $stmt0->execute();
    $rowCount = $stmt0->rowCount();

    if ($rowCount > 0) {
      // se envía 1 como parametro 4 para indicarle que aunque sea de tipo 3 (pedido) va a hacer la eliminación de forma normal.
      $get_data->getTruncateTableProducts(0,$tipo,$referencia,1);
    }

    foreach($productos as $r){
      $query2 = sprintf("insert into datos_producto_facturacion_temp (
                          referencia,
                          tipo,
                          producto_id,
                          unidad_medida_id,
                          clave_sat_id,
                          cantidad,
                          cantidad_facturada,
                          precio_unitario,
                          total_bruto,
                          iva,
                          importe_iva,
                          ieps,
                          importe_ieps,
                          ieps_monto_fijo,
                          ish,
                          importe_ish,
                          iva_exento,
                          importe_iva_exento,
                          iva_retenido,
                          importe_iva_retenido,
                          isr_retenido,
                          importe_isr_retenido,
                          isn_local,
                          importe_isn_local,
                          cedular,
                          importe_cedular,
                          al_millar,
                          importe_al_millar,
                          funcion_publica,
                          importe_funcion_publica,
                          ieps_retenido,
                          importe_ieps_retenido,
                          isr_exento,
                          importe_isr_exento,
                          isr_monto_fijo,
                          isr,
                          importe_isr,
                          ieps_exento,
                          importe_ieps_exento,
                          isr_retenido_monto_fijo,
                          ieps_retenido_monto_fijo,
                          total_neto,
                          usuario_id                          
                        ) 
                        values (
                          :referencia,
                          :tipo,
                          :producto_id,
                          :unidad_medida_id,
                          :clave_sat_id,
                          :cantidad,
                          :cantidad_facturada,
                          :precio_unitario,
                          :total_bruto,
                          :iva,
                          :importe_iva,
                          :ieps,
                          :importe_ieps,
                          :ieps_monto_fijo,
                          :ish,
                          :importe_ish,
                          :iva_exento,
                          :importe_iva_exento,
                          :iva_retenido,
                          :importe_iva_retenido,
                          :isr_retenido,
                          :importe_isr_retenido,
                          :isn_local,
                          :importe_isn_local,
                          :cedular,
                          :importe_cedular,
                          :al_millar,
                          :importe_al_millar,
                          :funcion_publica,
                          :importe_funcion_publica,
                          :ieps_retenido,
                          :importe_ieps_retenido,
                          :isr_exento,
                          :importe_isr_exento,
                          :isr_monto_fijo,
                          :isr,
                          :importe_isr,
                          :ieps_exento,
                          :importe_ieps_exento,
                          :isr_retenido_monto_fijo,
                          :ieps_retenido_monto_fijo,                             
                          :total_neto,
                          :usuario_id    
                      )");
      $stmt2 = $db->prepare($query2);
      $stmt2->bindValue(":referencia", $referencia);
      $stmt2->bindValue(":tipo", $tipo);
      $stmt2->bindValue(":producto_id", $r['producto_id']);
      $stmt2->bindValue(":unidad_medida_id", $r['FKClaveSATUnidad']);
      $stmt2->bindValue(":clave_sat_id", $r['FKClaveSAT']);
      $stmt2->bindValue(":cantidad", $r['cantidad']);
      $stmt2->bindValue(":cantidad_facturada", $r['cantidad']);
      $stmt2->bindValue(":precio_unitario", $r['precio_unitario']);
      $stmt2->bindValue(":total_bruto", $r['subtotal']);
      $stmt2->bindValue(":iva", $r['iva']);
      $stmt2->bindValue(":importe_iva", $r['importe_iva']);
      $stmt2->bindValue(":ieps",$r['ieps']);
      $stmt2->bindValue(":importe_ieps", $r['importe_ieps']);
      $stmt2->bindValue(":ieps_monto_fijo", $r['ieps_monto_fijo']);
      $stmt2->bindValue(":ish", $r['ish']);
      $stmt2->bindValue(":importe_ish", $r['importe_ish']);
      $stmt2->bindValue(":iva_exento", $r['iva_exento']);
      $stmt2->bindValue(":importe_iva_exento", $r['importe_iva_exento']);
      $stmt2->bindValue(":iva_retenido", $r['iva_retenido']);
      $stmt2->bindValue(":importe_iva_retenido", $r['importe_iva_retenido']);
      $stmt2->bindValue(":isr_retenido",$r['isr_retenido']);
      $stmt2->bindValue(":importe_isr_retenido", $r['importe_isr_retenido']);
      $stmt2->bindValue(":isn_local", $r['isn_local']);
      $stmt2->bindValue(":importe_isn_local", $r['importe_isn_local']);
      $stmt2->bindValue(":cedular", $r['cedular']);
      $stmt2->bindValue(":importe_cedular", $r['importe_cedular']);
      $stmt2->bindValue(":al_millar", $r['al_millar']);
      $stmt2->bindValue(":importe_al_millar", $r['importe_al_millar']);
      $stmt2->bindValue(":funcion_publica", $r['funcion_publica']);
      $stmt2->bindValue(":importe_funcion_publica", $r['importe_funcion_publica']);
      $stmt2->bindValue(":ieps_retenido", $r['ieps_retenido']);
      $stmt2->bindValue(":importe_ieps_retenido", $r['importe_ieps_retenido']);
      $stmt2->bindValue(":isr_exento", $r['isr_exento']);
      $stmt2->bindValue(":importe_isr_exento", $r['importe_isr_exento']);
      $stmt2->bindValue(":isr_monto_fijo", $r['isr_monto_fijo']);
      $stmt2->bindValue(":isr", $r['isr']);
      $stmt2->bindValue(":importe_isr", $r['importe_isr']);
      $stmt2->bindValue(":ieps_exento", $r['ieps_exento']);
      $stmt2->bindValue(":importe_ieps_exento", $r['importe_ieps_exento']);
      $stmt2->bindValue(":isr_retenido_monto_fijo", $r['isr_retenido_monto_fijo']);
      $stmt2->bindValue(":ieps_retenido_monto_fijo", $r['ieps_retenido_monto_fijo']);
      $stmt2->bindValue(":total_neto", $r['total']);
      $stmt2->bindValue(":usuario_id", $_SESSION['PKUsuario']);
      $stmt2->execute();
    }

    $query3 = sprintf("select
                        dpft.id id_row, 
                        dpft.referencia,
                        pr.PKProducto id, 
                        pr.ClaveInterna clave,
                        pr.Nombre nombre,
                        dpft.unidad_medida_id id_unidad_medida,
                        csu.Descripcion unidad_medida,
                        dpft.clave_sat_id sat_id,
                        dpft.descuento_tasa,
                        dpft.importe_descuento_tasa,
                        dpft.descuento_monto_fijo,
                        dpft.cantidad cantidad_total,
                        dpft.cantidad_facturada cantidad_facturada,
                        dpft.precio_unitario,
                        dpft.total_bruto,
                        dpft.iva,
                        dpft.importe_iva,
                        dpft.ieps,
                        dpft.importe_ieps,
                        dpft.ieps_monto_fijo,
                        dpft.ish,
                        dpft.importe_ish,
                        dpft.iva_exento,
                        dpft.importe_iva_exento,
                        dpft.iva_retenido,
                        dpft.importe_iva_retenido,
                        dpft.isr_retenido,
                        dpft.importe_isr_retenido,
                        dpft.isn_local,
                        dpft.importe_isn_local,
                        dpft.cedular,
                        dpft.importe_cedular,
                        dpft.al_millar,
                        dpft.importe_al_millar,
                        dpft.funcion_publica,
                        dpft.importe_funcion_publica,
                        dpft.ieps_retenido,
                        dpft.importe_ieps_retenido,
                        dpft.isr_exento,
                        dpft.importe_isr_exento,
                        dpft.isr_monto_fijo,
                        dpft.isr,
                        dpft.importe_isr,
                        dpft.ieps_exento,
                        dpft.importe_ieps_exento,
                        dpft.isr_retenido_monto_fijo,
                        dpft.ieps_retenido_monto_fijo,
                        dpft.total_neto
                      from datos_producto_facturacion_temp dpft
                        inner join productos pr on dpft.producto_id = pr.PKProducto
                        left join claves_sat_unidades csu on dpft.unidad_medida_id = csu.PKClaveSATUnidad
                      where usuario_id = :usuario_id and factura_concepto = 0 and tipo = :tipo and referencia = :ref");
    $stmt3 = $db->prepare($query3);
    $stmt3->bindValue(":usuario_id", $_SESSION['PKUsuario']);
    $stmt3->bindValue(":tipo", $tipo);
    $stmt3->bindValue(":ref", $referencia);
    $stmt3->execute();

    $detalleProducto = $stmt3->fetchAll();

    $alertaSat = "";

    foreach ($detalleProducto as $r) {
    $impuestos = "";
    $claveInterna = ($r['clave'] !== "" && $r['clave'] !== null) ? $r['clave'] : "S/C";

    $alertaSat = ($r['sat_id'] !== null && $r['sat_id'] !== "" && $r['sat_id'] !== 1) ? "" : '<img id=\"satAlert\" src=\"../../img/icons/ICONO ALERTA_Mesa de trabajo 1.svg\" style=\"width: 25px\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"No se asignó una clave SAT\">';

    $idUnidadMedida = ($r['id_unidad_medida'] !== "" && $r['id_unidad_medida'] !== null) ? $r['id_unidad_medida'] : "";
    $unidadMedida = ($r['unidad_medida'] !== "" && $r['unidad_medida'] !== null) ? $r['unidad_medida'] : "N/A";
    $cantidad = $r['cantidad_facturada'];

    if ($r['iva'] !== "" && $r['iva'] !== null) {
      $impuestos .= "IVA " . " " . $r['iva'] . "%: $ " . number_format($r['importe_iva'], 2) . "<br>";
    }
    if ($r['ieps'] !== "" && $r['ieps'] !== null) {
      $impuestos .= "IEPS " . " " . $r['ieps'] . "%: $ " . number_format($r['importe_ieps'], 2) . "<br>";
    }
    if ($r['ieps_monto_fijo'] !== "" && $r['ieps_monto_fijo'] !== null) {
      $impuestos .= "IEPS (Monto fijo): $ "  . number_format($r['ieps_monto_fijo'], 2) . "<br>";
    }
    if ($r['ish'] !== "" && $r['ish'] !== null) {
      $impuestos .= "ISH " . " " . $r['ish'] . "%: $ " . number_format($r['importe_ish'], 2) . "<br>";
    }
    if ($r['iva_exento'] !== "" && $r['iva_exento'] !== null) {
      $impuestos .= "IVA Exento " . " " . $r['iva_exento'] . "%: $ " . number_format($r['importe_iva_exento'], 2) . "<br>";
    }
    if ($r['iva_retenido'] !== "" && $r['iva_retenido'] !== null) {
      $impuestos .= "IVA Retenido " . " " . $r['iva_retenido'] . "%: $ " . number_format($r['importe_iva_retenido'], 2) . "<br>";
    }
    if ($r['isr'] !== "" && $r['isr'] !== null) {
      $impuestos .= "ISR " . " " . $r['isr'] . "%: $ " . number_format($r['importe_isr'], 2) . "<br>";
    }
    if ($r['isn_local'] !== "" && $r['isn_local'] !== null) {
      $impuestos .= "ISN (Local) " . " " . $r['isn_local'] . "%: $ " . number_format($r['importe_isn_local'], 2) . "<br>";
    }
    if ($r['cedular'] !== "" && $r['cedular'] !== null) {
      $impuestos .= "Cedular " . " " . $r['cedular'] . "%: $ " . number_format($r['importe_cedular'], 2) . "<br>";
    }
    if ($r['al_millar'] !== "" && $r['al_millar'] !== null) {
      $impuestos .= "5 al millar (Local) " . " " . $r['al_millar'] . "%: $ " . number_format($r['importe_al_millar'], 2) . "<br>";
    }
    if ($r['funcion_publica'] !== "" && $r['funcion_publica'] !== null) {
      $impuestos .= "Función Pública " . " " . $r['funcion_publica'] . "%: $ " . number_format($r['importe_funcion_publica'], 2) . "<br>";
    }

    if ($impuestos === "") {
      $impuestos = "Sin impuestos";
    }
    if ($r['descuento_tasa'] !== null && $r['descuento_tasa'] !== "") {
      $descuento = "Descuento " . $r['descuento_tasa'] . "%: " . $r['importe_descuento_tasa'];
    } else if ($r['descuento_monto_fijo'] !== null && $r['descuento_monto_fijo'] !== "") {
      $descuento = "Descuento: " . $r['importe_descuento_tasa'];
    } else {
      $descuento = "Sin descuento";
    }
    $edit = "<a id='edit" . $r['id'] . "' data-id='" . $r['id'] . "'  data-ref='" . $r['id_row'] . "' href='#' ><img src='../../img/icons/editar.svg' width='22px' data-toggle='tooltip' data-placement='right' title='Editar'>";
    
    $table .= '{
                "id":"' . $r['id'] . '",
                "edit":"' . $edit . '",
                "clave":"' . $claveInterna . '",
                "descripcion":"' . $r['nombre'] . '",
                "id_unidad_medida":"' . $idUnidadMedida . '",
                "sat_id":"' . $r['sat_id'] . '",
                "unidad_medida":"' . $unidadMedida . '",
                "cantidad":"' . $cantidad . '",
                "precio":"$ ' . number_format($r['precio_unitario'], 2) . '",
                "subtotal":"$ ' . number_format(($r['cantidad_facturada'] * $r['precio_unitario']), 2) . '",
                "impuestos":"' . $impuestos . '",
                "descuento":"' . $descuento . '",
                "importe_total":"$' . number_format($r['total_neto'], 2) . '",
                "alerta":"' . $alertaSat . '"
                },';
    $cont++;
    }

    $table = substr($table, 0, strlen($table) - 1);

    $con = "";
    $stmt = "";
    $db = "";

    return '{"data":[' . $table . ']}';
  }

  function getReferencias($productos)
  {
    $referencias = [];
    for($i = 0; $i > count($productos); $i++){
      if (!in_array($productos[$i]['referencia'], $referencias)) {
        array_push($referencias, $productos[$i]['referencia']);
      }
    }
    return $referencias;
  }

  //se añadió parametro folio salida para recibir el folio para el caso de los pedidos.
  function getPedidos($referencias,$tipo, $folio_salida = 0)
  {
    $con = new conectar();
    $db = $con->getDb();
    $pedidos = [];
    $falg=false;

    if($folio_salida == 0){
      $folio_salida = explode(',',$folio_salida);
      $falg=true;
    }
   
    if(is_array($referencias)){
      for ($i=0; $i < count($referencias); $i++) { 
        
        switch ((int)$tipo) {
          case 3:
            if($falg){
              $aux = 0;
            }else{
              $aux = $i; 
            }
            $queryPedidos = sprintf("select distinct p.id from orden_pedido_por_sucursales p 
                                      left join inventario_salida_por_sucursales s on p.id = s.orden_pedido_id
                                      left join movimientos_salidas_servicios_sin_inventario msssi on p.id = msssi.FKOrdenPedido
                                    where (s.id = :ref or msssi.PKMovServ = :ref2) and (s.folio_salida = :folio or msssi.FKSalida = :folio2) and p.empresa_id = :emp");
            $stmtPedidos = $db->prepare($queryPedidos);
            $stmtPedidos->bindValue(":ref", $referencias[$i]);
            $stmtPedidos->bindValue(":ref2", $referencias[$i]);
            $stmtPedidos->bindValue(":folio", $folio_salida[$aux]);
            $stmtPedidos->bindValue(":folio2", $folio_salida[$aux]);
            $stmtPedidos->bindValue(":emp", $_SESSION['IDEmpresa']);
            $stmtPedidos->execute();
            break;
          case 4:
            $queryPedidos = sprintf("select distinct s.orden_pedido_id id from remisiones r
                                        inner join inventario_salida_por_sucursales s on r.salida_id = s.folio_salida
                                      where s.id = :folio and r.empresa_id = :emp");
            $stmtPedidos = $db->prepare($queryPedidos);
            $stmtPedidos->bindValue(":folio", $referencias[$i]);
            $stmtPedidos->bindValue(":emp", $_SESSION['IDEmpresa']);
            $stmtPedidos->execute();
            break;
        }
              
        $idPedido = $stmtPedidos->fetchAll();
        
        if (!in_array($idPedido[0]['id'], $pedidos))
          array_push($pedidos, $idPedido[0]['id']);
      }
    } else if(gettype($referencias) == "string"){
      switch ((int)$tipo) {
        case 3:
          $queryPedidos = sprintf("select distinct p.id from orden_pedido_por_sucursales p 
                                      left join inventario_salida_por_sucursales s on p.id = s.orden_pedido_id
                                      left join movimientos_salidas_servicios_sin_inventario msssi on p.id = msssi.FKOrdenPedido
                                    where (s.id = :ref or msssi.PKMovServ = :ref2) and (s.folio_salida = :folio or msssi.FKSalida = :folio2) and p.empresa_id = :emp");

          $stmtPedidos = $db->prepare($queryPedidos);
          $stmtPedidos->bindValue(":ref", $referencias);
          $stmtPedidos->bindValue(":ref2", $referencias);
          $stmtPedidos->bindValue(":folio", $folio_salida[0]);
          $stmtPedidos->bindValue(":folio2", $folio_salida[0]);
          $stmtPedidos->bindValue(":emp", $_SESSION['IDEmpresa']);
          $stmtPedidos->execute();
          break;
        case 4:
          $queryPedidos = sprintf("select distinct s.orden_pedido_id from remisiones r
                                      inner join inventario_salida_por_sucursales s on r.salida_id = s.folio_salida
                                    where r.id = :folio and r.empresa_id = :emp");
         
          $stmtPedidos = $db->prepare($queryPedidos);
          $stmtPedidos->bindValue(":folio", $referencias);
          $stmtPedidos->bindValue(":emp", $_SESSION['IDEmpresa']);
          $stmtPedidos->execute();
          break;
      }
    
      $idPedido = $stmtPedidos->fetchAll();
      
      if (!in_array($idPedido[0]['id'], $pedidos))
        array_push($pedidos, $idPedido[0]['id']);
    }

    return $pedidos;
  }

  function getNotifications($array,$productos,$id_factura)
  {
    date_default_timezone_set('America/Mexico_City');
    $con = new conectar();
    $db = $con->getDb();
    $get_data = new get_data();
    $usuarios = [];
    
    $timestamp = date('Y-m-d H:i:s');
    $tipo = (int)$array['tipoDocumento'];
    $id = $array['idDocumento'];
    $referencias = $get_data->getReferencias($productos);
    $pedidos = $get_data->getPedidos($referencias,$tipo,$array['idDocumento']);

    switch ($tipo) {
      case 1:
        $query = sprintf("SELECT FKUsuarioCreacion, FKUsuarioEdicion FROM cotizacion where PKCotizacion = :id");
        $stmt = $db->prepare($query);
        $stmt->execute([":id" => $id[0]]);
        $usuariosRes = $stmt->fetch(PDO::FETCH_ASSOC);
        $usuarioCreo = $usuariosRes['FKUsuarioCreacion'];
        $usuarioEdito = $usuariosRes['FKUsuarioEdicion'];
        if (!in_array($usuarioCreo, $usuarios)) {
          array_push($usuarios, $usuarioCreo);
        }
        if (!in_array($usuarioEdito, $usuarios)) {
          array_push($usuarios, $usuarioEdito);
        }
        break;
      case 2:
        $query = sprintf("SELECT FKUsuarioCreacion, FKUsuarioEdicion FROM ventas_directas where PKVentaDirecta = :id");
        $stmt = $db->prepare($query);
        $stmt->execute([":id" => $id[0]]);
        $usuariosRes = $stmt->fetch(PDO::FETCH_ASSOC);
        $usuarioCreo = $usuariosRes['FKUsuarioCreacion'];
        $usuarioEdito = $usuariosRes['FKUsuarioEdicion'];
        if (!in_array($usuarioCreo, $usuarios)) {
          array_push($usuarios, $usuarioCreo);
        }
        if (!in_array($usuarioEdito, $usuarios)) {
          array_push($usuarios, $usuarioEdito);
        }
        break;
      case 3:
        for ($i=0; $i < count($pedidos); $i++) { 
          
          $query = sprintf("SELECT usuario_creo_id, usuario_edito_id FROM orden_pedido_por_sucursales where id = :id");
          $stmt = $db->prepare($query);
          $stmt->execute([":id" => $pedidos[$i]]);
          $usuariosRes = $stmt->fetch(PDO::FETCH_ASSOC);
          $usuarioCreo = $usuariosRes['usuario_creo_id'];
          $usuarioEdito = $usuariosRes['usuario_edito_id'];
          if (!in_array($usuarioCreo, $usuarios)) {
            array_push($usuarios, $usuarioCreo);
          }
          if (!in_array($usuarioEdito, $usuarios)) {
            array_push($usuarios, $usuarioEdito);
          }
        }
        break;
        
      case 4:
        for ($i=0; $i < count($pedidos); $i++) {
          $query = sprintf("SELECT usuario_creo_id, usuario_edito_id FROM orden_pedido_por_sucursales where id = :id");
          $stmt = $db->prepare($query);
          $stmt->execute([":id" => $pedidos[$i]]);
          $usuariosRes = $stmt->fetch(PDO::FETCH_ASSOC);
          $usuarioCreo = $usuariosRes['usuario_creo_id'];
          $usuarioEdito = $usuariosRes['usuario_edito_id'];
          if (!in_array($usuarioCreo, $usuarios)) {
            array_push($usuarios, $usuarioCreo);
          }
          if (!in_array($usuarioEdito, $usuarios)) {
            array_push($usuarios, $usuarioEdito);
          }
        }
        break;
    }
    // NOTIFICACIÓN 
    $stmt = $db->prepare('INSERT INTO notificaciones (tipo_notificacion, detalle_tipo_notificacion, id_elemento, created_at) VALUES (:tipoNot, :detaleNot, :idElem, :fecha)');
    $stmt->execute([':tipoNot' => 7, ':detaleNot' => 26, ':idElem' => $id_factura, ':fecha' => $timestamp]);
    $idNotification = $db->lastInsertId();
    // RELACIONAMOS EL/LOS USUARIOS CON LA NOTIFICACION 
    if ($array['tipoDocumento'] === "0") {
      // POR CONCEPTO SOLO NOTIFICO AL USUARIO QUE LA CREO
      $stmt = $db->prepare('INSERT INTO notificaciones_usuarios (id_notificacion, id_usuario) VALUES (:idNot, :idUsu)');
      $stmt->execute([':idNot' => $idNotification, ':idUsu' => $_SESSION['PKUsuario']]);
    } else {
      if (!in_array($_SESSION['PKUsuario'], $usuarios)) {
        array_push($usuarios, $_SESSION['PKUsuario']);
      }

      foreach ($usuarios as $usuario) {
        $stmt = $db->prepare('INSERT INTO notificaciones_usuarios (id_notificacion, id_usuario) VALUES (:idNot, :idUsu)');
        $stmt->execute([':idNot' => $idNotification, ':idUsu' => $usuario]);
      }
    }
  }

  function getComPago($value)
  {
   
    require_once '../../../include/functions_api_facturation.php';
    require_once '../../../vendor/facturapi/facturapi-php/src/Facturapi.php';
    //función para dar formato a las cantidades
    //require_once('../../function_formatoCantidad.php');
    require_once '../../recepcion_pagos/functions/function_formatoCantidad.php';

    $con = new conectar();
    $conn = $con->getDb();
    $api = new API();
    $Total=0;
    $empresa = $_SESSION["IDEmpresa"];

    $query = sprintf("select p.identificador_pago from movimientos_cuentas_bancarias_empresa m
                        inner join pagos p on m.id_pago = p.idpagos
                      where m.id_factura = :id_factura");
    $stmt = $conn->prepare($query);
    $stmt->bindValue(":id_factura",$value);
    $stmt->execute();

    $arr = $stmt->fetch(PDO::FETCH_OBJ);
    $folioPago = $arr->identificador_pago;
    
    //valida si el pago ya ha sido timbrado
    $query = sprintf('SELECT id_api FROM facturas_pagos where folio_pago="'.$folioPago.'" AND estatus=1 and empresa_id="'.$empresa.'";');
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $res=$stmt->rowCount();
    $result=$stmt->fetchAll();
    $stmt->closeCursor();

    if($res==0){
      //recuperación de los datos necesarios para facturapi
      //se recupera la key de la empresa
      $query = sprintf("select key_company_api key_company,key_user_company_api key_user from empresas where PKEmpresa = :id");
      $stmt = $conn->prepare($query);
      $stmt->bindValue(":id",$empresa);
      $stmt->execute();

      $key_company_api = $stmt->fetchAll();
      $stmt->closeCursor();

      //recupera el id del cliente mediante una factura del pago a facturar
    $query = sprintf('SELECT cl.regimen_fiscal_id ,cl.razon_social, cl.rfc, cl.PKCliente, cl.codigo_postal FROM facturacion f
    INNER JOIN clientes cl ON cl.PKCliente = f.cliente_id
    inner join movimientos_cuentas_bancarias_empresa m on m.id_factura=f.id
    inner join pagos p on p.idpagos=m.id_pago
    WHERE p.identificador_pago="'.$folioPago.'" and f.empresa_id = '.$empresa.' and p.estatus=1 limit 1;');

    $stmt = $conn->prepare($query);
    $stmt->execute();

    $cliente = $stmt->fetchAll();
    $cliente_Api = [
      "legal_name" => $cliente[0]['razon_social'],
      "tax_id" => $cliente[0]['rfc'],
      "tax_system" => $cliente[0]['regimen_fiscal_id'],
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
    WHERE p.identificador_pago="'.$folioPago.'" and f.empresa_id = '.$empresa.' and p.estatus=1;');
    $stmt = $conn->prepare($query);
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
                            dpft.isr,
                            dpft.isr_exento,
                            dpft.isr_monto_fijo,
                            dpft.isr_retenido,
                            dpft.isr_retenido_monto_fijo
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
        if ($r['iva'] !== "" && $r['iva'] !== null) {
          array_push(
            $impuestos,
            array(
              "base" => (float)$r['subtotal'],
              "type" => 'IVA',
              "rate" => (float)$r['iva'],
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
              "rate" => (float)$r['iva_exento'],
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
              "rate" => (float)$r['iva_retenido'],
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
              "rate" => (float)$r['ieps'],
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
              "rate" => (float)$r['ieps_retenido'],
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
              "rate" => (float)$r['ieps_exento'],
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
        if ($r['isr'] !== "" && $r['isr'] !== null) {
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
        }
        if ($r['isr_retenido'] !== "" && $r['isr_retenido'] !== null) {
          array_push(
            $impuestos,
            array(
              "base" => (float)$r['subtotal'],
              "type" => 'ISR',
              "rate" => (float)$r['isr_retenido'],
              "factor" => 'Tasa',
              "withholding" => true
            )
          );
        }
        if ($r['isr_retenido_monto_fijo'] !== "" && $r['isr_retenido_monto_fijo'] !== null) {
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
        }
      }

      $Total=$Total+$rows[$i]['Deposito'];
      $foliosInternosFacturas .='<h5>'.$rows[$i]['folioInternoFactura'].'</h5>';
      $facturasRelacionadas[]=[
        "uuid" => $rows[$i]['uuid'],
        "installment" => $rows[$i]['parcialidad'],
        "last_balance" => $rows[$i]['saldo_anterior'],
        "amount" => $rows[$i]['Deposito'],
        "taxes" => $impuestos,
        "currency" => $rows[$i]['clave_moneda'],
        "taxability" => $taxability
      ];
    }
    $stmt->closeCursor();


    $query = sprintf('SELECT fp.clave from formas_pago_sat fp
    inner join pagos p on p.forma_pago=fp.id
    inner join movimientos_cuentas_bancarias_empresa m on p.idpagos=m.id_pago
    inner join facturacion f on f.id=m.id_factura
    WHERE p.identificador_pago="'.$folioPago.'" and f.empresa_id = '.$empresa.' and p.estatus=1 limit 1;');
    $stmt = $conn->prepare($query);
    $stmt->execute();

    $forma_pago = $stmt->fetchAll();
    $stmt->closeCursor();

    //echo json_encode($forma_pago);

    $invoice = array(
      "type" => "P",
      "customer" => $cliente_Api,
      "complements" => array(
        "type" => "P",
        "data" => array(
          "payment_form" => $forma_pago[0]['clave'],
          "related_documents" => $facturasRelacionadas
        )),
      "pdf_custom_section" => $foliosInternosFacturas
    );
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
        $stmt->bindValue(":total_facturado",formatoCantidad($Total));
        $stmt->bindValue(":estatus",1);
        $stmt->bindValue(":empresa_id",$empresa);
        $stmt->bindValue(":forma_pago", $forma_pago[0]['clave']);
        $stmt->bindValue(":folio_pago",  $folioPago);
        $stmt->bindValue(":folio_complemento", $mensaje->folio_number);

        try{
          $stmt->execute();
          $data['status']="ok";
          $data['result']=$mensaje->id;

        }catch(exception $e){
          $data['status']="err";
          $data['result']="error: ".$e->getMessage();
        }
      }else{
        $data['status']="err";
        $data['result']="error: no se pudo crear el complemento de pago";
        $data['error']="El error es: ".$mensaje->message;
      } 
      }else{
        $data['status']="fine";
        $data['result']="inaccesible";
      }

    return $data;
  }

  function getAddressInvoiceCombo($value){
    $con = new conectar();
    $db = $con->getDb();

    $query = sprintf("select 
                        PKDireccionEnvioCliente id, 
                          concat(dcl.Sucursal,' ',
                            dcl.Calle,' ',
                            dcl.Numero_exterior,' Int. ',
                            dcl.Numero_Interior,' ',
                            dcl.Colonia,' ',
                            dcl.Municipio,' ',
                            e.Estado) texto 
                      from direcciones_envio_cliente dcl
                      left join estados_federativos e on dcl.Estado = e.PKEstado
                      where FKCliente = :id");
    $stmt = $db->prepare($query);
    $stmt->execute([":id"=>$value]);
                  
    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }

  //Funcion que recibe la referencia (id de la salida) y el folio para determinar si es producto o servicio
  function getIsProd ($id, $folio){
    $con = new conectar();
    $db = $con->getDb();

    if(is_array($folio)){
      $id_documento = $folio[0];
    } else {
      $id_documento = $folio;
    }

    $query1 = sprintf("Select FKProducto from movimientos_salidas_servicios_sin_inventario where PKMovServ = :ref and FKSalida = :folio");
    $stmt1 = $db->prepare($query1);
    $stmt1->bindValue(":ref", $id);
    $stmt1->bindValue(":folio", $id_documento);
    $stmt1->execute();
    $Serv = $stmt1->fetchAll();

    foreach ($Serv as $s){
      $query4 = sprintf("update movimientos_salidas_servicios_sin_inventario set estatus = 2 where PKMovServ = :id and FKProducto = :prod");
      $stmt4 = $db->prepare($query4);
      $stmt4->bindValue(":id", $id);
      $stmt4->bindValue(":prod", $s['FKProducto']);
      $stmt4->execute();
    }

    $query1 = sprintf("SELECT p.PKProducto, i.clave, i.numero_lote from inventario_salida_por_sucursales as i
                          inner join productos as p on p.ClaveInterna = i.clave and p.lote = i.numero_lote
                        where i.id = :ref and i.folio_salida = :folio and p.empresa_id = :empresa");
    $stmt1 = $db->prepare($query1);
    $stmt1->bindValue(":ref", $id);
    $stmt1->bindValue(":folio", $id_documento);
    $stmt1->bindValue(":empresa", $_SESSION['IDEmpresa']);
    $stmt1->execute();
    $prod = $stmt1->fetchAll();

    foreach ($prod as $p){
      $query2 = sprintf("select cantidad_facturada from datos_producto_facturacion_temp where referencia = :id and producto_id = :prod and numero_lote = :lote");
      $stmt2 = $db->prepare($query2);
      $stmt2->bindValue(":id", $id);
      $stmt2->bindValue(":prod", $p['PKProducto']);
      $stmt2->bindValue(":lote", $p['numero_lote']);
      $stmt2->execute();
      $cantidad_facturada_final = $stmt2->fetchAll();

      $query3 = sprintf("update inventario_salida_por_sucursales set cantidad_facturada = :cantidad, estatus = 2 where id = :id and clave = :clave and numero_lote = :lote");
      $stmt3 = $db->prepare($query3);
      $stmt3->bindValue(":cantidad", $cantidad_facturada_final[0]['cantidad_facturada']);
      $stmt3->bindValue(":id", $id);
      $stmt3->bindValue(":clave", $p['clave']);
      $stmt3->bindValue(":lote", $p['numero_lote']);
      $stmt3->execute();
    }
  }

    function getSucursales()
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf("select id, sucursal texto FROM sucursales where empresa_id = :id and activar_inventario = 1");
        $stmt = $db->prepare($query);
        $stmt->bindValue(":id",$_SESSION['IDEmpresa']);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    function getStockProduct($value,$value1)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf("select id, sum(existencia) existencia, numero_lote from existencia_por_productos where producto_id = :id and sucursal_id = :sucursal_id and existencia > 0 order by id asc limit 1");
        $stmt = $db->prepare($query);
        $stmt->bindValue(":id",$value);
        $stmt->bindValue(":sucursal_id",$value1);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    function getStockProductAll($value,$value1)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf("select id, existencia, numero_lote,clave_producto from existencia_por_productos where producto_id = :id and sucursal_id = :sucursal_id order by id asc");
        $stmt = $db->prepare($query);
        $stmt->bindValue(":id",$value);
        $stmt->bindValue(":sucursal_id",$value1);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    function getIfProductHasStock($prod_id,$suc)
    {
      $get_data = new get_data();
      $ban = 0;
      for ($i=0; $i < count($prod_id); $i++) { 
        $arr = $get_data->getStockProduct($prod_id[$i]['id'],$suc);
        if((int)$arr[0]->existencia < $prod_id[$i]['cant']){
          $ban++;
        }
      }
      return $ban;
    }

    function getFolioOrder()
    {
      $con = new conectar();
      $db = $con->getDb();

      $query = sprintf("SELECT id_orden_pedido_empresa FROM orden_pedido_por_sucursales WHERE empresa_id = :empresa_id ORDER BY id_orden_pedido_empresa DESC LIMIT 1");

      $stmt = $db->prepare($query);
      $stmt->bindValue(':empresa_id', $_SESSION['IDEmpresa']);
      $stmt->execute();
      $rowidordenpedido = $stmt->fetch();
      $idordenpedidoempresa = $rowidordenpedido['id_orden_pedido_empresa'] + 1;
      return $idordenpedidoempresa;
    }

    function getActiveStock($value)
    {
        $con = new conectar();
        $db = $con->getDb();
        $query = sprintf("select activar_inventario from sucursales where id = :id");
        $stmt = $db->prepare($query);
        $stmt->bindValue(':id', $value);
        $stmt->execute();
        $arr = $stmt->fetch();
        $stock_active = $arr['activar_inventario'];

        return $stock_active;
    }

    function getIfHasSaleOutputProduct($value)
    {
        $con = new conectar();
        $db = $con->getDb();
        $query = sprintf("select * from ventas_directas v 
                            inner join orden_pedido_por_sucursales p on v.PKVentaDirecta = p.numero_venta_directa
                            inner join inventario_salida_por_sucursales s on p.id = s.orden_pedido_id
                        where v.PKVentaDirecta = :ref");
        $stmt = $db->prepare($query);
        $stmt->bindValue(":ref",$value);
        $stmt->execute();
        return $stmt->rowCount();
    }

   

    function getIfHasQuotationOutputProduct($value)
    {
        $con = new conectar();
        $db = $con->getDb();
        $query = sprintf("select * from cotizacion c
                            inner join orden_pedido_por_sucursales p on c.PKCotizacion = p.numero_cotizacion
                            inner join inventario_salida_por_sucursales s on p.id = s.orden_pedido_id
                        where c.PKCotizacion = :ref");
        $stmt = $db->prepare($query);
        $stmt->bindValue(":ref",$value);
        $stmt->execute();
        return $stmt->rowCount();
    }
    
    function getIdOrderBySale($value)
    {
      $con = new conectar();
      $db = $con->getDb();

      $query = sprintf("select p.id,p.id_orden_pedido_empresa folio from facturacion f
                          inner join orden_pedido_por_sucursales p on f.referencia = p.numero_venta_directa
                        where f.id = :id");
      $stmt = $db->prepare($query);
      $stmt->bindValue(":id",$value);
      $stmt->execute();

      return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    function getDataInvoiceCancel($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf("select tipo, referencia from facturacion where id = :id");
        $stmt = $db->prepare($query);
        $stmt->bindValue(":id",$value);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);

    }

    function getDataInvoiceCancelCot($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf("select s.id salida_id, s.clave, s.numero_lote, s.cantidad, p.id pedido_id,s.sucursal_id from cotizacion c
                                inner join orden_pedido_por_sucursales p on c.PKCotizacion = p.numero_cotizacion
                                inner join inventario_salida_por_sucursales s on p.id = s.orden_pedido_id
                            where c.PKCotizacion = :id");
        $stmt = $db->prepare($query);
        $stmt->bindValue(":id",$value);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    function getDataInvoiceCancelFact($value)
    {
        $con = new conectar();
        $db = $con->getDb();
        $query = sprintf("select s.id salida_id, s.clave, s.numero_lote, s.cantidad, p.id pedido_id,s.sucursal_id from orden_pedido_por_sucursales p
                            inner join facturacion f on p.factura_id = f.id
                            inner join inventario_salida_por_sucursales s on p.id = s.orden_pedido_id
                        where f.id = :id");
        $stmt = $db->prepare($query);
        $stmt->bindValue(":id",$value);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    function getDataInvoiceCancelVenta($value)
    {
        $con = new conectar();
        $db = $con->getDb();
        $query = sprintf("select s.id salida_id, s.clave, s.numero_lote, s.cantidad, p.id pedido_id,s.sucursal_id,v.afecta_inventario from ventas_directas v
                                inner join orden_pedido_por_sucursales p on v.PKVentaDirecta = p.numero_venta_directa
                                inner join inventario_salida_por_sucursales s on p.id = s.orden_pedido_id
                            where v.PKVentaDirecta = :id");
        $stmt = $db->prepare($query);
        $stmt->bindValue(":id",$value);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    function getCountRelationSalesByTicket($id)
    {
      $con = new conectar();
      $db = $con->getDb();

      $query = sprintf("select * from relacion_tickets_ventas rtv
                          inner join ticket_punto_venta t on rtv.ticket_id = t.id
                        where t.empresa_id = :empresa_id and rtv.venta_id = :id");
      $stmt = $db->prepare($query);
      $stmt->bindValue(":empresa_id",$_SESSION['IDEmpresa']);
      $stmt->bindValue(":id",$id);
      $stmt->execute();

      return (int)$stmt->rowCount();
    }

    function getClientEmail($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('select dcc.Email from facturacion f
                            inner join dato_contacto_cliente dcc on f.cliente_id = FKCliente
                        where f.id = :id and dcc.EmailFacturacion = 1');
        $stmt = $db->prepare($query);
        $stmt->bindValue(":id",$value);
        $stmt->execute();
        $arr = $stmt->fetchAll(PDO::FETCH_OBJ);
        $query = sprintf("select email from facturacion f
                            inner join clientes c on f.cliente_id = c.PKCliente
                        where id = :id");
        $stmt = $db->prepare($query);
        $stmt->bindValue(":id",$value);
        $stmt->execute();
        $arr1 = $stmt->fetchAll(PDO::FETCH_OBJ);

        $email = "";
        if(count($arr) > 0){
            $email = $arr[0]->Email;
        } else{
            $email = $arr1[0]->email;
        }
        return $email;
    }

    function getRfcClient($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf("select rfc from clientes where PKCliente = :id");
        $stmt = $db->prepare($query);
        $stmt->bindValue(":id",$value);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    function getSalesData($initialDate,$finalDate)
    {
        $con = new conectar();
        $db = $con->getDb();
        $table = "";
        $sql = "";
        $actualYear = date('Y');

        if(
            ($initialDate !== null && $initialDate !== "") &&
            ($finalDate  !== null && $finalDate !== ""))
            {
                $sql .= " v.created_at between :initialDate and :finalDate";
            } else if(
                ($finalDate === null || $finalDate === "") and
                ($initialDate !== null && $initialDate !== "")
            ){
                $sql .= " v.created_at between :initialDate and now()";
            } else if(
                ($initialDate === null || $initialDate === "") and
                $finalDate  !== null && $finalDate !== ""
            ){
                $sql .= " v.created_at between '2019-01-01' and :finalDate";
            } else {
                $sql .= " year(v.created_at) = :year";
            }

        $query = sprintf("
            select 
                v.PKVentaDirecta id,
                v.Referencia folio,
                v.created_at fecha, 
                c.razon_social,
                v.subtotal
            from ventas_directas v
                inner join clientes c on v.FKCliente = c.PKCliente
            where 
                v.empresa_id = :empresa_id and 
                (v.estatus_factura_id = 3 or v.estatus_factura_id = 4) and
                c.rfc = 'XAXX010101000' and
                $sql
            order by PKVentaDirecta desc;
        ");

        $stmt = $db->prepare($query);
        $stmt->bindValue(":empresa_id",$_SESSION['IDEmpresa']);
        if(
            ($initialDate !== null && $initialDate !== "") &&
            ($finalDate  !== null && $finalDate !== ""))
            {
                $stmt->bindValue(":initialDate",date("Y-m-d",strtotime($initialDate)));
                $stmt->bindValue(":finalDate",date("Y-m-d",strtotime($finalDate)));
            } else if(
                ($finalDate === null || $finalDate === "") and
                ($initialDate !== null && $initialDate !== "")
            ){
                $stmt->bindValue(":initialDate",date("Y-m-d",strtotime($initialDate)));
            } else if(
                ($initialDate === null || $initialDate === "") and
                $finalDate  !== null && $finalDate !== ""
            ){
                $stmt->bindValue(":finalDate",date("Y-m-d",strtotime($finalDate)));
            } else {
                $stmt->bindValue(":year",$actualYear);
            }
        $stmt->execute();
        $arr = $stmt->fetchAll(PDO::FETCH_OBJ);

        foreach ($arr as $r) {
            $html = "<div><input class='sales-checked' type='checkbox' data-id='".$r->id."' data-total='".$r->subtotal."'></div>";
            $table .= 
                '{
                    "id" : "' . $r->id . '",
                    "folio" : "' . $r->folio . '",
                    "fecha" : "' . date("d-m-Y",strtotime($r->fecha)) . '",
                    "cliente" : "' . $r->razon_social . '",
                    "total" : "$' . number_format($r->subtotal,2) . '",
                    "total_" : "'.$r->subtotal.'",
                    "funciones" : "' . $html . '"
                },';
        }
        $table = substr($table, 0, strlen($table) - 1);

        $con = "";
        $stmt = "";
        $db = "";

        return '{"data":[' . $table . ']}';
    }

    function getTaxesSales($arr){
        $con = new conectar();
        $db = $con->getDb();
        $sql = "";
        
        for ($i=0; $i < count($arr); $i++) {
            $sql .= "v.FKVentaDirecta = ".$arr[$i]['id']." or ";
        }
        $sql = substr($sql, 0, strlen($sql) - 3);

        $query = sprintf("
                select 
                i.Nombre nombre,
                t.TipoImpuesto tipo,
                v.Tasa tasa,
                sum(v.TotalImpuesto) total
            from impuestos_venta_directa v 
            inner join impuesto i on v.FKImpuesto = i.PKImpuesto
            inner join tipos_impuestos t on i.FKTipoImpuesto = t.PKTipoImpuesto
            where ($sql)
            group by v.FKImpuesto,v.Tasa
        ");

        $stmt = $db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    function getTaxesSalesByID($id){
      $con = new conectar();
      $db = $con->getDb();

      $query = sprintf("
              select 
              i.PKImpuesto id,
              i.Nombre nombre,
              t.TipoImpuesto tipo,
              v.Tasa tasa,
              sum(v.TotalImpuesto) total
          from impuestos_venta_directa v 
          inner join impuesto i on v.FKImpuesto = i.PKImpuesto
          inner join tipos_impuestos t on i.FKTipoImpuesto = t.PKTipoImpuesto
          where v.FKVentaDirecta = :id
      ");

      $stmt = $db->prepare($query);
      $stmt->bindValue(":id",$id);
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_OBJ);
  }

    function getTaxSummary($ids)
    {
        $get_data = new get_data();

        $arr = $get_data->getTaxesSales($ids);
        $total = 0;
        $html = '<div class="row"> ';
        foreach($arr as $r)
        {
            $html .=             
            '<div class="col-12"><h5><b class="textBlue">Impuestos:</b></h5>
                <div class="row">
                    <div class="col-4"></div>
                    <div class="col-4">
                        <h5><b class="textBlue">'.$r->nombre.' '.$r->tasa.'%:</b></h5>
                    </div>
                    <div class="col-4 text-right">
                        <h5 class="textBlue">$ <span id="subtotal">'.$r->total.'</span></h5>
                    </div>
                </div>
            </div>
            ';
            $total += $r->total;
        }
        $html .= '</div>';

        return ["texto"=>$html,"total"=>$total];
    }

  function getDataGlobalInvoice($value)
  {
    $products = [];
    $get_data = new get_data();
    $arr = json_decode($value);

    foreach($arr as $r)
    {
      $impuestos = [];
      $impuestos_local = [];
      $arr1 = $get_data->getTaxesSalesByID($r->id);
      foreach($arr1 as $r1)
      {
        switch ($r1->id) {
          case 1:
            array_push(
              $impuestos,
              [
              "withholding" => false,
              "factor" => "Tasa",
              "type" => "IVA",
              "rate" => ($r1->tasa / 100)
              ]
            );
            break;
          case 2:
            array_push(
              $impuestos,
              [
              "withholding" => false,
              "factor" => "Tasa",
              "type" => "IEPS",
              "rate" => ($r1->tasa / 100)
              ]
            );
            break;
          case 3:
            array_push(
              $impuestos,
              [
              "withholding" => false,
              "factor" => "Cuota",
              "type" => "IEPS",
              "rate" => $r1->tasa
              ]
            );
            break;
          case 4:
            array_push(
              $impuestos_local,
              [
              "withholding" => false,
              "type" => "ISH",
              "rate" => ($r->tasa / 100)
              ]
            );
            break;
          case 5:
            array_push(
              $impuestos,
              [
              "withholding" => false,
              "factor" => "Exento",
              "type" => "IVA"
              ]
            );
            break;
          case 6:
            array_push(
              $impuestos,
              [
              "withholding" => true,
              "factor" => "Tasa",
              "type" => "IVA",
              "rate" => round(($r->tasa / 100),4)
              ]
            );
            break;
          case 7:
            array_push(
              $impuestos,
              [
              "withholding" => true,
              "factor" => "Tasa",
              "type" => "ISR",
              "rate" => ($r->tasa / 100)
              ]
            );
            break;
          case 8:
            array_push(
              $impuestos_local,
              [
              "withholding" => false,
              "type" => "ISN",
              "rate" => ($r->tasa / 100)
              ]
            );
            break;
          case 9:
            array_push(
              $impuestos_local,
              [
              "withholding" => false,
              "type" => "Cedular",
              "rate" => ($r->tasa / 100)
              ]
            );
            break;
          case 10:
            array_push(
              $impuestos_local,
              [
              "withholding" => false,
              "type" => "5 al millar",
              "rate" => ($r->tasa / 100)
              ]
            );
            break;
          case 11:
            array_push(
              $impuestos_local,
              [
              "withholding" => false,
              "type" => "Funcion publica",
              "rate" => ($r['funcion_publica_tasa'] / 100)
              ]
            );
            break;
          case 12:
            array_push(
              $impuestos,
              [
              "withholding" => true,
              "factor" => "Tasa",
              "type" => "IEPS",
              "rate" => ($r->tasa / 100)
              ]
            );
            break;
          case 16:
            array_push(
              $impuestos,
              [
              "withholding" => false,
              "factor" => "Exento",
              "type" => "IEPS",
              "rate" => ($r->tasa / 100)
              ]
            );
            break;
          case 18:
            array_push(
              $impuestos,
              [
              "withholding" => true,
              "factor" => "Cuota",
              "type" => "IEPS",
              "rate" => $r['ieps_retenido_monto_fijo']
              ]
            );
            break;
        }
      }
      array_push($products,[
        "quantity" => 1,
        "product" => [
          "tax_included" => false,
          "description" => "Venta",
          "product_key" => "01010101",
          "price" => $r->subtotal,
          "unit_key" => "ACT",
          "taxes" => $impuestos,
          "local_taxes" => $impuestos_local
        ]
      ]);
    }
    return $products;
  }
  
    function getClientDefault()
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf("
            select 
                cl.PKCliente id,
                cl.razon_social,
                if(dc.EmailFacturacion = 1,dc.Email,cl.Email) email,
                cl.codigo_postal,
                rf.clave,
                cl.rfc
            from clientes cl
            inner join claves_regimen_fiscal rf on cl.regimen_fiscal_id = rf.id
            left join dato_contacto_cliente dc on cl.PKCliente = dc.FKCliente
            where 
                empresa_id = :empresa_id and 
                Predeterminado = 1"
        );

        $stmt = $db->prepare($query);
        $stmt->bindValue(":empresa_id",$_SESSION['IDEmpresa']);
        $stmt->execute();

        $arr = $stmt->fetchAll(PDO::FETCH_OBJ);

        $stmt = null;
        $db = null;

        return $arr;
    }

    function getFormatClientGeneralInvoice()
    {
        $get_data = new get_data();

        $client = [
            "legal_name" => $get_data->getClientDefault()[0]->razon_social,
            "email" => $get_data->getClientDefault()[0]->email,
            "tax_id" => $get_data->getClientDefault()[0]->rfc,
            "tax_system" => $get_data->getClientDefault()[0]->clave,
            "address" => [
            "zip" => $get_data->getClientDefault()[0]->codigo_postal
            ]
        ];

        return $client;
    }

    function getKeyPaymentForm($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf("select clave from formas_pago_sat where id = {$value}");
        $stmt = $db->prepare($query);
        $stmt->execute();

        $arr = $stmt->fetchAll(PDO::FETCH_OBJ);

        $stmt = null;
        $db = null;

        return $arr;
    }

    function getKeyCfdiUse($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf("select clave from uso_cfdi where id = {$value}");
        $stmt = $db->prepare($query);
        $stmt->execute();

        $arr = $stmt->fetchAll(PDO::FETCH_OBJ);

        $stmt = null;
        $db = null;

        return $arr;
    }

    function getKeyCurrency($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf("select Clave clave from monedas where PKMoneda = {$value}");
        $stmt = $db->prepare($query);
        $stmt->execute();

        $arr = $stmt->fetchAll(PDO::FETCH_OBJ);

        $stmt = null;
        $db = null;

        return $arr;
    }

    function getFormatGeneralInvoice($value,$value1)
    {
        $get_data = new get_data();
        $invoice = [];
        $folio_serie = $get_data->getFolioSerie();
        $fechaEmision = date("c");
        $data = json_decode($value1);

        $paidType = $get_data->getKeyPaymentForm($data->paidType)[0]->clave;
        $cfdiUse = $get_data->getKeyCfdiUse($data->cfdiUse)[0]->clave;
        $currency = $get_data->getKeyCurrency($data->currency)[0]->clave;
        
        $invoice = [
            "customer" => $get_data->getFormatClientGeneralInvoice(),
            "items" => $get_data->getDataGlobalInvoice($value),
            "payment_form"=> $paidType,
            "type" => "I",
            "use" => $cfdiUse,
            "payment_method" => $data->paidMethod,
            "currency" => $currency,
            "folio_number" => $folio_serie['folio'],
            "date" => $fechaEmision,
            "series" => $folio_serie['serie'],
            "global" => [
                "periodicity" => $data->periodicity,
                "months" => $data->month,
                "year" => $data->year
            ]
        ];

        return $invoice;
    }
  
}

class save_data
{

    function createGeneralInvoice($value,$value1)
    {
        include_once "../../../include/functions_api_facturation.php";
        $get_data = new get_data();
        $api = new API();
        $keyCompany = $get_data->getApiKeys();
        $data = $get_data->getFormatGeneralInvoice($value,$value1);
        
        return $api->createInvoice($keyCompany[0]->key_company,$data);
    }

    function saveGeneralInvoice($value,$value1,$value2,$value3)
    {
        $con = new conection();
        $db = $con->getDb();
        $get_data = new get_data();

        $data1 = json_decode($value);
        
        $ticket_id = $get_data->getIdTicketGeneralInvoice($value2,$value3);
        $cliente = $get_data->getClientDefault()[0]->id;

        $mp = $data1->paidMethod === "PUE" ? 1 : 3;

        $query = sprintf("
            insert into facturacion 
            (
            id_api,
            serie,
            folio,
            referencia,
            tipo,
            uuid,
            fecha_timbrado,
            cliente_id,
            usuario_timbro_id,
            estatus,
            estatus_old,
            total_facturado,
            empresa_id,
            forma_pago_id,
            metodo_pago,
            uso_cfdi_id,
            moneda_id,
            version_factura,
            prefactura
            ) values ( 
            '{$value1->id}',
            '{$value1->series}',
            '{$value1->folio_number}',
            '{$ticket_id}',
            '5',
            '{$value1->uuid}',
            NOW(),
            '{$cliente}',
            '{$_SESSION['PKUsuario']}',
            '3',
            '3',
            '{$value1->total}',
            '{$_SESSION['IDEmpresa']}',
            '{$data1->paidType}',
            '{$mp}',
            '{$data1->cfdiUse}',
            '{$data1->currency}',
            '4.0',
            '0'
            )
        ");

        $stmt = $db->prepare($query);
        $stmt->execute();

        return $db->lastInsertId();
    }

    function saveGlobalInvoice($value,$value1)
    {
        $get_data = new get_data();
        $save_data = new save_data();
        return $save_data->createGeneralInvoice($value,$value1);
        //$arr = $get_data->getFormatGeneralInvoice($value,$value1);
        //$save_data->newCreateInvoice($key,$data);

        //return $arr;
        
    }

  function saveProductoConcepto($value,$value1)
  {
    $con = new conectar();
    $db = $con->getDb();
    $save_data = new save_data();
    //$get_data->getTruncateTableProducts(1,0,0);

    $json = json_decode($value);
    if(!is_null($value1) && !empty($value1))
    {
      $query = sprintf("select
                          pr.PKProducto producto_id,
                          pr.ClaveInterna clave, 
                          pr.Descripcion,
                          ifp.FKClaveSATUnidad,
                          ifp.FKClaveSAT,
                          sum(epp.existencia) stock
                        from productos pr
                          left join costo_venta_producto cvp on pr.PKProducto = cvp.FKProducto
                          left join info_fiscal_productos ifp on pr.PKProducto = ifp.FKProducto
                          left join claves_sat_unidades csu on ifp.FKClaveSATUnidad = csu.PKClaveSATUnidad
                          left join existencia_por_productos epp on pr.PKProducto = epp.producto_id
                        where PKProducto = :id and empresa_id = :empresa_id and epp.sucursal_id = :sucursal_id");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":id", $json->producto_id);
    $stmt->bindValue(":empresa_id", $_SESSION['IDEmpresa']);
    $stmt->bindValue(":sucursal_id", $value1);
    $stmt->execute();
    } else {
      $query = sprintf("select
                          pr.PKProducto producto_id,
                          pr.ClaveInterna clave, 
                          pr.Descripcion,
                          ifp.FKClaveSATUnidad,
                          ifp.FKClaveSAT
                        from productos pr
                          left join costo_venta_producto cvp on pr.PKProducto = cvp.FKProducto
                          left join info_fiscal_productos ifp on pr.PKProducto = ifp.FKProducto
                          left join claves_sat_unidades csu on ifp.FKClaveSATUnidad = csu.PKClaveSATUnidad
                          
                        where PKProducto = :id and empresa_id = :empresa_id");
      $stmt = $db->prepare($query);
      $stmt->bindValue(":id", $json->producto_id);
      $stmt->bindValue(":empresa_id", $_SESSION['IDEmpresa']);
      $stmt->execute();
    } 

    $prod =  $stmt->fetchAll();
    
    if(isset($prod[0]['stock']) && $prod[0]['stock'] >= $json->cantidad){
      return $save_data->saveProductsConceptInvoice($prod,$json);
    } else if(isset($prod[0]['stock']) && $prod[0]['stock'] < $json->cantidad){
      
      return ["message"=>0];
      
    } else if(!isset($prod[0]['stock'])){
      return $save_data->saveProductsConceptInvoice($prod,$json);
    }
    
  }

  function saveProductsConceptInvoice($prod,$json)
  {
    $con = new conectar();
    $db = $con->getDb();
   
    $table = [];

    $impuestos_importe = 0;
    $tasa_iva = null;
    $monto_iva = null;
    $tasa_ieps = null;
    $monto_ieps = null;
    $monto_ieps_fijo_inicial = null;
    $monto_ieps_fijo = null;
    $tasa_ish = null;
    $monto_ish = null;
    $tasa_iva_exento = null;
    $monto_iva_exento = null;
    $tasa_iva_retenido = null;
    $monto_iva_retenido = null;
    $tasa_isr_retenido = null;
    $monto_isr_retenido = null;
    $tasa_isn_local = null;
    $monto_isn_local = null;
    $tasa_cedular = null;
    $monto_cedular = null;
    $tasa_al_millar = null;
    $monto_al_millar = null;
    $tasa_funcion_publica = null;
    $monto_funcion_publica = null;
    $tasa_ieps_retenido = null;
    $monto_ieps_retenido = null;
    $tasa_isr_exento = null;
    $monto_isr_exento = null;
    $isr_monto_fijo = null;
    $tasa_isr = null;
    $monto_isr = null;
    $tasa_ieps_exento = null;
    $monto_ieps_exento = null;
    $isr_retenido_monto_fijo = null;
    $ieps_retenido_monto_fijo = null;

    $query1 = sprintf("select imp.PKImpuesto id,imp.Nombre nombre,ipr.Tasa tasa from productos pro
                          left join info_fiscal_productos ifp on pro.PKProducto = ifp.FKProducto
                          left join impuestos_productos ipr on ifp.PKInfoFiscalProducto = ipr.FKInfoFiscalProducto
                          left join impuesto imp on ipr.FKImpuesto = imp.PKImpuesto
                        where pro.PKProducto = :producto");
    $stmt1 = $db->prepare($query1);
    $stmt1->bindValue(":producto", $prod[0]['producto_id']);
    $stmt1->execute();

    $impuestos_table = $stmt1->fetchAll();

    $queryRowCount = sprintf("select id,cantidad from datos_producto_facturacion_temp dpft where dpft.producto_id = :id and dpft.factura_concepto = 1 and usuario_id = :usuario_id and tipo = 0 and referencia = 0");
    $stmtRowCount = $db->prepare($queryRowCount);
    $stmtRowCount->bindValue(":id", $prod[0]['producto_id']);
    $stmtRowCount->bindValue(":usuario_id", $_SESSION['PKUsuario']);
    $stmtRowCount->execute();
    $rowCount = $stmtRowCount->rowCount();

    $id_insert = "";
    
    if ($rowCount === 0) {
      $importe = (int)$json->cantidad * (float)$json->precio_unitario;
      if (count($impuestos_table) > 0) {
        foreach ($impuestos_table as $r1) {
          switch ($r1["id"]) {
            case 1:
              $tasa_iva = $r1['tasa'];
              $monto_iva = ($importe * ($r1['tasa'] / 100));
              
              $impuestos_importe += $monto_iva;
              break;
            case 2:
              $tasa_ieps = $r1['tasa'];
              $monto_ieps = ($importe * ($r1['tasa'] / 100));
            
              $impuestos_importe += $monto_ieps;
              break;
            case 3:
              $monto_ieps_fijo_inicial = $r1['tasa'];
              $monto_ieps_fijo = $r1['tasa'] * (int)$json->cantidad;
            
              $impuestos_importe += $monto_ieps_fijo;
              break;
            case 4:
              $tasa_ish = $r1['tasa'];
              $monto_ish = ($importe * ($r1['tasa'] / 100));
              $impuestos_importe += $monto_ish;
              break;
            case 5:
              $tasa_iva_exento = $r1['tasa'];
              $monto_iva_exento = ($importe * ($r1['tasa'] / 100));
              $impuestos_importe += $monto_iva_exento;
              break;
            case 6:
              $tasa_iva_retenido = $r1['tasa'];
              $monto_iva_retenido = ($importe * ($r1['tasa'] / 100));
              $impuestos_importe -= $monto_iva_retenido;
              break;
            case 7:
              $tasa_isr_retenido = $r1['tasa'];
              $monto_isr_retenido = ($importe * ($r1['tasa'] / 100));
              $impuestos_importe -= $monto_isr_retenido;
              break;
            case 8:
              $tasa_isn_local = $r1['tasa'];
              $monto_isn_local = ($importe * ($r1['tasa'] / 100));
              $impuestos_importe += $monto_isn_local;
              break;
            case 9:
              $tasa_cedular = $r1['tasa'];
              $monto_cedular = ($importe * ($r1['tasa'] / 100));
              $impuestos_importe += $monto_cedular;
              break;
            case 10:
              $tasa_al_millar = $r1['tasa'];
              $monto_al_millar = ($importe * ($r1['tasa'] / 100));
              $impuestos_importe += $monto_al_millar;
              break;
            case 11:
              $tasa_funcion_publica = $r1['tasa'];
              $monto_funcion_publica = ($importe * ($r1['tasa'] / 100));
              $impuestos_importe += $monto_funcion_publica;
              break;
            case 12:
              $tasa_ieps_retenido = $r1['tasa'];
              $monto_ieps_retenido = ($importe * ($r1['tasa'] / 100));
              $impuestos_importe += $monto_ieps_retenido;
              break;
            case 13:
              $tasa_ieps_exento = $r1['tasa'];
              $monto_ieps_exento = ($importe * ($r1['tasa'] / 100));
              $impuestos_importe += $monto_ieps_exento;
              break;
            case 14:
              $monto_isr_monto_fijo = ($importe * ($r1['tasa'] / 100));
              $impuestos_importe += $monto_isr_monto_fijo;
              break;
            case 15:
              $tasa_isr = $r1['tasa'];
              $monto_isr = ($importe * ($r1['tasa'] / 100));
              $impuestos_importe += $monto_isr;
              break;
            case 16:
              $tasa_isr_exento = $r1['tasa'];
              $monto_isr_exento = ($importe * ($r1['tasa'] / 100));
              $impuestos_importe += $monto_isr_exento;
              break;
            case 17:
              $isr_retenido_monto_fijo = ($importe * ($r1['tasa'] / 100));
              $impuestos_importe += $isr_retenido_monto_fijo;
              break;
            case 18:
              $ieps_retenido_monto_fijo = ($importe * ($r1['tasa'] / 100));
              $impuestos_importe += $ieps_retenido_monto_fijo;
              break;
          }
        }
        
        if($monto_iva !== null && $monto_ieps !== null){
          $monto_iva += $monto_ieps * ($tasa_iva / 100);
          $impuestos_importe += $monto_ieps * ($tasa_iva / 100);
        }

        if($monto_iva !== null && $monto_ieps_fijo !== null){
          $monto_iva += $monto_ieps_fijo * ($tasa_iva / 100);
          $impuestos_importe += $monto_ieps_fijo * ($tasa_iva / 100);
        }

        $totalDoc = $importe + $impuestos_importe;
      }

      $query2 = sprintf("insert into datos_producto_facturacion_temp 
                            (
                              referencia,
                              tipo,
                              producto_id,
                              unidad_medida_id,
                              clave_sat_id,
                              cantidad,
                              cantidad_facturada,
                              precio_unitario,
                              total_bruto,
                              iva,
                              importe_iva,
                              ieps,
                              importe_ieps,
                              ieps_monto_fijo,
                              ish,
                              importe_ish,
                              iva_exento,
                              importe_iva_exento,
                              iva_retenido,
                              importe_iva_retenido,
                              isr_retenido,
                              importe_isr_retenido,
                              isn_local,
                              importe_isn_local,
                              cedular,
                              importe_cedular,
                              al_millar,
                              importe_al_millar,
                              funcion_publica,
                              importe_funcion_publica,
                              ieps_retenido,
                              importe_ieps_retenido,
                              isr_exento,
                              importe_isr_exento,
                              isr_monto_fijo,
                              isr,
                              importe_isr,
                              ieps_exento,
                              importe_ieps_exento,
                              isr_retenido_monto_fijo,
                              ieps_retenido_monto_fijo,
                              total_neto,
                              usuario_id,
                              factura_concepto
                            ) values (
                              :referencia,
                              :tipo,
                              :producto_id,
                              :unidad_medida_id,
                              :clave_sat_id,
                              :cantidad,
                              :cantidad_facturada,
                              :precio_unitario,
                              :total_bruto,
                              :iva,
                              :importe_iva,
                              :ieps,
                              :importe_ieps,
                              :ieps_monto_fijo,
                              :ish,
                              :importe_ish,
                              :iva_exento,
                              :importe_iva_exento,
                              :iva_retenido,
                              :importe_iva_retenido,
                              :isr_retenido,
                              :importe_isr_retenido,
                              :isn_local,
                              :importe_isn_local,
                              :cedular,
                              :importe_cedular,
                              :al_millar,
                              :importe_al_millar,
                              :funcion_publica,
                              :importe_funcion_publica,
                              :ieps_retenido,
                              :importe_ieps_retenido,
                              :isr_exento,
                              :importe_isr_exento,
                              :isr_monto_fijo,
                              :isr,
                              :importe_isr,
                              :ieps_exento,
                              :importe_ieps_exento,
                              :isr_retenido_monto_fijo,
                              :ieps_retenido_monto_fijo,                             
                              :total_neto,
                              :usuario_id,
                              :factura_concepto
                            )");
      $stmt2 = $db->prepare($query2);
      $stmt2->bindValue(":referencia",0);
      $stmt2->bindVAlue(":tipo",0);
      $stmt2->bindValue(":producto_id", $prod[0]['producto_id']);
      $stmt2->bindValue(":unidad_medida_id", $prod[0]['FKClaveSATUnidad']);
      $stmt2->bindValue(":clave_sat_id", $prod[0]['FKClaveSAT']);
      $stmt2->bindValue(":cantidad", (int)$json->cantidad);
      $stmt2->bindValue(":cantidad_facturada", (int)$json->cantidad);
      $stmt2->bindValue(":precio_unitario", (float)$json->precio_unitario);
      $stmt2->bindValue(":total_bruto", (int)$json->cantidad * (float)$json->precio_unitario);
      $stmt2->bindValue(":iva", $tasa_iva);
      $stmt2->bindValue(":importe_iva", $monto_iva);
      $stmt2->bindValue(":ieps", $tasa_ieps);
      $stmt2->bindValue(":importe_ieps", $monto_ieps);
      $stmt2->bindValue(":ieps_monto_fijo", $monto_ieps_fijo_inicial);
      $stmt2->bindValue(":ish", $tasa_ish);
      $stmt2->bindValue(":importe_ish", $monto_ish);
      $stmt2->bindValue(":iva_exento", $tasa_iva_exento);
      $stmt2->bindValue(":importe_iva_exento", $monto_iva_exento);
      $stmt2->bindValue(":iva_retenido", $tasa_iva_retenido);
      $stmt2->bindValue(":importe_iva_retenido", $monto_iva_retenido);
      $stmt2->bindValue(":isr_retenido", $tasa_isr_retenido);
      $stmt2->bindValue(":importe_isr_retenido", $monto_isr_retenido);
      $stmt2->bindValue(":isn_local", $tasa_isn_local);
      $stmt2->bindValue(":importe_isn_local", $monto_isn_local);
      $stmt2->bindValue(":cedular", $tasa_cedular);
      $stmt2->bindValue(":importe_cedular", $monto_cedular);
      $stmt2->bindValue(":al_millar", $tasa_al_millar);
      $stmt2->bindValue(":importe_al_millar", $monto_al_millar);
      $stmt2->bindValue(":funcion_publica", $tasa_funcion_publica);
      $stmt2->bindValue(":importe_funcion_publica", $monto_funcion_publica);
      $stmt2->bindValue(":ieps_retenido", $tasa_ieps_retenido);
      $stmt2->bindValue(":importe_ieps_retenido", $monto_ieps_retenido);
      $stmt2->bindValue(":isr_exento", $tasa_isr_exento);
      $stmt2->bindValue(":importe_isr_exento", $monto_isr_exento);
      $stmt2->bindValue(":isr_monto_fijo", $isr_monto_fijo);
      $stmt2->bindValue(":isr", $tasa_isr);
      $stmt2->bindValue(":importe_isr", $monto_isr);
      $stmt2->bindValue(":ieps_exento", $tasa_ieps_exento);
      $stmt2->bindValue(":importe_ieps_exento", $monto_ieps_exento);
      $stmt2->bindValue(":isr_retenido_monto_fijo", $isr_retenido_monto_fijo);
      $stmt2->bindValue(":ieps_retenido_monto_fijo", $ieps_retenido_monto_fijo);
      $stmt2->bindValue(":total_neto", $totalDoc);
      $stmt2->bindValue(":usuario_id", $_SESSION['PKUsuario']);
      $stmt2->bindValue(":factura_concepto", 1);
      $stmt2->execute();
      $id_insert = $db->lastInsertId();
    } else {
      $auxArr = $stmtRowCount->fetchAll();
      $id_row = $auxArr[0]["id"];
      $cantidad_row = $auxArr[0]["cantidad"];
      $cantidad_row1 = (int)$cantidad_row + (int)$json->cantidad;
      $importe = $cantidad_row1 * (float)$json->precio_unitario;

      if (count($impuestos_table) > 0) {
        foreach ($impuestos_table as $r1) {
          switch ($r1["id"]) {
            case 1:
              $tasa_iva = $r1['tasa'];
              $monto_iva = ($importe * ($r1['tasa'] / 100));
              $impuestos_importe += $monto_iva;
              break;
            case 2:
              $tasa_ieps = $r1['tasa'];
              $monto_ieps = ($importe * ($r1['tasa'] / 100));
              $impuestos_importe += $monto_ieps;
              break;
            case 3:
              $monto_ieps_fijo_inicial = $r1['tasa'];
              $monto_ieps_fijo = $monto_ieps_fijo_inicial * $cantidad_row;
              $impuestos_importe += $monto_ieps_fijo;
              break;
            case 4:
              $tasa_ish = $r1['tasa'];
              $monto_ish = ($importe * ($r1['tasa'] / 100));
              $impuestos_importe += $monto_ish;
              break;
            case 5:
              $tasa_iva_exento = $r1['tasa'];
              $monto_iva_exento = ($importe * ($r1['tasa'] / 100));
              $impuestos_importe += $monto_iva_exento;
              break;
            case 6:
              $tasa_iva_retenido = $r1['tasa'];
              $monto_iva_retenido = ($importe * ($r1['tasa'] / 100));
              $impuestos_importe -= $monto_iva_retenido;
              break;
            case 7:
              $tasa_isr_retenido = $r1['tasa'];
              $monto_isr_retenido = ($importe * ($r1['tasa'] / 100));
              $impuestos_importe -= $monto_isr_retenido;
              break;
            case 8:
              $tasa_isn_local = $r1['tasa'];
              $monto_isn_local = ($importe * ($r1['tasa'] / 100));
              $impuestos_importe += $monto_isn_local;
              break;
            case 9:
              $tasa_cedular = $r1['tasa'];
              $monto_cedular = ($importe * ($r1['tasa'] / 100));
              $impuestos_importe += $monto_cedular;
              break;
            case 10:
              $tasa_al_millar = $r1['tasa'];
              $monto_al_millar = ($importe * ($r1['tasa'] / 100));
              $impuestos_importe += $monto_al_millar;
              break;
            case 11:
              $tasa_funcion_publica = $r1['tasa'];
              $monto_funcion_publica = ($importe * ($r1['tasa'] / 100));
              $impuestos_importe += $monto_funcion_publica;
              break;
            case 12:
              $tasa_ieps_retenido = $r1['tasa'];
              $monto_ieps_retenido = ($importe * ($r1['tasa'] / 100));
              $impuestos_importe += $monto_ieps_retenido;
              break;
            case 13:
              $tasa_ieps_exento = $r1['tasa'];
              $monto_ieps_exento = ($importe * ($r1['tasa'] / 100));
              $impuestos_importe += $monto_ieps_exento;
              break;
            case 14:
              $monto_isr_monto_fijo = ($importe * ($r1['tasa'] / 100));
              $impuestos_importe += $monto_isr_monto_fijo;
              break;
            case 15:
              $tasa_isr = $r1['tasa'];
              $monto_isr = ($importe * ($r1['tasa'] / 100));
              $impuestos_importe += $monto_isr;
              break;
            case 16:
              $tasa_isr_exento = $r1['tasa'];
              $monto_isr_exento = ($importe * ($r1['tasa'] / 100));
              $impuestos_importe += $monto_isr_exento;
              break;
            case 17:
              $isr_retenido_monto_fijo = ($importe * ($r1['tasa'] / 100));
              $impuestos_importe += $isr_retenido_monto_fijo;
              break;
            case 18:
              $ieps_retenido_monto_fijo = ($importe * ($r1['tasa'] / 100));
              $impuestos_importe += $ieps_retenido_monto_fijo;
              break;
          }
        }
        $totalDoc = $importe + $impuestos_importe;
      }

      $query2 = sprintf("update datos_producto_facturacion_temp set 
                            cantidad = :cantidad, 
                            cantidad_facturada = :cantidad_facturada,
                            precio_unitario = :precio_unitario,
                            total_bruto = :subtotal, 
                            iva =:iva,
                            importe_iva =:importe_iva,
                            ieps =:ieps,
                            importe_ieps =:importe_ieps,
                            ieps_monto_fijo =:ieps_monto_fijo,
                            ish =:ish,
                            importe_ish =:importe_ish,
                            iva_exento =:iva_exento,
                            importe_iva_exento =:importe_iva_exento,
                            iva_retenido =:iva_retenido,
                            importe_iva_retenido =:importe_iva_retenido,
                            isr_retenido =:isr_retenido,
                            importe_isr_retenido =:importe_isr_retenido,
                            isn_local =:isn_local,
                            importe_isn_local =:importe_isn_local,
                            cedular =:cedular,
                            importe_cedular =:importe_cedular,
                            al_millar =:al_millar,
                            importe_al_millar =:importe_al_millar,
                            funcion_publica =:funcion_publica,
                            importe_funcion_publica =:importe_funcion_publica,
                            ieps_retenido =:ieps_retenido,
                            importe_ieps_retenido =:importe_ieps_retenido,
                            isr_exento =:isr_exento,
                            importe_isr_exento =:importe_isr_exento,
                            isr_monto_fijo =:isr_monto_fijo,
                            isr =:isr,
                            importe_isr =:importe_isr,
                            ieps_exento =:ieps_exento,
                            importe_ieps_exento =:importe_ieps_exento,
                            isr_retenido_monto_fijo =:isr_retenido_monto_fijo,
                            ieps_retenido_monto_fijo =:ieps_retenido_monto_fijo,
                            total_neto =:total_neto
                          where id = :id and factura_concepto = 1 and referencia = 0 and tipo = 0");
      $stmt2 = $db->prepare($query2);
      $stmt2->bindValue(":cantidad", $cantidad_row1);
      $stmt2->bindValue(":cantidad_facturada", $cantidad_row1);
      $stmt2->bindValue(":precio_unitario", (float)$json->precio_unitario);
      $stmt2->bindValue(":subtotal", $importe);
      $stmt2->bindValue(":iva", $tasa_iva);
      $stmt2->bindValue(":importe_iva", $monto_iva);
      $stmt2->bindValue(":ieps", $tasa_ieps);
      $stmt2->bindValue(":importe_ieps", $monto_ieps);
      $stmt2->bindValue(":ieps_monto_fijo", $monto_ieps_fijo_inicial);
      $stmt2->bindValue(":ish", $tasa_ish);
      $stmt2->bindValue(":importe_ish", $monto_ish);
      $stmt2->bindValue(":iva_exento", $tasa_iva_exento);
      $stmt2->bindValue(":importe_iva_exento", $monto_iva_exento);
      $stmt2->bindValue(":iva_retenido", $tasa_iva_retenido);
      $stmt2->bindValue(":importe_iva_retenido", $monto_iva_retenido);
      $stmt2->bindValue(":isr_retenido", $tasa_isr_retenido);
      $stmt2->bindValue(":importe_isr_retenido", $monto_isr_retenido);
      $stmt2->bindValue(":isn_local", $tasa_isn_local);
      $stmt2->bindValue(":importe_isn_local", $monto_isn_local);
      $stmt2->bindValue(":cedular", $tasa_cedular);
      $stmt2->bindValue(":importe_cedular", $monto_cedular);
      $stmt2->bindValue(":al_millar", $tasa_al_millar);
      $stmt2->bindValue(":importe_al_millar", $monto_al_millar);
      $stmt2->bindValue(":funcion_publica", $tasa_funcion_publica);
      $stmt2->bindValue(":importe_funcion_publica", $monto_funcion_publica);
      $stmt2->bindValue(":ieps_retenido", $tasa_ieps_retenido);
      $stmt2->bindValue(":importe_ieps_retenido", $monto_ieps_retenido);
      $stmt2->bindValue(":isr_exento", $tasa_isr_exento);
      $stmt2->bindValue(":importe_isr_exento", $monto_isr_exento);
      $stmt2->bindValue(":isr_monto_fijo", $isr_monto_fijo);
      $stmt2->bindValue(":isr", $tasa_isr);
      $stmt2->bindValue(":importe_isr", $monto_isr);
      $stmt2->bindValue(":ieps_exento", $tasa_ieps_exento);
      $stmt2->bindValue(":importe_ieps_exento", $monto_ieps_exento);
      $stmt2->bindValue(":isr_retenido_monto_fijo", $isr_retenido_monto_fijo);
      $stmt2->bindValue(":ieps_retenido_monto_fijo", $ieps_retenido_monto_fijo);
      $stmt2->bindValue(":total_neto", $totalDoc);
      $stmt2->bindValue(":id", $id_row);
      $stmt2->execute();
    }

    $lastIdInsert = $id_insert !== "" && $id_insert !== null ? $id_insert : $id_row;

    $query3 = sprintf("select
                          dpft.id id_row,
                          dpft.referencia,
                          pr.PKProducto id, 
                          pr.ClaveInterna clave,
                          pr.Nombre nombre,
                          dpft.unidad_medida_id id_unidad_medida,
                          csu.Descripcion unidad_medida,
                          dpft.clave_sat_id sat_id,
                          dpft.descuento_tasa,
                          dpft.importe_descuento_tasa,
                          dpft.descuento_monto_fijo,
                          dpft.cantidad cantidad_total,
                          dpft.cantidad_facturada cantidad_facturada,
                          dpft.precio_unitario,
                          dpft.total_bruto,
                          dpft.iva,
                          dpft.importe_iva,
                          dpft.ieps,
                          dpft.importe_ieps,
                          dpft.ieps_monto_fijo,
                          dpft.ish,
                          dpft.importe_ish,
                          dpft.iva_exento,
                          dpft.importe_iva_exento,
                          dpft.iva_retenido,
                          dpft.importe_iva_retenido,
                          dpft.isr_retenido,
                          dpft.importe_isr_retenido,
                          dpft.isn_local,
                          dpft.importe_isn_local,
                          dpft.cedular,
                          dpft.importe_cedular,
                          dpft.al_millar,
                          dpft.importe_al_millar,
                          dpft.funcion_publica,
                          dpft.importe_funcion_publica,
                          dpft.ieps_retenido,
                          dpft.importe_ieps_retenido,
                          dpft.isr_exento,
                          dpft.importe_isr_exento,
                          dpft.isr_monto_fijo,
                          dpft.isr,
                          dpft.importe_isr,
                          dpft.ieps_exento,
                          dpft.importe_ieps_exento,
                          dpft.isr_retenido_monto_fijo,
                          dpft.ieps_retenido_monto_fijo,
                          dpft.total_neto
                        from datos_producto_facturacion_temp dpft
                          inner join productos pr on dpft.producto_id = pr.PKProducto
                          left join claves_sat_unidades csu on dpft.unidad_medida_id = csu.PKClaveSATUnidad
                        where dpft.usuario_id = :usuario_id and dpft.factura_concepto = 1 and id = :id");
    $stmt3 = $db->prepare($query3);
    $stmt3->bindValue(":usuario_id", $_SESSION['PKUsuario']);
    $stmt3->bindValue(":id", $lastIdInsert);

    $stmt3->execute();

    $detalleProducto = $stmt3->fetchAll();

    $alertaSat = "";

    $r = $detalleProducto[0];

    $impuestos = "";
    $claveInterna = ($r['clave'] !== "" && $r['clave'] !== null) ? $r['clave'] : "S/C";

    $alertaSat = ($r['sat_id'] !== null && $r['sat_id'] !== "" && $r['sat_id'] !== 1) ? "" : '<img id="satAlert" src="../../img/icons/alerta.svg" style="width: 25px" data-toggle="tooltip" data-placement="top" title="No se asignó una clave SAT">';

    //$sat = (count($arrSat) > 0 && $arrSat['clave_sat'] !== null && $arrSat['clave_sat'] !== "") ? $claveInterna : $alertaSat . "  " .$claveInterna;
    $idUnidadMedida = ($r['id_unidad_medida'] !== "" && $r['id_unidad_medida'] !== null) ? $r['id_unidad_medida'] : "";
    $unidadMedida = ($r['unidad_medida'] !== "" && $r['unidad_medida'] !== null) ? $r['unidad_medida'] : "N/A";
    $cantidad = $r['cantidad_facturada'];

    if ($r['iva'] !== "" && $r['iva'] !== null) {
      $impuestos .= "IVA " . $r['iva'] . "%: " . number_format($r['importe_iva'], 2) . "<br>";
    }
    if ($r['ieps'] !== "" && $r['ieps'] !== null) {
      $impuestos .= "IEPS " . $r['ieps'] . "%: " . number_format($r['importe_ieps'], 2) . "<br>";
    }
    if ($r['ieps_monto_fijo'] !== "" && $r['ieps_monto_fijo'] !== null) {
      $impuestos .= "IEPS (Monto fijo) $ " . number_format($monto_ieps_fijo_inicial, 2) . ": $ " . number_format($monto_ieps_fijo, 2) . "<br>";
    }
    if ($r['ish'] !== "" && $r['ish'] !== null) {
      $impuestos .= "ISH " . $r['ish'] . "%: " . number_format($r['importe_ish'], 2) . "<br>";
    }
    if ($r['iva_exento'] !== "" && $r['iva_exento'] !== null) {
      $impuestos .= "IVA Exento " . $r['iva_exento'] . "%: " . number_format($r['importe_iva_exento'], 2) . "<br>";
    }
    if ($r['iva_retenido'] !== "" && $r['iva_retenido'] !== null) {
      $impuestos .= "IVA Retenido " . " " . $r['iva_retenido'] . "%: " . number_format($r['importe_iva_retenido'], 2) . "<br>";
    }
    if ($r['isr_retenido'] !== "" && $r['isr_retenido'] !== null) {
      $impuestos .= "ISR Retenido" . $r['isr'] . "%: " . number_format($r['importe_isr_retenido'], 2) . "<br>";
    }
    if ($r['isn_local'] !== "" && $r['isn_local'] !== null) {
      $impuestos .= "ISN (Local) " . $r['isn_local'] . "%: " . number_format($r['importe_isn_local'], 2) . "<br>";
    }
    if ($r['cedular'] !== "" && $r['cedular'] !== null) {
      $impuestos .= "Cedular " . $r['cedular'] . "%: " . number_format($r['importe_cedular'], 2) . "<br>";
    }
    if ($r['al_millar'] !== "" && $r['al_millar'] !== null) {
      $impuestos .= "5 al millar (Local) " . $r['al_millar'] . "%: " . number_format($r['importe_al_millar'], 2) . "<br>";
    }
    if ($r['funcion_publica'] !== "" && $r['funcion_publica'] !== null) {
      $impuestos .= "Función Pública " . $r['funcion_publica'] . "%: " . number_format($r['importe_funcion_publica'], 2) . "<br>";
    }
    if ($r['ieps_retenido'] !== "" && $r['ieps_retenido'] !== null) {
      $impuestos .= "IEPS Retenido " . $r['ieps_retenido'] . "%: " . number_format($r['importe_ieps_retenido'], 2) . "<br>";
    }
    if ($r['isr_exento'] !== "" && $r['isr_exento'] !== null) {
      $impuestos .= "ISR Exento " . $r['isr_exento'] . "%: " . number_format($r['importe_isr_exento'], 2) . "<br>";
    }
    if ($r['isr_monto_fijo'] !== "" && $r['isr_monto_fijo'] !== null) {
      $impuestos .= "ISR (Monto fijo) : " . number_format($r['isr_monto_fijo'], 2) . "<br>";
    }
    if ($r['isr'] !== "" && $r['isr'] !== null) {
      $impuestos .= "ISR " . $r['isr'] . "%: " . number_format($r['importe_isr'], 2) . "<br>";
    }
    if ($r['ieps_exento'] !== "" && $r['ieps_exento'] !== null) {
      $impuestos .= "ISR Exento" . $r['ieps_exento'] . "%: " . number_format($r['importe_ieps_exento'], 2) . "<br>";
    }
    if ($r['isr_retenido_monto_fijo'] !== "" && $r['isr_retenido_monto_fijo'] !== null) {
      $impuestos .= "ISR Retenido (Monto fijo) : " . number_format($r['isr_retenido_monto_fijo'], 2) . "<br>";
    }
    if ($r['ieps_retenido_monto_fijo'] !== "" && $r['ieps_retenido_monto_fijo'] !== null) {
      $impuestos .= "IEPS Retenido (Monto fijo) : " . number_format($r['ieps_retenido_monto_fijo'], 2) . "<br>";
    }
    if ($impuestos === "") {
      $impuestos = "Sin impuestos";
    } else {
      $impuestos = substr($impuestos, 0, strlen($impuestos) - 4);
    }

    if ($r['descuento_tasa'] !== null && $r['descuento_tasa'] !== "" && $r['descuento_tasa'] !== 0) {
      $descuento = "Descuento " . $r['descuento_tasa'] . "%: " . $r['importe_descuento_tasa'];
    } else if ($r['descuento_monto_fijo'] !== null && $r['descuento_monto_fijo'] !== "" && $r['descuento_monto_fijo'] !== 0) {
      $descuento = "Descuento: " . $r['importe_descuento_tasa'];
    } else {
      $descuento = "Sin descuento";
    }

    $edit = "<a class='edit' id='edit" . $r['id'] . "' data-id='" . $r['id'] . "' data-ref='" . $r['id_row'] . "' href='#' ><img src='../../img/icons/editar.svg' width='22px' data-toggle='tooltip' data-placement='right' title='Editar'>";
    $delete = "<a class='delete' id='delete" . $r['id'] . "' data-id='" . $r['id'] . "' data-ref='" . $r['id_row'] . "' href='#' style='margin-left:5px'><img src='../../img/inventarios/delete.svg' width='22px' data-toggle='tooltip' data-placement='right' title='Eliminar'>";

    array_push($table, array(
      "id" => $r['id'],
      "edit" => $edit . $delete,
      "clave" => $claveInterna,
      "descripcion" => str_replace('"', '\"', $r['nombre']),
      "sat_id" => $r['sat_id'],
      "id_unidad_medida" => $idUnidadMedida,
      "unidad_medida" => $unidadMedida,
      "cantidad" => $cantidad,
      "precio" => number_format($r['precio_unitario'], 2),
      "subtotal" => number_format(($r['cantidad_facturada'] * $r['precio_unitario']), 2),
      "impuestos" => $impuestos,
      "descuento" => $descuento,
      "importe_total" => number_format($r['total_neto'], 2),
      "alerta" => $alertaSat
    ));

    return $table;
  }

  function newCreateInvoice($key,$data)
  {
    $ruta_api = "../../../";
    require_once $ruta_api . "include/functions_api_facturation.php";
    require_once $ruta_api . 'vendor/facturapi/facturapi-php/src/Facturapi.php';
    $api = new API();

    return $api->createInvoice($key, $data);

  }

  function saveFacturacion($array,$mensaje,$mp,$value,$cliente,$data1)
  {
    $con = new conectar();
    $db = $con->getDb();

    if(is_array($array['idDocumento'])){
      
      $ref = implode(",",$array['idDocumento']);
    } else {
     
      $ref = $array['idDocumento'];
    }

    $prefactura = $array['prefactura'] !== null && $array['prefactura'] !== "" ? $array['prefactura'] : 0;

    $serie = $mensaje['series'] !== "PRF" ? $mensaje['series'] : "";
    $folio = $mensaje['series'] !== "PRF" ? $mensaje['folio_number'] : 0;
    $folio_prefactura = $mensaje['series'] === "PRF" ? $mensaje['folio_number_preinvoice'] : null;
    $nota_cliente = isset($data1) ? $data1 : 'NULL';
    
    $query = sprintf("insert into facturacion (
                      id_api,
                      serie,
                      folio,
                      folio_prefactura,
                      referencia,
                      tipo,
                      uuid,
                      fecha_timbrado,
                      cliente_id,
                      usuario_timbro_id,
                      total_facturado,
                      estatus,
                      empresa_id,
                      version_factura,
                      forma_pago_id,
                      metodo_pago,
                      tipo_factura,
                      uso_cfdi_id,
                      moneda_id,
                      prefactura,
                      saldo_insoluto,
                      nota_cliente
                    ) values (
                      :id_api,
                      :serie,
                      :folio,
                      :folio_prefactura,
                      :referencia,
                      :tipo,
                      :uuid,
                      :fecha_timbrado,
                      :cliente_id,
                      :usuario_timbro_id,
                      :total_facturado,
                      :estatus,
                      :empresa_id,
                      :version_factura,
                      :forma_pago_id,
                      :metodo_pago,
                      :tipo_factura,
                      :uso_cfdi_id,
                      :moneda_id,
                      :prefactura,
                      :saldo_insoluto,
                      :nota_cliente
                    )");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":id_api", $mensaje['id']);
    $stmt->bindValue(":serie", $serie);
    $stmt->bindValue(":folio", $folio);
    $stmt->bindValue(":folio_prefactura", $folio_prefactura);
    $stmt->bindValue(":referencia", $ref);
    $stmt->bindValue(":tipo", $array['tipoDocumento']);
    $stmt->bindValue(":uuid", $mensaje['uuid']);
    $stmt->bindValue(":fecha_timbrado", date('Y-m-d H:i:s'));
    $stmt->bindValue(":cliente_id", $cliente[0]['id']);
    $stmt->bindValue(":usuario_timbro_id", $_SESSION['PKUsuario']);
    $stmt->bindValue(":total_facturado", $mensaje['total']);
    $stmt->bindValue(":estatus", 1);
    $stmt->bindValue(":empresa_id", $_SESSION['IDEmpresa']);
    $stmt->bindValue(":version_factura", "3.3");
    $stmt->bindValue(":forma_pago_id", $array['formaPago']);
    $stmt->bindValue(":metodo_pago", $mp);
    $stmt->bindValue(":tipo_factura", $value);
    $stmt->bindValue(":uso_cfdi_id", $array['usoCfdi']);
    $stmt->bindValue(":moneda_id", $array['moneda']);
    $stmt->bindValue(":prefactura", $prefactura);
    $stmt->bindValue(":saldo_insoluto", $mensaje['total']);    
    $stmt->bindValue(":nota_cliente",$nota_cliente);              
    $stmt->execute();

    return $db->lastInsertId();
  }

  function saveDetailInvoice($array,$total,$id_factura)
  {
    $con = new conectar();
    $db = $con->getDb();
    
    $importe_descuento_total = 0;
    $save_query = "";
    
    for($i = 0; $i < count($array); $i++){
      $subtotal = $array[$i]['cantidad'] * $array[$i]['price'];

      for ($j = 0; $j < count($array); $j++) {
        if ($array[$j]['importe_descuento_tasa'] !== "" && $array[$j]['importe_descuento_tasa'] !== null) {
          $importe_descuento_total = $array[$j]['importe_descuento_tasa'];
        }
        if ($array[$j]['descuento_monto_fijo'] !== "" && $array[$j]['descuento_monto_fijo'] !== null) {
          $importe_descuento_total = $array[$j]['descuento_monto_fijo'];
        }
      }
      $predial = $array[$i]['numero_predial'] !== '' && $array[$i]['numero_predial'] !== null ? $array[$i]['numero_predial'] : 'null';
      $iva = $array[$i]['iva'] !== '' && $array[$i]['iva'] !== null ? $array[$i]['iva'] : 'null';
      $importe_iva = $array[$i]['importe_iva'] !== '' && $array[$i]['importe_iva'] !== null ? $array[$i]['importe_iva'] : 'null';
      $ieps = $array[$i]['ieps'] !== '' && $array[$i]['ieps'] !== null ? $array[$i]['ieps'] : 'null';
      $importe_ieps = $array[$i]['importe_ieps'] !== '' && $array[$i]['importe_ieps'] !== null ? $array[$i]['importe_ieps'] : 'null';
      $ieps_monto_fijo = $array[$i]['ieps_monto_fijo'] !== '' && $array[$i]['ieps_monto_fijo'] !== null ? $array[$i]['ieps_monto_fijo'] : 'null';
      $ish = $array[$i]['ish'] !== '' && $array[$i]['ish'] !== null ? $array[$i]['ish'] : 'null';
      $importe_ish = $array[$i]['importe_ish'] !== '' && $array[$i]['importe_ish'] !== null ? $array[$i]['importe_ish'] : 'null';
      $iva_exento = $array[$i]['iva_exento'] !== '' && $array[$i]['iva_exento'] !== null ? $array[$i]['iva_exento'] : 'null';
      $importe_iva_exento = $array[$i]['importe_iva_exento'] !== '' && $array[$i]['importe_iva_exento'] !== null ? $array[$i]['importe_iva_exento'] : 'null';
      $iva_retenido = $array[$i]['iva_retenido'] !== '' && $array[$i]['iva_retenido'] !== null ? $array[$i]['iva_retenido'] : 'null';
      $importe_iva_retenido = $array[$i]['importe_iva_retenido'] !== '' && $array[$i]['importe_iva_retenido'] !== null ? $array[$i]['importe_iva_retenido'] : 'null';
      $isr_retenido = $array[$i]['isr_retenido'] !== '' && $array[$i]['isr_retenido'] !== null ? $array[$i]['isr_retenido'] : 'null';
      $importe_isr_retenido = $array[$i]['importe_isr_retenido'] !== '' && $array[$i]['importe_isr_retenido'] !== null ? $array[$i]['importe_isr_retenido'] : 'null';
      $isn_local = $array[$i]['isn_local'] !== '' && $array[$i]['isn_local'] !== null ? $array[$i]['isn_local'] : 'null';
      $importe_isn_local = $array[$i]['importe_isn_local'] !== '' && $array[$i]['importe_isn_local'] !== null ? $array[$i]['importe_isn_local'] : 'null';
      $cedular = $array[$i]['cedular'] !== '' && $array[$i]['cedular'] !== null ? $array[$i]['cedular'] : 'null';
      $importe_cedular = $array[$i]['importe_cedular'] !== '' && $array[$i]['importe_cedular'] !== null ? $array[$i]['importe_cedular'] : 'null';
      $al_millar = $array[$i]['al_millar'] !== '' && $array[$i]['al_millar'] !== null ? $array[$i]['al_millar'] : 'null';
      $importe_al_millar = $array[$i]['importe_al_millar'] !== '' && $array[$i]['importe_al_millar'] !== null ? $array[$i]['importe_al_millar'] : 'null';
      $funcion_publica = $array[$i]['funcion_publica'] !== '' && $array[$i]['funcion_publica'] !== null ? $array[$i]['funcion_publica'] : 'null';
      $importe_funcion_publica = $array[$i]['importe_funcion_publica'] !== '' && $array[$i]['importe_funcion_publica'] !== null ? $array[$i]['importe_funcion_publica'] : 'null';
      $ieps_retenido = $array[$i]['ieps_retenido'] !== '' && $array[$i]['ieps_retenido'] !== null ? $array[$i]['ieps_retenido'] : 'null';
      $importe_ieps_retenido = $array[$i]['importe_ieps_retenido'] !== '' && $array[$i]['importe_ieps_retenido'] !== null ? $array[$i]['importe_ieps_retenido'] : 'null';
      $isr_exento = $array[$i]['isr_exento'] !== '' && $array[$i]['isr_exento'] !== null ? $array[$i]['isr_exento'] : 'null';
      $importe_isr_exento = $array[$i]['importe_isr_exento'] !== '' && $array[$i]['importe_isr_exento'] !== null ? $array[$i]['importe_isr_exento'] : 'null';
      $isr_monto_fijo = $array[$i]['isr_monto_fijo'] !== '' && $array[$i]['isr_monto_fijo'] !== null ? $array[$i]['isr_monto_fijo'] : 'null';
      $isr = $array[$i]['isr'] !== '' && $array[$i]['isr'] !== null ? $array[$i]['isr'] : 'null';
      $importe_isr = $array[$i]['importe_isr'] !== '' && $array[$i]['importe_isr'] !== null ? $array[$i]['importe_isr'] : 'null';
      $ieps_exento = $array[$i]['ieps_exento'] !== '' && $array[$i]['ieps_exento'] !== null ? $array[$i]['ieps_exento'] : 'null';
      $importe_ieps_exento = $array[$i]['importe_ieps_exento'] !== '' && $array[$i]['importe_ieps_exento'] !== null ? $array[$i]['importe_ieps_exento'] : 'null';
      $isr_retenido_monto_fijo = $array[$i]['isr_retenido_monto_fijo'] !== '' && $array[$i]['isr_retenido_monto_fijo'] !== null ? $array[$i]['isr_retenido_monto_fijo'] : 'null';
      $ieps_retenido_monto_fijo = $array[$i]['ieps_retenido_monto_fijo'] !== '' && $array[$i]['ieps_retenido_monto_fijo'] !== null ? $array[$i]['ieps_retenido_monto_fijo'] : 'null';
      $lote = $array[$i]['numero_lote'] !== '' && $array[$i]['numero_lote'] !== null ? $array[$i]['numero_lote'] : 'null';
      $caducidad = $array[$i]['caducidad'] !== '' && $array[$i]['caducidad'] !== null ? $array[$i]['caducidad'] : '0000-00-00';
      $serie = $array[$i]['numero_serie'] !== '' && $array[$i]['numero_serie'] !== null ? $array[$i]['numero_serie'] : 'null';

      $save_query .= "(" . 
                      $array[$i]['cantidad'] . "," .
                      $array[$i]['price'] . "," .
                      $subtotal . "," .
                      $importe_descuento_total . "," .
                      $array[$i]['unidad_medida_id'] . "," .
                      $array[$i]['clave_sat_id'] . "," .
                      $array[$i]['producto_id'] . "," .
                      $iva . "," .
                      $importe_iva . "," .
                      $ieps . "," .
                      $importe_ieps . "," .
                      $ieps_monto_fijo . "," .
                      $ish . "," .
                      $importe_ish . "," .
                      $iva_exento . "," .
                      $importe_iva_exento . "," .
                      $iva_retenido . "," .
                      $importe_iva_retenido . "," .
                      $isr_retenido . "," .
                      $importe_isr_retenido . "," .
                      $isn_local . "," .
                      $importe_isn_local . "," .
                      $cedular . "," .
                      $importe_cedular . "," .
                      $al_millar . "," .
                      $importe_al_millar . "," .
                      $funcion_publica . "," .
                      $importe_funcion_publica . "," .
                      $ieps_retenido . "," .
                      $importe_ieps_retenido . "," .
                      $isr_exento . "," .
                      $importe_isr_exento . "," .
                      $isr_monto_fijo . "," .
                      $isr . "," .
                      $importe_isr . "," .
                      $ieps_exento . "," .
                      $importe_ieps_exento . "," .
                      $isr_retenido_monto_fijo . "," .
                      $ieps_retenido_monto_fijo . "," .
                      $total . "," .
                      $id_factura . "," .
                      $predial . "," .
                      "'" . $lote . "'," .
                      "'" . $caducidad . "'," .
                      "'" . $serie . "'" .
                      "),";
    }

    $save_query = substr($save_query, 0, strlen($save_query) - 1);

    $query = sprintf("insert into detalle_facturacion (
                    cantidad,
                    precio,
                    subtotal,
                    importe_descuento,
                    unidad_medida_id,
                    clave_prod_serv_id,
                    producto_id,
                    iva,
                    importe_iva,
                    ieps,
                    importe_ieps,
                    ieps_monto_fijo,
                    ish,
                    importe_ish,
                    iva_exento,
                    importe_iva_exento,
                    iva_retenido,
                    importe_iva_retenido,
                    isr_retenido,
                    importe_isr_retenido,
                    isn_local,
                    importe_isn_local,
                    cedular,
                    importe_cedular,
                    al_millar,
                    importe_al_millar,
                    funcion_publica,
                    importe_funcion_publica,
                    ieps_retenido,
                    importe_ieps_retenido,
                    isr_exento,
                    importe_isr_exento,
                    isr_monto_fijo,
                    isr,
                    importe_isr,
                    ieps_exento,
                    importe_ieps_exento,
                    isr_retenido_monto_fijo,
                    ieps_retenido_monto_fijo,
                    importe_total,
                    factura_id,
                    numero_predial,
                    numero_lote,
                    caducidad,
                    numero_serie
                    ) values " . $save_query);
    
    $stmt = $db->prepare($query);
    
    return $stmt->execute();
  }

  function saveFactura($data, $data1, $value)
  {
    $ban = 0;
    $array = json_decode($data, true);
    $response = [];

    if (
      $array['usoCfdi'] !== "" && $array['usoCfdi'] !== null && $array['usoCfdi'] !== "null" &&
      $array['formaPago'] !== "" && $array['formaPago'] !== null && $array['formaPago'] !== "null" &&
      $array['metodoPago'] !== "" && $array['metodoPago'] !== null && $array['metodoPago'] !== "null" &&
      $array['moneda'] !== "" && $array['moneda'] !== null && $array['moneda'] !== "null"
    ) {
      $ban = 1;
    } else {
      $ban = 0;
      $response = [
        "status" => 1,
        "message" => "Uso CFDI, Forma de pago, Metodo de pago y la moneda, son datos obligatorios"
      ];
    }

    if ($ban > 0) {

      $con = new conectar();
      $db = $con->getDb();

      $send_data = new send_data();
      $getData = new get_data();
	    $save_data = new save_data();
      $update_data = new edit_data();
      $email_vendedor = "";
      $referencia = "";
	    $id_factura = "";
      try {
        $db->beginTransaction();

        if((int)$array['tipoDocumento'] !== 3){
          if(is_array($array['idDocumento'])){
            $ref1 = implode(",",$array['idDocumento']);
          } else {
            $ref1 = $array['idDocumento'];
          }
        } else {
          $ref1 = $array['idDocumento'];
        }
       
        $query = sprintf("select key_company_api key_company,key_user_company_api key_user from empresas where PKEmpresa = :id");
        $stmt = $db->prepare($query);
        $stmt->bindValue(":id", $_SESSION['IDEmpresa']);
        $stmt->execute();

        $key_company_api = $stmt->fetchAll();

        $id_cliente = $array['cliente'] !== "" && $array['cliente'] !== null ? $array['cliente'] : "";

        $cliente = $getData->getClientInvoice($array['tipoDocumento'],$ref1,$id_cliente);
        $razon_socialPG = isset($array['razon_social']) ? $array['razon_social'] : "";
        $invoice = $getData->getFormatInvoice($array,$razon_socialPG,$data1);
        
        $productos = $getData->getProductsInvoiceTemp($array['tipoDocumento'],$ref1);
        $factura_concepto = $productos[0]['factura_concepto'];
        $dias_credito = "";
        if(isset($cliente[0]['dias_credito'])){
          $dias_credito = $cliente[0]['dias_credito'];
        }
        
        
        $mp = $array['metodoPago'] === "PUE" ? 1 : 3;
        
        $prefactura_switch = (int)$array['prefactura'];
   
        if($prefactura_switch === 0){
          
          $mensaje = $save_data->newCreateInvoice($key_company_api[0]['key_company'], $invoice);
          $folio_serie = $getData->getFolioSerie();
          
          if (isset($mensaje->id)) {
        	  $aux1 = [
              "id"=>$mensaje->id,
              "series"=>$folio_serie['serie'],
              "folio_number"=>$folio_serie['folio'],
              "uuid"=>$mensaje->uuid,
              "total"=>$mensaje->total
            ];
            
            if ($mensaje->id !== "" && $mensaje->id !== null) {
          	    if(empty($array['id_prefactura'])){
                  // $save_data->saveDataInventoryProduct($value,$factura_id,$sucursal,$cliente)
         	        $id_factura = $save_data->saveFacturacion($array,$aux1,$mp,$value,$cliente,$data1);

                    if ($id_factura !== null && $id_factura!== "") {
			  	
                        $total = $mensaje->total;
                        $save_data->saveDetailInvoice($productos,$total,$id_factura,);
                        
                        $getData->getNotifications($array,$productos,$id_factura);
                        $save_data->savePaids($array,$mensaje,$id_factura);
                        $save_data->saveDateExpiration($array,$id_factura,$dias_credito);
                        $email_vendedor = $save_data->saveSalesman($array,$id_factura);
                        
                        if(isset($array['afectar_inventario']) && isset($array['sucursal'])){
                            $sucursal = $array['sucursal'];
                            $save_data->saveDataInventoryProduct($productos,$id_factura,$sucursal,$cliente[0]['id'],(int)$array['tipoDocumento'],(int)$array['afectar_inventario'],$ref1);
                          
                        }
                        $ban1 = $update_data->updateStatusDocuments($array,$productos,$id_factura);

                        if((int)$array['tipoDocumento'] === 2){
                          $relationTicketSale = (int)$getData->getCountRelationSalesByTicket($ref1);
                          if($relationTicketSale > 0){
                            $update_data->updateStatusTicketBySale($ref1);
                          }
                          
                        }

                        if ($ban1 === true) {

                            $response = [
                                "status" => 0,
                                "message" => "Se ha guardado con exito!",
                                "id_api" =>$mensaje->id,
                                "id_factura"=>$id_factura
                            ];
                            $getData->getTruncateTableProducts($factura_concepto,$array['tipoDocumento'],$ref1);
                        } else {
                            $response = [
                            "status" => 1,
                            "message" => "No se pudo guardar la factura! "
                            ];
                        }
                    }
                } else {
              	
                $id_factura = $array['id_prefactura'];
                
                $ban1 = $update_data->updateInvoiceCfdi($aux1,$id_factura);
                $update_data->updateStatusDocuments($array,$productos,$id_factura);
                //$getData->getNotifications($array,$productos,$id_factura);
                $save_data->savePaids($array,$mensaje,$id_factura);
                $save_data->saveDateExpiration($array,$id_factura,$dias_credito);
                $email_vendedor = $save_data->saveSalesman($array,$id_factura);

                
                if ($ban1 === true) {
                  
                  $response = [
                    "status" => 0,
                    "id_invoice" => $id_factura,
                    "message" => "Se ha guardado con exito!"
                  ];
                  $getData->getTruncateTableProducts($factura_concepto,$array['tipoDocumento'],$ref1);
                } else {
                  $response = [
                    "status" => 1,
                    "message" => "No se pudo guardar la factura! "
                  ];
                }
              }
            } else {
              $response = [
                "status" => 1,
                "message" => $mensaje->message
              ];
            }
          } else {
            $response = [
              "status" => 1,
              "message" => $mensaje->message
            ];
          }
        } else if($prefactura_switch === 1){
          $total = $getData->getTotalPrefactura($array);
            $aux1 = [
              "id"=>"null",
              "series"=>"PRF",
              "folio_number_preinvoice"=>$getData->getFolioSeriePrefectura(),
              "uuid"=>"null",
              "total"=>$total
            ];
            
          $id_factura = $save_data->saveFacturacion($array,$aux1,$mp,$value,$cliente,$data1);
          $ban1 = $save_data->saveDetailInvoice($productos,$total,$id_factura);
          $update_data->updateStatusDocuments($array,$productos,$id_factura);
          $email_vendedor = $save_data->saveSalesman($array,$id_factura);
        
          if($ban1){
            
              $response = [
                "status" => 0,
                "id_invoice" => $id_factura,
                "message" => "Se ha guardado con exito!"
              ];
              $getData->getTruncateTableProducts($factura_concepto,$array['tipoDocumento'],$ref1);
          }
          
        }
    
        $db->commit();
        //$getData->getTruncateTableProducts($factura_concepto,$array['tipoDocumento'],$ref1);

        if($id_factura !== null && $id_factura !== ""){
          $correos = ($email_vendedor !== "") ? [$cliente[0]['email'], $_SESSION['Usuario'], $email_vendedor] : [$cliente[0]['email'], $_SESSION['Usuario']];
          $send_data->sendEmail($id_factura, $correos);
          sendAutoEmails($cliente[0]['id'], $id_factura);
        }
    
      } catch (PDOException $e) {
        $response = [
          "status" => 1,
          "message" => "¡No se pudo guardar la factura! " . $e->getMessage()
        ];
        //$db->rollBack();
      }
    }
    return $response;
  }

  function savePedido($factura_id,$sucursal,$cliente)
  {
    $con = new conectar();
    $db = $con->getDb();
    $get_data = new get_data();
    $folio = $get_data->getFolioOrder();
    $id = null;
    try {
        $db->beginTransaction();
    
        $query = sprintf("
            insert into orden_pedido_por_sucursales 
                (
                    id_orden_pedido_empresa,
                    tipo_pedido,
                    fecha_captura,
                    fecha_modificacion,
                    factura_id,
                    usuario_creo_id,
                    usuario_edito_id,
                    sucursal_origen_id,
                    cliente_id,
                    empresa_id,
                    estatus_orden_pedido_id,
                    estatus_factura_id,
                    estatus_factura_id_old,
                    estatus_orden_pedido_id_old
                ) values (
                    :id_orden_pedido_empresa,
                    :tipo_pedido,
                    now(),
                    now(),
                    :factura_id,
                    :usuario_creo_id,
                    :usuario_edito_id,
                    :sucursal_origen_id,
                    :cliente_id,
                    :empresa_id,
                    :estatus_orden_pedido_id,
                    :estatus_factura_id,
                    :estatus_factura_id_old,
                    :estatus_orden_pedido_id_old

                )
        ");
        $stmt = $db->prepare($query);
        $stmt->bindValue(":id_orden_pedido_empresa",$folio);
        $stmt->bindValue(":tipo_pedido",5);
        $stmt->bindValue(":factura_id",$factura_id);
        $stmt->bindValue(":usuario_creo_id",$_SESSION['PKUsuario']);
        $stmt->bindValue(":usuario_edito_id",$_SESSION['PKUsuario']);
        $stmt->bindValue(":sucursal_origen_id",$sucursal); 
        $stmt->bindValue(":cliente_id",$cliente);
        $stmt->bindValue(":empresa_id",$_SESSION['IDEmpresa']);
        $stmt->bindValue(":estatus_orden_pedido_id",9);
        $stmt->bindValue(":estatus_factura_id",2);
        $stmt->bindValue(":estatus_factura_id_old",2);
        $stmt->bindValue(":estatus_orden_pedido_id_old",9);
        $stmt->execute();
        $id = $db->lastInsertId();
        $status = $db->commit();
        return ["estatus"=>$status,"id"=>$id,"folio"=>$folio];
    } catch (PDOException $e) {
        $db->rollback();
        return "Error: " . $e->getMessage();
    }
  }

  function saveDetailsOrder($value,$value1)
  {
    $con = new conectar();
    $db = $con->getDb();

    for($i = 0; $i < count($value); $i++){
        try{
            $db->beginTransaction();
            $query = sprintf("insert into detalle_orden_pedido_por_sucursales (producto_id,cantidad_pedida,cantidad_surtida,cantidad_entregada,orden_pedido_id) values (:producto_id,:cantidad_pedida,:cantidad_surtida,:cantidad_entregada,:orden_pedido_id)");
            $stmt = $db->prepare($query);
            $stmt->bindValue(":producto_id",$value[$i]['id']);
            $stmt->bindValue(":cantidad_pedida",$value[$i]['cantidad']);
            $stmt->bindValue(":cantidad_surtida",$value[$i]['cantidad']);
            $stmt->bindValue(":cantidad_entregada",0);
            $stmt->bindValue(":orden_pedido_id",$value1);
            $stmt->execute();
            $status = $db->commit();
            return ["estatus"=>$status];
        } catch(PDOException $e){
            $db->rollback();
            return "Error: " . $e->getMessage();
        }
    }
  }

  function saveBinnacleOrder($id)
  {
    $con = new conectar();
    $db = $con->getDb();
    $stmt = $db->prepare("INSERT INTO bitacora_orden_pedido (usuario_id, mensaje_id, orden_pedido_id, created_at, updated_at) VALUES (:fkusuario, :mensaje_id, :orden_pedido_id, now(), now())");
          $stmt->bindValue(':fkusuario', $_SESSION['PKUsuario']);
          $stmt->bindValue(':mensaje_id', 17);
          $stmt->bindValue(':orden_pedido_id', $id);
          
          $stmt->execute();
  }

  function saveBinnacleDocument($tipo,$id)
  {
    switch((int)$tipo){
        case 1:
            $con = new conectar();
            $db = $con->getDb();
            $stmt = $db->prepare("INSERT INTO bitacora_cotizaciones (FKUsuario, Fecha_Movimiento, FKMensaje, FKCotizacion) VALUES (:fkusuario, now(), :fkmensaje, :fkcotizacion)");
            $stmt->bindValue(':fkusuario', $_SESSION['PKUsuario']);
            $stmt->bindValue(':fkmensaje', 16);
            $stmt->bindValue(':fkcotizacion', $id);
            $stmt->execute();
        break;
    }
  }

  function saveSalida($clave,$lote,$cantidad,$folio,$pedido,$sucursal)
  {
    $con = new conectar();
    $db = $con->getDb();

    try {
      $db->beginTransaction();

      $query = sprintf("
          insert into inventario_salida_por_sucursales 
              (
                  clave,
                  numero_lote,
                  cantidad,
                  fecha_salida,
                  folio_salida,
                  cantidad_facturada,
                  tipo_salida,
                  orden_pedido_id,
                  usuario_creo_id,
                  sucursal_id,
                  estatus
              ) values (
                  :clave,
                  :numero_lote,
                  :cantidad,
                  now(),
                  :folio_salida,
                  :cantidad_facturada,
                  :tipo_salida,
                  :orden_pedido_id,
                  :usuario_creo_id,
                  :sucursal_id,
                  :estatus
              )
      ");
      $stmt = $db->prepare($query);
      $stmt->bindValue(":clave",$clave);
      $stmt->bindValue(":numero_lote",$lote);
      $stmt->bindValue(":cantidad",$cantidad);
      $stmt->bindValue(":folio_salida",$folio);
      $stmt->bindValue(":cantidad_facturada",$cantidad);
      $stmt->bindValue(":tipo_salida",1);
      $stmt->bindValue(":orden_pedido_id",$pedido);
      $stmt->bindValue(":usuario_creo_id",$_SESSION['PKUsuario']);
      $stmt->bindValue(":sucursal_id",$sucursal);
      $stmt->bindValue(":estatus",2);
      $stmt->execute();
      $id = $db->lastInsertId();
      $estatus = $db->commit();
      return ["estatus"=>$estatus,"id"=>$id];
    } catch(PDOException $e) {
        $db->rollback();
        return "Error: " . $e->getMessage();
    }
  }
  function saveStocksProducts($value,$value1)
  {
    $get_data = new get_data();
    $update_data = new edit_data();

    $resto = 0;
    $suma = 0;
    $arr = [];
    $arr1 = [];
    $lotes = [];
    $cont = 0;
    //foreach($value as $r){
    for($i = 0; $i < count($value); $i++){
      $arr = $get_data->getStockProductAll($value[$i]['id'],$value1);
      $arr1 = $get_data->getStockProductAll($value[$i]['id'],$value1);
      
      $cantidad = $value[$i]['cantidad'];
      //foreach($arr as $r1){
        for($j = 0; $j < count($arr); $j++){
        
        if($cantidad > 0){
          
          if($arr[$j]->existencia >= $cantidad){
            $resto += $cantidad - $resto;
            
            $arr[$j]->existencia = $arr[$j]->existencia - $resto;
            $cantidad = $cantidad - $resto;
          } else {
            $resto += $arr[$j]->existencia - $resto;
            $cantidad = $cantidad - $arr[$j]->existencia;
            $arr[$j]->existencia = $arr[$j]->existencia - $resto;
            
          }
        }
        
      }
      for ($j=0; $j < count($arr); $j++) { 
        if($arr[$j]->existencia !== $arr1[$j]->existencia)
        {
          $cantidad = $arr1[$j]->existencia - $arr[$j]->existencia;
          $lotes[] = ["clave_producto"=>$arr[$j]->clave_producto,"lote"=>$arr[$j]->numero_lote,"cantidad"=>$cantidad];
        }
        $ban = $update_data->updateStockProduct($arr[$j]->existencia,$arr[$j]->id);
      }
    }
    
    
    return ["estatus"=>$ban,"lotes"=>$lotes];
  }

  function saveNotificationCot($id,$idOrdenPedido)
  {
    $con = new conectar();
    $db = $con->getDb();
    // NOTIFICACIONES 
    $timestamp = date('Y-m-d H:i:s');

    // SELECCIONAMOS LOS USUARIOS DE ESA COTIZACION 
    $stmt = $db->prepare('SELECT FKUsuarioCreacion, FKUsuarioEdicion FROM cotizacion WHERE PKCotizacion = :cotizacion');
    $stmt->execute([':cotizacion' => $id]);
    $res = $stmt->fetch(PDO::FETCH_ASSOC);

    // INSERTAMOS LA NOTIFICACION EN LA BD 
    $queryNot = 'INSERT INTO notificaciones (tipo_notificacion, detalle_tipo_notificacion, id_elemento, created_at, usuario_creo, usuario_recibe) VALUES (:tipoNot, :detaleNot, :idElem, :fecha, :usrCreo, :usrRecibe)';
    if ($res['FKUsuarioCreacion'] === $res['FKUsuarioEdicion']) {
      $stmt = $db->prepare($queryNot);
      $stmt->execute([':tipoNot' => 4, ':detaleNot' => 6, ':idElem' => $id, ':fecha' => $timestamp, ':usrCreo' => $_SESSION['PKUsuario'], ':usrRecibe' => $res['FKUsuarioCreacion']]);
    } else {
      $stmt = $db->prepare($queryNot);
      $stmt->execute([':tipoNot' => 4, ':detaleNot' => 6, ':idElem' => $id, ':fecha' => $timestamp, ':usrCreo' => $_SESSION['PKUsuario'], ':usrRecibe' => $res['FKUsuarioCreacion']]);

      $stmt = $db->prepare($queryNot);
      $stmt->execute([':tipoNot' => 4, ':detaleNot' => 6, ':idElem' => $id, ':fecha' => $timestamp, ':usrCreo' => $_SESSION['PKUsuario'], ':usrRecibe' => $res['FKUsuarioEdicion']]);
    }

    // SELECCIONAMOS LOS USUARIOS DE TIPO ALMACEN 
    $stmt = $db->prepare('SELECT id FROM usuarios WHERE empresa_id = :empresaId AND role_id = :roleId');
    $stmt->execute([':empresaId' => $_SESSION['IDEmpresa'], ':roleId' => 6]);
    $empleados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($empleados as $empleado) {
      // INSERTAMOS LA NOTIFICACION EN LA BD 
      $stmt = $db->prepare('INSERT INTO notificaciones (tipo_notificacion, detalle_tipo_notificacion, id_elemento, created_at, usuario_recibe) VALUES (:tipoNot, :detaleNot, :idElem, :fecha, :usrRecibe)');
      $stmt->execute([':tipoNot' => 6, ':detaleNot' => 13, ':idElem' => $idOrdenPedido, ':fecha' => $timestamp, ':usrRecibe' => $empleado['id']]);
    }
  }

  public function saveNotificationSale($idelemento)
    {
        $con = new conectar();
        $db = $con->getDb();

        /* NOTIFICACIONES */
        $timestamp = date('Y-m-d H:i:s');
        /* SELECCIONAMOS LOS USUARIOS DE TIPO ALMACEN */
        $stmt = $db->prepare('SELECT id FROM usuarios WHERE empresa_id = :empresaId AND role_id = :roleId');
        $stmt->execute([':empresaId' => $_SESSION['IDEmpresa'], ':roleId' => 6]);
        $empleados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($empleados as $empleado) {
            /* INSERTAMOS LA NOTIFICACION EN LA BD */
            $stmt = $db->prepare('INSERT INTO notificaciones (tipo_notificacion, detalle_tipo_notificacion, id_elemento, created_at, usuario_recibe) VALUES (:tipoNot, :detaleNot, :idElem, :fecha, :usrRecibe)');
            $stmt->execute([':tipoNot' => 6, ':detaleNot' => 13, ':idElem' => $idelemento, ':fecha' => $timestamp, ':usrRecibe' => $empleado['id']]);
        }
    }

  function saveDataInventoryProduct($value,$factura_id,$sucursal,$cliente,$tipo,$checked,$ref)
  {
    $get_data = new get_data();
    $save_data = new save_data();
    $update_data = new edit_data();
    $get_data = new get_data();
    $stock_active = $get_data->getActiveStock($sucursal);
    
    if((int) $stock_active === 1){
        switch((int)$tipo){
            case 0:
                if((int)$checked === 1){
                    $arr = $save_data->savePedido($factura_id,$sucursal,$cliente);
                    if((int)$arr['estatus'] === 1){
                        $save_data->saveDetailsOrder($value,$arr['id']);
                        $save_data->saveBinnacleOrder($arr['id']);
                        $arr1 = $save_data->saveStocksProducts($value,$sucursal);
                        if((int)$arr1['estatus'] === 1){
                            for ($i=0; $i < count($arr1['lotes']); $i++) { 
                                $save_data->saveSalida(
                                  $arr1['lotes'][$i]['clave_producto'],
                                  $arr1['lotes'][$i]['lote'],
                                  $arr1['lotes'][$i]['cantidad'],
                                  $arr['folio'] . "-1",
                                  $arr['id'],
                                  $sucursal);
                            }
                        }
                    }
                }
                
            break;
            case 1:
                
                if((int)$checked === 1){
                    
                    $arr = $save_data->savePedido($factura_id,$sucursal,$cliente);
                    if((int)$arr['estatus'] === 1){
                        $save_data->saveDetailsOrder($value,$arr['id']);
                        $save_data->saveNotificationCot($ref,$arr['id']);
                        $arr1 = $save_data->saveStocksProducts($value,$sucursal);
                        if((int)$arr1['estatus'] === 1){
                            for ($i=0; $i < count($arr1['lotes']); $i++) { 
                                $save_data->saveSalida($arr1['lotes'][$i]['clave_producto'],$arr1['lotes'][$i]['lote'],$arr1['lotes'][$i]['cantidad'],$arr[0]->folio . "-1",$arr['id'],$sucursal);
                            }
                        }
                    }
                } else {
                    $arr = $save_data->savePedido($factura_id,$sucursal,$cliente);
                    if((int)$arr['estatus'] === 1){
                        $save_data->saveDetailsOrder($value,$arr['id']);
                        $save_data->saveNotificationCot($ref,$arr['id']);
                    }
                }
                $save_data->saveBinnacleDocument($tipo,$ref);
                $update_data->updateStatusCot($ref);
            
            break;
            case 2:
                if((int)$checked === 1){
                    $arr = $get_data->getIdOrderBySale($factura_id);
                    
                    if(count($arr) > 0){
                      if((int)$arr[0]->id !== null && (int)$arr[0]->id !== ""){
                        
                        $save_data->saveNotificationSale($arr[0]->id);
                        $arr1 = $save_data->saveStocksProducts($value,$sucursal);
                        
                        if((int)$arr1['estatus'] === 1){
                            for ($i=0; $i < count($arr1['lotes']); $i++) { 
                                $ban = $save_data->saveSalida(
                                  $arr1['lotes'][$i]['clave_producto'],
                                  $arr1['lotes'][$i]['lote'],
                                  $arr1['lotes'][$i]['cantidad'],
                                  $arr[0]->folio . "-1",
                                  $arr[0]->id,
                                  $sucursal
                                );
                                
                            }
                            
                        }
                      }
                    }
                }
            break;
        }
    }
    
  }

  function saveDataTaxes($prod, $value, $tasa, $id)
  {
    $con = new conectar();
    $db = $con->getDb();

    switch ($value) {
      case "1":
        $column = "iva";
        break;
      case "2":
        $column = "ieps";
        break;
      case "3":
        $column = "ieps_monto_fijo";
        break;
      case "4":
        $column = "ish";
        break;
      case "5":
        $column = "iva_exento";
        break;
      case "6":
        $column = "iva_retenido";
        break;
      case "7":
        $column = "isr_retenido";
        break;
      case "8":
        $column = "isn_local";
        break;
      case "9":
        $column = "cedular";
        break;
      case "10":
        $column = "al_millar";
        break;
      case "11":
        $column = "funcion_publica";
        break;
      case "12":
        $column = "ieps_retenido";
        break;
      case "13":
        $column = "isr_exento";
        break;
      case "14":
        $column = "isr_monto_fijo";
        break;
      case "15":
        $column = "isr";
        break;
      case "16":
        $column = "ieps_exento";
        break;
      case "17":
        $column = "isr_retenido_monto_fijo";
        break;
      case "18":
        $column = "ieps_retenido_monto_fijo";
        break;
    }

    $query = sprintf("select PKInfoFiscalProducto id from info_fiscal_productos inner join productos on FKProducto = PKProducto where empresa_id = :id and FKProducto = :prod");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":id", $_SESSION['IDEmpresa']);
    $stmt->bindValue(":prod", $prod);
    $stmt->execute();
    $info_fiscal = $stmt->fetchAll();

    $query = sprintf("select * from impuestos_productos where FKInfoFiscalProducto =:id and FKImpuesto=:impuesto and Tasa=:tasa");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":id", $info_fiscal[0]['id']);
    $stmt->bindValue(":impuesto", $value);
    $stmt->bindValue(":tasa", $tasa);
    $stmt->execute();
    $rowCount0 = $stmt->rowCount();

    if($rowCount0 === 0){
      $query = sprintf("insert into impuestos_productos (FKInfoFiscalProducto, FKImpuesto, Tasa) values (:id,:impuesto,:tasa)");
      $stmt = $db->prepare($query);
      $stmt->bindValue(":id", $info_fiscal[0]['id']);
      $stmt->bindValue(":impuesto", $value);
      $stmt->bindValue(":tasa", $tasa);
      $stmt->execute();
    }

    $query = sprintf("select * from impuesto_tasas where FKImpuesto = :id and Tasa = :tasa");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":id", $value);
    $stmt->bindValue(":tasa", $tasa);
    $stmt->execute();
    $rowCount = $stmt->rowCount();

    if ($rowCount === 0) {
      $query = sprintf("insert into impuesto_tasas (Tasa, FKImpuesto) values (:tasa,:id)");
      $stmt = $db->prepare($query);
      $stmt->bindValue(":id", $value);
      $stmt->bindValue(":tasa", $tasa);
      $stmt->execute();
    }

    $query1 = sprintf("update datos_producto_facturacion_temp set " . $column . " = :tasa where id = :id");
    $stmt1 = $db->prepare($query1);
    $stmt1->bindValue(":tasa", $tasa);
    $stmt1->bindValue(":id", $id);
    return $stmt1->execute();
  }

  function savePaids($array,$mensaje,$id_factura){
    $con = new conectar();
    $db = $con->getDb();
    $getData = new get_data();

    $query = sprintf("select identificador_pago from pagos where empresa_id = :id and tipo_movimiento = 0 and estatus = 1 order by idpagos desc limit 1");
      $stmt = $db->prepare($query);
      $stmt->bindValue(":id", $_SESSION['IDEmpresa']);
      $stmt->execute();

      $iden = $stmt->fetchAll();
      if (count($iden) > 0) {
        if ($iden[0]['identificador_pago'] !== null && $iden[0]['identificador_pago'] !== "") {
          $iden_aux = (int)substr($iden[0]['identificador_pago'], 2);
          $iden = "RP" . str_pad(($iden_aux + 1), 6, 0, STR_PAD_LEFT);
        } else {
          $iden = "RP000001";
        }
      } else {
        $iden = "RP000001";
      }

    if ($array['cuenta_bancaria'] !== null && $array['cuenta_bancaria'] !== "" && $array['cuenta_bancaria'] !== "null") {
      
      $query = sprintf("insert into pagos (
                            fecha_registro,
                            total,
                            tipo_movimiento,
                            identificador_pago,
                            fecha_pago,
                            estatus,
                            empresa_id,
                            forma_pago
                          ) values (
                            :fecha_registro,
                            :total,
                            :tipo_movimiento,
                            :identificador_pago,
                            :fecha_pago,
                            :estatus,
                            :empresa_id,
                            :forma_pago
                          )");
      $stmt = $db->prepare($query);

      $stmt->bindValue(":fecha_registro", date("Y-m-d H:i:s"));
      $stmt->bindValue(":total", $mensaje->total);
      $stmt->bindValue(":tipo_movimiento", 0);
      $stmt->bindValue(":identificador_pago", $iden);
      $stmt->bindValue(":fecha_pago", date("Y-m-d"));
      $stmt->bindValue(":estatus", 1);
      $stmt->bindValue(":empresa_id", $_SESSION['IDEmpresa']);
      $stmt->bindValue(":forma_pago", $array['formaPago']);

      if ($stmt->execute()) {

        $pagoLast = $db->lastInsertId();
        
        $query = sprintf("insert into movimientos_cuentas_bancarias_empresa (
                              Fecha,
                              Descripcion,
                              Deposito,
                              tipo_movimiento_id,
                              cuenta_origen_id,
                              cuenta_destino_id,
                              FKResponsable,
                              id_pago,
                              id_factura,
                              saldo_anterior,
                              saldo_insoluto,
                              estatus,
                              Saldo,
                              parcialidad,
                              Referencia,
                              tipo_CuentaCobrar,
                              id_factura_relacion_pagoVenta
                            ) values (
                              :fecha,
                              :descripcion,
                              :deposito,
                              :tipo_movimiento_id,
                              :cuenta_origen_id,
                              :cuenta_destino_id,
                              :responsable,
                              :id_pago,
                              :id_factura,
                              :saldo_anterior,
                              :saldo_insoluto,
                              :estatus,
                              :saldo,
                              :parcialidad,
                              :referencia,
                              :tipoCuentaCobrar,
                              :idFacturaRelacion
                            )");
        $stmt = $db->prepare($query);
        $stmt->bindValue(":fecha", date("Y-m-d H:i:s"));
        $stmt->bindValue(":descripcion", "Recepcion de pago");
        $stmt->bindValue(":deposito", $mensaje->total);
        $stmt->bindValue(":tipo_movimiento_id", 5);
        $stmt->bindValue(":cuenta_origen_id", 300);
        $stmt->bindValue(":cuenta_destino_id", $array['cuenta_bancaria']);
        $stmt->bindValue(":responsable", $_SESSION['PKUsuario']);
        $stmt->bindValue(":id_pago", $pagoLast);
        $stmt->bindValue(":id_factura", $id_factura);
        $stmt->bindValue(":saldo_anterior", $mensaje->total);
        $stmt->bindValue(":saldo_insoluto", 0);
        $stmt->bindValue(":estatus", 1);
        $stmt->bindValue(":saldo", 1234);
        $stmt->bindValue(":parcialidad", 1);
        $stmt->bindValue(":tipoCuentaCobrar", 2);
        $stmt->bindValue(":idFacturaRelacion", $id_factura);
        $stmt->bindValue(":referencia",$array['referencia']);

        if ($stmt->execute()) {

          $query = sprintf("select saldo_inicial from cuentas_cheques where FKCuenta = :id");
          $stmt = $db->prepare($query);
          $stmt->bindValue(":id", $array['cuenta_bancaria']);
          $stmt->execute();

          $aux = $stmt->fetchAll();

          $saldo_inicial = $aux[0]['saldo_inicial'] !== null && $aux[0]['saldo_inicial'] !== "" ? (float)$aux[0]['saldo_inicial'] + (float)$mensaje->total : (float)$aux[0]['saldo_inicial'];

          $query = sprintf("update cuentas_cheques set saldo_inicial = :saldo where FKCuenta = :id");
          $stmt = $db->prepare($query);
          $stmt->bindValue(":saldo", $saldo_inicial);
          $stmt->bindValue(":id", $array['cuenta_bancaria']);
          $stmt->execute();

          $query = sprintf("update facturacion set saldo_insoluto = 0, estatus = :estatus where id = :id");
          $stmt = $db->prepare($query);
          $stmt->bindValue(":id", $id_factura);
          $stmt->bindValue(":estatus", 3);
          $stmt->execute();

          //eliminamos los pagos de la(s) venta(s) (estatus 2)
          if((int)$array['tipoDocumento']==3 || (int)$array['tipoDocumento']==2){
            switch((int)$array['tipoDocumento']){
              case 3:
                $arrayTotalSalidas = [];

                //recupera el total a pagar de la(s) venta(s)
                for ($i = 0; $i < count($array['idDocumento']); $i++){
                    $query = sprintf("select distinct * from (
                                      select distinct 
                                        numero_venta_directa 
                                      from orden_pedido_por_sucursales o
                                        inner join inventario_salida_por_sucursales s on o.id = s.orden_pedido_id
                                      where s.folio_salida = :salida and o.empresa_id = :empresa
                                      
                                      union

                                      select distinct
                                        numero_venta_directa 
                                      from orden_pedido_por_sucursales o
                                        inner join movimientos_salidas_servicios_sin_inventario ssi on o.id = ssi.FKOrdenPedido
                                      where ssi.FKSalida = :salida2 and o.empresa_id = :empresa2) as tabla");
                  $stmt = $db->prepare($query);
                  $stmt->execute([":salida"=>$array['idDocumento'][$i], ":salida2"=>$array['idDocumento'][$i], ":empresa"=>$_SESSION['IDEmpresa'], ":empresa2"=>$_SESSION['IDEmpresa']]);

                  $arr = $stmt->fetchAll(PDO::FETCH_OBJ);
                  
                  $TotalSubtotalSalida = $getData->getTotalSubtotalSalidas(0,'["'.$array['idDocumento'][$i].'"]',3);
                  

                  isset($arrayTotalSalidas[$arr[0]->numero_venta_directa]) ? $arrayTotalSalidas[$arr[0]->numero_venta_directa] = number_format($arrayTotalSalidas[$arr[0]->numero_venta_directa] + floatval(str_replace("$ ", "", $TotalSubtotalSalida['total'])),2,'.','') : $arrayTotalSalidas[$arr[0]->numero_venta_directa] = floatval(str_replace("$", "", $TotalSubtotalSalida['total']));
                }

                //compara el importe pagado de cada venta y el importe que se está pagando
                foreach($arrayTotalSalidas as $id_venta=>$a_pagar){
                  $query = sprintf("SELECT sum(m.deposito) as totalPagadoVenta from movimientos_cuentas_bancarias_empresa as m 
                                    where m.id_factura = :id and m.tipo_CuentaCobrar = 1 and m.estatus=1");
                  $stmt = $db->prepare($query);
                  $stmt->bindValue(":id", $id_venta); 
                  $stmt->execute();
                  $PagadoVenta = $stmt->fetchAll();


                  //si lo que tiene pagado la venta es menor a lo que se está pagando al facturar, se borran los pagos de la venta y se elimina el importe de la cuenta bancaria
                  if($PagadoVenta[0]['totalPagadoVenta'] < $a_pagar){
                    $query = sprintf("UPDATE movimientos_cuentas_bancarias_empresa as m set m.estatus = 2, m.id_factura_relacion_pagoVenta = :id_factura where m.id_factura = :id and m.tipo_CuentaCobrar = 1 and m.estatus=1");
                    $stmt = $db->prepare($query);
                    $stmt->bindValue(":id", $id_venta);
                    $stmt->bindValue(":id_factura", $id_factura);
                    $stmt->execute();
                  }else{
                  //si el importe a pagar es menor a lo pagado de la venta, se modifica la cantidad del pago (dejandolo en el total que se está pagando) y se crea un nuevo pago de la venta con el restante.       
                    $query = sprintf("SELECT Deposito, id_factura, id_pago from movimientos_cuentas_bancarias_empresa where id_factura = :id_venta and tipo_CuentaCobrar = 1 and estatus=1");
                    $stmt = $db->prepare($query);
                    $stmt->bindValue(":id_venta", $id_venta);
                    $stmt->execute();
                    $pagosVenta = $stmt->fetchAll();

                    //va sumando cada pago hasta igualar el importe a pagar y modifica el estatus en cada iteración, si tiene excedente el ultimo pago modifica la cantidad del pago y crea nuevo pago con el exedente
                    if($stmt->rowCount() > 0){
                      $acumulado = 0;
                      for ($i = 0; $i < $stmt->rowCount(); $i++){
                        $acumulado += (float)$pagosVenta[$i]['Deposito'];
                          if($acumulado <= (float)$a_pagar){
                            $query = sprintf("UPDATE movimientos_cuentas_bancarias_empresa as m set m.estatus = 2, m.id_factura_relacion_pagoVenta = :id_factura where m.id_factura = :id and m.id_pago = :idPago and m.tipo_CuentaCobrar = 1 and m.estatus=1");
                            $stmt = $db->prepare($query);
                            $stmt->bindValue(":id", $pagosVenta[$i]['id_factura']);
                            $stmt->bindValue(":idPago", $pagosVenta[$i]['id_pago']);
                            $stmt->bindValue(":id_factura", $id_factura);
                            $stmt->execute();
                          }else{
                            $excedente = ((float)$acumulado - (float)$a_pagar);

                            $query=sprintf("CALL spu_PagosVentaDirecta(?,?,?,?,?,?,?);");
                            $stmt = $db->prepare($query);
                            $stmt->execute(array($pagosVenta[$i]['id_factura'], $excedente, $pagosVenta[$i]['id_pago'], $array['cuenta_bancaria'], $_SESSION['PKUsuario'], $_SESSION['IDEmpresa'],$id_factura));
                          }
                      }
                    }
                  }
                }
                break;
              case 2:
                if(is_array($array['idDocumento'])){
                  $id_documento = $array['idDocumento'][0];
                } else {
                  $id_documento = $array['idDocumento'];
                }
                $query = sprintf("UPDATE movimientos_cuentas_bancarias_empresa as m set m.estatus = 2, m.id_factura_relacion_pagoVenta = :id_factura where m.id_factura = :id and m.tipo_CuentaCobrar = 1 and m.estatus=1");
                $stmt = $db->prepare($query);
                $stmt->bindValue(":id", $id_documento);
                $stmt->bindValue(":id_factura", $id_factura);
                $stmt->execute();
                break;
                //resta de depositos de las cuentas bancarias y estatus del pago realizados en trigger de la tabla "movimientos cuentas bancarias empresas"
            }
          }

        } 
      }
    }else{
        switch($array['tipoDocumento']){
          case 3:
              //recupera las ventas de los folios de salida
              $folios_Json = json_encode($array['idDocumento']);
              $pks_ventas_aPagar = $getData->getVentasOrigen($folios_Json);
              $flagPagos = false;
              $sumaPagosVentas = 0;

              //recorremos el arreglo de ventas para ver si alguna tiene pagos
              for($i=0; $i< count($pks_ventas_aPagar['salidas']); $i++){
                $query = sprintf("SELECT m.cuenta_destino_id, m.Fecha, p.forma_pago, sum(m.deposito) as totalPagadoVenta from movimientos_cuentas_bancarias_empresa as m 
                                    inner join pagos as p on m.id_pago = p.idpagos
                                  where m.id_factura = :id and m.tipo_CuentaCobrar=1 and m.estatus=1 order by m.Fecha desc limit 1;");
                $stmt = $db->prepare($query);
                $stmt->bindValue(":id", (int)$pks_ventas_aPagar['salidas'][$i]);
                $stmt->execute();
                $Pagado = $stmt->fetchAll();

                if((double)$Pagado[0]['totalPagadoVenta'] > 0){
                  $flagPagos = true;
                  if($array['metodoPago'] == "PUE"){
                    break;
                  }else{
                    $sumaPagosVentas += (double)$Pagado[0]['totalPagadoVenta'];
                  }
                }
              }

              if($flagPagos){
                // procede a hacer el registro del pago
                switch($array['metodoPago']){
                  case 'PPD':
                      $query = sprintf("insert into pagos (
                                                          fecha_registro,
                                                          total,
                                                          tipo_movimiento,
                                                          identificador_pago,
                                                          fecha_pago,
                                                          estatus,
                                                          empresa_id,
                                                          forma_pago
                                                        ) values (
                                                          :fecha_registro,
                                                          :total,
                                                          :tipo_movimiento,
                                                          :identificador_pago,
                                                          :fecha_pago,
                                                          :estatus,
                                                          :empresa_id,
                                                          :forma_pago
                                                        )");
                    $stmt = $db->prepare($query);

                    $stmt->bindValue(":fecha_registro", date("Y-m-d H:i:s"));
                    $stmt->bindValue(":total", $sumaPagosVentas);
                    $stmt->bindValue(":tipo_movimiento", 0);
                    $stmt->bindValue(":identificador_pago", $iden);
                    $stmt->bindValue(":fecha_pago", date("Y-m-d"));
                    $stmt->bindValue(":estatus", 0);
                    $stmt->bindValue(":empresa_id", $_SESSION['IDEmpresa']);
                    $stmt->bindValue(":forma_pago", $array['formaPago']);

                    if ($stmt->execute()) {

                      $pagoLast = $db->lastInsertId();
                      
                      $query = sprintf("insert into movimientos_cuentas_bancarias_empresa (
                                            Fecha,
                                            Descripcion,
                                            Deposito,
                                            tipo_movimiento_id,
                                            cuenta_origen_id,
                                            cuenta_destino_id,
                                            FKResponsable,
                                            id_pago,
                                            id_factura,
                                            saldo_anterior,
                                            saldo_insoluto,
                                            estatus,
                                            Saldo,
                                            parcialidad,
                                            Referencia,
                                            tipo_CuentaCobrar,
                                            id_factura_relacion_pagoVenta
                                          ) values (
                                            :fecha,
                                            :descripcion,
                                            :deposito,
                                            :tipo_movimiento_id,
                                            :cuenta_origen_id,
                                            :cuenta_destino_id,
                                            :responsable,
                                            :id_pago,
                                            :id_factura,
                                            :saldo_anterior,
                                            :saldo_insoluto,
                                            :estatus,
                                            :saldo,
                                            :parcialidad,
                                            :referencia,
                                            :tipoCuentaPagar,
                                            :idFacturaRelacion
                                          )");
                      $stmt = $db->prepare($query);
                      $stmt->bindValue(":fecha", date("Y-m-d H:i:s"));
                      $stmt->bindValue(":descripcion", "Recepcion de pago");
                      $stmt->bindValue(":deposito", $sumaPagosVentas);
                      $stmt->bindValue(":tipo_movimiento_id", 5);
                      $stmt->bindValue(":cuenta_origen_id", 300);
                      $stmt->bindValue(":cuenta_destino_id", $Pagado[0]['cuenta_destino_id']);
                      $stmt->bindValue(":responsable", $_SESSION['PKUsuario']);
                      $stmt->bindValue(":id_pago", $pagoLast);
                      $stmt->bindValue(":id_factura", $id_factura);
                      $stmt->bindValue(":saldo_anterior", $mensaje->total);
                      $stmt->bindValue(":saldo_insoluto", ($mensaje->total - $sumaPagosVentas));
                      $stmt->bindValue(":estatus", 1);
                      $stmt->bindValue(":saldo", 1234);
                      $stmt->bindValue(":parcialidad", 1);
                      $stmt->bindValue(":tipoCuentaPagar", 2);
                      $stmt->bindValue(":idFacturaRelacion", $id_factura);
                      $stmt->bindValue(":referencia",$array['referencia']);

                      if ($stmt->execute()) {

                        $query = sprintf("select saldo_inicial from cuentas_cheques where FKCuenta = :id");
                        $stmt = $db->prepare($query);
                        $stmt->bindValue(":id", $Pagado[0]['cuenta_destino_id']);
                        $stmt->execute();

                        $aux = $stmt->fetchAll();

                        $saldo_inicial = $aux[0]['saldo_inicial'] !== null && $aux[0]['saldo_inicial'] !== "" ? (float)$aux[0]['saldo_inicial'] + (float)$sumaPagosVentas : (float)$aux[0]['saldo_inicial'];

                        $query = sprintf("update cuentas_cheques set saldo_inicial = :saldo where FKCuenta = :id");
                        $stmt = $db->prepare($query);
                        $stmt->bindValue(":saldo", $saldo_inicial);
                        $stmt->bindValue(":id", $Pagado[0]['cuenta_destino_id']);
                        $stmt->execute();

                        if($mensaje->total > $sumaPagosVentas){
                          $estatusFactura = 2;
                        }else{
                          $estatusFactura = 3;
                        }

                        $query = sprintf("update facturacion set saldo_insoluto = :si, estatus = :estatus where id = :id");
                        $stmt = $db->prepare($query);
                        $stmt->bindValue(":id", $id_factura);
                        $stmt->bindValue(":estatus", $estatusFactura);
                        $stmt->bindValue(":si", ($mensaje->total-$sumaPagosVentas));

                        $stmt->execute();

                       //elimina pagos de las ventas.
                        //compara el importe pagado de cada venta y el importe que se está pagando
                        foreach($pks_ventas_aPagar['totalFS'] as $id_venta=>$a_pagar){
                          $query = sprintf("SELECT sum(m.deposito) as totalPagadoVenta from movimientos_cuentas_bancarias_empresa as m 
                                            where m.id_factura = :id and m.tipo_CuentaCobrar = 1 and m.estatus=1");
                          $stmt = $db->prepare($query);
                          $stmt->bindValue(":id", $id_venta);
                          $stmt->execute();
                          $PagadoVenta = $stmt->fetchAll();


                          //si lo que tiene pagado la venta es menor a lo que se está pagando al facturar, se borran los pagos de la venta y se elimina el importe de la cuenta bancaria
                          if($PagadoVenta[0]['totalPagadoVenta'] < $a_pagar){
                            $query = sprintf("UPDATE movimientos_cuentas_bancarias_empresa as m set m.estatus = 2, m.id_factura_relacion_pagoVenta = :id_factura where m.id_factura = :id and m.tipo_CuentaCobrar = 1 and m.estatus=1");
                            $stmt = $db->prepare($query);
                            $stmt->bindValue(":id", $id_venta);
                            $stmt->bindValue(":id_factura", $id_factura);
                            $stmt->execute();
                          }else{
                          //si el importe a pagar es menor a lo pagado de la venta, se modifica la cantidad del pago (dejandolo en el total que se está pagando) y se crea un nuevo pago de la venta con el restante.       
                            $query = sprintf("SELECT Deposito, id_factura, id_pago from movimientos_cuentas_bancarias_empresa where id_factura = :id_venta and tipo_CuentaCobrar = 1 and estatus=1");
                            $stmt = $db->prepare($query);
                            $stmt->bindValue(":id_venta", $id_venta);
                            $stmt->execute();
                            $pagosVenta = $stmt->fetchAll();

                            //va sumando cada pago hasta igualar el importe a pagar y modifica el estatus en cada iteración, si tiene excedente el ultimo pago modifica la cantidad del pago y crea nuevo pago con el exedente
                            if($stmt->rowCount() > 0){
                              $acumulado = 0;
                              for ($i = 0; $i < $stmt->rowCount(); $i++){
                                $acumulado += (float)$pagosVenta[$i]['Deposito'];
                                  if($acumulado <= (float)$a_pagar){
                                    $query = sprintf("UPDATE movimientos_cuentas_bancarias_empresa as m set m.estatus = 2, m.id_factura_relacion_pagoVenta = :id_factura where m.id_factura = :id and m.id_pago = :idPago and m.tipo_CuentaCobrar = 1 and m.estatus=1");
                                    $stmt = $db->prepare($query);
                                    $stmt->bindValue(":id", $pagosVenta[$i]['id_factura']);
                                    $stmt->bindValue(":idPago", $pagosVenta[$i]['id_pago']);
                                    $stmt->bindValue(":id_factura", $id_factura);
                                    $stmt->execute();
                                  }else{
                                    $excedente = ((float)$acumulado - (float)$a_pagar);

                                    $query=sprintf("CALL spu_PagosVentaDirecta(?,?,?,?,?,?,?);");
                                    $stmt = $db->prepare($query);
                                    $stmt->execute(array($pagosVenta[$i]['id_factura'], $excedente, $pagosVenta[$i]['id_pago'], 0, $_SESSION['PKUsuario'], $_SESSION['IDEmpresa'],$id_factura));
                                  }
                              }
                            }
                          }
                        }
                      } 
                    }
                    break;
                  case 'PUE':
                    $query = sprintf("insert into pagos (
                                                          fecha_registro,
                                                          total,
                                                          tipo_movimiento,
                                                          identificador_pago,
                                                          fecha_pago,
                                                          estatus,
                                                          empresa_id,
                                                          forma_pago
                                                        ) values (
                                                          :fecha_registro,
                                                          :total,
                                                          :tipo_movimiento,
                                                          :identificador_pago,
                                                          :fecha_pago,
                                                          :estatus,
                                                          :empresa_id,
                                                          :forma_pago
                                                        )");
                    $stmt = $db->prepare($query);

                    $stmt->bindValue(":fecha_registro", date("Y-m-d H:i:s"));
                    $stmt->bindValue(":total", $mensaje->total);
                    $stmt->bindValue(":tipo_movimiento", 0);
                    $stmt->bindValue(":identificador_pago", $iden);
                    $stmt->bindValue(":fecha_pago", date("Y-m-d"));
                    $stmt->bindValue(":estatus", 0);
                    $stmt->bindValue(":empresa_id", $_SESSION['IDEmpresa']);
                    $stmt->bindValue(":forma_pago", $array['formaPago']);

                    if ($stmt->execute()) {

                      $pagoLast = $db->lastInsertId();
                      
                      $query = sprintf("insert into movimientos_cuentas_bancarias_empresa (
                                            Fecha,
                                            Descripcion,
                                            Deposito,
                                            tipo_movimiento_id,
                                            cuenta_origen_id,
                                            cuenta_destino_id,
                                            FKResponsable,
                                            id_pago,
                                            id_factura,
                                            saldo_anterior,
                                            saldo_insoluto,
                                            estatus,
                                            Saldo,
                                            parcialidad,
                                            Referencia,
                                            tipo_CuentaCobrar,
                                            id_factura_relacion_pagoVenta
                                          ) values (
                                            :fecha,
                                            :descripcion,
                                            :deposito,
                                            :tipo_movimiento_id,
                                            :cuenta_origen_id,
                                            :cuenta_destino_id,
                                            :responsable,
                                            :id_pago,
                                            :id_factura,
                                            :saldo_anterior,
                                            :saldo_insoluto,
                                            :estatus,
                                            :saldo,
                                            :parcialidad,
                                            :referencia,
                                            :tipoCuentaPagar,
                                            :idFacturaRelacion
                                          )");
                      $stmt = $db->prepare($query);
                      $stmt->bindValue(":fecha", date("Y-m-d H:i:s"));
                      $stmt->bindValue(":descripcion", "Recepcion de pago");
                      $stmt->bindValue(":deposito", $mensaje->total);
                      $stmt->bindValue(":tipo_movimiento_id", 5);
                      $stmt->bindValue(":cuenta_origen_id", 300);
                      $stmt->bindValue(":cuenta_destino_id", $Pagado[0]['cuenta_destino_id']);
                      $stmt->bindValue(":responsable", $_SESSION['PKUsuario']);
                      $stmt->bindValue(":id_pago", $pagoLast);
                      $stmt->bindValue(":id_factura", $id_factura);
                      $stmt->bindValue(":saldo_anterior", $mensaje->total);
                      $stmt->bindValue(":saldo_insoluto", 0);
                      $stmt->bindValue(":estatus", 1);
                      $stmt->bindValue(":saldo", 1234);
                      $stmt->bindValue(":parcialidad", 1);
                      $stmt->bindValue(":tipoCuentaPagar", 2);
                      $stmt->bindValue(":idFacturaRelacion", $id_factura);
                      $stmt->bindValue(":referencia",$array['referencia']);

                      if ($stmt->execute()) {

                        $query = sprintf("select saldo_inicial from cuentas_cheques where FKCuenta = :id");
                        $stmt = $db->prepare($query);
                        $stmt->bindValue(":id", $Pagado[0]['cuenta_destino_id']);
                        $stmt->execute();

                        $aux = $stmt->fetchAll();

                        $saldo_inicial = $aux[0]['saldo_inicial'] !== null && $aux[0]['saldo_inicial'] !== "" ? (float)$aux[0]['saldo_inicial'] + (float)$mensaje->total : (float)$aux[0]['saldo_inicial'];

                        $query = sprintf("update cuentas_cheques set saldo_inicial = :saldo where FKCuenta = :id");
                        $stmt = $db->prepare($query);
                        $stmt->bindValue(":saldo", $saldo_inicial);
                        $stmt->bindValue(":id", $Pagado[0]['cuenta_destino_id']);
                        $stmt->execute();

                        $query = sprintf("update facturacion set saldo_insoluto = 0, estatus = :estatus where id = :id");
                        $stmt = $db->prepare($query);
                        $stmt->bindValue(":id", $id_factura);
                        $stmt->bindValue(":estatus", 3);
                        $stmt->execute();

                       //elimina pagos de las ventas.
                        $arrayTotalSalidas = [];

                        //recupera el total a pagar de la(s) venta(s)
                        for ($i = 0; $i < count($array['idDocumento']); $i++){
                          $query = sprintf("select distinct * from (
                                              select distinct 
                                                numero_venta_directa 
                                              from orden_pedido_por_sucursales o
                                                inner join inventario_salida_por_sucursales s on o.id = s.orden_pedido_id
                                              where s.folio_salida = :salida and o.empresa_id = :empresa
                                              
                                              union

                                              select distinct
                                                numero_venta_directa 
                                              from orden_pedido_por_sucursales o
                                                inner join movimientos_salidas_servicios_sin_inventario ssi on o.id = ssi.FKOrdenPedido
                                              where ssi.FKSalida = :salida2 and o.empresa_id = :empresa2) as tabla");
                          $stmt = $db->prepare($query);
                          $stmt->execute([":salida"=>$array['idDocumento'][$i], ":salida2"=>$array['idDocumento'][$i], ":empresa"=>$_SESSION['IDEmpresa'], ":empresa2"=>$_SESSION['IDEmpresa']]);

                          $arr = $stmt->fetchAll(PDO::FETCH_OBJ);
                          
                          $TotalSubtotalSalida = $getData->getTotalSubtotalSalidas(0,'["'.$array['idDocumento'][$i].'"]',3);
                          

                          isset($arrayTotalSalidas[$arr[0]->numero_venta_directa]) ? $arrayTotalSalidas[$arr[0]->numero_venta_directa] = number_format($arrayTotalSalidas[$arr[0]->numero_venta_directa] + floatval(str_replace("$ ", "", $TotalSubtotalSalida['total'])),2,'.','') : $arrayTotalSalidas[$arr[0]->numero_venta_directa] = floatval(str_replace("$", "", $TotalSubtotalSalida['total']));
                        }

                        //compara el importe pagado de cada venta y el importe que se está pagando
                        foreach($arrayTotalSalidas as $id_venta=>$a_pagar){
                          $query = sprintf("SELECT sum(m.deposito) as totalPagadoVenta from movimientos_cuentas_bancarias_empresa as m 
                                            where m.id_factura = :id and m.tipo_CuentaCobrar = 1 and m.estatus=1");
                          $stmt = $db->prepare($query);
                          $stmt->bindValue(":id", $id_venta);
                          $stmt->execute();
                          $PagadoVenta = $stmt->fetchAll();


                          //si lo que tiene pagado la venta es menor a lo que se está pagando al facturar, se borran los pagos de la venta y se elimina el importe de la cuenta bancaria
                          if($PagadoVenta[0]['totalPagadoVenta'] < $a_pagar){
                            $query = sprintf("UPDATE movimientos_cuentas_bancarias_empresa as m set m.estatus = 2, m.id_factura_relacion_pagoVenta = :id_factura where m.id_factura = :id and m.tipo_CuentaCobrar = 1 and m.estatus=1");
                            $stmt = $db->prepare($query);
                            $stmt->bindValue(":id", $id_venta);
                            $stmt->bindValue(":id_factura", $id_factura);
                            $stmt->execute();
                          }else{
                          //si el importe a pagar es menor a lo pagado de la venta, se modifica la cantidad del pago (dejandolo en el total que se está pagando) y se crea un nuevo pago de la venta con el restante.       
                            $query = sprintf("SELECT Deposito, id_factura, id_pago from movimientos_cuentas_bancarias_empresa where id_factura = :id_venta and tipo_CuentaCobrar = 1 and estatus=1");
                            $stmt = $db->prepare($query);
                            $stmt->bindValue(":id_venta", $id_venta);
                            $stmt->execute();
                            $pagosVenta = $stmt->fetchAll();

                            //va sumando cada pago hasta igualar el importe a pagar y modifica el estatus en cada iteración, si tiene excedente el ultimo pago modifica la cantidad del pago y crea nuevo pago con el exedente
                            if($stmt->rowCount() > 0){
                              $acumulado = 0;
                              for ($i = 0; $i < $stmt->rowCount(); $i++){
                                $acumulado += (float)$pagosVenta[$i]['Deposito'];
                                  if($acumulado <= (float)$a_pagar){
                                    $query = sprintf("UPDATE movimientos_cuentas_bancarias_empresa as m set m.estatus = 2, m.id_factura_relacion_pagoVenta = :id_factura where m.id_factura = :id and m.id_pago = :idPago and m.tipo_CuentaCobrar = 1 and m.estatus=1");
                                    $stmt = $db->prepare($query);
                                    $stmt->bindValue(":id", $pagosVenta[$i]['id_factura']);
                                    $stmt->bindValue(":idPago", $pagosVenta[$i]['id_pago']);
                                    $stmt->bindValue(":id_factura", $id_factura);
                                    $stmt->execute();
                                  }else{
                                    $excedente = ((float)$acumulado - (float)$a_pagar);

                                    $query=sprintf("CALL spu_PagosVentaDirecta(?,?,?,?,?,?,?);");
                                    $stmt = $db->prepare($query);
                                    $stmt->execute(array($pagosVenta[$i]['id_factura'], $excedente, $pagosVenta[$i]['id_pago'], 0, $_SESSION['PKUsuario'], $_SESSION['IDEmpresa'], $id_factura));
                                  }
                              }
                            }
                          }
                        }
                      } 
                    }
                    break;
                }
              }
            break;
          case 2:
            if(is_array($array['idDocumento'])){
              $id_documento = $array['idDocumento'][0];
            } else {
              $id_documento = $array['idDocumento'];
            }

            //comprueba que tenga pagos la venta
            $query = sprintf("SELECT vd.PKVentaDirecta, m.cuenta_destino_id, m.Fecha, p.forma_pago, vd.importe, sum(m.deposito) as pagado from ventas_directas as vd
                                inner join movimientos_cuentas_bancarias_empresa as m on m.id_factura = vd.PKVentaDirecta
                                inner join pagos as p on m.id_pago = p.idpagos
                              where vd.PKVentaDirecta = :id and m.tipo_CuentaCobrar=1 and m.estatus=1 order by m.Fecha desc limit 1;");
            $stmt = $db->prepare($query);
            $stmt->bindValue(":id", $id_documento);
            $stmt->execute();
            $res=$stmt->fetchAll();

            if($stmt->rowCount() > 0 && $res[0]['pagado'] > 0){
              switch($array['metodoPago']){
                case 'PPD':
                  if($mensaje->total > $res[0]['pagado']){
                    $total = $res[0]['pagado'];
                    $estatusFactura = 2;
                  }else{
                    $total = $mensaje->total;
                    $estatusFactura = 3;
                  }

                    $query = sprintf("insert into pagos (
                                                          fecha_registro,
                                                          total,
                                                          tipo_movimiento,
                                                          identificador_pago,
                                                          fecha_pago,
                                                          estatus,
                                                          empresa_id,
                                                          forma_pago
                                                        ) values (
                                                          :fecha_registro,
                                                          :total,
                                                          :tipo_movimiento,
                                                          :identificador_pago,
                                                          :fecha_pago,
                                                          :estatus,
                                                          :empresa_id,
                                                          :forma_pago
                                                        )");
                    $stmt = $db->prepare($query);

                    $stmt->bindValue(":fecha_registro", date("Y-m-d H:i:s"));
                    $stmt->bindValue(":total", $total);
                    $stmt->bindValue(":tipo_movimiento", 0);
                    $stmt->bindValue(":identificador_pago", $iden);
                    $stmt->bindValue(":fecha_pago", date("Y-m-d"));
                    $stmt->bindValue(":estatus", 1);
                    $stmt->bindValue(":empresa_id", $_SESSION['IDEmpresa']);
                    $stmt->bindValue(":forma_pago", $array['formaPago']);

                    if ($stmt->execute()) {

                      $pagoLast = $db->lastInsertId();
                      
                      $query = sprintf("insert into movimientos_cuentas_bancarias_empresa (
                                            Fecha,
                                            Descripcion,
                                            Deposito,
                                            tipo_movimiento_id,
                                            cuenta_origen_id,
                                            cuenta_destino_id,
                                            FKResponsable,
                                            id_pago,
                                            id_factura,
                                            saldo_anterior,
                                            saldo_insoluto,
                                            estatus,
                                            Saldo,
                                            parcialidad,
                                            Referencia,
                                            tipo_CuentaCobrar,
                                            id_factura_relacion_pagoVenta
                                          ) values (
                                            :fecha,
                                            :descripcion,
                                            :deposito,
                                            :tipo_movimiento_id,
                                            :cuenta_origen_id,
                                            :cuenta_destino_id,
                                            :responsable,
                                            :id_pago,
                                            :id_factura,
                                            :saldo_anterior,
                                            :saldo_insoluto,
                                            :estatus,
                                            :saldo,
                                            :parcialidad,
                                            :referencia,
                                            :tipoCuentaPagar,
                                            :idFacturaRelacion
                                          )");
                      $stmt = $db->prepare($query);
                      $stmt->bindValue(":fecha", date("Y-m-d H:i:s"));
                      $stmt->bindValue(":descripcion", "Recepcion de pago");
                      $stmt->bindValue(":deposito", $total);
                      $stmt->bindValue(":tipo_movimiento_id", 5);
                      $stmt->bindValue(":cuenta_origen_id", 300);
                      $stmt->bindValue(":cuenta_destino_id", $res[0]['cuenta_destino_id']);
                      $stmt->bindValue(":responsable", $_SESSION['PKUsuario']);
                      $stmt->bindValue(":id_pago", $pagoLast);
                      $stmt->bindValue(":id_factura", $id_factura);
                      $stmt->bindValue(":saldo_anterior", $mensaje->total);
                      $stmt->bindValue(":saldo_insoluto", ($mensaje->total - $total));
                      $stmt->bindValue(":estatus", 1);
                      $stmt->bindValue(":saldo", 1234);
                      $stmt->bindValue(":parcialidad", 1);
                      $stmt->bindValue(":tipoCuentaPagar", 2);
                      $stmt->bindValue(":idFacturaRelacion", $id_factura);
                      $stmt->bindValue(":referencia",$array['referencia']);

                      if ($stmt->execute()) {

                        $query = sprintf("select saldo_inicial from cuentas_cheques where FKCuenta = :id");
                        $stmt = $db->prepare($query);
                        $stmt->bindValue(":id", $res[0]['cuenta_destino_id']);
                        $stmt->execute();

                        $aux = $stmt->fetchAll();

                        $saldo_inicial = $aux[0]['saldo_inicial'] !== null && $aux[0]['saldo_inicial'] !== "" ? (float)$aux[0]['saldo_inicial'] + (float)$total : (float)$aux[0]['saldo_inicial'];

                        $query = sprintf("update cuentas_cheques set saldo_inicial = :saldo where FKCuenta = :id");
                        $stmt = $db->prepare($query);
                        $stmt->bindValue(":saldo", $saldo_inicial);
                        $stmt->bindValue(":id", $res[0]['cuenta_destino_id']);
                        $stmt->execute();

                        $query = sprintf("update facturacion set saldo_insoluto = :insoluto, estatus = :estatus where id = :id");
                        $stmt = $db->prepare($query);
                        $stmt->bindValue(":insoluto", ($mensaje->total - $total));
                        $stmt->bindValue(":id", $id_factura);
                        $stmt->bindValue(":estatus", $estatusFactura);
                        $stmt->execute();

                        //eliminamos los pagos de la(s) venta(s) (estatus 2)
                        $query = sprintf("UPDATE movimientos_cuentas_bancarias_empresa as m set m.estatus = 2, m.id_factura_relacion_pagoVenta = :id_factura where m.id_factura = :id and m.tipo_CuentaCobrar = 1 and m.estatus=1");
                        $stmt = $db->prepare($query);
                        $stmt->bindValue(":id", $id_documento);
                        $stmt->bindValue(":id_factura", $id_factura);
                        $stmt->execute();
                      }
                    }
                  break;
                case 'PUE':
                    //registra pago completo y borra pagos de la venta
                    $query = sprintf("insert into pagos (
                                                            fecha_registro,
                                                            total,
                                                            tipo_movimiento,
                                                            identificador_pago,
                                                            fecha_pago,
                                                            estatus,
                                                            empresa_id,
                                                            forma_pago
                                                          ) values (
                                                            :fecha_registro,
                                                            :total,
                                                            :tipo_movimiento,
                                                            :identificador_pago,
                                                            :fecha_pago,
                                                            :estatus,
                                                            :empresa_id,
                                                            :forma_pago
                                                          )");
                    $stmt = $db->prepare($query);

                    $stmt->bindValue(":fecha_registro", date("Y-m-d H:i:s"));
                    $stmt->bindValue(":total", $mensaje->total);
                    $stmt->bindValue(":tipo_movimiento", 0);
                    $stmt->bindValue(":identificador_pago", $iden);
                    $stmt->bindValue(":fecha_pago", date("Y-m-d"));
                    $stmt->bindValue(":estatus", 1);
                    $stmt->bindValue(":empresa_id", $_SESSION['IDEmpresa']);
                    $stmt->bindValue(":forma_pago", $array['formaPago']);

                    if ($stmt->execute()) {

                      $pagoLast = $db->lastInsertId();
                      
                      $query = sprintf("insert into movimientos_cuentas_bancarias_empresa (
                                            Fecha,
                                            Descripcion,
                                            Deposito,
                                            tipo_movimiento_id,
                                            cuenta_origen_id,
                                            cuenta_destino_id,
                                            FKResponsable,
                                            id_pago,
                                            id_factura,
                                            saldo_anterior,
                                            saldo_insoluto,
                                            estatus,
                                            Saldo,
                                            parcialidad,
                                            Referencia,
                                            tipo_CuentaCobrar,
                                            id_factura_relacion_pagoVenta
                                          ) values (
                                            :fecha,
                                            :descripcion,
                                            :deposito,
                                            :tipo_movimiento_id,
                                            :cuenta_origen_id,
                                            :cuenta_destino_id,
                                            :responsable,
                                            :id_pago,
                                            :id_factura,
                                            :saldo_anterior,
                                            :saldo_insoluto,
                                            :estatus,
                                            :saldo,
                                            :parcialidad,
                                            :referencia,
                                            :tipoCuentaPagar,
                                            :idFacturaRelacion
                                          )");
                      $stmt = $db->prepare($query);
                      $stmt->bindValue(":fecha", date("Y-m-d H:i:s"));
                      $stmt->bindValue(":descripcion", "Recepcion de pago");
                      $stmt->bindValue(":deposito", $mensaje->total);
                      $stmt->bindValue(":tipo_movimiento_id", 5);
                      $stmt->bindValue(":cuenta_origen_id", 300);
                      $stmt->bindValue(":cuenta_destino_id", $res[0]['cuenta_destino_id']);
                      $stmt->bindValue(":responsable", $_SESSION['PKUsuario']);
                      $stmt->bindValue(":id_pago", $pagoLast);
                      $stmt->bindValue(":id_factura", $id_factura);
                      $stmt->bindValue(":saldo_anterior", $mensaje->total);
                      $stmt->bindValue(":saldo_insoluto", 0);
                      $stmt->bindValue(":estatus", 1);
                      $stmt->bindValue(":saldo", 1234);
                      $stmt->bindValue(":parcialidad", 1);
                      $stmt->bindValue(":tipoCuentaPagar", 2);
                      $stmt->bindValue(":idFacturaRelacion", $id_factura);
                      $stmt->bindValue(":referencia",$array['referencia']);

                      if ($stmt->execute()) {

                        $query = sprintf("select saldo_inicial from cuentas_cheques where FKCuenta = :id");
                        $stmt = $db->prepare($query);
                        $stmt->bindValue(":id", $res[0]['cuenta_destino_id']);
                        $stmt->execute();

                        $aux = $stmt->fetchAll();

                        $saldo_inicial = $aux[0]['saldo_inicial'] !== null && $aux[0]['saldo_inicial'] !== "" ? (float)$aux[0]['saldo_inicial'] + (float)$mensaje->total : (float)$aux[0]['saldo_inicial'];

                        $query = sprintf("update cuentas_cheques set saldo_inicial = :saldo where FKCuenta = :id");
                        $stmt = $db->prepare($query);
                        $stmt->bindValue(":saldo", $saldo_inicial);
                        $stmt->bindValue(":id", $res[0]['cuenta_destino_id']);
                        $stmt->execute();

                        $query = sprintf("update facturacion set saldo_insoluto = 0, estatus = :estatus where id = :id");
                        $stmt = $db->prepare($query);
                        $stmt->bindValue(":id", $id_factura);
                        $stmt->bindValue(":estatus", 3);
                        $stmt->execute();

                        //eliminamos los pagos de la(s) venta(s) (estatus 2)
                        $query = sprintf("UPDATE movimientos_cuentas_bancarias_empresa as m set m.estatus = 2, m.id_factura_relacion_pagoVenta = :id_factura where m.id_factura = :id and m.tipo_CuentaCobrar = 1 and m.estatus=1");
                        $stmt = $db->prepare($query);
                        $stmt->bindValue(":id", $id_documento);
                        $stmt->bindValue(":id_factura", $id_factura);
                        $stmt->execute();
                      }
                    }
                  
                  break;
              }
            }          
            break;
        }
    }
    
    //return $response;
  }

  function saveDateExpiration($array,$id_factura,$dias_credito){
    $con = new conectar();
    $db = $con->getDb();

    if ($array['fecha_vencimiento'] !== null && $array['fecha_vencimiento'] !== "" && $array['fecha_vencimiento'] !== "null") {
      $fecha_vencimiento = date("Y-m-d", strtotime($array['fecha_vencimiento']));
      $query = sprintf("update facturacion set fecha_vencimiento = :fecha where id = :id");
      $stmt = $db->prepare($query);
      $stmt->bindValue(":fecha", $fecha_vencimiento);
      $stmt->bindValue(":id", $id_factura);
      $stmt->execute();
    } else {
      $fecha_actual = date("Y-m-d");
      if ($dias_credito > 0) {
        $fecha_vencimiento = date("Y-m-d", strtotime($fecha_actual . "+ " . $dias_credito . "days"));
        $query = sprintf("update facturacion set fecha_vencimiento = :fecha where id = :id");
        $stmt = $db->prepare($query);
        $stmt->bindValue(":fecha", $fecha_vencimiento);
        $stmt->bindValue(":id", $id_factura);
        $stmt->execute();
      } else {
        $query = sprintf("update facturacion set fecha_vencimiento = :fecha where id = :id");
        $stmt = $db->prepare($query);
        $stmt->bindValue(":fecha", $fecha_actual);
        $stmt->bindValue(":id", $id_factura);
        $stmt->execute();
      }
    }
  }

  function saveSalesman($array,$id_factura){
    $con = new conectar();
    $db = $con->getDb();
    $getData = new get_data();
    $email_vendedor = "";
    if ($array['tipoDocumento'] === "0") {
      if (isset($array['vendedor'])) {
        $vendedor = $array['vendedor'] !== "" ? $array['vendedor'] : null;
        $query = sprintf("update facturacion set empleado_id = :vendedor where id = :id");
        $stmt = $db->prepare($query);
        $stmt->bindValue(":vendedor", $vendedor);
        $stmt->bindValue(":id", $id_factura);
        $stmt->execute();

        $query = sprintf("select email from empleados where PKEmpleado = :id");
        $stmt = $db->prepare($query);
        $stmt->bindValue(":id", $vendedor);
        $stmt->execute();

        $aux = $stmt->fetchAll();
        if (count($aux) > 0) {
          $email_vendedor = $aux[0]['email'] !== null && $aux[0]['email'] !== "" ? $aux[0]['email'] : "";
        }
      } else {
        $email_vendedor = "";
      }
    } else {
      $dataVendedor = json_decode($getData->getVendedor($array['tipoDocumento'], $array['idDocumento'], ""));
      $vendedor = $dataVendedor[0]->id;
      $email_vendedor =  $dataVendedor[0]->email;
      $query = sprintf("update facturacion set empleado_id = :vendedor where id = :id");
      $stmt = $db->prepare($query);
      $stmt->bindValue(":vendedor", $vendedor);
      $stmt->bindValue(":id", $id_factura);
      $stmt->execute();
    }
    return $email_vendedor;
  }

  function savePrice($id,$price,$client)
  {
    $con = new conectar();
    $db = $con->getDb();

    $query = sprintf("insert into costo_especial_producto_cliente (CostoEspecial,FKTipoMoneda,FKCliente,FKProducto) values (:price,100,:cliente,:producto)");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":price",$price);
    $stmt->bindValue(":cliente",$client);
    $stmt->bindValue(":producto",$id);
    return $stmt->execute();
  }

  function savePersonal($nombre, $apellido, $genero, $roles, $estado)
  {
    $con = new conectar();
    $db = $con->getDb();
      
      $PKEmpresa = $_SESSION["IDEmpresa"];
      $idEmpleado=0;

    try {
      $selectIdEmpleado = "SELECT MAX(id_empleado) + 1 AS id_empleado FROM empleados WHERE empresa_id=:empresa_id";
      $stmSelectIdEmpleado = $db->prepare($selectIdEmpleado);
      $stmSelectIdEmpleado->execute(array(':empresa_id' => $PKEmpresa));
      $id_empleado = $stmSelectIdEmpleado->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      return "Error en Consulta: " . $e->getMessage();
    }

    try {
        $query = "INSERT INTO empleados(id_empleado, Nombres, PrimerApellido, Genero, FKEstado, empresa_id, estatus)
                  VALUES (:id_empleado, :nombre, :apellido, :genero, :estado_id, :empresa_id, 1)";
        $stmt = $db->prepare($query);
        $stmt->execute(array(':id_empleado' => $id_empleado['id_empleado'],':nombre' => $nombre,':apellido' => $apellido,':genero' => $genero,':estado_id' => $estado, ':empresa_id' => $PKEmpresa));
        $idEmpleado = $db->lastInsertId();

    } catch (PDOException $e) {
        return "Error en Consulta: " . $e->getMessage();
    }
    
    try {
      foreach($roles as $rol){
        $query = "INSERT INTO relacion_tipo_empleado (empleado_id, tipo_empleado_id)
                  VALUES (:empleado_id, :rol_id)";
        $stmt = $db->prepare($query);
        $stmt->execute(array(':empleado_id' => $idEmpleado,':rol_id' => $rol));
      }
    } catch (PDOException $e) {
      return "Error en Consulta: " . $e->getMessage();
    }
      return 'exito';
  }
}

class delete_data
{
  function deleteTaxProducto($ref, $prod, $value, $type, $id, $tasa)
  {
    $con = new conectar();
    $db = $con->getDb();

    switch ($value) {
      case "1":
        $column = "iva";
        break;
      case "2":
        $column = "ieps";
        break;
      case "3":
        $column = "ieps_monto_fijo";
        break;
      case "4":
        $column = "ish";
        break;
      case "5":
        $column = "iva_exento";
        break;
      case "6":
        $column = "iva_retenido";
        break;
      case "7":
        $column = "isr_retenido";
        break;
      case "8":
        $column = "isn_local";
        break;
      case "9":
        $column = "cedular";
        break;
      case "10":
        $column = "al_millar";
        break;
      case "11":
        $column = "funcion_publica";
        break;
      case "12":
        $column = "ieps_retenido";
        break;
      case "13":
        $column = "isr_exento";
        break;
      case "14":
        $column = "isr_monto_fijo";
        break;
      case "15":
        $column = "isr";
        break;
      case "16":
        $column = "ieps_exento";
        break;
      case "17":
        $column = "isr_retenido_monto_fijo";
        break;
      case "18":
        $column = "ieps_retenido_monto_fijo";
        break;
    }

    $query = sprintf("select PKInfoFiscalProducto id from info_fiscal_productos inner join productos on FKProducto = PKProducto where empresa_id = :id and FKProducto = :prod");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":id", $_SESSION['IDEmpresa']);
    $stmt->bindValue(":prod", $prod);
    $stmt->execute();
    $info_fiscal = $stmt->fetch()['id'];

    $query = sprintf("delete from impuestos_productos where FKInfoFiscalProducto =:id and FKImpuesto =:impuesto and Tasa =:tasa");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":id", $info_fiscal);
    $stmt->bindValue(":impuesto", $value);
    $stmt->bindValue(":tasa", $tasa);
    $stmt->execute();

    $query1 = sprintf("update datos_producto_facturacion_temp set " . $column . " = :tasa where producto_id = :id and usuario_id = :user and tipo = :type and referencia = :ref");
    $stmt1 = $db->prepare($query1);
    $stmt1->bindValue(":id", $prod);
    $stmt1->bindValue(":user", $_SESSION['PKUsuario']);
    $stmt1->bindValue(":tasa", null);
    $stmt1->bindValue(":type", $type);
    $stmt1->bindValue(":ref", $ref);
    return $stmt1->execute();
  }

  function deleteProduct($value)
  {
    $con = new conectar();
    $db = $con->getDb();

    $data = json_decode($value);

    $query = sprintf("delete from datos_producto_facturacion_temp where id = :id and producto_id = :producto_id and factura_concepto = 1");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":id", $data->id);
    $stmt->bindValue(":producto_id", $data->product);

    return $stmt->execute();
  }

  function deleteOrderCancel($value)
  {
    $con = new conectar();
    $db = $con->getDb();

    $query = sprintf("delete from orden_pedido_por_sucursales where id = :id");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":id",$value);
    return $stmt->execute();
  }

  function deleteOutputCancel($value)
  {
    $con = new conectar();
    $db = $con->getDb();

    $query = sprintf("delete from inventario_salida_por_sucursales where id = :id");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":id",$value);
    return $stmt->execute();
  }

  function deleteInvoiceDataDoc($value)
  {
    $con = new conectar();
    $db = $con->getDb();
    $get_data = new get_data();
    $update_data = new edit_data();
    $delete_data = new delete_data();

    # Obtiene info necesaria de la factura -referencia y tipo
    $arr = $get_data->getDataInvoiceCancel($value);
    #clave,numero_lote,cantidad,sucursal_id
    switch((int) $arr[0]->tipo){
      case 0:
        $fact = $get_data->getDataInvoiceCancelFact($value);
        if(count($fact) > 0){
          foreach($fact as $r){
            #devuelve la cantidad de la salida a la existencia value = cantidad, value1 = clave del producto, value2 = sucursal, value3 = lote
            $ban = $update_data->updateStockProductCancel($r->cantidad, $r->clave, $r->sucursal_id, $r->numero_lote);
            if($ban){
              $ban1 = $delete_data->deleteOutputCancel($r->salida_id);
              if($ban1){
                $ban2 =  $delete_data->deleteOrderCancel($r->pedido_id);
              }
            }
          }
        }
      case 1:
        # Obtiene datos de la cotización si tipo es igual a 1 y si la columna numero_cotizacion de pedidos es diferente de null o vacío
        $cot = $get_data->getDataInvoiceCancelCot($arr[0]->referencia);
        if(count($cot) > 0)
        {
          foreach($cot as $r){

            #devuelve la cantidad de la salida a la existencia value = cantidad, value1 = clave del producto, value2 = sucursal, value3 = lote
            $ban = $update_data->updateStockProductCancel($r->cantidad, $r->clave, $r->sucursal_id, $r->numero_lote);
            if($ban){
              $ban1 = $delete_data->deleteOutputCancel($r->salida_id);
              if($ban1){
                $ban2 =  $delete_data->deleteOrderCancel($r->pedido_id);
              }
            }
          }
          
        } else {
          # Obtiene datos del pedido si la columna factura_id de pedidos es diferente a null y vacío y es igual al id de la factura
          $fact = $get_data->getDataInvoiceCancelFact($arr[0]->referencia);

          if(count($fact) > 0){
            foreach($fact as $r){
              #devuelve la cantidad de la salida a la existencia value = cantidad, value1 = clave del producto, value2 = sucursal, value3 = lote
              $ban = $update_data->updateStockProductCancel($r->cantidad, $r->clave, $r->sucursal_id, $r->numero_lote);
              if($ban){
                $ban1 = $delete_data->deleteOutputCancel($r->salida_id);
                if($ban1){
                  $ban2 =  $delete_data->deleteOrderCancel($r->pedido_id);
                }
              }
            }
          }
        }
        
      break;
      case 2:
        # Obtiene datos de la venta si tipo es igual a 1 y si la columna numero_venta_directa de pedidos es diferente de null o vacío
        $venta = $get_data->getDataInvoiceCancelVenta($arr[0]->referencia);
        if(count($venta) > 0)
        {
          
          if((int)$venta[0]->afecta_inventario === 0){
            foreach($venta as $r){

              #devuelve la cantidad de la salida a la existencia value = cantidad, value1 = clave del producto, value2 = sucursal, value3 = lote
              $ban = $update_data->updateStockProductCancel($r->cantidad, $r->clave, $r->sucursal_id, $r->numero_lote);
              if($ban){
                $ban1 = $delete_data->deleteOutputCancel($r->salida_id);
              }
            }
          }
          
        } else {
          # Obtiene datos del pedido si la columna factura_id de pedidos es diferente a null y vacío y es igual al id de la factura
          $fact = $get_data->getDataInvoiceCancelFact($arr[0]->referencia);

          if(count($fact) > 0){
            foreach($fact as $r){
              #devuelve la cantidad de la salida a la existencia value = cantidad, value1 = clave del producto, value2 = sucursal, value3 = lote
              $ban = $update_data->updateStockProductCancel($r->cantidad, $r->clave, $r->sucursal_id, $r->numero_lote);
              if($ban){
                $ban1 = $delete_data->deleteOutputCancel($r->salida_id);
              }
            }
          }
        }
      break;
    }
  }
}
class edit_data
{

  function editDataProducto($value)
  {
    $con = new conectar();
    $db = $con->getDb();

    $column = "";
    $importe_impuesto = 0;
    $data = json_decode($value, true);

    $id = $data['id'];
    $cantidad = $data['cantidad'];
    $precio_untario = $data['precio_unitario'];
    $impuestos = $data['impuestos'];
    $subtotal = (float)$data['subtotal'];
    $sat_id = $data['sat_id'];
    $unidad_medida_id = $data['unidad_medida_id'];
    $descuento_tasa = $data['descuento_tasa'];
    $importe_descuento_tasa = $data['importe_descuento_tasa'];
    $descuento_monto_fijo = $data['descuento_monto_fijo'];
    $predial = $data['predial'];

    if ($importe_descuento_tasa !== null && $importe_descuento_tasa !== "") {
      $importe_descuento = $importe_descuento_tasa;
    } else if ($descuento_monto_fijo !== null && $descuento_monto_fijo !== "") {
      $importe_descuento =  $descuento_monto_fijo;
    } else {
      $importe_descuento = 0;
    }

    $importe_impuestos = 0;
    $ieps_iva = 0;
    $iva_ieps = 0;
    $ieps_monto_fijo = null;
    if (count($impuestos) > 0) {
      for ($i = 0; $i < count($impuestos); $i++) {
        switch ($impuestos[$i]['id']) {
          case "1":
            $tasa = (float)$impuestos[$i]['tasa'];
            $column = "importe_iva";            
            $importe_impuesto = $subtotal * ($tasa / 100);
            $importe_impuestos += $importe_impuesto;
            break;
          case "2":
            $tasa = $impuestos[$i]['tasa'];
            $column = "importe_ieps";
            $importe_impuesto = $subtotal * ($tasa / 100);
            $importe_impuestos += $importe_impuesto;
            break;
          case "3":
            $tasa = (float)$impuestos[$i]['tasa'];
            
            $column = "ieps_monto_fijo";
            $importe_impuesto = $tasa;
            $importe_impuestos += $tasa * $cantidad;
            break;
          case "4":
            $tasa = (float)$impuestos[$i]['tasa'];
            $column = "importe_ish";
            $importe_impuesto = $subtotal * ($tasa / 100);
            $importe_impuestos += $importe_impuesto;
            break;
          case "5":
            $tasa = (float)$impuestos[$i]['tasa'];
            $column = "importe_iva_exento";
            $importe_impuesto = $subtotal * ($tasa / 100);
            $importe_impuestos += $importe_impuesto;
            break;
          case "6":
            $tasa = (float)$impuestos[$i]['tasa'];
            $column = "importe_iva_retenido";
            $importe_impuesto = $subtotal * ($tasa / 100);
            $importe_impuestos -= $importe_impuesto;
            break;
          case "7":
            $tasa = (float)$impuestos[$i]['tasa'];
            $column = "importe_isr_retenido";
            $importe_impuesto = $subtotal * ($tasa / 100);
            $importe_impuestos -= $importe_impuesto;
            break;
          case "8":
            $tasa = (float)$impuestos[$i]['tasa'];
            $column = "importe_isn_local";
            $importe_impuesto = $subtotal * ($tasa / 100);
            $importe_impuestos += $importe_impuesto;
            break;
          case "9":
            $tasa = (float)$impuestos[$i]['tasa'];
            $column = "importe_cedular";
            $importe_impuesto = $subtotal * ($tasa / 100);
            $importe_impuestos += $importe_impuesto;
            break;
          case "10":
            $tasa = (float)$impuestos[$i]['tasa'];
            $column = "importe_al_millar";
            $importe_impuesto = $subtotal * ($tasa / 100);
            $importe_impuestos += $importe_impuesto;
            break;
          case "11":
            $tasa = (float)$impuestos[$i]['tasa'];
            $column = "importe_funcion_publica";
            $importe_impuesto = $subtotal * ($tasa / 100);
            $importe_impuestos += $importe_impuesto;
            break;
          case "12":
            $tasa = (float)$impuestos[$i]['tasa'];
            $column = "importe_ieps_retenido";
            $importe_impuesto = $subtotal * ($tasa / 100);
            $importe_impuestos -= $importe_impuesto;
            break;
          case "13":
            $tasa = (float)$impuestos[$i]['tasa'];
            $column = "importe_isr_exento";
            $importe_impuesto = $subtotal * ($tasa / 100);
            $importe_impuestos += $importe_impuesto;
            break;
          case "14":
            $tasa = (float)$impuestos[$i]['tasa'];
            $column = "isr_monto_fijo";
            $importe_impuesto = $subtotal * ($tasa / 100);
            $importe_impuestos += $importe_impuesto;
            break;
          case "15":
            $tasa = (float)$impuestos[$i]['tasa'];
            $column = "importe_isr";
            $importe_impuesto = $subtotal * ($tasa / 100);
            $importe_impuestos += $importe_impuesto;
            break;
          case "16":
            $tasa = (float)$impuestos[$i]['tasa'];
            $column = "importe_ieps_exento";
            $importe_impuesto = $subtotal * ($tasa / 100);
            $importe_impuestos += $importe_impuesto;
            break;
          case "17":
            $tasa = (float)$impuestos[$i]['tasa'];
            $column = "isr_retenido_monto_fijo";
            $importe_impuesto = $subtotal * ($tasa / 100);
            $importe_impuestos -= $importe_impuesto;
            break;
          case "18":
            $tasa = (float)$impuestos[$i]['tasa'];
            $column = "ieps_retenido_monto_fijo";
            $importe_impuesto = $subtotal * ($tasa / 100);
            $importe_impuestos -= $importe_impuesto;
            break;
          
        }

        $query = sprintf("update datos_producto_facturacion_temp set " . $column . " = :tasa where id = :id");
        $stmt = $db->prepare($query);
        $stmt->bindValue(":id", $id);
        $stmt->bindValue(":tasa", $importe_impuesto);
        $stmt->execute();
      }
    }

    $total = $subtotal + $importe_impuestos - $importe_descuento;

    if ($descuento_tasa !== null && $descuento_tasa !== "") {
      $query1 = sprintf("update datos_producto_facturacion_temp set 
                            clave_sat_id=:sat_id,
                            unidad_medida_id=:unidad_medida_id,
                            cantidad=:cantidad,
                            cantidad_facturada=:cantidad_facturada,
                            precio_unitario=:precio,
                            total_bruto=:subtotal,
                            descuento_tasa=:descuento_tasa,
                            importe_descuento_tasa=:importe_descuento_tasa,
                            total_neto=:total,
                            numero_predial=:predial
                        where id = :id
                      ");
    } else if ($descuento_monto_fijo !== null && $descuento_monto_fijo !== "") {
      $query1 = sprintf("update datos_producto_facturacion_temp set 
                            clave_sat_id=:sat_id,
                            unidad_medida_id=:unidad_medida_id,
                            cantidad=:cantidad,
                            cantidad_facturada=:cantidad_facturada,
                            precio_unitario=:precio,
                            total_bruto=:subtotal,
                            descuento_monto_fijo=:descuento_monto_fijo,
                            total_neto=:total,
                            numero_predial=:predial
                        where id = :id
                      ");
    } else {
      $query1 = sprintf("update datos_producto_facturacion_temp set 
                            clave_sat_id=:sat_id,
                            unidad_medida_id=:unidad_medida_id,
                            cantidad=:cantidad,
                            cantidad_facturada=:cantidad_facturada,
                            precio_unitario=:precio,
                            total_bruto=:subtotal,
                            total_neto=:total,
                            numero_predial=:predial
                        where id = :id
        ");
    }



    $stmt1 = $db->prepare($query1);
    $stmt1->bindValue(":sat_id", $sat_id);
    $stmt1->bindValue(":unidad_medida_id", $unidad_medida_id);
    $stmt1->bindValue(":cantidad", $cantidad);
    $stmt1->bindValue(":cantidad_facturada", $cantidad);
    $stmt1->bindValue(":precio", $precio_untario);
    $stmt1->bindValue(":subtotal", $subtotal);

    if ($descuento_tasa !== null && $descuento_tasa !== "") {
      $stmt1->bindValue(":descuento_tasa", $descuento_tasa);
      $stmt1->bindValue(":importe_descuento_tasa", $importe_descuento_tasa);
    } else if ($descuento_monto_fijo !== null && $descuento_monto_fijo !== "") {
      $stmt1->bindValue(":descuento_monto_fijo", $descuento_monto_fijo);
    }

    $stmt1->bindValue(":total", $total);
    $stmt1->bindValue(":predial", $predial);
    $stmt1->bindValue(":id", $id);

    $ban = $stmt1->execute();

    $query = sprintf("update datos_producto_facturacion_temp set 
                        importe_iva = cast(importe_iva+(importe_ieps * (iva / 100)) as decimal(18,6)),
                        total_neto = cast(total_neto+(importe_ieps * (iva / 100)) as decimal(18,6))
                      where id = :id and
                      importe_ieps > 0 and 
                      importe_iva > 0");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":id", $id);
    $stmt->execute();

    $query = sprintf("update datos_producto_facturacion_temp set 
                        importe_iva = cast(importe_iva+((ieps_monto_fijo * cantidad) * (iva / 100)) as decimal(18,6)),
                        total_neto = cast(total_neto+((ieps_monto_fijo * cantidad) * (iva / 100)) as decimal(18,6))
                      where id = :id and
                      ieps_monto_fijo > 0 and 
                      importe_iva > 0");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":id", $id);
    $stmt->execute();

    return $ban;
  }

  function editClaveSat($value, $prod)
  {
    $con = new conectar();
    $db = $con->getDb();

    $query = sprintf("select * from info_fiscal_productos where FKProducto = :id");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":id", $prod);
    $stmt->execute();
    $rowCount = $stmt->rowCount();

    if ($rowCount > 0) {
      $query = sprintf("update info_fiscal_productos set FKClaveSAT = :cls where FKProducto = :id");
      $stmt = $db->prepare($query);
      $stmt->bindValue(":cls", $value);
      $stmt->bindValue(":id", $prod);
      $stmt->execute();
    } else {
      $query = sprintf("insert into info_fiscal_productos (FKClaveSAT,FKProducto,FKClaveSATUnidad) values (:cls,:id,1)");
      $stmt = $db->prepare($query);
      $stmt->bindValue(":cls", $value);
      $stmt->bindValue(":id", $prod);
      $stmt->execute();
    }
  }

  function editClaveUnidadSat($value, $prod)
  {
    $con = new conectar();
    $db = $con->getDb();

    $query = sprintf("select * from info_fiscal_productos where FKProducto = :id");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":id", $prod);
    $stmt->execute();
    $rowCount = $stmt->rowCount();

    if ($rowCount > 0) {
      $query = sprintf("update info_fiscal_productos set FKClaveSATUnidad = :cls where FKProducto = :id");
      $stmt = $db->prepare($query);
      $stmt->bindValue(":cls", $value);
      $stmt->bindValue(":id", $prod);
      $stmt->execute();
    } else {
      $query = sprintf("insert into info_fiscal_productos (FKClaveSATUnidad,FKProducto) values (:cls,:id)");
      $stmt = $db->prepare($query);
      $stmt->bindValue(":cls", $value);
      $stmt->bindValue(":id", $prod);
      $stmt->execute();
    }
  }

  function updateStatusDocuments($array,$productos,$id_factura)
  {
    $con = new conectar();
    $db = $con->getDb();
    $get_data = new get_data();
    
    if(!is_array($array['tipoDocumento'])){
      $tipo_documento = (int)$array['tipoDocumento'];
    } else {
      $tipo_documento = $array['tipoDocumento'][0];
    }
    
    if(is_array($array['idDocumento'])){
      $id_documento = $array['idDocumento'][0];
    } else {
      $id_documento = $array['idDocumento'];
    }
    
    $referencias = [];
    switch ($tipo_documento) {
      case 1:
        $query = sprintf("update cotizacion set estatus_factura_id = :estatus_id, estatus_cotizacion_id = :estatus_cotizacion_id where PKCotizacion = :id");
        $stmt = $db->prepare($query);
        $ban1 = $stmt->execute([":estatus_id" => 1, ":estatus_cotizacion_id" => 2, ":id" => $id_documento]);
          
        $queryEstatus = sprintf("update orden_pedido_por_sucursales set estatus_factura_id = :estatus where numero_cotizacion= :id and empresa_id = :id_empresa");
        $stmtEstatus = $db->prepare($queryEstatus);
        $stmtEstatus->bindValue(":estatus", 2);
        $stmtEstatus->bindValue(":id", $id_documento);
        $stmtEstatus->bindValue(":id_empresa", $_SESSION['IDEmpresa']);
        $ban1 = $stmtEstatus->execute();
        break;
      case 2:
        $query = sprintf("update ventas_directas set FKEstatusVenta = :estatus_id,estatus_factura_id = :estatus_factura_id where PKVentaDirecta = :id");
        $stmt = $db->prepare($query);
        $ban1 = $stmt->execute([":estatus_id" => 2, ":estatus_factura_id" => 2, ":id" => $id_documento]);
          
        $queryEstatus = sprintf("update orden_pedido_por_sucursales set estatus_factura_id = :estatus where numero_venta_directa= :id and empresa_id = :id_empresa");
        $stmtEstatus = $db->prepare($queryEstatus);
        $stmtEstatus->bindValue(":estatus", 2);
        $stmtEstatus->bindValue(":id", $id_documento);
        $stmtEstatus->bindValue(":id_empresa", $_SESSION['IDEmpresa']);
        $ban1 = $stmtEstatus->execute();

        $queryRef = sprintf("select referencia_cotizacion from ventas_directas where PKVentaDirecta = :id");
        $stmtRef = $db->prepare($queryRef);
        $stmtRef->execute([":id" => $id_documento]);
        $referenciaCotizacion = $stmtRef->fetch(PDO::FETCH_ASSOC);

        if($referenciaCotizacion != null){
          $stmt = $db->prepare('UPDATE cotizacion c INNER JOIN ventas_directas vd ON c.id_cotizacion_empresa = vd.referencia_cotizacion SET c.estatus_factura_id = 2 WHERE vd.PKVentaDirecta = :id AND c.empresa_id = ' . $_SESSION['IDEmpresa']);
          $stmt->bindValue(':id', $id_documento);
          $stmt->execute();
        }
        break;
      case 3:
        $aux = $get_data->getIdSalidaForFolio($array['idDocumento']);
        $referencias = $aux['salidas_id'];

        for ($i = 0; $i < count($referencias); $i++) {
          $query1 = sprintf("insert into salidas_facturadas (salida_id,factura_id) values (:salida,:factura)");
          $stmt1 = $db->prepare($query1);
          $stmt1->bindValue(":salida", $referencias[$i]);
          $stmt1->bindValue(":factura", $id_factura);
          $ban1 = $stmt1->execute();

          $get_data->getIsProd($referencias[$i], $aux['salidas_folio'][$i]);

          /* if($get_data->getIsProd($referencias[$i], $aux['salidas_folio'][$i]) == 1){
            $query2 = sprintf("select cantidad_facturada from datos_producto_facturacion_temp where referencia = :id");
            $stmt2 = $db->prepare($query2);
            $stmt2->bindValue(":id", $referencias[$i]);
            $stmt2->execute();
            $cantidad_facturada_final = $stmt2->fetchAll();
            
            $query3 = sprintf("update inventario_salida_por_sucursales set cantidad_facturada = :cantidad, estatus = 2 where id = :id");
            $stmt3 = $db->prepare($query3);
            $stmt3->bindValue(":cantidad", $cantidad_facturada_final[0]['cantidad_facturada']);
            $stmt3->bindValue(":id", $referencias[$i]);
            $ban1 = $stmt3->execute();
          }else{
            $query4 = sprintf("update movimientos_salidas_servicios_sin_inventario set estatus = 2 where PKMovServ = :id");
            $stmt4 = $db->prepare($query4);
            $stmt4->bindValue(":id", $referencias[$i]);
            $ban1 = $stmt4->execute();
          } */

          //$query4 = sprintf("select * from inventario_salida_por_sucursales where id = :id ")
        }

        $pedidos = $get_data->getPedidos($referencias,$tipo_documento,$aux['salidas_folio']);

        for ($i=0; $i < count($pedidos); $i++) { 
          $query = sprintf("select 
                              numero_cotizacion cot,
                              numero_venta_directa vd
                            from orden_pedido_por_sucursales
                            where id = :id
                          ");
          $stmt = $db->prepare($query);
          $stmt->execute(["id"=>$pedidos[$i]]);
          $doc_ref_ped = $stmt->fetchAll(PDO::FETCH_OBJ);

          $query = sprintf("select sum(dp.cantidad_pedida) cantidad_pedido,
                            p.numero_cotizacion cot,
                            p.numero_venta_directa vd
                            from detalle_orden_pedido_por_sucursales dp
                              inner join orden_pedido_por_sucursales p on dp.orden_pedido_id = p.id
                            where dp.orden_pedido_id = :id");
          $stmt = $db->prepare($query);
          $stmt->bindValue(":id", $pedidos[$i]);
          $stmt->execute();

          $arr_detalle_pedido = $stmt->fetchAll();
          $cantidad_pedido = (int)$arr_detalle_pedido[0]['cantidad_pedido'];

          $query = sprintf("select sum(cantidad) cantidad from (							
                              select 
                                sum(cantidad_facturada) cantidad
                              from inventario_salida_por_sucursales s
                              where orden_pedido_id = :id
                              
                                union all
                                            
                              select 
                                sum(cantidad_surtida) cantidad
                              from movimientos_salidas_servicios_sin_inventario
                              where FKOrdenPedido = :id2 and estatus = 2
                            ) as tabla;");
          $stmt = $db->prepare($query);
          $stmt->bindValue(":id", $pedidos[$i]);
          $stmt->bindValue(":id2", $pedidos[$i]);
          $stmt->execute();

          $arr_pedidos = $stmt->fetchAll();

          $cantidad_salida = (int)$arr_pedidos[0]['cantidad'];

          if ($cantidad_salida < $cantidad_pedido) {
            $estatus_factura = 5;
            $estatus_factura_doc = 5;
          } else {
            $estatus_factura = 1;
            $estatus_factura_doc = 1;
          }

          $queryPedidos = sprintf("update orden_pedido_por_sucursales set estatus_factura_id = :estatus_factura where id = :id");
          $stmtPedidos = $db->prepare($queryPedidos);
          $stmtPedidos->bindValue(":estatus_factura",$estatus_factura);
          $stmtPedidos->bindValue(":id",$pedidos[$i]);

          $stmtPedidos->execute();

          if($doc_ref_ped[0]->cot !== null && $doc_ref_ped[0]->cot!== ''){
            $query = sprintf("update cotizacion set estatus_factura_id = :estatus_factura where PKCotizacion = :id");
            $stmt = $db->prepare($query);
            $stmt->execute([":id"=>$doc_ref_ped[0]->cot,":estatus_factura"=>$estatus_factura_doc]);
          } else if($doc_ref_ped[0]->vd !== null && $doc_ref_ped[0]->vd !== ''){
            $query = sprintf("update ventas_directas set estatus_factura_id = :estatus_factura where PKVentaDirecta = :id");
            $stmt = $db->prepare($query);
            $stmt->execute([":id"=>$doc_ref_ped[0]->vd,":estatus_factura"=>$estatus_factura_doc]);
          }
        }
        
        break;
      case 4:
        $pedidos = [];
        $query5 = sprintf("select salida_id from remisiones where id = :id");
        $stmt5 = $db->prepare($query5);
        $stmt5->bindValue(":id", $productos[0]['referencia']);
        $stmt5->execute();

        $array_salidas = $stmt5->fetchAll();
        $array_salidas = explode(",", $array_salidas[0]['salida_id']);

        $aux = $get_data->getIdSalidaForFolio($array_salidas[0]);
        $referencias = $aux['salidas_id'];

        $query5 = sprintf("update remisiones set estatus = 1 where id = :id");
        $stmt5 = $db->prepare($query5);
        $stmt5->bindValue(":id", $productos[0]['referencia']);
        $stmt5->execute();

        for ($i = 0; $i < count($array_salidas); $i++) {

          $query5 = sprintf("update inventario_salida_por_sucursales set estatus = 2 where id = :id");
          $stmt5 = $db->prepare($query5);
          $stmt5->bindValue(":id", $array_salidas[$i]);
          $stmt5->execute();
        }
        $pedidos = $get_data->getPedidos($referencias,$tipo_documento);

        for ($i = 0; $i < count($pedidos); $i++) {
          $query = sprintf("select sum(dp.cantidad_pedida) cantidad_pedido from detalle_orden_pedido_por_sucursales dp
                          where dp.orden_pedido_id = :id");
          $stmt = $db->prepare($query);
          $stmt->bindValue(":id", $pedidos[$i]);
          $stmt->execute();

          $cantidad_pedido = (int)$stmt->fetch()['cantidad_pedido'];

          $query = sprintf("select sum(cantidad) cantidad_remisionada from inventario_salida_por_sucursales where orden_pedido_id = :id");
          $stmt = $db->prepare($query);
          $stmt->bindValue(":id", $pedidos[$i]);
          $stmt->execute();

          $cantidad_remisionada = (int)$stmt->fetch()['cantidad_remisionada'];

          if ($cantidad_remisionada < $cantidad_pedido) {
            $data = 5;
          } else {
            $data = 1;
          }
          
          $queryEstatus = sprintf("update orden_pedido_por_sucursales set estatus_factura_id = :estatus where id= :id");
          $stmtEstatus = $db->prepare($queryEstatus);
          $stmtEstatus->bindValue(":estatus", $data);
          $stmtEstatus->bindValue(":id", $pedidos[$i]);
          $ban1 = $stmtEstatus->execute();
        }
        break;
        case 0:
          $ban1 = true;
          break;
    }
    return $ban1;
  }

  function updateStatusDocumentsCancel($id_factura)
  {
    $con = new conectar();
    $db = $con->getDb();
    $get_data = new get_data();
    
    $query = sprintf("select tipo, referencia from facturacion where id = :id");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":id",$id_factura);
    $stmt->execute();
    $arr = $stmt->fetchAll(PDO::FETCH_OBJ);
    //echo json_encode($arr);
    $tipo_documento = $arr[0]->tipo;
    
    if(is_array($arr[0]->referencia)){
      $id_documento = $arr[0]->referencia[0];
    } else {
      $id_documento = $arr[0]->referencia;
    }

    $referencias = [];
    switch ($tipo_documento) {
      case 1:

        $query = sprintf("update cotizacion set estatus_factura_id = estatus_factura_id_old, estatus_cotizacion_id = estatus_cotizacion_id_old where PKCotizacion = :id");
        $stmt = $db->prepare($query);
        $ban1 = $stmt->execute([":id" => $id_documento]);
        break;
      case 2:

        $query = sprintf("update ventas_directas set FKEstatusVenta = estatus_venta_old, estatus_factura_id = estatus_factura_id_old, estatus_cuentaCobrar = 1, estatus_cuentaCobrar_old = 1, saldo_insoluto_venta = Importe where PKVentaDirecta = :id");
        $stmt = $db->prepare($query);
        $ban1 = $stmt->execute([":id" => $id_documento]);

        if($ban1){
          //una vez cancelada la factura, se eliminan los pagos relacionados de la venta
          $query2 = sprintf("DELETE from movimientos_cuentas_bancarias_empresa where id_factura = :id and tipo_CuentaCobrar = 1 and estatus = 2 and id_factura_relacion_pagoVenta = :id2");
          $stmt2 = $db->prepare($query2);
          $stmt2->bindValue(":id", $id_documento);
          $stmt2->bindValue(":id2", $id_factura);
          $ban1 = $stmt2->execute();
        } 
        break;
      case 3:
        $ref = explode(',', $arr[0]->referencia);       
        $aux = $get_data->getIdSalidaForFolio($ref);
        $referencias = $aux['salidas_id'];
        
        for ($i = 0; $i < count($referencias); $i++) {

          $query3 = sprintf("update inventario_salida_por_sucursales set cantidad_facturada = :cantidad, estatus = 0 where id = :id and folio_salida = :folio");
          $stmt3 = $db->prepare($query3);
          $stmt3->bindValue(":cantidad", 0);
          $stmt3->bindValue(":id", $referencias[$i]);
          $stmt3->bindValue(":folio", $aux['salidas_folio'][$i]);
          $ban1 = $stmt3->execute();

          $query3 = sprintf("update movimientos_salidas_servicios_sin_inventario set estatus = 0 where PKMovServ = :id and FKSalida = :folio");
          $stmt3 = $db->prepare($query3);
          $stmt3->bindValue(":id", $referencias[$i]);
          $stmt3->bindValue(":folio", $aux['salidas_folio'][$i]);
          $ban1=$stmt3->execute();

        }
        
        $pedidos = $get_data->getPedidos($referencias,$tipo_documento,$aux['salidas_folio']);

        for ($i=0; $i < count($pedidos); $i++) { 
          $queryPedidos = sprintf("update orden_pedido_por_sucursales set estatus_factura_id = estatus_factura_id_old, estatus_orden_pedido_id = estatus_orden_pedido_id_old where id = :id");
          $stmtPedidos = $db->prepare($queryPedidos);
          //$stmtPedidos->bindValue(":estatus_factura",3);
          $stmtPedidos->bindValue(":id",$pedidos[$i]);
          $stmtPedidos->execute();

          $query = sprintf("select 
                              numero_cotizacion cot,
                              numero_venta_directa vd
                            from orden_pedido_por_sucursales
                            where id = :id
                          ");
          $stmt = $db->prepare($query);
          $stmt->execute(["id"=>$pedidos[$i]]);
          $doc_ref_ped = $stmt->fetchAll(PDO::FETCH_OBJ);
          
          if($doc_ref_ped[0]->cot !== null && $doc_ref_ped[0]->cot!== ''){
            $query = sprintf("update cotizacion set estatus_factura_id = estatus_factura_id_old where PKCotizacion = :id");
            $stmt = $db->prepare($query);
            $stmt->execute([":id"=>$doc_ref_ped[0]->cot]);
          } else if($doc_ref_ped[0]->vd !== null && $doc_ref_ped[0]->vd !== ''){
            $query = sprintf("update ventas_directas set estatus_factura_id = estatus_factura_id_old where PKVentaDirecta = :id");
            $stmt = $db->prepare($query);
            $stmt->execute([":id"=>$doc_ref_ped[0]->vd]);

            //una vez cancelada la factura, se eliminan los pagos relacionados de la venta 
            $query2 = sprintf("DELETE from movimientos_cuentas_bancarias_empresa where id_factura = :id and tipo_CuentaCobrar = 1 and estatus = 2 and id_factura_relacion_pagoVenta = :id2");
            $stmt2 = $db->prepare($query2);
            $stmt2->bindValue(":id", $doc_ref_ped[0]->vd);
            $stmt2->bindValue(":id2", $id_factura);
            $stmt2->execute(); 
            //en trigger suma el importe al saldo insoluto
            
          }
        }
        break;
      case 4:
        $pedidos = [];
        $query5 = sprintf("select salida_id from remisiones where id = :id");
        $stmt5 = $db->prepare($query5);
        $stmt5->bindValue(":id", $id_documento);
        $stmt5->execute();

        $array_salidas = $stmt5->fetchAll();
        $array_salidas = explode(",", $array_salidas[0]['salida_id']);

        $query5 = sprintf("update remisiones set estatus = 0 where id = :id");
        $stmt5 = $db->prepare($query5);
        $stmt5->bindValue(":id", $id_documento);
        $stmt5->execute();

        for ($i = 0; $i < count($array_salidas); $i++) {

          $query5 = sprintf("update inventario_salida_por_sucursales set estatus = 0 where id = :id");
          $stmt5 = $db->prepare($query5);
          $stmt5->bindValue(":id", $array_salidas[$i]);
          $stmt5->execute();
        }

        $pedidos = $get_data->getPedidos($array_salidas,$tipo_documento);

        for ($i = 0; $i < count($pedidos); $i++) {
          
          $queryEstatus = sprintf("update orden_pedido_por_sucursales set estatus_factura_id = estatus_factura_id_old, estatus_orden_pedido_id = estatus_orden_pedido_id_old where id= :id");
          $stmtEstatus = $db->prepare($queryEstatus);
          
          $stmtEstatus->bindValue(":id", $pedidos[$i]);
          $ban1 = $stmtEstatus->execute();
        }

        break;
        case 0:
          $ban1 = true;
          break;
    }
    return $ban1;
  }

  function updateInvoiceCfdi($mensaje,$id)
  {
    $con = new conectar();
    $db = $con->getDb();

    $query = sprintf("update 
                        facturacion 
                      set 
                        id_api = :id_api,
                        serie = :serie,
                        folio = :folio,
                        uuid = :uuid,
                        total_facturado = :total_facturado,
                        prefactura = 0
                      where id = :id
                        ");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":id_api",$mensaje['id']);
    $stmt->bindValue(":serie",$mensaje['series']);
    $stmt->bindValue(":folio",$mensaje['folio_number']);
    $stmt->bindValue(":uuid",$mensaje['uuid']);
    $stmt->bindValue(":total_facturado",$mensaje['total']);
    $stmt->bindValue(":id",$id);
    return $stmt->execute();
  }

  function updateExpiredDate($value, $expired_date)
  {
    $con = new conectar();
    $db = $con->getDb();

    $query = sprintf("update facturacion set fecha_vencimiento = :expired_date where id = :id"); 
    $stmt = $db->prepare($query);
    $stmt->bindValue(":expired_date",$expired_date);
    $stmt->bindValue(":id",$value);
    return $stmt->execute();
  }

  function updateSeller($value, $seller)
  {
    $con = new conectar();
    $db = $con->getDb();

    $query = sprintf("update facturacion set empleado_id = {$seller} where id = {$value}");
    $stmt = $db->prepare($query);
    return $stmt->execute();
  }

  function updateStockProduct($value,$value1)
  {
    $con = new conectar();
    $db = $con->getDb();

    $query = sprintf("update existencia_por_productos set existencia = :stock where id = :id");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":stock",$value);
    $stmt->bindValue(":id",$value1);
    return $stmt->execute();
  }

  function updateStatusCot($id){
    $con = new conectar();
    $db = $con->getDb();

    $query = sprintf('UPDATE cotizacion SET estatus_cotizacion_id = 2,flujo_almacen = 1 WHERE PKCotizacion = :id AND empresa_id = :empresa_id');
    $stmt = $db->prepare($query);
    $stmt->bindValue(':empresa_id',$_SESSION['IDEmpresa']);
    $stmt->bindValue(':id',$id);
    $stmt->execute();
  }

  function updateStockProductCancel($value, $value1, $value2, $value3)
  {
    $con = new conectar();
    $db = $con->getDb();

    $query = sprintf("update existencia_por_productos e set 
                        e.existencia = (e.existencia + :cant)
                      where e.clave_producto = :clave and e.sucursal_id = :sucursal and e.numero_lote = :lote");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":cant",$value);
    $stmt->bindValue(":clave",$value1);
    $stmt->bindValue(":sucursal",$value2);
    $stmt->bindValue(":lote",$value3);
    return $stmt->execute();

  }

  function updateStatusTicketBySale($id)
  {
    $con = new conectar();
    $db = $con->getDb();
    $query = sprintf("update ticket_punto_venta t
                      inner join relacion_tickets_ventas rtv ON t.id = rtv.ticket_id
                      set t.estatus = 3 
                      where rtv.venta_id = :id");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":id",$id);
    $stmt->execute();
  }

  function updateActionPrice($value)
  {
    $con = new conectar();
    $db = $con->getDb();
    $update_data = new edit_data();
    $save_data = new save_data();

    $data = json_decode($value);
    //producto_id,precio_unitario,cliente,precio_especial
    foreach($data as $r){
        if($r->precio_especial === 1){
            $query = sprintf("select costoEspecial price from costo_especial_producto_cliente where FKProducto = :producto_id and FKCliente = :cliente_id");
            $stmt = $db->prepare($query);
            $stmt->bindValue(":producto_id",$r->producto_id);
            $stmt->bindValue(":cliente_id",$r->cliente);
            $stmt->execute();
            $arr = $stmt->fetchAll(PDO::FETCH_OBJ);

            if(count($arr) > 0){
                return $update_data->updatePrice($r->producto_id,$r->precio_unitario,$r->cliente);
            } else {
                return $save_data->savePrice($r->producto_id,$r->precio_unitario,$r->cliente);
            }
        }
    }
    
    
  }

  function updatePrice($id,$price,$client)
  {
    $con = new conectar();
    $db = $con->getDb();

    $query = sprintf("update costo_especial_producto_cliente set costoEspecial = :price where FKProducto = :producto_id and FKCliente = :cliente_id");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":price",$price);
    $stmt->bindValue(":producto_id",$id);
    $stmt->bindValue(":cliente_id",$client);
    return $stmt->execute();
  }

//   function updateStatusCotStock($value)
//   {
//     $con = new conectar();
//     $db = $con->getDb();

//     $query = sprintf("update cotizacion set estatus_factura_id = 2, estatus_cotizacion_id = 7 where PKCotizacion = :id");
//     $stmt = $db->prepare($query);
//     $stmt->bindValue(":id",$value);
//     $stmt->execute();
//   }

//   function updateStatusVentaStock($value)
//   {
//     $con = new conectar();
//     $db = $con->getDb();

//     $query = sprintf("update ventas_directas set FKEstatusVenta = 2");
//   }
}

class send_data
{
  function sendEmail($value, $data)
  {
    $con = new conectar();
    $db = $con->getDb();

    $ruta_api = "../../../";
    require_once $ruta_api . "include/functions_api_facturation.php";
    require_once $ruta_api . 'vendor/facturapi/facturapi-php/src/Facturapi.php';
    $api = new API();

    $query = sprintf("select key_company_api from empresas where PKEmpresa = :id");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":id", $_SESSION['IDEmpresa']);
    $stmt->execute();
    $emp = $stmt->fetchAll();

    $query = sprintf("select id_api,uuid from facturacion where id= :id");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":id", $value);
    $stmt->execute();
    $fact = $stmt->fetchAll();


    if (count($data) < 2) {
      $mensaje = $api->sendEmailInvoice($emp[0]['key_company_api'], $fact[0]['id_api'], $data[0]);
    } else {
      for ($i = 0; $i < count($data); $i++) {
        $mensaje = $api->sendEmailInvoice($emp[0]['key_company_api'], $fact[0]['id_api'], $data[$i]);
      }
    }

    return $mensaje;
  }
}

function sendAutoEmails($cliente_id, $id_factura){
  $con = new conectar();
  $db = $con->getDb();
  $send_data = new send_data();
  
  $query = sprintf("SELECT 
                  cl.PKCliente as id,
                  dcc.Email as email,
                    dcc.EmailFacturacion
                FROM
                clientes cl
                LEFT JOIN dato_contacto_cliente dcc ON dcc.FKCliente = cl.PKCliente
                WHERE cl.PKCliente=:id
                AND dcc.EmailFacturacion=1
                  ");
  $stmt = $db->prepare($query);
  $stmt->bindValue(":id", $cliente_id);
  $stmt->execute();
  $row = $stmt->fetchAll();    

  $correosContactos = array();
  foreach($row as $rowCorreos){
    array_push($correosContactos, $rowCorreos['email']);
  }

  $send_data->sendEmail($id_factura, $correosContactos);
  
}