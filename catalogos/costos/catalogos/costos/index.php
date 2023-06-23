<?php
session_start();
require_once '../../../../include/db-conn.php';

if (isset($_SESSION["Usuario"])) {

  $user = $_SESSION["Usuario"];

  $pkusuario = $_SESSION["PKUsuario"];
  $ruta = "../../../";
  $screen = 8;

  $PKEmpresa = $_SESSION["IDEmpresa"];

  $rutaServer = $_ENV['RUTA_ARCHIVOS_READ'].$PKEmpresa.'/img'.'/';

  $GLOBALS["RutaServerRead"] = $rutaServer;

  require_once $ruta . '../include/db-conn.php';
} else {
  header("location:../../../dashboard.php");
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="icon" type="image/png" href="../../../../img/header/bluTimlid.png">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Timlid | Costos</title>

  <!-- ESTILOS -->
  <link href="../../../../css/slimselect.min.css" rel="stylesheet">
  <link href="../../../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../../../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../../../css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="../../../../vendor/datatables/buttons.dataTables.css" rel="stylesheet">
  <link href="../../../../css/styles.css" rel="stylesheet">
  <link href="../../style/materiales.css" rel="stylesheet">
  <link href="../../../../css/stylesNewTable.css" rel="stylesheet">
  <link href="../../../../css/lobibox.min.css" rel="stylesheet">
  <link href="../../../../css/notificaciones.css" rel="stylesheet">
  <!-- JS -->
  <script src="../../../../vendor/jquery/jquery.min.js"></script>
  <script src="../../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../../../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../../../../js/sb-admin-2.min.js"></script>
  <script src="../../../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../../../vendor/datatables/dataTables.buttons.js"></script>
  <script src="../../../../vendor/datatables/buttons.html5.min.js"></script>
  <script src="../../../../vendor/jszip/jszip.min.js"></script>
  <script src="../../js/slimselect_costos.js"></script>
  <script src="../../../../js/lobibox.min.js"></script>
  <script src="../../../../js/notificaciones_timlid.js" charset="utf-8"></script>
  <style type="text/css">
    #formDatosProveedor .form-control:disabled, #formDatosProveedor .form-control[readonly] {
        background-color: #eaecf4 !important;
        opacity: 1;
    }
   .tooltip-unico {
    display: none;
    width: auto;
    padding: 5px 10px;
    border: 1px solid #ccc;
    background: #053d76;
    box-shadow: 0 0 3px rgba(0, 0, 0, .3);
    -webkit-box-shadow: 0 0 3px rgba(0, 0, 0, .3);
    border-radius: 3px;
    -webkit-border-radius: 3px;
    position: absolute;
    top: -30px;
    right: -42px;
    z-index: 111000;
    opacity: 0.9;
    color: #FFF;
    font-size: 14px;
  }

  .tooltip-unico {
    z-index: 10000000;
  }

  .link {
    display: block;
    width: 9%;
  }

  .link:hover+.tooltip-unico {
    display: block;
  }

  .tooltip-unico:hover {
    display: block;
  }
  </style>
</head>

<body id="page-top" class="sidebar-toggled">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <?php
    $ruta = "../../../";
    $ruteEdit = $ruta . "central_notificaciones/";
    require_once $ruta . 'menu3.php';
    ?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
      <input type="hidden" id="txtUsuario" value="<?= $_SESSION['PKUsuario']; ?>">
      <input type="hidden" id="txtRuta" value="<?= $ruta; ?>">
      <input type="hidden" id="txtEdit" value="<?= $ruteEdit; ?>">
      <input type="hidden" id="txtPantalla" value="13">

      <!-- Main Content -->
      <div id="content">

        <?php
        $rutatb = "../../../";
        $icono = 'ICONO-COSTOS-AZUL.svg';
        $titulo = 'Costos';
        require_once $rutatb . 'topbar.php';
        ?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <!-- DataTales Example -->
          <div class="card mb-4">
            <div class="card-body">
              <div class="table-responsive">
                <table class="table" id="tblCostos" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Referencia</th>
                      <th>Clave interna</th>
                      <th>Nombre</th>
                      <th>Costo componentes</th>
                      <th>Costo adicionales</th>
                      <th>Gastos fijos</th>
                      <th>Utilidad</th>
                      <th>Costo total</th>
                      <th>Imagen</th>
                      <th>Estatus</th>
                      <th></th>
                    </tr>
                  </thead>
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
      $rutaf = "../../../";
      require_once $rutaf . 'footer.php';
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

  <script src="../../../../js/sb-admin-2.min.js"></script>
  <script src="../../../../js/scripts.js"></script>
