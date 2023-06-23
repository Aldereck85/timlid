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

$SBC = $SBCFijo + $SBCVariables;
$anioCalculo = calcularAnio($fechaPago, 1);
$UMA = getUMA($anioCalculo);
$limite = $UMA * 25;

if($SBC > $limite){
    $respuesta->estatus = "limite-excedido";
}
else{
   $respuesta->estatus = actualizarSBC($idEmpleado, $SBCFijo, $SBCVariables, $periodo, $fechaPago); 
}


if($respuesta->estatus == "exito"){
    $modo = 2;//para agregar o restar cantidades adicionales al total de percepciones 
    require("calculoImpuestos.php");
}

$respuesta = json_encode($respuesta);
echo $respuesta;