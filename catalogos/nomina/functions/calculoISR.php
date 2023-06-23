<?php
session_start();
$respuesta = new stdClass();
$token = $_POST['csr_token_UT5JP'];

if(empty($_SESSION['token_ld10d'])) {
    $respuesta->estatus = "fallo";
    $respuesta = json_encode($respuesta);
    echo $respuesta;
    return;          
}

if (!hash_equals($_SESSION['token_ld10d'], $token)) {
    $respuesta->estatus = "fallo";
    $respuesta = json_encode($respuesta);
    echo $respuesta;
    return;
}

require_once '../../../include/db-conn.php';

$idEmpleado = $_POST['idEmpleado'];
$idNominaEmpleado = $_POST['idNominaEmpleado'];
$fechaPago = $_POST['fechaPago'];
//echo "fecha pago   ".$fechaPago;
$fechaAnio = DateTime::createFromFormat("Y-m-d", $fechaPago);
$anioCalculo = $fechaAnio->format("Y");
//echo "  anio ".$anioCalculo;
$modo = 2;

//OBTENCION DE DATOS NECESARIOS PARA EL CALCULO
$stmt = $conn->prepare("SELECT dle.Sueldo, pp.DiasPago, dle.FKSucursal FROM datos_laborales_empleado as dle  INNER JOIN periodo_pago as pp ON pp.PKPeriodo_pago = dle.FKPeriodo WHERE FKEmpleado = :idEmpleado");
$stmt->execute(array(':idEmpleado'=>$idEmpleado));
$datosEmpleado = $stmt->fetch();

$idSucursal = $datosEmpleado['FKSucursal'];
$diasPeriodoIMSS = bcdiv($datosEmpleado['DiasPago'], '1', 0);
$base_imss_general = $datosEmpleado['Sueldo'];

$stmt = $conn->prepare('SELECT cantidad FROM parametros WHERE descripcion = "Factor_mes" OR descripcion = "UMA" OR descripcion = "Salario_Minimo_Nacional" OR descripcion = "Salario_Minimo_Norte" ORDER BY PKParametros Asc');
$stmt->execute();
$row_parametros = $stmt->fetchAll();
$UMA = $row_parametros[0]['cantidad'];
$factor_mes = $row_parametros[1]['cantidad'];
$salario_minimo_nacional = $row_parametros[2]['cantidad'];
$salario_minimo_norte = $row_parametros[3]['cantidad'];

$stmt = $conn->prepare('SELECT cantidad FROM parametros_nomina WHERE descripcion = "Dias_Aguinaldo" AND empresa_id = '.$_SESSION['IDEmpresa']);
$stmt->execute();
$row_aguinaldo = $stmt->fetch();
$dias_aguinaldo = $row_aguinaldo['cantidad'];

$stmt = $conn->prepare('SELECT cantidad FROM parametros_nomina WHERE descripcion = "Prima_Vacacional"  AND empresa_id = '.$_SESSION['IDEmpresa']);
$stmt->execute();
$row_prima_vacacional = $stmt->fetch();
$prima_vacacional_tasa = $row_prima_vacacional['cantidad'] / 100;

$stmt = $conn->prepare('SELECT zona_salario_minimo FROM sucursales WHERE id = '.$idSucursal.'  AND empresa_id = '.$_SESSION['IDEmpresa']);
$stmt->execute();
$row_zona = $stmt->fetch();
$zona_salario = $row_zona['zona_salario_minimo'];

$stmt = $conn->prepare('SELECT cantidad FROM parametros_nomina WHERE descripcion = "Riesgo_Trabajo"  AND empresa_id = '.$_SESSION['IDEmpresa']);
$stmt->execute();
$row_riesgo = $stmt->fetch();
$riesgo_trabajo = $row_riesgo['cantidad'];

if($zona_salario == 1){
  $salario_minimo = $salario_minimo_nacional;
}
else{
  $salario_minimo = $salario_minimo_norte;
}

