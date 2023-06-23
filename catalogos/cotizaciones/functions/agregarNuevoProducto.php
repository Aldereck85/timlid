<?php
session_start();
require_once('../../../include/db-conn.php');

  $idProducto = $_POST['idProducto'];
  $idCliente = $_POST['idCliente'];
  $costo = $_POST['costo'];


  try{
    $stmt = $conn->prepare('SELECT id, Venta FROM operaciones_producto WHERE FKProducto = :idProducto');
    $stmt->bindValue(':idProducto',$idProducto);
    $stmt->execute();
    $nuevo_venta = $stmt->fetch();

    if($nuevo_venta['Venta'] == 0){

      try{
          $conn->beginTransaction();
          $stmt = $conn->prepare('UPDATE operaciones_producto SET Venta = 1 WHERE FKProducto = :producto_id');
          $stmt->bindValue(':producto_id',$idProducto);
          $stmt->execute();

          $stmt = $conn->prepare('UPDATE costo_venta_producto SET CostoGeneral = :costo  WHERE FKProducto = :producto_id');
          $stmt->bindValue(':costo',$costo);
          $stmt->bindValue(':producto_id',$idProducto);
          $stmt->execute();

          if($conn->commit()){
            echo "exito-nuevo-costo";
          }
          else{
            echo "fallo-nuevo-costo";
          }
      }catch(PDOException $ex){
          echo $ex->getMessage();
          echo "fallo-nuevo-costo";
          $conn->rollBack(); 
      }
    }

    echo "**********//////////////////////////////***********";

    /* Alta en clientes */
    $stmt = $conn->prepare('SELECT PKCostoEspecialProductoCliente FROM costo_especial_producto_cliente WHERE FKCliente = :idCliente AND FKProducto = :idProducto');
    $stmt->bindValue(':idCliente',$idCliente);
    $stmt->bindValue(':idProducto',$idProducto);
    $stmt->execute();
    $existe_cliente_precio = $stmt->rowCount();

    if($existe_cliente_precio < 1){
        $stmt = $conn->prepare('INSERT INTO costo_especial_producto_cliente (CostoEspecial,FKTipoMoneda, FKCliente, FKProducto) VALUES (:costo,100,:idCliente, :idProducto)');
        $stmt->bindValue(':costo',$costo);
        $stmt->bindValue(':idCliente',$idCliente);
        $stmt->bindValue(':idProducto',$idProducto);

        if($stmt->execute()){
          echo "exito-cliente";
        }
        else{
          echo "fallo-cliente";
        }
    }
    else{

        $stmt = $conn->prepare('UPDATE costo_especial_producto_cliente SET CostoEspecial = :costo_especial WHERE FKCliente = :idCliente AND FKProducto = :producto_id');
        $stmt->bindValue(':costo_especial',$costo);
        $stmt->bindValue(':idCliente',$idCliente);
        $stmt->bindValue(':producto_id',$idProducto);
        if($stmt->execute()){
          echo "exito-cliente";
        }
        else{
          echo "fallo-cliente";
        }
    }


    echo "**********//////////////////////////////***********";

    $stmt = $conn->prepare('SELECT id FROM costo_especial_producto_vendedor WHERE vendedor_id = :vendedor_id AND producto_id = :producto_id');
    $stmt->bindValue(':vendedor_id',$_SESSION['PKUsuario']);
    $stmt->bindValue(':producto_id',$idProducto);
    $stmt->execute();
    $existe = $stmt->rowCount();

    if($existe < 1){
        /*$stmt = $conn->prepare('INSERT INTO costo_especial_producto_cliente (CostoEspecial,FKTipoMoneda, FKCliente, FKProducto) VALUES (:costo,100,:idCliente, :idProducto)');
        $stmt->bindValue(':costo',$costo);
        $stmt->bindValue(':idCliente',$idCliente);
        $stmt->bindValue(':idProducto',$idProducto);*/
        $stmt = $conn->prepare('INSERT INTO costo_especial_producto_vendedor (costo_especial,tipo_moneda_id, vendedor_id, producto_id) VALUES (:costo_especial,100,:vendedor_id, :producto_id)');
        $stmt->bindValue(':costo_especial',$costo);
        $stmt->bindValue(':vendedor_id',$_SESSION['PKUsuario']);
        $stmt->bindValue(':producto_id',$idProducto);

        if($stmt->execute()){
          echo "exito-vendedor";
        }
        else{
          echo "fallo-vendedor";
        }
    }
    else{

        $stmt = $conn->prepare('UPDATE costo_especial_producto_vendedor SET costo_especial = :costo_especial WHERE vendedor_id = :vendedor_id AND producto_id = :producto_id');
        $stmt->bindValue(':costo_especial',$costo);
        $stmt->bindValue(':vendedor_id',$_SESSION['PKUsuario']);
        $stmt->bindValue(':producto_id',$idProducto);
        if($stmt->execute()){
          echo "exito-vendedor";
        }
        else{
          echo "fallo-vendedor";
        }
    }
        
  }catch(PDOException $ex){
    echo $ex->getMessage();
  }
 ?>
