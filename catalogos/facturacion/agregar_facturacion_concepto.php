<?php
$ruta = "../";
$screen = 14;
$jwt_ruta = "../../";
require_once '../validarPermisoPantalla.php';
require_once '../jwt.php';

date_default_timezone_set('America/Mexico_City');
$token = $_SESSION['token_ld10d'];

$min = new DateTime();
$min->sub(new DateInterval('P3D'));



if (isset($_SESSION["Usuario"]) && $permiso === 1) {
  require_once '../../include/db-conn.php';
  $stmt = $conn->prepare("SELECT * FROM estados_federativos");
  $stmt->execute();
  $estados = $stmt->fetchAll();

  $stmt = $conn->prepare("SELECT * FROM tipo_empleado");
  $stmt->execute();
  $roles = $stmt->fetchAll();
 

  if (isset($_REQUEST['idCotizacionF'])) {
    $facturar = $_REQUEST['idCotizacionF'];
    $select = "1";
  } else if (isset($_REQUEST['idVentaDirecta'])) {
    $facturar = $_REQUEST['idVentaDirecta'];
    $select = "2";
  } else {
    $facturar = "";
    $select = "";
  }
} else {
  header("location:../dashboard.php");
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <link rel="icon" type="image/png" href="../../img/header/bluTimlid.png">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Timlid | Facturación</title>

  <!-- Bootstrap core JavaScript-->
  <script src="../../vendor/jquery/jquery.min.js"></script>
  <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>

  <script src="https://unpkg.com/@popperjs/core@2/dist/umd/popper.js"></script>

  <!-- Custom scripts for all pages-->

  <!-- Page level plugins -->
  <script src="../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../vendor/datatables/dataTables.bootstrap4.min.js"></script>
  <script src="../../vendor/datatables/dataTables.buttons.js"></script>
  <script src="../../vendor/datatables/buttons.bootstrap4.min.js"></script>
  <script src="../../vendor/datatables/buttons.html5.min.js"></script>
  <script src="../../vendor/jszip/jszip.min.js"></script>

  <!-- Custom fonts for this template -->
  <link href="../../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

  <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

  <!-- Custom styles for this template -->
  <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../vendor/datatables/buttons.dataTables.css" rel="stylesheet">
  <link href="../../vendor/datatables/buttons.bootstrap4.min.css" rel="stylesheet">
  <link href="../../css/styles.css" rel="stylesheet">
  <!-- <link href="../../css/stylesTable.css" rel="stylesheet"> -->
  <link href="../../css/stylesNewTable.css" rel="stylesheet">
  <link href="../../css/slimselect.min.css" rel="stylesheet">

  <link rel="stylesheet" href="../../css/sweetalert2.css">
  <link href="../../css/lobibox.min.css" rel="stylesheet">

  <link rel="stylesheet" href="../../css/notificaciones.css">
  <script src="../../js/notificaciones_timlid.js" charset="utf-8"></script>

  <link rel="stylesheet" href="css/agregar_factura_concepto.css">

  <script src="../../js/lobibox.min.js"></script>
  <script src="../../js/jquery.redirect.js"></script>

  <style>
    .bar-title{
        background-color:#006dd9;
        color:white;
        padding:0.75rem;
        font-size:18px;
    }
  </style>
</head>

<body id="page-top">
  <div id=loader></div>

  <!-- Page Wrapper -->
  <div id="wrapper">
    <!-- Sidebar -->
    <?php
    $icono = 'ICONO-FACTURACION-AZUL.svg';
    $titulo = 'Crear factura por conceptos';
    $backIcon = true;
    $ruteEdit = $ruta . "central_notificaciones/";
    require_once $ruta . 'menu3.php';
    ?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <?php
        $rutatb = "../";
        require_once '../topbar.php';
        ?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <div class="card shadow mb-4">
            <div class="card-body">
              <form id="datos-factura">
                <div class="row d-flex align-items-center" style="height:5rem;margin-bottom:1rem">
                    <div class="col-2">
                        <label class="" for="">
                            <p> <b class="textBlue">Afectar inventario:</b> </p> 
                        </label>
                    </div>
                    <div class="col-2">
                        <div class="custom-control custom-switch" id="divChkAfectarInventario">
                            
                            <input type="checkbox" class="check-custom" id="chkAfectarInventario">
                            <label class="shadow-sm check-custom-label" for="chkAfectarInventario">
                                <div class="circle"></div>
                                <div class="check-inactivo">Inactivo</div>
                                <div class="check-activo">Activo</div>
                            </label>
                            
                            
                        </div>
                    </div>
                    <div class="col-3">
                        <div id="comboAfectarInventario">
                            <label for="txaReferencia">Sucursal:</label>
                            <select name="cmbSucursales" id="cmbSucursales"></select>
                            <div class="invalid-feedback" id="invalid-afectarInventario">La factura debe de tener una sucursal.</div>
                        </div>
                    </div>
                </div>
               <div class="row">
                  <div class="col-lg-12">
                    <p class="bar-title">Información de venta</p>
                  </div>
                </div>  
                <div class="row">
                  <div class="col-lg-4">
                    <label for="">Cliente:</label>
                    <select name="cmbCliente" id="cmbCliente"></select>
                    <input type="hidden" id="rfc-cliente">
                    <input type="hidden" id="txtPrefactura">
                    <div id="legal_name_hidden">
                        <input class="form-control" type="text" id="txtLegalNamePG" placeholder="Agregue razón social de cliente">
                        <div class="invalid-feedback" id="invalid-razonSocialPG">La factura debe de tener la razón social del cliente.</div>
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <label for="">Serie</label>
                    <input class="form-control" type="text" name="txtSerie" id="txtSerie" readonly>
                  </div>
                  <div class="col-lg-3">
                    <label for="">Folio</label>
                    <input class="form-control" type="text" name="txtFolio" id="txtFolio" readonly>
                  </div>
                  <div class="col-lg-2">
                    <label for="">Fecha emision:</label>
                    <input class="form-control" type="date" name="txtFechaEmision" id="txtFechaEmision" min="<?= $min->format("Y-m-d"); ?>" max="<?= date("Y-m-d"); ?>" value="<?= date("Y-m-d"); ?>">
                  </div>
                </div>

                <br>
                <div class="row">
                  <div class="col-lg-3">
                    <label for="">Uso CFDI*:</label>
                    <select name="cmbUsoCFDI" id="cmbUsoCFDI" required=""></select>
                    <div class="invalid-feedback" id="invalid-usoCFDI">La factura debe de tener un uso de CFDI.</div>
                  </div>
                  <div class="col-lg-3">
                    <label for="">Formas de pago*:</label>
                    <div class="row">
                      <div class="col-lg-12 input-group">
                        <select name="cmbFormasPago" id="cmbFormasPago" required=""></select>
                        <div class="invalid-feedback" id="invalid-formasPago">La factura debe de tener una forma de pago.</div>
                      </div>
                    </div>
                  </div>

                  <div class="col-lg-3">
                    <label for="">Métodos de pago*:</label>
                    <div class="row">
                      <div class="col-lg-12 input-group">
                        <select name="cmbMetodoPago" id="cmbMetodoPago" required="">
                          "<option data-placeholder='true'></option>";
                          <option value="PUE">PUE - Pago en una sola exhibición (de contado)</option>
                          <option value="PPD">PPD - Pago en parcialidades o diferido (total o parcialmente a crédito)</option>
                        </select>
                        <div class="invalid-feedback" id="invalid-metodosPago">La factura debe de tener un método de pago.</div>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <label for="">Moneda*:</label>
                    <div class="row">
                      <div class="col-lg-12 input-group">
                        <select name="cmbMoneda" id="cmbMoneda" required=""></select>
                        <div class="invalid-feedback" id="invalid-moneda">La factura debe de tener una moneda.</div>
                      </div>
                    </div>
                  </div>
                </div>
                <br>
                <div class="row form-group">
                  <div class="col-lg-3">
                    <label for="cmbVendedor">Vendedor:</label>
                    <select name="cmbVendedor" id="cmbVendedor"></select>
                  </div>
                  <div class="col-lg-2">
                    <div id="comboCuentaBancaria">
                      <select name="cmbCuentaBancaria" id="cmbCuentaBancaria"></select>
                      <label for="txaReferencia">Referencia:</label>
                      <textarea class="form-control" name="txaReferencia" id="txaReferencia" cols="10" rows="2"></textarea>
                      <br>
                    </div>
                    <div class="custom-control custom-switch" id="divChkPagoContado">
                      <input type="checkbox" class="custom-control-input" id="chkPagoContado">
                      <label class="custom-control-label" for="chkPagoContado">Pago de contado</label>
                      <div class="invalid-feedback" id="invalid-cuentaBancaria">La factura debe de tener una cuenta bancaria.</div>
                    </div>
                  </div>
                  <div class="col-lg-2">
                    <div class="custom-control custom-switch" id="divChkFechaVencimiento">
                      <input class="form-control" type="date" name="txtFechaVencimiento" id="txtFechaVencimiento" min="<?= date("Y-m-d"); ?>">
                      <input type="checkbox" class="custom-control-input" id="chkFechaVencimiento">
                      <label class="custom-control-label" for="chkFechaVencimiento">Fecha de Vencimiento</label>
                      <div class="invalid-feedback" id="invalid-fechaVencimiento">La factura debe de tener una fecha de vencimiento.</div>
                    </div>
                  </div>
                  <div class="col-lg-2">
                    <div class="custom-control custom-switch" id="divChkPrefactura">
                      <input type="checkbox" class="custom-control-input" id="chkPrefactura">
                      <label class="custom-control-label" for="chkPrefactura">Prefactura</label>
                    </div>
                  </div>
                  <div class="col-lg-3">
                    
                  </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-lg-12">
                        <p class="bar-title">Agregar productos o servicios</p>
                    </div>
                </div>
                <div class="row">
                  <div class="col-lg-3">
                    <label for="">Producto:</label>
                    <select name="cmbProducto" id="cmbProducto" disabled></select>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" value="" id="chkProductosTodos" disabled>
                      <label class="form-check-label" for="chkProductosTodos">
                        Cargar todos los productos
                      </label>
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <label for="">Cantidad:</label>
                    <input class="form-control numeric-only" type="text" name="txtCantidad" id="txtCantidad" disabled>
                  </div>
                  <div class="col-lg-3">
                    <label for="">Precio unitario:</label>
                    <input class="form-control numericDecimal-only" type="text" name="txtPrecioUnitario" id="txtPrecioUnitario" disabled>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" value="" id="chkPrecioEspecial" disabled>
                      <label class="form-check-label" for="chkPrecioEspecial">
                        Precio especial
                      </label>
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <button type="button" class="btn-custom btn-custom--blue espAgregar float-center" id="cargarProducto" disabled>Cargar producto</button>
                  </div>

                </div>

              </form>
              <br>
              <div class="productos-cliente table-responsive">

                <table class="table stripe" id="tblDetalleProductos" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Indice</th>
                      <th>Acciones</th>
                      <th>Clave</th>
                      <th>Descripción</th>
                      <th></th>
                      <th></th>
                      <th>Unidad de medida</th>
                      <th>Cantidad</th>
                      <th>Precio unitario</th>
                      <th>Subtotal</th>
                      <th>Impuesto</th>
                      <th>Descuento</th>
                      <th>Importe</th>
                      <th></th>
                    </tr>
                  </thead>

                  <tbody id="detalle-productos"></tbody>
                </table>

                <br>
                <table class="table stripe" id="tblSubtotalesProductos" width="100%" cellspacing="0">
                  
                  <tbody>
                    <tr>
                      <th class="not_border_top_9"></th>
                      <th class="not_border_top_9"></th>
                      <th class="not_border_top_9"></th>
                      <th class="not_border_top_9"></th>
                      <th class="not_border_top_9"></th>
                      <th class="not_border_top_9"></th>
                      <th class="not_border_top_9"></th>
                      <th class="not_border_top_9"></th>
                      <th class="head-label-subtotal">Subtotal:</th>
                      <td class="head-quantity-subtotal" id="subtotal">$0.00</td>
                    </tr>
                    <tr>
                      <th class="not_border_top_9"></th>
                      <th class="not_border_top_9"></th>
                      <th class="not_border_top_9"></th>
                      <th class="not_border_top_9"></th>
                      <th class="not_border_top_9"></th>
                      <th class="not_border_top_9"></th>
                      <th class="not_border_top_9"></th>
                      <th class="not_border_top_9"></th>
                      <th class="head-label-subtotal">Impuestos:</th>
                      <td class="head-quantity-subtotal" id="impuestos">Sin impuestos</td>
                    </tr>
                    <tr>
                      <th class="not_border_top_9"></th>
                      <th class="not_border_top_9"></th>
                      <th class="not_border_top_9"></th>
                      <th class="not_border_top_9"></th>
                      <th class="not_border_top_9"></th>
                      <th class="not_border_top_9"></th>
                      <th class="not_border_top_9"></th>
                      <th class="not_border_top_9"></th>
                      <th class="head-label-subtotal">Total:</th>
                      <td class="head-quantity-subtotal" id="total">$0.00</td>
                    </tr>
                  </tbody>
                  
                </table>
                <!--
                <div class="subtotal">
                  <label for="subtotal">Subtotal:</label>
                  <label for="subtotalCantidad" id="subtotal"></label>
                </div>
                <div class="impuestos">
                  <label for="impuestos">Impuestos:</label>
                  <label for="impuestosCantidad" id="impuestos"></label>
                </div>

                <div class="total">
                  <label for="total">Total:</label>
                  <label for="totalCantidad" id="total"></label>
                </div>
                -->
                <hr>

              </div>

              <div class="row form-group">
                <div class="col-8">
                  <label for="txaNotasCliente">Notas cliente:</label>
                  <textarea class="form-control" name="txaNotasCliente" id="txaNotasCliente" cols="30" rows="3"></textarea>
                </div>
                <div class="col-4 d-flex align-items-end justify-content-end" >
                  <div class="mt-auto p-2">
                    <!--<button type="button" class="btnesp espAgregar mr-1" name="btnAgregarPrefactura" id="agregarPrefactura" disabled><span class="ajusteProyecto">Prefactura</span></button>-->
                    <button type="button" class="btn-custom btn-custom--blue espAgregar" name="btnAgregar" id="agregarFactura" disabled><span class="ajusteProyecto">Facturar</span></button>
                  </div>
                </div>
              </div>
              

            </div>
          </div>

        </div>
        <!-- End Page Content -->
      </div>
      <!--End Main Content -->

    </div>
    <!-- End Content Wrapper -->

  </div>
  <!-- End Page Wrapper -->

  <!-- Editar producto modal -->
  <div class="modal fade right hide" id="editarProducto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
    <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">
      <div class="modal-content">

        <div class="modal-header">
          <h4 class="modal-title w-100" id="myModalLabel">Editar producto</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="txtIdProd" id="txtIdProd">
          <input type="hidden" name="rowIndex" id="rowIndex">
          <input type="hidden" name="txtIdReferencia" id="txtIdReferencia">
          <div class="form-group">
            <label for="usr">Clave:</label>
            <input type="text" class="form-control" name="txtClave" id="txtClave" readonly required>
          </div>
          <div class="form-group">
            <label for="usr">Descripción:</label>
            <input type="text" class="form-control" name="txtDescripcion" id="txtDescripcion" readonly required>
          </div>
          <div class="form-group" id="sec_clave_sat">
            <label for="">Clave SAT:</label>
            <input type="hidden" name="txtClaveSatId" id="txtClaveSatId">
            <input type="text" class="form-control" name="txtClaveSat" id="txtClaveSat" value="Clic para asignar clave SAT" readonly required>
          </div>
          <div class="form-group" id="sec_unidad_medida">
            <label for="">Unidad de medida:</label>
            <input type="hidden" name="txtUnidadMedidaId" id="txtUnidadMedidaId">
            <input type="text" class="form-control" name="txtUnidadMedida" id="txtUnidadMedida" readonly required>
          </div>

          <div class="form-group">
            <label for="usr">Cantidad:</label>
            <input type="text" class="form-control numeric-only" name="txtCantidadEdit" id="txtCantidadEdit" min="0" required>
          </div>

          <div class="form-group">
            <label for="usr">Precio unitario:</label>
            <input type="text" class="form-control numericDecimal-only" name="txtPrecioUnitarioEdit" id="txtPrecioUnitarioEdit" step='0.01' required>
          </div>

          <div class="form-group div-descuento">
            <label for="usr">Descuento:</label>
            <br>
            <div class="form-check" style="display:inline-block;margin-right:40%;margin-left:9%">
              <input class="form-check-input" type="radio" name="tipoDescuento" id="tipoDescuento1" value="1" checked>
              <label class="form-check-label" for="tipoImpuesto2">Porcentaje</label>
            </div>

            <div class="form-check" style="display:inline-block;margin-right:3%">
              <input class="form-check-input" type="radio" name="tipoDescuento" id="tipoDescuento2" value="2">
              <label class="form-check-label" for="tipoImpuesto3">Monto</label>
            </div>
            <input type="text" class="form-control numericDecimal-only" name="txtDescuento" id="txtDescuento" required>
          </div>
          <div class="form-group">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="chkPredial">
              <label class="form-check-label" for="chkPredial">
                Cuenta de predial
              </label>
            </div>
          </div>
          <div class="form-group">
            <div id="predial">
              <label for="">Predial:</label>
              <input class="form-control" type="text" name="txtPredial" id="txtPredial" maxlength="15">
            </div>
          </div>
          <div class="form-group">
            <label for="">Tipo de impuestos:</label><br>
            <div class="form-check" style="display:inline-block;margin-right:9%;margin-left:9%">
              <input class="form-check-input" type="radio" name="tipoImpuesto" id="tipoImpuesto1" value="1" checked>
              <label class="form-check-label" for="tipoImpuesto1">Trasladado</label>
            </div>
            <div class="form-check" style="display:inline-block;margin-right:8%">
              <input class="form-check-input" type="radio" name="tipoImpuesto" id="tipoImpuesto1" value="2">
              <label class="form-check-label" for="tipoImpuesto1">Retenido</label>
            </div>
            <div class="form-check" style="display:inline-block;margin-right:3%">
              <input class="form-check-input" type="radio" name="tipoImpuesto" id="tipoImpuesto1" value="3">
              <label class="form-check-label" for="tipoImpuesto1">Local</label>
            </div>
          </div>
          <div class="form-group">
            <label for="cmbImpuesto">Impuestos:</label>
            <select name="cmbImpuesto" id="cmbImpuesto"></select>
          </div>

          <div class="form-group">
            <label for="usr" id="txtLabelTax"></label>
            <input class="form-control numericDecimal-only" type="text" name="txtTax" id="txtTax">
          </div>

          <button type="button" class="btn-custom btn-custom--blue espAgregar float-right" name="btnAgregar" id="btnAgregarImpuesto"><span class="ajusteProyecto">Agregar</span></button>
          <br><br>
          <div class="table-responsive">
            <table class="table stripe" id="tblDetalleImpuestosModal" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th></th>
                  <th>Tipo</th>
                  <th>Impuesto</th>
                  <th>Tasa/Importe</th>
                  <th></th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>

        </div>
        <div class="modal-footer justify-content-center">
          <button type="button" class="btnesp first espCancelar btnCancelarActualizacion" data-dismiss="modal" id="btnCancelarActualizacion"><span class="ajusteProyecto">Cancelar</span></button>
          <button type="button" class="btn-custom btn-custom--blue espAgregar float-right" name="btnAgregar" id="btnEditarProducto"><span class="ajusteProyecto">Guardar</span></button>
        </div>

      </div>
    </div>
  </div>
  <!-- End Add Fluid Modal User -->

  <!--agregar unidad medida modal -->
  <div class="modal fade right hide" id="agregar_unidad_medida" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
    <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">
      <div class="modal-content">

        <div class="modal-header">
          <h4 class="modal-title w-100" id="myModalLabel">Agregar unidad de medida</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div id="header_search_unidad_medida">
            <label>
              <img src="../../img/timdesk/buscar.svg" width="20px">
              <input class="form-control form-control-sm" type="search" placeholder="Buscar..." name="buscar_clave_unidad_medida" id="buscar_clave_unidad_medida">
            </label>
          </div>
          <div class="table-responsive">
            <table class="table stripe" id="tblUnidadMedidaModal" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>Clave</th>
                  <th>Descripción</th>
                </tr>
              </thead>
              <tbody id="tabla_body_medida"></tbody>
            </table>
          </div>

        </div>
        <!--
          <div class="modal-footer justify-content-center">
            <button type="button" class="btnesp first espCancelar btnCancelarActualizacion" data-dismiss="modal"
              id="btnCancelarActualizacion"><span class="ajusteProyecto">Cancelar</span></button>
            <button type="button" class="btnesp espAgregar float-right" name="btnAgregar" id="btnAgregar"><span
                class="ajusteProyecto">Agregar</span></button>
          </div>
          -->
      </div>
    </div>
  </div>
  <!-- End Add Fluid Modal User -->

  <!--agregar clave sat -->
  <div class="modal fade right hide" id="agregar_clave_sat" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
    <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">
      <div class="modal-content">

        <div class="modal-header">
          <h4 class="modal-title w-100" id="myModalLabel">Agregar clave SAT</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div id="header_search_sat">
            <label>
              <img src="../../img/timdesk/buscar.svg" width="20px">
              <input class="form-control form-control-sm" type="search" placeholder="Buscar..." name="buscar_clave_sat" id="buscar_clave_sat">
            </label>
          </div>
          <div class="table-responsive">
            <table class="table stripe" id="tblClaveSatModal" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>Clave</th>
                  <th>Descripción</th>
                </tr>
              </thead>
              <tbody id="tabla_body_sat"></tbody>
            </table>
          </div>

        </div>
        <!--
          <div class="modal-footer justify-content-center">
            <button type="button" class="btnesp first espCancelar btnCancelarActualizacion" data-dismiss="modal"
              id="btnCancelarActualizacion"><span class="ajusteProyecto">Cancelar</span></button>
            <button type="button" class="btnesp espAgregar float-right" name="btnAgregar" id="btnAgregar"><span
                class="ajusteProyecto">Agregar</span></button>
          </div>
          -->
      </div>
    </div>
  </div>
  <!-- End Add Fluid Modal User -->

  <!-- Add modal añadir cliente -->
  <div class="modal fade right" id="agregar_Cliente_50" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

      <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
      <div class="modal-dialog modal-full-height modal-right modal-md" role="document">
        <div class="modal-content">
          <form action="" id="agregarCliente" method="POST">
            <div class="modal-header">
              <h4 class="modal-title w-100" id="myModalLabel">Agregar cliente</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">x</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label for="usr">Razón social:*</label>
                <input class="form-control" type="text" name="txtRazonSocial" id="txtRazonSocial" autofocus="" required="" maxlength="100" placeholder="Ej. GH Medic" onchange="escribirRazonSocial()" style="text-transform: uppercase">
                <div class="invalid-feedback" id="invalid-razon">El cliente debe tener razón social.</div>
                <div class="invalid-feedback" id="invalid-razonTipoSociedad">La razón social no debe tener el tipo de sociedad.</div>
              </div>
              <div class="form-group">
                <label for="usr">Teléfono:</label>
                <input type="text" id="txtTelefono_Cl" maxlength="10" class="form-control numeric-only" name="txtTelefono_Cl"
                onkeyup="validaNumTelefono(event,'txtTelefono_Cl', 'invalid-telCl')">
                <div class="invalid-feedback" id="invalid-telCl">El número de teléfono debe ser válido.</div>
                <input type="hidden" id="result1" readonly>
              </div>
              <div class="form-group">
                <label for="usr">Nombre comercial:*</label>
                <input class="form-control" type="text" name="txtNombreComercial" id="txtNombreComercial" autofocus="" required maxlength="255" placeholder="Ej. GH Medic" onkeyup="escribirNombre()" style="text-transform: uppercase">
                <div class="invalid-feedback" id="invalid-nombreCom">El cliente debe tener un nombre comercial.</div>
              </div>
              <div class="form-group;">
                <label for="usr">RFC:*</label>
                <input class="form-control alphaNumeric-only" type="text" name="txtRFC" id="txtRFC" maxlength="13" required placeholder="Ej. GHMM100101AA1" onchange="validInput('txtRFC', 'invalid-rfc', 'El cliente debe tener RFC.')" oninput="let p=this.selectionStart;this.value=this.value.toUpperCase();this.setSelectionRange(p, p);">
                <div class="invalid-feedback" id="invalid-rfc">El cliente debe tener RFC.</div>
              </div>
              <div class="form-group">
                <label for="usr">Régimen fiscal:*</label>
                <select name="cmbRegimen" id="cmbRegimen" required onchange="validInput('cmbRegimen', 'invalid-regimen', 'El cliente debe tener régimen fiscal.')">
                </select>
                <div class="invalid-feedback" id="invalid-regimen">El cliente debe tener régimen fiscal.</div>
              </div>
              <div class="form-group">
                <label for="usr">Medio de contacto:*</label>
                <select name="cmbMedioContactoCliente" id="cmbMedioContactoCliente" onchange="validInput('cmbMedioContactoCliente', 'invalid-medioCont', 'El cliente debe tener un medio de contacto.')">
                </select>
                <div class="invalid-feedback" id="invalid-medioCont">El cliente debe tener un medio de contacto.</div>
              </div>
              <div class="form-group">
                <label for="usr">Vendedor*:</label>
                <select name="cmbVendedorNC" id="cmbVendedorNC" onchange="validInput('cmbVendedorNC', 'invalid-vendedor', 'El cliente debe tener un vendedor.')">
                </select>
                <div class="invalid-feedback" id="invalid-vendedor">El cliente debe tener un vendedor.</div>
              </div>
              <div class="form-group">
                <label for="usr">E-mail:*</label>
                <input class="form-control" type="email" name="txtEmail" id="txtEmail" autofocus="" required maxlength="100" placeholder="Ej. ejemplo@dominio.com" onchange="validarCorreo(this.value, 'txtEmail', 'invalid-email')">
                <div class="invalid-feedback" id="invalid-email">E-mail inválido.</div>
              </div>
              <div class="form-group">
                <label for="usr">Código postal:*</label>
                <input class="form-control numeric-only" type="text" name="txtCP" id="txtCP" autofocus="" required maxlength="5" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" placeholder="Ej. 52632" onkeyup="validarCP('txtCP', 'invalid-cp');"">
                <div class="invalid-feedback" id="invalid-cp">El cliente debe tener un codigo postal.</div>
              </div>
              <div class="form-group">
                <label for="usr">País:*</label>
                <select name="cmbPais" class="" id="cmbPais" onchange="validInput('cmbPais', 'invalid-paisFisc', 'El cliente debe tener un país.')">
                <option data-placeholder="true"></option>
                <?php
                  $stmt = $conn->prepare("SELECT * FROM paises");
                  $stmt->execute();
                  $row = $stmt->fetchAll();

                  if (count($row) > 0) {
                    foreach ($row as $r) { //Mostrar usuarios
                      if ($r['Disponible'] == 1) {
                        echo '<option value="' . $r['PKPais'] . '">' . $r['Pais'] . '</option>';
                        $pais = $r['PKPais'];
                      } else {
                        //echo '<option value="'.$r['PKPais'].'">'.$r['Pais'].'</option>';
                      }
                    }
                  } else {
                    echo '<option value="" disabled>No hay registros para mostrar.</option>';
                  }
                  ?>
                </select>
                <div class="invalid-feedback" id="invalid-paisFisc">El cliente debe tener un país.</div>
              </div>
              <div class="form-group">
                <label for="usr">Estado:*</label>
                <select name="cmbEstado" class="" id="cmbEstado" onchange="validInput('cmbEstado', 'invalid-paisEstadoFisc', 'El cliente debe tener un estado.')">
                  <option data-placeholder="true"></option>
                </select>
                <div class="invalid-feedback" id="invalid-paisEstadoFisc">El cliente debe tener un estado.</div>
              </div>
              <div>
                <label for="usr">Campos requeridos *</label>
              </div>
            </div>
            <div class="modal-footer justify-content-center">
              <button type="button" class="btnesp first espCancelar btnCancelarActualizacion" data-dismiss="modal" id="btnCancelar_newCliente_FC" onclick="resetForm('agregarCliente')"><span class="ajusteProyecto">Cancelar</span></button>
              <button type="button" class="btnesp espAgregar float-right" name="btnAgregarNC_FC" id="btnAgregarNC_FC"><span class="ajusteProyecto">Agregar</span></button>
            </div>
          </form>
        </div>
      </div>
      </div>
  <!-- End modal añadir cliente -->

  <!--ADD MODAL PRODUCTO-->
  <div class="modal fade right" id="agregar_Producto_FC" tabindex="-1" role="dialog" aria-labelledby="modalAgregarEmpleado"
      aria-hidden="true">
      <div class="modal-dialog modal-full-height modal-right modal-md" role="document">
        <div class="modal-content">
          <form id="agregarProductoForm">
            <div class="modal-header">
              <h4 class="modal-title w-100">Agregar producto</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">x</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label for="txtProducto">Nombre:*</label>
                <input type="text" class="form-control" maxlength="50" name="txtProducto" id="txtProducto" required
                onkeyup="escribirNombreProd()">
                <div class="invalid-feedback" id="invalid-nombreProducto">El producto debe tener un nombre.</div>
              </div>
              <div class="form-group">
                <label for="cmbTipoProducto">Tipo:*</label>
                <select name="cmbTipoProducto" id="cmbTipoProducto" onchange="validInput('cmbTipoProducto', 'invalid-tipoProd', 'El producto debe tener un tipo.')" required>
                  <option data-placeholder="true"></option>
                  <?php
                      $stmt = $conn->prepare("call spc_Combo_TipoProducto()");
                      $stmt->execute();
                      $row = $stmt->fetchAll();
                      foreach ($row as $r) { 
                        echo '<option value="' . $r['PKTipoProducto'] . '" >' . $r['TipoProducto'] . '</option>';
                      }
                    ?>
                </select>
                <div class="invalid-feedback" id="invalid-tipoProd">El producto debe tener un tipo.</div>
              </div>
              <div class="form-group">
                <label for="txtClave_FC">Clave interna:*</label>
                <input type="text" class="form-control" name="txtClave_FC" id="txtClave_FC" maxlength="50"
                  onkeyup="escribirClave()" required>
                <div class="invalid-feedback" id="invalid-clave">El producto debe tener clave interna.</div>
              </div>
              <div class="form-group">
                <a href="#" class="btn-custom btn-custom--blue ml-3" id="btnGenerarClave">Generar</a>
              </div>
              <div class="form-group">
                <div class="row">
                  <div class="col-lg-12">
                    <label>Existencia:</label>
                    <div class="input-group">
                        <input class="form-control cantidadProducto" type="text" maxlength="13" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" name="txtCostoUniFabri" id="txtCostoUniFabri" placeholder="Ej. 10.00" style="float:left;" onkeyup="validEmptyInput('txtCostoUniFabri', 'invalid-costoFabrProd', 'El producto debe tener un costo de fabricación.')">
                        <div class="invalid-feedback" id="invalid-costoFabrProd">El producto debe tener un costo de fabricación.</div>
                  </div>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label for="">Clave SAT:*</label>
                <input type="hidden" name="txtClaveSatId_NP" id="txtClaveSatId_NP">
                <input type="text" class="form-control" name="txtClaveSat_NP" id="txtClaveSat_NP" value="Clic para asignar clave SAT" onchange="validInput('txtClaveSat_NP', 'invalid-claveSat', 'El producto debe tener clave sat.')" readonly required>
                <div class="invalid-feedback" id="invalid-claveSat">El producto debe tener clave sat.</div>
              </div>
              <div class="form-group">
                <label for="">Unidad de medida:*</label>
                <input type="hidden" name="txtUnidadMedidaId_NP" id="txtUnidadMedidaId_NP">
                <input type="text" class="form-control" name="txtUnidadMedida_NP" id="txtUnidadMedida_NP" value="Clic para asignar una unidad de medida" onchange="validInput('txtUnidadMedida_NP', 'invalid-unidadSat', 'El producto debe tener unidad.')" readonly required>
                <div class="invalid-feedback" id="invalid-unidadSat">El producto debe tener unidad.</div>
              </div>
               <div class="form-group ">
                          <div class="row">
                            <div class="col-lg-6">
                              <label for="usr">Impuesto:</label>
                              <select class="cmbSlim" name="cmbImpuestos" id="cmbImpuestos" required="" onchange="cambioImpuesto(this.value)">
                              </select>
                              <input class="form-control" id="notaImpuesto" name="notaImpuesto" type="hidden"
                              style="color: darkred; background-color: transparent!important; border: none;"
                              value="Nota: El impuesto ya ha sido agregado." readonly>
                            </div>
                            <div class="col-lg-6">
                              <label for="usr">Tipo:</label>
                              <input type='hidden' value='1' name="txtTipoImpuesto" id="txtTipoImpuesto">
                              <div style="background:#0275d8;padding:5px;color:white;" id="trasladado"><center>Trasladado</center></div>
                              <div style="background:#f0ad4e;padding:5px;color:white;display: none;" id="retenciones"><center>Retenciones</center></div>
                              <div style="background:#5cb85c;padding:5px;color:white;display: none;" id="local"><center>Local</center></div>
                            </div>
                            <div class="col-lg-6">
                              <label for="usr" id="etiquetaImpuesto">Tasa:</label>
                              <input type='hidden' value='1' name="txtTipoTasa" id="txtTipoTasa">
                              <span id="areaimpuestos">
                                <select class="cmbSlim" name="cmbTasaImpuestos" id="cmbTasaImpuestos" required="">
                                </select> 
                              </span>   
                            </div>
                            <div class="col-lg-6" style="text-align:center!important; margin-top:35px;" id="btnAnadirImpuesto2">
                              <a href="#" class="btn-custom btn-custom--border-blue" id="btnAnadirImpuesto" onclick="validarImpuesto()">Añadir impuesto</a>
                            </div>
            </div>                   
            <br>

            <div class="table-responsive">
                      <table class="table" id="tablaprueba" width="100%" cellspacing="0">
                        <thead>
                          <tr>
                            <th style="width:50%">Impuesto</th>
                            <th style="width:20%">Tipo</th>
                            <th style="width:20%">Tasa</th>
                            <th style="width:10%"></th>
                          </tr>
                        </thead>
                        <tbody id="addImpuesto">
                        </tbody>
                      </table>
                    </div>
            <br>

            <div>
              <label for="usr">Campos requeridos *</label>
            </div>
          </div>
            </div>
            <div class="modal-footer justify-content-center">
              <button type="button" class="btnesp first espCancelar btnCancelarActualizacion" data-dismiss="modal"
                id="btnCancelar_newProd_FC" onclick="resetForm('agregarProductoForm')"><span class="ajusteProyecto">Cancelar</span></button>
              <button type="button" class="btnesp espAgregar float-right" name="btnAgregarProducto_FC" id="btnAgregarProducto_FC"><span
                  class="ajusteProyecto">Agregar</span></button>
            </div>
          </form>
        </div>
      </div>
    </div>
  <!--END MODAL PRODUCTO-->

  <!--agregar clave sat nuevo prod-->
  <div class="modal fade right hide" id="agregar_clave_sat_NP" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
    <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">
      <div class="modal-content">

        <div class="modal-header">
          <h4 class="modal-title w-100" id="myModalLabel">Agregar clave SAT</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div id="header_search_sat">
            <label>
              <img src="../../img/timdesk/buscar.svg" width="20px">
              <input class="form-control form-control-sm" type="search" placeholder="Buscar..." name="buscar_clave_sat_NP" id="buscar_clave_sat_NP">
            </label>
          </div>
          <div class="table-responsive">
            <table class="table stripe" id="tblClaveSatModal_NP" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>Clave</th>
                  <th>Descripción</th>
                </tr>
              </thead>
              <tbody id="tabla_body_sat_NP"></tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- End agregar clave sat nuevo prod -->

  <!--agregar unidad medida modal nuevo prod -->
  <div class="modal fade right hide" id="agregar_unidad_medida_NP" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
    <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">
      <div class="modal-content">

        <div class="modal-header">
          <h4 class="modal-title w-100" id="myModalLabel">Agregar unidad de medida</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div id="header_search_unidad_medida">
            <label>
              <img src="../../img/timdesk/buscar.svg" width="20px">
              <input class="form-control form-control-sm" type="search" placeholder="Buscar..." name="buscar_clave_unidad_medida_NP" id="buscar_clave_unidad_medida_NP">
            </label>
          </div>
          <div class="table-responsive">
            <table class="table stripe" id="tblUnidadMedidaModal_NP" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>Clave</th>
                  <th>Descripción</th>
                </tr>
              </thead>
              <tbody id="tabla_body_medida_NP"></tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- End agregar unidad medida modal nuevo prod -->

  <!-- Modal alert customer no billing -->
  <div class="modal fade" id="alert_custumer_no_billing" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="exampleModalLabel">Información del cliente incompleta </h4>
        </div>
        <div class="modal-body"><center><h4>El cliente no está disponible para facturación.</h4> <h6><br>Es necesario completar su información</h6></center></div>
        <div class="modal-footer">
          <div id="link"></div>
          <a class="btn btn-primary" data-dismiss="modal">Aceptar</a>
        </div>
      </div>
    </div>
  </div>
  <!-- End Modal alert customer no billing -->

  <!-- Modal alert customer no billing -->
  <div class="modal fade" id="alert_table_void" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="exampleModalLabel">Hay datos en la tabla </h4>
        </div>
        <div class="modal-body"><center><h4>La tabla contiene datos. Si realiza esta acción se borrarán.</h4> <h5><br>¿Desea proceder?</h5></center></div>
        <div class="modal-footer justify-content-center">
          <button type="button" class="btnesp first espCancelar btnCancelarActualizacion" data-dismiss="modal"
            id="btnCancelar_table_void" ><span class="ajusteProyecto">Cancelar</span></button>
          <button type="button" class="btnesp espAgregar float-right" name="btnAgregar_table_void" id="btnAgregar_table_void"><span
            class="ajusteProyecto">Aceptar</span></button>
        </div>
      </div>
    </div>
  </div>
  <!-- End Modal alert customer no billing -->
  <!--ADD MODAL PERSONAL-->
  <div class="modal fade right" id="agregar_Personal" tabindex="-1" role="dialog" aria-labelledby="modalAgregarPersonal"
      aria-hidden="true">
      <div class="modal-dialog modal-full-height modal-right modal-md" role="document">
        <div class="modal-content">
          <form id="agregarPersonal" method="POST">
            <div class="modal-header">
              <h4 class="modal-title w-100">Agregar personal</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">x</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label for="txtNombre">Nombre(s):*</label>
                <input type="text" class="form-control alpha-only" maxlength="50" name="txtNombre" id="txtNombre" required
                onkeyup="validEmptyInput(this)">
                <div class="invalid-feedback" id="invalid-nombre">El empleado debe tener un nombre.</div>
              </div>
              <div class="form-group">
                <label for="txtPrimerApellido">Primer apellido:*</label>
                <input type="text" class="form-control alpha-only" name="txtPrimerApellido" id="txtPrimerApellido" maxlength="50"
                  onkeyup="validEmptyInput(this)" required>
                <div class="invalid-feedback" id="invalid-primerApellido">El empleado debe tener un primer apellido.</div>
              </div>
              <div class="form-group">
                <label for="cmbGenero">Genero:</label>
                <select name="cmbGenero" id="cmbGenero">
                  <option data-placeholder="true"></option>
                  <option value="Masculino">Masculino</option>
                  <option value="Femenino">Femenino</option>
                </select>
                <div class="invalid-feedback" id="invalid-genero">El empleado debe tener un género.</div>
              </div>
              <div class="form-group">
                <label for="cmbEstadoPersonal">Estado:*</label>
                <select name="cmbEstadoPersonal" id="cmbEstadoPersonal" onchange="validEmptyInput(this)">
                  <option data-placeholder="true"></option>
                  <?php
                    foreach ($estados as $r) { //Mostrar estados
                        echo '<option value="' . $r['PKEstado'] . '" >' . $r['Estado'] . '</option>';
                    }
                  ?>
                </select>
                <div class="invalid-feedback" id="invalid-estado">El empleado debe tener un estado.</div>
              </div>
              <div class="form-group">
                <label for="cmbRolesPersonal">Roles:*</label>
                <select name="cmbRolesPersonal" id="cmbRolesPersonal" onchange="validEmptyInput(this)" multiple>
                  <option data-placeholder="true"></option>
                  <?php
                    foreach ($roles as $r) { //Mostrar roles
                      echo '<option value="' . $r['id'] . '" >' . $r['tipo'] . '</option>';
                    }
                  ?>
                </select>
                <div class="invalid-feedback" id="invalid-roles">El empleado debe tener al menos un rol.</div>
              </div>
            </div>
            <div class="modal-footer justify-content-center">
              <button type="button" class="btnesp first espCancelar btnCancelarActualizacion" data-dismiss="modal"
                id="btnCancelarActualizacion"><span class="ajusteProyecto">Cancelar</span></button>
              <button type="button" class="btnesp espAgregar float-right" name="btnAgregarPersonal" id="btnAgregarPersonal"><span
                  class="ajusteProyecto">Agregar</span></button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- END ADD MODAL PERSONAL -->

  <script src="../../js/sb-admin-2.min.js"></script>
  <script src="../../js/scripts.js"></script>
  <script src="js/agregar_factura_conceptos.js"></script>
  <script src="js/slimselect_add.js"></script>
  <script src="../../js/validaciones.js"></script>
</body>

</html>