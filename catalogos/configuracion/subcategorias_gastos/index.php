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

  <title>Timlid | Subategoría gastos</title>

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
  

  <!-- Custom fonts for this template -->
  <link href="../../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet">

  <!-- Custom styles for this page -->
  <link href="../../../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
  <link href="../../../vendor/datatables/responsive.dataTables.css" rel="stylesheet">
  <link rel="stylesheet" href="../../../vendor/datatables/buttons.dataTables.css">
  <link href="../../../vendor/datatables/buttons.bootstrap4.min.css" rel="stylesheet">



  <!-- Custom styles for this template -->
  <link href="../../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../../css/styles.css" rel="stylesheet">
  <link href="../../../css/stylesTable.css" rel="stylesheet">
  <link href="../../../css/lobibox.min.css" rel="stylesheet">
  <script src="../../../js/lobibox.min.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
  <link href="../../../css/slimselect.min.css" rel="stylesheet">

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
                  <a id="CargarCategoriaGastos" class="nav-link" href="../categoria_gastos">
                    Categoría gastos
                  </a>
                </li>
                <li class="nav-item">
                  <a id="CargarSubategoriaGastos" class="nav-link active" href="">
                    Subcategoría de gastos
                  </a>
                </li>
                <li class="nav-item">
                  <a id="CargarResponsableGastos" class="nav-link" href="../responsables_gastos">
                    Responsables de gastos
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
                      data-toggle="modal" data-target="#agregar_SubcategoriaGastos">
                      <i class="fas fa-plus"></i>
                    </a>
                  </div>
                  <div class="button-text-container">
                    <span>Agregar subcategoría</span>
                  </div>
                </div>
              </div>
            </div>
            <input type="hidden" name="emp_id" id="emp_id" value="<?php echo $idemp ?>">
            <div class="card-body">
              <div class="table-responsive">
                <table class="table" id="tblSubcategoriaGastos" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>id</th>
                      <th>No. Subcategoria</th>
                      <th>Nombre</th>
                      <th>Categoría</th>
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
  <div class="modal fade right" id="agregar_SubcategoriaGastos" tabindex="-1" role="dialog"
    aria-labelledby="myModalLabel" aria-hidden="true">
    <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
    <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">
      <div class="modal-content">
        <form id="agregarSubcategoria">
          <!--<input type="hidden" name="idProyectoA" value="">-->
          <div class="modal-header">
            <h4 class="modal-title w-100" id="myModalLabel">Agregar subcategoría</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">x</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label for="usr">Nombre:*</label>
              <input type="text" class="form-control alpha-only" maxlength="30" id="txtNombreSubcategoria"
                name="txtNombreSubcategoria" onkeyup="validarUnicaSubCatGasto()" required>
              <div class="invalid-feedback" id="invalid-nombreSubCat">La subcategoría de gastos debe tener un nombre.
              </div>
            </div>
            <div class="form-group">
              <label for="usr">Categoría:*</label>
              <select name="cmbCategoria" class="form-control" id="cmbCategoria" onchange="validEmptyInput(this)"
                required>
                
              </select>
              <div class="invalid-feedback" id="invalid-nombreCat">La subcategoría de tener una categoria.
              </div>
            </div>
            <label for="usr">* Campos requeridos</label>
          </div>

          <div class="modal-footer justify-content-center">
            <button type="button" class="btnesp first espCancelar btnCancelarActualizacion" data-dismiss="modal"
              id="btnCancelarActualizacion"><span class="ajusteProyecto">Cancelar</span></button>
            <button type="button" class="btnesp espAgregar float-right" name="btnAgregar"
              id="btnAgregarSubcategoria"><span class="ajusteProyecto">Agregar</span></button>
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
        <form action="" id="editarSubcategoriaU" method="POST">
          <input type="hidden" name="idSubcategoriaU" id="idSubcategoriaU">
          <input type="hidden" name="txtFKCategoriaU" id="txtFKCategoriaU">
          <div class="modal-header">
            <h4 class="modal-title w-100" id="myModalLabel">Editar subcategoría</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">x</span>
            </button>
          </div>
          <div class="modal-body">

            <input class="form-control" id="notaExisteRelacion" name="notaExisteRelacion" type="hidden"
              style="color: darkred; background-color: transparent!important; border: none;"
              value="Nota: La subcategoría está siendo utilizada." readonly>

            <div class="form-group">
              <label for="usr">Nombre:*</label>
              <input type="text" id="txtNombreU" class="form-control alpha-only" maxlength="40" name="txtNombreU"
                value="" onkeyup="validarUnicaSubCatGastoU()" required>
              <div class="invalid-feedback" id="invalid-nombreSubCatEdit">La subcategoría de gastos debe tener un
                nombre.
              </div>
            </div>
            <div class="form-group">
              <label for="usr">Categoría:*</label>
              <select name="cmbCategoriaU" class="form-control" id="txtCategoriaU" required onchange="verCategoria()">
              </select>
              <div class="invalid-feedback" id="invalid-nombreCatEdit">La subcategoría de gastos debe tener una
                categoria.
              </div>
            </div>
            <label for="usr">* Campos requeridos</label>

            <div class="modal-footer justify-content-center">
              <a class="btnesp first espEliminar" href="#" onclick="eliminarSubcategoria(this.value);"
                name="idSubcategoriaD" id="idSubcategoriaD" data-toggle="modal" data-target=""><span
                  class="ajusteProyecto">Eliminar
                  subcategoría</span></a>
              <button type="button" class="btnesp first espCancelar btnCancelarActualizacion" data-dismiss="modal"
                0id="btnCancelarActualizacion"><span class="ajusteProyecto">Cancelar</span></button>
              <button type="button" class="btnesp espAgregar float-right" name="btnEditar" id="btnEditarSubcategoria"
                value=""><span class="ajusteProyecto">Modificar</span></button>
            </div>
        </form>
      </div>
    </div>
  </div>
  <!--END UPDATE MODAL -->

  <script>
  /* Reiniciar el modal al cerrarlo */
  $("#agregar_SubcategoriaGastos").on("hidden.bs.modal", function(e) {
    $("#invalid-nombreSubCat").css("display", "none");
    $("#txtNombreSubcategoria").removeClass("is-invalid");
    $("#txtNombreSubcategoria").val("");
    $("#invalid-nombreCat").css("display", "none");
    $("#cmbCategoria").removeClass("is-invalid");
  });

  $("#modalEditar").on("hidden.bs.modal", function(e) {
    $("#invalid-nombreSubCatEdit").css("display", "none");
    $("#txtNombreU").removeClass("is-invalid");
    $("#txtNombreU").val("");
    $("#invalid-nombreCatEdit").css("display", "none");
    $("#txtCategoriaU").removeClass("is-invalid");
  });
  </script>

  <script>
  var ruta = "../../";
  </script>
  <!-- <script src="js/subcategorias_gastos.js"></script> -->
  <script src="../../../js/sb-admin-2.min.js"></script>
  <!-- <script src="../../../js/scripts.js"></script> -->
  <script src="../../../js/slimselect.min.js"></script>

</body>

</html>