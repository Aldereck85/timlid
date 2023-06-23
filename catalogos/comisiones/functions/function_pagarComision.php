<?php
session_start();
require_once('../../../include/db-conn.php');
/*Paga una comisión por completo*/

$idComision = $_REQUEST["idComision"];
$id = $_SESSION["PKUsuario"];
$numA = $_REQUEST["numA"];
$montoLiq= $_REQUEST["montoLiq"];
$cuenta= $_REQUEST["cuenta"];

//valida que la cuenta recibida si pertenezca a la empresa
$stmt = $conn->prepare('SELECT count(PKCuenta) from cuentas_bancarias_empresa where PKCuenta = :_cuenta_origen and empresa_id = :empresa_id and estatus = 1');
$stmt->bindValue(":_cuenta_origen",$cuenta);
$stmt->bindValue(":empresa_id",$_SESSION['IDEmpresa']);
$stmt->execute();

if($stmt-> rowCount()>0){
    try{
        $conn->beginTransaction();

        $stmt = $conn->prepare('SELECT tipo_cuenta from cuentas_bancarias_empresa where PKCuenta = :_cuenta_origen and empresa_id = :empresa_id');
        $stmt->bindValue(":_cuenta_origen",$cuenta);
        $stmt->bindValue(":empresa_id",$_SESSION['IDEmpresa']);
        $stmt->execute();

        $tipoCuenta = $stmt->fetchAll();

        if($numA==0) {
            $stmt = $conn->prepare('UPDATE comisiones SET saldo_insoluto = 0, estatus = 2, id_ultimo_usuario_modificacion = :idUsuario, fecha_ultima_modificacion = NOW() WHERE id = :idComision');
            $stmt->bindValue(":idComision",$idComision);
            $stmt->bindValue(":idUsuario",$id);
            $stmt->execute();
            $c=$stmt->rowCount();

            $stmt = $conn->prepare('INSERT INTO movimientos_cuentas_bancarias_empresa (`Descripcion`,`Retiro`, `Saldo`, `tipo_movimiento_id`, `cuenta_origen_id`, `cuenta_destino_id`,`FKResponsable`, `estatus`, id_comision_abono, id_comision) values(
                                    "Pago comision",
                                    :retiro,
                                    1234,
                                    8,
                                    :cuenta,
                                    300,
                                    :idUsuario,
                                    1,
                                    0,
                                    :idComision
                                );');
            $stmt->bindValue(":idComision",$idComision);
            $stmt->bindValue(":idUsuario",$id);
            $stmt->bindValue(":retiro",$montoLiq);
            $stmt->bindValue(":cuenta",$cuenta);
            $stmt->execute();

            $stmt->closeCursor();

            $data['result']=1;
        } else if($numA>0) {
            $stmt = $conn->prepare('INSERT INTO comision_abonos (fecha_abono, monto_abono, id_usuario_registro, id_comision) 
                                    VALUES (CURRENT_DATE(), :monto_abono, :idUsuario, :idComision)');
            $stmt->execute(array(":monto_abono" => $montoLiq, ":idUsuario" => $id, ":idComision" => $idComision));
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
            $stmt->bindValue(":retiro",$montoLiq);
            $stmt->bindValue(":cuenta",$cuenta);
            $stmt->bindValue(":idAbono",$idAbono);
            $stmt->execute();

            $stmt = $conn->prepare('UPDATE comisiones SET saldo_insoluto = 0, estatus = 2, id_ultimo_usuario_modificacion = :idUsuario, fecha_ultima_modificacion = NOW() WHERE id = :idComision');
            $stmt->bindValue(":idComision",$idComision);
            $stmt->bindValue(":idUsuario",$id);
            $stmt->execute();
            $c=$stmt->rowCount();
            $data['result']=2;
        }

        //se resta el monto de la cuenta ingresada
        if($tipoCuenta[0]['tipo_cuenta'] == 1){
            $stmt = $conn->prepare('UPDATE cuentas_cheques set Saldo_Inicial = Saldo_Inicial - :retiro where FKCuenta = :_cuenta_origen;');
            
        }else if($tipoCuenta[0]['tipo_cuenta'] == 2){
            $stmt = $conn->prepare('UPDATE cuentas_credito set Credito_Utilizado = Credito_Utilizado - :retiro where FKCuenta = :_cuenta_origen;');

        }else if($tipoCuenta[0]['tipo_cuenta'] == 3){
            $stmt = $conn->prepare('UPDATE cuentas_otras set Saldo_Inicial = Saldo_Inicial - :retiro where FKCuenta = :_cuenta_origen;');

        }else if($tipoCuenta[0]['tipo_cuenta'] == 4){
            $stmt = $conn->prepare('UPDATE cuenta_caja_chica set SaldoInicialCaja = SaldoInicialCaja - :retiro where FKCuenta = :_cuenta_origen;');

        }

        $stmt->bindValue(":retiro",$montoLiq);
        $stmt->bindValue(":_cuenta_origen",$cuenta);
        $stmt->execute();
        $stmt->closeCursor();

        if($c > 0) {
            $data['id']=$id;
            $data['estatus']=2;
            $conn->commit();
        }
        
    }catch(Exception $e){
        //$data['estatus']='err';
        $conn->rollBack();
        $data['result']=$e;
    }
}else{
    $data['result']="Cuenta bancaria inactiva";
}
echo json_encode($data); 
?>