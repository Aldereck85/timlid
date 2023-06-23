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
  $jwt_ruta = "../../../../";
  require_once '../../../jwt.php';
  $token = $_SESSION['token_ld10d'];

?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="icon" type="image/png" href="../../../../img/header/bluTimlid.png">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Timlid | Clientes</title>

  <!-- ESTILOS -->
  <link href="../../../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../../../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../../../css/styles.css" rel="stylesheet">
  <link href="../../../../css/sweetalert2.css" rel="stylesheet">
  <link href="../../../../css/pagination/pagination.css" rel="stylesheet">
  <link href="../../../../css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="../../../..//vendor/datatables/responsive.dataTables.css" rel="stylesheet">
  <link href="../../../../vendor/datatables/buttons.dataTables.css" rel="stylesheet">
  <link href="../../../../css/stylesNewTable.css" rel="stylesheet">
  <link href="../../../../css/notificaciones.css" rel="stylesheet">
  <link href="../../style/clientes.css" rel="stylesheet">
  <!-- JS -->
  <script src="../../../../vendor/jquery/jquery.min.js"></script>
  <script src="../../../../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../../../js/sweet/sweetalert2.js"></script>
  <script src="../../../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../../../vendor/datatables/dataTables.buttons.js"></script>
  <script src="../../../../vendor/datatables/buttons.html5.min.js"></script>
  <script src="../../../../vendor/jszip/jszip.min.js"></script>
</head>

