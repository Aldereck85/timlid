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

$idEmpleado = $_POST['idEmpleado'];
$clave = $_POST['clave'];
$concepto = $_POST['concepto'];
$fechaAplicacion = $_POST['fechaAplicacion'];
$numCredito = $_POST['numCredito'];
$ImporteFonacot = $_POST['ImporteFonacot'];
$ImporteFijoFonacot = $_POST['ImporteFijoFonacot'];
$PagoOtrosPatrones = $_POST['PagoOtrosPatrones'];
$existeConceptoFonacot = $_POST['existeConceptoFonacot'];
$existeClaveFonacot = $_POST['existeClaveFonacot'];
$tipoCalculo = $_POST['tipoCalculo'];    
$fechaPago = $_POST['fechaPago'];                   

$idNominaEmpleado = $_POST['idNominaEmpleado'];
$idNomina = $_POST['idNomina'];
$idUsuario = $_SESSION['PKUsuario'];
$idEmpresa = $_SESSION['IDEmpresa'];

if($PagoOtrosPatrones == null || $PagoOtrosPatrones == ""){
    $PagoOtrosPatrones = 0.00;
}

if($existeClaveFonacot == 1){
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
        return;
    }
    else{

        $stmt = $conn->prepare('SELECT id FROM relacion_tipo_deduccion WHERE tipo_deduccion_id = :concepto AND empresa_id = '.$idEmpresa);
        $stmt->bindValue(':concepto', 11);
        $stmt->execute();
        $existe_concepto = $stmt->rowCount();
        
        if($existe_concepto > 0){ 
            echo "existe-concepto";
            return;
        }
        else{

            $stmt = $conn->prepare('INSERT INTO relacion_tipo_deduccion ( clave, tipo_deduccion_id, empresa_id) VALUES( :clave, :tipo_deduccion_id, :empresa_id)');
            $stmt->bindValue(':clave', $clave);
            $stmt->bindValue(':tipo_deduccion_id', 11);
            $stmt->bindValue(':empresa_id', $idEmpresa);
            $stmt->execute();
        }
    }
}

if($existeConceptoFonacot == 1){
    $stmt = $conn->prepare('SELECT id FROM relacion_concepto_deduccion WHERE tipo_deduccion_id = 11 AND  empresa_id = '.$idEmpresa);
    $stmt->execute();
    $existe = $stmt->rowCount();

    if($existe > 0){
        $row_concepto = $stmt->fetch();
        $idConceptoDeduccion = $row_concepto['id'];
    }
    else{
        $stmt = $conn->prepare('INSERT INTO relacion_concepto_deduccion (concepto_nomina, tipo_deduccion_id, empresa_id) VALUES (:concepto_nomina, :tipo_deduccion_id, :empresa_id)');
        $stmt->bindValue(':concepto_nomina', $concepto);
        $stmt->bindValue(':tipo_deduccion_id', 11);
        $stmt->bindValue(':empresa_id', $idEmpresa);
        $stmt->execute();

        $idConceptoDeduccion = $conn->lastInsertId();
    }
}
else{
    $stmt = $conn->prepare('SELECT id FROM relacion_concepto_deduccion WHERE tipo_deduccion_id = 11 AND  empresa_id = '.$idEmpresa);
    $stmt->execute();
    $row_concepto = $stmt->fetch();
    $idConceptoDeduccion = $row_concepto['id'];
}


$tipo_concepto = 12;    
$fecha = date("Y-m-d H:i:s");

$stmt = $conn->prepare('SELECT fecha_inicio, fecha_fin FROM nomina WHERE id = :idNomina AND empresa_id = :idEmpresa');
$stmt->bindValue(':idNomina', $idNomina);  
$stmt->bindValue(':idEmpresa', $idEmpresa);
$stmt->execute();
$rowDatosNominaEsp = $stmt->fetch();
$fechaInicioNomina = $rowDatosNominaEsp['fecha_inicio'];
$fechaFinNomina = $rowDatosNominaEsp['fecha_fin'];

$fechaAplicacionTiempo = strtotime($fechaAplicacion);
$fechaInicioNominaTiempo = strtotime($fechaInicioNomina);
$fechaFinNominaTiempo = strtotime($fechaFinNomina);

$aplicarCreditoFonacot = 0;

