<?php
session_start();
require_once '../../../../include/db-conn.php';
$user = $_SESSION["Usuario"];
  if (isset($_SESSION["Usuario"])) {
      require_once '../../../../include/db-conn.php';
      $user = $_SESSION["Usuario"];
  } else {
      header("location:../../../dashboard.php");
  }
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <link rel="icon" type="image/png" href="../../../../img/header/bluTimlid.png">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Timlid | Vendedores</title>

<!-- Bootstrap core JavaScript-->
<script src="../../../../vendor/jquery/jquery.min.js"></script>
  <script src="../../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="../../../../vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->

  <script src="../../../../js/sb-admin-2.min.js"></script>
  <script src="../../../../js/scripts.js"></script>

  <!-- Page level plugins -->
  <script src="../../../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../../../vendor/datatables/dataTables.bootstrap4.min.js"></script>
  <script src="../../../../vendor/datatables/dataTables.buttons.js"></script>
  <script src="../../../../vendor/datatables/buttons.bootstrap4.min.js"></script>
  <script src="../../../../vendor/datatables/buttons.html5.min.js"></script>
  <script src="../../../../vendor/jszip/jszip.min.js"></script>

  <!-- Custom fonts for this template -->
  <link href="../../../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="../../../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../../../css/styles.css" rel="stylesheet">

  <!-- Custom styles for this page -->
  <link href="../../../../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
  <link href="../../../..//vendor/datatables/responsive.dataTables.css" rel="stylesheet">
  <link rel="stylesheet" href="../../../../vendor/datatables/buttons.dataTables.css">
  <link href="../../../../vendor/datatables/buttons.bootstrap4.min.css" rel="stylesheet">
  <link href="../../../../css/stylesTable.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">

  <script src="../../../../js/sweet/sweetalert2.js"></script>
  <link rel="stylesheet" href="../../../../css/sweetalert2.css">
  <link rel="stylesheet" href="../../../../css/notificaciones.css">

  <script src="../../../../js/notificaciones_timlid.js" charset="utf-8"></script>
  <script src="../../js/categorias.js" charset="utf-8"></script>
  <script src="../../js/agregar_categoria.js" charset="utf-8"></script>
  <script src="../../js/editar_categoria.js" charset="utf-8"></script>
  <script src="../../js/eliminar_categoria.js" charset="utf-8"></script>
</head>

<body id="page-top" class="sidebar-toggled">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <?php
    $icono = '../../../../img/menu/ICONO CLIENTES_Mesa de trabajo 1.svg';
    $titulo = '<div class="header-screen d-flex align-items-center">
                <div class="header-title-screen">
                  <h1 class="h3 mb-2">Vendedores</h1>
                </div>
              </div>';
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
        require_once $rutatb . 'topbar.php';
        ?>
        <input type="hidden" id="txtUsuario" value="<?=$_SESSION['PKUsuario'];?>">
        <input type="hidden" id="txtRuta" value="<?=$ruta;?>">
        <input type="hidden" id="txtEdit" value="<?=$ruteEdit;?>">
        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <!--<div class="divPageTitle" style="margin-left:10px;">
            <img src="../../../../img/inventarios/ICONO INVENTARIOS Y PROD_Mesa de trabajo 1.svg" width="45px"
              style="position:relative;top:-10px;">
            <label class="lblPageTitle" style="margin-left:10px;font-weight:bold;">Lista de Categorías</label>
          </div>-->

          <!-- DataTales Example -->
          <div class="card mb-4">
            <div class="card-header py-3">
              <div class="float-right">
                <div class="button-container2" id="">
                  <div class="button-icon-container">
                    <a href="" class="btn btn-circle float-right waves-effect waves-light shadow btn-table"
                      id="btn-agregarCat" data-toggle="modal" data-target="#agregar_Categoria"><i
                        class="fas fa-plus"></i></a>
                  </div>
                  <div class="button-text-container">
                    <span>Agregar categoría</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table" id="tblListadoCategorias" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Id</th>
                      <th>Categoría</th>
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

      <div class="row" style="margin-left: 10%;">
        <div class="cuadrado1 col-xl-1 col-lg-1 col-md-1 col-sm-1 col-xs-1">

        </div>
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-xs-6">
          Vendedor activo
        </div>

        <div class="cuadrado4 col-xl-1 col-lg-1 col-md-1 col-sm-1 col-xs-1">

        </div>
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-xs-6">
          Vendedor inactivo
        </div>
      </div>

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


