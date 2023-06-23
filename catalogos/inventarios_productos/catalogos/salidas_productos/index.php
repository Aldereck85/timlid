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

?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="icon" type="image/png" href="../../../../img/header/bluTimlid.png">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Timlid | Salidas</title>

  <!-- ESTILOS -->
  <link href="../../../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../../../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../../../css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="../../../../vendor/datatables/buttons.dataTables.css" rel="stylesheet">
  <link href="../../../../css/styles.css" rel="stylesheet">
  <link href="../../style/salidasProductos.css" rel="stylesheet">
  <link href="../../../../css/slimselect.min.css" rel="stylesheet">
  <link href="../../style/agregar_entrada.css" rel="stylesheet">
  <link href="../../../../css/stylesNewTable.css" rel="stylesheet">
  <link href="../../../../css/notificaciones.css" rel="stylesheet">
  <!-- JS -->
  <script src="../../../../vendor/jquery/jquery.min.js"></script>
  <script src="../../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../../../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../../../../js/sb-admin-2.min.js"></script>
  <script src="../../../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../../../vendor/datatables/dataTables.buttons.js"></script>
  <script src="../../../../vendor/datatables/buttons.html5.min.js"></script>
  <script src="../../../../vendor/jszip/jszip.min.js"></script>
  <script src="../../../../js/notificaciones_timlid.js" charset="utf-8"></script>
  <script src="../../js/salidasProductos.js" charset="utf-8"></script>
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
        $titulo = 'Salidas';
        require_once $rutatb . 'topbar.php';
        ?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- DataTales Example -->
          <div class="card mb-4">
            <div class="card-body">
              <div class="container-fluid mb-3">
                <div class="row align-items-center">
                  <div class="col-12 col-md-11">
                    <div class="form-row">
                      <div class="form-group col-md-3">
                        <label for="cmbBranchFilter">Sucursal:</label>
                        <select name="cmbBranchFilter" id="cmbBranchFilter" required></select>
                      </div>
                      <div class="form-group col-md-3">
                        <label for="cmbTypeExitFilter">Tipo de salida:</label>
                        <select name="cmbTypeExitFilter" id="cmbTypeExitFilter" required></select>
                      </div>
                      <div class="form-group col-md-3">
                        <label for="txtDateFrom">De:</label>
                        <input class="form-control" type="date" name="txtDateFrom" id="txtDateFrom" required>
                      </div>
                      <div class="form-group col-md-3">
                        <label for="txtDateTo">Hasta:</label>
                        <input class="form-control" type="date" name="txtDateTo" id="txtDateTo" required>
                      </div>
                    </div>
                  </div>
                  <div class="col-12 col-md-1">
                    <a class="btn-custom btn-custom--blue" id="btnFilterExits">Filtrar</a>
                  </div>
                </div>
              </div>
              <div class="table-responsive">
                <table class="table" id="tblSalidasProductos" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Id</th>
                      <th>Folio</th>
                      <th>Origen</th>
                      <th>Destino</th>
                      <th>Fecha</th>
                      <th>Tipo salida</th>
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

  <script>

  </script>
  <script src="../../../../js/sb-admin-2.min.js"></script>
  <script src="../../../../js/scripts.js"></script>
  <script src="../../../../js/slimselect.min.js"></script>
</body>
<script src="../../../../js/Sortable.js"></script>
<script src="../../../../js/pagination/pagination.js"></script>
<script>
  loadAlertsNoti('alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
  setInterval(loadAlertsNoti, 10000, 'alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
</script>

</html>