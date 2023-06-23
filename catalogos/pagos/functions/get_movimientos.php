<?php
require_once('../../../include/db-conn.php');
session_start();
$empresa = $_SESSION["IDEmpresa"];
/* $consulta = $_GET["consulta"]; */

/* $consulta = $_GET["toDo"]; */

$toggle;
$pagoid = $_GET["pagoid"];
$proveedorid = $_GET["proveedorid"];
$pagadas=array();
$movimientopago = array();
$all;
$pagadaporOtro=0;
//Consulta todas las cuentas por pagar de la empresa
$all = $conn->prepare("SELECT  pr.NombreComercial, cp.id, folio_factura,num_serie_factura,cp.saldo_insoluto,
subtotal,importe, DATEDIFF(SYSDATE(), fecha_vencimiento) as vencimiento, fecha_vencimiento,estatus_factura 
  FROM cuentas_por_pagar as cp 
    inner join proveedores as pr on pr.PKProveedor = cp.proveedor_id 
     where (cp.estatus_factura = 1 or cp.estatus_factura = 4 or cp.estatus_factura = 2 or cp.estatus_factura = 3 or cp.estatus_factura = 5 or cp.estatus_factura = 6) and (pr.empresa_id = $empresa) and (pr.PKProveedor= $proveedorid);");
  $all->execute();

  //Consulta las que fueron pagadas por el pago actual
$stmt;
  $stmt = $conn->prepare("SELECT  cp.id , mcbe.Retiro 
    FROM cuentas_por_pagar as cp 
      inner join proveedores as pr on pr.PKProveedor = cp.proveedor_id 
      inner join movimientos_cuentas_bancarias_empresa as mcbe on cp.id = mcbe.cuenta_pagar_id
      inner join pagos as pg on pg.idpagos = mcbe.id_pago 
       where (cp.estatus_factura = 1 or cp.estatus_factura = 4 or cp.estatus_factura = 2 or cp.estatus_factura = 3 or cp.estatus_factura = 5 or cp.estatus_factura = 6) 
       and (pr.empresa_id = $empresa) and (pg.tipo_movimiento = 1) and (pg.idpagos =$pagoid) and (mcbe.estatus = 1);");
  $stmt->execute();

  while (($row1 = $stmt->fetch()) !== false) {
    array_push($pagadas,$row1['id']);
    $movimientopago[$row1['id']] = $row1['Retiro'];
    }
    


$table="";
//print_r($pagadas);
while (($row = $all->fetch()) !== false) {

    $row['fecha_vencimiento'] = date("Y-m-d", strtotime($row['fecha_vencimiento']));

    if($row['estatus_factura']==1){
      $row['estatus_factura']= '<span class=\"left-dot green-dot\">Completo</span>';
      $pagadaporOtro = 1;
    }elseif($row['estatus_factura']==0){
      $row['estatus_factura']= '<span class=\"left-dot yellow-dot\">Pendiente</span>';
      $pagadaporOtro = 0;
    }elseif($row['estatus_factura']==2){
      $row['estatus_factura']= '<span class=\"left-dot yellow-light-dot\">Desviación</span>';
      $pagadaporOtro = 2;
    }elseif($row['estatus_factura']==3){
      $row['estatus_factura']= '<span class=\"left-dot primary-dot\">Revisado</span>';
      $pagadaporOtro = 3;
    }elseif($row['estatus_factura']==4){
      $row['estatus_factura']= '<span class=\"left-dot orange-dot\">Parcialmente pagada</span>';
      $pagadaporOtro = 4;
    }elseif($row['estatus_factura']==5){
      $row['estatus_factura']= '<span class=\"left-dot red-dot\">Pagada</span>';
      $pagadaporOtro = 5;
    }elseif($row['estatus_factura']==6){
      $row['estatus_factura']= '<span class=\"left-dot blue-light-dot\">Registro Manual</span>';
      $pagadaporOtro = 6;
    }else{
      $row['estatus_factura']= '<span class=\"left-dot red-dot\">Desconocido</span>';
    }
    $importeold =  $row['saldo_insoluto'];
    $importe =  $row['importe'];
    $row['importe'] = '<div style=\"text-align: right;\">$' .number_format($row['importe'],2).'</div>';
    $row['saldo_insoluto'] = '<div style=\"text-align: right;\">$' .number_format($row['saldo_insoluto'],2).'</div>';
  if($row['vencimiento']>0){
    $row['vencimiento']= '<div style=\"background:#e74a3b;padding:5px;color:white!important;-webkit-border-radius: 16px; -moz-border-radius: 16px; border-radius: 16px;width:90%;\"><center>' .$row['vencimiento']. ' dias'. '</center></div>';
  }elseif($row['vencimiento']==0){
    $row['vencimiento']= '<div style=\"background:#ffc107;padding:5px;color:white!important;-webkit-border-radius: 16px; -moz-border-radius: 16px; border-radius: 16px;width:90%;\"><center>' .$row['vencimiento']. ' dias'. '</center></div>';
  }elseif($row['vencimiento']<0){
    $row['vencimiento']= '<div style=\"background:#1cc88a;padding:5px;color:white!important;-webkit-border-radius: 16px; -moz-border-radius: 16px; border-radius: 16px;width:90%;\"><center>' .abs($row['vencimiento']). ' dias'. '</center></div>';
  }

  //Si el id actual esta en el array de pagadas pone el checked
  if(in_array ($row['id'],$pagadas )){
   //// echo "-". $movimientopago[$row['id']];
    $htmlAcciones = '<input Checked name=\"checks[]\" class=\"check\" onclick=\"sumar(this)\" type=\"checkbox\" value=\"'.$row['id'].','.$movimientopago[$row['id']].','.$importe.'\" id=\"chk\" >';
/* Guardamos en un JSON los datos de la consulta  */
$table.='{"Proveedor":"'.$row['NombreComercial'].
  '","Id":"'.$row['id'].
  '","Folio de Factura":"'.$row['folio_factura'].
  '","Serie de Factura":"'.$row['num_serie_factura'].
  '","Estatus":"'.$row['estatus_factura'].
  '","Fecha de Vencimiento":"'.$row['fecha_vencimiento'].
  '","Importe":"'.$row['importe'].
  '","Saldo insoluto":"'.$row['saldo_insoluto'].
  '","Seleccionar":"'.$htmlAcciones.
  '"},';
  }elseif($pagadaporOtro==5){
    $htmlAcciones = '<input disabled Checked name=\"checks[]\" class=\"check\" onclick=\"sumar(this)\" type=\"checkbox\" value=\"'.$row['id'].','.$importeold.','.$importe.'\" id=\"chk\" >';

    $table.='{"Proveedor":"'.$row['NombreComercial'].
      '","Id":"'.$row['id'].
      '","Folio de Factura":"'.$row['folio_factura'].
      '","Serie de Factura":"'.$row['num_serie_factura'].
      '","Estatus":"'.$row['estatus_factura'].
      '","Fecha de Vencimiento":"'.$row['fecha_vencimiento'].
      '","Importe":"'.$row['importe'].
      '","Saldo insoluto":"'.$row['saldo_insoluto'].
      '","Seleccionar":"'.$htmlAcciones.
      '"},';
  }else{
    $htmlAcciones = '<input name=\"checks[]\" class=\"check\" onclick=\"sumar(this)\" type=\"checkbox\" value=\"'.$row['id'].','.$importeold.','.$importe.'\" id=\"chk\" >';
    /* Guardamos en un JSON los datos de la consulta  */
    $table.='{"Proveedor":"'.$row['NombreComercial'].
      '","Id":"'.$row['id'].
      '","Folio de Factura":"'.$row['folio_factura'].
      '","Serie de Factura":"'.$row['num_serie_factura'].
      '","Estatus":"'.$row['estatus_factura'].
      '","Fecha de Vencimiento":"'.$row['fecha_vencimiento'].
      '","Importe":"'.$row['importe'].
      '","Saldo insoluto":"'.$row['saldo_insoluto'].
      '","Seleccionar":"'.$htmlAcciones.
      '"},';
  }
  
/*   foreach ($pagadas as &$valor) {
    if($valor==$row['id']){
      echo(" true: ". $valor. "-> ". $row['id']);
    }else{
      echo(" false: ".$valor. "-> ". $row['id']);

    }
}
 */

     
    //,"Acciones":"'.'"
  }

  $table = substr($table,0,strlen($table)-1);
	echo '{"data":['.$table.']}';
 ?>