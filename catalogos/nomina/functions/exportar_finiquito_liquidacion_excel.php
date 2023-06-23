<?php
require '../../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

session_start();

$jwt_ruta = "../../../";
require_once '../../jwt.php';

$token = $_POST['csr_token_UT5JP'];

if(empty($_SESSION['token_ld10d'])) {
    header('Location: ../finiquito_liquidacion.php');
    return;           
}

if (!hash_equals($_SESSION['token_ld10d'], $token)) {
    header('Location: ../finiquito_liquidacion.php');
    return;
} 

require_once('../../../include/db-conn.php');

date_default_timezone_set('America/Mexico_City');

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$idFiniquito = $_POST['idFiniquito'];
$stmt = $conn->prepare("SELECT f.*, l.*, e.Nombres, e.PrimerApellido, e.SegundoApellido,dle.FechaIngreso, e.RFC, dme.NSS, p.puesto,t.Turno, dle.Sueldo,pp.DiasPago FROM finiquito as f INNER JOIN empleados as e ON e.PKEmpleado = f.empleado_id INNER JOIN datos_laborales_empleado as dle ON dle.FKEmpleado = e.PKEmpleado INNER JOIN periodo_pago as pp ON pp.PKPeriodo_pago = dle.FKPeriodo LEFT JOIN datos_medicos_empleado as dme ON dme.FKEmpleado = e.PKEmpleado LEFT JOIN puestos as p ON p.id = dle.FKPuesto LEFT JOIN turnos as t ON t.PKTurno = dle.FKTurno LEFT JOIN liquidacion as l ON l.finiquito_id = f.id WHERE f.id = :idFiniquito ");
$stmt->bindValue(':idFiniquito',$idFiniquito);
$stmt->execute();
$datosFiniquito = $stmt->fetch();
$nombreEmpleado = $datosFiniquito['Nombres'].' '.$datosFiniquito['PrimerApellido'].' '.$datosFiniquito['SegundoApellido'];
$idEmpleado = $datosFiniquito['empleado_id'];
//print_r($datosFiniquito);

$GLOBALS['rutaFuncion'] = "./../";
require_once("../../../functions/funcionNomina.php");
$datosSalario = getSalario_Dias($idEmpleado);
$sueldo = $datosSalario[0];
$sueldoDiario = $datosSalario[2];
$datosSBC = getSBCNomina($idEmpleado, $datosFiniquito['fecha_salida']);
$SDI = $datosSBC[3];

if($datosFiniquito['finiquito_id'] == NULL){
  $tipoMovimiento = 1;
}
else{
  $tipoMovimiento = 2;
}

$aguinaldo = $datosFiniquito['aguinaldo'];
$aguinaldo_exento = $datosFiniquito['aguinaldo_exento'];
$aguinaldo_gravado = $datosFiniquito['aguinaldo_gravado'];

$vacaciones_gravadas = $datosFiniquito['vacaciones'];
$vacaciones_exentas = 0.00;
$vacaciones_gravadas = $datosFiniquito['vacaciones'];

$primaVacacional = $datosFiniquito['prima_vacacional'];
$primaVacacional_exento = $datosFiniquito['prima_vacacional_exenta'];
$primaVacacional_gravado = $datosFiniquito['prima_vacacional_gravada'];

$salariosDevengados = $datosFiniquito['salarios_devengados'];
$otros = $datosFiniquito['otros'];
$gratificacion = $datosFiniquito['gratificacion'];
$bonoAsistencia = $datosFiniquito['bonos_asistencia'];
$bonoPuntualidad = $datosFiniquito['bonos_puntualidad'];

$total = $datosFiniquito['aguinaldo'] + $datosFiniquito['vacaciones'] + $datosFiniquito['prima_vacacional'] + $datosFiniquito['salarios_devengados'] + $datosFiniquito['otros'] + $datosFiniquito['gratificacion'] + $datosFiniquito['bonos_asistencia'] + $datosFiniquito['bonos_puntualidad'];
$total_exento = $datosFiniquito['aguinaldo_exento'] + $datosFiniquito['prima_vacacional_exenta'];
$total_gravado = $datosFiniquito['aguinaldo_gravado'] + $datosFiniquito['vacaciones'] + $datosFiniquito['prima_vacacional_gravada'] + $datosFiniquito['salarios_devengados'] + $datosFiniquito['otros'] + $datosFiniquito['gratificacion'] + $datosFiniquito['bonos_asistencia'] + $datosFiniquito['bonos_puntualidad'];

