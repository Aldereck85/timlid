<?php
session_start();
require_once '../../../include/db-conn.php';
$user = $_SESSION["Usuario"];
$idemp = $_SESSION["IDEmpresa"];
/*if (isset($_SESSION["Usuario"])) {
require_once '../../../include/db-conn.php';
$user = $_SESSION["Usuario"];
} else {
header("location:../../../dashboard.php");
}*/
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <link rel="icon" type="image/png" href="../../../img/header/bluTimlid.png">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Timlid | Categoría gastos</title>

  <!-- Bootstrap core JavaScript-->
  <script src="../../../vendor/jquery/jquery.min.js"></script>
  <script src="../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>


  <!-- Core plugin JavaScript-->
  <script src="../../../vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="../../../js/sb-admin-2.min.js"></script>
  <script src="../../../js/sweet/sweetalert2.js"></script>

  <!-- Page level plugins -->
  <script src="../../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../../vendor/datatables/dataTables.responsive.js"></script>
  <script src="../../../vendor/datatables/dataTables.bootstrap4.min.js"></script>
  <script src="../../../vendor/datatables/dataTables.buttons.js"></script>
  <script src="../../../vendor/datatables/buttons.bootstrap4.min.js"></script>
  <script src="../../../vendor/datatables/buttons.html5.min.js"></script>
  <script src="../../../vendor/jszip/jszip.min.js"></script>
  <script src="../../../js/slimselect.min.js"></script>

  <!-- Custom fonts for this template -->
  <link href="../../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet">

  <!-- Custom styles for this page -->
  <link href="../../../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="../../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../../css/styles.css" rel="stylesheet">
  <link href="../../../css/stylesTable.css" rel="stylesheet">
  <link href="../../../css/lobibox.min.css" rel="stylesheet">
  <script src="../../../js/lobibox.min.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">

  <style>
  .nav-link {
    padding: .5rem;
  }
  </style>
</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <?php
$titulo = "Configuraciones";
$ruta = "../../";
$ruteEdit = $ruta . "central_notificaciones/";
$icono = '../../../img/inventarios/ICONO INVENTARIOS Y PROD_Mesa de trabajo 1.svg';
require_once '../../menu3.php';
?>
    <!-- End of Sidebar -->
    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <?php
