<?php
session_start();

if (isset($_SESSION["Usuario"])) {
  require_once '../../include/db-conn.php';
  $user = $_SESSION["Usuario"];
} else {
  header("location:../dashboard.php");
}

$nodo = "";
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="icon" type="image/png" href="../../img/header/bluTimlid.png">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Timlid | Organigrama</title>

  <!-- ESTILOS -->
  <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="../../vendor/datatables/buttons.dataTables.css" rel="stylesheet">
  <link href="../../css/stylesNewTable.css" rel="stylesheet">
  <link href="../../css/lobibox.min.css" rel="stylesheet">
  <link href="../../css/croppie.css" rel="stylesheet">
  <link href="../../css/slimselect.min.css" rel="stylesheet">
  <link href="../../css/styles.css" rel="stylesheet">
  <!-- JS -->
  <script src="../../vendor/jquery/jquery.min.js"></script>
  <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../../js/sb-admin-2.min.js"></script>
  <script src="../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../vendor/datatables/dataTables.buttons.js"></script>
  <script src="../../vendor/datatables/buttons.html5.min.js"></script>
  <script src="../../js/sweet/sweetalert2.js"></script>
  <script src="../../vendor/jszip/jszip.min.js"></script>
  <script src="../../js/lobibox.min.js"></script>
  <script src="../../js/croppie.js"></script>
  <script src="../../js/slimselect.min.js"></script>
</head>

