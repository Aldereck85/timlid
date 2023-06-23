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

$idConcepto = $_POST['idConcepto'];
$tipo = $_POST['tipoCon'];


try {
    if($tipo == 1){
        $stmt = $conn->prepare('SELECT rtp.id FROM relacion_tipo_percepcion as rtp INNER JOIN detalle_nomina_percepcion_empleado as dnpe ON dnpe.relacion_tipo_percepcion_id = rtp.tipo_percepcion_id WHERE rtp.id = :idConcepto AND rtp.empresa_id = '.$_SESSION['IDEmpresa']);
        $stmt->bindValue(':idConcepto', $idConcepto);
        $stmt->execute();
        $existe = $stmt->rowCount();

        if($existe > 0){
            echo "fallo-existe";
            return;
        }

        $stmt = $conn->prepare('DELETE FROM relacion_tipo_percepcion WHERE id = :idConcepto');
        $stmt->bindValue(':idConcepto', $idConcepto);
    }
    else{
        $stmt = $conn->prepare('SELECT rtd.id FROM relacion_tipo_deduccion as rtd INNER JOIN tipo_deduccion as td ON td.id = rtd.tipo_deduccion_id WHERE rtd.id = :idConcepto AND td.id = 1  AND rtd.empresa_id = '.$_SESSION['IDEmpresa']);
        $stmt->bindValue(':idConcepto', $idConcepto);
        $stmt->execute();
        $existeIMSS = $stmt->rowCount();

        if($existeIMSS > 0){
            echo "fallo-IMSS";
            return;
        }

        $stmt = $conn->prepare('SELECT rtd.id FROM relacion_tipo_deduccion as rtd INNER JOIN tipo_deduccion as td ON td.id = rtd.tipo_deduccion_id WHERE rtd.id = :idConcepto AND td.id = 2 AND rtd.empresa_id = '.$_SESSION['IDEmpresa']);
        $stmt->bindValue(':idConcepto', $idConcepto);
        $stmt->execute();
        $existeISR = $stmt->rowCount();

        if($existeISR > 0){
            echo "fallo-ISR";
            return;
        }
        

        $stmt = $conn->prepare('SELECT rtd.id FROM relacion_tipo_deduccion as rtd INNER JOIN detalle_nomina_deduccion_empleado as dnde ON dnde.relacion_tipo_deduccion_id = rtd.tipo_deduccion_id WHERE rtd.id = :idConcepto AND rtd.empresa_id = '.$_SESSION['IDEmpresa']);
        $stmt->bindValue(':idConcepto', $idConcepto);
        $stmt->execute();
        $existe = $stmt->rowCount();

        if($existe > 0){
            echo "fallo-existe";
            return;
        }

        $stmt = $conn->prepare('DELETE FROM relacion_tipo_deduccion WHERE id = :idConcepto');
        $stmt->bindValue(':idConcepto', $idConcepto);
    }

    if($stmt->execute()){
      echo "exito";
    }else{
      echo "fallo";
    }
    
} catch (PDOException $ex) {
    echo "fallo"; //$ex->getMessage();
}

?>