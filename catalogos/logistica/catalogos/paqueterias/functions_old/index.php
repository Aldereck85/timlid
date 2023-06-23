<?php
session_start();

if (isset($_SESSION["Usuario"])) {
    $ruta = "../";
    require_once $ruta . '../include/db-conn.php';
    $user = $_SESSION["Usuario"];
    $pkusuario = $_SESSION["PKUsuario"];

    $screen = 10;
    /*require_once $ruta . 'validarPermisoPantalla.php';
if ($permiso === 0) {
header("location:../dashboard.php");
}*/
} else {
    header("location:../dashboard.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <link rel="icon" type="image/png" href="../../img/header/bluTimlid.png">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Timlid | Paqueterias</title>

  <!-- Bootstrap core JavaScript-->
  <script src="../../vendor/jquery/jquery.min.js"></script>
  <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Page level plugins -->
  <script src="../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../vendor/datatables/dataTables.bootstrap4.min.js"></script>
  <script src="../../vendor/datatables/dataTables.buttons.js"></script>
  <script src="../../vendor/datatables/buttons.bootstrap4.min.js"></script>
  <script src="../../vendor/datatables/buttons.html5.min.js"></script>
  <script src="../../vendor/jszip/jszip.min.js"></script>
  <script src="../../js/lobibox.min.js"></script>

  <!-- Custom fonts for this template -->
  <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet">
  <!-- Custom styles for this page -->
  <link href="../../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
  <!-- Custom styles for this template -->
  <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../vendor/datatables/buttons.dataTables.css" rel="stylesheet">
  <link href="../../vendor/datatables/buttons.bootstrap4.min.css" rel="stylesheet">
  <link href="../../css/stylesTable.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
  <link href="../../css/lobibox.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../../css/notificaciones.css">
  <link href="../../css/slimselect.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../../css/sweetalert2.css">
  <link href="../../css/styles.css" rel="stylesheet">
</head>

<body id="page-top" class="sidebar-toggled">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <?php
$ruta = "../";
$ruteEdit = $ruta . "central_notificaciones/";
require_once $ruta . 'menu3.php';
?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
      <input type="hidden" id="txtUsuario" value="<?=$_SESSION['PKUsuario'];?>">
      <input type="hidden" id="txtRuta" value="<?=$ruta;?>">
      <input type="hidden" id="txtEdit" value="<?=$ruteEdit;?>">
      <input type="hidden" id="txtPantalla" value="<?=$screen;?>">
      <input type="hidden" id="emp_id" value="<?=$_SESSION['IDEmpresa'];?>">
      <!-- Main Content -->
      <div id="content">

        <?php
$rutatb = "../";
$icono = '../../img/icons/ICONO PAQUETERIAS-01.svg';
$titulo = "Paqueterias";
require_once '../topbar.php';
?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <!--<h1 class="h3 mb-2 text-gray-800">Paqueterias</h1>
          <p class="mb-4">Paqueterias de envios</p>
          -->

          <!-- DataTales Example -->
          <div class="card mb-4">
            <div class="card-header py-3">
              <div class="float-right">
                <div class="button-container2" id="btn_modal_agregar_paqueteria">
                  <div class="button-icon-container">
                    <a href="#" class="btn btn-circle float-right waves-effect waves-light shadow btn-table"><i
                        class="fas fa-plus"></i></a>
                  </div>
                  <div class="button-text-container">
                    <span>Agregar paquetería</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table" id="tblPaqueterias" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Id</th>
                      <th>Razón Social</th>
                      <th>Email</th>
                      <th>RFC</th>
                      <th>Calle</th>
                      <th>Num. exterior</th>
                      <th>Interior</th>
                      <th>Colonia</th>
                      <th>Municipio</th>
                      <th>Estado</th>
                      <th>Código Postal</th>
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

  <div class="modal fade right" id="agregar_paqueteria" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">

    <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
    <div class="modal-dialog modal-full-height modal-right modal-md" role="document">

      <div class="modal-content">
        <!--<input type="hidden" name="idProyectoA" value="">-->
        <div class="modal-header">
          <h4 class="modal-title w-100" id="myModalLabel">Agregar paquetería</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">x</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="form-paqueteria">
            <div class="form-group">
              <label for="usr">Razón social*:</label>
              <input type="text" id="txtRazonSocial" class="form-control alpha-only" maxlength="100"
                name="txtRazonSocial" onkeyup="validEmptyInput(this);" required>
              <div class="invalid-feedback" id="invalid-razonSocial">La razón social es obligatorio.</div>
            </div>
            <div class="form-group">
              <label for="usr">Nombre comercial*:</label>
              <input type="text" id="txtNombreComercial" class="form-control alpha-only" name="txtNombreComercial"
                maxlength="50" onkeyup="validEmptyInput(this);" required>
              <div class="invalid-feedback" id="invalid-nombreComercial">El nombre comercial es obligatorio.</div>
            </div>
            <div class="form-group">
              <label for="usr">Email:</label>
              <input type="text" id="txtEmail" class="form-control alphaNumeric-only" maxlength="50" name="txtEmail">
            </div>
            <div class="form-group">
              <label for="usr">RFC*:</label>
              <input type="text" id="txtRFC" class="form-control alphaNumeric-only" name="txtRFC" maxlength="13"
                onkeyup="validEmptyInput(this);" required>
              <div class="invalid-feedback" id="invalid-RFC">El RFC es obligatorio.</div>
            </div>
            <div class="form-group">
              <label for="usr">Calle*:</label>
              <input type="text" id="txtCalle" class="form-control alphaNumeric-only" maxlength="40" name="txtCalle"
                onkeyup="validEmptyInput(this);" required>
              <div class="invalid-feedback" id="invalid-calle">La calle es obligatorio.</div>
            </div>
            <div class="form-group">
              <label for="usr">Num. exterior*:</label>
              <input type="text" id="txtNumeroExterior" class="form-control alphaNumeric-only" size="40"
                name="txtNumeroExterior" maxlength="5" onkeyup="validEmptyInput(this);" required>
              <div class="invalid-feedback" id="invalid-noExterior">El número exterior es obligatorio.</div>
            </div>
            <div class="form-group">
              <label for="usr">Interior:</label>
              <input type="text" id="txtInterior" class="form-control alphaNumeric-only" size="40" maxlength="5"
                name="txtInterior">
            </div>
            <div class="form-group">
              <label for="usr">País*:</label>
              <select name="cmbPais" id="cmbPais" onchange="validEmptyInput(this);" required></select>
              <div class="invalid-feedback" id="invalid-pais">El pais es obligatorio.</div>
            </div>
            <div class="form-group">
              <label for="usr">Estado*:</label>
              <select name="cmbEstados" id="cmbEstados" onchange="validEmptyInput(this);" required></select>
              <div class="invalid-feedback" id="invalid-estado">El estado es obligatorio.</div>
            </div>
            <div class="form-group">
              <label for="usr">Municipio*:</label>
              <input type="text" id="txtMunicipio" class="form-control alphaNumeric-only" size="40" maxlength="40"
                name="txtMunicipio" onkeyup="validEmptyInput(this);" required>
              <div class="invalid-feedback" id="invalid-municipio">El municipio es obligatorio.</div>
            </div>
            <div class="form-group">
              <label for="usr">Código postal*:</label>
              <input type="text" id="txtCodigoPostal" class="form-control numeric-only" size="40" name="txtCodigoPostal"
                maxlength="5" onkeyup="validEmptyInput(this);" required>
              <div class="invalid-feedback" id="invalid-CP">El codigo postal es obligatorio.</div>
            </div>
            <div class="form-group">
              <label for="usr">Colonia*:</label>
              <input type="text" id="txtColonia" class="form-control alphaNumeric-only" size="40" maxlength="25"
                name="txtColonia" onkeyup="validEmptyInput(this);" required>
              <div class="invalid-feedback" id="invalid-colonia">La colonia es obligatorio.</div>
            </div>
            <div class="form-group">
              <label for="usr">Teléfono*:</label>
              <input type="text" id="txtTelefono" class="form-control numeric-only" size="40" name="txtTelefono"
                maxlength="10" onkeyup="validEmptyInput(this);" required>
              <div class="invalid-feedback" id="invalid-telefono">El teléfono es obligatorio.</div>
            </div>
          </form>
        </div>

        <div class="modal-footer justify-content-center">
          <button type="button" class="btn-custom btn-custom--border-blue float-right btnCancelarActualizacion"
            data-dismiss="modal" id="btnCancelarActualizacion"><span class="ajusteProyecto">Cancelar</span></button>
          <button type="button" class="btn-custom btn-custom--blue float-right" name="btn_agregar_paqueteria"
            id="btn_agregar_paqueteria"><span class="ajusteProyecto">Agregar</span></button>
        </div>
      </div>

    </div>
  </div>

  <div class="modal fade right" id="editar_paqueteria" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">

    <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
    <div class="modal-dialog modal-full-height modal-right modal-md" role="document">

      <div class="modal-content">
        <!--<input type="hidden" name="idProyectoA" value="">-->
        <div class="modal-header">
          <h4 class="modal-title w-100" id="myModalLabel">Editar paqueteria</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">x</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="form-paqueteria-edit">

            <input type="hidden" id="txtId" name="txtId">
            <div class="form-group">
              <label for="usr">Razón social*:</label>
              <input type="text" id="txtRazonSocialU" class="form-control alpha-only" maxlength="100"
                name="txtRazonSocialU" onkeyup="validEmptyInput(this);" required>
              <div class="invalid-feedback" id="invalid-razonSocialEdit">La razón social es obligatorio.</div>
            </div>
            <div class="form-group">
              <label for="usr">Nombre comercial*:</label>
              <input type="text" id="txtNombreComercialU" class="form-control alpha-only" name="txtNombreComercialU"
                maxlength="50" onkeyup="validEmptyInput(this);" required>
              <div class="invalid-feedback" id="invalid-nombreComEdit">El nombre comercial es obligatorio.</div>
            </div>
            <div class="form-group">
              <label for="usr">Email:</label>
              <input type="text" id="txtEmailU" class="form-control alphaNumeric-only" maxlength="50" name="txtEmailU">
            </div>
            <div class="form-group">
              <label for="usr">RFC*:</label>
              <input type="text" id="txtRFCU" class="form-control alphaNumeric-only" name="txtRFCU" maxlength="13"
                onkeyup="validEmptyInput(this);" required>
              <div class="invalid-feedback" id="invalid-RFCEdit">El RFC es obligatorio.</div>
            </div>
            <div class="form-group">
              <label for="usr">Calle*:</label>
              <input type="text" id="txtCalleU" class="form-control alphaNumeric-only" maxlength="40" name="txtCalleU"
                onkeyup="validEmptyInput(this);" required>
              <div class="invalid-feedback" id="invalid-calleEdit">La calle es obligatorio.</div>
            </div>
            <div class="form-group">
              <label for="usr">Num. exterior*:</label>
              <input type="text" id="txtNumeroExteriorU" class="form-control alphaNumeric-only" size="40"
                name="txtNumeroExteriorU" maxlength="5" onkeyup="validEmptyInput(this);" required>
              <div class="invalid-feedback" id="invalid-noExteriorEdit">El número exterior es obligatorio.</div>
            </div>
            <div class="form-group">
              <label for="usr">Interior:</label>
              <input type="text" id="txtInteriorU" class="form-control alphaNumeric-only" size="40" maxlength="5"
                name="txtInteriorU">
            </div>
            <div class="form-group">
              <label for="usr">País*:</label>
              <select name="cmbPaisU" id="cmbPaisU" onkeyup="validEmptyInput(this);" required></select>
              <div class="invalid-feedback" id="invalid-paisEdit">El pais es obligatorio.</div>
            </div>
            <div class="form-group">
              <label for="usr">Estado*:</label>
              <select name="cmbEstadosU" id="cmbEstadosU" onkeyup="validEmptyInput(this);" required></select>
              <div class="invalid-feedback" id="invalid-estadoEdit">El estado es obligatorio.</div>
            </div>
            <div class="form-group">
              <label for="usr">Municipio*:</label>
              <input type="text" id="txtMunicipioU" class="form-control alphaNumeric-only" size="40" maxlength="40"
                name="txtMunicipioU" onkeyup="validEmptyInput(this);" required>
              <div class="invalid-feedback" id="invalid-municipioEdit">El municipio es obligatorio.</div>
            </div>
            <div class="form-group">
              <label for="usr">Código postal*:</label>
              <input type="text" id="txtCodigoPostalU" class="form-control numeric-only" size="40"
                name="txtCodigoPostalU" maxlength="5" onkeyup="validEmptyInput(this);" required>
              <div class="invalid-feedback" id="invalid-CPEdit">El codigo postal es obligatorio.</div>
            </div>
            <div class="form-group">
              <label for="usr">Colonia*:</label>
              <input type="text" id="txtColoniaU" class="form-control alphaNumeric-only" size="40" maxlength="25"
                name="txtColoniaU" onkeyup="validEmptyInput(this);" required>
              <div class="invalid-feedback" id="invalid-coloniaEdit">La colonia es obligatorio.</div>
            </div>
            <div class="form-group">
              <label for="usr">Teléfono*:</label>
              <input type="text" id="txtTelefonoU" class="form-control numeric-only" size="40" name="txtTelefonoU"
                maxlength="10" onkeyup="validEmptyInput(this);" required>
              <div class="invalid-feedback" id="invalid-telefonoEdit">El teléfono es obligatorio.</div>
            </div>
          </form>
        </div>

        <div class="modal-footer justify-content-center">
          <button type="button" class="btn-custom btn-custom--border-blue float-right btnCancelarActualizacion"
            data-dismiss="modal" id="btnCancelarActualizacion"><span class="ajusteProyecto">Cancelar</span></button>
          <button type="button" class="btn-custom btn-custom--border-blue float-right" name="modal_eliminar_paqueteria"
            id="modal_eliminar_paqueteria" onclick="obtenerIdPaqueteriaEliminar();">
            <span class="ajusteProyecto">Eliminar
            </span>
          </button>
          <button type="button" class="btn-custom btn-custom--blue float-right" name="btn_editar_paqueteria"
            id="btn_editar_paqueteria">
            <span class="ajusteProyecto">Guardar
            </span>
          </button>
        </div>
      </div>

    </div>
  </div>


  <!-- Delete Modal mis paqueterias -->
  <div id="eliminar_Paqueteria" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">

        <input type="hidden" name="idPaqueteriaD" id="idPaqueteriaD">
        <div class="modal-header">
          <h4 class="modal-title">Eliminar paquetería</h4>
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        </div>
        <div class="modal-body">
          <p>¿Está seguro de realizar esta acción?</p>
          <p class="text-warning"><small>Esta acción es irreversible.</small></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn-custom btn-custom--border-blue btnCancelarActualizacion" data-dismiss="modal"
            id="btnCancelarActualizacion"><span class="ajusteProyecto">Cancelar</span></button>

          <button type="button" class="btn-custom btn-custom--blue float-right" name="btn_eliminar_paqueteria"
            id="btn_eliminar_paqueteria">
            <span class="ajusteProyecto">Eliminar
            </span>
          </button>
        </div>

      </div>
    </div>
  </div>

  <!-- Update Modal mis paqueterias -->
  <div id="editar_Paqueteria" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="functions/editar_Paqueteria.php" method="POST">
          <input type="hidden" name="idPaqueteriaU" id="idPaqueteriaU">
          <div class="modal-header">
            <h4 class="modal-title">Editar paqueteria</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
          </div>
          <div class="modal-body">
            <p>¿Está seguro de realizar esta acción?</p>
            <p class="text-warning"><small>Esta acción cambiará los datos del registro.</small></p>
          </div>
          <div class="modal-footer">
            <input type="button" class="btn-custom btn-custom--blue" data-dismiss="modal" value="Cancelar">
            <input type="submit" class="btn-custom btn-custom--border-blue" value="Editar">
          </div>
        </form>
      </div>
    </div>
  </div>

  <script src="../../js/sb-admin-2.min.js"></script>
  <script src="../../js/scripts.js"></script>
  <script src="../../js/notificaciones_timlid.js" charset="utf-8"></script>
  <script src="../../js/slimselect.min.js"></script>
  <script src="js/paqueterias.js"></script>
  <script src="../../js/sweet/sweetalert2.js"></script>

</body>

</html>