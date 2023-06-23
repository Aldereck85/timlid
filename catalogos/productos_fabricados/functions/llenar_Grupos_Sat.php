<?php
  require_once('../../../include/db-conn.php');
  session_start();

  if(isset($_GET['id'])){
    try{
      $id =  $_GET['id'];
      $stmt = $conn->prepare('SELECT * FROM `grupo_sat` WHERE Estatus = 1 AND FKDivision = :id');
      $stmt->execute(array(':id'=>$id));
    }catch(Exception $e){
      echo $e->getMessage();
      exit;
    }
        while (($row = $stmt->fetch()) !== false) {
          //$pieza = $row['PKPiezaProducto'];
            echo "<div class='row' style='padding:5px;'><div class='col-lg-6'>".$row['NombrePiezas']."</div><div class='col-lg-3'>".$row['CantidadPiezas']."</div><div class='col-lg-3'>";
        }
    }
   ?>

   <option value="'+idPresentecion+'">'+opcion+'</option>
