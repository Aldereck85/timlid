<?php
session_start();

  if(isset($_SESSION["Usuario"]) && ($_SESSION["FKRol"] == 4 || $_SESSION["FKRol"] == 1)){
    require_once('../../../../../include/db-conn.php');
      if(isset ($_POST['btnAgregar'])){

        if(isset($_GET['id'])){
          $id =  $_GET['id'];
          $nombre = $_POST['txtNombre'];
          $apellido = $_POST['txtApellido'];
          $puesto = $_POST['txtPuesto'];
          $telefono = $_POST['txtTelefono'];
          $extencion = $_POST['txtExtencion'];
          $celular= $_POST['txtCelular'];
          $email = $_POST['txtEmail'];
          try{
            $stmt = $conn->prepare('INSERT INTO datos_contacto (Nombre,Apellido_Paterno,Puesto,Telefono,Extencion,Celular,Email,FKCliente)VALUES(:nombre,:apellido,:puesto,:telefono,:extencion,:celular,:email,:cliente)');
            $stmt->bindValue(':nombre',$nombre);
            $stmt->bindValue(':apellido',$apellido);
            $stmt->bindValue(':puesto',$puesto);
            $stmt->bindValue(':telefono',$telefono);
            $stmt->bindValue(':extencion',$extencion);
            $stmt->bindValue(':celular',$celular);
            $stmt->bindValue(':email',$email);
            $stmt->bindValue(':cliente',$id);
            $stmt->execute();
            header('Location:../index.php?id='.$id);
          }catch(PDOException $ex){
            echo $ex->getMessage();
          }
        }
      }
  }else {
    header("location:../../../../dashboard.php");
  }


 ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <link rel="icon" type="image/png" href="../../../../../img/header/bluTimlid.png">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Timlid | Agregar datos de contacto</title>

  <!-- Bootstrap core JavaScript-->
  <script src="../../../../../vendor/jquery/jquery.min.js"></script>
  <script src="../../../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="../../../../../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../../../../../js/validaciones.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="../../../../../js/sb-admin-2.min.js"></script>

  <script src="../../../../../js/bootstrap-clockpicker.min.js"></script>

  <!-- Custom fonts for this template-->
  <link href="../../../../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../../../../../css/bootstrap-clockpicker.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="../../../../../css/sb-admin-2.css" rel="stylesheet">

</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <?php
      $ruta = "../../../../";
      require_once('../../../../menu3.php');
    ?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

          <?php
            $rutatb = "../../../../";
            require_once('../../../../topbar.php');
          ?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Agregar datos de contacto</h1>
          </div>


          <div class="row">

            <div class="col-lg-12">

              <!-- Basic Card Example -->
              <div class="card shadow mb-4">
                <div class="card-header">
                  Contacto
                </div>
                <div class="card-body">
                    <div class="row">
                      <div class="col-lg-12">
                        <form action="" method="post">
                          <div class="form-group">
                            <div class="row">
                              <div class="col-lg-4">
                                <label for="usr">Nombre:</label>
                                <input type="text" class="form-control alpha-only" maxlength="20" name="txtNombre" required>
                              </div>
                              <div class="col-lg-4">
                                <label for="usr">Apellido:</label>
                                <input type="text" class="form-control alpha-only" maxlength="20" name="txtApellido" required>
                              </div>
                              <div class="col-lg-4">
                                <label for="usr">Puesto:</label>
                                <input type="text" class="form-control alpha-only" maxlength="20" name="txtPuesto" required>
                              </div>
                            </div><br>
                            <div class="row">
                              <div class="col-lg-3">
                                <label for="usr">Teléfono:</label>
                                <input type="text" class="form-control numeric-only" maxlength="10" name="txtTelefono" required>
                              </div>
                              <div class="col-lg-3">
                                <label for="usr">Extención:</label>
                                <input type="text" class="form-control numeric-only" maxlength="5" name="txtExtencion" required>
                              </div>
                              <div class="col-lg-3">
                                <label for="usr">Celular:</label>
                                <input type="text" class="form-control numeric-only" maxlength="13" name="txtCelular" required>
                              </div>
                              <div class="col-lg-3">
                                <label for="usr">Email:</label>
                                <input type="email" class="form-control alphaNumeric-only" maxlength="40" name="txtEmail" required>
                              </div>
                            </div>
                          </div>

                          <button type="submit" class="btn btn-success float-right" name="btnAgregar">Agregar</button>
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
        $rutaf = "../../../../";
        require_once('../../../../footer.php');
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

  <script>
    $(document).ready(function(){
      $("#alertaTareas").load('../../../../alerta_Tareas_Nuevas.php?user='+<?=$_SESSION['PKUsuario'];?>+'&ruta='+'<?=$ruta;?>');
        setInterval(refrescar, 5000);
      });
    function refrescar(){
      $("#alertaTareas").load('../../../../alerta_Tareas_Nuevas.php?user='+<?=$_SESSION['PKUsuario'];?>+'&ruta='+'<?=$ruta;?>');
    }
  </script>
  <script> var ruta = "../../../../";</script>
  <script src="../../../../../js/sb-admin-2.min.js"></script>

</body>

</html>
