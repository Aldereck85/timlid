<?php
session_start();
if (isset($_SESSION["Usuario"])) {
  require_once '../../include/db-conn.php';
  $user = $_SESSION["Usuario"];
  $pkusuario = $_SESSION["PKUsuario"];
  $idemp = $_SESSION["IDEmpresa"];
  $ruta = "../";
  $screen = 43;
  //require_once $ruta . 'validarPermisoPantalla.php';
  /*if($permiso === 0){
        header("location:../dashboard.php");
    }*/
} else {
  header("location:../dashboard.php");
}
$jwt_ruta = "../../";
  require_once '../jwt.php';
$token = $_SESSION['token_ld10d'];

?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="icon" type="image/png" href="../../img/header/bluTimlid.png">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Timlid | Configuraciones</title>

  <!-- ESTILOS -->
  <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="../../vendor/datatables/buttons.dataTables.css" rel="stylesheet">
  <link href="../../css/slimselect.min.css" rel="stylesheet">
  <link href="../../css/styles.css" rel="stylesheet">
  <link href="../../css/stylesNewTable.css" rel="stylesheet">
  <!--  <link href="css/agregar_usuario.css" rel="stylesheet"> -->
  <link href="../../css/mdtimepicker.min.css" rel="stylesheet">
  <link href="../../css/timepicki.css" rel="stylesheet">
  <!-- <link href="css/usuarios.css" rel="stylesheet"> -->
  <link href="../../css/sweetalert2.css" rel="stylesheet">
  <link href="../../css/lobibox.min.css" rel="stylesheet">
  <link href="../../css/notificaciones.css" rel="stylesheet">
  <!-- <link href="css/logo_empresa.css" rel="stylesheet"> -->
  <link rel="stylesheet" href="css/empresas.css">
  <!-- JS -->
  <script src="../../vendor/jquery/jquery.min.js"></script>
  <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../../js/sb-admin-2.min.js"></script>
  <script src="../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../vendor/datatables/dataTables.buttons.js"></script>
  <script src="../../vendor/datatables/buttons.html5.min.js"></script>
  <script src="../../vendor/jszip/jszip.min.js"></script>
  <script src="../../js/notificaciones_timlid.js" charset="utf-8"></script>
  <script src="../../js/mdtimepicker.min.js"></script>
</head>

<body id="page-top" class="sidebar-toggled">

  <!-- Page Wrapper -->
  <div id="wrapper">
    <!-- Sidebar -->
    <?php
    $icono = '../../img/icons/CONFIGURACION.svg';
    $titulo = 'Configuraciones';

    $ruteEdit = $ruta . "central_notificaciones/";
    require_once $ruta . 'menu3.php';
    ?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
      <input type="hidden" id="txtUsuario" value="<?= $_SESSION['PKUsuario']; ?>">
      <input type="hidden" id="txtRuta" value="<?= $ruta; ?>">
      <input type="hidden" id="txtEdit" value="<?= $ruteEdit; ?>">
      <input type="hidden" id="txtPantalla" value="<?= $screen; ?>">
      <input type="hidden" id="emp_id" value="<?= $idemp; ?>">
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
            <div class="col-lg-12 config-tabs">
            </div>
          </div>
          <!-- Basic Card Example -->

          <!-- DataTales Example -->

          <div class="card mb-4 internal-table">
            <div class="card-body">
              <div class="permission-view-table">
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

  <!------------------------------------------------ MODALS USER --------------------------------------------------------------->

  <!-- Add Fluid Modal User -->
  <div class="modal fade right" id="agregar_Usuarios_43" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
    <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">
      <div class="modal-content">
        <form action="#" method="POST" id="agregarUsuario">
          <div class="modal-header">
            <h4 class="modal-title w-100" id="myModalLabel">Agregar Usuario</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">x</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label for="txtUser">Correo:*</label>
              <input type="email" class="form-control" name="txtUser" id="txtUser" onkeyup="validarCorreo(this)" required>
              <div class="invalid-feedback" id="invalid-correoUs">El usuario debe tener un correo.</div>
            </div>
            <div class="form-group form-check">
              <input type="checkbox" class="form-check-input" id="chkEmpleado" name="chkEmpleado">
              <label class="form-check-label" for="chkEmpleado">Empleado:</label>
            </div>
            <div class="form-group" id="secEmpleado" style="display:none">
              <select name="cmbEmpleadoUsuario" id="cmbEmpleadoUsuario" onchange="validEmptyInput(this)"></select>
              <div class="invalid-feedback" id="invalid-empleadoUs">El usuario debe tener un nombre de empleado.</div>
            </div>
            <div id="secNameEmpleado">
              <div class="form-group">
                <label for="txtNameUser">Nombres:*</label>
                <input type="text" class="form-control" name="txtNameUser" id="txtNameUser" onkeyup="validEmptyInput(this)" required>
                <div class="invalid-feedback" id="invalid-nombreUs">El usuario debe tener un nombre.</div>
              </div>
              <div class="form-group">
                <label for="txtPrimerApp">Primer Apellido:*</label>
                <input type="text" class="form-control" name="txtPrimerApp" id="txtPrimerApp" onkeyup="validEmptyInput(this)" required>
                <div class="invalid-feedback" id="invalid-PrimerAppUs">El usuario debe tener un apellido.</div>
              </div>
              <div class="form-group">
                <label for="txtSegundoApp">Segundo Apellido:</label>
                <input type="text" class="form-control" name="txtSegundoApp" id="txtSegundoApp">
              </div>
            </div>
            <!-- <br> -->
            <!-- <div class="form-group">
              <label for="usr" data-toggle="tooltip" data-placement="top" title="Define el tipo de usuario y el acceso al sistema">Rol preterminado:</label>
              <select name="cmbRoles" id="cmbRoles" data-toggle="tooltip" data-placement="top" title="Define el tipo de usuario y el acceso al sistema" onchange="validEmptyInput(this)">
              </select>
              <div class="invalid-feedback" id="invalid-rolUs">El usuario debe tener un rol.</div>
            </div> -->
            <!-- <br>
            <div class="form-group form-check">
              <input type="checkbox" class="form-check-input" id="chkPerfil" name="chkPerfil">
              <label class="form-check-label" for="usr">Agregar Perfíl:</label>
              <div class="invalid-feedback" id="invalid-passUs" style="display: block; color: #000;">Agrega perfiles de usuario con permisos especiales.</div>
            </div> -->
            <div class="form-group" id="secPerfiles">
              <label class="form-check-label" for="usr">Agregar Perfil:</label>
              <select name="cmbPerfiles" id="cmbPerfiles"></select>
            </div>
            <div class="form-group form-check">
              <input type="checkbox" class="form-check-input" id="chkPerfilPerzonalizado" name="chkPerfilPerzonalizado">
              <label class="form-check-label" for="usr">Personalizar Perfil:</label>
              <div class="invalid-feedback" id="invalid-passUs" style="display: block; color: #000;">Perzonaliza un perfil de usuario con permisos especiales.</div>
            </div>
          </div>
          <div class="form-group form-check">
            <p class="text-danger" id="id-maxUsers"></p>
          </div>
          <div class="modal-footer justify-content-center">
            <button type="button" class="btnesp first espCancelar btnCancelarActualizacion_Usuarios_43" data-dismiss="modal" id="btnCancelarAgregar"><span class="ajusteProyecto">Cancelar</span></button>
            <button type="button" class="btnesp espAgregar float-right" name="btnAgregar" id="btnAgregar"><span class="ajusteProyecto">Agregar</span></button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- End Add Fluid Modal User -->

  <!-- EDIT Fluid Modal User -->
  <div class="modal fade right" id="editar_Usuarios_43" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

    <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
    <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">
      <div class="modal-content">
        <form action="#" method="POST" id="editarUsuario">
          <div class="modal-header">
            <h4 class="modal-title w-100" id="myModalLabel">Editar Usuario</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">x</span>
            </button>
          </div>
          <div class="modal-body">
            <input type="hidden" id="">
            <input type="hidden" name="" id="txtUpdatePKUsuarios_43">
            <div class="form-group">
              <label for="usr">Correo:</label>
              <input type="email" class="form-control" name="txtUser" id="txtUpdateUser" onkeyup="validarCorreo(this)" required>
              <div class="invalid-feedback" id="invalid-correoEd">El usuario debe tener un email valido.</div>
            </div>
            <div class="form-group">
              <label for="usr">Nombre completo:</label>
              <input type="text" class="form-control" name="txtUser" id="txtUpdateUsuarios_43" readonly required>
            </div>
            <!-- <div class="form-group form-check">
              <input type="checkbox" class="form-check-input" id="chkUpdateEmpleado" name="chkEmpleado">
              <label class="form-check-label" for="chkEmpleado">Empleado:</label>
            </div> -->
            <div class="form-group" id="secUpdateEmpleado" style="display:none">
              <select name="cmbEmpleadoUsuario" id="cmbUpdateEmpleadoUsuario" onchange="validEmptyInput(this)"></select>
              <div class="invalid-feedback" id="invalid-empleadoUs">El usuario debe tener un nombre de empleado.</div>
            </div>

            <!-- <br> -->
            <!-- <div class="form-group">
              <label for="usr">Rol:</label>
              <select name="cmbRoles" id="cmbUpdateRoles">

              </select>
              <div class="invalid-feedback" id="invalid-rolUs">El usuario debe tener un rol.</div>
            </div> -->
            <!-- <br> -->
            <!-- <div class="form-group form-check">
              <input type="checkbox" class="form-check-input" id="chkUpdatePerfil" name="chkPerfil">
              <label class="form-check-label" for="usr">Agregar Perfíl:</label>
              <div class="invalid-feedback" id="invalid-passUs" style="display: block; color: #000;">Agrega perfiles de usuario con permisos especiales.</div>
            </div> -->
            <div class="form-group" id="secUpdatePerfiles">
              <select name="cmbPerfiles" id="cmbUpdatePerfiles"></select>
            </div>
          </div>
          <div class="modal-footer justify-content-center">
            <button type="button" class="btnesp first espCancelar btnCancelarActualizacion" data-dismiss="modal" id="btnCancelarEliminacion"><span class="ajusteProyecto">Cancelar</span></button>
            <div class="permission-view-delete-Usuarios_43"></div>
            <div class="permission-view-edit-Usuarios_43"></div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- End Edit Fluid Modal User -->

  <!--DELETE MODAL SLIDE USUARIO-->
  <div class="modal fade" id="eliminar_Usuarios_43" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
            <input type="hidden" name="txtPKUsuarios_43D" id="txtPKUsuarios_43D">
            <br>
            <label for="usr" style="margin-left: 80px!important;">Se eliminará el siguiente usuario:</label>
          </div>

          <div class="form-group col-md-6">
            <label for="usr">Usuario:</label>
          </div>
          <div class="form-group col-md-12">
            <input type="text" style="border:none!important;" class="form-control" maxlength="50" id="txtUsuarios_43D" name="txtUsuarios_43D" required readonly>
          </div>

          <div class="modal-body" style="font-size: 10 px!important; color: red;">Esta acción no podrá deshacerse</div>
          <div class="modal-footer">
            <button class="btnesp first espCancelar btnCancelarActualizacion" type="button" data-dismiss="modal"><span class="ajusteProyecto">Cancelar</span></button>
            <button type="submit" class="btnesp espAgregar float-right" id="btn_aceptar_eliminar_Usuarios_43"><span class="ajusteProyecto" data-dismiss="modal">Eliminar</span></button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!------------------------------------------------ END MODALS USER --------------------------------------------------------------->

  <!------------------------------------------------ MODALS MARCAS PRODUCTOS ------------------------------------------------------>

  <div class="modal fade right" id="agregar_MarcadeProductos_9" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
    <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">
      <div class="modal-content">
        <form action="" method="POST">
          <input type="hidden" name="idInput" value="">
          <div class="modal-header">
            <h4 class="modal-title w-100" id="myModalLabel">Agregar Marca</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">x</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-lg-12">
                <form id="formDatosMarca">
                  <div class="form-group">
                    <div class="row">
                      <div class="col-lg-12">
                        <label for="usr">Nombre de la marca</label>
                        <input class="form-control alphaNumeric-only" type="text" name="txtMarca" id="txtMarca"
                          autofocus="" required="" placeholder="Ej. GH Medic" onkeyup="validarMarca()">
                        <div class="invalid-feedback" id="invalid-nombreMarca">La marca debe tener un nombre.</div>
                      </div>
                    </div>
                  </div>
                  <br>
                </form>
              </div>
            </div>
          </div>
          <div class="modal-footer justify-content-end">
            <button type="button" class="btn-custom btn-custom--border-blue btnCancelarActualizacion" data-dismiss="modal"
              id="btnCancelarActualizacion"><span class="ajusteProyecto">Cancelar</span></button>
            <button type="submit" class="btn-custom btn-custom--blue" name="btnAgregarMarca" id="btnAgregarMarca"><span
                class="ajusteProyecto" onclick="anadirMarca()">Agregar</span></button>
          </div>
        </form>
      </div>
    </div>
  </div>
