<?php
session_start();
if (isset($_SESSION["Usuario"])) {
    require_once '../../../include/db-conn.php';
    $idCuentaActual = $_POST['idCuentaActual'];
    $idCuentaDestino = $_POST['idCuentaDestino'];
    $monedaOrigen = $_POST['monedaOrigen'];
    $monedaDestino = $_POST['monedaDestino'];
    $cantidad = $_POST['cantidad'];
    $tipoCambio = $_POST['tipoCambio'];
    $fechaTransferencia = $_POST['fechaTransferncia'];
    $observaciones = $_POST['observaciones'];
    $saldoCuentaActual = $_POST['saldoCuentaActual'];
    $nomCuentaT = $_POST['nomCuentaT'];

    $saldoFinal = 0;
    $uCuanta = false;
    //$uCuantaD=false;
    $exito = false;
    $exitoM = false;

    if (!$tipoCambio == 0) {
        $cantidad1 = $cantidad * $tipoCambio;
    } else {
        $cantidad1 = $cantidad;
    }

    try {

        if ($cantidad <= $saldoCuentaActual) {
            //UPDATE DE LA CUNETA CAJA OTRA ACTUAL
            $saldoFinal = $saldoCuentaActual - $cantidad;
            $stmt = $conn->prepare('UPDATE cuentas_otras SET Saldo_Inicial =:saldoFinal WHERE FKCuenta =:fkcuenta');
            $stmt->bindValue(':fkcuenta', $idCuentaActual, PDO::PARAM_INT);
            $stmt->bindValue(':saldoFinal', $saldoFinal);
            if ($stmt->execute() == true) {
                $uCuenta = true;
            } else {
                $uCuenta = false;
            }

            //SELECCIONAR EL SALDO DE LA CUENTA DESTINO
            $stmt = $conn->prepare('SELECT * FROM cuentas_otras  WHERE FKCuenta =:fkcuenta');
            $stmt->execute(array(':fkcuenta' => $idCuentaDestino));
            $stmt->execute();
            $rowC = $stmt->fetch();
            $saldoIDes = $rowC['Saldo_Inicial'];

            //UPDATE DE LA CUNETA CAJA CHICA DESTINO
            $saldoFinalD = $saldoIDes + $cantidad1;
            $stmt = $conn->prepare('UPDATE cuentas_otras SET Saldo_Inicial =:saldoFinal WHERE FKCuenta =:fkcuenta');
            $stmt->bindValue(':fkcuenta', $idCuentaDestino, PDO::PARAM_INT);
            $stmt->bindValue(':saldoFinal', $saldoFinalD);
            if ($stmt->execute() == true) {
                $uCuentaD = true;
            } else {
                $uCuentaD = false;
            }

            //REGISTRA EL MOVIMIENTO 1
            $stmt = $conn->prepare('INSERT INTO movimientos_cuentas_bancarias_empresa (cuenta_origen_id,Fecha,tipo_movimiento_id,Descripcion,Retiro,Saldo,Referencia,Comprobado,cuenta_destino_id) VALUES (:fkcuenta,:fecha,:tipo,:descripcion,:retiro,:saldo,:referencia,:comprobado,:idDestino)');

            $stmt->bindValue(':fkcuenta', $idCuentaActual, PDO::PARAM_INT);
            $stmt->bindValue(':fecha', $fechaTransferencia);
            $stmt->bindValue(':tipo', 3);
            $stmt->bindValue(':descripcion', $observaciones);
            $stmt->bindValue(':retiro', $cantidad);
            $stmt->bindValue(':saldo', $saldoFinalD);

            $stmt->bindValue(':referencia', "-");
            $stmt->bindValue(':comprobado', 0);
            $stmt->bindValue(':idDestino', $idCuentaDestino);

            if ($stmt->execute() == true) {
                $exitoM = true;
                $idLast = $conn->lastInsertId();
            } else {
                $exitoM = false;
            }
            // SELECIONA EL Saldo anterior de la cuenta y el pk del movimiento
            $stmt = $conn->prepare('SELECT o.Saldo_Inicial as saldoAnterior, mcb.PKMovimiento FROM cuentas_otras as o
            INNER JOIN movimientos_cuentas_bancarias_empresa as mcb ON o.FKCuenta=mcb.cuenta_origen_id WHERE mcb.PKMovimiento =:idLast');
            $stmt->execute(array(':idLast' => $idLast));
            $stmt->execute();
            $rowC = $stmt->fetch();
            $saldoAnterior = $rowC['saldoAnterior'];

            //REGISTRA EL SALDO QUE LE QUEDA A LA CUENTA que transfiere
            $stmt = $conn->prepare('SELECT * FROM cuentas_otras  WHERE FKCuenta =:fkcuenta');
            $stmt->execute(array(':fkcuenta' => $idCuentaActual));
            $stmt->execute();
            $rowC = $stmt->fetch();
            $saldoActual = $rowC['Saldo_Inicial'];
            //UPDATE del movimiento
            $stmt = $conn->prepare('UPDATE movimientos_cuentas_bancarias_empresa SET Saldo =:saldoFinal WHERE PKMovimiento =:idLast');
            $stmt->bindValue(':idLast', $idLast, PDO::PARAM_INT);
            $stmt->bindValue(':saldoFinal', $saldoActual);
            $stmt->execute();

            //MOVIMIMIENTO Otras 2 --------
            $stmt = $conn->prepare('INSERT INTO movimientos_cuentas_bancarias_empresa (cuenta_origen_id,Fecha,tipo_movimiento_id,Descripcion,Deposito,Referencia,Comprobado,TipoCambio,Saldo) VALUES (:fkcuenta,:fecha,:tipo,:descripcion,:deposito,:referencia,:comprobado,:tipoCambio,0)');
            $stmt->bindValue(':fkcuenta', $idCuentaDestino, PDO::PARAM_INT);
            $obs = "Transferencia de: " . $nomCuentaT;

            $stmt->bindValue(':fecha', $fechaTransferencia);
            $stmt->bindValue(':tipo', 7);
            $stmt->bindValue(':descripcion', $obs);
            $stmt->bindValue(':deposito', $cantidad1);
            $stmt->bindValue(':referencia', "-");
            $stmt->bindValue(':comprobado', 0);
            $stmt->bindValue(':tipoCambio', $tipoCambio);
            if ($stmt->execute() == true) {
                $exitoM2 = true;
                $idLast2 = $conn->lastInsertId();
            } else {
                $exitoM2 = false;
            }

            //UPDATE del movimiento
            $stmt = $conn->prepare('UPDATE movimientos_cuentas_bancarias_empresa SET Saldo =:saldoFinal WHERE PKMovimiento =:idLast');
            $stmt->bindValue(':idLast', $idLast2, PDO::PARAM_INT);
            $stmt->bindValue(':saldoFinal', $saldoFinalD);
            $stmt->execute();

        }

        if ($uCuenta === true && $exitoM === true && $exitoM2 === true) {
            echo "exito";
        } else {
            echo "fallo";
        }
    } catch (\Throwable $th) {
        echo $th;
    }

} else {
    header("location:../../../../dashboard.php");
}
