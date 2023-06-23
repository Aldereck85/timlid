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
    function getpersonalTable(){
      $idempresa = $_SESSION["IDEmpresa"];
      $con = new conectar();
      $db = $con->getDb();
      $table = "";

      $query = sprintf('SELECT e.PKEmpleado, e.Nombres, e.PrimerApellido, e.Genero, ef.Estado, s.prefijo, s.colonia, s.municipio, s.estado_id, p.PKPais, p.Pais, s.telefono, ef.Estado, s.activar_inventario, s.zona_salario_minimo FROM sucursales s INNER JOIN paises p ON s.pais_id = p.PKPais INNER JOIN estados_federativos ef ON s.estado_id=ef.PKEstado WHERE empresa_id = :idempresa');
      $stmt = $db->prepare($query);
      $stmt->execute(array(':idempresa'=>$idempresa));
      $array = $stmt->fetchAll();

      foreach ($array as $r) {
        $id = $r['id'];
        $sucursal = $r['sucursal'];
        $calle = $r['calle'];
        $ext = $r['numero_exterior'];
        $colonia = $r['colonia'];
        $municipio = $r['municipio'];
        $estado = $r['Estado'];
        $pais = $r['PKPais'];
        $nombrePais = $r['Pais'];
        $telefono = $r['telefono'];
        $actInventario = $r['activar_inventario'];

        if($r['zona_salario_minimo'] == 1){
          $zona_salario_minimo = "General";
        }
        else{
          $zona_salario_minimo = "Frontera";
        }

        if ($actInventario == 1){
          $actInventario = 'SI';
        }else{
          $actInventario = 'NO';
        }
        
        $acciones = '<i class=\"fas fa-edit pointer permission-view-edit\" data-toggle=\"modal\" data-target=\"#editar_Sucursales_50\" onclick=\"obtenerIdSucursalEditar('.$id.');\"></i>';
        
        if($r['numero_interior'] !== "" || $r['numero_interior'] !== null){
          $int = $r['numero_interior'];
        }else{
          $int = "";
        }
        
        if($r['prefijo'] !== "" || $r['prefijo'] !== null){
          $pref = $r['prefijo'];
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
          "Pais":"'.$nombrePais.'",
          "Telefono":"'.$telefono.'",
          "Inventario":"'.$actInventario.'",
          "Acciones":"'.$acciones.'",
          "Zona salario":"'.$zona_salario_minimo.'"},';
      }
      $table = substr($table,0,strlen($table)-1);

      return '{"data":['.$table.']}';

      $con = null;
      $db = null;
      $stmt = null;
    }
    function validarSucursal($data){
      $idempresa = $_SESSION["IDEmpresa"];
			$con = new conectar();
			$db = $con->getDb();

			$query = sprintf('call spc_ValidarUnicaSucursal(?,?)');
			$stmt = $db->prepare($query);
			$stmt->execute(array($data, $idempresa));
			$array = $stmt->fetchAll(PDO::FETCH_OBJ);

			return $array;

      $con = null;
      $db = null;
      $stmt = null;
		}
    function validarSucursalU($data,$data2){
      $idempresa = $_SESSION["IDEmpresa"];
			$con = new conectar();
			$db = $con->getDb();

			$query = sprintf('call spc_ValidarUnicaSucursalU(?,?,?)');
			$stmt = $db->prepare($query);
			$stmt->execute(array($data,$data2,$idempresa));
			$array = $stmt->fetchAll(PDO::FETCH_OBJ);

			return $array;

      $con = null;
      $db = null;
      $stmt = null;
		}
    function validarRelacionSucursal($data){
			$con = new conectar();
			$db = $con->getDb();

			$query = sprintf('call spc_ValidarRelacionSucursal(?)');
			$stmt = $db->prepare($query);
			$stmt->execute(array($data));
			$array = $stmt->fetchAll(PDO::FETCH_OBJ);

			return $array;

      $con = null;
      $db = null;
      $stmt = null;
		}
  }

?>