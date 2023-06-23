<?php
session_start();
$respuesta = new stdClass();
$token = $_POST['csr_token_UT5JP'];

if(empty($_SESSION['token_ld10d'])) {
    $respuesta->estatus = "fallo";
    $respuesta = json_encode($respuesta);
    echo $respuesta;
    return;          
}

if (!hash_equals($_SESSION['token_ld10d'], $token)) {
    $respuesta->estatus = "fallo";
    $respuesta = json_encode($respuesta);
    echo $respuesta;
    return;
}

require_once '../../../include/db-conn.php';
$GLOBALS['rutaFuncion'] = "./../";
require_once("../../../functions/funcionNomina.php");

$idEmpleado = $_POST['idEmpleado'];
$fechaIngreso = $_POST['fechaIngreso'];
$fechaSalida = $_POST['fechaSalida'];
$datetime1 = new DateTime($fechaIngreso); // Fecha inicial
$datetime2 = new DateTime($fechaSalida); // Fecha actual
$interval = $datetime1->diff($datetime2);
$num_dias_antiguedad = $interval->format('%a');

//echo "fecha ".$fechaIngreso." -- ".$fechaSalida." ///// ";
$fechaIngresoComoEntero = strtotime($fechaIngreso);
$fechaFinalComoEntero = strtotime($fechaSalida);

//OBTENCION DE DATOS NECESARIOS PARA EL CALCULO
$datosEmpleado = getSalario_Dias($idEmpleado);
$idSucursal = $datosEmpleado[6];
$diasPeriodoIMSS = bcdiv($datosEmpleado[1], '1', 0); //periodo de pago del trabajador
$base_imss_general = $datosEmpleado[0]; //salario del empleado en base al periodo

$stmt = $conn->prepare('SELECT cantidad FROM parametros WHERE descripcion = "Factor_mes" OR descripcion = "UMA" OR descripcion = "Salario_Minimo_Nacional" OR descripcion = "Salario_Minimo_Norte" ORDER BY PKParametros Asc');
$stmt->execute();
$row_parametros = $stmt->fetchAll();
$UMA = $row_parametros[0]['cantidad'];
$factor_mes = $row_parametros[1]['cantidad'];
$salario_minimo_nacional = $row_parametros[2]['cantidad'];
$salario_minimo_norte = $row_parametros[3]['cantidad'];

$stmt = $conn->prepare('SELECT cantidad FROM parametros_nomina WHERE descripcion = "Dias_Aguinaldo" AND empresa_id = '.$_SESSION['IDEmpresa']);
$stmt->execute();
$row_aguinaldo = $stmt->fetch();
$dias_aguinaldo = $row_aguinaldo['cantidad'];

$stmt = $conn->prepare('SELECT cantidad FROM parametros_nomina WHERE descripcion = "Prima_Vacacional"  AND empresa_id = '.$_SESSION['IDEmpresa']);
$stmt->execute();
$row_prima_vacacional = $stmt->fetch();
$prima_vacacional_tasa = $row_prima_vacacional['cantidad'] / 100;

$stmt = $conn->prepare('SELECT zona_salario_minimo FROM sucursales WHERE id = '.$idSucursal.'  AND empresa_id = '.$_SESSION['IDEmpresa']);
$stmt->execute();
$row_zona = $stmt->fetch();
$zona_salario = $row_zona['zona_salario_minimo'];

if($zona_salario == 1){
  $salario_minimo = $salario_minimo_nacional;
}
else{
  $salario_minimo = $salario_minimo_norte;
}

$salario_diario = round($datosEmpleado[0]/$datosEmpleado[1],2);
$ultimo_sueldo_mensual_ord = $datosEmpleado[5];

//llamada a funcion para calcular vacaciones
$dias_vacaciones_totales = calcularTotalDiasVacaciones($idEmpleado, $fechaIngreso, $fechaSalida);
/*CALCULO DIAS AGUINALDO*/
if(date("Y",$fechaIngresoComoEntero) == date("Y",$fechaFinalComoEntero)  ){
  $fechainicial = $fechaIngreso;
}
else{
  $fechainicial = date("Y",$fechaFinalComoEntero).'-01-01';
}
$dias_calculo_aguinaldo = calcularTotalDiasVacaciones($idEmpleado, $fechainicial, $fechaSalida);
//echo "fecha ini ".$fechainicial." -- ///// ";

$factor_dias = $dias_calculo_aguinaldo[1];

//echo "factor ".$factor_dias."<br>";
if(esBisiesto(date("Y",$fechaFinalComoEntero))){
  $dias_aguinaldo_calculo = number_format(($dias_aguinaldo / 366) * $factor_dias,2,'.',''); 
}
else{
  $dias_aguinaldo_calculo = number_format(($dias_aguinaldo / 365) * $factor_dias,2,'.','');
}
$respuesta->dias_aguinaldo = $dias_aguinaldo;
$respuesta->dias_aguinaldo_proporcionales = number_format($dias_aguinaldo_calculo,2,'.','');
$respuesta->calculo_aguinaldo = number_format($dias_aguinaldo_calculo * $salario_diario,2,'.','');
$respuesta->ultimo_sueldo_mensual_ord = $ultimo_sueldo_mensual_ord;

/*FIN CALCULO DIAS AGUINALDO*/


/*CALCULO DIAS DE VACACIONES*/
$respuesta->num_anios = $dias_vacaciones_totales[6]; //antiguedad


//VACACIONES
$respuesta->dias_vacaciones = $dias_vacaciones_totales[2]; 
$respuesta->dias_vacaciones_calculo = number_format($dias_vacaciones_totales[3],2,'.',''); 
$respuesta->dias_vacaciones_restantes = $dias_vacaciones_totales[4]; 
$respuesta->dias_vacaciones_a_pagar = number_format($dias_vacaciones_totales[5],2,'.',''); 

$respuesta = json_encode($respuesta);
echo $respuesta;