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
  <title>Timlid | Cuentas Por Cobrar</title>

  <!-- ESTILOS -->
  <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="../../vendor/datatables/buttons.dataTables.css" rel="stylesheet">
  <link href="../../css/stylesModal-lg.css" rel="stylesheet">
  <link href="../../css/lobibox.min.css" rel="stylesheet">
  <link href="../../css/slimselect.min.css" rel="stylesheet">
  <link href="../../css/styles.css" rel="stylesheet">
  <link href="../../css/stylesNewTable.css" rel="stylesheet">
  <link href="css/modal.css" rel="stylesheet">
  <link href="css/titleTabla.css" rel="stylesheet">
  <link rel="stylesheet" href="../../css/notificaciones.css">
  <!-- JS -->
  <script src="../../vendor/jquery/jquery.min.js"></script>
  <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="js/data_cuenta_Cliente.js"></script>
  <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../../js/sweet/sweetalert2.js"></script>
  <script src="../../js/validaciones.js"></script>
  <script src="../../js/lobibox.min.js"></script>
  <script src="../../js/slimselect.min.js"></script>
  <script src="../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../vendor/datatables/dataTables.buttons.js"></script>
  <script src="../../vendor/datatables/buttons.html5.min.js"></script>
  <script src="../../vendor/jszip/jszip.min.js"></script>
  <script src="js/filtrar.js"></script>
  <script src="../../js/notificaciones_timlid.js" charset="utf-8"></script>
  <script src="../../js/jquery.redirect.min.js"></script>




</head>

<!--verificacion de permisos para la pagina-->
<?php
    require_once 'functions/function_Permisos.php';
?>

<body id="page-top" class="sidebar-toggled">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <?php
      $titulo = "Cuentas por cobrar";
      $ruta = "../";
      $ruteEdit = $ruta . "central_notificaciones/";
      require_once '../menu3.php';
    ?>
    <!-- End of Sidebar -->
    <input type="hidden" id="txtUsuario" value="<?=$_SESSION['PKUsuario'];?>">
    <input type="hidden" id="txtRuta" value="<?=$ruta;?>">
    <input type="hidden" id="txtEdit" value="<?=$ruteEdit;?>">
    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <?php
          $rutatb = "../";
          $backIcon=true;
          $icono = '../../img/icons/CUENTAS POR PAGAR.svg';
          $backIcon = true;
          require_once "../topbar.php"
        ?>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">
          <!-- Page Heading
          inputs para recuperar los datos para ser enviados al js
          -->
          <input type="hidden" id="cliente_id" value="<?php echo ($_GET["id"]); ?>" />
          <input type="hidden" id="seleccion" value="<?php echo ($_GET["seleccion"]); ?>" />
          <input type="hidden" id="periodo" value="<?php echo (Int)($_GET['periodo']); ?>" />
          <!-- DataTales Example -->
          <div class="card mb-4">
            <div class="card-header py-3">
               <div class="titleTabla">
                  <h4>Tabla de cuentas por cobrar del cliente</h4>                 
                </div>
            </div>
            <!-- tabla para el detalle de la cuenta -->
            <div class="card-body">
              <div class="form-group" id="groupFiltro">
                <div class="row">
                  <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-xs-6">
                    <label for="txtDateFrom">De:</label>
                    <input class="form-control" type="date" name="txtDateFrom" id="txtDateFrom" >
                    <div class="invalid-feedback" id="invalid-txtDateFrom"></div>
                  </div>
                  <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-xs-6">
                      <label for="txtDateTo">Hasta:</label>
                      <input class="form-control" type="date" name="txtDateTo" id="txtDateTo" >
                      <div class="invalid-feedback" id="invalid-txtDateTo">.</div>
                  </div>
                  <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-xs-6">
                    <a class="btn-custom btn-custom--blue" id="btnFilterExits" style="margin-top: 10px!important">Filtrar</a>
                  </div> 
                </div>
              </div>
              <BR>      
              <div class="table-responsive">
                <table class="table" id="tblClientes" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Folio</th>
                      <th>Cliente</th>
                      <th>F. Expedición</th>
                      <th>F. Vencimiento</th>
                      <th>Estatus</th>
                      <th>Total</th>
                      <th>Importe Pagado</th>
                      <th>No. Parcialidades</th>
                      <th>Importe Notas de Crédito</th>
                      <th>Saldo Insoluto</th>
                      <th>Folios Complementos Pago</th>
                      <th>Folios Notas Crédito</th>
                      <th></th>
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

  <!-- modal de alerta de permisos denegados -->
  <?php 
    require_once 'modal_alert.php';
    require_once 'modal_alert_url.php';
  ?>

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">¿En serio quieres salir?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">x</span>
          </button>
        </div>
        <div class="modal-body">Selecciona "Salir" Para cerrar tu sesión</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
          <a class="btn btn-primary" href="../logout.php">Salir</a>
        </div>
      </div>
    </div>
  </div>

    <!-- Custom scripts for all pages-->
  <script src="../../js/sb-admin-2.min.js"></script>
  <script src="../../js/scripts.js"></script>
  <script src="js/scriptNotificaciones.js"></script>

</body>
</html>