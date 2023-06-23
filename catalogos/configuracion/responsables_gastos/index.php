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

  <title>Timlid | Responsable de gastos</title>

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
  <script src="../../../js/slimselect.min.js"></script>

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
                  <a id="CargarSubategoriaGastos" class="nav-link" href="../subcategorias_gastos">
                    Subcategoría de gastos
                  </a>
                </li>
                <li class="nav-item">
                  <a id="CargarResponsableGastos" class="nav-link active" href="">
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
                  
                    <a href="functions/agregar_Responsable_Gastos.php"
                      class="btn btn-circle float-right waves-effect waves-light shadow btn-table" id=""
                      data-toggle="modal" data-target="#agregar_ResponsableGastos">
                      <i class="fas fa-plus"></i>
                    </a>
                  </div>
                  <div class="button-text-container">
                    <span>Agregar subcategoría</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="table-responsive">
              <input type="hidden" name="" id="emp_id" value="<?php echo $idemp ?>">
                <table class="table" id="tblResponsableGastos" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>id</th>
                      <th>No. Responsable</th>
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

  <!--ADD MODAL SLIDE RESPONSABLE GASTOS-->
  <div class="modal fade right" id="agregar_ResponsableGastos" tabindex="-1" role="dialog"
    aria-labelledby="myModalLabel" aria-hidden="true">
    <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
    <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">
      <div class="modal-content">
        <form id="agregarResponsable">
          <!--<input type="hidden" name="idProyectoA" value="">-->
          <div class="modal-header">
            <h4 class="modal-title w-100" id="myModalLabel">Agregar responsable de gasto</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">x</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label for="usr">Responsable:*</label>
              <select name="cmbEmpleado" id="cmbEmpleado" onchange="validarExisteResponsable()" required>
              </select>
              <div class="invalid-feedback" id="invalid-responsable">El responsable debe tener un nombre.</div>
            </div>
            <label for="usr">* Campos requeridos</label>
          </div>

          <div class="modal-footer justify-content-center">
            <button type="button" class="btnesp first espCancelar btnCancelarActualizacion" data-dismiss="modal"
              id="btnCancelarActualizacion"><span class="ajusteProyecto">Cancelar</span></button>
            <button type="button" class="btnesp espAgregar float-right" name="btnAgregar"
              id="btnAgregarResponsable"><span class="ajusteProyecto"
                onclick="agregarResponsable()">Agregar</span></button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!--END ADD MODAL SLIDE RESPONSABLE GASTOS-->

  <div class="modal fade right" id="modalEditar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-full-height modal-right modal-md" role="document">
      <div class="modal-content">
        <form action="" id="editarSubcategoriaU" method="POST">
          <input type="hidden" name="idResponsableU" id="idResponsableU">
          <input type="hidden" name="idFkResponsableU" id="idFkResponsableU">
          <div class="modal-header">
            <h4 class="modal-title w-100" id="myModalLabel">Editar responsable de gasto</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">x</span>
            </button>
          </div>
          <div class="modal-body">
            <input type="hidden" name="idResponsableN" id="idResponsableN">
            <input class="form-control" id="notaExisteRelacion" name="notaExisteRelacion" type="hidden"
              style="color: darkred; background-color: transparent!important; border: none;"
              value="Nota: El responsable esta siendo utilizado." readonly>

            <div class="form-group">
              <label for="usr">Nombre responsable:*</label>
              <input type="text" id="txtNombreU" class="form-control alpha-only" maxlength="40" name="txtNombreU"
                value="" disabled>
            </div>
            <div class="form-group">
              <input class="form-control" id="notaResponsableNuevo" name="notaResponsableNuevo" type="hidden"
                style="color: darkred; background-color: transparent!important; border: none;"
                value="Nota: Este responsable ya está registrado." readonly>
              <br>
              <label for="usr">Nuevo responsable:*</label>
              <select name="cmbResponsableU" class="form-control" id="txtResponsableU" onchange="nuevoResponsable()"
                required>
              </select>
              <div class="invalid-feedback" id="invalid-responsableEdit">El responsable debe tener un nombre.</div>
            </div>

            <label for="usr">* Campos requeridos</label>

            <div class="modal-footer justify-content-center">
              <a class="btnesp first espEliminar" href="#" onclick="eliminarResponsable(this.value);"
                name="idResponsableD" id="idResponsableD" data-toggle="modal" data-target=""><span
                  class="ajusteProyecto">Eliminar
                  responsable</span></a>
              <button type="button" class="btnesp first espCancelar btnCancelarActualizacionEd" data-dismiss="modal"
                id="btnCancelarActualizacionEd"><span class="ajusteProyecto">Cancelar</span></button>
              <button type="button" class="btnesp espAgregar float-right" name="btnEditar" id="btnEditarResponsable"
                value=""><span class="ajusteProyecto">Modificar</span></button>
            </div>
        </form>
      </div>
    </div>
  </div>
  <!--END UPDATE MODAL -->

  <script>
  /* Reiniciar el modal al cerrarlo */
  $("#agregar_ResponsableGastos").on("hidden.bs.modal", function(e) {
    $("#invalid-responsable").text("El responsable debe tener un nombre.");
    $("#invalid-responsable").css("display", "none");
    $("#cmbEmpleado").removeClass("is-invalid");
    $("#cmbEmpleado").val("");
  });

  $("#modalEditar").on("hidden.bs.modal", function(e) {
    $("#invalid-responsableEdit").text("El responsable debe tener un nombre.");
    $("#invalid-responsableEdit").css("display", "none");
    $("#txtResponsableU").removeClass("is-invalid");
  });
  </script>

  <script>
  var ruta = "../../";
  </script>
  <script src="js/responsable_gastos.js"></script>
  <script src="../../../js/sb-admin-2.min.js"></script>
  <!-- <script src="../../../js/scripts.js"></script> -->

</body>

</html>