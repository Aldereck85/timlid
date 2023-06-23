<?php
require_once('../../../lib/TCPDF/tcpdf.php');
require_once('../../../include/db-conn.php');

class PDF extends TCPDF {
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
        if(isset($_POST['txtCompra'])){
          $ref = $_POST['txtCompra'];
        }
        if(isset($_GET['txtCompra'])){
          $ref = $_GET['txtCompra'];
        }
        $referencia = "OC".str_pad($ref, 6, "0", STR_PAD_LEFT);
  		// Logo
  		$image_file = '../../../img/Logo-transparente.png';
  		$this->Image($image_file, 10, 11, 33, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
      $this->Cell(40);

      // Set font
      $this->SetFont('helvetica', 'B', 14);
      // Title
      $this->Cell(30, 10, 'Orden de Compra: '.$referencia, 0, false, 'C', 0, '', 0, false, 'M', 'M');
      $this->Cell(50);
      $this->SetFont('Times','',12);
      $this->Cell(30,10,date('d/m/Y'),0, false, 'C', 0, '', 0, false, 'M', 'M');
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

}


$pdf = new PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('GH MEDIC');
$pdf->SetTitle('Orden de compra');
$pdf->SetSubject('Orden de compra');
$pdf->SetKeywords('Orden de compra');
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
//$header=Array('No','Clave','Productos','Cantidad','Precio unitario');

$pdf->AddPage();
$pdf->SetFont('Times','',12);
$total = 0;
$x = 1;

if(isset($_GET['txtCompra'])){
  ob_start();
  error_reporting(E_ALL & ~E_NOTICE);
  ini_set('display_errors', 0);
  ini_set('log_errors', 1);
  $id = $_GET['txtCompra'];
  $stmt = $conn->prepare('SELECT p.Clave,p.Descripcion,u.Unidad_de_Medida,u.Piezas_por_Caja,pr.Precio_Unitario,pr.Cantidad
          FROM productos_oc as pr
          LEFT JOIN productos as p ON pr.FKProducto = p.PKProducto
          LEFT JOIN unidad_medida as u ON p.FKUnidadMedida = u.PKUnidadMedida
          WHERE FKOrdenCompra = :id');
  $stmt->bindValue(':id',$id);
  $stmt->execute();
  $rowCount = $stmt->rowCount();
}

$text = 'Se envía listado de productos para la compra de los mismos:';
$pdf->Cell(100,0,$text);
$pdf->Ln(10);
$tbl = '
<style>
  table {
    font-family: "Lucida Sans Unicode","Lucida Grande", Sans-Serif;
    font-size: 12px;
    margin: 45px;
    width: 480px;
    text-align: left;
    border: none;
    width: 100%;
    border-collapse: collapse;

  }
  .thbody {
    font-size: 12px;
    font-weight: bold;
    padding: 8px;
    background-color: #b9c9fe;
    border-top: 2px solid #aabcfe;
    border-bottom: 1px solid #fff; color: #039;
    border-right: 1px solid #fff;
    text-align: center;
    height: 25px;
    line-height: 20px
  }
  .tdbody {
    font-size: 10px;
    padding: 8px;
    background-color: #e8edff;
    border-bottom: 1px solid #fff;
    color: #669;
    border-top: 1px solid transparent;
    border-right: 1px solid #fff;
    vertical-align: center;
    text-align: center;
    line-height: 20px;
  }
  tr:hover td {
    background: #d0dafd;
    color: #339;
  }
  .total {
    text-align: right;
    font-weight:bold;
    font-size: 12px;
    padding: 8px;
    background-color: #e8edff;
    border-bottom: 1px solid #fff;
    color: #669;
    border-right: 1px solid #fff;
    vertical-align: right;
    text-align: right;
    line-height: 20px;
    font-weight:bold;

  }
  .thtotales {
    text-align: right;
    font-size: 12px;
    font-weight: bold;
    padding: 8px;
    background-color: #b9c9fe;

    border-bottom: 1px solid #fff; color: #039;
    border-right: 1px solid #fff;
    height: 25px;
    line-height: 20px
  }

</style>
  <table>
    <thead>
      <tr>
        <th class="thbody" width="10%">No</th>
        <th class="thbody" width="10%">Clave</th>
        <th class="thbody" width="40%">Producto</th>
        <th class="thbody" width="10%">Cantidad</th>
        <th class="thbody" width="15%">Precio unitario</th>
        <th class="thbody" width="15%">Importe</th>
      </tr>

    </thead>
    <tbody>
';
while($row = $stmt->fetch()){
  $tbl .= '
    <tr>
      <td class="tdbody" width="10%">'.$x.'</td>
      <td class="tdbody" width="10%">'.$row['Clave'].'</td>
      <td class="tdbody" width="40%">'.$row['Descripcion']." ".$row['Unidad_de_Medida']." c/".$row['Piezas_por_Caja'].'</td>
      <td class="tdbody" width="10%">'.$row['Cantidad'].'</td>
      <td class="tdbody" width="15%" style="text-align: rigth">'."$".number_format($row['Precio_Unitario'],2).'</td>
      <td class="tdbody" width="15%" style="text-align: rigth">'."$".number_format(($row['Importe']),2).'</td>
    </tr>
  ';
  $x++;
  $subtotal += $row['Cantidad'] * $row['Precio_Unitario'];
  $iva += $subtotal * 0.16;
  $total = ($subtotal + $iva);
}
$tbl .= '
      <tr>
        <td style="background-color: none"></td>
        <td style="background-color: none"></td>
        <td style="background-color: none"></td>
        <td style="background-color: none"></td>
        <th class="thtotales">Subtotal: </th>
        <td class="total">'."$".number_format($subtotal,2).'</td>
      </tr>
      <tr>
      <td style="background-color: none"></td>
      <td style="background-color: none"></td>
      <td style="background-color: none"></td>
      <td style="background-color: none"></td>
      <th class="thtotales">IVA 16%: </th>
      <td class="total">'."$".number_format($iva,2).'</td>
      </tr>
      <tr>
        <td style="background-color: none"></td>
        <td style="background-color: none"></td>
        <td style="background-color: none"></td>
        <td style="background-color: none"></td>
        <th class="thtotales">Total: </th>
        <td class="total">'."$".number_format($total,2).'</td>
      </tr>

    </tbody>
  </table>
';
$pdf->writeHTML($tbl, true, false, true, false, '');
//$pdf->writeHTML($tbl, true, false, true, false, 'C');
ob_end_clean();
$pdf->Output('orden_compra '.$referencia.'.pdf', 'I');
?>
