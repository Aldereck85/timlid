<?php
require_once('../../../include/db-conn.php');
if(isset($_POST['id'])){
  $descripcion = $_POST['descripcion'];
  $repetible = $_POST['repetible'];
  $id = $_POST['id'];

  try{
    $stmt = $conn->prepare('SELECT * FROM texto_tarea WHERE FKTarea = :id');
    $stmt->execute(array(':id'=>$id));
    $rowCount = $stmt->rowCount();

    if($rowCount > 0){
      $stmt = $conn->prepare('UPDATE texto_tarea SET Texto= :texto WHERE FKTarea= :id');
      $stmt->execute(array(':texto'=>$descripcion,':id'=>$id));
    }else{
      $stmt = $conn->prepare('INSERT INTO texto_tarea (Texto,FKTarea) VALUES (:texto,:tarea)');
      $stmt->execute(array(':texto'=>$descripcion,':tarea'=>$id));
    }
    $stmt1 = $conn->prepare('SELECT * FROM tareas_repetibles WHERE FKTarea = :id');
    $stmt1->execute(array(':id'=>$id));
    $rowCount1 = $stmt1->rowCount();

    if($rowCount1 > 0){
      $stmt = $conn->prepare('UPDATE tareas_repetibles SET Repetible= :texto WHERE FKTarea= :id');
      echo $stmt->execute(array(':texto'=>$repetible,':id'=>$id));
    }else{
      $stmt = $conn->prepare('INSERT INTO tareas_repetibles (Repetible,FKTarea) VALUES (:repetible,:tarea)');
      echo $stmt->execute(array(':repetible'=>$repetible,':tarea'=>$id));
    }

  }catch(PDOException $ex){
    echo $ex->getMessage();
  }
}


?>
