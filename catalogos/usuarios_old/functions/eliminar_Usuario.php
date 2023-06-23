<?php
  require_once('../../../include/db-conn.php');
  $id = $_POST['idUsuarioD'];
  if(isset($_POST['idUsuarioD'])){
    try{
      $stmt = $conn->prepare("DELETE FROM usuarios WHERE PKUsuario=?");
      $stmt->execute(array($id));
      header('Location:../index.php');
    }catch(PDOException $ex){
      echo $ex->getMessage();
    }
  }
?>