</body>
<script src="../../../../js/Sortable.js"></script>
<script src="../../../../js/pagination/pagination.js"></script>
<script>
  loadAlertsNoti('alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
  setInterval(loadAlertsNoti, 10000, 'alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
</script>
<script src="../../js/costos.js" charset="utf-8"></script>
<script src="../../js/lista_combo_productos.js" charset="utf-8"></script>
<script type="text/javascript">
      _global.rutaServer = '<?php echo $GLOBALS["RutaServerRead"] ?>'; 
</script>
</html>

<!-- Modal Agregar Producto -->
  <div id="costosModal" class="modal fade" style="overflow-y: auto !important;">
    <div class="modal-dialog" style="max-width: 75% !important;">
      <div class="modal-content">
        <form action="#" method="POST" id="formAgregarCostos">
          <div class="modal-header">
            <h4 class="modal-title">Agregar costos</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-12 col-lg-9">
                <label for="usr">Producto:*</label>
                <div class="row">
                  <div class="col-lg-12 input-group">
                    <select name="cmbProductoMaterial" id="cmbProductoMaterial">
                    </select>
                    <div class="invalid-feedback" id="invalid-proveedorProd">Se debe de seleccionar un producto al cual agregar materiales.</div>
                  </div>
                </div>
              </div>
              <div class="col-12 col-lg-3"> 
                <label for="usr">Moneda:</label>
                <div class="row">
                  <div class="col-lg-12 input-group">
                    <select id="cmbMoneda" name="cmbMoneda">
                      <?php 
                          $stmt = $conn->prepare('SELECT * FROM monedas WHERE estatus = 1');
                          $stmt->execute();
                          $monedas = $stmt->fetchAll();

                          foreach($monedas as $m){
                            echo "<option value='".$m['PKMoneda']."' ";

                            if($m['Clave'] == "MXN"){
                              echo " selected";
                            }

                            echo ">".$m['Clave']."</option>";
                          }
                      ?>
                    </select>
                  </div>
                </div>
              </div>
            </div>
            <br>
            <div class="form-group">
              <div class="row">
                <div class="col-lg-12">
                  <span id="areaDatos">
                  </span>
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="row">
                <div class="col-lg-12">
                  <span id="areaCompuesto">
                  </span>
                </div>
              </div>
            </div>

          </div>
        </form>
      </div>
    </div>
  </div>


<!--ADD MODAL SLIDE PRODUCTOS-->
    <div class="modal fade right" id="agregar_Producto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="overflow-y: auto !important;">
      <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
      <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">
        <div class="modal-content">
          <form action="" method="POST">
            <input type="hidden" name="idInput" value="">
            <div class="modal-header">
              <h4 class="modal-title w-100" id="myModalLabel">Agregar Producto</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">x</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <!-- DataTales Example -->
                <div class="card mb-4">
                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table" id="tblListadoProductos" width="100%" cellspacing="0">
                        <thead>
                          <tr>
                            <th>Id</th>
                            <th>Clave</th>
                            <th>Producto</th>
                            <th>Estatus</th>
                          </tr>
                        </thead>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer justify-content-center">
              <button type="button" class="btnesp first espCancelar btnCancelarActualizacionProductos" data-dismiss="modal" id="btnCancelarActualizacionProductos"><span class="ajusteProyecto">Cancelar</span></button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!--END ADD MODAL SLIDE PRODUCTOS-->

<!--ADD MODAL SLIDE PRODUCTOS-->
<div class="modal fade right" id="agregar_GastoF" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="overflow-y: auto !important;">
  <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
  <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">
    <div class="modal-content">
      <form action="" method="POST">
        <input type="hidden" name="idInput" value="">
        <div class="modal-header">
          <h4 class="modal-title w-100" id="myModalLabel">Agregar gasto fijo</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">x</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <!-- DataTales Example -->
            <div class="card mb-4">
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table" id="tblListadoGastosF" width="100%" cellspacing="0">
                    <thead>
                      <tr>
                        <th>Id</th>
                        <th>Clave</th>
                        <th>Producto</th>
                        <th>Estatus</th>
                      </tr>
                    </thead>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer justify-content-center">
          <button type="button" class="btnesp first espCancelar btnCancelarActualizacionProductos" data-dismiss="modal" id="btnCancelarActualizacionGF"><span class="ajusteProyecto">Cancelar</span></button>
        </div>
      </form>
    </div>
  </div>
</div>
<!--END ADD MODAL SLIDE PRODUCTOS-->

<!--ADD MODAL SLIDE PROVEEDORES-->
  <div class="modal fade right" id="agregar_Proveedores" style="overflow-y: auto !important;" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
    <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">
      <div class="modal-content">
        <form action="" method="POST">
          <input type="hidden" name="idInput" value="">
          <div class="modal-header">
            <h4 class="modal-title w-100" id="myModalLabel">Agregar Proveedor</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">x</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <!-- DataTales Example -->
              <div class="card mb-4">
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table" id="tblListadoProveedores" width="100%" cellspacing="0">
                      <thead>
                        <tr>
                          <th>Id</th>
                          <th>Nombre comercial</th>
                          <th>Razón social</th>
                          <th>Estatus</th>
                        </tr>
                      </thead>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer justify-content-center">
            <button type="button" class="btnesp first espCancelar btnCancelarActualizacionProductos" data-dismiss="modal" id="btnCancelarActualizacionProductos"><span class="ajusteProyecto">Cancelar</span></button>
          </div>
        </form>
      </div>
    </div>
  </div>
<!--END ADD MODAL SLIDE PRODUCTOS-->


<!--DELETE MODAL SLIDE VEHICULO-->
<div class="modal fade" id="eliminar_Costo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="" method="POST">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">¿Desea eliminar el registro?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true" class="text-light">x</span>
          </button>
        </div>
        <div class="modal-body" style="font-size: 10 px!important; color: red;"></div>
        <div class="modal-footer">
          <button class="btn-custom btn-custom--border-blue btnCancelarActualizacion" type="button" data-dismiss="modal"><span class="ajusteProyecto">Cancelar</span></button>
          <button type="button" onclick="elminarCosto()" data-dismiss="modal" class="btn-custom btn-custom--blue">
            <span class="ajusteProyecto">Eliminar</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
<!--END DELETE MODAL SLIDE VEHICULO-->


<!-- Modal Editar Producto -->
  <div id="editarCostosModal" class="modal fade" style="overflow-y: auto !important;">
    <div class="modal-dialog" style="max-width: 75% !important;">
      <div class="modal-content">
        <form action="#" method="POST" id="formEditarCostos">
          <div class="modal-header">
            <h4 class="modal-title">Editar costos</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-12 col-lg-9">
                <label for="usr">Producto:</label>
                <div class="row">
                  <div class="col-lg-12 input-group">
                    <input type="text" name="textProducto" id="textProducto" class="form-control" disabled>
                    <input type="hidden" name="idCostoEdit" id="idCostoEdit">
                    <div class="invalid-feedback" id="invalid-proveedorProd">Se debe de seleccionar un producto al cual agregar materiales.</div>
                  </div>
                </div>
              </div>
              <div class="col-12 col-lg-3"> 
                <label for="usr">Moneda:</label>
                <div class="row">
                  <div class="col-lg-12 input-group">
                    <select id="cmbMonedaEdit" name="cmbMonedaEdit" class="form-control"></select>
                  </div>
                </div>
              </div>
            </div>
            <br>
            <div class="form-group">
              <div class="row">
                <div class="col-lg-12">
                  <span id="areaDatosEdit">
                  </span>
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="row">
                <div class="col-lg-12">
                  <span id="areaCompuestoEdit">
                  </span>
                </div>
              </div>
            </div>

          </div>
        </form>
      </div>
    </div>
  </div>

  <!--EDIT MODAL SLIDE PRODUCTOS-->
    <div class="modal fade right" id="editar_Producto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="overflow-y: auto !important;">
      <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
      <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">
        <div class="modal-content">
          <form action="" method="POST">
            <input type="hidden" name="idInput" value="">
            <div class="modal-header">
              <h4 class="modal-title w-100" id="myModalLabel">Agregar Producto</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">x</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <!-- DataTales Example -->
                <div class="card mb-4">
                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table" id="tblListadoProductosEdit" width="100%" cellspacing="0">
                        <thead>
                          <tr>
                            <th>Id</th>
                            <th>Clave</th>
                            <th>Producto</th>
                            <th>Estatus</th>
                          </tr>
                        </thead>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer justify-content-center">
              <button type="button" class="btnesp first espCancelar btnCancelarActualizacionProductos" data-dismiss="modal" id="btnCancelarActualizacionProductos"><span class="ajusteProyecto">Cancelar</span></button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!--END ADD MODAL SLIDE PRODUCTOS-->

    <!--EDIT MODAL SLIDE PROVEEDORES-->
    <div class="modal fade right" id="editar_Proveedores" style="overflow-y: auto !important;" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
      <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">
        <div class="modal-content">
          <form action="" method="POST">
            <input type="hidden" name="idInput" value="">
            <div class="modal-header">
              <h4 class="modal-title w-100" id="myModalLabel">Agregar Proveedor</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">x</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <!-- DataTales Example -->
                <div class="card mb-4">
                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table" id="tblListadoProveedoresEdit" width="100%" cellspacing="0">
                        <thead>
                          <tr>
                            <th>Id</th>
                            <th>Nombre comercial</th>
                            <th>Razón social</th>
                            <th>Estatus</th>
                          </tr>
                        </thead>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer justify-content-center">
              <button type="button" class="btnesp first espCancelar btnCancelarActualizacionProductos" data-dismiss="modal" id="btnCancelarActualizacionProductos"><span class="ajusteProyecto">Cancelar</span></button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!--END ADD MODAL SLIDE PRODUCTOS-->



    <!--ADD MODAL SLIDE PRODUCTOS ADICIONALES-->
    <div class="modal fade right" id="agregar_Producto_Adicionales" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="overflow-y: auto !important;">
      <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
      <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">
        <div class="modal-content">
          <form action="" method="POST">
            <input type="hidden" name="idInputAdicional" value="">
            <div class="modal-header">
              <h4 class="modal-title w-100" id="myModalLabel">Agregar adicionales</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">x</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <!-- DataTales Example -->
                <div class="card mb-4">
                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table" id="tblListadoProductosAdicionales" width="100%" cellspacing="0">
                        <thead>
                          <tr>
                            <th>Id</th>
                            <th>Clave</th>
                            <th>Producto</th>
                            <th>Estatus</th>
                          </tr>
                        </thead>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer justify-content-center">
              <button type="button" class="btnesp first espCancelar btnCancelarActualizacionProductos" data-dismiss="modal" id="btnCancelarActualizacionProductos"><span class="ajusteProyecto">Cancelar</span></button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!--END ADD MODAL SLIDE PRODUCTOS-->


    <!--ADD MODAL SLIDE PRODUCTOS ADICIONALES-->
    <div class="modal fade right" id="editar_Producto_Adicionales" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="overflow-y: auto !important;">
      <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
      <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">
        <div class="modal-content">
          <form action="" method="POST">
            <input type="hidden" name="idInputAdicional" value="">
            <div class="modal-header">
              <h4 class="modal-title w-100" id="myModalLabel">Agregar adicionales</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">x</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <!-- DataTales Example -->
                <div class="card mb-4">
                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table" id="tblListadoProductosAdicionalesEdit" width="100%" cellspacing="0">
                        <thead>
                          <tr>
                            <th>Id</th>
                            <th>Clave</th>
                            <th>Producto</th>
                            <th>Estatus</th>
                          </tr>
                        </thead>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer justify-content-center">
              <button type="button" class="btnesp first espCancelar btnCancelarActualizacionProductos" data-dismiss="modal" id="btnCancelarActualizacionProductos"><span class="ajusteProyecto">Cancelar</span></button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!--END ADD MODAL SLIDE PRODUCTOS-->


    <!--ADD MODAL SLIDE PRODUCTOS ADICIONALES-->
    <div class="modal fade" id="agregar_Productos_Modal" style="overflow-y: auto !important;">
      <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
      <div class="modal-dialog" style="max-width: 75% !important;" >
        <div class="modal-content">


              <div class="card mb-4">
                              <div class="modal-header">
                                <h4 class="modal-title">Agregar productos</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                              </div>

                              <form id="formDatosProducto">
                              <div class="card-body">
                                <div class="row">
                                  <div class="col-lg-12">
                                  

                                  <div class="form-group">
                                        <div class="row">
                                          <div class="col-lg-12">


                                              <div class="row">
                                                <div class="col-xl-9 col-lg-9 col-md-6 col-sm-3 col-xs-0"></div>
                                                <div class="col-xl-1 col-lg-1 col-md-2 col-sm-4 col-xs-6">
                                                  <label for="usr">Estatus:*</label>
                                                </div>
                                                <div class="col-xl-2 col-lg-2 col-md-4 col-sm-5 col-xs-6">
                                                  <input type="checkbox" id="activeProducto" name="activeProducto" class="check-custom" checked disabled>
                                                  <label class="shadow-sm check-custom-label" for="activeProducto">
                                                    <div class="circle"></div>
                                                    <div class="check-inactivo">Inactivo</div>
                                                    <div class="check-activo">Activo</div>
                                                  </label>
                                                </div>
                                              </div>



                                              <div class="form-group">
                                              <div class="row">
                                                <div class="col-lg-6">
                                                  <label for="usr">Nombre:*</label>
                                                  <div class="row">
                                                    <div class="col-lg-12 input-group">
                                                      <input class="form-control alphaNumeric-only" type="text" name="txtNombreProducto" id="txtNombreProducto" required maxlength="255" placeholder="Ej. Bata quirúgica desechable" onkeyup="escribirNombre();">
                                                      <div class="invalid-feedback" id="invalid-nombreProd">El producto debe tener un nombre.</div>
                                                    </div>
                                                  </div>
                                                </div>
                                                <div class="col-lg-6">
                                                  <label for="usr">Tipo:*</label>
                                                  <div class="row">
                                                    <div class="col-lg-12 input-group">
                                                        <select name="cmbTipoProducto" id="cmbTipoProducto" required="" onchange="cambiarTipoProd()">
                                                        </select>
                                                        <div class="invalid-feedback" id="invalid-tipoProd">El producto debe tener un tipo.</div>
                                                    </div>
                                                  </div>
                                                </div>
                                              </div>
                                            </div>



                                            <div class="form-group">
                                              <div class="row">
                                                <div class="col-lg-6"
                                                  <label for="usr">Clave interna:*</label>
                                                  <div class="row">
                                                    <div class="col-lg-12 input-group">
                                                      <input type="text" class="form-control alphaNumeric-only" name="txtClaveInternaProducto" id="txtClaveInternaProducto" required maxlength="50" placeholder="Ej. AA - 0001" onkeyup="escribirClave();" style="text-transform:uppercase">
                                                      <div class="invalid-feedback" id="invalid-claveProd">El producto debe tener una clave interna.</div>
                                                      <a href="#" class="btn-custom btn-custom--blue ml-3" id="btnGenerarClave">Generar</a>
                                                    </div>
                                                  </div>
                                                </div>
                                                <div class="col-lg-6">
                                                <label for="usr">Código de barras:</label>
                                                  <div class="row">
                                                    <div class="col-lg-12 input-group">
                                                      <input type="text" class="form-control numeric-only" name="txtCodigoBarrasProducto" id="txtCodigoBarrasProducto" maxlength="50" placeholder="Ej. 7 88492 808274" onkeyup="escribirCodigo()">
                                                      <div class="invalid-feedback" id="invalid-codigoProd">El codigo del producto debe ser unico.</div>
                                                    </div>
                                                  </div>
                                                </div>
                                              </div>
                                            </div>

                                            <div class="form-group">
                                              <div class="row">
                                                <div class="col-lg-6">
                                                  <label for="usr">Categoría:</label>
                                                  <div class="row">
                                                    <div class="col-lg-12 input-group">
                                                      <select name="cmbCategoriaProductoAAA" id="cmbCategoriaProductoAAA">
                                                      </select>
                                                    </div>
                                                  </div>
                                                </div>
                                                <div class="col-lg-6">
                                                  <label for="usr">Marca:</label>
                                                  <div class="row">
                                                    <div class="col-lg-12 input-group">
                                                      <select name="cmbMarcaProductoAAA" id="cmbMarcaProductoAAA">
                                                      </select>
                                                      <img  id="notaFMarcaProducto" name="notaFMarcaProducto" style="display: none;"
                                                      src="../../../../img/timdesk/alerta.svg" width=30px
                                                      title="Campo requerido" readonly>
                                                    </div>
                                                  </div>
                                                </div>
                                              </div>
                                            </div>




                                            <div class="form-group">
                                              <div class="row">
                                                <div class="col-lg-6">
                                                  <label>Costo unitario:*</label>
                                                  <div class="input-group">
                                                      <input class="form-control costoUnitarioClass" type="text" maxlength="13" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" name="txtCostoUniFabri" id="txtCostoUniFabri" placeholder="Ej. 10.00" style="float:left;" onkeyup="validEmptyInput('txtCostoUniFabri', 'invalid-costoFabrProd', 'El producto debe tener un costo de fabricación.')" required>
                                                      <span class="input-group-append">
                                                        <select name="cmbCostoUniFabri" id="cmbCostoUniFabri">
                                                            <option value="49">EUR</option>
                                                            <option value="100" selected>MXN</option>
                                                            <option value="149">USD</option>
                                                        </select>
                                                      </span>
                                                      <div class="invalid-feedback" id="invalid-costoFabrProd">El producto debe tener un costo de fabricación.</div>
                                                </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <label for="usr">Unidad de medida SAT:</label>
                                                    <input  name="txtIDUnidadSATAAA" id="txtIDUnidadSATAAA" type="hidden" value="1" readonly>
                                                    <div class="row">
                                                      <div class="col-lg-12 input-group">
                                                        <input type="text" class="form-control" name="cmbUnidadSATAAA" id="cmbUnidadSATAAA" data-toggle="modal" data-target="#agregar_UnidadSAT" 
                                                        placeholder="Seleccione una unidad de medida..." value="S/C - Sin Clave" readonly required="" >
                                                        <img  id="notaFUnidadSAT" name="notaFUnidadSAT" style="display: none;"
                                                        src="../../../../img/timdesk/alerta.svg" width=30px
                                                        title="Campo requerido" readonly>
                                                      </div>
                                                    </div>
                                                </div>
                                              </div>
                                            </div>



                                            <div class="form-group">
                                              <div class="row">
                                                <div class="col-lg-12">
                                                  <label for="usr">Descripción:</label>
                                                  <div class="row">
                                                    <div class="col-lg-12 input-group">
                                                      <textarea type="text" class="form-control" maxlength="255" id="txtDescripcionLargaAAA"
                                                      name="txtDescripcionLargaAAA" cols="30" rows="3" placeholder="Escriba aquí la descripción"
                                                      style="resize: none!important;"></textarea>
                                                    </div>
                                                  </div>
                                                </div>
                                              </div>
                                            </div>

                                            <br>
                                            <div class="row">
                                              <div class="col-lg-12">
                                                <center>
                                                  <input type="hidden" id="TipoProductoAlta" name="TipoProductoAlta" value="">
                                                  <button type="button" class="btn-custom btn-custom--blue" id="btGuardarProducto" >Guardar</button>
                                                </center>
                                              </div>
                                            </div>







                                        </div>
                                    </div>
                                  </div>

                                  
                                  </div>
                                </div>
                              </div>
                              </form>



              </div>

        </div>
      </div>
    </div>



  
 <!--ADD MODAL PROVEEDORES-->
 <!--ADD MODAL SLIDE PRODUCTOS-->
<div class="modal fade right" id="agregar_GastoFEdit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="overflow-y: auto !important;">
  <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">
    <div class="modal-content">
      <form action="" method="POST">
        <input type="hidden" name="idInput" value="">
        <div class="modal-header">
          <h4 class="modal-title w-100" id="myModalLabel">Agregar gasto fijo</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">x</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <!-- DataTales Example -->
            <div class="card mb-4">
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table" id="tblListadoGastosFEdit" width="100%" cellspacing="0">
                    <thead>
                      <tr>
                        <th>Id</th>
                        <th>Clave</th>
                        <th>Producto</th>
                        <th>Estatus</th>
                      </tr>
                    </thead>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer justify-content-center">
          <button type="button" class="btnesp first espCancelar btnCancelarActualizacionProductos" data-dismiss="modal" id="btnCancelarActualizacionGF"><span class="ajusteProyecto">Cancelar</span></button>
        </div>
      </form>
    </div>
  </div>
</div>
<!--END ADD MODAL SLIDE PRODUCTOS-->
 <div class="modal fade" id="agregar_Proveedores_Modal" style="overflow-y: auto !important;">
      <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
      <div class="modal-dialog" style="max-width: 75% !important;" >
        <div class="modal-content">

            <div class="card mb-4">
                        <div class="modal-header">
                          <h4 class="modal-title">Agregar proveedor</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        </div>
            </div>


              <div class="card-body">

                  <div class="row">
                    <div class="col-lg-12">
                      <form id="formDatosProveedor"> 
                        <div class="form-group">
                          <div class="row">
                            <div class="col-xl-9 col-lg-9 col-md-6 col-sm-3 col-xs-0">
                              
                            </div>
                            <div class="col-xl-1 col-lg-1 col-md-2 col-sm-4 col-xs-6">
                              <label for="usr">Estatus:*</label>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-4 col-sm-5 col-xs-6">
                              <input type="checkbox" id="active-proveedor" class="check-custom" checked disabled>
                              <label class="shadow-sm check-custom-label" for="active-proveedor">
                                <div class="circle"></div>
                                <div class="check-inactivo">Inactivo</div>
                                <div class="check-activo">Activo</div>
                              </label>
                            </div>
                          </div>
                        </div>
                        
                        <div class="form-group">
                          <div class="row">
                            <div class="col-lg-6">
                              <label for="usr">Nombre comercial:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control alphaNumeric-only" type="text" name="txtNombreComercial" id="txtNombreComercial" autofocus="" required="" maxlength="255" placeholder="Ej. GH Medic" onkeyup="escribirNombreProveedor()">
                                  <div class="invalid-feedback" id="invalid-nombreProv">El proveedor debe tener un nombre.</div>
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-6">
                              <label for="usr">Vendedor:</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control alphaNumeric-only" type="text" name="cmbVendedor" id="cmbVendedor" maxlength="255" placeholder="Ej. José María López Pérez" onkeypress="return soloLetras(event)">
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="row">
                            <div class="col-lg-6">
                              <label for="usr">Teléfono:</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control numeric-only" type="text" name="txtTelefono" id="txtTelefono" maxlength="10" placeholder="Ej. 33 3333 33 33">
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-6">
                              <label for="usr">Móvil:</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                <input class="form-control numeric-only" type="text" name="txtMovil" id="txtMovil" maxlength="10" placeholder="Ej. 33 3333 33 33">
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="row">
                            <div class="col-lg-6">
                              <label for="usr">E-mail principal:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control alphaNumeric-only" type="email" name="txtEmail" id="txtEmail" required="" maxlength="100" placeholder="Ej. ejemplo@dominio.com" onkeyup="validarCorreo(this.value)">
                                  <div class="invalid-feedback" id="invalid-emailProv">El proveedor debe tener un email.</div>
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-6">
                              <label for="usr">E-mail secundario:</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control alphaNumeric-only" type="email" name="txtEmail2" id="txtEmail2" maxlength="100" placeholder="Ej. ejemplo@dominio.com" onkeyup="validarCorreo2(this.value)">
                                  <div class="invalid-feedback" id="invalid-emailProv2">El proveedor debe tener un email.</div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        
                        <br>
                        <div class="form-group">
                          <div class="row">
                            <div class="col-lg-2">
                              <label for="usr">Agregar crédito:</label>
                              <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="cbxCredito" name="cbxCredito" onclick="activarCredito()">
                                <label class="form-check-label" for="cbxCredito">Activar crédito</label>
                              </div>
                            </div>
                            
                            <div class="col-lg-5">
                              <label for="usr">Monto de crédito:</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input type="text" class="form-control precioProducto" name="txtMontoCredito" id="txtMontoCredito" maxlength="13"  oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" placeholder="Ej. 1000.00" disabled required onkeyup="validEmptyInputProveedores(this)">
                                  <div class="invalid-feedback" id="invalid-montoProv">El credito debe tener un monto.</div>
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-5">
                              <label for="usr">Días de crédito:</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input type="text" class="form-control cantidadProducto" name="txtDiasCredito" id="txtDiasCredito" maxlength="3" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" max="999" min="0" placeholder="Ej. 90" disabled required onkeyup="validEmptyInputProveedores(this)">
                                  <div class="invalid-feedback" id="invalid-diasProv">El credito debe tener un numero de días</div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <br>
                        <div class="form-group">
                          <div class="row">
                            <div class="col-lg-6">
                              <label for="usr">Tipo de persona:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <select class="form-control" name="cmbTipoPersona" id="cmbTipoPersona" placeholder="Seleccionar tipo de persona" onchange="cambioTipoPersona()" required>
                                    <option data-placeholder="true"></option>  
                                    <option value="Física">Física</option>
                                    <option value="Moral">Moral</option>
                                  </select>
                                  <div class="invalid-feedback" id="invalid-tipoPersonaProv">El proveedor debe tener un tipo de persona.</div>
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-6">
                                <label for="usr">Giro:</label>
                                <div class="row">
                                  <div class="col-lg-12 input-group">
                                    <input class="form-control alphaNumeric-only" type="text" name="txtGiro" id="txtGiro" maxlength="100" placeholder="Ej. Plásticos">
                                  </div>
                                </div>
                            </div>
                          </div>
                        </div>
                        <br>
                        <label for="">* Campos requeridos</label>
                      </form>
                      <center>
                        <a href="#" class="btn-custom btn-custom--blue" id="btnAgregarProveedor">Guardar</a>
                      </center>
                    </div>
                  </div>

          </div>


              


        </div>
      </div>
    </div>


  <!--ADD MODAL SLIDE UNIDADES SAT-->
    <div class="modal fade right" id="agregar_UnidadSAT" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
      aria-hidden="true">
      <span id="cargarUnidadSAT">
      </span>
      <input id="contadorUnidadSAT" value="0" type="hidden">
      <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
      <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">
        <div class="modal-content">
          <form action="" method="POST">
            <!--<input type="hidden" name="idProyectoA" value="">-->
            <div class="modal-header">
              <h4 class="modal-title w-100" id="myModalLabel">Agregar Unidad SAT</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">x</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <!-- DataTales Example -->
                <div class="card mb-4">
                  <div class="card-body">
                    <div class="form-group">
                      <div class="row">
                        <input for="txtBuscarUnidad" class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6" value="Buscar:" style="text-align:right; border:none; background:transparent;" readonly>
                        <input type="text" class="form-control col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6" id="txtBuscarUnidad" name="txtBuscarUnidad" maxlength="255" onkeyup="buscandoUnidad();">
                      </div>
                    </div>
                    <div class="table-responsive">
                      <table class="table" id="tblListadoUnidadesSAT" width="100%" cellspacing="0">
                        <thead>
                          <tr>
                            <th>Id</th>
                            <th>Clave</th>
                            <th>Descripción</th>
                          </tr>
                        </thead>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer justify-content-center">
              <button type="button" class="btnesp first espCancelar btnCancelarActualizacionUnidad" data-dismiss="modal"
                id="btnCancelarActualizacionUnidad"><span class="ajusteProyecto">Cancelar</span></button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!--END ADD MODAL SLIDE UNIDADES SAT-->



    <!--ADD MODAL COSTOS HISTORICOS-->
    <div class="modal fade right" id="costos_historicos_Modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="overflow-y: auto !important;">
      <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
      <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">
        <div class="modal-content">
          <form action="" method="POST">
            <div class="modal-header">
              <h4 class="modal-title w-100" id="myModalLabel">Costos historicos</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">x</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <!-- DataTales Example -->
                <div class="card mb-4">
                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table" id="tblListadoCostosHistoricos" width="100%" cellspacing="0">
                        <thead>
                          <tr>
                            <th>Producto</th>
                            <th>Proveedor</th>
                            <th>Fecha</th>
                            <th>Costo</th>
                          </tr>
                        </thead>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer justify-content-center">
              <button type="button" class="btnesp first espCancelar btnCancelarActualizacionProductos" data-dismiss="modal" id="btnCancelarActualizacionProductos"><span class="ajusteProyecto">Cancelar</span></button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!--END ADD MODAL SLIDE PRODUCTOS-->


  <script type="text/javascript">
      var selectMoneda = new SlimSelect({
                select: '#cmbMoneda',
                deselectLabel: '<span class="">✖</span>'
              });

      var selectMonedaEdit = new SlimSelect({
        select: '#cmbMonedaEdit',
        deselectLabel: '<span class="">✖</span>'
      });

      var selectProductosAgr =  new SlimSelect({
        select: "#cmbTipoProducto",
        deselectLabel: '<span class="">✖</span>',
        /*addable: function (value) {
          validarTipoProducto(value);
        }*/
      });

      var selectCatProductoAgr = new SlimSelect({
        select: "#cmbCategoriaProductoAAA",
        deselectLabel: '<span class="">✖</span>',
      });

       var selectMarcaProductoAgr = new SlimSelect({
        select: "#cmbMarcaProductoAAA",
        deselectLabel: '<span class="">✖</span>',
      });

       var selectMonedaProducto = new SlimSelect({
        select: "#cmbCostoUniFabri",
        deselectLabel: '<span class="">✖</span>',
      });

       var selectTipoPersona = new SlimSelect({
        select: "#cmbTipoPersona",
        deselectLabel: '<span class="">✖</span>',
      });

      /* VALIAR QUE NO SE REPITA LA CLAVE INTERNA y Nombre AGREGADA POR EL USUARIO EN AGREGAR */
      function escribirNombre() {
        var valor = document.getElementById("txtNombreProducto").value;
        console.log("Valor nombre: " + valor);
        $.ajax({
          url: "../../../inventarios_productos/php/funciones.php",
          data: { clase: "get_data", funcion: "validar_nombre", data: valor },
          dataType: "json",
          success: function (data) {
            //console.log("respuesta nombre valida: ", data);
            /* Validar si ya existe el identificador con ese nombre*/
            if (parseInt(data[0]["existe"]) == 1) {
              $("#invalid-nombreProd").css("display", "block");
              $("#invalid-nombreProd").text("El nombre ya esta en el registro.");
              $("#txtNombreProducto").addClass("is-invalid");
            } else {
              if (!valor) {
                $("#invalid-nombreProd").css("display", "block");
                $("#invalid-nombreProd").text("El producto debe tener un nombre.");
                $("#txtNombreProducto").addClass("is-invalid");
              } else {
                $("#invalid-nombreProd").css("display", "none");
                $("#txtNombreProducto").removeClass("is-invalid");
              }
            }
          },
        });
      }

//Validar que se hayan completado todos los campos antes de pasar a la siguiente pestaña
$(document).on("click", "#btnGenerarClave", function () {


  var categoria = $("#cmbTipoProducto").val();
  var categoriaTexto = $("#cmbTipoProducto option:selected").html();
  var limpieza = "";

  if (categoria == "1") {
    limpieza = "Cmp";
  } else if (categoria == "2") {
    limpieza = "Cns";
  } else if (categoria == "3") {
    limpieza = "MP";
  } else if (categoria == "4") {
    limpieza = "P";
  } else if (categoria == "5") {
    limpieza = "S";
  } else if (categoria == "6") {
    limpieza = "AF";
  } else if (categoria == "7") {
    limpieza = "A";
  } else if (categoria == "8") {
    limpieza = "SI";
  } else if (categoria == "9") {
    limpieza = "EMP";
  }else if (categoria == "10") {
    limpieza = "GF";
  } else {
    limpieza = "N";
  }

  if (limpieza != "N") {
    $.ajax({
      url: "../../../inventarios_productos/php/funciones.php",
      data: { clase: "get_data", funcion: "get_claveReferencia" },
      dataType: "json",
      success: function (respuesta) {
        $("#txtClaveInternaProducto").val(limpieza + "" + respuesta);
        $("#txtClaveInternaProducto").removeClass("is-invalid");
        $("#invalid-claveProd").css("display", "none");
      },
      error: function (error) {
        console.log(error);
      },
    });
  } else {
    $("#invalid-tipoProd").css("display", "block");
    $("#invalid-tipoProd").text(
      "Debe de seleccionarse un tipo de producto para generar clave"
    );
    $("#cmbTipoProducto").addClass("is-invalid");
  }
});

function cargarCMBTipo(data, input, tipo) {
  var html = "";
  var selected;
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_tipo", datos: tipo },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta tipo producto: ", respuesta);

      html += '<option data-placeholder="true"></option>';

      $.each(respuesta, function (i) {
        if(tipo == 2){
          if (7 === respuesta[i].PKTipoProducto) {
            selected = "selected";
          } else {
            selected = "";
          }
        }else if (tipo == 3){
          if (10 === respuesta[i].PKTipoProducto) {
            selected = "selected";
          } else {
            selected = "";
          }
        }
        else{
          if (data === respuesta[i].PKTipoProducto) {
            selected = "selected";
          } else {
            selected = "";
          }
        }

        html +=
          '<option value="' +
          respuesta[i].PKTipoProducto +
          '" ' +
          selected +
          ">" +
          respuesta[i].TipoProducto +
          "</option>";
      });

      /*html += '<option value="add" style="background-color: #15589B;  color:white; text-align:center; width:100%;">Configurar tipos de producto</option>';*/

      $("#" + input + "").html(html);
    },
    error: function (error) {
      console.log(error);
    },
  });
}


