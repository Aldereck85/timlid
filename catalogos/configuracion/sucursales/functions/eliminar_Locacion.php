<?php
session_start();
if(isset($_SESSION["Usuario"])){
  require_once('../../../../include/db-conn.php');
  if(isset($_POST['idLocacionD'])){
    $id = $_POST['idLocacionD'];
    try{
      $stmt = $conn->prepare("DELETE FROM sucursales WHERE id=?");
      //$stmt->execute(array($id));
      if ($stmt->execute(array($id))){
        echo "1";
      }else{
        echo "0";
      }
      //header('Location:../index.php');
    }catch(PDOException $ex){
      echo $ex->getMessage();
    }
  }
  $con = null;
  $db = null;
  $stmt = null;
}
?>
