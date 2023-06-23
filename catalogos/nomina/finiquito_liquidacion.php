<?php
session_start();

$jwt_ruta = "../../";
require_once '../jwt.php';

date_default_timezone_set('America/Mexico_City');

$token = $_SESSION['token_ld10d'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="icon" type="image/png" href="../../img/header/bluTimlid.png">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Timlid | Finiquito/Liquidación</title>

  <!-- ESTILOS -->
  <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="../../vendor/datatables/buttons.dataTables.css" rel="stylesheet">
  <link href="../../css/slimselect.min.css" rel="stylesheet">
  <link href="../../css/styles.css" rel="stylesheet">
  <link href="../../css/stylesNewTable.css" rel="stylesheet">
  <link href="../../css/stylesModal-lg.css" rel="stylesheet">
  <link href="../../css/lobibox.min.css" rel="stylesheet">
  <link href="../../css/notificaciones.css" rel="stylesheet">
  <!-- JS -->
  <script src="../../vendor/jquery/jquery.min.js"></script>
  <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../vendor/datatables/dataTables.buttons.js"></script>
  <script src="../../vendor/datatables/buttons.html5.min.js"></script>
  <script src="../../vendor/jszip/jszip.min.js"></script>
  <script src="../../js/notificaciones_timlid.js" charset="utf-8"></script>
  <script src="../../js/slimselect.min.js"></script>
  <script src="../../js/lobibox.min.js"></script>
  <script src="../../js/sweet/sweetalert2.js"></script>
  <script src="../../js/jquery.redirect.min.js"></script>
</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <?php
    $icono = '../../img/icons/puestos.svg';
    $titulo = "Finiquito/Liquidación";
    $ruta = "../";
    $ruteEdit = $ruta . "central_notificaciones/";
    require_once '../menu3.php';
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
        $rutatb = "../";
        require_once "../topbar.php"
        ?>

        <!-- Begin Page Content -->
        <div class="container-fluid">
          <!-- Page Heading -->
          <!-- DataTales Example -->
          <div class="card mb-4 data-table">
            <!-- <div class="card-header py-3">
              <div class="float-right">
                <div class="button-container2" id="">
                  <div class="button-icon-container">
                    <a href="#" class="btn btn-circle float-right waves-effect waves-light shadow btn-table" id="btn-nomina" data-toggle="modal" data-target="#agregar_nomina"><i class="fas fa-plus"></i></a>
                  </div>
                  <div class="button-text-container">
                    <span>Agregar nómina</span>
                  </div>
                </div>
              </div>
            </div> -->
            <div class="card-body">
              <div class="table-responsive">
                <table class="table" id="tblFiniquito" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Empleado</th>
                      <th>Fecha ingreso</th>
                      <th>Fecha salida</th>
                      <th>Tipo</th>
                      <th>Total pagado</th>
                      <th>Fecha generación</th>
                      <th>Estatus</th>
                      <th></th>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>
          </div>
        </div>
        <!-- End of Main Content -->
      </div>
      <!-- End of Content Wrapper -->
      <!-- Footer -->
      <?php
      $rutaf = "../";
      require_once '../footer.php';
      ?>
      <!-- End of Footer -->
    </div>
    <!-- End of Page Wrapper -->
  </div>

  <!--ADD MODAL-->
  <div class="modal fade right" id="agregar_nomina" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
    <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">
      <div class="modal-content">
        <form action="#" method="POST" id="agregarNomina">
          <!--<input type="hidden" name="idProyectoA" value="">-->
          <div class="modal-header">
            <h4 class="modal-title w-100" id="myModalLabel">Agregar nómina</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">x</span>
            </button>
          </div>
          <div class="modal-body">

            <div class="form-group">
              <label for="usr">Sucursal*:</label>
              <select class="form-control" name="cmbSucursal" id="cmbSucursal" required>
                <option value="" disabled selected hidden>Seleccione una opcion...</option>
                <?php
                $stmt = $conn->prepare("SELECT id, sucursal FROM sucursales WHERE estatus = 1 AND empresa_id = " . $_SESSION['IDEmpresa']);
                $stmt->execute();
                $rowS = $stmt->fetchAll();

                if (count($rowS) > 0) {
                  foreach ($rowS as $r) { ?>
                    <option value="<?= $r['id'] ?>"><?= $r['sucursal'] ?></option>
                  <?php }
                } else { ?>
                  <option value="" disabled>No hay sucursales para mostrar.</option>
                <?php } ?>
              </select>
            </div>

            <div class="form-group">
              <label for="usr">Periodo*:</label>
              <select class="form-control" name="cmbPeriodo" id="cmbPeriodo" required>
                <option value="" disabled selected hidden>Seleccione una opcion...</option>
                <?php
                $stmt = $conn->prepare("SELECT PKPeriodo_pago, Periodo FROM periodo_pago");
                $stmt->execute();
                $rowP = $stmt->fetchAll();

                if (count($rowP) > 0) {
                  foreach ($rowP as $r) { ?>
                    <option value="<?= $r['PKPeriodo_pago'] ?>"><?= $r['Periodo'] ?></option>
                  <?php }
                } else { ?>
                  <option value="" disabled>No hay periodos para mostrar.</option>
                <?php } ?>
              </select>
            </div>

            <div class="form-group">
              <label for="usr">Tipo*:</label>
              <select class="form-control" name="cmbTipo" id="cmbTipo" required>
                <option value="" disabled selected hidden>Seleccione una opcion...</option>
                <?php
                $stmt = $conn->prepare("SELECT id, tipo FROM tipo_nomina");
                $stmt->execute();
                $rowT = $stmt->fetchAll();

                if (count($rowT) > 0) {
                  foreach ($rowT as $r) { ?>
                    <option value="<?= $r['id'] ?>"><?= $r['tipo'] ?></option>
                  <?php }
                } else { ?>
                  <option value="" disabled>No hay tipos de nómina para mostrar.</option>
                <?php } ?>
              </select>
            </div>

            <div class="form-group">
              <label for="usr">Fecha de pago*:</label>
              <input type="date" class="form-control" name="txtFechaPago" id="txtFechaPago" value="<?= date("Y-m-d") ?>" required>
            </div>
            <div class="form-group">
              <label for="usr">Fecha de inicio*:</label>
              <input type="date" class="form-control" name="txtFechaInicio" id="txtFechaInicio" value="" required>
            </div>
            <div class="form-group">
              <label for="usr">Fecha final*:</label>
              <input type="date" class="form-control" name="txtFechaFin" id="txtFechaFin" value="" required>
            </div>
            <label style="color:#006dd9;font-size: 13px;"> (*) Campos requeridos</label>
            <div class="modal-footer justify-content-center">
              <button type="button" class="btn-custom btn-custom--border-blue btnCancelarActualizacion" data-dismiss="modal" id="btnCancelarNomina"><span class="ajusteProyecto">Cancelar</span></button>
              <button type="button" class="btn-custom btn-custom--blue" name="btnAgregar" id="btnAgregarNomina"><span class="ajusteProyecto">Agregar</span></button>
            </div>
        </form>
      </div>
    </div>
  </div>
  </div>
  <!--END ADD MODAL-->


  <!--EDIT MODAL-->
  <div class="modal fade right" id="editar_nomina" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
    <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">
      <div class="modal-content">
        <form action="#" method="POST" id="editarNomina">
          <!--<input type="hidden" name="idProyectoA" value="">-->
          <div class="modal-header">
            <h4 class="modal-title w-100" id="myModalLabel">Editar nómina</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">x</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label for="usr">No. nómina:</label>
              <input type="text" class="form-control" name="txtNoNominaedit" id="txtNoNominaedit" value="" disabled>
            </div>
            <div class="form-group">
              <label for="usr">Sucursal*:</label>
              <select class="form-control" name="cmbSucursalEdit" id="cmbSucursalEdit" required>
              </select>
            </div>

            <div class="form-group">
              <label for="usr">Periodo*:</label>
              <select class="form-control" name="cmbPeriodoEdit" id="cmbPeriodoEdit" required>
              </select>
            </div>

            <div class="form-group">
              <label for="usr">Tipo*:</label>
              <select class="form-control" name="cmbTipoEdit" id="cmbTipoEdit" required>
              </select>
            </div>

            <div class="form-group">
              <label for="usr">Fecha de pago*:</label>
              <input type="date" class="form-control" name="txtFechaPagoEdit" id="txtFechaPagoEdit" value="" required>
            </div>
            <div class="form-group">
              <label for="usr">Fecha de inicio*:</label>
              <input type="date" class="form-control" name="txtFechaInicioEdit" id="txtFechaInicioEdit" value="" required>
            </div>
            <div class="form-group">
              <label for="usr">Fecha final*:</label>
              <input type="date" class="form-control" name="txtFechaFinEdit" id="txtFechaFinEdit" value="" required>
            </div>
            <label style="color:#006dd9;font-size: 13px;"> (*) Campos requeridos</label>
            <div class="modal-footer justify-content-center">
              <button type="button" class="btn-custom btn-custom--border-blue btnCancelarActualizacion" data-dismiss="modal" id="btnCancelarNominaEdit"><span class="ajusteProyecto">Cancelar</span></button>
              <button type="button" class="btn-custom btn-custom--blue" name="btnEditar" id="btnEditarNomina"><span class="ajusteProyecto">Modificar</span></button>
            </div>
        </form>
      </div>
    </div>
  </div>
  </div>
  <!--END EDIT MODAL-->

  <input type="hidden" name="csr_token_UT5JP" id="csr_token_UT5JP" value="<?= $token ?>">

  <!-- Custom scripts for all pages-->
  <script src="../../js/sb-admin-2.min.js"></script>
  <script src="../../js/scripts.js"></script>
  <script src="./js/finiquito_liquidacion.js"></script>

  <script>
    function obtenerVer(idFiniquito) {

      $().redirect("mostrar_finiquito_liquidacion.php", {
        'idFiniquito': idFiniquito
      });
    }
  </script>
  <script>
    var ruta = "../";
  </script>
  <script>
    loadAlertsNoti('alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
    setInterval(loadAlertsNoti, 10000, 'alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
  </script>
</body>

</html>