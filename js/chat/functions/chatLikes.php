<?php
session_start();
$timestamp = date('Y-m-d H:i:s');
if (isset($_SESSION["Usuario"])) {
	require_once('../../../include/db-conn.php');

	$json = new \stdClass();

	$id = $_POST['IdActualizacion'];
	$idusuario = $_POST['idusuario'];
	$tipo = $_POST['Tipo'];

	$stmt = $conn->prepare("SELECT IFNULL(COUNT(cl.PKChat_likes),0) as cant 
                                            FROM chat as c 
                                              LEFT JOIN chat_likes as cl ON cl.FKChat = c.PKChat AND cl.Tipo = 1 
                                              WHERE c.PKChat = :chat1 
                            UNION ALL 
                            SELECT IFNULL(COUNT(cl.PKChat_likes),0) as cant 
                                FROM chat as c 
                                  LEFT JOIN chat_likes as cl ON cl.FKChat = c.PKChat AND cl.Tipo = 2 
                                  WHERE c.PKChat = :chat2");
	$stmt->bindParam(':chat1', $id);
	$stmt->bindParam(':chat2', $id);
	$stmt->execute();
	$row_nl = $stmt->fetchAll();

	$likes_cantidad = $row_nl[0]['cant'];
	$dislikes_cantidad = $row_nl[1]['cant'];

	//Me gusta
	if ($tipo == 1) {

		$stmt = $conn->prepare('SELECT PKChat_likes FROM chat_likes WHERE FKChat = :fkchat AND FKUsuario = :fkusuario AND Tipo = 1');
		$stmt->bindValue(':fkchat', $id);
		$stmt->bindValue(':fkusuario', $idusuario);
		$stmt->execute();
		$nr = $stmt->rowCount();
		$row = $stmt->fetchAll();

		$stmt = $conn->prepare('SELECT PKChat_likes FROM chat_likes WHERE FKChat = :fkchat AND FKUsuario = :fkusuario AND Tipo = 2');
		$stmt->bindValue(':fkchat', $id);
		$stmt->bindValue(':fkusuario', $idusuario);
		$stmt->execute();
		$nr2 = $stmt->rowCount();

		if ($nr2 < 1) {

			try {
				$conn->beginTransaction();
				if ($nr > 0) {

					$stmt = $conn->prepare('DELETE FROM chat_likes WHERE FKChat = :fkchat AND FKUsuario = :fkusuario AND Tipo = 1');
					$stmt->bindValue(':fkchat', $id);
					$stmt->bindValue(':fkusuario', $idusuario);

					if ($stmt->execute()) {
						$json->res = "1";
						$json->likes_cantidad = $likes_cantidad - 1;
					} else {
						$json->res = "0";
						$json->likes_cantidad = $likes_cantidad;
					}
				} else {

					$stmt = $conn->prepare('INSERT INTO chat_likes (FKChat, FKUsuario, Tipo) VALUES (:fkchat, :fkusuario, 1)');
					$stmt->bindValue(':fkchat', $id);
					$stmt->bindValue(':fkusuario', $idusuario);

					if ($stmt->execute()) {

						/* NOTIFICACIONES */
						$stmt = $conn->prepare('SELECT chat.FKTarea, chat.FKUsuario, tareas.FKProyecto 
						FROM chat_likes
						INNER JOIN chat ON chat_likes.FKChat = chat.PKChat
						INNER JOIN tareas ON chat.FKTarea = tareas.PKTarea
						WHERE chat_likes.FKChat = :fkchat AND chat_likes.FKUsuario = :fkusuario');
						$stmt->execute([':fkchat' => $id, ':fkusuario' => $idusuario]);
						$tarea = $stmt->fetch(PDO::FETCH_ASSOC);
						/* INSERTAMOS LA NOTIFICACION EN LA BD */
						$stmt = $conn->prepare('INSERT INTO notificaciones (tipo_notificacion, detalle_tipo_notificacion, id_elemento, id_sub_elemento, created_at, usuario_creo, usuario_recibe) VALUES (:tipoNot, :detaleNot, :idElem, :idSubElem, :fecha, :usrCreo, :usrRecibe)');
						$insertedNot = $stmt->execute([':tipoNot' => 2, ':detaleNot' => 24, ':idElem' => $tarea['FKProyecto'], ':idSubElem' => $tarea['FKTarea'], ':fecha' => $timestamp, ':usrCreo' => $_SESSION['PKUsuario'], ':usrRecibe' => $tarea['FKUsuario']]);

						$json->res = "2";
						$json->likes_cantidad = $likes_cantidad + 1;
					} else {
						$json->res = "0";
						$json->likes_cantidad = $likes_cantidad;
					}
				}
				$conn->commit();
			} catch (PDOException $ex) {
				$conn->rollBack();
				echo $ex->getMessage();
			}
		} else {
			try {
				$conn->beginTransaction();
				//cuando ya tiene no me gusta
				$stmt = $conn->prepare('DELETE FROM chat_likes WHERE FKChat = :fkchat AND FKUsuario = :fkusuario AND Tipo = 2');
				$stmt->bindValue(':fkchat', $id);
				$stmt->bindValue(':fkusuario', $idusuario);
				$stmt->execute();

				$stmt = $conn->prepare('INSERT INTO chat_likes (FKChat, FKUsuario, Tipo) VALUES (:fkchat, :fkusuario, 1)');
				$stmt->bindValue(':fkchat', $id);
				$stmt->bindValue(':fkusuario', $idusuario);
				$stmt->execute();

				/* NOTIFICACIONES */
				$stmt = $conn->prepare('SELECT chat.FKTarea, chat.FKUsuario, tareas.FKProyecto 
				FROM chat_likes
				INNER JOIN chat ON chat_likes.FKChat = chat.PKChat
				INNER JOIN tareas ON chat.FKTarea = tareas.PKTarea
				WHERE chat_likes.FKChat = :fkchat AND chat_likes.FKUsuario = :fkusuario');
				$stmt->execute([':fkchat' => $id, ':fkusuario' => $idusuario]);
				$tarea = $stmt->fetch(PDO::FETCH_ASSOC);
				/* INSERTAMOS LA NOTIFICACION EN LA BD */
				$stmt = $conn->prepare('INSERT INTO notificaciones (tipo_notificacion, detalle_tipo_notificacion, id_elemento, id_sub_elemento, created_at, usuario_creo, usuario_recibe) VALUES (:tipoNot, :detaleNot, :idElem, :idSubElem, :fecha, :usrCreo, :usrRecibe)');
				$stmt->execute([':tipoNot' => 2, ':detaleNot' => 24, ':idElem' => $tarea['FKProyecto'], ':idSubElem' => $tarea['FKTarea'], ':fecha' => $timestamp, ':usrCreo' => $_SESSION['PKUsuario'], ':usrRecibe' => $tarea['FKUsuario']]);

				$json->res = "3";
				$json->likes_cantidad = $likes_cantidad + 1;
				$json->dislikes_cantidad = $dislikes_cantidad - 1;

				$conn->commit();
			} catch (PDOException $ex) {
				$conn->rollBack();
				echo $ex->getMessage();
			}
		}
	}

	//No me gusta
	if ($tipo == 2) {

		$stmt = $conn->prepare('SELECT PKChat_likes FROM chat_likes WHERE FKChat = :fkchat AND FKUsuario = :fkusuario AND Tipo = 2');
		$stmt->bindValue(':fkchat', $id);
		$stmt->bindValue(':fkusuario', $idusuario);
		$stmt->execute();
		$nr = $stmt->rowCount();
		$row = $stmt->fetchAll();

		$stmt = $conn->prepare('SELECT PKChat_likes FROM chat_likes WHERE FKChat = :fkchat AND FKUsuario = :fkusuario AND Tipo = 1');
		$stmt->bindValue(':fkchat', $id);
		$stmt->bindValue(':fkusuario', $idusuario);
		$stmt->execute();
		$nr2 = $stmt->rowCount();

		if ($nr2 < 1) {
			try {
				$conn->beginTransaction();

				if ($nr > 0) {
					$stmt = $conn->prepare('DELETE FROM chat_likes WHERE FKChat = :fkchat AND FKUsuario = :fkusuario AND Tipo = 2');
					$stmt->bindValue(':fkchat', $id);
					$stmt->bindValue(':fkusuario', $idusuario);

					if ($stmt->execute()) {
						$json->res = "1";
						$json->dislikes_cantidad = $dislikes_cantidad - 1;
					} else {
						$json->res = "0";
						$json->dislikes_cantidad = $dislikes_cantidad;
					}
				} else {
					$stmt = $conn->prepare('INSERT INTO chat_likes (FKChat, FKUsuario, Tipo) VALUES (:fkchat, :fkusuario, 2)');
					$stmt->bindValue(':fkchat', $id);
					$stmt->bindValue(':fkusuario', $idusuario);

					if ($stmt->execute()) {
						/* NOTIFICACIONES */
						$stmt = $conn->prepare('SELECT chat.FKTarea, chat.FKUsuario, tareas.FKProyecto 
						FROM chat_likes
						INNER JOIN chat ON chat_likes.FKChat = chat.PKChat
						INNER JOIN tareas ON chat.FKTarea = tareas.PKTarea
						WHERE chat_likes.FKChat = :fkchat AND chat_likes.FKUsuario = :fkusuario');
						$stmt->execute([':fkchat' => $id, ':fkusuario' => $idusuario]);
						$tarea = $stmt->fetch(PDO::FETCH_ASSOC);
						/* INSERTAMOS LA NOTIFICACION EN LA BD */
						$stmt = $conn->prepare('INSERT INTO notificaciones (tipo_notificacion, detalle_tipo_notificacion, id_elemento, id_sub_elemento, created_at, usuario_creo, usuario_recibe) VALUES (:tipoNot, :detaleNot, :idElem, :idSubElem, :fecha, :usrCreo, :usrRecibe)');
						$stmt->execute([':tipoNot' => 2, ':detaleNot' => 25, ':idElem' => $tarea['FKProyecto'], ':idSubElem' => $tarea['FKTarea'], ':fecha' => $timestamp, ':usrCreo' => $_SESSION['PKUsuario'], ':usrRecibe' => $tarea['FKUsuario']]);

						$json->res = "2";
						$json->dislikes_cantidad = $dislikes_cantidad + 1;
					} else {
						$json->res = "0";
						$json->dislikes_cantidad = $dislikes_cantidad;
					}
				}
				$conn->commit();
			} catch (PDOException $ex) {
				$conn->rollBack();
				echo $ex->getMessage();
			}
		} else {
			try {
				$conn->beginTransaction();
				//cuando ya tiene me gusta
				$stmt = $conn->prepare('DELETE FROM chat_likes WHERE FKChat = :fkchat AND FKUsuario = :fkusuario AND Tipo = 1');
				$stmt->bindValue(':fkchat', $id);
				$stmt->bindValue(':fkusuario', $idusuario);
				$stmt->execute();

				$stmt = $conn->prepare('INSERT INTO chat_likes (FKChat, FKUsuario, Tipo) VALUES (:fkchat, :fkusuario, 2)');
				$stmt->bindValue(':fkchat', $id);
				$stmt->bindValue(':fkusuario', $idusuario);
				$stmt->execute();

				/* NOTIFICACIONES */
				$stmt = $conn->prepare('SELECT chat.FKTarea, chat.FKUsuario, tareas.FKProyecto 
				FROM chat_likes
				INNER JOIN chat ON chat_likes.FKChat = chat.PKChat
				INNER JOIN tareas ON chat.FKTarea = tareas.PKTarea
				WHERE chat_likes.FKChat = :fkchat AND chat_likes.FKUsuario = :fkusuario');
				$stmt->execute([':fkchat' => $id, ':fkusuario' => $idusuario]);
				$tarea = $stmt->fetch(PDO::FETCH_ASSOC);
				/* INSERTAMOS LA NOTIFICACION EN LA BD */
				$stmt = $conn->prepare('INSERT INTO notificaciones (tipo_notificacion, detalle_tipo_notificacion, id_elemento, id_sub_elemento, created_at, usuario_creo, usuario_recibe) VALUES (:tipoNot, :detaleNot, :idElem, :idSubElem, :fecha, :usrCreo, :usrRecibe)');
				$stmt->execute([':tipoNot' => 2, ':detaleNot' => 25, ':idElem' => $tarea['FKProyecto'], ':idSubElem' => $tarea['FKTarea'], ':fecha' => $timestamp, ':usrCreo' => $_SESSION['PKUsuario'], ':usrRecibe' => $tarea['FKUsuario']]);

				$json->res = "4";
				$json->likes_cantidad = $likes_cantidad - 1;
				$json->dislikes_cantidad = $dislikes_cantidad + 1;

				$conn->commit();
			} catch (PDOException $ex) {
				$conn->rollBack();
				echo $ex->getMessage();
			}
		}
	}

	$json = json_encode($json);
	echo $json;
}
