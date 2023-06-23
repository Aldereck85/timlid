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
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="icon" type="image/png" href="../../img/header/bluTimlid.png">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Timlid | Cuentas Por pagar</title>

  <!-- ESTILOS -->
  <link href="../../css/slimselect.min.css" rel="stylesheet">
  <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="../../vendor/datatables/buttons.dataTables.css" rel="stylesheet">
  <link href="../../css/styles.css" rel="stylesheet">
  <link href="../../css/stylesNewTable.css" rel="stylesheet">
  <link href="../../css/stylesModal-lg.css" rel="stylesheet">
  <link href="../../css/lobibox.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/datetime/1.1.0/css/dataTables.dateTime.min.css" rel="stylesheet">
  <link href="css\searchbuilder.css" rel="stylesheet">
  <link rel="stylesheet" href="../../css/notificaciones.css">
  <!-- JS -->
  <script src="../../vendor/jquery/jquery.min.js"></script>
  <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../../js/sweet/sweetalert2.js"></script>
  <script src="../../js/validaciones.js"></script>
  <script src="../../js/lobibox.min.js"></script>
  <script src="../../js/slimselect.min.js"></script>
  <script src="../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../vendor/datatables/dataTables.buttons.js"></script>
  <script src="../../vendor/datatables/buttons.html5.min.js"></script>
  <script src="../../vendor/jszip/jszip.min.js"></script>
  <script src="https://cdn.datatables.net/searchbuilder/1.1.0/js/dataTables.searchBuilder.min.js"></script>
  <script src="https://cdn.datatables.net/datetime/1.1.0/js/dataTables.dateTime.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
  <script src="../../js/notificaciones_timlid.js" charset="utf-8"></script>
  <script src="js/cuentas_proveer.js"></script>
</head>

<body id="page-top" class="sidebar-toggled">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Content Wrapper -->
    <!-- Sidebar -->
    <?php
    $titulo = "Cuentas por pagar";
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
        $backIcon = true;
        $icono = 'ICONO-CUENTAS-POR-PAGAR-AZUL.svg';
        require_once "../topbar.php"
        ?>
        <!-- End of Topbar -->
        <input type="hidden" id="txtUsuario" value="<?= $_SESSION['PKUsuario']; ?>">
        <input type="hidden" id="txtRuta" value="<?= $ruta; ?>">
        <input type="hidden" id="txtEdit" value="<?= $ruteEdit; ?>">
        <!-- Begin Page Content -->
        <div class="container-fluid">
          <!-- Comprobar permisos para estar en la pagina -->
          <?php
          ///Primera parte comprueba si puede ver
          $pkuser = $_SESSION["PKUsuario"];
          $stmt = $conn->prepare("Select funcion_ver, funcion_agregar, funcion_editar, funcion_eliminar, funcion_exportar, 
                pantalla_id, fp.perfil_id from funciones_permisos as fp inner join usuarios as us 
                on fp.perfil_id = us.perfil_id where us.id = $pkuser and pantalla_id = 27");
          $stmt->execute();
          $row = $stmt->fetch();
          //Ponemos en el DOM el permiso ver
          echo ('<input id="ver" type="hidden" value="' . $row['funcion_ver'] . '">');
          ?>

          <!-- Page Heading -->
          <!--<h1 class="h3 mb-2 text-gray-800">Control vehicular</h1>
          <p class="mb-4">Información general de los vehiculos</p>-->
          <input type="hidden" id="proveedor_id" value="<?php echo ($_GET["id"]); ?>" />
          <input type="hidden" id="periodo" value="<?php echo (int)($_GET['periodo']); ?>" />
          <input type="hidden" id="toggle" value="<?php echo (int)($_GET['av']); ?>" />
          <!-- DataTales Example -->
          <div class="card mb-4">
            <div class="card-header py-3">
              Tabla de cuenta del proveedor
              <div id="mod" class="btn-group float-right" role="group" aria-label="Basic example">
                <a href="../pagos/agregar.php?idprovee=<?php echo ($_GET["id"]); ?>&toDo=1&av=<?php echo (int)($_GET['av']); ?>&periodo=<?php echo (int)($_GET['periodo']); ?>&id=<?php echo ($_GET["id"]); ?>" type="button" class="btn btn-secondary">Modulo de Pagos</a>
                <a type="button" class="btn btn-secondary">Notas de Crédito</a>
                <a href=""><img class="delete-icon" id="delete-icon-361" src="../../img/timdesk/delete.svg"></a>
              </div>
            </div>
            <div class="form-group">
              <div class="row">
                <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-xs-6" style="margin-left:20px;">
                <label for="txtDateFrom">De:</label>
                <input class="form-control" type="date" name="txtDateFrom" id="txtDateFrom" required>
                <div class="invalid-feedback" id="invalid-txtDateFrom"></div>
                </div>
                <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-xs-6">
                  <label for="txtDateTo">Hasta:</label>
                  <input class="form-control" type="date" name="txtDateTo" id="txtDateTo" required>
                  <div class="invalid-feedback" id="invalid-txtDateTo">.</div>
                </div>
                <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-xs-6">
                  <a class="btn-custom btn-custom--blue" id="btnFilterExits" style="margin-top: 10px!important">Filtrar</a>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table" id="tblVehiculos" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Proveedor</th>
                      <th>Folio de Factura</th>
                      <th>Serie de Factura</th>
                      <th>Subtotal</th>
                      <th>Importe</th>
                      <th>Saldo insoluto</th>
                      <th>F. de Expedicion</th>
                      <th>F. de Vencimiento</th>
                      <th>Vencimiento</th>
                      <th>Estatus</th>
                      <th>Editar</th>
                    </tr>
                  </thead>
                </table>
              </div>
              <!-- <a type="button" class="btn-custom btn-custom--border-blue btnCancelarActualizacion" href="../cuentas_pagar">Regresar</a> -->

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
  <script src="../../js/sb-admin-2.min.js"></script>
  <script src="../../js/scripts.js"></script>
  <script>
    loadAlertsNoti('alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
    setInterval(loadAlertsNoti, 10000, 'alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit')
      .val());
  </script>
  <?php
  require_once 'modal_alert.php';
  ?>
  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>


</body>


</html>