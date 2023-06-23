<?php
  require_once('../../../include/db-conn.php');
  if(isset($_POST['id'])){
    $id = $_POST['id'];
    $taskName = $_POST['task_name'];

    $stmt = $conn->prepare('UPDATE tareas SET Tarea = :tarea WHERE PKTarea = :id');
    $stmt->execute(array(':tarea'=>$taskName,':id'=>$id));

  }

?>
