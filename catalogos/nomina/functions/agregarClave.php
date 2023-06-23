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
$idEmpresa = $_SESSION['IDEmpresa'];
$claveguardada = $_POST['claveguardada'];

$existe_base_empresa = $_POST['existe_base_empresa'];
$baseEmpresa = $_POST['baseEmpresa'];
$valorEmpresa = $_POST['valorEmpresa'];

if($claveguardada !== $clave){
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

            $stmt = $conn->prepare('SELECT id FROM relacion_tipo_percepcion WHERE tipo_percepcion_id = :idconcepto AND empresa_id = '.$idEmpresa);
            $stmt->bindValue(':idconcepto', $idconcepto);
            $stmt->execute();
            $existe_concepto = $stmt->rowCount();
            
            if($existe_concepto > 0){ 
                $stmt = $conn->prepare('UPDATE relacion_tipo_percepcion SET clave = :clave WHERE tipo_percepcion_id = :tipo_percepcion_id AND empresa_id = :empresa_id');
                $stmt->bindValue(':clave', $clave);
                $stmt->bindValue(':tipo_percepcion_id', $idconcepto);
                $stmt->bindValue(':empresa_id', $idEmpresa);

                if($stmt->execute()){
                  $json->estatus_clave = "exito";
                }else{
                  $json->estatus_clave = "fallo";
                }
            }
            else{
                $stmt = $conn->prepare('INSERT INTO relacion_tipo_percepcion ( clave, tipo_percepcion_id, empresa_id) VALUES( :clave, :tipo_percepcion_id, :empresa_id)');
                $stmt->bindValue(':clave', $clave);
                $stmt->bindValue(':tipo_percepcion_id', $idconcepto);
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


$json = json_encode($json);
echo $json;

?>
