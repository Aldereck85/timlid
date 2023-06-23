<?php
require_once '../../../include/db-conn.php';
session_start();
if (isset($_SESSION["Usuario"])) {
    $empresa = $_SESSION["IDEmpresa"];
    $id = $_POST['idCuentaU'];
    $nombreCuenta = $_POST['txtNombreCuentaU'];
    $noCuenta = $_POST['txtNoCuentaU'];
    $clabe = $_POST['txtClabeUp'];
    $noCredito = $_POST['txtNoCreditoU'];
    $referencia = $_POST['txtReferenciaU'];
    $idCuenta = $_POST['txtIdentificadorU'];
    $descripcion = $_POST['txtDescripcionU'];
    $descripcionCaja = $_POST['txtDescripcionUCaja'];
    $tipoCuenta = $_POST['txtTipoCuentaU'];
    //$business=false;
    $cheque = false;
    $credito = false;
    $otras = false;
    $caja = false;

    try {
        $stmt = $conn->prepare('UPDATE cuentas_bancarias_empresa SET Nombre = :nombre WHERE PKCuenta = :id');
        $stmt->bindValue(':nombre', $nombreCuenta);
        $stmt->bindValue(':id', $id);
        if ($stmt->execute()) {
            $bussiness = true;
        }
    } catch (PDOException $ex) {
        echo $ex->getMessage();
    }

    if ($tipoCuenta == "1") {
        try {
            $stmt = $conn->prepare('UPDATE cuentas_cheques SET Numero_Cuenta = :cuenta, CLABE = :clabe WHERE FKCuenta = :id');
            $stmt->bindValue(':cuenta', $noCuenta);
            $stmt->bindValue(':clabe', $clabe);
            $stmt->bindValue(':id', $id);
            if ($stmt->execute()) {
                $cheque = true;
            }
        } catch (PDOException $ex) {
            echo $ex->getMessage();
        }
    } elseif ($tipoCuenta == "2") {
        try {
            $stmt = $conn->prepare('UPDATE cuentas_credito SET Numero_Credito = :credito, Referencia = :referencia WHERE FKCuenta = :id');
            $stmt->bindValue(':credito', $noCredito);
            $stmt->bindValue(':referencia', $referencia);
            $stmt->bindValue(':id', $id);
            if ($stmt->execute()) {
                $credito = true;
            }
        } catch (PDOException $ex) {
            echo $ex->getMessage();
        }
    } elseif ($tipoCuenta == "3") {
        try {
            $stmt = $conn->prepare('UPDATE cuentas_otras SET Cuenta = :cuenta, Descripcion = :descripcion WHERE FKCuenta = :id');
            $stmt->bindValue(':cuenta', $idCuenta);
            $stmt->bindValue(':descripcion', $descripcion);
            $stmt->bindValue(':id', $id);
            if ($stmt->execute()) {
                $otras = true;
            }

        } catch (PDOException $ex) {
            echo $ex->getMessage();
        }
    } elseif ($tipoCuenta == "4") {
        try {
            $stmt = $conn->prepare('UPDATE cuenta_caja_chica SET Descripcion = :descripcion WHERE FKCuenta = :id');
            $stmt->bindValue(':descripcion', $descripcionCaja);
            $stmt->bindValue(':id', $id);
            if ($stmt->execute()) {
                $caja = true;
            }
        } catch (PDOException $ex) {
            echo $ex->getMessage();
        }
    }

    if (($bussiness && $cheque) || ($bussiness && $credito) || ($bussiness && $otras) || ($bussiness && $caja)) {
        echo "exito";
    } else {
        echo "fallo";
    }

}