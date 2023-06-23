<?php
session_start();

  if(isset($_SESSION["Usuario"]) && ($_SESSION["FKRol"] == 4 || $_SESSION["FKRol"] == 4 || $_SESSION["FKRol"] == 1)){
    require_once('../../../include/db-conn.php');

      if(isset($_GET['id'])){
        $id =  $_GET['id'];
        $stmt = $conn->prepare('SELECT * FROM proveedores WHERE PKProveedor= :id');
        $stmt->execute(array(':id'=>$id));
        $row = $stmt->fetch();

        $razon = $row['Razon_Social'];
        $nombre = $row['Nombre_comercial'];
        $rfc = $row['RFC'];
        $calle = $row['Calle'];
        $numEx = $row['Numero_exterior'];
        $numInt = $row['Numero_Interior'];
        $colonia = $row['Colonia'];
        $municipio = $row['Municipio'];
        $estado = $row['Estado'];
        $cp = $row['CP'];
        $dias = $row['Dias_Credito'];
        $limite = $row['Limite_Credito'];
      }
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

  <title>Timlid | Editar proveedor</title>

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

  <script>
      $( document ).ready(function() {
        var monto = $("#txtMonto").val();
        var dias = $("#txtDias").val();
        if(monto!="" || dias!=""){
          $('#grupoCredito').prop('checked', true);
          //$("input.grupoCredito").prop("disabled", this.checked);
          $(function() {
              $("#grupoCredito").click(activarCredito);
            });

            function activarCredito() {
              $("input.grupoCredito").prop("disabled", !this.checked);
            }
        }else{
          $('#grupoCredito').prop('checked', false);
          $(function() {
              activarCredito();
              $("#grupoCredito").click(activarCredito);
            });

            function activarCredito() {
              $("input.grupoCredito").prop("disabled", !this.checked);
            }
        }

      });


  </script>
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
        "ajax":"function_verProveedor.php?id="+id,
          "columns":[
            {"data":"No"},
            {"data":"Clave"},
            {"data":"Producto"}
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
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="../../dashboard.php">
        <div class="sidebar-brand-icon">
          <img src="../../../img/header/ghMedic.png" width="150px">
        </div>
      </a>

      <!-- Divider -->
      <hr class="sidebar-divider my-0">

      <!-- Nav Item - Dashboard -->
      <li class="nav-item">
        <a class="nav-link" href="../../dashboard.php">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Dashboard</span></a>
      </li>
      <?php
      $ruta = "../../";
      require_once('../../menu3.php');
      ?>
    </ul>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Ver proveedor</h1>
          </div>


          <div class="row">

            <div class="col-lg-12">

              <!-- Basic Card Example -->
              <div class="card shadow mb-4">
                <div class="card-header">
                  Tarjeta del proveedor
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-6">
                      <h4>Razón social: <?=$razon;?></h4>
                    </div>
                    <div class="col-lg-6">
                        <h4>RFC: <?=$rfc;?></h4>
                    </div>
                  </div><hr>
                  <div class="row" style="margin-top: 30px;margin-bottom: 30px">
                    <div class="col-lg-12 text-center">
                      <h4>Productos comprados a este proveedor:</h4>
                    </div>
                  </div>
                  <div class="table-responsive">
                    <table class="table table-bordered" id="tblProductos" width="100%" cellspacing="0">
                      <thead>
                        <tr>
                          <th>No</th>
                          <th>Clave</th>
                          <th>Producto</th>
                        </tr>
                      </thead>
                      <tfoot>
                        <tr>
                          <th>No</th>
                          <th>Clave</th>
                          <th>Producto</th>
                        </tr>
                      </tfoot>
                      <tbody>
                      </tbody>
                    </table>
                  </div>



                        <!--
                        <form action="" method="post">
                          <div class="form-group">
                            <div class="row">
                              <div class="col-lg-4">
                                <label for="usr">Razón social:</label>
                                <input type="text" class="form-control alphaNumeric-only" maxlength="30" name="txtRazon" value="<?//=$razon;?>" disabled>
                              </div>
                              <div class="col-lg-4">
                                <label for="usr">Nombre comercial:</label>
                                <div class="input-group mb-3">
                                  <input type="text" class="form-control alphaNumeric-only" maxlength="30" name="txtNombre" value="<?//=$nombre;?>" disabled>
                                </div>
                              </div>
                              <div class="col-lg-4">
                                <label for="usr">RFC:</label>
                                <input type="text" class="form-control alphaNumeric-only" maxlength="30" name="txtRFC" value="<?//=$rfc;?>" disabled>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-lg-3">
                                <label for="usr">Calle:</label>
                                <input type="text" class="form-control alpha-only" maxlength="30" name="txtCalle" value="<?//=$calle;?>" disabled>
                              </div>
                              <div class="col-lg-3">
                                <label for="usr">Numero exterior:</label>
                                <div class="input-group mb-3">
                                  <input type="text" class="form-control numeric-only" maxlength="30" name="txtNumeroEx" value="<?//=$numEx;?>" disabled>
                                </div>
                              </div>
                              <div class="col-lg-3">
                                <label for="usr">Numero interior:</label>
                                <?php
                                  /*if($numInt == 0){
                                    $numInt = 'S/N';
                                  }*/
                                ?>
                                <input type="text" class="form-control alphaNumeric-only" maxlength="30" name="txtNumeroInt" value="<?//=$numInt;?>" disabled>
                              </div>
                              <div class="col-lg-3">
                                <label for="usr">Colonia:</label>
                                <input type="text" class="form-control alpha-only" maxlength="30" name="txtColonia" value="<?//=$colonia;?>" disabled>
                              </div>
                            </div>

                            <div class="row">
                              <div class="col-lg-4">
                                <label for="usr">Municipio:</label>
                                <div class="input-group mb-3">
                                  <input type="text" class="form-control alpha-only" maxlength="30" name="txtMunicipio" value="<?//=$municipio;?>" disabled>
                                </div>
                              </div>
                              <div class="col-lg-4">
                                <label for="usr">Estado:</label>
                                <input class="form-control" type="text" name="" value="<?//=$estado; ?>" disabled>
                              </div>
                              <div class="col-lg-4">
                                <label for="usr">Código Postal:</label>
                                <input type="text" class="form-control numeric-only" maxlength="30" name="txtCP" value="<?=$cp;?>" disabled>
                              </div>

                            </div>

                              <div class="row">
                                <div class="col-lg-4">
                                  <label for="txtDiasCredito">Dias de credito</label>
                                  <?php
                                    /*if(!isset($dias)){
                                      $dias = "Este proveedor no ofrece crédito";
                                    }*/
                                  ?>
                                  <input class="form-control numeric-only" type="text" name="txtDiasCredito" value="<?=$dias; ?>" disabled>
                                </div>
                                <div class="col-lg-4">
                                  <label for="txtLimiteCredito">Limite de credito</label>
                                  <?php
                                    /*if($limite == 0){
                                      $credito = "Este proveedor no ofrece crédito";
                                    }else{
                                      /$credito = "$".number_format($limite,2);
                                    }*/

                                  ?>
                                  <input class="form-control numericDecimal-only" type="text" name="txtLimiteCredito" value="<?=$credito; ?>" disabled>
                                </div>
                                <div class="col-lg-4">
                                  <label for="txtLimiteCredito">Credito usado actual:</label>
                                  <?php
                                    //$stmt = $conn->prepare('SELECT SUM(Importe) as Importe FROM orden_compra WHERE FKProveedor = :id');
                                    //$stmt->bindValue(':id',$id);
                                    //$stmt->execute();
                                    //$row = $stmt->fetch();
                                    //$suma = "Este proveedor no ofrece crédito";
                                    //if($dias != "Este proveedor no ofrece crédito"){
                                      //$suma = "$".number_format($row['Importe'],2);
                                    //}
                                  ?>
                                  <input class="form-control numericDecimal-only" type="text" name="txtLimiteCredito" value="<?=$suma; ?>" disabled>
                                </div>
                              </div>

                          </div>
                          <input type="hidden" name="txtId" value="<?//=$id;?>">
                          <button type="submit" class="btn btn-primary float-right" name="btnEditar">Editar</button>
                        </form>

                      </div>

                    </div>
                    -->
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
    $("#alertaTareas").load('../../alerta_Tareas_Nuevas.php?user='+<?=$_SESSION['PKUser'];?>+'&ruta='+'<?=$ruta;?>');
      setInterval(refrescar, 5000);
    });
  function refrescar(){
    $("#alertaTareas").load('../../alerta_Tareas_Nuevas.php?user='+<?=$_SESSION['PKUser'];?>+'&ruta='+'<?=$ruta;?>');
  }
  </script>

</body>

</html>
