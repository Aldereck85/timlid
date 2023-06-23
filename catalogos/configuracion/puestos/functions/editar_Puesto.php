<?php
  session_start();
  
  require_once('../../../../include/db-conn.php');
  date_default_timezone_set('America/Mexico_City');
    $id = (int) $_REQUEST['idPuestoU'];
    $puesto = $_REQUEST['txtPuestoU'];
    $emp_id = $_REQUEST['empresa_id'];
    $usuario = $_REQUEST['usuario'];
    $now = date("Y-m-d H:i:s");
    
    try{

      $stmt = $conn->prepare('UPDATE puestos set puesto= :puesto,updated_at = :updated_at,usuario_creacion_id = :user_updated WHERE id = :id AND empresa_id = :emp_id');
      $stmt->bindValue(':puesto',$puesto);
      $stmt->bindValue(':emp_id',$emp_id);
      $stmt->bindValue(':updated_at',$now);
      $stmt->bindValue(':user_updated',$usuario);
      $stmt->bindValue(':id', $id, PDO::PARAM_INT);

      if($stmt->execute()){
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
?>
