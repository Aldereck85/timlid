<?php
/////////
///Consulta las que fueron pagadas por el pago actual y deshabilita el boton de eliminar y el input de las que estan pagadas (saldo insoluto = 0) 
  //y el movimiento del pago actual no es el ultimo.
/////////
require_once('../../../include/db-conn.php');
session_start();
$empresa = $_SESSION["IDEmpresa"];
/* $consulta = $_GET["consulta"]; */

/* $consulta = $_GET["toDo"]; */

$toggle;
$pagoid = $_GET["pagoid"];
$proveedorid = $_GET["proveedorid"];
$tosee = "";
$pagadas=array();
if(isset($_GET["ver"])){
    $tosee = "disabled";
}
//Array que guarda los ids de las cuentas pagadas por el pago
$Id_ultimas=array();
$idMaxDate = array();
$stringIDsLast = "";
$all;
$ultima;
$pagadaporOtro=0;

//Trae solo los Ids de las cuentas que se pagaron en el pago
$idsM = $conn->prepare("SELECT  mv.cuenta_pagar_id 
  FROM movimientos_cuentas_bancarias_empresa as mv
     where estatus = 1 and (mv.id_pago = $pagoid);");
  $idsM->execute();

//Consulta los movimientos del pago
$all = $conn->prepare("SELECT  mv.PKMovimiento, pr.NombreComercial, cp.id, tipo_estatus, folio_factura,num_serie_factura, mv.Retiro,
subtotal,importe,cp.saldo_insoluto, DATEDIFF(SYSDATE(), fecha_vencimiento) as vencimiento, fecha_vencimiento,estatus_factura 
  FROM cuentas_por_pagar as cp 
    inner join proveedores as pr on pr.PKProveedor = cp.proveedor_id 
    inner join estatus_factura as stat on cp.estatus_factura = stat.id
    inner join movimientos_cuentas_bancarias_empresa as mv on mv.cuenta_pagar_id = cp.id
    inner join pagos as pg on pg.idpagos = mv.id_pago
     where (cp.estatus_factura = 1 or cp.estatus_factura = 4 or cp.estatus_factura = 2 or cp.estatus_factura = 3 or cp.estatus_factura = 5) and (pr.empresa_id = $empresa) and (pr.PKProveedor= $proveedorid) and (mv.id_pago = $pagoid);");
  $all->execute();

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

$table="";
//print_r($pagadas);

//REcorre todos los movimientos del pago actual
while (($row = $all->fetch()) !== false) {
  $row['disable'] = ' <input disabled type=\"text\" name=\"txtdisable\" id=\"txtdisable\" value=\"0\">';
  $htmlAcciones = '<i class=\"fas fa-trash-alt pointer\" onclick=\"delete_fact('.$row['id'].')\"></i>';
    $input='<input '.$tosee.' class=\"form-control numericDecimal-only pagoinput\" type=\"text\" name=\"inputs_facturas\" value=\"'.$row['Retiro'].'\" placeholder=\"0\" onchange=\"sumarInputs(this,'.$row['saldo_insoluto'].')\" id=\"'.$row['id'].'\" min=\"1\"> <div class=\"invalid-feedback\" id=\"invalid-input\">gg</div>';
    //Si el saldo insoluto es 0 osea que ya esta pagada no dejara editar a menos que el movimiento sea el ultimo registrado
    if($row["saldo_insoluto"]<=0){
      //Si el id del movimiento esta en el array de los ultimos continua si hacer nada especifico
      if(in_array ($row['PKMovimiento'],$idMaxDate )){
        //Si no esta en el array de ultimas deshabilita el input y el boton de eliminar
          //El boton eliminar mostrara una alerta y un tooltip
      }else{
        $htmlAcciones = '<i class=\"fas fa-trash-alt pointer\" onclick=\"alerta()\"></i>';
        $input='<input disabled class=\"form-control numericDecimal-only pagoinput\" type=\"text\" name=\"inputs_facturas\" value=\"'.$row['Retiro'].'\" placeholder=\"0\" onchange=\"sumarInputs(this,'.$row['saldo_insoluto'].')\" id=\"'.$row['id'].'\" min=\"1\"> <div class=\"invalid-feedback\" id=\"invalid-input\">gg</div>';
        $row['disable'] = ' <input disabled type=\"text\" name=\"txtdisable\" id=\"txtdisable\" value=\"'.$row['id'].'\">';
      }
    }
    $row['fecha_vencimiento'] = date("Y-m-d", strtotime($row['fecha_vencimiento']));
    
    if($row['estatus_factura']==1){
      $pagadaporOtro = 1;
      $row['estatus_factura']= '<span class=\"left-dot green-dot\">Completo</span>';
    }elseif($row['estatus_factura']==0){
      $pagadaporOtro = 0;
      $row['estatus_factura']= '<span class=\"left-dot yellow-dot\">Pendiente</span>';
    }elseif($row['estatus_factura']==2){
      $pagadaporOtro = 2;
      $row['estatus_factura']= '<span class=\"left-dot yellow-light-dot\">Desviación</span>';
    }elseif($row['estatus_factura']==3){
      $pagadaporOtro = 3;
      $row['estatus_factura']= '<span class=\"left-dot primary-dot\">Revisado</span>';
    }elseif($row['estatus_factura']==4){
      $pagadaporOtro = 4;
      $row['estatus_factura']= '<span class=\"left-dot orange-dot\">Parcialmente pagada</span>';
    }elseif($row['estatus_factura']==5){
      $pagadaporOtro = 5;
      $row['estatus_factura']= '<span class=\"left-dot green-dot\"> Pagada</span>';
    }else{
      $row['estatus_factura']= '<span class=\"left-dot red-dot\">Desconocido</span>';
    } 
    $importeold =  $row['importe'];
    $row['importe'] = '<div style=\"text-align: right;\">$' .number_format($row['importe'],2).'</div>';
    $row['saldo_insoluto'] = '<div id=\"S'.$row['id'].'\" style=\"text-align: right;\">$' .number_format($row['saldo_insoluto'],2).'</div>';
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
  '","No Edit":"'.$row['disable'].
  '","Folio de Factura":"'.$row['folio_factura'].
  '","Serie de Factura":"'.$row['num_serie_factura'].
  '","Estatus":"'.$row['estatus_factura'].
  '","Fecha de Vencimiento":"'.$row['fecha_vencimiento'].
  '","Importe":"'.$row['importe'].
  '","Saldo insoluto":"'.$row['saldo_insoluto'].
  '","Pago":"'.$input.
  '","Acciones":"'.$htmlAcciones.
  '"},';
  }
  //print_r($Id_ultimas);
  $table = substr($table,0,strlen($table)-1);
	echo '{"data":['.$table.']}';
 ?>