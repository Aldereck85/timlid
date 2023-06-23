<?php
  require_once('../../../include/db-conn.php');
  $id = $_GET['id'];
  if(isset($_GET['id'])){
    try{

      $stmt = $conn->prepare("SELECT FKEmpleado FROM vacaciones WHERE PKVacaciones = :id ");
      $stmt->bindValue(':id',$id);
      $stmt->execute();
      $row = $stmt->fetch();
      $fKEmpleado = $row['FKEmpleado'];
      
      $stmt = $conn->prepare("DELETE FROM vacaciones WHERE PKVacaciones=?");
      $stmt->execute(array($id));
      header('Location:../vacaciones.php?id='.$fKEmpleado);
    }catch(PDOException $ex){
      echo $ex->getMessage();
    }
  }
?>
