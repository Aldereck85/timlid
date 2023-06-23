<?php
session_start();
$token = $_POST['csr_token_UT5JP'];

if(empty($_SESSION['token_ld10d'])) {
    echo  "fallo";
    return;          
}

if (!hash_equals($_SESSION['token_ld10d'], $token)) {
    echo "fallo";
    return;
}

require_once '../../../include/db-conn.php';
$GLOBALS['rutaFuncion'] = "./../";
require_once("../../../functions/funcionNomina.php");

$calculo_aguinaldo = 0.00; $aguinaldo_excento = 0; $aguinaldo_gravar = 0.00; 


$idEmpleado = $_POST['idEmpleado'];
$diasAguinaldo = $_POST['diasAguinaldo'];
$diasVacaciones = $_POST['diasVacaciones'];
$salariosDevengados = $_POST['salariosDevengados'];
$fechaPeriodoIni = $_POST['fechaPeriodoIni'];
$fechaPeriodoFin = $_POST['fechaPeriodoFin'];
$tipoMovimiento = $_POST['tipoMovimiento'];
$fechaSalida = $_POST['fechaSalida'];
$fechaAnio = DateTime::createFromFormat("Y-m-d", $fechaSalida);
$anioCalculo = $fechaAnio->format("Y");
$antiguedad = $_POST['antiguedad'];
$gratificacion = $_POST['gratificacion'];
$otros = $_POST['otros'];
$bonoAsistencia = $_POST['bonoAsistencia'];
$bonoPuntualidad = $_POST['bonoPuntualidad'];
$pensionAlimenticiaCheck = $_POST['pensionAlimenticiaCheck'];
$pensionAlimenticiaPorc = $_POST['pensionAlimenticiaPorc'];
$infonavit = $_POST['infonavit'];
$fonacot = $_POST['fonacot'];
$diasCheck = $_POST['diasCheck'];
$primaAntiguedadCheck = $_POST['primaAntiguedadCheck'];

if(trim($salariosDevengados) == ""){
  $salariosDevengados = 0.00;
}
if(trim($gratificacion) == ""){
  $gratificacion = 0.00;
}
if(trim($otros) == ""){
  $otros = 0.00;
}
if(trim($bonoAsistencia) == ""){
  $bonoAsistencia = 0.00;
}
if(trim($bonoPuntualidad) == ""){
  $bonoPuntualidad = 0.00;
}
if(trim($infonavit) == ""){
  $infonavit = 0.00;
}
if(trim($fonacot) == ""){
  $fonacot = 0.00;
}

if($salariosDevengados > 0){
  $date1 = date_create_from_format('Y-m-d', $fechaPeriodoIni);
  $date2 = date_create_from_format('Y-m-d',  $fechaPeriodoFin);
  $diff = (array) date_diff($date1, $date2);

  $diferencia = $diff['days'];
  $diasTrabajados = $diferencia + 1;
  $imssSalariosDevengados = calcularIMSS($idEmpleado, $diasTrabajados);
}
else{
  $imssSalariosDevengados = 0.00;
}

$datosSalario = getSalario_Dias($idEmpleado);
$salario_diario = $datosSalario[2];
$salario_mensual = $datosSalario[5];
$dias_periodo = $datosSalario[1];
$UMA = getUMA(0);

$aguinaldo = number_format($diasAguinaldo * $salario_diario,2,'.','');
$aporte_exento = number_format($UMA * 30 ,2,'.','');

if($aporte_exento > $aguinaldo){
    $aguinaldo_exento = $aguinaldo;
    $aguinaldo_gravado = 0.00;
}
else{
    $aguinaldo_exento = $aporte_exento;
    $aguinaldo_gravado = number_format($aguinaldo - $aguinaldo_exento ,2,'.','');
}

//echo "dias ".$diasVacaciones."/".$salario_diario;
$vacaciones_gravadas = number_format($salario_diario * $diasVacaciones ,2,'.','');
$vacaciones_exentas = 0.00;

$exento_prima_vacacional = number_format($UMA * 15 ,2,'.','');
$primaVacacionalTasa = getPrimaVacacional();
$primaVacacional = bcdiv($vacaciones_gravadas * $primaVacacionalTasa,1,2);

if($exento_prima_vacacional > $primaVacacional){
  $primaVacacional_gravado = 0.00;
  $primaVacacional_exento = $primaVacacional;
}
else{
  $primaVacacional_gravado = bcdiv($primaVacacional - $exento_prima_vacacional,1,2);
  $primaVacacional_exento = bcdiv($primaVacacional - $primaVacacional_gravado,1,2);
}

$total = bcdiv($aguinaldo + $vacaciones_gravadas + $primaVacacional + $salariosDevengados + $otros + $gratificacion + $bonoAsistencia + $bonoPuntualidad,1,2);
$total_exento = bcdiv($aguinaldo_exento + $vacaciones_exentas + $primaVacacional_exento,1,2);
$total_gravado = bcdiv($aguinaldo_gravado + $vacaciones_gravadas + $primaVacacional_gravado + $salariosDevengados + $otros + $gratificacion + $bonoAsistencia + $bonoPuntualidad,1,2);

