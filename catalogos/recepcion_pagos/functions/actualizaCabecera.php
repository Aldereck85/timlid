<?php
session_start();
$empresa = $_SESSION["IDEmpresa"];
// Editar actualizar los datos de la pantalla edit
require_once('../../../include/db-conn.php');

    /* Update a la base de datos con los datos del modal */
$idpagos = $_POST['idpagos'];
$txtFecha = $_POST['txtFecha'];
$metodoPago = $_POST['metodoPago'];
$formaPago = $_POST['formaPago'];
$cuenta = $_POST['cuenta'];
$Referencia = trim($_POST['Referencia']);
$txtComentarios = $_POST['txtComentarios'];
$total = floatval($_POST['txtTotal']);

$stmt = $conn->prepare("UPDATE pagos as p 
left join movimientos_cuentas_bancarias_empresa as m on p.idpagos = m.id_pago
inner join facturacion as f on  m.id_factura = f.id
 SET fecha_registro = :txtFecha, tipo_pago = :metodoPago ,comentarios = :txtComentarios ,total = :total ,Referencia = :Referencia ,
 cuenta_origen_id = :cuenta
 where p.tipo_movimiento = 0 and (f.empresa_id=:empresa) and p.idpagos = :idpagos");

$stmt->bindValue(":txtFecha",$txtFecha);
$stmt->bindValue(":metodoPago",$metodoPago);
$stmt->bindValue(":txtComentarios",$txtComentarios);
$stmt->bindValue(":total",$total);
$stmt->bindValue(":Referencia",$Referencia);
$stmt->bindValue(":cuenta",$cuenta);
$stmt->bindValue(":empresa",$empresa);
$stmt->bindValue(":idpagos",$idpagos);

if($stmt->execute()){
    $data['status'] = 'success';
}else{
    $data['status'] = 'err';
}
echo json_encode($data);

?>