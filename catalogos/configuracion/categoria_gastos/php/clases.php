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
    function getCategoryTable($idemp){
      $con = new conectar();
      $db = $con->getDb();
      $table = "";
      $cintador = 1;

      $query = sprintf('select * from (select 
                            PKCategoria, 
                            Nombre 
                        from 
                            categoria_gastos 
                        where empresa_id = :id and estatus = 1
                        union 
                        select
                            PKCategoria, 
                            Nombre 
                        from 
                            categoria_gastos 
                        where PKCategoria = 1) as cat ORDER BY cat.PKCategoria;');
      $stmt = $db->prepare($query);
      $stmt->execute(array(':id'=>$idemp));
      $array = $stmt->fetchAll();
      $cont = 0;
      $x=count($array);

      foreach ($array as $r) {
        $id = $r['PKCategoria'];
        $nombre = $r['Nombre'];
       
        $acciones = '<i class=\"fas fa-edit pointer permission-view-edit\" data-toggle=\"modal\" data-target=\"#editar_CategoriaGastos_47\" onclick=\"obtenerIdCategoriaGastosEditar('.$id.');\"></i>';
        
        $table .= '
        {"id":"'.$id.'",
          "NoCategoria":"'.$x.'",
          "Acciones":"'.$acciones.'",
          "Nombre":"'.$nombre.'"},';
        $x--;
      }
      $table = substr($table,0,strlen($table)-1);

      return '{"data":['.$table.']}';

      $con = null;
      $db = null;
      $stmt = null;
    }
    function validarCategoriaGasto($data){
      $IDEmpresa = $_SESSION["IDEmpresa"];
			$con = new conectar();
			$db = $con->getDb();

			$query = sprintf('call spc_ValidarUnicaCategoriaGastos(?, ?)');
			$stmt = $db->prepare($query);
			$stmt->execute(array($data, $IDEmpresa));
			$array = $stmt->fetchAll(PDO::FETCH_OBJ);

			return $array;

      $con = null;
      $db = null;
      $stmt = null;
		}
    function validarRelacionCatGasto($data){
			$con = new conectar();
			$db = $con->getDb();

			$query = sprintf('call spc_ValidarRelacionCatGasto(?)');
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