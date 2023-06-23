<?php
session_start();
if(isset($_SESSION["Usuario"])){
	require_once('../../../include/db-conn.php');
	  
	$idactualizacion = $_POST['IDActualizacion'];

	$stmt = $conn->prepare('SELECT FKTarea FROM chat WHERE PKChat = :fkchat');
	$stmt->bindValue(':fkchat',$idactualizacion);
	$stmt->execute();
	$row = $stmt->fetch();

	$stmt = $conn->prepare('UPDATE chat set Anclar = 0 WHERE FKTarea = :tarea');
	$stmt->bindValue(':tarea',$row['FKTarea']);
	$stmt->execute();

	$stmt = $conn->prepare('UPDATE chat set Anclar = 1 WHERE PKChat = :fkchat');
	$stmt->bindValue(':fkchat',$idactualizacion);
	$stmt->execute();
}

?>