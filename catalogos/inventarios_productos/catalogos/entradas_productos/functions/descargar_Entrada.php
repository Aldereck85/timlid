<?php
session_start();
require_once('../../../../../lib/TCPDF/tcpdf.php');
require_once('../../../../../include/db-conn.php');
require_once('../../../../../lib/phpmailer_configuration.php');

$folio = $_GET['folio'];
$id_cuenta_pagar = $_GET['id_cuenta'];

$FKUsuario = $_SESSION["PKUsuario"];
$Usuario = $_SESSION["Usuario"];
$PKEmpresa = $_SESSION["IDEmpresa"];

$ruta = $_ENV['RUTA_ARCHIVOS_READ'].$PKEmpresa.'/fiscales'.'/';

//Logo de la empresa que emite la OC
$stmtLogo = $conn->prepare("SELECT logo FROM empresas WHERE PKEmpresa = :empresa");
$stmtLogo->bindValue(':empresa', $PKEmpresa, PDO::PARAM_INT);
$stmtLogo->execute();
$rowLogo = $stmtLogo->fetch();

$GLOBALS["ruta"] = $ruta;
$GLOBALS["logotipo"] = $rowLogo['logo'];

//Datos generales de la entrada por orden de compra
$stmt = $conn->prepare("SELECT distinct SUBSTRING_INDEX(ieps.numero_documento,' / ',1) as folio_factura,
              SUBSTRING_INDEX(ieps.numero_documento,' / ',-1) as serie_factura,
              DATE_FORMAT(ieps.fecha_captura, '%d/%m/%Y') as fecha_captura,
              pr.NombreComercial as nombre_comercial,
                ieps.folio_entrada as folio_entrada,
                s.Sucursal as sucursal,
                s.Calle as calle_sucursal,
                s.numero_exterior as num_ext_sucursal,
                s.Prefijo as prefijo,
                s.numero_interior  as num_int_sucursal,
                s.Colonia as colonia_sucursal,
                s.Municipio as municipio_sucursal,
                ef.Estado as estado_sucursal,
                ps.Pais as pais_sucursal,
                s.Telefono as telefono_sucursal,
                ifnull((select subtotal from cuentas_por_pagar where id = :id1),0) as subtotal,
                ifnull((select importe from cuentas_por_pagar where id = :id2),0) as importe,
                em.nombre_comercial as razon_social,
                concat(e.Nombres,' ',e.PrimerApellido,' ',e.SegundoApellido) as encargado,
                us.usuario as email,
                e.Telefono as telefono,
                ieps.observaciones as notas
              from inventario_entrada_por_sucursales ieps
              inner join proveedores pr on ieps.proveedor_id = pr.PKProveedor
              inner join sucursales s on ieps.sucursal_id = s.id
              inner join paises ps on s.pais_id = ps.PKPais
              inner join estados_federativos ef on s.estado_id = ef.PKEstado
              inner join empresas em on pr.empresa_id = em.PKEmpresa
              inner join usuarios us on ieps.usuario_creo_id = us.id
              left join empleados_usuarios eu on us.id = eu.FKUsuario
              left join empleados e on eu.FKEmpleado = e.PKEmpleado
              inner join sucursales sd on ieps.sucursal_id = sd.id
              where ieps.folio_entrada = :id3 and sd.empresa_id = :id_empresa
              ;");
$stmt->bindValue(':id1', $id_cuenta_pagar, PDO::PARAM_INT);
$stmt->bindValue(':id2', $id_cuenta_pagar, PDO::PARAM_INT);
$stmt->bindValue(':id3', $folio);
$stmt->bindValue(':id_empresa', $PKEmpresa);
$stmt->execute();
$row = $stmt->fetch();

  $GLOBALS["FolioFactura"] = $row['folio_factura'];
  $GLOBALS["SerieFactura"] = $row['serie_factura'];
  $GLOBALS["Referencia"] = $row['folio_entrada'];
  $GLOBALS["Subtotal"] = number_format($row['subtotal'],2,'.',','); 
  $GLOBALS["ImporteTotal"] = number_format($row['importe'],2,'.',',');
  $GLOBALS["FechaCaptura"] = $row['fecha_captura'];
  $GLOBALS["FechaEstimada"] = '1';
  $GLOBALS["Sucursal"] = $row['sucursal'];
  $GLOBALS["Direccion"] = $row['calle_sucursal'].' '.$row['num_ext_sucursal'].' Int.'.$row['num_int_sucursal'].'- '.$row['prefijo'].', '.$row['colonia_sucursal'].', '.$row['municipio_sucursal'].', '.$row['estado_sucursal'].', '.$row['pais_sucursal'];
  $GLOBALS["Empresa"] = $row['razon_social'];
  $GLOBALS["Receptor"] = $row['encargado'];
  $GLOBALS["Telefono"] = $row['telefono'];
  $GLOBALS["NotasReceptor"] = $row['notas'];
  $GLOBALS["Email"] = $row['email'];


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
      $this->Cell(30, 10, 'Entrada ', 0);
      $image_file = $GLOBALS["ruta"].$GLOBALS["logotipo"];
      $this->Image($image_file, 150, 9, 45,15, '', '', 'T', false, 300, 'R', false, false, 0, false, false, false);

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
              <th width="50%" style="font-size: 12px;"><b>Receptor:</b> ' . $GLOBALS['Receptor'] . '</th>
              <th width="50%" style="font-size: 12px;"><b>Teléfono:</b> ' . $GLOBALS['Telefono'] . '</th>
            </tr>
            <tr>
              <th width="50%" style="font-size: 12px;"><b>Email:</b> ' . $GLOBALS["Email"] . '</th>
            </tr>
            
          </table>';
        $this->writeHTML($footer, true, false, true, false, '');

    }

}

