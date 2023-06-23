<?php
require_once('../../../include/db-conn.php');

$stmt = $conn->prepare('SELECT piezas_fabricadas.PKPiezaFabricada, piezas_fabricadas.NombrePiezas, piezas_fabricadas.Ancho, piezas_fabricadas.Largo, rollos.Gramos,rollos.Ancho FROM piezas_fabricadas INNER JOIN rollos ON FKRollo = PKRollo');
$stmt->execute();
$table="";

while (($row = $stmt->fetch()) !== false) {
    $edit = '<a class=\"btn btn-primary\" href=\"#\" data-toggle=\"modal\" data-target=\"#editar_Pieza_Fabricada\" class=\"btn btn-primary\" onclick=\"obtenerIdRolloEditar('.$row['PKPiezaFabricada'].');\"><i class=\"fas fa-edit\"></i> Editar</a>&nbsp;&nbsp;';
    $delete ='<a class=\"btn btn-danger\" href=\"#\" data-toggle=\"modal\" data-target=\"#eliminar_Pieza_Fabricada\" class=\"btn btn-danger\" onclick=\"obtenerIdRolloEliminar('.$row['PKPiezaFabricada'].')\"><i class=\"fas fa-trash-alt\"></i> Eliminar</a>';
    $especificacionRollo  = $row['Gramos']." grs / ".$row['Ancho']." mts";

    $table.='{"Nombre de la piezas":"'.$row['NombrePiezas'].'","Largo":"'.$row['Largo'].'","Ancho":"'.$row['Ancho'].'","Rollo grs / mts":"'.$especificacionRollo.'","Acciones":"'.$edit.$delete.'"},';
  }
  $table = substr($table,0,strlen($table)-1);
	echo '{"data":['.$table.']}';
 ?>
