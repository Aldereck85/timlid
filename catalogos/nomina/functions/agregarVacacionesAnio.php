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
$fecha = date("Y-m-d H:i:s");
$anio = $_POST['anio'];
$dias = $_POST['dias'];
$idUsuario = $_SESSION['PKUsuario'];

$stmt = $conn->prepare('SELECT id, diasagregados, diasrestantes FROM vacaciones_agregadas WHERE anio = :anio AND empleado_id = :empleado_id ');
$stmt->bindValue(':anio', $anio);
$stmt->bindValue(':empleado_id', $idEmpleado);
$stmt->execute();
$existe = $stmt->rowCount();

if($existe > 0){
    $row = $stmt->fetch();

    $diasAgregadosFinales = $row['diasagregados'] + $dias;
    $diasRestantesFinales = $row['diasrestantes'] + $dias;

    $stmt = $conn->prepare('UPDATE vacaciones_agregadas SET diasagregados = :diasagregados, diasrestantes = :diasrestantes, fecha_edicion = :fecha_edicion , usuario_edicion = :usuario_edicion WHERE anio = :anio AND empleado_id = :empleado_id ');
    $stmt->bindValue(':diasagregados', $diasAgregadosFinales);
    $stmt->bindValue(':diasrestantes', $diasRestantesFinales);
    $stmt->bindValue(':fecha_edicion', $fecha);
    $stmt->bindValue(':usuario_edicion', $idUsuario);
    $stmt->bindValue(':anio', $anio);
    $stmt->bindValue(':empleado_id', $idEmpleado);
    

    if($stmt->execute()){
        echo "exito";
    }
    else{
        echo "fallo";
    }
}
else{
    $stmt = $conn->prepare('INSERT INTO vacaciones_agregadas (anio, diasagregados, diasrestantes, empleado_id, fecha_alta, fecha_edicion, usuario_alta, usuario_edicion) VALUES (:anio, :diasagregados, :diasrestantes, :empleado_id, :fecha_alta,:fecha_edicion,:usuario_alta,:usuario_edicion)');
    $stmt->bindValue(':anio', $anio);
    $stmt->bindValue(':diasagregados', $dias);
    $stmt->bindValue(':diasrestantes', $dias);
    $stmt->bindValue(':empleado_id', $idEmpleado);
    $stmt->bindValue(':fecha_alta', $fecha);
    $stmt->bindValue(':fecha_edicion', $fecha);
    $stmt->bindValue(':usuario_alta', $idUsuario);
    $stmt->bindValue(':usuario_edicion', $idUsuario);
    

    if($stmt->execute()){
        echo "exito";
    }
    else{
        echo "fallo";
    }
}

?>
