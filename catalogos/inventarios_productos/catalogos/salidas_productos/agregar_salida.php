<?php
session_start();

if (isset($_SESSION["Usuario"])) {

  $user = $_SESSION["Usuario"];

  $pkusuario = $_SESSION["PKUsuario"];
  $ruta = "../../../";
  $screen = 8;
  require_once $ruta . '../include/db-conn.php';
} else {
  header("location:../../../dashboard.php");
}

if (isset($_POST["idPedido"])) {
  $IdPedido = $_POST["idPedido"];
} else {
  $IdPedido = '0';
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
  <title>Timlid | Agregar salidas</title>

  <!-- STYLES -->
  <link href="../../../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../../../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../../../css/slimselect.min.css" rel="stylesheet">
  <link href="../../../../vendor/datatables/responsive.dataTables.css" rel="stylesheet">
  <link href="../../../../vendor/datatables/buttons.dataTables.css" rel="stylesheet">
  <link href="../../../../css/croppie.css" rel="stylesheet">
  <link href="../../../../css/sweetalert2.css" rel="stylesheet">
  <link href="../../../../css/lobibox.min.css" rel="stylesheet">
  <link href="../../../../css/stylesNewTable.css" rel="stylesheet">
  <link href="../../../../css/styles.css" rel="stylesheet">
  <link href="../../style/agregar_salidas.css" rel="stylesheet">
  <!-- SCRIPTS -->
  <script src="../../../../vendor/jquery/jquery.min.js"></script>
  <script src="../../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../../../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../../../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../../../vendor/datatables/dataTables.responsive.js"></script>
  <script src="../../../../vendor/datatables/dataTables.buttons.js"></script>
  <script src="../../../../vendor/datatables/buttons.html5.min.js"></script>
  <script src="../../../../vendor/jszip/jszip.min.js"></script>
  <script src="../../../../js/lobibox.min.js"></script>
  <script src="../../../../js/sb-admin-2.min.js"></script>
  <script src="../../../../js/scripts.js"></script>
  <script src="../../../../js/numeral.min.js" charset="utf-8"></script>
  <script src="../../../../js/croppie.js"></script>
  <script src="../../../../js/sweet/sweetalert2.js"></script>
  <script src="../../../../js/notificaciones_timlid.js" charset="utf-8"></script>
  <script src="../../../../js/numeral.min.js"></script>
  <script src="../../js/agregar_salidas.js" charset="utf-8"></script>

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
      <input type="hidden" id="txtUsuario" value="<?= $_SESSION['PKUsuario']; ?>">
      <input type="hidden" id="txtRuta" value="<?= $ruta; ?>">
      <input type="hidden" id="txtEdit" value="<?= $ruteEdit; ?>">
      <input type="hidden" id="txtPantalla" value="13">

      <!-- Main Content -->
      <div id="content">

        <?php
        $rutatb = "../../../";
        $icono = 'ICONO-SALIDAS-AZUL.svg';
        $titulo = 'Agregar salidas';
        $backIcon = true;
        require_once $rutatb . 'topbar.php';
        ?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- DataTales Example -->
          <div class="card mb-4">

            <div class="card-body py-3">
              <div class="data-container">
                <form id="frmSalidaOP">
                  <div class="form-group">
                    <div class="container-fluid">
                      <div class="row">
                        <!-- <div class="col-12 col-md-3">
                          <div class="principal">
                            <label for="cmbTipoSalida">Tipo de salida*:</label>
                            <select name="cmbTipoSalida" id="cmbTipoSalida" required></select>
                          </div>
                        </div> -->
                        <div class="col-12 col-md-4">
                          <div class="branchOrigin-disabled">
                            <label for="cmbSucursalOrigen">Sucursal de origen*:</label>
                            <select name="cmbSucursalOrigen" id="cmbSucursalOrigen" required></select>
                          </div>
                          <div class="providers-disabled">
                            <label for="cmbProveedor">Proveedor:</label>
                            <select name="cmbProveedor" id="cmbProveedor" required></select>
                          </div>
                        </div>
                        <div class="col-12 col-md-4">
                          <div class="typeOrderPedido-disabled">
                            <label for="cmbTypeOrderPedido">Tipo de pedido*:</label>
                            <select name="cmbTypeOrderPedido" id="cmbTypeOrderPedido" required>
                            </select>
                          </div>
                          <div class="purchases-disabled">
                            <label for="cmbNoDocumento">No. de documento:</label>
                            <select name="cmbNoDocumento" id="cmbNoDocumento" required></select>
                          </div>
                        </div>
                        <div class="col-12 col-md-4">
                          <div class="orderPedido-disabled">
                            <label for="cmbOrderPedido">Pedido*:</label>
                            <div class="input-group">
                              <select name="cmbOrderPedido" id="cmbOrderPedido" required></select>
                              <div class="invalid-feedback" id="invalid-ordenPedido">La salida debe de tener un pedido.</div>
                            </div>
                          </div>
                          <div class="orderPedidoGral-disabled">
                            <label for="cmbOrderPedidoGral">Pedido*:</label>
                            <div class="input-group">
                              <select name="cmbOrderPedidoGral" id="cmbOrderPedidoGral" required></select>
                              <div class="invalid-feedback" id="invalid-ordenPedido">La salida debe de tener un pedido.</div>
                            </div>
                          </div>
                          <div class="quote-disabled">
                            <label for="cmbQuote">Cotización*:</label>
                            <div class="input-group">
                              <select name="cmbQuote" id="cmbQuote" required></select>
                              <div class="invalid-feedback" id="invalid-ordenPedido">La salida debe de tener una cotización.</div>
                            </div>
                          </div>
                          <div class="sales-disabled">
                            <label for="cmbSales">Venta directa*:</label>
                            <div class="input-group">
                              <select name="cmbSales" id="cmbSales" required></select>
                              <div class="invalid-feedback" id="invalid-ordenPedido">La salida debe de tener una venta.</div>
                            </div>
                          </div>
                          <div class="branchOriginOC-disabled">
                            <label for="cmbSucursalOC">Sucursal:</label>
                            <select name="cmbSucursalOC" id="cmbSucursalOC" required></select>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <span id="div2">
                  </span>

                  <div class="form-group">
                    <div class="row">
                      <div class="col-lg-4" >
                        <span id="divCodigoBarras">
                        </span>
                      </div>
                    </div>
                  </div>
                  <br>
                  
                  <div class="container-fluid">
                    <div class="row">
                      <div class="table-responsive brancheDestination-disabled branchOrSale-disabled customer-disabled customerSales-disabled">
                        <table class="table opacidad" id="tblSalidaOrdenPedido" width="100%" cellspacing="0">
                          <thead>
                            <tr>
                              <th>ID</th>
                              <th>Clave</th>
                              <th>Producto</th>
                              <th>Cantidad pedida</th>
                              <th>Cantidad surtida</th>
                              <th>Cantidad faltante</th>
                              <th>Existencias</th>
                              <th>Cantidad</th>
                              <th>Lote</th>
                              <!--<th>Serie</th>-->
                              <th>Unidad medida</th>
                              <th>Código de barras</th>
                              <th>Caducidad</th>
                              <th></th>
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
                              <th id="impuestos"><span id="Total">0</span></th>
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
                      <div class="" style="width:100%">
                        <div class="brancheDestination-disabled">
                          <br>
                          <a class="btn-custom btn-custom--blue float-right botonesAbajo" id="btnAgregarSalidaOCP">Registrar salida</a>
                          <!--<a class="btn-custom btn-custom--blue float-right botonesAbajo" id="btnImprimirSalidaOCP">Imprimir</a> -->
                        </div>
                        <div class="customer-disabled">
                          <br>
                          <a class="btn-custom btn-custom--blue float-right botonesAbajo" id="btnAgregarSalidaCoti">Registrar salida</a>
                          <!--<a class="btn-custom btn-custom--blue float-right botonesAbajo" id="btnImprimirSalidaOCP">Imprimir</a> -->
                        </div>
                        <div class="customerSales-disabled">
                          <br>
                          <a class="btn-custom btn-custom--blue float-right botonesAbajo" id="btnAgregarSalidaVenta">Registrar salida</a>
                          <!--<a class="btn-custom btn-custom--blue float-right botonesAbajo" id="btnImprimirSalidaOCP">Imprimir</a> -->
                        </div>
                        <div class="branchOrSale-disabled">
                          <br>
                          <a class="btn-custom btn-custom--blue float-right botonesAbajo" id="btnAgregarSalidaGral">Registrar salida</a>
                          <!--<a class="btn-custom btn-custom--blue float-right botonesAbajo" id="btnImprimirSalidaOCP">Imprimir</a> -->
                        </div>
                      </div>
                      
                      <div class="branchOriginOC-disabled">
                        <table class="table opacidad" id="tblSalidaDevolucion" width="100%" cellspacing="0">
                          <thead>
                            <tr>
                              <th>ID</th>
                              <th>Clave</th>
                              <th>Producto</th>
                              <th>Cantidad entrada</th>
                              <th>Existencias</th>
                              <th>Cantidad</th>
                              <th>Lote</th>
                              <!--<th>Serie</th>-->
                              <th>Caducidad</th>
                              <th></th>
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
                              <th id="impuestos"><span id="TotalDevolucion">0</span></th>
                              <th></th>
                            </tr>
                            <tr class="total redondearAbajoIzq">
                              <th style="text-align: right;" class="redondearAbajoIzq"></th>
                              <th style="text-align: right;"></th>
                              <th></th>
                            </tr>
                          </tfoot>
                        </table>
                        <div class="">
                          <div class="branchOriginOC-disabled">
                            <br>
                            <a class="btn-custom btn-custom--blue float-right botonesAbajo" id="btnAgregarSalidaDevolucion">Registrar devolución</a>
                            <!--<a class="btn-custom btn-custom--blue float-right botonesAbajo" id="btnImprimirSalidaOCP">Imprimir</a> -->
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
            </div>
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


  <!--Set up MODAL SLIDE Products-->
  <div class="modal fade" id="configurarProducto" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form action="" method="POST">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Configurar lotes o series</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true" class="text-light">x</span>
            </button>
          </div>
          <div class="modal-body">
            <input type="hidden" id="idProducto" name="idProducto">
            <input type="hidden" id="cantidad-faltante" name="cantidad-faltante">
            <div class="row">
              <div class="form-group col-md-4">
                <label for="usr">Producto:</label> <span id="configProducto"></span>
              </div>
              <div class="form-group col-md-4">
                <label for="usr">Cantidad pedida:</label> <span id="configProductoCant"></span>
              </div>
              <div class="form-group col-md-4">
                <label for="usr">Cantidad faltante:</label> <span id="configProductoCantFalt"></span>
                <input type="hidden" id="configProductoCantFaltInput" />
              </div>
            </div>
            <div class="row">
              <div class="form-group col-md-12">
                <label for="usr">Descripción:</label> <span id="descripcionProducto"></span>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="invalid-feedback text-center" id="invalid-cantidad-mayor">La cantidad total excede el faltante.</div>
              </div>
            </div>

            <div class="form-group col-md-12">
              <hr>
              <div class="row">
                <div class="col-md-3">
                  Lote / Serie :
                </div>

                <div class="col-md-2">
                  Existencias
                </div>

                <div class="col-md-2">
                  Caducidad
                </div>

                <div class="col-md-2">
                  Cantidad
                </div>
                
                <div class="col-md-2">
                </div>
              </div>
              <hr>

              <span id="listProducto">

              </span>
            </div>
          </div>
          <div class="modal-footer">
            <button class="btn-custom btn-custom--border-blue btnCancelarActualizacion" type="button" data-dismiss="modal"><span class="ajusteProyecto">Salir</span></button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!--DELETE MODAL SLIDE Producto -->
  <div class="modal fade" id="eliminar_ProductoSalida" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form action="" method="POST">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">¿Desea eliminar el registro de este?</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true" class="text-light">x</span>
            </button>
          </div>
          <input type="hidden" value="0" id="exitTempIDD" name="exitTempIDD">
          <input type="hidden" value="0" id="ProductoTempIDD" name="ProductoTempIDD">
          <div class="modal-body" style="font-size: 10 px!important; color: red;">Esta acción no podrá deshacerse</div>
          <div class="modal-footer">
            <button class="btn-custom btn-custom--border-blue btnCancelarActualizacion" type="button" data-dismiss="modal"><span class="ajusteProyecto">Cancelar</span></button>
            <button type="button" onclick="eliminarCantidadTemp()" class="btn-custom btn-custom--blue"><span class="ajusteProyecto" data-dismiss="modal">Eliminar</span></button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script src="../../../../js/slimselect.min.js"></script>
  <script src="../../../../js/sb-admin-2.min.js"></script>
  <script src="../../../../js/scripts.js"></script>
  <script src="../../../../js/Sortable.js"></script>
  <script src="../../../../js/pagination/pagination.js"></script>
  <script>
    loadAlertsNoti('alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
    setInterval(loadAlertsNoti, 10000, 'alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
  </script>

  <script>
    var idPedido = '<?php echo $IdPedido; ?>';
  </script>

</body>

</html>