<?php
session_start();
require_once('../../../include/db-conn.php');

$idUsuario =  $_SESSION['PKUsuario'];

$stmt = $conn->prepare('SELECT tareas.PKTarea, tareas.Tarea, prioridades.Prioridad, prioridades.Color, tareas.Estatus, DAY(tareas.Fecha_Inicio), MONTH(tareas.Fecha_Inicio), YEAR(tareas.Fecha_Inicio), tareas.FKResponsable, DAY(tareas.Fecha_Termino), MONTH(tareas.Fecha_Termino), YEAR(tareas.Fecha_Termino), tareas.Fecha_Termino, tareas.Porcentaje_Avance, tareas.Notas, emplResponsable.Primer_Nombre AS Nombre_Responsable, emplResponsable.Apellido_Paterno AS Apellido_Responsable, empAsig.Primer_Nombre AS Nombre_Asignatario, empAsig.Apellido_Paterno AS Apellido_Asignatario FROM tareas_pendientes tareas INNER JOIN prioridades ON FKPrioridad = PKPrioridad INNER JOIN usuarios usResponsable ON tareas.FKResponsable = usResponsable.PKUsuario INNER JOIN empleados emplResponsable ON usResponsable.FKEmpleado = emplResponsable.PKEmpleado INNER JOIN usuarios usAsignatario ON tareas.FKUsuarioAsig = usAsignatario.PKUsuario INNER JOIN empleados empAsig ON usAsignatario.FKEmpleado = empAsig.PKEmpleado 
  LEFT JOIN subtareas subt ON subt.FKTarea = tareas.PKTarea WHERE subt.FKUsuario = :idUsuario GROUP BY subt.FKTarea  ');
$stmt->bindValue(":idUsuario",$idUsuario);
$stmt->execute();
$table="";

while (($row = $stmt->fetch()) !== false) {
    $fecha_inicio = $row['DAY(tareas.Fecha_Inicio)'].'/'.$row['MONTH(tareas.Fecha_Inicio)'].'/'.$row['YEAR(tareas.Fecha_Inicio)'];
    $fecha_termino = $row['DAY(tareas.Fecha_Termino)'].'/'.$row['MONTH(tareas.Fecha_Termino)'].'/'.$row['YEAR(tareas.Fecha_Termino)'];

    if(date("Y-m-d")  > $row['Fecha_Termino']  && $row['Estatus'] != 3)
      $estatusID = 5;
    else
      $estatusID = $row['Estatus'];

      $cambiarEstatus = '<div class=\"dropdown\"><button class=\"btn btn-secondary dropdown-toggle\" type=\"button\" id=\"dropdownMenuButton\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\"> Estatus</button><div class=\"dropdown-menu\" aria-labelledby=\"dropdownMenuButton\"><a class=\"dropdown-item\"  href=\"functions/cambiar_Estatus.php?id='.$row['PKTarea'].'&estatus=1\"><i class=\"fas fa-play\"></i> Por comenzar</a><a class=\"dropdown-item\" href=\"functions/cambiar_Estatus.php?id='.$row['PKTarea'].'&estatus=3\"><i class=\"fas fa-check\"></i> Terminado</a><a class=\"dropdown-item\" href=\"functions/cambiar_Estatus.php?id='.$row['PKTarea'].'&estatus=2\"><i class=\"fas fa-hourglass-half\"></i> En proceso</a><a class=\"dropdown-item\" href=\"functions/cambiar_Estatus.php?id='.$row['PKTarea'].'&estatus=4\"><i class=\"fas fa-times\"></i> No realizado</div></div>';

      $avance = '<div style=\"text-align:center;\"><div class=\"progress\"><div class=\"progress-bar progress-bar-striped bg-info\" role=\"progressbar\" style=\"width: '.$row['Porcentaje_Avance'].'%\" aria-valuenow=\"'.$row['Porcentaje_Avance'].'\" aria-valuemin=\"0\" aria-valuemax=\"100\"></div></div>'.$row['Porcentaje_Avance'].'%</div>';
      $ver = '<a class=\"btn btn-success\" href=\"functions/subtareas.php?id='.$row['PKTarea'].'\" class=\"btn btn-primary\" onclick=\"obtenerIdTareaEditar('.$row['PKTarea'].');\"><i class=\"fas fa-eye\"></i>Ver subtareas</a>&nbsp;&nbsp;';
      $eliminar = '<a class=\"btn btn-danger\" href=\"logoutModal\"><i class=\"fas fa-trash-alt\"></i> Eliminar</a>';
      $prioridad = "<div style='background:".$row['Color'].";padding:5px;color:white;-webkit-border-radius: 16px; -moz-border-radius: 16px; border-radius: 16px;'><center>".$row['Prioridad']."</center></div>";
      $responsable = $row['Nombre_Responsable']." ".$row['Apellido_Responsable'];
      $asignante = $row['Nombre_Asignatario']." ".$row['Apellido_Asignatario'];
      switch ($estatusID) {
        case 1:
            $estatus = '<div style=\"color:#4e73df;\"><i class=\"fas fa-play\"></i> Por comenzar</div>';
            break;
        case 2:
            $estatus = '<div style=\"color:#f6c23e;\"><i class=\"fas fa-hourglass-half\"></i> En proceso</div>';
            break;
        case 3:
            $estatus = '<div style=\"color:#1cc88a;\"><i class=\"fas fa-check\"></i> Terminado</div>';
            break;
        case 4:
            $estatus = '<div style=\"color:#e74a3b;\"><i class=\"fas fa-times\"></i> No realizado</div>';
            break;
        case 5:
            $estatus = '<div style=\"color:#e74a3b;\"><i class=\"fas fa-angle-double-left\"></i> Retrasado</div>';
            break;
      }


        $table.='{"Tarea":"'.$row['Tarea'].'","Prioridad":"'.$prioridad .'","Estatus":"'.$estatus.'","Fecha de inicio":"'.$fecha_inicio.'","Fecha de termino":"'.$fecha_termino.'","Responsable":"'.$responsable.'","Nota":"'.$row['Notas'].'","Porcentaje":"'.$avance.'" ,"Acciones":"'.$ver.'"},';
    
  }
  $table = substr($table,0,strlen($table)-1);
	echo '{"data":['.$table.']}';
 ?>