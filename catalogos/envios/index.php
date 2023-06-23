<?php
session_start();

if (isset($_SESSION["Usuario"])) {
    //require_once '../../include/db-conn.php';
    $user = $_SESSION["Usuario"];
    $pkususario = $_SESSION["PKUsuario"];
    $ruta = "../";
    $screen = 25;
    require_once $ruta . 'validarPermisoPantalla.php';
    if($permiso === 0){
      header("location:../dashboard.php");
    }
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

  <title>Timlid | Envios</title>

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
  <link href="../../css/styles.css" rel="stylesheet">
  <link href="../../css/stylesTable.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">

  <link rel="stylesheet" href="../../css/notificaciones.css">
  <script src="../../js/notificaciones_timlid.js" charset="utf-8"></script>

</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper" class="sidebar-toggled">

    <?php
      $titulo = "Cambiar";
      
      $ruteEdit = $ruta . "central_notificaciones/";
      require_once '../menu3.php';
    ?>
    </ul>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
      <input type="hidden" id="txtUsuario" value="<?=$_SESSION['PKUsuario'];?>">
      <input type="hidden" id="txtRuta" value="<?=$ruta;?>">
      <input type="hidden" id="txtEdit" value="<?=$ruteEdit;?>">
      <input type="hidden" id="txtPantalla" value="<?=$screen;?>">

      <!-- Main Content -->
      <div id="content">

        <?php
          $rutatb = "../";
          require_once '../topbar.php';
        ?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <h1 class="h3 mb-2 text-gray-800">Envios</h1>
          <p class="mb-4">Control de envios</p>

          <!-- DataTales Example -->
          <div class="card mb-4">
            <div class="card-header py-3">
              <div class="float-right">
                <div class="button-container2" id="">
                  <div class="button-icon-container">
                    <a href="functions/agregar_Envios.php"
                      class="btn btn-circle float-right waves-effect waves-light shadow btn-table"><i
                        class="fas fa-plus"></i></a>
                  </div>
                  <div class="button-text-container">
                    <span>Agregar envio</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table" id="tblEnvios" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Id de envio</th>
                      <th>Numero de rastreo</th>
                      <th>Estatus</th>
                      <th>Factura</th>
                      <th>Paqueteria</th>
                      <th>Fecha Envio</th>
                      <th>Fecha Entrega</th>
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

  <!-- Delete Modal envios -->
  <div id="eliminar_Envio" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="functions/eliminar_Envios.php" method="POST">
          <input type="hidden" name="idEnvioD" id="idEnvioD">
          <div class="modal-header">
            <h4 class="modal-title">Eliminar envio</h4>
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

  <!-- Update Modal envios -->
  <div id="editar_Envio" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="functions/editar_Envios.php" method="POST">
          <input type="hidden" name="idEnvioU" id="idEnvioU">
          <div class="modal-header">
            <h4 class="modal-title">Editar envio</h4>
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
  function obtenerIdEnvioEliminar(id) {
    document.getElementById('idEnvioD').value = id;
  }

  function obtenerIdEnvioEditar(id) {
    document.getElementById('idEnvioU').value = id;
  }

  
  </script>
  <script>
  //var ruta = "../";
  </script>
  <script src="../../js/sb-admin-2.min.js"></script>
  <script src="../../js/scripts.js"></script>
  <script src="js/envios.js"></script>


</body>

</html>