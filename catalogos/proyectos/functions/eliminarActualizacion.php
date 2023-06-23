<?php
session_start();
if(isset($_SESSION["Usuario"])){
	require_once('../../../include/db-conn.php');
	  
	$id = $_POST['IDActualizacion'];

	$stmt = $conn->prepare('SELECT FKUsuario FROM chat WHERE PKChat = :id');
	$stmt->bindValue(':id',$id);
	$stmt->execute();
	$row = $stmt->fetch();

	if($row['FKUsuario'] != $_SESSION['PKUsuario']){
		echo "fallo";
	}
	else{
		$stmt = $conn->prepare('DELETE FROM chat WHERE PKChat = :id');
		$stmt->bindValue(':id',$id);

		if($stmt->execute()){
			$stmt = $conn->prepare('DELETE FROM chat WHERE ChatPadre = :id');
			$stmt->bindValue(':id',$id);
			$stmt->execute();

			echo "exito";
		}
		else{
			echo "fallo";
		}
	}
}

?>