$rutatb = "../../";
require_once $rutatb . 'topbar.php';
?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="row">
            <div class="col-lg-12">
              <ul class="nav nav-tabs">
                <li class="nav-item">
                  <a id="CargarUsuarios" class="nav-link" href="../">
                    Usuarios
                  </a>
                </li>
                <li class="nav-item">
                  <a id="CargarPuestos" class="nav-link" href="../puestos">
                    Puestos
                  </a>
                </li>
                <li class="nav-item">
                  <a id="CargarTurnos" class="nav-link" href="../turnos">
                    Turnos
                  </a>
                </li>
                <li class="nav-item">
                  <a id="CargarSucursales" class="nav-link" href="../sucursales">
                    Sucursales
                  </a>
                </li>
                <li class="nav-item">
                  <a id="CargarCategoriaProductos" class="nav-link" href="../categoria_productos">
                    Categoría de productos
                  </a>
                </li>
                <li class="nav-item">
                  <a id="CargarMarcaDeProductos" class="nav-link" href="../marca_productos">
                    Marcas de productos
                  </a>
                </li>
                <li class="nav-item">
                  <a id="CargarCategoriaGastos" class="nav-link active" href="../categoria_gastos">
                    Categoría gastos
                  </a>
                </li>
                <li class="nav-item">
                  <a id="CargarSubategoriaGastos" class="nav-link" href="../subcategorias_gastos">
                    Subcategoría de gastos
                  </a>
                </li>
                <li class="nav-item">
                  <a id="CargarResponsableGastos" class="nav-link" href="../responsables_gastos">
                    Responsable de gastos
                  </a>
                </li>
                <li class="nav-item">
                  <a id="CargarTipoOrdenInventario" class="nav-link" href="../tipo_orden_inventario">
                    Tipo de orden de inventario
                  </a>
                </li>
              </ul>
            </div>
          </div>

          <!-- DataTales Example -->
          <div class="card mb-4">
            <div class="card-header py-3">
              <div class="float-right">
                <div class="button-container2" id="">
                  <div class="button-icon-container">
                    <a href="functions/agregar_Categoria_Gastos.php"
                      class="btn btn-circle float-right waves-effect waves-light shadow btn-table" id=""
                      data-toggle="modal" data-target="#agregar_CategoriaGastos">
                      <i class="fas fa-plus"></i>
                    </a>
                  </div>
                  <div class="button-text-container">
                    <span>Agregar categoría</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="table-responsive">
              <input type="hidden" name="emp_id" id="emp_id" value="<?php echo $idemp ?>">
                <table class="table" id="tblCategoriaGastos" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>id</th>
                      <th>No. Categoria</th>
                      <th>Nombre</th>
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

  <!--ADD MODAL SLIDE ESTATUS-->
  <div class="modal fade right" id="agregar_CategoriaGastos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
    <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">
      <div class="modal-content">
        <form action="" id="agregarCategoria" method="POST">
          <div class="modal-header">
            <h4 class="modal-title w-100" id="myModalLabel">Agregar categoría</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">x</span>
            </button>
          </div>
          <div class="modal-body">
            <input class="form-control" id="notaCategoria" name="notaCategoria" type="hidden"
              style="color: darkred; background-color: transparent!important; border: none;"
              value="Nota: Nombre de la categoría ya registrada." readonly>
            <div class="form-group">
              <label for="usr">Nombre:*</label>
              <input type="text" class="form-control alpha-only" maxlength="30" id="txtNombreCategoria"
                name="txtNombreCategoria" onkeyup="validarUnicaCategoriaGasto()">
              <div class="invalid-feedback" id="invalid-nombreGasto">El gasto debe tener un nombre.</div>
            </div>
          </div>
          <div class="modal-footer justify-content-center">
            <button type="button" class="btnesp first espCancelar btnCancelarActualizacion" data-dismiss="modal"
              id="btnCancelarActualizacion"><span class="ajusteProyecto">Cancelar</span></button>
            <button type="button" class="btnesp espAgregar float-right" name="btnAgregar" id="btnAgregarCategoria"><span
                class="ajusteProyecto">Agregar</span></button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!--END ADD MODAL SLIDE PUESTOS-->

  <div class="modal fade right" id="modalEditar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-full-height modal-right modal-md" role="document">
      <div class="modal-content">
        <form action="" id="editarCategoriaU" method="POST">
          <input type="hidden" name="idCategoriaU" id="idCategoriaU">
          <div class="modal-header">
            <h4 class="modal-title w-100" id="myModalLabel">Editar categoría</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">x</span>
            </button>
          </div>
          <div class="modal-body">
            <input class="form-control" id="notaExisteRelacion" name="notaExisteRelacion" type="hidden"
              style="color: darkred; background-color: transparent!important; border: none;"
              value="Nota: La categoría está siendo utilizada." readonly>
            <div class="form-group">
              <label for="usr">Nombre:*</label>
              <input type="text" id="txtNombreU" class="form-control alpha-only" maxlength="40" name="txtNombreU"
                onkeyup="validarUnicaCategoriaGastoU()" required>
              <div class="invalid-feedback" id="invalid-nombreGastoEdit">El gasto debe tener un nombre.</div>
            </div>
            <div class="modal-footer justify-content-center">
              <a class="btnesp first espEliminar" href="#" onclick="eliminarCategoria(this.value);" name="idCategoriaD"
                id="idCategoriaD" data-toggle="modal" data-target=""><span class="ajusteProyecto">Eliminar
                  categoría</span></a>
              <button type="button" class="btnesp first espCancelar btnCancelarActualizacion" data-dismiss="modal"
                0id="btnCancelarActualizacion"><span class="ajusteProyecto">Cancelar</span></button>
              <button type="button" class="btnesp espAgregar float-right" name="btnEditar" id="btnEditarCategoria"
                value=""><span class="ajusteProyecto">Modificar</span></button>
            </div>
        </form>
      </div>
    </div>
  </div>
  <!--END UPDATE MODAL -->

  <script>
  var ruta = "../../";
  </script>
  <script src="js/categoria_gastos.js"></script>
  <script src="../../../js/sb-admin-2.min.js"></script>
</body>

</html>