<?php
require_once('../../../include/db-conn.php');
session_start();
$empresa = $_SESSION["IDEmpresa"];
$idProveedor = $_GET["id"];
if(!($_GET["id"] > 0)){
  $idProveedor = 0;
}
/* $consulta = $_GET["consulta"]; */

/* $consulta = $_GET["toDo"]; */

$toggle;

$stmt;
  $stmt = $conn->prepare("SELECT  pr.NombreComercial, cp.id, tipo_estatus, folio_factura,num_serie_factura, cp.saldo_insoluto,
  subtotal,importe, DATEDIFF(SYSDATE(), fecha_vencimiento) as vencimiento, fecha_vencimiento,estatus_factura 
    FROM cuentas_por_pagar as cp 
      inner join proveedores as pr on pr.PKProveedor = cp.proveedor_id 
      inner join estatus_factura as stat on cp.estatus_factura = stat.id
       where (cp.estatus_factura = 1 or cp.estatus_factura = 4 or cp.estatus_factura = 2 or cp.estatus_factura = 3 or cp.estatus_factura = 6) and (pr.empresa_id = $empresa) and (pr.PKProveedor = $idProveedor);");


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

    $row['fecha_vencimiento'] = date("Y-m-d", strtotime($row['fecha_vencimiento']));
    
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
    $saldoold =  $row['saldo_insoluto'];
  $importeold =  $row['importe'];
  $row['importe'] = '<div style=\"text-align: right;\">$' .number_format($row['importe'],2).'</div>';
  $row['saldo_insoluto'] = '<div style=\"text-align: right;\">$' .number_format($row['saldo_insoluto'],2).'</div>';
  if($row['vencimiento']>0){
    $row['vencimiento']= '<span class=\"left-dot red-dot\">' .$row['vencimiento']. ' dias'. '</span>';
  }elseif($row['vencimiento']==0){
    $row['vencimiento']= '<span class=\"left-dot yellow-dot\">' .$row['vencimiento']. ' dias'. '</span>';
  }elseif($row['vencimiento']<0){
    $row['vencimiento']= '<span class=\"left-dot green-dot\">' .abs($row['vencimiento']). ' dias'. '</span>';
  }
  $htmlAcciones = '<input name=\"checks[]\" class=\"check\" onclick=\"sumar(this)\" type=\"number\" value=\"'.$row['id'].','.$saldoold.'\" id=\"chk\" >';
 
    /* Guardamos en un JSON los datos de la consulta  */
    $table.='{"Proveedor":"'.$row['NombreComercial'].
        '","Id":"'.$row['id'].
        '","Folio de Factura":"'.$row['folio_factura'].
        '","Serie de Factura":"'.$row['num_serie_factura'].
        '","Estatus":"'.$row['estatus_factura'].
        '","Fecha de Vencimiento":"'.$row['fecha_vencimiento'].
        '","Saldo insoluto":"'.$row['saldo_insoluto'].
        '","Importe":"'.$row['importe'].
        '","Acciones":"'.$htmlAcciones.
        '"},'; 
    //,"Acciones":"'.'"
  }
  $table = substr($table,0,strlen($table)-1);
	echo '{"data":['.$table.']}';
 ?>