<?php
session_start();
if(isset($_SESSION["Usuario"])){
	require_once('../../../include/db-conn.php');
	  
	$id = $_POST['IdActualizacion'];
	$idusuario = $_POST['idusuario'];

	$stmt = $conn->prepare('INSERT INTO chat_favoritos (FKChat, FKUsuario) VALUES (:fkchat, :fkusuario)');
	$stmt->bindValue(':fkchat',$id);
	$stmt->bindValue(':fkusuario',$idusuario);

	if($stmt->execute()){
		echo "exito";
	}
	else{
		echo "fallo";
	}
}

?>