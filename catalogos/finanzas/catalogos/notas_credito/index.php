<?php
session_start();

if (isset($_SESSION["Usuario"])) {

    $user = $_SESSION["Usuario"];

    $pkusuario = $_SESSION["PKUsuario"];
    $ruta = "../../../";
    $screen = 8;
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
    <title>Timlid | Notas de crédito</title>

    <!-- ESTILOS -->
    <link href="../../../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="../../../../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../../../../css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="../../../../vendor/datatables/buttons.dataTables.css" rel="stylesheet">
    <link href="../../../../css/styles.css" rel="stylesheet">
    <link href="../../../../css/stylesNewTable.css" rel="stylesheet">
    <link href="../../../../css/lobibox.min.css" rel="stylesheet">
    <link href="../../../../css/notificaciones.css" rel="stylesheet">
    <script src="../../../../vendor/jquery/jquery.min.js"></script>
    <script src="../../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../../../vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="../../../../js/sb-admin-2.min.js"></script>
    <script src="../../../../vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../../../../vendor/datatables/dataTables.buttons.js"></script>
    <script src="../../../../vendor/datatables/buttons.html5.min.js"></script>
    <script src="../../../../vendor/jszip/jszip.min.js"></script>
    <script src="../../../../js/lobibox.min.js"></script>
    <script src="../../../../js/notificaciones_timlid.js" charset="utf-8"></script>
    <script src="../../js/notasCredito.js" charset="utf-8"></script>

</head>

<body id="page-top" class="sidebar-toggled">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php
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
            <input type="hidden" id="txtPantalla" value="13">

            <!-- Main Content -->
            <div id="content">

                <?php
                $rutatb = "../../../";
                $icono = 'ICONO-APLICAR-NOTAS-DE-CREDITO-CARGO-AZUL.svg';
                $titulo = 'Notas de crédito';
                require_once $rutatb . 'topbar.php';
                ?>

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <!-- Page Heading -->
                    <!-- DataTales Example -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table" id="tblNotasCredito" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Estatus</th>
                                            <th>Serie/Folio</th>
                                            <th>Proveedor</th>
                                            <th>Importe</th>
                                            <th>Fecha</th>
                                            <th>Factura</th>
                                            <th>Tipo</th>
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

    <script src="../../../../js/sb-admin-2.min.js"></script>
    <script src="../../../../js/scripts.js"></script>
</body>
<script src="../../../../js/Sortable.js"></script>
<script src="../../../../js/pagination/pagination.js"></script>
<script>
    loadAlertsNoti('alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
    setInterval(loadAlertsNoti, 10000, 'alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
</script>

</html>