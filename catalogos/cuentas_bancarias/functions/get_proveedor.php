<?php
session_start();
require_once '../../../include/db-conn.php';
$PKEmpresa = $_SESSION["IDEmpresa"];
$query = sprintf('call spc_Combo_Proveedor(?)');
$stmt = $conn->prepare($query);
$stmt->execute(array($PKEmpresa));
$array = $stmt->fetchAll(PDO::FETCH_OBJ);

echo json_encode($array);