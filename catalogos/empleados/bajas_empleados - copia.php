<?php
session_start();

  if(isset($_SESSION["Usuario"])){
    require_once('../../include/db-conn.php');
    $user = $_SESSION["Usuario"];
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

  <title>Timlid | Empleados</title>

  <!-- Bootstrap core JavaScript-->
  <script src="../../vendor/jquery/jquery.min.js"></script>
  <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="../../js/sb-admin-2.min.js"></script>

  <!-- Page level plugins -->
  <script src="../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../vendor/datatables/dataTables.responsive.js"></script>
  <script src="../../vendor/datatables/dataTables.bootstrap4.min.js"></script>

  <!-- Page level custom scripts
  <script src="js/demo/datatables-demo.js"></script>
  -->

  <!-- Custom fonts for this template -->
  <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="../../css/sb-admin-2.css" rel="stylesheet">

  <!-- Custom styles for this page -->
  <link href="../../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
  <link href="../../vendor/datatables/responsive.dataTables.css" rel="stylesheet">
  <script>
    $(document).ready(function(){
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
        "ajax":"functions/function_bajas_empleados.php",
          "columns":[
            {"data":"Id empleado"},
            {"data":"Primer nombre"},
            {"data":"Segundo nombre"},
            {"data":"Apellido paterno"},
            {"data":"Apellido materno"},
            {"data":"Estatus"},
            {"data":"Fecha baja"},
            {"data":"Acciones"}
          ],
          "language": idioma_espanol,
            columnDefs: [
              { orderable: false, targets: 3 }
            ],
            responsive: true
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

          <!-- Page Heading -->
          <h1 class="h3 mb-2 text-gray-800">Empleados dados de baja</h1>
          <p class="mb-4">Información general de los empleados dados de baja</p>

          <!-- DataTales Example -->
          <div class="card shadow mb-4">
            <!--
            <div class="card-header py-3">
              <a href="functions/agregar_Empleado.php" class="btn btn-success float-right" ><i class="fas fa-user-plus"></i> Agregar empleado</a>
            </div>
          -->
            <div class="card-body">
              <div class="table-responsive">
                <table class="table stripe" id="tblEmpleados" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Id empleado</th>
                      <th>Primer nombre</th>
                      <th>Segundo nombre</th>
                      <th>Apellido paterno</th>
                      <th>Apellido materno</th>
                      <th>Estatus</th>
                      <th>Fecha baja</th>
                      <th>Acciones</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <th>Id empleado</th>
                      <th>Primer nombre</th>
                      <th>Segundo nombre</th>
                      <th>Apellido paterno</th>
                      <th>Apellido materno</th>
                      <th>Estatus</th>
                      <th>Fecha baja</th>
                      <th>Acciones</th>
                    </tr>
                  </tfoot>
                  <tbody>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

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

  <!-- Delete Modal empleado baja -->
  <div id="eliminar_Empleado" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="functions/eliminar_BajaEmpleado.php" method="POST">
          <input type="hidden" name="idEmpleadoD" id="idEmpleadoD">
          <div class="modal-header">
            <h4 class="modal-title">Eliminar empleado</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          </div>
          <div class="modal-body">
            <p>¿Está seguro de realizar esta acción?</p>
            <p class="text-warning"><small>Esta acción es irreversible.</small></p>
          </div>
          <div class="modal-footer">
            <input type="button" class="btn btn-secondary" data-dismiss="modal" value="Cancelar">
            <input type="submit" class="btn btn-danger" value="Eliminar">
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Update Modal empleado alta-->
  <div id="dar_AltaEmpleado" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="functions/dar_AltaEmpleado.php" method="POST">
          <input type="hidden" name="idEmpleadoA" id="idEmpleadoA">
          <div class="modal-header">
            <h4 class="modal-title">Dar de alta al empleado</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          </div>
          <div class="modal-body">
            <p>¿Está seguro de realizar esta acción?</p>
            <p class="text-warning"><small>Esta acción dará de alta al empleado seleccionado.</small></p>
          </div>
          <div class="modal-footer">
            <input type="button" class="btn btn-secondary" data-dismiss="modal" value="Cancelar">
            <input type="submit" class="btn btn-primary" value="Dar de alta">
          </div>
        </form>
      </div>
    </div>
  </div>


  <script>
    function obtenerIdEmpleadoEliminar(id){
      document.getElementById('idEmpleadoD').value = id;
    }
    function obtenerIdEmpleadoEditar(id){
      document.getElementById('idEmpleadoU').value = id;
    }
    function obtenerIdEmpleadoAlta(id){
      document.getElementById('idEmpleadoA').value = id;
    }

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
