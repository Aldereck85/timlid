<?php
session_start();
$ruta_api = "../../../";
$empresa = $_SESSION["IDEmpresa"];
require_once($ruta_api.'include/db-conn.php');

$factura=$_REQUEST['id'];

//recupera el id de los complementos de la factura dentro de facturapi
$query = sprintf('SELECT ncpc.id_Nota_Facturapi from notas_cuentas_por_cobrar as ncpc
inner join notas_cuentas_por_cobrar_has_facturacion as ncpcf on ncpc.id=ncpcf.notas_cuentas_por_cobrar_id
inner join facturacion as f on f.id = ncpcf.facturacion_id
where ncpcf.facturacion_id='.$factura.' and f.empresa_id=:empresa and f.prefactura = 0;');
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
    $data["result"]="Sin Notas de crédito";
}

echo json_encode($data);
?>