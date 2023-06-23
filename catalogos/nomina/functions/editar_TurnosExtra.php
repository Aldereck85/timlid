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

date_default_timezone_set('America/Mexico_City');

$diasExtra = $_POST['diasExtra'];
$ImporteTurnos = $_POST['ImporteTurnos'];
$idDetalleNomina = $_POST['idDetalleNomina'];
$idNominaEmpleado = $_POST['idNominaEmpleado'];
$idEmpleado = $_POST['idEmpleado'];
$idNomina = $_POST['idNomina'];
$fechaPago = $_POST['fechaPago'];
$fecha = date("Y-m-d H:i:s");
$idUsuario = $_SESSION['PKUsuario'];
$idEmpresa = $_SESSION['IDEmpresa'];

$stmt = $conn->prepare('SELECT dle.Sueldo, pp.DiasPago FROM datos_laborales_empleado as dle  INNER JOIN periodo_pago as pp ON pp.PKPeriodo_pago = dle.FKPeriodo WHERE FKEmpleado = :idEmpleado');
$stmt->bindValue(':idEmpleado', $idEmpleado);
$stmt->execute();
$datosEmpleado = $stmt->fetch();

$stmt = $conn->prepare('SELECT tipo_concepto, dias FROM detalle_nomina_percepcion_empleado WHERE tipo_concepto = 4 AND empleado_id = :idEmpleado AND nomina_empleado_id = :idNomina AND id <> :idDetalleNomina
    UNION ALL 
    SELECT tipo_concepto, dias FROM detalle_nomina_deduccion_empleado WHERE tipo_concepto = 5 AND empleado_id = :idEmpleado2 AND nomina_empleado_id = :idNomina2');
$stmt->bindValue(':idEmpleado', $idEmpleado);
$stmt->bindValue(':idNomina', $idNominaEmpleado);
$stmt->bindValue(':idDetalleNomina', $idDetalleNomina);
$stmt->bindValue(':idEmpleado2', $idEmpleado);
$stmt->bindValue(':idNomina2', $idNominaEmpleado);
$stmt->execute();
$diasCon = $stmt->fetchAll();

if(count($diasCon) > 0){

    $diasSuma = 0;
    foreach ($diasCon as $dc) {
        
        if($dc['tipo_concepto'] == 4){
            $diasSuma = $diasSuma + $dc['dias'];
        }
        if($dc['tipo_concepto'] == 5){
            $diasSuma = $diasSuma - $dc['dias'];
        }

    }

    $diasSuma = $diasSuma + $diasExtra;

    if($datosEmpleado['DiasPago'] == 7 && $diasSuma > 3){
        echo "diaspaso";
        return;
    }
    if(($datosEmpleado['DiasPago'] == 14 || $datosEmpleado['DiasPago'] == 15) && $diasSuma > 6){
        echo "diaspaso";
        return;
    }
    if($datosEmpleado['DiasPago'] == 30 && $diasSuma > 12){
        echo "diaspaso";
        return;
    }

}
else{
    if($datosEmpleado['DiasPago'] == 7 && $diasExtra > 3){
            echo "diaspaso";
            return;
    }
    if(($datosEmpleado['DiasPago'] == 14 || $datosEmpleado['DiasPago'] == 15) && $diasExtra > 6){
        echo "diaspaso";
        return;
    }
    if($datosEmpleado['DiasPago'] == 30 && $diasExtra > 12){
        echo "diaspaso";
        return;
    }
}

try {
    
    $stmt = $conn->prepare('UPDATE detalle_nomina_percepcion_empleado SET dias = :dias, importe = :importe, fecha_edicion = :fecha_edicion, usuario_edicion = :usuario_edicion WHERE id = :idDetalleNomina');
    $stmt->bindValue(':dias', $diasExtra);
    $stmt->bindValue(':importe', $ImporteTurnos);
    $stmt->bindValue(':fecha_edicion', $fecha);
    $stmt->bindValue(':usuario_edicion', $idUsuario);
    $stmt->bindValue(':idDetalleNomina', $idDetalleNomina);

    if($stmt->execute()){

      $modo = 2;//para agregar o restar cantidades adicionales al total de percepciones 
      require_once("calculoImpuestos.php");

      echo "exito";
    }else{
      echo "fallo";
    }
    
    
} catch (PDOException $ex) {
    echo "fallo"; //$ex->getMessage();
}

?>
