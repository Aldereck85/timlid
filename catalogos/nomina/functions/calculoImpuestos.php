<?php
//Cosas a elminar , solo para prueba directa
/*
session_start();
$fechaPago = $_POST['fechaPago'];
$idEmpleado = $_POST['idEmpleado'];
$idNominaEmpleado = $_POST['idNominaEmpleado'];
$idNomina = $_POST['idNomina'];
require_once '../../../include/db-conn.php';
$modo = 2;*/
//fin de cosas a eliminar
if($modo == 1){
  $ruta = "";
}
if($modo == 2){
  $ruta = "../";
}

error_reporting(E_ALL ^ E_WARNING);

$GLOBALS['rutaFuncion'] = $ruta;//"./../";
require_once($ruta."../../functions/funcionNomina.php");
date_default_timezone_set('America/Mexico_City');

$fechaAnio = DateTime::createFromFormat("Y-m-d", $fechaPago);
$anioCalculo = $fechaAnio->format("Y");

//OBTENCION DE DATOS NECESARIOS PARA EL CALCULO
$idSucursal = getSucursalProcedencia($idEmpleado);
$datosEmpleado = getSalario_Dias($idEmpleado);
$diasPeriodoIMSS = $datosEmpleado[1];
$base_imss_general = $datosEmpleado[0];

$anioCalculo = calcularAnio($fechaPago, 1);
$UMA = getUMA($anioCalculo);
$factor_mes = getFactorMes();
$salariosMinimons = getSalarioMinimo();
$salario_minimo_nacional = $salariosMinimons[0];
$salario_minimo_norte = $salariosMinimons[1];

$dias_aguinaldo = getDiasAguinaldo();
$prima_vacacional_tasa = getPrimaVacacional();
$zona_salario = getZonaSalario($idSucursal);
$riesgo_trabajo = getRiesgoTrabajo();

if($zona_salario == 1){
  $salario_minimo = $salario_minimo_nacional;
}
else{
  $salario_minimo = $salario_minimo_norte;
}


