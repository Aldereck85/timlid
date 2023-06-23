<?php
session_start();
if(isset($_SESSION["Usuario"])){
  require_once('../../../../include/db-conn.php');
  if(isset($_POST['idSubcategoriaD'])){
    $id = $_POST['idSubcategoriaD'];
    try{
      //DELETE SUBCATEGORIA
      $stmt = $conn->prepare("DELETE FROM subcategorias_gastos WHERE PKSubcategoria=?");
        if ($stmt->execute(array($id))){
          echo "1";
        }else{
          echo "0";
        }
    }catch(PDOException $ex){
      echo $ex->getMessage();
    }
  }
  $con = null;
  $db = null;
  $stmt = null;
}
?>
