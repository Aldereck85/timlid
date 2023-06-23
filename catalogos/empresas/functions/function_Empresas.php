<?php
  require_once('../../../include/db-conn.php');
  $table = "";
  $no = 1;
  $stmt = $conn->prepare('SELECT * FROM empresas');
  $interior = "S/N";
  $stmt->execute();

  while($row = $stmt->fetch()){
    if($row['Interior'] != null){
      $interior = $row['Interior'];
    }
    $edit = '<a class=\"dropdown-item\" href=\"#\" data-toggle=\"modal\" data-target=\"#editar_Empresa\" onclick=\"obtenerIdEmpresaEditar('.$row['PKEmpresa'].');\"><i class=\"fas fa-edit\"></i> Editar</a>';
  	$delete ='<a class=\"dropdown-item\" href=\"#\" data-toggle=\"modal\" data-target=\"#eliminar_Empresa\" onclick=\"obtenerIdEmpresaEliminar('.$row['PKEmpresa'].')\"><i class=\"fas fa-trash-alt\"></i> Eliminar</a>';
    $acciones = '<div class=\"dropdown\"><button class=\"btn btn-secondary dropdown-toggle\" type=\"button\" id=\"dropdownMenuButton\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\"><i class=\"fas fa-tools\"></i> Operaciones</button><div class=\"dropdown-menu\" aria-labelledby=\"dropdownMenuButton\">'.$edit.$delete;
    $table.='{"No":"'.$no.'","Razon Social":"'.$row['Razon_Social'].'","RFC":"'.$row['RFC'].'","Calle":"'.$row['Calle'].'","Numero Externo":"'.$row['Numero_Externo'].'","Interior":"'.$interior.'","Colonia":"'.$row['Colonia'].'","Codigo Postal":"'.$row['Codigo_Postal'].'","Municipio":"'.$row['Municipio'].'","Estado":"'.$row['Estado'].'","Registro patronal":"'.$row['Registro_Patronal'].'","Acciones":"'.$acciones.'"},';
    $no++;
  }

  $table = substr($table,0,strlen($table)-1);
	echo '{"data":['.$table.']}';
?>
