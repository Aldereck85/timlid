<?php
session_start();
if (isset($_SESSION["Usuario"])) {
    require_once '../../../include/db-conn.php';
    $id = (int) $_POST['idCuentaActual'];
    $saldoCuantaActual = $_POST['saldoCuantaActual'];
    $monto = $_POST['montoInyeccionCapital'];
    $fecha = $_POST['fechaInyeccionCapital'];
    $observaciones = $_POST['observaciones'];
    $hayArchivo = $_POST['hayArchivo'];

    $uCuanta = false;
    $exito = false;
    $exitoM = false;

    if ($hayArchivo == 1) {
        $filename = $_FILES['inputFileInyeccion']['name'];
        $tmp = $_FILES['inputFileInyeccion']['tmp_name'];
        $partesruta = pathinfo($filename);
        $identificador = round(microtime(true));
        $json = new \stdClass();

        $nombrearchivo = str_replace(" ", "_", $partesruta['filename']);
        $nombrefinal = $id . '_REF_' . $identificador . '.' . $partesruta['extension'];
        $location = "Documentos/$nombrefinal";

        if (move_uploaded_file($_FILES['inputFileInyeccion']['tmp_name'], $location)) {
            try {
                $stmt = $conn->prepare('INSERT INTO movimientos_cuentas_bancarias_empresa (cuenta_origen_id,Fecha,tipo_movimiento_id,Descripcion,Deposito,Referencia,Comprobado,Saldo) VALUES (:fkcuenta,:fecha,:tipo,:descripcion,:deposito,:referencia,:comprobado,0)');
                $stmt->bindValue(':fkcuenta', $id, PDO::PARAM_INT);
                //$stmt->bindValue(':responsable',$responsable);
                $stmt->bindValue(':fecha', $fecha);
                $stmt->bindValue(':tipo', 1);
                $stmt->bindValue(':descripcion', $observaciones);
                $stmt->bindValue(':deposito', $monto);
                $stmt->bindValue(':referencia', $nombrefinal);
                $stmt->bindValue(':comprobado', $hayArchivo);

                if ($stmt->execute() == true) {
                    $idLast = $conn->lastInsertId();
                    $exitoMov = true;
                } else {
                    $exitoMov = false;
                }
                // SELECIONA EL Saldo anterior de la cuenta y el pk del movimiento
                $stmt = $conn->prepare('SELECT o.Saldo_Inicial as saldoAnterior, mcb.PKMovimiento FROM cuentas_otras as o
           INNER JOIN movimientos_cuentas_bancarias_empresa as mcb ON o.FKCuenta=mcb.cuenta_origen_id WHERE mcb.PKMovimiento =:idLast');
                $stmt->execute(array(':idLast' => $idLast));
                $stmt->execute();
                $rowC = $stmt->fetch();
                $saldoAnterior = $rowC['saldoAnterior'];

                //UPDATE DE LA CUNETA CAJA CHICA
                $saldoFinal = $saldoCuantaActual + $monto;
                $stmt = $conn->prepare('UPDATE cuentas_otras SET Saldo_Inicial =:saldoFinal WHERE FKCuenta =:fkcuenta');
                $stmt->bindValue(':fkcuenta', $id, PDO::PARAM_INT);
                $stmt->bindValue(':saldoFinal', $saldoFinal);
                if ($stmt->execute() == true) {
                    $uCuenta = true;
                } else {
                    $uCuenta = false;
                }
                // SELECIONA EL SALDO QUE LE QUEDA A LA CUENTA que transfiere
                $stmt = $conn->prepare('SELECT * FROM cuentas_otras  WHERE FKCuenta =:fkcuenta');
                $stmt->execute(array(':fkcuenta' => $id));
                $stmt->execute();
                $rowC = $stmt->fetch();
                $saldoActual = $rowC['Saldo_Inicial'];
                //UPDATE del movimiento

                $stmt = $conn->prepare('UPDATE movimientos_cuentas_bancarias_empresa SET Saldo =:saldoFinal WHERE PKMovimiento =:idLast');
                $stmt->bindValue(':idLast', $idLast, PDO::PARAM_INT);
                $stmt->bindValue(':saldoFinal', $saldoActual);
                $stmt->execute();

                if ($uCuenta === true && $exitoMov === true) {

                    echo "exito";
                } else {
                    echo "fallo";
                }
            } catch (PDOException $ex) {
                echo $ex->getMessage();
            }
        } else { //else del movimiento delarchivo
        }
    } else {
        try {
            $stmt = $conn->prepare('INSERT INTO movimientos_cuentas_bancarias_empresa (cuenta_origen_id,Fecha,tipo_movimiento_id,Descripcion,Deposito,Referencia,Comprobado,Saldo) VALUES (:fkcuenta,:fecha,:tipo,:descripcion,:deposito,:referencia,:comprobado, 0)');
            $stmt->bindValue(':fkcuenta', $id, PDO::PARAM_INT);
            //$stmt->bindValue(':responsable',$responsable);
            $stmt->bindValue(':fecha', $fecha);
            $stmt->bindValue(':tipo', 1);
            $stmt->bindValue(':descripcion', $observaciones);
            $stmt->bindValue(':deposito', $monto);
            $stmt->bindValue(':referencia', "-");
            $stmt->bindValue(':comprobado', $hayArchivo);

            if ($stmt->execute() == true) {
                $idLast = $conn->lastInsertId();
                $exitoMov = true;
            } else {
                $exitoMov = false;
            }
            // SELECIONA EL Saldo anterior de la cuenta y el pk del movimiento
            $stmt = $conn->prepare('SELECT o.Saldo_Inicial as saldoAnterior, mcb.PKMovimiento FROM cuentas_otras as o
         INNER JOIN movimientos_cuentas_bancarias_empresa as mcb ON o.FKCuenta=mcb.cuenta_origen_id WHERE mcb.PKMovimiento =:idLast');
            $stmt->execute(array(':idLast' => $idLast));
            $stmt->execute();
            $rowC = $stmt->fetch();
            $saldoAnterior = $rowC['saldoAnterior'];

            //UPDATE DE LA CUNETA CAJA OTRA
            $saldoFinal = $saldoCuantaActual + $monto;
            $stmt = $conn->prepare('UPDATE cuentas_otras SET Saldo_Inicial =:saldoFinal WHERE FKCuenta =:fkcuenta');
            $stmt->bindValue(':fkcuenta', $id, PDO::PARAM_INT);
            $stmt->bindValue(':saldoFinal', $saldoFinal);
            if ($stmt->execute() == true) {
                $uCuenta = true;
            } else {
                $uCuenta = false;
            }
            // SELECIONA EL SALDO QUE LE QUEDA A LA CUENTA que transfiere
            $stmt = $conn->prepare('SELECT * FROM cuentas_otras  WHERE FKCuenta =:fkcuenta');
            $stmt->execute(array(':fkcuenta' => $id));
            $stmt->execute();
            $rowC = $stmt->fetch();
            $saldoActual = $rowC['Saldo_Inicial'];
            //UPDATE del movimiento

            $stmt = $conn->prepare('UPDATE movimientos_cuentas_bancarias_empresa SET Saldo =:saldoFinal WHERE PKMovimiento =:idLast');
            $stmt->bindValue(':idLast', $idLast, PDO::PARAM_INT);
            $stmt->bindValue(':saldoFinal', $saldoActual);
            $stmt->execute();

            if ($uCuenta === true && $exitoMov === true) {

                echo "exito";
            } else {
                echo "fallo";
            }
        } catch (PDOException $ex) {
            echo $ex->getMessage();
        }
    }

} else {
    header("location:../../../../dashboard.php");
}