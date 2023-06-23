<?php
  session_start();

  if (isset($_SESSION["Usuario"])) {
    
    $user = $_SESSION["Usuario"];
    $pkusuario = $_SESSION["PKUsuario"];
    $ruta = "../";
    $screen = 25;
    
    require_once $ruta . 'validarPermisoPantalla.php';
    if($permiso === 0){
      header("location:../dashboard.php");
    }
  } else {
      header("location:../dashboard.php");
  }

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <link rel="icon" type="image/png" href="../../img/header/bluTimlid.png">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Timlid | Almacenes</title>

  <!-- Bootstrap core JavaScript-->
  <script src="../../vendor/jquery/jquery.min.js"></script>
  <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../js/validaciones.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  
  <script src="../../js/sweet/sweetalert2.js"></script>

  <!-- Page level plugins -->
  <script src="../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../vendor/datatables/dataTables.bootstrap4.min.js"></script>
  <script src="../../vendor/datatables/dataTables.buttons.js"></script>
  <script src="../../vendor/datatables/buttons.bootstrap4.min.js"></script>
  <script src="../../vendor/datatables/buttons.html5.min.js"></script>
  <script src="../../vendor/jszip/jszip.min.js"></script>
  <script src="../../js/slimselect.min.js"></script>

  <!-- Custom fonts for this template -->
  <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet">

  <!-- Custom styles for this page -->
  <link href="../../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../css/styles.css" rel="stylesheet">
  <link href="../../css/stylesTable.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">

  <link href="../../css/lobibox.min.css" rel="stylesheet">
  <link href="../../css/slimselect.min.css" rel="stylesheet">
  <script src="../../js/lobibox.min.js"></script>

  <link rel="stylesheet" href="../../css/notificaciones.css">
  <script src="../../js/notificaciones_timlid.js" charset="utf-8"></script>

  
</head>

