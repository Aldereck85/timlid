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
$idNominaEmpleado = $_POST['idNominaEmpleado'];
$idEmpleado = $_POST['idEmpleado'];
$idNomina = $_POST['idNomina'];
$tipo_concepto = 5;// tipo 5 es para faltas
$fecha = date("Y-m-d H:i:s");
$idUsuario = $_SESSION['PKUsuario'];
$idEmpresa = $_SESSION['IDEmpresa'];
$agregarClaveFaltas = $_POST['agregarClaveFaltas'];
$claveFaltas = $_POST['claveFaltas'];
$cmbMotivoID = $_POST['cmbMotivoID'];
$agregarMotivoIncapacidad = $_POST['agregarMotivoIncapacidad'];
$claveIncapacidad = $_POST['claveIncapacidad'];
$fechaPago = $_POST['fechaPago'];
$existeConcepto = $_POST['existeconcepto'];
$nuevoConcepto = $_POST['nuevoconcepto'];
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

//clave
if($agregarClaveFaltas == 1){
    $stmt = $conn->prepare('SELECT id FROM relacion_tipo_percepcion WHERE clave = :clave AND  empresa_id = '.$idEmpresa);
    $stmt->bindValue(':clave', $claveFaltas);
    $stmt->execute();
    $existe1 = $stmt->rowCount();

    $stmt = $conn->prepare('SELECT id FROM relacion_tipo_deduccion WHERE clave = :clave AND  empresa_id = '.$idEmpresa);
    $stmt->bindValue(':clave', $claveFaltas);
    $stmt->execute();
    $existe2 = $stmt->rowCount();

    if($existe1 > 0 || $existe2 > 0){ 
        echo "existe-clave-faltas";
        return;
    }
    else{

        $stmt = $conn->prepare('SELECT id FROM relacion_tipo_deduccion WHERE tipo_deduccion_id = :concepto AND empresa_id = '.$idEmpresa);
        $stmt->bindValue(':concepto', $cmbMotivoID);
        $stmt->execute();
        $existe_concepto = $stmt->rowCount();
        
        if($existe_concepto > 0){ 
            echo "existe-concepto-faltas";
            return;
        }
        else{

            $stmt = $conn->prepare('INSERT INTO relacion_tipo_deduccion ( clave, tipo_deduccion_id, empresa_id) VALUES( :clave, :tipo_percepcion_id, :empresa_id)');
            $stmt->bindValue(':clave', $claveFaltas);
            $stmt->bindValue(':tipo_percepcion_id', $cmbMotivoID);
            $stmt->bindValue(':empresa_id', $idEmpresa);
            $stmt->execute();
        }
    }
}

if($existeConcepto == 1){
    $stmt = $conn->prepare('SELECT id FROM relacion_concepto_deduccion WHERE tipo_deduccion_id = :tipo_deduccion_id AND  empresa_id = '.$idEmpresa);
    $stmt->bindValue(':tipo_deduccion_id', $cmbMotivoID);
    $stmt->execute();
    $existe = $stmt->rowCount();

    if($existe > 0){
        $row_concepto = $stmt->fetch();
        $idConceptoDeduccion  = $row_concepto['id'];
    }
    else{
        $stmt = $conn->prepare('INSERT INTO relacion_concepto_deduccion (concepto_nomina, tipo_deduccion_id, empresa_id) VALUES (:concepto_nomina, :tipo_deduccion_id, :empresa_id)');
        $stmt->bindValue(':concepto_nomina', $nuevoConcepto);
        $stmt->bindValue(':tipo_deduccion_id', $cmbMotivoID);
        $stmt->bindValue(':empresa_id', $idEmpresa);
        $stmt->execute();

        $idConceptoDeduccion  = $conn->lastInsertId();
    }
}
else{
    $stmt = $conn->prepare('SELECT id FROM relacion_concepto_deduccion WHERE tipo_deduccion_id = :tipo_deduccion_id AND  empresa_id = '.$idEmpresa);
    $stmt->bindValue(':tipo_deduccion_id', $cmbMotivoID);
    $stmt->execute();
    $row_concepto = $stmt->fetch();
    $idConceptoDeduccion = $row_concepto['id'];
}

/*echo $cmbMotivoID."esto ".$idConceptoDeduccion;
return;*/
$stmt = $conn->prepare('SELECT dle.Sueldo, pp.DiasPago FROM datos_laborales_empleado as dle  INNER JOIN periodo_pago as pp ON pp.PKPeriodo_pago = dle.FKPeriodo WHERE FKEmpleado = :idEmpleado');
$stmt->bindValue(':idEmpleado', $idEmpleado);
$stmt->execute();
$datosEmpleado = $stmt->fetch();

$stmt = $conn->prepare('SELECT tipo_concepto, dias FROM detalle_nomina_deduccion_empleado WHERE tipo_concepto = 5 AND empleado_id = :idEmpleado2 AND nomina_empleado_id = :idNomina2');
$stmt->bindValue(':idEmpleado2', $idEmpleado);
$stmt->bindValue(':idNomina2', $idNominaEmpleado);
$stmt->execute();
$diasCon = $stmt->fetchAll();
///print_r($diasCon);