if($datosFiniquito['isr_vacaciones_salarios'] > 0){
  $titulo1 = "ISR (Vacaciones,salarios, otros, gratificación)";
  $impuestoAplicableVS = $datosFiniquito['isr_vacaciones_salarios'];
}
else{
  $titulo1 = "SAE a pagar (Vacaciones,salarios, otros, gratificación)";
  $impuestoAplicableVS = $datosFiniquito['sae_vacaciones_salarios'];
}

if($datosFiniquito['isr_aguinaldo'] > 0){
  $titulo2 = "ISR Aguinaldo";
  $impuestoAguinaldo = $datosFiniquito['isr_aguinaldo'];
}
else{
  $titulo2 = "SAE Aguinaldo";
  $impuestoAguinaldo = $datosFiniquito['sae_aguinaldo'];
}

if($datosFiniquito['isr_aguinaldo'] > 0){
  $titulo3 = "ISR Prima Vacacional";
  $impuestoPrimaVacacional = $datosFiniquito['isr_prima_vacacional'];
}
else{
  $titulo3 = "SAE Prima Vacacional";
  $impuestoPrimaVacacional = $datosFiniquito['sae_prima_vacacional'];
}

if($tipoMovimiento == 2){
 $indemnizacion = $datosFiniquito['indemnizacion'];
 $indemnizacion_exento = $datosFiniquito['indemnizacion_exento'];
 $indemnizacion_gravado = $datosFiniquito['indemnizacion_gravado'];

 $anios_servicio = $datosFiniquito['anios_servicio'];
 $prima_antiguedad = $datosFiniquito['prima_antiguedad'];

 $totalLiquidacion = bcdiv($indemnizacion + $anios_servicio + $prima_antiguedad,1,2);
 $totalLiquidacionExento = bcdiv($indemnizacion_exento,1,2);
 $totalLiquidacionGravada = bcdiv($indemnizacion_gravado + $anios_servicio + $prima_antiguedad,1,2);

 if($datosFiniquito['isr_liquidacion'] > 0){
    $titulo_indemnizacion = "ISR Indemnización";
    $impuestoIndemnizacion = $datosFiniquito['isr_liquidacion'];
  }
  else{
    $titulo_indemnizacion = "SAE Indemnización";
    $impuestoIndemnizacion = $datosFiniquito['sae_liquidacion'];
  }

  $totalLiquidacionFInal = $datosFiniquito['total_liquidacion'];
}

$pensionAlimenticiaCheck = $datosFiniquito['tipo_pension_alimenticia'];
$pension_alimenticia = $datosFiniquito['pension_alimenticia_cantidad'];
$pension_alimenticia_porc = $datosFiniquito['pension_alimenticia'];
$infonavit = $datosFiniquito['infonavit'];
$fonacot = $datosFiniquito['fonacot'];
$imss_salarios = $datosFiniquito['imss_salarios'];

if($pensionAlimenticiaCheck == 2){
  $titulo_pension = "Pensión alimenticia (Salario)";
}

if($pensionAlimenticiaCheck == 3){
  $titulo_pension = "Pensión alimenticia (Total percepciones)";
}

if($pensionAlimenticiaCheck == 4){
  $titulo_pension = "Pensión alimenticia (Total percepciones menos deducciones)";
}

$suma_deducciones = $datosFiniquito['isr_vacaciones_salarios'] + $datosFiniquito['isr_aguinaldo'] + $datosFiniquito['isr_prima_vacacional'] - $datosFiniquito['sae_vacaciones_salarios'] - $datosFiniquito['sae_aguinaldo'] - $datosFiniquito['sae_prima_vacacional'] + $pension_alimenticia + $infonavit + $fonacot + $imss_salarios;

$total_pagar = $datosFiniquito['total_pagar'];

$fechahoy = date("d/m/Y");

$spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(30);
$spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(15);
$spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(15);
$spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(15);
$spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(15);
$spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(15);
$spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(15);
$spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(15);

$styleTitle = [
      'font' => [
          'bold' => true
      ]
  ];

