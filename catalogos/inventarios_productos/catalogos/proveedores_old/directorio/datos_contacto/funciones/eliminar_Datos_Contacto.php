<?php
  require_once('../../../../../include/db-conn.php');

  if(isset($_POST['txtIdContactoD'])){
    $id = $_POST['txtIdContactoD'];
    $proveedor = $_POST['txtIdP'];
    try{
      $stmt = $conn->prepare("DELETE FROM datos_contacto_proveedores WHERE PKContacto_Proveedor=?");
      $stmt->execute(array($id));
      header('Location:../index.php?id='.$proveedor);
    }catch(PDOException $ex){
      echo $ex->getMessage();
    }
  }
?>
