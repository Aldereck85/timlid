<?php
  require_once('../../../include/db-conn.php');
  $fechasCalculo = array();
  $x = 0;
  $conteoAsistencias = 0;
  $number_of_rows = 0;
  $estatus = "Pagado";

  if(isset($_GET['turno'])){
    $idTurno =  $_GET['turno'];
  } 

  $stmt = $conn->prepare("SELECT * FROM empleados INNER JOIN datos_empleo ON FKEmpleado = PKEmpleado INNER JOIN puestos ON FKPuesto = PKPuesto WHERE FKTurno =".$idTurno." AND Bono = 1");
  $stmt->execute();
  $table="";
  while (($row = $stmt->fetch()) !== false) {
    $funciones = "";
    $bonoParcial = 0;
    
    $fechaIngreso = new DateTime($row['Fecha_Ingreso']);
    $fechaHoy = new DateTime('2019-10-22');

    ////////////////////////////////////////////////////////////////////

    $fechaAhora= time();
    $fechaMes = date("m",$fechaAhora);

    $st = $conn->prepare('SELECT COUNT(*) FROM bono_mensual WHERE FKEmpleado = :fkEmpleado  AND MONTH(Fecha) = :fecha');
    $st->bindValue(':fkEmpleado',$row['PKEmpleado']);
    $st->bindValue(':fecha',$fechaMes);
    $st->execute();

    $number_of_rows = $st->fetchColumn();

    if($number_of_rows == 1){
      $estatus = "Pagado";
    }else{
      $estatus = "Pendiente";
    }
    
    //////////////////////////////////////////////////////////////////

    $idEmpleado = $row['PKEmpleado'];
    $primerNombre = $row['Primer_Nombre'];
    $segundoNombre = $row['Segundo_Nombre'];
    $primerApellido = $row['Apellido_Paterno'];
    $segundoApellido = $row['Apellido_Materno'];
    $puesto = $row['Puesto'];
    $fechaIngresoEmpleado = $row['Fecha_Ingreso'];

    //$fechaIngresado = date_format($fechaIngresoEmpleado, 'd-m-Y');;

    $interval = $fechaIngreso->diff($fechaHoy);
    $meses = $interval->format('%m');
    $dias = $interval->format('%d');
    $fechaFinal;
    $fechaInicio;

    if($meses >= 1){
      $bonoParcial = 0;
      $fechaFinal = $fechaHoy->format('Y-m-d');
      $fechaInicio = date('Y-m-d', strtotime("-1 month", strtotime($fechaFinal)));

      $periodo = new DatePeriod(
        new DateTime($fechaInicio),
        new DateInterval('P1D'),
        new DateTime($fechaFinal)
      );

     foreach ($periodo as $key => $value) {
         $fechasCalculo[$x] = $value->format('Y-m-d');
         $x = $x + 1;
     }

     $max = sizeof($fechasCalculo);

     for($y = 0;$y<$max;$y++){
      $st = $conn->prepare('SELECT Estatus FROM gh_checador WHERE FKUsuario = :id AND Fecha = :fecha ORDER BY Fecha ASC');
      $st->execute(array(':id'=>$idEmpleado,':fecha'=>$fechasCalculo[$y]));
      $rw = $st->fetch();

      switch ($rw['Estatus']) {
        case 4:
            $conteoAsistencias++;
            break;
        case 9:
            $conteoAsistencias++;
            break;
        case 10:
            $conteoAsistencias++;
            break;
        case 11:
            $conteoAsistencias++;
            break;
        case 12:
            $conteoAsistencias++;
            break;
      }
      

     }

     if($conteoAsistencias == $max){
      if($number_of_rows != 1){
        $funciones = '<a class=\"btn btn-primary\" target=\"_blank\" href=\"ver_bono.php?id='.$idEmpleado.'\"><i class=\"fas fa-money-check-alt\"></i> Asignar bono</a>&nbsp;&nbsp;'; 
      }else{
        $funciones = "";
      }
        
      }else{
        $funciones = '<div style=\"color:#e74a3b\"><i class=\"fas fa-ban\"></i> Bono no aprobado</div>';
      }
      $conteoAsistencias = 0;

    }else{
      $max = 10;
      $bonoParcial = 1;
      $fechaFinal = $fechaHoy->format('Y-m-d');
      $fechaInicio = $fechaIngreso->format('Y-m-d');

      $periodo = new DatePeriod(
        new DateTime($fechaInicio),
        new DateInterval('P1D'),
        new DateTime($fechaFinal)
      );

     foreach ($periodo as $key => $value) {
         $fechasCalculo[$x] = $value->format('Y-m-d');
         $x = $x + 1;
     }

     $max = sizeof($fechasCalculo);

     for($y = 0;$y<$max;$y++){
      $st = $conn->prepare('SELECT Estatus FROM gh_checador WHERE FKUsuario = :id AND Fecha = :fecha ORDER BY Fecha ASC');
      $st->execute(array(':id'=>$idEmpleado,':fecha'=>$fechasCalculo[$y]));
      $rw = $st->fetch();

      switch ($rw['Estatus']) {
        case 4:
            $conteoAsistencias++;
            break;
        case 9:
            $conteoAsistencias++;
            break;
        case 10:
            $conteoAsistencias++;
            break;
        case 11:
            $conteoAsistencias++;
            break;
        case 12:
            $conteoAsistencias++;
            break;
      }
      

     }

    if($conteoAsistencias == $max){
      if($number_of_rows != 1){
        $funciones = '<a class=\"btn btn-primary\" target=\"_blank\" href=\"ver_bono_parcial.php?id='.$row['PKEmpleado'].'\"><i class=\"fas fa-money-check-alt\"></i> Asignar bono parcial</a>&nbsp;&nbsp;';
      }else{
        $funciones = "";
      }
    }else{
      $funciones = '<div style=\"color:#e74a3b\"><i class=\"fas fa-ban\"></i> Bono no aprobado</div>';
    }
    $conteoAsistencias = 0;

      
    }

    
    $table.='{"Id empleado":"'.$idEmpleado.'","Primer nombre":"'.$primerNombre.'","Segundo nombre":"'.$segundoNombre.'","Apellido paterno":"'.$primerApellido.'","Apellido materno":"'.$segundoApellido.'","Puesto":"'.$puesto.'","Fecha de ingreso":"'.$fechaIngresoEmpleado.'","Estatus":"'.$estatus.'","Acciones":"'.$funciones.'"},';

  }
  $table = substr($table,0,strlen($table)-1);
  echo '{"data":['.$table.']}';
  

?>
