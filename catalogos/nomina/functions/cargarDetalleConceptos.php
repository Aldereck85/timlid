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

$tipo = $_POST['tipo'];
$modo = $_POST['modo'];

try {

    if($tipo == 1){
        if($modo == 1){
            $stmt = $conn->prepare("SELECT rcp.id, rcp.concepto_nomina, tp.codigo, rtp.clave , rbp.id as existe_base 
                                    FROM relacion_concepto_percepcion as rcp 
                                        INNER JOIN tipo_percepcion as tp ON tp.id = rcp.tipo_percepcion_id 
                                        INNER JOIN relacion_tipo_percepcion as rtp ON rtp.tipo_percepcion_id = tp.id AND rtp.empresa_id = ".$_SESSION['IDEmpresa']." 
                                        LEFT JOIN relacion_base_percepcion as rbp ON rbp.relacion_concepto_percepcion_id = rcp.id AND rbp.empresa_id = ".$_SESSION['IDEmpresa']."
                                        WHERE rcp.empresa_id =".$_SESSION['IDEmpresa']);
        }
        else{
            $stmt = $conn->prepare("SELECT rcp.id, rcp.concepto_nomina, tp.codigo, rtp.clave , rsp.sucursal_id 
                                    FROM relacion_concepto_percepcion as rcp 
                                        INNER JOIN tipo_percepcion as tp ON tp.id = rcp.tipo_percepcion_id 
                                        INNER JOIN relacion_tipo_percepcion as rtp ON rtp.tipo_percepcion_id = tp.id AND rtp.empresa_id = ".$_SESSION['IDEmpresa']." 
                                        LEFT JOIN relacion_sucursal_percepcion as rsp ON rsp.relacion_concepto_percepcion_id = rcp.id AND rsp.empresa_id = ".$_SESSION['IDEmpresa']."
                                        WHERE rcp.empresa_id =".$_SESSION['IDEmpresa']." GROUP BY rcp.id");    
        }
        
    }
    if($tipo == 2){

        if($modo == 1){
            $stmt = $conn->prepare("SELECT rcd.id, rcd.concepto_nomina, td.codigo, rtd.clave , rbd.id as existe_base 
                                    FROM relacion_concepto_deduccion as rcd 
                                        INNER JOIN tipo_deduccion as td ON td.id = rcd.tipo_deduccion_id 
                                        INNER JOIN relacion_tipo_deduccion as rtd ON rtd.tipo_deduccion_id = td.id AND rtd.empresa_id = ".$_SESSION['IDEmpresa']." 
                                        LEFT JOIN relacion_base_deduccion as rbd ON rbd.relacion_concepto_deduccion_id = rcd.id AND rbd.empresa_id = ".$_SESSION['IDEmpresa']."
                                        WHERE rcd.empresa_id =".$_SESSION['IDEmpresa']);
        }
        else{
            $stmt = $conn->prepare("SELECT rcd.id, rcd.concepto_nomina, td.codigo, rtd.clave , rsd.sucursal_id 
                                    FROM relacion_concepto_deduccion as rcd 
                                        INNER JOIN tipo_deduccion as td ON td.id = rcd.tipo_deduccion_id 
                                        INNER JOIN relacion_tipo_deduccion as rtd ON rtd.tipo_deduccion_id = td.id AND rtd.empresa_id = ".$_SESSION['IDEmpresa']." 
                                        LEFT JOIN relacion_sucursal_deduccion as rsd ON rsd.relacion_concepto_deduccion_id = rcd.id AND rsd.empresa_id = ".$_SESSION['IDEmpresa']."
                                        WHERE rcd.empresa_id =".$_SESSION['IDEmpresa']." GROUP BY rcd.id");    
        }

        /*$stmt = $conn->prepare("SELECT dnpd.id, rtd.clave, td.codigo ,rcd.concepto_nomina as concepto, dnpd.tipo_concepto,dnpd.importe, dnpd.importe_exento, dnpd.exento, dnpd.horas, dnpd.dias, CONCAT(ti.codigo,' - ',ti.concepto) as incapacidad, dnpd.relacion_tipo_deduccion_id
                                FROM detalle_nomina_deduccion_empleado as dnpd 
                                INNER JOIN tipo_deduccion as td ON td.id = dnpd.relacion_tipo_deduccion_id 
                                INNER JOIN relacion_tipo_deduccion as rtd ON td.id = rtd.tipo_deduccion_id
                                INNER JOIN relacion_concepto_deduccion as rcd ON dnpd.relacion_concepto_deduccion_id = rcd.id
                                LEFT JOIN tipo_incapacidad as ti ON ti.id = dnpd.incapacidad
                                WHERE dnpd.id = :idDetalleNomina");*/
    }
    if($tipo == 3){
        $stmt = $conn->prepare("SELECT dopne.id, op.codigo , rcop.concepto_nomina as concepto, dopne.importe, rcop.tipo_otros_pagos_id
                                FROM detalle_otros_pagos_nomina_empleado as dopne 
                                INNER JOIN relacion_concepto_otros_pagos as rcop ON dopne.relacion_concepto_otros_pagos_id = rcop.id
                                INNER JOIN otros_pagos as op ON op.id = rcop.tipo_otros_pagos_id
                                WHERE dopne.id = :idDetalleNomina");
    }
    
    if($stmt->execute()){
        $json->estatus = "exito";
    }
    else{
        $json->estatus = "falso";
        $json = json_encode($json);
        echo $json;
        return;
    }
    $detalles = $stmt->fetchAll();
    $options = "";

    foreach($detalles as $d){
        if($modo == 1){
            if($d['existe_base'] == NULL){
                $options .= "<option value='".$d['id']."'>".$d['codigo']." - ".$d['clave']." - ".$d['concepto_nomina']."</option>";
            }
        }
        if($modo == 2){
            if($d['sucursal_id'] == NULL){
                $options .= "<option value='".$d['id']."'>".$d['codigo']." - ".$d['clave']." - ".$d['concepto_nomina']."</option>";
            }
        }
        
    }

    if($tipo == 1 || $tipo == 2){

        $json->select = trim($options);

    }

    

    if($tipo == 3){
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
