<?php
session_start();

  if(isset($_SESSION["Usuario"]) && ($_SESSION["FKRol"] == 4)){
    require_once('../../../../../include/db-conn.php');
      if(isset ($_POST['btnAgregar'])){

        if(isset($_GET['id'])){
          $id =  $_GET['id'];
          $banco = (int) $_POST['cmbBanco'];
          $cuenta = $_POST['txtCuenta'];
          $clabe = $_POST['txtCLABE'];
          try{
            $stmt = $conn->prepare('INSERT INTO cuentas_bancarias (FKBanco,FKCliente,No_de_cuenta,CLABE)VALUES(:banco,:cliente,:cuenta,:clabe)');
            $stmt->bindValue(':banco',$banco);
            $stmt->bindValue(':cliente',$id);
            $stmt->bindValue(':cuenta',$cuenta);
            $stmt->bindValue(':clabe',$clabe);
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

  <title>Timlid | Agregar cuenta bancaria</title>

  <!-- Bootstrap core JavaScript-->
  <script src="../../../../../vendor/jquery/jquery.min.js"></script>
  <script src="../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

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
  <script>
      $( document ).ready(function() {
        $(function() {
            activarCredito();
            $("#grupoCredito").click(activarCredito);
          });

          function activarCredito() {
            $("input.grupoCredito").prop("disabled", !this.checked);
          }
      });
  </script>

</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="../../dashboard.php">
        <div class="sidebar-brand-icon">
          <i class="fas fa-users"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Timlid</div>
      </a>

      <!-- Divider -->
      <hr class="sidebar-divider my-0">

      <!-- Nav Item - Dashboard -->
      <li class="nav-item">
        <a class="nav-link" href="../../dashboard.php">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Dashboard</span></a>
      </li>
      <?php
        if($_SESSION["FKRol"] == 1 || $_SESSION["FKRol"] == 4){
          echo'
          <hr class="sidebar-divider">
          <div class="sidebar-heading">
            Recursos humanos
          </div>

          <!-- Nav Item - Pages Collapse Menu -->
          <li class="nav-item">
            <a class="nav-link collapsed" href="../../empleados/">
              <i class="fas fa-address-book"></i>
              <span>Empleados</span>
            </a>
          </li>
          ';
          if($_SESSION["FKRol"] == 4){
            echo '<li class="nav-item">
              <a class="nav-link collapsed" href="../../usuarios/">
                <i class="fas fa-address-card"></i>
                <span>Usuarios</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="../../puestos/">
                <i class="fas fa-briefcase"></i>
                <span>Puestos</span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="../../turnos/">
                <i class="far fa-calendar-alt"></i>
                <span>Turnos</span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="../../locaciones">
                <i class="fas fa-map-marker-alt"></i>
                <span>Locaciones</span></a>
            </li>';
          }
        }
       ?>

      <hr class="sidebar-divider">
      <div class="sidebar-heading">
        Ventas
      </div>
      <li class="nav-item">
        <a class="nav-link" href="../../facturas/">
          <i class="fas fa-file-invoice"></i>
          <span>Facturas</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="../../envios/">
          <i class="fas fa-shipping-fast"></i>
          <span>Envios</span></a>
      </li>
      <?php
        if($_SESSION["FKRol"] == 4){
          echo '<hr class="sidebar-divider">
          <div class="sidebar-heading">
            Compras
          </div>
          <li class="nav-item">
            <a class="nav-link" href="../../productos/">
              <i class="fas fa-boxes"></i>
              <span>Productos</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../../paqueterias/">
              <i class="fas fa-dolly"></i>
              <span>Paqueterias</span></a>
          </li>';
        }
      ?>
    </ul>
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
                                <label for="usr">Banco:</label>
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
                                <label for="usr">No de cuenta:</label>
                                <div class="input-group mb-3">
                                  <input type="text" class="form-control alphaNumeric-only" maxlength="30" name="txtCuenta">
                                </div>
                              </div>
                              <div class="col-lg-4">
                                <label for="usr">CLABE:</label>
                                <input type="text" class="form-control alphaNumeric-only" maxlength="30" name="txtCLABE">
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
      <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright &copy;  Timlid 2020</span>
          </div>
        </div>
      </footer>
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
      $("#alertaTareas").load('../../../../alerta_Tareas_Nuevas.php?user='+<?=$_SESSION['PKUsuario'];?>+'&ruta='+'<?=$ruta;?>');
        setInterval(refrescar, 5000);
      });
    function refrescar(){
      $("#alertaTareas").load('../../../../alerta_Tareas_Nuevas.php?user='+<?=$_SESSION['PKUsuario'];?>+'&ruta='+'<?=$ruta;?>');
    }
  </script>

</body>

</html>
