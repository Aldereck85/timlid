<?php
session_start();

if(isset($_SESSION["Usuario"])){
  require_once('../../../include/db-conn.php');
  
  if(isset($_GET['idProyectoD'])){
    $id = $_GET['idProyectoD'];
    try{
      $stmt = $conn->prepare("DELETE FROM proyectos WHERE PKProyecto=?");
      $stmt->execute(array($id));
      header('Location:../../proyectos/index.php');
    }catch(PDOException $ex){
      echo $ex->getMessage();
    }
  }
}
else{
  header('Location:../index.php');
}
?>
