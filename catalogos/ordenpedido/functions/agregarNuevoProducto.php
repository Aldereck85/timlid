<?php
session_start();
require_once('../../../include/db-conn.php');

  $idProducto = $_POST['idProducto'];
  $idCliente = $_POST['idCliente'];
  $costo = $_POST['costo'];


  try{

    $stmt = $conn->prepare('INSERT INTO costo_especial_producto_cliente (CostoEspecial,FKTipoMoneda, FKCliente, FKProducto) VALUES (:costo,100,:idCliente, :idProducto)');
    $stmt->bindValue(':costo',$costo);
    $stmt->bindValue(':idCliente',$idCliente);
    $stmt->bindValue(':idProducto',$idProducto);
    if($stmt->execute())
      echo "exito";
      else
        echo "fallo";
  }catch(PDOException $ex){
    echo $ex->getMessage();
  }
 ?>
