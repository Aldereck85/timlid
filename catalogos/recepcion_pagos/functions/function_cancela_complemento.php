<?php
session_start();
$ruta_api = "../../../";
$empresa = $_SESSION["IDEmpresa"];
require_once($ruta_api.'include/db-conn.php');
require_once($ruta_api.'include/functions_api_facturation.php');
require_once $ruta_api . 'vendor/facturapi/facturapi-php/src/Facturapi.php';

$folioPago=$_REQUEST['folio'];
$motivo = $_REQUEST['motivo'];

$api = new API();

  //valida si el complemento existe
  $query = sprintf('SELECT id_api FROM facturas_pagos where folio_pago=:folioPago AND estatus=1 and empresa_id=:empresa;');
  $stmt = $conn->prepare($query);
  $stmt->bindValue(":folioPago",$folioPago);
  $stmt->bindValue(":empresa",$empresa);
  $stmt->execute();
  $res=$stmt->rowCount();
  $result=$stmt->fetchAll();
  $stmt->closeCursor();

  if($res==1){
    $idComplemento = $result[0]['id_api']; 
    //recuperación de los datos necesarios para facturapi
    //se recupera la key de la empresa
    $query = sprintf("select key_company_api key_company,key_user_company_api key_user from empresas where PKEmpresa = :id");
    $stmt = $conn->prepare($query);
    $stmt->bindValue(":id",$empresa);
    $stmt->execute();

    $key_company_api = $stmt->fetchAll();
    $stmt->closeCursor();
      
      $vacio="";//parametro vacio para la función
      $mensaje = $api->cancelInvoice($key_company_api[0]['key_company'], $idComplemento, $motivo, $vacio);
      $data['status']="";

      if(isset($mensaje->status) && $mensaje->status !== "" && $mensaje->status !== null){
        if($mensaje->status !== "valid"){
          $query = sprintf('UPDATE facturas_pagos as f 
                              set f.estatus = 0 
                            where f.folio_pago = :folio 
                                  and f.empresa_id = :empresa;');

        }else if($mensaje->status == "valid" && $mensaje->cancellation_status == 'pending'){
          $query = sprintf('UPDATE facturas_pagos as f 
                              set f.estatus = 2 
                            where f.folio_pago = :folio 
                                  and f.empresa_id = :empresa;');
        }

        $stmt = $conn->prepare($query);
        $stmt->bindValue(":empresa",$empresa);
        $stmt->bindValue(":folio",  $folioPago);
        
        try{
          $stmt->execute();
          $data['status']="ok";
          $data['result']=$mensaje->status;

        }catch(exception $e){
          $data['status']="err";
          $data['result']="error: ".$e->getMessage();
        }
      }else{
        $data['status']="err";
        $data['result']="Error: ".$mensaje->message;
        $data['error']="El error es: ".$mensaje->message;
      } 
  }else{
    $data['status']="fine";
    $data['result']="inaccesible";
  }

echo json_encode($data);
?>