$stmt = $conn->prepare('SELECT t.Num_Dias_Trabajo from turnos as t INNER JOIN datos_laborales_empleado as dle ON dle.FKTurno = t.PKTurno WHERE t.empresa_id = :empresa AND dle.FKEmpleado = :idEmpleado');
$stmt->bindValue(":empresa", $_SESSION['IDEmpresa']);
$stmt->bindValue(":idEmpleado", $idEmpleado);
$stmt->execute();
$result = $stmt->fetch();
$diasTrabajo = $result['Num_Dias_Trabajo'];
//print_r($diasTrabajo);

if(count($diasCon) > 0){

    $diasSuma = 0;
    foreach ($diasCon as $dc) {
        
        $diasSuma = $diasSuma + $dc['dias'];

    }

    $diasSuma = $diasSuma + $diasFalta;
//echo " /// ".$diasSuma;

    if($datosEmpleado['DiasPago'] == 7 && $diasSuma > $diasTrabajo){
        echo "diaspaso";
        return;
    }
    if($datosEmpleado['DiasPago'] == 14 && $diasSuma > ($diasTrabajo * 2 )){
        echo "diaspaso";
        return;
    }
    if($datosEmpleado['DiasPago'] == 15 && $diasSuma > (($diasTrabajo * 2) + 1 )){
        echo "diaspaso";
        return;
    }
    if($datosEmpleado['DiasPago'] == 30 && $diasSuma > (($diasTrabajo * 4) + 2 )){
        echo "diaspaso";
        return;
    }

}
else{

//    echo "dias flata ".$diasFalta." -- ".$diasTrabajo;
    if($datosEmpleado['DiasPago'] == 7 && $diasFalta > $diasTrabajo){
        echo "diaspaso";
            return;
    }
    if($datosEmpleado['DiasPago'] == 14 && $diasFalta > ($diasTrabajo * 2) ){
        echo "diaspaso";
        return;
    }
    if($datosEmpleado['DiasPago'] == 15 && $diasFalta > (($diasTrabajo * 2) + 1 )){
        echo "diaspaso";
        return;
    }
    if($datosEmpleado['DiasPago'] == 30 && $diasFalta > (($diasTrabajo * 4) + 2 )){
        echo "diaspaso";
        return;
    }
}


try {
    $conn->beginTransaction();

    if($agregarMotivoIncapacidad == 0){
        $incapacidad = 0;
    }
    else{
        $incapacidad = $claveIncapacidad;
    }

    $stmt = $conn->prepare('INSERT INTO  detalle_nomina_deduccion_empleado (relacion_tipo_deduccion_id, relacion_concepto_deduccion_id, tipo_concepto, dias, incapacidad, importe, exento, empleado_id, nomina_empleado_id, fecha_alta, fecha_edicion, usuario_alta, usuario_edicion) VALUES (:concepto, :relacion_concepto_deduccion_id, :tipo_concepto, :dias, :incapacidad, :importe, :exento, :empleado_id, :nomina_empleado_id,:fecha_alta,:fecha_edicion,:usuario_alta,:usuario_edicion)');
    $stmt->bindValue(':concepto', $cmbMotivoID);
    $stmt->bindValue(':relacion_concepto_deduccion_id', $idConceptoDeduccion);
    $stmt->bindValue(':tipo_concepto', $tipo_concepto);
    $stmt->bindValue(':dias', $diasFalta);
    $stmt->bindValue(':incapacidad', $incapacidad);
    $stmt->bindValue(':importe', $ImporteDiasFalta);
    $stmt->bindValue(':exento', 0);
    $stmt->bindValue(':empleado_id', $idEmpleado);
    $stmt->bindValue(':nomina_empleado_id', $idNominaEmpleado);
    $stmt->bindValue(':fecha_alta', $fecha);
    $stmt->bindValue(':fecha_edicion', $fecha);
    $stmt->bindValue(':usuario_alta', $idUsuario);
    $stmt->bindValue(':usuario_edicion', $idUsuario);
    $stmt->execute();

    $idDetalleNomina = $conn->lastInsertId();

    $stmt = $conn->prepare('INSERT INTO  faltas_registro (fecha_inicio, fecha_fin, detalle_nomina_deduccion_empleado_id, nomina_empleado_id, usuario_alta, fecha_alta) VALUES (:fecha_inicio, :fecha_fin, :detalle_nomina_deduccion_empleado_id, :nomina_empleado_id, :usuario_alta, :fecha_alta)');
    $stmt->bindValue(':fecha_inicio', $fechaIni);
    $stmt->bindValue(':fecha_fin', $fechaFin);
    $stmt->bindValue(':detalle_nomina_deduccion_empleado_id', $idDetalleNomina);
    $stmt->bindValue(':nomina_empleado_id', $idNominaEmpleado);
    $stmt->bindValue(':usuario_alta', $idUsuario);
    $stmt->bindValue(':fecha_alta', $fecha);
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
