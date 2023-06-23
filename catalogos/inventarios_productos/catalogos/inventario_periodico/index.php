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

?>
<!DOCTYPE html>
<html lang="es">

<head>
  <link rel="icon" type="image/png" href="../../../../img/header/bluTimlid.png">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Timlid | Inventario</title>

  <!-- Bootstrap core JavaScript-->
  <script src="../../../../vendor/jquery/jquery.min.js"></script>


  <!-- Core plugin JavaScript-->
  <script src="../../../../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Page level plugins -->
  <script src="../../../../js/sweet/sweetalert2.js"></script>
  <script src="../../../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../../../vendor/datatables/dataTables.bootstrap4.min.js"></script>
  <script src="../../../../vendor/datatables/dataTables.buttons.js"></script>
  <script src="../../../../vendor/datatables/buttons.bootstrap4.min.js"></script>
  <script src="../../../../vendor/datatables/buttons.html5.min.js"></script>
  <script src="../../../../vendor/jszip/jszip.min.js"></script>

  <!-- Custom fonts for this template -->
  <link href="../../../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">


  <!-- Custom styles for this template -->
  <link href="../../../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../../../css/slimselect.min.css" rel="stylesheet">

  <!-- Custom styles for this page -->
  <link rel="stylesheet" href="../../../../css/sweetalert2.css">
  <link rel="stylesheet" href="../../../../css/pagination/pagination.css">
  <link href="../../../../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
  <link href="../../../..//vendor/datatables/responsive.dataTables.css" rel="stylesheet">
  <link rel="stylesheet" href="../../../../vendor/datatables/buttons.dataTables.css">
  <link href="../../../../vendor/datatables/buttons.bootstrap4.min.css" rel="stylesheet">
  <link href="../../../../css/stylesTable.css" rel="stylesheet">
  <link rel="stylesheet" href="../../../../css/notificaciones.css">
  <link rel="stylesheet" href="../../style/inventario_periodico.css">
  <link href="../../../../css/lobibox.min.css" rel="stylesheet">
  <link href="../../../../css/styles.css" rel="stylesheet">
   <link href="../../../../css/stylesNewTable.css" rel="stylesheet">

  <script src="../../../../js/notificaciones_timlid.js" charset="utf-8"></script>
  <script src="../../js/inventario_periodico.js" charset="utf-8"></script>
</head>

<body id="page-top" class="sidebar-toggled">
  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <?php
//$ruta = "../../../";
$ruteEdit = "$ruta.central_notificaciones/";
require_once $ruta . 'menu3.php';
?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <?php
$rutatb = "../../../";
$icono = '../../../../img/menu/ICONO INVENTARIO GRIS_Mesa de trabajo 1.svg';
$titulo = 'Inventario';
require_once $rutatb . 'topbar.php';
?>

        <!-- Begin Page Content -->
        <input type="hidden" id="txtUsuario" value="<?=$_SESSION['PKUsuario'];?>">
        <input type="hidden" id="txtRuta" value="<?=$ruta;?>">
        <input type="hidden" id="txtEdit" value="<?=$ruteEdit;?>">
        <input type="hidden" id="txtPantalla" value="<?=$screen;?>">

        <div class="modal fade" id="modalNuevoInv" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Iniciar/Seguimiento inventario</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">x</span>
                        </button>
                    </div>
                <div class="modal-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <p><h5><b id="lblSucursal"></b></h5></p>
                        </div>
                        <div class="col-6">
                            <button id="iniciarSeguimientoInv" type="button" class="btn-custom btn-custom--blue" onclick="iniSeguiInv()">Iniciar inventario</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <p><b>Fecha inicio:</b></p>
                        </div>
                        <div class="col-8">
                            <p><b id="lblFechaIni">-</b></p>                            
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-2">
                            <p><b>Estatus:</b></p>
                        </div>
                        <div class="col-10">
                            <p><b id="lblEstatus">-</b></p>                            
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <p><a id="descargarLayout" href="javascript:descargarLayout()">Desacargar layout</a></p>
                        </div>
                    </div>
                </div>
                    <div class="modal-footer">
                        <button id="cancelInv" type="button" class="btn-custom btn-custom--border-blue" data-dismiss="modal" onclick="cancelInv()">Cancelar inventario</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">

            <div class="row">
                <div class="col">
                    <div class="card mb-4">
                        <div class="card-header py-3">

                        </div>
                        <div class="card-body">

                            <div class="row">
                                <div class="col">
                                    <div class="row justify-content-start my-3">
                                        <div class="col-xl-6 col-lg-6 col-md col-sm col-xs">
                                            <form class="form-inline">
                                                <label for="cmbSucursales">Sucursal:</label>
                                                <select class="form-select" id="cmbSucursales" aria-label="Default select example">
                                                </select>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                
                                </div>
                            </div>
                            <div class="row mt-md-0 mt-md-5 my-5" id="rowTabla">
                                <div class="col mt-md-5 my-5">
                                    <div class="table-responsive">
                                        <table class="table" id="tblConteosInventariosPorSucursales" class="display" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                <th class="d-none">IdInventario</th>
                                                <th>Conteo</th>
                                                <th>Estatus</th>
                                                <th>Usuario</th>
                                                <th>Numero de productos</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

          <!-- /.container-fluid-->

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

  <!-- INICIO MODAL-->
  <div class="modal fade" id="iniciomodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header d-flex">
          <img src="../../../../img/inventarios/warning_circle.svg" style="width: 20%;" class="ml-auto">
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">x</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="container">
                <div class="row">
                    <div class="col-12 mt-1 mb-2">
                        <span class="text-info">No se cuentan con sucursales que administren inventario</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer d-flex justify-content-around">
            <a class="btn-custom btn-custom--border-blue" href="../../../dashboard.php">Cerrar</a>
            <a class="btn-custom btn-custom--blue" href="../../../configuracion/#">Sucursales<i class="far fa-arrow-alt-circle-right ml-1"></i></a>
        </div>
      </div>
    </div>
  </div>

<script src="../../../../js/sb-admin-2.min.js"></script>
<script src="../../../../js/scripts.js"></script>
<script src="../../../../js/slimselect.min.js"></script>
<script src="../../../../js/Sortable.js"></script>
<script src="../../../../js/pagination/pagination.js"></script>
<script src="../../../../js/lobibox.min.js"></script>
<script>
    loadAlertsNoti('alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
    setInterval(loadAlertsNoti, 10000, 'alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
</script>
</body>
</html>