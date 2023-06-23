<?php
  require_once('../../../include/db-conn.php');
  $id = $_POST['idPrecioD'];
  if(isset($_POST['idPrecioD'])){
    try{
      $stmt = $conn->prepare("DELETE FROM precio_lista WHERE PKPrecioLista=?");
      $stmt->execute(array($id));
      header('Location:../index.php');
    }catch(PDOException $ex){
      echo $ex->getMessage();
    }
  }
?>
