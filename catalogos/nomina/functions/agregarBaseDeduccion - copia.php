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

$idconcepto = $_POST['idconcepto'];
$clave = $_POST['clave'];
$tipo = $_POST['tipo'];
$idEmpresa = $_SESSION['IDEmpresa'];
$claveguardada = $_POST['claveguardada'];

$existe_base_empresa = $_POST['existe_base_empresa'];
$baseEmpresa = $_POST['baseEmpresa'];
$valorEmpresa = $_POST['valorEmpresa'];

if($claveguardada != $clave){
    try {
        
        $stmt = $conn->prepare('SELECT id FROM relacion_tipo_percepcion WHERE clave = :clave AND  empresa_id = '.$idEmpresa);
        $stmt->bindValue(':clave', $clave);
        $stmt->execute();
        $existe1 = $stmt->rowCount();

        $stmt = $conn->prepare('SELECT id FROM relacion_tipo_deduccion WHERE clave = :clave AND  empresa_id = '.$idEmpresa);
        $stmt->bindValue(':clave', $clave);
        $stmt->execute();
        $existe2 = $stmt->rowCount();
        
        if($existe1 > 0 || $existe2 > 0){ 
           $json->estatus_clave = "existe-clave";
        }
        else{

            $stmt = $conn->prepare('SELECT id FROM relacion_tipo_deduccion WHERE tipo_deduccion_id = :idconcepto AND empresa_id = '.$idEmpresa);
            $stmt->bindValue(':idconcepto', $idconcepto);
            $stmt->execute();
            $existe_concepto = $stmt->rowCount();
            
            if($existe_concepto > 0){ 
                $stmt = $conn->prepare('UPDATE relacion_tipo_deduccion SET clave = :clave WHERE tipo_deduccion_id = :tipo_deduccion_id AND empresa_id = :empresa_id');
                $stmt->bindValue(':clave', $clave);
                $stmt->bindValue(':tipo_deduccion_id', $idconcepto);
                $stmt->bindValue(':empresa_id', $idEmpresa);

                if($stmt->execute()){
                  $json->estatus_clave = "exito";
                }else{
                  $json->estatus_clave = "fallo";
                }
            }
            else{
                $stmt = $conn->prepare('INSERT INTO relacion_tipo_deduccion ( clave, tipo_deduccion_id, empresa_id) VALUES( :clave, :tipo_deduccion_id, :empresa_id)');
                $stmt->bindValue(':clave', $clave);
                $stmt->bindValue(':tipo_deduccion_id', $idconcepto);
                $stmt->bindValue(':empresa_id', $idEmpresa);

                if($stmt->execute()){
                  $json->estatus_clave = "exito";
                }else{
                  $json->estatus_clave = "fallo";
                }
            }
        }
        
        
    } catch (PDOException $ex) {
       $json->estatus_clave = "fallo"; //$ex->getMessage();
    }
}
else{
    $json->estatus_clave = "sin_cambio";
}


//0 quiere decir que no existe
//1 ya se agrego
if($existe_base_empresa == 0){

    if($tipo == 1){
        $json->estatus_base_empresa = "sin_cambio";
    }
    if($tipo == 2){

        $stmt = $conn->prepare('INSERT INTO relacion_base_deduccion ( tipo_deduccion_id, tipo_base, cantidad, empresa_id) VALUES( :tipo_deduccion_id, :tipo_base, :cantidad, :empresa_id)');
        $stmt->bindValue(':tipo_deduccion_id', $idconcepto);
        $stmt->bindValue(':tipo_base', $baseEmpresa);
        $stmt->bindValue(':cantidad', $valorEmpresa);
        $stmt->bindValue(':empresa_id', $idEmpresa);

        if($stmt->execute()){
            $json->estatus_base_empresa = "exito";
        }
        else{
            $json->estatus_base_empresa = "fallo";
        }
    }

}
if($existe_base_empresa == 1){
    if($tipo == 1){

        $stmt = $conn->prepare('DELETE FROM relacion_base_deduccion WHERE tipo_deduccion_id = :tipo_deduccion_id AND empresa_id = :empresa_id');
        $stmt->bindValue(':tipo_deduccion_id', $idconcepto);
        $stmt->bindValue(':empresa_id', $idEmpresa);

        if($stmt->execute()){
            $json->estatus_base_empresa = "exito";
        }
        else{
            $json->estatus_base_empresa = "fallo";
        }
    }
    if($tipo == 2){

        $stmt = $conn->prepare('UPDATE relacion_base_deduccion SET tipo_base = :tipo_base, cantidad = :cantidad WHERE tipo_deduccion_id  = :tipo_deduccion_id AND empresa_id = :empresa_id');
        $stmt->bindValue(':tipo_base', $baseEmpresa);
        $stmt->bindValue(':cantidad', $valorEmpresa);
        $stmt->bindValue(':tipo_deduccion_id', $idconcepto);
        $stmt->bindValue(':empresa_id', $idEmpresa);

        if($stmt->execute()){
            $json->estatus_base_empresa = "exito";
        }
        else{
            $json->estatus_base_empresa = "fallo";
        }
    }
}   


$json = json_encode($json);
echo $json;

?>
