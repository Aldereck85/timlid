<?php
session_start();
require_once '../../../../include/db-conn.php';
$user = $_SESSION["Usuario"];

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
  <title>Timlid | Agregar Orden de compra</title>

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
  <!-- <link href="../../style/ordenesCompra.css" rel="stylesheet"> -->
  <link href="../../../../css/lobibox.min.css" rel="stylesheet">
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
        $icono = 'ICONO-ORDENES-DE-COMPRA-AZUL.svg';
        $titulo = 'Orden de compra';
        $backIcon = true;
        require_once $rutatb . 'topbar.php';
        ?>

        <input type="hidden" id="txtUsuario" value="<?= $_SESSION['PKUsuario']; ?>">
        <input type="hidden" id="txtRuta" value="<?= $ruta; ?>">
        <input type="hidden" id="txtEdit" value="<?= $ruteEdit; ?>">
        <input type="hidden" id="txtPantalla" value="13">

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
                              <input type="text" class="form-control alphaNumeric-only" maxlength="20" name="txtReferencia" id="txtReferencia" required readonly>
                              <div class="invalid-feedback" id="invalid-referencia">El producto debe tener una
                                referencia.
                              </div>
                            </div>
                          </div>
                          <div class="col-lg-4">
                            <div class="form-group">
                              <label for="usr">Fecha de emision:*</label>
                              <input type="date" class="form-control" maxlength="20" id="txtFechaEmision" readonly required>
                              <div class="invalid-feedback" id="invalid-emision">El producto debe tener una fecha de
                                emisión.
                              </div>
                            </div>
                          </div>
                          <div class="col-lg-4">
                            <div class="form-group">
                              <label for="usr">Fecha estimada de entrega:*</label>
                              <input type="date" class="form-control" maxlength="20" name="txtFechaEstimada" id="txtFechaEstimada" required onchange="validEmptyInput(this)">
                              <div class="invalid-feedback" id="invalid-fechaEst">El producto debe tener una
                                fecha estimada de entrega.
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-lg-3">
                            <div class="form-group">
                              <label for="usr">Proveedor:*</label>
                              <select name="cmbProveedor" id="cmbProveedor" onchange="cambioProveedor(this)" required></select>
                              <div class="invalid-feedback" id="invalid-proveedor">El producto debe tener un
                                proveedor.
                              </div>
                            </div>
                          </div>
                          <div class="col-lg-3">
                            <div class="form-group">
                              <label for="cmbDireccionEnvio">Sucursal de entrega:*</label>
                              <select name="cmbDireccionEnvio" id="cmbDireccionEnvio" required></select>
                              <div class="invalid-feedback" id="invalid-sucursal">El producto debe tener una sucursal
                                de entrega.</div>
                            </div>
                          </div>
                          <div class="col-lg-3">
                            <div class="form-group">
                              <label for="cmbComprador">Comprador:*</label>
                              <select name="cmbComprador" id="cmbComprador" required></select>
                              <div class="invalid-feedback" id="invalid-comprador">El producto debe tener un comprador.</div>
                            </div>
                          </div>
                          <div class="col-lg-2">
                            <label for="cmbCondicionPago">Condición de pago:*</label>
                            <select name="cmbCondicionPago" id="cmbCondicionPago" required>
                              <option value="0" disabled selected hidden>Seleccione una condición...</option>
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
                        <div class="row form-group">
                            <div class="col-lg-4">
                                <label for="cmbCategoriaCuenta">Categoria:*</label>
                                <select class="form-select" name="cmbCategoriaCuenta" id="cmbCategoriaCuenta" aria-label="Default select example" required></select>
                                <input type="hidden" name="txtIdCategoria" id="txtIdCategoria">
                                <div class="invalid-feedback" id="invalid-categoriaCuenta">El campo es obligatorio.</div>
                            </div>
                            <div class="col-lg-4">
                                <label for="cmbCategoriaCuenta">Subcategoria:*</label>
                                <select class="form-select" name="cmbSubcategoriaCuenta" id="cmbSubcategoriaCuenta" disabled></select>
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
                                <input type="checkbox" id="chkcmbTodoProducto" disabled="disabled">
                                <label for="">Cargar todos los productos</label>
                              </div>
                            </div>
                            <div class="form-group">
                              <select name="cmbProducto" id="cmbProducto"></select>
                              <select name="cmbTodoProducto" id="cmbTodoProducto" style="display:none;" onchange="validEmptyInput(this)"></select>
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
                                <input type="text" class="form-control numericDecimal-only" maxlength="10" name="txtPrecioUnitario" id="txtPrecioUnitario" required onkeyup="validEmptyInput(this)">
                                <div class="invalid-feedback" id="invalid-precioUnit">El producto debe tener un
                                  precio unitario.</div>
                              </div>
                            </div>
                          </div>
                          <div class="col-lg-4">
                            <div class="form-group">
                              <label for="usr">Cantidad:*</label>
                              <input type="number" class="form-control numeric-only txtCantidad" maxlength="8" name="txtCantidad" id="txtCantidad" required onkeyup="validEmptyInput(this)">
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
                              <button class="btn-custom btn-custom--blue" type="button" id="agregarProducto" name="agregarProducto" onclick="agregarProd()">Agregar producto</button>
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
                            <textarea class="form-control" cols="10" rows="3" name="NotasProveedor" id="NotasProveedor" placeholder="Aquí puedes colocar la descripción de tu orden de compra o datos importantes dirigidos hacía tu proveedor" maxlength="255"></textarea>
                          </div>
                          <div class="col-lg-6">
                            <label for="usr">Notas internas</label>
                            <textarea class="form-control" cols="10" rows="3" name="NotasInternas" id="NotasInternas" placeholder="Aquí puedes colocar la descripción de tu cotización o datos importantes solo para uso interno" maxlength="255"></textarea>
                          </div>
                        </div>
                        <br>
                        <button type="button" class="btn-custom btn-custom--blue" name="btnAgregar" id="btnAgregar" onclick="enviarOrdenCompra()" style="float:right">
                          Guardar orden de compra
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

    <!--ADD MODAL EMPLEADO-->
    <div class="modal fade right" id="agregar_Proveedor" tabindex="-1" role="dialog" aria-labelledby="modalAgregarEmpleado"
    aria-hidden="true">
    <div class="modal-dialog modal-full-height modal-right modal-md" role="document">
      <div class="modal-content">
        <form id="agregarProveedor">
          <div class="modal-header">
            <h4 class="modal-title w-100">Agregar proveedor</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">x</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label for="txtNombreCom">Nombre comercial:*</label>
              <input type="text" class="form-control alpha-only" maxlength="50" name="txtNombreCom" id="txtNombreCom" required
              onkeyup="validEmptyInput(this)">
              <div class="invalid-feedback" id="invalid-nombreCom">El proveedor debe tener un nombre comercial.</div>
            </div>
            <div class="form-group">
              <label for="txtContactoProv">Contacto del proveedor:*</label>
              <input type="text" class="form-control alpha-only" name="txtContactoProv" id="txtContactoProv" maxlength="50"
                onkeyup="validEmptyInput(this)" required>
              <div class="invalid-feedback" id="invalid-contacto">El provvedor debe tener un contacto.</div>
            </div>
            <div class="form-group">
              <label for="txtTelefono">Teléfono:*</label>
              <input type="text" class="form-control alpha-only" name="txtTelefono" id="txtTelefono" maxlength="10"
                onkeyup="validEmptyInput(this)" required>
              <div class="invalid-feedback" id="invalid-telefonoProv">El empleado debe tener un primer apellido.</div>
            </div>
            <div class="form-group">
              <label for="txtEmail">Correo:*</label>
              <input type="text" class="form-control alpha-only" name="txtEmail" id="txtEmail" maxlength="50"
                onkeyup="validEmptyInput(this)" required>
              <div class="invalid-feedback" id="invalid-correoProv">El empleado debe tener un correo.</div>
            </div>
            <div class="form-group">
              <label for="cmbTipoPer">Tipo de persona:*</label>
              <select name="cmbTipoPer" id="cmbTipoPer">
                <option data-placeholder="true"></option>
                <option value="Física">Física</option>
                <option value="Moral">Moral</option>
              </select>
              <div class="invalid-feedback" id="invalid-tipoPer">El empleado debe tener un tipo de persona.</div>
            </div>
          </div>
          <div class="modal-footer justify-content-center">
            <button type="button" class="btnesp first espCancelar btnCancelarActualizacion" data-dismiss="modal"
              id="btnCancelarActualizacion"><span class="ajusteProyecto">Cancelar</span></button>
            <button type="button" class="btnesp espAgregar float-right" name="btnAgregarProveedor" id="btnAgregarProveedor"><span
                class="ajusteProyecto">Agregar</span></button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!--END MODAL EMPLEADO-->

