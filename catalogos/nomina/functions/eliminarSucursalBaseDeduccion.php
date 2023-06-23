<?php
session_start();

$jwt_ruta = "../../../";
require_once '../../jwt.php';

$token = $_POST['csr_token_UT5JP'];

if(empty($_SESSION['token_ld10d'])) {
    $json->estatus = "fallo";        
}

if (!hash_equals($_SESSION['token_ld10d'], $token)) {
    $json->estatus = "fallo";
}

$json = new \stdClass();
require_once '../../../include/db-conn.php';

$idBaseSucursal = $_POST['idBaseSucursal'];
$idEmpresa = $_SESSION['IDEmpresa'];

try {
    $stmt = $conn->prepare('DELETE FROM relacion_sucursal_deduccion WHERE id = :idBaseSucursal AND empresa_id = :empresa_id');
    $stmt->bindValue(':idBaseSucursal', $idBaseSucursal);
    $stmt->bindValue(':empresa_id', $idEmpresa);

    if($stmt->execute()){
      $json->estatus = "exito";
    }else{
      $json->estatus = "fallo";
    }
    
} catch (PDOException $ex) {
   $json->estatus = "fallo"; echo $ex->getMessage();
}

$json = json_encode($json);
echo $json;

?>
