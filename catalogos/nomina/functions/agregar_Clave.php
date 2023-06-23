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

$concepto = $_POST['concepto'];
$tipo = $_POST['tipo'];
$clave = $_POST['clave'];
$idEmpresa = $_SESSION['IDEmpresa'];

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
        echo "existe-clave";
    }
    else{

        if($tipo == 1){
            $stmt = $conn->prepare('SELECT id FROM relacion_tipo_percepcion WHERE tipo_percepcion_id = :concepto AND empresa_id = '.$idEmpresa);
        }
        else{
            $stmt = $conn->prepare('SELECT id FROM relacion_tipo_deduccion WHERE tipo_deduccion_id = :concepto AND empresa_id = '.$idEmpresa);
        }

        $stmt->bindValue(':concepto', $concepto);
        $stmt->execute();
        $existe_concepto = $stmt->rowCount();
        
        if($existe_concepto > 0){ 
            echo "existe-concepto";
        }
        else{

            if($tipo == 1){
                $stmt = $conn->prepare('INSERT INTO relacion_tipo_percepcion ( clave, tipo_percepcion_id, empresa_id) VALUES( :clave, :tipo_percepcion_id, :empresa_id)');
                $stmt->bindValue(':clave', $clave);
                $stmt->bindValue(':tipo_percepcion_id', $concepto);
                $stmt->bindValue(':empresa_id', $idEmpresa);
            }
            else{
                $stmt = $conn->prepare('INSERT INTO relacion_tipo_deduccion ( clave, tipo_deduccion_id, empresa_id) VALUES( :clave, :tipo_deduccion_id, :empresa_id)');
                $stmt->bindValue(':clave', $clave);
                $stmt->bindValue(':tipo_deduccion_id', $concepto);
                $stmt->bindValue(':empresa_id', $idEmpresa);
            }
            

            if($stmt->execute()){
              echo "exito";
            }else{
              echo "fallo";
            }
        }
    }
    
    
} catch (PDOException $ex) {
    echo "fallo"; //$ex->getMessage();
}

?>
