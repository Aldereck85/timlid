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

$idVacaciones = $_POST['idVacaciones'];
$fecha = date("Y-m-d H:i:s");
$dias = $_POST['dias'];
$idUsuario = $_SESSION['PKUsuario'];

$stmt = $conn->prepare('SELECT diasagregados, diasrestantes FROM vacaciones_agregadas WHERE id = :id ');
$stmt->bindValue(':id', $idVacaciones);
$stmt->execute();
$existe = $stmt->rowCount();

$row = $stmt->fetch();    

$diasAgregadosFinales = $row['diasagregados'] - $dias;
$diasRestantesFinales = $row['diasrestantes'] - $dias;

$stmt = $conn->prepare('UPDATE vacaciones_agregadas SET diasagregados = :diasagregados, diasrestantes = :diasrestantes, fecha_edicion = :fecha_edicion , usuario_edicion = :usuario_edicion WHERE id = :id ');
$stmt->bindValue(':diasagregados', $diasAgregadosFinales);
$stmt->bindValue(':diasrestantes', $diasAgregadosFinales);
$stmt->bindValue(':fecha_edicion', $fecha);
$stmt->bindValue(':usuario_edicion', $idUsuario);
$stmt->bindValue(':id', $idVacaciones);

if($stmt->execute()){
    echo "exito";
}
else{
    echo "fallo";
}

?>
