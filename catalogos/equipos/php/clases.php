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
    function getTeamsTable(){
      $con = new conectar();
      $db = $con->getDb();
      $table = "";

      $query = sprintf('SELECT e.PKEquipo, e.Nombre_Equipo FROM equipos as e WHERE empresa_id = '.$_SESSION['IDEmpresa']);
      $stmt = $db->prepare($query);
      $stmt->execute();
      $array = $stmt->fetchAll();

      foreach ($array as $r) {
        $editar = '<span class=\"fas fa-edit pointer\"  onclick=\"obtenerIdEquipoEditar(\'' . $r['PKEquipo'] .'\');\"></span>';

        $table .= '{"Equipo":"' . $r['Nombre_Equipo'] . '",
          "Acciones":"' . $editar . '"},';
      }

      $table = substr($table,0,strlen($table)-1);
      return '{"data":['.$table.']}';

    }

    function getUser(){
      $con = new conectar();
      $db = $con->getDb();

      $query = sprintf('SELECT u.PKUsuario FROM usuarios as u');
      $stmt = $db->prepare($query);
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    function getTeamsEdit($value){
      $con = new conectar();
      $db = $con->getDb();

      $query = sprintf('SELECT e.PKEquipo, e.Nombre_Equipo FROM equipos as e WHERE e.PKEquipo = ?');
      $stmt = $db->prepare($query);
      $stmt->execute(array($value));
      return $stmt->fetchAll(PDO::FETCH_OBJ);

    }

    function getUserComboEdit($idEquipo){
      $con = new conectar();
      $db = $con->getDb();
      
      $query = sprintf('SELECT e.PKEmpleado as PKData, CONCAT(e.Nombres," ",e.PrimerApellido," ",e.SegundoApellido) as Data, ie.FKEmpleado FROM empleados as e 
      LEFT JOIN integrantes_equipo as ie ON ie.FKEquipo = ? AND ie.FKEmpleado = e.PKEmpleado  WHERE empresa_id = '.$_SESSION['IDEmpresa']);
      $stmt = $db->prepare($query);
      $stmt->execute(array($idEquipo));

      $empleado = $stmt->fetchAll();
      $html = "";
      foreach($empleado as $emp){

        $html .= "<option value='".$emp['PKData']."' ";


        if($emp['FKEmpleado'] == $emp['PKData']){
          $html .= " selected ";
        }

        $html .= ">".$emp['Data']."</option>";

      }
      return $html;
    }

    function getUserEditCombo(){
      $con = new conectar();
      $db = $con->getDb();

      $query = sprintf('SELECT DISTINCT ie.FKUsuario AS PKData, u.Nombre AS Data FROM integrantes_equipo ie LEFT JOIN usuarios u ON ie.FKUsuario = u.PKusuario');
      $stmt = $db->prepare($query);
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    function getUserEditComboMulti(){
      $con = new conectar();
      $db = $con->getDb();
      
      $query = sprintf('SELECT u.PKUsuario as PKData, u.Nombre as Data FROM usuarios as u');
      $stmt = $db->prepare($query);
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    function getMembers($value,$data){
      $con = new conectar();
      $db = $con->getDb();

      $query = sprintf('SELECT ie.FKEmpleado as FKData FROM integrantes_equipo ie WHERE ie.FKEquipo = ? AND FKEmpleado = ?');
      $stmt = $db->prepare($query);
      $stmt->execute(array($value,$data));
      return $stmt->rowCount();
    }
  }
