<?php
session_start();

  if(isset($_SESSION["Usuario"])){
    require_once('../../../include/db-conn.php');
        $almacen = $_POST['txtAlmacen'];
        $calle = $_POST['txtCalle'];
        $numExterior = $_POST['txtNe'];
        $prefijo = $_POST['prefijo'];
        $numInterior = strtoupper($_POST['txtNi']);
        $colonia = $_POST['txtColonia'];
        $ciudad = $_POST['txtCiudad'];
        $estado = $_POST['cmbEstados'];
        $pais = $_POST['cmbPais'];
        //$telefono = $_POST['telefono'];
  
        try{
          $stmt = $conn->prepare('INSERT INTO almacenes (Almacen, Direccion, Exterior, Prefijo, Interior, Colonia, Ciudad, FKEstado, FKPais)VALUES(:almacen, :calle, :numExt, :prefijo, :numInt, :colonia, :ciudad, :estado, :pais)');
          if($stmt->execute(array(':almacen'=>$almacen,':calle'=>$calle, ':numExt'=>$numExterior,':prefijo'=>$prefijo, ':numInt'=>$numInterior, ':colonia'=>$colonia, ':ciudad'=>$ciudad, ':estado'=>$estado, ':pais'=>$pais))){
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