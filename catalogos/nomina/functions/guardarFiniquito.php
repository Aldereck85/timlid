<?php
session_start();

$json = new stdClass();

$jwt_ruta = "../../../";
require_once '../../jwt.php';

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
//print_r($_POST);
$idEmpleado = $_POST['idEmpleado'];
$idEmpresa = $_SESSION['IDEmpresa'];
$fechaIngreso = $_POST['fechaIngreso'];
$fechaSalida = $_POST['fechaSalida'];
$motivo_separacion = $_POST['motivo_separacion'];

$diasAguinaldo = str_replace(",","",$_POST['diasAguinaldo']);
$diasAguinaldoProporcionales = str_replace(",","",$_POST['diasAguinaldoProporcionales']);
$antiguedad = str_replace(",","",$_POST['antiguedad']);
$num_anios_servicio = round($antiguedad);
$ultimo_sueldo_mensual_ord = $_POST['ultimo_sueldo_mensual_ord'];

$diasVacaciones = str_replace(",","",$_POST['diasVacaciones']);
$diasVacacionesProporcionales = str_replace(",","",$_POST['diasVacacionesProporcionales']);
if($_POST['diasVacacionesRestantes'] == ''){
    $diasVacacionesRestantes = 0;
}
else{
    $diasVacacionesRestantes = str_replace(",","",$_POST['diasVacacionesRestantes']);
}
$diasVacacionesPagar = str_replace(",","",$_POST['diasVacacionesPagar']);

$salariosDevengados = str_replace(",","",$_POST['salariosDevengados']);
if($salariosDevengados == ""){
    $salariosDevengados = 0.00;
}

$otros = str_replace(",","",$_POST['otros']);
if($otros == ""){
    $otros = 0.00;
}

$gratificacion = str_replace(",","",$_POST['gratificacion']);
if($gratificacion == ""){
    $gratificacion = 0.00;
}

$bonoAsistencia = str_replace(",","",$_POST['bonoAsistencia']);
if($bonoAsistencia == ""){
    $bonoAsistencia = 0.00;
}

$bonoPuntualidad = str_replace(",","",$_POST['bonoPuntualidad']);
if($bonoPuntualidad == ""){
    $bonoPuntualidad = 0.00;
}

if($_POST['fechaPeriodoIni'] == ""){
  $fechaPeriodoIni = "0000-00-00";
}
else{
  $fechaPeriodoIni = $_POST['fechaPeriodoIni'];
}

if($_POST['fechaPeriodoFin'] == ""){
  $fechaPeriodoFin = "0000-00-00";
}
else{
  $fechaPeriodoFin = $_POST['fechaPeriodoFin'];
}

$aguinaldoPercepcion = str_replace(",","",$_POST['aguinaldoPercepcion']);
$aguinaldoExento = str_replace(",","",$_POST['aguinaldoExento']);
$aguinaldoGravado = str_replace(",","",$_POST['aguinaldoGravado']);

$vacacionesPercepcion = str_replace(",","",$_POST['vacacionesPercepcion']);

$primaVacacionalPercepcion = str_replace(",","",$_POST['primaVacacionalPercepcion']);
$primaVacacionalExento = str_replace(",","",$_POST['primaVacacionalExento']);
$primaVacacionalGravado = str_replace(",","",$_POST['primaVacacionalGravado']);

$salarioDevengadoPercepcion = str_replace(",","",$_POST['salarioDevengadoPercepcion']);
$salarioDevengadoExento = str_replace(",","",$_POST['salarioDevengadoExento']);
$salarioDevengadoGravado = str_replace(",","",$_POST['salarioDevengadoGravado']);

$subtotalPercepcion = str_replace(",","",$_POST['subtotalPercepcion']);
$subtotalExento = str_replace(",","",$_POST['subtotalExento']);
$subtotalGravado = str_replace(",","",$_POST['subtotalGravado']);

$ISRVacacionesSalarios = str_replace(",","",$_POST['ISRVacacionesSalarios']);
$ISRAguinaldo = str_replace(",","",$_POST['ISRAguinaldo']);
$ISRPrimaVacacional = str_replace(",","",$_POST['ISRPrimaVacacional']);

$infonavit = str_replace(",","",$_POST['infonavit']);
$fonacot = str_replace(",","",$_POST['fonacot']);
$pensionAlimenticiaCantidad = str_replace(",","",$_POST['pensionAlimenticiaCantidad']);
$pensionAlimenticiaCheck = $_POST['pensionAlimenticiaCheck'];

if(trim($_POST['pensionAlimenticiaPorc']) == ""){
    $pensionAlimenticiaPorc = 0;
}
else{
    $pensionAlimenticiaPorc = $_POST['pensionAlimenticiaPorc'];
}

$imssSalarios = str_replace(",","",$_POST['imssSalarios']);

$tipoISRVacacionesSalarios = str_replace(",","",$_POST['tipoISRVacacionesSalarios']);
$tipoISRAguinaldo = str_replace(",","",$_POST['tipoISRAguinaldo']);
$tipoISRPrimaVacacional = str_replace(",","",$_POST['tipoISRPrimaVacacional']);