<!-- ADD SUCURSAL MODAL -->
<div class="modal fade right" id="agregar_Locacion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

  <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
  <div class="modal-dialog modal-full-height modal-right modal-md" role="document">


    <div class="modal-content">
      <form action="" id="agregarLocacion" method="POST">
        <!--<input type="hidden" name="idProyectoA" value="">-->
        <div class="modal-header">
          <h4 class="modal-title w-100" id="myModalLabel">Agregar sucursal</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">x</span>
          </button>
        </div>
        <div class="modal-body">
          <input class="form-control" id="notaSucursal" name="notaSucursal" type="hidden"
            style="color: darkred; background-color: transparent!important; border: none;"
            value="Nota: Nombre de la sucursal ya está registrada." readonly>

          <div class="form-group">
            <label for="usr">Nombre de sucursal:*</label>
            <input type="text" id="txtarea" class="form-control alpha-only" maxlength="40" name="txtLocacion" required
              onchange="validarUnicaSucursal(this)" placeholder="Ej. México">
            <div class="invalid-feedback" id="invalid-nombreSuc">La sucursal debe tener un nombre.</div>
          </div>
          <div class="form-group">
            <label for="usr">Calle:</label>
            <input type="text" id="txtarea2" class="form-control alpha-only" name="txtCalle" maxlength="50" placeholder="Ej. Av. Morelos">
          </div>
          <div class="form-group">
            <label for="usr">Número exterior:</label>
            <input type="text" id="txtarea3" class="form-control numeric-only" name="txtNe" placeholder="Ej. 237">
          </div>
          <div class="form-group">
            <label for="usr">Número interior: </label>
            <div class="input-group">
              <select name="cmbPrefijo" class="form-control" placeholder="" id="txtarea9">
                <option disabled selected hidden>Seleccionar</option>
                <option value="Interior">Interior</option>
                <option value="Bodega">Bodega</option>
                <option value="Piso">Piso</option>
                <option value="Departamento">Departamento</option>
              </select>
              <input type="text" id="txtarea4" style="width:40%;" class="form-control alphaNumeric-only"
                style="text-transform: uppercase" name="txtNi" placeholder="Ej. 43">
            </div>
          </div>
          <div class="form-group">
            <label for="usr">Colonia:</label>
            <input type="text" id="txtarea5" class="form-control alpha-only" size="40" name="txtColonia" placeholder="Ej. Vallarta">
          </div>
          <div class="form-group">
            <label for="usr">Municipio:</label>
            <input type="text" id="txtarea7" class="form-control alphaNumeric-only" size="40" name="txtMunicipio" placeholder="Ej. Guadalajara">
          </div>
          <div class="form-group">
            <label for="usr">País:</label>
            <select name="cmbPais" class="" id="txtarea8">
              <!--<option value="" style="" disabled selected hidden>Seleccionar pais</option>-->
              <?php
                $stmt = $conn->prepare("SELECT * FROM paises");
                $stmt->execute();
                $row = $stmt->fetchAll();

                if (count($row) > 0) {
                    foreach ($row as $r) { //Mostrar usuarios
                        if ($r['Disponible'] == 1) {
                            echo '<option value="' . $r['PKPais'] . '" selected>' . $r['Pais'] . '</option>';
                            $pais = $r['PKPais'];
                        } else {
                            //echo '<option value="'.$r['PKPais'].'">'.$r['Pais'].'</option>';
                        }
                    }
                } else {
                    echo '<option value="" disabled>No hay registros para mostrar.</option>';
                }
              ?>
            </select>
            <!--<input type="text" id="txtarea6" class="form-control alpha-only" size="40" name="txtEstado" required>-->
          </div>
          <div class="form-group">
            <label for="usr">Estado:</label>
            <select name="cmbEstados" class="" id="txtarea6">
              <option disabled selected>Seleccionar estado</option>
              <?php
                $stmt = $conn->prepare("SELECT * FROM estados_federativos");
                $stmt->execute();
                $row = $stmt->fetchAll();
                if (count($row) > 0) {
                    foreach ($row as $r) { //Mostrar usuarios
                        echo '<option value="' . $r['PKEstado'] . '" >' . $r['Estado'] . '</option>';
                    }
                } else {
                    echo '<option value="" disabled>No hay registros para mostrar.</option>';
                }
              ?>
            </select>
            <!--<input type="text" id="txtarea6" class="form-control alpha-only" size="40" name="txtEstado" required>-->
          </div>
          <div class="form-group">
            <label for="usr">Teléfono:</label>
            <input type="text" id="txtarea10" maxlength="10" class="form-control numeric-only" name="txtTelefono"
            onkeyup=" return validaNumTelefono(event,this)" placeholder="Ej. 3323025669">
              <input type="hidden" id="result1" readonly>
          </div>
          <div class="form-group">
            <div class="row">
              <div class="col-lg-12">
                <label for="usr">¿Se administran inventarios en esta sucursal?</label>
                <div class="form-check form-switch">
                  <input class="form-check-input" type="checkbox" id="cbxActivarInventario"
                    name="cbxActivarInventario" checked disabled>
                  <label class="form-check-label" for="flexSwitchCheckDefault">Activar inventario</label>
                </div>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label for="usr">Zona salario mínimo</label>
            <div class="form-check">
              <input type="radio" id="radioZonaSalarioMinimo" name="radioZonaSalarioMinimo" value="2" class="form-check-input frontera">
              <label class="form-check-label" for="norte">Zona libre de la frontera</label>
            </div>
            <div class="form-check">
              <input type="radio" id="radioZonaSalarioMinimo" name="radioZonaSalarioMinimo" value="1" checked class="form-check-input general">
              <label for="general">Resto del país</label>
            </div>
          </div>
          <br><br>
          <div>
            <label for="usr">Campos requeridos *</label>
          </div>
        </div>
        <div class="modal-footer justify-content-center">
          <button type="button" class="btnesp first espCancelar btnCancelarActualizacion" data-dismiss="modal"
            id="btnCancelarActualizacion"><span class="ajusteProyecto">Cancelar</span></button>
          <button type="button" class="btnesp espAgregar float-right" name="btnAgregar" id="btnAgregarLocacion"><span
              class="ajusteProyecto">Agregar</span></button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- END SUCURSAL MODAL -->

  <!--ADD MODAL EMPLEADO-->
  <div class="modal fade right" id="agregar_Empleado" tabindex="-1" role="dialog" aria-labelledby="modalAgregarEmpleado"
    aria-hidden="true">
    <div class="modal-dialog modal-full-height modal-right modal-md" role="document">
      <div class="modal-content">
        <form id="agregarEmpleado">
          <div class="modal-header">
            <h4 class="modal-title w-100">Agregar empleado</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">x</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label for="txtNombre">Nombre(s):*</label>
              <input type="text" class="form-control alpha-only" maxlength="50" name="txtNombre" id="txtNombre" required
              onkeyup="validEmptyInput(this)">
              <div class="invalid-feedback" id="invalid-nombre">El empleado debe tener un nombre.</div>
            </div>
            <div class="form-group">
              <label for="txtPrimerApellido">Primer apellido:*</label>
              <input type="text" class="form-control alpha-only" name="txtPrimerApellido" id="txtPrimerApellido" maxlength="50"
                onkeyup="validEmptyInput(this)" required>
              <div class="invalid-feedback" id="invalid-primerApellido">El empleado debe tener un primer apellido.</div>
            </div>
            <div class="form-group">
              <label for="cmbGenero">Genero:*</label>
              <select name="cmbGenero" id="cmbGenero">
                <option data-placeholder="true"></option>
                <option value="Masculino">Masculino</option>
                <option value="Femenino">Femenino</option>
              </select>
              <div class="invalid-feedback" id="invalid-genero">El empleado debe tener un género.</div>
            </div>
            <div class="form-group">
              <label for="cmbEstado">Estado:*</label>
              <select name="cmbEstado" id="cmbEstado" onchange="validEmptyInput(this)" required>
                <option data-placeholder="true"></option>
                <?php
                  $stmt = $conn->prepare("SELECT * FROM estados_federativos");
                  $stmt->execute();
                  $row = $stmt->fetchAll();
                  if (count($row) > 0) {
                      foreach ($row as $r) { //Mostrar estados
                          echo '<option value="' . $r['PKEstado'] . '" >' . $r['Estado'] . '</option>';
                      }
                  } else {
                      echo '<option value="" disabled>No hay registros para mostrar.</option>';
                  }
                ?>
              </select>
              <div class="invalid-feedback" id="invalid-estado">El empleado debe tener un estado.</div>
            </div>
            <div class="form-group">
              <label for="cmbRoles">Roles:*</label>
              <select name="cmbRoles" id="cmbRoles" onchange="validEmptyInput(this)" multiple required>
                <option data-placeholder="true"></option>
                <?php
                  $stmt = $conn->prepare("SELECT * FROM tipo_empleado");
                  $stmt->execute();
                  $row = $stmt->fetchAll();
                  foreach ($row as $r) { //Mostrar roles
                    if($r['id'] == 5){
                      echo '<option value="' . $r['id'] . '" data-mandatory="true" selected>' . $r['tipo'] . '</option>';
                    }else{
                      echo '<option value="' . $r['id'] . '" >' . $r['tipo'] . '</option>';
                    }
                  }
                ?>
              </select>
              <div class="invalid-feedback" id="invalid-roles">El empleado debe tener al menos un rol.</div>
            </div>
          </div>
          <div class="modal-footer justify-content-center">
            <button type="button" class="btnesp first espCancelar btnCancelarActualizacion" data-dismiss="modal"
              id="btnCancelarActualizacion"><span class="ajusteProyecto">Cancelar</span></button>
            <button type="button" class="btnesp espAgregar float-right" name="btnAgregarPersonal" id="btnAgregarPersonal"><span
                class="ajusteProyecto">Agregar</span></button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!--END MODAL EMPLEADO-->