function cambiarTipoProd() {
  var tipoProd = $("#cmbTipoProducto").val();
  if (tipoProd) {
    $("#invalid-tipoProd").css("display", "none");
    $("#cmbTipoProducto").removeClass("is-invalid");
  }
}


function cargarCMBCategoria(data, input) {
  var html = "";
  var html2 = "";
  var selected;
  $.ajax({
    url: "../../../inventarios_productos/php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_categoria" },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta categoría: ", respuesta);

      //html += '<option value="0">Seleccione una categoría...</option>';

      $.each(respuesta, function (i) {
        if (data === respuesta[i].PKCategoriaProducto) {
          selected = "selected";
        } else {
          selected = "";
        }

        if ("Sin categoría" == respuesta[i].CategoriaProductos) {
          html2 =
            '<option value="' +
            respuesta[i].PKCategoriaProducto +
            '" ' +
            selected +
            ">" +
            respuesta[i].CategoriaProductos +
            "</option>";
        } else {
          html +=
            '<option value="' +
            respuesta[i].PKCategoriaProducto +
            '" ' +
            selected +
            ">" +
            respuesta[i].CategoriaProductos +
            "</option>";
        }
      });

      $("#" + input + "").html(html2 + html);
    },
    error: function (error) {
      console.log(error);
    },
  });
}


