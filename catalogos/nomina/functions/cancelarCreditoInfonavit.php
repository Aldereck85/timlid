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

$idCreditoInfonavit = $_POST['idCreditoInfonavit'];

try {

    $stmt = $conn->prepare('UPDATE credito_infonavit SET estado = 2 WHERE id = :idCreditoInfonavit');
    $stmt->bindValue(':idCreditoInfonavit', $idCreditoInfonavit);
    
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
