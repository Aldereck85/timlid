<?php
session_start();
$json = new stdClass();
$token = $_POST['csr_token_UT5JP'];

if(empty($_SESSION['token_ld10d'])) {
    $json->estatus = "fallo";
    $json = json_encode($json);
    echo $json;
    return;          
}

if (!hash_equals($_SESSION['token_ld10d'], $token)) {
    $json->estatus = "fallo";
    $json = json_encode($json);
    echo $json;
    return;
}

require_once '../../../include/db-conn.php';

$idEmpleado = $_POST['idEmpleado'];

$stmt = $conn->prepare("SELECT id FROM finiquito WHERE empleado_id = :idEmpleado AND estadoTimbrado = 0");
$stmt->bindValue(":idEmpleado", $idEmpleado);
$stmt->execute();
$existe = $stmt->rowCount();

if($existe > 0){
    $json->estatus = "pendiente";
    $json = json_encode($json);
    echo $json;
    return;
}

$stmt = $conn->prepare("SELECT e.PKEmpleado, CONCAT(e.Nombres,' ', e.PrimerApellido,' ',e.SegundoApellido) as nombreEmpleado, dle.FechaIngreso, e.RFC, dme.NSS, p.puesto,t.Turno, dle.Sueldo,pp.DiasPago FROM empleados as e INNER JOIN datos_laborales_empleado as dle ON dle.FKEmpleado = e.PKEmpleado INNER JOIN periodo_pago as pp ON pp.PKPeriodo_pago = dle.FKPeriodo LEFT JOIN datos_medicos_empleado as dme ON dme.FKEmpleado = e.PKEmpleado LEFT JOIN puestos as p ON p.id = dle.FKPuesto LEFT JOIN turnos as t ON t.PKTurno = dle.FKTurno WHERE e.PKEmpleado = :idEmpleado AND e.empresa_id = ".$_SESSION['IDEmpresa']);
$stmt->bindValue(":idEmpleado", $idEmpleado);
    
if($stmt->execute()){
    $json->estatus = "exito";
}
else{
    $json->estatus = "falso";
    $json = json_encode($json);
    echo $json;
    return;
}
$empleado = $stmt->fetch();

$json->nombreEmpleado = $empleado['nombreEmpleado'];
$json->rfc = $empleado['RFC'];
$json->nss = $empleado['NSS'];
$json->Turno = $empleado['Turno'];
$json->puesto = $empleado['puesto'];
$json->fechaIngreso = $empleado['FechaIngreso'];
$json->sueldoPeriodo = $empleado['Sueldo'];
$json->sueldoDiario = bcdiv($empleado['Sueldo'] / $empleado['DiasPago'],1,2);

$GLOBALS['rutaFuncion'] = "./../";
require_once("../../../functions/funcionNomina.php");
$datosSBC = getSBCNomina($idEmpleado,'');
$SDI = $datosSBC[3];
$json->SDI = $SDI;

$json = json_encode($json);
echo $json;