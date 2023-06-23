<?php
session_start();
  if(isset($_SESSION["Usuario"]) && ($_SESSION["FKRol"] == 1 || $_SESSION["FKRol"] == 3 || $_SESSION["FKRol"] == 4 || $_SESSION["FKRol"] == 6)){
    require_once('../../../include/db-conn.php');
    $id = $_POST['idEnvioD'];
    if(isset($_POST['idEnvioD'])){
      try{
        $stmt = $conn->prepare("DELETE FROM envios WHERE PKEnvio=?");
        $stmt->execute(array($id));
        header('Location:../index.php');
      }catch(PDOException $ex){
        echo $ex->getMessage();
      }
    }
  }
  else{
    header('Location:../index.php');
  }
?>