<!--ADD MODAL PRODUCTO-->
<div class="modal fade right" id="agregar_Producto" tabindex="-1" role="dialog" aria-labelledby="modalAgregarEmpleado"
  aria-hidden="true">
  <div class="modal-dialog modal-full-height modal-right modal-md" role="document">
    <div class="modal-content">
      <form id="agregarProductoForm">
        <div class="modal-header">
          <h4 class="modal-title w-100">Agregar producto</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">x</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="txtNombre">Nombre:*</label>
            <input type="text" class="form-control" maxlength="50" name="txtProducto" id="txtProducto" required
            onkeyup="escribirNombreProd()">
            <div class="invalid-feedback" id="invalid-nombreProducto">El producto debe tener un nombre.</div>
          </div>
          <div class="form-group">
            <label for="usr">Tipo:*</label>
            <select name="cmbTipoProducto" id="cmbTipoProducto" required>
            </select>
            <div class="invalid-feedback" id="invalid-tipoProd">El producto debe tener un tipo.</div>
          </div>
          <div class="form-group">
            <label for="txtPrimerApellido">Clave interna:*</label>
            <input type="text" class="form-control" name="txtClave" id="txtClave" maxlength="50"
              onkeyup="escribirClave()" required>
            <div class="invalid-feedback" id="invalid-clave">El producto debe tener clave interna.</div>
            <a href="#" class="btn-custom btn-custom--blue mt-3" id="btnGenerarClave">Generar</a>
          </div>
        </div>
        <div class="modal-footer justify-content-center">
          <button type="button" class="btnesp first espCancelar btnCancelarActualizacion" data-dismiss="modal"
            id="btnCancelarActualizacion"><span class="ajusteProyecto">Cancelar</span></button>
          <button type="button" class="btnesp espAgregar float-right" name="btnAgregarPersonal" id="btnAgregarProducto"><span
              class="ajusteProyecto">Agregar</span></button>
        </div>
      </form>
    </div>
  </div>
