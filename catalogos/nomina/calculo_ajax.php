<?php
        require_once('../../include/db-conn.php');
        if(isset($_GET['id'])){
          $id =  $_GET['id'];
          //$diasTrabajo = $_GET['Dias'];
          $semana = $_GET['semana'];
          $stmt = $conn->prepare('SELECT empleados.Primer_Nombre,empleados.Segundo_Nombre,empleados.Apellido_Paterno,empleados.Apellido_Materno,empleados.NSS,empleados.Infonavit,empleados.Deuda_Interna,empleados.Deuda_Restante,empleados.RFC,turnos.Turno,turnos.Entrada,turnos.Salida,turnos.Horas_de_trabajo,turnos.Dias_de_trabajo,puestos.Puesto,puestos.Sueldo_semanal FROM empleados INNER JOIN datos_empleo ON empleados.PKEmpleado = datos_empleo.FKEmpleado INNER JOIN turnos on datos_empleo.FKTurno = turnos.PKTurno INNER JOIN puestos on datos_empleo.FKPuesto = puestos.PKPuesto WHERE empleados.PKEmpleado= :id');
          $stmt->execute(array(':id'=>$id));
          $row = $stmt->fetch();
          $nombreEmpleado = $row['Primer_Nombre']." ".$row['Segundo_Nombre']." ".$row['Apellido_Paterno']." ".$row['Apellido_Materno'];
          $diasTrabajo = $row['Dias_de_trabajo'];
          $contEstatus = 0;
          $bono = "0.00";
    
          $times = array();
          $horasAc = 0;
          $minutosAc = 0;
          ///////////////// Calculo de nomina////////////////////////////////////////////
          $rfc = $row['RFC'];
          $nss = $row['NSS'];
          $turno = $row['Turno'];
          $puesto = $row['Puesto'];
          $sueldoSemanal = $row['Sueldo_semanal'];
          $sueldo = $row['Sueldo_semanal'];
          $infonavit = $row['Infonavit'];
          $deuda = $row['Deuda_Interna'];
          $parcialidades = $row['Deuda_Interna']/10;
          $deudaRestante = $row['Deuda_Restante'];
          $diasTrabajo = $row['Dias_de_trabajo'];
          $sueldoDiario = $row['Sueldo_semanal']/$diasTrabajo;
          $bonoPreaprovado = 0;
    
          $sueldoTotal = 0.00;
    
          $bonoProductividad = 100/6;
          $bonoCorrespondiente = 0.00;
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
    
          ////////Fechas////////////////////////////
          $stmt = $conn->prepare('SELECT FechaInicio,FechaTermino FROM semanas_checador WHERE PKChecador = :id');
          $stmt->execute(array(':id'=>$semana));
          $x = 0;
    
          while (($row = $stmt->fetch()) !== false) {
            $fecha  = $row['FechaInicio'];
            $fecha2 = $row['FechaTermino'];
     
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
    
    
          for($y = 0;$y<7;$y++){
            $stmt = $conn->prepare('SELECT Estatus,Deuda_Horas FROM gh_checador WHERE FKUsuario = :id AND Fecha = :fecha ORDER BY Fecha ASC');
            $stmt->execute(array(':id'=>$id,':fecha'=>$dateBegin[$y]));
            $row = $stmt->fetch();
            $deuda = date('H:i:s', strtotime($row['Deuda_Horas']));
            array_push($times, $deuda);
            if($row['Estatus'] == 4 || $row['Estatus'] == 9 || $row['Estatus'] == 10){
              $contEstatus++;
            }
            if($diasTrabajo == 5){
              if($y != 1 || $y != 2){
                  if($row['Estatus'] == 4){
                    $bonoCorrespondiente = $bonoCorrespondiente + $bonoProductividad;
                  }
              }
            }else if($diasTrabajo == 6){
              if($y != 2){
                  if($row['Estatus'] == 4){
                    $bonoCorrespondiente = $bonoCorrespondiente + $bonoProductividad;
                  }
              }
            }
          }
    
          AgregarTiempos($times);          
          
          if($horasAc > 0){
            $sueldoDescuento = $sueldoDescuento + ($horasAc * $sueldoHora);
            $descuentoHora = $horasAc * $sueldoHora;
            $sueldo = $sueldo - $descuentoHora;
          }
    
          if($minutosAc > 0){
            $sueldoDescuento = $sueldoDescuento + ($minutosAc * $sueldoMinuto);
            $descuentoMinuto = $horasAc * $sueldoMinuto;
            $sueldo = $sueldo - $descuentoMinuto;
          }
          $sueldoTotal = $sueldoSemanal - $sueldoDescuento - $infonavit - $parcialidades; 

        }

        echo global $sueldoTotal;

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

        
        /*echo '
          <div class="row">
            <div class="col-lg-12">
              <center><h4>Recibo de Pago</h4></center><br>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-4">
              <label><b>Nombre:</b> <?='.$nombreEmpleado.';?></label><br>
              <label><b>NSS:</b> <?='.$nss.';?></label><br>
              <label><b>RFC:</b> <?='.$rfc.';?></label><br>
            </div>
            <div class="col-lg-4">

            </div>
            <div class="col-lg-4">

              <label><b>Turno:</b> <?='.$turno.';?></label><br>
              <label><b>Puesto:</b> <?='.$puesto.';?></label><br>
              <label><b>Periodo de pago: </b><?='.$fecha.';?></label>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-lg-3">
              <div class="row">
                <div class="col-lg-4">
                  <b>Acciones</b>
                </div>
                <div class="col-lg-8">
                  <b>Concepto</b>
                </div>
              </div>
            </div>
            <div class="col-lg-3">
              <b><label class="float-right">Percepción</label></b>
            </div>
            <div class="col-lg-3">
              <b><label class="float-right">Deducción</label></b>
            </div>
            <div class="col-lg-3">
              <b><label class="float-right">Total</label></b>
            </div>
          </div>
          <hr>

          <div class="row">
            <div class="col-lg-3">
              <div class="row">
                <div class="col-lg-4">

                </div>
                <div class="col-lg-8">
                  Sueldo semanal
                </div>
              </div>

            </div>
            <div class="col-lg-3">
              <label class="float-right"><?='.$sueldoSemanal.';?></label>
            </div>
            <div class="col-lg-3">
            </div>
            <div class="col-lg-3">
            </div>
          </div>

          <div class="row">
            <div class="col-lg-3">
              <div class="row">
                <div class="col-lg-4">
                    <button id="btnAgregarBono" type="button" class="btn btn-outline-success" onclick="agregarBono();"><i class="fas fa-plus"></i></i></button>
                    <button id="btnEliminarBono" type="button" class="btn btn-outline-danger" onclick="eliminarBono();"><i class="fas fa-times"></i></button>
                </div>
                <div class="col-lg-8">
                  Bono de productividad
                </div>
              </div>

              <br>
            </div>
            <div class="col-lg-3">
              <label id="lblBono" class="float-right"><?='.$bono.';?></label>
            </div>
            <div class="col-lg-3">
            </div>
            <div class="col-lg-3">
            </div>
          </div>

          <div class="row">
            <div class="col-lg-3">
              <div class="row">
                <div class="col-lg-4">
                </div>
                <div class="col-lg-8">
                  Descuento de improductividad
                </div>
              </div>
            </div>
            <div class="col-lg-3">

            </div>
            <div class="col-lg-3">
              <label id="lblDescuento" class="float-right"><?=number_format('.$sueldoDescuento.', 2, '.', '');?></label>
            </div>
            <div class="col-lg-3">
            </div>
          </div>

          <div class="row">
            <div class="col-lg-3">
              <div class="row">
                <div class="col-lg-4">
                </div>
                <div class="col-lg-8">
                  Descuento del INFONAVIT
                </div>
              </div>
            </div>
            <div class="col-lg-3">

            </div><
            <div class="col-lg-3">
              <label id="lblInfonavit" class="float-right"><?=number_format('$infonavit', 2, '.', '');?></label>$infonavit
            </div>
            <div class="col-lg-3">
            </div>
          </div>

          <div class="row">
            <div class="col-lg-3">
              <div class="row">
                <div class="col-lg-4">
                    <button id="btnAgregarPago" type="button" class="btn btn-outline-success" onclick="agregarPago();"><i class="fas fa-plus"></i></i></button>
                    <button id="btnEliminarPago" type="button" class="btn btn-outline-danger" onclick="eliminarPagoDeuda();"><i class="fas fa-times"></i></button>
                </div>
                <div class="col-lg-8">
                  Descuento deuda interna
                </div>
              </div>
            </div>

            <div class="col-lg-3">

            </div>
            <div class="col-lg-3">

              <label id="lblDeudaInterna" class="float-right"><?=number_format('.$parcialidades.', 2, '.', '');?></label>

            </div>
            <div class="col-lg-3">
            </div>
          </div>
          <hr>
          <div class="row">
            <div class="col-lg-3">
            </div>
            <div class="col-lg-3">
            </div>
            <div class="col-lg-3">
            </div>
            <div class="col-lg-3">
              <label name="lblTotal" id="lblTotal" class="float-right"><?=number_format('.$sueldoTotal.', 2, '.', '');?></label>
            </div>
          </div>
          <input type="hidden" name="txtId" id="txtId" value="<?='.$id.';?>">
          <input type="hidden" name="txtContEstatus" id="txtContEstatus" value="<?='.$contEstatus.';?>">
          <input type="hidden" name="txtSem" id="txtSem">
          <input type="hidden" name="txtBonoAgregado" id="txtBonoAgregado" value="0">
          <input type="hidden" name="txtSalario" id="txtSalario" value="<?='.$sueldoTotal.';?>">
          <input type="hidden" name="txtSalarioSem" id="txtSalarioSem" value="<?='.$sueldoSemanal.';?>">
          <input type="hidden" name="txtDeuda" id="txtDeuda" value="<?='.$deuda.';?>">
          <input type="hidden" name="txtParcialidades" id="txtParcialidades" value="<?='.$parcialidades.';?>">
          <input type="hidden" name="txtBonoPreAprobado" id="txtBonoPreAprobado" value="<?='.$bonoCorrespondiente.';?>">
          <button type="submit" class="btn btn-success float-right" name="btnAgregar">Sellar nomina</button>
          <button type="submit" class="btn btn-primary float-right" name="btnEditar" id="btnEditar">Editar nomina</button>';*/
?>