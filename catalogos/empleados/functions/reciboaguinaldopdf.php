<?php
session_start();
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

if(isset($_SESSION["Usuario"]) && ($_SESSION["FKRol"] == 1 || $_SESSION["FKRol"] == 4)){
    require_once('../../../include/db-conn.php');

    $IdEmpleado = $_GET['id'];

    $stmt = $conn->prepare("SELECT e.PKEmpleado, e.Primer_Nombre, e.Segundo_Nombre, e.Apellido_Paterno, e.Apellido_Materno, de.Fecha_Ingreso, em.NSS, e.RFC,
                                   p.Sueldo_semanal, p.Sueldo_semanal_bruto, p.Puesto, t.Turno, de.PKLaboralesEmpleado
                            FROM empleados AS e
                            LEFT JOIN datos_laborales_empleado as de ON de.FKEmpleado = e.PKEmpleado
                            LEFT JOIN datos_medicos_empleado as em ON em.FKEmpleado = e.PKEmpleado
                            LEFT JOIN puestos as p ON p.PKPuesto = de.FKPuesto
                            LEFT JOIN turnos as t ON t.PKTurno = de.FKTurno
                            WHERE e.PKEmpleado = :id ");
    $stmt->bindValue(':id',$IdEmpleado);
    $stmt->execute();
    $row = $stmt->fetch();

    $stmt = $conn->prepare("SELECT COUNT(PKChecada) as suma FROM gh_checador WHERE FKUsuario = :id AND Estatus = 0 AND YEAR(Fecha) = YEAR(curdate())");
    $stmt->bindValue(':id',$IdEmpleado);
    $stmt->execute();
    $row_diasfalta = $stmt->fetch();
    $dias_falta = $row_diasfalta['suma'];

    $stmt = $conn->prepare("SELECT cantidad FROM parametros WHERE descripcion = 'Dias_Aguinaldo' OR descripcion = 'Dias_excentos_Aguinaldo' OR descripcion = 'UMA' Order BY PKParametros ASC");
    $stmt->execute();
    $row_diasaguinaldo = $stmt->fetchAll();
    $dias_aguinaldo = $row_diasaguinaldo[1]['cantidad'];
    $dias_excentos_aguinaldo = $row_diasaguinaldo[2]['cantidad'];
    $uma = $row_diasaguinaldo[0]['cantidad'];
    $aguinaldo_exento = number_format($uma * $dias_excentos_aguinaldo,2,'.','');

    $segundo_nombre = '';
    if(trim($row['Segundo_Nombre']) != ""){
      $segundo_nombre = ' '.$row['Segundo_Nombre'];
    }

    $nombreEmpleado = $row['Primer_Nombre'].$segundo_nombre.' '.$row['Apellido_Paterno'].' '.$row['Apellido_Materno'];
    $nss = $row['NSS'];
    $rfc = $row['RFC'];
    $puesto = $row['Puesto'];
    $turno = $row['Turno'];;
    $Sueldo_semanal = $row['Sueldo_semanal_bruto'];
    $Sueldo_diario = number_format($Sueldo_semanal / 7,2,'.','');
    $Sueldo_Mensual = number_format($Sueldo_diario * 30,2,'.','');
    $ValidarDatosEmpleo = $row['PKLaboralesEmpleado'];

    $fechaIngreso = $row['Fecha_Ingreso'];
    /*Calculo desde la DB de los datos del finiquito*/
    function esBisiesto($year=NULL) {
      $year = ($year==NULL)? date('Y'):$year;
      return ( ($year%4 == 0 && $year%100 != 0) || $year%400 == 0 );
    }
    $fechaFinal = date("Y").'-12-31';

    if($fechaIngreso > date("Y").'-01-01')
    {
      $fechaInicial = $fechaIngreso;
    }
    else{
      $fechaInicial = date("Y").'-01-01';
    }

    $datetime1 = new DateTime($fechaInicial); // Fecha inicial
    $datetime2 = new DateTime($fechaFinal); // Fecha actual
    $interval = $datetime1->diff($datetime2);
    $num_dias_trabajados = $interval->format('%a') + 1 - $dias_falta;

    if(esBisiesto(date("Y"))){
      $dias_anio = 366;
    }
    else{
      $dias_anio = 365;
    }
    $porcentaje_proporcional = number_format($dias_aguinaldo / $dias_anio,3);
    $dias_trabajados_proporcional = number_format($porcentaje_proporcional * $num_dias_trabajados,2);
    
    if($dias_trabajados_proporcional > 15)
      $dias_trabajados_proporcional = 15;

    $aguinaldo = number_format($dias_trabajados_proporcional * $Sueldo_diario,2,'.','');

    $ingresoMensuales = number_format($Sueldo_Mensual + $aguinaldo,2,'.','');

    $aguinaldoGravado = number_format($ingresoMensuales - $aguinaldo_exento,2,'.','');

    $stmt = $conn->prepare("SELECT * FROM pagos_provisionales_mensuales WHERE Limite_inferior <= :impuestogravablemin AND Limite_superior >= :impuestogravablesup ");
    $stmt->bindValue(':impuestogravablemin',$aguinaldoGravado);
    $stmt->bindValue(':impuestogravablesup',$aguinaldoGravado);
    $stmt->execute();
    $row_limite = $stmt->fetch();

    $Limite_inferior = $row_limite['Limite_inferior'];
    $excedente_limite_inferior = number_format($aguinaldoGravado - $Limite_inferior,2,'.','');
    $porcentaje_tabla = $row_limite['Porcentaje_sobre_limite_inferior'];
    $impuesto_marginal = number_format($excedente_limite_inferior * ($porcentaje_tabla/100), 2, '.', '');
    $cuota_fija = $row_limite['Cuota_fija'];

    $stmt = $conn->prepare("SELECT * FROM subsidio_empleo WHERE IngresoMinimo <= :ingresominimo AND IngresoMaximo >= :ingresomaximo ");
    $stmt->bindValue(':ingresominimo',$aguinaldoGravado);
    $stmt->bindValue(':ingresomaximo',$aguinaldoGravado);
    $stmt->execute();
    $row_subsidio = $stmt->fetch();
    $subsidioMensual = $row_subsidio['SubsidioMensual'];

    $ISRDeterminado = number_format($impuesto_marginal + $cuota_fija - $subsidioMensual, 2, '.', '');

    /*ISR solo salario*/
    $baseISRSalario = $Sueldo_Mensual;
    $stmt = $conn->prepare("SELECT * FROM pagos_provisionales_mensuales WHERE Limite_inferior <= :impuestogravablemin AND Limite_superior >= :impuestogravablesup ");
    $stmt->bindValue(':impuestogravablemin',$baseISRSalario);
    $stmt->bindValue(':impuestogravablesup',$baseISRSalario);
    $stmt->execute();
    $row_limite_sueldo = $stmt->fetch();
    $Limite_inferior_sueldo = $row_limite_sueldo['Limite_inferior'];
    $excedente_limite_inferior_sueldo = number_format($baseISRSalario - $Limite_inferior_sueldo, 2, '.', '');
    $porcentaje_tabla_sueldo = $row_limite_sueldo['Porcentaje_sobre_limite_inferior'];
    $impuesto_marginal_sueldo = number_format($excedente_limite_inferior_sueldo * ($porcentaje_tabla_sueldo / 100), 2, '.', '');
    $cuota_fija_sueldo = $row_limite_sueldo['Cuota_fija'];

    $stmt = $conn->prepare("SELECT * FROM subsidio_empleo WHERE IngresoMinimo <= :ingresominimo AND IngresoMaximo >= :ingresomaximo ");
    $stmt->bindValue(':ingresominimo',$baseISRSalario);
    $stmt->bindValue(':ingresomaximo',$baseISRSalario);
    $stmt->execute();
    $row_subsidio_sueldo = $stmt->fetch();
    $subsidioMensualSueldo = $row_subsidio_sueldo['SubsidioMensual'];

    $ISRSalario = number_format($impuesto_marginal_sueldo + $cuota_fija_sueldo - $subsidioMensualSueldo, 2, '.', '');
    $ISRAguinaldo = number_format($ISRDeterminado - $ISRSalario, 2, '.', '');
    $AguinaldoAPagar = number_format($aguinaldo - $ISRAguinaldo , 2, '.', '');

  }else {
    header("location:../../dashboard.php");
  }

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
        $this->Cell(0, 15, 'Recibo de pago de aguinaldo', 0, false, 'C', 0, '', 0, false, 'M', 'M');
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
$pdf->SetTitle('Recibo aguinaldo');
$pdf->SetSubject('Recibo aguinaldo');
$pdf->SetKeywords('Recibo, aguinaldo');

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

$mes = array('enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre');

$fechaInicial = explode('-', $fechaIngreso);
$mes_nombre_ini = $mes[$fechaInicial[1]-1];
$fecha_nombre_ini = $fechaInicial[2].' de '.$mes_nombre_ini.' del '.$fechaInicial[0];

$fechaActual = explode('-', date('Y-m-d'));
$mes_nombre_actual = $mes[$fechaActual[1]-1];
$fecha_nombre_actual = $fechaActual[2].' de '.$mes_nombre_actual.' del '.$fechaActual[0];

$html = '<p style="font-weight:bold;text-align: rigth;">BUENO POR $ '.$AguinaldoAPagar.'</p>';
$pdf->writeHTML($html, true, false, true, false, '');

$pdf->ln(20);

$obj = new NumberToLetterConverter();
$cantidad_aguinaldo = $obj->numero_completo(number_format($AguinaldoAPagar,2,'.',''));

$html = '<p style="text-align: justify;">Recibí de “Timlid S.A. de C.V.”, la cantidad de $ '.$AguinaldoAPagar.' ('.$cantidad_aguinaldo.' M.N.), por concepto de aguinaldo correspondiente al año '.date('Y').', bajo mi entera conformidad.</p>';
$pdf->writeHTML($html, true, false, true, false, '');

$pdf->ln(10);
$html = '<table>
			<tr>
				<td>Fecha de ingreso</td>
				<td>'.$fecha_nombre_ini.'</td>
			</tr>
			<tr>
				<td>Salario Diario</td>
				<td >'.$Sueldo_diario.'</td>
			</tr>
			<tr>
				<td>Puesto</td>
				<td>'.$puesto.'</td>
				<td></td>
			</tr>
			<tr>
				<td>Aguinaldo neto</td>
				<td>'.$AguinaldoAPagar.'</td>
			</tr>
		 </table>';
$pdf->writeHTML($html, true, false, true, false, '');

$pdf->ln(10);
$html = '<p>Zapopan, Jalisco a '.$fecha_nombre_actual.'.</p>';
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
