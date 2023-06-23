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

//sacar la sucursal
$stmtSucursal = $conn->prepare("SELECT FKSucursal from ventas_directas where PKVentaDirecta = :PKVentaDirecta limit 1");
$stmtSucursal->bindValue(':PKVentaDirecta', $id, PDO::PARAM_INT);
$stmtSucursal->execute();
$rowSucursal = $stmtSucursal->fetch();

$GLOBALS["ruta"] = $ruta;
$GLOBALS["logotipo"] = $rowLogo['logo'];

//Datos generales de la Venta directa
$stmt = $conn->prepare("SELECT vd.Referencia,
      c.NombreComercial,
      c.razon_social as razonSocialCliente,
      c.rfc as rfcCliente,
      c.Telefono as telefonoCliente,
      c.Email as emailCliente,
      vd.Importe,
      DATE_FORMAT(vd.created_at, '%d/%m/%Y') as FechaCreacion,
      DATE_FORMAT(vd.FechaVencimiento, '%d/%m/%Y') as FechaEstimada,
      s.Sucursal,
      s.Calle,
      s.numero_exterior as NumExt,
      s.Prefijo,
      s.numero_interior as NumInt,
      s.Colonia,
      s.Municipio,
      ef.Estado,
      ps.Pais,
      s.Telefono as telefonos,
      em.RazonSocial as razon_social,
      vd.FKEstatusVenta,
      @_subtotal := (select ifnull(sum(dvd.Cantidad * dvd.Precio),0) as subtotal
					from detalle_venta_directa dvd
					where dvd.FKVentaDirecta = :id1) as Subtotal,
      @_impuestos := (select ifnull(sum((dvd.Cantidad * dvd.Precio) * (ip.Tasa / 100)),0) as totalImpuesto
					from detalle_venta_directa dvd
						 inner join productos p on dvd.FKProducto = p.PKProducto
						 inner join info_fiscal_productos ifp on p.PKProducto = ifp.FKProducto
						 inner join impuestos_productos ip on ifp.PKInfoFiscalProducto = ip.FKInfoFiscalProducto
						 inner join impuesto i on ip.FKImpuesto = i.PKImpuesto
						 inner join tipos_impuestos ti on i.FKTipoImpuesto = ti.PKTipoImpuesto
					where dvd.FKVentaDirecta = :id2) as Impuestos,
      (@_subtotal+@_impuestos) as Total,
      @_vendedor := vd.empleado_id as empleado_id,
      (select concat(e.Nombres,' ',e.PrimerApellido,' ',e.SegundoApellido) as usuario
					from empleados e where e.PKEmpleado =  @_vendedor) as Empleado,
      (select e.Telefono from empleados e where e.PKEmpleado =  @_vendedor) as Telefono,
      (select e.email from empleados e where e.PKEmpleado =  @_vendedor) as correoEmpleado,
      vd.NotasCliente as notas,
      vd.FKCliente,
      ifnull(vd.condicion_Pago,0) as condicionPago,
      decl.PKDireccionEnvioCliente,
      decl.Calle as CalleE,
      decl.Numero_exterior as NumExtE,
      decl.Numero_Interior as NumIntE,
      decl.Colonia as ColoniaE,
      decl.Municipio as MunicipioE,
      decl.Sucursal as SucursalE,
      decl.Contacto as ContactoE,
      decl.Telefono as TelefonoE,
      efE.Estado as EstadoE,
      psE.Pais as PaisE,
      ifnull(vd.direccion_entrega_id,0) as isNullE,
      IFNULL(md.CLAVE,0) as moneda
      FROM ventas_directas vd
      left join monedas md on md.PKMoneda = vd.FKMoneda_id
      inner join clientes c on vd.FKCliente = c.PKCliente
      inner join sucursales s on vd.FKSucursal = s.id
      inner join paises ps on s.pais_id = ps.PKPais
      inner join estados_federativos ef on s.estado_id = ef.PKEstado
      inner join empresas em on vd.empresa_id = em.PKEmpresa
      left join direcciones_envio_cliente decl on vd.direccion_entrega_id = decl.PKDireccionEnvioCliente
      left join paises psE on decl.Pais = psE.PKPais
      left join estados_federativos efE on decl.Estado = efE.PKEstado
      where vd.PKVentaDirecta = :id5;");
$stmt->bindValue(':id1', $id, PDO::PARAM_INT);
$stmt->bindValue(':id2', $id, PDO::PARAM_INT);
$stmt->bindValue(':id5', $id, PDO::PARAM_INT);
$stmt->execute();
$row = $stmt->fetch();

  $GLOBALS["referencia"] = $row['Referencia'];
  $GLOBALS["Subtotal"] = number_format($row['Subtotal'],2,'.',',');
  $GLOBALS["Total"] = floatval($row['Subtotal']);
  $GLOBALS["ImporteTotal"] = number_format($row['Importe'],2,'.',',');
  $GLOBALS["FechaIngreso"] = $row['FechaCreacion'];
  $GLOBALS["FechaEstimada"] = $row['FechaEstimada'];
  $GLOBALS["Sucursal"] = $row['Sucursal'];
  if($row['ContactoE'] == null || $row['ContactoE'] == ''){
    $row['ContactoE']= "Desconocido";
  }
  if ($row['PKDireccionEnvioCliente'] != 1){
    if ($row['isNullE'] == '0'){
      $GLOBALS["DireccionE"] = '';
    }else{
      $GLOBALS["DireccionE"] = $row['CalleE'].' '.$row['NumExtE'].' Int.'.$row['NumIntE'].', '.$row['ColoniaE'].', '.$row['MunicipioE'].', '.$row['EstadoE'].', '.$row['PaisE'].', Atención: '.$row['ContactoE'].' '.$row['TelefonoE'];
    }
  }else{
    $GLOBALS["DireccionE"] = '';
  }
  $GLOBALS["Direccion"] = $row['Calle'].' '.$row['NumExt'].' Int.'.$row['NumInt'].'- '.$row['Prefijo'].', '.$row['Colonia'].', '.$row['Municipio'].', '.$row['Estado'].', '.$row['Pais'];
  $GLOBALS["NombreComercial"] = $row['NombreComercial'];
  $GLOBALS["NombreComercial2"] = $row['razon_social'];
  $GLOBALS["Vendedor"] = $row['Empleado'];
  $GLOBALS["Telefono"] = $row['Telefono'];
  $GLOBALS["NotaComprador"] = $row['notas'];
  $GLOBALS["Email"] = $row['correoEmpleado'];
  $GLOBALS["RFCCliente"] = $row['rfcCliente'];
  $GLOBALS["TelefonoCliente"] = $row['telefonoCliente'];
  $GLOBALS["EmailCliente"] = $row['emailCliente'];
  $GLOBALS["RazonSocialCliente"] = $row['razonSocialCliente'];
  $GLOBALS["SucursalE"] = $row['SucursalE'];
  $GLOBALS["Moneda"] = $row['moneda'] == 0 ? $row['moneda'] : "" ;

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
      $this->Image($image_file, 10, 10, 50, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
      // Set font
      $this->SetFont('Helvetica', 'B', 20);
      // Title
      // set cell padding
      $this->setCellPaddings(1, 1, 1, 1);
      // set cell margins
      $this->setCellMargins(1, 1, 1, 1);
      // MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0)
      $this->MultiCell(0, 30, 'Venta', 0, 'R', 0, 0, 125, 6, true);

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

        /*$footer = '
          <table>
            <tr>
              <th width="50%" style="font-size: 12px;"><b>Contacto:</b> ' . $GLOBALS['Vendedor'] . '</th>
              <th width="50%" style="font-size: 12px;"><b>Teléfono:</b> ' . $GLOBALS['Telefono'] . '</th>
            </tr>
            <tr>
              <th width="50%" style="font-size: 12px;"><b>Email:</b> ' . $GLOBALS["Email"] . '</th>
            </tr>
            
          </table>';
        $this->writeHTML($footer, true, false, true, false, '');*/
        $this->Cell(0, 5, ''.$this->getAliasNumPage().' de '.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
    }

}

$pdf = new PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Timlid');
$pdf->SetTitle('Venta');
$pdf->SetSubject('Venta');
$pdf->SetKeywords('Venta');
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
    text-align: right;
    border: none;
    vertical-align:middle;
  }
