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
$idFiniquito = $_POST['idFiniquito']; 
$motivoCancelacion = $_POST['motivoCancelacion'];
$tipoEliminacion = $_POST['tipoEliminacion'];


//obtener el id de la factura de finiquito si existe
$stmt = $conn->prepare("SELECT estadoTimbrado, idFactura, uuid, empleado_id FROM finiquito WHERE id = :idFiniquito");
$stmt->bindValue(":idFiniquito", $idFiniquito);
$stmt->execute();
$row = $stmt->fetch();
$idEmpleado = $row['empleado_id'];

//cuando se tiene finiquito sin timbrar
if($tipoEliminacion == 5){
  if($row['estadoTimbrado'] == 0){

    try{    
          $conn->beginTransaction(); 

          $stmt = $conn->prepare('DELETE FROM finiquito WHERE id = :idFiniquito');
          $stmt->bindValue(':idFiniquito', $idFiniquito);
          $stmt->execute();

          $stmt = $conn->prepare('UPDATE empleados SET estatus = 1 WHERE PKEmpleado = :empleado_id');
          $stmt->bindValue(':empleado_id', $idEmpleado);
          $stmt->execute();

          if($conn->commit()){
            $respuesta->estatus = "exito";
          }else{
            $respuesta->estatus = "fallo";
          }

    }
    catch (PDOException $ex) {
        $conn->rollBack(); 
        $respuesta->estatus = "fallo"; // echo $ex->getMessage();
    }
    
  }
}

//cuando se tiene finiquito timbrado
if($tipoEliminacion == 6){

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
    //cancelar factura de finiquito
    if($row['estadoTimbrado'] == 1){

        $peticion = $facturapi->Invoices->cancel($row['idFactura'],  
        [
        "motive" => $motivoCancelacionDesc
        ]
        );

        if(!isset($peticion->status)){
          if($peticion->message == "No puedes cancelar una factura con estatus \"canceled\"."){
            $respuesta->estatus = "fallo-cancelado";

            $conn->beginTransaction(); 

            $stmt = $conn->prepare('UPDATE finiquito SET estadoTimbrado = 2 WHERE id = :idFiniquito');
            $stmt->bindValue(':idFiniquito', $idFiniquito);
            $stmt->execute();
               
            $stmt = $conn->prepare('UPDATE empleados SET estatus = 1 WHERE PKEmpleado = :empleado_id');
            $stmt->bindValue(':empleado_id', $idEmpleado);
            $stmt->execute();

            $stmt = $conn->prepare("SELECT id FROM bitacora_cfdi_eliminado_finiquito_liquidacion WHERE finiquito_id = :idFiniquito AND tipo = 1");
            $stmt->bindValue(":idFiniquito", $idFiniquito);
            $stmt->execute();
            $rowExisteBitacora = $stmt->rowCount();

            if($rowExisteBitacora < 1){
              $date = date("Y-m-d H:i:s");
              $stmt = $conn->prepare('INSERT INTO bitacora_cfdi_eliminado_finiquito_liquidacion (finiquito_id, idFactura, uuid, motivo_cancelacion_id, fecha_baja, usuario_baja, tipo)  VALUES (:finiquito_id, :idFactura, :uuid, :motivo_cancelacion_id, :fecha_baja, :usuario_baja, 1)');
              $stmt->bindValue(':finiquito_id', $idFiniquito);
              $stmt->bindValue(':idFactura', $row['idFactura'] );
              $stmt->bindValue(':uuid', $row['uuid']);
              $stmt->bindValue(':motivo_cancelacion_id', $motivoCancelacion);
              $stmt->bindValue(':fecha_baja', $date);
              $stmt->bindValue(':usuario_baja', $_SESSION['PKUsuario']);
              $stmt->execute();
            }

            $conn->commit();


            $respuesta = json_encode($respuesta);
            echo $respuesta;
            return;
          }
        }

        if($peticion->status == "canceled"){
              try{    
                $conn->beginTransaction(); 

                $stmt = $conn->prepare('UPDATE finiquito SET estadoTimbrado = 2 WHERE id = :idFiniquito');
                $stmt->bindValue(':idFiniquito', $idFiniquito);
                $stmt->execute();
                   
                $stmt = $conn->prepare('UPDATE empleados SET estatus = 1 WHERE PKEmpleado = :empleado_id');
                $stmt->bindValue(':empleado_id', $idEmpleado);
                $stmt->execute();

                $date = date("Y-m-d H:i:s");
                $stmt = $conn->prepare('INSERT INTO bitacora_cfdi_eliminado_finiquito_liquidacion (finiquito_id, idFactura, uuid, motivo_cancelacion_id, fecha_baja, usuario_baja, tipo)  VALUES (:finiquito_id, :idFactura, :uuid, :motivo_cancelacion_id, :fecha_baja, :usuario_baja, 1)');
                $stmt->bindValue(':finiquito_id', $idFiniquito);
                $stmt->bindValue(':idFactura', $row['idFactura'] );
                $stmt->bindValue(':uuid', $row['uuid']);
                $stmt->bindValue(':motivo_cancelacion_id', $motivoCancelacion);
                $stmt->bindValue(':fecha_baja', $date);
                $stmt->bindValue(':usuario_baja', $_SESSION['PKUsuario']);
                $stmt->execute();

                if($conn->commit()){
                  $respuesta->estatus = "exito";
                  $respuesta->mensaje = "Se cancelo el CFDI del finiquito y se inactivo.";
                }else{
                  $respuesta->estatus = "fallo";
                }
              }
              catch (PDOException $ex) {
                  $conn->rollBack(); //echo $ex;
                  $respuesta->estatus = "fallo"; // echo $ex->getMessage();
              }
        }
    }
    if($row['estadoTimbrado'] == 2){
      $respuesta->estatus = "fallo-cancelado";
    }

}

