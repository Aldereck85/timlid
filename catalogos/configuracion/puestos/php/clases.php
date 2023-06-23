<?php
  session_start();
  date_default_timezone_set('America/Mexico_City');
  $user = $_SESSION["Usuario"];

  class conectar{//Llamado al archivo de la conexión.
    function getDb(){
      include "../../../../include/db-conn.php";
      return $conn;
    }
  }

  class get_data{
    
    function validarPuesto($data){
      $idempresa = $_SESSION["IDEmpresa"];
			$con = new conectar();
			$db = $con->getDb();

			$query = sprintf('call spc_ValidarUnicoPuesto(?,?)');
			$stmt = $db->prepare($query);
			$stmt->execute(array($data, $idempresa));
			$array = $stmt->fetchAll(PDO::FETCH_OBJ);
      $con = null;
      $db = null;
      $stmt = null;
			return $array;
		}

    function validarRelacionPuesto($data){
			$con = new conectar();
			$db = $con->getDb();

			$query = sprintf('call spc_ValidarExisteRelacionPuesto(?)');
			$stmt = $db->prepare($query);
			$stmt->execute(array($data));
			$array = $stmt->fetchAll(PDO::FETCH_OBJ);
      $con = null;
      $db = null;
      $stmt = null;
			return $array;

		}
  }

?>