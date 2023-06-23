<?php
session_start();

  if(isset($_SESSION["Usuario"])){
    require_once('../../../include/db-conn.php');
        $id =  $_POST['idAlmacenU'];
        $almacen = $_POST['txtAlmacenU'];
        $calle = $_POST['txtCalleU'];
        $numExterior = $_POST['txtNeU'];
        $prefijo = $_POST['prefijo'];
        $numInterior = strtoupper($_POST['txtNiU']);
        $colonia = $_POST['txtColoniaU'];
        $ciudad = $_POST['txtCiudadU'];
        $estado = $_POST['cmbEstadosU'];
        $pais = $_POST['cmbPaisU'];
        //$telefono = $_POST['telefono'];
       
        try{
          $stmt = $conn->prepare('UPDATE almacenes SET Almacen=:locacion , Direccion=:calle , Exterior=:numExt , Prefijo=:prefijo , Interior=:numInt , Colonia=:colonia, Ciudad=:ciudad, FKEstado=:estado, FKPais=:pais WHERE PKAlmacen = :id');
          if($stmt->execute(array(':locacion'=>$almacen,':calle'=>$calle, ':numExt'=>$numExterior,':prefijo'=>$prefijo, ':numInt'=>$numInterior, ':colonia'=>$colonia, ':ciudad'=>$ciudad, ':estado'=>$estado, ':pais'=>$pais, ':id'=>$id ))){
            echo "exito";
          }else{
            echo "fallo";
          }
          //$stmt->execute();
          //header('Location:../index.php');
        }catch(PDOException $ex){
          echo $ex->getMessage();
        }
      }
 ?>