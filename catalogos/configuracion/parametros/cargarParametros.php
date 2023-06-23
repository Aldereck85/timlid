<?php
session_start();

$jwt_ruta = "../../../";
require_once '../../jwt.php';

date_default_timezone_set('America/Mexico_City');

$respuesta = new stdClass();

if(empty($_SESSION['token_ld10d'])) {
    $respuesta->resultado = "fallo1";
    $respuesta = json_encode($respuesta);
    echo $respuesta;
    return;           
}

require_once '../../../include/db-conn.php';

$idEmpresa = $_SESSION['IDEmpresa'];

//Dias de vencimiento
$stmt = $conn->prepare('SELECT cantidad FROM parametros WHERE descripcion = "Dias_Vencimiento" AND empresa_id = '.$idEmpresa );
$stmt->execute();
$rowV = $stmt->fetch();
$respuesta->diasVencimiento = $rowV['cantidad'];

//Leyenda
$stmt = $conn->prepare('SELECT texto FROM parametros_texto WHERE descripcion = "leyenda_cotizacion" AND empresa_id = '.$idEmpresa );
$stmt->execute();
$rowL = $stmt->fetch();
$respuesta->Leyenda = $rowL['texto'];

//Dias de aguinaldo
$stmt = $conn->prepare('SELECT cantidad FROM parametros_nomina WHERE (descripcion = "Dias_Aguinaldo"  AND empresa_id = '.$idEmpresa.' ) OR (descripcion = "Prima_Vacacional" AND empresa_id = '.$idEmpresa.' ) OR (descripcion = "Riesgo_Trabajo" AND empresa_id = '.$idEmpresa.' )' );
$stmt->execute();
$rowN = $stmt->fetchAll();
$respuesta->diasAguinaldo = $rowN[0]['cantidad'];
$respuesta->primaVacacional = $rowN[1]['cantidad'];
$respuesta->riesgotrabajo = $rowN[2]['cantidad'];

$respuesta->resultado = "exito";
$respuesta = json_encode($respuesta);
echo $respuesta;