<?php
session_start();
    require_once('../../../include/db-conn.php');
    $idLast = 0;
    $compra = $_POST['txtReferencia'];
    $fechaEmision = $_POST['txtFechaEmision'];
    $fechaEstimada = $_POST['txtFechaEstimada'];
    $proveedor = $_POST['cmbProveedor'];
    $observaciones = $_POST['txaObservaciones'];
    $envio = $_POST['cmbDireccionEnvio'];
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
    $stmt = $conn->prepare('SELECT COUNT(*) FROM orden_compra WHERE Referencia = :compra');
    $stmt->bindValue(':compra',$compra);
    $stmt->execute();
    $rowCount = $stmt->rowCount();

    try{
      $stmt = $conn->prepare('INSERT INTO orden_compra (Referencia,Fecha_de_Emision,Fecha_Deseada_Entrega,Observaciones,FKDireccionEnvio,FKProveedor,Estatus,Importe) VALUES (:compra,:fecha_emision,:fecha_deseada,:observaciones,:envio,:proveedor,:estatus,:importe)');
      $stmt->bindValue(':compra',$compra);
      $stmt->bindValue(':fecha_emision',$fechaEmision);
      $stmt->bindValue(':fecha_deseada',$fechaEstimada);
      $stmt->bindValue(':proveedor',$proveedor);
      $stmt->bindValue(':envio',$envio);
      $stmt->bindValue(':observaciones',$observaciones);
      $stmt->bindValue(':estatus',0);
      $stmt->bindValue(':importe',$total);
      $stmt->execute();
      $idLast = $conn->lastInsertId();

      for ($i=0; $i < count($ids); $i++) {
        $stmt = $conn->prepare('INSERT INTO productos_oc (FKOrdenCompra,FKProducto,Cantidad,Precio_Unitario,Importe) VALUES (:ordenCompra,:producto,:cantidad,:precio,:importe)');
        $stmt->bindValue(':ordenCompra',$idLast);
        $stmt->bindValue(':producto',$ids[$i]);
        $stmt->bindValue(':cantidad',$cantidades[$i]);
        $stmt->bindValue(':precio',$precios[$i]);
        $stmt->bindValue(':importe',$importes[$i]);
        $stmt->execute();
      }

      for ($i=0; $i < count($ids); $i++) {
        $stmt = $conn->prepare('UPDATE productos SET PrecioUnitario= :precio WHERE PKProducto = :id');
        $stmt->bindValue(':precio',$precios[$i]);
        $stmt->bindValue(':id',$ids[$i]);
        $stmt->execute();
      }

      $stmt = $conn->prepare('INSERT INTO bitacora_compras (FKUsuario,Fecha_Movimiento,FKMensaje,FKOrdenCompra) VALUES (:usuario,:fecha,:mensaje,:compra)');
      $stmt->bindValue(':usuario',$_SESSION['PKUsuario']);
      $stmt->bindValue(':fecha',date('Y-m-d'));
      $stmt->bindValue(':mensaje',4);
      $stmt->bindValue(':compra',$idLast);
      $stmt->execute();

      echo $idLast;

    }catch(PDOException $ex){
      echo $ex->getMessage();
    }

?>
