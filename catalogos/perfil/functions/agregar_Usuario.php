<?php
session_start();

  if(isset($_SESSION["Usuario"]) && ($_SESSION["FKRol"] == 4 || $_SESSION["FKRol"] == 1)){
    require_once('../../../include/db-conn.php');
      if(isset ($_POST['btnAgregar'])){
        $idUsuario = $_POST['txtIdEmpleado'];
        $stmt = $conn->prepare('SELECT count(*) FROM empleados WHERE PKEmpleado= :id');
        $stmt->execute(array(':id'=>$idUsuario));
        $number_of_rows = $stmt->fetchColumn();
        if($number_of_rows > 0)
        {
          $usuario = $_POST['txtUsuario'];
          $password = $_POST['txtContrasena'];
          $rol = (int) $_POST['cmbRol'];
          try{
            $stmt = $conn->prepare('INSERT INTO usuarios (Usuario,Contrasena,FKEmpleado,FKRol)VALUES(:usuario,:contrasena,:idEmpleado,:rol)');
            $stmt->bindValue(':usuario',$usuario);
            $stmt->bindValue(':contrasena',$password);
            $stmt->bindValue(':idEmpleado',$idUsuario);
            $stmt->bindValue(':rol', (int) $rol, PDO::PARAM_INT);
            $stmt->execute();
            header('Location:../index.php');
          }catch(PDOException $ex){
            echo $ex->getMessage();
          }
        }else{
          echo "El empleado no existe";
        }
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

  <title>Timlid | Agregar usuario</title>

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

  <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js" integrity="sha256-c4gVE6fn+JRKMRvqjoDp+tlG4laudNYrXI1GncbfAYY=" crossorigin="anonymous"></script>
  <link href="../../../css/chosen.css" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css" integrity="sha256-EH/CzgoJbNED+gZgymswsIOrM9XhIbdSJ6Hwro09WE4=" crossorigin="anonymous" />

  <script>
    function checkPassword(){
      if ($('#newPasswordAgain').val() !== $('#newPassword').val()) {
        $('#newPasswordAgain')[0].setCustomValidity('Las contraseñas deben coincidir.');
      }
      else{
        $('#newPasswordAgain')[0].setCustomValidity('');
      }
    }
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
            <h1 class="h3 mb-0 text-gray-800">Agregar Usuario</h1>
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
                              <div class="col-lg-3">
                                <label for="usr">Id de empleado:</label>
                                <select class="form-control" name="cmbIdEmpleado" id="cmbIdEmpleado" required>
                                  <option value="">Seleccione una opcion...</option>
                                  <?php
                                    $stmt = $conn->prepare('SELECT * FROM empleados WHERE NOT EXISTS (SELECT * FROM usuarios WHERE empleados.PKEmpleado = usuarios.FKEmpleado)');
                                    $stmt->execute();
                                    while($row = $stmt->fetch()){
                                  ?>
                                      <option value="<?=$row['FKEmpleado'];?>"><?=$row['PKEmpleado'].".- ".$row['Primer_Nombre']." ".$row['Segundo_Nombre']." ".$row['Apellido_Paterno']." ".$row['Apellido_Materno'];?></option>
                                  <?php } ?>
                                </select>
                              </div>
                              <div class="col-lg-5">
                                <label for="usr">usuario:</label>
                                <input type="email" class="form-control"  name="txtUsuario" required>
                              </div>
                              <div class="col-lg-4">
                                <label for="usr">Rol:</label>
                                <select name="cmbRol" id="cmbRol" class="form-control" required>
                                    <option value="">Elegir opción</option>
                                        <?php
                                            $stmt = $conn->prepare('SELECT * FROM roles');
                                            $stmt->execute();
                                        ?>
                                        <?php foreach($stmt as $option) : ?>
                                             <option value="<?php echo $option['PKRol']; ?>"><?php echo $option['Rol']; ?></option>
                                        <?php endforeach; ?>
                                </select>
                              </div>
                            </div>
                            </div>
                            <div class="form-group">
                            <div class="row">
                              <div class="col-lg-6">
                                <label for="usr">Contraseña:</label>
                                <input type="password" class="form-control" id="newPassword" name="txtContrasena" maxlength="10" pattern="(?=.*\d)(?=.*[A-Z])(?=.*[~`!@#$%^&*()\-_+={};:\[\]\?\.\\/,]).{10,}" title="La contraseña debe tener al menos una letra mayuscula,  un caracter especia y 10 caracteres." required>
                              </div>
                              <div class="col-lg-6">
                                <label for="usr">Repetir Contraseña:</label>
                                <input type="password" class="form-control" id="newPasswordAgain" maxlength="10" required >
                              </div>
                            </div>
                          </div>

                          <button type="submit" class="btn btn-success float-right" onclick="checkPassword()" name="btnAgregar">Agregar</button>
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
      $("#alertaTareas").load('../../alerta_Tareas_Nuevas.php?user='+<?=$_SESSION['PKUsuario'];?>+'&ruta='+'<?=$ruta;?>');
        setInterval(refrescar, 5000);
      });
    function refrescar(){
      $("#alertaTareas").load('../../alerta_Tareas_Nuevas.php?user='+<?=$_SESSION['PKUsuario'];?>+'&ruta='+'<?=$ruta;?>');
    }
    $(document).ready(function() {
      $("#cmbIdEmpleado").chosen();
    });
  </script>

</body>

</html>
