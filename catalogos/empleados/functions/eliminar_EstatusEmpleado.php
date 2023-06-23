<?php

session_start();

  if(isset($_SESSION["Usuario"]) && ($_SESSION["FKRol"] == 1 || $_SESSION["FKRol"] == 4)){
    require_once('../../../include/db-conn.php');
      if(isset($_POST['txtIdEstatusD'])){
        $id = $_POST['txtIdEstatusD'];
        try{
          $stmt = $conn->prepare('DELETE FROM estatus_empleado WHERE PKEstatusEmpleado= :id');
          $stmt->execute(array(':id'=>$id));
          header('Location:../estatus_empleado.php');
        }catch(PDOException $ex){
          echo $ex->getMessage();
        }
      }
  }else {
    header("location:../../dashboard.php");
  }
 ?>
