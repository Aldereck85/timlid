<?php
session_start();
require_once '../../../include/db-conn.php';

$PKEmpresa = $_SESSION['IDEmpresa'];
$nombre = $_POST['nombre'];
//echo json_encode(array("kjh" => $nombre));
$query = sprintf('call spc_ValidarUnicoNombreComercial_Proveedor(?,?)');
$stmt = $conn->prepare($query);
$stmt->execute(array($nombre, $PKEmpresa));
$array = $stmt->fetchAll(PDO::FETCH_OBJ);

echo json_encode($array);