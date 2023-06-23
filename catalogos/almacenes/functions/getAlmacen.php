<?php
session_start();

if(isset($_POST['id'])){
    require_once('../../../include/db-conn.php');
    $json = new \stdClass();
    $id = $_POST['id'];
    $stmt = $conn->prepare("SELECT * FROM almacenes WHERE PKAlmacen = :id");
    $stmt->execute(array(':id'=>$id));
    $stmt->execute();
    $row = $stmt->fetch();

    $html = $row['Almacen'];
     $html1 = $row['Direccion'];
     $html2 = $row['Exterior'];
     $html3 = $row['Prefijo'];
     $html4 = $row['Interior'];
     $html6 = $row['Colonia'];
     $html5 = $row['Ciudad'];
     $html7 = $row['FKEstado'];
     $html8 = $row['FKPais'];

    $json->html = $html;
    $json->html11 = $html1;
    $json->html21 = $html2;
    $json->html31 = $html3;
    $json->html41 = $html4;
    $json->html61 = $html6;
    $json->html51 = $html5;
    $json->html71 = $html7;
    $json->html81 = $html8;

    $json = json_encode($json);
    echo $json;
}
?>