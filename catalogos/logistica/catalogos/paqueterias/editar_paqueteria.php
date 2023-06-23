<?php
session_start();
require_once '../../../../include/db-conn.php';

$Paqueteria = $_GET["pq"];

if (isset($_SESSION["Usuario"])) {
  $user = $_SESSION["Usuario"];
  $PKEmpresa = $_SESSION["IDEmpresa"];

  $stmt = $conn->prepare("SELECT empresa_id FROM proveedores WHERE PKProveedor = :id");
  $stmt->bindValue(':id', $Paqueteria, PDO::PARAM_INT);
  $stmt->execute();
  $row = $stmt->fetch();

  $GLOBALS["PKProveedor"] = $row['empresa_id'];

  if ($GLOBALS["PKProveedor"] != $PKEmpresa) {
    header("location:../../../logistica/catalogos/paqueterias/");
  }
} else {
  header("location:../../../dashboard");
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="icon" type="image/png" href="../../../../img/header/bluTimlid.png">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Timlid | Editar paquetería</title>

  <!-- ESTILOS -->
  <link href="../../../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../../../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../../../css/slimselect.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../../style/agregar_entrada.css">
  <link rel="stylesheet" href="../../style/pestanas_paqueteriasEdit.css">
  <link href="../../../../css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="../../../../vendor/datatables/responsive.dataTables.css" rel="stylesheet">
  <link rel="stylesheet" href="../../../../vendor/datatables/buttons.dataTables.css">
  <link href="../../../../css/stylesNewTable.css" rel="stylesheet">
  <link href="../../../../css/styles.css" rel="stylesheet">
  <link rel="stylesheet" href="../../../../css/croppie.css" />
  <link href="../../../../css/lobibox.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../../../../css/sweetalert2.css">
  <link rel="stylesheet" href="../../../../css/notificaciones.css">
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
        $icono = '../../../../img/icons/ICONO PAQUETERIAS-01.svg';
        $titulo = 'Editar paquetería';
        $backIcon = true;
        require_once $rutatb . 'topbar.php';
        ?>
        <!-- End of Topbar -->
        <input type="hidden" id="txtUsuario" value="<?= $_SESSION['PKUsuario']; ?>">
        <input type="hidden" id="txtRuta" value="<?= $ruta; ?>">
        <input type="hidden" id="txtEdit" value="<?= $ruteEdit; ?>">

        <!-- Begin Page Content -->
        <div class="container-fluid">
          <div class="row">
            <div class="col-lg-12">
              <ul class="nav nav-tabs">
                <li class="nav-item">
                  <a id="CargarDatosPaqueteria" class="nav-link" onclick="CargarDatosPaqueteria(window.location.href = 'editar_paqueteria.php?pq='+_global.pkPaqueteria)">
                    Datos de la paquetería
                  </a>
                </li>
                <li class="nav-item">
                  <a id="CargarDatosContacto" class="nav-link" onclick="SeguirDatosContacto(_global.pkPaqueteria)">
                    Contacto
                  </a>
                </li>
                <li class="nav-item">
                  <a id="CargarDatosCuentasBancarias" class="nav-link" onclick="SeguirCuentasBancarias(_global.pkPaqueteria)">
                    Cuentas bancarias
                  </a>
                </li>
                <li class="nav-item">
                  <a id="CargarSucursales" class="nav-link" onclick="SeguirSucursales(_global.pkPaqueteria)">
                    Sucursales
                  </a>
                </li>
              </ul>

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
          <form action="" method="POST" id="formDatosContactoEdit">
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
                        <div class="col-lg-12">
                          <label for="usr">Nombre(s) del contacto:*</label>
                          <div class="row">
                            <div class="col-lg-12 input-group">
                              <input class="form-control alphaNumeric-only" type="text" name="txtNombreContactoEdit" id="txtNombreContactoEdit" autofocus="" required="" maxlength="50" placeholder="Ej. José María" onchange="validEmptyInput('txtNombreContactoEdit', 'invalid-nombreContEdit', 'El contacto debe tener un nombre.')">
                              <div class="invalid-feedback" id="invalid-nombreContEdit">El contacto debe tener un nombre.</div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="row">
                        <div class="col-lg-12">
                          <label for="usr">Apellido(s) del contacto:</label>
                          <div class="row">
                            <div class="col-lg-12 input-group">
                              <input type="text" maxlength="50" class="form-control alphaNumeric-only" name="txtApellidoContactoEdit" id="txtApellidoContactoEdit" placeholder="Ej. López Pérez">
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="row">
                        <div class="col-lg-12">
                          <label for="usr">Puesto:</label>
                          <div class="row">
                            <div class="col-lg-12 input-group">
                              <input type="text" class="form-control" name="txtPuestoEdit" id="txtPuestoEdit" maxlength="50" placeholder="Ej. Gerente de ventas">
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="row">
                        <div class="col-lg-12">
                          <label for="usr">Teléfono fijo:</label>
                          <div class="row">
                            <div class="col-lg-12 input-group">
                              <input class="form-control numeric-only" type="text" name="txtTelefonoEdit" id="txtTelefonoEdit" minlength="7" maxlength="10" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" placeholder="Ej. 33 3333 3333">
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="row">
                        <div class="col-lg-12">
                          <label for="usr">Celular:</label>
                          <div class="row">
                            <div class="col-lg-12 input-group">
                              <input class="form-control numeric-only" type="text" name="txtCelularEdit" id="txtCelularEdit" minlength="10" maxlength="10" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" placeholder="Ej. 33 3333 3333">
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="row">
                        <div class="col-lg-12">
                          <label for="usr">E-mail:*</label>
                          <div class="row">
                            <div class="col-lg-12 input-group">
                              <input class="form-control" type="email" name="txtEmailContactoEdit" id="txtEmailContactoEdit" required="" maxlength="100" placeholder="Ej. ejemplo@dominio.com" onchange="validarCorreo(this.value, 'txtEmailContactoEdit', 'invalid-emailContEdit');">
                              <div class="invalid-feedback" id="invalid-emailContEdit">El contacto debe tener un email.</div>
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
              <button type="button" class="btnesp espAgregar float-right"><span class="ajusteProyecto" data-dismiss="modal" data-toggle="modal" data-target="#eliminar_Contacto">Eliminar</span></button>
              <button type="button" class="btnesp first espCancelar" data-dismiss="modal" id="BtnCon"><span class="ajusteProyecto">Cancelar</span></button>
              <button type="button" class="btnesp espAgregar float-right">
                <span class="ajusteProyecto" id="btnEditarContacto">Guardar</span>
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
          <form action="" method="POST">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">¿Desea eliminar el registro de este?</h5>
              <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true" class="text-light">x</span>
              </button>
            </div>
            <div class="modal-body" style="font-size: 10 px!important; color: red;">Esta acción no podrá deshacerse</div>
            <div class="modal-footer">
              <button class="btn-custom btn-custom--border-blue btnCancelarActualizacion" type="button" data-dismiss="modal"><span class="ajusteProyecto">Cancelar</span></button>
              <button type="button" id="btnEliminarContacto" data-dismiss="modal" class="btn-custom btn-custom--blue">
                <span class="ajusteProyecto">Eliminar</span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!--END DELETE MODAL SLIDE CONTACTO-->

    <!--ADD MODAL SLIDE EDIT CUENTAS BANCARIAS-->
    <div class="modal fade right" id="editar_CuentaBancancaria" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
      <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">
        <div class="modal-content">
          <form action="" method="POST" id="formDatosCuentasBancariasEdit">
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
                          <div class="col-lg-12 input-group">
                            <select name="cmbBancoEdit" id="cmbBancoEdit" required="" onchange="validEmptyInput('cmbBancoEdit', 'invalid-bancoEdit', 'La cuenta debe tener un banco.')">
                            </select>
                            <div class="invalid-feedback" id="invalid-bancoEdit">La cuenta debe tener un banco.</div>
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
                              <input class="form-control numeric-only" type="text" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" minlength="10" maxlength="20" name="txtNoCuentaEdit" id="txtNoCuentaEdit" autofocus="" required="" placeholder="Ej. 0000000000" onchange="validarNoCuenta('txtNoCuentaEdit','invalid-noCuentaEdit')">
                              <div class="invalid-feedback" id="invalid-noCuentaEdit">La cuenta debe tener un número.</div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="row">
                        <div class="col-lg-12">
                          <label for="usr">CLABE:*</label>
                          <div class="row">
                            <div class="col-lg-12 input-group">
                              <input class="form-control numeric-only" type="text" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" name="txtCLABEEdit" id="txtCLABEEdit" minlength="18" maxlength="18" autofocus="" required="" placeholder="Ej. 000 000 0000000000 0" onchange="validarCLABE('txtCLABEEdit','invalid-clabeEdit')">
                              <div class="invalid-feedback" id="invalid-clabeEdit">La cuenta debe tener una clabe.</div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="row">
                        <div class="col-lg-12">
                          <label for="usr">Moneda:*</label>
                          <div class="row">
                            <div class="col-lg-12 input-group">
                              <select name="cmbCostoUniVentaEspecialEdit" id="cmbCostoUniVentaEspecialEdit" required="" onchange="validEmptyInput('cmbCostoUniVentaEspecialEdit', 'invalid-monedaEdit', 'La cuenta debe tener un tipo de moneda.')">
                              </select>
                              <div class="invalid-feedback" id="invalid-monedaEdit">La cuenta debe tener un tipo de moneda.</div>
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
              <button type="button" class="btnesp espAgregar float-right"><span class="ajusteProyecto" data-dismiss="modal" data-toggle="modal" data-target="#eliminar_CuentaBancaria">Eliminar</span></button>
              <button type="button" class="btnesp first espCancelar" data-dismiss="modal" id="BtnCon"><span class="ajusteProyecto">Cancelar</span></button>
              <button type="button" class="btnesp espAgregar float-right">
                <span class="ajusteProyecto" id="btnEditarCuentaBancaria">Guardar</span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!--END ADD MODAL SLIDE EDIT CUENTAS BANCARIAS-->

    <!--DELETE MODAL SLIDE CUENTAS BANCARIAS-->
    <div class="modal fade" id="eliminar_CuentaBancaria" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form action="" method="POST">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">¿Desea eliminar el registro de este?</h5>
              <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true" class="text-light">x</span>
              </button>
            </div>
            <div class="modal-body" style="font-size: 10 px!important; color: red;">Esta acción no podrá deshacerse</div>
            <div class="modal-footer">
              <button class="btn-custom btn-custom--border-blue btnCancelarActualizacion" type="button" data-dismiss="modal"><span class="ajusteProyecto">Cancelar</span></button>
              <button type="button" id="btnEliminarCuentaBancaria" data-dismiss="modal" class="btn-custom btn-custom--blue">
                <span class="ajusteProyecto">Eliminar</span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!--END DELETE MODAL SLIDE CUENTAS BANCARIAS-->

    <!--ADD MODAL SLIDE EDIT SUCURSALES-->
    <div class="modal fade right" id="editar_Sucursal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
      <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">
        <div class="modal-content">
          <form action="" method="POST" id="formDatosSucursalesEdit">
            <!--<input type="hidden" name="idProyectoA" value="">-->
            <div class="modal-header">
              <h4 class="modal-title w-100" id="myModalLabel">Editar Sucursal</h4>
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
                              <input class="form-control" type="text" name="txtSucursalEdit" id="txtSucursalEdit" autofocus="" required="" maxlength="255" placeholder="Ej. Nogales" onchange="escribirSucursal('txtSucursalEdit','invalid-sucursalEdit')">
                              <div class="invalid-feedback" id="invalid-sucursalEdit">La dirección debe tener un nombre de sucursal.</div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="row">
                        <div class="col-lg-12">
                          <label for="usr">Contacto:</label>
                          <div class="row">
                            <div class="col-lg-12 input-group">
                              <input class="form-control" type="text" name="txtContactoEdit" id="txtContactoEdit" maxlength="255" placeholder="Ej. José María Lopéz Pérez">
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="row">
                        <div class="col-lg-12">
                          <label for="usr">Teléfono:</label>
                          <div class="row">
                            <div class="col-lg-12 input-group">
                              <input class="form-control numeric-only" type="text" name="txtTelefonoEdit2" id="txtTelefonoEdit2" minlength="7" maxlength="10" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" placeholder="Ej. 123 456 7890">
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="row">
                        <div class="col-lg-12">
                          <label for="usr">E-mail*:</label>
                          <div class="row">
                            <div class="col-lg-12 input-group">
                              <input class="form-control alphaNumeric-only" type="email" name="txtEmailSucursalEdit" id="txtEmailSucursalEdit" required maxlength="100" placeholder="Ej. ejemplo@dominio.com" onchange="validarCorreo(this.value, 'txtEmailSucursalEdit', 'invalid-emailSucursalEdit');">
                              <div class="invalid-feedback" id="invalid-emailSucursalEdit">La dirección debe tener un email.</div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="row">
                        <div class="col-lg-12">
                          <label for="usr">Calle:*</label>
                          <div class="row">
                            <div class="col-lg-12 input-group">
                              <input class="form-control" type="text" name="txtCalleSucursalEdit" id="txtCalleSucursalEdit" required maxlength="255" placeholder="Ej. Av. México" onkeyup="validEmptyInput('txtCalleSucursalEdit', 'invalid-calleSucursalEdit', 'La dirección debe tener una calle.')">
                              <div class="invalid-feedback" id="invalid-calleSucursalEdit">La dirección debe tener una calle.</div>
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
                              <input class="form-control alphaNumeric-only" type="text" name="txtNumExtSucursalEdit" id="txtNumExtSucursalEdit" required maxlength="10" placeholder="Ej. 2353 A" onkeyup="validEmptyInput('txtNumExtSucursalEdit', 'invalid-numExtSucursalEdit', 'La dirección debe tener un número exterior.')">
                              <div class="invalid-feedback" id="invalid-numExtSucursalEdit">La dirección debe tener un número exterior.</div>
                            </div>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <label for="usr">Número interior:</label>
                          <div class="row">
                            <div class="col-lg-12 input-group">
                              <input class="form-control alphaNumeric-only" type="text" name="txtNumIntSucursalEdit" id="txtNumIntSucursalEdit" maxlength="10" placeholder="Ej. 524">
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="row">
                        <div class="col-lg-12">
                          <label for="usr">Colonia:*</label>
                          <div class="row">
                            <div class="col-lg-12 input-group">
                              <input class="form-control" type="text" name="txtColoniaSucursalEdit" id="txtColoniaSucursalEdit" required maxlength="255" placeholder="Ej. Los Agaves" onkeyup="validEmptyInput('txtColoniaSucursalEdit', 'invalid-coloniaSucursalEdit', 'La dirección debe tener una colonia.')">
                              <div class="invalid-feedback" id="invalid-coloniaSucursalEdit">La dirección debe tener una colonia.</div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="row">
                        <div class="col-lg-12">
                          <label for="usr">Municipio:*</label>
                          <div class="row">
                            <div class="col-lg-12 input-group">
                              <input class="form-control" type="text" name="txtMunicipioSucursalEdit" id="txtMunicipioSucursalEdit" required maxlength="255" placeholder="Ej. Guadalajara" onkeyup="validEmptyInput('txtMunicipioSucursalEdit', 'invalid-municipioSucursalEdit', 'La direccion debe tener un municipio.')">
                              <div class="invalid-feedback" id="invalid-municipioSucursalEdit">La direccion debe tener un municipio.</div>
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
                              <select name="cmbPaisSucursalEdit" id="cmbPaisSucursalEdit" required onchange="cambioPais('cmbPaisSucursalEdit','invalid-paisSucursalEdit','cmbEstadoSucursal')">
                              </select>
                              <div class="invalid-feedback" id="invalid-paisSucursalEdit">La dirección debe tener un pais.</div>
                            </div>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <label for="usr">Estado:*</label>
                          <div class="row">
                            <div class="col-lg-12 input-group">
                              <select name="cmbEstadoSucursalEdit" id="cmbEstadoSucursalEdit" required>
                              </select>
                              <div class="invalid-feedback" id="invalid-estadoSucursalEdit">La dirección debe tener un estado.</div>
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
                              <input class="form-control numeric-only" type="text" name="txtCPSucursalEdit" id="txtCPSucursalEdit" required maxlength="5" placeholder="Ej. 52632" onchange="validarCP('txtCPSucursalEdit','invalid-cpSucursalEdit');">
                              <div class="invalid-feedback" id="invalid-cpSucursalEdit">La dirección debe tener un CP.</div>
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
              <button type="button" class="btnesp espAgregar float-right"><span class="ajusteProyecto" data-dismiss="modal" data-toggle="modal" data-target="#eliminar_Sucursal">Eliminar</span></button>
              <button type="button" class="btnesp first espCancelar" data-dismiss="modal" id="BtnCon"><span class="ajusteProyecto">Cancelar</span></button>
              <button type="button" class="btnesp espAgregar float-right">
                <span class="ajusteProyecto" id="btnEditarSucursal">Guardar</span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!--END ADD MODAL SLIDE EDIT SUCURSALES-->

    <!--DELETE MODAL SLIDE SUCURSALES-->
    <div class="modal fade" id="eliminar_Sucursal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form action="" method="POST">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">¿Desea eliminar el registro de este?</h5>
              <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true" class="text-light">x</span>
              </button>
            </div>
            <div class="modal-body" style="font-size: 10 px!important; color: red;">Esta acción no podrá deshacerse</div>
            <div class="modal-footer">
              <button class="btn-custom btn-custom--border-blue btnCancelarActualizacion" type="button" data-dismiss="modal"><span class="ajusteProyecto">Cancelar</span></button>
              <button type="button" id="btnEliminarSucursales" data-dismiss="modal" class="btn-custom btn-custom--blue">
                <span class="ajusteProyecto">Eliminar</span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!--END DELETE MODAL SLIDE SUCURSALES-->

    <script src="../../../../js/sb-admin-2.min.js"></script>
    <script src="../../../../js/scripts.js"></script>
    <script src="../../../../js/notificaciones_timlid.js" charset="utf-8"></script>
    <script src="../../js/editar_paqueteria.js" charset="utf-8"></script>
    <script src="../../../../js/slimselect.min.js"></script>
    <script src="../../../../js/pestanas_paqueteriasEdit.js"></script>
    <script src="../../../../js/validaciones.js"></script>
    <script>
      loadAlertsNoti('alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
      setInterval(loadAlertsNoti, 10000, 'alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
    </script>
    <script type="text/javascript">
      _global.pkPaqueteria = '<?php echo $Paqueteria; ?>';
    </script>
</body>

</html>