<?php
session_start();

  if(isset($_SESSION["Usuario"]) && ($_SESSION["FKRol"] == 4 || $_SESSION["FKRol"] == 1)){
    require_once('../../../include/db-conn.php');
      if(isset($_GET['id'])){
        $id =  $_GET['id'];
          
        $stmt = $conn->prepare('SELECT count(*) FROM poliza_autos WHERE FKVehiculo= :id');
        $stmt->execute(array(':id'=>$id));
        $polizaExistente = $stmt->fetchColumn();
          
        if($polizaExistente > 0){
          header("location:editar_Poliza.php?id=".$id);
        }else{
          header("location:agregar_Poliza.php?id=".$id);
        }
      }
  }else {
    header("location:../../dashboard.php");
  }
 ?>
