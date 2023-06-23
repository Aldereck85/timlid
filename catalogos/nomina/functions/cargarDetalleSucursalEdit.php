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
$idConcepto = $_POST['idConcepto'];

try {

    if($tipo == 1){
        $stmt = $conn->prepare("SELECT rcp.id, rcp.concepto_nomina, tp.codigo, rtp.clave 
                                    FROM relacion_concepto_percepcion as rcp 
                                        INNER JOIN tipo_percepcion as tp ON tp.id = rcp.tipo_percepcion_id 
                                        LEFT JOIN relacion_tipo_percepcion as rtp ON rtp.tipo_percepcion_id = tp.id AND rtp.empresa_id = ".$_SESSION['IDEmpresa']." 
                                        WHERE rcp.empresa_id =".$_SESSION['IDEmpresa']." AND rcp.id = :idConcepto ");
        $stmt->bindValue(":idConcepto", $idConcepto);
    }
    if($tipo == 2){
        $stmt = $conn->prepare("SELECT rcd.id, rcd.concepto_nomina, td.codigo, rtd.clave 
                                    FROM relacion_concepto_deduccion as rcd 
                                        INNER JOIN tipo_deduccion as td ON td.id = rcd.tipo_deduccion_id 
                                        LEFT JOIN relacion_tipo_deduccion as rtd ON rtd.tipo_deduccion_id = td.id AND rtd.empresa_id = ".$_SESSION['IDEmpresa']." 
                                        WHERE rcd.empresa_id =".$_SESSION['IDEmpresa']." AND rcd.id = :idConcepto ");
        $stmt->bindValue(":idConcepto", $idConcepto);
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
    $detalles = $stmt->fetch();


           
    $json->concepto = $detalles['codigo']." - ".$detalles['clave']." - ".$detalles['concepto_nomina'];
    $json->conceptoID = $detalles['id'];

    if($tipo == 1){
        $stmt = $conn->prepare("SELECT rsp.id, rsp.tipo_base, rsp.cantidad, rsp.sucursal_id, s.sucursal FROM relacion_sucursal_percepcion as rsp INNER JOIN sucursales as s ON s.id = rsp.sucursal_id INNER JOIN relacion_concepto_percepcion as rcp ON rcp.id = rsp.relacion_concepto_percepcion_id WHERE rsp.relacion_concepto_percepcion_id = :idConcepto ");
        $stmt->bindValue(":idConcepto", $idConcepto);
        $stmt->execute();
        $sucursales_row = $stmt->fetchAll();
        $sucursales = "";

        foreach($sucursales_row as $s){

            if($s['tipo_base'] == 1){
                $tipo_base = "%";
            }
            else{
                $tipo_base = "$";
            }
            $sucursales .=
                '<div class="row" id="sucursal_'.$s['sucursal_id'].'">' .
                                        '<div class="col-lg-9">' .$s['sucursal']. ' - '.$tipo_base.' - ' .$s['cantidad']. '</div>' .
                                        '<div class="col-lg-3 text-right"><i class="fas fa-trash-alt pointer" onclick="eliminarSucursalEdit(' .$s['id'].','.$s['sucursal_id']. ');"></i></div>' .
                                        '<input type="hidden" id="idBaseSucursal" value="' .$s['sucursal_id']. '" />' .
                                        '<input type="hidden" id="cantidadIngresar" value="' .$s['cantidad']. '" />' .
                                        '<input type="hidden" id="idTipoBase" value="' .$s['tipo_base']. '" />' .
                                    '</div>';
        }

        $json->sucursales = $sucursales;

    }

    if($tipo == 2){

        $stmt = $conn->prepare("SELECT rsd.id, rsd.tipo_base, rsd.cantidad, rsd.sucursal_id, s.sucursal FROM relacion_sucursal_deduccion as rsd INNER JOIN sucursales as s ON s.id = rsd.sucursal_id INNER JOIN relacion_concepto_deduccion as rcd ON rcd.id = rsd.relacion_concepto_deduccion_id WHERE rsd.relacion_concepto_deduccion_id = :idConcepto ");
        $stmt->bindValue(":idConcepto", $idConcepto);
        $stmt->execute();
        $sucursales_row = $stmt->fetchAll();
        $sucursales = "";

        foreach($sucursales_row as $s){

            if($s['tipo_base'] == 1){
                $tipo_base = "%";
            }
            else{
                $tipo_base = "$";
            }
            $sucursales .=
                '<div class="row" id="sucursal_'.$s['sucursal_id'].'">' .
                                        '<div class="col-lg-9">' .$s['sucursal']. ' - '.$tipo_base.' - ' .$s['cantidad']. '</div>' .
                                        '<div class="col-lg-3 text-right"><i class="fas fa-trash-alt pointer" onclick="eliminarSucursalEdit(' .$s['id'].','.$s['sucursal_id']. ');"></i></div>' .
                                        '<input type="hidden" id="idBaseSucursal" value="' .$s['sucursal_id']. '" />' .
                                        '<input type="hidden" id="cantidadIngresar" value="' .$s['cantidad']. '" />' .
                                        '<input type="hidden" id="idTipoBase" value="' .$s['tipo_base']. '" />' .
                                    '</div>';
        }

        $json->sucursales = $sucursales;

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
