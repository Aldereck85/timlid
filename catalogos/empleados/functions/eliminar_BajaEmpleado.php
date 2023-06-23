<?php
  require_once('../../../include/db-conn.php');
  $id = $_POST['idEmpleadoD'];
  if(isset($_POST['idEmpleadoD'])){
    try{
      $stmt = $conn->prepare("DELETE FROM empleados WHERE PKEmpleado=?");
      $stmt->execute(array($id));
      header('Location:../bajas_empleados.php');
    }catch(PDOException $ex){
      echo $ex->getMessage();
    }
  }
?>
