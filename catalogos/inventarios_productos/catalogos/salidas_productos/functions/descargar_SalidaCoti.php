<?php
session_start();
require_once('../../../../../lib/TCPDF/tcpdf.php');
require_once('../../../../../include/db-conn.php');
require_once('../../../../../lib/phpmailer_configuration.php');

$folio = $_GET['folio'];
$orden = $_GET['orden'];

$PKuser = $_SESSION["PKUsuario"];
$PKEmpresa = $_SESSION["IDEmpresa"];
$ruta = $_ENV['RUTA_ARCHIVOS_READ'].$PKEmpresa.'/fiscales'.'/';

//Logo de la empresa que emite la OC
$stmtLogo = $conn->prepare("SELECT logo FROM empresas WHERE PKEmpresa = :empresa");
$stmtLogo->bindValue(':empresa', $PKEmpresa, PDO::PARAM_INT);
$stmtLogo->execute();
$rowLogo = $stmtLogo->fetch();

$GLOBALS["ruta"] = $ruta;
$GLOBALS["logotipo"] = $rowLogo['logo'];

$stmtSpanish = $conn->prepare("SET lc_time_names = 'es_MX';");
//$stmtSpanish->bindValue(':folio1', $folio, PDO::PARAM_INT);
$stmtSpanish->execute();

