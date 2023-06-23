<?php
session_start();

$jwt_ruta = "../../../";
require_once '../../jwt.php';

require_once '../../../lib/TCPDF/tcpdf.php';
require_once '../../../include/db-conn.php';

$id = $_POST['idOrdenPedido'];
$FKUsuario = $_SESSION["PKUsuario"];
$token = $_POST["csr_token_8UY8N"];

if(empty($_SESSION['token_ld10d'])) {
    header('Location: ../detallePedido.php?id='.$id);
    exit;
}

if (!hash_equals($_SESSION['token_ld10d'], $token)) {
    header('Location: ../detallePedido.php?id='.$id);
    exit;
}

$stmt = $conn->prepare('SELECT ops.id_orden_pedido_empresa, 
                               so.sucursal as sucursal_origen, 
                               sd.sucursal as sucursal_destino, 
                               c.razon_social as cliente, 
                               DATE_FORMAT(ops.fecha_captura, "%d/%m/%Y %H:%i:%s") as fecha_ingreso, 
                               ops.observaciones, 
                               cot.id_cotizacion_empresa as numero_cotizacion, 
                               vd.Referencia as numero_venta_directa, 
                               ops.empresa_id, ops.tipo_pedido,
                               decl.Calle as CalleE,
                               decl.Numero_exterior as NumExtE,
                               decl.Numero_Interior as NumIntE,
                               decl.Colonia as ColoniaE,
                               decl.Municipio as MunicipioE,
                               efE.Estado as EstadoE,
                               psE.Pais as PaisE,
                               ifnull(decl.PKDireccionEnvioCliente,0) as isNulo,
                               declC.Calle as CalleEC,
                               declC.Numero_exterior as NumExtEC,
                               declC.Numero_Interior as NumIntEC,
                               declC.Colonia as ColoniaEC,
                               declC.Municipio as MunicipioEC,
                               efEC.Estado as EstadoEC,
                               psEC.Pais as PaisEC,
                               ifnull(declC.PKDireccionEnvioCliente,0) as isNuloC,
                               ifnull(cot.PKCotizacion,0) as PKCotizacion,
                               ifnull(vd.PKVentaDirecta,0) as PKVentaDirecta
                        FROM orden_pedido_por_sucursales as ops 
                              LEFT JOIN sucursales as so ON so.id = ops.sucursal_origen_id 
                              LEFT JOIN sucursales as sd ON sd.id = ops.sucursal_destino_id 
                              LEFT JOIN clientes as c ON c.PKCliente = ops.cliente_id 
                              LEFT JOIN cotizacion as cot ON cot.PKCotizacion = ops.numero_cotizacion 
                              LEFT JOIN ventas_directas as vd ON vd.PKVentaDirecta = ops.numero_venta_directa
                              LEFT JOIN direcciones_envio_cliente decl on vd.direccion_entrega_id = decl.PKDireccionEnvioCliente
                              LEFT JOIN paises psE on decl.Pais = psE.PKPais
                              LEFT JOIN estados_federativos efE on decl.Estado = efE.PKEstado  
                              LEFT JOIN direcciones_envio_cliente declC on cot.direccion_entrega_id = declC.PKDireccionEnvioCliente
                              LEFT JOIN paises psEC on declC.Pais = psEC.PKPais
                              LEFT JOIN estados_federativos efEC on declC.Estado = efEC.PKEstado
                        WHERE ops.empresa_id = '.$_SESSION['IDEmpresa'] . ' AND ops.id = :id');
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$row = $stmt->fetch();
$empresa_id = $row['empresa_id'];

if($empresa_id != $_SESSION['IDEmpresa']){
  header("location:../detallePedido.php?id=".$id);
}

$GLOBALS["no_pedido"] = sprintf("%011d", $row['id_orden_pedido_empresa']);
$GLOBALS["FechaIngreso"] = $row['fecha_ingreso'];
$GLOBALS["Observaciones"] = $row['observaciones'];
$GLOBALS["Cliente"] = $row['cliente'];
$GLOBALS["sucursal_origen"] = $row['sucursal_origen'];
$GLOBALS["sucursal_destino"] = $row['sucursal_destino'];
$GLOBALS['numero_cotizacion'] = sprintf("%011d", $row['numero_cotizacion']);
$GLOBALS['numero_venta_directa'] = $row['numero_venta_directa'];
$GLOBALS['tipo_pedido'] = $row['tipo_pedido'];
if ($row['PKCotizacion'] != 0){
  if ($row['isNuloC'] == '0'){
    $GLOBALS["DireccionE"] = 'S/N';
  }else{
    $GLOBALS["DireccionE"] = $row['CalleEC'].' '.$row['NumExtEC'].' Int.'.$row['NumIntEC'].', '.$row['ColoniaEC'].', '.$row['MunicipioEC'].', '.$row['EstadoEC'].', '.$row['PaisEC'];
  }
}else if ($row['PKVentaDirecta'] != 0){
  if ($row['isNulo'] == '0'){
    $GLOBALS["DireccionE"] = 'S/N';
  }else{
    $GLOBALS["DireccionE"] = $row['CalleE'].' '.$row['NumExtE'].' Int.'.$row['NumIntE'].', '.$row['ColoniaE'].', '.$row['MunicipioE'].', '.$row['EstadoE'].', '.$row['PaisE'];
  }
}else{
  $GLOBALS["DireccionE"] = 'S/N';
}

$PKEmpresa = $_SESSION["IDEmpresa"];
$ruta = $_ENV['RUTA_ARCHIVOS_READ'].$PKEmpresa.'/fiscales'.'/';

//Logo de la empresa que emite la OC
$stmtLogo = $conn->prepare("SELECT logo FROM empresas WHERE PKEmpresa = :empresa");
$stmtLogo->bindValue(':empresa', $PKEmpresa, PDO::PARAM_INT);
$stmtLogo->execute();
$rowLogo = $stmtLogo->fetch();

$GLOBALS["ruta"] = $ruta;
$GLOBALS["logotipo"] = $rowLogo['logo'];

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
        $this->Cell(0, 0, '', 0, 1, 'C', 0, '', 0);
        $this->SetFont('Helvetica', '', 18);
        $this->Cell(30, 10, 'Pedido: ' . $GLOBALS["no_pedido"], 0);
        $image_file = $GLOBALS["ruta"].$GLOBALS["logotipo"];
        $this->Image($image_file, 150, 9, 45, 15, '', '', 'T', false, 300, 'R', false, false, 0, false, false, false);

        /* $this->SetFont('Helvetica', '', 12);
    $this->Cell(30, 10, "Expedición: " . $GLOBALS["FechaIngreso"], 0, false, 'C', 0, '', 0, false, 'M', 'M'); */
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
        $this->SetFont('Helvetica', '', 14);
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
        $this->SetFont('Helvetica', '', 9);
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

        $footer = '';/*'
          <table>
            <tr>
              <th width="50%" style="font-size: 12px;"><b>Contacto:</b> ' . $GLOBALS['Vendedor'] . '</th>
              <th width="50%" style="font-size: 12px;"><b>Teléfono:</b> ' . $GLOBALS['Telefono'] . '</th>
            </tr>
            <tr>
              <th width="50%" style="font-size: 12px;"><b>Email:</b> ' . $GLOBALS['Email'] . '</th>
            </tr>
          </table>';
        $this->writeHTML($footer, true, false, true, false, '');*/

        // Page number
        //$this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }

}

