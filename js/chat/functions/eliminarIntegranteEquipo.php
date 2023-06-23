<?php
session_start();
if(isset($_SESSION["Usuario"])){
	require_once('../../../include/db-conn.php');

	$idUsuario = $_POST['idUsuario'];
	$idEquipo = $_POST['idEquipo']; 
	$idProyecto = $_POST['idProyecto'];

	$stmt = $conn->prepare('SELECT FKResponsable FROM proyectos WHERE PKProyecto = :idProyecto AND FKResponsable = :idUsuario');
	$stmt->bindValue(':idProyecto',$idProyecto);
	$stmt->bindValue(':idUsuario',$idUsuario);
	$stmt->execute();
	$existe = $stmt->rowCount();

	if($existe > 0){
		echo "existe";
	}
	else{
		$stmt = $conn->prepare('DELETE FROM integrantes_equipo WHERE FKEquipo = :idEquipo AND FKUsuario = :idUsuario');
		$stmt->bindValue(':idEquipo',$idEquipo);
		$stmt->bindValue(':idUsuario',$idUsuario);
			
		if($stmt->execute()){
			echo "exito";
		}
		else{
			echo "fallo";
		}
	}

}

?>