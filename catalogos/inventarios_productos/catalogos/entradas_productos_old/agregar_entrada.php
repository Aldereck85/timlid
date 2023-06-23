<?php
session_start();

if (isset($_SESSION["Usuario"])) {
    require_once '../../../../include/db-conn.php';
    $user = $_SESSION["Usuario"];
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

  <title>Timlid | Agregar entrada</title>

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
  <link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="../../../../css/mdb.min.css" rel="stylesheet">
  <link href="../../../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../../../css/slimselect.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../../style/agregar_entrada.css">

  <!-- Custom styles for this page -->
  <link href="../../../../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
  <link href="../../../../vendor/datatables/responsive.dataTables.css" rel="stylesheet">
  <link rel="stylesheet" href="../../../../vendor/datatables/buttons.dataTables.css">
  <link href="../../../../vendor/datatables/buttons.bootstrap4.min.css" rel="stylesheet">
  <link href="../../../../css/styles.css" rel="stylesheet">

  <script src="../../../../js/numeral.min.js" charset="utf-8"></script>

  <script src="../../../../js/sweet/sweetalert2.js"></script>
  <link rel="stylesheet" href="../../../../css/sweetalert2.css">
</head>

<body id="page-top">
  <!-- Page Wrapper -->

  <div id="wrapper">

    <!-- Sidebar -->
    <?php
    $titulo = "Cambiar";
    $ruta = "../../../";
    $ruteEdit = $ruta . "central_notificaciones/";
    require_once $ruta . 'menu3.php';
    ?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <?php
          $rutatb = "../../../";
          $titulo = '<div class="header-screen">
                      <div class="header-title-screen">
                      <h1 class="h3 mb-2">Timdesk  <img src="../../../../img/timdesk/timdesk_icon.svg" alt="" style="position:relative;top:-5px;left:-7px;"></h1>
                      </div>
                     </div>';
          require_once $rutatb . 'topbar.php';
        ?>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">

            <!-- Page Heading -->
            <div class="divPageTitle" style="margin-left:10px;">
              <img src="../../../../img/inventarios/ICONO INVENTARIOS Y PROD_Mesa de trabajo 1.svg" width="45px" style="position:relative;top:-10px;">
              <label class="lblPageTitle" style="margin-left:10px;font-weight:bold;">Agregar entrada</label>
            </div>
            <!-- End Page Heading -->

            <!-- DataTales Example -->
            <div class="data-container">
              <div class="principal">
                <label for="cmbTipoEntrada">Tipo de entrada:</label>
                <select name="cmbTipoEntrada" id="cmbTipoEntrada" required></select>
              </div>

              <!-- Begins purchases section  -->
              <div class="purchases-disabled">
                <label for="cmbOrdenCompra">Orden de compra:</label>
                <select name="cmbOrdenCompra" id="cmbOrdenCompra"></select>
              </div>
              <!--
              <div class="purchases-disabled">
                <label for="txtFolio">Folio entrada:</label>
                <input class="form-control" name="txtFolio" id="txtFolio" readonly>
              </div>
              -->
              <input type="hidden" name="" id="usuario" value="<?=$user; ?>">
              <div class="purchases-disabled">
                <label for="cmbProveedor">Proveedor:</label>
                <select name="cmbProveedor" id="cmbProveedor" readonly></select>
              </div>
              <div class="purchases-disabled">
                <label for="txtReferencia">Documento:</label>
                <input class="form-control" name="txtReferencia" id="txtReferencia">
              </div>
              <div class="purchases-disabled">
                <label for="cmbAlmacen">Almacén:</label>
                <select name="cmbAlmacen" id="cmbAlmacen" readonly></select>
              </div>
              <div class="purchases-disabled" id="field-xml">
                <div id="output"><label for="fileXml">XML:</label> Seleccione un xml</div>
                <input type="file" name="fileXml" id="fileXml" accept=".xml">
                <button class="btnesp espAgregar" id="fileXml_alt" type="button">Seleccionar del XML</button>
                <!--<button class="btnesp first espCancelar" id="loadFolio" type="button">Cargar folio</button>-->
              </div>
              <br>
              <div class="purchases-disabled">
                <label for="">Notas:</label>
                <textarea class="form-control" name="txaNotaEntrada" id="txaNotaEntrada" rows="1" cols="200"></textarea>
              </div>
              <!-- Ends purchases section-->

              <!-- Begins repayment section -->
              <div class="repayment-disabled">
                <label for="cmbCliente">Cliente:</label>
                <select name="cmbCliente" id="cmbCliente"></select>
              </div>
              <div class="repayment-disabled">
                <label for="cmbDocumento">Documento:</label>
                <select name="cmbDocumento" id="cmbDocumento"></select>
              </div>
              <div class="repayment-disabled">
                <label for="cmbAlmacen2">Almacén:</label>
                <select name="cmbAlmacen2" id="cmbAlmacen2"></select>
              </div>
              <div class="repayment-disabled">
                <label for="cmbTicketCalidad">Ticket de calidad:</label>
                <select name="cmbTicketCalidad" id="cmbTicketCalidad"></select>
              </div>
              <div class="repayment-disabled">
                <label for="">Notas:</label>
                <textarea class="form-control" name="txaNotaDevolucionVenta" id="txaNotaDevolucionVenta" rows="1" cols="200"></textarea>
              </div>
              <!-- Ends repayment section -->

              <!-- Begins manufacturing Input section-->
              <div class="manufacturingInput-disabled">
                <label for="cmbOrdenFabricacion">Orden de fabricación:</label>
                <select name="cmbOrdenFabricacion" id="cmbOrdenFabricacion" readonly></select>
              </div>
              <div class="manufacturingInput-disabled">
                <label for="cmbAlmacen3">Almacén:</label>
                <select name="cmbAlmacen3" id="cmbAlmacen3"></select>
              </div>
              <!-- Ends manufacturing Input section-->

              <!-- Begins adjustment section -->
              <div class="adjustment-disabled">
                <label for="cmbAlmacen4">Almacén:</label>
                <select name="cmbAlmacen4" id="cmbAlmacen4"></select>
              </div>
              <div class="adjustment-disabled">
                <label for="cmbEntrada">Entrada:</label>
                <select name="cmbEntrada" id="cmbEntrada"></select>
              </div>
              <!-- Ends adjustment section -->

              <!-- Begins transfer section-->
              <div class="transfer-disabled">
                <label for="cmbSalida">Salida:</label>
                <select name="cmbSalida" id="cmbSalida" readonly></select>
              </div>
              <div class="transfer-disabled">
                <label for="cmbAlmacen5">Almacén:</label>
                <select name="cmbAlmacen5" id="cmbAlmacen5" readonly></select>
              </div>
                <!-- Ends transfer section-->

            </div>

            <div class="button-container" id="myButton">
              <div class="button-icon-container">
                <!-- data-toggle="modal" data-target="#agregar_producto" -->
                <button class="btn btn-info btn-circle float-right waves-effect waves-light" type="button" id="btnModalAgregarProducto"><i class="fas fa-plus"></i></button>
              </div>
              <div class="button-text-container">
                <span>Agregar producto</span>
              </div>
            </div>


            <div class="">
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table stripe" id="tblAgregarEntradasProductos" width="100%" cellspacing="0">
                    <thead>
                      <tr>
                        <th>No</th>
                        <th>Código</th>
                        <th>Producto</th>
                        <th>Lote</th>
                        <th>No. serie</th>
                        <th>Caducidad</th>
                        <th>Cantidad</th>
                        <!--<th>Precio unitario</th>
                        <th>Precio Total</th>-->
                      </tr>
                    </thead>
                  </table>
                </div>
                <div class="">
                  <button class="btnesp espAgregar float-right" type="button" name="btnAgregarEntrada" id="btnAgregarEntrada">Agregar entrada</button>
                </div>
              </div>
            </div>
          <!-- End DataTales Example -->

        </div>
        <!-- End Begin Page Content -->

      </div>
      <!-- End Main Content -->

      <!-- Footer -->
      <?php
      $rutaf = "../../../";
      require_once $rutaf . 'footer.php';
      ?>

    </div>
    <!-- End Content Wrapper -->

  </div>
  <!-- End Page Wrapper -->

  <!-- Modal agregar producto -->
  <div class="modal fade right" id="agregar_producto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-full-height modal-right modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title w-100" id="myModalLabel">Agregar producto</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="cmbProducto">Producto:</label>
            <select class="cmbProducto" name="cmbProducto" id="cmbProducto" required></select>
          </div>
          <div class="form-group">
            <div class="row">
              <div class="col-lg-6 ">
                <input type="checkbox" id="chkLote" name="chkLote">
                <label for="txtLote">Lote:</label>
              </div>
              <div class="col-lg-6 ">
                <input type="checkbox" id="chkNoSerie" name="chkNoSerie">
                <label for="txtNoSerie">No. serie:</label>
              </div>
            </div>
          </div>
          <div class="form-group">
            <input type="text" class="form-control" name="txtLote" id="txtLote" placeholder="Ingrese el lote...">
            <input type="text" class="form-control" name="txtNoSerie" id="txtNoSerie" placeholder="Ingrese el numero de serie...">
          </div>
          <div class="form-group">
            <label for="txtCaducidad">Caducidad:</label>
            <input type="date" class="form-control" name="txtCaducidad" id="txtCaducidad"></select>
          </div>

          <div class="form-group">
            <label for="txtCantidad">Cantidad:</label>
            <input type="number" class="form-control" name="txtCantidad" id="txtCantidad" min=1 required></select>
          </div>
          <!--
          <div class="form-group">
            <label for="txtPrecioUnitario">Precio unitario:</label>
            <input type="text" class="form-control" name="txtPrecioUnitario" id="txtPrecioUnitario" readonly value="19.50"></select>
          </div>
          <div class="form-group">
            <label for="txtPrecioTotal">Precio total:</label>
            <input type="text" class="form-control" name="txtPrecioTotal" id="txtPrecioTotal" readonly></select>
          </div>
          -->
        </div>
        <div class="modal-footer justify-content-center">
          <button type="button" class="btnesp first espCancelar " data-dismiss="modal"
            id="btnCancelarProducto"><span>Cancelar</span></button>
          <button type="submit" class="btnesp espAgregar float-right" name="btnAgregar"
            id="btnAgregarProducto"><span>Agregar</span></button>
        </div>
      </div>
    </div>
  </div>
  <!-- End modal agregar producto -->

  <!-- Modal agregar tipos entradas -->
  <div class="modal fade right" id="agregar_TipoEntrada" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-full-height modal-right modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title w-100" id="myModalLabel">Agregar tipo de entrada</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="txtTipoEntrada">Tipo de entrada:</label>
            <input type="text" id="txtTipoEntrada" class="form-control alpha-only" maxlength="40" name="txtTipoEntrada"
              required>
          </div>

        </div>
        <div class="modal-footer justify-content-center">
          <button type="button" class="btnesp first espCancelar " data-dismiss="modal"
            id="btnCancelarTipoEntrada"><span>Cancelar</span></button>
          <button type="submit" class="btnesp espAgregar float-right" name="btnAgregar"
            id="btnAgregarTipoEntrada"><span>Agregar</span></button>
        </div>
      </div>
    </div>
  </div>
  <!-- End modal agregar tipos entradas -->

  <script>
  var ruta = "../../../";
  </script>

  <script src="../../js/agregar_entrada_old.js" charset="utf-8"></script>
  <script src="../../../../js/scripts.js"></script>
  <!-- Custom scripts for all pages-->
  <script src="../../../../js/sb-admin-2.min.js"></script>
  <script src="../../../../js/slimselect.min.js"></script>
  <script>
  $(document).ready(function() {
    $("#alertaTareas").load('<?=$ruta?>alerta_Tareas_Nuevas.php?user=' + <?=$_SESSION['PKUsuario'];?> + '&ruta=' +
      '<?=$ruta;?>&ruteEdit=<?=$ruteEdit;?>');
    setInterval(refrescar, 50000);
  });

  function refrescar() {
    $("#alertaTareas").load('<?=$ruta;?>alerta_Tareas_Nuevas.php?user=' + <?=$_SESSION['PKUsuario'];?> + '&ruta=' +
      '<?=$ruta;?>&ruteEdit=<?=$ruteEdit;?>');
  }
  </script>
</body>

</html>