function cargarCMBMarca(data, input) {
  var html = "";
  var html2 = "";
  var selected;
  $.ajax({
    url: "../../../inventarios_productos/php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_marca" },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta marca: ", respuesta);

      //html += '<option value="0">Seleccione una marca...</option>';

      $.each(respuesta, function (i) {
        if (data === respuesta[i].PKMarcaProducto) {
          selected = "selected";
        } else {
          selected = "";
        }

        if ("Sin marca" == respuesta[i].MarcaProducto) {
          html2 =
            '<option value="' +
            respuesta[i].PKMarcaProducto +
            '" ' +
            selected +
            ">" +
            respuesta[i].MarcaProducto +
            "</option>";
        } else {
          html +=
            '<option value="' +
            respuesta[i].PKMarcaProducto +
            '" ' +
            selected +
            ">" +
            respuesta[i].MarcaProducto +
            "</option>";
        }
      });

      $("#" + input + "").html(html2 + html);
    },
    error: function (error) {
      console.log(error);
    },
  });
}


$(document).on("click", "#btGuardarProducto", function () {

  let TipoProductoAlta = $("#TipoProductoAlta").val();

  if ($("#formDatosProducto")[0].checkValidity()) {
    var badNombreProd =
      $("#invalid-nombreProd").css("display") === "block" ? false : true;
    var badClaveProd =
      $("#invalid-claveProd").css("display") === "block" ? false : true;
    var badTipoProd =
      $("#invalid-tipoProd").css("display") === "block" ? false : true;
    var badCodigoProd =
      $("#invalid-codigoProd").css("display") === "block" ? false : true;
    var badCostoFabrProd =
      $("#invalid-costoFabrProd").css("display") === "block" ? false : true;

    if (
      badNombreProd &&
      badClaveProd &&
      badTipoProd &&
      badCodigoProd &&
      badCostoFabrProd
    ) {
      var CostoFabricacion;


      if (
        $("#txtCostoUniFabri").val() == "" ||
        $("#txtCostoUniFabri").val() == null
      ) {
        CostoFabricacion = 0;
      } else {
        CostoFabricacion = $("#txtCostoUniFabri").val();
      }

      var datos = {
        nombre: $("#txtNombreProducto").val(),
        claveInterna: $("#txtClaveInternaProducto").val(),
        codigoBarra: $("#txtCodigoBarrasProducto").val(),
        categoria: $("#cmbCategoriaProductoAAA").val(),
        marca: $("#cmbMarcaProductoAAA").val(),
        descripcion: $("#txtDescripcionLargaAAA").val(),
        tipo: $("#cmbTipoProducto").val(),
        unidadSAT: $("#txtIDUnidadSATAAA").val(),
        fabricacion: {
          active: 1,
          costo: CostoFabricacion,
          moneda: $("#cmbCostoUniFabri").val(),
        },
      };

      $.ajax({
        url: "../../php/funciones.php",
        data: {
          clase: "save_data",
          funcion: "save_datosProducto",
          datos,
        },
        dataType: "json",
        success: function (respuesta) {
          console.log(respuesta);
          if (respuesta[0].status) {
            Lobibox.notify("success", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../../img/timdesk/checkmark.svg",
              msg: "Producto registrado correctamente",
              sound: "../../../../../sounds/sound4",
            });
            console.log(respuesta[0].id, $("#cmbTipoProducto").val());

            $('#formDatosProducto').trigger("reset");

            if( TipoProductoAlta == 1){
              $('#tblListadoProductos').DataTable().ajax.reload();
            }

            if( TipoProductoAlta == 2){
              $('#tblListadoProductosAdicionales').DataTable().ajax.reload();
            }

            if( TipoProductoAlta == 3){
              $('#tblListadoProductosEdit').DataTable().ajax.reload();
            }

            if( TipoProductoAlta == 4){
              $('#tblListadoProductosAdicionalesEdit').DataTable().ajax.reload();
            }

            if( TipoProductoAlta == 5){
              $('#tblListadoGastosF').DataTable().ajax.reload();
            }

            if(actualizarComboProductos == 1){
              cargarProductos();
              actualizarComboProductos = 0;
            }

            $('#agregar_Productos_Modal').modal('hide');
            

          } else {
            Lobibox.notify("error", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../../img/timdesk/notificacion_error.svg",
              msg: "¡Algo salio mal :(!",
              sound: "../../../../../sounds/sound4",
            });
          }
        },
        error: function (error) {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../../../img/timdesk/notificacion_error.svg",
            msg: "¡Algo salio mal :(!",
            sound: "../../../../../sounds/sound4",
          });
        },
      });
    }
  } else {
    if (!$("#txtNombreProducto").val()) {
      $("#invalid-nombreProd").css("display", "block");
      $("#txtNombreProducto").addClass("is-invalid");
    }
    if (!$("#txtClaveInternaProducto").val()) {
      $("#invalid-claveProd").css("display", "block");
      $("#txtClaveInternaProducto").addClass("is-invalid");
    }
    if (!$("#cmbTipoProducto").val()) {
      $("#invalid-tipoProd").css("display", "block");
      $("#cmbTipoProducto").addClass("is-invalid");
    }
    if (!$("#txtCostoUniFabri").val()) {
      $("#invalid-costoFabrProd").css("display", "block");
      $("#txtCostoUniFabri").addClass("is-invalid");
    }

  }

});

