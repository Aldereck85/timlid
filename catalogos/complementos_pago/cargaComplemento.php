<?php
session_start();
$ruta_api = "../../";
$empresa = $_SESSION["IDEmpresa"];
require_once($ruta_api.'include/db-conn.php');
require_once($ruta_api.'include/functions_api_facturation.php');
require_once $ruta_api.'vendor/facturapi/facturapi-php/src/Facturapi.php';

$folioPago=$_REQUEST['id'];

$api = new API();

//recuperación de los datos necesarios para facturapi
//recupera el id de facturapi
$query = sprintf('SELECT id_api FROM facturas_pagos where folio_pago="'.$folioPago.'" AND estatus=1 and empresa_id="'.$empresa.'";');
$stmt = $conn->prepare($query);
$stmt->execute();
$res=$stmt->rowCount();
$result=$stmt->fetchAll();
$id=$result[0]['id_api'];
$stmt->closeCursor();

if($res>0){
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
}else{
    $data['status']="fine";
    $data['result']="inaccesible";
}

?>