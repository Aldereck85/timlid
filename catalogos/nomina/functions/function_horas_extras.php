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
    $funcionesEncargado = array();
    $tiempoDeuda = array();
    $justificarBoton = array();////////////////////
    $horasAutorizadas = array();
    $encargado = array();
    $tiempoDeber;
    $horasDeber = 0;
    $minutosDeber = 0;
    $segundosDeber = 0;
    $tiempoComidaOficial = new DateTime('00:32:00');
    $funciones = array();
    

    $stmt = $conn->prepare('SELECT PKTurno,Turno, Entrada,Salida,Dias_de_trabajo FROM datos_laborales_empleado INNER JOIN turnos ON PKTurno = FKTurno WHERE FKEmpleado  = :id');
    $stmt->execute(array(':id'=>$id));
    $row = $stmt->fetch();
    $idTurno= $row['PKTurno'];
    $turno = $row['Turno'];
    $entradaOficial = new DateTime($row['Entrada']);
    $salidaOficial = new DateTime($row['Salida']);
    $interval = $entradaOficial->diff($salidaOficial);
    $horasNoTrabajadas = $interval->format('%H');
    $minutosNoTrabajados = $interval->format('%I');

    $diasTrab = $row['Dias_de_trabajo'];

    $stmt = $conn->prepare('SELECT PKNomina FROM nomina WHERE FKEmpleado = :id AND FKSemana = :semana');
    $stmt->execute(array(':id'=>$id,':semana'=>$semana));
    $nominaExiste = $stmt->rowCount();

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
        $sumEstatus = 0;
        $stmt = $conn->prepare('SELECT he.PKHoraExtra, he.Horas_Autorizadas, he.FKEmpleado, he.FechaAutorizada, he.Entrada, he.Salida, he.Horas_Trabajadas, CONCAT(e.Primer_Nombre," ",e.Segundo_Nombre," ",e.Apellido_Paterno," ",e.Apellido_Materno) as Nombre_Empleado FROM horas_extras as he LEFT JOIN empleados as e ON e.PKEmpleado = he.FKEmpleado WHERE he.FKEmpleado = :id AND he.FechaAutorizada = :fecha ORDER BY FechaAutorizada ASC');
        $stmt->execute(array(':id'=>$id,':fecha'=>$dateBegin[$y]));
          $row = $stmt->fetch();

          //print_r($row);
          //echo "<br>";
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

            if($row['Entrada'] !=null)
            {
                $entradaColumna = $row['Entrada'];
            }
            else
            {
              $entradaColumna = "<i class='fas fa-ban'></i> No registrado";
            }


            if($row['Salida'] != null){
                $salidaColumna = $row['Salida'];
            }else{
              $salidaColumna = "<i class='fas fa-ban'></i> No registrado";
            }

            array_push($entradaTurno, $entradaColumna);
            array_push($salidaTurno, $salidaColumna);

            array_push($tiempoDeuda,$row['Horas_Trabajadas']);
            array_push($horasAutorizadas, $row['Horas_Autorizadas']);
            array_push($encargado, $row['Nombre_Empleado']);

            if($nominaExiste == 0){
                $agregarHoras = "<div class='dropdown'><button class='btn btn-outline-primary dropdown-toggle btnJustificar' type='button' id='dropdownMenu2' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'><i class='fas fa-hourglass-end'></i> Agregar horas</button><div class='dropdown-menu' aria-labelledby='dropdownMenu2'><button class='dropdown-item' type='button' onclick='agregarHora(".$row['PKHoraExtra'].",1);'' value='".$row['PKHoraExtra']."'>+1 Hora</button><button class='dropdown-item' type='button' onclick='agregarHora(".$row['PKHoraExtra'].",2);'' value='".$row['PKHoraExtra']."'>-1 Hora</button></div></div>";
            }
            else{
                $agregarHoras = "";
            }
            array_push($funciones, $agregarHoras);


          }

      }

    $a = count($idEmpl);
    for($y = 0;$y<count($idEmpl);$y++){
      $table.='{"Dia":"'.$diaSem[$y].'","Fecha":"'.$fechaChec[$y].'","Entrada":"'.$entradaTurno[$y].'","Salida":"'.$salidaTurno[$y].'","Horas trabajadas":"'.$tiempoDeuda[$y].'","Horas autorizadas":"'.$horasAutorizadas[$y].'","Responsable":"'.$encargado[$y].'","Acciones":"'.$funciones[$y].'"},';
    }

    $table = substr($table,0,strlen($table)-1);
    echo '{"data":['.$table.']}';
?>
