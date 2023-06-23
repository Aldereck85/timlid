<?php
session_start();

  if(isset($_SESSION["Usuario"])){
    require_once('../include/db-conn.php');
    $stmt = $conn->prepare('SELECT count(*) FROM empleados');
    $stmt->execute();
    $numero_de_empleados = $stmt->fetchColumn();

    $stmt = $conn->prepare('SELECT count(MONTH(Fecha_de_Nacimiento)) FROM empleados WHERE MONTH(Fecha_de_Nacimiento) = :mes');
    $stmt->bindValue(':mes',date("m"));
    $stmt->execute();
    $cumple = $stmt->fetchColumn();

    $stmt = $conn->prepare('SELECT count(*) FROM facturas');
    $stmt->execute();
    $numero_de_facturas = $stmt->fetchColumn();

    $user = $_SESSION["Usuario"];
  }else {
    header("location:../index.php");
  }

  $stmt = $conn->prepare('SELECT count(*) FROM clientes WHERE FKEstatus = 1');
  $stmt->execute();
  $numero_de_toque1 = $stmt->fetchColumn();

  $stmt = $conn->prepare('SELECT count(*) FROM clientes WHERE FKEstatus = 2');
  $stmt->execute();
  $numero_de_toque2 = $stmt->fetchColumn();

  $stmt = $conn->prepare('SELECT count(*) FROM clientes WHERE FKEstatus = 3');
  $stmt->execute();
  $numero_de_toque3 = $stmt->fetchColumn();

  $stmt = $conn->prepare('SELECT count(*) FROM clientes WHERE FKEstatus = 4');
  $stmt->execute();
  $numero_de_clientes = $stmt->fetchColumn();

  $stmt = $conn->prepare('SELECT count(*) FROM clientes WHERE FKEstatus = 5');
  $stmt->execute();
  $numero_de_prospectos_inactivos = $stmt->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <link rel="icon" type="image/png" href="../img/header/bluTimlid.png">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">



  <title>Timlid | Dashboard</title>

  <!-- Bootstrap core JavaScript-->
  <script src="../vendor/jquery/jquery.min.js"></script>
  <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="../js/sb-admin-2.min.js"></script>

  <!-- Page level plugins -->
  <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>
  <script src="../vendor/chart.js/Chart.min.js"></script>

  <!-- Page level custom scripts
  <script src="js/demo/datatables-demo.js"></script>
  -->

  <!-- Custom fonts for this template -->
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="../css/sb-admin-2.css" rel="stylesheet">
  <link href="../css/dashboard.css" rel="stylesheet">

  <!-- Custom styles for this page -->
  <link href="../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">


</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
      <?php
        $ruta = "";
        $ruteEdit = $ruta."central_notificaciones/";
        require_once('menu3.php');
      ?>

    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">
        <div id="TopbarDiv">


        <!-- Topbar -->
        <nav id="topbar" class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
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
        </div>
        <!-- Begin Page Content -->
        <div class="container-fluid">
          <!-- Page Heading -->
          <h1 class="h3 mb-2 text-gray-800">Bienvenido</h1>
          <p class="mb-4">Al sistema de control de personal</p>
          <div class="row">
            <div class="col-lg-4">
              <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-primary mb-1">Empleados</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">Total: <?php echo $numero_de_empleados;?></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-id-card-alt fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-4">
              <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-primary mb-1">Cumpleañeros</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">Total: <?php echo $cumple;?></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-birthday-cake  fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-4">
              <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-primary mb-1">Facturas</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">Total: <?php echo $numero_de_facturas;?></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-file-invoice fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-lg-6">
              <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">Bienvenido</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                  <img src="../img/bienvenido.png" width="400px" >

                </div>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">Clientes y prospectos</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                  <div class="chart-pie pt-4">
                    <canvas id="myPieChart"></canvas>
                  </div>
                  <hr>
                  <div class="mt-4 text-center small">
                    <span class="mr-2">
                      <i class="fas fa-circle text-success"></i> Toque 1
                    </span>
                    <span class="mr-2">
                      <i class="fas fa-circle text-warning"></i> Toque 2
                    </span>
                    <span class="mr-2">
                      <i class="fas fa-circle text-danger"></i> Toque 3
                    </span>
                    <span class="mr-2">
                      <i class="fas fa-circle text-primary"></i> Cliente
                    </span>
                    <span class="mr-2">
                      <i class="fas fa-circle text-Secondary"></i> Prospectos inactivos
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div><br>
          <!--
          <div class="row">
            <div class="col-lg-6">
              <a href="turnos/" style="color:white">
                <div class="divMenuDashboard">
                  <center><i class="fas fa-address-card"></i> <br>Turnos</center>
                </div>
              </a>
            </div>
            <div class="col-lg-6">
              <a href="usuarios" style="color:white">
                <div class="divMenuDashboard">
                  <center><i class="fas fa-address-card"></i> <br>Usuarios</center>
                </div>
              </a>

            </div>
          </div>
-->

          <!-- DataTales Example
          <div class="card shadow mb-4">
            <div class="card-header py-3">

            </div>
            <div class="card-body">
            </div>
          </div>
          -->
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

  <!-- Logout Modal-->
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
          <a class="btn btn-primary" href="logout.php">Salir</a>
        </div>
      </div>
    </div>
  </div>


  <script>
  // Set new default font family and font color to mimic Bootstrap's default styling
  Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
  Chart.defaults.global.defaultFontColor = '#858796';

  // Pie Chart Example
  var ctx = document.getElementById("myPieChart");
  var myPieChart = new Chart(ctx, {
    type: 'doughnut',
    data: {
      labels: ["Toque 1","Toque 2","Toque 3","Clientes","Prospectos inactivos"],
      datasets: [{
        data: [<?php echo $numero_de_toque1; ?>,<?php echo $numero_de_toque2; ?>,<?php echo $numero_de_toque3; ?>, <?php echo $numero_de_clientes; ?>,<?php echo $numero_de_prospectos_inactivos ?>],
        backgroundColor: ['#1cc88a', '#f6c23e','#e74a3b','#4e73df','#858796'],
        hoverBackgroundColor: ['#169b6b','#D3A121','#9F281D','#224abe','#6b6d7d'],
        hoverBorderColor: "rgba(234, 236, 244, 1)",
      }],
    },
    options: {
      maintainAspectRatio: false,
      tooltips: {
        backgroundColor: "rgb(255,255,255)",
        bodyFontColor: "#858796",
        borderColor: '#dddfeb',
        borderWidth: 1,
        xPadding: 15,
        yPadding: 15,
        displayColors: false,
        caretPadding: 10,
      },
      legend: {
        display: false
      },
      cutoutPercentage: 80,
    },
  });

  $(document).ready(function(){
    $("#alertaTareas").load('alerta_Tareas_Nuevas.php?user='+<?=$_SESSION['PKUsuario'];?>+'&ruta='+'<?=$ruta;?>');
      setInterval(refrescar, 5000);
    });
  function refrescar(){
    $("#alertaTareas").load('alerta_Tareas_Nuevas.php?user='+<?=$_SESSION['PKUsuario'];?>+'&ruta='+'<?=$ruta;?>');
  }


  </script>
</body>

</html>
