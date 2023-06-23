<?php
require_once('../../../include/db-conn.php');
session_start();

$stmt = $conn->prepare('SELECT cbe.PKCuenta, cbe.Nombre as Cuenta, cbc.Saldo_Inicial as saldo_actual, 1 as tipoCuenta
                        from cuentas_bancarias_empresa cbe 
                            Inner join cuentas_cheques as cbc on cbc.FKCuenta = cbe.PKCuenta
                        where cbe.estatus = "1" and cbe.empresa_id = :idempresa
                        union 
                        SELECT cbe.PKCuenta, cbe.Nombre as Cuenta, cbo.Saldo_Inicial as saldo_actual, 2 as tipoCuenta
                        from cuentas_bancarias_empresa cbe 
                            Inner join cuentas_otras as cbo on cbo.FKCuenta = cbe.PKCuenta
                        where cbe.estatus = "1" and cbe.empresa_id = :idempresa2 order by tipoCuenta, Cuenta');
$stmt->bindValue("idempresa", $_SESSION['IDEmpresa']);
$stmt->bindValue("idempresa2", $_SESSION['IDEmpresa']);
$stmt->execute();

$D=$stmt-> fetchAll(PDO::FETCH_ASSOC);

echo json_encode($D);
?>