function escribirCodigo() {
  var valor = document.getElementById("txtCodigoBarrasProducto").value;
  console.log("Valor codigo" + valor);
  $.ajax({
    url: "../../../inventarios_productos/php/funciones.php",
    data: { clase: "get_data", funcion: "validar_codigoBarras", data: valor },
    dataType: "json",
    success: function (data) {
      console.log("respuesta código de barras valido: ", data);
      /* Validar si ya existe el identificador con ese nombre*/
      if (parseInt(data[0]["existe"]) == 1) {
        $("#invalid-codigoProd").css("display", "block");
        $("#txtCodigoBarrasProducto").addClass("is-invalid");
        console.log("¡Ya existe!");
      } else {
        $("#invalid-codigoProd").css("display", "none");
        $("#txtCodigoBarrasProducto").removeClass("is-invalid");
        console.log("¡No existe!");
      }
    },
  });
}

function escribirClave() {
  var valor = $("#txtClaveInternaProducto").val();
  console.log("Valor clave" + valor);
  $.ajax({
    url: "../../../inventarios_productos/php/funciones.php",
    data: { clase: "get_data", funcion: "validar_claveInterna", data: valor },
    dataType: "json",
    success: function (data) {
      console.log("respuesta clave interna valida: ", data);
      /* Validar si ya existe el identificador con ese nombre*/
      if (parseInt(data[0]["existe"]) == 1) {
        $("#invalid-claveProd").css("display", "block");
        $("#invalid-claveProd").text(
          "El producto debe tener una clave interna."
        );
        $("#txtClaveInternaProducto").addClass("is-invalid");
      } else {
        if (!valor) {
          $("#invalid-claveProd").css("display", "block");
          $("#invalid-claveProd").text("El producto debe tener un nombre.");
          $("#txtClaveInternaProducto").addClass("is-invalid");
        } else {
          $("#invalid-claveProd").css("display", "none");
          $("#txtClaveInternaProducto").removeClass("is-invalid");
        }
      }
    },
  });
}

