<?php
require_once('../../../include/db-conn.php');
  
$guardar = $_POST['Texto'];
$idusuario = $_POST['IDUsuario'];
$idalertas = $_POST['IDAlertas'];
$idtarea = $_POST['IDTarea'];

$json = new \stdClass();

$stmt = $conn->prepare('INSERT INTO chat (Contenido, FKUsuario, FechaAlta, FKTarea) VALUES(:content, :idusuario, NOW(), :fktarea)');
$stmt->bindValue(':content',$guardar);
$stmt->bindValue(':idusuario',$idusuario);
$stmt->bindValue(':fktarea',$idtarea);
$stmt->execute();

$id = $conn->lastInsertId();

if($idalertas[0] != -1){
	foreach ($idalertas as $al) {
		$stmt = $conn->prepare('INSERT INTO chat_notificaciones (FKUsuario, FKUsuarioMencion, FKChat, Visto) VALUES(:usuario, :usuariomencion, :chat, 0)');
		$stmt->bindValue(':usuario',$idusuario);
		$stmt->bindValue(':usuariomencion',$al);
		$stmt->bindValue(':chat',$id);
		$stmt->execute();
	}
}

$mes = array('enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre');
$orgFecha = date("Y-m-d h:i:s");
$division = explode(" ", $orgFecha);

$divisionFecha = explode("-", $division[0]);
$hora = date("h:i A", strtotime($division[1]));
$mes_nombre_ini = $mes[$divisionFecha[1]-1];
$fecha = $divisionFecha[2]." de ".$mes_nombre_ini." ".$divisionFecha[0]." ".$hora;
$json->fecha = $fecha;

$json->res = $id;
$json = json_encode($json);
echo $json;

?>