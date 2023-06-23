<?php
session_start();
  if(isset($_SESSION["Usuario"]) && ($_SESSION["FKRol"] == 4 || $_SESSION["FKRol"] == 1)){
    require_once('../../../include/db-conn.php');
  }else {
    header("location:../../dashboard.php");
  }
  $id = $_GET['id'];
  $stmt = $conn->prepare('SELECT * FROM productos LEFT JOIN unidad_medida ON FKUnidadMedida = PKUnidadMedida WHERE PKProducto = :id');
  $stmt->bindValue(':id',$id);
  $stmt->execute();
  $row = $stmt->fetch();
  $clave = $row['Clave'];
  $producto = $row['Descripcion'];
  $presentacion = "";
  $letra = strtoupper($row['Unidad_de_Medida']);
  if($letra == 'PIEZA' || $letra == 'PAR'){
    $presentacion = $row['Unidad_de_Medida'];
  }else{
    $presentacion = $row['Unidad_de_Medida']." c/".$row['Piezas_por_Caja'];
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

  <title>Timlid | Ver detalles del producto</title>

  <!-- Bootstrap core JavaScript-->
  <script src="../../../vendor/jquery/jquery.min.js"></script>
  <script src="../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../../js/validaciones.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="../../../vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="../../../js/sb-admin-2.min.js"></script>

  <script src="../../../js/bootstrap-clockpicker.min.js"></script>

  <!-- Custom fonts for this template-->
  <link href="../../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../../../css/bootstrap-clockpicker.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="../../../css/sb-admin-2.css" rel="stylesheet">

  <script src="../../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../../vendor/datatables/dataTables.bootstrap4.min.js"></script>
  <link href="../../../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

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
      $("#tblDetalleCompra").dataTable(
      {
        "ajax":"function_verDetalleCompra.php?id="+<?=$id; ?>,
          "columns":[
            {"data":"No"},
            {"data":"Referencia"},
            {"data":"Fecha de compra"},
            {"data":"Proveedor"},
            {"data":"Precio unitario"},
            {"data":"Cantidad"}
          ],
          "language": idioma_espanol,
            columnDefs: [
              { orderable: false, targets: 1 }
            ],
            responsive: true
      }

      )
      $("#tblDetalleVenta").dataTable(
      {
        "ajax":"function_verDetalleVenta.php?id=2",
          "columns":[
            {"data":"No"},
            {"data":"Factura"},
            {"data":"Fecha de venta"},
            {"data":"Cliente"},
            {"data":"Precio unitario"},
            {"data":"Cantidad"}
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
            <h1 class="h3 mb-0 text-gray-800">Ver detalles del producto</h1>
          </div>
          <div class="row my-3">
            <div class="col-lg-4">
              <h4>Clave: <?=$clave; ?></h4>
            </div>
            <div class="col-lg-4">
              <h4>Producto: <?=$producto; ?></h4>
            </div>
            <div class="col-lg-4">
              <h4>Presentacion: <?=$presentacion; ?></h4>
            </div>
            <hr class="my-3"style="width: 100%">
            <br>
            <br>
          </div>


          <div class="row">

            <div class="col-lg-12">

              <!-- Basic Card Example -->
              <div class="card shadow mb-4">
                <div class="card-header">
                  Tarjeta de detalles de compra del producto
                </div>
                <div class="card-body">

                  <div class="table-responsive">
                    <table class="table table-bordered" id="tblDetalleCompra" width="100%" cellspacing="0">
                      <thead>
                        <tr>
                          <th>No</th>
                          <th>Referencia</th>
                          <th>Fecha de compra</th>
                          <th>Proveedor</th>
                          <th>Precio unitario</th>
                          <th>Cantidad</th>
                        </tr>
                      </thead>
                      <tfoot>
                        <tr>
                          <th>No</th>
                          <th>Referencia</th>
                          <th>Fecha de compra</th>
                          <th>Proveedor</th>
                          <th>Precio unitario</th>
                          <th>Cantidad</th>
                        </tr>
                      </tfoot>
                      <tbody>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>

            </div>
          </div>
          <div class="row">

            <div class="col-lg-12">

              <!-- Basic Card Example -->
              <div class="card shadow mb-4">
                <div class="card-header">
                  Tarjeta de detalles de venta del producto
                </div>
                <div class="card-body">

                  <div class="table-responsive">
                    <table class="table table-bordered" id="tblDetalleVenta" width="100%" cellspacing="0">
                      <thead>
                        <tr>
                          <th>No</th>
                          <th>Factura</th>
                          <th>Fecha de venta</th>
                          <th>Cliente</th>
                          <th>Precio unitario</th>
                          <th>Cantidad</th>
                        </tr>
                      </thead>
                      <tfoot>
                        <tr>
                          <th>No</th>
                          <th>Factura</th>
                          <th>Fecha de venta</th>
                          <th>Cliente</th>
                          <th>Precio unitario</th>
                          <th>Cantidad</th>
                        </tr>
                      </tfoot>
                      <tbody>
                      </tbody>
                    </table>
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

  <script>
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
