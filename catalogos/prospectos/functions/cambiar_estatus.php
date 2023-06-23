<?php
  require_once('../../../include/db-conn.php');
  $id = $_GET['id'];
  $estatus = $_GET['estatus'];
  if(isset($_GET['id'])){
    try{
      $stmt = $conn->prepare("UPDATE clientes SET FKEstatus = $estatus WHERE PKCliente=?");
      $stmt->execute(array($id));
      header('Location:../index.php');
    }catch(PDOException $ex){
      echo $ex->getMessage();
    }
  }
?>