</style>
<table>
  <tr>
    <th width="50%">INFORMACIÓN DEL CLIENTE</th>
    <th width="50%">TOTAL</th>
  </tr>
  <tr>
    <td class="td3 td4" width="25%">Nombre</td>
    <td class="td3" width="25%">' . $GLOBALS["RazonSocialCliente"] . '</td>
    <td class="td4 td5 td6" width="50%" rowspan="7"> <br><br><br><br>$'.$GLOBALS["ImporteTotal"].'</td>
  </tr>
  <tr>
    <td class="td3 td4" width="25%">Atención</td>
    <td class="td3" width="25%"></td>
  </tr>
  <tr>
    <td class="td3 td4" width="25%">Condición Pago</td>
    <td class="td3" width="25%">'.$GLOBALS["CondicionPago"].'</td>
  </tr>
  <tr>
    <td class="td3 td4" width="25%">RFC</td>
    <td class="td3" width="25%">' . $GLOBALS["RFCCliente"] . '</td>
  </tr>
  <tr>
    <td class="td3 td4" width="25%">Teléfono</td>
    <td class="td3" width="25%">' . $GLOBALS["TelefonoCliente"] . '</td>
  </tr>
  <tr>
    <td class="td3 td4" width="25%">Forma de Envío</td>
    <td class="td3" width="25%"></td>
  </tr>
  <tr>
    <td class="td3 td4" width="25%">email</td>
    <td class="td3" width="25%"><u>' . $GLOBALS["EmailCliente"] . '</u></td>
  </tr>
  <tr>
    <td class="td3 td4" width="25%" rowspan="2">Domicilio Entrega</td>
    <td class="td3" width="25%" rowspan="2">' .$GLOBALS["SucursalE"].' - '.$GLOBALS['DireccionE']. '</td>
    <td class="td4 td6" width="50%"> Vencimiento: '.$GLOBALS["FechaEstimada"].'</td>
  </tr>
  <tr>
    <td class="td6" width="50%"></td>
  </tr>
