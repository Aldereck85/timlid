<?php
session_start();
$respuesta = new stdClass();
$token = $_POST['csr_token_UT5JP'];

if(empty($_SESSION['token_ld10d'])) {
    $respuesta->estatus = "fallo";
    $respuesta = json_encode($respuesta);
    echo $respuesta;
    return;          
}

if (!hash_equals($_SESSION['token_ld10d'], $token)) {
    $respuesta->estatus = "fallo";
    $respuesta = json_encode($respuesta);
    echo $respuesta;
    return;
}

require_once '../../../include/db-conn.php';
$GLOBALS['rutaFuncion'] = "./../";
require_once("../../../functions/funcionNomina.php");

$idEmpleado = $_POST['idEmpleado'];
$fechaPago = $_POST['fechaPago'];
$SBCFijo = $_POST['SBCFijo'];
$SBCVariables = $_POST['SBCVariables'];
$idNomina = $_POST['idNomina'];
$idNominaEmpleado = $_POST['idNominaEmpleado'];
$periodo = getPeriodoSBC($fechaPago);

$datosSBC = calcularSBC($idEmpleado, $fechaPago, 0);

$respuesta->estatus = "exito";
$respuesta->SBCFijo =$datosSBC[0];
$respuesta->SBCVariable =$datosSBC[1];

$respuesta = json_encode($respuesta);
echo $respuesta;