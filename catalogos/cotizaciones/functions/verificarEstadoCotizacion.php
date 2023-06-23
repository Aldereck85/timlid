<?php
  require_once('../../../include/db-conn.php');

  if(isset($_POST['idCotizacion'])){
    $id =  $_POST['idCotizacion'];

        try{
          $stmt = $conn->prepare('SELECT estatus_cotizacion_id FROM cotizacion WHERE PKCotizacion = :id');
          $stmt->execute(array(':id'=>$id));
          $row = $stmt->fetch();
          echo $row['estatus_cotizacion_id'];

        }catch(Exception $e){
          echo $e->getMessage();
          exit;
        }
    }
   ?>
