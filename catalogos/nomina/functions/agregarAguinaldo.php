<?php
session_start();

$jwt_ruta = "../../../";
require_once '../../jwt.php';

date_default_timezone_set('America/Mexico_City');

error_reporting(E_ALL & ~E_WARNING);

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
$GLOBALS['rutaFuncion'] = "./../";
require_once("../../../functions/funcionNomina.php");

$idNominaEmpleado = $_POST['idNominaEmpleado'];
$idEmpleado = $_POST['idEmpleado'];
$concepto = 2; //corresponde al aguinaldo
$tipo_concepto = 9;  //AGUINALDO
$idUsuario = $_SESSION['PKUsuario'];
$fecha = date("Y-m-d H:i:s");
$idNomina = $_POST['idNomina'];
$disponibleClave = $_POST['disponibleClave'];
$clave = $_POST['clave'];
$periodo = $_POST['periodo'];
$tipoCalculoISR = $_POST['tipoCalculoISR'];
$idEmpresa = $_SESSION['IDEmpresa'];
$dias_aguinaldo = $_POST['diasAguinaldo'];
$fechaPago = $_POST['fechaPago'];
$fechaAnio = DateTime::createFromFormat("Y-m-d", $fechaPago);
$anioCalculo = $fechaAnio->format("Y");

$stmt = $conn->prepare('SELECT estatus FROM nomina WHERE id ='.$idNomina);
$stmt->execute();
$row = $stmt->fetch();
$estatus = $row['estatus'];

if($estatus == 2){
    echo "fallo-agregar";
    return;
}

$stmt = $conn->prepare('SELECT id FROM detalle_nomina_percepcion_empleado WHERE relacion_tipo_percepcion_id = :concepto AND empleado_id = :empleado_id AND nomina_empleado_id = :nomina_empleado_id');
$stmt->bindValue(':concepto', $concepto);
$stmt->bindValue(':empleado_id', $idEmpleado);
$stmt->bindValue(':nomina_empleado_id', $idNominaEmpleado);
$stmt->execute();
$existe = $stmt->rowCount();


if($existe > 0){ 
    echo "existe-concepto";
    return;
}

