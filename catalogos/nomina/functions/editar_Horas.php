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


$horas = $_POST['horas'];
$tipoHora = $_POST['tipoHora'];
$ImporteHoras = $_POST['ImporteHoras'];
$idNominaEmpleado = $_POST['idNominaEmpleado'];
$idEmpleado = $_POST['idEmpleado'];
$idNomina = $_POST['idNomina'];
$idDetalleNominaHorasExtras = $_POST['idDetalleNominaHorasExtras'];
$fechaPago = $_POST['fechaPago'];

$valorHora = $ImporteHoras / ($horas * $tipoHora);

if($tipoHora == 1){
    $tipo_concepto = 3;    
}
if($tipoHora == 2){
    $tipo_concepto = 7;    
}
if($tipoHora == 3){
    $tipo_concepto = 8;    
}

$fecha = date("Y-m-d H:i:s");
$idUsuario = $_SESSION['PKUsuario'];
$idEmpresa = $_SESSION['IDEmpresa'];

if($tipoHora == 1){
    if($horas > 1){
        $concepto = $horas." horas extra";
    }
    else{
        $concepto = $horas." hora extra";
    }
    $cantidadGravada = $ImporteHoras;
    $cantidadExenta = 0.00;
}
if($tipoHora == 2){
    if($horas > 1){
        $concepto = $horas." horas extra dobles";
    }
    else{
        $concepto = $horas." hora extra doble";
    }

    $stmt = $conn->prepare('SELECT cantidad FROM parametros WHERE descripcion = "UMA" ');
    $stmt->execute();
    $row_parametros = $stmt->fetch();
    $UMA = $row_parametros['cantidad'];

    if(($ImporteHoras/2) > ($UMA * 5)){
      $cantidadExenta = $UMA * 5; 
      $cantidadGravada = $ImporteHoras - $cantidadExenta;
    }
    else{
      $cantidadExenta = number_format($ImporteHoras / 2,2, '.', '');
      $cantidadGravada = $cantidadExenta;
    }

}
if($tipoHora == 3){
    if($horas > 1){
        $concepto = $horas." horas extra triples";
    }
    else{
        $concepto = $horas." hora extra triple";
    }
    $cantidadGravada = $ImporteHoras;
    $cantidadExenta = 0.00;
}

    
try {
    $stmt = $conn->prepare('UPDATE detalle_nomina_percepcion_empleado SET horas = :horas, importe = :importe, importe_exento = :importe_exento, fecha_edicion = :fecha_edicion ,usuario_edicion = :usuario_edicion WHERE id = :idDetalleNominaHorasExtras');
    $stmt->bindValue(':horas', $horas);
    $stmt->bindValue(':importe', $cantidadGravada);
    $stmt->bindValue(':importe_exento', $cantidadExenta);
    $stmt->bindValue(':fecha_edicion', $fecha);
    $stmt->bindValue(':usuario_edicion', $idUsuario); 
    $stmt->bindValue(':idDetalleNominaHorasExtras', $idDetalleNominaHorasExtras);

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
