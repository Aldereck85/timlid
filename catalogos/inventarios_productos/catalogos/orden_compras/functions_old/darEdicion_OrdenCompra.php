<?php
session_start();
    require_once('../../../include/db-conn.php');
    $idLast = 0;
    $compra = $_POST['txtReferencia'];
    $idCompra = $_POST['txtIdCompra'];
    //$fechaEmision = $_POST['txtFechaEmision'];
    //$fechaEstimada = $_POST['txtFechaEstimada'];
    //$proveedor = $_POST['cmbProveedor'];
    //$observaciones = $_POST['txaObservaciones'];
    //$envio = $_POST['cmbDireccionEnvio'];
    //productos de oc
    $ids = $_POST['txtIdProductos'];
    $precios = $_POST['txtPrecios'];
    $cantidades = $_POST['txtCantidades'];
    $unidades = $_POST['txtUnidades'];
    $importes = $_POST['txtImportes'];
    $total = 0;

    for ($i=0; $i < count($importes); $i++) {
      $total += $importes[$i];
    }
    $total = $total *1.16;


    try{

      for ($i=0; $i < count($ids); $i++) {
        $stmt = $conn->prepare('SELECT * FROM productos_oc WHERE FKOrdenCompra = :id AND FKProducto = :prod');
        $stmt->bindValue(':id',$idCompra);
        $stmt->bindValue(':prod',$ids[$i]);
        $stmt->execute();
        $rowCount = $stmt->rowCount();

        if($rowCount == 0){
          $stmt = $conn->prepare('INSERT INTO productos_oc (FKOrdenCompra,FKProducto,Cantidad,Precio_Unitario,Importe) VALUES (:ordenCompra,:producto,:cantidad,:precio,:importe)');
          $stmt->bindValue(':ordenCompra',$idCompra);
          $stmt->bindValue(':producto',$ids[$i]);
          $stmt->bindValue(':cantidad',$cantidades[$i]);
          $stmt->bindValue(':precio',$precios[$i]);
          $stmt->bindValue(':importe',$importes[$i]);
          $stmt->execute();
        }else{
          $importe = $cantidades[$i] * $precios[$i];
          $stmt = $conn->prepare('UPDATE productos_oc SET Cantidad = :cantidad, Precio_Unitario= :precio, Importe= :importe WHERE FKOrdenCompra = :id AND FKProducto = :prod');
          $stmt->bindValue(':cantidad',$cantidades[$i]);
          $stmt->bindValue(':precio',$precios[$i]);
          $stmt->bindValue(':importe',$importe);
          $stmt->bindValue(':id',$idCompra);
          $stmt->bindValue(':prod',$ids[$i]);
          $stmt->execute();
        }
      }
      $stmt = $conn->prepare('UPDATE orden_compra SET Importe= :total WHERE PKOrdenCompra = :id');
      $stmt->bindValue(':total',$total);
      $stmt->bindValue(':id',$idCompra);
      $stmt->execute();

      $stmt = $conn->prepare('INSERT INTO bitacora_compras (FKUsuario,Fecha_Movimiento,FKMensaje,FKOrdenCompra) VALUES (:usuario,:fecha,:mensaje,:compra)');
      $stmt->bindValue(':usuario',$_SESSION['PKUsuario']);
      $stmt->bindValue(':fecha',date('Y-m-d'));
      $stmt->bindValue(':mensaje',15);
      $stmt->bindValue(':compra',$idCompra);
      $stmt->execute();

      echo $total;

    }catch(PDOException $ex){
      echo $ex->getMessage();
    }

?>
