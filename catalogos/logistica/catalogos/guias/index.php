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
  <title>Timlid | Guías</title>

  <!-- ESTILOS -->
  <link href="../../../../css/slimselect.min.css" rel="stylesheet">
  <link href="../../../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../../../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../../../css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="../../../../vendor/datatables/buttons.dataTables.css" rel="stylesheet">
  <link href="../../../../css/styles.css" rel="stylesheet">
  <link href="../../style/guias.css" rel="stylesheet">
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
  <script src="../../js/guias.js" charset="utf-8"></script>

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
        $icono = '../../../../img/icons/ICONO GUIAS DE ENVIO-01.svg';
        $titulo = 'Guías';
        require_once $rutatb . 'topbar.php';
        ?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->

          <!-- DataTales Example -->
          <div class="card mb-4">
            <div class="card-header py-3" style="background:transparent;">
              <div class="float-right">
                <div class="float-right" id="btnAddPermissions" name="btnAddPermissions">

                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table" id="tblGuias" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Estatus</th>
                      <th>No.</th>
                      <th>Descripción</th>
                      <th>Tipo de pago</th>
                      <th>Paquetería</th>
                      <th>Acciones</th>
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

<!--ADD MODAL SLIDE EDIT CARGA COMBUSTIBLE-->
<div class="modal fade right" id="agregar_Guia" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
  <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">
    <div class="modal-content">
      <form action="" method="POST" id="formDatosGuia">
        <!--<input type="hidden" name="idProyectoA" value="">-->
        <div class="modal-header">
          <h4 class="modal-title w-100" id="myModalLabel">Agregar guía</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">x</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <div class="row">
              <div class="col-lg-9">
                <label for="usr" style="float:right!important;">Estatus:*</label>
              </div>
              <div class="col-lg-3">
                <div class="row">
                  <div class="col-lg-12 input-group">
                    <input type="checkbox" id="activeGuia" class="check-custom" checked>
                    <label class="shadow-sm check-custom-label" for="activeGuia">
                      <div class="circle"></div>
                      <div class="check-inactivo">Inactivo</div>
                      <div class="check-activo">Activo</div>
                    </label>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <div class="col-lg-12">
                <label for="usr">Número:*</label>
                <div class="row">
                  <div class="col-lg-12 input-group">
                    <input class="form-control" type="text" name="txtNumero" id="txtNumero" autofocus="" required maxlength="50" placeholder="Número de la guía" onchange="validEmptyInput('txtNumero', 'invalid-numero', 'La guía debe tener un número.')">
                    <div class="invalid-feedback" id="invalid-numero">La guía debe tener un número.</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <div class="col-lg-12">
                <label for="usr">Descripción:*</label>
                <div class="row">
                  <div class="col-lg-12 input-group">
                    <input class="form-control" type="text" name="txtDescripcion" id="txtDescripcion" autofocus="" required maxlength="255" placeholder="Descripción de la guía" onchange="validEmptyInput('txtDescripcion', 'invalid-descripcion', 'La guía debe tener una descripción.')">
                    <div class="invalid-feedback" id="invalid-descripcion">La guía debe tener una descripción.</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <div class="col-lg-12">
                <label for="usr">Tipo de pago:*</label>
                <div class="row">
                  <div class="col-lg-12 input-group">
                    <select name="cmbTipoPago" id="cmbTipoPago" required>
                    </select>
                    <div class="invalid-feedback" id="invalid-tipoPago">La guía debe tener un tipo de pago.</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <div class="col-lg-12">
                <label for="usr">Paquetería:*</label>
                <div class="row">
                  <div class="col-lg-12 input-group">
                    <select name="cmbPaqueteria" id="cmbPaqueteria" required>
                    </select>
                    <div class="invalid-feedback" id="invalid-paqueteria">La guía debe tener una pauqetería.</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <p>* Campos requeridos</p>

        </div>
        <div class="modal-footer justify-content-center">
          <button type="button" class="btn-custom btn-custom--border-blue" data-dismiss="modal" id="">
            Cancelar
          </button>
          <button type="button" class="btn-custom btn-custom--blue" id="btnAgregarGuia">Agregar guía
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
<!--END ADD MODAL SLIDE EDIT CARGA COMBUSTIBLE-->

