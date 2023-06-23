<?php

function esBisiesto($year=NULL) {
        $year = ($year==NULL)? date('Y'):$year;
        return ( ($year%4 == 0 && $year%100 != 0) || $year%400 == 0 );
}

function calcularDiasVacaciones($num_dias_totales){
    switch ($num_dias_totales)
    {
      case ($num_dias_totales > 0 && $num_dias_totales < 1):
      $dias_vacaciones = 6;
      break;
      case ($num_dias_totales > 1 && $num_dias_totales < 2):
      $dias_vacaciones = 8;
      break;
      case ($num_dias_totales > 2 && $num_dias_totales < 3):
      $dias_vacaciones = 10;
      break;
      case ($num_dias_totales > 3 && $num_dias_totales < 4):
      $dias_vacaciones = 12;
      break;
      case ($num_dias_totales > 4 && $num_dias_totales < 9):
      $dias_vacaciones = 14;
      break;
      case ($num_dias_totales > 9 && $num_dias_totales < 14):
      $dias_vacaciones = 16;
      break;
      case ($num_dias_totales > 14 && $num_dias_totales < 19):
      $dias_vacaciones = 18;
      break;
      case ($num_dias_totales > 19 && $num_dias_totales < 24):
      $dias_vacaciones = 20;
      break;
      case ($num_dias_totales > 24 && $num_dias_totales < 29):
      $dias_vacaciones = 22;
      break;
      case ($num_dias_totales > 29 && $num_dias_totales < 34):
      $dias_vacaciones = 24;
      break;
      case ($num_dias_totales > 34 && $num_dias_totales < 39):
      $dias_vacaciones = 26;
      break;
      case ($num_dias_totales > 39 && $num_dias_totales < 44):
      $dias_vacaciones = 28;
      break;
      case ($num_dias_totales > 44 && $num_dias_totales < 49):
      $dias_vacaciones = 30;
      break;
      case ($num_dias_totales > 49):
      $dias_vacaciones = 32;
      break;
    }

    return $dias_vacaciones;
}

//regresa el dia de vacaciones totales, para finiquito
//0 antiguedad en completo, 1 año con 4 meses = 1.33
//1 factor dias
//2 dias de vacaciones(son los de ultimo año laborable, totales)
//3 dias de vacaciones proporcionales
//4 dias de vacaciones restantes
//5 dias de vacaciones a pagar
//6 antiguedad
function calcularTotalDiasVacaciones($idEmpleado, $fecha_inicial, $fecha_final){
  require($GLOBALS['rutaFuncion'].'../../include/db-conn.php');

  $datetime1 = new DateTime($fecha_inicial); // Fecha inicial
  $datetime2 = new DateTime($fecha_final); // Fecha actual
  $interval = $datetime1->diff($datetime2);
  $num_dias_antiguedad = $interval->format('%a');
  $anios_antiguedad = $interval->format('%y');

  $fechaIngresoComoEntero = strtotime($fecha_inicial);
  $fechaFinalComoEntero = strtotime($fecha_final);

  /*CALCULO DIAS DE VACACIONES*/
  $num_anios = 0;
  $num_dias = 0;
  if(date("m",$fechaIngresoComoEntero) == date("m",$fechaFinalComoEntero) && date("d",$fechaIngresoComoEntero) == date("d",$fechaFinalComoEntero)){

    for($x = date("Y",$fechaIngresoComoEntero);$x < date("Y",$fechaFinalComoEntero);$x++){  
      $num_anios++;
    }

    $num_dias_totales = number_format($num_anios,3,'.',''); 
  }
  else{
    for($x = date("Y",$fechaIngresoComoEntero);$x <= date("Y",$fechaFinalComoEntero);$x++){
      if(esBisiesto($x)){
        $num_dias = $num_dias + 366;
      }
      else{
        $num_dias = $num_dias + 365;
      }
      $num_anios++;
    }
    $factor_anios = $num_dias/$num_anios;
    $num_dias_totales = number_format($num_dias_antiguedad / $factor_anios,3,'.','');
  }

 //
  if(date("Y",$fechaIngresoComoEntero) == date("Y",$fechaFinalComoEntero) ){
    $anio_inicial = date("Y",$fechaIngresoComoEntero);
  }
  elseif(date("Y",$fechaIngresoComoEntero) != date("Y",$fechaFinalComoEntero)  && $num_dias_totales < 1){
    $anio_inicial = date("Y",$fechaFinalComoEntero);
  }
  elseif(date("Y",$fechaIngresoComoEntero) != date("Y",$fechaFinalComoEntero)  && $num_dias_totales >= 1){
    $anio_inicial = date("Y",$fechaFinalComoEntero) - $anios_antiguedad;
  }    

  $anio_final = date("Y",$fechaFinalComoEntero);

  $fecha1= new DateTime($fecha_inicial);
  $diff = $fecha1->diff($datetime2);
  $factor_dias = $diff->days + 1;

  $anio_cumplido_curso = 0.5;
  $dias_vacaciones_totales = 0;
  for($anio = $anio_inicial;$anio <= $anio_final ;$anio++){

    $stmt = $conn->prepare("SELECT diasrestantes FROM vacaciones_agregadas WHERE empleado_id = :empleado_id AND anio = :fecha_anio");
    $stmt->bindValue(':empleado_id',$idEmpleado);
    $stmt->bindValue(':fecha_anio',$anio);
    $stmt->execute();
    $existe_anio = $stmt->rowCount();
    
    if($existe_anio > 0){
      $row = $stmt->fetch();
      $dias_vacaciones = $row['diasrestantes'];
    }
    else{
      $dias_vacaciones = calcularDiasVacaciones($anio_cumplido_curso);
      
    }

    //obtener los dias de vacaciones del ultimo año en curso
    if($anio != $anio_final){
      if(esBisiesto($anio)){
        $factor_dias = $factor_dias - 366;
      }
      else{
        $factor_dias = $factor_dias - 365;
      }
    }

    if($anio == $anio_final){

      $dias_vacaciones_anio_curso = calcularDiasVacaciones($anio_cumplido_curso);

      if(esBisiesto(date("Y",$fechaFinalComoEntero))){
        $dias_vacaciones_calculo = (($dias_vacaciones / 366) * $factor_dias); 
      }
      else{
        $dias_vacaciones_calculo = (($dias_vacaciones / 365) * $factor_dias);
      }

      $dias_vacaciones_totales = $dias_vacaciones_totales + $dias_vacaciones_calculo;
      //echo $dias_vacaciones_calculo;
      $dias_restantes = bcdiv($dias_vacaciones_totales - bcdiv($dias_vacaciones_calculo, 1, 2),1,2);
      //echo " --  ".$dias_restantes." -- ".$dias_vacaciones_totales;
    }
    else{
      $dias_vacaciones_totales = $dias_vacaciones_totales + $dias_vacaciones;
    }


    
    $anio_cumplido_curso++;
  }
  //echo " --  ".$dias_restantes." -- ".$dias_vacaciones_totales;
  return array($num_dias_antiguedad, $factor_dias, $dias_vacaciones_anio_curso, $dias_vacaciones_calculo, $dias_restantes, $dias_vacaciones_totales, $num_dias_totales);
}

//regresa los dias de vacaciones actuales y agrega dias de vacaciones en caso de no existir
function getDiasVacaciones($idEmpleado){
    require($GLOBALS['rutaFuncion'].'../../include/db-conn.php');

    try{

      $where = " WHERE e.PKEmpleado = '".$idEmpleado."'";
      $stmt = $conn->prepare("SELECT de.FechaInicioVacaciones, de.FechaIngreso FROM empleados as e INNER JOIN datos_laborales_empleado as de ON de.FKEmpleado = e.PKEmpleado ".$where);
      $stmt->execute();
      $fecha = $stmt->fetch();

      if(trim($fecha['FechaInicioVacaciones']) == '' || $fecha['FechaInicioVacaciones'] == NULL){
        $fecha_inicio_calculo = $fecha['FechaIngreso'];
      }
      else{
        $fecha_inicio_calculo = $fecha['FechaInicioVacaciones'];
      }
      
      $fechaIngresoComoEntero = strtotime($fecha_inicio_calculo);
      $fecha_actual = date("Y-m-d");
      $fechaFinalComoEntero = strtotime($fecha_actual);


      $datetime1 = new DateTime($fecha_inicio_calculo); // Fecha inicial
      $datetime2 = new DateTime($fecha_actual); // Fecha actual
      $interval = $datetime1->diff($datetime2);
      $num_dias_antiguedad = $interval->format('%a');
      $anios_antiguedad = $interval->format('%y');

      $fechaIngresoComoEntero = strtotime($fecha_inicio_calculo);
      $fechaFinalComoEntero = strtotime($fecha_actual);

      /*CALCULO DIAS DE VACACIONES*/
      $num_anios = 0;
      $num_dias = 0;
      if(date("m",$fechaIngresoComoEntero) == date("m",$fechaFinalComoEntero) && date("d",$fechaIngresoComoEntero) == date("d",$fechaFinalComoEntero)){

        for($x = date("Y",$fechaIngresoComoEntero);$x < date("Y",$fechaFinalComoEntero);$x++){  
          $num_anios++;
        }

        $num_dias_totales = number_format($num_anios,3,'.',''); 
      }
      elseif(date("m",$fechaIngresoComoEntero) != date("m",$fechaFinalComoEntero) || date("d",$fechaIngresoComoEntero) != date("d",$fechaFinalComoEntero)){

        for($x = date("Y",$fechaIngresoComoEntero);$x <= date("Y",$fechaFinalComoEntero);$x++){
          if(esBisiesto($x)){
            $num_dias = $num_dias + 366;
          }
          else{
            $num_dias = $num_dias + 365;
          }
          $num_anios++;
        }
        $factor_anios = $num_dias/$num_anios;
        $num_dias_totales = number_format($num_dias_antiguedad / $factor_anios,3,'.','');
      }
      elseif($fechaFinalComoEntero >= $fechaIngresoComoEntero){
        $num_dias_totales = 0;
      }

      if(date("Y",$fechaIngresoComoEntero) == date("Y",$fechaFinalComoEntero) ){
        $anio_inicial = date("Y",$fechaIngresoComoEntero);
      }
      elseif(date("Y",$fechaIngresoComoEntero) != date("Y",$fechaFinalComoEntero)  && $num_dias_totales < 1){
        $anio_inicial = date("Y",$fechaIngresoComoEntero);
      }
      elseif(date("Y",$fechaIngresoComoEntero) != date("Y",$fechaFinalComoEntero)  && $num_dias_totales >= 1){
        $anio_inicial = date("Y",$fechaFinalComoEntero) - $anios_antiguedad;
      }    

      if($num_dias_totales >= 1){
        $anio_final = date("Y",$fechaFinalComoEntero);
      }
      else{
        $anio_final = $anio_inicial;
      }
      

      $anio_cumplido_curso = 0.5;
      for($anio = $anio_inicial;$anio <= $anio_final ;$anio++){

        $stmt = $conn->prepare("SELECT diasagregados FROM vacaciones_agregadas WHERE anio = :anio AND empleado_id = :empleado_id ");
        $stmt->bindValue(":anio", $anio);
        $stmt->bindValue(":empleado_id", $idEmpleado);
        $stmt->execute();
        $dias_vac = $stmt->fetch();
        $cuenta_dias_vac = $stmt->rowCount();

        if($cuenta_dias_vac > 0){
          $dias_vacaciones = $dias_vac['diasagregados'];

        }
        else{
              $dias_vacaciones = calcularDiasVacaciones($anio_cumplido_curso);   

              $fecha_agregar1 = date("Y-m-d");
              $anio_actual = $anio;
              $stmt = $conn->prepare("INSERT INTO vacaciones_agregadas (anio, diasagregados, diasrestantes, empleado_id, fecha_alta, fecha_edicion, usuario_alta, usuario_edicion)  VALUES ( :anio, :diasagregados, :diasrestantes, :empleado_id, :fecha_alta, :fecha_edicion, :usuario_alta, :usuario_edicion ) ");
              $stmt->bindValue(":anio", $anio_actual);
              $stmt->bindValue(":diasagregados", $dias_vacaciones);
              $stmt->bindValue(":diasrestantes", $dias_vacaciones);
              $stmt->bindValue(":empleado_id", $idEmpleado);
              $stmt->bindValue(":fecha_alta", $fecha_agregar1);
              $stmt->bindValue(":fecha_edicion", $fecha_agregar1);
              $stmt->bindValue(":usuario_alta", $_SESSION['PKUsuario']);
              $stmt->bindValue(":usuario_edicion", $_SESSION['PKUsuario']);
              $stmt->execute();

          }

          if($anio == $anio_final){
            $dias_vacaciones_correspondientes = $dias_vacaciones;
          }


        $anio_cumplido_curso++;
      }
      
      return $dias_vacaciones_correspondientes;
      

    }catch(PDOException $ex){
      return $ex->getMessage();
    }
}

//se obtiene por empresa, por eso no se necesitan parametros
function getDiasAguinaldo(){
  require($GLOBALS['rutaFuncion'].'../../include/db-conn.php');

  $stmt = $conn->prepare('SELECT cantidad FROM parametros_nomina WHERE descripcion = "Dias_Aguinaldo" AND empresa_id = '.$_SESSION['IDEmpresa']);
  $stmt->execute();
  $row_aguinaldo = $stmt->fetch();
  $dias_aguinaldo = $row_aguinaldo['cantidad'];

  return $dias_aguinaldo;

}

function getPrimaVacacional(){
  require($GLOBALS['rutaFuncion'].'../../include/db-conn.php');

  $stmt = $conn->prepare('SELECT cantidad FROM parametros_nomina WHERE descripcion = "Prima_Vacacional"  AND empresa_id = '.$_SESSION['IDEmpresa']);
  $stmt->execute();
  $row_prima_vacacional = $stmt->fetch();
  $prima_vacacional_tasa = $row_prima_vacacional['cantidad'] / 100;

  return $prima_vacacional_tasa;

}

function getFactorMes(){
  
  require($GLOBALS['rutaFuncion'].'../../include/db-conn.php');

  $stmt = $conn->prepare("SELECT cantidad FROM parametros WHERE descripcion = 'Factor_mes' ");
  $stmt->execute();
  $rowP = $stmt->fetch();
  $FactorMes = $rowP['cantidad'];

  return $FactorMes;
}


function getDiasTrabajo($idEmpleado){
  
  require($GLOBALS['rutaFuncion'].'../../include/db-conn.php');

  $stmt = $conn->prepare('SELECT t.Dias_de_trabajo from turnos as t INNER JOIN datos_laborales_empleado as dle ON dle.FKTurno = t.PKTurno WHERE t.empresa_id = :empresa AND dle.FKEmpleado = :idEmpleado');
  $stmt->bindValue(":empresa", $_SESSION['IDEmpresa']);
  $stmt->bindValue(":idEmpleado", $idEmpleado);
  $stmt->execute();
  $resultDias = $stmt->fetch();

  return $resultDias;
}

function getNumDiasTrabajo($idEmpleado){
  
  require($GLOBALS['rutaFuncion'].'../../include/db-conn.php');

  $stmt = $conn->prepare('SELECT t.Num_Dias_Trabajo from turnos as t INNER JOIN datos_laborales_empleado as dle ON dle.FKTurno = t.PKTurno WHERE t.empresa_id = :empresa AND dle.FKEmpleado = :idEmpleado');
  $stmt->bindValue(":empresa", $_SESSION['IDEmpresa']);
  $stmt->bindValue(":idEmpleado", $idEmpleado);
  $stmt->execute();
  $result = $stmt->fetch();

  return $result['Num_Dias_Trabajo'];
}

/**
De acuerdo a la Ley federal del Trabajo en el Art. 89 el salario diario se divide entre 30 días, pero para hacerlo mas justo de acuerdo a
se divide entre 30.4 para que se paguen los dias adicionales o restantes
0 Sueldo
1 Dias Pago
2 Sueldo diario
3 Horas trabajadas
4 Sueldo por hora
5 Sueldo mensual
6 Sucursal empleado
**/
function getSalario_Dias($idEmpleado){
  
  require($GLOBALS['rutaFuncion'].'../../include/db-conn.php');

  $stmt = $conn->prepare("SELECT dle.Sueldo,pp.DiasPago,t.HorasTrabajo, dle.FKSucursal  FROM empleados as e INNER JOIN datos_laborales_empleado as dle ON dle.FKEmpleado = e.PKEmpleado INNER JOIN periodo_pago as pp ON pp.PKPeriodo_pago = dle.FKPeriodo LEFT JOIN datos_medicos_empleado as dme ON dme.FKEmpleado = e.PKEmpleado LEFT JOIN puestos as p ON p.id = dle.FKPuesto LEFT JOIN turnos as t ON t.PKTurno = dle.FKTurno WHERE e.PKEmpleado = :idEmpleado AND e.empresa_id = ".$_SESSION['IDEmpresa']);
  $stmt->bindValue(":idEmpleado", $idEmpleado);
  $stmt->execute();
  $empleado = $stmt->fetch();
  $factor_mes = getFactorMes();

  $salario_diario = bcdiv($empleado['Sueldo'] / $empleado['DiasPago'],1,2);
  
  $salarioHoraCalculado = bcdiv($salario_diario / $empleado['HorasTrabajo'] ,1,2);
  
  $salario_mensual = bcdiv(($empleado['Sueldo'] / $empleado['DiasPago']) * 30,1,2);

  return array($empleado['Sueldo'], $empleado['DiasPago'], $salario_diario, $empleado['HorasTrabajo'], $salarioHoraCalculado, $salario_mensual, $empleado['FKSucursal']);
}

