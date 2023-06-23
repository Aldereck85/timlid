<?php
  require_once('../../../include/db-conn.php');

  $stmt = $conn->prepare("SELECT * FROM equipos");
  $stmt->execute();
  $table="";
  //href=\"#\" data-toggle=\"modal\" data-target=\"#editar_Puesto\" class=\"btn btn-primary\" onclick=\"obtenerIdPuestoEditar('.$row['PKPuesto'].');\"><i class=\"fas fa-edit\"></i> Editar
  //href=\"#\" data-toggle=\"modal\" data-target=\"#eliminar_Puesto\" class=\"btn btn-danger\" onclick=\"obtenerIdPuestoEliminar('.$row['PKPuesto'].')\"><i class=\"fas fa-trash-alt\"></i> Eliminar
  while (($row = $stmt->fetch()) !== false) {
      $edit = '<a class=\"btn btn-primary\" href=\"#\" data-toggle=\"modal\" data-target=\"#editar_Equipo\" class=\"btn btn-primary\" onclick=\"obtenerIdEquipoEditar('.$row['PKEquipo'].');\"><i class=\"fas fa-edit\"></i> Editar</a>&nbsp;&nbsp;';
      $delete ='<a class=\"btn btn-danger\" href=\"#\" data-toggle=\"modal\" data-target=\"#eliminar_Equipo\" class=\"btn btn-danger\" onclick=\"obtenerIdEquipoEliminar('.$row['PKEquipo'].')\"><i class=\"fas fa-trash-alt\"></i> Eliminar</a>';
      /* "<a href=\"#\" data-toggle=\"modal\" data-target=\"#editar_Equipo\" class=\"link\" onclick=\"obtenerIdEquipoEditar('.$row['PKEquipo'].');\">'.$row['Nombre_Equipo'].'</a>" */
      
      /* data-toggle=\"modal\" data-target=\"#editar_Equipo\" */

      $table.='{"Equipo":"<label class=\"textTable\">'.$row['Nombre_Equipo'].'</label><i><img class=\"btnEdit\" id=\"btnEditarEquipo\" onclick=\"obtenerIdEquipoEditar('.$row['PKEquipo'].');\" src=\"../../img/timdesk/edit.svg\"></i>"},';//,"Acciones":"'.$edit.$delete.'"
      //$table.='{"Equipo":"<a href=\"../tareas/index.php?id='.$row['PKEquipo'].'\" class=\"link\">'.$row['Nombre_Equipo'].'</a>"},';//,"Acciones":"'.$edit.$delete.'"
    }
    $table = substr($table,0,strlen($table)-1);
    echo '{"data":['.$table.']}';
?>
