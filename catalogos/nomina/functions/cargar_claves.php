<?php
session_start();

$jwt_ruta = "../../../";
require_once '../../jwt.php';

$token = $_POST['csr_token_UT5JP'];

if(empty($_SESSION['token_ld10d'])) {
    echo "fallo";
    return;           
}

if (!hash_equals($_SESSION['token_ld10d'], $token)) {
    echo "fallo";
    return;
}

require_once '../../../include/db-conn.php';


$tipo = $_POST['tipo'];

if($tipo == 1){
    $stmt = $conn->prepare('SELECT id, codigo, concepto FROM tipo_percepcion');
    if($stmt->execute()){

        $claves = $stmt->fetchAll();
          foreach ($claves as $c) {
            echo "<option value='".$c['id']."'>".$c["codigo"]." - ".$c["concepto"]."</option>";
          }
    }
    else{
        echo "fallo";
        return;   
    }
}
else{
    $stmt = $conn->prepare('SELECT id, codigo, concepto FROM tipo_deduccion');
    if($stmt->execute()){

        $claves = $stmt->fetchAll();
          foreach ($claves as $c) {
            echo "<option value='".$c['id']."'>".$c["codigo"]." - ".$c["concepto"]."</option>";
          }
    }
    else{
        echo "fallo";
        return;   
    }
}

?>