function calcularSalarioFaltas($idEmpleado, $diasfalta){
  $datosEmpleado = getSalario_Dias($idEmpleado);
  /*0 Sueldo
    1 Dias Pago*/

  switch ($datosEmpleado[1]) {
      case 7:
          $semanas = 1;
          break;
      case 14:
          $semanas = 2;
          break;
      case 15:
          $semanas = 2;
          break;
      case 30:
          $semanas = 4;
          break;
  }

  $diasTrabajo = getNumDiasTrabajo($idEmpleado);// son los dias que debe trabajar normalmente
  $diasTrabajoFinal = $diasTrabajo * $semanas;

  $diasDescanso = (7 - $diasTrabajo) * $semanas;
  $diasTrabajadosReales = $diasTrabajo - $diasfalta;//son los dias que ha trabajado en la semana realmente


  $proporcionDiaria = bcdiv($diasDescanso / $diasTrabajo,1,3);
  $proporcionSeptimoDia = bcdiv($proporcionDiaria * $diasTrabajadosReales,1,2);
  $salarioDiario = round($datosEmpleado[0]/$datosEmpleado[1],2);

  $totalDescansoCompleto = $diasDescanso * $salarioDiario;//cantidad que ganaria normalmente si va a trabjar la semana
  $proporcionalDescanso = $proporcionSeptimoDia * $salarioDiario;// el salario que ganaria de acuerdo a sus faltas.
  $totalDescanso = $totalDescansoCompleto - $proporcionalDescanso;//lo que se le restaria de acuerdo a las faltas actuales

  $salarioCalculado = ($salarioDiario * $diasfalta) + $totalDescanso;

  if($diasfalta == $diasTrabajo){
    $salarioCalculado = $datosEmpleado[0];
  }

  return $salarioCalculado;
}

//recibe fecha en formato Y-m-d y regresa anio o mes
//opcional puede regresar mes si opcion es 2
//1 para Año y 2 para mes
function calcularAnio($fecha, $opcion){

  date_default_timezone_set('America/Mexico_City');
  $fechaAnio = DateTime::createFromFormat("Y-m-d", $fecha);

  if($opcion == 1){
    $calculo = $fechaAnio->format("Y");
  }
  else{
    $calculo = $fechaAnio->format("m");
  }
  
  return $calculo;
}

function getUMA($anioCalculo){
  
  require($GLOBALS['rutaFuncion'].'../../include/db-conn.php');
  date_default_timezone_set('America/Mexico_City');

  if($anioCalculo == 0){

    $anioCalculo = calcularAnio(date('Y-m-d'), 1);
  }

  $stmt = $conn->prepare("SELECT cantidad FROM parametros WHERE descripcion = 'UMA' AND anio = :anio ");
  $stmt->bindValue(":anio", $anioCalculo);
  $stmt->execute();
  $rowP = $stmt->fetch();
  $UMA = $rowP['cantidad'];

  return $UMA;
}

function actualizarSBC($idEmpleado, $SBCFijo, $SBCVariables, $periodo, $fecha){

  require($GLOBALS['rutaFuncion'].'../../include/db-conn.php');

  $anioCalculo = calcularAnio($fecha, 1);

  if($periodo == 6){
    $anioCalculo = $anioCalculo - 1;
  }

  $stmt = $conn->prepare('SELECT SalarioBaseCotizacionFijo, SalarioBaseCotizacionVariable FROM datos_laborales_empleado WHERE FKEmpleado = :empleado_id');
  $stmt->bindValue(":empleado_id", $idEmpleado);
  $stmt->execute();
  $row_sbc_base= $stmt->fetch();

  if($row_sbc_base['SalarioBaseCotizacionFijo'] == "" || $row_sbc_base['SalarioBaseCotizacionFijo'] == null){

      $stmt = $conn->prepare('UPDATE datos_laborales_empleado SET SalarioBaseCotizacionFijo = :SalarioBaseCotizacionFijo, SalarioBaseCotizacionVariable = :SalarioBaseCotizacionVariable WHERE FKEmpleado = :empleado_id');
      $stmt->bindValue(":SalarioBaseCotizacionFijo", $SBCFijo);
      $stmt->bindValue(":SalarioBaseCotizacionVariable", $SBCVariables);
      $stmt->bindValue(":empleado_id", $idEmpleado);
      $stmt->execute();
      $row_sbc_base= $stmt->fetch();
  }

  $stmt = $conn->prepare('SELECT sbc_fijo, sbc_variables, modificable FROM salario_base_cotizacion WHERE empleado_id = :empleado_id AND periodo = :periodo');
  $stmt->bindValue(":empleado_id", $idEmpleado);
  $stmt->bindValue(":periodo", $periodo);
  $stmt->execute();
  $existe = $stmt->rowCount();

  if($existe > 0){
      $row_sbc = $stmt->fetch();

      if($row_sbc['modificable'] == 0){
          $stmt = $conn->prepare('UPDATE salario_base_cotizacion SET sbc_fijo = :sbc_fijo, sbc_variables = :sbc_variables WHERE empleado_id = :empleado_id AND periodo = :periodo AND anio = :anio');
          $stmt->bindValue(":sbc_fijo", $SBCFijo);
          $stmt->bindValue(":sbc_variables", $SBCVariables);
          $stmt->bindValue(":empleado_id", $idEmpleado);
          $stmt->bindValue(":periodo", $periodo);
          $stmt->bindValue(":anio", $anioCalculo);
          
          if($stmt->execute()){
              $estatus = "exito";
          }
          else{
              $estatus = "fallo";
          }
      }
      else{
          $estatus = "no-modificar";
      }
  }
  else{

      $stmt = $conn->prepare('INSERT INTO salario_base_cotizacion (sbc_fijo , sbc_variables, empleado_id, anio, periodo) VALUES ( :sbc_fijo, :sbc_variables, :empleado_id, :anio, :periodo) ');
      $stmt->bindValue(":sbc_fijo", $SBCFijo);
      $stmt->bindValue(":sbc_variables", $SBCVariables);
      $stmt->bindValue(":empleado_id", $idEmpleado);
      $stmt->bindValue(":anio", $anioCalculo);
      $stmt->bindValue(":periodo", $periodo);

      if($stmt->execute()){
          $estatus = "exito";
      }
      else{
          $estatus = "fallo";
      }
  }

  return $estatus;

}

//calcula el salario diario integrado sin variables
function getSDI($idEmpleado){

  $dias_vacaciones = getDiasVacaciones($idEmpleado);
  $dias_aguinaldo = getDiasAguinaldo();
  $prima_vacacional_tasa = getPrimaVacacional();
  $datosSalario = getSalario_Dias($idEmpleado);

  $salarioBaseDiario = $datosSalario[2];

  $factorSDI = bcdiv(1 + ($dias_aguinaldo + ($dias_vacaciones * $prima_vacacional_tasa)) / 365,1,4);
  $SDI = bcdiv($salarioBaseDiario * $factorSDI,1, 2);//Salario Base Cotizacion o Salario Diario Integrado, es igual

  return $SDI;
}

function getPeriodoSBC($fecha){

    $fechaMes = DateTime::createFromFormat("Y-m-d", $fecha);
    $mesCalculo = $fechaMes->format("m");

    if($mesCalculo == 1 || $mesCalculo == 2){
      $periodo = 6;
    }
    if($mesCalculo == 3 || $mesCalculo == 4){
      $periodo = 1;
    }
    if($mesCalculo == 5 || $mesCalculo == 6){
      $periodo = 2;
    }
    if($mesCalculo == 7 || $mesCalculo == 8){
      $periodo = 3;
    }
    if($mesCalculo == 9 || $mesCalculo == 10){
      $periodo = 4;
    }
    if($mesCalculo == 11 || $mesCalculo == 12){
      $periodo = 5;
    }

    return $periodo;

}

function getMesPeriodo($periodo){

    if($periodo == 1){
      $mes_inicial = 1;
      $mes_final = 2;
    }
    if($periodo == 2){
      $mes_inicial = 3;
      $mes_final = 4;
    }
    if($periodo == 3){
      $mes_inicial = 5;
      $mes_final = 6;
    }
    if($periodo == 4){
      $mes_inicial = 7;
      $mes_final = 8;
    }
    if($periodo == 5){
      $mes_inicial = 9;
      $mes_final = 10;
    }
    if($periodo == 6){
      $mes_inicial = 11;
      $mes_final = 12;
    }

    return array($mes_inicial, $mes_final);
}


function getDiasFaltasIMSS($idNominaEmpleado, $idEmpleado){
  require($GLOBALS['rutaFuncion'].'../../include/db-conn.php');

  $stmt = $conn->prepare('SELECT SUM(dias) as sumaDias FROM detalle_nomina_deduccion_empleado WHERE nomina_empleado_id = :nomina_empleado_id AND empleado_id = :empleado_id ');
  $stmt->bindValue(':nomina_empleado_id', $idNominaEmpleado);
  $stmt->bindValue(':empleado_id', $idEmpleado);
  $stmt->execute();

  if($stmt->rowCount() < 1){
    $diasFalta = 0;
  }
  else{
    $row = $stmt->fetch();
    $diasFalta = $row['sumaDias'];
  }
  

  return $diasFalta;
}

function getBaseCotizacion($idEmpleado){

  require($GLOBALS['rutaFuncion'].'../../include/db-conn.php');

  $stmt = $conn->prepare('SELECT BaseCotizacion FROM datos_laborales_empleado WHERE FKEmpleado = :empleado_id ');
  $stmt->bindValue(':empleado_id', $idEmpleado);
  $stmt->execute();
  $row = $stmt->fetch();
  $baseCotizacion = $row['BaseCotizacion'];

  return $baseCotizacion;
}


function getCalculoSBCVariables($periodo, $anioCalculo, $idEmpleado, $SBCFijo){

  require($GLOBALS['rutaFuncion'].'../../include/db-conn.php');

  //echo "SBC ".$SBCFijo."<br>";
  $datosMeses = getMesPeriodo($periodo);
  $baseCotizacion = getBaseCotizacion($idEmpleado);
  //echo $periodo." - ".$anioCalculo." -  ".$idEmpleado." - ".$SBCFijo." - ".$datosMeses[0]."/////////";
  $stmt = $conn->prepare(' SELECT n.id, ne.PKNomina FROM nomina as n INNER JOIN nomina_empleado as ne ON ne.FKNomina = n.id AND ne.FKEmpleado = :idEmpleado INNER JOIN nomina_principal as np ON np.id = n.fk_nomina_principal WHERE n.empresa_id = :idEmpresa AND MONTH(n.fecha_pago) = :mes_inicio AND YEAR(n.fecha_pago) = :anio_inicio AND np.tipo_id = 1');
  $stmt->bindValue(':idEmpleado',  $idEmpleado);
  $stmt->bindValue(':idEmpresa', $_SESSION['IDEmpresa']);
  $stmt->bindValue(':mes_inicio',  $datosMeses[0]);
  $stmt->bindValue(':anio_inicio',  $anioCalculo);
  $stmt->execute();          
  $rowNominas1 = $stmt->fetchAll();
/*  SELECT n.id, ne.PKNomina FROM nomina as n 
INNER JOIN nomina_empleado as ne ON ne.FKNomina = n.id AND ne.FKEmpleado = 102 

WHERE n.empresa_id = 1 AND MONTH(n.fecha_pago) = 11
 AND YEAR(n.fecha_pago) = 2022 AND np.tipo_id = 1*/

  $stmt = $conn->prepare(' SELECT n.id, ne.PKNomina FROM nomina as n INNER JOIN nomina_empleado as ne ON ne.FKNomina = n.id AND ne.FKEmpleado = :idEmpleado INNER JOIN nomina_principal as np ON np.id = n.fk_nomina_principal WHERE n.empresa_id = :idEmpresa AND MONTH(n.fecha_pago) = :mes_fin AND YEAR(n.fecha_pago) = :anio_inicio AND np.tipo_id = 1');
  $stmt->bindValue(':idEmpleado',  $idEmpleado);
  $stmt->bindValue(':idEmpresa', $_SESSION['IDEmpresa']);
  $stmt->bindValue(':mes_fin',  $datosMeses[1]);
  $stmt->bindValue(':anio_inicio',  $anioCalculo);
  $stmt->execute();          
  $rowNominas2 = $stmt->fetchAll();

  //echo "<pre>",print_r($rowNominas),"</pre>";
  $suma_pp1 = 0.00; $suma_pa1 = 0.00; $suma_vales1 = 0.00;
  $suma_variables = 0.00; //todas las variables que cotizan al 100%
  $diasFaltas = 0;
  foreach($rowNominas1 as $rn1){

      $diasFaltas = $diasFaltas + getDiasFaltasIMSS( $rn1['PKNomina'], $idEmpleado);

      $stmt = $conn->prepare('SELECT * FROM detalle_nomina_percepcion_empleado as dnpe INNER JOIN nomina_empleado as ne ON dnpe.nomina_empleado_id  = ne.PKNomina WHERE ne.FKNomina = :idNomina AND ne.FKEmpleado = :idEmpleado');
      $stmt->bindValue(':idNomina',  $rn1['id']);
      $stmt->bindValue(':idEmpleado',  $idEmpleado);
      $stmt->execute();          
      $rowDetalle1 = $stmt->fetchAll();

      foreach($rowDetalle1 as $rd1){

          //Premio puntualidad
          if($rd1['relacion_tipo_percepcion_id'] == 8){
            $suma_pp1 = $suma_pp1 + $rd1['importe'];
          }

          //Premio asistencia
          if($rd1['relacion_tipo_percepcion_id'] == 40){
            $suma_pa1 = $suma_pa1 + $rd1['importe'];
          }

          //Vales de despensa
          if($rd1['relacion_tipo_percepcion_id'] == 24){
            $suma_vales1 = $suma_vales1 + $rd1['importe'];
          }

          //Horas extras triples
          if($rd1['relacion_tipo_percepcion_id'] == 14){

            if($rd1['horas'] == 3){
              $suma_variables = $suma_variables + $rd1['importe'];
            }
          }

          //Prima dominical, prima de antiguedad, comisiones, otros ingresos por salarios, viaticos, --pagos por gratificacions, prima, compensaciones--
          if($rd1['relacion_tipo_percepcion_id'] == 15 || $rd1['relacion_tipo_percepcion_id'] == 17 || $rd1['relacion_tipo_percepcion_id'] == 23 || $rd1['relacion_tipo_percepcion_id'] == 33 || $rd1['relacion_tipo_percepcion_id'] == 41 || $rd1['relacion_tipo_percepcion_id'] == 42){

              $suma_variables = $suma_variables + $rd1['importe'];
          }

          //solo aplica cuando la base de cotizacion es variable para considerar el salario
          if($baseCotizacion == 2){

            if($rd1['relacion_tipo_percepcion_id'] == 1){
                $suma_variables = $suma_variables + $rd1['importe'];
            }

          }
            


      }
      

  }

  $suma_pp2 = 0.00; $suma_pa2 = 0.00; $suma_vales2 = 0.00;
  $excedente_pp1 = 0.00; $excedente_pp2 = 0.00;
  $excedente_pa1 = 0.00; $excedente_pa2 = 0.00;
  $excedente_vales1 = 0.00; $excedente_vales2 = 0.00;
  $SBCVariable = 0.00;
  foreach($rowNominas2 as $rn2){

      $diasFaltas = $diasFaltas + getDiasFaltasIMSS( $rn2['PKNomina'], $idEmpleado);

      $stmt = $conn->prepare('SELECT * FROM detalle_nomina_percepcion_empleado as dnpe INNER JOIN nomina_empleado as ne ON dnpe.nomina_empleado_id  = ne.PKNomina WHERE ne.FKNomina = :idNomina AND ne.FKEmpleado = :idEmpleado');
      $stmt->bindValue(':idNomina',  $rn2['id']);
      $stmt->bindValue(':idEmpleado',  $idEmpleado);
      $stmt->execute();          
      $rowDetalle2 = $stmt->fetchAll();

      foreach($rowDetalle2 as $rd2){

          //Premio puntualidad
          if($rd2['relacion_tipo_percepcion_id'] == 8){
            $suma_pp2 = $suma_pp2 + $rd2['importe'] + $rd2['importe_exento'];
          }

          //Premio asistencia
          if($rd2['relacion_tipo_percepcion_id'] == 40){
            $suma_pa2 = $suma_pa2 + $rd2['importe'] + $rd2['importe_exento'];
          }

          //Vales de despensa
          if($rd2['relacion_tipo_percepcion_id'] == 24){
            $suma_vales2 = $suma_vales2 + $rd2['importe'];
          }

          //Horas extras triples
          if($rd2['relacion_tipo_percepcion_id'] == 14){

            if($rd2['horas'] == 3){
              $suma_variables = $suma_variables + $rd2['importe'];
            }
          }

          //Prima dominical, prima de antiguedad, comisiones, otros ingresos por salarios, viaticos, --pagos por gratificacions, prima, compensaciones--
          if($rd2['relacion_tipo_percepcion_id'] == 15 || $rd2['relacion_tipo_percepcion_id'] == 17 || $rd2['relacion_tipo_percepcion_id'] == 23 || $rd2['relacion_tipo_percepcion_id'] == 33 || $rd2['relacion_tipo_percepcion_id'] == 41 || $rd2['relacion_tipo_percepcion_id'] == 42){

              $suma_variables = $suma_variables + $rd2['importe'];
          }

          //solo aplica cuando la base de cotizacion es variable para considerar el salario
          if($baseCotizacion == 2){

            if($rd1['relacion_tipo_percepcion_id'] == 1){
                $suma_variables = $suma_variables + $rd2['importe'];
            }

          }

      }

  }

  $totalPercepcionesVariables = 0.00;
  $limiteSBC = bcdiv(($SBCFijo * 0.10) * 30, 1 , 4); //limite mensual del 10%

  $UMA = getUMA($anioCalculo); 
  $limiteSBCVales = bcdiv(($UMA * 0.40) * 30, 1 , 4); //limite mensual del 40%
//echo "limiteSBC ".$limiteSBC."<br>";
  
  //premios puntualidad
  if($suma_pp1 > 0.00){

    if($suma_pp1 > $limiteSBC){
      $excedente_pp1 = $suma_pp1 - $limiteSBC;
    }
    else{
      $excedente_pp1 = 0.00;
    }
    
  }

  if($suma_pp2 > 0.00){

    if($suma_pp2 > $limiteSBC){
      $excedente_pp2 = $suma_pp2 - $limiteSBC;
    }
    else{
      $excedente_pp2 = 0.00;
    }
    
  }

  //premios asistencia
  if($suma_pa1 > 0.00){

    if($suma_pa1 > $limiteSBC){
      $excedente_pa1 = $suma_pa1 - $limiteSBC;
    }
    else{
      $excedente_pa1 = 0.00;
    }
    
  }

  if($suma_pa2 > 0.00){

    if($suma_pa2 > $limiteSBC){
      $excedente_pa2 = $suma_pa2 - $limiteSBC;
    }
    else{
      $excedente_pa2 = 0.00;
    }
    
  }


  //Vales de despensa
  if($suma_vales1 > 0.00){

    if($suma_vales1 > $limiteSBCVales){
      $excedente_vales1 = $suma_vales1 - $limiteSBCVales;
    }
    else{
      $excedente_vales1 = 0.00;
    }
    
  }

  if($suma_vales2 > 0.00){

    if($suma_vales2 > $limiteSBCVales){
      $excedente_vales2 = $suma_vales2 - $limiteSBCVales;
    }
    else{
      $excedente_vales2 = 0.00;
    }
    
  }

  $totalPercepcionesVariables = $excedente_pp1 + $excedente_pp2 + $excedente_pa1 + $excedente_pa2 + $excedente_vales1 + $excedente_vales2 + $suma_variables;

  $fechaIniBimestre = date('Y-'.$datosMeses[0].'-01' );
  $fechaFinBimestre = date('Y-'.$datosMeses[1].'-01' );
  $diaFinalMes = date("Y-m-t", strtotime($fechaFinBimestre));

  $date1 = date_create_from_format('Y-m-d', $fechaIniBimestre);
  $date2 = date_create_from_format('Y-m-d',  $diaFinalMes);
  $diff = (array) date_diff($date1, $date2);

  $diferencia = $diff['days'];
  $diasDeTrabajo = $diferencia + 1;

  $diasPeriodoIMSS = $diasDeTrabajo - $diasFaltas;

  $SBCVariable = bcdiv($totalPercepcionesVariables / $diasPeriodoIMSS ,1,2);

  return $SBCVariable;
}