<!--END ADD MODAL SLIDE MARCAS-->
<!--EDIT MODAL SLIDE MARCAS-->
<div class="modal fade right" id="editar_MarcadeProductos_9" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
  aria-hidden="true">
  <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
  <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">
    <div class="modal-content">
      <form>
        <input type='hidden' name="txtPKMarca" id="txtPKMarca">
        <input type='hidden' name="txtMarActual" id="txtMarActual">
        <div class="modal-header">
          <h4 class="modal-title w-100" id="myModalLabel">Editar Marca</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">x</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-lg-12">
              <form id="formDatosCategoriaU">
                <div class="form-group">
                  <div class="row">
                    <div class="col-lg-4">

                    </div>
                    <div class="col-lg-3">
                      <label for="usr">Estatus:*</label>
                    </div>
                    <div class="col-lg-5">
                      <input type="checkbox" id="activeMarcProd" name="activeMarcProd" class="check-custom">
                      <label class="shadow-sm check-custom-label" for="activeMarcProd">
                        <div class="circle"></div>
                        <div class="check-inactivo">Inactivo</div>
                        <div class="check-activo">Activo</div>
                      </label>
                    </div>
                  </div>
                </div>
                <input class="form-control" id="notaEstatusU" name="notaEstatusU" type="hidden"
                  style="color: darkred; background-color: transparent!important; border: none;"
                  value="Nota: Marca utilizada por un producto." readonly>
                <div class="form-group">
                  <div class="row">
                    <div class="col-lg-12">
                      <label for="usr">Nombre de la marca</label>
                      <input class="form-control alphaNumeric-only" type="text" name="txtMarcaU" id="txtMarcaU"
                        autofocus="" required="" placeholder="Ej. GH Medic" onkeyup="validarMarcaU()">
                      <div class="invalid-feedback" id="invalid-nombreMarcaEdit">La marca debe tener un nombre.</div>
                    </div>
                  </div>
                </div>
                <br>
              </form>
            </div>
          </div>
        </div>
        <div class="modal-footer justify-content-end">
          <button type="button" class="btn-custom btn-custom--border-blue btnCancelarActualizacion" data-dismiss="modal"
            id="btnCancelarActualizacionU"><span class="ajusteProyecto">Cancelar</span></button>
          <a aria-label="Close" class="btn-custom btn-custom--border-blue btnCancelarActualizacion" data-toggle="modal"
            data-target="#eliminar_MarcadeProductos_9" name="btnEliminarMarcaU" id="btnEliminarMarcaU" data-dismiss="modal"><span
              class="ajusteProyecto">Eliminar</span></a>
          <button type="submit" class="btn-custom btn-custom--blue" name="btnEditarMarca" id="btnEditarMarca"><span
              class="ajusteProyecto">Guardar</span></button>
        </div>
      </form>
    </div>
  </div>
</div>
<!--END EDIT MODAL SLIDE MARCAS-->

<!--DELETE MODAL SLIDE MARCAS-->
<div class="modal fade" id="eliminar_MarcadeProductos_9" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="" method="POST">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">¿Eliminar Marca?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">x</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="txtPKMarcaD" id="txtPKMarcaD">
          <p>Se eliminará la marca con los siguientes datos</p>
          <p>Nombre Marca: <span id="txtMarcaD"></span></p>
          <p class="text-danger">Esta acción no podrá deshacerse</p>
        </div>
        <div class="modal-footer">
          <button class="btn-custom btn-custom--border-blue btnCancelarActualizacion" type="button"
            data-dismiss="modal"><span class="ajusteProyecto" data-toggle="modal" data-target="#editar_Marca"
              onclick="obtenerIdMarcaEditar($('#txtPKMarcaD').val())">Cancelar</span></button>
          <button type="button" onclick="eliminarMarca()" class="btn-custom btn-custom--blue float-right"><span
              class="ajusteProyecto" data-dismiss="modal">Eliminar</span></button>
        </div>
      </form>
    </div>
  </div>
</div>
<!--END DELETE MODAL SLIDE CATEGORÍAS-->


  <!------------------------------------------------ END MODALS MARCAS PRODUCTOS ---------------------------------------------------->

  <!------------------------------------------------ MODALS CATEGORIAS PRODUCTOS ------------------------------------------------------>
  <!--ADD MODAL SLIDE CATEGORÍAS-->
  <div class="modal fade right" id="agregar_CategoriadeProductos_8" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
  <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">
    <div class="modal-content">
      <form action="" method="POST">
        <input type="hidden" name="idInput" value="">
        <div class="modal-header">
          <h4 class="modal-title w-100" id="myModalLabel">Agregar Categoría</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">x</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-lg-12">
              <form id="formDatosCategoria">
                <div class="form-group">
                  <div class="row">
                    <div class="col-lg-12">
                      <label for="usr">Nombre de la categoría</label>
                      <input class="form-control alphaNumeric-only" type="text" name="txtCategoria" id="txtCategoria" autofocus="" required="" placeholder="Ej. Bata" onkeyup="validarCategoria()">
                      <div class="invalid-feedback" id="invalid-nombreCat">La categoria debe tener un nombre.
                      </div>
                    </div>
                  </div>
                </div>
                <br>
              </form>
            </div>
          </div>
        </div>
        <div class="modal-footer justify-content-center">
          <button type="button" class="btn-custom btn-custom--border-blue btnCancelarActualizacion" data-dismiss="modal" id="btnCancelarActualizacion"><span class="ajusteProyecto">Cancelar</span></button>
          <button type="submit" class="btn-custom btn-custom--blue" name="btnAgregarCategoria" id="btnAgregarCategoria" ><span class="ajusteProyecto" onclick="anadirCategoria()">Agregar</span></button>
        </div>
      </form>
    </div>
  </div>
</div>
<!--END ADD MODAL SLIDE CATEGORÍAS-->


<!--EDIT MODAL SLIDE CATEGORÍAS-->
<div class="modal fade right" id="editar_CategoriadeProductos_8" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
  <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">
    <div class="modal-content">
      <form action="" method="POST">
        <input type='hidden' name="txtPKCategoria" id="txtPKCategoria">
        <input type='hidden' name="txtCatActual" id="txtCatActual">
        <div class="modal-header">
          <h4 class="modal-title w-100" id="myModalLabel">Editar Categoría</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">x</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-lg-12">
              <form id="formDatosCategoriaU">
                <div class="form-group">
                  <div class="row">
                    <div class="col-lg-4">

                    </div>
                    <div class="col-lg-3">
                      <label for="usr">Estatus:*</label>
                    </div>
                    <div class="col-lg-5">
                      <input type="checkbox" id="activeMarca" name="activeMarca" class="check-custom">
                      <label class="shadow-sm check-custom-label" for="activeMarca">
                        <div class="circle"></div>
                        <div class="check-inactivo">Inactivo</div>
                        <div class="check-activo">Activo</div>
                      </label>
                    </div>
                  </div>
                </div>
                <input class="form-control" id="notaEstatusU" name="notaEstatusU" type="hidden" style="color: darkred; background-color: transparent!important; border: none;" value="Nota: Categoría utilizada por un producto." readonly>
                <div class="form-group">
                  <div class="row">
                    <div class="col-lg-12">
                      <label for="usr">Nombre de la categoría</label>
                      <input class="form-control alphaNumeric-only" type="text" name="txtCategoriaU" id="txtCategoriaU" autofocus="" required="" placeholder="Ej. Bata" onkeyup="validarCategoriaU()">
                      <div class="invalid-feedback" id="invalid-nombreCatEdit">La categoria debe tener un nombre.
                      </div>
                    </div>
                  </div>
                </div>
                <br>
              </form>
            </div>
          </div>
        </div>

        <div class="modal-footer justify-content-center">
          <button type="button" class="btn-custom btn-custom--border-blue btnCancelarActualizacion" data-dismiss="modal" id="btnCancelarActualizacionU"><span class="ajusteProyecto">Cancelar</span>
          </button>
          <a aria-label="Close" class="btn-custom btn-custom--border-blue btnCancelarActualizacion" data-toggle="modal" data-target="#eliminar_CategoriadeProductos_8" name="btnEliminarU" id="btnEliminarU" data-dismiss="modal">
            <span class="ajusteProyecto">Eliminar</span>
          </a>
          <button type="submit" class="btn-custom btn-custom--blue" name="btnEditarCategoria" id="btnEditarCategoria"><span class="ajusteProyecto">Guardar</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
<!--END EDIT MODAL SLIDE CATEGORÍAS-->

<!--DELETE MODAL SLIDE CATEGORÍAS-->
<div class="modal fade" id="eliminar_CategoriadeProductos_8" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="" method="POST">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">¿Eliminar categoria?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">x</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="txtPKCategoriaD" id="txtPKCategoriaD">
          <p>Se eliminará la categoría con los siguientes datos</p>
          <p>Nombre Categoria: <span id="txtCategoriaD"></span></p>
          <p class="text-danger">Esta acción no podrá deshacerse</p>
        </div>
        <div class="modal-footer">
          <button class="btn-custom btn-custom--border-blue btnCancelarActualizacion" type="button" data-dismiss="modal"><span class="ajusteProyecto" data-toggle="modal" data-target="#editar_Categoria" onclick="obtenerIdCategoriaEditar($('#txtPKCategoriaD').val())">Cancelar</span></button>
          <button type="submit" onclick="eliminarCategoria()" class="btn-custom btn-custom--blue"><span class="ajusteProyecto" data-dismiss="modal">Eliminar</span></button>
        </div>
      </form>
    </div>
  </div>
