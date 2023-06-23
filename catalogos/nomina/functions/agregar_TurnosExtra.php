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
$idNominaEmpleado = $_POST['idNominaEmpleado'];
$idEmpleado = $_POST['idEmpleado'];
$idNomina = $_POST['idNomina'];
$tipo_concepto = 4;// tipo 4 es para dias extras
$fecha = date("Y-m-d H:i:s");
$idUsuario = $_SESSION['PKUsuario'];
$idEmpresa = $_SESSION['IDEmpresa'];
$claveHorasExtra = $_POST['claveHorasExtra'];
$agregarClaveTurnoExtra = $_POST['agregarClaveTurnoExtra'];
$fechaPago = $_POST['fechaPago'];
$existeConcepto = $_POST['existeconcepto'];
$nuevoConcepto = $_POST['nuevoconcepto'];


//Otros ingresos por salario son turnos extras
if($agregarClaveTurnoExtra == 1){
    $stmt = $conn->prepare('SELECT id FROM relacion_tipo_percepcion WHERE clave = :clave AND  empresa_id = '.$idEmpresa);
    $stmt->bindValue(':clave', $claveHorasExtra);
    $stmt->execute();
    $existe1 = $stmt->rowCount();

    $stmt = $conn->prepare('SELECT id FROM relacion_tipo_deduccion WHERE clave = :clave AND  empresa_id = '.$idEmpresa);
    $stmt->bindValue(':clave', $claveHorasExtra);
    $stmt->execute();
    $existe2 = $stmt->rowCount();

    if($existe1 > 0 || $existe2 > 0){ 
        echo "existe-clave-OtrosIngresos";
        return;
    }
    else{

        $stmt = $conn->prepare('SELECT id FROM relacion_tipo_percepcion WHERE tipo_percepcion_id = :concepto AND empresa_id = '.$idEmpresa);
        $stmt->bindValue(':concepto', 33);
        $stmt->execute();
        $existe_concepto = $stmt->rowCount();
        
        if($existe_concepto > 0){ 
            echo "existe-concepto-OtrosIngresos";
            return;
        }
        else{

            $stmt = $conn->prepare('INSERT INTO relacion_tipo_percepcion ( clave, tipo_percepcion_id, empresa_id) VALUES( :clave, :tipo_percepcion_id, :empresa_id)');
            $stmt->bindValue(':clave', $claveHorasExtra);
            $stmt->bindValue(':tipo_percepcion_id', 33);
            $stmt->bindValue(':empresa_id', $idEmpresa);
            $stmt->execute();
        }
    }
}

if($existeConcepto == 1){
    $stmt = $conn->prepare('SELECT id FROM relacion_concepto_percepcion WHERE tipo_percepcion_id = 33 AND  empresa_id = '.$idEmpresa);
    $stmt->execute();
    $existe = $stmt->rowCount();

    if($existe > 0){
        $row_concepto = $stmt->fetch();
        $idConceptoPercepcion = $row_concepto['id'];
    }
    else{
        $stmt = $conn->prepare('INSERT INTO relacion_concepto_percepcion (concepto_nomina, tipo_percepcion_id, empresa_id) VALUES (:concepto_nomina, :tipo_percepcion_id, :empresa_id)');
        $stmt->bindValue(':concepto_nomina', $nuevoConcepto);
        $stmt->bindValue(':tipo_percepcion_id', 33);
        $stmt->bindValue(':empresa_id', $idEmpresa);
        $stmt->execute();

        $idConceptoPercepcion = $conn->lastInsertId();
    }
}
else{
    $stmt = $conn->prepare('SELECT id FROM relacion_concepto_percepcion WHERE tipo_percepcion_id = 33 AND  empresa_id = '.$idEmpresa);
    $stmt->execute();
    $row_concepto = $stmt->fetch();
    $idConceptoPercepcion = $row_concepto['id'];
}

$stmt = $conn->prepare('SELECT dle.Sueldo, pp.DiasPago FROM datos_laborales_empleado as dle  INNER JOIN periodo_pago as pp ON pp.PKPeriodo_pago = dle.FKPeriodo WHERE FKEmpleado = :idEmpleado');
$stmt->bindValue(':idEmpleado', $idEmpleado);
$stmt->execute();
$datosEmpleado = $stmt->fetch();

$stmt = $conn->prepare('SELECT tipo_concepto, dias FROM detalle_nomina_percepcion_empleado WHERE tipo_concepto = 4 AND empleado_id = :idEmpleado AND nomina_empleado_id = :idNomina 
    UNION ALL 
    SELECT tipo_concepto, dias FROM detalle_nomina_deduccion_empleado WHERE tipo_concepto = 5 AND empleado_id = :idEmpleado2 AND nomina_empleado_id = :idNomina2');
$stmt->bindValue(':idEmpleado', $idEmpleado);
$stmt->bindValue(':idNomina', $idNominaEmpleado);
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
    
    $stmt = $conn->prepare('INSERT INTO detalle_nomina_percepcion_empleado (relacion_tipo_percepcion_id, relacion_concepto_percepcion_id, tipo_concepto, dias, importe, importe_exento, exento, empleado_id, nomina_empleado_id, fecha_alta, fecha_edicion, usuario_alta, usuario_edicion) VALUES (:concepto, :relacion_concepto_percepcion_id, :tipo_concepto, :dias, :importe, :importe_exento, :exento, :empleado_id, :nomina_empleado_id,:fecha_alta,:fecha_edicion,:usuario_alta,:usuario_edicion)');
    $stmt->bindValue(':concepto', 33);//otros ingresos por salario
    $stmt->bindValue(':relacion_concepto_percepcion_id', $idConceptoPercepcion);
    $stmt->bindValue(':tipo_concepto', $tipo_concepto);
    $stmt->bindValue(':dias', $diasExtra);
    $stmt->bindValue(':importe', $ImporteTurnos);
    $stmt->bindValue(':importe_exento', 0.00);//gravan al 100% turnos extras
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
    echo "fallo"; //$ex->getMessage();
}

?>
