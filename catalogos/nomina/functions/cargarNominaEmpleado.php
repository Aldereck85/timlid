<?php
session_start();

$jwt_ruta = "../../../";
require_once '../../jwt.php';

date_default_timezone_set('America/Mexico_City');

$token = $_POST['csr_token_UT5JP'];

if(empty($_SESSION['token_ld10d'])) {
    $json->estatus = "falso";
    $json = json_encode($json);
    echo $json;
    return;           
}

if (!hash_equals($_SESSION['token_ld10d'], $token)) {
    $json->estatus = "falso";
    $json = json_encode($json);
    echo $json;
    return;
}

require_once '../../../include/db-conn.php';
$json = new \stdClass();

$idNomina = $_POST['idNomina'];
$idEmpleado = $_POST['idEmpleado'];

try {

    $stmt = $conn->prepare("SELECT e.PKEmpleado, CONCAT(e.Nombres,' ', e.PrimerApellido,' ',e.SegundoApellido) as nombreEmpleado, DATE_FORMAT(dle.FechaIngreso,'%d/%m/%Y') as FechaIngreso, e.RFC, dme.NSS, p.puesto,t.Turno, ne.Exento, ne.PKNomina as idNominaEmpleado, ne.estadoTimbrado, ne.idFactura, dle.Sueldo,pp.DiasPago,  DATE_FORMAT(ne.fechaTimbrado,'%d/%m/%Y') as fechaTimbrado, e.email, n.autorizada
        FROM empleados as e 
        INNER JOIN datos_laborales_empleado as dle ON dle.FKEmpleado = e.PKEmpleado
        INNER JOIN periodo_pago as pp ON pp.PKPeriodo_pago = dle.FKPeriodo
        LEFT JOIN datos_medicos_empleado as dme ON dme.FKEmpleado = e.PKEmpleado
        LEFT JOIN puestos as p ON p.id = dle.FKPuesto
        LEFT JOIN turnos as t ON t.PKTurno = dle.FKTurno
        LEFT JOIN nomina_empleado as ne ON ne.FKEmpleado = e.PKEmpleado AND ne.FKNomina = :FKNomina
        LEFT JOIN nomina as n ON n.id = ne.FKNomina 
        WHERE e.PKEmpleado = :idEmpleado AND e.empresa_id = ".$_SESSION['IDEmpresa']);
    $stmt->bindValue(":FKNomina", $idNomina);
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
/*
    $diasPeriodoIMSS = bcdiv($empleado['DiasPago'], '1', 0);
    $base_imss_general = $empleado['Sueldo'];

    $salarioBaseDiario = number_format($base_imss_general / $diasPeriodoIMSS,2, '.', '');
    $factorSDI = bcdiv(1 + ($dias_aguinaldo + ($dias_vacaciones * $prima_vacacional_tasa)) / 365,1,4);
    $SBC = bcdiv($salarioBaseDiario * $factorSDI,1, 2);//Salario Base Cotizacion o Salario Diario Integrado, es igual
    $stmt = $conn->prepare('SELECT cantidad FROM parametros_nomina WHERE descripcion = "Dias_Aguinaldo" AND empresa_id = '.$_SESSION['IDEmpresa']);
    $stmt->execute();
    $row_aguinaldo = $stmt->fetch();
    $dias_aguinaldo = $row_aguinaldo['cantidad'];

    $stmt = $conn->prepare('SELECT cantidad FROM parametros_nomina WHERE descripcion = "Prima_Vacacional"  AND empresa_id = '.$_SESSION['IDEmpresa']);
    $stmt->execute();
    $row_prima_vacacional = $stmt->fetch();
    $prima_vacacional_tasa = $row_prima_vacacional['cantidad'] / 100;*/

    $json->nombreEmpleado = $empleado['nombreEmpleado'];
    $json->rfc = $empleado['RFC'];
    $json->nss = $empleado['NSS'];
    $json->Turno = $empleado['Turno'];
    $json->puesto = $empleado['puesto'];
    $json->FechaIngreso = $empleado['FechaIngreso'];
    $json->Exento = $empleado['Exento'];
    $json->idNominaEmpleado = $empleado['idNominaEmpleado'];  
    $json->estadoTimbrado = $empleado['estadoTimbrado'];
    $json->idFactura = $empleado['idFactura'];
    $json->fechaTimbrado = $empleado['fechaTimbrado'];
    $json->email = $empleado['email'];
    $json->autorizada = $empleado['autorizada'];

    $json = json_encode($json);
    echo $json;
    
} catch (PDOException $ex) {
    $json->estatus = "falso";
    $json = json_encode($json);
    echo $json;
}

?>
