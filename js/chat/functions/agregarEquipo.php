<?php
session_start();
if(isset($_SESSION["Usuario"])){
	require_once('../../../include/db-conn.php');

	$json = new \stdClass();

	$idEquipo = $_POST['idEquipo'];
	$idProyecto = $_POST['idProyecto'];

	$stmt = $conn->prepare('INSERT INTO equipos_por_proyecto (FKProyecto, FKEquipo) VALUES (:fkproyecto, :fkequipo)');
	$stmt->bindValue(':fkproyecto',$idProyecto);
	$stmt->bindValue(':fkequipo',$idEquipo);
	
	if($stmt->execute()){
		$json->estado = "exito";
	}
	else{
		$json->estado = "fallo";
	}

	$json = json_encode($json);
	echo $json;

}

?>