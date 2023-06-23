<?php
        if(isset($_GET['id'])){
          $id =  $_GET['id'];
          $semana = $_GET['semana'];
          $stmt = $conn->prepare('SELECT e.Nombres,e.PrimerApellido,e.SegundoApellido,dme.NSS,de.Infonavit,de.DeudaInterna,de.DeudaRestante,e.RFC,turnos.PKTurno,turnos.Turno,turnos.Entrada,turnos.Salida,turnos.HorasTrabajo,turnos.Dias_de_trabajo,p.Puesto,de.Sueldo, de.Bono, de.BonoAsignado, de.FactorHorasExtras, n.BonoProductividad  FROM empleados as e INNER JOIN datos_laborales_empleado as de ON e.PKEmpleado = de.FKEmpleado INNER JOIN turnos on de.FKTurno = turnos.PKTurno INNER JOIN puestos as p on de.FKPuesto = p.id LEFT JOIN datos_medicos_empleado as dme ON e.PKEmpleado = dme.FKEmpleado LEFT JOIN nomina_empleado  as n ON n.FKEmpleado = e.PKEmpleado AND n.FKSemana = :semana WHERE e.PKEmpleado= :id');
          $stmt->execute(array(':semana'=>$semana,':id'=>$id));
          $row = $stmt->fetch();
          $nombreEmpleado = $row['Nombres']." ".$row['PrimerApellido']." ".$row['SegundoApellido'];
          $diasTrabajo = $row['Dias_de_trabajo'];
          $contEstatus = 0;
          $contExcelente = 0;

          if($row['BonoProductividad'] == 0.00 || is_null($row['BonoProductividad'])){
            $bono = number_format(0.00, 2, '.', '');
          }
            else{
              $bono = number_format($row['BonoProductividad'], 2, '.', '');
            }
              
          $dobleTurno = 0.00;
          $horasExtras = 0.00;
          $FactorHorasExtras = $row['FactorHorasExtras'];

          $times = array();
          $horasAc = 0;
          $minutosAc = 0;
          ///////////////// Calculo de nomina////////////////////////////////////////////
          if($row['Sueldo'] == 0.00 || is_null($row['Sueldo']))
            $noSueldo = 1;
          else
            $noSueldo = 0;

          $rfc = $row['RFC'];
          $nss = $row['NSS'];
          $turno = $row['Turno'];
          $fkTurno = $row['PKTurno'];
          $puesto = $row['Puesto'];
          $sueldoSemanal = $row['Sueldo'];//sueldo bruto
          $sueldo = $row['Sueldo'];//sueldo neto
          $infonavit = $row['Infonavit'];
          $deuda = $row['DeudaInterna'];
          $parcialidades = $row['DeudaInterna']/10;
          $deudaRestante = $row['DeudaRestante'];
          $diasTrabajo = $row['Dias_de_trabajo'];
          $sueldoDiario = $row['Sueldo']/$diasTrabajo;//sueldo neto entre dias de trabajo
          $diasTrabajadosExcelente = 0;
    
          $sueldoTotal = 0.00;
    
          $bonoExiste = $row['Bono'];

          $bonoProductividad = 0.00;
          if($bonoExiste == 1){
              $bonoProductividad = $row['BonoAsignado'];
          }
          
          $estatusChecada;
          $date = new DateTime("00:00:00");
          $sueldoDescuento = 0;
    
          $horaInicio = new DateTime($row['Entrada']);
          $horaTermino = new DateTime($row['Salida']);
          $interval = $horaInicio->diff($horaTermino);
    
          $horas = $interval->format('%H');
          $minutos = $interval->format('%I');
    
          $horasDivision = $horas;
          if($minutos == 29 || $minutos == 30)
          {
            $minutos = 0.50;
          }
          $horasDivision = $horasDivision + $minutos;
    
    
          $sueldoHora = $sueldoDiario / $horasDivision;
          $sueldoMinuto = $sueldoHora / 60;
          //echo "sueldo hora: ".$sueldoHora." /// sueldo minuto: ".$sueldoMinuto;

          ////////Fechas////////////////////////////
          $stmt = $conn->prepare('SELECT FechaInicio,FechaTermino, DATE_FORMAT(FechaInicio, "%d/%m/%Y") as FechaNomina FROM semanas_checador WHERE PKChecador = :id');
          $stmt->execute(array(':id'=>$semana));
          $x = 0;
    
          while (($row = $stmt->fetch()) !== false) {
            $fecha  = $row['FechaInicio'];
            $fecha2 = $row['FechaTermino'];
            $fechanomina = $row['FechaNomina'];
     
            $period = new DatePeriod(
                 new DateTime($fecha),
                 new DateInterval('P1D'),
                 new DateTime($fecha2)
            );
    
              foreach ($period as $key => $value) {
                  $dateBegin[$x] = $value->format('Y-m-d');
                  $x = $x + 1;
              }
          }
    
          $diasTrabajadosImpuestos = 0;
          for($y = 0;$y<7;$y++){
            $stmt = $conn->prepare('SELECT Estatus,Deuda_Horas FROM gh_checador WHERE FKUsuario = :id AND Fecha = :fecha ORDER BY Fecha ASC');
            $stmt->execute(array(':id'=>$id,':fecha'=>$dateBegin[$y]));
            $row = $stmt->fetch();
            $deuda = date('H:i:s', strtotime($row['Deuda_Horas']));
            array_push($times, $deuda);
            if($row['Estatus'] == 4 || $row['Estatus'] == 9 || $row['Estatus'] == 10 || $row['Estatus'] == 11 || $row['Estatus'] == 12){
              $contEstatus++;
              if($row['Estatus'] == 4)
                $contExcelente++;
            }
            if($diasTrabajo == 5){
              if($y != 1 || $y != 2){
                  if($row['Estatus'] == 4){
                    $diasTrabajadosExcelente++;
                  }
              }
            }else if($diasTrabajo == 6){
              if($y != 2){
                  if($row['Estatus'] == 4){
                    $diasTrabajadosExcelente++;
                  }
              }
            }

            //cantidad de días trabajados en la semana
            if($row['Estatus'] == 4 || $row['Estatus'] == 5 || $row['Estatus'] == 10 || $row['Estatus'] == 11 || $row['Estatus'] == 12){
              $diasTrabajadosImpuestos++;
            }
          }

          $bonoCorrespondiente = ($bonoProductividad / $diasTrabajo) * $diasTrabajadosExcelente;
 
          AgregarTiempos($times);          
          
          if($horasAc > 0){
            $sueldoDescuento = $sueldoDescuento + ($horasAc * $sueldoHora);
            $descuentoHora = $horasAc * $sueldoHora;
            $sueldo = $sueldo - $descuentoHora;
          }
          /*if($minutosAc > 0){
            $sueldoDescuento = $sueldoDescuento + ($minutosAc * $sueldoMinuto);
            $descuentoMinuto = $horasAc * $sueldoMinuto;
            $sueldo = $sueldo - $descuentoMinuto;
          }*/

          ////HORAS EXTRAS
          $cuentaHorasExtras = 0;
          for($y = 0;$y<7;$y++){
            $stmt = $conn->prepare('SELECT Entrada,Salida, Horas_Autorizadas FROM horas_extras WHERE FKEmpleado = :id AND FechaAutorizada = :fecha ORDER BY FechaAutorizada ASC');
            $stmt->execute(array(':id'=>$id,':fecha'=>$dateBegin[$y]));
            $row_he = $stmt->fetch();           

            if($stmt->rowCount() > 0){
                  $horaInicio = new DateTime($row_he['Entrada']);
                  $horaTermino = new DateTime($row_he['Salida']);

                if($horaTermino < $horaInicio){
                  $fechaAhora= time();
                  $fechaMes = date("Y-m-d",$fechaAhora);

                  $fechaMeses = strtotime($fechaMes);
                  $fechaSalida = date( "Y-m-d", strtotime("+1 day",$fechaMeses) );

                  $horaInicio = new DateTime($row_he['Entrada']." ".$fechaMes);
                  $horaTermino = new DateTime($row_he['Salida']." ".$fechaSalida);
                }
                
                $interval = $horaInicio->diff($horaTermino);
          
                $horasHE = $interval->format('%H');
                $minutosHE = $interval->format('%I');
                $sueldoMinutoHE = 0.00; $sueldoHorasHE = 0.00; $sueldoExtra = 0.00;
                
                if($row_he['Entrada'] == null || $row_he['Salida'] == null){
                  $horasExtras = $horasExtras + 0.00;
                }
                else{
                    if(intval($horasHE) >= intval($row_he['Horas_Autorizadas'])){
                      $horasHE = $row_he['Horas_Autorizadas'];
                    }
                    else{
                      $sueldoMinutoHE = ($minutosHE * $sueldoMinuto) * $FactorHorasExtras;
                    }
                    $sueldoHorasHE = ($sueldoHora * $horasHE) * $FactorHorasExtras;
                    $sueldoExtra = $sueldoHorasHE + $sueldoMinutoHE;
                    $horasExtras = $horasExtras + $sueldoExtra;
                }

                  $cuentaHorasExtras++;
            }
          }

          ////DOBLE TURNO
          $cuentaDobleTurno = 0;
          $tiempoComidaOficial = new DateTime('00:32:00');
          $sueldoTotalDT = 0.00;
          for($y = 0;$y<7;$y++){
            $horasDeber = 0;
            $minutosDeber = 0;
            $segundosDeber = 0;

            $stmt = $conn->prepare('SELECT Entrada, Salida_Comida, Regreso_Comida, Salida, Estatus, FKTurno, Deuda_Horas FROM doblar_turno WHERE FKEmpleado = :id AND FechaAutorizada = :fecha ORDER BY FechaAutorizada ASC');
            $stmt->execute(array(':id'=>$id,':fecha'=>$dateBegin[$y]));
            $row_dt = $stmt->fetch();

            if($stmt->rowCount() > 0){
                //Estatus 0 Falta
                //Estatus 4 Excelente
                //Estatus 5 Tiempo injustificado
                //Estatus 9 Justificado sin sueldo
                //Estatus 10 Justificado con sueldo
                $registroComida =$row_dt['Salida_Comida'];
                $registroRegresoComida=$row_dt['Regreso_Comida'];
                $idTurnoDT = $row_dt['FKTurno'];

                $stmt = $conn->prepare('SELECT Entrada, Salida FROM turnos WHERE PKTurno = :id ');
                $stmt->execute(array(':id'=>$idTurnoDT));
                $row_turno = $stmt->fetch();


                if($idTurnoDT != 1){
                  $entradaOficial = new DateTime($row_turno['Entrada']);
                  $salidaOficial = new DateTime($row_turno['Salida']);
                  
                  $entradaEmpleado = new DateTime($row_dt['Entrada']);
                  $salidaEmpleado = new DateTime($row_dt['Salida']);
                }else{
                  $fechaAhora= time();
                  $fechaMes = date("Y-m-d",$fechaAhora);

                  $fechaMeses = strtotime($fechaMes);
                  $fechaSalida = date( "Y-m-d", strtotime("+1 day",$fechaMeses) );

                  $entradaOficial = new DateTime($row_turno['Entrada']." ".$fechaMes);
                  $salidaOficial = new DateTime($row_turno['Salida']." ".$fechaSalida);

                  $entradaEmpleado = new DateTime($row_dt['Entrada']);
                  $salidaEmpleado = new DateTime($row_dt['Salida']." ".$fechaSalida);


                }

                if($row_dt['Entrada'] == null || $row_dt['Salida_Comida'] == null || $row_dt['Regreso_Comida'] == null ||  $row_dt['Salida'] == null){

                  if($row_dt['Estatus'] == 10){
                    $sueldoTotalDT = $sueldoTotalDT + $sueldoDiario;     
                  }
                  elseif($row_dt['Estatus'] == 10 || $row_dt['Estatus'] == 0){
                    $sueldoTotalDT = $sueldoTotalDT + 0.00;     
                  }

                }
                else{
                  if($row_dt['Estatus'] == 5 || $row_dt['Estatus'] == 9 || $row_dt['Estatus'] == 0){

                  $horas_trabajadas = $entradaEmpleado->diff($salidaEmpleado);
                  $deudaHoras = $row_dt['Deuda_Horas'];
                  
                  $horas = sprintf("%02d", $horas_trabajadas->h);
                  $minutos = sprintf("%02d", $horas_trabajadas->i);
                  $segundos = sprintf("%02d", $horas_trabajadas->s);
                  $tiempoHorasTrabajadas = $horas.":".$minutos.":".$segundos;

                  $tiempo1 = new DateTime($tiempoHorasTrabajadas);
                  $tiempo2 = new DateTime($deudaHoras);
                  $tiempodiferencia = $tiempo1->diff($tiempo2);
                  $horasCalculo = $tiempodiferencia->format('%h');
                  $minutosCalculo = $tiempodiferencia->format('%i');

                  $sueldoHoraDT = $horasCalculo * $sueldoHora;
                  $sueldoMinutoDT = $minutosCalculo * $sueldoMinuto;
                  $sueldoTotalDT = $sueldoTotalDT + ($sueldoHoraDT + $sueldoMinutoDT);             

                }
                elseif($row_dt['Estatus'] == 4 || $row_dt['Estatus'] == 10){

                  $sueldoTotalDT = $sueldoTotalDT + $sueldoDiario;

                }
              }

                $cuentaDobleTurno++;
            }
          }
          
          $sueldoTotalDT = number_format($sueldoTotalDT, 2, '.', '');
          $horasExtras = number_format($horasExtras, 2, '.', '');

          $sueldoTotal = $sueldoSemanal + $horasExtras + $sueldoTotalDT + $bono - $sueldoDescuento - $infonavit - $parcialidades; 

          //calculo impuestos
          $stmt = $conn->prepare('SELECT cantidad FROM parametros WHERE descripcion = "Factor_mes" OR descripcion = "Dias_Aguinaldo"  OR descripcion = "Prima_Vacacional" OR descripcion = "UMA" OR descripcion = "Salario_Minimo_Nacional" ORDER BY PKParametros Asc');
          $stmt->execute();
          $row_parametros = $stmt->fetchAll();
          $UMA = $row_parametros[0]['cantidad'];
          $factor_mes = $row_parametros[4]['cantidad'];
          $dias_aguinaldo = $row_parametros[1]['cantidad'];
          $prima_vacacional_tasa = $row_parametros[2]['cantidad'] / 100;
          $salario_minimo = $row_parametros[3]['cantidad'];

          $semanaImpuesto = round($diasTrabajadosImpuestos / 7);
          $salarioTiempoExtra = $horasExtras + $sueldoTotalDT;
          $salarioTiempoExtraLimite = ($horasExtras + $sueldoTotalDT) / 2;
          $limite_excento_salarioExtra = $UMA * 5 * $semanaImpuesto;

          if($salarioTiempoExtraLimite > $limite_excento_salarioExtra){
            $salarioTiempoExtraBase =  $salarioTiempoExtra - $limite_excento_salarioExtra;
          }
          else{
            $salarioTiempoExtraBase = $salarioTiempoExtraLimite;
          }
          
          $salarioTiempoExtraBaseImpuesto = number_format($salarioTiempoExtraBase + $bono,2);

          $sueldoBaseImpuestos = bcdiv($sueldoSemanal + $salarioTiempoExtraBaseImpuesto - $sueldoDescuento - $parcialidades,1,2);
          $totalPercepciones = bcdiv($sueldoSemanal + $salarioTiempoExtra + $bono - $sueldoDescuento - $parcialidades,1,2);
          $sueldoDiario = number_format($sueldoBaseImpuestos / $diasTrabajadosImpuestos,2, '.', '');          
          $base = bcdiv($sueldoDiario * $factor_mes,1,2);
                  
          $stmt = $conn->prepare("SELECT * FROM pagos_provisionales_mensuales WHERE Limite_inferior <= :impuestogravablemin AND Limite_superior >= :impuestogravablesup ");
          $stmt->bindValue(':impuestogravablemin',$base);
          $stmt->bindValue(':impuestogravablesup',$base);
          $stmt->execute();
          $row_limite = $stmt->fetch();
          
          $Limite_inferior = $row_limite['Limite_inferior'];
          $excedente_limite_inferior = number_format($base - $Limite_inferior, 2, '.', '');
          $porcentaje_tabla = $row_limite['Porcentaje_sobre_limite_inferior'];
          $impuesto_marginal = number_format($excedente_limite_inferior * ($porcentaje_tabla/100), 2, '.', '');
          $cuota_fija = $row_limite['Cuota_fija'];
          $ISRDeterminado = $impuesto_marginal + $cuota_fija;

          $stmt = $conn->prepare("SELECT * FROM subsidio_empleo WHERE IngresoMinimo <= :ingresominimo AND IngresoMaximo >= :ingresomaximo ");
          $stmt->bindValue(':ingresominimo',$base);
          $stmt->bindValue(':ingresomaximo',$base);
          $stmt->execute();
          $row_subsidio = $stmt->fetch();

          $subsidioMensual = $row_subsidio['SubsidioMensual'];
          $subsidioAplicable = number_format(($subsidioMensual / $factor_mes) * $diasTrabajadosImpuestos, 2, '.', '');
          $ISRRetenido = number_format($ISRDeterminado, 2, '.', '');
          $ISRDiario = number_format((($ISRRetenido / $factor_mes) * $diasTrabajadosImpuestos) - $subsidioAplicable, 2, '.', '');
          
          require_once('../../functions/funcion_calculovacaciones.php');

          $sueldoDiarioBaseSDI = number_format($sueldoSemanal / $diasTrabajadosImpuestos,2, '.', '');
          $factorSDI = bcdiv(1 + ($dias_aguinaldo + ($dias_vacaciones * $prima_vacacional_tasa)) / 365,1,4);
          $SDI = bcdiv($sueldoDiarioBaseSDI * $factorSDI,1, 2);

          /*CUOTAS MENSUALES*/
          $stmt = $conn->prepare("SELECT * FROM cuotas_mensuales");
          $stmt->execute();
          $row_cuotasmensuales = $stmt->fetchAll();
 
          $impuestoCF = number_format($UMA * 7 * ($row_cuotasmensuales[0]['cantidad']/100), 2, '.', '');
          
          if($SDI > $UMA*3){
            $impuestoExcPat = number_format(($SDI - ($UMA*3)) * 11 * ($row_cuotasmensuales[1]['cantidad']/100), 2, '.', '');
            $impuestoExcObr = number_format(($SDI - ($UMA*3)) * 7 * ($row_cuotasmensuales[2]['cantidad']/100), 2, '.', '');
          }
          else {
            $impuestoExcPat = 0.00;
            $impuestoExcObr = 0.00;
          }

          $impuestoPDPat = number_format($SDI * 7 * ($row_cuotasmensuales[3]['cantidad']/100), 2, '.', '');
          $impuestoPDObr = number_format($SDI * 7 * ($row_cuotasmensuales[4]['cantidad']/100), 2, '.', '');
          $impuestoGMPat = number_format($SDI * 7 * ($row_cuotasmensuales[5]['cantidad']/100), 2, '.', '');
          $impuestoGMObr = number_format($SDI * 7 * ($row_cuotasmensuales[6]['cantidad']/100), 2, '.', '');
          $impuestoRT = number_format($SDI * 7 * ($row_cuotasmensuales[7]['cantidad']/100), 2, '.', '');
          $impuestoIVPat = number_format($SDI * 7 * ($row_cuotasmensuales[8]['cantidad']/100), 2, '.', '');
          $impuestoIVObr = number_format($SDI * 7 * ($row_cuotasmensuales[9]['cantidad']/100), 2, '.', '');
          $impuestoGPS = number_format($SDI * 7 * ($row_cuotasmensuales[10]['cantidad']/100), 2, '.', '');
          $sumapatronalMensual = $impuestoCF + $impuestoExcPat +  $impuestoPDPat + $impuestoGMPat + $impuestoRT + $impuestoIVPat + $impuestoGPS;
          $sumaobreraMensual = $impuestoExcObr + $impuestoPDObr + $impuestoGMObr + $impuestoIVObr;
          $cuota_mensual = $sumapatronalMensual + $sumaobreraMensual;
          /*CUOTAS MENSUALES*/

          /*CUOTAS BIMESTRALES*/
          $stmt = $conn->prepare("SELECT * FROM cuotas_bimestrales");
          $stmt->execute();
          $row_cuotasbimestrales = $stmt->fetchAll();

          $impuestoRETIRO = number_format($SDI * 7 * ($row_cuotasbimestrales[0]['cantidad']/100),2, '.', '');
          $impuestoCYVPat = number_format($SDI * 7 * ($row_cuotasbimestrales[1]['cantidad']/100),2, '.', '');
          $impuestoCYVObr = number_format($SDI * 7 * ($row_cuotasbimestrales[2]['cantidad']/100),2, '.', '');
          $impuestoAPPatron = number_format($SDI * 7 * ($row_cuotasbimestrales[3]['cantidad']/100),2, '.', '');

          $sumapatronalBimestral = $impuestoRETIRO + $impuestoCYVPat +  $impuestoAPPatron;
          $sumaobreraBimestral = $impuestoCYVObr;
          $cuota_Bimestral = $sumapatronalBimestral + $sumaobreraBimestral;

          if($SDI <= $salario_minimo)
          {
            $cuota_obrero = 0.00;
          }
          else{
            $cuota_obrero = $sumaobreraMensual + $sumaobreraBimestral; 
          }
          /*CUOTAS BIMESTRALES*/

          $ISRSemana = $ISRDiario;
          $neto_a_pagar = number_format($totalPercepciones - $ISRSemana - $cuota_obrero - $infonavit,2,'.','');
          //calculo impuestos
          
        }

        function AgregarTiempos($times) {
            global $minutosAc, $horasAc;
            foreach ($times as $time) {
                list($hora, $minuto) = explode(':', $time);
                $minutosAc+= $hora * 60;
                $minutosAc+= $minuto;
            }
        
            $horasAc = floor($minutosAc/ 60);
            $minutosAc-= $horasAc * 60;
            
            return sprintf('%02d:%02d', $horasAc, $minutosAc);
        }
?>