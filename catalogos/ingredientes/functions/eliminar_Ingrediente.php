<?php
  require_once('../../../include/db-conn.php');
  $id = $_POST['idIngredienteD'];
  if(isset($_POST['idIngredienteD'])){
    try{
      $stmt = $conn->prepare("DELETE FROM ingredientes WHERE PKIngrediente=?");
      $stmt->execute(array($id));
      header('Location:../index.php');
    }catch(PDOException $ex){
      echo $ex->getMessage();
    }
  }
?>
