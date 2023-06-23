<?php
  session_start();
  require_once $ruta.'../lib/json_web_token.php';
  $token = $_SESSION['token'];

  if(!Auth::Check($token)){
    require_once($ruta.'../include/db-conn.php');

    $query = "UPDATE usuarios SET estado_web = 0 WHERE id = :idusuario";
    $statement = $conn->prepare($query);
    $statement->execute( array('idusuario'     =>     $_SESSION["PKUsuario"] ) );

    $query = "DELETE FROM sessions WHERE sessions_userid = :sessions_userid AND sessions_date = :fecha_sessions ";
    $statement = $conn->prepare($query);
    $statement->execute( array('sessions_userid'     =>     $_SESSION["PKUsuario"] ,  'fecha_sessions'     =>     $_SESSION["fecha_sesion_unico"] ) );

    session_destroy();
    header("location: ../".$ruta);
  }

  require_once($ruta.'../functions/functions.php');
  require_once($ruta.'../include/db-conn.php');
  if(!checkLoginState($conn)){    

    $query = "UPDATE usuarios SET estado_web = 0 WHERE id = :idusuario";
    $statement = $conn->prepare($query);
    $statement->execute( array('idusuario'     =>     $_SESSION["PKUsuario"] ) );

    $query = "DELETE FROM sessions WHERE sessions_userid = :sessions_userid AND sessions_date = :fecha_sessions";
    $statement = $conn->prepare($query);
    $statement->execute( array('sessions_userid'     =>     $_SESSION["PKUsuario"],  'fecha_sessions'     =>     $_SESSION["fecha_sesion_unico"] ) );

    session_destroy();
    header("location: ../".$ruta);
  }

  $query = sprintf('SELECT pf.funcion_ver from funciones_permisos pf
                    INNER JOIN perfiles_permisos pfp ON pf.perfil_id = pfp.id
                    INNER JOIN usuarios u ON pfp.id = u.perfil_id
                    INNER JOIN pantallas p ON pf.pantalla_id = p.id
                    WHERE u.id = :id AND pf.pantalla_id = :screen');
  $stmt = $conn->prepare($query);
  $stmt->bindValue(":id",$_SESSION['PKUsuario']);
  $stmt->bindValue(":screen",$screen);
  $stmt->execute();
  $permiso = $stmt->fetch()['funcion_ver'];

?>