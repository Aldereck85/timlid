<?php
require_once('../../../include/db-conn.php');
session_start();

$stmt = $conn->prepare('SELECT c.PKCliente AS PKData, c.razon_social AS Data 
                        from clientes c 
                        where c.estatus = 1 and c.empresa_id = :idempresa
                        ;');
$stmt->bindValue("idempresa", $_SESSION['IDEmpresa']);
$stmt->execute();

$D=$stmt-> fetchAll(PDO::FETCH_ASSOC);

echo json_encode($D);
?>
