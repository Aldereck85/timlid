<?php
//////// PARA EL MODAL de agregar//// 
///Consulta todas las cuentas por pagar que aun no son pagadas para el proveedor
/////////
require_once('../../../include/db-conn.php');
session_start();
$empresa = $_SESSION["IDEmpresa"];
$idProveedor = $_GET["id"];

$toggle;
//Consulta todas las cuentas del proveedor
$stmt;
  $stmt = $conn->prepare("SELECT  pr.NombreComercial, cp.id, tipo_estatus, folio_factura,num_serie_factura,
  subtotal,importe,  DATEDIFF(SYSDATE(), fecha_vencimiento) as vencimiento, fecha_vencimiento,estatus_factura, saldo_insoluto
    FROM cuentas_por_pagar as cp 
      inner join proveedores as pr on pr.PKProveedor = cp.proveedor_id 
      inner join estatus_factura as stat on cp.estatus_factura = stat.id
       where (cp.estatus_factura = 1 or cp.estatus_factura = 4 or cp.estatus_factura = 2 or cp.estatus_factura = 3 or cp.estatus_factura = 5 or cp.estatus_factura = 6) and (pr.empresa_id = $empresa) and (pr.PKProveedor = $idProveedor);");
$stmt->execute();

$table="";
while (($row = $stmt->fetch()) !== false) {
  $htmlAcciones = '<input name=\"checks[]\" class=\"check\" onclick=\"get_ids(this)\" type=\"checkbox\" value=\"'.$row['id'].'\" id=\"'.$row['id'].'\" >';
  //Si NO esta en el array de pagadas y el estatus es 5 NO la pone en la tabla
      ///Es decir: si fue pagada por otro pago
  if($row['estatus_factura']==5){

  }else{  
      $row['fecha_vencimiento'] = date("Y-m-d", strtotime($row['fecha_vencimiento']));
      
      if($row['estatus_factura']==1){
        $row['estatus_factura']= '<div class=\"btn-table-custom--blue-light\" style=\"background:var(--azul-oscuro);padding:5px;-webkit-border-radius: 16px; -moz-border-radius: 16px; border-radius: 16px;width:90%;\"><center style=\"color:var(--color-claro)!important;\">Pendiente de pago</center></div>';
      }elseif($row['estatus_factura']==0){
        $row['estatus_factura']= '<div class=\"btn-table-custom--blue-light\" style=\"background:var(--azul-oscuro);padding:5px;-webkit-border-radius: 16px; -moz-border-radius: 16px; border-radius: 16px;width:90%;\"><center style=\"color:var(--color-claro)!important;\">Pendiente de pago</center></div>';
      }elseif($row['estatus_factura']==2){
        $row['estatus_factura']= '<div class=\"btn-table-custom--blue-light\" style=\"background:#EFEFA8;padding:5px;-webkit-border-radius: 16px; -moz-border-radius: 16px; border-radius: 16px;width:90%;\"><center style=\"color:var(--color-claro)!important;\">Desviación</center></div>';
      }elseif($row['estatus_factura']==3){
        $row['estatus_factura']= '<div class=\"btn-table-custom--blue-light\" style=\"background:#006dd9;padding:5px;-webkit-border-radius: 16px; -moz-border-radius: 16px; border-radius: 16px;width:90%;\"><center style=\"color:var(--color-claro)!important;\">Revisado</center></div>';
      }elseif($row['estatus_factura']==4){
        $row['estatus_factura']= '<div class=\"btn-table-custom--blue-light\" style=\"background:var(--naranja-claro);padding:5px;-webkit-border-radius: 16px; -moz-border-radius: 16px; border-radius: 16px;width:90%;\"><center style=\"color:var(--color-claro)!important;\">Parcialmente pagada</center></div>';
      }elseif($row['estatus_factura']==5){
        $row['estatus_factura']= '<div class=\"btn-table-custom--blue-light\" style=\"background:#e74a3b;padding:5px;-webkit-border-radius: 16px; -moz-border-radius: 16px; border-radius: 16px;width:90%;\"><center style=\"color:var(--color-claro)!important;\"> Pagada</center></div>';
      }elseif($row['estatus_factura']==6){
        $row['estatus_factura']= '<div class=\"btn-table-custom--blue-light\" style=\"background:#208D90;padding:5px;-webkit-border-radius: 16px; -moz-border-radius: 16px; border-radius: 16px;width:90%;\"><center style=\"color:var(--color-claro)!important;\">Registro Manual</center></div>';
      }else{
        $row['estatus_factura']= '<div class=\"btn-table-custom--blue-light\" style=\"background:#e74a3b;padding:5px;-webkit-border-radius: 16px; -moz-border-radius: 16px; border-radius: 16px;width:90%;\"><center style=\"color:var(--color-claro)!important;\">Desconocido</center></div>';
      } 
      $importeold =  $row['importe'];
      $row['importe'] = '<div style=\"text-align: right;\">$' .number_format($row['importe'],2).'</div>';
      $row['saldo_insoluto'] = '<div style=\"text-align: right;\">$' .number_format($row['saldo_insoluto'],2).'</div>';
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
          '","Folio de Factura":"'.$row['folio_factura'].
          '","Serie de Factura":"'.$row['num_serie_factura'].
          '","Estatus":"'.$row['estatus_factura'].
          '","Fecha de Vencimiento":"'.$row['fecha_vencimiento'].
          '","Importe":"'.$row['importe'].
          '","Saldo insoluto":"'.$row['saldo_insoluto'].
        //  '","Saldo insoluto":"'.$row['Saldo_insoluto'].
          '","Agregar":"'.$htmlAcciones.
          '"},'; 
      //,"Acciones":"'.'"
    }
  }
  $table = substr($table,0,strlen($table)-1);
	echo '{"data":['.$table.']}';
 ?>