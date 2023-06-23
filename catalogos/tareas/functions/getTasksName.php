<?php
  require_once('../../../include/db-conn.php');
  $id = $_POST['id'];
  $stmt = $conn->prepare('SELECT PKTarea,Tarea FROM tareas WHERE PKTarea = :id');
  $stmt->execute(array(':id'=>$id));
  $row = $stmt->fetch();
  $json = '{"id":"'.$row['PKTarea'].'","tarea":"'.$row['Tarea'].'"}';
  echo json_encode($json);

?>
