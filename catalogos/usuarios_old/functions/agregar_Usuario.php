<?php
session_start();

function generateRandomString($length = 12)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
$redirect = 0;
if (isset($_SESSION["Usuario"]) && ($_SESSION["FKRol"] == 4 || $_SESSION["FKRol"] == 1)) {
    require_once '../../../include/db-conn.php';
    if (isset($_POST['btnAgregar'])) {
        $idUsuario = $_POST['cmbIdEmpleado'];

        $stmt = $conn->prepare('SELECT count(*) FROM empleados WHERE PKEmpleado= :id');
        $stmt->execute(array(':id' => $idUsuario));
        $number_of_rows = $stmt->fetchColumn();
        if ($number_of_rows > 0) {
            $usuario = $_POST['txtUsuario'];
            $password = $_POST['txtContrasena'];
            $rol = (int) $_POST['cmbRol'];
            $codigo = generateRandomString();

            try {
                $stmt = $conn->prepare('INSERT INTO usuarios (Usuario,Contrasena,FKEmpleado,FKRol,Codigo)VALUES(:usuario,:contrasena,:idEmpleado,:rol, :codigo)');
                $stmt->bindValue(':usuario', $usuario);
                $stmt->bindValue(':contrasena', $password);
                $stmt->bindValue(':idEmpleado', $idUsuario);
                $stmt->bindValue(':codigo', $codigo);
                $stmt->bindValue(':rol', (int) $rol, PDO::PARAM_INT);
                if ($stmt->execute()) {
                    $idUsuario = $conn->lastInsertId();

                    $redirect = 1;

                    $stmt = $conn->prepare("SELECT u.PKUsuario, u.Usuario, e.Primer_Nombre, e.Segundo_Nombre, e.Apellido_Paterno, e.Apellido_Materno, u.Codigo FROM usuarios as u LEFT JOIN empleados as e ON u.FKEmpleado = e.PKEmpleado WHERE u.PKUsuario = :idusuario");
                    $stmt->bindValue(':idusuario', $idUsuario);
                    $stmt->execute();
                    $row_usuario = $stmt->fetch();

                    if ($row_usuario['Segundo_Nombre'] == "") {
                        $segundoNombre = "";
                    } else {
                        $segundoNombre = $row_usuario['Segundo_Nombre'] . " ";
                    }

                    $email = $row_usuario['Usuario'];
                    $nombreUsuario = $row_usuario['Primer_Nombre'] . " " . $segundoNombre . $row_usuario['Apellido_Paterno'] . " " . $row_usuario['Apellido_Paterno'];
                    $codigoBD = $row_usuario['Codigo'];

                }
            } catch (PDOException $ex) {
                echo $ex->getMessage();
            }
        } else {
            echo "El empleado no existe";
        }
    }
} else {
    header("location:../../dashboard.php");
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <link rel="icon" type="image/png" href="../../../img/header/bluTimlid.png">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Timlid | Agregar usuario</title>

  <!-- Bootstrap core JavaScript-->
  <script src="../../../vendor/jquery/jquery.min.js"></script>
  <script src="../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../../../js/bootstrap-clockpicker.min.js"></script>
  <!-- Custom fonts for this template-->
  <link href="../../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../../../css/bootstrap-clockpicker.min.css" rel="stylesheet" type="text/css">
  <link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet">
  <!-- Custom styles for this template-->
  <link href="../../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../../css/styles.css" rel="stylesheet">
  <link href="../../../css/chosen.css" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css"
    integrity="sha256-EH/CzgoJbNED+gZgymswsIOrM9XhIbdSJ6Hwro09WE4=" crossorigin="anonymous" />

  <script>
  function checkPassword() {
    if ($('#newPasswordAgain').val() !== $('#newPassword').val()) {
      $('#newPasswordAgain')[0].setCustomValidity('Las contraseñas deben coincidir.');
    } else {
      $('#newPasswordAgain')[0].setCustomValidity('');
    }
  }
  </script>

</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <?php
$titulo = "Cambiar";
$ruta = "../../";
$ruteEdit = $ruta . "central_notificaciones/";
require_once '../../menu3.php';
?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <?php
$rutatb = "../../";
require_once '../../topbar.php';
?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <?php
if ($redirect == 1) {
    echo '<div class="container" id="mensaje_exito">
                      <br>
                      <div class="alert alert-success" role="alert">
                        Se ha enviado un correo electrónico al email de este usuario para activar su cuenta.
                      </div>
                    </div>';

    $to = $email;
    $subject = "Activar cuenta | Timlid";

    $message = "
                <h2 align='center'>Activar cuenta</h2>
                <hr>
                <p align='left'>Saludos, " . $nombreUsuario . "</p>
                <p align='justify'>Bienvenido al sistema ERP de Timlid, para completar tu registro solo necesitas acceder al siguiente enlace:</p>
                <p align='center'><a href='http://erpghmedic.com.mx/index.php?id=" . $idUsuario . "&codigo=" . $codigoBD . "' >Timlid - Activar cuenta</a></p>
                <hr>
                <center><img src='http://erpghmedic.com.mx/img/Logo-transparente.png' width='15%' /></center>
                ";

    // Always set content-type when sending HTML email
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

    $headers .= 'From: <erpghmed@erpghmedic.com.mx>' . "\r\n";

    mail($to, $subject, $message, $headers);
}
?>
          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Agregar Usuario</h1>
          </div>


          <div class="row">

            <div class="col-lg-12">

              <!-- Basic Card Example -->
              <div class="card shadow mb-4">
                <div class="card-header">
                  Tarjeta de puestos
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-12">
                      <form action="" method="post">
                        <div class="form-group">
                          <div class="row">
                            <div class="col-lg-6">
                              <label for="usr">Id de empleado:</label>
                              <select class="form-control" name="cmbIdEmpleado" id="cmbIdEmpleado" required>
                                <option value="">Seleccione una opcion...</option>
                                <?php
$stmt = $conn->prepare('SELECT * FROM empleados WHERE NOT EXISTS (SELECT * FROM usuarios WHERE empleados.PKEmpleado = usuarios.FKEmpleado)');
$stmt->execute();
while ($row = $stmt->fetch()) {
    ?>
                                <option value="<?=$row['PKEmpleado'];?>">
                                  <?=$row['PKEmpleado'] . ".- " . $row['PrimerApellido'] . " " . $row['SegundoApellido'] . " " . $row['Nombres'];?>
                                </option>
                                <?php }?>
                              </select>
                            </div>
                            <div class="col-lg-6">
                              <label for="usr">Usuario:</label>
                              <input type="email" class="form-control" name="txtUsuario" required>
                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="row">
                            <div class="col-lg-6">
                              <label for="usr">Contraseña:</label>
                              <input type="password" class="form-control" id="newPassword" name="txtContrasena"
                                maxlength="10"
                                pattern="(?=.*\d)(?=.*[A-Z])(?=.*[~`!@#$%^&*()\-_+={};:\[\]\?\.\\/,]).{10,}"
                                title="La contraseña debe tener al menos una letra mayuscula,  un caracter especia y 10 caracteres."
                                required>
                            </div>
                            <div class="col-lg-6">
                              <label for="usr">Repetir Contraseña:</label>
                              <input type="password" class="form-control" id="newPasswordAgain" maxlength="10" required>
                            </div>
                          </div>
                        </div>

                        <button type="submit" class="btn btn-success float-right" onclick="checkPassword()"
                          name="btnAgregar">Agregar</button>
                      </form>
                    </div>
                  </div>


                </div>
              </div>

            </div>
          </div>

        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

      <!-- Footer -->
      <?php
$rutaf = "../../";
require_once '../../footer.php';
?>
      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Custom scripts for all pages-->
  <script src="../../../js/sb-admin-2.min.js"></script>
  <script src="../../../js/scripts.js"></script>
  <script src="../../../js/validaciones.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"
    integrity="sha256-c4gVE6fn+JRKMRvqjoDp+tlG4laudNYrXI1GncbfAYY=" crossorigin="anonymous"></script>
  <script>
  $(document).ready(function() {
    $("#alertaTareas").load(
      '../../alerta_Tareas_Nuevas.php?user=<?=$_SESSION['PKUsuario'];?>&ruta=<?=$ruta;?>&ruteEdit=<?=$ruteEdit;?>');
    setInterval(
      refrescar, 5000);
  });

  function refrescar() {
    $("#alertaTareas").load(
      '../../alerta_Tareas_Nuevas.php?user=<?=$_SESSION['PKUsuario'];?>&ruta=<?=$ruta;?>&ruteEdit=<?=$ruteEdit;?>');
  }
  $(document).ready(function() {
    $("#cmbIdEmpleado").chosen();
  });
  </script>
  <script>
  var ruta = "../../";
  </script>

</body>

</html>
<?php
if ($redirect == 1) {

    sleep(3);
    ?>
<script>
window.location.replace("../index.php");
</script>

<?php
}
?>