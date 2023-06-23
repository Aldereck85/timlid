<?php
session_start();
$data['estatus']='no';

if (isset($_SESSION["bandera_filtraComplementos"])) {
    $data['estatus']='ok';
    unset($_SESSION["bandera_filtraComplementos"]);
  }

echo json_encode($data);
?>