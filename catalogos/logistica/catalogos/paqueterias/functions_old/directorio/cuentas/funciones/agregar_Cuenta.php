<?php
session_start();

  if(isset($_SESSION["Usuario"]) && ($_SESSION["FKRol"] == 4 || $_SESSION["FKRol"] == 1)){
    require_once('../../../../../include/db-conn.php');
      if(isset ($_POST['btnAgregar'])){

        if(isset($_GET['id'])){
          $id =  $_GET['id'];
        }
          $banco = (int) $_POST['cmbBanco'];
          $cuenta = $_POST['txtCuenta'];
          $clabe = $_POST['txtCLABE'];
          try{
            $stmt = $conn->prepare('INSERT INTO cuentas_bancarias_paqueterias (FKBanco,FKPaqueteria,No_de_cuenta,CLABE) VALUES (:banco,:paqueteria,:cuenta,:clabe)');
            $stmt->bindValue(':banco',$banco);
            $stmt->bindValue(':paqueteria',$id);
            $stmt->bindValue(':cuenta',$cuenta);
            $stmt->bindValue(':clabe',$clabe);
            $stmt->execute();
            header('Location:../index.php?id='.$id);
          }catch(PDOException $ex){
            echo $ex->getMessage();
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

  <title>Timlid | Agregar cuenta bancaria proveedor</title>

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
            <h1 class="h3 mb-0 text-gray-800">Agregar cuenta bancaria</h1>
          </div>


          <div class="row">

            <div class="col-lg-12">

              <!-- Basic Card Example -->
              <div class="card shadow mb-4">
                <div class="card-header">
                  Nueva cuenta
                </div>
                <div class="card-body">
                    <div class="row">
                      <div class="col-lg-12">
                        <form action="" method="post">
                          <div class="form-group">
                            <div class="row">
                              <div class="col-lg-4">
                                <label for="usr">Banco:*</label>
                                <select name="cmbBanco" id="cmbBanco" class="form-control" required>
                                    <option value="">Elegir opción</option>
                                        <?php
                                            $stmt = $conn->prepare('SELECT * FROM bancos');
                                            $stmt->execute();
                                        ?>
                                        <?php foreach($stmt as $option) : ?>
                                             <option value="<?php echo $option['PKBanco']; ?>"><?php echo $option['Nombre']; ?></option>
                                        <?php endforeach; ?>
                                </select>
                              </div>
                              <div class="col-lg-4">
                                <label for="usr">No de cuenta:*</label>
                                <div class="input-group mb-3">
                                  <input type="text" class="form-control alphaNumericnumeric-only" maxlength="24" name="txtCuenta" required>
                                </div>
                              </div>
                              <div class="col-lg-4">
                                <label for="usr">CLABE:*</label>
                                <input type="text" class="form-control numeric-only" maxlength="18" name="txtCLABE" required>
                              </div>
                            </div>
                          </div>
                          <label for="">* Campos requeridos</label>
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