<body id="page-top" class="sidebar-toggled">
  <!-- Imagen de fondo de cargando datos-->
  <div style="position: fixed;
                    left: 0px;
                    top: 0px;
                    width: 100%;
                    height: 100%;
                    z-index: 9999;
                    background: url('../../../../img/timdesk/Preloader.gif') 50% 50% no-repeat rgb(249,249,249);
                    opacity: .6;
                    display: none;" id="loaderValidacion">
    <h3 style="margin: 20% 38% 0%">Validando archivo...</h3>
  </div>
  <!-- Imagen de fondo de cargando datos-->
  <div style="position: fixed;
                    left: 0px;
                    top: 0px;
                    width: 100%;
                    height: 100%;
                    z-index: 9999;
                    background: url('../../../../img/timdesk/Preloader.gif') 50% 50% no-repeat rgb(249,249,249);
                    opacity: .6;
                    display: none;" id="loaderImportacion">
    <h3 style="margin: 20% 38% 0%">Importando archivo...</h3>
  </div>
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
        $icono = 'ICONO-CLIENTES-AZUL.svg';
        $titulo = 'Listado de clientes';
        require_once $rutatb . 'topbar.php';
        ?>

        <!-- Begin Page Content -->
        <input type="hidden" id="txtUsuario" value="<?= $_SESSION['PKUsuario']; ?>">
        <input type="hidden" id="txtRuta" value="<?= $ruta; ?>">
        <input type="hidden" id="txtEdit" value="<?= $ruteEdit; ?>">
        <input type="hidden" id="txtPantalla" value="<?= $screen; ?>">
        <div class="container-fluid">
          <!-- Page Heading -->
          <p class="mb-4"></p>

          <!-- DataTales Example -->
          <div class="card mb-4">
            <div class="card-body">
              <!-- INICIO FILTROS -->
              <div class="listaColumnas listaColumnasClientes d-none" id="listaColumnas">
                <div class="mt-2 text-center">
                  <strong>Mostrar columnas</strong>
                </div>
                <div class="row">
                  <div class="col-4">
                    <div class="pd-15 columna-item">
                      <div class="text-left" style="margin-right:10px;">
                        <div id="statusFiltroTarea" class="check-type-column checked-type-column checked-type-column-imgClientes filtro filtro-columna" data-column="0">
                        </div>
                      </div>
                      <div>
                        <span class="fs-15 colorG">Acciones</span>
                      </div>
                    </div>
                    <div class="pd-15 columna-item">
                      <div class="text-left" style="margin-right:10px;">
                        <div id="fechaFiltroTarea" class="check-type-column checked-type-column checked-type-column-imgClientes filtro filtro-columna" data-column="1">
                        </div>
                      </div>
                      <div>
                        <span class="fs-15 colorG">Nombre</span>
                      </div>
                    </div>
                    <div class="pd-15 columna-item">
                      <div class="text-left" style="margin-right:10px;">
                        <div id="fechaFiltroTarea" class="check-type-column checked-type-column checked-type-column-imgClientes filtro filtro-columna" data-column="2">
                        </div>
                      </div>
                      <div>
                        <span class="fs-15 colorG">Medio de contacto</span>
                      </div>
                    </div>
                    <div class="pd-15 columna-item">
                      <div class="text-left" style="margin-right:10px;">
                        <div id="fechaFiltroTarea" class="check-type-column checked-type-column checked-type-column-imgClientes filtro filtro-columna" data-column="3">
                        </div>
                      </div>
                      <div>
                        <span class="fs-15 colorG">Fecha de alta</span>
                      </div>
                    </div>
                    <div class="pd-15 columna-item">
                      <div class="text-left" style="margin-right:10px;">
                        <div id="fechaFiltroTarea" class="check-type-column checked-type-column checked-type-column-imgClientes filtro filtro-columna" data-column="4">
                        </div>
                      </div>
                      <div>
                        <span class="fs-15 colorG">Teléfono</span>
                      </div>
                    </div>
                  </div>
                  <div class="col-4">
                    <div class="pd-15 columna-item">
                      <div class="text-left" style="margin-right:10px;">
                        <div id="fechaFiltroTarea" class="check-type-column checked-type-column checked-type-column-imgClientes filtro filtro-columna" data-column="5">
                        </div>
                      </div>
                      <div>
                        <span class="fs-15 colorG">Email</span>
                      </div>
                    </div>
                    <div class="pd-15 columna-item">
                      <div class="text-left" style="margin-right:10px;">
                        <div id="fechaFiltroTarea" class="check-type-column checked-type-column checked-type-column-imgClientes filtro filtro-columna" data-column="6">
                        </div>
                      </div>
                      <div>
                        <span class="fs-15 colorG">Fecha del último contacto</span>
                      </div>
                    </div>
                    <div class="pd-15 columna-item">
                      <div class="text-left" style="margin-right:10px;">
                        <div id="fechaFiltroTarea" class="check-type-column checked-type-column checked-type-column-imgClientes filtro filtro-columna" data-column="7">
                        </div>
                      </div>
                      <div>
                        <span class="fs-15 colorG">Fecha del siguiente contacto</span>
                      </div>
                    </div>
                    <div class="pd-15 columna-item">
                      <div class="text-left" style="margin-right:10px;">
                        <div id="fechaFiltroTarea" class="check-type-column checked-type-column checked-type-column-imgClientes filtro filtro-columna" data-column="8">
                        </div>
                      </div>
                      <div>
                        <span class="fs-15 colorG">Monto de crédito</span>
                      </div>
                    </div>
                    <div class="pd-15 columna-item">
                      <div class="text-left" style="margin-right:10px;">
                        <div id="fechaFiltroTarea" class="check-type-column checked-type-column checked-type-column-imgClientes filtro filtro-columna" data-column="9">
                        </div>
                      </div>
                      <div>
                        <span class="fs-15 colorG">Días de credito</span>
                      </div>
                    </div>
                  </div>
                  <div class="col-4">
                    <div class="pd-15 columna-item">
                      <div class="text-left" style="margin-right:10px;">
                        <div id="fechaFiltroTarea" class="check-type-column checked-type-column checked-type-column-imgClientes filtro filtro-columna" data-column="10">
                        </div>
                      </div>
                      <div>
                        <span class="fs-15 colorG">Estatus del cliente</span>
                      </div>
                    </div>
                    <div class="pd-15 columna-item">
                      <div class="text-left" style="margin-right:10px;">
                        <div id="fechaFiltroTarea" class="check-type-column checked-type-column checked-type-column-imgClientes filtro filtro-columna" data-column="11">
                        </div>
                      </div>
                      <div>
                        <span class="fs-15 colorG">Vendedor</span>
                      </div>
                    </div>
                    <div class="pd-15 columna-item">
                      <div class="text-left" style="margin-right:10px;">
                        <div id="statusFiltroTarea" class="check-type-column checked-type-column checked-type-column-imgClientes filtro filtro-columna" data-column="12">
                        </div>
                      </div>
                      <div>
                        <span class="fs-15 colorG">Razón social</span>
                      </div>
                    </div>
                    <div class="pd-15 columna-item">
                      <div class="text-left" style="margin-right:10px;">
                        <div id="fechaFiltroTarea" class="check-type-column checked-type-column checked-type-column-imgClientes filtro filtro-columna" data-column="13">
                        </div>
                      </div>
                      <div>
                        <span class="fs-15 colorG">RFC</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- FIN FILTROS -->
              <div class="table-responsive">
                <table class="table" id="tblListadoClientes" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th></th>
                      <th>Nombre</th>
                      <th>Razón social</th>
                      <th>RFC</th>
                      <th>Teléfono</th>
                      <th>Email</th>
                      <th>Monto de crédito</th>
                      <th>Días de credito</th>
                      <th>Estatus del cliente</th>
                      <th>Vendedor</th>
                      <th>Medio de contacto</th>
                      <th>Fecha de alta</th>
                    </tr>
                  </thead>
                </table>
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
    <script src="../../../../js/sb-admin-2.min.js"></script>
    <script src="../../../../js/scripts.js"></script>
    <script src="../../../../js/notificaciones_timlid.js" charset="utf-8"></script>
    <script src="../../js/clientes.js" charset="utf-8"></script>
    <script src="../../js/script_Clientes.js" charset="utf-8"></script>
    <script src="../../../../js/Sortable.js"></script>
    <script src="../../../../js/pagination/pagination.js"></script>
