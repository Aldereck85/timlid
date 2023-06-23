<?php
  require_once('../../../include/db-conn.php');
  $id = $_POST['id'];
  $compra = $_POST['compra'];
  $stmt = $conn->prepare('SELECT pr.PrecioUnitario AS precio,poc.Cantidad FROM productos AS pr
    LEFT JOIN productos_oc AS poc ON pr.PKProducto = poc.FKProducto
    WHERE poc.FKProducto = :id AND poc.FKOrdenCompra = :compra');
  $stmt->bindValue(':id',$id);
  $stmt->bindValue(':compra',$compra);
  $stmt->execute();
  $row = $stmt->fetch();

  $data = '{"PrecioUnitario":"'.$row['precio'].'","Cantidad":"'.$row['Cantidad'].'"}';

  echo json_encode($data);
?>
