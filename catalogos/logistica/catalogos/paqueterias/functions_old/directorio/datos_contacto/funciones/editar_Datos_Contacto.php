<?php
session_start();

  if(isset($_SESSION["Usuario"]) && ($_SESSION["FKRol"] == 4 || $_SESSION["FKRol"] == 1)){
    require_once('../../../../../include/db-conn.php');
      if(isset ($_POST['btnEditar'])){
        $id = $_POST['txtIdContacto'];
        $paqueteria = $_POST['txtIdPaqueteria'];
        $nombre = $_POST['txtNombre'];
        $apellido = $_POST['txtApellido'];
        $puesto = $_POST['txtPuesto'];
        $telefono = $_POST['txtTelefono'];
        $celular= $_POST['txtCelular'];
        $email = $_POST['txtEmail'];
        try{
        $stmt = $conn->prepare('UPDATE datos_contacto_paqueterias set Nombre= :nombre,Apellido_Paterno= :apellido,Puesto= :puesto,Telefono= :telefono,Celular= :celular,Email= :email WHERE PKContacto_Paqueteria = :id');

        $stmt->bindValue(':nombre',$nombre);
        $stmt->bindValue(':apellido',$apellido);
        $stmt->bindValue(':puesto',$puesto);
        $stmt->bindValue(':telefono',$telefono);
        $stmt->bindValue(':celular',$celular);
        $stmt->bindValue(':email',$email);
        $stmt->bindValue(':id',$id);
        $stmt->execute();
          header('Location:../index.php?id='.$paqueteria);
        }catch(PDOException $ex){
          echo $ex->getMessage();
        }
      }

      if(isset($_POST['idContactoU'])){
        $id =  $_POST['idContactoU'];
        $stmt = $conn->prepare('SELECT * FROM datos_contacto_paqueterias WHERE PKContacto_Paqueteria= :id');
        $stmt->execute(array(':id'=>$id));
        $row = $stmt->fetch();
        $nombre = $row['Nombre'];
        $apellido = $row['Apellido_Paterno'];
        $puesto = $row['Puesto'];
        $telefono = $row['Telefono'];
        $celular = $row['Celular'];
        $email = $row['Email'];
        $paqueteria = $row['FKPaqueteria'];
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

  <title>Timlid | Editar Paqueteria</title>

  <!-- Bootstrap core JavaScript-->
  <script src="../../../../../vendor/jquery/jquery.min.js"></script>
  <script src="../../../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../../../../js/validaciones.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="../../../../../vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="../../../../../js/sb-admin-2.min.js"></script>
  <script src="../../../../../js/bootstrap-clockpicker.min.js"></script>

  <!-- Custom fonts for this template-->
  <link href="../../../../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../../../../../css/bootstrap-clockpicker.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="../../../../../css/sb-admin-2.css" rel="stylesheet">

  <script>
      $( document ).ready(function() {
        var monto = $("#txtMonto").val();
        var dias = $("#txtDias").val();
        if(monto!="" || dias!=""){
          $('#grupoCredito').prop('checked', true);
          //$("input.grupoCredito").prop("disabled", this.checked);
          $(function() {
              $("#grupoCredito").click(activarCredito);
            });

            function activarCredito() {
              $("input.grupoCredito").prop("disabled", !this.checked);
            }
        }else{
          $('#grupoCredito').prop('checked', false);
          $(function() {
              activarCredito();
              $("#grupoCredito").click(activarCredito);
            });

            function activarCredito() {
              $("input.grupoCredito").prop("disabled", !this.checked);
            }
        }

      });
  </script>


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
            <h1 class="h3 mb-0 text-gray-800">Editar email</h1>
          </div>


          <div class="row">

            <div class="col-lg-12">

              <!-- Basic Card Example -->
              <div class="card shadow mb-4">
                <div class="card-header">
                  Cuenta de correo
                </div>
                <div class="card-body">
                    <div class="row">
                      <div class="col-lg-12">
                        <form action="" method="post">
                          <input type="hidden" name="txtIdContacto" value="<?=$id; ?>">
                          <input type="hidden" name="txtIdPaqueteria" value="<?=$paqueteria; ?>">
                          <div class="form-group">
                            <div class="row">
                              <div class="col-lg-4">
                                <label for="usr">Nombre:*</label>
                                <input type="text" class="form-control alpha-only" maxlength="20" name="txtNombre" value="<?=$nombre;?>" required>
                              </div>
                              <div class="col-lg-4">
                                <label for="usr">Apellido:*</label>
                                <input type="text" class="form-control alpha-only" maxlength="20" name="txtApellido" value="<?=$apellido;?>" required>
                              </div>
                              <div class="col-lg-4">
                                <label for="usr">Puesto:*</label>
                                <input type="text" class="form-control alpha-only" maxlength="20" name="txtPuesto" value="<?=$puesto;?>" required>
                              </div>
                            </div><br>
                            <div class="row">
                              <div class="col-lg-4">
                                <label for="usr">Teléfono:*</label>
                                <input type="text" class="form-control numeric-only" maxlength="10" name="txtTelefono" value="<?=$telefono;?>" required>
                              </div>
                              <div class="col-lg-4">
                                <label for="usr">Celular:*</label>
                                <input type="text" class="form-control numeric-only" maxlength="13" name="txtCelular" value="<?=$celular;?>" required>
                              </div>
                              <div class="col-lg-4">
                                <label for="usr">Email:*</label>
                                <input type="email" class="form-control alphaNumeric-only" maxlength="40" value="<?=$email;?>" name="txtEmail" required>
                              </div>
                            </div>
                          </div>
                          <label for="">* Campos requeridos</label>
                          <button type="submit" class="btn btn-primary float-right"  name="btnEditar">Editar</button>
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
