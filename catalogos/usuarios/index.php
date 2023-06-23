<?php
  session_start();
  if (isset($_SESSION["Usuario"])) {  
    require_once '../../include/db-conn.php';
    $user = $_SESSION["Usuario"];
    $pkususario = $_SESSION["PKUsuario"];
    $ruta = "../";
    $screen = 2;
    require_once $ruta . 'validarPermisoPantalla.php';
    if($permiso === 0){
      header("location:../dashboard.php");
    }
  }else {
    header("location:../dashboard.php");
  }
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <link rel="icon" type="image/png" href="../../img/header/bluTimlid.png">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Timlid | Usuarios</title>

  <!-- Bootstrap core JavaScript-->
  <script src="../../vendor/jquery/jquery.min.js"></script>
  <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="../../js/sb-admin-2.min.js"></script>

  <!-- Page level plugins -->
  <script src="../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../vendor/datatables/dataTables.bootstrap4.min.js"></script>
  <script src="../../vendor/datatables/dataTables.buttons.js"></script>
  <script src="../../vendor/datatables/buttons.bootstrap4.min.js"></script>
  <script src="../../vendor/datatables/buttons.html5.min.js"></script>
  <script src="../../vendor/jszip/jszip.min.js"></script>

  <!-- Custom fonts for this template -->
  <link href="../../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
  <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

  <!-- Custom styles for this template -->
  <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../vendor/datatables/buttons.dataTables.css" rel="stylesheet">
  <link href="../../vendor/datatables/buttons.bootstrap4.min.css" rel="stylesheet">
  <link href="../../css/styles.css" rel="stylesheet">
  <link href="../../css/stylesTable.css" rel="stylesheet">
  <link href="../../css/slimselect.min.css" rel="stylesheet">
  <link href="css/agregar_usuario.css">
  
  <link rel="stylesheet" href="css/usuarios.css">

  <link rel="stylesheet" href="../../css/sweetalert2.css">
  <link href="../../css/lobibox.min.css" rel="stylesheet">
  
  <link rel="stylesheet" href="../../css/notificaciones.css">
  <script src="../../js/notificaciones_timlid.js" charset="utf-8"></script>

</head>

<body id="page-top" class="sidebar-toggled">

  <!-- Page Wrapper -->
  <div id="wrapper">
    <!-- Sidebar -->
    <?php
      $icono = '../../img/toolbar/usuarios.svg';
      $titulo = '<div class="header-screen d-flex align-items-center">
                        <div class="header-title-screen">
                          <h1 class="h3">Usuarios </h1>
                        </div>
                      </div>';
      
      $ruteEdit = $ruta . "central_notificaciones/";
      require_once $ruta . 'menu3.php';
      
      
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
        $rutatb = "../";
        require_once '../topbar.php';
      ?>

        <!-- Begin Page Content -->
        <div class="container-fluid">
          
            <!-- Page Heading -->
            <div class="row">
              <div class="col-lg-12">
              <ul class="nav nav-tabs">
                <li class="nav-item">
                  <a id="CargarUsuarios" class="nav-link" href="usuarios">
                    Usuarios
                  </a>
                </li>
                <li class="nav-item">
                  <a id="CargarEdicionDatosImpuestos" class="nav-link" href="../puestos">
                    Puestos
                  </a>
                </li>
                <li class="nav-item">
                  <a id="CargarEdicionDatosProveedor" class="nav-link"
                    onclick="cargarTurnos()">
                    Turnos
                  </a>
                </li>
                <li class="nav-item">
                  <a id="CargarEdicionDatosInventario" class="nav-link"
                    onclick="cargarSucursales()">
                    Sucursales
                  </a>
                </li>
                <li class="nav-item">
                  <a id="CargarEdicionTiposProducto" class="nav-link"
                    onclick="cargarEstatusEmpleado()">
                    Estatus empleado
                  </a>
                </li>
                <li class="nav-item">
                  <a id="CargarEdicionTiposProducto" class="nav-link"
                    onclick="cargarCategoria($('#txtPKProducto').val())">
                    Categoría
                  </a>
                </li>
                <li class="nav-item">
                  <a id="CargarEdicionTiposProducto" class="nav-link"
                    onclick="cargarMarcas($('#txtPKProducto').val())">
                    Marcas
                  </a>
                </li>
              </ul>

              </div>
            </div>
            <!-- Basic Card Example --> 

          <!-- DataTales Example -->
          <div class="card mb-4">
            <div class="card-header py-3">
              <div class="float-right permission-view-add">
                <div class="button-container2">
                  <div class="button-icon-container">
                    <a href="" class="btn btn-circle float-right waves-effect waves-light shadow btn-table btn-Agregar"
                      id="btn-proyectos" data-toggle="modal" data-target="#agregarUsuario"><i
                        class="fas fa-plus"></i></a>
                  </div>
                  <div class="button-text-container">
                    <span>Agregar usuarios</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table permission-view-table" id="tblUsuarios" width="100%" cellspacing="0">
                  <thead>
                    <tr>

                      <th>Id usuario</th>
                      <th>Usuario</th>
                      <th>Nombre completo</th>

                    </tr>
                  </thead>
                </table>
              </div>
            </div>
          </div>


        </div>
        <!-- End Page Content -->

      </div>
      <!-- End Main Content -->

      <!-- Footer -->
      <?php
