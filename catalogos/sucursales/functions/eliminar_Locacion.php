<?php
session_start();
if(isset($_SESSION["Usuario"])){
  require_once('../../../include/db-conn.php');
  if(isset($_POST['idLocacionD'])){
    $id = $_POST['idLocacionD'];
    try{
      $stmt = $conn->prepare("DELETE FROM locaciones WHERE PKLocacion=?");
      //$stmt->execute(array($id));
      if ($stmt->execute(array($id))){
        echo "exito";
      }else{
        echo "fallo";
      }
      //header('Location:../index.php');
    }catch(PDOException $ex){
      echo $ex->getMessage();
    }
  }
}
?>
