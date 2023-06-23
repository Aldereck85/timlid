<?php
session_start();

$jwt_ruta = "../../../../../";
require_once ('../../../../jwt.php');

require_once('../../../../../lib/TCPDF/tcpdf.php');
require_once('../../../../../include/db-conn.php');
require_once('../../../../../lib/phpmailer_configuration.php');


if(isset($_POST["csr_token_7ALF1"])){
  $token = $_POST["csr_token_7ALF1"];
}

if(empty($_SESSION['token_ld10d'])) {
  echo "fallo";
  return;
}

if (!hash_equals($_SESSION['token_ld10d'], $token)) {
  echo "fallo";
  return;
}

$appUrl = $_ENV['APP_URL'] ?? 'https://app.timlid.com/';

if(isset($_POST['txtId'])){
  $origen = $_POST['txtOrigen'];
  $destino = $_POST['txtDestino'];
  $asunto = $_POST['txtAsunto'];
  $mensaje = $_POST['txaMensaje'];
}

$id = $_POST['txtId'];
$FKUsuario = $_SESSION["PKUsuario"];

date_default_timezone_set('America/Mexico_City');
$FechaModificacion = date("Y-m-d H:i:s");

include_once("../../../../../functions/functions.php");

$stmt = $conn->prepare('SELECT vd.PKVentaDirecta, vd.Referencia, vd.Importe, vd.Subtotal, vd.FechaVencimiento, vd.NotasInternas, vd.NotasCliente, vd.id_encriptado, c.NombreComercial, u.usuario, u.nombre,  u.Usuario, e.Telefono, IFNULL(md.CLAVE,0) as moneda  FROM ventas_directas vd INNER JOIN clientes as c ON c.PKCliente = vd.FKCliente
LEFT JOIN usuarios AS u ON vd.FKUsuarioCreacion = u.id left join monedas md on md.PKMoneda = vd.FKMoneda_id LEFT JOIN empleados as e ON vd.empleado_id = e.PKEmpleado WHERE vd.PKVentaDirecta=:id');
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$row = $stmt->fetch();
$GLOBALS["referencia"] = $row['Referencia'];
$GLOBALS["Subtotal"] = number_format($row['Subtotal'],2);
$GLOBALS["Total"] = floatval($row['Subtotal']); 
$GLOBALS["ImporteTotal"] = number_format($row['Importe'],2);
$GLOBALS["NotaCliente"] = $row['NotasCliente'];
$GLOBALS["NotaInterna"] = $row['NotasInternas'];
$GLOBALS["NombreComercial"] = $row['NombreComercial'];
$GLOBALS["Email"] = $row['email'];
$GLOBALS["Telefono"] = $row['Telefono'];
$GLOBALS['FechaVencimiento'] = $row['FechaVencimiento'];
$GLOBALS["Moneda"] = $row['moneda'] == 0 ? $row['moneda'] : "" ;

$GLOBALS["Vendedor"] = $row['nombre'];
$codigo = $row['id_encriptado'];
$idDes = encryptor("encrypt", $id);
$codigoDes = encryptor("encrypt", $codigo);

$PKEmpresa = $_SESSION["IDEmpresa"];
$ruta = $_ENV['RUTA_ARCHIVOS_READ'].$PKEmpresa.'/fiscales'.'/';

//Logo de la empresa que emite la OC
$stmtLogo = $conn->prepare("SELECT logo FROM empresas WHERE PKEmpresa = :empresa");
$stmtLogo->bindValue(':empresa', $PKEmpresa, PDO::PARAM_INT);
$stmtLogo->execute();
$rowLogo = $stmtLogo->fetch();

$GLOBALS["ruta"] = $ruta;
$GLOBALS["logotipo"] = $rowLogo['logo'];

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
        $this->Cell(30, 10, 'Venta No. ' . $GLOBALS["referencia"], 0);
        $image_file = $GLOBALS["ruta"].$GLOBALS["logotipo"];
        $this->Image($image_file, 150, 9, 45);
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
      $this->SetFont('Helvetica', '', 14);
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

        $footer = '
          <table>
            <tr>
              <th width="50%" style="font-size: 12px;"><b>Contacto:</b> ' . $GLOBALS['Vendedor'] . '</th>
              <th width="50%" style="font-size: 12px;"><b>Teléfono:</b> ' . $GLOBALS['Telefono'] . '</th>
            </tr>
            <tr>
              <th width="50%" style="font-size: 12px;"><b>Email:</b> ' . $GLOBALS['Email'] . '</th>
            </tr>
            <tr>
                <th width="100%" style="text-align: center; font-size: 11px;">' . $GLOBALS['leyenda'] . '</th>
              </tr>
          </table>';
        $this->writeHTML($footer, true, false, true, false, '');

        // Page number
        //$this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
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
    <td class="td3" width="25%" rowspan="2">'.$GLOBALS["SucursalE"].' - '.$GLOBALS['DireccionE']. '</td>
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

ob_end_clean();

// encode data (puts attachment in proper format)
$pdfdoc = $pdf->Output("venta_".$GLOBALS["referencia"], "S");

// send message
$stmt = $conn->prepare('SELECT RazonSocial FROM empresas WHERE PKEmpresa = '.$_SESSION['IDEmpresa']);
$stmt->execute();
$rowemp = $stmt->fetch();

$stmt = $conn->prepare("SELECT valor FROM parametros_servidor WHERE parametro = 'email_contacto' ");
$stmt->execute();
$url = $stmt->fetch();
$email_origen = $_ENV['ORIGEN_MAIL'] ?? "no-reply@timlid.com";

try {    
    $mail->Sender = $email_origen;
    $mail->setFrom($email_origen, $rowemp['RazonSocial']);
    $mail->addReplyTo($origen, $rowemp['RazonSocial']);
    $mail->addAddress($destino);     //Add a recipient

    //Attachments
    $mail->AddStringAttachment($pdfdoc, 'venta_'.$GLOBALS["referencia"].'.pdf');

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = utf8_decode($asunto);
    $mail->Body    = utf8_decode($mensaje);

    if($mail->send())
    {
      echo "exito";
    }
    else{
      echo "fallo";
    }

} catch (Exception $e) {
    //header('Location: ver_Cotizacion.php?id='.$id.'&estatus=2');
    echo "fallo";
}

?>
