<?php
session_start();

  if(isset($_SESSION["Usuario"])){
    require_once('../../include/db-conn.php');
    $user = $_SESSION["Usuario"];

    $stmt = $conn->prepare('SELECT SUM(Total) AS total FROM facturacion');
    $stmt->execute();
    $total = $stmt->fetch()['total'];

  }else {
    header("location:../dashboard.php");
  }
  $alerta = -1;
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

  <title>Timlid | Facturación</title>

  <!-- Bootstrap core JavaScript-->
  <script src="../../vendor/jquery/jquery.min.js"></script>
  <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="../../js/sb-admin-2.min.js"></script>

  <!-- Page level plugins -->
  <script src="../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../vendor/datatables/dataTables.bootstrap4.min.js"></script>
  <script src="../../vendor/datatables/dataTables.buttons.js"></script>
  <script src="../../vendor/datatables/buttons.bootstrap4.min.js"></script>
  <script src="../../vendor/datatables/buttons.html5.min.js"></script>
  <script src="../../vendor/jszip/jszip.min.js"></script>

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
  <link rel="stylesheet" href="../../vendor/datatables/buttons.dataTables.css">
  <link href="../../vendor/datatables/buttons.bootstrap4.min.css" rel="stylesheet">

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
      $("#tblCFDI").dataTable(
      {
        "ajax":"functions/function_Facturacion.php",
          "columns":[
            {"data":"id"},
            {"data":"Folio"},
            {"data":"Tipo"},
            {"data":"Razon social"},
            {"data":"Saldo"},
            {"data":"Estatus"},
            {"data":"Fecha de timbrado"}
          ],
          "order": [0,'desc'],
          "language": idioma_espanol,
          "columnDefs": [
            { "targets": 0,
              "visible": false,
              "searchable": false
            },
            {
              "width":"5%",
              "targets":1
            },
            {
              "width":"8%",
              "targets":2
            },
            {
              "width":"15%",
              "targets":4
            },
            {
              "width":"15%",
              "targets":5
            },
            {
              "width":"13%",
              "targets":6,
            },

          ],
            responsive: true,
            "dom":"lBfrtip",
            "buttons": [
              {
                extend: 'excelHtml5',
                text: '<img class="readEditPermissions" type="submit" width="50px" src="../../../../img/excel-azul.svg" />',
                className: "excelDataTableButton",
                titleAttr: 'Excel',
                exportOptions: {
                  columns: [0,1,2,3,4,5]
                }
              }
            ]
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

        <?php if(isset($_GET['alerta'])){
          $alerta = $_GET['alerta'];
        } ?>
        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->

          <div id="alerta"></div>
          <!-- DataTales Example -->
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <div class="row">
                <div class="col-lg-4" style="margin-top:5px">
                  <h1 class="h3 text-gray-800" style="font-weight:bold">Facturacion</h1>
                </div>
                <div class="col-lg-4">
                  <span class="badge badge-primary" style="border-radius: 20px 20px 20px 20px;"><h4 class="float-left" style="font-weight: bold;margin: 3px 5px 3px 5px;">Saldo Total: $ <?=number_format($total,2); ?></h3></span>
                </div>
                <div class="col-lg-4">
                  <button class="btn btn-success btn-circle float-right" id="btnAgregarCFDI"><i class="fas fa-plus"></i></button>

                </div>
              </div>

            </div>

            <div class="card-body">
              <div class="table-responsive">
                <table class="table stripe" id="tblCFDI" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th></th>
                      <th>Folio</th>
                      <th>Tipo</th>
                      <th>Razon social</th>
                      <th>Saldo</th>
					            <th>Estatus</th>
                      <th>Fecha de timbrado</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <th></th>
                      <th>Folio</th>
                      <th>Tipo</th>
                      <th>Razon social</th>
                      <th>Saldo</th>
					            <th>Estatus</th>
                      <th>Fecha de timbrado</th>
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

  <!-- Delete Modal mis paqueterias -->
  <div id="eliminar_Paqueteria" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="functions/eliminar_Paqueteria.php" method="POST">
          <input type="hidden" name="idPaqueteriaD" id="idPaqueteriaD">
          <div class="modal-header">
            <h4 class="modal-title">Eliminar paqueteria</h4>
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

  <!-- Update Modal mis paqueterias -->
  <div id="editar_Paqueteria" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="functions/editar_Paqueteria.php" method="POST">
          <input type="hidden" name="idPaqueteriaU" id="idPaqueteriaU">
          <div class="modal-header">
            <h4 class="modal-title">Editar paqueteria</h4>
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
    function obtenerIdPaqueteriaEliminar(id){
      document.getElementById('idPaqueteriaD').value = id;
    }
    function obtenerIdPaqueteriaEditar(id){
      document.getElementById('idPaqueteriaU').value = id;
    }

    $(document).ready(function(){
      $("#alertaTareas").load('../alerta_Tareas_Nuevas.php?user='+<?=$_SESSION['PKUsuario'];?>+'&ruta='+'<?=$ruta;?>');
        setInterval(refrescar, 5000);
      });
    function refrescar(){
      $("#alertaTareas").load('../alerta_Tareas_Nuevas.php?user='+<?=$_SESSION['PKUsuario'];?>+'&ruta='+'<?=$ruta;?>');
    }
    $(document).ready(function(){
      $('#btnAgregarCFDI').click(function(){
        window.location.href = "functions/agregar_CFDI.php";
      });
    });

    $(document).ready(function(){
      var alerta = <?=$alerta; ?>;
      if(alerta === 1){
        $('#alerta').html('<div class="alert alert-success" role="alert">El CFDI se canceló con éxito.</div>');
      }else if(alerta === 0){
        $('#alerta').html('<div class="alert alert-danger" role="alert">El CFDI no se  pudo cancelar.</div>');
      }
    });
  </script>
  <script> var ruta = "../";</script>
  <script src="../../js/sb-admin-2.min.js"></script>


</body>

</html>
