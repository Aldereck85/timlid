<?php
session_start();

$jwt_ruta = "../../../";
require_once '../../jwt.php';

date_default_timezone_set('America/Mexico_City');

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

$idPensionAlimenticia = $_POST['idPensionAlimenticia'];
$fechaAplicacion = $_POST['fechaAplicacion'];
$pensionAlimenticiaTipo = $_POST['pensionAlimenticiaTipo'];
$PorcentajeAplicar = $_POST['PorcentajeAplicar'];

try {

    $stmt = $conn->prepare('UPDATE pension_alimenticia SET fecha_aplicacion = :fecha_aplicacion, tipo_importe = :tipo_importe, tasa_pension = :tasa_pension WHERE id = :idPensionAlimenticia');
    $stmt->bindValue(':fecha_aplicacion', $fechaAplicacion);
    $stmt->bindValue(':tipo_importe', $pensionAlimenticiaTipo);
    $stmt->bindValue(':tasa_pension', $PorcentajeAplicar);
    $stmt->bindValue(':idPensionAlimenticia', $idPensionAlimenticia);
    
    if($stmt->execute()){
        echo "exito";
    }
    else{
        echo "fallo"; 
    }
    
} catch (PDOException $ex) {
    echo "fallo"; 
    //echo $ex->getMessage();
}

?>
