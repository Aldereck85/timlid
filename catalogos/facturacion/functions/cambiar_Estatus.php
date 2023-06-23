<?php
  session_start();
  require_once('../../../include/db-conn.php');

  if(isset($_GET['id'])){
    $id = $_GET['id'];
    $estatus = $_GET['estatus'];
    try{

      $stmt = $conn->prepare("UPDATE facturacion set Estatus = :estatus WHERE PKFacturacion = :id");
      $stmt->bindValue(':estatus',$estatus);
      $stmt->bindValue(':id', $id, PDO::PARAM_INT);
      $stmt->execute();
      header('Location:../index.php');
    }catch(PDOException $ex){
      echo $ex->getMessage();
    }
  }
  header('Location:../index.php'); 
?>

