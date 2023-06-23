<?php //include 'checkcotizaciones.php' 
?>
<?php //include 'checkPedidos.php' 
?>

<!-- Topbar -->
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 sticky-top shadow">

  <!-- Sidebar Toggle (Topbar) -->
  <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
    <i class="fa fa-bars"></i>
  </button>

  <!-- Topbar Search -->

  <h3 class="d-none d-sm-inline-block">
    <?php
    $avatar = $_SESSION['avatar'] ? $_ENV['RUTA_ARCHIVOS_READ'] . $_SESSION["IDEmpresa"] . '/img/' . $_SESSION['avatar'] : $_ENV['APP_URL'] . 'img/timUser.png';
    $imgDisplay = '';
    if (!isset($icono)) {
      $icono = isset($icono) ? $icono : "dashboardTopbar.svg";
    }
    /* if (strpos($_SERVER['REQUEST_URI'], 'timDesk')) {
      $imgDisplay = 'd-none';
    } */
    ?>
    <div class="header-screen">
      <div class="header-title-screen">
        <img src="<?= $rutatb ?>../img/nuevos-iconos/<?= $icono; ?>" alt="" class="<?= $imgDisplay ?> mr-3">
        <?= $titulo ?>
        <?php if (isset($backIcon) && $backIcon) {
          $imgIconBack = $rutatb . "../img/icons/REGRESAR_2.svg";
        ?>
          <a href="<?= isset($backRoute) ? $backRoute : "./" ?>" data-toggle="tooltip" data-placement="bottom" title="Regresar" class="ml-3">
            <img src="<?= $imgIconBack ?>" alt="Regresar">
          </a>
        <?php } ?>
      </div>
    </div>
  </h3>

  <!-- Topbar Navbar -->
  <ul class="navbar-nav ml-auto">
    <?php include 'notificaciones.php'; ?>

    <div class="topbar-divider d-none d-sm-block"></div>
    <!-- Nav Item - User Information -->
    <li class="nav-item dropdown no-arrow">
      <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <div class="mr-2 d-none d-lg-inline text-gray-600 small"><span style="color:  var(--color-primario);font-weight: 800;"><?= $_SESSION["NombreEmpresa"] ?></span><br><?= $_SESSION["UsuarioNombre"] ?></div>
        <img class="img-profile rounded-circle" src="<?= $avatar ?>" width="30px">
      </a>
      <!-- Dropdown - User Information -->
      <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
        <a class="dropdown-item" href="<?= $rutatb; ?>perfil/">
          <!-- <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i> -->
          <img src="../<?= $rutatb ?>img/toolbar/perfil.svg" width="15px">
          Perfil usuario
        </a>
        <a class="dropdown-item" href="<?= $rutatb; ?>empresas/">
          <!-- <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i> -->
          <img src="../<?= $rutatb ?>img/toolbar/perfil.svg" width="15px">
          Perfil empresa
        </a>
        <a class="dropdown-item" href="<?= $rutatb ?>configuracion/">
          <!-- <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i> -->
          <img src="../<?= $rutatb ?>img/toolbar/configuracion.svg" width="15px">
          Configuración
        </a>
        <?php if ($_SESSION["IDEmpresa"] == '1') { ?>
          <a class="dropdown-item" href="<?= $rutatb ?>secciones_pantallas/">
            <!-- <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i> -->
            <img src="../<?= $rutatb ?>img/toolbar/configuracion.svg" width="15px">
            Secciones - Pantallas
          </a>
        <?php } ?>
        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
          <!-- <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i> -->
          <img src="../<?= $rutatb ?>img/toolbar/salir.svg" width="15px">
          Cerrar sesión
        </a>
      </div>
    </li>

  </ul>

</nav>
<!-- Topbar -->

<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">¿En serio quieres salir?</h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">x</span>
        </button>
      </div>
      <div class="modal-body modal__log-out">Selecciona "Salir" Para cerrar tu sesión</div>
      <div class="modal-footer d-flex justify-content-around border-0">
        <button class="btn-custom btn-custom--border-blue" type="button" data-dismiss="modal">Cancelar</button>
        <a class="btn-custom btn-custom--blue" href="<?= $rutatb ?>logout.php">Salir</a>
      </div>
    </div>
  </div>
</div>