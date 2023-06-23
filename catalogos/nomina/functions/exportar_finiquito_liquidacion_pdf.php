<?php
session_start();

$jwt_ruta = "../../../";
require_once '../../jwt.php';

$idFiniquito = $_POST['idFiniquito'];
$token = $_POST["csr_token_UT5JP"];

if(!empty($_SESSION['token_ld10d'])) {
    if (!hash_equals($_SESSION['token_ld10d'], $token)) {
        header('Location: ../finiquito_liquidacion.php');
        exit;
    }
    else{

        require_once '../../../lib/TCPDF/tcpdf.php';
        require_once '../../../include/db-conn.php';

        $stmt = $conn->prepare("SELECT f.*, l.*, e.Nombres, e.PrimerApellido, e.SegundoApellido,dle.FechaIngreso, e.RFC, dme.NSS, p.puesto,t.Turno, dle.Sueldo,pp.DiasPago FROM finiquito as f INNER JOIN empleados as e ON e.PKEmpleado = f.empleado_id INNER JOIN datos_laborales_empleado as dle ON dle.FKEmpleado = e.PKEmpleado INNER JOIN periodo_pago as pp ON pp.PKPeriodo_pago = dle.FKPeriodo LEFT JOIN datos_medicos_empleado as dme ON dme.FKEmpleado = e.PKEmpleado LEFT JOIN puestos as p ON p.id = dle.FKPuesto LEFT JOIN turnos as t ON t.PKTurno = dle.FKTurno LEFT JOIN liquidacion as l ON l.finiquito_id = f.id WHERE f.id = :idFiniquito ");
        $stmt->bindValue(':idFiniquito',$idFiniquito);
        $stmt->execute();
        $datosFiniquito = $stmt->fetch();
        $nombreEmpleado = $datosFiniquito['Nombres'].' '.$datosFiniquito['PrimerApellido'].' '.$datosFiniquito['SegundoApellido'];
        $idEmpleado = $datosFiniquito['empleado_id'];
        //print_r($datosFiniquito);

        $GLOBALS['rutaFuncion'] = "./../";
        require_once("../../../functions/funcionNomina.php");
        $datosSalario = getSalario_Dias($idEmpleado);
        $sueldo = $datosSalario[0];
        $sueldoDiario = $datosSalario[2];
        $datosSBC = getSBCNomina($idEmpleado, $datosFiniquito['fecha_salida']);
        $SDI = $datosSBC[3];

        if($datosFiniquito['finiquito_id'] == NULL){
          $tipoMovimiento = 1;
        }
        else{
          $tipoMovimiento = 2;
        }

        $aguinaldo = $datosFiniquito['aguinaldo'];
        $aguinaldo_exento = $datosFiniquito['aguinaldo_exento'];
        $aguinaldo_gravado = $datosFiniquito['aguinaldo_gravado'];

        $vacaciones_gravadas = $datosFiniquito['vacaciones'];
        $vacaciones_exentas = 0.00;
        $vacaciones_gravadas = $datosFiniquito['vacaciones'];

        $primaVacacional = $datosFiniquito['prima_vacacional'];
        $primaVacacional_exento = $datosFiniquito['prima_vacacional_exenta'];
        $primaVacacional_gravado = $datosFiniquito['prima_vacacional_gravada'];

        $salariosDevengados = $datosFiniquito['salarios_devengados'];
        $otros = $datosFiniquito['otros'];
        $gratificacion = $datosFiniquito['gratificacion'];
        $bonoAsistencia = $datosFiniquito['bonos_asistencia'];
        $bonoPuntualidad = $datosFiniquito['bonos_puntualidad'];

        $total = $datosFiniquito['aguinaldo'] + $datosFiniquito['vacaciones'] + $datosFiniquito['prima_vacacional'] + $datosFiniquito['salarios_devengados'] + $datosFiniquito['otros'] + $datosFiniquito['gratificacion'] + $datosFiniquito['bonos_asistencia'] + $datosFiniquito['bonos_puntualidad'];
        $total_exento = $datosFiniquito['aguinaldo_exento'] + $datosFiniquito['prima_vacacional_exenta'];
        $total_gravado = $datosFiniquito['aguinaldo_gravado'] + $datosFiniquito['vacaciones'] + $datosFiniquito['prima_vacacional_gravada'] + $datosFiniquito['salarios_devengados'] + $datosFiniquito['otros'] + $datosFiniquito['gratificacion'] + $datosFiniquito['bonos_asistencia'] + $datosFiniquito['bonos_puntualidad'];

        if($datosFiniquito['isr_vacaciones_salarios'] > 0){
          $titulo1 = "ISR (Vacaciones,salarios, otros, gratificación)";
          $impuestoAplicableVS = $datosFiniquito['isr_vacaciones_salarios'];
        }
        else{
          $titulo1 = "SAE a pagar (Vacaciones,salarios, otros, gratificación)";
          $impuestoAplicableVS = $datosFiniquito['sae_vacaciones_salarios'];
        }

        if($datosFiniquito['isr_aguinaldo'] > 0){
          $titulo2 = "ISR Aguinaldo";
          $impuestoAguinaldo = $datosFiniquito['isr_aguinaldo'];
        }
        else{
          $titulo2 = "SAE Aguinaldo";
          $impuestoAguinaldo = $datosFiniquito['sae_aguinaldo'];
        }

        if($datosFiniquito['isr_aguinaldo'] > 0){
          $titulo3 = "ISR Prima Vacacional";
          $impuestoPrimaVacacional = $datosFiniquito['isr_prima_vacacional'];
        }
        else{
          $titulo3 = "SAE Prima Vacacional";
          $impuestoPrimaVacacional = $datosFiniquito['sae_prima_vacacional'];
        }

        if($tipoMovimiento == 2){
         $indemnizacion = $datosFiniquito['indemnizacion'];
         $indemnizacion_exento = $datosFiniquito['indemnizacion_exento'];
         $indemnizacion_gravado = $datosFiniquito['indemnizacion_gravado'];

         $anios_servicio = $datosFiniquito['anios_servicio'];
         $prima_antiguedad = $datosFiniquito['prima_antiguedad'];

         $totalLiquidacion = bcdiv($indemnizacion + $anios_servicio + $prima_antiguedad,1,2);
         $totalLiquidacionExento = bcdiv($indemnizacion_exento,1,2);
         $totalLiquidacionGravada = bcdiv($indemnizacion_gravado + $anios_servicio + $prima_antiguedad,1,2);

         if($datosFiniquito['isr_liquidacion'] > 0){
            $titulo_indemnizacion = "ISR Indemnización";
            $impuestoIndemnizacion = $datosFiniquito['isr_liquidacion'];
          }
          else{
            $titulo_indemnizacion = "SAE Indemnización";
            $impuestoIndemnizacion = $datosFiniquito['sae_liquidacion'];
          }
          $totalLiquidacionFInal = $datosFiniquito['total_liquidacion'];
        }

        $pensionAlimenticiaCheck = $datosFiniquito['tipo_pension_alimenticia'];
        $pension_alimenticia = $datosFiniquito['pension_alimenticia_cantidad'];
        $pension_alimenticia_porc = $datosFiniquito['pension_alimenticia'];
        $infonavit = $datosFiniquito['infonavit'];
        $fonacot = $datosFiniquito['fonacot'];
        $imss_salarios = $datosFiniquito['imss_salarios'];

        if($pensionAlimenticiaCheck == 2){
          $titulo_pension = "Pensión alimenticia (Salario)";
        }

        if($pensionAlimenticiaCheck == 3){
          $titulo_pension = "Pensión alimenticia (Total percepciones)";
        }

        if($pensionAlimenticiaCheck == 4){
          $titulo_pension = "Pensión alimenticia (Total percepciones menos deducciones)";
        }

        $suma_deducciones = $datosFiniquito['isr_vacaciones_salarios'] + $datosFiniquito['isr_aguinaldo'] + $datosFiniquito['isr_prima_vacacional'] - $datosFiniquito['sae_vacaciones_salarios'] - $datosFiniquito['sae_aguinaldo'] - $datosFiniquito['sae_prima_vacacional'] + $pension_alimenticia + $infonavit + $fonacot;

        $total_pagar = $datosFiniquito['total_pagar'];

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
                $this->Cell(30, 10, '', 0);
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
        $pdf->SetTitle('Finiquito/Liquidacion');
        $pdf->SetSubject('Finiquito/Liquidacion');
        $pdf->SetKeywords('Finiquito/Liquidacion');
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
/*
        error_reporting(E_ALL & ~E_NOTICE);
        ini_set('display_errors', 0);
        ini_set('log_errors', 1);
*/
        $titulo = "FINIQUITO";
        $titulo_archivo = "finiquito_".$nombreEmpleado;
        $encabezado = '
          <table>
            <tr>
              <td width="20%"><b>Nombre: </b></td>
              <td width="80%">'.$nombreEmpleado.'</td>
            </tr>
            <tr>
              <td width="15%"><b>Turno: </b></td>
              <td width="35%">'.$datosFiniquito['Turno'].'</td>
              <td width="15%"><b>NSS: </b></td>
              <td width="35%">'.$datosFiniquito['NSS'].'</td>
            </tr>
            <tr>
              <td width="15%"><b>Puesto: </b></td>
              <td width="35%">'.$datosFiniquito['puesto'].'</td>
              <td width="15%"><b>RFC: </b></td>
              <td width="35%">'.$datosFiniquito['RFC'].'</td>
            </tr>
            <tr>
              <td width="15%"><b>Salario: </b></td>
              <td width="35%">'.$sueldo.'</td>
              <td width="25%"><b>Salario diario: </b></td>
              <td width="25%">'.$sueldoDiario.'</td>
            </tr>
            <tr>
              <td width="30%"><b>Salario diario integrado: </b></td>
              <td width="20%">'.$SDI.'</td>
            </tr>
            <tr>
              <td width="25%"><b>Fecha ingreso: </b></td>
              <td width="25%">'.$datosFiniquito['fecha_ingreso'].'</td>
              <td width="25%"><b>Fecha salida: </b></td>
              <td width="25%">'.$datosFiniquito['fecha_salida'].'</td>
            </tr>
            <tr>
              <td width="25%"><b>Días aguinaldo: </b></td>
              <td width="25%">'.$datosFiniquito['dias_aguinaldo'].'</td>
              <td width="25%"><b>Proporcionales: </b></td>
              <td width="25%">'.$datosFiniquito['dias_aguinaldo_proporcionales'].'</td>
            </tr>
            <tr>
              <td width="25%"><b>Antiguedad: </b></td>
              <td width="25%">'.$datosFiniquito['antiguedad'].'</td>
            </tr>
            <tr>
              <td width="25%"><b>Días de vac. prop.: </b></td>
              <td width="25%">'.$datosFiniquito['dias_vacaciones_proporcionales'].'</td>
              <td width="25%"><b>Restantes: </b></td>
              <td width="25%">'.$datosFiniquito['dias_restantes'].'</td>
            </tr>
            <tr>
              <td width="25%"><b>A pagar: </b></td>
              <td width="25%">'.$datosFiniquito['dias_pagar'].'</td>
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
            <th width="100%" style="font-size: 14px;text-align: center;"><b>'.$titulo.'</b></th>
          </tr>
          <tr>
            <td width="100%"></td>
          </tr>
          <tr>
            <th width="40%">Concepto</th>
            <th width="20%">Importe</th>
            <th width="20%">Exento</th>
            <th width="20%">Gravado</th>
          </tr>
          <tr>
            <td width="100%" class="centrar"><b>PERCEPCIONES</b></td>
          </tr>
          <tr>
            <td width="40%" class="izquierda">Aguinaldo</td>
            <td width="20%">' . number_format($aguinaldo,2,'.',',') . '</td>
            <td width="20%">' . number_format($aguinaldo_exento,2,'.',',') . '</td>
            <td width="20%">' . number_format($aguinaldo_gravado,2,'.',',') . '</td>
          </tr>
          <tr>
            <td width="40%" class="izquierda">Vacaciones</td>
            <td width="20%">' . number_format($aguinaldo,2,'.',',') . '</td>
            <td width="20%">' . number_format($aguinaldo_exento,2,'.',',') . '</td>
            <td width="20%">' . number_format($aguinaldo_gravado,2,'.',',') . '</td>
          </tr>
          <tr>
            <td width="40%" class="izquierda">Vacaciones</td>
            <td width="20%">' . number_format($vacaciones_gravadas,2,'.',',') . '</td>
            <td width="20%">' . number_format($vacaciones_exentas,2,'.',',') . '</td>
            <td width="20%">' . number_format($vacaciones_gravadas,2,'.',',') . '</td>
          </tr>
          <tr>
            <td width="40%" class="izquierda">Prima vacacional</td>
            <td width="20%">' . number_format($primaVacacional,2,'.',',') . '</td>
            <td width="20%">' . number_format($primaVacacional_exento,2,'.',',') . '</td>
            <td width="20%">' . number_format($primaVacacional_gravado,2,'.',',') . '</td>
          </tr>
          <tr>
            <td width="40%" class="izquierda">Salario devengado</td>
            <td width="20%">' . number_format($salariosDevengados,2,'.',',') . '</td>
            <td width="20%">0.00</td>
            <td width="20%">' . number_format($salariosDevengados,2,'.',',') . '</td>
          </tr>
          <tr>
            <td width="40%" class="izquierda">Otros</td>
            <td width="20%">' . number_format($otros,2,'.',',') . '</td>
            <td width="20%">0.00</td>
            <td width="20%">' . number_format($otros,2,'.',',') . '</td>
          </tr>
          <tr>
            <td width="40%" class="izquierda">Gratificación</td>
            <td width="20%">' . number_format($gratificacion,2,'.',',') . '</td>
            <td width="20%">0.00</td>
            <td width="20%">' . number_format($gratificacion,2,'.',',') . '</td>
          </tr>
          <tr>
            <td width="40%" class="izquierda">Bono por asistencia</td>
            <td width="20%">' . number_format($bonoAsistencia,2,'.',',') . '</td>
            <td width="20%">0.00</td>
            <td width="20%">' . number_format($bonoAsistencia,2,'.',',') . '</td>
          </tr>
          <tr>
            <td width="40%" class="izquierda">Bono por puntualidad</td>
            <td width="20%">' . number_format($bonoPuntualidad,2,'.',',') . '</td>
            <td width="20%">0.00</td>
            <td width="20%">' . number_format($bonoPuntualidad,2,'.',',') . '</td>
          </tr>
          <tr>
            <td width="40%" class="izquierda"><b>SUMA PERCEPCIONES</b></td>
            <td width="20%">' . number_format($total,2,'.',',') . '</td>
            <td width="20%">' . number_format($total_exento,2,'.',',') . '</td>
            <td width="20%">' . number_format($total_gravado,2,'.',',') . '</td>
          </tr>
          <tr>
            <td width="100%" class="centrar"><b>DEDUCCIONES</b></td>
          </tr>
          <tr>
            <td width="40%" class="izquierda">'.$titulo1.'</td>
            <td width="60%">' . number_format($impuestoAplicableVS,2,'.',',') . '</td>
          </tr>
          <tr>
            <td width="40%" class="izquierda">'.$titulo2.'</td>
            <td width="60%">' . number_format($impuestoAguinaldo,2,'.',',') . '</td>
          </tr>
          <tr>
            <td width="40%" class="izquierda">'.$titulo3.'</td>
            <td width="60%">' . number_format($impuestoPrimaVacacional,2,'.',',') . '</td>
          </tr>'; 

        if($infonavit > 0){
          $tblenc .= '
            <tr>
              <td width="40%" class="izquierda">INFONAVIT</td>
              <td width="60%">' . number_format($infonavit,2,'.',',') . '</td>
            </tr>';
        }

        if($fonacot > 0){
          $tblenc .= '
            <tr>
              <td width="40%" class="izquierda">FONACOT</td>
              <td width="60%">' . number_format($fonacot,2,'.',',') . '</td>
            </tr>';
        }

        if($pensionAlimenticiaCheck != 1){
          $tblenc .= '
            <tr>
              <td width="40%" class="izquierda">'.$titulo_pension.'</td>
              <td width="60%">' . number_format($pension_alimenticia,2,'.',',') . '</td>
            </tr>';
        } 

        if($imss_salarios > 0){
          $tblenc .= '
            <tr>
              <td width="40%" class="izquierda">IMSS (Salarios devengados)</td>
              <td width="60%">' . number_format($imss_salarios,2,'.',',') . '</td>
            </tr>';
        }

        $tblenc .= '
          <tr>
            <td width="40%" class="izquierda"><b>SUMA DEDUCCIONES</b></td>
            <td width="60%">' . number_format($suma_deducciones,2,'.',',') . '</td>
          </tr>
          <tr>
            <td width="40%" class="izquierda"><b>TOTAL FINIQUITO</b></td>
            <td width="60%">' . number_format($total_pagar,2,'.',',') . '</td>
          </tr>
        </table>';
        $pdf->writeHTML($tblenc, true, false, true, false, '');

        if($tipoMovimiento == 2){

          $total_final = $totalLiquidacionFInal + $total_pagar;
          $titulo_archivo = "finiquito_liquidacion_".$nombreEmpleado;
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
                <td width="100%"></td>
              </tr>
              <tr>
                <th width="100%" style="font-size: 14px;text-align: center;"><b>LIQUIDACION</b></th>
              </tr>
              <tr>
                <td width="100%"></td>
              </tr>
              <tr>
                <th width="40%">Concepto</th>
                <th width="20%">Importe</th>
                <th width="20%">Exento</th>
                <th width="20%">Gravado</th>
              </tr>
              <tr>
                <td width="40%" class="izquierda">Indemnización (90 días)</td>
                <td width="20%">' . number_format($indemnizacion,2,'.',',') . '</td>
                <td width="20%">' . number_format($indemnizacion_exento,2,'.',',') . '</td>
                <td width="20%">' . number_format($indemnizacion_gravado,2,'.',',') . '</td>
              </tr>
              <tr>
                <td width="40%" class="izquierda">20 días por año de servicio</td>
                <td width="20%">' . number_format($anios_servicio,2,'.',',') . '</td>
                <td width="20%">0.00</td>
                <td width="20%">' . number_format($anios_servicio,2,'.',',') . '</td>
              </tr>
              <tr>
                <td width="40%" class="izquierda">Prima antiguedad</td>
                <td width="20%">' . number_format($prima_antiguedad,2,'.',',') . '</td>
                <td width="20%">0.00</td>
                <td width="20%">' . number_format($prima_antiguedad,2,'.',',') . '</td>
              </tr>
              <tr>
                <td width="40%" class="izquierda"><b>SUMA LIQUIDACION</b></td>
                <td width="20%">' . number_format($totalLiquidacion,2,'.',',') . '</td>
                <td width="20%">' . number_format($totalLiquidacionExento,2,'.',',') . '</td>
                <td width="20%">' . number_format($totalLiquidacionGravada,2,'.',',') . '</td>
              </tr>
              <tr>
                <td width="100%" class="centrar"><b>DEDUCCIONES</b></td>
              </tr>
              <tr>
                <td width="40%" class="izquierda">'.$titulo_indemnizacion.'</td>
                <td width="60%">' . number_format($impuestoIndemnizacion,2,'.',',') . '</td>
              </tr>
              <tr>
                <td width="40%" class="izquierda"><b>SUMA DEDUCCIONES</b></td>
                <td width="60%">' . number_format($impuestoIndemnizacion,2,'.',',') . '</td>
              </tr>
              <tr>
                <td width="40%" class="izquierda"><b>TOTAL LIQUIDACION</b></td>
                <td width="60%">' . number_format($totalLiquidacionFInal,2,'.',',') . '</td>
              </tr>
              <tr>
                <td width="100%"></td>
              </tr>
              <tr>
                <td width="40%" class="izquierda"><b>TOTAL A PAGAR</b></td>
                <td width="60%">' . number_format($total_final,2,'.',',') . '</td>
              </tr>
            </table>';
            $pdf->writeHTML($tblenc, true, false, true, false, '');


        }

        ob_end_clean();
        $pdf->Output($titulo_archivo.'.pdf', 'D');
    }
}
else{
    header('Location: ../finiquito_liquidacion.php');
    exit;
}