<?php
session_start();

$jwt_ruta = "../../../";
require_once '../../jwt.php';

$token = $_POST['csr_token_UT5JP'];

if(empty($_SESSION['token_ld10d'])) {
    $json->estatus = "fallo";
    $json = json_encode($json);
    echo $json;
    return;           
}

if (!hash_equals($_SESSION['token_ld10d'], $token)) {
    $json->estatus = "fallo";
    $json = json_encode($json);
    echo $json;
    return;
}

$json = new \stdClass();
require_once '../../../include/db-conn.php';

$conceptoID = $_POST['conceptoID'];
$idEmpresa = $_SESSION['IDEmpresa'];
$sucursales = $_POST['cadenaSucursales'];

$stmt = $conn->prepare('SELECT id FROM relacion_sucursal_deduccion WHERE relacion_concepto_deduccion_id = :relacion_concepto_deduccion_id AND empresa_id = :empresa_id');
$stmt->bindValue(':relacion_concepto_deduccion_id', $conceptoID);
$stmt->bindValue(':empresa_id', $idEmpresa);
$stmt->execute();
$existesucursal = $stmt->fetchAll();

if(count($existesucursal) > 0){
    $json->estatus = "existe-registro";
    $json = json_encode($json);
    echo $json;
    return;
}

try{
    
    $conn->beginTransaction();

    foreach($sucursales as $s){
        $stmt = $conn->prepare('INSERT INTO relacion_sucursal_deduccion ( tipo_base, cantidad, relacion_concepto_deduccion_id , sucursal_id, empresa_id) VALUES( :tipo_base, :cantidad, :relacion_concepto_deduccion_id, :sucursal_id, :empresa_id)');
        $stmt->bindValue(':tipo_base', $s[2]);
        $stmt->bindValue(':cantidad', $s[1]);
        $stmt->bindValue(':relacion_concepto_deduccion_id', $conceptoID);
        $stmt->bindValue(':sucursal_id', $s[0]);
        $stmt->bindValue(':empresa_id', $idEmpresa);
        $stmt->execute();
    }

    if($conn->commit()){
        $json->estatus = "exito";
    }
    else{
        $json->estatus = "fallo";
    }

} catch (PDOException $ex) {
    $conn->rollBack(); 
    $json->estatus = "fallo";
    //echo $ex->getMessage();
}

$json = json_encode($json);
echo $json;

?>
