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

$idDetalleNomina = $_POST['idDetalleNomina'];
$idNominaEmpleado = $_POST['idNominaEmpleado'];
$idEmpleado = $_POST['idEmpleado'];
$activo = $_POST['activo'];
$idNomina = $_POST['idNomina'];
$fechaPago = $_POST['fechaPago'];

try {

    $stmt = $conn->prepare('SELECT importe, importe_exento, relacion_tipo_percepcion_id FROM detalle_nomina_percepcion_empleado WHERE empleado_id = :empleado_id AND id = :idDetalleNomina ');
    $stmt->bindValue(':empleado_id', $idEmpleado);
    $stmt->bindValue(':idDetalleNomina', $idDetalleNomina);
    $stmt->execute();
    $detalle_nomina = $stmt->fetch();

    if($detalle_nomina['relacion_tipo_percepcion_id'] == 41){
        echo "fallo_viatico";
        return;
    }

    if($activo == 0){

        $importe = $detalle_nomina['importe_exento'];
        $importe_exento = 0.00;
       
    }
    elseif($activo == 1){

        $importe = 0.00;
        $importe_exento = $detalle_nomina['importe'];

    }

    $stmt = $conn->prepare('UPDATE detalle_nomina_percepcion_empleado SET exento = :activo, importe = :importe, importe_exento = :importe_exento WHERE empleado_id = :empleado_id AND id = :idDetalleNomina ');
    $stmt->bindValue(':activo', $activo);
    $stmt->bindValue(':importe', $importe);
    $stmt->bindValue(':importe_exento', $importe_exento);
    $stmt->bindValue(':empleado_id', $idEmpleado);
    $stmt->bindValue(':idDetalleNomina', $idDetalleNomina);
    

    if($stmt->execute()){

      $modo = 2;//calculo de vacaciones
      require_once("calculoImpuestos.php");

      echo "exito";
    }else{
      echo "fallo";
    }
    
} catch (PDOException $ex) {
    echo "fallo"; //echo $ex->getMessage();
}

?>
