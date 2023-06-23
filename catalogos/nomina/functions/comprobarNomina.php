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


$idNomina = $_POST['idNomina'];

try{
    $stmt = $conn->prepare('SELECT id, id_nomina_anterior FROM nomina WHERE id = :idNomina');
    $stmt->bindValue(':idNomina', $idNomina);
    $stmt->execute();

    $rowN = $stmt->fetch();

    if($rowN['id_nomina_anterior'] == 0){
        echo "exito";
    }
    else{
        $idNominaAnterior = $rowN['id_nomina_anterior'];

        $stmt = $conn->prepare('SELECT autorizada FROM nomina WHERE id = :id_nomina_anterior');
        $stmt->bindValue(':id_nomina_anterior', $idNominaAnterior);
        $stmt->execute();

        $rowNAnt = $stmt->fetch();
        $autorizado = $rowNAnt['autorizada'];

        if($autorizado == 0){
            echo "no-autorizado";
        }
        else{
            echo "exito";
        }

    }
}
catch(PDOException $ex){
    echo "fallo";
}
?>