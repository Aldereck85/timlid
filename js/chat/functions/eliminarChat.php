<?php
session_start();
if(isset($_SESSION["Usuario"])){
	require_once('../../../include/db-conn.php');

	$idTarea = $_POST['idTarea'];
	$idUsuario = $_POST['IDUsuario'];

	$stmt = $conn->prepare('SELECT p.FKResponsable FROM tareas as t INNER JOIN proyectos as p ON t.FKProyecto = p.PKProyecto WHERE t.PKTarea = :idTarea');
	$stmt->bindValue(':idTarea',$idTarea);
	$stmt->execute();
	$row = $stmt->fetch();

	if($row['FKResponsable'] != $idUsuario){
		echo "negado";
	}
	else{

		$stmt = $conn->prepare('DELETE FROM chat WHERE FKTarea = :idTarea');
		$stmt->bindValue(':idTarea',$idTarea);
		
		if($stmt->execute()){
			echo "exito";
		}
		else{
			echo "fallo";
		}

		
	}

}

?>