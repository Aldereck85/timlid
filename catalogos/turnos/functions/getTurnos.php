<?php
session_start();

if(isset($_POST['id'])){
    require_once('../../../include/db-conn.php');
    $json = new \stdClass();
    $id = $_POST['id'];
    
    $stmt = $conn->prepare('SELECT *, DATE_FORMAT(TiempoComida,"%H:%i") TComida FROM turnos WHERE PKTurno = :id');
    $stmt->execute(array(':id'=>$id));
    //$stmt->execute();
    $row = $stmt->fetch();

    $html = $row['Turno'];
    $html1 = $row['Entrada'];
    $html2 = $row['Salida'];
    $html3 = $row['Dias_de_trabajo'];
    $html4 = $row['TComida'];
    //$html5 = $row2['Dias'];
    $json->html = $html;
    $json->html11 = $html1;
    $json->html21 = $html2;
    $json->html31 = $html3;
    $json->html41 = $html4;
    //$json->html51 = $html5;

    $stack = array();
    $stmt2 = $conn->prepare('SELECT * FROM dias_turno WHERE FKTurno = :id');
    $stmt2->execute(array(':id'=>$id));
    while (($row2 = $stmt2->fetch()) !== false) {
        array_push($stack, $row2['Dias']);
    }
    $html5 = $stack;
    $json->html51 = $html5;



    $json = json_encode($json);
    echo $json;
}
?>