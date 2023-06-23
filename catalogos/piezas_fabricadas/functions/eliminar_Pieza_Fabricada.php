<?php
  require_once('../../../include/db-conn.php');
  $id = $_POST['idPiezaD'];
  if(isset($_POST['idPiezaD'])){
    try{
      $stmt = $conn->prepare("DELETE FROM piezas_fabricadas WHERE PKPiezaFabricada=?");
      $stmt->execute(array($id));
      header('Location:../index.php');
    }catch(PDOException $ex){
      echo $ex->getMessage();
    }
  }
?>
