<?php
session_start();
if(isset($_SESSION["Usuario"])){
  require_once('../../../include/db-conn.php');
  if(isset($_POST['idAlmacenD'])){
    $id = $_POST['idAlmacenD'];
    try{
      $stmt = $conn->prepare("DELETE FROM almacenes WHERE PKAlmacen=?");
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
