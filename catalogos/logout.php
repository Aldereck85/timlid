<?php
  require_once('../include/db-conn.php');
  session_start();

  $query = "UPDATE usuarios SET estado_web = 0 WHERE id = :idusuario";
  $statement = $conn->prepare($query);
  $statement->execute( array('idusuario'     =>     $_SESSION["PKUsuario"] ) );

  session_destroy();
  header("location:../");
?>