//cuando se tiene liquidacion sin timbrar y finiquito timbrado
if($tipoEliminacion == 7){

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

    //cancelar factura de finiquito
    if($row['estadoTimbrado'] == 1){

        $peticion = $facturapi->Invoices->cancel($row['idFactura'],  
        [
        "motive" => $motivoCancelacionDesc
        ]
        );

        if(!isset($peticion->status)){
          if($peticion->message == "No puedes cancelar una factura con estatus \"canceled\"."){
            $respuesta->estatus = "fallo-cancelado";
            $respuesta = json_encode($respuesta);
            echo $respuesta;
            return;
          }
        }

        if($peticion->status == "canceled"){

              try{    
                $conn->beginTransaction(); 

                $stmt = $conn->prepare('UPDATE finiquito SET estadoTimbrado = 2 WHERE id = :idFiniquito');
                $stmt->bindValue(':idFiniquito', $idFiniquito);
                $stmt->execute();

                $stmt = $conn->prepare('UPDATE liquidacion SET estadoTimbradoLiquidacion = 3 WHERE finiquito_id = :idFiniquito');
                $stmt->bindValue(':idFiniquito', $idFiniquito);
                $stmt->execute();
                   
                $stmt = $conn->prepare('UPDATE empleados SET estatus = 1 WHERE PKEmpleado = :empleado_id');
                $stmt->bindValue(':empleado_id', $idEmpleado);
                $stmt->execute();

                $date = date("Y-m-d H:i:s");
                $stmt = $conn->prepare('INSERT INTO bitacora_cfdi_eliminado_finiquito_liquidacion (finiquito_id, idFactura, uuid, motivo_cancelacion_id, fecha_baja, usuario_baja, tipo)  VALUES (:finiquito_id, :idFactura, :uuid, :motivo_cancelacion_id, :fecha_baja, :usuario_baja, 1)');
                $stmt->bindValue(':finiquito_id', $idFiniquito);
                $stmt->bindValue(':idFactura', $row['idFactura'] );
                $stmt->bindValue(':uuid', $row['uuid']);
                $stmt->bindValue(':motivo_cancelacion_id', $motivoCancelacion);
                $stmt->bindValue(':fecha_baja', $date);
                $stmt->bindValue(':usuario_baja', $_SESSION['Usuario']);
                $stmt->execute();

                if($conn->commit()){
                  $respuesta->estatus = "exito";
                  $respuesta->mensaje = "Se cancelo el CFDI del finiquito y se inactivo la liquidación.";
                }else{
                  $respuesta->estatus = "fallo";
                }
              }
              catch (PDOException $ex) {
                  $conn->rollBack(); 
                  $respuesta->estatus = "fallo"; // echo $ex->getMessage();
              }
        }
    }
    if($row['estadoTimbrado'] == 2){
      $respuesta->estatus = "fallo-cancelado";
    }

}

