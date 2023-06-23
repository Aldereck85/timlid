<?php
session_start();

  if(isset($_SESSION["Usuario"]) && ($_SESSION["FKRol"] == 1 || $_SESSION["FKRol"] == 4)){
    require_once('../../../include/db-conn.php');
      if(isset($_GET['id'])){
        $id =  $_GET['id'];
      }
        $stmt = $conn->prepare('SELECT Odometro,Kilometraje_para_Servicio,Kilometraje_acumulado FROM vehiculos WHERE PKVehiculo= :id');
        $stmt->execute(array(':id'=>$id));
        $row = $stmt->fetch();
        $kilometraOdo = $row['Odometro'];
        $kilometrajeAcumulado = $row['Kilometraje_acumulado'];

      if(isset ($_POST['btnAgregar'])){
        $id = $_POST['txtId'];
        //$idVehiculo = $id;
        $fecha = $_POST['txtFecha'];
        $cantidad = $_POST['txtCantidad'];
        $costo = $_POST['txtCosto'];
        $odometro = $_POST['txtOdometro'];
        $tanque = $_POST['cmbTanque'];
        $idUsuario = $_SESSION["PKUsuario"];

        $diferencia = $odometro-$kilometraOdo;
        try{
          $stmt = $conn->prepare('INSERT INTO combustible (Fecha_Carga,Cantidad,Costo_Unitario,Odometro,Diferencia_Odometro,Tanque_Lleno,FKVehiculo,FKUsuario) VALUES(:fecha,:cantidad,:costo,:odometro,:diferencia_Odometro,:tanque,:fkVehiculo,:usuario)');
          $stmt->bindValue(':fecha',$fecha);
          $stmt->bindValue(':cantidad',$cantidad);
          $stmt->bindValue(':costo',$costo);
          $stmt->bindValue(':odometro',$odometro);
          $stmt->bindValue(':diferencia_Odometro',$diferencia);
          $stmt->bindValue(':tanque',$tanque);
          $stmt->bindValue(':fkVehiculo',$id);
          $stmt->bindValue(':usuario',$idUsuario);
          $stmt->execute();

          $kilometrajeAcumulado = $kilometrajeAcumulado+($odometro-$kilometraOdo);
          //$id = $conn->lastInsertId();
          //echo $idVehiculo." ".$odometro;
          $stmt = $conn->prepare('UPDATE vehiculos set Odometro= :odometro,Kilometraje_acumulado= :kilometrajeAcumulado,Kilometraje_anterior =:kilometraje_anterior WHERE PKVehiculo = :id');
          $stmt->bindValue(':odometro',$odometro);
          $stmt->bindValue(':kilometrajeAcumulado',$kilometrajeAcumulado);
          $stmt->bindValue(':kilometraje_anterior',$kilometraOdo);
          $stmt->bindValue(':id',$id);
          $stmt->execute();

          header('Location:../combustible.php?id='.$id);
        }catch(PDOException $ex){
          echo $ex->getMessage();
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

  <title>Timlid | Agregar Poliza</title>

  <!-- Bootstrap core JavaScript-->
  <script src="../../../vendor/jquery/jquery.min.js"></script>
  <script src="../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="../../../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../../../js/validaciones.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="../../../js/sb-admin-2.min.js"></script>

  <script src="../../../js/bootstrap-clockpicker.min.js"></script>

  <!-- Custom fonts for this template-->
  <link href="../../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../../../css/bootstrap-clockpicker.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="../../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../../css/styles.css" rel="stylesheet">

</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <?php
      $ruta = "../../";
      $ruteEdit = $ruta . "central_notificaciones/";
      require_once '../../menu3.php';
    ?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <?php
          $rutatb = "../../";
          $titulo = '<div class="header-screen">
                                  <div class="header-title-screen">
                                  <h1 class="h3 mb-2">Timdesk  <img src="' . $rutatb . '../img/timdesk/timdesk_icon.svg" alt="" style="position:relative;top:-5px;left:-7px;"></h1>
                                  </div>
                                 </div>';
          require_once $rutatb . 'topbar.php';
        ?>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Agregar poliza</h1>
          </div>


          <div class="row">

            <div class="col-lg-12">

              <!-- Basic Card Example -->
              <div class="card shadow mb-4">
                <div class="card-header">
                  Detalles de la poliza
                </div>
                <div class="card-body">
                    <div class="row">
                      <div class="col-lg-12">
                        <form action="" method="post">
                          <div class="form-group">
                            <div class="row">
                              <div class="col-lg-6">
                                <label for="usr">Fecha de carga:</label>
                                <input type="date" class="form-control" maxlength="20" name="txtFecha">
                              </div>
                              <div class="col-lg-6">
                                <label for="usr">Cantidad:</label>
                                <div class="input-group mb-3">
                                  <input type="text" class="form-control numericDecimal-only" maxlength="20" name="txtCantidad">
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
                                  <input type="text" class="form-control numericDecimal-only" name="txtCosto">
                                </div>

                              </div>
                              <div class="col-lg-5">
                                <label for="usr">Odometro:</label>
                                <input type="text" class="form-control numeric-only" name="txtOdometro">
                              </div>
                              <div class="col-lg-2">
                                <label for="usr">Tanque lleno:</label>
                                <select name="cmbTanque" class="form-control" required>
                                  <option value="">Elegir opción</option>
                                  <option value="1">Si</option>
                                  <option value="0">No</option>
                                </select>
                              </div>
                            </div>
                          </div>
                          <input type="hidden" name="txtId" value="<?=$id;?>">
                          <input type="hidden" name="txtKilometrajeServ" value="<?=$kilometraOdo;?>">
                          <input type="hidden" name="txtKilometrajeAcum" value="<?=$kilometrajeAcumulado;?>">
                            <input type="hidden" name="txtTanque">
                          <button type="submit" class="btn btn-success float-right" name="btnAgregar" onclick="tanqueLleno();">Agregar</button>
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
    $(document).ready(function() {
    $("#alertaTareas").load(
      '../../alerta_Tareas_Nuevas.php?user=<?=$_SESSION['PKUsuario'];?>&ruta=<?=$ruta;?>&ruteEdit=<?=$ruteEdit;?>');
    setInterval(refrescar, 5000);
    });

    function refrescar() {
      $("#alertaTareas").load(
        '../../alerta_Tareas_Nuevas.php?user=<?=$_SESSION['PKUsuario'];?>&ruta=<?=$ruta;?>&ruteEdit=<?=$ruteEdit;?>');
    }
  </script>

</body>

</html>
