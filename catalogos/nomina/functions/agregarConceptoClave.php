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

$tipo = $_POST['tipo'];
$idUsuario = $_SESSION['PKUsuario'];
$fecha = date("Y-m-d H:i:s");
$disponibleClave = $_POST['disponibleClave'];
$clave = $_POST['clave'];
$idEmpresa = $_SESSION['IDEmpresa'];
$nuevoConcepto = $_POST['nuevoConcepto'];
$claveSAT = $_POST['claveSAT'];


if($tipo == 1){
    $stmt = $conn->prepare('SELECT id FROM relacion_concepto_percepcion WHERE concepto_nomina = :concepto AND empresa_id = '.$idEmpresa);
}
else{
    $stmt = $conn->prepare('SELECT id FROM relacion_concepto_deduccion WHERE concepto_nomina = :concepto AND empresa_id = '.$idEmpresa);
}

$stmt->bindValue(':concepto', $nuevoConcepto);
$stmt->execute();
$existe_concepto = $stmt->rowCount();

if($existe_concepto > 0){ 
    echo "existe-concepto";
    return;
}

try {
    $conn->beginTransaction();

        if($tipo == 1){
            $stmt = $conn->prepare('INSERT INTO relacion_concepto_percepcion ( concepto_nomina, tipo_percepcion_id, empresa_id) VALUES( :concepto_nomina, :tipo_percepcion_id, :empresa_id)');
            $stmt->bindValue(':concepto_nomina', $nuevoConcepto);
            $stmt->bindValue(':tipo_percepcion_id', $claveSAT);
            $stmt->bindValue(':empresa_id', $idEmpresa);
            $stmt->execute();
            $idRelacionConcepto = $conn->lastInsertId();
        }
        if($tipo == 2){
            $stmt = $conn->prepare('INSERT INTO relacion_concepto_deduccion ( concepto_nomina, tipo_deduccion_id, empresa_id) VALUES( :concepto_nomina, :tipo_deduccion_id, :empresa_id)');
            $stmt->bindValue(':concepto_nomina', $nuevoConcepto);
            $stmt->bindValue(':tipo_deduccion_id', $claveSAT);
            $stmt->bindValue(':empresa_id', $idEmpresa);
            $stmt->execute();
            $idRelacionConcepto = $conn->lastInsertId();
        }
        if($tipo == 3){
            $stmt = $conn->prepare('INSERT INTO relacion_concepto_otros_pagos ( concepto_nomina, tipo_otros_pagos_id, empresa_id) VALUES( :concepto_nomina, :tipo_otros_pagos_id, :empresa_id)');
            $stmt->bindValue(':concepto_nomina', $nuevoConcepto);
            $stmt->bindValue(':tipo_otros_pagos_id', $claveSAT);
            $stmt->bindValue(':empresa_id', $idEmpresa);
            $stmt->execute();
            $idRelacionConcepto = $conn->lastInsertId();
        }

        if($disponibleClave == 1){

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
                $conn->rollBack(); 
                return;
            }

            if($tipo == 1){
                $stmt = $conn->prepare('SELECT id FROM relacion_tipo_percepcion WHERE tipo_percepcion_id = :concepto AND empresa_id = '.$idEmpresa);
            }
            if($tipo == 2){
                $stmt = $conn->prepare('SELECT id FROM relacion_tipo_deduccion WHERE tipo_deduccion_id = :concepto AND empresa_id = '.$idEmpresa);
            }

            $stmt->bindValue(':concepto', $claveSAT);
            $stmt->execute();
            $existe_concepto = $stmt->rowCount();
            
            if($existe_concepto > 0){ 
                echo "existe-concepto-clave";
                $conn->rollBack(); 
                return;
            }

            if($tipo == 1){
                $stmt = $conn->prepare('INSERT INTO relacion_tipo_percepcion ( clave, tipo_percepcion_id, empresa_id) VALUES( :clave, :tipo_percepcion_id, :empresa_id)');
                $stmt->bindValue(':clave', $clave);
                $stmt->bindValue(':tipo_percepcion_id', $claveSAT);
                $stmt->bindValue(':empresa_id', $idEmpresa);
            }
            if($tipo == 2){
                $stmt = $conn->prepare('INSERT INTO relacion_tipo_deduccion ( clave, tipo_deduccion_id, empresa_id) VALUES( :clave, :tipo_deduccion_id, :empresa_id)');
                $stmt->bindValue(':clave', $clave);
                $stmt->bindValue(':tipo_deduccion_id', $claveSAT);
                $stmt->bindValue(':empresa_id', $idEmpresa);
            }
            $stmt->execute();

        }

    if($conn->commit()){
      echo "exito";
      return;
    }else{
      $conn->rollBack(); 
      echo "fallo";
      return;
    }
    
} catch (PDOException $ex) {
    $conn->rollBack(); 
    echo "fallo"; 
    //echo $ex->getMessage();
}

?>
