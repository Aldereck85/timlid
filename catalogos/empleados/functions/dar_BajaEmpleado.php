<?php

session_start();

if (isset($_POST['idEmpleadoB'])) {
  require_once('../../../include/db-conn.php');
  $id = $_POST['idEmpleadoB'];
  try {

    date_default_timezone_set('America/Mexico_City');
    $stmt = $conn->prepare('UPDATE empleados SET FKEstatus = :estatus, FechaBaja = :baja WHERE PKEmpleado= :id');
    $stmt->bindValue(':estatus', 4);
    $stmt->bindValue(':baja', date("Y-m-d H:i:s"));
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    if (!$stmt->execute()) {
      throw new Exception('Algo salio mal');
    }
    return json_encode(['status' => 'success', 'message' => 'Empleado dado de baja correctamente.']);
  } catch (PDOException $ex) {
    return json_encode(['status' => 'fail', 'message' => 'Algo salio mal intentado de nuevo.']);
  }
}
