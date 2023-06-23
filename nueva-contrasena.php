<?php
session_start();
require_once("include/db-conn.php");

if(!isset($_POST['email'])){
  header("location:./");
}

$email = $_POST['email'];
$idusuario = $_POST['idusuario'];

if(isset($_SESSION["Usuario"])){
  header("location:catalogos/dashboard.php");
}

if (empty($_SESSION['token_ld10d'])) {
    $_SESSION['token_ld10d'] = bin2hex(random_bytes(32));
}
$token = $_SESSION['token_ld10d'];

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

  <title>Timlid | Nueva contraseña</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.css" rel="stylesheet">
  <script src="js/sweetalert.min.js"></script>
  <style type="text/css">
    .inputLogin>input[type=text] {
      border: none;
      border-bottom: 2px solid white;
      outline: none;
      background: transparent !important;
      width: 100%;
      color: white;
    }

    .underline-animation {
      top: 26px;
    }

    .toggle-pass {
      cursor: pointer;
      position: absolute;
      top: 6px;
      right: 7px;
    }
  </style>
</head>

<body class="bg-gradient-login">
  <div class="container">

    <div class="center-me">
        <!-- Outer Row -->
        <div class="row justify-content-center" style="width:85%">

          <div class="col-xl-12 col-lg-12 col-md-12">
              <input type="hidden" id="idusuario" value="<?=$idusuario;?>">
              <input type="hidden" id="email" value="<?=$email;?>">
            <div class="cardPassword o-hidden border-0 shadow-lg my-5">
              <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                  <div class="col-lg-8" style="background-color: rgba(5,61,117,0.7);">
                    <div class="p-5">
                      <div class="text-center">
                        <h1 class="h4 text-gray-900 mb-4" style="color:white !important;">Nueva contraseña</h1>
                      </div>
                      <form class="user" method="post">
                        <!--Inicio-->
                        <div class="form-group pass-user container-contrasenia inputLogin">
                           <input type="password" class="newPass" id="newUpdatePassword" name="txtContrasena"
                            required onkeyup="validar_clave()" placeholder="Nueva contraseña" maxlength="40">
                            <span class="underline-animation"></span>
                            <i class="fas fa-eye-slash toggle-pass" data-pass="false"></i>
                            <div class="invalid-feedback" id="invalid-passUs">La contraseña debe tener 10 caracteres minimo,
                            al menos una letra mayuscula, un número y un caracter especial permitido (@$!%*?&).</div>
                            <div class="especial-feedback" id="especial-feedback">Caracteres especiales permitidos @$!%*?&</div>
                            <input type="hidden" name="csr_token_78L4" id="csr_token_78L4" value="<?=$token?>">
                        </div>

                        <!--Final-->
                         <center><button type="button" class="btn btn-light-timLid btn-user btn-block" name="nueva_contrasena" id="nueva_contrasena">Ingresar</button></center>
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
  <script type="text/javascript">
    /* Funcion para mostrar u ocultar la contraseña */
    $(".toggle-pass").click(function () {
      const togglePass = document.querySelector(".toggle-pass");
      if (togglePass.dataset.pass === "false") {
        togglePass.dataset.pass = "true";
        togglePass.classList.add("fa-eye");
        togglePass.classList.remove("fa-eye-slash");
        document.getElementsByName("txtContrasena")[0].setAttribute("type", "text");

      } else {
        togglePass.dataset.pass = "false";
        togglePass.classList.remove("fa-eye");
        togglePass.classList.add("fa-eye-slash");
        document.getElementsByName("txtContrasena")[0].setAttribute("type", "password");
      }
    });
  </script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>
  <script>
      let contador = 0;

      $("#nueva_contrasena").click(function(){
        
        var nueva_contrasena = $("#newUpdatePassword").val();

        if(contador == 0){
          $("#newUpdatePassword")[0].reportValidity();
          $("#newUpdatePassword")[0].setCustomValidity('Completa este campo.');
          contador = 1;
        }

        if(nueva_contrasena.trim() == ""){
          $("#newUpdatePassword")[0].reportValidity();
          $("#newUpdatePassword")[0].setCustomValidity('Completa este campo.');
          return;
        }

        if(!validar_clave()) {
          $("#newUpdatePassword")[0].reportValidity();
          $("#newUpdatePassword")[0].setCustomValidity('La contraseña no cumple con los caracteristicas necesarias.');
          return;
        }

        let idusuario = $('#idusuario').val();
        let email = $('#email').val();
        let crf_token_l125 = $("#csr_token_78L4").val();

        $.post( "catalogos/nuevacontrasena.php", { IDUsuario: idusuario, Usuario: email ,contrasena : nueva_contrasena, csr_token_78L4: crf_token_l125 } ,function( data ) {
            console.log('respuesta: ',data);
            if(data == "exito"){
              swal("¡Éxito!", "Ya puedes ingresar con tu nueva contraseña, en breve serás redirigido a la página principal del sistema.", "success");

              setTimeout(function(){
                window.location.replace("catalogos/dashboard.php");
              }, 3000);
            }

            if(data == "erroragregar"){

              swal("Alerta", "No se pudo cambiar la contraseña, favor de intentarlo nuevamente.", "error");

            }

            if(data == "noexisteusuario"){
              swal("Alerta", "No se encuentra el usuario, favor de verificarlo e ingresar su usuario y contraseña en la página del login.", "error");

              setTimeout(function(){
                window.location.replace("./");
              }, 5000);
            }

            if (data == "error-general" || data == "error-general2") {
              swal("Hubo un error", "Ocurrio un error, vuelve a ingresar tus datos.", "error");

            }
            
        });


      });

  </script>
  <script type="text/javascript">
    function validar_clave() {
        const pass = $("#newUpdatePassword").val();

        const reg = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{10,}$/;
        if (reg.test(pass)) {
          document.getElementById("invalid-passUs").style.display = "none";
          return true;
        } else {
          document.getElementById("invalid-passUs").style.display = "block";
          return false;
        }
    }

    var inputPassword = document.getElementById("newUpdatePassword");

    inputPassword.addEventListener("keyup", function(event) {
      if (event.keyCode === 13) {
        event.preventDefault();
        document.getElementById("nueva_contrasena").click();
      }
    });
  </script>
</body>

</html>