$pdf = new PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Timlid');
$pdf->SetTitle('Pedido');
$pdf->SetSubject('Pedido');
$pdf->SetKeywords('Pedido');
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
$pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
    require_once dirname(__FILE__) . '/lang/eng.php';
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------
//$pdf->setFontSubsetting(true);

// Set font
$pdf->SetFont('Helvetica', '', 12);

$pdf->AddPage();
$pdf->SetFont('Helvetica', '', 12);
$total = 0;

error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

$tblenc = '
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
    vertical-align: center;
    text-align: center;
    line-height: 20px;
    border: 1px solid #ffffff;
    border-collapse: collapse;
  }
</style>
<table>
  <tr>
    <th width="20%">Fecha de expedición</th>';

if($GLOBALS['tipo_pedido'] == 1)
  $tablac = "80%";
else
  $tablac = "40%";

  $tblenc .= '<th width="'.$tablac.'" style="background-color: none;"></th>';

if($GLOBALS['tipo_pedido'] == 2)
  $tblenc .= '<th width="40%">Cliente</th>';

if($GLOBALS['tipo_pedido'] == 3)
  $tblenc .= '<th width="40%">Numero cotización</th>';

if($GLOBALS['tipo_pedido'] == 4)
  $tblenc .= '<th width="40%">Numero venta</th>';

