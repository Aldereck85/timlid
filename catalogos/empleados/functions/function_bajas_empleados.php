<?php
  require_once('../../../include/db-conn.php');

  $stmt = $conn->prepare('SELECT PKEmpleado,Primer_Nombre,Segundo_Nombre,Apellido_Paterno,Apellido_Materno,FKEstatus,PKEstatusEmpleado,Estatus_Empleado,DAY(Fecha_Baja),MONTH(Fecha_Baja),YEAR(Fecha_Baja), PKFiniquito, Tipo FROM empleados INNER JOIN datos_laborales_empleado ON empleados.PKEmpleado = datos_laborales_empleado.FKEmpleado INNER JOIN estatus_empleado ON FKEstatus = PKEstatusEmpleado LEFT JOIN finiquito ON finiquito.FKEmpleado = empleados.PKEmpleado');
  $stmt->execute();

  $table="";
  /*$funciones= '<div class=\"dropdown\"><button class=\"btn btn-outline-secondary btnMargin dropdown-toggle\" type=\"button\" id=\"dropdownMenuButton\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\"><i class=\"fas fa-tools\"></i> Opciones</button><div class=\"dropdown-menu\" aria-labelledby=\"dropdownMenuButton\"><a class=\"dropdown-item\" href=\"functions/ficha_empleado.php?id='.$row['PKEmpleado'].'\"><i class=\"far fa-id-card\"></i> Ficha</a><a class=\"dropdown-item\" href=\"#\" data-toggle=\"modal\" data-target=\"#editar_Empleado\" class=\"btn btn-primary\" onclick=\"obtenerIdEmpleadoEditar('.$row['PKEmpleado'].');\"><i class=\"fas fa-user-edit\"></i> Editar</a><a class=\"dropdown-item\" href=\"functions/detalles_empleo.php?id='.$row['PKEmpleado'].'\"><i class=\"fas fa-chalkboard-teacher\"></i> Puesto</a><a class=\"dropdown-item\" href=\"nomina.php?id='.$row['PKEmpleado'].'\"><i class=\"fas fa-user-clock\"></i> Checador</a> <a class=\"dropdown-item\" href=\"doble_turno.php?id='.$row['PKEmpleado'].'\"><i class=\"fas fa-business-time\"></i> Doble turno</a> <a class=\"dropdown-item\" href=\"horas_extras.php?id='.$row['PKEmpleado'].'\"><i class=\"fas fa-clock\"></i> Horas Extra</a> <a class=\"dropdown-item\" href=\"vacaciones.php?id='.$row['PKEmpleado'].'\"><i class=\"fas fa-umbrella-beach\"></i> Vacaciones</a> <a class=\"dropdown-item\" href=\"aguinaldo.php?id='.$row['PKEmpleado'].'\"><i class=\"fas fa-money-bill-wave\"></i> Aguinaldo</a> <a class=\"dropdown-item\" href=\"finiquito.php?id='.$row['PKEmpleado'].'\"><i class=\"fas fa-donate\"></i> Finiquito</a> <a class=\"dropdown-item\" href=\"#\" data-toggle=\"modal\" data-target=\"#eliminar_Empleado\" class=\"btn btn-danger\" onclick=\"obtenerIdEmpleadoEliminar('.$row['PKEmpleado'].')\"><i class=\"fas fa-user-times\"></i> Eliminar</a> </div></div>';
*/
  while (($row = $stmt->fetch()) !== false) {

    $finiquito = "";
    $liquidacion = "";

    if($row['PKFiniquito'] != ""){

      if($row['Tipo'] == 1)
        $finiquito ='<a class=\"btn btn-info\" href=\"finiquito.php?id='.$row['PKEmpleado'].'\" data-target=\"#finiquito_Empleado\" ><i class=\"fas fa-donate\"></i> Finiquito</a>';

      if($row['Tipo'] == 2)
        $liquidacion = '<a class=\"btn btn-info\" href=\"liquidacion.php?id='.$row['PKEmpleado'].'\" data-target=\"#liquidacion_Empleado\" ><i class=\"fas fa-donate\"></i> Liquidación</a>';
    }

    $fecha = $row['DAY(Fecha_Baja)']."/".$row['MONTH(Fecha_Baja)']."/".$row['YEAR(Fecha_Baja)'];
    if($row['FKEstatus'] == 1){
      $edit = '<a class=\"btn btn-primary btnMargin\" href=\"#\" data-toggle=\"modal\" data-target=\"#dar_AltaEmpleado\" onclick=\"obtenerIdEmpleadoAlta('.$row['PKEmpleado'].');\"><i class=\"fas fa-user-plus\"></i> Dar de alta</a>&nbsp;&nbsp;';
      $delete ='<a class=\"btn btn-danger btnMargin\" href=\"#\" data-toggle=\"modal\" data-target=\"#eliminar_Empleado\" onclick=\"obtenerIdEmpleadoEliminar('.$row['PKEmpleado'].')\"><i class=\"fas fa-trash-alt\"></i> Eliminar</a>&nbsp;&nbsp;';
      $table.='{"Id empleado":"'.$row['PKEmpleado'].'","Primer nombre":"'.$row['Primer_Nombre'].'","Segundo nombre":"'.$row['Segundo_Nombre'].'","Apellido paterno":"'.$row['Apellido_Paterno'].'","Apellido materno":"'.$row['Apellido_Materno'].'","Estatus":"'.$row['Estatus_Empleado'].'","Fecha baja":"'.$fecha.'","Acciones":"'.$edit.$delete.$finiquito.$liquidacion.'"},';
    }
  }
  $table = substr($table,0,strlen($table)-1);
	echo '{"data":['.$table.']}';
?>
