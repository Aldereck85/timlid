<?php

$screen = 61;
$ruta = "../../../";

require_once $ruta . 'validarPermisoPantalla.php';
if (isset($_SESSION["Usuario"]) && $permiso === 1) {
  require_once '../../../../include/db-conn.php';
} else {
  header("location:../../../dashboard.php");
}
$jwt_ruta = "../../../../";
require_once '../../../jwt.php';

date_default_timezone_set('America/Mexico_City');

$token = $_SESSION['token_ld10d'];
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="icon" type="image/png" href="../../../../img/header/bluTimlid.png">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Timlid | Órdenes de Producción</title>

  <!-- ESTILOS -->
  <link href="../../../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../../../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../../../css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="../../../../vendor/datatables/responsive.dataTables.css" rel="stylesheet">
  <link href="../../../../vendor/datatables/buttons.dataTables.css" rel="stylesheet">
  <link href="../../../../css/styles.css" rel="stylesheet">
  <link href="../../../../css/stylesNewTable.css" rel="stylesheet">
  <link href="../../../../css/slimselect.min.css" rel="stylesheet">
  <link href="../../../../css/sweetalert2.css" rel="stylesheet">
  <link href="../../../../css/lobibox.min.css" rel="stylesheet">
  <link href="../../style/ordenes_produccion.css" rel="stylesheet">
  <!-- JS -->
  <script src="../../../../vendor/jquery/jquery.min.js"></script>
  <script src="../../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../../../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../../../../js/sb-admin-2.min.js"></script>
  <script src="../../../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../../../vendor/datatables/dataTables.buttons.js"></script>
  <script src="../../../../vendor/datatables/buttons.html5.min.js"></script>
  <script src="../../../../vendor/jszip/jszip.min.js"></script>
  <script src="../../../../js/jquery.redirect.js"></script>
  <script src="../../../../js/notificaciones_timlid.js" charset="utf-8"></script>
  <script src="../../../../js/mdtimepicker.min.js"></script>
  <script src="../../../../js/permisos_usuario.js"></script>
</head>

<body id="page-top" data-screen="61">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <?php
    $icono = '../../../../img/icons/ICONO FACTURACION-01.svg';
    $titulo = 'Órdenes de producción';

    $ruteEdit = $ruta . "central_notificaciones/";
    require_once $ruta . 'menu3.php';
    ?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <?php
        $rutatb = "../../../";
        require_once '../../../topbar.php';
        ?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- DataTales Example -->
          <div class="card shadow mb-4">
            <div class="card-body">
              <div class="table-responsive">
                <table class="table" id="tblOrdenesProduccion" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Folio</th>
                      <th>Sucursal</th>
                      <th>Producto</th>
                      <th>Fecha inicio</th>
                      <th>Fecha estimada</th>
                      <th>Fecha termino</th>
                      <th>Responsable</th>
                      <th>Estatus</th>
                    </tr>
                  </thead>
                  <tbody></tbody>
                </table>
              </div>
            </div>
          </div>

        </div>
        <!-- End Page Content -->
      </div>
      <!-- End Main Content -->
      <!-- Footer -->
      <?php
      $rutaf = "../../../";
      require_once '../../../footer.php';
      ?>
      <!-- End of Footer -->
    </div>
    <!-- End Content Wrapper -->

  </div>
  <!-- End Page Wrapper -->

  <script src="../../js/ordenes_produccion.js"></script>
  <script src="../../../../js/scripts.js"></script>

</body>

</html>