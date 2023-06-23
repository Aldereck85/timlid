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
    function getScreens(){
      $con = new conectar();
      $db = $con->getDb();

      $query = sprintf('SELECT p.id,p.pantalla,p.url,p.seccion_id FROM pantallas p');
      $stmt = $db->prepare($query);
      $stmt->execute();
      $array = $stmt->fetchAll(PDO::FETCH_OBJ);

      return $array;
    }

    function getScreensVal($value){
      $con = new conectar();
      $db = $con->getDb();

      $query = sprintf('SELECT p.id,p.pantalla,p.url,p.seccion_id FROM pantallas p WHERE seccion_id = ?');
      $stmt = $db->prepare($query);
      $stmt->execute(array($value));
      $array = $stmt->fetchAll(PDO::FETCH_OBJ);

      return $array;
    }

    function getSections(){
      $con = new conectar();
      $db = $con->getDb();

      $query = sprintf('SELECT * FROM secciones');
      $stmt = $db->prepare($query);
      $stmt->execute();
      $array = $stmt->fetchAll(PDO::FETCH_OBJ);

      return $array;
    }

    function getFunctions(){
      $con = new conectar();
      $db = $con->getDb();

      $query = sprintf('SELECT f.id,f.funcion,f.pantalla_id FROM funciones f');
      $stmt = $db->prepare($query);
      $stmt->execute();
      $array = $stmt->fetchAll(PDO::FETCH_OBJ);

      return $array;
    }

    function getFunctionsVal($value){
      $con = new conectar();
      $db = $con->getDb();

      $query = sprintf('SELECT f.id,f.funcion,f.pantalla_id FROM funciones f 
                        WHERE pantalla_id = ?');
      $stmt = $db->prepare($query);
      $stmt->execute(array($value));
      $array = $stmt->fetchAll(PDO::FETCH_OBJ);

      return $array;
    }

    function getFunctionsValues($value,$value1){
      $con = new conectar();
      $db = $con->getDb();

      $query = sprintf('SELECT pf.permiso FROM permisos_funciones pf 
                        WHERE pf.perfil_permiso_id = ? AND pf.funcion_id = ?');
      $stmt = $db->prepare($query);
      $stmt->execute(array($value,$value1));
      return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
  }

  class save_data{
    function savePermission($value,$user){
      $con = new conectar();
      $db = $con->getDb();
      $aux = [];
      $aux1 = [];
      $aux2 = [];
      
      $res = 0;

      $data = json_decode($value,true);

      for ($i=0; $i < count($data); $i++) { 
        if($data[$i]["value"] !== 0){

          array_push($aux,$data[$i]['seccion']);
          array_push($aux1,$data[$i]['pantalla']);
          
        }
        array_push($aux2,$data[$i]['funcion']);
        $funciones[$i]['funcion'] = $data[$i]['funcion'];
        $funciones[$i]['valor'] = $data[$i]['value'];
      } 
      
      $auxSecciones = array_values(array_unique($aux));
      $auxPantallas = array_values(array_unique($aux1));
      $auxFunciones = array_values(array_unique($aux2));

      for ($i=0; $i < count($auxSecciones); $i++) { 
        $secciones[$i]['seccion'] = $auxSecciones[$i];
        $secciones[$i]['valor'] = 1;
      }

      for ($i=0; $i < count($auxPantallas); $i++) { 
        $pantallas[$i]['pantalla'] = $auxPantallas[$i];
        $pantallas[$i]['valor'] = 1;
      }

      try {

        $query = sprintf('SELECT pp.id FROM permisos_pantallas pp WHERE pp.rol_id = ?');
        $stmt = $db->prepare($query);
        $stmt->execute(array($user));
        $countPerPan = $stmt->rowCount();

        $query = sprintf('SELECT ps.id FROM permisos_secciones ps WHERE ps.rol_id = ?');
        $stmt = $db->prepare($query);
        $stmt->execute(array($user));
        $countPerSec = $stmt->rowCount();

        $query = sprintf('SELECT pf.id FROM permisos_funciones pf WHERE pf.rol_id = ?');
        $stmt = $db->prepare($query);
        $stmt->execute(array($user));
        $countPerFun = $stmt->rowCount();

        /* Secciones*/
        $query = sprintf("SELECT id FROM secciones");
        $stmt = $db->prepare($query);
        $stmt->execute();
        $arraySection = $stmt->fetchAll();

        $contador = count($secciones);
        
        foreach($arraySection as $r){
          if(!in_array($r['id'], $auxSecciones)){
            if($r['id'] !== 5){
              $secciones[$contador]['seccion'] = $r['id'];
              $secciones[$contador]['valor'] = 0;
            }else{
              $secciones[$contador]['seccion'] = $r['id'];
              $secciones[$contador]['valor'] = 1;
            }
            $contador++;
          }
        }
        /* Fin Secciones */

        /* Pantallas */
        $contador = count($pantallas);

        $query = sprintf("SELECT id,seccion_id FROM pantallas");
        $stmt = $db->prepare($query);
        $stmt->execute();
        $arrayScreen = $stmt->fetchAll();

        foreach ($arrayScreen as $r) {
          if(!in_array($r['id'], $auxPantallas)){
            if($r['seccion_id'] === 5){
              $pantallas[$contador]['pantalla'] = $r['id'];
              $pantallas[$contador]['valor'] = 1;
            }else{
              $pantallas[$contador]['pantalla'] = $r['id'];
              $pantallas[$contador]['valor'] = 0;
            }
            $contador++;
          }
        }
        /* Fin pantallas */

        /* Funciones */
        $contador = count($funciones);

        $query = sprintf("SELECT f.id id_funcion,s.id id_seccion FROM funciones f
                          INNER JOIN pantallas p ON f.pantalla_id = p.id
                          INNER JOIN secciones s ON p.seccion_id = s.id");
        $stmt = $db->prepare($query);
        $stmt->execute();
        $arrayFunction = $stmt->fetchAll();

        foreach ($arrayFunction as $r) {
          if(!in_array($r['id_funcion'], $aux2)){
            if($r['id_seccion'] === 5){
              $funciones[$contador]['funcion'] = $r['id_funcion'];
              $funciones[$contador]['valor'] = 1;
            }
            $contador++;
          }
          
        }
        /* Fin funciones */

        if($countPerPan > 0){

           /* Actualizar permiso_pantallas */
           for ($i=0; $i < count($pantallas); $i++) { 
            $query = sprintf("UPDATE permisos_pantallas SET permiso = ? 
                              WHERE rol_id = ? AND pantalla_id = ? AND tim_impulsa");
            $stmt = $db->prepare($query);
            $res += $stmt->execute(array($pantallas[$i]['valor'],$user,$pantallas[$i]['pantalla'],$_SESSION['tim_impulsa']));
          }
          /* Fin Actualizar permiso_pantallas */

        }else{

          /* Insertar permiso_pantallas */
          for ($i=0; $i < count($pantallas); $i++) { 
            $query = sprintf("INSERT INTO permisos_pantallas (pantalla_id,rol_id,permiso,tim_impulsa) 
                              VALUES (?,?,?)");
            $stmt = $db->prepare($query);
            $res += $stmt->execute(array($pantallas[$i]['pantalla'],$user,$pantallas[$i]['valor'],$_SESSION['tim_impulsa']));
          }
          /* Fin insertar permiso_pantallas */
        }

        if($countPerSec > 0){

          /* Actualizar permisos_secciones */
          for ($i=0; $i < count($secciones); $i++) { 
            $query = sprintf("UPDATE permisos_secciones SET permiso = ? 
                              WHERE rol_id = ? AND seccion_id = ?");
            $stmt = $db->prepare($query);
            $res += $stmt->execute(array($secciones[$i]['valor'],$user,$secciones[$i]['seccion']));
          }
          /* Fin Actualizar permisos_secciones */

        }else{

          /* Insertar permisos_secciones */
          for ($i=0; $i < count($secciones); $i++) { 
            $query = sprintf("INSERT INTO permisos_secciones (seccion_id,rol_id,permiso) 
                              VALUES (?,?,?)");
            $stmt = $db->prepare($query);
            $res += $stmt->execute(array($secciones[$i]['seccion'],$user,$secciones[$i]['valor']));
          }
          /* Fin Insertar permisos_secciones */

        }

        if($countPerFun > 0){

          /* Actualizar permisos_funciones */
          for ($i=0; $i < count($funciones); $i++) { 
            $query = sprintf("UPDATE permisos_funciones SET permiso = ? 
                              WHERE perfil_permiso_id = ? AND funcion_id = ?");
            $stmt = $db->prepare($query);
            $res += $stmt->execute(array($funciones[$i]['valor'],$user,$funciones[$i]['funcion']));
          }
          /* Fin Actualizar permisos_funciones */

        }else{

          /* Insertar permisos_funciones */
          for ($i=0; $i < count($funciones); $i++) { 
            $query = sprintf("INSERT INTO permisos_funciones (funcion_id,perfil_permiso_id,permiso) 
                              VALUES (?,?,?)");
            $stmt = $db->prepare($query);
            $res += $stmt->execute(array($funciones[$i]['funcion'],$user,$funciones[$i]['valor']));
          }
          /* Fin insertar permisos_funciones */

        }
        return $res;
      } catch (PDOException $e) {
        return "Error en Consulta: ".$e->getMessage();
      }


    
    }
  }


?>