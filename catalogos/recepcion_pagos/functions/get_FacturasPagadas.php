<?php
require_once('../../../include/db-conn.php');
session_start();
$empresa = $_SESSION["IDEmpresa"];
//función para dar formato a las cantidades
require_once('function_formatoCantidad.php');

$idPago = $_POST["idPago"];
$stringNoEdit="";
$stringpgds ="";
$stringFacCarg='';
$_pagadas=array();
$facturasComplemento=(object)[];

//recupera las facturas que tienen un complemento de pago para cumplir validacion
$smtp=$conn->prepare('SELECT f.id,max(m.parcialidad) as parcialidad from facturacion f
inner join movimientos_cuentas_bancarias_empresa m on m.id_factura=f.id
inner join pagos p on p.idpagos=m.id_pago
inner join facturas_pagos fp on fp.folio_pago=p.identificador_pago
where fp.estatus!=0 and fp.empresa_id=:empresa and f.empresa_id=:empresa2 and f.prefactura = 0 group by f.id;');

$smtp->bindValue(":empresa",$empresa);
$smtp->bindValue(":empresa2",$empresa);
$smtp->execute();

while (($row = $smtp->fetch()) !== false){
  $facturasComplemento->{$row['id']} = $row['parcialidad'];
}

$smtp->closeCursor();
  
$stmt;
  $stmt = $conn->prepare('SELECT t.id_factura as id, 
                                  t.Deposito, 
                                  t.saldo_insoluto, 
                                  if(t.tipo_CuentaCobrar =2, f.estatus, vd.estatus_cuentaCobrar) as estatus,
                                  t.tipo_CuentaCobrar,
                                  t.parcialidad 
                          FROM pagos as p 
                            inner join movimientos_cuentas_bancarias_empresa as t on p.idpagos = t.id_pago
                            left join facturacion as f on t.id_factura=f.id and t.tipo_CuentaCobrar = 2 and f.estatus not in (4,5) and f.prefactura = 0
                            left join ventas_directas as vd on vd.PKVentaDirecta = t.id_factura and t.tipo_CuentaCobrar = 1 and vd.FKEstatusVenta != 5 and vd.empresa_id !=6
                          where p.empresa_id=:empresa and p.identificador_pago=:idPago and p.tipo_movimiento=0 and p.estatus=1;');
$stmt->bindValue(":empresa",$empresa);
$stmt->bindValue(":idPago",$idPago);
$stmt->execute();

while (($row = $stmt->fetch()) !== false) {
  $TipoCuenta = $row['tipo_CuentaCobrar'];
  $stringpgds = $stringpgds.$row['id'].",".formatoCantidad($row['Deposito'])."-";
  $stringFacCarg = $stringFacCarg.$row['id'].",".$row['parcialidad']."-";

  if($row['saldo_insoluto']!=0 && $row['estatus']==3){
    $stringNoEdit = $stringNoEdit.$row['id'].",".$row['parcialidad']."-";
  }else if(isset($facturasComplemento->{$row['id']}) && $row['tipo_CuentaCobrar'] == 2){
    $stringNoEdit = $stringNoEdit.$row['id'].",".$facturasComplemento->{$row['id']}."-";
  }
  array_push($_pagadas,$row['id']);
}
  if(count($_pagadas) > 0){
    $data['status'] = 'ok';
    $data['result'] =trim($stringpgds,'-');
    $data['noEdit'] =trim($stringNoEdit,'-');
    $data['facCarg'] =trim($stringFacCarg,'-');
    $data['tipoCuenta']=$TipoCuenta;

  }else{
      $data['status'] = 'err';
      $data['result'] = '';
  }
//returns data as JSON format
echo json_encode($data); 

 ?>