<?php
session_start();

$jwt_ruta = "../../../";
require_once '../../jwt.php';

$token = $_POST['csr_token_UT5JP'];

if(empty($_SESSION['token_ld10d'])) {
    $json->estatus = "fallo";
    return;           
}

if (!hash_equals($_SESSION['token_ld10d'], $token)) {
    $json->estatus = "fallo";
    return;
}

$json = new \stdClass();
require_once '../../../include/db-conn.php';

$conceptoID = $_POST['conceptoID'];
$idEmpresa = $_SESSION['IDEmpresa'];

$stmt = $conn->prepare('DELETE FROM relacion_base_deduccion WHERE id = :idConcepto AND empresa_id = :empresa_id ');
$stmt->bindValue(':idConcepto', $conceptoID);
$stmt->bindValue(':empresa_id', $idEmpresa);

if($stmt->execute()){
    $json->estatus_base_empresa = "exito";
}
else{
    $json->estatus_base_empresa = "fallo";
}

$json = json_encode($json);
echo $json;

?>
