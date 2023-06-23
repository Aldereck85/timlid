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

  $query = sprintf("SELECT id_Nota_Facturapi,concat(num_serie_nota,'-',folion_nota) folio from notas_cuentas_por_cobrar where id= :id");
  $stmt = $conn->prepare($query);
  $stmt->bindValue(":id",$value);
  $stmt->execute();
  $fact = $stmt->fetchAll();
  
  $zip = $api->downloadZipInvoice($emp[0]['key_company_api'],$fact[0]['id_Nota_Facturapi']);

  header('Content-type: application/zip');

  header('Content-Description: File Transfer');

  header('Content-Disposition: attachment; filename="' . $fact[0]['folio'] . '.zip"');
    
  header('Content-Transfer-Encoding: binary');

  header('Expires: 0');
    
  ob_clean();
  flush();
  
  echo $zip ;

?>