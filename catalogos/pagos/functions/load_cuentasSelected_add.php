<?php
require_once('../../../include/db-conn.php');
session_start();
$empresa = $_SESSION["IDEmpresa"];
//$idProveedor = $_GET["id"];
$ids = $_GET["id"];
/* $consulta = $_GET["consulta"]; */

/* $consulta = $_GET["toDo"]; */

$toggle;


$stmt;
  $stmt = $conn->prepare("SELECT  pr.NombreComercial, cp.id, tipo_estatus, folio_factura,num_serie_factura,
  subtotal,importe,  DATEDIFF(SYSDATE(), fecha_vencimiento) as vencimiento, fecha_vencimiento,estatus_factura, cp.saldo_insoluto
    FROM cuentas_por_pagar as cp 
      inner join proveedores as pr on pr.PKProveedor = cp.proveedor_id 
      inner join estatus_factura as stat on cp.estatus_factura = stat.id
       where (cp.estatus_factura = 1 or cp.estatus_factura = 4 or cp.estatus_factura = 2 or cp.estatus_factura = 3 or cp.estatus_factura = 6 or cp.estatus_factura = 5)  and (pr.empresa_id = $empresa) and cp.id in($ids);");

$stmt->execute();

$pintar = true;
$table="";
while (($row = $stmt->fetch()) !== false) {
  $pintar = true;
  if($pintar){
    $htmlAcciones = '<input type=\"image\" src=\"../../img/timdesk/delete.svg\" width=\"20px\" heigth=\"20px\" onclick=\"delete_fact('.$row['id'].')\"/>';
    $input='<input class=\"form-control numericDecimal-only\" maxlength=\"20\" type=\"text\" name=\"inputs_facturas\" value=\"\" placeholder=\"0\" onchange=\"sumarInputs(this,'.$row['saldo_insoluto'].')\" id=\"'.$row['id'].'\" min=\"1\"> <div class=\"invalid-feedback\" id=\"invalid-input\">gg</div>';
    $row['fecha_vencimiento'] = date("Y-m-d", strtotime($row['fecha_vencimiento']));
    $row['disable'] = ' <input disabled type=\"text\" name=\"txtdisable\" id=\"txtdisable\" value=\"0\">';  
    if($row['estatus_factura']==1){
      $row['estatus_factura']= '<div style=\"background:var(--azul-oscuro);padding:5px;-webkit-border-radius: 16px; -moz-border-radius: 16px; border-radius: 16px;width:90%;\"><center style=\"color:var(--color-claro)!important;\">Pendiente de pago</center></div>';
    }elseif($row['estatus_factura']==0){
      $row['estatus_factura']= '<div style=\"background:var(--azul-oscuro);padding:5px;-webkit-border-radius: 16px; -moz-border-radius: 16px; border-radius: 16px;width:90%;\"><center style=\"color:var(--color-claro)!important;\">Pendiente de pago</center></div>';
    }elseif($row['estatus_factura']==2){
      $row['estatus_factura']= '<div style=\"background:#EFEFA8;padding:5px;-webkit-border-radius: 16px; -moz-border-radius: 16px; border-radius: 16px;width:90%;\"><center style=\"color:var(--color-claro)!important;\">Desviación</center></div>';
    }elseif($row['estatus_factura']==3){
      $row['estatus_factura']= '<div style=\"background:#006dd9;padding:5px;-webkit-border-radius: 16px; -moz-border-radius: 16px; border-radius: 16px;width:90%;\"><center style=\"color:var(--color-claro)!important;\">Revisado</center></div>';
    }elseif($row['estatus_factura']==4){
      $row['estatus_factura']= '<div style=\"background:var(--naranja-claro);padding:5px;-webkit-border-radius: 16px; -moz-border-radius: 16px; border-radius: 16px;width:90%;\"><center style=\"color:var(--color-claro)!important;\">Parcialmente pagada</center></div>';
    }elseif($row['estatus_factura']==5){
      $row['estatus_factura']= '<div style=\"background:#e74a3b;padding:5px;-webkit-border-radius: 16px; -moz-border-radius: 16px; border-radius: 16px;width:90%;\"><center style=\"color:var(--color-claro)!important;\"> Pagada</center></div>';
    }elseif($row['estatus_factura']==6){
      $row['estatus_factura']= '<div style=\"background:#208D90;padding:5px;-webkit-border-radius: 16px; -moz-border-radius: 16px; border-radius: 16px;width:90%;\"><center style=\"color:var(--color-claro)!important;\">Registro Manual</center></div>';
    }else{
      $row['estatus_factura']= '<div style=\"background:#e74a3b;padding:5px;-webkit-border-radius: 16px; -moz-border-radius: 16px; border-radius: 16px;width:90%;\"><center style=\"color:var(--color-claro)!important;\">Desconocido</center></div>';
    } 
    $importeold =  $row['importe'];
    $row['importe'] = '<div style=\"text-align: right;\">$' .number_format($row['importe'],2).'</div>';
    $row['saldo_insoluto'] = '<div id=\"S'.$row['id'].'\" style=\"text-align: right;\">$' .number_format($row['saldo_insoluto'],2).'</div>';
    if($row['vencimiento']>0){
      $row['vencimiento']= '<div style=\"background:#e74a3b;padding:5px;-webkit-border-radius: 16px; -moz-border-radius: 16px; border-radius: 16px;width:90%;\"><center>' .$row['vencimiento']. ' dias'. '</center></div>';
    }elseif($row['vencimiento']==0){
      $row['vencimiento']= '<div style=\"background:#ffc107;padding:5px;-webkit-border-radius: 16px; -moz-border-radius: 16px; border-radius: 16px;width:90%;\"><center>' .$row['vencimiento']. ' dias'. '</center></div>';
    }elseif($row['vencimiento']<0){
      $row['vencimiento']= '<div style=\"background:#1cc88a;padding:5px;-webkit-border-radius: 16px; -moz-border-radius: 16px; border-radius: 16px;width:90%;\"><center>' .abs($row['vencimiento']). ' dias'. '</center></div>';
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

  /* Guardamos en un JSON el HTML del total  */
  $table.='{"Proveedor":"",
    "Id":"",
    "No Edit":"",
    "Folio de Factura":"",
    "Serie de Factura":"",
    "Estatus":"",
    "Fecha de Vencimiento":"",
    "Importe":"",
    "Saldo insoluto":"Total",
    "Pago":"$ <span id=\'total\'>0.00</span>",
    "Acciones":""},'; 
//,"Acciones":"'.'"

  $table = substr($table,0,strlen($table)-1);
	echo '{"data":['.$table.']}';
 ?>