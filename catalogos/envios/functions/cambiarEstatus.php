<?php
  require_once('../../../include/db-conn.php');
  
  date_default_timezone_set('America/Mexico_City'); 

  $id = (int)$_POST['IDEnvio'];
  $Estatus = $_POST['EstatusEnvio'];
  $NumeroGuias = $_POST['NumeroGuias'];
  $DevolverGuias = $_POST['Guia'];
  $DevolverProductos = $_POST['Productos'];
  $IDNumero_guia = $_POST['Tipo_Guia'];

  $stmt = $conn->prepare('SELECT e.Estatus, p.PKPaqueteria , ge.Tipo_de_Pago, i.Existencias as Numero_Guias, i.FKProducto as idproducto, e.FKFactura FROM envios AS e INNER JOIN paqueterias AS p ON p.PKPaqueteria = e.FKPaqueteria LEFT JOIN guias_envio as ge ON ge.FKPaqueteria = p.PKPaqueteria LEFT JOIN inventario as i ON i.FKProducto = ge.FKProducto WHERE e.PKEnvio = :id AND ge.PKGuiaEnvio = :tipopago');
  $stmt->execute(array(':id' => $id,':tipopago' => $IDNumero_guia));
  $row= $stmt->fetch();

  $Estatus_old = $row['Estatus'];
  $Paqueteria = $row['PKPaqueteria'];
  $TipoPago = $row['Tipo_de_Pago'];
  $NumeroGuias_old = $row['Numero_Guias'];
  $IDProductoGuia = $row['idproducto'];//id de la guia como producto
  $IDFactura = $row['FKFactura'];

  if($Estatus_old != $Estatus){
      try{
        $conn->beginTransaction();

        if($Estatus_old == 'En proceso' && ($Estatus == 'Entregado' || $Estatus == 'Cancelado')){
          echo "No se puede cambiar el estatus de En proceso a Entregado o Cancelado.";
        }
        elseif($Estatus_old == 'Cancelado' && ($Estatus == 'Enviado' || $Estatus == 'Entregado')){
          echo "No se puede volver al estatus de En proceso.";
        }
        elseif($Estatus_old == 'Enviado' && $Estatus == 'En proceso'){
          echo "No se puede volver al estatus de En proceso.";
        }
        elseif($Estatus_old == 'Entregado' && ($Estatus == 'En proceso' || $Estatus == 'Enviado' || $Estatus == 'Cancelado')){
          echo "No se puede cambiar el estatus.";
        }
        else{    

            if($Estatus == 'Enviado'){
              
                //Se descuentan o suman las guias que se utilizaran
                if($TipoPago == 0)
                  $NumeroGuiasFinal = $NumeroGuias_old - $NumeroGuias;
                else
                  $NumeroGuiasFinal = $NumeroGuias_old + $NumeroGuias;

                //se actualiza la cantidad de guias
                $stmt = $conn->prepare('UPDATE inventario SET Existencias = :numero_guias WHERE FKProducto = :idproducto');
                $stmt->execute(array(':numero_guias' => $NumeroGuiasFinal, ':idproducto' => $IDProductoGuia));
                //Fin guias

                //Se descuentan productos del inventario
                $stmt = $conn->prepare('SELECT pe.FKProducto, pe.Cajas_por_enviar, pe.Piezas_por_enviar, um.Piezas_por_Caja, i.Existencias, p.Descripcion FROM productos_en_envio as pe LEFT JOIN productos as p ON p.PKProducto = pe.FKProducto LEFT JOIN unidad_medida as um ON um.PKUnidadMedida = p.FKUnidadMedida LEFT JOIN inventario as i ON i.FKProducto = pe.FKProducto WHERE pe.FKEnvio = :id ');
                $stmt->execute(array(':id' => $id));
                $row_productos = $stmt->fetchAll();

                foreach($row_productos as $rp){
                  $idproducto = $rp['FKProducto'];
                  $unidades_restar_cajas = $rp['Piezas_por_Caja'] * $rp['Cajas_por_enviar'];
                  $unidades_restar_piezas = $rp['Piezas_por_enviar'];
                  $unidades_restar_total = $unidades_restar_cajas + $unidades_restar_piezas;

                  $existencias = $rp['Existencias'];

                  if($existencias == ""){
                    echo "El producto ".$rp['Descripcion']." no se encuentra en el inventario.";
                    $conn->rollBack();
                    return;
                  }

                  $existencias_finales = $existencias - $unidades_restar_total;
                  $stmt = $conn->prepare('UPDATE inventario SET Existencias = :existencias WHERE FKProducto = :id ');
                  $stmt->execute(array(':existencias' => $existencias_finales, ':id' => $idproducto));
                }
                //fin descuento productos

                $FechaEnvio = date('Y-m-d');
                $stmt = $conn->prepare('UPDATE envios SET Estatus = :estatus, Numero_Guias = :numero_guias, FechaEnvio = :fechaenvio, FKGuiaEnvio = :fknumero_guias WHERE PKEnvio = :id');
                $stmt->execute(array(':estatus' => $Estatus, ':numero_guias' => $NumeroGuias,':fechaenvio' => $FechaEnvio , ':fknumero_guias' => $IDNumero_guia,':id' => $id));
            }
            elseif($Estatus == 'Entregado'){

                $FechaEntrega = date('Y-m-d');
                $stmt = $conn->prepare('UPDATE envios SET Estatus = :estatus, FechaEntrega = :fechaentrega WHERE PKEnvio = :id');
                $stmt->execute(array(':estatus' => $Estatus, ':fechaentrega' => $FechaEntrega , ':id' => $id));

                $stmt = $conn->prepare('SELECT SUM(pe.Cajas_por_enviar) as Cajas_por_enviar, SUM(pe.Piezas_por_enviar) as Piezas_por_enviar, dc.Cantidad, um.Piezas_por_Caja FROM envios as e LEFT JOIN facturacion as f ON f.PKFacturacion = e.FKFactura LEFT JOIN cotizacion as c ON c.PKCotizacion = f.FKCotizacion LEFT JOIN detallecotizacion as dc ON dc.FKCotizacion = c.PKCotizacion LEFT JOIN productos_en_envio as pe ON pe.FKEnvio = e.PKEnvio AND dc.FKProducto = pe.FKProducto LEFT JOIN productos as p ON p.PKProducto = dc.FKProducto LEFT JOIN unidad_medida as um ON um.PKUnidadMedida = p.FKUnidadMedida WHERE e.FKFactura = :id GROUP BY p.PKProducto');
                $stmt->execute(array(':id' => $IDFactura));
                $row_prod = $stmt->fetchAll();
                $cuenta_productos = $stmt->rowCount();
                $cuenta_comp = 0;
				
                foreach ($row_prod as $rp) {
                  if($rp['Cajas_por_enviar'] != NULL || $rp['Cajas_por_enviar'] != "")
                    $cajasenviar = $rp['Cajas_por_enviar'];
                  else
                    $cajasenviar = 0;

                  if($rp['Piezas_por_enviar'] != NULL || $rp['Piezas_por_enviar'] != "")
                    $piezasenviar = $rp['Piezas_por_enviar'];
                  else
                    $piezasenviar = 0;

                  if($rp['Piezas_por_Caja'] != NULL || $rp['Piezas_por_Caja'] != "")
                    $piezascaja = $rp['Piezas_por_Caja'];
                  else
                    $piezascaja = 1;


                  $piezasenviar =  ($cajasenviar * $piezascaja) + $piezasenviar;
                  //echo $piezasenviar." - cajas enviar ".$cajasenviar." - piezas cajas ".$piezascaja." - piezas enviar ".$piezasenviar."<br>";
				  
                  if($rp['Cantidad'] == $piezasenviar)
                  {
                    $cuenta_comp++;
                  }

                }

//echo $cuenta_comp." - ".$cuenta_productos;
                if($cuenta_productos == $cuenta_comp){
                  $stmt = $conn->prepare('UPDATE facturacion SET Enviado = 1 WHERE PKFacturacion = :id');
                  $stmt->execute(array(':id' => $IDFactura));
                }

            }
            elseif($Estatus == 'En proceso'){
                $stmt = $conn->prepare('UPDATE envios SET Estatus = :estatus WHERE PKEnvio = :id');
                $stmt->execute(array(':estatus' => $Estatus, ':id' => $id));
            }
            elseif($Estatus == 'Cancelado'){

                //Se descuentan o suman las guias que se utilizaran
                if($DevolverGuias == 1){
                    if($TipoPago == 0)
                      $NumeroGuiasFinal = $NumeroGuias_old + $NumeroGuias;
                    else
                      $NumeroGuiasFinal = $NumeroGuias_old - $NumeroGuias;

                    //se actualiza la cantidad de guias
                    $stmt = $conn->prepare('UPDATE inventario SET Existencias = :numero_guias WHERE FKProducto = :idproducto');
                    $stmt->execute(array(':numero_guias' => $NumeroGuiasFinal, ':idproducto' => $IDProductoGuia));
                }
                //Fin guias

                //Se descuentan productos del inventario
                if($DevolverProductos == 1){

                    $stmt = $conn->prepare('SELECT pe.FKProducto, pe.Cajas_por_enviar, pe.Piezas_por_enviar, um.Piezas_por_Caja, i.Existencias, p.Descripcion FROM productos_en_envio as pe LEFT JOIN productos as p ON p.PKProducto = pe.FKProducto LEFT JOIN unidad_medida as um ON um.PKUnidadMedida = p.FKUnidadMedida LEFT JOIN inventario as i ON i.FKProducto = pe.FKProducto WHERE pe.FKEnvio = :id ');
                    $stmt->execute(array(':id' => $id));
                    $row_productos = $stmt->fetchAll();

                    foreach($row_productos as $rp){
                      $idproducto = $rp['FKProducto'];
                      $unidades_restar_cajas = $rp['Piezas_por_Caja'] * $rp['Cajas_por_enviar'];
                      $unidades_restar_piezas = $rp['Piezas_por_enviar'];
                      $unidades_restar_total = $unidades_restar_cajas + $unidades_restar_piezas;

                      $existencias = $rp['Existencias'];

                      $existencias_finales = $existencias + $unidades_restar_total;
                      $stmt = $conn->prepare('UPDATE inventario SET Existencias = :existencias WHERE FKProducto = :id ');
                      $stmt->execute(array(':existencias' => $existencias_finales, ':id' => $idproducto));
                    }
                }
                //fin descuento productos

                $stmt = $conn->prepare('UPDATE envios SET Estatus = :estatus WHERE PKEnvio = :id');
                $stmt->execute(array(':estatus' => $Estatus, ':id' => $id));
            }
        }

        $conn->commit();

      }catch(Exception $e){
        echo $e->getMessage();
        $conn->rollBack();
      }
    }
 ?>
