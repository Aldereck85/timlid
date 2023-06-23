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
    $stmt = $conn->prepare("SELECT rcp.id, tp.codigo, rcp.concepto_nomina, rtp.clave  FROM tipo_percepcion as tp INNER JOIN relacion_concepto_percepcion as rcp ON tp.id = rcp.tipo_percepcion_id AND rcp.empresa_id = ".$_SESSION['IDEmpresa']." INNER JOIN relacion_tipo_percepcion as rtp ON rtp.tipo_percepcion_id = tp.id AND rtp.empresa_id = ".$_SESSION['IDEmpresa']." ORDER BY rcp.concepto_nomina Asc");
}
if($tipo == 2){
    $stmt = $conn->prepare("SELECT rcd.id, td.codigo, rcd.concepto_nomina, rtd.clave FROM tipo_deduccion as td INNER JOIN relacion_concepto_deduccion as rcd ON td.id = rcd.tipo_deduccion_id AND rcd.empresa_id = ".$_SESSION['IDEmpresa']." INNER JOIN relacion_tipo_deduccion as rtd ON rtd.tipo_deduccion_id = td.id AND rtd.empresa_id = ".$_SESSION['IDEmpresa']." WHERE td.id NOT IN (1,2,6,20) ORDER BY td.id Asc");
}
if($tipo == 3){
    $stmt = $conn->prepare("SELECT rcop.id, op.codigo, rcop.concepto_nomina FROM otros_pagos as op INNER JOIN relacion_concepto_otros_pagos as rcop ON rcop.tipo_otros_pagos_id = op.id AND rcop.empresa_id = ".$_SESSION['IDEmpresa']." ORDER BY concepto_nomina ASC ");
}



$stmt->execute();
$conceptos = $stmt->fetchAll();

$concepto = "<option disabled selected>Selecciona el concepto</option>";
foreach ($conceptos as $c) {

        if($tipo == 1 || $tipo == 2){
           $concepto .= "<option value='".$c['id']."' >".$c["clave"]." - ".$c["codigo"]." - ".$c["concepto_nomina"]."</option>";    
        }
        else{
           $concepto .= "<option value='".$c['id']."' >".$c["codigo"]." - ".$c["concepto_nomina"]."</option>";  
        }
                

}

echo $concepto;


?>
