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

$ruta = "./../";
$GLOBALS['rutaFuncion'] = $ruta;
require_once($ruta."../../functions/funcionNomina.php");
require_once '../../../include/db-conn.php';

$idEmpleado = $_POST['idEmpleado'];
$idNominaEmpleado = $_POST['idNominaEmpleado'];
$fechaPago = $_POST['fechaPago'];
$modo = 2;


//Calculo de conceptos para el total de calculo de impuestos
$stmt = $conn->prepare('SELECT 1 as tipo, tipo_concepto, importe, importe_exento, exento, dias FROM detalle_nomina_percepcion_empleado WHERE empleado_id = :empleado_id AND nomina_empleado_id = :nomina_empleado_id
                        UNION ALL
                        SELECT 2 as tipo, tipo_concepto, importe, 0.00 as importe_exento, exento, dias FROM detalle_nomina_deduccion_empleado WHERE empleado_id = :empleado_id2 AND nomina_empleado_id = :nomina_empleado_id2');
$stmt->bindValue(':empleado_id', $idEmpleado);
$stmt->bindValue(':nomina_empleado_id', $idNominaEmpleado);
$stmt->bindValue(':empleado_id2', $idEmpleado);
$stmt->bindValue(':nomina_empleado_id2', $idNominaEmpleado);
$stmt->execute();
$conceptos = $stmt->fetchAll();

if(count($conceptos) > 0){

    $datosSBC = getSBCNomina($idEmpleado, $fechaPago);
    $SBC = $datosSBC[2];

    $datosEmpleado = getSalario_Dias($idEmpleado);
    $diasTrabajados = $datosEmpleado[1];

    $diasFaltasIMSS = getDiasFaltasIMSS($idNominaEmpleado, $idEmpleado);

    if($diasFaltasIMSS > $diasTrabajados){
      $diasTrabajadosIMSS = 0;
    }
    else{
      $diasTrabajadosIMSS = $diasTrabajados - $diasFaltasIMSS;
    }

    $calculo = calcularCuotasIMSSMostrar($idEmpleado, $SBC, $diasTrabajadosIMSS, $fechaPago);

    $respuesta->estatus = "exito";
    $respuesta->resultado = $calculo;
      
}
else{
  // en caso de que el empleado no tenga ningun concepto agregado , se pone en ceros su nomina 
       $respuesta->estatus = "exito";
       $respuesta->resultado = '<div class="row" style="display: block;">
                                  <center>
                                    No se puede calcular el IMSS sin ningún concepto.
                                  </center>
                                 </div>';
}

$respuesta = json_encode($respuesta);
echo $respuesta;