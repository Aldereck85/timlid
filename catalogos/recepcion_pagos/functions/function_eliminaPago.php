<?php
require_once('../../../include/db-conn.php');
session_start();
$empresa = $_SESSION["IDEmpresa"];
$idPago = $_REQUEST["idPago"];
$stringtoDelete="";
$countToDelete=0;
$from=$_REQUEST["from"];
$flag=true;
$facturasComplemento=(object)[];

try{
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

    //consulta para recuperar las facturas pagadas en el pago a eliminar
    $facturas=$conn->prepare('SELECT m.id_factura as id, 
                                    m.tipo_CuentaCobrar,
                                    m.Deposito, 
                                    if (m.tipo_CuentaCobrar = 2, f.estatus, vd.estatus_cuentaCobrar) as estatus,
                                    m.saldo_insoluto, 
                                    m.parcialidad 
                                from pagos as p 
                                    inner join movimientos_cuentas_bancarias_empresa as m on m.id_pago = p.idpagos
                                    left join facturacion as f on f.id = m.id_factura and m.tipo_CuentaCobrar = 2 and f.prefactura = 0
                                    left join ventas_directas as vd on vd.PKVentaDirecta = m.id_factura and m.tipo_CuentaCobrar = 1 and vd.empresa_id !=6
                                where  p.identificador_pago=:idPago and p.empresa_id=:empresa and p.estatus=1;');
    $facturas->bindValue(":idPago",$idPago);
    $facturas->bindValue(":empresa",$empresa);
    $facturas->execute();
    while (($row = $facturas->fetch()) !== false){
        if(isset($facturasComplemento->{$row['id']}) && $row['parcialidad'] <= $facturasComplemento->{$row['id']} && $row['tipo_CuentaCobrar'] == 2){
            $flag=false;
            $data['status'] = 'err-2';
            break;
        }else{
            if($row['estatus']==3 && $row['saldo_insoluto']!=0){
                $flag=false;
                $data['status'] = 'err-1';
                break;
            }
            $stringtoDelete=$stringtoDelete.$row['id']."-".$row['Deposito'].",";
        }
    }
    if($flag){
        $stringtoDelete=substr($stringtoDelete, 0, -1);
        $countToDelete=$facturas->rowCount();
        $facturas->closeCursor();
        //llamada al procedimiento almacenado para eliminar
        $query=sprintf("CALL spd_EliminarPago_cpc(?,?,?,?);");
        $stmt = $conn->prepare($query);
        $stmt->execute(array($idPago,$stringtoDelete,$countToDelete,$empresa));
        $data['status'] = 'ok';
        //si viene desde la pantalla principal, no genera la variable de sesión con el mensaje a mostrar al redireccionar
        if($from!=1){
            $_SESSION["actualizadoCPC"]=3;
        } 
    }
}catch(Exception $e){
    $data['status'] = 'err';
    $data['msj'] = $e;
}
  
//returns data as JSON format
echo json_encode($data);

?>