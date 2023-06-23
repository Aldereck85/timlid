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


$idVacaciones = $_POST['idVacaciones'];
require_once('../../../include/db-conn.php');


$stmt = $conn->prepare("SELECT anio, diasagregados, diasrestantes FROM vacaciones_agregadas WHERE id = :id");

if($stmt->execute(array(':id'=>$idVacaciones))){
    $json->respuesta = "exito";
}
else{
    $json->respuesta = "fallo";
    return;
}
$row = $stmt->fetch();

$json->anio = $row['anio'];
$json->diasagregados = $row['diasagregados'];
$json->diasrestantes = $row['diasrestantes'];



$json = json_encode($json);
echo $json;

?>