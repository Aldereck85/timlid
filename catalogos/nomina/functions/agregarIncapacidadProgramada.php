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
$GLOBALS['rutaFuncion'] = "./../";
require_once("../../../functions/funcionNomina.php");

date_default_timezone_set('America/Mexico_City');


function getDiasDiferencia($fechaIni, $fechaFin){
    $fechaIniTiempo = strtotime($fechaIni); 
    $fechaFinTiempo = strtotime($fechaFin);
    $diferencia_dias_tiempo = $fechaIniTiempo - $fechaFinTiempo;
    $diferencia_dias_tiempo = ((round($diferencia_dias_tiempo / (60 * 60 * 24))) * -1) + 1;

    return $diferencia_dias_tiempo;
}

function getDiasTrabajoPorPeriodo($diasTrabajoNombre, $fechaIni, $fechaFin){

    /*print_r($diasTrabajoNombre);
    echo  "//-".$diasTrabajoNombre["sabado"]."-//<br>";*/
    
    $startDate = new DateTime($fechaIni);
    $endDate = new DateTime($fechaFin);

    //echo "start ".$startDate->format('w');
    //$totalDomingos

    $diasTrabajo = 0;
    while ($startDate <= $endDate) {

        //Domingo
        if ($startDate->format('w') == 0) {
            if($diasTrabajoNombre["domingo"] == 1){
                $diasTrabajo++;
            }
        }
        if ($startDate->format('w') == 1) {
            if($diasTrabajoNombre["lunes"] == 1){
                $diasTrabajo++;
            }
        }
        if ($startDate->format('w') == 2) {
            if($diasTrabajoNombre["martes"] == 1){
                $diasTrabajo++;
            }
        }
        if ($startDate->format('w') == 3) {
            if($diasTrabajoNombre["miercoles"] == 1){
                $diasTrabajo++;
            }
        }
        if ($startDate->format('w') == 4) {
            if($diasTrabajoNombre["jueves"] == 1){
                $diasTrabajo++;
            }
        }
        if ($startDate->format('w') == 5) {
            if($diasTrabajoNombre["viernes"] == 1){
                $diasTrabajo++;
            }
        }
        if ($startDate->format('w') == 6) {
            if($diasTrabajoNombre["sabado"] == 1){
                $diasTrabajo++;
            }
        }
        
        $startDate->modify('+1 day');
    }

    return $diasTrabajo;
}

$folioIncapacidad = $_POST['folioIncapacidad'];
$diasIncapacidad = $_POST['diasIncapacidad'];
$fechaIni = $_POST['fechaIni'];
$incapacidadID = $_POST['incapacidadID'];

if($_POST['PorcentajeFaltasIncapacidad'] == "" || $_POST['PorcentajeFaltasIncapacidad'] == 0){
    $PorcentajeFaltasIncapacidad = 0.00;
}
else{
    $PorcentajeFaltasIncapacidad = $_POST['PorcentajeFaltasIncapacidad'];
}

if($_POST['riesgoID'] == "" || $_POST['riesgoID'] == 0){
    $riesgoID = 0;
}
else{
    $riesgoID = $_POST['riesgoID'];
}

$observaciones = $_POST['observaciones'];


$agregarClaveIncapacidad = $_POST['agregarClaveIncapacidad'];
$claveIncapacidad = $_POST['claveIncapacidad'];

$existeConceptoIncapacidad = $_POST['existeConceptoIncapacidad'];
$conceptoIncapacidad = $_POST['conceptoIncapacidad'];


$idNominaEmpleado = $_POST['idNominaEmpleado'];
$idNomina = $_POST['idNomina'];
$fecha = date("Y-m-d H:i:s");
$idEmpleado = $_POST['idEmpleado'];
$idUsuario = $_SESSION['PKUsuario'];
$idEmpresa = $_SESSION['IDEmpresa'];
$fechaPago = $_POST['fechaPago'];

//clave
if($agregarClaveIncapacidad == 1){
    $stmt = $conn->prepare('SELECT id FROM relacion_tipo_percepcion WHERE clave = :clave AND  empresa_id = '.$idEmpresa);
    $stmt->bindValue(':clave', $claveIncapacidad);
    $stmt->execute();
    $existe1 = $stmt->rowCount();

    $stmt = $conn->prepare('SELECT id FROM relacion_tipo_deduccion WHERE clave = :clave AND  empresa_id = '.$idEmpresa);
    $stmt->bindValue(':clave', $claveIncapacidad);
    $stmt->execute();
    $existe2 = $stmt->rowCount();

    if($existe1 > 0 || $existe2 > 0){ 
        echo "existe-clave-incapacidad";
        return;
    }
    else{

        $stmt = $conn->prepare('SELECT id FROM relacion_tipo_deduccion WHERE tipo_deduccion_id = :concepto AND empresa_id = '.$idEmpresa);
        $stmt->bindValue(':concepto', 6);
        $stmt->execute();
        $existe_concepto = $stmt->rowCount();
        
        if($existe_concepto > 0){ 
            echo "existe-concepto-incapacidad";
            return;
        }
        else{

            $stmt = $conn->prepare('INSERT INTO relacion_tipo_deduccion ( clave, tipo_deduccion_id, empresa_id) VALUES( :clave, :tipo_percepcion_id, :empresa_id)');
            $stmt->bindValue(':clave', $claveIncapacidad);
            $stmt->bindValue(':tipo_percepcion_id', 6);
            $stmt->bindValue(':empresa_id', $idEmpresa);
            $stmt->execute();
        }
    }
}

