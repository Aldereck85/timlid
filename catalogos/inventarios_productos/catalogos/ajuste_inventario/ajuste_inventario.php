<?php
session_start();

if (isset($_SESSION["Usuario"])) {

    $user = $_SESSION["Usuario"];

    $pkusuario = $_SESSION["PKUsuario"];
    $ruta = "../../../";
    $screen = 8;
    require_once $ruta . '../include/db-conn.php';
    /*require_once $ruta . 'validarPermisoPantalla.php';
if ($permiso === 0) {
header("location:../dashboard.php");
}*/
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

    <title>Timlid | Ajuste de inventario</title>

    <!-- ESTILOS -->
    <link href="../../../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="../../../../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../../../../css/slimselect.min.css" rel="stylesheet">
    <link href="../../../../css/styles.css" rel="stylesheet">
    <link href="../../../../css/sweetalert2.css" rel="stylesheet">
    <link href="../../../../css/pagination/pagination.css" rel="stylesheet">
    <link href="../../../../css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="../../../..//vendor/datatables/responsive.dataTables.css" rel="stylesheet">
    <link href="../../../../vendor/datatables/buttons.dataTables.css" rel="stylesheet">
    <link href="../../../../css/stylesNewTable.css" rel="stylesheet">
    <link href="../../../../css/notificaciones.css" rel="stylesheet">
    <link href="../../../../css/lobibox.min.css" rel="stylesheet">
    <link href="../../style/ajuste_inventario.css" rel="stylesheet">
    <!-- JS -->
    <script src="../../../../vendor/jquery/jquery.min.js"></script>
    <script src="../../../../vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="../../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../../../js/sb-admin-2.min.js"></script>
    <script src="../../../../js/scripts.js"></script>
    <script src="../../../../js/sweet/sweetalert2.js"></script>
    <script src="../../../../vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../../../../vendor/datatables/dataTables.buttons.js"></script>
    <script src="../../../../vendor/datatables/buttons.html5.min.js"></script>
    <script src="../../../../vendor/jszip/jszip.min.js"></script>
    <script src="../../../../js/notificaciones_timlid.js" charset="utf-8"></script>
    <script src="../../../../js/jquery.redirect.min.js"></script>
    <script src="../../js/ajuste_inventario_detalle.js" charset="utf-8"></script>

</head>

<body id="page-top" class="sidebar-toggled">
    <!-- Imagen de fondo de cargando datos-->
    <div style="position: fixed;
                left: 0px;
                top: 0px;
                width: 100%;
                height: 100%;
                z-index: 9999;
                background: url('../../../../img/timdesk/Preloader.gif') 50% 50% no-repeat rgb(249,249,249);
                opacity: .6;
                display: none;" id="loader">
    </div>
    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php
        //$ruta = "../../../";
        $ruteEdit = "$ruta.central_notificaciones/";
        require_once $ruta . 'menu3.php';
        ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <?php
                $rutatb = "../../../";
                $icono = 'ICONO-AJUSTES-DE-INVENTARIO-AZUL.svg';
                $titulo = 'Ajuste de inventario';
                $backIcon = true;
                require_once $rutatb . 'topbar.php';
                ?>

                <!-- Begin Page Content -->
                <input type="hidden" id="txtUsuario" value="<?= $_SESSION['PKUsuario']; ?>">
                <input type="hidden" id="txtRuta" value="<?= $ruta; ?>">
                <input type="hidden" id="txtEdit" value="<?= $ruteEdit; ?>">
                <input type="hidden" id="txtPantalla" value="<?= $screen; ?>">

                <input type="hidden" id="inputSucursal" value="<?= $_REQUEST['data1']; ?>">
                <input type="hidden" id="inputAjuste">
                <input type="hidden" id="inputTipoAjuste" value="<?= $_REQUEST['data2']; ?>">
                <input type="hidden" id="inputFolioAjuste">

                <div class="container-fluid">

                    <div class="row">
                        <div class="col">
                            <div class="card mb-4">
                                
                                <div class="card-body">

                                    <div class="row">
                                        <div class="col-12 col-md-4">
                                            <div class="row d-flex align-items-center">
                                                <div class="col-10">
                                                    <div class="form-group">
                                                        <label for="txtBusqueda">Buscar productos:</label>
                                                        <input class="form-control" type="text" id="txtBusqueda" placeholder="Buscar..." onkeypress="buscar(event)">
                                                    </div>
                                                </div>
                                                <div class="col-2">
                                                    <button class="btn-custom btn-custom--blue" id="btnBusqueda">Buscar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row" id="rowTabla">
                                        <div class="col">
                                            <div class="table-responsive">
                                                <table class="table dataTable no-footer" id="tblAjustesDetalle" class="display" width="100%" cellspacing="0">
                                                    <thead>
                                                        <tr>
                                                            <th class="d-none">IdExistencia</th>
                                                            <th class="d-none">IdProducto</th>
                                                            <th>Clave</th>
                                                            <th>Nombre</th>
                                                            <th>U. Medida</th>
                                                            <!--<th>Serie</th>-->
                                                            <th>Lote</th>
                                                            <th>Caducidad</th>
                                                            <th>Existencia</th>
                                                            <th>Cantidad</th>
                                                            <th>Motivo</th>
                                                            <th>Comentarios</th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-n5">
                                        <div class="col"></div>
                                        <div class="col"></div>
                                        <div class="col d-flex justify-content-end align-items-end mt-n5">
                                            <button class="btn-custom btn-custom--blue" id="btnAjustar">Guardar</button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- /.container-fluid-->

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

        <!-- Begin modal product checker -->


</body>
<script src="../../../../js/slimselect.min.js"></script>
<script src="../../../../js/Sortable.js"></script>
<script src="../../../../js/pagination/pagination.js"></script>
<script src="../../../../js/lobibox.min.js"></script>
<script src="../../../../js/sweet/sweetalert2.js"></script>
<script>
    loadAlertsNoti('alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
    setInterval(loadAlertsNoti, 10000, 'alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
</script>

</html>