//cuando se tiene liquidacion y ninguno esta timbrado
if($tipoEliminacion == 8){

    try{    
      $conn->beginTransaction(); 

      $stmt = $conn->prepare('DELETE FROM liquidacion WHERE finiquito_id = :idFiniquito');
      $stmt->bindValue(':idFiniquito', $idFiniquito);
      $stmt->execute();

      $stmt = $conn->prepare('DELETE FROM finiquito WHERE id = :idFiniquito');
      $stmt->bindValue(':idFiniquito', $idFiniquito);
      $stmt->execute();

      if($conn->commit()){
        $respuesta->estatus = "exito";
        $respuesta->mensaje = "Se ha eliminado la liquidación.";
      }else{
        $respuesta->estatus = "fallo";
      }
    }
    catch (PDOException $ex) {
        $conn->rollBack(); 
        $respuesta->estatus = "fallo"; // echo $ex->getMessage();
    } 

}

//cuando se tiene liquidacion y finiquito timbrado
if($tipoEliminacion == 9){

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

    //cancelar factura de finiquito
    if($row['estadoTimbrado'] == 1){

        $peticion = $facturapi->Invoices->cancel($row['idFactura'],  
        [
        "motive" => $motivoCancelacionDesc
        ]
        );

        if(!isset($peticion->status)){
          if($peticion->message == "No puedes cancelar una factura con estatus \"canceled\"."){
            $respuesta->estatus = "fallo-cancelado";

            try{    
              $conn->beginTransaction(); 

              $stmt = $conn->prepare('UPDATE finiquito SET estadoTimbrado = 2 WHERE id = :idFiniquito');
              $stmt->bindValue(':idFiniquito', $idFiniquito);
              $stmt->execute();
                 
              $stmt = $conn->prepare('UPDATE empleados SET estatus = 1 WHERE PKEmpleado = :empleado_id');
              $stmt->bindValue(':empleado_id', $idEmpleado);
              $stmt->execute();

              $stmt = $conn->prepare("SELECT id FROM bitacora_cfdi_eliminado_finiquito_liquidacion WHERE finiquito_id = :idFiniquito AND tipo = 1");
              $stmt->bindValue(":idFiniquito", $idFiniquito);
              $stmt->execute();
              $rowExisteBitacora = $stmt->rowCount();

              if($rowExisteBitacora < 1){
                $date = date("Y-m-d H:i:s");
                $stmt = $conn->prepare('INSERT INTO bitacora_cfdi_eliminado_finiquito_liquidacion (finiquito_id, idFactura, uuid, motivo_cancelacion_id, fecha_baja, usuario_baja, tipo)  VALUES (:finiquito_id, :idFactura, :uuid, :motivo_cancelacion_id, :fecha_baja, :usuario_baja, 1)');
                $stmt->bindValue(':finiquito_id', $idFiniquito);
                $stmt->bindValue(':idFactura', $row['idFactura'] );
                $stmt->bindValue(':uuid', $row['uuid']);
                $stmt->bindValue(':motivo_cancelacion_id', $motivoCancelacion);
                $stmt->bindValue(':fecha_baja', $date);
                $stmt->bindValue(':usuario_baja', $_SESSION['PKUsuario']);
                $stmt->execute();
              }

              $conn->commit();
            }
            catch (PDOException $ex) {
                $conn->rollBack(); //echo $ex;
            }
          }
        }
        else{

            if($peticion->status == "canceled"){

                  try{    
                    $conn->beginTransaction(); 

                    $stmt = $conn->prepare('UPDATE finiquito SET estadoTimbrado = 2 WHERE id = :idFiniquito');
                    $stmt->bindValue(':idFiniquito', $idFiniquito);
                    $stmt->execute();
                       
                    $stmt = $conn->prepare('UPDATE empleados SET estatus = 1 WHERE PKEmpleado = :empleado_id');
                    $stmt->bindValue(':empleado_id', $idEmpleado);
                    $stmt->execute();

                    $date = date("Y-m-d H:i:s");
                    $stmt = $conn->prepare('INSERT INTO bitacora_cfdi_eliminado_finiquito_liquidacion (finiquito_id, idFactura, uuid, motivo_cancelacion_id, fecha_baja, usuario_baja, tipo)  VALUES (:finiquito_id, :idFactura, :uuid, :motivo_cancelacion_id, :fecha_baja, :usuario_baja, 1)');
                    $stmt->bindValue(':finiquito_id', $idFiniquito);
                    $stmt->bindValue(':idFactura', $row['idFactura'] );
                    $stmt->bindValue(':uuid', $row['uuid']);
                    $stmt->bindValue(':motivo_cancelacion_id', $motivoCancelacion);
                    $stmt->bindValue(':fecha_baja', $date);
                    $stmt->bindValue(':usuario_baja', $_SESSION['PKUsuario']);
                    $stmt->execute();

                    if($conn->commit()){
                      $respuesta->estatus = "exito";
                      $respuesta->mensaje = "Se cancelo el CFDI del finiquito y se inactivo la liquidación.";
                    }else{
                      $respuesta->estatus = "fallo";
                    }
                  }
                  catch (PDOException $ex) {
                      $conn->rollBack(); 
                      $respuesta->estatus = "fallo"; // echo $ex->getMessage();
                  }
            }
        }
    }

    if($row['estadoTimbrado'] == 2){
      $respuesta->estatus = "fallo-cancelado";
    }

    $stmt = $conn->prepare("SELECT estadoTimbrado FROM finiquito WHERE id = :idFiniquito");
    $stmt->bindValue(":idFiniquito", $idFiniquito);
    $stmt->execute();
    $rowEstatusActual = $stmt->fetch();

    if($rowEstatusActual['estadoTimbrado'] != 2){
      $respuesta->estatus = "fallo-cancelado-finiquito";
      $respuesta = json_encode($respuesta);
      echo $respuesta;
      return;
    }

    //obtener el id de la factura de finiquito si existe
    $stmt = $conn->prepare("SELECT estadoTimbradoLiquidacion, idFacturaLiquidacion, uuidLiquidacion FROM liquidacion WHERE finiquito_id = :idFiniquito");
    $stmt->bindValue(":idFiniquito", $idFiniquito);
    $stmt->execute();
    $rowLiquidacion = $stmt->fetch();

    $facturapiLiquidacion = new Facturapi($key_company_api[0]['key_company']);

    //cancelar factura de finiquito
    if($rowLiquidacion['estadoTimbradoLiquidacion'] == 1){

        $peticionLiq = $facturapiLiquidacion->Invoices->cancel($rowLiquidacion['idFacturaLiquidacion'],  
        [
        "motive" => $motivoCancelacionDesc
        ]
        );

        if(!isset($peticionLiq->status)){
          if($peticionLiq->message == "No puedes cancelar una factura con estatus \"canceled\"."){
            $respuesta->estatus_liquidacion = "fallo-cancelado";
            $respuesta = json_encode($respuesta);
            echo $respuesta;
            return;
          }
        }

        if($peticionLiq->status == "canceled"){

              try{    
                $conn->beginTransaction(); 

                $stmt = $conn->prepare('UPDATE liquidacion SET estadoTimbradoLiquidacion = 2 WHERE finiquito_id = :idFiniquito');
                $stmt->bindValue(':idFiniquito', $idFiniquito);
                $stmt->execute();
                   
                $stmt = $conn->prepare('UPDATE empleados SET estatus = 1 WHERE PKEmpleado = :empleado_id');
                $stmt->bindValue(':empleado_id', $idEmpleado);
                $stmt->execute();

                $date = date("Y-m-d H:i:s");
                $stmt = $conn->prepare('INSERT INTO bitacora_cfdi_eliminado_finiquito_liquidacion (finiquito_id, idFactura, uuid, motivo_cancelacion_id, fecha_baja, usuario_baja, tipo)  VALUES (:finiquito_id, :idFactura, :uuid, :motivo_cancelacion_id, :fecha_baja, :usuario_baja, 2)');
                $stmt->bindValue(':finiquito_id', $idFiniquito);
                $stmt->bindValue(':idFactura', $rowLiquidacion['idFacturaLiquidacion'] );
                $stmt->bindValue(':uuid', $rowLiquidacion['uuidLiquidacion']);
                $stmt->bindValue(':motivo_cancelacion_id', $motivoCancelacion);
                $stmt->bindValue(':fecha_baja', $date);
                $stmt->bindValue(':usuario_baja', $_SESSION['PKUsuario']);
                $stmt->execute();

                if($conn->commit()){
                  $respuesta->estatus_liquidacion = "exito";
                  $respuesta->mensaje_liquidacion = "Se cancelo el CFDI de la liquidación y se inactivo la liquidación.";
                }else{
                  $respuesta->estatus_liquidacion = "fallo";
                }
              }
              catch (PDOException $ex) {
                  $conn->rollBack(); 
                  $respuesta->estatus_liquidacion = "fallo"; // echo $ex->getMessage();
              }
        }
    }
    if($rowLiquidacion['estadoTimbradoLiquidacion'] == 2){
      $respuesta->estatus_liquidacion = "fallo-cancelado";
    }
    


}
/*
if($row['estadoTimbrado'] == 1){
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

    $respuesta = $facturapi->Invoices->cancel($row['idFactura'],  
    [
    "motive" => $motivoCancelacionDesc
    ]
    );

    if($respuesta->status == "canceled"){


        try{    
          $conn->beginTransaction(); 

          $stmt = $conn->prepare('UPDATE finiquito SET estadoTimbrado = 2, motivo_cancelacion_id = :motivo_cancelacion_id WHERE id = :idFiniquito');
          $stmt->bindValue(':motivo_cancelacion_id', $motivoCancelacion);
          $stmt->bindValue(':idFiniquito', $idFiniquito);
          $stmt->execute();
             
          $stmt = $conn->prepare('UPDATE empleados SET estatus = 1 WHERE PKEmpleado = :empleado_id');
          $stmt->bindValue(':empleado_id', $idEmpleado);
          $stmt->execute();

          if($conn->commit()){
            $respuesta->estatus = "exito-1";
          }else{
            $respuesta->estatus = "fallo";
          }
        }
        catch (PDOException $ex) {
            $conn->rollBack(); 
            $respuesta->estatus = "fallo"; // echo $ex->getMessage();
        }
    }
}

if($row['estadoTimbrado'] == 2){
  $respuesta->estatus = "fallo-cancelado";
}
*/
$respuesta = json_encode($respuesta);
echo $respuesta;

?>
