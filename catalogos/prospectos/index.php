<?php
session_start();

if (isset($_SESSION["Usuario"])) {
    require_once '../../include/db-conn.php';
    $user = $_SESSION["Usuario"];

    $stmt = $conn->prepare('SELECT count(*) FROM clientes WHERE FKEstatus = 1');
    $stmt->execute();
    $numero_de_toque1 = $stmt->fetchColumn();

    $stmt = $conn->prepare('SELECT count(*) FROM clientes WHERE FKEstatus = 2');
    $stmt->execute();
    $numero_de_toque2 = $stmt->fetchColumn();

    $stmt = $conn->prepare('SELECT count(*) FROM clientes WHERE FKEstatus = 3');
    $stmt->execute();
    $numero_de_toque3 = $stmt->fetchColumn();

    $stmt = $conn->prepare('SELECT count(*) FROM clientes WHERE FKEstatus = 4');
    $stmt->execute();
    $numero_de_clientes = $stmt->fetchColumn();

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

  <title>Timlid | Prospectos</title>

  <!-- Bootstrap core JavaScript-->
  <script src="../../vendor/jquery/jquery.min.js"></script>
  <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="../../js/sb-admin-2.min.js"></script>
  <script src="../../js/scripts.js"></script>

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
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" rel="stylesheet">

  <script>
  $(document).ready(function() {
    $("#txtToken1").val(0);
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

    $("#tblToque1").dataTable({
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
      "ajax": "functions/function_Prospectos.php?toque=1",
      "columns": [{
          "data": "Id prospecto"
        },
        {
          "data": "Nombre comercial"
        },
        {
          "data": "Medio de contacto"
        },
        {
          "data": "Fecha de alta"
        },
        {
          "data": "Vendedor"
        },
      ]
    })

    $("#tblToque2").dataTable({
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
      "ajax": "functions/function_Prospectos.php?toque=2",
      "columns": [{
          "data": "Id prospecto"
        },
        {
          "data": "Nombre comercial"
        },
        {
          "data": "Medio de contacto"
        },
        {
          "data": "Fecha de alta"
        },
        {
          "data": "Vendedor"
        },
      ]
    })

    $("#tblToque3").dataTable({
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
      "ajax": "functions/function_Prospectos.php?toque=3",
      "columns": [{
          "data": "Id prospecto"
        },
        {
          "data": "Nombre comercial"
        },
        {
          "data": "Medio de contacto"
        },
        {
          "data": "Fecha de alta"
        },
        {
          "data": "Vendedor"
        },
      ]
    })

    $("#tblToque4").dataTable({
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
      "ajax": "functions/function_Prospectos.php?toque=4",
      "columns": [{
          "data": "Id prospecto"
        },
        {
          "data": "Nombre comercial"
        },
        {
          "data": "Medio de contacto"
        },
        {
          "data": "Fecha de alta"
        },
        {
          "data": "Vendedor"
        },
      ]
    })

    $("#txtBuscar").keyup(function() {
      $('table').DataTable().search(this.value).draw();
    });

  });

  function ocultarToken1() {
    $("#cardToken1").toggle("slide");
    var iconButton = $("#btnToken1").html();
    if (iconButton == '<i class="fas fa-angle-up"></i>') {
      $("#btnToken1").html('<i class="fas fa-angle-down"></i>');
    } else {
      $("#btnToken1").html('<i class="fas fa-angle-up"></i>');
    }
  }

  function ocultarToken2() {
    $("#cardToken2").toggle("slide");
    var iconButton = $("#btnToken2").html();
    if (iconButton == '<i class="fas fa-angle-up"></i>') {
      $("#btnToken2").html('<i class="fas fa-angle-down"></i>');
    } else {
      $("#btnToken2").html('<i class="fas fa-angle-up"></i>');
    }
  }

  function ocultarToken3() {
    $("#cardToken3").toggle("slide");
    var iconButton = $("#btnToken3").html();
    if (iconButton == '<i class="fas fa-angle-up"></i>') {
      $("#btnToken3").html('<i class="fas fa-angle-down"></i>');
    } else {
      $("#btnToken3").html('<i class="fas fa-angle-up"></i>');
    }
  }

  function ocultarToken4() {
    $("#cardToken4").toggle("slide");
    var iconButton = $("#btnToken4").html();
    if (iconButton == '<i class="fas fa-angle-up"></i>') {
      $("#btnToken4").html('<i class="fas fa-angle-down"></i>');
    } else {
      $("#btnToken4").html('<i class="fas fa-angle-up"></i>');
    }
  }
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
$titulo = "Prospectos";
require_once '../topbar.php';
?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <h1 class="h3 mb-2 text-gray-800"><i class="fas fa-address-book"></i> Prospectos</h1>
          <p class="mb-4">Información general de Prospectos</p>
          <br>
          <div class="row">
            <div class="col-lg-12">
              <!-- <a href="functions/agregar_Prospecto.php" class="btn btn-success btn-circle float-right"><i
                  class="fas fa-plus"></i></a> -->
              <div class="float-right">
                <div class="button-container2" id="">
                  <div class="button-icon-container">
                    <a href="" class="btn btn-circle float-right waves-effect waves-light shadow btn-table" id=""
                      data-toggle="modal" data-target=""><i class="fas fa-plus"></i></a>
                  </div>
                  <div class="button-text-container">
                    <span>Agregar prospecto</span>
                  </div>
                </div>
              </div>
            </div>
          </div><br>
          <div class="row clientbar">
            <div class="col-lg-3">
              <div class="card bg-success text-white shadow">
                <div class="card-body">
                  Toque 1
                  <div class="text-white-50 small">Total: <label> <?php echo $numero_de_toque1 ?></label></div>
                </div>
              </div>
            </div>
            <div class="col-lg-3">
              <div class="card bg-warning text-white shadow">
                <div class="card-body">
                  Toque 2
                  <div class="text-white-50 small">Total: <label> <?php echo $numero_de_toque2 ?></label></div>
                </div>
              </div>
            </div>
            <div class="col-lg-3">
              <div class="card bg-danger text-white shadow">
                <div class="card-body">
                  Toque 3
                  <div class="text-white-50 small">Total: <label> <?php echo $numero_de_toque3 ?></label></div>
                </div>
              </div>
            </div>
            <div class="col-lg-3">
              <div class="card bg-primary text-white shadow">
                <div class="card-body">
                  Clientes
                  <div class="text-white-50 small">Total: <label> <?php echo $numero_de_clientes ?></label></div>
                </div>
              </div>
            </div>
          </div>
          <br>

          <div class="row">
            <div class="col-lg-12">
              <div class="input-group mb-3">
                <input type="text" class="form-control alphaNumeric-only" placeholder="Buscar en todas las tablas..."
                  id="txtBuscar">
                <div class="input-group-append">
                  <span class="input-group-text" id="basic-addon2"><i class="fas fa-search"></i></span>
                </div>
              </div>

            </div>
          </div>
          <br>
          <!-- DataTales Example -->
          <div class="card shadow mb-4">
            <div class="card-header bg-success card-headerToken1 py-3">
              <div class="row">
                <div class="col-lg-6">
                  <label style="color:white;font-weight:bold;">Prospectos en toque 1</label>
                </div>
                <div class="col-lg-6">
                  <button class="btn btn-success float-right" id="btnToken1" onclick="ocultarToken1();"><i
                      class="fas fa-angle-up"></i></button>
                </div>
              </div>
            </div>
            <div class="card-body" id="cardToken1">
              <div class="table-responsive">
                <table class="table stripe" id="tblToque1" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Id prospecto</th>
                      <th>Nombre comercial</th>
                      <th>Medio de contacto</th>
                      <th>Fecha de alta</th>
                      <th>Vendedor</th>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>
          </div>

          <div class="card shadow mb-4">
            <div class="card-header bg-warning card-headerToken2 py-3">
              <div class="row">
                <div class="col-lg-6">
                  <label style="color:white;font-weight:bold;">Prospectos en toque 2</label>
                </div>
                <div class="col-lg-6">
                  <button class="btn btn-warning float-right" id="btnToken2" onclick="ocultarToken2();"><i
                      class="fas fa-angle-up"></i></button>
                </div>
              </div>
            </div>
            <div class="card-body" id="cardToken2">
              <div class="table-responsive">
                <table class="table stripe" id="tblToque2" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Id prospecto</th>
                      <th>Nombre comercial</th>
                      <th>Medio de contacto</th>
                      <th>Fecha de alta</th>
                      <th>Vendedor</th>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>
          </div>

          <div class="card shadow mb-4">
            <div class="card-header bg-danger card-headerToken3 py-3">
              <div class="row">
                <div class="col-lg-6">
                  <label style="color:white;font-weight:bold;">Prospectos en toque 3</label>
                </div>
                <div class="col-lg-6">
                  <button class="btn btn-danger float-right" id="btnToken3" onclick="ocultarToken3();"><i
                      class="fas fa-angle-up"></i></button>
                </div>
              </div>
            </div>
            <div class="card-body" id="cardToken3">
              <div class="table-responsive">
                <table class="table stripe" id="tblToque3" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Id prospecto</th>
                      <th>Nombre comercial</th>
                      <th>Medio de contacto</th>
                      <th>Fecha de alta</th>
                      <th>Vendedor</th>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>
          </div>

          <div class="card shadow mb-4">
            <div class="card-header bg-primary card-headerToken4 py-3">
              <div class="row">
                <div class="col-lg-6">
                  <label style="color:white;font-weight:bold;">Clientes</label>
                </div>
                <div class="col-lg-6">

                  <button class="btn btn-primary float-right" id="btnToken4" onclick="ocultarToken4();"><i
                      class="fas fa-angle-up"></i></button>
                  <a href="../clientes" class="btn btn-primary float-right"><i class="fas fa-users"></i> Catálogo
                    clientes </a>&nbsp;&nbsp;
                </div>
              </div>
            </div>
            <div class="card-body" id="cardToken4">
              <div class="table-responsive">
                <table class="table" id="tblToque4" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Id prospecto</th>
                      <th>Nombre comercial</th>
                      <th>Medio de contacto</th>
                      <th>Fecha de alta</th>
                      <th>Vendedor</th>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>
          </div>

        </div>
        <!-- /.container-fluid -->
        <input type="hidden" name="txtToken1" value="0">
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

  <script>
  function obtenerIdProspectoEliminar(id) {
    document.getElementById('idProspectoD').value = id;
  }

  function obtenerIdProspectoEditar(id) {
    document.getElementById('idProspectoU').value = id;
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
  </script>
  <script>
  var ruta = "../";
  </script>
  <script src="../../js/sb-admin-2.min.js"></script>

</body>

</html>