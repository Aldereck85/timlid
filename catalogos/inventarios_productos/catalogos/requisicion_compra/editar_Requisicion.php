<?php
session_start();
$jwt_ruta = "../../../../";
require_once '../../../jwt.php';

date_default_timezone_set('America/Mexico_City');

$token = $_SESSION['token_ld10d'];

if (isset($_SESSION["Usuario"])) {
  require_once '../../../../include/db-conn.php';
  $user = $_SESSION["Usuario"];
} else {
  header("location:../../../dashboard.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="icon" type="image/png" href="../../../../img/header/bluTimlid.png">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Timlid | Editar Requisición de compra</title>

  <!-- ESTILOS -->
  <link href="../../../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../../../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../../../css/slimselect.min.css" rel="stylesheet">
  <link href="../../style/agregar_entrada.css" rel="stylesheet">
  <link href="../../style/pestanas_producto.css" rel="stylesheet">
  <link href="../../../../css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="../../../../vendor/datatables/responsive.dataTables.css" rel="stylesheet">
  <link href="../../../../vendor/datatables/buttons.dataTables.css" rel="stylesheet">
  <link href="../../../../css/stylesNewTable.css" rel="stylesheet">
  <link href="../../../../css/styles.css" rel="stylesheet">
  <link href="../../../../css/croppie.css" rel="stylesheet">
  <link href="../../../../css/sweetalert2.css" rel="stylesheet">
  <link href="../../../../css/notificaciones.css" rel="stylesheet">
  <link href="../../../../css/lobibox.min.css" rel="stylesheet">
  <link href="css/disabled.css" rel="stylesheet">
  <script src="../../../../vendor/jquery/jquery.min.js"></script>
  <script src="../../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../../../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../../../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../../../vendor/datatables/dataTables.responsive.js"></script>
  <script src="../../../../vendor/datatables/dataTables.buttons.js"></script>
  <script src="../../../../vendor/datatables/buttons.html5.min.js"></script>
  <script src="../../../../vendor/jszip/jszip.min.js"></script>
  <script src="../../../../js/numeral.min.js" charset="utf-8"></script>
  <script src="../../../../js/croppie.js"></script>
  <script src="../../../../js/sweet/sweetalert2.js"></script>
  <style>
    .bar-title{
        background-color:#006dd9;
        color:white;
        padding:0.75rem;
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
        $titulo = 'Editar requisición de compra';
        $backIcon = true;
        require_once $rutatb . 'topbar.php';
        ?>

        <input type="hidden" id="txtUsuario" value="<?= $_SESSION['PKUsuario']; ?>">
        <input type="hidden" id="txtRuta" value="<?= $ruta; ?>">
        <input type="hidden" id="txtEdit" value="<?= $ruteEdit; ?>">
        <input type="hidden" id="inpt_idRequisicion" value="<?= $_REQUEST['idRequisicion']; ?>">

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
                      <form action="" method="post" id="frmRequisicionCompraEdit">
                       <div class="row">
                            <div class="col-lg-12">
                                    <p class="bar-title">Información de compra</p>
                            </div>
                        </div> 
                        <div class="row">
                          <div class="col-lg-2">
                            <div class="form-group">
                              <label for="usr">Fecha de emision:*</label>
                              <input type="date" class="form-control" maxlength="20" id="txtFechaEmision" readonly required>
                              <div class="invalid-feedback" id="invalid-emision">La requisición debe tener una fecha de
                                emisión.
                              </div>
                            </div>
                          </div>
                          <div class="col-lg-3">
                            <div class="form-group">
                              <label for="usr">Fecha estimada de entrega:*</label>
                              <input type="date" class="form-control" maxlength="20" name="txtFechaEstimada" id="txtFechaEstimada" required onchange="validEmptyInput(this)">
                              <div class="invalid-feedback" id="invalid-fechaEst">La requisición debe tener una
                                fecha estimada de entrega.
                              </div>
                            </div>
                          </div>
                          <div class="col-lg-2">
                            <div class="form-group">
                              <label for="cmbDireccionEnvio">Sucursal de entrega:*</label>
                              <select name="cmbDireccionEnvio" id="cmbDireccionEnvio" required onchange="validEmptyInput(this)"></select>
                              <div class="invalid-feedback" id="invalid-sucursal">La Requisición debe tener una sucursal
                                de entrega.</div>
                            </div>
                          </div>
                          <div class="col-lg-2">
                            <label for="cmbArea">Area/Departamento:*</label>
                            <select name="cmbArea" id="cmbArea" onchange="validEmptyInput(this)" required></select>
                            <div class="invalid-feedback" id="invalid-cmbArea">La Requisición de compra debe tener una area/departamento.</div>
                          </div>
                          <div class="col-lg-3">
                            <label for="cmbEmpleado">Empleado:*</label>
                            <select name="cmbEmpleado" id="cmbEmpleado" required onchange="validEmptyInput(this)"></select>
                            <div class="invalid-feedback" id="invalid-empleado">La Requisición de compra debe tener un empleado solicitante.</div>
                          </div>
                        </div>
                        <br>
                        <div class="row">
                          <div class="col-lg-3">
                            <div class="form-group" id="divProvee">
                              <label for="usr">Proveedor:</label>
                              <select name="cmbProveedor" id="cmbProveedor" onchange="cambioProveedor(this)"></select>
                            </div>
                          </div>
                          
                          <div class="col-lg-3">
                            <div class="form-group">
                              <label for="cmbComprador">Comprador:*</label>
                              <select name="cmbComprador" id="cmbComprador" required onchange="validEmptyInput(this)"></select>
                              <div class="invalid-feedback" id="invalid-comprador">La Requisición debe tener un comprador.</div>
                            </div>
                          </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-lg-12">
                                <p class="bar-title">Agregar productos o servicios</p>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                          <div class="col-lg-4">
                            <div class="row">
                              <div class="col-lg-3">
                                <label for="usr">Producto:*</label>
                              </div>
                              <div id="checkAllProd" class="col-lg-9" style="display:none">
                                <input type="checkbox" id="chkcmbTodoProducto">
                                <label for="">Cargar todos los productos</label>
                              </div>
                            </div>
                            <div class="form-group">
                              <select name="cmbProducto" id="cmbProducto" required></select>
                              <div class="invalid-feedback" id="invalid-producto">La Requisición debe tener un
                                producto.</div>
                            </div>
                          </div>
                          <div class="col-lg-2">
                            <div class="form-group">
                              <label for="txtCantidad">Cantidad:*</label>
                              <input type="text" class="form-control numeric-only txtCantidad" maxlength="8" name="txtCantidad" id="txtCantidad" required="" placeholder="Ingrese una cantidad" onchange="validaCantidad(this,0), validEmptyInput(this)" >                              
                              <input type="hidden" name="txtCantidadHis" id="txtCantidadHis">
                              <div class="invalid-feedback" id="invalid-cantidad">El producto debe tener una cantidad.</div>
                            </div>
                          </div>
                        </div>
                        <div class="col-lg-10" id="datosNew">
                            <span id="datosProve">
                            </span>
                        </div>
                        <div class="row">
                          <div class="col-12 d-flex justify-content-between mt-3">
                            <label for="">* Campos requeridos</label>
                            <div class="">
                              <button class="btn-custom btn-custom--blue" type="button" id="agregarProducto" name="agregarProducto" onclick="agregarProd()">Agregar producto</button>
                            </div>
                          </div>
                        </div>
                        <br>
                        <div class="form-group">
                          <!-- DataTales Example -->
                          <div class="mb-4">
                            <div class="">
                              <div class="table-responsive">
                                <table class="table" id="tblListadoRequisicionesCompra" width="100%" cellspacing="0">
                                  <thead>
                                    <tr>
                                      <th>ID</th>
                                      <th>Clave/Producto</th>
                                      <th>Cantidad</th>
                                      <th>Unidad de medida</th>
                                      <th></th>
                                    </tr>
                                  </thead>
                                </table>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-lg-6">
                            <label for="usr">Notas visibles al Comprador</label>
                            <textarea class="form-control alphaNumeric-only" cols="10" rows="3" name="NotasComprador" id="NotasComprador" placeholder="Aquí puedes colocar la descripción de tu requisición de compra o datos importantes dirigidos hacia el comprador" maxlength="255"></textarea>
                          </div>
                          <div class="col-lg-6">
                            <label for="usr">Notas internas</label>
                            <textarea class="form-control alphaNumeric-only" cols="10" rows="3" name="NotasInternas" id="NotasInternas" placeholder="Aquí puedes colocar la descripción de tu requisición o datos importantes solo para uso interno" maxlength="255"></textarea>
                          </div>
                        </div>
                        <br>
                        <button type="button" class="btn-custom btn-custom--blue" name="btnAgregar" id="btnAgregar" onclick="constructArrays()" style="float:right">
                          Guardar Requisición
                        </button>
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

      <!--<embed src="../../../../ordenComp/OrdendeCompra_15.pdf" type="application/pdf" width="100%" height="600px" />-->
      <!-- End Main Content -->

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

  <?php
  require_once 'modal_alert.php';
  require_once 'modal_alert_editable.php';
  ?>

  <script src="../../../../js/sb-admin-2.min.js"></script>
  <script src="../../../../js/scripts.js"></script>
  <script src="../../../../js/notificaciones_timlid.js" charset="utf-8"></script>
  <script src="js/requisicionCompra.js" charset="utf-8"></script>
  <script src="js/editar_requisicionCompra.js" charset="utf-8"></script>
  <script src="../../../../js/numeral.min.js"></script>
  <script src="../../../../js/slimselect.min.js"></script>
  <script src="../../../../js/lobibox.min.js"></script>
  <script src="js/scriptNotificaciones.js"></script>
</body>

</html>