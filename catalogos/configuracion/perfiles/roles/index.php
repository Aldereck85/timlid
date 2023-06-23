<?php
session_start();

if (isset($_SESSION["Usuario"])) {
  require_once '../../../../include/db-conn.php';
  $user = "";
  $name = "";
  $role = "";
  $id = "";

  if (isset($_GET['id'])) {
    $id = $_GET['id'];
  }

  if (isset($_GET['user']) && isset($_GET['name']) && isset($_GET['role'])) {
    $user = base64_decode($_GET['user']);
    $name = base64_decode($_GET['name']);
    $role = base64_decode($_GET['role']);
  }


} else {
  header("location:../../dashboard.php");
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
  <title>Timlid | Perfiles de usuario</title>

  <!-- STYLES -->
  <link href="../../../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
  <link href="../../../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../../../css/slimselect.min.css" rel="stylesheet">
  <link href="../../../../css/lobibox.min.css" rel="stylesheet">
  <link href="css/roles.css" rel="stylesheet">
  <link href="../../../../css/styles.css" rel="stylesheet">
  <!-- SCRIPTS -->
  <script src="../../../../vendor/jquery/jquery.min.js"></script>
  <script src="../../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../../../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../../../../js/sb-admin-2.min.js"></script>
  <script src="../../../../js/lobibox.min.js"></script>
  <script src="js/roles.js"></script>

</head>

<body id="page-top" class="sidebar-toggled">
  <div id="loader"></div> 
  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <?php
    $icono = '../../img/toolbar/usuarios.svg';
    $titulo = '<div class="header-screen d-flex align-items-center">
                  <div class="header-title-screen">
                    <h1 class="h3">Perfiles de usuario </h1>
                  </div>
                </div>';
    $ruta = "../../../";
    $ruteEdit = $ruta . "central_notificaciones/";
    require_once $ruta . 'menu3.php';
    ?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
      <input type="hidden" id="txtUsuario" value="<?= $_SESSION['PKUsuario']; ?>">
      <input type="hidden" id="txtRuta" value="<?= $ruta; ?>">
      <input type="hidden" id="txtEdit" value="<?= $ruteEdit; ?>">
      <input type="hidden" id="txtIdProfile" value="<?= $id ?>">
      <input type="hidden" id="txtUser" value="<?= $user; ?>">
      <input type="hidden" id="txtUserName" value="<?= $name; ?>">
      <input type="hidden" id="txtUserRole" value="<?= $role; ?>">

      <!-- Main Content -->
      <div id="content">
        <?php
        $rutatb = "../../../";
        $backIcon = true;
        $backRoute = "../../";
        require_once $rutatb . 'topbar.php';
        ?>

        <!-- Begin Page Content -->
        <div class="container-fluid">
          <!-- Page Heading -->
          <!--
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Personalización de rol</h1>
          </div>
          -->
          
          <div class="row">
            <div class="col-lg-12">
              <!-- Basic Card Example -->
              <div class="card">
                <div class="card-body">
                  <div class="row">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                      <h1 class="h3 mb-0 text-gray-800">
                        <? //=$_GET['usuario'];
                        ?>
                      </h1>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-lg-6">
                      <label for="">Perfil:</label>
                      <input class="form-control" type="text" name="txtPerfil" id="txtPerfil">
                      <div class="invalid-feedback" id="invalid-perfil">El perfil debe tener un nombre.</div>
                    </div>
                    <!-- <div class="col-lg-6">
                      <label for="">Rol:</label>
                      <select name="cmbRol" id="cmbRol"></select>
                      <div class="invalid-feedback" id="invalid-rol">El perfil debe tener un rol.</div>
                    </div> -->
                    <div class="col-lg-12">
                      <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                          <br>
                          <div class="row">
                            <div class="col-lg-12">
                              <div class="radio-permisos-container">
                                <span class='radio-permisos'>
                                  <input type="radio" id="rdoControlTotalGeneral" class="rdbHeader" name="gender">
                                  <label for="rdoControlTotalGeneral">Control total general<span></span> <span></span>
                                  </label>
                                </span>
                                <span class='radio-permisos'>
                                  <input type="radio" id="rdoNoEliminarGeneral" class="rdbHeader" name="gender">
                                  <label for="rdoNoEliminarGeneral">Controlar todo excepto eliminar general<span></span>
                                    <span></span></label>
                                </span>
                                <span class='radio-permisos'>
                                  <input type="radio" id="rdoVerGeneral" class="rdbHeader" name="gender">
                                  <label for="rdoVerGeneral">Solo lectura general<span></span> <span></span></label>
                                </span>
                                <span class='radio-permisos'>
                                  <input type="radio" id="rdoSinPermisosGeneral" class="rdbHeader" name="gender">
                                  <label for="rdoSinPermisosGeneral">Sin permisos general<span></span>
                                    <span></span></label>
                                </span>
                                <span class='radio-permisos'>
                                  <input type="radio" id="rdoPersonalizadoGeneral" class="rdbHeader" name="gender">
                                  <label for="rdoPersonalizadoGeneral">Personalizado general<span></span>
                                    <span></span></label>
                                </span>
                              </div>

                            </div>
                          </div>
                          <br>
                          <div class="row">
                            <div class="col-lg-12">
                              <div class="accordion" id="title-card-tabs">
                                
                              </div>
                            </div>
                          </div>
                          <div>
                            <button style="float: right;" class="btn-custom btn-custom--blue" name="btnGuardar" id="btnGuardar" style="margin-right:10px;" disabled>Guardar</button>
                            <div id="showBtnEliminar" style="margin-right:10px;"></div>
                          </div>

                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>
        <!-- End Page Content -->
      </div>
      <!-- End Main Content -->
      <!-- Footer -->
      <?php
      $rutaf = "../../../";
      require_once $rutaf . 'footer.php';
      ?>
      <!-- End of Footer -->
    </div>
    <!-- End Content Wrapper -->
  </div>
  <!-- End Page Wrapper -->
  <script src="../../../../js/sb-admin-2.min.js"></script>
  <script src="../../../../js/scripts.js"></script>
  <script src="../../../../js/slimselect.min.js"></script>

</body>

</html>