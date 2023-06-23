<?php
//============================================================+
// File name   : example_010.php
// Begin       : 2008-03-04
// Last Update : 2013-05-14
//
// Description : Example 010 for TCPDF class
//               Text on multiple columns
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: Text on multiple columns
 * @author Nicola Asuni
 * @since 2008-03-04
 */

// Include the main TCPDF library (search for installation path).
require_once('../../../lib/TCPDF/tcpdf.php');
require_once('../../../include/db-conn.php');
require_once('function_numeros.php');


/**
 * Extend TCPDF to work with multiple columns
 */
class MC_TCPDF extends TCPDF {

	/**
	 * Print chapter
	 * @param $num (int) chapter number
	 * @param $title (string) chapter title
	 * @param $file (string) name of the file containing the chapter body
	 * @param $mode (boolean) if true the chapter body is in HTML, otherwise in simple text.
	 * @public
	 */
	//Page header
    public function Header() {
        // Logo
		$image_file = '../../../img/Logo-transparente.png';
		$this->Image($image_file, 15, 15, 70, '', 'PNG', '', 'T', false, 300, 'C', false, false, 0, false, false, false);
        $this->Ln(50);
        $this->Line(10, 35, 200, 35);
        // Set font
        $this->SetFont('helvetica', 'B', 14);
        // Title
        $this->Cell(0, 15, '', 0, false, 'C', 0, '', 0, false, 'M', 'M');
    }

	public function PrintChapter($num, $title, $file) {
		// add a new page
		$this->AddPage();
		// disable existing columns
		$this->resetColumns();
		// print chapter title
		$this->ChapterTitle($num, $title);
		// set columns
		$this->setEqualColumns(1, 57);
		// print chapter body
		$this->ChapterBody($file);
	}

	/**
	 * Set chapter title
	 * @param $num (int) chapter number
	 * @param $title (string) chapter title
	 * @public
	 */
	public function ChapterTitle($num, $title) {
		$this->SetFont('helvetica', '', 14);
		$this->SetFillColor(200, 220, 255);
		$this->Cell(180, 6, 'Chapter '.$num.' : '.$title, 0, 1, '', 1);
		$this->Ln(4);
	}

	/**
	 * Print chapter body
	 * @param $file (string) name of the file containing the chapter body
	 * @param $mode (boolean) if true the chapter body is in HTML, otherwise in simple text.
	 * @public
	 */
	public function ChapterBody($file) {
		$this->selectColumn();
		// get esternal file content
		$content = $file;//file_get_contents($file, false);
		// set font
		$this->SetFont('times', '', 9);
		$this->SetTextColor(50, 50, 50);
		// print content
		$this->writeHTML($content, true, false, true, false, 'J');
		
		$this->Ln();
	}

	// Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-20);
        // Set font
        $this->SetFont('helvetica', 'I', 12);
        // Page number
        $html = '<p style="font-weight:bold;text-align: center;color:#aed6f1">Atotonilco 300, Colonia Nuevo México, Zapopan, Jalisco. CP 45087.<br> www.ghmedic.com.mx</p>';
		$this->writeHTML($html, true, false, true, false, '');
    }
} // end of extended class

// ---------------------------------------------------------
// EXAMPLE
// ---------------------------------------------------------
// create new PDF document
$pdf = new MC_TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('GH MEDIC');
$pdf->SetTitle('Recibo finiquito');
$pdf->SetSubject('Recibo finiquito');
$pdf->SetKeywords('Recibo, finiquito');

// set default header data
//$pdf->SetHeaderData("", 0, "", "");

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------
$pdf->setFontSubsetting(true);

// Set font
$pdf->SetFont('times', '', 11);

// Add a page
// This method has several options, check the source code documentation for more information.
$pdf->AddPage();

$pdf->ln(10);

$IdFiniquito = $_GET['id'];
    
