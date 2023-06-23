<?php
session_start();

$jwt_ruta = "../../../";
require_once '../../jwt.php';

$token = $_POST['csr_token_UT5JP'];

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

$diasFalta = $_POST['diasFalta'];
$ImporteDiasFalta = $_POST['ImporteDiasFalta'];
$idDetalleNominafaltas = $_POST['idDetalleNominafaltas'];
$idNominaEmpleado = $_POST['idNominaEmpleado'];
$idEmpleado = $_POST['idEmpleado'];
$idNomina = $_POST['idNomina'];
$fechaPago = $_POST['fechaPago'];
$fecha = date("Y-m-d H:i:s");
$idUsuario = $_SESSION['PKUsuario'];
$idEmpresa = $_SESSION['IDEmpresa'];
$fechaIni = $_POST['fechaIni'];
$fechaFin = $_POST['fechaFin'];


$stmt = $conn->prepare('SELECT fecha_inicio, fecha_fin FROM nomina WHERE id = :idNomina AND empresa_id = '.$idEmpresa);
$stmt->bindValue(':idNomina', $idNomina);
$stmt->execute();
$datosNomina = $stmt->fetch();

if(!(strtotime($fechaIni) >= strtotime($datosNomina["fecha_inicio"])  &&  strtotime($fechaIni) <= strtotime($datosNomina["fecha_fin"]))){
    echo "fallo-fechaini";
    return;
}

if(!(strtotime($fechaFin) >= strtotime($datosNomina["fecha_inicio"])  &&  strtotime($fechaFin) <= strtotime($datosNomina["fecha_fin"]))){
    echo "fallo-fechafin";
    return;
}

$stmt = $conn->prepare('SELECT t.Dias_de_trabajo from turnos as t INNER JOIN datos_laborales_empleado as dle ON dle.FKTurno = t.PKTurno WHERE t.empresa_id = :empresa AND dle.FKEmpleado = :idEmpleado');
$stmt->bindValue(":empresa", $_SESSION['IDEmpresa']);
$stmt->bindValue(":idEmpleado", $idEmpleado);
$stmt->execute();
$resultDias = $stmt->fetch();
$diasTrabajoNombre = json_decode($resultDias['Dias_de_trabajo'], true);

/*print_r($diasTrabajoNombre);
echo  "//-".$diasTrabajoNombre["sabado"]."-//<br>";*/

$startDate = new DateTime($fechaIni);
$endDate = new DateTime($fechaFin);

//echo "start ".$startDate->format('w');
//$totalDomingos

$diasFaltasCuenta = 0;
while ($startDate <= $endDate) {

    //Domingo
    if ($startDate->format('w') == 0) {
        if($diasTrabajoNombre["domingo"] == 1){
            $diasFaltasCuenta++;
        }
    }
    if ($startDate->format('w') == 1) {
        if($diasTrabajoNombre["lunes"] == 1){
            $diasFaltasCuenta++;
        }
    }
    if ($startDate->format('w') == 2) {
        if($diasTrabajoNombre["martes"] == 1){
            $diasFaltasCuenta++;
        }
    }
    if ($startDate->format('w') == 3) {
        if($diasTrabajoNombre["miercoles"] == 1){
            $diasFaltasCuenta++;
        }
    }
    if ($startDate->format('w') == 4) {
        if($diasTrabajoNombre["jueves"] == 1){
            $diasFaltasCuenta++;
        }
    }
    if ($startDate->format('w') == 5) {
        if($diasTrabajoNombre["viernes"] == 1){
            $diasFaltasCuenta++;
        }
    }
    if ($startDate->format('w') == 6) {
        if($diasTrabajoNombre["sabado"] == 1){
            $diasFaltasCuenta++;
        }
    }
    
    $startDate->modify('+1 day');
}

if($diasFaltasCuenta != $diasFalta){
    echo "fallo-fechacuenta";
    return;
}


$stmt = $conn->prepare('SELECT dle.Sueldo, pp.DiasPago FROM datos_laborales_empleado as dle  INNER JOIN periodo_pago as pp ON pp.PKPeriodo_pago = dle.FKPeriodo WHERE FKEmpleado = :idEmpleado');
$stmt->bindValue(':idEmpleado', $idEmpleado);
$stmt->execute();
$datosEmpleado = $stmt->fetch();

