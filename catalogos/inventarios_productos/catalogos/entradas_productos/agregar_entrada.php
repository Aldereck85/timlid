<?php
session_start();

if (isset($_SESSION["Usuario"])) {

    $user = $_SESSION["Usuario"];

    $pkusuario = $_SESSION["PKUsuario"];
    $ruta = "../../../";
    $screen = 8;
    require_once $ruta . '../include/db-conn.php';

} else {
    header("location:../../../dashboard.php");
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
  <link rel="icon" type="image/png" href="../../../../img/header/bluTimlid.png">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Timlid | Agregar entradas</title>

  <!-- Bootstrap core JavaScript-->
  <script src="../../../../vendor/jquery/jquery.min.js"></script>
  <script src="../../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="../../../../vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Page level plugins -->
  <script src="../../../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../../../vendor/datatables/dataTables.bootstrap4.min.js"></script>
  <script src="../../../../vendor/datatables/dataTables.responsive.js"></script>
  <script src="../../../../vendor/datatables/dataTables.buttons.js"></script>
  <script src="../../../../vendor/datatables/buttons.bootstrap4.min.js"></script>
  <script src="../../../../vendor/datatables/buttons.html5.min.js"></script>
  <script src="../../../../vendor/jszip/jszip.min.js"></script>
  <script src="../../../../js/validaciones.js"></script>

  <!-- Page level custom scripts
    <script src="js/demo/datatables-demo.js"></script>
    -->

  <!-- Custom fonts for this template -->
  <link href="../../../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

  <!-- Custom styles for this template -->
  <link href="../../../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../../../css/slimselect.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../../style/agregar_entrada.css">

  <!-- Custom scripts for all pages-->

  <script src="../../../../js/sb-admin-2.min.js"></script>
  <script src="../../../../js/scripts.js"></script>

  <!-- Custom styles for this page -->
  <link href="../../../../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
  <link href="../../../../vendor/datatables/responsive.dataTables.css" rel="stylesheet">
  <link rel="stylesheet" href="../../../../vendor/datatables/buttons.dataTables.css">
  <link href="../../../../vendor/datatables/buttons.bootstrap4.min.css" rel="stylesheet">
  <link href="../../../../css/stylesTable.css" rel="stylesheet">
  <link href="../../../../css/styles.css" rel="stylesheet">
  <link rel="stylesheet" href="../../../../css/croppie.css" />

  <script src="../../../../js/numeral.min.js" charset="utf-8"></script>
  <script src="../../../../js/croppie.js"></script>

  <script src="../../../../js/sweet/sweetalert2.js"></script>
  <link rel="stylesheet" href="../../../../css/sweetalert2.css">
  <link rel="stylesheet" href="../../../../css/notificaciones.css">

  <script src="../../../../js/notificaciones_timlid.js" charset="utf-8"></script>
  <script src="../../js/agregar_entrada.js" charset="utf-8"></script>
  <script src="../../../../js/numeral.min.js"></script>
  <link href="../../../../css/lobibox.min.css" rel="stylesheet">
  
  <script src="../../../../js/lobibox.min.js"></script>
  <link href="../../../../css/stylesNewTable.css" rel="stylesheet">

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
      <input type="hidden" id="txtUsuario" value="<?=$_SESSION['PKUsuario'];?>">
      <input type="hidden" id="txtRuta" value="<?=$ruta;?>">
      <input type="hidden" id="txtEdit" value="<?=$ruteEdit;?>">
      <input type="hidden" id="txtPantalla" value="13">

      <!-- Main Content -->
      <div id="content">

        <?php
$rutatb = "../../../";
$icono = 'ICONO-ENTRADA-AZUL.svg';
$titulo = 'Agregar entradas';
$backIcon = true;
require_once $rutatb . 'topbar.php';
?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- DataTales Example -->
          <div class="card mb-4">
            <div class="card-body py-3">
              <div class="data-container">
                <form id="frmEntradaOC">
                  <div class="form-group">
                    <div class="row">
                      <!-- <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-6">
                        <div class="principal">
                          <label for="cmbTipoEntrada">Tipo de entrada:</label>
                          <select name="cmbTipoEntrada" id="cmbTipoEntrada" required></select>
                        </div>
                      </div> -->
                      <!-- input hiden que sustituye al select de tipo de entrada -->
                      <input type="hidden" value="4" id="cmbTipoEntrada">
                      <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-6">
                        <span id="div2">
                        </span>
                      </div>
                      <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-6">
                        <span id="div3">
                        </span>
                      </div>
                      <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-6"></div>
                      <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-6">
                        <span id="div55">
                        </span>
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-6">
                        <span id="div4">
                        </span>
                      </div><div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-6">
                        <span id="div66">
                        </span>
                      </div>
                    </div>
                  </div>
                   <div class="form-group">
                    <span id="div5">
                    </span>
                  </div>
                  <div class="form-group">
                    <span id="div6">
                    </span>
                  </div>
                  <div class="form-group">
                    <span id="div7">
                    </span>
                  </div>
                  <div class="form-group">
                    <span id="div8">
                    </span>
                  </div>
                  
                  <div class="form-group">
                    <div class="row">
                      <br>
                      <div class="products-disabled opacidad">
                        <div class="row">
                          <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-6">
                            <label for="usr">Referencia:</label>
                            <div class="input-group">
                              <span name="txtReferenciaP" id="txtReferenciaP"></span>
                            </div>
                          </div>
                          <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-6">
                            <label for="usr">Proveedor:</label>
                            <div class="input-group">
                              <span name="txtProveedorP" id="txtProveedorP"></span>
                            </div>
                          </div>
                          <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-6">
                            <label for="usr">Fecha de emisión:</label>
                            <div class="input-group">
                              <span name="txtFechaEmisionP" id="txtFechaEmisionP"></span>
                            </div>
                          </div>
                          <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-6">
                            <label for="usr">Fecha estimada de entrega:</label>
                            <div class="input-group">
                              <span name="txtFechaEstimadaP" id="txtFechaEstimadaP"></span>
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <label for="usr">Dirección de entrega:</label>
                            <div class="input-group">
                              <span name="txtDireccionP" id="txtDireccionP"></span>
                            </div>
                          </div>
                          <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <label for="usr">Notas:</label>
                            <div class="input-group">
                              <span name="NotasInternasP" id="NotasInternasP"></span>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group" >
                    <div class="row">
                      <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <span id="div9">
                        </span>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
            </div>
            <input id="entryTempIDD" type="hidden" value="0">
            <input id="entryTempIDTrasD" type="hidden" value="0">
            
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

  <script src="../../../../js/slimselect.min.js"></script>
  <script src="../../../../js/sb-admin-2.min.js"></script>
  <script src="../../../../js/scripts.js"></script>
</body>
<script src="../../../../js/Sortable.js"></script>
<script src="../../../../js/pagination/pagination.js"></script>
<script>
loadAlertsNoti('alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
setInterval(loadAlertsNoti, 10000, 'alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
</script>

</html>

<!--QUESITON MODAL SLIDE FULL INVOICE-->
<div class="modal fade" id="fullInvoice" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="" method="POST">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">¿La captura de la factura está completa?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true" class="text-light">x</span>
          </button>
        </div>
        <br>
        <input type="hidden" id="folio_entrada" name="folio_entrada">
        <input type="hidden" id="id_cuenta_pagar" name="id_cuenta_pagar">
        <input type="hidden" id="folio_doc" name="folio_doc">
        <input type="hidden" id="serie_doc" name="serie_doc">
        <div class="form-group col-md-12" style="text-align:center">
          <button class="btn-custom btn-custom--border-blue btnNoFullInvoice" type="button"
            data-dismiss="modal" onclick="descargarPDFEntrada();"><span class="ajusteProyecto">NO</span></button>
          <button type="button" data-dismiss="modal" onclick="descargarPDFFactura();" class="btn-custom btn-custom--blue"><span
              class="ajusteProyecto">SI</span></button>
        </div>
        <div class="modal-footer">

        </div>
      </form>
    </div>
  </div>
</div>

<!--DELETE MODAL SLIDE Producto-->
<div class="modal fade" id="eliminar_ProductoEnt" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="" method="POST">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">¿Desea eliminar el registro de este?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true" class="text-light">x</span>
          </button>
        </div>
        
        <div class="modal-body" style="font-size: 10 px!important; color: red;">Esta acción no podrá deshacerse</div>
        <div class="modal-footer">
          <button class="btn-custom btn-custom--border-blue btnCancelarActualizacion" type="button"
            data-dismiss="modal"><span class="ajusteProyecto">Cancelar</span></button>
          <button type="button" data-dismiss="modal" onclick="deleteLoteSerie($('#entryTempIDD').val())" class="btn-custom btn-custom--blue"><span
              class="ajusteProyecto" >Eliminar</span></button>
        </div>
      </form>
    </div>
  </div>
</div>

<!--DELETE MODAL SLIDE Producto-->
<div class="modal fade" id="eliminar_ProductoEntTras" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="" method="POST">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">¿Desea eliminar el registro de este?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true" class="text-light">x</span>
          </button>
        </div>
        
        <div class="modal-body" style="font-size: 10 px!important; color: red;">Esta acción no podrá deshacerse</div>
        <div class="modal-footer">
          <button class="btn-custom btn-custom--border-blue btnCancelarActualizacion" type="button"
            data-dismiss="modal"><span class="ajusteProyecto">Cancelar</span></button>
          <button type="button" onclick="deleteLoteSerieTras($('#entryTempIDTrasD').val())" class="btn-custom btn-custom--blue"><span
              class="ajusteProyecto" data-dismiss="modal">Eliminar</span></button>
        </div>
      </form>
    </div>
  </div>
</div>

<!--CLOSE MODAL SLIDE Orden Compra-->
<div class="modal fade" id="cerrar_OrdenCompra" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="" method="POST">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">¿Desea cerrar la orden de compra?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true" class="text-light">x</span>
          </button>
        </div>
        
        <div class="modal-body" style="font-size: 10 px!important; color: red;">Esta acción no podrá deshacerse, y ya no podrá darle entrada a ningún producto de la orden.</div>
        <div class="modal-footer">
          <button class="btn-custom btn-custom--border-blue btnCancelarActualizacion" type="button"
            data-dismiss="modal"><span class="ajusteProyecto">Cancelar</span></button>
          <button type="button" onclick="cerrar_OrdenCompra()" class="btn-custom btn-custom--blue"><span
              class="ajusteProyecto" data-dismiss="modal">Cerrar</span></button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="agregar_ProductoEDBranch" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="" method="POST" id="">
        <div class="modal-header">
          <h4 class="modal-title w-100" id="myModalLabel">Agregar producto</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">x</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="card">
            <div class="card-body">
              <div class="table-responsive">
                <table class="table" id="tblListadoProductosEDBranch" width="100%">
                  <thead>
                    <tr>
                      <th>Id</th>
                      <th>Clave</th>
                      <th>Producto</th>
                    </tr>
                  </thead>
                </table>
              </div> 
            </div>
          </div>
        </div>
        <div class="modal-footer justify-content-center">
          
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="agregar_ProductoEDProvider" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="" method="POST" id="">
        <div class="modal-header">
          <h4 class="modal-title w-100" id="myModalLabel">Agregar producto</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">x</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="card">
            <div class="card-body">
              <div class="table-responsive">
                <table class="table" id="tblListadoProductosEDProvider" width="100%">
                  <thead>
                    <tr>
                      <th>Id</th>
                      <th>Clave</th>
                      <th>Producto</th>
                    </tr>
                  </thead>
                </table>
              </div> 
            </div>
          </div>
        </div>
        <div class="modal-footer justify-content-center">
          
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="agregar_ProductoEDCustomer" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="" method="POST" id="">
        <div class="modal-header">
          <h4 class="modal-title w-100" id="myModalLabel">Agregar producto</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">x</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="card">
            <div class="card-body">
              <div class="table-responsive">
                <table class="table" id="tblListadoProductosEDCustomer" width="100%">
                  <thead>
                    <tr>
                      <th>Id</th>
                      <th>Clave</th>
                      <th>Producto</th>
                    </tr>
                  </thead>
                </table>
              </div> 
            </div>
          </div>
        </div>
        <div class="modal-footer justify-content-center">
          
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="eliminar_ProductoEntED" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="" method="POST">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">¿Desea eliminar el registro de este?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true" class="text-light">x</span>
          </button>
        </div>
        
        <div class="modal-body" style="font-size: 10 px!important; color: red;">Esta acción no podrá deshacerse</div>
        <div class="modal-footer">
          <button class="btn-custom btn-custom--border-blue btnCancelarActualizacion" type="button"
            data-dismiss="modal"><span class="ajusteProyecto">Cancelar</span></button>
          <button type="button" data-dismiss="modal" id="btndeleteLoteSerieED" class="btn-custom btn-custom--blue"><span
              class="ajusteProyecto">Eliminar</span></button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="eliminar_ProductoEntEDProvider" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="" method="POST">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">¿Desea eliminar el registro de este?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true" class="text-light">x</span>
          </button>
        </div>
        
        <div class="modal-body" style="font-size: 10 px!important; color: red;">Esta acción no podrá deshacerse</div>
        <div class="modal-footer">
          <button class="btn-custom btn-custom--border-blue btnCancelarActualizacion" type="button"
            data-dismiss="modal"><span class="ajusteProyecto">Cancelar</span></button>
          <button type="button" data-dismiss="modal" id="btndeleteLoteSerieEDProvider" class="btn-custom btn-custom--blue"><span
              class="ajusteProyecto">Eliminar</span></button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="eliminar_ProductoEntEDCustomer" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="" method="POST">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">¿Desea eliminar el registro de este?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true" class="text-light">x</span>
          </button>
        </div>
        
        <div class="modal-body" style="font-size: 10 px!important; color: red;">Esta acción no podrá deshacerse</div>
        <div class="modal-footer">
          <button class="btn-custom btn-custom--border-blue btnCancelarActualizacion" type="button"
            data-dismiss="modal"><span class="ajusteProyecto">Cancelar</span></button>
          <button type="button" data-dismiss="modal" id="btndeleteLoteSerieEDCustomer" class="btn-custom btn-custom--blue"><span
              class="ajusteProyecto">Eliminar</span></button>
        </div>
      </form>
    </div>
  </div>
</div>