<?php
session_start();

if (isset($_SESSION["Usuario"])) {
    require_once '../../include/db-conn.php';
    $user = $_SESSION["Usuario"];
    $pkususario = $_SESSION["PKUsuario"];
    $ruta = "../";
    $screen = 57;
    $titulo = 'Agregar Negocio';

    $query = "SELECT a.PKCliente, a.NombreComercial FROM clientes a WHERE a.empresa_id = :empresa_id AND estatus = 1 ORDER BY a.NombreComercial ASC;";
    $rst = $conn->prepare($query);
    $rst->execute([':empresa_id' => $_SESSION["IDEmpresa"]]);
    $clientes = $rst->fetchAll(PDO::FETCH_OBJ);

    $query = "SELECT id, empresa FROM contactos WHERE empresa_id = :empresa_id AND (cliente_id IS NULL) ORDER BY id ASC";
    $rst = $conn->prepare($query);
    $rst->execute([':empresa_id' => $_SESSION["IDEmpresa"]]);
    $prospectos = $rst->fetchAll(PDO::FETCH_OBJ);

    $query = "SELECT em.PKEmpleado, concat(em.Nombres,' ',em.PrimerApellido,' ',em.SegundoApellido) AS nombre 
    FROM relacion_tipo_empleado re
    INNER JOIN empleados em ON re.empleado_id = em.PKEmpleado
    WHERE re.tipo_empleado_id = 1 AND em.estatus = 1 AND em.empresa_id = :empresa_id ORDER BY nombre";
    $rst = $conn->prepare($query);
    $rst->execute([':empresa_id' => $_SESSION["IDEmpresa"]]);
    $propietarios = $rst->fetchAll(PDO::FETCH_OBJ);
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
    <link href="../../css/stylesTable.css" rel="stylesheet">
    <link href="../../css/slimselect.min.css" rel="stylesheet">
    <script src="../../js/validaciones.js"></script>

    <link rel="stylesheet" href="../../css/sweetalert2.css">
    <link href="../../css/lobibox.min.css" rel="stylesheet">

    <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../../css/slimselect.min.css" rel="stylesheet">

    <link href="../../css/styles.css" rel="stylesheet">

    <!-- <link rel="stylesheet" href="../../css/notificaciones.css"> -->
    <!-- <script src="../../js/notificaciones_timlid.js" charset="utf-8"></script> -->

    <script src="js/agregar_negocio.js"></script>
    <script src="js/format_money.js"></script>
    <link href="css/agregar_negocio.css" rel="stylesheet">

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
                $icono = '../../img/crm/negocios.svg';
                $backIcon = true;
                require_once $rutatb . 'topbar.php';
                ?>

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <div class="row">
                        <!-- Basic Card Example -->
                        <div class="card shadow mb-4 nav-link col-lg-12" id="bodyUp">
                            <div class="card-body">
                                <!-- <span id="alertas"> </span> -->
                                <div class="row">
                                    <div class="col-lg-12">
                                        <form id="formAgregarNegocio">
                                            <div class="row form-group">
                                                <div class="radio col-lg-4">
                                                    <div class="row">
                                                        <label class="col-lg-6">
                                                            <input type="radio" name="tipoContacto" value="lead" required id="tipo" checked>
                                                            <span class="px-2">Lead</span>
                                                        </label>
                                                        <label class="col-lg-6"><input type="radio" name="tipoContacto" value="cliente" required id="tipo">
                                                            <span class="px-2">Cliente</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-8">
                                                    <label for="empresa">Empresa:*</label>
                                                    <div id="div_emprePros" class="form-group">
                                                        <select name="contacto" id="empresaProsSelect">
                                                            <option data-placeholder="true"></option>
                                                            <?php foreach ($prospectos as $prospecto) { ?>
                                                                <option value="<?= $prospecto->id ?>"><?= $prospecto->empresa ?></option>
                                                            <?php } ?>
                                                        </select>
                                                        <div class="invalid-feedback" id="invalid-empresaProsp">El campo empresa es requerido.</div>
                                                    </div>
                                                    <div id="div_empreClie" class="form-group d-none">
                                                        <select name="cliente" id="empresaClieSelect">
                                                            <option data-placeholder="true"></option>
                                                            <?php foreach ($clientes as $cliente) { ?>
                                                                <option value="<?= $cliente->PKCliente ?>"><?= $cliente->NombreComercial ?></option>
                                                            <?php } ?>
                                                        </select>
                                                        <div class="invalid-feedback" id="invalid-empresaClien">El campo empresa es requerido.</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <div class="radio col-lg-4">
                                                    <label for="prioridad">Prioridad:*</label>
                                                    <div class="row" id="div_etapas">
                                                        <div class="col-lg-12 input-group">
                                                            <select name="prioridad" id="prioridad">
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="invalid-feedback" id="invalid-prioridad">El campo prioridad es requerido.</div>
                                                </div>
                                                <div class="radio col-lg-8">
                                                    <label for="nombre">Nombre del Negocio:*</label>
                                                    <input type="text" class="form-control alphaNumeric-only" maxlength="50" name="nombre" id="nombre" required placeholder="Ej. Licitación 2021">
                                                    <div class="invalid-feedback" id="invalid-nombre">El campo nombre es requerido.</div>
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <div class="col-lg-4">
                                                    <label for="etapa">Etapa:*</label>
                                                    <div class="row" id="div_etapas">
                                                        <div class="col-lg-12 input-group">
                                                            <select name="etapa" id="etapa" required>
                                                            </select>
                                                            <div class="invalid-feedback" id="invalid-etapa">Selecciona una etapa</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <label for="valor">Valor:*</label>
                                                    <input type="text" class="form-control alphaNumeric-only" name="valor" id="valor" maxlength="12" required data-type="currency" placeholder="$1,000,000.00">
                                                    <input type="hidden" name="" id="valor_1">
                                                    <div class="invalid-feedback" id="invalid-valor">El campo valor es requerido.</div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <label for="propietario">Vendedor:*</label>
                                                    <div class="row">
                                                        <div class="col-lg-12 input-group">
                                                            <select name="propietario" id="propietario">
                                                                <option data-placeholder="true"></option>
                                                                <?php foreach ($propietarios as $propietario) { ?>
                                                                    <option value="<?= $propietario->PKEmpleado ?>"><?= $propietario->nombre ?></option>
                                                                <?php } ?>
                                                            </select>
                                                            <div class="invalid-feedback" id="invalid-propietario">El campo vendedor es requerido.</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <div class="col-lg-4">
                                                    <label for="nombre">Contacto:</label>
                                                    <select name="contacto" id="contacto"></select>
                                                </div>
                                                <div class="col-lg-8">
                                                    <label for="descripcion">Descripción:</label>
                                                    <textarea class="form-control" name="descripcion" id="descripcion"></textarea>
                                                </div>
                                            </div>
                                            <div class="row form-group mt-4">
                                                <div class="col-lg-12">
                                                    <label for="">* Campos requeridos</label>
                                                </div>
                                                <div class="col-lg-12">
                                                    <button type="submit" class="float-right btn-custom btn-custom--blue" id="btnGuardarNegocio">Guardar</button>
                                                </div>
                                            </div>
                                        </form>
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

    <script src="../../js/sb-admin-2.min.js"></script>
    <script src="../../js/scripts.js"></script>
    <script src="../../js/slimselect.min.js"></script>
    <script src="../../js/sweet/sweetalert2.js"></script>
    <script src="../../js/lobibox.min.js"></script>

</body>

</html>