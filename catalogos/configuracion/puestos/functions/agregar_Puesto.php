<?php
session_start();
  //if(isset($_SESSION["Usuario"]) && ($_SESSION["FKRol"] == 4 || $_SESSION["FKRol"] == 1)){
    require_once('../../../../include/db-conn.php');
    date_default_timezone_set('America/Mexico_City');
        $puesto = $_REQUEST['txtPuesto'];
        $emp_id = $_REQUEST['empresa_id'];
        $usuario = $_REQUEST['usuario'];
        
        $now = date("Y-m-d H:i:s");

        try{
          $stmt = $conn->prepare('INSERT INTO puestos (puesto,empresa_id,created_at,updated_at,usuario_creacion_id,usuario_edicion_id,estatus) VALUES (:puesto,:emp_id,:created_at,:updated_at,:user_created,:user_updated,1)');
          $stmt->bindValue(':puesto',$puesto);
          $stmt->bindValue(':emp_id',$emp_id);
          $stmt->bindValue(':created_at', $now);
          $stmt->bindValue(':updated_at', $now);
          $stmt->bindValue(':user_created',$usuario);
          $stmt->bindValue(':user_updated',$usuario);

          if ($stmt->execute()){
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
  //}else {
    //header("location:../../../dashboard.php");
  //}
 ?>

