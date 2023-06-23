<?php
session_start();
require_once('../../../include/db-conn.php');
/*Recupera los vendedores para mostrarlos en el select*/

$PKEmpresa = $_SESSION["IDEmpresa"];

$query = sprintf('call spc_Combo_Vendedor(?)');
$stmt = $conn->prepare($query);
$stmt->execute(array($PKEmpresa));
$array = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($array);
?>