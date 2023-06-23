<?php
use Facturapi\Facturapi;
require_once '../../../vendor/facturapi/facturapi-php/src/Facturapi.php';

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

$respuesta = new stdClass();

$idNominaEmpleado = $_POST['idNominaEmpleado'];
$idEmpleado = $_POST['idEmpleado'];
$idNomina = $_POST['idNomina'];
$idEmpresa = $_SESSION['IDEmpresa'];
$motivoCancelacion = $_POST['motivoCancelacion'];

$stmt = $conn->prepare("SELECT estadoTimbrado, idFactura, uuid FROM nomina_empleado WHERE PKNomina = :idNominaEmpleado AND FKEmpleado = :idEmpleado AND FKNomina = :idNomina");
$stmt->bindValue(":idNominaEmpleado", $idNominaEmpleado);
$stmt->bindValue(":idEmpleado", $idEmpleado);
$stmt->bindValue(":idNomina", $idNomina);
$stmt->execute();
$row = $stmt->fetch();

if($row['estadoTimbrado'] == 0 || $row['estadoTimbrado'] == 2){
  $respuesta->estatus = "fallo-cancelada";  
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
    $respuesta->estatus = "fallo-cancelado";
    
    $conn->beginTransaction(); 

    $stmt = $conn->prepare('UPDATE nomina_empleado SET estadoTimbrado = 2 WHERE PKNomina = :idNominaEmpleado AND FKNomina = :idNomina AND FKEmpleado = :idEmpleado');
    $stmt->bindValue(':idNominaEmpleado', $idNominaEmpleado);
    $stmt->bindValue(':idNomina', $idNomina);
    $stmt->bindValue(':idEmpleado', $idEmpleado);
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
      $respuesta->estatus = "exito-existe";
    }else{
      $respuesta->estatus = "fallo-existe";
      $conn->rollBack();
    }


    $respuesta = json_encode($respuesta);
    echo $respuesta;
    return;
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
  $stmt->bindValue(':idEmpleado', $idEmpleado);
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
    $respuesta->estatus = "exito";
  }else{
    $respuesta->estatus = "fallo";
    $conn->rollBack();
  }
}

$respuesta = json_encode($respuesta);
echo $respuesta;

?>
