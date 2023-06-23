<?php
  require_once('../../../include/db-conn.php');
  if(isset($_GET['id'])){
    $id =  $_GET['id'];
    $semana = $_GET['semana'];
  }
    $hora = "";
    $idTurno= "";
    $turno = "";
    $entrada = "";
    $salida = "";
    $dateBegin = array();
    $idEmpl = array();
    $fechaChec = array();
    $entradaTurno = array();
    $salidaComida = array();
    $regresoComida = array();
    $salidaTurno = array();
    $estatusChec = array();
    $tiempoTotalComida = array();
    $diaSem = array();
    /////////////////////////////////////////

    $stmt = $conn->prepare('SELECT PKTurno,Turno, Entrada, Salida FROM datos_empleo INNER JOIN turnos ON PKTurno = FKTurno WHERE FKEmpleado  = :id');
    $stmt->execute(array(':id'=>$id));
    $row = $stmt->fetch();
    $idTurno= $row['PKTurno'];
    $turno = $row['Turno'];
    $entradaOficial = new DateTime($row['Entrada']);
    $salidaOficial = new DateTime($row['Salida']);



    $stmt = $conn->prepare('SELECT FechaInicio,FechaTermino FROM semanas_checador WHERE PKChecador = :id');
    $stmt->execute(array(':id'=>$semana));
    $x = 0;

    while (($row = $stmt->fetch()) !== false) {
      $fecha = $row['FechaInicio'];
      $date = $row['FechaTermino'];

      $period = new DatePeriod(
           new DateTime($fecha),
           new DateInterval('P1D'),
           new DateTime($date)
      );

        foreach ($period as $key => $value) {
            $dateBegin[$x] = $value->format('Y-m-d');
            $x = $x + 1;
        }
    }

    $table="";
    $y = 0;
    $fechados = "2019-08-15";
    $numRow = 1;

    for($y = 0;$y<7;$y++){
      $stmt = $conn->prepare('SELECT * FROM doblar_turno WHERE FKEmpleado = :id AND FechaAutorizada = :fecha ORDER BY FechaAutorizada ASC');
      $stmt->execute(array(':id'=>$id,':fecha'=>$dateBegin[$y]));
        $row = $stmt->fetch();
        if($stmt->rowCount() > 0){
          array_push($idEmpl, $row['FKEmpleado']);
          $diaSemEn = date('D',strtotime($row['FechaAutorizada']));

          switch ($diaSemEn) {
              case "Mon":
                  array_push($diaSem, "Lunes");
                  break;
              case "Tue":
                  array_push($diaSem, "Martes");
                  break;
              case "Wed":
                  array_push($diaSem, "Miercoles");
                  break;
              case "Thu":
                  array_push($diaSem, "Jueves");
                  break;
              case "Fri":
                  array_push($diaSem, "Viernes");
                  break;
              case "Sat":
                  array_push($diaSem, "Sabado");
                  break;
              case "Sun":
                  array_push($diaSem, "Domingo");
                  break;
          }
          $formatoFecha = date('d/m/Y',strtotime($row['FechaAutorizada']));
          array_push($fechaChec, $formatoFecha);

          $entradaEmpleado = new DateTime($row['Entrada']);
          $salidaEmpleado = new DateTime($row['Salida']);
          //$entradaOficial  = new DateTime('10:30:00');


          if($entradaEmpleado <= $entradaOficial){
            $entradaColumna = "<label class='hrAprobada'>".$row['Entrada']."</label>";
          }else{
            $entradaColumna = "<label class='hrReprobada'>".$row['Entrada']."</label>";
          }

          if($salidaEmpleado >= $salidaOficial){
            $salidaColumna = "<label class='hrAprobada'>".$row['Salida']."</label>";
          }else{
            $salidaColumna = "<label class='hrReprobada'>".$row['Salida']."</label>";
          }

          //$intervalo = $row['Salida_Comida']->diff($row['Regreso_Comida']);

          $fecha1 = new DateTime($row['Salida_Comida']);
          $fecha2 = new DateTime($row['Regreso_Comida']);
          $intervalo = $fecha1->diff($fecha2);
          $tiempoComida = $intervalo->h." Horas ".$intervalo->i." minutos ".$intervalo->s." segundos ";

          array_push($entradaTurno, $entradaColumna);
          array_push($salidaComida, $row['Salida_Comida']);
          array_push($salidaTurno, $salidaColumna);
          array_push($regresoComida, $row['Regreso_Comida']);
          array_push($tiempoTotalComida,$tiempoComida);
          array_push($estatusChec, $row['Estatus']);
        }

    }


    $a = count($idEmpl);
    for($y = 0;$y<count($idEmpl);$y++){
      $funciones= '';
      $table.='{"Dia":"'.$diaSem[$y].'","Id empleado":"'.$idEmpl[$y].'","Fecha":"'.$fechaChec[$y].'","Entrada":"'.$entradaTurno[$y].'","Salida a comer":"'.$salidaComida[$y].'","Salida":"'.$salidaTurno[$y].'","Regreso de Comer":"'.$regresoComida[$y].'","Tiempo de comida":"'.$tiempoTotalComida[$y].'","Estatus":"'.$estatusChec[$y].'","Acciones":"'.$funciones.'"},';
      }
      $table = substr($table,0,strlen($table)-1);
      echo '{"data":['.$table.']}';
?>
