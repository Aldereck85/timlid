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
    <title>Timlid | Inventario inicial</title>

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
    <script src="../../js/inventario_inicial.js" charset="utf-8"></script>

    <style>
        /*  #tblInventariosIniciales tbody tr td {
            vertical-align: middle;
        } */
    </style>
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
                    display: none;" id="loaderValidacion">
        <h3 style="margin: 20% 38% 0%">Validando archivo...</h3>
    </div>
    <!-- Imagen de fondo de cargando datos-->
    <div style="position: fixed;
                    left: 0px;
                    top: 0px;
                    width: 100%;
                    height: 100%;
                    z-index: 9999;
                    background: url('../../../../img/timdesk/Preloader.gif') 50% 50% no-repeat rgb(249,249,249);
                    opacity: .6;
                    display: none;" id="loaderImportacion">
        <h3 style="margin: 20% 38% 0%">Importando archivo...</h3>
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
                $icono = '../../../../img/inventarios/INVENTARIO INICIAL.svg';
                $titulo = 'Inventario inicial';
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
                                <div class="col-12 col-md-6">
                                    <div class="row">
                                        <div class="col-12 col-md-6">
                                            <div class="form-group">
                                                <label for="cmbSucursales" id="lblSucursal">Sucursal:</label>
                                                <select class="form-select" id="cmbSucursales" placeholder="Seleccionar sucursal">
                                                    <option disabled selected>Seleccionar sucursal</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <div class="form-group">
                                                <label for="cmbCategorias" id="lblCategoria">Categorias:</label>
                                                <select class="form-select" id="cmbCategorias" name="cmbCaregoria" placeholder="Seleccionar categoria" multiple>

                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 d-flex justify-content-end align-items-center">
                                    <button class="btn-custom btn-custom--border-blue mr-3" type="button" id="btnGuardar">Guardar previo</button>
                                    <button class="btn-custom btn-custom--blue" type="button" id="btnFinalizar">Finalizar inventario</button>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table" id="tblInventariosIniciales" class="display" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>IdDetalle</th>
                                            <th>Id</th>
                                            <th>Clave</th>
                                            <th>Nombre</th>
                                            <th>Descripción</th>
                                            <th>Cantidad</th>
                                            <!--<th>Serie</th>-->
                                            <th>Lote</th>
                                            <th>Caducidad</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                            <div class="col d-flex justify-content-end align-items-end my-3">
                                <button class="btn-custom btn-custom--border-blue mr-3" type="button" id="btnGuardarFot">Guardar previo</button>
                                <button class="btn-custom btn-custom--blue" type="button" id="btnFinalizarFot">Finalizar inventario</button>
                            </div>
                        </div>
                    </div>

                    <!-- /.container-fluid-->

                </div>
                <!-- End of Main Content -->
            </div>
            <!-- End of Content Wrapper -->
            <!-- Footer -->
            <?php
            $rutaf = "../../../";
            require_once $rutaf . 'footer.php';
            ?>
            <!-- End of Footer -->
        </div>
        <!-- End of Page Wrapper -->
        <!-- EXCEL MODAL-->
        <div class="modal fade" id="excelmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Inventario Inicial</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">x</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="container">
                            <div class="row">
                                <div class="col-12">
                                    <img src="../../../../img/inventarios/excel.jpeg" style="width: 100%;">
                                    <a href="exportLayout.php" class="mt-3">Descargar layout</a><br>
                                </div>
                                <div class="col-12 mt-1 mb-2">
                                    <span class="text-danger">*Formato correcto del documento</span>
                                    <span class="text-danger d-block">*Formatos aceptados: .XLS, .XLSX</span>
                                    <span class="text-danger d-block">*Los datos actuales serán sustituidos</span>
                                </div>
                                <div class="col-12">
                                    <form action="upload.php" method="post" enctype="multipart/form-data" id="formexcel">
                                        <input type="hidden" name="valorSucursal" id="valorSucursal">
                                        <input type="file" class="btn-custom btn-custom--blue" id="dataexcel" name="dataexcel" accept=".xls,.xlsx">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-around">
                        <button class="btn-custom btn-custom--border-blue" data-dismiss="modal">Cancelar</button>
                        <button class="btn-custom btn-custom--blue" id="importExcel" data-dismiss="modal" onclick="validarExcel()">Importar</button>
                    </div>
                </div>
            </div>
        </div>
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