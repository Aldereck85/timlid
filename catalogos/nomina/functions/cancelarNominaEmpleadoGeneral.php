<?php
use Facturapi\Facturapi;
require_once '../../../vendor/facturapi/facturapi-php/src/Facturapi.php';

session_start();

$jwt_ruta = "../../../";
require_once '../../jwt.php';

$token = $_POST['csr_token_UT5JP'];
$respuesta = new stdClass();

$array[0]['estatus'] = "";
$array[0]['estatus_token'] = "";
$estatus_final = 0;

if(empty($_SESSION['token_ld10d'])) {
    $array[0]['estatus_token'] = "fallo";
    $respuesta->resultado = $array;
    $respuesta = json_encode($respuesta);
    echo $respuesta;
    return;                 
}

if (!hash_equals($_SESSION['token_ld10d'], $token)) {
    $array[0]['estatus_token'] = "fallo";
    $respuesta->resultado = $array;
    $respuesta = json_encode($respuesta);
    echo $respuesta;
    return;
}

require_once '../../../include/db-conn.php';

date_default_timezone_set('America/Mexico_City');

$respuesta = new stdClass();

$idEmpleado = $_POST['idEmpleado'];
$idNomina = $_POST['idNomina'];
$idEmpresa = $_SESSION['IDEmpresa'];
$motivoCancelacion = $_POST['motivoCancelacion'];


$stmt = $conn->prepare("SELECT PKNomina, FKEmpleado FROM nomina_empleado WHERE FKNomina = :idNomina AND Exento = 0 AND estadoTimbrado = 1");
$stmt->bindValue(":idNomina",$idNomina);
$stmt->execute();
$empleados = $stmt->fetchAll();

$cont = 0;

