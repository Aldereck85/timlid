<?php
  require_once('../../../include/db-conn.php');
  $id = $_POST['id'];
  $start = $_POST['date'];

  $stmt = $conn->prepare('SELECT * FROM cronograma_tarea WHERE FKTarea = :id');
  $stmt->execute(array(':id'=>$id));
  $countCrono = $stmt->rowCount();

  $stmt = $conn->prepare('SELECT * FROM fecha_tarea WHERE FKTarea = :id');
  $stmt->execute(array(':id'=>$id));
  $countDate = $stmt->rowCount();

  if(isset($_POST['date2'])){
    $final = $_POST['date2'];

    if($countDate > 0){
      $stmt = $conn->prepare('DELETE FROM fecha_tarea WHERE FKTarea = :id ');
      $stmt->execute(array(':id'=>$id));

      $stmt = $conn->prepare('INSERT INTO cronograma_tarea (FechaInicio,FechaTermino,FKTarea) VALUES (:start,:final,:id_tarea)');
      echo $stmt->execute(array(':start'=>$start,':final'=>$final,':id_tarea'=>$id));
    }else {
      $stmt = $conn->prepare('UPDATE cronograma_tarea SET FechaInicio= :start, FechaTermino= :final WHERE FKTarea= :id');
      echo $stmt->execute(array(':start'=>$start,':final'=>$final,':id'=>$id));
    }

  }else{
    if($countCrono > 0){
      $stmt = $conn->prepare('DELETE FROM cronograma_tarea WHERE FKTarea = :id ');
      $stmt->execute(array(':id'=>$id));

      $stmt = $conn->prepare('INSERT INTO fecha_tarea (Fecha,FKTarea) VALUES (:start,:id_tarea)');
      echo $stmt->execute(array(':start'=>$start,':id_tarea'=>$id));
    }else{
      $stmt = $conn->prepare('UPDATE fecha_tarea SET Fecha= :start WHERE FKTarea= :id');
      echo $stmt->execute(array(':start'=>$start,':id'=>$id));
    }
  }

  /*$stmt = $conn->prepare('UPDATE fecha_tarea SET Fecha= :fecha WHERE FKTarea= :id');
  echo $stmt->execute(array(':fecha'=>$text,':id'=>$id));*/

  //echo $text;

?>
