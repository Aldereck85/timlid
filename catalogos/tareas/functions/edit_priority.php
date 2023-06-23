<?php
  require_once('../../../include/db-conn.php');
  $id = $_POST['id'];
  $text = $_POST['text'];

  $stmt = $conn->prepare('SELECT * FROM prioridad_tareas WHERE FKTarea = :id');
  $stmt->execute(array(':id'=>$id));
  $rowCount = $stmt->rowCount();

  if($rowCount > 0){
    $stmt = $conn->prepare('UPDATE prioridad_tareas SET Prioridad= :priority WHERE FKTarea= :id');
    echo $stmt->execute(array(':priority'=>$text,':id'=>$id));
  }else{
    $stmt = $conn->prepare('INSERT INTO prioridad_tareas (Prioridad,FKTarea) VALUES (:prioridad,:id)');
    echo $stmt->execute(array(':prioridad'=>$text,':id'=>$id));
  }

?>
