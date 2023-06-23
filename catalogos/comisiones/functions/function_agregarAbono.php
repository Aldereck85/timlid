<?php
session_start();
require_once('../../../include/db-conn.php');
/*Guarda una nueva parcialidad*/ 

$id = $_SESSION["PKUsuario"];
$monto_abono = $_REQUEST["montoA"];
$idComision = $_REQUEST["idComision"];
$estatus = $_REQUEST["estatusCom"];
$totalsalin = $_REQUEST["totalsaldo"];
$fechaParcialidad = $_REQUEST["fechaParcialidad"];
$cuenta = $_REQUEST["cuenta"];

if($fechaParcialidad==""){
    $fechaParcialidad = date('y/m/d');
}

//valida que la cuenta recibida si pertenezca a la empresa
$stmt = $conn->prepare('SELECT count(PKCuenta) as PKCuenta from cuentas_bancarias_empresa where PKCuenta = :_cuenta_origen and empresa_id = :empresa_id and estatus = 1');
$stmt->bindValue(":_cuenta_origen",$cuenta);
$stmt->bindValue(":empresa_id",$_SESSION['IDEmpresa']);
$stmt->execute();
$res=$stmt->fetchAll();

if($res[0]['PKCuenta']>0){
    try{
        $conn->beginTransaction();
        $stmt = $conn->prepare('INSERT INTO comision_abonos (fecha_abono, monto_abono, id_usuario_registro, id_comision) 
                                VALUES (:fechaParcialidad, :monto_abono, :idUsuario, :idComision)');
        $stmt->execute(array(":fechaParcialidad" => $fechaParcialidad, ":monto_abono" => $monto_abono, ":idUsuario" => $id, ":idComision" => $idComision));
        $idAbono = $conn->lastInsertId();
    
        $stmt->closeCursor();
    
        $stmt = $conn->prepare('INSERT INTO movimientos_cuentas_bancarias_empresa (`Descripcion`,`Retiro`, `Saldo`, `tipo_movimiento_id`, `cuenta_origen_id`, `cuenta_destino_id`,`FKResponsable`, `estatus`, id_comision_abono, id_comision) values(
                "Pago comision",
                :retiro,
                1234,
                8,
                :cuenta,
                300,
                :idUsuario,
                1,
                :idAbono,
                :idComision
            );');
        $stmt->bindValue(":idComision",$idComision);
        $stmt->bindValue(":idUsuario",$id);
        $stmt->bindValue(":retiro",$monto_abono);
        $stmt->bindValue(":cuenta",$cuenta);
        $stmt->bindValue(":idAbono",$idAbono);
        $stmt->execute();
    
        //se resta el monto de la cuenta ingresada
        $stmt = $conn->prepare('SELECT tipo_cuenta from cuentas_bancarias_empresa where PKCuenta = :_cuenta_origen and empresa_id = :empresa_id');
        $stmt->bindValue(":_cuenta_origen",$cuenta);
        $stmt->bindValue(":empresa_id",$_SESSION['IDEmpresa']);
        $stmt->execute();
    
        $tipoCuenta = $stmt->fetchAll();
    
        if($tipoCuenta[0]['tipo_cuenta'] == 1){
            $stmt = $conn->prepare('UPDATE cuentas_cheques set Saldo_Inicial = Saldo_Inicial - :retiro where FKCuenta = :_cuenta_origen;');
            
        }else if($tipoCuenta[0]['tipo_cuenta'] == 2){
            $stmt = $conn->prepare('UPDATE cuentas_credito set Credito_Utilizado = Credito_Utilizado - :retiro where FKCuenta = :_cuenta_origen;');
    
        }else if($tipoCuenta[0]['tipo_cuenta'] == 3){
            $stmt = $conn->prepare('UPDATE cuentas_otras set Saldo_Inicial = Saldo_Inicial - :retiro where FKCuenta = :_cuenta_origen;');
    
        }else if($tipoCuenta[0]['tipo_cuenta'] == 4){
            $stmt = $conn->prepare('UPDATE cuenta_caja_chica set SaldoInicialCaja = SaldoInicialCaja - :retiro where FKCuenta = :_cuenta_origen;');
    
        }
    
        $stmt->bindValue(":retiro",$monto_abono);
        $stmt->bindValue(":_cuenta_origen",$cuenta);
        $stmt->execute();
        $stmt->closeCursor();
    
        $stmt = $conn->prepare('UPDATE comisiones SET saldo_insoluto = :totalsalin, estatus = :estatus, id_ultimo_usuario_modificacion = :idUsuario, 
                                                      fecha_ultima_modificacion = NOW() WHERE id = :idComision');
        $stmt->bindValue(":totalsalin",$totalsalin);
        $stmt->bindValue(":estatus",$estatus);
        $stmt->bindValue(":idComision",$idComision);
        $stmt->bindValue(":idUsuario",$id);
        $stmt->execute();
        if($stmt->rowCount() > 0) {
            $data['result']=1;
            $data['estatus']=$estatus;
            $conn->commit();
        }
    }catch(Exception $e){
        $conn->rollBack();
        $data['result']=$e;
        }
}else{
    $data['result']="Cuenta bancaria inactiva";
}



echo json_encode($data); 
?>