if($tipoMovimiento == 1 || $tipoMovimiento == 2){

  $titulo_general = "FINIQUITO";
  $spreadsheet->getActiveSheet()->mergeCells("A1:D1");
  
  $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
  $spreadsheet->getActiveSheet()->getStyle('A1')->applyFromArray($styleTitle);
  $sheet->setCellValue('A1', $titulo_general);  

  $spreadsheet->getActiveSheet()->getStyle('F1')->applyFromArray($styleTitle);
  $sheet->setCellValue('F1', $fechahoy);

  $spreadsheet->getActiveSheet()->getStyle('A3')->applyFromArray($styleTitle);
  $sheet->setCellValue('A3', "Nombre: "); 
  $sheet->setCellValue('B3', $nombreEmpleado);

  $fila = 4;

  $spreadsheet->getActiveSheet()->getStyle('A'.$fila)->applyFromArray($styleTitle);
  $sheet->setCellValue('A'.$fila, "Turno: "); 
  $sheet->setCellValue('B'.$fila, $datosFiniquito['Turno']); 

  $spreadsheet->getActiveSheet()->getStyle('D'.$fila)->applyFromArray($styleTitle);
  $sheet->setCellValue('D'.$fila, "NSS: "); 
  $sheet->setCellValue('E'.$fila, $datosFiniquito['NSS']); 
  $fila++;

  $spreadsheet->getActiveSheet()->getStyle('A'.$fila)->applyFromArray($styleTitle);
  $sheet->setCellValue('A'.$fila, "Puesto: "); 
  $sheet->setCellValue('B'.$fila, $datosFiniquito['puesto']); 

  $spreadsheet->getActiveSheet()->getStyle('D'.$fila)->applyFromArray($styleTitle);
  $sheet->setCellValue('D'.$fila, "RFC: "); 
  $sheet->setCellValue('E'.$fila, $datosFiniquito['RFC']); 
  $fila++;

  $spreadsheet->getActiveSheet()->getStyle('A'.$fila)->applyFromArray($styleTitle);
  $sheet->setCellValue('A'.$fila, "Salario: "); 
  $sheet->setCellValue('B'.$fila, $sueldo); 

  $spreadsheet->getActiveSheet()->getStyle('D'.$fila)->applyFromArray($styleTitle);
  $sheet->setCellValue('D'.$fila, "Salario diario: "); 
  $sheet->setCellValue('E'.$fila, $sueldoDiario); 
  $fila++;

  $spreadsheet->getActiveSheet()->getStyle('A'.$fila)->applyFromArray($styleTitle);
  $sheet->setCellValue('A'.$fila, "Salario diario integrado: "); 
  $sheet->setCellValue('B'.$fila, $SDI); 
  $fila++;

  $spreadsheet->getActiveSheet()->getStyle('A'.$fila)->applyFromArray($styleTitle);
  $sheet->setCellValue('A'.$fila, "Fecha de ingreso: "); 
  $sheet->setCellValue('B'.$fila, $datosFiniquito['fecha_ingreso']); 

  $spreadsheet->getActiveSheet()->getStyle('D'.$fila)->applyFromArray($styleTitle);
  $sheet->setCellValue('D'.$fila, "Fecha de salida: "); 
  $sheet->setCellValue('E'.$fila, $datosFiniquito['fecha_salida']); 
  $fila++;

  $spreadsheet->getActiveSheet()->getStyle('A'.$fila)->applyFromArray($styleTitle);
  $sheet->setCellValue('A'.$fila, "Días de aguinaldo: "); 
  $sheet->setCellValue('B'.$fila, $datosFiniquito['dias_aguinaldo']); 

  $spreadsheet->getActiveSheet()->getStyle('D'.$fila)->applyFromArray($styleTitle);
  $sheet->setCellValue('D'.$fila, "Proporcionales: "); 
  $sheet->setCellValue('E'.$fila, $datosFiniquito['dias_aguinaldo_proporcionales']); 

  $spreadsheet->getActiveSheet()->getStyle('G'.$fila)->applyFromArray($styleTitle);
  $sheet->setCellValue('G'.$fila, "Antiguedad: "); 
  $sheet->setCellValue('H'.$fila, $datosFiniquito['antiguedad']); 
  $fila++;

  $spreadsheet->getActiveSheet()->getStyle('A'.$fila)->applyFromArray($styleTitle);
  $sheet->setCellValue('A'.$fila, "Días de vac. prop.: "); 
  $sheet->setCellValue('B'.$fila, $datosFiniquito['dias_vacaciones_proporcionales']); 

  $spreadsheet->getActiveSheet()->getStyle('D'.$fila)->applyFromArray($styleTitle);
  $sheet->setCellValue('D'.$fila, "Restantes: "); 
  $sheet->setCellValue('E'.$fila, $datosFiniquito['dias_restantes']); 

  $spreadsheet->getActiveSheet()->getStyle('G'.$fila)->applyFromArray($styleTitle);
  $sheet->setCellValue('G'.$fila, "A pagar: "); 
  $sheet->setCellValue('H'.$fila, $datosFiniquito['dias_pagar']); 
  $fila = $fila + 2;

  $spreadsheet->getActiveSheet()->getStyle('A'.$fila.":D".$fila)->applyFromArray($styleTitle);
  $sheet->setCellValue('A'.$fila, "Concepto"); 
  $sheet->setCellValue('B'.$fila, "Importe");
  $sheet->setCellValue('C'.$fila, "Exento"); 
  $sheet->setCellValue('D'.$fila, "Gravado");
  $fila++; 

  $spreadsheet->getActiveSheet()->getStyle('A'.$fila)->applyFromArray($styleTitle);
  $sheet->getStyle('A'.$fila)->getAlignment()->setHorizontal('center');
  $spreadsheet->getActiveSheet()->mergeCells('A'.$fila.':D'.$fila);
  $sheet->setCellValue('A'.$fila, "PERCEPCIONES");
  $fila++;

  $inicio_alineacion1 = $fila;
  $sheet->setCellValue('A'.$fila, "Aguinaldo"); 
  $sheet->setCellValue('B'.$fila, number_format($aguinaldo,2,'.',','));
  $sheet->setCellValue('C'.$fila, number_format($aguinaldo_exento,2,'.',',')); 
  $sheet->setCellValue('D'.$fila, number_format($aguinaldo_gravado,2,'.',','));
  $fila++;  

  $sheet->setCellValue('A'.$fila, "Vacaciones"); 
  $sheet->setCellValue('B'.$fila, number_format($vacaciones_gravadas,2,'.',','));
  $sheet->setCellValue('C'.$fila, number_format($vacaciones_exentas,2,'.',',')); 
  $sheet->setCellValue('D'.$fila, number_format($vacaciones_gravadas,2,'.',','));
  $fila++;  

  $sheet->setCellValue('A'.$fila, "Prima vacacional"); 
  $sheet->setCellValue('B'.$fila, number_format($primaVacacional,2,'.',','));
  $sheet->setCellValue('C'.$fila, number_format($primaVacacional_exento,2,'.',',')); 
  $sheet->setCellValue('D'.$fila, number_format($primaVacacional_gravado,2,'.',','));
  $fila++;

  $sheet->setCellValue('A'.$fila, "Salario devengado"); 
  $sheet->setCellValue('B'.$fila, number_format($salariosDevengados,2,'.',','));
  $sheet->setCellValue('C'.$fila, "0.00"); 
  $sheet->setCellValue('D'.$fila, number_format($salariosDevengados,2,'.',','));
  $fila++;  

  $sheet->setCellValue('A'.$fila, "Otros"); 
  $sheet->setCellValue('B'.$fila, number_format($otros,2,'.',','));
  $sheet->setCellValue('C'.$fila, "0.00"); 
  $sheet->setCellValue('D'.$fila, number_format($otros,2,'.',','));
  $fila++;   

  $sheet->setCellValue('A'.$fila, "Gratificación"); 
  $sheet->setCellValue('B'.$fila, number_format($gratificacion,2,'.',','));
  $sheet->setCellValue('C'.$fila, "0.00"); 
  $sheet->setCellValue('D'.$fila, number_format($gratificacion,2,'.',','));
  $fila++;   

  $sheet->setCellValue('A'.$fila, "Bono por asistencia"); 
  $sheet->setCellValue('B'.$fila, number_format($bonoAsistencia,2,'.',','));
  $sheet->setCellValue('C'.$fila, "0.00"); 
  $sheet->setCellValue('D'.$fila, number_format($bonoAsistencia,2,'.',','));
  $fila++;  

  $sheet->setCellValue('A'.$fila, "Bono por puntualidad"); 
  $sheet->setCellValue('B'.$fila, number_format($bonoPuntualidad,2,'.',','));
  $sheet->setCellValue('C'.$fila, "0.00"); 
  $sheet->setCellValue('D'.$fila, number_format($bonoPuntualidad,2,'.',','));
  $fila++;  

  $final_alineacion1 = $fila;
  $spreadsheet->getActiveSheet()->getStyle('A'.$fila.':D'.$fila)->applyFromArray($styleTitle);
  $sheet->setCellValue('A'.$fila, "SUMA PERCEPCIONES");
  $sheet->setCellValue('B'.$fila, number_format($total,2,'.',','));
  $sheet->setCellValue('C'.$fila, number_format($total_exento,2,'.',',')); 
  $sheet->setCellValue('D'.$fila, number_format($total_gravado,2,'.',','));
  $fila++;       

  $sheet->getStyle('B'.$inicio_alineacion1.':D'.$final_alineacion1)->getAlignment()->setHorizontal('right');

  $spreadsheet->getActiveSheet()->getStyle('A'.$fila)->applyFromArray($styleTitle);
  $sheet->getStyle('A'.$fila)->getAlignment()->setHorizontal('center');
  $spreadsheet->getActiveSheet()->mergeCells('A'.$fila.':D'.$fila);
  $sheet->setCellValue('A'.$fila, "DEDUCCIONES");
  $fila++;

  $inicio_alineacion2 = $fila;
  $sheet->setCellValue('A'.$fila, $titulo1);
  $sheet->setCellValue('D'.$fila, number_format($impuestoAplicableVS,2,'.',','));
  $fila++;

  $sheet->setCellValue('A'.$fila, $titulo2);
  $sheet->setCellValue('D'.$fila, number_format($impuestoAguinaldo,2,'.',','));
  $fila++;

  $sheet->setCellValue('A'.$fila, $titulo3);
  $sheet->setCellValue('D'.$fila, number_format($impuestoPrimaVacacional,2,'.',','));
  $fila++;

  if($infonavit > 0){
    $sheet->setCellValue('A'.$fila, "INFONAVIT");
    $sheet->setCellValue('D'.$fila, number_format($infonavit,2,'.',','));
    $fila++;
  }

  if($fonacot > 0){
    $sheet->setCellValue('A'.$fila, "FONACOT");
    $sheet->setCellValue('D'.$fila, number_format($fonacot,2,'.',','));
    $fila++;
  }

  if($pensionAlimenticiaCheck != 1){
    $sheet->setCellValue('A'.$fila, $titulo_pension);
    $sheet->setCellValue('D'.$fila, number_format($pension_alimenticia,2,'.',','));
    $fila++;
  }

  if($imss_salarios > 0){
    $sheet->setCellValue('A'.$fila, "IMSS (Salarios devengados)");
    $sheet->setCellValue('D'.$fila, number_format($imss_salarios,2,'.',','));
    $fila++;
  }

  $spreadsheet->getActiveSheet()->getStyle('A'.$fila.':D'.$fila)->applyFromArray($styleTitle);
  $sheet->setCellValue('A'.$fila, "SUMA DEDUCCIONES");
  $sheet->setCellValue('D'.$fila, number_format($suma_deducciones,2,'.',','));
  $fila++;

  $final_alineacion2 = $fila;
  $spreadsheet->getActiveSheet()->getStyle('A'.$fila.':D'.$fila)->applyFromArray($styleTitle);
  $sheet->setCellValue('A'.$fila, "TOTAL FINIQUITO");
  $sheet->setCellValue('D'.$fila, number_format($total_pagar,2,'.',','));

  $sheet->getStyle('D'.$inicio_alineacion2.':D'.$final_alineacion2)->getAlignment()->setHorizontal('right');
  $archivo = "finiquito_".$nombreEmpleado;

}

