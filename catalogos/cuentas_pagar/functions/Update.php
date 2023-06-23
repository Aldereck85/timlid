<?php
// Editar actualizar los datos de la pantalla edit
require_once('../../../include/db-conn.php');
$action = $_POST["action"];
$id = $_POST['id'];
$query = sprintf("select if(Retiro > 0,sum(Retiro),0) total_pagado from movimientos_cuentas_bancarias_empresa where cuenta_pagar_id = :id");
$stmt = $conn->prepare($query);
$stmt->bindValue(":id",$id);
$stmt->execute();
$arr = $stmt->fetchAll(PDO::FETCH_OBJ);
$total_pagado = 0;
if(count($arr) > 0){
    $total_pagado = $arr[0]->total_pagado;
}
//valida si se puede editar o no
$stmt = $conn->prepare("SELECT estatus_factura,importe from cuentas_por_pagar WHERE (id = '$id')");
$stmt->execute();
$res = $stmt->fetch(PDO::FETCH_ASSOC);
if($res['estatus_factura'] != 5){
if($action== "0"){
    /* Update a la base de datos con los datos del modal */
$id = $_POST['id'];
$precio = $_POST['inputPrecio'];
$inputDescuento = $_POST['inputDescuento'];
$inputIva = $_POST['inputIva'];
$inputCantidad = $_POST['inputCantidad'];
$inputIeps = $_POST['inputIeps'];
$comentarios = $_POST['comentarios'];
$stmt = $conn->prepare("UPDATE detalle_cuentas_por_pagar SET precio = '$precio', descuento = '$inputDescuento' ,
iva = '$inputIva', cantidad = '$inputCantidad', ieps = '$inputIeps', comentarios = '$comentarios'
WHERE (id = '$id')");
$stmt->execute();
$data['status'] = 'ok';
}elseif($action=="1"){
    $id = $_POST['id'];
    $inputSubtotal = $_POST['inputSubtotal'];
    $inputSubtotal = filter_var($inputSubtotal, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $inmputImporte = $_POST['inmputImporte'];
    $inmputImporte = filter_var($inmputImporte, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $inputIva = $_POST['inputIva'];
    $inputIva = filter_var($inputIva, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $inmputIeps = $_POST['inmputIeps'];
    $inmputIeps = filter_var($inmputIeps, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $saldo_insoluto = $inmputImporte - $total_pagado;
    $comentarios = $_POST['comentarios'];

    $stmt = $conn->prepare("UPDATE cuentas_por_pagar SET subtotal = '$inputSubtotal', importe = '$inmputImporte', 
    iva = '$inputIva', ieps = '$inmputIeps', saldo_insoluto = '$saldo_insoluto', comentarios = '$comentarios'   
        WHERE (id = '$id')");
    $stmt->execute();
    $data['status'] = 'ok';
}
}else{
    $data['status'] = 'no';
}

echo json_encode($data);


/* UPDATE `detalle_cuentas_por_pagar` SET `cantidad` = '41' WHERE (`id` = '75');
 */
?>