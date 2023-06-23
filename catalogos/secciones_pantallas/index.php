<?php
session_start();

$jwt_ruta = "../../";
require_once '../jwt.php';

if (isset($_SESSION["Usuario"]) && $_SESSION["IDEmpresa"] == 1) {
  require_once '../../include/db-conn.php';

  /* ROLES */
  $stmt = $conn->prepare('SELECT rol.rol AS rol, rol.id AS id
  FROM roles AS rol
  WHERE rol != "Super Usuario" AND rol != "Auditoría" AND rol != "Administrador"');
  $stmt->execute();
  $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);

  /* SECCIONES */
  $stmt = $conn->prepare('SELECT id, seccion, orden FROM secciones WHERE seccion != "Configuración" ORDER BY orden');
  $stmt->execute();
  $secciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

  function getPantallas($seccion, $conexion)
  {
    $stmt = $conexion->prepare("SELECT id, pantalla, orden FROM pantallas WHERE seccion_id = $seccion ORDER BY orden");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
} else {
  header("location:../dashboard.php");
}

$token = $_SESSION['token_ld10d'];

?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="icon" type="image/png" href="../../img/header/bluTimlid.png">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Timlid | Secciones - Pantallas</title>

  <!-- ESTILOS -->
  <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
  <link href="../../css/bootstrap-clockpicker.min.css" rel="stylesheet">
  <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../css/lobibox.min.css" rel="stylesheet">
  <link href="../../css/slimselect.min.css" rel="stylesheet">
  <link href="../../css/styles.css" rel="stylesheet">

  <!-- Bootstrap core JavaScript-->
  <script src="../../vendor/jquery/jquery.min.js"></script>
  <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../../js/slimselect.min.js"></script>
</head>

<body id="page-top">
  <!-- Page Wrapper -->
  <div id="wrapper">
    <?php
    $ruta = "../";
    $ruteEdit = $ruta . "central_notificaciones/";
    require_once '../menu3.php';
    ?>
    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <?php
        $rutatb = "../";
        $icono = '../../img/icons/ICONO COTIZACIONES-01.svg';
        $titulo = "Secciones pantallas";
        require_once '../topbar.php';
        ?>
        <div class="container-fluid">
          <div class="row">
            <div class="col-lg-12">
              <div class="card shadow mb-4">
                <div class="card-body">
                  <div class="row">
                    <div class="col-12">
                      <div class="form-group">
                        <form enctype="multipart/form-data" method="post" id="form-seccion">
                          <div class="row">
                            <div class="col-lg-2">
                              <label for="usr">Nombre de la sección:</label>
                              <input type="text" class="form-control" id="txtSeccion" name="txtSeccion" placeholder="Nombre de la sección" data-tipo="seccion">
                              <div class="invalid-feedback" id="invalid-seccion">La sección debe tener un nombre.</div>
                            </div>
                            <div class="col-lg-2">
                              <label for="usr">Siglas de la sección:</label>
                              <input type="text" class="form-control alpha-only" id="txtSiglas" name="txtSiglas" placeholder="Siglas de la sección">
                              <div class="invalid-feedback" id="invalid-siglas">La sección debe tener una sigla.</div>
                            </div>
                            <div class="col-lg-2">
                              <label for="usr">Icono:</label>
                              <input type="file" class="form-control alpha-only" id="iconFile" name="iconFile" placeholder="Siglas de la sección" accept="image/*">
                              <div class="invalid-feedback" id="invalid-icono">La sección debe tener un icono.</div>
                            </div>
                            <div class="col-lg-3">
                              <label for="usr">Perfiles con acceso:</label>
                              <select name="cmbPerfilesSeccion" id="cmbPerfilesSeccion" class="form-control" multiple>
                                <option data-placeholder="true"></option>
                                <?php foreach ($roles as $rol) { ?>
                                  <option value="<?= $rol['id'] ?>"><?= $rol['rol'] ?></option>
                                <?php } ?>
                              </select>
                              <div class="invalid-feedback" id="invalid-perfil-seccion">La sección debe tener por lo menos un perfil asosiado.</div>
                            </div>
                            <div class="col-lg-3 align-self-center">
                              <button type="button" class="btn-custom btn-custom--blue" id="agregarSeccion">Agregar sección</button>
                            </div>
                          </div>
                        </form>
                        <br>
                        <form action="">
                          <div class="row">
                            <div class="col-lg-2">
                              <label for="usr">Nombre pantalla:</label>
                              <input type="text" class="form-control" id="txtPantalla" name="txtPantalla" placeholder="Nombre pantalla" data-tipo="pantalla">
                              <div class="invalid-feedback" id="invalid-pantalla">La pantalla debe tener un nombre.</div>
                            </div>
                            <div class="col-lg-2">
                              <label for="usr">URL:</label>
                              <input type="text" class="form-control valid-url" id="txtURL" name="txtURL" placeholder="URL" data-tipo="url">
                              <div class="invalid-feedback" id="invalid-url">La pantalla debe tener una URL.</div>
                            </div>
                            <div class="col-lg-2">
                              <label for="usr">Seccion:</label>
                              <select name="cmbSeccion" id="cmbSeccion" class="form-control">
                                <option data-placeholder="true"></option>
                              </select>
                              <div class="invalid-feedback" id="invalid-seccion-id">La sección debe tener una sigla.</div>
                            </div>
                            <div class="col-lg-3">
                              <label for="usr">Perfiles con acceso:</label>
                              <select name="cmbPerfilesPantalla" id="cmbPerfilesPantalla" class="form-control" multiple>
                                <option data-placeholder="true"></option>
                                <?php foreach ($roles as $rol) { ?>
                                  <option value="<?= $rol['id'] ?>"><?= $rol['rol'] ?></option>
                                <?php } ?>
                              </select>
                              <div class="invalid-feedback" id="invalid-perfil-pantalla">La pantalla debe tener por lo menos un perfil asosiado.</div>
                            </div>
                            <div class="col-lg-3 align-self-center">
                              <button type="button" class="btn-custom btn-custom--blue" id="agregarPantalla">Agregar pantalla</button>
                            </div>
                          </div>
                        </form>
                      </div>
                    </div>
                    <div class="col-12">
                      <div class="accordion" id="dragable-secciones">
                        <?php foreach ($secciones as $seccion) { ?>
                          <div class="card border-bottom" id="seccion-<?= $seccion['id'] ?>" data-seccion="<?= $seccion['seccion'] ?>">
                            <div id="btn-<?= $seccion['id'] ?>" class="card-header seccion-flex">
                              <span class="linkTable dragable seccion-flex__title" data-toggle="collapse" data-target="#pantallas-seccion-<?= $seccion['id'] ?>" aria-expanded="true" aria-controls="pantallas-seccion-<?= $seccion['id'] ?>">
                                <?= $seccion['seccion'] ?>
                              </span>
                              <span class="seccion-flex__buttons" data-id="<?= $seccion['id'] ?>" data-name="<?= $seccion['seccion'] ?>"><i class="fas fa-edit pointer mr-1 color-primary edit-seccion"></i> <i class="fas fa-trash-alt pointer color-primary delete-seccion"></i></span>
                            </div>

                            <div id="pantallas-seccion-<?= $seccion['id'] ?>" class="collapse" aria-labelledby="btn-<?= $seccion['id'] ?>" data-parent="#dragable-secciones">
                              <div class="card-body dragable-pantallas-seccion" id="dragable-pantallas-seccion-<?= $seccion['id'] ?>">
                                <?php $pantallas = getPantallas($seccion['id'], $conn) ?>
                                <?php foreach ($pantallas as $pantalla) { ?>
                                  <div id="pantalla-<?= $pantalla['id'] ?>" class="alert alert-primary linkTable dragable pantalla-flex" data-pantalla="<?= $pantalla['id'] ?>" data-seccion="<?= $seccion['id'] ?>">
                                    <span class="pantalla-flex__title"><?= $pantalla['pantalla'] ?></span>
                                    <span class="pantalla-flex__buttons" data-id="<?= $pantalla['id'] ?>" data-name="<?= $pantalla['pantalla'] ?>"><i class="fas fa-edit pointer mr-1 color-primary edit-pantalla"></i> <i class="fas fa-trash-alt pointer color-primary delete-pantalla"></i></span>
                                  </div>
                                <?php } ?>
                              </div>
                            </div>
                          </div>
                        <?php } ?>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php
      $rutaf = "../";
      require_once '../footer.php';
      ?>

      <!-- MODAL EDITAR SECCION -->
      <div class="modal fade right" id="modal-editar" tabindex="-1" role="dialog" aria-labelledby="modalLabelEditar" aria-hidden="true">
        <div class="modal-dialog modal-full-height modal-right" role="document">
          <div class="modal-content">
            <input type='hidden' id="hiddenIdEditar">
            <input type='hidden' id="hiddenNameEditar">
            <div class="modal-header">
              <h4 class="modal-title w-100" id="modalLabelEditar"></h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">x</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <div class="row">
                  <div class="col-lg-12">
                    <label id="label-name"></label>
                    <input class="form-control alpha-only" type="text" id="txtEditar" data-tipo="">
                    <div class="invalid-feedback" id="invalid-editar-modal">
                    </div>
                  </div>
                </div>
              </div>
              <div class="d-flex justify-content-center">
                <button type="button" class="btn-custom btn-custom--border-blue mr-1" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn-custom btn-custom--blue" id="btnEditar">Guardar</button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- MODAL ELIMINAR SECCION -->
      <div class="modal fade" id="modal-eliminar" tabindex="-1" role="dialog" aria-labelledby="modalLabelEliminar" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <input type="hidden" id="hiddenIdEliminar" data-tipo="">
            <div class="modal-header">
              <h5 class="modal-title" id="modalLabelEliminar"></h5>
              <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">x</span>
              </button>
            </div>
            <div class="modal-body">
              <p id="leyenda-eliminar"></p>
              <p class="text-danger">Esta acción no podrá deshacerse</p>
              <div class="d-flex justify-content-center">
                <button class="btn-custom btn-custom--border-blue mr-1" type="button" data-dismiss="modal">Cancelar</button>
                <button id="btnEliminar" type="button" class="btn-custom btn-custom--blue">Eliminar</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Custom scripts for all pages-->
  <script src="../../js/sb-admin-2.min.js"></script>
  <script src="../../js/lobibox.min.js"></script>
  <script src="../../js/scripts.js"></script>
  <script src="../../js/validaciones.js"></script>
  <script src="../../js/Sortable.js"></script>
  <script src="js/index.js"></script>
</body>

</html>