function validEmptyInput(inputID, invalidDivID, textInvalidDiv) {
  if (!$("#" + inputID).val()) {
    $("#" + inputID).addClass("is-invalid");
    $("#" + invalidDivID).css("display", "block");
    $("#" + invalidDivID).text(textInvalidDiv);
  } else {
    $("#" + inputID).removeClass("is-invalid");
    $("#" + invalidDivID).css("display", "none");
    $("#" + invalidDivID).text(textInvalidDiv);
  }
}


$(document).on("click", "#btnAgregarProveedor", function () {

  var tipoPersonaPr = $("#cmbTipoPersona").val();
  if (tipoPersonaPr) {
    $("#invalid-tipoPersonaProv").css("display", "none");
    $("#cmbTipoPersona").removeClass("is-invalid");
  } else {
    $("#invalid-tipoPersonaProv").css("display", "block");
    $("#cmbTipoPersona").addClass("is-invalid");
  }

  if ($("#formDatosProveedor")[0].checkValidity()) {
    var badNombreCom =
      $("#invalid-nombreProv").css("display") === "block" ? false : true;
    var badEmail =
      $("#invalid-emailProv").css("display") === "block" ? false : true;
    var badMonto =
      $("#invalid-montoProv").css("display") === "block" ? false : true;
    var badDiasCred =
      $("#invalid-diasProv").css("display") === "block" ? false : true;
    var badTipoPersona =
      $("#invalid-tipoPersonaProv").css("display") === "block" ? false : true;
    if (badNombreCom && badEmail && badMonto && badDiasCred && badTipoPersona) {
      var data = [];
      //El serializeArray obtiene un id consecutivo de los elementos fiel que posee el formulario
      $.each($("#formDatosProveedor").serializeArray(), function (i, field) {
        data.push({ id: i, campos: field.name, datos: field.value });
      });
      var pkUsuario = $("#PKUsuario").val();
      var montoCredito, diasCredito;

      if ($("#cbxCredito").is(":checked")) {
        montoCredito = $("#txtMontoCredito").val();
        diasCredito = $("#txtDiasCredito").val();
      } else {
        montoCredito = "0";
        diasCredito = "0";
      }

      var nombreComercial = $.trim($("#txtNombreComercial").val());
      var vendedor = $.trim($("#cmbVendedor").val());
      var telefono = $.trim($("#txtTelefono").val());
      var email = $.trim($("#txtEmail").val());
      var tipoPersona = $("#cmbTipoPersona").val();
      var email2 = $.trim($("#txtEmail2").val());
      var movil = $.trim($("#txtMovil").val());
      var giro = $.trim($("#txtGiro").val());
      var estatus = $("#active-proveedor").prop("checked") ? 1 : 0;

      $.ajax({
        url: "../../../inventarios_productos/php/funciones.php",
        data: {
          clase: "save_data",
          funcion: "save_datosProveedorTable",
          datos: data,
          datos2: nombreComercial,
          datos4: vendedor,
          datos5: montoCredito,
          datos6: diasCredito,
          datos7: telefono,
          datos8: email,
          datos9: estatus,
          datos10: tipoPersona,
          datos11: email2,
          datos12: movil,
          datos13: giro
        },
        dataType: "json",
        success: function (respuesta) {
          console.log(
            "respuesta agregar datos generales del proveedor:",
            respuesta
          );

          if (respuesta[0].status) {
            Lobibox.notify("success", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../../img/timdesk/checkmark.svg",
              msg: "Proveedor registrado correctamente.",
              sound: '../../../../../sounds/sound4'
            });

            $('#formDatosProveedor').trigger("reset");
            
            if(opcionProveedores == 1){
              $('#tblListadoProveedores').DataTable().ajax.reload();
            }
            if(opcionProveedores == 2){
              $('#tblListadoProveedoresEdit').DataTable().ajax.reload();
            }

            $('#agregar_Proveedores_Modal').modal('hide');
            
          } else {
            Lobibox.notify("error", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../../img/timdesk/notificacion_error.svg",
              msg: "¡Algo salio mal :(!",
              sound: '../../../../../sounds/sound4'
            });
          }
        },
        error: function (error) {
          console.log(error);
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../../../img/timdesk/notificacion_error.svg",
            msg: "¡Algo salio mal :(!",
            sound: '../../../../../sounds/sound4'
          });
        },
      });
    }
  } else {
    if (!$("#txtNombreComercial").val()) {
      $("#invalid-nombreProv").css("display", "block");
      $("#txtNombreComercial").addClass("is-invalid");
    }
    if (!$("#txtEmail").val()) {
      $("#invalid-emailProv").css("display", "block");
      $("#txtEmail").addClass("is-invalid");
    }
    if ($("#cbxCredito").prop("checked")) {
      if (!$("#txtMontoCredito").val()) {
        $("#invalid-montoProv").css("display", "block");
        $("#txtMontoCredito").addClass("is-invalid");
      }
      if (!$("#txtDiasCredito").val()) {
        $("#invalid-diasProv").css("display", "block");
        $("#txtDiasCredito").addClass("is-invalid");
      }
    }
    if (!$("#cmbTipoPersona").val()) {
      $("#invalid-tipoPersonaProv").css("display", "block");
    }
  }
});


