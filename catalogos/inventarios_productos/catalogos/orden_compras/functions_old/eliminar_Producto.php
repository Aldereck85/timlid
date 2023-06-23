<?php
  require_once('../../../include/db-conn.php');
  $id = $_POST['idProductoD'];
  $compra = $_POST['txtCompra'];
  if(isset($_POST['idProductoD'])){
    try{
      $stmt = $conn->prepare("DELETE FROM compras_tmp WHERE PKComprastmp=?");
      $stmt->execute(array($id));
      header('Location: agregar_Productos.php?id='.$compra);
    }catch(PDOException $ex){
      echo $ex->getMessage();
    }
  }
?>
