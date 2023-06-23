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
		$this->Image($image_file, 15, 15, 50, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);

        $this->Ln(50);
        // Set font
        $this->SetFont('helvetica', 'B', 14);
        // Title
        $this->Cell(0, 15, 'RECIBO DE VACACIONES', 0, false, 'C', 0, '', 0, false, 'M', 'M');
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
} // end of extended class

// ---------------------------------------------------------
// EXAMPLE
// ---------------------------------------------------------
// create new PDF document
$pdf = new MC_TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('GH MEDIC');
$pdf->SetTitle('Recibo vacaciones');
$pdf->SetSubject('Recibo vacaciones');
$pdf->SetKeywords('Recibo, vacaciones');

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
$pdf->SetFont('times', '', 12);

// Add a page
// This method has several options, check the source code documentation for more information.
$pdf->AddPage();

$pdf->ln(50);

$IdVacaciones = $_GET['id'];
    
$stmt = $conn->prepare("SELECT v.*, e.PKEmpleado, e.Primer_Nombre, e.Segundo_Nombre, e.Apellido_Paterno, e.Apellido_Materno 
                        FROM vacaciones as v 
                        INNER JOIN empleados as e ON e.PKEmpleado = v.FKEmpleado 
                        LEFT JOIN datos_empleo as de ON de.FKEmpleado = e.PKEmpleado 
                        WHERE v.PKVacaciones = :id");
$stmt->bindValue(':id',$IdVacaciones);
$stmt->execute();
$row = $stmt->fetch();
//print_r($row);
$fkEmpleado = $row['PKEmpleado'];

$segundo_nombre = '';
if(trim($row['Segundo_Nombre']) != ""){
  $segundo_nombre = ' '.$row['Segundo_Nombre'];
}

$nombreEmpleado = $row['Primer_Nombre'].$segundo_nombre.' '.$row['Apellido_Paterno'].' '.$row['Apellido_Materno'];
$fecha_ini = $row['FechaIni'];
$fecha_fin = $row['FechaFin'];
$dias_vacaciones = $row['Dias_de_Vacaciones_Tomados'];
$total_vacaciones = $row['Total_Vacaciones'];

$mes = array('enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre');

$fechaInicial = explode('-', $fecha_ini);
$mes_nombre_ini = $mes[$fechaInicial[1]-1];
$fecha_nombre_ini = $fechaInicial[2].' de '.$mes_nombre_ini.' del '.$fechaInicial[0];

$fechaFinal = explode('-', $fecha_fin);
$mes_nombre_fin = $mes[$fechaFinal[1]-1];
$fecha_nombre_fin = $fechaFinal[2].' de '.$mes_nombre_fin.' del '.$fechaFinal[0];

$fecha_completa = $fecha_nombre_ini.' al '.$fecha_nombre_fin;

$html = '<p style="font-weight:bold;text-align: rigth;">BUENO POR $ '.number_format($total_vacaciones,2).'</p>';
$pdf->writeHTML($html, true, false, true, false, '');

$pdf->ln(20);

$mes_nombre = $mes[date('m')-1];
$fecha = date('d').' de '.$mes_nombre.' del '.date('Y');
$html = '<p style="text-align: left;">En la ciudad de Zapopan, Jalisco a '.$fecha.'.</p>'; 
$pdf->writeHTML($html, true, false, true, false, '');

$pdf->ln(4);
$prima_vacacional = $row['Prima_Vacacional'];

$obj = new NumberToLetterConverter();

$cadena_prima_vacacional = $obj->numero_completo(number_format($prima_vacacional,2,'.',''));

$sueldo_vacaciones = number_format($row['Total_Vacaciones'] - $prima_vacacional,2,'.','');
$numero_letras_sueldo_vacaciones = $obj->numero_completo(number_format($sueldo_vacaciones,2,'.','')); 

$total_vacaciones = number_format($row['Total_Vacaciones'],2,'.','');;

$html = '<p style="text-align: justify;">Con esta fecha recibí de la empresa “Timlid S.A. de C.V.”, la cantidad de $ '.number_format($prima_vacacional,2).' ('.$cadena_prima_vacacional.' M.N.), por concepto de pago de PRIMA VACACIONAL, del período comprendido del '.$fecha_completa.', de conformidad con lo señalado en el artículo 80 de la Ley Federal del Trabajo (o su correlativo del contrato individual o colectivo de trabajo aplicable en la empresa), tomando en cuenta que por concepto de VACACIONES del mismo período recibí la cantidad bruta de $ '.$sueldo_vacaciones.' ('.$numero_letras_sueldo_vacaciones.' M.N.).</p>'; 
$pdf->writeHTML($html, true, false, true, false, '');

$pdf->ln(8);

$html = '<table> 
			<tr>
				<td>Sueldo por vacaciones:</td>
				<td align="rigth">'.number_format($sueldo_vacaciones,2).'</td>
				<td></td>
			</tr>
			<tr>
				<td>Prima vacacional:</td>
				<td align="rigth">'.number_format($prima_vacacional,2).'</td>
				<td></td>
			</tr>
			<tr>
				<td colspan="3"></td>
			</tr>
			<tr>
				<td colspan="3"></td>
			</tr>
			<tr style="font-weight:bold">
				<td>Total:</td>
				<td align="rigth">'.number_format($total_vacaciones,2).'</td>
				<td></td>
			</tr>
		 </table>';
$pdf->writeHTML($html, true, false, true, false, '');

$pdf->ln(30);
$html = '<p style="text-align: center;">'.$nombreEmpleado.'</p>';
$pdf->writeHTML($html, true, false, true, false, '');

$pdf->ln(15);
$html = '<p style="font-weight:bold;text-align: center;"><br/>______________________________<br/>Firma del trabajador</p>';
$pdf->writeHTML($html, true, false, true, false, '');


// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('recibo_nomina.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
