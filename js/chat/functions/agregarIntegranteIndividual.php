<?php
session_start();
if(isset($_SESSION["Usuario"])){
	require_once('../../../include/db-conn.php');

	$json = new \stdClass();

	$idUsuario = $_POST['idUsuario'];
	$idProyecto = $_POST['idProyecto'];

	$stmt = $conn->prepare('SELECT FKEquipo FROM integrantes_equipo WHERE FKEquipo IN (SELECT FKEquipo FROM equipos_por_proyecto WHERE FKProyecto = :fkproyecto) AND FKUsuario = :fkusuario');
	$stmt->bindValue(':fkproyecto',$idProyecto);
	$stmt->bindValue(':fkusuario',$idUsuario);
	$stmt->execute();
	$existe = $stmt->rowCount();

	if($existe > 0){
		$json->estado = "existe";
	}
	else{
		$stmt = $conn->prepare('INSERT INTO integrantes_proyecto (FKProyecto, FKUsuario) VALUES (:fkproyecto, :fkusuario)');
		$stmt->bindValue(':fkproyecto',$idProyecto);
		$stmt->bindValue(':fkusuario',$idUsuario);
		
		if($stmt->execute()){
			$json->estado = "exito";
		}
		else{
			$json->estado = "fallo";
		}
	}

	$json = json_encode($json);
	echo $json;

}

?>