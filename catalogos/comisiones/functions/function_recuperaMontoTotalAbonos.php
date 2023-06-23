<?php
session_start();
require_once('../../../include/db-conn.php');
/*Recupera el monto total de parcialidades*/

$idComision = $_REQUEST["idComision"];

$stmt = $conn->prepare('SELECT ifnull(sum(monto_abono),0) as totalAbonos FROM comision_abonos WHERE id_comision=:idComision');
$stmt->bindValue(":idComision",$idComision);
$stmt->execute();
$totalAbonos = $stmt->fetch(PDO::FETCH_ASSOC);

echo json_encode($totalAbonos);
?>