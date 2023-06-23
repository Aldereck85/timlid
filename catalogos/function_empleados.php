<?php
  require_once('../../../include/db-conn.php');

  $stmt = $conn->prepare('SELECT * FROM empleados LEFT JOIN datos_laborales_empleado ON PKEmpleado = FKEmpleado LEFT JOIN Estatus_Empleado ON FKEstatus = PKEstatusEmpleado');
  $stmt->execute();
  $table="";
  $estatus = "";
  while (($row = $stmt->fetch()) !== false) {
    if($row['Estatus_Empleado'] != null){
      $estatus = $row['Estatus_Empleado'];
    }else{
      $estatus = 'No se ha registrado un estatus a este usuarios.';
    }
    $segNom = "S/N";
    if(isset($row['Segundo_Nombre']) && !empty($row['Segundo_Nombre']))
      $segNom = $row['Segundo_Nombre'];
    if($row['FKEstatus'] != 1){
      $funciones= '<div class=\"dropdown\"><button class=\"btn btn-primary btnMargin dropdown-toggle\" type=\"button\" id=\"dropdownMenuButton\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\"><i class=\"fas fa-tools\"></i> Opciones</button><div class=\"dropdown-menu\" aria-labelledby=\"dropdownMenuButton\"><a class=\"dropdown-item\" href=\"functions/ficha_empleado.php?id='.$row['PKEmpleado'].'\"><i class=\"far fa-id-card\"></i> Ficha</a><a class=\"dropdown-item\" href=\"#\" data-toggle=\"modal\" data-target=\"#editar_Empleado\" class=\"btn btn-primary\" onclick=\"obtenerIdEmpleadoEditar('.$row['PKEmpleado'].');\"><i class=\"fas fa-user-edit\"></i> Editar</a><a class=\"dropdown-item\" href=\"vacaciones.php?id='.$row['PKEmpleado'].'\"><i class=\"fas fa-umbrella-beach\"></i> Vacaciones</a><a class=\"dropdown-item\" href=\"aguinaldo.php?id='.$row['PKEmpleado'].'\"><i class=\"fas fa-money-bill-wave\"></i> Aguinaldo</a> <a class=\"dropdown-item\" href=\"finiquito.php?id='.$row['PKEmpleado'].'\"><i class=\"fas fa-donate\"></i> Finiquito</a><a class=\"dropdown-item\" href=\"#\" data-toggle=\"modal\" data-target=\"#Baja_Empleado\" class=\"btn btn-danger\" onclick=\"obtenerIdEmpleadoBaja('.$row['PKEmpleado'].')\"><i class=\"fas fa-user-times\"></i> Dar de baja</a> </div></div>';
      $table.='{"Id empleado":"'.$row['PKEmpleado'].'","Primer nombre":"'.$row['Primer_Nombre'].'","Segundo nombre":"'.$segNom.'","Apellido paterno":"'.$row['Apellido_Paterno'].'","Apellido materno":"'.$row['Apellido_Materno'].'","Estatus":"'.$estatus.'","Acciones":"'.$funciones.'"},';
    }
  }
  $table = substr($table,0,strlen($table)-1);
	echo '{"data":['.$table.']}';
?>