//calcular el salario base de cotizacion con variables
// quizas se necesite agregar dias que se restaran
// 0 para no actualizar, 1 para actualizar
//0 SBC Fijo
//1 SBC Variable
//2 Salario base diario
function calcularSBC($idEmpleado, $fecha, $actualizar){

  require($GLOBALS['rutaFuncion'].'../../include/db-conn.php');
  date_default_timezone_set('America/Mexico_City');

  $dias_aguinaldo = getDiasAguinaldo();
  $prima_vacacional_tasa = getPrimaVacacional();
  $dias_vacaciones = getDiasVacaciones($idEmpleado);
  $datosSalario = getSalario_Dias($idEmpleado);
  $salarioBaseDiario = $datosSalario[2];// se calculo con lo dias del periodo, ya que no afecta los dias trabajados por el empleado, sino el periodo, al igual el salario, es su pago completo
  $factorSDI = bcdiv(1 + ($dias_aguinaldo + ($dias_vacaciones * $prima_vacacional_tasa)) / 365,1,4);
  $SBCFijo = bcdiv($salarioBaseDiario * $factorSDI,1, 2);//Salario Base Cotizacion o Salario Diario Integrado, es igual

  if($fecha != ''){
    $periodo = getPeriodoSBC($fecha);
  }
  else{
    $periodo = getPeriodoSBC(date('Y-m-d'));
  }

  //hasta solo son variables fijas

  //////////**CALCULAR SBC VARIABLES**///////////

  $fechaAnio = DateTime::createFromFormat("Y-m-d", $fecha);
  $anioCalculo = $fechaAnio->format("Y");
  $SBCVariables = getCalculoSBCVariables($periodo, $anioCalculo, $idEmpleado, $SBCFijo);

  /*****************************************////


  /*/////////////////Ingresar el SBC si no se ha ingresado////////////////*/
  ///****************Solo actualizar en caso de variable***********////////

  if($actualizar == 1){
    $estatus = actualizarSBC($idEmpleado, $SBCFijo, $SBCVariables, $periodo);
  }

  ////////////////////////////////*********************************/////////
  return array($SBCFijo, $SBCVariables, $salarioBaseDiario);
}

//0 SBC Fijo
//1 SBC Variable
//2 SBC
//3 SDI
function getSBCNomina($idEmpleado, $fecha){
  require($GLOBALS['rutaFuncion'].'../../include/db-conn.php');
  date_default_timezone_set('America/Mexico_City');

  if($fecha != ''){
    $periodo = getPeriodoSBC($fecha);
  }
  else{
    $fecha = date('Y-m-d');
    $periodo = getPeriodoSBC($fecha);
  }

  $anioCalculo = calcularAnio($fecha, 1);
  $UMA = getUMA($anioCalculo);

  if($periodo == 6){
    $anioCalculo = $anioCalculo - 1;
  } 

  $stmt = $conn->prepare('SELECT sbc_fijo, sbc_variables FROM salario_base_cotizacion WHERE empleado_id = :empleado_id AND periodo = :periodo AND anio = :anio');
  $stmt->bindValue(":empleado_id", $idEmpleado);
  $stmt->bindValue(":periodo", $periodo);
  $stmt->bindValue(":anio", $anioCalculo);
  $stmt->execute();
  $existe = $stmt->rowCount();

  if($existe > 0){
    $row_sbc_base = $stmt->fetch();
    $SBCFijo = $row_sbc_base['sbc_fijo'];
    $SBCVariable = $row_sbc_base['sbc_variables'];
  }
  else{
    $stmt = $conn->prepare('SELECT SalarioBaseCotizacionFijo, SalarioBaseCotizacionVariable FROM datos_laborales_empleado WHERE FKEmpleado = :empleado_id');
    $stmt->bindValue(":empleado_id", $idEmpleado);
    $stmt->execute();
    $row_sbc_base= $stmt->fetch();

    $SBCFijo = $row_sbc_base['SalarioBaseCotizacionFijo'];
    $SBCVariable = $row_sbc_base['SalarioBaseCotizacionVariable'];
  }

  if($SBCFijo == 0 || $SBCFijo == '' || $SBCFijo == null){
    $datosSBC = calcularSBC($idEmpleado, $fecha, 0);

    $SBC = $datosSBC[0] + $datosSBC[1];
    $SBCFijo = $datosSBC[0];
    $SBCVariable = $datosSBC[1];
    
  }
  else{
    $SBC = $SBCFijo + $SBCVariable;
  }

  $limite = bcdiv($UMA * 25,1,2);
  $SDI = $SBC;

  if($SBC > $limite){
    $SBC = $limite;
  }

  return array($SBCFijo, $SBCVariable, $SBC, $SDI);

}

//calculo del salario base de cotizacion, solo funciona con ingreso fijos
function getSBC($idEmpleado){

  $SDI = getSDI($idEmpleado);
  $UMA = getUMA(0);
  $limite = bcdiv($UMA * 25,1,2);

  if($SDI > $limite){
    $SBC = $limite;
  }
  else{
    $SBC = $SDI;
  }

  return $SBC;
}

//0 salario minimo nacional
//1 salario minimo norte
function getSalarioMinimo(){
  require($GLOBALS['rutaFuncion'].'../../include/db-conn.php');

  $stmt = $conn->prepare("SELECT cantidad FROM parametros WHERE descripcion = 'Salario_Minimo_Nacional' OR descripcion = 'Salario_Minimo_Norte'");
  $stmt->execute();
  $rowP = $stmt->fetchAll();

  return array($rowP[0]['cantidad'],$rowP[1]['cantidad']);
}
/*
function getAniosTrabajados($idEmpleado, $fechaSalida){
    require('../../../include/db-conn.php');

    try{

        /*CALCULO DIAS DE VACACIONES
        $where = " WHERE e.PKEmpleado = '".$idEmpleado."'";
        $stmt = $conn->prepare("SELECT e.PKEmpleado, de.FechaIngreso
                                    FROM empleados as e 
                                      INNER JOIN datos_laborales_empleado as de ON de.FKEmpleado = e.PKEmpleado".$where);
        $stmt->execute();
        $datos_vacaciones = $stmt->fetch();
  
        $fechaIngreso = $datos_vacaciones['FechaIngreso'];
        $fechaFinal = $fechaSalida;
        $datetime1 = new DateTime($fechaIngreso); // Fecha inicial
        $datetime2 = new DateTime($fechaFinal); // Fecha actual
        $interval = $datetime1->diff($datetime2);
        $num_dias_antiguedad = $interval->format('%a');

        $fechaIngresoComoEntero = strtotime($fechaIngreso);
        $fechaFinalComoEntero = strtotime($fechaFinal);
        $num_anios = 0;
        $num_dias = 0;
        $anios_trabajados = 0;


        if(date("m",$fechaIngresoComoEntero) == date("m",$fechaFinalComoEntero) && date("d",$fechaIngresoComoEntero) == date("d",$fechaFinalComoEntero)){

            for($x = date("Y",$fechaIngresoComoEntero);$x < date("Y",$fechaFinalComoEntero);$x++){  
              $num_anios++;
            }
            //$json->num_anios = number_format($num_anios,3,'.','');
        }
        elseif(date("m",$fechaIngresoComoEntero) != date("m",$fechaFinalComoEntero) || date("d",$fechaIngresoComoEntero) != date("d",$fechaFinalComoEntero)){

            for($x = date("Y",$fechaIngresoComoEntero);$x <= date("Y",$fechaFinalComoEntero);$x++){
              if(esBisiesto($x)){
                $num_dias = $num_dias + 366;
              }
              else{
                $num_dias = $num_dias + 365;
              }
              $num_anios++;
            }
            $factor_anios = $num_dias/$num_anios;
            $anios_trabajados = number_format($num_dias_antiguedad / $factor_anios,3,'.','');
            echo "medio".$anios_trabajados;
        }
        elseif($fechaFinalComoEntero >= $fechaIngresoComoEntero){
          $anios_trabajados = 0;
          echo "fin";
        }

          echo $anios_trabajados;      

      
      return " saa ".$num_anios;
      

    }catch(PDOException $ex){
      return $ex->getMessage();
    }
}
*/

//regresa el id de la sucursal a la que pertenece el empleado
function getSucursalProcedencia($idEmpleado){
  require($GLOBALS['rutaFuncion'].'../../include/db-conn.php');

  $stmt = $conn->prepare('SELECT FKSucursal FROM datos_laborales_empleado WHERE FKEmpleado = '.$idEmpleado);
  $stmt->execute();
  $row_sucursal = $stmt->fetch();
  $sucursal = $row_sucursal['FKSucursal'];

  return $sucursal;
}


//regresa la zona del salario de la sucursal
function getZonaSalario($idSucursal){
  require($GLOBALS['rutaFuncion'].'../../include/db-conn.php');

  $stmt = $conn->prepare('SELECT zona_salario_minimo FROM sucursales WHERE id = '.$idSucursal.'  AND empresa_id = '.$_SESSION['IDEmpresa']);
  $stmt->execute();
  $row_zona = $stmt->fetch();
  $zona_salario = $row_zona['zona_salario_minimo'];

  return $zona_salario;
}

//regresa el riesgo de trabajo por empresa
function getRiesgoTrabajo(){
  require($GLOBALS['rutaFuncion'].'../../include/db-conn.php');

  $stmt = $conn->prepare('SELECT cantidad FROM parametros_nomina WHERE descripcion = "Riesgo_Trabajo"  AND empresa_id = '.$_SESSION['IDEmpresa']);
  $stmt->execute();
  $row_riesgo = $stmt->fetch();
  $riesgo_trabajo = $row_riesgo['cantidad'];

  return $riesgo_trabajo;
}


//0 indemnizacion
//1 indemnizacion gravada 
//2 indemnizacion exenta
//3 salario por año de servicio
//4 prima antiguedad
function calculoInicialLiquidacion($idEmpleado, $anios_completos, $diasCheck, $primaAntiguedadCheck){
  require($GLOBALS['rutaFuncion'].'../../include/db-conn.php');
 
  $SBC = getSBC($idEmpleado);
  $salarioMinimo = getSalarioMinimo();
  $UMA = getUMA(0);

  $idSucursal = getSucursalProcedencia($idEmpleado);
  $stmt = $conn->prepare('SELECT zona_salario_minimo FROM sucursales WHERE id = '.$idSucursal.'  AND empresa_id = '.$_SESSION['IDEmpresa']);
  $stmt->execute();
  $row_zona = $stmt->fetch();
  $zona_salario = $row_zona['zona_salario_minimo'];

  if($zona_salario == 1){
    $salario_minimo = $salarioMinimo[0];
  }
  else{
    $salario_minimo = $salarioMinimo[1];
  }

  $Indemnizacion = number_format($SBC * 90,2,'.','');
  $parte_exenta_indemnizacion =  number_format($UMA * 90 * $anios_completos,2,'.','');
  
  if($parte_exenta_indemnizacion > $Indemnizacion){
    $Indemnizacion_gravada = 0.00;
    $Indemnizacion_exenta = $Indemnizacion;
  }
  else{
    $Indemnizacion_gravada = number_format($Indemnizacion - $parte_exenta_indemnizacion,2,'.','');
    $Indemnizacion_exenta = number_format($Indemnizacion - $Indemnizacion_gravada,2,'.','');
  }

  if($diasCheck == 1){
    $salario_anio_servicio = number_format($SBC * ($anios_completos * 20),2,'.','');
  }
  else{
    $salario_anio_servicio = 0.00;
  }

  //prima de antiguedad
  $valorBasePA = $salario_minimo * 2;
  if($primaAntiguedadCheck == 1){
    $prima_antiguedad = ($valorBasePA * 12) * $anios_completos;
  }
  else{
    $prima_antiguedad = 0.00;
  }
  

  return array($Indemnizacion, $Indemnizacion_gravada, $Indemnizacion_exenta, $salario_anio_servicio, $prima_antiguedad);
}

//programada en especifico para finiquito
// 0 isr
// 1 SAE, cuando es mayor el subsidio
// ISR para calculo del ajuste al subsidio
// Subsidio para el calculo del ajuste al subsidio
function calculoISRFiniquito($base, $anio){

  require($GLOBALS['rutaFuncion'].'../../include/db-conn.php');
  
  if($base < 0.01){
    $base = 0.01;
  }
  $stmt = $conn->prepare("SELECT * FROM pagos_provisionales_mensuales WHERE Limite_inferior <= :impuestogravablemin AND Limite_superior >= :impuestogravablesup AND Anio = :anio");
  $stmt->bindValue(':impuestogravablemin',$base);
  $stmt->bindValue(':impuestogravablesup',$base);
  $stmt->bindValue(':anio',$anio);
  $stmt->execute();
  $row_limite = $stmt->fetch();

  $stmt = $conn->prepare("SELECT * FROM subsidio_empleo  WHERE IngresoMinimo <= :impuestogravablemin AND IngresoMaximo >= :impuestogravablesup AND Anio = :anio");
  $stmt->bindValue(':impuestogravablemin',$base);
  $stmt->bindValue(':impuestogravablesup',$base);
  $stmt->bindValue(':anio',$anio);
  $stmt->execute();
  $row_subsidio = $stmt->fetch();

  $Limite_inferior = $row_limite['Limite_inferior'];
  $excedente_limite_inferior = number_format($base - $Limite_inferior, 2, '.', '');
  $porcentaje_tabla = $row_limite['Porcentaje_sobre_limite_inferior'];
  $impuesto_marginal = number_format($excedente_limite_inferior * ($porcentaje_tabla/100), 2, '.', '');
  $cuota_fija = $row_limite['Cuota_fija'];
  $ISRDeterminado = $impuesto_marginal + $cuota_fija;
  $subsidio_mensual = $row_subsidio['SubsidioMensual'];

  if($subsidio_mensual > $ISRDeterminado){
    $ISR_Retener = 0.00;
    $SAE_Pagar = number_format($subsidio_mensual - $ISRDeterminado,2, '.', '');
  }
  else{
    $ISR_Retener = number_format($ISRDeterminado - $subsidio_mensual,2, '.', '');
    $SAE_Pagar = 0.00;
  }

  return array($ISR_Retener, $SAE_Pagar, $ISRDeterminado, $subsidio_mensual );
}


