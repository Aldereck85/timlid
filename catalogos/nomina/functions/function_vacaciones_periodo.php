<?php
session_start();

$jwt_ruta = "../../../";
require_once '../../jwt.php';

require_once '../../../include/db-conn.php';

$empleado_id = $_POST['idEmpleado'];
$stmt = $conn->prepare("SELECT PKVacaciones as id, Dias_de_Vacaciones_Tomados, DATE_FORMAT(FechaIni, '%d/%m/%Y') as FechaIni, DATE_FORMAT(FechaFin, '%d/%m/%Y') as FechaFin, Prima_Vacacional, Total_Vacaciones FROM vacaciones WHERE FKEmpleado = :empleado_id");
$stmt->bindValue(":empleado_id", $empleado_id);
$stmt->execute();
$vacaciones = $stmt->fetchAll();
$table = "";
foreach ($vacaciones as $v) {

    $editar = '<span class=\"fas fa-clipboard-list pointer\"  onclick=\"descargarRecibo(' . $v['id'] . ');\"></span>';

    $table .= '{"dias":"' . $v['Dias_de_Vacaciones_Tomados'] . '","fechaini":"' . $v['FechaIni'] . '","fechafin":"' . $v['FechaFin'] . '","primavacacional":"' . number_format($v['Prima_Vacacional'],2) . '","totalvacaciones":"' . number_format($v['Total_Vacaciones'],2) . '", "acciones":"' . $editar .'"},';

}
$table = substr($table, 0, strlen($table) - 1);
echo '{"data":[' . $table . ']}';