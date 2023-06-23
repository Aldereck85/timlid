<?php
session_start();
if(isset($_SESSION["Usuario"])){
	require_once('../../../include/db-conn.php');
	  
	$id = $_POST['IdActualizacion'];
	$texto = $_POST['Texto'];

	$stmt = $conn->prepare('SELECT FKUsuario FROM chat WHERE PKChat = :id');
	$stmt->bindValue(':id',$id);
	$stmt->execute();
	$row = $stmt->fetch();

	if($row['FKUsuario'] != $_SESSION['PKUsuario']){
		echo "fallo";
	}
	else{
		$stmt = $conn->prepare('UPDATE chat set Contenido = :content WHERE PKChat = :id');
		$stmt->bindValue(':content',$texto);
		$stmt->bindValue(':id',$id);

		if($stmt->execute()){
			echo "exito";
		}
		else{
			echo "fallo";
		}
	}
}

?>