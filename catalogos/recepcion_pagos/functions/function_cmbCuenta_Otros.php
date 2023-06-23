<?php
require_once('../../../include/db-conn.php');
session_start();

$stmt = $conn->prepare('call spc_Combo_Cuentas_otras(:idempresa)');
$stmt->bindValue("idempresa", $_SESSION['IDEmpresa']);
$stmt->execute();

$D=$stmt-> fetchAll(PDO::FETCH_ASSOC);

echo json_encode($D);
?>

