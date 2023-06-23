<?php
  require_once('../../../../../include/db-conn.php');

  if(isset($_POST['txtIdDomicilioEnvioD'])){
    $id = $_POST['txtIdDomicilioEnvioD'];
    $proveedor = $_POST['txtIdP'];
    try{
      $stmt = $conn->prepare("DELETE FROM domicilio_de_envio_proveedores WHERE PKDomicilio=?");
      $stmt->execute(array($id));
      header('Location:../index.php?id='.$proveedor);
    }catch(PDOException $ex){
      echo $ex->getMessage();
    }
  }
?>
