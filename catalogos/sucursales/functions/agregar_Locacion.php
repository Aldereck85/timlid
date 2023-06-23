<?php
session_start();

  if(isset($_SESSION["Usuario"])){
    require_once('../../../include/db-conn.php');
        $locacion = $_POST['txtLocacion'];
        $calle = $_POST['txtCalle'];
        $numExterior = $_POST['txtNe'];
        $prefijo = $_POST['prefijo'];
        $numInterior = strtoupper($_POST['txtNi']);
        $colonia = $_POST['txtColonia'];
        $municipio = $_POST['txtMunicipio'];
        $estado = $_POST['cmbEstados'];
        $pais = $_POST['cmbPais'];
        $telefono = $_POST['telefono'];
       
        try{
          $stmt = $conn->prepare('INSERT INTO locaciones (Locacion, Calle, NumExt, Prefijo, NumInt, Colonia, Municipio, FKEstado, FKPais, Telefono)VALUES(:locacion, :calle, :numExt, :prefijo, :numInt, :colonia, :municipio, :estado, :pais, :telefono)');
          if($stmt->execute(array(':locacion'=>$locacion,':calle'=>$calle, ':numExt'=>$numExterior,':prefijo'=>$prefijo, ':numInt'=>$numInterior, ':colonia'=>$colonia, ':municipio'=>$municipio, ':estado'=>$estado, ':pais'=>$pais, ':telefono'=>$telefono ))){
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

