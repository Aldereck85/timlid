<?php
  require_once('../../../include/db-conn.php');
  $id = $_POST['idMarcaD'];
  if(isset($_POST['idMarcaD'])){
    try{
      $stmt = $conn->prepare("DELETE FROM marcas WHERE PKMarca=?");
      $stmt->execute(array($id));
      header('Location:../index.php');
    }catch(PDOException $ex){
      echo $ex->getMessage();
    }
  }
?>
