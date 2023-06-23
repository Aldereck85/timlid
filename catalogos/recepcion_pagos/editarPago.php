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
  <title>Timlid | Editar pago</title>

  <!-- ESTILOS -->
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
  <link rel="stylesheet" href="../../css/notificaciones.css">
  <link href="css/signoPesos.css" rel="stylesheet">
  <link href="css/disabled.css" rel="stylesheet">
  <link href="css/editImputNumber.css" rel="stylesheet">
  <link href="css/pago.css" rel="stylesheet">

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
  <script src="js/data_editPagos.js"></script>
  <script src="js/eliminarPago.js"></script>
  <script src="js/validaInputs.js"></script>
  <script src="../../js/notificaciones_timlid.js" charset="utf-8"></script>
</head>

<!--verificacion de permisos para la pagina-->
<?php
require_once 'functions/function_Permisos.php';
?>

<body id="page-top" class="sidebar-toggled">
<div id=loader></div>


  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <?php
    $titulo = "Editar pago";
    $ruta = "../";
    $ruteEdit = $ruta . "central_notificaciones/";
    require_once '../menu3.php';
    ?>
    <!-- End of Sidebar -->
    <input type="hidden" id="txtUsuario" value="<?= $_SESSION['PKUsuario']; ?>">
    <input type="hidden" id="txtRuta" value="<?= $ruta; ?>">
    <input type="hidden" id="txtEdit" value="<?= $ruteEdit; ?>">

    <input type="hidden" id="idPago" value="<?php echo ($_REQUEST['folio']); ?>" />
    <input type="hidden" id="isSubstitution" value="<?php echo (isset($_REQUEST['sustitucion']) ? $_REQUEST['sustitucion'] : 0); ?>" />
    <input type="hidden" id="clienteId" />


    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <?php
        $rutatb = "../";
        $backIcon = true;
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
              <div class="form-group">
                <div class="row">
                  <div class="col-sm-3">
                    <div class="col-xl-12 col-lg-6 col-md col-sm col-xs">
                      <label for="usr">Clientes:*</label>
                      <input type="text" name="Cliente" id="Cliente" class="form-control disabled" value="">
                      <div class="invalid-feedback" id="invalid-cliente">gg</div>
                    </div>
                  </div>
                  <!-- <div class="col-sm-2">
                    <div class=""> 
                      <span id="div1" class="">
                        <br>
                        <button data-toggle="modal" data-target="#mod_agregarFacturas" type="button" class="btnesp first espCancelar btnCancelar " id="agregarPagoBTN"><span class="ajusteProyecto">Agregar Documentos</span></button>
                      </span>                   
                    </div>
                  </div> -->
                  <div class="col-sm-3">
                    <div class="col-xl-12 col-lg-6 col-md col-sm col-xs">                    
                      <span id="div4" class="inpt-forma-disabled">                  
                        <label for="usr">Forma de pago:*</label>
                        <select name="cmbFormasPago" id="cmbFormasPago" required="" onchange="validateSelects('cmbFormasPago','invalid-formasPago')">
                        <option disabled selected value="f">Seleccione una forma de pago</option>
                        </select>
                        <div class="invalid-feedback" id="invalid-formasPago">gg</div>
                      </span>
                    </div>
                  </div>
                  <div class="col-sm-3">
                    <div class="col-xl-8 col-lg-6 col-md col-sm col-xs"> 
                      <span id="div2" class="inpt-fecha-disabled">
                        <label for="usr">Fecha:*</label>
                        <input type="date" class="form-control" name="txtFecha" id="txtFecha" value="<?php echo (date('Y-m-d'));?>" max="<?php echo (date('Y-m-d'));?>" onchange="validateSelects('txtFecha','invalid-fecha')">
                        <div class="invalid-feedback" id="invalid-fecha">gg</div>
                      </span>
                    </div>
                  </div>
                  <div class="col-sm-3 textData">
                    <div class="col-xl-12 col-lg-6 col-md col-sm col-xs inpt-total-disabled">
                      <label for="txtTo" class="textBlue"><b>Total:</b></label>
                      <div id="txtTo">
                        $ <span name="txtTota" id="txtTotal">0</span>
                      </div>
                    </div>
                  </div>
                </div>
                <br>
                <div class="row">
                  <div class="col-sm-3">
                    <div class="col-xl-12 col-lg-6 col-md col-sm col-xs">
                      <label for="usr">Cuenta:*</label>
                      <select name="cmbCuenta" id="chosenCuenta" onchange="validateSelects('chosenCuenta','invalid-cuenta')">
                        <option disabled selected value="f">Selecciona una cuenta</option>
                      </select>
                      <div class="invalid-feedback" id="invalid-cuenta">gg</div>
                    </div>
                  </div>
                  <div class="col-sm-3">
                    <div class="col-xl-12 col-lg-6 col-md col-sm col-xs">
                      <label for="usr">Referencia:</label>
                      <input type="text" name="cmbReferencia" id="Referencia" class="form-control alphaNumeric-only" value=""  maxlength="1000" data-toggle="tooltip" data-placement="left" title data-original-title="">
                    </div>
                  </div>
                  <div class="col-sm-3"></div>  
                  <div class="col-sm-3 textData">
                    <div class="col-xl-12 col-lg-6 col-md col-sm col-xs">  
                      <span id="div3" class="inpt-metodo-disabled">                  
                        <label for="Tipago" class="textBlue"><b>Método de pago:</b></label>
                        <div id="Tipago">
                          <span name ="cmbTipo" id="cmbTipo"></span>
                        </div>
                      </span>
                    </div>
                  </div>
                </div>
                <br>
                <div class="row">
                  <div class="col-sm-4" id="DivCheckComplement">
                  
                  </div>
                  <div class="col-sm-8">
                    <div class="float-right">
                      <label for="usr" class="float-right">* Campos requeridos</label>
                    </div>
                  </div>
                </div>
                <br>
                <div class="table-responsive">
                  <table class="table" id="tblFacturasSelect" width="100%" cellspacing="0">
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
                        <th></th>
                      </tr>
                    </thead>
                  </table>
                </div>
                <div class="row" style="align-items:center">
                  <div class="col-sm-5">
                      <label for="usr">Comentarios:</label>
                      <textarea name="txtComentarios" id="txtComentarios" class="form-control alphaNumeric-only" maxlength="400" name="txtComentarios" rows="3" value=""></textarea>
                    </div>
                </div>
                <div class="modal-footer justify-content-rigth">
                  <button type="button" class="btn-custom btn-custom--border-blue float-right" id="btnEliminaPago" onclick="eliminaPago('<?php echo ($_REQUEST['folio']); ?>',2)" data-dismiss="modal"><span class="ajusteProyecto">Cancelar pago</span></button>
                  <button type="button" class="btn-custom btn-custom--blue float-right" name="btnAgregar" id="btnAgregar"><span class="ajusteProyecto">Guardar</span></button>
                </div>
              </div>
            </div>
            <?php
            require_once 'modal_AgregarFacturas.php';
            ?>
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
    require_once 'modal_alert_deletePago.php';
    require_once 'modal_invoiceNotFound.php';
    require_once 'modal_alert_noEdit.php';
    ?>

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
              <span aria-hidden="true">y</span>
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
    <script>
      var selectSucursal = new SlimSelect({
        select: '#chosenCuenta',
        deselectLabel: '<span class="">✖</span>'
      });
      var selectFormapago = new SlimSelect({
        select: '#cmbFormasPago',
        deselectLabel: '<span class="">✖</span>'
      });
    </script>

</body>

</html>