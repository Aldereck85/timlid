<?php
session_start();
require_once('../../../include/db-conn.php');
/*elimina una parcialidad*/ 

$id = $_SESSION["PKUsuario"];
$idAbono = $_REQUEST["idAbono"];
$estatus = $_REQUEST["estatus"];
$totalsalins = $_REQUEST["totalsalins"];
$idComision = $_REQUEST["idComision"];

//valida que sea un abono de la empresa
$stmt=$conn->prepare('SELECT count(c.id_empresa) as id_empresa FROM comisiones c inner join comision_abonos ca on ca.id_comision = c.id where ca.id=:idAbono and c.id_empresa = :id_empresa');
$stmt->bindValue(":idAbono",$idAbono);
$stmt->bindValue(":id_empresa",$_SESSION['IDEmpresa']);

$stmt->execute();

$res=$stmt->fetchAll();

if($res[0]['id_empresa']>0){

    try{
        $conn->beginTransaction();
        //suma importe al saldo de la cuenta bancaria
        $stmt=$conn->prepare('SELECT m.retiro, m.cuenta_origen_id, cb.tipo_cuenta FROM movimientos_cuentas_bancarias_empresa m inner join cuentas_bancarias_empresa cb on cb.PKCuenta = m.cuenta_origen_id where m.id_comision_abono=:idAbono');
        $stmt->bindValue(":idAbono",$idAbono);
        $stmt->execute();

        $res2 = $stmt->fetchAll();

        if($res2[0]['tipo_cuenta'] == 1){
            $stmt = $conn->prepare('UPDATE cuentas_cheques set Saldo_Inicial = Saldo_Inicial + :retiro where FKCuenta = :_cuenta_origen;');
            
        }else if($res2[0]['tipo_cuenta'] == 2){
            $stmt = $conn->prepare('UPDATE cuentas_credito set Credito_Utilizado = Credito_Utilizado + :retiro where FKCuenta = :_cuenta_origen;');
    
        }else if($res2[0]['tipo_cuenta'] == 3){
            $stmt = $conn->prepare('UPDATE cuentas_otras set Saldo_Inicial = Saldo_Inicial + :retiro where FKCuenta = :_cuenta_origen;');
    
        }else if($res2[0]['tipo_cuenta'] == 4){
            $stmt = $conn->prepare('UPDATE cuenta_caja_chica set SaldoInicialCaja = SaldoInicialCaja + :retiro where FKCuenta = :_cuenta_origen;');
    
        }

        $stmt->bindValue(":retiro",$res2[0]['retiro']);
        $stmt->bindValue(":_cuenta_origen",$res2[0]['cuenta_origen_id']);
        $stmt->execute();
        $stmt->closeCursor();

        //eliminar abono
        $stmt=$conn->prepare('DELETE FROM comision_abonos where id=:idAbono');
        $stmt->bindValue(":idAbono",$idAbono);
        $stmt->execute();

        $stmt->closeCursor();

        $stmt=$conn->prepare('DELETE FROM movimientos_cuentas_bancarias_empresa where id_comision_abono=:idAbono');
        $stmt->bindValue(":idAbono",$idAbono);
        $stmt->execute();

        $stmt->closeCursor();

        $stmt = $conn->prepare('UPDATE comisiones SET saldo_insoluto = :totalsalins, estatus = :estatus, id_ultimo_usuario_modificacion = :idUsuario, 
                                                        fecha_ultima_modificacion = NOW() WHERE id = :idComision');
        $stmt->bindValue(":totalsalins",$totalsalins);
        $stmt->bindValue(":estatus",$estatus);
        $stmt->bindValue(":idComision",$idComision);
        $stmt->bindValue(":idUsuario",$id);
        $stmt->execute();
        if($stmt->rowCount() > 0) {
            $data['estatus']=$estatus;
            $data['result']=1;
            $conn->commit();
        }
    }catch(Exception $e){
        //$data['estatus']='err';
        $conn->rollBack();
        $data['result']=$e;
        }
}else{
    $data['result']="Comision inexistente";
}

echo json_encode($data); 
?>