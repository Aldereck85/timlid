<?php
session_start();
require_once('../../../../../lib/TCPDF/tcpdf.php');
require_once('../../../../../include/db-conn.php');
require_once('../../../../../lib/phpmailer_configuration.php');

$id = $_GET['txtId'];

$FKUsuario = $_SESSION["PKUsuario"];
$Usuario = $_SESSION["Usuario"];
$PKEmpresa = $_SESSION["IDEmpresa"];
$ruta = $_ENV['RUTA_ARCHIVOS_READ'].$PKEmpresa.'/fiscales'.'/';

//Logo de la empresa que emite la OC
$stmtLogo = $conn->prepare("SELECT logo FROM empresas WHERE PKEmpresa = :empresa");
$stmtLogo->bindValue(':empresa', $PKEmpresa, PDO::PARAM_INT);
$stmtLogo->execute();
$rowLogo = $stmtLogo->fetch();

$GLOBALS["ruta"] = $ruta;
$GLOBALS["logotipo"] = $rowLogo['logo'];

$stmtProveedor = $conn->prepare("SELECT pv.PKProveedor 
                                FROM ordenes_compra oc 
                                  inner join proveedores pv on oc.FKProveedor = pv.PKProveedor 
                                where oc.PKOrdenCompra = (select PKOrdenCompra from ordenes_compra where id_encriptado = :id);");
$stmtProveedor->bindValue(':id', $id, PDO::PARAM_INT);
$stmtProveedor->execute();
$rowProveedor = $stmtProveedor->fetch();

$GLOBALS["PKPrveedor"] = $rowProveedor['PKProveedor'];

//Datos generales de la orden de compra
$stmt = $conn->prepare("SELECT oc.Referencia,
                               pv.NombreComercial,
                               oc.Importe,
                               DATE_FORMAT(oc.created_at, '%d/%m/%Y') as FechaCreacion,
                               DATE_FORMAT(oc.FechaEstimada, '%d/%m/%Y') as FechaEstimada,
                               s.Sucursal,
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
                               @_subtotal := (select ifnull(sum(doc.Cantidad * doc.Precio),0) as subtotal
                                              from detalle_orden_compra doc
                                              where doc.FKOrdenCompra = (select PKOrdenCompra from ordenes_compra where id_encriptado = :id1)) as Subtotal,
                               @_impuestos := (select ifnull(sum((doc.Cantidad * doc.Precio) * (ip.Tasa / 100)),0) as totalImpuesto
                                              from detalle_orden_compra doc
                                                inner join productos p on doc.FKProducto = p.PKProducto  
                                                inner join info_fiscal_productos ifp on p.PKProducto = ifp.FKProducto
                                                inner join impuestos_productos ip on ifp.PKInfoFiscalProducto = ip.FKInfoFiscalProducto
                                                inner join impuesto i on ip.FKImpuesto = i.PKImpuesto
                                                inner join tipos_impuestos ti on i.FKTipoImpuesto = ti.PKTipoImpuesto
                                              where doc.FKOrdenCompra = (select PKOrdenCompra from ordenes_compra where id_encriptado = :id2)) as Impuestos,
                               (@_subtotal+@_impuestos) as Total, 
                               oc.NotasProveedor as notas,
                               dfp2.RFC as rfcProveedor,
                               dfp2.Razon_Social as razonSocialPV,
                               pv.Telefono as telefonoPv,
                               pv.Email as emailPV,
                               pv.vendedor as vendedor,
                               concat(emOP.Nombres,' ',emOP.PrimerApellido,' ',emOP.SegundoApellido) as comprador, 
                               emOP.email as emailComprador,
                               emOP.Telefono as telefonoComprador,
                               emOP.RFC as rfcComprador,
                               ifnull(oc.condicion_Pago,0) as condicionPago,
                               timo.TipoMoneda
                          FROM ordenes_compra oc
                                inner join proveedores pv on oc.FKProveedor = pv.PKProveedor
                                left join (select dfp.FKProveedor, dfp.RFC, dfp.Razon_Social from domicilio_fiscal_proveedor dfp where dfp.FKProveedor = ".$GLOBALS["PKPrveedor"]." limit 1) dfp2 on dfp2.FKProveedor = oc.FKProveedor
	                              inner join sucursales s on oc.FKSucursal = s.id
                                inner join paises ps on s.pais_id = ps.PKPais
                                inner join estados_federativos ef on s.estado_id = ef.PKEstado
                                inner join empresas em on oc.empresa_id = em.PKEmpresa
                                left join empleados emOP on oc.comprador_id = emOP.PKEmpleado
                                left join tipo_moneda timo on oc.moneda=timo.PKTipoMoneda
                          where oc.PKOrdenCompra = (select PKOrdenCompra from ordenes_compra where id_encriptado = :id5);");
$stmt->bindValue(':id1', $id, PDO::PARAM_INT);
$stmt->bindValue(':id2', $id, PDO::PARAM_INT);
$stmt->bindValue(':id5', $id, PDO::PARAM_INT);
$stmt->execute();
$row = $stmt->fetch();

  $GLOBALS["referencia"] = $row['Referencia'];
  $GLOBALS["Subtotal"] = number_format($row['Subtotal'],2,'.',','); 
  $GLOBALS["ImporteTotal"] = number_format($row['Importe'],2,'.',',');
  $GLOBALS["FechaIngreso"] = $row['FechaCreacion'];
  $GLOBALS["FechaEstimada"] = $row['FechaEstimada'];
  $GLOBALS["Sucursal"] = $row['Sucursal'];
  $GLOBALS["Direccion"] = $row['Sucursal']. ' - ' .$row['Calle'].' '.$row['NumExt'].' Int.'.$row['NumInt'].'- '.$row['Prefijo'].', '.$row['Colonia'].', '.$row['Municipio'].', '.$row['Estado'].', '.$row['Pais'];
  $GLOBALS["NombreCliente"] = $row['razon_social'];
  $GLOBALS["RFCCliente"] = $row['rfcCliente'];
  $GLOBALS["TelefonoCliente"] = $row['telefonoCliente'];
  $GLOBALS["RFCProveedor"] = $row['rfcProveedor'];
  $GLOBALS["RazonSocialPV"] = $row['razonSocialPV'];
  $GLOBALS["TelefonoPv"] = $row['telefonoPv'];
  $GLOBALS["EmailPV"] = $row['emailPV'];
  $GLOBALS["NombreProveedor"] = $row['NombreComercial'];
  $GLOBALS["vendedor"] = $row['vendedor'];
  $GLOBALS["Telefono"] = $row['Telefono'];
  $GLOBALS["NotaComprador"] = $row['notas'];
  $GLOBALS["Comprador"] = $row['comprador'];
  $GLOBALS["EmailComprador"] = $row['emailComprador'];
  $GLOBALS["TelefonoComprador"] = $row['telefonoComprador'];
  $GLOBALS["RFCComprador"] = $row['rfcComprador'];
  $GLOBALS["Moneda"] = $row['TipoMoneda'];
  if ($row['condicionPago'] == '1'){
    $GLOBALS["CondicionPago"] = 'Contado';
  }else if ($row['condicionPago'] == '2'){
    $GLOBALS["CondicionPago"] = 'Crédito';
  }else{
    $GLOBALS["CondicionPago"] = 'Sin especificar';
  }

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
      $this->MultiCell(0, 30, 'Orden de compra', 0, 'R', 0, 0, 125, 6, true);

      $this->SetFont('Helvetica', 'N', 15);
      // Title
      // set cell padding
      $this->setCellPaddings(1, 1, 1, 1);
      // set cell margins
      $this->setCellMargins(1, 1, 1, 1);
      $this->MultiCell(70, 5, '#'.$GLOBALS["referencia"], 0, 'R', 0, 1, 125, 14, true);

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
    <th width="50%">INFORMACIÓN DEL PROVEEDOR</th>
    <th width="50%">INFORMACIÓN DEL COMPRADOR</th>
  </tr>
  <tr>
    <td class="td3 td4" width="25%">Nombre</td>
    <td class="td3" width="25%">' . $GLOBALS["NombreProveedor"] . '</td>
    <td class="td7 td4 td6" width="25%">Razón social</td>
    <td class="td7 td6" width="25%">' . $GLOBALS["NombreCliente"] . '</td>
  </tr>
  <tr>
    <td class="td3 td4" width="25%">Atención</td>
    <td class="td3" width="25%">'.$GLOBALS["vendedor"].'</td>
    <td class="td7 td4 td6" width="25%">Comprador</td>
    <td class="td7 td6" width="25%">'.$GLOBALS["Comprador"].'</td>
  </tr>
  <tr>
    <td class="td3 td4" width="25%">RFC</td>
    <td class="td3" width="25%">' . $GLOBALS["RFCProveedor"] . '</td>
    <td class="td7 td4 td6" width="25%">Condición Pago</td>
    <td class="td7 td6" width="25%">'.$GLOBALS["CondicionPago"].'</td>
  </tr>
  <tr>
    <td class="td3 td4" width="25%">Teléfono</td>
    <td class="td3" width="25%">' . $GLOBALS["TelefonoPv"] . '</td>
    <td class="td7 td4 td6" width="25%">RFC</td>
    <td class="td7 td6" width="25%">' . $GLOBALS["RFCCliente"] . '</td>
  </tr>
  <tr>
    <td class="td3 td4" width="25%">email</td>
    <td class="td3" width="25%"><u>' . $GLOBALS["EmailPV"] . '</u></td>
    <td class="td7 td4 td6" width="25%">Teléfono</td>
    <td class="td7 td6" width="25%">' . $GLOBALS["TelefonoComprador"] . '</td>
  </tr>
  <tr>
    <td class="td3 td4" width="25%">Razón Social</td>
    <td class="td3" width="25%">' . $GLOBALS["RazonSocialPV"] . '</td>
    <td class="td7 td4 td6" width="25%">email</td>
    <td class="td7 td6" width="25%"><u>'. $GLOBALS["EmailComprador"] .'</u></td>
  </tr>
  <tr>
    <td class="td3 td4" width="25%"></td>
    <td class="td3" width="25%"></td>
    <td class="td4 td6" width="25%">Fecha solicitada de entrega </td>
    <td class="td4 td6" width="25%">'.$GLOBALS["FechaEstimada"].'</td>
  </tr>
  <tr>
    <td class="td3 td4" width="25%" rowspan="2"></td>
    <td class="td3" width="25%" rowspan="2"></td>
    <td class="td4 td6" width="25%"> </td>
    <td class="td4 td6" width="25%"></td>
  </tr>
  <tr>
    <td class="td6" width="50%"></td>
  </tr>
</table>';
$pdf->writeHTML($fechaSolicitud, true, false, true, false, '');

/*$contacto = '
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
    border: 1px solid #000000;
    border-collapse: collapse;
  }
  td {
    font-size: 9px;
    padding: 4px;
    background-color: #f0f0f0;
    color: #000000;
    vertical-align: middle;
    text-align: left;
    line-height: 15px;
    border: 1px solid #000000;
    border-collapse: collapse;
  }
  .td3 {
    background-color: #ffffff;
  }
  .td4 {
    font-weight: bold;
  }
  .td6 {
    border: none;
  }
</style>
<table>
  <tr>
    <th width="100%">INFORMACIÓN DE COMPRA</th>
  </tr>
  <tr>
    <td class="td3 td4" width="27.5%">Comprador</td>
    <td class="td3 td4" width="27.5%">Correo</td>
    <td class="td3 td4" width="15%">Teléfono</td>
    <td class="td3 td4" width="15%">Moneda</td>
    <td class="td3 td4" width="15%">Tipo de Cambio</td>
  </tr>
  <tr>
    <td class="td3 td6" width="27.5%">'. $GLOBALS['Comprador'] .'</td>
    <td class="td3 td6" width="27.5%"><u>'. $GLOBALS["EmailComprador"] .'</u></td>
    <td class="td3 td6" width="15%">'. $GLOBALS["TelefonoComprador"] .'</td>
    <td class="td3 td6" width="15%"></td>
    <td class="td3 td6" width="15%"></td>
  </tr>
</table>';
$pdf->writeHTML($contacto, true, false, true, false, '');*/

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
        <th width="9%">Piezas</th>
        <th width="10%">Clave</th>
        <th width="39%">Nombre</th>
        <th width="14%">Precio</th>
        <th width="16%">Unidad de Medida</th>
        <th width="12%">Importe</th>
      </tr>
    </thead>
    <tbody>
';

$stmtp = $conn->prepare("SELECT id, producto, nombre, clave, cantidad, unidadMedida, precio, importe, GROUP_CONCAT(impuestos SEPARATOR ' / ') as impuestos  from (
  select oc.PKOrdenCompra as id,
       doc.FKProducto as producto,
       dpp.NombreProducto as nombre,
       dpp.Clave as clave,
       doc.Cantidad as cantidad,
       case dpp.UnidadMedida
				when null then csu.Descripcion
        when '' then csu.Descripcion
        else dpp.UnidadMedida end as unidadMedida,			   
			 doc.Precio as precio,
       (doc.Cantidad * doc.Precio) as importe,
       ifnull(if(i.FKTipoImporte = 2,(concat(i.Nombre,' - ',ioc.Tasa)),(concat(i.Nombre,' ',ioc.Tasa, '%' ))),'') as impuestos
  from ordenes_compra oc
    inner join detalle_orden_compra doc on oc.PKOrdenCompra = doc.FKOrdenCompra
    inner join datos_producto_proveedores dpp on doc.FKProducto = dpp.FKProducto and (select FKProveedor from ordenes_compra where id_encriptado = :idOrdenCompra1) = dpp.FKProveedor
    inner join productos p on doc.FKProducto = p.PKProducto  
    left join info_fiscal_productos ifp on p.PKProducto = ifp.FKProducto
    left join impuestos_orden_compra ioc on oc.PKOrdenCompra = ioc.FKOrdenCompra and doc.FKProducto = ioc.FKProducto
    left join impuesto i on ioc.FKImpuesto = i.PKImpuesto
    left join claves_sat_unidades as csu on ifp.FKClaveSATUnidad = csu.PKClaveSATUnidad
  where doc.FKOrdenCompra = (select PKOrdenCompra from ordenes_compra where id_encriptado =:idOrdenCompra2)
  ) as tabless
   GROUP BY producto 
   ;");
$stmtp->execute(array(':idOrdenCompra1'=>$id, ':idOrdenCompra2'=>$id));
$rowp = $stmtp->fetchAll();

foreach ($rowp as $rp) { 

  $tbl.=  '<tr>
            <td width="9%">'.$rp['cantidad'].'</td>
            <td width="10%">'.$rp['clave'].'</td>
            <td width="39%">'.$rp['nombre'].'</td>
            <td width="14%">$ '.number_format($rp['precio'],2,'.',',').'</td>
            <td width="16%">'.$rp['unidadMedida'].'</td>
            <td width="12%">$ '.number_format($rp['importe'],2,'.',',').'</td>
          </tr>';
}

$tbl.= '<tr>
          <td class="td6 td7" width="100%"></td>
        </tr>
        <tr>
          <td class="td4 td6" width="55%" style="background-color: none;border-bottom: 1px solid #fff; border-top: 1px solid #fff;"></td>
          <td class="td4 td6" width="21%">Subtotal:</td>
          <td class="td6" width="24%" style="text-align: right;">$ '.$GLOBALS["Subtotal"].'</td>
        </tr>
        <tr>
          <td class="td4 td6" width="55%" style="background-color: none;border-bottom: 1px solid #fff; border-top: 1px solid #fff;"></td>
          <td class="td4 td6" width="21%">Impuestos:</td>
          <td class="td4 td6" width="24%"></td>
        </tr>';

  $stmti = $conn->prepare("call spc_OrdenesCompra_getImpuestosPDF(?);");
  $stmti->execute(array($id));
  $rowi = $stmti->fetchAll();

  $tasa = '';
  foreach ($rowi as $ri) { 
      if($ri['tasa'] == '' || $ri['tasa'] == null){
        $tasa = $ri['nombre'].' '.$ri['tasa'].'';
      }else{
        $tasa = $ri['nombre'].' - '.$ri['tasa'].'%';
      }
      $tbl.= '<tr>
                <td width="55%" style="background-color: none;border-bottom: 1px solid #fff; border-top: 1px solid #fff;"></td>
                <td width="21%" style="text-align: right;">'.$tasa.' </td>
                <td width="24%" style="text-align: right;">$ '.number_format($ri['totalImpuesto'],2,'.',',').'</td>
              </tr>';

  }

$tbl .= '<tr>
            <td width="55%" style="background-color: none;border-bottom: 1px solid #fff; border-top: 1px solid #fff;"></td>
            <th width="21%">Total:</th>
            <td width="24%" style="text-align: right;"> '.$GLOBALS["Moneda"].' $ '.$GLOBALS["ImporteTotal"].'</td>
          </tr>';

$tbl.= '</table>';

$pdf->writeHTML($tbl, true, false, true, false, '');

$direccionEntrega = '<p style="font-size: 11px; font-weight: bold;">Dirección de entrega:</p>
<p style="font-size: 11px; font-weight: normal;">'.$GLOBALS["Direccion"].'<p>';
$pdf->writeHTML($direccionEntrega, true, false, true, false, '');

$notasCliente = '<p style="font-size: 9px; font-weight: bold;">Notas:</p>
<p style="font-size: 9px; font-weight: normal;">'.$GLOBALS["NotaComprador"].'<p>';
$pdf->writeHTML($notasCliente, true, false, true, false, '');

$pdf->write1DBarcode($GLOBALS["referencia"], 'C39', '', '', '', 18, 0.4, $style, 'N');
$pdf->writeHTML($GLOBALS["referencia"], true, false, true, false, '');

ob_end_clean();

$pdf->Output("Orden de Compra ".$GLOBALS["referencia"]. '.pdf', 'D');

?>
