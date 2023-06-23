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

$idNomina = $_POST['idNomina'];

try {

    $stmt = $conn->prepare('SELECT estatus FROM nomina WHERE id ='.$idNomina);
    $stmt->execute();
    $row = $stmt->fetch();
    $estatus = $row['estatus'];

    if($estatus == 2){
        echo "fallo-cancelacion";
        return;
    }

    $stmt = $conn->prepare('DELETE FROM nomina WHERE id = :idNomina');
    $stmt->bindValue(':idNomina', $idNomina);

    if($stmt->execute()){
      echo "exito";
    }else{
      echo "fallo";
    }
    
} catch (PDOException $ex) {
    echo "fallo"; //$ex->getMessage();
}

?>
