<?php
session_start();

if (isset($_SESSION["Usuario"])) {
    $user = $_SESSION["Usuario"];
    require_once '../../include/db-conn.php';
} else {
    header("location:../dashboard.php");
}

if (isset($_GET["id"])) {
    $id = $_GET["id"];
} else {
    $id = 0;
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

  <title>Timlid | Vacaciones</title>

  <!-- Bootstrap core JavaScript-->
  <script src="../../vendor/jquery/jquery.min.js"></script>
  <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="../../js/sb-admin-2.min.js"></script>

  <!-- Page level plugins -->
  <script src="../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../vendor/datatables/dataTables.bootstrap4.min.js"></script>
  <script src="../../vendor/datatables/dataTables.buttons.js"></script>
  <script src="../../vendor/datatables/buttons.bootstrap4.min.js"></script>
  <script src="../../vendor/datatables/buttons.html5.min.js"></script>
  <script src="../../vendor/jszip/jszip.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"
    integrity="sha256-c4gVE6fn+JRKMRvqjoDp+tlG4laudNYrXI1GncbfAYY=" crossorigin="anonymous"></script>

  <!-- Custom fonts for this template -->
  <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet">

  <!-- Custom styles for this page -->
  <link href="../../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../css/styles.css" rel="stylesheet">
  <link href="../../css/stylesTable.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css"
    integrity="sha256-EH/CzgoJbNED+gZgymswsIOrM9XhIbdSJ6Hwro09WE4=" crossorigin="anonymous" />
  <link href="../../css/chosen.css" rel="stylesheet" type="text/css">

  <script>
  var id = <?=$id?>;
  var rol = <?=$_SESSION['FKRol']?>;
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

    $("#tblVacaciones").dataTable({
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
      "ajax": "functions/function_Vacaciones.php?id=" + id,
      "columns": [{
          "data": "ID"
        },
        {
          "data": "Empleado"
        },
        {
          "data": "Dias Vacaciones"
        },
        {
          "data": "Fecha Inicio"
        },
        {
          "data": "Fecha Termino"
        },
        {
          "data": "Estatus"
        },
        {
          "data": "Acciones"
        }
      ],
      "language": idioma_espanol,
      columnDefs: [{
        orderable: false,
        targets: 5
      }],
      "order": [
        [0, "desc"]
      ],
      responsive: true
    })
  });
  </script>
  </style>
</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <?php
$ruta = "../";
$ruteEdit = $ruta . "central_notificaciones/";
require_once '../menu3.php';
$rutes = "../";
?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <?php
$rutatb = '../';
$icono = '../../img/menu/dashboardTopbar.svg';
$titulo = '<div class="header-screen d-flex align-items-center">
                      <div class="header-title-screen">
                        <h1 class="h3">Dashboard </h1>
                      </div>
                    </div>';
require_once '../topbar.php';
?>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <h1 class="h3 mb-2 text-gray-800"><i class="fas fa-suitcase-rolling"></i> Vacaciones</h1>
          <p class="mb-4">Autorizar vacaciones</p>

          <!-- DataTales Example -->
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <?php
if (isset($_GET['mensaje'])) {
    if ($_GET['mensaje'] == 1) {
        echo '<div class="alert alert-warning" role="alert">
                                No se puede eliminar un permiso que ya fue aceptado.
                              </div>';
    }
}
?>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table" id="tblVacaciones" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>ID Permiso</th>
                      <th>Empleado</th>
                      <th>Dias Vacaciones</th>
                      <th>Fecha de Inicio</th>
                      <th>Fecha de Termino</th>
                      <th>Estatus</th>
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

  <div id="modificarEstatus" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="functions/modificarVacaciones.php" method="POST">
          <input type="hidden" name="idPermisoU" id="idPermisoU">
          <input type="hidden" name="idEstatus" id="idEstatus">
          <div class="modal-header">
            <h4 class="modal-title">Estatus permiso vacaciones</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          </div>
          <div class="modal-body">
            <p>¿Está seguro de realizar esta acción?</p>
            <p class="text-warning"><small>Esta acción es irreversible.</small></p>
          </div>
          <div class="modal-footer">
            <input type="button" class="btn btn-secondary" data-dismiss="modal" value="Cancelar">
            <input type="submit" class="btn btn-success" value="Aceptar">
          </div>
        </form>
      </div>
    </div>
  </div>


  <div id="modificarEstatusCanc" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="functions/modificarVacaciones.php" method="POST">
          <input type="hidden" name="idPermisoCanc" id="idPermisoCanc">
          <input type="hidden" name="idEstatusCanc" id="idEstatusCanc">
          <div class="modal-header">
            <h4 class="modal-title">Estatus permiso vacaciones</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          </div>
          <div class="modal-body">
            <p>¿Está seguro de realizar esta acción?</p>
            <p class="text-warning"><small>Esta acción es irreversible.</small></p>
          </div>
          <div class="modal-footer">
            <input type="button" class="btn btn-secondary" data-dismiss="modal" value="Cancelar">
            <input type="submit" class="btn btn-success" value="Aceptar">
          </div>
        </form>
      </div>
    </div>
  </div>


  <!-- Delete Modal mis tareas -->
  <div id="eliminar_Vacaciones" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="functions/eliminar_Vacaciones.php" method="POST">
          <input type="hidden" name="idVacacionesD" id="idVacacionesD">
          <input type="hidden" name="idEstatusD" id="idEstatusD">
          <div class="modal-header">
            <h4 class="modal-title">Eliminar vacaciones</h4>
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


  <script>
  function obtenerIdVacacionesEliminar(id, idEstatus) {
    //alert(id + "/" + idEstatus);
    document.getElementById('idVacacionesD').value = id;
    document.getElementById('idEstatusD').value = idEstatus;
  }

  function obtenerIdModificarEstatus(id, idEstatus) {
    document.getElementById('idPermisoU').value = id;
    document.getElementById('idEstatus').value = idEstatus;
    document.getElementById('idPermisoCanc').value = id;
    document.getElementById('idEstatusCanc').value = idEstatus;
  }


  $(document).ready(function() {
    $("#alertaTareas").load('../alerta_Tareas_Nuevas.php?user=' + <?=$_SESSION['PKUsuario'];?> + '&ruta=' +
      '<?=$ruta;?>');
    setInterval(refrescar, 5000);
  });

  function refrescar() {
    $("#alertaTareas").load('../alerta_Tareas_Nuevas.php?user=' + <?=$_SESSION['PKUsuario'];?> + '&ruta=' +
      '<?=$ruta;?>');
  }

  $(document).ready(function() {
    $("#alertaVacaciones").load('../alerta_Vacaciones.php?user=' + <?=$_SESSION['PKUsuario'];?> + '&ruta=' +
      '<?=$ruta;?>');
    setInterval(refrescar, 5000);
  });

  function refrescar() {
    $("#alertaVacaciones").load('../alerta_Vacaciones.php?user=' + <?=$_SESSION['PKUsuario'];?> + '&ruta=' +
      '<?=$ruta;?>');
  }
  </script>
  <script>
  var ruta = "../";
  </script>
  <script src="../../js/sb-admin-2.min.js"></script>


</body>

</html>