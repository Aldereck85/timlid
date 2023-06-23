<?php
session_start();
use Facturapi\Facturapi;

$ruta_api = "../../../";
$empresa = $_SESSION["IDEmpresa"];
require_once($ruta_api.'include/db-conn.php');
require_once($ruta_api.'include/functions_api_facturation.php');
require_once $ruta_api . 'vendor/facturapi/facturapi-php/src/Facturapi.php';
$api = new API();

$folio = $_REQUEST['folio'];
$idNew=$_REQUEST['ComplementNew'];
$idOld = $_REQUEST['ComplementOld'];

    $stmt=$conn->prepare('SELECT idpagos from pagos where identificador_pago = :folio and tipo_movimiento = 0 and estatus = 1 and empresa_id = :empresa');
    $stmt->bindValue(":folio",$folio);
    $stmt->bindValue(":empresa",$empresa);
    $stmt->execute();
    $idPago = $stmt->fetchAll();
    $stmt->closeCursor();

    //se recupera la key de la empresa
    $query = sprintf("select key_company_api key_company,key_user_company_api key_user from empresas where PKEmpresa = :id");
    $stmt = $conn->prepare($query);
    $stmt->bindValue(":id",$empresa);
    $stmt->execute();
    $key_company_api = $stmt->fetchAll();
    $stmt->closeCursor();

    $facturapi = new Facturapi($key_company_api[0]['key_company']);

    $mensaje=$facturapi->Invoices->cancel(
        $idOld,
        [
        "motive" => "01",
        "substitution" => $idNew
        ]
    );

    $data['status']="";

    if(isset($mensaje->status)&& $mensaje->status !== "valid" && $mensaje->status !== "" && $mensaje->status !== null){
      $data['status']="ok";
      $data['result']=$mensaje->status;

      //elimina los datos anteriores del pago de las tablas temporales
      try {  
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      
        $conn->beginTransaction();
          $query = sprintf("DELETE from pagos_data_temporal where idpagos = :id");
          $stmt = $conn->prepare($query);
          $stmt->bindValue(":id",$idPago[0]['idpagos']);
          $stmt->execute();
          $stmt->closeCursor();
    
          $query = sprintf("DELETE from detalle_Pago_Temporal where id_pago = :id");
          $stmt = $conn->prepare($query);
          $stmt->bindValue(":id",$idPago[0]['idpagos']);
          $stmt->execute();
          $stmt->closeCursor();
        $conn->commit();
      } catch (Exception $e) {
        $conn->rollBack();
        $data['status'] = 'err-deleteDatos';
        $data['result'] = "Fallo: " . $e->getMessage();
      }
    }else{
      $data['status']="err";
      $data['result']="Error: ".$mensaje->message;
      $data['error']="El error es: ".$mensaje->message;

      //si falla la sustitución se cancela el nuevo complemento y se activa el viejo y se reestablecen los datos del pago.
      $mensaje = $api->cancelInvoice($key_company_api[0]['key_company'], $idNew, "02");
      try {  
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      
        $conn->beginTransaction();
          $query = sprintf('UPDATE facturas_pagos set estatus = 0 where id_api=:idNew AND estatus=1 and empresa_id=:empresa;');
          $stmt = $conn->prepare($query);
          $stmt->bindValue(":empresa",$empresa);
          $stmt->bindValue(":idNew",$idNew);
          $stmt->execute();
          $stmt->closeCursor();
    
          $query = sprintf('UPDATE facturas_pagos set estatus = 1 where id_api=:idOld and empresa_id=:empresa;');
          $stmt = $conn->prepare($query);
          $stmt->bindValue(":empresa",$empresa);
          $stmt->bindValue(":idOld",$idOld);
          $stmt->execute();
          $stmt->closeCursor();
          
        $data['result-Rollback'] = 'factura RollBack';
      } catch (Exception $e) {
        $conn->rollBack();
        $data['result-Rollback'] = "Fallo: " . $e->getMessage();
      }
      
    }

echo json_encode($data);
?>