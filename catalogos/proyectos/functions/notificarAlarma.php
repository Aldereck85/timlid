<?php
session_start();
if(isset($_SESSION["Usuario"])){
	require_once('../../../include/db-conn.php');
	  
	$idusuario = $_POST['IDUsuario'];

	date_default_timezone_set('America/Mexico_City');
	$start = date('H:i');

	/*$fechaarray = explode(" ",$tiempo);
	$fechasolo = explode("/",$fechaarray[0]);
	$fechacorrecta = $fechasolo[2]."-".$fechasolo[1]."-".$fechasolo[0]." ".$fechaarray[1];*/

	$stmt = $conn->prepare('SELECT PKChat_Alertas, Tiempo, FKChat FROM chat_alertas WHERE FKUsuario = :idusuario AND Tiempo = DATE_FORMAT(NOW(),"%Y-%m-%d %H:%i:00") AND Visto = 0');
	$stmt->bindValue(':idusuario',$idusuario);
	$stmt->execute();
	$row = $stmt->fetch();
	$nc = $stmt->rowCount();

	$json = new \stdClass();
	$json->nc = $nc;

	//$date = date_create($row['Tiempo']);
	//echo date_format($date, 'd-m-Y H:i:s');

	$json->FKChat = 0;
	if($nc > 0){
		$stmt = $conn->prepare('UPDATE chat_alertas SET Visto = 1  WHERE PKChat_Alertas = :chat_alertas');
		$stmt->bindValue(':chat_alertas',$row[0]);
		$stmt->execute();
		$json->FKChat = $row[2];
	}

	$json = json_encode($json);
	echo $json;
}

?>