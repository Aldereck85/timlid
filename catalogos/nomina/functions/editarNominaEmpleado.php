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
$tipo_concepto = $_POST['tipo_concepto'];
$fechaPago = $_POST['fechaPago'];
$tipo = $_POST['tipo'];

if($tipo_concepto == 2){
  $exento = $_POST['exento'];

  if($tipo == 2){
    $exento = 0;
  }
}

$stmt = $conn->prepare('SELECT estatus FROM nomina WHERE id ='.$idNomina);
$stmt->execute();
$row = $stmt->fetch();
$estatus = $row['estatus'];

if($estatus == 2){
    echo "fallo-edicion";
    return;
}

try {
    //salario
    if($tipo_concepto == 1 || $tipo_concepto == 6 || $tipo_concepto == 11){
      
      $importe_gravado = $importe;
      $importe_exento = 0.00;
      
      $stmt = $conn->prepare('UPDATE detalle_nomina_percepcion_empleado SET importe = :importe, importe_exento = :importe_exento, fecha_edicion = :fecha_edicion, usuario_edicion = :usuario_edicion WHERE id = :iddetallenomina ');
    }
    //percepciones deducciones
    if($tipo_concepto == 2){

      if($exento == 1){
          $importe_gravado = 0.00;
          $importe_exento = $importe;
          
      }
      else{
          $importe_gravado = $importe;
          $importe_exento = 0.00;
      }

      if($tipo == 1){
            $stmt = $conn->prepare('UPDATE detalle_nomina_percepcion_empleado SET importe = :importe, importe_exento = :importe_exento, exento = :exento, fecha_edicion = :fecha_edicion, usuario_edicion = :usuario_edicion WHERE id = :iddetallenomina ');
      }
      if($tipo == 2){
        $stmt = $conn->prepare('UPDATE detalle_nomina_deduccion_empleado SET importe = :importe, importe_exento = :importe_exento, exento = :exento, fecha_edicion = :fecha_edicion, usuario_edicion = :usuario_edicion WHERE id = :iddetallenomina ');
      }
      $stmt->bindValue(':exento', $exento);
    }

    if($tipo == 3){
        $stmt = $conn->prepare('UPDATE detalle_otros_pagos_nomina_empleado SET importe = :importe, fecha_edicion = :fecha_edicion, usuario_edicion = :usuario_edicion WHERE id = :iddetallenomina ');
        $importe_gravado = $importe;
        $importe_exento = 0.00;
    }

      $stmt->bindValue(':importe', $importe_gravado);

      if($tipo != 3){
        $stmt->bindValue(':importe_exento', $importe_exento);
      }

      $stmt->bindValue(':fecha_edicion', $fecha);
      $stmt->bindValue(':usuario_edicion', $idUsuario);
      $stmt->bindValue(':iddetallenomina', $idDetalleNomina);

    if($stmt->execute()){

      $modo = 2;//calculo de vacaciones
      require_once("calculoImpuestos.php");


      echo "exito";
    }else{
      echo "fallo";
    }
    
} catch (PDOException $ex) {
    echo "fallo"; 
    echo $ex->getMessage();
}

?>