//Calculo de conceptos para el total de calculo de impuestos
$stmt = $conn->prepare('SELECT 1 as tipo, tipo_concepto, importe, importe_exento, exento, dias, 1 as concepto_id FROM detalle_nomina_percepcion_empleado WHERE empleado_id = :empleado_id AND nomina_empleado_id = :nomina_empleado_id
                        UNION ALL
                        SELECT 2 as tipo, tipo_concepto, importe, 0.00 as importe_exento, exento, dias, 1 as concepto_id FROM detalle_nomina_deduccion_empleado WHERE empleado_id = :empleado_id2 AND nomina_empleado_id = :nomina_empleado_id2
                        UNION ALL
                        SELECT 3 as tipo, 100 as tipo_concepto, importe, 0.00 as importe_exento, 0 as exento,0 as dias, otros_pagos_id as concepto_id FROM detalle_otros_pagos_nomina_empleado WHERE empleado_id = :empleado_id3 AND nomina_empleado_id = :nomina_empleado_id3');
$stmt->bindValue(':empleado_id', $idEmpleado);
$stmt->bindValue(':nomina_empleado_id', $idNominaEmpleado);
$stmt->bindValue(':empleado_id2', $idEmpleado);
$stmt->bindValue(':nomina_empleado_id2', $idNominaEmpleado);
$stmt->bindValue(':empleado_id3', $idEmpleado);
$stmt->bindValue(':nomina_empleado_id3', $idNominaEmpleado);
$stmt->execute();
$conceptos = $stmt->fetchAll();

$totalBase = 0;
$totalExento = 0;
$totalHoras = 0;
$totalPercepcionesBaseUnicas = 0;
$totalPercepcionesExentasUnicas = 0;
$totalDeduccionesBaseUnicas = 0;

//print_r($conceptos);
if(count($conceptos) > 0){

      foreach ($conceptos as $c) {

          //calcular dias
          /*if($c['tipo_concepto'] == 4){
            $dias_extras = $dias_extras + $c['dias'];
          }
          if($c['tipo_concepto'] == 5){
            $dias_extras = $dias_extras - $c['dias'];
          }*/

          //fin calcular dias

          //salario, percepciones y deducciones, y otros pagos, fonacot(12), infonavit(13), pension alimenticia(14)
          if($c['tipo_concepto'] == 1  || $c['tipo_concepto'] == 2 || $c['tipo_concepto'] == 4 || $c['tipo_concepto'] == 5 || $c['tipo_concepto'] == 11 || $c['tipo_concepto'] == 100 || $c['tipo_concepto'] == 12 || $c['tipo_concepto'] == 13 || $c['tipo_concepto'] == 14){

              if($c['exento'] == 1){
                //percepcion
                if($c['tipo'] == 1){
                    $totalExento = $totalExento + $c['importe_exento'];
                    $totalPercepcionesExentasUnicas = $totalPercepcionesExentasUnicas + $c['importe_exento'];
                }
                //deduccion
                /*if($c['tipo'] == 2){
                    $totalExento = $totalExento - $c['importe'];
                }*/
              }
              else{
                  //percepcion
                  if($c['tipo'] == 1){
                    $totalBase = $totalBase + $c['importe'];
                    //echo "paso 1 ".$totalBase."<br>";
                    $totalExento = $totalExento + $c['importe_exento'];

                    $totalPercepcionesBaseUnicas = $totalPercepcionesBaseUnicas + $c['importe'];
                    $totalPercepcionesExentasUnicas = $totalPercepcionesExentasUnicas + $c['importe_exento'];
                  }
                  //deduccion
                  if($c['tipo'] == 2){
                    $totalBase = $totalBase - $c['importe'];
                    //echo "paso 2 ".$totalBase."<br>";
                    $totalDeduccionesBaseUnicas = $totalDeduccionesBaseUnicas + $c['importe'];
                  }
                  //otros pagos
                  if($c['tipo'] == 3){

                    if($c['concepto_id'] != 3){
                      $totalBase = $totalBase + $c['importe'];

                      $totalPercepcionesBaseUnicas = $totalPercepcionesBaseUnicas + $c['importe'];
                    }
                  }
              }

          }

          //prima vacacional
          if($c['tipo_concepto'] == 10){

              $totalBase = $totalBase + $c['importe'];
              //echo "paso 10 ".$totalBase."<br>";
              $totalExento = $totalExento + $c['importe_exento'];

              $totalPercepcionesBaseUnicas = $totalPercepcionesBaseUnicas + $c['importe'];
              $totalPercepcionesExentasUnicas = $totalPercepcionesExentasUnicas + $c['importe_exento'];
          }

          //horas extra 4 simples, 7 dobles, 8  triples
         /* if($c['tipo_concepto'] == 3  || $c['tipo_concepto'] == 7 || $c['tipo_concepto'] == 8){

              $totalHoras = $totalHoras + $c['importe'];
          }*/

          //horas simples se gravan al 100%
          if($c['tipo_concepto'] == 3){
             $totalBase = $totalBase + $c['importe'];
             //echo "paso 3 ".$totalBase."<br>";
             $totalPercepcionesBaseUnicas = $totalPercepcionesBaseUnicas + $c['importe'];
          }

          //horas dobles en base al calculo
          if($c['tipo_concepto'] == 7){

            $totalBase = $totalBase + $c['importe'];
            $totalExento = $totalExento + $c['importe_exento'];
            //echo "paso 7 ".$totalBase."<br>";
            $totalPercepcionesBaseUnicas = $totalPercepcionesBaseUnicas + $c['importe'];
            $totalPercepcionesExentasUnicas = $totalPercepcionesExentasUnicas + $c['importe_exento'];
          }

          //horas triples se gravan al 100%
          if($c['tipo_concepto'] == 8){
            $totalBase = $totalBase + $c['importe'];
            //echo "paso 8 ".$totalBase."<br>";
            $totalPercepcionesBaseUnicas = $totalPercepcionesBaseUnicas + $c['importe'];
          }

          //prima dominical
          if($c['tipo_concepto'] == 6){

            $totalBase = $totalBase + $c['importe'];
            //echo "paso 6 ".$totalBase."<br>";
            $totalExento = $totalExento + $c['importe_exento'];

            $totalPercepcionesBaseUnicas = $totalPercepcionesBaseUnicas + $c['importe'];
            $totalPercepcionesExentasUnicas = $totalPercepcionesExentasUnicas + $c['importe_exento'];
            
          }
      }

      
      //dias trabajados en el periodo para el calculo de las cuotas del IMSS
      $diasTrabajados = $diasPeriodoIMSS;

      
      //Calculo del SALARIO BASE DE COTIZACION
      //require_once('calculovacacionesSDI.php');  se usara la funcion en vez
      $datosSBC = getSBCNomina($idEmpleado, $fechaPago);
      $SBC = $datosSBC[2];

      //calculo impuestos
      //echo "total base 1: ".$totalBase."<br>";
      $totalPercepciones = bcdiv($totalBase + $totalExento,1,2);
      //$totalBase = $totalPercepciones;
      //echo "total base 2: ".$totalBase." -- totalExento: ".$totalExento." total percepciones: ".$totalPercepciones;
      //echo " ////// percepcion ".$totalPercepciones;
      $sueldoDiario = number_format($totalBase / $diasTrabajados,3, '.', '');          
      $base = bcdiv($sueldoDiario * $factor_mes,1,2); //basado en lo mismo que calculo ISR
//echo "base: ". $base."   factor_mes: ". $factor_mes."   sueldoDiario: ". $sueldoDiario;
      if($base < 0.01){
        $base = 0.01;
      }
      $stmt = $conn->prepare("SELECT * FROM pagos_provisionales_mensuales WHERE Limite_inferior <= :impuestogravablemin AND Limite_superior >= :impuestogravablesup AND Anio = :anio");
      $stmt->bindValue(':impuestogravablemin',$base);
      $stmt->bindValue(':impuestogravablesup',$base);
      $stmt->bindValue(':anio',$anioCalculo);
      $stmt->execute();
      $row_limite = $stmt->fetch();
      //echo "base ".$base;

      $Limite_inferior = $row_limite['Limite_inferior'];
      $excedente_limite_inferior = number_format($base - $Limite_inferior, 2, '.', '');
      $porcentaje_tabla = $row_limite['Porcentaje_sobre_limite_inferior'];
      $impuesto_marginal = number_format($excedente_limite_inferior * ($porcentaje_tabla/100), 2, '.', '');
      $cuota_fija = $row_limite['Cuota_fija'];
      $ISRDeterminado = $impuesto_marginal + $cuota_fija;

      $stmt = $conn->prepare("SELECT * FROM subsidio_empleo WHERE IngresoMinimo <= :ingresominimo AND IngresoMaximo >= :ingresomaximo AND Anio = :anio");
      $stmt->bindValue(':ingresominimo',$base);
      $stmt->bindValue(':ingresomaximo',$base);
      $stmt->bindValue(':anio',$anioCalculo);
      $stmt->execute();
      $row_subsidio = $stmt->fetch();
      $subsidioMensual = $row_subsidio['SubsidioMensual'];
      $subsidioAplicable = number_format(($subsidioMensual / $factor_mes) * $diasTrabajados, 2, '.', '');
      //echo "sinsidio ".$subsidioAplicable;

      $ISRRetenido = number_format($ISRDeterminado, 2, '.', '');
      $ISRPrevioDiario = number_format((($ISRRetenido / $factor_mes) * $diasTrabajados) , 2, '.', '');
      //echo $ISRPrevioDiario." -- ".$subsidioAplicable;
      //echo "isr retenido ".$ISRRetenido." -- ".$ISRPrevioDiario;
  
      $SAEPagar = 0; $ISRDiario = 0.00;
      if($ISRPrevioDiario >= $subsidioAplicable){
        $ISRDiario = bcdiv($ISRPrevioDiario - $subsidioAplicable,1,2);
      }
      else{
        $SAEPagar = bcdiv(($ISRPrevioDiario - $subsidioAplicable) * -1,1,2);
      }

      /*CUOTAS MENSUALES Y BIMESTRALES*/
      $diasFaltasIMSS = getDiasFaltasIMSS($idNominaEmpleado, $idEmpleado);

      if($diasFaltasIMSS > $diasTrabajados){
        $diasTrabajadosIMSS = 0;
      }
      else{
        $diasTrabajadosIMSS = $diasTrabajados - $diasFaltasIMSS;
      }
      
      $datosCuotaIMSS = calcularCuotasIMSS($idEmpleado, $SBC, $diasTrabajadosIMSS, $fechaPago);
      $cuota_obrero = $datosCuotaIMSS[21];
      /*CUOTAS MENSUALES Y BIMESTRALES*/
/*
      $fechaAhora = DateTime::createFromFormat("Y-m-d", date('Y-m-d'));
      $mesAnterior2 = $fechaAhora->format("m") - 2;
      $mesAnterior1 = $fechaAhora->format("m") - 1;

      $fechaIniBimestreAnt = date('Y-'.$mesAnterior2.'-01' );
      $fechaFinBimestreAnt = date('Y-'.$mesAnterior1.'-01' );
      $diaFinalMes = date("Y-m-t", strtotime($fechaFinBimestreAnt));

      $date1 = date_create_from_format('Y-m-d', $fechaPeriodoIni);
      $date2 = date_create_from_format('Y-m-d',  $fechaPeriodoFin);
      $diff = (array) date_diff($date1, $date2);

      $diferencia = $diff['days'];
      $diasTrabajados = $diferencia + 1;*/

      //se ingresa la pension en caso de que se haya agregado un credito
      $importeAplicar = 0.00;
      if(isset($aplicarPension)){
        if($aplicarPension == 1){
            
            if(isset($idPension)){

              if($pensionAlimenticiaTipo == 2){

                $importeAplicar = bcdiv($datosEmpleado[0] * ($PorcentajeAplicar / 100),1,2);
              }
              if($pensionAlimenticiaTipo == 3){

                $baseTotalPercepciones = bcdiv($totalPercepcionesBaseUnicas + $totalPercepcionesExentasUnicas,1,2); 
                $importeAplicar = bcdiv($baseTotalPercepciones * ($PorcentajeAplicar / 100),1,2);
              }
              if($pensionAlimenticiaTipo == 4){

                $baseTotalPercepciones = bcdiv($totalPercepcionesBaseUnicas + $totalPercepcionesExentasUnicas,1,2); 
                $baseTotalPercepcionesDeducciones = bcdiv($totalPercepcionesBaseUnicas - $totalDeduccionesBaseUnicas - $ISRDiario - $cuota_obrero + $SAEPagar,1,2); 
                $importeAplicar = bcdiv($baseTotalPercepcionesDeducciones * ($PorcentajeAplicar / 100),1,2);
              }

              $stmt = $conn->prepare('INSERT INTO detalle_nomina_deduccion_empleado (relacion_tipo_deduccion_id, relacion_concepto_deduccion_id, tipo_concepto, horas, importe, importe_exento, exento, empleado_id, nomina_empleado_id, fecha_alta, fecha_edicion, usuario_alta, usuario_edicion) VALUES (:concepto, :relacion_concepto_percepcion_id, :tipo_concepto, :horas, :importe, :importe_exento, :exento, :empleado_id, :nomina_empleado_id,:fecha_alta,:fecha_edicion,:usuario_alta,:usuario_edicion)');
              $stmt->bindValue(':concepto', 7);
              $stmt->bindValue(':relacion_concepto_percepcion_id', $idConceptoDeduccion);
              $stmt->bindValue(':tipo_concepto', $tipo_concepto);
              $stmt->bindValue(':horas', 0);
              $stmt->bindValue(':importe', $importeAplicar);
              $stmt->bindValue(':importe_exento', 0);
              $stmt->bindValue(':exento', 0);
              $stmt->bindValue(':empleado_id', $idEmpleado);
              $stmt->bindValue(':nomina_empleado_id', $idNominaEmpleado);
              $stmt->bindValue(':fecha_alta', $fecha);
              $stmt->bindValue(':fecha_edicion', $fecha);
              $stmt->bindValue(':usuario_alta', $idUsuario);
              $stmt->bindValue(':usuario_edicion', $idUsuario);
              $stmt->execute();

              $idDetalleNominaDeduccion = $conn->lastInsertId();

              $stmt = $conn->prepare('INSERT INTO pension_alimenticia_registro (pension_alimenticia_id, nomina_empleado_id, detalle_nomina_deduccion_empleado_id, importe_aplicado, usuario_alta, fecha_alta, estatus) VALUES (:pension_alimenticia_id, :nomina_empleado_id, :detalle_nomina_deduccion_empleado_id, :importe_aplicado, :usuario_alta, :fecha_alta, :estatus)');
              $stmt->bindValue(':pension_alimenticia_id', $idPension);
              $stmt->bindValue(':nomina_empleado_id', $idNominaEmpleado);
              $stmt->bindValue(':detalle_nomina_deduccion_empleado_id', $idDetalleNominaDeduccion);
              $stmt->bindValue(':importe_aplicado', $importeAplicar);
              $stmt->bindValue(':usuario_alta', $idUsuario);
              $stmt->bindValue(':fecha_alta', $fecha);
              $stmt->bindValue(':estatus', 1);
              $stmt->execute();
          }
        }
      }

      if($SAEPagar == 0.00){
        //echo "lala ".$ISRDiario." -- ".$SAEPagar;
        $neto_a_pagar = number_format($totalBase - $ISRDiario - $cuota_obrero - $importeAplicar,2,'.','');
        $subtotal = number_format($totalBase - $importeAplicar,2,'.','');

        $stmt = $conn->prepare('UPDATE nomina_empleado SET ISR = :isr, SAE = 0.00, isr_tarifa = :isr_tarifa, subsidio_causado = :subsidio_causado, base_gravable = :base_gravable, cuotaIMSS = :cuotaImss, Total = :total, TotalNeto = :totalNeto WHERE PKNomina = :idNomina ');
        $stmt->bindValue(":isr", $ISRDiario);
      }
      else{
        $neto_a_pagar = number_format($totalBase + $SAEPagar - $cuota_obrero - $importeAplicar,2,'.','');
        $subtotal = number_format($totalBase - $importeAplicar,2,'.','');

        $stmt = $conn->prepare('UPDATE nomina_empleado SET ISR = 0.00, SAE = :sae, isr_tarifa = :isr_tarifa, subsidio_causado = :subsidio_causado, base_gravable = :base_gravable, cuotaIMSS = :cuotaImss, Total = :total, TotalNeto = :totalNeto WHERE PKNomina = :idNomina ');
        $stmt->bindValue(":sae", $SAEPagar);
      }

      $stmt->bindValue(":isr_tarifa", $ISRPrevioDiario);
      $stmt->bindValue(":subsidio_causado", $subsidioAplicable);
      $stmt->bindValue(":base_gravable", $totalBase);
      $stmt->bindValue(":cuotaImss", $cuota_obrero);
      $stmt->bindValue(":total", $subtotal);
      $stmt->bindValue(":totalNeto", $neto_a_pagar);
      $stmt->bindValue(":idNomina", $idNominaEmpleado);
      $stmt->execute();
}
else{
  // en caso de que el empleado no tenga ningun concepto agregado , se pone en ceros su nomina 
      $stmt = $conn->prepare('UPDATE nomina_empleado SET ISR = :isr, SAE = :sae, isr_tarifa = :isr_tarifa, subsidio_causado = :subsidio_causado, base_gravable = :base_gravable, cuotaIMSS = :cuotaImss, Total = :total, TotalNeto = :totalNeto WHERE PKNomina = :idNomina ');
      $stmt->bindValue(":isr", 0.00);
      $stmt->bindValue(":sae", 0.00);
      $stmt->bindValue(":isr_tarifa", 0.00);
      $stmt->bindValue(":subsidio_causado", 0.00);
      $stmt->bindValue(":base_gravable", 0.00);
      $stmt->bindValue(":cuotaImss", 0.00);
      $stmt->bindValue(":total", 0.00);
      $stmt->bindValue(":totalNeto", 0.00);
      $stmt->bindValue(":idNomina", $idNominaEmpleado);
      $stmt->execute();
}

//echo "neto a pagar ".$neto_a_pagar."  total percepciones ".$totalPercepciones." isr ".$ISRSemana." cuota imss ".$cuota_obrero."<br>";   isrsemana es el isr del calculo del periodo

//fin calculo impuestos


// SOLO SE REALIZARA EL CALCULO EN LA ULTIMA NOMINA DEL MES
$stmt = $conn->prepare(' SELECT ultima_nomina FROM nomina WHERE id = :idNomina');
$stmt->bindValue(':idNomina',  $idNomina);
$stmt->execute(); 
$ultima_nomina = $stmt->fetch();

//solo aplicara para ultima nomina del mes y nominas que no sean mensuales
if($ultima_nomina['ultima_nomina'] == 1 && $datosEmpleado[1] != 30){

    //OBTENER LAS NOMINAS QUE SE CALCULARAN
    $stmt = $conn->prepare('SELECT n.fecha_inicio, np.sucursal_id, np.periodo_id FROM nomina_empleado as ne INNER JOIN nomina as n ON ne.FKNomina = n.id INNER JOIN nomina_principal
    as np ON np.id = n.fk_nomina_principal WHERE ne.PKNomina = :idNominaEmpleado');
    $stmt->bindValue(':idNominaEmpleado',  $idNominaEmpleado);
    $stmt->execute(); 
    $row_detalle_nomina = $stmt->fetch();

    $fechaAnio = DateTime::createFromFormat("Y-m-d", $row_detalle_nomina['fecha_inicio']);
    $mesCalculo = $fechaAnio->format("m");

    $stmt = $conn->prepare('SELECT n.id, n.ultima_nomina FROM nomina as n INNER JOIN nomina_principal as np ON np.id = n.fk_nomina_principal WHERE n.empresa_id = :idEmpresa AND np.sucursal_id = :sucursal_id AND np.periodo_id = :periodo_id AND MONTH(n.fecha_inicio) = :mes_inicio AND YEAR(n.fecha_inicio) = :anio_inicio ');
    $stmt->bindValue(':idEmpresa', $_SESSION['IDEmpresa']);
    $stmt->bindValue(':sucursal_id',  $row_detalle_nomina['sucursal_id']);
    $stmt->bindValue(':periodo_id',  $row_detalle_nomina['periodo_id']);
    $stmt->bindValue(':mes_inicio',  $mesCalculo);
    $stmt->bindValue(':anio_inicio',  $anioCalculo);
    $stmt->execute();          
    $rowNominas = $stmt->fetchAll();

    //echo "<pre>",print_r($rowNominas),"</pre>";
    $array_subsidio = array();

    $suma_base = 0.00; $suma_isr_tarifa = 0.00; $suma_isr_tarifa_total = 0.00; $suma_subsidio_causado = 0.00; $suma_isr = 0.00; $suma_sae = 0.00;
    $suma_isr_periodos_anteriores = 0.00;
    $bandera_tipo_4 = 0;
    $bandera_tipo_5 = 0;
    $existe_subsidio_causado_final_periodo = 0;

    foreach($rowNominas as $rn){

      $stmt = $conn->prepare('SELECT ne.PKNomina, ne.ISR, ne.SAE, ne.Total, ne.TotalNeto, ne.isr_tarifa, ne.subsidio_causado, ne.base_gravable, n.ultima_nomina, ne.calculo_subsidio FROM nomina_empleado as ne INNER JOIN nomina as n ON ne.FKNomina = n.id WHERE ne.FKEmpleado = :empleado_id AND ne.FKNomina = :idNomina AND ne.Exento = 0');
      $stmt->bindValue(':empleado_id', $idEmpleado);
      $stmt->bindValue(':idNomina', $rn['id']);
      $stmt->execute();       
      $detalle_nomina_empleado = $stmt->fetch();
      //echo "<pre>",print_r($detalle_nomina_empleado),"</pre>";

      $suma_base = $suma_base + $detalle_nomina_empleado['base_gravable'];
      $suma_isr_tarifa_total = $suma_isr_tarifa_total + $detalle_nomina_empleado['isr_tarifa'];

      if($detalle_nomina_empleado['SAE'] > 0){
        $suma_isr_tarifa = $suma_isr_tarifa + $detalle_nomina_empleado['isr_tarifa'];
        $bandera_tipo_5++;
      }    

      if($detalle_nomina_empleado['ultima_nomina'] == 0){
        $suma_subsidio_causado = $suma_subsidio_causado + $detalle_nomina_empleado['subsidio_causado'];
        $suma_isr_periodos_anteriores = $suma_isr_periodos_anteriores + $detalle_nomina_empleado['ISR'];
      }

      $suma_isr = $suma_isr + $detalle_nomina_empleado['ISR'];

      if($detalle_nomina_empleado['ultima_nomina'] == 0){
        $suma_sae = $suma_sae + $detalle_nomina_empleado['SAE'];
      }


      if($detalle_nomina_empleado['ISR'] > 0 ){
        $bandera_tipo_4++;
      }

      //saber si ya se le calculo antes el ajuste al subsidio
      if($detalle_nomina_empleado['ultima_nomina'] == 1){
        $ajuste_calculo = $detalle_nomina_empleado['calculo_subsidio'];
        $ultimo_sae = $detalle_nomina_empleado['SAE'];

        /*if($detalle_nomina_empleado['subsidio_causado'] > 0){
          $existe_subsidio_causado_final_periodo = 1;  
        }*/
        
        $id_ultima_nomina =  $detalle_nomina_empleado['PKNomina'];
      }

    }

    $isr_ajustado_subsidio = bcdiv($suma_subsidio_causado- $suma_sae,1,2);
    $calculo_mensual = calculoISRFiniquito($suma_base, $anioCalculo); //calculo mensual del isr y del sae

//echo "suma base ".$suma_base;
//print_r($calculo_mensual);

    if($calculo_mensual[0] > 0){

      $stmt = $conn->prepare('UPDATE nomina_empleado SET SAE = 0.00 WHERE PKNomina = :id_ultima_nomina');
      $stmt->bindValue(':id_ultima_nomina', $id_ultima_nomina);
      $stmt->execute();     

    }

    if($calculo_mensual[3] > 0){
      $existe_subsidio_causado_final_periodo = 1;   
    }
    else{
      $existe_subsidio_causado_final_periodo = 0; 
    }

    $fecha_actualizacion = date("Y-m-d H:i:s");
//print_r($calculo_mensual);
    $isr_ajuste_mensual = $suma_subsidio_causado - $suma_sae;

    $tipo_calculo = 0;
    //CASO 1
    //if($calculo_mensual[2] > 0 && $suma_sae > 0 && $suma_isr_tarifa_total > $suma_subsidio_causado && $bandera_tipo_5 <= 3 && $suma_sae > $isr_ajuste_mensual && $suma_sae <= $calculo_mensual[1] &&  $tipo_calculo == 0){
    if($suma_subsidio_causado > 0 && $suma_sae > 0 && $existe_subsidio_causado_final_periodo == 0 && $suma_sae >= $calculo_mensual[1] && $suma_isr_periodos_anteriores == 0){
      $tipo_calculo = 1;
    }
    
    //CASO 2
    //if($suma_subsidio_causado > 0 && $suma_sae > 0 && $isr_ajuste_mensual > $suma_sae && $suma_sae <= $calculo_mensual[1] && $tipo_calculo == 0){
    if($suma_subsidio_causado > 0 && $suma_sae > 0 && $existe_subsidio_causado_final_periodo == 0 && $suma_sae >= $calculo_mensual[1] && $suma_isr_periodos_anteriores > 0){
      $tipo_calculo = 2;
    }

    //CASO 3
    //if($suma_isr_tarifa_total > $suma_subsidio_causado && $suma_sae <= $calculo_mensual[1] && $tipo_calculo == 0){
  //  echo " suma sae ".$suma_sae."  suma_subsidio_causado ".$suma_subsidio_causado."  calculo_mensual[1] ".$calculo_mensual[1]. "  suma_isr_periodos_anteriores ".$suma_isr_periodos_anteriores. " existe_subsidio_causado_final_periodo ".$existe_subsidio_causado_final_periodo; 
    if($suma_subsidio_causado > 0 && $suma_sae == 0 && $existe_subsidio_causado_final_periodo == 0 && $suma_sae >= $calculo_mensual[1] && $suma_isr_periodos_anteriores > 0){
      $tipo_calculo = 3;
    }

    //($calculo_mensual[3] > $suma_sae)      primer comprobacion que quzias esta mal
    //if($suma_sae > $calculo_mensual[1]  && $tipo_calculo == 0){
    if($suma_subsidio_causado > 0 && $suma_sae > 0 && $existe_subsidio_causado_final_periodo == 1 && $suma_sae >= $calculo_mensual[1] && $suma_isr_periodos_anteriores > 0 ){
      $tipo_calculo = 4;
    }
    
    //if(($calculo_mensual[1] > $suma_sae) && $bandera_tipo_5 > 3 && $tipo_calculo == 0){
    if($suma_subsidio_causado > 0 && $suma_sae > 0 && $existe_subsidio_causado_final_periodo == 1 && $suma_sae < $calculo_mensual[1] && $suma_isr_periodos_anteriores == 0 && $calculo_mensual[0] == 0){
      $tipo_calculo = 5;
    }

    //if($calculo_mensual[2] > $calculo_mensual[3] && $tipo_calculo == 0){
    //
    if($suma_subsidio_causado > 0 && $suma_sae > 0 && $existe_subsidio_causado_final_periodo == 1 && $suma_sae >= $calculo_mensual[1] && $suma_isr_periodos_anteriores == 0 && $calculo_mensual[0] > 0){
      $tipo_calculo = 6;
    }

/*
echo "tipo calculo: ".$tipo_calculo."<br>";  
    echo " suma_subsidio_causado ".$suma_subsidio_causado."<br>";  //107
    echo " suma_sae ".$suma_sae."<br>";  //107
    echo " existe_subsidio_causado_final_periodo ".$existe_subsidio_causado_final_periodo."<br>";  //107
    echo " calculo_mensual[1] ".$calculo_mensual[1]."<br>";
    echo " suma_isr_periodos_anteriores ".$suma_isr_periodos_anteriores."<br>";
echo " calculo mensual isr ".$calculo_mensual[0]."<br>";

/*
echo " calculo_mensual[3] ".$calculo_mensual[3]."<br>";
echo " calculo_mensual[2] ".$calculo_mensual[2]."<br>";
echo " suma_isr_tarifa_total ".$suma_isr_tarifa_total."<br>";  //002



echo $bandera_tipo_5." ------------------------------------------------------ ";

echo " calculo mensual sae tarifa ".$calculo_mensual[3]."<br>";

echo " calculo mensual isr ".$calculo_mensual[0]."<br>";
echo " calculo mensual sae ".$calculo_mensual[1]."<br>";
//echo " suma_sae ".$suma_sae."<br>";  //071
//
//return;
*/

// 0 isr
// 1 SAE, cuando es mayor el subsidio
// ISR para calculo del ajuste al subsidio
// Subsidio para el calculo del ajuste al subsidio
//echo "tipo ".$tipo_calculo;
//ELIMINAN TODOS LOS CONCEPTOS QUE SU CANTIDAD PARA GENERAR SEA 0, Y SE REINGRESARAN EN CASO DE SER NECESARIO
if($ajuste_calculo != $tipo_calculo && $ajuste_calculo != 0){
//107
      $stmt = $conn->prepare(' DELETE FROM detalle_nomina_deduccion_empleado WHERE empleado_id = :idEmpleado AND nomina_empleado_id = :nomina_empleado_id AND relacion_tipo_deduccion_id = 107 ');
      $stmt->bindValue(':idEmpleado',  $idEmpleado);
      $stmt->bindValue(':nomina_empleado_id',  $idNominaEmpleado);
      $stmt->execute(); 

//002
      $stmt = $conn->prepare(' DELETE FROM detalle_nomina_deduccion_empleado WHERE empleado_id = :idEmpleado AND nomina_empleado_id = :nomina_empleado_id AND relacion_tipo_deduccion_id = 2 ');
      $stmt->bindValue(':idEmpleado',  $idEmpleado);
      $stmt->bindValue(':nomina_empleado_id',  $idNominaEmpleado);
      $stmt->execute(); 

//7 Otros pagos
      $stmt = $conn->prepare(' DELETE FROM detalle_otros_pagos_nomina_empleado WHERE empleado_id = :idEmpleado AND nomina_empleado_id = :nomina_empleado_id AND otros_pagos_id = 7  AND edicion = 0');
      $stmt->bindValue(':idEmpleado',  $idEmpleado);
      $stmt->bindValue(':nomina_empleado_id',  $idNominaEmpleado);
      $stmt->execute(); 

//8 Otros pagos
      $stmt = $conn->prepare(' DELETE FROM detalle_otros_pagos_nomina_empleado WHERE empleado_id = :idEmpleado AND nomina_empleado_id = :nomina_empleado_id AND otros_pagos_id = 8 AND edicion = 0');
      $stmt->bindValue(':idEmpleado',  $idEmpleado);
      $stmt->bindValue(':nomina_empleado_id',  $idNominaEmpleado);
      $stmt->execute(); 

//71
      $stmt = $conn->prepare(' DELETE FROM detalle_nomina_deduccion_empleado WHERE empleado_id = :idEmpleado AND nomina_empleado_id = :nomina_empleado_id AND relacion_tipo_deduccion_id = 71 ');
      $stmt->bindValue(':idEmpleado',  $idEmpleado);
      $stmt->bindValue(':nomina_empleado_id',  $idNominaEmpleado);
      $stmt->execute(); 

//8 Otros pagos
      $stmt = $conn->prepare(' DELETE FROM detalle_otros_pagos_nomina_empleado WHERE empleado_id = :idEmpleado AND nomina_empleado_id = :nomina_empleado_id AND otros_pagos_id = 8 AND edicion = 0');
      $stmt->bindValue(':idEmpleado',  $idEmpleado);
      $stmt->bindValue(':nomina_empleado_id',  $idNominaEmpleado);
      $stmt->execute(); 

}

    ////////////////////////////////////////////////////////            CASO 1               ///////////////////////////////////////////////////////////////////////////////////////
    if($tipo_calculo == 1){

          $stmt = $conn->prepare(' SELECT id FROM detalle_nomina_deduccion_empleado WHERE empleado_id = :idEmpleado AND nomina_empleado_id = :nomina_empleado_id AND relacion_tipo_deduccion_id = 107 ');
          $stmt->bindValue(':idEmpleado',  $idEmpleado);
          $stmt->bindValue(':nomina_empleado_id',  $idNominaEmpleado);
          $stmt->execute();  
          $existe_ajuste_subsidio = $stmt->rowCount(); 


          //107
          if($suma_subsidio_causado > 0){
              if($existe_ajuste_subsidio > 0){
                $stmt = $conn->prepare('UPDATE detalle_nomina_deduccion_empleado SET importe = :importe, fecha_edicion = :fecha_edicion, usuario_edicion = :usuario_edicion WHERE empleado_id = :idEmpleado AND nomina_empleado_id = :nomina_empleado_id AND relacion_tipo_deduccion_id = 107 ');
                $stmt->bindValue(":importe", $suma_subsidio_causado);
                $stmt->bindValue(":fecha_edicion", $fecha_actualizacion);
                $stmt->bindValue(":usuario_edicion", $_SESSION['PKUsuario']);
                $stmt->bindValue(':idEmpleado',  $idEmpleado);
                $stmt->bindValue(':nomina_empleado_id',  $idNominaEmpleado);
                $stmt->execute();
              }
              else{

                $stmt = $conn->prepare(' SELECT id FROM relacion_concepto_deduccion WHERE tipo_deduccion_id = :tipo_deduccion_id AND empresa_id = :empresa_id');
                $stmt->bindValue(':tipo_deduccion_id',  107);
                $stmt->bindValue(':empresa_id',  $_SESSION['IDEmpresa']);
                $stmt->execute();  
                $existe_concepto_unico = $stmt->rowCount(); 

                if($existe_concepto_unico > 0){
                  $rowConceptoUnico = $stmt->fetch();
                  $idConceptoUnico = $rowConceptoUnico['id'];
                }
                else{

                  $stmt = $conn->prepare('INSERT INTO relacion_concepto_deduccion ( concepto_nomina, tipo_deduccion_id,  empresa_id) VALUES (  :concepto_nomina, :tipo_deduccion_id, :empresa_id )');
                  $stmt->bindValue(":concepto_nomina", "Ajuste al subsidio causado");
                  $stmt->bindValue(":tipo_deduccion_id", 107);
                  $stmt->bindValue(":empresa_id", $_SESSION['IDEmpresa']);
                  $stmt->execute();

                  $idConceptoUnico = $conn->lastInsertId();
                  
                }

                $stmt = $conn->prepare('INSERT INTO detalle_nomina_deduccion_empleado ( relacion_tipo_deduccion_id, relacion_concepto_deduccion_id, tipo_concepto, dias, horas, incapacidad, importe, importe_exento, exento, empleado_id, nomina_empleado_id, fecha_alta, fecha_edicion, usuario_alta, usuario_edicion ) VALUES (  :relacion_tipo_deduccion_id, :relacion_concepto_deduccion_id, 9, 0, 0, 0, :importe, 0.00, 0, :idEmpleado, :nomina_empleado_id, :fecha_alta, :fecha_edicion, :usuario_alta, :usuario_edicion )');
                $stmt->bindValue(":relacion_tipo_deduccion_id", 107);
                $stmt->bindValue(":relacion_concepto_deduccion_id", $idConceptoUnico);
                $stmt->bindValue(":importe", $suma_subsidio_causado);
                $stmt->bindValue(':idEmpleado',  $idEmpleado);
                $stmt->bindValue(':nomina_empleado_id',  $idNominaEmpleado);
                $stmt->bindValue(":fecha_alta", $fecha_actualizacion);
                $stmt->bindValue(":fecha_edicion", $fecha_actualizacion);
                $stmt->bindValue(":usuario_alta", $_SESSION['PKUsuario']);
                $stmt->bindValue(":usuario_edicion", $_SESSION['PKUsuario']);
                $stmt->execute();
              }
          }
          


          //002 este es el isr ajustado que es el mismo ISR pero igual hay qu agregarlo
          $stmt = $conn->prepare(' SELECT id FROM detalle_nomina_deduccion_empleado WHERE empleado_id = :idEmpleado AND nomina_empleado_id = :nomina_empleado_id AND relacion_tipo_deduccion_id = 2 ');
          $stmt->bindValue(':idEmpleado',  $idEmpleado);
          $stmt->bindValue(':nomina_empleado_id',  $idNominaEmpleado);
          $stmt->execute();  
          $existe_isr_corregir = $stmt->rowCount(); 

          if($suma_isr_tarifa > 0){

              if($existe_isr_corregir > 0){
                $stmt = $conn->prepare('UPDATE detalle_nomina_deduccion_empleado SET importe = :importe, fecha_edicion = :fecha_edicion, usuario_edicion = :usuario_edicion WHERE empleado_id = :idEmpleado AND nomina_empleado_id = :nomina_empleado_id AND relacion_tipo_deduccion_id = 2 ');
                $stmt->bindValue(":importe", $suma_isr_tarifa);
                $stmt->bindValue(":fecha_edicion", $fecha_actualizacion);
                $stmt->bindValue(":usuario_edicion", $_SESSION['PKUsuario']);
                $stmt->bindValue(':idEmpleado',  $idEmpleado);
                $stmt->bindValue(':nomina_empleado_id',  $idNominaEmpleado);
                $stmt->execute();
              }
              else{

                $stmt = $conn->prepare(' SELECT id FROM relacion_concepto_deduccion WHERE tipo_deduccion_id = :tipo_deduccion_id AND empresa_id = :empresa_id');
                $stmt->bindValue(':tipo_deduccion_id',  2);
                $stmt->bindValue(':empresa_id',  $_SESSION['IDEmpresa']);
                $stmt->execute();  
                $existe_concepto_unico = $stmt->rowCount(); 

                if($existe_concepto_unico > 0){
                  $rowConceptoUnico = $stmt->fetch();
                  $idConceptoUnico = $rowConceptoUnico['id'];
                }
                else{

                  $stmt = $conn->prepare('INSERT INTO relacion_concepto_deduccion ( concepto_nomina, tipo_deduccion_id,  empresa_id) VALUES (  :concepto_nomina, :tipo_deduccion_id, :empresa_id )');
                  $stmt->bindValue(":concepto_nomina", "ISR");
                  $stmt->bindValue(":tipo_deduccion_id", 2);
                  $stmt->bindValue(":empresa_id", $_SESSION['IDEmpresa']);
                  $stmt->execute();

                  $idConceptoUnico = $conn->lastInsertId();
                  
                }

                $stmt = $conn->prepare('INSERT INTO detalle_nomina_deduccion_empleado ( relacion_tipo_deduccion_id, relacion_concepto_deduccion_id, tipo_concepto, dias, horas, incapacidad, importe, importe_exento, exento, empleado_id, nomina_empleado_id, fecha_alta, fecha_edicion, usuario_alta, usuario_edicion ) VALUES (  :relacion_tipo_deduccion_id, :relacion_concepto_deduccion_id, 9, 0, 0, 0, :importe, 0.00, 0, :idEmpleado, :nomina_empleado_id, :fecha_alta, :fecha_edicion, :usuario_alta, :usuario_edicion )');
                $stmt->bindValue(":relacion_tipo_deduccion_id", 2);
                $stmt->bindValue(":relacion_concepto_deduccion_id", $idConceptoUnico);
                $stmt->bindValue(":importe", $suma_isr_tarifa);
                $stmt->bindValue(':idEmpleado',  $idEmpleado);
                $stmt->bindValue(':nomina_empleado_id',  $idNominaEmpleado);
                $stmt->bindValue(":fecha_alta", $fecha_actualizacion);
                $stmt->bindValue(":fecha_edicion", $fecha_actualizacion);
                $stmt->bindValue(":usuario_alta", $_SESSION['PKUsuario']);
                $stmt->bindValue(":usuario_edicion", $_SESSION['PKUsuario']);
                $stmt->execute();
              }

          }


          //ISR ajustado por subsidio
          $stmt = $conn->prepare(' SELECT id FROM detalle_otros_pagos_nomina_empleado WHERE empleado_id = :idEmpleado AND nomina_empleado_id = :nomina_empleado_id AND otros_pagos_id = 7 ');
          $stmt->bindValue(':idEmpleado',  $idEmpleado);
          $stmt->bindValue(':nomina_empleado_id',  $idNominaEmpleado);
          $stmt->execute();  
          $existe_isr_ajuste_subsidio = $stmt->rowCount(); 

          if($suma_isr_tarifa > 0){

              if($existe_isr_ajuste_subsidio > 0){
                $stmt = $conn->prepare('UPDATE detalle_otros_pagos_nomina_empleado SET importe = :importe, fecha_edicion = :fecha_edicion, usuario_edicion = :usuario_edicion WHERE empleado_id = :idEmpleado AND nomina_empleado_id = :nomina_empleado_id AND otros_pagos_id = 7 ');
                $stmt->bindValue(":importe", $suma_isr_tarifa);
                $stmt->bindValue(":fecha_edicion", $fecha_actualizacion);
                $stmt->bindValue(":usuario_edicion", $_SESSION['PKUsuario']);
                $stmt->bindValue(':idEmpleado',  $idEmpleado);
                $stmt->bindValue(':nomina_empleado_id',  $idNominaEmpleado);
                $stmt->execute();
              }
              else{

                $stmt = $conn->prepare(' SELECT id FROM relacion_concepto_otros_pagos WHERE tipo_otros_pagos_id = :tipo_otros_pagos_id AND empresa_id = :empresa_id');
                $stmt->bindValue(':tipo_otros_pagos_id',  7);
                $stmt->bindValue(':empresa_id',  $_SESSION['IDEmpresa']);
                $stmt->execute();  
                $existe_concepto_unico = $stmt->rowCount(); 

                if($existe_concepto_unico > 0){
                  $rowConceptoUnico = $stmt->fetch();
                  $idConceptoUnico = $rowConceptoUnico['id'];
                }
                else{

                  $stmt = $conn->prepare('INSERT INTO relacion_concepto_otros_pagos ( concepto_nomina, tipo_otros_pagos_id,  empresa_id) VALUES (  :concepto_nomina, :tipo_otros_pagos_id, :empresa_id )');
                  $stmt->bindValue(":concepto_nomina", "ISR ajustado por subsidio");
                  $stmt->bindValue(":tipo_otros_pagos_id", 7);
                  $stmt->bindValue(":empresa_id", $_SESSION['IDEmpresa']);
                  $stmt->execute();

                  $idConceptoUnico = $conn->lastInsertId();
                }

                $stmt = $conn->prepare('INSERT INTO detalle_otros_pagos_nomina_empleado ( otros_pagos_id, relacion_concepto_otros_pagos_id, importe, empleado_id, nomina_empleado_id, fecha_alta, fecha_edicion, usuario_alta, usuario_edicion ) VALUES (  :otros_pagos_id, :relacion_concepto_otros_pagos_id, :importe, :idEmpleado, :nomina_empleado_id, :fecha_alta, :fecha_edicion, :usuario_alta, :usuario_edicion )');
                $stmt->bindValue(":otros_pagos_id", 7);
                $stmt->bindValue(":relacion_concepto_otros_pagos_id", $idConceptoUnico);
                $stmt->bindValue(":importe", $suma_isr_tarifa);
                $stmt->bindValue(':idEmpleado',  $idEmpleado);
                $stmt->bindValue(':nomina_empleado_id',  $idNominaEmpleado);
                $stmt->bindValue(":fecha_alta", $fecha_actualizacion);
                $stmt->bindValue(":fecha_edicion", $fecha_actualizacion);
                $stmt->bindValue(":usuario_alta", $_SESSION['PKUsuario']);
                $stmt->bindValue(":usuario_edicion", $_SESSION['PKUsuario']);
                $stmt->execute();
              }

          }


          //071 Ajuste en Subsidio para el empleo (efectivamente entregado al trabajador)
          $stmt = $conn->prepare(' SELECT id FROM detalle_nomina_deduccion_empleado WHERE empleado_id = :idEmpleado AND nomina_empleado_id = :nomina_empleado_id AND relacion_tipo_deduccion_id = 71 ');
          $stmt->bindValue(':idEmpleado',  $idEmpleado);
          $stmt->bindValue(':nomina_empleado_id',  $idNominaEmpleado);
          $stmt->execute();  
          $existe_subsidio_entregado = $stmt->rowCount(); 

          if($suma_sae > 0){

              if($existe_subsidio_entregado > 0){
                $stmt = $conn->prepare('UPDATE detalle_nomina_deduccion_empleado SET importe = :importe, fecha_edicion = :fecha_edicion, usuario_edicion = :usuario_edicion WHERE empleado_id = :idEmpleado AND nomina_empleado_id = :nomina_empleado_id AND relacion_tipo_deduccion_id = 71 ');
                $stmt->bindValue(":importe", $suma_sae);
                $stmt->bindValue(":fecha_edicion", $fecha_actualizacion);
                $stmt->bindValue(":usuario_edicion", $_SESSION['PKUsuario']);
                $stmt->bindValue(':idEmpleado',  $idEmpleado);
                $stmt->bindValue(':nomina_empleado_id',  $idNominaEmpleado);
                $stmt->execute();
              }
              else{

                $stmt = $conn->prepare(' SELECT id FROM relacion_concepto_deduccion WHERE tipo_deduccion_id = :tipo_deduccion_id AND empresa_id = :empresa_id');
                $stmt->bindValue(':tipo_deduccion_id',  71);
                $stmt->bindValue(':empresa_id',  $_SESSION['IDEmpresa']);
                $stmt->execute();  
                $existe_concepto_unico = $stmt->rowCount(); 

                if($existe_concepto_unico > 0){
                  $rowConceptoUnico = $stmt->fetch();
                  $idConceptoUnico = $rowConceptoUnico['id'];
                }
                else{

                  $stmt = $conn->prepare('INSERT INTO relacion_concepto_deduccion ( concepto_nomina, tipo_deduccion_id,  empresa_id) VALUES (  :concepto_nomina, :tipo_deduccion_id, :empresa_id )');
                  $stmt->bindValue(":concepto_nomina", "Ajuste en Subsidio para el empleo");
                  $stmt->bindValue(":tipo_deduccion_id", 71);
                  $stmt->bindValue(":empresa_id", $_SESSION['IDEmpresa']);
                  $stmt->execute();

                  $idConceptoUnico = $conn->lastInsertId();
                  
                }

                $stmt = $conn->prepare('INSERT INTO detalle_nomina_deduccion_empleado ( relacion_tipo_deduccion_id, relacion_concepto_deduccion_id, tipo_concepto, dias, horas, incapacidad, importe, importe_exento, exento, empleado_id, nomina_empleado_id, fecha_alta, fecha_edicion, usuario_alta, usuario_edicion ) VALUES (  :relacion_tipo_deduccion_id, :relacion_concepto_deduccion_id, 9, 0, 0, 0, :importe, 0.00, 0, :idEmpleado, :nomina_empleado_id, :fecha_alta, :fecha_edicion, :usuario_alta, :usuario_edicion )');
                $stmt->bindValue(":relacion_tipo_deduccion_id", 71);
                $stmt->bindValue(":relacion_concepto_deduccion_id", $idConceptoUnico);
                $stmt->bindValue(":importe", $suma_sae);
                $stmt->bindValue(':idEmpleado',  $idEmpleado);
                $stmt->bindValue(':nomina_empleado_id',  $idNominaEmpleado);
                $stmt->bindValue(":fecha_alta", $fecha_actualizacion);
                $stmt->bindValue(":fecha_edicion", $fecha_actualizacion);
                $stmt->bindValue(":usuario_alta", $_SESSION['PKUsuario']);
                $stmt->bindValue(":usuario_edicion", $_SESSION['PKUsuario']);
                $stmt->execute();
              }

          }



          //Subsidio efectivamente entregado que no correpondia 008
          $stmt = $conn->prepare(' SELECT id FROM detalle_otros_pagos_nomina_empleado WHERE empleado_id = :idEmpleado AND nomina_empleado_id = :nomina_empleado_id AND otros_pagos_id = 8 ');
          $stmt->bindValue(':idEmpleado',  $idEmpleado);
          $stmt->bindValue(':nomina_empleado_id',  $idNominaEmpleado);
          $stmt->execute();  
          $existe_subsidio_entregado_no_corresponde = $stmt->rowCount(); 

          if($suma_sae > 0){

              if($existe_subsidio_entregado_no_corresponde > 0){
                $stmt = $conn->prepare('UPDATE detalle_otros_pagos_nomina_empleado SET importe = :importe, fecha_edicion = :fecha_edicion, usuario_edicion = :usuario_edicion WHERE empleado_id = :idEmpleado AND nomina_empleado_id = :nomina_empleado_id AND otros_pagos_id = 8 ');
                $stmt->bindValue(":importe", $suma_sae);
                $stmt->bindValue(":fecha_edicion", $fecha_actualizacion);
                $stmt->bindValue(":usuario_edicion", $_SESSION['PKUsuario']);
                $stmt->bindValue(':idEmpleado',  $idEmpleado);
                $stmt->bindValue(':nomina_empleado_id',  $idNominaEmpleado);
                $stmt->execute();
              }
              else{

                $stmt = $conn->prepare(' SELECT id FROM relacion_concepto_otros_pagos WHERE tipo_otros_pagos_id = :tipo_otros_pagos_id AND empresa_id = :empresa_id');
                $stmt->bindValue(':tipo_otros_pagos_id',  8);
                $stmt->bindValue(':empresa_id',  $_SESSION['IDEmpresa']);
                $stmt->execute();  
                $existe_concepto_unico = $stmt->rowCount(); 

                if($existe_concepto_unico > 0){
                  $rowConceptoUnico = $stmt->fetch();
                  $idConceptoUnico = $rowConceptoUnico['id'];
                }
                else{

                  $stmt = $conn->prepare('INSERT INTO relacion_concepto_otros_pagos ( concepto_nomina, tipo_otros_pagos_id,  empresa_id) VALUES (  :concepto_nomina, :tipo_otros_pagos_id, :empresa_id )');
                  $stmt->bindValue(":concepto_nomina", "Subsidio efectivamente entregado que no correspondía");
                  $stmt->bindValue(":tipo_otros_pagos_id", 8);
                  $stmt->bindValue(":empresa_id", $_SESSION['IDEmpresa']);
                  $stmt->execute();

                  $idConceptoUnico = $conn->lastInsertId();
                }

                $stmt = $conn->prepare('INSERT INTO detalle_otros_pagos_nomina_empleado ( otros_pagos_id, relacion_concepto_otros_pagos_id, importe, empleado_id, nomina_empleado_id, fecha_alta, fecha_edicion, usuario_alta, usuario_edicion ) VALUES (  :otros_pagos_id, :relacion_concepto_otros_pagos_id, :importe, :idEmpleado, :nomina_empleado_id, :fecha_alta, :fecha_edicion, :usuario_alta, :usuario_edicion )');
                $stmt->bindValue(":otros_pagos_id", 8);
                $stmt->bindValue(":relacion_concepto_otros_pagos_id", $idConceptoUnico);
                $stmt->bindValue(":importe", $suma_sae);
                $stmt->bindValue(':idEmpleado',  $idEmpleado);
                $stmt->bindValue(':nomina_empleado_id',  $idNominaEmpleado);
                $stmt->bindValue(":fecha_alta", $fecha_actualizacion);
                $stmt->bindValue(":fecha_edicion", $fecha_actualizacion);
                $stmt->bindValue(":usuario_alta", $_SESSION['PKUsuario']);
                $stmt->bindValue(":usuario_edicion", $_SESSION['PKUsuario']);
                $stmt->execute();
              }

          }


    }

    ////////////////////////////////////////////////////////            CASO 2               ///////////////////////////////////////////////////////////////////////////////////////
    if($tipo_calculo == 2){

          $stmt = $conn->prepare(' SELECT id FROM detalle_nomina_deduccion_empleado WHERE empleado_id = :idEmpleado AND nomina_empleado_id = :nomina_empleado_id AND relacion_tipo_deduccion_id = 107 ');
          $stmt->bindValue(':idEmpleado',  $idEmpleado);
          $stmt->bindValue(':nomina_empleado_id',  $idNominaEmpleado);
          $stmt->execute();  
          $existe_ajuste_subsidio = $stmt->rowCount(); 


          //107
          if($suma_subsidio_causado > 0){

              if($existe_ajuste_subsidio > 0){
                $stmt = $conn->prepare('UPDATE detalle_nomina_deduccion_empleado SET importe = :importe, fecha_edicion = :fecha_edicion, usuario_edicion = :usuario_edicion WHERE empleado_id = :idEmpleado AND nomina_empleado_id = :nomina_empleado_id AND relacion_tipo_deduccion_id = 107 ');
                $stmt->bindValue(":importe", $suma_subsidio_causado);
                $stmt->bindValue(":fecha_edicion", $fecha_actualizacion);
                $stmt->bindValue(":usuario_edicion", $_SESSION['PKUsuario']);
                $stmt->bindValue(':idEmpleado',  $idEmpleado);
                $stmt->bindValue(':nomina_empleado_id',  $idNominaEmpleado);
                $stmt->execute();
              }
              else{

                $stmt = $conn->prepare(' SELECT id FROM relacion_concepto_deduccion WHERE tipo_deduccion_id = :tipo_deduccion_id AND empresa_id = :empresa_id');
                $stmt->bindValue(':tipo_deduccion_id',  107);
                $stmt->bindValue(':empresa_id',  $_SESSION['IDEmpresa']);
                $stmt->execute();  
                $existe_concepto_unico = $stmt->rowCount(); 

                if($existe_concepto_unico > 0){
                  $rowConceptoUnico = $stmt->fetch();
                  $idConceptoUnico = $rowConceptoUnico['id'];
                }
                else{

                  $stmt = $conn->prepare('INSERT INTO relacion_concepto_deduccion ( concepto_nomina, tipo_deduccion_id,  empresa_id) VALUES (  :concepto_nomina, :tipo_deduccion_id, :empresa_id )');
                  $stmt->bindValue(":concepto_nomina", "Ajuste al subsidio causado");
                  $stmt->bindValue(":tipo_deduccion_id", 107);
                  $stmt->bindValue(":empresa_id", $_SESSION['IDEmpresa']);
                  $stmt->execute();

                  $idConceptoUnico = $conn->lastInsertId();
                  
                }

                $stmt = $conn->prepare('INSERT INTO detalle_nomina_deduccion_empleado ( relacion_tipo_deduccion_id, relacion_concepto_deduccion_id, tipo_concepto, dias, horas, incapacidad, importe, importe_exento, exento, empleado_id, nomina_empleado_id, fecha_alta, fecha_edicion, usuario_alta, usuario_edicion ) VALUES (  :relacion_tipo_deduccion_id, :relacion_concepto_deduccion_id, 9, 0, 0, 0, :importe, 0.00, 0, :idEmpleado, :nomina_empleado_id, :fecha_alta, :fecha_edicion, :usuario_alta, :usuario_edicion )');
                $stmt->bindValue(":relacion_tipo_deduccion_id", 107);
                $stmt->bindValue(":relacion_concepto_deduccion_id", $idConceptoUnico);
                $stmt->bindValue(":importe", $suma_subsidio_causado);
                $stmt->bindValue(':idEmpleado',  $idEmpleado);
                $stmt->bindValue(':nomina_empleado_id',  $idNominaEmpleado);
                $stmt->bindValue(":fecha_alta", $fecha_actualizacion);
                $stmt->bindValue(":fecha_edicion", $fecha_actualizacion);
                $stmt->bindValue(":usuario_alta", $_SESSION['PKUsuario']);
                $stmt->bindValue(":usuario_edicion", $_SESSION['PKUsuario']);
                $stmt->execute();
              }

          }


          //002 este es el isr ajustado que es el mismo ISR pero igual hay qu agregarlo
          $stmt = $conn->prepare(' SELECT id FROM detalle_nomina_deduccion_empleado WHERE empleado_id = :idEmpleado AND nomina_empleado_id = :nomina_empleado_id AND relacion_tipo_deduccion_id = 2 ');
          $stmt->bindValue(':idEmpleado',  $idEmpleado);
          $stmt->bindValue(':nomina_empleado_id',  $idNominaEmpleado);
          $stmt->execute();  
          $existe_isr_corregir = $stmt->rowCount(); 

          if($isr_ajuste_mensual > 0){

              if($existe_isr_corregir > 0){
                $stmt = $conn->prepare('UPDATE detalle_nomina_deduccion_empleado SET importe = :importe, fecha_edicion = :fecha_edicion, usuario_edicion = :usuario_edicion WHERE empleado_id = :idEmpleado AND nomina_empleado_id = :nomina_empleado_id AND relacion_tipo_deduccion_id = 2 ');
                $stmt->bindValue(":importe", $isr_ajuste_mensual);
                $stmt->bindValue(":fecha_edicion", $fecha_actualizacion);
                $stmt->bindValue(":usuario_edicion", $_SESSION['PKUsuario']);
                $stmt->bindValue(':idEmpleado',  $idEmpleado);
                $stmt->bindValue(':nomina_empleado_id',  $idNominaEmpleado);
                $stmt->execute();
              }
              else{

                $stmt = $conn->prepare(' SELECT id FROM relacion_concepto_deduccion WHERE tipo_deduccion_id = :tipo_deduccion_id AND empresa_id = :empresa_id');
                $stmt->bindValue(':tipo_deduccion_id',  2);
                $stmt->bindValue(':empresa_id',  $_SESSION['IDEmpresa']);
                $stmt->execute();  
                $existe_concepto_unico = $stmt->rowCount(); 

                if($existe_concepto_unico > 0){
                  $rowConceptoUnico = $stmt->fetch();
                  $idConceptoUnico = $rowConceptoUnico['id'];
                }
                else{

                  $stmt = $conn->prepare('INSERT INTO relacion_concepto_deduccion ( concepto_nomina, tipo_deduccion_id,  empresa_id) VALUES (  :concepto_nomina, :tipo_deduccion_id, :empresa_id )');
                  $stmt->bindValue(":concepto_nomina", "ISR");
                  $stmt->bindValue(":tipo_deduccion_id", 2);
                  $stmt->bindValue(":empresa_id", $_SESSION['IDEmpresa']);
                  $stmt->execute();

                  $idConceptoUnico = $conn->lastInsertId();
                  
                }

                $stmt = $conn->prepare('INSERT INTO detalle_nomina_deduccion_empleado ( relacion_tipo_deduccion_id, relacion_concepto_deduccion_id, tipo_concepto, dias, horas, incapacidad, importe, importe_exento, exento, empleado_id, nomina_empleado_id, fecha_alta, fecha_edicion, usuario_alta, usuario_edicion ) VALUES (  :relacion_tipo_deduccion_id, :relacion_concepto_deduccion_id, 9, 0, 0, 0, :importe, 0.00, 0, :idEmpleado, :nomina_empleado_id, :fecha_alta, :fecha_edicion, :usuario_alta, :usuario_edicion )');
                $stmt->bindValue(":relacion_tipo_deduccion_id", 2);
                $stmt->bindValue(":relacion_concepto_deduccion_id", $idConceptoUnico);
                $stmt->bindValue(":importe", $isr_ajuste_mensual);
                $stmt->bindValue(':idEmpleado',  $idEmpleado);
                $stmt->bindValue(':nomina_empleado_id',  $idNominaEmpleado);
                $stmt->bindValue(":fecha_alta", $fecha_actualizacion);
                $stmt->bindValue(":fecha_edicion", $fecha_actualizacion);
                $stmt->bindValue(":usuario_alta", $_SESSION['PKUsuario']);
                $stmt->bindValue(":usuario_edicion", $_SESSION['PKUsuario']);
                $stmt->execute();
              }

          }


          //ISR ajustado por subsidio
          $stmt = $conn->prepare(' SELECT id FROM detalle_otros_pagos_nomina_empleado WHERE empleado_id = :idEmpleado AND nomina_empleado_id = :nomina_empleado_id AND otros_pagos_id = 7 ');
          $stmt->bindValue(':idEmpleado',  $idEmpleado);
          $stmt->bindValue(':nomina_empleado_id',  $idNominaEmpleado);
          $stmt->execute();  
          $existe_isr_ajuste_subsidio = $stmt->rowCount(); 

          if($isr_ajuste_mensual > 0){

              if($existe_isr_ajuste_subsidio > 0){
                $stmt = $conn->prepare('UPDATE detalle_otros_pagos_nomina_empleado SET importe = :importe, fecha_edicion = :fecha_edicion, usuario_edicion = :usuario_edicion WHERE empleado_id = :idEmpleado AND nomina_empleado_id = :nomina_empleado_id AND otros_pagos_id = 7 ');
                $stmt->bindValue(":importe", $isr_ajuste_mensual);
                $stmt->bindValue(":fecha_edicion", $fecha_actualizacion);
                $stmt->bindValue(":usuario_edicion", $_SESSION['PKUsuario']);
                $stmt->bindValue(':idEmpleado',  $idEmpleado);
                $stmt->bindValue(':nomina_empleado_id',  $idNominaEmpleado);
                $stmt->execute();
              }
              else{

                $stmt = $conn->prepare(' SELECT id FROM relacion_concepto_otros_pagos WHERE tipo_otros_pagos_id = :tipo_otros_pagos_id AND empresa_id = :empresa_id');
                $stmt->bindValue(':tipo_otros_pagos_id',  7);
                $stmt->bindValue(':empresa_id',  $_SESSION['IDEmpresa']);
                $stmt->execute();  
                $existe_concepto_unico = $stmt->rowCount(); 

                if($existe_concepto_unico > 0){
                  $rowConceptoUnico = $stmt->fetch();
                  $idConceptoUnico = $rowConceptoUnico['id'];
                }
                else{

                  $stmt = $conn->prepare('INSERT INTO relacion_concepto_otros_pagos ( concepto_nomina, tipo_otros_pagos_id,  empresa_id) VALUES (  :concepto_nomina, :tipo_otros_pagos_id, :empresa_id )');
                  $stmt->bindValue(":concepto_nomina", "ISR ajustado por subsidio");
                  $stmt->bindValue(":tipo_otros_pagos_id", 7);
                  $stmt->bindValue(":empresa_id", $_SESSION['IDEmpresa']);
                  $stmt->execute();

                  $idConceptoUnico = $conn->lastInsertId();
                }

                $stmt = $conn->prepare('INSERT INTO detalle_otros_pagos_nomina_empleado ( otros_pagos_id, relacion_concepto_otros_pagos_id, importe, empleado_id, nomina_empleado_id, fecha_alta, fecha_edicion, usuario_alta, usuario_edicion ) VALUES (  :otros_pagos_id, :relacion_concepto_otros_pagos_id, :importe, :idEmpleado, :nomina_empleado_id, :fecha_alta, :fecha_edicion, :usuario_alta, :usuario_edicion )');
                $stmt->bindValue(":otros_pagos_id", 7);
                $stmt->bindValue(":relacion_concepto_otros_pagos_id", $idConceptoUnico);
                $stmt->bindValue(":importe", $isr_ajuste_mensual);
                $stmt->bindValue(':idEmpleado',  $idEmpleado);
                $stmt->bindValue(':nomina_empleado_id',  $idNominaEmpleado);
                $stmt->bindValue(":fecha_alta", $fecha_actualizacion);
                $stmt->bindValue(":fecha_edicion", $fecha_actualizacion);
                $stmt->bindValue(":usuario_alta", $_SESSION['PKUsuario']);
                $stmt->bindValue(":usuario_edicion", $_SESSION['PKUsuario']);
                $stmt->execute();
              }

          }


          //071 Ajuste en Subsidio para el empleo (efectivamente entregado al trabajador)
          $stmt = $conn->prepare(' SELECT id FROM detalle_nomina_deduccion_empleado WHERE empleado_id = :idEmpleado AND nomina_empleado_id = :nomina_empleado_id AND relacion_tipo_deduccion_id = 71 ');
          $stmt->bindValue(':idEmpleado',  $idEmpleado);
          $stmt->bindValue(':nomina_empleado_id',  $idNominaEmpleado);
          $stmt->execute();  
          $existe_subsidio_entregado = $stmt->rowCount(); 

          if($suma_sae > 0){

              if($existe_subsidio_entregado > 0){
                $stmt = $conn->prepare('UPDATE detalle_nomina_deduccion_empleado SET importe = :importe, fecha_edicion = :fecha_edicion, usuario_edicion = :usuario_edicion WHERE empleado_id = :idEmpleado AND nomina_empleado_id = :nomina_empleado_id AND relacion_tipo_deduccion_id = 71 ');
                $stmt->bindValue(":importe", $suma_sae);
                $stmt->bindValue(":fecha_edicion", $fecha_actualizacion);
                $stmt->bindValue(":usuario_edicion", $_SESSION['PKUsuario']);
                $stmt->bindValue(':idEmpleado',  $idEmpleado);
                $stmt->bindValue(':nomina_empleado_id',  $idNominaEmpleado);
                $stmt->execute();
              }
              else{

                $stmt = $conn->prepare(' SELECT id FROM relacion_concepto_deduccion WHERE tipo_deduccion_id = :tipo_deduccion_id AND empresa_id = :empresa_id');
                $stmt->bindValue(':tipo_deduccion_id',  71);
                $stmt->bindValue(':empresa_id',  $_SESSION['IDEmpresa']);
                $stmt->execute();  
                $existe_concepto_unico = $stmt->rowCount(); 

                if($existe_concepto_unico > 0){
                  $rowConceptoUnico = $stmt->fetch();
                  $idConceptoUnico = $rowConceptoUnico['id'];
                }
                else{

                  $stmt = $conn->prepare('INSERT INTO relacion_concepto_deduccion ( concepto_nomina, tipo_deduccion_id,  empresa_id) VALUES (  :concepto_nomina, :tipo_deduccion_id, :empresa_id )');
                  $stmt->bindValue(":concepto_nomina", "Ajuste en Subsidio para el empleo");
                  $stmt->bindValue(":tipo_deduccion_id", 71);
                  $stmt->bindValue(":empresa_id", $_SESSION['IDEmpresa']);
                  $stmt->execute();

                  $idConceptoUnico = $conn->lastInsertId();
                  
                }

                $stmt = $conn->prepare('INSERT INTO detalle_nomina_deduccion_empleado ( relacion_tipo_deduccion_id, relacion_concepto_deduccion_id, tipo_concepto, dias, horas, incapacidad, importe, importe_exento, exento, empleado_id, nomina_empleado_id, fecha_alta, fecha_edicion, usuario_alta, usuario_edicion ) VALUES (  :relacion_tipo_deduccion_id, :relacion_concepto_deduccion_id, 9, 0, 0, 0, :importe, 0.00, 0, :idEmpleado, :nomina_empleado_id, :fecha_alta, :fecha_edicion, :usuario_alta, :usuario_edicion )');
                $stmt->bindValue(":relacion_tipo_deduccion_id", 71);
                $stmt->bindValue(":relacion_concepto_deduccion_id", $idConceptoUnico);
                $stmt->bindValue(":importe", $suma_sae);
                $stmt->bindValue(':idEmpleado',  $idEmpleado);
                $stmt->bindValue(':nomina_empleado_id',  $idNominaEmpleado);
                $stmt->bindValue(":fecha_alta", $fecha_actualizacion);
                $stmt->bindValue(":fecha_edicion", $fecha_actualizacion);
                $stmt->bindValue(":usuario_alta", $_SESSION['PKUsuario']);
                $stmt->bindValue(":usuario_edicion", $_SESSION['PKUsuario']);
                $stmt->execute();
              }

          }



          //Subsidio efectivamente entregado que no correpondia 008
          $stmt = $conn->prepare(' SELECT id FROM detalle_otros_pagos_nomina_empleado WHERE empleado_id = :idEmpleado AND nomina_empleado_id = :nomina_empleado_id AND otros_pagos_id = 8 ');
          $stmt->bindValue(':idEmpleado',  $idEmpleado);
          $stmt->bindValue(':nomina_empleado_id',  $idNominaEmpleado);
          $stmt->execute();  
          $existe_subsidio_entregado_no_corresponde = $stmt->rowCount(); 

          if($suma_sae > 0){

              if($existe_subsidio_entregado_no_corresponde > 0){
                $stmt = $conn->prepare('UPDATE detalle_otros_pagos_nomina_empleado SET importe = :importe, fecha_edicion = :fecha_edicion, usuario_edicion = :usuario_edicion WHERE empleado_id = :idEmpleado AND nomina_empleado_id = :nomina_empleado_id AND otros_pagos_id = 8 ');
                $stmt->bindValue(":importe", $suma_sae);
                $stmt->bindValue(":fecha_edicion", $fecha_actualizacion);
                $stmt->bindValue(":usuario_edicion", $_SESSION['PKUsuario']);
                $stmt->bindValue(':idEmpleado',  $idEmpleado);
                $stmt->bindValue(':nomina_empleado_id',  $idNominaEmpleado);
                $stmt->execute();
              }
              else{

                $stmt = $conn->prepare(' SELECT id FROM relacion_concepto_otros_pagos WHERE tipo_otros_pagos_id = :tipo_otros_pagos_id AND empresa_id = :empresa_id');
                $stmt->bindValue(':tipo_otros_pagos_id',  8);
                $stmt->bindValue(':empresa_id',  $_SESSION['IDEmpresa']);
                $stmt->execute();  
                $existe_concepto_unico = $stmt->rowCount(); 

                if($existe_concepto_unico > 0){
                  $rowConceptoUnico = $stmt->fetch();
                  $idConceptoUnico = $rowConceptoUnico['id'];
                }
                else{

                  $stmt = $conn->prepare('INSERT INTO relacion_concepto_otros_pagos ( concepto_nomina, tipo_otros_pagos_id,  empresa_id) VALUES (  :concepto_nomina, :tipo_otros_pagos_id, :empresa_id )');
                  $stmt->bindValue(":concepto_nomina", "Subsidio efectivamente entregado que no correspondía");
                  $stmt->bindValue(":tipo_otros_pagos_id", 8);
                  $stmt->bindValue(":empresa_id", $_SESSION['IDEmpresa']);
                  $stmt->execute();

                  $idConceptoUnico = $conn->lastInsertId();
                }

                $stmt = $conn->prepare('INSERT INTO detalle_otros_pagos_nomina_empleado ( otros_pagos_id, relacion_concepto_otros_pagos_id, importe, empleado_id, nomina_empleado_id, fecha_alta, fecha_edicion, usuario_alta, usuario_edicion ) VALUES (  :otros_pagos_id, :relacion_concepto_otros_pagos_id, :importe, :idEmpleado, :nomina_empleado_id, :fecha_alta, :fecha_edicion, :usuario_alta, :usuario_edicion )');
                $stmt->bindValue(":otros_pagos_id", 8);
                $stmt->bindValue(":relacion_concepto_otros_pagos_id", $idConceptoUnico);
                $stmt->bindValue(":importe", $suma_sae);
                $stmt->bindValue(':idEmpleado',  $idEmpleado);
                $stmt->bindValue(':nomina_empleado_id',  $idNominaEmpleado);
                $stmt->bindValue(":fecha_alta", $fecha_actualizacion);
                $stmt->bindValue(":fecha_edicion", $fecha_actualizacion);
                $stmt->bindValue(":usuario_alta", $_SESSION['PKUsuario']);
                $stmt->bindValue(":usuario_edicion", $_SESSION['PKUsuario']);
                $stmt->execute();
              }

          }



    }


    ////////////////////////////////////////////////////////            CASO 3               ///////////////////////////////////////////////////////////////////////////////////////
    if($tipo_calculo == 3){

          $stmt = $conn->prepare(' SELECT id FROM detalle_nomina_deduccion_empleado WHERE empleado_id = :idEmpleado AND nomina_empleado_id = :nomina_empleado_id AND relacion_tipo_deduccion_id = 107 ');
          $stmt->bindValue(':idEmpleado',  $idEmpleado);
          $stmt->bindValue(':nomina_empleado_id',  $idNominaEmpleado);
          $stmt->execute();  
          $existe_ajuste_subsidio = $stmt->rowCount(); 


          //107
          if($suma_subsidio_causado > 0){
              if($existe_ajuste_subsidio > 0){
                $stmt = $conn->prepare('UPDATE detalle_nomina_deduccion_empleado SET importe = :importe, fecha_edicion = :fecha_edicion, usuario_edicion = :usuario_edicion WHERE empleado_id = :idEmpleado AND nomina_empleado_id = :nomina_empleado_id AND relacion_tipo_deduccion_id = 107 ');
                $stmt->bindValue(":importe", $suma_subsidio_causado);
                $stmt->bindValue(":fecha_edicion", $fecha_actualizacion);
                $stmt->bindValue(":usuario_edicion", $_SESSION['PKUsuario']);
                $stmt->bindValue(':idEmpleado',  $idEmpleado);
                $stmt->bindValue(':nomina_empleado_id',  $idNominaEmpleado);
                $stmt->execute();
              }
              else{

                $stmt = $conn->prepare(' SELECT id FROM relacion_concepto_deduccion WHERE tipo_deduccion_id = :tipo_deduccion_id AND empresa_id = :empresa_id');
                $stmt->bindValue(':tipo_deduccion_id',  107);
                $stmt->bindValue(':empresa_id',  $_SESSION['IDEmpresa']);
                $stmt->execute();  
                $existe_concepto_unico = $stmt->rowCount(); 

                if($existe_concepto_unico > 0){
                  $rowConceptoUnico = $stmt->fetch();
                  $idConceptoUnico = $rowConceptoUnico['id'];
                }
                else{

                  $stmt = $conn->prepare('INSERT INTO relacion_concepto_deduccion ( concepto_nomina, tipo_deduccion_id,  empresa_id) VALUES (  :concepto_nomina, :tipo_deduccion_id, :empresa_id )');
                  $stmt->bindValue(":concepto_nomina", "Ajuste al subsidio causado");
                  $stmt->bindValue(":tipo_deduccion_id", 107);
                  $stmt->bindValue(":empresa_id", $_SESSION['IDEmpresa']);
                  $stmt->execute();

                  $idConceptoUnico = $conn->lastInsertId();
                  
                }
// echo "  idUsuario ".$_SESSION['PKUsuario']." importe ".$suma_subsidio_causado." idEmpleado ".$idEmpleado."  nomina_empleado_id ".$idNominaEmpleado."  fecha alta ".$fecha_actualizacion;
                $stmt = $conn->prepare('INSERT INTO detalle_nomina_deduccion_empleado ( relacion_tipo_deduccion_id, relacion_concepto_deduccion_id,  tipo_concepto, dias, horas, incapacidad, importe, importe_exento, exento, empleado_id, nomina_empleado_id, fecha_alta, fecha_edicion, usuario_alta, usuario_edicion ) VALUES (  :relacion_tipo_deduccion_id, :relacion_concepto_deduccion_id, 9, 0, 0, 0, :importe, 0.00, 0, :idEmpleado, :nomina_empleado_id, :fecha_alta, :fecha_edicion, :usuario_alta, :usuario_edicion )');
                $stmt->bindValue(":relacion_tipo_deduccion_id", 107);
                $stmt->bindValue(":relacion_concepto_deduccion_id", $idConceptoUnico);
                $stmt->bindValue(":importe", $suma_subsidio_causado);
                $stmt->bindValue(':idEmpleado',  $idEmpleado);
                $stmt->bindValue(':nomina_empleado_id',  $idNominaEmpleado);
                $stmt->bindValue(":fecha_alta", $fecha_actualizacion);
                $stmt->bindValue(":fecha_edicion", $fecha_actualizacion);
                $stmt->bindValue(":usuario_alta", $_SESSION['PKUsuario']);
                $stmt->bindValue(":usuario_edicion", $_SESSION['PKUsuario']);
                $stmt->execute();
              }
          }


          //002 este es el isr ajustado que es el mismo ISR pero igual hay qu agregarlo
          $stmt = $conn->prepare(' SELECT id FROM detalle_nomina_deduccion_empleado WHERE empleado_id = :idEmpleado AND nomina_empleado_id = :nomina_empleado_id AND relacion_tipo_deduccion_id = 2 ');
          $stmt->bindValue(':idEmpleado',  $idEmpleado);
          $stmt->bindValue(':nomina_empleado_id',  $idNominaEmpleado);
          $stmt->execute();  
          $existe_isr_corregir = $stmt->rowCount(); 

          if($suma_subsidio_causado > 0){

              if($existe_isr_corregir > 0){
                $stmt = $conn->prepare('UPDATE detalle_nomina_deduccion_empleado SET importe = :importe, fecha_edicion = :fecha_edicion, usuario_edicion = :usuario_edicion WHERE empleado_id = :idEmpleado AND nomina_empleado_id = :nomina_empleado_id AND relacion_tipo_deduccion_id = 2 ');
                $stmt->bindValue(":importe", $suma_subsidio_causado);
                $stmt->bindValue(":fecha_edicion", $fecha_actualizacion);
                $stmt->bindValue(":usuario_edicion", $_SESSION['PKUsuario']);
                $stmt->bindValue(':idEmpleado',  $idEmpleado);
                $stmt->bindValue(':nomina_empleado_id',  $idNominaEmpleado);
                $stmt->execute();
              }
              else{

                $stmt = $conn->prepare(' SELECT id FROM relacion_concepto_deduccion WHERE tipo_deduccion_id = :tipo_deduccion_id AND empresa_id = :empresa_id');
                $stmt->bindValue(':tipo_deduccion_id',  2);
                $stmt->bindValue(':empresa_id',  $_SESSION['IDEmpresa']);
                $stmt->execute();  
                $existe_concepto_unico = $stmt->rowCount(); 

                if($existe_concepto_unico > 0){
                  $rowConceptoUnico = $stmt->fetch();
                  $idConceptoUnico = $rowConceptoUnico['id'];
                }
                else{

                  $stmt = $conn->prepare('INSERT INTO relacion_concepto_deduccion ( concepto_nomina, tipo_deduccion_id,  empresa_id) VALUES (  :concepto_nomina, :tipo_deduccion_id, :empresa_id )');
                  $stmt->bindValue(":concepto_nomina", "ISR");
                  $stmt->bindValue(":tipo_deduccion_id", 2);
                  $stmt->bindValue(":empresa_id", $_SESSION['IDEmpresa']);
                  $stmt->execute();

                  $idConceptoUnico = $conn->lastInsertId();
                }

                $stmt = $conn->prepare('INSERT INTO detalle_nomina_deduccion_empleado ( relacion_tipo_deduccion_id, relacion_concepto_deduccion_id, tipo_concepto, dias, horas, incapacidad, importe, importe_exento, exento, empleado_id, nomina_empleado_id, fecha_alta, fecha_edicion, usuario_alta, usuario_edicion ) VALUES (  :relacion_tipo_deduccion_id, :relacion_concepto_deduccion_id, 9, 0, 0, 0, :importe, 0.00, 0, :idEmpleado, :nomina_empleado_id, :fecha_alta, :fecha_edicion, :usuario_alta, :usuario_edicion )');
                $stmt->bindValue(":relacion_tipo_deduccion_id", 2);
                $stmt->bindValue(":relacion_concepto_deduccion_id", $idConceptoUnico);
                $stmt->bindValue(":importe", $suma_subsidio_causado);
                $stmt->bindValue(':idEmpleado',  $idEmpleado);
                $stmt->bindValue(':nomina_empleado_id',  $idNominaEmpleado);
                $stmt->bindValue(":fecha_alta", $fecha_actualizacion);
                $stmt->bindValue(":fecha_edicion", $fecha_actualizacion);
                $stmt->bindValue(":usuario_alta", $_SESSION['PKUsuario']);
                $stmt->bindValue(":usuario_edicion", $_SESSION['PKUsuario']);
                $stmt->execute();
              }

          }


          //ISR ajustado por subsidio
          $stmt = $conn->prepare(' SELECT id FROM detalle_otros_pagos_nomina_empleado WHERE empleado_id = :idEmpleado AND nomina_empleado_id = :nomina_empleado_id AND otros_pagos_id = 7 ');
          $stmt->bindValue(':idEmpleado',  $idEmpleado);
          $stmt->bindValue(':nomina_empleado_id',  $idNominaEmpleado);
          $stmt->execute();  
          $existe_isr_ajuste_subsidio = $stmt->rowCount(); 

          if($suma_subsidio_causado > 0){

              if($existe_isr_ajuste_subsidio > 0){
                $stmt = $conn->prepare('UPDATE detalle_otros_pagos_nomina_empleado SET importe = :importe, fecha_edicion = :fecha_edicion, usuario_edicion = :usuario_edicion WHERE empleado_id = :idEmpleado AND nomina_empleado_id = :nomina_empleado_id AND otros_pagos_id = 7 ');
                $stmt->bindValue(":importe", $suma_subsidio_causado);
                $stmt->bindValue(":fecha_edicion", $fecha_actualizacion);
                $stmt->bindValue(":usuario_edicion", $_SESSION['PKUsuario']);
                $stmt->bindValue(':idEmpleado',  $idEmpleado);
                $stmt->bindValue(':nomina_empleado_id',  $idNominaEmpleado);
                $stmt->execute();
              }
              else{

                $stmt = $conn->prepare(' SELECT id FROM relacion_concepto_otros_pagos WHERE tipo_otros_pagos_id = :tipo_otros_pagos_id AND empresa_id = :empresa_id');
                $stmt->bindValue(':tipo_otros_pagos_id',  7);
                $stmt->bindValue(':empresa_id',  $_SESSION['IDEmpresa']);
                $stmt->execute();  
                $existe_concepto_unico = $stmt->rowCount(); 

                if($existe_concepto_unico > 0){
                  $rowConceptoUnico = $stmt->fetch();
                  $idConceptoUnico = $rowConceptoUnico['id'];
                }
                else{

                  $stmt = $conn->prepare('INSERT INTO relacion_concepto_otros_pagos ( concepto_nomina, tipo_otros_pagos_id,  empresa_id) VALUES (  :concepto_nomina, :tipo_otros_pagos_id, :empresa_id )');
                  $stmt->bindValue(":concepto_nomina", "ISR ajustado por subsidio");
                  $stmt->bindValue(":tipo_otros_pagos_id", 7);
                  $stmt->bindValue(":empresa_id", $_SESSION['IDEmpresa']);
                  $stmt->execute();

                  $idConceptoUnico = $conn->lastInsertId();
                }

                $stmt = $conn->prepare('INSERT INTO detalle_otros_pagos_nomina_empleado ( otros_pagos_id, relacion_concepto_otros_pagos_id, importe, empleado_id, nomina_empleado_id, fecha_alta, fecha_edicion, usuario_alta, usuario_edicion ) VALUES (  :otros_pagos_id, :relacion_concepto_otros_pagos_id, :importe, :idEmpleado, :nomina_empleado_id, :fecha_alta, :fecha_edicion, :usuario_alta, :usuario_edicion )');
                $stmt->bindValue(":otros_pagos_id", 7);
                $stmt->bindValue(":relacion_concepto_otros_pagos_id", $idConceptoUnico);
                $stmt->bindValue(":importe", $suma_subsidio_causado);
                $stmt->bindValue(':idEmpleado',  $idEmpleado);
                $stmt->bindValue(':nomina_empleado_id',  $idNominaEmpleado);
                $stmt->bindValue(":fecha_alta", $fecha_actualizacion);
                $stmt->bindValue(":fecha_edicion", $fecha_actualizacion);
                $stmt->bindValue(":usuario_alta", $_SESSION['PKUsuario']);
                $stmt->bindValue(":usuario_edicion", $_SESSION['PKUsuario']);
                $stmt->execute();
              }

          }

    }


    ////////////////////////////////////////////////////////            CASO 4               ///////////////////////////////////////////////////////////////////////////////////////
    if($tipo_calculo == 4){
      
      //071 Ajuste en Subsidio para el empleo (efectivamente entregado al trabajador)
      $stmt = $conn->prepare(' SELECT id FROM detalle_nomina_deduccion_empleado WHERE empleado_id = :idEmpleado AND nomina_empleado_id = :nomina_empleado_id AND relacion_tipo_deduccion_id = 71 ');
      $stmt->bindValue(':idEmpleado',  $idEmpleado);
      $stmt->bindValue(':nomina_empleado_id',  $idNominaEmpleado);
      $stmt->execute();  
      $existe_subsidio_entregado = $stmt->rowCount(); 

      if($suma_sae > 0){

          if($existe_subsidio_entregado > 0){
            $stmt = $conn->prepare('UPDATE detalle_nomina_deduccion_empleado SET importe = :importe, fecha_edicion = :fecha_edicion, usuario_edicion = :usuario_edicion WHERE empleado_id = :idEmpleado AND nomina_empleado_id = :nomina_empleado_id AND relacion_tipo_deduccion_id = 71 ');
            $stmt->bindValue(":importe", $suma_sae);
            $stmt->bindValue(":fecha_edicion", $fecha_actualizacion);
            $stmt->bindValue(":usuario_edicion", $_SESSION['PKUsuario']);
            $stmt->bindValue(':idEmpleado',  $idEmpleado);
            $stmt->bindValue(':nomina_empleado_id',  $idNominaEmpleado);
            $stmt->execute();
          }
          else{

            $stmt = $conn->prepare(' SELECT id FROM relacion_concepto_deduccion WHERE tipo_deduccion_id = :tipo_deduccion_id AND empresa_id = :empresa_id');
            $stmt->bindValue(':tipo_deduccion_id',  71);
            $stmt->bindValue(':empresa_id',  $_SESSION['IDEmpresa']);
            $stmt->execute();  
            $existe_concepto_unico = $stmt->rowCount(); 

            if($existe_concepto_unico > 0){
              $rowConceptoUnico = $stmt->fetch();
              $idConceptoUnico = $rowConceptoUnico['id'];
            }
            else{

              $stmt = $conn->prepare('INSERT INTO relacion_concepto_deduccion ( concepto_nomina, tipo_deduccion_id,  empresa_id) VALUES (  :concepto_nomina, :tipo_deduccion_id, :empresa_id )');
              $stmt->bindValue(":concepto_nomina", "Ajuste en Subsidio para el empleo");
              $stmt->bindValue(":tipo_deduccion_id", 71);
              $stmt->bindValue(":empresa_id", $_SESSION['IDEmpresa']);
              $stmt->execute();

              $idConceptoUnico = $conn->lastInsertId();
              
            }

            $stmt = $conn->prepare('INSERT INTO detalle_nomina_deduccion_empleado ( relacion_tipo_deduccion_id, relacion_concepto_deduccion_id, tipo_concepto, dias, horas, incapacidad, importe, importe_exento, exento, empleado_id, nomina_empleado_id, fecha_alta, fecha_edicion, usuario_alta, usuario_edicion ) VALUES (  :relacion_tipo_deduccion_id, :relacion_concepto_deduccion_id, 9, 0, 0, 0, :importe, 0.00, 0, :idEmpleado, :nomina_empleado_id, :fecha_alta, :fecha_edicion, :usuario_alta, :usuario_edicion )');
            $stmt->bindValue(":relacion_tipo_deduccion_id", 71);
            $stmt->bindValue(":relacion_concepto_deduccion_id", $idConceptoUnico);
            $stmt->bindValue(":importe", $suma_sae);
            $stmt->bindValue(':idEmpleado',  $idEmpleado);
            $stmt->bindValue(':nomina_empleado_id',  $idNominaEmpleado);
            $stmt->bindValue(":fecha_alta", $fecha_actualizacion);
            $stmt->bindValue(":fecha_edicion", $fecha_actualizacion);
            $stmt->bindValue(":usuario_alta", $_SESSION['PKUsuario']);
            $stmt->bindValue(":usuario_edicion", $_SESSION['PKUsuario']);
            $stmt->execute();
          }

      }


      $stmt = $conn->prepare('UPDATE nomina_empleado SET ISR = :isr, SAE = :sae  WHERE PKNomina = :idNomina ');
      $stmt->bindValue(":isr", 0.00);
      $stmt->bindValue(":sae", 0.00);
      $stmt->bindValue(":idNomina", $idNominaEmpleado);
      $stmt->execute();

    }

    ////////////////////////////////////////////////////////            CASO 5                ///////////////////////////////////////////////////////////////////////////////////////
    if($tipo_calculo == 5){

      $subsidio_entregado_menos = bcdiv($calculo_mensual[1] - ($suma_sae + $ultimo_sae),1,2);  
      //echo "   subsidio_entregado_menos  ".$subsidio_entregado_menos;
      
      //print_r($calculo_mensual);

      //Subsidio entregado de menos 002
      $stmt = $conn->prepare(' SELECT id FROM detalle_otros_pagos_nomina_empleado WHERE empleado_id = :idEmpleado AND nomina_empleado_id = :nomina_empleado_id AND otros_pagos_id = 2 ');
      $stmt->bindValue(':idEmpleado',  $idEmpleado);
      $stmt->bindValue(':nomina_empleado_id',  $idNominaEmpleado);
      $stmt->execute();  
      $existe_subsidio_entregado_menos = $stmt->rowCount(); 

      if($subsidio_entregado_menos > 0){

          if($existe_subsidio_entregado_menos > 0){
            $stmt = $conn->prepare('UPDATE detalle_otros_pagos_nomina_empleado SET importe = :importe, fecha_edicion = :fecha_edicion, usuario_edicion = :usuario_edicion WHERE empleado_id = :idEmpleado AND nomina_empleado_id = :nomina_empleado_id AND otros_pagos_id = 2 ');
            $stmt->bindValue(":importe", $subsidio_entregado_menos);
            $stmt->bindValue(":fecha_edicion", $fecha_actualizacion);
            $stmt->bindValue(":usuario_edicion", $_SESSION['PKUsuario']);
            $stmt->bindValue(':idEmpleado',  $idEmpleado);
            $stmt->bindValue(':nomina_empleado_id',  $idNominaEmpleado);
            $stmt->execute();
          }
          else{

            $stmt = $conn->prepare(' SELECT id FROM relacion_concepto_otros_pagos WHERE tipo_otros_pagos_id = :tipo_otros_pagos_id AND empresa_id = :empresa_id');
            $stmt->bindValue(':tipo_otros_pagos_id',  2);
            $stmt->bindValue(':empresa_id',  $_SESSION['IDEmpresa']);
            $stmt->execute();  
            $existe_concepto_unico = $stmt->rowCount(); 

            if($existe_concepto_unico > 0){
              $rowConceptoUnico = $stmt->fetch();
              $idConceptoUnico = $rowConceptoUnico['id'];
            }
            else{

              $stmt = $conn->prepare('INSERT INTO relacion_concepto_otros_pagos ( concepto_nomina, tipo_otros_pagos_id,  empresa_id) VALUES (  :concepto_nomina, :tipo_otros_pagos_id, :empresa_id )');
              $stmt->bindValue(":concepto_nomina", "Subsidio para el empleo");
              $stmt->bindValue(":tipo_otros_pagos_id", 2);
              $stmt->bindValue(":empresa_id", $_SESSION['IDEmpresa']);
              $stmt->execute();

              $idConceptoUnico = $conn->lastInsertId();
            }

            $stmt = $conn->prepare('INSERT INTO detalle_otros_pagos_nomina_empleado ( otros_pagos_id, relacion_concepto_otros_pagos_id, importe, empleado_id, nomina_empleado_id, fecha_alta, fecha_edicion, usuario_alta, usuario_edicion ) VALUES (  :otros_pagos_id, :relacion_concepto_otros_pagos_id, :importe, :idEmpleado, :nomina_empleado_id, :fecha_alta, :fecha_edicion, :usuario_alta, :usuario_edicion )');
            $stmt->bindValue(":otros_pagos_id", 2);
            $stmt->bindValue(":relacion_concepto_otros_pagos_id", $idConceptoUnico);
            $stmt->bindValue(":importe", $subsidio_entregado_menos);
            $stmt->bindValue(':idEmpleado',  $idEmpleado);
            $stmt->bindValue(':nomina_empleado_id',  $idNominaEmpleado);
            $stmt->bindValue(":fecha_alta", $fecha_actualizacion);
            $stmt->bindValue(":fecha_edicion", $fecha_actualizacion);
            $stmt->bindValue(":usuario_alta", $_SESSION['PKUsuario']);
            $stmt->bindValue(":usuario_edicion", $_SESSION['PKUsuario']);
            $stmt->execute();
          }

      }
    }

    
    ////////////////////////////////////////////////////////            CASO 6                ///////////////////////////////////////////////////////////////////////////////////////
    if($tipo_calculo == 6){
      
      $stmt = $conn->prepare(' SELECT id FROM detalle_nomina_deduccion_empleado WHERE empleado_id = :idEmpleado AND nomina_empleado_id = :nomina_empleado_id AND relacion_tipo_deduccion_id = 107 ');
      $stmt->bindValue(':idEmpleado',  $idEmpleado);
      $stmt->bindValue(':nomina_empleado_id',  $idNominaEmpleado);
      $stmt->execute();  
      $existe_ajuste_subsidio = $stmt->rowCount(); 

      $ajuste_subsidio_causado =  bcdiv($suma_subsidio_causado - $calculo_mensual[3], 1, 2);

      if($suma_subsidio_causado > 0){

          if($existe_ajuste_subsidio > 0){
            $stmt = $conn->prepare('UPDATE detalle_nomina_deduccion_empleado SET importe = :importe, fecha_edicion = :fecha_edicion, usuario_edicion = :usuario_edicion WHERE empleado_id = :idEmpleado AND nomina_empleado_id = :nomina_empleado_id AND relacion_tipo_deduccion_id = 107 ');
            $stmt->bindValue(":importe", $ajuste_subsidio_causado);
            $stmt->bindValue(":fecha_edicion", $fecha_actualizacion);
            $stmt->bindValue(":usuario_edicion", $_SESSION['PKUsuario']);
            $stmt->bindValue(':idEmpleado',  $idEmpleado);
            $stmt->bindValue(':nomina_empleado_id',  $idNominaEmpleado);
            $stmt->execute();
          }
          else{

            $stmt = $conn->prepare(' SELECT id FROM relacion_concepto_deduccion WHERE tipo_deduccion_id = :tipo_deduccion_id AND empresa_id = :empresa_id');
            $stmt->bindValue(':tipo_deduccion_id',  107);
            $stmt->bindValue(':empresa_id',  $_SESSION['IDEmpresa']);
            $stmt->execute();  
            $existe_concepto_unico = $stmt->rowCount(); 

            if($existe_concepto_unico > 0){
              $rowConceptoUnico = $stmt->fetch();
              $idConceptoUnico = $rowConceptoUnico['id'];
            }
            else{

              $stmt = $conn->prepare('INSERT INTO relacion_concepto_deduccion ( concepto_nomina, tipo_deduccion_id,  empresa_id) VALUES (  :concepto_nomina, :tipo_deduccion_id, :empresa_id )');
              $stmt->bindValue(":concepto_nomina", "Ajuste al subsidio causado");
              $stmt->bindValue(":tipo_deduccion_id", 107);
              $stmt->bindValue(":empresa_id", $_SESSION['IDEmpresa']);
              $stmt->execute();

              $idConceptoUnico = $conn->lastInsertId();
              
            }


            $stmt = $conn->prepare('INSERT INTO detalle_nomina_deduccion_empleado ( relacion_tipo_deduccion_id, relacion_concepto_deduccion_id, tipo_concepto, dias, horas, incapacidad, importe, importe_exento, exento, empleado_id, nomina_empleado_id, fecha_alta, fecha_edicion, usuario_alta, usuario_edicion ) VALUES (  :relacion_tipo_deduccion_id, :relacion_concepto_deduccion_id, 9, 0, 0, 0, :importe, 0.00, 0, :idEmpleado, :nomina_empleado_id, :fecha_alta, :fecha_edicion, :usuario_alta, :usuario_edicion )');
            $stmt->bindValue(":relacion_tipo_deduccion_id", 107);
            $stmt->bindValue(":relacion_concepto_deduccion_id", $idConceptoUnico);
            $stmt->bindValue(":importe", $ajuste_subsidio_causado);
            $stmt->bindValue(':idEmpleado',  $idEmpleado);
            $stmt->bindValue(':nomina_empleado_id',  $idNominaEmpleado);
            $stmt->bindValue(":fecha_alta", $fecha_actualizacion);
            $stmt->bindValue(":fecha_edicion", $fecha_actualizacion);
            $stmt->bindValue(":usuario_alta", $_SESSION['PKUsuario']);
            $stmt->bindValue(":usuario_edicion", $_SESSION['PKUsuario']);
            $stmt->execute();
          }

      }

      //071 Ajuste en Subsidio para el empleo (efectivamente entregado al trabajador)
      $stmt = $conn->prepare(' SELECT id FROM detalle_nomina_deduccion_empleado WHERE empleado_id = :idEmpleado AND nomina_empleado_id = :nomina_empleado_id AND relacion_tipo_deduccion_id = 71 ');
      $stmt->bindValue(':idEmpleado',  $idEmpleado);
      $stmt->bindValue(':nomina_empleado_id',  $idNominaEmpleado);
      $stmt->execute();  
      $existe_subsidio_entregado = $stmt->rowCount(); 

      if($suma_sae > 0){

          if($existe_subsidio_entregado > 0){
            $stmt = $conn->prepare('UPDATE detalle_nomina_deduccion_empleado SET importe = :importe, fecha_edicion = :fecha_edicion, usuario_edicion = :usuario_edicion WHERE empleado_id = :idEmpleado AND nomina_empleado_id = :nomina_empleado_id AND relacion_tipo_deduccion_id = 71 ');
            $stmt->bindValue(":importe", $suma_sae);
            $stmt->bindValue(":fecha_edicion", $fecha_actualizacion);
            $stmt->bindValue(":usuario_edicion", $_SESSION['PKUsuario']);
            $stmt->bindValue(':idEmpleado',  $idEmpleado);
            $stmt->bindValue(':nomina_empleado_id',  $idNominaEmpleado);
            $stmt->execute();
          }
          else{

            $stmt = $conn->prepare(' SELECT id FROM relacion_concepto_deduccion WHERE tipo_deduccion_id = :tipo_deduccion_id AND empresa_id = :empresa_id');
            $stmt->bindValue(':tipo_deduccion_id',  71);
            $stmt->bindValue(':empresa_id',  $_SESSION['IDEmpresa']);
            $stmt->execute();  
            $existe_concepto_unico = $stmt->rowCount(); 

            if($existe_concepto_unico > 0){
              $rowConceptoUnico = $stmt->fetch();
              $idConceptoUnico = $rowConceptoUnico['id'];
            }
            else{

              $stmt = $conn->prepare('INSERT INTO relacion_concepto_deduccion ( concepto_nomina, tipo_deduccion_id,  empresa_id) VALUES (  :concepto_nomina, :tipo_deduccion_id, :empresa_id )');
              $stmt->bindValue(":concepto_nomina", "Ajuste en Subsidio para el empleo");
              $stmt->bindValue(":tipo_deduccion_id", 71);
              $stmt->bindValue(":empresa_id", $_SESSION['IDEmpresa']);
              $stmt->execute();

              $idConceptoUnico = $conn->lastInsertId();
              
            }

            $stmt = $conn->prepare('INSERT INTO detalle_nomina_deduccion_empleado ( relacion_tipo_deduccion_id, relacion_concepto_deduccion_id, tipo_concepto, dias, horas, incapacidad, importe, importe_exento, exento, empleado_id, nomina_empleado_id, fecha_alta, fecha_edicion, usuario_alta, usuario_edicion ) VALUES (  :relacion_tipo_deduccion_id, :relacion_concepto_deduccion_id, 9, 0, 0, 0, :importe, 0.00, 0, :idEmpleado, :nomina_empleado_id, :fecha_alta, :fecha_edicion, :usuario_alta, :usuario_edicion )');
            $stmt->bindValue(":relacion_tipo_deduccion_id", 71);
            $stmt->bindValue(":relacion_concepto_deduccion_id", $idConceptoUnico);
            $stmt->bindValue(":importe", $suma_sae);
            $stmt->bindValue(':idEmpleado',  $idEmpleado);
            $stmt->bindValue(':nomina_empleado_id',  $idNominaEmpleado);
            $stmt->bindValue(":fecha_alta", $fecha_actualizacion);
            $stmt->bindValue(":fecha_edicion", $fecha_actualizacion);
            $stmt->bindValue(":usuario_alta", $_SESSION['PKUsuario']);
            $stmt->bindValue(":usuario_edicion", $_SESSION['PKUsuario']);
            $stmt->execute();
          }

      }

      //Subsidio efectivamente entregado que no correpondia 008
      $stmt = $conn->prepare(' SELECT id FROM detalle_otros_pagos_nomina_empleado WHERE empleado_id = :idEmpleado AND nomina_empleado_id = :nomina_empleado_id AND otros_pagos_id = 8 ');
      $stmt->bindValue(':idEmpleado',  $idEmpleado);
      $stmt->bindValue(':nomina_empleado_id',  $idNominaEmpleado);
      $stmt->execute();  
      $existe_subsidio_entregado_no_corresponde = $stmt->rowCount(); 

      if($ajuste_subsidio_causado > 0){

          if($existe_subsidio_entregado_no_corresponde > 0){
            $stmt = $conn->prepare('UPDATE detalle_otros_pagos_nomina_empleado SET importe = :importe, fecha_edicion = :fecha_edicion, usuario_edicion = :usuario_edicion WHERE empleado_id = :idEmpleado AND nomina_empleado_id = :nomina_empleado_id AND otros_pagos_id = 8 ');
            $stmt->bindValue(":importe", $ajuste_subsidio_causado);
            $stmt->bindValue(":fecha_edicion", $fecha_actualizacion);
            $stmt->bindValue(":usuario_edicion", $_SESSION['PKUsuario']);
            $stmt->bindValue(':idEmpleado',  $idEmpleado);
            $stmt->bindValue(':nomina_empleado_id',  $idNominaEmpleado);
            $stmt->execute();
          }
          else{

            $stmt = $conn->prepare(' SELECT id FROM relacion_concepto_otros_pagos WHERE tipo_otros_pagos_id = :tipo_otros_pagos_id AND empresa_id = :empresa_id');
            $stmt->bindValue(':tipo_otros_pagos_id',  8);
            $stmt->bindValue(':empresa_id',  $_SESSION['IDEmpresa']);
            $stmt->execute();  
            $existe_concepto_unico = $stmt->rowCount(); 

            if($existe_concepto_unico > 0){
              $rowConceptoUnico = $stmt->fetch();
              $idConceptoUnico = $rowConceptoUnico['id'];
            }
            else{

              $stmt = $conn->prepare('INSERT INTO relacion_concepto_otros_pagos ( concepto_nomina, tipo_otros_pagos_id,  empresa_id) VALUES (  :concepto_nomina, :tipo_otros_pagos_id, :empresa_id )');
              $stmt->bindValue(":concepto_nomina", "Subsidio efectivamente entregado que no correspondía");
              $stmt->bindValue(":tipo_otros_pagos_id", 8);
              $stmt->bindValue(":empresa_id", $_SESSION['IDEmpresa']);
              $stmt->execute();

              $idConceptoUnico = $conn->lastInsertId();
            }

            $stmt = $conn->prepare('INSERT INTO detalle_otros_pagos_nomina_empleado ( otros_pagos_id, relacion_concepto_otros_pagos_id, importe, empleado_id, nomina_empleado_id, fecha_alta, fecha_edicion, usuario_alta, usuario_edicion ) VALUES (  :otros_pagos_id, :relacion_concepto_otros_pagos_id, :importe, :idEmpleado, :nomina_empleado_id, :fecha_alta, :fecha_edicion, :usuario_alta, :usuario_edicion )');
            $stmt->bindValue(":otros_pagos_id", 8);
            $stmt->bindValue(":relacion_concepto_otros_pagos_id", $idConceptoUnico);
            $stmt->bindValue(":importe", $ajuste_subsidio_causado);
            $stmt->bindValue(':idEmpleado',  $idEmpleado);
            $stmt->bindValue(':nomina_empleado_id',  $idNominaEmpleado);
            $stmt->bindValue(":fecha_alta", $fecha_actualizacion);
            $stmt->bindValue(":fecha_edicion", $fecha_actualizacion);
            $stmt->bindValue(":usuario_alta", $_SESSION['PKUsuario']);
            $stmt->bindValue(":usuario_edicion", $_SESSION['PKUsuario']);
            $stmt->execute();
          }

      }

  }



  //Calculo de conceptos para el total de calculo de impuestos por el ajuste de empleado
    $stmt = $conn->prepare('SELECT 1 as tipo, tipo_concepto, importe, importe_exento, exento, dias, 1 as concepto_id FROM detalle_nomina_percepcion_empleado WHERE empleado_id = :empleado_id AND nomina_empleado_id = :nomina_empleado_id
                        UNION ALL
                        SELECT 2 as tipo, tipo_concepto, importe, 0.00 as importe_exento, exento, dias, 1 as concepto_id FROM detalle_nomina_deduccion_empleado WHERE empleado_id = :empleado_id2 AND nomina_empleado_id = :nomina_empleado_id2
                        UNION ALL
                        SELECT 3 as tipo, 100 as tipo_concepto, importe, 0.00 as importe_exento, 0 as exento,0 as dias, otros_pagos_id as concepto_id FROM detalle_otros_pagos_nomina_empleado WHERE empleado_id = :empleado_id3 AND nomina_empleado_id = :nomina_empleado_id3');
    $stmt->bindValue(':empleado_id', $idEmpleado);
    $stmt->bindValue(':nomina_empleado_id', $idNominaEmpleado);
    $stmt->bindValue(':empleado_id2', $idEmpleado);
    $stmt->bindValue(':nomina_empleado_id2', $idNominaEmpleado);
    $stmt->bindValue(':empleado_id3', $idEmpleado);
    $stmt->bindValue(':nomina_empleado_id3', $idNominaEmpleado);
    $stmt->execute();
    $conceptos = $stmt->fetchAll();

    $totalBase = 0;
    $totalExento = 0;
    $dias_extras = 0;
    $totalHoras = 0;
    $totalPercepcionesExentasUnicas = 0;

    //print_r($conceptos);
    if(count($conceptos) > 0){

          foreach ($conceptos as $c) {

              //salario, percepciones y deducciones, y otros pagos, fonacot(12), infonavit(13), pension alimenticia(14)
              if($c['tipo_concepto'] == 1  || $c['tipo_concepto'] == 2 || $c['tipo_concepto'] == 4 || $c['tipo_concepto'] == 5 || $c['tipo_concepto'] == 100 || $c['tipo_concepto'] == 12 || $c['tipo_concepto'] == 13 || $c['tipo_concepto'] == 14){


                  if($c['exento'] == 1){
                    //percepcion
                    if($c['tipo'] == 1){
                        $totalExento = $totalExento + $c['importe_exento'];
                        $totalPercepcionesExentasUnicas = $totalPercepcionesExentasUnicas + $c['importe_exento'];
                    }
                  }
                  else{
                      //percepcion
                      if($c['tipo'] == 1){
                        $totalBase = $totalBase + $c['importe'];
                        $totalExento = $totalExento + $c['importe_exento'];

                        $totalPercepcionesBaseUnicas = $totalPercepcionesBaseUnicas + $c['importe'];
                        $totalPercepcionesExentasUnicas = $totalPercepcionesExentasUnicas + $c['importe_exento'];
                      }
                      //deduccion
                      if($c['tipo'] == 2){
                        $totalBase = $totalBase - $c['importe'];
                        $totalDeduccionesBaseUnicas = $totalDeduccionesBaseUnicas + $c['importe'];
                      }
                      //otros pagos
                      if($c['tipo'] == 3){

                        if($c['concepto_id'] != 3){
                          $totalBase = $totalBase + $c['importe'];

                          $totalPercepcionesBaseUnicas = $totalPercepcionesBaseUnicas + $c['importe'];
                        }
                        
                      }
                  }

              }

              //prima vacacional
              if($c['tipo_concepto'] == 10){

                  $totalBase = $totalBase + $c['importe'];
                  $totalExento = $totalExento + $c['importe_exento'];

                  $totalPercepcionesBaseUnicas = $totalPercepcionesBaseUnicas + $c['importe'];
                  $totalPercepcionesExentasUnicas = $totalPercepcionesExentasUnicas + $c['importe_exento'];
              }

              //horas simples se gravan al 100%
              if($c['tipo_concepto'] == 3){
                 $totalBase = $totalBase + $c['importe'];

                 $totalPercepcionesBaseUnicas = $totalPercepcionesBaseUnicas + $c['importe'];
              }

              //horas dobles en base al calculo
              if($c['tipo_concepto'] == 7){

                $totalBase = $totalBase + $c['importe'];
                $totalExento = $totalExento + $c['importe_exento'];

                $totalPercepcionesBaseUnicas = $totalPercepcionesBaseUnicas + $c['importe'];
                $totalPercepcionesExentasUnicas = $totalPercepcionesExentasUnicas + $c['importe_exento'];
              }

              //horas triples se gravan al 100%
              if($c['tipo_concepto'] == 8){
                $totalBase = $totalBase + $c['importe'];

                $totalPercepcionesBaseUnicas = $totalPercepcionesBaseUnicas + $c['importe'];
              }

              //prima dominical
              if($c['tipo_concepto'] == 6){

                $totalBase = $totalBase + $c['importe'];
                $totalExento = $totalExento + $c['importe_exento'];
                
              }

              //prima dominical
              if($c['tipo_concepto'] == 6){

                $totalBase = $totalBase + $c['importe'];
                $totalExento = $totalExento + $c['importe_exento'];

                $totalPercepcionesBaseUnicas = $totalPercepcionesBaseUnicas + $c['importe'];
                $totalPercepcionesExentasUnicas = $totalPercepcionesExentasUnicas + $c['importe_exento'];
                
              }

              //deducciona agregadas en el ajuste al subsidio
              if($c['tipo_concepto'] == 9){

                //deduccion
                if($c['tipo'] == 2){
                  $totalBase = $totalBase - $c['importe'];
                }
                
              }


          }

    }


    $stmt = $conn->prepare('SELECT ISR, SAE, cuotaImss FROM nomina_empleado WHERE PKNomina = :idNomina ');
    $stmt->bindValue(":idNomina", $idNominaEmpleado);
    $stmt->execute();
    $datosFinalNomina = $stmt->fetch();

    $totalPercepciones = bcdiv($totalBase + $totalExento,1,2);

    if($datosFinalNomina['SAE'] == 0.00){
        $neto_a_pagar = number_format($totalPercepciones - $datosFinalNomina['ISR'] - $datosFinalNomina['cuotaImss'],2,'.','');        
    }
    else{
        $neto_a_pagar = number_format($totalPercepciones + $datosFinalNomina['SAE'] - $datosFinalNomina['cuotaImss'],2,'.','');
    }

    $stmt = $conn->prepare('UPDATE nomina_empleado SET calculo_subsidio = :calculo_subsidio , Total = :total, TotalNeto = :totalNeto WHERE PKNomina = :idNomina ');
    $stmt->bindValue(":calculo_subsidio", $tipo_calculo);
    $stmt->bindValue(":total", $totalPercepciones);
    $stmt->bindValue(":totalNeto", $neto_a_pagar);
    $stmt->bindValue(":idNomina", $idNominaEmpleado);
    $stmt->execute();

}
//fin de evaluacion de ultima nomina


//actualziación de totales de nomina.
$stmt = $conn->prepare('SELECT SUM(TotalNeto) as total, COUNT(PKNomina) as num_empleados FROM nomina_empleado WHERE FKNomina = '. $idNomina);
$stmt->execute();
$row_cont = $stmt->fetch();
$num_empleados = $row_cont['num_empleados'];
$total = $row_cont['total'];

$stmt = $conn->prepare('UPDATE nomina SET no_empleados = :no_empleados, total = :total WHERE id = :idNomina ');
$stmt->bindValue(':total', $total);
$stmt->bindValue(':no_empleados', $num_empleados);
$stmt->bindValue(':idNomina', $idNomina);
$stmt->execute();