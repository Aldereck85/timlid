<?php
session_start();

  if(isset($_SESSION["Usuario"]) && ($_SESSION["FKRol"] == 1 || $_SESSION["FKRol"] == 4)){
    require_once('../../../include/db-conn.php');
      if(isset ($_POST['btnEditar'])){
        $ide = (int) $_POST['txtId'];
        $estatus = $_POST['cmbEstatus'];
        try{
          $stmt = $conn->prepare('SELECT * FROM datos_laborales_empleado WHERE FKEmpleado = :id');
          $stmt->bindValue(':id', $ide, PDO::PARAM_INT);
          $stmt->execute();
          $row = $stmt->fetch();
          $idex = $row['PKLaboralesEmpleado'];

          $stmt = $conn->prepare('UPDATE datos_laborales_empleado set FKEstatus = :estatus WHERE PKLaboralesEmpleado = :id');
          $stmt->bindValue(':estatus',$estatus);
          $stmt->bindValue(':id', $idex, PDO::PARAM_INT);
          $stmt->execute();
          header('Location:../index.php');
        }catch(PDOException $ex){
          echo $ex->getMessage();
        }
      }

      if(isset($_POST['idEmpleadoA'])){
        $id =  $_POST['idEmpleadoA'];
        $stmt = $conn->prepare('SELECT * FROM empleados INNER JOIN datos_laborales_empleado ON PKEmpleado = FKEmpleado WHERE PKEmpleado= :id');
        $stmt->execute(array(':id'=>$id));
        $row = $stmt->fetch();
        $primerNombre = $row['Primer_Nombre'];
        $segundoNombre = $row['Segundo_Nombre'];
        $apellidoPaterno = $row['Apellido_Paterno'];
        $apellidoMaterno = $row['Apellido_Materno'];
        $estatus = $row['FKEstatus'];
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
  <link href="../../../css/dashboard.css" rel="stylesheet">

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

          <?php
            $rutatb = "../../";
            require_once('../../topbar.php');
          ?>

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
                        <form action="" method="post">
                          <div class="form-group">
                            <div class="row">
                              <div class="col-lg-3">
                                <label for="usr">Primer nombre:</label>
                                <input type="text" class="form-control alpha-only" maxlength="20"  name="txtPrimerNombre" value="<?=$primerNombre;?>" disabled>
                              </div>
                              <div class="col-lg-3">
                                <label for="usr">Segundo nombre:</label>
                                <input type="text" class="form-control alpha-only" maxlength="20"  name="txtSegundoNombre" value="<?=$segundoNombre;?>" disabled>
                              </div>
                              <div class="col-lg-3">
                                <label for="usr">Apellido paterno:</label>
                                <input type="text" class="form-control alpha-only" maxlength="20"  name="txtApellidoPaterno" value="<?=$apellidoPaterno;?>" disabled>
                              </div>
                              <div class="col-lg-3">
                                <label for="usr">Apellido materno:</label>
                                <input type="text" class="form-control alpha-only" maxlength="20"  name="txtApellidoMaterno" value="<?=$apellidoMaterno;?>" disabled>
                              </div>
                            </div>
                          </div>
                          <div class="form-group">
                            <div class="row">
                              <div class="col-lg-12">
                                <label for="usr">Estatus:</label>
                                <select name="cmbEstatus" class="form-control" id="cmbEstatus">
                                  <option value="">Seleccionar estatus</option>
                                  <?php
                                    $stmt = $conn->prepare('SELECT * FROM estatus_empleado');
                                    $stmt->execute();
                                    while($row = $stmt->fetch()){
                                      if($row['PKEstatusEmpleado'] != 1){
                                  ?>
                                      <option value="<?=$row['PKEstatusEmpleado'];?>"><?=$row['Estatus_Empleado'];  ?></option>
                                  <?php }} ?>
                                </select>
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

  <script type="text/javascript">
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