</body>
  <script>
    loadAlertsNoti('alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
    setInterval(loadAlertsNoti, 10000, 'alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
  </script>
</html>

<!--ADD MODAL SLIDE CATEGORÍAS-->
<div class="modal fade right" id="agregar_Categoria" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
  aria-hidden="true">
  <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
  <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">
    <div class="modal-content">
      <form action="" method="POST">
        <input type="hidden" name="idInput" value="">
        <div class="modal-header">
          <h4 class="modal-title w-100" id="myModalLabel">Agregar Categoría</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
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
                      <input class="form-control alphaNumeric-only" type="text" name="txtCategoria" id="txtCategoria"
                        autofocus="" required="" placeholder="Ej. Bata" onkeyup="validarCategoria()">
                      <input class="form-control" id="notaCategoria" name="notaCategoria" type="hidden"
                        style="color: darkred; background-color: transparent!important; border: none;"
                        value="Nota: La categoría ya existe." readonly>
                    </div>
                  </div>
                </div>
                <br>
              </form>
            </div>
          </div>
        </div>
        <div class="modal-footer justify-content-center">
          <button type="button" class="btnesp first espCancelar btnCancelarActualizacion" data-dismiss="modal"
            id="btnCancelarActualizacion"><span class="ajusteProyecto">Cancelar</span></button>
          <button type="submit" class="btnesp espAgregar float-right" name="btnAgregarCategoria"
            id="btnAgregarCategoria"><span class="ajusteProyecto" data-dismiss="modal"
              onclick="anadirCategoria()">Agregar</span></button>
        </div>
      </form>
    </div>
  </div>
</div>
<!--END ADD MODAL SLIDE CATEGORÍAS-->


<!--EDIT MODAL SLIDE CATEGORÍAS-->
<div class="modal fade right" id="editar_Categoria" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
  aria-hidden="true">
  <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
  <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">
    <div class="modal-content">
      <form action="" method="POST">
        <input type='hidden' name="txtPKCategoria" id="txtPKCategoria">
        <input type='hidden' name="txtCatActual" id="txtCatActual">
        <div class="modal-header">
          <h4 class="modal-title w-100" id="myModalLabel">Editar Categoría</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
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
                      <select class="form-control" name="cmbEstatusCategoria" id="cmbEstatusCategoria" required=""
                        onchange="cambiarColor()">
                      </select>
                    </div>
                  </div>
                </div>
                <input class="form-control" id="notaEstatusU" name="notaEstatusU" type="hidden"
                  style="color: darkred; background-color: transparent!important; border: none;"
                  value="Nota: Categoría utilizada por un producto." readonly>
                <div class="form-group">
                  <div class="row">
                    <div class="col-lg-12">
                      <label for="usr">Nombre de la categoría</label>
                      <input class="form-control alphaNumeric-only" type="text" name="txtCategoriaU" id="txtCategoriaU"
                        autofocus="" required="" placeholder="Ej. Bata" onkeyup="validarCategoriaU()">
                      <input class="form-control" id="notaCategoriaU" name="notaCategoriaU" type="hidden"
                        style="color: darkred; background-color: transparent!important; border: none;"
                        value="Nota: La categoría ya existe." readonly>
                    </div>
                  </div>
                </div>
                <br>
              </form>
            </div>
          </div>
        </div>

      <div class="modal-footer justify-content-center">
      <a aria-label="Close" class="btnesp first espEliminar btnCancelarActualizacion"
               data-toggle="modal" data-target="#eliminar_Categoria"
              name="btnEliminarU" id="btnEliminarU" data-dismiss="modal"><span class="ajusteProyecto">Eliminar</span></a>
            <button type="button" class="btnesp first espCancelar btnCancelarActualizacion" data-dismiss="modal"
              id="btnCancelarActualizacionU"><span class="ajusteProyecto">Cancelar</span></button>
            <button type="submit" class="btnesp espAgregar float-right" name="btnEditarCategoria" id="btnEditarCategoria"><span
                class="ajusteProyecto" data-dismiss="modal">Guardar</span></button>
      </div>
    </form>
  </div>
</div>
</div>
<!--END EDIT MODAL SLIDE CATEGORÍAS-->

<!--DELETE MODAL SLIDE CATEGORÍAS-->
<div class="modal fade" id="eliminar_Categoria" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="" method="POST">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">¿Desea eliminar el registro de este?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">x</span>
          </button>
        </div>
        <div>
          <input type="hidden" name="txtPKCategoriaD" id="txtPKCategoriaD">
          <br>
          <label for="usr" style="margin-left: 80px!important;">Se eliminará la categoría con los siguientes
            datos:</label>
        </div>

          <div class="form-group col-md-3">
            <label for="usr">Nombre:</label>
          </div>
          <div class="form-group col-md-9">
            <input type="text" style="border:none!important;"
              class="form-control" maxlength="50" id="txtCategoriaD" name="txtCategoriaD" required
              readonly>
          </div>

        <div class="modal-body" style="font-size: 10 px!important; color: red;">Esta acción no podrá deshacerse</div>
        <div class="modal-footer">
          <button class="btnesp first espCancelar btnCancelarActualizacion" type="button" data-dismiss="modal"><span
              class="ajusteProyecto" data-toggle="modal" data-target="#editar_Categoria" onclick="obtenerIdCategoriaEditar($('#txtPKCategoriaD').val())">Cancelar</span></button>
          <button type="submit" onclick="eliminarCategoria()" class="btnesp espAgregar float-right"><span
              class="ajusteProyecto" data-dismiss="modal">Eliminar</span></button>
        </div>
      </form>
    </div>
  </div>
</div>
<!--END DELETE MODAL SLIDE CATEGORÍAS-->