switch ($datosEmpleado['DiasPago']) {
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
//print_r($conceptos);
$totalBase = 0;
$totalExento = 0;
$dias_extras = 0;
$totalHoras = 0;
$totalPercepcionesBaseUnicas = 0;
$totalPercepcionesExentasUnicas = 0;
$totalDeduccionesBaseUnicas = 0;

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
          if($c['tipo_concepto'] == 1  || $c['tipo_concepto'] == 2 || $c['tipo_concepto'] == 4 || $c['tipo_concepto'] == 5 || $c['tipo_concepto'] == 100 || $c['tipo_concepto'] == 12 || $c['tipo_concepto'] == 13 || $c['tipo_concepto'] == 14){

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

      //agregar cantidad de horas extras
      /*
      if(($totalHoras/2) > ($UMA * 5 * $semanas)){
        $cantidadExenta = $UMA * 5 * $semanas; 
        $cantidadBase = $totalHoras - $cantidadExenta;
        $totalBase = $totalBase + $cantidadBase;
        $totalExento = $totalExento + $cantidadExenta;
      }
      else{
        $totalBase = $totalBase + ($totalHoras/2);
        $totalExento = $totalExento + ($totalHoras/2);
      }*/

      //dias trabajados en el periodo para el calculo de las cuotas del IMSS
      $diasTrabajados = $diasPeriodoIMSS + $dias_extras;

      if($diasTrabajados > $diasPeriodoIMSS){
        $diasTrabajados = $diasPeriodoIMSS;
      }

      if($modo == 1){
        $ruta = "";
      }
      if($modo == 2){
        $ruta = "../";
      } 
      //Calculo del salario diario integrado
      require_once($ruta.'../../functions/funcion_calculovacaciones.php');

      $salarioBaseDiario = number_format($base_imss_general / $diasPeriodoIMSS,2, '.', '');// se calculo con lo dias del periodo, ya que no afecta los dias trabajados por el empleado, sino el periodo, al igual el salario, es su pago completo
      $factorSDI = bcdiv(1 + ($dias_aguinaldo + ($dias_vacaciones * $prima_vacacional_tasa)) / 365,1,4);
      $SBC = bcdiv($salarioBaseDiario * $factorSDI,1, 2);//Salario Base Cotizacion o Salario Diario Integrado, es igual


      //calculo impuestos

      /*  $semanaImpuesto = round($diasTrabajadosImpuestos / 7);
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

      $sueldoBaseImpuestos = bcdiv($sueldoSemanal + $salarioTiempoExtraBaseImpuesto - $sueldoDescuento ,1,2);*/

      //$totalPercepciones = bcdiv($sueldoSemanal + $salarioTiempoExtra + $bono - $sueldoDescuento ,1,2);
      //echo "totalBase: ".$totalBase."<br>";
      $totalPercepciones = bcdiv($totalBase + $totalExento,1,2);
      //echo "totalPercepciones: ".$totalPercepciones."<br>";
      
      $sueldoDiario = number_format($totalBase / $diasTrabajados,3, '.', '');          
      $base = bcdiv($sueldoDiario * $factor_mes,1,2);
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
      //echo "subsidio ".$subsidioAplicable;

      $ISRRetenido = number_format($ISRDeterminado, 2, '.', '');
      $ISRPrevioDiario = number_format((($ISRRetenido / $factor_mes) * $diasTrabajados) , 2, '.', '');
      //echo "ISR DIARIO ".$ISRDiario;
      //$ISRDiario = number_format((($ISRRetenido / $factor_mes) * $diasTrabajados) - $subsidioAplicable, 2, '.', '');

      $SAEPagar = 0;
      if($ISRPrevioDiario >= $subsidioAplicable){
        $ISRDiario = bcdiv($ISRPrevioDiario - $subsidioAplicable,1,2);
        $impuesto_aplicable = $ISRDiario;
        $titulo = "ISR a aplicar";
      }
      else{
        $SAEPagar = bcdiv(($ISRPrevioDiario - $subsidioAplicable) * -1,1,2);
        $impuesto_aplicable = $SAEPagar;
        $titulo = "SAE a pagar";
      }

      $respuesta->estatus = "exito";

      $respuesta->resultado = '
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
                          <td>(=)</td>
                          <td>ISR Determinado</td>
                          <td>'.number_format($ISRDeterminado, 2, '.', ',').'</td>
                        </tr>
                        <tr class="text-center">
                          <td>(=)</td>
                          <td>ISR Previo Diario</td>
                          <td>'.number_format($ISRPrevioDiario, 2, '.', ',').'</td>
                        </tr>
                        <tr class="text-center">
                          <td>(-)</td>
                          <td>Subsidio aplicable</td>
                          <td>'.number_format($subsidioAplicable, 2, '.', ',').'</td>
                        </tr>                      
                        <tr class="text-center">
                          <td>(=)</td>
                          <td>'.$titulo.'</td>
                          <td>'.number_format($impuesto_aplicable, 2, '.', ',').'</td>
                        </tr>
                      </tbody>
                  </table>';

}
else{
  // en caso de que el empleado no tenga ningun concepto agregado , se pone en ceros su nomina 
  
  $respuesta->estatus = "exito";
  $respuesta->resultado = '<div class="row" style="display: block;">
                            <center>
                              No se puede calcular el ISR sin ningún concepto.
                            </center>
                           </div>';
}

$respuesta = json_encode($respuesta);
echo $respuesta;