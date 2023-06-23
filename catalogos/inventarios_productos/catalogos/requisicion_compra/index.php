<?php
session_start();
$jwt_ruta = "../../../../";
require_once '../../../jwt.php';

date_default_timezone_set('America/Mexico_City');

$token = $_SESSION['token_ld10d'];

if (isset($_SESSION["Usuario"])) {

    $user = $_SESSION["Usuario"];

    $pkusuario = $_SESSION["PKUsuario"];
    $ruta = "../../../";
    require_once $ruta . '../include/db-conn.php';
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
  <title>Timlid | Requisiciones de compra</title>

  <!-- ESTILOS -->
  <link href="../../../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../../../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../../../css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="../../../../vendor/datatables/buttons.dataTables.css" rel="stylesheet">
  <link href="../../../../css/styles.css" rel="stylesheet">
  <link href="css/requisicion.css" rel="stylesheet">
  <link href="../../../../css/stylesNewTable.css" rel="stylesheet">
  <link href="../../../../css/notificaciones.css" rel="stylesheet">
  <link href="../../../../css/lobibox.min.css" rel="stylesheet">

  <!-- JS -->
  <script src="../../../../vendor/jquery/jquery.min.js"></script>
  <script src="../../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../../../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../../../../js/sb-admin-2.min.js"></script>
  <script src="../../../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../../../vendor/datatables/dataTables.buttons.js"></script>
  <script src="../../../../vendor/datatables/buttons.html5.min.js"></script>
  <script src="../../../../vendor/jszip/jszip.min.js"></script>
  <script src="../../../../js/notificaciones_timlid.js" charset="utf-8"></script>
  <script src="js/requisicionCompra.js"></script>
  <script src="js/index.js" charset="utf-8"></script>

</head>

<body id="page-top" class="sidebar-toggled">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <?php
      $ruta = "../../../";
      $ruteEdit = $ruta . "central_notificaciones/";
      require_once $ruta . 'menu3.php';

      //verificacion de la variable para las notificaciones desde otra pantalla
      if (isset($_SESSION["actualizadoRC"])) {
        echo ('<input type="hidden" id="notifi" value="' . $_SESSION["actualizadoRC"] . '">');
        unset($_SESSION["actualizadoRC"]);
      } else {
        echo ('<input type="hidden" id="notifi" value="0">');
      }
    ?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
      <input type="hidden" id="txtUsuario" value="<?=$_SESSION['PKUsuario'];?>">
      <input type="hidden" id="txtRuta" value="<?=$ruta;?>">
      <input type="hidden" id="txtEdit" value="<?=$ruteEdit;?>">

      <!-- Main Content -->
      <div id="content">

        <?php
            $rutatb = "../../../";
            $icono = '../../../../img/icons/ICONO ORDENES DE COMPRA-01.svg';
            $titulo = 'Requisiciones de compra';
            require_once $rutatb . 'topbar.php';
        ?>

        <!-- Begin Page Content -->
        <div class="container-fluid">
          <!-- DataTales Example -->
          <div class="card mb-4">
            <div class="card-body">
              <div class="table-responsive">
                <table class="table" id="tblRequisicionesCompra" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Id</th>
                      <th>Folio</th>
                      <th>Fecha de emisión</th>
                      <th>Fecha estimada de entrega</th>
                      <th>Comprador</th>
                      <th>Aplicado por:</th>
                      <th>Area/Departamento</th>
                      <th>Estado</th>
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
  
  <?php
  require_once 'modal_alert.php';
  ?>
  
  <script src="../../../../js/sb-admin-2.min.js"></script>
  <script src="../../../../js/scripts.js"></script>
</body>
<script src="../../../../js/Sortable.js"></script>
<script src="../../../../js/pagination/pagination.js"></script>
<script src="../../../../js/lobibox.min.js"></script>
<script src="js/scriptNotificaciones.js"></script>

</html>