</table>';
$pdf->writeHTML($fechaSolicitud, true, false, true, false, '');

$contacto = '
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
    <th width="100%">INFORMACIÓN DE VENTAS</th>
  </tr>
  <tr>
    <td class="td3 td4" width="27.5%">Vendedor</td>
    <td class="td3 td4" width="27.5%">Correo</td>
    <td class="td3 td4" width="15%">Teléfono</td>
    <td class="td3 td4" width="15%">Moneda</td>
    <td class="td3 td4" width="15%">Tipo de Cambio</td>
  </tr>
  <tr>
    <td class="td3 td6" width="27.5%">'. $GLOBALS['Vendedor'] .'</td>
    <td class="td3 td6" width="27.5%"><u>'. $GLOBALS["Email"] .'</u></td>
    <td class="td3 td6" width="15%">'. $GLOBALS["Telefono"] .'</td>
    <td class="td3 td6" width="15%"></td>
    <td class="td3 td6" width="15%"></td>
  </tr>
</table>';
$pdf->writeHTML($contacto, true, false, true, false, '');

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

///Tabla de productos
$stmtp = $conn->prepare("SELECT id, idDetalle, producto, nombre, clave, cantidad, unidadMedida, precio, importe, GROUP_CONCAT(impuestos SEPARATOR ' / ') as impuestos, minima, existencia  from (
  select vd.PKVentaDirecta as id,
       dvd.PKDetalleVentaDirecta as idDetalle,
       dvd.FKProducto as producto,
       p.Nombre as nombre,
       p.ClaveInterna as clave,
       #dvd.Cantidad as cantidad,
       (SELECT IFNULL(edv.cantidad,dvd.Cantidad))  as cantidad,
       csu.Descripcion as unidadMedida,
       (SELECT IFNULL(edv.precio,dvd.Precio)) as precio,
       ((SELECT IFNULL(edv.cantidad,dvd.Cantidad)) * (SELECT IFNULL(edv.precio,dvd.Precio))) as importe,
       if(i.FKTipoImporte = 2,(concat(i.Nombre,' - ',ip.Tasa)),(concat(i.Nombre,' ',ip.Tasa, '%' ))) as impuestos,
             '1' as minima,
             (select ifnull(sum(existencia),0) as StockExistencia
      from existencia_por_productos
      where producto_id = producto
        and sucursal_id = :pkSucursal) as existencia
  from ventas_directas vd
    inner join detalle_venta_directa dvd on vd.PKVentaDirecta = dvd.FKVentaDirecta
    LEFT JOIN edicion_detalle_venta edv on edv.FKDetalleVentaDirecta = dvd.PKDetalleVentaDirecta
    inner join productos p on dvd.FKProducto = p.PKProducto
    left join info_fiscal_productos ifp on p.PKProducto = ifp.FKProducto
          left join claves_sat_unidades csu on ifp.FKClaveSATUnidad = csu.PKClaveSATUnidad
    left join impuestos_productos ip on ifp.PKInfoFiscalProducto = ip.FKInfoFiscalProducto
    left join impuesto i on ip.FKImpuesto = i.PKImpuesto
  where vd.PKVentaDirecta = :idVentaDirecta and dvd.estatus = 1
  ) as tabless GROUP BY producto
                      ;");
