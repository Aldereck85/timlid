<?php
require_once('../../../include/db-conn.php');
session_start();
$stmt = $conn->prepare("SELECT p.PKProyecto, p.Proyecto, CONCAT(e.Nombres,' ', e.PrimerApellido) as nombre_empleado, u.id, p.Descripcion
                          FROM proyectos as p LEFT JOIN usuarios as u ON u.id = p.FKResponsable INNER JOIN empleados as e ON e.PKEmpleado = u.id WHERE p.empresa_id = " . $_SESSION['IDEmpresa'] . " AND p.PKProyecto <> 3");
$stmt->execute();
$table = "";
$rowproyectos = $stmt->fetchAll();
//href=\"#\" data-toggle=\"modal\" data-target=\"#editar_Puesto\" class=\"btn btn-primary\" onclick=\"obtenerIdPuestoEditar('.$row['PKPuesto'].');\"><i class=\"fas fa-edit\"></i> Editar
//href=\"#\" data-toggle=\"modal\" data-target=\"#eliminar_Puesto\" class=\"btn btn-danger\" onclick=\"obtenerIdPuestoEliminar('.$row['PKPuesto'].')\"><i class=\"fas fa-trash-alt\"></i> Eliminar
foreach ($rowproyectos as $row) {

  $stmt = $conn->prepare("SELECT FKUsuario FROM integrantes_proyecto WHERE FKProyecto = :idProyecto");
  $stmt->bindValue(":idProyecto", $row['PKProyecto']);
  $stmt->execute();
  $usuariosProyecto = $stmt->fetchAll();
  //print_r($usuariosProyecto);

  $stmt = $conn->prepare("SELECT ie.FKEmpleado as FKUsuario, ep.FKEquipo FROM equipos_por_proyecto as ep INNER JOIN integrantes_equipo as ie ON ie.FKEquipo = ep.FKEquipo WHERE ep.FKProyecto = :idProyecto");
  $stmt->bindValue(":idProyecto", $row['PKProyecto']);
  $stmt->execute();
  $usuariosEquipo = $stmt->fetchAll();
  //var_dump($usuariosEquipo);

  $usuarios = array_merge($usuariosProyecto, $usuariosEquipo);
  //print_r($usuarios);

  $cont = 0;
  foreach ($usuarios as $us) {
    if ($us['FKUsuario'] == $_SESSION['PKUsuario'])
      $cont++;
  }


  if ($_SESSION['PKUsuario'] == $row['id'] || $cont > 0) {
    $edit = '<a class=\"btn btn-primary\" href=\"#\" data-toggle=\"modal\" data-target=\"#modalEditar\" class=\"btn btn-primary\" onclick=\"obtenerIdProyectoEditar(' . $row['PKProyecto'] . ');\"><i class=\"fas fa-edit\"></i> Editar</a>&nbsp;&nbsp;';
    $delete = '<a class=\"btn btn-danger\" href=\"#\" data-toggle=\"modal\" data-target=\"#eliminar_Proyecto\" class=\"btn btn-danger\" onclick=\"obtenerIdProyectoEliminar(' . $row['PKProyecto'] . ')\"><i class=\"fas fa-trash-alt\"></i> Eliminar</a>';

    $table .= '{
          "Proyecto":"<a href=\"../tareas/timDesk/index.php?id=' . $row['PKProyecto'] . '\"  class=\"linkTable link\">' . $row['Proyecto'] . '</a>",
          "Descripcion":"' . $row['Descripcion'] . '",
          "Acciones":"",
          "Usuario":"' . $row['nombre_empleado'] . '"},'; //"Acciones":"'.$edit.$delete.'"
    //$table.='{"Proyecto":"<a href=\"../tareas/timdesk/index.php?id='.$row['PKProyecto'].'\" class=\"link\">'.$row['Proyecto'].'</a>","Usuario":"'.$row['nombre_empleado'].'"},';//"Acciones":"'.$edit.$delete.'"
  }
}
$table = substr($table, 0, strlen($table) - 1);
echo '{"data":[' . $table . ']}';