$stmt = $conn->prepare('SELECT tipo_concepto, dias FROM detalle_nomina_deduccion_empleado WHERE tipo_concepto = 5 AND empleado_id = :idEmpleado2 AND nomina_empleado_id = :idNomina2 AND id <> :idDetalleNomina');
$stmt->bindValue(':idEmpleado2', $idEmpleado);
$stmt->bindValue(':idNomina2', $idNominaEmpleado);
$stmt->bindValue(':idDetalleNomina', $idDetalleNominafaltas);
$stmt->execute();
$diasCon = $stmt->fetchAll();

$stmt = $conn->prepare('SELECT t.Num_Dias_Trabajo from turnos as t INNER JOIN datos_laborales_empleado as dle ON dle.FKTurno = t.PKTurno WHERE t.empresa_id = :empresa AND dle.FKEmpleado = :idEmpleado');
$stmt->bindValue(":empresa", $_SESSION['IDEmpresa']);
$stmt->bindValue(":idEmpleado", $idEmpleado);
$stmt->execute();
$result = $stmt->fetch();
$diasTrabajo = $result['Num_Dias_Trabajo'];

if(count($diasCon) > 0){

    $diasSuma = 0;
    foreach ($diasCon as $dc) {
        
        $diasSuma = $diasSuma + $dc['dias'];

    }

    $diasSuma = $diasSuma + $diasFalta;

    if($datosEmpleado['DiasPago'] == 7 && $diasSuma > $diasTrabajo){
        echo "diaspaso";
        return;
    }
    if($datosEmpleado['DiasPago'] == 14 && $diasSuma < ($diasTrabajo * 2 )){
        echo "diaspaso";
        return;
    }
    if($datosEmpleado['DiasPago'] == 15 && $diasSuma < (($diasTrabajo * 2) + 1 )){
        echo "diaspaso";
        return;
    }
    if($datosEmpleado['DiasPago'] == 30 && $diasSuma < (($diasTrabajo * 4) + 2 )){
        echo "diaspaso";
        return;
    }

}
else{
    if($datosEmpleado['DiasPago'] == 7 && $diasTrabajo < $diasFalta){
            echo "diaspaso";
            return;
    }
    if($datosEmpleado['DiasPago'] == 14 && ($diasTrabajo * 2) < $diasFalta){
        echo "diaspaso";
        return;
    }
    if($datosEmpleado['DiasPago'] == 15 && (($diasTrabajo * 2) + 1 ) < $diasFalta){
        echo "diaspaso";
        return;
    }
    if($datosEmpleado['DiasPago'] == 30 && (($diasTrabajo * 4) + 2 ) < $diasFalta){
        echo "diaspaso";
        return;
    }
}


try {
    $conn->beginTransaction();

    $stmt = $conn->prepare('UPDATE detalle_nomina_deduccion_empleado SET dias = :dias, importe = :importe, fecha_edicion = :fecha_edicion, usuario_edicion = :usuario_edicion WHERE id = :idDetalleNominafaltas');
    $stmt->bindValue(':dias', $diasFalta);
    $stmt->bindValue(':importe', $ImporteDiasFalta);
    $stmt->bindValue(':fecha_edicion', $fecha);
    $stmt->bindValue(':usuario_edicion', $idUsuario); 
    $stmt->bindValue(':idDetalleNominafaltas', $idDetalleNominafaltas); 
    $stmt->execute();

    $stmt = $conn->prepare('UPDATE faltas_registro SET fecha_inicio = :fecha_inicio, fecha_fin = :fecha_fin WHERE detalle_nomina_deduccion_empleado_id = :detalle_nomina_deduccion_empleado_id AND nomina_empleado_id = :nomina_empleado_id');
    $stmt->bindValue(':fecha_inicio', $fechaIni);
    $stmt->bindValue(':fecha_fin', $fechaFin);
    $stmt->bindValue(':detalle_nomina_deduccion_empleado_id', $idDetalleNominafaltas);
    $stmt->bindValue(':nomina_empleado_id', $idNominaEmpleado);
    $stmt->execute();
 
    if($conn->commit()){

      $modo = 2;//para agregar o restar cantidades adicionales al total de percepciones 
      require_once("calculoImpuestos.php");

      echo "exito";
    }else{
      echo "fallo";
    }
    
    
} catch (PDOException $ex) {
    $conn->rollBack();
    echo "fallo"; //$ex->getMessage();
}

?>
