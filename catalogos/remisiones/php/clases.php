<?php
  session_start();
  date_default_timezone_set('America/Mexico_City');

  class conectar{//Llamado al archivo de la conexión.
    function getDb(){
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

  class get_data{
    function getRemissionsTable(){
      $con = new conectar();
      $db = $con->getDb();
      $envVariables = GetEvn();
      $appUrl = $envVariables['server'];
      $table = "";

      $query = sprintf("SELECT r.id, r.folio, cl.PKCliente, cl.razon_social, r.subtotal, r.total, r.estatus, r.fecha_creacion fecha FROM remisiones r
                        INNER JOIN clientes cl ON r.cliente_id = cl.PKCliente
                        WHERE r.empresa_id = :id");
      $stmt = $db->prepare($query);
      $stmt->bindValue(":id",$_SESSION['IDEmpresa']);
      $stmt->execute();
      $array = $stmt->fetchAll();

      foreach ($array as $r) {

        $fecha = ($r['fecha'] !== "" && $r['fecha'] !== "0000-00-00 00:00:00" && $r['fecha'] !== null) ? date("d-m-Y H:i:s",strtotime($r['fecha'])) : $fecha = "";

        switch($r['estatus']){
          case 0:
            $estatus = "Activa";
          break;
          case 1:
            $estatus = "Facturada";
          break;
          case 2:
            $estatus = "Cancelada";
          break;
        }

        //link para detalle del cliente
        $r['razon_social'] = '<a style=\"cursor:pointer\" href=\"'.$appUrl.'catalogos/clientes/catalogos/clientes/detalles_cliente.php?c='.$r['PKCliente'].'\">'.$r['razon_social'].'</a>';

        $html = "<a id='detalle_remision' href='#' data-id='". $r['id'] ."'> ".sprintf("%011d", $r['folio'])." </a>";
        $table .= '{
          "id" : "' . $r['id'] . '",
          "Folio" : "' . $html . '",
          "Razon social" : "' . $r['razon_social'] . '",
          "Subtotal" : "' . number_format($r['subtotal'],2) . '",
          "Total" : "' . number_format($r['total'],2) . '",
          "Estatus" : "' . $estatus . '",
          "Fecha" : "' . $fecha . '"
        },';

      }
      $table = substr($table,0,strlen($table)-1);

      $con = "";
      $stmt = "";
      $db = "";

      return '{"data":['.$table.']}';
    }

    function getClients(){
      $con = new conectar();
      $db = $con->getDb();
      $arr = [];

      $query = sprintf("SELECT DISTINCT cl.PKCliente id,cl.razon_social texto, isps.estatus FROM orden_pedido_por_sucursales opps
                        INNER JOIN clientes cl ON opps.cliente_id = cl.PKCliente
                        INNER JOIN inventario_salida_por_sucursales isps ON opps.id = isps.orden_pedido_id
                        WHERE opps.empresa_id = :empresa_id and 
                        (opps.estatus_orden_pedido_id = 3 or
                        opps.estatus_orden_pedido_id = 11)
                       ");
      $stmt = $db->prepare($query);
      $stmt->bindValue(":empresa_id",$_SESSION['IDEmpresa']);
      $stmt->execute();
      $aux = $stmt->fetchAll();

      foreach($aux as $r){
        if($r['estatus'] === 0 OR $r['estatus'] === null)
          array_push($arr,
            array(
              "id"=>$r['id'],
              "texto"=>$r['texto']
            )
          );
      }
      
      return $arr;
    }

    function getOrdenesPedido($value){
      $con = new conectar();
      $db = $con->getDb();

      $query = sprintf("SELECT DISTINCT opps.id,opps.id_orden_pedido_empresa FROM orden_pedido_por_sucursales opps
                          INNER JOIN inventario_salida_por_sucursales s on s.orden_pedido_id = opps.id
                        WHERE opps.empresa_id = :empresa_id AND 
                        opps.cliente_id = :cliente and
                        (opps.estatus_orden_pedido_id = 3 or
                        opps.estatus_orden_pedido_id = 11) and
                        s.estatus = 0;");
      $stmt = $db->prepare($query);
      $stmt->bindValue(":empresa_id",$_SESSION['IDEmpresa']);
      $stmt->bindValue(":cliente",$value);
      $stmt->execute();

      $array =  $stmt->fetchAll(PDO::FETCH_OBJ);

      if(count($array) > 0){  
        for ($i=0; $i < count($array); $i++) { 
          $id_orden_pedido_empresa = sprintf("%011d", $array[$i]->id_orden_pedido_empresa);
          $aux[$i] = [
            'id'=>$array[$i]->id,
            'texto'=>$id_orden_pedido_empresa];
        }
      } else {
        $aux = "";
      }
      
      $con = "";
      $stmt = "";
      $db = "";

      return $aux;

    }

    function getSalidas($value){
      $con = new conectar();
      $db = $con->getDb();
      
      $query = sprintf("SELECT DISTINCT s.folio_salida texto 
                        FROM inventario_salida_por_sucursales s 
                        INNER JOIN orden_pedido_por_sucursales o ON s.orden_pedido_id = o.id
                        WHERE orden_pedido_id = :id AND s.estatus = 0");
      $stmt = $db->prepare($query);
      $stmt->bindValue(":id",$value);
      $stmt->execute();
      
      return $stmt->fetchAll(PDO::FETCH_OBJ);

    }

    function getProductosSalidas($value){
      $con = new conectar();
      $db = $con->getDb();
      $table = "";
      $impuestos = "";
      $referencias = [];
      $prod = [];
      $txtReferencia = "";

      $query0 = sprintf("select * from datos_producto_remision_temp where usuario_id = :id");
      $stmt0 = $db->prepare($query0);
      $stmt0->bindValue(":id",$_SESSION['PKUsuario']);
      $stmt0->execute();
      $rowCount = $stmt0->rowCount();

      if($rowCount > 0){
        
        $query0 = sprintf("delete from datos_producto_remision_temp where usuario_id = :id");
        $stmt0 = $db->prepare($query0);
        $stmt0->bindValue(":id",$_SESSION['PKUsuario']);
        $stmt0->execute();
      }

      $data = json_decode($value);

      for ($i=0; $i < count($data); $i++) { 
        if(!in_array($data[$i],$referencias)){
          array_push($referencias,$data[$i]); 
        }
      }
      for ($j=0; $j < count($referencias); $j++) { 
        $txtReferencia .= $referencias[$j] . ",";
      }
      
      for ($i=0; $i < count($data); $i++) {
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
                            cvp.CostoGeneral precio_unitario,
                            ifp.FKClaveSAT
                          from inventario_salida_por_sucursales sa
                            inner join productos pr on sa.clave = pr.ClaveInterna
                            left join costo_venta_producto cvp on pr.PKProducto = cvp.FKProducto
                            left join info_fiscal_productos ifp on pr.PKProducto = ifp.FKProducto
                            left join claves_sat_unidades csu on ifp.FKClaveSATUnidad = csu.PKClaveSATUnidad
                          where folio_salida = :folio and pr.empresa_id = :id_empresa");
        $stmt = $db->prepare($query);
        $stmt->bindValue(":folio",$data[$i]);
        $stmt->bindValue(":id_empresa",$_SESSION['IDEmpresa']);
        $stmt->execute();

        $aux = $stmt->fetchAll();

        if(count($prod) > 0){
          for ($k=0; $k < count($aux); $k++) { 
            if(in_array($aux[$k]['producto_id'],array_column($prod, 'producto_id'))){
              if(in_array($aux[$k]['lote'],array_column($prod, 'lote'))){
                $prod[array_search($aux[$k]['producto_id'],array_column($prod, 'producto_id'))]['cantidad'] += $aux[$k]['cantidad'];
              } else if(in_array($aux[$k]['serie'],array_column($prod, 'serie'))){
                $prod[array_search($aux[$k]['producto_id'],array_column($prod, 'producto_id'))]['cantidad'] += $aux[$k]['cantidad'];
              } else {
                $prod[array_search($aux[$k]['producto_id'],array_column($prod, 'producto_id'))]['cantidad'] = $aux[$k]['cantidad'];
              }
            } else {
              array_push(
                $prod,
                array(
                  "id"=>$aux[$k]['id'],
                  "referencia"=>$txtReferencia,
                  "producto_id"=>$aux[$k]['producto_id'],
                  "clave"=>$aux[$k]['clave'], 
                  "lote"=>$aux[$k]['lote'],
                  "caducidad"=>$aux[$k]['caducidad'],
                  "serie"=>$aux[$k]['serie'],
                  "cantidad"=>$aux[$k]['cantidad'], 
                  "FKClaveSATUnidad"=>$aux[$k]['FKClaveSATUnidad'],
                  "precio_unitario"=>$aux[$k]['precio_unitario'],
                  "FKClaveSAT"=>$aux[$k]['FKClaveSAT']
                )
              );
            }
          }
        } else {
          for ($k=0; $k < count($aux); $k++) { 
            array_push(
              $prod,
              array(
                "id"=>$aux[$k]['id'],
                "referencia"=>$txtReferencia,
                "producto_id"=>$aux[$k]['producto_id'],
                "clave"=>$aux[$k]['clave'], 
                "lote"=>$aux[$k]['lote'],
                "caducidad"=>$aux[$k]['caducidad'],
                "serie"=>$aux[$k]['serie'],
                "cantidad"=>$aux[$k]['cantidad'], 
                "FKClaveSATUnidad"=>$aux[$k]['FKClaveSATUnidad'],
                "precio_unitario"=>$aux[$k]['precio_unitario'],
                "FKClaveSAT"=>$aux[$k]['FKClaveSAT']
              )
            );
          }
        }
      }
      $txtReferencia = substr($txtReferencia,0,strlen($txtReferencia)-1);
      
      $cont = 0;
      foreach($prod as $r){
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

        $query1 = sprintf("select imp.PKImpuesto id,imp.Nombre nombre,ipr.Tasa tasa from inventario_salida_por_sucursales sa
                            inner join productos pro on sa.clave = pro.ClaveInterna
                            inner join info_fiscal_productos ifp on pro.PKProducto = ifp.FKProducto
                            inner join impuestos_productos ipr on ifp.PKInfoFiscalProducto = ipr.FKInfoFiscalProducto
                            inner join impuesto imp on ipr.FKImpuesto = imp.PKImpuesto
                          where sa.id = :id and pro.PKProducto = :producto");
        $stmt1 = $db->prepare($query1);
        $stmt1->bindValue(":id",$r['id']);
        $stmt1->bindValue(":producto",$r['producto_id']);
        $stmt1->execute();

        $arr1 =  $stmt1->fetchAll();

        if(count($arr1) > 0){
          foreach ($arr1 as $r1) {
            switch($r1['id']){
              case 1:
                $tasa_iva = $r1['tasa'];
                $monto_iva = ($importe * ($r1['tasa']/100));
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
                $monto_ieps = ($importe * ($r1['tasa']/100));
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
                $monto_ish = ($importe * ($r1['tasa']/100));
                $impuestos_importe += $monto_ish;
              break;
              case 5:
                $tasa_iva_exento = $r1['tasa'];
                $monto_iva_exento = ($importe * ($r1['tasa']/100));
                $impuestos_importe += $monto_iva_exento;
              break;
              case 6:
                $tasa_iva_retenido = $r1['tasa'];
                $monto_iva_retenido = ($importe * ($r1['tasa']/100));
                $impuestos_importe -= $monto_iva_retenido;
              break;
              case 7:
                $tasa_isr_retenido = $r1['tasa'];
                $monto_isr_retenido = ($importe * ($r1['tasa']/100));
                $impuestos_importe -= $monto_isr_retenido;
              break;
              case 8:
                $tasa_isn_local = $r1['tasa'];
                $monto_isn_local = ($importe * ($r1['tasa']/100));
                $impuestos_importe += $monto_isn_local;
              break;
              case 9:
                $tasa_cedular = $r1['tasa'];
                $monto_cedular = ($importe * ($r1['tasa']/100));
                $impuestos_importe += $monto_cedular;
              break;
              case 10:
                $tasa_al_millar = $r1['tasa'];
                $monto_al_millar = ($importe * ($r1['tasa']/100));
                $impuestos_importe += $monto_al_millar;
              break;
              case 11:
                $tasa_funcion_publica = $r1['tasa'];
                $monto_funcion_publica = ($importe * ($r1['tasa']/100));
                $impuestos_importe += $monto_funcion_publica;
              break;
              case 12:
                $tasa_ieps_retenido = $r1['tasa'];
                $monto_ieps_retenido = ($importe * ($r1['tasa']/100));
                $impuestos_importe -= $monto_ieps_retenido;
              break;
              case 13:
                $tasa_isr_exento = $r1['tasa'];
                $monto_isr_exento = ($importe * ($r1['tasa']/100));
                $impuestos_importe += $monto_ieps_exento;
              break;
              case 14:
                $monto_isr_monto_fijo = ($importe * ($r1['tasa']/100));
                $impuestos_importe += $monto_isr_monto_fijo;
              break;
              case 15:
                $tasa_isr = $r1['tasa'];
                $monto_isr = ($importe * ($r1['tasa']/100));
                $impuestos_importe += $monto_isr;
              break;
              case 16:
                $tasa_ieps_exento = $r1['tasa'];
                $monto_ieps_exento = ($importe * ($r1['tasa']/100));
                $impuestos_importe += $monto_isr_exento;
              break;
              case 17:
                $isr_retenido_monto_fijo = ($importe * ($r1['tasa']/100));
                $impuestos_importe -= $isr_retenido_monto_fijo;
              break;
              case 18:
                $ieps_retenido_monto_fijo = ($importe * ($r1['tasa']/100));
                $impuestos_importe -= $ieps_retenido_monto_fijo;
              break;
            }
          }
          $impuestos = substr($impuestos,0,strlen($impuestos)-4);
        } else {
          $impuestos = "Sin impuestos";
        }
        $totalDoc = ($r['cantidad'] * $r['precio_unitario']) + $impuestos_importe;

        $query2 = sprintf("insert into datos_producto_remision_temp (
                            referencia,
                            tipo,
                            producto_id,
                            unidad_medida_id,
                            clave_sat_id,
                            cantidad,
                            cantidad_facturada,
                            precio_unitario,
                            numero_lote,
                            numero_serie,
                            caducidad,
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
                            ) values (
                            :referencia,
                            :tipo,
                            :producto_id,
                            :unidad_medida_id,
                            :clave_sat_id,
                            :cantidad,
                            :cantidad_facturada,
                            :precio_unitario,
                            :lote,
                            :serie,
                            :caducidad,
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
                            :id
                          )");
        $stmt2 = $db->prepare($query2);
        $stmt2->bindValue(":referencia",$txtReferencia);
        $stmt2->bindValue(":tipo",3);
        $stmt2->bindValue(":producto_id",$r['producto_id']);
        $stmt2->bindValue(":unidad_medida_id",$r['FKClaveSATUnidad']);
        $stmt2->bindValue(":clave_sat_id",$r['FKClaveSAT']);
        $stmt2->bindValue(":cantidad",$r['cantidad']);
        $stmt2->bindValue(":cantidad_facturada",$r['cantidad']);
        $stmt2->bindValue(":precio_unitario",$r['precio_unitario']);
        $stmt2->bindValue(":lote",$r['lote']);
        $stmt2->bindValue(":serie",$r['serie']);
        $stmt2->bindValue(":caducidad",$r['caducidad']);
        $stmt2->bindValue(":total_bruto",$importe);
        $stmt2->bindValue(":iva",$tasa_iva);
        $stmt2->bindValue(":importe_iva",$monto_iva);
        $stmt2->bindValue(":ieps",$tasa_ieps);
        $stmt2->bindValue(":importe_ieps",$monto_ieps);
        $stmt2->bindValue(":ieps_monto_fijo",$monto_ieps_fijo);
        $stmt2->bindValue(":ish",$tasa_ish);
        $stmt2->bindValue(":importe_ish",$monto_ish);
        $stmt2->bindValue(":iva_exento",$tasa_iva_exento);
        $stmt2->bindValue(":importe_iva_exento",$monto_iva_exento);
        $stmt2->bindValue(":iva_retenido",$tasa_iva_retenido);
        $stmt2->bindValue(":importe_iva_retenido",$monto_iva_retenido);
        $stmt2->bindValue(":isr_retenido",$tasa_isr_retenido);
        $stmt2->bindValue(":importe_isr_retenido",$monto_isr_retenido);
        $stmt2->bindValue(":isn_local",$tasa_isn_local);
        $stmt2->bindValue(":importe_isn_local",$monto_isn_local);
        $stmt2->bindValue(":cedular",$tasa_cedular);
        $stmt2->bindValue(":importe_cedular",$monto_cedular);
        $stmt2->bindValue(":al_millar",$tasa_al_millar);
        $stmt2->bindValue(":importe_al_millar",$monto_al_millar);
        $stmt2->bindValue(":funcion_publica",$tasa_funcion_publica);
        $stmt2->bindValue(":importe_funcion_publica",$monto_funcion_publica);
        $stmt2->bindValue(":ieps_retenido",$tasa_ieps_retenido);
        $stmt2->bindValue(":importe_ieps_retenido",$monto_ieps_retenido);
        $stmt2->bindValue(":isr_exento",$tasa_isr_exento);
        $stmt2->bindValue(":importe_isr_exento",$monto_isr_exento);
        $stmt2->bindValue(":isr_monto_fijo",$isr_monto_fijo);
        $stmt2->bindValue(":isr",$tasa_isr);
        $stmt2->bindValue(":importe_isr",$monto_isr);
        $stmt2->bindValue(":ieps_exento",$tasa_ieps_exento);
        $stmt2->bindValue(":importe_ieps_exento",$monto_ieps_exento);
        $stmt2->bindValue(":isr_retenido_monto_fijo",$isr_retenido_monto_fijo);
        $stmt2->bindValue(":ieps_retenido_monto_fijo",$ieps_retenido_monto_fijo);
        $stmt2->bindValue(":total_neto",$totalDoc);
        $stmt2->bindValue(":id",$_SESSION['PKUsuario']);
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
                          dpft.numero_serie,
                          dpft.caducidad
                        from datos_producto_remision_temp dpft
                          inner join productos pr on dpft.producto_id = pr.PKProducto
                          left join claves_sat_unidades csu on dpft.unidad_medida_id = csu.PKClaveSATUnidad
                        where usuario_id = :id
                        ");
      $stmt3 = $db->prepare($query3);
      $stmt3->bindValue(":id",$_SESSION['PKUsuario']);
      $stmt3->execute();

      $detalleProducto = $stmt3->fetchAll();

      $alertaSat = "";
      
      foreach($detalleProducto as $r){

        if($r['numero_lote'] !== "" && $r['numero_lote'] !== null){
          if($r['caducidad'] !== "" && $r['caducidad'] !== null && $r['caducidad'] !== "0000-00-00"){
            $descripcion = $r['nombre'] . "<br>Lote: " . $r['numero_lote'] . " Caducidad: " . $r['caducidad'];
          }else{
            $descripcion = $r['nombre'] . "<br>Lote: " . $r['numero_lote'];
          }
        } else if($r['numero_serie'] !== "" && $r['numero_serie'] !== null){
          $descripcion = $r['nombre'] . "<br>Serie: " . $r['numero_serie'];
        } else {
          $descripcion = $r['nombre'];
        }

        $impuestos = "";
        $claveInterna = ($r['clave'] !== "" && $r['clave'] !== null) ? $r['clave'] : "S/C";

        $alertaSat = ($r['sat_id'] !== null && $r['sat_id'] !== "" && $r['sat_id'] !== 1) ? "" : '<img id=\"satAlert\" src=\"../../img/icons/ICONO ALERTA_Mesa de trabajo 1.svg\" style=\"width: 25px\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"No se asignó una clave SAT\">';

        //$sat = (count($arrSat) > 0 && $arrSat['clave_sat'] !== null && $arrSat['clave_sat'] !== "") ? $claveInterna : $alertaSat . "  " .$claveInterna;
        $idUnidadMedida =($r['id_unidad_medida'] !== "" && $r['id_unidad_medida'] !== null) ? $r['id_unidad_medida'] : "";
        $unidadMedida = ($r['unidad_medida'] !== "" && $r['unidad_medida'] !== null) ? $r['unidad_medida'] : "N/A";
        $cantidad = $r['cantidad_facturada'];
        
        if($r['iva'] !== "" && $r['iva'] !== null) {
          $impuestos .= "IVA " . $r['iva'] . "%: " . number_format($r['importe_iva'],2) . "<br>";
        }
        if($r['ieps'] !== "" && $r['ieps'] !== null) {
          $impuestos .= "IEPS " . $r['ieps'] . "%: " . number_format($r['importe_ieps'],2) . "<br>";
        }
        if($r['ieps_monto_fijo'] !== "" && $r['ieps_monto_fijo'] !== null) {
          $impuestos .= "IEPS (Monto fijo): "  . number_format($r['ieps_monto_fijo'],2) . "<br>";
        }
        if($r['ish'] !== "" && $r['ish'] !== null) {
          $impuestos .= "ISH " . $r['ish'] . "%: " . number_format($r['importe_ish'],2) . "<br>";
        }
        if($r['iva_exento'] !== "" && $r['iva_exento'] !== null) {
          $impuestos .= "IVA Exento " . $r['iva_exento'] . "%: " . number_format($r['importe_iva_exento'],2) . "<br>";
        }
        if($r['iva_retenido'] !== "" && $r['iva_retenido'] !== null) {
          $impuestos .= "IVA Exento " . $r['iva_retenido'] . "%: " . number_format($r['importe_iva_retenido'],2) . "<br>";
        }
        if($r['isr_retenido'] !== "" && $r['isr_retenido'] !== null) {
          $impuestos .= "ISR Retenido " . $r['isr_retenido'] . "%: " . number_format($r['importe_isr_retenido'],2) . "<br>";
        }
        if($r['isn_local'] !== "" && $r['isn_local'] !== null) {
          $impuestos .= "ISN (Local) " . $r['isn_local'] . "%: " . number_format($r['importe_isn_local'],2) . "<br>";
        }
        if($r['cedular'] !== "" && $r['cedular'] !== null) {
          $impuestos .= "Cedular " . $r['cedular'] . "%: " . number_format($r['importe_cedular'],2) . "<br>";
        }
        if($r['al_millar'] !== "" && $r['al_millar'] !== null) {
          $impuestos .= "5 al millar (Local) " . $r['al_millar'] . "%: " . number_format($r['importe_al_millar'],2) . "<br>";
        }
        if($r['funcion_publica'] !== "" && $r['funcion_publica'] !== null) {
          $impuestos .= "Función Pública " . $r['funcion_publica'] . "%: " . number_format($r['importe_funcion_publica'],2) . "<br>";
        }
        if($r['ieps_retenido'] !== "" && $r['ieps_retenido'] !== null){
          $impuestos .= "IEPS Retenido " . $r['ieps_retenido'] . "%: " . number_format($r['importe_ieps_retenido'],2) . "<br>";
        }
        if($r['isr_exento'] !== "" && $r['isr_exento'] !== null){
          $impuestos .= "ISR Exento " . $r['isr_exento'] . "%: " . number_format($r['importe_isr_exento'],2) . "<br>";
        }
        if($r['isr_monto_fijo'] !== "" && $r['isr_monto_fijo'] !== null){
          $impuestos .= "ISR (Monto fijo) : " . number_format($r['isr_monto_fijo'],2) . "<br>";
        }
        if($r['isr'] !== "" && $r['isr'] !== null){
          $impuestos .= "ISR " . $r['isr'] . "%: " . number_format($r['importe_isr'],2) . "<br>";
        }
        if($r['ieps_exento'] !== "" && $r['ieps_exento'] !== null){
          $impuestos .= "IEPS Exento " . $r['ieps_exento'] . "%: " . number_format($r['importe_ieps_exento'],2) . "<br>";
        }
        if($r['isr_retenido_monto_fijo'] !== "" && $r['isr_retenido_monto_fijo'] !== null){
          $impuestos .= "ISR Retenido (Monto fijo) : " . number_format($r['isr_retenido_monto_fijo'],2) . "<br>";
        }
        if($r['ieps_retenido_monto_fijo'] !== "" && $r['ieps_retenido_monto_fijo'] !== null){
          $impuestos .= "IEPS Retenido (Monto fijo) : " . number_format($r['ieps_retenido_monto_fijo'],2) . "<br>";
        }

        if($impuestos === ""){
          $impuestos = "Sin impuestos";
        }
        if($r['descuento_tasa'] !== null && $r['descuento_tasa'] !== ""){
          $descuento = "Descuento " . $r['descuento_tasa'] . "%: " . $r['importe_descuento_tasa'];
        }else if($r['descuento_monto_fijo'] !== null && $r['descuento_monto_fijo'] !== ""){
          $descuento = "Descuento: " . $r['importe_descuento_tasa'];
        }else{
          $descuento = "Sin descuento";
        }

        $queryPermit = sprintf("select funcion_editar from funciones_permisos fp
                                                      inner join usuarios u on fp.perfil_id = u.perfil_id
                                                      where u.id = :id and fp.pantalla_id = :screen");
        $stmtPermit = $db->prepare($queryPermit);
        $stmtPermit->bindValue(":id",$_SESSION['PKUsuario']);
        $stmtPermit->bindValue(":screen",16);
        $stmtPermit->execute();
        $editarPermiso = $stmtPermit->fetchAll();
        
        
        
        if($editarPermiso[0]['funcion_editar'] === 1){
          $edit = "<a id='edit".$r['id']."' data-id='".$r['id']."' data-ref='".$r['id_row']."' href='#' ><img src='../../img/icons/editar.svg' width='22px' data-toggle='tooltip' data-placement='right' title='Editar'>";
        } else {
          $edit = "";
        }

        $table .= '{
            "id":"'.$r['id'].'",
            "edit":"'.$edit.'",
            "clave":"'.$claveInterna.'",
            "descripcion":"'.$descripcion.'",
            "id_unidad_medida":"'.$idUnidadMedida.'",
            "sat_id":"'.$r['sat_id'].'",
            "unidad_medida":"'.$unidadMedida.'",
            "cantidad":"'.$cantidad.'",
            "precio":"'.number_format($r['precio_unitario'],2).'",
            "subtotal":"'.number_format(($r['cantidad_facturada'] * $r['precio_unitario']),2).'",
            "impuestos":"'.$impuestos.'",
            "descuento":"'.$descuento.'",
            "importe_total":"'.number_format($r['total_neto'],2).'",
            "alerta":"'.$alertaSat.'"
        },';
        $cont++;
      }
      
      $table = substr($table,0,strlen($table)-1);

      $con = "";
      $stmt = "";
      $db = "";

      return '{"data":['.$table.']}';
    }

    function getOrdenPedido($value){
      $con = new conectar();
      $db = $con->getDb();

      $query = sprintf("SELECT op.id,
                              op.id_orden_pedido_empresa,
                              c.razon_social,
                              c.rfc
                        FROM orden_pedido_por_sucursales op
                        INNER JOIN clientes c ON op.cliente_id = c.PKCliente
                        WHERE c.PKCliente = :id");
      $stmt = $db->prepare($query);
      $stmt->bindValue(":id",$value,PDO::PARAM_INT);
      $stmt->execute();

      $aux = $stmt->fetchAll(PDO::FETCH_OBJ);

      
      $id_orden_pedido_empresa = sprintf("%011d", $aux[0]->id_orden_pedido_empresa);
      $array[] = [
        'id' => $aux[0]->id,
        'referencia' => $id_orden_pedido_empresa,
        'razon_social' => $aux[0]->razon_social,
        'rfc' => $aux[0]->rfc
      ];

      $con = "";
      $stmt = "";
      $db = "";

      return $array;
    }

    function getTotalSubtotalSalidas(){
      $con = new conectar();
      $db = $con->getDb();

      $array = [];
      $impuestos = "<table class='table'><tbody>";
      $total = 0;
      
      $query = sprintf("select sum(total_bruto) subtotal, sum(total_neto) total from datos_producto_remision_temp where usuario_id = :usuario_id");
      $stmt = $db->prepare($query);
      $stmt->bindValue(":usuario_id", $_SESSION['PKUsuario']);
      $stmt->execute();
      $arr = $stmt->fetchAll();

      $query = sprintf("select 
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
                        ieps_retenido
                        isr_exento,
                        isr_monto_fijo,
                        isr,
                        ieps_exento,
                        isr_retenido_monto_fijo,
                        ieps_retenido_monto_fijo
                        from datos_producto_remision_temp
                      where usuario_id = :id");
      $stmt = $db->prepare($query);
      $stmt->bindValue(":id",$_SESSION['PKUsuario']);
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
          $impuestos .= "<tr><th>IEPS (Monto fijo) $ ".number_format($tasa, 2).": $</th><td>" . number_format($sum, 2) . "</td><tr>";
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
      $impuestos .= "</tbody></table>";
     
      $array = [
        "subtotal"=>number_format($arr[0]['subtotal'],2),
        "impuestos"=> $impuestos,
        "total"=>number_format($arr[0]['total'],2)
      ];

      return $array;
    }

    function getDataProduct($id){
      $con = new conectar();
      $db = $con->getDb();

      $query =sprintf("select 
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
                        from datos_producto_remision_temp dpft
                              inner join productos pro on dpft.producto_id = pro.PKProducto
                              left join claves_sat_unidades csu on dpft.unidad_medida_id = csu.PKClaveSATUnidad
                              left join claves_sat csa on dpft.clave_sat_id = csa.PKClaveSAT
                              where dpft.id = :id_row");
    
      $stmt = $db->prepare($query);
      $stmt->bindValue(":id_row",$id);
      $stmt->execute();

      $detalleProducto = $stmt->fetchAll();
      
      if($detalleProducto[0]['cantidad_facturada'] !== null && $detalleProducto[0]['cantidad_facturada'] !== ""){
        if($detalleProducto[0]['cantidad_total'] !== $detalleProducto[0]['cantidad_facturada']){
          $cantidad = (int)$detalleProducto[0]['cantidad_total'] - (int)$detalleProducto[0]['cantidad_facturada'];
        } else {
          $cantidad = $detalleProducto[0]['cantidad_facturada'];
        }
      }
      
      if(count($detalleProducto) > 0 && $detalleProducto[0]['descuento_tasa'] !== null && $detalleProducto[0]['descuento_tasa'] !== ""){
        $tipo_descuento = 1;
        $descuento = $detalleProducto[0]['descuento_tasa'];
      } else if(count($detalleProducto) > 0 && $detalleProducto[0]['descuento_monto_fijo'] !== null && $detalleProducto[0]['descuento_monto_fijo'] !== ""){
        $tipo_descuento = 2;
        $descuento = number_format($detalleProducto[0]['descuento_monto_fijo'],2,".",",");
      } else {
        $tipo_descuento = 1;
        $descuento = 0;
      }
      
      $array = [
        "id"=>$detalleProducto[0]['id_row'],
        "clave"=>$detalleProducto[0]['clave'],
        "nombre"=>$detalleProducto[0]['nombre'],
        "clave_sat"=>$detalleProducto[0]['sat_id'],
        "clave_sat_texto"=>$detalleProducto[0]['clave_sat_texto'],
        "unidad_medida"=>$detalleProducto[0]['unidad_medida'],
        "unidad_medida_texto"=>$detalleProducto[0]['unidad_medida_texto'],
        "limite_cantidad"=>$detalleProducto[0]['cantidad_total'],
        "cantidad"=>$cantidad,
        "precio_unitario"=>$detalleProducto[0]['precio_unitario'],
        "tipo_descuento"=>$tipo_descuento,
        "descuento"=>$descuento
      ];

      return $array;
    }

    function getImpuestoTable($value,$producto,$tipo,$id){
      $con = new conectar();
      $db = $con->getDb();
      $table= "";

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
                      from datos_producto_remision_temp
                        where id = :id");
      $stmt = $db->prepare($query);
      $stmt->bindValue(":id",$id);
      $stmt->execute();

      $detalleImpuesto = $stmt->fetchAll();
      $impuestos = [];

      //iva
      if($detalleImpuesto[0]['iva'] !== null){
        array_push(
          $impuestos,["id"=>1,"tipo"=>1,"nombre"=>"IVA","tasa"=>$detalleImpuesto[0]['iva']]
        );
      } 
      //ieps
      if($detalleImpuesto[0]['ieps'] !== null){
        array_push(
          $impuestos,["id"=>2,"tipo"=>1,"nombre"=>"IEPS","tasa"=>$detalleImpuesto[0]['ieps']]
        );
      } 
      //ieps monto fijo
      if($detalleImpuesto[0]['ieps_monto_fijo'] !== null){
        array_push(
          $impuestos,["id"=>3,"tipo"=>2,"nombre"=>"IEPS (Monto fijo)","tasa"=>$detalleImpuesto[0]['ieps_monto_fijo']]
        );
      }
      //iva exento
      if($detalleImpuesto[0]['iva_exento'] !== null){
        array_push(
          $impuestos,["id"=>5,"tipo"=>3,"nombre"=>"IVA Exento","tasa"=>$detalleImpuesto[0]['iva_exento']]
        );
      }
      //ish
      if($detalleImpuesto[0]['ish'] !== null){
        array_push(
          $impuestos,["id"=>4,"tipo"=>1,"nombre"=>"ISH","tasa"=>$detalleImpuesto[0]['ish']]
        );
      }
      //iva retenido
      if($detalleImpuesto[0]['iva_retenido'] !== null){
        array_push(
          $impuestos,["id"=>6,"tipo"=>1,"nombre"=>"IVA Retenido","tasa"=>$detalleImpuesto[0]['iva_retenido']]
        );
      }
      //isr
      if($detalleImpuesto[0]['isr_retenido'] !== null){
        array_push(
          $impuestos,["id"=>7,"tipo"=>2,"nombre"=>"ISR Retenido","tasa"=>$detalleImpuesto[0]['isr_retenido']]
        );
      }
      //isn local
      if($detalleImpuesto[0]['isn_local'] !== null){
        array_push(
          $impuestos,["id"=>8,"tipo"=>1,"nombre"=>"ISN (Local)","tasa"=>$detalleImpuesto[0]['isn_local']]
        );
      }
      //cedular
      if($detalleImpuesto[0]['cedular'] !== null){
        array_push(
          $impuestos,["id"=>9,"tipo"=>1,"nombre"=>"Cedular","tasa"=>$detalleImpuesto[0]['cedular']]
        );
      }
      //al millar
      if($detalleImpuesto[0]['al_millar'] !== null){
        array_push(
          $impuestos,["id"=>10,"tipo"=>1,"nombre"=>"5 al millar","tasa"=>$detalleImpuesto[0]['al_millar']]
        );
      }
      //funcion publica
      if($detalleImpuesto[0]['funcion_publica'] !== null){
        array_push(
          $impuestos,["id"=>11,"tipo"=>1,"nombre"=>"Función pública","tasa"=>$detalleImpuesto[0]['funcion_publica']]
        );
      }
      //ieps retenido
      if($detalleImpuesto[0]['ieps_retenido'] !== null){
        array_push(
          $impuestos,["id"=>12,"tipo"=>2,"nombre"=>"IEPS Retenido","tasa"=>$detalleImpuesto[0]['ieps_retenido']]
        );
      }
      //isr_exento
      if($detalleImpuesto[0]['isr_exento'] !== null){
        array_push(
          $impuestos,["id"=>13,"tipo"=>3,"nombre"=>"ISR Exento","tasa"=>$detalleImpuesto[0]['isr_exento']]
        );
      }
      //isr_monto_fijo
      if($detalleImpuesto[0]['isr_monto_fijo'] !== null){
        array_push(
          $impuestos,["id"=>14,"tipo"=>2,"nombre"=>"ISR (Monto fijo)","tasa"=>$detalleImpuesto[0]['isr_monto_fijo']]
        );
      }
      //isr
      if($detalleImpuesto[0]['isr'] !== null){
        array_push(
          $impuestos,["id"=>15,"tipo"=>1,"nombre"=>"ISR","tasa"=>$detalleImpuesto[0]['isr']]
        );
      }
      //ieps_exento
      if($detalleImpuesto[0]['ieps_exento'] !== null){
        array_push(
          $impuestos,["id"=>16,"tipo"=>3,"nombre"=>"IEPS Exento","tasa"=>$detalleImpuesto[0]['ieps_exento']]
        );
      }
      //ieps_exento
      if($detalleImpuesto[0]['isr_retenido_monto_fijo'] !== null){
        array_push(
          $impuestos,["id"=>17,"tipo"=>2,"nombre"=>"ISR Retenido (Monto fijo)","tasa"=>$detalleImpuesto[0]['isr_retenido_monto_fijo']]
        );
      }
      //ieps_retenido_monto_fijo
      if($detalleImpuesto[0]['ieps_retenido_monto_fijo'] !== null){
        array_push(
          $impuestos,["id"=>18,"tipo"=>2,"nombre"=>"IEPS Retenido (Monto fijo)","tasa"=>$detalleImpuesto[0]['ieps_retenido_monto_fijo']]
        );
      }

      $cont = 0;
      foreach ($impuestos as $r) {
        
        $table .= '{
          "id": "'.$r['id'].'",
          "tipo":"'.$r['tipo'].'",
          "nombre": "'.$r['nombre'].'",
          "tasa": "'.$r['tasa'].'",
          "delete":"<a id=\"deleteImp'.$r['id'].'\" data-pos=\"'.$cont.'\" data-id=\"'.$r['id'].'\" href=\"#\"><i class=\"fas fa-trash-alt\"></i></a>"
        },';
        $cont++;
      }

      $table = substr($table,0,strlen($table)-1);

      $con = "";
      $stmt = "";
      $db = "";

      return '{"data":['.$table.']}';
    }
    
    function getImpuestos($value){
      $con = new conectar();
      $db = $con->getDb();

      $query = sprintf("SELECT PKImpuesto id, Nombre texto FROM impuesto WHERE FKTipoImpuesto = :id ORDER BY Nombre ASC");
      $stmt = $db->prepare($query);
      $stmt->bindValue(":id",$value);
      
      $stmt->execute();

      return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    function getInvoiceDetailTable($value){
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
                          dpft.subtotal
                        from detalle_remision dpft
                          inner join productos pr on dpft.producto_id = pr.PKProducto
                          left join claves_sat_unidades csu on dpft.unidad_medida_id = csu.PKClaveSATUnidad
                        where remision_id = :id");
      $stmt = $db->prepare($query);
      $stmt->bindValue(":id",$value);
      $stmt->execute();

      $array = $stmt->fetchAll();
      
      foreach($array as $r){
        $claveInterna = ($r['clave'] !== "" && $r['clave'] !== null) ? $r['clave'] : "S/C";

        $unidadMedida = ($r['unidad_medida'] !== "" && $r['unidad_medida'] !== null) ? $r['unidad_medida'] : "N/A";
        
        $table .= '{
          "clave":"'.$claveInterna.'",
          "descripcion":"'.$r['nombre'].'",
          "unidad_medida":"'.$unidadMedida.'",
          "cantidad":"'.$r['cantidad'].'",
          "precio":"'.number_format($r['precio'],2).'",
          "importe":"'.number_format(($r['subtotal']),2).'"
        },';
      }

      $table = substr($table,0,strlen($table)-1);

      $con = "";
      $stmt = "";
      $db = "";

      return '{"data":['.$table.']}';
    }

    function getInvoiceDetail($value){
      $con = new conectar();
      $db = $con->getDb();
      $data = [];

      $query = sprintf("select
                          ft.folio serie_folio,
                          ft.fecha_creacion,
                          ft.estatus,
                          cl.razon_social,
                          cl.rfc,                   
                          sum(dpft.subtotal) subtotal
                        from detalle_remision dpft
                          inner join remisiones ft on dpft.remision_id = ft.id
                          left join clientes cl on ft.cliente_id = cl.PKCliente
                        where remision_id = :id");
      $stmt = $db->prepare($query);
      $stmt->bindValue(":id",$value);
      $stmt->execute();
      $client = $stmt->fetchAll();

      $query = sprintf("select
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
                          dpft.ieps_retenido_monto_fijo
                        from detalle_remision dpft
                        where remision_id = :id");
      $stmt = $db->prepare($query);
      $stmt->bindValue(":id",$value);
      $stmt->execute();

      $impuestos_aux = $stmt->fetchAll();
      $impuestos = "";
      $importe_impuestos = 0;
      $impuestos_aux1 = [];
     

      foreach($impuestos_aux as $r){
        if($r['iva'] !== "" && $r['iva'] !== null) {
          array_push($impuestos_aux1,
            array(
              "impuesto"=>1,
              "tasa"=>$r['iva'],
              "importe"=>(double)$r['importe_iva']
            )
          );
          
        }
        if($r['ieps'] !== "" && $r['ieps'] !== null) {
          array_push($impuestos_aux1,
            array(
              "impuesto"=>2,
              "tasa"=>$r['ieps'],
              "importe"=>(double)$r['importe_ieps']
            )
          );
          
        }
        if($r['ieps_monto_fijo'] !== "" && $r['ieps_monto_fijo'] !== null) {
          array_push($impuestos_aux1,
            array(
              "impuesto"=>3,
              "tasa"=>$r['ieps_monto_fijo'],
              "importe"=>(double)$r['ieps_monto_fijo']
            )
          );
          
        }
        if($r['ish'] !== "" && $r['ish'] !== null) {
          array_push($impuestos_aux1,
            array(
              "impuesto"=>4,
              "tasa"=>$r['ish'],
              "importe"=>(double)$r['importe_ish']
            )
          );
         
        }
        if($r['iva_exento'] !== "" && $r['iva_exento'] !== null) {
          array_push($impuestos_aux1,
            array(
              "impuesto"=>5,
              "tasa"=>$r['iva_exento'],
              "importe"=>(double)$r['importe_iva_exento']
            )
          );
          
        }
        if($r['iva_retenido'] !== "" && $r['iva_retenido'] !== null) {
          array_push($impuestos_aux1,
            array(
              "impuesto"=>6,
              "tasa"=>$r['iva_retenido'],
              "importe"=>(double)$r['importe_iva_retenido']
            )
          );
        }
        if($r['isr_retenido'] !== "" && $r['isr_retenido'] !== null) {
          array_push($impuestos_aux1,
            array(
              "impuesto"=>7,
              "tasa"=>$r['isr_retenido'],
              "importe"=>(double)$r['importe_isr_retenido']
            )
          );
        }
        if($r['isn_local'] !== "" && $r['isn_local'] !== null) {
          array_push($impuestos_aux1,
            array(
              "impuesto"=>8,
              "tasa"=>$r['isn_local'],
              "importe"=>(double)$r['importe_isn_local']
            )
          );
        }
        if($r['cedular'] !== "" && $r['cedular'] !== null) {
          array_push($impuestos_aux1,
            array(
              "impuesto"=>9,
              "tasa"=>$r['cedular'],
              "importe"=>(double)$r['importe_cedular']
            )
          );
        }
        if($r['al_millar'] !== "" && $r['al_millar'] !== null) {
          array_push($impuestos_aux1,
            array(
              "impuesto"=>10,
              "tasa"=>$r['al_millar'],
              "importe"=>(double)$r['importe_al_millar']
            )
          );
        }
        if($r['funcion_publica'] !== "" && $r['funcion_publica'] !== null) {
          array_push($impuestos_aux1,
            array(
              "impuesto"=>11,
              "tasa"=>$r['funcion_publica'],
              "importe"=>(double)$r['importe_funcion_publica']
            )
          );
        }
        if($r['ieps_retenido'] !== "" && $r['ieps_retenido'] !== null) {
          array_push($impuestos_aux1,
            array(
              "impuesto"=>12,
              "tasa"=>$r['ieps_retenido'],
              "importe"=>(double)$r['importe_ieps_retenido']
            )
          );
        }
        if($r['isr_exento'] !== "" && $r['isr_exento'] !== null) {
          array_push($impuestos_aux1,
            array(
              "impuesto"=>13,
              "tasa"=>$r['isr_exento'],
              "importe"=>(double)$r['importe_isr_exento']
            )
          );
        }
        if($r['isr_monto_fijo'] !== "" && $r['isr_monto_fijo'] !== null) {
          array_push($impuestos_aux1,
            array(
              "impuesto"=>14,
              "tasa"=>$r['isr_monto_fijo'],
              "importe"=>(double)$r['isr_monto_fijo']
            )
          );
        }
        if($r['isr'] !== "" && $r['isr'] !== null) {
          array_push($impuestos_aux1,
            array(
              "impuesto"=>15,
              "tasa"=>$r['isr'],
              "importe"=>(double)$r['importe_isr']
            )
          );
        }
        if($r['ieps_exento'] !== "" && $r['ieps_exento'] !== null) {
          array_push($impuestos_aux1,
            array(
              "impuesto"=>16,
              "tasa"=>$r['ieps_exento'],
              "importe"=>(double)$r['importe_ieps_exento']
            )
          );
        }
        if($r['isr_retenido_monto_fijo'] !== "" && $r['isr_retenido_monto_fijo'] !== null) {
          array_push($impuestos_aux1,
            array(
              "impuesto"=>17,
              "tasa"=>$r['isr_retenido_monto_fijo'],
              "importe"=>(double)$r['isr_retenido_monto_fijo']
            )
          );
        }
        if($r['ieps_retenido_monto_fijo'] !== "" && $r['ieps_retenido_monto_fijo'] !== null) {
          array_push($impuestos_aux1,
            array(
              "impuesto"=>18,
              "tasa"=>$r['ieps_retenido_monto_fijo'],
              "importe"=>(double)$r['ieps_retenido_monto_fijo']
            )
          );
        }
      }

      for ($i=0;$i<count($impuestos_aux1);$i++)
      {
        for ($j=$i+1; $j<count($impuestos_aux1);$j++)
        {
        if ($impuestos_aux1[$i]['tasa'] == $impuestos_aux1[$j]['tasa'])
          {
            $impuestos_aux1[$i]['importe'] = $impuestos_aux1[$i]['importe']+$impuestos_aux1[$j]['importe'];
            $impuestos_aux1[$j]['importe'] = 0;

          }    
        }
      }

      $impuestos_aux2 = [];

      foreach ($impuestos_aux1 as $r) {
        if($r['importe'] !== 0){
          array_push($impuestos_aux2,
            array(
              "impuesto"=>$r['impuesto'],
              "tasa"=>$r["tasa"],
              "importe"=>$r["importe"]
            )
          );
          
        }
      }

      foreach($impuestos_aux2 as $r){
        switch($r['impuesto']){
          case 1:
            $impuestos .= "IVA " . $r['tasa'] . "%: " . number_format($r['importe'],2) . "<br>";
            $importe_impuestos += $r["importe"];
          break;
          case 2:
            $impuestos .= "IEPS " . $r['tasa'] . "%: " . number_format($r['importe'],2) . "<br>";
            $importe_impuestos += $r["importe"];
          break;
          case 3:
            $impuestos .= "IEPS (Monto fijo): " . number_format($r['importe'],2) . "<br>";
            $importe_impuestos += $r["importe"];
          break;
          case 4:
            $impuestos .= "ISH " . $r['tasa'] . "%: " . number_format($r['importe'],2) . "<br>";
            $importe_impuestos += $r["importe"];
          break;
          case 5:
            $impuestos .= "IVA Exento " . $r['tasa'] . "%: " . number_format($r['importe'],2) . "<br>";
            //$importe_impuestos += $r["importe"];
          break;
          case 6:
            $impuestos .= "IVA Exento " . $r['tasa'] . "%: " . number_format($r['importe'],2) . "<br>";
            $importe_impuestos -= $r["importe"];
          break;
          case 7:
            $impuestos .= "ISR Retenido " . $r['tasa'] . "%: " . number_format($r['importe'],2) . "<br>";
            $importe_impuestos -= $r["importe"];
          break;
          case 8:
            $impuestos .= "ISN (Local) " . $r['tasa'] . "%: " . number_format($r['importe'],2) . "<br>";
            $importe_impuestos += $r["importe"];
          break;
          case 9:
            $impuestos .= "Cedular " . $r['tasa'] . "%: " . number_format($r['importe'],2) . "<br>";
            $importe_impuestos += $r["importe"];
          break;
          case 10:
            $impuestos .= "5 al millar (Local) " . $r['tasa'] . "%: " . number_format($r['importe'],2) . "<br>";
            $importe_impuestos += $r["importe"];
          break;
          case 11:
            $impuestos .= "Función Pública " . $r['tasa'] . "%: " . number_format($r['importe'],2) . "<br>";
            $importe_impuestos += $r["importe"];
          break;
          case 12:
            $impuestos .= "IEPS Retenido " . $r['tasa'] . "%: " . number_format($r['importe'],2) . "<br>";
            $importe_impuestos -= $r["importe"];
          break;
          case 13:
            $impuestos .= "ISR Exento " . $r['tasa'] . "%: " . number_format($r['importe'],2) . "<br>";
            //$importe_impuestos += $r["importe"];
          break;
          case 14:
            $impuestos .= "ISR (MontoFijo): " . number_format($r['importe'],2) . "<br>";
            $importe_impuestos += $r["importe"];
          break;
          case 15:
            $impuestos .= "ISR " . $r['tasa'] . "%: " . number_format($r['importe'],2) . "<br>";
            $importe_impuestos += $r["importe"];
          break;
          case 16:
            $impuestos .= "IEPS Exento " . $r['tasa'] . "%: " . number_format($r['importe'],2) . "<br>";
            //$importe_impuestos += $r["importe"];
          break;
          case 17:
            $impuestos .= "ISR retenido (Monto fijo): " . number_format($r['importe'],2) . "<br>";
            $importe_impuestos -= $r["importe"];
          break;
          case 18:
            $impuestos .= "IEPS Retenido (Monto fijo): " . number_format($r['importe'],2) . "<br>";
            $importe_impuestos -= $r["importe"];
          break;
        }
      }

      switch($client[0]['estatus']){
        case 0:
          $estatus = "Activa";
        break;
        case 1:
          $estatus = "Facturada";
        break;
        case 2:
          $estatus = "Cancelada";
        break;
      }
      
      $data = [
        
        "serie_folio" =>sprintf("%011d", $client[0]['serie_folio']),
        "fecha_creacion" => date("d-m-Y H:i:s",strtotime($client[0]['fecha_creacion'])),
        "razon_social" => $client[0]['razon_social'],
        "estatus" => $estatus,
        "rfc" => $client[0]['rfc'],
        "subtotal" =>  number_format($client[0]['subtotal'],2),
        "impuestos"=> $impuestos !== "" ? $impuestos : number_format('0',2),
        "total"=>number_format($importe_impuestos + $client[0]['subtotal'],2)
      ];

      return $data;
    
    }

    function getProductosEditTable($value,$tipo){
      $con = new conectar();
      $db = $con->getDb();
      $table = "";
      $cont = 0;

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
                          dpft.numero_lote,
                          dpft.caducidad,
                          dpft.numero_serie
                        from datos_producto_remision_temp dpft
                          inner join productos pr on dpft.producto_id = pr.PKProducto
                          left join claves_sat_unidades csu on dpft.unidad_medida_id = csu.PKClaveSATUnidad
                          where usuario_id = :usuario_id
                        ");
      $stmt = $db->prepare($query);
      $stmt->bindValue(":usuario_id",$_SESSION['PKUsuario']);
      //$stmt->bindValue(":tipo",$tipo);
      $stmt->execute();

      $detalleProducto = $stmt->fetchAll();

      $alertaSat = "";
      
      $impuestos = "";
      foreach($detalleProducto as $r){
        
        $claveInterna = ($r['clave'] !== "" && $r['clave'] !== null) ? $r['clave'] : "S/C";

        $alertaSat = ($r['sat_id'] !== null && $r['sat_id'] !== "" &&$r['sat_id'] !== 1) ? "" : '<img id=\"satAlert\" src=\"../../img/icons/ICONO ALERTA_Mesa de trabajo 1.svg\" style=\"width: 25px\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"No se asignó una clave SAT\">';

        //$sat = (count($arrSat) > 0 && $arrSat['clave_sat'] !== null && $arrSat['clave_sat'] !== "") ? $claveInterna : $alertaSat . "  " .$claveInterna;
        $idUnidadMedida =($r['id_unidad_medida'] !== "" && $r['id_unidad_medida'] !== null) ? $r['id_unidad_medida'] : "";
        $unidadMedida = ($r['unidad_medida'] !== "" && $r['unidad_medida'] !== null) ? $r['unidad_medida'] : "N/A";
        $cantidad = $r['cantidad_facturada'];
        
        if($r['iva'] !== "" && $r['iva'] !== null) {
          $impuestos .= "IVA " . $r['iva'] . "%: " . number_format($r['importe_iva'],2) . "<br>";
        }
        if($r['ieps'] !== "" && $r['ieps'] !== null) {
          $impuestos .= "IEPS " . $r['ieps'] . "%: " . number_format($r['importe_ieps'],2) . "<br>";
        }
        if($r['ieps_monto_fijo'] !== "" && $r['ieps_monto_fijo'] !== null) {
          $impuestos .= "IEPS (Monto fijo): " . number_format($r['ieps_monto_fijo'],2) . "<br>";
        }
        if($r['ish'] !== "" && $r['ish'] !== null) {
          $impuestos .= "ISH " . $r['ish'] . "%: " . number_format($r['importe_ish'],2) . "<br>";
        }
        if($r['iva_exento'] !== "" && $r['iva_exento'] !== null) {
          $impuestos .= "IVA Exento " . $r['iva_exento'] . "%: " . number_format($r['importe_iva_exento'],2) . "<br>";
        }
        if($r['iva_retenido'] !== "" && $r['iva_retenido'] !== null) {
          $impuestos .= "IVA Retenido " . $r['iva_retenido'] . "%: " . number_format($r['importe_iva_retenido'],2) . "<br>";
        }
        if($r['isr_retenido'] !== "" && $r['isr_retenido'] !== null) {
          $impuestos .= "ISR Retenido " . $r['isr_retenido'] . "%: " . number_format($r['importe_isr_retenido'],2) . "<br>";
        }
        if($r['isn_local'] !== "" && $r['isn_local'] !== null) {
          $impuestos .= "ISN (Local) " . $r['isn_local'] . "%: " . number_format($r['importe_isn_local'],2) . "<br>";
        }
        if($r['cedular'] !== "" && $r['cedular'] !== null) {
          $impuestos .= "Cedular " . $r['cedular'] . "%: " . number_format($r['importe_cedular'],2) . "<br>";
        }
        if($r['al_millar'] !== "" && $r['al_millar'] !== null) {
          $impuestos .= "5 al millar (Local) " . $r['al_millar'] . "%: " . number_format($r['importe_al_millar'],2) . "<br>";
        }
        if($r['funcion_publica'] !== "" && $r['funcion_publica'] !== null) {
          $impuestos .= "Función Pública " . $r['funcion_publica'] . "%: " . number_format($r['importe_funcion_publica'],2) . "<br>";
        }
        if($r['ieps_retenido'] !== "" && $r['ieps_retenido'] !== null){
          $impuestos .= "IEPS Retenido " . $r['ieps_retenido'] . "%: " . number_format($r['importe_ieps_retenido'],2) . "<br>";
        }
        if($r['isr_exento'] !== "" && $r['isr_exento'] !== null){
          $impuestos .= "ISR Exento " . $r['isr_exento'] . "%: " . number_format($r['importe_isr_exento'],2) . "<br>";
        }
        if($r['isr_monto_fijo'] !== "" && $r['isr_monto_fijo'] !== null){
          $impuestos .= "ISR (Monto fijo) : " . number_format($r['isr_monto_fijo'],2) . "<br>";
        }
        if($r['isr'] !== "" && $r['isr'] !== null){
          $impuestos .= "ISR " . $r['isr'] . "%: " . number_format($r['importe_isr'],2) . "<br>";
        }
        if($r['ieps_exento'] !== "" && $r['ieps_exento'] !== null){
          $impuestos .= "IEPS Exento" . $r['ieps_exento'] . "%: " . number_format($r['importe_ieps_exento'],2) . "<br>";
        }
        if($r['isr_retenido_monto_fijo'] !== "" && $r['isr_retenido_monto_fijo'] !== null){
          $impuestos .= "ISR Retenido (Monto fijo) : " . number_format($r['isr_retenido_monto_fijo'],2) . "<br>";
        }
        if($r['ieps_retenido_monto_fijo'] !== "" && $r['ieps_retenido_monto_fijo'] !== null){
          $impuestos .= "IEPS Retenido (Monto fijo) : " . number_format($r['ieps_retenido_monto_fijo'],2) . "<br>";
        }

        if($impuestos === ""){
          $impuestos = "Sin impuestos";
        }
        if($r['descuento_tasa'] !== null && $r['descuento_tasa'] !== "" && $r['descuento_tasa'] !== 0){
          $descuento = "Descuento " . $r['descuento_tasa'] . "%: " . $r['importe_descuento_tasa'];
        }else if($r['descuento_monto_fijo'] !== null && $r['descuento_monto_fijo'] !== "" && $r['descuento_monto_fijo'] !== 0){
          $descuento = "Descuento: " . $r['importe_descuento_tasa'];
        }else{
          $descuento = "Sin descuento";
        }

        if($r['numero_lote'] !== "" && $r['numero_lote'] !== null){
          if($r['caducidad'] !== "" && $r['caducidad'] !== null && $r['caducidad'] !== "0000-00-00"){
            $descripcion = $r['nombre'] . "<br>Lote: " . $r['numero_lote'] . " Caducidad: " . $r['caducidad'];
          }else{
            $descripcion = $r['nombre'] . "<br>Lote: " . $r['numero_lote'];
          }
        } else if($r['numero_serie'] !== "" && $r['numero_serie'] !== null){
          $descripcion = $r['nombre'] . "<br>Serie: " . $r['numero_serie'];
        } else {
          $descripcion = $r['nombre'];
        }

        $edit = "<a id='edit".$r['id']."' data-id='".$cont."' data-ref='".$r['id_row']."' href='#' ><img src='../../img/icons/editar.svg' width='22px' data-toggle='tooltip' data-placement='right' title='Editar'>";
        //$descripcion .= $r['numero_predial'] !== null && $r['numero_predial'] !== "" ? $r['nombre'] . "<br>Número predial: " . $r['numero_predial'] : "";
        $table .= '{
            "id":"'.$r['id'].'",
            "edit":"'.$edit.'",
            "clave":"'.$claveInterna.'",
            "descripcion":"'.$descripcion.'",
            "id_unidad_medida":"'.$idUnidadMedida.'",
            "sat_id":"'.$r['sat_id'].'",
            "unidad_medida":"'.$unidadMedida.'",
            "cantidad":"'.$cantidad.'",
            "precio":"'.number_format($r['precio_unitario'],2).'",
            "subtotal":"'.number_format(($r['cantidad_facturada'] * $r['precio_unitario']),2).'",
            "impuestos":"'.$impuestos.'",
            "descuento":"'.$descuento.'",
            "importe_total":"'.number_format($r['total_neto'],2).'",
            "alerta":"'.$alertaSat.'"
        },';
        $cont++;
        $impuestos = "";
      }
      
      $table = substr($table,0,strlen($table)-1);

      $con = "";
      $stmt = "";
      $db = "";

      return '{"data":['.$table.']}';
    }

    function getClaveSat($prod){
      $con = new conectar();
      $db = $con->getDb();

      $query = sprintf("select * from info_fiscal_productos where FKProducto = :id");
      $stmt = $db->prepare($query);
      $stmt->bindValue(":id",$prod);
      $stmt->execute();
      $arr = $stmt->fetchAll();

      if(count($arr) > 0 && $arr[0]['FKClaveSAT'] !== 1){
        $msj = [
          "mensaje"=>1,
          "clave_sat_id"=>$arr[0]['FKClaveSAT']
        ];
      } else {
        $msj = [
          "mensaje"=>0,
          "clave_sat_id"=>""
        ];
      }
      return $msj;
    }

    function getClaveSatTable(){
      $con = new conectar();
      $db = $con->getDb();

      $query = sprintf("SELECT PKClaveSAT id,Clave clave, Descripcion descripcion FROM claves_sat LIMIT 100");
      $stmt = $db->prepare($query);
      $stmt->execute();
      
      return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    function getClaveSatTableSearch($value){
      $con = new conectar();
      $db = $con->getDb();

      $query = sprintf("SELECT PKClaveSAT id,Clave clave, Descripcion descripcion FROM claves_sat WHERE Clave LIKE :q OR Descripcion LIKE :q1 LIMIT 100");
      $stmt = $db->prepare($query);
      $stmt->bindValue(":q","%".$value."%");
      $stmt->bindValue(":q1","%".$value."%");
      $stmt->execute();
      
      return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    function getClaveUnidadMedidaTable(){
      $con = new conectar();
      $db = $con->getDb();

      $query = sprintf("SELECT PKClaveSATUnidad id,Clave clave, Descripcion descripcion FROM claves_sat_unidades LIMIT 100");
      $stmt = $db->prepare($query);
      $stmt->execute();

      return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    function getClaveUnidadMedidaTableSearch($value){
      $con = new conectar();
      $db = $con->getDb();

      $query = sprintf("SELECT PKClaveSATUnidad id,Clave clave, Descripcion descripcion FROM claves_sat_unidades WHERE Clave LIKE :q OR Descripcion LIKE :q1 LIMIT 100");
      $stmt = $db->prepare($query);
      $stmt->bindValue(":q","%".$value."%");
      $stmt->bindValue(":q1","%".$value."%");
      $stmt->execute();

      return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    function getUnidadMedida($prod){
      $con = new conectar();
      $db = $con->getDb();

      $query = sprintf("select * from info_fiscal_productos where FKProducto = :id");
      $stmt = $db->prepare($query);
      $stmt->bindValue(":id",$prod);
      $stmt->execute();
      $arr = $stmt->fetchAll();

      if(count($arr) > 0){
        $msj = [
          "mensaje"=>1,
          "clave_sat_id"=>$arr[0]['FKClaveSATUnidad']
        ];
      } else {
        $msj = [
          "mensaje"=>0,
          "clave_sat_id"=>""
        ];
      }
      return $msj;
    }

    function getTruncateTableProducts(){
      $con = new conectar();
      $db = $con->getDb();

      $query = sprintf("delete from datos_producto_remision_temp where usuario_id = :usuario_id");
      $stmt = $db->prepare($query);
      $stmt->bindValue(":usuario_id",$_SESSION['PKUsuario']);
     

      $stmt->execute();
    }
  }

  class save_data{
    function saveRemission($value,$pedidos,$salidas){
      $con = new conectar();
      $db = $con->getDb();
      $subtotal = 0;
      $total = 0;
      $impuestos = 0;
      $descuento = 0;
      $referencias = [];
      $txtReferencia = "";
      $ban = 0;
      $pedidosParse = json_decode($pedidos);
      $salidasParse = json_decode($salidas);
      
      try{
        $db->beginTransaction();

        $query = sprintf("select * from remisiones where empresa_id = :empresa");
        $stmt = $db->prepare($query);
        $stmt->bindValue(":empresa",$_SESSION["IDEmpresa"]);
        $stmt->execute();
        $cont = $stmt->rowCount();

        $query = sprintf("select
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
                              dpft.clave_sat_id
                            from datos_producto_remision_temp dpft
                            inner join productos pr on dpft.producto_id = pr.PKProducto
                            left join claves_sat_unidades csu on dpft.unidad_medida_id = csu.PKClaveSATUnidad
                            left join claves_sat cst on dpft.clave_sat_id = cst.PKClaveSAT");
        $stmt = $db->prepare($query);
        $stmt->execute();

        $productos = $stmt->fetchAll();

        foreach($productos as $r){

          $subtotal += $r['cantidad'] * $r['price'];

          $impuestos += $r['importe_iva'] +
                        $r['importe_ieps'] +
                        $r['ieps_monto_fijo'] +
                        $r['importe_ish'] +
                        $r['importe_iva_exento'] +
                        $r['importe_iva_retenido'] +
                        $r['importe_isr_retenido'] +
                        $r['importe_isn_local'] +
                        $r['importe_cedular'] +
                        $r['importe_al_millar'] +
                        $r['importe_funcion_publica'] +
                        $r['importe_ieps_retenido'] +
                        $r['importe_isr_exento'] +
                        $r['isr_monto_fijo'] +
                        $r['importe_isr'] +
                        $r['importe_ieps_exento'] +
                        $r['isr_retenido_monto_fijo'] +
                        $r['ieps_retenido_monto_fijo'];
          
          $descuento += $r['importe_descuento_tasa'] + $r['descuento_monto_fijo'];

        }
        $total = $subtotal + $impuestos - $descuento;

        $query = sprintf("insert into remisiones (
                
                folio,
                fecha_creacion,
                subtotal,
                total,
                estatus,
                cliente_id,
                empresa_id,
                salida_id
              ) values (
                :folio,
                NOW(),
                :subtotal,
                :total,
                :estatus,
                :cliente_id,
                :empresa_id,
                :salida_id
              )");
        $stmt = $db->prepare($query);
        $stmt->bindValue(":folio",$cont+1);
        $stmt->bindValue(":subtotal",$subtotal);
        $stmt->bindValue(":total",$total);
        $stmt->bindValue(":estatus",0);
        $stmt->bindValue(":cliente_id",$value);
        $stmt->bindValue(":empresa_id",$_SESSION["IDEmpresa"]);
        $stmt->bindValue(":salida_id",$productos[0]['referencia']);
        
        if($stmt->execute()){
          $id_remision = $db->lastInsertId();

          $salidas = explode(",",$productos[0]['referencia']);
          
          foreach($productos as $r){
            $importe_descuento_total = 0;
            $subtotal_producto = $r['cantidad'] * $r['price'];
                  
            if($r['importe_descuento_tasa'] !== "" && $r['importe_descuento_tasa'] !== null){
              $importe_descuento_total = $r['importe_descuento_tasa'];
            }
            if($r['descuento_monto_fijo'] !== "" && $r['descuento_monto_fijo'] !== null){
              $importe_descuento_total = $r['descuento_monto_fijo'];
            }

            $impuestos = $r['importe_iva'] +
                        $r['importe_ieps'] +
                        $r['importe_ish'] +
                        $r['importe_iva_exento'] +
                        $r['importe_iva_retenido'] +
                        $r['importe_isr'] +
                        $r['importe_isn_local'] +
                        $r['importe_cedular'] +
                        $r['importe_al_millar'] +
                        $r['importe_funcion_publica'];
            
            $total_producto = $subtotal_producto + $impuestos - $importe_descuento_total;   

            $query = sprintf("insert into detalle_remision (
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
                      remision_id
                      ) values (
                        :cantidad,
                        :precio,
                        :subtotal,
                        :importe_descuento,
                        :unidad_medida_id,
                        :clave_prod_serv_id,
                        :producto_id,
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
                        :importe_total,
                        :remision_id
                      )");
            $stmt = $db->prepare($query);
            $stmt->bindValue(":cantidad",(int)$r['cantidad']);
            $stmt->bindValue(":precio",$r['price']);
            $stmt->bindValue(":subtotal",$subtotal_producto);
            $stmt->bindValue(":importe_descuento",$importe_descuento_total);
            $stmt->bindValue(":unidad_medida_id",$r['unidad_medida_id']);
            $stmt->bindValue(":clave_prod_serv_id",$r['clave_sat_id']);
            $stmt->bindValue(":producto_id",$r['producto_id']);
            $stmt->bindValue(":iva",$r['iva']);
            $stmt->bindValue(":importe_iva",$r['importe_iva']);
            $stmt->bindValue(":ieps",$r['ieps']);
            $stmt->bindValue(":importe_ieps",$r['importe_ieps']);
            $stmt->bindValue(":ieps_monto_fijo",$r['ieps_monto_fijo']);
            $stmt->bindValue(":ish",$r['ish']);
            $stmt->bindValue(":importe_ish",$r['importe_ish']);
            $stmt->bindValue(":iva_exento",$r['iva_exento']);
            $stmt->bindValue(":importe_iva_exento",$r['importe_iva_exento']);
            $stmt->bindValue(":iva_retenido",$r['iva_retenido']);
            $stmt->bindValue(":importe_iva_retenido",$r['importe_iva_retenido']);
            $stmt->bindValue(":isr_retenido",$r['isr_retenido']);
            $stmt->bindValue(":importe_isr_retenido",$r['importe_isr_retenido']);
            $stmt->bindValue(":isn_local",$r['isn_local']);
            $stmt->bindValue(":importe_isn_local",$r['importe_isn_local']);
            $stmt->bindValue(":cedular",$r['cedular']);
            $stmt->bindValue(":importe_cedular",$r['importe_cedular']);
            $stmt->bindValue(":al_millar",$r['al_millar']);
            $stmt->bindValue(":importe_al_millar",$r['importe_al_millar']);
            $stmt->bindValue(":funcion_publica",$r['funcion_publica']);
            $stmt->bindValue(":importe_funcion_publica",$r['importe_funcion_publica']);
            $stmt->bindValue(":ieps_retenido",$r['ieps_retenido']);
            $stmt->bindValue(":importe_ieps_retenido",$r['importe_ieps_retenido']);
            $stmt->bindValue(":isr_exento",$r['isr_exento']);
            $stmt->bindValue(":importe_isr_exento",$r['importe_isr_exento']);
            $stmt->bindValue(":isr_monto_fijo",$r['isr_monto_fijo']);
            $stmt->bindValue(":isr",$r['isr']);
            $stmt->bindValue(":importe_isr",$r['importe_isr']);
            $stmt->bindValue(":ieps_exento",$r['ieps_exento']);
            $stmt->bindValue(":importe_ieps_exento",$r['importe_ieps_exento']);
            $stmt->bindValue(":isr_retenido_monto_fijo",$r['isr_retenido_monto_fijo']);
            $stmt->bindValue(":ieps_retenido_monto_fijo",$r['ieps_retenido_monto_fijo']);
            $stmt->bindValue(":importe_total",$total_producto);
            $stmt->bindValue(":remision_id",$id_remision);
            $stmt->execute();

          }
          
          for ($i=0; $i < count($salidasParse); $i++) {
            
            $query3 = sprintf("update inventario_salida_por_sucursales set estatus = 1 where folio_salida = :id");
            $stmt3 = $db->prepare($query3);
            $stmt3->bindValue(":id",$salidasParse[$i]);
            $ban = $stmt3->execute();
          }
          if($ban){
            for ($i=0; $i < count($pedidosParse); $i++) { 
              $query = sprintf("select sum(dp.cantidad_pedida) cantidad_pedido from detalle_orden_pedido_por_sucursales dp
                                where dp.orden_pedido_id = :id");
              $stmt = $db->prepare($query);
              $stmt->bindValue(":id", $pedidosParse[$i]);
              $stmt->execute();

              $cantidad_pedido = (int)$stmt->fetch()['cantidad_pedido'];

              $query = sprintf("select sum(cantidad) cantidad_remisionada from inventario_salida_por_sucursales where orden_pedido_id = :id and estatus = 1");
              $stmt = $db->prepare($query);
              $stmt->bindValue(":id", $pedidosParse[$i]);
              $stmt->execute();

              $cantidad_remisionada = (int)$stmt->fetch()['cantidad_remisionada'];
              
              if($cantidad_remisionada < $cantidad_pedido){
                $data = 5;
                $data1 = 11;
              } else {
                $data = 1;
                $data1 = 12;
              }
              $queryEstatus = sprintf("update orden_pedido_por_sucursales set estatus_factura_id = :estatus, estatus_orden_pedido_id = :estatus1 where id= :id");
              $stmtEstatus = $db->prepare($queryEstatus);
              $stmtEstatus->bindValue(":estatus",$data);
              $stmtEstatus->bindValue(":estatus1",$data1);
              $stmtEstatus->bindValue(":id",$pedidosParse[$i]);
              $ban1 = $stmtEstatus->execute();
            }
          }
        }
        $db->commit();
      } catch (PDOException $e){
        echo $e->getMessage();
        $db->rollBack();
        $ban1 = false;
      }
      return $ban1;
    }

    function saveDataTaxes($prod,$value,$tasa,$id){
      $con = new conectar();
      $db = $con->getDb();

      switch($value){
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
      $stmt->bindValue(":id",$_SESSION['IDEmpresa']);
      $stmt->bindValue(":prod",$prod);
      $stmt->execute();
      $info_fiscal = $stmt->fetch()['id'];
      
      $query = sprintf("insert into impuestos_productos (FKInfoFiscalProducto, FKImpuesto, Tasa) values (:id,:impuesto,:tasa)");
      $stmt = $db->prepare($query);
      $stmt->bindValue(":id",$info_fiscal);
      $stmt->bindValue(":impuesto",$value);
      $stmt->bindValue(":tasa",$tasa);
      $stmt->execute();

      $query = sprintf("select * from impuesto_tasas where FKImpuesto = :id and Tasa = :tasa");
      $stmt = $db->prepare($query);
      $stmt->bindValue(":id",$value);
      $stmt->bindValue(":tasa",$tasa);
      $stmt->execute();
      $rowCount = $stmt->rowCount();

      if($rowCount === 0){
        $query = sprintf("insert into impuesto_tasas (Tasa, FKImpuesto) values (:tasa,:id)");
        $stmt = $db->prepare($query);
        $stmt->bindValue(":id",$value);
        $stmt->bindValue(":tasa",$tasa);
        $stmt->execute();
      }
      
      $query1 = sprintf("update datos_producto_remision_temp set ".$column." = :tasa where id = :id");
      $stmt1 = $db->prepare($query1);
      /*$stmt1->bindValue(":ref",$ref);
      $stmt1->bindValue(":tipo",$tipo);
      $stmt1->bindValue(":prod",$prod);*/
      $stmt1->bindValue(":tasa",$tasa);
      $stmt1->bindValue(":id",$id);
      return $stmt1->execute();
    }
    
  }
  class delete_data{
    function deleteTaxProducto($ref,$prod,$value,$type,$id,$tasa){
      $con = new conectar();
      $db = $con->getDb();

      switch($value){
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
      $stmt->bindValue(":id",$_SESSION['IDEmpresa']);
      $stmt->bindValue(":prod",$prod);
      $stmt->execute();
      $info_fiscal = $stmt->fetch()['id'];
      
      $query = sprintf("delete from impuestos_productos where FKInfoFiscalProducto =:id and FKImpuesto =:impuesto");
      $stmt = $db->prepare($query);
      $stmt->bindValue(":id",$info_fiscal);
      $stmt->bindValue(":impuesto",$value);
      if($stmt->execute()){
        $query1 = sprintf("update datos_producto_remision_temp set " . $column . " = :tasa where producto_id = :id and usuario_id = :user");
        $stmt1 = $db->prepare($query1);
        $stmt1->bindValue(":id",$prod);
        $stmt1->bindValue(":user",$_SESSION['PKUsuario']);
        $stmt1->bindValue(":tasa",null);
        $rst = $stmt1->execute();
      }
      return $rst;
    }

    
  }

  class edit_data{
    
    function editDataProducto($value){
      $con = new conectar();
      $db = $con->getDb();
      
      $cad = "";
      $column = "";
      $importe_impuesto = 0;
      $data = json_decode($value,true);
      
      $id = $data['id'];
      $referencia = $data['referencia'];
      $tipo_referencia = $data['tipo_referencia'];
      $id_producto = $data['producto_id'];
      $cantidad = $data['cantidad'];
      $precio_untario = $data['precio_unitario'];
      $impuestos = $data['impuestos'];
      $subtotal = (float)$data['subtotal'];
      $sat_id = $data['sat_id'];
      $unidad_medida_id =$data['unidad_medida_id'];
      $descuento_tasa = $data['descuento_tasa'];
      $importe_descuento_tasa = $data['importe_descuento_tasa'];
      $descuento_monto_fijo = $data['descuento_monto_fijo'];
      $predial = $data['predial'];

      if($importe_descuento_tasa !== null && $importe_descuento_tasa !== ""){
        $importe_descuento = $importe_descuento_tasa;
      } else if($descuento_monto_fijo !== null && $descuento_monto_fijo !== ""){
        $importe_descuento =  $descuento_monto_fijo;
      } else {
        $importe_descuento = 0;
      }
      
      $importe_impuestos = 0;
      if(count($impuestos) > 0){
        for ($i=0; $i < count($impuestos); $i++) {
          switch($impuestos[$i]['id']){
            case "1":
              $tasa = (double)$impuestos[$i]['tasa'];
              $column = "importe_iva";
              $importe_impuesto = $subtotal * ($tasa/100);
              $importe_impuestos += $importe_impuesto;
            break;
            case "2":
              $tasa = $impuestos[$i]['tasa'];
              $column = "importe_ieps";
              $importe_impuesto = $subtotal * ($tasa/100);
              $importe_impuestos += $importe_impuesto;
            break;
            case "3":
              $tasa = (double)$impuestos[$i]['tasa'];
              $column = "ieps_monto_fijo";
              $importe_impuesto = $tasa;
              $importe_impuestos += $tasa;
            break;
            case "4":
              $tasa = (double)$impuestos[$i]['tasa'];
              $column = "importe_ish";
              $importe_impuesto = $subtotal * ($tasa/100);
              $importe_impuestos += $importe_impuesto;
            break;
            case "5":
              $tasa = (double)$impuestos[$i]['tasa'];
              $column = "importe_iva_exento";
              $importe_impuesto = $subtotal * ($tasa/100);
              $importe_impuestos += $importe_impuesto;
            break;
            case "6":
              $tasa = (double)$impuestos[$i]['tasa'];
              $column = "importe_iva_retenido";
              $importe_impuesto = $subtotal * ($tasa/100);
              $importe_impuestos -= $importe_impuesto;
            break;
            case "7":
              $tasa = (double)$impuestos[$i]['tasa'];
              $column = "importe_isr_retenido";
              $importe_impuesto = $subtotal * ($tasa/100);
              $importe_impuestos -= $importe_impuesto;
            break;
            case "8":
              $tasa = (double)$impuestos[$i]['tasa'];
              $column = "importe_isn_local";
              $importe_impuesto = $subtotal * ($tasa/100);
              $importe_impuestos += $importe_impuesto;
            break;
            case "9":
              $tasa = (double)$impuestos[$i]['tasa'];
              $column = "importe_cedular";
              $importe_impuesto = $subtotal * ($tasa/100);
              $importe_impuestos += $importe_impuesto;
            break;
            case "10":
              $tasa = (double)$impuestos[$i]['tasa'];
              $column = "importe_al_millar";
              $importe_impuesto = $subtotal * ($tasa/100);
              $importe_impuestos += $importe_impuesto;
            break;
            case "11":
              $tasa = (double)$impuestos[$i]['tasa'];
              $column = "importe_funcion_publica";
              $importe_impuesto = $subtotal * ($tasa/100);
              $importe_impuestos += $importe_impuesto;
            break;
            case "12":
              $tasa = (double)$impuestos[$i]['tasa'];
              $column = "importe_ieps_retenido";
              $importe_impuesto = $subtotal * ($tasa/100);
              $importe_impuestos -= $importe_impuesto;
            break;
            case "13":
              $tasa = (double)$impuestos[$i]['tasa'];
              $column = "importe_isr_exento";
              $importe_impuesto = $subtotal * ($tasa/100);
              $importe_impuestos += $importe_impuesto;
            break;
            case "14":
              $tasa = (double)$impuestos[$i]['tasa'];
              $column = "isr_monto_fijo";
              $importe_impuesto = $subtotal * ($tasa/100);
              $importe_impuestos += $importe_impuesto;
            break;
            case "15": 
              $tasa = (double)$impuestos[$i]['tasa'];
              $column = "importe_isr";
              $importe_impuesto = $subtotal * ($tasa/100);
              $importe_impuestos += $importe_impuesto;
            break;
            case "16":
              $tasa = (double)$impuestos[$i]['tasa'];
              $column = "importe_ieps_exento";
              $importe_impuesto = $subtotal * ($tasa/100);
              $importe_impuestos += $importe_impuesto;
            break;
            case "17":
              $tasa = (double)$impuestos[$i]['tasa'];
              $column = "isr_retenido_monto_fijo";
              $importe_impuesto = $subtotal * ($tasa/100);
              $importe_impuestos -= $importe_impuesto;
            break;
            case "18":
              $tasa = (double)$impuestos[$i]['tasa'];
              $column = "ieps_retenido_monto_fijo";
              $importe_impuesto = $subtotal * ($tasa/100);
              $importe_impuestos -= $importe_impuesto;
            break;

          }
          $query = sprintf("update datos_producto_remision_temp set " . $column . " = :tasa where id = :id");
          $stmt = $db->prepare($query);
          $stmt->bindValue(":id",$id);
          $stmt->bindValue(":tasa",$importe_impuesto);
          $ban = $stmt->execute();
        }
      }
      $total = $subtotal + $importe_impuestos - $importe_descuento;
      //$query1 = "";
      if($descuento_tasa !== null && $descuento_tasa !== ""){
        $query1 = sprintf("update datos_producto_remision_temp set 
                            clave_sat_id=:sat_id,
                            unidad_medida_id=:unidad_medida_id,
                            cantidad_facturada=:cantidad,
                            precio_unitario=:precio,
                            total_bruto=:subtotal,
                            descuento_tasa=:descuento_tasa,
                            importe_descuento_tasa=:importe_descuento_tasa,
                            total_neto=:total
                        where id = :id
                      ");
      } else if($descuento_monto_fijo !== null && $descuento_monto_fijo !== ""){
        $query1 = sprintf("update datos_producto_remision_temp set 
                            clave_sat_id=:sat_id,
                            unidad_medida_id=:unidad_medida_id,
                            cantidad_facturada=:cantidad,
                            precio_unitario=:precio,
                            total_bruto=:subtotal,
                            descuento_monto_fijo=:descuento_monto_fijo,
                            total_neto=:total
                        where id = :id
                      ");
      } else {
        $query1 = sprintf("update datos_producto_remision_temp set 
                            clave_sat_id=:sat_id,
                            unidad_medida_id=:unidad_medida_id,
                            cantidad_facturada=:cantidad,
                            precio_unitario=:precio,
                            total_bruto=:subtotal,
                            total_neto=:total
                        where id = :id
        ");
      }
      

                    
      $stmt1 = $db->prepare($query1);
      $stmt1->bindValue(":sat_id",$sat_id);
      $stmt1->bindValue(":unidad_medida_id",$unidad_medida_id);
      $stmt1->bindValue(":cantidad",$cantidad);
      $stmt1->bindValue(":precio",$precio_untario);
      $stmt1->bindValue(":subtotal",$subtotal);

      if($descuento_tasa !== null && $descuento_tasa !== ""){
        $stmt1->bindValue(":descuento_tasa",$descuento_tasa);
        $stmt1->bindValue(":importe_descuento_tasa",$importe_descuento_tasa);
      } else if($descuento_monto_fijo !== null && $descuento_monto_fijo !== ""){
        $stmt1->bindValue(":descuento_monto_fijo",$descuento_monto_fijo);
      }
      
      $stmt1->bindValue(":total",$total);
      $stmt1->bindValue(":id",$id);
      
      return $stmt1->execute();
    }

    function editClaveSat($value,$prod){
      $con = new conectar();
      $db = $con->getDb();

      $query = sprintf("select * from info_fiscal_productos where FKProducto = :id");
      $stmt = $db->prepare($query);
      $stmt->bindValue(":id",$prod);
      $stmt->execute();
      $rowCount = $stmt->rowCount();

      if($rowCount > 0){
        $query = sprintf("update info_fiscal_productos set FKClaveSAT = :cls where FKProducto = :id");
        $stmt = $db->prepare($query);
        $stmt->bindValue(":cls",$value);
        $stmt->bindValue(":id",$prod);
        $stmt->execute();
      } else {
        $query = sprintf("insert into info_fiscal_productos (FKClaveSAT,FKProducto,FKClaveSATUnidad) values (:cls,:id,1)");
        $stmt = $db->prepare($query);
        $stmt->bindValue(":cls",$value);
        $stmt->bindValue(":id",$prod);
        $stmt->execute();
      }
    }

    function editClaveUnidadSat($value,$prod){
      $con = new conectar();
      $db = $con->getDb();

      $query = sprintf("select * from info_fiscal_productos where FKProducto = :id");
      $stmt = $db->prepare($query);
      $stmt->bindValue(":id",$prod);
      $stmt->execute();
      $rowCount = $stmt->rowCount();

      if($rowCount > 0){
        $query = sprintf("update info_fiscal_productos set FKClaveSATUnidad = :cls where FKProducto = :id");
        $stmt = $db->prepare($query);
        $stmt->bindValue(":cls",$value);
        $stmt->bindValue(":id",$prod);
        $stmt->execute();
      } else {
        $query = sprintf("insert into info_fiscal_productos (FKClaveSATUnidad,FKProducto) values (:cls,:id)");
        $stmt = $db->prepare($query);
        $stmt->bindValue(":cls",$value);
        $stmt->bindValue(":id",$prod);
        $stmt->execute();
      }
    }
  }

 
?>