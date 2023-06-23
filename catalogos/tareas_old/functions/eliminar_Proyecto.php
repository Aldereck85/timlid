<?php
session_start();

if(isset($_SESSION["Usuario"])){
  require_once('../../../include/db-conn.php');
  $id = $_POST['idProyectoD'];
  if(isset($_POST['idProyectoD'])){
    try{
      $stmt = $conn->prepare("DELETE FROM proyectos WHERE PKProyecto=?");
      $stmt->execute(array($id));
      header('Location:../../proyectos_copia/index.php');
    }catch(PDOException $ex){
      echo $ex->getMessage();
    }
  }
}
else{
  header('Location:../index.php');
}
?>
