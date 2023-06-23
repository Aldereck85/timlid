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

  class get_data{//Obtener información de la base de datos
    function getUserTable(){
      $con = new conectar();
      $db = $con->getDb();
      $table = "";
      
      $stmt = $db->prepare('call spc_Usuarios()');
      $stmt->execute();
      $array = $stmt->fetchAll();

      foreach ($array as $r) {
        $id = $r['PKEmpleado'];
        $nombres = $r['Nombres'];
        $apellido = $r['PrimerApellido'];
        $usuario = $r['Usuario'];
        $rol = $r['Rol'];

        $table .= '
          {"Id empleado":"'.$id.'",
            "Nombres":"'.$nombres.'",
            "Primer Apellido":"'.$apellido.'",
            "Usuario":"'.$usuario.'",
            "Rol":"'.$rol.'"},';
      }

      $table = substr($table,0,strlen($table)-1);

      return '{"data":['.$table.']}';
    }

    function getRols($value){
      $con = new conectar();
      $db = $con->getDb();
      $array = [];

      if($value !== ""){
        $stmt = $db->prepare('call spc_Roles_id()');
        $stmt->execute();
        $aux = $stmt->fetchAll();
      }else{
        $stmt = $db->prepare('call spc_Roles()');
        $stmt->execute();
        $aux = $stmt->fetchAll();
      }

      for ($i=0; $i < count($aux); $i++) { 
        $array[$i] = [
          "id" => $aux[$i]->PKRol,
          "value" => $aux[$i]->Rol
        ];
      }
      

      return $array;
    }
  }
?>