<?php
session_start();

if (isset($_SESSION["Usuario"])) {
    require_once '../../include/db-conn.php';
    $user = $_SESSION["Usuario"];
} else {
    header("location:../dashboard.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <link rel="icon" type="image/png" href="../../img/header/bluTimlid.png">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Timlid | Cuentas Por pagar</title>

  <!-- Bootstrap core JavaScript-->
  <script src="../../vendor/jquery/jquery.min.js"></script>
  <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="js\data_index.js"></script>
  <!-- Core plugin JavaScript-->
  <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../../js/sweet/sweetalert2.js"></script>
  <script src="../../js/validaciones.js"></script>
  <script src="../../js/lobibox.min.js"></script>
  <script src="../../js/slimselect.min.js"></script>

  <!-- Page level plugins -->
  <script src="../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../vendor/datatables/dataTables.bootstrap4.min.js"></script>
  <script src="../../vendor/datatables/dataTables.buttons.js"></script>
  <script src="../../vendor/datatables/buttons.bootstrap4.min.js"></script>
  <script src="../../vendor/datatables/buttons.html5.min.js"></script>
  <script src="../../vendor/jszip/jszip.min.js"></script>
  <script src="js/cuentas_Proveer.js"></script>
  


  <!-- Custom fonts for this template -->
  <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet">

  <!-- Custom styles for this page -->
  <link href="../../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../vendor/datatables/buttons.dataTables.css" rel="stylesheet">
  <link href="../../vendor/datatables/buttons.bootstrap4.min.css" rel="stylesheet">
  <link href="../../css/styles.css" rel="stylesheet">
  <link href="../../css/stylesTable.css" rel="stylesheet">
  <link href="../../css/stylesModal-lg.css" rel="stylesheet">
  <link href="../../css/lobibox.min.css" rel="stylesheet">
  <link href="../../css/slimselect.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">

  <script src="https://cdn.datatables.net/searchbuilder/1.1.0/js/dataTables.searchBuilder.min.js"></script>
  <script src="https://cdn.datatables.net/datetime/1.1.0/js/dataTables.dateTime.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
 <!--  <link href="https://cdn.datatables.net/searchbuilder/1.1.0/css/searchBuilder.dataTables.min.css" rel="stylesheet"> -->
  <link href="https://cdn.datatables.net/datetime/1.1.0/css/dataTables.dateTime.min.css" rel="stylesheet">

  <!-- Personales -->
  <link href="css\menus.css" rel="stylesheet">
  <link href="css\searchbuilder.css" rel="stylesheet">
  <style>
    .dtsb-add.dtsb-button{
      background: red;
    }
  </style>
  <link rel="stylesheet" href="../../css/notificaciones.css">
  <script src="../../js/notificaciones_timlid.js" charset="utf-8"></script>
</head>

<body id="page-top" class="sidebar-toggled">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Content Wrapper -->
    <!-- Sidebar -->
    <?php
      $titulo = "Cuentas por pagar";
      $ruta = "../";
      $ruteEdit = $ruta . "central_notificaciones/";
      require_once '../menu3.php';
    ?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <?php
          $rutatb = "../";
          $icono = '../../img/icons/CUENTAS POR PAGAR.svg';
          require_once "../topbar.php"
        ?>
        <!-- End of Topbar -->
        <input type="hidden" id="txtUsuario" value="<?=$_SESSION['PKUsuario'];?>">
    <input type="hidden" id="txtRuta" value="<?=$ruta;?>">
    <input type="hidden" id="txtEdit" value="<?=$ruteEdit;?>">
        <!-- Begin Page Content -->
        <div class="container-fluid">
        <!-- Comprobar permisos para estar en la pagina -->
        <?php
            ///Primera parte comprueba si puede ver
                $pkuser = $_SESSION["PKUsuario"];
                $stmt = $conn->prepare("Select funcion_ver, funcion_agregar, funcion_editar, funcion_eliminar, funcion_exportar, 
                pantalla_id, fp.perfil_id from funciones_permisos as fp inner join usuarios as us 
                on fp.perfil_id = us.perfil_id where us.id = $pkuser and pantalla_id = 61");
                    $stmt->execute();
                    $row = $stmt->fetch();
                    //Ponemos en el DOM el permiso ver
                    echo ('<input id="ver" type="hidden" value="'.$row['funcion_ver'].'">');
          ?>

          <!-- Page Heading -->
          <!--<h1 class="h3 mb-2 text-gray-800">Control vehicular</h1>
          <p class="mb-4">Información general de los vehiculos</p>-->
          <input type="hidden" id="proveedor_id" value="<?php echo ($_GET["id"]); ?>" />
          <input type="hidden" id="periodo" value="<?php echo (Int)($_GET['periodo']); ?>" />
          <input type="hidden" id="toggle" value="<?php echo (Int)($_GET['av']); ?>" />
          <!-- DataTales Example -->
          <div class="card mb-4">
            <div class="card-header py-3">
                  Tabla de cuenta del proveedor 
                  <div  id="mod" class="btn-group float-right" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-secondary">Modulo de Pagos</button>
                    <button type="button" class="btn btn-secondary">Notas de Crédito</button>
                    <a href=""><img class="delete-icon" id="delete-icon-361" src="../../img/timdesk/delete.svg"></a>
                  </div>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table" id="tblVehiculos" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Proveedor</th>
                      <th>Folio de Factura</th>
                      <th>Serie de Factura</th>
                      <th>Subtotal</th>
                      <th>Importe</th>
                      <th>F. de Expedicion</th>
                      <th>F. de Vencimiento</th>
                      <th>Vencimiento</th>
                      <th>Estatus</th>
                      <th>Editar</th>
                    </tr>
                  </thead>
                </table>
              </div>
              <a type="button" class="btn-custom btn-custom--border-blue btnCancelarActualizacion" href="../cuentas_pagar">Regresar</a>

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
  <script>
  loadAlertsNoti('alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
  setInterval(loadAlertsNoti, 10000, 'alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit')
    .val());
  </script>
  <?php
    require_once 'modal_alert.php';
  ?>
  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>


</body>


</html>