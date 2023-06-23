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

$concepto = $_POST['concepto'];
$concepto_guardado = $_POST['concepto_guardado'];
$clave = $_POST['clave'];
$clave_guardada = $_POST['clave_guardada'];
$tipo = $_POST['tipo'];
$tipo_guardado = $_POST['tipo_guardado'];
$idConcepto = $_POST['idConcepto'];
$idEmpresa = $_SESSION['IDEmpresa'];

try {
    $conn->beginTransaction();

    if($concepto != $concepto_guardado){
        
        if($tipo == 1){
            $stmt = $conn->prepare('SELECT id FROM relacion_tipo_percepcion WHERE tipo_percepcion_id = :concepto AND tipo_percepcion_id <> :concepto2 AND empresa_id = '.$idEmpresa);
        }
        else{
            $stmt = $conn->prepare('SELECT id FROM relacion_tipo_deduccion WHERE tipo_deduccion_id = :concepto AND tipo_deduccion_id <> :concepto2 AND empresa_id = '.$idEmpresa);
        }

        $stmt->bindValue(':concepto', $concepto);
        $stmt->bindValue(':concepto2', $concepto_guardado);
        $stmt->execute();
        $existe_concepto = $stmt->rowCount();
        
        if($existe_concepto > 0){ 
            echo "existe-concepto";
            $conn->rollBack(); 
            return;
        }
    }

    if($clave != $clave_guardada){

        $stmt = $conn->prepare('SELECT id FROM relacion_tipo_percepcion WHERE clave = :clave AND clave <> :clave2 AND empresa_id = '.$idEmpresa);
        $stmt->bindValue(':clave', $clave);
        $stmt->bindValue(':clave2', $clave_guardada);
        $stmt->execute();
        $existe1 = $stmt->rowCount();
        
        $stmt = $conn->prepare('SELECT id FROM relacion_tipo_deduccion WHERE clave = :clave AND clave <> :clave2 AND empresa_id = '.$idEmpresa);
        $stmt->bindValue(':clave', $clave);
        $stmt->bindValue(':clave2', $clave_guardada);
        $stmt->execute();
        $existe2 = $stmt->rowCount();
        
        
        if($existe1 > 0 || $existe2 > 0){ 
            echo "existe-clave";
            $conn->rollBack(); 
            return;
        }
    }   


    if($tipo == $tipo_guardado){

        if($tipo == 1){

            $stmt = $conn->prepare('UPDATE relacion_tipo_percepcion SET clave = :clave, tipo_percepcion_id = :tipo_percepcion_id WHERE id = :idConcepto ');
            $stmt->bindValue(':clave', $clave);
            $stmt->bindValue(':tipo_percepcion_id', $concepto);
            $stmt->bindValue(':idConcepto', $idConcepto);
            $stmt->execute();

        }
        else{
            $stmt = $conn->prepare('UPDATE relacion_tipo_deduccion SET clave = :clave, tipo_deduccion_id = :tipo_deduccion_id WHERE id = :idConcepto ');
            $stmt->bindValue(':clave', $clave);
            $stmt->bindValue(':tipo_deduccion_id', $concepto);
            $stmt->bindValue(':idConcepto', $idConcepto);
            $stmt->execute();
        }
        
    }
    else{

        if($tipo_guardado == 1){

            $stmt = $conn->prepare('DELETE FROM relacion_tipo_percepcion WHERE id = :idConcepto ');
            $stmt->bindValue(':idConcepto', $idConcepto);
            $stmt->execute();

            $stmt = $conn->prepare('INSERT relacion_tipo_deduccion (clave, tipo_deduccion_id, empresa_id) VALUES (:clave, :tipo_deduccion_id, :empresa_id )');
            $stmt->bindValue(':clave', $clave);
            $stmt->bindValue(':tipo_deduccion_id', $concepto);
            $stmt->bindValue(':empresa_id', $idEmpresa);
            $stmt->execute();

        }
        else{

            $stmt = $conn->prepare('DELETE FROM relacion_tipo_deduccion WHERE id = :idConcepto ');
            $stmt->bindValue(':idConcepto', $idConcepto);
            $stmt->execute();

            $stmt = $conn->prepare('INSERT relacion_tipo_percepcion (clave, tipo_percepcion_id, empresa_id) VALUES (:clave, :tipo_percepcion_id, :empresa_id )');
            $stmt->bindValue(':clave', $clave);
            $stmt->bindValue(':tipo_percepcion_id', $concepto);
            $stmt->bindValue(':empresa_id', $idEmpresa);
            $stmt->execute();
        }


    }


    if($conn->commit()){
      echo "exito";
    }else{
      $conn->rollBack(); 
      echo "fallo";
    }
    
} catch (PDOException $ex) {
    $conn->rollBack(); 
    echo "fallo"; //$ex->getMessage();
}

?>