//Mostrara el calculo de ISR o SAE de finiquito
// 0 isr
// 1 SAE, cuando es mayor el subsidio
function calculoISRFiniquitoImpresion($base, $anio){

  require($GLOBALS['rutaFuncion'].'../../include/db-conn.php');
  
  if($base < 0.01){

    $calculoMostrar = '<div class="row" style="display: block;">
                            <center>
                              No se puede calcular el ISR con un valor de cero.
                            </center>
                           </div>';

  }
  else{
  
    $stmt = $conn->prepare("SELECT * FROM pagos_provisionales_mensuales WHERE Limite_inferior <= :impuestogravablemin AND Limite_superior >= :impuestogravablesup AND Anio = :anio");
    $stmt->bindValue(':impuestogravablemin',$base);
    $stmt->bindValue(':impuestogravablesup',$base);
    $stmt->bindValue(':anio',$anio);
    $stmt->execute();
    $row_limite = $stmt->fetch();

    $stmt = $conn->prepare("SELECT * FROM subsidio_empleo  WHERE IngresoMinimo <= :impuestogravablemin AND IngresoMaximo >= :impuestogravablesup AND Anio = :anio");
    $stmt->bindValue(':impuestogravablemin',$base);
    $stmt->bindValue(':impuestogravablesup',$base);
    $stmt->bindValue(':anio',$anio);
    $stmt->execute();
    $row_subsidio = $stmt->fetch();

    $Limite_inferior = $row_limite['Limite_inferior'];
    $excedente_limite_inferior = number_format($base - $Limite_inferior, 2, '.', '');
    $porcentaje_tabla = $row_limite['Porcentaje_sobre_limite_inferior'];
    $impuesto_marginal = number_format($excedente_limite_inferior * ($porcentaje_tabla/100), 2, '.', '');
    $cuota_fija = $row_limite['Cuota_fija'];
    $ISRDeterminado = $impuesto_marginal + $cuota_fija;
    $subsidio_mensual = $row_subsidio['SubsidioMensual'];

    if($subsidio_mensual > $ISRDeterminado){
      $titulo = "SAE a pagar";
      $ISR_Retener = 0.00;
      $SAE_Pagar = number_format($subsidio_mensual - $ISRDeterminado,2, '.', '');
      $impuesto_aplicable = $SAE_Pagar;
    }
    else{    
      $titulo = "ISR";
      $ISR_Retener = number_format($ISRDeterminado - $subsidio_mensual,2, '.', '');
      $SAE_Pagar = 0.00;
      $impuesto_aplicable = $ISR_Retener;
    }

    $calculoMostrar = '
                    <table class="table table-sm" id="calculoISRtabla">
                        <thead class="text-center header-color">
                          <tr>
                            <th></th>
                            <th>Concepto</th>
                            <th>Importe</th>
                          </tr>
                        </thead>
                        <tbody>                      
                          <tr class="text-center">
                            <td></td>
                            <td>Ingreso gravado</td>
                            <td>'.number_format($base, 2, '.', ',').'</td>
                          </tr>
                          <tr class="text-center">
                            <td>(-)</td>
                            <td>Límite inferior tarifa ISR mensual</td>
                            <td>'.number_format($Limite_inferior, 2, '.', ',').'</td>
                          </tr>
                          <tr class="text-center">
                            <td>(=)</td>
                            <td>Excedente</td>
                            <td>'.number_format($excedente_limite_inferior, 2, '.', ',').'</td>
                          </tr>
                          <tr class="text-center">
                            <td>(X)</td>
                            <td>Tasa</td>
                            <td>'.number_format($porcentaje_tabla, 2, '.', ',').'</td>
                          </tr>
                          <tr class="text-center">
                            <td>(=)</td>
                            <td>Impuesto marginal</td>
                            <td>'.number_format($impuesto_marginal, 2, '.', ',').'</td>
                          </tr>
                          <tr class="text-center">
                            <td>(+)</td>
                            <td>Cuota fija</td>
                            <td>'.number_format($cuota_fija, 2, '.', ',').'</td>
                          </tr>
                          <tr class="text-center">
                            <td>(-)</td>
                            <td>Subsidio</td>
                            <td>'.number_format($subsidio_mensual, 2, '.', ',').'</td>
                          </tr>
                          <tr class="text-center">
                            <td>(=)</td>
                            <td>'.$titulo.'</td>
                            <td>'.number_format($impuesto_aplicable, 2, '.', ',').'</td>
                          </tr>
                        </tbody>
                    </table>';
  }

  

  return $calculoMostrar;
}


function calculoISRGeneralFiniquito($aguinaldo_gravado, $sueldoMensual, $anio){

  if($aguinaldo_gravado > 0){

        require($GLOBALS['rutaFuncion'].'../../include/db-conn.php');

        //calculo del isr del salario
        $stmt = $conn->prepare("SELECT * FROM pagos_provisionales_mensuales WHERE Limite_inferior <= :impuestogravablemin AND Limite_superior >= :impuestogravablesup AND Anio = :anio");
        $stmt->bindValue(':impuestogravablemin',$sueldoMensual);
        $stmt->bindValue(':impuestogravablesup',$sueldoMensual);
        $stmt->bindValue(':anio',$anio);
        $stmt->execute();
        $row_limite = $stmt->fetch();

        $Limite_inferior = $row_limite['Limite_inferior'];
        $excedente_limite_inferior = number_format($sueldoMensual - $Limite_inferior, 2, '.', '');
        $porcentaje_tabla = $row_limite['Porcentaje_sobre_limite_inferior'];
        $impuesto_marginal = number_format($excedente_limite_inferior * ($porcentaje_tabla/100), 2, '.', '');
        $cuota_fija = $row_limite['Cuota_fija'];

        $stmt = $conn->prepare("SELECT * FROM subsidio_empleo WHERE IngresoMinimo <= :ingresominimo AND IngresoMaximo >= :ingresomaximo AND Anio = :anio");
        $stmt->bindValue(':ingresominimo',$sueldoMensual);
        $stmt->bindValue(':ingresomaximo',$sueldoMensual);
        $stmt->bindValue(':anio',$anio);
        $stmt->execute();
        $row_subsidio = $stmt->fetch();
        $subsidioMensual = $row_subsidio['SubsidioMensual'];

        $ISRDeterminado = $impuesto_marginal + $cuota_fija;
        if($subsidioMensual > $ISRDeterminado){
          $SAESalario = number_format($subsidioMensual - $ISRDeterminado, 2, '.', '');
          $ISRSalario = 0.00;
        }
        else{
          $ISRSalario = number_format($ISRDeterminado - $subsidioMensual, 2, '.', '');
          $SAESalario = 0.00;
        }
        
        $FactorMes = getFactorMes();
        $FraccionI = round(($aguinaldo_gravado/365) * $FactorMes,2);//FACTOR MENSUALIZACION
        $FraccionIIa = number_format($sueldoMensual + $FraccionI, 2, '.', '');

        //calculo del isr Fraccion II
        $stmt = $conn->prepare("SELECT * FROM pagos_provisionales_mensuales WHERE Limite_inferior <= :impuestogravablemin AND Limite_superior >= :impuestogravablesup AND Anio = :anio");
        $stmt->bindValue(':impuestogravablemin',$FraccionIIa);
        $stmt->bindValue(':impuestogravablesup',$FraccionIIa);
        $stmt->bindValue(':anio',$anio);
        $stmt->execute();
        $row_limite = $stmt->fetch();

        $Limite_inferior = $row_limite['Limite_inferior'];
        $excedente_limite_inferior = number_format($FraccionIIa - $Limite_inferior, 2, '.', '');
        $porcentaje_tabla = $row_limite['Porcentaje_sobre_limite_inferior'];
        $impuesto_marginal = number_format($excedente_limite_inferior * ($porcentaje_tabla/100), 2, '.', '');
        $cuota_fija = $row_limite['Cuota_fija'];

        $stmt = $conn->prepare("SELECT * FROM subsidio_empleo WHERE IngresoMinimo <= :ingresominimo AND IngresoMaximo >= :ingresomaximo AND Anio = :anio");
        $stmt->bindValue(':ingresominimo',$FraccionIIa);
        $stmt->bindValue(':ingresomaximo',$FraccionIIa);
        $stmt->bindValue(':anio',$anio);
        $stmt->execute();
        $row_subsidio = $stmt->fetch();
        $subsidioMensual = $row_subsidio['SubsidioMensual'];

        $ISRDeterminadoEspecifico = $impuesto_marginal + $cuota_fija; //es el isr o sae antes del subsidio del aguinaldo o prima vacacional

        if($subsidioMensual > $ISRDeterminadoEspecifico){
          $SAEEspecifico =  number_format($subsidioMensual - $ISRDeterminadoEspecifico, 2, '.', ''); 
          $ISREspecifico = 0.00;
          $FraccionII = $SAEEspecifico;

          //CUANDO isr salario existe y se obtiene sae en salario+prestacion
          if($ISRSalario != 0.00){
            $FraccionIII_SAE  = number_format($FraccionII - $ISRSalario, 2, '.', ''); //queda como sae
            $FraccionIII_ISR  = 0.00;
            $FraccionIII  = $FraccionIII_SAE;

            $FraccionV = round(($FraccionIII / $FraccionI) * 100,2);
            $FraccionIV =  number_format($aguinaldo_gravado * ($FraccionV / 100), 2, '.', ''); //ISRAguinaldo

            $ISR = 0.00;
            $SAE = $FraccionIV;
          }
          //cuando el salario es SAE y se obtiene sae en salario+prestacion 
          if($SAESalario != 0.00){
            $FraccionIII_SAE  = number_format(abs($FraccionII - $SAESalario), 2, '.', ''); //queda como sae
            $FraccionIII_ISR  = 0.00;
            $FraccionIII  = $FraccionIII_SAE;

            $FraccionV = round(($FraccionIII / $FraccionI) * 100,2);
            $FraccionIV =  number_format($aguinaldo_gravado * ($FraccionV / 100), 2, '.', ''); //ISRAguinaldo

            $ISR = $FraccionIV;
            $SAE = 0.00;
          }

        }
        else{
          $SAEEspecifico = 0.00;
          $ISREspecifico = number_format($ISRDeterminadoEspecifico - $subsidioMensual, 2, '.', ''); 
          $FraccionII = $ISREspecifico;

          //CUANDO isr salario existe y se obtiene isr en salario+prestacion
          if($ISRSalario != 0.00){
            $FraccionIII_ISR  = number_format($FraccionII - $ISRSalario, 2, '.', ''); //queda como sae
            $FraccionIII_SAE  = 0.00;
            $FraccionIII  = $FraccionIII_ISR;
 
            $FraccionV = round(($FraccionIII / $FraccionI) * 100,2);
            $FraccionIV =  number_format($aguinaldo_gravado * ($FraccionV / 100), 2, '.', ''); //ISRAguinaldo

            $ISR = $FraccionIV;
            $SAE = 0.00;
          }
          //cuando el salario es SAE y se obtiene isr en salario+prestacion 
          if($SAESalario != 0.00){
            $FraccionIII_SAE  = number_format(abs($FraccionII - $SAESalario), 2, '.', ''); //queda como sae
            $FraccionIII_ISR  = 0.00;
            $FraccionIII  = $FraccionIII_SAE;

            $FraccionV = round(($FraccionIII / $FraccionI) * 100,2);
            $FraccionIV =  number_format($aguinaldo_gravado * ($FraccionV / 100), 2, '.', ''); //ISRAguinaldo

            $SAE = $FraccionIV;
            $ISR = 0.00;
          }

        }

  }
  else{
    $SAE = 0.00;
    $ISR = 0.00;
  }
  


  return array($ISR, $SAE);
}

//muestra el calculo del ISR de aguinaldo y prima vacacional
function calculoISRGeneralFiniquitoImpresion($aguinaldo_gravado, $sueldoMensual, $tipo, $anio){

  if($tipo == 2){
    $titulo_final_1 = "Aguinaldo gravado";
    $titulo_final_2 = "ISR Aguinaldo";
    $titulo_final_3 = " aguinaldo";
  }
  else{
    $titulo_final_1 = "Prima vacacional gravada";
    $titulo_final_2 = "ISR Prima vacacional";
    $titulo_final_3 = " prima vacacional";
  }

  if($aguinaldo_gravado > 0){

        require($GLOBALS['rutaFuncion'].'../../include/db-conn.php');

        //calculo del isr del salario
        $stmt = $conn->prepare("SELECT * FROM pagos_provisionales_mensuales WHERE Limite_inferior <= :impuestogravablemin AND Limite_superior >= :impuestogravablesup AND Anio = :anio");
        $stmt->bindValue(':impuestogravablemin',$sueldoMensual);
        $stmt->bindValue(':impuestogravablesup',$sueldoMensual);
        $stmt->bindValue(':anio',$anio);
        $stmt->execute();
        $row_limite = $stmt->fetch();

        $Limite_inferior = $row_limite['Limite_inferior'];
        $excedente_limite_inferior = number_format($sueldoMensual - $Limite_inferior, 2, '.', '');
        $porcentaje_tabla = $row_limite['Porcentaje_sobre_limite_inferior'];
        $impuesto_marginal = number_format($excedente_limite_inferior * ($porcentaje_tabla/100), 2, '.', '');
        $cuota_fija = $row_limite['Cuota_fija'];

        $stmt = $conn->prepare("SELECT * FROM subsidio_empleo WHERE IngresoMinimo <= :ingresominimo AND IngresoMaximo >= :ingresomaximo AND Anio = :anio");
        $stmt->bindValue(':ingresominimo',$sueldoMensual);
        $stmt->bindValue(':ingresomaximo',$sueldoMensual);
        $stmt->bindValue(':anio',$anio);
        $stmt->execute();
        $row_subsidio = $stmt->fetch();
        $subsidioMensual = $row_subsidio['SubsidioMensual'];

        $ISRDeterminado = $impuesto_marginal + $cuota_fija;
        if($subsidioMensual > $ISRDeterminado){
          $SAESalario = number_format($subsidioMensual - $ISRDeterminado, 2, '.', '');
          $ISRSalario = 0.00;
          $titulo = "SAE a pagar";
          $impuesto_aplicable = $SAESalario;
        }
        else{
          $ISRSalario = number_format($ISRDeterminado - $subsidioMensual, 2, '.', '');
          $SAESalario = 0.00;
          $titulo = "ISR";
          $impuesto_aplicable = $ISRSalario;
        }
        
        $FactorMes = getFactorMes();
        $FraccionI = round(($aguinaldo_gravado/365) * $FactorMes,2);//FACTOR MENSUALIZACION
        $FraccionIIa = number_format($sueldoMensual + $FraccionI, 2, '.', '');

        //calculo del isr Fraccion II
        $stmt = $conn->prepare("SELECT * FROM pagos_provisionales_mensuales WHERE Limite_inferior <= :impuestogravablemin AND Limite_superior >= :impuestogravablesup AND Anio = :anio");
        $stmt->bindValue(':impuestogravablemin',$FraccionIIa);
        $stmt->bindValue(':impuestogravablesup',$FraccionIIa);
        $stmt->bindValue(':anio',$anio);
        $stmt->execute();
        $row_limite = $stmt->fetch();

        $Limite_inferior_II = $row_limite['Limite_inferior'];
        $excedente_limite_inferior_II = number_format($FraccionIIa - $Limite_inferior_II, 2, '.', '');
        $porcentaje_tabla_II = $row_limite['Porcentaje_sobre_limite_inferior'];
        $impuesto_marginal_II = number_format($excedente_limite_inferior_II * ($porcentaje_tabla_II/100), 2, '.', '');
        $cuota_fija_II = $row_limite['Cuota_fija'];

        $stmt = $conn->prepare("SELECT * FROM subsidio_empleo WHERE IngresoMinimo <= :ingresominimo AND IngresoMaximo >= :ingresomaximo AND Anio = :anio");
        $stmt->bindValue(':ingresominimo',$FraccionIIa);
        $stmt->bindValue(':ingresomaximo',$FraccionIIa);
        $stmt->bindValue(':anio',$anio);
        $stmt->execute();
        $row_subsidio = $stmt->fetch();
        $subsidioMensual_II = $row_subsidio['SubsidioMensual'];

        $ISRDeterminadoEspecifico = $impuesto_marginal_II + $cuota_fija_II; //es el isr o sae antes del subsidio del aguinaldo o prima vacacional

        if($subsidioMensual_II > $ISRDeterminadoEspecifico){

          $titulo_II = "SAE a pagar";

          $SAEEspecifico =  number_format($subsidioMensual_II - $ISRDeterminadoEspecifico, 2, '.', ''); 
          $impuesto_aplicable_II = $SAEEspecifico;
          $ISREspecifico = 0.00;
          $FraccionII = $SAEEspecifico;

          //CUANDO isr salario existe y se obtiene sae en salario+prestacion
          if($ISRSalario != 0.00){
            $FraccionIII_SAE  = number_format($FraccionII - $ISRSalario, 2, '.', ''); //queda como sae
            $FraccionIII_ISR  = 0.00;
            $FraccionIII  = $FraccionIII_SAE;

            $FraccionV = round(($FraccionIII / $FraccionI) * 100,2);
            $FraccionIV =  number_format($aguinaldo_gravado * ($FraccionV / 100), 2, '.', ''); //ISRAguinaldo

            $ISR = 0.00;
            $SAE = $FraccionIV;
          }
          //cuando el salario es SAE y se obtiene sae en salario+prestacion 
          if($SAESalario != 0.00){
            $FraccionIII_SAE  = number_format(abs($FraccionII - $SAESalario), 2, '.', ''); //queda como sae
            $FraccionIII_ISR  = 0.00;
            $FraccionIII  = $FraccionIII_SAE;

            $FraccionV = round(($FraccionIII / $FraccionI) * 100,2);
            $FraccionIV =  number_format($aguinaldo_gravado * ($FraccionV / 100), 2, '.', ''); //ISRAguinaldo

            $ISR = $FraccionIV;
            $SAE = 0.00;
          }

        }
        else{
          $titulo_II = "ISR";

          $SAEEspecifico = 0.00;
          $ISREspecifico = number_format($ISRDeterminadoEspecifico - $subsidioMensual_II, 2, '.', ''); 
          $impuesto_aplicable_II = $ISREspecifico;
          $FraccionII = $ISREspecifico;

          //CUANDO isr salario existe y se obtiene isr en salario+prestacion
          if($ISRSalario != 0.00){
            $FraccionIII_ISR  = number_format($FraccionII - $ISRSalario, 2, '.', ''); //queda como sae
            $FraccionIII_SAE  = 0.00;
            $FraccionIII  = $FraccionIII_ISR;
 
            $FraccionV = round(($FraccionIII / $FraccionI) * 100,2);
            $FraccionIV =  number_format($aguinaldo_gravado * ($FraccionV / 100), 2, '.', ''); //ISRAguinaldo

            $ISR = $FraccionIV;
            $SAE = 0.00;
            $impuesto_final = $ISR;
          }
          //cuando el salario es SAE y se obtiene isr en salario+prestacion 
          if($SAESalario != 0.00){
            $FraccionIII_SAE  = number_format(abs($FraccionII - $SAESalario), 2, '.', ''); //queda como sae
            $FraccionIII_ISR  = 0.00;
            $FraccionIII  = $FraccionIII_SAE;

            $FraccionV = round(($FraccionIII / $FraccionI) * 100,2);
            $FraccionIV =  number_format($aguinaldo_gravado * ($FraccionV / 100), 2, '.', ''); //ISRAguinaldo

            $SAE = $FraccionIV;
            $ISR = 0.00;
            $impuesto_final = $SAE;
          }

        }

        $calculoMostrar = '
                    <table class="table table-sm" id="calculoISRtabla">
                        <thead class="text-center header-color">
                          <tr>
                            <th></th>
                            <th>Concepto</th>
                            <th>Importe</th>
                            <th></th>
                            <th width="120px">Fracción II</th>
                          </tr>
                        </thead>
                        <tbody>                      
                          <tr class="text-center">
                            <td></td>
                            <td>Ingreso gravado</td>
                            <td>'.number_format($sueldoMensual, 2, '.', ',').'</td>
                            <td></td>
                            <td>'.number_format($FraccionIIa, 2, '.', ',').'</td>
                          </tr>
                          <tr class="text-center">
                            <td>(-)</td>
                            <td>Límite inferior tarifa ISR mensual</td>
                            <td>'.number_format($Limite_inferior, 2, '.', ',').'</td>
                            <td></td>
                            <td>'.number_format($Limite_inferior_II, 2, '.', ',').'</td>
                          </tr>
                          <tr class="text-center">
                            <td>(=)</td>
                            <td>Excedente</td>
                            <td>'.number_format($excedente_limite_inferior, 2, '.', ',').'</td>
                            <td></td>
                            <td>'.number_format($excedente_limite_inferior_II, 2, '.', ',').'</td>
                          </tr>
                          <tr class="text-center">
                            <td>(X)</td>
                            <td>Tasa</td>
                            <td>'.number_format($porcentaje_tabla, 2, '.', ',').'</td>
                            <td></td>
                            <td>'.number_format($porcentaje_tabla_II, 2, '.', ',').'</td>
                          </tr>
                          <tr class="text-center">
                            <td>(=)</td>
                            <td>Impuesto marginal</td>
                            <td>'.number_format($impuesto_marginal, 2, '.', ',').'</td>
                            <td></td>
                            <td>'.number_format($impuesto_marginal_II, 2, '.', ',').'</td>
                          </tr>
                          <tr class="text-center">
                            <td>(+)</td>
                            <td>Cuota fija</td>
                            <td>'.number_format($cuota_fija, 2, '.', ',').'</td>
                            <td></td>
                            <td>'.number_format($cuota_fija_II, 2, '.', ',').'</td>
                          </tr>
                          <tr class="text-center">
                            <td>(-)</td>
                            <td>Subsidio</td>
                            <td>'.number_format($subsidioMensual, 2, '.', ',').'</td>
                            <td></td>
                            <td>'.number_format($subsidioMensual_II, 2, '.', ',').'</td>
                          </tr>
                          <tr class="text-center">
                            <td>(=)</td>
                            <td>'.$titulo.'</td>
                            <td>'.number_format($impuesto_aplicable, 2, '.', ',').'</td>
                            <td></td>
                            <td>'.number_format($impuesto_aplicable_II, 2, '.', ',').'</td>
                          </tr>
                          <tr class="text-center">
                            <td>(=)</td>
                            <td>Diferencia</td>
                            <td></td>
                            <td></td>
                            <td>'.number_format($FraccionIII, 2, '.', ',').'</td>
                          </tr>
                          <tr class="text-center">
                            <td>(=)</td>
                            <td>Tasa</td>
                            <td></td>
                            <td></td>
                            <td>'.number_format($FraccionV, 2, '.', ',').'</td>
                          </tr>
                          <tr class="text-center">
                            <td>(X)</td>
                            <td>'.$titulo_final_1.'</td>
                            <td></td>
                            <td></td>
                            <td>'.number_format($aguinaldo_gravado, 2, '.', ',').'</td>
                          </tr>
                          <tr class="text-center">
                            <td>(=)</td>
                            <td>'.$titulo_final_2.'</td>
                            <td></td>
                            <td></td>
                            <td>'.number_format($impuesto_final, 2, '.', ',').'</td>
                          </tr>
                        </tbody>
                    </table>
                </div>';


  }
  else{
    
    $calculoMostrar = '
                        <div class="row" style="display: block;">
                          <center>
                            No hay ISR de '.$titulo_final_3.'
                          </center>
                         </div>';

  }
  


  return $calculoMostrar;
}


