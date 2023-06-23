<?php
session_start();

if (isset($_POST['id'])) {
    require_once '../../../../include/db-conn.php';
    $id = $_POST['id'];
    $stmt = $conn->prepare("SELECT r.id as fkresp, e.Nombres as nom
      FROM empleados as e
      INNER JOIN relacion_tipo_empleado as r  ON r.empleado_id = e.PKEmpleado WHERE id = :id");

    $stmt->execute(array(':id' => $id));
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $result['status'] = 'success';
    $res = json_encode($result);
    echo $res;
} else {
    echo json_encode(array('status' => 'fail'));
}