$base_vacaciones_salarios_devengados = bcdiv($vacaciones_gravadas + $salariosDevengados + $bonoPuntualidad + $bonoAsistencia,1,2);

$ISRAguinaldo = calculoISRGeneralFiniquito($aguinaldo_gravado, $salario_mensual, $anioCalculo);
$ISRPrimaVacacional = calculoISRGeneralFiniquito($primaVacacional_gravado, $salario_mensual, $anioCalculo);

$datosISR = calculoISRFiniquito($base_vacaciones_salarios_devengados, $anioCalculo);

if($datosISR[0] == 0.00){
  $titulo = "SAE a pagar (Vacaciones,salarios, otros, gratificación)";
  $impuestoAplicableVS = $datosISR[1];
  $tipoISRVacacionesSalarios = 1;

  if($ISRAguinaldo[0] != 0.00){
    $tipoISRAguinaldo = 0;
    if($ISRPrimaVacacional != 0.00){
      $tipoISRPrimaVacacional = 0;
      $subtotal_pagar = bcdiv($total + $impuestoAplicableVS - $ISRAguinaldo[0] - $ISRPrimaVacacional[0],1,2);
    }
    else{
      $tipoISRPrimaVacacional = 1;
      $subtotal_pagar = bcdiv($total + $impuestoAplicableVS - $ISRAguinaldo[0] + $ISRPrimaVacacional[1],1,2);
    }
  }
  else{
    $tipoISRAguinaldo = 1;
    if($ISRPrimaVacacional != 0.00){
      $tipoISRPrimaVacacional = 0;
      $subtotal_pagar = bcdiv($total + $impuestoAplicableVS + $ISRAguinaldo[1] - $ISRPrimaVacacional[0],1,2);
    }
    else{
      $tipoISRPrimaVacacional = 1;
      $subtotal_pagar = bcdiv($total + $impuestoAplicableVS + $ISRAguinaldo[1] + $ISRPrimaVacacional[1],1,2);
    }
  }

}
else{
  $titulo = "ISR (Vacaciones,salarios, otros, gratificación)";
  $impuestoAplicableVS = $datosISR[0];
  $tipoISRVacacionesSalarios = 0;

  if($ISRAguinaldo[0] != 0.00){
    $tipoISRAguinaldo = 0;
    if($ISRPrimaVacacional != 0.00){
      $tipoISRPrimaVacacional = 0;
      $subtotal_pagar = bcdiv($total - $impuestoAplicableVS - $ISRAguinaldo[0] - $ISRPrimaVacacional[0],1,2);
    }
    else{
      $tipoISRPrimaVacacional = 1;
      $subtotal_pagar = bcdiv($total - $impuestoAplicableVS - $ISRAguinaldo[0] + $ISRPrimaVacacional[1],1,2);
    }
  }
  else{
    $tipoISRAguinaldo = 1;
    if($ISRPrimaVacacional != 0.00){
      $tipoISRPrimaVacacional = 0;
      $subtotal_pagar = bcdiv($total - $impuestoAplicableVS + $ISRAguinaldo[1] - $ISRPrimaVacacional[0],1,2);
    }
    else{
      $tipoISRPrimaVacacional = 1;
      $subtotal_pagar = bcdiv($total - $impuestoAplicableVS + $ISRAguinaldo[1] + $ISRPrimaVacacional[1],1,2);
    }
  }
  
}

$suma_deducciones = $datosISR[0] + $ISRAguinaldo[0] + $ISRPrimaVacacional[0] + $infonavit + $fonacot - $datosISR[1] - $ISRAguinaldo[1] - $ISRPrimaVacacional[1] - $imssSalariosDevengados;
$total_pagar = $subtotal_pagar;