$tblenc .= '
  </tr>
  <tr>
    <td width="20%">' . $GLOBALS['FechaIngreso'] . '</td>
    <td width="'.$tablac.'" style="background-color: none;"></td>';

    if($GLOBALS['tipo_pedido'] == 2){
      $tblenc .= '
        <td width="40%">'.$GLOBALS['Cliente'].'</td>';
    }
    if($GLOBALS['tipo_pedido'] == 3){
      $tblenc .= '
        <td width="40%">'.$GLOBALS['numero_cotizacion'].'</td>';
    }
    if($GLOBALS['tipo_pedido'] == 4){
      $tblenc .= '
        <td width="40%">'.$GLOBALS['numero_venta_directa'].'</td>';
    }

  $tblenc .= '
  </tr>
</table>';
$pdf->writeHTML($tblenc, true, false, true, false, '');

/*
if($GLOBALS['tipo_pedido'] == 1){
      //INICIO SUCURSALES
      $tblsuc = '
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
          vertical-align: center;
          text-align: center;
          line-height: 20px;
          border: 1px solid #ffffff;
          border-collapse: collapse;
        }
      </style>
      <table>
        <tr>
          <th width="45%">Sucursal origen</th>
          <th width="10%" style="background-color: none;"></th>';
          
          if(trim($GLOBALS['Cliente']) != ""){
            $tblsuc = '<th width="45%">Cliente</th>';
          }
          if(trim($GLOBALS['sucursal_destino']) != ""){
            $tblsuc = '<th width="45%">Sucursal destino</th>';
          }

        $tblsuc = '
        </tr>
        <tr>
          <td width="45%">' . $GLOBALS['sucursal_origen'] . '</td>
          <td width="10%" style="background-color: none;"></td>';

          if(trim($GLOBALS['Cliente']) != ""){
            $tblsuc = '<td width="45%">' . $GLOBALS['Cliente'] . '</td>';
          }
          if(trim($GLOBALS['sucursal_destino']) != ""){
            $tblsuc = '<td width="45%">' . $GLOBALS['sucursal_destino'] . '</td>';
          }

      $tblsuc = '
        </tr>
      </table>';
      $pdf->writeHTML($tblsuc, true, false, true, false, '');
      //FIN SUCURSALES
}
*/
if($GLOBALS['tipo_pedido'] == 1){
  //INICIO SUCURSAL
  $tblsuc = '
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
      vertical-align: center;
      text-align: center;
      line-height: 20px;
      border: 1px solid #ffffff;
      border-collapse: collapse;
    }
  </style>
  <table>
    <tr>
      <th width="45%">Sucursal origen</th>
      <th width="10%" style="background-color: none;"></th>
      <th width="45%">Sucursal destino</th>
    </tr>
    <tr>
      <td width="45%">'. $GLOBALS['sucursal_origen'] . '</td>
      <td width="10%" style="background-color: none;"></td>
      <td width="45%">' . $GLOBALS['sucursal_destino'] . '</td>
    </tr>
  </table>';
  $pdf->writeHTML($tblsuc, true, false, true, false, '');
  //FIN SUCURSALES
}

if($GLOBALS['tipo_pedido'] == 3 || $GLOBALS['tipo_pedido'] == 4){
  //INICIO SUCURSAL
  $tblsuc = '
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
      vertical-align: center;
      text-align: center;
      line-height: 20px;
      border: 1px solid #ffffff;
      border-collapse: collapse;
    }
  </style>
  <table>
    <tr>
      <th width="45%">Sucursal origen</th>
      <th width="10%" style="background-color: none;"></th>
      <th width="45%">Cliente</th>
    </tr>
    <tr>
      <td width="45%">'. $GLOBALS['sucursal_origen'] . '</td>
      <td width="10%" style="background-color: none;"></td>
      <td width="45%">' . $GLOBALS['Cliente'] . '</td>
    </tr>
  </table>';
  $pdf->writeHTML($tblsuc, true, false, true, false, '');
  //FIN SUCURSALES
}

$domicilioEntrega = '
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
    <th width="30%">Domicilio de entrega:</th>
    <td width="70%">' . $GLOBALS['DireccionE'] . '</td>
  </tr>
