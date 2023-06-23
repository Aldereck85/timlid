<?php
session_start();
if(isset($_SESSION["Usuario"])){
  require_once('../../../../include/db-conn.php');
  if(isset($_POST['idVendedorD'])){
    $id = $_POST['idVendedorD'];
    try{
      //DELETE SUBCATEGORIA
      $stmt = $conn->prepare("DELETE FROM vendedores WHERE PKVendedor=?");
        if ($stmt->execute(array($id))){
          echo "1";
        }else{
          echo "0";
        }
    }catch(PDOException $ex){
      echo $ex->getMessage();
    }
  }
}
?>