</div>
<!--END MODAL PRODUCTO-->

<!--ADD MODAL CATEGORIA-->
<div class="modal fade right" id="agregar_categoria" tabindex="-1" role="dialog" aria-labelledby="modalAgregarEmpleado"
  aria-hidden="true">
  <div class="modal-dialog modal-full-height modal-right modal-md" role="document">
    <div class="modal-content">
      <form id="add_category_form">
        <div class="modal-header">
          <h4 class="modal-title w-100">Agregar categoria</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">x</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="txtNombre">Nombre:*</label>
            <input type="text" class="form-control" maxlength="50" name="txtAddCategoria" id="txtAddCategoria" required>
            <input type="hidden" name="txtIdAddCategory" id="txtIdAddCategory">
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="chkAddSubcategoria">
                <label class="form-check-label" for="chkAddSubcategoria">Agregar subcategoria</label>
            </div>
            <div class="invalid-feedback" id="invalid-addCategoriaCuenta">El campo es obligatorio.</div>
          </div>
          <div class="form-group d-none" id="div_subcategoriaCat">
            <label for="txtAddSubcategoriaCat">Subcatecoria:*</label>
            <input class="form-control" type="text" name="txtAddSubcategoriaCat" id="txtAddSubcategoriaCat">
            <div class="invalid-feedback" id="invalid-addSubCategoriaCuentaCat">El campo es obligatorio.</div>
          </div>
        </div>
        <div class="modal-footer justify-content-center">
          <button type="button" class="btnesp first espCancelar btnCancelarActualizacion" data-dismiss="modal"
            id="btnCancelarActualizacion"><span class="ajusteProyecto">Cancelar</span></button>
          <button type="button" class="btnesp espAgregar float-right" name="btnAgregarCategoriaGastos" id="btnAgregarCategoriaGastos"><span
              class="ajusteProyecto">Agregar</span></button>
        </div>
      </form>
    </div>
  </div>
