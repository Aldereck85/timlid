<?php
  require_once('../../../include/db-conn.php');
  if(isset($_POST['id']) && is_numeric($_POST['id'])){
    $id = $_POST['id'];
    $compra = $_POST['orden'];
    $stmt = $conn->prepare('SELECT * FROM productos_oc
      WHERE FKProducto = :id AND FKOrdenCompra = :compra');
    $stmt->bindValue(':id',$id);
    $stmt->bindValue(':compra',$compra);
    $stmt->execute();
    $row = $stmt->fetch();
    echo $row['Precio_Unitario'];
  }else{
    echo "0.00";
  }


 ?>