</div>
<!--END DELETE MODAL SLIDE CATEGORÍAS-->

  <!------------------------------------------------ END MODALS CATEGORIAS PRODUCTOS ------------------------------------------------------>

  <!------------------------------------------------ MODALS PUESTOS --------------------------------------------------------------->

  <!--ADD MODAL SLIDE PUESTOS-->
  <div class="modal fade right" id="agregar_Puestos_45" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
    <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">
      <div class="modal-content">
        <form action="" id="agregarPuesto" method="POST">
          <!--<input type="hidden" name="idProyectoA" value="">-->
          <div class="modal-header">
            <h4 class="modal-title w-100" id="myModalLabel">Agregar puesto</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">x</span>
            </button>
          </div>
          <div class="modal-body">
            <input class="form-control" id="notaPuesto" name="notaPuesto" type="hidden" style="color: darkred; background-color: transparent!important; border: none;" value="Nota: Nombre de la sucursal ya está registrada." readonly>
            <div class="form-group">
              <label for="usr">Puesto:*</label>
              <input type="text" class="form-control alpha-only" maxlength="30" id="txtPuesto" name="txtPuesto" onkeyup="validarUnicoPuesto(this)" required>
              <div class="invalid-feedback" id="invalid-puesto">El nombre del puesto es requerido.</div>
            </div>
          </div>
          <div class="modal-footer justify-content-center">
            <button type="button" class="btnesp first espCancelar btnCancelarActualizacion" data-dismiss="modal" id="btnCancelarActualizacion_Puestos_45"><span class="ajusteProyecto">Cancelar</span></button>
            <button type="button" class="btnesp espAgregar float-right" name="btnAgregar" id="btnAgregarPuestos_45"><span class="ajusteProyecto">Agregar</span></button>
          </div>
        </form>
      </div>
    </div>
  </div>
  </div>
  <!--END ADD MODAL SLIDE PUESTOS-->

  <!--EDIT MODAL SLIDE PUESTOS-->
  <div class="modal fade right" id="editar_Puestos_45" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
    <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">
      <div class="modal-content">
        <form action="" id="editarPuesto" method="POST">
          <input type="hidden" name="txtUpdatePKPuestos_45" id="txtUpdatePKPuestos_45">
          <div class="modal-header">
            <h4 class="modal-title w-100" id="myModalLabel">Editar puesto</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">x</span>
            </button>
          </div>
          <div class="modal-body">

            <div class="form-group">
              <!--
              <input class="form-control" id="notaExisteRelacion" name="notaExisteRelacion" type="hidden"
                style="color: darkred; background-color: transparent!important; border: none;"
                value="Nota: El puesto está siendo utilizado." readonly>
              -->
              <label for="usr">Puesto:</label>
              <input type="text" class="form-control alpha-only" maxlength="30" id="txtUpdatePuestos_45" name="txtUpdatePuestos_45" onkeyup="validarUnicoPuesto(this)" required>
              <div class="invalid-feedback" id="invalid-puestoEdit">El nombre del puesto es requerido.</div>
            </div>
          </div>
          <div class="modal-footer justify-content-center">
            <div class="permission-view-delete-Puestos_45"></div>
            <button type="button" class="btnesp first espCancelar btnCancelarActualizacion" data-dismiss="modal" id="btnCancelarActualizacionU_Puestos_45"><span class="ajusteProyecto">Cancelar</span></button>
            <div class="permission-view-edit-Puestos_45"></div>

          </div>
        </form>
      </div>
    </div>
  </div>
  </div>
  <!--END EDIT MODAL SLIDE PUESTOS-->

  <!--DELETE MODAL SLIDE PUESTO-->
  <div class="modal fade" id="eliminar_Puestos_45" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form action="" method="POST">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">¿Desea eliminar este puesto?</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">x</span>
            </button>
          </div>
          <div>
            <input type="hidden" name="txtPKPuestos_45D" id="txtPKPuestos_45D">
            <br>
            <label for="usr" style="margin-left: 80px!important;">Se eliminará el siguiente puesto:</label>
          </div>

          <div class="form-group col-md-6">
            <label for="usr">Puesto:</label>
          </div>
          <div class="form-group col-md-12">
            <input type="text" style="border:none!important;" class="form-control" maxlength="50" id="txtPuestos_45D" name="txtPuestos_45D" required readonly>
          </div>

          <div class="modal-body" style="font-size: 10 px!important; color: red;">Esta acción no podrá deshacerse</div>
          <div class="modal-footer">
            <button class="btnesp first espCancelar btnCancelarActualizacion" type="button" data-dismiss="modal"><span class="ajusteProyecto">Cancelar</span></button>
            <button type="button" class="btnesp espAgregar float-right" id="btn_aceptar_eliminar_Puestos_45"><span class="ajusteProyecto" data-dismiss="modal">Eliminar</span></button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!--END DELETE MODAL SLIDE PUESTOS-->

  <!------------------------------------------------ END MODALS PUESTOS --------------------------------------------------------------->

  <!------------------------------------------------ MODALS TURNOS --------------------------------------------------------------->

  <!--ADD MODAL SLIDE TURNOS-->
  <div class="modal fade right" id="agregar_Turnos_46" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
    <div class="modal-dialog modal-full-height modal-right  modal-md" role="document">
      <div class="modal-content">
        <form action="" id="agregarTurno" method="POST">
          <!--<input type="hidden" name="idProyectoA" value="">-->
          <div class="modal-header">
            <h4 class="modal-title w-100" id="myModalLabel">Agregar turno</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">x</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label for="usr">Turno:*</label>
              <input type="text" class="form-control alpha-only" maxlength="20" name="txtTurno" id="txtTurno" onkeyup="validarUnicoTurno(this)" required>
              <div class="invalid-feedback" id="invalid-turno">El nombre del turno es requerido.</div>
            </div>
            <div class="form-group">
              <label for="usr">Tipo de jornada:</label>
              <select id="tipo_jornada" name="tipo_jornada" class="form-control">
                <?php
                $stmt = $conn->prepare("SELECT id, tipo_jornada FROM tipo_jornada");
                $stmt->execute();
                $row = $stmt->fetchAll();

                foreach ($row as $r) { //Mostrar usuarios
                  echo '<option value="' . $r['id'] . '">' . $r['tipo_jornada'] . '</option>';
                }

                ?>
              </select>
            </div>
            <div class="form-group">
              <label for="usr">Entrada:*</label>
              <input type="text" class="form-control time-only" name="txtEntrada" id="txtEntrada" onchange="validEmptyInput(this)" required>
              <div class="invalid-feedback" id="invalid-entrada">La hora de entrada es requerida.</div>
            </div>
            <div class="form-group">
              <label for="usr">Salida:*</label>
              <input type="text" class="form-control time-only" name="txtSalida" id="txtSalida" onchange="validEmptyInput(this)" required>
              <div class="invalid-feedback" id="invalid-salida">La hora de salida es requerida.</div>
            </div>

            <div class="form-group weekDays-selector">
              <form id="form" name="cat" method="POST" action="">
                <label for="usr">Días de trabajo:</label><br>
                <input type="checkbox" name="weekday" id="weekday-mon" class="weekday" value="1" onclick="checkbox_Selected();" />
                <label for="weekday-mon">LUN</label>
                <input type="checkbox" name="weekday" id="weekday-tue" class="weekday" value="2" onclick="checkbox_Selected();" />
                <label for="weekday-tue">MAR</label>
                <input type="checkbox" name="weekday" id="weekday-wed" class="weekday" value="3" onclick="checkbox_Selected();" />
                <label for="weekday-wed">MIE</label>
                <input type="checkbox" name="weekday" id="weekday-thu" class="weekday" value="4" onclick="checkbox_Selected();" />
                <label for="weekday-thu">JUE</label>
                <input type="checkbox" name="weekday" id="weekday-fri" class="weekday" value="5" onclick="checkbox_Selected();" />
                <label for="weekday-fri">VIE</label>
                <input type="checkbox" name="weekday" id="weekday-sat" class="weekday" value="6" onclick="checkbox_Selected();" />
                <label for="weekday-sat">SAB</label>
                <input type="checkbox" name="weekday" id="weekday-sun" class="weekday" value="7" onclick="checkbox_Selected();" />
                <label for="weekday-sun">DOM</label>
                <div class="invalid-feedback" id="invalid-dias">El tuno debe tener al menos un día.</div>
              </form>
            </div>
            <div class="form-group">
              <label for="usr">Tiempo de comida:*</label>
              <input type="text" class="form-control time-only time_element" name="txtComida" id="txtComida" oninput="validEmptyInput(this, 'invalid-comida')" value="00:00" required>
              <div class="invalid-feedback" id="invalid-comida">La hora de comida es requerida.</div>
            </div>
          </div>
          <div class="modal-footer justify-content-center">
            <button type="button" class="btnesp first espCancelar btnCancelarActualizacion" data-dismiss="modal" id="btnCancelarActualizacion_9"><span class="ajusteProyecto">Cancelar</span></button>
            <button type="button" class="btnesp espAgregar float-right" name="btnAgregar" id="btnAgregarTurno"><span class="ajusteProyecto">Agregar</span></button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!--END ADD MODAL SLIDE TURNOS-->

  <!--UPDATE MODAL SLIDE TURNOS-->
  <div class="modal fade right" id="editar_Turnos_46" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
    <div class="modal-dialog modal-full-height modal-right modal-md" role="document">
      <div class="modal-content">
        <form action="" id="editarTurno" method="POST">
          <input type="hidden" name="txtUpdatePKTurnos_46" id="txtUpdatePKTurnos_46">
          <div class="modal-header">
            <h4 class="modal-title w-100" id="myModalLabel">Editar turno</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">x</span>
            </button>
          </div>
          <div class="modal-body">

            <!--<input class="form-control" id="notaExisteRelacion" name="notaExisteRelacion" type="hidden"
              style="color: darkred; background-color: transparent!important; border: none;"
              value="Nota: El turno está siendo utilizado." readonly>-->

            <div class="form-group">
              <label for="usr">Turno:</label>
              <input type="text" class="form-control alpha-only" maxlength="20" name="txtUpdateTurnos_46" id="txtUpdateTurnos_46" onkeyup="validarUnicoTurno(this)" required>
              <div class="invalid-feedback" id="invalid-turnoEdit">El nombre del turno es requerido.</div>
            </div>
            <div class="form-group">
              <label for="usr">Tipo de jornada:</label>
              <select id="tipo_jornada_edit" name="tipo_jornada_edit" class="form-control">
              </select>
            </div>
            <div class="form-group">
              <label for="usr">Entrada:</label>
              <input type="text" class="form-control  time-only" name="txtEntradaU" id="txtEntradaU" value="" required>
              <div class="invalid-feedback" id="invalid-entradaEdit">La hora de entrada es requerida.</div>
            </div>
            <div class="form-group">
              <label for="usr">Salida:</label>
              <input type="text" class="form-control time-only" name="txtSalidaU" id="txtSalidaU" required>
              <div class="invalid-feedback" id="invalid-salidaEdit">La hora de salida es requerida.</div>
            </div>
            <div class="form-group weekDays-selector2">
              <form id="form2" name="cat" method="POST" action="">
                <label for="usr">Dias de trabajo:</label><br>
                <input type="checkbox" name="weekdayU" id="weekday-monU" class="weekday1" value="1" onclick="checkbox_UnSelected();" />
                <label for="weekday-monU">LUN</label>
                <input type="checkbox" name="weekdayU" id="weekday-tueU" class="weekday1" value="2" onclick="checkbox_UnSelected();" />
                <label for="weekday-tueU">MAR</label>
                <input type="checkbox" name="weekdayU" id="weekday-wedU" class="weekday1" value="3" onclick="checkbox_UnSelected();" />
                <label for="weekday-wedU">MIE</label>
                <input type="checkbox" name="weekdayU" id="weekday-thuU" class="weekday1" value="4" onclick="checkbox_UnSelected();" />
                <label for="weekday-thuU">JUE</label>
                <input type="checkbox" name="weekdayU" id="weekday-friU" class="weekday1" value="5" onclick="checkbox_UnSelected();" />
                <label for="weekday-friU">VIE</label>
                <input type="checkbox" name="weekdayU" id="weekday-satU" class="weekday1" value="6" onclick="checkbox_UnSelected();" />
                <label for="weekday-satU">SAB</label>
                <input type="checkbox" name="weekdayU" id="weekday-sunU" class="weekday1" value="7" onclick="checkbox_UnSelected();" />
                <label for="weekday-sunU">DOM</label>
              </form>
            </div>
            <div class="form-group">
              <label for="usr">Tiempo de comida:</label>
              <input type="text" class="form-control time-only time_element" name="txtComidaU" id="txtComidaU" required>
              <div class="invalid-feedback" id="invalid-comidaEdit">La hora de comida es requerida.</div>
            </div>
          </div>
          <div class="modal-footer justify-content-center">
            <div class="permission-view-delete-Turnos_46"></div>
            <button type="button" class="btnesp first espCancelar btnCancelarActualizacion" data-dismiss="modal" id="btnCancelarActualizacionU_Turnos_46"><span class="ajusteProyecto">Cancelar</span></button>
            <div class="permission-view-edit-Turnos_46"></div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!--END ADD MODAL SLIDE TURNOS-->

  <!--DELETE MODAL SLIDE TURNOS-->
  <div class="modal fade" id="eliminar_Turnos_46" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form action="" method="POST">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">¿Desea eliminar este turno?</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">x</span>
            </button>
          </div>
          <div>
            <input type="hidden" name="txtPKTurnos_46D" id="txtPKTurnos_46D">
            <br>
            <label for="usr" style="margin-left: 80px!important;">Se eliminará el siguiente turno:</label>
          </div>

          <div class="form-group col-md-6">
            <label for="usr">Turno:</label>
          </div>
          <div class="form-group col-md-12">
            <input type="text" style="border:none!important;" class="form-control" maxlength="50" id="txtTurnos_46D" name="txtTurnos_46D" required readonly>
          </div>

          <div class="modal-body" style="font-size: 10 px!important; color: red;">Esta acción no podrá deshacerse</div>
          <div class="modal-footer">
            <button class="btnesp first espCancelar btnCancelarActualizacion" type="button" data-dismiss="modal"><span class="ajusteProyecto">Cancelar</span></button>
            <button type="button" class="btnesp espAgregar float-right" id="btn_aceptar_eliminar_Turnos_46"><span class="ajusteProyecto" data-dismiss="modal">Eliminar</span></button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!--END DELETE MODAL SLIDE TURNOS-->

  <!------------------------------------------------ END MODALS TURNOS --------------------------------------------------------------->

  <!------------------------------------------------ MODALS CATEGORIA GASTOS --------------------------------------------------------------->

  <!--ADD MODAL SLIDE CATEGORIA GASTOS-->
  <div class="modal fade right" id="agregar_CategoriaGastos_47" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
            <input class="form-control" id="notaCategoria" name="notaCategoria" type="hidden" style="color: darkred; background-color: transparent!important; border: none;" value="Nota: Nombre de la categoría ya registrada." readonly>
            <div class="form-group">
              <label for="usr">Nombre:*</label>
              <input type="text" class="form-control alpha-only" maxlength="30" id="txtNombreCategoria" name="txtNombreCategoria" onkeyup="validarUnicaCategoriaGasto()">
              <div class="invalid-feedback" id="invalid-nombreGasto">El gasto debe tener un nombre.</div>
            </div>
          </div>
          <div class="modal-footer justify-content-center">
            <button type="button" class="btnesp first espCancelar btnCancelarActualizacion" data-dismiss="modal" id="btnCancelarActualizacion_CategoriaGastos_47"><span class="ajusteProyecto">Cancelar</span></button>
            <button type="button" class="btnesp espAgregar float-right" name="btnAgregar" id="btnAgregarCategoriaGastos"><span class="ajusteProyecto" >Agregar</span></button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!--END ADD MODAL SLIDE CATEGORIA GASTOS-->

  <div class="modal fade right" id="editar_CategoriaGastos_47" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-full-height modal-right modal-md" role="document">
      <div class="modal-content">
        <form action="" id="editarCategoriaU" method="POST">
          <input type="hidden" name="txtUpdatePKCategoriaGastos_47" id="txtUpdatePKCategoriaGastos_47">
          <div class="modal-header">
            <h4 class="modal-title w-100" id="myModalLabel">Editar categoría</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">x</span>
            </button>
          </div>
          <div class="modal-body">
            <!--
            <input class="form-control" id="notaExisteRelacion" name="notaExisteRelacion" type="hidden"
              style="color: darkred; background-color: transparent!important; border: none;"
              value="Nota: La categoría está siendo utilizada." readonly>
            -->
            <div class="form-group">
              <label for="usr">Nombre:*</label>
              <input type="text" id="txtUpdateCategoriaGastos_47" class="form-control alpha-only" maxlength="40" name="txtUpdateCategoriaGastos_47" onkeyup="validarUnicaCategoriaGastoU()" required>
              <div class="invalid-feedback" id="invalid-nombreGastoEdit">El gasto debe tener un nombre.</div>
            </div>
            <div class="modal-footer justify-content-center">
              <div class="permission-view-delete-CategoriaGastos_47"></div>

              <button type="button" class="btnesp first espCancelar btnCancelarActualizacion" data-dismiss="modal" id="btnCancelarActualizacionU_CategoriaGastos_47"><span class="ajusteProyecto">Cancelar</span></button>

              <div class="permission-view-edit-CategoriaGastos_47"></div>

            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!--END UPDATE MODAL -->

  <!--DELETE MODAL SLIDE CATEGORIA GASTOS-->
  <div class="modal fade" id="eliminar_CategoriaGastos_47" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form action="" method="POST">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">¿Desea eliminar esta categoría de gasto?</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">x</span>
            </button>
          </div>
          <div>
            <input type="hidden" name="txtPKCategoriaGastos_47D" id="txtPKCategoriaGastos_47D">
            <br>
            <label for="usr" style="margin-left: 80px!important;">Se eliminará la siguiente categoría de gasto:</label>
          </div>

          <div class="form-group col-md-6">
            <label for="usr">Categoría de gasto:</label>
          </div>
          <div class="form-group col-md-12">
            <input type="text" style="border:none!important;" class="form-control" maxlength="50" id="txtCategoriaGastos_47D" name="txtCategoriaGastos_47D" required readonly>
          </div>

          <div class="modal-body" style="font-size: 10 px!important; color: red;">Esta acción no podrá deshacerse</div>
          <div class="modal-footer">
            <button class="btnesp first espCancelar btnCancelarActualizacion" type="button" data-dismiss="modal"><span class="ajusteProyecto">Cancelar</span></button>
            <button type="button" class="btnesp espAgregar float-right" id="btn_aceptar_eliminar_CategoriaGastos_47"><span class="ajusteProyecto" data-dismiss="modal">Eliminar</span></button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!--END DELETE MODAL SLIDE CATEGORIA GASTOS-->

  <!------------------------------------------------ END MODALS CATEGORIA GASTOS --------------------------------------------------------------->

  <!------------------------------------------------ MODALS SUBCATEGORIA GASTOS --------------------------------------------------------------->

  <!--ADD MODAL SLIDE SUBCATEGORIA GASTOS -->
  <div class="modal fade right" id="agregar_SubcategoriaGastos_48" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
              <input type="text" class="form-control alpha-only" maxlength="30" id="txtNombreSubcategoria" name="txtNombreSubcategoria" onkeyup="validarUnicaSubCatGasto()" required>
              <div class="invalid-feedback" id="invalid-nombreSubCat">La subcategoría de gastos debe tener un nombre.
              </div>
            </div>
            <div class="form-group">
              <label for="usr">Categoría:*</label>
              <select name="cmbCategoria" class="form-control" id="cmbCategoria" onchange="validEmptyInput(this)" required>

              </select>
              <div class="invalid-feedback" id="invalid-nombreCat">La subcategoría de tener una categoria.
              </div>
            </div>
            <label for="usr">* Campos requeridos</label>
          </div>

          <div class="modal-footer justify-content-center">
            <button type="button" class="btnesp first espCancelar btnCancelarActualizacion" data-dismiss="modal" id="btnCancelarActualizacion_SubcategoriaGastos_45"><span class="ajusteProyecto">Cancelar</span></button>
            <button type="button" class="btnesp espAgregar float-right" name="btnAgregar" id="btnAgregarSubcategoria"><span class="ajusteProyecto">Agregar</span></button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!--END ADD MODAL SLIDE SUBCATEGORIA GASTOS -->

  <div class="modal fade right" id="editar_SubcategoriaGastos_48" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-full-height modal-right modal-md" role="document">
      <div class="modal-content">
        <form action="" id="editarSubcategoriaU" method="POST">
          <input type="hidden" name="txtUpdatePKSubcategoriaGastos_48" id="txtUpdatePKSubcategoriaGastos_48">
          <input type="hidden" name="txtFKCategoriaU" id="txtFKCategoriaU">
          <div class="modal-header">
            <h4 class="modal-title w-100" id="myModalLabel">Editar subcategoría</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">x</span>
            </button>
          </div>
          <div class="modal-body">
            <!--
            <input class="form-control" id="notaExisteRelacion" name="notaExisteRelacion" type="hidden"
              style="color: darkred; background-color: transparent!important; border: none;"
              value="Nota: La subcategoría está siendo utilizada." readonly>
            -->
            <div class="form-group">
              <label for="usr">Nombre:*</label>
              <input type="text" id="txtUpdateSubcategoriaGastos_48" class="form-control alpha-only" maxlength="40" name="txtUpdateSubcategoriaGastos_48" value="" onkeyup="validarUnicaSubCatGastoU()" required>
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
              <div class="permission-view-delete-SubcategoriaGastos_48"></div>
              <button type="button" class="btnesp first espCancelar btnCancelarActualizacion" data-dismiss="modal" id="btnCancelarActualizacionU_SubcategoriaGastos_45"><span class="ajusteProyecto">Cancelar</span></button>

              <div class="permission-view-edit-SubcategoriaGastos_48"></div>

            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!--END UPDATE MODAL -->

  <!--DELETE MODAL SLIDE SUBCATEGORIA GASTOS-->
  <div class="modal fade" id="eliminar_SubcategoriaGastos_48" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form action="" method="POST">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">¿Desea eliminar esta subcategoría de gasto?</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">x</span>
            </button>
          </div>
          <div>
            <input type="hidden" name="txtPKSubcategoriaGastos_48D" id="txtPKSubcategoriaGastos_48D">
            <br>
            <label for="usr" style="margin-left: 80px!important;">Se eliminará la siguiente subcategoría de gasto:</label>
          </div>

          <div class="form-group col-md-6">
            <label for="usr">Categoría de gasto:</label>
          </div>
          <div class="form-group col-md-12">
            <input type="text" style="border:none!important;" class="form-control" maxlength="50" id="txtSubcategoriaGastos_48D" name="txtSubcategoriaGastos_48D" required readonly>
          </div>

          <div class="modal-body" style="font-size: 10 px!important; color: red;">Esta acción no podrá deshacerse</div>
          <div class="modal-footer">
            <button class="btnesp first espCancelar btnCancelarActualizacion" type="button" data-dismiss="modal"><span class="ajusteProyecto">Cancelar</span></button>
            <button type="button" class="btnesp espAgregar float-right" id="btn_aceptar_eliminar_SubcategoriaGastos_48"><span class="ajusteProyecto" data-dismiss="modal">Eliminar</span></button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!--END DELETE MODAL SLIDE SUBCATEGORIA GASTOS-->

  <!------------------------------------------------ END MODALS SUBCATEGORIA GASTOS --------------------------------------------------------------->

  <!------------------------------------------------ MODALS RESPONSABLES GASTOS --------------------------------------------------------------->

  <!--ADD MODAL SLIDE RESPONSABLE GASTOS-->
  <div class="modal fade right" id="agregar_ResponsableGastos_49" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
              <select name="cmbEmpleadoResGasto" id="cmbEmpleadoResGasto" onchange="validEmptyInput(this, 'invalid-responsable')">
              </select>
              <div class="invalid-feedback" id="invalid-responsable">El responsable debe tener un nombre.</div>
            </div>
            <label for="usr">* Campos requeridos</label>
          </div>

          <div class="modal-footer justify-content-center">
            <button type="button" class="btnesp first espCancelar btnCancelarActualizacion" data-dismiss="modal" id="btnCancelarActualizacion_ResponsableGastos_46"><span class="ajusteProyecto">Cancelar</span></button>
            <button type="button" class="btnesp espAgregar float-right" name="btnAgregar" id="btnAgregarResponsable"><span class="ajusteProyecto" onclick="agregarResponsable()">Agregar</span></button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!--END ADD MODAL SLIDE RESPONSABLE GASTOS-->

  <div class="modal fade right" id="editar_ResponsableGastos_49" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-full-height modal-right modal-md" role="document">
      <div class="modal-content">
        <form action="" id="editarResponsableGastosU" method="POST">
          <input type="hidden" name="txtUpdatePKResponsableGastos_49" id="txtUpdatePKResponsableGastos_49">
          <input type="hidden" name="idFkResponsableU" id="idFkResponsableU">
          <div class="modal-header">
            <h4 class="modal-title w-100" id="myModalLabel">Editar responsable de gasto</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">x</span>
            </button>
          </div>
          <div class="modal-body">
            <!--
            <input type="hidden" name="idResponsableN" id="idResponsableN">
            <input class="form-control" id="notaExisteRelacion" name="notaExisteRelacion" type="hidden"
              style="color: darkred; background-color: transparent!important; border: none;"
              value="Nota: El responsable esta siendo utilizado." readonly>
            -->
            <div class="form-group">
              <label for="usr">Nombre responsable:*</label>
              <input type="text" id="txtUpdateResponsableGastos_49" class="form-control alpha-only" maxlength="40" name="txtUpdatePKResponsableGastos_49" value="" disabled>
            </div>
            <div class="form-group">
              <input class="form-control" id="notaResponsableNuevo" name="notaResponsableNuevo" type="hidden" style="color: darkred; background-color: transparent!important; border: none;" value="Nota: Este responsable ya está registrado." readonly>
              <br>
              <label for="usr">Nuevo responsable:*</label>
              <select name="cmbResponsableU" class="form-control" id="txtResponsableGastosU_49" required>
              </select>
              <div class="invalid-feedback" id="invalid-responsableEdit">El responsable debe tener un nombre.</div>
            </div>

            <label for="usr">* Campos requeridos</label>

            <div class="modal-footer justify-content-center">
              <div class="permission-view-delete-ResponsableGastos_49"></div>

              <button type="button" class="btnesp first espCancelar btnCancelarActualizacionEd" data-dismiss="modal" id="btnCancelarActualizacionU_ResponsableGastos_46"><span class="ajusteProyecto">Cancelar</span></button>
              <div class="permission-view-edit-ResponsableGastos_49"></div>
              <!--<button type="button" class="btnesp espAgregar float-right" name="btnEditar" id="btnEditarResponsable"
                value=""><span class="ajusteProyecto">Modificar</span></button>-->
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!--END UPDATE MODAL -->

  <!--DELETE MODAL SLIDE SUBCATEGORIA GASTOS-->
  <div class="modal fade" id="eliminar_ResponsableGastos_49" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form action="" method="POST">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">¿Desea eliminar al responsable de gastos?</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">x</span>
            </button>
          </div>
          <div class="modal-body">
            <br />
            <p>Se eliminará al siguiente responsable de gastos:</p>
            <p>Responsable:</p>
            <div class="form-group">
              <input type="hidden" id="txtempleadoResponsable_49D">
              <input type="text" style="border:none!important;" class="form-control" maxlength="50" id="txtResponsableGastos_49D" name="txtResponsableGastos_49D" required readonly>
            </div>
            <p class="text-danger">Esta acción no podrá deshacerse</p>
          </div>
          <div class="modal-footer">
            <button class="btn-custom btn-custom--border-blue" type="button" data-dismiss="modal">Cancelar</button>
            <button type="button" class="btn-custom btn-custom--blue" id="btn_aceptar_eliminar_ResponsableGastos_49">Eliminar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!--END DELETE MODAL SLIDE SUBCATEGORIA GASTOS-->

  <!------------------------------------------------ END MODAL RESPONSABLES GASTOS --------------------------------------------------------------->

  <!------------------------------------------------ MODALS SUCURSALES --------------------------------------------------------------->

  <div class="modal fade right" id="agregar_Sucursales_50" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

    <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
    <div class="modal-dialog modal-full-height modal-right modal-md" role="document">


      <div class="modal-content">
        <form action="" id="agregarLocacion" method="POST">
          <!--<input type="hidden" name="idProyectoA" value="">-->
          <div class="modal-header">
            <h4 class="modal-title w-100" id="myModalLabel">Agregar sucursal</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">x</span>
            </button>
          </div>
          <div class="modal-body">
            <input class="form-control" id="notaSucursal" name="notaSucursal" type="hidden" style="color: darkred; background-color: transparent!important; border: none;" value="Nota: Nombre de la sucursal ya está registrada." readonly>

            <div class="form-group">
              <label for="usr">Nombre de sucursal:*</label>
              <input type="text" id="txtarea" class="form-control alpha-only" maxlength="40" name="txtLocacion" required onkeyup="validarUnicaSucursal(this)">
              <div class="invalid-feedback" id="invalid-nombreSuc">La sucursal debe tener un nombre.</div>
            </div>
            <div class="form-group">
              <label for="usr">Calle:*</label>
              <input type="text" id="txtarea2" class="form-control alpha-only" name="txtCalle" maxlength="50" onkeyup="validEmptyInput(this)" required>
              <div class="invalid-feedback" id="invalid-calleSuc">La sucursal debe tener una calle.</div>
            </div>
            <div class="form-group">
              <label for="usr">Número exterior:*</label>
              <input type="text" id="txtarea3" class="form-control numeric-only" name="txtNe" onkeyup="validEmptyInput(this)" required>
              <div class="invalid-feedback" id="invalid-noExtSuc">La sucursal debe tener un número exterior.</div>
            </div>
            <div class="form-group">
              <label for="usr">Número interior: </label>
              <div class="input-group">
                <select name="cmbPrefijo" class="form-control" placeholder="" id="txtarea9">
                  <option disabled selected hidden>Seleccionar</option>
                  <option value="Interior">Interior</option>
                  <option value="Bodega">Bodega</option>
                  <option value="Piso">Piso</option>
                  <option value="Departamento">Departamento</option>
                </select>
                <input type="text" id="txtarea4" style="width:40%;" class="form-control alphaNumeric-only" style="text-transform: uppercase" name="txtNi">
              </div>
            </div>
            <div class="form-group">
              <label for="usr">Colonia:*</label>
              <input type="text" id="txtarea5" class="form-control alpha-only" size="40" name="txtColonia" onkeyup="validEmptyInput(this)" required>
              <div class="invalid-feedback" id="invalid-coloniaSuc">La sucursal debe tener una colonia.</div>
            </div>
            <div class="form-group">
              <label for="usr">Municipio:*</label>
              <input type="text" id="txtarea7" class="form-control alphaNumeric-only" size="40" name="txtMunicipio" onkeyup="validEmptyInput(this)" required>
              <div class="invalid-feedback" id="invalid-municipioSuc">La sucursal debe tener un municipio.</div>
            </div>
            <div class="form-group">
              <label for="usr">País:*</label>
              <select name="cmbPais" class="" id="txtarea8" onchange="validEmptyInput(this)" required>
                <!--<option value="" style="" disabled selected hidden>Seleccionar pais</option>-->
                <?php
                $stmt = $conn->prepare("SELECT * FROM paises");
                $stmt->execute();
                $row = $stmt->fetchAll();

                if (count($row) > 0) {
                  foreach ($row as $r) { //Mostrar usuarios
                    if ($r['Disponible'] == 1) {
                      echo '<option value="' . $r['PKPais'] . '" selected>' . $r['Pais'] . '</option>';
                      $pais = $r['PKPais'];
                    } else {
                      //echo '<option value="'.$r['PKPais'].'">'.$r['Pais'].'</option>';
                    }
                  }
                } else {
                  echo '<option value="" disabled>No hay registros para mostrar.</option>';
                }
                ?>
              </select>
              <div class="invalid-feedback" id="invalid-paisSuc">La sucursal debe tener un país.</div>
              <!--<input type="text" id="txtarea6" class="form-control alpha-only" size="40" name="txtEstado" required>-->
            </div>
            <div class="form-group">
              <label for="usr">Estado:*</label>
              <select name="cmbEstados" class="" id="txtarea6" onchange="validEmptyInput(this)" required>
                <option disabled selected>Seleccionar estado</option>
                <?php
                $stmt = $conn->prepare("SELECT * FROM estados_federativos WHERE FKPais = 146");
                $stmt->execute();
                $row = $stmt->fetchAll();
                if (count($row) > 0) {
                  foreach ($row as $r) { //Mostrar usuarios
                    echo '<option value="' . $r['PKEstado'] . '" >' . $r['Estado'] . '</option>';
                  }
                } else {
                  echo '<option value="" disabled>No hay registros para mostrar.</option>';
                }
                ?>
              </select>
              <div class="invalid-feedback" id="invalid-estadoSuc">La sucursal debe tener un estado.</div>
              <!--<input type="text" id="txtarea6" class="form-control alpha-only" size="40" name="txtEstado" required>-->
            </div>
            <div class="form-group">
              <label for="usr">Teléfono:*</label>
              <input type="text" id="txtarea10" maxlength="10" class="form-control numeric-only" name="txtTelefono" onkeyup=" return validaNumTelefono(event,this)" required>
              <input type="hidden" id="result1" readonly>
              <div class="invalid-feedback" id="invalid-telSuc">La sucursal debe número de teléfono valido.</div>

            </div>
            <div class="form-group">
              <label for="usr">¿Se administran inventarios en esta sucursal?</label>
              <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="cbxActivarInventario" name="cbxActivarInventario">
                <label class="form-check-label" for="flexSwitchCheckDefault">Activar inventario</label>
              </div>
            </div>

            <div class="form-group">
              <label for="usr">Zona salario mínimo</label>
              <div class="form-check">
                <input type="radio" id="radioZonaSalarioMinimo" name="radioZonaSalarioMinimo" value="2" class="form-check-input frontera">
                <label class="form-check-label" for="norte">Zona libre de la frontera</label>
              </div>
              <div class="form-check">
                <input type="radio" id="radioZonaSalarioMinimo" name="radioZonaSalarioMinimo" value="1" checked class="form-check-input general">
                <label for="general">Resto del país</label>
              </div>
            </div>
            <br><br>
            <div>
              <label for="usr">Campos requeridos *</label>
            </div>
          </div>
          <div class="modal-footer justify-content-center">
            <button type="button" class="btnesp first espCancelar btnCancelarActualizacion" data-dismiss="modal" id="btnCancelarActualizacion_Sucursales_47"><span class="ajusteProyecto">Cancelar</span></button>
            <button type="button" class="btnesp espAgregar float-right" name="btnAgregar" id="btnAgregarLocacion"><span class="ajusteProyecto">Agregar</span></button>
          </div>
        </form>
      </div>
    </div>
  </div>
  </div>
  <!-- End Add Fluid Modal mis proyectos -->

  <!--UPDATE MODAL SUCURSALES-->
  <div class="modal fade right" id="editar_Sucursales_50" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-full-height modal-right modal-md" role="document">
      <div class="modal-content">
        <form action="" id="editarLocacionU" method="POST">
          <input type="hidden" name="txtUpdatePKSucursales_50" id="txtUpdatePKSucursales_50">
          <div class="modal-header">
            <h4 class="modal-title w-100" id="myModalLabel">Editar sucursal</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">x</span>
            </button>
          </div>
          <div class="modal-body">
            <!-- 
            <input class="form-control" id="notaExisteRelacion" name="notaExisteRelacion" type="hidden"
              style="color: darkred; background-color: transparent!important; border: none;"
              value="Nota: La sucursal está siendo utilizada." readonly>
            -->
            <div class="form-group">
              <label for="usr">Nombre de sucursal:*</label>
              <input type="text" id="txtUpdateSucursales_50" class="form-control alpha-only" maxlength="40" name="txtUpdateSucursales_50" onkeyup="validarUnicaSucursalU(this)" required>
              <div class="invalid-feedback" id="invalid-nombreSucEdit">La sucursal debe tener un nombre.</div>
            </div>
            <div class="form-group">
              <label for="usr">Calle:*</label>
              <input type="numeric" id="txtarea2u" class="form-control " maxlength="40" name="txtCalleU" onkeyup="validEmptyInput(this)" required>
              <div class="invalid-feedback" id="invalid-calleSucEdit">La sucursal debe tener una calle.</div>
            </div>
            <div class="form-group">
              <label for="usr">Número exterior:*</label>
              <input type="text" id="txtarea3u" class="form-control numeric-only" name="txtNeU" onkeyup="validEmptyInput(this)" required>
              <div class="invalid-feedback" id="invalid-noExtSucEdit">La sucursal debe tener un número exterior.</div>
            </div>
            <div class="form-group">
              <label for="usr">Número interior:</label>

              <div class="input-group">
                <select name="cmbPrefijoU" class="form-control" id="txtarea9u">
                  <!--<option value="">Seleccionar</option>-->
                  <option value="Interior">Interior</option>
                  <option value="Bodega">Bodega</option>
                  <option value="Piso">Piso</option>
                  <option value="Departamento">Departamento</option>
                </select>
                <input type="text" id="txtarea4u" style="width:40%;" class="form-control alphaNumeric-only" name="txtNiU">
              </div>
            </div>
            <div class="form-group">
              <label for="usr">Colonia:*</label>
              <input type="text" id="txtarea5u" class="form-control alpha-only" size="40" name="txtColoniaU" onkeyup="validEmptyInput(this)" required>
              <div class="invalid-feedback" id="invalid-coloniaSucEdit">La sucursal debe tener una colonia.</div>
            </div>
            <div class="form-group">
              <label for="usr">Municipio:*</label>
              <input type="text" id="txtarea7u" class="form-control alphaNumeric-only" size="40" name="txtMunicipioU" onkeyup="validEmptyInput(this)" required>
              <div class="invalid-feedback" id="invalid-municipioSucEdit">La sucursal debe tener un municipio.</div>
            </div>
            <div class="form-group">
              <label for="usr">País:*</label>
              <select name="cmbPaisU" class="" id="txtarea8u" onchange="validEmptyInput(this)" required>
                <!--<option value="Elegir" id="AF">Elegir opción</option>-->
                <?php
                $stmt = $conn->prepare("SELECT * FROM paises");
                $stmt->execute();
                $row = $stmt->fetchAll();
                if (count($row) > 0) {
                  foreach ($row as $r) { //Mostrar usuarios
                    if ($r['Disponible'] == 1) {
                      echo '<option value="' . $r['PKPais'] . '" selected>' . $r['Pais'] . '</option>';
                    } else {
                      //echo '<option value="'.$r['PKPais'].'">'.$r['Pais'].'</option>';
                    }
                  }
                } else {
                  echo '<option value="" disabled>No hay registros para mostrar.</option>';
                }
                ?>
              </select>
              <div class="invalid-feedback" id="invalid-paisSucEdit">La sucursal debe tener un país.</div>
            </div>
            <div class="form-group">
              <label for="usr">Estado:*</label>
              <select name="cmbEstadosU" class="form-control" id="txtarea6u" onchange="validEmptyInput(this)" required>
                <?php
                $stmt = $conn->prepare("SELECT * FROM estados_federativos WHERE FKPais = 146");
                $stmt->execute();
                $row = $stmt->fetchAll();
                if (count($row) > 0) {
                  foreach ($row as $r) { //Mostrar usuarios
                    echo '<option value="' . $r['PKEstado'] . '" >' . $r['Estado'] . '</option>';
                  }
                } else {
                  echo '<option value="" disabled>No hay registros para mostrar.</option>';
                }
                ?>
              </select>
              <div class="invalid-feedback" id="invalid-estadoSucEdit">La sucursal debe tener un número estado.</div>
            </div>

            <div class="form-group">
              <label for="usr">Teléfono:*</label>
              <input type="numeric" maxlength="10" id="txtarea10u" class="form-control numeric-only" size="40" name="txtTelefono" onkeyup=" return validaNumTelefonoU(event,this)" required>
              <input type="hidden" id="result2" readonly>
              <div class="invalid-feedback" id="invalid-telSucEdit">La sucursal debe número de teléfono valido.</div>
            </div>

            <div class="form-group">
              <div class="row">
                <div class="col-lg-12">
                  <label for="usr">¿Se administran inventarios en esta sucursal?</label>
                  <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="cbxActivarInventarioU" name="cbxActivarInventarioU">
                    <label class="form-check-label" for="flexSwitchCheckDefault">Activar inventario</label>
                  </div>
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="row">
                <div class="col-lg-12">
                  <label for="usr">Zona salario mínimo</label>
                  <div class="row">
                    <div class="col-lg-6">
                      <input type="radio" id="radioZonaSalarioMinimoEdit" name="radioZonaSalarioMinimoEdit" value="2" class="fronteraEdit">
                      <label for="norte">Zona libre de la frontera</label>
                    </div>
                    <div class="col-lg-6">
                      <input type="radio" id="radioZonaSalarioMinimoEdit" name="radioZonaSalarioMinimoEdit" value="1" class="generalEdit">
                      <label for="general">Resto del país</label>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="modal-footer justify-content-center">
              <div class="permission-view-delete-Sucursales_50"></div>

              <button type="button" class="btnesp first espCancelar btnCancelarActualizacion" data-dismiss="modal" id="btnCancelarActualizacionU_Sucursales_47"><span class="ajusteProyecto">Cancelar</span></button>
              <div class="permission-view-edit-Sucursales_50"></div>

            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!--END UPDATE MODAL DENTRO DE PROYECTOS-->

  <!--DELETE MODAL SLIDE SUCURSALES-->
  <div class="modal fade" id="eliminar_Sucursales_50" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form action="" method="POST">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">¿Desea eliminar esta subcategoría de gasto?</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">x</span>
            </button>
          </div>
          <div>
            <input type="hidden" name="txtPKSucursales_50D" id="txtPKSucursales_50D">
            <br>
            <label for="usr" style="margin-left: 80px!important;">Se eliminará la siguiente subcategoría de gasto:</label>
          </div>

          <div class="form-group col-md-6">
            <label for="usr">Categoría de gasto:</label>
          </div>
          <div class="form-group col-md-12">
            <input type="text" style="border:none!important;" class="form-control" maxlength="50" id="txtSucursales_50D" name="txtSucursales_50D" required readonly>
          </div>

          <div class="modal-body" style="font-size: 10 px!important; color: red;">Esta acción no podrá deshacerse</div>
          <div class="modal-footer">
            <button class="btnesp first espCancelar btnCancelarActualizacion" type="button" data-dismiss="modal"><span class="ajusteProyecto">Cancelar</span></button>
            <button type="button" class="btnesp espAgregar float-right" id="btn_aceptar_eliminar_Sucursales_50"><span class="ajusteProyecto" data-dismiss="modal">Eliminar</span></button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!--END DELETE MODAL SLIDE SUCURSALES-->

  <!------------------------------------------------ END MODALS SUCURSALES --------------------------------------------------------------->

  <!------------------------------------------------ MODALS TIPO ORDEN INVENTARIO --------------------------------------------------------------->

  <!--ADD MODAL SLIDE TIPOS ORDEN DE INVENTARIO-->
  <div class="modal fade right" id="agregar_Tipoordeninventario_51" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
    <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">
      <div class="modal-content">
        <form action="" method="POST" id="agregarTipoOrdenInventario">
          <input type="hidden" name="idInput" value="">
          <div class="modal-header">
            <h4 class="modal-title w-100" id="myModalLabel">Agregar Tipo de Orden de Inventario</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">x</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-lg-12">
                <form id="formDatosTipoOrdenInventario">
                  <div class="form-group">
                    <div class="row">
                      <div class="col-lg-12">
                        <label for="usr">Nombre del tipo de orden</label>
                        <input class="form-control alphaNumeric-only" type="text" name="txtTipoOrdenInventario" id="txtTipoOrdenInventario" autofocus="" required="" placeholder="Ej. Bajo pedido" onkeyup="validarTipoOrdenInventario()">
                        <div class="invalid-feedback" id="invalid-nombreOrden">El tipo de orden debe tener un nombre.
                        </div>
                      </div>
                    </div>
                  </div>
                  <br>
                </form>
              </div>
            </div>
          </div>
          <div class="modal-footer justify-content-center">
            <button type="button" class="btn-custom btn-custom--border-blue btnCancelarActualizacion" data-dismiss="modal" id="btnCancelarActualizacion_Tipoordeninventario_48"><span class="ajusteProyecto">Cancelar</span></button>
            <button type="submit" class="btn-custom btn-custom--blue" name="btnAgregarTipoOrdenInventario" id="btnAgregarTipoOrdenInventario"><span class="ajusteProyecto" data-dismiss="" onclick="anadirTipoOrdenInventario()">Agregar</span></button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!--END ADD MODAL SLIDE TIPOS ORDEN DE INVENTARIO-->


  <!--EDIT MODAL SLIDE TIPOS ORDEN DE INVENTARIO-->
  <div class="modal fade right" id="editar_Tipoordeninventario_51" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
    <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">
      <div class="modal-content">
        <form action="" method="POST" id="aditarTipoOrdenInventario">
          <input type='hidden' name="txtPKTipoOrdenInventario" id="txtPKTipoOrdenInventario">
          <input type='hidden' name="txtTipOIActual" id="txtTipOIActual">
          <div class="modal-header">
            <h4 class="modal-title w-100" id="myModalLabel">Editar Tipo de Orden de Inventario</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">x</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-lg-12">
                <form id="formDatosCategoriaU">
                  <div class="form-group">
                    <div class="row">
                      <div class="col-lg-4">

                      </div>
                      <div class="col-lg-3">
                        <label for="usr">Estatus:*</label>
                      </div>
                      <div class="col-lg-5">
                        <input type="checkbox" id="activeOrdenInv" name="activeOrdenInv" class="check-custom">
                        <label class="shadow-sm check-custom-label" for="activeOrdenInv">
                          <div class="circle"></div>
                          <div class="check-inactivo">Inactivo</div>
                          <div class="check-activo">Activo</div>
                        </label>
                      </div>
                    </div>
                  </div>
                  <input class="form-control" id="notaEstatusU" name="notaEstatusU" type="hidden" style="color: darkred; background-color: transparent!important; border: none;" value="Nota: Tipo de orden utilizado por un producto." readonly>
                  <div class="form-group">
                    <div class="row">
                      <div class="col-lg-12">
                        <label for="usr">Nombre del tipo de orden:*</label>
                        <input class="form-control alphaNumeric-only" type="text" name="txtTipoOrdenInventarioU" id="txtTipoOrdenInventarioU" autofocus="" required="" placeholder="Ej. Bajo pedido" onkeyup="validarTipoOrdenInventarioU()">
                        <div class="invalid-feedback" id="invalid-nombreOrdenEdit">El tipo de orden debe tener un
                          nombre.
                        </div>
                      </div>
                    </div>
                  </div>
                  <br>
                </form>
              </div>
            </div>
          </div>
          <div class="modal-footer justify-content-end">
            <button type="button" class="btn-custom btn-custom--border-blue btnCancelarActualizacion" data-dismiss="modal" id="btnCancelarActualizacionU_Tipoordeninventario_48"><span class="ajusteProyecto">Cancelar</span></button>
            <div class="permission-view-delete-Tipoordeninventario_51"></div>
            <div class="permission-view-edit-Tipoordeninventario_51"></div>

          </div>
        </form>
      </div>
    </div>
  </div>
  <!--END EDIT MODAL SLIDE TIPOS ORDEN DE INVENTARIO-->

  <!--DELETE MODAL SLIDE TIPOS ORDEN DE INVENTARIO-->
  <div class="modal fade" id="eliminar_Tipoordeninventario_51" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form action="" method="POST">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">¿Desea eliminar el registro de este?</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">x</span>
            </button>
          </div>
          <div class="modal-body">
            <input type="hidden" name="txtPKTipoOrdenInventarioD" id="txtPKTipoOrdenInventarioD">
            <p>Se eliminará el tipo de orden con los siguientes datos:</p>
            <p>Nombre:</p>
            <input type="text" style="border:none!important;" class="form-control" maxlength="50" id="txtTipoOrdenInventarioD" name="txtTipoOrdenInventarioD" required readonly>
            <p class="text-danger">Esta acción no podrá deshacerse</p>
          </div>
          <div class="modal-footer">
            <button class="btn-custom btn-custom--border-blue btnCancelarActualizacion" type="button" data-dismiss="modal"><span class="ajusteProyecto" data-toggle="modal" data-target="#editar_TipoOrdenInventario" onclick="obtenerIdTipoOrdenInventarioEditar($('#txtPKTipoOrdenInventarioD').val())">Cancelar</span></button>
            <button type="button" onclick="eliminarTipoOrdenInventario();" class="btn-custom btn-custom--blue"><span class="ajusteProyecto" data-dismiss="modal">Eliminar</span></button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!--END DELETE MODAL SLIDE TIPOS ORDEN DE INVENTARIO -->

  <!------------------------------------------------ END MODALS DATOS EMPRESA --------------------------------------------------------------->

  <!-- ADD DATOS EMPRESA -->
  <div class="modal fade right" id="agregar_DatosEmpresa_52" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
    <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">
      <div class="modal-content">
        <form method="POST" id="agregarDatosEmpresa" enctype="multipart/form-data">
          <div class="modal-header">
            <h4 class="modal-title w-100" id="myModalLabel">Agregar Datos Empresa</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">x</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label for="txtGiroComercial">Giro comercial:</label>
              <input type="text" class="form-control" name="txtGiroComercial" id="txtGiroComercial" maxlength="15">
            </div>
            <div class="form-group">
              <label for="txtRegimenFiscal">Régimen fiscal:</label>
              <!--<input type="text" class="form-control" name="txtRegimenFiscal" id="txtRegimenFiscal">-->
              <select name="cmbRegimenFiscal" id="cmbRegimenFiscal"></select>
            </div>
            <div class="form-group">
              <label for="txtStreetCompany">Calle*:</label>
              <input type="text" class="form-control" name="txtStreetCompany" id="txtStreetCompany" maxlength="50">
            </div>
            <div class="form-group">
              <label for="txtExteriorCompany">Numero exterior*:</label>
              <input type="text" class="form-control" name="txtExteriorCompany" id="txtExteriorCompany" maxlength="5">
            </div>
            <div class="form-group">
              <label for="txtInteriorCompany">Numero interior:</label>
              <input type="text" class="form-control" name="txtInteriorCompany" id="txtInteriorCompany" maxlength="5">
            </div>
            <div class="form-group">
              <label for="txtNeighborhoodCompany">Colonia*:</label>
              <input type="text" class="form-control" name="txtNeighborhoodCompany" id="txtNeighborhoodCompany" maxlength="70">
            </div>
            <div class="form-group">
              <label for="txtZipCompany">Código postal*:</label>
              <input type="text" class="form-control" name="txtZipCompany" id="txtZipCompany" maxlength="5">
            </div>
            <div class="form-group">
              <label for="txtCityCompany">Ciudad*:</label>
              <input type="text" class="form-control" name="txtCityCompany" id="txtCityCompany" maxlength="50">
            </div>
            <div class="form-group">
              <label for="txtStateCompany">Estado*:</label>
              <select name="cmbStateCompany" id="cmbStateCompany"></select>
            </div>
            <div class="form-group">
              <label for="txtPhoneCompany">Telefono:</label>
              <input type="text" class="form-control" name="txtPhoneCompany" id="txtPhoneCompany" maxlength="8">
            </div>

            <div class="form-group">
              <label for="txtIMSS">Número de registro patronal IMSS:</label>
              <input type="text" class="form-control" name="txtIMSS" id="txtIMSS" maxlength="11">
            </div>
            <div class="form-group">
              <label for="txtIMSS">Serie inicial:</label>
              <input type="text" class="form-control" name="txtSerie" id="txtSerie" maxlength="4">
            </div>
            <div class="form-group">
              <label for="txtFolio">Folio inicial:</label>
              <input type="text" class="form-control" name="txtFolio" id="txtFolio" maxlength="11">
            </div>
            <div class="form-group">
              <label for="fileCer" id="fileCerName" data-toggle="tooltip" data-placement="top" title="Clic para subir archivo .cer">Archivo .cer:</label>
              <input type="file" class="form-control" name="fileCer" id="fileCer" accept=".cer">
              <input id="uploadFileCer" placeholder="No File" disabled="disabled" data-toggle="tooltip" data-placement="top">
              
            </div>
            <div class="form-group">
              <label for="fileKey" id="fileKeyName" data-toggle="tooltip" data-placement="top" title="Clic para subir archivo .key">Archivo .key:</label>
              <input type="file" class="form-control" name="fileKey" id="fileKey" accept=".key">
              <input id="uploadFileKey" placeholder="No File" disabled="disabled" data-toggle="tooltip" data-placement="top">
            </div>
            <div class="form-group">
              <label for="txtPasswordCert">Contraseña certificado:</label>
              <input type="password" class="form-control" name="txtPasswordCert" id="txtPasswordCert" autocomplete="on">
            </div>
            <div class="form-group upload-btn-wrapper">
              <label for="fileLogo" id="fileLogoName" data-toggle="tooltip" data-placement="top" title="Clic para subir archivo el logo">Logo:</label>
              <input type="file" class="form-control" name="fileLogo" id="fileLogo" accept="image/jpg,image/jpeg,image/png">
              <input id="uploadFileLogo" placeholder="No File" disabled="disabled" data-toggle="tooltip" data-placement="top">
            </div>


          </div>
          <div class="modal-footer justify-content-center">
            <button type="button" class="btnesp first espCancelar btnCancelarActualizacion_DatosEmpresa_52" data-dismiss="modal" id="btnCancelarAgregar_DatosEmpresa_52"><span class="ajusteProyecto">Cancelar</span></button>
            <button type="button" class="btnesp espAgregar float-right" name="btnAgregar" id="btnAgregar_DatosEmpresa_52"><span class="ajusteProyecto">Agregar</span></button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- END ADD DATOS EMPRESA -->

  <!-- EDIT DATOS EMPRESA -->
  <div class="modal fade right" id="editar_DatosEmpresa_52" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
    <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">
      <div class="modal-content">
        <form method="POST" id="editarDatosEmpresa" enctype="multipart/form-data">
          <div class="modal-header">
            <h4 class="modal-title w-100" id="myModalLabel">Editar Datos Empresa</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">x</span>
            </button>
          </div>
          <div class="modal-body">
            <input type="hidden" id="txtUpdatePKDatosEmpresa_52" name="txtUpdatePKDatosEmpresa_52">
            <input type="hidden" id="txtUpdateDatosEmpresa_52" name="txtUpdateDatosEmpresa_52">
            <div class="form-group">
              <label for="txtGiroComercial">Giro comercial:</label>
              <input type="text" class="form-control" name="txtUpdateGiroComercial" id="txtUpdateGiroComercial">
            </div>
            <div class="form-group">
              <label for="txtUpdateRegimenFiscal">Régimen fiscal*:</label>
              <select name="cmbUpdateRegimenFiscal" id="cmbUpdateRegimenFiscal"></select>
            </div>
            <div class="form-group">
              <label for="txtUpdateStreetCompany">Calle*:</label>
              <input type="text" class="form-control" name="txtUpdateStreetCompany" id="txtUpdateStreetCompany">
            </div>
            <div class="form-group">
              <label for="txtUpdateExteriorCompany">Numero exterior*:</label>
              <input type="text" class="form-control" name="txtUpdateExteriorCompany" id="txtUpdateExteriorCompany">
            </div>
            <div class="form-group">
              <label for="txtUpdateInteriorCompany">Numero interior:</label>
              <input type="text" class="form-control" name="txtUpdateInteriorCompany" id="txtUpdateInteriorCompany">
            </div>
            <div class="form-group">
              <label for="txtUpdateNeighborhoodCompany">Colonia*:</label>
              <input type="text" class="form-control" name="txtUpdateNeighborhoodCompany" id="txtUpdateNeighborhoodCompany">
            </div>
            <div class="form-group">
              <label for="txtUpdateZipCompany">Código postal*:</label>
              <input type="text" class="form-control" name="txtUpdateZipCompany" id="txtUpdateZipCompany">
            </div>
            <div class="form-group">
              <label for="txtUpdateCityCompany">Ciudad*:</label>
              <input type="text" class="form-control" name="txtUpdateCityCompany" id="txtUpdateCityCompany">
            </div>
            <div class="form-group">
              <label for="txtUpdateStateCompany">Estado*:</label>
              <select name="cmbUpdateStateCompany" id="cmbUpdateStateCompany"></select>
            </div>
            <div class="form-group">
              <label for="txtUpdatePhoneCompany">Telefono:</label>
              <input type="text" class="form-control" name="txtUpdatePhoneCompany" id="txtUpdatePhoneCompany">
            </div>
            <div class="form-group">
              <label for="txtIMSS">Número de registro patronal IMSS:</label>
              <input type="text" class="form-control" name="txtIMSS" id="txtUpdateIMSS">
            </div>
            <div class="form-group">
              <label for="txtIMSS">Serie inicial:</label>
              <input type="text" class="form-control" name="txtSerie" id="txtUpdateSerie">
            </div>
            <div class="form-group">
              <label for="txtFolio">Folio inicial:</label>
              <input type="text" class="form-control" name="txtFolio" id="txtUpdateFolio">
            </div>
            <form >
              <div class="form-group inputFilePadding">
                <label for="fileUpdateCer" id="fileUpdateCerName" data-toggle="tooltip" data-placement="top" title="Clic para resubir archivo .cer">Archivo .cer:</label>
                <input type="file" class="form-control" name="fileCer" id="fileUpdateCer" accept=".cer">
                <input id="uploadFileUpdateCer" placeholder="No hay archivo" disabled="disabled" data-toggle="tooltip" data-placement="top">
              </div>
            </form>
            <div class="form-group inputFilePadding">
              <label for="fileUpdateKey" id="fileUpdateKeyName" data-toggle="tooltip" data-placement="top" title="Clic para resubir archivo .key">Archivo .key:</label>
              <input type="file" class="form-control" name="fileKey" id="fileUpdateKey" accept=".key">
              <input id="uploadFileUpdateKey" placeholder="No hay archivo" disabled="disabled" data-toggle="tooltip" data-placement="top">
            </div>
            <div class="form-group">
              <label for="txtPasswordCert">Contraseña certificado:</label>
              <input type="password" class="form-control" name="txtUpdatePasswordCert" id="txtUpdatePasswordCert" autocomplete="on">
            </div>
            <div class="form-group inputFilePadding">
              <label for="fileUpdateLogo" id="fileUpdateLogoName" data-toggle="tooltip" data-placement="top" title="Clic para resubir archivo el logo">Logo:</label>
              <input type="file" class="form-control" name="fileLogo" id="fileUpdateLogo" accept="image/jpg,image/jpeg,image/png">
              <input id="uploadFileUpdateLogo" placeholder="No hay archivo" disabled="disabled" data-toggle="tooltip" data-placement="top">
            </div>


          </div>
          <div class="modal-footer justify-content-center">
            <button type="button" class="btnesp first espCancelar btnCancelarActualizacion_DatosEmpresa_52" data-dismiss="modal" id="btnCancelarEditar_DatosEmpresa_52"><span class="ajusteProyecto">Cancelar</span></button>
            <div class="permission-view-delete-DatosEmpresa_52"></div>
            <div class="permission-view-edit-DatosEmpresa_52"></div>
            <!--<button type="button" class="btnesp espAgregar float-right" name="btnAgregar" id="btnEditar_DatosEmpresa_52"><span
                class="ajusteProyecto">Editar</span></button>-->
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- END EDIT DATOS EMPRESA -->

  <!--DELETE MODAL SLIDE DATOS EMPRESA-->
  <div class="modal fade" id="eliminar_DatosEmpresa_52" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">

        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">¿Desea eliminar el registro de este?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">x</span>
          </button>
        </div>
        <div>
          <input type="hidden" name="txtPKDatosEmpresa_52D" id="txtPKDatosEmpresa_52D">
          <br>
          <label for="usr" style="margin-left: 80px!important;">Se eliminará el tipo de orden con los siguientes
            datos:</label>
        </div>

        <div class="form-group col-md-3">
          <label for="usr">Nombre:</label>
        </div>
        <div class="form-group col-md-9">
          <input type="text" style="border:none!important;" class="form-control" maxlength="50" id="txtDatosEmpresa_52D" name="txtDatosEmpresa_52D" required readonly>
        </div>

        <div class="modal-body" style="font-size: 10 px!important; color: red;">Esta acción no podrá deshacerse</div>
        <div class="modal-footer">
          <button class="btn-custom btn-custom--border-blue btnCancelarActualizacion" type="button" data-dismiss="modal"><span class="ajusteProyecto" data-toggle="modal" data-target="#eliminar_DatosEmpresa_52">Cancelar</span></button>
          <button type="button" class="btn-custom btn-custom--blue" id="btn_aceptar_eliminar_DatosEmpresa_52"><span class="ajusteProyecto">Eliminar</span></button>
        </div>

      </div>
    </div>
  </div>
  <!--END DELETE MODAL SLIDE DATOS EMPRESA -->

  <!-- IMAGEN LOGO EMPRESA -->
  <!-- <div id="modal-logo" class="modal-logo_empresa">
    <span class="close-logo_empresa"><i class="fas fa-times"></i></span>
    <img class="modal-content-logo_empresa" id="zoom-logo">
    <div id="caption-logo_empresa"></div>
  </div> -->

  <!-- END IMAGEN LOGO EMPRESA -->

  <!------------------------------------------------ END MODALS TIPO ORDEN INVENTARIO --------------------------------------------------------------->

  <!------------------------------------------------------------------------------- MODALS PERSONAL ------------------------------------------------------------------------------>

    <!--ADD MODAL PERSONAL-->
    <div class="modal fade right" id="agregar_Personal_72" tabindex="-1" role="dialog" aria-labelledby="modalAgregarPersonal"
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
                <label for="cmbEstadoPersonal">Estado:*</label>
                <select name="cmbEstadoPersonal" id="cmbEstadoPersonal" onchange="validEmptyInput(this)">
                  <option data-placeholder="true"></option>
                  <?php
                    $stmt = $conn->prepare("SELECT * FROM estados_federativos");
                    $stmt->execute();
                    $row = $stmt->fetchAll();
                    foreach ($row as $r) { //Mostrar roles
                        echo '<option value="' . $r['PKEstado'] . '" >' . $r['Estado'] . '</option>';
                    }
                  ?>
                </select>
                <div class="invalid-feedback" id="invalid-estado">El empleado debe tener un estado.</div>
              </div>
              <div class="form-group">
                <label for="cmbRolesPersonal">Roles:*</label>
                <select name="cmbRolesPersonal" id="cmbRolesPersonal" onchange="validEmptyInput(this)" multiple>
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
    <div class="modal fade right" id="editar_Personal_72" tabindex="-1" role="dialog" aria-labelledby="modalEditarPersonal"
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
                <select name="cmbGeneroU" id="cmbGeneroU" required>
                  <option data-placeholder="true"></option>
                  <option value="Masculino">Masculino</option>
                  <option value="Femenino">Femenino</option>
                </select>
                <div class="invalid-feedback" id="invalid-generoU">El empleado debe tener un género.</div>
              </div>
              <div class="form-group">
                <label for="cmbEstadoPersonalU">Estado:*</label>
                <select name="cmbEstadoPersonalU" id="cmbEstadoPersonalU" onchange="validEmptyInput(this)">
                  <option data-placeholder="true"></option>
                  <?php
                    $stmt = $conn->prepare("SELECT * FROM estados_federativos");
                    $stmt->execute();
                    $row = $stmt->fetchAll();
                    foreach ($row as $r) { //Mostrar roles
                        echo '<option value="' . $r['PKEstado'] . '" >' . $r['Estado'] . '</option>';
                    }
                  ?>
                </select>
                <div class="invalid-feedback" id="invalid-estadoU">El empleado debe tener un estado.</div>
              </div>
              <div class="form-group">
                <label for="cmbRolesPersonalU">Roles:*</label>
                <select name="cmbRolesPersonalU" id="cmbRolesPersonalU" required multiple>
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
                <a class="btnesp first espEliminar" href="#" name="idPersonalD"
                  id="idPersonalD" data-toggle="modal" data-target="#eliminar_Personal_72"><span class="ajusteProyecto">Eliminar
                    empleado</span></a>
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

    <!--DELETE MODAL SLIDE PERSONAL-->
  <div class="modal fade" id="eliminar_Personal_72" tabindex="-1" role="dialog" aria-labelledby="modalEliminarPersonal" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form action="" method="POST">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">¿Desea eliminar este empleado?</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">x</span>
            </button>
            <input type="hidden" name="hddPersonalD" id="hddPersonalD">
          </div>
          <div class="modal-body" style="font-size: 10 px!important; color: red;">Esta acción no podrá deshacerse</div>
          <div class="modal-footer">
            <button class="btnesp first espCancelar btnCancelarActualizacion" type="button" data-dismiss="modal"><span class="ajusteProyecto">Cancelar</span></button>
            <button type="submit" class="btnesp espAgregar float-right" id="btn_aceptar_eliminar_Personal_72"><span class="ajusteProyecto" data-dismiss="modal">Eliminar</span></button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="modal fade" id="redirect_dashboard" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        
        <h4 class="modal-title w-100" id="myModalLabel">Redireccionando</h4>
        
      </div>
      <div class="modal-body">
        <p>No tiene permisos para esta pantalla.<br>Redireccionando al dashboard...</p>
      </div>
    </div>
  </div>
