<?php
session_start();
require_once "include/db-conn.php";

if (isset($_SESSION["Usuario"])) {
  header("location:catalogos/dashboard.php");
}

if (empty($_SESSION["token_ld10d"])) {
  $_SESSION["token_ld10d"] = bin2hex(random_bytes(32));
}
$token = $_SESSION["token_ld10d"];
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

  <title>Timlid | Activar cuenta</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.css" rel="stylesheet">
  <link href="css/styles.css" rel="stylesheet">
  <script src="js/sweetalert.min.js"></script>

</head>

<body class="bg-gradient-login">
  <div class="container">

    <div class="center-me">
        <!-- Outer Row -->
        <div class="row justify-content-center" style="width:85%">

          <div class="col-xl-12 col-lg-12 col-md-12">

            <div class="cardPassword o-hidden border-0 shadow-lg my-5">
              <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                  <div class="col-lg-8" style="background-color: rgba(5,61,117,0.7);">
                    <div class="p-5">
                      <div class="text-center">
                        <h1 class="h4 text-gray-900 mb-4" style="color:white !important;">Activar cuenta</h1>
                      </div>
                      <form class="user" method="post">
                        <!--Inicio-->

                        <!--Final-->
                        <div class="form-group inputLogin">
                          <input type="email" id="exampleInputEmail" name="Usuario" placeholder="Email" maxlength="40" required>
                          <span class="underline-animation"></span>
                          <input type="hidden" name="csr_token_78L4" id="csr_token_78L4" value="<?= $token ?>">
                        </div>
                         <center><button type="button" class="btn btn-light-timLid btn-user btn-block" name="activar_cuenta" id="activar_cuenta">Activar</button></center>
                      </form>
                      <br>
                    </div>
                  </div>
                  <div class="col-lg-4 d-none d-lg-block"  style="background-color: rgba(255,255,255,0.7) !important;z-index: 10;">
                    <center><img src="img/active-account.svg" width="200px" style="justify-content: center;align-items: center;vertical-align: middle;position: relative; top: 50px;"></center>
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
      $("#activar_cuenta").click(function(){

        var email = $("#exampleInputEmail").val();
        
        if(email.trim() == ""){
          $("#exampleInputEmail")[0].reportValidity();
          $("#exampleInputEmail")[0].setCustomValidity('Completa este campo.');
          return;
        }

        $("#activar_cuenta").prop("disabled",true);
        var crf_token_l125 = $("#csr_token_78L4").val();

        $.post( "catalogos/activarcuenta.php", { Email: email, csr_token_78L4: crf_token_l125} ,function( data ) {
          console.log('hola');
            if(data == "exito"){
              swal("¡Éxito!", "Se ha enviado un email a tu cuenta de correo electrónico, desde ahi podrás activar tu cuenta.", "success");

              setTimeout(function(){
                window.location.replace("index.php");
                $("#activar_cuenta").prop("disabled",false);
              }, 3000);
            }

            if(data == "activo"){

              swal("Actualización", "Esta cuenta ya está activa, serás redirigido a la página para ingresar.", "info");
              setTimeout(function(){
                window.location.replace("index.php");
                $("#activar_cuenta").prop("disabled",false);
              }, 4000);
            }

            if(data == "fallo" || data == "error-general"){
              swal("Alerta", "No se encuentra el correo electrónico, favor de verificarlo.", "error");
              $("#activar_cuenta").prop("disabled",false);
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
