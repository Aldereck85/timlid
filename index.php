<?php
session_start();

if (isset($_GET['id']) && isset($_GET['codigo'])) {
  $id = $_GET['id'];
  $codigo = $_GET['codigo'];
} else {
  $id = "";
  $codigo = "";
}

if (!isset($_SESSION['login_attempt'])) {
  $_SESSION['login_attempt'] = 1;
}

if (empty($_SESSION['token_ld10d'])) {
  $_SESSION['token_ld10d'] = bin2hex(random_bytes(32));
}
$token = $_SESSION['token_ld10d'];

require_once "include/db-conn.php";

if (isset($_SESSION["Usuario"])) {
  header("location:catalogos/dashboard.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <link rel="icon" type="image/png" href="img/header/bluTimlid.png">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Timlid | ERP</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <script src="js/sweet/sweetalert2.js"></script>
  <link href="css/sb-admin-2.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/styles.css">

</head>

<body class="bg-gradient-login">
  <?php
  if ($id != "" && $codigo != "") {

    include_once("functions/functions.php");

    $idDes = encryptor("decrypt", $id);
    $codigoDes = encryptor("decrypt", $codigo);

    $statement = $conn->prepare("SELECT codigo FROM usuarios WHERE id = :usuario");
    $statement->bindValue(':usuario', $idDes);
    $statement->execute();
    $row = $statement->fetch();
    $total = $statement->rowCount();
    if ($total > 0) {
      if ($row['codigo'] == $codigoDes) {
        $statement = $conn->prepare("UPDATE usuarios SET estatus = 1 WHERE id = :usuario");
        $statement->bindValue(':usuario', $idDes);
        $statement->execute();

  ?>
        <script type="text/javascript">
          Swal.fire({
            title: "¡Éxito!",
            text: "¡Bienvenido! Ya puedes ingresar al sistema con tus datos.",
            icon: "success",
            showConfirmButton: false,
          });
        </script>
      <?php

      } else {
      ?>
        <script type="text/javascript">
          Swal.fire({
            title: "Hubo un error",
            text: "No se puede activar tu cuenta",
            icon: "error",
            showConfirmButton: false,
          });
        </script>
      <?php

      }
    } else {
      ?>
      <script type="text/javascript">
        Swal.fire({
          title: "Hubo un error",
          text: "No se encuentra el usuario",
          icon: "error",
          showConfirmButton: false,
        });
      </script>

  <?php

    }
  }

  ?>
  <div class="container">
    <!-- Outer Row -->
    <div class="row justify-content-center">
      <div class="col-xl-10 col-lg-12 col-md-9">
        <div class="cardLogin o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
              <div class="col-12 col-lg-8" style="background-color: rgba(5,61,117,0.7);">
                <div class="p-5">
                  <div class="login__logo">
                    <img src="img/header/timlidBlanco.png">
                  </div>
                  <br>
                  <form class="user" method="post">
                    <label class="lblLogin">Usuario</label>
                    <div class="form-group inputLogin">
                      <input type="email" placeholder="" name="Usuario" id="exampleInputEmail" aria-describedby="emailHelp" required>
                      <!-- <span class="underline-animation"></span> -->
                    </div>
                    <br>
                    <label class="lblLogin">Contraseña</label>
                    <div class="form-group inputLogin container-input-contrasenia-index">
                      <input type="password" id="exampleInputPassword" name="Contrasena" placeholder="" maxlength="40" required>
                      <i class="fas fa-eye-slash toggle-pass" data-pass="false"></i>
                      <input type="hidden" name="csr_token_78L4" id="csr_token_78L4" value="<?= $token ?>">
                    </div>
                    <div class="colorWhite text-right">
                      <a class="small" href="forgot-password">¿Olvidaste tu contraseña?</a>
                    </div>
                    <?php
                    if ($_SESSION['login_attempt'] > 3) {
                      $displayL = 'none';
                    } else {
                      $displayL = 'flex';
                    }
                    echo '
                          <div class="colorWhite" id="timer-show" style="display: none;position: absolute;">
                            <span style="color:red;" class="small">No puedes ingresar por los proximos <span id="demo"></span>.</span>
                          </div>';
                    echo '<div class="cont-button-login" id="login-show" style="display: ' . $displayL . ';">
                                <button type="button" class="btn-custom btn-custom--white" name="login" id="login">Inicio</button>
                              </div>';
                    ?>
                  </form>
                </div>
              </div>
              <div class="col-4 d-none d-lg-flex bg-light" id="imageBackground">
                <div class="imgJumping">
                  <img src="img/login.png" id="imgJumping" width="165px" id="imagen-icono">
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>
  <script src="js/scripts.js"></script>
  <script src="js/jquery.redirect.min.js"></script>
  <script>
    let tiempo = localStorage.getItem('tiempo-restante');
    let primerTiempo = 0;
    let ejecutarTimerReceso = 0;
    let contador_login_attempt = <?php echo $_SESSION['login_attempt']; ?>;


    if (contador_login_attempt > 3) {

      var date = new Date();
      if (tiempo == 'null') {
        date.setMinutes(date.getMinutes() + 10);
      } else {
        date.setMilliseconds(tiempo);
      }
      var countDownDate = new Date(date).getTime();

      var x = setInterval(function() {

        if (ejecutarTimerReceso == 0) {

          var now = new Date().getTime();

          var distance = countDownDate - now;

          var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
          var seconds = Math.floor((distance % (1000 * 60)) / 1000);

          if (minutes < 1) {
            document.getElementById("demo").innerHTML = seconds + "s ";
            if (tiempo != 'null') {
              $("#timer-show").css("display", "flex");
              $("#login-show").css("display", "none");
            }
          } else {
            document.getElementById("demo").innerHTML = minutes + "m " + seconds + "s ";
            if (tiempo != 'null') {
              $("#timer-show").css("display", "flex");
              $("#login-show").css("display", "none");
            }
          }

          if (primerTiempo == 0) {
            primerTiempo = 1;
          }

          localStorage.setItem('tiempo-restante', distance);

          if (distance < 1) {
            clearInterval(x);
            localStorage.setItem('tiempo-restante', null);
            $("#timer-show").css("display", "none");
            $("#login-show").css("display", "flex");
            primerTiempo = 0;
            tiempo = null;

            $.post("catalogos/gestionLogin.php", {
              gestionLogin: 1
            }, function() {

            });

          }
        }
      }, 1000);
    }


    $("#login").click(function() {
      $("#login").prop( "disabled", true );

      var usuario = $("#exampleInputEmail").val();
      var contrasena = $("#exampleInputPassword").val();

      if (usuario.trim() == "") {
        $("#exampleInputEmail")[0].reportValidity();
        $("#exampleInputEmail")[0].setCustomValidity('Completa este campo.');
        return;
      }
      if (contrasena.trim() == "") {
        $("#exampleInputPassword")[0].reportValidity();
        $("#exampleInputPassword")[0].setCustomValidity('Completa este campo.');
        return;
      }
      var crf_token_l125 = $("#csr_token_78L4").val();

      $.post("catalogos/validarUsuario.php", {
        Usuario: usuario,
        Contrasena: contrasena,
        csr_token_78L4: crf_token_l125
      }, function(data) {
        console.log(data);
        var datos = JSON.parse(data);
        const swalWithBootstrapButtons = Swal.mixin({
          customClass: {
            actions: "d-flex justify-content-around",
            confirmButton: "btn-custom btn-custom--blue",
            cancelButton: "btn-custom btn-custom--border-blue",
          },
          buttonsStyling: false,
        });

        if (datos.estatus == "exito") {

          $('#imgJumping').attr('src', 'img/login.gif');

          setTimeout(function() {
            window.location.replace("catalogos/dashboard.php");
          }, 3000);
        }

        if (datos.estatus == "exito-nuevo") {
          Swal.fire({
            title: 'Antes de continuar',
            text: "Para continuar es necesario crear tu contraseña",
            icon: 'info',
            confirmButtonText: 'OK'
          }).then((result) => {
            if (result.isConfirmed) {
              $().redirect('nueva-contrasena.php', {
                'email': usuario,
                'idusuario': datos.usuario_id
              });
            }
          })
          setTimeout(function() {
            $().redirect('nueva-contrasena.php', {
              'email': usuario,
              'idusuario': idusuario
            });
          }, 3000);
        } else if (datos.estatus == "fallonoexisteusuario") {
          swalWithBootstrapButtons.fire({
            title: "Hubo un error",
            text: "El usuario y/o contraseña es incorrecta",
            icon: 'error',
            confirmButtonText: 'OK',
          });
          $("#login").prop( "disabled", false );
        } else if (datos.estatus == "fallo") {
          $("#login").prop( "disabled", false );

          if (datos.login_attempt > 3) {
            swalWithBootstrapButtons.fire({
              title: "Hubo un error",
              text: "Has tratado de ingresar 3 veces con un contraseña incorrecta, debes esperar 10 minutos para volver a intentarlo.",
              icon: 'error',
              confirmButtonText: 'OK',
            });

            ejecutarTimerReceso = 1;
            let primerTiempo = 0;
            // Contador de tiempo
            var date = new Date();

            if (tiempo == 'null' || tiempo == null) {
              date.setMinutes(date.getMinutes() + 10);
            } else {
              date.setMilliseconds(tiempo);
            }

            var countDownDate = new Date(date).getTime();


            // Update the count down every 1 second
            var x = setInterval(function() {

              // Get today's date and time
              var now = new Date().getTime();

              var distance = countDownDate - now;


              // Time calculations for days, hours, minutes and seconds

              var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
              var seconds = Math.floor((distance % (1000 * 60)) / 1000);

              if (minutes < 1) {
                document.getElementById("demo").innerHTML = seconds + "s ";
              } else {
                document.getElementById("demo").innerHTML = minutes + "m " + seconds + "s ";
              }


              if (primerTiempo == 0) {
                $("#timer-show").css("display", "flex");
                $("#login-show").css("display", "none");
                primerTiempo = 1;
              }

              localStorage.setItem('tiempo-restante', distance);
              if (distance < 0) {
                clearInterval(x);
                $("#timer-show").css("display", "none");
                $("#login-show").css("display", "flex");
                localStorage.setItem('tiempo-restante', null);

                $.post("catalogos/gestionLogin.php", {
                  gestionLogin: 1
                }, function() {});
              }
            }, 1000);
          } else {
            swalWithBootstrapButtons.fire({
              title: "Hubo un error",
              text: "El usuario y/o contraseña es incorrecta",
              icon: 'error',
              confirmButtonText: 'OK',
            });
          }
        } else if (datos.estatus == "no-activado") {
          Swal.fire({
            title: 'Tu cuenta está inactiva, ¿Deseas activarla?',
            text: "Para continuar es necesario activar tu cuenta",
            icon: 'info',
            confirmButtonText: 'OK'
          }).then((result) => {
            if (result.isConfirmed) {
              window.location.replace("activar-cuenta.php");
            }
          })
        } else if (datos.estatus == "fail") {
        $("#login").prop( "disabled", false );
          swalWithBootstrapButtons.fire({
            title: "Hubo un error",
            text: "Ocurrio un error, vuelve a ingresar tus datos.",
            icon: 'error',
            confirmButtonText: 'OK',
          });
        } else if (datos.estatus == "badPlan") {
        $("#login").prop( "disabled", false );
          swalWithBootstrapButtons.fire({
            title: "¡Espera!",
            html: datos.message,
            icon: 'warning',
            confirmButtonText: '<a href="https://timlid.com/comenzar" style="text-decoration: none; color: white;">Vamos</a>'
          });
        } else if (datos.estatus == "inactivo") {
          $("#login").prop( "disabled", false );
          swalWithBootstrapButtons.fire({
            title: "Hubo un error",
            text: "Su usuario no está activo, póngase en contacto con el administrador del sistema.",
            icon: 'error',
            confirmButtonText: 'OK',
          });
        }
      });
    });

    var inputEmail = document.getElementById("exampleInputEmail");

    inputEmail.addEventListener("keyup", function(event) {
      if (event.keyCode === 13) {
        event.preventDefault();
        document.getElementById("login").click();
      }
    });

    var inputPassword = document.getElementById("exampleInputPassword");

    inputPassword.addEventListener("keyup", function(event) {
      if (event.keyCode === 13) {
        event.preventDefault();
        document.getElementById("login").click();
      }
    });
  </script>

</body>

</html>