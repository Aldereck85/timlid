<?php
session_start();
$respuesta = new stdClass();
$token = $_POST['csr_token_UT5JP'];

if(empty($_SESSION['token_ld10d'])) {
    $respuesta->estatus = "fallo";
    $respuesta = json_encode($respuesta);
    echo $respuesta;
    return;          
}

if (!hash_equals($_SESSION['token_ld10d'], $token)) {
    $respuesta->estatus = "fallo";
    $respuesta = json_encode($respuesta);
    echo $respuesta;
    return;
}

require_once '../../../include/db-conn.php';
$GLOBALS['rutaFuncion'] = "./../";
require_once("../../../functions/funcionNomina.php");

$tipoMovimiento = $_POST['tipoMovimiento'];
$idEmpleado = $_POST['idEmpleado'];
$fechaSalida = $_POST['fechaSalida'];
$fechaAnio = DateTime::createFromFormat("Y-m-d", $fechaSalida);
$anioCalculo = $fechaAnio->format("Y");

if(isset($_POST['sueldoMensual'])){
  $sueldoMensual = $_POST['sueldoMensual'];
}else{
  $datosSalario = getSalario_Dias($idEmpleado);
  $sueldoMensual = $datosSalario[5];
}


// para mostrar calculo de salario/vacaciones
if($tipoMovimiento == 1){
  if($_POST['totalVacacionesGravado'] == ''){
      $totalVacacionesGravado = 0;
  }
  else{
      $totalVacacionesGravado = str_replace(",","",$_POST['totalVacacionesGravado']);
  }

  if($_POST['totalSalario'] == ''){
      $totalSalario = 0;
  }
  else{
      $totalSalario = str_replace(",","",$_POST['totalSalario']);
  }

  $totalCalculo = $totalVacacionesGravado + $totalSalario;

  $respuesta->estatus = "exito";
  $respuesta->resultado = calculoISRFiniquitoImpresion($totalCalculo, $anioCalculo);
}

if($tipoMovimiento == 2){

  if($_POST['aguinaldoGravado'] == ''){
      $aguinaldoGravado = 0;
  }
  else{
      $aguinaldoGravado = str_replace(",","",$_POST['aguinaldoGravado']);
  }

  $respuesta->estatus = "exito";
  $respuesta->resultado = calculoISRGeneralFiniquitoImpresion($aguinaldoGravado, $sueldoMensual, $tipoMovimiento, $anioCalculo);

}

if($tipoMovimiento == 3){

  if($_POST['primaVacacionalGravado'] == ''){
      $primaVacacionalGravado = 0;
  }
  else{
      $primaVacacionalGravado = str_replace(",","",$_POST['primaVacacionalGravado']);
  }

  $respuesta->estatus = "exito";
  $respuesta->resultado = calculoISRGeneralFiniquitoImpresion($primaVacacionalGravado, $sueldoMensual , $tipoMovimiento, $anioCalculo);

}

if($tipoMovimiento == 4){

  $fechaPeriodoIni = $_POST['fechaPeriodoIni'];
  $fechaPeriodoFin = $_POST['fechaPeriodoFin'];

  $date1 = date_create_from_format('Y-m-d', $fechaPeriodoIni);
  $date2 = date_create_from_format('Y-m-d',  $fechaPeriodoFin);
  $diff = (array) date_diff($date1, $date2);

  $diferencia = $diff['days'];
  $diasTrabajados = $diferencia + 1;

  $respuesta->estatus = "exito";
  $respuesta->resultado = calcularIMSSMostrar($idEmpleado, $diasTrabajados, 1);

}

if($tipoMovimiento == 5){

  if($_POST['indeminizacionGravado'] == ''){
      $indeminizacionGravado = 0;
  }
  else{
      $indeminizacionGravado = str_replace(",","",$_POST['indeminizacionGravado']);
  }

  $respuesta->estatus = "exito";
  $respuesta->resultado = calculoISRIndemnizacionMostrar($idEmpleado, $indeminizacionGravado, $anioCalculo);

}

$respuesta = json_encode($respuesta);
echo $respuesta;