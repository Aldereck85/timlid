<?php
session_start();
$data['estatus']='no';

if (isset($_SESSION["bandera_filtraCliente_RecepcionPagos"])) {
    $data['estatus']='ok';
    $data['result']=$_SESSION["bandera_filtraCliente_RecepcionPagos"];
    unset($_SESSION["bandera_filtraCliente_RecepcionPagos"]);
  }

echo json_encode($data);
?>