$stmt = $conn->prepare("SELECT FechaIngreso
                            FROM datos_laborales_empleado 
                            WHERE FKEmpleado = :id ");
$stmt->bindValue(':id',$idEmpleado);
$stmt->execute();
$rowD = $stmt->fetch();


$datosSalario = getSalario_Dias($idEmpleado);
$UMA = getUMA($anioCalculo);
$sueldoEmpleado = $datosSalario[0];
$sueldoDiario = $datosSalario[2];
$sueldoMensual = $datosSalario[5];
$fechaIngreso = $rowD['FechaIngreso'];
$dias_falta = 0;


/*Calculo desde la DB de los datos del finiquito*/
$fechaFinal = date("Y").'-12-31';

if($fechaIngreso > date("Y").'-01-01')
{
  $fechaInicial = $fechaIngreso;
}
else{
  $fechaInicial = date("Y").'-01-01';
}

$datetime1 = new DateTime($fechaInicial); // Fecha inicial
$datetime2 = new DateTime($fechaFinal); // Fecha actual
$interval = $datetime1->diff($datetime2);
$num_dias_trabajados = $interval->format('%a') + 1 - $dias_falta;

if(esBisiesto(date("Y"))){
  $dias_anio = 366;
}
else{
  $dias_anio = 365;
}
$porcentaje_proporcional = $dias_aguinaldo / $dias_anio;
$dias_trabajados_proporcional = number_format($porcentaje_proporcional * $num_dias_trabajados,2);

$aguinaldo = number_format($dias_trabajados_proporcional * $sueldoDiario,2,'.','');
$aporte_exento = number_format($UMA * 30 ,2,'.','');

if($aporte_exento > $aguinaldo){
    $aguinaldo_exento = $aguinaldo;
    $aguinaldo_gravado = 0.00;
}
else{
    $aguinaldo_exento = $aporte_exento;
    $aguinaldo_gravado = number_format($aguinaldo - $aguinaldo_exento ,2,'.','');
}

/*
echo " UMA ".$UMA;
echo " aguinaldo ".$aguinaldo;
echo " aguinaldo_exento ".$aguinaldo_exento;
echo " aguinaldo_gravado ".$aguinaldo_gravado;
*/

try {
    $conn->beginTransaction();

    if($disponibleClave == 1){

        $stmt = $conn->prepare('SELECT id FROM relacion_tipo_percepcion WHERE clave = :clave AND  empresa_id = '.$idEmpresa);
        $stmt->bindValue(':clave', $clave);
        $stmt->execute();
        $existe1 = $stmt->rowCount();
        
        if($existe1 > 0){ 
            echo "existe-clave";
            $conn->rollBack(); 
            return;
        }

        $stmt = $conn->prepare('SELECT id FROM relacion_tipo_percepcion WHERE tipo_percepcion_id = :concepto AND empresa_id = '.$idEmpresa);
        $stmt->bindValue(':concepto', $concepto);
        $stmt->execute();
        $existe_concepto = $stmt->rowCount();
        
        if($existe_concepto > 0){ 
            echo "existe-concepto-clave";
            $conn->rollBack(); 
            return;
        }

        $stmt = $conn->prepare('INSERT INTO relacion_tipo_percepcion ( clave, tipo_percepcion_id, empresa_id) VALUES( :clave, :tipo_percepcion_id, :empresa_id)');
        $stmt->bindValue(':clave', $clave);
        $stmt->bindValue(':tipo_percepcion_id', $concepto);
        $stmt->bindValue(':empresa_id', $idEmpresa);
        $stmt->execute();
    }

    //Busca que se haya agregado el concepto del aguinaldo
    $stmt = $conn->prepare('SELECT id FROM relacion_concepto_percepcion WHERE tipo_percepcion_id = 2 AND empresa_id = '.$idEmpresa);
    $stmt->execute();
    $existe_relacion_concepto = $stmt->rowCount();

    if($existe_relacion_concepto > 0){
        $relacion_con = $stmt->fetch();
        $relacion_concepto_percepcion_id = $relacion_con['id'];
    }
    else{
        $stmt = $conn->prepare('INSERT INTO relacion_concepto_percepcion (concepto_nomina, tipo_percepcion_id, empresa_id) VALUES (:concepto_nomina, :tipo_percepcion_id, :empresa_id)');
        $stmt->bindValue(':concepto_nomina', "Aguinaldo");
        $stmt->bindValue(':tipo_percepcion_id', 2);
        $stmt->bindValue(':empresa_id', $idEmpresa);
        $stmt->execute();

        $relacion_concepto_percepcion_id = $conn->lastInsertId();

    }


    $stmt = $conn->prepare('INSERT INTO detalle_nomina_percepcion_empleado (relacion_tipo_percepcion_id, relacion_concepto_percepcion_id, tipo_concepto, importe, importe_exento, exento, empleado_id, nomina_empleado_id, fecha_alta, fecha_edicion, usuario_alta, usuario_edicion) VALUES (:concepto, :relacion_concepto_percepcion_id, :tipo_concepto, :importe, :importe_exento, :exento, :empleado_id, :nomina_empleado_id,:fecha_alta,:fecha_edicion,:usuario_alta,:usuario_edicion)');
    $stmt->bindValue(':concepto', $concepto);
    $stmt->bindValue(':relacion_concepto_percepcion_id', $relacion_concepto_percepcion_id);
    $stmt->bindValue(':tipo_concepto', $tipo_concepto);
    $stmt->bindValue(':importe', $aguinaldo_gravado);
    $stmt->bindValue(':importe_exento', $aguinaldo_exento);
    $stmt->bindValue(':exento', 0);
    $stmt->bindValue(':empleado_id', $idEmpleado);
    $stmt->bindValue(':nomina_empleado_id', $idNominaEmpleado);
    $stmt->bindValue(':fecha_alta', $fecha);
    $stmt->bindValue(':fecha_edicion', $fecha);
    $stmt->bindValue(':usuario_alta', $idUsuario);
    $stmt->bindValue(':usuario_edicion', $idUsuario);
    $stmt->execute();

    $iddetallenominapercepcion = $conn->lastInsertId();

    $stmt = $conn->prepare('INSERT INTO tipo_calculo_aguinaldo (detalle_nomina_percepcion_empleado_id, tipo) VALUES (:detalle_nomina_percepcion_empleado_id, :tipo)');
    $stmt->bindValue(':detalle_nomina_percepcion_empleado_id', $iddetallenominapercepcion);
    $stmt->bindValue(':tipo', $tipoCalculoISR);
    $stmt->execute();

    if($conn->commit()){
            
        $datosISRAguinaldo = calculoISRAguinaldo($tipoCalculoISR, $sueldoMensual, $aguinaldo_gravado, $aguinaldo, $anioCalculo);
        $ISRAguinaldo = $datosISRAguinaldo[0];
        $AguinaldoPagar = $datosISRAguinaldo[1];                  


        $stmt = $conn->prepare('UPDATE nomina_empleado SET ISR = :ISR, cuotaIMSS = 0.00, SAE = 0.00, Total = :Total, TotalNeto = :TotalNeto WHERE PKNomina = :PKNomina');
        $stmt->bindValue(':ISR', $ISRAguinaldo);
        $stmt->bindValue(':Total', $aguinaldo);
        $stmt->bindValue(':TotalNeto', $AguinaldoPagar);
        $stmt->bindValue(':PKNomina', $idNominaEmpleado);
        $stmt->execute();

        //actualziación de totales de nomina.
        $stmt = $conn->prepare('SELECT SUM(TotalNeto) as total, COUNT(PKNomina) as num_empleados FROM nomina_empleado WHERE FKNomina = '. $idNomina);
        $stmt->execute();
        $row_cont = $stmt->fetch();
        $num_empleados = $row_cont['num_empleados'];
        $total = $row_cont['total'];

        $stmt = $conn->prepare('UPDATE nomina SET no_empleados = :no_empleados, total = :total WHERE id = :idNomina ');
        $stmt->bindValue(':total', $total);
        $stmt->bindValue(':no_empleados', $num_empleados);
        $stmt->bindValue(':idNomina', $idNomina);
        $stmt->execute();
        

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
   // echo $ex->getMessage();
}

?>
