<?php
session_start();

if (isset($_SESSION["Usuario"])) {
    require_once '../../include/db-conn.php';
    $user = $_SESSION["Usuario"];
} else {
    header("location:../dashboard.php");
}
/*
$timestamp = strtotime('2005-10-17');
$day = date('D', $timestamp);
echo $day;
 */
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

  <title>Timlid | Guias</title>

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
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">

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

    $("#tblGuias").dataTable({
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
        "ajax": "functions/function_Guia.php",
        "columns": [{
            "data": "No"
          },
          {
            "data": "Descripcion"
          },
          {
            "data": "Tipo de pago"
          },
          {
            "data": "Paqueteria"
          },
          {
            "data": "Acciones"
          }
        ],
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
$ruta = "../";
$ruteEdit = $ruta . "central_notificaciones/";
require_once '../menu3.php';
?>
    </ul>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <?php
$rutatb = "../";
$icono = '../../img/icons/ICONO GUIAS DE ENVIO-01.svg';
$titulo = "Guias de Envio";
require_once '../topbar.php';
?>

        <!-- Begin Page Content -->
        <div class="container-fluid">
          <!-- DataTales Example -->
          <div class="card mb-4">
            <div class="card-header py-3">
              <div class="float-right">
                <div class="button-container2" id="">
                  <div class="button-icon-container">
                    <a href="functions/agregar_Guia.php"
                      class="btn btn-circle float-right waves-effect waves-light shadow btn-table"><i
                        class="fas fa-plus"></i></a>
                  </div>
                  <div class="button-text-container">
                    <span>Agregar guia</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table" id="tblGuias" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Descripción</th>
                      <th>Tipo</th>
                      <th>Paqueteria</th>
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

  <!-- Delete Modal mis turnos -->
  <div id="eliminar_Guia" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="functions/eliminar_Guia.php" method="POST">
          <input type="hidden" name="idGuiaD" id="idGuiaD">
          <div class="modal-header">
            <h4 class="modal-title">Eliminar guia</h4>
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

  <!-- Update Modal mis turnos -->
  <div id="editar_Guia" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="functions/editar_Guia.php" method="POST">
          <input type="hidden" name="idGuiaU" id="idGuiaU">
          <div class="modal-header">
            <h4 class="modal-title">Editar guia</h4>
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

  <script>
  function obtenerIdGuiaEliminar(id) {
    document.getElementById('idGuiaD').value = id;
  }

  function obtenerIdGuiaEditar(id) {
    document.getElementById('idGuiaU').value = id;
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
  </script>
  <script>
  var ruta = "../";
  </script>
  <script src="../../js/sb-admin-2.min.js"></script>
  <script src="../../js/scripts.js"></script>
</body>

</html>