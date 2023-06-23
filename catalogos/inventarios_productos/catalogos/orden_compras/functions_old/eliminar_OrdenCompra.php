<?php
  require_once('../../../include/db-conn.php');
  $id = $_POST['idOrdenCompraD'];
  if(isset($_POST['idOrdenCompraD'])){
    try{
      $stmt = $conn->prepare("DELETE FROM orden_compra WHERE PKOrdenCompra=?");
      $stmt->execute(array($id));
      header('Location:../index.php');
    }catch(PDOException $ex){
      echo $ex->getMessage();
    }
  }
?>