</div>
<!--END MODAL CATEGORIA-->

<!--ADD MODAL SUBCATEGORIA-->
<div class="modal fade right" id="agregar_subcategoria" tabindex="-1" role="dialog" aria-labelledby="modalAgregarEmpleado"
  aria-hidden="true">
  <div class="modal-dialog modal-full-height modal-right modal-md" role="document">
    <div class="modal-content">
      <form id="add_subcat_form">
        <div class="modal-header">
          <h4 class="modal-title w-100">Agregar subcategoria</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">x</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="cmbAddCategoria">Categoria:*</label>
            <select name="cmbAddCategoria" id="cmbAddCategoria"></select>
            <div class="invalid-feedback" id="invalid-addCategoriaCuentaSubCat">El campo es obligatorio.</div>
          </div>  
          <div class="form-group">
            <label for="txtNombre">Nombre:*</label>
            <input type="text" class="form-control" maxlength="50" name="txtAddSubcategoria" id="txtAddSubcategoria" required>
            <div class="invalid-feedback" id="invalid-addSubcategoriaCuenta">El campo es obligatorio.</div>
          </div>
        </div>
        <div class="modal-footer justify-content-center">
          <button type="button" class="btnesp first espCancelar btnCancelarActualizacion" data-dismiss="modal"
            id="btnCancelarActualizacion"><span class="ajusteProyecto">Cancelar</span></button>
          <button type="button" class="btnesp espAgregar float-right" name="btnAgregarSubcategoria" id="btnAgregarSubcategoria"><span
              class="ajusteProyecto">Agregar</span></button>
        </div>
      </form>
    </div>
  </div>
</div>
<!--END MODAL SUBCATEGORIA-->

<!--   <script src="../../../../js/slimselect.min.js"></script>-->  
  <script src="../../js/slimselect_add.js" charset="utf-8"></script>
  <script src="../../../../js/sb-admin-2.min.js"></script>
  <script src="../../../../js/scripts.js"></script>
  <script src="../../../../js/notificaciones_timlid.js" charset="utf-8"></script>
  <script src="../../js/ordenesCompra.js" charset="utf-8"></script>
  <script src="../../js/agregar_ordenCompra.js" charset="utf-8"></script>
  <script src="../../../../js/numeral.min.js"></script>
  <script src="../../../../js/lobibox.min.js"></script>
  <script>
    loadAlertsNoti('alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
    setInterval(loadAlertsNoti, 10000, 'alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit')
      .val());
  </script>
</body>

</html>