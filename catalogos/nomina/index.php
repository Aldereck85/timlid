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
  <title>Timlid | Nómina</title>

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
  <style type="text/css">
    .loader {
      position: fixed;
      left: 0px;
      top: 0px;
      width: 100%;
      height: 100%;
      z-index: 9999;
      opacity: 0.6;
      background: url("../../img/timdesk/Preloader.gif") 50% 50% no-repeat rgb(249, 249, 249);
    }

    /* The container */
    .container_esp {
      display: block;
      position: relative;
      bottom: 7px;
      left: 9%;
      cursor: pointer;
      font-size: 22px;
      -webkit-user-select: none;
      -moz-user-select: none;
      -ms-user-select: none;
      user-select: none;
    }

    /* Hide the browser's default checkbox */
    .container_esp input {
      position: absolute;
      opacity: 0;
      cursor: pointer;
      height: 0;
      width: 0;
    }

    /* Create a custom checkbox */
    .checkmark {
      position: absolute;
      top: 0;
      left: 0;
      height: 25px;
      width: 25px;
      background-color: #eee;
    }

    /* On mouse-over, add a grey background color */
    .container_esp:hover input~.checkmark {
      background-color: #ccc;
    }

    /* When the checkbox is checked, add a blue background */
    .container_esp input:checked~.checkmark {
      background-color: #2196F3;
    }

    /* Create the checkmark/indicator (hidden when not checked) */
    .checkmark:after {
      content: "";
      position: absolute;
      display: none;
    }

    /* Show the checkmark when checked */
    .container_esp input:checked~.checkmark:after {
      display: block;
    }

    /* Style the checkmark/indicator */
    .container_esp .checkmark:after {
      left: 10px;
      top: 6px;
      width: 5px;
      height: 10px;
      border: solid white;
      border-width: 0 3px 3px 0;
      -webkit-transform: rotate(45deg);
      -ms-transform: rotate(45deg);
      transform: rotate(45deg);
    }

    .click {
      cursor: pointer;
    }
  </style>
</head>

