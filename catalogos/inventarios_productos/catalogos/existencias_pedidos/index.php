<?php
session_start();
require_once '../../../../include/db-conn.php';
$user = $_SESSION["Usuario"];
/*if (isset($_SESSION["Usuario"]) && ($_SESSION["FKRol"] == 4 || $_SESSION["FKRol"] == 1)) {
require_once '../../../../include/db-conn.php';
$user = $_SESSION["Usuario"];
} else {
header("location:../../../dashboard.php");
}*/
/* SUCURSALES */
$stmt = $conn->prepare('SELECT id, sucursal FROM sucursales WHERE empresa_id = :empresa AND estatus = 1');
$stmt->execute([':empresa' => $_SESSION['IDEmpresa']]);
$sucData = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* CATEGORIAS */
$stmt = $conn->prepare('SELECT PKCategoriaProducto, CategoriaProductos FROM categorias_productos WHERE (empresa_id = :empresa OR empresa_id = 1) AND estatus = 1');
$stmt->execute([':empresa' => $_SESSION['IDEmpresa']]);
$sucCat = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="icon" type="image/png" href="../../../../img/header/bluTimlid.png">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Timlid | Existencias pedidos</title>

  <!-- ESTILOS -->
  <link href="../../../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../../../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../../../css/slimselect.min.css" rel="stylesheet">
  <link href="../../../../css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="../../../..//vendor/datatables/responsive.dataTables.css" rel="stylesheet">
  <link href="../../../../vendor/datatables/buttons.dataTables.css" rel="stylesheet">
  <link href="../../../../css/notificaciones.css" rel="stylesheet">
  <link href="../../../../css/lobibox.min.css" rel="stylesheet">
  <link href="../../../../css/styles.css" rel="stylesheet">
  <link href="../../../../css/stylesNewTable.css" rel="stylesheet">
  <!-- JS -->
  <script src="../../../../vendor/jquery/jquery.min.js"></script>
  <script src="../../../../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../../../vendor/datatables/dataTables.buttons.js"></script>
  <script src="../../../../vendor/datatables/buttons.html5.min.js"></script>
  <script src="../../../../vendor/jszip/jszip.min.js"></script>
</head>

<body id="page-top" class="sidebar-toggled">
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
        $icono = '../../../../img/inventarios/ICONO INVENTARIOS Y PROD_Mesa de trabajo 1.svg';
        $titulo = 'Existencias pedidos';
        require_once $rutatb . 'topbar.php';
        ?>

        <!-- Begin Page Content -->
        <input type="hidden" id="txtUsuario" value="<?= $_SESSION['PKUsuario']; ?>">
        <input type="hidden" id="txtRuta" value="<?= $ruta; ?>">
        <input type="hidden" id="txtEdit" value="<?= $ruteEdit; ?>">
        <div class="container-fluid">

          <!-- DataTales Example -->
          <div class="card mb-4">
            <div class="card-body">
              <div class="filtros mb-4">
                <form>
                  <div class="form-row">
                    <div class="col-md-3 mb-3">
                      <label for="sucursal_input">Sucursal</label>
                      <select name="" class="form-control" id="sucursal_input" onchange="validEmptyInput(this)">
                        <option disabled selected>Selecciona una sucursal</option>
                        <option value="todas">Todas</option>
                        <?php foreach ($sucData as $sucItem) { ?>
                          <option value="<?= $sucItem["id"] ?>"><?= $sucItem["sucursal"] ?></option>
                        <?php } ?>
                      </select>
                      <div class="invalid-feedback" id="invalid-sucursal">
                        La sucursal no puede estar vacia.
                      </div>
                    </div>
                    <div class="col-md-3 mb-3">
                      <label for="existencia_input">Existencias</label>
                      <select name="" class="form-control" id="existencia_input" onchange="validEmptyInput(this)">
                        <option disabled selected>Selecciona la existencia</option>
                        <option value="todos">Todas</option>
                        <option value="noExistencia">Sin existencias</option>
                        <option value="existencia">Con existencias</option>
                      </select>
                      <div class="invalid-feedback" id="invalid-exitencia">
                        La existencia no puede estra vacia.
                      </div>
                    </div>
                    <div class="col-md-3 mb-3">
                      <button id="fitro_inventario" type="button" class="btn-custom btn-custom--blue ml-md-5">Filtrar</button>
                    </div>
                  </div>
                </form>
              </div>
              <div class="table-responsive">
                <table class="table" id="tblListadoInventario" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Sucursal</th>
                      <th>Clave</th>
                      <th>Cantidad pendiente de surtir</th>
                      <th>Existencia</th>
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

  <!-- MODAL INVENTARIOS INICIO -->
  <div class="modal fade right" id="editStockModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
    <div class="modal-dialog modal-full-height modal-right modal-md" role="document">
      <div class="modal-content">
        <form>
          <input type="hidden" id="id-stock">
          <div class="modal-header">
            <h4 class="modal-title w-100" id="myModalLabel">Editar stock</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">x</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label for="usr">Clave producto: <span class="text-dark" id="stock-producto"></span></label>
            </div>
            <div class="form-group">
              <label for="usr">Lote/serie producto: <span class="text-dark" id="stock-serieLote"></span></label>
            </div>
            <div class="form-group">
              <label for="usr">Stock mínimo:</label>
              <input type="text" class="form-control numeric-only" maxlength="10" id="stock-minimo">
              <div class="invalid-feedback" id="invalid-minStock">El stock minimo no puede ser mayor que el stock maximo.</div>
            </div>
            <div class="form-group">
              <label for="usr">Stock máximo:</label>
              <input type="text" class="form-control numeric-only" maxlength="10" id="stock-maximo">
              <div class="invalid-feedback" id="invalid-entradaEdit">La hora de entrada es requerida.</div>
            </div>
          </div>
          <div class="modal-footer justify-content-center">
            <button type="button" class="btnesp first espCancelar btnCancelarActualizacion" data-dismiss="modal">Cancelar</button>
            <button type="button" class="btn-custom btn-custom--blue" onclick="setInfoStock()">Guardar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!--MODAL INVENTARIOS FIN-->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>
  <script src="../../../../js/sb-admin-2.min.js"></script>
  <script src="../../../../js/slimselect.min.js"></script>
  <script src="../../../../js/validaciones.js"></script>
  <script src="../../../../js/scripts.js"></script>
  <script src="../../../../js/lobibox.min.js"></script>
  <script src="../../../../js/notificaciones_timlid.js" charset="utf-8"></script>
  <script src="../../js/inventario_copy.js" charset="utf-8"></script>
  <script>
    loadAlertsNoti('alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
    setInterval(loadAlertsNoti, 10000, 'alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());

    new SlimSelect({
      select: "#sucursal_input",
    });
    new SlimSelect({
      select: "#existencia_input",
    });
  </script>
</body>

</html>