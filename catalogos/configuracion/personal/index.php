<?php

session_start();
require_once '../../../include/db-conn.php';
$user = $_SESSION["Usuario"];

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

    <title>Timlid | Personal</title>

    <!-- Bootstrap core JavaScript-->
    <script src="../../../vendor/jquery/jquery.min.js"></script>
    <script src="../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../../js/validaciones.js"></script>

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

    <!-- Page level custom scripts
    <script src="js/demo/datatables-demo.js"></script>
    -->

    <!-- Custom fonts for this template -->
    <link href="../../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
      href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
      rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../../../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../../../css/styles.css" rel="stylesheet">
    <link href="../../../css/stylesTable.css" rel="stylesheet">
    <link href="../../../css/lobibox.min.css" rel="stylesheet">
    <script src="../../../js/lobibox.min.js"></script>

    <!-- Custom styles for this page -->
    <link href="../../../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="../../../vendor/datatables/responsive.dataTables.css" rel="stylesheet">
    <link rel="stylesheet" href="../../../vendor/datatables/buttons.dataTables.css">
    <link href="../../../vendor/datatables/buttons.bootstrap4.min.css" rel="stylesheet">
    <link href="../../../css/slimselect.min.css" rel="stylesheet">

    <style>
    .nav-link {
      padding: .5rem;
    }
    </style>

  </head>

  <body id="page-top" class="sidebar-toggled">

    <!-- Page Wrapper -->
    <div id="wrapper">

      <!-- Sidebar -->
      <?php
        $icono = '../../../img/inventarios/ICONO INVENTARIOS Y PROD_Mesa de trabajo 1.svg';
        $titulo = '<div class="header-screen d-flex align-items-center">
                                <div class="header-title-screen">
                                  <h1 class="h3">Configuraciones </h1>
                                </div>
                              </div>';
        $ruta = "../../";
        $ruteEdit = $ruta . "central_notificaciones/";
        require_once '../../menu3.php';
      ?>
      <!-- End of Sidebar -->

      <!-- Content Wrapper -->
      <div id="content-wrapper" class="d-flex flex-column">
        <input type="hidden" id="txtUsuario" value="<?=$_SESSION['PKUsuario'];?>">
        <input type="hidden" id="txtRuta" value="<?=$ruta;?>">
        <input type="hidden" id="txtEdit" value="<?=$ruteEdit;?>">
        <input type="hidden" id="txtPantalla" value="<?=$screen;?>">
        <!-- Main Content -->
        <div id="content">

          <?php
          $rutatb = "../../";
          require_once '../../topbar.php';
          ?>

          <!-- Begin Page Content -->
          <div class="container-fluid">

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
                    <a id="CargarSucursales" class="nav-link active" href="">
                      Sucursales
                    </a>
                  </li>
                  <li class="nav-item">
                    <a id="CargarCategoriasProductos" class="nav-link" href="../categoria_productos">
                      Categoría de productos
                    </a>
                  </li>
                  <li class="nav-item">
                    <a id="CargarMarcas" class="nav-link" href="../marca_productos">
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
                      <a href="#" class="btn btn-circle float-right waves-effect waves-light shadow btn-table"
                        id="btn-proyectos" data-toggle="modal" data-target="#agregar_Personal">
                        <i class="fas fa-plus"></i>
                      </a>
                    </div>
                    <div class="button-text-container">
                      <span>Agregar personal</span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table" id="tblPersonal" width=" 100%" cellspacing="0">
                    <thead>
                      <tr>
                        <th>id</th>
                        <th>Nombre</th>
                        <th>Género</th>
                        <th>Código postal</th>
                        <th>Estado</th>
                        <th>Roles</th>
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
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
    <!--ADD MODAL PERSONAL-->
    <div class="modal fade right" id="agregar_Personal" tabindex="-1" role="dialog" aria-labelledby="modalAgregarPersonal"
      aria-hidden="true">
      <div class="modal-dialog modal-full-height modal-right modal-md" role="document">
        <div class="modal-content">
          <form id="agregarPersonal" method="POST">
            <div class="modal-header">
              <h4 class="modal-title w-100">Agregar personal</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">x</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label for="txtNombre">Nombre(s):*</label>
                <input type="text" class="form-control alpha-only" maxlength="50" name="txtNombre" id="txtNombre" required
                onkeyup="validEmptyInput(this)">
                <div class="invalid-feedback" id="invalid-nombre">El empleado debe tener un nombre.</div>
              </div>
              <div class="form-group">
                <label for="txtPrimerApellido">Primer apellido:*</label>
                <input type="text" class="form-control alpha-only" name="txtPrimerApellido" id="txtPrimerApellido" maxlength="50"
                  onkeyup="validEmptyInput(this)" required>
                <div class="invalid-feedback" id="invalid-primerApellido">El empleado debe tener un primer apellido.</div>
              </div>
              <div class="form-group">
                <label for="cmbGenero">Genero:</label>
                <select name="cmbGenero" id="cmbGenero">
                  <option data-placeholder="true"></option>
                  <option value="Masculino">Masculino</option>
                  <option value="Femenino">Femenino</option>
                </select>
                <div class="invalid-feedback" id="invalid-genero">El empleado debe tener un género.</div>
              </div>
              <div class="form-group">
                <label for="cmbEstado">Estado:*</label>
                <select name="cmbEstado" id="cmbEstado" onchange="validEmptyInput(this)" required>
                  <option data-placeholder="true"></option>
                  <?php
                    $stmt = $conn->prepare("SELECT * FROM estados_federativos");
                    $stmt->execute();
                    $row = $stmt->fetchAll();
                    if (count($row) > 0) {
                        foreach ($row as $r) { //Mostrar estados
                            echo '<option value="' . $r['PKEstado'] . '" >' . $r['Estado'] . '</option>';
                        }
                    } else {
                        echo '<option value="" disabled>No hay registros para mostrar.</option>';
                    }
                  ?>
                </select>
                <div class="invalid-feedback" id="invalid-estado">El empleado debe tener un estado.</div>
              </div>
              <div class="form-group">
                <label for="cmbRoles">Roles:*</label>
                <select name="cmbRoles" id="cmbRoles" onchange="validEmptyInput(this)" multiple required>
                  <option data-placeholder="true"></option>
                  <?php
                    $stmt = $conn->prepare("SELECT * FROM tipo_empleado");
                    $stmt->execute();
                    $row = $stmt->fetchAll();
                    foreach ($row as $r) { //Mostrar roles
                        echo '<option value="' . $r['id'] . '" >' . $r['tipo'] . '</option>';
                    }
                  ?>
                </select>
                <div class="invalid-feedback" id="invalid-roles">El empleado debe tener al menos un rol.</div>
              </div>
            </div>
            <div class="modal-footer justify-content-center">
              <button type="button" class="btnesp first espCancelar btnCancelarActualizacion" data-dismiss="modal"
                id="btnCancelarActualizacion"><span class="ajusteProyecto">Cancelar</span></button>
              <button type="button" class="btnesp espAgregar float-right" name="btnAgregarPersonal" id="btnAgregarPersonal"><span
                  class="ajusteProyecto">Agregar</span></button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- END ADD MODAL PERSONAL -->
    <!--UPDATE MODAL PERSONAL-->
    <div class="modal fade right" id="editar_Personal" tabindex="-1" role="dialog" aria-labelledby="modalEditarPersonal"
      aria-hidden="true">
      <div class="modal-dialog modal-full-height modal-right modal-md" role="document">
        <div class="modal-content">
          <form action="" id="editarPersonalU" method="POST">
            <input type="hidden" name="idPersonalU" id="idPersonalU">
            <div class="modal-header">
              <h4 class="modal-title w-100" id="myModalLabel">Editar personal</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">x</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label for="txtNombreU">Nombre(s):*</label>
                <input type="text" class="form-control alpha-only" maxlength="50" name="txtNombreU" id="txtNombreU" required
                onkeyup="validEmptyInput(this)">
                <div class="invalid-feedback" id="invalid-nombreU">El empleado debe tener un nombre.</div>
              </div>
              <div class="form-group">
                <label for="txtPrimerApellidoU">Primer apellido:*</label>
                <input type="text" class="form-control alpha-only" name="txtPrimerApellidoU" id="txtPrimerApellidoU" maxlength="50"
                  onkeyup="validEmptyInput(this)" required>
                <div class="invalid-feedback" id="invalid-primerApellidoU">El empleado debe tener un primer apellido.</div>
              </div>
              <div class="form-group">
                <label for="cmbGeneroU">Genero:*</label>
                <select name="cmbGeneroU" id="cmbGeneroU">
                  <option data-placeholder="true"></option>
                  <option value="Masculino">Masculino</option>
                  <option value="Femenino">Femenino</option>
                </select>
                <div class="invalid-feedback" id="invalid-generoU">El empleado debe tener un género.</div>
              </div>
              <div class="form-group">
                <label for="txtRolInicialU">Rol inicial:*</label>
                <input type="text" class="form-control alpha-only" size="40" name="txtRolInicialU" id="txtRolInicialU"
                  onkeyup="validEmptyInput(this)" required>
                <div class="invalid-feedback" id="invalid-rolInicialU">El empleado debe tener un rol inicial.</div>
              </div>
              <div class="form-group">
                <label for="cmbEstadoU">Estado:*</label>
                <select name="cmbEstadoU" id="cmbEstadoU" onchange="validEmptyInput(this)" required>
                  <option disabled selected>Seleccionar estado</option>
                  <?php
                    $stmt = $conn->prepare("SELECT * FROM estados_federativos");
                    $stmt->execute();
                    $row = $stmt->fetchAll();
                    if (count($row) > 0) {
                        foreach ($row as $r) { //Mostrar estados
                            echo '<option value="' . $r['PKEstado'] . '" >' . $r['Estado'] . '</option>';
                        }
                    } else {
                        echo '<option value="" disabled>No hay registros para mostrar.</option>';
                    }
                  ?>
                </select>
                <div class="invalid-feedback" id="invalid-estadoU">El empleado debe tener un estado.</div>
              </div>
              <div class="form-group">
                <label for="cmbRolesU">Roles:*</label>
                <select name="cmbRolesU" id="cmbRolesU" onchange="validEmptyInput(this)" multiple required>
                  <option data-placeholder="true"></option>
                  <?php
                    $stmt = $conn->prepare("SELECT * FROM tipo_empleado");
                    $stmt->execute();
                    $row = $stmt->fetchAll();
                    foreach ($row as $r) { //Mostrar roles
                        echo '<option value="' . $r['id'] . '" >' . $r['tipo'] . '</option>';
                    }
                  ?>
                </select>
                <div class="invalid-feedback" id="invalid-rolesU">El empleado debe tener al menos un rol.</div>
              </div>
              <div class="modal-footer justify-content-center">
                <a class="btnesp first espEliminar" href="#" onclick="eliminarLocacion(this.value);" name="idPersonalD"
                  id="idPersonalD" data-toggle="modal" data-target=""><span class="ajusteProyecto">Eliminar
                    sucursal</span></a>
                <button type="button" class="btnesp first espCancelar btnCancelarActualizacion" data-dismiss="modal"
                  0id="btnCancelarActualizacion"><span class="ajusteProyecto">Cancelar</span></button>
                <button type="button" class="btnesp espAgregar float-right" name="btnEditarPersonal" id="btnEditarPersonal"
                  value=""><span class="ajusteProyecto">Guardar</span></button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!--END MODAL UPDATE PERSONAL-->
    <script src="js/personal.js"></script>
    <script src="../../../js/sb-admin-2.min.js"></script>
    <script src="../../../js/scripts.js"></script>

  </body>

</html>