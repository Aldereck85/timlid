<?php
  require_once('../../../../../include/db-conn.php');

  if(isset($_POST['txtIdCuentaD'])){
    $id = $_POST['txtIdCuentaD'];
    $proveedor = $_POST['txtIdP'];
    try{
      $stmt = $conn->prepare("DELETE FROM cuentas_bancarias_proveedores WHERE PKCuentaProveedor=?");
      $stmt->execute(array($id));
      header('Location:../index.php?id='.$proveedor);
    }catch(PDOException $ex){
      echo $ex->getMessage();
    }
  }
?>
