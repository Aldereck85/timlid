<?php
session_start();
$ruta_api = "../../../";
$empresa = $_SESSION["IDEmpresa"];
require_once($ruta_api.'include/db-conn.php');
require_once($ruta_api.'include/functions_api_facturation.php');
require_once $ruta_api.'vendor/facturapi/facturapi-php/src/Facturapi.php';

$idApi=$_REQUEST['id'];

$api = new API();

    //se recupera la key de la empresa
    $query = sprintf("select key_company_api key_company,key_user_company_api key_user from empresas where PKEmpresa = :id");
    $stmt = $conn->prepare($query);
    $stmt->bindValue(":id",$empresa);
    $stmt->execute();

    $key_company_api = $stmt->fetchAll();
    $stmt->closeCursor();
    try{
        $pdf=$api->downloadPdfInvoice($key_company_api[0]['key_company'],$idApi);

        $json=json_decode(strval($pdf));
        if(isset($json->{'message'})){
            $data['status']="err";
            $data['result']=$json->{'message'};
            echo json_encode($data);
        }else{
            /* header("Content-type: application/pdf");
            header('Content-Disposition: inline; filename="' . 'folio' . '.pdf"');
    
            header('Content-Transfer-Encoding: binary');
              
            header('Accept-Ranges: bytes');
            $data['status']="ok"; */
            echo $pdf;
        }   
    }catch(Exception $e){
        echo($e->getMessage());
    }
?>