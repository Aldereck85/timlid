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

  <title>Timlid | Claves SAT</title>

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

  <!-- Page level custom scripts
  <script src="js/demo/datatables-demo.js"></script>
  -->

  <!-- Custom fonts for this template -->
  <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../css/styles.css" rel="stylesheet">

  <!-- Custom styles for this page -->
  <link href="../../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

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
          <h1 class="h3 mb-2 text-gray-800">Claves de unidades del SAT</h1>
          <p class="mb-4">Información general de claves de unidades del SAT</p>

          <!-- DataTales Example -->
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <p class="mb-0">Habilitar o deshabilitar claves SAT</p>
            </div>
            <div class="card-body">
              <form action="" method="post">
                <input type="hidden" name="txtId" id="txtId">
                <div class="form-group">
                  <div class="row">
                    <div class="col-lg-3">
                      <label for="txtClaveSAT">Clave SAT</label>
                      <input class="form-control" type="text" name="txtClaveSAT" id="txtClaveSAT" maxlength=8>
                    </div>
                    <div class="col-lg-4">
                      <label for="txtDescripcion">Descripcion</label>
                      <input class="form-control" type="text" name="txtDescripcion" id="txtDescripcion" disabled>
                    </div>
                    <div class="col-lg-3">
                      <label for="txtEstatus">Estatus</label>
                      <input class="form-control" type="text" name="txtEstatus" id="txtEstatus" disabled>
                    </div>
                    <div class="col-lg-2" id="habilitar">
                      <button class="btn btn-primary" style="position: relative; top: 32px;width: 100%;" type="button"
                        name="btnHabilitar" id="btnHabilitar">Habilitar</button>
                    </div>
                    <div class="col-lg-2" id="deshabilitar" style="display:none">
                      <button class="btn btn-danger" style="position: relative; top: 32px;width: 100%;" type="button"
                        name="btnDeshabilitar" id="btnDeshabilitar">Deshabilitar</button>
                    </div>
                  </div>
                </div>
              </form>
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

  <!-- Delete Modal mis paqueterias -->
  <div id="eliminar_Paqueteria" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="functions/eliminar_Paqueteria.php" method="POST">
          <input type="hidden" name="idPaqueteriaD" id="idPaqueteriaD">
          <div class="modal-header">
            <h4 class="modal-title">Eliminar paqueteria</h4>
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

  <!-- Update Modal mis paqueterias -->
  <div id="editar_Paqueteria" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="functions/editar_Paqueteria.php" method="POST">
          <input type="hidden" name="idPaqueteriaU" id="idPaqueteriaU">
          <div class="modal-header">
            <h4 class="modal-title">Editar paqueteria</h4>
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
  function obtenerIdPaqueteriaEliminar(id) {
    document.getElementById('idPaqueteriaD').value = id;
  }

  function obtenerIdPaqueteriaEditar(id) {
    document.getElementById('idPaqueteriaU').value = id;
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
    $('#txtClaveSAT').change(function() {
      var cadena = "clave=" + $('#txtClaveSAT').val();
      $.ajax({
        type: 'POST',
        url: 'functions/getData.php',
        data: cadena,
        dataType: 'json',
        success: function(r) {
          var content = JSON.parse(r);
          $('#txtId').val(content.Id);
          $('#txtDescripcion').val(content.Descripcion);
          if (content.Estatus == 0) {
            $('#txtEstatus').val('Deshabilitada');

          } else {
            $('#txtEstatus').val('Habilitada');
            $('#habilitar').hide();
            $('#deshabilitar').show();
          }
        }
      });
    });
  });

  $(document).ready(function() {
    $('#btnHabilitar').click(function() {
      var cadena = "id=" + $('#txtId').val() + "&estado=Habilitar";
      $.ajax({
        url: 'functions/cambiar_Estatus.php',
        data: cadena,
        type: 'POST',
        success: function() {
          window.location.href = "index.php";
        }
      })
    });
  });
  $(document).ready(function() {
    $('#btnDeshabilitar').click(function() {
      var cadena = "id=" + $('#txtId').val() + "&estado=Deshabilitar";
      $.ajax({
        url: 'functions/cambiar_Estatus.php',
        data: cadena,
        type: 'POST',
        success: function() {
          window.location.href = "index.php";
        }
      })
    });
  });
  </script>
  <script>
  var ruta = "../";
  </script>
  <script src="../../js/sb-admin-2.min.js"></script>


</body>

</html>