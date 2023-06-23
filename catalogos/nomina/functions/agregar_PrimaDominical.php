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

$primaDominical = $_POST['primaDominical'];
$idNominaEmpleado = $_POST['idNominaEmpleado'];
$idEmpleado = $_POST['idEmpleado'];
$idNomina = $_POST['idNomina'];
$tipo_concepto = 6;//prima dominical
$fecha = date("Y-m-d H:i:s");
$idUsuario = $_SESSION['PKUsuario'];
$idEmpresa = $_SESSION['IDEmpresa'];
$concepto = 15;
$agregarClavePrimaDominical = $_POST['agregarClavePrimaDominical'];
$clavePrimaDominicalUnica = $_POST['clavePrimaDominicalUnica'];
$fechaPago = $_POST['fechaPago'];

//prima dominical
if($agregarClavePrimaDominical == 1){
    $stmt = $conn->prepare('SELECT id FROM relacion_tipo_percepcion WHERE clave = :clave AND  empresa_id = '.$idEmpresa);
    $stmt->bindValue(':clave', $clavePrimaDominicalUnica);
    $stmt->execute();
    $existe1 = $stmt->rowCount();

    $stmt = $conn->prepare('SELECT id FROM relacion_tipo_deduccion WHERE clave = :clave AND  empresa_id = '.$idEmpresa);
    $stmt->bindValue(':clave', $clavePrimaDominicalUnica);
    $stmt->execute();
    $existe2 = $stmt->rowCount();

    if($existe1 > 0 || $existe2 > 0){ 
        echo "existe-clave-PrimaDominicalUnica";
        return;
    }
    else{

        $stmt = $conn->prepare('SELECT id FROM relacion_tipo_percepcion WHERE tipo_percepcion_id = :concepto AND empresa_id = '.$idEmpresa);
        $stmt->bindValue(':concepto', $concepto);
        $stmt->execute();
        $existe_concepto = $stmt->rowCount();
        
        if($existe_concepto > 0){ 
            echo "existe-concepto-PrimaDominicalUnica";
            return;
        }
        else{

            $stmt = $conn->prepare('INSERT INTO relacion_tipo_percepcion ( clave, tipo_percepcion_id, empresa_id) VALUES( :clave, :tipo_percepcion_id, :empresa_id)');
            $stmt->bindValue(':clave', $clavePrimaDominicalUnica);
            $stmt->bindValue(':tipo_percepcion_id', $concepto);
            $stmt->bindValue(':empresa_id', $idEmpresa);
            $stmt->execute();
        }
    }
}

//obtener o ingresar el concepto de prima dominical 
$stmt = $conn->prepare('SELECT id FROM relacion_concepto_percepcion WHERE tipo_percepcion_id = 15 AND  empresa_id = '.$idEmpresa);
$stmt->execute();
$existe = $stmt->rowCount();

if($existe > 0){
    $row_concepto = $stmt->fetch();
    $idConceptoPercepcion = $row_concepto['id'];
}
else{
    $stmt = $conn->prepare('INSERT INTO relacion_concepto_percepcion (concepto_nomina, tipo_percepcion_id, empresa_id) VALUES (:concepto_nomina, :tipo_percepcion_id, :empresa_id)');
    $stmt->bindValue(':concepto_nomina', 'Prima dominical');
    $stmt->bindValue(':tipo_percepcion_id', 15);
    $stmt->bindValue(':empresa_id', $idEmpresa);
    $stmt->execute();

    $idConceptoPercepcion = $conn->lastInsertId();
}



$stmt = $conn->prepare('SELECT dle.Sueldo, pp.DiasPago FROM datos_laborales_empleado as dle  INNER JOIN periodo_pago as pp ON pp.PKPeriodo_pago = dle.FKPeriodo WHERE FKEmpleado = :idEmpleado');
$stmt->bindValue(':idEmpleado', $idEmpleado);
$stmt->execute();
$datosEmpleado = $stmt->fetch();

$stmt = $conn->prepare('SELECT tipo_concepto FROM detalle_nomina_percepcion_empleado WHERE tipo_concepto = 6 AND empleado_id = :idEmpleado AND nomina_empleado_id = :idNomina ');
$stmt->bindValue(':idEmpleado', $idEmpleado);
$stmt->bindValue(':idNomina', $idNominaEmpleado);
$stmt->execute();
$diasCon = $stmt->fetchAll();
$cuentaDomingos = count($diasCon);

if($cuentaDomingos > 0){

    if($datosEmpleado['DiasPago'] == 7 && $cuentaDomingos > 0){
        echo "domingopaso";
        return;
    }
    if(($datosEmpleado['DiasPago'] == 14 || $datosEmpleado['DiasPago'] == 15) && $cuentaDomingos > 1){
        echo "domingopaso";
        return;
    }
    if($datosEmpleado['DiasPago'] == 30 && $cuentaDomingos > 4){
        echo "domingopaso";
        return;
    }
}

$stmt = $conn->prepare('SELECT cantidad FROM parametros WHERE descripcion = "UMA" ');
$stmt->execute();
$row_parametros = $stmt->fetch();
$UMA = $row_parametros['cantidad'];


 if($primaDominical > $UMA){
    $cantidadExenta = $UMA;
    $primaDominical_gravado = $primaDominical - $UMA;
}
else{
    $primaDominical_gravado = 0.00;
    $cantidadExenta = $primaDominical;
}

try {
    $stmt = $conn->prepare('INSERT INTO detalle_nomina_percepcion_empleado (relacion_tipo_percepcion_id, relacion_concepto_percepcion_id, tipo_concepto, dias, importe, importe_exento, exento, empleado_id, nomina_empleado_id, fecha_alta, fecha_edicion, usuario_alta, usuario_edicion) VALUES (:concepto, :relacion_concepto_percepcion_id, :tipo_concepto, :dias, :importe, :importe_exento, :exento, :empleado_id, :nomina_empleado_id,:fecha_alta,:fecha_edicion,:usuario_alta,:usuario_edicion)');
    $stmt->bindValue(':concepto', $concepto);
    $stmt->bindValue(':relacion_concepto_percepcion_id', $idConceptoPercepcion);
    $stmt->bindValue(':tipo_concepto', $tipo_concepto);
    $stmt->bindValue(':dias', 0);
    $stmt->bindValue(':importe', $primaDominical_gravado);
    $stmt->bindValue(':importe_exento', $cantidadExenta);
    $stmt->bindValue(':exento', 0);
    $stmt->bindValue(':empleado_id', $idEmpleado);
    $stmt->bindValue(':nomina_empleado_id', $idNominaEmpleado);
    $stmt->bindValue(':fecha_alta', $fecha);
    $stmt->bindValue(':fecha_edicion', $fecha);
    $stmt->bindValue(':usuario_alta', $idUsuario);
    $stmt->bindValue(':usuario_edicion', $idUsuario);

    if($stmt->execute()){

      $modo = 2;//para agregar o restar cantidades adicionales al total de percepciones 
      require_once("calculoImpuestos.php");

      echo "exito";
    }else{
      echo "fallo";
    }
    
    
} catch (PDOException $ex) {
    echo "fallo"; //echo $ex->getMessage();
}

?>
