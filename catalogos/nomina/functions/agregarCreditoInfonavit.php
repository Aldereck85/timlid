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
$numCredito = $_POST['numCredito'];
$tipoCalculo = $_POST['tipoCalculo'];
$CuotaFija = $_POST['CuotaFija'];
$fechaAplicacion = $_POST['fechaAplicacion'];
$fechaRegistro = $_POST['fechaRegistro'];

if($_POST['MontoAcumulado'] == "" || $_POST['MontoAcumulado'] == null){
    $MontoAcumulado = 0.00;
}
else{
    $MontoAcumulado = $_POST['MontoAcumulado'];
}

if($_POST['fechaSuspension'] == "" || $_POST['fechaSuspension'] == null){
    $fechaSuspension = "0000-00-00";
}
else{
    $fechaSuspension = $_POST['fechaSuspension'];
}

$vecesAplicadas = $_POST['vecesAplicadas'];
                    
$idNomina = $_POST['idNomina'];
$idNominaEmpleado = $_POST['idNominaEmpleado'];
$existeConceptoInfonavit = $_POST['existeConceptoInfonavit'];
$existeClaveInfonavit = $_POST['existeClaveInfonavit'];
$fechaPago = $_POST['fechaPago']; 
$idUsuario = $_SESSION['PKUsuario'];
$idEmpresa = $_SESSION['IDEmpresa'];

if($existeClaveInfonavit == 1){
    $stmt = $conn->prepare('SELECT id FROM relacion_tipo_percepcion WHERE clave = :clave AND empresa_id = '.$idEmpresa);
    $stmt->bindValue(':clave', $clave);
    $stmt->execute();
    $existe1 = $stmt->rowCount();

    $stmt = $conn->prepare('SELECT id FROM relacion_tipo_deduccion WHERE clave = :clave AND empresa_id = '.$idEmpresa);
    $stmt->bindValue(':clave', $clave);
    $stmt->execute();
    $existe2 = $stmt->rowCount();

    if($existe1 > 0 || $existe2 > 0){ 
        echo "existe-clave";
        return;
    }
    else{

        $stmt = $conn->prepare('SELECT id FROM relacion_tipo_deduccion WHERE tipo_deduccion_id = :concepto AND empresa_id = '.$idEmpresa);
        $stmt->bindValue(':concepto', 9);
        $stmt->execute();
        $existe_concepto = $stmt->rowCount();
        
        if($existe_concepto > 0){ 
            echo "existe-concepto";
            return;
        }
        else{

            $stmt = $conn->prepare('INSERT INTO relacion_tipo_deduccion ( clave, tipo_deduccion_id, empresa_id) VALUES( :clave, :tipo_deduccion_id, :empresa_id)');
            $stmt->bindValue(':clave', $clave);
            $stmt->bindValue(':tipo_deduccion_id', 9);
            $stmt->bindValue(':empresa_id', $idEmpresa);
            $stmt->execute();
        }
    }
}

if($existeConceptoInfonavit == 1){
    $stmt = $conn->prepare('SELECT id FROM relacion_concepto_deduccion WHERE tipo_deduccion_id = 9 AND  empresa_id = '.$idEmpresa);
    $stmt->execute();
    $existe = $stmt->rowCount();

    if($existe > 0){
        $row_concepto = $stmt->fetch();
        $idConceptoDeduccion = $row_concepto['id'];
    }
    else{
        $stmt = $conn->prepare('INSERT INTO relacion_concepto_deduccion (concepto_nomina, tipo_deduccion_id, empresa_id) VALUES (:concepto_nomina, :tipo_deduccion_id, :empresa_id)');
        $stmt->bindValue(':concepto_nomina', $concepto);
        $stmt->bindValue(':tipo_deduccion_id', 9);
        $stmt->bindValue(':empresa_id', $idEmpresa);
        $stmt->execute();

        $idConceptoDeduccion = $conn->lastInsertId();
    }
}
else{
    $stmt = $conn->prepare('SELECT id FROM relacion_concepto_deduccion WHERE tipo_deduccion_id = 9 AND  empresa_id = '.$idEmpresa);
    $stmt->execute();
    $row_concepto = $stmt->fetch();
    $idConceptoDeduccion = $row_concepto['id'];
}


