<?php
session_start();

require_once('../../../include/db-conn.php');

$PKPUsuario = $_SESSION['PKUsuario'];


  try{ 
    $stmt = $conn->prepare('DELETE FROM traspaso_temp WHERE producto_id = :producto_id AND lote = :lote  AND usuario_id = :usuario_id');
    $stmt->bindValue(':producto_id',$_POST['idProducto']);
    $stmt->bindValue(':lote',$_POST['lote']);
    $stmt->bindValue(':usuario_id',$PKPUsuario);
    if($stmt->execute())
        echo "exito";
        else
          echo "fallo";
  }catch(PDOException $ex){
    echo $ex->getMessage();
  }
  
 ?>