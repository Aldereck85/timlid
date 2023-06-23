<?php
session_start();
$jwt_ruta = "../../";
require_once '../jwt.php';

date_default_timezone_set('America/Mexico_City');

$token = $_SESSION['token_ld10d'];

if (isset($_SESSION["Usuario"])) {
  require_once '../../include/db-conn.php';
  $user = $_SESSION["Usuario"];
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
  <title>Timlid | Comisiones</title>

  <!-- ESTILOS -->
  <link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">
  <link href="../../css/slimselect.min.css" rel="stylesheet">
  <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="../../vendor/datatables/buttons.dataTables.css" rel="stylesheet">
  <link href="../../css/styles.css" rel="stylesheet">
  <link href="../../css/stylesNewTable.css" rel="stylesheet">
  <link href="../../css/stylesModal-lg.css" rel="stylesheet">
  <link href="../../css/lobibox.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" rel="stylesheet">
  <link href="css/disabled.css" rel="stylesheet">
  <link href="css/detalle_calculo.css" rel="stylesheet">
  
  <!-- JS -->
  <script src="../../vendor/jquery/jquery.min.js"></script>
  <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.4/moment.min.js"></script>
  <script src="https://cdn.datatables.net/datetime/1.1.0/js/dataTables.dateTime.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/plug-ins/1.10.21/sorting/datetime-moment.js"></script>
  <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../../js/sweet/sweetalert2.js"></script>
  <script src="../../js/validaciones.js"></script>
  <script src="../../js/lobibox.min.js"></script>
  <script src="../../js/slimselect.min.js"></script>
  <script src="../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../vendor/datatables/dataTables.buttons.js"></script>
  <script src="../../vendor/datatables/buttons.html5.min.js"></script>
  <script src="../../vendor/jszip/jszip.min.js"></script>
  <script src="js/data_index.js"></script>
  <script src="../../js/notificaciones_timlid.js"></script>
</head>

<body id="page-top" class="sidebar-toggled">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <?php
    $titulo = "Comisiones";
    $ruta = "../";
    $ruteEdit = $ruta . "central_notificaciones/";
    require_once '../menu3.php';
    ?>

    <!-- End of Sidebar -->
    <input type="hidden" id="txtUsuario" value="<?= $_SESSION['PKUsuario']; ?>">
    <input type="hidden" id="txtRuta" value="<?= $ruta; ?>">
    <input type="hidden" id="txtEdit" value="<?= $ruteEdit; ?>">

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
      
      <!-- Main Content -->
      <div id="content">
        
        <!-- Topbar -->
        <?php
        $rutatb = "../";
        $icono = '../../img/icons/CUENTAS POR PAGAR.svg';
        require_once "../topbar.php";
        ?>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">
          <!-- DataTales Example -->
          <div class="card mb-4">   
            <div class="card-body"> 
            <div class="form-group">
                  <div class="row">
                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-xs-6" style="margin-left:20px;">
                      <label for="cmbProveedor">Vendedor:</label>
                      <select name="chosenVendedores" id="chosenVendedores" required>
                      <option disabled selected value="f">Selecciona un vendedor</option>
                    </select>
                    <div class="invalid-feedback" id="invalid-cmbVendedores">.</div>
                  </div>
                  <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-xs-6">
                    <label for="txtDateFrom">Desde:</label>
                    <input class="form-control" type="date" name="txtDateFrom" id="txtDateFrom">
                    <div class="invalid-feedback" id="invalid-txtDateFrom"></div>
                  </div>
                  <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-xs-6">
                    <label for="txtDateTo">Hasta:</label>
                    <input class="form-control" type="date" name="txtDateTo" id="txtDateTo">
                    <div class="invalid-feedback" id="invalid-txtDateTo">.</div>
                  </div>
                  <div> 
                    <a class="btn-custom btn-custom--blue" id="btnFilterExits" style="margin-top: 10px!important; margin-right:45px; margin-left:20px;">Filtrar</a>
                    <a class="btn-custom btn-custom--blue" id="btnMostrarTCalculos" style="margin-top: 10px!important; margin-right:45px; margin-left:20px;">Mostrar todos</a>
                    <a class="btn-custom btn-custom--blue" id="btnVerTotales" style="margin-top: 10px!important">Ver totales</a>
                  </div>
                </div>
              </div>
              <BR>
              <!-- table to show accounts by range-->
              <div class="table-responsive" id="movimientos">
                <table class="table" id="tblcomisiones" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Número de cálculo</th>
                      <th>Fecha</th>
                      <th>Vendedor</th>
                      <th>Monto calculado</th>
                      <th>Monto ingresado</th>
                      <th>Porcentaje de comisión</th>
                      <th>Saldo insoluto</th>
                      <th><center>Estatus</center></th>
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

    <?php 
    require_once 'modal_alert_eliminar_calculo.php';
    require_once 'modal_totales_vendedor.php';
    ?>

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
      <i class="fas fa-angle-up"></i>
    </a>
  </div>

  <!-- Custom scripts for all pages-->
  <script src="../../js/sb-admin-2.min.js"></script>
  <script src="../../js/scripts.js"></script>
  <script src="../../js/jquery.redirect.min.js"></script>

  <script>
    var selectVendedor = new SlimSelect({
      select: '#chosenVendedores',
      deselectLabel: '<span class="">✖</span>'
    });
  </script>
  
</body>
</html>
