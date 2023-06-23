<?php
require_once('../../../include/db-conn.php');
session_start();

$stmt = $conn->prepare('call spc_Combo_Formas_Pago_Sat()');
$stmt->execute();

$D=$stmt-> fetchAll(PDO::FETCH_ASSOC);

echo json_encode($D);
?>