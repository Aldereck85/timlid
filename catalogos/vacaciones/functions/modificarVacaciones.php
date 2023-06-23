<?php
session_start();
if(isset($_SESSION["Usuario"])){
  require_once('../../../include/db-conn.php');
 
  if((isset($_POST['idPermisoU']) && isset($_POST['idEstatus'])) || isset($_POST['idPermisoCanc']) && isset($_POST['idEstatusCanc']) ){

    if(isset($_POST['idPermisoU']))
      $id = $_POST['idPermisoU'];
    else
      $id = $_POST['idPermisoCanc'];

    if(isset($_POST['idEstatus']))
      $estatus = $_POST['idEstatus'];
    else
      $estatus = $_POST['idEstatusCanc'];

    try{

      $conn->beginTransaction();

      $stmt = $conn->prepare("UPDATE permiso_vacaciones SET Estatus = :estatus WHERE PKPermiso_Vacaciones = :idpermiso");
      $stmt->bindValue(":estatus", $estatus);
      $stmt->bindValue(":idpermiso", $id);
      $stmt->execute();

      $conn->commit();

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