<body id="page-top" class="sidebar-toggled">
  <!-- Page Wrapper -->
  <div id="wrapper">
    <!-- Sidebar -->
    <?php
    $ruta = "../";
    $ruteEdit = $ruta . "central_notificaciones/";
    require_once '../menu3.php';
    ?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <?php
        $icono = '../../img/icons/ICONO ORGANIGRAMA-01.svg';
        $titulo = "Organigrama";
        $rutatb = "../";

        require_once '../topbar.php';
        ?>

        <!-- Begin Page Content -->
        <div class="container-fluid">
          <!-- DataTales Example -->
          <div class="card shadow mb-4">
            <!-- <div class="card-header py-3">
              <div class="float-right">
                <div class="button-container2" id="">
                  <div class="button-icon-container">
                    <a href="#" data-toggle="modal" data-target="#agregar_Organigrama" class="btn btn-circle float-right waves-effect waves-light shadow btn-table" onclick="obtenerDatosOrganigrama();">
                      <i class="fas fa-plus"></i></a>
                  </div>
                  <div class="button-text-container">
                    <span>Agregar al organigrama</span>
                  </div>
                </div>
              </div>
              <a href="organigrama.php" class="btn btn-info float-right" style="position: relative; right: 20px;"><i class="fas fa-project-diagram"></i> Ver organigrama</a>
            </div> -->
            <div class="card-body">
              <div class="table-responsive">
                <table class="table" id="tblOrganigrama" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Empleado</th>
                      <th>Jefe Inmediato</th>
                      <th>Puesto</th>
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

  <!-- Modal datos agregar registro organigrama -->
  <div id="agregar_Organigrama" class="modal fade right" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-full-height modal-right modal-md" role="document">
      <div class="modal-content">
        <form enctype="multipart/form-data">
          <div class="modal-header">
            <h4 class="modal-title w-100" id="myModalLabel">Agregar organigrama</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">x</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label for="nombre-empleado">Nombre del empleado:</label>
              <select class="form-select" name="cmbIdEmpleado" id="cmbIdEmpleado">
              </select>
              <div class="invalid-feedback" id="invalid-nombreComEmp">El empleado es requerido.</div>
            </div>
            <div class="form-group" id="form-group-jefe">
              <label for="jefe-inmediato">Jefe inmediato:</label>
              <select class="form-select" name="cmbIdNodo" id="cmbIdNodo">
              </select>
              <div class="invalid-feedback" id="invalid-jefe">El jefe es requerido.</div>
            </div>
            <div class="form-group">
              <label for="puesto">Puesto:</label>
              <input type="text" class="form-control" id="puesto" name="puesto" disabled>
            </div>
            <div class="form-group">
              <label for="usr">Foto:</label>
              <input type="file" name="img_perfil" id="img_perfil" class="form-control" accept="image/*"><br>
              <div id="uploaded_image"></div>
              <div class="invalid-feedback" id="invalid-foto">La foto es requerida.</div>
            </div>
          </div>
          <div class="modal-footer justify-content-center">
            <button type="button" class="btn-custom btn-custom--border-blue btnCancelarActualizacion" data-dismiss="modal" id="btnCancelar">
              <span>Cancelar</span>
            </button>
            <button type="button" class="btn-custom btn-custom--blue float-right" name="btnGuardar" id="guardarOrganigrama">
              <span>Agregar</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal datos editar registro organigrama -->
  <div id="editar_Organigrama" class="modal fade right" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-full-height modal-right modal-md" role="document">
      <div class="modal-content">
        <form enctype="multipart/form-data" id="form-edit-organigrama">
          <input type="hidden" name="id-organigrama" id="id-organigrama">
          <input type="hidden" name="id-empleado-original" id="id-empleado-original">
          <div class="modal-header">
            <h4 class="modal-title w-100" id="myModalLabel">Editar organigrama</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">x</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label for="nombre-empleado-edit">Nombre del empleado:</label>
              <select class="form-select" name="nombre-empleado-edit" id="nombre-empleado-edit">
              </select>
              <div class="invalid-feedback" id="invalid-nombreComEmpEdit">El empleado es requerido.</div>
            </div>
            <div class="form-group" id="form-group-jefe">
              <label for="jefe-inmediato-edit">Jefe inmediato:</label>
              <select class="form-control" name="jefe-inmediato-edit" id="jefe-inmediato-edit">
              </select>
            </div>
            <div class="form-group">
              <label for="puesto-edit">Puesto:</label>
              <input type="text" class="form-control" id="puesto-edit" name="puesto-edit" disabled>
            </div>
            <div class="form-group">
              <label for="usr">Foto:</label>
              <input type="file" name="img_perfilEditar" id="img_perfilEditar" class="form-control" accept="image/*"><br>
              <div id="uploaded_imageEditar"></div>
            </div>
          </div>
          <div class="modal-footer justify-content-center">
            <button type="button" class="btn-custom btn-custom--border-blue btnCancelarActualizacion" data-dismiss="modal" id="btnCancelar">
              <span>Cancelar</span>
            </button>
            <button type="button" class="btn-custom btn-custom--border-blue" name="eliminarOrganigrama" id="eliminarOrganigrama" data-toggle="modal" data-target="">
              <span>Eliminar</span>
            </button>
            <button type="button" class="btn-custom btn-custom--blue float-right" name="modificarOrganigrama" id="modificarOrganigrama">
              <span>Modificar</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal foto agregar-->
  <div id="uploadimageModal" class="modal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content" align="center">
        <div class="modal-header">
          <h4 class="modal-title">Carga y escoge tu imagen</h4>
        </div>
        <div class="modal-body">
          <div class="row" align="center">
            <div class="col-md-12">
              <div id="image_demo" style="width:80%; margin-top:15px"></div>
            </div>
            <div class="col-md-12" style="padding-top:10px;">
              <button class="btn-custom btn-custom--blue crop_image" type="button">Subir imagen</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal foto Editar -->
  <div id="uploadimageModalEditar" class="modal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content" align="center">
        <div class="modal-header">
          <h4 class="modal-title">Carga y escoge tu imagen</h4>
        </div>
        <div class="modal-body">
          <div class="row" align="center">
            <div class="col-md-12">
              <div id="image_demo_editar" style="width:80%; margin-top:15px"></div>
            </div>
            <div class="col-md-12" style="padding-top:10px;">
              <button class="btn-custom btn-custom--blue crop_imageEditar" type="button">Subir imagen</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script>
    var ruta = "../";
  </script>
  <script src="../../js/sb-admin-2.min.js"></script>
  <script src="../../js/scripts.js"></script>
  <script src="./js/organigrama.js"></script>
</body>

</html>