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
    $stmt = $conn->prepare('SELECT id, id_nomina_anterior, autorizada FROM nomina WHERE id = :idNomina');
    $stmt->bindValue(':idNomina', $idNomina);
    $stmt->execute();

    $rowN = $stmt->fetch();

    if($rowN['autorizada'] == 1){
        echo "nomina-ya-autorizada";
        return;
    }

    if($rowN['id_nomina_anterior'] == 0){

        $stmt = $conn->prepare('SELECT PKNomina FROM nomina_empleado WHERE FKNomina = :idNomina');
        $stmt->bindValue(':idNomina', $idNomina);
        $stmt->execute();
        $cantidadNominasGeneradas = $stmt->rowCount();

        if($cantidadNominasGeneradas < 1){
            echo "no-existen-nominas";
            return;
        }

        $stmt = $conn->prepare('UPDATE nomina SET autorizada = 1 WHERE id = :idNomina');
        $stmt->bindValue(':idNomina', $idNomina);
        
        if($stmt->execute()){
            echo "exito";
        }
        else{
            echo "fallo";
        }
        
    }
    else{
        $idNominaAnterior = $rowN['id_nomina_anterior'];

        $stmt = $conn->prepare('SELECT PKNomina FROM nomina_empleado WHERE FKNomina = :idNomina');
        $stmt->bindValue(':idNomina', $idNomina);
        $stmt->execute();
        $cantidadNominasGeneradas = $stmt->rowCount();

        if($cantidadNominasGeneradas < 1){
            echo "no-existen-nominas";
            return;
        }

        $stmt = $conn->prepare('SELECT autorizada FROM nomina WHERE id = :id_nomina_anterior');
        $stmt->bindValue(':id_nomina_anterior', $idNominaAnterior);
        $stmt->execute();

        $rowNAnt = $stmt->fetch();
        $autorizado = $rowNAnt['autorizada'];

        if($autorizado == 0){
            echo "no-autorizado";
        }
        else{
            $stmt = $conn->prepare('UPDATE nomina SET autorizada = 1 WHERE id = :idNomina');
            $stmt->bindValue(':idNomina', $idNomina);
            
            if($stmt->execute()){
                echo "exito";
            }
            else{
                echo "fallo";
            }
        }

    }
}
catch(PDOException $ex){
    //echo $ex;
    echo "fallo";
}
?>
