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

require_once("../../../functions/funcionNomina.php");
$SDI = getSDI($idEmpleado);
$json->SDI = $SDI;

$stmt = $conn->prepare("SELECT * FROM finiquito WHERE empleado_id = :idEmpleado ORDER BY fecha_alta DESC limit 1");
$stmt->bindValue(":idEmpleado", $idEmpleado);
if($stmt->execute()){

    $finiquito = $stmt->fetch();  
    //0 no existe finiquito
    //1 finiquito
    //2 liquidacion
    //3 error
    if(count($finiquito < 1)){
        $json->estatus_finiquito = "0";
    }
    else{
        //validar la liquidacion
        $json->estatus_finiquito = "1";
        $json->fecha_salida = $finiquito['fecha_salida'];
        $json->dias_aguinaldo = $finiquito['dias_aguinaldo'];
        $json->dias_aguinaldo_proporcionales = $finiquito['dias_aguinaldo_proporcionales'];
        $json->antiguedad = $finiquito['antiguedad'];
        $json->dias_vacaciones = $finiquito['dias_vacaciones'];
        $json->dias_vacaciones_calculo = $finiquito['dias_vacaciones_calculo'];
        $json->dias_vacaciones_restantes = $finiquito['dias_vacaciones_restantes'];
        $json->dias_vacaciones_a_pagar = $finiquito['dias_vacaciones_a_pagar'];
        $json->salarios_devengados = $finiquito['salarios_devengados'];
        $json->fecha_inicial_salarios_devengados = $finiquito['fecha_inicial_salarios_devengados'];
        $json->fecha_final_salarios_devengados = $finiquito['fecha_final_salarios_devengados'];

    }
    
}
else{
    $json->estatus_finiquito = "3";
    $json = json_encode($json);
    echo $json;
    return;
}


$json = json_encode($json);
echo $json;