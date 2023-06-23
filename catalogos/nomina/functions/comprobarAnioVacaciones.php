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

date_default_timezone_set('America/Mexico_City');

$idEmpleado = $_POST['idEmpleado'];

if(isset($_POST['anios'])){
    $anioAct = $_POST['anios'];
}
else{
    $anioAct = date("Y"); 
}


$stmt = $conn->prepare('SELECT id FROM vacaciones_agregadas WHERE anio = :anioAct AND empleado_id = :empleado_id ');
$stmt->bindValue(':anioAct', $anioAct);
$stmt->bindValue(':empleado_id', $idEmpleado);
$stmt->execute();
$existe = $stmt->rowCount();

if($existe > 0){
    echo "exito";
}
else{
    echo "fallo";
}


?>
