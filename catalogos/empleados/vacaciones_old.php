<?php
session_start();

  if(isset($_SESSION["Usuario"]) && ($_SESSION["FKRol"] == 1 || $_SESSION["FKRol"] == 4)){

    require_once('../../include/db-conn.php');
    $fkEmpleado = $_GET['id'];

    $stmt = $conn->prepare("SELECT e.*, p.Sueldo_semanal, de.PKLaboralesEmpleado, IFNULL(de.Dias_de_Vacaciones,0) as Dias_de_Vacaciones,
                            IFNULL(SUM(v.Dias_de_Vacaciones_Tomados),0) as Dias_de_Vacaciones_Tomados, t.Turno, p.Puesto, dm.NSS
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

  <title>Timlid | Vacaciones</title>

  <!-- Bootstrap core JavaScript-->
  <script src="../../vendor/jquery/jquery.min.js"></script>
  <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="../../js/sb-admin-2.min.js"></script>
  <script src="../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../vendor/datatables/dataTables.bootstrap4.min.js"></script>

  <!-- Page level plugins -->

  <script src="../../js/cambiar_Estatus_Asistencia.js"></script>
  <script src="../../js/nomina.js"></script>

  <!-- Custom fonts for this template -->
  <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="../../css/sb-admin-2.css" rel="stylesheet">
  <link href="../../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

  <script>
    $(document).ready(function(){
      var table;
      var fkEmpleado = <?php echo $fkEmpleado;?>;

        $("#tblVacaciones").show();
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
        $("#tblVacaciones").dataTable(
        {
          "ajax":"functions/function_vacaciones.php?idempleado="+fkEmpleado,
            "columns":[
              {"data":"PKVacaciones"},
              {"data":"Dias de vacaciones"},
              {"data":"Fecha Inicial"},
              {"data":"Fecha Final"},
              {"data":"Total Salario"},
              {"data":"Prima Vacacional"},
              {"data":"Total Vacaciones"},
              {"data":"Acciones"}
            ],
            "language": idioma_espanol,
              order: [[ 0, "desc" ]],
              columnDefs: [
                {
                "targets": [ 0 ],
                "visible": false,
                "searchable": false
                },
                { orderable: false, targets: 5 }
              ],
              responsive: true,
              destroy: true,
        }
        )

    });
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

          <?php
            $rutatb = "../";
            require_once('../topbar.php');
          ?>

        <!-- Begin Page Content -->
        <div class="container-fluid">
          <div class="card-header py-3">
              <?php
                  if($PKDatosEmpleo == NULL || $PKDatosEmpleo == ""){
                    echo "<div class='alert alert-danger' role='alert'><center>NO TIENE DATOS DE EMPLEO, FAVOR DE AGREGARLOS.</center></div>";
                  }
                  elseif($dias_vacaciones_restantes == 0){
                    echo "<div class='alert alert-danger' role='alert'><center>NO TIENE DÍAS DE VACACIONES DISPONIBLES.</center></div>";
                  }

                  if($dias_vacaciones_restantes != 0){
                  ?>
                    <a href="functions/agregar_Vacaciones.php?id=<?=$fkEmpleado;?>" class="btn btn-success float-right" ><i class="fas fa-suitcase"></i> Agregar vacaciones</a>
                  <?php
                  }
                  ?>
                  <a href="functions/agregar_dias_Vacaciones.php?id=<?=$fkEmpleado;?>" class="btn btn-info float-right" style="margin-right: 2%;"><i class="fas fa-suitcase"></i> Agregar días de vacaciones</a>
                  <a href="functions/calculo_vacaciones.php?id=<?=$fkEmpleado;?>" class="btn btn-secondary float-right" style="margin-right: 2%;"><i class="far fa-calendar-alt"></i> Calculo de vacaciones</a>
          </div>
          <!-- Page Heading -->
          <br>
          <?php
            if(isset($_GET['estatus']))
              $estatus = 1;
            else
              $estatus = 0;

            if($estatus == 1){ ?>
            <div class="alert alert-info" id="calculo-vacaciones" role="alert">
               El cálculo de las vacaciones se realizo correctamente.
            </div>
          <?php } ?>
          <h1 class="h3 mb-2 text-gray-800">Vacaciones</h1>

          <!-- DataTales Example -->
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <b>Vacaciones tomadas</b>
            </div>
            <div class="card-body">
              <input type="hidden" name="txtId" id="txtId" value="<?=$semana;?>">
              <br>
              <div class="table-responsive">
                <table class="table stripe" id="tblVacaciones" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>PKVacaciones</th>
                      <th>Días de vacaciones</th>
                      <th>Fecha Inicial</th>
                      <th>Fecha Termino</th>
                      <th>Total Salario</th>
                      <th>Prima Vacacional</th>
                      <th>Total Vacaciones</th>
                      <th>Acciones</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <th>PKVacaciones</th>
                      <th>Días de vacaciones</th>
                      <th>Fecha Inicial</th>
                      <th>Fecha Termino</th>
                      <th>Total Salario</th>
                      <th>Prima Vacacional</th>
                      <th>Total Vacaciones</th>
                      <th>Acciones</th>
                    </tr>
                  </tfoot>
                  <tbody>
                  </tbody>
                </table>
              </div>
            </div>
          </div>


          <?php

            $sueldoSemanalVacaciones = number_format(0.00,2);
            $primaVacacional = number_format(0.00,2);
            $sueldoTotal = 0.00;

          ?>

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
                    <label><b>Días de vacaciones: </b><span id="dias_vacaciones"><?=$fecha;?></span></label>
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-12">
                    <center><label><b>Periodo de vacaciones:</b><span id="periodo_vacaciones"><?=$fecha;?></label></span></center>
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
                <a href="functions/recibovacacionespdf.php?id=8" class="btn btn-success float-right" target="_blank" id="linkcambio">Imprimir recibo</a>
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

   $("#dias_vacaciones").change(function() {
        //var fkEmpleado = <?php echo $fkEmpleado;?>;
        //var sueldoSemanal = <?php echo $sueldoSemanal;?>;
        var dias_vacaciones = $("#dias_vacaciones").val();

        $.ajax({
          url: 'functions/function_vacacionescalculo.php?fkEmpleado='+fkEmpleado+'&dias='+dias_vacaciones+'&sueldoSemanal='+sueldoSemanal,
          success: function(data){
             var datos = JSON.parse(data);
             $('#prima_vacacional').val(datos.prima_vacacional);
             $('#total_vacaciones').val(datos.total_vacaciones);
             $('#mostrarVacaciones').html(datos.total_vacaciones);
      }});
    });

   $('#tblVacaciones tbody').on('click', 'button', function () {
        var id = this.id;

        var div = document.getElementById('divRecibo');
        div.style.display = "block";

        var new_position = jQuery('#divRecibo').offset();
        window.scrollTo(new_position.left,new_position.top);

        $("#linkcambio").attr("href","functions/recibovacacionespdf.php?id=" + id);

        $.ajax({
          url: 'functions/function_recibo.php?PKVacaciones='+id,
          success: function(data){
             var datos = JSON.parse(data);
             $('#dias_vacaciones').html(datos.dias_vacaciones);
             $('#periodo_vacaciones').html(datos.periodo_vacaciones);
             $('#lblSueldoVacaciones').html(datos.sueldo_vacaciones);
             $('#lblPrimaVacacional').html(datos.prima_vacacional);
             $('#lblTotal').html(datos.sueldo_total);
        }});

    } );

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
