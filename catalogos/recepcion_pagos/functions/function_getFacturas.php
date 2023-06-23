<?php
require_once('../../../include/db-conn.php');
session_start();
$empresa = $_SESSION["IDEmpresa"];
//función para dar formato a las cantidades
require_once('function_formatoCantidad.php');

$idPago = $_GET["idPago"];
$cliente = $_GET["cliente"];
$tipoCuenta = $_GET["tipoCuenta"];
$tipoCuentaPagada=0;
$pagadas=array();
$facturasComplemento=(object)[];
$importeold=0;

//recupera las facturas que tienen un complemento de pago para cumplir validacion
$smtp=$conn->prepare('SELECT f.id,max(m.parcialidad) as parcialidad from facturacion f
inner join movimientos_cuentas_bancarias_empresa m on m.id_factura=f.id
inner join pagos p on p.idpagos=m.id_pago
inner join facturas_pagos fp on fp.folio_pago=p.identificador_pago
where fp.estatus!=0 and fp.empresa_id=:empresa and f.empresa_id=:empresa2 and f.prefactura = 0 group by f.id;');
$smtp->bindValue(":empresa",$empresa);
$smtp->bindValue(":empresa2",$empresa);
$smtp->execute();

while (($row = $smtp->fetch()) !== false){
    $facturasComplemento->{$row['id']} = $row['parcialidad'];
}
$smtp->closeCursor();

//Consulta las que fueron pagadas por el pago actual
$stmt = $conn->prepare('SELECT  m.id_factura as id, m.tipo_CuentaCobrar FROM pagos as p 
inner join movimientos_cuentas_bancarias_empresa as m on p.idpagos = m.id_pago
left join facturacion as f on f.id = m.id_factura and m.tipo_CuentaCobrar = 2 and f.prefactura = 0 and f.estatus not in (4, 5)
left join ventas_directas as vd on vd.PKVentaDirecta = m.id_factura and m.tipo_CuentaCobrar = 1 and vd.estatus_cuentaCobrar in (1,2,3) and vd.empresa_id !=6
where p.empresa_id = :empresa and p.tipo_movimiento = 0 and p.identificador_pago=:idPago and p.estatus=1;');
$stmt->bindValue(":empresa",$empresa);
$stmt->bindValue(":idPago",$idPago);
$stmt->execute();

while (($row1 = $stmt->fetch()) !== false) {
    $tipoCuentaPagada = $row1['tipo_CuentaCobrar'];
    array_push($pagadas,$row1['id']);
}
$stmt->closeCursor();

$flag = false;
//Consulta todas las cuentas por cobrar del cliente
$query=sprintf("CALL spc_facturasClientePagadas(?,?,?,?);");
$all = $conn->prepare($query);
$all->execute(array($_SESSION['IDEmpresa'],$cliente,$idPago,$tipoCuentaPagada));
$table="";
while (($row = $all->fetch()) !== false) {
    $importeold =  formatoCantidad($row['Deposito']);
    switch($row['metodo_pago']){
        case "1":
            $row['metodo_pago']= 'Pago en Una Exhibición';
        break;
        case "2":
            $row['metodo_pago']= 'Pago Inicial y Parcialidades'; 
        break;
        case "3":
            $row['metodo_pago']= 'Pago en Parcialidades o Diferido'; 
        break;
        case "0":
            $row['metodo_pago']="Sin Método";
        break;                 
    }
    //Si el id de la factura actual esta en el array y pertenece al pago a editar se pone el checked
    $html = "";
    if(in_array ($row['id'],$pagadas)){
        if($row['identificador_pago']==$idPago){
            $flag = true;
            if(($row['estatus']==3 && $row['saldo_insoluto_parcialidad']!=0) || (isset($facturasComplemento->{$row['id']}) && $row['parcialidad'] < $facturasComplemento->{$row['id']})){
                $html = '<input type=\"checkbox\" disabled checked onclick=\"sumar(this)\" name=\"invoiceSelected\" id=\"invoiceSelected\" data-id=\"'.$row['tipoDoc'].'\" value=\"'.$row['id'].'-'.$row['id_fp'].'-'.$row['metodo_pago'].'-'.$importeold.'\"> <label for=\"cbox2\">'.$row['Folio'].'</label>';
            }else{
                $html = '<input type=\"checkbox\" checked onclick=\"sumar(this)\" name=\"invoiceSelected\" id=\"invoiceSelected\" data-id=\"'.$row['tipoDoc'].'\" value=\"'.$row['id'].'-'.$row['id_fp'].'-'.$row['metodo_pago'].'-'.$importeold.'\"> <label for=\"cbox2\">'.$row['Folio'].'</label>';
            }
        }else{
            $flag = false;
        }
    }else{
        $flag = true;
        $html = '<input type=\"checkbox\" onclick=\"sumar(this)\" name=\"invoiceSelected\" id=\"invoiceSelected\" data-id=\"'.$row['tipoDoc'].'\" value=\"'.$row['id'].'-'.$row['id_fp'].'-'.$row['metodo_pago'].'-'.$importeold.'\"> <label for=\"cbox2\">'.$row['Folio'].'</label>';
    }
    if($flag){
        if($row['saldo_anterior']==""){
        $row['saldo_anterior']=$row['saldo_insoluto'];
        }
    
        if($row['parcialidad']==""){
        $row['parcialidad']="0";
        }
    
        //cambiamos formato a las fechas
        $row['Fecha_facturacion']=date("d-m-Y", strtotime($row['Fecha_facturacion']));
        $row['Fecha_vencimiento']=date("d-m-Y", strtotime($row['Fecha_vencimiento']));
    
        $fechaVencimiento=date("Y-m-d", strtotime($row['Fecha_vencimiento']));
        $fechaActual=date("Y-m-d");
  
        if( $fechaActual > $fechaVencimiento){
          $row['Fecha_vencimiento']='<span class=\"badge badge-danger\" style=\"font-size:1rem;font-family: Montserrat, sans-serif\">'.$row['Fecha_vencimiento'].'</span>';
        }else{
          $row['Fecha_vencimiento']='<span class=\"badge badge-success\" style=\"font-size:1rem;font-family: Montserrat, sans-serif\">'.$row['Fecha_vencimiento'].'</span>';
        }

        /* Guardamos en un JSON los datos de la consulta  */
        $row['saldo_anterior']=formatoCantidad($row['saldo_anterior']);
        $row['saldo_insoluto']=formatoCantidad($row['saldo_insoluto']);
        $row['Monto factura']=formatoCantidad($row['Monto factura']);
    
        //asignamos el metodo de pago segun su id
        switch($row['metodo_pago']){
            case "1":
            $row['metodo_pago']="En Una Exhibición";
            break;
            case "2":
            $row['metodo_pago']="Inicial y Parcialidades";
            break;
            case "3":
            $row['metodo_pago']="En Parcialidades o Diferido";
            break;
            case "0":
                $row['metodo_pago']="Sin Método";
            break;
        }
        
        $row['Deposito'] = "$" .formatoCantidad($row['Deposito']);
    
        $table.='{"Folio":"'.$html.'",
            "F de expedicion":"'.$row['Fecha_facturacion'].'",
            "F de vencimiento":"'.$row['Fecha_vencimiento'].'",
            "Metodo de pago":"'.$row['metodo_pago'].'",
            "Monto factura":"$'.$row['Monto factura'].'",
            "Forma de pago":"'.$row['descripcion'].'",
            "Saldo anterior":"$'.$row['saldo_anterior'].'",
            "Saldo insoluto":"$'.$row['saldo_insoluto'].'",
            "No Parcialidad":"'.$row['parcialidad'].'",
            "Seleccionar":""},';
    }


  }

  $table = substr($table,0,strlen($table)-1);
	echo '{"data":['.$table.']}';
 ?>