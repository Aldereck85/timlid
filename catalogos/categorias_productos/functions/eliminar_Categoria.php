<?php
  require_once('../../../include/db-conn.php');
  $id = $_POST['idCategoriaD'];
  if(isset($_POST['idCategoriaD'])){
    try{
      $stmt = $conn->prepare("DELETE FROM categorias_producto WHERE PKCategoriaProducto=?");
      $stmt->execute(array($id));
      header('Location:../index.php');
    }catch(PDOException $ex){
      echo $ex->getMessage();
    }
  }
?>
