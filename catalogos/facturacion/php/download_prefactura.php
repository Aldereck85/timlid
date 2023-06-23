<?php
session_start();
require_once('../../../lib/TCPDF/tcpdf.php');
require('../../../include/db-conn.php');
require_once('../../../lib/phpmailer_configuration.php');

$query = sprintf("SELECT e.logo, e.RazonSocial, e.RFC, e.domicilio_fiscal, crf.descripcion
FROM empresas as e 
LEFT JOIN claves_regimen_fiscal AS crf ON e.regimen_fiscal_id = crf.id 
WHERE PKEmpresa = :id");
$stmt = $conn->prepare($query);
$stmt->execute([":id" => $_SESSION['IDEmpresa']]);
$empresa = $stmt->fetch(PDO::FETCH_ASSOC);
$logo = $_ENV['RUTA_ARCHIVOS_READ'] . $_SESSION['IDEmpresa'] . "/fiscales/" . $empresa['logo'];
$razonSoc = $empresa['RazonSocial'];
$rfcEmpresa = $empresa['RFC'];
$domicilio = $empresa['domicilio_fiscal'];
$regimen = $empresa['descripcion'];

$folio = $_POST['folioyserie'];
$razon_social = $_POST['razon_social'];
$fecha = $_POST['fecha'];
$cfdi = $_POST['cfdi'];
$forma_pago = $_POST['forma_pago'];
$metodo_pago = $_POST['metodo_pago'];
$moneda = $_POST['moneda'];
$rfcCliente = $_POST['rfc'];
$productos = json_decode($_POST['productos']);
$subtotal = $_POST['subtotal'];
$impuestos = $_POST['impuestos'];
$total1 = $_POST['total'];
//<tr><td colspan="2">'.$_POST['telefono'].'</td></tr>
$notas_cliente = isset($_POST['notas_cliente']) ? '<tr><td colspan="2">'.$_POST['notas_cliente'].'</td></tr>' : "";
$direccion_envio =  isset($_POST['direccion_envio']) ? '<tr><td colspan="2">'.$_POST['direccion_envio'].'</td></tr>' : "";
$contacto =  isset($_POST['contacto']) ? '<tr><td colspan="2">'.$_POST['contacto'].'</td></tr>' : "";
$telefono =  isset($_POST['telefono']) ? '<tr><td colspan="2">'.$_POST['telefono'].'</td></tr>' : "";

class PDF extends TCPDF
{
  /**
   * Print chapter
   * @param $num (int) chapter number
   * @param $title (string) chapter title
   * @param $file (string) name of the file containing the chapter body
   * @param $mode (boolean) if true the chapter body is in HTML, otherwise in simple text.
   * @public
   */

  //Page header
  public function Header()
  {
    $generales = '
    <table style="width: 100%; font-family: Helvetica;">
      <tr>
        <td style="width: 30%;"><img src="' . $this->logo . '" width="50"></td>
        <td style="width: 50%;"></td>
        <td style="text-align: end; width:20%;">
          <table style="color: #000000; width:100%;">
            <tr>
              <td style="font-size: 11px; text-align: center; border-bottom: 2px solid #7abaff;">Folio</td>
            </tr>
            <tr>
              <td style="font-size: 14px; font-weight: bolder; background-color: #f5f5f5; text-align: center; vertical-align: bottom;">' . $this->folio . '</td>
            </tr>
          </table>
        </td>
      </tr>
    </table>';
    $this->writeHTML($generales, true, false, true, false, '');
  }

  public function PrintChapter($num, $title, $file)
  {
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
  public function ChapterTitle($num, $title)
  {
    $this->SetFont('helvetica', '', 14);
    $this->SetFillColor(200, 220, 255);
    $this->Cell(180, 6, 'Chapter ' . $num . ' : ' . $title, 0, 1, '', 1);

    $this->Ln(4);
  }

  /**
   * Print chapter body
   * @param $file (string) name of the file containing the chapter body
   * @param $mode (boolean) if true the chapter body is in HTML, otherwise in simple text.
   * @public
   */
  public function ChapterBody($file)
  {
    $this->selectColumn();
    // get esternal file content
    $content = $file; //file_get_contents($file, false);
    // set font
    $this->SetFont('times', '', 9);
    $this->SetTextColor(50, 50, 50);
    // print content
    $this->writeHTML($content, true, false, true, false, 'J');

    $this->Ln();
  }

  // Page footer
  public function Footer()
  {
    $this->SetY(-25);
    // Set font
    $this->SetFont('Helvetica', 'N', 12);

    $footer = '';
    $this->writeHTML($footer, true, false, true, false, '');
  }
}

$pdf = new PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Timlid');
$pdf->SetTitle('Prefactura');
$pdf->SetSubject('Prefactura');
$pdf->SetKeywords('Prefactura');
$pdf->logo = $logo;
$pdf->folio = $folio;
// set header and footer fonts
$pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

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
if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
  require_once(dirname(__FILE__) . '/lang/eng.php');
  $pdf->setLanguageArray($l);
}

// Set font
$pdf->SetFont('times', '', 12);

$pdf->AddPage();
$pdf->SetFont('Times', '', 12);
$total = 0;

error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

$html = '
<table style="width: 100%; font-family: Helvetica; font-size: 12px;">
  <tr>
    <td style="width: 100%;padding-top:20px;">
      <table style="color: #000000; width:100%;">
        <tr>
          <td>' . $razonSoc . '</td>
        </tr>
        <tr>
          <td>' . $domicilio . '</td>
        </tr>
        <tr>
          <td><span style="font-weight: bold;">RFC </span> ' . $rfcEmpresa . '</td>
        </tr>
        <tr>
          <td><span style="font-weight: bold;">Régimen F. </span> ' . $regimen . '</td>
        </tr>
      </table>
    </td>
  </tr>
</table>';
$pdf->writeHTML($html, true, false, true, false, '');

$html = '
<table style="width: 100%; font-family: Helvetica; font-size: 12px;">
  <tr>
    <td style="width: 100%;">
      <table style="color: #000000; width:100%;">
        <tr>
          <td style="font-size: 10px; font-weight: bold; border-bottom: 2px solid #7abaff;">Receptor</td>
        </tr>
        <tr>
          <td style="border: 1px solid #dfdfdf">
            <table>
              <tr>
                <td style="width: 30%; font-weight: bold;">Razón Social</td> <td style="width: 70%;">' . $razon_social . '</td>
              </tr>
              <tr>
                <td style="width: 30%; font-weight: bold;">RFC</td> <td style="width: 70%;">' . $rfcCliente . '</td>
              </tr>
              <tr>
                <td style="width: 30%; font-weight: bold;">Uso del CFDI</td> <td style="width: 70%;">' . $cfdi . '</td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>';
$pdf->writeHTML($html, true, false, true, false, '');

$clavesHTML = '';
$productosHTML = '';
$medidasHTML = '';
$cantidadesHTML = '';
$preciosHTML = '';
$impuestosHTML = '';
$totalesHTML = '';
foreach ($productos as $r) {
  $clavesHTML .= '
      <tr>
        <td>' . $r->clave . '</td>
      </tr>
  ';
  $productosHTML .= '
      <tr>
        <td>' . $r->producto . '</td>
      </tr>
  ';
  $medidasHTML .= '
      <tr>
        <td>' . $r->u_medida . '</td>
      </tr>
  ';
  $cantidadesHTML .= '
      <tr>
        <td>' . $r->cantidad . '</td>
      </tr>
  ';
  $preciosHTML .= '
      <tr>
        <td>' . $r->precio . '</td>
      </tr>
  ';
  $impuestosHTML .= '
      <tr>
        <td>' . $r->impuestos . '</td>
      </tr>
  ';
  $totalesHTML .= '
      <tr>
        <td>' . $r->total . '</td>
      </tr>
  ';
}
$html = '
<table style="width: 100%; font-family: Helvetica; font-size: 12px;">
  <tr>
    <td>
      <table style="color: #000000; width:100%;">
        <tr>
          <th style="width: 20%; font-size: 10px; font-weight: bold; border-bottom: 2px solid #7abaff">Clave</th>
          <th style="width: 30%; font-size: 10px; font-weight: bold; border-bottom: 2px solid #7abaff">Concepto</th>
          <th style="width: 20%; font-size: 10px; font-weight: bold; border-bottom: 2px solid #7abaff">U. Medida</th>
          <th style="width: 10%; font-size: 10px; font-weight: bold; border-bottom: 2px solid #7abaff">Cant.</th>
          <th style="width: 10%; font-size: 10px; font-weight: bold; border-bottom: 2px solid #7abaff">Precio</th>
          <th style="width: 10%; font-size: 10px; font-weight: bold; border-bottom: 2px solid #7abaff">Importe</th>
        </tr>
        <tr>
          <td style="width: 20%; font-size: 10px; border: 1px solid #dfdfdf;"><table>' .$clavesHTML . '</table></td>
          <td style="width: 30%; font-size: 10px; width: 30%; border: 1px solid #dfdfdf;"><table>' .$productosHTML . '</table></td>
          <td style="width: 20%; font-size: 10px; border: 1px solid #dfdfdf;"><table>' .$medidasHTML . '</table></td>
          <td style="width: 10%; font-size: 10px; border: 1px solid #dfdfdf;"><table>' .$cantidadesHTML . '</table></td>
          <td style="width: 10%; font-size: 10px; border: 1px solid #dfdfdf;"><table>' .$preciosHTML . '</table></td>
          <td style="width: 10%; font-size: 10px; border: 1px solid #dfdfdf;"><table>' .$totalesHTML . '</table></td>
        </tr>

      </table>
    </td>
  </tr>
</table>';
$pdf->writeHTML($html, true, false, true, false, '');

$html = '
<table style="color: #000000; width: 100%; font-family: Helvetica; font-size: 12px;">
  <tr>
    <td style="WIDTH: 70%;">
      <table style="width:100%;">
        <tr>
          <td>
            <table>
              <tr>
                <th style="font-weight: bold; font-size: 9px;">Forma de pago</th>
                <th style="width:55%; font-weight: bold; font-size: 9px;">Método de pago</th>
                <th style="font-weight: bold; font-size: 9px;">Moneda</th>
              </tr>
              <tr>
                <td style="font-size: 10px;">' . $forma_pago . ' </td>
                <td style="width:55%; font-size: 10px;">' .$metodo_pago . '</td>
                <td style="font-size: 10px;">' . $moneda . '</td>
              </tr>
              <br>  
              '.$notas_cliente.'
              '.$direccion_envio.'
              '.$contacto.'
              '.$telefono.'
            </table>
          </td>
        </tr>
      </table>
    </td>
    <td style="width: 30%;">
      <table>
        <tr>
          <td style="width: 50%; text-align: right;">Subtotal</td>
          <td style="width: 50%; text-align: right;">' . $subtotal . '</td>
        </tr>
        <tr>
          <td style="width: 50%; text-align: right;">Impuestos</td>
          <td style="width: 50%; text-align: right;">' . $impuestos . '</td>
        </tr>
        <tr>
          <td style="width: 50%; text-align: right;">Total</td>
          <td style="width: 50%; text-align: right;">' . $total1 . '</td>
        </tr>
      </table>
    </td>
  </tr>
</table>';
$pdf->writeHTML($html, true, false, true, false, '');

ob_end_clean();

$pdf->Output("Prefactura " . $folio . '.pdf', 'I');
