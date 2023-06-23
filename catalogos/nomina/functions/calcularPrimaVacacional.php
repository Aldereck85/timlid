<?php
session_start();

$jwt_ruta = "../../../";
require_once '../../jwt.php';

$token = $_POST['csr_token_UT5JP'];

$respuesta = new stdClass();

if(empty($_SESSION['token_ld10d'])) {
    echo "fallo";
    return;           
}

if (!hash_equals($_SESSION['token_ld10d'], $token)) {
    echo "fallo";
    return;
}

require_once '../../../include/db-conn.php';

date_default_timezone_set('America/Mexico_City');

$dias = $_POST['dias'];
$idEmpleado = $_POST['idEmpleado'];

$stmt = $conn->prepare('SELECT dle.Sueldo, pp.DiasPago FROM datos_laborales_empleado as dle  INNER JOIN periodo_pago as pp ON pp.PKPeriodo_pago = dle.FKPeriodo WHERE FKEmpleado = :idEmpleado');
$stmt->bindValue(':idEmpleado', $idEmpleado);
$stmt->execute();
$datosEmpleado = $stmt->fetch();

$salarioDiario = round($datosEmpleado['Sueldo']/$datosEmpleado['DiasPago'],2);
$totalVacaciones = bcdiv($salarioDiario * $dias,1,2);
$respuesta->totalVacaciones = number_format($totalVacaciones,2,'.','');

$stmt = $conn->prepare('SELECT cantidad FROM parametros_nomina WHERE descripcion = "Prima_Vacacional"  AND empresa_id = :empresa_id');
$stmt->bindValue(':empresa_id', $_SESSION['IDEmpresa']);
$stmt->execute();
$primaVacacionalPorcentaje = $stmt->fetch();

$respuesta->primaVacacional = number_format($totalVacaciones * ($primaVacacionalPorcentaje['cantidad'] / 100),2);


$stmt = $conn->prepare("SELECT SUM(diasrestantes) as diasrestantes FROM vacaciones_agregadas WHERE empleado_id = :empleado_id ");
$stmt->bindValue(":empleado_id", $idEmpleado);
$stmt->execute();
$dias_vac = $stmt->fetch();

$respuesta->diasVacaciones = $dias_vac['diasrestantes'];

$respuesta = json_encode($respuesta);
echo $respuesta;

?>
