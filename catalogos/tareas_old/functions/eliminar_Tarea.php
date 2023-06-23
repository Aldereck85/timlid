<?php
session_start();
if(isset($_SESSION["Usuario"])){
  require_once('../../../include/db-conn.php');
  $id = $_POST['idTareaD'];
  if(isset($_POST['idTareaD'])){
    try{
      $stmt = $conn->prepare("DELETE FROM tareas_pendientes WHERE PKTarea=?");
      $stmt->execute(array($id));
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
