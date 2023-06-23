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

$idDetalleNomina = $_POST['idDetalleNomina'];
$tipo = $_POST['tipo'];
$idEmpresa = $_SESSION['IDEmpresa'];

try {

    if($tipo == 1){
        $stmt = $conn->prepare("SELECT dnpe.id, rtp.clave, tp.codigo ,rcp.concepto_nomina as concepto, dnpe.tipo_concepto,dnpe.importe, dnpe.importe_exento, dnpe.exento, dnpe.horas, dnpe.dias 
                                FROM detalle_nomina_percepcion_empleado as dnpe 
                                INNER JOIN tipo_percepcion as tp ON tp.id = dnpe.relacion_tipo_percepcion_id 
                                INNER JOIN relacion_tipo_percepcion as rtp ON tp.id = rtp.tipo_percepcion_id AND rtp.empresa_id = :idEmpresa
                                INNER JOIN relacion_concepto_percepcion as rcp ON dnpe.relacion_concepto_percepcion_id = rcp.id AND rcp.empresa_id = :idEmpresa2
                                WHERE dnpe.id = :idDetalleNomina");
    }
    if($tipo == 2){
        $stmt = $conn->prepare("SELECT dnpd.id, rtd.clave, td.codigo ,rcd.concepto_nomina as concepto, dnpd.tipo_concepto,dnpd.importe, dnpd.importe_exento, dnpd.exento, dnpd.horas, dnpd.dias, CONCAT(ti.codigo,' - ',ti.concepto) as incapacidad, dnpd.relacion_tipo_deduccion_id
                                FROM detalle_nomina_deduccion_empleado as dnpd 
                                INNER JOIN tipo_deduccion as td ON td.id = dnpd.relacion_tipo_deduccion_id 
                                INNER JOIN relacion_tipo_deduccion as rtd ON td.id = rtd.tipo_deduccion_id AND rtd.empresa_id = :idEmpresa
                                INNER JOIN relacion_concepto_deduccion as rcd ON dnpd.relacion_concepto_deduccion_id = rcd.id AND rcd.empresa_id = :idEmpresa2
                                LEFT JOIN tipo_incapacidad as ti ON ti.id = dnpd.incapacidad
                                WHERE dnpd.id = :idDetalleNomina");
    }
    if($tipo == 3){
        $stmt = $conn->prepare("SELECT dopne.id, op.codigo , rcop.concepto_nomina as concepto, dopne.importe, rcop.tipo_otros_pagos_id
                                FROM detalle_otros_pagos_nomina_empleado as dopne 
                                INNER JOIN relacion_concepto_otros_pagos as rcop ON dopne.relacion_concepto_otros_pagos_id = rcop.id AND rtd.empresa_id = :idEmpresa
                                INNER JOIN otros_pagos as op ON op.id = rcop.tipo_otros_pagos_id
                                WHERE dopne.id = :idDetalleNomina");
    }

    $stmt->bindValue(":idEmpresa", $idEmpresa);
    $stmt->bindValue(":idEmpresa2", $idEmpresa);
    $stmt->bindValue(":idDetalleNomina", $idDetalleNomina);
    
    if($stmt->execute()){
        $json->estatus = "exito";
    }
    else{
        $json->estatus = "falso";
        $json = json_encode($json);
        echo $json;
        return;
    }
    $detalle = $stmt->fetch();

    $json->idDetalleNomina = $idDetalleNomina;
    
    $json->iddetallenomina = trim($detalle['id']);

    if($tipo == 1 || $tipo == 2){

        $json->concepto = trim($detalle['clave']).' - '.trim($detalle['codigo']).' - '.trim($detalle['concepto']);
        $json->tipo_concepto = $detalle['tipo_concepto'];
        $json->importe = number_format($detalle['importe'],2);
        $json->importe_exento = number_format($detalle['importe_exento'],2);
        $json->importe_total = number_format($detalle['importe'] + $detalle['importe_exento'],2);
        $json->exento = $detalle['exento'];
        $json->horas = $detalle['horas'];
        $json->dias = $detalle['dias'];

        if($detalle['tipo_concepto'] == 5){

            $stmt = $conn->prepare("SELECT fecha_inicio, fecha_fin FROM faltas_registro WHERE detalle_nomina_deduccion_empleado_id = :idDetalleNomina");
            $stmt->bindValue(":idDetalleNomina", $idDetalleNomina);
            $stmt->execute();
            $fechas = $stmt->fetch();

            $json->fecha_inicio_faltas = $fechas['fecha_inicio'];
            $json->fecha_fin_faltas = $fechas['fecha_fin'];
        }

    }
    else{

        $json->concepto = trim($detalle['codigo']).' - '.trim($detalle['concepto']);
        $json->tipo_concepto = 100;
        $json->importe = number_format($detalle['importe'],2);
        $json->importe_exento = 0;
        $json->importe_total = number_format($detalle['importe'],2);
        $json->exento = 0;
        $json->horas = 0;
        $json->dias = 0;

    }

    

    if($tipo == 2){
        $json->incapacidad = $detalle['incapacidad'];
        $json->relacion_tipo_deduccion_id = $detalle['relacion_tipo_deduccion_id'];
    }

    if($tipo != 3){
        //datos necesarios para la prima vacacional
        if($detalle['tipo_concepto'] == 10){

            $stmt = $conn->prepare("SELECT FechaIni, FechaFin, Prima_Vacacional FROM vacaciones WHERE detalle_nomina_percepcion_empleado_id = :idDetalleNomina");
            $stmt->bindValue(":idDetalleNomina", $idDetalleNomina);
            $stmt->execute();
            $vacaciones = $stmt->fetch();

            $json->fechaIniPV = $vacaciones['FechaIni'];
            $json->fechaFinPV = $vacaciones['FechaFin'];
            $json->importePrimaVac = $vacaciones['Prima_Vacacional'];

        }
    }

    $json = json_encode($json);
    echo $json;
    
} catch (PDOException $ex) {
    $json->estatus = "falso";
    $json = json_encode($json);
    echo $json;
}

?>
