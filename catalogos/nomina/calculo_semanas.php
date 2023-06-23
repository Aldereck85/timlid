<?php
session_start();

if(isset($_SESSION["Usuario"]) && ($_SESSION["FKRol"] == 1 || $_SESSION["FKRol"] == 4)){

  require_once('../../include/db-conn.php');

  if(isset ($_POST['btnAgregar'])){
      
      $anio = $_POST['anio'];
      function getFridays($year, $format, $timezone='UTC')
      {
          $semana = 1;
          $fridays = array();
          $startDate = new DateTime("{$year}-01-01 Friday", new DateTimezone($timezone));
          $year++;
          $endDate = new DateTime("{$year}-01-08", new DateTimezone($timezone));
          $int = new DateInterval('P7D');
          foreach(new DatePeriod($startDate, $int, $endDate) as $d) {
              $fridays[] = $d->format($format);
          }

          return $fridays;
      }

      //$fridays = 
      $fridays = getFridays($anio, 'Y-m-d', 'America/Mexico_City');

      for($x = 0; $x < count($fridays); $x++){

        if($x > 0){
          //echo "INSERT INTO semanas_checador (NoSemana, FechaInicio, FechaTermino) VALUES ('".$x."','".$fridays[$x-1]." 06:00:00','".$fridays[$x]." 06:00:00')<br>";
          $fechainicio = $fridays[$x-1]." 06:00:00";
          $fechatermino = $fridays[$x]." 06:00:00";
          $stmt = $conn->prepare('SELECT PKChecador FROM semanas_checador WHERE FechaInicio = :fechainicio AND FechaTermino = :fechatermino');
          $stmt->bindValue(':fechainicio',$fechainicio);
          $stmt->bindValue(':fechatermino',$fechatermino);
          $stmt->execute();

          if($stmt->rowCount() == 0){
            $stmt = $conn->prepare("INSERT INTO semanas_checador (NoSemana, FechaInicio, FechaTermino) VALUES (:nosemana,:fechainicio,:fechatermino)");
            $stmt->bindValue(':nosemana',$x);
            $stmt->bindValue(':fechainicio',$fechainicio);
            $stmt->bindValue(':fechatermino',$fechatermino);
            $stmt->execute();
          }

        }
      }
  }
}
else{
  header("location:../dashboard.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="icon" type="image/png" href="../../img/header/bluTimlid.png">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Timlid | Nomina semanal</title>

  <!-- Bootstrap core JavaScript-->
  <script src="../../vendor/jquery/jquery.min.js"></script>
  <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="../../js/sb-admin-2.min.js"></script>
  <script src="../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../vendor/datatables/dataTables.bootstrap4.min.js"></script>

  <!-- Page level plugins -->

  <script src="../../js/cambiar_Estatus_Asistencia.js"></script>
  <script src="../../js/nomina.js"></script>
  <script src="../../js/nomina_dobleturno.js"></script>
  <script src="../../js/nomina_horasextras.js"></script>
  <script src="../../js/paginacionNomina.js"></script>

  <!-- Custom fonts for this template -->
  <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="../../css/sb-admin-2.css" rel="stylesheet">
  <link href="../../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="../dashboard.php">
              <div class="sidebar-brand-icon">
                <img src="../../img/header/ghMedic.png"/ width="150px">
              </div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
              <a class="nav-link" href="../dashboard.php">
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
                  <a class="nav-link collapsed" href="../empleados/">
                    <i class="fas fa-address-book"></i>
                    <span>Empleados</span>
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="../nomina/">
                  <i class="fas fa-business-time"></i>
                  <span>Nomina</span></a>
                </li>
                ';
                if($_SESSION["FKRol"] == 4){
                  echo '<li class="nav-item">
                    <a class="nav-link collapsed" href="../usuarios/">
                      <i class="fas fa-address-card"></i>
                      <span>Usuarios</span>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="../puestos/">
                      <i class="fas fa-briefcase"></i>
                      <span>Puestos</span></a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="../turnos/">
                      <i class="far fa-calendar-alt"></i>
                      <span>Turnos</span></a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="../locaciones">
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
              <a class="nav-link" href="../facturas/">
                <i class="fas fa-file-invoice"></i>
                <span>Facturas</span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="../envios/">
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
                  <a class="nav-link" href="../productos/">
                    <i class="fas fa-boxes"></i>
                    <span>Productos</span></a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="../paqueterias/">
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
          <h1 class="h3 mb-2 text-gray-800">Calculo semanas</h1>

          <!-- DataTales Example -->
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <div class="row">
                <div class="col-lg-10">
                  <b>Calcular semanas</label>
                </div>
                <div class="col-lg-2">
                </div>
              </div>
            </div>
            <div class="card-body" id="divAsistencias">
              <form id="calculosemanas" method="POST" action="">
              <div class="row">
                <div class="col-lg-12">
                        <label for="anio">Año</label>
                        <select name="anio" id="anio" class="form-control">
                          <?php
                          $anioactual = date("Y");
                          for($anio = $anioactual;$anio <= $anioactual + 10; $anio++){
                              echo "<option value='".$anio."'>".$anio."</option>";
                          }
                          ?>
                        </select>
                    
                </div>
              </div>
              <br>
              <div class="row">
                  <div class="col-lg-12">
                      <button type="submit" class="btn btn-success" style="float: right;" id="btnAgregar" name="btnAgregar">Agregar semanas</button>
                  </div>
              </div>
              </form>
              <br>
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
          <a class="btn btn-primary" href="../logout.php">Salir</a>
        </div>
      </div>
    </div>
  </div>



</body>

</html>
