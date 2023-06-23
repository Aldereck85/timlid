<?php
  session_start();
  require_once('../../../lib/TCPDF/tcpdf.php');
  //require_once('../../../include/db-conn.php');
  require_once('../../../lib/phpmailer_configuration.php');

  $folio = $_POST['folioyserie'];
  $razon_social = $_POST['razon_social'];
  $fecha = $_POST['fecha'];
  $cfdi = $_POST['cfdi'];
  $forma_pago = $_POST['forma_pago'];
  $metodo_pago = $_POST['metodo_pago'];
  $moneda = $_POST['moneda'];
  $rfc = $_POST['rfc'];
  $productos = json_decode($_POST['productos']);
  $subtotal = $_POST['subtotal'];
  $impuestos = $_POST['impuestos'];
  $total1 = $_POST['total'];
  $email = $_POST['email'];
  $mensaje = $_POST['mensaje'];
  //$id_company = $_SESSION['IDEmpresa'];

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
      require_once('../../../include/db-conn.php');
      $db = $conn;
      $id_company = $_SESSION['IDEmpresa'];
      $query = sprintf("select logo from empresas where PKEmpresa = :id");
      $stmt = $db->prepare($query);
      $stmt->bindValue(":id",$id_company);
      $stmt->execute();
      $logo = $stmt->fetchAll();
      $this->Cell(0, 0, '', 0, 1, 'C', 0, '', 0);
      $this->SetFont('Helvetica', '', 18);
      $this->Cell(30, 10, 'Prefactura: ', 0);
      $image_file = isset($_ENV['RUTA_ARCHIVOS_READ']) ? $_ENV['RUTA_ARCHIVOS_READ'] . $id_company . "/fiscales/" . $logo[0]['logo'] : "/home/timlid/public_html/app-tim/file_server/" . $id_company . "/fiscales/" . $logo[0]['logo'];
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

        $footer = '';
        $this->writeHTML($footer, true, false, true, false, '');

    }
  }

  $pdf = new PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
  $pdf->SetCreator(PDF_CREATOR);
  $pdf->SetAuthor('Timlid');
  $pdf->SetTitle('Prefactura');
  $pdf->SetSubject('Prefactura');
  $pdf->SetKeywords('Prefactura');
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

  // Set font
  $pdf->SetFont('times', '', 12);

  $pdf->AddPage();
  $pdf->SetFont('Times','',12);
  $total = 0;

  error_reporting(E_ALL & ~E_NOTICE);
  ini_set('display_errors', 0);
  ini_set('log_errors', 1);

  $generales = '
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
      <th width="15%">Serie y folio</th>
      <th width="5%" style="background-color: none;"></th>
      <th width="15%">Fecha</th>
      <th width="5%" style="background-color: none;"></th>
      <th width="15%">RFC</th>
      <th width="5%" style="background-color: none;"></th>
      <th width="40%">Cliente</th>
    </tr>
    <tr>
      <td class="td1" width="15%">
      ' . $folio . '
      </td>
      <td width="5%" style="background-color: none;"></td>
      <td class="td1" width="15%">
        ' . date("d/m/Y",strtotime($fecha)) . '
      </td>
      <td width="5%" style="background-color: none;"></td>
      <td class="td1" width="15%">
        ' . $rfc . '
      </td>
      <td width="5%" style="background-color: none;"></td>
      <td class="td1" width="40%">
        ' . $razon_social . '
      </td>
    </tr>
  </table>';
  $pdf->writeHTML($generales, true, false, true, false, '');

  $fiscales1 = '
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
        <th width="45%">Uso CFDI</th>
        <th width="10%" style="background-color: none;"></th>
        <th width="45%">Forma de pago</th>
      </tr>
      <tr>
        <td class="td1" width="45%">
          ' . $cfdi . '
        </td>
        <td width="10%" style="background-color: none;"></td>
        <td class="td1" width="45%">
          ' . $forma_pago . '
        </td>
      </tr>
    </table>
  ';

  $pdf->writeHTML($fiscales1, true, false, true, false, '');

  $fiscales2 = '
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
      <th width="45%">Método de pago</th>
      <th width="10%" style="background-color: none;"></th>
      <th width="45%">Moneda</th>
    </tr>
    <tr>
      <td class="td1" width="45%">
        ' . $metodo_pago . '
      </td>
      <td width="10%" style="background-color: none;"></td>
      <td class="td1" width="45%">
        ' . $moneda . '
      </td>
    </tr>
  </table>
  ';
  
  $pdf->writeHTML($fiscales2, true, false, true, false, '');

  $productos1 = '
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
        <tr>
          <th width="9%">Clave</th>
          <th width="24%">Producto</th>
          <th width="15%">U. medida</th>
          <th width="13%">Cantidad</th>
          <th width="13%">Precio</th>
          <th width="14%">Impuestos</th>
          <th width="12%">Importe</th>
        </tr>
      </thead>
    <tbody>
  ';
  
  foreach($productos as $r){
    $productos1 .= '
      <tr>
        <td class="td1" width="9%">
          ' . $r->clave . '
        </td>
        <td class="td1" width="24%">
          ' . $r->producto . '
        </td>
        <td class="td1" width="15%">
          ' . $r->u_medida . '
        </td>
        <td class="td1" width="13%">
          ' . $r->cantidad . '
        </td>
        <td class="td1" width="13%">
          ' . $r->precio . '
        </td>
        <td class="td1" width="14%">
          ' . $r->impuestos . '
        </td>
        <td class="td1" width="12%">
          ' . $r->total . '
        </td>
      </tr>
    ';
  }

  $productos1 .= '
    </tbody>
  </table>';

  $pdf->writeHTML($productos1, true, false, true, false, '');

  $totales = '
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
      <td class="td1" width="65%" style="background-color: none;border-bottom: 1px solid #fff; border-top: 1px solid #fff;"></td>
      <td class="td1" width="21%">Subtotal:</td>
      <td class="td1" width="14%" style="text-align: right;">
        ' . $subtotal . '
      </td>
    </tr>
    <tr>
      <td class="td1" width="65%" style="background-color: none;border-bottom: 1px solid #fff; border-top: 1px solid #fff;"></td>
      <td class="td1" width="21%">Impuestos:</td>
      <td class="td1" style="text-align: right;" width="14%" height="30px">
        ' . $impuestos . '
      </td>
    </tr>
    <tr>
      <td class="td1" width="65%" style="background-color: none;border-bottom: 1px solid #fff; border-top: 1px solid #fff;"></td>
      <td class="td1" width="21%">Total:</td>
      <td class="td1" style="text-align: right;" width="14%" height="30px">
        ' . $total1 . '
      </td>
    </tr>
  </table>
  ';

  $pdf->writeHTML($totales, true, false, true, false, '');

  ob_end_clean();

  $auxpdf = $pdf->Output("Prefactura ".$folio. '.pdf', 'S');

  




  function sendEmail($user, $motivo, $pdf, $folio){
      
    require('../../../lib/phpmailer_configuration.php');

    try {
        $origen = $_ENV['ORIGEN_MAIL'] ?? "no-reply@timlid.com";
        $usuario_envia = "Timlid";
        $mail->Sender = $origen;
        $mail->setFrom($origen, $usuario_envia);
        $mail->addReplyTo($origen, $usuario_envia);
        $mail->addAddress($user);     //Add a recipient  $user

        $mensaje = "Aviso de envío de prefactura";         
        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = utf8_decode("Timlid - Aviso de envío de prefactura");
        $mail->Body    = utf8_decode($motivo);
        
        $mail->AddStringAttachment($pdf, $folio . '.pdf');
        
        if($mail->send())
        {
            return true;
        }
        else{
            return false;
        }

    } catch (Exception $e) {
        //header('Location: ver_Cotizacion.php?id='.$id.'&estatus=2');
        return false;
    }
    
  }

  return sendEmail($email, $mensaje, $auxpdf, $folio);
?>