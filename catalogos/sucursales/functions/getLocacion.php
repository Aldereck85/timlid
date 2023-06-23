<?php
session_start();

if(isset($_POST['id'])){
    require_once('../../../include/db-conn.php');
    $json = new \stdClass();
    $id = $_POST['id'];
    $stmt = $conn->prepare("SELECT * FROM locaciones WHERE PKLocacion= :id");
    $stmt->execute(array(':id'=>$id));
    $stmt->execute();
    $row = $stmt->fetch();

    $html = $row['Locacion'];
    $html1 = $row['Calle'];
    $html2 = $row['NumExt'];
    $html3 = $row['NumInt'];
    $html4 = $row['Colonia'];
    $html6 = $row['Municipio'];
    $html5 = $row['FKEstado'];
    $html7 = $row['FKPais'];
    $html8 = $row['Prefijo'];
    $html9 = $row['Telefono'];
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

    $json = json_encode($json);
    echo $json;
}
?>