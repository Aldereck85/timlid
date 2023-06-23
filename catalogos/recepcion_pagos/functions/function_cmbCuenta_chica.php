<?php
require_once('../../../include/db-conn.php');
session_start();

$stmt = $conn->prepare("SELECT cbe.PKCuenta, cbe.Nombre as Cuenta, cbc.SaldoInicialCaja as saldo_actual
                        from cuentas_bancarias_empresa cbe 
                            Inner join cuenta_caja_chica as cbc on cbc.FKCuenta = cbe.PKCuenta
                        where cbe.estatus = '1' and cbe.empresa_id = :_PKEmpresa order by Cuenta asc;");
$stmt->bindValue(":_PKEmpresa", $_SESSION['IDEmpresa']);
$stmt->execute();

$D=$stmt-> fetchAll(PDO::FETCH_ASSOC);

echo json_encode($D);
?>