<!--EDIT MODAL SLIDE EDIT GUIA-->
<div class="modal fade right" id="editar_Guia" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
  <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">
    <div class="modal-content">
      <form action="" method="POST" id="formDatosGuiaEdit">
        <!--<input type="hidden" name="idProyectoA" value="">-->
        <div class="modal-header">
          <h4 class="modal-title w-100" id="myModalLabel">Editar guía</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">x</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <div class="row">
              <div class="col-lg-9">
                <label for="usr" style="float:right!important;">Estatus:*</label>
              </div>
              <div class="col-lg-3">
                <div class="row">
                  <div class="col-lg-12 input-group">
                    <input type="checkbox" id="activeGuiaEdit" class="check-custom" checked>
                    <label class="shadow-sm check-custom-label" for="activeGuiaEdit">
                      <div class="circle"></div>
                      <div class="check-inactivo">Inactivo</div>
                      <div class="check-activo">Activo</div>
                    </label>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <div class="col-lg-12">
                <label for="usr">Número:*</label>
                <div class="row">
                  <div class="col-lg-12 input-group">
                    <input class="form-control" type="text" name="txtNumeroEdit" id="txtNumeroEdit" autofocus="" required maxlength="50" placeholder="Número de la guía" onchange="validEmptyInput('txtNumeroEdit', 'invalid-numeroEdit', 'La guía debe tener un número.')">
                    <div class="invalid-feedback" id="invalid-numeroEdit">La guía debe tener un número.</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <div class="col-lg-12">
                <label for="usr">Descripción:*</label>
                <div class="row">
                  <div class="col-lg-12 input-group">
                    <input class="form-control" type="text" name="txtDescripcionEdit" id="txtDescripcionEdit" autofocus="" required maxlength="255" placeholder="Descripción de la guía" onchange="validEmptyInput('txtDescripcionEdit', 'invalid-descripcionEdit', 'La guía debe tener una descripción.')">
                    <div class="invalid-feedback" id="invalid-descripcionEdit">La guía debe tener una descripción.</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <div class="col-lg-12">
                <label for="usr">Tipo de pago:*</label>
                <div class="row">
                  <div class="col-lg-12 input-group">
                    <select name="cmbTipoPagoEdit" id="cmbTipoPagoEdit" required>
                    </select>
                    <div class="invalid-feedback" id="invalid-tipoPagoEdit">La guía debe tener un tipo de pago.</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <div class="col-lg-12">
                <label for="usr">Paquetería:*</label>
                <div class="row">
                  <div class="col-lg-12 input-group">
                    <select name="cmbPaqueteriaEdit" id="cmbPaqueteriaEdit" required>
                    </select>
                    <div class="invalid-feedback" id="invalid-paqueteriaEdit">La guía debe tener una pauqetería.</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <p>* Campos requeridos</p>

        </div>
        <div class="modal-footer justify-content-center">
          <button type="button" class="btn-custom btn-custom--blue" data-dismiss="modal" data-toggle="modal" data-target="#eliminar_Guia">Eliminar
          </button>
          <button type="button" class="btn-custom btn-custom--border-blue" data-dismiss="modal" id="">
            Cancelar
          </button>
          <button type="button" class="btn-custom btn-custom--blue" id="btnEditarGuia">Guardar
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
<!--END EDIT MODAL SLIDE EDIT GUIA-->

<!--DELETE MODAL SLIDE GUIA-->
<div class="modal fade" id="eliminar_Guia" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="" method="POST">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">¿Desea inactivar el registro de este?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true" class="text-light">x</span>
          </button>
        </div>
        <div class="modal-body" style="font-size: 10 px!important; color: red;"></div>
        <div class="modal-footer">
          <button class="btn-custom btn-custom--border-blue btnCancelarActualizacion" type="button" data-dismiss="modal"><span class="ajusteProyecto">Cancelar</span></button>
          <button type="button" onclick="obtenerEliminarGuia()" data-dismiss="modal" class="btn-custom btn-custom--blue">
            <span class="ajusteProyecto">Inactivar</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
<!--END DELETE MODAL SLIDE GUIA-->