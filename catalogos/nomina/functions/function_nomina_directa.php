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
    $fechaSinFormato = array();
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
    $tiempoDeber;
    $horasDeber = 0;
    $minutosDeber = 0;
    $segundosDeber = 0;
    $tiempoComidaOficial = new DateTime('00:32:00');
    $funciones;
    

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
      $stmt = $conn->prepare('SELECT * FROM gh_checador WHERE FKUsuario = :id AND Fecha = :fecha ORDER BY Fecha ASC');
      $stmt->execute(array(':id'=>$id,':fecha'=>$dateBegin[$y]));
        $row = $stmt->fetch();
        if($stmt->rowCount() > 0){

          array_push($idEmpl, $row['FKUsuario']);
          $diaSemEn = date('D',strtotime($row['Fecha']));

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
          $formatoFecha = date('d/m/Y',strtotime($row['Fecha']));
          array_push($fechaChec, $formatoFecha);
          array_push($fechaSinFormato, $row['Fecha']);

          $entradaEmpleado = new DateTime($row['Entrada']);
          $entradaEmpleadoFormat = $entradaEmpleado;
          $entradaEmpleadoFormat->setTime ( $entradaEmpleadoFormat->format("H"), $entradaEmpleadoFormat->format("i"), "00" );
          
          $salidaEmpleado = new DateTime($row['Salida']);
          $registroComida =$row['Salida_Comida'];
          $registroRegresoComida=$row['Regreso_Comida'];

          if($row['Entrada'] !=null)
          {
            if($entradaEmpleadoFormat <= $entradaOficial){
              $entradaColumna = "<label class='hrAprobada'>".$row['Entrada']."</label>";
            }else{

              $tiempoDeber = $entradaEmpleado->diff($entradaOficial);
              $entradaColumna = "<label class='hrReprobada'>".$row['Entrada']."</label>";
            }
          }
          else
          {
            $entradaColumna = "<i class='fas fa-ban'></i> No registrado";
          }


          if($row['Salida'] != null){
            if($salidaEmpleado >= $salidaOficial){
              $salidaColumna = "<label class='hrAprobada'>".$row['Salida']."</label>";
            }else{
              $tiempoDeber = $salidaEmpleado->diff($salidaOficial);
              $salidaColumna = "<label class='hrReprobada'>".$row['Salida']."</label>";
            }
          }else{
            $salidaColumna = "<i class='fas fa-ban'></i> No registrado";
          }

          if($row['Salida_Comida'] == null){
            $registroComida = "<i class='fas fa-ban'></i> No registrado";
          }

          if($row['Regreso_Comida'] == null)
          {
            $registroRegresoComida = "<i class='fas fa-ban'></i> No registrado";
          }

          $estatusDefinitivo;
          if($row['Estatus'] == 4)
          {
            $estatusDefinitivo = "Excelente";
          }


          array_push($entradaTurno, $entradaColumna);
          array_push($salidaComida,$registroComida);
          array_push($salidaTurno, $salidaColumna);
          array_push($regresoComida, $registroRegresoComida);

          $estatus = $row['Estatus'];

          if($estatus == 0)
          {
            if($nominaExiste == 0){
              $funciones ="<div class='dropdown btnJustificar'><button class='btn btn-primary dropdown-toggle' type='button' id='dropdownMenu2' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'><i class='fas fa-balance-scale'></i> Justificar</button><div class='dropdown-menu' aria-labelledby='dropdownMenu2'><button class='dropdown-item' type='button' onclick='justificarFalta(".$row['PKChecada'].",0);'' value='".$row['PKChecada']."'>Sin sueldo</button><button class='dropdown-item' type='button' onclick='justificarFalta(".$row['PKChecada'].",1);'' value='".$row['PKChecada']."'>Con sueldo</button><button class='dropdown-item' type='button' onclick='justificarFalta(".$row['PKChecada'].",2);'' value='".$row['PKChecada']."'>Vacaciones</button></div></div>";
            }else{
              $funciones= "";
            }
            array_push($justificarBoton, $funciones);
            $estatusEscrito ="<label style='color:#e74a3b;'><i class='fas fa-ban'></i> Falta</label>";
          }
          else if($estatus < 4)
          {
            if($nominaExiste == 0){
              $funciones= "<button type='button' class='btn btn-outline-info btnJustificar' onclick='justificarFalta(".$row['PKChecada'].",1);' value='".$row['PKChecada']."'><i class='fas fa-balance-scale'></i> Justificar</button>";
            }else{
              $funciones= "";
            }
            array_push($justificarBoton, $funciones);
            $estatusEscrito = "<label style='color:#f6c23e;'><i class='fas fa-star-half'></i> Incompleto</label>";
          }
          else if($estatus == 4)
          {
            $funciones= "";
            array_push($justificarBoton, $funciones);
            $estatusEscrito = "<label style='color:#1cc88a;'><i class='fas fa-star'></i> Excelente</label>";
          }
          else if($estatus == 5)
          {
            if($nominaExiste == 0){
              $funciones ="<div class='dropdown'><button class='btn btn-outline-primary dropdown-toggle btnJustificar' type='button' id='dropdownMenu2' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'><i class='fas fa-balance-scale'></i> Justificar</button><div class='dropdown-menu' aria-labelledby='dropdownMenu2'><button class='dropdown-item' type='button' onclick='justificarFalta(".$row['PKChecada'].",0);'' value='".$row['PKChecada']."'>Sin sueldo</button><button class='dropdown-item' type='button' onclick='justificarFalta(".$row['PKChecada'].",1);'' value='".$row['PKChecada']."'>Con sueldo</button></div></div>";
            }else{
              $funciones= "";
            }
            array_push($justificarBoton, $funciones);
            $estatusEscrito = "<label style='color:#ff7b00;'><i class='fas fa-balance-scale-left'></i> Tiempo injustificado</label>";
          }
          else if($estatus == 9)
          {
            $funciones= "";
            array_push($justificarBoton, $funciones);
            $estatusEscrito = "<label style='color:#36b9cc;'><i class='fas fa-balance-scale'></i> Justificado s/sueldo</label>";
          }
          else if($estatus == 10)
          {
            $funciones= "";
            array_push($justificarBoton, $funciones);
            $estatusEscrito = "<label style='color:#366ecc;'><i class='fas fa-balance-scale'></i> Justificado c/sueldo</label>";
          }else if($estatus == 11)
          {
            $funciones= "";
            array_push($justificarBoton, $funciones);
            $estatusEscrito = "<label style='color:#366ecc;'><i class='fas fa-calendar-check'></i> Dia libre</label>";
          }else if($estatus == 12)
          {
            $funciones= "";
            array_push($justificarBoton, $funciones);
            $estatusEscrito = "<label style='color:#832081;'><i class='fas fa-umbrella-beach'></i> Dia de vacaciones</label>";
          }
          

          $tiempo = $row['Deuda_Horas'];          
          array_push($tiempoDeuda,$tiempo);
          array_push($estatusChec, $estatusEscrito);

        }

    }


    $a = count($idEmpl);
    for($y = 0;$y<count($idEmpl);$y++){
      //$funciones= "<button type='button' class='btn btn-outline-primary'><i class='fas fa-balance-scale'></i> Justificar</button>";
      $table.='{"Dia":"'.$diaSem[$y].'","Fecha":"'.$fechaChec[$y].'","Fecha Sin Formato":"'.$fechaSinFormato[$y].'","Entrada":"'.$entradaTurno[$y].'","Salida a comer":"'.$salidaComida[$y].'","Regreso de Comer":"'.$regresoComida[$y].'","Salida":"'.$salidaTurno[$y].'","Tiempo a deber":"'.$tiempoDeuda[$y].'","Estatus":"'.$estatusChec[$y].'","Acciones":"'.$justificarBoton[$y].'"},';
      }
      $table = substr($table,0,strlen($table)-1);
      echo '{"data":['.$table.']}';
?>