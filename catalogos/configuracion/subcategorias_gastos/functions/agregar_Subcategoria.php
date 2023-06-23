<?php
session_start();
  if(isset($_SESSION["Usuario"])){
    require_once('../../../../include/db-conn.php');
        $nombre = $_POST['nombreSubcategoria'];
        $fkcategoria = $_POST['fkcategoria'];
        try{
          $stmt = $conn->prepare('INSERT INTO subcategorias_gastos (Nombre,FKCategoria)VALUES(:nombre,:fkcategoria)');
          $stmt->bindValue(':nombre',$nombre);
          $stmt->bindValue(':fkcategoria',$fkcategoria);
          if ($stmt->execute()){
            echo "exito";
          }else{
            echo "fallo";
          }
        }catch(PDOException $ex){
          echo $ex->getMessage();
        }
        $con = null;
        $db = null;
        $stmt = null;
      }
 ?>

