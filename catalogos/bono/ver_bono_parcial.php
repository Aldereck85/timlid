<?php
session_start();
  if(isset($_SESSION["Usuario"]) && ($_SESSION["FKRol"] == 1 || $_SESSION["FKRol"] == 4)){
    require_once("ver_recibo_bono.php");
    $id = $_GET['id'];
    $existeRegistro = 0;
    $fechaAhora= time();
    $fechaMes = date("m",$fechaAhora);
    $bonoParcial = $bonoAsignado / 30;
    $fechaIngreso = new DateTime($row['Fecha_Ingreso']);
    $fechaCorte = "2019-".$fechaMes."-22";
    $fechaHoy = new DateTime($fechaCorte);
    $interval = $fechaIngreso ->diff($fechaHoy);
    $dias = $interval->format('%d');
    $bonoParcial = $bonoParcial * $dias;

    $stmt = $conn->prepare('SELECT COUNT(*) FROM bono_mensual WHERE FKEmpleado = :fkEmpleado  AND MONTH(Fecha) = :fecha');
    $stmt->bindValue(':fkEmpleado',$id);
    $stmt->bindValue(':fecha',$fechaMes);
    $stmt->execute();

    $number_of_rows = $stmt->fetchColumn();

    if($number_of_rows == 1){
      $existeRegistro = 1;
    }else{
      $existeRegistro = 0;
    }

    if(isset ($_POST['btnAgregar'])){
      $fechaAhora= time();
      $fechaHoy = date("Y-m-d",$fechaAhora);
      //echo $fechaHoy;}
      $estatus = 1;
      $bono = $_POST['txtBono'];
      $fkEmpleado = $_POST['txtId'];
      $sueldo = floatval($_POST['txtSueldo']);
      if($bono == 0.00){
        $estatus = 0;
      }
      try{
        $stmt = $conn->prepare('INSERT INTO bono_mensual (Bono,Fecha,FKEmpleado,Estatus)VALUES(:bono,:fecha,:fkEmpleado,:estatus)');
        $stmt->bindValue(':bono',$bono);
        $stmt->bindValue(':fecha',$fechaHoy);
        $stmt->bindValue(':fkEmpleado',$fkEmpleado);
        $stmt->bindValue(':estatus',$estatus);
        $stmt->execute();
        header('Location:index.php');
      }catch(PDOException $ex){
        echo $ex->getMessage();
      }
    }

    if(isset ($_POST['btnEliminar'])){
      $fkEmpleado = $_POST['txtId'];
      try{
        $stmt = $conn->prepare("DELETE FROM bono_mensual WHERE FKEmpleado = :fkEmpleado  AND MONTH(Fecha) = :fecha");
        $stmt->bindValue(':fkEmpleado',$fkEmpleado);
        $stmt->bindValue(':fecha',$fechaMes);
        $stmt->execute();
        header('Location:index.php');
      }catch(PDOException $ex){
        echo $ex->getMessage();
      }
    }

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

  <title>Timlid | Bono de asistencia mensual</title>

  <!-- Bootstrap core JavaScript-->
  <script src="../../vendor/jquery/jquery.min.js"></script>
  <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="../../js/sb-admin-2.min.js"></script>
  <script src="../../js/bono.js"></script>
  <script src="../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../vendor/datatables/dataTables.bootstrap4.min.js"></script>

  <!-- Page level plugins -->

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
      require_once("../menu3.php");
    ?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

          <?php
            $rutatb = "../";
            require_once('../topbar.php');
          ?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <h1 class="h3 mb-2 text-gray-800">Bono de asistencia mensual</h1>

          <!-- DataTales Example -->


          <input type="hidden" name="txtSemana" id="txtSemana" value="<?=$semana;?>">


          <div class="card shadow mb-4" id="divCalculo">
            <div class="card-header py-3">
              <div class="row">
                <div class="col-lg-10">
                  <b>Pago de bono</b>
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
                    <center><h4>Recibo de Pago</h4></center><br>
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
                    <label><b>Periodo de pago: </b></label>
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
                        <button id="btnAgregarBono" type="button" class="btn btn-outline-success" onclick="agregarBono();"><i class="fas fa-plus"></i></i></button>
                        <button id="btnEliminarBono" type="button" class="btn btn-outline-danger" onclick="eliminarBono();"><i class="fas fa-times"></i></button>
                      </div>
                      <div class="col-lg-8">
                        Bono de productividad
                      </div>
                    </div>

                  </div>
                  <div class="col-lg-3">
                    <label class="float-right" id="lblBonoAsignado"><?=number_format($bonoParcial, 2, '.', '');?></label>
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
                    <label name="lblBono" id="lblBono" class="float-right"><?=number_format($bonoParcial, 2, '.', '');?></label>
                    <input type="hidden" name="txtBono" id="txtBono" value="<?=number_format($bonoParcialo, 2, '.', '');?>">
                    <input type="hidden" name="txtBonoPuesto" id="txtBonoPuesto" value="<?=number_format($bonoParcial, 2, '.', '');?>">

                  </div>
                </div>
                <input type="hidden" name="txtId" id="txtId" value="<?=$id;?>">
                <input type="hidden" name="txtExiste" id="txtExiste" value="<?=$existeRegistro;?>">
                <button type="submit" class="btn btn-success float-right" name="btnAgregar" id="btnAgregar">Agregar</button>
                <button type="submit" class="btn btn-danger float-right" name="btnEliminar" id="btnEliminar">Eliminar nomina</button>
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
