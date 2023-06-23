<?php
  require_once('../../../../../include/db-conn.php');
  $id = $_GET['id'];
  $cliente = $_GET['cliente'];
  if(isset($_GET['id'])){
    try{
      $stmt = $conn->prepare("DELETE FROM domicilio_fiscal WHERE PKDomicilioFiscal=?");
      $stmt->execute(array($id));
      header('Location:../index.php?id='.$cliente);
    }catch(PDOException $ex){
      echo $ex->getMessage();
    }
  }
?>
