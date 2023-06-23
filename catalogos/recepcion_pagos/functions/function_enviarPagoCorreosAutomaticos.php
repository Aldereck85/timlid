<?php
session_start();

$jwt_ruta = "../../../";
require_once '../../jwt.php';

//función para dar formato a las cantidades
require_once('function_formatoCantidad.php');

  require_once '../../../lib/TCPDF/tcpdf.php';
  require_once '../../../include/db-conn.php';
  require_once('../../../lib/phpmailer_configuration.php');

  $FKUsuario = $_SESSION["PKUsuario"];
  date_default_timezone_set('America/Mexico_City');
  $fecha_alta = date("Y-m-d H:i:s");

  $stmt = $conn->prepare('SELECT p.identificador_pago, 
                                  if( m.tipo_CuentaCobrar = 2, c.PKCliente, cl.PKCliente) as PKCliente, 
                                  if( m.tipo_CuentaCobrar = 2, c.razon_social, cl.razon_social) as NombreComercial, 
                                  p.fecha_pago, 
                                  if( m.tipo_CuentaCobrar = 2, f.metodo_pago, 0) as metodo_pago, 
                                  fp.id, 
                                  cu.PKCuenta, 
                                  cu.Nombre as "cuenta", 
                                  m.Referencia, 
                                  p.comentarios, 
                                  p.total, 
                                  p.forma_pago,
                                  if( m.tipo_CuentaCobrar = 2, c.Email, cl.Email) as Email  
                          from pagos as p 
                            inner join movimientos_cuentas_bancarias_empresa as m on m.id_pago=p.idpagos
                            left join facturacion as f on f.id=m.id_factura and m.tipo_CuentaCobrar = 2 and f.prefactura = 0
                            left join clientes c on f.cliente_id = c.PKCliente
                            left join ventas_directas as vd on vd.PKVentaDirecta = m.id_factura and m.tipo_CuentaCobrar = 1 and vd.empresa_id !=6
                            left join clientes cl on vd.FKCliente = cl.PKCliente
                            inner join formas_pago_sat as fp on p.forma_pago = fp.id
                            inner join cuentas_bancarias_empresa as cu on m.cuenta_destino_id = cu.PKCuenta
                          where p.idpagos=(select max(idpagos) from pagos as p where p.empresa_id='.$_SESSION['IDEmpresa'].' and p.tipo_movimiento=0 and p.estatus=1);');
  $stmt->execute();
  $row = $stmt->fetch();

  switch($row['metodo_pago']){
    case "1":
        $GLOBALS['MetodoPago']= 'Pago en Una Exhibición';
    break;
    case "2":
        $GLOBALS['MetodoPago']= 'Pago Inicial y Parcialidades'; 
    break;
    case "3":
        $GLOBALS['MetodoPago']= 'Pago en Parcialidades o Diferido'; 
    break; 
    case "0":
        $GLOBALS['MetodoPago']= 'Sin método'; 
    break;                 
  }

  $GLOBALS["PKCliente"] = $row['PKCliente'];
  $GLOBALS["FolioPago"] = $row['identificador_pago'];
  $GLOBALS["FechaPago"] = $row['fecha_pago'];
  $GLOBALS["Observaciones"] = $row['comentarios'];
  $GLOBALS["Cliente"] = $row['NombreComercial'];
  $GLOBALS["Cuenta"] = $row['Nombre'];
  $GLOBALS["Referencia"] = $row['Referencia'];
  $GLOBALS['Total'] = formatoCantidad($row['total']);
  $GLOBALS['FormaPago'] = $row['forma_pago'];
  $GLOBALS['EmailCliente'] = $row['Email'];


  $mensaje = $mensaje."<br><br>"."Se te ha enviado el pago ".$GLOBALS["FolioPago"];

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
          $this->Cell(30, 10, 'Pago: ' . $GLOBALS["FolioPago"], 0);
          $image_file = $GLOBALS["ruta"].$GLOBALS["logotipo"];
          $this->Image($image_file, 150, 9, 45);

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
  $pdf->SetTitle('Pago');
  $pdf->SetSubject('Pago');
  $pdf->SetKeywords('Pago');
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
      <th width="30%">Cliente</th>
      <th width="50%" style="background-color: none;"></th>
      <th width="20%">Cliente</th>
    </tr>
    <tr>
      <td width="30%">' . $GLOBALS['Cliente'] . '</td>
      <td width="50%" style="background-color: none;"></td>
      <td width="20%">'.$GLOBALS['FechaPago'].'</td>
    </tr>
  </table>';
  $pdf->writeHTML($tblenc, true, false, true, false, '');

  $tblMetodoPago = '
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
      <th width="20%">Método de pago</th>
      <th width="20%" style="background-color: none;"></th>
      <th width="20%">Forma de pago</th>
      <th width="20%" style="background-color: none;"></th>
      <th width="20%">Cuenta</th>
    </tr>
    <tr>
      <td width="20%">' . $GLOBALS['MetodoPago'] . '</td>
      <td width="20%" style="background-color: none;"></td>
      <td width="20%">'.$GLOBALS['FormaPago'].'</td>
      <td width="20%" style="background-color: none;"></td>
      <td width="20%">'.$GLOBALS['Cuenta'].'</td>
    </tr>
  </table>';
  $pdf->writeHTML($tblMetodoPago, true, false, true, false, '');

  $tblTotal = '
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
      <th width="30%">Referencia</th>
      <th width="50%" style="background-color: none;"></th>
      <th width="20%">Total</th>
    </tr>
    <tr>
      <td width="30%">' . $GLOBALS['Referencia'] . '</td>
      <td width="50%" style="background-color: none;"></td>
      <td width="20%">'.$GLOBALS['Total'].'</td>
    </tr>
  </table>';
  $pdf->writeHTML($tblTotal, true, false, true, false, '');

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
          <th width="10%">Serie</th>
          <th width="10%">Folio</th>
          <th width="10%">Cliente</th>
          <th width="10%">F. Expedición</th>        
          <th width="10%">F. Vencimiento</th> 
          <th width="10%">Monto factura</th>
          <th width="10%">Saldo anterior</th>
          <th width="10%">Importe pago</th>
          <th width="10%">Saldo insoluto</th>        
          <th width="10%">No. Parcialidad</th>   
        </tr>
      </thead>
      <tbody>
  ';

  $stmt = $conn->prepare('SELECT f.serie as "Serie", f.folio as "Folio",  c.razon_social AS "Nombre Comercial", f.fecha_timbrado as "Fecha de facturacion", date_add(f.fecha_timbrado, interval c.Dias_credito day)  as "Fecha de vencimiento",  
  f.total_facturado as "Monto factura", m.saldo_anterior, m.saldo_insoluto, m.parcialidad, f.id as "id",f.metodo_pago, m.Deposito FROM facturacion as f
  inner join clientes as c on f.cliente_id=c.PKCliente 
  left join movimientos_cuentas_bancarias_empresa as m on m.id_factura=f.id 
  inner join pagos as p on p.idpagos=m.id_pago
  where f.empresa_id='.$_SESSION["IDEmpresa"].' and f.estatus not in (4,5)  and p.identificador_pago="'.$GLOBALS["FolioPago"].'" and p.tipo_movimiento=0 and p.estatus=1 and f.prefactura = 0 group by m.id_factura;');
  $stmt->execute();
  $rowp = $stmt->fetchAll();

  $x = 0;

  foreach ($rowp as $rp) {

    $tbl .= '<tr>
              <td width="10%">' . $rp['Serie'] . '</td>
              <td width="10%">' . $rp['Folio'] . '</td>
              <td width="10%">' . $rp['Nombre Comercial'] .'</td>
              <td width="10%">' . $rp['Fecha de facturacion'] . '</td>
              <td width="10%">' . $rp['Fecha de vencimiento'] . '</td>
              <td width="10%">' . $rp['Monto factura'] . '</td>
              <td width="10%">' . $rp['saldo_anterior'] . '</td>
              <td width="10%">' . $rp['Deposito'] .'</td>
              <td width="10%">' . $rp['saldo_insoluto'] . '</td>
              <td width="10%">' . $rp['parcialidad'] . '</td>
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

  ob_end_clean();
  $pdfdoc = $pdf->Output('pago_' . $GLOBALS["FolioPago"] . '.pdf', 'S');


  // send message
  $stmt = $conn->prepare('SELECT RazonSocial FROM empresas WHERE PKEmpresa = '.$_SESSION['IDEmpresa']);
  $stmt->execute();
  $rowemp = $stmt->fetch();

  $stmt = $conn->prepare("SELECT valor FROM parametros_servidor WHERE parametro = 'email_contacto'");
  $stmt->execute();
  $url = $stmt->fetch();
  $email_origen = $_ENV['ORIGEN_MAIL'] ?? "no-reply@timlid.com";

  $query = sprintf("SELECT 
                  cl.PKCliente as id,
                  dcc.Email as email,
                  dcc.EmailPagos
                FROM
                clientes cl
                LEFT JOIN dato_contacto_cliente dcc ON dcc.FKCliente = cl.PKCliente
                WHERE cl.PKCliente=:id
                AND dcc.EmailPagos=1
              ");
  $stmt = $conn->prepare($query);
  $stmt->bindValue(":id", $GLOBALS["PKCliente"]);
  $stmt->execute();
  $row = $stmt->fetchAll();    

  try {    
    $mail->Sender = $email_origen;
    $mail->setFrom($email_origen, $rowemp['RazonSocial']);
    for($i=0;$i<count($row);$i++){
      $mail->addAddress($row[$i]['email']);     //Add a recipient
    }
    $mail->addAddress($GLOBALS['EmailCliente']);     //Add a recipient
    //Attachments
    $mail->AddStringAttachment($pdfdoc, 'pago_'.$GLOBALS["FolioPago"].'.pdf');

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = utf8_decode('Pago '.$GLOBALS["FolioPago"]);
    $mail->Body    = utf8_decode('Usted está recibiendo un pago nuevo');

    $mail->send();

  } catch (Exception $e) {
      //header('Location: ver_Cotizacion.php?id='.$id.'&estatus=2');
      echo "fallo try catch".$e;
  }

?>
