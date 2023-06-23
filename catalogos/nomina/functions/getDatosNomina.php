<?php
session_start();

$jwt_ruta = "../../../";
require_once '../../jwt.php';

$json = new \stdClass();

$token = $_POST['csr_token_UT5JP'];

if(empty($_SESSION['token_ld10d'])) {
    $json->respuesta = "fallo";
    return;           
}

if (!hash_equals($_SESSION['token_ld10d'], $token)) {
    $json->respuesta = "fallo";
    return;
}


$idNomina = $_POST['idNomina'];
require_once('../../../include/db-conn.php');


$stmt = $conn->prepare("SELECT no_nomina, fecha_pago, fecha_inicio, fecha_fin, estatus, ultima_nomina FROM nomina WHERE id = :id");

if($stmt->execute(array(':id'=>$idNomina))){
    $json->respuesta = "exito";
}
else{
    $json->respuesta = "fallo";
    return;
}
$row = $stmt->fetch();

$json->no_nomina = $row['no_nomina'];
$json->fecha_pago = $row['fecha_pago'];
$json->fecha_inicio = $row['fecha_inicio'];
$json->fecha_fin = $row['fecha_fin'];
$json->estatus = $row['estatus'];
$json->ultima_nomina = $row['ultima_nomina'];

$json = json_encode($json);
echo $json;

?>