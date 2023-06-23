<?php
session_start();

$jwt_ruta = "../../../";
require_once '../../jwt.php';

$json = new \stdClass();

$token = $_POST['csr_token_UT5JP'];

if(empty($_SESSION['token_ld10d'])) {
    $json->respuesta = "fallo";
    return;           
}

if (!hash_equals($_SESSION['token_ld10d'], $token)) {
    $json->respuesta = "fallo";
    return;
}


$idConcepto = $_POST['idConcepto'];
$tipo = $_POST['tipo'];
require_once('../../../include/db-conn.php');

if($tipo == 1){
    $stmt = $conn->prepare("SELECT id, clave, tipo_percepcion_id FROM relacion_tipo_percepcion WHERE id = :id");
}
else{
    $stmt = $conn->prepare("SELECT id, clave, tipo_deduccion_id FROM relacion_tipo_deduccion WHERE id = :id");
}

if($stmt->execute(array(':id'=>$idConcepto))){
    $json->respuesta = "exito";
}
else{
    $json->respuesta = "fallo";
    return;
}
$row = $stmt->fetch();

$json->clave = $row['clave'];

$select = "";
if($tipo == 1){
    $stmt = $conn->prepare('SELECT id, codigo, concepto FROM tipo_percepcion');
    if($stmt->execute()){

        $claves = $stmt->fetchAll();
          foreach ($claves as $c) {
            $select .= "<option value='".$c['id']."' ";

                if($c['id'] == $row['tipo_percepcion_id']){
                    $select .= "selected ";
                }

            $select .= ">".$c["codigo"]." - ".$c["concepto"]."</option>";
          }
    }
    else{
        $json->respuesta = "fallo";
        return;   
    }
    $json->movimiento_id = $row['tipo_percepcion_id'];
}
else{
    $stmt = $conn->prepare('SELECT id, codigo, concepto FROM tipo_deduccion');
    if($stmt->execute()){

        $claves = $stmt->fetchAll();
          foreach ($claves as $c) {
            $select .= "<option value='".$c['id']."' ";

                if($c['id'] == $row['tipo_deduccion_id']){
                    $select .= "selected ";
                }

            $select .= ">".$c["codigo"]." - ".$c["concepto"]."</option>";
          }
    }
    else{
        $json->respuesta = "fallo";
        return;   
    }
    $json->movimiento_id = $row['tipo_deduccion_id'];
}

$json->select = $select;

$json = json_encode($json);
echo $json;

?>