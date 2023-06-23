<?php
  error_reporting(~E_WARNING);
  session_start();
  require_once('../../../include/db-conn.php');

  if(isset($_POST['idCotizacion'])){
    $id =  $_POST['idCotizacion'];

    $stmt = $conn->prepare('SELECT modificar FROM cotizacion WHERE PKCotizacion = :idCotizacion AND empresa_id = '.$_SESSION['IDEmpresa']);
    $stmt->execute(array(':idCotizacion'=>$id));
    $cotizacion_mod = $stmt->fetch();

    if($cotizacion_mod['modificar'] == 1){
      echo "no_modificar";
      return;
    }

    $stmt = $conn->prepare('SELECT estatus_orden_pedido_id FROM orden_pedido_por_sucursales WHERE numero_cotizacion = :idCotizacion AND empresa_id = :empresa_id');
    $stmt->execute(array(':idCotizacion'=>$id, ':empresa_id'=>$_SESSION['IDEmpresa']));
    $pedido_mod = $stmt->fetch();

    if($pedido_mod['estatus_orden_pedido_id'] > 2){
      echo "no_modificar_pedido";
      return;
    }

    $activar = $_POST['activar'];
        try{
          $conn->beginTransaction();

          if($activar){
            $stmt = $conn->prepare('UPDATE cotizacion SET facturacion_directa = 1, flujo_almacen = 0, estatus_factura_id = 4 WHERE PKCotizacion = :idCotizacion AND empresa_id = '.$_SESSION['IDEmpresa']);
            $stmt->execute(array(':idCotizacion'=>$id));

            $stmt = $conn->prepare('UPDATE orden_pedido_por_sucursales SET estatus_orden_pedido_id = 2, estatus_factura_id = 4 WHERE numero_cotizacion = :idCotizacion AND empresa_id = '.$_SESSION['IDEmpresa']);
            $stmt->execute(array(':idCotizacion'=>$id));


          }
          else{
            $stmt = $conn->prepare('UPDATE cotizacion SET facturacion_directa = 0, flujo_almacen = 1, estatus_factura_id = 3 WHERE PKCotizacion = :idCotizacion AND empresa_id = '.$_SESSION['IDEmpresa']);
            $stmt->execute(array(':idCotizacion'=>$id));

            $stmt = $conn->prepare('UPDATE orden_pedido_por_sucursales SET estatus_orden_pedido_id = 1, estatus_factura_id = 3 WHERE numero_cotizacion = :idCotizacion AND empresa_id = '.$_SESSION['IDEmpresa']);
            $stmt->execute(array(':idCotizacion'=>$id));
          }
          
          
          
          if($conn->commit()){           
            echo "exito";              
          }
          else{
            echo "fallo";
          }

        }catch(Exception $ex){
          echo $ex->getMessage();
          $conn->rollBack(); 
          exit;
        }
    }
   ?>
