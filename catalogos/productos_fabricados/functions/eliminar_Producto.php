<?php
  require_once('../../../include/db-conn.php');
  $id = $_POST['idProductoD'];
  if(isset($_POST['idProductoD'])){
    try{
      $stmt = $conn->prepare("DELETE FROM productos_fabricados WHERE PKProductoFabricado=?");
      $stmt->execute(array($id));
      header('Location:../index.php');
    }catch(PDOException $ex){
      echo $ex->getMessage();
    }
  }
?>
