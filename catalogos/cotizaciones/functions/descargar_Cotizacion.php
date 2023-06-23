<?php
session_start();

$jwt_ruta = "../../../";
require_once '../../jwt.php';

$id = $_REQUEST['idCotizacion'];
$FKUsuario = $_SESSION["PKUsuario"];
$token = $_REQUEST["csr_token_7ALF1"];

if(!empty($_SESSION['token_ld10d'])) {
    if (!hash_equals($_SESSION['token_ld10d'], $token)) {
        header('Location: ../detalleCotizacion.php?id='.$id);
        exit;
    }
    else{

        require_once '../../../lib/TCPDF/tcpdf.php';
        require_once '../../../include/db-conn.php';

        $stmt = $conn->prepare("SELECT texto FROM parametros_texto WHERE descripcion = 'leyenda_cotizacion' AND empresa_id = ".$_SESSION['IDEmpresa']);
        $stmt->execute();
        $row_leyenda = $stmt->fetch();
        $GLOBALS["leyenda"] = $row_leyenda['texto'];

        $stmt = $conn->prepare('SELECT c.id_cotizacion_empresa, 
                                       c.Subtotal, c.ImporteTotal, 
                                       DATE_FORMAT(c.FechaIngreso, "%d/%m/%Y %H:%i:%s") as FechaIngreso, 
                                       DATE_FORMAT(c.FechaVencimiento, "%d/%m/%Y") as FechaVencimiento,
                                       c.NotaCliente,
                                       c.NotaInterna, 
                                       cl.NombreComercial, 
                                       cl.Email, 
                                       cl.razon_social as razonSocialCliente,
                                       cl.rfc as rfcCliente,
                                       cl.Telefono as telefonoCliente,
                                       cl.Email as emailCliente,
                                       u.nombre,  
                                       u.Usuario, 
                                       e.Telefono,
                                       c.CodigoCotizacion,
                                       ifnull(c.condicion_Pago,0) as condicionPago,
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
                                       ifnull(decl.PKDireccionEnvioCliente,0) as isNulo,
                                       s.Sucursal,
                                       @_vendedor := c.empleado_id as empleado_id,
                                      (select concat(e.Nombres," ",e.PrimerApellido," ",e.SegundoApellido) as usuario
                                          from empleados e where e.PKEmpleado =  @_vendedor) as Empleado,
                                      (select e.Telefono from empleados e where e.PKEmpleado =  @_vendedor) as TelefonoEmpleado,
                                      (select e.email from empleados e where e.PKEmpleado =  @_vendedor) as correoEmpleado,
                                      IFNULL(md.CLAVE,0) as moneda,
                                    md.PKMoneda
                                  FROM cotizacion as c 
                                      INNER JOIN clientes as cl ON cl.PKCliente = c.FKCliente
                                      LEFT JOIN usuarios AS u ON c.FKUsuarioCreacion = u.id                         
                                      LEFT JOIN sucursales s on c.FKSucursal = s.id
                                      LEFT JOIN empleados_usuarios as eu ON eu.FKUsuario = u.id 
                                      LEFT JOIN empleados as e ON eu.FKEmpleado = e.PKEmpleado 
                                      LEFT JOIN direcciones_envio_cliente decl on c.direccion_entrega_id = decl.PKDireccionEnvioCliente
                                      LEFT JOIN paises psE on decl.Pais = psE.PKPais
                                      LEFT JOIN estados_federativos efE on decl.Estado = efE.PKEstado
                                      left join monedas md on md.PKMoneda = c.FkMoneda_id
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
        $GLOBALS["Email"] = $row['Email'];
        $GLOBALS["Telefono"] = $row['Telefono'];

        $GLOBALS["VendedorJ"] = $row['Empleado'];
        $GLOBALS["EmailJ"] = $row['correoEmpleado'];
        $GLOBALS["TelefonoJ"] = $row['TelefonoEmpleado'];
        $GLOBALS["RFCCliente"] = $row['rfcCliente'];
        $GLOBALS["TelefonoCliente"] = $row['telefonoCliente'];
        $GLOBALS["EmailCliente"] = $row['emailCliente'];
        $GLOBALS["RazonSocialCliente"] = $row['razonSocialCliente'];
        $GLOBALS["SucursalE"] = $row['SucursalE'];
        $GLOBALS["Moneda"] = $row['moneda'] == "0" ? "" : $row['moneda'];


        if ($row['condicionPago'] == '1'){
          $GLOBALS["CondicionPago"] = 'Contado';
        }else if ($row['condicionPago'] == '2'){
          $GLOBALS["CondicionPago"] = 'Crédito';
        }else{
          $GLOBALS["CondicionPago"] = 'Sin especificar';
        }

        $GLOBALS["Vendedor"] = $row['nombre'];      
        $GLOBALS["Sucursal"] = $row['Sucursal'];
        $GLOBALS['FechaVencimiento'] = $row['FechaVencimiento'];
        if ($row['PKDireccionEnvioCliente'] != 1){
          if ($row['isNulo'] == '0'){
            $GLOBALS["DireccionE"] = '';
          }else{
            if($row['ContactoE'] == null || $row['ContactoE'] == ''){
              $row['ContactoE']= "Desconocido";
            }
            $GLOBALS["DireccionE"] = $row['CalleE'].' '.$row['NumExtE'].' Int.'.$row['NumIntE'].', '.$row['ColoniaE'].', '.$row['MunicipioE'].', '.$row['EstadoE'].', '.$row['PaisE'].', Atención: '.$row['ContactoE'].' '.$row['TelefonoE'];
          }
        }else{
          $GLOBALS["DireccionE"] = '';
        }

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
                // Logo
                $image_file = $GLOBALS["ruta"].$GLOBALS["logotipo"];
                $this->Image($image_file, 15, 10, 45, 15, '', '', 'T', false, 300, '', false, false, 0, false, false, false);
                // Set font
                $this->SetFont('Helvetica', 'B', 20);
                // Title
                // set cell padding
                $this->setCellPaddings(1, 1, 1, 1);
                // set cell margins
                $this->setCellMargins(1, 1, 1, 1);
                // MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0)
                $this->MultiCell(0, 30, 'Cotización', 0, 'R', 0, 0, 125, 6, true);

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
                $this->SetFont('Helvetica', 'I', 8);

                /*$footer = '
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
                $this->writeHTML($footer, true, false, true, false, '');*/

                // Page number
                $this->Cell(0, 5, ''.$this->getAliasNumPage().' de '.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
            }

        }

        $pdf = new PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Timlid'
        );
        $pdf->SetTitle('Cotizacion');
        $pdf->SetSubject('Cotizacion');
        $pdf->SetKeywords('Cotizacion');
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
            <td class="td4 td6" width="50%"> Vencimiento: '.$GLOBALS['FechaVencimiento'].'</td>
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
            <td class="td3 td6" width="27.5%">'. $GLOBALS['VendedorJ'] .'</td>
            <td class="td3 td6" width="27.5%"><u>'. $GLOBALS["EmailJ"] .'</u></td>
            <td class="td3 td6" width="15%">'. $GLOBALS["TelefonoJ"] .'</td>
            <td class="td3 td6" width="15%"></td>
            <td class="td3 td6" width="15%"></td>
          </tr>
          <tr>
            <td class="td3" width="100%">
              PRESENTAMOS A USTED(ES) LA PROPUESTA DE PRECIOS PREVIAMENTE SOLICITADOS.
              <br>
              <b>LE SUGERIMOS REVISAR DETALLADAMENTE LO QUE AQUÍ SE OFRECE.</b>
            </td>
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
          .td8 {
            border-bottom: 1px solid #b2b2b2;
            border-top: 1px solid #b2b2b2;
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

        $stmt = $conn->prepare('SELECT dc.FKProducto, 
                                       dc.Cantidad, 
                                       dc.Precio, 
                                       p.ClaveInterna, 
                                       p.Nombre, 
                                       p.Descripcion as descripcionProd,
                                       cs.Clave as claveSAT,
                                       csu.Descripcion 
                                FROM detalle_cotizacion as dc 
                                    INNER JOIN productos as p ON p.PKProducto = dc.FKProducto 
                                    LEFT JOIN info_fiscal_productos as ifp ON ifp.FKProducto = p.PKProducto 
                                    LEFT JOIN claves_sat cs on ifp.FKClaveSAT = cs.PKClaveSAT
                                    LEFT JOIN claves_sat_unidades as csu ON csu.PKClaveSATUnidad = ifp.FKClaveSATUnidad 
                                WHERE dc.FKCotizacion = :id');
        $stmt->execute(array(':id' => $id));
        $numero_productos = $stmt->rowCount();
        $rowp = $stmt->fetchAll();

        $impuestos = array();
        $x = 0;

        foreach ($rowp as $rp) {

            $stmt = $conn->prepare('SELECT i.PKImpuesto, i.Nombre, i.FKTipoImpuesto as TipoImpuesto, i.FKTipoImporte as TipoImporte, i.Operacion, di.FKProducto, di.Tasa FROM detalleimpuesto as di INNER JOIN impuesto as i ON i.PKImpuesto = di.FKImpuesto WHERE di.FKCotizacion= :id AND FKProducto = :idProducto');
            $stmt->execute(array(':id' => $id, ':idProducto' => $rp['FKProducto']));
            $rowi = $stmt->fetchAll();

            if($rp['Descripcion'] == ""){
              $ClaveUnidad = "Sin unidad";
            }
            else{
              $ClaveUnidad = $rp['Descripcion'];
            }

            $totalProducto = $rp['Cantidad'] * $rp['Precio'];
            $tbl .= '<tr>
                      <td class="td8" width="9%">' . $rp['Cantidad'] . '</td>
                      <td class="td8" width="10%">' . $rp['ClaveInterna'] . '</td>
                      <td class="td8" width="39%">' . $rp['Nombre'] . '</td>
                      <td class="td8" width="14%">$ '.number_format($rp['Precio'],4,'.',',').'</td>
                      <td class="td8" width="16%">'. $ClaveUnidad .'</td>
                      <td class="td8" width="12%">$ '.number_format($totalProducto,4,'.',',').'</td>
                    </tr>';

            $contImpuestos = 1;
            $numImpuestos = count($rowi);
            foreach ($rowi as $ri) {
                $IniImpuesto = explode(" ", $ri['Nombre']);
                $Identificador = $IniImpuesto[0] . "_" . $ri['TipoImpuesto'] . "_" . $ri['TipoImporte'] . "_" . $ri['PKImpuesto'] . "_" . $ri['FKProducto'];

                if ($ri['TipoImporte'] == 1) {
                    $tas = "%";
                }
                if ($ri['TipoImporte'] == 2 || $ri['TipoImporte'] == 3) {
                    $tas = "";
                }

                //print_r($impuestos);

                //echo "id impuesto :".$ri['PKImpuesto']."//";
                $found_key = array_search($ri['PKImpuesto'], array_column($impuestos, 0));

                if ($found_key > -1) {
                    $impuestos[$found_key][0] = $ri['PKImpuesto'];

                    if ($ri['TipoImporte'] == 1) {
                        $impuestos[$found_key][1] = $impuestos[$found_key][1] + (($rp['Cantidad'] * $rp['Precio']) * ($ri['Tasa'] / 100));
                    } else {
                        $impuestos[$found_key][1] = $impuestos[$found_key][1] + $ri['Tasa'];
                    }

                    $impuestos[$found_key][2] = $ri['Nombre'];
                    $impuestos[$found_key][3] = $ri['TipoImpuesto'];
                    $impuestos[$found_key][4] = $ri['Operacion'];
                } else {
                    $impuestos[$x][0] = $ri['PKImpuesto'];
                    if ($ri['TipoImporte'] == 1) {
                        $impuestos[$x][1] = ($rp['Cantidad'] * $rp['Precio']) * ($ri['Tasa'] / 100);
                    } else {
                        $impuestos[$x][1] = $ri['Tasa'];
                    }

                    $impuestos[$x][2] = $ri['Nombre'];
                    $impuestos[$x][3] = $ri['TipoImpuesto'];
                    $impuestos[$x][4] = $ri['Operacion'];
                    $x++;
                }

                /*$tbl .= $ri['Nombre'] . " " . $ri['Tasa'] . $tas;
                if ($contImpuestos != $numImpuestos) {
                    $tbl .= "<br>";
                }*/

                $contImpuestos++;
            }

            /*$tbl .= '</td>
                    <td width="12%">' . number_format($totalProducto, 2) . '</td>
                  </tr>';*/
        }

        /*<tr>
            <td class="td4 td6 td7" width="100%">\'NOTA ESPECIAL: MATERIAL CORTADO A LA MEDIDA O SURTIDO EN TÉRMINOS ESPECIALES, POR NINGÚN MOTIVO SE ACEPTARÁN DEVOLUCIONES\'</td>
          </tr> */
        $tbl .= '<tr>
                  <td class="td6 td7" width="100%"></td>
                </tr>
                <tr>
                  <td class="td4 td6" width="55%" style="background-color: none;"></td>
                  <td class="td4 td6" width="21%" style="text-align: right; font-weight: bold;">Subtotal:</td>
                  <td class="td6" width="24%" style="text-align: right;">$ ' . $GLOBALS["Subtotal"] . '</td>
                </tr>
                <tr>
                  <td class="td4 td6" width="55%" style="background-color: none;"></td>
                  <td class="td4 td6" width="21%" style="text-align: right; font-weight: bold;">Impuestos:</td>
                  <td class="td4 td6" width="24%"></td>
                </tr>';

        //print_r($impuestos);
        foreach ($impuestos as $imp) {
            $IniImpuesto = explode(" ", $imp[2]);
            $tbl .= '<tr>
                        <td class="td4 td6" width="55%" style="background-color: none;"></td>
                        <td class="td4 td6" width="21%" style="text-align: right; font-weight: bold;">' . $imp[2] . '</td>
                        <td class="td4 td6" width="24%" style="text-align: right;">$ ' . number_format($imp[1], 2) . '</td>
                      </tr>';

        }

        $tbl .= '<tr>
                    <td width="55%" style="background-color: none;border-bottom: 1px solid #fff; border-top: 1px solid #fff;"></td>
                    <th width="21%" style="text-align: right; font-weight: bold;">Total:</th>
                    <td width="24%" style="text-align: right;">'.$GLOBALS["Moneda"].' $ ' . $GLOBALS["ImporteTotal"] . '</td>
                  </tr>';

        $tbl .= '</table>';

        $pdf->writeHTML($tbl, true, false, true, false, '');

        /*$tblNotas = '<p style="font-size: 8px; font-weight: bold;">LIMITACION DE RESPONSABILIDAD</p>
        <p style="font-size: 7px;">El material que ampara esta cotización es de Calidad Certificada, pero por su naturaleza puede tener discontinuidades internas que los equipos de Control de Calidad de nuestros Proveedores pueden no detectarlas; en el caso de encontrar en estos productos alguna discontinuidad 
        interna que pudiera afectar su producto terminado, como siempre estamos en la mejor disposición de atender su petición. Únicamente nos hacemos responsables por el valor del material reclamado el cual será repuesto o se le acreditará en su cuenta. No nos hacemos responsables 
        por tiempo de maquinado, herramientas fracturadas, maquinaria dañada, transporte, maniobras o algún otro gasto generado en sus procesos.</p>
        <br>
        <p style="font-size: 8px; font-weight: bold;">CONDICIONES DE VENTA</p>
        <p style="font-size: 7px;">Los precios en moneda nacional se realizan al tipo de cambio del día del diario oficial, sujetos a cambio sin previo aviso
        <br>
        Todos nuestros precios son L.A.B. origen de embarque, la mercancía viajará por cuenta y riesgo del comprador
        <br>
        No se aceptan cancelaciones de material cortado a medida o surtido en términos especiales</p>
        <br><br><br><br><br>';
        $pdf->writeHTML($tblNotas, true, false, true, false, '');*/

        $notasCliente = '<p style="font-size: 9px; font-weight: bold;">Notas:</p>
        <p style="font-size: 9px; font-weight: normal;">'.$GLOBALS["NotaCliente"].'<p>';
        $pdf->writeHTML($notasCliente, true, false, true, false, '');


        $pdf->write1DBarcode($GLOBALS["referencia"], 'C39', '', '', '', 18, 0.4, $style, 'N');
        $pdf->writeHTML($GLOBALS["referencia"], true, false, true, false, '');


        ob_end_clean();
        $pdf->Output('cotizacion_' . $GLOBALS["referencia"] . '.pdf', 'D');
    }
}
else{
    header('Location: ../detalleCotizacion.php?id='.$id);
    exit;
}