$tipo_concepto = 13;//credito infonavit    
$fecha = date("Y-m-d H:i:s");

    try {
        $conn->beginTransaction();

        $estado = 1;
        require_once("calculoinfonavit.php"); //retorna aplicarCreditoInfonavit si es 1 se aplica en esta nomina, si no no
        $ImporteAplicar = $valorCreditoInfonavitaAplicar; //Cantidad a aplicar por el calculo del infonavit
        $ImporteAcumulado = floatval($MontoAcumulado) + floatval($ImporteAplicar);


        if($vecesAplicadas == "" || $vecesAplicadas == null){
            $vecesAplicadas = 0;
            if($aplicarCreditoInfonavit == 1){
                $VecesYaAplicadas = $vecesAplicadas + 1;
            }
            else{
                $VecesYaAplicadas = $vecesAplicadas;
            }
        }
        else{
            if($aplicarCreditoInfonavit == 1){
                $VecesYaAplicadas = 1;
            }
            else{
                $VecesYaAplicadas = 0;
            }
        }
        
        $stmt = $conn->prepare('INSERT INTO credito_infonavit (empleado_id, relacion_concepto_deduccion_id, numero_credito, tipo_importe, importe_fijo, fecha_aplicacion, fecha_suspension, fecha_alta, fecha_cancelacion, importe_acumulado, veces_aplicadas, usuario_alta, usuario_cancelacion, empresa_id, estado) VALUES (:empleado_id, :relacion_concepto_deduccion_id,:numero_credito, :tipo_importe, :importe_fijo, :fecha_aplicacion, :fecha_suspension, :fecha_alta, :fecha_cancelacion, :importe_acumulado, :veces_aplicadas, :usuario_alta, :usuario_cancelacion, :empresa_id, :estado)');
        $stmt->bindValue(':empleado_id', $idEmpleado);
        $stmt->bindValue(':relacion_concepto_deduccion_id', $idConceptoDeduccion);
        $stmt->bindValue(':numero_credito', $numCredito);
        $stmt->bindValue(':tipo_importe', $tipoCalculo);
        $stmt->bindValue(':importe_fijo', $CuotaFija);
        $stmt->bindValue(':fecha_aplicacion', $fechaAplicacion);
        $stmt->bindValue(':fecha_suspension', $fechaSuspension);
        $stmt->bindValue(':fecha_alta', $fechaRegistro);
        $stmt->bindValue(':fecha_cancelacion', "0000-00-00");
        $stmt->bindValue(':importe_acumulado', $ImporteAcumulado);
        $stmt->bindValue(':veces_aplicadas', $VecesYaAplicadas);
        $stmt->bindValue(':usuario_alta', $idUsuario);
        $stmt->bindValue(':usuario_cancelacion', $idUsuario);
        $stmt->bindValue(':empresa_id', $idEmpresa);
        $stmt->bindValue(':estado', $estado);       
        $stmt->execute();

        $idCreditoInfonavit = $conn->lastInsertId();

        if($aplicarCreditoInfonavit == 1){
            $stmt = $conn->prepare('INSERT INTO detalle_nomina_deduccion_empleado (relacion_tipo_deduccion_id, relacion_concepto_deduccion_id, tipo_concepto, horas, importe, importe_exento, exento, empleado_id, nomina_empleado_id, fecha_alta, fecha_edicion, usuario_alta, usuario_edicion) VALUES (:concepto, :relacion_concepto_percepcion_id, :tipo_concepto, :horas, :importe, :importe_exento, :exento, :empleado_id, :nomina_empleado_id,:fecha_alta,:fecha_edicion,:usuario_alta,:usuario_edicion)');
            $stmt->bindValue(':concepto', 9);
            $stmt->bindValue(':relacion_concepto_percepcion_id', $idConceptoDeduccion);
            $stmt->bindValue(':tipo_concepto', $tipo_concepto);
            $stmt->bindValue(':horas', 0);
            $stmt->bindValue(':importe', $ImporteAplicar);
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

            $stmt = $conn->prepare('INSERT INTO credito_infonavit_registro (credito_infonavit_id, nomina_empleado_id, detalle_nomina_deduccion_empleado_id, importe_aplicado, usuario_alta, fecha_alta, estatus) VALUES (:credito_infonavit_id, :nomina_empleado_id, :detalle_nomina_deduccion_empleado_id, :importe_aplicado, :usuario_alta, :fecha_alta, :estatus)');
            $stmt->bindValue(':credito_infonavit_id', $idCreditoInfonavit);
            $stmt->bindValue(':nomina_empleado_id', $idNominaEmpleado);
            $stmt->bindValue(':detalle_nomina_deduccion_empleado_id', $idDetalleNominaDeduccion);
            $stmt->bindValue(':importe_aplicado', $ImporteAplicar);
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
