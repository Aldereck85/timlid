<?php
session_start();

if (isset($_SESSION["Usuario"])) {
    require_once '../../include/db-conn.php';
    $user = $_SESSION["Usuario"];
    $pkususario = $_SESSION["PKUsuario"];
    $ruta = "../";
    $screen = 56;
    $titulo = 'Editar Contacto';

    $query = 'SELECT e.PKEstado, e.Estado FROM estados_federativos e WHERE FKPais = 146 ORDER BY e.Estado ASC';
    $rst = $conn->prepare($query);
    $rst->execute();
    $estados = $rst->fetchAll(PDO::FETCH_OBJ);

    $query = 'SELECT PKPais, Pais FROM paises ORDER BY PKPais ASC';
    $rst = $conn->prepare($query);
    $rst->execute();
    $paises = $rst->fetchAll(PDO::FETCH_ASSOC);
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
    <meta http-equiv="cache-control" content="no-cache" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="-1" />
    <title>Timlid | <?= $titulo ?></title>

    <!-- ESTILOS -->
    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="" rel="stylesheet" id="estilos-tables">
    <link href="../../css/slimselect.min.css" rel="stylesheet">
    <link href="../../css/sweetalert2.css" rel="stylesheet">
    <link href="../../css/lobibox.min.css" rel="stylesheet">
    <link href="../../css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="../../vendor/datatables/responsive.dataTables.css" rel="stylesheet">
    <link href="../../vendor/datatables/buttons.dataTables.css" rel="stylesheet">
    <link href="../../css/stylesNewTable.css" rel="stylesheet">
    <link href="css/editar_contacto.css" rel="stylesheet">
    <link type="text/css" href="css/style_modal_eventos.css" rel="stylesheet">
    <link type="text/css" href="https://cdn.jsdelivr.net/npm/@fullcalendar/core@4.4.2/main.css" rel="stylesheet">
    <link type="text/css" href="https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@4.4.2/main.min.css" rel="stylesheet">
    <link type="text/css" href="https://cdn.jsdelivr.net/npm/@fullcalendar/list@4.4.2/main.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@fullcalendar/timegrid@4.4.2/main.min.css" rel="stylesheet">
    <link type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tokenfield/0.12.0/css/bootstrap-tokenfield.min.css" rel="stylesheet">
    <link href="../../css/styles.css" rel="stylesheet">
    <link href="../../css/stylesCalendar.css" rel="stylesheet">

    <!-- JS -->
    <script src="../../vendor/jquery/jquery.min.js"></script>
    <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="../../js/sb-admin-2.min.js"></script>
    <script src="../../vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../../vendor/datatables/dataTables.buttons.js"></script>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tokenfield/0.12.0/bootstrap-tokenfield.js"></script>
    <script src="js/jquery.tag.js"></script>
</head>

<body id="page-top" class="sidebar-toggled">

    <div style="position: fixed;
                  left: 0px;
                  top: 0px;
                  width: 100%;
                  height: 100%;
                  z-index: 9999;
                  display: none;
                  background: url('../../img/timdesk/Preloader.gif') 50% 50% no-repeat rgb(249,249,249);
                  opacity: .6;" id="loader">
    </div>

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
                $icono = 'ICONO-CRM-AZUL.svg';
                $backIcon = true;
                require_once $rutatb . 'topbar.php';
                ?>
                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <div class="row min-h-full mb-4">

                        <!-- Basic Card Example -->
                        <div class="card shadow col-xl-3 min-h-full">
                            <div class="card-body">
                                <!-- <span id="alertas"> </span> -->
                                <div class="row">
                                    <div class="col-xl-12">
                                        <div class="d-flex justify-content-end">
                                            <button type="button" id="editarContacto" class="btn-custom btn-custom--blue-lightest mx-1" data-tooltip="tooltip" title="Editar prospecto" onclick="modalContacto()">
                                                <i class="far fa-edit"></i>
                                            </button>
                                            <button type="button" id="agregarContacto" class="btn-custom btn-custom--blue-lightest mx-1" data-tooltip="tooltip" title="Añadir contacto" onclick="modalContactos()">
                                                <i class="fas fa-user"></i>
                                            </button>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center" id="tipo_contacto"></div>
                                        <h5 class="header-title-screen mt-4 ">Nombre: &nbsp; <span class="text-secondary nombre"></span></h5>
                                        <h5 class="header-title-screen">Empresa: &nbsp; <span class="text-secondary empresa"></span></h5>
                                        <h5 class="header-title-screen">Estado: &nbsp; <span class="text-secondary estado"></span>
                                        </h5>
                                        <h5 class="header-title-screen">Puesto: &nbsp; <span class="text-secondary puesto"></span>
                                        </h5>
                                        <div class="d-flex justify-content-end mt-4" id="prospecto_ascender"></div>
                                    </div>
                                    <div class="col-lg-12 mb-5 d-none" id="contacto-declinado">
                                        <h5 class="header-title-screen">Motivo declinación: &nbsp; <span class="text-secondary motivo-declinacion"></span>
                                        </h5>
                                    </div>
                                    <div class="col-lg-12" style="margin-bottom: 10%; ">
                                        <label for="">Actividades por usuario/contacto</label>
                                        <select name="" id="opciones-usuario">
                                            <option selected disabled>Elije una opción</option>
                                            <option value="1"> Ver todas tus actividades</option>
                                            <option value="2"> Ver las actividades del contacto</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-12">
                                        <label for="">Actividades calendario</label>
                                        <select name="" id="opciones-actividades">
                                            <option selected disabled>Elije una opción</option>
                                            <option value="all">Filtrar todas las tareas</option>
                                            <option value="1">Filtrar por tarea</option>
                                            <option value="2">Filtrar por reunión</option>
                                            <option value="3">Filtrar por llamada</option>
                                            <option value="4">Filtrar por correo</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-9 min-h-full d-flex flex-column mt-4 mt-md-2">
                            <nav>
                                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                    <ul class="nav nav-tabs">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="task-tab-actividades" data-toggle="tab" href="#task" role="tab" aria-controls="task" aria-selected="false">
                                                Actividades
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="task-tab-notas" data-toggle="tab" href="#note" role="tab" aria-controls="note" aria-selected="false">
                                                Notas
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </nav>
                            <div class="card shadow nav-link col-lg-12 flex-1">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="tab-content">
                                                <div class="tab-pane fade" id="note" role="tabpanel" aria-labelledby="note-tab">
                                                    <div class="table-responsive">
                                                        <table class="table" id="tblNotas" width="100%" cellspacing="0">
                                                            <thead>
                                                                <tr>
                                                                    <th>Color</th>
                                                                    <th>Nota</th>
                                                                    <th>Fecha creación</th>
                                                                    <th>Fecha edición</th>
                                                                    <th></th>
                                                                </tr>
                                                            </thead>
                                                        </table>
                                                    </div>
                                                </div>
                                                <!--<select id="selectContactos">
                                               <option value="0">Selecciona un contacto</option>
                                             </select>-->
                                                <div class="tab-pane fade show active" id="task" role="tabpanel" aria-labelledby="task-tab">
                                                    <button class="btn-custom btn-custom--blue mb-3" onclick="addEventButton()"><i class="far fa-calendar-plus mr-2"></i> Nueva Actividad</button>
                                                    <div id="calendar">

                                                    </div>
                                                    <input type="hidden" id="contacto_id">
                                                    <input type="hidden" id="contacto_id_select">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- End Page Content -->
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
    <?php include('modales/modales_editar_contacto.php'); ?>
    <?php include('modales/modales_agregar_contacto.php'); ?>
    <?php include('modales/modal_fullcalendar.php') ?>

    <script src="../../js/sb-admin-2.min.js"></script>
    <script src="../../js/slimselect.min.js"></script>
    <script src="../../js/sweet/sweetalert2.js"></script>
    <script src="../../js/lobibox.min.js"></script>
    <script src="../../js/scripts.js"></script>
    <script src="js/editar_contacto.js"></script>
    <script src="js/funcionalidades_calendar.js"></script>
</body>

</html>