if($tipoMovimiento == 1){

    echo
    '<table class="table">
      <thead>
        <th>Concepto</th>
        <th>Importe</th>
        <th>Exentos</th>
        <th>Gravado</th>
      </thead>
      <tbody>
        <tr>
          <td colspan="4"><b>PERCEPCIONES</b></td>
        </tr>
        <tr>
          <td>Aguinaldo</td>
          <td id="lblAguinaldoPercepcion">'.number_format($aguinaldo,2,'.',',').'</td>
          <td id="lblAguinaldoExento">'.number_format($aguinaldo_exento,2,'.',',').'</td>
          <td id="lblAguinaldoGravado">'.number_format($aguinaldo_gravado,2,'.',',').'</td>
        </tr>
        <tr>
          <td>Vacaciones</td>
          <td id="lblVacacionesPercepcion">'.number_format($vacaciones_gravadas,2,'.',',').'</td>
          <td id="lblVacacionesExento">'.number_format($vacaciones_exentas,2,'.',',').'</td>
          <td id="lblVacacionesGravado">'.number_format($vacaciones_gravadas,2,'.',',').'</td>
        </tr>
        <tr>
          <td>Prima vacacional</td>
          <td id="lblPrimaVacacionalPercepcion">'.number_format($primaVacacional,2,'.',',').'</td>
          <td id="lblPrimaVacacionalExento">'.number_format($primaVacacional_exento,2,'.',',').'</td>
          <td id="lblPrimaVacacionalGravado">'.number_format($primaVacacional_gravado,2,'.',',').'</td>
        </tr>
        <tr>
          <td>Salario devengado</td>
          <td id="lblSalarioDevengadoPercepcion">'.number_format($salariosDevengados,2,'.',',').'</td>
          <td id="lblSalarioDevengadoExento">0.00</td>
          <td id="lblSalarioDevengadoGravado">'.number_format($salariosDevengados,2,'.',',').'</td>
        </tr>
        <tr>
          <td>Otros</td>
          <td id="lblOtrosPercepcion">'.number_format($otros,2,'.',',').'</td>
          <td id="lblOtrosExento">0.00</td>
          <td id="lblOtrosGravado">'.number_format($otros,2,'.',',').'</td>
        </tr>
        <tr>
          <td>Gratificación</td>
          <td id="lblGratificacionPercepcion">'.number_format($gratificacion,2,'.',',').'</td>
          <td id="lblGratificacionExento">0.00</td>
          <td id="lblGratificacionGravado">'.number_format($gratificacion,2,'.',',').'</td>
        </tr>
        <tr>
          <td>Bono por asistencia</td>
          <td id="lblBonoAsistenciaPercepcion">'.number_format($bonoAsistencia,2,'.',',').'</td>
          <td id="lblBonoAsistenciaExento">0.00</td>
          <td id="lblBonoAsistenciaGravado">'.number_format($bonoAsistencia,2,'.',',').'</td>
        </tr>
        <tr>
          <td>Bono por puntualidad</td>
          <td id="lblBonoPuntualidadPercepcion">'.number_format($bonoPuntualidad,2,'.',',').'</td>
          <td id="lblBonoPuntualidadExento">0.00</td>
          <td id="lblBonoPuntualidadGravado">'.number_format($bonoPuntualidad,2,'.',',').'</td>
        </tr>
         <tr>
          <td><b>SUMA PERCEPCIONES</b></td>
          <td id="lblSubtotalFiniquitoPercepcion">'.number_format($total,2,'.',',').'</td>
          <td id="lblSubtotalFiniquitoExento">'.number_format($total_exento,2,'.',',').'</td>
          <td id="lblSubtotalFiniquitoGravado">'.number_format($total_gravado,2,'.',',').'</td>
        </tr>
        <tr>
          <td colspan="4"><b>DEDUCCIONES</b></td>
        </tr>
        <td>'.$titulo.' <b><button type="button" id="mostrarCalculoImpuestos" class="circuloImpuestos" title="Mostrar el calculo del ISR/SAE de Salarios/Vacaciones" value="1">?</button></b></td>
          <td>&nbsp</td>
          <td>&nbsp</td>
          <td id="lblISRVacacacionesSalarios">'.number_format($impuestoAplicableVS,2,'.',',').'</td>
          <input type="hidden"  id="tipoISRVacacionesSalarios" value="'.$tipoISRVacacionesSalarios.'" />
        </tr>
        <td>ISR Aguinaldo <b><button type="button" id="mostrarCalculoImpuestos" class="circuloImpuestos" title="Mostrar el calculo del ISR/SAE de Aguinaldo" value="2">?</button></b></td>
          <td>&nbsp</td>
          <td>&nbsp</td>
          <td id="lblISRAguinaldo">'.number_format($ISRAguinaldo[0],2,'.',',').'</td>
          <input type="hidden"  id="tipoISRAguinaldo" value="'.$tipoISRAguinaldo.'" />
        </tr>
        <td>ISR Prima Vacacional <b><button type="button" id="mostrarCalculoImpuestos" class="circuloImpuestos" title="Mostrar el calculo del ISR/SAE de Prima Vacacional" value="3">?</button></b></td>
          <td>&nbsp</td>
          <td>&nbsp</td>
          <td id="lblISRPrimaVacacional">'.number_format($ISRPrimaVacacional[0],2,'.',',').'</td>
          <input type="hidden"  id="tipoISRPrimaVacacional" value="'.$tipoISRPrimaVacacional.'" />
        </tr>';

        if($infonavit > 0){

          echo '
                <tr>
                  <td>INFONAVIT</td>
                  <td>&nbsp</td>
                  <td>&nbsp</td>
                  <td id="lblInfonavit">'.number_format($infonavit,2,'.',',').'</td>
                </tr>';
        }
        else{
          echo '<span id="lblInfonavit" style="display:none;" >0.00</span>';
        }

        if($fonacot > 0){

          echo '
                <tr>
                  <td>FONACOT</td>
                  <td>&nbsp</td>
                  <td>&nbsp</td>
                  <td id="lblFonacot">'.number_format($fonacot,2,'.',',').'</td>
                </tr>';
        }
        else{
          echo '<span id="lblFonacot" style="display:none;" >0.00</span>';
        }

        $pension_alimenticia = 0;
        if($pensionAlimenticiaCheck == 2){
          $titulo_pension = "Pensión alimenticia (Salario)";
          $pension_alimenticia = bcdiv($salariosDevengados * ($pensionAlimenticiaPorc/100),1,2);
          $total_pagar = bcdiv($total_pagar - $pension_alimenticia,1,2);
        }

        if($pensionAlimenticiaCheck == 3){
          $titulo_pension = "Pensión alimenticia (Total percepciones)";
          $pension_alimenticia = bcdiv($total * ($pensionAlimenticiaPorc/100),1,2);
          $total_pagar = bcdiv($total_pagar - $pension_alimenticia,1,2);
        }

        if($pensionAlimenticiaCheck == 4){
          $titulo_pension = "Pensión alimenticia (Total percepciones menos deducciones)";
          $pension_alimenticia = bcdiv(($total_pagar - $infonavit - $fonacot) * ($pensionAlimenticiaPorc/100),1,2);
          $total_pagar = bcdiv($total_pagar - $pension_alimenticia,1,2);
        }
//echo "pension ".$pension_alimenticia;
        if($pensionAlimenticiaCheck != 1){

          echo '
            <tr>
              <td>'.$titulo_pension.'</td>
              <td>&nbsp</td>
              <td>&nbsp</td>
              <td id="lblPension">'.number_format($pension_alimenticia,2,'.',',').'</td>
            </tr>
          ';

        }
        else{
          echo '<span id="lblPension" style="display:none;" >0.00</span>';
        }

        $suma_deducciones = $suma_deducciones + $pension_alimenticia;

        if($imssSalariosDevengados > 0){

          echo '
                <tr>
                  <td>IMSS (Salarios devengados) <b><button type="button" id="mostrarCalculoImpuestos" class="circuloImpuestos" title="Mostrar el calculo del IMSS de salarios devengados" value="4">?</button></b></td>
                  <td>&nbsp</td>
                  <td>&nbsp</td>
                  <td id="lblIMSSSalarios">'.number_format($imssSalariosDevengados,2,'.',',').'</td>
                </tr>';
        }
        else{
          echo '<span id="lblIMSSSalarios" style="display:none;" >0.00</span>';
        }
        
        echo '
        <tr>
          <td><b>SUMA DEDUCCIONES</b></td>
          <td>&nbsp</td>
          <td>&nbsp</td>
          <td id="lblSumaDeducciones" style="font-weight:800">'.number_format($suma_deducciones,2,'.',',').'</td>
        </tr>
      ';


    $total_pagar = $total_pagar - $infonavit - $fonacot - $imssSalariosDevengados;
    echo '
      <tr>
        <td><b>TOTAL A PAGAR</b></td>
        <td>&nbsp</td>
        <td>&nbsp</td>
        <td id="lblTotalPagar" style="font-weight:800">'.number_format($total_pagar,2,'.',',').'</td>
      </tr>
    </tbody>
  </table>
  <br><br>
  <div class="col-12 text-center">
    <button type="button" class="btn btn-custom btn-custom--border-blue" id="guardarFiniquito">Guardar finiquito</button>
      <div class="row" id="mostrarTimbrado" style="display:none;">
        <div class="col-12 text-center">
              <div class="row">
                <div class="col-lg-6">
                  <button type="button" class="btn btn-custom btn-custom--border-blue" id="exportarExcel">Exportar Excel</button>
                </div>
                <div class="col-lg-6">
                  <button type="button" class="btn btn-custom btn-custom--border-blue" id="exportarPDF">Exportar PDF</button>
                </div>
              </div>
              <br><br>
              <div class="col-lg-12">
                  <label for="timbrarLiquidacionL"><b>Opciones de timbrado</b></label>
              </div>
              <br>
              <div class="row">
                    <div class="col-lg-6" style="position:relative; bottom: 20px;">
                      <label>Método de pago:</label>
                        <select class="form-control" id="metodo_pago_id">
                          <option disabled selected>Selecciona un método de pago</option>';

                            $stmt = $conn->prepare('SELECT id, clave as codigo, descripcion FROM formas_pago_sat');
                            $stmt->execute();
                            $metodos_de_pago = $stmt->fetchAll();
                            foreach ($metodos_de_pago as $mp) {
                          
                             echo '<option value="'.$mp['id'].'">'.$mp["codigo"]." - ".$mp["descripcion"].'</option>';

                            }
                          
                      echo '
                        </select>
                    </div>
                    <div class="col-lg-6" style="position:relative; bottom: 20px;">
                      <div class="row">
                          <div class="col-lg-8">
                            <label>Cfdi relacionados:</label>
                              <select class="form-control" id="facturas_relacionadas_id">
                                <option value="0" disabled selected>Selecciona un cfdi</option>';

                                  $stmt = $conn->prepare('SELECT bcef.id, bcef.idFactura, bcef.uuid, IF(bcef.tipo = 1,"Finiquito","Liquidación") as tipo_factura, bcef.tipo FROM bitacora_cfdi_eliminado_finiquito_liquidacion as bcef INNER JOIN finiquito as f ON f.id = bcef.finiquito_id AND f.empleado_id = :empleado_id WHERE bcef.tipo = 1 ORDER BY tipo_factura');
                                  $stmt->bindValue(":empleado_id",$idEmpleado);
                                  $stmt->execute();
                                  $bitacora = $stmt->fetchAll();
                                  foreach ($bitacora as $b) {
                                
                                   echo '<option value="'.$b['uuid'].'&'.$b["tipo"].'&'.$b["id"].'&'.$b["idFactura"].'">'.$b["uuid"]." - ".$b["tipo_factura"].'</option>';

                                  }
                                
                            echo '
                              </select>
                          </div>
                          <div class="col-lg-4">
                            <br>
                            <button type="button" class="btn btn-custom btn-custom--border-blue" id="agregarRelacion">+</button>
                            <a href="#" class="btn btn-custom btn-custom--border-blue" id="descargarPDF" target="_self" > <i class="fas fa-file-invoice"></i> PDF </a>
                            <a href="#" class="btn btn-custom btn-custom--border-blue" id="descargarXML" target="_self"> <i class="far fa-file-alt"></i> XML </a>
                          </div>
                      </div>
                    </div>
                    <div class="col-lg-12">
                      <label><b>CFDI agregados para relacionar:</b></label><br>
                      <span id="cfdiRelacionado">No hay ningún CFDI relacionado.</span>
                    </div>   
                    <br><br><br>
                    <div class="col-lg-12">
                      <button type="button" class="btn btn-custom btn-custom--border-blue" id="timbrarFiniquito">Timbrar finiquito</button>
                    </div> 
            </div>                                       
            <br>
            <div class="col-12 text-center">
                <div class="row" id="cancelarTimbradoMostrar">
                  <div class="col-lg-6">
                    <button type="button" class="btn btn-custom btn-custom--border-blue" id="regresarFiniquito">Regresar</button>
                  </div>
                  <div class="col-12 col-lg-6">
                       <button type="button" class="btn btn-custom btn-custom--red" id="eliminarFiniquito">Eliminar finiquito</button>
                  </div>
                </div>
            </div>
      </div>
  </div>
  </div>';

}

