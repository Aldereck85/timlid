<?php
session_start();
if (isset($_SESSION["Usuario"])) {
    require_once '../../../include/db-conn.php';
    $id = (int) $_POST['idCuentaActual'];
    $idACuenta = $_POST['idCuentaDestino'];
    $creditDisponible = $_POST['saldoCuentaActual'];
    $cantidadDisponer = $_POST['txtCantidadT'];
    $tipoCambio = $_POST['tipoCambio'];

    $observaciones = $_POST['areaObservacionD'];

    $tipoC = $_POST['tipoC'];
    $nomCuentaT = $_POST['nomCuentaT'];

    $fecha = date('Y-m-d');
    $saldoF = 0;

    /* if($tipoCambio != ""){
    $cantidadDisponer1 = $cantidadDisponer * $tipoCambio;
    }else{
    $cantidadDisponer1 = $cantidadDisponer;
    } */
    $cantidadDisponer1 = $cantidadDisponer;
    if ($observaciones != "") {
        $obs = $_POST['areaObservacionD'];
    } else {
        $obs = "Transferencia de: " . $nomCuentaT;
    }

    //Seleccionar el saldo de cuentas_credito para hacer la suma al credito utilizado
    $stmt = $conn->prepare('SELECT * FROM  cuentas_credito WHERE FKCuenta = :fkcuenta');
    $stmt->bindValue(':fkcuenta', $id, PDO::PARAM_INT);
    $stmt->execute();
    $rowC = $stmt->fetch();
    $saldoUtulizado = $rowC['Credito_Utilizado'];

    try {
        if ($tipoC == 2) {
            $stmt = $conn->prepare('SELECT * FROM  cuentas_credito WHERE FKCuenta = :fkcuenta');
            $stmt->bindValue(':fkcuenta', $idACuenta, PDO::PARAM_INT);
            $stmt->execute();
            $rowC = $stmt->fetch();
            $saldoUtulizadoDest = $rowC['Credito_Utilizado'];
            //UPDATE DE LA CUNETA CREDITO
            $saldoF = $saldoUtulizado + $cantidadDisponer;
            $stmt = $conn->prepare('UPDATE cuentas_credito SET Credito_Utilizado =:saldoFinal WHERE FKCuenta =:fkcuenta');
            $stmt->bindValue(':fkcuenta', $id, PDO::PARAM_INT);
            $stmt->bindValue(':saldoFinal', $saldoF);
            if ($stmt->execute() == true) {
                $uCredito = true;
            } else {
                $uCredito = false;
            }
            //UPDATE DE LA CUENTA DESTINO CREDITO
            $saldoR = $saldoUtulizadoDest - $cantidadDisponer1;
            $stmt = $conn->prepare('UPDATE cuentas_credito SET Credito_Utilizado =:saldoFinal WHERE FKCuenta =:fkcuenta');
            $stmt->bindValue(':fkcuenta', $idACuenta, PDO::PARAM_INT);
            $stmt->bindValue(':saldoFinal', $saldoR);
            if ($stmt->execute() == true) {
                $uCreditoACuenta = true;
            } else {
                $uCreditoACuenta = false;
            }
            //REGISTRO DEL MOVIMIENTO 1
            $stmt = $conn->prepare('INSERT INTO movimientos_cuentas_bancarias_empresa (cuenta_origen_id,Fecha,tipo_movimiento_id,Descripcion,Retiro,Saldo,Comprobado,cuenta_destino_id) VALUES (:fkcuenta,:fecha,:tipo,:descripcion,:retiro,:saldo,:comprobado,:idDestino)');
            $stmt->bindValue(':fkcuenta', $id, PDO::PARAM_INT);
            //$stmt->bindValue(':responsable',$responsable);
            $stmt->bindValue(':fecha', $fecha);
            $stmt->bindValue(':tipo', 3);
            $stmt->bindValue(':descripcion', $observaciones);
            $stmt->bindValue(':retiro', $cantidadDisponer);
            $stmt->bindValue(':saldo', $saldoF);
            $stmt->bindValue(':comprobado', 0);
            $stmt->bindValue(':idDestino', $idACuenta);
            if ($stmt->execute() == true) {
                $exitoM1 = true;
                $idLast = $conn->lastInsertId();
            } else {
                $exitoM1 = false;
            }
            //REGISTRO DEL MOVIMIENTO 2
            $stmt = $conn->prepare('INSERT INTO movimientos_cuentas_bancarias_empresa (cuenta_origen_id,Fecha,tipo_movimiento_id,Descripcion,Deposito,Referencia,Comprobado,Saldo) VALUES (:fkcuenta,:fecha,:tipo,:descripcion,:deposito,:referencia,:comprobado,:saldo)');
            $stmt->bindValue(':fkcuenta', $idACuenta, PDO::PARAM_INT);

            $stmt->bindValue(':fecha', $fecha);
            $stmt->bindValue(':tipo', 7);
            $stmt->bindValue(':descripcion', $obs);
            $stmt->bindValue(':deposito', $cantidadDisponer1);
            $stmt->bindValue(':referencia', "-");
            $stmt->bindValue(':comprobado', 0);
            $stmt->bindValue(':saldo', $saldoF);

            if ($stmt->execute() == true) {
                $exitoM1 = true;
                $idLast = $conn->lastInsertId();
            } else {
                $exitoM1 = false;
            }

            if ($uCredito == true && $uCreditoACuenta == true && $exitoM1 == true) {
                echo "exito";
            } else {
                echo "false";
            }
        } else if ($tipoC == 3) { //la cuenta a disponer es de tipo OTRAS
            //UPDATE DE LA CUNETA CREDITO
            $saldoF1 = $saldoUtulizado + $cantidadDisponer;
            $stmt = $conn->prepare('UPDATE cuentas_credito SET Credito_Utilizado =:saldoFinal WHERE FKCuenta =:fkcuenta');
            $stmt->bindValue(':fkcuenta', $id, PDO::PARAM_INT);
            $stmt->bindValue(':saldoFinal', $saldoF1);
            if ($stmt->execute() == true) {
                $uCreditoACheque = true;
            } else {
                $uCreditoACheque = false;
            }
            //SELECCION DEL SALDO EN LA CUENTA OTRAS PARA HACER LA SUMA
            $stmt = $conn->prepare('SELECT * FROM  cuentas_otras WHERE FKCuenta = :fkcuenta');
            $stmt->bindValue(':fkcuenta', $idACuenta, PDO::PARAM_INT);
            $stmt->execute();
            $rowCh = $stmt->fetch();
            $SaldoInicial = $rowCh['Saldo_Inicial'];
            //UPDATE DE LA CUNETA DESTINO OTRAS
            $saldoFOtras = $SaldoInicial + $cantidadDisponer1;
            $stmt = $conn->prepare('UPDATE cuentas_otras SET Saldo_Inicial =:saldoFinal WHERE FKCuenta =:fkcuenta');
            $stmt->bindValue(':fkcuenta', $idACuenta, PDO::PARAM_INT);
            $stmt->bindValue(':saldoFinal', $saldoFOtras);
            if ($stmt->execute() == true) {
                $uOtras = true;
            } else {
                $uOtras = false;
            }
            //MOVIMIMIENTO CUENTA CREDITO 1 --------
            $stmt = $conn->prepare('INSERT INTO movimientos_cuentas_bancarias_empresa (cuenta_origen_id,Fecha,tipo_movimiento_id,Descripcion,Retiro,Saldo,Comprobado,cuenta_destino_id) VALUES (:fkcuenta,:fecha,:tipo,:descripcion,:retiro,:saldo,:comprobado,:idDestino)');
            $stmt->bindValue(':fkcuenta', $id, PDO::PARAM_INT);

            //$stmt->bindValue(':responsable',$responsable);
            $stmt->bindValue(':fecha', $fecha);
            $stmt->bindValue(':tipo', 3);
            $stmt->bindValue(':descripcion', $observaciones);
            $stmt->bindValue(':retiro', $cantidadDisponer);
            $stmt->bindValue(':saldo', $saldoF1);
            $stmt->bindValue(':comprobado', 0);
            $stmt->bindValue(':idDestino', $idACuenta);

            if ($stmt->execute() == true) {
                $exitoM11 = true;
                $idLast = $conn->lastInsertId();
            } else {
                $exitoM11 = false;
            }
            //MOVIMIMIENTO CUENTA OTRA 2 --------
            $stmt = $conn->prepare('INSERT INTO movimientos_cuentas_bancarias_empresa (cuenta_origen_id,Fecha,tipo_movimiento_id,Descripcion,Deposito,Referencia,Comprobado,Saldo) VALUES (:fkcuenta,:fecha,:tipo,:descripcion,:deposito,:referencia,:comprobado,:saldo)');
            $stmt->bindValue(':fkcuenta', $idACuenta, PDO::PARAM_INT);
            //$stmt->bindValue(':responsable',$responsable);
            $stmt->bindValue(':fecha', $fecha);
            $stmt->bindValue(':tipo', 7);
            $stmt->bindValue(':descripcion', $obs);
            $stmt->bindValue(':deposito', $cantidadDisponer1);
            $stmt->bindValue(':referencia', "-");
            $stmt->bindValue(':comprobado', 0);
            $stmt->bindValue(':saldo', $saldoFOtras);
            if ($stmt->execute() == true) {
                $exitoM11 = true;
                $idLast = $conn->lastInsertId();
            } else {
                $exitoM11 = false;
            }

            if ($uOtras == true && $exitoM11 == true) {
                echo "exito";
            } else {
                echo "false";
            }
        } else if ($tipoC == 4) { // SI LA CUENTA DESTINO ES DE CAJA CHICA
            //UPDATE DE LA CUNETA CREDITO
            $saldoF2 = $saldoUtulizado + $cantidadDisponer;
            $stmt = $conn->prepare('UPDATE cuentas_credito SET Credito_Utilizado =:saldoFinal WHERE FKCuenta =:fkcuenta');
            $stmt->bindValue(':fkcuenta', $id, PDO::PARAM_INT);
            $stmt->bindValue(':saldoFinal', $saldoF2);
            if ($stmt->execute() == true) {
                $uCreditoAOtras = true;
            } else {
                $uCreditoAOtras = false;
            }
            //SELECCION DEL SALDO EN LA CUENTA_CAJA_CHICA PARA HACER LA SUMA
            $stmt = $conn->prepare('SELECT * FROM  cuenta_caja_chica WHERE FKCuenta = :fkcuenta');
            $stmt->bindValue(':fkcuenta', $idACuenta, PDO::PARAM_INT);
            $stmt->execute();
            $rowCh = $stmt->fetch();
            $SaldoInicialCaja = $rowCh['SaldoInicialCaja'];
            //UPDATE DE LA CUNETA DESTINO OTRAS
            $saldoFCaja = $SaldoInicialCaja + $cantidadDisponer;
            $stmt = $conn->prepare('UPDATE cuenta_caja_chica SET SaldoInicialCaja =:saldoFinal WHERE FKCuenta =:fkcuenta');
            $stmt->bindValue(':fkcuenta', $idACuenta, PDO::PARAM_INT);
            $stmt->bindValue(':saldoFinal', $saldoFCaja);
            if ($stmt->execute() == true) {
                $uCajaChica = true;
            } else {
                $uCajaChica = false;
            }
            $saldoFF = $saldoUtulizado + $cantidadDisponer;
            //MOVIMIMIENTO CUENTA CREDITO 1 --------
            $stmt = $conn->prepare('INSERT INTO movimientos_cuentas_bancarias_empresa (cuenta_origen_id,Fecha,tipo_movimiento_id,Descripcion,Retiro,Saldo,Comprobado,cuenta_destino_id) VALUES (:fkcuenta,:fecha,:tipo,:descripcion,:retiro,:saldo,:comprobado,:idDestino)');
            $stmt->bindValue(':fkcuenta', $id, PDO::PARAM_INT);
            //$stmt->bindValue(':responsable',$responsable);
            $stmt->bindValue(':fecha', $fecha);
            $stmt->bindValue(':tipo', 3);
            $stmt->bindValue(':descripcion', $observaciones);
            $stmt->bindValue(':retiro', $cantidadDisponer);
            $stmt->bindValue(':saldo', $saldoFF);
            $stmt->bindValue(':comprobado', 0);
            $stmt->bindValue(':idDestino', $idACuenta);
            if ($stmt->execute() == true) {
                $exitoMC = true;
                $idLast = $conn->lastInsertId();
            } else {
                $exitoMC = false;
            }
            //MOVIMIMIENTO caja_chica 2 --------
            $stmt = $conn->prepare('INSERT INTO movimientos_cuentas_bancarias_empresa (cuenta_origen_id,Fecha,tipo_movimiento_id,Descripcion,Deposito,Referencia,Comprobado,Saldo) VALUES (:fkcuenta,:fecha,:tipo,:descripcion,:deposito,:referencia,:comprobado,:saldo)');
            $stmt->bindValue(':fkcuenta', $idACuenta, PDO::PARAM_INT);
            $stmt->bindValue(':fecha', $fecha);
            $stmt->bindValue(':tipo', 7);
            $stmt->bindValue(':descripcion', $obs);
            $stmt->bindValue(':deposito', $cantidadDisponer1);
            $stmt->bindValue(':referencia', "-");
            $stmt->bindValue(':comprobado', 0);
            $stmt->bindValue(':saldo', $saldoFCaja);

            if ($stmt->execute() == true) {
                $exitoMCajaC = true;
                $idLastC = $conn->lastInsertId();
            } else {
                $exitoMCajaC = false;
            }
            //UPDATE DEL SALDO EN MOVIMIENTO;
            $stmtM = $conn->prepare('UPDATE movimientos_cuentas_bancarias_empresa SET Saldo =:saldoFinalMov WHERE PKMovimiento =:fkmovimiento');
            $stmtM->bindValue(':fkmovimiento', $idLastC, PDO::PARAM_INT);
            $stmtM->bindValue(':saldoFinalMov', $saldoFCaja);
            if ($stmtM->execute() == true) {
                $uM = true;
            } else {
                $uM = false;
            }
            if ($uCreditoAOtras == true && $uCajaChica == true && $exitoMC == true && $exitoMCajaC == true && $uM == true) {
                echo "exito";
            } else {
                echo "false";
            }
        } else {
            //UPDATE DE LA CUNETA CREDITO
            $saldoF1cr = $saldoUtulizado + $cantidadDisponer;
            $stmt = $conn->prepare('UPDATE cuentas_credito SET Credito_Utilizado =:saldoFinal WHERE FKCuenta =:fkcuenta');
            $stmt->bindValue(':fkcuenta', $id, PDO::PARAM_INT);
            $stmt->bindValue(':saldoFinal', $saldoF1cr);
            if ($stmt->execute() == true) {
                $uCreditoACheque = true;
            } else {
                $uCreditoACheque = false;
            }
            //SELECCION DEL SALDO EN LA CUENTA CHEQUES PARA HACER LA SUMA
            $stmt = $conn->prepare('SELECT * FROM  cuentas_cheques WHERE FKCuenta = :fkcuenta');
            $stmt->bindValue(':fkcuenta', $idACuenta, PDO::PARAM_INT);
            $stmt->execute();
            $rowCheques = $stmt->fetch();
            $SaldoInicialCh = $rowCheques['Saldo_Inicial'];
            //UPDATE DE LA CUNETA DESTINO CHEQUES
            $saldoFCh = $SaldoInicialCh + $cantidadDisponer;
            $stmt = $conn->prepare('UPDATE cuentas_cheques SET Saldo_Inicial =:saldoFinal WHERE FKCuenta =:fkcuenta');
            $stmt->bindValue(':fkcuenta', $idACuenta, PDO::PARAM_INT);
            $stmt->bindValue(':saldoFinal', $saldoFCh);
            if ($stmt->execute() == true) {
                $uCheques = true;
            } else {
                $uCheques = false;
            }
            //MOVIMIMIENTO CHEQUES 1 --------
            $stmt = $conn->prepare('INSERT INTO movimientos_cuentas_bancarias_empresa (cuenta_origen_id,Fecha,tipo_movimiento_id,Descripcion,Retiro,Saldo,Comprobado,cuenta_destino_id) VALUES (:fkcuenta,:fecha,:tipo,:descripcion,:retiro,:saldo,:comprobado,:idDestino)');
            $stmt->bindValue(':fkcuenta', $id, PDO::PARAM_INT);
            $stmt->bindValue(':fecha', $fecha);
            $stmt->bindValue(':tipo', 3);
            $stmt->bindValue(':descripcion', $observaciones);
            $stmt->bindValue(':retiro', $cantidadDisponer);
            $stmt->bindValue(':saldo', $saldoF1cr);
            $stmt->bindValue(':comprobado', 0);
            $stmt->bindValue(':idDestino', $idACuenta);
            if ($stmt->execute() == true) {
                $exitoMACheques = true;
                $idLast = $conn->lastInsertId();
            } else {
                $exitoMACheques = false;
            }
            //MOVIMIMIENTO cheques 2 --------
            $stmt = $conn->prepare('INSERT INTO movimientos_cuentas_bancarias_empresa (cuenta_origen_id,Fecha,tipo_movimiento_id,Descripcion,Deposito,Saldo,Referencia,Comprobado) VALUES (:fkcuenta,:fecha,:tipo,:descripcion,:deposito,:saldo,:referencia,:comprobado)');
            $stmt->bindValue(':fkcuenta', $idACuenta, PDO::PARAM_INT);
            $stmt->bindValue(':fecha', $fecha);
            $stmt->bindValue(':tipo', 7);
            $stmt->bindValue(':descripcion', $obs);
            $stmt->bindValue(':deposito', $cantidadDisponer1);
            $stmt->bindValue(':saldo', $saldoFCh);
            $stmt->bindValue(':referencia', "-");
            $stmt->bindValue(':comprobado', 0);
            if ($stmt->execute() == true) {
                $exitoM2Cheques = true;
                $idLast = $conn->lastInsertId();
            } else {
                $exitoM2Cheques = false;
            }
            if ($uCheques == true && $exitoMACheques == true && $uCreditoACheque == true && $exitoM2Cheques == true) {
                echo "exito";
            } else {
                echo "false";
            }
        }
    } catch (\Throwable $th) {
        echo $th;
    }
} else {
    header("location:../../../../dashboard.php");
}
