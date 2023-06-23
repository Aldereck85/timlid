<?php
require_once('../../../include/db-conn.php');
session_start();
$idEmpresa=$_SESSION['IDEmpresa'];
$cliente = ($_REQUEST['id']);
  
$stmt = $conn->prepare("SELECT PKCliente from clientes where PKCliente=:cliente;");  
$stmt->bindValue(":cliente",$cliente);
$stmt->execute();
$res=$stmt->rowCount();

if($res==1){
    $data['estatus']="ok";
}else{
    $data['estatus']="no";
}
  
echo json_encode($data);  
?>
