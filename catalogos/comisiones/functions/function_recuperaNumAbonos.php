<?php
session_start();
require_once('../../../include/db-conn.php');
/*Recupera el numero de las parcialidades que hay en un cálculo*/ 

$idComision = $_REQUEST["idComision"];


$stmt=$conn->prepare('SELECT COUNT(*) as numeroAbonos FROM comision_abonos WHERE id_comision = :idComision');
$stmt->bindValue(":idComision",$idComision);
$stmt->execute();
$numAbonos = $stmt->fetch();

if($numAbonos != "") {
    echo json_encode($numAbonos); 
}

?>