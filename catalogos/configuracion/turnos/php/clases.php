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
    
    function validarTurno($data){
      $idempresa = $_SESSION["IDEmpresa"];
			$con = new conectar();
			$db = $con->getDb();
      $unico = "";

			$query = sprintf("SELECT * FROM turnos WHERE Turno = :turno AND empresa_id = :idempresa AND estatus = 1");
			$stmt = $db->prepare($query);
      $stmt->bindValue(":turno",$data);
      $stmt->bindValue(":idempresa",$idempresa);
			$stmt->execute();
			$count = $stmt->rowCount();

      if($count === 1){
        $unico = 1;
      }else{
        $unico = 0;
      }

			return $unico;
		}
    function validarRelacionTurno($data){
			$con = new conectar();
			$db = $con->getDb();

			$query = sprintf('call spc_ValidarExisteRelacionTurno(?)');
			$stmt = $db->prepare($query);
			$stmt->execute(array($data));
			$array = $stmt->fetchAll(PDO::FETCH_OBJ);

			return $array;
		}
  }

?>