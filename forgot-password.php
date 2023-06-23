<?php
session_start();
require_once "include/db-conn.php";

if (isset($_SESSION["Usuario"])) {
    header("location:catalogos/dashboard.php");
}

if (empty($_SESSION['token_ld10d'])) {
    $_SESSION['token_ld10d'] = bin2hex(random_bytes(32));
}
$token = $_SESSION['token_ld10d'];

if(!isset($_SESSION['pass_attempt'])){
    $_SESSION['pass_attempt'] = 1;
}

date_default_timezone_set('America/Mexico_City');
if(!isset($_SESSION['pass_attempt_time'])){
    $_SESSION['pass_attempt_time'] = date('Y-m-d H:i:s');
}
/*
echo date('Y-m-d H:i:s')." -- ".strtotime(date('Y-m-d H:i:s'))." //";
echo $_SESSION['pass_attempt_time']." -- ".strtotime($_SESSION['pass_attempt_time']);
$_SESSION['pass_attempt_time'] = date('Y-m-d H:i:s');
$_SESSION['pass_attempt'] = 1;*/
$sin_permiso = 0;
if(strtotime(date('Y-m-d H:i:s')) < strtotime($_SESSION['pass_attempt_time'])){
  $sin_permiso = 1;
}
else{
  $_SESSION['pass_attempt'] = 1;
  $_SESSION['pass_attempt_time'] = date('Y-m-d H:i:s');
}
$hora = date("H:i:s A", strtotime($_SESSION['pass_attempt_time']));
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

  <title>Timlid | Forgot Password</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">
  <link href="css/styles.css" rel="stylesheet">
  <script src="js/sweetalert.min.js"></script>
</head>

<body class="bg-gradient-login">
  <div class="container">

    <div class="center-me">
      <!-- Outer Row -->
      <div class="row justify-content-center">

        <div class="col-xl-10 col-lg-12 col-md-9">

          <div class="cardPassword o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
              <!-- Nested Row within Card Body -->
              <div class="row">
                <div class="col-lg-8" style="background-color: rgba(5,61,117,0.7);">
                  <div class="p-5">
                    <h2 class="mb-4 text-white">Restablecer contraseña</h2>
                    <form class="user" method="post">
                      <label class="lblLogin">Email</label>
                      <div class="form-group inputLogin">
                        <input type="email" id="exampleInputEmail" name="Usuario" required>
                        <span class="underline-animation"></span>
                        <input type="hidden" name="csr_token_78L4" id="csr_token_78L4" value="<?=$token?>">
                      </div>
                      <div class="cont-button-login">
                        <?php 
                        
                          echo '<center id="mostrar-permiso" ';
                            if($sin_permiso == 1){
                              echo 'style="display:block"';
                            }
                            else{
                              echo 'style="display:none"';
                            }

                          echo '><span style="color: #dc3545;position:relative;">Has excedido el máximo numero de intentos para ingresar un email válido, puedes volver a intentarlo a las '.$hora.'. <br> <a href="forgot-password" style="color: white;"> Actualiza la página para volver a intentar</a></span></center>';
                        
                        if($sin_permiso == 0){
                            echo '<button type="button" class="btn-custom btn-custom--white" name="forgot_password"
                          id="forgot_password">Generar nueva contraseña</button>';
                        }
                        ?>
                      </div>
                    </form>
                    <br>
                  </div>
                </div>
                <div class="col-lg-4 d-none d-lg-flex bg-light" id="imageBackground">
                  <div class="imgJumping">
                    <img src="img/candado.gif" width="100%">
                  </div>
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
  <script>
  $("#forgot_password").click(function() {

    var email = $("#exampleInputEmail").val();

    if (email.trim() == "") {
      $("#exampleInputEmail")[0].reportValidity();
      $("#exampleInputEmail")[0].setCustomValidity('Completa este campo.');
      return;
    }

    $("#forgot_password").prop("disabled",true);
    let crf_token_l125 = $("#csr_token_78L4").val();

    $.post("catalogos/enviarpassword.php", {
      Email: email,
      csr_token_78L4: crf_token_l125
    }, function(data) {
      if (data == "exito") {
        swal("¡Éxito!",
          "¡Bienvenido! Se ha enviado un email a tu cuenta de correo electrónico, ahi podrás cambiar tu contraseña.",
          "success");

        setTimeout(function() {
          window.location.replace("index.php");
          $("#forgot_password").prop("disabled",false);
        }, 5000);
      } 

      if (data == "fallo"){
        swal("Hubo un error", "No se encuentra el correo electrónico, favor de verificarlo.", "error");
        $("#forgot_password").prop("disabled",false);
      }

      if (data == "fallo-email"){
        swal("Hubo un error", "No se envio el correo electrónico, favor de intentarlo nuevamente.", "error");
        $("#forgot_password").prop("disabled",false);
      }

      if (data == "error-general" || data == "error-general2") {
            swal("Hubo un error", "Ocurrio un error, vuelve a ingresar tus datos.", "error");

      }

      if (data == "tiempo-out") {
            $("#mostrar-permiso").css("display","block");
            $("#forgot_password").css("display","none");
            swal("Hubo un error", "Tienes que esperar 5 minutos para volver a intentarlo.", "error");
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
  </script>

</body>

</html>