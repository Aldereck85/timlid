<?php
  require_once('../../../include/db-conn.php');
  if(isset($_POST['id']) && is_numeric($_POST['id']) && isset($_POST['orden']) && is_numeric($_POST['orden'])){
    $id = $_POST['id'];
    $compra = $_POST['orden'];
    $stmt = $conn->prepare('SELECT Cantidad FROM productos_oc AS poc
                  WHERE poc.FKProducto = :id AND poc.FKOrdenCompra = :orden');
    $stmt->bindValue(':id',$id);
    $stmt->bindValue(':orden',$compra);
    $stmt->execute();
    $row = $stmt->fetch();
    $canTotal = $row['Cantidad'];
    echo $canTotal;

  }

?>
