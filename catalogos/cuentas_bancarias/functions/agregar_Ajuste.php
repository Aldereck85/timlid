<?php
session_start();
if (isset($_SESSION["Usuario"])) {
    require_once '../../../include/db-conn.php';

    $id = (int) $_POST['idCuentaActual'];
    $cantidadAjuste = $_POST['cantidadAjuste'];
    $tipoAjuste = $_POST['tipoAjuste'];
    $txtFechaAjuste = $_POST['txtFechaAjuste'];
    $observacionesAjsute = $_POST['observacionesAjsute'];
    $uCuenta = false;
    //Seleccionar el saldo de la cuenta caja chica para hacer la resta
    $stmt = $conn->prepare('SELECT * FROM  cuenta_caja_chica WHERE FKCuenta = :fkcuenta');
    $stmt->bindValue(':fkcuenta', $id, PDO::PARAM_INT);
    $stmt->execute();
    $rowC = $stmt->fetch();
    $saldoInicial = $rowC['SaldoInicialCaja'];

    if ($tipoAjuste == "positivo") {
        $saldoFinal = $saldoInicial + $cantidadAjuste;
        $retDep = "Deposito";
    } else {
        $saldoFinal = $saldoInicial - $cantidadAjuste;
        $retDep = "Retiro";
    }
    //UPDATE DE LA CUNETA CAJA CHICA
    $stmt = $conn->prepare('UPDATE cuenta_caja_chica SET SaldoInicialCaja =:saldoFinal WHERE FKCuenta =:fkcuenta');
    $stmt->bindValue(':fkcuenta', $id, PDO::PARAM_INT);
    $stmt->bindValue(':saldoFinal', $saldoFinal);
    if ($stmt->execute() == true) {
        $uCuenta = true;
    } else {
        $uCuenta = false;
    }
    try {
        $stmt = $conn->prepare("INSERT INTO movimientos_cuentas_bancarias_empresa (cuenta_origen_id,Fecha,tipo_movimiento_id,Descripcion,$retDep,Saldo,Referencia,Comprobado) VALUES (:fkcuenta,:fecha,:tipo,:descripcion,:tipAjust,:saldo,:referencia,:comprobado)");
        $stmt->bindValue(':fkcuenta', $id, PDO::PARAM_INT);
        $stmt->bindValue(':fecha', $txtFechaAjuste);
        $stmt->bindValue(':tipo', 6);
        $stmt->bindValue(':descripcion', $observacionesAjsute);
        $stmt->bindValue(':tipAjust', $cantidadAjuste);
        $stmt->bindValue('saldo', $saldoFinal);
        $stmt->bindValue(':referencia', "-");
        $stmt->bindValue(':comprobado', 0);

        if ($stmt->execute() == true) {
            $exito = true;
            $idLast = $conn->lastInsertId();
        } else {
            $exito = false;
        }
        //
        // SELECIONA EL Saldo  de la cuenta y el pk del movimiento
        $stmt = $conn->prepare('SELECT cc.SaldoInicialCaja as saldoAnterior, mcb.PKMovimiento FROM cuenta_caja_chica as cc
             INNER JOIN movimientos_cuentas_bancarias_empresa as mcb ON cc.FKCuenta=mcb.cuenta_origen_id WHERE mcb.PKMovimiento =:idLast');
        $stmt->execute(array(':idLast' => $idLast));
        $stmt->execute();
        $rowC = $stmt->fetch();
        $saldoAnterior = $rowC['saldoAnterior'];
        //REGISTRA EL SALDO QUE LE QUEDA A LA CUENTA que transfiere
        $stmt = $conn->prepare('SELECT * FROM cuenta_caja_chica  WHERE FKCuenta =:fkcuenta');
        $stmt->execute(array(':fkcuenta' => $id));
        $stmt->execute();
        $rowC = $stmt->fetch();
        $saldoActual = $rowC['SaldoInicialCaja'];

        //UPDATE del movimiento
        $stmt = $conn->prepare('UPDATE movimientos_cuentas_bancarias_empresa SET Saldo =:saldoFinal WHERE PKMovimiento =:idLast');
        $stmt->bindValue(':idLast', $idLast, PDO::PARAM_INT);
        $stmt->bindValue(':saldoFinal', $saldoActual);
        $stmt->execute();

        if ($exito === true && $uCuenta === true) {
            echo "exito";
        } else {
            echo "exito";
        }
    } catch (PDOException $ex) {
        echo $ex->getMessage();
    }

} else {
    header("location:../../../../dashboard.php");
}