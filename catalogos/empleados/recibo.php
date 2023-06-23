<?php
session_start();

  if(isset($_SESSION["Usuario"]) && ($_SESSION["FKRol"] == 1 || $_SESSION["FKRol"] == 4)){

    require_once('../../include/db-conn.php');
    $IdVacaciones = $_GET['id'];

    /*$stmt = $conn->prepare("SELECT v.*, t.Turno, p.Puesto, e.PKEmpleado, e.NSS, e.RFC, e.Primer_Nombre, e.Segundo_Nombre, e.Apellido_Paterno, e.Apellido_Materno, p.Sueldo_semanal,de.Dias_de_Vacaciones, SUM(v.Dias_de_Vacaciones_Tomados) as Dias_de_Vacaciones_Total
                            FROM vacaciones as v
                            INNER JOIN empleados as e ON e.PKEmpleado = v.FKEmpleado
                            LEFT JOIN datos_empleo as de ON de.FKEmpleado = e.PKEmpleado
                            LEFT JOIN puestos as p ON p.PKPuesto = de.FKPuesto
                            LEFT JOIN turnos as t ON t.PKTurno = de.FKTurno
                            WHERE v.PKVacaciones = :id");
    $stmt->bindValue(':id',$IdVacaciones);
    $stmt->execute();
    $row = $stmt->fetch();
    $fkEmpleado = $row['PKEmpleado'];
*/
    $segundo_nombre = '';
    if(trim($row['Segundo_Nombre']) != ""){
      $segundo_nombre = ' '.$row['Segundo_Nombre'];
    }

    $nombreEmpleado = $row['Primer_Nombre'].$segundo_nombre.' '.$row['Apellido_Paterno'].' '.$row['Apellido_Materno'];
    $nss = $row['NSS'];
    $rfc = $row['RFC'];
    $turno = $row['Turno'];
    $puesto = $row['Puesto'];
    $fecha_ini = $row['FechaIni'];
    $fecha_fin = $row['FechaFin'];

    $sueldoSemanal = $row['Sueldo_semanal'];
    $dias_vacaciones = $row['Dias_de_Vacaciones'];
    $dias_vacaciones_tomados = $row['Dias_de_Vacaciones_Tomados'];
    $dias_vacaciones_restantes =  $dias_vacaciones - $dias_vacaciones_tomados;

  }else {
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

  <title>Timlid | Vacaciones</title>

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
    <?php
      $ruta = "../";
      $ruteEdit = $ruta."central_notificaciones/";
      require_once('../menu3.php');
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
          <h1 class="h3 mb-2 text-gray-800">Vacaciones</h1>

          <?php

            $sueldoVacaciones = number_format($row['Total_Vacaciones'] - $row['Prima_Vacacional'],2,'.','');
            $primaVacacional = number_format($row['Prima_Vacacional'],2,'.','');
            $sueldoTotal = number_format($row['Total_Vacaciones'],2,'.','');

          ?>

          <div class="card shadow mb-4" id="divRecibo">
            <div class="card-header py-3">
              <div class="row">
                <div class="col-lg-10">
                  <b>Recibo</b>
                </div>
                <div class="col-lg-2">

                </div>
              </div>

            </div>
            <div class="card-body">
            <form action="" method="post" id="frmNomina">
                <br>
                <div class="row">
                  <div class="col-lg-12">
                    <center><h4>Recibo de Vacaciones</h4></center><br>
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-4">
                    <label><b>Nombre:</b> <?=$nombreEmpleado;?></label><br>
                    <label><b>NSS:</b> <?=$nss;?></label><br>
                    <label><b>RFC:</b> <?=$rfc;?></label><br>
                  </div>
                  <div class="col-lg-4">

                  </div>
                  <div class="col-lg-4">

                    <label><b>Turno:</b> <?=$turno;?></label><br>
                    <label><b>Puesto:</b> <?=$puesto;?></label><br>
                    <label><b>Periodo de pago: </b><?=$fecha_ini;?></label>
                  </div>
                </div>
                <br>
                <div class="row">
                  <div class="col-lg-3">
                    <div class="row">
                      <div class="col-lg-4">
                        <b>Acciones</b>
                      </div>
                      <div class="col-lg-8">
                        <b>Concepto</b>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <b><label class="float-right">Percepción</label></b>
                  </div>
                  <div class="col-lg-3">
                    <b><label class="float-right">Deducción</label></b>
                  </div>
                  <div class="col-lg-3">
                    <b><label class="float-right">Total</label></b>
                  </div>
                </div>
                <hr>

                <div class="row">
                  <div class="col-lg-3">
                    <div class="row">
                      <div class="col-lg-4">

                      </div>
                      <div class="col-lg-8">
                        Sueldo vacaciones
                      </div>
                    </div>

                  </div>
                  <div class="col-lg-3">
                    <label class="float-right"><?=$sueldoVacaciones;?></label>
                  </div>
                  <div class="col-lg-3">
                  </div>
                  <div class="col-lg-3">
                  </div>
                </div>

                <div class="row">
                  <div class="col-lg-3">
                    <div class="row">
                      <div class="col-lg-4">
                      </div>
                      <div class="col-lg-8">
                        Prima vacacional
                      </div>
                    </div>

                    <br>
                  </div>
                  <div class="col-lg-3">
                    <label id="lblPrimaVacacional" class="float-right"><?=$primaVacacional;?></label>
                  </div>
                  <div class="col-lg-3">
                  </div>
                  <div class="col-lg-3">
                  </div>
                </div>

                <hr>
                <div class="row">
                  <div class="col-lg-3">
                  </div>
                  <div class="col-lg-3">
                  </div>
                  <div class="col-lg-3">
                  </div>
                  <div class="col-lg-3">
                    <label name="lblTotal" id="lblTotal" class="float-right"><?=number_format($sueldoTotal, 2, '.', '');?></label>
                  </div>
                </div>
                <button type="submit" class="btn btn-success float-right" name="btnAgregar" id="btnAgregar">Imprimir recibo</button>
              </form>
              <br>
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

  <script>
  $(document).ready(function(){
    $("#alertaTareas").load('../alerta_Tareas_Nuevas.php?user='+<?=$_SESSION['PKUsuario'];?>+'&ruta='+'<?=$ruta;?>');
      setInterval(refrescar, 5000);
    });
    function refrescar(){
      $("#alertaTareas").load('../alerta_Tareas_Nuevas.php?user='+<?=$_SESSION['PKUsuario'];?>+'&ruta='+'<?=$ruta;?>');
    }
  </script>
  <script> var ruta = "../";</script>
  <script src="../../js/sb-admin-2.min.js"></script>

</body>

</html>
