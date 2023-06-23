<?php
session_start();
$ruta_api = "../../";
$empresa = $_SESSION["IDEmpresa"];
require_once($ruta_api.'include/db-conn.php');
require_once($ruta_api.'include/functions_api_facturation.php');
$id=$_REQUEST['id'];
$api = new API();

//recuperación de los datos necesarios para facturapi
//se recupera la key de la empresa
$query = sprintf("select key_company_api key_company,key_user_company_api key_user from empresas where PKEmpresa = :id");
$stmt = $conn->prepare($query);
$stmt->bindValue(":id",$empresa);
$stmt->execute();

$key_company_api = $stmt->fetchAll();
$stmt->closeCursor();
try{
    $pdf=$api->downloadPdfInvoice($key_company_api[0]['key_company'],$id);
    header("Content-type: application/pdf");
    echo($pdf);    
}catch(Exception $e){
    echo($e->getMessage());
}
?>