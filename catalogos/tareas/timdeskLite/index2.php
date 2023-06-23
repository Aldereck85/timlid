<?php
date_default_timezone_set('America/Mexico_City');
session_start();
if (isset($_SESSION["Usuario"])) {
  $user = $_SESSION["Usuario"];
  $pkusuario = $_SESSION["PKUsuario"];
  $ruta = "../../../";
  $screen = 8;
  require_once $ruta . 'include/db-conn.php';
} else {
  header("location:../../dashboard.php");
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="icon" type="image/png" href="../../../img/header/bluTimlid.png">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Timlid | Tareas</title>

  <!-- ESTILOS -->
  <link href="../../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../../css/sweetalert2.css" rel="stylesheet">
  <link href="../../../css/slimselect.min.css" rel="stylesheet">
  <link href="../../../css/styles.css" rel="stylesheet">
  <link href="../../../css/stylesNewTable.css" rel="stylesheet">
  <!-- JS-->
  <script src="../../../vendor/jquery/jquery.min.js"></script>
  <script src="../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../../../js/sb-admin-2.min.js"></script>
  <script src="../../../js/sweet/sweetalert2.js"></script>
</head>

<body id="page-top" class="sidebar-toggled">
  <!-- Page Wrapper -->
  <div id="wrapper">
    <!-- Sidebar -->
    <?php
    $ruta = "../../";
    $ruteEdit = $ruta . "central_notificaciones/";
    require_once $ruta . 'menu3.php';
    ?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
      <input type="hidden" id="txtUsuario" value="<?= $_SESSION['PKUsuario']; ?>">
      <input type="hidden" id="txtRuta" value="<?= $ruta; ?>">
      <input type="hidden" id="txtEdit" value="<?= $ruteEdit; ?>">
      <input type="hidden" id="txtPantalla" value="13">

      <!-- Main Content -->
      <div id="content">

        <?php
        $rutatb = "../../";
        $icono = '../../../img/icons/ICONO ORDENES DE COMPRA-01.svg';
        $titulo = 'Tareas';
        require_once $rutatb . 'topbar.php';
        ?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->

          <!-- DataTales Example -->
          <div class="card mb-4 border-0 shadow">
            <div class="card-body">
              <!-- <div class="container-fluid">
                <div class="row mb-3">
                  <div class="col-sm-12 col-md-9 p-0 d-flex align-items-center">
                    <div class="dt-buttons">
                      <button class="btn-table-custom btn-table-custom--blue" tabindex="0" aria-controls="tblCotizacion" type="button"><i class="fas fa-plus-square"></i> Añadir registro</button>
                    </div>
                  </div>
                  <div class="col-sm-12 col-md-3 p-0 d-flex justify-content-end">
                    <div id="tblCotizacion_filter" class="dataTables_filter"><label><img src="../../../img/timdesk/buscar.svg" width="20px"><input type="search" class="" placeholder="Buscar..." aria-controls="tblCotizacion"></label></div>
                  </div>
                </div>
              </div> -->
              <div class="container-fluid">
                <div class="row">
                  <div class="col-12 col-md-4">
                    <div class="card card-tarea shadow">
                      <div class="card-header">Por hacer</div>
                      <div class="card-body" id="todo">
                        <div class="card shadow mb-3 p-3 move-drag tarea">
                          <div class="tarea__header">
                            <div class="d-flex justify-content-between">
                              <div class="tarea__etiquetas text-muted mb-2">Etiquetas...</div>
                              <div class="tarea__acciones">
                              <i class="fas fa-edit pointer"></i>
                              <i class="fas fa-trash-alt pointer"></i>
                              </div>
                            </div>
                          </div>
                          <div class="tarea__body mb-2">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Nulla, quam a deserunt consequatur ullam voluptatibus ipsum dolores error rem iure.
                          </div>
                          <div class="tarea__footer">
                            <label for="date-tarea-1" class="text-muted" id="label-date-1">12/01/2022</label>
                            <input type="date" name="date-tarea-1" id="date-tarea-1" data-id="1" style="width: 0; height: 0; opacity: 0; visibility: hidden;" onchange="changeDate(this)">
                          </div>
                        </div>
                        <div class="card shadow mb-3 p-3 move-drag">To-do-2</div>
                        <div class="card shadow mb-3 p-3 move-drag">To-do-3</div>
                        <div class="card shadow mb-3 p-3 move-drag">To-do-4</div>
                      </div>
                      <div class="card-footer">
                        <div class="pointer">+ Añadir tarea . . .</div>
                      </div>
                    </div>
                  </div>
                  <div class="col-12 col-md-4">
                    <div class="card card-tarea shadow">
                      <div class="card-header">En proceso</div>
                      <div class="card-body" id="inprogress">
                        <div class="card shadow mb-3 p-3 move-drag">In-progress-1</div>
                        <div class="card shadow mb-3 p-3 move-drag">In-progress-2</div>
                        <div class="card shadow mb-3 p-3 move-drag">In-progress-3</div>
                        <div class="card shadow mb-3 p-3 move-drag">In-progress-4</div>
                      </div>
                      <div class="card-footer">
                        <div class="pointer">+ Añadir tarea . . .</div>
                      </div>
                    </div>
                  </div>
                  <div class="col-12 col-md-4">
                    <div class="card card-tarea shadow">
                      <div class="card-header">Finalizadas</div>
                      <div class="card-body" id="done">
                        <div class="card shadow mb-3 p-3 move-drag">Done-1</div>
                        <div class="card shadow mb-3 p-3 move-drag">Done-2</div>
                        <div class="card shadow mb-3 p-3 move-drag">Done-3</div>
                        <div class="card shadow mb-3 p-3 move-drag">Done-4</div>
                      </div>
                      <div class="card-footer">
                        <div class="pointer">+ Añadir tarea . . .</div>
                      </div>
                    </div>
                  </div>
                </div>
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
      require_once $rutaf . 'footer.php';
      ?>
      <!-- End of Footer -->
    </div>
    <!-- End of Content Wrapper -->
  </div>
  <!-- End of Page Wrapper -->

  <script src="../../../js/sb-admin-2.min.js"></script>
  <script src="../../../js/Sortable.js"></script>
  <script src="../../../js/scripts.js"></script>
  <script src="js/index.js"></script>
</body>

</html>