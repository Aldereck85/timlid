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

  <title>Timlid | Agregar proveedor</title>

  <!-- Bootstrap core JavaScript-->
  <script src="../../../../vendor/jquery/jquery.min.js"></script>
  <script src="../../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="../../../../vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Page level plugins -->
  <script src="../../../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../../../vendor/datatables/dataTables.bootstrap4.min.js"></script>
  <script src="../../../../vendor/datatables/dataTables.responsive.js"></script>
  <script src="../../../../vendor/datatables/dataTables.buttons.js"></script>
  <script src="../../../../vendor/datatables/buttons.bootstrap4.min.js"></script>
  <script src="../../../../vendor/datatables/buttons.html5.min.js"></script>
  <script src="../../../../vendor/jszip/jszip.min.js"></script>

  <!-- Page level custom scripts
    <script src="js/demo/datatables-demo.js"></script>
    -->

  <!-- Custom fonts for this template -->
  <link href="../../../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

  <!-- Custom styles for this template -->
  <link href="../../../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../../../css/slimselect.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../../style/agregar_entrada.css">
  <link rel="stylesheet" href="../../style/pestanas_proveedores.css">

  <!-- Custom scripts for all pages-->

  <script src="../../../../js/sb-admin-2.min.js"></script>
  <script src="../../../../js/scripts.js"></script>

  <!-- Custom styles for this page -->
  <link href="../../../../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
  <link href="../../../../vendor/datatables/responsive.dataTables.css" rel="stylesheet">
  <link rel="stylesheet" href="../../../../vendor/datatables/buttons.dataTables.css">
  <link href="../../../../vendor/datatables/buttons.bootstrap4.min.css" rel="stylesheet">
  <link href="../../../../css/stylesTable.css" rel="stylesheet">
  <link href="../../../../css/styles.css" rel="stylesheet">
  <link rel="stylesheet" href="../../../../css/croppie.css" />

  <script src="../../../../js/numeral.min.js" charset="utf-8"></script>
  <script src="../../../../js/croppie.js"></script>

  <script src="../../../../js/sweet/sweetalert2.js"></script>
  <link rel="stylesheet" href="../../../../css/sweetalert2.css">
  <link rel="stylesheet" href="../../../../css/notificaciones.css">

  <script src="../../../../js/notificaciones_timlid.js" charset="utf-8"></script>
  <script src="../../js/agregar_proveedores.js" charset="utf-8"></script>
  <script src="../../js/razon_social_proveedor.js" charset="utf-8"></script>
  <script src="../../js/contacto_proveedor.js" charset="utf-8"></script>
  <script src="../../js/banco_proveedor.js" charset="utf-8"></script>
  <script src="../../js/direccion_envio_proveedor.js" charset="utf-8"></script>

</head>

<body id="page-top">
  <!-- Imagen de fondo de cargando datos
  <div style="position: fixed;
                  left: 0px;
                  top: 0px;
                  width: 100%;
                  height: 100%;
                  z-index: 9999;
                  background: url('../../../../img/timdesk/Preloader.gif') 50% 50% no-repeat rgb(249,249,249);
                  opacity: .6;" id="loader">
  </div>-->
  <!-- Page Wrapper -->

  <div id="wrapper">

    <!-- Sidebar -->
    <?php
$icono = '../../../../img/icons/proveedores.svg';
$titulo = '<div class="header-screen d-flex align-items-center">
                <div class="header-title-screen">
                  <h1 class="h3 mb-2">Agregar proveedores</h1>
                </div>
              </div>';
$ruta = "../../../";
$ruteEdit = $ruta . "central_notificaciones/";
require_once $ruta . 'menu3.php';
?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <?php
$rutatb = "../../../";

require_once $rutatb . 'topbar.php';
?>
        <!-- End of Topbar -->
        <input type="hidden" id="txtUsuario" value="<?=$_SESSION['PKUsuario'];?>">
        <input type="hidden" id="txtRuta" value="<?=$ruta;?>">
        <input type="hidden" id="txtEdit" value="<?=$ruteEdit;?>">
        .

        <!-- Begin Page Content -->
        <div class="container-fluid">
          <div class="row">
            <div class="col-lg-12">
              <ul class="nav nav-tabs">
                <li class="nav-item">
                  <a id="CargarDatosProveedor" class="nav-link nav-link--add" href="#">
                    Datos del proveedor
                  </a>
                </li>
                <li class="nav-item pestanas_proveedor">
                  <a id="CargarDatosFiscal" class="nav-link nav-link--add">
                    Información fiscal
                  </a>
                </li>
                <li class="nav-item">
                  <a id="CargarDatosContacto" class="nav-link nav-link--add">
                    Contacto
                  </a>
                </li>
                <li class="nav-item">
                  <a id="CargarDatosCuentasBancarias" class="nav-link nav-link--add">
                    Cuentas bancarias
                  </a>
                </li>
                <li class="nav-item">
                  <a id="CargarDireccionesEnvio" class="nav-link nav-link--add">
                    Productos
                  </a>
                </li>
              </ul>
              <input id="PKUsuario" value="<?php echo $_SESSION["PKUsuario"]; ?>" type="hidden">
              <!-- Basic Card Example -->
              <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="datos" role="tabpanel" aria-labelledby="nav-main-tab">
                </div>
              </div>
            </div>
          </div>

          <!-- End Begin Page Content -->

        </div>
        <!-- End Main Content -->

        <!-- Footer -->
        <?php
$rutaf = "../../../";
require_once $rutaf . 'footer.php';
?>

      </div>
      <!-- End Content Wrapper -->

    </div>
    <!-- End Page Wrapper -->



    <script src="../../../../js/slimselect.min.js"></script>
    <!-- Custom scripts for all pages-->

    <script src="../../../../js/pestanas_proveedores.js"></script>
    <script>
    loadAlertsNoti('alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
    setInterval(loadAlertsNoti, 10000, 'alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit')
      .val());
    </script>
    <script type="text/javascript">
    CargarDatosProveedor();
    </script>

    <script>
    /*new SlimSelect({
      select: '#cmbClaveSAT',
      deselectLabel: '<span class="">✖</span>',
    });*/


    /*var loadFile = function(event) {
      var reader = new FileReader();
      reader.onload = function(){
        var output = document.getElementById('imgProd');
        output.src = reader.result;
      };
      reader.readAsDataURL(event.target.files[0]);
    };*/
    </script>
</body>

</html>