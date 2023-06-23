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

$idCreditoFonacot = $_POST['idCreditoFonacot'];

try {

    $stmt = $conn->prepare('UPDATE credito_fonacot SET estado = 2 WHERE id = :idCreditoFonacot');
    $stmt->bindValue(':idCreditoFonacot', $idCreditoFonacot);
    
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
