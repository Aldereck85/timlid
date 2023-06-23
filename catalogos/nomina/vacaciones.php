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
  <title>Timlid | Vacaciones</title>

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
    .modal .modal-full-height {
      width: 500px;
    }
  </style>
</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <?php
    $icono = '../../img/icons/puestos.svg';
    $titulo = "Vacaciones";
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
                <table class="table" id="tblVacaciones" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Nombre</th>
                      <th>Primer Apellido</th>
                      <th>Segundo Apellido</th>
                      <th>CURP</th>
                      <th>RFC</th>
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
  <div class="modal fade right" id="vacaciones_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="overflow-y: auto !important;">
    <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
    <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">
      <div class="modal-content">
        <form action="#" method="POST" id="vacacionesForm">
          <!--<input type="hidden" name="idProyectoA" value="">-->
          <div class="modal-header">
            <h4 class="modal-title w-100" id="myModalLabel">Vacaciones</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">x</span>
            </button>
          </div>
          <input type="hidden" name="idEmpleadoVac" id="idEmpleadoVac" val="">
          <div class="modal-body">
            <div class="row" style="display: block;">
              <center>
                <button type="button" class="btn-custom btn-custom--border-blue" id="btnAcumuladoVacaciones" style="position: relative;right: 2%;"><span class="ajusteProyecto">Acumulado</span></button>
                <button type="button" class="btn-custom btn-custom--border-blue" id="btnMostrarVacaciones" style="position: relative;left: 2%;"><span class="ajusteProyecto">Periodos</span></button>
              </center>
            </div>

            <div id="acumulado">
              <br>
              <div class="table-responsive">
                <table class="table" id="tblVacacionesAcumuladas" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Año</th>
                      <th>Días</th>
                      <th>Restantes</th>
                      <th></th>
                    </tr>
                  </thead>
                </table>
              </div>
              <br>
              <button class="btn-table-custom btn-table-custom--blue" tabindex="0" aria-controls="tblVacaciones" type="button" id="agregarVacaciones"><i class="fas fa-plus-square"></i> Añadir vacaciones</button>
            </div>


            <div id="periodoDiv" style="display: none;">
              <br>
              <div class="table-responsive">
                <table class="table" id="tblVacacionesPeriodo" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Días</th>
                      <th>Inicio</th>
                      <th>Termino</th>
                      <th>Prima vac.</th>
                      <th>Total</th>
                      <th></th>
                    </tr>
                  </thead>
                </table>
              </div>
              <br>
            </div>



        </form>
      </div>
    </div>
  </div>
  </div>
  <!--END ADD MODAL-->


  <!-- Modal agregar vacaciones -->
  <div id="vacaciones_agregar_modal" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="#" method="POST">
          <div class="modal-header">
            <h4 class="modal-title">Agregar vacaciones</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-12 col-lg-12">
                <label>Año:</label>
                <select class="form-control" id="aniosAgregar">
                  <?php
                  $anioAct = date("Y");
                  $anioIni = $anioAct - 1;
                  $anioFin = $anioAct + 1;

                  for ($x = $anioIni; $x <= $anioFin; $x++) {
                    echo '<option value="' . $x . '" ';

                    if ($x == $anioAct) {
                      echo "selected";
                    }

                    echo '>' . $x . '</option>';
                  }
                  ?>
                </select>
              </div>
            </div>
            <br>
            <label>Días:</label>
            <div class="row">
              <div class="col-12 col-lg-12">
                <input type="number" value="" name="txtDias" id="txtDias" class="form-control" maxlength="2" min="1" max="32">
              </div>
            </div>
            <br>
            <div class="row" style="display: none;color: #e74a3b; font-size: 12px;position: relative;bottom: 8px;" id="mostrarAdvertencia">*Se agregarán los días a los ya existentes de este año.</div>
            <div class="row" style="display: block;">
              <center>
                <button type="button" class="btn-custom btn-custom--border-blue" data-dismiss="modal" id="btnCancelarAgregarVacaciones"><span class="ajusteProyecto">Cancelar</span></button>
                <button type="button" class="btn btn-custom btn-custom--blue" id="agregarDiasVacaciones">Agregar</button>
              </center>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal restar dias vacaciones -->
  <div id="vacaciones_editar_modal" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="#" method="POST">
          <div class="modal-header">
            <h4 class="modal-title">Modificar vacaciones</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-12 col-lg-12">
                <label>Año:</label>
                <input type="number" value="" name="aniosEditar" id="aniosEditar" class="form-control" readonly>
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-12 col-lg-6">
                <label>Días agregados:</label>
                <input type="number" value="" name="txtDiasEdit" id="txtDiasEdit" class="form-control" readonly>
              </div>
              <div class="col-12 col-lg-6">
                <label>Días restantes:</label>
                <input type="number" value="" name="txtDiasRestantes" id="txtDiasRestantes" class="form-control" readonly>
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-12 col-lg-12">
                <label>Días a restar:</label>
                <input type="number" value="" name="txtDiasRestar" id="txtDiasRestar" class="form-control" maxlength="2" min="1">
              </div>
            </div>
            <br>
            <div class="row" style="display: block;">
              <center>
                <input type="hidden" value="" name="idVacaciones" id="idVacaciones">
                <button type="button" class="btn-custom btn-custom--border-blue" data-dismiss="modal" id="btnCancelarEditarVacaciones"><span class="ajusteProyecto">Cancelar</span></button>
                <button type="button" class="btn btn-custom btn-custom--blue" id="editarDiasVacaciones">Modificar</button>
              </center>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <input type="hidden" name="csr_token_UT5JP" id="csr_token_UT5JP" value="<?= $token ?>">

  <!-- Custom scripts for all pages-->
  <script src="../../js/sb-admin-2.min.js"></script>
  <script src="../../js/scripts.js"></script>
  <script src="./js/vacaciones.js"></script>

  <script>
    let idEmpleado = 0;

    function obtenerVer(idEmpleadoL) {

      idEmpleado = idEmpleadoL;
      var table = $('#tblVacacionesAcumuladas').DataTable();
      table.destroy();

      var idioma_espanol = {
        sProcessing: "Procesando...",
        sZeroRecords: "No se encontraron resultados",
        sEmptyTable: "Ningún dato disponible en esta tabla",
        sSearch: "<img src='../../img/timdesk/buscar.svg' width='20px' />",
        sLoadingRecords: "Cargando...",
        searchPlaceholder: "Buscar...",
        oPaginate: {
          sFirst: "Primero",
          sLast: "Último",
          sNext: "<img src='../../img/icons/pagination.svg' width='20px'/>",
          sPrevious: "<img src='../../img/icons/pagination.svg' width='20px' style='transform: scaleX(-1)'/>",
        },
      };

      $("#tblVacacionesAcumuladas").dataTable({
        language: idioma_espanol,
        dom: "lfrtip",
        scrollX: true,
        lengthChange: true,
        info: false,
        ordering: false,
        pageLength: 15,
        paging: false,
        bFilter: false,
        ajax: {
          url: 'functions/function_vacaciones_acumulado.php',
          data: {
            'idEmpleado': idEmpleadoL
          },
          type: 'POST'
        },
        columns: [{
            data: "anios"
          },
          {
            data: "diasagregados"
          },
          {
            data: "diasrestantes"
          },
          {
            data: "acciones"
          },
        ],
      });

      var table2 = $('#tblVacacionesPeriodo').DataTable();
      table2.destroy();
      $("#tblVacacionesPeriodo").dataTable({
        language: idioma_espanol,
        dom: "lfrtip",
        scrollX: true,
        lengthChange: true,
        info: false,
        ordering: false,
        pageLength: 15,
        paging: false,
        bFilter: false,
        ajax: {
          url: 'functions/function_vacaciones_periodo.php',
          data: {
            'idEmpleado': idEmpleadoL
          },
          type: 'POST'
        },
        columns: [{
            data: "dias"
          },
          {
            data: "fechaini"
          },
          {
            data: "fechafin"
          },
          {
            data: "primavacacional"
          },
          {
            data: "totalvacaciones"
          },
          {
            data: "acciones"
          },
        ],
      });

      $("#periodoDiv").css("display", "none");
      $("#acumulado").css("display", "block");
      $("#vacaciones_modal").modal();

    }

    $("#agregarVacaciones").click(function() {
      let token = $("#csr_token_UT5JP").val();

      $("#mostrarAdvertencia").css("display", "none");

      $.ajax({
        type: 'POST',
        url: 'functions/comprobarAnioVacaciones.php',
        data: {
          idEmpleado: idEmpleado,
          csr_token_UT5JP: token
        },
        success: function(r) {

          if (r == "exito") {
            $("#mostrarAdvertencia").css("display", "block");
          }

        }
      });

      $("#vacaciones_agregar_modal").modal();

    });

    selectAnios = new SlimSelect({
      select: '#aniosAgregar',
      deselectLabel: '<span class="">✖</span>'
    });

    $("#aniosAgregar").change(function() {
      let token = $("#csr_token_UT5JP").val();
      let anios = $("#aniosAgregar").val().trim();
      $("#mostrarAdvertencia").css("display", "none");

      $.ajax({
        type: 'POST',
        url: 'functions/comprobarAnioVacaciones.php',
        data: {
          idEmpleado: idEmpleado,
          anios: anios,
          csr_token_UT5JP: token
        },
        success: function(r) {

          if (r == "exito") {
            $("#mostrarAdvertencia").css("display", "block");
          }

        }
      });

    });

    $("#agregarDiasVacaciones").click(function() {
      let anio = $("#aniosAgregar").val().trim();
      let dias = $("#txtDias").val().trim();
      let token = $("#csr_token_UT5JP").val();

      if (dias == "") {
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/notificacion_error.svg",
          msg: "Es necesario ingresar los días de vacaciones.",
        });
        return;
      }

      if (dias < 1 || dias > 32) {
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/notificacion_error.svg",
          msg: "No puedes agregar 0 días o más de 32 días.",
        });
        return;
      }

      $("#btnCancelarAgregarVacaciones").prop("disabled", true);
      $("#agregarDiasVacaciones").prop("disabled", true);

      $.ajax({
        type: 'POST',
        url: 'functions/agregarVacacionesAnio.php',
        data: {
          idEmpleado: idEmpleado,
          anio: anio,
          dias: dias,
          csr_token_UT5JP: token
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
              msg: "Días agregados",
            });
            $('#tblVacacionesAcumuladas').DataTable().ajax.reload();
            $('#vacaciones_agregar_modal').modal('hide');
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
          $("#btnCancelarAgregarVacaciones").prop("disabled", false);
          $("#agregarDiasVacaciones").prop("disabled", false);
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
          $("#btnCancelarAgregarVacaciones").prop("disabled", false);
          $("#agregarDiasVacaciones").prop("disabled", false);
        }
      });

    });

    function modificarVacaciones(idVacaciones) {

      let token = $("#csr_token_UT5JP").val();

      $.ajax({
        type: 'POST',
        url: 'functions/cargarVacacionesAnio.php',
        data: {
          idVacaciones: idVacaciones,
          csr_token_UT5JP: token
        },
        success: function(r) {
          var datos = JSON.parse(r);

          if (datos.respuesta == "exito") {

            $("#idVacaciones").val(idVacaciones);
            $("#aniosEditar").val(datos.anio);
            $("#txtDiasEdit").val(datos.diasagregados);
            $("#txtDiasRestantes").val(datos.diasrestantes);

            $("#vacaciones_editar_modal").modal();

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
        }
      });
    }

    $("#editarDiasVacaciones").click(function() {
      let idVacaciones = $("#idVacaciones").val().trim();
      let dias = $("#txtDiasRestar").val().trim();
      let limiteDias = $("#txtDiasRestantes").val().trim();
      let token = $("#csr_token_UT5JP").val();

      if (dias == "") {
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/notificacion_error.svg",
          msg: "Es necesario ingresar los días a restar de las vacaciones.",
        });
        return;
      }

      if (parseInt(dias) < 1 || parseInt(dias) > parseInt(limiteDias)) {
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/notificacion_error.svg",
          msg: "No puedes restar 0 días o más de los días de vacaciones que tiene el empleado.",
        });
        return;
      }

      $("#btnCancelarEditarVacaciones").prop("disabled", true);
      $("#editarDiasVacaciones").prop("disabled", true);

      $.ajax({
        type: 'POST',
        url: 'functions/editarVacacionesAnio.php',
        data: {
          idVacaciones: idVacaciones,
          dias: dias,
          csr_token_UT5JP: token
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
              msg: "Días restados",
            });
            $('#tblVacacionesAcumuladas').DataTable().ajax.reload();
            $('#vacaciones_editar_modal').modal('hide');
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
          $("#btnCancelarEditarVacaciones").prop("disabled", false);
          $("#editarDiasVacaciones").prop("disabled", false);
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
          $("#btnCancelarEditarVacaciones").prop("disabled", false);
          $("#editarDiasVacaciones").prop("disabled", false);
        }
      });

    });

    function descargarRecibo(idVacaciones) {

      $().redirect('functions/recibovacaciones.php', {
        'id': idVacaciones
      });
    }

    $("#btnAcumuladoVacaciones").click(function() {

      $("#periodoDiv").css("display", "none");
      $("#acumulado").css("display", "block");

    });

    $("#btnMostrarVacaciones").click(function() {

      $("#acumulado").css("display", "none");
      $("#periodoDiv").css("display", "block");

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