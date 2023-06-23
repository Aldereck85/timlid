<?php
session_start();

  if(isset($_SESSION["Usuario"])){
    require_once('../../../include/db-conn.php');


    if(isset ($_POST['btnEditar'])){
      $puesto = (int) $_POST['cmbPuesto'];
      $locacion = (int) $_POST['cmbLocacion'];
      $turno = (int) $_POST['cmbTurnos'];
      $idBanco = (int) $_POST['txtIdBanco'];
      $cuentaBanco = (int) $_POST['txtCuentaBanco'];
      $id = (int) $_POST['txtId'];
      try{
        $stmt = $conn->prepare('UPDATE datos_empleo set FKPuesto= :puesto, FKLocacion= :locacion,FKTurno= :turno,Id_banco= :idBanco,Cuenta_de_Banco= :cuentaBanco WHERE FKEmpleado = :id');
        $stmt->bindValue(':id', (int) $id, PDO::PARAM_INT);
        $stmt->bindValue(':puesto', (int) $puesto, PDO::PARAM_INT);
        $stmt->bindValue(':locacion', (int) $locacion, PDO::PARAM_INT);
        $stmt->bindValue(':turno', (int) $turno, PDO::PARAM_INT);
        $stmt->bindValue(':idBanco', (int) $idBanco, PDO::PARAM_INT);
        $stmt->bindValue(':cuentaBanco', (int) $cuentaBanco, PDO::PARAM_INT);
        $stmt->execute();
        header('Location:../index.php');


      }catch(PDOException $ex){
        echo $ex->getMessage();
      }
    }

      if(isset($_GET['id'])){
        $id =  $_GET['id'];
        $stmt = $conn->prepare('SELECT PKEmpleado,Primer_Nombre,Segundo_Nombre,Apellido_Paterno,Apellido_Materno,FKPuesto,FKLocacion,FKTurno,Id_banco,Cuenta_de_Banco
    FROM empleados INNER JOIN datos_empleo on PKEmpleado = FKEmpleado WHERE PKEmpleado= :id');
        $stmt->execute(array(':id'=>$id));
        $row = $stmt->fetch();
        $primerNombre = $row['Primer_Nombre'];
        $segundoNombre = $row['Segundo_Nombre'];
        $apellidoPaterno = $row['Apellido_Paterno'];
        $apellidoMaterno = $row['Apellido_Materno'];
        $puesto = (int) $row['FKPuesto'];
        $locacion = (int)$row['FKLocacion'];
        $turno = (int)$row['FKTurno'];
        $idBanco = (int)$row['Id_banco'];
        $cuentaBanco = (int)$row['Cuenta_de_Banco'];
      }

  }else {
    header("location:../../index.php");
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

  <title>Timlid | Editar empleado</title>

  <!-- Bootstrap core JavaScript-->
  <script src="../../../vendor/jquery/jquery.min.js"></script>
  <script src="../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../../js/validaciones.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="../../../vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="../../../js/sb-admin-2.min.js"></script>

  <!-- Custom fonts for this template-->
  <link href="../../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
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
            <h1 class="h3 mb-0 text-gray-800">Editar empleado</h1>
          </div>


          <div class="row">

            <div class="col-lg-12">

              <!-- Basic Card Example -->
              <div class="card shadow mb-4">
                <div class="card-header">
                  Tarjeta del empleado
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-12">
                      <label for="usr">Nombre completo:</label>
                      <label><?=$primerNombre." ".$segundoNombre." ".$apellidoPaterno." ".$apellidoMaterno;?></label>
                    </div>
                  </div>
                    <div class="row">
                      <div class="col-lg-12">
                        <form action="" method="post">
                          <div class="form-group">
                            <div class="row">
                              <div class="col-lg-4">
                                <label for="usr">Puesto:</label>
                                <select name="cmbPuesto" id="cmbPuesto" class="form-control" required>
                                    <option value="">Elegir opción</option>
                                        <?php
                                            $stmt = $conn->prepare('SELECT * FROM puestos');
                                            $stmt->execute();
                                        ?>
                                        <?php foreach($stmt as $option) : ?>
                                             <option value="<?php echo $option['PKPuesto']; ?>" <?php if ($puesto == $option['PKPuesto'] ) echo 'selected'; ?>><?php echo $option['Puesto']; ?></option>
                                        <?php endforeach; ?>
                                </select>
                              </div>
                              <div class="col-lg-4">
                                <label for="usr">Locación:</label>
                                <select name="cmbLocacion" id="cmbLocacion" class="form-control" required>
                                    <option value="">Elegir opción</option>
                                        <?php
                                            $stmt = $conn->prepare('SELECT * FROM locacion');
                                            $stmt->execute();
                                        ?>
                                        <?php foreach($stmt as $option) : ?>
                                             <option value="<?php echo $option['PKLocacion']; ?>" <?php if ($locacion == $option['PKLocacion'] ) echo 'selected'; ?>><?php echo $option['Locacion']; ?></option>
                                        <?php endforeach; ?>
                                </select>
                              </div>
                              <div class="col-lg-4">
                                <label for="usr">Turno:</label>
                                <select name="cmbTurnos" id="cmbTurnos" class="form-control" required>
                                    <option value="">Elegir opción</option>
                                        <?php
                                            $stmt = $conn->prepare('SELECT * FROM turnos');
                                            $stmt->execute();
                                        ?>
                                        <?php foreach($stmt as $option) : ?>
                                             <option value="<?php echo $option['PKTurno']; ?>" <?php if ($turno == $option['PKTurno'] ) echo 'selected'; ?>><?php echo $option['Turno']; ?></option>
                                        <?php endforeach; ?>
                                </select>
                              </div>

                            </div>
                          </div>
                          <div class="form-group">
                            <div class="row">
                              <div class="col-lg-6">
                                <label for="usr">Id banco:</label>
                                <input type="text" class="form-control numeric-only" maxlength="11" name="txtIdBanco" value="<?=$idBanco;?>">
                              </div>
                              <div class="col-lg-6">
                                <label for="usr">Cuenta de banco:</label>
                                <input type="text" class="form-control numeric-only" maxlength="18"  name="txtCuentaBanco" value="<?=$cuentaBanco;?>">
                              </div>
                            </div>
                          </div>

                          <input type="hidden" name="txtId" value="<?=$id;?>">
                          <button type="submit" class="btn btn-primary float-right" name="btnEditar">Editar</button>
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
          <a class="btn btn-primary" href="../../logout.php">Salir</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="../../../vendor/jquery/jquery.min.js"></script>
  <script src="../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="../../../vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="../../../js/sb-admin-2.min.js"></script>

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
