<?php
session_start();
require_once('../../../include/db-conn.php');

  $idProducto = $_POST['data3'];
  $cantidad = $_POST['data2'];
  $lote = $_POST['data1'];
  $PKPUsuario = $_SESSION['PKUsuario'];

  try{ 
    $stmt = $conn->prepare('SELECT * FROM traspaso_temp WHERE producto_id = :producto_id AND lote = :lote AND usuario_id = :usuario_id');
    $stmt->bindValue(':producto_id',$idProducto);
    $stmt->bindValue(':lote',$lote);
    $stmt->bindValue(':usuario_id',$PKPUsuario);
    $stmt->execute();
    $row = $stmt->fetch();
    $num = $stmt->rowCount();
  }catch(PDOException $ex){
    echo $ex->getMessage();
  }

  if($num < 1){
    try{ 
      $stmt = $conn->prepare('INSERT INTO traspaso_temp (producto_id, cantidad, lote, usuario_id) VALUES (:producto_id, :cantidad, :lote, :user_id)');
      $stmt->bindValue(':producto_id',$idProducto);
      $stmt->bindValue(':cantidad',$cantidad);
      $stmt->bindValue(':lote',$lote);
      $stmt->bindValue(':user_id',$PKPUsuario);
      if($stmt->execute())
        echo "exito";
        else
          echo "fallo";
    }catch(PDOException $ex){
      echo $ex->getMessage();
    }    
  }else{
    try{ 
      $stmt = $conn->prepare('UPDATE traspaso_temp SET cantidad = :cantidad WHERE producto_id = :producto_id AND lote = :lote AND usuario_id = :user_id');
      $stmt->bindValue(':producto_id',$idProducto);
      $stmt->bindValue(':cantidad',$cantidad);
      $stmt->bindValue(':lote',$lote);
      $stmt->bindValue(':user_id',$PKPUsuario);
      if($stmt->execute())
        echo "exito";
        else
          echo "fallo";
    }catch(PDOException $ex){
      echo $ex->getMessage();
    }    
  }
 ?>
