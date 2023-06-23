<?php
require_once('../../../include/db-conn.php');
session_start();

$stmt = $conn->prepare('SELECT c.PKCliente, 
                        c.razon_social 
                        from clientes c 
                        where c.estatus = 1 and c.empresa_id = :idempresa order by c.razon_social asc;');
$stmt->bindValue("idempresa", $_SESSION['IDEmpresa']);
$stmt->execute();

$D=$stmt-> fetchAll(PDO::FETCH_ASSOC);

echo json_encode($D);
?>
