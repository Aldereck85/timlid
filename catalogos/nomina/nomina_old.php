<?php
session_start();

  if(isset($_SESSION["Usuario"])){
    require_once('../../include/db-conn.php');
    $user = $_SESSION["Usuario"];
    if(isset($_GET['id'])){
      $id =  $_GET['id'];
      $diasTrabajo = $_GET['Dias'];
      $stmt = $conn->prepare('SELECT e.Nombres,e.PrimerApellido,e.SegundoApellido, dle.FKTurno FROM empleados as e LEFT JOIN datos_laborales_empleado as dle ON dle.FKEmpleado = e.PKEmpleado WHERE e.PKEmpleado= :id');
      $stmt->execute(array(':id'=>$id));
      $row = $stmt->fetch();
      $nombreEmpleado = $row['Nombres']." ".$row['PrimerApellido']." ".$row['SegundoApellido'];
      $idTurno = $row['FKTurno'];
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

  <title>Timlid | Checador</title>

  <!-- Bootstrap core JavaScript-->
  <script src="../../vendor/jquery/jquery.min.js"></script>
  <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Page level plugins -->
  <script src="../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../vendor/datatables/dataTables.bootstrap4.min.js"></script>
  <script src="../../vendor/datatables/dataTables.buttons.js"></script>
  <script src="../../vendor/datatables/buttons.bootstrap4.min.js"></script>
  <script src="../../vendor/datatables/buttons.html5.min.js"></script>
  <script src="../../vendor/jszip/jszip.min.js"></script>

  <!-- Custom fonts for this template -->
  <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet">

  <!-- Custom styles for this page -->
  <link href="../../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../vendor/datatables/buttons.dataTables.css" rel="stylesheet">
  <link href="../../vendor/datatables/buttons.bootstrap4.min.css" rel="stylesheet">
  <link href="../../css/styles.css" rel="stylesheet">
  <link href="../../css/stylesTable.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">


  <script src="../../js/cambiar_Estatus_Asistencia.js"></script>
  <script>
  function justificarFalta(idChecada,estatus) {
    var idEmpleado = $("#txtId").val();
    var inputs = "Hola";
    $.ajax({
      type:"POST",
      url:"actualizarEstatus.php?id="+idChecada+"&estatus="+estatus+"&idEmpleado="+idEmpleado,
      data:inputs,
      success:function(data){
        var idEmpleado = $("#txtId").val();
        var idSemana = $("#txtSem").val();

        $("#txtSem").val(idSemana);
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
        $("#tblEmpleados").dataTable(
        {
          "ajax":"functions/function_nomina.php?id="+idEmpleado+"&semana="+idSemana,
            "columns":[
              {"data":"Dia"},
              {"data":"Fecha"},
              {"data":"Fecha Sin Formato"},
              {"data":"Entrada"},
              {"data":"Salida a comer"},
              {"data":"Regreso de Comer"},
              {"data":"Salida"},
              {"data":"Tiempo de comida"},
              {"data":"Tiempo a deber"},
              {"data":"Estatus"},
              {"data":"Acciones"}
            ],
            "language": idioma_espanol,
              columnDefs: [
                { orderable: false, targets: 9, },
                { targets: 2, "visible": false,  "searchable": false }
              ],
              responsive: true,
              destroy: true,
              "order": [[ 2, "asc" ]]
        }
        )
      }
    });
  }

    $(document).ready(function(){
      var table;
      $("#tblEmpleados").hide();
      $("#btnCalcular").hide();

      $('#cmbPeriodo').on('change', function() {
        $("#tblEmpleados").show();
        $("#btnCalcular").show();
        var idEmpleado = $("#txtId").val();
        var idSemana = $("#cmbPeriodo").val();
        var turno = <?=$idTurno?>;
        var newUrl = "asistencia.php?id="+idEmpleado+"&semana="+idSemana+"&turno="+turno;
        $("#btnCalcular").attr("href", newUrl);

        $("#txtSem").val(idSemana);
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
        $("#tblEmpleados").dataTable(
        {
          "ajax":"functions/function_nomina.php?id="+idEmpleado+"&semana="+idSemana,
            "columns":[
              {"data":"Dia"},
              {"data":"Fecha"},
              {"data":"Fecha Sin Formato"},
              {"data":"Entrada"},
              {"data":"Salida a comer"},
              {"data":"Regreso de Comer"},
              {"data":"Salida"},
              {"data":"Tiempo de comida"},
              {"data":"Tiempo a deber"},
              {"data":"Estatus"},
              {"data":"Acciones"}
            ],
            "language": idioma_espanol,
              columnDefs: [
                { orderable: false, targets: 9 },
                { targets: 2, "visible": false,  "searchable": false }
              ],
              responsive: true,
              destroy: true,
              "order": [[ 2, "asc" ]]
        }
        )
      });


    });
  </script>

</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <?php
$icono = '../../img/menu/dashboardTopbar.svg';
$titulo = "Nomina";
$ruta = "../";
$ruteEdit = $ruta . "central_notificaciones/";
require_once '../menu3.php';
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
          <h1 class="h3 mb-2 text-gray-800">Nomina semanal</h1>

          <!-- DataTales Example -->
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <div class="row">
                <div class="col-lg-10">
                  <b>Asistencias de :</b> <label><?=$nombreEmpleado;?></label>
                </div>
                <div class="col-lg-2">
                  <a id="btnCalcular" class="btn btn-primary float-right" ><i class="fas fa-calculator"></i> Calcular nomina</a>
                </div>
              </div>

            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-lg-12">
                  <label for="usr">Semanas de asistencia:</label>
                  <select name="cmbPeriodo" id="cmbPeriodo" class="form-control" required>
                      <option value="">Elegir semana</option>
                          <?php
                              $stmt = $conn->prepare('SELECT * FROM semanas_checador');
                              $stmt->execute();
                          ?>
                          <?php foreach($stmt as $option) : ?>
                              <?php
                                $fechaIni = date_create($option['FechaInicio']);
                                $fechaFin = date_create($option['FechaTermino']);
                              ?>
                               <option value="<?php echo $option['PKChecador']; ?>"><?php echo date_format($fechaIni, 'd/m/Y')." - ".date_format($fechaFin, 'd/m/Y'); ?></option>
                          <?php endforeach; ?>

                  </select>

                </div>
              </div>
              <br>
              <div class="table-responsive">
                <table class="table table-bordered" id="tblEmpleados" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Dia</th>
                      <th>Fecha</th>
                      <th>Fecha Sin</th>
                      <th>Entrada</th>
                      <th>Salida a comer</th>
                      <th>Regreso de Comer</th>
                      <th>Salida</th>
                      <th>Tiempo de comida</th>
                      <th>Tiempo a deber</th>
                      <th>Estatus</th>
                      <th>Acciones</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <th>Dia</th>
                      <th>Fecha</th>
                      <th>Fecha Sin</th>
                      <th>Entrada</th>
                      <th>Salida a comer</th>
                      <th>Regreso de Comer</th>
                      <th>Salida</th>
                      <th>Tiempo de comida</th>
                      <th>Tiempo a deber</th>
                      <th>Estatus</th>
                      <th>Acciones</th>
                    </tr>
                  </tfoot>
                  <tbody>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <input type="hidden" name="txtId" id="txtId" value="<?=$id;?>">
          <input type="hidden" name="txtSem" id="txtSem">
        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

      <!-- Footer -->
      <?php
        $rutaf = "../";
        require_once('../footer.php');
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
  <script> var ruta = "../";</script>
  <script src="../../js/sb-admin-2.min.js"></script>

</body>

</html>
