<?php
  session_start();
  date_default_timezone_set('America/Mexico_City');
  include "../../../include/db-conn.php";

  $ruta_api = "../../../";
  include $ruta_api . "include/functions_api_facturation.php";
  require_once $ruta_api . 'vendor/facturapi/facturapi-php/src/Facturapi.php';

  $api = new API();

  $value = $_REQUEST['value'];

  $query = sprintf("select key_company_api from empresas where PKEmpresa = :id");
  $stmt = $conn->prepare($query);
  $stmt->bindValue(":id",$_SESSION['IDEmpresa']);
  $stmt->execute();
  $emp = $stmt->fetchAll();

  $query = sprintf("select id_api,uuid,concat(serie,'-',folio) folio from facturacion where id= :id");
  $stmt = $conn->prepare($query);
  $stmt->bindValue(":id",$value);
  $stmt->execute();
  $fact = $stmt->fetchAll();
  
  $xml = $api->downloadXmlInvoice($emp[0]['key_company_api'],$fact[0]['id_api']);

  header('Content-type: application/xml; charset=utf-8');

  header('Content-Disposition: attachment; filename="' . $fact[0]['folio'] . '.xml"');
    
  header('Content-Transfer-Encoding: binary');
    
  header('Accept-Ranges: bytes');

  echo $xml ;

?>