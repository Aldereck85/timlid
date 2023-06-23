<?php
session_start();
if (isset($_SESSION["Usuario"])) {
    require_once '../../../include/db-conn.php';
} else {
    header("location:../../dashboard.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" type="image/png" href="../../../img/header/bluTimlid.png">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Timlid | Reportes</title>

    <!-- ESTILOS -->
    <link href="../../../css/slimselect.min.css" rel="stylesheet">
    <link href="../../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css" async>
    <link href="../../../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../../../css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="../../../vendor/datatables/buttons.dataTables.css" rel="stylesheet">
    <link href="../../../css/styles.css" rel="stylesheet">
    <link href="../../../css/stylesNewTable.css" rel="stylesheet">
    <link href="../../../css/stylesModal-lg.css" rel="stylesheet">
    <link href="../../../css/lobibox.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/datetime/1.1.0/css/dataTables.dateTime.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../../../../css/notificaciones.css">
    <link rel="stylesheet" href="../css/index.css">
    <script src="../../../vendor/jquery/jquery.min.js"></script>
    <script src="../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.4/moment.min.js"></script>
    <script src="https://cdn.datatables.net/datetime/1.1.0/js/dataTables.dateTime.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/plug-ins/1.10.21/sorting/datetime-moment.js"></script>
    <script src="../../../vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="../../../js/sweet/sweetalert2.js"></script>
    <script src="../../../js/validaciones.js"></script>
    <script src="../../../js/lobibox.min.js"></script>
    <script src="../../../js/slimselect.min.js"></script>
    <script src="../../../vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../../../vendor/datatables/dataTables.buttons.js"></script>
    <script src="../../../vendor/datatables/buttons.html5.min.js"></script>
    <script src="../../../vendor/jszip/jszip.min.js"></script>
    
    <script src="https://cdn.datatables.net/searchbuilder/1.1.0/js/dataTables.searchBuilder.min.js"></script>
    <script src="https://cdn.datatables.net/datetime/1.1.0/js/dataTables.dateTime.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
    <script src="../../../js/notificaciones_timlid.js" charset="utf-8"></script>
    <script src="../../../js/jquery.redirect.min.js"></script>

</head>

<body id="page-top" class="sidebar-toggled">

    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Comprobar permisos para estar en la pagina -->
        <?php
        $pkuser = $_SESSION["PKUsuario"];
        require_once "./php/permisos.php";
        ///Primera parte comprueba si puede ver
        ?>
        <!-- Sidebar -->
        <?php
        $titulo = "Reportes";
        $ruta = "../../";
        $ruteEdit = $ruta . "central_notificaciones/";
        require_once '../../menu3.php';
        if (isset($_SESSION["mensaje"])) {
            echo ('<input type="hidden" id="notifi" value="' . $_SESSION["mensaje"] . '">');
            unset($_SESSION['mensaje']);
        } else {
            echo ('<input type="hidden" id=/"notifi/" value="f">');
        }
        ?>

        <!-- End of Sidebar -->
        <div id="content-wrapper" class="d-flex flex-column">
            <input type="hidden" id="txtUsuario" value="<?= $_SESSION['PKUsuario']; ?>">
            <input type="hidden" id="txtRuta" value="<?= $ruta; ?>">
            <input type="hidden" id="txtEdit" value="<?= $ruteEdit; ?>">
            <!-- Main Content -->
            <div id="content">
                <?php
                $rutatb = "../../";
                $icono = '../../../img/icons/CUENTAS POR PAGAR.svg';

                require_once $rutatb . "topbar.php"
                ?>
                <!-- End of Topbar -->
                <!-- Begin Page Content -->

                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <ul class="nav nav-tabs" id="nav-tab" role="tablist">
                                <li class="nav-item">
                                    <a id="reportGraficas" class="nav-item nav-link active" href="#graficas" data-toggle="tab" role="tab" aria-controls="nav-datos" aria-selected="true">
                                        Gráficas
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a id="reportVentas" class="nav-item nav-link" href="#ventas" data-toggle="tab" role="tab" aria-controls="nav-datos" aria-selected="true">
                                        Reporte de Ventas
                                    </a>
                                </li>
                                <li class="nav-item" id="CargarFacturas">
                                    <a  class="nav-item nav-link" href="#facturacion" data-toggle="tab" role="tab" aria-controls="nav-datos" aria-selected="false">
                                        Reporte de Facturación
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a id="CargarConceptos" class="nav-item nav-link " href="#productos" data-toggle="tab" role="tab" aria-controls="nav-datos" aria-selected="false">
                                        Reporte de Productos
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a id="CargarHistorico" class="nav-item nav-link " href="#historico" data-toggle="tab" role="tab" aria-controls="nav-datos" aria-selected="false">
                                        Reporte Histórico Ventas
                                    </a>
                                </li>
                            </ul>
                            <div class="card">
                                <div class="card-body" id="divFiltros" style="display: none;">
                                    <!-- Filtros -->
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-xs-6" style="margin-left:20px;">
                                                <label for="cmbCliente">Cliente:</label>
                                                <select name="cmbCliente" id="cmbCliente" class="form-select" required></select>
                                                <div class="invalid-feedback" id="invalid-cmbCliente">.</div>
                                            </div>
                                            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-xs-6" style="margin-left:20px;">
                                                <label for="cmbVendedor">Vendedor:</label>
                                                <select name="cmbVendedor" id="cmbVendedor" class="form-select" required></select>
                                                <div class="invalid-feedback" id="invalid-cmbVendedor">.</div>
                                            </div>
                                            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-xs-6" style="margin-left:20px;">
                                                <label for="cmbEstado">Estado:</label>
                                                <select name="cmbEstado" id="cmbEstado" class="form-select" required></select>
                                                <div class="invalid-feedback" id="invalid-cmbEstado">.</div>
                                            </div>
                                            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-xs-6" style="margin-left:20px;">
                                                <label for="cmbMarcas">Marcas:</label>
                                                <select name="cmbMarcas" id="cmbMarcas" class="form-select" required></select>
                                                <div class="invalid-feedback" id="invalid-cmbMarcas">.</div>
                                            </div>
                                            <div id="divRelleno" class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-xs-6" style="margin-left:20px;">

                                            </div>
                                            <div id="divProducto" style="display:none;" class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-xs-6" style="margin-left:20px;">
                                                <label for="cmbProductos">Productos:</label>
                                                <select name="cmbProductos" id="cmbProductos" class="form-select" required></select>
                                                <div class="invalid-feedback" id="invalid-cmbProductos">.</div>
                                            </div>
                                            <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-xs-6 " style="margin-left:20px;">
                                                <label for="txtDateFrom">De:</label>
                                                <input class="form-control dateRange" type="date" name="txtDateFrom" id="txtDateFrom">
                                                <div class="invalid-feedback" id="invalid-txtDateFrom"></div>
                                            </div>
                                            <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-xs-6" style="margin-left:20px;">

                                                <label for="txtDateTo">Hasta:</label>
                                                <input class="form-control dateRange" type="date" name="txtDateTo" id="txtDateTo">
                                                <div class="invalid-feedback" id="invalid-txtDateTo">.</div>
                                            </div>
                                            <div id="divMes" class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-xs-6" style="margin-left:20px;">
                                                <label for="cmbMes">Mes:</label>
                                                <select name="cmbMes" id="cmbMes" class="form-select" multiple required></select>
                                                <div class="invalid-feedback" id="invalid-cmbMes">.</div>
                                            </div>

                                            <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-xs-6" style="margin-left:20px;">
                                            </div>
                                            <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-xs-6" style="margin-left:20px;">
                                            </div>
                                            <div id="container-buttons" class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-xs-6" style="margin-left:20px;">
                                                <button data-toggle="tooltip" data-placement="top" title="Aplicar Filtro" disabled="true" class="btn-custom btn-custom--blue" id="btnFiltertable" style="margin-top: 10px!important">Filtrar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Filtros -->

                            <!-- DataTales Example -->
                            <div class="tab-content" id="nav-tabContent">
                                <div class="tab-pane fade show active" id="graficas" role="tabpanel" aria-labelledby="nav-main-tab">
                                    <br>
                                            
                                    <div class="row" id="container-canvas"></div><br>
                                    <div class="row" id="container-canvas1"></div>       
                                       
                                    
                                </div>
                                <div class="tab-pane fade" id="facturacion" role="tabpanel" aria-labelledby="nav-main-tab">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <div id="tabla">

                                                    <table class="table" id="tblreport" width="100%" cellspacing="0">
                                                        <thead>
                                                            <tr>
                                                                <th>Factura</th>
                                                                <th>Folio</th>
                                                                <th>Estado</th>
                                                                <th>Cliente</th>
                                                                <th>Asesor</th>
                                                                <th>Fecha</th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Tab de Productos -->
                                
                                <?php require_once 'reporte_productos.php' ?>
                                <?php require_once 'index_report_ventas.php' ?>
                                <?php require_once 'index_report_historico.php' ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- End of Content Wrapper -->
            <!-- Footer -->
            <?php
            require_once 'modal_goout.php';
            $rutaf = "../../";
            require_once '../../footer.php';
            ?>
            <!-- End of Footer -->
        </div>
    </div>
    <!-- End of Page Wrapper -->
    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">¿En serio quieres salir?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Selecciona "Salir" Para cerrar tu sesión</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                    <a class="btn btn-primary" href="../logout.php">Salir</a>
                </div>
            </div>
        </div>
    </div>
    <?php
    $accion = "eliminar el registro?";
    //require_once 'modal_alert_confirm.php';
    ?>
    <!-- Custom scripts for all pages-->
    <script src="../../../js/sb-admin-2.min.js"></script>
    <script src="../../../js/scripts.js"></script>
    <script>
        loadAlertsNoti('alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
        setInterval(loadAlertsNoti, 10000, 'alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());

        //Muestra la notificacion cada que se recibe la variable por post
        jQuery(function($) {
            var notifi = $("#notifi").val();
            //  console.log(notifi);
            //  console.log(notifi);
            if (notifi == "1") {
                Lobibox.notify("success", {
                    size: "mini",
                    rounded: true,
                    delay: 1500,
                    delayIndicator: false,
                    position: "center top",
                    icon: true,
                    img: "../../img/timdesk/checkmark.svg",
                    msg: "Pago registrado!",
                });
            } else if (notifi == "0") {
                Lobibox.notify("success", {
                    size: "mini",
                    rounded: true,
                    delay: 1500,
                    delayIndicator: false,
                    position: "center top",
                    icon: true,
                    img: "../../img/timdesk/checkmark.svg",
                    msg: "Pago registrado!",
                });
            } else if (notifi == "2") {
                Lobibox.notify("success", {
                    size: "mini",
                    rounded: true,
                    delay: 1500,
                    delayIndicator: false,
                    position: "center top",
                    icon: true,
                    img: "../../img/timdesk/checkmark.svg",
                    msg: "Pago actualizado!",
                });
            } else if (notifi == "3") {
                Lobibox.notify("success", {
                    size: "mini",
                    rounded: true,
                    delay: 1500,
                    delayIndicator: false,
                    position: "center top",
                    icon: true,
                    img: "../../img/timdesk/checkmark.svg",
                    msg: "Anticipo actualizado!",
                });
            } else if (notifi == "4") {
                Lobibox.notify("success", {
                    size: "mini",
                    rounded: true,
                    delay: 1500,
                    delayIndicator: false,
                    position: "center top",
                    icon: true,
                    img: "../../img/timdesk/checkmark.svg",
                    msg: "Pago eliminado!",
                });
            }

        });
    </script>
    <script src="js/index.js"></script>
</body>

</html>