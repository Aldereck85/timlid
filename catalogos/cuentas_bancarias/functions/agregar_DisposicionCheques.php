<?php
session_start();

if (isset($_SESSION["Usuario"])) {
    require_once '../../../include/db-conn.php';
    $id = (int) $_POST['idActual'];
    $idACuenta = $_POST['idACuenta'];
    $cantidadDisponer = $_POST['cantidadDisponer'];
    $tipoCambio = $_POST['tipoCambio'];
    $observaciones = $_POST['areaObservacionD'];
    $hayCuenta = $_POST['hayCuenta'];
    $tipoC = $_POST['tipoC'];
    $nomCuenta = $_POST['nomCuenta'];
    try {

        $obs = "Disposicion de: " . $nomCuenta;

        $fecha = date('Y-m-d');
        $saldoF = 0;
        $cantidadDisponer1 = 0;

        /* if ($tipoCambio != "") {
            $cantidadDisponer1 = $cantidadDisponer * $tipoCambio;
        } else {
            $cantidadDisponer1 = $cantidadDisponer;
        } */
        $cantidadDisponer1 = $cantidadDisponer;

//Seleccionar el saldo de cuentas_cheques para hacer la suma al credito utilizado
        $stmt = $conn->prepare('SELECT * FROM cuentas_cheques WHERE FKCuenta = :fkcuenta');
        $stmt->bindValue(':fkcuenta', $id, PDO::PARAM_INT);
        $stmt->execute();
        $rowC = $stmt->fetch();
        $saldoAnterior = $rowC['Saldo_Inicial'];

        $saldoFD = $saldoAnterior + $cantidadDisponer1;

        if ($hayCuenta == 1) { //IF exist account
            if ($tipoC == 2) { // Cuentas credito
                //Seleccionar el saldo de CUENTAS_CREDITO para hacer la suma al credito utilizado
                $stmt = $conn->prepare('SELECT * FROM cuentas_credito WHERE FKCuenta = :fkcuenta');
                $stmt->bindValue(':fkcuenta', $idACuenta, PDO::PARAM_INT);
                $stmt->execute();
                $rowC = $stmt->fetch();
                $limCredito = $rowC['Limite_Credito'];
                $creditoUtulizado = $rowC['Credito_Utilizado'];

                //UPDATE DE LA CUENTA CREDITO CREDITO
                $saldoFDes = $creditoUtulizado - $cantidadDisponer1;
                $stmt = $conn->prepare('UPDATE cuentas_credito SET Credito_Utilizado = :saldoFinal WHERE FKCuenta = :fkcuenta');
                $stmt->bindValue(':fkcuenta', $idACuenta, PDO::PARAM_INT);
                $stmt->bindValue(':saldoFinal', $saldoFDes);
                if ($stmt->execute() == true) {
                    $uCredito = true;
                } else {
                    $uCredito = false;
                }

                //UPDATE DE LA CUENTA CHEQUES
                $saldoFU = $saldoAnterior - $cantidadDisponer;
                $stmt = $conn->prepare('UPDATE cuentas_cheques SET Saldo_Inicial = :saldoFinal WHERE FKCuenta = :fkcuenta');
                $stmt->bindValue(':fkcuenta', $id, PDO::PARAM_INT);
                $stmt->bindValue(':saldoFinal', $saldoFU);
                if ($stmt->execute() == true) {
                    $uCheques = true;
                } else {
                    $uCheques = false;
                }

                //REGISTRO DEL MOVIMIENTO 1
                $stmt = $conn->prepare('INSERT INTO movimientos_cuentas_bancarias_empresa (cuenta_origen_id,Fecha,tipo_movimiento_id,Descripcion,Retiro,Saldo,Comprobado,cuenta_destino_id) VALUES (:fkcuenta,:fecha,:tipo,:descripcion,:retiro,:saldo,:comprobado,:iddestino)');
                $stmt->bindValue(':fkcuenta', $id, PDO::PARAM_INT);
                //$stmt->bindValue(':responsable',$responsable);
                $stmt->bindValue(':fecha', $fecha);
                $stmt->bindValue(':tipo', 4);
                $stmt->bindValue(':descripcion', $observaciones);
                $stmt->bindValue(':retiro', $cantidadDisponer);
                $stmt->bindValue(':saldo', $saldoFDes);
                $stmt->bindValue(':comprobado', 0);
                $stmt->bindValue(':iddestino', $idACuenta);
                if ($stmt->execute() == true) {
                    $exitoM1 = true;
                    $idLast = $conn->lastInsertId();
                } else {
                    $exitoM1 = false;
                }
                //REGISTRO DEL MOVIMIENTO 2
                $saldoDisponible = $limCredito - $creditoUtulizado + $cantidadDisponer1;

                $stmt = $conn->prepare('INSERT INTO movimientos_cuentas_bancarias_empresa (cuenta_origen_id,Fecha,tipo_movimiento_id,Descripcion,Deposito,Saldo,Comprobado) VALUES (:fkcuenta,:fecha,:tipo,:descripcion,:deposito,:saldo,:comprobado)');
                $stmt->bindValue(':fkcuenta', $idACuenta, PDO::PARAM_INT);

                $stmt->bindValue(':fecha', $fecha);
                $stmt->bindValue(':tipo', 4);
                $stmt->bindValue(':descripcion', $obs);
                $stmt->bindValue(':deposito', $cantidadDisponer1);
                $stmt->bindValue(':saldo', $saldoDisponible);
                $stmt->bindValue(':comprobado', 0);
                if ($stmt->execute() == true) {
                    $exitoM2 = true;
                    $idLast = $conn->lastInsertId();
                } else {
                    $exitoM2 = false;
                }

                if ($uCredito == true && $uCheques == true && $exitoM1 == true && $exitoM2 == true) {
                    echo "exito";
                } else {
                    echo "false";
                }

            } else if ($tipoC == 3) { //la cuenta a disponer es de tipo OTRAS
                //SELECCION DEL SALDO EN LA CUENTA OTRAS PARA HACER LA SUMA
                $stmt = $conn->prepare('SELECT * FROM cuentas_otras WHERE FKCuenta = :fkcuenta');
                $stmt->bindValue(':fkcuenta', $idACuenta, PDO::PARAM_INT);
                $stmt->execute();
                $rowCh = $stmt->fetch();
                $SaldoInicial = $rowCh['Saldo_Inicial'];

                //UPDATE DE LA CUENTA DESTINO OTRAS
                $saldoFOtras = $SaldoInicial + $cantidadDisponer1;
                $stmt = $conn->prepare('UPDATE cuentas_otras SET Saldo_Inicial = :saldoFinal WHERE FKCuenta = :fkcuenta');
                $stmt->bindValue(':fkcuenta', $idACuenta, PDO::PARAM_INT);
                $stmt->bindValue(':saldoFinal', $saldoFOtras);
                if ($stmt->execute() == true) {
                    $uOtras = true;
                } else {
                    $uOtras = false;
                }

                //UPDATE DE LA CUENTA CHEQUES
                $saldoF = $saldoAnterior - $cantidadDisponer;
                $stmt = $conn->prepare('UPDATE cuentas_cheques SET Saldo_Inicial =:saldoFinal WHERE FKCuenta =:fkcuenta');
                $stmt->bindValue(':fkcuenta', $id, PDO::PARAM_INT);
                $stmt->bindValue(':saldoFinal', $saldoF);
                if ($stmt->execute() == true) {
                    $uChequesAOtros = true;
                } else {
                    $uChequesAOtros = false;
                }

                //MOVIMIMIENTO CUENTA CREDITO 1 --------
                $stmt = $conn->prepare('INSERT INTO movimientos_cuentas_bancarias_empresa (cuenta_origen_id,Fecha,tipo_movimiento_id,Descripcion,Retiro,Saldo,Comprobado,cuenta_destino_id) VALUES (:fkcuenta,:fecha,:tipo,:descripcion,:retiro,:saldo,:comprobado,:iddestino)');
                $stmt->bindValue(':fkcuenta', $id, PDO::PARAM_INT);
                //$stmt->bindValue(':responsable',$responsable);
                $stmt->bindValue(':fecha', $fecha);
                $stmt->bindValue(':tipo', 4);
                $stmt->bindValue(':descripcion', $observaciones);
                $stmt->bindValue(':retiro', $cantidadDisponer);
                $stmt->bindValue(':saldo', $saldoF);
                $stmt->bindValue(':comprobado', 0);
                $stmt->bindValue(':iddestino', $idACuenta);
                if ($stmt->execute() == true) {
                    $exitoM11 = true;
                    $idLast = $conn->lastInsertId();
                } else {
                    $exitoM11 = false;
                }

                //MOVIMIMIENTO CUENTA OTRA 2 --------
                $stmt = $conn->prepare('INSERT INTO movimientos_cuentas_bancarias_empresa (cuenta_origen_id,Fecha,tipo_movimiento_id,Descripcion,Deposito,Saldo,Comprobado) VALUES (:fkcuenta,:fecha,:tipo,:descripcion,:deposito,:saldo,:comprobado)');
                $stmt->bindValue(':fkcuenta', $idACuenta, PDO::PARAM_INT);
                $stmt->bindValue(':fecha', $fecha);
                $stmt->bindValue(':tipo', 4);
                $stmt->bindValue(':descripcion', $obs);
                $stmt->bindValue(':deposito', $cantidadDisponer1);
                $stmt->bindValue(':saldo', $saldoFOtras);
                $stmt->bindValue(':comprobado', 0);
                if ($stmt->execute() == true) {
                    $exitoM112 = true;
                    $idLastM2 = $conn->lastInsertId();
                } else {
                    $exitoM112 = false;
                }
                //UPDATE DEL SALDO EN MOVIMIENTO;
                $stmtM = $conn->prepare('UPDATE movimientos_cuentas_bancarias_empresa SET Saldo = :saldoFinalMov WHERE PKMovimiento = :fkmovimiento');
                $stmtM->bindValue(':saldoFinalMov', $saldoFOtras);
                $stmtM->bindValue(':fkmovimiento', $idLastM2, PDO::PARAM_INT);
                if ($stmtM->execute() == true) {
                    $uMO = true;
                } else {
                    $uMO = false;
                }

                if ($uChequesAOtros == true && $uOtras == true && $exitoM11 == true && $exitoM112 == true && $uMO == true) {
                    echo "exito";
                } else {
                    echo "false";
                }
            } else if ($tipoC == 4) { // SI LA CUENTA DESTINO ES DE CAJA CHICA
                //UPDATE DE LA CUNATA CHEQUES
                $saldoF = $saldoAnterior - $cantidadDisponer;
                $stmt = $conn->prepare('UPDATE cuentas_cheques SET Saldo_Inicial =:saldoFinal WHERE FKCuenta = :fkcuenta');
                $stmt->bindValue(':fkcuenta', $id, PDO::PARAM_INT);
                $stmt->bindValue(':saldoFinal', $saldoF);
                if ($stmt->execute() == true) {
                    $uChequesACaja = true;
                } else {
                    $uChequesACaja = false;
                }
                //SELECCION DEL SALDO EN LA CUENTA_CAJA_CHICA PARA HACER LA SUMA
                $stmt = $conn->prepare('SELECT * FROM cuenta_caja_chica WHERE FKCuenta = :fkcuenta');
                $stmt->bindValue(':fkcuenta', $idACuenta, PDO::PARAM_INT);
                $stmt->execute();
                $rowCh = $stmt->fetch();
                $SaldoInicialCaja = $rowCh['SaldoInicialCaja'];

                //UPDATE DE LA CUNETA DESTINO OTRAS
                $saldoFCaja = $SaldoInicialCaja + $cantidadDisponer1;
                $stmt = $conn->prepare('UPDATE cuenta_caja_chica SET SaldoInicialCaja =:saldoFinal WHERE FKCuenta = :fkcuenta');
                $stmt->bindValue(':fkcuenta', $idACuenta, PDO::PARAM_INT);
                $stmt->bindValue(':saldoFinal', $saldoFCaja);
                if ($stmt->execute() == true) {
                    $uCajaChica = true;
                } else {
                    $uCajaChica = false;
                }

                //MOVIMIMIENTO CUENTA CHEQUE 1 --------
                $stmt = $conn->prepare('INSERT INTO movimientos_cuentas_bancarias_empresa (cuenta_origen_id,Fecha,tipo_movimiento_id,Descripcion,Retiro,Saldo,Comprobado,cuenta_destino_id) VALUES (:fkcuenta,:fecha,:tipo,:descripcion,:retiro,:saldo,:comprobado,:iddestino)');
                $stmt->bindValue(':fkcuenta', $id, PDO::PARAM_INT);
                //$stmt->bindValue(':responsable',$responsable);
                $stmt->bindValue(':fecha', $fecha);
                $stmt->bindValue(':tipo', 4);
                $stmt->bindValue(':descripcion', $observaciones);
                $stmt->bindValue(':retiro', $cantidadDisponer);
                $stmt->bindValue(':saldo', $saldoF);
                $stmt->bindValue(':comprobado', 0);
                $stmt->bindValue(':iddestino', $idACuenta);
                if ($stmt->execute() == true) {
                    $exitoMC = true;
                    $idLast = $conn->lastInsertId();
                } else {
                    $exitoMC = false;
                }
                //MOVIMIMIENTO caja_chica 2 --------
                $stmt = $conn->prepare('INSERT INTO movimientos_cuentas_bancarias_empresa (cuenta_origen_id,Fecha,tipo_movimiento_id,Descripcion,Deposito,Saldo,Comprobado) VALUES (:fkcuenta,:fecha,:tipo,:descripcion,:deposito,:saldo,:comprobado)');
                $stmt->bindValue(':fkcuenta', $idACuenta, PDO::PARAM_INT);
                //$stmt->bindValue(':responsable',$responsable);
                $stmt->bindValue(':fecha', $fecha);
                $stmt->bindValue(':tipo', 4);
                $stmt->bindValue(':descripcion', $obs);
                $stmt->bindValue(':deposito', $cantidadDisponer1);
                $stmt->bindValue(':saldo', $saldoFCaja);
                $stmt->bindValue(':comprobado', 0);

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

                if ($uChequesACaja == true && $uCajaChica == true && $exitoMC == true && $exitoMCajaC == true && $uM == true) {
                    echo "exito";
                } else {
                    echo "false";
                }

            } else {
                //UPDATE DE LA CUNETA CHEQUES
                $saldoF = $saldoAnterior - $cantidadDisponer;
                $stmt = $conn->prepare('UPDATE cuentas_cheques SET Saldo_Inicial =:saldoFinal WHERE FKCuenta =:fkcuenta');
                $stmt->bindValue(':fkcuenta', $id, PDO::PARAM_INT);
                $stmt->bindValue(':saldoFinal', $saldoF);
                if ($stmt->execute() == true) {
                    $uChequesACheque = true;
                } else {
                    $uChequesACheque = false;
                }
                //SELECT CUENTA DESTINO
                $stmt = $conn->prepare('SELECT * FROM cuentas_cheques WHERE FKCuenta = :fkcuenta');
                $stmt->bindValue(':fkcuenta', $idACuenta, PDO::PARAM_INT);
                $stmt->execute();
                $rowC = $stmt->fetch();
                $saldoAnteriorD = $rowC['Saldo_Inicial'];

                //UPDATE DE LA CUNETA DESTINO CHEQUES
                $saldoFCh = $saldoAnteriorD + $cantidadDisponer1;

                $stmt = $conn->prepare('UPDATE cuentas_cheques SET Saldo_Inicial =:saldoFinal WHERE FKCuenta =:fkcuenta');
                $stmt->bindValue(':fkcuenta', $idACuenta, PDO::PARAM_INT);
                $stmt->bindValue(':saldoFinal', $saldoFCh);
                if ($stmt->execute() == true) {
                    $uChequesD = true;
                } else {
                    $uChequesD = false;
                }
                //MOVIMIMIENTO CHEQUES 1 --------
                $stmt = $conn->prepare('INSERT INTO movimientos_cuentas_bancarias_empresa (cuenta_origen_id,Fecha,tipo_movimiento_id,Descripcion,Retiro,Saldo,Comprobado,,cuenta_destino_id) VALUES (:fkcuenta,:fecha,:tipo,:descripcion,:retiro,:saldo,:comprobado,:iddestino)');
                $stmt->bindValue(':fkcuenta', $id, PDO::PARAM_INT);
                //$stmt->bindValue(':responsable',$responsable);
                $stmt->bindValue(':fecha', $fecha);
                $stmt->bindValue(':tipo', 4);
                $stmt->bindValue(':descripcion', $observaciones);
                $stmt->bindValue(':retiro', $cantidadDisponer);
                $stmt->bindValue(':saldo', $saldoF);
                $stmt->bindValue(':comprobado', 0);
                $stmt->bindValue(':iddestino', $idACuenta);
                if ($stmt->execute() == true) {
                    $exitoMACheques = true;
                    $idLast = $conn->lastInsertId();
                } else {
                    $exitoMACheques = false;
                }
                //MOVIMIMIENTO cheques 2 --------
                $stmt = $conn->prepare('INSERT INTO movimientos_cuentas_bancarias_empresa (cuenta_origen_id,Fecha,tipo_movimiento_id,Descripcion,Deposito,Saldo,Comprobado) VALUES (:fkcuenta,:fecha,:tipo,:descripcion,:deposito,:saldo,:comprobado)');
                $stmt->bindValue(':fkcuenta', $idACuenta, PDO::PARAM_INT);
                //$stmt->bindValue(':responsable',$responsable);
                $stmt->bindValue(':fecha', $fecha);
                $stmt->bindValue(':tipo', 4);
                $stmt->bindValue(':descripcion', $observaciones);
                $stmt->bindValue(':deposito', $cantidadDisponer1);
                $stmt->bindValue(':saldo', $saldoFCh);
                $stmt->bindValue(':comprobado', 0);
                if ($stmt->execute() == true) {
                    $exitoM2Cheques = true;
                    $idLastS = $conn->lastInsertId();
                } else {
                    $exitoM2Cheques = false;
                }
                //UPDATE SALDO DEL MOVIMIENTO
                $stmtM = $conn->prepare('UPDATE movimientos_cuentas_bancarias_empresa SET Saldo =:saldoFinalMov WHERE PKMovimiento =:fkmovimiento');
                $stmtM->bindValue(':fkmovimiento', $idLastS, PDO::PARAM_INT);
                $stmtM->bindValue(':saldoFinalMov', $saldoFCh);
                if ($stmtM->execute() == true) {
                    $uM = true;
                } else {
                    $uM = false;
                }

                if ($uChequesACheque == true && $uChequesD == true && $exitoMACheques == true && $exitoM2Cheques == true) {
                    echo "exito";
                } else {
                    echo "false";
                }

            }
        } else { //solo es una disposicion sin cuenta:

            //UPDATE DE LA CUNETA CHEQUES
            $saldoFU = $saldoAnterior - $cantidadDisponer;
            $stmt = $conn->prepare('UPDATE cuentas_cheques SET Saldo_Inicial =:saldoFinal WHERE FKCuenta =:fkcuenta');
            $stmt->bindValue(':fkcuenta', $id, PDO::PARAM_INT);
            $stmt->bindValue(':saldoFinal', $saldoFU);
            if ($stmt->execute() == true) {
                $uChequesSinCuenta = true;
            } else {
                $uChequesSinCuenta = false;
            }
            //REGISTRO DEL MOVIMIENTO 1
            $stmt = $conn->prepare('INSERT INTO movimientos_cuentas_bancarias_empresa (cuenta_origen_id,Fecha,tipo_movimiento_id,Descripcion,Retiro,Saldo,Comprobado) VALUES (:fkcuenta,:fecha,:tipo,:descripcion,:retiro,:saldo,:comprobado)');
            $stmt->bindValue(':fkcuenta', $id, PDO::PARAM_INT);
            $stmt->bindValue(':fecha', $fecha);
            $stmt->bindValue(':tipo', 4);
            $stmt->bindValue(':descripcion', $observaciones);
            $stmt->bindValue(':retiro', $cantidadDisponer);
            $stmt->bindValue(':saldo', $saldoFU);
            $stmt->bindValue(':comprobado', 0);
            if ($stmt->execute() == true) {
                $MovimientoSinCuenta = true;
                $idLastT = $conn->lastInsertId();
            } else {
                $MovimientoSinCuenta = false;
            }
            //UPDATE DEL SALDO EN MOVIMIENTO;
            $stmtM = $conn->prepare('UPDATE movimientos_cuentas_bancarias_empresa SET Saldo =:saldoFinalMov WHERE PKMovimiento =:fkmovimiento');
            $stmtM->bindValue(':fkmovimiento', $idLastT, PDO::PARAM_INT);
            $stmtM->bindValue(':saldoFinalMov', $saldoFU);
            if ($stmtM->execute() == true) {
                $MovSinCuenta = true;
            } else {
                $MovSinCuenta = false;
            }
            if (($MovSinCuenta == true && $uChequesSinCuenta == true)) {
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
