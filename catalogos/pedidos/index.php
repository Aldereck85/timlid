<?php
session_start();

$jwt_ruta = "../../";
require_once '../jwt.php';

if (isset($_SESSION["Usuario"])) {
  require_once '../../include/db-conn.php';
  $user = $_SESSION["Usuario"];
  $message = "";
  $estatus = "";
  if (isset($_GET['estatus'])) {
    $estatus = $_GET['estatus'];
  }
} else {
  header("location:../dashboard.php");
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="icon" type="image/png" href="../../img/header/bluTimlid.png">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Timlid | Pedidos</title>

  <!-- ESTILOS -->
  <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../css/styles.css" rel="stylesheet">
  <link href="../../css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="../../vendor/datatables/responsive.dataTables.css" rel="stylesheet">
  <link href="../../vendor/datatables/buttons.dataTables.css" rel="stylesheet">
  <link href="../../css/stylesNewTable.css" rel="stylesheet">
  <link href="../../css/notificaciones.css" rel="stylesheet">
  <link href="../../css/lobibox.min.css" rel="stylesheet">
  <!-- JS -->
  <script src="../../vendor/jquery/jquery.min.js"></script>
  <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../js/sb-admin-2.min.js"></script>
  <script src="../../js/scripts.js"></script>
  <script src="../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../vendor/datatables/dataTables.buttons.js"></script>
  <script src="../../vendor/datatables/buttons.html5.min.js"></script>
  <script src="../../vendor/jszip/jszip.min.js"></script>
  <script src="../../js/notificaciones_timlid.js" charset="utf-8"></script>
  <script src="../../js/sweet/sweetalert2.js"></script>
  <script src="js/pedido.js" charset="utf-8"></script>
  <script src="../../js/lobibox.min.js"></script>
  <script src="../../js/jquery.redirect.min.js"></script>
</head>

<body id="page-top" class="sidebar-toggled">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->

    <?php
    $ruta = "../";
    $ruteEdit = $ruta . "central_notificaciones/";
    require_once '../menu3.php';
    ?>
    </ul>
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
        $rutatb = "../";
        $icono = 'ICONO-PEDIDOS-AZUL.svg';
        $titulo = "Pedidos";
        require_once '../topbar.php';
        ?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <p class="mb-4"></p>

          <!-- DataTales Example -->
          <div class="card mb-4">
            <div class="card-body">
              <div class="container-fluid mb-3">
                <div class="row align-items-center">
                  <div class="col-12 col-md-4 p-0">
                    <div class="form-row">
                      <div class="form-group col-md-6">
                        <label for="txtDateFrom">Fecha inicio:</label>
                        <input class="form-control" type="date" name="txtDateFrom" id="txtDateFrom" required>
                      </div>
                      <div class="form-group col-md-6">
                        <label for="txtDateTo">Fecha final:</label>
                        <input class="form-control" type="date" name="txtDateTo" id="txtDateTo" required>
                      </div>
                    </div>
                  </div>
                  <div class="col-12 col-md-1">
                    <a class="btn-custom btn-custom--blue" id="btnFilterEntries" style="margin-top: 10px!important">Filtrar</a>
                  </div>
                </div>
              </div>
              <div class="table-responsive">
                <table class="table" id="tblPedido" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th style="width: 10%;">No. Pedido</th>
                      <th style="width: 10%;">Sucursal origen</th>
                      <th style="width: 10%;">Sucursal destino</th>
                      <th>Cliente</th>
                      <th>Fecha generación</th>
                      <th>Tipo</th>
                      <th>Estatus</th>
                      <th>Estatus factura</th>
                      <th style="width: 10%;">Folio-serie facturación</th>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>
          </div>

        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

      <!-- Footer -->
      <?php
      $rutaf = "../";
      require_once '../footer.php';
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

  <!-- Update Modal mis productos -->
  <div id="seleccionar_Cotizacion" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="agregarPedido.php" method="POST">
          <div class="modal-header">
            <h4 class="modal-title">Tipo pedido</h4>
            <button type="button" class="close text-light" data-dismiss="modal" aria-hidden="true">x</button>
          </div>
          <div class="modal-body text-center">
            <p>Selecciona el tipo de pedido:</p>
            <br>
            <input type="button" class="btn-custom btn-custom--blue" id="aceptarGeneral" value="General">
            <input type="button" class="btn-custom btn-custom--blue" id="aceptarTraspaso" value="Traspaso">
          </div>
        </form>
      </div>
    </div>
  </div>


  <script>
    function obtenerIdCotizacionEliminar(id) {
      document.getElementById('idCotizacionD').value = id;
    }

    function obtenerIdCotizacionEditar(id) {
      document.getElementById('idCotizacionU').value = id;
    }

    function crearPedido() {
      $("#seleccionar_Cotizacion").modal('toggle');
    }

    $("#aceptarGeneral").click(function() {
      $().redirect('agregarPedido.php', {
        'tipo_pedido': 2
      });
    });

    $("#aceptarTraspaso").click(function() {
      $().redirect('agregarPedido.php', {
        'tipo_pedido': 1
      });
    });
  </script>
  <script src="../../js/sb-admin-2.min.js"></script>
  <script src="../../js/scripts.js"></script>
  <script>
    loadAlertsNoti('alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
    setInterval(loadAlertsNoti, 10000, 'alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
  </script>

</body>

</html>