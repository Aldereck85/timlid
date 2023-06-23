<?php
  
  $screen = 14;
  $ruta = "../";
  require_once $ruta . 'validarPermisoPantalla.php';
  if(isset($_SESSION["Usuario"]) && $permiso === 1){
    require_once '../../include/db-conn.php';
  } else {
    header("location:../dashboard.php");
  }
  $jwt_ruta = "../../";
  require_once '../jwt.php';

  date_default_timezone_set('America/Mexico_City');

  $token = $_SESSION['token_ld10d'];

 
?>
<!DOCTYPE html>
<html lang="es">

<head>
  
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="icon" type="image/png" href="../../img/header/bluTimlid.png">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Timlid | Facturación</title>

  <!-- ESTILOS -->
  <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="../../vendor/datatables/responsive.dataTables.css" rel="stylesheet">
  <link href="../../vendor/datatables/buttons.dataTables.css" rel="stylesheet">
  <link href="../../css/styles.css" rel="stylesheet">
  <link href="../../css/stylesNewTable.css" rel="stylesheet">
  <link href="../../css/slimselect.min.css" rel="stylesheet">
  <link href="../../css/sweetalert2.css" rel="stylesheet">
  <link href="../../css/lobibox.min.css" rel="stylesheet">
  <link href="../../css/notificaciones.css" rel="stylesheet">
  <link href="css/facturacion.css" rel="stylesheet">
  <!-- JS -->
  <script src="../../vendor/jquery/jquery.min.js"></script>
  <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../../js/sb-admin-2.min.js"></script>
  <script src="../../vendor/moment/moment.min.js"></script>
  <script src="../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../vendor/datatables/dataTables.buttons.js"></script>
  <script src="../../vendor/datatables/datetime-moment.js"></script>
  <script src="../../vendor/datatables/datetime.js"></script>
  <script src="../../vendor/datatables/buttons.html5.min.js"></script>
  <script src="../../vendor/jszip/jszip.min.js"></script>
  <script src="../../js/jquery.redirect.min.js"></script>
  <script src="../../js/notificaciones_timlid.js" charset="utf-8"></script>
  <script src="../../js/mdtimepicker.min.js"></script>
  <script src="../../js/permisos_usuario.js"></script>
  <script src="../../js/jquery.redirect.js"></script>
  <script src="../../js/numeral.min.js"></script>
</head>

<body id="page-top" data-screen="14">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <?php
        $icono = 'ICONO-FACTURACION-AZUL.svg';
        $titulo = 'Facturación';

        $ruteEdit = $ruta . "central_notificaciones/";
        require_once $ruta . 'menu3.php';
    ?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <?php
          $rutatb = "../";
          require_once '../topbar.php';
        ?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->

          <div id="alerta"></div>
		        <ul class="nav nav-tabs" id="myTab" role="tablist">
              <li class="nav-item" role="presentation">
                <a class="nav-link active" id="invoices-tab" data-toggle="tab" href="#invoices" role="tab" aria-controls="home" aria-selected="true">Facturas</a>
              </li>
              <li class="nav-item" role="presentation">
                <a class="nav-link" id="preinvoice-tab" data-toggle="tab" href="#preinvoices" role="tab" aria-controls="home" aria-selected="true">Prefacturas</a>
              </li>
            </ul>
          <!-- DataTales Example -->
          <div class="card shadow mb-4">
            <div class="card-body">
              <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="invoices" role="tabpanel" aria-labelledby="invoices-tab">
                  <div class="table-responsive">
                    <!-- <div class="row">
                      <div class="col-lg-3">
                        <label for="txtFechaMinima">Fecha inicial:</label>
                        <input class="form-control" type="date" name="txtFechaMinima" id="txtFechaMinima">
                      </div>
                      <div class="col-lg-3">
                        <label for="txtFechaMaxima">Fecha final:</label>
                        <input class="form-control" type="date" name="txtFechaMaxima" id="txtFechaMaxima">
                      </div>
                    </div> -->
                    <br>
                    <table class="table" id="tblCFDI" width="100%" cellspacing="0">
                      <thead>
                        <tr>
                          <th>id</th>
                          <th>Folio</th>
                          <th>Serie</th>
                          <th>Razon social</th>
                          <th>Total facturado</th>
                          <th>Estatus</th>
                          <th>Fecha de timbrado</th>
                          <th>Fecha de vencimiento</th>
                          <th>Estatus vencimiento</th>
                          <th>Vendedor</th>
                        </tr>
                      </thead>
                    
                      <tbody>
                      </tbody>
                    </table>
                  </div>
                </div>
                <div class="tab-pane fade" id="preinvoices" role="tabpanel" aria-labelledby="preinvoices-tab">
                  <div class="table-responsive">
                    <table class="table" id="tblpreinvoices" width="100%" cellspacing="0">
                      <thead>
                        <tr>
                          <th>id</th>
                          <th>Serie</th>
                          <th>Folio</th>
                          <th>Razon social</th>
                          <th>Total facturado</th>
                          <th>Estatus</th>
                          <th>Fecha de creación</th>
                          <th>Fecha de vencimiento</th>
                          <th>Estatus vencimiento</th>
                          <th>Vendedor</th>
                          <th></th>
                        </tr>
                      </thead>
                    
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

  <script src="js/facturacion.js"></script>
  <script> var ruta = "../";</script>
  <script src="../../js/sb-admin-2.min.js"></script>
  <script src="../../js/scripts.js"></script>


</body>

</html>
