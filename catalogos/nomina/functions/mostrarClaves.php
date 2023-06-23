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

$idclave = $_POST['idclave'];
$tipo = $_POST['tipo'];

if($tipo == 1){
	$stmt = $conn->prepare('SELECT id FROM relacion_tipo_percepcion WHERE tipo_percepcion_id = :idclave AND empresa_id = '.$_SESSION['IDEmpresa'] );
}
else{
	$stmt = $conn->prepare('SELECT id FROM relacion_tipo_deduccion WHERE tipo_deduccion_id = :idclave AND empresa_id = '.$_SESSION['IDEmpresa']);
}

$stmt->bindValue(':idclave', $idclave);
$stmt->execute();
$cantidad = $stmt->rowCount();

echo $cantidad;

?>
