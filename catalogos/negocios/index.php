<?php
session_start();

if (isset($_SESSION["Usuario"])) {
    require_once '../../include/db-conn.php';
    $user = $_SESSION["Usuario"];
    $pkususario = $_SESSION["PKUsuario"];
    $ruta = "../";
    $screen = 57;
    $titulo = 'Negocios';
    // require_once $ruta . 'validarPermisoPantalla.php';
    // if($permiso === 0){
    //   header("location:../dashboard.php");
    // }
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

    <title>Timlid | <?= $titulo ?></title>

    <!-- Bootstrap core JavaScript-->
    <script src="../../vendor/jquery/jquery.min.js"></script>
    <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../../js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="../../vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../../vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="../../vendor/datatables/dataTables.buttons.js"></script>
    <script src="../../vendor/datatables/buttons.bootstrap4.min.js"></script>
    <script src="../../vendor/datatables/buttons.html5.min.js"></script>
    <script src="../../vendor/jszip/jszip.min.js"></script>

    <!-- Custom fonts for this template -->
    <link href="../../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

    <!-- Custom styles for this template -->
    <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../../vendor/datatables/buttons.dataTables.css" rel="stylesheet">
    <link href="../../vendor/datatables/buttons.bootstrap4.min.css" rel="stylesheet">
    <link href="../../css/stylesNewTable.css" rel="stylesheet">
    <link href="../../css/slimselect.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../../css/sweetalert2.css">
    <link href="../../css/lobibox.min.css" rel="stylesheet">
    <link href="../../css/styles.css" rel="stylesheet">

    <script src="https://unpkg.com/sortablejs-make/Sortable.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-sortablejs@latest/jquery-sortable.js"></script>

    <script src="js/index.js"></script>
    <script src="js/money_format.js"></script>
    <link href="css/index.css" rel="stylesheet">

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
            <input type="hidden" id="txtUsuario" value="<?= $_SESSION['PKUsuario']; ?>">
            <input type="hidden" id="txtRuta" value="<?= $ruta; ?>">
            <input type="hidden" id="txtEdit" value="<?= $ruteEdit; ?>">
            <input type="hidden" id="txtPantalla" value="<?= $screen; ?>">

            <!-- Main Content -->
            <div id="content">

                <?php
                $rutatb = "../";
                $icono = 'ICONO-NEGOCIOS-AZUL.svg';
                require_once $rutatb . 'topbar.php';

                include "./php/negocio.component.php";
                ?>

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <div class="card" style="height: 100%">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-12 mb-4">
                                    <a class="btn-table-custom buttons-excel buttons-html5 btn-table-custom--blue mr-4" href="agregar_negocio.php" id="btn-contatoAdd"><i class="fas fa-plus-square"></i> Agregar negocio</a>
                                </div>
                            </div>
                            <div class="row align-items-center position-relative">
                                <div class="col-lg-1 mr-3">
                                    <button class="btn btn-sm dropdown-toggle btn-columns" onclick="mostrarEtapas(event)">
                                        Editar Etapas
                                    </button>
                                    <div class="listaColumnas position-absolute d-flex flex-column invisible mt-2">
                                        <div id="lista-etapas">
                                        </div>
                                        <div class="columns_modal cursor-pointer" data-toggle="modal" data-target="#AgregarEtapa">
                                            Nueva Etapa
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class="col-lg-4">
                                    <label for="">Selecciona un vendedor:</label>
                                    <select name="" id="propietarioNegocio" onchange="getNegocioByEmpleado(this)">
                                        <option data-placeholder="true"></option>
                                    </select>
                                </div> -->
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="d-flex grid-etapas py-4" id="grid_negocios">
                                        <div id="negocios-draggable" class="d-flex"></div>
                                        <div id="negocios-not-draggable" class="d-flex flex-fill"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="position-absolute w-50" id="alert"></div>

            </div>
            <!-- End Page Content -->
            <!-- Footer -->
            <?php
            $rutaf = "../";
            require_once '../footer.php';
            ?>
            <!-- End of Footer -->
        </div>
    </div>
    <!-- End Page Wrapper -->
    <?php include('modales/modales.php'); ?>
    <script src="../../js/sb-admin-2.min.js"></script>
    <script src="../../js/scripts.js"></script>
    <script src="../../js/slimselect.min.js"></script>
    <script src="../../js/sweet/sweetalert2.js"></script>
    <script src="../../js/lobibox.min.js"></script>

</body>

</html>