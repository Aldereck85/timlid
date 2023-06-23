<?php
session_start();

if (isset($_SESSION["Usuario"])) {
    require_once '../../include/db-conn.php';
    $user = $_SESSION["Usuario"];
    $fechaHoy = date('Y-m-d', time());
    $fecha = $fechaHoy . " 06:00:00";
    $stmt = $conn->prepare('SELECT * FROM semanas_checador WHERE FechaInicio <= :fechaInicio AND FechaTermino >= :fechaTermino');
    $stmt->bindValue(':fechaInicio', $fecha);
    $stmt->bindValue(':fechaTermino', $fecha);
    $stmt->execute();
    $row = $stmt->fetch();
    $semana = $row['PKChecador'];
    //echo $semana;

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


  <title>Timlid | Bono mensual de asistencia</title>

  <!-- Bootstrap core JavaScript-->
  <script src="../../vendor/jquery/jquery.min.js"></script>
  <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="../../js/sb-admin-2.min.js"></script>
  <script src="../../js/scripts.min.js"></script>

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
  <link href="../../css/styles.css" rel="stylesheet">
  <link href="../../css/stylesTable.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">

  <script>
  $(document).ready(function() {
    var table;
    var semana = $("#txtId").val();
    $("#tblEmpleados").hide();

    $("#tblEmpleados").show();
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
    $("#tblEmpleados").dataTable({
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
      "ajax": "functions/function_empleados.php?turno=6&semana=" + semana,
      "columns": [{
          "data": "Id empleado"
        },
        {
          "data": "Primer nombre"
        },
        {
          "data": "Segundo nombre"
        },
        {
          "data": "Apellido paterno"
        },
        {
          "data": "Apellido materno"
        },
        {
          "data": "Puesto"
        },
        {
          "data": "Fecha de ingreso"
        },
        {
          "data": "Estatus"
        },
        {
          "data": "Acciones"
        }
      ]
    })

    $("#tblVespertino").dataTable({
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
      "ajax": "functions/function_empleados.php?turno=2&semana=" + semana,
      "columns": [{
          "data": "Id empleado"
        },
        {
          "data": "Primer nombre"
        },
        {
          "data": "Segundo nombre"
        },
        {
          "data": "Apellido paterno"
        },
        {
          "data": "Apellido materno"
        },
        {
          "data": "Puesto"
        },
        {
          "data": "Fecha de ingreso"
        },
        {
          "data": "Estatus"
        },
        {
          "data": "Acciones"
        }
      ]
    })

    $("#tblMixto").dataTable({
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
      "ajax": "functions/function_empleados.php?turno=7&semana=" + semana,
      "columns": [{
          "data": "Id empleado"
        },
        {
          "data": "Primer nombre"
        },
        {
          "data": "Segundo nombre"
        },
        {
          "data": "Apellido paterno"
        },
        {
          "data": "Apellido materno"
        },
        {
          "data": "Puesto"
        },
        {
          "data": "Fecha de ingreso"
        },
        {
          "data": "Estatus"
        },
        {
          "data": "Acciones"
        }
      ]
    })

    $("#tblNocturno").dataTable({
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
      "ajax": "functions/function_empleados.php?turno=1&semana=" + semana,
      "columns": [{
          "data": "Id empleado"
        },
        {
          "data": "Primer nombre"
        },
        {
          "data": "Segundo nombre"
        },
        {
          "data": "Apellido paterno"
        },
        {
          "data": "Apellido materno"
        },
        {
          "data": "Puesto"
        },
        {
          "data": "Fecha de ingreso"
        },
        {
          "data": "Estatus"
        },
        {
          "data": "Acciones"
        }
      ]
    })
  });
  </script>

</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <?php
$ruta = "../";
$ruteEdit = $ruta . "central_notificaciones/";
require_once '../menu3.php';
?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <?php
$rutatb = "../";
require_once '../topbar.php';
?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <h1 class="h3 mb-2 text-gray-800"><i class="fas fa-money-check-alt"></i> Bono mensual de asistencia</h1>
          <p class="mb-4">Lista de empleados por turno</p>

          <!-- DataTales Example -->
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <b>Turno matutino</b>
            </div>
            <div class="card-body">
              <input type="hidden" name="txtId" id="txtId" value="<?=$semana;?>">
              <br>
              <div class="table-responsive">
                <table class="table stripe" id="tblEmpleados" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Id empleado</th>
                      <th>Primer nombre</th>
                      <th>Segundo nombre</th>
                      <th>Apellido paterno</th>
                      <th>Apellido materno</th>
                      <th>Puesto</th>
                      <th>Fecha de ingreso</th>
                      <th>Estatus</th>
                      <th>Acciones</th>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>
          </div>

          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <b>Turno Vespertino</b>
            </div>
            <div class="card-body">
              <br>
              <div class="table-responsive">
                <table class="table stripe" id="tblVespertino" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Id empleado</th>
                      <th>Primer nombre</th>
                      <th>Segundo nombre</th>
                      <th>Apellido paterno</th>
                      <th>Apellido materno</th>
                      <th>Puesto</th>
                      <th>Fecha de ingreso</th>
                      <th>Estatus</th>
                      <th>Acciones</th>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>
          </div>

          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <b>Turno Mixto</b>
            </div>
            <div class="card-body">
              <br>
              <div class="table-responsive">
                <table class="table stripe" id="tblMixto" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Id empleado</th>
                      <th>Primer nombre</th>
                      <th>Segundo nombre</th>
                      <th>Apellido paterno</th>
                      <th>Apellido materno</th>
                      <th>Puesto</th>
                      <th>Fecha de ingreso</th>
                      <th>Estatus</th>
                      <th>Acciones</th>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>
          </div>

          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <b>Turno Nocturno</b>
            </div>
            <div class="card-body">
              <br>
              <div class="table-responsive">
                <table class="table stripe" id="tblNocturno" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Id empleado</th>
                      <th>Primer nombre</th>
                      <th>Segundo nombre</th>
                      <th>Apellido paterno</th>
                      <th>Apellido materno</th>
                      <th>Puesto</th>
                      <th>Fecha de ingreso</th>
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

  <script type="text/javascript">
  $(document).ready(function() {
    $("#alertaTareas").load('../alerta_Tareas_Nuevas.php?user=' + <?=$_SESSION['PKUsuario'];?> + '&ruta=' +
      '<?=$ruta;?>');
    setInterval(refrescar, 5000);
  });

  function refrescar() {
    $("#alertaTareas").load('../alerta_Tareas_Nuevas.php?user=' + <?=$_SESSION['PKUsuario'];?> + '&ruta=' +
      '<?=$ruta;?>');
  }
  </script>
  <script>
  var ruta = "../";
  </script>
  <script src="../../js/sb-admin-2.min.js"></script>

</body>

</html>