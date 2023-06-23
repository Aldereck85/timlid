<?php
session_start();
$empresa = $_SESSION["IDEmpresa"];
// Editar actualizar los datos de la pantalla edit
require_once('../../../include/db-conn.php');
$action = $_POST["action"];
if($action== "0"){
    /* Update a la base de datos con los datos del modal */
$idpagos = $_POST['idpagos'];
$proveedorid = $_POST['proveedorid'];
$txtfecha = $_POST['txtfecha'];
$tipopagoid = $_POST['tipopagoid'];
$cuentaid = $_POST['cuentaid'];
$txtreferencia = $_POST['txtreferencia'];
$textareaCoemtarios = $_POST['textareaCoemtarios'];
$total = floatval($_POST['txtTotal']);

$stmt = $conn->prepare("UPDATE pagos as pg 
left join movimientos_cuentas_bancarias_empresa as mcbe on pg.idpagos = mcbe.id_pago
inner join cuentas_por_pagar as cpp on  mcbe.cuenta_pagar_id = cpp.id
inner join proveedores as pv on pv.PKProveedor = cpp.proveedor_id
 SET fecha_registro = '$txtfecha', tipo_pago = '$tipopagoid' ,comentarios = '$textareaCoemtarios' ,total = '$total' ,Referencia = '$txtreferencia' ,
 cuenta_origen_id = '$cuentaid'
 where pg.tipo_movimiento = 1 and (pv.empresa_id=$empresa) and pg.idpagos = $idpagos");
$stmt->execute();
}elseif($action=="1"){

}


/* UPDATE `detalle_cuentas_por_pagar` SET `cantidad` = '41' WHERE (`id` = '75');
 */
?>