if($existeConceptoIncapacidad == 1){
    $stmt = $conn->prepare('SELECT id FROM relacion_concepto_deduccion WHERE tipo_deduccion_id = :tipo_deduccion_id AND  empresa_id = '.$idEmpresa);
    $stmt->bindValue(':tipo_deduccion_id', 6);
    $stmt->execute();
    $existe = $stmt->rowCount();

    if($existe > 0){
        $row_concepto = $stmt->fetch();
        $idConceptoDeduccion  = $row_concepto['id'];
    }
    else{
        $stmt = $conn->prepare('INSERT INTO relacion_concepto_deduccion (concepto_nomina, tipo_deduccion_id, empresa_id) VALUES (:concepto_nomina, :tipo_deduccion_id, :empresa_id)');
        $stmt->bindValue(':concepto_nomina', $conceptoIncapacidad);
        $stmt->bindValue(':tipo_deduccion_id', 6);
        $stmt->bindValue(':empresa_id', $idEmpresa);
        $stmt->execute();

        $idConceptoDeduccion  = $conn->lastInsertId();
    }
}
else{
    $stmt = $conn->prepare('SELECT id FROM relacion_concepto_deduccion WHERE tipo_deduccion_id = :tipo_deduccion_id AND  empresa_id = '.$idEmpresa);
    $stmt->bindValue(':tipo_deduccion_id', 6);
    $stmt->execute();
    $row_concepto = $stmt->fetch();
    $idConceptoDeduccion = $row_concepto['id'];
}


//obtener fechas de inicio y fin de la nomina para ver si se aplicara
$aplicarIncapacidad = 0;

$stmt = $conn->prepare('SELECT fecha_inicio, fecha_fin FROM nomina WHERE id = :idNomina AND empresa_id = '.$idEmpresa);
$stmt->bindValue(':idNomina', $idNomina);
$stmt->execute();
$datosNomina = $stmt->fetch();
$fechaFinNomina = $datosNomina["fecha_fin"];

if((strtotime($fechaIni) >= strtotime($datosNomina["fecha_inicio"])  &&  strtotime($fechaIni) <= strtotime($datosNomina["fecha_fin"]))){
    $aplicarIncapacidad = 1;
}

