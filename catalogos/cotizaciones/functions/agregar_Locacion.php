<?php
session_start();

  if(isset($_SESSION["Usuario"])){
    require_once('../../../include/db-conn.php');
    $idemp = $_SESSION["IDEmpresa"];
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
        $actInventario = $_POST['actInventario'];
        $zonaSalarioMinimo = $_POST['zonaSalarioMinimo'];
        if(!$calle){
          $calle = 'Av. Vallarta';
        }
        if(!$numExterior){
          $numExterior = 237;
        }
        if(!$prefijo){
          $prefijo = 'Interior';
        }
        if(!$numInterior){
          $numInterior = 45;
        }
        if(!$colonia){
          $colonia = 'Centro';
        }
        if(!$municipio){
          $municipio = 'Guadalajara';
        }
        if(!$estado){
          $estado = 14;
        }
        if(!$pais){
          $pais = 146;
        }
        if(!$telefono){
          $telefono = '3323025669';
        }
        
        try{
          $stmt = $conn->prepare('INSERT INTO sucursales (sucursal, calle, numero_exterior, prefijo, numero_interior, colonia, municipio, estado_id, pais_id, telefono, activar_inventario, empresa_id, estatus, zona_salario_minimo) VALUES (:locacion, :calle, :numExt, :prefijo, :numInt, :colonia, :municipio, :estado, :pais, :telefono, :actInventario, :idemp, :estatus, :zona_salario_minimo)');
          if($stmt->execute(array(':locacion'=>$locacion,':calle'=>$calle, ':numExt'=>$numExterior,':prefijo'=>$prefijo, ':numInt'=>$numInterior, ':colonia'=>$colonia, ':municipio'=>$municipio, ':estado'=>$estado, ':pais'=>$pais, ':telefono'=>$telefono, ':actInventario'=>$actInventario, ':idemp'=>$idemp, ':estatus'=>1, ':zona_salario_minimo'=>$zonaSalarioMinimo))){
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
