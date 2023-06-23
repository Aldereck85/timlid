<?php
  session_start();
  if(isset($_SESSION["Usuario"])){
    require_once('../../../../include/db-conn.php');
        $id =  $_POST['idSubcategoriaU'];
        $nombreEstatus = $_POST['txtNombreU'];
        $fksubcategoria = $_POST['txtCategoriaU'];
        try{
          $stmt = $conn->prepare('UPDATE subcategorias_gastos set Nombre= :nombre, FKCategoria= :fkcategoria WHERE PKSubcategoria = :id');
          $stmt->bindValue(':nombre',$nombreEstatus);
          $stmt->bindValue(':fkcategoria',$fksubcategoria);
          $stmt->bindValue(':id', $id);
          
          if($stmt->execute()){
            echo "1";
          }else{
            echo "0";
          }
        }catch(PDOException $ex){
          echo $ex->getMessage();
        }
    $con = null;
    $db = null;
    $stmt = null;
  }
?>

