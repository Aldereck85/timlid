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
$GLOBALS['rutaFuncion'] = "./../";
require_once("../../../functions/funcionNomina.php");

date_default_timezone_set('America/Mexico_City');

$hora = $_POST['hora'];
$tipoHora = $_POST['tipoHora'];
$idEmpleado = $_POST['idEmpleado'];
$horascalculo = $hora * $tipoHora;
$datosSalario = getSalario_Dias($idEmpleado);
$salarioHora = $datosSalario[4];
$salarioHoraCalculado = bcdiv($salarioHora * $horascalculo,1,2);

echo number_format($salarioHoraCalculado,2);

?>
