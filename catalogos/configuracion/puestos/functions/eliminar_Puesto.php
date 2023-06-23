<?php
  require_once('../../../../include/db-conn.php');
  
  if(isset($_REQUEST['idPuestoD']) AND isset($_REQUEST['empresa_id'])){
    date_default_timezone_set('America/Mexico_City');
    $id = $_REQUEST['idPuestoD'];
    $emp_id = $_REQUEST['empresa_id'];
    $usuario = $_REQUEST['usuario'];
    $now = date("Y-m-d H:i:s");

    try{
      

      //$stmt = $conn->prepare("DELETE FROM puestos WHERE id = :id AND empresa_id = :emp_id");
      $query = sprintf("UPDATE puestos SET estatus = 0, usuario_edicion_id = :user_updated, updated_at = :updated_at WHERE id = :id AND empresa_id = :emp_id");

      $stmt = $conn->prepare($query);
      $stmt->bindValue(':user_updated',$usuario);
      $stmt->bindValue(":id",$id);
      $stmt->bindValue(":emp_id",$emp_id);
      $stmt->bindValue(":updated_at",$now);

      if($stmt->execute()){
        echo "exito";
      }else{
        echo "fallo";
      }
      //header('Location:../index.php?p='.$puesto1.'&a=elim');
    }catch(PDOException $ex){
      echo $ex->getMessage();
    }
    $con = null;
    $db = null;
    $stmt = null;
  }
?>