$ISRIndemnizacion = str_replace(",","",$_POST['ISRIndemnizacion']);
$tipoISRIndemnizacion = str_replace(",","",$_POST['tipoISRIndemnizacion']);

$TotalPagar = str_replace(",","",$_POST['TotalPagar']);

if($tipoISRVacacionesSalarios == 0){
    $ISRVacacionesSalariosFinal = $ISRVacacionesSalarios;
    $SAEVacacionesSalariosFinal = 0.00;
}
else{
    $ISRVacacionesSalariosFinal = 0.00;
    $SAEVacacionesSalariosFinal = $ISRVacacionesSalarios;
}

if($tipoISRAguinaldo == 0){
    $ISRAguinaldoFinal = $ISRAguinaldo;
    $SAEAguinaldoFinal = 0.00;
}
else{
    $ISRAguinaldoFinal = 0.00;
    $SAEAguinaldoFinal = $ISRAguinaldo;
}

if($tipoISRPrimaVacacional == 0){
    $ISRPrimaVacacionalFinal = $ISRPrimaVacacional;
    $SAEPrimaVacacionalFinal = 0.00;
}
else{
    $ISRPrimaVacacionalFinal = 0.00;
    $SAEPrimaVacacionalFinal = $ISRPrimaVacacional;
}

if($tipoISRIndemnizacion == 1){
    $ISRLiquidacionFinal = $ISRIndemnizacion;
    $SAELiquidacionFinal = 0.00;
}
else{
    $ISRLiquidacionFinal = 0.00;
    $SAELiquidacionFinal = $ISRIndemnizacion;
}

$tipoMovimiento = $_POST['tipoMovimiento'];

$IndeminizacionPercepcion = str_replace(",","",$_POST['IndeminizacionPercepcion']);  
$IndeminizacionExento = str_replace(",","",$_POST['IndeminizacionExento']);  
$IndeminizacionGravado = str_replace(",","",$_POST['IndeminizacionGravado']);  
$TotalLiquidacion = str_replace(",","",$_POST['TotalLiquidacion']);  

$SalarioAnioPercepcion = str_replace(",","",$_POST['SalarioAnioPercepcion']);  
$PrimaAntiguedadPercepcion = str_replace(",","",$_POST['PrimaAntiguedadPercepcion']);  

date_default_timezone_set('America/Mexico_City');
$fechaAlta = date("Y-m-d H:i:s"); 
$usuarioAlta = $_SESSION['PKUsuario'];

