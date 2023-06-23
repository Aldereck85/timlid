<?php
  require_once('../../../include/db-conn.php');
  $id = $_GET['id'];
  $vehiculo = $_GET['vehiculo'];
  if(isset($_GET['id'])){
    try{
      $stmt = $conn->prepare("DELETE FROM servicios WHERE PKServicio=?");
      $stmt->execute(array($id));
      header('Location:../servicios.php?id='.$vehiculo);
    }catch(PDOException $ex){
      echo $ex->getMessage();
    }
  }
?>
