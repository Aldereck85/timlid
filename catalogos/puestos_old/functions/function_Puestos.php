<?php
require_once('../../../include/db-conn.php');

$stmt = $conn->prepare('SELECT * FROM puestos');
$stmt->execute();
$table="";
//href=\"#\" data-toggle=\"modal\" data-target=\"#editar_Puesto\" class=\"btn btn-primary\" onclick=\"obtenerIdPuestoEditar('.$row['PKPuesto'].');\"><i class=\"fas fa-edit\"></i> Editar
//href=\"#\" data-toggle=\"modal\" data-target=\"#eliminar_Puesto\" class=\"btn btn-danger\" onclick=\"obtenerIdPuestoEliminar('.$row['PKPuesto'].')\"><i class=\"fas fa-trash-alt\"></i> Eliminar
while (($row = $stmt->fetch()) !== false) {
    $edit = '<a class=\"btn btn-primary\" href=\"#\" data-toggle=\"modal\" data-target=\"#editar_Puesto\" class=\"btn btn-primary\" onclick=\"obtenerIdPuestoEditar('.$row['PKPuesto'].');\"><i class=\"fas fa-edit\"></i> Editar</a>&nbsp;&nbsp;';
    $delete ='<a class=\"btn btn-danger\" href=\"#\" data-toggle=\"modal\" data-target=\"#eliminar_Puesto\" class=\"btn btn-danger\" onclick=\"obtenerIdPuestoEliminar('.$row['PKPuesto'].')\"><i class=\"fas fa-trash-alt\"></i> Eliminar</a>';
    $sueldo = "$".number_format($row['Sueldo_semanal'],2);

    $table.='{"Puesto":"'.$row['Puesto'].'","Sueldo semanal":"'.$sueldo.'","Acciones":"'.$edit.$delete.'"},';
  }
  $table = substr($table,0,strlen($table)-1);
	echo '{"data":['.$table.']}';
 ?>
