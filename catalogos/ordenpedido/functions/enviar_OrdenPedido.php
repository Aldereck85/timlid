<?php
session_start();

$jwt_ruta = "../../../";
require_once '../../jwt.php';
var_dump($_POST);
$token = $_POST["csr_token_8UY8N"];

if(!empty($_SESSION['token_ld10d'])) {
    if (!hash_equals($_SESSION['token_ld10d'], $token)) {
        echo "error-general";
    }
    else{

        require_once '../../../lib/TCPDF/tcpdf.php';
        require_once '../../../include/db-conn.php';
        require_once('../../../lib/phpmailer_configuration.php');


        $id = $_POST['txtId'];
        $FKUsuario = $_SESSION["PKUsuario"];
        date_default_timezone_set('America/Mexico_City');
        $fecha_alta = date("Y-m-d H:i:s");

        if(isset($_POST['txtId'])){
          $origen = $_POST['txtOrigen'];
          $destino = $_POST['txtDestino'];
          $asunto = $_POST['txtAsunto'];
          $mensaje = $_POST['txaMensaje'];
        }

        $stmt = $conn->prepare('SELECT ops.id_orden_pedido_empresa, so.sucursal as sucursal_origen, sd.sucursal as sucursal_destino, c.NombreComercial as cliente, DATE_FORMAT(ops.fecha_captura, "%d/%m/%Y %H:%i:%s") as fecha_ingreso, DATE_FORMAT(ops.fecha_entrega, "%d/%m/%Y") as fecha_entrega, u.nombre as nombre_vendedor, ops.observaciones, ops.numero_cotizacion, ops.numero_venta_directa, ops.empresa_id FROM orden_pedido_por_sucursales as ops INNER JOIN sucursales as so ON so.id = ops.sucursal_origen_id LEFT JOIN sucursales as sd ON sd.id = ops.sucursal_destino_id LEFT JOIN clientes as c ON c.PKCliente = ops.cliente_id LEFT JOIN usuarios as u ON u.id = ops.vendedor_id WHERE ops.empresa_id = '.$_SESSION['IDEmpresa'] . ' AND ops.id = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch();
        $empresa_id = $row['empresa_id'];

        if($empresa_id != $_SESSION['IDEmpresa']){
          header("location:../detalleOrdenPedido.php?id=".$id);
        }

        $GLOBALS["no_pedido"] = sprintf("%011d", $row['id_orden_pedido_empresa']);
        $GLOBALS["FechaIngreso"] = $row['fecha_ingreso'];
        $GLOBALS['FechaEntrega'] = $row['fecha_entrega'];
        $GLOBALS["Observaciones"] = $row['observaciones'];
        $GLOBALS["Cliente"] = $row['cliente'];
        $GLOBALS["Vendedor"] = $row['nombre_vendedor'];
        $GLOBALS["sucursal_origen"] = $row['sucursal_origen'];
        $GLOBALS["sucursal_destino"] = $row['sucursal_destino'];
        $GLOBALS['numero_cotizacion'] = sprintf("%011d", $row['numero_cotizacion']);
        $GLOBALS['numero_venta_directa'] = sprintf("%011d", $row['numero_venta_directa']);


        $mensaje = $mensaje."<br><br>"."Se te ha enviado la orden de pedido No. ".$GLOBALS["no_pedido"];



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
                $this->Cell(30, 10, 'Orden de pedido: ' . $GLOBALS["no_pedido"], 0);
                $image_file = '../../../img/header/bluTimlid.png';
                $this->Image($image_file, 185, 9, 7);

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
        $pdf->SetTitle('Orden de pedido');
        $pdf->SetSubject('Orden de pedido');
        $pdf->SetKeywords('Orden de pedido');
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
            <th width="20%">Fecha de expedición</th>
            <th width="20%">Fecha de vencimiento</th>
            <th width="40%" style="background-color: none;"></th>';

        if(trim($GLOBALS['Vendedor']) != ""){
          $tblenc .= '
            <th width="20%">Cliente</th>';
        }

        $tblenc .= '
          </tr>
          <tr>
            <td width="20%">' . $GLOBALS['FechaIngreso'] . '</td>
            <td width="20%">' . $GLOBALS['FechaEntrega'] . '</td>
            <td width="40%" style="background-color: none;"></td>';

            if(trim($GLOBALS['Vendedor']) != ""){
              $tblenc .= '
                <td width="20%">'.$GLOBALS['Vendedor'].'</td>';
            }

          $tblenc .= '
          </tr>
        </table>';
        $pdf->writeHTML($tblenc, true, false, true, false, '');

        //INICIO SUCURSALES
        $tblsuc = '
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
            <th width="45%">Sucursal origen</th>
            <th width="10%" style="background-color: none;"></th>';
            
            if(trim($GLOBALS['Cliente']) != ""){
              $tblsuc = '<th width="45%">Cliente</th>';
            }
            if(trim($GLOBALS['sucursal_destino']) != ""){
              $tblsuc = '<th width="45%">Sucursal destino</th>';
            }

          $tblsuc = '
          </tr>
          <tr>
            <td width="45%">' . $GLOBALS['sucursal_origen'] . '</td>
            <td width="10%" style="background-color: none;"></td>';

            if(trim($GLOBALS['Cliente']) != ""){
              $tblsuc = '<td width="45%">' . $GLOBALS['Cliente'] . '</td>';
            }
            if(trim($GLOBALS['sucursal_destino']) != ""){
              $tblsuc = '<td width="45%">' . $GLOBALS['sucursal_destino'] . '</td>';
            }

        $tblsuc = '
          </tr>
        </table>';
        $pdf->writeHTML($tblsuc, true, false, true, false, '');
        //FIN SUCURSALES

        //INICIO SUCURSAL
        $tblsuc = '
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
            <th width="45%">Sucursal origen</th>
            <th width="10%" style="background-color: none;"></th>
            <th width="45%">Sucursal destino</th>
          </tr>
          <tr>
            <td width="45%">' . $GLOBALS['sucursal_origen'] . '</td>
            <td width="10%" style="background-color: none;"></td>
            <td width="45%">' . $GLOBALS['sucursal_destino'] . '</td>
          </tr>
        </table>';
        $pdf->writeHTML($tblsuc, true, false, true, false, '');
        //FIN SUCURSALES

        //INICIO NUMERO DE COTIZACION O DE VENTA
        if($GLOBALS['numero_cotizacion'] != "" || $GLOBALS['numero_venta_directa'] != ""){
          $tblnum = '
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
            <tr>';
              if($GLOBALS['numero_cotizacion'] != ""){
                  $tblnum = '
                      <th width="20%">ID Cotizacion</th>
                      <td width="80%">' . $GLOBALS['numero_cotizacion'] . '</td>';
              }
              if($GLOBALS['numero_venta_directa'] != ""){
                  $tblnum = '
                      <th width="20%">ID Venta</th>
                      <td width="80%">' . $GLOBALS['numero_venta_directa'] . '</td>';
              }

          $tblnum = '
            </tr>
          </table>';
          $pdf->writeHTML($tblnum, true, false, true, false, '');
        }
        //FIN NUMERO DE COTIZACION O DE VENTA


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
                <th width="18%">Clave</th>
                <th width="45%">Producto</th>
                <th width="25%">Unidad medida</th>
                <th width="12%">Cantidad</th>        
              </tr>
            </thead>
            <tbody>
        ';

        $stmt = $conn->prepare('SELECT p.PKProducto, p.Nombre, p.ClaveInterna, dop.cantidad_pedida, csu.Descripcion FROM detalle_orden_pedido_por_sucursales as dop INNER JOIN productos as p ON p.PKProducto = dop.producto_id LEFT JOIN info_fiscal_productos as ifp ON ifp.FKProducto = p.PKProducto LEFT JOIN claves_sat_unidades as csu ON csu.PKClaveSATUnidad = ifp.FKClaveSATUnidad WHERE dop.orden_pedido_id = :id');
        $stmt->execute(array(':id' => $id));
        $numero_productos = $stmt->rowCount();
        $rowp = $stmt->fetchAll();

        $x = 0;

        foreach ($rowp as $rp) {

            if($rp['Descripcion'] == ""){
              $ClaveUnidad = "Sin Clave";
            }
            else{
              $ClaveUnidad = $rp['Descripcion'];
            }

            $tbl .= '<tr>
                      <td width="18%">' . $rp['ClaveInterna'] . '</td>
                      <td width="45%">' . $rp['Nombre'] . '</td>
                      <td width="25%">' . $ClaveUnidad .'</td>
                      <td width="12%">' . $rp['cantidad_pedida'] . '</td>
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
            <td width="100%">' . $GLOBALS["Observaciones"] . '</td>
          </tr>
        </table>';
        $pdf->writeHTML($tblNotas, true, false, true, false, '');

        ob_end_clean();
        $pdfdoc = $pdf->Output('orden_pedido_' . $GLOBALS["no_pedido"] . '.pdf', 'S');


        // send message
        $stmt = $conn->prepare('SELECT RazonSocial FROM empresas WHERE PKEmpresa = '.$_SESSION['IDEmpresa']);
        $stmt->execute();
        $rowemp = $stmt->fetch();

        try {    
            $mail->Sender = $origen;
            $mail->setFrom($origen, $rowemp['RazonSocial']);
            $mail->addReplyTo($origen, $rowemp['RazonSocial']);
            $mail->addAddress($destino);     //Add a recipient

            //Attachments
            $mail->AddStringAttachment($pdfdoc, 'orden_pedido_'.$GLOBALS["no_pedido"].'.pdf');

            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = utf8_decode($asunto);
            $mail->Body    = utf8_decode($mensaje);

            if($mail->send())
            {
              $stmt = $conn->prepare("INSERT INTO bitacora_orden_pedido (usuario_id, mensaje_id, orden_pedido_id, created_at, updated_at) VALUES (:usuario_id, :mensaje_id, :orden_pedido_id, :created_at, :updated_at)");
              $stmt->bindValue(':usuario_id',$FKUsuario);
              $stmt->bindValue(':mensaje_id', 19);
              $stmt->bindValue(':orden_pedido_id',$id);
              $stmt->bindValue(':created_at',$fecha_alta);
              $stmt->bindValue(':updated_at',$fecha_alta);
              $stmt->execute();  
              echo "exito";
            }
            else{
              echo "fallo";
            }

        } catch (Exception $e) {
            //header('Location: ver_Cotizacion.php?id='.$id.'&estatus=2');
            echo "fallo";
        }
    }
}
else{
    echo "error-general";
}
?>
