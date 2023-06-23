<?php
session_start();
require_once('../../../include/db-conn.php');

/*$stmt = $conn->prepare("SELECT pv.PKPermiso_Vacaciones, e.Primer_Nombre, e.Segundo_Nombre, e.Apellido_Paterno, e.Apellido_Materno, pv.DiasVacaciones, DATE_FORMAT(pv.FechaIni, '%d/%m/%Y') as fechaini, DATE_FORMAT(pv.FechaFin, '%d/%m/%Y') as fechafin, pv.Estatus FROM permiso_vacaciones as pv INNER JOIN empleados as e ON e.PKEmpleado = pv.FKEmpleado");*/

$usuario = $_SESSION['PKUsuario'];

$stmt = $conn->prepare('SELECT FKEmpleado FROM usuarios WHERE PKUsuario = :id');
$stmt->bindValue(':id', $usuario, PDO::PARAM_INT);
$stmt->execute();
$row = $stmt->fetch();

$stmt = $conn->prepare("SELECT pv.PKPermiso_Vacaciones, e.Primer_Nombre, e.Segundo_Nombre, e.Apellido_Paterno, e.Apellido_Materno, pv.DiasVacaciones, DATE_FORMAT(pv.FechaIni, '%d/%m/%Y') as fechaini, DATE_FORMAT(pv.FechaFin, '%d/%m/%Y') as fechafin, pv.Estatus FROM organigrama as o INNER JOIN organigrama as o2 ON o.PKOrganigrama = o2.ParentNode INNER JOIN permiso_vacaciones as pv ON pv.FKEmpleado = o2.FKEmpleado INNER JOIN empleados as e ON e.PKEmpleado = pv.FKEmpleado WHERE o.FKEmpleado = :id");
$stmt->bindValue(':id', $row['FKEmpleado'], PDO::PARAM_INT);
$stmt->execute();

$table="";

while (($row = $stmt->fetch()) !== false) {

    if($row['Segundo_Nombre'] == "")
      $segundoNombre = "";
    else
      $segundoNombre = $row['Segundo_Nombre']." ";

    $nombreEmpleado = $row['Primer_Nombre']." ".$segundoNombre.$row['Apellido_Paterno'].' '.$row['Apellido_Materno'];

    if($row['Estatus'] == 0){
      $estatus = '<center><img src=\"../../img/wrong.png\" class=\"img-responsive\" /></center>';
      $estado = 'Aceptar';

      $modificarestatus = '<a class=\"btn btn-success\" href=\"#\" data-toggle=\"modal\" data-target=\"#modificarEstatus\" class=\"btn btn-primary\" onclick=\"obtenerIdModificarEstatus('.$row['PKPermiso_Vacaciones'].',1);\"><i class=\"fas fa-edit\"></i> '.$estado.'</a>&nbsp;&nbsp;';
    }
    else{
      $estatus = '<center><img src=\"../../img/success.png\" class=\"img-responsive\" /></center>';
      $estado = 'Cancelar';

      $modificarestatus = '<a class=\"btn btn-success\" href=\"#\" data-toggle=\"modal\" data-target=\"#modificarEstatusCanc\" class=\"btn btn-primary\" onclick=\"obtenerIdModificarEstatus('.$row['PKPermiso_Vacaciones'].',0);\"><i class=\"fas fa-edit\"></i> '.$estado.'</a>&nbsp;&nbsp;';
    }

      
      $delete ='<a class=\"btn btn-danger\" href=\"#\" data-toggle=\"modal\" data-target=\"#eliminar_Vacaciones\" class=\"btn btn-danger\" onclick=\"obtenerIdVacacionesEliminar('.$row['PKPermiso_Vacaciones'].','.$row['Estatus'].')\"><i class=\"fas fa-trash-alt\"></i> Eliminar</a>';
      

        $table.='{"ID":"'.$row['PKPermiso_Vacaciones'].'","Empleado":"'.$nombreEmpleado.'","Dias Vacaciones":"'.$row['DiasVacaciones'].'","Fecha Inicio":"'.$row['fechaini'].'","Fecha Termino":"'.$row['fechafin'].'","Estatus":"'.$estatus.'","Acciones":"'.$modificarestatus.$delete.'"},';

  }
  $table = substr($table,0,strlen($table)-1);
	echo '{"data":['.$table.']}';
 ?>