<body id="page-top">
  <div id=loader></div>
  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <?php
    $icono = '../../img/icons/puestos.svg';
    $titulo = "Nomina";
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
                <table class="table" id="tblNominas" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Nómina</th>
                      <th>Sucursal</th>
                      <th>Periodicidad</th>
                      <th>Tipo</th>
                      <th>Fecha inicio</th>
                      <th>Confidencial</th>
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

  <!-- Scroll to Top Button-->


  <!--ADD MODAL-->
  <div class="modal fade right" id="agregar_nomina" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
    <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">
      <div class="modal-content">
        <form action="#" method="POST" id="agregarNominaForm">
          <!--<input type="hidden" name="idProyectoA" value="">-->
          <div class="modal-header">
            <h4 class="modal-title w-100" id="myModalLabel">Agregar nómina</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">x</span>
            </button>
          </div>
          <div class="modal-body">

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

            <span class="mostrarPeriodo" style="display: none;">
              <div class="form-group">
                <label for="usr">Fecha de pago*:</label>
                <input type="date" class="form-control" name="txtFechaPago" id="txtFechaPago" value="<?= date("Y-m-d") ?>" required>
              </div>
            </span>
            <div class="form-group">
              <label for="usr">Fecha de inicio*:</label>
              <input type="date" class="form-control" name="txtFechaInicio" id="txtFechaInicio" value="" required>
            </div>
            <span class="mostrarPeriodo" style="display: none;">
              <div class="form-group">
                <label for="usr">Fecha final*:</label>
                <input type="date" class="form-control" name="txtFechaFin" id="txtFechaFin" value="" required>
              </div>
            </span>
            <div class="form-group">
              <label><input type="checkbox" id="cbxNominaConfidencial"> Nómina confidencial</label>
            </div>
            <div class="form-group" id="ajustarMesCalendarioMostrar" style="display: none;">
              <label><input type="checkbox" id="ajustarMesCalendario"> Ajustar mes calendario</label>
            </div>
            <label style="color:#006dd9;font-size: 13px;"> (*) Campos requeridos</label>
            <label style="color:#006dd9;font-size: 13px;" id="mostrarPeriodoOrdinario"> Al guardar la nómina se agregarán todos los periodos del año en base a la fecha de inicio.</label>
            <label style="color:#006dd9;font-size: 13px; display: none;" id="mostrarPeriodoExtraOrdinario"> En período extraordinario sólo se genera el periodo en base a la fecha de inicio y fecha final.</label>
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
            <div class="form-group">
              <label><input type="checkbox" id="cbUltimaNominaEdit" value="1"> Última nómina del mes</label>
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
  <script src="./js/nomina_principal.js"></script>

  <script>
    function verPeriodos(idNomina, tipoNomina) {
      let url;
      if (tipoNomina == 1) {
        url = "periodos.php";
      } else {
        url = "nomina_extraordinaria.php";
      }

      $().redirect(url, {
        'idNomina': idNomina
      });
    }

    function obtenerVer(idNomina, tipoNomina) {
      let url;
      if (tipoNomina == 1) {
        url = "nomina.php";
      } else {
        url = "nomina_extraordinaria.php";
      }

      $().redirect(url, {
        'idNomina': idNomina
      });
    }

    $("#cmbTipo").change(function() {
      let idTipo = $("#cmbTipo").val();
      let tipoPeriodo = $("#cmbPeriodo").val();

      if (idTipo == 1 || idTipo == null) {
        $("#mostrarPeriodoOrdinario").css('display', 'block');
        $("#mostrarPeriodoExtraOrdinario").css('display', 'none');
        $(".mostrarPeriodo").css('display', 'none');
      } else {
        $("#mostrarPeriodoOrdinario").css('display', 'none');
        $("#mostrarPeriodoExtraOrdinario").css('display', 'block');
        $(".mostrarPeriodo").css('display', 'block');
      }

      if (tipoPeriodo == 3 && idTipo == 1) {
        $("#ajustarMesCalendarioMostrar").css('display', 'block');
      } else {
        $("#ajustarMesCalendarioMostrar").css('display', 'none');
      }

    });

    $("#cmbPeriodo").change(function() {
      let tipoPeriodo = $("#cmbPeriodo").val();
      let idTipo = $("#cmbTipo").val();

      if (tipoPeriodo == 3 && idTipo == 1) {
        $("#ajustarMesCalendarioMostrar").css('display', 'block');
      } else {
        $("#ajustarMesCalendarioMostrar").css('display', 'none');
      }
    });

    $("#btnAgregarNomina").click(function() {
      let idSucursal = $("#cmbSucursal").val();
      let idPeriodo = $("#cmbPeriodo").val();
      let idTipo = $("#cmbTipo").val();
      let fechaPago = $("#txtFechaPago").val();
      let fechaIni = $("#txtFechaInicio").val();
      let fechaFin = $("#txtFechaFin").val();
      let token = $("#csr_token_UT5JP").val();
      let nominaConfidencial;

      if ($('#cbxNominaConfidencial').is(":checked")) {
        nominaConfidencial = 1;
      } else {
        nominaConfidencial = 0;
      }

      if (idSucursal < 1) {
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/notificacion_error.svg",
          msg: "¡Es necesario seleccionar una sucursal!",
        });
        return;
      }

      if (idPeriodo < 1) {
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/notificacion_error.svg",
          msg: "¡Es necesario seleccionar el periodo!",
        });
        return;
      }

      if (idTipo < 1) {
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/notificacion_error.svg",
          msg: "¡Es necesario seleccionar el tipo de nómina!",
        });
        return;
      }

      if (fechaIni.trim() == "") {
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/notificacion_error.svg",
          msg: "¡Es necesario seleccionar la fecha de inicio!",
        });
        return;
      }

      if (idTipo == 2) {
        if (fechaPago.trim() == "") {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/notificacion_error.svg",
            msg: "¡Es necesario seleccionar la fecha de pago!",
          });
          return;
        }

        if (fechaFin.trim() == "") {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/notificacion_error.svg",
            msg: "Es necesario seleccionar la fecha final o ingresar una fecha válida.",
          });
          return;
        }

        if (new Date(fechaIni).getTime() > new Date(fechaFin).getTime()) {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/notificacion_error.svg",
            msg: "¡La fecha final no puede ser menor a la fecha de inicio!",
          });
          return;
        }
      }

      $("#btnCancelarNomina").prop("disabled", true);
      $("#btnAgregarNomina").prop("disabled", true);

      let ajustarmescalendario;
      if ($('#ajustarMesCalendario').is(":checked")) {
        ajustarmescalendario = 0;
      } else {
        ajustarmescalendario = 1;
      }

      $("#loader").addClass("loader");

      $.ajax({
        type: 'POST',
        url: 'functions/agregar_Nomina.php',
        data: {
          idSucursal: idSucursal,
          idPeriodo: idPeriodo,
          idTipo: idTipo,
          fechaPago: fechaPago,
          fechaIni: fechaIni,
          fechaFin: fechaFin,
          csr_token_UT5JP: token,
          ajustarmescalendario: ajustarmescalendario,
          nominaConfidencial: nominaConfidencial
        },
        success: function(r) {

          if (r == "exito") {
            Lobibox.notify("success", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../img/timdesk/checkmark.svg",
              msg: "¡Nómina agregada!",
            });
            $('#tblNominas').DataTable().ajax.reload();
            $('#agregar_nomina').modal('hide');
          }
          if (r == "fallo") {
            Lobibox.notify("error", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../img/timdesk/notificacion_error.svg",
              msg: "¡Ocurrio un error, intentalo nuevamente!",
            });
          }
          if (r == "existe_nomina") {
            Lobibox.notify("warning", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../img/timdesk/notificacion_error.svg",
              msg: "Ya existe una nómina con esas características.",
            });
          }
          $("#btnCancelarNomina").prop("disabled", false);
          $("#btnAgregarNomina").prop("disabled", false);

          $(".loader").fadeOut("slow");
          $("#loader").removeClass("loader");
        },
        error: function() {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/notificacion_error.svg",
            msg: "¡Ocurrio un error, intentalo nuevamente!",
          });
          $("#btnCancelarNomina").prop("disabled", false);
          $("#btnAgregarNomina").prop("disabled", false);

          $(".loader").fadeOut("slow");
          $("#loader").removeClass("loader");
        }
      });

    });

    /*let idNominaEdit = 0;

    function obtenerEditar(idNomina) {
      idNominaEdit = idNomina;
      let token = $("#csr_token_UT5JP").val();

      selectSucursalEdit.enable();
      selectPeriodoEdit.enable();
      selectTipoEdit.enable();
      $("#txtFechaPagoEdit").prop("disabled", false);
      $("#txtFechaInicioEdit").prop("disabled", false);
      $("#txtFechaFinEdit").prop("disabled", false);
      $("#btnEditarNomina").prop("disabled", false);

      $.ajax({
        type: 'POST',
        url: 'functions/getDatosNomina.php',
        data: {
          idNomina: idNominaEdit,
          csr_token_UT5JP: token
        },
        success: function(r) {

          var datos = JSON.parse(r);

          if (datos.respuesta == "exito") {
            $("#txtNoNominaedit").val(datos.no_nomina);
            $("#cmbSucursalEdit").html(datos.sucursal);
            $("#cmbPeriodoEdit").html(datos.periodo);
            $("#cmbTipoEdit").html(datos.tipo);
            $("#txtFechaPagoEdit").val(datos.fecha_pago);
            $("#txtFechaInicioEdit").val(datos.fecha_inicio);
            $("#txtFechaFinEdit").val(datos.fecha_fin);

            if(datos.ultima_nomina == 1){
              $( "#cbUltimaNominaEdit" ).prop( "checked", true );
            }
            else{
              $( "#cbUltimaNominaEdit" ).prop( "checked", false );
            }

            if (datos.estatus == 2) {
              selectSucursalEdit.disable();
              selectPeriodoEdit.disable();
              selectTipoEdit.disable();
              $("#txtFechaPagoEdit").prop("disabled", true);
              $("#txtFechaInicioEdit").prop("disabled", true);
              $("#txtFechaFinEdit").prop("disabled", true);
              $("#btnEditarNomina").prop("disabled", true);
            }
          }
        },
        error: function() {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/notificacion_error.svg",
            msg: "Ocurrió un error, intentalo cargar la nómina nuevamente",
          });
          $('#editar_nomina').modal('hide');
        }
      });
    }

    $("#btnEditarNomina").click(function() {
      let idSucursal = $("#cmbSucursalEdit").val();
      let idPeriodo = $("#cmbPeriodoEdit").val();
      let idTipo = $("#cmbTipoEdit").val();
      let fechaPago = $("#txtFechaPagoEdit").val();
      let fechaIni = $("#txtFechaInicioEdit").val();
      let fechaFin = $("#txtFechaFinEdit").val();
      let token = $("#csr_token_UT5JP").val();
      let ultimaNomina;
      if($("#cbUltimaNominaEdit").is(':checked')){
        ultimaNomina = 1;
      }
      else{
        ultimaNomina = 0;
      }

      if (idSucursal < 1) {
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/notificacion_error.svg",
          msg: "¡Es necesario seleccionar una sucursal!",
        });
        return;
      }

      if (idPeriodo < 1) {
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/notificacion_error.svg",
          msg: "¡Es necesario seleccionar el periodo!",
        });
        return;
      }

      if (idTipo < 1) {
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/notificacion_error.svg",
          msg: "¡Es necesario seleccionar el tipo de nómina!",
        });
        return;
      }

      if (fechaPago.trim() == "") {
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/notificacion_error.svg",
          msg: "¡Es necesario seleccionar la fecha de pago!",
        });
        return;
      }

      if (fechaIni.trim() == "") {
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/notificacion_error.svg",
          msg: "¡Es necesario seleccionar la fecha de inicio!",
        });
        return;
      }

      if (fechaFin.trim() == "") {
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/notificacion_error.svg",
          msg: "¡Es necesario seleccionar la fecha final!",
        });
        return;
      }

      if (new Date(fechaIni).getTime() > new Date(fechaFin).getTime()) {
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/notificacion_error.svg",
          msg: "¡La fecha final no puede ser menor a la fecha de inicio!",
        });
        return;
      }

      $("#btnCancelarNominaEdit").prop("disabled", true);
      $("#btnEditarNomina").prop("disabled", true);

      $.ajax({
        type: 'POST',
        url: 'functions/editar_Nomina.php',
        data: {
          idNomina: idNominaEdit,
          idSucursal: idSucursal,
          idPeriodo: idPeriodo,
          idTipo: idTipo,
          fechaPago: fechaPago,
          fechaIni: fechaIni,
          fechaFin: fechaFin,
          csr_token_UT5JP: token,
          ultimaNomina: ultimaNomina
        },
        success: function(r) {

          if (r == "exito") {
            Lobibox.notify("success", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../img/timdesk/checkmark.svg",
              msg: "¡Nómina modificada!",
            });
            $('#tblNominas').DataTable().ajax.reload();
            $('#editar_nomina').modal('hide');
          } else {
            Lobibox.notify("error", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../img/timdesk/notificacion_error.svg",
              msg: "¡Ocurrio un error, intentalo nuevamente!",
            });
          }
          $("#btnCancelarNominaEdit").prop("disabled", false);
          $("#btnEditarNomina").prop("disabled", false);
        },
        error: function() {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/notificacion_error.svg",
            msg: "¡Ocurrio un error, intentalo nuevamente!",
          });
          $("#btnCancelarNominaEdit").prop("disabled", false);
          $("#btnEditarNomina").prop("disabled", false);
        }
      });

    });
*/

    function eliminarNomina(idNomina) {
      let token = $("#csr_token_UT5JP").val();

      const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
          actions: "d-flex justify-content-around",
          confirmButton: "btn-custom btn-custom--border-blue",
          cancelButton: "btn-custom btn-custom--blue",
        },
        buttonsStyling: false,
      });

      swalWithBootstrapButtons
        .fire({
          title: "¿Desea continuar?",
          text: "Se eliminará la nómina.",
          icon: "warning",
          showCancelButton: true,
          confirmButtonText: '<span class="verticalCenter">Eliminar nómina</span>',
          cancelButtonText: '<span class="verticalCenter">Cerrar</span>',
          reverseButtons: true,
        })
        .then((result) => {
          if (result.isConfirmed) {

            $.ajax({
              type: 'POST',
              url: 'functions/eliminar_Nomina.php',
              data: {
                idNomina: idNomina,
                csr_token_UT5JP: token
              },
              success: function(data) {

                if (data == "exito") {

                  Lobibox.notify("success", {
                    size: 'mini',
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: 'center top', //or 'center bottom'
                    icon: false,
                    img: '../../img/timdesk/checkmark.svg',
                    msg: "Se ha eliminado la nómina"
                  });

                  $('#tblNominas').DataTable().ajax.reload();

                } else if (data == "fallo-cancelacion") {
                  Lobibox.notify("error", {
                    size: 'mini',
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: 'center top', //or 'center bottom'
                    icon: false,
                    img: '../../img/timdesk/warning_circle.svg',
                    msg: "No puedes cancelar un nómina timbrada."
                  });

                } else {

                  Lobibox.notify("error", {
                    size: 'mini',
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: 'center top', //or 'center bottom'
                    icon: false,
                    img: '../../img/timdesk/warning_circle.svg',
                    msg: "Ocurrio un error, vuelva intentarlo."
                  });

                }
              }
            });



          } else if (
            /* Read more about handling dismissals below */
            result.dismiss === Swal.DismissReason.cancel
          ) {}
        });
    }

    $("#txtFechaInicio,#cmbPeriodo").change(function() {

      let fecha = new Date($("#txtFechaInicio").val());
      let periodo = $("#cmbPeriodo").val();
      let diasAgregar = 0;

      if (fecha == "" || fecha == null || periodo == "" || periodo == null) {
        return;
      }

      if (periodo == 1) {
        diasAgregar = 8;
      }
      if (periodo == 2) {
        diasAgregar = 15;
      }
      if (periodo == 3) {
        diasAgregar = 16;
      }
      if (periodo == 4) {
        diasAgregar = 31;
      }

      fecha.setDate(fecha.getDate() + diasAgregar);

      var day = ("0" + fecha.getDate()).slice(-2);
      var month = ("0" + (fecha.getMonth() + 1)).slice(-2);
      var today = fecha.getFullYear() + "-" + (month) + "-" + (day);


      $("#txtFechaFin").val(today);

    });

    $("#txtFechaInicioEdit,#cmbPeriodoEdit").change(function() {

      let fecha = new Date($("#txtFechaInicioEdit").val());
      let periodo = $("#cmbPeriodoEdit").val();
      let diasAgregar = 0;

      if (fecha == "" || fecha == null || periodo == "" || periodo == null) {
        return;
      }

      if (periodo == 1) {
        diasAgregar = 8;
      }
      if (periodo == 2) {
        diasAgregar = 15;
      }
      if (periodo == 3) {
        diasAgregar = 16;
      }
      if (periodo == 4) {
        diasAgregar = 31;
      }

      fecha.setDate(fecha.getDate() + diasAgregar);

      var day = ("0" + fecha.getDate()).slice(-2);
      var month = ("0" + (fecha.getMonth() + 1)).slice(-2);
      var today = fecha.getFullYear() + "-" + (month) + "-" + (day);


      $("#txtFechaFinEdit").val(today);

    });

    var selectSucursal = new SlimSelect({
      select: '#cmbSucursal',
      deselectLabel: '<span class="">✖</span>'
    });

    var selectPeriodo = new SlimSelect({
      select: '#cmbPeriodo',
      deselectLabel: '<span class="">✖</span>'
    });

    var selectTipo = new SlimSelect({
      select: '#cmbTipo',
      deselectLabel: '<span class="">✖</span>'
    });

    var selectSucursalEdit = new SlimSelect({
      select: '#cmbSucursalEdit',
      deselectLabel: '<span class="">✖</span>'
    });

    var selectPeriodoEdit = new SlimSelect({
      select: '#cmbPeriodoEdit',
      deselectLabel: '<span class="">✖</span>'
    });

    var selectTipoEdit = new SlimSelect({
      select: '#cmbTipoEdit',
      deselectLabel: '<span class="">✖</span>'
    });
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