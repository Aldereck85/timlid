<?php
session_start();

$jwt_ruta = "../../../";
require_once '../../jwt.php';

date_default_timezone_set('America/Mexico_City');

error_reporting(E_ALL & ~E_WARNING);

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

$idEmpleado = $_POST['idEmpleado'];


$stmt = $conn->prepare('SELECT estatus FROM empleados WHERE PKEmpleado ='.$idEmpleado.' AND empresa_id = '.$_SESSION['IDEmpresa']);
$stmt->execute();
$row = $stmt->fetch();
$estatus = $row['estatus'];

if($estatus == 1){
    echo "activo";
    return;
}

$stmt = $conn->prepare('UPDATE empleados SET estatus = 1  WHERE PKEmpleado ='.$idEmpleado.' AND empresa_id = '.$_SESSION['IDEmpresa']);

if($stmt->execute()){
    echo "exito";
}
else{
    echo "fallo";
}

?>
