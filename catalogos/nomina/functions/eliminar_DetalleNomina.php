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
$idNomina = $_POST['idNomina'];
$tipo = $_POST['tipo'];
$idEmpleado = $_POST['idEmpleado']; 
$fechaPago = $_POST['fechaPago'];


try {
    $conn->beginTransaction();

    $stmt = $conn->prepare('SELECT estatus FROM nomina WHERE id ='.$idNomina);
    $stmt->execute();
    $row = $stmt->fetch();
    $estatus = $row['estatus'];
    //$tipo_nomina = $row['tipo_nomina'];

    if($estatus == 2){
        echo "fallo-cancelacion";
        return;
    }

    //no se puede eliminar el concepto salario de nominas ordinarias
    /*if($tipo == 1){
        if($tipo_nomina == 1){
            $stmt = $conn->prepare('SELECT tipo_concepto FROM detalle_nomina_percepcion_empleado WHERE id = :idDetalleNomina');
            $stmt->bindValue(":idDetalleNomina", $idDetalleNomina);
            $stmt->execute();
            $row = $stmt->fetch();
            $tipo_concepto = $row['tipo_concepto'];

            if($tipo_concepto == 1){
                echo "fallo-salario";
                return;
            }
        }
    }*/

    if($tipo == 1){
        
        $stmt = $conn->prepare('SELECT tipo_concepto FROM detalle_nomina_percepcion_empleado WHERE id = :idDetalleNomina');
        $stmt->bindValue(":idDetalleNomina", $idDetalleNomina);
        $stmt->execute();
        $row = $stmt->fetch();
        $tipo_concepto = $row['tipo_concepto'];
            
        if($tipo_concepto == 10){
            $stmt = $conn->prepare('SELECT anio, diasrestados FROM vacaciones_revision WHERE empleado_id = :empleado_id AND detalle_nomina_percepcion_empleado_id = :detalle_nomina_percepcion');
            $stmt->bindValue(":empleado_id", $idEmpleado);
            $stmt->bindValue(":detalle_nomina_percepcion", $idDetalleNomina);
            $stmt->execute();
            $rowVac = $stmt->fetchAll();
            

            foreach ($rowVac as $rv) {

                $stmt = $conn->prepare('SELECT diasrestantes FROM vacaciones_agregadas WHERE empleado_id = :empleado_id AND anio = :anio');
                $stmt->bindValue(":empleado_id", $idEmpleado);
                $stmt->bindValue(":anio", $rv['anio']);
                $stmt->execute();
                $rowRev = $stmt->fetch();
                
                $diasDevolver = $rv['diasrestados'] + $rowRev['diasrestantes'];                

                $stmt = $conn->prepare('UPDATE vacaciones_agregadas SET diasrestantes = :diasrestantes  WHERE empleado_id = :empleado_id AND anio = :anio');
                $stmt->bindValue(":diasrestantes", $diasDevolver);
                $stmt->bindValue(":empleado_id", $idEmpleado);
                $stmt->bindValue(":anio", $rv['anio']);
                $stmt->execute();
                
            }

                //se eliminan las vacaciones agregadas
                $stmt = $conn->prepare('DELETE FROM vacaciones WHERE detalle_nomina_percepcion_empleado_id = :idDetalleNomina');
                $stmt->bindValue(':idDetalleNomina', $idDetalleNomina);
                $stmt->execute();

        }

        

        $stmt = $conn->prepare('DELETE FROM detalle_nomina_percepcion_empleado WHERE id = :idDetalleNomina');
    }

    //eliminar DEDUCCIONES
    if($tipo == 2 || $tipo == 12 || $tipo == 13 || $tipo == 14){
        $stmt = $conn->prepare('DELETE FROM detalle_nomina_deduccion_empleado WHERE id = :idDetalleNomina');
    }

    //eliminar PERCEPCIONES
    if($tipo == 3){
        $stmt = $conn->prepare('DELETE FROM detalle_otros_pagos_nomina_empleado WHERE id = :idDetalleNomina');
    }
    $stmt->bindValue(':idDetalleNomina', $idDetalleNomina);
    $stmt->execute();

    if($tipo == 12){
        $stmt = $conn->prepare('SELECT cfr.importe_aplicado, cf.monto_acumulado_retenido, cf.saldo, cf.id as credito_fonacot_id FROM credito_fonacot_registro as cfr INNER JOIN credito_fonacot as cf ON cf.id = cfr.credito_fonacot_id WHERE cfr.detalle_nomina_deduccion_empleado_id = :detalle_nomina_deduccion_empleado_id');
        $stmt->bindValue(":detalle_nomina_deduccion_empleado_id", $idDetalleNomina);
        $stmt->execute();
        $rowCF = $stmt->fetch();

        $importeAplicado = $rowCF['importe_aplicado'];
        $MontoAcumuladoOriginal = $rowCF['monto_acumulado_retenido'];
        $SaldoOriginal = $rowCF['saldo'];
        $idCreditoFonacot = $rowCF['credito_fonacot_id'];

        $stmt = $conn->prepare('UPDATE credito_fonacot_registro SET estatus = 2 WHERE detalle_nomina_deduccion_empleado_id = :detalle_nomina_deduccion_empleado_id');
        $stmt->bindValue(":detalle_nomina_deduccion_empleado_id", $idDetalleNomina);
        $stmt->execute();

        $MontoAcumuladoActual = $MontoAcumuladoOriginal - $importeAplicado;
        $saldoActual = $SaldoOriginal + $importeAplicado;

        $stmt = $conn->prepare('UPDATE credito_fonacot SET monto_acumulado_retenido = :monto_acumulado_retenido, saldo = :saldo WHERE id = :idCreditoFonacot ');
        $stmt->bindValue(':monto_acumulado_retenido', $MontoAcumuladoActual);
        $stmt->bindValue(':saldo', $saldoActual);
        $stmt->bindValue(':idCreditoFonacot', $idCreditoFonacot);
        $stmt->execute();
    }

    //infonavit
    if($tipo == 13){
      $stmt = $conn->prepare('SELECT cir.importe_aplicado, ci.importe_acumulado, ci.id as credito_infonavit_id, ci.veces_aplicadas FROM credito_infonavit_registro as cir INNER JOIN credito_infonavit as ci ON ci.id = cir.credito_infonavit_id WHERE cir.detalle_nomina_deduccion_empleado_id = :detalle_nomina_deduccion_empleado_id ');
      $stmt->bindValue(':detalle_nomina_deduccion_empleado_id', $idDetalleNomina);
      $stmt->execute();
      $rowCI = $stmt->fetch();

      $importeAplicadoOriginal = $rowCI['importe_aplicado'];
      $importeAcumuladoOriginal = $rowCI['importe_acumulado'];
      $idCreditoInfonavit = $rowCI['credito_infonavit_id'];
      $vecesAplicadasOriginal = $rowCI['veces_aplicadas'];

      $importeAcumuladoActual = $importeAcumuladoOriginal - $importeAplicadoOriginal;
      $vecesAplicadasActual = $vecesAplicadasOriginal - 1;

      $stmt = $conn->prepare('UPDATE credito_infonavit_registro SET estatus = 2 WHERE detalle_nomina_deduccion_empleado_id = :detalle_nomina_deduccion_empleado_id ');
      $stmt->bindValue(':detalle_nomina_deduccion_empleado_id', $idDetalleNomina);
      $stmt->execute();

      $stmt = $conn->prepare('UPDATE credito_infonavit SET importe_acumulado = :importe_acumulado, veces_aplicadas = :veces_aplicadas WHERE id = :idCreditoInfonavit ');
      $stmt->bindValue(':importe_acumulado', $importeAcumuladoActual);
      $stmt->bindValue(':veces_aplicadas', $vecesAplicadasActual);
      $stmt->bindValue(':idCreditoInfonavit', $idCreditoInfonavit);
      $stmt->execute();
    }

    //Pension alimenticia
    if($tipo == 14){

      $stmt = $conn->prepare('UPDATE pension_alimenticia_registro SET estatus = 2 WHERE detalle_nomina_deduccion_empleado_id = :detalle_nomina_deduccion_empleado_id ');
      $stmt->bindValue(':detalle_nomina_deduccion_empleado_id', $idDetalleNomina);
      $stmt->execute();

    }

    if($conn->commit()){

      $modo = 2;//calculo de vacaciones
      require_once("calculoImpuestos.php");

      echo "exito";

    }else{
      echo "fallo";
      $conn->rollBack();
    }
    
} catch (PDOException $ex) {
    echo "fallo"; //echo $ex->getMessage();
    $conn->rollBack();
}

?>
