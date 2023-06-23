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
    <title>Timlid | Kardex</title>

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
    <!-- <link rel="stylesheet" href="../../style/inventario_inicial.css"> -->
    <!-- JS -->
    <script src="../../../../vendor/jquery/jquery.min.js"></script>
    <script src="../../../../vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="../../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../../../js/sb-admin-2.min.js"></script>
    <script src="../../../../js/scripts.js"></script>
    <script src="../../../../js/sweet/sweetalert2.js"></script>
    <script src="../../../../vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../../../../vendor/datatables/row().show().js"></script>
    <script src="../../../../vendor/datatables/dataTables.buttons.js"></script>
    <script src="../../../../vendor/datatables/buttons.html5.min.js"></script>
    <script src="../../../../vendor/jszip/jszip.min.js"></script>
    <script src="../../../../js/notificaciones_timlid.js" charset="utf-8"></script>

    <style>
        /*  #tblInventariosIniciales tbody tr td {
            vertical-align: middle;
        } */
    </style>
</head>

<body id="page-top" class="sidebar-toggled">
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
                $icono = '../../../../img/inventarios/INVENTARIO INICIAL.svg';
                $titulo = 'Kardex';
                require_once $rutatb . 'topbar.php';
                ?>

                <!-- Begin Page Content -->
                <input type="hidden" id="txtUsuario" value="<?= $_SESSION['PKUsuario']; ?>">
                <input type="hidden" id="txtRuta" value="<?= $ruta; ?>">
                <input type="hidden" id="txtEdit" value="<?= $ruteEdit; ?>">
                <input type="hidden" id="txtPantalla" value="<?= $screen; ?>">

                <div class="container-fluid">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="row">
                            <div class="col">
                                    <div class="form-group">
                                        <label for="cmbTipoReporte" id="lblTipoReporte">Tipo de reporte:</label>
                                        <select class="form-select" id="cmbTipoReporte" placeholder="Seleccionar tipo de reporte" required>
                                            <option data-placeholder="true"></option>
                                            <option value="1">Reporte general</option>
                                            <option value="2">Reporte detallado</option>
                                        </select>
                                        <div class="invalid-feedback d-none" id="invalid-tipoReporte">Elige un tipo de reporte.</div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="cmbClave" id="lblClave">Clave:</label>
                                        <select class="form-select" id="cmbClave" placeholder="Seleccionar clave" multiple required>
                                        </select>
                                        <div class="invalid-feedback d-none" id="invalid-claves">Elige al menos una clave.</div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="cmbSucursal" id="lblSucursal">Sucursal:</label>
                                        <select class="form-select" id="cmbSucursal" placeholder="Seleccionar sucursal" required>
                                        </select>
                                        <div class="invalid-feedback d-none" id="invalid-sucursal">Elige una sucursal.</div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="cmbTipoMovimiento" id="lblTipoMovimiento">Tipo de movimiento:</label>
                                        <select class="form-select" id="cmbTipoMovimiento" name="cmbTipoMovimiento" placeholder="Seleccionar tipo de movimiento">
                                            <option data-placeholder="true"></option>
                                            <option value="0">Todos</option>
                                            <option value="1">Entradas</option>
                                            <option value="2">Salidas</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="txtDateFrom">De:</label>
                                        <input class="form-control" type="date" name="inputDateDe" id="inputDateDe">
                                        <div class="invalid-feedback d-none" id="invalid-fechaDe">Elige una fecha.</div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="txtDateFrom">Hasta:</label>
                                        <input class="form-control" type="date" name="inputDateHasta" id="inputDateHasta">
                                        <div class="invalid-feedback d-none" id="invalid-fechaHasta">Elige una fecha.</div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <button class="btn-custom btn-custom--blue" type="button" id="btnFinalizar">Filtrar</button>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table d-none" id="tblReporteGeneral" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th class="d-none">Id</th>
                                            <th>Clave</th>
                                            <th>Descripción</th>
                                            <th>Lote</th>
                                            <th>Serie</th>
                                            <th>Caducidad</th>
                                            <th>Inventario inicial</th>
                                            <th>Entradas</th>
                                            <th>Salidas</th>
                                            <th>Cantidad sistema</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                            <div class="table-responsive">
                                <table class="table d-none" id="tblReporteDetallado" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th class="d-none">Id</th>
                                            <th>Clave</th>
                                            <th>Descripción</th>
                                            <th>Lote</th>
                                            <th>Serie</th>
                                            <th>Caducidad</th>
                                            <th>Entradas</th>
                                            <th>Salidas</th>
                                            <th>Cantidad sistema</th>
                                            <th>Referencia</th>
                                            <th>Tipo de movimiento</th>
                                            <th>Usuario</th>
                                            <th>Observaciones</th>
                                            <th>Fecha</th>
                                            <th class="d-none">Folio Venta/Cotización</th>
                                            <th class="d-none">Motivo</th>
                                        </tr>
                                    </thead>
                                </table>
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

    </div>
    <script src="../../../../js/slimselect.min.js"></script>
    <script src="../../../../js/Sortable.js"></script>
    <script src="../../../../js/pagination/pagination.js"></script>
    <script src="../../../../js/lobibox.min.js"></script>
    <script src="../../../../js/sweet/sweetalert2.js"></script>
    <script src="js/kardex.js" charset="utf-8"></script>
    <script>
        loadAlertsNoti('alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
        setInterval(loadAlertsNoti, 10000, 'alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
    </script>
</body>

</html>