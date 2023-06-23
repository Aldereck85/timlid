<?php
session_start();

if (isset($_SESSION["Usuario"])) {
    require_once '../../include/db-conn.php';
    $user = $_SESSION["Usuario"];
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

  <title>Timlid | Vehículos</title>

  <!-- Bootstrap core JavaScript-->
  <script src="../../vendor/jquery/jquery.min.js"></script>
  <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- Core plugin JavaScript-->
  <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../../js/sweet/sweetalert2.js"></script>
  <script src="../../js/lobibox.min.js"></script>
  <script src="../../js/slimselect.min.js"></script>

  <!-- Page level plugins -->
  <script src="../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../vendor/datatables/dataTables.bootstrap4.min.js"></script>
  <script src="../../vendor/datatables/dataTables.buttons.js"></script>
  <script src="../../vendor/datatables/buttons.bootstrap4.min.js"></script>
  <script src="../../vendor/datatables/buttons.html5.min.js"></script>
  <script src="../../vendor/jszip/jszip.min.js"></script>

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
  <link href="../../css/styles.css" rel="stylesheet">
  <link href="../../css/stylesTable.css" rel="stylesheet">
  <link href="../../css/stylesModal-lg.css" rel="stylesheet">
  <link href="../../css/lobibox.min.css" rel="stylesheet">
  <link href="../../css/slimselect.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
  <style>
  .dropdown-menu {
    left: 2px !important;
  }

  .dropdown-menu::before {
    border: 0;
  }

  .dropdown-menu::after {
    border: 0;
  }
  </style>


  <script>
  $(document).ready(function() {
    var idioma_espanol = {
      "sProcessing": "Procesando...",
      "sZeroRecords": "No se encontraron resultados",
      "sEmptyTable": "Ningún dato disponible en esta tabla",
      "sSearch": "<img src='../../img/timdesk/buscar.svg' width='20px' />",
      "sLoadingRecords": "Cargando...",
      searchPlaceholder: "Buscar...",
      "oPaginate": {
        "sFirst": "Primero",
        "sLast": "Último",
        "sNext": "<img src='../../img/icons/pagination.svg' width='20px'/>",
        "sPrevious": "<img src='../../img/icons/pagination.svg' width='20px' style='transform: scaleX(-1)'/>"
      },
    }

    $("#tblVehiculos").dataTable({
        "language": idioma_espanol,
        "dom": "Bfrtip",
        "buttons": [{
          extend: 'excelHtml5',
          text: '<img class="readEditPermissions" type="submit" width="50px" src="../../img/excel-azul.svg" />',
        className: "excelDataTableButton",
          titleAttr: 'Excel',
        }],
        "scrollX": true,
        "lengthChange": false,
        "info": false,
        "scrollY": "200px",
        "ajax": "functions/function_Puestos.php",
        "columns": [{
            "data": "Linea"
          },
          {
            "data": "Marca"
          },
          {
            "data": "Serie"
          },
          {
            "data": "Placas"
          },
          {
            "data": "Color"
          },
          {
            "data": "Modelo"
          },
          {
            "data": "Puertas"
          },
          {
            "data": "Cilindros"
          },
          {
            "data": "Odometro"
          },
          {
            "data": "Kilometraje para servicio"
          },
          {
            "data": "Motor"
          },
          {
            "data": "Combustible"
          },
          {
            "data": "Transmision"
          },
          {
            "data": "Acciones"
          }
        ]
      }

    )
  });
  </script>

</head>

<body id="page-top" class="sidebar-toggled">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <?php
$titulo = "Control vehicular";
$ruta = "../";
$ruteEdit = $ruta . "central_notificaciones/";
require_once '../menu3.php';
?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <?php
$rutatb = "../";
$icono = '../../img/icons/vehiculos.svg';
require_once "../topbar.php"
?>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <!--<h1 class="h3 mb-2 text-gray-800">Control vehicular</h1>
          <p class="mb-4">Información general de los vehiculos</p>-->

          <!-- DataTales Example -->
          <div class="card mb-4">
            <div class="card-header py-3">
              <div class="float-right">
                <div class="button-container2" id="">
                  <div class="button-icon-container">
                    <a href="functions/agregar_Vehiculo.php"
                      class="btn btn-circle float-right waves-effect waves-light shadow btn-table" id="btn-vehiculos"
                      data-toggle="modal" data-target="#agregar_Vehiculo"><i class="fas fa-plus"></i></a>
                  </div>
                  <div class="button-text-container">
                    <span>Agregar almacén</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table" id="tblVehiculos" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Linea</th>
                      <th>Marca</th>
                      <th>Serie</th>
                      <th>Placas</th>
                      <th>Color</th>
                      <th>Modelo</th>
                      <th>Puertas</th>
                      <th>Cilindros</th>
                      <th>Odometro</th>
                      <th>Kilometraje para servicio</th>
                      <th>Motor</th>
                      <th>Combustible</th>
                      <th>Transmision</th>
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


  <!--ADD MODAL-->
  <div class="modal fade right" id="agregar_Vehiculo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
    <div class="modal-dialog modal-full-height-lg modal-right modal-lg" role="document">
      <div class="modal-content">
        <form action="" id="agregarVehiculo" method="POST">
          <!--<input type="hidden" name="idProyectoA" value="">-->
          <div class="modal-header">
            <h4 class="modal-title w-100" id="myModalLabel">Agregar vehiculo</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">x</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <div class="row">
                <div class="col-lg-6">
                  <label for="usr">Linea:</label>
                  <input type="text" class="form-control alphaNumeric-only" maxlength="30" name="txtLinea"
                    id="txtLinea">
                </div>
                <div class="col-lg-6">
                  <label for="usr">Marca:</label>
                  <input type="text" class="form-control alpha-only" maxlength="30" name="txtMarca" id="txtMarca">
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="row">
                <div class="col-lg-6">
                  <label for="usr">Serie:</label>
                  <input type="text" class="form-control alphaNumeric-only" maxlength="30" name="txtSerie"
                    id="txtSerie">
                </div>
                <div class="col-lg-6">
                  <label for="usr">Placas:</label>
                  <input type="text" class="form-control alphaNumeric-only" maxlength="30" name="txtPlacas"
                    id="txtPlacas">
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="row">
                <div class="col-lg-6">
                  <label for="usr">Modelo:</label>
                  <input type="text" class="form-control numeric-only" maxlength="30" name="txtModelo" id="txtModelo">
                </div>
                <div class="col-lg-6">
                  <label for="usr">Puertas:</label>
                  <input type="text" class="form-control numeric-only" maxlength="30" name="txtPuertas" id="txtPuertas">
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="row">
                <div class="col-lg-6">
                  <label for="usr">Cilindros:</label>
                  <input type="text" class="form-control numeric-only" maxlength="30" name="txtCilindros"
                    id="txtCilindros">
                </div>
                <div class="col-lg-6">
                  <label for="usr">Odometro:</label>
                  <input type="text" class="form-control numeric-only" maxlength="30" name="txtOdometro"
                    id="txtOdometro">
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="row">
                <div class="col-lg-6">
                  <label for="usr">Kilometros para servicio:</label>
                  <input type="text" class="form-control numeric-only" maxlength="30" name="txtKilometros"
                    id="txtKilometros">
                </div>
                <div class="col-lg-6">
                  <label for="usr">Motor:</label>
                  <input type="text" class="form-control alphaNumeric-only" maxlength="30" name="txtMotor"
                    id="txtMotor">
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="row">
                <div class="col-lg-6">
                  <label for="usr">Color:</label>
                  <input type="text" class="form-control alpha-only" maxlength="30" name="txtColor" id="txtColor">
                </div>
                <div class="col-lg-6">
                  <label for="usr">Combustible:</label>
                  <input type="text" class="form-control alpha-only" maxlength="30" name="txtCombustible"
                    id="txtCombustible">
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="row">
                <div class="col-lg-6">
                  <label for="usr">Transmision:</label>
                  <input type="text" class="form-control alpha-only" maxlength="30" name="txtTransmision"
                    id="txtTransmision">
                </div>

                <div class="col-lg-6">
                  <label for="usr">Responsable carga de combustible:</label>
                  <select class="form-control" name="cmbUsuario" id="cmbUsuario" required>
                    <option value="" disabled selected hidden>Seleccione una opcion...</option>
                    <?php
$stmt = $conn->prepare("SELECT id, usuario, nombre FROM usuarios");
$stmt->execute();
$row = $stmt->fetchAll();

if (count($row) > 0) {
    foreach ($row as $r) //Mostrar usuarios
    {
        echo '<option value="' . $r['id'] . '">' . $r['nombre'] . '</option>';
    }
} else {
    echo '<option value="" disabled>No hay usuarios para mostrar.</option>';
}
?>

                  </select>
                </div>
              </div>
            </div>
            <div class="modal-footer justify-content-end">
              <button type="button" class="btn-custom btn-custom--border-blue btnCancelarActualizacion"
                data-dismiss="modal" id="btnCancelarActualizacion"><span class="ajusteProyecto">Cancelar</span></button>
              <button type="button" class="btn-custom btn-custom--blue" name="btnAgregar" id="btnAgregarVehiculo"><span
                  class="ajusteProyecto">Agregar</span></button>
            </div>
        </form>
      </div>
    </div>
  </div>

  <!--END ADD MODAL SLIDE PUESTOS-->

  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">¿En serio quieres salir?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">x</span>
          </button>
        </div>
        <div class="modal-body">Selecciona "Salir" Para cerrar tu sesión</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
          <a class="btn btn-primary" href="../logout.php">Salir</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Delete Modal mis vehiculos -->
  <div id="eliminar_Vehiculo" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="functions/eliminar_Vehiculo.php" method="POST">
          <input type="hidden" name="idVehiculoD" id="idVehiculoD">
          <div class="modal-header">
            <h4 class="modal-title">Eliminar vehiculo</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          </div>
          <div class="modal-body">
            <p>¿Está seguro de realizar esta acción?</p>
            <p class="text-warning"><small>Esta acción es irreversible.</small></p>
          </div>
          <div class="modal-footer">
            <input type="button" class="btn btn-secondary" data-dismiss="modal" value="Cancelar">
            <input type="submit" class="btn btn-danger" value="Eliminar">
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Update Modal mis vehiculos -->
  <div id="editar_Vehiculo" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="functions/editar_Vehiculo.php" method="POST">
          <input type="hidden" name="idVehiculoU" id="idVehiculoU">
          <div class="modal-header">
            <h4 class="modal-title">Editar vehiculo</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          </div>
          <div class="modal-body">
            <p>¿Está seguro de realizar esta acción?</p>
            <p class="text-warning"><small>Esta acción cambiará los datos del registro.</small></p>
          </div>
          <div class="modal-footer">
            <input type="button" class="btn btn-secondary" data-dismiss="modal" value="Cancelar">
            <input type="submit" class="btn btn-primary" value="Editar">
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Custom scripts for all pages-->
  <script src="../../js/sb-admin-2.min.js"></script>
  <script src="../../js/scripts.js"></script>
  <script src="../../js/validaciones.js"></script>

  <script>
  $("#btnAgregarVehiculo").click(function() {
    var linea = $("#txtLinea").val();
    var marca = $("#txtMarca").val();
    var serie = $("#txtSerie").val();
    var placas = $("#txtPlacas").val();
    var modelo = $("#txtModelo").val();
    var puertas = $("#txtPuertas").val();
    var cilindros = $("#txtCilindros").val();
    var odometro = $("#txtOdometro").val();
    var km = $("#txtKilometros").val();
    var motor = $("#txtMotor").val();
    var color = $("#txtColor").val();
    var combustible = $("#txtCombustible").val();
    var transmision = $("#txtTransmision").val();
    var usuario = $("#cmbUsuario").val();



    if (linea.length < 1) {
      $("#txtLinea")[0].reportValidity();
      $("#txtLinea")[0].setCustomValidity('Completa este campo.');
      return;
    }

    if (marca.length < 1) {
      $("#txtMarca")[0].reportValidity();
      $("#txtMarca")[0].setCustomValidity('Completa este campo.');
      return;
    }

    if (serie.length < 1) {
      $("#txtSerie")[0].reportValidity();
      $("#txtSerie")[0].setCustomValidity('Completa este campo.');
      return;
    }

    if (placas.length < 1) {
      $("#txtPlacas")[0].reportValidity();
      $("#txtPlacas")[0].setCustomValidity('Completa este campo.');
      return;
    }

    if (modelo.length < 1) {
      $("#txtModelo")[0].reportValidity();
      $("#txtModelo")[0].setCustomValidity('Completa este campo.');
      return;
    }
    if (puertas.length < 1) {
      $("#txtPuertas")[0].reportValidity();
      $("#txtPuertas")[0].setCustomValidity('Completa este campo.');
      return;
    }
    if (cilindros.length < 1) {
      $("#txtCilindros")[0].reportValidity();
      $("#txtCilindros")[0].setCustomValidity('Completa este campo.');
      return;
    }
    if (odometro.length < 1) {
      $("#txtOdometro")[0].reportValidity();
      $("#txtOdometro")[0].setCustomValidity('Completa este campo.');
      return;
    }
    if (km.length < 1) {
      $("#txtKilometros")[0].reportValidity();
      $("#txtKilometros")[0].setCustomValidity('Completa este campo.');
      return;
    }
    if (motor.length < 1) {
      $("#txtMotor")[0].reportValidity();
      $("#txtMotor")[0].setCustomValidity('Completa este campo.');
      return;
    }
    if (color.length < 1) {
      $("#txtColor")[0].reportValidity();
      $("#txtColor")[0].setCustomValidity('Completa este campo.');
      return;
    }
    if (combustible.length < 1) {
      $("#txtCombustible")[0].reportValidity();
      $("#txtCombustible")[0].setCustomValidity('Completa este campo.');
      return;
    }
    if (transmision.length < 1) {
      $("#txtTransmision")[0].reportValidity();
      $("#txtTransmision")[0].setCustomValidity('Completa este campo.');
      return;
    }

    if (usuario == "Seleccione una opcion...") {
      $("#cmbUsuario")[0].reportValidity();
      $("#cmbUsuario")[0].setCustomValidity('Selecciona un usuario');
    }

    $.ajax({
      url: "functions/agregar_Vehiculo.php",
      type: "POST",
      data: {
        "txtLinea": linea,
        "txtMarca": marca,
        "txtSerie": serie,
        "txtPlacas": placas,
        "txtModelo": modelo,
        "txtPuertas": puertas,
        "txtCilindros": cilindros,
        "txtOdometro": odometro,
        "txtKilometros": km,
        "txtMotor": motor,
        "txtColor": color,
        "txtCombustible": combustible,
        "txtTransmision": transmision,
        "cmbUsuario": usuario
      },
      success: function(data, status, xhr) {
        if (data.trim() == "exito") {
          $('#agregar_Vehiculo').modal('toggle');
          $('#agregarVehiculo').trigger("reset");
          $('#tblVehiculos').DataTable().ajax.reload();
          Lobibox.notify('success', {
            size: 'mini',
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: 'center top', //or 'center bottom'
            icon: true,
            //img: '<i class="fas fa-check-circle"></i>',
            img: '../../img/timdesk/checkmark.svg',
            msg: '¡Registro agregado!'
          });
        } else {
          Lobibox.notify('warning', {
            size: 'mini',
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: 'center top',
            icon: true,
            img: '../../img/timdesk/warning_circle.svg',
            img: null,
            msg: 'Ocurrió un error al agregar'
          });
        }
      }
    });
  });

  function obtenerIdVehiculoEliminar(id) {
    document.getElementById('idVehiculoD').value = id;
  }

  function obtenerIdVehiculoEditar(id) {
    document.getElementById('idVehiculoU').value = id;
  }

  $(document).ready(function() {
    $("#alertaTareas").load(
      '../alerta_Tareas_Nuevas.php?user=<?=$_SESSION['PKUsuario'];?>&ruta=<?=$ruta;?>&ruteEdit=<?=$ruteEdit;?>');
    setInterval(refrescar, 5000);
  });

  function refrescar() {
    $("#alertaTareas").load(
      '../alerta_Tareas_Nuevas.php?user=<?=$_SESSION['PKUsuario'];?>&ruta=<?=$ruta;?>&ruteEdit=<?=$ruteEdit;?>');
  }
  new SlimSelect({
    select: '#cmbUsuario'
  });
  </script>
</body>

</html>