function escribirNombreProveedor() {
  var valor = $("#txtNombreComercial").val();
  console.log("Valor nombre" + valor);
  $.ajax({
    url: "../../../inventarios_productos/php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "validar_nombreComercial",
      data: valor,
    },
    dataType: "json",
    success: function (data) {
      console.log("respuesta nombre valida: ", data);
      /* Validar si ya existe el identificador con ese nombre*/
      if (parseInt(data[0]["existe"]) == 1) {
        $("#invalid-nombreProv").text(
          "El nombre ya esta registrado en el sistema."
        );
        $("#invalid-nombreProv").css("display", "block");
        $("#txtNombreComercial").addClass("is-invalid");
      } else {
        $("#invalid-nombreProv").text("El proveedor debe tener un nombre.");
        $("#invalid-nombreProv").css("display", "none");
        $("#txtNombreComercial").removeClass("is-invalid");
        console.log("¡No existe!");
      }
    },
  });
}

function validarCorreo(item) {
  console.log(item);
  var caract = new RegExp(/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/);

  //Se muestra un texto a modo de ejemplo, luego va a ser un icono
  if (item.trim() == '') {
    $("#invalid-emailProv").text("El proveedor debe tener un email.");
    $("#invalid-emailProv").css("display", "block");
    $("#txtEmail").addClass("is-invalid");
    return;
  } 
  if (item.trim() != '') {
    $("#invalid-emailProv").css("display", "none");
    $("#txtEmail").removeClass("is-invalid");
  } 
  if (!caract.test(item)) {
    $("#invalid-emailProv").text("Debe ser un email valido.");
    $("#invalid-emailProv").css("display", "block");
    $("#txtEmail").addClass("is-invalid");
  }
  if (caract.test(item)) {
    $("#invalid-emailProv").css("display", "none");
    $("#txtEmail").removeClass("is-invalid");
  }
}

