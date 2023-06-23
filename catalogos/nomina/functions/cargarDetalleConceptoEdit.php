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
        $stmt = $conn->prepare("SELECT rcp.id, tp.codigo, rtp.clave, rcp.concepto_nomina, tp.concepto as conceptoSAT FROM relacion_concepto_percepcion as rcp LEFT JOIN relacion_tipo_percepcion as rtp ON rtp.tipo_percepcion_id = rcp.tipo_percepcion_id AND rtp.empresa_id = ".$_SESSION['IDEmpresa']." LEFT JOIN tipo_percepcion as tp ON tp.id = rtp.tipo_percepcion_id WHERE rcp.empresa_id =".$_SESSION['IDEmpresa']." AND rcp.id = :idConcepto ");

    }
    if($tipo == 2){

        $stmt = $conn->prepare("SELECT rcd.id, td.codigo, rtd.clave, rcd.concepto_nomina, td.concepto as conceptoSAT FROM relacion_concepto_deduccion as rcd LEFT JOIN relacion_tipo_deduccion as rtd ON rtd.tipo_deduccion_id = rcd.tipo_deduccion_id AND rtd.empresa_id = ".$_SESSION['IDEmpresa']." LEFT JOIN tipo_deduccion as td ON td.id = rtd.tipo_deduccion_id WHERE rcd.empresa_id =".$_SESSION['IDEmpresa']." AND rcd.id = :idConcepto ");
        
    }
    if($tipo == 3){
        $stmt = $conn->prepare("SELECT dopne.id, op.codigo , rcop.concepto_nomina as concepto, dopne.importe, rcop.tipo_otros_pagos_id
                                FROM detalle_otros_pagos_nomina_empleado as dopne 
                                INNER JOIN relacion_concepto_otros_pagos as rcop ON dopne.relacion_concepto_otros_pagos_id = rcop.id
                                INNER JOIN otros_pagos as op ON op.id = rcop.tipo_otros_pagos_id
                                WHERE dopne.id = :idDetalleNomina");
    }
    
    $stmt->bindValue(":idConcepto", $idConcepto);

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
   
    $json->concepto =$detalles['concepto_nomina'];
    $json->conceptoID = $detalles['id'];
    $json->clave = $detalles['clave'];
    $json->conceptoSAT = $detalles['codigo']." - ".$detalles['clave']." - ".$detalles['conceptoSAT'];

    $json = json_encode($json);
    echo $json;
    
} catch (PDOException $ex) {
    $json->estatus = "falso";
    $json = json_encode($json);
    echo $json;
}

?>
