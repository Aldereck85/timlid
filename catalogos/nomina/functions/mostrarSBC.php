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

$datosSBC = getSBCNomina($idEmpleado,$fechaPago);
$respuesta->estatus = "exito";
$respuesta->SBCFijo = $datosSBC[0];

if($datosSBC[1] == "" || $datosSBC[1] == null){
  $respuesta->SBCVariable = 0;
}
else{
  $respuesta->SBCVariable = $datosSBC[1];
}


$respuesta = json_encode($respuesta);
echo $respuesta;