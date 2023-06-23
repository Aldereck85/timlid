<?php
require_once('../../../include/db-conn.php');
session_start();
$empresa = $_SESSION["IDEmpresa"];
/* $consulta = $_GET["consulta"]; */

/* $consulta = $_GET["toDo"]; */

$toggle;
$pagoid = $_POST["idpagos"];
$pagadas=array();
$_pagadas=array();
$stringpgds ="";

$stmt;
  $stmt = $conn->prepare("SELECT  cp.id, importe
    FROM cuentas_por_pagar as cp 
      inner join proveedores as pr on pr.PKProveedor = cp.proveedor_id 
      inner join estatus_factura as stat on cp.estatus_factura = stat.id
      inner join movimientos_cuentas_bancarias_empresa as mcbe on cp.id = mcbe.cuenta_pagar_id
      inner join pagos as pg on pg.idpagos = mcbe.id_pago 
       where (cp.estatus_factura = 1 or cp.estatus_factura = 4 or cp.estatus_factura = 2 or cp.estatus_factura = 3 or cp.estatus_factura = 5) 
       and (pr.empresa_id = $empresa) and (pg.tipo_movimiento = 1) and (pg.idpagos =$pagoid);");

$stmt->execute();


$table="";
while (($row = $stmt->fetch()) !== false) {
  $stringpgds = $stringpgds.$row['id'].",".$row['importe']."-";
  array_push($_pagadas,$row['id'],$row['importe']);
  array_push($pagadas,$_pagadas);
  }
/*   print_r($pagadas);  */
  if(count($pagadas) > 0){
    $userData = $pagadas;
    $data['status'] = 'ok';
    $data['result'] =trim($stringpgds,'-');
  }else{
      $data['status'] = 'err '.$pagadas;
      $data['result'] = '';
  }
  
//returns data as JSON format
echo json_encode($data);

 ?>