$pdf = new PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor($GLOBALS["Empresa"]);
$pdf->SetTitle('Entrada por orden de compra');
$pdf->SetSubject('Entrada por orden de compra');
$pdf->SetKeywords('Entrada por orden de compra');
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
    <td width="30%">' . $GLOBALS['Referencia'] . '</td>
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
    <th width="15%">Fecha de recepción</th>
    <th width="55%" style="background-color: none;"></th>
    <th width="30%">Proveedor</th>
  </tr>
  <tr>
    <td width="15%">' . $GLOBALS['FechaCaptura'] . '</td>
    <td width="55%" style="background-color: none;"></td>
    <td width="30%">' . $GLOBALS['Empresa'] . '</td>
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
  <th width="20%">No. serie de factura:</th>
  <td width="30%">' . $GLOBALS['SerieFactura'] . '</td> 
  <th width="5%" style="background-color: none;"></th>
  <th width="15%">Folio de factura</th>
  <td width="30%">' . $GLOBALS['FolioFactura'] . '</td>
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
    <th width="20%">Sucursal Entrada:</th>
    <td width="30%">' . $GLOBALS['Sucursal'] . '</td>    
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
';
$pdf->writeHTML($domicilioEntrega, true, false, true, false, '');

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
        <th width="10%">Clave</th>
        <th width="29%">Producto</th>
        <th width="10%">Precio</th>
        <th width="11%">U. medida</th>
        <th width="10%">Lote</th>
        <th width="10%">Caducidad</th>
        <th width="10%">Impuestos</th>
        <th width="10%">Cantidad</th>
      </tr>
    </thead>
    <tbody>
';

