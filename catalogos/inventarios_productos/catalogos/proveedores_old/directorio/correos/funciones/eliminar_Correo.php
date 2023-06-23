<?php
  require_once('../../../../../include/db-conn.php');

  if(isset($_POST['idCorreoD'])){
    $id = $_POST['idCorreoD'];
    $proveedor = $_POST['txtIdP'];
    try{
      $stmt = $conn->prepare("DELETE FROM correos_proveedores WHERE PKEmail_Proveedor=?");
      $stmt->execute(array($id));
      header('Location:../index.php?id='.$proveedor);
    }catch(PDOException $ex){
      echo $ex->getMessage();
    }
  }
?>
