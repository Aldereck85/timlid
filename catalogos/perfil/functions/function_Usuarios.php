<?php
require_once('../../../include/db-conn.php');

$stmt = $conn->prepare('SELECT PKUsuario,PKEmpleado, Primer_Nombre, Apellido_Paterno, Usuario, Rol FROM usuarios INNER JOIN empleados on FKEmpleado = PKEmpleado INNER JOIN roles on FKRol = PKRol');
$stmt->execute();

  $table="";
  //href=\"#\" data-toggle=\"modal\" data-target=\"#editar_Usuario\" class=\"btn btn-primary\" onclick=\"obtenerIdUsuarioEditar('.$row['PKUsuario'].');\"><i class=\"fas fa-edit\"></i> Editar
  //href=\"#\" data-toggle=\"modal\" data-target=\"#eliminar_Usuario\" class=\"btn btn-danger\" onclick=\"obtenerIdUsuarioEliminar('.$row['PKUsuario'].')\"><i class=\"fas fa-trash-alt\"></i> Eliminar
  while (($row = $stmt->fetch()) !== false) {
    $edit = '<a class=\"btn btn-primary\" href=\"#\" data-toggle=\"modal\" data-target=\"#editar_Usuario\" class=\"btn btn-primary\" onclick=\"obtenerIdUsuarioEditar('.$row['PKUsuario'].');\"><i class=\"fas fa-edit\"></i> Editar</a>&nbsp;&nbsp;';
		$delete ='<a class=\"btn btn-danger\" href=\"#\" data-toggle=\"modal\" data-target=\"#eliminar_Usuario\" class=\"btn btn-danger\" onclick=\"obtenerIdUsuarioEliminar('.$row['PKUsuario'].')\"><i class=\"fas fa-trash-alt\"></i> Eliminar</a>';
    $table.='{"Id empleado":"'.$row['PKEmpleado'].'","Primer nombre":"'.$row['Primer_Nombre'].'","Apellido paterno":"'.$row['Apellido_Paterno'].'","Usuario":"'.$row['Usuario'].'","Rol":"'.$row['Rol'].'","Acciones":"'.$edit.$delete.'"},';
  }
  $table = substr($table,0,strlen($table)-1);
	echo '{"data":['.$table.']}';
 ?>
