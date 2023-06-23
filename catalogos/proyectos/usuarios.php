<?php
require_once('../../include/db-conn.php');

$nombre = trim($_GET['nombre']);
$stmt = $conn->prepare('SELECT u.PKUsuario, u.Usuario, IFNULL(CONCAT(e.Nombres," ", e.PrimerApellido, " ", e.SegundoApellido), u.Nombre) as nombre_usuario FROM usuarios as u LEFT JOIN empleados_usuarios as eu ON eu.FKUsuario = u.PKUsuario LEFT JOIN empleados as e ON e.PKEmpleado = eu.FKEmpleado WHERE CONCAT(e.Nombres," ", e.PrimerApellido, " ", e.SegundoApellido) LIKE ? ');
$stmt->bindValue(1,"%".$nombre."%");
$stmt->execute();
$row = $stmt->fetchAll();

//print_r($row);

$myJSON = json_encode($row);

echo $myJSON;

?>