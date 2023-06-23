<?php
  $ruta = "../";
  $screen = 16;
  $jwt_ruta = "../../";
  require_once '../validarPermisoPantalla.php';
  require_once '../jwt.php';
  date_default_timezone_set('America/Mexico_City');
  $token = $_SESSION['token_ld10d'];

 if(isset($_SESSION["Usuario"]) && $permiso === 1){
  require_once '../../include/db-conn.php';
   
  
  if(isset($_REQUEST['idCotizacionF'])){
    $facturar = $_REQUEST['idCotizacionF'];
    $select = "1";
  } else if(isset($_REQUEST['idVentaDirecta'])){
    $facturar = $_REQUEST['idCotizacionF'];
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
<html lang="es">
<head>
<link rel="icon" type="image/png" href="../../img/header/bluTimlid.png">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Timlid | Remisión</title>

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
  <!--<link href="../../css/stylesTable.css" rel="stylesheet">-->
  <link href="../../css/stylesNewTable.css" rel="stylesheet">
  <link href="../../css/slimselect.min.css" rel="stylesheet">
  
  <link rel="stylesheet" href="../../css/sweetalert2.css">
  <link href="../../css/lobibox.min.css" rel="stylesheet">

  <link rel="stylesheet" href="../../css/notificaciones.css">
  <script src="../../js/notificaciones_timlid.js" charset="utf-8"></script>

  <link rel="stylesheet" href="css/agregar_remision.css">

  <script src="../../js/lobibox.min.js"></script>
</head>
<body id="page-top">
  <div id=loader></div>
  <!-- Page Wrapper -->
  <div id="wrapper">

     <!-- Sidebar -->
     <?php
        $icono = '../../img/icons/ICONO FACTURACION-01.svg';
        $titulo = 'Crear remisión';
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
              
              <div class="cabecera">

                <div class="row cabecera-selector">

                  <div class="col-lg-3" id="comboCliente">
                    <label for="">Cliente:</label>
                    <select name="cmbCliente" id="cmbCliente"></select>
                  </div>

                  <div class="col-lg-3 input-group select-timlid" id="select-pedidos">
                    <label for="">Pedidos:</label>
                    <div class="select-main" id="cmbPedido">
                      <div class="select-body">
                        <span class="placeholder">
                          <span class="select-disabled">Seleccione un pedido...</span>
                        </span>
                        <span class="select-deselect select-hide">x</span>
                        <span class="select-arrow">
                          <span class="select-arrow-down"></span>
                        </span>
                      </div>
                      <div class="select-content">
                        <div class="select-search">
                          <input type="search" placeholder="Buscar" tabindex="0" aria-label="Buscar" autocapitalize="off" autocomplete="off" autocorrect="off" spellcheck="false" data-ms-editar="true">
                        </div>
                        <div class="select-list"></div>
                      </div>
                    </div>
                  </div>

                  <div class="col-lg-3 select-timlid" id="select-salidas">
                    <label for="">Salida:</label>
                    <div class="select-main" id="cmbSalida">
                      <div class="select-body">
                        <span class="placeholder">
                          <span class="select-disabled">Seleccione una salida...</span>
                        </span>
                        <span class="select-deselect select-hide">x</span>
                        <span class="select-arrow">
                          <span class="select-arrow-down"></span>
                        </span>
                      </div>
                      <div class="select-content">
                        <div class="select-search">
                          <input type="search" placeholder="Buscar" tabindex="0" aria-label="Buscar" autocapitalize="off" autocomplete="off" autocorrect="off" spellcheck="false" data-ms-editar="true">
                        </div>
                        <div class="select-list">
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="col-lg-3">
                    <a href="#" class="btnesp espAgregar float-center" id="cargarProductosPedidos">Cargar productos</a>
                  </div>

                </div>
               
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
                        <td class="head-quantity-subtotal" id="subtotal"></td>
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
                        <td class="head-quantity-subtotal" id="impuestos"></td>
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
                        <td class="head-quantity-subtotal" id="total"></td>
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
                  <br>
                  <button type="button" class="btnesp espAgregar float-right" name="btnAgregar" id="agregarFactura"><span
                  class="ajusteProyecto">Guardar</span></button>
                  
                </div>
              </div>
            </div>
          </div>

        </div>
        <!-- End Page Content -->

      </div>
      <!-- End Main Content -->

    </div>
    <!-- End Content Wrapper -->

  <!-- Editar producto modal -->
  <div class="modal fade right hide" id="editarProducto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
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
              <input type="text" class="form-control numeric-only" name="txtCantidad" id="txtCantidad" min="0" required readonly>
            </div>

            <div class="form-group">
              <label for="usr">Precio unitario:</label>
              <input type="text" class="form-control numericDecimal-only" name="txtPrecioUnitario" id="txtPrecioUnitario" step='0.01' required>
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
              <label for="">Tipo de impuestos:</label><br>
              <div class="form-check" style="display:inline-block;margin-right:9%;margin-left:9%">
                <input class="form-check-input" type="radio" name="tipoImpuesto" id="tipoImpuesto1" value="1" checked>
                <label class="form-check-label" for="tipoImpuesto1">Trasladado</label>
              </div>
              <div class="form-check" style="display:inline-block;margin-right:8%">
                <input class="form-check-input" type="radio" name="tipoImpuesto" id="tipoImpuesto1" value="2" >
                <label class="form-check-label" for="tipoImpuesto1">Retenido</label>
              </div>
              <div class="form-check" style="display:inline-block;margin-right:3%">
                <input class="form-check-input" type="radio" name="tipoImpuesto" id="tipoImpuesto1" value="3" >
                <label class="form-check-label" for="tipoImpuesto1">Local</label>
              </div>
            </div>
            <div class="form-group">
              <label for="cmbImpuesto">Impuestos:</label>
              <select name="cmbImpuesto" id="cmbImpuesto"></select>
            </div>
            <div class="form-group">
              <label for="usr" id="txtLabelTax"></label>
              <input class="form-control numeric-only" type="text" name="txtTax" id="txtTax">
            </div>
            
              <button type="button" class="btnesp espAgregar float-right" name="btnAgregar" id="btnAgregarImpuesto"><span
                class="ajusteProyecto">Agregar</span></button>
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
            <button type="button" class="btnesp first espCancelar btnCancelarActualizacion" data-dismiss="modal"
              id="btnCancelarActualizacion"><span class="ajusteProyecto">Cancelar</span></button>
            <button type="button" class="btnesp espAgregar float-right" name="btnAgregar" id="btnEditarProducto"><span
                class="ajusteProyecto">Guardar</span></button>
          </div>
        
      </div>
    </div>
  </div>
  <!-- End Add Fluid Modal User -->

  <!--agregar clave sat -->
  <div class="modal fade right hide" id="agregar_clave_sat" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
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

  <!--agregar unidad medida modal -->
  <div class="modal fade right hide" id="agregar_unidad_medida" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
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

  </div>
  <!-- End Page Wrapper -->
  <script src="js/agregar_remision.js"></script>
  <script src="js/select_timlid.js"></script>
  <script src="../../js/slimselect.min.js"></script>
  <script src="../../js/numeral.min.js"></script>
</body>
</html>