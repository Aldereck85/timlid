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
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="icon" type="image/png" href="../../../../img/header/bluTimlid.png">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Timlid | Categorías de productos</title>

  <!-- ESTILOS -->
  <link href="../../../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../../../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../../../css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="../../../..//vendor/datatables/responsive.dataTables.css" rel="stylesheet">
  <link href="../../../../vendor/datatables/buttons.dataTables.css" rel="stylesheet">
  <link href="../../../../css/sweetalert2.css" rel="stylesheet">
  <link href="../../../../css/notificaciones.css" rel="stylesheet">
  <link href="../../../../css/lobibox.min.css" rel="stylesheet">
  <link href="../../../../css/styles.css" rel="stylesheet">
  <link href="../../../../css/stylesNewTable.css" rel="stylesheet">
  <!-- JS -->
  <script src="../../../../vendor/jquery/jquery.min.js"></script>
  <script src="../../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../../../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../../../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../../../vendor/datatables/dataTables.buttons.js"></script>
  <script src="../../../../vendor/datatables/buttons.html5.min.js"></script>
  <script src="../../../../vendor/jszip/jszip.min.js"></script>
  <script src="../../../../js/sweet/sweetalert2.js"></script>
  <script src="../../../../js/lobibox.min.js"></script>
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
        $icono = '../../../../img/icons/ICONO CATEGORIAS DE PRODUCTO-01.svg';
        $titulo = 'Lista de Categorías';
        require_once $rutatb . 'topbar.php';
        ?>
        <input type="hidden" id="txtUsuario" value="<?= $_SESSION['PKUsuario']; ?>">
        <input type="hidden" id="txtRuta" value="<?= $ruta; ?>">
        <input type="hidden" id="txtEdit" value="<?= $ruteEdit; ?>">
        <!-- Begin Page Content -->
        <div class="container-fluid">
          <!-- DataTales Example -->
          <div class="card mb-4">
            <div class="card-body">
              <div class="table-responsive">
                <div class="button-container2 float-right">
                </div>
                <table class="table" id="tblListadoCategorias" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Id</th>
                      <th>Categoría</th>
                      <th>Estatus</th>
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

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!--ADD MODAL SLIDE CATEGORÍAS-->
<div class="modal fade right" id="agregar_Categoria" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
          <button type="submit" class="btn-custom btn-custom--blue" name="btnAgregarCategoria" id="btnAgregarCategoria"><span class="ajusteProyecto" onclick="anadirCategoria()">Agregar</span></button>
        </div>
      </form>
    </div>
  </div>
</div>
<!--END ADD MODAL SLIDE CATEGORÍAS-->


<!--EDIT MODAL SLIDE CATEGORÍAS-->
<div class="modal fade right" id="editar_Categoria" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
          <a aria-label="Close" class="btn-custom btn-custom--border-blue btnCancelarActualizacion" data-toggle="modal" data-target="#eliminar_Categoria" name="btnEliminarU" id="btnEliminarU" data-dismiss="modal">
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
<div class="modal fade" id="eliminar_Categoria" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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

  <script src="../../../../js/sb-admin-2.min.js"></script>
  <script src="../../../../js/scripts.js"></script>
  <script src="../../../../js/notificaciones_timlid.js" charset="utf-8"></script>
  <script src="../../js/categorias.js" charset="utf-8"></script>
  <script src="../../js/agregar_categoria.js" charset="utf-8"></script>
  <script src="../../js/editar_categoria.js" charset="utf-8"></script>
  <script src="../../js/eliminar_categoria.js" charset="utf-8"></script>
  <script>
    loadAlertsNoti('alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
    setInterval(loadAlertsNoti, 10000, 'alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
  </script>
</body>

</html>