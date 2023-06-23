<?php
session_start();
$ruta_api = "../../../";
$empresa = $_SESSION["IDEmpresa"];
require_once($ruta_api.'include/db-conn.php');
require_once($ruta_api.'include/functions_api_facturation.php');
require_once $ruta_api.'vendor/facturapi/facturapi-php/src/Facturapi.php';

$factura=$_REQUEST['id'];
$api = new API();

//recuperación de los datos necesarios para facturapi
//se recupera la key de la empresa
$query = sprintf("SELECT key_company_api key_company,key_user_company_api key_user from empresas where PKEmpresa = :id");
$stmt = $conn->prepare($query);
$stmt->bindValue(":id",$empresa);
$stmt->execute();

$key_company_api = $stmt->fetchAll();
$stmt->closeCursor();

//recupera el id de la factura dentro de facturapi
$query = sprintf('SELECT id_api from facturacion where id = :factura and empresa_id= :empresa;');
$stmt = $conn->prepare($query);
$stmt->bindValue(":factura",$factura);
$stmt->bindValue(":empresa",$empresa);
$stmt->execute();

$res=$stmt->rowCount();

$id = $stmt->fetchAll();
$stmt->closeCursor();

$data['status']="";
if($res>0){
    $xml=$api->downloadXmlInvoice($key_company_api[0]['key_company'],$id[0]['id_api']);

    //valida que si se haya ejecutado bien la función y se haya encontrado la factura, si no, se retorna null y al intenar
    //descargar el archivo se generará un error, asi se sabe si se encontró o no la factura.
    $json=json_decode(strval($xml));
    if(isset($json->{'message'})){
        $data['status']="err";
        $data['result']=$json->{'message'};
    }else{
        header("Content-type: application/xml");
        $data['status']="ok";
        echo $xml;
    }
}else{
    $data['status']="err";
    $data['result']="Factura inexistente"; 
}
if($data['status']!='ok'){
    echo json_encode($data);
}
?>