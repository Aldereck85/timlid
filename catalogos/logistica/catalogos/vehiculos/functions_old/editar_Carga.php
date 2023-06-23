<?php
session_start();

  if(isset($_SESSION["Usuario"]) && ($_SESSION["FKRol"] == 4)){
    require_once('../../../include/db-conn.php');
      if(isset ($_POST['btnEditar'])){
        $id = $_POST['txtId'];
        $vehiculo = $_POST['txtVehiculo'];
        $fecha = $_POST['txtFecha'];
        $cantidad = $_POST['txtCantidad'];
        $costo = $_POST['txtCosto'];
        $odometro = $_POST['txtOdometro'];
        $tanque = $_POST['cmbTanque'];
        try{
        $stmt = $conn->prepare('UPDATE combustible set Fecha_Carga= :fecha, Cantidad= :cantidad,Costo_Unitario= :costo,Odometro= :odometro,Tanque_Lleno= :tanque WHERE PKCombustible = :id');
          $stmt->bindValue(':fecha',$fecha);
          $stmt->bindValue(':cantidad',$cantidad);
          $stmt->bindValue(':costo',$costo);
          $stmt->bindValue(':odometro',$odometro);
          $stmt->bindValue(':tanque',$tanque);
          $stmt->bindValue(':id',$id);
          $stmt->execute();
          header('Location:../combustible.php?id='.$vehiculo);
        }catch(PDOException $ex){
          echo $ex->getMessage();
        }
      }

      if(isset($_GET['id'])){
        $id =  $_GET['id'];
        $vehiculo =  $_GET['vehiculo'];
        $stmt = $conn->prepare('SELECT * FROM combustible WHERE PKCombustible= :id');
        $stmt->execute(array(':id'=>$id));
        $row = $stmt->fetch();
        $fecha = $row['Fecha_Carga'];
        $cantidad = $row['Cantidad'];
        $costo = $row['Costo_Unitario'];
        $odometro = $row['Odometro'];
        $tanque = $row['Tanque_Lleno'];
      }
  }else {
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

  <title>Timlid | Editar vehiculo</title>

  <!-- Bootstrap core JavaScript-->
  <script src="../../../vendor/jquery/jquery.min.js"></script>
  <script src="../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../../js/validaciones.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="../../../vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="../../../js/sb-admin-2.min.js"></script>
  <script src="../../../js/bootstrap-clockpicker.min.js"></script>

  <!-- Custom fonts for this template-->
  <link href="../../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../../../css/bootstrap-clockpicker.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="../../../css/sb-admin-2.css" rel="stylesheet">
</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <?php
      $ruta = "../../";
      $ruteEdit = $ruta."central_notificaciones/";
      require_once('../../menu3.php');
    ?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

          <!-- Sidebar Toggle (Topbar) -->
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>



          <!-- Topbar Navbar -->
          <ul class="navbar-nav ml-auto">
            <div id="alertaTareas"></div>
            <div class="topbar-divider d-none d-sm-block"></div>

            <!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $_SESSION["Usuario"] ?></span>
                <i class="fas fa-user-circle fa-3x"></i>
              </a>
              <!-- Dropdown - User Information -->
              <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="#">
                  <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                  Perfil
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                  <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                  Salir
                </a>
              </div>
            </li>

          </ul>

        </nav>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Editar vehiculo</h1>
          </div>


          <div class="row">

            <div class="col-lg-12">

              <!-- Basic Card Example -->
              <div class="card shadow mb-4">
                <div class="card-header">

                  <div class="row">
                    Información del vehiculo
                  </div>
                </div>
                <div class="card-body">
                    <div class="row">
                      <div class="col-lg-12">
                        <form action="" method="post">
                          <div class="form-group">
                            <div class="row">
                              <div class="col-lg-6">
                                <label for="usr">Fecha de carga:</label>
                                <input type="date" class="form-control" maxlength="20" name="txtFecha" value="<?=$fecha;?>">
                              </div>
                              <div class="col-lg-6">
                                <label for="usr">Cantidad:</label>
                                <div class="input-group mb-3">
                                  <input type="text" class="form-control numericDecimal-only" maxlength="20" name="txtCantidad" value="<?=$cantidad;?>">
                                  <div class="input-group-append">
                                    <span class="input-group-text" id="basic-addon2">lts</span>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-lg-5">
                                <label for="usr">Costo unitario:</label>
                                <div class="input-group mb-3">
                                  <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">$</span>
                                  </div>
                                  <input type="text" class="form-control numericDecimal-only" name="txtCosto" value="<?=$costo;?>">
                                </div>

                              </div>
                              <div class="col-lg-5">
                                <label for="usr">Odometro:</label>
                                <input type="text" class="form-control numeric-only" name="txtOdometro" value="<?=$odometro;?>">
                              </div>
                              <div class="col-lg-2">
                                <label for="usr">Tanque lleno:</label>
                                <select name="cmbTanque" class="form-control" required>
                                  <option value="" >Elegir opción</option>
                                  <option value="1" <?php if ($tanque == 1 ) echo 'selected'; ?>>Si</option>
                                  <option value="0" <?php if ($tanque == 0 ) echo 'selected'; ?>>No</option>
                                </select>
                              </div>
                            </div>
                          </div>

                          <input type="hidden" name="txtId" value="<?=$id;?>">
                          <input type="hidden" name="txtVehiculo" value="<?=$vehiculo;?>">
                          <button type="submit" class="btn btn-primary float-right" name="btnEditar" id="btnEditar">Editar</button>
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
        require_once('../../footer.php');
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

  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">¿En serio quieres salir?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">x</span>
          </button>
        </div>
        <div class="modal-body">Selecciona "Salir" Para cerrar tu sesión</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
          <a class="btn btn-primary" href="../../logout.php">Salir</a>
        </div>
      </div>
    </div>
  </div>

  <script>
    $(document).ready(function(){
      $("#alertaTareas").load('../alerta_Tareas_Nuevas.php?user='+<?=$_SESSION['PKUsuario'];?>+'&ruta='+'<?=$ruta;?>');
        setInterval(refrescar, 5000);
      });
    function refrescar(){
      $("#alertaTareas").load('../alerta_Tareas_Nuevas.php?user='+<?=$_SESSION['PKUsuario'];?>+'&ruta='+'<?=$ruta;?>');
    }
  </script>

</body>

</html>
