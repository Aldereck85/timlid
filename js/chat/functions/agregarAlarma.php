<?php
session_start();
if(isset($_SESSION["Usuario"])){
	require_once('../../../include/db-conn.php');
	  
	$idusuario = $_POST['IDUsuario'];
	$tiempo = $_POST['Tiempo'];
	$idactualizacion = $_POST['IDActualizacion'];

	date_default_timezone_set('America/Mexico_City');
	$start = date('H:i');

	if($tiempo == '20m'){
		$horaAlarma = date('Y-m-d H:i',strtotime('+20 minutes',strtotime($start)));
		$horaMostrar = date('d/m/Y h:i A',strtotime('+20 minutes',strtotime($start)));
	} 
	if($tiempo == '1h'){
		$horaAlarma = date('Y-m-d H:i',strtotime('+1 hour',strtotime($start)));
		$horaMostrar = date('d/m/Y h:i A',strtotime('+1 hour',strtotime($start)));
	} 
	if($tiempo == '3h'){
		$horaAlarma = date('Y-m-d H:i',strtotime('+3 hour',strtotime($start)));
		$horaMostrar = date('d/m/Y h:i A',strtotime('+3 hour',strtotime($start)));
	} 
	if($tiempo == 'T'){
		$horaAlarma = date('Y-m-d H:i',strtotime('+1 day',strtotime($start)));
		$horaMostrar = date('d/m/Y h:i A',strtotime('+1 day',strtotime($start)));
	} 
	if($tiempo == 'W'){
		$horaAlarma = date('Y-m-d H:i',strtotime('+7 day',strtotime($start)));
		$horaMostrar = date('d/m/Y h:i A',strtotime('+7 day',strtotime($start)));
	}

	/*$fechaarray = explode(" ",$horaAlarma);
	$fechasolo = explode("/",$fechaarray[0]);
	$fechacorrecta = $fechasolo[2]."-".$fechasolo[1]."-".$fechasolo[0]." ".$fechaarray[1];*/

	$stmt = $conn->prepare('SELECT PKChat_Alertas FROM chat_alertas WHERE Tiempo = :tiempo AND FKUsuario = :idusuario AND FKChat = :fkchat AND Visto = 0 ');
	$stmt->bindValue(':tiempo',$horaAlarma);
	$stmt->bindValue(':idusuario',$idusuario);
	$stmt->bindValue(':fkchat',$idactualizacion);
	$stmt->execute();
	$nca = $stmt->rowCount();

	if($nca < 1){
		$stmt = $conn->prepare('INSERT INTO chat_alertas (Tiempo, FKUsuario, FKChat, Visto) VALUES(:tiempo, :idusuario, :fkchat ,:visto)');
		$stmt->bindValue(':tiempo',$horaAlarma);
		$stmt->bindValue(':idusuario',$idusuario);
		$stmt->bindValue(':fkchat',$idactualizacion);
		$stmt->bindValue(':visto', 0);

		if($stmt->execute()){
			echo $horaMostrar;
		}
		else{
			echo "0";
		}
	}
	else{
		echo "1";
	}
}


?>