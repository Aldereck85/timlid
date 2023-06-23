<?php
session_start();
if(isset($_SESSION["Usuario"])){
	require_once('../../../include/db-conn.php');
	  
	$id = $_POST['IdActualizacion'];
	$texto = $_POST['Texto'];
	$idalertas = $_POST['IDAlertas']; 
	$idusuario = $_POST['IDUsuario'];

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

			//agrega id de notificaciones
			if($idalertas[0] != -1){

				foreach ($idalertas as $al) {

					$stmt = $conn->prepare('SELECT PKChat_Notificaciones FROM chat_notificaciones WHERE FKChat = :id AND FKUsuarioMencion = :idusuario');
					$stmt->bindValue(':id',$id);
					$stmt->bindValue(':idusuario',$idusuario);
					$stmt->execute();
					$cant = $stmt->rowCount();

					if($cant < 1){
						$stmt = $conn->prepare('INSERT INTO chat_notificaciones (FKUsuario, FKUsuarioMencion, FKChat, Visto, FechaCreacion, FKTipoNotificacion) VALUES(:usuario, :usuariomencion, :chat, 0, NOW(), 2)');
						$stmt->bindValue(':usuario',$idusuario);
						$stmt->bindValue(':usuariomencion',$al);
						$stmt->bindValue(':chat',$id);
						$stmt->execute();
					}
				}
			}

		}
		else{
			echo "fallo";
		}
	}
}

?>