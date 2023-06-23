<?php
session_start();

$jwt_ruta = "../../../";
require_once '../../jwt.php';

require_once '../../../include/db-conn.php';

$stmt = $conn->prepare("SELECT PKEmpleado, Nombres, PrimerApellido, SegundoApellido, CURP, RFC FROM empleados WHERE is_generic = 0 AND estatus = 0 AND empresa_id = ".$_SESSION['IDEmpresa']);
$stmt->execute();
$vacaciones = $stmt->fetchAll();
$table = "";
foreach ($vacaciones as $v) {

    $editar = '<span class=\"fas fa-edit pointer\"  onclick=\"darAlta(' . $v['PKEmpleado'] . ');\"></span>';

    $table .= '{"id":"' . $v['PKEmpleado'] . '","nombre":"' . $v['Nombres'] . '","apellido1":"' . $v['PrimerApellido'] . '","apellido2":"' . $v['SegundoApellido'] . '","curp":"' . $v['CURP'] . '","rfc":"' . $v['RFC'] .'", "acciones":"' . $editar .'"},';

}
$table = substr($table, 0, strlen($table) - 1);
echo '{"data":[' . $table . ']}';
