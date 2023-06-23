<?php
require_once('../../../include/db-conn.php');
session_start();
$empresa = $_SESSION["IDEmpresa"];
/* $consulta = $_GET["consulta"]; */

/* $consulta = $_GET["toDo"]; */

$toggle;

$stmt;
  $stmt = $conn->prepare("SELECT  pr.NombreComercial, cp.id, tipo_estatus, folio_factura,num_serie_factura,
  subtotal,importe,fecha_factura, DATEDIFF(SYSDATE(), fecha_vencimiento) as vencimiento, fecha_vencimiento,estatus_factura, cp.saldo_insoluto,sum(if(mcb.estatus = 1,mcb.Retiro,0)) total_pagado
    from cuentas_por_pagar as cp 
      inner join proveedores as pr on pr.PKProveedor = cp.proveedor_id 
      inner join estatus_factura as stat on cp.estatus_factura = stat.id
      left join movimientos_cuentas_bancarias_empresa mcb on cp.id = mcb.cuenta_pagar_id
       where pr.empresa_id = $empresa and cp.estatus_factura != 7
       group by cp.id
       order by fecha_captura desc;");


/* $stmt = $conn->prepare("SELECT PKProveedor,  NombreComercial FROM proveedores Where empresa_id = $empresa"); */
/* $stmt = $conn->prepare("SELECT * from (
    Select pr.NombreComercial, PKProveedor, cp.folio_factura, cp.fecha_vencimiento  FROM cuentas_por_pagar as cp inner join proveedores 
                        as pr  ON cp.proveedor_id = pr.PKProveedor order by cp.fecha_vencimiento) as tabale group by NombreComercial"); */
$stmt->execute();


/* $stmt = $conn->prepare("SELECT id, folio_factura, num_serie_factura, subtotal, importe, fecha_factura, 
fecha_vencimiento,estatus_factura, NombreComercial 
FROM proveedores where Dias_credito between 0 and 30 and NombreComercial = Esteritam");
$stmt->execute(); */


$table="";
while (($row = $stmt->fetch()) !== false) {

    if($row['fecha_vencimiento']){
      $row['fecha_vencimiento'] = date("Y-m-d", strtotime($row['fecha_vencimiento']));
    }else{
      $row['fecha_vencimiento'] = "Desconocida";
      $row['vencimiento'] = false;
    }
    
    $row['fecha_factura'] = date("Y-m-d", strtotime($row['fecha_factura']));
    
    if($row['estatus_factura']==1){
      $row['estatus_factura']= '<span class=\"left-dot blue-light-dot\">Pendiente de pago</span>';
    }elseif($row['estatus_factura']==0){
      $row['estatus_factura']= '<span class=\"left-dot blue-light-dot\">Pendiente de pago</span>';
    }elseif($row['estatus_factura']==2){
      $row['estatus_factura']= '<span class=\"left-dot turquoise-dot\">Desviación</span>';
    }elseif($row['estatus_factura']==3){
      $row['estatus_factura']= '<span class=\"left-dot gray-dot\"> Revisado por finanzas</span>';
    }elseif($row['estatus_factura']==4){
      $row['estatus_factura']= '<span class=\"left-dot orange-dot\">Parcialmente pagada</span>';
    }elseif($row['estatus_factura']==5){
      $row['estatus_factura']= '<span class=\"left-dot green-dot\">Pagada</span>';
      $row['vencimiento']= '<span> - </span>';
    }elseif($row['estatus_factura']==6){
      $row['estatus_factura']= '<span class=\"left-dot yellow-dot\">Registro Manual</span>';
    } 
    $row['importe'] = '<div style=\"text-align: right;\">$' .number_format($row['importe'],2).'</div>';
    $row['total_pagado'] = '<div style=\"text-align: right;\">$' .number_format($row['total_pagado'],2).'</div>';
    $row['saldo_insoluto'] = '<div style=\"text-align: right;\">$' .number_format($row['saldo_insoluto'],2).'</div>';
  if($row['vencimiento']>0){
    $row['vencimiento']= '<span class=\"left-dot red-dot\">' .$row['vencimiento']. ' dias'. '</span>';
  }elseif($row['vencimiento']===0){
    $row['vencimiento']= '<div class=\"left-dot yellow-dot\">Hoy</div>';
  }elseif($row['vencimiento']<0){
    $row['vencimiento']= '<div class=\"left-dot green-dot\">' .abs($row['vencimiento']). ' dias'. '</div>';
  }elseif($row['vencimiento']==false){
    $row['vencimiento']= '<div class=\"left-dot gray-dot\">Desconocido</div>';
  }
  /* $acciones = '<input type=\"hidden\" id=\"hddId-'.$row['id'].'\">'; */

  $nombreComercial = str_replace('"', '\"', $row['NombreComercial']);

  $nombreComercial = '<a class=\"pointer\" href=\"editar.php?id=' . $row['id'] . '\">' . $nombreComercial . '</a>';

    /* Guardamos en un JSON los datos de la consulta  */
    $table.='{"Proveedor":"'.$nombreComercial.
        '","Id":"'.$row['id'].
        '","Folio de Factura":"'.$row['folio_factura'].
        '","Fecha de Factura":"'.$row['fecha_factura'].
        '","Estatus":"'.$row['estatus_factura'].
        '","Fecha de Vencimiento":"'.$row['fecha_vencimiento'].
        '","Importe":"'.$row['importe'].
        '","saldo_insoluto":"'.$row['saldo_insoluto'].
        '","Vencimiento":"'.$row['vencimiento'].
        '","total_pagado":"'.$row['total_pagado'].
        '"},'; 
    //,"Acciones":"'.'"
  }
  $table = substr($table,0,strlen($table)-1);
	echo '{"data":['.$table.']}';
 ?>