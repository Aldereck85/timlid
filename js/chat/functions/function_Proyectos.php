<?php
require_once('../../../include/db-conn.php');

$stmt = $conn->prepare("SELECT p.PKProyecto, p.Proyecto, CONCAT(e.Primer_Nombre,' ',e.Segundo_Nombre,' ',e.Apellido_Paterno,' ',e.Apellido_Materno) as nombre_empleado 
                        FROM proyectos as p LEFT JOIN usuarios as u ON u.PKUsuario = p.FKResponsable LEFT JOIN empleados as e ON e.PKEmpleado = u.FKEmpleado");
$stmt->execute();
$table="";
//href=\"#\" data-toggle=\"modal\" data-target=\"#editar_Puesto\" class=\"btn btn-primary\" onclick=\"obtenerIdPuestoEditar('.$row['PKPuesto'].');\"><i class=\"fas fa-edit\"></i> Editar
//href=\"#\" data-toggle=\"modal\" data-target=\"#eliminar_Puesto\" class=\"btn btn-danger\" onclick=\"obtenerIdPuestoEliminar('.$row['PKPuesto'].')\"><i class=\"fas fa-trash-alt\"></i> Eliminar
while (($row = $stmt->fetch()) !== false) {
    $edit = '<a class=\"btn btn-primary\" href=\"#\" data-toggle=\"modal\" data-target=\"#editar_Proyecto\" class=\"btn btn-primary\" onclick=\"obtenerIdProyectoEditar('.$row['PKProyecto'].');\"><i class=\"fas fa-edit\"></i> Editar</a>&nbsp;&nbsp;';
    $delete ='<a class=\"btn btn-danger\" href=\"#\" data-toggle=\"modal\" data-target=\"#eliminar_Proyecto\" class=\"btn btn-danger\" onclick=\"obtenerIdProyectoEliminar('.$row['PKProyecto'].')\"><i class=\"fas fa-trash-alt\"></i> Eliminar</a>';

    $table.='{"Proyecto":"<a href=\"../tareas/index.php?id='.$row['PKProyecto'].'\" class=\"link\">'.$row['Proyecto'].'</a>","Usuario":"'.$row['nombre_empleado'].'"},';//"Acciones":"'.$edit.$delete.'"
  }
  $table = substr($table,0,strlen($table)-1);
	echo '{"data":['.$table.']}';
 ?>
