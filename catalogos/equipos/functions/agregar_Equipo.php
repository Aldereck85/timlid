<?php
session_start();

$jwt_ruta = "../../../";
require_once '../../jwt.php';

$token = $_POST['token_4s45us'];

if(empty($_SESSION['token_ld10d'])) {
    echo "fallo";
    return;           
}

if (!hash_equals($_SESSION['token_ld10d'], $token)) {
    echo "fallo";
    return;
}

require_once('../../../include/db-conn.php');

$Equipo = $_POST['txtEquipo'];
$idUsuario = $_POST['cmbIdUsuario'];

try{
    $conn->beginTransaction();

    $stmt = $conn->prepare('INSERT INTO equipos (Nombre_Equipo, empresa_id) VALUES (:equipo, :idempresa)');
    $stmt->bindValue(':equipo',$Equipo);
    $stmt->bindValue(':idempresa',$_SESSION['IDEmpresa']);
    $stmt->execute();
    
    $idEquipo=$conn->lastInsertId();

    foreach ($_POST['cmbIdUsuario'] as $idUsuario){
       $stmt = $conn->prepare('INSERT INTO integrantes_equipo (FKEquipo, FKEmpleado) VALUES (:equipo,:fkempleado)');
       $stmt->bindValue(':equipo',$idEquipo);
       $stmt->bindValue(':fkempleado',$idUsuario);
       $stmt->execute();
    }

    if($conn->commit()){
      echo "exito";
    }
    else{
      echo "fallo";
    }

}catch(PDOException $ex){
    //echo $ex->getMessage();
    $conn->rollBack();
    echo "fallo";
}
