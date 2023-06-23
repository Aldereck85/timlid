<?php
  session_start();
  date_default_timezone_set('America/Mexico_City');
  $user = $_SESSION["Usuario"];

  class conectar{//Llamado al archivo de la conexión.
    function getDb(){
      include "../../../include/db-empresas.php";
      return $conn;
    }
  }

  class get_data{//Obtener información de la base de datos
    function getUserTable(){
      $con = new conectar();
      $db = $con->getDb();
      $table = "";
      
      $stmt = $db->prepare('call spc_Tabla_Usuarios()');
      $stmt->execute();
      $array = $stmt->fetchAll();

      foreach ($array as $r) {
        $id = $r['PKUsuario'];
        $nombres = $r['Nombre'];
        $usuario = $r['Usuario'];

        $acciones = '<i class=\"permission-view-edit\"><img class=\"btnEdit\" data-toggle=\"modal\" data-target=\"#editarUsuario\" onclick=\"obtenerIdUsuarioEditar('.$id.');\" src=\"../../img/timdesk/edit.svg\"></i>';

        $table .= '
          {"Id usuario":"'.$id.'",
            "Nombre completo":"'.$nombres.$acciones.'",
            "Usuario":"'.$usuario.'"},';
      }

      $table = substr($table,0,strlen($table)-1);

      return '{"data":['.$table.']}';
    }

    function getUser($value){
      $con = new conectar();
      $db = $con->getDb();

      $query = sprintf('call spc_Usuario(?)');
      $stmt = $db->prepare($query);
      $stmt->execute(array($value));
    
      return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    function getRols($value){
      $con = new conectar();
      $db = $con->getDb();
      $array = [];

      if($value !== ""){
        $stmt = $db->prepare('call spc_Roles_id()');
        $stmt->execute(array($value));
        $aux = $stmt->fetchAll();
      }else{
        $stmt = $db->prepare('call spc_Roles()');
        $stmt->execute();
        $aux = $stmt->fetchAll();
      }

      $i = 0;
      
      foreach ($aux as $r) { 
        $array[$i] = [
          "id" => $r['id'],
          "value" => $r['rol']
        ];
        $i++;
      }

      return $array;
    }

    function getEmployer(){
      $con = new conectar();
      $db = $con->getDb();
      $array;
      
      $query = sprintf('SELECT e.PKEmpleado, CONCAT(e.Nombres," ",e.PrimerApellido," ",e.SegundoApellido) Nombre
                        FROM empleados e WHERE e.PKEmpleado
                        NOT IN (SELECT eu.FKEmpleado FROM empleados_usuarios eu)');
      $stmt = $db->prepare($query);
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_OBJ);
      
    }
  }

  class save_data{
    function saveUser($user, $name, $pass, $employer){
      $con = new conectar();
      $db = $con->getDb();
      $cod = new save_data();
      $nombre = "";

      $codigo = $cod->generateRandomString();
      //$codigo = "";

      if($employer !== ""){
        $query1 = sprintf('SELECT CONCAT(Nombres," ",PrimerApellido," ",SegundoApellido) Nombre FROM empleados WHERE PKEmpleado = ?');
        $stmt1 = $db->prepare($query1);
        $stmt1->execute(array($employer));
        $nombre = $stmt1->fetch()['Nombre'];
      }else{
        $nombre = $name;
      }
      
      try{

        $query3 = sprintf('SELECT * FROM usuarios WHERE Usuario = ?');
        $stmt3 = $db->prepare($query3);
        $stmt3->execute(array($user));
        $count = $stmt3->rowCount();

        if($count > 0){
          $id = 0;
        }else{
          $query = sprintf('INSERT INTO usuarios (Usuario,Contrasena,Nombre,Codigo,Activo,Estado) VALUES (?,?,?,?,?,?)');
          $stmt = $db->prepare($query);
          $stmt->execute(array($user,$pass,$nombre,$codigo,1,0));
          //$id = $stmt->fetch()['0'];
          $id = $db->lastInsertId();
          
          if($employer !== ""){
            $query2 = sprintf("INSERT INTO empleados_usuarios (FKEmpleado,FKUsuario) VALUES (?,?)");
            $stmt2 = $db->prepare($query2);
            $stmt2->execute(array($employer,$id));
          }
        }
        return $id;
      }catch(PDOException $e){
        return "Error en Consulta: ".$e->getMessage();
      }
      

    }

    function generateRandomString($length = 12) {
      $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $charactersLength = strlen($characters);
      $randomString = '';
      for ($i = 0; $i < $length; $i++) {
          $randomString .= $characters[rand(0, $charactersLength - 1)];
      }
      return $randomString;
  }
  }

  class update_data{
    function updateUser($idUser,$user,$name,$pass){
      $con = new conectar();
      $db = $con->getDb();
      
      try {
        $query = sprintf('call spu_Usuario_Actualizar(?,?,?,?)');

        $stmt = $db->prepare($query);
        return $stmt->execute(array($user,$name,$pass,$idUser));

      } catch (PDOException $e) {
        return "Error en Consulta: ".$e->getMessage();
      }
    }
  }

  class delete_data{
    function deleteUser($value){
      $con = new conectar();
      $db = $con->getDb();

      $query = sprintf('call spd_EliminarUsuario(?)');
      $stmt = $db->prepare($query);
      return $stmt->execute(array($value));

    }
  }
?>