<body id="page-top" class="sidebar-toggled">

  <!-- Page Wrapper -->
  <div id="wrapper">
    <?php
      $icono = '../../img/icons/inventarios.svg';
      $titulo = '<div class="header-screen d-flex align-items-center">
                  <div class="header-title-screen">
                    <h1 class="h3">Almacenes </h1>
                  </div>
                </div>';
      
      $ruteEdit = $ruta . "central_notificaciones/";
      require_once '../menu3.php';
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
          $rutatb = '../';
          require_once '../topbar.php';
        ?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <!--<div class="divPageTitle">
            <img src="../../img/icons/inventarios.svg" width="45px" style="position:relative;top:-10px;">
            <label class="lblPageTitle">&nbsp;Almacenes</label>
          </div>-->

          <!-- DataTales Example -->
          <div class="card mb-4">
            <div class="card-header py-3">
              <div class="float-right">
                <div class="button-container2" id="">
                  <div class="button-icon-container">
                    <a href="#" class="btn btn-circle float-right waves-effect waves-light shadow btn-table"
                      id="btn-almacenes"><i
                        class="fas fa-plus"></i></a>
                  </div>
                  <div class="button-text-container">
                    <span>Agregar almacén</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table" id="tblAlmacenes" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>id</th>
                      <th>Almacén</th>
                      <th>Domicilio</th>
                      <th>Colonia</th>
                      <th>Ciudad</th>
                      <th>Estado</th>
                      <th>País</th>
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
        $rutaf = "../";
        require_once '../footer.php';
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

  <div class="modal fade right" id="agregar_Almacen" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">

    <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
    <div class="modal-dialog modal-full-height modal-right modal-md" role="document">

      <div class="modal-content">
        <form action="" id="agregarAlmacen" method="POST">
          <!--<input type="hidden" name="idProyectoA" value="">-->
          <div class="modal-header">
            <h4 class="modal-title w-100" id="myModalLabel">Agregar almacén</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">

            <div class="form-group">
              <label for="usr">Nombre de almacen:</label>
              <input type="text" id="txtarea" class="form-control alpha-only" maxlength="40" name="txtAlmacen" required>
            </div>
            <div class="form-group">
              <label for="usr">Calle:</label>
              <input type="text" id="txtarea2" class="form-control alpha-only" name="txtCalle" maxlength="50" required>
            </div>
            <div class="form-group">
              <label for="usr">Número exterior:</label>
              <input type="text" id="txtarea3" class="form-control alphaNumeric-only" name="txtNe">
            </div>
            <div class="form-group">
              <label for="usr">Número interior:</label>
              <div class="input-group">
                <select name="cmbPrefijo" class="form-control" placeholder="hola" id="txtarea9">
                  <option value="" disabled selected hidden>Seleccionar</option>
                  <option value="Interior">Interior</option>
                  <option value="Bodega">Bodega</option>
                  <option value="Piso">Piso</option>
                  <option value="Departamento">Departamento</option>
                </select>
                <input type="text" id="txtarea4" style="width:30%;" class="form-control alphaNumeric-only"
                  style="text-transform: uppercase" name="txtNi">
              </div>
            </div>
            <div class="form-group">
              <label for="usr">Colonia:</label>
              <input type="text" id="txtarea5" class="form-control alpha-only" size="40" name="txtColonia" required>
            </div>
            <div class="form-group">
              <label for="usr">Ciudad:</label>
              <input type="text" id="txtarea7" class="form-control alphaNumeric-only" size="40" name="txtMunicipio"
                required>
            </div>
            <div class="form-group">
              <label for="usr">País:</label>
              <select name="cmbPais" class="form-control" id="cmbPais" required></select>
            </div>
            <div class="form-group">
              <label for="usr">Estado:</label>
              <select name="cmbEstados" class="form-control" placeholder="hola" id="cmbEstados" required></select>
              
            </div>

          </div>
          <!--<div class="form-group">
                  <label for="usr">Teléfono:</label>
                  <input type="text" id="txtarea10" maxlength="10" class="form-control numeric-only" name="txtTelefono" required>
              </div>-->
          <div class="modal-footer justify-content-center">
            <button type="button" class="btnesp first espCancelar btnCancelarActualizacion" data-dismiss="modal"
              id="btnCancelarActualizacion"><span class="ajusteProyecto">Cancelar</span></button>
            <button type="button" class="btnesp espAgregar float-right" name="btnAgregar" id="btnAgregarAlmacen"><span
                class="ajusteProyecto">Agregar</span></button>
          </div>
      </div>
      </form>
    </div>
  </div>
  </div>
  </div>
  <!-- End Add Fluid Modal mis proyectos -->
  <!--UPDATE MODAL DENTRO DE PROYECTOS 04/09/2020-->
  <div class="modal fade right" id="modalEditar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-full-height modal-right modal-md" role="document">
      <div class="modal-content">
        <form action="" id="editarAlmacenU" method="POST">
          <input type="hidden" name="idAlmacenU" id="idAlmacenU">
          <div class="modal-header">
            <h4 class="modal-title w-100" id="myModalLabel">Editar almacén</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label for="usr">Almacén:</label>
              <input type="text" id="txtAlmacenU" class="form-control alpha-only" maxlength="40" name="txtAlmacenU"
                value="" required>
            </div>
            <div class="form-group">
              <label for="usr">Calle:</label>
              <input type="text" id="txtCalleU" class="form-control " maxlength="40" name="txtCalleU" required>
            </div>
            <div class="form-group">
              <label for="usr">Numero exterior:</label>
              <input type="text" id="txtNeU" class="form-control alphaNumeric-only" name="txtNeU" required>
            </div>
            <div class="form-group">
              <label for="usr">Numero interior:</label>
              <div class="input-group">
                <select name="cmbPrefijoU" class="form-control" placeholder="" id="txtarea9u">
                  <!--<option value="">Seleccionar</option>-->
                  <option value="Interior">Interior</option>
                  <option value="Bodega">Bodega</option>
                  <option value="Piso">Piso</option>
                  <option value="Departamento">Departamento</option>
                </select>
                <input type="text" id="txtarea4u" style="width:40%;" class="form-control alphaNumeric-only"
                  name="txtNiU">
              </div>
            </div>
            <div class="form-group">
              <label for="usr">Colonia:</label>
              <input type="text" id="txtarea5u" class="form-control alpha-only" size="40" name="txtColoniaU" required>
            </div>
            <div class="form-group">
              <label for="usr">Ciudad:</label>
              <input type="text" id="txtarea7u" class="form-control alphaNumeric-only" size="40" name="txtMunicipioU"
                required>
            </div>
            <div class="form-group">
              <label for="usr">País:</label>
              <select name="cmbPaisU" class="form-control" id="cmbPaisU" required></select>
            </div>
            <div class="form-group">
              <label for="usr">Estado:</label>
              <select name="cmbEstadosU" class="form-control" id="cmbEstadosU" required></select>
            </div>

            <div class="modal-footer justify-content-center">
              <a class="btnesp first espEliminar" href="#" onclick="eliminarAlmacen(this.value);" name="idAlmacenD"
                id="idAlmacenD" data-toggle="modal" data-target=""><span class="ajusteProyecto">Eliminar
                  almacén</span></a>
              <button type="button" class="btnesp first espCancelar btnCancelarActualizacion" data-dismiss="modal"
                0id="btnCancelarActualizacion"><span class="ajusteProyecto">Cancelar</span></button>
              <!--<input type="submit" class="btn btn-primary float-right" name="btnEditar" id="btnEditar" value="Guardar">-->
              <button type="button" class="btnesp espAgregar float-right" name="btnEditar" id="btnEditarAlmacen"
                value=""><span class="ajusteProyecto">Guardar</span></button>
            </div>
        </form>
      </div>
    </div>
  </div>
  <!--END UPDATE MODAL DENTRO DE PROYECTOS-->


  
  <script src="../../js/sb-admin-2.min.js"></script>
  <script src="../../js/scripts.js"></script>
  <script src="js/almacenes.js"></script>



</body>

</html>