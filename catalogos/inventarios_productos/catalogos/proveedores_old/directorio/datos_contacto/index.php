<?php
session_start();

  if(isset($_SESSION["Usuario"]) && ($_SESSION["FKRol"] == 4 || $_SESSION["FKRol"] == 1)){
      $id =  $_GET['id'];
    $user = $_SESSION["Usuario"];
  }else {
    header("location:../../../dashboard.php");
  }
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <link rel="icon" type="image/png" href="../../../../img/header/bluTimlid.png">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Timlid | Datos de contacto</title>

  <!-- Bootstrap core JavaScript-->
  <script src="../../../../vendor/jquery/jquery.min.js"></script>
  <script src="../../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="../../../../vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="../../../../js/sb-admin-2.min.js"></script>

  <!-- Page level plugins -->
  <script src="../../../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../../../vendor/datatables/dataTables.bootstrap4.min.js"></script>

  <!-- Page level custom scripts
  <script src="js/demo/datatables-demo.js"></script>
  -->

  <!-- Custom fonts for this template -->
  <link href="../../../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="../../../../css/sb-admin-2.css" rel="stylesheet">

  <!-- Custom styles for this page -->
  <link href="../../../../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

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
      var id = $("#txtId").val();
      $("#divAgregar").html('<a href="funciones/agregar_Datos_Contacto.php?id='+id+'" class="btn btn-success float-right" ><i class="fas fa-plus"></i> Agregar contacto </a>');

      $("#tblDatosContacto").dataTable(
      {
        "ajax":"funciones/function_Datos_Contacto.php?id="+id,
          "columns":[
            {"data":"Nombre"},
            {"data":"Apellido"},
            {"data":"Puesto"},
            {"data":"Telefono"},
            {"data":"Celular"},
            {"data":"Email"},
            {"data":"Acciones"}
          ],
          "language": idioma_espanol,
            columnDefs: [
              { orderable: false, targets: 1 }
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
        $ruta = "../../../";
        require_once('../../../menu3.php');
      ?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

          <?php
            $rutatb = "../../../";
            require_once('../../../topbar.php');
          ?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <h1 class="h3 mb-2 text-gray-800">Datos de contacto</h1>
          <p class="mb-4">Directorio contactos del proveedor</p>

          <!-- DataTales Example -->
          <div class="card shadow mb-4">
            <div id="divAgregar" class="card-header py-3">
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered" id="tblDatosContacto" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Nombre</th>
                      <th>Apellido</th>
                      <th>Puesto</th>
                      <th>Telefono</th>
                      <th>Celular</th>
                      <th>Email</th>
                      <th>Acciones</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <th>Nombre</th>
                      <th>Apellido</th>
                      <th>Puesto</th>
                      <th>Telefono</th>
                      <th>Celular</th>
                      <th>Email</th>
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
        <input type="hidden" name="txtId" id="txtId" value="<?=$id;?>">
      </div>
      <!-- End of Main Content -->

      <!-- Footer -->
      <?php
        $rutaf = "../../../";
        require_once('../../../footer.php');
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

  <!-- Delete Modal mis proveedores -->
  <div id="eliminar_Contacto" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="funciones/eliminar_Datos_Contacto.php" method="POST">
          <input type="hidden" name="txtIdContactoD" id="txtIdContactoD">
          <input type="hidden" name="txtIdP" id="txtIdP">
          <div class="modal-header">
            <h4 class="modal-title">Eliminar cuenta</h4>
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

  <!-- Update Modal mis proveedores -->
  <div id="editar_Contacto" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="funciones/editar_Datos_Contacto.php" method="POST">
          <input type="hidden" name="txtIdContactoU" id="txtIdContactoU">
          <div class="modal-header">
            <h4 class="modal-title">Editar cuenta</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          </div>
          <div class="modal-body">
            <p>¿Está seguro de realizar esta acción?</p>
            <p class="text-warning"><small>Esta acción cambiará los datos del registro.</small></p>
          </div>
          <div class="modal-footer">
            <input type="button" class="btn btn-secondary" data-dismiss="modal" value="Cancelar">
            <input type="submit" class="btn btn-primary" value="Editar">
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
    function obtenerIdContactoEliminar(id,proveedor){
      document.getElementById('txtIdContactoD').value = id;
      document.getElementById('txtIdP').value = proveedor;
    }
    function obtenerIdContactoEditar(id){
      document.getElementById('txtIdContactoU').value = id;
    }
    $(document).ready(function(){
      $("#alertaTareas").load('../../../alerta_Tareas_Nuevas.php?user='+<?=$_SESSION['PKUser'];?>+'&ruta='+'<?=$ruta;?>');
        setInterval(refrescar, 5000);
      });
    function refrescar(){
      $("#alertaTareas").load('../../../alerta_Tareas_Nuevas.php?user='+<?=$_SESSION['PKUser'];?>+'&ruta='+'<?=$ruta;?>');
    }
  </script>
  <script> var ruta = "../../../";</script>
  <script src="../../../../js/sb-admin-2.min.js"></script>

</body>

</html>
