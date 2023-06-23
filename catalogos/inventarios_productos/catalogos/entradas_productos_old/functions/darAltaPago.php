<?php
session_start();
  require_once('../../../include/db-conn.php');

  $id = $_POST['compra'];
  $fechaPago = $_POST['fecha'];
  $cuenta = $_POST['cuenta'];
  $tipoPago = $_POST['tipo_pago'];
  $importePago = $_POST['importe'];

  $stmt = $conn->prepare('INSERT INTO pagos_productos (Fecha_Pago,FKCuenta,Tipo_Pago,Importe,FKCompra) VALUES (:fecha,:cuenta,:tipo,:importe,:compra)');
  $stmt->bindValue(':fecha',$fechaPago);
  $stmt->bindValue(':cuenta',$cuenta);
  $stmt->bindValue(':tipo',$tipoPago);
  $stmt->bindValue(':importe',$importePago);
  $stmt->bindValue(':compra',$id);
  $stmt->execute();

  $stmt = $conn->prepare('SELECT Importe FROM compras_productos WHERE PKCompra = :id');
  $stmt->bindValue(':id',$id);
  $stmt->execute();
  $importeTotal = $stmt->fetch()['Importe'];

  $importe = 0;
  $stmt = $conn->prepare('SELECT Importe FROM pagos_productos WHERE FKCompra = :id');
  $stmt->bindValue(':id',$id);
  $stmt->execute();
  $rowCount = $stmt->rowCount();
  while($row = $stmt->fetch()){
    $importe += $row['Importe'];
  }
  $estatus = 1;
  if($importe < $importeTotal){
    $estatus = 2;
  }else if($importe == $importeTotal){
    $estatus = 3;
  }

  $stmt = $conn->prepare('UPDATE compras_productos SET Estatus = :estatus WHERE PKCompra = :id');
  $stmt->bindValue(':estatus',$estatus);
  $stmt->bindValue(':id',$id);
  $stmt->execute();

  $stmt = $conn->prepare('INSERT INTO bitacora_compras_productos (FKUsuario,Fecha_Movimiento,FKMensaje,FKCompraProducto) VALUES (:usuario,:fecha,:mensaje,:compra)');
  $stmt->bindValue(':usuario',$_SESSION['PKUsuario']);
  $stmt->bindValue(':fecha',date('Y-m-d'));
  $stmt->bindValue(':mensaje',11);
  $stmt->bindValue(':compra',$id);
  $stmt->execute();





?>
