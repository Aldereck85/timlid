<?php
  require_once('../../../include/db-conn.php');
  $id = $_POST['id'];
  $text = $_POST['text'];

  $stmt = $conn->prepare('SELECT * FROM responsables_tarea WHERE FKTarea = :id');
  $stmt->execute(array(':id'=>$id));
  $rowCount = $stmt->rowCount();

  if($rowCount > 0){
    $stmt = $conn->prepare('UPDATE responsables_tarea SET FKUsuario= :usuario WHERE FKTarea= :id');
    echo $stmt->execute(array(':usuario'=>$text,':id'=>$id));
  }else{
    $stmt = $conn->prepare('INSERT INTO responsables_tarea (FKUsuario,FKTarea) VALUES (:usuario,:id)');
    echo $stmt->execute(array(':usuario'=>$text,':id'=>$id));
  }

?>
