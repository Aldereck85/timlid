<?php
  require_once('../../include/db-conn.php');
  $id = 0;
  $semana = 0;

  if(isset($_GET['id'])){
    $id =  $_GET['id'];
    $semana = $_GET['semana'];
    $idTurno = $_GET['turno'];
    $idTurnoVar = $_GET['turno'];
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
    $justificarBoton = array();
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
    $idTurnoVar= $row['PKTurno'];
    $turno = $row['Turno'];
    ///////////Calculo de nomina nocturno///////////////////////////////
    
    if($idTurno != 1){
      $entradaOficial = new DateTime($row['Entrada']);
      $salidaOficial = new DateTime($row['Salida']);
    }else{
      $fechaAhora= time();
      $fechaMes = date("Y-m-d",$fechaAhora);

      $fechaMeses = strtotime($fechaMes);
      $fechaSalida = date( "Y-m-d", strtotime("+1 day",$fechaMeses) );

      $entradaOficial = new DateTime($row['Entrada']." ".$fechaMes);
      $salidaOficial = new DateTime($row['Salida']." ".$fechaSalida);
    }
    ///////////////////////////////////////////////////////////
    $interval = $entradaOficial->diff($salidaOficial);
    
    $horasNoTrabajadas = $interval->format('%H');
    $minutosNoTrabajados = $interval->format('%I');

    $diasTrab = $row['Dias_de_trabajo'];

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
print_r($dateBegin);
    $y = 0;

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

          
          $registroComida =$row['Salida_Comida'];
          $registroRegresoComida=$row['Regreso_Comida'];

          //Calculo de entrada y salida del empleado turno nocturno
          if($idTurno != 1){
            $entradaEmpleado = new DateTime($row['Entrada']);
            $salidaEmpleado = new DateTime($row['Salida']);
          }else{
            $fechaAhora= time();
            $fechaMes = date("Y-m-d",$fechaAhora);

            $fechaMeses = strtotime($fechaMes);
            $fechaSalida = date( "Y-m-d", strtotime("+1 day",$fechaMeses) );

            $entradaEmpleado = new DateTime($row['Entrada']." ".$fechaMes);
            $salidaEmpleado = new DateTime($row['Salida']." ".$fechaSalida);
          }

          if($row['Entrada'] !=null)
          {
            if($entradaEmpleado <= $entradaOficial){
              $entradaColumna = "<label class='hrAprobada'>".$row['Entrada']."</label>";
            }else{

              $tiempoDeber = $entradaEmpleado->diff($entradaOficial);
              $horasDeber = $horasDeber + $tiempoDeber->h;
              $minutosDeber = $horasDeber + $tiempoDeber->i;
              $entradaColumna = "<label class='hrReprobada'>".$row['Entrada']."</label>";
            }
            $sumEstatus = $sumEstatus +1;
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
              $horasDeber = $horasDeber + $tiempoDeber->h;
              $minutosDeber = $horasDeber + $tiempoDeber->i;
              $salidaColumna = "<label class='hrReprobada'>".$row['Salida']."</label>";
            }
            $sumEstatus = $sumEstatus +1;
          }else{
            $salidaColumna = "<i class='fas fa-ban'></i> No registrado";
          }

          if($row['Salida_Comida'] != null){
            $sumEstatus = $sumEstatus +1;
          }else{
            $registroComida = "<i class='fas fa-ban'></i> No registrado";
          }

          if($row['Regreso_Comida'] != null)
          {
            $sumEstatus = $sumEstatus +1;
          }else{
            $registroRegresoComida = "<i class='fas fa-ban'></i> No registrado";
          }

          if($diasTrab == 5)
          {
            if($diaSemEn == "Sat")
            {
              $sumEstatus = 11;
            }
          }

          if($diaSemEn == "Sun")
          {
            $sumEstatus = 11;
          }

          if($row['Estatus'] == 9){
            $sumEstatus = 9;
          }

          $stmt = $conn->prepare('UPDATE gh_checador set Estatus= :estatus WHERE FKUsuario = :id AND Fecha =:fecha');
          $stmt->bindValue(':fecha',$dateBegin[$y]);
          $stmt->bindValue(':estatus',$sumEstatus);
          $stmt->bindValue(':id', $id, PDO::PARAM_INT);
          $stmt->execute();

          if($row['Salida_Comida'] != null && $row['Regreso_Comida'] != null)
          {
            if($idTurno != 1){
              $fecha1 = new DateTime($row['Salida_Comida']);
              $fecha2 = new DateTime($row['Regreso_Comida']);
            }else{
              $fechaAhora= time();
              $fechaMes = date("Y-m-d",$fechaAhora);

              $fechaMeses = strtotime($fechaMes);
              $fechaSalida = date( "Y-m-d", strtotime("+1 day",$fechaMeses) );

              $fecha1 = new DateTime($row['Salida_Comida']." ".$fechaMes);
              $fecha2 = new DateTime($row['Regreso_Comida']." ".$fechaSalida);
            }

            $intervalo = $fecha1->diff($fecha2);
            $horas = sprintf("%02d", $intervalo->h);
            $minutos = sprintf("%02d", $intervalo->i);
            $segundos = sprintf("%02d", $intervalo->s);
            $tiempo = $horas.":".$minutos.":".$segundos;
            $tiempoComidaUtilizado = new DateTime($horas.":".$minutos.":".$segundos);

            if($tiempoComidaUtilizado > $tiempoComidaOficial){
              $tiempoComidaExtra = $tiempoComidaUtilizado->diff($tiempoComidaOficial);
              $horasDeber = $horasDeber + $tiempoComidaExtra->h;
              $minutosDeber = $minutosDeber + $tiempoComidaExtra->i;
            }

          }else{
            $tiempoComida = "Incanculable";
            $tiempo = "Incalculable";
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
          array_push($tiempoTotalComida,$tiempo);

          if($horasDeber!=0 || $minutosDeber !=0)
          {
            $sumEstatus = 5;
          }

          if($row['Estatus'] == 9){
            $sumEstatus = 9;
          }

          if($row['Estatus'] == 10){
            $sumEstatus = 10;
          }
          if($row['Estatus'] == 12){
            $sumEstatus = 12;
          }
          $estatusEscrito;

          if($sumEstatus == 0)
          {
            $funciones= "<button type='button' class='btn btn-outline-info btnJustificar' onclick='justificarFalta(".$row['PKChecada'].");' value='".$row['PKChecada']."'><i class='fas fa-balance-scale'></i> Justificar</button>";
            array_push($justificarBoton, $funciones);
            $estatusEscrito ="<label style='color:#e74a3b;'><i class='fas fa-ban'></i> Falta</label>";
            $horasDeber = $horasNoTrabajadas;
            $minutosDeber = $minutosNoTrabajados;
          }
          else if($sumEstatus < 4)
          {
            $funciones= "<button type='button' class='btn btn-outline-info btnJustificar' onclick='justificarFalta(".$row['PKChecada'].");' value='".$row['PKChecada']."'><i class='fas fa-balance-scale'></i> Justificar</button>";
            array_push($justificarBoton, $funciones);
            $estatusEscrito = "<label style='color:#f6c23e;'><i class='fas fa-star-half'></i> Incompleto</label>";
          }
          else if($sumEstatus == 4)
          {
            $funciones= "";
            array_push($justificarBoton, $funciones);
            $estatusEscrito = "<label style='color:#1cc88a;'><i class='fas fa-star'></i> Excelente</label>";
          }
          else if($sumEstatus == 5)
          {
            $funciones ="<div class='dropdown'><button class='btn btn-outline-primary dropdown-toggle' type='button' id='dropdownMenu2' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'><i class='fas fa-balance-scale'></i> Justificar</button><div class='dropdown-menu' aria-labelledby='dropdownMenu2'><button class='dropdown-item' type='button' onclick='justificarFalta(".$row['PKChecada'].",0);'' value='".$row['PKChecada']."'>Sin sueldo</button><button class='dropdown-item' type='button' onclick='justificarFalta(".$row['PKChecada'].",1);'' value='".$row['PKChecada']."'>Con sueldo</button></div></div>";
            //$funciones= "<button type='button' class='btn btn-outline-info btnJustificar' onclick='justificarFalta(".$row['PKChecada'].");'' value='".$row['PKChecada']."'><i class='fas fa-balance-scale'></i> Justificar</button>";
            array_push($justificarBoton, $funciones);
            $estatusEscrito = "<label style='color:#ff7b00;'><i class='fas fa-balance-scale-left'></i> Tiempo injustificado</label>";
          }
          else if($sumEstatus == 9)
          {
            $funciones= "";
            array_push($justificarBoton, $funciones);
            $estatusEscrito = "<label style='color:#36b9cc;'><i class='fas fa-balance-scale'></i> Justificado s/sueldo</label>";
          }
          else if($sumEstatus == 10)
          {
            $funciones= "";
            array_push($justificarBoton, $funciones);
            $estatusEscrito = "<label style='color:#366ecc;'><i class='fas fa-balance-scale'></i> Justificado c/sueldo</label>";
          }
          

          //sprintf("%02d", $intervalo->h);
          if($minutosDeber > 60)
          {
            $horasDeber = $horasDeber + floor($minutosDeber/60);
            $minutosDeber = $minutosDeber-60;
          }

          /*
          if($sumEstatus == 0){
            $tiempo = sprintf("%02d",$horasDeber).":".sprintf("%02d",$minutosDeber).":".sprintf("%02d",$segundosDeber);
          }else if($sumEstatus == 0){

          }*/
          //////////////
          if($sumEstatus !=10){
            $tiempo = sprintf("%02d",$horasDeber).":".sprintf("%02d",$minutosDeber).":".sprintf("%02d",$segundosDeber);
          }else{
            $tiempo ="00:00:00";
          }

          if($sumEstatus == 12){
            $tiempo ="00:00:00";
          }

          $tiempoSinTrabajo = new DateTime($tiempo);

          if($row['Entrada'] == null || $row['Salida'] == null){
            if($sumEstatus != 11 && $sumEstatus != 4 && $sumEstatus != 10 && $sumEstatus != 12){
              $intervaloTiempo = $entradaOficial->diff($salidaOficial);
              $horasTrabajadasTiempo = $intervaloTiempo->format('%H');
              $minutosTrabajadosTiempo = $intervaloTiempo->format('%I');
              $segundosTrabajadosTiempo = $intervaloTiempo->format('%S');
              $tiempo = sprintf("%02d",$horasTrabajadasTiempo).":".sprintf("%02d",$minutosTrabajadosTiempo).":".sprintf("%02d",$segundosTrabajadosTiempo);
            }
          }

          $stmt = $conn->prepare('UPDATE gh_checador set Deuda_Horas= :tiempo,Estatus= :estatus WHERE FKUsuario = :id AND Fecha =:fecha');
          $stmt->bindValue(':fecha',$dateBegin[$y]);
          $stmt->bindValue(':tiempo',$tiempo);
          $stmt->bindValue(':estatus',$sumEstatus);
          $stmt->bindValue(':id', $id, PDO::PARAM_INT);
          $stmt->execute();
          /////Actualizar el tiempo

          array_push($tiempoDeuda,$tiempo);
          array_push($estatusChec, $estatusEscrito);
          $horasDeber = 0;
          $minutosDeber = 0;
          $tiempo = 0;

        }else{
          /////Date does not exists//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
          $usuario = $id;
          $estatus = 0;
          array_push($idEmpl, $id);
          $diaSemEn = date('D',strtotime($dateBegin[$y]));

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
          $noRegistrado = "<i class='fas fa-ban'></i> No registrado";
          $formatoFecha = date('d/m/Y',strtotime($dateBegin[$y]));
          array_push($fechaChec, $formatoFecha);
          array_push($entradaTurno, $noRegistrado);
          array_push($salidaComida, $noRegistrado);
          array_push($regresoComida, $noRegistrado);
          array_push($salidaTurno, $noRegistrado);
          array_push($tiempoTotalComida, "Incalculable");

          ///////Diferencia de tiempos///////////////////
          if($diasTrab == 5){

              if($diaSemEn != "Sun" && $diaSemEn != "Sat"){
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
              $tiempoaDeber = $horasNoTrabajadas.":".$minutosNoTrabajados.":00";
            }else{
              $tiempoaDeber = "00:00:00";
              $estatus = 11;
            }

          }

          if($diasTrab == 6){

              if($diaSemEn != "Sun"){
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
              $tiempoaDeber = $horasNoTrabajadas.":".$minutosNoTrabajados.":00";
            }else{
              $tiempoaDeber = "00:00:00";
              $estatus = 11;
            }
            
          }
          //echo $tiempoaDeber."<br>";
       
          
          $stmt = $conn->prepare('INSERT INTO gh_checador (FKUsuario,Fecha,Deuda_Horas,Estatus)VALUES(:usuario,:fecha,:deuda,:estatus)');
          $stmt->bindValue(':usuario',$usuario);
          $stmt->bindValue(':fecha',$dateBegin[$y]);
          $stmt->bindValue(':deuda',$tiempoaDeber);
          $stmt->bindValue(':estatus',$estatus);
          $stmt->execute();

          /*$estatusEscrito;
          if($sumEstatus == 0)
          {
            $funciones= "<button type='button' class='btn btn-outline-info btnJustificar' onclick='justificarFalta(".$row['PKChecada'].");' value='".$row['PKChecada']."'><i class='fas fa-balance-scale'></i> Justificar</button>";
            array_push($justificarBoton, $funciones);
            $estatusEscrito ="<label style='color:#e74a3b;'><i class='fas fa-ban'></i> Falta</label>";
          }
          array_push($estatusChec, $estatusEscrito);*/
          ////////////////////////////////
        }         
    }


    //DOBLE TURNO   
    for($y = 0;$y<7;$y++){
  
      $sumEstatus = 0;
      $stmt = $conn->prepare('SELECT t.PKTurno, t.Turno, dt.PKChecadaDoble, dt.Entrada, dt.Salida_Comida, dt.Regreso_Comida, dt.Salida, dt.FKEmpleado, dt.FechaAutorizada, dt.Estatus, t.Entrada as EntradaOficial, t.Salida as SalidaOficial  FROM doblar_turno as dt INNER JOIN turnos as t ON dt.FKTurno = t.PKTurno WHERE dt.FKEmpleado = :id AND dt.FechaAutorizada = :fecha ORDER BY dt.FechaAutorizada ASC');
        $stmt->execute(array(':id'=>$id,':fecha'=>$dateBegin[$y]));
        $row = $stmt->fetch();
        if($stmt->rowCount() > 0){

          $idTurno= $row['PKTurno'];
          $turno = $row['Turno'];
          ///////////Calculo de nomina nocturno///////////////////////////////
          
          if($idTurno != 1){
            $entradaOficial = new DateTime($row['EntradaOficial']);
            $salidaOficial = new DateTime($row['SalidaOficial']);
          }else{
            $fechaAhora= time();
            $fechaMes = date("Y-m-d",$fechaAhora);

            $fechaMeses = strtotime($fechaMes);
            $fechaSalida = date( "Y-m-d", strtotime("+1 day",$fechaMeses) );

            $entradaOficial = new DateTime($row['EntradaOficial']." ".$fechaMes);
            $salidaOficial = new DateTime($row['SalidaOficial']." ".$fechaSalida);
          }

          $interval = $entradaOficial->diff($salidaOficial);

          $horasNoTrabajadas = $interval->format('%H');
          $minutosNoTrabajados = $interval->format('%I');
          
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

          if($idTurno != 1){
            $entradaEmpleado = new DateTime($row['Entrada']);
            $salidaEmpleado = new DateTime($row['Salida']);
          }else{
            $fechaAhora= time();
            $fechaMes = date("Y-m-d",$fechaAhora);

            $fechaMeses = strtotime($fechaMes);
            $fechaSalida = date( "Y-m-d", strtotime("+1 day",$fechaMeses) );

            $entradaEmpleado = new DateTime($row['Entrada']." ".$fechaMes);
            $salidaEmpleado = new DateTime($row['Salida']." ".$fechaSalida);
          }

          
          $registroComida =$row['Salida_Comida'];
          $registroRegresoComida=$row['Regreso_Comida'];

          if($row['Entrada'] !=null)
          {
            if($entradaEmpleado <= $entradaOficial){
              $entradaColumna = "<label class='hrAprobada'>".$row['Entrada']."</label>";
            }else{

              $tiempoDeber = $entradaEmpleado->diff($entradaOficial);
              $horasDeber = $horasDeber + $tiempoDeber->h;
              $minutosDeber = $horasDeber + $tiempoDeber->i;
              $entradaColumna = "<label class='hrReprobada'>".$row['Entrada']."</label>";
            }
            $sumEstatus = $sumEstatus +1;
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
              $horasDeber = $horasDeber + $tiempoDeber->h;
              $minutosDeber = $horasDeber + $tiempoDeber->i;
              $salidaColumna = "<label class='hrReprobada'>".$row['Salida']."</label>";
            }
            $sumEstatus = $sumEstatus +1;
          }else{
            $salidaColumna = "<i class='fas fa-ban'></i> No registrado";
          }

          if($row['Salida_Comida'] != null){
            $sumEstatus = $sumEstatus +1;
          }else{
            $registroComida = "<i class='fas fa-ban'></i> No registrado";
          }

          if($row['Regreso_Comida'] != null)
          {
            $sumEstatus = $sumEstatus +1;
          }else{
            $registroRegresoComida = "<i class='fas fa-ban'></i> No registrado";
          }

          if($diasTrab == 5)
          {
            if($diaSemEn == "Sat")
            {
              $sumEstatus = 11;
            }
          }

          if($diaSemEn == "Sun")
          {
            $sumEstatus = 11;
          }

          if($row['Estatus'] == 9){
            $sumEstatus = 9;
          }

          $stmt = $conn->prepare('UPDATE doblar_turno set Estatus= :estatus WHERE FKEmpleado = :id AND FechaAutorizada =:fecha');
          $stmt->bindValue(':fecha',$dateBegin[$y]);
          $stmt->bindValue(':estatus',$sumEstatus);
          $stmt->bindValue(':id', $id, PDO::PARAM_INT);
          $stmt->execute();

          if($row['Salida_Comida'] != null && $row['Regreso_Comida'] != null)
          {
            if($idTurno != 1){
              $fecha1 = new DateTime($row['Salida_Comida']);
              $fecha2 = new DateTime($row['Regreso_Comida']);
            }else{
              $fechaAhora= time();
              $fechaMes = date("Y-m-d",$fechaAhora);

              $fechaMeses = strtotime($fechaMes);
              $fechaSalida = date( "Y-m-d", strtotime("+1 day",$fechaMeses) );

              $fecha1 = new DateTime($row['Salida_Comida']." ".$fechaMes);
              $fecha2 = new DateTime($row['Regreso_Comida']." ".$fechaSalida);
            }

            $intervalo = $fecha1->diff($fecha2);
            $horas = sprintf("%02d", $intervalo->h);
            $minutos = sprintf("%02d", $intervalo->i);
            $segundos = sprintf("%02d", $intervalo->s);
            $tiempo = $horas.":".$minutos.":".$segundos;
            $tiempoComidaUtilizado = new DateTime($horas.":".$minutos.":".$segundos);

            if($tiempoComidaUtilizado > $tiempoComidaOficial){
              $tiempoComidaExtra = $tiempoComidaUtilizado->diff($tiempoComidaOficial);
              $horasDeber = $horasDeber + $tiempoComidaExtra->h;
              $minutosDeber = $minutosDeber + $tiempoComidaExtra->i;
            }

          }else{
            $tiempoComida = "Incanculable";
            $tiempo = "Incalculable";
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
          array_push($tiempoTotalComida,$tiempo);

          if($horasDeber!=0 || $minutosDeber !=0)
          {
            $sumEstatus = 5;
          }

          if($row['Estatus'] == 9){
            $sumEstatus = 9;
          }

          if($row['Estatus'] == 10){
            $sumEstatus = 10;
          }
          if($row['Estatus'] == 12){
            $sumEstatus = 12;
          }
          $estatusEscrito;

          if($sumEstatus == 0)
          {
            $funciones= "<button type='button' class='btn btn-outline-info btnJustificar' onclick='justificarFalta(".$row['PKChecadaDoble'].");' value='".$row['PKChecadaDoble']."'><i class='fas fa-balance-scale'></i> Justificar</button>";
            array_push($justificarBoton, $funciones);
            $estatusEscrito ="<label style='color:#e74a3b;'><i class='fas fa-ban'></i> Falta</label>";
            $horasDeber = $horasNoTrabajadas;
            $minutosDeber = $minutosNoTrabajados;
          }
          else if($sumEstatus < 4)
          {
            $funciones= "<button type='button' class='btn btn-outline-info btnJustificar' onclick='justificarFalta(".$row['PKChecadaDoble'].");' value='".$row['PKChecadaDoble']."'><i class='fas fa-balance-scale'></i> Justificar</button>";
            array_push($justificarBoton, $funciones);
            $estatusEscrito = "<label style='color:#f6c23e;'><i class='fas fa-star-half'></i> Incompleto</label>";
          }
          else if($sumEstatus == 4)
          {
            $funciones= "";
            array_push($justificarBoton, $funciones);
            $estatusEscrito = "<label style='color:#1cc88a;'><i class='fas fa-star'></i> Excelente</label>";
          }
          else if($sumEstatus == 5)
          {
            $funciones ="<div class='dropdown'><button class='btn btn-outline-primary dropdown-toggle' type='button' id='dropdownMenu2' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'><i class='fas fa-balance-scale'></i> Justificar</button><div class='dropdown-menu' aria-labelledby='dropdownMenu2'><button class='dropdown-item' type='button' onclick='justificarFalta(".$row['PKChecadaDoble'].",0);'' value='".$row['PKChecadaDoble']."'>Sin sueldo</button><button class='dropdown-item' type='button' onclick='justificarFalta(".$row['PKChecadaDoble'].",1);'' value='".$row['PKChecadaDoble']."'>Con sueldo</button></div></div>";
            array_push($justificarBoton, $funciones);
            $estatusEscrito = "<label style='color:#ff7b00;'><i class='fas fa-balance-scale-left'></i> Tiempo injustificado</label>";
          }
          else if($sumEstatus == 9)
          {
            $funciones= "";
            array_push($justificarBoton, $funciones);
            $estatusEscrito = "<label style='color:#36b9cc;'><i class='fas fa-balance-scale'></i> Justificado s/sueldo</label>";
          }
          else if($sumEstatus == 10)
          {
            $funciones= "";
            array_push($justificarBoton, $funciones);
            $estatusEscrito = "<label style='color:#366ecc;'><i class='fas fa-balance-scale'></i> Justificado c/sueldo</label>";
          }
          
          if($minutosDeber > 60)
          {
            $horasDeber = $horasDeber + floor($minutosDeber/60);
            $minutosDeber = $minutosDeber-60;
          }

          if($sumEstatus !=10){
            $tiempo = sprintf("%02d",$horasDeber).":".sprintf("%02d",$minutosDeber).":".sprintf("%02d",$segundosDeber);
          }else{
            $tiempo ="00:00:00";
          }
          if($sumEstatus == 12){
            $tiempo ="00:00:00";
          }
         // $my_dt = DateTime::createFromFormat('m-d-Y H:i:s', $token_created_at);
          $tiempoSinTrabajo = new DateTime($tiempo);

          if($row['Entrada'] == null || $row['Salida'] == null){
            if($sumEstatus != 11 && $sumEstatus != 4 && $sumEstatus != 10 && $sumEstatus != 12){
              $intervaloTiempo = $entradaOficial->diff($salidaOficial);
              $horasTrabajadasTiempo = $intervaloTiempo->format('%H');
              $minutosTrabajadosTiempo = $intervaloTiempo->format('%I');
              $segundosTrabajadosTiempo = $intervaloTiempo->format('%S');
              $tiempo = sprintf("%02d",$horasTrabajadasTiempo).":".sprintf("%02d",$minutosTrabajadosTiempo).":".sprintf("%02d",$segundosTrabajadosTiempo);
            }
          }
          
          $stmt = $conn->prepare('UPDATE doblar_turno set Deuda_Horas= :tiempo,Estatus= :estatus WHERE FKEmpleado = :id AND FechaAutorizada =:fecha');
          $stmt->bindValue(':fecha',$dateBegin[$y]);
          $stmt->bindValue(':tiempo',$tiempo);
          $stmt->bindValue(':estatus',$sumEstatus);
          $stmt->bindValue(':id', $id, PDO::PARAM_INT);
          $stmt->execute();
          /////Actualizar el tiempo

          array_push($tiempoDeuda,$tiempo);
          array_push($estatusChec, $estatusEscrito);
          $horasDeber = 0;
          $minutosDeber = 0;
          $tiempo = 0;
        }     
      }

      //Horas extras
      for($y = 0;$y<7;$y++){

        $stmt = $conn->prepare('SELECT PKHoraExtra, Horas_Autorizadas, FechaAutorizada, Entrada, Salida FROM horas_extras WHERE FKEmpleado = :id AND FechaAutorizada = :fecha');
        $stmt->execute(array(':id'=>$id,':fecha'=>$dateBegin[$y]));
        $row = $stmt->fetch();

        if($stmt->rowCount() > 0){
          $entradaOficial = new DateTime($row['Entrada']);
          $salidaOficial = new DateTime($row['Salida']);

          if($salidaOficial < $entradaOficial){
            $fechaAhora= time();
            $fechaMes = date("Y-m-d",$fechaAhora);

            $fechaMeses = strtotime($fechaMes);
            $fechaSalida = date( "Y-m-d", strtotime("+1 day",$fechaMeses) );

            $entradaOficial = new DateTime($row['Entrada']." ".$fechaMes);
            $salidaOficial = new DateTime($row['Salida']." ".$fechaSalida);
          }

          $intervalo = $entradaOficial->diff($salidaOficial);
          $horastrabajadas = sprintf("%02d",$intervalo->h).":".sprintf("%02d",$intervalo->i).":".sprintf("%02d",$intervalo->s);
          
          $stmt = $conn->prepare('UPDATE horas_extras SET Horas_Trabajadas = :horastrabajadas WHERE FKEmpleado = :id AND FechaAutorizada = :fecha');
          $stmt->execute(array(':horastrabajadas'=> $horastrabajadas,':id'=>$id,':fecha'=>$dateBegin[$y]));
          $row = $stmt->fetch();
        }
      }
    header("location:nomina_directa.php?id=".$id."&semana=".$semana."&turno=".$idTurnoVar);
?>
