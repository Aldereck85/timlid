<?php
  require_once('../../../include/db-conn.php');

  $stmt = $conn->prepare('SELECT * FROM estatus_empleado');
  $stmt->execute();
  $table="";
  $funciones = "";
  $no = 0;
  while (($row = $stmt->fetch()) !== false) {
    if($row['PKEstatusEmpleado'] != 1){
      $edit = '<a class=\"btn btn-primary btnMargin\" href=\"#\" data-toggle=\"modal\" data-target=\"#editar_EstatusEmpleado\" onclick=\"obtenerIdEstatusEmpleadoEditar('.$row['PKEstatusEmpleado'].');\"><i class=\"fas fa-edit\"></i> Editar</a>&nbsp;&nbsp;';
      $delete ='<a class=\"btn btn-danger btnMargin\" href=\"#\" data-toggle=\"modal\" data-target=\"#eliminar_EstatusEmpleado\" onclick=\"obtenerIdEstatusEmpleadoEliminar('.$row['PKEstatusEmpleado'].')\"><i class=\"fas fa-trash-alt\"></i> Eliminar</a>';

      $table.='{"No estatus empleado":"'.$no.'","Estatus":"'.$row['Estatus_Empleado'].'","Acciones":"'.$edit.$delete.'"},';
    }
    $no++;
  }
  $table = substr($table,0,strlen($table)-1);
	echo '{"data":['.$table.']}';
?>
