<?php
  session_start();
  date_default_timezone_set('America/Mexico_City');
  $user = $_SESSION["Usuario"];

  class conectar{//Llamado al archivo de la conexión.
    function getDb(){
      include "../../../include/db-conn.php";
      return $conn;
    }
  }

  class get_data{
    function getScreensMenus($value){
      $con = new conectar();
      $db = $con->getDb();
      
      $stmt = $db->prepare('call spc_Permisos_Secciones(?)');
      $stmt->execute(array($value));
      $array = $stmt->fetchAll(PDO::FETCH_OBJ);

      
      return $array;
    }
  }


?>