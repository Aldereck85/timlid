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

$idNominaEmpleado = $_POST['idNominaEmpleado'];
$idEmpleado = $_POST['idEmpleado'];
$concepto = $_POST['concepto'];
$importe = $_POST['importe'];
$tipo = $_POST['tipo'];
$tipo_concepto = 2;
$exento = $_POST['exento'];
$idUsuario = $_SESSION['PKUsuario'];
$fecha = date("Y-m-d H:i:s");
$idNomina = $_POST['idNomina'];
$disponibleClave = $_POST['disponibleClave'];
$clave = $_POST['clave'];
$idEmpresa = $_SESSION['IDEmpresa'];
$fechaPago = $_POST['fechaPago'];

$nuevoConcepto = $_POST['nuevoConcepto'];
$nuevoConceptoCheck = $_POST['nuevoConceptoCheck'];
$claveSAT = $_POST['claveSAT'];

$stmt = $conn->prepare('SELECT estatus FROM nomina WHERE id ='.$idNomina);
$stmt->execute();
$row = $stmt->fetch();
$estatus = $row['estatus'];

if($estatus == 2){
    echo "fallo-agregar";
    return;
}

/*
if($tipo == 1){
    $stmt = $conn->prepare('SELECT id FROM detalle_nomina_percepcion_empleado WHERE relacion_tipo_percepcion_id = :concepto AND empleado_id = :empleado_id AND nomina_empleado_id = :nomina_empleado_id');
}
else{
    $stmt = $conn->prepare('SELECT id FROM detalle_nomina_deduccion_empleado WHERE relacion_tipo_deduccion_id = :concepto AND empleado_id = :empleado_id AND nomina_empleado_id = :nomina_empleado_id');
}

$stmt->bindValue(':concepto', $concepto);
$stmt->bindValue(':empleado_id', $idEmpleado);
$stmt->bindValue(':nomina_empleado_id', $idNominaEmpleado);
$stmt->execute();
$existe = $stmt->rowCount();


if($existe > 0){ 
    echo "existe-concepto";
    return;
}*/

