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
  $fecha_limite_inferior = date("Y-m-d");
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="icon" type="image/png" href="../../../../img/header/bluTimlid.png">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Timlid | Agregar Órden de Producción</title>

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
  <link href="../../style/agregar_ordenProduccion.css" rel="stylesheet" >
  <link href="../../style/slimSelectStyle.css" rel="stylesheet">
  
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
  <script src="../../../../js/notificaciones_timlid.js" charset="utf-8"></script>
  <script src="../../../../js/mdtimepicker.min.js"></script>
  <script src="../../../../js/permisos_usuario.js"></script>
  <script src="../../../../js/sweet/sweetalert2.js"></script>
  
</head>
<body id="page-top" data-screen="61">
  
  <!-- Page Wrapper -->
  <div id="wrapper">
    <!-- Sidebar -->
    <?php
        $icono = '../../../../img/icons/ICONO FACTURACION-01.svg';
        $titulo = 'Agregar orden de producción';
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
              <form id="order_production-data">
                <div class="form-group form-row">
                  <div class="col">
                    <label for="cmbSucursal">Sucursal:</label>
                    <select name="cmbSucursal" id="cmbSucursal" required></select>
                    <div class="invalid-feedback" id="invalid-branch">La orden de producción debe de tener una sucursal.</div>
                  </div>
                  <div class="col">
                    <label for="cmbSucursal">Fecha:</label>
                    <input class="form-control" type="date" name="txtFechaActual" id="txtFechaActual" value="<?=date("Y-m-d",strtotime($fecha_limite_inferior));?>" readonly>
                  </div>
                  <div class="col">
                    <label for="">Fecha Prevista*:</label>
                    <input class="form-control" type="date" name="txtFechaPrevista" id="txtFechaPrevista" value="<?=$fecha_limite_inferior;?>" min="<?=$fecha_limite_inferior;?>" required disabled>
                    <div class="invalid-feedback" id="invalid-expectedDate">La orden de producción debe de tener una fecha prvista.</div>
                  </div>
                </div>
                
                <div class="form-group form-row">
                  <div class="col">
                    <label for="cmbProducto">Producto*:</label>
                    <select name="cmbProducto" id="cmbProducto" required disabled></select>
                    <div class="invalid-feedback" id="invalid-product">La orden de producción debe de tener un producto seleccionado.</div>
                  </div>
                  <div class="col">
                    <label for="txtCantidad">Cantidad*:</label>
                    <input class="form-control numericDecimal-only" type="text" name="txtCantidad" id="txtCantidad" value="" maxlength="7" disabled required>
                    <div class="invalid-feedback" id="invalid-quantity">La orden de producción debe de tener una cantidad.</div>
                  </div>
                </div>

                <div class="form-group form-row">
                  <div class="col">
                    <label for="cmbResponsable">Responsable*:</label>
                    <select name="cmbResponsable" id="cmbResponsable" disabled required></select>
                    <div class="invalid-feedback" id="invalid-responsable">La orden de producción debe de tener un responsable.</div>
                    <br><br>
                    <a href="#" id="btn-add_more_materials">Agregar más componentes al producto</a>
                  </div>
                  <div class="col">
                    <label for="cmbGrupoTrabajo">Grupo de trabajo:</label>
                    <select class="workgroup-select" name="cmbGrupoTrabajo" id="cmbGrupoTrabajo" multiple disabled></select>
                  </div>
                </div>
              </form>

              <div class="form-group form-row">
                <div class="col">
                  <table class="table" id="tblMateriales" width="100%" cellspacing="0">
                    <thead>
                      <tr>
                        <th style="display:none"></th>
                        <th>Clave</th>
                        <th>Descripción</th>
                        <th>Unidad de medida</th>
                        <th>A consumir</th>
                        <th>Stock</th>
                        <th>Lote</th>
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
                  <textarea class="form-control" name="txaNotas" id="txaNotas" cols="30" rows="2" maxlength="255"></textarea>
                  <div id="caracter_limit">Limite de caracteres: 255</div>
                </div>
                <div class="col d-flex align-items-end flex-column" >
                  <a class="btn-table-custom--blue mt-auto p-2 disabled_link" href="#" id="guardar_ordenProduccion" disabled>
                    <i class="fas fa-plus-square"></i>
                      Guardar
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- End Page Content -->

      </div>
      <!-- End Main Content -->
    </div>
    <!-- End Content Wrapper -->
  </div>

  <!-- Modal Add Lot -->
  <div class="modal fade right hide" id="add_lot_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title w-100" id="myModalLabel">Añadir lote</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="data-material_lot">
            <input type="hidden" name="txtMaterialId" id="txtMaterialId">
            <input type="hidden" name="txtRowIndex" id="txtRowIndex">
            <input type="hidden" name="txtNoInsert" id="txtNoInsert">
            <div class="form-group">
              <label for="txtProductModal">Producto:</label>
              <input class="form-control" type="text" name="txtProductModal" id="txtProductModal" disabled>
            </div>
            <div class="form-group">
              <label for="cmbLot">Lotes:</label>
              <select name="cmbLot" id="cmbLot"></select>
            </div>
            <div class="form-group">
              <label for="txtCantidadModal">Cantidad:</label>
              <input class="form-control numericDecimal-only" type="text" name="txtCantidadModal" id="txtCantidadModal" maxlength="7">
              <div class="d-flex align-items-end flex-column">
                <a class="btn-table-custom--blue mt-auto p-2" href="#" id="add_lote" disabled>
                  <i class="fas fa-plus-square"></i>
                    Añadir
                </a>
              </div>
            </div>
          </form>
          <table class="table" id="tblMaterialsLot" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th>Lote</th>
                <th>Cantidad</th>
                <th></th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
        <div class="modal-footer justify-content-center">
          <button type="button" class="btnesp first espCancelar btnCancelarActualizacion" data-dismiss="modal"
            id="btnCancelarActualizacion"><span class="ajusteProyecto">Cancelar</span></button>
          <button type="button" class="btnesp espAgregar float-right" name="btnAddLot" id="btnAddLot"><span
              class="ajusteProyecto">Aceptar</span></button>
        </div>
      </div>
    </div>
  </div>

  <!-- End Page Wrapper -->
  <script src="../../js/agregar_ordenProduccion.js"></script>
  <script src="../../../../js/slimselect.min.js"></script>
  <script src="../../../../js/validaciones.js"></script>
  <script src="../../../../js/lobibox.min.js"></script>
</body>
</html>