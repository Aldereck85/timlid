<?php
session_start();
if(isset($_SESSION["Usuario"])){
  require_once('../../../include/db-conn.php');

  if(isset($_POST['idVacacionesD']) && isset($_POST['idEstatusD'])){
    $id = $_POST['idVacacionesD'];
    $estatus = $_POST['idEstatusD'];
    try{

      if($estatus == 0){
        $stmt = $conn->prepare("DELETE FROM permiso_vacaciones WHERE PKPermiso_Vacaciones=?");
        $stmt->execute(array($id));
        header('Location:../index.php');
      }
      else{
        header('Location:../index.php?mensaje=1');
      }
    }catch(PDOException $ex){
      echo $ex->getMessage();
    }
  }
}
else{
  header('Location:../index.php');
}
?>
