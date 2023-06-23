<?php
session_start();

if (isset($_SESSION["Usuario"])) {
    require_once '../../../include/db-conn.php';

    $id = (int) $_POST['idCuentaCaja'];
    $responsable = $_POST['cmbResponsableGasto'];
    $importe = $_POST['txtImporteGasto'];
    $fechaGasto = $_POST['txtFechaGasto'];
    $observaciones = $_POST['areaDescripcionGasto'];
    $proveedor = $_POST['cmbProvedoresGasto'];
    $categoria = $_POST['cmbCategoria'];
    $subcategoria = $_POST['cmbSubcategoria'];
    $check = $_POST['comprobado'];
    $hayArchivo = $_POST['hayArchivo'];

    if ($hayArchivo == 1) {
        $filename = $_FILES['inputFile']['name'];
        $tmp = $_FILES['inputFile']['tmp_name'];
        $partesruta = pathinfo($filename);
        $identificador = round(microtime(true));
        $nombrefinal = $id . '_REF_' . $identificador . '.' . $partesruta['extension'];
        // Location
        $location = "Documentos/$nombrefinal";
        if ($partesruta['extension'] == "jpg" || $partesruta['extension'] == "jpeg" || $partesruta['extension'] == "png" || $partesruta['extension'] == "pdf" || $partesruta['extension'] == "xlsx" || $partesruta['extension'] == "xml") {
            if ($_FILES['inputFile']['size'] < 4000000) {
                if (move_uploaded_file($_FILES['inputFile']['tmp_name'], $location)) {
                    echo insertRetiroCaja($conn, $id, $importe, $responsable, $fechaGasto, $proveedor, $observaciones, $categoria, $subcategoria, $hayArchivo, $nombrefinal);
                } else {
                    echo "mal 1";
                }
            } else {
                echo "mal 2";
            }
        } else {
            echo "mal 3";
        }
    } else {
        //llamar funcion
        echo insertRetiroCaja($conn, $id, $importe, $responsable, $fechaGasto, $proveedor, $observaciones, $categoria, $subcategoria, $hayArchivo);
    }
} else {
    header("location:../../../../dashboard.php");
}

function insertRetiroCaja($conn, $id, $importe, $responsable, $fechaGasto, $proveedor, $observaciones, $categoria, $subcategoria, $hayArchivo, $nombrefinal = NULL)
{
    try {
        if ($categoria) {
            if ($subcategoria) {
                $query = 'INSERT INTO movimientos_cuentas_bancarias_empresa (cuenta_origen_id,FKResponsable,Fecha,FKProveedor,tipo_movimiento_id,Descripcion,FKCategoria,FKSubcategoria,Retiro,Referencia,Comprobado,Saldo) VALUES (:fkcuenta,:responsable,:fecha,:proveedor,:tipo,:descripcion,:categoria,:subcategoria,:retiro,:referencia,:comprobado,0)';
                $datos = [
                    'fkcuenta' => $id,
                    'responsable' => $responsable,
                    'fecha' => $fechaGasto,
                    'proveedor' => $proveedor,
                    'tipo' => 2,
                    'descripcion' => $observaciones,
                    'categoria' => $categoria,
                    'subcategoria' => $subcategoria,
                    'retiro' => $importe,
                    'referencia' => $nombrefinal,
                    'comprobado' => $hayArchivo];
            } else {
                $query = 'INSERT INTO movimientos_cuentas_bancarias_empresa (cuenta_origen_id,FKResponsable,Fecha,FKProveedor,tipo_movimiento_id,Descripcion,FKCategoria,Retiro,Referencia,Comprobado,Saldo) VALUES (:fkcuenta,:responsable,:fecha,:proveedor,:tipo,:descripcion,:categoria,:retiro,:referencia,:comprobado,0)';
                $datos = [
                    'fkcuenta' => $id,
                    'responsable' => $responsable,
                    'fecha' => $fechaGasto,
                    'proveedor' => $proveedor,
                    'tipo' => 2,
                    'descripcion' => $observaciones,
                    'categoria' => $categoria,
                    'retiro' => $importe,
                    'referencia' => $nombrefinal,
                    'comprobado' => $hayArchivo];
            }
        } else {
            $query = 'INSERT INTO movimientos_cuentas_bancarias_empresa (cuenta_origen_id,FKResponsable,Fecha,FKProveedor,tipo_movimiento_id,Descripcion,Retiro,Referencia,Comprobado,Saldo) VALUES (:fkcuenta,:responsable,:fecha,:proveedor,:tipo,:descripcion,:retiro,:referencia,:comprobado,0)';
            $datos = [
                'fkcuenta' => $id,
                'responsable' => $responsable,
                'fecha' => $fechaGasto,
                'proveedor' => $proveedor,
                'tipo' => 2,
                'descripcion' => $observaciones,
                'retiro' => $importe,
                'referencia' => $nombrefinal,
                'comprobado' => $hayArchivo];
        }
        //Seleccionar el saldo de la cuenta caja chica para hacer la resta
        $stmt = $conn->prepare('SELECT * FROM cuentas_otras WHERE FKCuenta = :fkcuenta');
        $stmt->bindValue(':fkcuenta', $id, PDO::PARAM_INT);
        $stmt->execute();
        $rowC = $stmt->fetch();
        $saldoInicial = $rowC['Saldo_Inicial'];

        $saldoFinalSiArchivo = $saldoInicial - $importe;
        //UPDATE DE LA CUNETA CAJA CHICA
        $stmt = $conn->prepare('UPDATE cuentas_otras SET Saldo_Inicial = :saldoFinal WHERE FKCuenta = :fkcuenta');
        $stmt->bindValue(':fkcuenta', $id, PDO::PARAM_INT);
        $stmt->bindValue(':saldoFinal', $saldoFinalSiArchivo);
        if ($stmt->execute()) {
            try {
                $stmtI = $conn->prepare($query);
                if ($stmtI->execute($datos)) {
                    $idLast = $conn->lastInsertId();
                    // SELECIONA EL Saldo  de la cuenta y el pk del movimiento
                    $stmt = $conn->prepare('SELECT cc.Saldo_Inicial as saldoAnterior, mcb.PKMovimiento FROM cuentas_otras as cc INNER JOIN movimientos_cuentas_bancarias_empresa as mcb ON cc.FKCuenta = mcb.cuenta_origen_id WHERE mcb.PKMovimiento = :idLast');
                    $stmt->execute(array(':idLast' => $idLast));
                    $stmt->execute();
                    $rowC = $stmt->fetch();
                    $saldoAnterior = $rowC['saldoAnterior'];
                    //UPDATE del movimiento
                    $stmt = $conn->prepare('UPDATE movimientos_cuentas_bancarias_empresa SET Saldo =:saldoFinal WHERE PKMovimiento = :idLast');
                    $stmt->bindValue(':idLast', $idLast, PDO::PARAM_INT);
                    $stmt->bindValue(':saldoFinal', $saldoFinalSiArchivo);
                    if ($stmt->execute()) {
                        return "exito";
                    } else {
                        return "mal funcion";
                    }
                } else {
                    return "mal funcion 2";
                }
            } catch (PDOException $ex) {
                echo $ex->getMessage();
            }
        }
    } catch (\Throwable $th) {
        echo $th;
    }
}
