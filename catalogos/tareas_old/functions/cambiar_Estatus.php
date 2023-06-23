<?php
session_start();
if(isset($_SESSION["Usuario"])){
  require_once('../../../include/db-conn.php');

  if(isset($_GET['id'])){
    $id = $_GET['id'];
    $estatus = $_GET['estatus'];
    try{
      $stmt = $conn->prepare("UPDATE tareas_pendientes set Estatus = :estatus WHERE PKTarea = :id");
      $stmt->bindValue(':estatus',$estatus);
      $stmt->bindValue(':id', $id, PDO::PARAM_INT);
      $stmt->execute();
      header('Location:../index.php');
    }catch(PDOException $ex){
      echo $ex->getMessage();
    }
  }
}
else{
  header('Location:../index.php');
}
?>
