<?php
  require_once('../../../../../include/db-conn.php');
  if(isset($_POST['txtIdTelefonoD'])){
    $id = $_POST['txtIdTelefonoD'];
    $proveedor = $_POST['txtIdP'];
    try{
      $stmt = $conn->prepare("DELETE FROM telefonos_proveedores WHERE PKTelefono_Proveedor=?");
      $stmt->execute(array($id));
      header('Location:../index.php?id='.$proveedor);
    }catch(PDOException $ex){
      echo $ex->getMessage();
    }
  }
?>