//Datos generales de la entrada por orden de compra
$stmt = $conn->prepare("SELECT distinct ispp.folio_salida as folioSalida,
                          DATE_FORMAT(ispp.fecha_salida, '%d/%m/%Y %h:%i:%s %p') as fechaSalida,
                          DATE_FORMAT(opps.fecha_captura, '%d/%m/%Y %h:%i:%s %p') as fechaCrecionPedido,
                          so.sucursal as sucursalOrigen,
                          em.RazonSocial as empresaRazonSocial,
                          opps.id_orden_pedido_empresa as folioOrdenPedido,
                          concat(e.Nombres,' ',e.PrimerApellido,' ',e.SegundoApellido) as surtidor,
                          concat(eOP.Nombres,' ',eOP.PrimerApellido,' ',eOP.SegundoApellido) as creador,
                          ispp.observaciones as notas,
                          decl.Calle as CalleE,
                          decl.Numero_exterior as NumExtE,
                          decl.Numero_Interior as NumIntE,
                          decl.Colonia as ColoniaE,
                          decl.Municipio as MunicipioE,
                          efE.Estado as EstadoE,
                          psE.Pais as PaisE,
                          ifnull(decl.PKDireccionEnvioCliente,0) as isNulo,
                          cl.NombreComercial,
                          cl.razon_social as razonSocialCliente,
                          cl.rfc as rfcCliente,
                          cl.Telefono as telefonoCliente,
                          cl.Email as emailCliente,
                          c.id_cotizacion_empresa as numeroCotizacion,
                          c.NotaCliente as NotasCliente,
                          opps.estatus_factura_id,
                          ifnull(concat(fct.serie,'-',fct.folio),'') as numeroFacturacion
                          from inventario_salida_por_sucursales ispp
                          inner join orden_pedido_por_sucursales opps on ispp.orden_pedido_id = opps.id
                          inner join clientes cl on opps.cliente_id = cl.PKCliente 
                          inner join sucursales so on opps.sucursal_origen_id = so.id
                          inner join empresas em on so.empresa_id = em.PKEmpresa 
                          inner join empleados e on ispp.surtidor_id = e.PKEmpleado
                          inner join cotizacion c on opps.numero_cotizacion = c.PKCotizacion
                          inner join empleados eOP on ispp.usuario_creo_id = eOP.PKEmpleado
                          left join direcciones_envio_cliente decl on c.direccion_entrega_id = decl.PKDireccionEnvioCliente
                          left join paises psE on decl.Pais = psE.PKPais
                          left join estados_federativos efE on decl.Estado = efE.PKEstado
                          left join facturacion fct on c.PKCotizacion = fct.referencia and fct.tipo = 1
                          where ispp.folio_salida = :folio1 and so.empresa_id = :empresa

                          union 

                          SELECT distinct msssi.FKSalida as folioSalida,
                          DATE_FORMAT(msssi.fecha, '%d/%m/%Y %h:%i:%s %p') as fechaSalida,
                          DATE_FORMAT(opps.fecha_captura, '%d/%m/%Y %h:%i:%s %p') as fechaCrecionPedido,
                          so.sucursal as sucursalOrigen,
                          em.RazonSocial as empresaRazonSocial,
                          opps.id_orden_pedido_empresa as folioOrdenPedido,
                          concat(e.Nombres,' ',e.PrimerApellido,' ',e.SegundoApellido) as surtidor,
                          concat(eOP.Nombres,' ',eOP.PrimerApellido,' ',eOP.SegundoApellido) as creador,
                          msssi.observaciones as notas,
                          decl.Calle as CalleE,
                          decl.Numero_exterior as NumExtE,
                          decl.Numero_Interior as NumIntE,
                          decl.Colonia as ColoniaE,
                          decl.Municipio as MunicipioE,
                          efE.Estado as EstadoE,
                          psE.Pais as PaisE,
                          ifnull(decl.PKDireccionEnvioCliente,0) as isNulo,
                          cl.NombreComercial,
                          cl.razon_social as razonSocialCliente,
                          cl.rfc as rfcCliente,
                          cl.Telefono as telefonoCliente,
                          cl.Email as emailCliente,
                          c.id_cotizacion_empresa as numeroCotizacion,
                          c.NotaCliente as NotasCliente,
                          opps.estatus_factura_id,
                          ifnull(concat(fct.serie,'-',fct.folio),'') as numeroFacturacion
                          from movimientos_salidas_servicios_sin_inventario msssi
                          inner join orden_pedido_por_sucursales opps on msssi.FKOrdenPedido = opps.id
                          inner join clientes cl on opps.cliente_id = cl.PKCliente 
                          inner join sucursales so on opps.sucursal_origen_id = so.id
                          inner join empresas em on so.empresa_id = em.PKEmpresa 
                          inner join empleados e on msssi.surtidor_id = e.PKEmpleado
                          inner join cotizacion c on opps.numero_cotizacion = c.PKCotizacion
                          inner join empleados eOP on msssi.usuario_creo_id = eOP.PKEmpleado
                          left join direcciones_envio_cliente decl on c.direccion_entrega_id = decl.PKDireccionEnvioCliente
                          left join paises psE on decl.Pais = psE.PKPais
                          left join estados_federativos efE on decl.Estado = efE.PKEstado
                          left join facturacion fct on c.PKCotizacion = fct.referencia and fct.tipo = 1
                          where msssi.FKSalida = :folio2 and so.empresa_id = :empresa2;");
$stmt->bindValue(':folio1', $folio, PDO::PARAM_INT);
$stmt->bindValue(':folio2', $folio, PDO::PARAM_INT);
$stmt->bindValue(':empresa', $PKEmpresa, PDO::PARAM_INT);
$stmt->bindValue(':empresa2', $PKEmpresa, PDO::PARAM_INT);
$stmt->execute();
$row = $stmt->fetch();

$stmtEnglish = $conn->prepare("SET lc_time_names = 'en_US';");
//$stmtEnglish->bindValue(':folio1', $folio, PDO::PARAM_INT);
$stmtEnglish->execute();

//Datos generales de la entrada por orden de compra
$stmtUs = $conn->prepare("SELECT concat(e.Nombres,' ',e.PrimerApellido,' ',e.SegundoApellido) as encargado,
                            us.usuario as email,
                            e.Telefono as telefono
                          from usuarios us
                            left join empleados_usuarios eu on us.id = eu.FKUsuario
                            left join empleados e on eu.FKEmpleado = e.PKEmpleado
                          where us.id = :user
                          ;");
$stmtUs->bindValue(':user', $PKuser, PDO::PARAM_INT);
$stmtUs->execute();
$rowUs = $stmtUs->fetch();

  $GLOBALS["FolioSalida"] = $row['folioSalida'];
  $GLOBALS["FolioOrden"] = $row['folioOrdenPedido'];
  $GLOBALS["FechaSalida"] = $row['fechaSalida'];
  $GLOBALS["FechaCrecionPedido"] = $row['fechaCrecionPedido'];
  $GLOBALS["SucursalOrigen"] = $row['sucursalOrigen'];
  $GLOBALS["Empresa"] = $row['empresaRazonSocial'];
  $GLOBALS["creador"] = $row['creador'];
  $GLOBALS["Surtidor"] = $row['surtidor'];
  $GLOBALS["Notas"] = $row['notas'];
  //$GLOBALS["FolioSalida"] = $row['folio_salida'];
  if ($row['isNulo'] == '0'){
    $GLOBALS["DireccionE"] = '';
  }else{
    $GLOBALS["DireccionE"] = $row['CalleE'].' '.$row['NumExtE'].' Int.'.$row['NumIntE'].', '.$row['ColoniaE'].', '.$row['MunicipioE'].', '.$row['EstadoE'].', '.$row['PaisE'];
  }
  $GLOBALS["RazonSocialCliente"] = $row['razonSocialCliente'];
  $GLOBALS["NotasCliente"] = $row['NotasCliente'];
  $GLOBALS["NumeroCotizacion"] = $row['numeroCotizacion'];
  if ($row['estatus_factura_id'] == '1'){
    $GLOBALS["Estatus_factura_id"] = '#'.$row['numeroFacturacion'];
  }else if ($row['estatus_factura_id'] == '2'){
    $GLOBALS["Estatus_factura_id"] = '#'.$row['numeroFacturacion'];
  }else if ($row['estatus_factura_id'] == '3'){
    $GLOBALS["Estatus_factura_id"] = 'Pendiente de facturar';
  }else if ($row['estatus_factura_id'] == '4'){
    $GLOBALS["Estatus_factura_id"] = 'Pendiente de facturar directo';
  }else if ($row['estatus_factura_id'] == '5'){
    $GLOBALS["Estatus_factura_id"] = '#'.$row['numeroFacturacion'];
  }else if ($row['estatus_factura_id'] == '6'){
    $GLOBALS["Estatus_factura_id"] = 'Cancelado';
  }else if ($row['estatus_factura_id'] == '7'){
    $GLOBALS["Estatus_factura_id"] = '#'.$row['numeroFacturacion'];
  }else if ($row['estatus_factura_id'] == '8'){
    $GLOBALS["Estatus_factura_id"] = '#'.$row['numeroFacturacion'];
  }else if ($row['estatus_factura_id'] == '9'){
    $GLOBALS["Estatus_factura_id"] = '#'.$row['numeroFacturacion'];
  }else if ($row['estatus_factura_id'] == '10'){
    $GLOBALS["Estatus_factura_id"] = '#'.$row['numeroFacturacion'];
  }else{
    $GLOBALS["Estatus_factura_id"] = 'Pendiente de Facturar';
  }

  $GLOBALS["EncargadoUs"] = $rowUs['encargado'];
  $GLOBALS["EmailUs"] = $rowUs['email'];
  $GLOBALS["TelefonoUs"] = $rowUs['telefono'];
  
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
       $this->Image($image_file, 10, 10, 45, 15, '', '', 'T', false, 300, '', false, false, 0, false, false, false);
       // Set font
       $this->SetFont('Helvetica', 'B', 20);
       // Title
       // set cell padding
       $this->setCellPaddings(1, 1, 1, 1);
       // set cell margins
       $this->setCellMargins(1, 1, 1, 1);
       // MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0)
       $this->MultiCell(0, 30, 'Salida de pedido', 0, 'C', 0, 0, 0, 10, true);
 
       // Set font
       $this->SetFont('Helvetica', 'B', 9);
       // Title
       // set cell padding
       $this->setCellPaddings(1, 1, 1, 1);
       // set cell margins
       $this->setCellMargins(1, 1, 1, 1);
       $this->MultiCell(70, 5, 'Número de pedido', 1, 'C', 0, 1, 210, 10, true);
       $this->MultiCell(70, 5, '#'.$GLOBALS["FolioSalida"], 1, 'C', 0, 1, 210, 16, true); 

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

$pdf = new PDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor($GLOBALS["Empresa"]);
$pdf->SetTitle('Salida de pedido');
$pdf->SetSubject('Salida de pedido');
$pdf->SetKeywords('Salida de pedido');
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

$fechaSalida = '
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
    font-size: 12px;
    padding: 2px;
    background-color: #f0f0f0;
    color: #000000;
    vertical-align: middle;
    text-align: left;
    line-height: 20px;
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
    text-align: right;
    border: none;
    vertical-align:middle;
  }
  .td7 {
    border: 1px solid #000000;
    border-collapse: collapse;
  }
  .td8 {
    border-bottom: 1px solid #000000;
  }
  .td9 {
    border-top: 1px solid #000000;
  }
  .td10 {
    border-right: 1px solid #000000;
  }
  .td11 {
    border-left: 1px solid #000000;
  }
</style>
<table>
  <tr>
    <td class="td3 td4" width="15%">Cliente: </td>
    <td class="td3" width="35%">' . $GLOBALS["RazonSocialCliente"] . '</td>
    <td class="td3 td4" width="5%" rowspan="4"></td>
    <td class="td3 td4" width="20%">Monto Total del Pedido: </td>
    <td class="td3 td8" width="20%"></td>
  </tr>
  <tr>
    <td class="td3 td4" width="15%">Domicilio de entrega: </td>
    <td class="td3" width="40%">' . $GLOBALS["DireccionE"] . '</td>
    <td class="td3 td4 td10" width="20%">Surtido por: </td>
    <td class="td3 td7 td8 td9" width="25%">' . $GLOBALS["Surtidor"] . '</td>
  </tr>
  <tr>
    <td class="td3 td4" width="15%">Sucursal de origen: </td>
    <td class="td3" width="35%">' . $GLOBALS["SucursalOrigen"] . '</td>
  </tr>
  <tr>
    <td class="td3 td4" width="15%">Capturado por: </td>
    <td class="td3" width="25%">' . $GLOBALS["creador"] . '</td>
    <td class="td3 td4" width="5%">el día </td>
    <td class="td3" width="25%">' . $GLOBALS["FechaSalida"]  . '</td>
  </tr>
  <tr>
    <td class="td3 td4" width="15%">Cotización: </td>
    <td class="td3" width="40%">' . $GLOBALS["NumeroCotizacion"] . '</td>
    <td class="td3 td4" width="20%">Factura: </td>
    <td class="td3" width="25%">' . $GLOBALS["Estatus_factura_id"] . '</td>
  </tr>
</table>';
$pdf->writeHTML($fechaSalida, true, false, true, false, '');

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
        <th width="15%">Clave</th>
        <th width="30%">Nombre</th>
        <th width="9%">Piezas</th>
        <th width="26%">U. medida</th>
        <th width="10%">Lote</th>
        <!--<th width="10%">No. Serie</th>-->
        <th width="10%">Caducidad</th>
      </tr>
    </thead>
    <tbody>
';

$stmtp = $conn->prepare("SELECT p.PKProducto as id,
                          p.Nombre as nombre,
                          p.ClaveInterna  as clave,
                          csu.Descripcion as unidadMedida,
                          epp.numero_lote as lote, 
                          /*epp.numero_serie as serie,*/
                          if(epp.caducidad = '0000-00-00' or epp.caducidad = null,'', DATE_FORMAT(epp.caducidad, '%d/%m/%Y'))  as fechaCaducidad,
                          isps.cantidad as cantidad
                          from inventario_salida_por_sucursales isps
                          inner join existencia_por_productos epp on ifnull(isps.numero_lote,'') = ifnull(epp.numero_lote,'') and/* ifnull(isps.numero_serie,'') = ifnull(epp.numero_serie,'') and*/ isps.clave = epp.clave_producto
                          inner join productos p on isps.clave = p.ClaveInterna  and p.empresa_id = :empresa
                          left join info_fiscal_productos ifp on p.PKProducto = ifp.FKProducto
                          left join claves_sat_unidades csu on ifp.FKClaveSATUnidad = csu.PKClaveSATUnidad
                          inner join orden_pedido_por_sucursales opps on isps.orden_pedido_id = opps.id and epp.sucursal_id = opps.sucursal_origen_id
                          inner join sucursales so on opps.sucursal_origen_id = so.id
                          where isps.folio_salida = :folio and so.empresa_id = :empresa1

                          union 

                          SELECT p.PKProducto as id,
                          p.Nombre as nombre,
                          p.ClaveInterna  as clave,
                          csu.Descripcion as unidadMedida,
                          '' as lote, 
                          /*'' as serie,*/
                          '' as fechaCaducidad,
                          msssi.cantidad_surtida as cantidad
                          from movimientos_salidas_servicios_sin_inventario msssi
                          inner join productos p on msssi.FKProducto = p.PKProducto  and p.empresa_id = :empresa2
                          left join info_fiscal_productos ifp on p.PKProducto = ifp.FKProducto
                          left join claves_sat_unidades csu on ifp.FKClaveSATUnidad = csu.PKClaveSATUnidad
                          inner join orden_pedido_por_sucursales opps on msssi.FKOrdenPedido = opps.id
                          inner join sucursales so on opps.sucursal_origen_id = so.id
                          where msssi.FKSalida = :folio2 and so.empresa_id = :empresa3
                          group by p.PKProducto, lote/* serie*/
                          ;");
$stmtp->execute(array(':folio'=>$folio, ':empresa'=>$PKEmpresa, ':folio2'=>$folio, ':empresa1'=>$PKEmpresa, ':empresa2'=>$PKEmpresa, ':empresa3'=>$PKEmpresa));
$rowp = $stmtp->fetchAll();

foreach ($rowp as $rp) { 

  $tbl.=  '<tr>
            <td width="15%">'.$rp['clave'].'</td>
            <td width="30%">'.$rp['nombre'].'</td>
            <td width="9%">'.$rp['cantidad'].'</td>
            <td width="26%">'.$rp['unidadMedida'].'</td>
            <td width="10%">'.$rp['lote'].'</td>
            <!--<td width="10%">'.$rp['serie'].'</td>-->
            <td width="10%">'.$rp['fechaCaducidad'].'</td>
          </tr>';
}

$tbl.= '</table>';
$pdf->writeHTML($tbl, true, false, true, false, '');

$notasCliente = '<p style="font-size: 10px; font-weight: bold;">Notas:</p>
<p style="font-size: 10px; font-weight: normal;">'.$GLOBALS["NotasCliente"].'<p>';
$pdf->writeHTML($notasCliente, true, false, true, false, '');

$pdf->write1DBarcode($GLOBALS["FolioSalida"], 'C39', '', '', '', 18, 0.8, $style, 'N');
$pdf->writeHTML($GLOBALS["FolioSalida"], true, false, true, false, '');

$pdf->ln(4);

$observaciones = '<label style="font-size: 10px; font-weight: bold;">Observaciones: </label><label style="font-size: 10px;">'.$GLOBALS["Notas"].'</label>';
$pdf->writeHTML($observaciones, true, false, true, false, '');

$total = '
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
    font-size: 12px;
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
    font-size: 14px;
  }
  .td6 {
    display:inline-block;
    text-align: right;
    border: none;
    vertical-align:middle;
  }
</style>
<table>
  <tr>
    <th width="15%">TOTAL</th>';

$stmtc = $conn->prepare("SELECT sum(cantidad) as cantidad from(
                            SELECT p.PKProducto as id,
                                  p.Nombre as nombre,
                                  p.ClaveInterna  as clave,
                                  csu.Descripcion as unidadMedida,
                                  epp.numero_lote as lote, 
                                  /*epp.numero_serie as serie,*/
                                  if(epp.caducidad = '0000-00-00' or epp.caducidad = null,'', DATE_FORMAT(epp.caducidad, '%d/%m/%Y'))  as fechaCaducidad,
                                  isps.cantidad as cantidad
                            from inventario_salida_por_sucursales isps
                                inner join existencia_por_productos epp on ifnull(isps.numero_lote,'') = ifnull(epp.numero_lote,'') and /*ifnull(isps.numero_serie,'') = ifnull(epp.numero_serie,'') and*/ isps.clave = epp.clave_producto
                                inner join productos p on isps.clave = p.ClaveInterna and p.empresa_id = :empresa
                                left join info_fiscal_productos ifp on p.PKProducto = ifp.FKProducto
                                left join claves_sat_unidades csu on ifp.FKClaveSATUnidad = csu.PKClaveSATUnidad
                                inner join orden_pedido_por_sucursales opps on isps.orden_pedido_id = opps.id and epp.sucursal_id = opps.sucursal_origen_id
                                inner join sucursales so on opps.sucursal_origen_id = so.id
                            where isps.folio_salida = :folio and so.empresa_id = :empresa1
                            group by p.PKProducto, epp.numero_lote/*, epp.numero_serie*/


                            union
        
                            SELECT p.PKProducto as id,
                                  p.Nombre as nombre,
                                  p.ClaveInterna  as clave,
                                  csu.Descripcion as unidadMedida,
                                  '' as lote, 
                                  /*'' as serie,*/
                                  ''  as fechaCaducidad,
                                  msssi.cantidad_surtida as cantidad
                            from movimientos_salidas_servicios_sin_inventario msssi
                                inner join productos p on msssi.FKProducto = p.PKProducto and p.empresa_id = :empresa2
                                left join info_fiscal_productos ifp on p.PKProducto = ifp.FKProducto
                                left join claves_sat_unidades csu on ifp.FKClaveSATUnidad = csu.PKClaveSATUnidad
                                inner join orden_pedido_por_sucursales opps on msssi.FKOrdenPedido = opps.id
                                inner join sucursales so on opps.sucursal_origen_id = so.id                          
                            where msssi.FKSalida = :folio2 and so.empresa_id = :empresa3
                          ) as total
                          ;");
$stmtc->execute(array(':folio'=>$folio, ':empresa'=>$PKEmpresa, ':folio2'=>$folio, ':empresa1'=>$PKEmpresa, ':empresa2'=>$PKEmpresa, ':empresa3'=>$PKEmpresa));
$rowc = $stmtc->fetchAll();

foreach ($rowc as $rc) { 

  $total .= '<td class="td4 td5 td6" width="15%"> <br>'.$rc['cantidad'].'</td>
          </tr>
        </table>';
}

$pdf->writeHTML($total, true, false, true, false, '');

$pdf->ln(4);

$firmas = '
<style>
  table {
    font-family: Helvetica;
    font-size: 12px;
    margin: 45px;
    width: 480px;
    text-align: left;
    border: none;
    width: 100%;
  }
  th {
    font-size: 10px;
    font-weight: bold;
    padding: 8px;
    text-align: center;
    height: 25px;
    line-height: 20px;
    border: none;
  }
  td {
    font-size: 9px;
    padding: 4px;
    vertical-align: middle;
    text-align: center;
    line-height: 20px;
    border: none;
  }
</style>
<br>
<br>
<br>
<table>
  <tr>
    <th width="30%">_______________________________________</th>
    <th width="40%" style="background-color: none;"></th>
    <th width="30%">_______________________________________</th>
  </tr>
  <tr>
    <td width="30%">Nombre y Firma de quien Entregó</td>
    <td width="40%" style="background-color: none;"></td>
    <td width="30%">Nombre y Firma de quien Recibió</td>
  </tr>
</table>';
$pdf->writeHTML($firmas, true, false, true, false, '');

ob_end_clean();

$pdf->Output("Salida de pedido ".$GLOBALS["FolioSalida"]. '.pdf', 'D');
return true;
?>
