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
  <title>Timlid | Materiales a consumir</title>

  <!-- ESTILOS -->
  <link href="../../../../css/slimselect.min.css" rel="stylesheet">
  <link href="../../../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../../../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../../../css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="../../../../vendor/datatables/buttons.dataTables.css" rel="stylesheet">
  <link href="../../../../css/styles.css" rel="stylesheet">
  <link href="../../style/materiales.css" rel="stylesheet">
  <link href="../../../../css/stylesNewTable.css" rel="stylesheet">
  <link href="../../../../css/lobibox.min.css" rel="stylesheet">
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
  <script src="../../../../js/slimselect.min.js"></script>
  <script src="../../../../js/lobibox.min.js"></script>
  <script src="../../../../js/notificaciones_timlid.js" charset="utf-8"></script>
  <script src="../../js/materiales_consumir.js" charset="utf-8"></script>

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
        $icono = '../../../../img/icons/insumos.svg';
        $titulo = 'Materiales a consumir';
        require_once $rutatb . 'topbar.php';
        ?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->

          <!-- DataTales Example -->
          <div class="card mb-4">
            <div class="card-body">
            <div class="filtros mb-4">
                <form>
                  <div class="form-row">
                    <div class="col-md-3 mb-3">
                      <label for="sucursal_input">Sucursal</label>
                      <select name="" class="form-control" id="sucursal_input">
                      </select>
                      <div class="invalid-feedback" id="invalid-sucursal">
                        La sucursal no puede estar vacia.
                      </div>
                    </div>
                    <div class="col-md-3 mb-3">
                      <button id="filtro_materiales" type="button" class="btn-custom btn-custom--blue ml-md-5">Filtrar</button>
                    </div>
                    <div class="col-md-3 mb-3">
                    </div>
                    <div class="col-md-3 mb-3">
                    </div>
                  </div>
                </form>
              </div>
              <div class="table-responsive">
                <table class="table" id="tblListaMateriales" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Id</th>
                      <th>Sucursal</th>
                      <th>Clave interna</th>
                      <th>Nombre</th>
                      <th>Existencia</th>
                      <th>Materiales a consumir</th>
                      <th>Unidad</th>
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

  <script src="../../../../js/sb-admin-2.min.js"></script>
  <script src="../../../../js/scripts.js"></script>
</body>
<script src="../../../../js/Sortable.js"></script>
<script src="../../../../js/pagination/pagination.js"></script>
<script>
  loadAlertsNoti('alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
  setInterval(loadAlertsNoti, 10000, 'alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
</script>

</html>