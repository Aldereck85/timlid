<?php
session_start();

if(isset($_POST['id'])){
    require_once('../../../../include/db-conn.php');
    $json = new \stdClass();
    $id = $_POST['id'];
    $stmt = $conn->prepare("SELECT * FROM sucursales WHERE id= :id");
    $stmt->execute(array(':id'=>$id));
    $stmt->execute();
    $row = $stmt->fetch();

    $html = $row['sucursal'];
    $html1 = $row['calle'];
    $html2 = $row['numero_exterior'];
    $html3 = $row['numero_interior'];
    $html4 = $row['colonia'];
    $html6 = $row['municipio'];
    $html5 = $row['estado_id'];
    $html7 = $row['pais_id'];
    $html8 = $row['prefijo'];
    $html9 = $row['telefono'];
    $html10 = $row['activar_inventario'];
    $html11 = $row['zona_salario_minimo'];
    $json->html = $html;
    $json->html11 = $html1;
    $json->html21 = $html2;
    $json->html31 = $html3;
    $json->html41 = $html4;
    $json->html61 = $html6;
    $json->html51 = $html5;
    $json->html71 = $html7;
    $json->html81 = $html8;
    $json->html91 = $html9;
    $json->html101 = $html10;
    $json->html102 = $html11;

    $json = json_encode($json);
    echo $json;
    $con = null;
      $db = null;
      $stmt = null;
}
?>