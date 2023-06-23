<?php
session_start();

$jwt_ruta = "../../../";
require_once '../../jwt.php';

$idNominaEmpleado = $_POST['idNominaEmpleado'];
$token = $_POST["csr_token_UT5JP"];

if(!empty($_SESSION['token_ld10d'])) {
    if (!hash_equals($_SESSION['token_ld10d'], $token)) {
        header('Location: ../');
        exit;
    }
    else{

        require_once '../../../lib/TCPDF/tcpdf.php';
        require_once '../../../include/db-conn.php';

        $stmt = $conn->prepare("SELECT ne.*,n.*, np.tipo_id, DATE_FORMAT(n.fecha_inicio, '%d/%m/%Y') as fechaInicio, DATE_FORMAT(n.fecha_fin, '%d/%m/%Y') as fechaFin, DATE_FORMAT(n.fecha_pago, '%d/%m/%Y') as fechaPago, e.PKEmpleado, e.Nombres, e.PrimerApellido, e.SegundoApellido,dle.FechaIngreso, DATE_FORMAT(dle.FechaIngreso, '%d/%m/%Y') as FechaIngresoFormat, e.RFC, dme.NSS, p.puesto,t.Turno, dle.Sueldo,pp.DiasPago, s.sucursal, pp.Periodo 
          FROM nomina_empleado as ne 
          INNER JOIN empleados as e ON e.PKEmpleado = ne.FKEmpleado 
          INNER JOIN datos_laborales_empleado as dle ON dle.FKEmpleado = e.PKEmpleado 
          INNER JOIN periodo_pago as pp ON pp.PKPeriodo_pago = dle.FKPeriodo 
          LEFT JOIN datos_medicos_empleado as dme ON dme.FKEmpleado = e.PKEmpleado 
          LEFT JOIN puestos as p ON p.id = dle.FKPuesto LEFT JOIN turnos as t ON t.PKTurno = dle.FKTurno 
          LEFT JOIN nomina as n ON n.id = ne.FKNomina LEFT JOIN nomina_principal as np ON np.id = n.fk_nomina_principal 
          LEFT JOIN sucursales as s ON s.id = np.sucursal_id 
          WHERE ne.PKNomina = :idNominaEmpleado");
        $stmt->bindValue(':idNominaEmpleado',$idNominaEmpleado);
        $stmt->execute();
        $datosNomina = $stmt->fetch();
        $nombreEmpleado = $datosNomina['Nombres'].' '.$datosNomina['PrimerApellido'].' '.$datosNomina['SegundoApellido'];
        $idEmpleado = $datosNomina['empleado_id'];
        //print_r($datosFiniquito);

        $fechahoy = date("d/m/Y");

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
                $this->Cell(30, 10, 'Nómina', 0);
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
                // Page number
                $this->Cell(0, 10, $this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
            }

        }

        $pdf = new PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Timlid'
        );
        $pdf->SetTitle('Nomina');
        $pdf->SetSubject('Nomina');
        $pdf->SetKeywords('Nomina');
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
        $pdf->SetFont('Helvetica', '', 11);
        $total = 0;
/*
        error_reporting(E_ALL & ~E_NOTICE);
        ini_set('display_errors', 0);
        ini_set('log_errors', 1);
*/
        $titulo = "NOMINA NO.".$datosNomina['no_nomina'];
        $cadena = str_replace(" ", "_", trim($nombreEmpleado));
        $titulo_archivo = "nomina_".$cadena;

        if($datosNomina['tipo_id'] == 1){
          $tipo_nomina = "Ordinaria";
        }
        else{
          $tipo_nomina = "Extraordinaria";
        }

        $encabezado = '
          <table>
            <tr>
              <th width="100%" style="font-size: 14px;text-align: center;"><b>'.$titulo.'</b></th>
            </tr>
            <tr>
              <td width="100%"></td>
            </tr>
            <tr>
              <td width="20%"><b>Nombre: </b></td>
              <td width="80%">'.$nombreEmpleado.'</td>
            </tr>
            <tr>
              <td width="15%"><b>Fecha inicio: </b></td>
              <td width="15%">'.$datosNomina['fechaInicio'].'</td>
              <td width="15%"><b>Fecha fin: </b></td>
              <td width="15%">'.$datosNomina['fechaInicio'].'</td>
              <td width="15%"><b>Fecha pago: </b></td>
              <td width="15%">'.$datosNomina['fechaPago'].'</td>
            </tr>
            <tr>
              <td width="15%"><b>Tipo nómina: </b></td>
              <td width="15%">'.$tipo_nomina.'</td>
              <td width="15%"><b>Sucursal: </b></td>
              <td width="15%">'.$datosNomina['sucursal'].'</td>
              <td width="15%"><b>Periodicidad: </b></td>
              <td width="15%">'.$datosNomina['Periodo'].'</td>
            </tr>
            <tr>
              <td width="15%"><b>Turno: </b></td>
              <td width="15%">'.$datosNomina['Turno'].'</td>
              <td width="15%"><b>NSS: </b></td>
              <td width="15%">'.$datosNomina['NSS'].'</td>
              <td width="15%"><b>Puesto: </b></td>
              <td width="15%">'.$datosNomina['puesto'].'</td>
            </tr>
            <tr>
              <td width="15%"><b>RFC: </b></td>
              <td width="35%">'.$datosNomina['RFC'].'</td>
              <td width="15%"><b>Fecha de ingreso: </b></td>
              <td width="35%">'.$datosNomina['FechaIngresoFormat'].'</td>
            </tr>
          </table>';
        $pdf->writeHTML($encabezado, true, false, true, false, '');

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
          .centrar{
            text-align: center;
          }
          .izquierda{
            text-align: left;
          }
          td {
            font-size: 9px;
            padding: 4px;
            background-color: #f0f0f0;
            color: #000000;
            vertical-align: center;
            text-align: right;
            line-height: 20px;
            border: 1px solid #ffffff;
            border-collapse: collapse;
          }
        </style>
        <table>
          <tr>
            <th width="40%">Concepto</th>
            <th width="20%">Gravado</th>
            <th width="20%">Exento</th>
            <th width="20%">Importe</th>
          </tr>
          <tr>
            <td width="100%" class="centrar"><b>PERCEPCIONES</b></td>
          </tr>';

          //PERCEPCIONES
          $stmt = $conn->prepare("SELECT * 
              FROM detalle_nomina_percepcion_empleado as dnpe 
              INNER JOIN relacion_tipo_percepcion as rtp ON rtp.tipo_percepcion_id = dnpe.relacion_tipo_percepcion_id AND rtp.empresa_id = :empresa_id 
              INNER JOIN relacion_concepto_percepcion as rcp ON dnpe.relacion_concepto_percepcion_id = rcp.id 
              INNER JOIN tipo_percepcion as tp ON tp.id = rtp.tipo_percepcion_id 
              WHERE dnpe.nomina_empleado_id = :idNominaEmpleado");
          $stmt->bindValue(':empresa_id',$_SESSION['IDEmpresa']);
          $stmt->bindValue(':idNominaEmpleado',$idNominaEmpleado);
          $stmt->execute();
          $datosPercepciones = $stmt->fetchAll();
          //echo "<pre>",print_r($datosPercepciones),"</pre>";

          $total_percepciones = 0.00; $total_percepciones_gravado = 0.00; $total_percepciones_exento = 0.00;
          $total_deducciones = 0.00;
          $total_otros_pagos = 0.00;
          
          if($datosNomina['SAE'] > 0){

            $tblenc .= '
              <tr>
                <td width="40%" class="izquierda">Subsidio al empleo</td>
                <td width="20%">0.00</td>
                <td width="20%">0.00</td>
                <td width="20%">' . number_format($datosNomina['SAE'],2,'.',',') . '</td>
              </tr>';

            $total_percepciones = $total_percepciones + $datosNomina['SAE'];
          }

          foreach($datosPercepciones as $dp){

            $total_concepto = $dp['importe'] + $dp['importe_exento'];
            $tblenc .= '
                    <tr>
                      <td width="40%" class="izquierda">'.$dp['clave']." - ".$dp['codigo']." - ".$dp['concepto_nomina'].'</td>
                      <td width="20%">' . number_format($dp['importe'],2,'.',',') . '</td>
                      <td width="20%">' . number_format($dp['importe_exento'],2,'.',',') . '</td>
                      <td width="20%">' . number_format($total_concepto,2,'.',',') . '</td>
                    </tr>';

            $total_percepciones = $total_percepciones + $total_concepto;
            $total_percepciones_gravado = $total_percepciones_gravado + $dp['importe'];
            $total_percepciones_exento = $total_percepciones_exento + $dp['importe_exento'];
          }

          $tblenc .= '
                    <tr>
                      <td width="40%" class="izquierda">TOTAL PERCEPCIONES</td>
                      <td width="20%">' . number_format($total_percepciones_gravado,2,'.',',') . '</td>
                      <td width="20%">' . number_format($total_percepciones_exento,2,'.',',') . '</td>
                      <td width="20%">' . number_format($total_percepciones,2,'.',',') . '</td>
                    </tr>
                    <tr>
                      <td width="100%"></td>
                    </tr>
                    <tr>
                      <td width="100%" class="centrar"><b>OTROS PAGOS</b></td>
                    </tr>
                    <tr>
                      <th width="40%">Concepto</th>
                      <th width="20%">Gravado</th>
                      <th width="20%">Exento</th>
                      <th width="20%">Importe</th>
                    </tr>';
          //PERCEPCIONES


          //OTROS PAGOS
          $stmt = $conn->prepare("SELECT * 
            FROM detalle_otros_pagos_nomina_empleado as dnope 
            INNER JOIN otros_pagos as op ON op.id = dnope.otros_pagos_id 
            INNER JOIN relacion_concepto_otros_pagos as rcop ON dnope.relacion_concepto_otros_pagos_id = rcop.id AND rcop.empresa_id = :empresa_id 
            WHERE dnope.nomina_empleado_id = :idNominaEmpleado ");
          $stmt->bindValue(':empresa_id',$_SESSION['IDEmpresa']);
          $stmt->bindValue(':idNominaEmpleado',$idNominaEmpleado);
          $stmt->execute();
          $datosOtrosPagos = $stmt->fetchAll();
          //echo "<pre>",print_r($datosOtrosPagos),"</pre>";
          
          foreach($datosOtrosPagos as $dop){

            $tblenc .= '
                    <tr>
                      <td width="40%" class="izquierda">'.$dop['codigo']." - ".$dop['codigo']." - ".$dop['concepto_nomina'].'</td>
                      <td width="20%">' . number_format($dop['importe'],2,'.',',') . '</td>
                      <td width="20%">0.00</td>
                      <td width="20%">' . number_format($dop['importe'],2,'.',',') . '</td>
                    </tr>';

            $total_otros_pagos = $total_otros_pagos + $dop['importe'];
          }

          $tblenc .= '
                    <tr>
                      <td width="40%" class="izquierda">TOTAL OTROS PAGOS</td>
                      <td width="20%"></td>
                      <td width="20%"></td>
                      <td width="20%">' . number_format($total_otros_pagos,2,'.',',') . '</td>
                    </tr>
                    <tr>
                      <td width="100%"></td>
                    </tr>
                    <tr>
                      <td width="100%" class="centrar"><b>DEDUCCIONES</b></td>
                    </tr>
                    <tr>
                      <th width="40%">Concepto</th>
                      <th width="20%">Gravado</th>
                      <th width="20%">Exento</th>
                      <th width="20%">Importe</th>
                    </tr>';


          //OTROS PAGOS


          //DEDUCCIONES
          $stmt = $conn->prepare("SELECT * FROM detalle_nomina_deduccion_empleado as dnde INNER JOIN relacion_tipo_deduccion as rtd ON rtd.tipo_deduccion_id = dnde.relacion_tipo_deduccion_id AND rtd.empresa_id = :empresa_id INNER JOIN relacion_concepto_deduccion as rcd ON dnde.relacion_concepto_deduccion_id = rcd.id INNER JOIN tipo_deduccion as td ON td.id = rtd.tipo_deduccion_id WHERE dnde.nomina_empleado_id = :idNominaEmpleado ");
          $stmt->bindValue(':empresa_id',$_SESSION['IDEmpresa']);
          $stmt->bindValue(':idNominaEmpleado',$idNominaEmpleado);
          $stmt->execute();
          $datosDeducciones = $stmt->fetchAll();


          if($datosNomina['ISR'] > 0){

            $stmt = $conn->prepare("SELECT tp.codigo, tp.concepto, rtd.clave FROM tipo_deduccion as tp INNER JOIN relacion_tipo_deduccion as rtd ON rtd.tipo_deduccion_id = tp.id AND rtd.empresa_id = ".$_SESSION['IDEmpresa']." WHERE tp.id = 2 ");
            $stmt->execute();
            $datosISR = $stmt->fetch();

            $tblenc .= '
                    <tr>
                      <td width="40%" class="izquierda">'.$datosISR['clave']." - ".$datosISR['codigo']." - ".$datosISR['concepto'].'</td>
                      <td width="20%">' . number_format($datosNomina['ISR'],2,'.',',') . '</td>
                      <td width="20%">0.00</td>
                      <td width="20%">' . number_format($datosNomina['ISR'],2,'.',',') . '</td>
                    </tr>';
            $total_deducciones = $total_deducciones + $datosNomina['ISR'];
          }

          if($datosNomina['cuotaIMSS'] > 0){

            $stmt = $conn->prepare("SELECT tp.codigo, tp.concepto, rtd.clave FROM tipo_deduccion as tp INNER JOIN relacion_tipo_deduccion as rtd ON rtd.tipo_deduccion_id = tp.id AND rtd.empresa_id = ".$_SESSION['IDEmpresa']." WHERE tp.id = 1 ");
            $stmt->execute();
            $datosIMSS = $stmt->fetch();

            $tblenc .= '
                    <tr>
                      <td width="40%" class="izquierda">'.$datosIMSS['clave']." - ".$datosIMSS['codigo']." - ".$datosIMSS['concepto'].'</td>
                      <td width="20%">' . number_format($datosNomina['cuotaIMSS'],2,'.',',') . '</td>
                      <td width="20%">0.00</td>
                      <td width="20%">' . number_format($datosNomina['cuotaIMSS'],2,'.',',') . '</td>
                    </tr>';

            $total_deducciones = $total_deducciones + $datosNomina['cuotaIMSS'];
          }

          foreach($datosDeducciones as $dd){

            $tblenc .= '
                    <tr>
                      <td width="40%" class="izquierda">'.$dd['clave']." - ".$dd['codigo']." - ".$dd['concepto_nomina'].'</td>
                      <td width="20%">' . number_format($dd['importe'],2,'.',',') . '</td>
                      <td width="20%">0.00</td>
                      <td width="20%">' . number_format($dd['importe'],2,'.',',') . '</td>
                    </tr>';

            $total_deducciones = $total_deducciones + $dd['importe'];
          }

          $total_neto = $total_percepciones + $total_otros_pagos - $total_deducciones;

          $tblenc .= '
                    <tr>
                      <td width="40%" class="izquierda">TOTAL DEDUCCIONES</td>
                      <td width="20%"></td>
                      <td width="20%"></td>
                      <td width="20%">' . number_format($total_deducciones,2,'.',',') . '</td>
                    </tr>
                    <tr>
                      <td width="100%"></td>
                    </tr>
                    <tr>
                      <td width="40%" class="izquierda">TOTAL NETO</td>
                      <td width="20%"></td>
                      <td width="20%"></td>
                      <td width="20%">' . number_format($total_neto,2,'.',',') . '</td>
                    </tr>
                  </table>';
          $pdf->writeHTML($tblenc, true, false, true, false, '');


        ob_end_clean();
        $pdf->Output($titulo_archivo.'.pdf', 'D');
    }
}
else{
    header('Location: ../');
    exit;
}