<?php
session_start();
require_once '../../../../include/db-conn.php';
$user = $_SESSION["Usuario"];
$PKEmpresa = $_SESSION["IDEmpresa"];

$PKOrden = $_GET["oc"];

$stmt = $conn->prepare("SELECT empresa_id, FKEstatusOrden FROM ordenes_compra WHERE PKORdenCompra = :id");
$stmt->bindValue(':id', $PKOrden, PDO::PARAM_INT);
$stmt->execute();
$row = $stmt->fetch();

$GLOBALS["PKOrdenCompra"] = $row['empresa_id'];
$GLOBALS["PKEstatusOrden"] = $row['FKEstatusOrden'];

if (isset($_SESSION["Usuario"])) {
    require_once '../../../../include/db-conn.php';
    $user = $_SESSION["Usuario"];

    if ($GLOBALS["PKOrdenCompra"] != $PKEmpresa) {
        header("location:../../../inventarios_productos/catalogos/orden_compras/");
    }else if($GLOBALS["PKEstatusOrden"] != 1){
      header("location:../../../inventarios_productos/catalogos/orden_compras/comentarOrdenCompra.php?oc=".md5($PKOrden));
    }
} else {
    header("location:../../../dashboard.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <link rel="icon" type="image/png" href="../../../../img/header/bluTimlid.png">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Timlid | Editar Orden de compra</title>

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

  <!-- Page level custom scripts
    <script src="js/demo/datatables-demo.js"></script>
    -->

  <!-- Custom fonts for this template -->
  <link href="../../../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

  <!-- Custom styles for this template -->
  <link href="../../../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../../../css/slimselect.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../../style/agregar_entrada.css">
  <link rel="stylesheet" href="../../style/pestanas_producto.css">

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
  <script src="../../../../js/lobibox.min.js"></script>

  <script src="../../../../js/sweet/sweetalert2.js"></script>
  <link rel="stylesheet" href="../../../../css/sweetalert2.css">
  <link rel="stylesheet" href="../../../../css/notificaciones.css">
  <link rel="stylesheet" href="../../style/ordenesCompra.css">
  <link href="../../../../css/lobibox.min.css" rel="stylesheet">

  <script src="../../../../js/notificaciones_timlid.js" charset="utf-8"></script>
  <script src="../../js/ordenesCompra.js" charset="utf-8"></script>
  <script src="../../js/editar_ordenCompra.js" charset="utf-8"></script>
  <script src="../../../../js/numeral.min.js"></script>
  <style>
    .bar-title{
        background-color:#006dd9;
        color:white;
        padding:.75rem;
        font-size:18px;
    }
  </style>
</head>

<body id="page-top">
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

      <!-- Main Content -->
      <div id="content">
        <?php
$rutatb = "../../../";
$icono = '../../../../img/icons/ICONO ORDENES DE COMPRA-01.svg';
$titulo = 'Editar orden de compra';
$backIcon = true;
require_once $rutatb . 'topbar.php';
?>

        <input type="hidden" id="txtUsuario" value="<?=$_SESSION['PKUsuario'];?>">
        <input type="hidden" id="txtRuta" value="<?=$ruta;?>">
        <input type="hidden" id="txtEdit" value="<?=$ruteEdit;?>">
        <input type="hidden" id="txtPantalla" value="13">
        <input type="hidden" id="txtPKOrden" value="<?=$PKOrden?>">
        <input type="hidden" id="txtPKOrdenEncrip" value="">

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <div class="row">
            <div class="col-lg-12">

              <!-- Basic Card Example -->
              <div class="card shadow mb-4 nav-link" id="bodyUp">
                <div class="card-body">
                  <span id="alertas"> </span>
                  <div class="row">
                    <div class="col-lg-12">
                      <form action="" method="post" id="frmOrdenCompra">
                        <div class="row">
                          <div class="col-lg-12">
                            <p class="bar-title">Información de compra</p>
                          </div>
                        </div>  
                        <div class="row">
                          <div class="col-lg-4">
                            <div class="form-group">
                              <label for="usr">Referencia:*</label>
                              <input type="text" class="form-control alphaNumeric-only" maxlength="20"
                                name="txtReferencia" id="txtReferencia" required readonly>
                              <div class="invalid-feedback" id="invalid-referencia">El producto debe tener una
                                referencia.
                              </div>
                            </div>
                          </div>
                          <div class="col-lg-4">
                            <div class="form-group">
                              <label for="usr">Fecha de emision:*</label>
                              <input type="date" class="form-control" maxlength="20" id="txtFechaEmision" readonly
                                required>
                              <div class="invalid-feedback" id="invalid-emision">El producto debe tener una fecha de
                                emisión.
                              </div>
                            </div>
                          </div>
                          <div class="col-lg-4">
                            <div class="form-group">
                              <label for="usr">Fecha estimada de entrega:*</label>
                              <input type="date" class="form-control" maxlength="20" name="txtFechaEstimada"
                                id="txtFechaEstimada" required min="" onchange="validEmptyInput(this)">
                              <div class="invalid-feedback" id="invalid-fechaEst">El producto debe tener una
                                fecha estimada de entrega.
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="row">
                            <div class="col-lg-3">
                              <div class="form-group">
                                <label for="usr">Proveedor:*</label>
                                <select name="cmbProveedor" id="cmbProveedor" onchange="cambioProveedor()"
                                  disabled="true" required></select>
                                <div class="invalid-feedback" id="invalid-proveedor">El producto debe tener un
                                  proveedor.
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-3">
                              <div class="form-group">
                                <label for="cmbDireccionEnvio">Dirección de entrega:*</label>
                                <select name="cmbDireccionEnvio" id="cmbDireccionEnvio" required
                                  onchange="validEmptyInput(this)"></select>
                                <div class="invalid-feedback" id="invalid-sucursal">El producto debe tener una sucursal
                                  de entrega.</div>
                              </div>
                            </div>
                            <div class="col-lg-3">
                              <div class="form-group">
                                <label for="cmbComprador">Comprador:*</label>
                                <select name="cmbComprador" id="cmbComprador" required onchange="validEmptyInput(this)"></select>
                                <div class="invalid-feedback" id="invalid-comprador">El producto debe tener un comprador.</div>
                              </div>
                            </div>
                            <div class="col-lg-2">
                              <label for="cmbCondicionPago">Condición de pago:*</label>
                              <select name="cmbCondicionPago" id="cmbCondicionPago" required onchange="validEmptyInput(this)">
                              </select>
                              <div class="invalid-feedback" id="invalid-condicionPago">La orden de compra debe tener una condición de pago.</div>
                            </div>
                            <div class="col-lg-1">
                              <div class="form-group">
                                <label for="usr">Moneda:*</label>
                                <select name="cmbMoneda" id="cmbMoneda" required>
                                  <option value="0" disabled selected hidden>Seleccione una moneda...</option>
                                </select>
                                <div class="invalid-feedback" id="invalid-moneda">La orden de compra debe tener una Moneda.</div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-lg-4">
                                <label for="cmbCategoriaCuenta">Categoria:*</label>
                                <select class="form-select" name="cmbCategoriaCuenta" id="cmbCategoriaCuenta" aria-label="Default select example" required></select>
                                <input type="hidden" name="txtIdCategoria" id="txtIdCategoria">
                                <div class="invalid-feedback" id="invalid-categoriaCuenta">El campo es obligatorio.</div>
                            </div>
                            <div class="col-lg-4">
                                <label for="cmbCategoriaCuenta">Subcategoria:*</label>
                                <select class="form-select" name="cmbSubcategoriaCuenta" id="cmbSubcategoriaCuenta" required ></select>
                                <input type="hidden" name="txtIdSubcategoria" id="txtIdSubcategoria">
                                <div class="invalid-feedback" id="invalid-subcategoriaCuenta">El campo es obligatorio.</div>                        
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <p class="bar-title">Agregar productos o servicios</p>
                            </div>
                        </div>
                        <div class="row">
                          <div class="col-lg-4">
                            <div class="row">
                              <div class="col-lg-3">
                                <label for="usr">Producto:*</label>
                              </div>
                              <div class="col-lg-9">
                                <input type="checkbox" id="chkcmbTodoProducto" disabled="disabled"> <label for="">Cargar
                                  todos los productos</label>
                              </div>
                            </div>
                            <div class="form-group">
                              <select name="cmbProducto" id="cmbProducto" required
                                onchange="validEmptyInput(this)"></select>
                              <select name="cmbTodoProducto" id="cmbTodoProducto" style="display:none;"
                                onchange="validEmptyInput(this)"></select>
                              <div class="invalid-feedback" id="invalid-producto" required>El registro debe tener un
                                producto.</div>
                            </div>
                          </div>
                          <div class="col-lg-4">
                            <div class="form-group">
                              <label for="usr">Precio unitario:*</label>
                              <div class="input-group">
                                <div class="input-group-prepend">
                                  <span class="input-group-text">$</span>
                                </div>
                                <input type="text" class="form-control numericDecimal-only" maxlength="10"
                                  name="txtPrecioUnitario" id="txtPrecioUnitario" required
                                  onkeyup="validEmptyInput(this)">
                                <div class="invalid-feedback" id="invalid-precioUnit">El producto debe tener un
                                  precio unitario.</div>
                              </div>
                            </div>
                          </div>
                          <div class="col-lg-4">
                            <div class="form-group">
                              <label for="usr">Cantidad:*</label>
                              <input type="number" class="form-control numeric-only txtCantidad" maxlength="8"
                                name="txtCantidad" id="txtCantidad" required onkeyup="validEmptyInput(this)">
                              <input type="hidden" name="txtCantidadHis" id="txtCantidadHis">
                              <div class="invalid-feedback" id="invalid-cantidad">El producto debe tener una
                                cantidad.</div>
                            </div>
                          </div>
                          <input type="hidden" id="unidadMedida">
                          <input type="hidden" id="seleccionado" value="0">
                          <div class="col-lg-10" id="datosNew">
                            <span id="datosProve">
                            </span>
                          </div>
                          <div class="col-12 d-flex justify-content-between mt-3">
                            <label for="">* Campos requeridos</label>
                            <div class="">
                              <button class="btn-custom btn-custom--blue" type="button" id="agregarProducto"
                                name="agregarProducto" onclick="agregarProd()">Agregar producto</button>
                            </div>
                          </div>
                        </div>

                        <div class="form-group">
                          <!-- DataTales Example -->
                          <div class="mb-4">
                            <div class="">
                              <div class="table-responsive">
                                <table class="table" id="tblListadoOrdenesCompra" width="100%" cellspacing="0">
                                  <thead>
                                    <tr>
                                      <th>ID</th>
                                      <th>Clave/Producto</th>
                                      <th>Cantidad</th>
                                      <th>Unidad de medida</th>
                                      <th>Precio unitario</th>
                                      <th>Impuestos</th>
                                      <th>Importe</th>
                                    </tr>
                                  </thead>

                                </table>
                              </div>
                            </div>
                          </div>
                        </div>
                        <table class="table table-hover">
                          <tfoot>
                            <tr>
                              <th style="text-align: right;">Subtotal:</th>
                              <th style="text-align: right; width:400px!important">$ <span id="Subtotal">0.00</span>
                              </th>
                              <th style="width:60px;"></th>
                            </tr>
                            <tr>
                              <th style="text-align: right;">Impuestos:</th>
                              <th id="impuestos"></th>
                              <th></th>
                            </tr>
                            <tr class="total redondearAbajoIzq">
                              <th style="text-align: right;" class="redondearAbajoIzq">Total:</th>
                              <th style="text-align: right;">$ <span id="Total">0.00</span></th>
                              <th></th>
                            </tr>
                          </tfoot>
                        </table>

                        <div class="row">
                          <div class="col-lg-6">
                            <label for="usr">Notas visibles al proveedor</label>
                            <textarea class="form-control" cols="10" rows="3" name="NotasProveedor" id="NotasProveedor"
                              placeholder="Aquí puedes colocar la descripción de tu orden de compra o datos importantes dirigidos hacía tu proveedor"
                              maxlength="255"></textarea>
                          </div>
                          <div class="col-lg-6">
                            <label for="usr">Notas internas</label>
                            <textarea class="form-control" cols="10" rows="3" name="NotasInternas" id="NotasInternas"
                              placeholder="Aquí puedes colocar la descripción de tu cotización o datos importantes solo para uso interno"
                              maxlength="255"></textarea>
                          </div>
                        </div>
                        <br>
                        <label for="">* Campos requeridos</label>

                        <button type="button" class="btn-custom btn-custom--blue" name="btnAgregar" id="btnAgregar"
                          onclick="enviarOrdenCompra()" style="float:right">Guardar
                          orden
                          de compra</button>
                        <button type="button" class="btn-custom btn-custom--blue" name="btnCancelar" id="btnCancelar"
                          onclick="cancelarOrdenCompra(3)" style="float:right; margin-right:50px">
                          Cancelar
                          orden
                          de compra</button>

                        <button type="button" class="btn-custom btn-custom--blue" name="btnComentar" id="btnComentar"
                          onclick="comentarOrdenCompra()" style="float:right; margin-right:50px">
                          Comentar
                          orden
                          de compra</button>
                        </br></br>

                        <span id="modal_envio"></span>

                      </form>
                    </div>
                  </div>
                </div>
              </div>
              <!-- End Basic Card Example -->

            </div>
          </div>

        </div>
        <!-- End Page Content -->
      </div>

      <!-- Footer -->
      <?php
$rutaf = "../../../";
require_once $rutaf . 'footer.php';
?>
      <!-- End of Footer -->

    </div>
    <!-- End Content Wrapper -->



  </div>
  <!-- End Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>


  <script src="../../../../js/slimselect.min.js"></script>
  <script>
  loadAlertsNoti('alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
  setInterval(loadAlertsNoti, 10000, 'alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit')
    .val());
  </script>
</body>

</html>

<!--CANCEL MODAL SLIDE Orden Compra-->
<div class="modal fade" id="cancelar_OrdenCompra" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="" method="POST">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">¿Desea cancelar la orden de compra?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true" class="text-light">x</span>
          </button>
        </div>
        <input type="hidden" value="" id="valorId" name="valorId">
        <div class="modal-body" style="font-size: 10 px!important; color: red;">Al cancelar la Orden de compra se le notificará al proveedor de esta acción vía chat.</div>
        <div class="modal-footer">
          <button class="btn-custom btn-custom--border-blue btnCancelarActualizacion" type="button"
            data-dismiss="modal"><span class="ajusteProyecto">Cancelar</span></button>
          <button type="submit" onclick="cancelar_OC()" class="btn-custom btn-custom--blue"><span
              class="ajusteProyecto" data-dismiss="modal">Confirmar</span></button>
        </div>
      </form>
    </div>
  </div>
</div>