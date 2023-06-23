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

$idEmpleado = $_POST['idEmpleado'];

$diasVacaciones = getDiasVacaciones($idEmpleado);

if($diasVacaciones > 0){
  echo "exito";
}
else{
  echo "fallo";
}

?>
