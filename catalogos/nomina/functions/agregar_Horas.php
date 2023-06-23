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
$agregarClaveHorasExtra = $_POST['agregarClaveHorasExtra'];
$claveHorasExtra = $_POST['claveHorasExtra'];
$idUsuario = $_SESSION['PKUsuario'];
$idEmpresa = $_SESSION['IDEmpresa'];
$fechaPago = $_POST['fechaPago'];
$existeConcepto = $_POST['existeconcepto'];
$nuevoConcepto = $_POST['nuevoconcepto'];

if($agregarClaveHorasExtra == 1){
    $stmt = $conn->prepare('SELECT id FROM relacion_tipo_percepcion WHERE clave = :clave AND  empresa_id = '.$idEmpresa);
    $stmt->bindValue(':clave', $claveHorasExtra);
    $stmt->execute();
    $existe1 = $stmt->rowCount();

    $stmt = $conn->prepare('SELECT id FROM relacion_tipo_deduccion WHERE clave = :clave AND  empresa_id = '.$idEmpresa);
    $stmt->bindValue(':clave', $claveHorasExtra);
    $stmt->execute();
    $existe2 = $stmt->rowCount();

    if($existe1 > 0 || $existe2 > 0){ 
        echo "existe-clave-HorasExtra";
        return;
    }
    else{

        $stmt = $conn->prepare('SELECT id FROM relacion_tipo_percepcion WHERE tipo_percepcion_id = :concepto AND empresa_id = '.$idEmpresa);
        $stmt->bindValue(':concepto', 14);
        $stmt->execute();
        $existe_concepto = $stmt->rowCount();
        
        if($existe_concepto > 0){ 
            echo "existe-concepto-HorasExtra";
            return;
        }
        else{

            $stmt = $conn->prepare('INSERT INTO relacion_tipo_percepcion ( clave, tipo_percepcion_id, empresa_id) VALUES( :clave, :tipo_percepcion_id, :empresa_id)');
            $stmt->bindValue(':clave', $claveHorasExtra);
            $stmt->bindValue(':tipo_percepcion_id', 14);
            $stmt->bindValue(':empresa_id', $idEmpresa);
            $stmt->execute();
        }
    }
}

if($existeConcepto == 1){
    $stmt = $conn->prepare('SELECT id FROM relacion_concepto_percepcion WHERE tipo_percepcion_id = 14 AND  empresa_id = '.$idEmpresa);
    $stmt->execute();
    $existe = $stmt->rowCount();

    if($existe > 0){
        $row_concepto = $stmt->fetch();
        $idConceptoPercepcion = $row_concepto['id'];
    }
    else{
        $stmt = $conn->prepare('INSERT INTO relacion_concepto_percepcion (concepto_nomina, tipo_percepcion_id, empresa_id) VALUES (:concepto_nomina, :tipo_percepcion_id, :empresa_id)');
        $stmt->bindValue(':concepto_nomina', $nuevoConcepto);
        $stmt->bindValue(':tipo_percepcion_id', 14);
        $stmt->bindValue(':empresa_id', $idEmpresa);
        $stmt->execute();

        $idConceptoPercepcion = $conn->lastInsertId();
    }
}
else{
    $stmt = $conn->prepare('SELECT id FROM relacion_concepto_percepcion WHERE tipo_percepcion_id = 14 AND  empresa_id = '.$idEmpresa);
    $stmt->execute();
    $row_concepto = $stmt->fetch();
    $idConceptoPercepcion = $row_concepto['id'];
}


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


$stmt = $conn->prepare('SELECT horas, importe  FROM  detalle_nomina_percepcion_empleado WHERE empleado_id = :idEmpleado AND nomina_empleado_id = :idNominaEmpleado  AND tipo_concepto = :tipoConcepto');
$stmt->bindValue(':idEmpleado', $idEmpleado);
$stmt->bindValue(':idNominaEmpleado', $idNominaEmpleado);
$stmt->bindValue(':tipoConcepto', $tipo_concepto);
$stmt->execute();
$rowHoras = $stmt->fetch(); 
$cantidadRegistros = $stmt->rowCount(); 

if($tipo_concepto == 3 || $tipo_concepto == 8){


    if($cantidadRegistros > 0){
        $horas = $horas + $rowHoras['horas'];
        $ImporteHoras = bcdiv($ImporteHoras + $rowHoras['importe'],1,2);
    }

    if($tipoHora == 1){
        if($horas > 1){
            $concepto = $horas." horas extra";
        }
        else{
            $concepto = $horas." hora extra";
        }
    }
    if($tipoHora == 3){
        if($horas > 1){
            $concepto = $horas." horas extra triples";
        }
        else{
            $concepto = $horas." hora extra triple";
        }
    }
}


