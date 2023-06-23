<?php
//////// PARA EL MODAL de Editar//// 
//Consulta las cuentas pagadas por el pago actual sin incluir las que estan pagadas por otro pago o las que estan pagadas y el movimiento del pago no es el ultimo
/////
require_once('../../../include/db-conn.php');
session_start();
$empresa = $_SESSION["IDEmpresa"];
$idProveedor = $_GET["id"];
$pagoid = $_GET["pagoid"];
//Cuentas por pagar pagadas por el pago actual.
$pagadas=array();
/// Ids de los movimientos con la fecha maxima de cada cuenta por pagar afectada
$idMaxDate=array();
//Array que guarda las Cuentas por pagar que no se podran editar.
$CPNoEdit = array();
$stringIDsLast = "";
/* $consulta = $_GET["consulta"]; */

/* $consulta = $_GET["toDo"]; */

$toggle;
//Consulta todas las cuentas del proveedor
$stmt;
  $stmt = $conn->prepare("SELECT  pr.NombreComercial, cp.id, tipo_estatus, folio_factura,num_serie_factura,
  subtotal,importe,  DATEDIFF(SYSDATE(), fecha_vencimiento) as vencimiento, fecha_vencimiento,estatus_factura, saldo_insoluto
    FROM cuentas_por_pagar as cp 
      inner join proveedores as pr on pr.PKProveedor = cp.proveedor_id 
      inner join estatus_factura as stat on cp.estatus_factura = stat.id
       where (cp.estatus_factura = 1 or cp.estatus_factura = 4 or cp.estatus_factura = 2 or cp.estatus_factura = 3 or cp.estatus_factura = 5 or cp.estatus_factura = 6) and (pr.empresa_id = $empresa) and (pr.PKProveedor = $idProveedor);");


/* $stmt = $conn->prepare("SELECT PKProveedor,  NombreComercial FROM proveedores Where empresa_id = $empresa"); */
/* $stmt = $conn->prepare("SELECT * from (
    Select pr.NombreComercial, PKProveedor, cp.folio_factura, cp.fecha_vencimiento  FROM cuentas_por_pagar as cp inner join proveedores 
                        as pr  ON cp.proveedor_id = pr.PKProveedor order by cp.fecha_vencimiento) as tabale group by NombreComercial"); */
$stmt->execute();
///Consulta solo las cuentas pagadas por el pago actual 
$pag;
  $pag = $conn->prepare("SELECT  cp.id , mcbe.PKMovimiento
    FROM cuentas_por_pagar as cp 
      inner join proveedores as pr on pr.PKProveedor = cp.proveedor_id 
      inner join movimientos_cuentas_bancarias_empresa as mcbe on cp.id = mcbe.cuenta_pagar_id
      inner join pagos as pg on pg.idpagos = mcbe.id_pago 
       where (cp.estatus_factura = 1 or cp.estatus_factura = 4 or cp.estatus_factura = 2 or cp.estatus_factura = 3 or cp.estatus_factura = 5 or cp.estatus_factura = 6) 
       and (pr.empresa_id = $empresa) and (pg.tipo_movimiento = 1) and (pg.idpagos =$pagoid);");
  $pag->execute();
//Consulta los movimientos del pago
$all = $conn->prepare("SELECT  mv.PKMovimiento, pr.NombreComercial, cp.id, tipo_estatus, folio_factura,num_serie_factura, mv.Retiro,
subtotal,importe,cp.saldo_insoluto, DATEDIFF(SYSDATE(), fecha_vencimiento) as vencimiento, fecha_vencimiento,estatus_factura 
  FROM cuentas_por_pagar as cp 
    inner join proveedores as pr on pr.PKProveedor = cp.proveedor_id 
    inner join estatus_factura as stat on cp.estatus_factura = stat.id
    inner join movimientos_cuentas_bancarias_empresa as mv on mv.cuenta_pagar_id = cp.id
    inner join pagos as pg on pg.idpagos = mv.id_pago
     where (cp.estatus_factura = 1 or cp.estatus_factura = 4 or cp.estatus_factura = 2 or cp.estatus_factura = 3 or cp.estatus_factura = 5) and (pr.empresa_id = $empresa) and (pr.PKProveedor= $idProveedor) and (mv.id_pago = $pagoid);");
  $all->execute();

  $MaxDate;
  //Consulta la fecha maxima de cada cuenta por pagar afectada por el pago
  //Pone en un array los ids de los movimientos con la fecha maxima de cada cuenta por pagar afectada
  while (($row1 = $pag->fetch()) !== false) {
    $MaxDate = $conn-> prepare("SELECT * FROM movimientos_cuentas_bancarias_empresa WHERE cuenta_pagar_id = ( ".$row1['id']." ) ORDER by Fecha DESC  limit 1;");
    $MaxDate->execute();
    $Row_idMaxDate= $MaxDate->fetch();
    /////Pone en un array los ids de los movimientos con la fecha maxima de cada cuenta por pagar afectada
    array_push($idMaxDate,$Row_idMaxDate['PKMovimiento']);
    ///Las que fueron pagadas por el pago actual.
    array_push($pagadas,$row1['id']);
  }
  //Movimientos del pago actual
  while (($row0 = $all->fetch()) !== false) {
    //Si el movimiento del pago actual es el maximo para el id
    if((in_array($row0['PKMovimiento'],$idMaxDate ))){
      //Pone el id de la cuenta por pagar en un array
      array_push($CPNoEdit,$row0['id']);
    }
  }


$table="";
while (($row = $stmt->fetch()) !== false) {
  $htmlAcciones = '<input name=\"checks[]\" class=\"check\" onclick=\"get_ids(this)\" type=\"checkbox\" value=\"'.$row['id'].'\" id=\"'.$row['id'].'\" >';
  //Si NO esta en el array de pagadas y el estatus es 5 NO la pone en la tabla
      ///Es decir: si fue pagada por otro pago
  if(!(in_array ($row['id'],$pagadas )) && $row['estatus_factura']==5){

  }else{
    if(!(in_array($row['id'],$CPNoEdit )) && $row['estatus_factura']==5){

    }else{
      
    
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
        $row['estatus_factura']= '<span class=\"left-dot red-dot\"> Pagada</span>';
      }elseif($row['estatus_factura']==6){
        $row['estatus_factura']= '<span class=\"left-dot blue-light-dot\">Registro Manual</span>';
      }else{
        $row['estatus_factura']= '<span class=\"left-dot red-dot\">Desconocido</span>';
      } 
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
    
  
      /* Guardamos en un JSON los datos de la consulta  */
      $table.='{"Proveedor":"'.$row['NombreComercial'].
          '","Id":"'.$row['id'].
          '","Folio de Factura":"'.$row['folio_factura'].
          '","Serie de Factura":"'.$row['num_serie_factura'].
          '","Estatus":"'.$row['estatus_factura'].
          '","Fecha de Vencimiento":"'.$row['fecha_vencimiento'].
          '","Importe":"'.$row['importe'].
          '","Saldo insoluto":"'.$row['saldo_insoluto'].
        //  '","Saldo insoluto":"'.$row['Saldo_insoluto'].
          '","Acciones":"'.$htmlAcciones.
          '"},'; 
      //,"Acciones":"'.'"
    }
  }
  }
  $table = substr($table,0,strlen($table)-1);
	echo '{"data":['.$table.']}';
 ?>