function validarCorreo2(item) {
  console.log(item);
  var caract = new RegExp(/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/);

  if (item.trim() == '') {
    $("#invalid-emailProv2").text("El proveedor debe tener un email.");
    $("#invalid-emailProv2").css("display", "none");
    $("#txtEmail2").removeClass("is-invalid");
    return;
  } 
  //Se muestra un texto a modo de ejemplo, luego va a ser un icono
  if (!caract.test(item)) {
    $("#invalid-emailProv2").text("Debe ser un email valido.");
    $("#invalid-emailProv2").css("display", "block");
    $("#txtEmail2").addClass("is-invalid");
  }
  if (caract.test(item)) {
    $("#invalid-emailProv2").css("display", "none");
    $("#txtEmail2").removeClass("is-invalid");
  }
}

function activarCredito() {
  if ($("#cbxCredito").is(":checked")) {
    console.log("Checked");
    $("#txtMontoCredito").prop("disabled", false);
    $("#txtDiasCredito").prop("disabled", false);
  } else {
    console.log("No checked");
    $("#txtMontoCredito").prop("disabled", true);
    $("#txtDiasCredito").prop("disabled", true);

    $("#txtMontoCredito").val("");
    $("#txtDiasCredito").val("");

    $("#invalid-montoProv").css("display", "none");
    $("#txtMontoCredito").removeClass("is-invalid");
    $("#invalid-diasProv").css("display", "none");
    $("#txtDiasCredito").removeClass("is-invalid");
  }
}

function cambioTipoPersona() {
  var tipoPersona = $("#cmbTipoPersona").val();
  if (tipoPersona) {
    $("#invalid-tipoPersonaProv").css("display", "none");
    $("#cmbTipoPersona").removeClass("is-invalid");
  } else {
    $("#invalid-tipoPersonaProv").css("display", "block");
    $("#cmbTipoPersona").addClass("is-invalid");
  }
}

function validEmptyInputProveedores(item, invalid = null) {
  const val = item.value;
  const parent = item.parentNode;
  let invalidDiv;
  if (invalid) {
    invalidDiv = document.getElementById(invalid);
  } else {
    for (let i = 0; i < parent.children.length; i++) {
      if (parent.children[i].classList.contains("invalid-feedback")) {
        invalidDiv = parent.children[i];
        break;
      }
    }
  }
  if (!val) {
    item.classList.add("is-invalid");
    invalidDiv.style.display = "block";
  } else {
    item.classList.remove("is-invalid");
    invalidDiv.style.display = "none";
  }
}

/*
$('#agregar_Proveedores_Modal').modal({backdrop: 'static', keyboard: false})*/

/*Permitir solamente letras y numeros*/
  $(".alphaNumeric-only").on("input", function () {
    var regexp = /[^a-zA-Z0-9 @.-]/g;
    if ($(this).val().match(regexp)) {
      $(this).val($(this).val().replace(regexp, ""));
    }
  });

  /*Permitir solamente letras y numeros sin punto*/
  $(".alphaNumericNDot-only").on("input", function () {
    var regexp = /[^a-zA-Z0-9 @]/g;
    if ($(this).val().match(regexp)) {
      $(this).val($(this).val().replace(regexp, ""));
    }
  });

  /*Permitir solamente numeros*/
  $(".numeric-only").on("input", function () {
    console.log($(this).val());
    var regexp = /[^0-9]/g;
    if ($(this).val().match(regexp)) {
      $(this).val($(this).val().replace(regexp, ""));
    }
  });

  /*Permitir solamente numeros y ":" reloj*/
  $(".time-only").on("input", function () {
    var regexp = /[^0-9:]/g;
    if ($(this).val().match(regexp)) {
      $(this).val($(this).val().replace(regexp, ""));
    }
  });

  /*Permitir numero decimales */
  $(".numericDecimal-only").on("input", function () {
    var regexp = /[^\d.]/g;
    if ($(this).val().match(regexp)) {
      $(this).val($(this).val().replace(regexp, ""));
    }
  });

</script>
<script src="../../js/unidadesSAT.js" charset="utf-8"></script>