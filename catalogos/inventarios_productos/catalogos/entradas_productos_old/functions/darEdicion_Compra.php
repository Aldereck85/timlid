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
        $stmt = $conn->prepare('SELECT * FROM productos_cc WHERE FKCompra = :id AND FKProducto = :prod');
        $stmt->bindValue(':id',$idCompra);
        $stmt->bindValue(':prod',$ids[$i]);
        $stmt->execute();
        $rowCount = $stmt->rowCount();

        if($rowCount == 0){
          $stmt = $conn->prepare('INSERT INTO productos_cc (FKCompra,FKProducto,Cantidad_Recibida,Precio_Unitario,Importe) VALUES (:compra,:producto,:cantidad,:precio,:importe)');
          $stmt->bindValue(':compra',$idCompra);
          $stmt->bindValue(':producto',$ids[$i]);
          $stmt->bindValue(':cantidad',$cantidades[$i]);
          $stmt->bindValue(':precio',$precios[$i]);
          $stmt->bindValue(':importe',$importes[$i]);
          $stmt->execute();
        }else{
          $importe = $cantidades[$i] * $precios[$i];
          $stmt = $conn->prepare('UPDATE productos_cc SET Cantidad_Recibida = :cantidad, Precio_Unitario= :precio, Importe= :importe WHERE FKCompra = :id AND FKProducto = :prod');
          $stmt->bindValue(':cantidad',$cantidades[$i]);
          $stmt->bindValue(':precio',$precios[$i]);
          $stmt->bindValue(':importe',$importe);
          $stmt->bindValue(':id',$idCompra);
          $stmt->bindValue(':prod',$ids[$i]);
          $stmt->execute();
        }
      }
      $importeCompra = 0;
      $stmt = $conn->prepare('SELECT Importe FROM productos_cc WHERE FKCompra = :id');
      $stmt->bindValue(':id',$idCompra);
      $stmt->execute();
      while($row = $stmt->fetch()){
        $importeCompra += $row['Importe'];
      }

      $stmt = $conn->prepare('UPDATE compras_productos SET Importe= :total WHERE PKCompra = :id');
      $stmt->bindValue(':total',$importeCompra);
      $stmt->bindValue(':id',$idCompra);
      $stmt->execute();

      $stmt = $conn->prepare('INSERT INTO bitacora_compras_productos (FKUsuario,Fecha_Movimiento,FKMensaje,FKCompraProducto) VALUES (:usuario,:fecha,:mensaje,:compra)');
      $stmt->bindValue(':usuario',$_SESSION['PKUsuario']);
      $stmt->bindValue(':fecha',date('Y-m-d'));
      $stmt->bindValue(':mensaje',14);
      $stmt->bindValue(':compra',$idCompra);
      $stmt->execute();

      echo $idCompra;

    }catch(PDOException $ex){
      echo $ex->getMessage();
    }

?>
