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

$idNomina = $_POST['idNomina'];
$fechaPago = $_POST['fechaPago'];
$usuario = $_SESSION['PKUsuario'];
$fecha = date("Y-m-d H:i:s");

try {

    $stmt = $conn->prepare('SELECT n.fecha_fin, np.sucursal_id, np.periodo_id FROM nomina as n INNER JOIN nomina_principal as np ON np.id = n.fk_nomina_principal WHERE n.id = :idNomina ');
    $stmt->bindValue(':idNomina', $idNomina);
    $stmt->execute();
    $datosNomina = $stmt->fetch();

    $fechaFin = $datosNomina['fecha_fin'];
    $idSucursal = $datosNomina['sucursal_id'];
    $idPeriodo = $datosNomina['periodo_id'];

    $stmt = $conn->prepare('SELECT e.PKEmpleado FROM empleados as e INNER JOIN datos_laborales_empleado as dle ON dle.FKEmpleado = e.PKEmpleado AND dle.FechaIngreso <= :fecha_fin  WHERE e.estatus = 1 AND dle.FKSucursal = :idsucursal AND dle.FKPeriodo = :idperiodo AND e.empresa_id = '.$_SESSION['IDEmpresa']);
    $stmt->bindValue(":fecha_fin", $fechaFin);
    $stmt->bindValue(":idsucursal", $idSucursal);
    $stmt->bindValue(":idperiodo", $idPeriodo);
    $stmt->execute();
    $num_empleados = $stmt->rowCount();


    $stmt = $conn->prepare('UPDATE nomina SET no_empleados = :no_empleados, fecha_pago = :fecha_pago, usuario_edicion_id = :usuario_edicion_id , updated_at = :updated_at WHERE id = :idNomina ');
    $stmt->bindValue(':no_empleados', $num_empleados);
    $stmt->bindValue(':fecha_pago', $fechaPago);
    $stmt->bindValue(':usuario_edicion_id', $usuario);
    $stmt->bindValue(':updated_at', $fecha);
    $stmt->bindValue(':idNomina', $idNomina);

    if($stmt->execute()){
      echo "exito";
    }else{
      echo "fallo";
    }
    
} catch (PDOException $ex) {
    echo "fallo"; //$ex->getMessage();
}

?>