$rutaf = "../";
require_once '../footer.php';
?>
      <!-- End of Footer -->

    </div>
    <!-- End Content Wrapper -->

  </div>
  <!-- End Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Add Fluid Modal User -->
  <div class="modal fade right" id="agregarUsuario" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
    <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">
      <div class="modal-content">
        <form action="#" method="POST" id="agregarUsuario">
          <div class="modal-header">
            <h4 class="modal-title w-100" id="myModalLabel">Agregar Usuario</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">

            <div class="form-group">
              <label for="usr">Correo:</label>
              <input type="email" class="form-control" name="txtUser" id="txtUser" required>
            </div>
            <div class="form-group form-check">
              <input type="checkbox" class="form-check-input" id="chkEmpleado" name="chkEmpleado">
              <label class="form-check-label" for="chkEmpleado">Empleado:</label>
            </div>
            <div class="form-group" id="secEmpleado" style="display:none">
              <select name="cmbEmpleado" id="cmbEmpleado"></select>
            </div>
            <div class="form-group" id="secNameEmpleado">
              <label for="usr">Nombre completo:</label>
              <input type="text" class="form-control" name="txtUser" id="txtNameUser" required>
            </div>

            <div class="form-group pass-user">
              <label for="usr">Contraseña:</label>
              
              <input type="password" class="form-control newPass" id="newPassword" name="txtContrasena" maxlength="10"
                title="La contraseña debe tener al menos una letra mayuscula,  un caracter especia y 10 caracteres."
                required>
              
              <!--<input class="image_pass" type="image" src="../../img/visto.svg">-->
            </div>

            <div class="form-group">
              <label for="usr">Repetir Contraseña:</label>
              <input type="password" class="form-control" id="newPasswordAgain" maxlength="10" required>
            </div>



          </div>
          <div class="modal-footer justify-content-center">
            <button type="button" class="btnesp first espCancelar btnCancelarActualizacion" data-dismiss="modal"
              id="btnCancelarActualizacion"><span class="ajusteProyecto">Cancelar</span></button>
            <button type="button" class="btnesp espAgregar float-right" name="btnAgregar" id="btnAgregar"><span
                class="ajusteProyecto">Agregar</span></button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- End Add Fluid Modal User -->

  <!-- Edit Fluid Modal User -->
  <div class="modal fade right" id="editarUsuario" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">

    <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
    <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">
      <div class="modal-content">
        <form action="#" method="POST" id="agregarUsuario">
          <div class="modal-header">
            <h4 class="modal-title w-100" id="myModalLabel">Editar Usuario</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <input type="hidden" name="" id="txtUpdatePKUser">
            <div class="form-group">
              <label for="usr">Correo:</label>
              <input type="email" class="form-control" name="txtUser" id="txtUpdateUser" required>
            </div>
            <div class="form-group">
              <label for="usr">Nombre completo:</label>
              <input type="text" class="form-control" name="txtUser" id="txtUpdateNameUser" readonly required>
            </div>

            <div class="form-group">
              <label for="usr">Contraseña:</label>
              <input type="password" class="form-control" id="newUpdatePassword" name="txtContrasena" maxlength="10"
                pattern="(?=.*\d)(?=.*[A-Z])(?=.*[~`!@#$%^&*()\-_+={};:\[\]\?\.\\/,]).{10,}"
                title="La contraseña debe tener al menos una letra mayuscula,  un caracter especia y 10 caracteres."
                required>
            </div>

            <div class="form-group">
              <label for="usr">Repetir Contraseña:</label>
              <input type="password" class="form-control" id="newUpdatePasswordAgain" maxlength="10" required>
            </div>

          </div>
          <div class="modal-footer justify-content-center">
            <button type="button" class="btnesp first espCancelar btnCancelarActualizacion" data-dismiss="modal"
              id="btnCancelarActualizacion"><span class="ajusteProyecto">Cancelar</span></button>
            <button type="button" class="btnesp espAgregar float-right" data-dismiss="modal"
              id="btnPermisosActualizacion"><span class="ajusteProyecto">Configurar permisos</span></button>
              <button type="button" class="btnesp espAgregar float-right permission-view-delete" data-dismiss="modal"
              id="btnEliminar"><span class="ajusteProyecto">Eliminar</span></button>
            <button type="button" class="btnesp espAgregar float-right" name="btnAgregar" id="btnEditar"><span
                class="ajusteProyecto">Guardar</span></button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- End Edit Fluid Modal User -->

  <!--DELETE MODAL SLIDE MARCAS-->
  <div class="modal fade" id="eliminarUsuario" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form action="" method="POST">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">¿Desea eliminar este usuario?</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">x</span>
            </button>
          </div>
          <div>
            <input type="hidden" name="txtPKUsuarioD" id="txtPKUsuarioD">
            <br>
            <label for="usr" style="margin-left: 80px!important;">Se eliminará el siguiente usuario:</label>
          </div>

            <div class="form-group col-md-6">
              <label for="usr">Usuario:</label>
            </div>
            <div class="form-group col-md-12">
              <input type="text" style="border:none!important;"
                class="form-control" maxlength="50" id="txtUsuarioD" name="txtUsuarioD" required
                readonly>
            </div>

          <div class="modal-body" style="font-size: 10 px!important; color: red;">Esta acción no podrá deshacerse</div>
          <div class="modal-footer">
            <button class="btnesp first espCancelar btnCancelarActualizacion" type="button" data-dismiss="modal"><span
                class="ajusteProyecto">Cancelar</span></button>
            <button type="submit" class="btnesp espAgregar float-right" id="btnEliminarUsuario"><span
                class="ajusteProyecto" data-dismiss="modal">Eliminar</span></button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
  loadAlertsNoti('alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
  setInterval(loadAlertsNoti, 10000, 'alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
  </script>
  <script src="js/usuarios.js"></script>
  <script src="../../js/sb-admin-2.min.js"></script>
  <script src="../../js/scripts.js"></script>
  <script src="../../js/slimselect.min.js"></script>
  <script src="../../js/sweet/sweetalert2.js"></script>
  <script src="../../js/lobibox.min.js"></script>

</body>

</html>