if($tipoMovimiento == 2){

  $total_final = $totalLiquidacionFInal + $total_pagar;

  $fila = $fila + 2;
  $titulo_general = "LIQUIDACION";
  $spreadsheet->getActiveSheet()->mergeCells("A".$fila.":D".$fila);
  
  $sheet->getStyle('A'.$fila)->getAlignment()->setHorizontal('center');
  $spreadsheet->getActiveSheet()->getStyle('A'.$fila)->applyFromArray($styleTitle);
  $sheet->setCellValue('A'.$fila, $titulo_general);  
  $fila = $fila + 2;

  $spreadsheet->getActiveSheet()->getStyle('A'.$fila.":D".$fila)->applyFromArray($styleTitle);
  $sheet->setCellValue('A'.$fila, "Concepto"); 
  $sheet->setCellValue('B'.$fila, "Importe");
  $sheet->setCellValue('C'.$fila, "Exento"); 
  $sheet->setCellValue('D'.$fila, "Gravado");
  $fila++; 

  $inicio_alineacion3 = $fila;
  $sheet->setCellValue('A'.$fila, "Indemnización (90 días)"); 
  $sheet->setCellValue('B'.$fila, number_format($indemnizacion,2,'.',','));
  $sheet->setCellValue('C'.$fila, number_format($indemnizacion_exento,2,'.',',')); 
  $sheet->setCellValue('D'.$fila, number_format($indemnizacion_gravado,2,'.',','));
  $fila++;  

  $sheet->setCellValue('A'.$fila, "20 días por año de servicio"); 
  $sheet->setCellValue('B'.$fila, number_format($anios_servicio,2,'.',','));
  $sheet->setCellValue('C'.$fila, "0.00"); 
  $sheet->setCellValue('D'.$fila, number_format($anios_servicio,2,'.',','));
  $fila++;  

  $sheet->setCellValue('A'.$fila, "Prima antiguedad"); 
  $sheet->setCellValue('B'.$fila, number_format($prima_antiguedad,2,'.',','));
  $sheet->setCellValue('C'.$fila, "0.00"); 
  $sheet->setCellValue('D'.$fila, number_format($prima_antiguedad,2,'.',','));
  $fila++;  
  
  $spreadsheet->getActiveSheet()->getStyle('A'.$fila.':D'.$fila)->applyFromArray($styleTitle);
  $sheet->setCellValue('A'.$fila, "SUMA LIQUIDACION"); 
  $sheet->setCellValue('B'.$fila, number_format($totalLiquidacion,2,'.',','));
  $sheet->setCellValue('C'.$fila, number_format($totalLiquidacionExento,2,'.',',')); 
  $sheet->setCellValue('D'.$fila, number_format($totalLiquidacionGravada,2,'.',','));
  $fila++;  

  $sheet->getStyle('B'.$inicio_alineacion1.':D'.$final_alineacion1)->getAlignment()->setHorizontal('right');
  $spreadsheet->getActiveSheet()->getStyle('A'.$fila)->applyFromArray($styleTitle);
  $sheet->getStyle('A'.$fila)->getAlignment()->setHorizontal('center');
  $spreadsheet->getActiveSheet()->mergeCells('A'.$fila.':D'.$fila);
  $sheet->setCellValue('A'.$fila, "DEDUCCIONES");
  $fila++;

  $sheet->setCellValue('A'.$fila, $titulo_indemnizacion); 
  $sheet->setCellValue('D'.$fila, number_format($impuestoIndemnizacion,2,'.',','));
  $fila++;  

  $spreadsheet->getActiveSheet()->getStyle('A'.$fila)->applyFromArray($styleTitle);
  $sheet->setCellValue('A'.$fila, "SUMA DEDUCCIONES"); 
  $sheet->setCellValue('D'.$fila, number_format($impuestoIndemnizacion,2,'.',','));
  $fila++;  

  $spreadsheet->getActiveSheet()->getStyle('A'.$fila.':D'.$fila)->applyFromArray($styleTitle);
  $sheet->setCellValue('A'.$fila, "TOTAL LIQUIDACION");
  $sheet->setCellValue('D'.$fila, number_format($totalLiquidacionFInal,2,'.',','));
  $fila = $fila + 2;

  $final_alineacion3 = $fila;
  $total_final = $totalLiquidacionFInal + $total_pagar;
  $spreadsheet->getActiveSheet()->getStyle('A'.$fila.':D'.$fila)->applyFromArray($styleTitle);
  $sheet->setCellValue('A'.$fila, "TOTAL A PAGAR");
  $sheet->setCellValue('D'.$fila, number_format($total_final,2,'.',','));

  $sheet->getStyle('B'.$inicio_alineacion3.':D'.$final_alineacion3)->getAlignment()->setHorizontal('right');

  $archivo = "finiquito_liquidacion_".$nombreEmpleado;
}

$writer = new Xlsx($spreadsheet);
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="'.$archivo.'.xlsx"');
$writer->save("php://output");
?>