<?php
session_start();

if (isset($_SESSION["Usuario"])) {
    require_once '../../include/db-conn.php';
    $user = $_SESSION["Usuario"];
    $pkususario = $_SESSION["PKUsuario"];
    $ruta = "../";
    $screen = 56;
    $titulo = 'Calendario';
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
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" type="image/png" href="../../img/header/bluTimlid.png">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Timlid | <?= $titulo ?></title>

    <!-- ESTILOS -->
    <link href="../../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../../vendor/datatables/buttons.dataTables.css" rel="stylesheet">
    <link href="../../vendor/datatables/buttons.bootstrap4.min.css" rel="stylesheet">
    <link href="" rel="stylesheet" id="estilos-tables">
    <link href="../../css/slimselect.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../css/sweetalert2.css">
    <link href="../../css/lobibox.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/@fullcalendar/core@4.4.2/main.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@4.4.2/main.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/@fullcalendar/list@4.4.2/main.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fullcalendar/timegrid@4.4.2/main.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tokenfield/0.12.0/css/bootstrap-tokenfield.min.css">
    <link rel="stylesheet" type="text/css" href="css/style_modal_eventos.css">
    <link href="../../css/styles.css" rel="stylesheet">
    <link href="../../css/stylesCalendar.css" rel="stylesheet">

    <!-- JS -->
    <script src="../../vendor/jquery/jquery.min.js"></script>
    <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="../../js/sb-admin-2.min.js"></script>
    <script src="../../vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../../vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="../../vendor/datatables/dataTables.buttons.js"></script>
    <script src="../../vendor/datatables/buttons.bootstrap4.min.js"></script>
    <script src="../../vendor/datatables/buttons.html5.min.js"></script>
    <script src="../../vendor/jszip/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/4.2.0/core/main.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/4.2.0/core/locales-all.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/4.2.0/interaction/main.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/4.2.0/daygrid/main.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/4.2.0/timegrid/main.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/4.2.0/list/main.min.js"></script>
    <script src="https://unpkg.com/@fullcalendar/resource-common@4.2.0/main.min.js"></script>
    <script src="https://unpkg.com/@fullcalendar/timeline@4.2.0/main.min.js"></script>
    <script src="https://unpkg.com/@fullcalendar/resource-timeline@4.2.0/main.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="js/jquery.tag.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tokenfield/0.12.0/bootstrap-tokenfield.js"></script>
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
                $icono = 'ICONO-CALENDARIO-AZUL.svg';
                require_once $rutatb . 'topbar.php';
                ?>

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <div class="row mb-4">
                        <div class="card shadow col-12">
                            <div class="card-body">
                                <div class="tab-pane fade show active" id="task" role="tabpanel" aria-labelledby="task-tab">
                                    <div class="row">
                                        <div class="col-12 d-flex mb-3">
                                            <div class="">
                                                <button id="btn-new-schedule" type="button" class="btn-custom btn-custom--blue" data-toggle="modal" onclick="addEventButton()">
                                                    <i class="far fa-calendar-plus mr-2"></i> Nueva Actividad
                                                </button>
                                            </div>
                                            <!-- <div class="">
                                                <div class="d-flex">
                                                    <span class="dropdown">
                                                        <button id="dropdownMenu-activityType" class="btn btn-outline-dark btn-sm move-today rounded-full mx-2 dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <span id="activityTypeName">Actividad</span>
                                                            <input type="hidden" value="" id="activityType">
                                                        </button>
                                                        <ul class="dropdown-menu " role="menu" aria-labelledby="dropdownMenu-activityType">
                                                            <li role="presentation">
                                                                <a class="dropdown-item filter" role="menuitem" data-action="all">Todos</a>
                                                            </li>
                                                            <li role="presentation">
                                                                <a class="dropdown-item filter" role="menuitem" data-action="1">Tarea</a>
                                                            </li>
                                                            <li role="presentation">
                                                                <a class="dropdown-item filter" role="menuitem" data-action="2">Reunión</a>
                                                            </li>
                                                            <li role="presentation">
                                                                <a class="dropdown-item filter" role="menuitem" data-action="3">Llamada</a>
                                                            </li>
                                                            <li role="presentation">
                                                                <a class="dropdown-item filter" role="menuitem" data-action="4">Correo</a>
                                                            </li>
                                                        </ul>
                                                    </span>
                                                </div>
                                            </div> -->
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div id="calendar"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <?php
                $rutaf = "../";
                require_once '../footer.php';
                ?>
                <!-- End of Footer -->

            </div>
            <!-- End Page Content -->
        </div>

        <!-- Modal Agregar Calendario -->
        <div class="modal fade" id="modal-new-calendar" tabindex="-1" role="dialog" aria-labelledby="modal-new-calendarLabel" aria-hidden="true">
            <div class="modal-dialog tui-full-calendar-popup" role="document">
                <div class="modal-content tui-full-calendar-popup-container">

                    <div class="tui-full-calendar-popup-section mt-4">
                        <div class="tui-full-calendar-popup-section-item tui-full-calendar-section-location d-flex align-items-center">
                            <label for="name" class="label-modal">Nombre:</label>
                            <input name="name" id="name" class="tui-full-calendar-content" placeholder="Nombre" value="">
                        </div>
                        <div class="tui-full-calendar-popup-section-item w-100 mt-2 d-flex align-items-center">
                            <label for="color" class="label-modal">Color:</label>
                            <input type="color" name="color" id="color">
                        </div>
                        <div class="tui-full-calendar-section-button-save mt-2">
                            <button class="tui-full-calendar-button tui-full-calendar-confirm tui-full-calendar-popup-save" id="save-calendar">
                                <span>Guardar</span>
                            </button>
                        </div>
                    </div>
                    <button class="tui-full-calendar-button tui-full-calendar-popup-close" data-dismiss="modal"><span class="tui-full-calendar-icon tui-full-calendar-ic-close"></span></button>
                </div>
            </div>
        </div>

    </div>
    </div>
    <!-- End Page Wrapper -->
    <?php include('modales/modal_fullcalendar.php') ?>
    <script src="../../js/sb-admin-2.min.js"></script>
    <script src="../../js/slimselect.min.js"></script>
    <script src="../../js/sweet/sweetalert2.js"></script>
    <script src="../../js/lobibox.min.js"></script>
    <script src="../../js/scripts.js"></script>
    <script src="js/funcionalidades_calendar.js"></script>
</body>

</html>