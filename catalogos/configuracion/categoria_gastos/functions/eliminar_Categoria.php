<?php
session_start();
date_default_timezone_set('America/Mexico_City');
if(isset($_SESSION["Usuario"])){
  require_once('../../../../include/db-conn.php');
  if(isset($_POST['idEstatusD'])){
    $id = $_POST['idEstatusD'];
    $now = date("Y-m-d H:i:s");
    $usuario = $_POST['usuario'];

    try{
      //DELETE SUBCATEGORIA
      /*$stmt1 = $conn->prepare("DELETE FROM subcategorias_gastos WHERE FKCategoria=?");
      if ($stmt1->execute(array($id))){
        $stmt = $conn->prepare("DELETE FROM categoria_gastos WHERE PKCategoria=?");
        if ($stmt->execute(array($id))){
          echo "1";
        }else{
          echo "0";
        }
      }else{
        echo "0";
      }*/

      $query = sprintf("UPDATE categoria_gastos SET estatus = 0,updated_at = :updated_at, usuario_edicion_id = :usuario_edicion_id WHERE PKCategoria=:id");
      $stmt = $conn->prepare($query);
      $stmt->bindValue(':updated_at',$now);
      $stmt->bindValue(':usuario_edicion_id',$usuario);
      $stmt->bindValue(':id',$id);
      if ($stmt->execute()){
        echo "1";
      }else{
        echo "0";
      }
    }catch(PDOException $ex){
      echo $ex->getMessage();
    }
  }
}
?>
