<?php
  require_once('../../../include/db-conn.php');
  $id = $_POST['id'];
  $compra = $_POST['compra'];
  $stmt = $conn->prepare('SELECT pr.PrecioUnitario AS precio,pc.Cantidad_Recibida FROM productos AS pr
    LEFT JOIN productos_cc AS pc ON pr.PKProducto = pc.FKProducto
    WHERE pc.FKProducto = :id AND pc.FKCompra = :compra');
  $stmt->bindValue(':id',$id);
  $stmt->bindValue(':compra',$compra);
  $stmt->execute();
  $row = $stmt->fetch();

  $data = '{"PrecioUnitario":"'.$row['precio'].'","Cantidad":"'.$row['Cantidad_Recibida'].'"}';

  echo json_encode($data);
?>
