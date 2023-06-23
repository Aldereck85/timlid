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

$idconcepto = $_POST['idconcepto'];
$idEmpresa = $_SESSION['IDEmpresa'];
$idsucursal = $_POST['idsucursal'];
$sucursal = $_POST['sucursal'];
$baseSucursal = $_POST['baseSucursal'];
$valorSucursal = $_POST['valorSucursal'];

try {
    
    $stmt = $conn->prepare('SELECT id FROM relacion_sucursal_deduccion WHERE relacion_concepto_deduccion_id = :relacion_concepto_deduccion_id AND sucursal_id = :sucursal_id AND empresa_id = :empresa_id');
    $stmt->bindValue(':relacion_concepto_deduccion_id', $idconcepto);
    $stmt->bindValue(':sucursal_id', $idsucursal);
    $stmt->bindValue(':empresa_id', $idEmpresa);
    $stmt->execute();
    $existe = $stmt->rowCount();
    
    if($existe > 0){ 
       $json->estatus = "existe";
    }
    else{

        $stmt = $conn->prepare('INSERT INTO relacion_sucursal_deduccion ( tipo_base, cantidad, relacion_concepto_deduccion_id , sucursal_id, empresa_id) VALUES( :tipo_base, :cantidad, :relacion_concepto_deduccion_id, :sucursal_id, :empresa_id)');
        $stmt->bindValue(':tipo_base', $baseSucursal);
        $stmt->bindValue(':cantidad', $valorSucursal);
        $stmt->bindValue(':relacion_concepto_deduccion_id', $idconcepto);
        $stmt->bindValue(':sucursal_id', $idsucursal);
        $stmt->bindValue(':empresa_id', $idEmpresa);

        if($stmt->execute()){

          $id = $conn->lastInsertId();

          $json->id = $id;
          $json->concepto = $sucursal;
          $json->estatus = "exito";
        }else{
          $json->estatus = "fallo";
        }
    }
    
    
} catch (PDOException $ex) {
   $json->estatus = "fallo"; echo $ex->getMessage();
}

$json = json_encode($json);
echo $json;

?>
