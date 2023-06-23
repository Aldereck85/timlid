<?php
session_start();

$_SESSION['bandera_filtraComplementos']="si";
$data['estatus']="ok";

echo json_encode($data);
?>