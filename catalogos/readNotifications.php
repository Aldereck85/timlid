<?php
include "../include/db-conn.php";

if (isset($_POST['notificationId'])) {
  try {
    $query = sprintf('UPDATE notificaciones SET visto = 1 WHERE id = :idNotificacion');
    $stmt = $conn->prepare($query);
    if ($stmt->execute(array(':idNotificacion' => $_POST['notificationId']))) {
      echo json_encode(['status' => 'success', 'message' => 'Todo bien']);
    }
  } catch (\Throwable $th) {
    echo json_encode(['status' => 'fail', 'message' => 'Algo salio mal.']);
  }
} else {
  echo json_encode(['status' => 'fail', 'message' => 'No se envio la data.']);
}
