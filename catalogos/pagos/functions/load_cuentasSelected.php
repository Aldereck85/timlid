<?php
require_once('../../../include/db-conn.php');
session_start();
$empresa = $_SESSION["IDEmpresa"];
//$idProveedor = $_GET["id"];
$ids = $_GET["id"];
$_pagoid = $_GET["pago"];
/* $consulta = $_GET["consulta"]; */

$mvDelPago = array();

$Id_ultimas=array();
$idMaxDate = array();
/* $consulta = $_GET["toDo"]; */

$toggle;

$idsM;
//Trae solo los Ids de las cuentas que se va a pintar
$idsM = $conn->prepare("SELECT  mv.cuenta_pagar_id 
  FROM movimientos_cuentas_bancarias_empresa as mv
     where estatus = 1 and mv.cuenta_pagar_id in($ids);");
  $idsM->execute();

  $idsPago = $conn->prepare("SELECT  mv.cuenta_pagar_id 
  FROM movimientos_cuentas_bancarias_empresa as mv
     where estatus = 1 and mv.id_pago in($_pagoid);");
  $idsPago->execute();

$stmt;
  $stmt = $conn->prepare("SELECT  pr.NombreComercial, cp.id, tipo_estatus, folio_factura,num_serie_factura, mv.PKMovimiento,
  subtotal,importe,  DATEDIFF(SYSDATE(), fecha_vencimiento) as vencimiento, fecha_vencimiento,estatus_factura, cp.saldo_insoluto
    FROM cuentas_por_pagar as cp 
    inner join movimientos_cuentas_bancarias_empresa as mv on mv.cuenta_pagar_id = cp.id
      inner join proveedores as pr on pr.PKProveedor = cp.proveedor_id 
      inner join estatus_factura as stat on cp.estatus_factura = stat.id
       where (cp.estatus_factura = 1 or cp.estatus_factura = 4 or cp.estatus_factura = 2 or cp.estatus_factura = 3 or cp.estatus_factura = 6 or cp.estatus_factura = 5) and (mv.id_pago = $_pagoid) and (pr.empresa_id = $empresa) and cp.id in($ids)
       Union 
(SELECT  pr.NombreComercial, cp.id, tipo_estatus, folio_factura,num_serie_factura, null as PKMovimiento, 
  subtotal,importe,  DATEDIFF(SYSDATE(), fecha_vencimiento) as vencimiento, fecha_vencimiento,estatus_factura, cp.saldo_insoluto
    FROM cuentas_por_pagar as cp 
      inner join proveedores as pr on pr.PKProveedor = cp.proveedor_id 
      inner join estatus_factura as stat on cp.estatus_factura = stat.id
       where (cp.estatus_factura = 1 or cp.estatus_factura = 4 or cp.estatus_factura = 2 or cp.estatus_factura = 3 or cp.estatus_factura = 6 or cp.estatus_factura = 5)  and (pr.empresa_id = $empresa) and cp.id in($ids));");


/* $stmt = $conn->prepare("SELECT PKProveedor,  NombreComercial FROM proveedores Where empresa_id = $empresa"); */
/* $stmt = $conn->prepare("SELECT * from (
    Select pr.NombreComercial, PKProveedor, cp.folio_factura, cp.fecha_vencimiento  FROM cuentas_por_pagar as cp inner join proveedores 
                        as pr  ON cp.proveedor_id = pr.PKProveedor order by cp.fecha_vencimiento) as tabale group by NombreComercial"); */
$stmt->execute();


$MaxDate;
  //Consulta la fecha maxima de cada cuenta por pagar afectada por el pago
  //Pone en un array los ids de los movimientos con la fecha maxima de cada cuenta por pagar afectada
  while (($row1 = $idsM->fetch()) !== false) {
    $MaxDate = $conn-> prepare("SELECT * FROM movimientos_cuentas_bancarias_empresa WHERE  estatus = 1 and cuenta_pagar_id = ( ".$row1['cuenta_pagar_id']." ) ORDER by Fecha DESC  limit 1;");
    $MaxDate->execute();
    $Row_idMaxDate= $MaxDate->fetch();
    /////Pone en un array los ids de los movimientos con la fecha maxima de cada cuenta por pagar afectada
    array_push($idMaxDate,$Row_idMaxDate['PKMovimiento']);
  }

  while (($row3 = $idsPago->fetch()) !== false) {
    /////Pone en un array los ids de los movimientos con la fecha maxima de cada cuenta por pagar afectada
    array_push($mvDelPago,$row3['cuenta_pagar_id']);
  }

/* $stmt = $conn->prepare("SELECT id, folio_factura, num_serie_factura, subtotal, importe, fecha_factura, 
fecha_vencimiento,estatus_factura, NombreComercial 
FROM proveedores where Dias_credito between 0 and 30 and NombreComercial = Esteritam");
$stmt->execute(); */
//print($mvDelPago);
$pintar = true;
$table="";
while (($row = $stmt->fetch()) !== false) {
  $pintar = true;
  if($row["PKMovimiento"]==null){
    if(in_array($row['id'],$mvDelPago)){
      $pintar = false;
    }else{
      $pintar = true;
    }
  }
  if($pintar){
    $htmlAcciones = '<input type=\"image\" src=\"../../img/timdesk/delete.svg\" width=\"20px\" heigth=\"20px\" onclick=\"delete_fact('.$row['id'].')\"/>';
    $input='<input class=\"form-control numericDecimal-only\" type=\"text\" name=\"inputs_facturas\" value=\"\" placeholder=\"0\" onchange=\"sumarInputs(this,'.$row['saldo_insoluto'].')\" id=\"'.$row['id'].'\" min=\"1\"> <div class=\"invalid-feedback\" id=\"invalid-input\">gg</div>';
    $row['fecha_vencimiento'] = date("Y-m-d", strtotime($row['fecha_vencimiento']));
    $row['disable'] = ' <input disabled type=\"text\" name=\"txtdisable\" id=\"txtdisable\" value=\"0\">';
    if($row["saldo_insoluto"]<=0){
      //Si el id del movimiento esta en el array de los ultimos continua si hacer nada especifico
      if(in_array ($row['PKMovimiento'],$idMaxDate )){
        //Si no esta en el array de ultimas deshabilita el input y el boton de eliminar
          //El boton eliminar mostrara una alerta y un tooltip
      }else{
        $htmlAcciones = '<input type=\"image\" src=\"../../img/timdesk/delete.svg\" width=\"20px\" heigth=\"20px\" data-placement=\"top\" onclick=\"alerta()\" title=\"Solo puedes editar el ultimo registro de una factura pagada\"/>';
        $input='<input disabled class=\"form-control numericDecimal-only\" type=\"text\" name=\"inputs_facturas\" value=\"\" placeholder=\"0\" onchange=\"sumarInputs(this,'.$row['saldo_insoluto'].')\" id=\"'.$row['id'].'\" min=\"1\"> <div class=\"invalid-feedback\" id=\"invalid-input\">gg</div>';
        $row['disable'] = ' <input disabled type=\"text\" name=\"txtdisable\" id=\"txtdisable\" value=\"'.$row['id'].'\">';
      }
    }
    
    if($row['estatus_factura']==1){
      $row['estatus_factura']= '<span class=\"left-dot green-dot\">Completo</span>';
    }elseif($row['estatus_factura']==0){
      $row['estatus_factura']= '<span class=\"left-dot yellow-dot\">Pendiente</span>';
    }elseif($row['estatus_factura']==2){
      $row['estatus_factura']= '<span class=\"left-dot yellow-light-dot\">Desviación</span>';
    }elseif($row['estatus_factura']==3){
      $row['estatus_factura']= '<span class=\"left-dot primary-dot\">Revisado</span>';
    }elseif($row['estatus_factura']==4){
      $row['estatus_factura']= '<span class=\"left-dot orange-dot\">Parcialmente pagada</span>';
    }elseif($row['estatus_factura']==5){
      $row['estatus_factura']= '<span class=\"left-dot red-dot\">Pagada</span>';
    }elseif($row['estatus_factura']==6){
      $row['estatus_factura']= '<span class=\"left-dot blue-light-dot\">Registro Manual</span>';
    }else{
      $row['estatus_factura']= '<span class=\"left-dot red-dot\">Desconocido</span>';
    }
    $importeold =  $row['importe'];
    $row['importe'] = '<div style=\"text-align: right;\">$' .number_format($row['importe'],2).'</div>';
    $row['saldo_insoluto'] = '<div id=\"S'.$row['id'].'\" style=\"text-align: right;\">$' .number_format($row['saldo_insoluto'],2).'</div>';
    if($row['vencimiento']>0){
      $row['vencimiento']= '<div style=\"background:#e74a3b;padding:5px;color:white!important;-webkit-border-radius: 16px; -moz-border-radius: 16px; border-radius: 16px;width:90%;\"><center>' .$row['vencimiento']. ' dias'. '</center></div>';
    }elseif($row['vencimiento']==0){
      $row['vencimiento']= '<div style=\"background:#ffc107;padding:5px;color:white!important;-webkit-border-radius: 16px; -moz-border-radius: 16px; border-radius: 16px;width:90%;\"><center>' .$row['vencimiento']. ' dias'. '</center></div>';
    }elseif($row['vencimiento']<0){
      $row['vencimiento']= '<div style=\"background:#1cc88a;padding:5px;color:white!important;-webkit-border-radius: 16px; -moz-border-radius: 16px; border-radius: 16px;width:90%;\"><center>' .abs($row['vencimiento']). ' dias'. '</center></div>';
    }
  
 
    /* Guardamos en un JSON los datos de la consulta  */
    $table.='{"Proveedor":"'.$row['NombreComercial'].
        '","Id":"'.$row['id'].
        '","No Edit":"'.$row['disable'].
        '","Folio de Factura":"'.$row['folio_factura'].
        '","Serie de Factura":"'.$row['num_serie_factura'].
        '","Estatus":"'.$row['estatus_factura'].
        '","Fecha de Vencimiento":"'.$row['fecha_vencimiento'].
        '","Importe":"'.$row['importe'].
        '","Saldo insoluto":"'.$row['saldo_insoluto'].
        '","Pago":"'.$input.
      //  '","Saldo insoluto":"'.$row['Saldo_insoluto'].
        '","Acciones":"'.$htmlAcciones.
        '"},'; 
    //,"Acciones":"'.'"
  }
  
  }
  $table = substr($table,0,strlen($table)-1);
	echo '{"data":['.$table.']}';
 ?>