<?php
session_start();

if (isset($_SESSION["Usuario"])) {
    require_once '../../../include/db-conn.php';
} else {
    header("location:../../dashboard.php");
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="icon" type="image/png" href="../../../img/header/bluTimlid.png">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Timlid | Agregar empleado</title>

  <!-- ESTILOS -->
  <link href="../../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../../css/dashboard.css" rel="stylesheet">
  <link href="../../../css/chosen.css" rel="stylesheet">
  <link href="../../../css/chosen.min.css" rel="stylesheet" />
  <link href="../../../css/lobibox.min.css" rel="stylesheet">
  <link href="../../../css/slimselect.min.css" rel="stylesheet">
  <link href="../style/empleados.css" rel="stylesheet">
  <link href="../style/pestanas_empleado.css" rel="stylesheet">
  <link href="../../../css/notificaciones.css" rel="stylesheet">
  <link href="../../../css/styles.css" rel="stylesheet">
  <link href="../../../vendor/datatables/responsive.dataTables.css" rel="stylesheet">
  <link href="../../../vendor/datatables/buttons.dataTables.css" rel="stylesheet">
  <link href="../../../css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="../../../css/stylesNewTable.css" rel="stylesheet">

  <!-- JS -->
  <script src="../../../vendor/jquery/jquery.min.js"></script>
  <script src="../../../js/validaciones.js"></script>
  <script src="../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../../../js/lobibox.min.js"></script>
  <script src="../../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../../vendor/datatables/dataTables.responsive.js"></script>
  <script src="../../../vendor/datatables/dataTables.buttons.js"></script>
  <script src="../../../vendor/datatables/buttons.html5.min.js"></script>
  <script src="../../../vendor/jszip/jszip.min.js"></script>
</head>

<body id="page-top" class="sidebar-toggled">
  <?php
if (isset($_SESSION["Usuario"])) {

} else {
    header("location:../../dashboard.php");
}
?>


  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
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
$rutatb = "../../";
$titulo = 'Empleados';
$icono = '../../img/Empleados/ICONO LISTA DE EMPLEADOS_Mesa de trabajo 1.svg';
$backIcon = true;
$backRoute = "../";
require_once $rutatb . 'topbar.php';
?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <h2 class="color-primary">Agregar empleado</h2>

          <div class="row">

            <div class="col-lg-12">

              <ul class="nav nav-tabs">
                <li class="nav-item nav-item">
                  <a id="CargarDatosPersonales" class="nav-link nav-link--add" href="#">
                    Datos personales
                  </a>
                </li>
                <li class="nav-item nav-item">
                  <a id="CargarDatosLaborales" class="nav-link nav-link--add" href="#">
                    Datos Laborales
                  </a>
                </li>
                <li class="nav-item nav-item">
                  <a id="CargarDatosMedicos" class="nav-link nav-link--add" href="#">
                    Datos médicos
                  </a>
                </li>
                <li class="nav-item nav-item">
                  <a id="CargarDatosBancarios" class="nav-link nav-link--add" href="#">
                    Datos bancarios
                  </a>
                </li>
                <li class="nav-item nav-item">
                  <a id="CargarDatosRoles" class="nav-link nav-link--add" href="#">
                    Roles
                  </a>
                </li>
              </ul>

              <!-- Basic Card Example -->
              <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="datos" role="tabpanel" aria-labelledby="nav-main-tab">
                </div>
              </div>
            </div>

            <div id="modal"></div>

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

  <script src="../../../js/chosen.jquery.min.js"></script>
  <script src="../../../js/sb-admin-2.min.js"></script>
  <script src="../../../js/slimselect.min.js"></script>
  <script src="../../../js/scripts.js"></script>
  <script>
  $(document).ready(function() {
    $("#alertaTareas").load('<?=$ruta?>alerta_Tareas_Nuevas.php?user=' + <?=$_SESSION['PKUsuario'];?> + '&ruta=' +
      '<?=$ruta;?>&ruteEdit=<?=$ruteEdit;?>');
    setInterval(refrescar, 50000);
  });

  function refrescar() {
    $("#alertaTareas").load('<?=$ruta;?>alerta_Tareas_Nuevas.php?user=' + <?=$_SESSION['PKUsuario'];?> + '&ruta=' +
      '<?=$ruta;?>&ruteEdit=<?=$ruteEdit;?>');
  }
  </script>
  <script src="../js/pestanas_empleados.js"></script>
  <script>
  var ruta = "../../";
  </script>

  <script type="text/javascript">
  CargarDatosPersonales();
  </script>
</body>

</html>