//se calcula el ISR de acuerdo al periodo:  -7 semanal  -14 catorcenal   -15 quincenal  -30 mensual
// 0 ISR
// 1 SAE
// 2 Tipo isr o sae
  function calculoISR($idEmpleado, $anio){

    require($GLOBALS['rutaFuncion'].'../../include/db-conn.php');

    $salario = getSalario_Dias($idEmpleado);
    $base = $salario[5];
    $periodo = $salario[1];

    $factor_mes = getFactorMes();

    $stmt = $conn->prepare("SELECT * FROM pagos_provisionales_mensuales WHERE Limite_inferior <= :impuestogravablemin AND Limite_superior >= :impuestogravablesup AND Anio = :anio");
    $stmt->bindValue(':impuestogravablemin',$base);
    $stmt->bindValue(':impuestogravablesup',$base);
    $stmt->bindValue(':anio',$anio);
    $stmt->execute();
    $row_limite = $stmt->fetch();

    $Limite_inferior = $row_limite['Limite_inferior'];
    $excedente_limite_inferior = number_format($base - $Limite_inferior, 2, '.', '');
    $porcentaje_tabla = $row_limite['Porcentaje_sobre_limite_inferior'];
    $impuesto_marginal = number_format($excedente_limite_inferior * ($porcentaje_tabla/100), 2, '.', '');
    $cuota_fija = $row_limite['Cuota_fija'];
    $ISRDeterminado = $impuesto_marginal + $cuota_fija;

    $stmt = $conn->prepare("SELECT * FROM subsidio_empleo WHERE IngresoMinimo <= :ingresominimo AND IngresoMaximo >= :ingresomaximo AND Anio = :anio");
    $stmt->bindValue(':ingresominimo',$base);
    $stmt->bindValue(':ingresomaximo',$base);
    $stmt->bindValue(':anio',$anio);
    $stmt->execute();
    $row_subsidio = $stmt->fetch();
    $subsidioMensual = $row_subsidio['SubsidioMensual'];
    $subsidioAplicable = number_format(($subsidioMensual / $factor_mes) * $periodo, 2, '.', ''); 

    $ISRRetenido = number_format($ISRDeterminado, 2, '.', '');      
    $ISRDiario = number_format((($ISRRetenido / $factor_mes) * $periodo) , 2, '.', '');

    if($subsidioAplicable > $ISRDiario){
      $SAE_Pagar =  number_format($subsidioAplicable - $ISRDiario, 2, '.', '');
      $ISR = 0.00;
      $tipo = 2;
    }
    else{
      $ISR = number_format($ISRDiario - $subsidioAplicable, 2, '.', '');
      $SAE_Pagar = 0.00;
      $tipo = 1;
    }
  
  return array($ISR, $SAE_Pagar, $tipo);
}


function calculoISRAguinaldo($tipoCalculoISR, $sueldoMensual, $aguinaldo_gravado, $aguinaldo, $anioCalculo){

      require($GLOBALS['rutaFuncion'].'../../include/db-conn.php');

      $ingreso_gravado = $sueldoMensual + $aguinaldo_gravado;

      //calculo del isr del salario
      $stmt = $conn->prepare("SELECT * FROM pagos_provisionales_mensuales WHERE Limite_inferior <= :impuestogravablemin AND Limite_superior >= :impuestogravablesup AND Anio = :anio");
      $stmt->bindValue(':impuestogravablemin',$sueldoMensual);
      $stmt->bindValue(':impuestogravablesup',$sueldoMensual);
      $stmt->bindValue(':anio',$anioCalculo);
      $stmt->execute();
      $row_limite = $stmt->fetch();

      $Limite_inferior = $row_limite['Limite_inferior'];
      $excedente_limite_inferior = number_format($sueldoMensual - $Limite_inferior, 2, '.', '');
      $porcentaje_tabla = $row_limite['Porcentaje_sobre_limite_inferior'];
      $impuesto_marginal = number_format($excedente_limite_inferior * ($porcentaje_tabla/100), 2, '.', '');
      $cuota_fija = $row_limite['Cuota_fija'];

      $stmt = $conn->prepare("SELECT * FROM subsidio_empleo WHERE IngresoMinimo <= :ingresominimo AND IngresoMaximo >= :ingresomaximo AND Anio = :anio");
      $stmt->bindValue(':ingresominimo',$sueldoMensual);
      $stmt->bindValue(':ingresomaximo',$sueldoMensual);
      $stmt->bindValue(':anio',$anioCalculo);
      $stmt->execute();
      $row_subsidio = $stmt->fetch();
      $subsidioMensual = $row_subsidio['SubsidioMensual'];

      $ISRSalario = number_format($impuesto_marginal + $cuota_fija - $subsidioMensual, 2, '.', '');

    //calculo segun ISR
    if($tipoCalculoISR == 1){

      //calculo del isr del salario mas aguinaldo
      $stmt = $conn->prepare("SELECT * FROM pagos_provisionales_mensuales WHERE Limite_inferior <= :impuestogravablemin AND Limite_superior >= :impuestogravablesup AND Anio = :anio");
      $stmt->bindValue(':impuestogravablemin',$ingreso_gravado);
      $stmt->bindValue(':impuestogravablesup',$ingreso_gravado);
      $stmt->bindValue(':anio',$anioCalculo);
      $stmt->execute();
      $row_limite = $stmt->fetch();

      $Limite_inferior = $row_limite['Limite_inferior'];
      $excedente_limite_inferior = number_format($ingreso_gravado - $Limite_inferior, 2, '.', '');
      $porcentaje_tabla = $row_limite['Porcentaje_sobre_limite_inferior'];
      $impuesto_marginal = number_format($excedente_limite_inferior * ($porcentaje_tabla/100), 2, '.', '');
      $cuota_fija = $row_limite['Cuota_fija'];

      $stmt = $conn->prepare("SELECT * FROM subsidio_empleo WHERE IngresoMinimo <= :ingresominimo AND IngresoMaximo >= :ingresomaximo AND Anio = :anio");
      $stmt->bindValue(':ingresominimo',$ingreso_gravado);
      $stmt->bindValue(':ingresomaximo',$ingreso_gravado);
      $stmt->bindValue(':anio',$anioCalculo);
      $stmt->execute();
      $row_subsidio = $stmt->fetch();
      $subsidioMensual = $row_subsidio['SubsidioMensual'];

      $ISRSalarioAguinaldo = number_format($impuesto_marginal + $cuota_fija - $subsidioMensual, 2, '.', '');
      
      $ISRAguinaldo = number_format($ISRSalarioAguinaldo - $ISRSalario, 2, '.', '');
      $AguinaldoPagar = number_format($aguinaldo - $ISRAguinaldo, 2, '.', '');

    }
    //calculo segun RISR(Reglamento)
    if($tipoCalculoISR == 2){

      $FraccionI = number_format(($aguinaldo_gravado/365) * 30.4, 2, '.', '');
      $FraccionIIa = number_format($sueldoMensual + $FraccionI, 2, '.', '');

      //calculo del isr Fraccion II
      $stmt = $conn->prepare("SELECT * FROM pagos_provisionales_mensuales WHERE Limite_inferior <= :impuestogravablemin AND Limite_superior >= :impuestogravablesup AND Anio = :anio");
      $stmt->bindValue(':impuestogravablemin',$FraccionIIa);
      $stmt->bindValue(':impuestogravablesup',$FraccionIIa);
      $stmt->bindValue(':anio',$anioCalculo);
      $stmt->execute();
      $row_limite = $stmt->fetch();

      $Limite_inferior = $row_limite['Limite_inferior'];
      $excedente_limite_inferior = number_format($FraccionIIa - $Limite_inferior, 2, '.', '');
      $porcentaje_tabla = $row_limite['Porcentaje_sobre_limite_inferior'];
      $impuesto_marginal = number_format($excedente_limite_inferior * ($porcentaje_tabla/100), 2, '.', '');
      $cuota_fija = $row_limite['Cuota_fija'];

      $stmt = $conn->prepare("SELECT * FROM subsidio_empleo WHERE IngresoMinimo <= :ingresominimo AND IngresoMaximo >= :ingresomaximo AND Anio = :anio");
      $stmt->bindValue(':ingresominimo',$FraccionIIa);
      $stmt->bindValue(':ingresomaximo',$FraccionIIa);
      $stmt->bindValue(':anio',$anioCalculo);
      $stmt->execute();
      $row_subsidio = $stmt->fetch();
      $subsidioMensual = $row_subsidio['SubsidioMensual'];

      $FraccionII = number_format($impuesto_marginal + $cuota_fija - $subsidioMensual, 2, '.', '');
      $FraccionIII  = number_format($FraccionII - $ISRSalario, 2, '.', '');
      $FraccionV = number_format( ($FraccionIII / $FraccionI) * 100, 2, '.', '');
      $FraccionIV =  number_format($aguinaldo_gravado * ($FraccionV / 100), 2, '.', ''); //ISRAguinaldo
      $ISRAguinaldo = $FraccionIV;

      $AguinaldoPagar = number_format($aguinaldo - $FraccionIV, 2, '.', '');

    }

    return array($ISRAguinaldo, $AguinaldoPagar);
}