$stmtp->execute(array(':idVentaDirecta'=>$id,':pkSucursal'=>$rowSucursal));
$rowp = $stmtp->fetchAll();

foreach ($rowp as $rp) { 

  if($rp['unidadMedida'] == ""){
    $ClaveUnidad = "Sin unidad";
  }
  else{
    $ClaveUnidad = $rp['unidadMedida'];
  }

  $tbl.=  '<tr>
            <td width="9%">'.$rp['cantidad'].'</td>
            <td width="10%">'.$rp['clave'].'</td>
            <td width="39%">' . $rp['nombre'] . '</td>
            <td width="14%">$ '.number_format($rp['precio'],2,'.',',').'</td>
            <td width="16%">'. $ClaveUnidad .'</td>
            <td width="12%">$ '.number_format($rp['importe'],2,'.',',').'</td>
          </tr>';
  }

  /*<tr>
      <td class="td4 td6 td7" width="100%">\'NOTA ESPECIAL: MATERIAL CORTADO A LA MEDIDA O SURTIDO EN TÉRMINOS ESPECIALES, POR NINGÚN MOTIVO SE ACEPTARÁN DEVOLUCIONES\'</td>
    </tr> */

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
        
  //// Consulta IEPS
  $stmtIEPS = $conn->prepare("SELECT ((if(i.FKTipoImporte = 2,(dvd.Cantidad * ivd.Tasa),((dvd.Cantidad * dvd.Precio) * (ivd.Tasa / 100)))) ) as TotalIEPS, dvd.FKProducto as id from 
  ventas_directas vd 
  LEFT JOIN detalle_venta_directa dvd on vd.PKVentaDirecta = dvd.FKVentaDirecta
  LEFT JOIN productos p on dvd.FKProducto = p.PKProducto 
  LEFT JOIN impuestos_venta_directa ivd on vd.PKVentaDirecta = ivd.FKVentaDirecta and dvd.FKProducto = ivd.FKProducto
  left join impuesto i on ivd.FKImpuesto = i.PKImpuesto
  where (vd.PKVentaDirecta = :idVentaDirecta) and (ivd.Impuesto = 'IEPS' or ivd.Impuesto = 'IEPS (Monto fijo)') ;");
          $stmtIEPS->execute(array(':idVentaDirecta'=>$id));
          $rowIEPS = $stmtIEPS->fetchAll();

    //Tabla de Impuestos
  $stmti = $conn->prepare("SELECT  p.Nombre as producto,
    ivd.FKProducto as id,
    i.Nombre as nombre,
    i.FKTipoImporte as tipo,
      i.FKTipoImpuesto as tipoImp,
    if(i.FKTipoImporte = 2,ivd.Tasa,ivd.Tasa) as tasa,
    i.PKImpuesto as pkImpuesto, 
    ifnull(if(ivd.impuesto = 'IVA', (if(i.FKTipoImporte = 2,(SELECT IFNULL(dvd.cantidad,dvd.Cantidad)) * ivd.Tasa,(((SELECT IFNULL(dvd.cantidad,dvd.Cantidad)) * ifnull(dvd.Precio, cepc.CostoEspecial)) * (ivd.Tasa / 100)))),if(i.FKTipoImporte = 2,(SELECT IFNULL(dvd.cantidad,dvd.Cantidad)) * ivd.Tasa,(((SELECT IFNULL(dvd.cantidad,dvd.Cantidad)) * ifnull(dvd.Precio, cepc.CostoEspecial)) * (ivd.Tasa / 100)))  ),0) as totalImpuesto
    FROM 
    ventas_directas vd 
    left join detalle_venta_directa dvd on vd.PKVentaDirecta = dvd.FKVentaDirecta
    left join productos p on dvd.FKProducto = p.PKProducto  
    left join impuestos_venta_directa ivd on vd.PKVentaDirecta = ivd.FKVentaDirecta and dvd.FKProducto = ivd.FKProducto	 
    left join impuesto i on ivd.FKImpuesto = i.PKImpuesto
    left join costo_especial_producto_cliente cepc on p.PKProducto = cepc.FKProducto and vd.FKCliente = cepc.FKCliente
    where vd.PKVentaDirecta = :idVentaDirecta and dvd.estatus = 1 ;");
  $stmti->execute(array(':idVentaDirecta'=>$id));
  $rowi = $stmti->fetchAll();
  
  //$GLOBALS["Total"] = floatval($GLOBALS["Subtotal"]);
  if(!empty($rowIEPS)){
    //Recorre el arreglo de IEPS de productos
    foreach($rowIEPS as $result){
        $count = 0;
        ///Recorre el array de impuestos de productos de la venta
        foreach($rowi as $impuestos){
            /// Si el producto en ambos arreglos es igual y el tipo de impuesto en el array impuestos es un IVA
            if(($result['id'] == $impuestos['id']) && $impuestos['nombre'] == "IVA"){
                /// El Total Impuesto es igual a lo que ya tenia mas el iva el ieps de ese producto ya sea en tasa o en cuota
                $impuestos['totalImpuesto'] = floatval($impuestos['totalImpuesto']) + (floatval($result['TotalIEPS']) * (floatval($impuestos['tasa']) / 100)); 
                $rowi[$count]['totalImpuesto'] = $impuestos['totalImpuesto'];
            }
            
            $count++;
          }

        
    }
  }
  ///Agrupar los impuestos 
  $result = array_reduce($rowi, function($carry, $item, $count = 0){ 
    $count++;
    ///Quita los productos que no tienen ningun impuesto
    if($item['id'] == null){
                
    }else{
        ///se recorre el array, si el array aux carry no contiene el nombre del impuesto lo crea.
    if(!isset($carry[$item['nombre']])){ 
        $carry[$item['nombre']] = ['nombre'=>$item['nombre'],'totalImpuesto'=>$item['totalImpuesto'],'tasa'=>$item['tasa'],'id'=>$item['id'],'tipo'=>$item['tipo'],'tipo'=>$item['tipo'],'tipoImp'=>$item['tipoImp']]; 
    } else { 
        ///Si ya lo tiene
        ////Si la tasa del impuesto del mismo nombre que contiene $carry es igual a la del nuevo de array suma los totales impuestos. 
        if($carry[$item['nombre']]['tasa'] == $item['tasa']){
            $carry[$item['nombre']]['totalImpuesto'] += $item['totalImpuesto']; 
        }else{
            ///Si es una tasa diferente crea un nuevo array concatenando el contador para que sea una posision del result diferente
            $carry[$item['nombre'].$count] = ['nombre'=>$item['nombre'],'totalImpuesto'=>$item['totalImpuesto'],'tasa'=>$item['tasa'],'id'=>$item['id'],'tipo'=>$item['tipo']]; 
        }
        
    }
    }
    return $carry;
  });

  $tasa = '';
  foreach ($result as $ri) { 

    if($ri['tasa'] == '' || $ri['tasa'] == null){
      $tasa = $ri['nombre'].' '.$ri['tasa'].'';
    }else{
      $tasa = $ri['nombre'].' - '.$ri['tasa'].'%';
    }
    /// Restar cuando el tipo impuesto sea retenido.
    if($ri['tipoImp'] == "2"){
      $GLOBALS["Total"] -=  floatval($ri['totalImpuesto']);
    }else{
      $GLOBALS["Total"] +=  floatval($ri['totalImpuesto']);
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
            <td width="24%" style="text-align: right;"> '.$GLOBALS["Moneda"].' $ '.number_format($GLOBALS["Total"],2,'.',',').'</td>
          </tr>';

$tbl.= '</table>';

$pdf->writeHTML($tbl, true, false, true, false, '');

/*$tblNotas = '<p style="font-size: 8px; font-weight: bold;">LIMITACION DE RESPONSABILIDAD</p>
<br>
<p style="font-size: 7px;">El material que ampara esta cotización es de Calidad Certificada, pero por su naturaleza puede tener discontinuidades internas que los equipos de Control de Calidad de nuestros Proveedores pueden no detectarlas; en el caso de encontrar en estos productos alguna discontinuidad 
interna que pudiera afectar su producto terminado, como siempre estamos en la mejor disposición de atender su petición. Únicamente nos hacemos responsables por el valor del material reclamado el cual será repuesto o se le acreditará en su cuenta. No nos hacemos responsables 
por tiempo de maquinado, herramientas fracturadas, maquinaria dañada, transporte, maniobras o algún otro gasto generado en sus procesos.</p>
<br>
<p style="font-size: 8px; font-weight: bold;">CONDICIONES DE VENTA</p><br>
<p style="font-size: 7px;">Los precios en moneda nacional se realizan al tipo de cambio del día del diario oficial, sujetos a cambio sin previo aviso
<br>
Todos nuestros precios son L.A.B. origen de embarque, la mercancía viajará por cuenta y riesgo del comprador
<br>
No se aceptan cancelaciones de material cortado a medida o surtido en términos especiales</p>
<br><br><br><br><br>';
$pdf->writeHTML($tblNotas, true, false, true, false, '');*/

$notasCliente = '<p style="font-size: 9px; font-weight: bold;">Notas:</p>
<p style="font-size: 9px; font-weight: normal;">'.$GLOBALS["NotaComprador"].'<p>';
$pdf->writeHTML($notasCliente, true, false, true, false, '');

$pdf->write1DBarcode($GLOBALS["referencia"], 'C39', '', '', '', 18, 0.4, $style, 'N');
$pdf->writeHTML($GLOBALS["referencia"], true, false, true, false, '');

ob_end_clean();

$pdf->Output("Venta ".$GLOBALS["referencia"]. '.pdf', 'I');

?>
