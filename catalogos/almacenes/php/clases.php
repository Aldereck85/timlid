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
    function getWarehouseTable(){
      $con = new conectar();
      $db = $con->getDb();
      $table = "";

      $query = sprintf('SELECT * FROM almacenes LEFT JOIN estados_federativos AS E ON almacenes.FKEstado = E.PKEstado    
                        LEFT JOIN paises AS P ON almacenes.FKPais = P.PKPais');
      $stmt = $db->prepare($query);
      $stmt->execute();
      $array = $stmt->fetchAll();

      foreach($array as $r){
        if(preg_match('/[A-Z_\-0-9]/i',$r['Interior'])){
          $imp = $r['Prefijo']." ".$r['Interior'];
        }else{
          $imp ="";
        }

        $table .='{"id":"<label class=\"textTable\">'.$r['PKAlmacen'].'",
                  "Almacen":"<label class=\"textTable\">'.$r['Almacen'].'",
                  "Domicilio":"<label class=\"textTable\">Calle '.$r['Direccion'].' No. '.$r['Exterior'].' '.$imp.'",
                  "Colonia":"<label class=\"textTable\">'.$r['Colonia'].'",
                  "Ciudad":"<label class=\"textTable\">'.$r['Ciudad'].'",
                  "Estado":"<label class=\"textTable\">'.$r['Estado'].'",
                  "Pais":"<label class=\"textTable\">'.$r['Pais'].'</label><i><img class=\"btnEdit\" data-toggle=\"modal\" data-target=\"#modalEditar\" onclick=\"obtenerIdAlmacenEditar('.$r['PKAlmacen'].');\" src=\"../../img/timdesk/edit.svg\"></i>"},';
      }
      $table = substr($table,0,strlen($table)-1);
      return '{"data":['.$table.']}';
    }

    function getCountriesCombo($value){
      $con = new conectar();
      $db = $con->getDb();

      $query = sprintf('SELECT PKPais AS PKData, Pais AS Data FROM paises');
      $stmt = $db->prepare($query);
      $stmt->execute();
      
      return $stmt->fetchAll();
    }

    function getStatesCombo($value){
      $con = new conectar();
      $db = $con->getDb();

      $query = sprintf('SELECT PKEstado AS PKData, Estado AS Data FROM estados_federativos WHERE FKPais = ?');
      $stmt = $db->prepare($query);
      $stmt->execute(array($value));
      
      return $stmt->fetchAll();
    }

    function getWarehouseEdit($value){
      $con = new conectar();
      $db = $con->getDb();

      $query = sprintf('SELECT * FROM almacenes WHERE PKAlmacen = ?');
      $stmt = $db->prepare($query);
      $stmt->execute(array($value));
      
      return $stmt->fetchAll();
    }

  }

?>