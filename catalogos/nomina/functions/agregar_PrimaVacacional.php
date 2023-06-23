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

$primaVacacional = str_replace(",","",$_POST['primaVacacional']);
$dias = $_POST['dias'];
$totalVacaciones = $_POST['totalVacaciones'];
$fechaIni = $_POST['fechaIni'];
$fechaFin = $_POST['fechaFin'];
$idNominaEmpleado = $_POST['idNominaEmpleado'];
$idEmpleado = $_POST['idEmpleado'];
$idNomina = $_POST['idNomina'];
$tipo_concepto = 10;//prima vacacional
$fecha = date("Y-m-d H:i:s");
$idUsuario = $_SESSION['PKUsuario'];
$idEmpresa = $_SESSION['IDEmpresa'];
$concepto = 16;
$agregarClavePrimaVacacional = $_POST['agregarClavePrimaVacacional'];
$clavePrimaVacacionalUnica = $_POST['clavePrimaVacacionalUnica'];
$fechaPago = $_POST['fechaPago'];                  

try {
    $conn->beginTransaction();

        //prima vacacional
        if($agregarClavePrimaVacacional == 1){
            $stmt = $conn->prepare('SELECT id FROM relacion_tipo_percepcion WHERE clave = :clave AND  empresa_id = '.$idEmpresa);
            $stmt->bindValue(':clave', $clavePrimaVacacionalUnica);
            $stmt->execute();
            $existe1 = $stmt->rowCount();

            $stmt = $conn->prepare('SELECT id FROM relacion_tipo_deduccion WHERE clave = :clave AND  empresa_id = '.$idEmpresa);
            $stmt->bindValue(':clave', $clavePrimaVacacionalUnica);
            $stmt->execute();
            $existe2 = $stmt->rowCount();

            if($existe1 > 0 || $existe2 > 0){ 
                echo "existe-clave-PrimaVacacionalUnica";
                return;
            }
            else{

                $stmt = $conn->prepare('SELECT id FROM relacion_tipo_percepcion WHERE tipo_percepcion_id = :concepto AND empresa_id = '.$idEmpresa);
                $stmt->bindValue(':concepto', $concepto);
                $stmt->execute();
                $existe_concepto = $stmt->rowCount();
                
                if($existe_concepto > 0){ 
                    echo "existe-concepto-PrimaVacacionalUnica";
                    return;
                }
                else{

                    $stmt = $conn->prepare('INSERT INTO relacion_tipo_percepcion ( clave, tipo_percepcion_id, empresa_id) VALUES( :clave, :tipo_percepcion_id, :empresa_id)');
                    $stmt->bindValue(':clave', $clavePrimaVacacionalUnica);
                    $stmt->bindValue(':tipo_percepcion_id', $concepto);
                    $stmt->bindValue(':empresa_id', $idEmpresa);
                    $stmt->execute();
                }
            }
        }


        //obtener o ingresar el concepto de prima vacacional 
        $stmt = $conn->prepare('SELECT id FROM relacion_concepto_percepcion WHERE tipo_percepcion_id = 16 AND empresa_id = '.$idEmpresa);
        $stmt->execute();
        $existe = $stmt->rowCount();

        if($existe > 0){
            $row_concepto = $stmt->fetch();
            $idConceptoPercepcion = $row_concepto['id'];
        }
        else{
            $stmt = $conn->prepare('INSERT INTO relacion_concepto_percepcion (concepto_nomina, tipo_percepcion_id, empresa_id) VALUES (:concepto_nomina, :tipo_percepcion_id, :empresa_id)');
            $stmt->bindValue(':concepto_nomina', 'Prima vacacional');
            $stmt->bindValue(':tipo_percepcion_id', 16);
            $stmt->bindValue(':empresa_id', $idEmpresa);
            $stmt->execute();

            $idConceptoPercepcion = $conn->lastInsertId();
        }


        $stmt = $conn->prepare("SELECT SUM(diasrestantes) as diasrestantes FROM vacaciones_agregadas WHERE empleado_id = :empleado_id ");
        $stmt->bindValue(":empleado_id", $idEmpleado);
        $stmt->execute();
        $dias_vac = $stmt->fetch();
        $diasrestantes = $dias_vac['diasrestantes'];

        if($dias > $diasrestantes){
            echo "paso-dias";
            return;
        }

        $stmt = $conn->prepare('SELECT cantidad FROM parametros WHERE descripcion = "UMA" ');
        $stmt->execute();
        $row_parametros = $stmt->fetch();
        $UMA = $row_parametros['cantidad'];
        $totalExento = $UMA * 15;

        if($primaVacacional > $totalExento){
            $primaVacacionalGravada = bcdiv($primaVacacional - $totalExento,1,2);
            $primaVacacionalExenta = $totalExento;
        }
        else{
            $primaVacacionalGravada = 0.00;
            $primaVacacionalExenta = $primaVacacional;
        }

        $stmt = $conn->prepare('INSERT INTO detalle_nomina_percepcion_empleado (relacion_tipo_percepcion_id, relacion_concepto_percepcion_id, tipo_concepto, dias, importe, importe_exento, exento, empleado_id, nomina_empleado_id, fecha_alta, fecha_edicion, usuario_alta, usuario_edicion) VALUES (:concepto, :relacion_concepto_percepcion_id, :tipo_concepto, :dias, :importe, :importe_exento, :exento, :empleado_id, :nomina_empleado_id,:fecha_alta,:fecha_edicion,:usuario_alta,:usuario_edicion)');
        $stmt->bindValue(':concepto', $concepto);
        $stmt->bindValue(':relacion_concepto_percepcion_id', $idConceptoPercepcion);
        $stmt->bindValue(':tipo_concepto', $tipo_concepto);
        $stmt->bindValue(':dias', $dias);
        $stmt->bindValue(':importe', $primaVacacionalGravada);
        $stmt->bindValue(':importe_exento', $primaVacacionalExenta);
        $stmt->bindValue(':exento', 0);
        $stmt->bindValue(':empleado_id', $idEmpleado);
        $stmt->bindValue(':nomina_empleado_id', $idNominaEmpleado);
        $stmt->bindValue(':fecha_alta', $fecha);
        $stmt->bindValue(':fecha_edicion', $fecha);
        $stmt->bindValue(':usuario_alta', $idUsuario);
        $stmt->bindValue(':usuario_edicion', $idUsuario);
        $stmt->execute();
        $idDetalleNomina = $conn->lastInsertId();

        $stmt = $conn->prepare('INSERT INTO vacaciones (FKEmpleado, Dias_de_Vacaciones_Tomados, FechaIni, FechaFin, Prima_Vacacional, Total_Vacaciones, detalle_nomina_percepcion_empleado_id) VALUES (:FKEmpleado, :Dias_de_Vacaciones_Tomados, :FechaIni, :FechaFin, :Prima_Vacacional, :Total_Vacaciones, :detalle_nomina_percepcion_empleado_id)');
        $stmt->bindValue(':FKEmpleado', $idEmpleado);
        $stmt->bindValue(':Dias_de_Vacaciones_Tomados', $dias);
        $stmt->bindValue(':FechaIni', $fechaIni);
        $stmt->bindValue(':FechaFin', $fechaFin);
        $stmt->bindValue(':Prima_Vacacional', $primaVacacional);
        $stmt->bindValue(':Total_Vacaciones', $totalVacaciones);
        $stmt->bindValue(':detalle_nomina_percepcion_empleado_id', $idDetalleNomina);
        $stmt->execute();
    
        $stmt = $conn->prepare("SELECT id, diasrestantes, anio FROM vacaciones_agregadas WHERE empleado_id = :empleado_id ORDER BY anio ASC ");
        $stmt->bindValue(":empleado_id", $idEmpleado);
        $stmt->execute();
        $dias_vac_totales = $stmt->fetchAll();

        $x = 0;
        $diasrestantes = $dias;
        do{

            if($diasrestantes > $dias_vac_totales[$x]['diasrestantes']){
                $dias_a_restar = 0; 
                $diasrestantes = $diasrestantes - $dias_vac_totales[$x]['diasrestantes'];
                $dias_bitacora = $dias_vac_totales[$x]['diasrestantes'];
            }
            else{
                $dias_a_restar = $dias_vac_totales[$x]['diasrestantes'] - $diasrestantes;
                $dias_bitacora = $diasrestantes;
                $diasrestantes = 0;
            }

            $stmt = $conn->prepare('UPDATE vacaciones_agregadas SET diasrestantes = :diasrestantes WHERE id = :id');
            $stmt->bindValue(':diasrestantes', $dias_a_restar);
            $stmt->bindValue(':id', $dias_vac_totales[$x]['id']);
            $stmt->execute();

            $stmt = $conn->prepare('INSERT INTO vacaciones_revision (anio, diasrestados, empleado_id, detalle_nomina_percepcion_empleado_id) values (:anio , :diasrestados, :empleado_id, :detalle_nomina_percepcion_empleado_id)');
            $stmt->bindValue(':anio', $dias_vac_totales[$x]['anio']);
            $stmt->bindValue(':diasrestados', $dias_bitacora);
            $stmt->bindValue(':empleado_id', $idEmpleado);
            $stmt->bindValue(':detalle_nomina_percepcion_empleado_id', $idDetalleNomina);
            $stmt->execute();

            $x++;
        }while ($diasrestantes > 0);

        if($conn->commit()){

          $modo = 2;//para agregar o restar cantidades adicionales al total de percepciones 
          require_once("calculoImpuestos.php");

          echo "exito";
        }else{
          $conn->rollBack();
          echo "fallo1";
        }
    
    
} catch (PDOException $ex) {
    $conn->rollBack();
    echo "fallo2"; echo $ex->getMessage();
}

?>
