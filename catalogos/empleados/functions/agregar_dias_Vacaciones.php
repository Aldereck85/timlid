<?php

session_start();

  if(isset($_SESSION["Usuario"]) && ($_SESSION["FKRol"] == 1 || $_SESSION["FKRol"] == 4)){

    require_once('funcion_calculovacaciones.php');

    $fkEmpleado = $_GET['id'];

    calculoVacaciones($fkEmpleado, 1);

    require('../../../include/db-conn.php');

    $stmt = $conn->prepare("SELECT de.PKLaboralesEmpleado, e.*, p.Sueldo_semanal_bruto, IFNULL(de.Dias_de_Vacaciones,0) as Dias_de_Vacaciones,
                            IFNULL(SUM(v.Dias_de_Vacaciones_Tomados),0) as Dias_de_Vacaciones_Tomados, t.Dias_de_trabajo, t.Turno, p.Puesto, dm.NSS,
                            DATE_FORMAT(de.Fecha_Ingreso, '%d/%m/%Y') as Fecha_Ingreso
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

    $fecha_ingreso = $row['Fecha_Ingreso'];

    $segundo_nombre = '';
    if(trim($row['Segundo_Nombre']) != ""){
      $segundo_nombre = ' '.$row['Segundo_Nombre'];
    }
    $nombreEmpleado = $row['Primer_Nombre'].$segundo_nombre.' '.$row['Apellido_Paterno'].' '.$row['Apellido_Materno'];

    $sueldoSemanal = $row['Sueldo_semanal_bruto'];
    $dias_vacaciones_restantes =  $row['Dias_de_Vacaciones'];

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

  <title>Timlid | Agregar vacaciones</title>

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
    #info, #error{
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

          <?php
            $rutatb = "../../";
            require_once('../../topbar.php');
          ?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Agregar vacaciones</h1>
          </div>


          <div class="row">

            <div class="col-lg-12">

              <!-- Basic Card Example -->
              <div class="card shadow mb-4" id="agregarVacaciones">
                <div class="card-header">
                  Tarjeta del empleado
                </div>
                <div class="card-body">
                    <div class="row">
                      <div class="col-lg-12">
                        <form action="" method="post">
                          <div class="form-group">
                            <div class="row">
                              <div class="col-lg-6">
                                <label><b>Empleado:</b> <?=$nombreEmpleado;?></label><br/>
                                <label><b>Sueldo semanal:</b> <?=$sueldoSemanal;?></label><br/>
                                <label><b>Fecha de ingreso:</b> <?=$fecha_ingreso;?></label><br/>
                                <label><b>Días de vacaciones restantes:</b> <?=$dias_vacaciones_restantes;?></label>
                              </div>
                              <div class="col-lg-6">
                                <center>
                                  <?php
                                    //obtener dias de vacaciones tomados un año antes
                                    $stmt = $conn->prepare("SELECT YEAR(FechaIni) as Anio,SUM(Dias_de_Vacaciones_Tomados) as Dias_Vacaciones FROM vacaciones WHERE FKEmpleado = :id AND YEAR(FechaIni) = YEAR(DATE_SUB(CURDATE(), INTERVAL 1 YEAR)) ");
                                    $stmt->bindValue(':id',$fkEmpleado);
                                    $stmt->execute();
                                    $row_fechas_ant = $stmt->fetch();

                                    if($row_fechas_ant['Dias_Vacaciones'] != "" || $row_fechas_ant['Dias_Vacaciones'] != NULL){
                                      $anio_anterior_vacaciones = $row_fechas_ant['Anio'];
                                      $dias_tomados_anterior = $row_fechas_ant['Dias_Vacaciones'];
                                    }
                                    else{
                                       $anio_anterior_vacaciones = date('Y') - 1;
                                       $dias_tomados_anterior = 0;
                                    }

                                    //obtener dias de vacaciones tomados en el año actual
                                    $stmt = $conn->prepare("SELECT SUM(Dias_de_Vacaciones_Tomados) as Dias_Vacaciones FROM vacaciones WHERE FKEmpleado = :id AND YEAR(FechaIni) = YEAR(CURDATE()) ");
                                    $stmt->bindValue(':id',$fkEmpleado);
                                    $stmt->execute();
                                    $row_fechas = $stmt->fetch();

                                    if($row_fechas['Dias_Vacaciones'] != "" || $row_fechas['Dias_Vacaciones'] != NULL){
                                      $dias_tomados = $row_fechas['Dias_Vacaciones'];
                                    }
                                    else{
                                      $dias_tomados = 0;
                                    }

                                  ?>
                                  <span id="mostrarVacaciones">
                                    <span id='dias_vacaciones_anteriores'><b>Días de vacaciones tomados del <?=$anio_anterior_vacaciones?>: </b></span><?=$dias_tomados_anterior;?><br>
                                    <span id='dias_vacaciones'><b>Días de vacaciones tomados del año actual: </b></span><?=$dias_tomados;?><br>
                                  </span>
                                </center>
                              </div>
                            </div>
                          </div>
                          <br>
                          <div class="form-group">
                            <div class="row">
                              <div class="col-lg-12">
                                <label for="usr">Días de vacaciones a agregar:</label>
                                <input type="number" name="txtDiasVacacionesAgregar" id="txtDiasVacacionesAgregar" class="form-control" step="1" required >
                              </div>
                            </div>
                          </div>
                          <div class="form-group">
                            <div class="row">
                              <div class="col-lg-12">
                                <center>
                                  <div id="info" class="alert alert-success" role="alert"></div>
                                  <div id="error" class="alert alert-danger" role="alert"></div>
                                </center>
                              </div>
                            </div>
                          </div>
                          <input type="hidden" name="IdEmpleado" id="IdEmpleado" value="<?=$fkEmpleado?>">
                          <button type="button" class="btn btn-success float-right" name="btnAgregar" id="btnAgregar">Agregar</button>
                        </form>
                      </div>
                    </div>

                </div>
              </div>

            </div>
          </div>

          <div class="card shadow mb-4" id="divRecibo" style="display:none">
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
                    <label><b>Días de vacaciones: </b><span id="lbldias_vacaciones"><?=$fecha;?></span></label>
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-12">
                    <center><label><b>Periodo de vacaciones: </b><span id="periodo_vacaciones"><?=$fecha;?></label></span></center>
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
                    <label class="float-right" id="lblSueldoVacaciones"><?=$sueldoSemanalVacaciones;?></label>
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
                <a href="recibovacacionespdf.php?id=8" class="btn btn-success float-right" target="_blank" id="linkcambio">Imprimir recibo</a>
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
  var VacacionesAgregar = $('#txtDiasVacacionesAgregar').val();
  var IdEmpleado = $('#IdEmpleado').val();

  if(VacacionesAgregar === ''){
    $("#txtDiasVacacionesAgregar")[0].reportValidity();
    $("#txtDiasVacacionesAgregar")[0].setCustomValidity('Completa este campo.');
    return;
  }

  VacacionesAgregar = parseInt(VacacionesAgregar);

  var myData={"dias_trabajo" : VacacionesAgregar, "IdEmpleado" : IdEmpleado};

  $.ajax({
      url : "function_agregar_dias_Vacaciones.php",
      type: "POST",
      data : myData,
      success: function(data,status,xhr)
       {

          $("#info").css("display","block");

          if(data == "Exito"){
            $("#info").html("Se agregarón los días de vacaciones al empleado.");
            setTimeout(function(){
                $(location).attr('href','../vacaciones.php?id=' + IdEmpleado);
              }, 3000);
          }
          else{
            $("#error").html("Se generó un error en el servidor.");
            setTimeout(function(){
                $("#info").css("display","none");
              }, 3000);
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
             $('#prima_vacacional').val(datos.prima_vacacional);
             $('#total_vacaciones').val(datos.total_vacaciones);
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
<script> var ruta = "../../";</script>
<script src="../../../js/sb-admin-2.min.js"></script>


</body>

</html>
