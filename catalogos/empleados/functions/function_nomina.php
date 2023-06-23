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
    $justificarBoton = array();///////////////////////////////////////
    $tiempoDeber;
    $horasDeber = 0;
    $minutosDeber = 0;
    $segundosDeber = 0;
    $tiempoComidaOficial = new DateTime('00:32:00');
    $funciones;

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

          $entradaEmpleado = new DateTime($row['Entrada']);
          $salidaEmpleado = new DateTime($row['Salida']);
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
          if($diaSemEn == "Sun")
          {
            $sumEstatus = 4;
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
            $fecha1 = new DateTime($row['Salida_Comida']);
            $fecha2 = new DateTime($row['Regreso_Comida']);
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
          $estatusEscrito;
          if($sumEstatus == 0)
          {
            $funciones= "<button type='button' class='btn btn-outline-info btnJustificar' onclick='justificarFalta(".$row['PKChecada'].");' value='".$row['PKChecada']."'><i class='fas fa-balance-scale'></i> Justificar</button>";
            array_push($justificarBoton, $funciones);
            $estatusEscrito ="<label style='color:#e74a3b;'><i class='fas fa-ban'></i> Falta</label>";
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
            $funciones= "<button type='button' class='btn btn-outline-info btnJustificar' onclick='justificarFalta(".$row['PKChecada'].");'' value='".$row['PKChecada']."'><i class='fas fa-balance-scale'></i> Justificar</button>";
            array_push($justificarBoton, $funciones);
            $estatusEscrito = "<label style='color:#ff7b00;'><i class='fas fa-balance-scale-left'></i> Tiempo injustificado</label>";
          }
          else if($sumEstatus == 9)
          {
            $funciones= "";
            array_push($justificarBoton, $funciones);
            $estatusEscrito = "<label style='color:#36b9cc;'><i class='fas fa-balance-scale'></i> Justificado</label>";
          }

          //sprintf("%02d", $intervalo->h);
          $tiempo = sprintf("%02d",$horasDeber).":".sprintf("%02d",$minutosDeber).":".sprintf("%02d",$segundosDeber);
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

          $stmt = $conn->prepare('INSERT INTO gh_checador (FKUsuario,Fecha,Estatus)VALUES(:usuario,:fecha,:estatus)');
          $stmt->bindValue(':usuario',$usuario);
          $stmt->bindValue(':fecha',$dateBegin[$y]);
          $stmt->bindValue(':estatus',$estatus);
          $stmt->execute();

          $estatusEscrito;
          if($sumEstatus == 0)
          {
            $funciones= "<button type='button' class='btn btn-outline-info btnJustificar' onclick='justificarFalta(".$row['PKChecada'].");' value='".$row['PKChecada']."'><i class='fas fa-balance-scale'></i> Justificar</button>";
            array_push($justificarBoton, $funciones);
            $estatusEscrito ="<label style='color:#e74a3b;'><i class='fas fa-ban'></i> Falta</label>";
          }
          array_push($estatusChec, $estatusEscrito);
          ////////////////////////////////
        }

    }


    $a = count($idEmpl);
    for($y = 0;$y<count($idEmpl);$y++){
      //$funciones= "<button type='button' class='btn btn-outline-primary'><i class='fas fa-balance-scale'></i> Justificar</button>";
      $table.='{"Dia":"'.$diaSem[$y].'","Fecha":"'.$fechaChec[$y].'","Entrada":"'.$entradaTurno[$y].'","Salida a comer":"'.$salidaComida[$y].'","Regreso de Comer":"'.$regresoComida[$y].'","Salida":"'.$salidaTurno[$y].'","Tiempo de comida":"'.$tiempoTotalComida[$y].'","Tiempo a deber":"'.$tiempoDeuda[$y].'","Estatus":"'.$estatusChec[$y].'","Acciones":"'.$justificarBoton[$y].'"},';
      }
      $table = substr($table,0,strlen($table)-1);
      echo '{"data":['.$table.']}';
?>
