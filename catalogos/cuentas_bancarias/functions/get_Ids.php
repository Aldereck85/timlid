<?php
session_start();
if(isset($_POST['idDetalle'])){
  require_once('../../../include/db-conn.php');
    $json = new \stdClass();
    $id = $_POST['idDetalle'];

    $json->pkcuenta = $id;
    $json->pkcuentaActual = $id;
   
    $json = json_encode($json);
    echo $json;
}
?>
