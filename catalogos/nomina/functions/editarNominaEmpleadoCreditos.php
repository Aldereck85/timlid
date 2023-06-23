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
$importe = $_POST['importe'];
$idUsuario = $_SESSION['PKUsuario'];
$fecha = date("Y-m-d H:i:s");
$idNominaEmpleado = $_POST['idNominaEmpleado'];  
$idNomina = $_POST['idNomina'];  
$idEmpleado = $_POST['idEmpleado']; 
$fechaPago = $_POST['fechaPago'];
$tipoCredito = $_POST['tipoCredito'];

$stmt = $conn->prepare('SELECT estatus FROM nomina WHERE id ='.$idNomina);
$stmt->execute();
$row = $stmt->fetch();
$estatus = $row['estatus'];

if($estatus == 2){
    echo "fallo-edicion";
    return;
}

try {
      
    $conn->beginTransaction();

    //fonacot
    if($tipoCredito == 12){
      $stmt = $conn->prepare('SELECT cfr.importe_aplicado, cf.monto_acumulado_retenido, cf.saldo, cf.id as credito_fonacot_id FROM credito_fonacot_registro as cfr INNER JOIN credito_fonacot as cf ON cf.id = cfr.credito_fonacot_id WHERE cfr.detalle_nomina_deduccion_empleado_id = :detalle_nomina_deduccion_empleado_id ');
      $stmt->bindValue(':detalle_nomina_deduccion_empleado_id', $idDetalleNomina);
      $stmt->execute();
      $rowCF = $stmt->fetch();

      $importeAplicadoOriginal = $rowCF['importe_aplicado'];
      $MontoAcumuladoRetOriginal = $rowCF['monto_acumulado_retenido'];
      $SaldoOriginal = $rowCF['saldo'];
      $idCreditoFonacot = $rowCF['credito_fonacot_id'];

      $saldoActual = $SaldoOriginal + $importeAplicadoOriginal - $importe;
      $estado = 1;

      if($saldoActual < 0){
        echo "pasa_credito";
        return;
      }

      if($saldoActual == 0){
        $estado = 3;
      }

      $MontoAcumuladoRetActual = $MontoAcumuladoRetOriginal - $importeAplicadoOriginal + $importe;

      $stmt = $conn->prepare('UPDATE credito_fonacot_registro SET importe_aplicado = :importe_aplicado WHERE detalle_nomina_deduccion_empleado_id = :detalle_nomina_deduccion_empleado_id ');
      $stmt->bindValue(':importe_aplicado', $importe);
      $stmt->bindValue(':detalle_nomina_deduccion_empleado_id', $idDetalleNomina);
      $stmt->execute();

      $stmt = $conn->prepare('UPDATE credito_fonacot SET monto_acumulado_retenido = :monto_acumulado_retenido, saldo = :saldo, estado = :estado WHERE id = :idCreditoFonacot ');
      $stmt->bindValue(':estado', $estado);
      $stmt->bindValue(':monto_acumulado_retenido', $MontoAcumuladoRetActual);
      $stmt->bindValue(':saldo', $saldoActual);
      $stmt->bindValue(':idCreditoFonacot', $idCreditoFonacot);
      $stmt->execute();
    }

    //infonavit
    if($tipoCredito == 13){
      $stmt = $conn->prepare('SELECT cir.importe_aplicado, ci.importe_acumulado, ci.id as credito_infonavit_id FROM credito_infonavit_registro as cir INNER JOIN credito_infonavit as ci ON ci.id = cir.credito_infonavit_id WHERE cir.detalle_nomina_deduccion_empleado_id = :detalle_nomina_deduccion_empleado_id ');
      $stmt->bindValue(':detalle_nomina_deduccion_empleado_id', $idDetalleNomina);
      $stmt->execute();
      $rowCI = $stmt->fetch();

      $importeAplicadoOriginal = $rowCI['importe_aplicado'];
      $importeAcumuladoOriginal = $rowCI['importe_acumulado'];
      $idCreditoInfonavit = $rowCI['credito_infonavit_id'];

      $importeAcumuladoActual = $importeAcumuladoOriginal - $importeAplicadoOriginal + $importe;

      $stmt = $conn->prepare('UPDATE credito_infonavit_registro SET importe_aplicado = :importe_aplicado WHERE detalle_nomina_deduccion_empleado_id = :detalle_nomina_deduccion_empleado_id ');
      $stmt->bindValue(':importe_aplicado', $importe);
      $stmt->bindValue(':detalle_nomina_deduccion_empleado_id', $idDetalleNomina);
      $stmt->execute();

      $stmt = $conn->prepare('UPDATE credito_infonavit SET importe_acumulado = :importe_acumulado WHERE id = :idCreditoInfonavit ');
      $stmt->bindValue(':importe_acumulado', $importeAcumuladoActual);
      $stmt->bindValue(':idCreditoInfonavit', $idCreditoInfonavit);
      $stmt->execute();
    }

    //Pensión alimenticia
    if($tipoCredito == 14){

      $stmt = $conn->prepare('UPDATE pension_alimenticia_registro SET importe_aplicado = :importe_aplicado WHERE detalle_nomina_deduccion_empleado_id = :detalle_nomina_deduccion_empleado_id ');
      $stmt->bindValue(':importe_aplicado', $importe);
      $stmt->bindValue(':detalle_nomina_deduccion_empleado_id', $idDetalleNomina);
      $stmt->execute();
    }
    
    $stmt = $conn->prepare('UPDATE detalle_nomina_deduccion_empleado SET importe = :importe, fecha_edicion = :fecha_edicion, usuario_edicion = :usuario_edicion WHERE id = :iddetallenomina ');
    $stmt->bindValue(':importe', $importe);
    $stmt->bindValue(':fecha_edicion', $fecha);
    $stmt->bindValue(':usuario_edicion', $idUsuario);
    $stmt->bindValue(':iddetallenomina', $idDetalleNomina);
    $stmt->execute();

    if($conn->commit()){

      $modo = 2;//calculo de vacaciones
      require_once("calculoImpuestos.php");


      echo "exito";
    }else{
      echo "fallo";
      $conn->rollBack();
    }
    
} catch (PDOException $ex) {
    echo "fallo"; 
    $conn->rollBack();
    //echo $ex->getMessage();
}

?>
