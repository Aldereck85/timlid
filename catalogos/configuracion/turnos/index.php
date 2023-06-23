<?php
session_start();

if (isset($_SESSION["Usuario"])) {
    require_once '../../../include/db-conn.php';
    $user = $_SESSION["Usuario"];
} else {
    header("location:../../dashboard.php");
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <link rel="icon" type="image/png" href="../../../img/header/bluTimlid.png">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Timlid | Turnos</title>

  <!-- Bootstrap core JavaScript-->
  <script src="../../../vendor/jquery/jquery.min.js"></script>
  <script src="../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="../../../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../../../js/sweet/sweetalert2.js"></script>


  <!-- Custom scripts for all pages-->
  <script src="../../../js/sb-admin-2.min.js"></script>
  <script src="../../../js/lobibox.min.js"></script>
  <script src="../../../js/validaciones.js"></script>
  <script src="../../../js/mdtimepicker.min.js"></script>
  <script src="../../../js/bootstrap-clockpicker.min.js"></script>
  <!--<script src="../../../js/timepicker.js"></script> -->
  <!--<script src="../../js/drum.js"></script>-->
  <!--<script src="../../js/hammerjs/hammer.min.js"></script>-->
  <!--<script src="../../js/hammerjs/hammer.fakemultitouch.js"></script>-->
  <!--<script src="../../js/jquery-weekdays.js"></script>-->
  <!-- <script src="../../../js/hr.timePicker.js"></script> -->
  <script src="../../../js/timepicki.js"></script>

  <!-- Page level plugins -->
  <script src="../../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../../vendor/datatables/dataTables.bootstrap4.min.js"></script>
  <script src="../../../vendor/datatables/dataTables.buttons.js"></script>
  <script src="../../../vendor/datatables/buttons.bootstrap4.min.js"></script>
  <script src="../../../vendor/datatables/buttons.html5.min.js"></script>
  <script src="../../../vendor/datatables/dataTables.responsive.js"></script>
  <script src="../../../vendor/jszip/jszip.min.js"></script>

  <!-- Page level custom scripts
  <script src="js/demo/datatables-demo.js"></script>
  -->

  <!-- Custom fonts for this template -->
  <link href="../../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet">

  <!-- Custom styles for this template -->
  <link rel="stylesheet" href="../../../css/sweetalert2.css">
  <link href="../../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../../css/styles.css" rel="stylesheet">
  <link href="css/dias.css" rel="stylesheet">
  <link href="../../../css/stylesTable.css" rel="stylesheet">
  <link href="../../../css/lobibox.min.css" rel="stylesheet">
  <link href="../../../css/mdtimepicker.min.css" rel="stylesheet">
  <link href="../../../css/bootstrap-clockpicker.min.css" rel="stylesheet" type="text/css">
  <!-- <link href="../../../css/timepicker.css" rel="stylesheet"> -->
  <!--<link href="../../css/drum.css" rel="stylesheet">-->
  <!-- <link href="../../../css/hr-timePicker.css" rel="stylesheet"> -->
  <!--<link href="../../css/jquery-weekdays.css" rel="stylesheet">-->
  <link href="../../../css/timepicki.css" rel="stylesheet">


  <!-- Custom styles for this page -->
  <!--<script type="module" src="https://cdn.jsdelivr.net/npm/@ionic/core/dist/ionic/ionic.esm.js"></script>-->
  <!--<script nomodule src="https://cdn.jsdelivr.net/npm/@ionic/core/dist/ionic/ionic.js"></script>-->
  <!--<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ionic/core/css/ionic.bundle.css"/>-->
  <link href="../../../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
  <link href="../../../vendor/datatables/responsive.dataTables.css" rel="stylesheet">
  <link rel="stylesheet" href="../../../vendor/datatables/buttons.dataTables.css">
  <link href="../../../vendor/datatables/buttons.bootstrap4.min.css" rel="stylesheet">

  <script>
  $(document).ready(function() {
    var idioma_espanol = {
      "sProcessing": "Procesando...",
      "sZeroRecords": "No se encontraron resultados",
      "sEmptyTable": "Ningún dato disponible en esta tabla",
      "sSearch": "<img src='../../../img/timdesk/buscar.svg' width='20px' />",
      "sLoadingRecords": "Cargando...",
      searchPlaceholder: "Buscar...",
      "oPaginate": {
        "sFirst": "Primero",
        "sLast": "Último",
        "sNext": "<img src='../../../img/icons/pagination.svg' width='20px'/>",
        "sPrevious": "<img src='../../../img/icons/pagination.svg' width='20px' style='transform: scaleX(-1)'/>"
      },
    }

    $("#tblTurnos").dataTable({
      "language": idioma_espanol,
      "dom": "Bfrtip",
      "buttons": [{
        extend: 'excelHtml5',
        text: '<img class="readEditPermissions" type="submit" width="50px" src="../../../img/excel-azul.svg" />',
        className: "excelDataTableButton",
        titleAttr: 'Excel',
      }],
      "scrollX": true,
      "lengthChange": false,
      "info": false,
      "order": [
        [0, "desc"]
      ],
      "ajax": "functions/function_Turno.php",
      "columns": [{
          "data": "id"
        },
        {
          "data": "Turno"
        },
        {
          "data": "Entrada"
        },
        {
          "data": "Salida"
        },
        {
          "data": "Dias"
        },
        {
          "data": "Horas/Semana"
        },
        {
          "data": "TiempoComida"
        }
      ],
      columnDefs: [{
        orderable: false,
        targets: 0,
        visible: false
      }],
      responsive: true

    })
  });
  </script>


  <style>
  .nav-link {
    padding: .5rem;
  }
  </style>
</head>

<body id="page-top" class="sidebar-toggled">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <!--<?php
//$ruta = "../";
//require_once('../menu3.php');
?>-->
    <?php
$ruta = "../../";
$ruteEdit = $ruta . "central_notificaciones/";
require_once '../../menu3.php';
?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <?php
$rutatb = '../../';
$titulo = 'Configuraciones';
$icono = '../../../img/inventarios/ICONO INVENTARIOS Y PROD_Mesa de trabajo 1.svg';
require_once '../../topbar.php';
?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->

          <div class="row">
            <div class="col-lg-12">
              <ul class="nav nav-tabs">
                <li class="nav-item">
                  <a id="CargarUsuarios" class="nav-link" href="../">
                    Usuarios
                  </a>
                </li>
                <li class="nav-item">
                  <a id="CargarPuestos" class="nav-link" href="../puestos">
                    Puestos
                  </a>
                </li>
                <li class="nav-item">
                  <a id="CargarTurnos" class="nav-link active" href="">
                    Turnos
                  </a>
                </li>
                <li class="nav-item">
                  <a id="CargarSucursales" class="nav-link" href="../sucursales">
                    Sucursales
                  </a>
                </li>
                <li class="nav-item">
                  <a id="CargarCategoriaProductos" class="nav-link" href="../categoria_productos">
                    Categoría de productos
                  </a>
                </li>
                <li class="nav-item">
                  <a id="CargarMarcas" class="nav-link" href="../marca_productos">
                    Marcas de productos
                  </a>
                </li>
                <li class="nav-item">
                  <a id="CargarCategoriaGastos" class="nav-link" href="../categoria_gastos">
                    Categoría gastos
                  </a>
                </li>
                <li class="nav-item">
                  <a id="CargarSubategoriaGastos" class="nav-link" href="../subcategorias_gastos">
                    Subcategoría de gastos
                  </a>
                </li>
                <li class="nav-item">
                  <a id="CargarResponsableGastos" class="nav-link" href="../responsables_gastos">
                    Responsable de gastos
                  </a>
                </li>
                <li class="nav-item">
                  <a id="CargarTipoOrdenInventario" class="nav-link" href="../tipo_orden_inventario">
                    Tipo de orden de inventario
                  </a>
                </li>
              </ul>
            </div>
          </div>
          <!-- DataTales Example -->
          <div class="card mb-4">
            <div class="card-header py-3">
              <div class="float-right">
                <div class="button-container2" id="">
                  <div class="button-icon-container">
                    <a href="#" class="btn btn-circle float-right waves-effect waves-light shadow btn-table"
                      id="btn-turnos" data-toggle="modal" data-target="#agregar_Turno">
                      <i class="fas fa-plus"></i>
                    </a>
                  </div>
                  <div class="button-text-container">
                    <span>Agregar turno</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table" id="tblTurnos" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>id</th>
                      <th>Turno</th>
                      <th>Entrada</th>
                      <th>Salida</th>
                      <th>Días de labores</th>
                      <th>Horas/Semana</th>
                      <th>Tiempo de comida</th>
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
$rutaf = "../../";
require_once '../../footer.php';
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

  <!--ADD MODAL SLIDE PUESTOS-->
  <div class="modal fade right" id="agregar_Turno" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
    <div class="modal-dialog modal-full-height modal-right  modal-md" role="document">
      <div class="modal-content">
        <form action="" id="agregarTurno" method="POST">
          <!--<input type="hidden" name="idProyectoA" value="">-->
          <div class="modal-header">
            <h4 class="modal-title w-100" id="myModalLabel">Agregar turno</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">x</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label for="usr">Turno:*</label>
              <input type="text" class="form-control alpha-only" maxlength="20" name="txtTurno" id="txtTurno"
                onkeyup="validarUnicoTurno()" required>
              <div class="invalid-feedback" id="invalid-turno">El nombre del turno es requerido.</div>
            </div>
            <div class="form-group">
              <label for="usr">Entrada:*</label>
              <input type="text" class="form-control time-only" name="txtEntrada" id="txtEntrada"
                onchange="validEmptyInput(this)" required>
              <div class="invalid-feedback" id="invalid-entrada">La hora de entrada es requerida.</div>
            </div>
            <div class="form-group">
              <label for="usr">Salida:*</label>
              <input type="text" class="form-control time-only" name="txtSalida" id="txtSalida"
                onchange="validEmptyInput(this)" required>
              <div class="invalid-feedback" id="invalid-salida">La hora de salida es requerida.</div>
            </div>

            <div class="form-group weekDays-selector">
              <form id="form" name="cat" method="POST" action="">
                <label for="usr">Días de trabajo:</label><br>
                <input type="checkbox" name="weekday" id="weekday-mon" class="weekday" value="1"
                  onclick="checkbox_Selected();" />
                <label for="weekday-mon">LUN</label>
                <input type="checkbox" name="weekday" id="weekday-tue" class="weekday" value="2"
                  onclick="checkbox_Selected();" />
                <label for="weekday-tue">MAR</label>
                <input type="checkbox" name="weekday" id="weekday-wed" class="weekday" value="3"
                  onclick="checkbox_Selected();" />
                <label for="weekday-wed">MIE</label>
                <input type="checkbox" name="weekday" id="weekday-thu" class="weekday" value="4"
                  onclick="checkbox_Selected();" />
                <label for="weekday-thu">JUE</label>
                <input type="checkbox" name="weekday" id="weekday-fri" class="weekday" value="5"
                  onclick="checkbox_Selected();" />
                <label for="weekday-fri">VIE</label>
                <input type="checkbox" name="weekday" id="weekday-sat" class="weekday" value="6"
                  onclick="checkbox_Selected();" />
                <label for="weekday-sat">SAB</label>
                <input type="checkbox" name="weekday" id="weekday-sun" class="weekday" value="7"
                  onclick="checkbox_Selected();" />
                <label for="weekday-sun">DOM</label>
              </form>
            </div>
            <div class="form-group">
              <label for="usr">Tiempo de comida:*</label>
              <input type="text" class="form-control time-only time_element" name="txtComida" id="txtComida"
                oninput="validEmptyInput(this, 'invalid-comida')" required>
              <div class="invalid-feedback" id="invalid-comida">La hora de comida es requerida.</div>
            </div>
          </div>
          <div class="modal-footer justify-content-center">
            <button type="button" class="btnesp first espCancelar btnCancelarActualizacion" data-dismiss="modal"
              id="btnCancelarActualizacion"><span class="ajusteProyecto">Cancelar</span></button>
            <button type="button" class="btnesp espAgregar float-right" name="btnAgregar" id="btnAgregarTurno"><span
                class="ajusteProyecto">Agregar</span></button>
          </div>
        </form>
      </div>
    </div>
  </div>
  </div>
  <!--END ADD MODAL SLIDE PUESTOS-->

  <!--UPDATE MODAL SLIDE PUESTOS-->
  <div class="modal fade right" id="editar_Turno" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
    <div class="modal-dialog modal-full-height modal-right modal-md" role="document">
      <div class="modal-content">
        <form action="" id="editarTurno" method="POST">
          <input type="hidden" name="idTurnoU" id="idTurnoU">
          <div class="modal-header">
            <h4 class="modal-title w-100" id="myModalLabel">Editar turno</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">x</span>
            </button>
          </div>
          <div class="modal-body">
            <input class="form-control" id="notaExisteRelacion" name="notaExisteRelacion" type="hidden"
              style="color: darkred; background-color: transparent!important; border: none;"
              value="Nota: El turno está siendo utilizado." readonly>
            <div class="form-group">
              <label for="usr">Turno:</label>
              <input type="text" class="form-control alpha-only" maxlength="20" name="txtTurnoU" id="txtTurnoU"
                onkeyup="validarUnicoTurnoEdit()" required>
              <div class="invalid-feedback" id="invalid-turnoEdit">El nombre del turno es requerido.</div>
            </div>
            <div class="form-group">
              <label for="usr">Entrada:</label>
              <input type="text" class="form-control  time-only" name="txtEntradaU" id="txtEntradaU" value="" required>
              <div class="invalid-feedback" id="invalid-entradaEdit">La hora de entrada es requerida.</div>
            </div>
            <div class="form-group">
              <label for="usr">Salida:</label>
              <input type="text" class="form-control time-only" name="txtSalidaU" id="txtSalidaU" required>
              <div class="invalid-feedback" id="invalid-salidaEdit">La hora de salida es requerida.</div>
            </div>
            <div class="form-group weekDays-selector2">
              <form id="form2" name="cat" method="POST" action="">
                <label for="usr">Dias de trabajo:</label><br>
                <input type="checkbox" name="weekdayU" id="weekday-monU" class="weekday1" value="1"
                  onclick="checkbox_UnSelected();" />
                <label for="weekday-monU">LUN</label>
                <input type="checkbox" name="weekdayU" id="weekday-tueU" class="weekday1" value="2"
                  onclick="checkbox_UnSelected();" />
                <label for="weekday-tueU">MAR</label>
                <input type="checkbox" name="weekdayU" id="weekday-wedU" class="weekday1" value="3"
                  onclick="checkbox_UnSelected();" />
                <label for="weekday-wedU">MIE</label>
                <input type="checkbox" name="weekdayU" id="weekday-thuU" class="weekday1" value="4"
                  onclick="checkbox_UnSelected();" />
                <label for="weekday-thuU">JUE</label>
                <input type="checkbox" name="weekdayU" id="weekday-friU" class="weekday1" value="5"
                  onclick="checkbox_UnSelected();" />
                <label for="weekday-friU">VIE</label>
                <input type="checkbox" name="weekdayU" id="weekday-satU" class="weekday1" value="6"
                  onclick="checkbox_UnSelected();" />
                <label for="weekday-satU">SAB</label>
                <input type="checkbox" name="weekdayU" id="weekday-sunU" class="weekday1" value="7"
                  onclick="checkbox_UnSelected();" />
                <label for="weekday-sunU">DOM</label>
              </form>
            </div>
            <div class="form-group">
              <label for="usr">Tiempo de comida:</label>
              <input type="text" class="form-control time-only time_element" name="txtComidaU" id="txtComidaU" required>
              <div class="invalid-feedback" id="invalid-comidaEdit">La hora de comida es requerida.</div>
            </div>
          </div>
          <div class="modal-footer justify-content-center">
            <a class="btnesp first espEliminar" href="#" onclick="eliminarTurno(this.value);" name="idTurnoD"
              id="idTurnoD" data-toggle="modal" data-target=""><span class="ajusteProyecto">Eliminar turno</span></a>
            <button type="button" class="btnesp first espCancelar btnCancelarActualizacion" data-dismiss="modal"
              id="btnCancelarActualizacionU"><span class="ajusteProyecto">Cancelar</span></button>
            <button type="button" class="btnesp espAgregar float-right" name="btnAgregar" id="btnEditarTurno"><span
                class="ajusteProyecto">Modificar</span></button>
          </div>
        </form>
      </div>
    </div>
  </div>
  </div>

  <!--END ADD MODAL SLIDE PUESTOS-->


  <script>
  $(document).ready(function() {
    /* $('#relojEntrada').clockpicker({
      placement: 'top',
      align: 'left',
      donetext: 'Cerrar',
      autoclose: true
    });
    $('#relojSalida').clockpicker({
      placement: 'top',
      align: 'left',
      donetext: 'Cerrar',
      autoclose: true
    });
    $('#relojEntradaU').clockpicker({
      placement: 'top',
      align: 'left',
      donetext: 'Cerrar',
      autoclose: true
    });
    $('#relojSalidaU').clockpicker({
      placement: 'top',
      align: 'left',
      donetext: 'Cerrar',
      autoclose: true
    }); */
    $("#txtComida").timepicki({
      show_meridian: false,
      start_time: ["00", "00"],
      min_hour_value: 0,
      max_hour_value: 2,
      step_size_minutes: 1,
      overflow_minutes: true,
      increase_direction: 'up',
      disable_keyboard_mobile: true
    });
    $("#txtComidaU").timepicki({
      show_meridian: false,
      start_time: ["00", "00"],
      min_hour_value: 0,
      max_hour_value: 2,
      step_size_minutes: 1,
      overflow_minutes: true,
      increase_direction: 'up',
      disable_keyboard_mobile: true
    });
  });

  var val_selected = []; /*Funcion para los checkbox de seleccion de dias de la semana*/
  var val_selected2 = [];

  function checkbox_Selected() {
    //$('input[type="checkbox"]').click(function(){
    val_selected = [];
    $('input[name="weekday"]:checkbox:checked').each(function(i) {
      val_selected[i] = $(this).val();
    });
    console.log(val_selected);
  }

  function checkbox_UnSelected() {
    val_selected2 = [];
    $("input[name='weekdayU']:checkbox:checked").each(function(i) {
      val_selected2[i] = $(this).val();
    });
    console.log(val_selected2);
  }

  function obtenerIdTurnoEditar(id) {
    /**Funcion para obtener datos para editar */
    val_selected = [];
    document.getElementById('idTurnoU').value = id;
    document.getElementById('idTurnoD').value = id;
    var id = "id=" + id;
    $.ajax({
      type: 'POST',
      url: 'functions/getTurnos.php',
      data: id,
      success: function(r) {
        var datos = JSON.parse(r);
        $("#txtTurnoU").val(datos.html);
        $("#txtEntradaU").val(datos.html11);
        $("#txtSalidaU").val(datos.html21);
        $("#cmbDiasU").val(datos.html31);
        $("#txtComidaU").val(datos.html41);
        $.each(datos.html51, function(i) {
          //console.log(datos.html51[i]);
          if (datos.html51[i] == 1) {
            $("#weekday-monU").prop("checked", true);
            val_selected2.push(1);
          } else if (datos.html51[i] == 2) {
            $("#weekday-tueU").prop("checked", true);
            val_selected2.push(2);
          } else if (datos.html51[i] == 3) {
            $("#weekday-wedU").prop("checked", true);
            val_selected2.push(3);
          } else if (datos.html51[i] == 4) {
            $("#weekday-thuU").prop("checked", true);
            val_selected2.push(4);
          } else if (datos.html51[i] == 5) {
            $("#weekday-friU").prop("checked", true);
            val_selected2.push(5);
          } else if (datos.html51[i] == 6) {
            $("#weekday-satU").prop("checked", true);
            val_selected2.push(6);
          } else if (datos.html51[i] == 7) {
            $("#weekday-sunU").prop("checked", true);
            val_selected2.push(7);
          }
        });

        var idSucursal = $("#idTurnoU").val();
        if (idSucursal != "") {
          validarExisteRelacionTurno(idSucursal);
        } else {}

      }
    });
  }



  $("#btnAgregarTurno").click(function() {
    if ($("#agregarTurno")[0].checkValidity()) {
      var turno = $("#txtTurno").val().trim();
      var entrada = $("#txtEntrada").val();
      var salida = $("#txtSalida").val();
      var comida = $("#txtComida").val();
      var dias = val_selected;

      if (comida) {
        $("#invalid-comida").css("display", "none");
        $("#txtComida").removeClass("is-invalid");
      }

      var badNombreTur =
        $("#invalid-turno").css("display") === "block" ? false : true;
      var badEntrada =
        $("#invalid-entrada").css("display") === "block" ? false : true;
      var badSalida =
        $("#invalid-salida").css("display") === "block" ? false : true;
      var badComida =
        $("#invalid-comida").css("display") === "block" ? false : true;

      if (badNombreTur && badEntrada && badSalida && badComida) {
        $.ajax({
          url: "functions/agregar_Turno.php",
          type: "POST",
          data: {
            "txtTurno": turno,
            "txtEntrada": entrada,
            "txtSalida": salida,
            "cmbDias": dias,
            "txtComida": comida
          },
          success: function(data, status, xhr) {
            console.log(data);
            if (data.trim() == "exito") {
              $('#agregar_Turno').modal('toggle');
              $('#agregarTurno').trigger("reset");
              $('#tblTurnos').DataTable().ajax.reload();
              Lobibox.notify('success', {
                size: 'mini',
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: 'center top', //or 'center bottom'
                icon: false,
                img: '../../../img/timdesk/checkmark.svg',
                msg: '¡Registro agregado!'
              });
            } else {
              Lobibox.notify('warning', {
                size: 'mini',
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: 'center top',
                icon: false,
                img: '../../../img/timdesk/warning_circle.svg',
                msg: 'Ocurrió un error al agregar'
              });
            }
          }
        });
      }
    } else {
      if (!$("#txtTurno").val()) {
        $("#invalid-turno").css("display", "block");
        $("#txtTurno").addClass("is-invalid");
      }
      if (!$("#txtEntrada").val()) {
        $("#invalid-entrada").css("display", "block");
        $("#txtEntrada").addClass("is-invalid");
      }
      if (!$("#txtSalida").val()) {
        $("#invalid-salida").css("display", "block");
        $("#txtSalida").addClass("is-invalid");
      }
      if (!$("#txtComida").val()) {
        $("#invalid-comida").css("display", "block");
        $("#txtComida").addClass("is-invalid");
      }
    }
  });

  $("#btnEditarTurno").click(function() {
    if ($("#editarTurno")[0].checkValidity()) {
      var id = $('#idTurnoU').val();
      var turno = $("#txtTurnoU").val();
      var entrada = $("#txtEntradaU").val();
      var salida = $("#txtSalidaU").val();
      var dias = val_selected2;
      var comida = $("#txtComidaU").val();

      if (comida) {
        $("#invalid-comidaEdit").css("display", "none");
        $("#txtComidaU").removeClass("is-invalid");
      }
      var badNombreTur =
        $("#invalid-turnoEdit").css("display") === "block" ? false : true;
      var badEntrada =
        $("#invalid-entradaEdit").css("display") === "block" ? false : true;
      var badSalida =
        $("#invalid-salidaEdit").css("display") === "block" ? false : true;
      var badComida =
        $("#invalid-comidaEdit").css("display") === "block" ? false : true;

      if (badNombreTur && badEntrada && badSalida && badComida) {
        $.ajax({
          url: "functions/editar_Turno.php",
          type: "POST",
          data: {
            "idTurnoU": id,
            "txtTurnoU": turno,
            "txtEntradaU": entrada,
            "txtSalidaU": salida,
            "cmbDiasU": dias,
            "txtComidaU": comida
          },
          success: function(data, status, xhr) {
            console.log(data);
            if (data.trim() == "1") {
              $('#editar_Turno').modal('toggle');
              $('#editarTurno').trigger("reset");
              $('#tblTurnos').DataTable().ajax.reload();
              Lobibox.notify('success', {
                size: 'mini',
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: 'center top', //or 'center bottom'
                icon: false,
                img: '../../../img/timdesk/checkmark.svg',
                msg: '¡Registro agregado!'
              });
            } else {
              Lobibox.notify('warning', {
                size: 'mini',
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: 'center top',
                icon: false,
                img: '../../../img/timdesk/warning_circle.svg',
                msg: 'Ocurrió un error al agregar'
              });
            }
          }
        });
      }
    } else {
      if (!$("#txtTurnoU").val()) {
        $("#invalid-turnoEdit").css("display", "block");
        $("#txtTurnoU").addClass("is-invalid");
      }
      if (!$("#txtEntradaU").val()) {
        $("#invalid-entradaEdit").css("display", "block");
        $("#txtEntradaU").addClass("is-invalid");
      }
      if (!$("#txtSalidaU").val()) {
        $("#invalid-salidaEdit").css("display", "block");
        $("#txtSalidaU").addClass("is-invalid");
      }
      if (!$("#txtComidaU").val()) {
        $("#invalid-comidaEdit").css("display", "block");
        $("#txtComidaU").addClass("is-invalid");
      }
    }
  });

  function eliminarTurno(id) {
    const swalWithBootstrapButtons = Swal.mixin({
      customClass: {
        actions: "d-flex justify-content-around",
        confirmButton: "btn-custom btn-custom--border-blue",
        cancelButton: "btn-custom btn-custom--blue"
      },
      buttonsStyling: false
    })
    swalWithBootstrapButtons.fire({
      title: '¿Desea eliminar el registro de este turno?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: '<span class="verticalCenter2">Eliminar turno</span>',
      cancelButtonText: '<span class="verticalCenter2">Cancelar</span>',
      reverseButtons: true
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: "functions/eliminar_Turno.php",
          type: "POST",
          data: {
            "idTurnoD": id
          },
          success: function(data, status, xhr) {
            if (data == "exito") {
              $('#editar_Turno').modal('toggle');
              $('#tblTurnos').DataTable().ajax.reload();
              Lobibox.notify('error', {
                size: 'mini',
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: 'center top', //or 'center bottom'
                icon: true,
                img: '../../../img/chat/notificacion_error.svg',
                msg: '¡Registro eliminado!'
              });
            } else {
              Lobibox.notify('warning', {
                size: 'mini',
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: 'center top',
                icon: true,
                img: '../../../img/timdesk/warning_circle.svg',
                msg: 'Ocurrió un error al eliminar'
              });
            }
          }
        });

      } else if (
        /* Read more about handling dismissals below */
        result.dismiss === Swal.DismissReason.cancel
      ) {}
    });

    swal("¿Desea eliminar el registro de este turno?", {
        buttons: {
          cancel: {
            text: "Cancelar",
            value: null,
            visible: true,
            className: "",
            closeModal: true,
          },
          confirm: {
            text: "Eliminar turno",
            value: true,
            visible: true,
            className: "",
            closeModal: true,
          },
        },
        icon: "warning"
      })
      .then((value) => {
        if (value) {
          $.ajax({
            url: "functions/eliminar_Turno.php",
            type: "POST",
            data: {
              "idTurnoD": id
            },
            success: function(data, status, xhr) {
              if (data == "exito") {
                $('#editar_Turno').modal('toggle');
                $('#tblturnos').DataTable().ajax.reload();
                Lobibox.notify('error', {
                  size: 'mini',
                  rounded: true,
                  delay: 3000,
                  delayIndicator: false,
                  position: 'center top', //or 'center bottom'
                  icon: false,
                  img: '../../../img/chat/notificacion_error.svg',
                  msg: '¡Registro eliminado!'
                });
              } else {
                Lobibox.notify('warning', {
                  size: 'mini',
                  rounded: true,
                  delay: 3000,
                  delayIndicator: false,
                  position: 'center top',
                  icon: false,
                  img: '../../../img/timdesk/warning_circle.svg',
                  msg: 'Ocurrió un error al eliminar'
                });
              }
            }
          });
        }
      });
  }
  </script>

  <script>
  var ruta = "../../";
  </script>
  <script src="../../../js/sb-admin-2.min.js"></script>
  <script src="js/turnos.js"></script>
  <script>
  $(document).ready(function() {
    $('#txtEntrada').mdtimepicker(); //Initializes the time picker
    $('#txtSalida').mdtimepicker(); //Initializes the time picker
    $('#txtEntradaU').mdtimepicker(); //Initializes the time picker
    $('#txtSalidaU').mdtimepicker(); //Initializes the time picker
  });
  </script>

  <script>
  /* Reiniciar el modal al cerrarlo */
  $("#agregar_Turno").on("hidden.bs.modal", function(e) {
    $("#invalid-turno").text("El nombre del turno es requerido.");
    $("#invalid-turno").css("display", "none");
    $("#txtTurno").removeClass("is-invalid");
    $("#txtTurno").val("");

    $("#invalid-entrada").text("La hora de entrada es requerida.");
    $("#invalid-entrada").css("display", "none");
    $("#txtEntrada").removeClass("is-invalid");
    $("#txtEntrada").val("");

    $("#invalid-salida").text("La hora de salida es requerida.");
    $("#invalid-salida").css("display", "none");
    $("#txtSalida").removeClass("is-invalid");
    $("#txtSalida").val("");

    $("#invalid-comida").text("La hora de comida es requerida.");
    $("#invalid-comida").css("display", "none");
    $("#txtComida").removeClass("is-invalid");
    $("#txtComida").val("");
  });

  /* Reiniciar el modal al cerrarlo */
  $("#editar_Turno").on("hidden.bs.modal", function(e) {
    $("#invalid-turnoEdit").text("El nombre del turno es requerido.");
    $("#invalid-turnoEdit").css("display", "none");
    $("#txtTurnoU").removeClass("is-invalid");
    $("#txtTurnoU").val("");

    $("#invalid-entradaEdit").text("La hora de entrada es requerida.");
    $("#invalid-entradaEdit").css("display", "none");
    $("#txtEntradaU").removeClass("is-invalid");
    $("#txtEntradaU").val("");

    $("#invalid-salidaEdit").text("La hora de salida es requerida.");
    $("#invalid-salidaEdit").css("display", "none");
    $("#txtSalidaU").removeClass("is-invalid");
    $("#txtSalidaU").val("");

    $("#invalid-comidaEdit").text("La hora de comida es requerida.");
    $("#invalid-comidaEdit").css("display", "none");
    $("#txtComidaU").removeClass("is-invalid");
    $("#txtComidaU").val("");
  });
  </script>
</body>

</html>