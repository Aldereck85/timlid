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

if (isset($_POST["data1"])) {
    $cmbsucursal = $_POST["data1"];
    $cmbtipo = $_POST["data2"];
    $cmbfolio =  $_POST["data3"];
} else {
    $cmbsucursal = 'no';
    $cmbtipo = 'no';
    $cmbfolio =  'no';
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" type="image/png" href="../../../../img/header/bluTimlid.png">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Timlid | Cambio de lote</title>

    <!-- ESTILOS -->
    <link href="../../../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="../../../../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../../../../css/slimselect.min.css" rel="stylesheet">
    <link href="../../../../css/sweetalert2.css" rel="stylesheet">
    <link href="../../../../css/pagination/pagination.css" rel="stylesheet">
    <link href="../../../../css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="../../../../vendor/datatables/responsive.dataTables.css" rel="stylesheet">
    <link href="../../../../vendor/datatables/buttons.dataTables.css" rel="stylesheet">
    <link href="../../../../css/styles.css" rel="stylesheet">
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
    <script src="../../js/cambio_lote.js" charset="utf-8"></script>
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
                $icono = '../inventarios/INVENTARIO INICIAL.svg';
                $titulo = 'Cambio de lote';
                require_once $rutatb . 'topbar.php';
                ?>

                <!-- Begin Page Content -->
                <input type="hidden" id="txtUsuario" value="<?= $_SESSION['PKUsuario']; ?>">
                <input type="hidden" id="txtRuta" value="<?= $ruta; ?>">
                <input type="hidden" id="txtEdit" value="<?= $ruteEdit; ?>">
                <input type="hidden" id="txtPantalla" value="<?= $screen; ?>">

                <input type="hidden" id="inputCmbSucursal" value="<?= $cmbsucursal; ?>">
                <input type="hidden" id="inputCmbTipo" value="<?= $cmbtipo; ?>">
                <input type="hidden" id="inputCmbFolio" value="<?= $cmbfolio; ?>">

                <div class="container-fluid">

                    <div class="row">
                        <div class="col">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            <form class="my-3">
                                                <label for="cmbSucursales" id="lblSucursal">Sucursal:</label>
                                                <select class="form-select" id="cmbSucursales" aria-label="Default select example" placeholder="Seleccionar sucursal">
                                                    <option data-placeholder="true"></option>
                                                    <option value="0">Todas</option>
                                                </select>
                                            </form>
                                        </div>
                                        <div class="col">
                                            <form class="my-3">
                                                <label for="cmbTipos" id="lblTipo">Tipo de cambio:</label>
                                                <select class="form-select" id="cmbTipos" aria-label="Default select example" placeholder="Seleccionar tipo de ajuste">
                                                    <option data-placeholder="true"></option>
                                                    <option value="3">Todos</option>
                                                    <option value="lote">Lote</option>
                                                    <option value="serie">Serie</option>
                                                </select>
                                            </form>
                                        </div>
                                        <div class="col">
                                            <form class="my-3">
                                                <label for="cmbFolios" id="lblFolio">Folio:</label>
                                                <select class="form-select" id="cmbFolios" aria-label="Default select example" placeholder="Seleccionar folio">

                                                </select>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col d-flex justify-content-start align-items-start">
                                            <!-- BOTON AGREGAR -->
                                            <button class="btn-custom btn-custom--white-dark" title="Agregar cambio" type="button" id="btnCambio" data-toggle="modal" data-target="#modal_lot_change"><img src="../../../../img/icons/ICONO_AGREGAR_DARK.svg" width="20" class="mr-1"> Nuevo cambio</button>
                                        </div>
                                    </div>


                                    <div class="row" id="rowTabla">
                                        <div class="col">
                                            <div class="table-responsive">
                                                <table class="table" id="tblCambios" class="display" width="100%" cellspacing="0">
                                                    <thead>
                                                        <tr>
                                                            <th class="d-none">IdCambio</th>
                                                            <th>Sucursal</th>
                                                            <th>Fecha de captura</th>
                                                            <th>Usuario</th>
                                                            <th>Folio</th>
                                                            <th>Tipo de cambio</th>
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
    </div>

<div class="modal fade" id="modal_lot_change" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row" style="margin-left:-1.49rem;margin-right:-1.49rem">
                    <div class="col-9">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group row">
                                    <div class="col">
                                        <label for="cmbSucursalesModal" id="lblSucursalModal">Sucursal:</label>
                                        <select class='form-select' id='cmbSucursalesModal'>
                                            <option data-placeholder='true'></option>
                                        </select>
                                    </div>
                                    <!-- <div class="col-6">
                                        <label for='cmbTiposModal' id='lblTipoModal'>Tipo de cambio:</label>
                                        <select class='form-select' id='cmbTiposModal'>
                                            <option data-placeholder='true'></option>

                                            <option value='1' selected>Lote</option>
                                            <option value='0'>Serie</option>
                                        </select>
                                    </div> -->
                                </div>
                               
                                <div class="d-flex justify-content-around" style="margin-top:5rem">
                                    
                                    <button type="button" class="btn-custom btn-custom--blue btn-aceptar" onclick="insertarCambios();"> Aceptar</button>
                                    <button type="button" class="btn-custom btn-custom--border-blue" data-dismiss="modal"> Cancelar</button>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                    <div class="col-3 d-flex align-items-center" style="background-color:#1880e8;margin-top:-1.50rem;margin-bottom:-1.50rem;">
                        <div class="row">
                            <div class="card" style="background-color:#1880e8;width:12.5rem">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            <img src="../../../../img/inventarios/cambio_lote.svg" alt="">
                                        </div> 
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <h4 class="text-center" style="color:#ffff">Cambio de lote</h4>
                                        </div> 
                                    </div>
                                </div>
                            </div> 
                        </div>
                        
                        
                        
                    </div>
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

        $(function() {
            $("[data-toggle='tooltip']").tooltip({
                trigger: "hover focus click",
            });
        });

        var sucursalcmb = '<?php echo $cmbsucursal; ?>';
        var tipocmb = '<?php echo $cmbtipo; ?>';
        var foliocmb = '<?php echo $cmbfolio; ?>';
    </script>
</body>

</html>