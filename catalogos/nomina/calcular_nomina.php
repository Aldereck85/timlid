 <?php
session_start();
  if(isset($_SESSION["Usuario"]) && ($_SESSION["FKRol"] == 1 || $_SESSION["FKRol"] == 4)){
    require_once('../../include/db-conn.php');
    $user = $_SESSION["Usuario"];
    if(isset($_GET['id'])){
      $id =  $_GET['id'];
      $semana = $_GET['Semana'];
      $dateBegin = array();
      $contEstatus = 0;
      $bono = "0.00";

      $stmt = $conn->prepare('SELECT empleados.Primer_Nombre,empleados.Segundo_Nombre,empleados.Apellido_Paterno,empleados.Apellido_Materno,empleados.RFC,turnos.Turno,turnos.Entrada,turnos.Salida,turnos.Horas_de_trabajo,turnos.Dias_de_trabajo,puestos.Puesto,puestos.Sueldo_semanal, de.Deuda_Interna,de.Deuda_Restante,dme.NSS, de.Infonavit  FROM empleados INNER JOIN datos_laborales_empleado as de ON empleados.PKEmpleado = de.FKEmpleado INNER JOIN turnos on de.FKTurno = turnos.PKTurno INNER JOIN puestos on de.FKPuesto = puestos.PKPuesto INNER JOIN datos_medicos_empleado as dme ON empleados.PKEmpleado = dme.FKEmpleado WHERE empleados.PKEmpleado= :id');

      $stmt->execute(array(':id'=>$id));
      $row = $stmt->fetch();
      $nombreEmpleado = $row['Primer_Nombre']." ".$row['Segundo_Nombre']." ".$row['Apellido_Paterno']." ".$row['Apellido_Materno'];
      $rfc = $row['RFC'];
      $nss = $row['NSS'];
      $turno = $row['Turno'];
      $puesto = $row['Puesto'];
      $sueldoSemanal = $row['Sueldo_semanal'];
      $sueldo = $row['Sueldo_semanal'];
      $infonavit = $row['Infonavit'];

      $deuda = $row['Deuda_Interna'];
      $parcialidades = $row['Deuda_Interna']/10;
      $deudaRestante = $row['Deuda_Restante'];
      $diasTrabajo = $row['Dias_de_trabajo'];


      //$sueldoDiario = $row['Sueldo_semanal']/$row['Dias_de_trabajo'];
      $sueldoDiario = $row['Sueldo_semanal']/$diasTrabajo;
      $bonoPreaprovado = 0;

      $sueldoTotal = 0.00;

      $bonoProductividad = 100/6;
      $bonoCorrespondiente = 0.00;
      $estatusChecada;
      $horasAcumuladas = strtotime("00:00:00");
      $date = new DateTime("00:00:00");
      $sueldoDescuento = 0;
      ////////////////////////////
      /*$entrada = strtotime($row['Entrada']);
      $salida = strtotime($row['Salida']);
      $tiempoTotal = $salida - $entrada;*/

      $horaInicio = new DateTime($row['Entrada']);
      $horaTermino = new DateTime($row['Salida']);
      $interval = $horaInicio->diff($horaTermino);

      $horas = $interval->format('%H');
      $minutos = $interval->format('%I');
      //$horas = date("h", $tiempoTotal);
      //$minutos = date("i", $tiempoTotal);

      $horasDivision = $horas;
      if($minutos == 29 || $minutos == 30)
      {
        $minutos = 0.50;
      }
      $horasDivision = $horasDivision + $minutos;


      $sueldoHora = $sueldoDiario / $horasDivision;
      $sueldoMinuto = $sueldoHora / 60;

      ////////Fechas////////////////////////////
      $stmt = $conn->prepare('SELECT FechaInicio,FechaTermino FROM semanas_checador WHERE PKChecador = :id');
      $stmt->execute(array(':id'=>$semana));
      $x = 0;

      while (($row = $stmt->fetch()) !== false) {
        $fecha  = $row['FechaInicio'];
        $fecha2 = $row['FechaTermino'];


        $period = new DatePeriod(
             new DateTime($fecha),
             new DateInterval('P1D'),
             new DateTime($fecha2)
        );

          foreach ($period as $key => $value) {
              $dateBegin[$x] = $value->format('Y-m-d');
              $x = $x + 1;
          }
      }


      for($y = 0;$y<7;$y++){

        $stmt = $conn->prepare('SELECT Estatus,Deuda_Horas FROM gh_checador WHERE FKUsuario = :id AND Fecha = :fecha ORDER BY Fecha ASC');
        $stmt->execute(array(':id'=>$id,':fecha'=>$dateBegin[$y]));
        $row = $stmt->fetch();

        //if($y == 1){
          $horasAcumuladas = $horasAcumuladas + strtotime($row['Deuda_Horas']);
        if($row['Estatus'] == 4 || $row['Estatus'] == 9 || $row['Estatus'] == 10){
          $contEstatus++;
        }
        if($diasTrabajo == 5){
          if($y != 1 || $y != 2){
              if($row['Estatus'] == 4){
                $bonoCorrespondiente = $bonoCorrespondiente + $bonoProductividad;
              }
          }
        }else if($diasTrabajo == 6){
          if($y != 2){
              if($row['Estatus'] == 4){
                $bonoCorrespondiente = $bonoCorrespondiente + $bonoProductividad;
              }
          }
        }


      }
      $date = strtotime('- 9 hours', $horasAcumuladas);
      $horasDeuda = date("h", $date);
      $minutosDeuda = date("i", $date);

      if($horasDeuda > 0){
        $sueldoDescuento = $sueldoDescuento + ($horasDeuda * $sueldoHora);
        $descuentoHora = $horasDeuda * $sueldoHora;
        $sueldo = $sueldo - $descuentoHora;
      }

      if($minutosDeuda > 0){
        $sueldoDescuento = $sueldoDescuento + ($minutosDeuda * $sueldoMinuto);
        $descuentoMinuto = $horasDeuda * $sueldoMinuto;
        $sueldo = $sueldo - $descuentoMinuto;
      }

      $sueldoTotal = $sueldoSemanal - $sueldoDescuento - $infonavit - $parcialidades;
    }

  }else {
    header("location:../dashboard.php");
  }

  ////////////////////////////////
    if(isset ($_POST['btnAgregar'])){
      $bonoAprobado = $_POST['txtBonoAgregado'];
      $salario = $_POST['txtSalario'];
      try{
        $stmt = $conn->prepare('INSERT INTO nomina (FKEmpleado,FKSemana,BonoSemanal,Salario)VALUES(:empleado,:semana,:bono,:salario)');
        $stmt->bindValue(':empleado',$id);
        $stmt->bindValue(':semana',$semana);
        $stmt->bindValue(':bono',$bonoAprobado);
        $stmt->bindValue(':salario',$salario);
        $stmt->execute();

        $deudaMenos = $deudaRestante - $parcialidades;

        if($deudaRestante > $parcialidades){
          $stmt = $conn->prepare('UPDATE empleados set Deuda_Restante = :deuda WHERE PKEmpleado = :id');
          $stmt->bindValue(':deuda',$deudaMenos);
          $stmt->bindValue(':id', $id, PDO::PARAM_INT);
          $stmt->execute();
        }else if($deudaRestante == $parcialidades){
          $stmt = $conn->prepare('UPDATE empleados set Deuda_Restante = :deudaRestante,Deuda_Interna = :deudaTotal WHERE PKEmpleado = :id');
          $stmt->bindValue(':deudaRestante',$deudaMenos);
          $stmt->bindValue(':deudaTotal',$deudaMenos);
          $stmt->bindValue(':id', $id, PDO::PARAM_INT);
          $stmt->execute();
        }


        //echo $id;
        //header('Location:../index.php');
      }catch(PDOException $ex){
        echo $ex->getMessage();
      }
    }
  ///////////////////////////////
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

  <title>Timlid | Calculo de nomina</title>

  <!--
   Bootstrap core JavaScript-->
  <script src="../../vendor/jquery/jquery.min.js"></script>
  <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="../../js/sb-admin-2.min.js"></script>

  <!-- Page level plugins -->
  <script src="../../vendor/datatables/jquery.dataTables.min.js"></script>

  <script src="../../vendor/datatables/dataTables.bootstrap4.min.js"></script>

  <!-- Custom fonts for this template -->
  <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="../../css/sb-admin-2.css" rel="stylesheet">

  <!-- Custom styles for this page -->
  <link href="../../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

  <script>
    $(document).ready(function(){
      var numberEstatus = $("#txtContEstatus").val();
      var parcialidad = $("#txtParcialidades").val();
      $("#btnAgregarBono").hide();
      $("#btnEditar").hide();
      $("#btnEliminarBono").hide()
      $("#btnAgregarPago").hide();
      if(numberEstatus == 1){
        $("#lblDescuento").text($("#txtSalarioSem"));
        $("#lblTotal").text("0.00");
        $("#txtSalario").val("0.00");
      }
      if(numberEstatus == 7){
        $("#btnAgregarBono").show();
      }

      if(parcialidad == 0.00){
        $("#btnEliminarPago").hide();
      }

    });

    function agregarBono(){
      $("#btnAgregarBono").hide();
      $("#btnEliminarBono").show();
      $("#txtBonoAgregado").val(1);

      var bonoPre = $("#txtBonoPreAprobado").val();

      var bono = parseFloat(bonoPre);

      $("#lblBono").text(bono.toFixed(2));

      var descuentoDeudaInterna = parseFloat($("#lblDeudaInterna").text());
      var salarioSemanal = parseFloat($("#txtSalarioSem").val());
      var salarioTotal = parseFloat($("#lblTotal").text());
      //var bono = parseFloat($("#lblBono").text());
      var descuentoImproductividad = parseFloat($("#lblDescuento").text());
      var descuentoInfonavit = parseFloat($("#lblInfonavit").text());

      salarioTotal = salarioSemanal + bono - descuentoImproductividad - descuentoInfonavit - descuentoDeudaInterna;
      $("#lblTotal").text(salarioTotal.toFixed(2));

    }

    function eliminarBono(){
      $("#btnEliminarBono").hide();
      $("#btnAgregarBono").show();
      $("#txtBonoAgregado").val(0);
      $("#lblBono").text("0.00");

      var descuentoDeudaInterna = parseFloat($("#lblDeudaInterna").text());
      var salarioSemanal = parseFloat($("#txtSalarioSem").val());
      var salarioTotal = parseFloat($("#lblTotal").text());
      var bono = parseFloat($("#lblBono").text());
      var descuentoImproductividad = parseFloat($("#lblDescuento").text());
      var descuentoInfonavit = parseFloat($("#lblInfonavit").text());

      salarioTotal = salarioSemanal + bono - descuentoImproductividad - descuentoInfonavit - descuentoDeudaInterna;
      $("#lblTotal").text(salarioTotal.toFixed(2));

    }

    function eliminarPagoDeuda(){
      $("#btnEliminarPago").hide();
      $("#btnAgregarPago").show();
      $("#lblDeudaInterna").text("0.00");
      var descuentoDeudaInterna = parseFloat($("#lblDeudaInterna").text());
      var salarioSemanal = parseFloat($("#txtSalarioSem").val());
      var salarioTotal = parseFloat($("#lblTotal").text());
      var bono = parseFloat($("#lblBono").text());
      var descuentoImproductividad = parseFloat($("#lblDescuento").text());
      var descuentoInfonavit = parseFloat($("#lblInfonavit").text());
      salarioTotal = salarioSemanal + bono - descuentoImproductividad - descuentoInfonavit - descuentoDeudaInterna;
      $("#lblTotal").text(salarioTotal.toFixed(2));
    }

    function agregarPago(){
      $("#btnAgregarPago").hide();
      $("#btnEliminarPago").show();
      var deuda = parseFloat($("#txtParcialidades").val());
      $("#lblDeudaInterna").text(deuda.toFixed(2));
      var descuentoDeudaInterna = parseFloat($("#lblDeudaInterna").text());
      var salarioSemanal = parseFloat($("#txtSalarioSem").val());
      var salarioTotal = parseFloat($("#lblTotal").text());
      var bono = parseFloat($("#lblBono").text());
      var descuentoImproductividad = parseFloat($("#lblDescuento").text());
      var descuentoInfonavit = parseFloat($("#lblInfonavit").text());
      salarioTotal = salarioSemanal + bono - descuentoImproductividad - descuentoInfonavit - descuentoDeudaInterna;
      $("#lblTotal").text(salarioTotal.toFixed(2));
    }

  </script>

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
          <h1 class="h3 mb-2 text-gray-800">Nomina semanal</h1>

          <!-- DataTales Example -->
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <div class="row">
                <div class="col-lg-10">

                </div>
                <div class="col-lg-2">

                </div>
              </div>

            </div>

            <div class="card-body">
              <form action="" method="post">
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
                    <label><b>Periodo de pago: </b><?=$fecha;?></label>
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
                        Sueldo semanal
                      </div>
                    </div>

                  </div>
                  <div class="col-lg-3">
                    <label class="float-right"><?=$sueldoSemanal;?></label>
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
                          <button id="btnAgregarBono" type="button" class="btn btn-outline-success" onclick="agregarBono();"><i class="fas fa-plus"></i></i></button>
                          <button id="btnEliminarBono" type="button" class="btn btn-outline-danger" onclick="eliminarBono();"><i class="fas fa-times"></i></button>
                      </div>
                      <div class="col-lg-8">
                        Bono de productividad
                      </div>
                    </div>

                    <br>
                  </div>
                  <div class="col-lg-3">
                    <label id="lblBono" class="float-right"><?=$bono;?></label>
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
                        Descuento de improductividad
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-3">

                  </div>
                  <div class="col-lg-3">
                    <label id="lblDescuento" class="float-right"><?=number_format($sueldoDescuento, 2, '.', '');?></label>
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
                        Descuento del INFONAVIT
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-3">

                  </div>
                  <div class="col-lg-3">
                    <label id="lblInfonavit" class="float-right"><?=number_format($infonavit, 2, '.', '');?></label>
                  </div>
                  <div class="col-lg-3">
                  </div>
                </div>

                <div class="row">
                  <div class="col-lg-3">
                    <div class="row">
                      <div class="col-lg-4">
                          <button id="btnAgregarPago" type="button" class="btn btn-outline-success" onclick="agregarPago();"><i class="fas fa-plus"></i></i></button>
                          <button id="btnEliminarPago" type="button" class="btn btn-outline-danger" onclick="eliminarPagoDeuda();"><i class="fas fa-times"></i></button>
                      </div>
                      <div class="col-lg-8">
                        Descuento deuda interna
                      </div>
                    </div>
                  </div>

                  <div class="col-lg-3">

                  </div>
                  <div class="col-lg-3">

                    <label id="lblDeudaInterna" class="float-right"><?=number_format($parcialidades, 2, '.', '');?></label>

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
                <input type="hidden" name="txtId" id="txtId" value="<?=$id;?>">
                <input type="hidden" name="txtContEstatus" id="txtContEstatus" value="<?=$contEstatus;?>">
                <input type="hidden" name="txtSem" id="txtSem">
                <input type="hidden" name="txtBonoAgregado" id="txtBonoAgregado" value="0">
                <input type="hidden" name="txtSalario" id="txtSalario" value="<?=$sueldoTotal;?>">
                <input type="hidden" name="txtSalarioSem" id="txtSalarioSem" value="<?=$sueldoSemanal;?>">
                <input type="hidden" name="txtDeuda" id="txtDeuda" value="<?=$deuda;?>">
                <input type="hidden" name="txtDeuda" id="txtParcialidades" value="<?=$parcialidades;?>">
                <input type="hidden" name="txtBonoPreAprobado" id="txtBonoPreAprobado" value="<?=$bonoCorrespondiente;?>">
                <button type="submit" class="btn btn-success float-right" name="btnAgregar">Sellar nomina</button>
                <button type="submit" class="btn btn-primary float-right" name="btnEditar" id="btnEditar">Editar nomina</button>
              </form>
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


</body>

</html>
