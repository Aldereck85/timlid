<?php
session_start();
if(isset($_SESSION["Usuario"])){
	require_once('../../../include/db-conn.php');

	$json = new \stdClass();

	$idEquipo = $_POST['idEquipo'];
	$idUsuario = $_POST['idUsuario'];
	$idProyecto = $_POST['idProyecto'];

	$stmt = $conn->prepare('SELECT CONCAT(e.Primer_Nombre," ",e.Apellido_Paterno) as nombreEmpleado FROM empleados as e INNER JOIN usuarios as u ON u.FKEmpleado = e.PKEmpleado WHERE u.PKUsuario = :fkusuario');
	$stmt->bindValue(':fkusuario',$idUsuario);
	$stmt->execute();
	$row = $stmt->fetch();
	$json->nombreEmpleado = $row['nombreEmpleado'];

	$stmt = $conn->prepare('SELECT PKIntegrante FROM integrantes_proyecto WHERE FKUsuario = :fkusuario AND FKProyecto = :fkproyecto');
	$stmt->bindValue(':fkusuario',$idUsuario);
	$stmt->bindValue(':fkproyecto',$idProyecto);
	$stmt->execute();
	$existe = $stmt->rowCount();

	if($existe > 0){
		$json->estado = "existe";
	}
	else{
		$stmt = $conn->prepare('INSERT INTO integrantes_equipo (FKEquipo, FKUsuario) VALUES (:fkequipo, :fkusuario)');
		$stmt->bindValue(':fkequipo',$idEquipo);
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