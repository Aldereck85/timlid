<?php
require_once('../../../include/db-conn.php');
session_start();

$stmt = $conn->prepare("SELECT cbe.PKCuenta, cbe.Nombre as Cuenta, cpv.saldo_inicial as saldo_actual
                        from cuentas_bancarias_empresa cbe 
                            Inner join cuentas_punto_venta as cpv on cpv.cuenta_empresa_id = cbe.PKCuenta
                        where cbe.estatus = '1' and cbe.empresa_id = :_PKEmpresa order by Cuenta asc;");
$stmt->bindValue(":_PKEmpresa", $_SESSION['IDEmpresa']);
$stmt->execute();

$D=$stmt-> fetchAll(PDO::FETCH_ASSOC);

echo json_encode($D);
?>

