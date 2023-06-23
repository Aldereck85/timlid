<?php
require_once('../../../include/db-conn.php');
session_start();
$empresa = $_SESSION["IDEmpresa"];
$folio = $_POST["folio"];

//comprueba si el pago fue timbrado.
try{
    $smtp=$conn->prepare('SELECT fp.folio_pago from facturas_pagos fp where fp.folio_pago=:folio and fp.estatus != 0 AND fp.empresa_id=:empresa;');
    $smtp->bindValue(":empresa",$empresa);
    $smtp->bindValue(":folio",$folio);
    $smtp->execute();
    $res=$smtp->rowCount();
    if ($res != 0){
        $data['status'] = 'err';
    }else{
        $data['status'] = 'ok';
    }
    $data['result'] ='success';
}catch(Exception $e){
    $data['status'] = 'err';
    $data['result'] =$e->getMessage();
}

//returns data as JSON format
echo json_encode($data); 
 ?>