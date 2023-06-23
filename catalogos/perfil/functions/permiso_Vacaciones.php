<?php

session_start();

  if(isset($_SESSION["Usuario"])){

    require_once('../../../include/db-conn.php');
    $fkEmpleado = $_GET['id'];

    $stmt = $conn->prepare("SELECT de.PKLaboralesEmpleado, e.*, p.Sueldo_semanal, IFNULL(de.Dias_de_Vacaciones,0) as Dias_de_Vacaciones,
                            IFNULL(SUM(v.Dias_de_Vacaciones_Tomados),0) as Dias_de_Vacaciones_Tomados, t.Dias_de_trabajo, t.Turno, p.Puesto, dm.NSS
                            FROM empleados as e
                            LEFT JOIN datos_laborales_empleado as de ON de.FKEmpleado = e.PKEmpleado
                            LEFT JOIN datos_medicos_empleado as dm ON dm.FKEmpleado = e.PKEmpleado
                            LEFT JOIN puestos as p ON p.PKPuesto = de.FKPuesto
                            LEFT JOIN vacaciones as v ON v.FKEmpleado = e.PKEmpleado AND YEAR(v.FechaIni) = YEAR(CURDATE())
                            LEFT JOIN turnos as t ON de.FKTurno = t.PKTurno
                            WHERE e.PKEmpleado = :id ");
    $stmt->bindValue(':id',$fkEmpleado);
    $stmt->execute();
    $row = $stmt->fetch();
    $PKDatosEmpleo = $row['PKLaboralesEmpleado'];
    $fkEmpleado = $row['PKEmpleado'];
    $dias_trabajo = $row['Dias_de_trabajo'];

    $segundo_nombre = '';
    if(trim($row['Segundo_Nombre']) != ""){
      $segundo_nombre = ' '.$row['Segundo_Nombre'];
    }
    $nombreEmpleado = $row['Primer_Nombre'].$segundo_nombre.' '.$row['Apellido_Paterno'].' '.$row['Apellido_Materno'];

    $nss = $row['NSS'];
    $rfc = $row['RFC'];
    $turno = $row['Turno'];
    $puesto = $row['Puesto'];
    $fecha = "Sin datos";

    $sueldoSemanal = $row['Sueldo_semanal'];
    $dias_vacaciones_restantes =  $row['Dias_de_Vacaciones'];

    if($dias_vacaciones_restantes == 0){
      header('Location:../vacaciones.php?id='.$fkEmpleado);
    }

  }else {
    header("location:../dashboard.php");
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

  <title>Timlid | Permiso vacaciones</title>

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
  <style type="text/css">
    #info{
      display: none;
    }
  </style>

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
            <h1 class="h3 mb-0 text-gray-800">Solicitar vacaciones</h1>
          </div>


          <div class="row">

            <div class="col-lg-12">

              <!-- Basic Card Example -->
              <div class="card shadow mb-4" id="agregarVacaciones">
                <div class="card-header">
                  <center><button type="button" class="btn btn-info" name="btnPermisos" id="btnPermisos" onclick="window.location.href='permiso_Vacaciones.php'"><i class="far fa-calendar-check"></i> Permisos</button></center>
                </div>
                <div class="card-body">
                    <div class="row">
                      <div class="col-lg-12">
                        <form action="" method="post">
                          <div class="form-group">
                            <div class="row">
                              <div class="col-lg-6">
                                <label><b>Nombre:</b> <?=$nombreEmpleado;?></label><br/>
                                <label><b>Días de vacaciones restantes:</b> <?=$dias_vacaciones_restantes;?></label>
                              </div>
                              <div class="col-lg-6">
                                <center>
                                  <label for="dias_vacaciones">Días de vacaciones a tomar:</label>
                                  <?php
                                      echo "<br/><select name='dias_vacaciones' id='dias_vacaciones'>";
                                      for($x = 1; $x <= $dias_vacaciones_restantes;$x++){
                                        echo "<option value='".$x."'>".$x."</option>";
                                      }

                                    ?>
                                  </select>
                                  <br><br>
                                  <?php
                                    $sueldoDiario = $sueldoSemanal / 7;

                                    $sueldo_vacaciones = number_format(floatval($sueldoDiario * 1),2,'.','');
                                    $prima_vacacional = floatval(round($sueldo_vacaciones * 0.25,2));
                                    $total_vacaciones = floatval(round($sueldo_vacaciones + $prima_vacacional,2));
                                  ?>
                                </center>
                              </div>
                            </div>
                          </div>
                          <br>
                          <div class="form-group">
                            <div class="row">
                              <div class="col-lg-6">
                                <label for="usr">Fecha de inicio:</label>
                                <input type="date" name="txtFechaInicio" id="txtFechaInicio" class="form-control" step="1" required >
                              </div>
                              <div class="col-lg-6">
                                <label for="usr">Fecha de termino:</label>
                                <input type="date" name="txtFechaTermino" id="txtFechaTermino" class="form-control" step="1" required>
                              </div>
                            </div>
                          </div>
                          <div class="form-group">
                            <div class="row">
                              <div class="col-lg-12">
                                <center><div id="info" class="alert alert-danger" role="alert"></div></center>
                              </div>
                            </div>
                          </div>
                          <input type="hidden" name="prima_vacacional" id="prima_vacacional" value="<?=$prima_vacacional;?>">
                          <input type="hidden" name="total_vacaciones" id="total_vacaciones" value="<?=$total_vacaciones;?>">
                          <button type="button" class="btn btn-primary float-left" name="btnVolver" id="btnVolver" onclick="window.location.href='../'">Volver</button>
                          <button type="button" class="btn btn-success float-right" name="btnAgregar" id="btnAgregar">Agregar</button>
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

<script type="text/javascript">

 function ajaxFunction(){
   var ajaxRequest;

     try{
       // Opera 8.0+, Firefox, Safari
       ajaxRequest = new XMLHttpRequest();
       }catch (e){
         // Internet Explorer Browsers
         try{
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
         }catch (e) {
            try{
               ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            }catch (e){
               //alert("Your browser broke!");
               return false;
            }
         }
       }
  }


$("#btnAgregar").click(function(){
  var fecha_ini = $('#txtFechaInicio').val();
  var fecha_fin = $('#txtFechaTermino').val();


  if(fecha_ini === ''){
    $("#txtFechaInicio")[0].reportValidity();
    $("#txtFechaInicio")[0].setCustomValidity('Completa este campo.');
    return;
  }

  if(fecha_fin === ''){
    $("#txtFechaTermino")[0].reportValidity();
    $("#txtFechaTermino")[0].setCustomValidity('Completa este campo.');
    return;
  }

  var dias_vacaciones = $('#dias_vacaciones').val();
  var fkEmpleado = <?php echo $fkEmpleado;?>;
  var dias_trabajo = <?php echo $dias_trabajo;?>;

  var myData={"fkEmpleado":fkEmpleado,"dias_vacaciones":dias_vacaciones,"txtFechaInicio":fecha_ini,"txtFechaTermino":fecha_fin,"dias_trabajo":dias_trabajo};

  $.ajax({
      url : "function_agregar_Vacaciones.php",
      type: "POST",
      data : myData,
      success: function(data,status,xhr)
       {
          var datos = JSON.parse(data);

          $("#info").html("");

          if(datos.error == 2){
            $("#info").css("display","block");
            $("#info").html("La fecha de termino no puede ser mayor que la de inicio.");
            setTimeout(function(){
                $("#info").css("display","none");
              }, 3000);
            return;
          }

          if(datos.error == 1){
            $("#info").css("display","block");
            $("#info").html("El número de días seleccionados es diferente que el rango de fechas.");
            setTimeout(function(){
                $("#info").css("display","none");
              }, 3000);
            return;
          }

          if(datos.error == 0){

              $('#lbldias_vacaciones').html(dias_vacaciones);
              $('#periodo_vacaciones').html(datos.fecha);
              $(location).attr('href','../');
          }
       }

  });


});


   $("#dias_vacaciones").change(function() {
        var fkEmpleado = <?php echo $fkEmpleado;?>;
        var sueldoSemanal = <?php echo $sueldoSemanal;?>;
        var dias_vacaciones = $("#dias_vacaciones").val();

        $.ajax({
          url: 'function_vacacionescalculo.php?fkEmpleado='+fkEmpleado+'&dias='+dias_vacaciones+'&sueldoSemanal='+sueldoSemanal,
          success: function(data){
             var datos = JSON.parse(data);
             var info = "<span id='sueldo_vacaciones'><b>Sueldo vacaciones: </b></span>" + datos.salario_vacaciones + "<br>" +
                        "<span id='prima_vacacionaltxt'><b>Prima vacacional: </b></span>" + datos.prima_vacacional + "<br>" +
                        "<span id='sueldo_total'><b>Sueldo total: </b></span>" + datos.total_vacaciones;

             $('#mostrarVacaciones').html(info);
      }});
    });

    $(document).ready(function(){
      $("#alertaTareas").load('../../alerta_Tareas_Nuevas.php?user='+<?=$_SESSION['PKUsuario'];?>+'&ruta='+'<?=$ruta;?>');
        setInterval(refrescar, 5000);
      });
    function refrescar(){
      $("#alertaTareas").load('../../alerta_Tareas_Nuevas.php?user='+<?=$_SESSION['PKUsuario'];?>+'&ruta='+'<?=$ruta;?>');
    }

</script>


</body>

</html>
