<?php
session_start();
require_once('../../../../../lib/TCPDF/tcpdf.php');
require_once('../../../../../include/db-conn.php');
require_once('../../../../../lib/phpmailer_configuration.php');

$folio = $_GET['folio'];
$serie = $_GET['serie'];

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

$stmt = $conn->prepare("SELECT 
                        sum(cpp.subtotal) as subtotal
                        from cuentas_por_pagar cpp
                        where cpp.folio_factura = :folio
                        and cpp.num_serie_factura = :serie
                        ;");
$stmt->bindValue(':folio', $folio, PDO::PARAM_STR);
$stmt->bindValue(':serie', $serie, PDO::PARAM_STR);
$stmt->execute();
$row = $stmt->fetch();

$stmt11 = $conn->prepare("SELECT ifnull(sum(doc.Cantidad * doc.Precio),0) as subtotal
                          from ordenes_compra oc
                            inner join detalle_orden_compra doc on oc.PKOrdenCompra = doc.FKOrdenCompra  
                            inner join datos_producto_proveedores dpp on doc.FKProducto = dpp.FKProducto and oc.FKProveedor = dpp.FKProveedor
                          where oc.PKOrdenCompra in (
                                                      select distinct 
                                                          ieps.orden_compra_id
                                                      from cuentas_por_pagar cpp
                                                        inner join detalle_cuentas_por_pagar dcpp on cpp.id = dcpp.cuenta_por_pagar_id 
                                                        inner join inventario_entrada_por_sucursales ieps on concat(cpp.folio_factura,' / ',cpp.num_serie_factura) = ieps.numero_documento
                                                      where cpp.folio_factura = :folio
                                                        and cpp.num_serie_factura = :serie
                          )");
$stmt11->bindValue(':folio', $folio, PDO::PARAM_STR);
$stmt11->bindValue(':serie', $serie, PDO::PARAM_STR);
$stmt11->execute();
$row11 = $stmt11->fetch();

$stmt111 = $conn->prepare("SELECT sum(cpp.subtotal) as subtotal
                          from cuentas_por_pagar cpp
                          where cpp.folio_factura = :folio
                          and cpp.num_serie_factura = :serie
                          ;");
$stmt111->bindValue(':folio', $folio, PDO::PARAM_STR);
$stmt111->bindValue(':serie', $serie, PDO::PARAM_STR);
$stmt111->execute();
$row111 = $stmt111->fetch();


$GLOBALS["SubTotalEnt"] = $row['subtotal'];
$GLOBALS["SubTotalOC"] = $row11['subtotal'];
$GLOBALS["SubTotalFAC"] = $row111['subtotal'];

$stmt2 = $conn->prepare("SELECT 
                          (select 
                            sum(cpp.subtotal) as subtotal
                          from cuentas_por_pagar cpp
                          where cpp.folio_factura = :folio1
                            and cpp.num_serie_factura = :serie1
                          ) 
                          +
                          (select ifnull(sum(totalImpuesto),0) from (
                            select ifnull(sum(totalImpuesto),0) as totalImpuesto from (
                              select 
                                  dcpp.id as id,
                                  dcpp.cantidad as cantidad,
                                  dcpp.precio as precio,
                                  ifnull((dcpp.cantidad * dcpp.precio) * (dcpp.iva / 100),0) as totalImpuesto,
                                  ifnull(dcpp.iva,0) as tasa,
                                  'IVA' as impuesto
                              from cuentas_por_pagar cpp
                                inner join detalle_cuentas_por_pagar dcpp on cpp.id = dcpp.cuenta_por_pagar_id 
                              where cpp.folio_factura = :folio2
                                and cpp.num_serie_factura = :serie2
                                and dcpp.iva != 0
                            ) as impu
                              GROUP BY tasa
                            union
                            select ifnull(sum(totalImpuesto),0) as totalImpuesto from (
                              select 
                                  dcpp.id as id,
                                  dcpp.cantidad as cantidad,
                                  dcpp.precio as precio,
                                  ifnull((dcpp.cantidad * dcpp.precio) * (dcpp.ieps / 100),0) as totalImpuesto,
                                  ifnull(dcpp.ieps,0) as tasa,
                                  'IEPS' as impuesto
                              from cuentas_por_pagar cpp
                                inner join detalle_cuentas_por_pagar dcpp on cpp.id = dcpp.cuenta_por_pagar_id 
                              where cpp.folio_factura = :folio3
                                and cpp.num_serie_factura = :serie3
                                and dcpp.ieps != 0
                            ) as impu
                              GROUP BY tasa
                            union 
                            select ifnull(sum(totalImpuesto),0) as totalImpuesto from (
                              select 
                                  dcpp.id as id,
                                  dcpp.cantidad as cantidad,
                                  dcpp.precio as precio,
                                  ifnull(dcpp.ieps_monto_fijo * dcpp.cantidad,0) as totalImpuesto,
                                  ifnull(dcpp.ieps_monto_fijo,0) as tasa,
                                  'IEPS (Monto fijo)' as impuesto
                              from cuentas_por_pagar cpp
                                inner join detalle_cuentas_por_pagar dcpp on cpp.id = dcpp.cuenta_por_pagar_id 
                              where cpp.folio_factura = :folio4
                                and cpp.num_serie_factura = :serie4
                                and dcpp.ieps_monto_fijo != 0
                            ) as impu
                              GROUP BY tasa
                          ) as impus	
                        ) as total
                        ;");
$stmt2->bindValue(':folio1', $folio, PDO::PARAM_STR);
$stmt2->bindValue(':serie1', $serie, PDO::PARAM_STR);
$stmt2->bindValue(':folio2', $folio, PDO::PARAM_STR);
$stmt2->bindValue(':serie2', $serie, PDO::PARAM_STR);
$stmt2->bindValue(':folio3', $folio, PDO::PARAM_STR);
$stmt2->bindValue(':serie3', $serie, PDO::PARAM_STR);
$stmt2->bindValue(':folio4', $folio, PDO::PARAM_STR);
$stmt2->bindValue(':serie4', $serie, PDO::PARAM_STR);
$stmt2->execute();
$row2 = $stmt2->fetch();

$stmt22 = $conn->prepare("SELECT (
                                    (
                                      select ifnull(sum(totalImpuesto),0) as totalImpuestoTras from (
                                        select 
                                              If(i.FKTipoImporte = 2,doc.Cantidad * ip.Tasa,ifnull((doc.Cantidad * doc.Precio) * (ip.Tasa / 100),0)) as totalImpuesto
                                        from ordenes_compra oc
                                          inner join detalle_orden_compra doc on oc.PKOrdenCompra = doc.FKOrdenCompra  
                                          inner join productos p on doc.FKProducto = p.PKProducto  
                                          inner join info_fiscal_productos ifp on p.PKProducto = ifp.FKProducto
                                          inner join impuestos_productos ip on ifp.PKInfoFiscalProducto = ip.FKInfoFiscalProducto
                                          inner join impuesto i on ip.FKImpuesto = i.PKImpuesto
                                          inner join tipos_impuestos ti on i.FKTipoImpuesto = ti.PKTipoImpuesto
                                          inner join datos_producto_proveedores dpp on doc.FKProducto = dpp.FKProducto and oc.FKProveedor = dpp.FKProveedor
                                        where oc.PKOrdenCompra in (
                                                                    select distinct 
                                                                        ieps.orden_compra_id
                                                                    from cuentas_por_pagar cpp
                                                                      inner join detalle_cuentas_por_pagar dcpp on cpp.id = dcpp.cuenta_por_pagar_id 
                                                                      inner join inventario_entrada_por_sucursales ieps on concat(cpp.folio_factura,' / ',cpp.num_serie_factura) = ieps.numero_documento
                                                                    where cpp.folio_factura = :folio1
                                                                      and cpp.num_serie_factura = :serie1
                                                                  )
                                            and (ti.PKTipoImpuesto = 1 or ti.PKTipoImpuesto = 3)
                                      ) as impuTras
                                    )
                                    -
                                    (
                                      select ifnull(sum(totalImpuesto),0) as totalImpuestoRet from (
                                        select 
                                              If(i.FKTipoImporte = 2,doc.Cantidad * ip.Tasa,ifnull((doc.Cantidad * doc.Precio) * (ip.Tasa / 100),0)) as totalImpuesto
                                        from ordenes_compra oc
                                          inner join detalle_orden_compra doc on oc.PKOrdenCompra = doc.FKOrdenCompra  
                                          inner join productos p on doc.FKProducto = p.PKProducto  
                                          inner join info_fiscal_productos ifp on p.PKProducto = ifp.FKProducto
                                          inner join impuestos_productos ip on ifp.PKInfoFiscalProducto = ip.FKInfoFiscalProducto
                                          inner join impuesto i on ip.FKImpuesto = i.PKImpuesto
                                          inner join tipos_impuestos ti on i.FKTipoImpuesto = ti.PKTipoImpuesto
                                          inner join datos_producto_proveedores dpp on doc.FKProducto = dpp.FKProducto and oc.FKProveedor = dpp.FKProveedor
                                        where oc.PKOrdenCompra in (
                                                                    select distinct 
                                                                        ieps.orden_compra_id
                                                                    from cuentas_por_pagar cpp
                                                                      inner join detalle_cuentas_por_pagar dcpp on cpp.id = dcpp.cuenta_por_pagar_id 
                                                                      inner join inventario_entrada_por_sucursales ieps on concat(cpp.folio_factura,' / ',cpp.num_serie_factura) = ieps.numero_documento
                                                                    where cpp.folio_factura = :folio2
                                                                      and cpp.num_serie_factura = :serie2
                                                                  )
                                          and ti.PKTipoImpuesto = 2
                                      ) as impuRet
                                    )
                                    +
                                    (
                                      select ifnull(sum(doc.Cantidad * doc.Precio),0) as subtotal
                                      from ordenes_compra oc
                                        inner join detalle_orden_compra doc on oc.PKOrdenCompra = doc.FKOrdenCompra  
                                        inner join datos_producto_proveedores dpp on doc.FKProducto = dpp.FKProducto and oc.FKProveedor = dpp.FKProveedor
                                      where oc.PKOrdenCompra in (
                                                                  select distinct 
                                                                      ieps.orden_compra_id
                                                                  from cuentas_por_pagar cpp
                                                                    inner join detalle_cuentas_por_pagar dcpp on cpp.id = dcpp.cuenta_por_pagar_id 
                                                                    inner join inventario_entrada_por_sucursales ieps on concat(cpp.folio_factura,' / ',cpp.num_serie_factura) = ieps.numero_documento
                                                                  where cpp.folio_factura = :folio3
                                                                    and cpp.num_serie_factura = :serie3
                                                                )
                                    )
                                 ) as total
                                 ;");
$stmt22->bindValue(':folio1', $folio, PDO::PARAM_STR);
$stmt22->bindValue(':serie1', $serie, PDO::PARAM_STR);
$stmt22->bindValue(':folio2', $folio, PDO::PARAM_STR);
$stmt22->bindValue(':serie2', $serie, PDO::PARAM_STR);
$stmt22->bindValue(':folio3', $folio, PDO::PARAM_STR);
$stmt22->bindValue(':serie3', $serie, PDO::PARAM_STR);
$stmt22->execute();
$row22 = $stmt22->fetch();

$stmt222 = $conn->prepare("SELECT sum(cpp.importe) as total
                        from cuentas_por_pagar cpp
                        where cpp.folio_factura = :folio1
                        and cpp.num_serie_factura = :serie1
                        ;");
$stmt222->bindValue(':folio1', $folio, PDO::PARAM_STR);
$stmt222->bindValue(':serie1', $serie, PDO::PARAM_STR);
$stmt222->execute();
$row222 = $stmt222->fetch();

$GLOBALS["TotalEnt"] = $row2['total'];
$GLOBALS["TotalOC"] = $row22['total'];
$GLOBALS["TotalFAC"] = $row222['total'];

$stmt3 = $conn->prepare("SELECT distinct 
                              cpp.folio_factura as folio,
                              cpp.num_serie_factura as serie,
                              DATE_FORMAT( (select now()), '%d/%m/%Y') as fecha,
                              concat(e.Nombres,' ',e.PrimerApellido,' ',e.SegundoApellido) as encargado,
                              us.usuario as email,
                              e.Telefono as telefono,
                              em.RazonSocial as razon_social,
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
                              pr.NombreComercial as proveedor
                          from cuentas_por_pagar cpp
                              inner join detalle_cuentas_por_pagar dcpp on cpp.id = dcpp.cuenta_por_pagar_id 
                              inner join inventario_entrada_por_sucursales ieps on concat(cpp.folio_factura,' / ',cpp.num_serie_factura) = ieps.numero_documento
                              inner join sucursales s on ieps.sucursal_id = s.id
                              inner join paises ps on s.pais_id = ps.PKPais
                              inner join estados_federativos ef on s.estado_id = ef.PKEstado
                              inner join empresas em on s.empresa_id = em.PKEmpresa
                              inner join usuarios us on ieps.usuario_creo_id = us.id
                              left join empleados_usuarios eu on us.id = eu.FKUsuario
                              left join empleados e on eu.FKEmpleado = e.PKEmpleado
                              left join proveedores pr on cpp.proveedor_id = pr.PKProveedor
                          where cpp.folio_factura = :folio1
                              and cpp.num_serie_factura = :serie1
                          ;");
$stmt3->bindValue(':folio1', $folio, PDO::PARAM_STR);
$stmt3->bindValue(':serie1', $serie, PDO::PARAM_STR);
$stmt3->execute();
$row3 = $stmt3->fetch();

$GLOBALS["FolioFactura"] = $row3['folio'];
$GLOBALS["SerieFactura"] = $row3['serie'];
$GLOBALS["Fecha"] = $row3['fecha'];
$GLOBALS["Sucursal"] = $row3['sucursal'];
$GLOBALS["Direccion"] = $row3['calle_sucursal'].' '.$row3['num_ext_sucursal'].' Int.'.$row3['num_int_sucursal'].'- '.$row3['prefijo'].', '.$row3['colonia_sucursal'].', '.$row3['municipio_sucursal'].', '.$row3['estado_sucursal'].', '.$row3['pais_sucursal'];
$GLOBALS["Empresa"] = $row3['razon_social'];
$GLOBALS["Responsable"] = $row3['encargado'];
$GLOBALS["Telefono"] = $row3['telefono'];
$GLOBALS["Email"] = $row3['email'];
$GLOBALS["Proveedor"] = $row3['proveedor'];

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
      $this->Cell(30, 10, 'Entrada por Orden de Compra', 0);
      $image_file = $GLOBALS["ruta"].$GLOBALS["logotipo"];
      $this->Image($image_file, 150, 9, 45, 15, '', '', 'T', false, 300, 'R', false, false, 0, false, false, false);

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
              <th width="50%" style="font-size: 12px;"><b>Responsable:</b> ' . $GLOBALS['Responsable'] . '</th>
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
$pdf->SetTitle('Entrada por Orden de Compra');
$pdf->SetSubject('Entrada por Orden de Compra');
$pdf->SetKeywords('Entrada por Orden de Compra');
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
    <td width="30%">' . $GLOBALS['Empresa'] . '</td>
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
    <th width="15%">Fecha de factura</th>
    <th width="55%" style="background-color: none;"></th>
    <th width="30%">Sucursal destino</th>
  </tr>
  <tr>
    <td width="15%">' . $GLOBALS['Fecha'] . '</td>
    <td width="55%" style="background-color: none;"></td>
    <td width="30%">' . $GLOBALS['Sucursal'] . '</td>
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
    <td width="25%">' . $GLOBALS['SerieFactura'] . '</td>
    <th width="5%" style="background-color: none;"></th>
    <th width="20%">Folio de factura:</th>
    <td width="30%">' . $GLOBALS['FolioFactura'] . '</td>
  </tr>
</table>';
$pdf->writeHTML($contacto, true, false, true, false, '');

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
<table>
  <tr>
    <th width="20%">Proveedor:</th>
    <td width="80%">' . $GLOBALS['Proveedor'] . '</td>

  </tr>
</table>';
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
        <th colspan="2" width="33%">Orden de Compra</th>
        <th colspan="2" width="33%">Entrada</th>
        <th colspan="2" width="34%">Factura</th>
      </tr>
    </thead>
    <tbody>';
$tbl .= '            
    <tr>
      <td width="16.5%">Subtotal:</td>
      <td width="16.5%"> $'.number_format($GLOBALS["SubTotalOC"],2,'.',',').'</td>
      <td width="16.5%"> Subtotal:</td>
      <td width="16.5%"> $'.number_format($GLOBALS["SubTotalEnt"],2,'.',',').'</td>
      <td width="17%">Subtotal:</td>
      <td width="17%"> $'.number_format($GLOBALS["SubTotalFAC"],2,'.',',').'</td>
    </tr>
    <tr>
      <td width="16.5%">Impuestos:</td>
      <td width="16.5%"> </td>
      <td width="16.5%"> Impuestos:</td>
      <td width="16.5%"> </td>
      <td width="17%">Impuestos:</td>
      <td width="17%"> </td>
    </tr>';
$tbl.=  '
      <tr>
        <td width="33%">
          <table cellspacing=0 cellpadding=0>';

          $stmtp = $conn->prepare("SELECT GROUP_CONCAT(id SEPARATOR ' / ') as id , GROUP_CONCAT(producto SEPARATOR ' / ') as producto, nombre, impuesto, tasa, pkImpuesto, sum(totalImpuesto) as totalImpuesto from (
                                      select ioc.FKProducto as id, 
                                             p.Nombre as producto, 
                                             ioc.Impuesto as nombre, 
                                             if(i.FKTipoImporte = 2,'',ioc.Tasa) as tasa, 
                                             ioc.FKImpuesto as pkImpuesto, 
                                             ioc.TotalImpuesto as totalImpuesto,
                                             i.Nombre as impuesto
                                      from impuestos_orden_compra ioc
                                        inner join impuesto i on ioc.FKImpuesto = i.PKImpuesto
                                                  inner join productos p on ioc.FKProducto = p.PKProducto
                                      where FKOrdenCompra in (
                                                  select distinct 
                                                      ieps.orden_compra_id
                                                  from cuentas_por_pagar cpp
                                                    inner join detalle_cuentas_por_pagar dcpp on cpp.id = dcpp.cuenta_por_pagar_id 
                                                    inner join inventario_entrada_por_sucursales ieps on concat(cpp.folio_factura,' / ',cpp.num_serie_factura) = ieps.numero_documento
                                                  where cpp.folio_factura = :folio1
                                                    and cpp.num_serie_factura = :serie1
                                                  )
                                    ) as impu
                                    GROUP BY nombre, tasa
                                    order by nombre, tasa desc
                                    ; ");
$stmtp->bindValue(':folio1', $folio, PDO::PARAM_STR);
$stmtp->bindValue(':serie1', $serie, PDO::PARAM_STR);
$stmtp->execute();
$rowp = $stmtp->fetchAll();


foreach ($rowp as $rp) { 

$tbl.=  '<tr>
          <td width="50%">'.$rp['impuesto'].' '.$rp['tasa'].'</td>
          <td width="50%"> $'.number_format($rp['totalImpuesto'],2,'.',',').'</td>
        </tr>';
}

$tbl .= '
          </table>
        </td>
        <td width="33%">
          <table cellspacing=0 cellpadding=0>';

          $stmtp2 = $conn->prepare("SELECT id, cantidad, precio, impuesto, tasa, totalImpuesto from (
            select id, cantidad, precio, impuesto, tasa, sum(totalImpuesto) as totalImpuesto from (
              select 
                  dcpp.id as id,
                  dcpp.cantidad as cantidad,
                  dcpp.precio as precio,
                  ifnull((dcpp.cantidad * dcpp.precio) * (dcpp.iva / 100),0) as totalImpuesto,
                  ifnull(dcpp.iva,0) as tasa,
                  'IVA' as impuesto
              from cuentas_por_pagar cpp
                inner join detalle_cuentas_por_pagar dcpp on cpp.id = dcpp.cuenta_por_pagar_id 
              where cpp.folio_factura = :folio1
                and cpp.num_serie_factura = :serie1
                and dcpp.iva != 0
            ) as impu
              GROUP BY tasa
            union
            select id, cantidad, precio, impuesto, tasa, sum(totalImpuesto) as totalImpuesto from (
              select 
                  dcpp.id as id,
                  dcpp.cantidad as cantidad,
                  dcpp.precio as precio,
                  ifnull((dcpp.cantidad * dcpp.precio) * (dcpp.ieps / 100),0) as totalImpuesto,
                  ifnull(dcpp.ieps,0) as tasa,
                  'IEPS' as impuesto
              from cuentas_por_pagar cpp
                inner join detalle_cuentas_por_pagar dcpp on cpp.id = dcpp.cuenta_por_pagar_id 
              where cpp.folio_factura = :folio2
                and cpp.num_serie_factura = :serie2
                and dcpp.ieps != 0
            ) as impu
              GROUP BY tasa
            union 
            select id, cantidad, precio, impuesto, tasa, sum(totalImpuesto) as totalImpuesto from (
              select 
                  dcpp.id as id,
                  dcpp.cantidad as cantidad,
                  dcpp.precio as precio,
                  ifnull(dcpp.ieps_monto_fijo * dcpp.cantidad,0) as totalImpuesto,
                  ifnull(dcpp.ieps_monto_fijo,0) as tasa,
                  'IEPS (Monto fijo)' as impuesto
              from cuentas_por_pagar cpp
                inner join detalle_cuentas_por_pagar dcpp on cpp.id = dcpp.cuenta_por_pagar_id 
              where cpp.folio_factura = :folio3
                and cpp.num_serie_factura = :serie3
                and dcpp.ieps_monto_fijo != 0
            ) as impu
              GROUP BY tasa desc
          ) as impus	;");

$stmtp2->bindValue(':folio1', $folio, PDO::PARAM_STR);
$stmtp2->bindValue(':serie1', $serie, PDO::PARAM_STR);
$stmtp2->bindValue(':folio2', $folio, PDO::PARAM_STR);
$stmtp2->bindValue(':serie2', $serie, PDO::PARAM_STR);
$stmtp2->bindValue(':folio3', $folio, PDO::PARAM_STR);
$stmtp2->bindValue(':serie3', $serie, PDO::PARAM_STR);
$stmtp2->execute();
$rowp2 = $stmtp2->fetchAll();
          

foreach ($rowp2 as $rp2) { 

  $tbl.=  '<tr>
            <td width="50%">'.$rp2['impuesto'].' '.$rp2['tasa'].'</td>
            <td width="50%"> $'.number_format($rp2['totalImpuesto'],2,'.',',').'</td>
          </tr>';
}

$tbl .= '
          </table>
        </td>
        <td width="34%">
          <table cellspacing=0 cellpadding=0>';

          $stmtp3 = $conn->prepare("SELECT impuesto, tasa, totalImpuesto from(  
                                    select impuesto, sum(tasa) as tasa, sum(totalImpuesto) as totalImpuesto from(  
                                      select cpp.id as id,
                                          ifnull(cpp.iva,0) as totalImpuesto,
                                          ifnull(cpp.iva,0) as tasa,
                                          'IVA' as impuesto
                                      from cuentas_por_pagar cpp
                                      where cpp.folio_factura = :folio1
                                        and cpp.num_serie_factura = :serie1
                                        and cpp.iva != 0
                                    ) as imp
                                        group by impuesto
                                    union 
                                        select impuesto, sum(tasa) as tasa, sum(totalImpuesto) as totalImpuesto from(  
                                      select cpp.id as id,
                                          ifnull(cpp.ieps,0) as totalImpuesto,
                                          ifnull(cpp.ieps,0) as tasa,
                                          'IEPS' as impuesto
                                      from cuentas_por_pagar cpp
                                      where cpp.folio_factura = :folio2
                                        and cpp.num_serie_factura = :serie2
                                        and cpp.ieps != 0
                                    ) as imp
                                        group by impuesto
                                  )as impu
                                    group by impuesto
                                    ;");

$stmtp3->bindValue(':folio1', $folio, PDO::PARAM_STR);
$stmtp3->bindValue(':serie1', $serie, PDO::PARAM_STR);
$stmtp3->bindValue(':folio2', $folio, PDO::PARAM_STR);
$stmtp3->bindValue(':serie2', $serie, PDO::PARAM_STR);
$stmtp3->execute();
$rowp3 = $stmtp3->fetchAll();

foreach ($rowp3 as $rp3) { 

  $tbl.=  '<tr>
            <td width="50%">'.$rp3['impuesto'].' '.$rp3['tasa'].'</td>
            <td width="50%"> $'.number_format($rp3['totalImpuesto'],2,'.',',').'</td>
          </tr>';
  }

$tbl .= '            
          </table>
        </td>
      </tr>
      
      <tr>
        <td width="16.5%">Total:</td>
        <td width="16.5%"> $'.number_format($GLOBALS["TotalOC"],2,'.',',').'</td>
        <td width="16.5%"> Total:</td>
        <td width="16.5%"> $'.number_format($GLOBALS["TotalEnt"],2,'.',',').'</td>
        <td width="17%">Total:</td>
        <td width="17%"> $'.number_format($GLOBALS["TotalFAC"],2,'.',',').'</td>
      </tr>';


$tbl.= '</table>';

$pdf->writeHTML($tbl, true, false, true, false, '');


ob_end_clean();

$pdf->Output("Entrada por Orden de Compra".'.pdf', 'D');
return true;
?>
