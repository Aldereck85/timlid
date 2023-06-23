<?php
session_start();
require_once('../../../include/db-conn.php');
/*Recupera el numero de las parcialidades los totales de un vendedor*/ 

$idVendedor = $_REQUEST["idVendedor"];
$empresa = $_SESSION["IDEmpresa"];


$stmt=$conn->prepare('SELECT sum(c.monto_ingresado) as totalComPagadas FROM comisiones c WHERE c.estatus=2 and c.id_empleado=:idVendedor and c.id_empresa=:empresa');
$stmt->bindValue(":idVendedor",$idVendedor);
$stmt->bindValue(":empresa",$empresa);
$stmt->execute();
$totalComPagadas = $stmt->fetchAll();
$stmt->closeCursor();
$data['totalComPagadas']=$totalComPagadas[0]['totalComPagadas'];

$stmt=$conn->prepare('SELECT sum(c.saldo_insoluto) as totalSIPendiente FROM comisiones c WHERE c.estatus=1 and c.id_empleado=:idVendedor and c.id_empresa=:empresa');
$stmt->bindValue(":idVendedor",$idVendedor);
$stmt->bindValue(":empresa",$empresa);
$stmt->execute();
$totalSIPendiente = $stmt->fetchAll();
$stmt->closeCursor();
$data['totalSIPendiente']=$totalSIPendiente[0]['totalSIPendiente'];

$stmt=$conn->prepare('SELECT sum(c.saldo_insoluto) as totalSIParcial FROM comisiones c WHERE c.estatus=3 and c.id_empleado=:idVendedor and c.id_empresa=:empresa');
$stmt->bindValue(":idVendedor",$idVendedor);
$stmt->bindValue(":empresa",$empresa);
$stmt->execute();
$totalSIParcial = $stmt->fetchAll();
$stmt->closeCursor();
$data['totalSIParcial']=$totalSIParcial[0]['totalSIParcial'];


echo json_encode($data); 
?>