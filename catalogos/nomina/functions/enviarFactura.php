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

require_once '../../../include/db-conn.php';

$idFactura = $_POST['idFactura'];
$idEmpresa = $_SESSION['IDEmpresa'];
$email = $_POST['destino'];
$tipo = $_POST['tipo']; //1 nomina_empleado 2 finiquito

$ruta_api = "../../../";
include $ruta_api . "include/functions_api_facturation.php";
require_once $ruta_api . 'vendor/facturapi/facturapi-php/src/Facturapi.php';
$api = new API();

$query = sprintf("select key_company_api from empresas where PKEmpresa = :id");
$stmt = $conn->prepare($query);
$stmt->bindValue(":id",$_SESSION['IDEmpresa']);
$stmt->execute();
$emp = $stmt->fetchAll();

if($tipo == 1){
    $query = sprintf("select uuid from nomina_empleado where idFactura = :id");
}
elseif($tipo == 2){
    $query = sprintf("select uuid from finiquito where idFactura = :id");
}
elseif($tipo == 3){
    $query = sprintf("select uuidLiquidacion as uuid from liquidacion where idFacturaLiquidacion = :id");
}

$stmt = $conn->prepare($query);
$stmt->bindValue(":id",$idFactura );
$stmt->execute();
$fact = $stmt->fetchAll();

$mensaje = $api->sendEmailInvoice($emp[0]['key_company_api'],$idFactura,$email);

if($mensaje->ok){
    echo "exito";
}
else{
    echo "fallo";
}

?>