foreach($empleados as $emp){

    $array[$cont]['estatus_ind'] = "";
    $array[$cont]['estatus_act'] = "";  
    $array[$cont]['nombre'] = "";
    $array[$cont]['idempleado'] = "";

    $stmt = $conn->prepare("SELECT e.PKEmpleado ,e.Nombres, e.PrimerApellido, e.SegundoApellido, e.CURP, e.CP, dme.NSS as num_seguridad_social, e.RFC, e.idtimbrado, e.email, CONCAT(dle.FechaIngreso,'T06:00:00.000Z') as fecha_inicio_rel_laboral, tc.codigo as tipo_contrato, tj.codigo as tipo_jornada, tr.codigo as tipo_regimen, e.id_empleado as num_empleado, p.puesto, rp.codigo as riesgo_puesto,  pp.Codigo as periodicidad_pago, ef.Codigo as clave_ent_fed, dle.Sueldo, crf.clave as clave_regimen_fiscal, dle.SalarioBaseCotizacionFijo, dle.SalarioBaseCotizacionVariable FROM empleados as e LEFT JOIN datos_medicos_empleado as dme ON dme.FKEmpleado = e.PKEmpleado LEFT JOIN datos_laborales_empleado as dle ON dle.FKEmpleado = e.PKEmpleado LEFT JOIN tipo_contrato as tc ON tc.id = dle.FKTipoContrato LEFT JOIN turnos as t ON t.PKTurno = dle.FKTurno LEFT JOIN tipo_jornada as tj ON tj.id = t.tipo_jornada_id LEFT JOIN tipo_regimen as tr ON tr.id = dle.FKRegimen LEFT JOIN puestos as p ON p.id = dle.FKPuesto LEFT JOIN riesgo_puesto as rp ON rp.id = dle.FKRiesgoPuesto LEFT JOIN periodo_pago as pp ON pp.PKPeriodo_pago = dle.FKPeriodo LEFT JOIN estados_federativos as ef ON ef.PKEstado = e.FKEstado LEFT JOIN claves_regimen_fiscal as crf ON crf.id = e.claves_regimen_fiscal_id WHERE e.PKEmpleado = :idEmpleado AND e.empresa_id = :empresa_id");
    $stmt->bindValue(":idEmpleado",$emp['FKEmpleado']);
    $stmt->bindValue(":empresa_id",$idEmpresa);
    $stmt->execute();
    $datos_empleado = $stmt->fetch();

    $array[$cont]['nombre'] = $datos_empleado['Nombres']." ".$datos_empleado['PrimerApellido']." ".$datos_empleado['SegundoApellido'];
    $array[$cont]['idempleado'] = $datos_empleado['PKEmpleado'];
    $idNominaEmpleado = $emp['PKNomina'];

    $stmt = $conn->prepare("SELECT estadoTimbrado, idFactura, uuid FROM nomina_empleado WHERE PKNomina = :idNominaEmpleado AND FKEmpleado = :idEmpleado AND FKNomina = :idNomina");
    $stmt->bindValue(":idNominaEmpleado", $idNominaEmpleado);
    $stmt->bindValue(":idEmpleado", $emp['FKEmpleado']);
    $stmt->bindValue(":idNomina", $idNomina);
    $stmt->execute();
    $row = $stmt->fetch();

    if($row['estadoTimbrado'] == 0 || $row['estadoTimbrado'] == 2){
      $array[$cont]['estatus_ind'] = "fallo-cancelada";
      $estatus_final++;
      $cont++;
      continue;
    }

    $stmt = $conn->prepare("SELECT clave FROM motivo_cancelacion_factura WHERE id = :idMotivo");
    $stmt->bindValue(":idMotivo", $motivoCancelacion);
    $stmt->execute();
    $rowCancelar = $stmt->fetch();
    $motivoCancelacionDesc = $rowCancelar['clave'];

    $query = sprintf("select key_company_api key_company,key_user_company_api key_user, RFC, registro_patronal from empresas where PKEmpresa = :id");
    $stmt = $conn->prepare($query);
    $stmt->bindValue(":id",$_SESSION['IDEmpresa']);
    $stmt->execute();
    $key_company_api = $stmt->fetchAll();

    $facturapi = new Facturapi($key_company_api[0]['key_company']);

    $peticion = $facturapi->Invoices->cancel($row['idFactura'],['motive' => $motivoCancelacionDesc]);

    //echo "<pre>",print_r($peticion),"</pre>";

    if(!isset($peticion->status)){
      if($peticion->message == "No puedes cancelar una factura con estatus \"canceled\"."){
        $array[$cont]['estatus_ind'] = "fallo-cancelado";

        $conn->beginTransaction(); 

        $stmt = $conn->prepare('UPDATE nomina_empleado SET estadoTimbrado = 2 WHERE PKNomina = :idNominaEmpleado AND FKNomina = :idNomina AND FKEmpleado = :idEmpleado');
        $stmt->bindValue(':idNominaEmpleado', $idNominaEmpleado);
        $stmt->bindValue(':idNomina', $idNomina);
        $stmt->bindValue(':idEmpleado', $emp['FKEmpleado']);
        $stmt->execute(); 

        $stmt = $conn->prepare(' UPDATE nomina SET estatus = 1 WHERE id = :idNomina AND empresa_id = :idEmpresa');
        $stmt->bindValue(':idNomina', $idNomina);
        $stmt->bindValue(':idEmpresa', $_SESSION['IDEmpresa']);
        $stmt->execute();

        $stmt = $conn->prepare("SELECT id FROM bitacora_cfdi_eliminado_nomina WHERE idFactura = :idFactura AND uuid = :uuid ");
        $stmt->bindValue(":idFactura", $row['idFactura']);
        $stmt->bindValue(":uuid", $row['uuid']);
        $stmt->execute();
        $rowExisteBitacora = $stmt->rowCount();

        if($rowExisteBitacora < 1){
          $date = date("Y-m-d H:i:s");
          $stmt = $conn->prepare('INSERT INTO bitacora_cfdi_eliminado_nomina (nomina_empleado_id, empleado_id, idFactura, uuid, motivo_cancelacion_id, fecha_baja, usuario_baja)  VALUES (:idNominaEmpleado, :empleado_id, :idFactura, :uuid, :motivo_cancelacion_id, :fecha_baja, :usuario_baja)');
          $stmt->bindValue(':idNominaEmpleado', $idNominaEmpleado);
          $stmt->bindValue(':empleado_id', $idEmpleado);
          $stmt->bindValue(':idFactura', $row['idFactura'] );
          $stmt->bindValue(':uuid', $row['uuid']);
          $stmt->bindValue(':motivo_cancelacion_id', $motivoCancelacion);
          $stmt->bindValue(':fecha_baja', $date);
          $stmt->bindValue(':usuario_baja', $_SESSION['PKUsuario']);
          $stmt->execute();
        }

        if($conn->commit()){
          $array[$cont]['estatus_act'] = "exito-existe";
        }else{
          $array[$cont]['estatus_act'] = "fallo-existe";
          $conn->rollBack();
        }
        $estatus_final++;
        $cont++;
        continue;
      }
    }

    $stmt = $conn->prepare(' SELECT SUM(estadoTimbrado) as total_timbrado, COUNT(PKNomina)  as total_empleados FROM nomina_empleado WHERE FKNomina = :idNomina AND Exento = 0');
    $stmt->bindValue(':idNomina', $idNomina);
    $stmt->execute();
    $nomina_completa = $stmt->fetch();

    if($nomina_completa['total_timbrado'] == $nomina_completa['total_empleados']){
      $estatus_nomina_completa_ant = 1;
    }
    else{
      $estatus_nomina_completa_ant = 0;
    }

    if($peticion->status == "canceled"){

      $conn->beginTransaction(); 

      $stmt = $conn->prepare('UPDATE nomina_empleado SET estadoTimbrado = 2 WHERE PKNomina = :idNominaEmpleado AND FKNomina = :idNomina AND FKEmpleado = :idEmpleado');
      $stmt->bindValue(':idNominaEmpleado', $idNominaEmpleado);
      $stmt->bindValue(':idNomina', $idNomina);
      $stmt->bindValue(':idEmpleado', $emp['FKEmpleado']);
      $stmt->execute();

      $stmt = $conn->prepare(' UPDATE nomina SET estatus = 1 WHERE id = :idNomina AND empresa_id = :idEmpresa');
      $stmt->bindValue(':idNomina', $idNomina);
      $stmt->bindValue(':idEmpresa', $_SESSION['IDEmpresa']);
      $stmt->execute();

      $stmt = $conn->prepare(' SELECT SUM(estadoTimbrado) as total_timbrado, COUNT(PKNomina)  as total_empleados FROM nomina_empleado WHERE FKNomina = :idNomina AND Exento = 0');
      $stmt->bindValue(':idNomina', $idNomina);
      $stmt->execute();
      $nomina_completa = $stmt->fetch();

      if($nomina_completa['total_timbrado'] == $nomina_completa['total_empleados']){
        $estatus_nomina_completa = 1; 
      }
      else{
        $estatus_nomina_completa = 0; 
      }

      $respuesta->nomina_completa = $estatus_nomina_completa;
      $respuesta->estatus_nomina_completa_ant = $estatus_nomina_completa_ant;

      $stmt = $conn->prepare("SELECT id FROM bitacora_cfdi_eliminado_nomina WHERE idFactura = :idFactura AND uuid = :uuid ");
      $stmt->bindValue(":idFactura", $row['idFactura']);
      $stmt->bindValue(":uuid", $row['uuid']);
      $stmt->execute();
      $rowExisteBitacora = $stmt->rowCount();

      if($rowExisteBitacora < 1){
        $date = date("Y-m-d H:i:s");
        $stmt = $conn->prepare('INSERT INTO bitacora_cfdi_eliminado_nomina (nomina_empleado_id, empleado_id, idFactura, uuid, motivo_cancelacion_id, fecha_baja, usuario_baja)  VALUES (:idNominaEmpleado, :empleado_id, :idFactura, :uuid, :motivo_cancelacion_id, :fecha_baja, :usuario_baja)');
        $stmt->bindValue(':idNominaEmpleado', $idNominaEmpleado);
        $stmt->bindValue(':empleado_id', $idEmpleado);
        $stmt->bindValue(':idFactura', $row['idFactura'] );
        $stmt->bindValue(':uuid', $row['uuid']);
        $stmt->bindValue(':motivo_cancelacion_id', $motivoCancelacion);
        $stmt->bindValue(':fecha_baja', $date);
        $stmt->bindValue(':usuario_baja', $_SESSION['PKUsuario']);
        $stmt->execute();
      }

      if($conn->commit()){
        $array[$cont]['estatus_ind'] = "exito";
      }else{
        $array[$cont]['estatus_ind'] = "fallo";
        $conn->rollBack();
      }
      $cont++;
    }
}
//fin foreach de empleados

if($estatus_final == 0){
  $array[0]['estatus'] = "exito";
}
else{
  $array[0]['estatus'] = "fallo";
}

$respuesta->resultado = $array;
$respuesta = json_encode($respuesta);
echo $respuesta;

?>
