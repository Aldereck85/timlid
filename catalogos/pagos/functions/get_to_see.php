<?php
require_once('../../../include/db-conn.php');
session_start();
$empresa = $_SESSION["IDEmpresa"];
/* $consulta = $_GET["consulta"]; */

/* $consulta = $_GET["toDo"]; */

$toggle;
$pagoid = $_GET["pagoid"];
$pagadas=array();
$all;
//Consulta todas las cuentas por pagar de la empresa

$stmt;
  $stmt = $conn->prepare("SELECT  pr.NombreComercial, cp.id, tipo_estatus, folio_factura,num_serie_factura,
  subtotal,importe, DATEDIFF(SYSDATE(), fecha_vencimiento) as vencimiento, fecha_vencimiento,estatus_factura 
    FROM cuentas_por_pagar as cp 
      inner join proveedores as pr on pr.PKProveedor = cp.proveedor_id 
      inner join estatus_factura as stat on cp.estatus_factura = stat.id
      inner join movimientos_cuentas_bancarias_empresa as mcbe on cp.id = mcbe.cuenta_pagar_id
      inner join pagos as pg on pg.idpagos = mcbe.id_pago 
       where (cp.estatus_factura = 1 or cp.estatus_factura = 4 or cp.estatus_factura = 2 or (cp.estatus_factura = 3) or (cp.estatus_factura = 5)) 
       and (pr.empresa_id = $empresa) and (pg.tipo_movimiento = 1) and (pg.idpagos =$pagoid);");
  $stmt->execute();
 
  if($stmt->rowCount() == 0){
    $stmt = $conn->prepare("SELECT  pr.NombreComercial, '' as id, 'N/A' as folio_factura,'N/A' as num_serie_factura,total as importe, 'N/A' AS vencimiento, 'N/A' as fecha_vencimiento, 1 as estatus_factura 
      FROM pagos as pg
        inner join movimientos_cuentas_bancarias_empresa as mcbe on pg.idpagos = mcbe.id_pago
        inner join proveedores as pr on pr.PKProveedor = mcbe.FKProveedor 
        where (pr.empresa_id = $empresa) and (pg.tipo_movimiento = 1) and (pg.idpagos =$pagoid);");
  $stmt->execute();
  }


$table="";
//print_r($pagadas);
while (($row = $stmt->fetch()) !== false) {

    if($row['fecha_vencimiento'] != 'N/A'){
      $row['fecha_vencimiento'] = date("Y-m-d", strtotime($row['fecha_vencimiento']));
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
      $row['estatus_factura']= '<span class=\"left-dot green-dot\"> Pagada</span>';
    }else{
      $row['estatus_factura']= '<span class=\"left-dot red-dot\">Desconocido</span>';
    } 
    $importeold =  $row['importe'];
    $row['importe'] = '<div style=\"text-align: right;\">$' .number_format($row['importe'],2).'</div>';
  if($row['vencimiento']>0){
    $row['vencimiento']= '<span sclass=\"left-dot red-dot\">' .$row['vencimiento']. ' dias'. '</span>';
  }elseif($row['vencimiento']==0){
    $row['vencimiento']= '<span class=\"left-dot yellow-dot\">' .$row['vencimiento']. ' dias'. '</span>';
  }elseif($row['vencimiento']<0){
    $row['vencimiento']= '<span class=\"left-dot green-dot\">' .abs($row['vencimiento']). ' dias'. '</span>';
  }
  
/*   foreach ($pagadas as &$valor) {
    if($valor==$row['id']){
      echo(" true: ". $valor. "-> ". $row['id']);
    }else{
      echo(" false: ".$valor. "-> ". $row['id']);

    }
}
 */

    /* Guardamos en un JSON los datos de la consulta  */
    $table.='{"Proveedor":"'.$row['NombreComercial'].
        '","Id":"'.$row['id'].
        '","Folio de Factura":"'.$row['folio_factura'].
        '","Serie de Factura":"'.$row['num_serie_factura'].
        '","Estatus":"'.$row['estatus_factura'].
        '","Fecha de Vencimiento":"'.$row['fecha_vencimiento'].
        '","Importe":"'.$row['importe'].
        '"},'; 
    //,"Acciones":"'.'"
  }

  $table = substr($table,0,strlen($table)-1);
	echo '{"data":['.$table.']}';
 ?>