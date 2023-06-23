<?php
  $screen = 61;
  $ruta = "../../../";
  
  require_once $ruta . 'validarPermisoPantalla.php';
  if(isset($_SESSION["Usuario"]) && $permiso === 1){
    require_once '../../../../include/db-conn.php';
  } else {
    header("location:../dashboard.php");
  }
  $jwt_ruta = "../../../../";
  require_once '../../../jwt.php';

  date_default_timezone_set('America/Mexico_City');

  $token = $_SESSION['token_ld10d'];

  $fecha_limite = date("Y-m-d");

  $idProduccionOrder = $_POST['id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="icon" type="image/png" href="../../../../img/header/bluTimlid.png">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Timlid | Seguimiento Orden de Producción</title>

  <!-- ESTILOS -->
  <link href="../../../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../../../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../../../css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="../../../../vendor/datatables/responsive.dataTables.css" rel="stylesheet">
  <link href="../../../../vendor/datatables/buttons.dataTables.css" rel="stylesheet">
  <link href="../../../../css/styles.css" rel="stylesheet">
  <link href="../../../../css/stylesNewTable.css" rel="stylesheet">
  <link href="../../../../css/slimselect.min.css" rel="stylesheet">
  <link href="../../../../css/sweetalert2.css" rel="stylesheet">
  <link href="../../../../css/lobibox.min.css" rel="stylesheet">
  <link href="../../style/slimSelectStyle.css" rel="stylesheet">
  <link href="../../style/seguimiento_ordenProduccion.css" rel="stylesheet">
  
  <!-- JS -->
  <script src="../../../../vendor/jquery/jquery.min.js"></script>
  <script src="../../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../../../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../../../../js/sb-admin-2.min.js"></script>
  <script src="../../../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../../../vendor/datatables/dataTables.buttons.js"></script>
  <script src="../../../../vendor/datatables/buttons.html5.min.js"></script>
  <script src="../../../../vendor/jszip/jszip.min.js"></script>
  <script src="../../../../js/jquery.redirect.min.js"></script>
  <script src="../../../../js/jquery.redirect.js"></script>
  <script src="../../../../js/notificaciones_timlid.js" charset="utf-8"></script>
  <script src="../../../../js/mdtimepicker.min.js"></script>
  <script src="../../../../js/permisos_usuario.js"></script>
  <script src="../../../../js/sweet/sweetalert2.js"></script>
</head>
</head>
<body id="page-top" data-screen="61">
  <div id="loader"></div>
  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <?php
      $icono = '../../../../img/icons/ICONO FACTURACION-01.svg';
      $titulo = 'Seguimiento Orden de producción';
      $backIcon = true;
      $ruteEdit = $ruta . "central_notificaciones/";
      require_once $ruta . 'menu3.php';
    ?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <?php
          $rutatb = "../../../";
          require_once '../../../topbar.php';
        ?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <div class="card shadow mb-4">
            <div class="card-body">
              
              <div class="form-group form-row">
                <div class="col">
                  <h2 class="float-center" id="noOrdenProduccionH2">Orden de produccion: <span id="noOrdenProduccion"></span> </h2>
                </div>

                <div class="col">
                  <h2 class="float-right" id="estatusOrdenProduccionH2">Estatus: <span id="estatusOrdenProduccion"></span> </h2>
                  <input type="hidden" name="txtEstatusOrdenProduccion" id="txtEstatusOrdenProduccion">
                </div>
              </div>

              <div class="form-group form-row">
                <div class="col">
                  <div class="btn-action" id="btn-action">
                    <input type="hidden" name="txtProduccionOrderId" id="txtProduccionOrderId" value="<?=$idProduccionOrder;?>">
                  </div>
                </div>
              </div>

              <div class="form-group form-row">
                
                <div class="col">
                  <label for="txtSucursal">Sucursal:</label>
                  <input class="form-control" type="text" name="txtSucursal" id="txtSucursal" readonly>
                  <input type="hidden" name="txtSucursalId" id="txtSucursalId">
                </div>
                <div class="col">
                  <label for="txtFechaInicio">Fecha creación:</label>
                  <input class="form-control" type="date" name="txtFechaCreacion" id="txtFechaCreacion" readonly>
                </div>
                <div class="col">
                  <label for="txtFechaPrevista">Fecha prevista:</label>
                  <input class="form-control" type="date" name="txtFechaPrevista" id="txtFechaPrevista" readonly>
                </div>
              </div>

              <div class="form-group form-row">
                <div class="col">
                  <label for="txtResponsable">Responsable:</label>
                  <input class="form-control" type="text" name="txtResponsable" id="txtResponsable" readonly>
                </div>
                <div class="col">
                  <label for="txtProducto">Producto en fabricación:</label>
                  <input class="form-control" type="text" name="txtProducto" id="txtProducto" readonly>
                  <input type="hidden" name="txtClaveProducto" id="txtClaveProducto">
                  <input type="hidden" name="txtProductoId" id="txtProductoId">
                </div>
                
              </div>

              <div class="form-group form-row">
                
                <div class="col">
                  <label for="txtCantidadOrdenada">Cantidad a producir:</label>
                  <input class="form-control" type="text" name="txtCantidad" id="txtCantidad" readonly>
                </div>
                <div class="col">
                  <label for="txtCantidadFabricada">Cantidad fabricada:</label>
                  <input class="form-control" type="text" name="txtCantidadFabricada" id="txtCantidadFabricada" readonly>
                </div>
                <div class="col">
                  <label for="txtCantidadPendiente">Cantidad pendiente:</label>
                  <input class="form-control" type="text" name="txtCantidadPendiente" id="txtCantidadPendiente" readonly>
                </div>
              </div>

              <hr>

              <form id="data-productionOrderTracking">
                <div class="form-group form-row">
                  <div class="col">
                    <label for="txtGrupoTrabajo">Grupo de trabajo:</label>
                    <select name="cmbGrupoTrabajo" id="cmbGrupoTrabajo" required></select>
                    <div class="invalid-feedback" id="invalid-workgroup">El seguimiento de la orden de producción debe de tener un grupo de trabajo.</div>
                  </div>
                  <div class="col">
                    <label for="txtFechaFabricacion">Fecha de fabricacion:</label>
                    <input class="form-control" type="date" name="txtFechaFabricacion" id="txtFechaFabricacion" max="<?=$fecha_limite;?>" required>
                    <div class="invalid-feedback" id="invalid-manufacturingDate">El seguimiento de la orden de producción debe de tener una fecha de fabricación.</div>
                  </div>
                  <div class="col">
                    <label for="txtCantidadTerminada">Cantidad terminada:</label>
                    <input class="form-control numericDecimal-only" type="text" name="txtCantidadTerminada" id="txtCantidadTerminada" maxlength="7" required>
                    <div class="invalid-feedback" id="invalid-finishedQuantity">El seguimiento de la orden de producción debe de tener una cantidad terminada.</div>
                  </div>
                  <div class="col">
                    <label for="txtLote">Lote:</label>
                    <div id="new-lote-combo">
                      <select name="cmbLote" id="cmbLote" required></select>
                      <div class="invalid-feedback" id="invalid-cmbLote">El seguimiento de la orden de producción debe de tener un lote.</div>
                    </div>
                    <div id="new-lote-text">
                      <input class="form-control" type="text" name="txtLote" id="txtLote" disabled>
                      <div class="invalid-feedback" id="invalid-txtLote">La orden de producción debe de tener un lote.</div>
                    </div>
                      
                  </div>
                  
                  <div class="col-1 d-flex align-items-start flex-column">
                    <div class="form-check mt-auto p-0" style="margin-left:15px">
                      <input class="form-check-input" type="checkbox" name="chkNuevoLote" id="chkNuevoLote">
                      <label class="form-check-label" for="chkNuevoLote">
                        Nuevo lote
                      </label>
                    </div>
                  </div>

                </div>
              </form>

              <div class="form-group form-row">
                <div class="col d-flex align-items-end flex-column">
                <a class="btn-table-custom--blue mt-auto p-2 enabled_link" href="#" id="guardar_seguimientoOrdenProduccion" >
                    <i class="fas fa-plus-square"></i>
                      Guardar
                  </a>
                </div>
              </div>

              <div class="form-group form-row">
                <div class="col">
                  <table class="table" id="tblManufacturingHistory" width="100%" cellspacing="0">
                    <thead>
                      <tr>
                        <th>Fecha de captura</th>
                        <th>Grupo de trabajo</th>
                        <th>Fecha de fabricacion</th>
                        <th>Cantidad fabricada</th>
                        <th>Lote</th>
                        <th>Usuario que registró movimiento</th>
                        <th></th>
                      </tr>
                      
                    </thead>
                    <tbody></tbody>
                  </table>
                </div>
              </div>
              <div class="form-group form-row">
                <div class="col">
                  <label for="">Notas:</label>
                  <textarea class="form-control" name="txaNotas" id="txaNotas" cols="30" rows="2" maxlength="255" readonly></textarea>
                  <div id="caracter_limit">Limite de caracteres: 255</div>
                </div>
              </div>

            </div>
          </div>

        </div>
        <!-- End Page Content -->

      </div>
      <!-- End Main Content -->
      <!-- Footer -->
      <?php
      $rutaf = "../../../";
      require_once '../../../footer.php';
      ?>

    </div>
    <!-- End Content Wrapper -->

  </div>
  <!-- End Page Wrapper -->
  <script src="../../js/seguimiento_ordenProduccion.js"></script>
  <script src="../../../../js/slimselect.min.js"></script>
  <script src="../../../../js/lobibox.min.js"></script>
  <script src="../../../../js/scripts.js"></script>
</body>
</html>