</div>

<!----------------------------------------------------------------------------- ENDS MODALS PERSONAL ----------------------------------------------------------------------------->

<!----------------------------------------------------------------------------- MODALS CLIENTES ----------------------------------------------------------------------------->

<!--ADD MODAL SLIDE CATEGORÍAS CLIENTES-->
<div class="modal fade right" id="agregar_CategoriadeClientes_87" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
  <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">
    <div class="modal-content">
      <form action="" method="POST">
        <input type="hidden" name="idInput" value="">
        <div class="modal-header">
          <h4 class="modal-title w-100" id="myModalLabel">Agregar Categoría Clientes</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">x</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-lg-12">
              <form id="formDatosCategoriaClientes">
                <div class="form-group">
                  <div class="row">
                    <div class="col-lg-12">
                      <label for="usr">Nombre de la categoría</label>
                      <input class="form-control alphaNumeric-only" type="text" name="txtCategoriaCliente" id="txtCategoriaCliente" autofocus="" required="" onkeyup="validarCategoriaClientes()">
                      <div class="invalid-feedback" id="invalid-nombreCatCliente">La categoria debe tener un nombre.
                      </div>
                    </div>
                  </div>
                </div>
                <br>
              </form>
            </div>
          </div>
        </div>
        <div class="modal-footer justify-content-center">
          <button type="button" class="btn-custom btn-custom--border-blue btnCancelarActualizacion" data-dismiss="modal" id="btnCancelarActualizacion"><span class="ajusteProyecto">Cancelar</span></button>
          <button type="submit" class="btn-custom btn-custom--blue" name="btnAgregarCategoriaClientes" id="btnAgregarCategoriaClientes" ><span class="ajusteProyecto" onclick="anadirCategoriaClientes()">Agregar</span></button>
        </div>
      </form>
    </div>
  </div>
