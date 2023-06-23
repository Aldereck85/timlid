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
  <title>Timlid | Recepción De Pagos</title>

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
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
  <link href="css/disabled.css" rel="stylesheet">
  <link href="css/modal.css" rel="stylesheet">
  <link rel="stylesheet" href="../../css/notificaciones.css">
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
  <script src="js/eliminarPago.js"></script>
  <script src="js/filtrar.js"></script>
  <script src="../../js/notificaciones_timlid.js" charset="utf-8"></script>
</head>

<!--verificacion de permisos para la pagina-->
<?php
require_once 'functions/function_Permisos.php';

//verificacion de la variable para las notificaciones desde otra pantalla
if (isset($_SESSION["actualizadoCPC"])) {
  echo ('<input type="hidden" id="notifi" value="' . $_SESSION["actualizadoCPC"] . '">');
  unset($_SESSION["actualizadoCPC"]);
} else {
  echo ('<input type="hidden" id=/"notifi/" value="0">');
}
?>

<body id="page-top" class="sidebar-toggled">
<div id=loader></div>

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <?php
    $titulo = "Recepción de pagos";
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
        //se define la ruta de regreso
        if(isset($_REQUEST['rutaFrom'])){
          $backIcon=true;
          switch ($_REQUEST['rutaFrom']){
            case 1:
              $_REQUEST['rutaFrom']='../cuentas_cobrar/cuentas_Cliente.php?periodo='. ($_REQUEST['periodo']) .'&id='. ($_REQUEST['idCliente']) . '&seleccion='. ($_REQUEST["seleccion"]);
              break;
            case 2:
              if(isset($_REQUEST["is_invoice"])){
                $doc = $_REQUEST["is_invoice"] == 1 ? 'idFactura' : 'idVenta';
              }
              $_REQUEST['rutaFrom']='../cuentas_cobrar/detalle_factura.php?'.$doc.'='.$_REQUEST['idFactura'];
              break;
          }
        }

        $backRoute = (isset($_REQUEST["rutaFrom"]) && ($_REQUEST["rutaFrom"] != "" || $_REQUEST["rutaFrom"] != null)) ? $_REQUEST["rutaFrom"] : "../recepcion_pagos";

        $icono = 'ICONO-RECEPCION-DE-PAGOS-AZUL.svg';
        require_once "../topbar.php";

        //define los ids en html
        if (isset($_REQUEST["idCliente"])) {
          echo ('<input type="hidden" id="idClienteFrom" value="' . $_REQUEST['idCliente'] . '">');
        }
        if (isset($_REQUEST["idFactura"])) {
          echo ('<input type="hidden" id="idFacturaFrom" value="' . $_REQUEST['idFactura'] . '">');
        }

        if (isset($_REQUEST["is_invoice"])) {
          echo ('<input type="hidden" id="is_invoice" value="' . $_REQUEST['is_invoice'] . '">');
        }
      
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
                      <label for="cmbProveedor">Cliente:</label>
                      <select name="chosenClientes" id="chosenClientes" required>
                      <option disabled selected value="f">Selecciona un cliente</option>
                    </select>
                    <div class="invalid-feedback" id="invalid-cmbCliente">.</div>
                  </div>
                  <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-xs-6">
                    <label for="txtDateFrom">De:</label>
                    <input class="form-control" type="date" name="txtDateFrom" id="txtDateFrom">
                    <div class="invalid-feedback" id="invalid-txtDateFrom"></div>
                  </div>
                  <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-xs-6">
                    <label for="txtDateTo">Hasta:</label>
                    <input class="form-control" type="date" name="txtDateTo" id="txtDateTo">
                    <div class="invalid-feedback" id="invalid-txtDateTo">.</div>
                  </div>
                  <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-xs-6">
                    <a class="btn-custom btn-custom--blue" id="btnFilterExits" style="margin-top: 10px!important">Filtrar</a>
                  </div>
                </div>
              </div>
              <BR>
              <!-- table to show accounts by range-->
              <div class="table-responsive" id="movimientos">
                <table class="table" id="tblmovimientos" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Folio pago</th>
                      <th>Folio referencia</th>
                      <th>Cliente</th>
                      <th>Fecha</th>
                      <th>Forma de pago</th>
                      <th>Metodo de pago</th>
                      <th>Cuenta</th>
                      <th>Monto total</th>
                      <th>Referencia</th>
                      <th>Aplicado por : </th>
                      <th>Estatus de complemento de pago</th>
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

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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

  <?php
  require_once 'modal_alert.php';
  require_once 'modal_alert_deletePago.php';
  require_once 'modal_alert_cancelComplement.php';
  ?>

  <!-- Custom scripts for all pages-->
  <script src="../../js/sb-admin-2.min.js"></script>
  <script src="../../js/scripts.js"></script>
  <script src="../../js/jquery.redirect.min.js"></script>
  <script src="js/scriptNotificaciones.js"></script>
  <script>
    var selectCliente = new SlimSelect({
      select: '#chosenClientes',
      deselectLabel: '<span class="">✖</span>'
    });
    var selectMotivoCancelacion = new SlimSelect({
      select: '#cmbMotivoCancela',
      deselectLabel: '<span class="">✖</span>'
    });
  </script>

</body>

</html>