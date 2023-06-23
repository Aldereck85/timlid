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
$PorcentajeAplicar = $_POST['PorcentajeAplicar'];
$pensionAlimenticiaTipo = $_POST['pensionAlimenticiaTipo'];
$existeConceptoPensionAlimenticia = $_POST['existeConceptoPensionAlimenticia'];
$existeClavePensionAlimenticia = $_POST['existeClavePensionAlimenticia'];
$fechaPago = $_POST['fechaPago'];                   

$idNominaEmpleado = $_POST['idNominaEmpleado'];
$idNomina = $_POST['idNomina'];
$idUsuario = $_SESSION['PKUsuario'];
$idEmpresa = $_SESSION['IDEmpresa'];


if($existeClavePensionAlimenticia == 1){
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
        $stmt->bindValue(':concepto', 7);
        $stmt->execute();
        $existe_concepto = $stmt->rowCount();
        
        if($existe_concepto > 0){ 
            echo "existe-concepto";
            return;
        }
        else{

            $stmt = $conn->prepare('INSERT INTO relacion_tipo_deduccion ( clave, tipo_deduccion_id, empresa_id) VALUES( :clave, :tipo_deduccion_id, :empresa_id)');
            $stmt->bindValue(':clave', $clave);
            $stmt->bindValue(':tipo_deduccion_id', 7);
            $stmt->bindValue(':empresa_id', $idEmpresa);
            $stmt->execute();
        }
    }
}

if($existeConceptoPensionAlimenticia == 1){
    $stmt = $conn->prepare('SELECT id FROM relacion_concepto_deduccion WHERE tipo_deduccion_id = 7 AND  empresa_id = '.$idEmpresa);
    $stmt->execute();
    $existe = $stmt->rowCount();

    if($existe > 0){
        $row_concepto = $stmt->fetch();
        $idConceptoDeduccion = $row_concepto['id'];
    }
    else{
        $stmt = $conn->prepare('INSERT INTO relacion_concepto_deduccion (concepto_nomina, tipo_deduccion_id, empresa_id) VALUES (:concepto_nomina, :tipo_deduccion_id, :empresa_id)');
        $stmt->bindValue(':concepto_nomina', $concepto);
        $stmt->bindValue(':tipo_deduccion_id', 7);
        $stmt->bindValue(':empresa_id', $idEmpresa);
        $stmt->execute();

        $idConceptoDeduccion = $conn->lastInsertId();
    }
}
else{
    $stmt = $conn->prepare('SELECT id FROM relacion_concepto_deduccion WHERE tipo_deduccion_id = 7 AND  empresa_id = '.$idEmpresa);
    $stmt->execute();
    $row_concepto = $stmt->fetch();
    $idConceptoDeduccion = $row_concepto['id'];
}

$tipo_concepto = 14;    
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

$aplicarPension = 0;

if($fechaInicioNominaTiempo >= $fechaAplicacionTiempo){
    $aplicarPension = 1;
}

try {
    $conn->beginTransaction();

    $stmt = $conn->prepare('INSERT INTO pension_alimenticia (empleado_id, relacion_concepto_deduccion_id, tipo_importe, tasa_pension, fecha_aplicacion, fecha_suspension, fecha_termino, fecha_alta,  usuario_alta, empresa_id, estado) VALUES (:empleado_id, :relacion_concepto_deduccion_id, :tipo_importe, :tasa_pension, :fecha_aplicacion, :fecha_suspension, :fecha_termino, :fecha_alta,  :usuario_alta, :empresa_id, :estado)');
    $stmt->bindValue(':empleado_id', $idEmpleado);
    $stmt->bindValue(':relacion_concepto_deduccion_id', $idConceptoDeduccion);
    $stmt->bindValue(':tipo_importe', $pensionAlimenticiaTipo);
    $stmt->bindValue(':tasa_pension', $PorcentajeAplicar);
    $stmt->bindValue(':fecha_aplicacion', $fechaAplicacion);
    $stmt->bindValue(':fecha_suspension', "0000-00-00");
    $stmt->bindValue(':fecha_termino', "0000-00-00");
    $stmt->bindValue(':fecha_alta', $fecha);
    $stmt->bindValue(':usuario_alta', $idUsuario);
    $stmt->bindValue(':empresa_id', $idEmpresa);
    $stmt->bindValue(':estado', 1);        
    $stmt->execute();

    $idPension = $conn->lastInsertId();

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
