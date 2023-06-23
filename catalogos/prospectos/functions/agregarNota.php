<?php
session_start();
require_once('../../../include/db-conn.php');

  $nota = $_POST['Nota'];
  $cliente = $_POST['Cliente'];
  $fecha = $_POST['Fecha'];

  $fechaarray = explode(" ",$fecha);
  $fechasolo = explode("/",$fechaarray[0]);
  $fechacorrecta = $fechasolo[2]."-".$fechasolo[1]."-".$fechasolo[0]." ".$fechaarray[1];

  try{

    $stmt = $conn->prepare('INSERT INTO bitacora_notas (Nota, FKCliente, FechaAlta, FechaModificacion) VALUES (:nota, :cliente, :fecha, :fechaMod)');
    $stmt->bindValue(':nota',$nota);
    $stmt->bindValue(':cliente',$cliente);
    $stmt->bindValue(':fecha',$fechacorrecta);
    $stmt->bindValue(':fechaMod',$fechacorrecta);
    $stmt->execute();

    $id = $conn->lastInsertId();

    echo $id;

  }catch(PDOException $ex){
    echo $ex->getMessage();
  }
 ?>
