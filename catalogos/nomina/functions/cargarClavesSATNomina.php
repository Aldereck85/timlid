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

$tipo = $_POST['tipo'];
require_once '../../../include/db-conn.php';

if($tipo == 1){
    $stmt = $conn->prepare("SELECT tp.id, tp.codigo, tp.concepto, rtp.clave  FROM tipo_percepcion as tp LEFT JOIN relacion_tipo_percepcion as rtp ON tp.id = rtp.tipo_percepcion_id AND rtp.empresa_id = ".$_SESSION['IDEmpresa'].' WHERE tp.id NOT IN (2) ORDER BY tp.id Asc');
}
if($tipo == 2){
    $stmt = $conn->prepare("SELECT td.id, td.codigo, td.concepto, rtd.clave FROM tipo_deduccion as td LEFT JOIN relacion_tipo_deduccion as rtd ON td.id = rtd.tipo_deduccion_id AND rtd.empresa_id = ".$_SESSION['IDEmpresa'].' ORDER BY td.id Asc');
}
if($tipo == 3){
    $stmt = $conn->prepare("SELECT id, codigo, descripcion FROM otros_pagos");
}



$stmt->execute();
$conceptos = $stmt->fetchAll();

$concepto = "<option disabled selected>Selecciona el concepto</option>";
foreach ($conceptos as $c) {

    if($tipo == 1 || $tipo == 2){
        if(trim($c["clave"]) != ""){
            $concepto .= "<option value='".$c['id']."' >".$c["clave"].' - '.$c["codigo"].' - '.$c["concepto"]."</option>";    
        }
        else{
            $concepto .= "<option value='".$c['id']."' >".$c["codigo"].' - '.$c["concepto"]."</option>";  
        }
    }
    else{
        $concepto .= "<option value='".$c['id']."' >".$c["codigo"].' - '.$c["descripcion"]."</option>";  
    }
         

}

echo $concepto;


?>
