<?php
require_once('../../../include/db-conn.php');

$stmt = $conn->prepare('SELECT * FROM almacenes INNER JOIN estados_federativos AS E ON almacenes.FKEstado = E.PKEstado INNER JOIN paises AS P ON almacenes.FKPais = P.PKPais');
$stmt->execute();
$table="";
while (($row = $stmt->fetch()) !== false) {
  //href=\"#\" data-toggle=\"modal\" data-target=\"#editar_Locacion\" class=\"btn btn-primary\" onclick=\"obtenerIdLocacionEditar('.$row['PKLocacion'].');\"><i class=\"fas fa-edit\"></i> Editar
  //href=\"#\" data-toggle=\"modal\" data-target=\"#eliminar_Locacion\" class=\"btn btn-danger\" onclick=\"obtenerIdLocacionEliminar('.$row['PKLocacion'].')\"><i class=\"fas fa-trash-alt\"></i> Eliminar
    //$edit = '<a class=\"btn btn-primary\" href=\"#\" data-toggle=\"modal\" data-target=\"#editar_Locacion\" class=\"btn btn-primary\" onclick=\"obtenerIdLocacionEditar('.$row['PKLocacion'].');\"><i class=\"fas fa-edit\"></i> Editar</a>&nbsp;&nbsp;';
	//$delete ='<a class=\"btn btn-danger\" href=\"#\" data-toggle=\"modal\" data-target=\"#eliminar_Locacion\" class=\"btn btn-danger\" onclick=\"obtenerIdLocacionEliminar('.$row['PKLocacion'].')\"><i class=\"fas fa-trash-alt\"></i> Eliminar</a>';
    //$table .='{"Locacion":"'.$row['Locacion'].'","Acciones":"'.$edit.$delete.'"},';
    if(preg_match('/[A-Z_\-0-9]/i',$row['Interior'])){
      $imp = $row['Prefijo']." ".$row['Interior'];
    }else{
      $imp ="";
    }
    $table .='{"id":"<label class=\"textTable\">'.$row['PKAlmacen'].'","Almacen":"<label class=\"textTable\">'.$row['Almacen'].'","Domicilio":"<label class=\"textTable\">Calle '.$row['Direccion'].' No. '.$row['Exterior'].' '.$imp.'","Colonia":"<label class=\"textTable\">'.$row['Colonia'].'","Ciudad":"<label class=\"textTable\">'.$row['Ciudad'].'","Estado":"<label class=\"textTable\">'.$row['Estado'].'","Pais":"<label class=\"textTable\">'.$row['Pais'].'</label><i><img class=\"btnEdit\" data-toggle=\"modal\" data-target=\"#modalEditar\" onclick=\"obtenerIdAlmacenEditar('.$row['PKAlmacen'].');\" src=\"../../img/timdesk/edit.svg\"></i>"},';
    //$table .='{"Locacion":"'.$row['Locacion'].'","Domicilio":"'.$row['Calle'].' '.$row['NumExt'].' '.$row['NumInt'].'","Colonia":"'.$row['Colonia'].'","Estado":"'.$row['Estado'].'"},';     
}
$table = substr($table,0,strlen($table)-1);
echo '{"data":['.$table.']}';
  //" ".." ".
?>
