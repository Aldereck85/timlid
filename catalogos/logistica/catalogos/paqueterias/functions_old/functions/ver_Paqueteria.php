<?php
session_start();

  if(isset($_SESSION["Usuario"]) && ($_SESSION["FKRol"] == 4 || $_SESSION["FKRol"] == 4 || $_SESSION["FKRol"] == 1)){
    require_once('../../../include/db-conn.php');

      if(isset($_GET['id'])){
        $id =  $_GET['id'];
        $stmt = $conn->prepare('SELECT * FROM paqueterias WHERE PKPaqueteria= :id');
        $stmt->execute(array(':id'=>$id));
        $row = $stmt->fetch();

        $razon = $row['Razon_Social'];
        $nombre = $row['Nombre_Comercial'];
        $rfc = $row['RFC'];
        $calle = $row['Calle'];
        $numEx = $row['Numero_Exterior'];
        $numInt = $row['Numero_Interior'];
        $colonia = $row['Colonia'];
        $municipio = $row['Municipio'];
        $estado = $row['Estado'];
        $cp = $row['CP'];
        $guias = $row['Numero_Guias'];
        $pago = $row['Tipo_de_Pago'];
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

  <title>Timlid | Editar Paqueteria</title>

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
            <h1 class="h3 mb-0 text-gray-800">Ver paqueteria</h1>
          </div>


          <div class="row">

            <div class="col-lg-12">

              <!-- Basic Card Example -->
              <div class="card shadow mb-4">
                <div class="card-header">
                  Tarjeta de la paqueteria
                </div>
                <div class="card-body">
                    <div class="row">
                      <div class="col-lg-12">
                        <form action="" method="post">
                          <div class="form-group">
                            <div class="row">
                              <div class="col-lg-4">
                                <label for="usr">Razón social:</label>
                                <input type="text" class="form-control alphaNumeric-only" maxlength="30" name="txtRazon" value="<?=$razon;?>" disabled>
                              </div>
                              <div class="col-lg-4">
                                <label for="usr">Nombre comercial:</label>
                                <div class="input-group mb-3">
                                  <input type="text" class="form-control alphaNumeric-only" maxlength="30" name="txtNombre" value="<?=$nombre;?>" disabled>
                                </div>
                              </div>
                              <div class="col-lg-4">
                                <label for="usr">RFC:</label>
                                <input type="text" class="form-control alphaNumeric-only" maxlength="30" name="txtRFC" value="<?=$rfc;?>" disabled>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-lg-3">
                                <label for="usr">Calle:</label>
                                <input type="text" class="form-control alpha-only" maxlength="30" name="txtCalle" value="<?=$calle;?>" disabled>
                              </div>
                              <div class="col-lg-3">
                                <label for="usr">Numero exterior:</label>
                                <div class="input-group mb-3">
                                  <input type="text" class="form-control numeric-only" maxlength="30" name="txtNumeroEx" value="<?=$numEx;?>" disabled>
                                </div>
                              </div>
                              <div class="col-lg-3">
                                <label for="usr">Numero interior:</label>
                                <?php
                                  if($numInt == 0){
                                    $numInt = 'S/N';
                                  }
                                ?>
                                <input type="text" class="form-control alphaNumeric-only" maxlength="30" name="txtNumeroInt" value="<?=$numInt;?>" disabled>
                              </div>
                              <div class="col-lg-3">
                                <label for="usr">Colonia:</label>
                                <input type="text" class="form-control alpha-only" maxlength="30" name="txtColonia" value="<?=$colonia;?>" disabled>
                              </div>
                            </div>

                            <div class="row">
                              <div class="col-lg-4">
                                <label for="usr">Municipio:</label>
                                <div class="input-group mb-3">
                                  <input type="text" class="form-control alpha-only" maxlength="30" name="txtMunicipio" value="<?=$municipio;?>" disabled>
                                </div>
                              </div>
                              <div class="col-lg-4">
                                <label for="usr">Estado:</label>
                                <input class="form-control" type="text" name="" value="<?=$estado; ?>" disabled>
                              </div>
                              <div class="col-lg-4">
                                <label for="usr">Código Postal:</label>
                                <input type="text" class="form-control numeric-only" maxlength="30" name="txtCP" value="<?=$cp;?>" disabled>
                              </div>

                            </div>
                            <div class="row">
                              <div class="col-lg-6">
                                <label for="usr">Numero de guias:</label>
                                <input class="form-control" type="numeric-only" name="" value="<?=$guias; ?>" disabled>
                              </div>
                              <div class="col-lg-6">
                                <label for="usr">Tipo de Pago:</label>
                                <?php
                                  if($pago == 0){
                                    $tipoPago = 'Prepagadas';
                                  }else{
                                    $tipoPago = 'Por consumo';
                                  }
                                ?>
                                <input type="text" class="form-control alphaNumeric-only" maxlength="30" name="txtCP" value="<?=$tipoPago;?>" disabled>
                              </div>
                            </div>

                          </div>
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
