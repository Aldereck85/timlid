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

$idNominaEmpleado = $_POST['idNominaEmpleado'];
$idNomina = $_POST['idNomina'];
$idEmpleado = $_POST['idEmpleado'];
$fechaPago = $_POST['fechaPago'];
$fechaAnio = DateTime::createFromFormat("Y-m-d", $fechaPago);
$anioCalculo = $fechaAnio->format("Y");

$datosSalario = getSalario_Dias($idEmpleado);
$sueldoMensual = $datosSalario[5];


$stmt = $conn->prepare("SELECT dnpe.importe, dnpe.importe_exento, tca.tipo FROM detalle_nomina_percepcion_empleado as dnpe LEFT JOIN tipo_calculo_aguinaldo as tca ON  dnpe.id = tca.detalle_nomina_percepcion_empleado_id WHERE dnpe.nomina_empleado_id = :idNominaEmpleado AND dnpe.empleado_id = :empleado_id AND dnpe.tipo_concepto = 9");
$stmt->bindValue(':idNominaEmpleado',$idNominaEmpleado);
$stmt->bindValue(':empleado_id',$idEmpleado);
$stmt->execute();
$row = $stmt->fetch();

$aguinaldoGravado = $row['importe'];
$aguinaldo = $aguinaldoGravado + $row['importe_exento'];

if(trim($row['tipo']) == '' || $row['tipo'] == NULL){
    $tipo = 1;
}else{
    $tipo = $row['tipo'];
}
//echo $aguinaldoGravado;
$respuesta->estatus = "exito";
$respuesta->resultado = calculoISRAguinaldoMostrar($tipo, $sueldoMensual, $aguinaldoGravado, $aguinaldo, $anioCalculo);


$respuesta = json_encode($respuesta);
echo $respuesta;