//echo " disponibleClave ".$disponibleClave;
try {
    $conn->beginTransaction();

    //cuando se ingresa un nuevo concepto con la clave SAT
    if($nuevoConceptoCheck == 1){

        if($tipo == 1){
            $stmt = $conn->prepare('INSERT INTO relacion_concepto_percepcion ( concepto_nomina, tipo_percepcion_id, empresa_id) VALUES( :concepto_nomina, :tipo_percepcion_id, :empresa_id)');
            $stmt->bindValue(':concepto_nomina', $nuevoConcepto);
            $stmt->bindValue(':tipo_percepcion_id', $claveSAT);
            $stmt->bindValue(':empresa_id', $idEmpresa);
            $stmt->execute();
            $idRelacionConcepto = $conn->lastInsertId();
        }
        if($tipo == 2){
            $stmt = $conn->prepare('INSERT INTO relacion_concepto_deduccion ( concepto_nomina, tipo_deduccion_id, empresa_id) VALUES( :concepto_nomina, :tipo_deduccion_id, :empresa_id)');
            $stmt->bindValue(':concepto_nomina', $nuevoConcepto);
            $stmt->bindValue(':tipo_deduccion_id', $claveSAT);
            $stmt->bindValue(':empresa_id', $idEmpresa);
            $stmt->execute();
            $idRelacionConcepto = $conn->lastInsertId();
        }
        if($tipo == 3){
            $stmt = $conn->prepare('INSERT INTO relacion_concepto_otros_pagos ( concepto_nomina, tipo_otros_pagos_id, empresa_id) VALUES( :concepto_nomina, :tipo_otros_pagos_id, :empresa_id)');
            $stmt->bindValue(':concepto_nomina', $nuevoConcepto);
            $stmt->bindValue(':tipo_otros_pagos_id', $claveSAT);
            $stmt->bindValue(':empresa_id', $idEmpresa);
            $stmt->execute();
            $idRelacionConcepto = $conn->lastInsertId();
        }
    }
    else{

        if($tipo == 1){
            $stmt = $conn->prepare('SELECT tipo_percepcion_id FROM relacion_concepto_percepcion WHERE id = :concepto AND  empresa_id = '.$idEmpresa);
            $stmt->bindValue(':concepto', $concepto);
            $stmt->execute();
            $rowConcepto = $stmt->fetch();
            $tipo_relacion_id = $rowConcepto['tipo_percepcion_id'];
        }
        if($tipo == 2){
            $stmt = $conn->prepare('SELECT tipo_deduccion_id FROM relacion_concepto_deduccion WHERE id = :concepto AND  empresa_id = '.$idEmpresa);
            $stmt->bindValue(':concepto', $concepto);
            $stmt->execute();
            $rowConcepto = $stmt->fetch();
            $tipo_relacion_id = $rowConcepto['tipo_deduccion_id'];
        }
        if($tipo == 3){
            $stmt = $conn->prepare('SELECT tipo_otros_pagos_id FROM relacion_concepto_otros_pagos WHERE id = :concepto AND  empresa_id = '.$idEmpresa);
            $stmt->bindValue(':concepto', $concepto);
            $stmt->execute();
            $rowConcepto = $stmt->fetch();
            $tipo_relacion_id = $rowConcepto['tipo_otros_pagos_id'];
        }
    }

    if($disponibleClave == 1){
       // echo " clave ".$clave;

        if($nuevoConceptoCheck == 1){

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
                    $conn->rollBack(); 
                    return;
                }

                if($tipo == 1){
                    $stmt = $conn->prepare('SELECT id FROM relacion_tipo_percepcion WHERE tipo_percepcion_id = :concepto AND empresa_id = '.$idEmpresa);
                }
                else{
                    $stmt = $conn->prepare('SELECT id FROM relacion_tipo_deduccion WHERE tipo_deduccion_id = :concepto AND empresa_id = '.$idEmpresa);
                }

                $stmt->bindValue(':concepto', $claveSAT);
                $stmt->execute();
                $existe_concepto = $stmt->rowCount();
                
                if($existe_concepto > 0){ 
                    echo "existe-concepto-clave";
                    $conn->rollBack(); 
                    return;
                }

                if($tipo == 1){
                    $stmt = $conn->prepare('INSERT INTO relacion_tipo_percepcion ( clave, tipo_percepcion_id, empresa_id) VALUES( :clave, :tipo_percepcion_id, :empresa_id)');
                    $stmt->bindValue(':clave', $clave);
                    $stmt->bindValue(':tipo_percepcion_id', $claveSAT);
                    $stmt->bindValue(':empresa_id', $idEmpresa);
                }
                if($tipo == 2){
                    $stmt = $conn->prepare('INSERT INTO relacion_tipo_deduccion ( clave, tipo_deduccion_id, empresa_id) VALUES( :clave, :tipo_deduccion_id, :empresa_id)');
                    $stmt->bindValue(':clave', $clave);
                    $stmt->bindValue(':tipo_deduccion_id', $claveSAT);
                    $stmt->bindValue(':empresa_id', $idEmpresa);
                }
                $stmt->execute();
        }
    }

    if($tipo == 1){

        if($concepto == 1){
            $tipo_concepto = 1;
            $exento = 0;
        }

        $stmt = $conn->prepare('INSERT INTO detalle_nomina_percepcion_empleado (relacion_tipo_percepcion_id, relacion_concepto_percepcion_id, tipo_concepto, importe, importe_exento, exento, empleado_id, nomina_empleado_id, fecha_alta, fecha_edicion, usuario_alta, usuario_edicion) VALUES (:concepto, :relacion_concepto, :tipo_concepto, :importe, :importe_exento, :exento, :empleado_id, :nomina_empleado_id,:fecha_alta,:fecha_edicion,:usuario_alta,:usuario_edicion)');
    }
    if($tipo == 2){
        $stmt = $conn->prepare('INSERT INTO detalle_nomina_deduccion_empleado (relacion_tipo_deduccion_id, relacion_concepto_deduccion_id , tipo_concepto, importe, exento, empleado_id, nomina_empleado_id, fecha_alta, fecha_edicion, usuario_alta, usuario_edicion) VALUES (:concepto, :relacion_concepto, :tipo_concepto, :importe, :exento, :empleado_id, :nomina_empleado_id,:fecha_alta,:fecha_edicion,:usuario_alta,:usuario_edicion)');
    }
    if($tipo == 3){
        $stmt = $conn->prepare('INSERT INTO detalle_otros_pagos_nomina_empleado (otros_pagos_id, relacion_concepto_otros_pagos_id, importe, empleado_id, nomina_empleado_id, fecha_alta, fecha_edicion, usuario_alta, usuario_edicion, edicion) VALUES (:otros_pagos_id, :relacion_concepto, :importe, :empleado_id, :nomina_empleado_id,:fecha_alta,:fecha_edicion,:usuario_alta,:usuario_edicion, :edicion)');
    }


    if($tipo == 1 || $tipo == 2){

        if($nuevoConceptoCheck == 1){

            if($tipo == 1){
                if($claveSAT == 41){
                    $exento = 1;
                }
            }

            $stmt->bindValue(':concepto', $claveSAT);
            $stmt->bindValue(':relacion_concepto', $idRelacionConcepto);//$idRelacionConcepto

        }
        else{

            if($tipo == 1){
                if($tipo_relacion_id == 41){
                    $exento = 1;
                }
            }
            
            $stmt->bindValue(':concepto', $tipo_relacion_id);
            $stmt->bindValue(':relacion_concepto', $concepto);
        }
        
        if($exento == 1){
            $importe_exento = $importe;
            $importe = 0.00;
        }
        
        $stmt->bindValue(':tipo_concepto', $tipo_concepto);
        $stmt->bindValue(':importe', $importe);

        if($tipo == 1){
            $stmt->bindValue(':importe_exento', $importe_exento);
        }

        $stmt->bindValue(':exento', $exento);
        $stmt->bindValue(':empleado_id', $idEmpleado);
        $stmt->bindValue(':nomina_empleado_id', $idNominaEmpleado);
        $stmt->bindValue(':fecha_alta', $fecha);
        $stmt->bindValue(':fecha_edicion', $fecha);
        $stmt->bindValue(':usuario_alta', $idUsuario);
        $stmt->bindValue(':usuario_edicion', $idUsuario);
    }
    else{
        
        if($nuevoConceptoCheck == 1){
            $stmt->bindValue(':otros_pagos_id', $claveSAT);
            $stmt->bindValue(':relacion_concepto', $idRelacionConcepto);
        }
        else{
            $stmt->bindValue(':otros_pagos_id', $tipo_relacion_id);
            $stmt->bindValue(':relacion_concepto', $concepto);
        }

        $stmt->bindValue(':importe', $importe);
        $stmt->bindValue(':empleado_id', $idEmpleado);
        $stmt->bindValue(':nomina_empleado_id', $idNominaEmpleado);
        $stmt->bindValue(':fecha_alta', $fecha);
        $stmt->bindValue(':fecha_edicion', $fecha);
        $stmt->bindValue(':usuario_alta', $idUsuario);
        $stmt->bindValue(':usuario_edicion', $idUsuario);
        $stmt->bindValue(':edicion', 1);
    }

    $stmt->execute();

    if($conn->commit()){
      $modo = 2;//calculo de vacaciones
      require_once("calculoImpuestos.php");

      echo "exito";
      return;
    }else{
      $conn->rollBack(); 
      echo "fallo";
      return;
    }
    
} catch (PDOException $ex) {
    $conn->rollBack(); 
    echo "fallo"; 
    echo $ex->getMessage();
}

?>
