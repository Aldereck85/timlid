<?php
require_once('../../../include/db-conn.php');

$stmt = $conn->prepare('SELECT * FROM locaciones INNER JOIN estados_federativos AS E ON locaciones.FKEstado = E.PKEstado INNER JOIN paises AS P ON locaciones.FKPais = P.PKPais');
$stmt->execute();
$table="";
while (($row = $stmt->fetch()) !== false) {
  //href=\"#\" data-toggle=\"modal\" data-target=\"#editar_Locacion\" class=\"btn btn-primary\" onclick=\"obtenerIdLocacionEditar('.$row['PKLocacion'].');\"><i class=\"fas fa-edit\"></i> Editar
  //href=\"#\" data-toggle=\"modal\" data-target=\"#eliminar_Locacion\" class=\"btn btn-danger\" onclick=\"obtenerIdLocacionEliminar('.$row['PKLocacion'].')\"><i class=\"fas fa-trash-alt\"></i> Eliminar
    //$edit = '<a class=\"btn btn-primary\" href=\"#\" data-toggle=\"modal\" data-target=\"#editar_Locacion\" class=\"btn btn-primary\" onclick=\"obtenerIdLocacionEditar('.$row['PKLocacion'].');\"><i class=\"fas fa-edit\"></i> Editar</a>&nbsp;&nbsp;';
		//$delete ='<a class=\"btn btn-danger\" href=\"#\" data-toggle=\"modal\" data-target=\"#eliminar_Locacion\" class=\"btn btn-danger\" onclick=\"obtenerIdLocacionEliminar('.$row['PKLocacion'].')\"><i class=\"fas fa-trash-alt\"></i> Eliminar</a>';
    //$table .='{"Locacion":"'.$row['Locacion'].'","Acciones":"'.$edit.$delete.'"},';
    if(preg_match('/[A-Z_\-0-9]/i',$row['NumInt'])){
      $imp = $row['Prefijo']." ".$row['NumInt'];
    }else{
      $imp ="";
    }
    $table .='{"id":"<label class=\"textTable\">'.$row['PKLocacion'].'","Locacion":"<label class=\"textTable\">'.$row['Locacion'].'","Domicilio":"<label class=\"textTable\">'.$row['Calle'].' '.$row['NumExt'].' '.$imp.'","Colonia":"<label class=\"textTable\">'.$row['Colonia'].'","Municipio":"<label class=\"textTable\">'.$row['Municipio'].'","Estado":"<label class=\"textTable\">'.$row['Estado'].'","Pais":"<label class=\"textTable\">'.$row['Pais'].'","Telefono":"<label class=\"textTable\">'.$row['Telefono'].'</label><i><img class=\"btnEdit\" data-toggle=\"modal\" data-target=\"#modalEditar\" onclick=\"obtenerIdLocacionEditar('.$row['PKLocacion'].');\" src=\"../../img/timdesk/edit.svg\"></i>"},';
    //$table .='{"Locacion":"'.$row['Locacion'].'","Domicilio":"'.$row['Calle'].' '.$row['NumExt'].' '.$row['NumInt'].'","Colonia":"'.$row['Colonia'].'","Estado":"'.$row['Estado'].'"},';
    
      
  }
  $table = substr($table,0,strlen($table)-1);
  echo '{"data":['.$table.']}';
  //" ".." ".
?>
