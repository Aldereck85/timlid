<?php
  require_once('../../../include/db-conn.php');
  if(isset($_POST['nombre_subtarea'])){
    $subtarea = $_POST['nombre_subtarea'];
    $idTarea = $_POST['id_tarea'];
    $responsable = $_POST['responsable_subtarea'];
    $lastID ="";
    try{
      $stmt = $conn->prepare('INSERT INTO subtareas (SubTarea,FKTarea,FKUsuario) VALUES (:subtarea,:tarea,:usuario)');
      $stmt->execute(array(':subtarea'=>$subtarea,':tarea'=>$idTarea,':usuario'=>$responsable));
      $lastID = $conn->lastInsertId();

      if($lastID != 0){
        $stmt = $conn->prepare('INSERT INTO responsable_subtarea (FKUsuario,FKSubTarea) VALUES (:usuario,:subtarea)');
        $stmt->bindValue(':usuario',$responsable,PDO::PARAM_INT);
        $stmt->bindValue(':subtarea',$lastID,PDO::PARAM_INT);
        echo $stmt->execute();
      }
    }catch(PDOException $ex){
      echo $ex->getMessage();
    }
  }

?>
