<?php
session_start();
require_once('../../../include/db-conn.php');
/*Cancela un pago de una comisión*/ 


$idComision = $_REQUEST["idComision"];
$id = $_SESSION["PKUsuario"];


try{
    $conn->beginTransaction();

    //suma importe al saldo de la cuenta bancaria
    $stmt=$conn->prepare('SELECT m.retiro, m.cuenta_origen_id, cb.tipo_cuenta FROM movimientos_cuentas_bancarias_empresa m inner join cuentas_bancarias_empresa cb on cb.PKCuenta = m.cuenta_origen_id where m.id_comision=:idComision');
    $stmt->bindValue(":idComision",$idComision);
    $stmt->execute();

    $res = $stmt->fetchAll();

    if($res[0]['tipo_cuenta'] == 1){
        $stmt = $conn->prepare('UPDATE cuentas_cheques set Saldo_Inicial = Saldo_Inicial + :retiro where FKCuenta = :_cuenta_origen;');
        
    }else if($res[0]['tipo_cuenta'] == 2){
        $stmt = $conn->prepare('UPDATE cuentas_credito set Credito_Utilizado = Credito_Utilizado + :retiro where FKCuenta = :_cuenta_origen;');

    }else if($res[0]['tipo_cuenta'] == 3){
        $stmt = $conn->prepare('UPDATE cuentas_otras set Saldo_Inicial = Saldo_Inicial + :retiro where FKCuenta = :_cuenta_origen;');

    }else if($res[0]['tipo_cuenta'] == 4){
        $stmt = $conn->prepare('UPDATE cuenta_caja_chica set SaldoInicialCaja = SaldoInicialCaja + :retiro where FKCuenta = :_cuenta_origen;');

    }

    $stmt->bindValue(":retiro",$res[0]['retiro']);
    $stmt->bindValue(":_cuenta_origen",$res[0]['cuenta_origen_id']);
    $stmt->execute();
    $stmt->closeCursor();

    
    $stmt = $conn->prepare('SELECT monto_ingresado FROM comisiones WHERE id=:idComision');
    $stmt->bindValue(":idComision",$idComision);
    $stmt->execute();
    $mc = $stmt->fetch(PDO::FETCH_ASSOC);
    $monto_comision = $mc['monto_ingresado'];

    $stmt->closeCursor();

    $stmt=$conn->prepare('DELETE FROM comision_abonos where id_comision=:idComision');
    $stmt->bindValue(":idComision",$idComision);
    $stmt->execute();

    $stmt->closeCursor();

    $stmt=$conn->prepare('DELETE FROM movimientos_cuentas_bancarias_empresa where id_comision=:idComision');
    $stmt->bindValue(":idComision",$idComision);
    $stmt->execute();

    $stmt->closeCursor();

    $stmt = $conn->prepare('UPDATE comisiones SET saldo_insoluto = :monto_comision, estatus = 1, id_ultimo_usuario_modificacion = :idUsuario, 
                                                    fecha_ultima_modificacion = NOW() WHERE id = :idComision');
    $stmt->bindValue(":monto_comision",$monto_comision);
    $stmt->bindValue(":idComision",$idComision);
    $stmt->bindValue(":idUsuario",$id);
    $stmt->execute();

    if($stmt->rowCount() > 0) {
        $data['result']=1;
        $data['estatus']=1;
        $data['monto_comision']=$monto_comision;
        $conn->commit();
    }
}catch(Exception $e){
    $conn->rollBack();
    $data['result']=$e;
    }

echo json_encode($data); 
?>