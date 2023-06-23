<?php
session_start();
if(isset($_SESSION["Usuario"])){
	require_once('../../../include/db-conn.php');

	$idProyecto = $_POST['idProyecto'];
	$idUsuario = $_POST['idUsuario'];

	$stmt = $conn->prepare('SELECT FKResponsable FROM proyectos WHERE PKProyecto = :idProyecto');
	$stmt->bindValue(':idProyecto',$idProyecto);
	$stmt->execute();
	$row = $stmt->fetch();

	if($idUsuario == $row['FKResponsable']){
		echo "igual";
		exit();
	}

	$stmt = $conn->prepare('UPDATE proyectos SET FKResponsable = :idUsuario WHERE PKProyecto = :idProyecto');
	$stmt->bindValue(':idUsuario',$idUsuario);
	$stmt->bindValue(':idProyecto',$idProyecto);
	
	if($stmt->execute()){
		echo "exito";
	}
	else{
		echo "fallo";
	}

}

?>