<?php
session_start();
$ruta_api = "../../../";
$empresa = $_SESSION["IDEmpresa"];
require_once($ruta_api.'include/db-conn.php');
require_once($ruta_api.'include/functions_api_facturation.php');
require_once $ruta_api . 'vendor/facturapi/facturapi-php/src/Facturapi.php';

$id=$_REQUEST['id'];
$nombre_fichero = 'CP-'.$id.'pdf';
$api = new API();

//recuperación de los datos necesarios para facturapi
//se recupera la key de la empresa
$query = sprintf("select key_company_api key_company,key_user_company_api key_user from empresas where PKEmpresa = :id");
$stmt = $conn->prepare($query);
$stmt->bindValue(":id",$empresa);
$stmt->execute();

$key_company_api = $stmt->fetchAll();
$stmt->closeCursor();

//recupera el id del pago dentro de facturapi
$query = sprintf('SELECT id_api from facturas_pagos where folio_pago = :id and estatus != 0 and empresa_id= :empresa;');
$stmt = $conn->prepare($query);
$stmt->bindValue(":id",$id);
$stmt->bindValue(":empresa",$empresa);
$stmt->execute();

$res=$stmt->rowCount();

$id = $stmt->fetchAll();
$stmt->closeCursor();

if($res>0){
    $xml=$api->downloadXmlInvoice($key_company_api[0]['key_company'],$id[0]['id_api']);
    header('Content-Type: text/xml');
    print($xml);
}else{
    print("err");
}


?>