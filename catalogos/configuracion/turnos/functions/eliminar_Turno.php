<?php
  require_once('../../../../include/db-conn.php');
  date_default_timezone_set('America/Mexico_City');
  
  if(isset($_POST['idTurnoD'])){
    try{
      $id = $_POST['idTurnoD'];
      $usuario = $_REQUEST['usuario'];
      
      $now = date("Y-m-d H:i:s");

      //$stmt = $conn->prepare("DELETE FROM turnos WHERE PKTurno=?");
      $stmt = $conn->prepare("UPDATE turnos SET usuario_edicion_id= :usuario, estatus=0, updated_at = :updated_at WHERE PKTurno = :id");
      $stmt->bindValue(":usuario",$usuario);
      $stmt->bindValue(":id",$id);
      $stmt->bindValue(":updated_at",$now);
      if($stmt->execute()){
        echo "exito";
      }else{
        echo "fallo";
      }
    }catch(PDOException $ex){
      echo $ex->getMessage();
    }
  }
?>
