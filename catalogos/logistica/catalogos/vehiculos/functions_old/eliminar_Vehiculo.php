<?php
  require_once('../../../include/db-conn.php');
  $id = $_POST['idVehiculoD'];
  if(isset($_POST['idVehiculoD'])){
    try{
      $stmt = $conn->prepare("DELETE FROM vehiculos WHERE PKVehiculo=?");
      $stmt->execute(array($id));
      header('Location:../index.php');
    }catch(PDOException $ex){
      echo $ex->getMessage();
    }
  }
?>