try {
    $conn->beginTransaction();

    $stmt = $conn->prepare('INSERT INTO  incapacidades ( empleado_id, folio, dias_autorizados, fecha_inicio, motivo_incapacidad, porcentaje_incapacidad, riesgo_trabajo, observaciones, dias_restantes, empresa_id) VALUES (:empleado_id, :folio, :dias_autorizados, :fecha_inicio, :motivo_incapacidad, :porcentaje_incapacidad, :riesgo_trabajo, :observaciones, :dias_restantes, :empresa_id)');
    $stmt->bindValue(':empleado_id', $idEmpleado);
    $stmt->bindValue(':folio', $folioIncapacidad);
    $stmt->bindValue(':dias_autorizados', $diasIncapacidad);
    $stmt->bindValue(':fecha_inicio', $fechaIni);
    $stmt->bindValue(':motivo_incapacidad', $incapacidadID);
    $stmt->bindValue(':porcentaje_incapacidad', $PorcentajeFaltasIncapacidad);
    $stmt->bindValue(':riesgo_trabajo', $riesgoID);
    $stmt->bindValue(':observaciones', $observaciones);
    $stmt->bindValue(':dias_restantes', $diasIncapacidad);
    $stmt->bindValue(':empresa_id', $idEmpresa);
    $stmt->execute();

    $idIncapacidad = $conn->lastInsertId();

    if($aplicarIncapacidad == 1){

        $fecha_fin_incapacidad = date('Y-m-d', strtotime($fechaIni. ' + '.($diasIncapacidad - 1).' days'));
        //echo "fecha_fin_incapacidad ".$fecha_fin_incapacidad."<br>";

        if(strtotime($fecha_fin_incapacidad) > strtotime($fechaFinNomina)){
            $fechaFinPeriodo = $fechaFinNomina;
        }
        else{
            $fechaFinPeriodo = $fecha_fin_incapacidad;
        }

        $diferencia_dias_tiempo = getDiasDiferencia($fechaIni, $fechaFinPeriodo);
        //echo "diferencia_dias_tiempo ".$diferencia_dias_tiempo."<br>";

        $resultDias = getDiasTrabajo($idEmpleado);


        $stmt = $conn->prepare('SELECT t.Dias_de_trabajo from turnos as t INNER JOIN datos_laborales_empleado as dle ON dle.FKTurno = t.PKTurno WHERE t.empresa_id = :empresa AND dle.FKEmpleado = :idEmpleado');
        $stmt->bindValue(":empresa", $_SESSION['IDEmpresa']);
        $stmt->bindValue(":idEmpleado", $idEmpleado);
        $stmt->execute();
        $resultDias = $stmt->fetch();
        $diasTrabajoNombre = json_decode($resultDias['Dias_de_trabajo'], true);

        $diasTrabajo = getDiasTrabajoPorPeriodo($diasTrabajoNombre, $fechaIni, $fechaFinPeriodo);
        //echo "diasTrabajo ".$diasTrabajo."<br>";

        if($diasTrabajo < $diferencia_dias_tiempo){
            $diasAplicar = $diasTrabajo;
        }
        else{
            $diasAplicar = $diferencia_dias_tiempo;
        }

        if($diasIncapacidad > $diasAplicar){

            $fecha_nueva_ini = date('Y-m-d', strtotime($fechaFinPeriodo. ' + 1 days'));
            //echo "fecha_nueva_ini ".$fecha_nueva_ini."<br>";

            $fecha_nueva_fin = $fechaFinNomina;
            //echo "fecha_nueva_fin ".$fecha_nueva_fin."<br>";

            $diasTrabajo = getDiasTrabajoPorPeriodo($diasTrabajoNombre, $fecha_nueva_ini, $fecha_nueva_fin);
            //echo "diasTrabajo v2 ".$diasTrabajo."<br>";

            $diasRestantes = $diasIncapacidad - $diasAplicar;
            //echo "diasRestantes v2 ".$diasRestantes."<br>";

            if($diasRestantes >= $diasTrabajo){
                $sumarDias = $diasTrabajo;
            }
            else{
                $sumarDias = $diasRestantes;
            }
            //echo "sumarDias v2 ".$sumarDias."<br>";
            $diasAplicar = $diasAplicar + $sumarDias;
        }
        

        $ImporteDiasFalta = calcularSalarioFaltas($idEmpleado, $diasAplicar);
        /*echo "ImporteDiasFalta ".$ImporteDiasFalta."<br>";
        return;*/

        $tipo_concepto = 5;// tipo 5 es para faltas e incapacidades
        $stmt = $conn->prepare('INSERT INTO  detalle_nomina_deduccion_empleado (relacion_tipo_deduccion_id, relacion_concepto_deduccion_id, tipo_concepto, dias, incapacidad, importe, exento, empleado_id, nomina_empleado_id, fecha_alta, fecha_edicion, usuario_alta, usuario_edicion) VALUES (:concepto, :relacion_concepto_deduccion_id, :tipo_concepto, :dias, :incapacidad, :importe, :exento, :empleado_id, :nomina_empleado_id,:fecha_alta,:fecha_edicion,:usuario_alta,:usuario_edicion)');
        $stmt->bindValue(':concepto', 6);
        $stmt->bindValue(':relacion_concepto_deduccion_id', $idConceptoDeduccion);
        $stmt->bindValue(':tipo_concepto', $tipo_concepto);
        $stmt->bindValue(':dias', $diasAplicar);
        $stmt->bindValue(':incapacidad', $incapacidadID);
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

        $diasFaltantesAplicar = $diasIncapacidad - $diasAplicar;
        if($diasFaltantesAplicar > 0){
            $estado = 1;
            $aplicacion = 1;
        }
        else{
            $estado = 3;
            $aplicacion = 0;
        }

        $stmt = $conn->prepare('UPDATE incapacidades SET dias_restantes = :dias_restantes , en_aplicacion = :en_aplicacion, estado = :estado WHERE id = :id');
        $stmt->bindValue(':dias_restantes', $diasFaltantesAplicar);
        $stmt->bindValue(':en_aplicacion', 1);
        $stmt->bindValue(':estado', $estado);
        $stmt->bindValue(':id', $idIncapacidad);
        $stmt->execute();

        $stmt = $conn->prepare('INSERT INTO incapacidades_registro (fecha_inicio, fecha_fin, dias_agregados, detalle_nomina_deduccion_empleado_id, nomina_empleado_id, usuario_alta, fecha_alta) VALUES (:fecha_inicio, :fecha_fin, :dias_agregados, :detalle_nomina_deduccion_empleado_id, :nomina_empleado_id, :usuario_alta, :fecha_alta)');
        $stmt->bindValue(':fecha_inicio', $fechaIni);
        $stmt->bindValue(':fecha_fin', $fechaFinPeriodo);
        $stmt->bindValue(':dias_agregados', $diasAplicar);
        $stmt->bindValue(':detalle_nomina_deduccion_empleado_id', $idDetalleNomina);
        $stmt->bindValue(':nomina_empleado_id', $idNominaEmpleado);
        $stmt->bindValue(':usuario_alta', $idUsuario);
        $stmt->bindValue(':fecha_alta', $fecha);
        $stmt->execute();

    }


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
