<?php
session_start();

if (isset($_SESSION["Usuario"])) {
    require_once '../../include/db-conn.php';
    $user = $_SESSION["Usuario"];
    $pkususario = $_SESSION["PKUsuario"];
    $ruta = "../";
    $screen = 56;
    $titulo = 'Agregar Prospecto';

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
    <title>Timlid | <?= $titulo ?></title>

    <!-- ESTILOS -->
    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../../css/slimselect.min.css" rel="stylesheet">
    <link href="../../css/sweetalert2.css" rel="stylesheet">
    <link href="../../css/lobibox.min.css" rel="stylesheet">
    <link href="../../css/styles.css" rel="stylesheet">

    <!-- JS -->
    <script src="../../vendor/jquery/jquery.min.js"></script>
    <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>
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
                $icono = 'ICONO-CRM-AZUL.svg';
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
                                        <form id="formAgregarContactos">
                                            <div class="row form-group">
                                                <div class="col-lg-4">
                                                    <label for="empresa">Empresa:*</label>
                                                    <input class="form-control" type="text" name="empresa" id="empresa" autofocus="" maxlength="50" placeholder="Ej. GH Medic" required>
                                                    <div class="invalid-feedback" id="invalid-empresa">El campo empresa es requerido.</div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <label for="propietario">Vendedor:</label>
                                                    <div class="row">
                                                        <div class="col-lg-12 input-group">
                                                            <select id="propietario"></select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <label for="usr">Medio de contacto / Campaña:</label>
                                                    <div class="row">
                                                        <!-- CREAR PARA MEDIOS O CAMPAÑAS -->
                                                        <div class="col-lg-12 input-group">
                                                            <select name="campania" id="campania"></select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <div class="col-lg-4">
                                                    <label for="nombre">Nombre:*</label>
                                                    <input type="text" class="form-control" maxlength="35" name="nombre" id="nombre" placeholder="Ej.Cesar" required>
                                                    <div class="invalid-feedback" id="invalid-nombre">El campo nombre es requerido.</div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <label for="apellido">Apellido del contacto:</label>
                                                    <input type="text" class="form-control alphaNumeric-only" maxlength="35" name="apellido" id="apellido" placeholder="Ej.Pérez">
                                                </div>
                                                <div class="col-lg-4">
                                                    <label for="puesto">Puesto:</label>
                                                    <input type="text" class="form-control alphaNumeric-only" name="puesto" id="puesto" maxlength="50" placeholder="Ej. Gerente de ventas">
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <div class="col-lg-4">
                                                    <label for="email">Email:</label>
                                                    <input type="email" class="form-control alphaNumeric-only" maxlength="35" name="email" id="email" placeholder="Ej. ejemplo@dominio.com">
                                                </div>
                                                <div class="col-lg-4">
                                                    <label for="telefono">Teléfono:</label>
                                                    <input type="text" class="form-control alphaNumeric-only" name="telefono" id="telefono" maxlength="10" placeholder="Ej. 33 3333 3333">
                                                </div>
                                                <div class="col-lg-4">
                                                    <label for="celular">Celular:*</label>
                                                    <input type="text" class="form-control alphaNumeric-only" name="celular" id="celular" maxlength="10" placeholder="Ej. 33 3333 3333" required>
                                                    <div class="invalid-feedback" id="invalid-celular">El campo celular es requerido.</div>
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <div class="col-lg-4">
                                                    <label for="sitio-web">Sitio web:</label>
                                                    <input type="text" class="form-control alphaNumeric-only" name="sitio-web" id="sitio-web" placeholder="www.google.com">
                                                </div>
                                                <div class="col-lg-4">
                                                    <label for="direccion">Dirección:</label>
                                                    <input type="text" class="form-control alphaNumeric-only" name="direccion" id="direccion" placeholder="Calzada del ejercito 169">
                                                </div>
                                                <div class="col-lg-4">
                                                    <label for="aniversario">Fecha aniversario:</label>
                                                    <input type="date" class="form-control" name="aniversario" id="aniversario">
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <div class="col-lg-4">
                                                    <label for="usr">Pais:</label>
                                                    <select name="pais" id="pais">
                                                        <option value="" disabled selected hidden>Seleccionar Pais
                                                        </option>
                                                        <?php foreach ($paises as $pais) { ?>
                                                            <option value="<?= $pais['PKPais'] ?>"><?= $pais['Pais'] ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="col-lg-4">
                                                    <label for="usr">Estado:</label>
                                                    <select name="estado" id="estado">
                                                        <option value="" disabled selected hidden>Seleccionar Estado
                                                        </option>
                                                        <?php for ($i = 0; $i < count($estados); $i++) { ?>
                                                            <option value="<?= $estados[$i]->PKEstado ?>"><?= $estados[$i]->Estado ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row form-group mt-4">
                                                <div class="col-lg-12">
                                                    <label for="">* Campos requeridos</label>
                                                </div>
                                                <div class="col-lg-12">
                                                    <button class="float-right btn-custom btn-custom--blue" id="btnGuardarContacto">Guardar
                                                    </button>
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
    <script src="../../js/validaciones.js"></script>
    <script src="js/agregar_contacto.js"></script>
</body>

</html>