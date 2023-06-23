<?php
session_start();

if (isset($_SESSION["Usuario"])) {

  $user = $_SESSION["Usuario"];
  $PKEmpresa = $_SESSION["IDEmpresa"];

  $pkusuario = $_SESSION["PKUsuario"];
  $ruta = "../../../";
  $screen = 8;
  require_once $ruta . '../include/db-conn.php';

  $rutaServer = $_ENV['RUTA_ARCHIVOS_READ'] . $PKEmpresa . '/img' . '/';
} else {
  header("location:../../../dashboard.php");
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="icon" type="image/png" href="../../../../img/header/bluTimlid.png">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Timlid | Productos / Servicios</title>

  <!-- ESTILOS -->
  <link href="../../../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../../../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../../../css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="../../../../vendor/datatables/responsive.dataTables.css" rel="stylesheet">
  <link href="../../../../vendor/datatables/buttons.dataTables.css" rel="stylesheet">
  <link href="../../../../css/slimselect.min.css" rel="stylesheet">
  <link href="../../../../css/styles.css" rel="stylesheet">
  <link href="../../../../css/stylesNewTable.css" rel="stylesheet">
  <link href="../../../../css/lobibox.min.css" rel="stylesheet">
  <!-- <link href="../../style/productos.css" rel="stylesheet">
  <link href="../../style/pestanas_producto.css" rel="stylesheet"> -->
  <!-- JS -->
  <script src="../../../../vendor/jquery/jquery.min.js"></script>
  <script src="../../../../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../../../js/sb-admin-2.min.js"></script>
  <script src="../../../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../../../vendor/datatables/dataTables.buttons.js"></script>
  <script src="../../../../vendor/datatables/buttons.html5.min.js"></script>
  <script src="../../../../vendor/jszip/jszip.min.js"></script>
  <script src="../../../../js/notificaciones_timlid.js" charset="utf-8"></script>
  <script src="../../../../js/sweet/sweetalert2.js"></script>
  <script src="../../../../js/jquery.redirect.min.js"></script>
  <script src="../../../../js/Sortable.js"></script>
  <script src="../../../../js/slimselect.min.js"></script>
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
    $ruta = "../../../";
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
        $icono = 'ICONO-PRODUCTOS-SERVICIOS-AZUL.svg';
        $titulo = 'Lista de Producto/Servicio';
        require_once $rutatb . 'topbar.php';
        ?>

        <!-- Begin Page Content -->
        <input type="hidden" id="txtUsuario" value="<?= $_SESSION['PKUsuario']; ?>">
        <input type="hidden" id="txtRuta" value="<?= $ruta; ?>">
        <input type="hidden" id="txtEdit" value="<?= $ruteEdit; ?>">
        <input type="hidden" id="txtPantalla" value="Listado de productos">
        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <p class="mb-4"></p>

          <!-- DataTales Example -->
          <div class="card mb-4">
            <div class="card-body">
              <!-- INICIO FILTROS -->
              <div class="listaColumnas listaColumnasProductos d-none" id="listaColumnas">
                <div class="mt-2 text-center">
                  <strong>Mostrar columnas</strong>
                </div>
                <div class="row">
                  <div class="col-4">
                    <div class="pd-15 columna-item">
                      <div class="text-left" style="margin-right:10px;">
                        <div id="statusFiltroTarea" class="check-type-column checked-type-column checked-type-column-imgProductos filtro filtro-columna" data-column="0">
                        </div>
                      </div>
                      <div>
                        <span class="fs-15 colorG">Acciones</span>
                      </div>
                    </div>
                    <div class="pd-15 columna-item">
                      <div class="text-left" style="margin-right:10px;">
                        <div id="fechaFiltroTarea" class="check-type-column checked-type-column checked-type-column-imgProductos filtro filtro-columna" data-column="1">
                        </div>
                      </div>
                      <div>
                        <span class="fs-15 colorG">Nombre</span>
                      </div>
                    </div>
                    <div class="pd-15 columna-item">
                      <div class="text-left" style="margin-right:10px;">
                        <div id="fechaFiltroTarea" class="check-type-column checked-type-column checked-type-column-imgProductos filtro filtro-columna" data-column="2">
                        </div>
                      </div>
                      <div>
                        <span class="fs-15 colorG">Clave interna</span>
                      </div>
                    </div>
                    <div class="pd-15 columna-item">
                      <div class="text-left" style="margin-right:10px;">
                        <div id="fechaFiltroTarea" class="check-type-column checked-type-column checked-type-column-imgProductos filtro filtro-columna" data-column="3">
                        </div>
                      </div>
                      <div>
                        <span class="fs-15 colorG">Código de barras</span>
                      </div>
                    </div>
                  </div>
                  <div class="col-4">
                    <div class="pd-15 columna-item">
                      <div class="text-left" style="margin-right:10px;">
                        <div id="fechaFiltroTarea" class="check-type-column checked-type-column checked-type-column-imgProductos filtro filtro-columna" data-column="4">
                        </div>
                      </div>
                      <div>
                        <span class="fs-15 colorG">Categoría</span>
                      </div>
                    </div>
                    <div class="pd-15 columna-item">
                      <div class="text-left" style="margin-right:10px;">
                        <div id="fechaFiltroTarea" class="check-type-column checked-type-column checked-type-column-imgProductos filtro filtro-columna" data-column="5">
                        </div>
                      </div>
                      <div>
                        <span class="fs-15 colorG">Marca</span>
                      </div>
                    </div>
                    <div class="pd-15 columna-item">
                      <div class="text-left" style="margin-right:10px;">
                        <div id="fechaFiltroTarea" class="check-type-column checked-type-column checked-type-column-imgProductos filtro filtro-columna" data-column="6">
                        </div>
                      </div>
                      <div>
                        <span class="fs-15 colorG">Descripción</span>
                      </div>
                    </div>
                    <div class="pd-15 columna-item">
                      <div class="text-left" style="margin-right:10px;">
                        <div id="fechaFiltroTarea" class="check-type-column checked-type-column checked-type-column-imgProductos filtro filtro-columna" data-column="7">
                        </div>
                      </div>
                      <div>
                        <span class="fs-15 colorG">Tipo</span>
                      </div>
                    </div>
                  </div>
                  <div class="col-4">
                    <div class="pd-15 columna-item">
                      <div class="text-left" style="margin-right:10px;">
                        <div id="fechaFiltroTarea" class="check-type-column checked-type-column checked-type-column-imgProductos filtro filtro-columna" data-column="8">
                        </div>
                      </div>
                      <div>
                        <span class="fs-15 colorG">Imagen</span>
                      </div>
                    </div>
                    <div class="pd-15 columna-item">
                      <div class="text-left" style="margin-right:10px;">
                        <div id="fechaFiltroTarea" class="check-type-column checked-type-column checked-type-column-imgProductos filtro filtro-columna" data-column="9">
                        </div>
                      </div>
                      <div>
                        <span class="fs-15 colorG">Estatus</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- FIN FILTROS -->
              <div class="table-responsive">
                <table class="table" id="tblProductos" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Nombre</th>
                      <th>Clave interna</th>
                      <th>Código de barras</th>
                      <th>Categoría</th>
                      <th>Marca</th>
                      <th>Descripción</th>
                      <th>Tipo</th>
                      <th>Imagen</th>
                      <th>Estatus</th>
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
  </div>
  <!-- End of Content Wrapper -->
  </div>
  <!-- End of Page Wrapper -->

  <!--DELETE MODAL SLIDE MARCAS-->
  <div class="modal fade" id="eliminar_Producto" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form action="" method="POST">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">¿Desea eliminar el registro de este?</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true" class="text-light">x</span>
            </button>
          </div>
          <div>
            <input type="hidden" name="txtPKProductoD" id="txtPKProductoD">
            <br>
            <label for="usr" style="margin-left: 80px!important;">Se eliminará el producto con los siguientes
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
            <button type="submit" onclick="eliminarProducto();" class="btn-custom btn-custom--blue"><span class="ajusteProyecto" data-dismiss="modal">Eliminar</span></button>
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
          <h5 class="modal-title" id="exampleModalLabel">Productos</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">x</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="container">
            <div class="row">
              <div class="col-12">
                <label for="formato">Cualquier tipo de producto</label>
                <img id="formato" src="../../../../img/Productos/formato_excel_productos.jpeg" style="width: 100%;">
                <a href="exportLayout.php" class="mt-3">Descargar layout</a><br>
                <label for="formato" class="mt-3">Productos de materia prima</label>
                <img id="formatoMP" src="../../../../img/Productos/formato_materia_prima_productos.jpg" style="width: 100%;">
                <a href="exportLayoutMateriaPrima.php" class="mt-3">Descargar layout</a><br>
              </div>
              <div class="col-12 mt-1 mb-2">
                <span class="text-danger d-block">*Formatos aceptados: .XLS, .XLSX</span><br>
              </div>
              <div class="col-12">
                <form action="uploadExcel.php" method="post" enctype="multipart/form-data" id="formexcel">
                  <div class="row mb-3">
                    <p>Elige el tipo de productos a importar</p>
                    <label for="tipoImportacion">Tipo de productos:</label>
                    <select class="form-select" name="tipoImportacion" id="tipoImportacion">
                      <option value="1">Activo fijo</option>
                      <option value="2">Consumible</option>
                      <option value="3">Materia prima</option>
                      <option value="4">Producto</option>
                      <option value="5">Servicio</option>
                    </select>
                  </div>
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

    <script src="../../../../js/slimselect.min.js"></script>
    <script src="../../../../js/sb-admin-2.min.js"></script>
    <script src="../../../../js/scripts.js"></script>
    <script src="../../js/productos.js" charset="utf-8"></script>
    <!-- <script src="../../js/script_Productos.js" charset="utf-8"></script> -->
</body>

</html>