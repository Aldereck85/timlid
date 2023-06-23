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

if (isset($_POST["idVenta"])) {
  $IdVenta = $_POST["idVenta"];
}else{
  $IdVenta = '0';
}
  $jwt_ruta = "../../../../";
  require_once '../../../jwt.php';
  $token = $_SESSION['token_ld10d'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="icon" type="image/png" href="../../../../img/header/bluTimlid.png">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="Last-Modified" content="0">
  <meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
  <meta http-equiv="Pragma" content="no-cache">
  <title>Timlid | Agregar Venta</title>

  <!-- ESTILOS -->
  <link href="../../../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../../css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="../../../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../../../css/slimselect.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../../style/agregar_entrada.css">
  <link href="../../../../vendor/datatables/responsive.dataTables.css" rel="stylesheet">
  <link rel="stylesheet" href="../../../../vendor/datatables/buttons.dataTables.css">
  <link href="../../../../css/stylesNewTable.css" rel="stylesheet">
  <link href="../../../../css/styles.css" rel="stylesheet">
  <link rel="stylesheet" href="../../../../css/croppie.css">
  <link rel="stylesheet" href="../../../../css/sweetalert2.css">
  <link rel="stylesheet" href="../../../../css/notificaciones.css">
  <link rel="stylesheet" href="../../style/ventas.css">
  <link href="../../../../css/lobibox.min.css" rel="stylesheet">
  <!-- JS -->
  <script src="../../../../vendor/jquery/jquery.min.js"></script>
  <script src="../../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../../../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../../../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../../../vendor/datatables/dataTables.responsive.js"></script>
  <script src="../../../../vendor/datatables/dataTables.buttons.js"></script>
  <script src="../../../../vendor/datatables/buttons.html5.min.js"></script>
  <script src="../../../../vendor/jszip/jszip.min.js"></script>
  <script src="../../../../js/sb-admin-2.min.js"></script>
  <script src="../../../../js/scripts.js"></script>
  <script src="../../../../js/numeral.min.js" charset="utf-8"></script>
  <script src="../../../../js/croppie.js"></script>
  <script src="../../../../js/sweet/sweetalert2.js"></script>
  <script src="../../../../js/notificaciones_timlid.js" charset="utf-8"></script>
  <script src="../../js/ventas.js" charset="utf-8"></script>
  
  <script src="../../js/unidadesSAT.js" charset="utf-8"></script>
  <script src="../../../../js/numeral.min.js"></script>
  <script src="../../../../js/validaciones.js"></script>
  <style type="text/css">
    #agregarProductoForm .form-control:disabled, #agregarProductoForm .form-control[readonly] {
      background-color: #eaecf4 !important;
      opacity: 1 !important;
    }
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
        $icono = 'ICONO-NOTA-DE-VENTA-AZUL.svg';
        $titulo = 'Agregar venta';
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
                      <form action="" method="post" id="frmVentaDirecta">
                        <div class="form-group">
                          <div class="row d-flex align-items-center" style="height:5rem;margin-bottom:0.5rem">
                            <div class="col-2">
                              <label class="" for="">
                                <p> <b class="textBlue">Afectar inventario:</b> </p> 
                              </label>
                            </div>
                            <div class="col-2">
                              <div class="custom-control custom-switch" id="divChkAfectarInventario">
                                <input type="checkbox" class="check-custom" id="chkAfectarInventario" checked>
                                <label class="shadow-sm check-custom-label" for="chkAfectarInventario">
                                  <div class="circle"></div>
                                  <div class="check-inactivo">Inactivo</div>
                                  <div class="check-activo">Activo</div>
                                </label>  
                              </div>
                            </div>
                            
                          </div>
                          <div class="row">
                              <div class="col-lg-12">
                                      <p class="bar-title">Información de venta</p>
                              </div>
                          </div>  
                          <div class="row">
                            <div class="col-lg-3">
                              <label for="usr">Referencia:*</label>
                              <input type="text" class="form-control alphaNumeric-only" maxlength="20" name="txtReferencia" id="txtReferencia" required readonly>
                              <div class="invalid-feedback" id="invalid-referencia">La venta debe tener una referencia.</div>
                            </div>
                            <div class="col-lg-3">
                              <label for="usr">Fecha de emisión:*</label>
                              <input type="date" class="form-control" maxlength="20" id="txtFechaEmision" readonly required>
                              <div class="invalid-feedback" id="invalid-fechaEm">La venta debe tener una fecha de emisión.</div>
                            </div>
                            <div class="col-lg-3">
                              <label for="usr">Fecha de vencimiento:*</label>
                              <input type="date" class="form-control" maxlength="20" name="txtFechaEstimada" id="txtFechaEstimada" required min="" onchange="validEmptyInput('txtFechaEstimada','invalid-fechaVen')">
                              <div class="invalid-feedback" id="invalid-fechaVen">La venta debe tener una fecha de vencimiento.</div>
                              <input type="date" class="form-control" maxlength="20" name="txtFechaEstimadaMin" id="txtFechaEstimadaMin" style="display:none">
                            </div>
                            <div class="col-lg-2">
                              <label for="cmbMoneda" style="float: rigth;">Moneda:*</label>
                              <select name="cmbMoneda" id="cmbMoneda" required onchange="getval(this);">
                                <option value="0" disabled selected hidden>Seleccione una moneda...</option>
                                <option value="49"  selected >EUR</option>
                                <option value="149"  selected >USD</option>
                                <option value="100"  selected >MXN</option>
                              </select>
                              <div class="invalid-feedback" id="invalid-moneda">El cliente debe tener una moneda.</div>
                            </div>
                          </div>
                          <br>
                          <div class="form-group">
                            <div class="row">
                              <div class="col-lg-3" id="comboCliente">
                                <label for="usr">Cliente:*</label>
                                <select name="cmbCliente" id="cmbCliente" onchange="cambioCliente()"></select>
                                <div class="invalid-feedback" id="invalid-cliente">La venta debe tener un cliente.</div>
                              </div>
                              <div class="col-lg-3">
                                <label for="cmbDireccionEnvio">Sucursal de origen:*</label>
                                <select name="cmbDireccionEnvio" id="cmbDireccionEnvio" onchange="cambioSucursal()"></select>
                                <div class="invalid-feedback" id="invalid-sucursal">La venta debe tener una sucursal.</div>
                              </div>
                              <div class="col-lg-3">
                                <label for="cmbVendedor">Vendedor:*</label>
                                <select name="cmbVendedor" id="cmbVendedor" required>
                                  <option value="0" disabled selected hidden>Seleccione un vendedor...</option>
                                </select>
                                <div class="invalid-feedback" id="invalid-vendedor">El cliente debe tener un vendedor.</div>
                              </div>
                              <div class="col-lg-3">
                                <label for="cmbDireccionEntrega">Dirección de envío:*</label>
                                <select name="cmbDireccionEntrega" id="cmbDireccionEntrega" required>
                                  <option value="0" disabled selected hidden>Seleccione una dirección de envío...</option>
                                </select>
                                <div class="invalid-feedback" id="invalid-direccionEntrega">El cliente debe tener una dirección de envío.</div>
                              </div>
                            </div>
                          </div>
                          <div class="form-group">
                            <div class="row">
                              <div class="col-lg-3">
                                <label for="cmbCondicionPago">Condición de pago:*</label>
                                <select name="cmbCondicionPago" id="cmbCondicionPago" required>
                                  <option value="0" disabled selected hidden>Seleccione una condición...</option>
                                </select>
                                <div class="invalid-feedback" id="invalid-condicionPago">La venta debe tener una condición de pago.</div>
                              </div>
                              
                            </div>
                          </div>
                          <div class="row">
                                <div class="col-lg-12">
                                    <p class="bar-title">Agregar productos o servicios</p>
                                </div>
                            </div>
                          <div class="form-group">
                            <div class="row">
                            <div class="col-lg-3">
                                <div class="row">
                                  <div class="col-lg-3">
                                    <label for="usr">Producto/Servicio:*</label>
                                  </div>
                                  <div class="col-lg-9 noVer">
                                    <input type="checkbox" id="chkcmbTodoProducto" disabled="disabled"> <label for="">Cargar todos los productos</label>
                                  </div>
                                </div>
                                <select name="cmbProducto" id="cmbProducto" onchange="validEmptyInput('cmbProducto','invalid-producto')"></select>
                                <div class="invalid-feedback" id="invalid-producto">La venta debe tener un producto.</div>
                                <select name="cmbTodoProducto" id="cmbTodoProducto" style="display:none;"></select>
                                <div class="row">
                                  <div class="col-lg-12">
                                    <button style="width: 100%;font-size: 14px;" type="button" class="btn-custom btn-custom--border-blue mt-2" id="mostrarTodos"><span id="textoMos">Mostrar todos los productos</span></button>
                                  </div>
                                </div>
                              </div>
                              <div class="col-lg-6" id="inventarioStock">
                                <div class="form-group">
                                  <div class="row">
                                    <div class="col-lg-6">
                                      <label for="usr">Cantidad:*</label>
                                      <input type="number" class="form-control numeric-only txtCantidad" maxlength="8" name="txtCantidad" id="txtCantidad" value="0" onclick="select()">
                                      <div class="invalid-feedback" id="invalid-productoCnt">El producto debe tener una cantidad.</div>
                                      <input type="hidden" name="txtCantidadHis" id="txtCantidadHis">
                                    </div>
                                    <div class="col-lg-3">
                                      <label for="usr">Precio unitario:*</label>
                                      <div class="input-group">
                                        <div class="input-group-prepend">
                                          <span class="input-group-text">$</span>
                                        </div>
                                        <input type="text" class="form-control numericDecimal-only" maxlength="18" name="txtPrecioUnitario" id="txtPrecioUnitario">
                                        <div class="invalid-feedback" id="invalid-precioUnit">El producto debe tener un precio unitario.</div>
                                      </div>
                                    </div>
                                    <div class="col-lg-3">
                                      <input type="checkbox" name="check_precioEspecial" id="check_precioEspecial"/>
                                      <label for="check_precioEspecial">Precio especial</label>
                                    </div>
                                  </div>
                                </div>      
                              </div>
                              
                              <input type="hidden" id="unidadMedida">
                              <input type="hidden" id="seleccionado" value="0">
                              <div class="col-lg-8" id="datosNew">
                                <span id="datosProve">
                                </span>
                              </div>
                              <div class="col-lg-2" id="verInventarioSuc">

                              </div>
                              <div class="col-lg-2">
                                <button class="btn-custom btn-custom--blue" style="position: relative; top: 32px;width: 100%;" type="button" id="agregarProducto" name="agregarProducto" onclick="agregarProd()">Agregar producto</button>
                              </div>
                              <label for="">* Campos requeridos</label>
                            </div>
                          </div>
                          <br>

                          <div class="form-group">
                            <!-- DataTales Example -->
                            <div class="mb-4">
                              <div class="">
                                <div class="table-responsive">
                                  <table class="table" id="tblListadoVentasDirectasTemp" width="100%" cellspacing="0">
                                    <thead>
                                      <tr>
                                        <th></th>
                                        <th>ID</th>
                                        <th>Clave/Producto</th>
                                        <th></th>
                                        <th>Cantidad</th>
                                        <th>Unidad de medida</th>
                                        <th>Precio unitario</th>
                                        <th>Impuestos</th>
                                        <th>Importe</th>
                                        <th></th>
                                        <th>Existencias</th>
                                        <th></th>
                                      </tr>
                                    </thead>

                                  </table>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col-12" style="float:right">
                            <table class="table table-hover" ALIGN="right" style="width: 25%;">
                              <tfoot>
                                <tr>
                                  <th style="color: var(--color-primario)"><b>Subtotal: </b></th>
                                  <th style="color: var(--color-primario)">$ <span id="Subtotal">0.00</span>
                                  </th>
                                  <th style="width:60px;"></th>
                                </tr>
                                <tr>
                                  <th style="color: var(--color-primario)"><b>Impuestos: </b></th>
                                  <th id="impuestos"></th>
                                  <th></th>
                                </tr>
                                <tr class="total redondearAbajoIzq">
                                  <th style="color: var(--color-primario)" class="redondearAbajoIzq"><b>Total: </b></th>
                                  <th style="color: var(--color-primario)"><b>$ <span id="Total">0.00</span></b></th>
                                  <th></th>
                                </tr>
                              </tfoot>
                            </table>
                          </div>
                          <div class="row">
                            <div class="col-lg-6">
                              <label for="usr">Notas visibles al cliente</label>
                              <textarea class="form-control" cols="10" rows="3" name="NotasCliente" id="NotasCliente" placeholder="Aquí puedes colocar la descripción de tu orden de compra o datos importantes dirigidos hacía tu cliente" maxlength="900"></textarea>
                            </div>
                            <div class="col-lg-6">
                              <label for="usr">Notas internas</label>
                              <textarea class="form-control" cols="10" rows="3" name="NotasInternas" id="NotasInternas" placeholder="Aquí puedes colocar la descripción de tu cotización o datos importantes solo para uso interno" maxlength="900"></textarea>
                            </div>
                          </div>
                          <br>
                          <button type="button" class="btn-custom btn-custom--blue" name="btnAgregar" id="btnAgregar" onclick="enviarVentaDirecta()" style="float:right"> Guardar venta </button>

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
  <div class="modal fade" id="alert_table_void" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="exampleModalLabel">Hay datos en la tabla </h4>
        </div>
        <div class="modal-body"><center><h4>La tabla contiene datos. Si realiza esta acción se borrarán.</h4> <h5><br>¿Desea proceder?</h5></center></div>
        <div class="modal-footer justify-content-center">
          <button type="button" class="btnesp first espCancelar btnCancelarActualizacion" data-dismiss="modal"
            id="btnCancelar_table_void"><span class="ajusteProyecto">Cancelar</span></button>
          <button type="button" class="btnesp espAgregar float-right" name="btnAgregar_table_void" id="btnAgregar_table_void"><span
            class="ajusteProyecto">Aceptar</span></button>
        </div>
      </div>
    </div>
  </div>

  <!--<script src="../../../../js/sb-admin-2.min.js"></script>
  <script src="../../../../js/scripts.js"></script>-->
  <script src="../../../facturacion/js/slimselect_add.js"></script>
  <script src="../../../../js/lobibox.min.js"></script>
  <script src="../../../../js/validaciones.js"></script>
  
  <script>
    loadAlertsNoti('alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
    setInterval(loadAlertsNoti, 10000, 'alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit')
      .val());
  </script>
  <script>
    _global.PKVenta = '<?php echo $IdVenta;?>';
  </script>

