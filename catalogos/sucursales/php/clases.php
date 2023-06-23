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
    function getSucursalTable(){
      $con = new conectar();
      $db = $con->getDb();
      $table = "";

      $query = sprintf('SELECT s.PKSucursal, s.Sucursal, s.Calle, s.NumInt, s.NumExt, s.Prefijo, s.Colonia, s.Municipio, s.Estado, p.Pais, s.Telefono FROM sucursales s INNER JOIN paises p ON s.FKPais = p.PKPais');
      $stmt = $db->prepare($query);
      $stmt->execute();
      $array = $stmt->fetchAll();

      foreach ($array as $r) {
        $id = $r['PKSucursal'];
        $sucursal = $r['Sucursal'];
        $calle = $r['Calle'];
        $ext = $r['NumExt'];
        $colonia = $r['Colonia'];
        $municipio = $r['Municipio'];
        $estado = $r['Estado'];
        $pais = $r['Pais'];
        $telefono = $r['Telefono'];
        

        $acciones = '<i class=\"permission-view-edit\"><img class=\"btnEdit\" data-toggle=\"modal\" data-target=\"#editarUsuario\" onclick=\"obtenerIdUsuarioEditar('.$id.');\" src=\"../../img/timdesk/edit.svg\"></i>';
        
        if($r['NumInt'] !== "" || $r['NumInt'] !== null){
          $int = $r['NumInt'];
        }else{
          $int = "";
        }
        
        if($r['Prefijo'] !== "" || $r['Prefijo'] !== null){
          $pref = $r['Prefijo'];
        }else{
          $pref = "";
        }
        
        $table .= '
        {"id":"'.$id.'",
          "Sucursal":"'.$sucursal.'",
          "Domicilio":"'.$calle.' No. '.$ext.' '.$pref.' '.$int.'",
          "Colonia":"'.$colonia.'",
          "Municipio":"'.$municipio.'",
          "Estado":"'.$estado.'",
          "Pais":"'.$pais.'",
          "Telefono":"'.$telefono.$acciones.'"},';
      }
      $table = substr($table,0,strlen($table)-1);

      return '{"data":['.$table.']}';
    }
  }

?>