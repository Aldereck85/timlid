<?php
  session_start();
  date_default_timezone_set('America/Mexico_City');
  require_once('../../../include/db-conn.php');
  
  $table = $_POST['table'];
  $id = $_POST['id'];
  $auxHoy = getdate();
  $hoy = $auxHoy['year']."-".$auxHoy['mon']."-".$auxHoy['mday'];
  $hora = $auxHoy['hours'].":".$auxHoy['minutes'].":".($auxHoy['seconds']);
  $fechaHora = $hoy." ".$hora;

  switch($table){
    case 'chats':
      $stmt = $conn->prepare('UPDATE chat_notificaciones SET Visto = 1, FechaVisto = :fecha WHERE PKChat_Notificaciones = :id');
      echo $stmt->execute(array(':fecha'=>$fechaHora, ':id'=>$id));
    break;
    case 'tasks':
      $stmt = $conn->prepare('UPDATE tarea_notificaciones SET Visto = 1, FechaVisto = :fecha WHERE PKTareaNotificacion = :id');
      echo $stmt->execute(array(':fecha'=>$fechaHora, ':id'=>$id));
    break;
    case 'subtasks':
      $stmt = $conn->prepare('UPDATE subtarea_notificaciones SET Visto = 1, FechaVisto = :fecha WHERE PKSubTareaNotificacion = :id');
      echo $stmt->execute(array(':fecha'=>$fechaHora, ':id'=>$id));
    break;
    case 'checks':
    $stmt = $conn->prepare('UPDATE verificacion_notificaciones SET Visto = 1, FechaVisto = :fecha WHERE PKVerificacionNotificacion = :id');
    echo $stmt->execute(array(':fecha'=>$fechaHora, ':id'=>$id));
    break;
  }

?>
