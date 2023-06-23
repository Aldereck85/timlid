<?php
  require_once('../../../include/db-conn.php');

  $stmt = $conn->prepare('SELECT * FROM empleados LEFT JOIN datos_laborales_empleado ON PKEmpleado = FKEmpleado LEFT JOIN estatus_empleado ON FKEstatus = PKEstatusEmpleado LEFT JOIN finiquito ON finiquito.FKEmpleado = empleados.PKEmpleado');
  //$stmt = $conn->prepare('SELECT * FROM empleados LEFT JOIN datos_laborales_empleado ON PKEmpleado = FKEmpleado LEFT JOIN estatus_empleado ON FKEstatus = PKEstatusEmpleado');
  $stmt->execute();
  $table="";
  $estatus = "";
  while (($row = $stmt->fetch()) !== false) {

    $finiquito = "";
    $liquidacion = "";

    $finiquito ="";
    if($row['PKFiniquito'] == ""){
    //if($finiquito == ""){
      $finiquito = '<a class=\"dropdown-item\" href=\"finiquito.php?id='.$row['PKEmpleado'].'\"><i class=\"fas fa-donate\"></i> Finiquito</a>';
      $liquidacion = '<a class=\"dropdown-item\" href=\"liquidacion.php?id='.$row['PKEmpleado'].'\"><i class=\"fas fa-money-check-alt\"></i> Liquidacion</a>';
    }
    else{

      if($row['Tipo'] == 1)
        $finiquito = '<a class=\"dropdown-item\" href=\"finiquito.php?id='.$row['PKEmpleado'].'\"><i class=\"fas fa-donate\"></i> Finiquito</a>';

      if($row['Tipo'] == 2)
        $liquidacion = '<a class=\"dropdown-item\" href=\"liquidacion.php?id='.$row['PKEmpleado'].'\"><i class=\"fas fa-money-check-alt\"></i> Liquidacion</a>';
    }

    if($row['Estatus_Empleado'] != null){
      $estatus = $row['Estatus_Empleado'];
    }else{
      $estatus = 'No se ha registrado un estatus a este usuarios.';
    }
    $segNom = "S/N";

    $contrato = '<a class=\"dropdown-item\" href=\"../contratos/index.php?id='.$row['PKEmpleado'].'\"><i class=\"fas fa-money-check-alt\"></i> Contrato</a>';

    if($row['FKEstatus'] != 1){
      $funciones= '<div class=\"dropdown\"><button class=\"btn btn-primary btnMargin dropdown-toggle\" type=\"button\" id=\"dropdownMenuButton\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\"><i class=\"fas fa-tools\"></i> Opciones</button><div class=\"dropdown-menu\" aria-labelledby=\"dropdownMenuButton\"><a class=\"dropdown-item\" href=\"functions/ficha_empleado.php?id='.$row['PKEmpleado'].'\"><i class=\"far fa-id-card\"></i> Ficha</a><a class=\"dropdown-item\" href=\"#\" data-toggle=\"modal\" data-target=\"#editar_Empleado\" class=\"btn btn-primary\" onclick=\"obtenerIdEmpleadoEditar('.$row['PKEmpleado'].');\"><i class=\"fas fa-user-edit\"></i> Editar</a><a class=\"dropdown-item\" href=\"vacaciones.php?id='.$row['PKEmpleado'].'\"><i class=\"fas fa-umbrella-beach\"></i> Vacaciones</a><a class=\"dropdown-item\" href=\"aguinaldo.php?id='.$row['PKEmpleado'].'\"><i class=\"fas fa-money-bill-wave\"></i> Aguinaldo</a> '.$finiquito.$liquidacion.' <a class=\"dropdown-item\" href=\"#\" data-toggle=\"modal\" data-target=\"#Baja_Empleado\" class=\"btn btn-danger\" onclick=\"obtenerIdEmpleadoBaja('.$row['PKEmpleado'].')\"><i class=\"fas fa-user-times\"></i> Dar de baja</a>'.$contrato.' </div></div>';
      $table.='{"Id empleado":"'.$row['PKEmpleado'].'","Apellido paterno":"'.$row['Apellido_Paterno'].'","Apellido materno":"'.$row['Apellido_Materno'].'","Nombre(s)":"'.$row['Primer_Nombre'].'","Estatus":"'.$estatus.'","Acciones":"'.$funciones.'"},';
    }
  }
  $table = substr($table,0,strlen($table)-1);
	echo '{"data":['.$table.']}';
?>
