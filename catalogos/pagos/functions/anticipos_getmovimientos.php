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
  $stmt = $conn->prepare("SELECT  cp.id, cp.saldo_insoluto ,mcbe.retiro
  FROM cuentas_por_pagar as cp 
    inner join proveedores as pr on pr.PKProveedor = cp.proveedor_id 
    inner join movimientos_cuentas_bancarias_empresa as mcbe on cp.id = mcbe.cuenta_pagar_id
     where  (pr.empresa_id = $empresa) and (mcbe.id_pago =$pagoid);");

$stmt->execute();


$table="";
while (($row = $stmt->fetch()) !== false) {
  $stringpgds = $stringpgds.$row['id'].",".$row['retiro'].",".$row['saldo_insoluto']."-";
  array_push($_pagadas,$row['id'],$row['retiro']);
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