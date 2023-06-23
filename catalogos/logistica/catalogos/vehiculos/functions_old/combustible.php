<?php
session_start();

  if(isset($_SESSION["Usuario"]) && ($_SESSION["FKRol"] == 4 || $_SESSION["FKRol"] == 1)){
    require_once('../../include/db-conn.php');
    if(isset($_GET['id'])){
        $id =  $_GET['id'];
      }
    $user = $_SESSION["Usuario"];
  }else {
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

  <title>Timlid | Combustible</title>

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

  <!-- Page level custom scripts
  <script src="js/demo/datatables-demo.js"></script>
  -->

  <!-- Custom fonts for this template -->
  <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="../../css/sb-admin-2.min.css" rel="stylesheet">

  <!-- Custom styles for this page -->
  <link href="../../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
  <link href="../../css/styles.css" rel="stylesheet">

  <script>
    $(document).ready(function(){
    var id = $("#txtId").val();
      var idioma_espanol = {
          "sProcessing":     "Procesando...",
          "sLengthMenu":     "Mostrar _MENU_ registros",
          "sZeroRecords":    "No se encontraron resultados",
          "sEmptyTable":     "Ningún dato disponible en esta tabla",
          "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
          "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
          "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
          "sInfoPostFix":    "",
          "sSearch":         "Buscar:",
          "sUrl":            "",
          "sInfoThousands":  ",",
          "sLoadingRecords": "Cargando...",
          "oPaginate": {
              "sFirst":    "Primero",
              "sLast":     "Último",
              "sNext":     "Siguiente",
              "sPrevious": "Anterior"
          },
          "oAria": {
              "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
              "sSortDescending": ": Activar para ordenar la columna de manera descendente"
          }
      }
      $("#tblPuestos").dataTable(
      {
        "ajax":"functions/function_Combustible.php?id="+id,
          "columns":[
            {"data":"Fecha"},
            {"data":"Cantidad"},
            {"data":"Costo total"},
            {"data":"Odometro"},
            {"data":"Tanque lleno"},
            {"data":"Usuario"},
            {"data":"Acciones"}
          ],
          "language": idioma_espanol,
            columnDefs: [
              { orderable: false, targets: 0}
            ],
            responsive: true
      }

      )
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

        <!-- Topbar -->
        <?php
          $rutatb = "../";
          $titulo = '<div class="header-screen">
                                  <div class="header-title-screen">
                                  <h1 class="h3 mb-2">Timdesk  <img src="' . $rutatb . '../img/timdesk/timdesk_icon.svg" alt="" style="position:relative;top:-5px;left:-7px;"></h1>
                                  </div>
                                 </div>';
          require_once $rutatb . 'topbar.php';
        ?>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <h1 class="h3 mb-2 text-gray-800">Carga de combustible</h1>
          <p class="mb-4">Información de cargas de combustible</p>

          <!-- DataTales Example -->
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <a href="functions/agregar_Carga.php?id=<?php echo $id ?>" class="btn btn-success float-right" ><i class="fas fa-plus"></i> Agregar carga </a>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table stripe" id="tblPuestos" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Fecha</th>
                      <th>Cantidad</th>
                      <th>Costo total</th>
                      <th>Odometro</th>
                      <th>Tanque lleno</th>
                      <th>Usuario</th>
                      <th>Acciones</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <th>Fecha</th>
                      <th>Cantidad</th>
                      <th>Costo total</th>
                      <th>Odometro</th>
                      <th>Tanque lleno</th>
                      <th>Usuario</th>
                      <th>Acciones</th>
                    </tr>
                  </tfoot>
                  <tbody>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <input type="hidden" name="txtId" id="txtId" value="<?=$id;?>">
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

      <!-- Footer -->
      <?php
        $rutaf = "../";
        require_once('../footer.php');
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
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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

  <script type="text/javascript">
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

</body>

</html>
