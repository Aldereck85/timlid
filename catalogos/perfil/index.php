<?php
session_start();

if (isset($_SESSION["Usuario"])) {
  require_once '../../include/db-conn.php';
  $user = $_SESSION["Usuario"];
  $id = $_SESSION["PKUsuario"];

  $queryUsuario = 'SELECT u.usuario AS email, u.imagen AS avatar, r.rol AS rol, CONCAT(e.Nombres, " ", e.PrimerApellido, " ", e.SegundoApellido) AS nombre, e.is_generic 
  FROM usuarios AS u 
  INNER JOIN empleados AS e ON u.id = e.PKEmpleado 
  INNER JOIN roles AS r ON u.role_id = r.id 
  WHERE e.PKEmpleado = :idUsuario';
  $stmt = $conn->prepare($queryUsuario);
  $stmt->execute(array(':idUsuario' => $id));
  $datosUsuario = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($datosUsuario['is_generic'] == '0') {
    $queryEmpleado = "SELECT e.Telefono AS telefono, e.CURP, e.RFC, e.FechaNacimiento AS fechaNac, dme.NSS, dme.TipoSangre AS sangre, dme.contactoEmergencia AS contactoEmg, dme.NumeroEmergencia AS numeroEmg, dme.Alergias AS alergias, dme.Notas AS notas, dle.FechaIngreso AS fechaIn, p.puesto , t.Turno AS turno, s.sucursal
    FROM empleados AS e 
    LEFT JOIN datos_medicos_empleado AS dme ON e.PKEmpleado = dme.FKEmpleado 
    LEFT JOIN datos_laborales_empleado AS dle ON e.PKEmpleado = dle.FKEmpleado 
    LEFT JOIN puestos AS p ON dle.FKPuesto = p.id 
    LEFT JOIN turnos AS t ON dle.FKTurno = t.PKTurno
    LEFT JOIN sucursales AS s ON dle.FKSucursal = s.id
    WHERE e.PKEmpleado = :idEmpleado;";
    $stmt = $conn->prepare($queryEmpleado);
    $stmt->execute(array(':idEmpleado' => $id));
    $datosEmpleado = $stmt->fetch(PDO::FETCH_ASSOC);

    $queryRolesEmp = "SELECT te.tipo 
    FROM relacion_tipo_empleado AS rtp 
    INNER JOIN tipo_empleado AS te ON rtp.tipo_empleado_id = te.id 
    WHERE rtp.empleado_id = :idEmpleado";
    $stmt = $conn->prepare($queryRolesEmp);
    $stmt->execute(array(':idEmpleado' => $id));
    $rolesEmp = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $rolesEmpString = '';
    foreach ($rolesEmp as $rol) {
      if ($rolesEmpString === '') {
        $rolesEmpString = $rol['tipo'];
      } else {
        $rolesEmpString .= ', ' . $rol['tipo'];
      }
    }
  }

  function getInfo($valor)
  {
    return $valor ? $valor : "No se a registrado";
  }
} else {
  header("location:../dashboard.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <link rel="icon" type="image/png" href="../../img/header/bluTimlid.png">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Timlid | Perfil</title>

  <!-- STYLES -->
  <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../css/lobibox.min.css" rel="stylesheet">
  <link href="../../css/croppie.css" rel="stylesheet">
  <link href="../../css/styles.css" rel="stylesheet">
  <link href="../../css/notificaciones.css" rel="stylesheet">
  <link href="./css/perfil.css" rel="stylesheet">
  <!-- JS -->
  <script src="../../vendor/jquery/jquery.min.js"></script>
  <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../../js/sb-admin-2.min.js"></script>
  <script src="../../js/scripts.js"></script>
  <script src="../../js/lobibox.min.js"></script>
  <script src="../../js/croppie.js"></script>
  <script src="../../js/croppie.js"></script>
  <script src="../../js/exif.js"></script>
  <script src="js/perfil.js"></script>
</head>

<body id="page-top">
  <!-- Page Wrapper -->
  <div id="wrapper">
    <!-- Sidebar -->
    <?php
    $ruta = "../";
    $rutaf = "../";
    $ruteEdit = $ruta . "central_notificaciones/";
    require_once '../menu3.php';
    ?>
    <!-- End of Sidebar -->
    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
      <!-- Main Content -->
      <div id="content">
        <?php
        $rutatb = "../";
        $rutaAvatar = $datosUsuario['avatar'] ? $_ENV['RUTA_ARCHIVOS_READ'] . $_SESSION["IDEmpresa"] . '/img/' . $datosUsuario['avatar'] : '../../img/timUser.png';
        $titulo = "Mi perfil";
        require_once '../topbar.php';
        ?>
        <!-- Begin Page Content -->
        <div class="container-fluid">
          <div class="row">
            <div class="col-lg-4">
              <div class="card shadow mb-4">
                <div class="card-header py-3">
                  Datos usuario
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-12 d-flex mb-2 flex-column justify-content-center align-items-center">
                      <label id="avatar-pic" for="avatar-input">
                        <img src="<?= $rutaAvatar ?>" alt="avatar">
                        <span>Editar</span>
                      </label>
                      <input type="file" name="avatar-input" id="avatar-input" class="d-none">
                    </div>
                    <div class="col-lg-12">
                      <label for="usr">Usuario:</label>
                      <input class="form-control" value="<?= getInfo($datosUsuario['email']) ?>" disabled>
                    </div>
                    <div class="col-lg-12">
                      <label for="usr">Rol:</label>
                      <input type="text" class="form-control" name="txtRol" value="<?= getInfo($datosUsuario['rol']) ?>" disabled>
                    </div>
                  </div>
                  <a data-toggle="modal" data-target="#editar_Contrasenia" class=" mt-4 btn-custom btn-custom--blue float-right">Cambiar
                    contraseña</a>
                </div>
              </div>
            </div>
            <div class="col-lg-8">
              <div class="card shadow mb-4">
                <div class="card-header py-3">
                  Datos personales
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-6">
                      <label for="usr">Nombre completo:</label>
                      <input class="form-control" value="<?= getInfo($datosUsuario['nombre']) ?>" disabled>
                    </div>
                    <?php if ($datosUsuario['is_generic'] == '0') { ?>
                      <div class="col-lg-6">
                        <label for="usr">Teléfono:</label>
                        <input class="form-control" value="<?= getInfo($datosEmpleado['telefono']) ?>" disabled>
                      </div>
                      <div class="col-lg-6">
                        <label for="usr">CURP:</label>
                        <input class="form-control" value="<?= getInfo($datosEmpleado['CURP']) ?>" disabled>
                      </div>
                      <div class="col-lg-6">
                        <label for="usr">RFC:</label>
                        <input class="form-control" value="<?= getInfo($datosEmpleado['RFC']) ?>" disabled>
                      </div>
                      <div class="col-lg-6">
                        <label for="usr">Fecha de nacimiento:</label>
                        <input class="form-control" value="<?= getInfo($datosEmpleado['fechaNac']) ?>" disabled>
                      </div>
                    <?php } ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <?php if ($datosUsuario['is_generic'] == '0') { ?>
            <div class="row">
              <div class="col-lg-6">
                <div class="card shadow mb-4">
                  <div class="card-header py-3">
                    Datos laborales
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-lg-6">
                        <label for="usr">Fecha de ingreso:</label>
                        <input class="form-control" value="<?= getInfo($datosEmpleado['fechaIn']) ?>" disabled>
                      </div>
                      <div class="col-lg-6">
                        <label for="usr">Puesto:</label>
                        <input class="form-control" value="<?= getInfo($datosEmpleado['puesto']) ?>" disabled>
                      </div>
                      <div class="col-lg-12">
                        <label for="usr">Roles:</label>
                        <input class="form-control" value="<?= getInfo($rolesEmpString) ?>" disabled>
                      </div>
                      <div class="col-lg-6">
                        <label for="usr">Turno:</label>
                        <input class="form-control" value="<?= getInfo($datosEmpleado['turno']) ?>" disabled>
                      </div>
                      <div class="col-lg-6">
                        <label for="usr">Locación:</label>
                        <input class="form-control" value="<?= getInfo($datosEmpleado['sucursal']) ?>" disabled>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="card shadow mb-4">
                  <div class="card-header py-3">
                    Datos medicos
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-lg-6">
                        <label for="usr">NSS:</label>
                        <input class="form-control" value="<?= getInfo($datosEmpleado['NSS']) ?>" disabled>
                      </div>
                      <div class="col-lg-6">
                        <label for="usr">Tipo de sangre:</label>
                        <input class="form-control" value="<?= getInfo($datosEmpleado['sangre'])  ?>" disabled>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-lg-6">
                        <label for="usr">Contacto de emergencia:</label>
                        <input class="form-control" value="<?= getInfo($datosEmpleado['contactoEmg']) ?>" disabled>
                      </div>
                      <div class="col-lg-6">
                        <label for="usr">Telefono de emergencia:</label>
                        <input class="form-control" value="<?= getInfo($datosEmpleado['numeroEmg']) ?>" disabled>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-lg-12">
                        <label for="usr">Notas:</label>
                        <textarea name="txtNotes" class="form-control" cols="40" rows="5" disabled><?= getInfo($datosEmpleado['notas']) ?></textarea>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          <?php } ?>
        </div>
        <!-- /.container-fluid -->
      </div>
      <!-- End of Main Content -->
      <!-- Modal cambiar contraseña -->
      <div id="editar_Contrasenia" class="modal fade right" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-full-height modal-right modal-md" role="document">
          <div class="modal-content">
            <form>
              <input type="hidden" name="id-usuario" id="id-usuario" value="<?= $id ?>">
              <div class="modal-header">
                <h4 class="modal-title w-100" id="myModalLabel">Cambiar contraseña</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">x</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="form-group">
                  <label for="contasenia-actual">Contraseña actual:</label>
                  <input type="password" class="form-control" id="contasenia-actual" name="contasenia-actual">
                  <div class="invalid-feedback">
                    Por favor ingresa tu contraseña.
                  </div>
                </div>
                <div class="form-group container-input-contrasenia">
                  <label for="contrasenia-nueva">Contraseña nueva:</label>
                  <input type="password" class="form-control" id="contrasenia-nueva" name="contrasenia-nueva">
                  <i class="fas fa-eye-slash toggle-pass" data-pass="false"></i>
                  <div class="invalid-feedback">
                    Por favor ingresa tu nueva contraseña.
                  </div>
                </div>
              </div>
              <div class="modal-footer justify-content-end">
                <button type="button" class="btn-custom btn-custom--border-blue btnCancelarActualizacion" data-dismiss="modal" id="btnCancelar">
                  <span>Cancelar</span>
                </button>
                <button type="button" class="btn-custom btn-custom--blue" name="btnGuardar" id="guardarContrasenia">
                  <span>Guardar</span>
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <!-- Modal avatar -->
      <div id="modalAvatar" class="modal fade right" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-full-height modal-right modal-md" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title w-100" id="myModalLabel">Cambiar avatar</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">x</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-12">
                  <div id="croppie-avatar"></div>
                </div>
                <div class="col-12"><button id="croppie-btn" class="btn-custom btn-custom--blue float-right">Elegir</button></div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Footer -->
      <?php require_once '../footer.php'; ?>
      <!-- End of Footer -->
    </div>
    <!-- End of Content Wrapper -->
  </div>
</body>

</html>