<?php
  require_once('../../../include/db-conn.php');
  if(isset($_POST['nombre_tarea'])){
    $tarea = $_POST['nombre_tarea'];
    $responsable = $_POST['responsable'];
    $start = $_POST['date'];
    //$final =  $_POST['date2'];
    $prioridad = $_POST['prioridad'];
    $lastID = "";

    try{
      $stmt = $conn->prepare('INSERT INTO tareas (Tarea,FKProyecto,Terminada,FKEtapa) VALUES (:tarea,:proyecto,:terminada,:etapa)');
      $stmt->execute(array(':tarea'=>$tarea,':proyecto'=>1,':terminada'=>0,':etapa'=>1));
      $lastID = $conn->lastInsertId();

      if($lastID != ""){
        $stmt = $conn->prepare('INSERT INTO responsables_tarea (FKUsuario,FKTarea) VALUES (:usuario,:id_tarea)');
        $stmt->bindValue(':usuario',$responsable,PDO::PARAM_INT);
        $stmt->bindValue(':id_tarea',$lastID,PDO::PARAM_INT);
        $stmt->execute();

        if(!isset($_POST['date2'])){
          $stmt = $conn->prepare('INSERT INTO fecha_tarea (Fecha,FKTarea) VALUES (:start,:id_tarea)');
          $stmt->execute(array(':start'=>$start,':id_tarea'=>$lastID));
        }else {
          $final =  $_POST['date2'];
          $stmt = $conn->prepare('INSERT INTO cronograma_tarea (FechaInicio,FechaTermino,FKTarea) VALUES (:start,:final,:id_tarea)');
          $stmt->execute(array(':start'=>$start,':final'=>$final,':id_tarea'=>$lastID));
        }

        $stmt = $conn->prepare('INSERT INTO prioridad_tareas (Prioridad,FKTarea) VALUES (:priority,:tarea)');
        $stmt->execute(array(':priority'=>$prioridad,':tarea'=>$lastID));
      }
      echo 1;
    }catch(PDOException $ex){
      echo $ex->getMessage();
    }
  }



?>
