<?php
session_start();

$jwt_ruta = "../../../";
require_once '../../jwt.php';

require_once('../../../lib/TCPDF/tcpdf.php');
require_once('../../../include/db-conn.php');
require_once('../../../lib/phpmailer_configuration.php');


$token = $_POST["csr_token_7ALF1"];

if(empty($_SESSION['token_ld10d'])) {
  echo "fallo - empty token";
  return;
}

if (!hash_equals($_SESSION['token_ld10d'], $token)) {
  echo "fallo - bad token";
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

$stmt = $conn->prepare("SELECT texto FROM parametros_texto WHERE descripcion = 'leyenda_cotizacion' AND empresa_id = :empresa_id");
$stmt->bindValue(":empresa_id", $_SESSION['IDEmpresa']);
$stmt->execute();
$row_leyenda = $stmt->fetch();
$GLOBALS["leyenda"] = $row_leyenda['texto'];

include_once("../../../functions/functions.php");

$stmt = $conn->prepare('SELECT c.id_cotizacion_empresa, c.Subtotal, c.ImporteTotal, DATE_FORMAT(c.FechaIngreso, "%d/%m/%Y %H:%i:%s") as FechaIngreso, DATE_FORMAT(c.FechaVencimiento, "%d/%m/%Y") as FechaVencimiento,
c.NotaCliente,c.NotaInterna, cl.NombreComercial, u.nombre,  u.Usuario as email, e.Telefono,c.CodigoCotizacion,IFNULL(md.CLAVE,0) as moneda,md.PKMoneda
FROM cotizacion as c INNER JOIN clientes as cl ON cl.PKCliente = c.FKCliente
LEFT JOIN usuarios AS u ON c.FKUsuarioCreacion = u.id LEFT JOIN empleados as e ON c.empleado_id = e.PKEmpleado left join monedas md on md.PKMoneda = c.FkMoneda_id
WHERE c.PKCotizacion = :id');
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$row = $stmt->fetch();
$GLOBALS["referencia"] = sprintf("%011d", $row['id_cotizacion_empresa']);
$GLOBALS["Subtotal"] = number_format($row['Subtotal'],2); 
$GLOBALS["ImporteTotal"] = number_format($row['ImporteTotal'],2); 
$GLOBALS["FechaIngreso"] = $row['FechaIngreso'];
$GLOBALS["NotaCliente"] = $row['NotaCliente'];
$GLOBALS["NotaInterna"] = $row['NotaInterna'];
$GLOBALS["NombreComercial"] = $row['NombreComercial'];
$GLOBALS["Email"] = $row['email'];
$GLOBALS["Telefono"] = $row['Telefono'];
$GLOBALS['FechaVencimiento'] = $row['FechaVencimiento'];
$GLOBALS["Moneda"] = $row['moneda'] == "0" ? "" : $row['moneda'];

$GLOBALS["Vendedor"] = $row['nombre'];
$codigo = $row['CodigoCotizacion'];
$idDes = encryptor("encrypt", $id);
$codigoDes = encryptor("encrypt", $codigo);

$mensaje = $mensaje."<br><br>"."Puedes revisar y aceptar tu cotización en el siguiente link:<br><br><a href='".$appUrl."cliente/cotizacion.php?id=".$idDes."&codigo=".$codigoDes."' target='_blank' title='Cotización TimLid'>Cotización No. ".$GLOBALS["referencia"]."</a>";

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
        $this->Cell(30, 10, 'Cotización No. ' . $GLOBALS["referencia"], 0);
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
$pdf->SetTitle('Cotizacion');
$pdf->SetSubject('Cotizacion');
$pdf->SetKeywords('Cotizacion');
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
    <th width="20%">Fecha de expedición</th>
    <th width="20%">Fecha de vencimiento</th>
    <th width="40%" style="background-color: none;"></th>
    <th width="20%">Cliente</th>
  </tr>
  <tr>
    <td width="20%">' . $GLOBALS['FechaIngreso'] . '</td>
    <td width="20%">' . $GLOBALS['FechaVencimiento'] . '</td>
    <td width="40%" style="background-color: none;"></td>
    <td width="20%">' . $GLOBALS['NombreComercial'] . '</td>
  </tr>
</table>';
$pdf->writeHTML($tblenc, true, false, true, false, '');

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
$stmt = $conn->prepare('SELECT dc.FKProducto, dc.Cantidad, dc.Precio, p.ClaveInterna, p.Nombre, csu.Descripcion FROM detalle_cotizacion as dc INNER JOIN productos as p ON p.PKProducto = dc.FKProducto LEFT JOIN info_fiscal_productos as ifp ON ifp.FKProducto = p.PKProducto LEFT JOIN claves_sat_unidades as csu ON csu.PKClaveSATUnidad = ifp.FKClaveSATUnidad WHERE dc.FKCotizacion = :id');
$stmt->execute(array(':id'=>$id));
$numero_productos = $stmt->rowCount();
$rowp = $stmt->fetchAll();

$impuestos = array();
$x = 0;

foreach ($rowp as $rp) {

    $stmt = $conn->prepare('SELECT i.PKImpuesto, i.Nombre, i.FKTipoImpuesto as TipoImpuesto, i.FKTipoImporte as TipoImporte, i.Operacion, di.FKProducto, di.Tasa FROM detalleimpuesto as di INNER JOIN impuesto as i ON i.PKImpuesto = di.FKImpuesto WHERE di.FKCotizacion= :id AND FKProducto = :idProducto');
    $stmt->execute(array(':id'=>$id, ':idProducto'=>$rp['FKProducto']));
    $rowi = $stmt->fetchAll();
    
    if($rp['Descripcion'] == ""){
      $ClaveUnidad = "Sin unidad";
    }
    else{
      $ClaveUnidad = $rp['Descripcion'];
    }

    $totalProducto = $rp['Cantidad'] * $rp['Precio'];
    $tbl.=  '<tr>
              <td width="9%">'.$rp['ClaveInterna'].'</td>
              <td width="36%">'.$rp['Nombre'].'</td>
              <td width="10%">'.$rp['Cantidad'].'</td>
              <td width="10%">'.$rp['Precio'].'</td>
              <td width="11%">'.$ClaveUnidad.'</td>
              <td width="12%">';

          $contImpuestos = 1;
          $numImpuestos = count($rowi);
          foreach ($rowi as $ri) { 
            $IniImpuesto = explode(" ", $ri['Nombre']);
            $Identificador = $IniImpuesto[0]."_".$ri['TipoImpuesto']."_".$ri['TipoImporte']."_".$ri['PKImpuesto']."_".$ri['FKProducto'];
            
            if($ri['TipoImporte'] == 1){
              $tas = "%";
            }
            if($ri['TipoImporte'] == 2 || $ri['TipoImporte'] == 3){
              $tas = "";
            }

            //print_r($impuestos);

            //echo "id impuesto :".$ri['PKImpuesto']."//";
            $found_key = array_search($ri['PKImpuesto'], array_column($impuestos, 0));
          
            if($found_key > -1){
                $impuestos[$found_key][0] = $ri['PKImpuesto']; 

                if($ri['TipoImporte'] == 1)
                  $impuestos[$found_key][1] = $impuestos[$found_key][1] + (($rp['Cantidad'] * $rp['Precio']) * ($ri['Tasa'] / 100));
                else
                  $impuestos[$found_key][1] = $impuestos[$found_key][1] + $ri['Tasa'];

                $impuestos[$found_key][2] = $ri['Nombre']; 
                $impuestos[$found_key][3] = $ri['TipoImpuesto']; 
                $impuestos[$found_key][4] = $ri['Operacion'];
            }
            else{
                $impuestos[$x][0] = $ri['PKImpuesto']; 
                if($ri['TipoImporte'] == 1)
                  $impuestos[$x][1] = ($rp['Cantidad'] * $rp['Precio']) * ($ri['Tasa'] / 100);
                else
                  $impuestos[$x][1] = $ri['Tasa'];

                $impuestos[$x][2] = $ri['Nombre']; 
                $impuestos[$x][3] = $ri['TipoImpuesto']; 
                $impuestos[$x][4] = $ri['Operacion']; 
                $x++;
            }
            
             $tbl.= $ri['Nombre']." ".$ri['Tasa'].$tas;
             if($contImpuestos != $numImpuestos)
              $tbl.= "<br>";
             $contImpuestos++;
          }


    $tbl.=   '</td>
            <td width="12%">'.number_format($totalProducto,2).'</td>
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

        //print_r($impuestos);
        foreach ($impuestos as $imp) {
            $IniImpuesto = explode(" ", $imp[2]);
            $tbl.= '<tr>
                    <td width="55%" style="background-color: none;border-bottom: 1px solid #fff; border-top: 1px solid #fff;"></td>
                    <td width="21%" style="text-align: right;">'.$imp[2].'</td>
                    <td width="24%" style="text-align: right;">$ '.number_format($imp[1],2).'</td>
                 </tr>';

        }

$tbl .= '<tr>
            <td width="55%" style="background-color: none;border-bottom: 1px solid #fff; border-top: 1px solid #fff;"></td>
            <td width="21%">Total:</td>
            <td width="24%" style="text-align: right;">'.$GLOBALS["Moneda"].' $ ' . $GLOBALS["ImporteTotal"] . '</td>
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
    vertical-align: center;
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
    <td width="100%">' . $GLOBALS["NotaCliente"] . '</td>
  </tr>
</table>';
$pdf->writeHTML($tblNotas, true, false, true, false, '');

ob_end_clean();

// encode data (puts attachment in proper format)
$pdfdoc = $pdf->Output("cotizacion_".$GLOBALS["referencia"], "S");

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
    $mail->AddStringAttachment($pdfdoc, 'cotizacion_'.$GLOBALS["referencia"].'.pdf');

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = utf8_decode($asunto);
    $mail->Body    = utf8_decode($mensaje);

    if($mail->send())
    {
      $stmt = $conn->prepare("INSERT INTO bitacora_cotizaciones (FKUsuario, Fecha_Movimiento, FKMensaje, FKCotizacion) VALUES (:fkusuario, :fechamovimiento, :fkmensaje, :fkcotizacion)");
      $stmt->bindValue(':fkusuario',$FKUsuario);
      $stmt->bindValue(':fechamovimiento',$FechaModificacion);
      $stmt->bindValue(':fkmensaje', 8);
      $stmt->bindValue(':fkcotizacion',$id);
      $stmt->execute();  
      echo "exito";
    }
    else{
      echo "fallo - send mail";
    }

} catch (Exception $e) {
    //header('Location: ver_Cotizacion.php?id='.$id.'&estatus=2');
    echo "fallo - try catch: ".$e;
}

?>
