<?php
$ruta = "../";
$screen = 27;
require_once $ruta . 'validarPermisoPantalla.php';
if ($permiso === 0) {
  header("location:../dashboard.php");
}

$token = $_SESSION['token_ld10d'];

require_once('../../include/db-conn.php');
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
  <title>Timlid | Equipos</title>

  <!-- STYLES -->
  <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../css/slimselect.min.css" rel="stylesheet">
  <link href="../../css/notificaciones.css" rel="stylesheet">
  <link href="../../css/lobibox.min.css" rel="stylesheet">
  <link href="../../css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="../../vendor/datatables/buttons.dataTables.css" rel="stylesheet">
  <link href="../../css/styles.css" rel="stylesheet">
  <link href="../../css/stylesNewTable.css" rel="stylesheet">
  <!-- SCRIPTS -->
  <script src="../../js/notificaciones_timlid.js" charset="utf-8"></script>
  <script src="../../vendor/jquery/jquery.min.js"></script>
  <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../vendor/datatables/dataTables.buttons.js"></script>
  <script src="../../vendor/datatables/buttons.html5.min.js"></script>
  <script src="../../vendor/jszip/jszip.min.js"></script>
  <script src="../../js/slimselect.min.js"></script>
  <script src="../../js/lobibox.min.js"></script>
  <script src="../../js/sweet/sweetalert2.js"></script>

</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <?php
    $icono = '../../img/icons/proveedores.svg';
    $titulo = 'Equipos';

    $ruteEdit = $ruta . "central_notificaciones/";
    require_once '../menu3.php';
    ?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
      <input type="hidden" id="txtUsuario" value="<?= $_SESSION['PKUsuario']; ?>">
      <input type="hidden" id="txtRuta" value="<?= $ruta; ?>">
      <input type="hidden" id="txtEdit" value="<?= $ruteEdit; ?>">
      <input type="hidden" id="txtPantalla" value="<?= $screen; ?>">

      <!-- Main Content -->
      <div id="content">
        <?php
        $rutatb = "../";
        require_once '../topbar.php';
        ?>
        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->

          <!-- DataTales Example -->
          <div class="card mb-4">
            <div class="card-body">
              <div class="table-responsive">
                <table class="table" id="tblEquipos" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Equipo</th>
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
      <?php
      $rutaf = "../";
      require_once '../footer.php';
      ?>
      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->
  <!--Add Slim Modal equipos-->
  <div class="modal fade right" id="agregar_Equipo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

    <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
    <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">


      <div class="modal-content">
        <form action="#" method="POST" id="form-equipos">
          <!--<input type="hidden" name="idProyectoA" value="">-->
          <div class="modal-header">
            <h4 class="modal-title w-100" id="myModalLabel">Agregar Equipo</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label for="usr">Nombre del Equipo:</label>
              <input type="text" class="form-control alpha-only" maxlength="50" name="txtEquipo" id="txtEquipo" required>
            </div>
            <div class="form-group">
              <label for="usr">Miembros del equipo:</label>
              <select name="cmbIdUsuario[]" id="multiple" multiple>
                <?php
                $query = sprintf('SELECT PKEmpleado as PKData, CONCAT(Nombres," ",PrimerApellido," ",SegundoApellido) as Data FROM empleados WHERE empresa_id = ' . $_SESSION['IDEmpresa']);
                $stmt = $conn->prepare($query);
                $stmt->execute();
                $empleado = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($empleado as $emp) {

                  echo "<option value='" . $emp['PKData'] . "'>" . $emp['Data'] . "</option>";
                }
                ?>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <input type="hidden" id="token_4s45us" name="token_4s45us" value="<?= $token; ?>">
            <input type="button" class="btn-custom btn-custom--border-blue" data-dismiss="modal" value="Cancelar" id="btnCancelar">
            <button type="button" class="btn-custom btn-custom--blue" name="btnAgregar" id="btnAgregar">Agregar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!--end slim modal equipos-->


  <!--Slim Update Equipo-->
  <div class="modal fade right" id="editar_Equipo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">
      <div class="modal-content">
        <form action="#" method="POST" id="editarEquipo">
          <input type="hidden" name="idEquipoU" id="idEquipoU">
          <input type="hidden" id="token_4s45us" name="token_4s45us" value="<?= $token; ?>">
          <div class="modal-header">
            <h4 class="modal-title" id="myModalLabel">Editar Equipo</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label for="usr">Nombre del equipo:</label>
              <input type="text" class="form-control alpha-only" id="txtEquipoU" maxlength="50" name="txtEquipo">
            </div>
            <div class="form-group">
              <label for="usr">Miembros del equipo:</label>
              <select name="cmbIdUsuarioU[]" id="multipleU" multiple>
              </select>
            </div>
          </div>
          <div class="modal-footer justify-content-end">
            <a class="btn-custom btn-custom--border-blue" name="btnEliminarEquipo" id="btnEliminarEquipo" href="#"><i class="fas fa-trash-alt"></i> Eliminar Equipo</a>
            <button type="button" class="btn-custom btn-custom--blue" name="btnEditar" id="btnEditar">Guardar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!--End Slim Update Equipo-->

  <script src="../../js/sb-admin-2.min.js"></script>
  <script src="../../js/scripts.js"></script>
  <script src="js/equipos.js"></script>
</body>

</html>