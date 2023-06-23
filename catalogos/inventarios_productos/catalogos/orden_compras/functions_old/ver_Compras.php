<?php
session_start();
  if(isset($_SESSION["Usuario"]) && ($_SESSION["FKRol"] == 4 || $_SESSION["FKRol"] == 1)){
    require_once('../../../include/db-conn.php');
    if(isset($_GET['id'])){
      $id = $_GET['id'];
      $stmt = $conn->prepare('SELECT *,oc.Referencia AS ref,oc.Importe AS imp, oc.Fecha_de_Emision AS fecha FROM orden_compra AS oc
        LEFT JOIN compras_productos AS cp ON cp.FKOrdenCompra = oc.PKOrdenCompra
        LEFT JOIN proveedores AS p ON oc.FKProveedor = p.PKProveedor
        WHERE PKOrdenCompra = :id');
      $stmt->bindValue(':id', $id, PDO::PARAM_INT);
      $stmt->execute();
      $row = $stmt->fetch();
      $compra = $row['ref'];
      $fecha = $row['fecha'];
      $totales = $row['imp'];
      $fecha = date("d/m/Y",strtotime($fecha));
      $idProveedor = $row['PKProveedor'];
      $proveedor = $row['Razon_Social'];
      $precio = "$ ".number_format($row['imp'],2);
      $ordenCompra = $row['Referencia'];
      $importe = $row['Importe'];
      $fechaOrden = $row['Fecha_de_Emision'];
      $fechaOrden = date("d/m/Y",strtotime($fechaOrden));
    }
  }else {
    header("location:../../dashboard.php");
  }
  if(isset($_POST['btnRegistroPago'])){


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

  <title>Timlid | Ver compra</title>

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
      var id = <?=$id; ?>;
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
      $("#tblProductos").dataTable(
      {
        "ajax":"function_VerCompras.php?id="+id,
          "columns":[
            {"data":"No"},
            {"data":"Compra"},
            {"data":"Fecha de compra"},
            {"data":"Importe"}
          ],
          "language": idioma_espanol,
            columnDefs: [
              { orderable: false, targets: 2 }
            ],
            responsive: true
      }

      )
    });
  </script>

  <style>
  .redondear{
    border-radius: 10px;
    background: gray;
    border: 1px transparent;
    color:white;
    float:right;
    text-align:right;
    margin-right: 10px;
    margin-bottom: 20px;
    width:350px;
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
            <h1 class="h3 mb-0 text-gray-800">Ver compras</h1>
          </div>


          <div class="row">

            <div class="col-lg-12">

              <!-- Basic Card Example -->
              <div class="card shadow mb-4">
                <div class="card-header">
                  Tarjeta de compras
                </div>
                <div class="card-body">

                  <div class="row my-3">
                    <div class="col-lg-3 ">
                      <h4>Referencia: <?=$compra; ?></h4>
                    </div>
                    <div class="col-lg-3">
                      <h4>Fecha de emision: <?=$fecha; ?></h4>
                    </div>
                    <div class="col-lg-3">
                      <h4>Proveedor: <?=$proveedor; ?></h4>
                    </div>
                    <div class="col-lg-3">
                      <h4>Importe total: <?=$precio; ?></h4>
                    </div>
                    <hr class="my-3"style="width: 100%">
                    <br>
                  </div>
                  <?php
                    $totalNeto = 0;
                    $stmt = $conn->prepare('SELECT Importe FROM compras_productos WHERE FKOrdenCompra = :id');
                    $stmt->bindValue(':id',$id);
                    $stmt->execute();
                    while($row = $stmt->fetch()){
                      $totalNeto += $row['Importe'];
                    }
                    $totalNeto = "$ ".number_format($totalNeto,2);
                  ?>
                  <h1 class="float-right">
                    <span class="badge badge-secondary">Importe parcial: <?=$totalNeto; ?>  </span>
                  </h1>
                  <!-- Datatables aqui -->
                  <div class="table-responsive">
                    <table class="table table-bordered" id="tblProductos" width="100%" cellspacing="0">
                      <thead>
                        <tr>
                          <th>No</th>
                          <th>Compra</th>
                          <th>Fecha de compra</th>
                          <th>Importe</th>
                        </tr>
                      </thead>
                      <tfoot>
                        <tr>
                          <th>No</th>
                          <th>Compra</th>
                          <th>Fecha de compra</th>
                          <th>Importe</th>
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
      <div id="modal_envio"></div>
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
