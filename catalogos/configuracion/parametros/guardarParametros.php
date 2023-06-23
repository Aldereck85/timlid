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

$diasVencimiento = $_POST['diasVencimiento'];
$leyenda = $_POST['leyenda'];
$diasAguinaldo = $_POST['diasAguinaldo'];
$primaVacacional = $_POST['primaVacacional'];
$riesgoTrabajo = $_POST['riesgoTrabajo'];
$idEmpresa = $_SESSION['IDEmpresa'];


try {
    $conn->beginTransaction();

    //Dias de vencimiento
    $stmt = $conn->prepare('SELECT PKParametros FROM parametros WHERE descripcion = "Dias_Vencimiento" AND empresa_id = '.$idEmpresa );
    $stmt->execute();
    $cuentaDiasVencimiento = $stmt->rowCount();

    if($cuentaDiasVencimiento > 0){
        $stmt = $conn->prepare('UPDATE parametros SET cantidad = :diasVencimiento WHERE descripcion = "Dias_Vencimiento" AND empresa_id = '.$idEmpresa);
        $stmt->bindValue(':diasVencimiento', $diasVencimiento);
        $stmt->execute();
    }
    else{
        $stmt = $conn->prepare('INSERT INTO parametros (descripcion, cantidad, empresa_id) VALUES (:descripcion,:cantidad,:empresa_id)');
        $stmt->bindValue(':descripcion', "Dias_Vencimiento");
        $stmt->bindValue(':cantidad', $diasVencimiento);
        $stmt->bindValue(':empresa_id', $idEmpresa);
        $stmt->execute();
    }

    //Leyenda
    $stmt = $conn->prepare('SELECT PKParametros_texto FROM parametros_texto WHERE descripcion = "leyenda_cotizacion" AND empresa_id = '.$idEmpresa );
    $stmt->execute();
    $cuentaDiasVencimiento = $stmt->rowCount();

    if($cuentaDiasVencimiento > 0){
        $stmt = $conn->prepare('UPDATE parametros_texto SET texto = :leyenda WHERE descripcion = "leyenda_cotizacion" AND empresa_id = '.$idEmpresa);
        $stmt->bindValue(':leyenda', $leyenda);
        $stmt->execute();
    }
    else{
        $stmt = $conn->prepare('INSERT INTO parametros_texto (descripcion, texto, empresa_id) VALUES (:descripcion,:texto,:empresa_id)');
        $stmt->bindValue(':descripcion', "leyenda_cotizacion");
        $stmt->bindValue(':texto', $leyenda);
        $stmt->bindValue(':empresa_id', $idEmpresa);
        $stmt->execute();
    }

    //Dias de aguinaldo
    $stmt = $conn->prepare('SELECT id FROM parametros_nomina WHERE descripcion = "Dias_Aguinaldo" AND empresa_id = '.$idEmpresa );
    $stmt->execute();
    $cuentaDiasVencimiento = $stmt->rowCount();

    if($cuentaDiasVencimiento > 0){
        $stmt = $conn->prepare('UPDATE parametros_nomina SET cantidad = :diasAguinaldo WHERE descripcion = "Dias_Aguinaldo" AND empresa_id = '.$idEmpresa);
        $stmt->bindValue(':diasAguinaldo', $diasAguinaldo);
        $stmt->execute();
    }
    else{
        $stmt = $conn->prepare('INSERT INTO parametros_nomina (descripcion, cantidad, empresa_id) VALUES (:descripcion,:cantidad,:empresa_id)');
        $stmt->bindValue(':descripcion', "Dias_Aguinaldo");
        $stmt->bindValue(':cantidad', $diasAguinaldo);
        $stmt->bindValue(':empresa_id', $idEmpresa);
        $stmt->execute();
    }

    //Prima Vacacional
    $stmt = $conn->prepare('SELECT id FROM parametros_nomina WHERE descripcion = "Prima_Vacacional" AND empresa_id = '.$idEmpresa );
    $stmt->execute();
    $cuentaDiasVencimiento = $stmt->rowCount();

    if($cuentaDiasVencimiento > 0){
        $stmt = $conn->prepare('UPDATE parametros_nomina SET cantidad = :primaVacacional WHERE descripcion = "Prima_Vacacional" AND empresa_id = '.$idEmpresa);
        $stmt->bindValue(':primaVacacional', $primaVacacional);
        $stmt->execute();
    }
    else{
        $stmt = $conn->prepare('INSERT INTO parametros_nomina (descripcion, cantidad, empresa_id) VALUES (:descripcion,:cantidad,:empresa_id)');
        $stmt->bindValue(':descripcion', "Prima_Vacacional");
        $stmt->bindValue(':cantidad', $primaVacacional);
        $stmt->bindValue(':empresa_id', $idEmpresa);
        $stmt->execute();
    }

    //Riesgo trabajo
    $stmt = $conn->prepare('SELECT id FROM parametros_nomina WHERE descripcion = "Riesgo_Trabajo" AND empresa_id = '.$idEmpresa );
    $stmt->execute();
    $cuentaDiasVencimiento = $stmt->rowCount();

    if($cuentaDiasVencimiento > 0){
        $stmt = $conn->prepare('UPDATE parametros_nomina SET cantidad = :riesgoTrabajo WHERE descripcion = "Riesgo_Trabajo" AND empresa_id = '.$idEmpresa);
        $stmt->bindValue(':riesgoTrabajo', $riesgoTrabajo);
        $stmt->execute();
    }
    else{
        $stmt = $conn->prepare('INSERT INTO parametros_nomina (descripcion, cantidad, empresa_id) VALUES (:descripcion,:cantidad,:empresa_id)');
        $stmt->bindValue(':descripcion', "Riesgo_Trabajo");
        $stmt->bindValue(':cantidad', $riesgoTrabajo);
        $stmt->bindValue(':empresa_id', $idEmpresa);
        $stmt->execute();
    }


    if($conn->commit()){
        echo "exito";
    }
    else{
        $conn->rollBack(); 
        echo "fallo";
        return;
    }


} catch (PDOException $ex) {
    $conn->rollBack(); 
    echo "fallo"; 
    echo $ex->getMessage();
}
