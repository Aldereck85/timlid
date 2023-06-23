<?php
session_start();
require_once('../../../include/db-conn.php');
/*Recupera el saldo insoluto de una comisión*/ 

$idComision = $_REQUEST['idComision'];
    
 $stmt = $conn->prepare('SELECT c.saldo_insoluto FROM comisiones c WHERE c.id=:idComision');
    
$stmt->bindValue(":idComision",$idComision);
$stmt->execute();
    
$saldoInsoluto=$stmt-> fetch(PDO::FETCH_ASSOC);
echo json_encode($saldoInsoluto);
?>