//echo $fechaInicioNomina." -- ".$fechaAplicacion." -- ".$fechaFinNomina;
/*2022-05-14 -- 2022-07-06 -- 2022-05-20
2022-05-14 -- 2022-05-15 -- 2022-05-20*/
if($fechaInicioNominaTiempo <= $fechaAplicacionTiempo && $fechaFinNominaTiempo >= $fechaAplicacionTiempo){
    $aplicarCreditoFonacot = 1;
}

    try {
        $conn->beginTransaction();

        if($aplicarCreditoFonacot == 1){
            $saldo = floatval($ImporteFonacot) - floatval($PagoOtrosPatrones) ;
            $montoAcumuladoRetenido = floatval($PagoOtrosPatrones) + floatval($ImporteFijoFonacot);

            if($ImporteFijoFonacot >= $saldo){
                $importeAplicar = $saldo;
                $estado = 3;
                $en_aplicacion = 2;
            }
            else{
                $importeAplicar = $ImporteFijoFonacot;
                $estado = 1;
                $en_aplicacion = 1;
            }
            
            $saldo = floatval($ImporteFonacot) - floatval($PagoOtrosPatrones) - floatval($importeAplicar);
        }
        else{
            $estado = 1;
            $saldo = floatval($ImporteFonacot) - floatval($PagoOtrosPatrones);
            $montoAcumuladoRetenido = floatval($PagoOtrosPatrones);
            $en_aplicacion = 0;
        }

        $stmt = $conn->prepare('INSERT INTO credito_fonacot (empleado_id, relacion_concepto_deduccion_id,  numero_credito, fecha_aplicacion, tipo_importe, importe_total, importe_periodo, pagos_otros_patrones, monto_acumulado_retenido, saldo, estado, fecha_alta, fecha_cancelacion, usuario_alta, usuario_cancelacion, empresa_id, en_aplicacion) VALUES (:empleado_id, :relacion_concepto_deduccion_id, :numero_credito, :fecha_aplicacion, :tipo_importe, :importe_total, :importe_periodo, :pagos_otros_patrones, :monto_acumulado_retenido, :saldo, :estado, :fecha_alta, :fecha_cancelacion, :usuario_alta, :usuario_cancelacion, :empresa_id, :en_aplicacion)');
        $stmt->bindValue(':empleado_id', $idEmpleado);
        $stmt->bindValue(':relacion_concepto_deduccion_id', $idConceptoDeduccion);
        $stmt->bindValue(':numero_credito', $numCredito);
        $stmt->bindValue(':fecha_aplicacion', $fechaAplicacion);
        $stmt->bindValue(':tipo_importe', $tipoCalculo);
        $stmt->bindValue(':importe_total', $ImporteFonacot);
        $stmt->bindValue(':importe_periodo', $ImporteFijoFonacot);
        $stmt->bindValue(':pagos_otros_patrones', $PagoOtrosPatrones);
        $stmt->bindValue(':monto_acumulado_retenido', $montoAcumuladoRetenido);
        $stmt->bindValue(':saldo', $saldo);
        $stmt->bindValue(':estado', $estado);
        $stmt->bindValue(':fecha_alta', $fecha);
        $stmt->bindValue(':fecha_cancelacion', "0000-00-00");
        $stmt->bindValue(':usuario_alta', $idUsuario);
        $stmt->bindValue(':usuario_cancelacion', $idUsuario);
        $stmt->bindValue(':empresa_id', $idEmpresa);
        $stmt->bindValue(':en_aplicacion', $en_aplicacion);
        $stmt->execute();

        $idCreditoFonacot = $conn->lastInsertId();

//echo $aplicarCreditoFonacot." -- ";
        if($aplicarCreditoFonacot == 1){
            $stmt = $conn->prepare('INSERT INTO detalle_nomina_deduccion_empleado (relacion_tipo_deduccion_id, relacion_concepto_deduccion_id, tipo_concepto, horas, importe, importe_exento, exento, empleado_id, nomina_empleado_id, fecha_alta, fecha_edicion, usuario_alta, usuario_edicion) VALUES (:concepto, :relacion_concepto_percepcion_id, :tipo_concepto, :horas, :importe, :importe_exento, :exento, :empleado_id, :nomina_empleado_id,:fecha_alta,:fecha_edicion,:usuario_alta,:usuario_edicion)');
            $stmt->bindValue(':concepto', 11);
            $stmt->bindValue(':relacion_concepto_percepcion_id', $idConceptoDeduccion);
            $stmt->bindValue(':tipo_concepto', $tipo_concepto);
            $stmt->bindValue(':horas', 0);
            $stmt->bindValue(':importe', $importeAplicar);
            $stmt->bindValue(':importe_exento', 0);
            $stmt->bindValue(':exento', 0);
            $stmt->bindValue(':empleado_id', $idEmpleado);
            $stmt->bindValue(':nomina_empleado_id', $idNominaEmpleado);
            $stmt->bindValue(':fecha_alta', $fecha);
            $stmt->bindValue(':fecha_edicion', $fecha);
            $stmt->bindValue(':usuario_alta', $idUsuario);
            $stmt->bindValue(':usuario_edicion', $idUsuario);
            $stmt->execute();

            $idDetalleNominaDeduccion = $conn->lastInsertId();

            $stmt = $conn->prepare('INSERT INTO credito_fonacot_registro (credito_fonacot_id, nomina_empleado_id, detalle_nomina_deduccion_empleado_id, importe_aplicado, usuario_alta, fecha_alta, estatus) VALUES (:credito_fonacot_id, :nomina_empleado_id, :detalle_nomina_deduccion_empleado_id, :importe_aplicado, :usuario_alta, :fecha_alta, :estatus)');
            $stmt->bindValue(':credito_fonacot_id', $idCreditoFonacot);
            $stmt->bindValue(':nomina_empleado_id', $idNominaEmpleado);
            $stmt->bindValue(':detalle_nomina_deduccion_empleado_id', $idDetalleNominaDeduccion);
            $stmt->bindValue(':importe_aplicado', $importeAplicar);
            $stmt->bindValue(':usuario_alta', $idUsuario);
            $stmt->bindValue(':fecha_alta', $fecha);
            $stmt->bindValue(':estatus', 1);
            $stmt->execute();
        }

        if($conn->commit()){
          
          $modo = 2;//para agregar o restar cantidades adicionales al total de percepciones 
          require_once("calculoImpuestos.php");

          echo "exito";
        }else{
          echo "fallo";
          $conn->rollBack();
        }
        
        
    } catch (PDOException $ex) {
        echo "fallo"; echo $ex->getMessage();
        $conn->rollBack();
    }
?>
