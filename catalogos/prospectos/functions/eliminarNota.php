<?php
session_start();
require_once('../../../include/db-conn.php');

  $idNota = $_POST['idNota'];

  try{

    $stmt = $conn->prepare('DELETE FROM bitacora_notas WHERE PKBitacoraNotas = :idnota');
    $stmt->bindValue(':idnota',$idNota);
    if($stmt->execute())
      echo "exito";
      else
        echo "fallo";
  }catch(PDOException $ex){
    echo $ex->getMessage();
  }
 ?>
