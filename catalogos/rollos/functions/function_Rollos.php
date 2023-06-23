<?php
require_once('../../../include/db-conn.php');

$stmt = $conn->prepare('SELECT * FROM rollos');
$stmt->execute();
$table="";

while (($row = $stmt->fetch()) !== false) {
    $edit = '<a class=\"btn btn-primary\" href=\"#\" data-toggle=\"modal\" data-target=\"#editar_Rollo\" class=\"btn btn-primary\" onclick=\"obtenerIdRolloEditar('.$row['PKRollo'].');\"><i class=\"fas fa-edit\"></i> Editar</a>&nbsp;&nbsp;';
    $delete ='<a class=\"btn btn-danger\" href=\"#\" data-toggle=\"modal\" data-target=\"#eliminar_Rollo\" class=\"btn btn-danger\" onclick=\"obtenerIdRolloEliminar('.$row['PKRollo'].')\"><i class=\"fas fa-trash-alt\"></i> Eliminar</a>';

    $table.='{"Gramos":"'.$row['Gramos'].'","Largo (mts)":"'.$row['Largo'].'","Ancho (mts)":"'.$row['Ancho'].'","Área":"'.$row['Area'].'","Acciones":"'.$edit.$delete.'"},';
  }
  $table = substr($table,0,strlen($table)-1);
	echo '{"data":['.$table.']}';
 ?>