function calculoISRAguinaldoMostrar($tipoCalculoISR, $sueldoMensual, $aguinaldo_gravado, $aguinaldo, $anioCalculo){

      require($GLOBALS['rutaFuncion'].'../../include/db-conn.php');

      $ingreso_gravado = $sueldoMensual + $aguinaldo_gravado;

      //calculo del isr del salario
      $stmt = $conn->prepare("SELECT * FROM pagos_provisionales_mensuales WHERE Limite_inferior <= :impuestogravablemin AND Limite_superior >= :impuestogravablesup AND Anio = :anio");
      $stmt->bindValue(':impuestogravablemin',$sueldoMensual);
      $stmt->bindValue(':impuestogravablesup',$sueldoMensual);
      $stmt->bindValue(':anio',$anioCalculo);
      $stmt->execute();
      $row_limite = $stmt->fetch();

      $Limite_inferior = $row_limite['Limite_inferior'];
      $excedente_limite_inferior = number_format($sueldoMensual - $Limite_inferior, 2, '.', '');
      $porcentaje_tabla = $row_limite['Porcentaje_sobre_limite_inferior'];
      $impuesto_marginal = number_format($excedente_limite_inferior * ($porcentaje_tabla/100), 2, '.', '');
      $cuota_fija = $row_limite['Cuota_fija'];

      $stmt = $conn->prepare("SELECT * FROM subsidio_empleo WHERE IngresoMinimo <= :ingresominimo AND IngresoMaximo >= :ingresomaximo AND Anio = :anio");
      $stmt->bindValue(':ingresominimo',$sueldoMensual);
      $stmt->bindValue(':ingresomaximo',$sueldoMensual);
      $stmt->bindValue(':anio',$anioCalculo);
      $stmt->execute();
      $row_subsidio = $stmt->fetch();
      $subsidioMensual = $row_subsidio['SubsidioMensual'];

      $ISRSalario = number_format($impuesto_marginal + $cuota_fija - $subsidioMensual, 2, '.', '');

    //calculo segun ISR
    if($tipoCalculoISR == 1){

      //calculo del isr del salario mas aguinaldo
      $stmt = $conn->prepare("SELECT * FROM pagos_provisionales_mensuales WHERE Limite_inferior <= :impuestogravablemin AND Limite_superior >= :impuestogravablesup AND Anio = :anio");
      $stmt->bindValue(':impuestogravablemin',$ingreso_gravado);
      $stmt->bindValue(':impuestogravablesup',$ingreso_gravado);
      $stmt->bindValue(':anio',$anioCalculo);
      $stmt->execute();
      $row_limite = $stmt->fetch();

      $Limite_inferior_II = $row_limite['Limite_inferior'];
      $excedente_limite_inferior_II = number_format($ingreso_gravado - $Limite_inferior_II, 2, '.', '');
      $porcentaje_tabla_II = $row_limite['Porcentaje_sobre_limite_inferior'];
      $impuesto_marginal_II = number_format($excedente_limite_inferior_II * ($porcentaje_tabla_II/100), 2, '.', '');
      $cuota_fija_II = $row_limite['Cuota_fija'];

      $stmt = $conn->prepare("SELECT * FROM subsidio_empleo WHERE IngresoMinimo <= :ingresominimo AND IngresoMaximo >= :ingresomaximo AND Anio = :anio");
      $stmt->bindValue(':ingresominimo',$ingreso_gravado);
      $stmt->bindValue(':ingresomaximo',$ingreso_gravado);
      $stmt->bindValue(':anio',$anioCalculo);
      $stmt->execute();
      $row_subsidio = $stmt->fetch();
      $subsidioMensual_II = $row_subsidio['SubsidioMensual'];

      $ISRSalarioAguinaldo = number_format($impuesto_marginal_II + $cuota_fija_II - $subsidioMensual_II, 2, '.', '');
      
      $ISRAguinaldo = number_format($ISRSalarioAguinaldo - $ISRSalario, 2, '.', '');
      $AguinaldoPagar = number_format($aguinaldo - $ISRAguinaldo, 2, '.', '');


      $calculoMostrar = '
                    <table class="table table-sm" id="calculoISRtabla">
                        <thead class="text-center header-color">
                          <tr>
                            <th></th>
                            <th>Concepto</th>
                            <th>Sueldo</th>
                            <th></th>
                            <th width="120px">Sueldo + Aguinaldo</th>
                          </tr>
                        </thead>
                        <tbody>                      
                          <tr class="text-center">
                            <td></td>
                            <td>Ingreso gravado</td>
                            <td>'.number_format($sueldoMensual, 2, '.', ',').'</td>
                            <td></td>
                            <td>'.number_format($ingreso_gravado, 2, '.', ',').'</td>
                          </tr>
                          <tr class="text-center">
                            <td>(-)</td>
                            <td>Límite inferior tarifa ISR mensual</td>
                            <td>'.number_format($Limite_inferior, 2, '.', ',').'</td>
                            <td></td>
                            <td>'.number_format($Limite_inferior_II, 2, '.', ',').'</td>
                          </tr>
                          <tr class="text-center">
                            <td>(=)</td>
                            <td>Excedente</td>
                            <td>'.number_format($excedente_limite_inferior, 2, '.', ',').'</td>
                            <td></td>
                            <td>'.number_format($excedente_limite_inferior_II, 2, '.', ',').'</td>
                          </tr>
                          <tr class="text-center">
                            <td>(X)</td>
                            <td>Tasa</td>
                            <td>'.number_format($porcentaje_tabla, 2, '.', ',').'</td>
                            <td></td>
                            <td>'.number_format($porcentaje_tabla_II, 2, '.', ',').'</td>
                          </tr>
                          <tr class="text-center">
                            <td>(=)</td>
                            <td>Impuesto marginal</td>
                            <td>'.number_format($impuesto_marginal, 2, '.', ',').'</td>
                            <td></td>
                            <td>'.number_format($impuesto_marginal_II, 2, '.', ',').'</td>
                          </tr>
                          <tr class="text-center">
                            <td>(+)</td>
                            <td>Cuota fija</td>
                            <td>'.number_format($cuota_fija, 2, '.', ',').'</td>
                            <td></td>
                            <td>'.number_format($cuota_fija_II, 2, '.', ',').'</td>
                          </tr>
                          <tr class="text-center">
                            <td>(-)</td>
                            <td>Subsidio</td>
                            <td>'.number_format($subsidioMensual, 2, '.', ',').'</td>
                            <td></td>
                            <td>'.number_format($subsidioMensual_II, 2, '.', ',').'</td>
                          </tr>
                          <tr class="text-center">
                            <td>(=)</td>
                            <td>ISR</td>
                            <td>'.number_format($ISRSalario, 2, '.', ',').'</td>
                            <td></td>
                            <td>'.number_format($ISRSalarioAguinaldo, 2, '.', ',').'</td>
                          </tr>
                          <tr class="text-center">
                            <td>(=)</td>
                            <td>ISR Aguinaldo</td>
                            <td></td>
                            <td></td>
                            <td>'.number_format($ISRAguinaldo, 2, '.', ',').'</td>
                          </tr>
                        </tbody>
                    </table>
                </div>';

    }
    //calculo segun RISR(Reglamento)
    if($tipoCalculoISR == 2){

      $FraccionI = number_format(($aguinaldo_gravado/365) * 30.4, 2, '.', '');
      $FraccionIIa = number_format($sueldoMensual + $FraccionI, 2, '.', '');

      //calculo del isr Fraccion II
      $stmt = $conn->prepare("SELECT * FROM pagos_provisionales_mensuales WHERE Limite_inferior <= :impuestogravablemin AND Limite_superior >= :impuestogravablesup AND Anio = :anio");
      $stmt->bindValue(':impuestogravablemin',$FraccionIIa);
      $stmt->bindValue(':impuestogravablesup',$FraccionIIa);
      $stmt->bindValue(':anio',$anioCalculo);
      $stmt->execute();
      $row_limite = $stmt->fetch();

      $Limite_inferior_II = $row_limite['Limite_inferior'];
      $excedente_limite_inferior_II = number_format($FraccionIIa - $Limite_inferior_II, 2, '.', '');
      $porcentaje_tabla_II = $row_limite['Porcentaje_sobre_limite_inferior'];
      $impuesto_marginal_II = number_format($excedente_limite_inferior_II * ($porcentaje_tabla_II/100), 2, '.', '');
      $cuota_fija_II = $row_limite['Cuota_fija'];

      $stmt = $conn->prepare("SELECT * FROM subsidio_empleo WHERE IngresoMinimo <= :ingresominimo AND IngresoMaximo >= :ingresomaximo AND Anio = :anio");
      $stmt->bindValue(':ingresominimo',$FraccionIIa);
      $stmt->bindValue(':ingresomaximo',$FraccionIIa);
      $stmt->bindValue(':anio',$anioCalculo);
      $stmt->execute();
      $row_subsidio = $stmt->fetch();
      $subsidioMensual_II = $row_subsidio['SubsidioMensual'];

      $FraccionII = number_format($impuesto_marginal_II + $cuota_fija_II - $subsidioMensual_II, 2, '.', '');
      $FraccionIII  = number_format($FraccionII - $ISRSalario, 2, '.', '');
      $FraccionV = number_format( ($FraccionIII / $FraccionI) * 100, 2, '.', '');
      $FraccionIV =  number_format($aguinaldo_gravado * ($FraccionV / 100), 2, '.', ''); //ISRAguinaldo
      $ISRAguinaldo = $FraccionIV;

      $AguinaldoPagar = number_format($aguinaldo - $FraccionIV, 2, '.', '');


      $calculoMostrar = '
                    <table class="table table-sm" id="calculoISRtabla">
                        <thead class="text-center header-color">
                          <tr>
                            <th></th>
                            <th>Concepto</th>
                            <th>Importe</th>
                            <th></th>
                            <th width="120px">Fracción II</th>
                          </tr>
                        </thead>
                        <tbody>                      
                          <tr class="text-center">
                            <td></td>
                            <td>Ingreso gravado</td>
                            <td>'.number_format($sueldoMensual, 2, '.', ',').'</td>
                            <td></td>
                            <td>'.number_format($FraccionIIa, 2, '.', ',').'</td>
                          </tr>
                          <tr class="text-center">
                            <td>(-)</td>
                            <td>Límite inferior tarifa ISR mensual</td>
                            <td>'.number_format($Limite_inferior, 2, '.', ',').'</td>
                            <td></td>
                            <td>'.number_format($Limite_inferior_II, 2, '.', ',').'</td>
                          </tr>
                          <tr class="text-center">
                            <td>(=)</td>
                            <td>Excedente</td>
                            <td>'.number_format($excedente_limite_inferior, 2, '.', ',').'</td>
                            <td></td>
                            <td>'.number_format($excedente_limite_inferior_II, 2, '.', ',').'</td>
                          </tr>
                          <tr class="text-center">
                            <td>(X)</td>
                            <td>Tasa</td>
                            <td>'.number_format($porcentaje_tabla, 2, '.', ',').'</td>
                            <td></td>
                            <td>'.number_format($porcentaje_tabla_II, 2, '.', ',').'</td>
                          </tr>
                          <tr class="text-center">
                            <td>(=)</td>
                            <td>Impuesto marginal</td>
                            <td>'.number_format($impuesto_marginal, 2, '.', ',').'</td>
                            <td></td>
                            <td>'.number_format($impuesto_marginal_II, 2, '.', ',').'</td>
                          </tr>
                          <tr class="text-center">
                            <td>(+)</td>
                            <td>Cuota fija</td>
                            <td>'.number_format($cuota_fija, 2, '.', ',').'</td>
                            <td></td>
                            <td>'.number_format($cuota_fija_II, 2, '.', ',').'</td>
                          </tr>
                          <tr class="text-center">
                            <td>(-)</td>
                            <td>Subsidio</td>
                            <td>'.number_format($subsidioMensual, 2, '.', ',').'</td>
                            <td></td>
                            <td>'.number_format($subsidioMensual_II, 2, '.', ',').'</td>
                          </tr>
                          <tr class="text-center">
                            <td>(=)</td>
                            <td>ISR</td>
                            <td>'.number_format($ISRSalario, 2, '.', ',').'</td>
                            <td></td>
                            <td>'.number_format($FraccionII, 2, '.', ',').'</td>
                          </tr>
                          <tr class="text-center">
                            <td>(=)</td>
                            <td>Diferencia</td>
                            <td></td>
                            <td></td>
                            <td>'.number_format($FraccionIII, 2, '.', ',').'</td>
                          </tr>
                          <tr class="text-center">
                            <td>(=)</td>
                            <td>Tasa</td>
                            <td></td>
                            <td></td>
                            <td>'.number_format($FraccionV, 2, '.', ',').'</td>
                          </tr>
                          <tr class="text-center">
                            <td>(X)</td>
                            <td>Aguinaldo gravado</td>
                            <td></td>
                            <td></td>
                            <td>'.number_format($aguinaldo_gravado, 2, '.', ',').'</td>
                          </tr>
                          <tr class="text-center">
                            <td>(=)</td>
                            <td>ISR Aguinaldo</td>
                            <td></td>
                            <td></td>
                            <td>'.number_format($ISRAguinaldo, 2, '.', ',').'</td>
                          </tr>
                        </tbody>
                    </table>
                </div>';

    }

    return $calculoMostrar;
}


function calculoISRIndemnizacionMostrar($idEmpleado,$indeminizacion, $anio){

  require($GLOBALS['rutaFuncion'].'../../include/db-conn.php');

  $salario = getSalario_Dias($idEmpleado);
  $base = $salario[5];
  $salario_mensual = $base;
  $periodo = $salario[1];

  $factor_mes = getFactorMes();

  $stmt = $conn->prepare("SELECT * FROM pagos_provisionales_mensuales WHERE Limite_inferior <= :impuestogravablemin AND Limite_superior >= :impuestogravablesup AND Anio = :anio");
  $stmt->bindValue(':impuestogravablemin',$base);
  $stmt->bindValue(':impuestogravablesup',$base);
  $stmt->bindValue(':anio',$anio);
  $stmt->execute();
  $row_limite = $stmt->fetch();

  $Limite_inferior = $row_limite['Limite_inferior'];
  $excedente_limite_inferior = number_format($base - $Limite_inferior, 2, '.', '');
  $porcentaje_tabla = $row_limite['Porcentaje_sobre_limite_inferior'];
  $impuesto_marginal = number_format($excedente_limite_inferior * ($porcentaje_tabla/100), 2, '.', '');
  $cuota_fija = $row_limite['Cuota_fija'];
  $ISRDeterminado = $impuesto_marginal + $cuota_fija;

  $stmt = $conn->prepare("SELECT * FROM subsidio_empleo WHERE IngresoMinimo <= :ingresominimo AND IngresoMaximo >= :ingresomaximo AND Anio = :anio");
  $stmt->bindValue(':ingresominimo',$base);
  $stmt->bindValue(':ingresomaximo',$base);
  $stmt->bindValue(':anio',$anio);
  $stmt->execute();
  $row_subsidio = $stmt->fetch();
  $subsidioMensual = $row_subsidio['SubsidioMensual'];
  $subsidioAplicable = number_format(($subsidioMensual / $factor_mes) * $periodo, 2, '.', ''); 

  $ISRRetenido = number_format($ISRDeterminado, 2, '.', '');      
  $ISRDiario = number_format((($ISRRetenido / $factor_mes) * $periodo) , 2, '.', '');

  if($subsidioAplicable > $ISRDiario){
    $SAE_pagar =  number_format($subsidioAplicable - $ISRDiario, 2, '.', '');
    $ISR = 0.00;
    $tipo = 2;
    $titulo = "SAE sueldo";
    $impuesto_aplicable = $SAE_Pagar;
  }
  else{
    $ISR = number_format($ISRDiario - $subsidioAplicable, 2, '.', '');
    $SAE_Pagar = 0.00;
    $tipo = 1;
    $titulo = "ISR sueldo";
    $impuesto_aplicable = $ISR;
  }


  if($ISR > 0){
  //isr es mayor que cero  
    $tasa_isr_indemnizacion = bcdiv($ISR / $salario_mensual,1,5);
    $titulo_indemnizacion = "ISR Indemnización";
    $ISRIndemnizacion = bcdiv($indeminizacion * $tasa_isr_indemnizacion,1,2);
  }
  else{
  //sae a pagar es mayor que cero
    $tasa_isr_indemnizacion = bcdiv($SAE_Pagar / $salario_mensual,1,5);
    $titulo_indemnizacion = "SAE Indemnización";
    $ISRIndemnizacion = bcdiv($indeminizacion * $tasa_isr_indemnizacion,1,2);
  }

  $calculoMostrar = '
                    <table class="table table-sm" id="calculoISRtabla">
                        <thead class="text-center header-color">
                          <tr>
                            <th></th>
                            <th>Concepto</th>
                            <th>Importe</th>
                          </tr>
                        </thead>
                        <tbody>                      
                          <tr class="text-center">
                            <td></td>
                            <td>Ingreso gravado</td>
                            <td>'.number_format($base, 2, '.', ',').'</td>
                          </tr>
                          <tr class="text-center">
                            <td>(-)</td>
                            <td>Límite inferior tarifa ISR mensual</td>
                            <td>'.number_format($Limite_inferior, 2, '.', ',').'</td>
                          </tr>
                          <tr class="text-center">
                            <td>(=)</td>
                            <td>Excedente</td>
                            <td>'.number_format($excedente_limite_inferior, 2, '.', ',').'</td>
                          </tr>
                          <tr class="text-center">
                            <td>(X)</td>
                            <td>Tasa</td>
                            <td>'.number_format($porcentaje_tabla, 2, '.', ',').'</td>
                          </tr>
                          <tr class="text-center">
                            <td>(=)</td>
                            <td>Impuesto marginal</td>
                            <td>'.number_format($impuesto_marginal, 2, '.', ',').'</td>
                          </tr>
                          <tr class="text-center">
                            <td>(+)</td>
                            <td>Cuota fija</td>
                            <td>'.number_format($cuota_fija, 2, '.', ',').'</td>
                          </tr>
                          <tr class="text-center">
                            <td>(-)</td>
                            <td>Subsidio</td>
                            <td>'.number_format($subsidioAplicable, 2, '.', ',').'</td>
                          </tr>
                          <tr class="text-center">
                            <td>(=)</td>
                            <td>'.$titulo.'</td>
                            <td>'.number_format($impuesto_aplicable, 2, '.', ',').'</td>
                          </tr>
                          <tr class="text-center">
                            <td>(/)</td>
                            <td>Sueldo mensual</td>
                            <td>'.number_format($salario_mensual, 2, '.', ',').'</td>
                          </tr>
                          <tr class="text-center">
                            <td>(=)</td>
                            <td>Tasa indemnización</td>
                            <td>'.number_format($tasa_isr_indemnizacion, 2, '.', ',').'</td>
                          </tr>
                          <tr class="text-center">
                            <td>(*)</td>
                            <td>Indemnización</td>
                            <td>'.number_format($indeminizacion, 2, '.', ',').'</td>
                          </tr>
                          <tr class="text-center">
                            <td>(=)</td>
                            <td>'.$titulo_indemnizacion.'</td>
                            <td>'.number_format($ISRIndemnizacion, 2, '.', ',').'</td>
                          </tr>
                        </tbody>
                    </table>';
  
  return $calculoMostrar;
}

