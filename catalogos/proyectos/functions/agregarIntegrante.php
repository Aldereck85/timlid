<?php
session_start();
if(isset($_SESSION["Usuario"])){
	require_once('../../../include/db-conn.php');

	$idEquipo = $_POST['idEquipo'];
	$idUsuario = $_POST['idUsuario'];

	$stmt = $conn->prepare('SELECT PKIntegrante FROM integrantes_equipo WHERE FKEquipo = :fkequipo AND FKUsuario = :fkusuario');
	$stmt->bindValue(':fkequipo',$idEquipo);
	$stmt->bindValue(':fkusuario',$idUsuario);
	$stmt->execute();
	$nc = $stmt->rowCount();

	if($nc < 1){
		$stmt = $conn->prepare('INSERT INTO integrantes_equipo (FKEquipo, FKUsuario) VALUES (:fkequipo, :fkusuario)');
		$stmt->bindValue(':fkequipo',$idEquipo);
		$stmt->bindValue(':fkusuario',$idUsuario);
		$stmt->execute();

		echo "exito";
	}

}

?>