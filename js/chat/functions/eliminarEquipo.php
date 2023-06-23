<?php
session_start();
if(isset($_SESSION["Usuario"])){
	require_once('../../../include/db-conn.php');

	$idProyecto = $_POST['idProyecto'];
	$idEquipo = $_POST['idEquipo'];

	$stmt = $conn->prepare('DELETE FROM equipos_por_proyecto WHERE FKEquipo = :idEquipo AND FKProyecto = :idProyecto');
	$stmt->bindValue(':idEquipo',$idEquipo);
	$stmt->bindValue(':idProyecto',$idProyecto);
		
	if($stmt->execute()){
		echo "exito";
	}
	else{
		echo "fallo";
	}

}

?>