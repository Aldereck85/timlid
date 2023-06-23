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

if(strcasecmp($concepto, "salario") == 0){
    echo "salario";
    return;
}

try {
    $conn->beginTransaction();

    $stmt = $conn->prepare('SELECT id FROM concepto_nomina WHERE concepto = :concepto AND  empresa_id = '.$idEmpresa);
    $stmt->bindValue(':concepto', $concepto);
    $stmt->execute();
    $existe = $stmt->rowCount();
    
    if($existe > 0){ 
        echo "existe";
    }
    else{
        $stmt = $conn->prepare('INSERT INTO concepto_nomina (concepto, tipo, empresa_id) VALUES(:concepto, :tipo, :empresa_id)');
        $stmt->bindValue(':concepto', $concepto);
        $stmt->bindValue(':tipo', $tipo);
        $stmt->bindValue(':empresa_id', $idEmpresa);
        $stmt->execute();

        $idLast = $conn->lastInsertId();

        if($tipo == 1){
            $stmt = $conn->prepare('INSERT INTO relacion_concepto_nomina_percepcion (tipo_percepcion_id, concepto_nomina_id) VALUES(:tipo_percepcion_id, :concepto_nomina_id)');
            $stmt->bindValue(':tipo_percepcion_id', $clave);
            $stmt->bindValue(':concepto_nomina_id', $idLast);
            $stmt->execute();
        }
        else{
            $stmt = $conn->prepare('INSERT INTO relacion_concepto_nomina_deduccion (tipo_deduccion_id, concepto_nomina_id) VALUES(:tipo_deduccion_id, :concepto_nomina_id)');
            $stmt->bindValue(':tipo_deduccion_id', $clave);
            $stmt->bindValue(':concepto_nomina_id', $idLast);
            $stmt->execute();
        }
        

        if($conn->commit()){
          echo "exito";
        }else{
          $conn->rollBack(); 
          echo "fallo";
        }
    }
    
    
} catch (PDOException $ex) {
    $conn->rollBack(); 
    echo "fallo"; //$ex->getMessage();
}

?>
