<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/png" href="../../img/header/bluTimlid.png">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Timlid | Perfiles</title>

    <!-- Bootstrap core JavaScript-->
    <script src="../../../vendor/jquery/jquery.min.js"></script>
    <script src="../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../../../vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../../../js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="../../../vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../../../vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="../../../vendor/datatables/dataTables.buttons.js"></script>
    <script src="../../../vendor/datatables/buttons.bootstrap4.min.js"></script>
    <script src="../../../vendor/datatables/buttons.html5.min.js"></script>
    <script src="../../../vendor/jszip/jszip.min.js"></script>

    <!-- Custom fonts for this template -->
    <link href="../../../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="../../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

    <!-- Custom styles for this template -->
    <link href="../../../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../../../vendor/datatables/buttons.dataTables.css" rel="stylesheet">
    <link href="../../../vendor/datatables/buttons.bootstrap4.min.css" rel="stylesheet">
    <link href="../../../css/styles.css" rel="stylesheet">
    <link href="../../../css/stylesTable.css" rel="stylesheet">
    <link href="../../../css/slimselect.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/agregar_usuario.css">

    <link rel="stylesheet" href="css/usuarios.css">

    <link rel="stylesheet" href="../../../css/sweetalert2.css">
    <link href="../../../css/lobibox.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../../../css/notificaciones.css">
    <script src="../../../js/notificaciones_timlid.js" charset="utf-8"></script>

    <style>
        .nav-link {
            padding: .5rem;
        }
    </style>
</head>
<body id="page-top" class="sidebar-toggled">
    <h1>hola</h1>
    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="row">
                        <div class="col-lg-12">
                            <ul class="nav nav-tabs">

                                <li class="nav-item">
                                    <a id="CargarUsuarios" class="nav-link active" href="">
                                        Usuarios
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a id="CargarPuestos" class="nav-link" href="puestos">
                                        Puestos
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a id="CargarTurnos" class="nav-link" href="turnos">
                                        Turnos
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a id="CargarSucursales" class="nav-link" href="sucursales">
                                        Sucursales
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a id="CargarEstatusEmpleado" class="nav-link" href="estatus_empleado">
                                        Estatus empleado
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a id="CargarCategoriaProductos" class="nav-link" href="categoria_productos">
                                        Categoría de productos
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a id="CargarMarcas" class="nav-link" href="marca_productos">
                                        Marca de productos
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a id="CargarCategoriaGastos" class="nav-link" href="categoria_gastos">
                                        Categoría gastos
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a id="CargarSubategoriaGastos" class="nav-link" href="subcategorias_gastos">
                                        Subcategoría de gastos
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a id="CargarResponsableGastos" class="nav-link" href="responsables_gastos">
                                        Responsable de gastos
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a id="CargarTipoOrdenInventario" class="nav-link" href="tipo_orden_inventario">
                                        Tipo de orden de inventario
                                    </a>
                                </li>
                            </ul>

                        </div>
                    </div>
                    <!-- Basic Card Example -->

                    <!-- DataTales Example -->
                    <div class="card mb-4">
                        <div class="card-header py-3">
                            <div class="float-right permission-view-add">
                                <div class="button-container2">
                                    <div class="button-icon-container">
                                        <a href="" class="btn btn-circle float-right waves-effect waves-light shadow btn-table btn-Agregar"
                                           id="btn-proyectos" data-toggle="modal" data-target="#agregar_Usuario"><i
                                                class="fas fa-plus"></i></a>
                                    </div>
                                    <div class="button-text-container">
                                        <span>Agregar perfil</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table permission-view-table" id="tblUsuarios" width="100%" cellspacing="0">
                                    <thead>
                                    <tr>
                                        <th>Id usuario</th>
                                        <th>Usuario</th>
                                        <th>Nombre completo</th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- End Page Content -->
            </div>
            <!-- End Main Content -->
        </div>
        <!-- End Content Wrapper -->
    </div>
    <!-- End Page Wrapper -->

</body>
</html>