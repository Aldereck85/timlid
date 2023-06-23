<?php
session_start();

$jwt_ruta = "../../../";
require_once '../../jwt.php';

require_once '../../../include/db-conn.php';

$empleado_id = $_POST['idEmpleado'];
$stmt = $conn->prepare("SELECT id, anio, diasagregados, diasrestantes FROM vacaciones_agregadas WHERE empleado_id = :empleado_id");
$stmt->bindValue(":empleado_id", $empleado_id);
$stmt->execute();
$vacaciones = $stmt->fetchAll();
$table = "";
foreach ($vacaciones as $v) {

    $editar = '<span class=\"fas fa-clipboard-list pointer\"  onclick=\"modificarVacaciones(' . $v['id'] . ');\"></span>';

    $table .= '{"anios":"' . $v['anio'] . '","diasagregados":"' . $v['diasagregados'] . '","diasrestantes":"' . $v['diasrestantes'] . '", "acciones":"' . $editar .'"},';

}
$table = substr($table, 0, strlen($table) - 1);
echo '{"data":[' . $table . ']}';