if($tipoMovimiento == 2){

$anios_completos = round($antiguedad);

$datosLiquidacion = calculoInicialLiquidacion($idEmpleado, $anios_completos, $diasCheck, $primaAntiguedadCheck);

$ISRUltimoSueldo = calculoISR($idEmpleado, $anioCalculo);

$totalLiquidacion = $datosLiquidacion[0] + $datosLiquidacion[3] + $datosLiquidacion[4];
$totalLiquidacionExento = $datosLiquidacion[2];
$totalLiquidacionGravada = $datosLiquidacion[1] + $datosLiquidacion[3] + $datosLiquidacion[4];

if($ISRUltimoSueldo[2] == 1){
//isr es mayor que cero  
  $tasa_isr_indemnizacion = bcdiv($ISRUltimoSueldo[0] / $salario_mensual,1,5);
  $titulo_indemnizacion = "ISR Indemnización";
  $ISRIndemnizacion = bcdiv($datosLiquidacion[1] * $tasa_isr_indemnizacion,1,2);
}
else{
//sae a pagar es mayor que cero
  $tasa_isr_indemnizacion = bcdiv($ISRUltimoSueldo[1] / $salario_mensual,1,5);
  $titulo_indemnizacion = "SAE Indemnización";
  $ISRIndemnizacion = bcdiv($datosLiquidacion[1] * $tasa_isr_indemnizacion,1,2);
}
    
    echo
    '<center><h3><b>FINIQUITO</b></h3></center><br>
    <table class="table">
      <thead>
        <th>Concepto</th>
        <th>Importe</th>
        <th>Exentos</th>
        <th>Gravado</th>
      </thead>
      <tbody>
        <tr>
          <td colspan="4"><b>PERCEPCIONES</b></td>
        </tr>
        <tr>
          <td>Aguinaldo</td>
          <td id="lblAguinaldoPercepcion">'.number_format($aguinaldo,2,'.',',').'</td>
          <td id="lblAguinaldoExento">'.number_format($aguinaldo_exento,2,'.',',').'</td>
          <td id="lblAguinaldoGravado">'.number_format($aguinaldo_gravado,2,'.',',').'</td>
        </tr>
        <tr>
          <td>Vacaciones</td>
          <td id="lblVacacionesPercepcion">'.number_format($vacaciones_gravadas,2,'.',',').'</td>
          <td id="lblVacacionesExento">'.number_format($vacaciones_exentas,2,'.',',').'</td>
          <td id="lblVacacionesGravado">'.number_format($vacaciones_gravadas,2,'.',',').'</td>
        </tr>
        <tr>
          <td>Prima vacacional</td>
          <td id="lblPrimaVacacionalPercepcion">'.number_format($primaVacacional,2,'.',',').'</td>
          <td id="lblPrimaVacacionalExento">'.number_format($primaVacacional_exento,2,'.',',').'</td>
          <td id="lblPrimaVacacionalGravado">'.number_format($primaVacacional_gravado,2,'.',',').'</td>
        </tr>
        <tr>
          <td>Salario devengado</td>
          <td id="lblSalarioDevengadoPercepcion">'.number_format($salariosDevengados,2,'.',',').'</td>
          <td id="lblSalarioDevengadoExento">0.00</td>
          <td id="lblSalarioDevengadoGravado">'.number_format($salariosDevengados,2,'.',',').'</td>
        </tr>
        <tr>
          <td>Otros</td>
          <td id="lblOtrosPercepcion">'.number_format($otros,2,'.',',').'</td>
          <td id="lblOtrosExento">0.00</td>
          <td id="lblOtrosGravado">'.number_format($otros,2,'.',',').'</td>
        </tr>
        <tr>
          <td>Gratificación</td>
          <td id="lblGratificacionPercepcion">'.number_format($gratificacion,2,'.',',').'</td>
          <td id="lblGratificacionExento">0.00</td>
          <td id="lblGratificacionGravado">'.number_format($gratificacion,2,'.',',').'</td>
        </tr>
        <tr>
          <td>Bono por asistencia</td>
          <td id="lblBonoAsistenciaPercepcion">'.number_format($bonoAsistencia,2,'.',',').'</td>
          <td id="lblBonoAsistenciaExento">0.00</td>
          <td id="lblBonoAsistenciaGravado">'.number_format($bonoAsistencia,2,'.',',').'</td>
        </tr>
        <tr>
          <td>Bono por puntualidad</td>
          <td id="lblBonoPuntualidadPercepcion">'.number_format($bonoPuntualidad,2,'.',',').'</td>
          <td id="lblBonoPuntualidadExento">0.00</td>
          <td id="lblBonoPuntualidadGravado">'.number_format($bonoPuntualidad,2,'.',',').'</td>
        </tr>
         <tr>
          <td><b>SUMA PERCEPCIONES</b></td>
          <td id="lblSubtotalFiniquitoPercepcion">'.number_format($total,2,'.',',').'</td>
          <td id="lblSubtotalFiniquitoExento">'.number_format($total_exento,2,'.',',').'</td>
          <td id="lblSubtotalFiniquitoGravado">'.number_format($total_gravado,2,'.',',').'</td>
        </tr>
        <tr>
          <td colspan="4"><b>DEDUCCIONES</b></td>
        </tr>
        <td>'.$titulo.' <b><button type="button" id="mostrarCalculoImpuestos" class="circuloImpuestos" title="Mostrar el calculo del ISR/SAE de Salarios/Vacaciones" value="1">?</button></b></td>
          <td>&nbsp</td>
          <td>&nbsp</td>
          <td id="lblISRVacacacionesSalarios">'.number_format($impuestoAplicableVS,2,'.',',').'</td>
          <input type="hidden"  id="tipoISRVacacionesSalarios" value="'.$tipoISRVacacionesSalarios.'" />
        </tr>
        <td>ISR Aguinaldo <b><button type="button" id="mostrarCalculoImpuestos" class="circuloImpuestos" title="Mostrar el calculo del ISR/SAE de Aguinaldo" value="2">?</button></b></td>
          <td>&nbsp</td>
          <td>&nbsp</td>
          <td id="lblISRAguinaldo">'.number_format($ISRAguinaldo[0],2,'.',',').'</td>
          <input type="hidden"  id="tipoISRAguinaldo" value="'.$tipoISRAguinaldo.'" />
        </tr>
        <td>ISR Prima Vacacional <b><button type="button" id="mostrarCalculoImpuestos" class="circuloImpuestos" title="Mostrar el calculo del ISR/SAE de Prima Vacacional" value="3">?</button></b></td>
          <td>&nbsp</td>
          <td>&nbsp</td>
          <td id="lblISRPrimaVacacional">'.number_format($ISRPrimaVacacional[0],2,'.',',').'</td>
          <input type="hidden"  id="tipoISRPrimaVacacional" value="'.$tipoISRPrimaVacacional.'" />
        </tr>';

        if($infonavit > 0){

          echo '
                <tr>
                  <td>INFONAVIT</td>
                  <td>&nbsp</td>
                  <td>&nbsp</td>
                  <td id="lblInfonavit">'.number_format($infonavit,2,'.',',').'</td>
                </tr>';
        }
        else{
          echo '<span id="lblInfonavit" style="display:none;" >0.00</span>';
        }

        if($fonacot > 0){

          echo '
                <tr>
                  <td>FONACOT</td>
                  <td>&nbsp</td>
                  <td>&nbsp</td>
                  <td id="lblFonacot">'.number_format($fonacot,2,'.',',').'</td>
                </tr>';
        }
        else{
          echo '<span id="lblFonacot" style="display:none;" >0.00</span>';
        }

        $pension_alimenticia = 0;
        if($pensionAlimenticiaCheck == 2){
          $titulo_pension = "Pensión alimenticia (Salario)";
          $pension_alimenticia = bcdiv($salariosDevengados * ($pensionAlimenticiaPorc/100),1,2);
          $total_pagar = bcdiv($total_pagar - $pension_alimenticia,1,2);
        }

        if($pensionAlimenticiaCheck == 3){
          $titulo_pension = "Pensión alimenticia (Total percepciones)";
          $pension_alimenticia = bcdiv($total * ($pensionAlimenticiaPorc/100),1,2);
          $total_pagar = bcdiv($total_pagar - $pension_alimenticia,1,2);
        }

        if($pensionAlimenticiaCheck == 4){
          $titulo_pension = "Pensión alimenticia (Total percepciones menos deducciones)";
          $pension_alimenticia = bcdiv(($total_pagar - $infonavit - $fonacot) * ($pensionAlimenticiaPorc/100),1,2);
          $total_pagar = bcdiv($total_pagar - $pension_alimenticia,1,2);
        }

        if($pensionAlimenticiaCheck != 1){

          echo '
            <tr>
              <td>'.$titulo_pension.'</td>
              <td>&nbsp</td>
              <td>&nbsp</td>
              <td id="lblPension">'.number_format($pension_alimenticia,2,'.',',').'</td>
            </tr>
          ';

        }
        else{
          echo '<span id="lblPension" style="display:none;" >0.00</span>';
        }

        $suma_deducciones = $suma_deducciones + $pension_alimenticia;

        if($imssSalariosDevengados > 0){

          echo '
                <tr>
                  <td>IMSS (Salarios devengados) <b><button type="button" id="mostrarCalculoImpuestos" class="circuloImpuestos" title="Mostrar el calculo del IMSS de salarios devengados" value="4">?</button></b></td>
                  <td>&nbsp</td>
                  <td>&nbsp</td>
                  <td id="lblIMSSSalarios">'.number_format($imssSalariosDevengados,2,'.',',').'</td>
                </tr>';
        }
        else{
          echo '<span id="lblIMSSSalarios" style="display:none;" >0.00</span>';
        }
        
        echo '
        <tr>
          <td><b>SUMA DEDUCCIONES</b></td>
          <td>&nbsp</td>
          <td>&nbsp</td>
          <td id="lblSumaDeducciones" style="font-weight:800">'.number_format($suma_deducciones,2,'.',',').'</td>
        </tr>
      ';


    $total_pagar = $total_pagar - $infonavit - $fonacot;
    echo '
      <tr>
        <td><b>TOTAL FINIQUITO</b></td>
        <td>&nbsp</td>
        <td>&nbsp</td>
        <td id="lblTotalPagar" style="font-weight:800">'.number_format($total_pagar,2,'.',',').'</td>
      </tr>
    </tbody>
  </table><br><br><br>';

    $total_liquidacion_final = $totalLiquidacion - $ISRIndemnizacion;
    echo
    '<center><h3><b>LIQUIDACION</b></h3></center><br>
    <table class="table">
      <thead>
        <th>Concepto</th>
        <th>Importe</th>
        <th>Exentos</th>
        <th>Gravado</th>
      </thead>
      <tbody>
        <tr>
          <td colspan="4"><b>PERCEPCIONES</b></td>
        </tr>
        <tr>
          <td>Indemnización (90 días)</td>
          <td id="lblIndeminizacionPercepcion">'.number_format($datosLiquidacion[0],2,'.',',').'</td>
          <td id="lblIndeminizacionExento">'.number_format($datosLiquidacion[2],2,'.',',').'</td>
          <td id="lblIndeminizacionGravado">'.number_format($datosLiquidacion[1],2,'.',',').'</td>
        </tr>
        <tr>
          <td>20 días por año de servicio</td>
          <td id="lblSalarioAnioPercepcion">'.number_format($datosLiquidacion[3],2,'.',',').'</td>
          <td id="lblSalarioAnioExento">0.00</td>
          <td id="lblSalarioAnioGravado">'.number_format($datosLiquidacion[3],2,'.',',').'</td>
        </tr>
        <tr>
          <td>Prima antiguedad</td>
          <td id="lblPrimaAntiguedadPercepcion">'.number_format($datosLiquidacion[4],2,'.',',').'</td>
          <td id="lblPrimaAntiguedadExento">0.00</td>
          <td id="lblPrimaAntiguedadGravado">'.number_format($datosLiquidacion[4],2,'.',',').'</td>
        </tr>
        <tr>
          <td><b>SUMA LIQUIDACION</b></td>
          <td id="lblSubtotalLiquidacionPercepcion">'.number_format($totalLiquidacion,2,'.',',').'</td>
          <td id="lblSubtotalLiquidacionExento">'.number_format($totalLiquidacionExento,2,'.',',').'</td>
          <td id="lblSubtotalLiquidacionGravado">'.number_format($totalLiquidacionGravada,2,'.',',').'</td>
        </tr>
        <tr>
          <td colspan="4"><b>DEDUCCIONES</b></td>
        </tr>
        <td>'.$titulo_indemnizacion.' <b><button type="button" id="mostrarCalculoImpuestos" class="circuloImpuestos" title="Mostrar el calculo del ISR/SAE de Indemnización" value="5">?</button></b></td>
          <td>&nbsp</td>
          <td>&nbsp</td>
          <td id="lblISRIndemnizacion">'.number_format($ISRIndemnizacion,2,'.',',').'</td>
          <input type="hidden"  id="tipoISRIndemnizacion" value="'.$ISRUltimoSueldo[2].'" />
        </tr>
        <tr>
          <td><b>SUMA DEDUCCIONES</b></td>
          <td>&nbsp</td>
          <td>&nbsp</td>
          <td id="lblSumaDeducciones" style="font-weight:800">'.number_format($ISRIndemnizacion,2,'.',',').'</td>
        </tr>
        <tr>
          <td><b>TOTAL LIQUIDACION</b></td>
          <td>&nbsp</td>
          <td>&nbsp</td>
          <td id="lblTotalPagarLiquidacion" style="font-weight:800">'.number_format($total_liquidacion_final,2,'.',',').'</td>
        </tr>
      </tbody>
    </table>';

  $total_final = $total_liquidacion_final + $total_pagar;

    echo '
    <br><h2><span style="float: right;position:relative; right: 5%;">TOTAL A PAGAR: '.number_format($total_final,2,'.',',').'</span></h2><br><br>
  <div class="col-12 text-center">
    <button type="button" class="btn btn-custom btn-custom--border-blue" id="guardarFiniquito">Guardar liquidación</button>
      <div class="row" id="mostrarTimbrado" style="display:none;">
        <div class="col-12 text-center">
              <div class="row">
                <div class="col-lg-6">
                  <button type="button" class="btn btn-custom btn-custom--border-blue" id="exportarExcel">Exportar Excel</button>
                </div>
                <div class="col-lg-6">
                  <button type="button" class="btn btn-custom btn-custom--border-blue" id="exportarPDF">Exportar PDF</button>
                </div>
              </div>
              <br><br>
              <div class="col-lg-12">
                  <label for="timbrarLiquidacionL"><b>Opciones de timbrado</b></label>
              </div>
              <br>
              <div class="row">
                    <div class="col-lg-6" style="position:relative; bottom: 20px;">
                      <label>Método de pago:</label>
                        <select class="form-control" id="metodo_pago_id">
                          <option disabled selected>Selecciona un método de pago</option>';

                            $stmt = $conn->prepare('SELECT id, clave as codigo, descripcion FROM formas_pago_sat');
                            $stmt->execute();
                            $metodos_de_pago = $stmt->fetchAll();
                            foreach ($metodos_de_pago as $mp) {
                          
                             echo '<option value="'.$mp['id'].'">'.$mp["codigo"]." - ".$mp["descripcion"].'</option>';

                            }
                          
                      echo '
                        </select>
                    </div>
                    <div class="col-lg-6" style="position:relative; bottom: 20px;">
                      <div class="row">
                          <div class="col-lg-8">
                            <label>Cfdi relacionados:</label>
                              <select class="form-control" id="facturas_relacionadas_id">
                                <option value="0" disabled selected>Selecciona un cfdi</option>';

                                  $stmt = $conn->prepare('SELECT bcef.id, bcef.idFactura, bcef.uuid, IF(bcef.tipo = 1,"Finiquito","Liquidación") as tipo_factura, bcef.tipo FROM bitacora_cfdi_eliminado_finiquito_liquidacion as bcef INNER JOIN finiquito as f ON f.id = bcef.finiquito_id AND f.empleado_id = :empleado_id ORDER BY tipo_factura');
                                  $stmt->bindValue(":empleado_id",$idEmpleado);
                                  $stmt->execute();
                                  $bitacora = $stmt->fetchAll();
                                  foreach ($bitacora as $b) {
                                
                                   echo '<option value="'.$b['uuid'].'&'.$b["tipo"].'&'.$b["id"].'&'.$b["idFactura"].'">'.$b["uuid"]." - ".$b["tipo_factura"].'</option>';

                                  }
                                
                            echo '
                              </select>
                          </div>
                          <div class="col-lg-4">
                            <br>
                            <button type="button" class="btn btn-custom btn-custom--border-blue" id="agregarRelacion">+</button>
                            <a href="#" class="btn btn-custom btn-custom--border-blue" id="descargarPDF" target="_self" > <i class="fas fa-file-invoice"></i> PDF </a>
                            <a href="#" class="btn btn-custom btn-custom--border-blue" id="descargarXML" target="_self"> <i class="far fa-file-alt"></i> XML </a>
                          </div>
                      </div>
                    </div>
                    <div class="col-lg-12">
                      <label><b>CFDI agregados para relacionar:</b></label><br>
                      <span id="cfdiRelacionado">No hay ningún CFDI relacionado.</span>
                    </div>   
                    <br><br><br>
                    <div class="col-lg-12">
                      <button type="button" class="btn btn-custom btn-custom--border-blue" id="timbrarLiquidacion">Timbrar liquidación</button>
                    </div> 
            </div>                                       
            <br>
            <div class="col-12 text-center">
                <div class="row" id="cancelarTimbradoMostrar">
                  <div class="col-lg-6">
                    <button type="button" class="btn btn-custom btn-custom--border-blue" id="regresarFiniquito">Regresar</button>
                  </div>
                  <div class="col-12 col-lg-6">
                       <button type="button" class="btn btn-custom btn-custom--red" id="eliminarLiquidacion_unico">Eliminar liquidación</button>
                  </div>
                </div>
            </div>
      </div>
  </div>
  </div>';
}


