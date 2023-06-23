<?php
session_start();
require_once '../../../../include/db-conn.php';
$user = $_SESSION["Usuario"];
$PKEmpresa = $_SESSION["IDEmpresa"];

$folio = $_GET['f'];

$stmt = $conn->prepare("SELECT distinct s.empresa_id as empresa, 
                            if(ieps.is_movimiento is null or ieps.is_movimiento = null,0, ieps.is_movimiento) as is_movimiento, 
                            ieps.tipo_entrada as tipoId,
                            ifnull(ieps.cliente_id,0) as cliente,
                            ifnull(ieps.proveedor_id,0) as proveedor,
                            ifnull(ieps.sucursal_origen_id,0) as sucOrigen
                        FROM inventario_entrada_por_sucursales ieps
                            inner join sucursales s on ieps.sucursal_id = s.id 
                        WHERE ieps.folio_entrada = :id and s.empresa_id = :empresa
                        group by ieps.id");
$stmt->bindValue(':id', $folio, PDO::PARAM_INT);
$stmt->bindValue(':empresa', $PKEmpresa, PDO::PARAM_INT);
$stmt->execute();
$row = $stmt->fetch();


$GLOBALS["PKEmpresaIEPS"] = $row['empresa'];
$GLOBALS["IsMovimiento"] = $row['is_movimiento'];
$GLOBALS["TipoEntrada"] = $row['tipoId'];
$GLOBALS["Cliente"] = $row['cliente'];
$GLOBALS["Proveedor"] = $row['proveedor'];
$GLOBALS["SucOrigen"] = $row['sucOrigen'];

if (isset($_SESSION["Usuario"])) {
    require_once '../../../../include/db-conn.php';
    $user = $_SESSION["Usuario"];

    if($GLOBALS["PKEmpresaIEPS"] != $PKEmpresa){
      header("location:../../../inventarios_productos/catalogos/entradas_productos/");
    }
} else {
    header("location:../../../dashboard.php");
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
  <link rel="icon" type="image/png" href="../../../../img/header/bluTimlid.png">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Timlid | Ver entrada</title>

  <!-- Bootstrap core JavaScript-->
  <script src="../../../../vendor/jquery/jquery.min.js"></script>
  <script src="../../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="../../../../vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Page level plugins -->
  <script src="../../../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../../../vendor/datatables/dataTables.bootstrap4.min.js"></script>
  <script src="../../../../vendor/datatables/dataTables.responsive.js"></script>
  <script src="../../../../vendor/datatables/dataTables.buttons.js"></script>
  <script src="../../../../vendor/datatables/buttons.bootstrap4.min.js"></script>
  <script src="../../../../vendor/datatables/buttons.html5.min.js"></script>
  <script src="../../../../vendor/jszip/jszip.min.js"></script>

  <!-- Page level custom scripts
    <script src="js/demo/datatables-demo.js"></script>
    -->

  <!-- Custom fonts for this template -->
  <link href="../../../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

  <!-- Custom styles for this template -->
  <link href="../../../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../../../css/slimselect.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../../style/ver_entrada.css">

  <!-- Custom scripts for all pages-->

  <script src="../../../../js/sb-admin-2.min.js"></script>
  <script src="../../../../js/scripts.js"></script>

  <!-- Custom styles for this page -->
  <link href="../../../../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
  <link href="../../../../vendor/datatables/responsive.dataTables.css" rel="stylesheet">
  <link rel="stylesheet" href="../../../../vendor/datatables/buttons.dataTables.css">
  <link href="../../../../vendor/datatables/buttons.bootstrap4.min.css" rel="stylesheet">
  <link href="../../../../css/stylesTable.css" rel="stylesheet">
  <link href="../../../../css/styles.css" rel="stylesheet">
  <link rel="stylesheet" href="../../../../css/croppie.css" />

  <script src="../../../../js/numeral.min.js" charset="utf-8"></script>
  <script src="../../../../js/croppie.js"></script>

  <script src="../../../../js/sweet/sweetalert2.js"></script>
  <link rel="stylesheet" href="../../../../css/sweetalert2.css">
  <link rel="stylesheet" href="../../../../css/notificaciones.css">

  <script src="../../../../js/notificaciones_timlid.js" charset="utf-8"></script>
  <script src="../../js/ver_entrada.js" charset="utf-8"></script>
  <script src="../../../../js/numeral.min.js"></script>
  <link href="../../../../css/lobibox.min.css" rel="stylesheet">
  <link href="../../../../css/stylesNewTable.css" rel="stylesheet">
  
  <script src="../../../../js/lobibox.min.js"></script>

</head>

<body id="page-top" class="sidebar-toggled">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <?php
    $ruta = "../../../";
    $ruteEdit = $ruta . "central_notificaciones/";
    require_once $ruta . 'menu3.php';
    ?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
      <input type="hidden" id="txtUsuario" value="<?=$_SESSION['PKUsuario'];?>">
      <input type="hidden" id="txtRuta" value="<?=$ruta;?>">
      <input type="hidden" id="txtEdit" value="<?=$ruteEdit;?>">
      <input type="hidden" id="txtPantalla" value="13">

      <!-- Main Content -->
      <div id="content">

        <?php
        $rutatb = "../../../";
        $icono = 'ICONO-ENTRADA-AZUL.svg';
        $titulo = 'Ver entrada';
        $backIcon = true;
        $backRoute = (isset($_GET['ed']) && $_GET['ed']) ? "./editar_entrada.php?f=".$_GET['f'] : "./";
        require_once $rutatb . 'topbar.php';
        ?>

        <!-- Begin Page Content -->
        <div class="container-fluid">
          <!-- DataTales Example -->
          <div class="card mb-4">
            <div class="card-body py-3">
              <div class="data-container">
                <form id="frmEntradaOC">
                  <div class="form-group">
                    <div class="row">
                      <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-xs-6">
                        <div class="purchases-disabled">
                          <label for="cmbOrdenCompra">Orden de compra:</label>
                          <input class="form-control" name="cmbOrdenCompra" id="cmbOrdenCompra" readonly>
                        </div>
                        <div class="transfer-disabled">
                          <label for="cmbOrderPedido">Pedido:</label>
                          <input class="form-control" name="cmbOrderPedido" id="cmbOrderPedido" readonly>
                        </div>
                        <div class="directBranch-disabled">
                          <label for="txtRefereciaEDBranch">Referencia:</label>
                          <input class="form-control" name="txtRefereciaEDBranch" id="txtRefereciaEDBranch" readonly>
                        </div>
                        <div class="directProvider-disabled">
                          <label for="txtRefereciaEDProvider">Referencia:</label>
                          <input class="form-control" name="txtRefereciaEDProvider" id="txtRefereciaEDProvider" readonly>
                        </div>
                        <div class="directCustomer-disabled">
                          <label for="txtRefereciaEDCustomer">Referencia:</label>
                          <input class="form-control" name="txtRefereciaEDCustomer" id="txtRefereciaEDCustomer" readonly>
                        </div>
                      </div>
                      <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-xs-6">
                        <div class="purchases-disabled">
                          <label for="cmbProveedor">Proveedor:</label>
                          <input class="form-control" name="cmbProveedor" id="cmbProveedor" readonly>
                        </div>
                        <div class="transfer-disabled">
                          <label for="cmbSucursalDestino">Sucursal de destino:</label>
                          <input class="form-control" name="cmbSucursalDestino" id="cmbSucursalDestino" readonly>
                        </div>
                        <div class="directBranch-disabled">
                          <label for="txtSucDestinoEDBranch">Sucursal de destino:</label>
                          <input class="form-control" name="txtSucDestinoEDBranch" id="txtSucDestinoEDBranch" readonly>
                        </div>
                        <div class="directProvider-disabled">
                          <label for="txtSucDestinoEDProvider">Sucursal de destino:</label>
                          <input class="form-control" name="txtSucDestinoEDProvider" id="txtSucDestinoEDProvider" readonly>
                        </div>
                        <div class="directCustomer-disabled">
                          <label for="txtSucDestinoEDCustomer">Sucursal de destino:</label>
                          <input class="form-control" name="txtSucDestinoEDCustomer" id="txtSucDestinoEDCustomer" readonly>
                        </div>
                      </div>
                      <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-xs-6">
                        <div class="purchases-disabled">
                          <label for="cmbSucursal">Sucursal:</label>
                          <input class="form-control" name="cmbSucursal" id="cmbSucursal" readonly>
                        </div>
                        <div class="transfer-disabled">
                          <label for="cmbSucursalOrigen">Sucursal de origen:</label>
                          <input class="form-control" name="cmbSucursalOrigen" id="cmbSucursalOrigen" readonly>
                        </div>
                        <div class="directBranch-disabled">
                          <label for="txtOrigenEDBranch">Sucursal de origen:</label>
                          <input class="form-control" name="txtOrigenEDBranch" id="txtOrigenEDBranch" readonly>
                        </div>
                        <div class="directProvider-disabled">
                          <label for="txtProveedorEDProvider">Proveedor:</label>
                          <input class="form-control" name="txtProveedorEDProvider" id="txtProveedorEDProvider" readonly>
                        </div>
                        <div class="directCustomer-disabled">
                          <label for="txtClienteEDCustomer">Cliente:</label>
                          <input class="form-control" name="txtClienteEDCustomer" id="txtClienteEDCustomer" readonly>
                        </div>
                      </div>
                      <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-6">
                        <div class="purchases-disabled">
                          <label for="usr">No. de documento:</label>
                          <div class="input-group">
                            <input required class="form-control" type="text" name="txtNoDocumento" id="txtNoDocumento" placeholder="Folio" style="float:left;" readonly>
                          </div>
                        </div>
                        <div class="directBranch-disabled">
                          <label for="txtNotasEDBranch">Notas:</label>
                          <textarea disabled class="form-control" name="txtNotasEDBranch" id="txtNotasEDBranch" readonly></textarea>
                        </div>
                        <div class="directProvider-disabled">
                          <label for="usr">Serie*:</label>
                          <div class="input-group">
                            <input disabled required class="form-control alphaNumeric-only" type="text" name="txtSerieProviderED" id="txtSerieProviderED" placeholder="Serie" style="float:left;" onchange="validEmptyInputSLProvider('txtSerieProviderED', 'invalid-serieProviderED', 'La entrada debe de tener número de serie.')">
                            <div class="invalid-feedback" id="invalid-serieProviderED">La entrada debe de tener número de serie.</div>
                          </div>
                        </div>
                        <div class="directCustomer-disabled">
                          <label for="txtNotasEDCustomer">Notas:</label>
                          <textarea disabled class="form-control" name="txtNotasEDCustomer" id="txtNotasEDCustomer" readonly></textarea>
                        </div>
                      </div><div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-6">
                        <div class="purchases-disabled">
                          <label for="usr">Serie de factura:</label>
                          <div class="input-group">
                            <input required class="form-control" type="text" name="txtSerie" id="txtSerie" placeholder="Serie" style="float:left;" readonly>
                          </div>
                        </div>
                        <div class="directProvider-disabled">
                          <label for="usr">Folio*:</label>
                          <div class="input-group">
                            <input disabled required class="form-control alphaNumeric-only" type="text" name="txtNoDocumentoProviderED" id="txtNoDocumentoProviderED" placeholder="Folio" style="float:left;" onchange="validEmptyInputSLProvider('txtNoDocumentoProviderED', 'invalid-noDocumentoProviderED', 'La entrada debe de tener un número de folio.')">
                            <div class="invalid-feedback" id="invalid-noDocumentoProviderED">La entrada debe de tener número de folio.</div>
                          </div>
                        </div>
                      </div>
                      <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-xs-6">
                        <div class="purchases-disabled">
                          <label for="usr">Subtotal:</label>
                          <div class="input-group">
                            <input required class="form-control" type="text" name="txtSubtotal" id="txtSubtotal" placeholder="Ej. 1000.00" style="float:left;" readonly>
                          </div>
                        </div>
                        <div class="directProvider-disabled">
                          <label for="usr">Subtotal*:</label>
                          <div class="input-group">
                            <input disabled required class="form-control numericDecimal-only" type="text" name="txtSubtotalProviderED" id="txtSubtotalProviderED" placeholder="Ej. 1000.00" style="float:left;" onchange="validEmptyInputSLProvider('txtSubtotalProviderED', 'invalid-subtotalProviderED', 'La entrada debe de tener subtotal.')">
                            <div class="invalid-feedback" id="invalid-subtotalProviderED">La entrada debe de tener subtotal.</div>
                          </div>
                        </div>
                      </div>
                      <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-xs-6">
                        <div class="purchases-disabled">
                          <label for="usr">IVA (Monto):</label>
                          <div class="input-group">
                            <input class="form-control" type="text" name="txtIva" id="txtIva" placeholder="Ej. 1000.00" style="float:left;" value="0" readonly>
                          </div>
                        </div>
                        <div class="directProvider-disabled">
                          <label for="usr">IVA (Monto):</label>
                          <div class="input-group">
                            <input disabled class="form-control numericDecimal-only" type="text" name="txtIvaProviderED" id="txtIvaProviderED" placeholder="Ej. 1000.00" style="float:left;" value="0" onchange="validEmptyInputSLProvider('txtIvaProviderED', 'invalid-ivaProviderED', 'La entrada debe de tener IVA.')">
                            <div class="invalid-feedback" id="invalid-ivaProviderED">La entrada debe de tener IVA.</div>
                          </div>
                        </div>
                      </div>
                      <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-xs-6">
                        <div class="purchases-disabled">
                          <label for="usr">IEPS (Monto):</label>
                          <div class="input-group">
                            <input class="form-control" type="text" name="txtIEPS" id="txtIEPS" placeholder="Ej. 1000.00" style="float:left;" value="0" readonly>
                          </div>
                        </div>
                        <div class="directProvider-disabled">
                          <label for="usr">IEPS (Monto):</label>
                          <div class="input-group">
                            <input disabled class="form-control numericDecimal-only" type="text" name="txtIEPSProviderED" id="txtIEPSProviderED" placeholder="Ej. 1000.00" style="float:left;" value="0" onchange="validEmptyInputSLProvider('txtIEPSProviderED', 'invalid-iepsProviderED', 'La entrada debe de tener IEPS.')">
                            <div class="invalid-feedback" id="invalid-iepsProviderED">La entrada debe de tener IEPS.</div>
                          </div>
                        </div>
                      </div>
                      <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-xs-6">
                        <div class="purchases-disabled">
                          <label for="usr">Importe factura:</label>
                          <div class="input-group">
                            <input required class="form-control" type="text" name="txtImporte" id="txtImporte" placeholder="Ej. 1000.00" style="float:left;" readonly>
                          </div>
                        </div>
                        <div class="directProvider-disabled">
                          <label for="usr">Importe factura*:</label>
                          <div class="input-group">
                            <input disabled equired class="form-control numericDecimal-only" type="text" name="txtImporteProviderED" id="txtImporteProviderED" placeholder="Ej. 1000.00" style="float:left;" onchange="validEmptyInputSLProvider('txtImporteProviderED', 'invalid-importeProviderED', 'La entrada debe de tener importe.')">
                            <div class="invalid-feedback" id="invalid-importeProviderED">La entrada debe de tener importe.</div>
                          </div>
                        </div>
                      </div>
                      <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-xs-6">
                        <div class="purchases-disabled">
                          <label for="usr">Descuento (Monto):</label>
                          <div class="input-group">
                            <input class="form-control" type="text" name="txtDescuento" id="txtDescuento" placeholder="Ej. 1000.00" style="float:left;" readonly>
                          </div>
                        </div>
                        <div class="directProvider-disabled">
                          <label for="cmbTipoProviderED">Tipo*:</label>
                          <div class="input-group">
                            <select disabled name="cmbTipoProviderED" id="cmbTipoProviderED" required onchange="validEmptyInputSLProvider('cmbTipoProviderED', 'invalid-tipoProviderED', 'La entrada debe de tener un tipo.')"></select>
                            <div class="invalid-feedback" id="invalid-tipoProviderED">La entrada debe de tener un tipo.</div>
                          </div>
                        </div>
                      </div>
                      <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-xs-6">
                        <div class="purchases-disabled">
                          <label for="usr">Fecha de factura:</label>
                          <div class="input-group">
                            <input required class="form-control" type="date" name="txtFechaFactura" id="txtFechaFactura" style="float:left;" readonly>
                          </div>
                        </div>
                        <div class="directProvider-disabled">
                          <label for="usr">Fecha de factura*:</label>
                          <div class="input-group">
                            <input disabled required class="form-control" type="date" name="txtFechaFacturaProviderED" id="txtFechaFacturaProviderED" style="float:left;" onchange="validEmptyInputSLProvider('txtFechaFacturaProviderED', 'invalid-fechaFacturaProviderED', 'La entrada debe de tener una fecha de factura.')">
                            <div class="invalid-feedback" id="invalid-fechaFacturaProviderED">La entrada debe de una fecha de factura.</div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="row">
                      <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-xs-6">
                        <div class="purchases-disabled" style="margin-left:20px">
                          <input class="form-check-input" type="checkbox" id="cbxRemision" name="cbxRemision" readonly disabled>
                          <label class="form-check-label" for="cbxRemision">Remisión</label>
                        </div>
                        <div class="directProvider-disabled">
                          <label for="txtNotasEDProvider">Notas:</label>
                          <textarea class="form-control" name="txtNotasEDProvider" id="txtNotasEDProvider" readonly></textarea>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="row">
                      <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="purchases-disabled">
                          <label for="">Notas:</label>
                          <textarea class="form-control" name="txaNotaEntrada" id="txaNotaEntrada" placeholder="Escribe las notas aquí..." rows="2" cols="200" readonly></textarea>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group purchases-disabled opacidad">
                    <div class="row">
                      <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-6">
                        <label for="usr">Referencia:</label>
                        <div class="input-group">
                          <span name="txtReferenciaP" id="txtReferenciaP"></span>
                        </div>
                      </div>
                      <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-6">
                        <label for="usr">Proveedor:</label>
                        <div class="input-group">
                          <span name="txtProveedorP" id="txtProveedorP"></span>
                        </div>
                      </div>
                      <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-6">
                        <label for="usr">Fecha de emisión:</label>
                        <div class="input-group">
                          <span name="txtFechaEmisionP" id="txtFechaEmisionP"></span>
                        </div>
                      </div>
                      <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-6">
                        <label for="usr">Fecha estimada de entrega:</label>
                        <div class="input-group">
                          <span name="txtFechaEstimadaP" id="txtFechaEstimadaP"></span>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        <label for="usr">Dirección de entrega:</label>
                        <div class="input-group">
                          <span name="txtDireccionP" id="txtDireccionP"></span>
                        </div>
                      </div>
                      <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        <label for="usr">Notas:</label>
                        <div class="input-group">
                          <span name="NotasInternasP" id="NotasInternasP"></span>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="form-group">
                    <div class="row">
                      <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 purchases-disabled">
                        <table class="table-sm tblCoti dataTable no-footer" id="tblProductosEntradaOC" width="100%" cellspacing="0">
                          <thead>
                            <tr>
                              <th>ID</th>
                              <th>Clave/Producto</th>
                              <th>Cantidad recibida</th>
                              <th>Unidad de medida</th>
                              <th>Lote</th>
                              <th>Serie</th>
                              <th>Fecha de caducidad</th>
                              <th>Precio unitario</th>
                              <th>Impuestos</th>
                              <th>Importe</th>
                            </tr>
                          </thead>
                        </table>
                        <table class="table opacidad">
                          <tfoot>
                            <tr>
                              <th style="text-align: right;"></th>
                              <th style="text-align: right; width:400px!important"></span>
                              </th>
                              <th style="width:60px;"></th>
                            </tr>
                            
                            <tr class="total redondearAbajoIzq">
                              <th style="text-align: right;" class="redondearAbajoIzq">Total:</th>
                              <th style="text-align: right;"><span id="Total">0</span></th>
                              <th></th>
                            </tr>
                          </tfoot>
                        </table>
                      </div>
                      <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 transfer-disabled">
                        <table class="table opacidad" id="tblProductosTraspaso" width="100%" cellspacing="0">
                          <thead>
                            <tr>
                              <th>ID</th>
                              <th>Clave/Producto</th>
                              <th>Cantidad recibida</th>
                              <th>Unidad de medida</th>
                              <th>Lote</th>
                              <th>Serie</th>
                              <th>Fecha de caducidad</th>
                            </tr>
                          </thead>
                        </table>
                        <table class="table opacidad">
                          <tfoot>
                            <tr>
                              <th style="text-align: right;"></th>
                              <th style="text-align: right; width:400px!important"></th>
                              <th style="width:60px;"></th>
                            </tr>
                            <tr>
                              <th style="text-align: right;">Total:</th>
                              <th id="impuestos"><span id="TotalTras">0</span></th>
                              <th></th>
                            </tr>
                            <tr class="total redondearAbajoIzq">
                              <th style="text-align: right;" class="redondearAbajoIzq"></th>
                              <th style="text-align: right;"></th>
                              <th></th>
                            </tr>
                          </tfoot>
                        </table>
                      </div>
                      <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 directBranch-disabled">
                        <table class="table opacidad" id="tblProductosEntradaDCSucursal" width="100%" cellspacing="0">
                          <thead>
                            <tr>
                              <th>ID</th>
                              <th>Clave/Producto</th>
                              <th>Cantidad a entrar</th>
                              <th>Unidad de medida</th>
                              <th>Lote</th>
                              <th>Serie</th>
                              <th>Fecha de caducidad</th>
                              <th></th>
                            </tr>
                          </thead>
                        </table>
                      </div>
                      <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 directProvider-disabled">
                        <table class="table opacidad" id="tblProductosEntradaDProvider" width="100%" cellspacing="0">
                          <thead>
                            <tr>
                              <th>ID</th>
                              <th>Clave/Producto</th>
                              <th>Cantidad a entrar</th>
                              <th>Unidad de medida</th>
                              <th>Precio</th>
                              <th>Lote</th>
                              <th>Serie</th>
                              <th>Fecha de caducidad</th>
                              <th>Impuestos</th>
                              <th>Importe</th>
                              <th></th>
                            </tr>
                          </thead>
                        </table>
                        <table class="table opacidad">
                          <tfoot>
                            <tr>
                              <th style="text-align: right;">Subtotal:</th>
                              <th style="text-align: right; width:400px!important">$ <span id="Subtotal">0.00</span>
                              </th>
                              <th style="width:60px;"></th>
                            </tr>
                            <tr>
                              <th style="text-align: right;">Impuestos:</th>
                              <th id="impuestosEDProvider"></th>
                              <th></th>
                            </tr>
                            <tr class="total redondearAbajoIzq">
                              <th style="text-align: right;" class="redondearAbajoIzq">Total:</th>
                              <th style="text-align: right;"> $ <span id="TotalEDProvider">0.00</span></th>
                              <th></th>
                            </tr>
                            <tr>
                              <th style="text-align: right;" class="redondearAbajoIzq"></th>
                              <th style="text-align: right;"> <div class="invalid-feedback" id="invalid-totalED">El importe de la factura no coincide con el total.</div> </th>
                              <th></th>
                            </tr>
                          </tfoot>
                        </table>
                      </div>
                      <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 directCustomer-disabled">
                        <table class="table opacidad" id="tblProductosEntradaDCCliente" width="100%" cellspacing="0">
                          <thead>
                            <tr>
                              <th>ID</th>
                              <th>Clave/Producto</th>
                              <th>Cantidad a entrar</th>
                              <th>Unidad de medida</th>
                              <th>Lote</th>
                              <th>Serie</th>
                              <th>Fecha de caducidad</th>
                              <th></th>
                            </tr>
                          </thead>
                        </table>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
            </div>
            <input id="entryTempIDD" type="hidden" value="0">
            <input id="entryTempIDTrasD" type="hidden" value="0">
          </div>

        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

      <!-- Footer -->
      <?php
$rutaf = "../../../";
require_once $rutaf . 'footer.php';
?>
      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <script src="../../../../js/slimselect.min.js"></script>
  <script src="../../../../js/sb-admin-2.min.js"></script>
  <script src="../../../../js/scripts.js"></script>
</body>
<script src="../../../../js/Sortable.js"></script>
<script src="../../../../js/pagination/pagination.js"></script>
<script>
loadAlertsNoti('alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
setInterval(loadAlertsNoti, 10000, 'alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
</script>

<script>
  var folio = '<?php echo $folio;?>';
  var tipoE = '<?php echo $GLOBALS["TipoEntrada"];?>';
  _global.cliente = '<?php echo $GLOBALS["Cliente"];?>';
  _global.proveedor = '<?php echo $GLOBALS["Proveedor"];?>';
  _global.sucOrigen = '<?php echo $GLOBALS["SucOrigen"];?>';
</script>

</html>