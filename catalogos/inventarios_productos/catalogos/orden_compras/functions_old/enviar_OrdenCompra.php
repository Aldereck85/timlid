<?php
session_start();
require_once('../../../../../lib/TCPDF/tcpdf.php');
require_once('../../../../../include/db-conn.php');
require_once('../../../../../lib/phpmailer_configuration.php');

if(isset($_POST['txtId'])){
  $origen = $_POST['txtOrigen'];
  $destino = $_POST['txtDestino'];
  $asunto = $_POST['txtAsunto'];
  $mensaje = $_POST['txaMensaje'];

  
}

$id = $_POST['txtId'];
$idProveedor = $_POST['txtIdProveedor'];
$notas = $_POST['txtNotas'];

$FKUsuario = $_SESSION["PKUsuario"];
$FechaModificacion = date("Y-m-d");

//Datos generales de la orden de compra
$stmt = $conn->prepare("SELECT oc.Referencia,
pv.NombreComercial,
    oc.Importe,
    DATE_FORMAT(oc.FechaCreacion, '%d/%m/%Y') as FechaCreacion,
    DATE_FORMAT(oc.FechaEstimada, '%d/%m/%Y') as FechaEstimada,
    s.Sucursal,
    s.Calle,
    s.NumExt,
    s.Prefijo,
    s.NumInt,
    s.Colonia,
    s.Municipio,
    ef.Estado,
    ps.Pais,
    s.Telefono,
    (select ifnull(sum(doc.Cantidad * doc.Precio),0) as subtotal
    from detalle_orden_compra doc
    where doc.FKOrdenCompra = :id1) as Subtotal,
    (select 
    ifnull(sum((doc.Cantidad * doc.Precio) * (ip.Tasa / 100)),0) as totalImpuesto
from detalle_orden_compra doc
 inner join productos p on doc.FKProducto = p.PKProducto  
 inner join info_fiscal_productos ifp on p.PKProducto = ifp.FKProducto
 inner join impuestos_productos ip on ifp.PKInfoFiscalProducto = ip.FKInfoFiscalProducto
 inner join impuesto i on ip.FKImpuesto = i.PKImpuesto
 inner join tipos_impuestos ti on i.FKTipoImpuesto = ti.PKTipoImpuesto
where doc.FKOrdenCompra = :id2) as Impuestos,
    ((select ifnull(sum(doc.Cantidad * doc.Precio),0) as subtotal
    from detalle_orden_compra doc
    where doc.FKOrdenCompra = :id3)+(select 
    ifnull(sum((doc.Cantidad * doc.Precio) * (ip.Tasa / 100)),0) as totalImpuesto
from detalle_orden_compra doc
 inner join productos p on doc.FKProducto = p.PKProducto  
 inner join info_fiscal_productos ifp on p.PKProducto = ifp.FKProducto
 inner join impuestos_productos ip on ifp.PKInfoFiscalProducto = ip.FKInfoFiscalProducto
 inner join impuesto i on ip.FKImpuesto = i.PKImpuesto
 inner join tipos_impuestos ti on i.FKTipoImpuesto = ti.PKTipoImpuesto
where doc.FKOrdenCompra = :id4)) as Total, 
    (select concat(e.Nombres,' ',e.PrimerApellido,' ',e.SegundoApellido) as usuario
           from empleados_usuarios eu
            inner join empleados e on eu.FKEmpleado = e.PKEmpleado
           where eu.FKUsuario = :usuario) as Empleado
FROM ordenes_compra oc
inner join proveedores pv on oc.FKProveedor = pv.PKProveedor
 inner join sucursales s on oc.FKSucursal = s.PKSucursal
 inner join paises ps on s.FKPais = ps.PKPais
 inner join estados_federativos ef on s.Estado = ef.PKEstado
where oc.PKOrdenCompra = :id5;");
$stmt->bindValue(':id1', $id, PDO::PARAM_INT);
$stmt->bindValue(':id2', $id, PDO::PARAM_INT);
$stmt->bindValue(':id3', $id, PDO::PARAM_INT);
$stmt->bindValue(':id4', $id, PDO::PARAM_INT);
$stmt->bindValue(':usuario',$FKUsuario);
$stmt->bindValue(':id5', $id, PDO::PARAM_INT);
$stmt->execute();
$row = $stmt->fetch();

  $GLOBALS["referencia"] = $row['Referencia'];
  $GLOBALS["Subtotal"] = number_format($row['Subtotal'],2,'.',','); 
  $GLOBALS["ImporteTotal"] = number_format($row['Importe'],2,'.',',');
  $GLOBALS["FechaIngreso"] = $row['FechaCreacion'];
  $GLOBALS["FechaEstimada"] = $row['FechaEstimada'];
  $GLOBALS["Sucursal"] = $row['Sucursal'];
  $GLOBALS["Direccion"] = $row['Calle'].' '.$row['NumExt'].' Int.'.$row['NumInt'].'- '.$row['Prefijo'].', '.$row['Colonia'].', '.$row['Municipio'].', '.$row['Estado'].', '.$row['Pais'];
  $GLOBALS["NombreComercial"] = 'GH MEDIC S.A. de C.V.';
  $GLOBALS["Vendedor"] = $row['Empleado'];
  $GLOBALS["Telefono"] = $row['Telefono'];
  $GLOBALS["NotaComprador"] = $notas;
  $GLOBALS["Email"] = $origen;

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
      $this->Cell(30, 10, 'Orden de Compra: ', 0);
      $image_file = '../../../../../img/Logo-transparente.png';
      $this->Image($image_file, 150, 9, 45);

      /*// Logo
      $image_file = '../../../../../img/Logo-transparente.png';
      $this->Image($image_file, 10, 11, 33, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
      $this->Cell(40);

      // Set font
      $this->SetFont('helvetica', 'B', 14);
      // Title
      $this->Cell(30, 10, 'Orden de Compra: '.$GLOBALS["referencia"] , 0, false, 'C', 0, '', 0, false, 'M', 'M');
      $this->Cell(50);
      $this->SetFont('Times','',12);
      $this->Cell(30,10,"Fecha de solicitud: ".$GLOBALS["FechaIngreso"] ,0, false, 'C', 0, '', 0, false, 'M', 'M');*/
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

        $footer = '
          <table>
            <tr>
              <th width="50%" style="font-size: 12px;"><b>Contacto:</b> ' . $GLOBALS['Vendedor'] . '</th>
              <th width="50%" style="font-size: 12px;"><b>Teléfono:</b> ' . $GLOBALS['Telefono'] . '</th>
            </tr>
            <tr>
              <th width="50%" style="font-size: 12px;"><b>Email:</b> ' . $GLOBALS["Email"] . '</th>
            </tr>
            
          </table>';
        $this->writeHTML($footer, true, false, true, false, '');

        // Page number
        //$this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
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

$noReferencia = '
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
  td {
    font-size: 12px;
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
    <td width="30%">' . $GLOBALS['referencia'] . '</td>
  </tr>
</table>';
$pdf->writeHTML($noReferencia, true, false, true, false, '');

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
    <th width="15%">Fecha de Solicitud</th>
    <th width="55%" style="background-color: none;"></th>
    <th width="30%">Cliente</th>
  </tr>
  <tr>
    <td width="15%">' . $GLOBALS['FechaIngreso'] . '</td>
    <td width="55%" style="background-color: none;"></td>
    <td width="30%">' . $GLOBALS['NombreComercial'] . '</td>
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
    <th width="15%">Contacto:</th>
    <td width="40%">' . $GLOBALS['Vendedor'] . '</td>
    <th width="5%" style="background-color: none;"></th>
    <th width="15%">Fecha de solicitud de entrega:</th>
    <td width="25%">' . $GLOBALS['FechaEstimada'] . '</td>
  </tr>
</table>';
$pdf->writeHTML($contacto, true, false, true, false, '');

$solicitante = '
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
    <th width="10%">Referencia:</th>
    <td width="15%">' . $GLOBALS['referencia'] . '</td>
    <th width="5%" style="background-color: none;"></th>
    <th width="15%">Solicitante:</th>
    <td width="25%">' . $GLOBALS['NombreComercial'] . '</td>
    <th width="5%" style="background-color: none;"></th>
    <th width="10%">Sucursal:</th>
    <td width="15%">' . $GLOBALS['Sucursal'] . '</td>
  </tr>
</table>';
$pdf->writeHTML($solicitante, true, false, true, false, '');

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
    <th width="15%">Domicilio de entrega:</th>
    <td width="85%">' . $GLOBALS['Direccion'] . '</td>

  </tr>
</table>';
$pdf->writeHTML($domicilioEntrega, true, false, true, false, '');

/*$tblenc = '<table>
      <tr>
        <th width="100%"><b>Contacto:</b> '.$GLOBALS['Vendedor'].'</th>
      </tr>
      <tr>
        <th width="100%"><b>Fecha de solicitud de entrega:</b> '.$GLOBALS['FechaEstimada'].'</th>
      </tr>
      <tr>
        <th width="100%"><b>Referencia:</b> '.$GLOBALS['referencia'].'</th>
      </tr>
      <tr>
        <th width="100%"><b>Solicitante:</b> '.$GLOBALS['NombreComercial'].'</th>
      </tr>
      <tr>
        <th width="100%"><b>Sucursal:</b> '.$GLOBALS['Sucursal'].'</th>
      </tr>
      <tr>
        <th width="100%"><b>Dirección de entrega:</b> '.$GLOBALS['Direccion'].'</th>
      </tr>
    </table>';
$pdf->writeHTML($tblenc, true, false, true, false, '');*/

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
        <th width="9%">Clave</th>
        <th width="36%">Producto</th>
        <th width="10%">Cantidad</th>
        <th width="10%">Precio</th>
        <th width="11%">U. medida</th>
        <th width="12%">Impuestos</th>
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
       dpp.UnidadMedida as unidadMedida,
       doc.Precio as precio,
       (doc.Cantidad * doc.Precio) as importe,
       (concat(i.Nombre,' ',ip.Tasa, '%' )) as impuestos
  from ordenes_compra oc
    inner join detalle_orden_compra doc on oc.PKOrdenCompra = doc.FKOrdenCompra
    inner join datos_producto_proveedores dpp on doc.FKProducto = dpp.FKProducto and :idProveedor = dpp.FKProveedor
    inner join productos p on doc.FKProducto = p.PKProducto  
    left join info_fiscal_productos ifp on p.PKProducto = ifp.FKProducto
    left join impuestos_productos ip on ifp.PKInfoFiscalProducto = ip.FKInfoFiscalProducto
    left join impuesto i on ip.FKImpuesto = i.PKImpuesto
  where doc.FKOrdenCompra = :idOrdenCompra
  ) as tabless
   GROUP BY producto 
   ;");
$stmtp->execute(array(':idOrdenCompra'=>$id, ':idProveedor'=>$idProveedor));
$rowp = $stmtp->fetchAll();

foreach ($rowp as $rp) { 

  $tbl.=  '<tr>
            <td width="9%">'.$rp['clave'].'</td>
            <td width="36%">'.$rp['nombre'].'</td>
            <td width="10%">'.$rp['cantidad'].'</td>
            <td width="10%">$ '.number_format($rp['precio'],2,'.',',').'</td>
            <td width="11%">'.$rp['unidadMedida'].'</td>
            <td width="12%">'.$rp['impuestos'].'</td>
            <td width="12%">$ '.number_format($rp['importe'],2,'.',',').'</td>
          </tr>';
}

$tbl.= '<tr>
          <td width="55%" style="background-color: none;border-bottom: 1px solid #fff; border-top: 1px solid #fff;"></td>
          <td width="21%">Subtotal:</td>
          <td width="24%" style="text-align: right;">$ '.$GLOBALS["Subtotal"].'</td>
        </tr>
        <tr>
          <td width="55%" style="background-color: none;border-bottom: 1px solid #fff; border-top: 1px solid #fff;"></td>
          <td width="21%">Impuestos:</td>
          <td width="24%"></td>
        </tr>';

$stmti = $conn->prepare("SELECT GROUP_CONCAT(id SEPARATOR ' / ') as id , GROUP_CONCAT(producto SEPARATOR ' / ') as producto, nombre, tasa, pkImpuesto, sum(totalImpuesto) as totalImpuesto from (
  select oc.PKOrdenCompra as id,
       dpp.NombreProducto as producto,
       i.Nombre as nombre,
       @_tasa :=ip.Tasa as tasa,
       i.PKImpuesto as pkImpuesto,
       (doc.Cantidad * doc.Precio) * (@_tasa / 100) as totalImpuesto
  from ordenes_compra oc
    inner join detalle_orden_compra doc on oc.PKOrdenCompra = doc.FKOrdenCompra
    inner join productos p on doc.FKProducto = p.PKProducto  
    inner join info_fiscal_productos ifp on p.PKProducto = ifp.FKProducto
          inner join impuestos_productos ip on ifp.PKInfoFiscalProducto = ip.FKInfoFiscalProducto
    inner join impuesto i on ip.FKImpuesto = i.PKImpuesto
    inner join tipos_impuestos ti on i.FKTipoImpuesto = ti.PKTipoImpuesto
          inner join datos_producto_proveedores dpp on doc.FKProducto = dpp.FKProducto and :idProveedor = dpp.FKProveedor
  where doc.FKOrdenCompra = :idOrdenCompra
 ) as impu
   GROUP BY tasa 
 order by nombre, tasa desc
   ;");
$stmti->execute(array(':idOrdenCompra'=>$id, ':idProveedor'=>$idProveedor));
$rowi = $stmti->fetchAll();

foreach ($rowi as $ri) { 
    $tbl.= '<tr>
            <td width="55%" style="background-color: none;border-bottom: 1px solid #fff; border-top: 1px solid #fff;"></td>
            <td width="21%" style="text-align: right;">'.$ri['nombre'].' - '.$ri['tasa'].'% </td>
            <td width="24%" style="text-align: right;">$ '.number_format($ri['totalImpuesto'],2,'.',',').'</td>
          </tr>';

}

$tbl .= '<tr>
            <td width="55%" style="background-color: none;border-bottom: 1px solid #fff; border-top: 1px solid #fff;"></td>
            <td width="21%">Total:</td>
            <td width="24%" style="text-align: right;">$ '.$GLOBALS["ImporteTotal"].'</td>
          </tr>';

$tbl.= '</table>';

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
    vertical-align: middle;
    text-align: center;
    line-height: 20px;
    border: 1px solid #ffffff;
    border-collapse: collapse;
  }