if($tipo_concepto == 7){


    $stmt = $conn->prepare("SELECT pp.DiasPago FROM datos_laborales_empleado as dle  INNER JOIN periodo_pago as pp ON pp.PKPeriodo_pago = dle.FKPeriodo WHERE FKEmpleado = :idEmpleado");
    $stmt->execute(array(':idEmpleado'=>$idEmpleado));
    $datosEmpleado = $stmt->fetch();

    switch ($datosEmpleado['DiasPago']) {
        case 7:
            $semanas = 1;
            break;
        case 14:
            $semanas = 2;
            break;
        case 15:
            $semanas = 2;
            break;
        case 30:
            $semanas = 4;
            break;
    }

    if($cantidadRegistros + 1 > $semanas ){
        echo "mas-conceptos-doble";
        return;
    }

    if($horas > 9){
        echo "horas-pasadas-doble";
        return;
    }
    
    if($tipoHora == 2){
        if($horas > 1){
            $concepto = $horas." horas extra dobles";
        }
        else{
            $concepto = $horas." hora extra doble";
        }
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


    //en horas dobles siempre va a ser insert, pero solo de acuerdo al numero de semanas
    try {

        $stmt = $conn->prepare('INSERT INTO detalle_nomina_percepcion_empleado (relacion_tipo_percepcion_id, relacion_concepto_percepcion_id, tipo_concepto, horas, importe, importe_exento, exento, empleado_id, nomina_empleado_id, fecha_alta, fecha_edicion, usuario_alta, usuario_edicion) VALUES (:concepto, :relacion_concepto_percepcion_id, :tipo_concepto, :horas, :importe, :importe_exento, :exento, :empleado_id, :nomina_empleado_id,:fecha_alta,:fecha_edicion,:usuario_alta,:usuario_edicion)');
        $stmt->bindValue(':concepto', 14);
        $stmt->bindValue(':relacion_concepto_percepcion_id', $idConceptoPercepcion);
        $stmt->bindValue(':tipo_concepto', $tipo_concepto);
        $stmt->bindValue(':horas', $horas);
        $stmt->bindValue(':importe', $cantidadGravada);
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


}
else{
    if($cantidadRegistros > 0){
        
        try {
            $stmt = $conn->prepare('UPDATE  detalle_nomina_percepcion_empleado SET horas = :horas, importe =  :importe, importe_exento = :importe_exento, fecha_edicion = :fecha_edicion ,usuario_edicion = :usuario_edicion WHERE empleado_id = :idEmpleado AND nomina_empleado_id = :idNominaEmpleado  AND tipo_concepto = :tipoConcepto');
            $stmt->bindValue(':horas', $horas);
            $stmt->bindValue(':importe', $ImporteHoras);
            $stmt->bindValue(':importe_exento', 0.00);
            $stmt->bindValue(':fecha_edicion', $fecha);
            $stmt->bindValue(':usuario_edicion', $idUsuario);
            $stmt->bindValue(':idEmpleado', $idEmpleado);
            $stmt->bindValue(':idNominaEmpleado', $idNominaEmpleado);
            $stmt->bindValue(':tipoConcepto', $tipo_concepto);

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


    }
    else{
        
        try {
            $stmt = $conn->prepare('INSERT INTO detalle_nomina_percepcion_empleado (relacion_tipo_percepcion_id, relacion_concepto_percepcion_id, tipo_concepto, horas, importe, importe_exento, exento, empleado_id, nomina_empleado_id, fecha_alta, fecha_edicion, usuario_alta, usuario_edicion) VALUES (:concepto, :relacion_concepto_percepcion_id, :tipo_concepto, :horas, :importe, :importe_exento, :exento, :empleado_id, :nomina_empleado_id,:fecha_alta,:fecha_edicion,:usuario_alta,:usuario_edicion)');
            $stmt->bindValue(':concepto', 14);
            $stmt->bindValue(':relacion_concepto_percepcion_id', $idConceptoPercepcion);
            $stmt->bindValue(':tipo_concepto', $tipo_concepto);
            $stmt->bindValue(':horas', $horas);
            $stmt->bindValue(':importe', $ImporteHoras);
            $stmt->bindValue(':importe_exento', 0.00);
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
    }
}
?>
