<?php
  require_once('../../../include/db-conn.php');
  $id = $_POST['id'];
  $lugar = $_POST['lugar'];
  $stmt = $conn->prepare('UPDATE orden_compra SET FKDireccionEnvio= :lugar WHERE PKOrdenCompra = :id');
  $stmt->bindValue(':lugar',$lugar);
  $stmt->bindValue(':id',$id);
  echo $stmt->execute();
?>
