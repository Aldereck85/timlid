<?php
session_start();
require_once('../../../../../lib/TCPDF/tcpdf.php');
require_once('../../../../../include/db-conn.php');
require_once('../../../../../lib/phpmailer_configuration.php');

if(isset($_REQUEST["data"])){
    $id_cambio = $_REQUEST['data'];
}else{
    $id_cambio = 0;
}

$stmtSpanish = $conn->prepare("SET lc_time_names = 'es_MX';");
$stmtSpanish->execute();

$PKEmpresa = $_SESSION["IDEmpresa"];
$ruta = $_ENV['RUTA_ARCHIVOS_READ'].$PKEmpresa.'/fiscales'.'/';

$stmtLogo = $conn->prepare("SELECT logo FROM empresas WHERE PKEmpresa = :empresa");
$stmtLogo->bindValue(':empresa', $PKEmpresa, PDO::PARAM_INT);
$stmtLogo->execute();
$rowLogo = $stmtLogo->fetch();

$GLOBALS["ruta"] = $ruta;
$GLOBALS["logotipo"] = $rowLogo['logo'];

//Datos generales del cambio
$cambio = $conn->prepare("SELECT
    cls.id, 
    s.sucursal, 
    DATE_FORMAT(cls.fecha_captura, '%d de %M de %Y a las %h:%i:%s %p') as fecha_captura, 
    u.Nombre, 
    cls.folio,
    IF (cls.tipo_cambio = 1, 'Positivo', 'Negativo') as tipo_cambio
    FROM
    cambio_lote_serie cls
    INNER JOIN
    sucursales s
    ON
    cls.sucursal_id = s.id
    INNER JOIN
    usuarios u
    ON
    cls.usuario_creo_id = u.id
    WHERE
    cls.id = :id_cambio1
    ;");
$cambio->bindValue(':id_cambio1', $id_cambio, PDO::PARAM_INT);
$cambio->execute();
$rowCambio = $cambio->fetch();

$stmtEnglish = $conn->prepare("SET lc_time_names = 'en_US';");
$stmtEnglish->execute();

  $GLOBALS["IdCambio"] = $rowCambio['id'];
  $GLOBALS["Sucursal"] = $rowCambio['sucursal'];
  $GLOBALS["FechaCaptura"] = $rowCambio['fecha_captura'];
  $GLOBALS["NombreUsuario"] = $rowCambio['Nombre'];
  $GLOBALS["Folio"] = $rowCambio['folio'];
  $GLOBALS["TipoCambio"] = $rowCambio['tipo_cambio'];

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
      $this->Cell(0, 0, '', 0, 1, 'C', 0, '', 0);
      $this->SetFont('Helvetica', '', 18);
      $this->Cell(30, 10, 'Cambio de lote', 0);
      $image_file = $GLOBALS["ruta"].$GLOBALS["logotipo"];
      $this->Image($image_file, 150, 9, 45, 15, '', '', 'T', false, 300, 'R', false, false, 0, false, false, false);

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
        $this->SetFont('Helvetica', 'N', 12);
    }

}

$pdf = new PDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor($GLOBALS["NombreUsuario"]);
$pdf->SetTitle('Cambio de lote');
$pdf->SetSubject('Cambio de lote');
$pdf->SetKeywords('Cambio de lote');
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

$datosCambio = '
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
    font-size: 10px;
    font-weight: bold;
    padding: 8px;
    background-color: #b2b2b2;
    color: #ffffff;
    text-align: center;
    height: 25px;
    line-height: 20px;
    border: 1px solid #ffffff;
    border-collapse: collapse;
  }
  td {
    font-size: 9px;
    padding: 4px;
    background-color: #f0f0f0;
    color: #000000;
    vertical-align: middle;
    text-align: center;
    line-height: 20px;
    border: 1px solid #ffffff;
    border-collapse: collapse;
  }
</style>
<table>
  <tr>
    <th width="5%" style="background-color: none;"></th>
    <th width="20%">Sucursal</th>
    <th width="10%" style="background-color: none;"></th>
    <th width="30%">Fecha de captura</th>
    <th width="10%" style="background-color: none;"></th>
    <th width="20%">Usuario</th>
    <th width="5%" style="background-color: none;"></th>
  </tr>
  <tr>
    <td width="5%" style="background-color: none;"></td>
    <td width="20%">' . $GLOBALS['Sucursal'] . '</td>
    <td width="10%" style="background-color: none;"></td>
    <td width="30%">' . $GLOBALS['FechaCaptura'] . '</td>
    <td width="10%" style="background-color: none;"></td>
    <td width="20%">' . $GLOBALS['NombreUsuario'] . '</td>
    <td width="5%" style="background-color: none;"></td>
  </tr>
