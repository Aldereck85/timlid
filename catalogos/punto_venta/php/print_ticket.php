<?php
    require_once 'clases.php';
    require_once '../../../lib/TCPDF/tcpdf.php' ;
    error_reporting(E_ALL & ~E_NOTICE);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);

    class PDF extends TCPDF
    {
        
        //Page header
        public function Header()
        {
            $generales = '
            <table style="width: 100%; font-family: Helvetica;">
                <tr style="text-align: center">
                    <td><img src="' . $this->logo . '" width="50"></td>
                </tr>
                <tr style="text-align: center;font-weight: bold; font-size: 6px">
                    <td>' . strtoupper($this->razon_social) . '</td>
                </tr>
                <tr style="text-align: center;font-weight: bold; font-size: 6px">
                    <td>' . strtoupper($this->dom_fiscal) . '</td>
                </tr>
            </table> 
            <p style="text-align: center;font-weight: bold; font-size: 6px">DATOS DEL CLIENTE</p>
            <table style="width: 100%; font-family: Helvetica;">
                <tr style="text-align: center;font-weight: bold; font-size: 6px">
                    <td>' . strtoupper($this->client_razon_social) . '</td>
                </tr>
                <tr style="text-align: center;font-weight: bold; font-size: 6px">
                    <td>C. P. ' . strtoupper($this->client_codigo_postal) . '</td>
                </tr>
                <tr style="text-align: center;font-weight: bold; font-size: 6px">
                    <td> RFC: ' . strtoupper($this->client_rfc) . '</td>
                </tr>
                
            </table>'
            ;
            $this->writeHTML($generales, true, false, true, false, '');
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
            $this->SetFont('helvetica', '', 14);
            $this->SetFillColor(200, 220, 255);
            $this->Cell(180, 6, 'Chapter ' . $num . ' : ' . $title, 0, 1, '', 1);

            $this->Ln(4);
        }

        public function ChapterBody($file)
        {
            $this->selectColumn();
            // get esternal file content
            $content = $file; //file_get_contents($file, false);
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
            $this->SetY(-6);
            // Set font
            $this->SetFont('Helvetica', 'N', 12);

            $footer = '';
            $this->writeHTML($footer, true, false, true, false, '');
        }
    }

    $get_data = new get_data();
    $get_print = new get_print();
    
    $value =  $_POST['value'];
    $value1 =  $_POST['value1'];
    $value2 =  $_POST['value2'];
    $date =  $_POST['date'];
    $date1 =  $_POST['date1'];
    $value3 =  $_POST['value3'];

    $data_enterpise = $get_data->getDataEnterprise();
    $client = $get_data->getDataClient($value);
    $data_products = $get_data->getProductsTicket($value,"","");
    $data_cashRegister = $get_data->getDataTicket($value,"","");
    

    $path = $_ENV['RUTA_ARCHIVOS_READ'] . $data_enterpise[0]->PKEmpresa . "/fiscales/";
    $path_logo = $path . $data_enterpise[0]->logo;


    $num_ext = $data_enterpise[0]->numero_interior !== null && $data_enterpise[0]->numero_interior !== "" ? $data_enterpise[0]->numero_interior : "";

    $domi_fiscal = 
    $data_enterpise[0]->calle !== null && $data_enterpise[0]->calle !== "" ?
        $data_enterpise[0]->calle .
        $data_enterpise[0]->numero_exterior .
        $num_ext .
        $data_enterpise[0]->codigo_postal . "<br>" .
        $data_enterpise[0]->colonia .
        $data_enterpise[0]->ciudad . 
        $data_enterpise[0]->Estado . "<br>" .
        $data_enterpise[0]->telefono : "";
    
    $dom_fiscal = $data_enterpise[0]->domicilio_fiscal !== null && $data_enterpise[0]->domicilio_fiscal !== "" ? $data_enterpise[0]->domicilio_fiscal : $domi_fiscal;

    $pdf = new PDF(PDF_PAGE_ORIENTATION, 'mm', [80,150], true, 'UTF-8', false);
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Timlid');
    $pdf->SetTitle('Ticket');
    $pdf->SetSubject('Ticket');
    $pdf->SetKeywords('Ticket');
    $pdf->logo = $path_logo;
    $pdf->razon_social = $data_enterpise[0]->RazonSocial;
    $pdf->dom_fiscal = $dom_fiscal;
    $pdf->client_razon_social = $client[0]->razon_social;
    $pdf->client_rfc = $client[0]->rfc;
    $pdf->client_codigo_postal = $client[0]->codigo_postal;
    
    // set header and footer fonts
    $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

    // set default monospaced font
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // set margins
    $pdf->SetMargins(0, PDF_MARGIN_TOP, 0);
    $pdf->SetHeaderMargin(5);
    $pdf->SetFooterMargin(5);

    // set auto page breaks
    $pdf->SetAutoPageBreak(TRUE, 0);

    // set image scale factor
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    // set some language-dependent strings (optional)
    if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
    require_once(dirname(__FILE__) . '/lang/eng.php');
    $pdf->setLanguageArray($l);
    }

    // Set font
    $pdf->SetFont('times', '', 12);

    $pdf->AddPage();
    $pdf->SetFont('Times', '', 12);

    $product_quantities = "";
    $product_names = "";
    $product_amount = "";
    $totals = "";
    $impuestos = "";

    foreach($data_products as $r)
    {
        $product_quantities .= "<tr><td>".$r->cantidad."</td></tr>";
        $product_names .= "<tr><td>".$r->nombre."</td></tr>";
        $product_amount .= "<tr><td>$".number_format($r->cantidad * $r->precio_unitario, 2)."</td></tr>";
        
    }

    $data_tax = $get_print->getFormatTicketTax($value,$date,$date1);

    for ($i=0; $i < count($data_tax); $i++) { 
        
        $impuestos .= $data_tax[$i] . "<br>";
    }
    $pdf->Ln(4);
    
    $html = 
    '   
        <table style="width: 100%; font-family: Helvetica; font-size: 6px;">
            <tr>
                <td>
                    <table style="color: #000000; width:100%;">
                        <tr>
                            <th style="width: 20%; font-size: 6px; font-weight: bold;">Cantidad</th>
                            <th style="width: 55%; font-size: 6px; font-weight: bold;">Concepto</th>
                            <th style="width: 25%; font-size: 6px; font-weight: bold;">Importe</th>
                        </tr>
                        <tr>
                            <td style="width: 20%; font-size: 6px;"><table>' .$product_quantities . '</table></td>
                            <td style="width: 55%; font-size: 6px;"><table>' .$product_names . '</table></td>
                            <td style="width: 25%; font-size: 6px;"><table>' .$product_amount . '</table></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    ';

    $pdf->writeHTML($html, true, false, true, false, '');

    
    $monto_recibido = "";
    $cambio = "";

    $value1 !== null && $value1 !== "" ? 
        $monto_recibido = '<tr>
            <td style="width: 50%; text-align: right;">Monto recibido</td>
            <td style="width: 50%; text-align: right;>$' . number_format($value1,2) . '</td>
        </tr>' : $monto_recibido = "";

    $value2 !== null && $value2 !== "" ? 
        $cambio = '<tr>
            <td style="width: 50%; text-align: right;">Cambio</td>
            <td style="width: 50%; text-align: right;>$' . number_format($value2,2) . '</td>
        </tr>' : $cambio ="";

    $html = 
    '
        <table style="width: 100%; font-family: Helvetica; font-size: 6px;">
            <tr>
                <td style="width: 60%;"></td>
                <td style="width: 40%;">
                    <table>
                        <tr>
                            <td style="width: 30%; text-align: left;">Subtotal</td>
                            <td style="width: 70%; text-align: left;">$' . number_format($data_cashRegister[0]->subtotal,2) . '</td>
                        </tr>
                        <tr>
                            <td style="width: 30%; text-align: left;">Impuestos</td>
                            <td style="width: 70%; text-align: left;">'.$impuestos.'</td>
                        </tr>
                        ' . $monto_recibido . '
                        ' . $cambio . '
                        <tr>
                            <td style="width: 30%; text-align: left;">Total</td>
                            <td style="width: 70%; text-align: left;">$' . number_format($data_cashRegister[0]->total,2) . '</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    ';

    $pdf->writeHTML($html, true, false, true, false, '');

    $pdf->writeHTML('<p style="text-align: center; font-weigth: bold;font-size: 6px; font-family: Helvetica;">By Timlid</p>', true, false, true, false, '');

    ob_end_clean();

    $pdf->Output("Prefactura " . 1 . '.pdf', 'I');

?>