$stmtp = $conn->prepare("SELECT nombre, clave, cantidad, precio, unidadMedida, lote, caducidad, group_concat(iva, ieps, ieps_monto_fijo order by  iva,ieps,ieps_monto_fijo asc separator '/' ) as impuestos, importe from
          (
          select distinct ifnull(dpp.NombreProducto, p.Nombre) as nombre,
            ifnull(dpp.Clave, p.ClaveInterna) as clave,
            ieps.cantidad as cantidad,
            ifnull(dcpp.precio,0) as precio,
            ifnull(dpp.UnidadMedida, csu.Descripcion) as unidadMedida,
            ifnull(epp.numero_lote,'') as lote,
            if( epp.caducidad = null or epp.caducidad = '0000-00-00','', epp.caducidad) as caducidad,
            ifnull(concat('IVA ',dcpp.iva, '%'),'') as iva,
            ifnull(concat('IEPS ',dcpp.ieps, '%' ),'') as ieps,
            ifnull(concat('IEPS (Monto fijo) - ',dcpp.ieps_monto_fijo ),'') as ieps_monto_fijo,
                (ieps.cantidad * dcpp.precio)as importe
            from inventario_entrada_por_sucursales ieps
              inner join sucursales s on s.id = ieps.sucursal_id
              left join detalle_cuentas_por_pagar dcpp on dcpp.clave = ieps.clave and dcpp.cuenta_por_pagar_id = :cuentaPagar
              left join cuentas_por_pagar cpp on dcpp.cuenta_por_pagar_id = cpp.id
              inner join productos p on ieps.clave = p.ClaveInterna and p.empresa_id = :pkEmpresa
              inner join info_fiscal_productos ifp on ifp.FKProducto = p.PKProducto
              inner join claves_sat_unidades csu on csu.PKClaveSATUnidad = ifp.FKClaveSATUnidad
              left join datos_producto_proveedores dpp on p.PKProducto = dpp.FKProducto and cpp.proveedor_id = dpp.FKProveedor
              inner join existencia_por_productos epp on p.PKProducto = epp.producto_id and ifnull(ieps.numero_lote,'') = ifnull(epp.numero_lote,'') and ifnull(ieps.numero_serie,'') = ifnull(epp.numero_serie,'')
            where ieps.folio_entrada = :folioEntrada and s.empresa_id = :pkEmpresa2
          ) as tabless
          group by nombre, lote
          ;");
$stmtp->execute(array(':cuentaPagar'=>$id_cuenta_pagar,':pkEmpresa'=>$PKEmpresa,':folioEntrada'=>$folio,':pkEmpresa2'=>$PKEmpresa));
$rowp = $stmtp->fetchAll();

foreach ($rowp as $rp) { 

  $tbl.=  '<tr>
            <td width="10%">'.$rp['clave'].'</td>
            <td width="29%">'.$rp['nombre'].'</td>
            <td width="10%">$ '.number_format($rp['precio'],2,'.',',').'</td>
            <td width="11%">'.$rp['unidadMedida'].'</td>
            <td width="10%">'.$rp['lote'].'</td>
            <td width="10%">'.$rp['caducidad'].'</td>
            <td width="10%">'.$rp['impuestos'].'</td>
            <td width="10%">'.$rp['cantidad'].'</td>
          </tr>';
}

$stmtc = $conn->prepare("SELECT sum(cantidad) as cantidad from (
                          SELECT nombre, lote, cantidad, group_concat(iva, ieps, ieps_monto_fijo order by  iva,ieps,ieps_monto_fijo asc separator '/' ) as impuestos from
                          (
                            select distinct ifnull(dpp.NombreProducto, p.Nombre) as nombre,
                            ieps.cantidad as cantidad,
                            ifnull(epp.numero_lote,'') as lote,
                            ifnull(concat('IVA ',dcpp.iva, '%'),'') as iva,
                            ifnull(concat('IEPS ',dcpp.ieps, '%' ),'') as ieps,
                            ifnull(concat('IEPS (Monto fijo) - ',dcpp.ieps_monto_fijo ),'') as ieps_monto_fijo
                            from inventario_entrada_por_sucursales ieps
                              inner join sucursales s on s.id = ieps.sucursal_id
                              left join detalle_cuentas_por_pagar dcpp on dcpp.clave = ieps.clave and dcpp.cuenta_por_pagar_id = :cuentaPagar
                              left join cuentas_por_pagar cpp on dcpp.cuenta_por_pagar_id = cpp.id
                              inner join productos p on ieps.clave = p.ClaveInterna and p.empresa_id = :pkEmpresa
                              left join datos_producto_proveedores dpp on p.PKProducto = dpp.FKProducto and cpp.proveedor_id = dpp.FKProveedor
                              inner join existencia_por_productos epp on p.PKProducto = epp.producto_id and ifnull(ieps.numero_lote,'') = ifnull(epp.numero_lote,'') and ifnull(ieps.numero_serie,'') = ifnull(epp.numero_serie,'')
                            where ieps.folio_entrada = :folioEntrada and s.empresa_id = :pkEmpresa2
                          ) as tabless
                          group by nombre, lote
                        ) as total;");
$stmtc->execute(array(':cuentaPagar'=>$id_cuenta_pagar,':pkEmpresa'=>$PKEmpresa,':folioEntrada'=>$folio,':pkEmpresa2'=>$PKEmpresa));
$rowc = $stmtc->fetchAll();

foreach ($rowc as $rc) { 

  $tbl .= '<tr>
            <td width="80%" style="background-color: none;border-bottom: 1px solid #fff; border-top: 1px solid #fff;"></td>
            <td width="10%">Total:</td>
            <td width="10%">'.$rc['cantidad'].'</td>
          </tr>';
}

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
    <td width="100%">' . $GLOBALS["NotasReceptor"] . '</td>
  </tr>
</table>';
$pdf->writeHTML($tblNotas, true, false, true, false, '');


ob_end_clean();

$pdf->Output("Entrada por orden de Compra ".$GLOBALS["Referencia"]. '.pdf', 'D');
return true;
?>
