<?php
session_start();

$jwt_ruta = "../";
require_once 'jwt.php';

if (isset($_SESSION["Usuario"])) {
  require_once '../include/db-conn.php';
  require_once '../php_dashboard/clases.php';

  $getData = new get_data();
  $rol = $getData->getRol();
  $permissions = $getData->getWidgetsPermissions();

  /* FACTURACION TRES MESES */
  $mesActual = date('n');
  $anioActual = date('Y');
  $mesPasado = date('n', strtotime('first day of -1 month'));
  $mesAntePasado = date('n', strtotime('first day of -2 month'));
  $factMesAct = $getData->getVentasMes($mesActual, $anioActual);
  $NofactMesAct = $_SESSION['IDEmpresa'] != 6 ? $getData->getVentasMes($mesActual, $anioActual, 0, 0) : 0;
  $factMesActEmp = $getData->getVentasMes($mesActual, $anioActual, $_SESSION['PKUsuario']);
  $factMesPas = $getData->getVentasMes($mesPasado, $anioActual);
  $NofactMesPas = $_SESSION['IDEmpresa'] != 6 ? $getData->getVentasMes($mesPasado, $anioActual, 0, 0) : 0;
  $factMesPasEmp = $getData->getVentasMes($mesPasado, $anioActual, $_SESSION['PKUsuario']);
  $factMesAnte = $getData->getVentasMes($mesAntePasado, $anioActual);
  $NofactMesAnte = $_SESSION['IDEmpresa'] != 6 ? $getData->getVentasMes($mesAntePasado, $anioActual, 0, 0) : 0;
  $factMesAnteEmp = $getData->getVentasMes($mesAntePasado, $anioActual, $_SESSION['PKUsuario']);
  $TotalFactAnio = $getData->getVentasMes(0, $anioActual);
  $NoTotalFactAnio = $_SESSION['IDEmpresa'] != 6 ? $getData->getVentasMes(0, $anioActual, 0, 0) : 0;
  if ($factMesPas === $factMesAct) {
    $porcentajeVenta = 0;
    $mensajeVentas = 'Las ventas han subido un';
  } else if ($factMesPas == 0 && $factMesAct == 0) {
    $porcentajeVenta = 0;
    $mensajeVentas = 'Las ventas han subido un';
  } else if ($factMesPas == 0) {
    $porcentajeVenta = $factMesAct;
    $mensajeVentas = 'Las ventas han subido un';
  } else {
    $porcentajeVenta = (($factMesAct - $factMesPas) / $factMesPas) * 100;
    $mensajeVentas = $factMesAct > $factMesPas ? 'Las ventas han subido un' : 'Las ventas han bajado un';
  }
} else {
  header("location:../index.php");
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <link rel="icon" type="image/png" href="../img/header/bluTimlid.png">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>Timlid | Dashboard</title>

  <!-- STYLES -->
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../css/sb-admin-2.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/calendar.min.css">
  <link href="../css/styles.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/notificaciones.css">
  <!-- SCRIPTS -->
  <script src="../vendor/jquery/jquery.min.js"></script>
  <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
</head>

<body id="page-top" class="sidebar-toggled">
  <!-- Page Wrapper -->
  <div id="wrapper">
    <?php
    $ruta = "";
    $ruteEdit = $ruta . "central_notificaciones/";
    require_once $ruta . 'menu3.php';
    ?>
    <!-- End of Sidebar -->
    <input type="hidden" id="txtUsuario" value="<?= $_SESSION['PKUsuario'] ?>">
    <input type="hidden" id="txtRuta" value="<?= $ruta ?>">
    <input type="hidden" id="txtEdit" value="<?= $ruteEdit ?>">
    <input type="hidden" id="rol" value="<?= $rol ?>">
    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column content-dashboard">
      <!-- Main Content -->
      <div id="content">
        <?php
        $rutatb = '';
        //$icono = '../img/menu/dashboardTopbar.svg';
        $icono = 'dashboardTopbar.svg';
        $titulo = 'Dashboard';
        require_once 'topbar.php';
        ?>
        <!-- Begin Page Content -->
        <div class="container-fluid">
          <h3 class="mb-4 color-primary">Bienvenido <?= $_SESSION['UsuarioNombre'] ?></h3>
          <div class="row">
            <!-- MI FACTURACION DEL MES -->
            <?php if ($permissions['mifacturacion']['permiso'] === 1) { ?>
              <div class="col-12 col-lg-6 col-xl-4 mb-4">
                <div class="card card-dashboard card-facturacion">
                  <div class="card-header">
                    <span class="m-0">Mi facturación</span>
                  </div>
                  <div class="card-body card-ventas pt-4">
                    <div class="text-center">
                      <p class="card-facturacion__actual">
                        <span>$</span>
                        <span><?= number_format($factMesActEmp, 2, ".", ","); ?></span>
                        <span class="color-primary">Mes actual</span>
                      </p>
                      <p class="card-facturacion__anterior">
                        <span>$</span>
                        <span><?= number_format($factMesPasEmp, 2, ".", ","); ?></span>
                        <span class="color-primary">Mes pasado</span>
                      </p>
                      <p class="card-facturacion__anterior">
                        <span>$</span>
                        <span><?= number_format($factMesAnteEmp, 2, ".", ","); ?></span>
                        <span class="color-primary">Mes Antepasado</span>
                      </p>
                    </div>
                  </div>
                </div>
              </div>
            <?php } ?>
            <!-- FACTURACION DEL MES -->
            <?php if ($permissions['ventas']['permiso'] === 1) { ?>
              <div class="col-12 col-lg-6 col-xl-4 mb-4">
                <div class="card card-dashboard card-facturacion">
                  <div class="card-header">
                    <span class="m-0">Ventas</span>
                  </div>
                  <div class="card-body card-ventas pt-4">
                    <div class="text-center">
                      <p class="card-facturacion__actual_VD">
                        <span class="color-primary">Mes actual</span>
                        <span>Facturado</span>
                        <span>$</span>
                        <span><?= number_format($factMesAct, 2, ".", ","); ?></span>
                        <span>Sin facturar</span>
                        <span>$</span>
                        <span><?= number_format($NofactMesAct, 2, ".", ","); ?></span>
                      </p>
                      <p class="anteriorVD">
                        <span class="color-primary">Mes pasado</span>
                        <span>Facturado</span>
                        <span>$</span>
                        <span><?= number_format($factMesPas, 2, ".", ","); ?></span>
                        <span>Sin facturar</span>
                        <span>$</span>
                        <span><?= number_format($NofactMesPas, 2, ".", ","); ?></span>
                      </p>
                      <p class="anteriorVD">
                        <span class="color-primary">Mes Antepasado</span>
                        <span>Facturado</span>
                        <span>$</span>
                        <span><?= number_format($factMesAnte, 2, ".", ","); ?></span>
                        <span>Sin facturar</span>
                        <span>$</span>
                        <span><?= number_format($NofactMesAnte, 2, ".", ","); ?></span>
                      </p>
                    </div>
                  </div>
                </div>
              </div>
            <?php } ?>
            <!-- VENTAS POR EJECUTIVO -->
            <?php if ($permissions['ventasejecutivo']['permiso'] === 1) {
              /* VENTAS POR EMPLEADO*/
              $ventas = $getData->getVentasMesEmpleados();
            ?>
              <div class="col-12 col-lg-6 col-xl-4 mb-4">
                <div class="card card-dashboard">
                  <div class="card-header">
                    <span class="m-0">Ventas por ejecutivo</span>
                  </div>
                  <div class="card-body card-ventas pt-4">
                    <?php foreach ($ventas as $venta) { ?>
                      <div class="mb-1">
                        <span class="lead"><?= $venta['Nombre'] ?></span>
                        <span class="d-block color-primary">$ <?= number_format($venta['Ventas'], 2, ".", ",") ?></span>
                      </div>
                    <?php } ?>
                  </div>
                </div>
              </div>
            <?php } ?>
            <!-- CUENTAS POR PAGAR -->
            <?php
            if ($permissions['cuentaspagar']['permiso'] === 1) {
              $totalCuentasPorPagar = number_format($getData->getCuentasPorPagar(), 2, ".", ",");
            ?>
              <div class="col-12 col-lg-6 col-xl-4 mb-4">
                <div class="card card-dashboard">
                  <div class="card-header">
                    <span class="m-0">Cuentas por pagar</span>
                  </div>
                  <div class="card-body card-ventas pt-4">
                    <div class="row">
                      <div class="col-12 ">
                        <div class="text-center">
                          <p class="lead">Total cuentas por pagar</p>
                          <p class="display-4">$ <?= $totalCuentasPorPagar ?></p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            <?php } ?>
            <!-- CUENTAS POR COBRAR -->
            <?php
            if ($permissions['cuentascobrar']['permiso'] === 1) {
              /* CUENTAS POR COBRAR */
              $NototalCuentasPorCobrar = number_format($_SESSION['IDEmpresa'] != 6 ? $getData->getCuentasPorCobrar(0) : 0, 2, ".", ",");
              $totalCuentasPorCobrar = number_format($getData->getCuentasPorCobrar(), 2, ".", ",");
            ?>
              <div class="col-12 col-lg-6 col-xl-4 mb-4">
                <div class="card card-dashboard">
                  <div class="card-header">
                    <span class="m-0">Cuentas por cobrar</span>
                  </div>
                  <div class="card-body card-ventas pt-4">
                    <div class="row">
                      <div class="col-12 ">
                        <div class="text-center">
                          <p class="lead">Total cuentas por cobrar</p>
                          <p class="card-TotalVentas">
                            <span class="color-primary">Facturado</span>
                            <span>$</span>
                            <span><?= $totalCuentasPorCobrar; ?></span>
                            <span class="color-primary">Sin Facturar</span>
                            <span>$</span>
                            <span><?= $NototalCuentasPorCobrar; ?></span>
                          </p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            <?php } ?>
            <!-- FACTURAS -->
            <?php if ($permissions['ventasanios']['permiso'] === 1) { ?>
              <div class="col-12 col-lg-6 col-xl-4 mb-4">
                <div class="card card-dashboard">
                  <div class="card-header">
                    <span class="m-0">Ventas del año</span>
                  </div>
                  <div class="card-body card-ventas pt-4">
                    <div class="row">
                      <div class="col-12 ">
                        <div class="text-center">
                          <p class="lead">Total ventas del año</p>
                          <p class="card-TotalVentas">
                            <span class="color-primary">Facturado</span>
                            <span>$</span>
                            <span><?= number_format($TotalFactAnio, 2, ".", ","); ?></span>
                            <span class="color-primary">Sin Facturar</span>
                            <span>$</span>
                            <span><?= number_format($NoTotalFactAnio, 2, ".", ","); ?></span>
                          </p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            <?php } ?>
          </div>
          <div class="dashboard-grid">
            <!-- PROYECTOS EN CURSO -->
            <?php
            if ($permissions['cumpleanios']['permiso'] === 1) {
              $proyectos = $getData->getProjects();
            ?>
              <div class="proyectos-curso">
                <div class="card card-dashboard card-proyectos">
                  <div class="card-header">
                    <span class="m-0">Proyectos en curso</span>
                  </div>
                  <div class="card-body pt-4">
                    <div>
                      <p class="text-center display-4"><?= count($proyectos) ?></p>
                      <?php if (count($proyectos)) { ?>
                        <?php foreach ($proyectos as $proyecto) { ?>
                          <span class="card-proyectos__blue"><?= $proyecto['Proyecto'] ?></span>
                        <?php } ?>
                      <?php } else { ?>
                        <p>No tienes proyectos actualmente</p>
                      <?php } ?>
                    </div>
                  </div>
                </div>
              </div>
            <?php } ?>
            <!-- NOTAS -->
            <?php if ($permissions['notas']['permiso'] === 1) { ?>
              <div class="notas">
                <div class="card card-dashboard card-notas">
                  <div class="card-body p-0">
                    <textarea name="" id="notas-usuario" cols="22" rows="5" placeholder="Escribe algo importante"></textarea>
                  </div>
                </div>
              </div>
            <?php } ?>
            <?php
            /* CUMPLEAÑOS DEL MES */
            if ($permissions['cumpleanios']['permiso'] === 1) {
              $cumpleanios = $getData->getCumpleanios();
            ?>
              <div class="cumpleanios">
                <div class="card card-dashboard card-cumpleanios">
                  <div class="card-header">
                    <span class="m-0">Cumpleaños del mes</span>
                  </div>
                  <div class="card-body pt-4">
                    <div class="container-fluid">
                      <div class="row">
                        <div class="col-12 col-md-6 col-lg-6 col-xl-12 d-flex justify-content-center">
                          <img src="../img/PASTEL.png" alt="">
                        </div>
                        <div class="mt-5 col-12 col-md-6 col-lg-6 col-xl-12 d-flex justify-content-center text-center">
                          <ul class="p-0">
                            <?php foreach ($cumpleanios as $cumpl) { ?>
                              <li><?= $cumpl['diaNac'] ?> - <?= $cumpl['Nombres'] ?></li>
                            <?php } ?>
                          </ul>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            <?php } ?>
            <!-- CALENDARIO -->
            <?php if ($permissions['calendario']['permiso'] === 1) { ?>
              <div class="calendario">
                <div class="card card-dashboard card-calendario">
                  <div class="card-header">
                  </div>
                  <div class="card-body">
                    <div id="calendar">
                    </div>
                  </div>
                </div>
              </div>
            <?php } ?>
          </div>
          <div class="row">
            <!-- GRAFICA BARRA VENTAS AÑO -->
            <?php if ($permissions['ventasaniograficauno']['permiso'] === 1) { ?>
              <div class="col-12 col-lg-6 col-xl-4 mb-4">
                <div class="card card-dashboard">
                  <div class="card-header">
                    <span class="m-0">Ventas del año</span>
                  </div>
                  <div class="card-body" style="position: relative; height:500px">
                    <canvas id="myBarChart"></canvas>
                  </div>
                </div>
              </div>
            <?php } ?>
            <!-- GRAFICA DONA VENTAS AÑO -->
            <?php if ($permissions['ventasaniograficados']['permiso'] === 1) { ?>
              <div class="col-12 col-lg-6 col-xl-4 mb-4">
                <div class="card card-dashboard">
                  <div class="card-header">
                    <span class="m-0">Ventas del año</span>
                  </div>
                  <div class="card-body" style="position: relative; height:500px">
                    <canvas id="myBarChart2"></canvas>
                  </div>
                </div>
              </div>
            <?php } ?>
            <!-- GRAFICA DONA VENTAS MES -->
            <?php if ($permissions['ventasmesgrafica']['permiso'] === 1) { ?>
              <div class="col-12 col-lg-6 col-xl-4 mb-4">
                <div class="card card-dashboard">
                  <div class="card-header">
                    <span class="m-0">Ventas del mes</span>
                  </div>
                  <div class="card-body">
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <label class="input-group-text" for="inputMesEmpleados">Mes del año</label>
                      </div>
                      <select class="custom-select" id="inputMesEmpleados">
                        <option selected disabled>Selecciona el mes</option>
                        <option value="1">Enero</option>
                        <option value="2">Febrero</option>
                        <option value="3">Marzo</option>
                        <option value="4">Abril</option>
                        <option value="5">Mayo</option>
                        <option value="6">Junio</option>
                        <option value="7">Julio</option>
                        <option value="8">Agosto</option>
                        <option value="9">Septiembre</option>
                        <option value="10">Octubre</option>
                        <option value="11">Noviembre</option>
                        <option value="12">Diciembre</option>
                      </select>
                    </div>
                    <div id="canvas-container" style="position: relative; height:500px">
                    </div>
                  </div>
                </div>
              </div>
            <?php } ?>
          </div>
        </div>
        <!-- /.container-fluid -->
      </div>
      <!-- End of Main Content -->
      <!-- Footer -->
      <?php
      $rutaf = "";
      require_once 'footer.php';
      ?>
      <!-- End of Footer -->
    </div>
    <!-- End of Content Wrapper -->
  </div>
  <!-- End of Page Wrapper -->
  <!-- Custom scripts for all pages-->
  <script src="../js/sb-admin-2.min.js"></script>
  <script src="../js/scripts.js"></script>
  <script src="../vendor/chart.js/Chart.min.js"></script>
  <script src="../js/notificaciones_timlid.js"></script>
  <script src="../js/calendar.min.js"></script>
  <script src="../js/locales-all.min.js"></script>
  <script src="../js/dashboard.js"></script>
  <script>
    var ruta = "";
  </script>
  <script>
    var calendarEl = document.getElementById("calendar");
    calendar = new FullCalendar.Calendar(calendarEl, {
      height: 650,
      locale: 'es',
      initialView: "dayGridMonth",
      eventDurationEditable: false,
      events: {
        url: 'calendario_crm/app/controladores/loadCalendarController.php',
        method: "POST",
        extraParams: {
          accion: "cargar_eventos_por_usuario",
        },
      },
      eventClick: function(info) {
        console.log(info.event.start.getTime());
        var datemiliseconds = info.event.start.getTime();
        //var fecha_tarea = moment(info_evento.start).format("YYYY-MM-DD");
        //var fechaEvento = info.view.getCurrentData().currentDate.toISOString();
        window.location.href = "calendario_crm/?meet=" + datemiliseconds;
      }
    });

    calendar.render();
  </script>
</body>

</html>