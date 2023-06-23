<?php
session_start();
require_once '../../../../include/db-conn.php';

$Producto = $_GET["p"];

if (isset($_SESSION["Usuario"])) {
  $user = $_SESSION["Usuario"];
  $PKEmpresa = $_SESSION["IDEmpresa"];

  $stmt = $conn->prepare("SELECT empresa_id FROM productos WHERE PKProducto = :id");
  $stmt->bindValue(':id', $Producto, PDO::PARAM_INT);
  $stmt->execute();
  $row = $stmt->fetch();

  $GLOBALS["PKProducto"] = $row['empresa_id'];

  if ($GLOBALS["PKProducto"] != $PKEmpresa) {
    header("location:../../../inventarios_productos/catalogos/productos/");
  }
} else {
  header("location:../../../dashboard");
}

$rutaServer = $_ENV['RUTA_ARCHIVOS_READ'].$PKEmpresa.'/img'.'/';
$rutaServer_t = $_ENV['RUTA_ARCHIVOS_READ'].$PKEmpresa.'/temp'.'/';
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="icon" type="image/png" href="../../../../img/header/bluTimlid.png">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Timlid | Editar producto</title>

  <!-- ESTILOS -->
  <link href="../../../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../../../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../../../css/slimselect.min.css" rel="stylesheet">
  <link href="../../style/agregar_entrada.css" rel="stylesheet">
  <link href="../../style/pestanas_producto.css" rel="stylesheet">
  <link href="../../../../css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="../../../../vendor/datatables/responsive.dataTables.css" rel="stylesheet">
  <link href="../../../../vendor/datatables/buttons.dataTables.css" rel="stylesheet">
  <link href="../../../../css/styles.css" rel="stylesheet">
  <link href="../../../../css/stylesNewTable.css" rel="stylesheet">
  <link href="../../../../css/croppie.css" rel="stylesheet">
  <link href="../../../../css/lobibox.min.css" rel="stylesheet">
  <link href="../../../../css/sweetalert2.css" rel="stylesheet">
  <link href="../../../../css/notificaciones.css" rel="stylesheet">
  <!-- JS -->
  <script src="../../../../vendor/jquery/jquery.min.js"></script>
  <script src="../../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../../../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../../../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../../../vendor/datatables/dataTables.responsive.js"></script>
  <script src="../../../../vendor/datatables/dataTables.buttons.js"></script>
  <script src="../../../../vendor/datatables/buttons.html5.min.js"></script>
  <script src="../../../../vendor/jszip/jszip.min.js"></script>
  <script src="../../../../js/lobibox.min.js"></script>
  <script src="../../../../js/numeral.min.js" charset="utf-8"></script>
  <script src="../../../../js/croppie.js"></script>
  <script src="../../../../js/sweet/sweetalert2.js"></script>

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

        <!-- Topbar -->
        <?php
        $rutatb = "../../../";
        $icono = 'ICONO-PRODUCTOS-SERVICIOS-AZUL.svg';
        $titulo = 'Editar producto';
        $backIcon = true;
        require_once $rutatb . 'topbar.php';
        ?>
        <!-- End of Topbar -->
        <input type="hidden" id="txtUsuario" value="<?= $_SESSION['PKUsuario']; ?>">
        <input type="hidden" id="txtRuta" value="<?= $ruta; ?>">
        <input type="hidden" id="txtEdit" value="<?= $ruteEdit; ?>">
        <input type="hidden" id="txtPKProducto" value="<?= $Producto; ?>">

        <!-- Begin Page Content -->
        <div class="container-fluid">
          <div class="row">
            <div class="col-lg-12">
              <ul class="nav nav-tabs">
                <li class="nav-item">
                  <a id="CargarEdicionDatosProducto" class="nav-link" onclick="CargarDatosProducto(window.location.href = 'editar_producto.php?p='+$('#txtPKProducto').val())">
                    Datos del producto
                  </a>
                </li>
                <li class="nav-item">
                  <a id="CargarEdicionDatosImpuestos" class="nav-link" onclick="SeguirDatosImpuestos($('#txtPKProducto').val())">
                    Información fiscal
                  </a>
                </li>
                <!-- <li class="nav-item">
                  <a id="CargarDatosInventario" class="nav-link" onclick="SeguirPestanaInventario($('#txtPKProducto').val())">
                    Inventario
                  </a>
                </li> -->
                <li class="nav-item" id="pestDatosProveedor">
                  <a id="CargarEdicionDatosProveedor" class="nav-link" onclick="SeguirTipoProveedor($('#txtPKProducto').val())">
                    Proveedor
                  </a>
                </li>
                <li class="nav-item" id="pestDatosVenta">
                  <a id="CargarEdicionTiposProducto" class="nav-link" onclick="SeguirDatosVenta($('#txtPKProducto').val())">
                    Datos de Venta
                  </a>
                </li>
              </ul>
              <input id="PKUsuario" value="<?php echo $_SESSION["PKUsuario"]; ?>" type="hidden">
              <input name="contadorCompuesto" id="contadorCompuesto" type="hidden" readonly value="0">

              <!-- Basic Card Example -->
              <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="datos" role="tabpanel" aria-labelledby="nav-main-tab">

                </div>
              </div>
            </div>
          </div>

          <!-- End Begin Page Content -->

        </div>
        <!-- End Main Content -->

        <!-- Footer -->
        <?php
        $rutaf = "../../../";
        require_once $rutaf . 'footer.php';
        ?>

      </div>
      <!-- End Content Wrapper -->

    </div>
    <!-- End Page Wrapper -->


    <!--ADD MODAL SLIDE CLAVES SAT-->
    <div class="modal fade right" id="agregar_ClaveSAT" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" onscroll="esElFinal()">
      <span id="cargarClaveSAT">
      </span>
      <input id="contadorClaveSAT" value="0" type="hidden">
      <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
      <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">
        <div class="modal-content">
          <form action="" method="POST">
            <!--<input type="hidden" name="idProyectoA" value="">-->
            <div class="modal-header">
              <h4 class="modal-title w-100" id="myModalLabel">Agregar Clave SAT</h4>
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
                        <input for="txtBuscarClave" class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6" value="Buscar:" style="text-align:right; border:none; background:transparent;" readonly>
                        <input type="text" class="form-control col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6" id="txtBuscarClave" name="txtBuscarClave" maxlength="255" onkeyup="buscando();">
                      </div>
                    </div>
                    <div class="table-responsive">
                      <table class="table" id="tblListadoClavesSAT" width="100%" cellspacing="0">
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
              <button type="button" class="btnesp first espCancelar btnCancelarActualizacionClave" data-dismiss="modal" id="btnCancelarActualizacionClave"><span class="ajusteProyecto">Cancelar</span></button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!--END ADD MODAL SLIDE CLAVES SAT-->

    <!--ADD MODAL SLIDE UNIDADES SAT-->
    <div class="modal fade right" id="agregar_UnidadSAT" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
              <button type="button" class="btnesp first espCancelar btnCancelarActualizacionUnidad" data-dismiss="modal" id="btnCancelarActualizacionUnidad"><span class="ajusteProyecto">Cancelar</span></button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!--END ADD MODAL SLIDE UNIDADES SAT-->

    <!--ADD MODAL SLIDE PRODUCTOS-->
    <div class="modal fade right" id="agregar_Producto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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

    <!-- UPLOAD MODAL IMAGE FILE -->
    <div id="uploadimageModal" class="modal" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog">
        <div class="modal-content" align="center">
          <div class="modal-header">
            <h4 class="modal-title">Carga y ajusta tu imagen</h4>
            <button type="button" class="close" data-dismiss="modal">x</button>
          </div>
          <div class="modal-body">
            <div class="row" align="center">
              <div class="col-md-12">
                <div id="image_demo" style="width:80%; margin-top:15px"></div>
              </div>
              <div class="col-md-12" style="padding-top:10px;">
                <button type="submit" class="btnesp espAgregar margin-auto crop_image" name="btnAgregarImagen" id="btnAgregarImagen"><span class="ajusteProyecto" data-dismiss="modal">Subir imagen</span></button>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btnesp first espCancelar btnCancelarAgregarImagen" data-dismiss="modal" id="btnCancelarAgregarImagen"><span class="ajusteProyecto">Cancelar</span></button>
          </div>
        </div>
      </div>
    </div>
    <!-- END UPLOAD MODAL IMAGE FILE -->

    <!--ADD MODAL SLIDE EDIT PROVEEDORES-->
    <div class="modal fade right" id="editar_Proveedor" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
      <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">
        <div class="modal-content">
          <form action="" method="POST" id="formProveedorEdit">
            <!--<input type="hidden" name="idProyectoA" value="">-->
            <div class="modal-header">
              <h4 class="modal-title w-100" id="myModalLabel">Editar Proveedor</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">x</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <!-- DataTales Example -->
                <div class="card mb-4">
                  <div class="">
                    <div class="form-group">
                      <div class="row">
                        <div class="col-lg-6">
                          <label for="usr">Proveedor:*</label>
                          <div class="row">
                            <div class="col-lg-12 input-group">
                              <select name="cmbProveedorProductoEdit" id="cmbProveedorProductoEdit" required="">
                              </select>
                              <div class="invalid-feedback" id="invalid-provEdit">El producto debe tener un proveedor.
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="row">
                        <div class="col-lg-6">
                          <label for="usr">Nombre del producto del proveedor:</label>
                          <div class="row">
                            <div class="col-lg-12 input-group">
                              <input class="form-control" type="text" name="txtNombreProdProveEdit" id="txtNombreProdProveEdit" maxlength="255" placeholder="Ej. Bata quirúgica desechable">
                              <img id="notaFNombreProdProveEdit" name="notaFNombreProdProveEdit" style="display: none;" src="../../../../img/timdesk/alerta.svg" width=30px title="Campo requerido" readonly>
                            </div>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <label for="usr">Clave del producto del proveedor:</label>
                          <div class="row">
                            <div class="col-lg-12 input-group">
                              <input type="hidden" name="txtClaveProdProveHisEdit" id="txtClaveProdProveHisEdit">
                              <input type="text" class="form-control" name="txtClaveProdProveEdit" id="txtClaveProdProveEdit" maxlength="50" placeholder="Ej. AA - 0001" onkeyup="escribirClaveProveedorEdit()">
                              <img id="notaClaveProdProveEdit" name="notaClaveProdProveEdit" style="display: none;" src="../../../../img/timdesk/alerta.svg" width=30px title="La clave ya existe para este proveedor, favor de anexar otra" readonly>
                              <img id="notaFClaveProdProveEdit" name="notaFClaveProdProveEdit" style="display: none;" src="../../../../img/timdesk/alerta.svg" width=30px title="Campo requerido" readonly>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="row">
                        <div class="col-lg-6">
                          <label for="usr">Precio:</label>
                          <div class="row">
                            <div class="col-lg-12 input-group">
                              <input class="form-control numericDecimal-only" type="text" name="txtPrecioProdProveEdit" id="txtPrecioProdProveEdit" required="" min="0" maxlength="13" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" placeholder="Ej. 30.00" onkeyup="validEmptyInput('txtPrecioProdProveEdit', 'invalid-precioProdEdit', 'El producto debe tener un precio.')">
                              <span class="input-group-addon" style="width:100px">
                                <select name="cmbMonedaPrecioEdit" id="cmbMonedaPrecioEdit" required="">
                                </select>
                              </span>
                              <div class="invalid-feedback" id="invalid-precioProdEdit">El producto debe tener un
                                precio.
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <label for="usr">Cantidad mínima de compra:</label>
                          <div class="row">
                            <div class="col-lg-12 input-group">
                              <input class="form-control numeric-only" type="text" name="txtCantMinProdProveEdit" id="txtCantMinProdProveEdit" min="0" maxlength="12" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" placeholder="Ej. 1000">
                              <img id="notaFCantMinProdProveEdit" name="notaFCantMinProdProveEdit" style="display: none;" src="../../../../img/timdesk/alerta.svg" width=30px title="Campo requerido" readonly>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="row">
                        <div class="col-lg-6">
                          <label for="usr">Días de entrega:</label>
                          <div class="row">
                            <div class="col-lg-12 input-group">
                              <input class="form-control numeric-only" type="text" name="txtDiasEntregProdProveEdit" id="txtDiasEntregProdProveEdit" min="0" maxlength="3" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" placeholder="Ej. 15">
                              <img id="notaFDiasEntregProdProveEdit" name="notaFDiasEntregProdProveEdit" style="display: none;" src="../../../../img/timdesk/alerta.svg" width=30px title="Campo requerido" readonly>
                            </div>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <label for="usr">Unidad de medida:</label>
                          <div class="row">
                            <div class="col-lg-12 input-group">
                              <span class="input-group-addon" style="width:100%">
                                <input class="form-control" type="text" name="txtUnidadMedidaEdit" id="txtUnidadMedidaEdit" min="0" maxlength="50" placeholder="Ej. Caja de 12 piezas">
                              </span>
                              <img id="notaFUnidadMProveedorEdit" name="notaFUnidadMProveedorEdit" style="display: none;" src="../../../../img/timdesk/alerta.svg" width=30px title="Campo requerido" readonly>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer justify-content-center">
              <button type="button" class="btn-custom btn-custom--blue" data-dismiss="modal" data-toggle="modal" data-target="#eliminar_Proveedor">Eliminar
              </button>
              <button type="button" class="btn-custom btn-custom--border-blue" data-dismiss="modal" id="">
                Cancelar
              </button>
              <button type="button" class="btn-custom btn-custom--blue" onclick="validarProveedorEdit($('#txtPKProductoProveedorEdit').val())">Guardar cambios
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!--END ADD MODAL SLIDE EDIT PROVEEDORES-->

    <!--DELETE MODAL SLIDE PROVEEDORES-->
    <div class="modal fade" id="eliminar_Proveedor" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">

          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">¿Desea eliminar el registro de este?</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">x</span>
            </button>
          </div>
          <div>
            <br>
            <label for="usr" style="margin-left: 80px!important;">Se eliminará el proveedor con los siguientes
              datos:</label>
          </div>
          <div class="row" style="margin-left: 80px!important;">
            <div class="form-group col-md-2">
              <label for="usr">Nombre:</label>
            </div>
            <div class="form-group col-md-10">
              <input type="text" style="border:none!important; background-color: transparent!important;" class="form-control" maxlength="50" id="txtNombreD" name="txtNombreD" placeholder="" required readonly>
            </div>
          </div>
          <div class="modal-body" style="font-size: 10 px!important; color: red;">Esta acción no podrá deshacerse</div>
          <div class="modal-footer">
            <button class="btnesp first espCancelar btnCancelarActualizacion" type="button" data-dismiss="modal"><span class="ajusteProyecto">Cancelar</span></button>
            <button type="submit" class="btnesp espAgregar float-right" data-dismiss="modal"><span class="ajusteProyecto" onclick="eliminarProveedor($('#txtPKProductoProveedorEdit').val())">Eliminar</span></button>
          </div>

        </div>
      </div>
    </div>
    <!--END DELETE MODAL SLIDE PROVEEDORES-->

    <!-- ADD MODAL EDIT COSTO PRODUCTO CLIENTE -->
    <div class="modal fade right" id="editar_Producto_costo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-full-height modal-right modal-md" role="document">
        <div class="modal-content">
          <form action="" id="EditarProductoCliente" method="POST">
            <div class="modal-header">
              <h4 class="modal-title w-100" id="myModalLabel">Editar costo especial</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">x</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label for="txtProducto">Producto:</label>
                <div name="txtProducto" id="txtProducto"></div>
              </div>
              <div class="form-group">
                <label for="usr">Cliente:*</label>
                <select name="cmbCliente_edit" id="cmbCliente_edit" class="readEditPermissions" tabindex="-1" required>
                </select>
                <div class="invalid-feedback" id="invalid-cliente_edit">El producto debe tener una moneda.</div>
                <input type="hidden" name="txthideidCliente" id="txthideidCliente">
              </div>
              <div class="form-group">
                <label for="usr">Costo especial:*</label>
                <input class="form-control numericDecimal-only readEditPermissions" type="text" name="txtCostoEspecialVenta_modalEdit" id="txtCostoEspecialVenta_modalEdit" maxlength="13" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" placeholder="Ej. 10.00" onkeyup="validEmptyInput('txtCostoEspecialVenta_modalEdit', 'invalid-costoProd_edit', 'El producto debe tener un costo.')" required>            
                <div class="invalid-feedback" id="invalid-costoProd_edit">El producto debe tener un costo.</div>
              </div>
              <div class="form-group">
                <label for="usr">Moneda:*</label>
                <select name="cmbMoneda_edit" id="cmbMoneda_edit" class="readEditPermissions" tabindex="-1" required>
                  <option data-placeholder="true"></option>
                  <option value="49">EUR</option>
                  <option value="100">MXN</option>
                  <option value="149">USD</option>
                </select>
                <div class="invalid-feedback" id="invalid-moneda_edit">El producto debe tener una moneda.</div>
              </div>
              <br><br>
              <div>
                <label for="usr">Campos requeridos *</label>
              </div>
              <br><br>
            </div>
            <div class="modal-footer justify-content-center">
              <button type="button" class="btnesp espAgregar" data-dismiss="modal" data-toggle="modal" data-target="#eliminar_Producto"><span class="ajusteProyecto">Eliminar</span></button>
              <button type="button" class="btnesp first espCancelar btnCancelarActualizacion" data-dismiss="modal"><span class="ajusteProyecto">Cancelar</span></button>
              <button type="button" class="btnesp espAgregar float-right" data-dismiss="modal" name="btnEditarCosto" id="btnEditarCosto"><span class="ajusteProyecto">Guardar</span></button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- END MODAL EDIT COSTO PRODUCTO CLIENTE -->

    <!--DELETE MODAL SLIDE PRODUCTOS -->
    <div class="modal fade" id="eliminar_Producto" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">

          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">¿Desea eliminar el registro de este?</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">x</span>
            </button>
          </div>
          <div>
            <input type="hidden" name="txthideidproduct" id="txthideidproduct">
            <input type="hidden" name="txthideidproduct_PK" id="txthideidproduct_PK">
            <br>
          </div>
          
          <div class="modal-body" style="font-size: 10 px!important; color: red;">Esta acción no podrá deshacerse</div>
          <div class="modal-footer">
            <button class="btnesp first espCancelar btnCancelarActualizacion" type="button" data-dismiss="modal" id="btnCancelarCuenta"><span class="ajusteProyecto">Cancelar</span></button>
            <button type="submit" class="btnesp espAgregar float-right" data-dismiss="modal"><span class="ajusteProyecto" onclick="eliminarProducto($('#txthideidproduct').val())">Eliminar</span></button>
          </div>

        </div>
      </div>
    </div>
    <!--END DELETE MODAL SLIDE PRODUCTOS -->

    <!--DELETE MODAL SLIDE IMPUESTO PRODUCTOS -->
    <div class="modal fade" id="eliminar_ImpuestoProducto" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">

          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">¿Desea eliminar este impuesto del producto?</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">x</span>
            </button>
          </div>
          <div>
            <input type="hidden" name="txthideidInpuesto" id="txthideidInpuesto">
            <br>
          </div>
          
          <div class="modal-body" style="font-size: 10 px!important; color: red;">Esta acción no podrá deshacerse</div>
          <div class="modal-footer">
            <button class="btnesp first espCancelar btnCancelarActualizacion" type="button" data-dismiss="modal" id="btnCancelarCuenta"><span class="ajusteProyecto">Cancelar</span></button>
            <button type="submit" class="btnesp espAgregar float-right" data-dismiss="modal"><span class="ajusteProyecto" onclick="eliminarImpuesto($('#txthideidInpuesto').val())">Eliminar</span></button>
          </div>

        </div>
      </div>
    </div>
    <!--END DELETE MODAL SLIDE IMPUESTO PRODUCTOS -->

    <script src="../../../../js/sb-admin-2.min.js"></script>
    <script src="../../../../js/scripts.js"></script>
    <script src="../../../../js/notificaciones_timlid.js" charset="utf-8"></script>
    <script src="../../js/editar_productos.js" charset="utf-8"></script>
    <script src="../../js/clavesSAT.js" charset="utf-8"></script>
    <script src="../../js/unidadesSAT.js" charset="utf-8"></script>
    <script src="../../js/impuestos_producto.js" charset="utf-8"></script>
    <script src="../../js/acciones_producto.js" charset="utf-8"></script>
    <script src="../../js/lista_combo_productos.js" charset="utf-8"></script>
    <script src="../../js/proveedores.js" charset="utf-8"></script>
    <script src="../../js/clientes.js" charset="utf-8"></script>
    <script src="../../../../js/slimselect.min.js"></script>
    <!-- Custom scripts for all pages-->
    <script src="../../../../js/pestanas_productosEdit.js"></script>
    <script src="../../../../js/validaciones.js"></script>
    <script>
      loadAlertsNoti('alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
      setInterval(loadAlertsNoti, 10000, 'alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit')
        .val());
    </script>
    <script type="text/javascript">
      CargarDatosProducto($("#txtPKProducto").val());
      _global.rutaServer = '<?php echo $rutaServer ?>'; 
      _global.rutaServer_t = '<?php echo $rutaServer_t ?>'; 
    </script>
</body>

</html>