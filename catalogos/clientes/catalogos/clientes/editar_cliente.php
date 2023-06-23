<?php
session_start();
require_once '../../../../include/db-conn.php';
$user = $_SESSION["Usuario"];
$PKEmpresa = $_SESSION["IDEmpresa"];

$Cliente = $_GET["c"];

$stmt = $conn->prepare("SELECT empresa_id FROM clientes WHERE PKCliente = :id");
$stmt->bindValue(':id', $Cliente, PDO::PARAM_INT);
$stmt->execute();
$row = $stmt->fetch();

$GLOBALS["PKCliente"] = $row['empresa_id'];

if (isset($_SESSION["Usuario"])) {
  require_once '../../../../include/db-conn.php';
  $user = $_SESSION["Usuario"];

  if ($GLOBALS["PKCliente"] != $PKEmpresa) {
    header("location:../../../clientes/catalogos/clientes/");
  }
} else {
  header("location:../../../dashboard");
}
  $jwt_ruta = "../../../../";
  require_once '../../../jwt.php';
  $token = $_SESSION['token_ld10d'];
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="icon" type="image/png" href="../../../../img/header/bluTimlid.png">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Timlid | Editar cliente</title>

  <!-- ESTILOS -->
  <link href="../../../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../../../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../../../css/slimselect.min.css" rel="stylesheet">
  <link href="../../style/agregar_entrada.css" rel="stylesheet">
  <link href="../../style/pestanas_clientes.css" rel="stylesheet">
  <link href="../../../../css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="../../../../vendor/datatables/responsive.dataTables.css" rel="stylesheet">
  <link href="../../../../vendor/datatables/buttons.dataTables.css" rel="stylesheet">
  <link href="../../../../css/lobibox.min.css" rel="stylesheet">
  <link href="../../../../css/styles.css" rel="stylesheet">
  <link href="../../../../css/stylesNewTable.css" rel="stylesheet">
  <link href="../../../../css/croppie.css" rel="stylesheet">
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
        $icono = 'ICONO-CLIENTES-AZUL.svg';
        $titulo = 'Editar cliente';
        $backIcon = true;
        require_once $rutatb . 'topbar.php';
        ?>
        <!-- End of Topbar -->
        <input type="hidden" id="txtUsuario" value="<?= $_SESSION['PKUsuario']; ?>">
        <input type="hidden" id="txtUsuario" value="<?= $_SESSION['PKUsuario']; ?>">
        <input type="hidden" id="txtRuta" value="<?= $ruta; ?>">
        <input type="hidden" id="txtEdit" value="<?= $ruteEdit; ?>">
        <input type="hidden" id="txtPKCliente" value="<?= $Cliente; ?>">
        .

        <!-- Begin Page Content -->
        <div class="container-fluid" id="upPg">
          <div class="row">
            <div class="col-lg-12">
              <ul class="nav nav-tabs">
                <li class="nav-item">
                  <a id="CargarDatosEdicionCliente" class="nav-link" href="#" onclick="CargarDatosCliente(window.location.href = 'editar_cliente.php?c='+$('#txtPKCliente').val())">
                    Datos del cliente
                  </a>
                </li>
                <li class="nav-item">
                  <a id="CargarDatosEdicionFiscal" class="nav-link" onclick="SeguirDatosFiscales($('#txtPKCliente').val())">
                    Información fiscal
                  </a>
                </li>
                <li class="nav-item">
                  <a id="CargarDatosEdicionContacto" class="nav-link" onclick="SeguirContacto($('#txtPKCliente').val())">
                    Contacto
                  </a>
                </li>
                <li class="nav-item">
                  <a id="CargarDatosEdicionCuentasBancarias" class="nav-link" onclick="SeguirCuentasBancarias($('#txtPKCliente').val())">
                    Cuentas bancarias
                  </a>
                </li>
                <li class="nav-item">
                  <a id="CargarEdicionListadoProductos" class="nav-link" onclick="SeguirListadoProductos($('#txtPKCliente').val())">
                    Listado de productos
                  </a>
                </li>
                <li class="nav-item">
                  <a id="CargarEdicionDireccionesEnvio" class="nav-link" onclick="SeguirDireccionesEnvio($('#txtPKCliente').val())">
                    Direcciones de envío
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

    <!--ADD MODAL SLIDE EDIT CONTACTO-->
    <div class="modal fade right" id="editar_Contacto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
      <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">
        <div class="modal-content">
          <form id="formDatosContactoEdit">
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
                              <input type="hidden" id="nombre-old">
                              <input class="form-control alphaNumeric-only" type="text" name="txtNombreContactoEdit" id="txtNombreContactoEdit" onkeyup="validEmptyInput('txtNombreContactoEdit', 'invalid-nombreContEdit', 'El contacto debe tener un nombre.')" required maxlength="50" placeholder="Ej. José María">
                              <div class="invalid-feedback" id="invalid-nombreContEdit">El contacto debe tener un nombre.
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <label for="usr">Apellido(s) del contacto:</label>
                          <div class="row">
                            <div class="col-lg-12 input-group">
                              <input type="hidden" id="apellido-old">
                              <input type="text" maxlength="50" class="form-control alphaNumeric-only" name="txtApellidoContactoEdit" id="txtApellidoContactoEdit" placeholder="Ej. López Pérez">

                              <img id="notaFApellidoContactoEdit" name="notaFApellidoContactoEdit" style="display: none;" src="../../../../img/timdesk/alerta.svg" width=30px title="Campo requerido" readonly>
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
                              <input type="text" class="form-control" name="txtPuestoEdit" id="txtPuestoEdit" maxlength="50" placeholder="Ej. Gerente de ventas">

                              <img id="notaFPuestoEdit" name="notaFPuestoEdit" style="display: none;" src="../../../../img/timdesk/alerta.svg" width=30px title="Campo requerido" readonly>
                            </div>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <label for="usr">Teléfono fijo:</label>
                          <div class="row">
                            <div class="col-lg-12 input-group">
                              <input class="form-control numeric-only" type="text" name="txtTelefonoEdit" id="txtTelefonoEdit" autofocus="" maxlength="10" placeholder="Ej. 33 3333 3333">
                              <img id="notaFTelefonoEdit" name="notaFTelefonoEdit" style="display: none;" src="../../../../img/timdesk/alerta.svg" width=30px title="Campo requerido" readonly>
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
                              <input class="form-control numeric-only" type="text" name="txtCelularEdit" id="txtCelularEdit" autofocus="" minlength="7" maxlength="10" placeholder="Ej. 33 3333 3333">
                              <img id="notaFCelularEdit" name="notaFCelularEdit" style="display: none;" src="../../../../img/timdesk/alerta.svg" width=30px title="Campo requerido" readonly>
                            </div>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <label for="usr">E-mail:*</label>
                          <div class="row">
                            <div class="col-lg-12 input-group">
                              <input type="hidden" id="email-old">
                              <input class="form-control" type="email" name="txtEmailEdit" id="txtEmailEdit" autofocus="" required="" maxlength="100" placeholder="Ej. ejemplo@dominio.com" onkeyup="validarCorreoContactoModalEdit(this.value)">
                              <div class="invalid-feedback" id="invalid-emailContEdit">El contacto debe tener un email.
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="row">
                        <div class="col-lg-12">
                          <label for="usr">Correos automáticos de:</label>
                        </div>
                        <div class="col-lg-12">
                          <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="cbxFacturacionEdit" name="cbxFacturacionEdit">
                            <label class="form-check-label" for="flexSwitchCheckDefault">Facturación</label>
                          </div>

                          <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="cbxComplementoPagoEdit" name="cbxComplementoPagoEdit">
                            <label class="form-check-label" for="flexSwitchCheckDefault">Complemento de pago</label>
                          </div>

                          <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="cbxAvisosEnvioEdit" name="cbxAvisosEnvioEdit">
                            <label class="form-check-label" for="flexSwitchCheckDefault">Avisos de envío</label>
                          </div>

                          <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="cbxPagosEdit" name="cbxPagosEdit">
                            <label class="form-check-label" for="flexSwitchCheckDefault">Pagos</label>
                          </div>
                        </div>
                      </div>
                    </div>

                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer justify-content-end">
              <button type="button" class="btn-custom btn-custom--blue" data-dismiss="modal" data-toggle="modal" data-target="#eliminar_Contacto">
                <span>Eliminar</span>
              </button>
              <button type="button" class="btn-custom btn-custom--border-blue" data-dismiss="modal" id="BtnCon">
                <span>Cancelar</span>
              </button>
              <button type="button" class="btn-custom btn-custom--blue" onclick="validarContactoEdit($('#txtPKCliente').val(), $('#txtPKContacto').val())">
                <span>Guardar cambios</span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!--END ADD MODAL SLIDE EDIT CONTACTO-->

    <!--DELETE MODAL SLIDE CONTACTO-->
    <div class="modal fade" id="eliminar_Contacto" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">

          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">¿Desea eliminar el registro?</h5>
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
              <input type="text" style="border:none!important; background-color: transparent!important;" class="form-control" maxlength="50" id="txtNombreDContacto" name="txtNombreDContacto" placeholder="" required readonly>
            </div>
          </div>
          <div class="modal-body" style="font-size: 10 px!important; color: red;">Esta acción no podrá deshacerse</div>
          <div class="modal-footer">
            <button class="btn-custom btn-custom--border-blue" type="button" data-dismiss="modal" id="btnCancelarContacto">Cancelar
            </button>
            <button type="submit" class="btn-custom btn-custom--blue" data-dismiss="modal" onclick="obtenerIdContactoClienteEliminar($('#txtPKContacto').val())">Eliminar
            </button>
          </div>

        </div>
      </div>
    </div>
    <!--END DELETE MODAL SLIDE CONTACTO-->

    <!--ADD MODAL SLIDE EDIT CUENTAS BANCARIAS -->
    <div class="modal fade right" id="editar_CuentaBancancaria" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
      <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">
        <div class="modal-content">
          <form id="formDatosProveedorEdit">
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
                          <div class="row">
                            <div class="col-lg-12 input-group">
                              <select name="cmbBancoEdit" id="cmbBancoEdit" required="">
                              </select>
                              <div class="invalid-feedback" id="invalid-nombreBancoEdit">La cuenta debe tener un banco.
                              </div>
                            </div>
                          </div>
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
                              <input class="form-control numeric-only" type="text" minlength="10" maxlength="20" name="txtNoCuentaEdit" id="txtNoCuentaEdit" min="0" autofocus="" placeholder="Ej. 0000000000" onkeyup="validarNoCuentaEditModal()">
                              <div class="invalid-feedback" id="invalid-noCuentaEdit">La cuenta debe tener un número.</div>
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
                              <input class="form-control numeric-only" type="text" name="txtCLABEEdit" id="txtCLABEEdit" min="0" minlength="18" maxlength="18" autofocus="" placeholder="Ej. 000 000 0000000000 0" onkeyup="validarCLABEEditModal()">
                              <div class="invalid-feedback" id="invalid-claveCuentaEdit">La cuenta debe tener una clabe.
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-lg-4">
                          <label for="usr">Moneda:*</label>
                          <div class="row">
                            <div class="col-lg-12 input-group">
                              <select name="cmbCostoUniVentaEspecialEdit" id="cmbCostoUniVentaEspecialEdit" required="">
                              </select>
                              <div class="invalid-feedback" id="invalid-monedaCuentaEdir">La cuenta debe tener un tipo de
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
            <div class="modal-footer justify-content-end">
              <button type="button" class="btn-custom btn-custom--blue" data-dismiss="modal" data-toggle="modal" data-target="#eliminar_DireccionProveedor">Eliminar
              </button>
              <button type="button" class="btn-custom btn-custom--border-blue" data-dismiss="modal" id="btnBancarias">Cancelar
              </button>
              <button type="button" class="btn-custom btn-custom--blue" onclick="validarBancoEdit($('#txtPKCuentaBancaria').val())">Guardar cambios
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!--END ADD MODAL SLIDE EDIT CUENTAS BANCARIAS -->

    <!--DELETE MODAL SLIDE CUENTAS BANCARIAS -->
    <div class="modal fade" id="eliminar_DireccionProveedor" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
              <input type="text" style="border:none!important; background-color: transparent!important;" class="form-control" maxlength="20" id="txtNombreDCuenta" name="txtNombreDCuenta" placeholder="" required readonly>
            </div>
          </div>
          <div class="modal-body" style="font-size: 10 px!important; color: red;">Esta acción no podrá deshacerse</div>
          <div class="modal-footer">
            <button class="btnesp first espCancelar btnCancelarActualizacion" type="button" data-dismiss="modal" id="btnCancelarCuenta"><span class="ajusteProyecto">Cancelar</span></button>
            <button type="submit" class="btnesp espAgregar float-right" data-dismiss="modal"><span class="ajusteProyecto" onclick="obtenerIdBancoClienteEliminar($('#txtPKCuentaBancaria').val())">Eliminar</span></button>
          </div>

        </div>
      </div>
    </div>
    <!--END DELETE MODAL SLIDE CUENTAS BANCARIAS -->

    <!--ADD MODAL SLIDE EDIT DIRECCIONES DE ENVIO -->
    <div class="modal fade right" id="editar_DireccionEnvio" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
      <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">
        <div class="modal-content">
          <form id="formDatosFiscalesEdit">
            <!--<input type="hidden" name="idProyectoA" value="">-->
            <div class="modal-header">
              <h4 class="modal-title w-100" id="myModalLabel">Editar Direccón de Envío</h4>
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
                          <label for="usr">Sucursal:*</label>
                          <div class="row">
                            <div class="col-lg-12 input-group">
                              <input class="form-control" type="text" name="txtSucursalEdit" id="txtSucursalEdit" autofocus="" required="" maxlength="255" placeholder="Ej. Nogales" onkeyup="escribirSucursalModal($('#txtPKCliente').val())">
                              <div class="invalid-feedback" id="invalid-sucursalEdit">La dirección debe tener un nombre de
                                sucursal.</div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="row">
                        <div class="col-lg-6">
                          <label for="usr">Contacto:*</label>
                          <div class="row">
                            <div class="col-lg-12 input-group">
                              <input class="form-control alphaNumeric-only" type="text" name="txtContactoEdit" id="txtContactoEdit" maxlength="255" required=""placeholder="Ej. José María Lopéz Pérez" onkeyup="validEmptyInput('txtContactoEdit', 'invalid-contactoEdit', 'La dirección debe tener un contacto.')">
                              <div class="invalid-feedback" id="invalid-contactoEdit">La dirección debe tener un contacto.</div>
                            </div>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <label for="usr">Teléfono:*</label>
                          <div class="row">
                            <div class="col-lg-12 input-group">
                              <input class="form-control numeric-only" type="text" name="txtTelefonoEdit2" id="txtTelefonoEdit2" maxlength="10" required="" placeholder="Ej. 123 456 7890" onkeyup="validEmptyInput('txtTelefonoEdit2', 'invalid-telefonoEdit', 'La dirección debe tener un teléfono.')">
                              <div class="invalid-feedback" id="invalid-telefonoEdit">La dirección debe tener un teléfono.</div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="row">
                        <div class="col-lg-6">
                          <label for="usr">E-mail:*</label>
                          <div class="row">
                            <div class="col-lg-12 input-group">
                              <input class="form-control alphaNumeric-only" type="email" name="txtEmailEdit2" id="txtEmailEdit2" autofocus="" required="" maxlength="100" placeholder="Ej. ejemplo@dominio.com" onkeyup="validarCorreoDireModal(this.value)">
                              <div class="invalid-feedback" id="invalid-emailDireEdit">La dirección debe tener un email.
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <label for="usr">Calle:*</label>
                          <div class="row">
                            <div class="col-lg-12 input-group">
                              <input class="form-control" type="text" name="txtCalleEdit" id="txtCalleEdit" autofocus="" required="" maxlength="255" placeholder="Ej. Av. México" onkeyup="validEmptyInput('txtCalleEdit', 'invalid-calleDireEdit', 'La dirección debe tener una calle.')">
                              <div class="invalid-feedback" id="invalid-calleDireEdit">La dirección debe tener una calle.
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="row">
                        <div class="col-lg-6">
                          <label for="usr">Número exterior:*</label>
                          <div class="row">
                            <div class="col-lg-12 input-group">
                              <input class="form-control alphaNumeric-only" type="text" name="txtNumExtEdit" id="txtNumExtEdit" autofocus="" required="" maxlength="30" placeholder="Ej. 2353 A" onkeyup="validEmptyInput('txtNumExtEdit', 'invalid-numExtEdit', 'La dirección debe tener un número exterior.')">
                              <div class="invalid-feedback" id="invalid-numExtEdit">La dirección debe tener un número
                                exterior.</div>
                            </div>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <label for="usr">Número interior:</label>
                          <div class="row">
                            <div class="col-lg-12 input-group">
                              <input class="form-control alphaNumeric-only" type="text" name="txtNumIntEdit" id="txtNumIntEdit" maxlength="10" placeholder="Ej. 524">
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="row">
                        <div class="col-lg-6">
                          <label for="usr">Colonia:*</label>
                          <div class="row">
                            <div class="col-lg-12 input-group">
                              <input class="form-control" type="text" name="txtColoniaEdit" id="txtColoniaEdit" autofocus="" required="" maxlength="255" placeholder="Ej. Los Agaves" onkeyup="validEmptyInput('txtColoniaEdit', 'invalid-coloniaEdit', 'La dirección debe tener una colonia.')">
                              <div class="invalid-feedback" id="invalid-coloniaEdit">La dirección debe tener una colonia.
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <label for="usr">Municipio:*</label>
                          <div class="row">
                            <div class="col-lg-12 input-group">
                              <input class="form-control" type="text" name="txtMunicipioEdit" id="txtMunicipioEdit" autofocus="" required="" maxlength="25" placeholder="Ej. Guadalajara" onkeyup="validEmptyInput('txtMunicipioEdit', 'invalid-municipioDireEdit', 'La direccion debe tener un municipio.')">
                              <div class="invalid-feedback" id="invalid-municipioDireEdit">La direccion debe tener un
                                municipio.</div>
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
                              <select name="cmbPaisEdit" id="cmbPaisEdit" required="" onchange="cambioPaisDirModal()">
                              </select>
                              <div class="invalid-feedback" id="invalid-paisDireEdit">La dirección debe tener un pais.</div>
                            </div>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <label for="usr">Estado:*</label>
                          <div class="row">
                            <div class="col-lg-12 input-group">
                              <select name="cmbEstadoEdit" id="cmbEstadoEdit" required="">
                              </select>
                              <div class="invalid-feedback" id="invalid-estadoDireEdit">La dirección debe tener un estado.
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="row">
                        <div class="col-lg-12">
                          <label for="usr">Código Postal:*</label>
                          <div class="row">
                            <div class="col-lg-12 input-group">
                              <input class="form-control numeric-only" type="text" name="txtCPEdit" id="txtCPEdit" autofocus="" required="" maxlength="5" placeholder="Ej. 52632" onkeyup="validarCPEditModal()">
                              <div class="invalid-feedback" id="invalid-cpDireEdit">La dirección debe tener un CP.</div>
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
              <button type="button" class="btn-custom btn-custom--blue" data-dismiss="modal" data-toggle="modal" data-target="#eliminar_CuentaBancaria">Eliminar
              </button>
              <button type="button" class="btn-custom btn-custom--border-blue" data-dismiss="modal" id="btnBancariasEdit">Cancelar
              </button>
              <button type="button" class="btn-custom btn-custom--blue" onclick="anadirRazonSocialDirEdit($('#txtPKCliente').val())">Guardar cambios
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!--END ADD MODAL SLIDE EDIT DIRECCIONES DE ENVIO -->

    <!--DELETE MODAL SLIDE DIRECCIONES DE ENVIO -->
    <div class="modal fade" id="eliminar_CuentaBancaria" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
            <label for="usr" style="margin-left: 80px!important;">Se eliminará la dirección con los siguientes
              datos:</label>
          </div>
          <div class="row" style="margin-left: 80px!important;">
            <div class="form-group col-md-2">
              <label for="usr">Sucursal:</label>
            </div>
            <div class="form-group col-md-10">
              <input type="text" style="border:none!important; background-color: transparent!important;" class="form-control" maxlength="255" id="txtNombreDDir" name="txtNombreDDir" placeholder="" required readonly>
            </div>
          </div>
          <div class="modal-body" style="font-size: 10 px!important; color: red;">Esta acción no podrá deshacerse</div>
          <div class="modal-footer">
            <button class="btnesp first espCancelar btnCancelarActualizacion" type="button" data-dismiss="modal" id="btnCancelarCuenta"><span class="ajusteProyecto">Cancelar</span></button>
            <button type="submit" class="btnesp espAgregar float-right" data-dismiss="modal"><span class="ajusteProyecto" onclick="obtenerIdDireccionProveedorEliminar($('#txtPKDireccion').val())">Eliminar</span></button>
          </div>

        </div>
      </div>
    </div>
    <!--END DELETE MODAL SLIDE DIRECCIONES DE ENVIO -->

    <!-- ADD MODAL EDIT COSTO PRODUCTO CLIENTE -->
    <div class="modal fade right" id="editar_Producto_cliente" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-full-height modal-right modal-md" role="document">
        <div class="modal-content">
          <form action="" id="EditarProductoCliente" method="POST">
            <div class="modal-header">
              <h4 class="modal-title w-100" id="myModalLabel">Editar costo especial</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">x</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label for="txtProducto">Producto:</label>
                <div name="txtProducto" id="txtProducto"></div>
              </div>
              <div class="form-group">
                <label for="usr">Costo especial:*</label>
                <input class="form-control numericDecimal-only readEditPermissions" type="text" name="txtCostoEspecialVenta_modalEdit" id="txtCostoEspecialVenta_modalEdit" maxlength="13" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" placeholder="Ej. 10.00" onkeyup="validEmptyInput('txtCostoEspecialVenta_modalEdit', 'invalid-costoProd_edit', 'El producto debe tener un costo.')" required>            
                <div class="invalid-feedback" id="invalid-costoProd_edit">El producto debe tener un costo.</div>
              </div>
              <div class="form-group">
                <label for="usr">Moneda:*</label>
                <select name="cmbMoneda_edit" id="cmbMoneda_edit" class="readEditPermissions" tabindex="-1" required>
                  <option data-placeholder="true"></option>
                  <option value="49">EUR</option>
                  <option value="100">MXN</option>
                  <option value="149">USD</option>
                </select>
                <div class="invalid-feedback" id="invalid-moneda_edit">El producto debe tener una moneda.</div>
              </div>
              <br><br>
              <div>
                <label for="usr">Campos requeridos *</label>
              </div>
              <br><br>
            </div>
            <div class="modal-footer justify-content-center">
              <button type="button" class="btnesp espAgregar" data-dismiss="modal" data-toggle="modal" data-target="#eliminar_Producto"><span class="ajusteProyecto">Eliminar</span></button>
              <button type="button" class="btnesp first espCancelar btnCancelarActualizacion" data-dismiss="modal"><span class="ajusteProyecto">Cancelar</span></button>
              <button type="button" class="btnesp espAgregar float-right" data-dismiss="modal" name="btnEditarCosto" id="btnEditarCosto"><span class="ajusteProyecto">Guardar</span></button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- END MODAL EDIT COSTO PRODUCTO CLIENTE -->

    <!--DELETE MODAL SLIDE PRODUCTOS -->
    <div class="modal fade" id="eliminar_Producto" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">

          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">¿Desea eliminar el registro de este?</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">x</span>
            </button>
          </div>
          <div>
            <input type="hidden" name="txthideidproduct" id="txthideidproduct">
            <br>
          </div>
          
          <div class="modal-body" style="font-size: 10 px!important; color: red;">Esta acción no podrá deshacerse</div>
          <div class="modal-footer">
            <button class="btnesp first espCancelar btnCancelarActualizacion" type="button" data-dismiss="modal" id="btnCancelarCuenta"><span class="ajusteProyecto">Cancelar</span></button>
            <button type="submit" class="btnesp espAgregar float-right" data-dismiss="modal"><span class="ajusteProyecto" onclick="eliminarProducto($('#txthideidproduct').val())">Eliminar</span></button>
          </div>

        </div>
      </div>
    </div>
    <!--END DELETE MODAL SLIDE PRODUCTOS -->

  <div class="modal fade" id="editar_Predeterminado" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form action="" method="POST">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">¿Seguro que desea predeterminar este contacto?</h5>
            <button class="close text-light" type="button" data-dismiss="modal" aria-label="Close">
              x
            </button>
          </div>
          <div class="modal-body" style="font-size: 10 px!important; color: red;"></div>
          <div class="modal-footer d-flex justify-content-end">
            <button class="btn-custom btn-custom--border-blue btnCancelarPredeterminacion" type="button" data-dismiss="modal"><span class="ajusteProyecto">Cancelar</span></button>
            <button type="submit" onclick="predeterminarDireccion();" class="btn-custom btn-custom--blue"><span class="ajusteProyecto" data-dismiss="modal">Predeterminar</span></button>
          </div>
        </form>
      </div>
    </div>
  </div>

    <script src="../../../../js/sb-admin-2.min.js"></script>
    <script src="../../../../js/scripts.js"></script>
    <script src="../../../../js/notificaciones_timlid.js" charset="utf-8"></script>
    <script src="../../js/editar_clientes.js" charset="utf-8"></script>
    <script src="../../js/razon_social_cliente.js" charset="utf-8"></script>
    <script src="../../js/contacto_cliente.js" charset="utf-8"></script>
    <script src="../../js/banco_cliente.js" charset="utf-8"></script>
    <script src="../../js/productos_cliente.js" charset="utf-8"></script>
    <script src="../../js/direccion_envio_cliente.js" charset="utf-8"></script>
    <script src="../../../../js/slimselect.min.js"></script>
    <!-- Custom scripts for all pages-->

    <script src="../../../../js/pestanas_clientesEdit.js"></script>
    <script type="text/javascript">
      CargarDatosCliente($('#txtPKCliente').val());
    </script>
    <script src="../../../../js/validaciones.js"></script>
    <script>
      loadAlertsNoti('alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
      setInterval(loadAlertsNoti, 10000, 'alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit')
        .val());
    </script>
</body>

</html>