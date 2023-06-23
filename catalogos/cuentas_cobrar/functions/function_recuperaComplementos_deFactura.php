<?php
session_start();
$ruta_api = "../../../";
$empresa = $_SESSION["IDEmpresa"];
require_once($ruta_api.'include/db-conn.php');

$factura=$_REQUEST['id'];

//recupera el id de los complementos de la factura dentro de facturapi
$query = sprintf('SELECT fp.id_api from facturas_pagos as fp
inner join pagos as p on fp.folio_pago=p.identificador_pago
inner join movimientos_cuentas_bancarias_empresa as m on m.id_pago=p.idpagos
where m.id_factura= '.$factura.' and p.tipo_movimiento=0 and fp.estatus=1 and p.estatus=1 and m.estatus=1 and fp.empresa_id=:empresa;');
$stmt = $conn->prepare($query);
$stmt->bindValue(":empresa",$empresa);
$stmt->execute();

$count=$stmt->rowCount();

$result = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
$stmt->closeCursor();

$data["status"]="";
if($count>0){
    $data["status"]="ok";
    $data["result"]=$result;
}else{
    $data["status"]="alert";
    $data["result"]="Sin complementos de pago";
}

echo json_encode($data);
?>