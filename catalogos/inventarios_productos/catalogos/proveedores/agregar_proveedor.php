<?php
session_start();
require_once '../../../../include/db-conn.php';
$user = $_SESSION["Usuario"];

if (isset($_SESSION["Usuario"])) {
    require_once '../../../../include/db-conn.php';
    $user = $_SESSION["Usuario"];
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
  <title>Timlid | Agregar proveedor</title>

  <!-- ESTILOS -->
  <link href="../../../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../../../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../../../css/slimselect.min.css" rel="stylesheet">
  <link href="../../style/agregar_entrada.css" rel="stylesheet">
  <link href="../../style/pestanas_proveedores.css" rel="stylesheet">
  <link href="../../../../css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="../../../../vendor/datatables/responsive.dataTables.css" rel="stylesheet">
  <link href="../../../../vendor/datatables/buttons.dataTables.css" rel="stylesheet">
  <link href="../../../../css/stylesNewTable.css" rel="stylesheet">
  <link href="../../../../css/styles.css" rel="stylesheet">
  <link href="../../../../css/croppie.css" rel="stylesheet">
  <link href="../../../../css/lobibox.min.css" rel="stylesheet">
  <link href="../../../../css/sweetalert2.css" rel="stylesheet">
  <link href="../../../../css/notificaciones.css" rel="stylesheet">
  <!-- JS -->
  <script src="../../../../vendor/jquery/jquery.min.js"></script>
  <script src="../../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../../../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../../../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../../../vendor/datatables/dataTables.responsive.js"></script>
  <script src="../../../../vendor/datatables/dataTables.buttons.js"></script>
  <script src="../../../../vendor/datatables/buttons.html5.min.js"></script>
  <script src="../../../../vendor/jszip/jszip.min.js"></script>
  <script src="../../../../js/lobibox.min.js"></script>
  <script src="../../../../js/numeral.min.js" charset="utf-8"></script>
  <script src="../../../../js/croppie.js"></script>
  <script src="../../../../js/sweet/sweetalert2.js"></script>
</head>

<body id="page-top">
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

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <?php
$rutatb = "../../../";
$icono = 'ICONO-PROVEEDORES-AZUL.svg';
$titulo = 'Agregar Proveedores';
$backIcon = true;
require_once $rutatb . 'topbar.php';
?>
        <!-- End of Topbar -->
        <input type="hidden" id="txtUsuario" value="<?=$_SESSION['PKUsuario'];?>">
        <input type="hidden" id="txtRuta" value="<?=$ruta;?>">
        <input type="hidden" id="txtEdit" value="<?=$ruteEdit;?>">
        .

        <!-- Begin Page Content -->
        <div class="container-fluid">
          <div class="row">
            <div class="col-lg-12">
              <ul class="nav nav-tabs">
                <li class="nav-item">
                  <a id="CargarDatosProveedor" class="nav-link nav-link--add" href="#">
                    Datos del proveedor
                  </a>
                </li>
                <li class="nav-item pestanas_proveedor">
                  <a id="CargarDatosFiscal" class="nav-link nav-link--add">
                    Información fiscal
                  </a>
                </li>
                <li class="nav-item">
                  <a id="CargarDatosContacto" class="nav-link nav-link--add">
                    Contacto
                  </a>
                </li>
                <li class="nav-item">
                  <a id="CargarDatosCuentasBancarias" class="nav-link nav-link--add">
                    Cuentas bancarias
                  </a>
                </li>
                <li class="nav-item">
                  <a id="CargarDireccionesEnvio" class="nav-link nav-link--add">
                    Productos
                  </a>
                </li>
              </ul>
              <input id="PKUsuario" value="<?php echo $_SESSION["PKUsuario"]; ?>" type="hidden">
              <!-- Basic Card Example -->
              <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="datos" role="tabpanel" aria-labelledby="nav-main-tab">
                </div>
              </div>
            </div>
          </div>

          <!-- End Begin Page Content -->

        </div>
        <!-- End Main Content -->

        <!-- Footer -->
        <?php
$rutaf = "../../../";
require_once $rutaf . 'footer.php';
?>

      </div>
      <!-- End Content Wrapper -->

    </div>
    <!-- End Page Wrapper -->



    <!--ADD MODAL SLIDE EDIT INFO FISCAL-->
    <div class="modal fade right" id="editar_InfoFiscal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
      aria-hidden="true">
      <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
      <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">
        <div class="modal-content">
          <form id="info-fiscal-edit">
            <!--<input type="hidden" name="idProyectoA" value="">-->
            <div class="modal-header">
              <h4 class="modal-title w-100" id="myModalLabel">Editar Información Fiscal</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">x</span>
              </button>
              <input type="hidden" id="txtPkRazonSocialProveedor" value="0">
            </div>
            <div class="modal-body">
              <div class="form-group">
                <!-- DataTales Example -->
                <div class="card mb-4">
                  <div class="">

                    <div class="form-group">
                      <div class="row">
                        <div class="col-lg-12">
                          <label for="usr">Razón Social:*</label>
                          <div class="row">
                            <div class="col-lg-12 input-group">
                              <input class="form-control alphaNumeric-only" type="text" name="txtRazonSocialEdit"
                                id="txtRazonSocialEdit" required="" maxlength="100"
                                placeholder="Ej. GH Medic S.A. de C.V."
                                onkeyup="escribirRazonSocialEditModal($('#txtPKProveedor').val())">
                              <div class="invalid-feedback" id="invalid-razonSocEdit">El proveedor debe tener una razón
                                social.</div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="row">
                        <div class="col-lg-12">
                          <label for="usr">RFC:*</label>
                          <div class="row">
                            <div class="col-lg-12 input-group">
                              <input class="form-control alphaNumeric-only" type="text" name="txtRFCEdit"
                                id="txtRFCEdit" required="" maxlength="13" placeholder="Ej. GHMM100101AA1"
                                onkeyup="validarInputEditModal($('#txtPKProveedor').val())"
                                oninput="let p=this.selectionStart;this.value=this.value.toUpperCase();this.setSelectionRange(p, p);">
                              <div class="invalid-feedback" id="invalid-rfcEdit">El proveedor debe tener un RFC.</div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="row">
                        <div class="col-lg-12">
                          <label for="usr">Calle:</label>
                          <div class="row">
                            <div class="col-lg-12 input-group">
                              <input class="form-control alphaNumeric-only" type="text" name="txtCalleEdit"
                                id="txtCalleEdit" maxlength="255" placeholder="Ej. Av. México">

                              <img id="notaFCalleEdit" name="notaFCalleEdit" style="display: none;"
                                src="../../../../img/timdesk/alerta.svg" width=30px title="Campo requerido" readonly>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="row">
                        <div class="col-lg-6">
                          <label for="usr">Número exterior:</label>
                          <div class="row">
                            <div class="col-lg-12 input-group">
                              <input class="form-control alphaNumeric-only" type="text" name="txtNumExtEdit"
                                id="txtNumExtEdit" maxlength="10" placeholder="Ej. 2353 A">
                            </div>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <label for="usr">Número interior:</label>
                          <div class="row">
                            <div class="col-lg-12 input-group">
                              <input class="form-control alphaNumeric-only" type="text" name="txtNumIntEdit"
                                id="txtNumIntEdit" maxlength="10" placeholder="Ej. 524">
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="row">
                        <div class="col-lg-6">
                          <label for="usr">Colonia:</label>
                          <div class="row">
                            <div class="col-lg-12 input-group">
                              <input class="form-control alphaNumeric-only" type="text" name="txtColoniaEdit"
                                id="txtColoniaEdit" maxlength="255" placeholder="Ej. Los Agaves">
                            </div>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <label for="usr">Municipio:</label>
                          <div class="row">
                            <div class="col-lg-12 input-group">
                              <input class="form-control alphaNumeric-only" type="text" name="txtMunicipioEdit"
                                id="txtMunicipioEdit" maxlength="255" placeholder="Ej. Guadalajara">
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="row">
                        <div class="col-lg-6">
                          <label for="usr">País:*</label>
                          <div class="row">
                            <div class="col-lg-12 input-group">
                              <select name="cmbPaisEdit" id="cmbPaisEdit" onchange="cambioPaisEditModal()" required>
                              </select>
                              <div class="invalid-feedback" id="invalid-paisEdit">El proveedor debe tener un pais.</div>
                            </div>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <label for="usr">Estado:*</label>
                          <div class="row">
                            <div class="col-lg-12 input-group">
                              <select name="cmbEstadoEdit" id="cmbEstadoEdit" onchange="validEmptyInput(this)" required>
                              </select>
                              <div class="invalid-feedback" id="invalid-estado">El proveedor debe tener un estado.</div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="row">
                        <div class="col-lg-6">
                          <label for="usr">Código Postal:*</label>
                          <div class="row">
                            <div class="col-lg-12 input-group">
                              <input class="form-control numeric-only" type="text" name="txtCPEdit" id="txtCPEdit"
                                autofocus="" required="" maxlength="5"
                                oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                placeholder="Ej. 52632" onkeyup="validarCPEditModal();">
                              <div class="invalid-feedback" id="invalid-cpEdit">El proveedor debe tener un CP.</div>
                            </div>
                          </div>
                        </div>
                        <div class="col-lg-6">
                              <label for="usr">Localidad:</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control" type="text" name="txtLocalidadEdit" id="txtLocalidadEdit" maxlength="255" placeholder="Ej. Camichines">
                                </div>
                              </div>
                            </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="row">
                        <div class="col-lg-6">
                          <label for="usr">Referencia:</label>
                          <div class="row">
                            <div class="col-lg-12 input-group">
                              <input class="form-control" type="text" name="txtReferenciaEdit" id="txtReferenciaEdit" maxlength="255" placeholder="Ej. Contacto camichines">
                            </div>
                          </div>
                        </div>
                        <div class="col-lg-6">

                        </div>
                      </div>
                    </div>


                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer justify-content-center">
              <button type="button" class="btnesp espAgregar float-right"><span class="ajusteProyecto"
                  data-dismiss="modal" data-toggle="modal" data-target="#eliminar_InfoFiscal">Eliminar</span></button>
              <button type="button" class="btnesp first espCancelar" data-dismiss="modal" id="BtnSocial"><span
                  class="ajusteProyecto">Cancelar</span></button>
              <button type="button" class="btnesp espAgregar float-right"
                onclick="anadirRazonSocialEdit($('#txtPkRazonSocialProveedor').val())"><span
                  class="ajusteProyecto">Guardar
                  cambios</span></button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!--END ADD MODAL SLIDE EDIT INFO FISCAL-->

    <!--DELETE MODAL SLIDE INFO FISCAL-->
    <div class="modal fade" id="eliminar_InfoFiscal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
      aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">

          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">¿Desea eliminar el registro de este?</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">x</span>
            </button>
          </div>
          <div>
            <br>
            <label for="usr" style="margin-left: 80px!important;">Se eliminará la razón social con los siguientes
              datos:</label>
          </div>
          <div class="row" style="margin-left: 80px!important;">
            <div class="form-group col-md-2">
              <label for="usr">Razón Social:</label>
            </div>
            <div class="form-group col-md-10">
              <input type="text" style="border:none!important; background-color: transparent!important;"
                class="form-control" maxlength="100" id="txtNombreD" name="txtNombreD" placeholder="" required readonly>
            </div>
          </div>
          <div class="modal-body" style="font-size: 10 px!important; color: red;">Esta acción no podrá deshacerse</div>
          <div class="modal-footer">
            <button class="btnesp first espCancelar btnCancelarActualizacion" type="button" data-dismiss="modal"
              id="btnCancelarInfoFiscal"><span class="ajusteProyecto">Cancelar</span></button>
            <button type="submit" class="btnesp espAgregar float-right" data-dismiss="modal"><span
                class="ajusteProyecto"
                onclick="obtenerIdRazonSocialProveedorEliminar($('#txtPkRazonSocialProveedor').val())">Eliminar</span></button>
          </div>

        </div>
      </div>
    </div>
    <!--END DELETE MODAL SLIDE INFO FISCAL-->

    <!--ADD MODAL SLIDE EDIT CONTACTO-->
    <div class="modal fade right" id="editar_Contacto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
      aria-hidden="true">
      <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
      <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">
        <div class="modal-content">
          <form id="form-contacto-edit">
            <!--<input type="hidden" name="idProyectoA" value="">-->
            <div class="modal-header">
              <h4 class="modal-title w-100" id="myModalLabel">Editar Contacto</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">x</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <!-- DataTales Example -->
                <div class="card mb-4">
                  <div class="">

                    <div class="form-group">
                      <div class="row">
                        <div class="col-lg-6">
                          <label for="usr">Nombre(s) del contacto:*</label>
                          <div class="row">
                            <div class="col-lg-12 input-group">
                              <input class="form-control alphaNumeric-only" type="text" name="txtNombreContactoEdit"
                                id="txtNombreContactoEdit" required="" maxlength="50" placeholder="Ej. José María"
                                onkeyup="validEmptyInput(this)">
                              <div class="invalid-feedback" id="invalid-nombreContEdit">El cliente debe tener un
                                nombre.</div>
                            </div>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <label for="usr">Apellido(s) del contacto:</label>
                          <div class="row">
                            <div class="col-lg-12 input-group">
                              <input type="text" maxlength="50" class="form-control alphaNumeric-only"
                                name="txtApellidoContactoEdit" id="txtApellidoContactoEdit"
                                placeholder="Ej. López Pérez">
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="row">
                        <div class="col-lg-6">
                          <label for="usr">Puesto:</label>
                          <div class="row">
                            <div class="col-lg-12 input-group">
                              <input type="text" class="form-control alphaNumeric-only" name="txtPuestoEdit"
                                id="txtPuestoEdit" maxlength="50" placeholder="Ej. Gerente de ventas">
                            </div>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <label for="usr">Teléfono fijo:</label>
                          <div class="row">
                            <div class="col-lg-12 input-group">
                              <input class="form-control numeric-only" type="text" name="txtTelefonoEdit"
                                id="txtTelefonoEdit" minlength="7" maxlength="10" placeholder="Ej. 33 3333 3333"
                                oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);">
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="row">
                        <div class="col-lg-6">
                          <label for="usr">Celular:</label>
                          <div class="row">
                            <div class="col-lg-12 input-group">
                              <input class="form-control numeric-only" type="text" name="txtCelularEdit"
                                id="txtCelularEdit" minlength="10" maxlength="10"
                                oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                placeholder="Ej. 33 3333 3333">
                            </div>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <label for="usr">E-mail:*</label>
                          <div class="row">
                            <div class="col-lg-12 input-group">
                              <input type="hidden" id="email-old">
                              <input class="form-control" type="email" name="txtEmailEdit" id="txtEmailEdit" required=""
                                maxlength="100" placeholder="Ej. ejemplo@dominio.com"
                                onkeyup="validarCorreoContactoModalEdit(this.value)">
                              <div class="invalid-feedback" id="invalid-emailContEdit">El contacto debe tener un email.
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer justify-content-center">
              <button type="button" class="btnesp espAgregar float-right"><span class="ajusteProyecto"
                  data-dismiss="modal" data-toggle="modal" data-target="#eliminar_Contacto">Eliminar</span></button>
              <button type="button" class="btnesp first espCancelar" data-dismiss="modal" id="BtnCon"><span
                  class="ajusteProyecto">Cancelar</span></button>
              <button type="button" class="btnesp espAgregar float-right"
                onclick="validarContactoEdit($('#txtPKContacto').val())"><span class="ajusteProyecto">Guardar
                  cambios</span></button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!--END ADD MODAL SLIDE EDIT CONTACTO-->

    <!--DELETE MODAL SLIDE CONTACTO-->
    <div class="modal fade" id="eliminar_Contacto" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
      aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">

          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">¿Desea eliminar el registro de este?</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">x</span>
            </button>
          </div>
          <div>
            <br>
            <label for="usr" style="margin-left: 80px!important;">Se eliminará el contacto con los siguientes
              datos:</label>
          </div>
          <div class="row" style="margin-left: 80px!important;">
            <div class="form-group col-md-2">
              <label for="usr">Contacto:</label>
            </div>
            <div class="form-group col-md-10">
              <input type="text" style="border:none!important; background-color: transparent!important;"
                class="form-control" maxlength="50" id="txtNombreDContacto" name="txtNombreDContacto" placeholder=""
                required readonly>
            </div>
          </div>
          <div class="modal-body" style="font-size: 10 px!important; color: red;">Esta acción no podrá deshacerse</div>
          <div class="modal-footer">
            <button class="btnesp first espCancelar btnCancelarActualizacion" type="button" data-dismiss="modal"
              id="btnCancelarContacto"><span class="ajusteProyecto">Cancelar</span></button>
            <button type="submit" class="btnesp espAgregar float-right" data-dismiss="modal"><span
                class="ajusteProyecto"
                onclick="obtenerIdContactoProveedorEliminar($('#txtPKContacto').val())">Eliminar</span></button>
          </div>

        </div>
      </div>
    </div>
    <!--END DELETE MODAL SLIDE CONTACTO-->

    <!--ADD MODAL SLIDE EDIT CUENTAS BANCARIAS -->
    <div class="modal fade right" id="editar_CuentaBancancaria" tabindex="-1" role="dialog"
      aria-labelledby="myModalLabel" aria-hidden="true">
      <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
      <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">
        <div class="modal-content">
          <form id="form-cuentabanc-edit">
            <!--<input type="hidden" name="idProyectoA" value="">-->
            <div class="modal-header">
              <h4 class="modal-title w-100" id="myModalLabel">Editar Cuenta Bancaria</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">x</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <!-- DataTales Example -->
                <div class="card mb-4">
                  <div class="">

                    <div class="form-group">
                      <div class="row">
                        <div class="col-lg-12">
                          <label for="usr">Banco:*</label>
                          <select name="cmbBancoEdit" id="cmbBancoEdit" required=""
                            onchange="validEmptyInput(this, 'invalid-banco')">
                          </select>
                          <div class="invalid-feedback" id="invalid-bancoEdit">La cuenta debe tener un banco.</div>
                        </div>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="row">
                        <div class="col-lg-12">
                          <label for="usr">No. de cuenta:*</label>
                          <div class="row">
                            <div class="col-lg-12 input-group">
                              <input type="hidden" id="cuenta-old">
                              <input class="form-control numeric-only" type="text"
                                oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                minlength="10" maxlength="20" name="txtNoCuentaEdit" id="txtNoCuentaEdit" autofocus=""
                                required="" placeholder="Ej. 0000000000" onkeyup="validarNoCuentaEditModal()">
                              <div class="invalid-feedback" id="invalid-noCuentaEdit">La cuenta debe tener un número.
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="row">
                        <div class="col-lg-8">
                          <label for="usr">CLABE:*</label>
                          <div class="row">
                            <div class="col-lg-12 input-group">
                              <input type="hidden" id="clave-old">
                              <input class="form-control numeric-only" type="text"
                                oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                name="txtCLABEEdit" id="txtCLABEEdit" minlength="18" maxlength="18" autofocus=""
                                required="" placeholder="Ej. 000 000 0000000000 0" onkeyup="validarCLABEEditModal()">
                              <div class="invalid-feedback" id="invalid-clabeEdit">La cuenta debe tener una clabe.</div>
                            </div>
                          </div>
                        </div>
                        <div class="col-lg-4">
                          <label for="usr">Moneda:*</label>
                          <div class="row">
                            <div class="col-lg-12 input-group">
                              <select name="cmbCostoUniVentaEspecialEdit" id="cmbCostoUniVentaEspecialEdit" required=""
                                onchange="validEmptyInput(this, 'invalid-moneda')">
                              </select>
                              <div class="invalid-feedback" id="invalid-monedaEdit">La cuenta debe tener un tipo de
                                moneda.</div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer justify-content-center">
              <button type="button" class="btnesp espAgregar float-right"><span class="ajusteProyecto"
                  data-dismiss="modal" data-toggle="modal"
                  data-target="#eliminar_CuentaBancaria">Eliminar</span></button>
              <button type="button" class="btnesp first espCancelar" data-dismiss="modal" id="btnBancarias"><span
                  class="ajusteProyecto">Cancelar</span></button>
              <button type="button" class="btnesp espAgregar float-right"
                onclick="validarBancoEdit($('#txtPKCuentaBancaria').val())"><span class="ajusteProyecto">Guardar
                  cambios</span></button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!--END ADD MODAL SLIDE EDIT CUENTAS BANCARIAS -->

    <!--DELETE MODAL SLIDE CUENTAS BANCARIAS -->
    <div class="modal fade" id="eliminar_CuentaBancaria" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
      aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">

          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">¿Desea eliminar el registro de este?</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">x</span>
            </button>
          </div>
          <div>
            <br>
            <label for="usr" style="margin-left: 80px!important;">Se eliminará la cuenta bancaria con los siguientes
              datos:</label>
          </div>
          <div class="row" style="margin-left: 80px!important;">
            <div class="form-group col-md-2">
              <label for="usr">Cuenta:</label>
            </div>
            <div class="form-group col-md-10">
              <input type="text" style="border:none!important; background-color: transparent!important;"
                class="form-control" maxlength="20" id="txtNombreDCuenta" name="txtNombreDCuenta" placeholder=""
                required readonly>
            </div>
          </div>
          <div class="modal-body" style="font-size: 10 px!important; color: red;">Esta acción no podrá deshacerse</div>
          <div class="modal-footer">
            <button class="btnesp first espCancelar btnCancelarActualizacion" type="button" data-dismiss="modal"
              id="btnCancelarCuenta"><span class="ajusteProyecto">Cancelar</span></button>
            <button type="submit" class="btnesp espAgregar float-right" data-dismiss="modal"><span
                class="ajusteProyecto"
                onclick="obtenerIdBancoProveedorEliminar($('#txtPKCuentaBancaria').val())">Eliminar</span></button>
          </div>

        </div>
      </div>
    </div>
    <!--END DELETE MODAL SLIDE CUENTAS BANCARIAS -->

    <!--ADD MODAL SLIDE EDIT LISTA DE PRODUCTOS -->
    <div class="modal fade right" id="editar_Producto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
      aria-hidden="true">
      <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
      <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">
        <div class="modal-content">
          <form action="" method="POST">
            <div class="modal-header">
              <h4 class="modal-title w-100" id="myModalLabel">Editar Producto</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">x</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <!-- DataTales Example -->
                <div class="card mb-4">
                  <div class="">

                    <div class="form-group">
                      <div class="row">
                        <div class="col-lg-12">
                          <label for="usr">Producto:*</label>
                          <div class="row">
                            <div class="col-lg-12 input-group">
                              <span class="input-group-addon" style="width:100%">
                                <select name="cmbProveedorProductoEdit" id="cmbProveedorProductoEdit" required=""
                                  onchange="cambioProveedorEditModal()">
                                </select>
                              </span>
                              <img id="notaFProveedorProductoEdit" name="notaFProveedorProductoEdit"
                                style="display: none;" src="../../../../img/timdesk/alerta.svg" width=30px
                                title="Campo requerido" readonly>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="row">
                        <div class="col-lg-6">
                          <label for="usr">Nombre del producto del proveedor:</label>
                          <div class="row">
                            <div class="col-lg-12 input-group">
                              <input class="form-control" type="text" name="txtNombreProdProveEdit"
                                id="txtNombreProdProveEdit" autofocus="" required="" maxlength="255"
                                placeholder="Ej. Bata quirúgica desechable">
                              <img id="notaFNombreProdProveEdit" name="notaFNombreProdProveEdit" style="display: none;"
                                src="../../../../img/timdesk/alerta.svg" width=30px title="Campo requerido" readonly>
                            </div>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <label for="usr">Clave del producto del proveedor:</label>
                          <div class="row">
                            <div class="col-lg-12 input-group">
                              <input type="text" class="form-control" name="txtClaveProdProveEdit"
                                id="txtClaveProdProveEdit" required="" maxlength="50" placeholder="Ej. AA - 0001"
                                onkeyup="escribirClaveProveedorEditModal()">
                              <img id="notaClaveProdProveEdit" name="notaClaveProdProveEdit" style="display: none;"
                                src="../../../../img/timdesk/alerta.svg" width=30px
                                title="La clave ya existe para este proveedor, favor de anexar otra" readonly>
                              <img id="notaFClaveProdProveEdit" name="notaFClaveProdProveEdit" style="display: none;"
                                src="../../../../img/timdesk/alerta.svg" width=30px title="Campo requerido" readonly>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="row">
                        <div class="col-lg-6">
                          <label for="usr">Precio:*</label>
                          <div class="row">
                            <div class="col-lg-12 input-group">
                              <input class="form-control numericDecimal-only" type="text" name="txtPrecioProdProveEdit"
                                id="txtPrecioProdProveEdit" min="0" maxlength="13" placeholder="Ej. 30.00" onkeyup="validEmptyInput(this)">
                              <span class="input-group-addon" style="width:100px">
                                <select name="cmbMonedaPrecioEdit" id="cmbMonedaPrecioEdit" required="">
                                </select>
                              </span>
                              <div class="invalid-feedback" id="invalid-precioProdU">El producto debe tener un precio.</div>
                            </div>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <label for="usr">Cantidad mínima de compra:</label>
                          <div class="row">
                            <div class="col-lg-12 input-group">
                              <input class="form-control numeric-only" type="text" name="txtCantMinProdProveEdit"
                                id="txtCantMinProdProveEdit" min="0" maxlength="12"
                                oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                placeholder="Ej. 1000">
                              <img id="notaFCantMinProdProveEdit" name="notaFCantMinProdProveEdit"
                                style="display: none;" src="../../../../img/timdesk/alerta.svg" width=30px
                                title="Campo requerido" readonly>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="row">
                        <div class="col-lg-6">
                          <label for="usr">Días de entrega:</label>
                          <div class="row">
                            <div class="col-lg-12 input-group">
                              <input class="form-control numeric-only" type="text" name="txtDiasEntregProdProveEdit"
                                id="txtDiasEntregProdProveEdit" autofocus="" required="" min="0" maxlength="3"
                                oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                placeholder="Ej. 15">
                              <img id="notaFDiasEntregProdProveEdit" name="notaFDiasEntregProdProveEdit"
                                style="display: none;" src="../../../../img/timdesk/alerta.svg" width=30px
                                title="Campo requerido" readonly>
                            </div>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <label for="usr">Unidad de medida:</label>
                          <div class="row">
                            <div class="col-lg-12 input-group">
                              <span class="input-group-addon" style="width:100%">
                                <input class="form-control" type="text" name="txtUnidadMedidaEdit"
                                  id="txtUnidadMedidaEdit" autofocus="" required="" maxlength="50"
                                  placeholder="Ej. Caja de 12 piezas">
                              </span>
                              <img id="notaFUnidadMProveedorEdit" name="notaFUnidadMProveedorEdit"
                                style="display: none;" src="../../../../img/timdesk/alerta.svg" width=30px
                                title="Campo requerido" readonly>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer justify-content-center">
              <button type="button" class="btnesp espAgregar float-right"><span class="ajusteProyecto"
                  data-dismiss="modal" data-toggle="modal" data-target="#eliminar_Producto">Eliminar</span></button>
              <button type="button" class="btnesp first espCancelar" data-dismiss="modal" id="btnProductos"><span
                  class="ajusteProyecto">Cancelar</span></button>
              <button type="button" class="btnesp espAgregar float-right"
                onclick="validarProveedor($('#txtPKProductoProveedor').val())"><span class="ajusteProyecto">Guardar
                  cambios</span></button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!--END ADD MODAL SLIDE EDIT LISTA DE PRODUCTOS -->

    <!--DELETE MODAL SLIDE LISTA DE PRODUCTOS -->
    <div class="modal fade" id="eliminar_Producto" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
      aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">

          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">¿Desea eliminar el registro de este?</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">x</span>
            </button>
          </div>
          <div>
            <br>
            <label for="usr" style="margin-left: 80px!important;">Se eliminará el producto con los siguientes
              datos:</label>
          </div>
          <div class="row" style="margin-left: 80px!important;">
            <div class="form-group col-md-2">
              <label for="usr">Nombre:</label>
            </div>
            <div class="form-group col-md-10">
              <input type="text" style="border:none!important; background-color: transparent!important;"
                class="form-control" maxlength="255" id="txtNombreDProducto" name="txtNombreDProducto" placeholder=""
                required readonly>
            </div>
          </div>
          <div class="modal-body" style="font-size: 10 px!important; color: red;">Esta acción no podrá deshacerse</div>
          <div class="modal-footer">
            <button class="btnesp first espCancelar btnCancelarActualizacion" type="button" data-dismiss="modal"
              id="btnCancelarProducto"><span class="ajusteProyecto">Cancelar</span></button>
            <button type="submit" class="btnesp espAgregar float-right" data-dismiss="modal"><span
                class="ajusteProyecto"
                onclick="eliminarProveedor($('#txtPKDatosProductoProveedor').val())">Eliminar</span></button>
          </div>

        </div>
      </div>
    </div>
    <!--END DELETE MODAL SLIDE LISTA DE PRODUCTOS -->

    <script src="../../../../js/sb-admin-2.min.js"></script>
    <script src="../../../../js/scripts.js"></script>
    <script src="../../../../js/notificaciones_timlid.js" charset="utf-8"></script>
    <script src="../../js/agregar_proveedores.js" charset="utf-8"></script>
    <script src="../../js/razon_social_proveedor.js" charset="utf-8"></script>
    <script src="../../js/contacto_proveedor.js" charset="utf-8"></script>
    <script src="../../js/banco_proveedor.js" charset="utf-8"></script>
    <script src="../../js/direccion_envio_proveedor.js" charset="utf-8"></script>
    <script src="../../../../js/slimselect.min.js"></script>
    <!-- Custom scripts for all pages-->
    <script src="../../../../js/pestanas_proveedores.js"></script>
    <script src="../../../../js/validaciones.js"></script>
    <script>
    loadAlertsNoti('alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
    setInterval(loadAlertsNoti, 10000, 'alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit')
      .val());
    </script>
    <script type="text/javascript">
    CargarDatosProveedor();
    </script>

    <script>
    $('#editar_InfoFiscal').on('hidden.bs.modal', function(event) {
      $("#invalid-razonSocEdit").text("display", "block");
      $("#invalid-razonSocEdit").css("display", "none");
      $("#txtRazonSocialEdit").removeClass("is-invalid");

      $("#invalid-rfcEdit").text("display", "block");
      $("#invalid-rfcEdit").css("display", "none");
      $("#txtRFCEdit").removeClass("is-invalid");

      $("#invalid-cpEdit").text("display", "block");
      $("#invalid-cpEdit").css("display", "none");
      $("#txtCPEdit").removeClass("is-invalid");
    });

    $("#editar_Contacto").on("hidden.bs.modal", function(event) {
      $("#invalid-nombreContEdit").css("display", "none");
      $("#txtNombreContactoEdit").removeClass("is-invalid");

      $("#invalid-emailContEdit").css("display", "none");
      $("#txtEmailEdit").removeClass("is-invalid");
    });

    $("#editar_CuentaBancancaria").on("hidden.bs.modal", function(event) {
      console.log("Me cerre");
      $("#invalid-bancoEdit").css("display", "none");
      $("#cmbBancoEdit").removeClass("is-invalid");

      $("#invalid-noCuentaEdit").css("display", "none");
      $("#txtNoCuentaEdit").removeClass("is-invalid");

      $("#invalid-clabeEdit").css("display", "none");
      $("#txtCLABEEdit").removeClass("is-invalid");

      $("#invalid-monedaEdit").css("display", "none");
      $("#cmbCostoUniVentaEspecialEdit").removeClass("is-invalid");
    });
    </script>
</body>

</html>