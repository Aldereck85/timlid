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
    <link href="../../../../vendor/datatables/responsive.dataTables.css" rel="stylesheet">
    <link href="../../../../vendor/datatables/buttons.dataTables.css" rel="stylesheet">
    <link href="../../../../css/stylesNewTable.css" rel="stylesheet">
    <link href="../../../../css/notificaciones.css" rel="stylesheet">
    <link href="../../../../css/lobibox.min.css" rel="stylesheet">
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
    <script src="../../js/ajuste_movimientos.js" charset="utf-8"></script>

</head>

<body id="page-top" class="sidebar-toggled">
    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php
        //$ruta = "../../../";
        $ruteEdit = "$ruta.central_notificaciones/";
        $backIcon = true;
        $backRoute = '#';
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
                $titulo = 'Ajuste de inventario - Movimientos';
                require_once $rutatb . 'topbar.php';

                if (isset($_REQUEST["data1"])) {
                    $id_ajuste = $_REQUEST["data1"];
                    $sucursal = $_REQUEST["data2"];
                    $fecha_captura = $_REQUEST["data3"];
                    $usuario = $_REQUEST["data4"];
                    $folio = $_REQUEST["data5"];
                    $tipo_ajuste = $_REQUEST["data6"];
                    $GLOBALS['cmbsucursal'] = $_REQUEST["data7"];
                    $GLOBALS['cmbtipo'] = $_REQUEST["data8"];
                    $GLOBALS['cmbfolio'] = $_REQUEST["data9"];
                } else {
                    $id_ajuste = '0';
                    $sucursal = '0';
                    $fecha_captura = '0';
                    $usuario = '0';
                    $folio = '0';
                    $tipo_ajuste = '0';
                    $cmbsucursal = '0';
                    $cmbtipo = '3';
                    $cmbfolio = '0';
                }

                ?>

                <!-- Begin Page Content -->
                <input type="hidden" id="txtUsuario" value="<?= $_SESSION['PKUsuario']; ?>">
                <input type="hidden" id="txtRuta" value="<?= $ruta; ?>">
                <input type="hidden" id="txtEdit" value="<?= $ruteEdit; ?>">
                <input type="hidden" id="txtPantalla" value="<?= $screen; ?>">

                <input type="hidden" id="inputAjuste" value="<?= $_REQUEST['data1']; ?>">
                <input type="hidden" id="inputSucursal" value="<?= $_REQUEST['data2']; ?>">
                <input type="hidden" id="inputFechaCaptura" value="<?= $_REQUEST['data3']; ?>">
                <input type="hidden" id="inputUsuario" value="<?= $_REQUEST['data4']; ?>">
                <input type="hidden" id="inputFolio" value="<?= $_REQUEST['data5']; ?>">
                <input type="hidden" id="inputTipoAjuste" value="<?= $_REQUEST['data6']; ?>">
                <input type="hidden" id="inputCmbSucursal" value="<?= $_REQUEST['data7']; ?>">
                <input type="hidden" id="inputCmbTipo" value="<?= $_REQUEST['data8']; ?>">
                <input type="hidden" id="inputCmbFolio" value="<?= $_REQUEST['data9']; ?>">

                <div class="container-fluid">

                    <div class="row">
                        <div class="col">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row mb-4">
                                    <div class="col">
                                            <p>
                                                <b class="textBlue">Sucursal: </b>
                                                <span><?= $sucursal ?></span>
                                            </p>
                                        </div>
                                        <div class="col">
                                            <p>
                                                <b class="textBlue">Fecha de captura: </b>
                                                <span><?= $fecha_captura ?></span>
                                            </p>
                                            
                                        </div>
                                        <div class="col">
                                            <p>
                                                <b class="textBlue">Usuario: </b>
                                                <span><?= $usuario ?></span>
                                            </p>
                                            
                                        </div>
                                        <div class="col">
                                            <p>
                                                <b class="textBlue">Folio: </b>
                                                <span><?= $folio ?></span>
                                            </p>
                                        </div>
                                        <div class="col">
                                            <p>
                                                <b class="textBlue">Tipo: </b>
                                                <span><?= $tipo_ajuste ?></span>
                                            </p>
                                            
                                        </div>
                                    </div>
                                    <div class="row" id="rowTabla">
                                        <div class="col">
                                            <div class="table-responsive">
                                                <table class="table" id="tblMovimientosAjuste" class="display" width="100%" cellspacing="0">
                                                    <thead>
                                                        <tr>
                                                            <th class="d-none">IdAjusteDetalle</th>
                                                            <th>Clave</th>
                                                            <th>Nombre</th>
                                                            <!--<th>Serie</th>-->
                                                            <th>Lote</th>
                                                            <th>Caducidad</th>
                                                            <th>Cantidad</th>
                                                            <th>Motivo</th>
                                                            <th>Comentarios</th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
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
    </div>
    <script src="../../../../js/slimselect.min.js"></script>
    <script src="../../../../js/Sortable.js"></script>
    <script src="../../../../js/pagination/pagination.js"></script>
    <script src="../../../../js/lobibox.min.js"></script>
    <script src="../../../../js/sweet/sweetalert2.js"></script>
    <script>
        loadAlertsNoti('alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
        setInterval(loadAlertsNoti, 10000, 'alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
    </script>
</body>

</html>