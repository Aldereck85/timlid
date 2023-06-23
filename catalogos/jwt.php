<?php
	require_once ($jwt_ruta.'lib/json_web_token.php');
	$token = $_SESSION['token'];

	if(!Auth::Check($token)){
	  require_once($jwt_ruta.'include/db-conn.php');

	  $query = "UPDATE usuarios SET estado_web = 0 WHERE id = :idusuario";
	  $statement = $conn->prepare($query);
	  $statement->execute( array('idusuario'     =>     $_SESSION["PKUsuario"] ) );

	  $query = "DELETE FROM sessions WHERE sessions_userid = :sessions_userid AND sessions_date = :fecha_sessions ";
	  $statement = $conn->prepare($query);
	  $statement->execute( array('sessions_userid'     =>     $_SESSION["PKUsuario"] ,  'fecha_sessions'     =>     $_SESSION["fecha_sesion_unico"] ) );

	  session_destroy();
	  header("location:".$jwt_ruta);
	}

	require_once($jwt_ruta.'functions/functions.php');
	require_once($jwt_ruta.'include/db-conn.php');
	if(!checkLoginState($conn)){	  

	  $query = "UPDATE usuarios SET estado_web = 0 WHERE id = :idusuario";
	  $statement = $conn->prepare($query);
	  $statement->execute( array('idusuario'     =>     $_SESSION["PKUsuario"] ) );

	  $query = "DELETE FROM sessions WHERE sessions_userid = :sessions_userid AND sessions_date = :fecha_sessions";
	  $statement = $conn->prepare($query);
	  $statement->execute( array('sessions_userid'     =>     $_SESSION["PKUsuario"],  'fecha_sessions'     =>     $_SESSION["fecha_sesion_unico"] ) );

	  session_destroy();
	  header("location:".$jwt_ruta);
	}
	/*	date_default_timezone_set('America/Mexico_City');
	$decoded = Auth::decode($token);
	var_dump($decoded);

	echo date("H:i:s",strtotime($decoded->exp));

	echo "---".date("H:i:s",time());*/


?>