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
  <title>Timlid | Ver pago</title>

  <!-- ESTILOS -->
  <link href="../../css/slimselect.min.css" rel="stylesheet">
  <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="../../vendor/datatables/buttons.dataTables.css" rel="stylesheet">
  <link href="../../css/stylesModal-lg.css" rel="stylesheet">
  <link href="../../css/lobibox.min.css" rel="stylesheet">
  <link href="../../css/styles.css" rel="stylesheet">
  <link href="../../css/stylesNewTable.css" rel="stylesheet">
  <link href="css/modal.css" rel="stylesheet">
  <link rel="stylesheet" href="../../css/notificaciones.css">
  <link href="css/signoPesos.css" rel="stylesheet">
  <link href="css/disabled.css" rel="stylesheet">
  <!-- JS -->
  <script src="../../vendor/jquery/jquery.min.js"></script>
  <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../../js/sweet/sweetalert2.js"></script>
  <script src="../../js/validaciones.js"></script>
  <script src="../../js/lobibox.min.js"></script>
  <script src="../../js/slimselect.min.js"></script>
  <script src="../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../vendor/datatables/dataTables.buttons.js"></script>
  <script src="../../vendor/datatables/buttons.html5.min.js"></script>
  <script src="../../vendor/jszip/jszip.min.js"></script>
  <script src="js/detalle_pago.js"></script>
  <script src="js/eliminarPago.js"></script>
  <script src="../../js/notificaciones_timlid.js" charset="utf-8"></script>
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
      $titulo = "Ver pago";
      $ruta = "../";
      $ruteEdit = $ruta . "central_notificaciones/";
      require_once '../menu3.php';
    ?>
    <!-- End of Sidebar -->
    <input type="hidden" id="txtUsuario" value="<?=$_SESSION['PKUsuario'];?>">
    <input type="hidden" id="txtRuta" value="<?=$ruta;?>">
    <input type="hidden" id="txtEdit" value="<?=$ruteEdit;?>">

    <!-- Recuperacion del pago a mostrar -->
    <input type="hidden" id="idPago" value="<?php echo ($_GET['idPago']); ?>" />

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
    <div id=loader></div>

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <?php
          $rutatb = "../";
          $backIcon=true;
          $icono = 'ICONO-RECEPCION-DE-PAGOS-AZUL.svg';
          require_once "../topbar.php"
        ?>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">
          <!-- Page Heading-->
          <!-- DataTales Example -->
          <div class="card mb-4">
            <!-- tabla para el detalle de la cuenta -->
            <div class="card-body"> 
              <div class="row">
                <div id="divPdf">

                </div>
                <div id="divXml">

                </div>
                <div id="divCancelarComplemento">

                </div>
                <div id="divEditar">

                </div>
                <div id="divBTimbrar">

                </div>
                <div id="divEliminarPago">

                </div>
              </div>
              <br>
              <div class="form-group textData">
                <div class="row">
                  <div class="col-sm-3">
                    <div class="col-xl-12 col-lg-6 col-md col-sm col-xs">
                      <label for="usr" class="textblue"><b>Cliente:</b></label>
                      <div id="usr">
                        <span id="cliente"></span>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-3">
                    <div class="col-xl-12 col-lg-6 col-md col-sm col-xs">                    
                      <label for="usr" class="textblue"><b>Forma de pago:</b></label>
                      <div id="usr">
                        <span id="cmbForma"></span>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-3">
                    <div class="col-xl-8 col-lg-6 col-md col-sm col-xs">
                     <label for="usr" class="textblue"><b>Fecha:</b></label>
                     <div id="usr">
                        <span id="txtFecha"></span>
                     </div>
                    </div>
                  </div>
                  <div class="col-sm-3">
                    <div class="col-xl-8 col-lg-6 col-md col-sm col-xs">                      
                      <label for="usr" class="textblue"><b>Total:</b></label>
                      <div id="usr">
                        $ <span id="txtTotal"></span>
                      </div>
                    </div> 
                  </div>
                </div>
                <br>
                <div class="row">
                  <div class="col-sm-3">
                    <div class="col-xl-12 col-lg-6 col-md col-sm col-xs">                  
                      <label for="usr" class="textblue"><b>Cuenta:</b></label>
                      <div id="usr">
                        <span id="cuenta"></span>
                      </div>
                   </div>
                  </div>
                  <div class="col-sm-3">
                    <div class="col-xl-12 col-lg-6 col-md col-sm col-xs">
                      <label for="usr" class="textblue"><b>Referencia:</b></label>
                      <div id="usr">
                        <span id="Referencia"></span>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-3"></div>
                  <div class="col-sm-3">
                    <div class="col-xl-12 col-lg-6 col-md col-sm col-xs">                    
                      <label for="usr" class="textblue"><b>Método de pago:</b></label>
                      <div id="usr">
                        <span id="cmbTipo"></span>
                      </div>
                    </div>
                  </div>         
                </div>
                <br>
                <div class="table-responsive">
                  <table class="table" id="tblFacturas" width="100%" cellspacing="0">
                    <thead>
                      <tr>
                        <th>Folio</th>
                        <th>Cliente</th>
                        <th>F. Expedición</th>
                        <th>F. Vencimiento</th>
                        <th>Monto total</th>
                        <th>Saldo anterior</th>
                        <th>Importe pago</th>
                        <th>Saldo insoluto</th>
                        <th>No. Parcialidad</th>
                      </tr>
                    </thead>
                  </table>
                </div>
                <div class="col-sm-4">
                  <label for="usr" class="textblue"><b>Comentarios:</b></label>
                  <div id="usr">
                    <span id="txtComentarios"></span>
                  </div>
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
    require_once 'modal_invoiceNotFound.php';
    require_once 'modal_alert_deletePago.php';
    require_once 'modal_alert_cancelComplement.php';
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
  <script src="../../js/jquery.redirect.min.js"></script>
  <script src="js/scriptNotificaciones.js"></script>
  <script>
     var selectMotivoCancelacion = new SlimSelect({
      select: '#cmbMotivoCancela',
      deselectLabel: '<span class="">✖</span>'
    });
  </script>

</body>
</html>