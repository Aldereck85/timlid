<?php
  session_start();
  date_default_timezone_set('America/Mexico_City');
  include "../../../include/db-conn.php";

  $ruta_api = "../../../";
  include $ruta_api . "include/functions_api_facturation.php";
  require_once $ruta_api . 'vendor/facturapi/facturapi-php/src/Facturapi.php';
  $api = new API();

  $value = $_REQUEST['value'];
  $tipo = $_REQUEST['tipo'];

  $query = sprintf("select key_company_api from empresas where PKEmpresa = :id");
  $stmt = $conn->prepare($query);
  $stmt->bindValue(":id",$_SESSION['IDEmpresa']);
  $stmt->execute();
  $emp = $stmt->fetchAll();

  if($tipo == 1){
    $query = sprintf("select uuid from finiquito where idFactura = :id");
    $stmt = $conn->prepare($query);
    $stmt->bindValue(":id",$value);
    $stmt->execute();
    $fact = $stmt->fetchAll();
  }
  else{
    $query = sprintf("select uuidLiquidacion as uuid from liquidacion where idFacturaLiquidacion = :id");
    $stmt = $conn->prepare($query);
    $stmt->bindValue(":id",$value);
    $stmt->execute();
    $fact = $stmt->fetchAll();
  }

  
  $pdf = $api->downloadPdfInvoice($emp[0]['key_company_api'],$value );    //$fact[0]['id_api']);

  header('Content-type: application/pdf');

  header('Content-Disposition: inline; filename="' . $fact[0]['uuid'] . '.pdf"');
    
  header('Content-Transfer-Encoding: binary');
    
  header('Accept-Ranges: bytes');

  echo $pdf;
?>