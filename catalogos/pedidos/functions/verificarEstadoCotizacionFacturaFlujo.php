<?php
  require_once('../../../include/db-conn.php');
  $json = new \stdClass();

  if(isset($_POST['idCotizacion'])){
    $id =  $_POST['idCotizacion'];

        try{
          $stmt = $conn->prepare('SELECT estatus_factura_id, facturacion_directa, flujo_almacen FROM cotizacion WHERE PKCotizacion = :id');
          $stmt->execute(array(':id'=>$id));
          $row = $stmt->fetch();
          $json->estatus_cotizacion = $row['estatus_factura_id'];
          $json->facturacion_directa = $row['facturacion_directa'];
          $json->flujo_almacen = $row['flujo_almacen'];
          $json = json_encode($json);
          echo $json;

        }catch(Exception $e){
          echo $e->getMessage();
          exit;
        }
    }
   ?>
