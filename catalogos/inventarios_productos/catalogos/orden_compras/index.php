<?php
session_start();

if (isset($_SESSION["Usuario"])) {

    $user = $_SESSION["Usuario"];

    $pkusuario = $_SESSION["PKUsuario"];
    $ruta = "../../../";
    $screen = 8;
    require_once $ruta . '../include/db-conn.php';
    /*require_once $ruta . 'validarPermisoPantalla.php';
if ($permiso === 0) {
header("location:../dashboard.php");
}*/
} else {
    header("location:../../../dashboard.php");
}
/*
$timestamp = strtotime('2005-10-17');
$day = date('D', $timestamp);
echo $day;
 */
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="icon" type="image/png" href="../../../../img/header/bluTimlid.png">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Timlid | Órdenes de compra</title>

  <!-- ESTILOS -->
  <link href="../../../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../../../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../../../css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="../../../../vendor/datatables/buttons.dataTables.css" rel="stylesheet">
  <link href="../../../../css/styles.css" rel="stylesheet">
  <link href="../../style/ordenesCompra.css" rel="stylesheet">
  <link href="../../../../css/stylesNewTable.css" rel="stylesheet">
  <link href="../../../../css/notificaciones.css" rel="stylesheet">
  <!-- JS -->
  <script src="../../../../vendor/jquery/jquery.min.js"></script>
  <script src="../../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../../../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../../../../js/sb-admin-2.min.js"></script>
  <script src="../../../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../../../vendor/datatables/dataTables.buttons.js"></script>
  <script src="../../../../vendor/datatables/buttons.html5.min.js"></script>
  <script src="../../../../vendor/jszip/jszip.min.js"></script>
  <script src="../../../../js/notificaciones_timlid.js" charset="utf-8"></script>
  <script src="../../js/ordenesCompra.js" charset="utf-8"></script>

</head>

<body id="page-top" class="sidebar-toggled">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <?php
$ruta = "../../../";
$ruteEdit = $ruta . "central_notificaciones/";
require_once $ruta . 'menu3.php';
?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
      <input type="hidden" id="txtUsuario" value="<?=$_SESSION['PKUsuario'];?>">
      <input type="hidden" id="txtRuta" value="<?=$ruta;?>">
      <input type="hidden" id="txtEdit" value="<?=$ruteEdit;?>">
      <input type="hidden" id="txtPantalla" value="13">

      <!-- Main Content -->
      <div id="content">

        <?php
$rutatb = "../../../";
$icono = 'ICONO-ORDENES-DE-COMPRA-AZUL.svg';
$titulo = 'Órdenes de compra';
require_once $rutatb . 'topbar.php';
?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <!--
          <div class="divPageTitle" style="margin-left:10px;">
            <img src="../../../../img/inventarios/ICONO INVENTARIOS Y PROD_Mesa de trabajo 1.svg" width="45px"
              style="position:relative;top:-10px;">
            <label class="lblPageTitle" style="margin-left:10px;font-weight:bold;">Órdenes de compra</label>
          </div>
          -->

          <!-- DataTales Example -->
          <div class="card mb-4">
            <div class="card-body">
              <div class="table-responsive">
                <table class="table" id="tblOrdenesCompra" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Id</th>
                      <th>Referencia</th>
                      <th>Fecha de emisión</th>
                      <th>Fecha estimada de entrega</th>
                      <th>Fecha de entrega</th>
                      <th>Proveedor</th>
                      <th>Importe</th>
                      <th>Estado de la orden</th>
                      <th></th>
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
$rutaf = "../../../";
require_once $rutaf . 'footer.php';
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

  <!-- Delete Modal orden compra -->
  <div id="eliminar_OrdenCompra" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="functions/eliminar_OrdenCompra.php" method="POST">
          <input type="hidden" name="idOrdenCompraD" id="idOrdenCompraD">
          <div class="modal-header">
            <h4 class="modal-title">Eliminar marca</h4>
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

  <!-- Update Modal orden compra -->
  <div id="editar_OrdenCompra" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="functions/editar_OrdenCompra.php" method="POST">
          <input type="hidden" name="idOrdenCompraU" id="idOrdenCompraU">
          <div class="modal-header">
            <h4 class="modal-title">Editar orden de compra</h4>
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

  <!-- Cancel Modal orden compra -->
  <div id="cancelar_OrdenCompra" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="functions/cancelar_OrdenCompra.php" method="POST">
          <input type="hidden" name="idOrdenCompraC" id="idOrdenCompraC">
          <div class="modal-header">
            <h4 class="modal-title">Cancelar orden de compra</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          </div>
          <div class="modal-body">
            <label for="">Motivo:*</label>
            <textarea class="form-control alphaNumeric-only" maxlength="100" name="txaMotivo" rows="5" cols="80"
              required></textarea>
            <label for="">* Campo obligatorio</label>
          </div>

          <div class="modal-footer">

            <input type="button" class="btn btn-secondary" data-dismiss="modal" value="Cancelar">
            <input type="submit" class="btn btn-primary" value="Enviar">
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
  function obtenerIdOrdenCompraEliminar(id) {
    document.getElementById('idOrdenCompraD').value = id;
  }

  function obtenerIdOrdenCompraEditar(id) {
    document.getElementById('idOrdenCompraU').value = id;
  }

  function obtenerIdOrdenCompraCancelar(id) {
    document.getElementById('idOrdenCompraC').value = id;
  }
  </script>
  <script src="../../../../js/sb-admin-2.min.js"></script>
  <script src="../../../../js/scripts.js"></script>
</body>
<script src="../../../../js/Sortable.js"></script>
<script src="../../../../js/pagination/pagination.js"></script>
<script>
loadAlertsNoti('alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
setInterval(loadAlertsNoti, 10000, 'alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
</script>

</html>