<?php
  require_once('../../../include/db-conn.php');
  $id = $_POST['idRolloD'];
  if(isset($_POST['idRolloD'])){
    try{
      $stmt = $conn->prepare("DELETE FROM rollos WHERE PKRollo=?");
      $stmt->execute(array($id));
      header('Location:../index.php');
    }catch(PDOException $ex){
      echo $ex->getMessage();
    }
  }
?>