</body>

</html>

<!--DELETE MODAL SLIDE CLIENTE-->
<div class="modal fade" id="eliminar_Cliente" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="" method="POST">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">¿Desea eliminar el registro de este?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            x
          </button>
        </div>
        <div>
          <input type="hidden" name="txtClienteD" id="txtClienteD">
          <br>
          <label for="usr" style="margin-left: 80px!important;">Se eliminará el cliente con los siguientes
            datos:</label>
        </div>

        <div class="form-group col-md-6">
          <label for="usr">Nombre:</label>
        </div>
        <div class="form-group col-md-12">
          <input type="text" style="border:none!important;" class="form-control" maxlength="50" id="txtNombre" name="txtNombre" required readonly>
        </div>

        <div class="modal-body" style="font-size: 10 px!important; color: red;">Esta acción no podrá deshacerse</div>
        <div class="modal-footer">
          <button class="btn-custom btn-custom--border-blue btnCancelarActualizacion" type="button" data-dismiss="modal"><span class="ajusteProyecto">Cancelar</span></button>
          <button type="submit" onclick="eliminarCliente();" class="btn-custom btn-custom--blue"><span class="ajusteProyecto" data-dismiss="modal">Eliminar</span></button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- EXCEL MODAL-->
<div class="modal fade" id="excelmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Clientes</h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">x</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="container">
          <div class="row">
            <div class="col-12">
              <img id="formatoMP" src="../../../../img/Clientes/formato_clientes.jpg" style="width: 100%;">
              <a href="exportLayout.php" class="mt-3">Descargar layout</a><br>
            </div>
            <div class="col-12 mt-1 mb-2">
              <span class="text-danger d-block">*Formatos aceptados: .XLS, .XLSX</span><br>
            </div>
            <div class="col-12">
              <form action="uploadExcel.php" method="post" enctype="multipart/form-data" id="formexcel">
                <div class="row">
                  <input type="file" class="btn-custom btn-custom--blue" id="dataexcel" name="dataexcel" accept=".xls,.xlsx">
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer d-flex justify-content-around">
        <button class="btn-custom btn-custom--border-blue" data-dismiss="modal">Cancelar</button>
        <button class="btn-custom btn-custom--blue" id="importExcel" data-dismiss="modal" onclick="validarExcel()">Importar</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalColumnsElement" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title add-columns-title">Centro de columnas</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true" class="white-text">x</span>
        </button>
      </div>
      <div class="container-fluid">
        <div id="list-all-columns" class="modal-body row p-0 pt-4">
        </div>
      </div>
    </div>
  </div>
</div>