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
    function getResponsableTable($idemp){
      $con = new conectar();
      $db = $con->getDb();
      $table = "";
      $cintador = 1;


      $query = sprintf('SELECT r.id, e.Nombres as nom, e.PrimerApellido as ape, e.SegundoApellido as aped
      FROM empleados as e INNER JOIN relacion_tipo_empleado as r ON e.PKEmpleado = r.empleado_id WHERE e.empresa_id = :idemp AND r.tipo_empleado_id = 2');
      $stmt = $db->prepare($query);
      $stmt->execute(array(':idemp'=>$idemp));
      $array = $stmt->fetchAll();
      $cont = count($array);

      foreach ($array as $r) {
        $id = $r['id'];
        $nombre = $r['nom'];
        $ape = $r['ape'];
        $aped = $r['aped'];
       
        $acciones = '<i class=\"fas fa-trash-alt pointer permission-view-edit\" onclick=\"obtenerIdResponsableEditar('.$id.');\"></i>';
        
        $table .= '
        {"id":"'.$id.'",
          "NoResponsable":"'.$cont.'",
          "Acciones":"'.$acciones.'",
          "Nombre":"'.$nombre." ".$ape." ".$aped.'"},';
        $cont--;
      }
      $table = substr($table,0,strlen($table)-1);

      return '{"data":['.$table.']}';

      $con = null;
      $db = null;
      $stmt = null;
    }
    function validarExisteResponsable($data){
      $con = new conectar();
      $db = $con->getDb();
  
      $query = sprintf('call spc_ValidarExisteResponsable(?)');
      $stmt = $db->prepare($query);
      $stmt->execute(array($data));
      $array = $stmt->fetchAll(PDO::FETCH_OBJ);
  
      return $array;

      $con = null;
      $db = null;
      $stmt = null;
    }
    function validarExisteRelacionResponsable($data){
      $con = new conectar();
      $db = $con->getDb();
  
      $query = sprintf('call spc_ValidarExisteRelacionResponsable(?)');
      $stmt = $db->prepare($query);
      $stmt->execute(array($data));
      $array = $stmt->fetchAll(PDO::FETCH_OBJ);
  
      return $array;

      $con = null;
      $db = null;
      $stmt = null;
    }
    function getCmbEmpleados(){
      $idempresa = $_SESSION["IDEmpresa"];
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Combo_Empleados(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($idempresa));
        return $array = $stmt->fetchAll(PDO::FETCH_OBJ);
        $con = null;
        $db = null;
        $stmt = null;
    }
    
    
  }

?>