try{
    $conn->beginTransaction();

    $stmt = $conn->prepare('INSERT INTO finiquito ( fecha_ingreso, fecha_salida, motivo_separacion_id, dias_aguinaldo, dias_aguinaldo_proporcionales, antiguedad, aguinaldo, aguinaldo_exento, aguinaldo_gravado, vacaciones, dias_vacaciones, dias_vacaciones_proporcionales, dias_restantes, dias_pagar, prima_vacacional, prima_vacacional_exenta, prima_vacacional_gravada, salarios_devengados, fecha_inicial_salarios_devengados, fecha_final_salarios_devengados, otros, gratificacion, bonos_asistencia, bonos_puntualidad, infonavit, fonacot, tipo_pension_alimenticia, pension_alimenticia, pension_alimenticia_cantidad, imss_salarios,isr_vacaciones_salarios, sae_vacaciones_salarios, isr_aguinaldo, sae_aguinaldo, isr_prima_vacacional, sae_prima_vacacional, total_pagar, num_anios_servicio, ultimo_sueldo_mensual_ord, fecha_alta, empleado_id, usuario_alta_id, empresa_id) VALUES( :fecha_ingreso, :fecha_salida, :motivo_separacion_id, :dias_aguinaldo, :dias_aguinaldo_proporcionales, :antiguedad, :aguinaldo, :aguinaldo_exento, :aguinaldo_gravado, :vacaciones, :dias_vacaciones, :dias_vacaciones_proporcionales, :dias_restantes, :dias_pagar, :prima_vacacional, :prima_vacacional_exenta, :prima_vacacional_gravada, :salarios_devengados, :fecha_inicial_salarios_devengados, :fecha_final_salarios_devengados, :otros, :gratificacion, :bonos_asistencia, :bonos_puntualidad, :infonavit, :fonacot, :tipo_pension_alimenticia, :pension_alimenticia, :pension_alimenticia_cantidad, :imss_salarios,:isr_vacaciones_salarios, :sae_vacaciones_salarios, :isr_aguinaldo, :sae_aguinaldo, :isr_prima_vacacional, :sae_prima_vacacional, :total_pagar, :num_anios_servicio, :ultimo_sueldo_mensual_ord, :fecha_alta, :empleado_id, :usuario_alta_id, :empresa_id)');
    $stmt->bindValue(':fecha_ingreso', $fechaIngreso);
    $stmt->bindValue(':fecha_salida', $fechaSalida);
    $stmt->bindValue(':motivo_separacion_id', $motivo_separacion);
    $stmt->bindValue(':dias_aguinaldo', $diasAguinaldo);
    $stmt->bindValue(':dias_aguinaldo_proporcionales', $diasAguinaldoProporcionales);
    $stmt->bindValue(':antiguedad', $antiguedad);
    $stmt->bindValue(':aguinaldo', $aguinaldoPercepcion);
    $stmt->bindValue(':aguinaldo_exento', $aguinaldoExento);
    $stmt->bindValue(':aguinaldo_gravado', $aguinaldoGravado);
    $stmt->bindValue(':vacaciones', $vacacionesPercepcion);
    $stmt->bindValue(':dias_vacaciones', $diasVacaciones);
    $stmt->bindValue(':dias_vacaciones_proporcionales', $diasVacacionesProporcionales);
    $stmt->bindValue(':dias_restantes', $diasVacacionesRestantes);
    $stmt->bindValue(':dias_pagar', $diasVacacionesPagar);
    $stmt->bindValue(':prima_vacacional', $primaVacacionalPercepcion);
    $stmt->bindValue(':prima_vacacional_exenta', $primaVacacionalExento);
    $stmt->bindValue(':prima_vacacional_gravada', $primaVacacionalGravado);
    $stmt->bindValue(':salarios_devengados', $salariosDevengados);
    $stmt->bindValue(':fecha_inicial_salarios_devengados', $fechaPeriodoIni);
    $stmt->bindValue(':fecha_final_salarios_devengados', $fechaPeriodoFin);
    $stmt->bindValue(':otros', $otros);
    $stmt->bindValue(':gratificacion', $gratificacion);
    $stmt->bindValue(':bonos_asistencia', $bonoAsistencia);
    $stmt->bindValue(':bonos_puntualidad', $bonoPuntualidad);
    $stmt->bindValue(':infonavit', $infonavit);
    $stmt->bindValue(':fonacot', $fonacot);
    $stmt->bindValue(':tipo_pension_alimenticia', $pensionAlimenticiaCheck);
    $stmt->bindValue(':pension_alimenticia', $pensionAlimenticiaPorc);
    $stmt->bindValue(':pension_alimenticia_cantidad', $pensionAlimenticiaCantidad);
    $stmt->bindValue(':imss_salarios', $imssSalarios);
    $stmt->bindValue(':isr_vacaciones_salarios', $ISRVacacionesSalariosFinal);
    $stmt->bindValue(':sae_vacaciones_salarios', $SAEVacacionesSalariosFinal);
    $stmt->bindValue(':isr_aguinaldo', $ISRAguinaldoFinal);
    $stmt->bindValue(':sae_aguinaldo', $SAEAguinaldoFinal);
    $stmt->bindValue(':isr_prima_vacacional', $ISRPrimaVacacionalFinal);
    $stmt->bindValue(':sae_prima_vacacional', $SAEPrimaVacacionalFinal);
    $stmt->bindValue(':total_pagar', $TotalPagar);
    $stmt->bindValue(':num_anios_servicio', $num_anios_servicio);
    $stmt->bindValue(':ultimo_sueldo_mensual_ord', $ultimo_sueldo_mensual_ord);
    $stmt->bindValue(':fecha_alta', $fechaAlta);
    $stmt->bindValue(':empleado_id', $idEmpleado);
    $stmt->bindValue(':usuario_alta_id', $usuarioAlta);
    $stmt->bindValue(':empresa_id', $idEmpresa);
    $stmt->execute();

    $id = $conn->lastInsertId();
    $json->idfiniquito = $id;

    //se agrega a la tabla de liquidacion
    if($tipoMovimiento == 2){

        $stmt = $conn->prepare('INSERT INTO liquidacion ( indemnizacion, indemnizacion_exento, indemnizacion_gravado, anios_servicio, prima_antiguedad, isr_liquidacion, sae_liquidacion, finiquito_id, total_liquidacion) VALUES( :indemnizacion, :indemnizacion_exento, :indemnizacion_gravado, :anios_servicio, :prima_antiguedad, :isr_liquidacion, :sae_liquidacion,  :finiquito_id, :total_liquidacion)');
        $stmt->bindValue(':indemnizacion', $IndeminizacionPercepcion);
        $stmt->bindValue(':indemnizacion_exento', $IndeminizacionExento);
        $stmt->bindValue(':indemnizacion_gravado', $IndeminizacionGravado);
        $stmt->bindValue(':anios_servicio', $SalarioAnioPercepcion);
        $stmt->bindValue(':prima_antiguedad', $PrimaAntiguedadPercepcion);
        $stmt->bindValue(':isr_liquidacion', $ISRLiquidacionFinal);
        $stmt->bindValue(':sae_liquidacion', $SAELiquidacionFinal);
        $stmt->bindValue(':finiquito_id', $id);
        $stmt->bindValue(':total_liquidacion', $TotalLiquidacion);
        $stmt->execute();
    }

    if($conn->commit()){
        $json->estatus = "exito";   

    }
    else{
        $json->estatus = "fallo";
        $conn->rollBack(); 
    }

    $json = json_encode($json);
    echo $json;
} catch (PDOException $ex) {
    $conn->rollBack(); 
    echo "fallo";  //echo $ex->getMessage();
}

?>
