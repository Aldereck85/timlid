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
$tipo = $_POST['tipo'];
$concepto_guardado = $_POST['concepto_guardado'];
$clave = $_POST['clave'];
$clave_guardada = $_POST['clave_guardada'];
$idConcepto = $_POST['idconcepto'];
$idEmpresa = $_SESSION['IDEmpresa'];

if($tipo == 1){
    $stmt = $conn->prepare('SELECT tipo_percepcion_id FROM relacion_concepto_percepcion WHERE id = :idConcepto  AND empresa_id = '.$idEmpresa);
    $stmt->bindValue(':idConcepto', $idConcepto);
    $stmt->execute();
    $row_percepcion = $stmt->fetch();
    $idPercepcion = $row_percepcion['tipo_percepcion_id'];
}
if($tipo == 2){
    $stmt = $conn->prepare('SELECT tipo_deduccion_id FROM relacion_concepto_deduccion WHERE id = :idConcepto  AND empresa_id = '.$idEmpresa);
    $stmt->bindValue(':idConcepto', $idConcepto);
    $stmt->execute();
    $row_deduccion = $stmt->fetch();
    $idDeduccion = $row_deduccion['tipo_deduccion_id'];
}

try {
    $conn->beginTransaction();

    if($concepto != $concepto_guardado){
        
        if($tipo == 1){
            $stmt = $conn->prepare('SELECT id FROM relacion_concepto_percepcion WHERE concepto_nomina = :concepto AND empresa_id = '.$idEmpresa);
        }
        else{
            $stmt = $conn->prepare('SELECT id FROM relacion_concepto_deduccion WHERE concepto_nomina = :concepto AND empresa_id = '.$idEmpresa);
        }

        $stmt->bindValue(':concepto', $concepto);
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


    if($tipo == 1){

        $stmt = $conn->prepare('UPDATE relacion_tipo_percepcion SET clave = :clave WHERE tipo_percepcion_id = :idPercepcion AND empresa_id = '.$idEmpresa);
        $stmt->bindValue(':clave', $clave);
        $stmt->bindValue(':idPercepcion', $idPercepcion);
        $stmt->execute();

        $stmt = $conn->prepare('UPDATE relacion_concepto_percepcion SET concepto_nomina = :concepto_nomina WHERE id = :idConcepto AND empresa_id = '.$idEmpresa);
        $stmt->bindValue(':concepto_nomina', $concepto);
        $stmt->bindValue(':idConcepto', $idConcepto);
        $stmt->execute();

    }
    else{
        $stmt = $conn->prepare('UPDATE relacion_tipo_deduccion SET clave = :clave WHERE tipo_deduccion_id = :idDeduccion AND empresa_id = '.$idEmpresa);
        $stmt->bindValue(':clave', $clave);
        $stmt->bindValue(':idDeduccion', $idDeduccion);
        $stmt->execute();

        $stmt = $conn->prepare('UPDATE relacion_concepto_deduccion SET concepto_nomina = :concepto_nomina WHERE id = :idConcepto AND empresa_id = '.$idEmpresa);
        $stmt->bindValue(':concepto_nomina', $concepto);
        $stmt->bindValue(':idConcepto', $idConcepto);
        $stmt->execute();
    }


    if($conn->commit()){
      echo "exito";
    }else{
      $conn->rollBack(); 
      echo "fallo";
    }
    
} catch (PDOException $ex) {
    $conn->rollBack(); 
    echo "fallo"; //echo $ex->getMessage();
}

?>
