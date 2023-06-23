<?php
  session_start();
  if(isset($_SESSION["Usuario"]) && ($_SESSION["FKRol"] == 4 || $_SESSION["FKRol"] == 1)){
    require_once('../../../include/db-conn.php');
    if(isset($_GET['id'])){
      $id = $_GET['id'];
      $total = 0;
      $stmt = $conn->prepare('SELECT *,pp.Importe AS total FROM pagos_productos AS pp
                              RIGHT JOIN compras_productos AS cp ON pp.FKCompra = cp.PKCompra
                              WHERE cp.PKCompra = :id');
      $stmt->bindValue(':id',$id);
      $stmt->execute();
      $rowCount = $stmt->rowCount();
        $row = $stmt->fetch();
        $referencia = $row['Referencia'];
        $importe = $row['Importe'];

        $stmt = $conn->prepare('SELECT Importe AS total FROM pagos_productos AS pp
                                WHERE pp.FKCompra = :id');
        $stmt->bindValue(':id',$id);
        $stmt->execute();
        while($row = $stmt->fetch()){
          $total += $row['total'];
        }

        $diferencia = $importe - $total;
        $total = "$".number_format($total,2);
        $importe = "$".number_format($importe,2);
        $diferencia = "$".number_format($diferencia,2);

    }
  }else {
    header("location:../../dashboard.php");
  }
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <link rel="icon" type="image/png" href="../../../img/header/bluTimlid.png">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Timlid | Ver pagos de compra</title>

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
        $("#tblPagos").dataTable(
        {
          "ajax":"function_VerPagosCompra.php?id="+id,
            "columns":[
              {"data":"No"},
              {"data":"Fecha"},
              {"data":"Cuenta"},
              {"data":"Tipo de pago"},
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
              <h1 class="h3 mb-0 text-gray-800">Ver pagos de compra</h1>
            </div>


            <div class="row">

              <div class="col-lg-12">

                <!-- Basic Card Example -->
                <div class="card shadow mb-4">
                  <div class="card-header">
                    Tarjeta pagos de compra
                  </div>
                  <div class="card-body">
                    <div class="row my-3">
                      <div class="col-lg-3 ">
                        <h4>Compra: <?=$referencia; ?></h4>
                      </div>
                      <div class="col-lg-3">
                        <h4>Importe total: <?=$importe; ?></h4>
                      </div>
                      <div class="col-lg-3">
                        <h4>Total pagado: <?=$total; ?></h4>
                      </div>
                      <div class="col-lg-3">
                        <h4>Faltante: <?=$diferencia; ?></h4>
                      </div>
                    </div>
                    <hr class="my-3"style="width: 100%">
                    <br>
                    <br>
                    <!-- Datatables aqui -->
                    <div class="table-responsive">
                      <table class="table table-bordered" id="tblPagos" width="100%" cellspacing="0">
                        <thead>
                          <tr>
                            <th>No</th>
                            <th>Fecha</th>
                            <th>Cuenta</th>
                            <th>Tipo de pago</th>
                            <th>Importe</th>
                          </tr>
                        </thead>
                        <tfoot>
                          <tr>
                            <th>No</th>
                            <th>Fecha</th>
                            <th>Cuenta</th>
                            <th>Tipo de pago</th>
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

        <!-- Modal Datos pago -->
        <div id="pagar" class="modal fade">
          <div class="modal-dialog">
            <div class="modal-content">
              <form action="" method="POST">
                <input type="hidden" name="txtId" value="<?=$id; ?>">
                <div class="modal-header">
                  <h4 class="modal-title">Registro de pago</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                  <div id="alertas"></div>
                  <div class="form-group">
                    <div class="row">
                      <div class="col-lg-6">
                        <label for="txtImporte">Importe:*</label>
                        <?php
                          $costo = 0;
                          $stmt = $conn->prepare('SELECT Importe FROM pagos_productos WHERE FKCompra = :id');
                          $stmt->execute(array(':id'=>$id));
                          $rowCount = $stmt->rowCount();
                          while($row = $stmt->fetch()){
                            if($rowCount > 0){
                              $costo += $row['Importe'];
                            }else{
                              $costo = 0;
                            }
                          }
                          $total1 = (double)$totales - (double)$costo;

                        ?>
                        <input class="form-control numericDecimal-only" style="text-align:right" type="text" pattern="^\$ \d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency" name="txtImporte" id="txtImporte" value="<?=$total1; ?>" required autofocus>

                      </div>
                      <div class="col-lg-6">
                        <label for="txtFechaPago">Fecha de pago:*</label>
                        <input class="form-control" type="date" name="txtFechaPago" id="txtFechaPago" value="" required>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="row">
                      <div class="col-lg-6">
                        <label for="cmbCuenta">Cuenta:*</label>
                        <select class="form-control" name="cmbCuenta" id="cmbCuenta" required>
                          <option value="">Seleccione una cuenta...</option>
                          <?php
                            $stmt = $conn->prepare('SELECT cb.PKCuentaProveedor,b.Nombre FROM cuentas_bancarias_proveedores AS cb
                              LEFT JOIN bancos AS b ON cb.FKBanco = b.PKBanco
                              WHERE FKProveedor = :id');
                            $stmt->bindValue(':id',1);
                            $stmt->execute();
                            while($row = $stmt->fetch()){
                              ?>
                              <option value="<?=$row['PKCuentaProveedor']; ?>"><?=$row['Nombre']; ?></option>
                            <?php } ?>
                        </select>
                      </div>
                      <div class="col-lg-6">
                        <label for="cmbTipoPago">Tipo de pago:*</label>
                        <select class="form-control" name="cmbTipoPago" id="cmbTipoPago" required>
                          <option value="">Seleccione un tipo de pago...</option>
                          <option value="1">Transferencia electrónica</option>
                          <option value="2">Efectivo</option>
                          <option value="3">Tarjeta de crédito</option>
                          <option value="4">Tarjeta de débito</option>
                          <option value="5">Por definir</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-lg-12">
                      <label for="">Notas de pago:</label>
                      <textarea class="form-control alphaNumeric-only" name="txtNotasPago" maxlength="100" rows="3" cols="80"></textarea>
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal" name="button"><i class="fas fa-times"></i> Cancelar</button>
                  <button class="btn btn-success" type="button" name="btnRegistroPago" id="btnRegistroPago" ><i class="fas fa-coins"></i> Registrar pago</button>
                </div>
              </form>
            </div>
          </div>
        </div>

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

  </body>
  <script>
  $(document).ready(function(){
    $("#alertaTareas").load('../../alerta_Tareas_Nuevas.php?user='+<?=$_SESSION['PKUsuario'];?>+'&ruta='+'<?=$ruta;?>');
      setInterval(refrescar, 5000);
    });
  function refrescar(){
    $("#alertaTareas").load('../../alerta_Tareas_Nuevas.php?user='+<?=$_SESSION['PKUsuario'];?>+'&ruta='+'<?=$ruta;?>');
  }
  </script>
</html>
