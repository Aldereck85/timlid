<?php
  session_start();
  date_default_timezone_set('America/Mexico_City');
  if(isset($_SESSION["Usuario"])){
    require_once('../../../../include/db-conn.php');
        $id =  $_POST['idCategoriaU'];
        $nombreEstatus = $_POST['txtNombreU'];
        $usuario = $_POST['usuario'];
        
        $now = date("Y-m-d H:i:s");
        try{
          $stmt = $conn->prepare('UPDATE categoria_gastos set Nombre= :nombre, usuario_edicion_id =:usuario_edicion_id, updated_at = :updated_at WHERE PKCategoria = :id');
          $stmt->bindValue(':nombre',$nombreEstatus);
          $stmt->bindValue(':id', $id);
          $stmt->bindValue(':usuario_edicion_id',$usuario);
          $stmt->bindValue(':updated_at',$now);
          if($stmt->execute()){
            echo "1";
          }else{
            echo "0";
          }
        }catch(PDOException $ex){
          echo $ex->getMessage();
        }
  }
?>

