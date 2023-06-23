<?php
  require_once('../../../include/db-conn.php');
  $id = $_POST['idProveedorD'];
  if(isset($_POST['idProveedorD'])){
    try{
      $stmt = $conn->prepare("DELETE FROM proveedores WHERE PKProveedor=?");
      $stmt->execute(array($id));
      header('Location:../index.php');
    }catch(PDOException $ex){
      echo $ex->getMessage();
    }
  }
?>
