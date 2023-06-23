<?php
  require_once('../../../include/db-conn.php');
  $id = $_POST['id'];
  $text = $_POST['text'];

  $stmt = $conn->prepare('UPDATE subtareas SET SubTarea= :tarea WHERE PKSubTarea= :id');
  echo $stmt->execute(array(':tarea'=>$text,':id'=>$id));


?>
