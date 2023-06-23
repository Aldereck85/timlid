<?php
session_start();

$jwt_ruta = "../../../";
require_once '../../jwt.php';

$token = $_POST['csr_token_UT5JP'];

if(empty($_SESSION['token_ld10d'])) {
    $json->estatus = "fallo";
    return;           
}

if (!hash_equals($_SESSION['token_ld10d'], $token)) {
    $json->estatus = "fallo";
    return;
}

$json = new \stdClass();
require_once '../../../include/db-conn.php';

$conceptoID = $_POST['conceptoID'];
$idEmpresa = $_SESSION['IDEmpresa'];
$baseEmpresa = $_POST['baseEmpresa'];
$valorEmpresa = $_POST['valorEmpresa'];

$stmt = $conn->prepare('SELECT id FROM relacion_base_percepcion WHERE relacion_concepto_percepcion_id = :idConcepto AND empresa_id = :empresa_id');
$stmt->bindValue(':idConcepto', $conceptoID);
$stmt->bindValue(':empresa_id', $idEmpresa);
$stmt->execute();
$existe = $stmt->rowCount();

if($existe > 0){
    $json->estatus_base_empresa = "existe";
    $json = json_encode($json);
    echo $json;
    return;
}

$stmt = $conn->prepare('INSERT INTO relacion_base_percepcion ( relacion_concepto_percepcion_id , tipo_base, cantidad, empresa_id) VALUES( :relacion_concepto_percepcion_id, :tipo_base, :cantidad, :empresa_id)');
$stmt->bindValue(':relacion_concepto_percepcion_id', $conceptoID);
$stmt->bindValue(':tipo_base', $baseEmpresa);
$stmt->bindValue(':cantidad', $valorEmpresa);
$stmt->bindValue(':empresa_id', $idEmpresa);

if($stmt->execute()){
    $json->estatus_base_empresa = "exito";
}
else{
    $json->estatus_base_empresa = "fallo";
}


/*
if($existe_base_empresa == 1){

    if($tipo == 1){

        $stmt = $conn->prepare('DELETE FROM relacion_base_percepcion WHERE tipo_percepcion_id = :tipo_percepcion_id AND empresa_id = :empresa_id');
        $stmt->bindValue(':tipo_percepcion_id', $idconcepto);
        $stmt->bindValue(':empresa_id', $idEmpresa);

        if($stmt->execute()){
            $json->estatus_base_empresa = "exito";
        }
        else{
            $json->estatus_base_empresa = "fallo";
        }
    }

    if($tipo == 2){

        $stmt = $conn->prepare('UPDATE relacion_base_percepcion SET tipo_base = :tipo_base, cantidad = :cantidad WHERE tipo_percepcion_id = :tipo_percepcion_id AND empresa_id = :empresa_id');
        $stmt->bindValue(':tipo_base', $baseEmpresa);
        $stmt->bindValue(':cantidad', $valorEmpresa);
        $stmt->bindValue(':tipo_percepcion_id', $idconcepto);
        $stmt->bindValue(':empresa_id', $idEmpresa);

        if($stmt->execute()){
            $json->estatus_base_empresa = "exito";
        }
        else{
            $json->estatus_base_empresa = "fallo";
        }
    }
}
*/    


$json = json_encode($json);
echo $json;

?>
