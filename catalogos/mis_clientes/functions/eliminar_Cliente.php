<?php
  require_once('../../../include/db-conn.php');
  $id = $_POST['idClienteD'];
  if(isset($_POST['idClienteD'])){
    try{
      $stmt = $conn->prepare("DELETE FROM clientes WHERE PKCliente=?");
      $stmt->execute(array($id));
      header('Location:../index.php');
    }catch(PDOException $ex){
      echo $ex->getMessage();
    }
  }
?>
