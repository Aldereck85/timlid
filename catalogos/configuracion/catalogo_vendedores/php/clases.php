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
    function getSellerTable(){
      $con = new conectar();
      $db = $con->getDb();
      $table = "";
      $cintador = 1;

      $query = sprintf('SELECT v.PKVendedor,
        v.FKUsuario,
        v.FKEstatusGeneral as eg,
        u.Nombre as nom,
        eg.Estatus as nomest
          FROM vendedores as v INNER JOIN usuarios as u ON v.FKUsuario=u.PKUsuario
          INNER JOIN estatus_general as eg ON eg.PKEstatusGeneral=v.FKEstatusGeneral');

      $stmt = $db->prepare($query);
      $stmt->execute();
      $array = $stmt->fetchAll();
      $cont = 0;

      foreach ($array as $r) {
        $id = $r['PKVendedor'];
        $nombre = $r['nom'];
        $estatus = $r['nomest'];
       
        $acciones = '<i class=\"permission-view-edit\"><img class=\"btnEdit\" data-toggle=\"modal\" data-target=\"#modalEditar\" onclick=\"obtenerIdVendedorEditar('.$id.');\" src=\"../../../img/timdesk/edit.svg\"></i>';
        
        $table .= '
        {"id":"'.$id.'",
          "NoVendedor":"'.$id.'",
          "Nombre":"'.$nombre.'",
          "Estatus":"'.$estatus.$acciones.'"},';
      }
      $table = substr($table,0,strlen($table)-1);

      return '{"data":['.$table.']}';
    }
    function validarExisteVendedor($data){
      $con = new conectar();
      $db = $con->getDb();
  
      $query = sprintf('call spc_ValidarExisteVendedor(?)');
      $stmt = $db->prepare($query);
      $stmt->execute(array($data));
      $array = $stmt->fetchAll(PDO::FETCH_OBJ);
  
      return $array;
    }
    function validarExisteRelacionVendedor($data){
      $con = new conectar();
      $db = $con->getDb();
  
      $query = sprintf('call spc_ValidarExisteRelacionVendedor(?)');
      $stmt = $db->prepare($query);
      $stmt->execute(array($data));
      $array = $stmt->fetchAll(PDO::FETCH_OBJ);
  
      return $array;
    }
  }
  

?>