<?php
  require_once('../../../include/db-conn.php');
  $id = $_POST['idPuestoD'];
  if(isset($_POST['idPuestoD'])){
    try{
      $stmt = $conn->prepare("DELETE FROM Puestos WHERE PKPuesto=?");
      $stmt->execute(array($id));
      header('Location:../index.php');
    }catch(PDOException $ex){
      echo $ex->getMessage();
    }
  }
?>