</table>';
$pdf->writeHTML($datosCambio, true, false, true, false, '');


$datosCambio2 = '
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
    font-size: 10px;
    font-weight: bold;
    padding: 8px;
    background-color: #b2b2b2;
    color: #ffffff;
    text-align: center;
    height: 25px;
    line-height: 20px;
    border: 1px solid #ffffff;
    border-collapse: collapse;
  }
  td {
    font-size: 9px;
    padding: 4px;
    background-color: #f0f0f0;
    color: #000000;
    vertical-align: middle;
    text-align: center;
    line-height: 20px;
    border: 1px solid #ffffff;
    border-collapse: collapse;
  }
</style>
<table>
  <tr>
    <th width="20%">Folio de cambio:</th>
    <td width="20%">' . $GLOBALS['Folio'] . '</td>
    <th width="20%" style="background-color: none;"></th>
    <th width="20%">Tipo de cambio:</th>
    <td width="20%">' . $GLOBALS['TipoCambio'] . '</td>
    </tr>
</table>';
$pdf->writeHTML($datosCambio2, true, false, true, false, '');

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
    font-size: 10px;
    font-weight: bold;
    padding: 8px;
    background-color: #b2b2b2;
    color: #ffffff;
    text-align: center;
    height: 25px;
    line-height: 20px;
    border: 1px solid #ffffff;
    border-collapse: collapse;
  }
  td {
    font-size: 9px;
    padding: 4px;
    background-color: #f0f0f0;
    color: #000000;
    vertical-align: middle;
    text-align: center;
    line-height: 20px;
    border: 1px solid #ffffff;
    border-collapse: collapse;
  }
</style>
  <table>
    <thead>
      <tr class="headers">
        <th width="11%">Clave</th>
        <th width="21%">Nombre</th>
        <th width="11%">Cantidad</th>
        <!--<th width="10%">Serie</th>-->
        <th width="11%">Lote</th>
        <!--<th width="10%">Serie nueva</th>-->
        <th width="11%">Lote nuevo</th>
        <th width="11%">Caducidad</th>
        <th width="24%">Comentarios</th>
      </tr>
    </thead>
    <tbody>
';

$tipo_cambio = $conn->prepare("SELECT
  tipo_cambio
  FROM
  cambio_lote_serie
  WHERE
  id = :id_cambio1
  ;");
$tipo_cambio->bindValue(':id_cambio1', $id_cambio, PDO::PARAM_INT);
$tipo_cambio->execute();
$rowTipoCambio = $tipo_cambio->fetch();

$GLOBALS["TipoCambio"] = $rowTipoCambio['tipo_cambio'];

//Datos del detalle del cambio
$cambio = $conn->prepare("SELECT 
  dcls.id,
  dcls.clave,
  p.Nombre AS nombre,
  dcls.cantidad,
  dcls.lote_antiguo,
  dcls.serie_antigua,
  dcls.lote_nuevo,
  dcls.serie_nueva,
  IF(dcls.caducidad = '0000-00-00',
      '',
      dcls.caducidad) AS caducidad,
  dcls.observaciones
  FROM
  detalle_cambio_lote_serie dcls
      INNER JOIN
  productos p ON dcls.clave = p.ClaveInterna AND p.empresa_id = :id_empresa
  WHERE
  cambio_lote_serie_id = :id_cambio
    ;");
$cambio->execute(array(':id_cambio'=>$id_cambio, ':id_empresa'=>$_SESSION['IDEmpresa']));
$rowCambioDetalle = $cambio->fetchAll();

//<td width="10%">'.$r['serie_antigua'].'</td> 386
//<td width="10%">'.$r['serie_nueva'].'</td>
foreach ($rowCambioDetalle as $r) {
  $tbl.=  '<tr>
          <td width="11%">'.$r['clave'].'</td>
          <td width="21%">'.$r['nombre'].'</td>
          <td width="11%">'.$r['cantidad'].'</td>
          
          <td width="11%">'.$r['lote_antiguo'].'</td>
          
          <td width="11%">'.$r['lote_nuevo'].'</td>
          <td width="11%">'.$r['caducidad'].'</td>
          <td width="24%">'.$r['observaciones'].'</td>
        </tr>';    
}

$tbl.= '</table>';


$pdf->writeHTML($tbl, true, false, true, false, '');

ob_end_clean();

$pdf->Output("Cambio de lote ".$GLOBALS["Folio"]. '.pdf', 'D');
return true;
?>