//el calculo de Salario devengados para finiquitos y liquidaciones.
function calcularIMSS($idEmpleado, $diasTrabajados){
    require($GLOBALS['rutaFuncion'].'../../include/db-conn.php');

    $UMA = getUMA(0);
    $SBC = getSBC($idEmpleado);
    $datosSalario = getSalario_Dias($idEmpleado);
    $salarioBaseDiario = $datosSalario[2];
    $salarioMinimo = getSalarioMinimo();
    $riesgo_trabajo = getRiesgoTrabajo();

    $idSucursal = $datosSalario[6];
    $stmt = $conn->prepare('SELECT zona_salario_minimo FROM sucursales WHERE id = '.$idSucursal.'  AND empresa_id = '.$_SESSION['IDEmpresa']);
    $stmt->execute();
    $row_zona = $stmt->fetch();
    $zona_salario = $row_zona['zona_salario_minimo'];

    if($zona_salario == 1){
      $salario_minimo = $salarioMinimo[0];
    }
    else{
      $salario_minimo = $salarioMinimo[1];
    }

    /*CUOTAS MENSUALES*/
    $stmt = $conn->prepare("SELECT * FROM cuotas_mensuales");
    $stmt->execute();
    $row_cuotasmensuales = $stmt->fetchAll();

    $impuestoCF = number_format($UMA * 7 * ($row_cuotasmensuales[0]['cantidad']/100), 2, '.', '');

    if($SBC > $UMA*3){
      $impuestoExcPat = number_format(($SBC - ($UMA*3)) * $diasTrabajados * ($row_cuotasmensuales[1]['cantidad']/100), 2, '.', '');
      $impuestoExcObr = number_format(($SBC - ($UMA*3)) * $diasTrabajados * ($row_cuotasmensuales[2]['cantidad']/100), 2, '.', '');
    }
    else {
      $impuestoExcPat = 0.00;
      $impuestoExcObr = 0.00;
    }

    $impuestoPDPat = number_format($SBC * $diasTrabajados * ($row_cuotasmensuales[3]['cantidad']/100), 2, '.', '');
    $impuestoPDObr = number_format($SBC * $diasTrabajados * ($row_cuotasmensuales[4]['cantidad']/100), 2, '.', '');
    $impuestoGMPat = number_format($SBC * $diasTrabajados * ($row_cuotasmensuales[5]['cantidad']/100), 2, '.', '');
    $impuestoGMObr = number_format($SBC * $diasTrabajados * ($row_cuotasmensuales[6]['cantidad']/100), 2, '.', '');
    $impuestoRT = number_format($SBC * $diasTrabajados * ($riesgo_trabajo/100), 2, '.', '');
    $impuestoIVPat = number_format($SBC * $diasTrabajados * ($row_cuotasmensuales[8]['cantidad']/100), 2, '.', '');
    $impuestoIVObr = number_format($SBC * $diasTrabajados * ($row_cuotasmensuales[9]['cantidad']/100), 2, '.', '');
    $impuestoGPS = number_format($SBC * $diasTrabajados * ($row_cuotasmensuales[10]['cantidad']/100), 2, '.', '');
    $sumapatronalMensual = $impuestoCF + $impuestoExcPat +  $impuestoPDPat + $impuestoGMPat + $impuestoRT + $impuestoIVPat + $impuestoGPS;
    $sumaobreraMensual = $impuestoExcObr + $impuestoPDObr + $impuestoGMObr + $impuestoIVObr;
    $cuota_mensual = $sumapatronalMensual + $sumaobreraMensual;

    //echo "impuestoExcPat: ".$impuestoExcPat." impuestoExcObr: ".$impuestoExcObr." impuestoPDPat: ".$impuestoPDPat." impuestoPDObr: ".$impuestoPDObr." impuestoGMPat: ".$impuestoGMPat." impuestoGMObr: ".$impuestoGMObr." impuestoRT: ".$impuestoRT." impuestoIVPat: ". $impuestoIVPat." impuestoIVObr: ".$impuestoIVObr."  impuestoGPS: ".$impuestoGPS. "  sumapatronalMensual: ".$sumapatronalMensual."  sumaobreraMensual: ".$sumaobreraMensual."  cuota_mensual: ".$cuota_mensual;
    /*CUOTAS MENSUALES*/

    /*CUOTAS BIMESTRALES*/
    $stmt = $conn->prepare("SELECT * FROM cuotas_bimestrales");
    $stmt->execute();
    $row_cuotasbimestrales = $stmt->fetchAll();

    $impuestoRETIRO = number_format($SBC * $diasTrabajados * ($row_cuotasbimestrales[0]['cantidad']/100),2, '.', '');
    $impuestoCYVPat = number_format($SBC * $diasTrabajados * ($row_cuotasbimestrales[1]['cantidad']/100),2, '.', '');
    $impuestoCYVObr = number_format($SBC * $diasTrabajados * ($row_cuotasbimestrales[2]['cantidad']/100),2, '.', '');
    $impuestoAPPatron = number_format($SBC * $diasTrabajados * ($row_cuotasbimestrales[3]['cantidad']/100),2, '.', '');

    $sumapatronalBimestral = $impuestoRETIRO + $impuestoCYVPat +  $impuestoAPPatron;
    $sumaobreraBimestral = $impuestoCYVObr;
    $cuota_Bimestral = $sumapatronalBimestral + $sumaobreraBimestral;

    if($salarioBaseDiario <= $salario_minimo)
    {
      $cuota_obrero = 0.00;
    }
    else{
      $cuota_obrero = $sumaobreraMensual + $sumaobreraBimestral; 
    }
    /*CUOTAS BIMESTRALES*/

    return $cuota_obrero;
}