<!-- Add modal añadir cliente -->
  <div class="modal fade right" id="agregar_Cliente_50" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

      <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
      <div class="modal-dialog modal-full-height modal-right modal-md" role="document">
        <div class="modal-content">
          <form action="" id="agregarCliente" method="POST">
            <div class="modal-header">
              <h4 class="modal-title w-100" id="myModalLabel">Agregar cliente</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">x</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label for="usr">Razón social:*</label>
                <input class="form-control" type="text" name="txtRazonSocial" id="txtRazonSocial" autofocus="" required="" maxlength="100" placeholder="Ej. GH Medic" onchange="escribirRazonSocial()" style="text-transform: uppercase">
                <div class="invalid-feedback" id="invalid-razon">El cliente debe tener razón social.</div>
                <div class="invalid-feedback" id="invalid-razonTipoSociedad">La razón social no debe tener el tipo de sociedad.</div>
              </div>
              <div class="form-group">
                <label for="usr">Teléfono:</label>
                <input type="text" id="txtTelefono_Cl" maxlength="10" class="form-control numeric-only" name="txtTelefono_Cl"
                onkeyup="validaNumTelefono(event,'txtTelefono_Cl', 'invalid-telCl', 'result2')">
                <div class="invalid-feedback" id="invalid-telCl">El número de teléfono debe ser válido.</div>
                <input type="hidden" id="result2" readonly>
              </div>
              <div class="form-group DataClient_invoice w-100" style="display: none;">
                <label for="usr">Nombre comercial:</label>
                <input class="form-control" type="text" name="txtNombreComercial" id="txtNombreComercial" autofocus="" maxlength="255" placeholder="Ej. GH Medic" onkeyup="escribirNombre()" style="text-transform: uppercase">
                <div class="invalid-feedback" id="invalid-nombreCom">El cliente debe tener un nombre comercial.</div>
              </div>
              <div class="form-group DataClient_invoice w-100"  style="display: none;">
                <label for="usr">RFC:**</label>
                <input class="form-control alphaNumeric-only" type="text" name="txtRFC" id="txtRFC" maxlength="13" placeholder="Ej. GHMM100101AA1" onchange="validInput('txtRFC', 'invalid-rfc', 'El cliente debe tener RFC.')" oninput="let p=this.selectionStart;this.value=this.value.toUpperCase();this.setSelectionRange(p, p);">
                <div class="invalid-feedback" id="invalid-rfc">El cliente debe tener RFC.</div>
              </div>
              <div class="form-group DataClient_invoice w-100" style="display: none;">
                <label for="usr">Régimen fiscal:**</label>
                <select name="cmbRegimen" id="cmbRegimen" onchange="validInput('cmbRegimen', 'invalid-regimen', 'El cliente debe tener régimen fiscal.')">
                </select>
                <div class="invalid-feedback" id="invalid-regimen">El cliente debe tener régimen fiscal.</div>
              </div>
              <div class="form-group DataClient_invoice w-100" style="display: none;">
                <label for="usr">Medio de contacto:</label>
                <select name="cmbMedioContactoCliente" id="cmbMedioContactoCliente">
                <!-- <select name="cmbMedioContactoCliente" id="cmbMedioContactoCliente" onchange="validInput('cmbMedioContactoCliente', 'invalid-medioCont', 'El cliente debe tener un medio de contacto.')"> -->
                </select>
                <!-- <div class="invalid-feedback" id="invalid-medioCont">El cliente debe tener un medio de contacto.</div> -->
              </div>
              <div class="form-group DataClient_invoice w-100" style="display: none;">
                <label for="usr">Vendedor:</label>
                <select name="cmbVendedorNC" id="cmbVendedorNC">
                <!-- <select name="cmbVendedorNC" id="cmbVendedorNC" onchange="validInput('cmbVendedorNC', 'invalid-vendedor', 'El cliente debe tener un vendedor.')"> -->
                </select>
                <!-- <div class="invalid-feedback" id="invalid-vendedor">El cliente debe tener un vendedor.</div> -->
              </div>
              <div class="form-group DataClient_invoice w-100" style="display: none;">
                <label for="usr">E-mail:</label>
                <input class="form-control" type="email" name="txtEmail" id="txtEmail" autofocus="" maxlength="100" placeholder="Ej. ejemplo@dominio.com" onchange="validarCorreo(this.value, 'txtEmail', 'invalid-email')">
                <div class="invalid-feedback" id="invalid-email">E-mail inválido.</div>
              </div>
              <div class="form-group DataClient_invoice w-100" style="display: none;">
                <label for="usr">Código postal:**</label>
                <input class="form-control numeric-only" type="text" name="txtCP" id="txtCP" autofocus="" maxlength="5" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" placeholder="Ej. 52632" onkeyup="validarCP('txtCP', 'invalid-cp');"">
                <div class="invalid-feedback" id="invalid-cp">El cliente debe tener un codigo postal.</div>
              </div>
              <div class="form-group DataClient_invoice w-100" style="display: none;">
                <label for="usr">País:</label>
                <select name="cmbPais" class="" id="cmbPais">
                <!-- <select name="cmbPais" class="" id="cmbPais" onchange="validInput('cmbPais', 'invalid-paisFisc', 'El cliente debe tener un país.')"> -->
                <option data-placeholder="true"></option>
                <?php
                  $stmt = $conn->prepare("SELECT * FROM paises");
                  $stmt->execute();
                  $row = $stmt->fetchAll();

                  if (count($row) > 0) {
                    foreach ($row as $r) { //Mostrar usuarios
                      if ($r['Disponible'] == 1) {
                        echo '<option value="' . $r['PKPais'] . '">' . $r['Pais'] . '</option>';
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
                <div class="invalid-feedback" id="invalid-paisFisc">El cliente debe tener un país.</div>
                <!--<input type="text" id="txtarea6" class="form-control alpha-only" size="40" name="txtEstado" required>-->
              </div>
              <div class="form-group DataClient_invoice w-100" style="display: none;">
                <label for="usr">Estado:</label>
                <select name="cmbEstado" class="" id="cmbEstado">
                <!-- <select name="cmbEstado" class="" id="cmbEstado" onchange="validInput('cmbEstado', 'invalid-paisEstadoFisc', 'El cliente debe tener un estado.')"> -->
                  <option data-placeholder="true"></option>
                </select>
                <!-- <div class="invalid-feedback" id="invalid-paisEstadoFisc">El cliente debe tener un estado.</div> -->
              </div>
              <input type="checkbox" name="check_clienteFacturar" id="check_clienteFacturar" value="1" onclick="valida_check(this);"/>
                <label for="check_clienteFacturar">Cliente para facturación</label>
              <br><br>
              <div>
                <label for="usr">Campos requeridos *</label>
              </div>
              <div class="DataClient_invoice" style="display: none;">
                <label for="usr">Campos requeridos para facturación **</label>
              </div>
            </div>
            <div class="modal-footer justify-content-center">
              <button type="button" class="btnesp first espCancelar btnCancelarActualizacion" data-dismiss="modal" id="btnCancelar_newCliente" onclick="resetForm('agregarCliente')"><span class="ajusteProyecto">Cancelar</span></button>
              <button type="button" class="btnesp espAgregar float-right" name="btnAgregarNC" id="btnAgregarNC"><span class="ajusteProyecto">Agregar</span></button>
            </div>
          </form>
        </div>
      </div>
      </div>
<!-- End modal añadir cliente -->

<!-- Add modal añadir dirección de envio -->
<div class="modal fade right" id="agregar_direccion_50" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

<!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
<div class="modal-dialog modal-full-height modal-right modal-md" role="document">
  <div class="modal-content">
    <form action="" id="agregarDireccionCL" method="POST">
      <div class="modal-header">
        <h4 class="modal-title w-100" id="myModalLabel">Agregar Dirección</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">x</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label for="usr">Sucursal:*</label>
          <input class="form-control" type="text" name="txtSucursalD" id="txtSucursalD" autofocus="" required="" maxlength="255" placeholder="Ej. Nogales" onkeyup="escribirSucursal()">
          <div class="invalid-feedback" id="invalid-sucursalD">La dirección debe tener un nombre de sucursal.</div>
        </div>
        <div class="form-group">
          <label for="usr">E-mail:*</label>
          <input class="form-control alphaNumeric-only" type="email" name="txtEmailD" id="txtEmailD" required maxlength="100" placeholder="Ej. ejemplo@dominio.com" onchange="validarCorreo(this.value, 'txtEmailD', 'invalid-emailDire')">
          <div class="invalid-feedback" id="invalid-emailDire">La dirección debe tener un email.</div>
        </div>
        <div class="form-group">
          <label for="usr">Calle:*</label>
          <input class="form-control" type="text" name="txtCalle" id="txtCalle" required maxlength="255" placeholder="Ej. Av. México" onkeyup="validInput('txtCalle', 'invalid-calleDire', 'La dirección debe tener una calle.')">
          <div class="invalid-feedback" id="invalid-calleDire">La dirección debe tener una calle.</div>
        </div>
        <div class="form-group">
          <label for="usr">Número exterior:*</label>
          <input class="form-control alphaNumeric-only" type="text" name="txtNumExt" id="txtNumExt" required maxlength="10" placeholder="Ej. 2353 A" onkeyup="validInput('txtNumExt', 'invalid-numExt', 'La dirección debe tener un número exterior.')">
          <div class="invalid-feedback" id="invalid-numExt">La dirección debe tener un número exterior.</div>
        </div>
        <div class="form-group">
          <label for="usr">Colonia:*</label>
          <input class="form-control" type="text" name="txtColonia" id="txtColonia" required maxlength="255" placeholder="Ej. Los Agaves" onkeyup="validInput('txtColonia', 'invalid-colonia', 'La dirección debe tener una colonia.')">
          <div class="invalid-feedback" id="invalid-colonia">La dirección debe tener una colonia.</div>
        </div>
        <div class="form-group">
          <label for="usr">Municipio:*</label>
          <input class="form-control" type="text" name="txtMunicipio" id="txtMunicipio" required maxlength="255" placeholder="Ej. Guadalajara" onkeyup="validInput('txtMunicipio', 'invalid-municipioDire', 'La direccion debe tener un municipio.')">
          <div class="invalid-feedback" id="invalid-municipioDire">La direccion debe tener un municipio.</div>
        </div>
        <div class="form-group">
          <label for="usr">País:*</label>
          <select name="cmbPaisD" class="" id="cmbPaisD" onchange="validInput('cmbPaisD', 'invalid-paisDire', 'La direccion debe tener un país.')" required>
          <option data-placeholder="true"></option>
          <?php
            $stmt = $conn->prepare("SELECT * FROM paises");
            $stmt->execute();
            $row = $stmt->fetchAll();

            if (count($row) > 0) {
              foreach ($row as $r) { //Mostrar usuarios
                if ($r['Disponible'] == 1) {
                  echo '<option value="' . $r['PKPais'] . '">' . $r['Pais'] . '</option>';
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
          <div class="invalid-feedback" id="invalid-paisDire">La dirección debe tener un país.</div>
        </div>
        <div class="form-group">
          <label for="usr">Estado:*</label>
          <select name="cmbEstadoD" class="" id="cmbEstadoD" onchange="validInput('cmbEstadoD', 'invalid-estadoDire', 'El cliente debe tener un estado.')" required>
            <option data-placeholder="true"></option>
          </select>
          <div class="invalid-feedback" id="invalid-estadoDire">La dirección debe tener un estado.</div>
          <!--<input type="text" id="txtarea6" class="form-control alpha-only" size="40" name="txtEstado" required>-->
        </div>
        <div class="form-group">
          <label for="usr">Código postal:*</label>
          <input class="form-control numeric-only" type="text" name="txtCPD" id="txtCPD" required maxlength="5" placeholder="Ej. 52632" onkeyup="validarCP('txtCPD', 'invalid-cpDire')">
          <div class="invalid-feedback" id="invalid-cpDire">La dirección debe tener un CP.</div>
        </div>
        <br>
        <div>
          <label for="usr">Campos requeridos *</label>
        </div>
      </div>
      <div class="modal-footer justify-content-center">
        <button type="button" class="btnesp first espCancelar btnCancelarActualizacion" data-dismiss="modal" id="btnCancelar_newDire" onclick="resetForm('agregarDireccionCL')"><span class="ajusteProyecto">Cancelar</span></button>
        <button type="button" class="btnesp espAgregar float-right" name="btnAgregarD" id="btnAgregarD"><span class="ajusteProyecto">Agregar</span></button>
      </div>
    </form>
  </div>
</div>
</div>
<!-- End modal añadir dirección de envio -->

<!-- Add modal añadir sucursal -->
<div class="modal fade right" id="agregar_Locacion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">

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
              <input type="text" id="txtarea" class="form-control" maxlength="40" name="txtLocacion" required
                onchange="validarUnicaSucursal(this)" placeholder="Ej. México">
              <div class="invalid-feedback" id="invalid-nombreSuc">La sucursal debe tener un nombre.</div>
            </div>
            <div class="form-group">
              <label for="usr">Calle:</label>
              <input type="text" id="txtarea2" class="form-control" name="txtCalle" maxlength="50" placeholder="Ej. Av. Morelos">
              <!-- <input type="text" id="txtarea2" class="form-control alpha-only" name="txtCalle" maxlength="50"
                onkeyup="validInput('txtarea2', 'invalid-calleSuc', 'La sucursal debe tener una calle.')" placeholder="Ej. Av. Morelos" required> -->
              <!-- <div class="invalid-feedback" id="invalid-calleSuc">La sucursal debe tener una calle.</div> -->
            </div>
            <div class="form-group">
              <label for="usr">Número exterior:</label>
              <input type="text" id="txtarea3" class="form-control numeric-only" name="txtNe" placeholder="Ej. 237">
              <!-- <input type="text" id="txtarea3" class="form-control numeric-only" name="txtNe"
                onkeyup="validInput('txtarea3', 'invalid-noExtSuc', 'La sucursal debe tener un número exterior.')" required placeholder="Ej. 237">
              <div class="invalid-feedback" id="invalid-noExtSuc">La sucursal debe tener un número exterior.</div> -->
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
              <input type="text" id="txtarea5" class="form-control" size="40" name="txtColonia" placeholder="Ej. Vallarta">
              <!-- <input type="text" id="txtarea5" class="form-control alpha-only" size="40" name="txtColonia"
                onkeyup="validInput('txtarea5', 'invalid-coloniaSuc', 'La sucursal debe tener una colonia.')" required placeholder="Ej. Vallarta">
              <div class="invalid-feedback" id="invalid-coloniaSuc">La sucursal debe tener una colonia.</div> -->
            </div>
            <div class="form-group">
              <label for="usr">Municipio:</label>
              <input type="text" id="txtarea7" class="form-control alphaNumeric-only" size="40" name="txtMunicipio" placeholder="Ej. Guadalajara">
              <!-- <input type="text" id="txtarea7" class="form-control alphaNumeric-only" size="40" name="txtMunicipio"
                onkeyup="validInput('txtarea7', 'invalid-municipioSuc', 'La sucursal debe tener un municipio.')" required placeholder="Ej. Guadalajara">
              <div class="invalid-feedback" id="invalid-municipioSuc">La sucursal debe tener un municipio.</div> -->
            </div>
            <div class="form-group">
              <label for="usr">País:</label>
              <select name="cmbPaisS" class="" id="txtarea8">
                <option data-placeholder="true"></option>
                <?php
                  $stmt = $conn->prepare("SELECT * FROM paises");
                  $stmt->execute();
                  $row = $stmt->fetchAll();

                  if (count($row) > 0) {
                      foreach ($row as $r) { //Mostrar usuarios
                          if ($r['Disponible'] == 1) {
                              echo '<option value="' . $r['PKPais'] . '">' . $r['Pais'] . '</option>';
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
              <!-- <div class="invalid-feedback" id="invalid-paisSuc">La sucursal debe tener un país.</div> -->
            </div>
            <div class="form-group">
              <label for="usr">Estado:</label>
              <select name="cmbEstados" class="" id="txtarea6">
                <option data-placeholder="true"></option>
              </select>
              <!-- <div class="invalid-feedback" id="invalid-estadoSuc">La sucursal debe tener un estado.</div> -->
            </div>
            <div class="form-group">
              <label for="usr">Teléfono:</label>
              <input type="text" id="txtarea10" maxlength="10" class="form-control numeric-only" name="txtTelefono"
              onkeyup=" return validaNumTelefono(event,'txtarea10', 'invalid-telSuc', 'result1')">
              <div class="invalid-feedback" id="invalid-telSuc">La sucursal debe número de teléfono valido.</div>
              <input type="hidden" id="result1" readonly>

            </div>
            <div class="form-group">
              <div class="row">
                <div class="col-lg-12">
                  <label for="usr">¿Se administran inventarios en esta sucursal?</label>
                  <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="cbxActivarInventario"
                      name="cbxActivarInventario">
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
              id="btnCancelar_newSuc" onclick="resetForm('agregarLocacion')"><span class="ajusteProyecto">Cancelar</span></button>
            <button type="button" class="btnesp espAgregar float-right" name="btnAgregar" id="btnAgregarLocacion"><span
                class="ajusteProyecto">Agregar</span></button>
          </div>
        </form>
      </div>
    </div>
  </div>
<!-- End modal añadir sucursal -->

<!-- Add modal añadir vendedor -->
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
              onkeyup="validInput('txtNombre', 'invalid-nombre', 'El empleado debe tener un nombre.')">
              <div class="invalid-feedback" id="invalid-nombre">El empleado debe tener un nombre.</div>
            </div>
            <div class="form-group">
              <label for="txtPrimerApellido">Primer apellido:*</label>
              <input type="text" class="form-control alpha-only" name="txtPrimerApellido" id="txtPrimerApellido" maxlength="50"
                onkeyup="validInput('txtPrimerApellido', 'invalid-primerApellido', 'El empleado debe tener apellido.')" required>
              <div class="invalid-feedback" id="invalid-primerApellido">El empleado debe tener un primer apellido.</div>
            </div>
            <div class="form-group">
              <label for="cmbGenero">Genero:</label>
              <select name="cmbGenero" id="cmbGenero">
                <option data-placeholder="true"></option>
                <option value="Masculino">Masculino</option>
                <option value="Femenino">Femenino</option>
              </select>
              <!-- <div class="invalid-feedback" id="invalid-genero">El empleado debe tener un género.</div> -->
            </div>
            <div class="form-group">
              <label for="usr">Código postal:</label>
              <input class="form-control numeric-only" type="text" name="txtCPE" id="txtCPE" maxlength="5" placeholder="Ej. 52632" onkeyup="validarCP('txtCPE', 'invalid-cpE')">
              <div class="invalid-feedback" id="invalid-cpE"></div>
            </div>
            <div class="form-group">
              <label for="cmbEstado">Estado:</label>
              <select name="cmbEstado_NE" id="cmbEstado_NE">
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
              <!-- <div class="invalid-feedback" id="invalid-estadoNE">El empleado debe tener un estado.</div> -->
            </div>
            <div class="form-group">
              <label for="cmbRoles">Roles:</label>
              <select name="cmbRoles" id="cmbRoles" multiple>
                <option data-placeholder="true"></option>
                <?php
                  $stmt = $conn->prepare("SELECT * FROM tipo_empleado");
                  $stmt->execute();
                  $row = $stmt->fetchAll();
                  foreach ($row as $r) { //Mostrar roles
                    if($r['id'] == 1){
                      echo '<option value="' . $r['id'] . '" data-mandatory="true" selected>' . $r['tipo'] . '</option>';
                    }else{
                      echo '<option value="' . $r['id'] . '" >' . $r['tipo'] . '</option>';
                    }
                  }
                ?>
              </select>
             <!--  <div class="invalid-feedback" id="invalid-roles">El empleado debe tener al menos un rol.</div> -->
            </div>
            <br>
            <div>
              <label for="usr">Campos requeridos *</label>
            </div>
          </div>
          <div class="modal-footer justify-content-center">
            <button type="button" class="btnesp first espCancelar btnCancelarActualizacion" data-dismiss="modal"
              id="btnCancelar_newEmpleado" onclick="resetForm('agregarEmpleado')"><span class="ajusteProyecto">Cancelar</span></button>
            <button type="button" class="btnesp espAgregar float-right" name="btnAgregarPersonal" id="btnAgregarPersonal"><span
                class="ajusteProyecto">Agregar</span></button>
          </div>
        </form>
      </div>
    </div>
  </div>
<!-- End modal añadir vendedor -->

<!--ADD MODAL PRODUCTO-->
<div class="modal fade right" id="agregar_Producto" tabindex="-1" role="dialog" aria-labelledby="modalAgregarEmpleado"
    aria-hidden="true" style="overflow-y: auto !important;">
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
              <label for="txtProducto">Nombre:*</label>
              <input type="text" class="form-control alphaNumeric-only" maxlength="50" name="txtProducto" id="txtProducto" required
              onkeyup="escribirNombreProd()">
              <div class="invalid-feedback" id="invalid-nombreProducto">El producto debe tener un nombre.</div>
            </div>
            <div class="form-group">
              <label for="cmbTipoProducto">Tipo:*</label>
              <select name="cmbTipoProducto" id="cmbTipoProducto" onchange="validInput('cmbTipoProducto', 'invalid-tipoProd', 'El producto debe tener un tipo.')" required>
                <option data-placeholder="true"></option>
                <?php
                    $stmt = $conn->prepare("call spc_Combo_TipoProducto()");
                    $stmt->execute();
                    $row = $stmt->fetchAll();
                    foreach ($row as $r) { 
                      echo '<option value="' . $r['PKTipoProducto'] . '" >' . $r['TipoProducto'] . '</option>';
                    }
                  ?>
              </select>
              <div class="invalid-feedback" id="invalid-tipoProd">El producto debe tener un tipo.</div>
            </div>
            <div class="form-group">
              <label for="txtClave">Clave interna:*</label>
              <input type="text" class="form-control alphaNumeric-only" name="txtClave" id="txtClave" maxlength="50"
                onkeyup="escribirClave()" style="text-transform:uppercase" required>
              <div class="invalid-feedback" id="invalid-clave">El producto debe tener clave interna.</div>
            </div>
            <div class="form-group">
              <a href="#" class="btn-custom btn-custom--blue ml-3" id="btnGenerarClave">Generar</a>
            </div>
            

            <div class="form-group">
              <div class="row">
                <div class="col-lg-12">
                  <label>Existencia:</label>
                  <div class="input-group">
                      <input class="form-control cantidadProducto" type="text" maxlength="13" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" name="txtCostoUniFabri" id="txtCostoUniFabri" placeholder="Ej. 10.00" style="float:left;" onkeyup="validEmptyInput('txtCostoUniFabri', 'invalid-costoFabrProd', 'El producto debe tener un costo de fabricación.')">
                      <div class="invalid-feedback" id="invalid-costoFabrProd">El producto debe tener un costo de fabricación.</div>
                </div>
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="row">
                <div class="col-lg-12">
                    <label for="usr">Unidad de medida SAT:</label>
                    <input  name="txtIDUnidadSATAAA" id="txtIDUnidadSATAAA" type="hidden" value="1" readonly>
                    <div class="row">
                      <div class="col-lg-12 input-group">
                        <input type="text" class="form-control" name="cmbUnidadSATAAA" id="cmbUnidadSATAAA" data-toggle="modal" data-target="#agregar_UnidadSAT" 
                        placeholder="Seleccione una unidad de medida..." value="Seleccione una clave" readonly required="" >
                        <img  id="notaFUnidadSAT" name="notaFUnidadSAT" style="display: none;"
                        src="../../../../img/timdesk/alerta.svg" width=30px
                        title="Campo requerido" readonly>
                      </div>
                    </div>
                </div>
              </div>
            </div>

            <div class="form-group ">
                          <div class="row">
                            <div class="col-lg-6">
                              <label for="usr">Impuesto:</label>
                              <select class="cmbSlim" name="cmbImpuestos" id="cmbImpuestos" required="" onchange="cambioImpuesto(this.value)">
                              </select>
                              <input class="form-control" id="notaImpuesto" name="notaImpuesto" type="hidden"
                              style="color: darkred; background-color: transparent!important; border: none;"
                              value="Nota: El impuesto ya ha sido agregado." readonly>
                            </div>
                            <div class="col-lg-6">
                              <label for="usr">Tipo:</label>
                              <input type='hidden' value='1' name="txtTipoImpuesto" id="txtTipoImpuesto">
                              <div style="background:#0275d8;padding:5px;color:white;" id="trasladado"><center>Trasladado</center></div>
                              <div style="background:#f0ad4e;padding:5px;color:white;display: none;" id="retenciones"><center>Retenciones</center></div>
                              <div style="background:#5cb85c;padding:5px;color:white;display: none;" id="local"><center>Local</center></div>
                            </div>
                            <div class="col-lg-6">
                              <label for="usr" id="etiquetaImpuesto">Tasa:</label>
                              <input type='hidden' value='1' name="txtTipoTasa" id="txtTipoTasa">
                              <span id="areaimpuestos">
                                <select class="cmbSlim" name="cmbTasaImpuestos" id="cmbTasaImpuestos" required="">
                                </select> 
                              </span>   
                            </div>
                            <div class="col-lg-6" style="text-align:center!important; margin-top:35px;" id="btnAnadirImpuesto2">
                              <a href="#" class="btn-custom btn-custom--border-blue" id="btnAnadirImpuesto" onclick="validarImpuesto()">Añadir impuesto</a>
                            </div>
            </div>                   
            <br>

            <div class="table-responsive">
                      <table class="table" id="tablaprueba" width="100%" cellspacing="0">
                        <thead>
                          <tr>
                            <th style="width:50%">Impuesto</th>
                            <th style="width:20%">Tipo</th>
                            <th style="width:20%">Tasa</th>
                            <th style="width:10%"></th>
                          </tr>
                        </thead>
                        <tbody id="addImpuesto">
                        </tbody>
                      </table>
                    </div>
            <br>

            <div>
              <label for="usr">Campos requeridos *</label>
            </div>
          </div>
          <div class="modal-footer justify-content-center">
            <button type="button" class="btnesp first espCancelar btnCancelarActualizacion" data-dismiss="modal"
              id="btnCancelar_newProd" onclick="resetForm('agregarProductoForm')"><span class="ajusteProyecto">Cancelar</span></button>
            <button type="button" class="btnesp espAgregar float-right" name="btnAgregarProducto" id="btnAgregarProducto"><span
                class="ajusteProyecto">Agregar</span></button>
          </div>
        </form>
      </div>
    </div>
  </div>
<!--END MODAL PRODUCTO-->

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
    
  
    <script src="../../js/agregar_ventas.js" charset="utf-8"></script>
</body>

</html>