</table>';
$pdf->writeHTML($domicilioEntrega, true, false, true, false, '');
/*
//INICIO NUMERO DE COTIZACION O DE VENTA
if($GLOBALS['numero_cotizacion'] != "" || $GLOBALS['numero_venta_directa'] != ""){
  $tblnum = '
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
      vertical-align: center;
      text-align: center;
      line-height: 20px;
      border: 1px solid #ffffff;
      border-collapse: collapse;
    }
  </style>
  <table>
    <tr>';
      if($GLOBALS['numero_cotizacion'] != ""){
          $tblnum = '
              <th width="20%">ID Cotizacion</th>
              <td width="80%">' . $GLOBALS['numero_cotizacion'] . '</td>';
      }
      if($GLOBALS['numero_venta_directa'] != ""){
          $tblnum = '
              <th width="20%">ID Venta</th>
              <td width="80%">' . $GLOBALS['numero_venta_directa'] . '</td>';
      }

  $tblnum = '
    </tr>
  </table>';
  $pdf->writeHTML($tblnum, true, false, true, false, '');
}
//FIN NUMERO DE COTIZACION O DE VENTA
*/

$tbl = '
<style>
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
    vertical-align: center;
    text-align: center;
    line-height: 20px;
    border: 1px solid #ffffff;
    border-collapse: collapse;
  }
</style>
  <table>
    <thead>
      <tr class="headers">
        <th width="10%">Clave</th>
        <th width="30%">Producto</th>
        <th width="10%">Unidad medida</th>
        <th width="10%">Cantidad pedida</th>     
        <th width="10%">Cantidad surtida</th> 
        <th width="10%">Lote</th> 
        <th width="10%">Serie</th> 
        <th width="10%">Caducidad</th>      
      </tr>
    </thead>
    <tbody>
';

$stmt = $conn->prepare('SELECT p.PKProducto, p.Nombre, p.ClaveInterna, dop.cantidad_pedida, csu.Descripcion FROM detalle_orden_pedido_por_sucursales as dop INNER JOIN productos as p ON p.PKProducto = dop.producto_id LEFT JOIN info_fiscal_productos as ifp ON ifp.FKProducto = p.PKProducto LEFT JOIN claves_sat_unidades as csu ON csu.PKClaveSATUnidad = ifp.FKClaveSATUnidad WHERE dop.orden_pedido_id = :id');
$stmt->execute(array(':id' => $id));
$numero_productos = $stmt->rowCount();
$rowp = $stmt->fetchAll();

$x = 0;

foreach ($rowp as $rp) {

    if($rp['Descripcion'] == ""){
      $ClaveUnidad = "Sin Clave";
    }
    else{
      $ClaveUnidad = $rp['Descripcion'];
    }

    $tbl .= '<tr>
              <td width="10%">' . $rp['ClaveInterna'] . '</td>
              <td width="30%">' . $rp['Nombre'] . '</td>
              <td width="10%">' . $ClaveUnidad .'</td>
              <td width="10%">' . $rp['cantidad_pedida'] . '</td>
              <td width="10%"></td>
              <td width="10%"></td>
              <td width="10%"></td>
              <td width="10%"></td>
          </tr>';
}

$tbl .= '</table>';

$pdf->writeHTML($tbl, true, false, true, false, '');

$tblNotas = '
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
    vertical-align: center;
    text-align: center;
    line-height: 20px;
    border: 1px solid #ffffff;
    border-collapse: collapse;
  }
</style>
<table>
  <tr>
    <th width="100%" style="text-align: left;">Observaciones</th>
  </tr>
  <tr>
    <td width="100%">' . $GLOBALS["Observaciones"] . '<br><br><br></td>
  </tr>
</table>';
$pdf->writeHTML($tblNotas, true, false, true, false, '');

$tblSurtidor = '
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
    vertical-align: center;
    text-align: center;
    line-height: 20px;
    border: 1px solid #ffffff;
    border-collapse: collapse;
  }
</style>
<table>
  <tr>
    <th width="100%" style="text-align: left;">Surtidor</th>
  </tr>
  <tr>
    <td width="100%"><br><br><br></td>
  </tr>
</table>';
$pdf->writeHTML($tblSurtidor, true, false, true, false, '');

ob_end_clean();
$pdf->Output('pedido_' . $GLOBALS["no_pedido"] . '.pdf', 'D');