<?php
session_start();
if(isset($_SESSION["Usuario"])){
	require_once('../../../include/db-conn.php');
	  
	$id = $_POST['IdActualizacion'];
	$idusuario = $_POST['idusuario'];

	$stmt = $conn->prepare('DELETE FROM chat_favoritos WHERE FKChat = :chat AND FKUsuario = :usuario');
	$stmt->bindValue(':chat',$id);
	$stmt->bindValue(':usuario',$idusuario);

	if($stmt->execute()){
		echo "exito";
	}
	else{
		echo "fallo";
	}
	
}

?>