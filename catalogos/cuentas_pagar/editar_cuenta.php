<?php
session_start();
require_once '../../include/db-connProduccion.php';
/* Optenemos el id que se mando */
$Producto = $_GET["id"];

if (isset($_SESSION["Usuario"])) {
    $user = $_SESSION["Usuario"];
    $PKEmpresa = $_SESSION["IDEmpresa"];

    $stmt = $conn->prepare("SELECT empresa_id FROM productos WHERE PKProducto = :id");
    $stmt->bindValue(':id', $Producto, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch();

    $id = intval($_GET['id']);

    $GLOBALS["PKProducto"] = $row['empresa_id'];
/* Ahora me esta redireccionando a po */
    if ($GLOBALS["PKProducto"] != $PKEmpresa) {
        header("location:../inventarios_productos/catalogos/productos/");
    }
} else {
    header("location:../../../dashboard");
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <link rel="icon" type="image/png" href="../../img/header/bluTimlid.png">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Timlid | Editar producto</title>

  <!-- Bootstrap core JavaScript-->
  <script src="../../vendor/jquery/jquery.min.js"></script>
  <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Page level plugins -->
  <script src="../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../vendor/datatables/dataTables.bootstrap4.min.js"></script>
  <script src="../../vendor/datatables/dataTables.responsive.js"></script>
  <script src="../../vendor/datatables/dataTables.buttons.js"></script>
  <script src="../../vendor/datatables/buttons.bootstrap4.min.js"></script>
  <script src="../../vendor/datatables/buttons.html5.min.js"></script>
  <script src="../../vendor/jszip/jszip.min.js"></script>
  <script src="../../js/lobibox.min.js"></script>

  <!-- Page level custom scripts
    <script src="js/demo/datatables-demo.js"></script>
    -->

  <!-- Custom fonts for this template -->
  <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

  <!-- Custom styles for this template -->
  <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../css/slimselect.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../../style/agregar_entrada.css">
  <link rel="stylesheet" href="../../style/pestanas_producto.css">

  <!-- Custom scripts for all pages-->

  <script src="../../js/sb-admin-2.min.js"></script>
  <script src="../../js/scripts.js"></script>

  <!-- Custom styles for this page -->
  <link href="../../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
  <link href="../../vendor/datatables/responsive.dataTables.css" rel="stylesheet">
  <link rel="stylesheet" href="../../vendor/datatables/buttons.dataTables.css">
  <link href="../../vendor/datatables/buttons.bootstrap4.min.css" rel="stylesheet">
  <link href="../../css/stylesTable.css" rel="stylesheet">
  <link href="../../css/styles.css" rel="stylesheet">
  <link rel="stylesheet" href="../../css/croppie.css" />
  <link href="../../css/lobibox.min.css" rel="stylesheet">

  <script src="../../js/numeral.min.js" charset="utf-8"></script>
  <script src="../../js/croppie.js"></script>

  <script src="../../js/sweet/sweetalert2.js"></script>
  <link rel="stylesheet" href="../../css/sweetalert2.css">
  <link rel="stylesheet" href="../../css/notificaciones.css">

  <script src="../../js/notificaciones_timlid.js" charset="utf-8"></script>
  <script src="../../js/editar_productos.js" charset="utf-8"></script>
  <script src="../../js/clavesSAT.js" charset="utf-8"></script>
  <script src="../../js/unidadesSAT.js" charset="utf-8"></script>
  <script src="../../js/impuestos_producto.js" charset="utf-8"></script>
  <script src="../../js/acciones_producto.js" charset="utf-8"></script>
  <script src="../../js/lista_combo_productos.js" charset="utf-8"></script>
  <script src="../../js/proveedores.js" charset="utf-8"></script>
  <script src="../../js/clientes.js" charset="utf-8"></script>

</head>

<body id="page-top">
  <!-- Imagen de fondo de cargando datos
  <div style="position: fixed;
                  left: 0px;
                  top: 0px;
                  width: 100%;
                  height: 100%;
                  z-index: 9999;
                  background: url('../../img/timdesk/Preloader.gif') 50% 50% no-repeat rgb(249,249,249);
                  opacity: .6;" id="loader">
  </div>-->
  <!-- Page Wrapper -->

  <div id="wrapper">

    <!-- Sidebar -->
    <?php
$ruta = "../";
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
$rutatb = "../";
$icono = '../../img/icons/ICONO PRODUCTO-01.svg';
$titulo = 'Editar producto';
require_once $rutatb . 'topbar.php';
?>
        <!-- End of Topbar -->
        <input type="hidden" id="txtUsuario" value="<?=$_SESSION['PKUsuario'];?>">
        <input type="hidden" id="txtRuta" value="<?=$ruta;?>">
        <input type="hidden" id="txtEdit" value="<?=$ruteEdit;?>">
        <input type="hidden" id="txtPKProducto" value="<?=$Producto;?>">

        <!-- Begin Page Content -->
        <div class="container-fluid">
          <div class="row">
            <div class="col-lg-12">
              <ul class="nav nav-tabs">
                <li class="nav-item">
                  <a id="CargarEdicionDatosProducto" class="nav-link"
                    onclick="CargarDatosProducto(window.location.href = 'editar_producto?p='+$('#txtPKProducto').val())">
                    Datos del producto
                  </a>
                </li>
                <li class="nav-item">
                  <a id="CargarEdicionDatosImpuestos" class="nav-link"
                    onclick="SeguirDatosImpuestos($('#txtPKProducto').val())">
                    Información fiscal
                  </a>
                </li>
                <li class="nav-item">
                  <a id="CargarEdicionDatosProveedor" class="nav-link"
                    onclick="SeguirTipoProveedor($('#txtPKProducto').val())">
                    Proveedor
                  </a>
                </li>
                <li class="nav-item">
                  <a id="CargarEdicionDatosInventario" class="nav-link"
                    onclick="SeguirInventario($('#txtPKProducto').val())">
                    Inventario
                  </a>
                </li>
                <li class="nav-item" id="pestDatosVenta">
                  <a id="CargarEdicionTiposProducto" class="nav-link"
                    onclick="SeguirDatosVenta($('#txtPKProducto').val())">
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
$rutaf = "../";
require_once $rutaf . 'footer.php';
?>

      </div>
      <!-- End Content Wrapper -->

    </div>
    <!-- End Page Wrapper -->


    <!--ADD MODAL SLIDE CLAVES SAT-->
    <div class="modal fade right" id="agregar_ClaveSAT" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
      aria-hidden="true">
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

                    <!--<div id="infinite_scroll">
                    <table class="table" width="100%" cellspacing="0">
                    <thead>
                      <tr>
                        <th>Id</th>
                        <th>Clave</th>
                      </tr>
                    </thead>-->


                    <?php
/*$stmt = $conn->prepare('call spc_ClaveSAT_Consulta()');
$stmt->execute();
$rowTipoProducto = $stmt->fetchAll();
foreach ($rowTipoProducto as $rtp) {      */
?>
                    <!-- <tr id="lista">
                        <strong>
                          <?php echo '<td>' . $rtp["clave"] . "</td><td>" . $rtp["descripcion"] . '</td>'; ?>
                        </strong>
                      </tr>-->
                    <?php
/*}*/
?>

                    <!--</div> -->
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer justify-content-center">
              <button type="button" class="btnesp first espCancelar btnCancelarActualizacionClave" data-dismiss="modal"
                id="btnCancelarActualizacionClave"><span class="ajusteProyecto">Cancelar</span></button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!--END ADD MODAL SLIDE CLAVES SAT-->

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

    <!--ADD MODAL SLIDE PRODUCTOS-->
    <div class="modal fade right" id="agregar_Producto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
      aria-hidden="true">
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
              <button type="button" class="btnesp first espCancelar btnCancelarActualizacionProductos"
                data-dismiss="modal" id="btnCancelarActualizacionProductos"><span
                  class="ajusteProyecto">Cancelar</span></button>
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
                <button type="submit" class="btnesp espAgregar margin-auto crop_image" name="btnAgregarImagen"
                  id="btnAgregarImagen"><span class="ajusteProyecto" data-dismiss="modal">Subir imagen</span></button>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btnesp first espCancelar btnCancelarAgregarImagen" data-dismiss="modal"
              id="btnCancelarAgregarImagen"><span class="ajusteProyecto">Cancelar</span></button>
          </div>
        </div>
      </div>
    </div>
    <!-- END UPLOAD MODAL IMAGE FILE -->

    <!--ADD MODAL SLIDE EDIT PROVEEDORES-->
    <div class="modal fade right" id="editar_Proveedor" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
      aria-hidden="true">
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
                              <span class="input-group-addon" style="width:100%">
                                <select name="cmbProveedorProductoEdit" id="cmbProveedorProductoEdit" required="">
                                </select>
                              </span>
                              <img id="notaFProveedorProductoEdit" name="notaFProveedorProductoEdit"
                                style="display: none;" src="../../img/timdesk/alerta.svg" width=30px
                                title="Campo requerido" readonly>
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
                              <input class="form-control alphaNumeric-only" type="text" name="txtNombreProdProveEdit"
                                id="txtNombreProdProveEdit" maxlength="100" placeholder="Ej. Bata quirúgica desechable">
                              <img id="notaFNombreProdProveEdit" name="notaFNombreProdProveEdit" style="display: none;"
                                src="../../img/timdesk/alerta.svg" width=30px title="Campo requerido" readonly>
                            </div>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <label for="usr">Clave del producto del proveedor:</label>
                          <div class="row">
                            <div class="col-lg-12 input-group">
                              <input type="hidden" name="txtClaveProdProveHisEdit" id="txtClaveProdProveHisEdit">
                              <input type="text" maxlength="50" class="form-control alphaNumeric-only"
                                name="txtClaveProdProveEdit" id="txtClaveProdProveEdit" maxlength="50"
                                placeholder="Ej. AA - 0001" onkeyup="escribirClaveProveedorEdit()">
                              <img id="notaClaveProdProveEdit" name="notaClaveProdProveEdit" style="display: none;"
                                src="../../img/timdesk/alerta.svg" width=30px
                                title="La clave ya existe para este proveedor, favor de anexar otra" readonly>
                              <img id="notaFClaveProdProveEdit" name="notaFClaveProdProveEdit" style="display: none;"
                                src="../../img/timdesk/alerta.svg" width=30px title="Campo requerido" readonly>
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
                              <input class="form-control numeric-only" type="text" name="txtPrecioProdProveEdit"
                                id="txtPrecioProdProveEdit" required="" min="0" placeholder="Ej. 30.00"
                                onkeyup="validEmptyInput('txtPrecioProdProveEdit', 'invalid-precioProdEdit', 'El producto debe tener un precio.')">
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
                              <input class="form-control numeric-only" type="text" name="txtCantMinProdProveEdit"
                                id="txtCantMinProdProveEdit" min="0" placeholder="Ej. 1000">
                              <img id="notaFCantMinProdProveEdit" name="notaFCantMinProdProveEdit"
                                style="display: none;" src="../../img/timdesk/alerta.svg" width=30px
                                title="Campo requerido" readonly>
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
                              <input class="form-control numeric-only" type="text" name="txtDiasEntregProdProveEdit"
                                id="txtDiasEntregProdProveEdit" min="0" placeholder="Ej. 15">
                              <img id="notaFDiasEntregProdProveEdit" name="notaFDiasEntregProdProveEdit"
                                style="display: none;" src="../../img/timdesk/alerta.svg" width=30px
                                title="Campo requerido" readonly>
                            </div>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <label for="usr">Unidad de medida:</label>
                          <div class="row">
                            <div class="col-lg-12 input-group">
                              <span class="input-group-addon" style="width:100%">
                                <input class="form-control" type="text" name="txtUnidadMedidaEdit"
                                  id="txtUnidadMedidaEdit" min="0" placeholder="Ej. Caja de 12 piezas">
                              </span>
                              <img id="notaFUnidadMProveedorEdit" name="notaFUnidadMProveedorEdit"
                                style="display: none;" src="../../img/timdesk/alerta.svg" width=30px
                                title="Campo requerido" readonly>
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
              <button type="button" class="btn-custom btn-custom--blue" data-dismiss="modal" data-toggle="modal"
                data-target="#eliminar_Proveedor">Eliminar
              </button>
              <button type="button" class="btn-custom btn-custom--border-blue" data-dismiss="modal" id="">
                Cancelar
              </button>
              <button type="button" class="btn-custom btn-custom--blue"
                onclick="validarProveedorEdit($('#txtPKProductoProveedorEdit').val())">Guardar cambios
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!--END ADD MODAL SLIDE EDIT PROVEEDORES-->

    <!--DELETE MODAL SLIDE PROVEEDORES-->
    <div class="modal fade" id="eliminar_Proveedor" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
      aria-hidden="true">
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
              <input type="text" style="border:none!important; background-color: transparent!important;"
                class="form-control" maxlength="50" id="txtNombreD" name="txtNombreD" placeholder="" required readonly>
            </div>
          </div>
          <div class="modal-body" style="font-size: 10 px!important; color: red;">Esta acción no podrá deshacerse</div>
          <div class="modal-footer">
            <button class="btnesp first espCancelar btnCancelarActualizacion" type="button" data-dismiss="modal"><span
                class="ajusteProyecto">Cancelar</span></button>
            <button type="submit" class="btnesp espAgregar float-right" data-dismiss="modal"><span
                class="ajusteProyecto"
                onclick="eliminarProveedor($('#txtPKProductoProveedorEdit').val())">Eliminar</span></button>
          </div>

        </div>
      </div>
    </div>
    <!--END DELETE MODAL SLIDE PROVEEDORES-->

    <script src="../../js/slimselect.min.js"></script>
    <!-- Custom scripts for all pages-->

    <script src="../../js/pestanas_productosEdit.js"></script>
    <script src="../../js/validaciones.js"></script>
    <script>
    loadAlertsNoti('alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
    setInterval(loadAlertsNoti, 10000, 'alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit')
      .val());
    </script>
    <script type="text/javascript">
    CargarDatosProducto($("#txtPKProducto").val());
    </script>

    <script>
    /*new SlimSelect({
      select: '#cmbClaveSAT',
      deselectLabel: '<span class="">✖</span>',
    });*/


    /*var loadFile = function(event) {
      var reader = new FileReader();
      reader.onload = function(){
        var output = document.getElementById('imgProd');
        output.src = reader.result;
      };
      reader.readAsDataURL(event.target.files[0]);
    };*/
    </script>
</body>

</html>