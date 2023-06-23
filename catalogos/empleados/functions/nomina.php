<?php
session_start();

  if(isset($_SESSION["Usuario"]) && ($_SESSION["FKRol"] == 4)){
    $empleado = $_GET['id'];
    require_once('../../../include/db-conn.php');
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

  <title>Timlid | Nomina semanal</title>

  <!-- Bootstrap core JavaScript-->
  <script src="../../../vendor/jquery/jquery.min.js"></script>
  <script src="../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../../js/validaciones.js"></script>
  <!-- Core plugin JavaScript-->
  <script src="../../../vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="../../../js/sb-admin-2.min.js"></script>

  <script src="../../../js/bootstrap-clockpicker.min.js"></script>

  <script src="../../../vendor/datatables/jquery.dataTables.min.js"></script>

  <script src="../../../vendor/datatables/dataTables.bootstrap4.min.js"></script>

  <!-- Custom fonts for this template-->
  <link href="../../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../../../css/bootstrap-clockpicker.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
  <link href="../../../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="../../../css/sb-admin-2.css" rel="stylesheet">

  <script>
  $(document).ready(function(){
    var idioma_espanol = {
        "sProcessing":     "Procesando...",
        "sLengthMenu":     "Mostrar _MENU_ registros",
        "sZeroRecords":    "No se encontraron resultados",
        "sEmptyTable":     "Ningún dato disponible en esta tabla",
        "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
        "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
        "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
        "sInfoPostFix":    "",
        "sSearch":         "Buscar:",
        "sUrl":            "",
        "sInfoThousands":  ",",
        "sLoadingRecords": "Cargando...",
        "oPaginate": {
            "sFirst":    "Primero",
            "sLast":     "Último",
            "sNext":     "Siguiente",
            "sPrevious": "Anterior"
        },
        "oAria": {
            "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
            "sSortDescending": ": Activar para ordenar la columna de manera descendente"
        }
    }
    $("#tblEmpleados").dataTable(
    {
      "ajax":"actualizar_Lista.php",
        "columns":[
          {"data":"Id empleado"},
          {"data":"Primer nombre"},
          {"data":"Segundo nombre"},
          {"data":"Apellido paterno"},
          {"data":"Apellido materno"},
          {"data":"Estatus"},
          {"data":"Acciones"}
        ],
        "language": idioma_espanol,
          columnDefs: [
            { orderable: false, targets: 3 }
          ],
          responsive: true
    }



    )
  });
</script>
  </script>

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
                  Profile
                </a>
                <a class="dropdown-item" href="#">
                  <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                  Settings
                </a>
                <a class="dropdown-item" href="#">
                  <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                  Activity Log
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                  <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                  Logout
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
            <h1 class="h3 mb-0 text-gray-800">Nomina semanal</h1>
          </div>


          <div class="row">

            <div class="col-lg-12">

              <!-- Basic Card Example -->
              <div class="card shadow mb-4">
                <div class="card-header">
                  Tarjeta de puestos
                </div>
                <div class="card-body">
                    <div class="row">
                      <div class="col-lg-12">
                        <form action="" method="post">
                          <div class="form-group">
                            <div class="row">
                              <div class="col-lg-12">
                                <label for="usr">Semanas de asistencia:</label>
                                <select name="cmbPeriodo" id="cmbPeriodo" class="form-control" required>
                                    <option value="">Elegir semana</option>
                                        <?php
                                            $stmt = $conn->prepare('SELECT * FROM semanas_checador');
                                            $stmt->execute();
                                        ?>
                                        <?php foreach($stmt as $option) : ?>
                                            <?php
                                              $fechaIni = date_create($option['FechaInicio']);
                                              $fechaFin = date_create($option['FechaTermino']);
                                            ?>
                                             <option value="<?php echo $option['PKChecador']; ?>"><?php echo date_format($fechaIni, 'd/m/Y')." - ".date_format($fechaFin, 'd/m/Y'); ?></option>
                                        <?php endforeach; ?>

                                </select>

                              </div>
                            </div>
                          </div>
                          <input type="hidden" name="txtId" id="txtId" value="<?=$empleado;?>">
                        </form>
                        <div class="table-responsive">
                          <table class="table table-bordered" id="tblEmpleados" width="100%" cellspacing="0">
                            <thead>
                              <tr>
                                <th>Id empleado</th>
                                <th>Fecha</th>
                                <th>Entrada</th>
                                <th>Salida Comida</th>
                                <th>Regreso Comida</th>
                                <th>Salida</th>
                                <th>Estatus</th>
                                <th>Acciones</th>
                              </tr>
                            </thead>
                            <tfoot>
                              <tr>
                                <th>Id empleado</th>
                                <th>Fecha</th>
                                <th>Entrada</th>
                                <th>Salida Comida</th>
                                <th>Regreso Comida</th>
                                <th>Salida</th>
                                <th>Estatus</th>
                                <th>Acciones</th>
                              </tr>
                            </tfoot>
                            <tbody>
                            </tbody>
                          </table>
                        </div>
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
    $("#alertaTareas").load('../../alerta_Tareas_Nuevas.php?user='+<?=$_SESSION['PKUsuario'];?>+'&ruta='+'<?=$ruta;?>');
      setInterval(refrescar, 5000);
    });
  function refrescar(){
    $("#alertaTareas").load('../../alerta_Tareas_Nuevas.php?user='+<?=$_SESSION['PKUsuario'];?>+'&ruta='+'<?=$ruta;?>');
  }
</script>
<script> var ruta = "../../";</script>
<script src="../../../js/sb-admin-2.min.js"></script>

</body>

</html>