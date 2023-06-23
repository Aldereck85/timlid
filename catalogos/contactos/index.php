<?php
session_start();
$jwt_ruta = "../../";
require_once '../jwt.php';
if (isset($_SESSION["Usuario"])) {
} else {
    header("location:../dashboard.php");
}
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
    <title>Timlid | <?= $titulo = "CRM" ?></title>

    <!-- ESTILOS -->
    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../../css/slimselect.min.css" rel="stylesheet">
    <link href="../../css/sweetalert2.css" rel="stylesheet">
    <link href="../../css/lobibox.min.css" rel="stylesheet">
    <link href="../../css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="../../vendor/datatables/responsive.dataTables.css" rel="stylesheet">
    <link href="../../vendor/datatables/buttons.dataTables.css" rel="stylesheet">
    <link href="../../css/styles.css" rel="stylesheet">
    <link href="../../css/stylesNewTable.css" rel="stylesheet">
    <!-- <link href="css/index.css" rel="stylesheet"> -->

    <!-- JS -->
    <script src="../../vendor/jquery/jquery.min.js"></script>
    <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="../../js/sb-admin-2.min.js"></script>
    <script src="../../vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../../vendor/datatables/dataTables.buttons.js"></script>
    <script src="../../vendor/datatables/buttons.html5.min.js"></script>
    <script src="../../vendor/jszip/jszip.min.js"></script>
</head>

<body id="page-top" class="sidebar-toggled">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php
        $ruta = "../";
        $ruteEdit = $ruta . "central_notificaciones/";
        require_once $ruta . 'menu3.php';
        ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!--  <input type="" id="txtUsuario" value="<?= $_SESSION['PKUsuario']; ?>">
      <input type="" id="txtRuta" value="<?= $ruta; ?>">
      <input type="" id="txtEdit" value="<?= $ruteEdit; ?>">
      <input type="" id="txtPantalla" value="<?= $screen; ?>">-->

            <!-- Main Content -->
            <div id="content">

                <?php
                $rutatb = "../";
                $icono = 'ICONO-CRM-AZUL.svg';
                require_once $rutatb . 'topbar.php';
                ?>

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <!-- DataTales Example -->
                    <div class="card mb-12">
                        <nav>
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                <a class="nav-item nav-link active" id="nav-tab-activos" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">Prospectos</a>
                                <a class="nav-item nav-link" id="nav-tab-inactivos" data-toggle="tab" href="#nav-contact" role="tab" aria-controls="nav-contact" aria-selected="false">Clientes</a>
                            </div>
                        </nav>
                        <div class="card-body">
                            <div class="tab-content" id="nav-tabContent">
                                <div class="tab-pane fade show active" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                                    <div class="table-responsive">
                                        <table class="table" id="tblContactosNuevos" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>#</th>
                                                    <th>Nombre</th>
                                                    <th>Empresa</th>
                                                    <th>Email</th>
                                                    <th>Contacto Campaña</th>
                                                    <th>Vendedor</th>
                                                    <th>Tipo</th>
                                                    <th>Estatus</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
                                    <div class="table-responsive">
                                        <table class="table" id="tblContactosInactivos" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>id</th>
                                                    <th>Nombre</th>
                                                    <th>Email</th>
                                                    <th>Razón Social</th>
                                                    <th>RFC</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Page Content -->
                </div>
            </div>
            <!-- Footer -->
            <?php
            $rutaf = "../";
            require_once '../footer.php';
            ?>
            <!-- End of Footer -->
        </div>
    </div>
    <!-- End Page Wrapper -->
    <?php include('modales/modal_cliente.php'); ?>
    <script src="../../js/sb-admin-2.min.js"></script>
    <script src="../../js/slimselect.min.js"></script>
    <script src="../../js/sweet/sweetalert2.js"></script>
    <script src="../../js/lobibox.min.js"></script>
    <script src="../../js/validaciones.js"></script>
    <script src="../../js/scripts.js"></script>
    <script src="js/index_activos.js"></script>
    <script src="js/index_inactivos.js"></script>
</body>

</html>