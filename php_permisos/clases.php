<?php
  session_start();
  date_default_timezone_set('America/Mexico_City');
  $user = $_SESSION["Usuario"];

  class conectar{//Llamado al archivo de la conexión.
    function getDb(){
      include "../include/db-conn.php";
      return $conn;
    }
  }

  class get_data{
    function getPermission($value){
      $con = new conectar();
      $db = $con->getDb();

      $query = sprintf("SELECT p.pantalla, pf.id,pf.funcion_ver,pf.funcion_agregar,pf.funcion_editar,pf.funcion_eliminar,pf.funcion_exportar from funciones_permisos pf
                        INNER JOIN perfiles_permisos pfp ON pf.perfil_id = pfp.id
                        INNER JOIN usuarios u ON pfp.id = u.perfil_id
                        INNER JOIN pantallas p ON pf.pantalla_id = p.id
                        WHERE u.id = :id AND pf.pantalla_id = :screen");
      $stmt = $db->prepare($query);
      $stmt->bindValue(":id",$_SESSION["PKUsuario"]);
      $stmt->bindValue(":screen", $value);
      $stmt->execute();

      return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
  }

?>