</div>
<!--END ADD MODAL SLIDE CATEGORIAS CLIENTES-->

<!--EDIT MODAL SLIDE CATEGORÍAS-->
<div class="modal fade right" id="editar_CategoriadeClientes_87" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
  <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">
    <div class="modal-content">
      <form action="" method="POST">
        <input type='hidden' name="txtPKCategoriaCliente" id="txtPKCategoriaCliente">
        <input type='hidden' name="txtCatClienteActual" id="txtCatClienteActual">
        <div class="modal-header">
          <h4 class="modal-title w-100" id="myModalLabel">Editar Categoría Clientes</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">x</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-lg-12">
              <form id="formDatosCategoriaU">
                <div class="form-group">
                  <div class="row">
                    <div class="col-lg-4">

                    </div>
                    <div class="col-lg-3">
                      <label for="usr">Estatus:*</label>
                    </div>
                    <div class="col-lg-5">
                      <input type="checkbox" id="activeCatCliente" name="activeCatCliente" class="check-custom">
                      <label class="shadow-sm check-custom-label" for="activeCatCliente">
                        <div class="circle"></div>
                        <div class="check-inactivo">Inactivo</div>
                        <div class="check-activo">Activo</div>
                      </label>
                    </div>
                  </div>
                </div>
                <input class="form-control" id="notaEstatusUClientes" name="notaEstatusUClientes" type="hidden" style="color: darkred; background-color: transparent!important; border: none;" value="Nota: Categoría utilizada por un Cliente." readonly>
                <div class="form-group">
                  <div class="row">
                    <div class="col-lg-12">
                      <label for="usr">Nombre de la categoría</label>
                      <input class="form-control alphaNumeric-only" type="text" name="txtCategoriaUclientes" id="txtCategoriaUclientes" autofocus="" required="" onkeyup="validarCategoriaClientesU()">
                      <div class="invalid-feedback" id="invalid-nombreCatClientesEdit">La categoria debe tener un nombre.
                      </div>
                    </div>
                  </div>
                </div>
                <br>
              </form>
            </div>
          </div>
        </div>

        <div class="modal-footer justify-content-center">
          <button type="button" class="btn-custom btn-custom--border-blue btnCancelarActualizacion" data-dismiss="modal" id="btnCancelarActualizacionU"><span class="ajusteProyecto">Cancelar</span>
          </button>
          <a aria-label="Close" class="btn-custom btn-custom--border-blue btnCancelarActualizacion" data-toggle="modal" data-target="#eliminar_CategoriadeClientes_87" name="btnEliminarUClientes" id="btnEliminarUClientes" data-dismiss="modal">
            <span class="ajusteProyecto">Eliminar</span>
          </a>
          <button type="submit" class="btn-custom btn-custom--blue" name="btnEditarCategoriaClientes" id="btnEditarCategoriaClientes" data-dismiss="modal"><span class="ajusteProyecto">Guardar</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
