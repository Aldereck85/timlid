<?php
session_start();
require_once('../../../../../lib/TCPDF/tcpdf.php');
require_once('../../../../../include/db-conn.php');
require_once('../../../../../lib/phpmailer_configuration.php');

$id = $_REQUEST['idRequisicion'];

$FKUsuario = $_SESSION["PKUsuario"];
$Usuario = $_SESSION["Usuario"];
$PKEmpresa = $_SESSION["IDEmpresa"];
$ruta = $_ENV['RUTA_ARCHIVOS_READ'].$PKEmpresa.'/fiscales'.'/';

//Logo de la empresa que emite la RC
$stmtLogo = $conn->prepare("SELECT logo FROM empresas WHERE PKEmpresa = :empresa");
$stmtLogo->bindValue(':empresa', $PKEmpresa, PDO::PARAM_INT);
$stmtLogo->execute();
$rowLogo = $stmtLogo->fetch();

$GLOBALS["ruta"] = $ruta;
$GLOBALS["logotipo"] = $rowLogo['logo'];

//Datos generales de la requisicion de compra
$stmt = $conn->prepare("SELECT rc.folio,
                          DATE_FORMAT(rc.fecha_registro, '%d/%m/%Y') as FechaCreacion,
                          DATE_FORMAT(rc.fecha_estimada_entrega, '%d/%m/%Y') as FechaEstimada,
                          s.Sucursal,
                          a.nombre as area,
                          s.Calle,
                          s.numero_exterior as NumExt,
                          s.Prefijo,
                          s.numero_interior as NumInt,
                          s.Colonia,
                          s.Municipio,
                          ef.Estado,
                          ps.Pais,
                          s.Telefono,
                          em.RazonSocial as razon_social,
                          em.RFC as rfcCliente,
                          em.telefono as telefonoCliente,
                          rc.notas_comprador as notas,
                          concat(emOP.Nombres,' ',emOP.PrimerApellido,' ',emOP.SegundoApellido) as comprador, 
                          concat(emSo.Nombres,' ',emSo.PrimerApellido,' ',emSo.SegundoApellido) as Empleado, 
                          emOP.email as emailComprador,
                          emOP.Telefono as telefonoComprador,
                          emOP.RFC as rfcComprador
                        FROM requisiciones_compra rc
                          inner join sucursales s on rc.FKSucursal = s.id
                          inner join areaDepartamento a on rc.area = a.id
                          inner join paises ps on s.pais_id = ps.PKPais
                          inner join estados_federativos ef on s.estado_id = ef.PKEstado
                          inner join empresas em on rc.empresa_id = em.PKEmpresa
                          left join empleados emOP on rc.comprador = emOP.PKEmpleado
                          inner join empleados emSo on rc.aplicado_por = emSo.PKEmpleado
                        where rc.PKRequisicion = :idRequisicion;");
$stmt->bindValue(':idRequisicion', $id, PDO::PARAM_INT);
$stmt->execute();
$row = $stmt->fetch();

  $GLOBALS["folio"] = $row['folio'];
  $GLOBALS["FechaIngreso"] = $row['FechaCreacion'];
  $GLOBALS["FechaEstimada"] = $row['FechaEstimada'];
  $GLOBALS["Sucursal"] = $row['Sucursal'];
  $GLOBALS["area"] = $row['area'];
  $GLOBALS["Direccion"] = $row['Sucursal']. ' - ' .$row['Calle'].' '.$row['NumExt'].' Int.'.$row['NumInt'].'- '.$row['Prefijo'].', '.$row['Colonia'].', '.$row['Municipio'].', '.$row['Estado'].', '.$row['Pais'];
  $GLOBALS["NombreCliente"] = $row['razon_social'];
  $GLOBALS["RFCCliente"] = $row['rfcCliente'];
  $GLOBALS["TelefonoCliente"] = $row['telefonoCliente'];
  $GLOBALS["Telefono"] = $row['Telefono'];
  $GLOBALS["NotaComprador"] = $row['notas'];
  $GLOBALS["Comprador"] = $row['comprador'];
  $GLOBALS["Empleado"] = $row['Empleado'];
  $GLOBALS["EmailComprador"] = $row['emailComprador'];
  $GLOBALS["TelefonoComprador"] = $row['telefonoComprador'];
  $GLOBALS["RFCComprador"] = $row['rfcComprador'];

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
      // Logo
      $image_file = $GLOBALS["ruta"].$GLOBALS["logotipo"];
      $this->Image($image_file, 10, 10, 45, 15, '', '', 'T', false, 300, 'L', false, false, 0, false, false, false);
      // Set font
      $this->SetFont('Helvetica', 'B', 20);
      // Title
      // set cell padding
      $this->setCellPaddings(1, 1, 1, 1);
      // set cell margins
      $this->setCellMargins(1, 1, 1, 1);
      // MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0)
      $this->MultiCell(0, 80, 'Requisición de compra', 0, 'R', 0, 0, 100, 6, true);

      $this->SetFont('Helvetica', 'N', 15);
      // Title
      // set cell padding
      $this->setCellPaddings(1, 1, 1, 1);
      // set cell margins
      $this->setCellMargins(1, 1, 1, 1);
      $this->MultiCell(70, 5, '#'.$GLOBALS["folio"], 0, 'R', 0, 1, 125, 14, true);

       // Set font
       $this->SetFont('Helvetica', 'N', 9);
       // Title
       // set cell padding
       $this->setCellPaddings(1, 1, 1, 1);
       // set cell margins
       $this->setCellMargins(1, 1, 1, 1);
       $this->MultiCell(70, 5, 'Fecha: '.$GLOBALS["FechaIngreso"], 0, 'R', 0, 1, 125, 20, true);
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
    public function Footer()
    {
      $this->SetY(-25);
      // Set font
      $this->SetFont('Helvetica', 'I', 8);

      $this->Cell(0, 5, ''.$this->getAliasNumPage().' de '.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');  
    }

}

$pdf = new PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('TIMLID');
$pdf->SetTitle('Requisición de compra');
$pdf->SetSubject('Requisición de compra');
$pdf->SetKeywords('Requisición de compra');
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
//$pdf->setFontSubsetting(true);

// Set font
$pdf->SetFont('times', '', 12);

$pdf->AddPage();
$pdf->SetFont('Times','',12);
$total = 0;

error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

$fechaSolicitud = '
<style>
  table {
    font-family: Helvetica;
    font-size: 12px;
    margin: 45px;
    width: 480px;
    text-align: left;
    border: none;
    width: 100%;
    border: 1px solid #ffffff;
    border-collapse: collapse;
  }
  th {
    font-size: 14px;
    font-weight: bold;
    padding: 8px;
    background-color: #b2b2b2;
    color: #000000;
    text-align: left;
    height: 25px;
    line-height: 20px;
    border: 1px solid #ffffff;
    border-collapse: collapse;
  }
  td {
    font-size: 9px;
    padding: 2px;
    background-color: #f0f0f0;
    color: #000000;
    vertical-align: middle;
    text-align: left;
    line-height: 10px;
    border: 1px solid #ffffff;
    border-collapse: collapse;
  }
  .td3 {
    background-color: #ffffff;
  }
  .td4 {
    font-weight: bold;
  }
  .td5 {
    font-size: 28px;
  }
  .td6 {
    display:inline-block;
    text-align: left;
    border: none;
    vertical-align:middle;
  }
  .td7 {
    background-color: #f0f0f0;
  }
</style>
<table>
  <tr>
    <th width="100%">INFORMACIÓN DEL SOLICITANTE</th>
  </tr>
  <tr>
    <td class="td3 td4" width="25%">Nombre</td>
    <td class="td3" width="75%">' . $GLOBALS["Empleado"] . '</td>
  </tr>
  <tr>
    <td class="td3 td4" width="25%">Sucursal de entrega</td>
    <td class="td3" width="75%">'.$GLOBALS["Sucursal"].'</td>
  </tr>
  <tr>
    <td class="td3 td4" width="25%">Area/Departamento</td>
    <td class="td3" width="75%">' . $GLOBALS["area"] . '</td>
  </tr>
  <tr>
    <td class="td3 td4" width="25%">Comprador sugerido</td>
    <td class="td3" width="75%">' . $GLOBALS["Comprador"] . '</td>
  </tr>
  <tr>
    <td class="td4 td6" width="25%">Fecha solicitada de entrega </td>
    <td class="td4 td6" width="75%">'.$GLOBALS["FechaEstimada"].'</td>
  </tr>
  <tr>
    <td class="td3 td4" width="25%" rowspan="2"></td>
    <td class="td3" width="75%" rowspan="2"></td>
  </tr>
</table>';
$pdf->writeHTML($fechaSolicitud, true, false, true, false, '');

$tbl = '
<style>
  table {
    font-family: Helvetica;
    font-size: 12px;
    margin: 45px;
    width: 480px;
    text-align: left;
    border: none;
    width: 100%;
    border: 1px solid #ffffff;
    border-collapse: collapse;
  }
  th {
    font-size: 11px;
    font-weight: bold;
    padding: 8px;
    background-color: #b2b2b2;
    color: #000000;
    text-align: center;
    height: 25px;
    line-height: 15px;
    border: 1px solid #000000;
    border-collapse: collapse;
  }
  td {
    font-size: 9px;
    padding: 4px;
    background-color: #ffffff;
    color: #000000;
    vertical-align: middle;
    text-align: center;
    line-height: 15px;
    border-bottom: 1px solid #b2b2b2;
    border-top: 1px solid #b2b2b2;
    border-collapse: collapse;
  }
  .td4 {
    font-weight: bold;
  }
  .td6 {
    border: none;
    border-bottom: none;
    border-top: none;
  }
  .td7 {
    text-align: left;
  }
</style>
  <table>
    <thead>
      <tr class="headers">
        <th width="10%">Piezas</th>
        <th width="17%">Clave</th>
        <th width="46%">Nombre</th>
        <th width="27%">Unidad de Medida</th>
      </tr>
    </thead>
    <tbody>
';

//recuperacion de los productos
$stmtp = $conn->prepare('call spc_Requisicion_productosDetalle(?,?)');
$stmtp->execute(array($id,$PKEmpresa));
$rowp = $stmtp->fetchAll();

foreach ($rowp as $rp) { 

  $tbl.=  '<tr>
            <td width="10%">'.$rp['cantidad'].'</td>
            <td width="17%">'.$rp['claveProd'].'</td>
            <td width="46%">'.$rp['nombreProd'].'</td>
            <td width="27%">'.$rp['unidad'].'</td>
          </tr>';
}

$tbl.= '</table>';

$pdf->writeHTML($tbl, true, false, true, false, '');

$direccionEntrega = '<p style="font-size: 11px; font-weight: bold;">Dirección de entrega:</p>
<p style="font-size: 11px; font-weight: normal;">'.$GLOBALS["Direccion"].'<p>';
$pdf->writeHTML($direccionEntrega, true, false, true, false, '');

$notasCliente = '<p style="font-size: 9px; font-weight: bold;">Notas:</p>
<p style="font-size: 9px; font-weight: normal;">'.$GLOBALS["NotaComprador"].'<p>';
$pdf->writeHTML($notasCliente, true, false, true, false, '');

$firmas = '
<style>
  table {
    font-family: Helvetica;
    font-size: 12px;
    margin: 45px;
    width: 480px;
    text-align: center;
    border: none;
    width: 100%;
    border-collapse: collapse;
  }
  td {
    font-size: 9px;
    padding: 2px;
    color: #000000;
    vertical-align: middle;
    text-align: center;
    line-height: 10px;
    border-collapse: collapse;
  }
</style>
<br><br><br><br>
<table>
  <tr>
    <td class="td3" width="50%">__________________________________</td>
    <td class="td3" width="50%">__________________________________</td>
  </tr>
  <tr>
    <td class="td3" width="50%">'.$GLOBALS["Empleado"].'</td>
    <td class="td3" width="50%"> Autorizó</td>
  </tr>
</table>';
$pdf->writeHTML($firmas, true, false, true, false, '');

ob_end_clean();

$pdf->Output("Requisicion de Compra ".$GLOBALS["folio"]. '.pdf', 'D');

?>
