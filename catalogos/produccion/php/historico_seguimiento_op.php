<?php
    
    require_once('../../../lib/TCPDF/tcpdf.php');
    require_once('clases_copy.php');

    $get_data = new get_data();

    $production_order_id = isset($_POST['id']) ? $_POST['id'] : "";
    $tranking_production_order_id = isset($_POST['id1']) ? $_POST['id1'] : "";

    $company_data = $get_data->getDataEnterprise();
    $production_order = $get_data->getDetailsDataProductionOrder($production_order_id);
    
    if($tranking_production_order_id !== ""){
        $tracing_production_order = json_decode($get_data->getManufacturingHistoryById($tranking_production_order_id));
        $GLOBALS["title"] = "Seguimiento orden produccion";
    } else {
        $tracing_production_order = json_decode($get_data->getManufacturingHistory($production_order_id));
        $GLOBALS["title"] = "Histórico orden produccion";
    }
    
    $data_tracing = $get_data->getDataProductionOrderTracking($production_order_id);

    $lastRow = count($tracing_production_order->data)-1;
    
    $GLOBALS["logotipo"] = $company_data[0]['logo'];
    $GLOBALS["Empresa"] = $company_data[0]['RazonSocial'];
    $GLOBALS["Product_key"] = $production_order['clave'];
    $GLOBALS["Product_name"] = $production_order['producto'];
    $GLOBALS["Product_quantity"] = $production_order['cantidad'];
    $GLOBALS["FolioProductionOrder"] = $production_order['folio'];
    $GLOBALS["creador"] = $tracing_production_order->data[$lastRow]->usuario_registro;
    $GLOBALS['responsable'] = $production_order['responsable'];
    $GLOBALS["FechaCreacion"] = $tracing_production_order->data[$lastRow]->fecha_captura;
    $GLOBALS["FechaTermino"] = $tracing_production_order->data[$lastRow]->fecha_termino;
    $GLOBALS["grupo_trabajo"] = $tracing_production_order->data[$lastRow]->grupo_trabajo;
    $GLOBALS["cantidad_fabricada"] = $tracing_production_order->data[$lastRow]->cantidad_termina;
    $GLOBALS["lote"] = $tracing_production_order->data[$lastRow]->lote;
    $GLOBALS["usuario_registro"] = $tracing_production_order->data[$lastRow]->usuario_registro;
    $GLOBALS['cantidad_faltante'] =  $data_tracing[0]->cantidad_faltante;
    $GLOBALS['estatus'] = $production_order['estatus'];
    $GLOBALS['notas'] = $production_order['notas'];

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
        $image_file = $GLOBALS["logotipo"];
        $this->Image($image_file, 10, 10, 32, 12, '', '', 'T', false, 300, '', false, false, 0, false, false, false);
        // Set font
        $this->SetFont('Helvetica', 'B', 16);
        // Title
        // set cell padding
        $this->setCellPaddings(1, 1, 1, 1);
        // set cell margins
        $this->setCellMargins(1, 1, 1, 1);
        // MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0)
        $this->MultiCell(100, 10,$GLOBALS["title"], 0, 'C', 0, 0, 45, 10, true);

        // Set font
        $this->SetFont('Helvetica', 'B', 9);
        // Title
        // set cell padding
        $this->setCellPaddings(1, 1, 1, 1);
        // set cell margins
        $this->setCellMargins(1, 1, 1, 1);
        $this->MultiCell(45, 5, 'Orden de producción', 1, 'C', 0, 1, 150, 10, true);
        $this->MultiCell(45, 5, '#'.$GLOBALS["FolioProductionOrder"], 1, 'C', 0, 1, 150, 16, true);
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

    $pdf = new PDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
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
        <td class="td3 td4" width="20%">Producto a fabricar: </td>
        <td class="td3" width="35%">' . $GLOBALS["Product_key"] . ' ' . $GLOBALS["Product_name"] . '</td>
        <td class="td3 td4" width="20%">Responsable: </td>
        <td class="td3" width="35%">' . $GLOBALS['responsable'] . '</td>
    </tr>

    <tr>
        <td class="td3 td4" width="20%">Cantidad a fabricar: </td>
        <td class="td3" width="35%">' . $GLOBALS["Product_quantity"] . '</td>
        <td class="td3 td4" width="20%">Cantidad faltante: </td>
        <td class="td3" width="35%">' . $GLOBALS['cantidad_faltante'] . '</td>
    </tr>';
    if($GLOBALS['notas'] !==  "" && $GLOBALS['notas'] !== null){
        $fechaSalida .= '<tr>
            <td class="td3 td4" width="10%">Notas:</td>
            <td class="td3" width="90%">' . $GLOBALS['notas'] .'</td>
        </tr>';
    }
    $fechaSalida .= '</table>';
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
        vertical-align: middle;
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
        <tr>
            <th class="center_td" width="15%">Fecha de captura</th>
            <th class="center_td" width="18%">Grupo de trabajo</th>
            <th class="center_td" width="15%">Fecha de fabricacion</th>
            <th class="center_td" width="15%">Cantidad fabricada</th>
            <th class="center_td" width="10%">Lote</th>
            <th class="center_td" width="27%">Usuario</th>
        </tr>
        </thead>
        <tbody>
    ';

    if((int)$GLOBALS['estatus'] !== 4){

    $tbl .=  '<tr>
                <td width="15%">'.$GLOBALS["FechaCreacion"].'</td>
                <td width="18%">'.$GLOBALS["grupo_trabajo"].'</td>
                <td width="15%">'.$GLOBALS["FechaTermino"].'</td>
                <td width="15%">'.$GLOBALS["cantidad_fabricada"].'</td>
                <td width="10%">'.$GLOBALS["lote"].'</td>
                <td width="27%">'.$GLOBALS["usuario_registro"].'</td>
                
            </tr>';
   
    } else {
        foreach($tracing_production_order->data as $r){
            $tbl .=  
                '<tr>
                    <td width="15%">'.$r->fecha_captura.'</td>
                    <td width="18%">'.$r->grupo_trabajo.'</td>
                    <td width="15%">'.$r->fecha_termino.'</td>
                    <td width="15%">'.$r->cantidad_termina.'</td>
                    <td width="10%">'.$r->lote.'</td>
                    <td width="27%">'.$r->usuario_registro.'</td>  
                </tr>';
        }
    }
    $tbl.= '</tbody></table>';
    $pdf->writeHTML($tbl, true, false, true, false, '');

    $pdf->Ln(25);

    if($tranking_production_order_id !== "")
    {
        $tbl = 
            '<table>
                <thead>
                    <tr>
                        <th width="30%" style="text-align:center;">Entregó</th>
                        <th width="40%"></th>
                        <th width="30%" style="text-align:center;">Recibió</th>
                    </tr>
                </thead >
                <tbody>
                    <tr>
                        <td width="30%"></td>
                        <td width="40%"></td>
                        <td width="30%"></td>
                    </tr>
                    <tr>
                        <td width="30%"></td>
                        <td width="40%"></td>
                        <td width="30%"></td>
                    </tr>
                    <tr>
                        <td width="30%"></td>
                        <td width="40%"></td>
                        <td width="30%"></td>
                    </tr>
                    
                    <tr>
                        <td width="30%" style="text-align:center;border-top: 1px solid #b2b2b2">Nombre y firma</td>
                        <td width="40%"></td>
                        <td width="30%" style="text-align:center;border-top: 1px solid #b2b2b2">Nombre y firma</td>
                    </tr>
                </tbody>
            </table>';
        
        $pdf->writeHTML($tbl, true, false, true, false, '');
    }

    ob_end_clean();
    

    $pdf->Output("Seguimiento orden produccion No. ".$GLOBALS["FolioProductionOrder"]. '.pdf', 'I');
    
?>