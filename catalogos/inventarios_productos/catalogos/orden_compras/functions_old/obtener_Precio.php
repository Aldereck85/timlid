<?php
  require_once('../../../include/db-conn.php');
  $id = $_POST['id'];
  $stmt = $conn->prepare('SELECT * FROM productos WHERE PKProducto = :id');
  $stmt->bindValue(':id',$id);
  $stmt->execute();
  $row = $stmt->fetch();

  echo $row['PrecioUnitario'];

 ?>