<!--END EDIT MODAL SLIDE CATEGORÍAS-->

<!--DELETE MODAL SLIDE CATEGORÍAS-->
<div class="modal fade" id="eliminar_CategoriadeClientes_87" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="" method="POST">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">¿Eliminar categoria?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">x</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="txtPKCategoriaClienteD" id="txtPKCategoriaClienteD">
          <p>Se eliminará la categoría con los siguientes datos</p>
          <p>Nombre Categoria: <span id="txtCategoriaClientesD"></span></p>
          <p class="text-danger">Esta acción no podrá deshacerse</p>
        </div>
        <div class="modal-footer">
          <button class="btn-custom btn-custom--border-blue btnCancelarActualizacion" type="button" data-dismiss="modal"><span class="ajusteProyecto" data-toggle="modal" data-target="#editar_Categoria" onclick="obtenerIdCategoriaClientesEditar($('#txtPKCategoriaClienteD').val())">Cancelar</span></button>
          <button type="submit" onclick="eliminarCategoriaCliente()" class="btn-custom btn-custom--blue"><span class="ajusteProyecto" data-dismiss="modal">Eliminar</span></button>
        </div>
      </form>
    </div>
  </div>
</div>
<!--END DELETE MODAL SLIDE CATEGORÍAS-->

<!----------------------------------------------------------------------------- ENDS MODALS CLIENTES ----------------------------------------------------------------------------->

  <script>
    /*loadAlertsNoti('alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
  setInterval(loadAlertsNoti, 10000, 'alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());*/
  </script>
  <script src="https://cdn.jsdelivr.net/npm/luxon@2.0.2/build/global/luxon.min.js" integrity="sha256-CnZmNCHHUMy22/PJclCIISZ5Ib4MnUu+7ee5YNxtsZQ=" crossorigin="anonymous"></script>
  <script src="../../js/slimselect.min.js"></script>
  <script src="js/config_tabs.js"></script>
  <script src="js/usuarios.js"></script>
  <script src="js/perfiles.js"></script>
  <script src="js/puestos.js"></script>
  <script src="js/turnos.js"></script>
  <script src="js/categoria_gastos.js"></script>
  <script src="js/subcategorias_gastos.js"></script>
  <script src="js/responsable_gastos.js"></script>
  <script src="js/sucursales.js"></script>
  <script src="js/tipo_ordenInventario.js" charset="utf-8"></script>
  <script src="js/agregar_tipoOrdenInventario.js" charset="utf-8"></script>
  <script src="js/editar_tipoOrdenInventario.js" charset="utf-8"></script>
  <script src="js/eliminar_tipoOrdenInventario.js" charset="utf-8"></script>
  <script src="js/datos_empresas.js"></script>
  <script src="js/editar_marca.js" charset="utf-8"></script>
  <script src="js/agregar_marca.js" charset="utf-8"></script>
  <script src="js/editar_categoria.js" charset="utf-8"></script>
  <script src="js/agregar_categoria.js" charset="utf-8"></script>
  <script src="js/eliminar_categoria.js" charset="utf-8"></script>
  <script src="js/eliminar_marca.js" charset="utf-8"></script>
  <script src="js/personal.js" charset="utf-8"></script>
  <script src="../../js/sb-admin-2.min.js"></script>
  <script src="../../js/scripts.js"></script>
  <script src="../../js/sweet/sweetalert2.js"></script>
  <script src="../../js/lobibox.min.js"></script>
  <script src="../../js/mdtimepicker.min.js"></script>
  <script src="../../js/timepicki.js"></script>
  <script type="text/javascript">
  let token = '<?=$token?>';
  </script>
  <script src="js/parametros.js"></script>
  <script src="js/widgets.js"></script>
</body>

</html>