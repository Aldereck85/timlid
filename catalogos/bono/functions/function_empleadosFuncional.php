<?php
  require_once('../../../include/db-conn.php');
  $fechasCalculo = array();
  
  if(isset($_GET['turno'])){
    $idTurno =  $_GET['turno'];
    $semana = $_GET['semana'];
  } 

  $stmt = $conn->prepare("SELECT * FROM empleados INNER JOIN datos_empleo ON FKEmpleado = PKEmpleado INNER JOIN puestos ON FKPuesto = PKPuesto WHERE FKTurno =".$idTurno." AND Bono = 1");
  $stmt->execute();
  $table="";
  while (($row = $stmt->fetch()) !== false) {
    $funciones = "";
    $bonoParcial = 0;
    $fechaIngreso = new DateTime($row['Fecha_Ingreso']);
    $fechaHoy = new DateTime('2019-10-22');

    $interval = $fechaIngreso->diff($fechaHoy);
    $meses = $interval->format('%m');
    $dias = $interval->format('%d');
    $fechaFinal;
    $fechaInicio;

    //$funciones = date('y,m,d', strtotime("-1 month", strtotime($fechaHoy)));

    if($meses >= 1){
      $bonoParcial = 0;
      $fechaFinal = $fechaHoy->format('Y-m-d');
      $fechaInicio = date('Y-m-d', strtotime("-1 month", strtotime($fechaFinal)));
      $funciones = '<a class=\"btn btn-primary\" href=\"ver_nomina.php?id='.$row['PKEmpleado'].'&semana='.$semana.'\"><i class=\"fas fa-money-check-alt\"></i> Asignar bono</a>&nbsp;&nbsp;'; 
    }else{
      $bonoParcial = 1;
      $fechaFinal = $fechaHoy->format('Y-m-d');
      $fechaInicio = $fechaIngreso->format('Y-m-d');
      $funciones = '<a class=\"btn btn-primary\" href=\"ver_nomina.php?id='.$row['PKEmpleado'].'&semana='.$semana.'\"><i class=\"fas fa-money-check-alt\"></i> Asignar bono parcial</a>&nbsp;&nbsp;';
    }


    /*$stmt = $conn->prepare('SELECT COUNT(*) FROM nomina WHERE FKSemana = :semana  AND FKEmpleado = :empleado');
    $stmt->execute(array(':semana' => $semana, ':empleado' => $row['PKEmpleado']));*/

    /*$number_of_rows = $stmt->fetchColumn();
    if($number_of_rows > 0)
    {
      $funciones = '<a class=\"btn btn-success\" href=\"ver_nomina.php?id='.$row['PKEmpleado'].'&semana='.$semana.'\"><i class=\"fas fa-search-dollar\"></i> Ver nomina</a>&nbsp;&nbsp;'; 
    }else{
      $funciones= '<div class=\"dropdown\"><button class=\"btn btn-secondary btnMargin dropdown-toggle\" type=\"button\" id=\"dropdownMenuButton\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\"><i class=\"fas fa-tools\"></i> Opciones</button><div class=\"dropdown-menu\" aria-labelledby=\"dropdownMenuButton\"><a class=\"dropdown-item\" href=\"nomina.php?id='.$row['PKEmpleado'].'&Dias=5\"><i class=\"fas fa-user-clock\"></i> Checador</a><a class=\"dropdown-item\" href=\"asistencia.php?id='.$row['PKEmpleado'].'&semana='.$semana.'\"><i class=\"fas fa-file-invoice-dollar\"></i> Nomina</a> </div></div>';
    }*/
    
    $table.='{"Id empleado":"'.$row['PKEmpleado'].'","Primer nombre":"'.$row['Primer_Nombre'].'","Segundo nombre":"'.$row['Segundo_Nombre'].'","Apellido paterno":"'.$row['Apellido_Paterno'].'","Apellido materno":"'.$row['Apellido_Materno'].'","Puesto":"'.$row['Puesto'].'","Fecha de ingreso":"'.$row['Fecha_Ingreso'].'","Acciones":"'.$funciones.'"},';

  }
  $table = substr($table,0,strlen($table)-1);
  echo '{"data":['.$table.']}';
  

?>
