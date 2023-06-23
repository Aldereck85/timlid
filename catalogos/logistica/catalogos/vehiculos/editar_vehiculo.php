<?php
session_start();
require_once '../../../../include/db-conn.php';

$Vehiculo = $_GET["vh"];

if (isset($_SESSION["Usuario"])) {
  $user = $_SESSION["Usuario"];
  $PKEmpresa = $_SESSION["IDEmpresa"];

  $stmt = $conn->prepare("SELECT empresa_id FROM vehiculos WHERE PKVehiculo = :id");
  $stmt->bindValue(':id', $Vehiculo, PDO::PARAM_INT);
  $stmt->execute();
  $row = $stmt->fetch();

  $GLOBALS["PKVehiculo"] = $row['empresa_id'];

  if ($GLOBALS["PKVehiculo"] != $PKEmpresa) {
    header("location:../../../logistica/catalogos/vehiculos/");
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
  <title>Timlid | Editar vehículo</title>

  <!-- ESTILOS -->
  <link href="../../../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../../../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../../../css/slimselect.min.css" rel="stylesheet">
  <link href="../../style/agregar_entrada.css" rel="stylesheet">
  <link href="../../style/pestanas_vehiculosEdit.css" rel="stylesheet">
  <link href="../../../../css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="../../../../vendor/datatables/responsive.dataTables.css" rel="stylesheet">
  <link href="../../../../vendor/datatables/buttons.dataTables.css" rel="stylesheet">
  <link href="../../../../css/stylesNewTable.css" rel="stylesheet">
  <link href="../../../../css/styles.css" rel="stylesheet">
  <link href="../../../../css/croppie.css" rel="stylesheet">
  <link href="../../../../css/lobibox.min.css" rel="stylesheet">
  <link href="../../../../css/sweetalert2.css" rel="stylesheet">
  <link href="../../../../css/notificaciones.css" rel="stylesheet">
  <link href="../../style/disabled.css" rel="stylesheet">
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
        $icono = '../../../../img/icons/vehiculos.svg';
        $titulo = 'Editar vehículo';
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
                  <a id="CargarDatosVehiculo" class="nav-link" onclick="CargarDatosVehiculo(window.location.href = 'editar_vehiculo.php?vh='+_global.PKVehiculo)">
                    Datos del vehículo
                  </a>
                </li>
                <li class="nav-item">
                  <a id="CargarDatosCombustible" class="nav-link" onclick="SeguirDatosCombustible(_global.PKVehiculo)">
                    Combustible
                  </a>
                </li>
                <li class="nav-item">
                  <a id="CargarDatosPolizaSeguro" class="nav-link" onclick="SeguirPolizaSeguro(_global.PKVehiculo)">
                    Póliza de seguro
                  </a>
                </li>
                <li class="nav-item">
                  <a id="CargarDatosServicios" class="nav-link" onclick="SeguirDatosServicios(_global.PKVehiculo)">
                    Servicios
                  </a>
                </li>
                <li class="nav-item">
                  <a id="CargarDatosPrestamos" class="nav-link"
                    onclick="SeguirBitacoraPrestamos(_global.PKVehiculo)">
                    Bitácora de préstamos
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

    <script src="../../../../js/sb-admin-2.min.js"></script>
    <script src="../../../../js/scripts.js"></script>
    <script src="../../../../js/notificaciones_timlid.js" charset="utf-8"></script>
    <script src="../../js/editar_vehiculo.js" charset="utf-8"></script>
    <script src="../../../../js/slimselect.min.js"></script>
    <script src="../../../../js/pestanas_vehiculosEdit.js"></script>
    <script src="../../../../js/validaciones.js"></script>
    <script>
      loadAlertsNoti('alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
      setInterval(loadAlertsNoti, 10000, 'alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
    </script>
    <script type="text/javascript">
      _global.PKVehiculo = '<?php echo $Vehiculo; ?>';
    </script>
</body>

</html>


<!--ADD MODAL SLIDE EDIT CARGA COMBUSTIBLE-->
<div class="modal fade right" id="editar_CargaCombustible" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
  <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">
    <div class="modal-content">
      <form action="" method="POST" id="formDatosCombustibleEdit">
        <!--<input type="hidden" name="idProyectoA" value="">-->
        <div class="modal-header">
          <h4 class="modal-title w-100" id="myModalLabel">Editar Carga de combustible</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">x</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <div class="row">
              <div class="col-lg-12">
                <label for="usr">Fecha de carga:*</label>
                <div class="row">
                  <div class="col-lg-12 input-group">
                    <input type="date" class="form-control" name="txtFechaCargaEdit" id="txtFechaCargaEdit" required onchange="validEmptyInput('txtFechaCargaEdit', 'invalid-fechaCargaEdit', 'La carga de combustible debe tener una fecha.')">
                    <div class="invalid-feedback" id="invalid-fechaCargaEdit">La carga de combustible debe tener una fecha.</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <div class="col-lg-12">
                <label for="usr">Cantidad:*</label>
                <div class="row">
                  <div class="col-lg-12 input-group">
                    <input class="form-control numericDecimal-only" type="text" name="txtCantidadCargaEdit" id="txtCantidadCargaEdit" required min="0" maxlength="6" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" placeholder="10.00" onchange="validEmptyInput('txtCantidadCargaEdit', 'invalid-cantidadCargaEdit', 'La carga de combustible debe de tener una cantidad.')">
                    <span class="input-group-addon" style="width:100px">
                      <select name="cmbUnidadMedidaLiquidoEdit" id="cmbUnidadMedidaLiquidoEdit" required>
                      </select>
                    </span>
                    <div class="invalid-feedback" id="invalid-cantidadCargaEdit">La carga de combustible debe de tener una cantidad.</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <div class="col-lg-12">
                <label for="usr">Costo unitario:*</label>
                <div class="row">
                  <div class="col-lg-12 input-group">
                    <input class="form-control numericDecimal-only" type="text" name="txtPrecioCargaEdit" id="txtPrecioCargaEdit" required min="0" maxlength="13" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" placeholder="Ej. 30.00" onchange="validEmptyInput('txtPrecioCargaEdit', 'invalid-precioCargaEdit', 'La carga de combustible debe de tener un costo unitario.')">
                    <span class="input-group-addon" style="width:100px">
                      <select name="cmbMonedaPrecioEdit" id="cmbMonedaPrecioEdit" required>
                      </select>
                    </span>
                    <div class="invalid-feedback" id="invalid-precioCargaEdit">La carga de combustible debe de tener un costo unitario.</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <div class="col-lg-12">
                <label for="usr">Odometro:*</label>
                <div class="row">
                  <div class="col-lg-12 input-group">
                    <input class="form-control numeric-only" type="text" name="txtOdometroCargaEdit" id="txtOdometroCargaEdit" required min="0" maxlength="5" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" placeholder="Ej. 30.00" onchange="validEmptyInput('txtOdometroCargaEdit', 'invalid-odometroCargaEdit', 'La carga de combustible debe de tener la medida del odometro.')">
                    <div class="invalid-feedback" id="invalid-odometroCargaEdit">La carga de combustible debe de tener la medida del odometro.</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <div class="col-lg-12">
                <label for="usr">Tanque lleno:*</label>
                <div class="form-check form-switch">
                  <input class="form-check-input" type="checkbox" id="cbxTanqueLlenoEdit" name="cbxTanqueLlenoEdit" onclick="checkTanqueLlenoEdit()">
                  <label class="form-check-label" for="cbxTanqueLlenoEdit" id="txtTanqueLlenoEdit">No</label>
                </div>
              </div>
            </div>
          </div>
          <p>* Campos requeridos</p>

        </div>
        <div class="modal-footer justify-content-center">
          <button type="button" class="btn-custom btn-custom--blue" data-dismiss="modal" data-toggle="modal" data-target="#eliminar_CargaCombustible">Eliminar
          </button>
          <button type="button" class="btn-custom btn-custom--border-blue" data-dismiss="modal" id="">
            Cancelar
          </button>
          <button type="button" class="btn-custom btn-custom--blue" data-dismiss="modal" id="btnEditarCarga">Guardar cambios
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
<!--END ADD MODAL SLIDE EDIT CARGA COMBUSTIBLE-->

<!--DELETE MODAL SLIDE CARGA COMBUSTIBLE-->
<div class="modal fade" id="eliminar_CargaCombustible" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
          <button type="button" onclick="eliminarCargaCombustible()" data-dismiss="modal" class="btn-custom btn-custom--blue">
            <span class="ajusteProyecto">Eliminar</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
<!--END DELETE MODAL SLIDE CARGA COMBUSTIBLE-->

<!--ADD MODAL SLIDE EDIT SERVICIO-->
<div class="modal fade right" id="editar_Servicios" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
  <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">
    <div class="modal-content">
      <form id="formDatosServiciosEdit">
        <!--<input type="hidden" name="idProyectoA" value="">-->
        <div class="modal-header">
          <h4 class="modal-title w-100" id="myModalLabel">Editar Servicio</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">x</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <div class="row">
              <div class="col-lg-12">
                <label for="usr">Servicio:*</label>
                <div class="row">
                  <div class="col-lg-12 input-group">
                    <input type="text" class="form-control" name="txtServicioEdit" id="txtServicioEdit" required onchange="validEmptyInput('txtServicioEdit', 'invalid-servicioEdit', 'El servicio debe de tener un nombre.')" placeholder="Ej. Afinación">
                    <div class="invalid-feedback" id="invalid-servicioEdit">El servicio debe de tener un nombre.</div>
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
                    <textarea type="text" class="form-control" name="txtDescripcionEdit" id="txtDescripcionEdit" required onchange="validEmptyInput('txtDescripcionEdit', 'invalid-descripcionEdit', 'El servicio debe de tener una descripcón.')" placeholder="Descripción del servicio"></textarea>
                    <div class="invalid-feedback" id="invalid-descripcionEdit">El servicio debe de tener una descripcón.</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <div class="col-lg-12">
                <label for="usr">Lugar:*</label>
                <div class="row">
                  <div class="col-lg-12 input-group">
                    <input type="text" class="form-control" name="txtLugarEdit" id="txtLugarEdit" required onchange="validEmptyInput('txtLugarEdit', 'invalid-lugarEdit', 'El servicio debe de tener un lugar donde se realizó.')" placeholder="Ej. Av. México 0001, Guadalaja, Jalisco">
                    <div class="invalid-feedback" id="invalid-lugarEdit">El servicio debe de tener un lugar donde se realizó.</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <div class="col-lg-12">
                <label for="usr">Tipo de servicio:*</label>
                <div class="row">
                  <div class="col-lg-12 input-group">
                    <select name="cmbTipoServicioEdit" id="cmbTipoServicioEdit" required>
                    </select>
                    <div class="invalid-feedback" id="invalid-tipoServicioEdit">El servicio debe tener un tipo.</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <div class="col-lg-12">
                <label for="usr">Costo:*</label>
                <div class="row">
                  <div class="col-lg-12 input-group">
                    <input class="form-control numericDecimal-only" type="text" name="txtCostoServicioEdit" id="txtCostoServicioEdit" required min="0" maxlength="12" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" placeholder="Ej. 3000.00" onchange="validEmptyInput('txtCostoServicioEdit', 'invalid-costoServicioEdit', 'El servicio debe de tener un costo.')">
                    <span class="input-group-addon" style="width:100px">
                      <select name="cmbMonedaCostoServicioEdit" id="cmbMonedaCostoServicioEdit" required>
                      </select>
                    </span>
                    <div class="invalid-feedback" id="invalid-costoServicioEdit">El servicio debe de tener un costo.</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <div class="col-lg-12">
                <a style="cursor:pointer; text-decoration:none; color:#15589b;" onclick="descargarPDFServicio()"><i class="fas fa-cloud-download-alt" id="btnExportPermissions"></i>Descargar PDF</a>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <div class="col-lg-12">
                <label for="usr">Cambiar PDF:</label>
                <div class="d-flex justify-content-center">
                  <div class="btnesp espAgregar">
                    <input type="file" id="inptFile" name="inptFile" accept="application/pdf">
                  </div>
                </div>
              </div>
            </div>
          </div>
          <p>* Campos requeridos</p>

        </div>
        <div class="modal-footer justify-content-center">
          <button type="button" class="btn-custom btn-custom--blue" data-dismiss="modal" data-toggle="modal" data-target="#eliminar_Servicio">Eliminar
          </button>
          <button type="button" class="btn-custom btn-custom--border-blue" data-dismiss="modal" id="">
            Cancelar
          </button>
          <button type="button" class="btn-custom btn-custom--blue" data-dismiss="modal" id="btnEditarServicio">Guardar cambios
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
<!--END ADD MODAL SLIDE EDIT SERVICIO-->

<!--DELETE MODAL SLIDE SERVICIO-->
<div class="modal fade" id="eliminar_Servicio" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
          <button type="button" onclick="eliminarServicio()" data-dismiss="modal" class="btn-custom btn-custom--blue">
            <span class="ajusteProyecto">Eliminar</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
<!--END DELETE MODAL SLIDE SERVICIO-->

<!--ADD MODAL SLIDE ADD PRESTAMO DE VEHICULO-->
<div class="modal fade bd-example-modal-lg" id="añadir_Prestamo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <form id="formDatosPrestamosAdd">
        <!--<input type="hidden" name="idProyectoA" value="">-->
        <div class="modal-header">
          <h4 class="modal-title w-100" id="myModalLabel">Añadir Préstamo de Vehículo</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">x</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <div class="row">
              <div class="col-lg-4 input-group">
                <div class="row">
                  <label for="usr">Submarca:</label>
                  <div class="col-lg-12 input-group">
                    <h4 id="ModalAddSubmarca"></h4>
                  </div>
                </div>
              </div>
              <div class="col-lg-4 input-group">
                <div class="row">
                  <label for="usr">Marca:</label>
                  <div class="col-lg-12 input-group">
                    <h4 id="ModalAddMarca"></h4>
                  </div>
                </div>
              </div>
              <div class="col-lg-4 input-group">
                <div class="row">
                  <label for="usr">Modelo:</label>
                  <div class="col-lg-12 input-group">
                    <h4 id="ModalAddModelo"></h4>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <br>
          <div class="form-group">
            <div class="row">
              <div class="col-lg-4 input-group">
                <div class="row">
                  <label for="usr">Placas:</label>
                  <div class="col-lg-12 input-group">
                    <h4 id="ModalAddPlacas"></h4>
                  </div>
                </div>
              </div>
              <div class="col-lg-4 input-group">
                <div class="row">
                  <label for="usr">Color:</label>
                  <div class="col-lg-12 input-group">
                    <h4 id="ModalAddColor"></h4>
                  </div>
                </div>
              </div>
              <div class="col-lg-4 input-group">
                <div class="row">
                  <label for="usr">Combustible que usa:</label>
                  <div class="col-lg-12 input-group">
                    <h4 id="ModalAddCombustible"></h4>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <br><br>
          <div class="form-group">
            <div class="row">
              <div class="col-lg-4 input-group">
                <div class="row">
                  <label for="usr">Empleado:*</label>
                  <div class="col-lg-10 input-group">
                    <select name="cmbEmpleadoAdd" id="cmbEmpleadoAdd" required>
                    </select>
                    <div class="invalid-feedback" id="invalid-empleadoAdd">El préstamo debe tener un empleado.</div>
                  </div>
                </div>
              </div>
              <div class="col-lg-4 input-group">
                <div class="row">
                  <label for="usr">Motivo del préstamo:*</label>
                  <div class="col-lg-12 input-group">
                    <input type="text" class="form-control alphaNumericNDot-only" name="txtMotivoPrestamoAdd" id="txtMotivoPrestamoAdd" maxlength="140" required onchange="validEmptyInput('txtMotivoPrestamoAdd', 'invalid-motivoPrestamoAdd', 'El préstamo debe de tener un motivo.')">
                    <div class="invalid-feedback" id="invalid-motivoPrestamoAdd">El préstamo debe de tener un motivo.</div>
                  </div>
                </div>
              </div>
              <div class="col-lg-4 input-group">
                <div class="row">
                  <label for="usr">Nivel de combustible*:</label>
                  <div class="col-lg-12 input-group">
                    <select name="cmbCombustibleAdd" id="cmbCombustibleAdd">
                      <option value="1">lleno</option>
                      <option value="2">3/4</option>
                      <option value="3">1/2</option>
                      <option value="4">1/4</option>
                      <option value="5">Reserva</option>
                    </select>
                    <div class="invalid-feedback" id="invalid-nivelCombustibleAdd">Debe seleccionar un nivel de combustible.</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <br>
          <div class="form-group">
            <div class="row">
              <div class="col-lg-4 input-group">
                <div class="row">
                  <label for="usr">Autoriza:</label>
                  <div class="col-lg-10 input-group">
                    <select name="cmbAutorizaAdd" id="cmbAutorizaAdd">
                      <option disabled selected value="f">Selecciona un empleado</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="col-lg-4">
                <label for="usr">Kilometraje de salida:*</label>
                <div class="row">
                  <div class="col-lg-12 input-group">
                    <input type="text" class="form-control numeric-only" name="txtModalAddkilometraje" id="txtModalAddkilometraje" maxlength="11" required onchange="validEmptyInput('txtModalAddkilometraje', 'invalid-KilometrajeAdd', 'El Kilometraje debe ser registrado.')">
                    <div class="invalid-feedback" id="invalid-KilometrajeAdd">El Kilometraje debe ser registrado.</div>
                  </div>
                </div>
              </div>
              <div class="col-lg-4">
                <label for="usr">Fecha de préstamo:*</label>
                <div class="row">
                  <div class="col-lg-12 input-group">
                    <input type="date" class="form-control" name="txtFechaPrestamoAdd" id="txtFechaPrestamoAdd" required onchange="validEmptyInput('txtFechaPrestamoAdd', 'invalid-fechaPrestamoAdd', 'El préstamo debe de tener una fecha.')">
                    <div class="invalid-feedback" id="invalid-fechaPrestamoAdd">El préstamo debe de tener una fecha.</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <p>* Campos requeridos</p>
        </div>
        <div class="modal-footer justify-content-center">
          <button type="button" class="btn-custom btn-custom--border-blue" data-dismiss="modal">
            Cancelar
          </button>
          <button type="button" class="btn-custom btn-custom--blue" id="btnAñadirPrestamo">Guardar
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
<!--END ADD MODAL SLIDE ADD PRESTAMO DE VEHICULO-->

<!--ADD MODAL SLIDE EDIT PRESTAMO DE VEHICULO-->
<div class="modal fade bd-example-modal-lg" id="editar_Prestamos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <form id="formDatosPrestamosEdit">
        <!--<input type="hidden" name="idProyectoA" value="">-->
        <div class="modal-header">
          <h4 class="modal-title w-100" id="myModalLabel">Editar Préstamo de vehículo</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">x</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <div class="row">
              <div class="col-lg-8 input-group">
                <div class="row">
                  <label for="usr">Estatus:</label>
                  <div class="col-lg-12 input-group">
                    <h3 id="lblEstatus"></h4>
                  </div>
                </div>
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-lg-4 input-group">
                <div class="row">
                  <label for="usr">Submarca:</label>
                  <div class="col-lg-12 input-group">
                    <h4 id="ModalEditSubmarca"></h4>
                  </div>
                </div>
              </div>
              <div class="col-lg-4 input-group">
                <div class="row">
                  <label for="usr">Marca:</label>
                  <div class="col-lg-12 input-group">
                    <h4 id="ModalEditMarca"></h4>
                  </div>
                </div>
              </div>
              <div class="col-lg-4 input-group">
                <div class="row">
                  <label for="usr">Modelo:</label>
                  <div class="col-lg-12 input-group">
                    <h4 id="ModalEditModelo"></h4>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <br>
          <div class="form-group">
            <div class="row">
              <div class="col-lg-4 input-group">
                <div class="row">
                  <label for="usr">Placas:</label>
                  <div class="col-lg-12 input-group">
                    <h4 id="ModalEditPlacas"></h4>
                  </div>
                </div>
              </div>
              <div class="col-lg-4 input-group">
                <div class="row">
                  <label for="usr">Color:</label>
                  <div class="col-lg-12 input-group">
                    <h4 id="ModalEditColor"></h4>
                  </div>
                </div>
              </div>
              <div class="col-lg-4 input-group">
                <div class="row">
                  <label for="usr">Combustible que usa:</label>
                  <div class="col-lg-12 input-group">
                    <h4 id="ModalEditCombustible"></h4>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <br><br>
          <div class="form-group">
            <div class="row">
              <div class="col-lg-4 input-group">
                <div class="row">
                  <label for="usr">Empleado:*</label>
                  <div class="col-lg-10 input-group">
                    <select name="cmbEmpleadoEdit" id="cmbEmpleadoEdit" required>
                    </select>
                    <div class="invalid-feedback" id="invalid-empleadoEdit">El préstamo debe tener un empleado.</div>
                  </div>
                </div>
              </div>
              <div class="col-lg-4 input-group">
                <div class="row">
                  <label for="usr">Motivo del préstamo:*</label>
                  <div class="col-lg-12 input-group">
                    <input type="text" class="form-control alphaNumericNDot-only" name="txtMotivoPrestamoEdit" id="txtMotivoPrestamoEdit" maxlength="140" required onchange="validEmptyInput('txtMotivoPrestamoEdit', 'invalid-motivoPrestamoEdit', 'El préstamo debe de tener un motivo.')">
                    <div class="invalid-feedback" id="invalid-motivoPrestamoEdit">El préstamo debe de tener un motivo.</div>
                  </div>
                </div>
              </div>
              <div class="col-lg-4 input-group">
                <div class="row">
                  <label for="usr">Nivel de combustible*:</label>
                  <div class="col-lg-12 input-group">
                    <select name="cmbCombustibleEdit" id="cmbCombustibleEdit">
                      <option value="1">lleno</option>
                      <option value="2">3/4</option>
                      <option value="3">1/2</option>
                      <option value="4">1/4</option>
                      <option value="5">Reserva</option>
                    </select>
                    <div class="invalid-feedback" id="invalid-nivelCombustibleEdit">Debe seleccionar un nivel de combustible.</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <br>
          <div class="form-group">
            <div class="row">
              <div class="col-lg-4 input-group">
                <div class="row">
                  <label for="usr">Autoriza:</label>
                  <div class="col-lg-10 input-group">
                    <select name="cmbAutorizaEdit" id="cmbAutorizaEdit">
                      
                    </select>
                  </div>
                </div>
              </div>
              <div class="col-lg-4">
                <label for="usr">Kilometraje de salida:*</label>
                <div class="row">
                  <div class="col-lg-12 input-group">
                    <input type="text" class="form-control numeric-only" name="txtModalEditkilometraje" id="txtModalEditkilometraje" maxlength="11" required onchange="validEmptyInput('txtModalEditkilometraje', 'invalid-KilometrajeEdit', 'El Kilometraje debe ser registrado.')">
                    <div class="invalid-feedback" id="invalid-KilometrajeEdit">El Kilometraje debe ser registrado.</div>
                  </div>
                </div>
              </div>
              <div class="col-lg-4">
                <label for="usr">Fecha de préstamo:*</label>
                <div class="row">
                  <div class="col-lg-12 input-group">
                    <input type="date" class="form-control" name="txtFechaPrestamoEdit" id="txtFechaPrestamoEdit" required onchange="validEmptyInput('txtFechaPrestamoEdit', 'invalid-fechaPrestamoEdit', 'El préstamo debe de tener una fecha.')">
                    <div class="invalid-feedback" id="invalid-fechaPrestamoEdit">El préstamo debe de tener una fecha.</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <p>* Campos requeridos</p>
        </div>
        <div class="modal-footer justify-content-center">
          <button type="button" class="btn-custom btn-custom--border-blue bloque_botonesModal" data-dismiss="modal" data-toggle="modal" data-target="#eliminar_PrestamoVehiculo">Eliminar
          </button>
          <button type="button" class="btn-custom btn-custom--blue bloque_botonesModal" data-dismiss="modal" id="btn_cerrarPrestamo_pantallaEdit">Cerrar Préstamo
          </button>
          <button type="button" class="btn-custom btn-custom--border-blue" data-dismiss="modal">
            Cancelar
          </button>
          <button type="button" class="btn-custom btn-custom--blue bloque_botonesModal" id="btnEditarPrestamo">Guardar
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
<!--END ADD MODAL SLIDE EDIT PRESTAMO DE VEHICULO-->

<!--DELETE MODAL SLIDE PRESTAMO DE VEHICULO-->
<div class="modal fade" id="eliminar_PrestamoVehiculo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
          <button type="button" onclick="eliminarPrestamoVehiculo()" data-dismiss="modal" class="btn-custom btn-custom--blue">
            <span class="ajusteProyecto">Eliminar</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
<!--END DELETE MODAL SLIDE PRESTAMO DE VEHICULO-->

<!--CLOSE MODAL SLIDE PRESTAMO DE VEHICULO-->
<div class="modal fade" id="cerrar_PrestamoVehiculo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form id="form_cerrarPrestamo">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">¿Desea cerrar el prestamo del vehículo?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true" class="text-light">x</span>
          </button>
        </div>
        <div class="modal-body" style="font-size: 10 px!important;">
          <div class="form-group">
            <div class="row">
              <div class="col-lg-5 input-group">
                <div class="row">
                  <label for="usr">Nivel de combustible final*:</label>
                  <div class="col-lg-12 input-group">
                    <select name="cmbCombustibleClose" id="cmbCombustibleClose" required>
                      <option value="1">lleno</option>
                      <option value="2">3/4</option>
                      <option value="3">1/2</option>
                      <option value="4">1/4</option>
                      <option value="5">Reserva</option>
                    </select>
                    <div class="invalid-feedback" id="invalid-nivelCombustibleClose">Debe seleccionar un nivel de combustible.</div>
                  </div>
                </div>
              </div>
              <div class="col-lg-5">
                <label for="usr">Kilometraje de salida:*</label>
                <div class="row">
                  <div class="col-lg-12 input-group">
                    <input type="text" class="form-control numeric-only" name="txtModalClosekilometraje" id="txtModalClosekilometraje" maxlength="11" required onchange="validEmptyInput('txtModalClosekilometraje', 'invalid-KilometrajeClose', 'El Kilometraje debe ser registrado.')">
                    <div class="invalid-feedback" id="invalid-KilometrajeClose">El Kilometraje debe ser registrado.</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        <p>* Campos requeridos</p>
        </div>
        <div class="modal-footer">
          <button class="btn-custom btn-custom--border-blue btnCancelarActualizacion" type="button" data-dismiss="modal"><span class="ajusteProyecto">Cancelar</span></button>
          <button type="button" id="btn_cerrarPrestamoVehiculo" class="btn-custom btn-custom--blue">
            <span class="ajusteProyecto">Cerrar préstamo</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
<!--END CLOSE MODAL SLIDE PRESTAMO DE VEHICULO-->