$stmt = $conn->prepare("SELECT e.PKEmpleado, e.Primer_Nombre, e.Segundo_Nombre, e.Apellido_Paterno, e.Apellido_Materno, de.Fecha_Ingreso, 
                               p.Sueldo_semanal_bruto, p.Puesto, de.Deuda_Restante,t.Horas_de_trabajo, f.*
                            FROM empleados AS e
                            LEFT JOIN datos_laborales_empleado as de ON de.FKEmpleado = e.PKEmpleado 
                            LEFT JOIN puestos as p ON p.PKPuesto = de.FKPuesto 
                            LEFT JOIN finiquito as f  ON f.FKEmpleado = e.PKEmpleado
                            LEFT JOIN turnos as t ON de.FKTurno = t.PKTurno
                            WHERE f.PKFiniquito = :id");
$stmt->bindValue(':id',$IdFiniquito);
$stmt->execute();
$row = $stmt->fetch();
//print_r($row);
$fkEmpleado = $row['PKEmpleado'];

$segundo_nombre = '';
if(trim($row['Segundo_Nombre']) != ""){
  $segundo_nombre = ' '.$row['Segundo_Nombre'];
}

$nombreEmpleado = $row['Primer_Nombre'].$segundo_nombre.' '.$row['Apellido_Paterno'].' '.$row['Apellido_Materno'];
$fechaIngreso = $row['Fecha_Ingreso'];
$Sueldo_diario = number_format($row['Sueldo_semanal_bruto'] / 7,2,'.','');
$Sueldo_semanal = $row['Sueldo_semanal_bruto']; 
$Puesto = $row['Puesto'];
$Sueldo_diario_integrado = $Sueldo_diario * 1.0452;
$prestamo = $row['Deuda_Restante'];
$horasTrabajoArray = explode(':',$row['Horas_de_trabajo']);
$horasTrabajo = $horasTrabajoArray[0] + ($horasTrabajoArray[1]/60);
$sueldo_Hora = $row['Sueldo_semanal_bruto'] / $horasTrabajo;

/*Calculo desde la DB de los datos del finiquito*/
function esBisiesto($year=NULL) {
  $year = ($year==NULL)? date('Y'):$year;
  return ( ($year%4 == 0 && $year%100 != 0) || $year%400 == 0 );
}
$fechaSalidaBD = $row['FechaSalida'];
$salarioDevengadoBD = $row['SalariosDevengados'];
$fechaIniciaSD_BD = $row['FechaInicialSalariosDevengados'];
$fechaFinalSD_BD = $row['FechaFinalSalariosDevengados'];
$horasExtrasBD = $row['HorasExtras'];
$horasExtras_calculo = $horasExtrasBD * $sueldo_Hora;
$prestamosBD = $row['Prestamos'];
$ISRProporcionalBD = $row['ISRProporcional'];
$ISRetenidoBD = $row['ISRRetenido'];
$SubsidioBD = $row['Subsidio'];

$datetime1 = new DateTime($fechaIngreso); // Fecha inicial
$datetime2 = new DateTime($fechaSalidaBD); // Fecha actual
$interval = $datetime1->diff($datetime2);
$num_dias_antiguedad = $interval->format('%a');

$fechaIngresoComoEntero = strtotime($fechaIngreso);
$fechaFinalComoEntero = strtotime($fechaSalidaBD);
$num_anios = 0;
$num_dias = 0;

/*CALCULO DIAS AGUINALDO*/
if(date("Y",$fechaIngresoComoEntero) == date("Y") ){
  $fechainicial = $fechaIngreso;
}
else{
  $fechainicial = date("Y",$fechaFinalComoEntero).'-01-01';
}

$fecha1= new DateTime($fechainicial);
$diff = $fecha1->diff($datetime2);
$factor_dias = $diff->days + 1;

if(esBisiesto(date("Y",$fechaFinalComoEntero))){
  $dias_aguinaldo_calculo = number_format((15 / 366) * $factor_dias,2,'.',''); 
}
else{
  $dias_aguinaldo_calculo = number_format((15 / 365) * $factor_dias,2,'.','');
}
  $calculo_aguinaldo = number_format($dias_aguinaldo_calculo * $Sueldo_diario,2,'.','');
/*FIN CALCULO DIAS AGUINALDO*/

$num_dias_totales = 0;
if(date("m",$fechaIngresoComoEntero) == date("m",$fechaFinalComoEntero) && date("d",$fechaIngresoComoEntero) == date("d",$fechaFinalComoEntero)){

    for($x = date("Y",$fechaIngresoComoEntero);$x < date("Y",$fechaFinalComoEntero);$x++){  
      $num_anios++;
    }

    $num_anios_json = number_format($num_anios,3,'.','');
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
    $num_anios = $num_dias_totales; 
}

/*CALCULO DIAS DE VACACIONES*/
$stmt = $conn->prepare("SELECT IFNULL(de.Dias_de_Vacaciones,0) as Dias_de_Vacaciones,IFNULL(SUM(v.Dias_de_Vacaciones_Tomados),0) 
                        as Dias_de_Vacaciones_Tomados
                        FROM datos_laborales_empleado as de
                        LEFT JOIN vacaciones as v ON v.FKEmpleado = de.FKEmpleado AND YEAR(FechaIni) = :fecha_anio
                        WHERE de.FKEmpleado = :id 
                        GROUP BY v.FKEmpleado");
$stmt->bindValue(':id',$fkEmpleado);
$stmt->bindValue(':fecha_anio',date("Y",$fechaFinalComoEntero));
$stmt->execute();
$row_dv = $stmt->fetch();

$dias_vacaciones = $row_dv['Dias_de_Vacaciones'];
$dias_vacaciones_tomados = $row_dv['Dias_de_Vacaciones_Tomados'];
$dias_vacaciones_restantes = $dias_vacaciones - $dias_vacaciones_tomados;

switch ($num_dias_totales)
{
  case ($num_dias_totales > 0 && $num_dias_totales < 2):
    $dias_vacaciones = 6;
    break;
  case ($num_dias_totales > 2 && $num_dias_totales < 3):
    $dias_vacaciones = 8;
    break;
  case ($num_dias_totales > 3 && $num_dias_totales < 4):
    $dias_vacaciones = 10;
    break;
  case ($num_dias_totales > 4 && $num_dias_totales < 5):
    $dias_vacaciones = 12;
    break;
  case ($num_dias_totales > 5 && $num_dias_totales < 10):
    $dias_vacaciones = 14;
    break;
  case ($num_dias_totales > 10 && $num_dias_totales < 15):
    $dias_vacaciones = 16;
    break;
  case ($num_dias_totales > 15 && $num_dias_totales < 20):
    $dias_vacaciones = 18;
    break;
  case ($num_dias_totales > 20 && $num_dias_totales < 25):
    $dias_vacaciones = 20;
    break;
  case ($num_dias_totales > 25 && $num_dias_totales < 30):
    $dias_vacaciones = 22;
    break;
  case ($num_dias_totales > 30 && $num_dias_totales < 35):
    $dias_vacaciones = 24;
    break;
  case ($num_dias_totales > 35 && $num_dias_totales < 40):
    $dias_vacaciones = 26;
    break;
  case ($num_dias_totales > 40 && $num_dias_totales < 45):
    $dias_vacaciones = 28;
    break;
  case ($num_dias_totales > 45 && $num_dias_totales < 50):
    $dias_vacaciones = 30;
    break;
}

if(esBisiesto(date("Y",$fechaFinalComoEntero))){
  $dias_vacaciones_calculo = ($dias_vacaciones / 366) * $factor_dias; 
}
else{
  $dias_vacaciones_calculo = ($dias_vacaciones / 365) * $factor_dias;
}
$dias_vacaciones_json = $dias_vacaciones; 
$dias_vacaciones_tomados_json = $dias_vacaciones_tomados;

if($dias_vacaciones_calculo > $dias_vacaciones_tomados){
  $dias_vacaciones_a_pagar = $dias_vacaciones_calculo - $dias_vacaciones_tomados;
}
else{
  $dias_vacaciones_a_pagar = 0.00;
}
$dias_vacaciones_calculo_json = number_format($dias_vacaciones_calculo,2,'.',''); 
$dias_vacaciones_a_pagar_json = number_format($dias_vacaciones_a_pagar,2,'.',''); 
$calculo_vacaciones_json = number_format($dias_vacaciones_a_pagar * $Sueldo_diario,2,'.','');
$prima_vacacional_json = number_format(($dias_vacaciones_a_pagar * $Sueldo_diario) *.25,2,'.','');

$subtotal_finiquito_json = number_format($calculo_aguinaldo + $calculo_vacaciones_json + $prima_vacacional_json + $salarioDevengadoBD + $horasExtras_calculo ,2,'.','');

if($subtotal_finiquito_json > $prestamosBD){
  $cantidad_neta_recibida = number_format($subtotal_finiquito_json - $prestamosBD ,2,'.','');
}
else{
  $cantidad_neta_recibida = 0.00;
}

/*FIN CALCULO DIAS DE VACACIONES*/
$stmt = $conn->prepare("SELECT cantidad FROM parametros WHERE descripcion = 'UMA' OR descripcion = 'Factor_mes' ORDER BY PKParametros Asc");
$stmt->execute();
$row_parametros = $stmt->fetchAll();
$UMA = $row_parametros[0]['cantidad'];
$dias_mes = $row_parametros[1]['cantidad'];
$dias_periodo = 13;//verificar cantidad

$limite_gravar_prima_vacacional = $UMA * 15;
$limite_gravar_aguinaldo = $UMA * 30;

//base gravable aguinaldo
if($calculo_aguinaldo > $limite_gravar_aguinaldo){
	$aguinaldo_gravar =  number_format($calculo_aguinaldo - $limite_gravar_aguinaldo,2);
	$aguinaldo_excento = $limite_gravar_aguinaldo - $aguinaldo_gravar;
  }
  else{
	$aguinaldo_gravar = 0.00;
	$aguinaldo_excento = $calculo_aguinaldo;
  }

  $aguinaldo_excento =  number_format($aguinaldo_excento,2,'.','');
  $aguinaldo_gravar = number_format($aguinaldo_gravar,2,'.','');

  //base gravable prima vacacional
  if($prima_vacacional_json > $limite_gravar_prima_vacacional){
	$prima_vacacional_gravar =  number_format($prima_vacacional_json - $limite_gravar_prima_vacacional,2);
	$prima_vacacional_excento = $limite_gravar_prima_vacacional - $prima_vacacional_gravar;
  }
  else{
	$prima_vacacional_gravar = 0.00;
	$prima_vacacional_excento = $prima_vacacional_json;
  }
  $prima_vacacional_excento =  number_format($prima_vacacional_excento,2,'.','');
  $prima_vacacional_gravar = number_format($prima_vacacional_gravar,2,'.','');

  //base gravable horas extras
  $salarioTiempoExtra = $horasExtras_calculo;
  $salarioTiempoExtraLimite = ($horasExtras_calculo) / 2;
  $limite_excento_salarioExtra = $UMA * 5;

  if($salarioTiempoExtraLimite > $limite_excento_salarioExtra){
	$salarioTiempoExtraBase =  $salarioTiempoExtra - $limite_excento_salarioExtra;
	$tiempoextra_excento = $salarioTiempoExtra - $salarioTiempoExtraBase;
  }
  else{
	$salarioTiempoExtraBase = $salarioTiempoExtraLimite;
	$tiempoextra_excento = $salarioTiempoExtraLimite;
  }
		
$base_finiquito_pre = $calculo_vacaciones_json + $prima_vacacional_gravar + $aguinaldo_gravar + $salarioDevengadoBD  + $salarioTiempoExtraBase - $prestamosBD;
$SubsidioProporcional = number_format($SubsidioBD/$dias_mes * $dias_periodo,2,'.','');

$subtotal_finiquito_gravado = number_format($base_finiquito_pre,2,'.','');
$tasa_isr_pre = $ISRetenidoBD / $subtotal_finiquito_gravado;
$tasa_isr = number_format($tasa_isr_pre * 100,2, '.', '');
$tasa_isr = $tasa_isr." %";

$total_pagar = number_format($cantidad_neta_recibida - $ISRetenidoBD,2,'.','');
/*FIN Calculo desde la DB de los datos del finiquito*/

$mes = array('ENERO', 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO', 'JULIO', 'AGOSTO', 'SEPTIEMBRE', 'OCTUBE', 'NOVIEMBRE', 'DICIEMBRE');

/*$fechaInicial = explode('-', $fecha_ini);
$mes_nombre_ini = $mes[$fechaInicial[1]-1];
$fecha_nombre_ini = $fechaInicial[2].' de '.$mes_nombre_ini.' del '.$fechaInicial[0];

$fechaFinal = explode('-', $fecha_fin);
$mes_nombre_fin = $mes[$fechaFinal[1]-1];
$fecha_nombre_fin = $fechaFinal[2].' de '.$mes_nombre_fin.' del '.$fechaFinal[0];

$fecha_completa = $fecha_nombre_ini.' al '.$fecha_nombre_fin;*/

$html = '<p style="font-weight:bold;text-align: rigth;">BUENO POR $ '.$total_pagar.'</p>';
$pdf->writeHTML($html, true, false, true, false, '');

$pdf->ln(10);

$obj = new NumberToLetterConverter();
$cadena_total = $obj->numero_completo(number_format($total_pagar,2,'.',''));
$html = '<p style="text-align: justify;">Con esta fecha en la que di por terminado mi Contrato de Trabajo, renunciando a mi empleo voluntariamente en términos de la fracción I del artículo 53 de la Ley Laboral, recibo a mi entera satisfacción la cantidad de $ '.$total_pagar.' ('.$cadena_total.' M.N) en: efectivo ( X ) cheque (  ), Por concepto de: LIQUIDACION Y PAGO FINAL DE TODAS Y CADA UNA DE MIS PRESTACIONES LABORALES ORDINARIAS Y EXTRAORDINARIAS, LEGALES COMO CONTRACTUALES A QUE TENGO DERECHO. Comprendiendo de modo enunciativo y no limitativo: </p>'; 
$pdf->writeHTML($html, true, false, true, false, '');

$pdf->ln(8);

$cadena_fecha_salarios_devengados = "";
$fechaInicialCadena = date("d/m/Y", strtotime($fechaIniciaSD_BD));
$fechaFinalCadena = date("d/m/Y", strtotime($fechaFinalSD_BD));

if($fechaFinalSD_BD != "0000-00-00")
	$cadena_fecha_salarios_devengados = $fechaInicialCadena.' al '.$fechaFinalCadena;
$html = '<table> 
			<tr>
				<td>PRESTACIONES</td>
				<td>IMPORTE</td>
				<td>PERIODO</td>
			</tr>
			<tr>
				<td>SALARIOS DEVENGADOS </td>
				<td align="center">'.number_format($salarioDevengadoBD,2).'</td>
				<td align="center">'.$cadena_fecha_salarios_devengados.'</td>
			</tr>
			<tr>
				<td>HORAS EXTRAS</td>
				<td align="center">'.number_format($horasExtras_calculo,2).'</td>
				<td></td>
			</tr>
			<tr>
				<td>VACACIONES</td>
				<td align="center">'.number_format($calculo_vacaciones_json,2).'</td>
				<td></td>
			</tr>
			<tr>
				<td>PRIMA VACACIONAL</td>
				<td align="center">'.number_format($prima_vacacional_json,2).'</td>
				<td></td>
			</tr>
			<tr>
				<td>AGUINALDO</td>
				<td align="center">'.number_format($calculo_aguinaldo,2).'</td>
				<td></td>
			</tr>
			<tr>
				<td>DEUDA</td>
				<td align="center">'.number_format($prestamosBD,2).'</td>
				<td></td>
			</tr>
			<tr style="font-weight:bold">
				<td>SUBTOTAL 1</td>
				<td align="center">'.number_format($cantidad_neta_recibida,2).'</td>
				<td></td>
			</tr>
		 </table>';
$pdf->writeHTML($html, true, false, true, false, '');

$html = '<p style="text-align: justify;">Determinación del Impuesto Sobre la Renta Art. 113 LISR.</p>'; 
$pdf->writeHTML($html, true, false, true, false, '');

$html = '<table> 
			<tr>
				<td>ISR PROPORCIONAL</td>
				<td align="center">'.$ISRProporcionalBD.'</td>
				<td></td>
			</tr>
			<tr>
				<td>SUBSIDIO</td>
				<td align="center">'.number_format($SubsidioProporcional,2).'</td>
				<td>'.number_format($SubsidioBD,2).'</td>
			</tr>
			<tr>
				<td>ISR RETENIDO</td>
				<td align="center">'.$ISRetenidoBD.'</td>
				<td>'.$tasa_isr.'</td>
			</tr>
			<tr>
				<td colspan="3"></td>
			</tr>
			<tr style="font-weight:bold">
				<td>SUBTOTAL 2</td>
				<td align="center">'.number_format($total_pagar,2).'</td>
				<td></td>
			</tr>
			<tr>
				<td colspan="3"></td>
			</tr>
			<tr style="font-weight:bold">
				<td>CANTIDAD NETA RECIBIDA</td>
				<td></td>
				<td align="center">'.number_format($total_pagar,2).'</td>
			</tr>
		 </table>';
$pdf->writeHTML($html, true, false, true, false, '');

$html = '<p style="text-align: justify;">Manifiesto para los efectos legales a que haya lugar que mi salario, puesto y antigüedad que sirven de base para cuantificar el presente son:</p>'; 
$pdf->writeHTML($html, true, false, true, false, '');

$html = '<table> 
			<tr>
				<td>SALARIO</td>
				<td>'.number_format($Sueldo_semanal,2).'</td>
			</tr>
			<tr>
				<td>PUESTO</td>
				<td>'.$Puesto.'</td>
			</tr>
			<tr>
				<td>ANTIGÜEDAD</td>
				<td>'.$num_dias_totales.'</td>
			</tr>
		 </table>';
$pdf->writeHTML($html, true, false, true, false, '');

$html = '<p style="text-align: justify;">Por tal motivo extiendo el más amplio y valedero RECIBO FINIQUITO que en derecho proceda, haciendo constar que no se me adeuda prestación alguna de ninguna naturaleza, firmando el presente para constancia.</p>'; 
$pdf->writeHTML($html, true, false, true, false, '');

$mes_nombre = $mes[date('m')-1];
$fecha = date('d').' DE '.$mes_nombre.' DEL '.date('Y');
$pdf->ln(2);
$html = '<p style="text-align: center;">EN LA CIUDAD DE ZAPOPAN A '.$fecha.'.</p>'; 
$pdf->writeHTML($html, true, false, true, false, '');

$pdf->ln(2);
$html = '<p style="text-align: center;">NOMBRE Y FIRMA O HUELLA DEL TRABAJADOR</p>'; 
$pdf->writeHTML($html, true, false, true, false, '');

$pdf->ln(2);
$html = '<p style="font-weight:bold;text-align: center;"><br/>______________________________<br/>'.$nombreEmpleado.'</p>';
$pdf->writeHTML($html, true, false, true, false, '');

$pdf->ln(2);
$html = '<table style="font-weight:bold;text-align: center;">
				<tr>
					<td>TESTIGOS NOMBRE Y FIRMA</td>
					<td>TESTIGOS NOMBRE Y FIRMA</td>
				</tr>
				<tr>
					<td colspan="2"></td>
				</tr>
				<tr>
					<td colspan="2"></td>
				</tr>
				<tr>
					<td>______________________________</td>
					<td>______________________________</td>
				</tr>
		</table>';
$pdf->writeHTML($html, true, false, true, false, '');

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('recibo_finiquito_'.$nombreEmpleado.'.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
