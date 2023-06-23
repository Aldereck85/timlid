<?php
date_default_timezone_set('America/Mexico_City');
session_start();
if (isset($_SESSION["Usuario"])) {
  $user = $_SESSION["Usuario"];
  $pkusuario = $_SESSION["PKUsuario"];
  $ruta = "../../../";
  $screen = 8;
  require_once $ruta . 'include/db-conn.php';
  /* ACTUALIZA EL STATUS DE LAS TAREAS CUANDO LA FECHA LIMITE SE A VENCIDO */
  $query = 'UPDATE tareas_simples_recurrentes AS tsr
  INNER JOIN tareas_simples AS ts ON tsr.id_tarea_simple = ts.id
  SET tsr.status = "delayed" 
  WHERE ts.id_usuario = :usuario_id AND tsr.fecha_tarea < NOW() AND tsr.status != "done"';
  $stmt = $conn->prepare($query);
  $stmt->execute([':usuario_id' => $_SESSION['PKUsuario']]);
  /* ACTIVA LAS TAREAS QUE TENEN UNA FECHA IGUAL O MAYOR A LA DE HOY */
  $query = 'UPDATE tareas_simples_recurrentes AS tsr
  INNER JOIN tareas_simples AS ts ON tsr.id_tarea_simple = ts.id
  SET tsr.active = 1 
  WHERE id_usuario = :usuario_id AND (NOW() >= tsr.fecha_tarea)';
  $stmt = $conn->prepare($query);
  $stmt->execute([':usuario_id' => $_SESSION['PKUsuario']]);
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
  <link href="../../../css/lobibox.min.css" rel="stylesheet">
  <link href="../../../css/styles.css" rel="stylesheet">
  <link href="../../../css/stylesNewTable.css" rel="stylesheet">
  <link href="css/index.css" rel="stylesheet">
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
        $icono = 'ICONO-TAREAS-SIMPLES-AZUL.svg';
        $titulo = 'Tareas';
        require_once $rutatb . 'topbar.php';
        ?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->

          <!-- DataTales Example -->
          <div class="card mb-4 border-0 shadow">
            <div class="card-body">
              <!-- INICIO FILTROS -->
              <div class="listaColumnas d-none" id="listaColumnas">
                <div class="mt-2 text-center">
                  <strong>ORDENAR POR</strong>
                </div>
                <div class="pd-15 columna-item">
                  <div class="text-left" style="margin-right:10px;">
                    <div id="statusFiltroTarea" class="check-type-column filtro" data-tipo="orden" data-valor="status" onclick="filtroSelect(this)">
                    </div>
                  </div>
                  <div>
                    <span class="fs-15 colorG">Status de la tarea</span>
                  </div>
                </div>
                <div class="pd-15 columna-item">
                  <div class="text-left" style="margin-right:10px;">
                    <div id="fechaFiltroTarea" class="check-type-column filtro" data-tipo="orden" data-valor="fecha_tarea" onclick="filtroSelect(this)">
                    </div>
                  </div>
                  <div>
                    <span class="fs-15 colorG">Fecha limite</span>
                  </div>
                </div>
                <div class="text-center">
                  <strong>MOSTRAR SOLO</strong>
                </div>
                <div class="pd-15 columna-item">
                  <div class="text-left" style="margin-right:10px;">
                    <div id="todoFiltroTarea" class="check-type-column filtro" data-tipo="mostrar" data-valor="todo" onclick="filtroSelect(this)">
                    </div>
                  </div>
                  <div>
                    <span class="fs-15 colorG">Tareas en por hacer</span>
                  </div>
                </div>
                <div class="pd-15 columna-item">
                  <div class="text-left" style="margin-right:10px;">
                    <div id="doneFiltroTarea" class="check-type-column filtro" data-tipo="mostrar" data-valor="done" onclick="filtroSelect(this)">
                    </div>
                  </div>
                  <div>
                    <span class="fs-15 colorG">Tareas finalizadas</span>
                  </div>
                </div>
                <div class="pd-15 columna-item">
                  <div class="text-left" style="margin-right:10px;">
                    <div id="doneFiltroTarea" class="check-type-column filtro" data-tipo="mostrar" data-valor="delayed" onclick="filtroSelect(this)">
                    </div>
                  </div>
                  <div>
                    <span class="fs-15 colorG">Tareas retrasadas</span>
                  </div>
                </div>
                <div class="text-center">
                  <strong>RANGO DE FECHAS</strong>
                </div>
                <div class="pd-15">
                  <label for="" class="d-block text-left">Desde:</label>
                  <input type="date" class="form-control input-filtro filtro" data-index="0" onchange="filtroSelect(this)">
                </div>
                <div class="pd-15">
                  <label for="" class="d-block text-left">Hasta:</label>
                  <input type="date" class="form-control input-filtro filtro" data-index="1" onchange="filtroSelect(this)">
                </div>
              </div>
              <!-- FIN FILTROS -->
              <div class="container-fluid">
                <div class="row mb-3">
                  <div class="col-sm-12 col-md-9 p-0 d-flex align-items-center">
                    <div class="dt-buttons">
                      <button class="btn-custom btn-custom--white-dark" data-toggle="modal" data-target="#modalAddTarea" type="button"><img src="../../../img/icons/ICONO_AGREGAR_DARK.svg" width="20" class="mr-1"> Añadir registro</button>
                    </div>
                  </div>
                  <div class="col-sm-12 col-md-3 p-0 d-flex justify-content-end">
                    <button id="btnVerFiltros" class="btn-custom btn-custom--white-dark dropdown-toggle btn-tareas-simples mr-3" type="button">
                      <img src="../../../img/icons/ICONO FILTRAR-01.svg" width="20" class="mr-1">
                      Filtros
                    </button>
                    <span class="d-flex">
                      <img src="../../../img/timdesk/buscar.svg" width="20px"><input id="input-buscar" type="text" placeholder="Buscar..." class="form-control pl-2">
                    </span>
                  </div>
                </div>
              </div>
              <div class="container-fluid">
                <div class="row">
                  <div class="col-12">
                    <div class="card-body" id="tareas">
                    </div>
                  </div>
                </div>
                <div class="row mt-4 justify-content-center">
                  <div class="col-6 col-lg-3">
                    <div class="status-meaning d-flex justify-content-center align-items-center">
                      <div class="color-meaning nota-tarea--todo mr-1"></div>
                      <div class="name">Tareas por hacer</div>
                    </div>
                  </div>
                  <div class="col-6 col-lg-3">
                    <div class="status-meaning d-flex justify-content-center align-items-center">
                      <div class="color-meaning nota-tarea--done mr-1"></div>
                      <div class="name">Tareas finalizadas</div>
                    </div>
                  </div>
                  <div class="col-6 col-lg-3">
                    <div class="status-meaning d-flex justify-content-center align-items-center">
                      <div class="color-meaning nota-tarea--delayed mr-1"></div>
                      <div class="name">Tareas retrasadas</div>
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

  <!-- Add Fluid Modal User -->
  <div class="modal fade right" id="modalAddTarea" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
    <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">
      <div class="modal-content">
        <form action="#" method="POST" id="formAgregarTarea">
          <input type="hidden" name="input-id-tarea" id="input-id-tarea">
          <div class="modal-header">
            <h4 class="modal-title w-100" id="myModalLabel">Agregar tarea</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">x</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label for="input-titulo">Titulo:*</label>
              <input type="text" class="form-control" name="input-titulo" id="input-titulo" required>
              <div class="invalid-feedback" id="invalid-titulo">La tarea debe tener un titulo.</div>
            </div>
            <div class="form-group">
              <label for="input-titulo">Status:*</label>
              <select name="input-status" id="input-status">
                <option value="todo">Por hacer</option>
                <option value="done">Finalizadas</option>
              </select>
              <div class="invalid-feedback" id="invalid-">La tarea debe tener un titulo.</div>
            </div>
            <div class="form-group">
              <label for="input-descripcion">Descripción:</label>
              <textarea name="input-descripcion" id="input-descripcion" class="form-control"></textarea>
              <div class="invalid-feedback" id="invalid-descripcion">La tarea debe tener una descripción.</div>
            </div>
            <div class="form-group">
              <label for="input-fecha">Fecha limite:</label>
              <input type="date" class="form-control" name="input-fecha" id="input-fecha">
              <div class="invalid-feedback" id="invalid-fecha">Las tareas recurrentes deben tener una fecha.</div>
            </div>
            <div class="form-group">
              <div class="form-check">
                <input type="checkbox" class="form-check-input" name="check-recurrencia" id="check-recurrencia">
                <label class="form-check-label" for="check-recurrencia">¿Es recurrente?</label>
              </div>
            </div>
            <div id="contenedor-recurrente" class="d-none">
              <div class="form-group">
                <label for="input-recurrencia" id="label-recurrencia">Recurrencia:</label>
                <select name="input-recurrencia" id="input-recurrencia">
                  <option value="semanal">Semanal</option>
                  <option value="mensual">Mensual</option>
                  <option value="trimestral">Trimestral</option>
                  <option value="semestral">Semestral</option>
                  <option value="anual">Anual</option>
                </select>
              </div>
            </div>
            <div class="modal-footer justify-content-center">
              <button type="button" class="btn-custom btn-custom--border-blue" data-dismiss="modal" id="btnCancelarAgregar"><span class="ajusteProyecto">Cancelar</span></button>
              <button type="button" class="btn-custom btn-custom--blue" id="btnAgregarTarea">Agregar</button>
              <button type="button" class="btn-custom btn-custom--blue d-none" id="btnEditarTarea">Editar</button>
            </div>
        </form>
      </div>
    </div>
  </div>
  <!-- End Add Fluid Modal User -->

  <script src="../../../js/sb-admin-2.min.js"></script>
  <script src="../../../js/slimselect.min.js"></script>
  <script src="../../../js/lobibox.min.js"></script>
  <script src="../../../js/scripts.js"></script>
  <script src="js/index.js"></script>
</body>

</html>