//tipo 1 es para ejecutarse desde el finiquito, y 2 para ejecutarse desde el 
function calcularIMSSMostrar($idEmpleado, $diasTrabajados, $tipo){
    require($GLOBALS['rutaFuncion'].'../../include/db-conn.php');

    $UMA = getUMA();
    $SBC = getSBC($idEmpleado);
    $datosSalario = getSalario_Dias($idEmpleado);
    $salarioBaseDiario = $datosSalario[2];
    $salarioMinimo = getSalarioMinimo();
    $riesgo_trabajo = getRiesgoTrabajo();

    $idSucursal = $datosSalario[6];
    $stmt = $conn->prepare('SELECT zona_salario_minimo FROM sucursales WHERE id = '.$idSucursal.'  AND empresa_id = '.$_SESSION['IDEmpresa']);
    $stmt->execute();
    $row_zona = $stmt->fetch();
    $zona_salario = $row_zona['zona_salario_minimo'];

    if($zona_salario == 1){
      $salario_minimo = $salarioMinimo[0];
    }
    else{
      $salario_minimo = $salarioMinimo[1];
    }

    if($tipo == 2){
      //Calculo de conceptos para el total de calculo de impuestos
      $stmt = $conn->prepare('SELECT 1 as tipo, tipo_concepto, importe, importe_exento, exento, dias FROM detalle_nomina_percepcion_empleado WHERE empleado_id = :empleado_id AND nomina_empleado_id = :nomina_empleado_id
                              UNION ALL
                              SELECT 2 as tipo, tipo_concepto, importe, 0.00 as importe_exento, exento, dias FROM detalle_nomina_deduccion_empleado WHERE empleado_id = :empleado_id2 AND nomina_empleado_id = :nomina_empleado_id2');
      $stmt->bindValue(':empleado_id', $idEmpleado);
      $stmt->bindValue(':nomina_empleado_id', $idNominaEmpleado);
      $stmt->bindValue(':empleado_id2', $idEmpleado);
      $stmt->bindValue(':nomina_empleado_id2', $idNominaEmpleado);
      $stmt->execute();
      $conceptos = $stmt->rowCount();
    }

    /*CUOTAS MENSUALES*/
    $stmt = $conn->prepare("SELECT * FROM cuotas_mensuales");
    $stmt->execute();
    $row_cuotasmensuales = $stmt->fetchAll();

    $impuestoCF = number_format($UMA * 7 * ($row_cuotasmensuales[0]['cantidad']/100), 2, '.', '');

    if($SBC > $UMA*3){
      $impuestoExcPat = number_format(($SBC - ($UMA*3)) * $diasTrabajados * ($row_cuotasmensuales[1]['cantidad']/100), 2, '.', '');
      $impuestoExcObr = number_format(($SBC - ($UMA*3)) * $diasTrabajados * ($row_cuotasmensuales[2]['cantidad']/100), 2, '.', '');
    }
    else {
      $impuestoExcPat = 0.00;
      $impuestoExcObr = 0.00;
    }

    $impuestoPDPat = number_format($SBC * $diasTrabajados * ($row_cuotasmensuales[3]['cantidad']/100), 2, '.', '');
    $impuestoPDObr = number_format($SBC * $diasTrabajados * ($row_cuotasmensuales[4]['cantidad']/100), 2, '.', '');
    $impuestoGMPat = number_format($SBC * $diasTrabajados * ($row_cuotasmensuales[5]['cantidad']/100), 2, '.', '');
    $impuestoGMObr = number_format($SBC * $diasTrabajados * ($row_cuotasmensuales[6]['cantidad']/100), 2, '.', '');
    $impuestoRT = number_format($SBC * $diasTrabajados * ($riesgo_trabajo/100), 2, '.', '');
    $impuestoIVPat = number_format($SBC * $diasTrabajados * ($row_cuotasmensuales[8]['cantidad']/100), 2, '.', '');
    $impuestoIVObr = number_format($SBC * $diasTrabajados * ($row_cuotasmensuales[9]['cantidad']/100), 2, '.', '');
    $impuestoGPS = number_format($SBC * $diasTrabajados * ($row_cuotasmensuales[10]['cantidad']/100), 2, '.', '');
    $sumapatronalMensual = $impuestoCF + $impuestoExcPat +  $impuestoPDPat + $impuestoGMPat + $impuestoRT + $impuestoIVPat + $impuestoGPS;
    $sumaobreraMensual = $impuestoExcObr + $impuestoPDObr + $impuestoGMObr + $impuestoIVObr;
    $cuota_mensual = $sumapatronalMensual + $sumaobreraMensual;

    //echo "impuestoExcPat: ".$impuestoExcPat." impuestoExcObr: ".$impuestoExcObr." impuestoPDPat: ".$impuestoPDPat." impuestoPDObr: ".$impuestoPDObr." impuestoGMPat: ".$impuestoGMPat." impuestoGMObr: ".$impuestoGMObr." impuestoRT: ".$impuestoRT." impuestoIVPat: ". $impuestoIVPat." impuestoIVObr: ".$impuestoIVObr."  impuestoGPS: ".$impuestoGPS. "  sumapatronalMensual: ".$sumapatronalMensual."  sumaobreraMensual: ".$sumaobreraMensual."  cuota_mensual: ".$cuota_mensual;
    /*CUOTAS MENSUALES*/

    /*CUOTAS BIMESTRALES*/
    $stmt = $conn->prepare("SELECT * FROM cuotas_bimestrales");
    $stmt->execute();
    $row_cuotasbimestrales = $stmt->fetchAll();

    $impuestoRETIRO = number_format($SBC * $diasTrabajados * ($row_cuotasbimestrales[0]['cantidad']/100),2, '.', '');
    $impuestoCYVPat = number_format($SBC * $diasTrabajados * ($row_cuotasbimestrales[1]['cantidad']/100),2, '.', '');
    $impuestoCYVObr = number_format($SBC * $diasTrabajados * ($row_cuotasbimestrales[2]['cantidad']/100),2, '.', '');
    $impuestoAPPatron = number_format($SBC * $diasTrabajados * ($row_cuotasbimestrales[3]['cantidad']/100),2, '.', '');

    $sumapatronalBimestral = $impuestoRETIRO + $impuestoCYVPat +  $impuestoAPPatron;
    $sumaobreraBimestral = $impuestoCYVObr;
    $cuota_Bimestral = $sumapatronalBimestral + $sumaobreraBimestral;

    if($salarioBaseDiario <= $salario_minimo)
    {
      $cuota_obrero = 0.00;
    }
    else{
      $cuota_obrero = $sumaobreraMensual + $sumaobreraBimestral; 
    }
    /*CUOTAS BIMESTRALES*/


    $mostrarIMSS = '
                  <table class="table table-sm" id="calculoIMSStabla">
                      <thead class="text-center header-color">
                        <tr>
                          <th>%</th>
                          <th>Concepto</th>
                          <th>Importe</th>
                        </tr>
                      </thead>
                      <tbody>                      
                        <tr class="text-center">
                          <td></td>
                          <td>SBC</td>
                          <td>'.number_format($SBC, 2, '.', ',').'</td>
                        </tr>
                        <tr class="text-center">
                          <td></td>
                          <td></td>
                          <td></td>
                        </tr>
                        <tr class="text-center">
                          <td colspan="3">CUOTAS MENSUALES</td>
                        </tr>
                        <tr class="text-center">
                          <td>'.$row_cuotasmensuales[0]['cantidad'].'</td>
                          <td>C.F.</td>
                          <td>'.number_format($impuestoCF, 2, '.', ',').'</td>
                        </tr>
                        <tr class="text-center">
                          <td>'.$row_cuotasmensuales[1]['cantidad'].'</td>
                          <td>Exc. Pat.</td>
                          <td>'.number_format($impuestoExcPat, 2, '.', ',').'</td>
                        </tr>
                        <tr class="text-center">
                          <td>'.$row_cuotasmensuales[2]['cantidad'].'</td>
                          <td>Exc. Obr.</td>
                          <td>'.number_format($impuestoExcObr, 2, '.', ',').'</td>
                        </tr>
                        <tr class="text-center">
                          <td>'.$row_cuotasmensuales[3]['cantidad'].'</td>
                          <td>P.D. Pat.</td>
                          <td>'.number_format($impuestoPDPat, 2, '.', ',').'</td>
                        </tr>
                        <tr class="text-center">
                          <td>'.$row_cuotasmensuales[4]['cantidad'].'</td>
                          <td>P.D. Obr.</td>
                          <td>'.number_format($impuestoPDObr, 2, '.', ',').'</td>
                        </tr>
                        <tr class="text-center">
                          <td>'.$row_cuotasmensuales[5]['cantidad'].'</td>
                          <td>G.M.P. Pat.</td>
                          <td>'.number_format($impuestoGMPat, 2, '.', ',').'</td>
                        </tr>
                        <tr class="text-center">
                          <td>'.$row_cuotasmensuales[6]['cantidad'].'</td>
                          <td>G.M.P. Obr.</td>
                          <td>'.number_format($impuestoGMObr, 2, '.', ',').'</td>
                        </tr>
                        <tr class="text-center">
                          <td>'.$row_cuotasmensuales[7]['cantidad'].'</td>
                          <td>R.T.</td>
                          <td>'.number_format($impuestoRT, 2, '.', ',').'</td>
                        </tr>
                        <tr class="text-center">
                          <td>'.$row_cuotasmensuales[8]['cantidad'].'</td>
                          <td>I.V. Pat.</td>
                          <td>'.number_format($impuestoIVPat, 2, '.', ',').'</td>
                        </tr>
                        <tr class="text-center">
                          <td>'.$row_cuotasmensuales[9]['cantidad'].'</td>
                          <td>I.V. Obr.</td>
                          <td>'.number_format($impuestoIVObr, 2, '.', ',').'</td>
                        </tr>
                        <tr class="text-center">
                          <td>'.$row_cuotasmensuales[10]['cantidad'].'</td>
                          <td>GPS</td>
                          <td>'.number_format($impuestoGPS, 2, '.', ',').'</td>
                        </tr>
                        <tr class="text-center">
                          <td>(=)</td>
                          <td>CUOTA PATRONAL</td>
                          <td>'.number_format($sumapatronalMensual, 2, '.', ',').'</td>
                        </tr>
                        <tr class="text-center">
                          <td>(=)</td>
                          <td>CUOTA OBRERA</td>
                          <td>'.number_format($sumaobreraMensual, 2, '.', ',').'</td>
                        </tr>
                        <tr class="text-center">
                          <td>(=)</td>
                          <td>TOTAL MENSUAL</td>
                          <td>'.number_format($cuota_mensual, 2, '.', ',').'</td>
                        </tr>
                        <tr class="text-center">
                          <td colspan="3"></td>
                        </tr>
                        <tr class="text-center">
                          <td colspan="3"></td>
                        </tr>
                        <tr class="text-center">
                          <td colspan="3">CUOTAS BIMESTRALES</td>
                        </tr>
                        <tr class="text-center">
                          <td>'.$row_cuotasbimestrales[0]['cantidad'].'</td>
                          <td>Retiro</td>
                          <td>'.number_format($impuestoRETIRO, 2, '.', ',').'</td>
                        </tr>
                        <tr class="text-center">
                          <td>'.$row_cuotasbimestrales[1]['cantidad'].'</td>
                          <td>CYV Pat.</td>
                          <td>'.number_format($impuestoCYVPat, 2, '.', ',').'</td>
                        </tr>
                        <tr class="text-center">
                          <td>'.$row_cuotasbimestrales[2]['cantidad'].'</td>
                          <td>CYV Obr.</td>
                          <td>'.number_format($impuestoCYVObr, 2, '.', ',').'</td>
                        </tr>
                        <tr class="text-center">
                          <td>'.$row_cuotasbimestrales[3]['cantidad'].'</td>
                          <td>Ap. pat.</td>
                          <td>'.number_format($impuestoAPPatron, 2, '.', ',').'</td>
                        </tr>
                        <tr class="text-center">
                          <td>(=)</td>
                          <td>CUOTA PATRONAL</td>
                          <td>'.number_format($sumapatronalBimestral, 2, '.', ',').'</td>
                        </tr>
                        <tr class="text-center">
                          <td>(=)</td>
                          <td>CUOTA OBRERA</td>
                          <td>'.number_format($sumaobreraBimestral, 2, '.', ',').'</td>
                        </tr>
                        <tr class="text-center">
                          <td colspan="3"></td>
                        </tr>
                        <tr class="text-center">
                          <td>(=)</td>
                          <td>TOTAL CUOTA</td>
                          <td>'.number_format($cuota_obrero, 2, '.', ',').'</td>
                        </tr>
                      </tbody>
                  </table>';

    return $mostrarIMSS;
}

//esto es para la nomina
/*
0 $impuestoCF
1 $impuestoExcPat
2 $impuestoExcObr
3 $impuestoPDPat
4 $impuestoPDObr
5 $impuestoGMPat
6 $impuestoGMObr
7 $impuestoRT
8 $impuestoIVPat
9 $impuestoIVObr
10 $impuestoGPS
11 $sumapatronalMensual
12 $sumaobreraMensual
13 $cuota_mensual 
/// cuotas bimestrales
14 $impuestoRETIRO
15 $impuestoCYVPat
16 $impuestoCYVObr
17 $impuestoAPPatron
18 $sumapatronalBimestral
19 $sumaobreraBimestral
20 $cuota_Bimestral
21 $cuota_obrero
*/
function calcularCuotasIMSS($idEmpleado, $SBC, $diasTrabajados, $fecha){

  require($GLOBALS['rutaFuncion'].'../../include/db-conn.php');
  
  $anioCalculo = calcularAnio($fecha, 1);
  $UMA = getUMA($anioCalculo);
  $riesgo_trabajo = getRiesgoTrabajo();
  $datosSalario = getSalario_Dias($idEmpleado);
  $salarioBaseDiario = $datosSalario[2];
  $salarioMinimo = getSalarioMinimo();

  $idSucursal = $datosSalario[6];
  $stmt = $conn->prepare('SELECT zona_salario_minimo FROM sucursales WHERE id = '.$idSucursal.'  AND empresa_id = '.$_SESSION['IDEmpresa']);
  $stmt->execute();
  $row_zona = $stmt->fetch();
  $zona_salario = $row_zona['zona_salario_minimo'];

  if($zona_salario == 1){
    $salario_minimo = $salarioMinimo[0];
  }
  else{
    $salario_minimo = $salarioMinimo[1];
  }


  /*CUOTAS MENSUALES*/

  $stmt = $conn->prepare("SELECT * FROM cuotas_mensuales");
  $stmt->execute();
  $row_cuotasmensuales = $stmt->fetchAll();

  $impuestoCF = number_format($UMA * 7 * ($row_cuotasmensuales[0]['cantidad']/100), 2, '.', '');

  if($SBC > $UMA*3){
    $impuestoExcPat = number_format(($SBC - ($UMA*3)) * $diasTrabajados * ($row_cuotasmensuales[1]['cantidad']/100), 2, '.', '');
    $impuestoExcObr = number_format(($SBC - ($UMA*3)) * $diasTrabajados * ($row_cuotasmensuales[2]['cantidad']/100), 2, '.', '');
  }
  else {
    $impuestoExcPat = 0.00;
    $impuestoExcObr = 0.00;
  }

  $impuestoPDPat = number_format($SBC * $diasTrabajados * ($row_cuotasmensuales[3]['cantidad']/100), 2, '.', '');
  $impuestoPDObr = number_format($SBC * $diasTrabajados * ($row_cuotasmensuales[4]['cantidad']/100), 2, '.', '');
  $impuestoGMPat = number_format($SBC * $diasTrabajados * ($row_cuotasmensuales[5]['cantidad']/100), 2, '.', '');
  $impuestoGMObr = number_format($SBC * $diasTrabajados * ($row_cuotasmensuales[6]['cantidad']/100), 2, '.', '');
  $impuestoRT = number_format($SBC * $diasTrabajados * ($riesgo_trabajo/100), 2, '.', '');
  $impuestoIVPat = number_format($SBC * $diasTrabajados * ($row_cuotasmensuales[8]['cantidad']/100), 2, '.', '');
  $impuestoIVObr = number_format($SBC * $diasTrabajados * ($row_cuotasmensuales[9]['cantidad']/100), 2, '.', '');
  $impuestoGPS = number_format($SBC * $diasTrabajados * ($row_cuotasmensuales[10]['cantidad']/100), 2, '.', '');
  $sumapatronalMensual = $impuestoCF + $impuestoExcPat +  $impuestoPDPat + $impuestoGMPat + $impuestoRT + $impuestoIVPat + $impuestoGPS;
  $sumaobreraMensual = $impuestoExcObr + $impuestoPDObr + $impuestoGMObr + $impuestoIVObr;
  $cuota_mensual = $sumapatronalMensual + $sumaobreraMensual;

  //echo "impuestoExcPat: ".$impuestoExcPat." impuestoExcObr: ".$impuestoExcObr." impuestoPDPat: ".$impuestoPDPat." impuestoPDObr: ".$impuestoPDObr." impuestoGMPat: ".$impuestoGMPat." impuestoGMObr: ".$impuestoGMObr." impuestoRT: ".$impuestoRT." impuestoIVPat: ". $impuestoIVPat." impuestoIVObr: ".$impuestoIVObr."  impuestoGPS: ".$impuestoGPS. "  sumapatronalMensual: ".$sumapatronalMensual."  sumaobreraMensual: ".$sumaobreraMensual."  cuota_mensual: ".$cuota_mensual;

  /*CUOTAS MENSUALES*/

  /*CUOTAS BIMESTRALES*/
  
  $stmt = $conn->prepare("SELECT * FROM cuotas_bimestrales");
  $stmt->execute();
  $row_cuotasbimestrales = $stmt->fetchAll();

  $impuestoRETIRO = number_format($SBC * $diasTrabajados * ($row_cuotasbimestrales[0]['cantidad']/100),2, '.', '');
  $impuestoCYVPat = number_format($SBC * $diasTrabajados * ($row_cuotasbimestrales[1]['cantidad']/100),2, '.', '');
  $impuestoCYVObr = number_format($SBC * $diasTrabajados * ($row_cuotasbimestrales[2]['cantidad']/100),2, '.', '');
  $impuestoAPPatron = number_format($SBC * $diasTrabajados * ($row_cuotasbimestrales[3]['cantidad']/100),2, '.', '');

  $sumapatronalBimestral = $impuestoRETIRO + $impuestoCYVPat +  $impuestoAPPatron;
  $sumaobreraBimestral = $impuestoCYVObr;
  $cuota_Bimestral = $sumapatronalBimestral + $sumaobreraBimestral;

  if($salarioBaseDiario <= $salario_minimo)
  {
    $cuota_obrero = 0.00;
  }
  else{
    $cuota_obrero = $sumaobreraMensual + $sumaobreraBimestral; 
  }
  
  /*CUOTAS BIMESTRALES*/

  return array($impuestoCF, $impuestoExcPat, $impuestoExcObr, $impuestoPDPat, $impuestoPDObr, $impuestoGMPat, $impuestoGMObr, $impuestoRT, $impuestoIVPat, $impuestoIVObr, $impuestoGPS, $sumapatronalMensual, $sumaobreraMensual, $cuota_mensual, $impuestoRETIRO, $impuestoCYVPat, $impuestoCYVObr, $impuestoAPPatron, $sumapatronalBimestral, $sumaobreraBimestral, $cuota_Bimestral, $cuota_obrero);
}



function calcularCuotasIMSSMostrar($idEmpleado, $SBC, $diasTrabajados, $fecha){

  require($GLOBALS['rutaFuncion'].'../../include/db-conn.php');
  
  $anioCalculo = calcularAnio($fecha, 1);
  $UMA = getUMA($anioCalculo);
  $riesgo_trabajo = getRiesgoTrabajo();
  $datosSalario = getSalario_Dias($idEmpleado);
  $salarioBaseDiario = $datosSalario[2];
  $salarioMinimo = getSalarioMinimo();

  $idSucursal = $datosSalario[6];
  $stmt = $conn->prepare('SELECT zona_salario_minimo FROM sucursales WHERE id = '.$idSucursal.'  AND empresa_id = '.$_SESSION['IDEmpresa']);
  $stmt->execute();
  $row_zona = $stmt->fetch();
  $zona_salario = $row_zona['zona_salario_minimo'];

  if($zona_salario == 1){
    $salario_minimo = $salarioMinimo[0];
  }
  else{
    $salario_minimo = $salarioMinimo[1];
  }


  /*CUOTAS MENSUALES*/

  $stmt = $conn->prepare("SELECT * FROM cuotas_mensuales");
  $stmt->execute();
  $row_cuotasmensuales = $stmt->fetchAll();

  $impuestoCF = number_format($UMA * 7 * ($row_cuotasmensuales[0]['cantidad']/100), 2, '.', '');

  if($SBC > $UMA*3){
    $impuestoExcPat = number_format(($SBC - ($UMA*3)) * $diasTrabajados * ($row_cuotasmensuales[1]['cantidad']/100), 2, '.', '');
    $impuestoExcObr = number_format(($SBC - ($UMA*3)) * $diasTrabajados * ($row_cuotasmensuales[2]['cantidad']/100), 2, '.', '');
  }
  else {
    $impuestoExcPat = 0.00;
    $impuestoExcObr = 0.00;
  }

  $impuestoPDPat = number_format($SBC * $diasTrabajados * ($row_cuotasmensuales[3]['cantidad']/100), 2, '.', '');
  $impuestoPDObr = number_format($SBC * $diasTrabajados * ($row_cuotasmensuales[4]['cantidad']/100), 2, '.', '');
  $impuestoGMPat = number_format($SBC * $diasTrabajados * ($row_cuotasmensuales[5]['cantidad']/100), 2, '.', '');
  $impuestoGMObr = number_format($SBC * $diasTrabajados * ($row_cuotasmensuales[6]['cantidad']/100), 2, '.', '');
  $impuestoRT = number_format($SBC * $diasTrabajados * ($riesgo_trabajo/100), 2, '.', '');
  $impuestoIVPat = number_format($SBC * $diasTrabajados * ($row_cuotasmensuales[8]['cantidad']/100), 2, '.', '');
  $impuestoIVObr = number_format($SBC * $diasTrabajados * ($row_cuotasmensuales[9]['cantidad']/100), 2, '.', '');
  $impuestoGPS = number_format($SBC * $diasTrabajados * ($row_cuotasmensuales[10]['cantidad']/100), 2, '.', '');
  $sumapatronalMensual = $impuestoCF + $impuestoExcPat +  $impuestoPDPat + $impuestoGMPat + $impuestoRT + $impuestoIVPat + $impuestoGPS;
  $sumaobreraMensual = $impuestoExcObr + $impuestoPDObr + $impuestoGMObr + $impuestoIVObr;
  $cuota_mensual = $sumapatronalMensual + $sumaobreraMensual;

  //echo "impuestoExcPat: ".$impuestoExcPat." impuestoExcObr: ".$impuestoExcObr." impuestoPDPat: ".$impuestoPDPat." impuestoPDObr: ".$impuestoPDObr." impuestoGMPat: ".$impuestoGMPat." impuestoGMObr: ".$impuestoGMObr." impuestoRT: ".$impuestoRT." impuestoIVPat: ". $impuestoIVPat." impuestoIVObr: ".$impuestoIVObr."  impuestoGPS: ".$impuestoGPS. "  sumapatronalMensual: ".$sumapatronalMensual."  sumaobreraMensual: ".$sumaobreraMensual."  cuota_mensual: ".$cuota_mensual;

  /*CUOTAS MENSUALES*/

  /*CUOTAS BIMESTRALES*/
  
  $stmt = $conn->prepare("SELECT * FROM cuotas_bimestrales");
  $stmt->execute();
  $row_cuotasbimestrales = $stmt->fetchAll();

  $impuestoRETIRO = number_format($SBC * $diasTrabajados * ($row_cuotasbimestrales[0]['cantidad']/100),2, '.', '');
  $impuestoCYVPat = number_format($SBC * $diasTrabajados * ($row_cuotasbimestrales[1]['cantidad']/100),2, '.', '');
  $impuestoCYVObr = number_format($SBC * $diasTrabajados * ($row_cuotasbimestrales[2]['cantidad']/100),2, '.', '');
  $impuestoAPPatron = number_format($SBC * $diasTrabajados * ($row_cuotasbimestrales[3]['cantidad']/100),2, '.', '');

  $sumapatronalBimestral = $impuestoRETIRO + $impuestoCYVPat +  $impuestoAPPatron;
  $sumaobreraBimestral = $impuestoCYVObr;
  $cuota_Bimestral = $sumapatronalBimestral + $sumaobreraBimestral;

  if($salarioBaseDiario <= $salario_minimo)
  {
    $cuota_obrero = 0.00;
  }
  else{
    $cuota_obrero = $sumaobreraMensual + $sumaobreraBimestral; 
  }
  
  /*CUOTAS BIMESTRALES*/

  $mostrarIMSS = '
                  <table class="table table-sm" id="calculoIMSStabla">
                      <thead class="text-center header-color">
                        <tr>
                          <th>%</th>
                          <th>Concepto</th>
                          <th>Importe</th>
                        </tr>
                      </thead>
                      <tbody>                      
                        <tr class="text-center">
                          <td></td>
                          <td>SBC</td>
                          <td>'.number_format($SBC, 2, '.', ',').'</td>
                        </tr>
                        <tr class="text-center">
                          <td></td>
                          <td></td>
                          <td></td>
                        </tr>
                        <tr class="text-center">
                          <td colspan="3">CUOTAS MENSUALES</td>
                        </tr>
                        <tr class="text-center">
                          <td>'.$row_cuotasmensuales[0]['cantidad'].'</td>
                          <td>C.F.</td>
                          <td>'.number_format($impuestoCF, 2, '.', ',').'</td>
                        </tr>
                        <tr class="text-center">
                          <td>'.$row_cuotasmensuales[1]['cantidad'].'</td>
                          <td>Exc. Pat.</td>
                          <td>'.number_format($impuestoExcPat, 2, '.', ',').'</td>
                        </tr>
                        <tr class="text-center">
                          <td>'.$row_cuotasmensuales[2]['cantidad'].'</td>
                          <td>Exc. Obr.</td>
                          <td>'.number_format($impuestoExcObr, 2, '.', ',').'</td>
                        </tr>
                        <tr class="text-center">
                          <td>'.$row_cuotasmensuales[3]['cantidad'].'</td>
                          <td>P.D. Pat.</td>
                          <td>'.number_format($impuestoPDPat, 2, '.', ',').'</td>
                        </tr>
                        <tr class="text-center">
                          <td>'.$row_cuotasmensuales[4]['cantidad'].'</td>
                          <td>P.D. Obr.</td>
                          <td>'.number_format($impuestoPDObr, 2, '.', ',').'</td>
                        </tr>
                        <tr class="text-center">
                          <td>'.$row_cuotasmensuales[5]['cantidad'].'</td>
                          <td>G.M.P. Pat.</td>
                          <td>'.number_format($impuestoGMPat, 2, '.', ',').'</td>
                        </tr>
                        <tr class="text-center">
                          <td>'.$row_cuotasmensuales[6]['cantidad'].'</td>
                          <td>G.M.P. Obr.</td>
                          <td>'.number_format($impuestoGMObr, 2, '.', ',').'</td>
                        </tr>
                        <tr class="text-center">
                          <td>'.$row_cuotasmensuales[7]['cantidad'].'</td>
                          <td>R.T.</td>
                          <td>'.number_format($impuestoRT, 2, '.', ',').'</td>
                        </tr>
                        <tr class="text-center">
                          <td>'.$row_cuotasmensuales[8]['cantidad'].'</td>
                          <td>I.V. Pat.</td>
                          <td>'.number_format($impuestoIVPat, 2, '.', ',').'</td>
                        </tr>
                        <tr class="text-center">
                          <td>'.$row_cuotasmensuales[9]['cantidad'].'</td>
                          <td>I.V. Obr.</td>
                          <td>'.number_format($impuestoIVObr, 2, '.', ',').'</td>
                        </tr>
                        <tr class="text-center">
                          <td>'.$row_cuotasmensuales[10]['cantidad'].'</td>
                          <td>GPS</td>
                          <td>'.number_format($impuestoGPS, 2, '.', ',').'</td>
                        </tr>
                        <tr class="text-center">
                          <td>(=)</td>
                          <td>CUOTA PATRONAL</td>
                          <td>'.number_format($sumapatronalMensual, 2, '.', ',').'</td>
                        </tr>
                        <tr class="text-center">
                          <td>(=)</td>
                          <td>CUOTA OBRERA</td>
                          <td>'.number_format($sumaobreraMensual, 2, '.', ',').'</td>
                        </tr>
                        <tr class="text-center">
                          <td>(=)</td>
                          <td>TOTAL MENSUAL</td>
                          <td>'.number_format($cuota_mensual, 2, '.', ',').'</td>
                        </tr>
                        <tr class="text-center">
                          <td colspan="3"></td>
                        </tr>
                        <tr class="text-center">
                          <td colspan="3"></td>
                        </tr>
                        <tr class="text-center">
                          <td colspan="3">CUOTAS BIMESTRALES</td>
                        </tr>
                        <tr class="text-center">
                          <td>'.$row_cuotasbimestrales[0]['cantidad'].'</td>
                          <td>Retiro</td>
                          <td>'.number_format($impuestoRETIRO, 2, '.', ',').'</td>
                        </tr>
                        <tr class="text-center">
                          <td>'.$row_cuotasbimestrales[1]['cantidad'].'</td>
                          <td>CYV Pat.</td>
                          <td>'.number_format($impuestoCYVPat, 2, '.', ',').'</td>
                        </tr>
                        <tr class="text-center">
                          <td>'.$row_cuotasbimestrales[2]['cantidad'].'</td>
                          <td>CYV Obr.</td>
                          <td>'.number_format($impuestoCYVObr, 2, '.', ',').'</td>
                        </tr>
                        <tr class="text-center">
                          <td>'.$row_cuotasbimestrales[3]['cantidad'].'</td>
                          <td>Ap. pat.</td>
                          <td>'.number_format($impuestoAPPatron, 2, '.', ',').'</td>
                        </tr>
                        <tr class="text-center">
                          <td>(=)</td>
                          <td>CUOTA PATRONAL</td>
                          <td>'.number_format($sumapatronalBimestral, 2, '.', ',').'</td>
                        </tr>
                        <tr class="text-center">
                          <td>(=)</td>
                          <td>CUOTA OBRERA</td>
                          <td>'.number_format($sumaobreraBimestral, 2, '.', ',').'</td>
                        </tr>
                        <tr class="text-center">
                          <td colspan="3"></td>
                        </tr>
                        <tr class="text-center">
                          <td>(=)</td>
                          <td>TOTAL CUOTA</td>
                          <td>'.number_format($cuota_obrero, 2, '.', ',').'</td>
                        </tr>
                      </tbody>
                  </table>';

    return $mostrarIMSS;
}

?>