</style>
<table>
  <tr>
    <th width="100%" style="text-align: left;">Notas</th>
  </tr>
  <tr>
    <td width="100%">' . $GLOBALS["NotaComprador"] . '</td>
  </tr>
</table>';
$pdf->writeHTML($tblNotas, true, false, true, false, '');


ob_end_clean();
/*$pdf->Output('ordenCompra' . $GLOBALS["referencia"] . '.pdf', 'I');*/
// encode data (puts attachment in proper format)
$pdfdoc = $pdf->Output("Orden de Compra".$id, "S");

// send message
/*$stmt = $conn->prepare('SELECT NombreComercial FROM empresas WHERE PKEmpresa = 1');
$stmt->execute();
$rowemp = $stmt->fetch();*/

try {    
    $mail->Sender = $origen;
    $mail->setFrom($origen, 'nombre comercial');
    $mail->addReplyTo($origen, 'Javier');
    $mail->addAddress($destino);     //Add a recipient

    //Attachments
    $mail->AddStringAttachment($pdfdoc, 'OrdendeCompra_'.$id.'.pdf');

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = utf8_decode($asunto);
    $mail->Body    = utf8_decode($mensaje.'<br> <a href="http://192.168.1.204//taskManager/catalogos/inventarios_productos/catalogos/orden_compras/aceptarOrdenCompra?oc='.md5($id).'" style="background-color: #006dd9; /* TIMLID */
    border: none;
    color: white;
    padding: 15px 32px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;">Ver/Aceptar Orden de Compra</a>');

    if($mail->send())
    {
      /*$stmt = $conn->prepare("INSERT INTO bitacora_cotizaciones (FKUsuario, Fecha_Movimiento, FKMensaje, FKCotizacion) VALUES (:fkusuario, :fechamovimiento, :fkmensaje, :fkcotizacion)");
      $stmt->bindValue(':fkusuario',$FKUsuario);
      $stmt->bindValue(':fechamovimiento',$FechaModificacion);
      $stmt->bindValue(':fkmensaje', 8);
      $stmt->bindValue(':fkcotizacion',$id);
      $stmt->execute();  */
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
