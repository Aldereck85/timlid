<?php
session_start();

$jwt_ruta = "../../";
require_once '../jwt.php';

if (isset($_SESSION["Usuario"])) {
    require_once '../../include/db-conn.php';
} else {
    header("location:../dashboard.php");
}

$token = $_SESSION['token_ld10d'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="icon" type="image/png" href="../../img/header/bluTimlid.png">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Timlid | Agregar Cotización</title>

  <!-- ESTILOS -->
  <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../../css/bootstrap-clockpicker.min.css" rel="stylesheet" type="text/css">
  <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../css/lobibox.min.css" rel="stylesheet">
  <link href="../../css/slimselect.min.css" rel="stylesheet">
  <link href="../../css/styles.css" rel="stylesheet">

  <link href="../../vendor/datatables/responsive.dataTables.css" rel="stylesheet">
  <link rel="stylesheet" href="../../vendor/datatables/buttons.dataTables.css">

  <!-- Bootstrap core JavaScript-->
  <script src="../../vendor/jquery/jquery.min.js"></script>
  <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="js/slimselect_cotizaciones.js"></script>
  <script src="../../js/sweet/sweetalert2.js"></script>
  <link rel="stylesheet" href="../../css/sweetalert2.css">
  <script src="js/cotizaciones.js" charset="utf-8"></script>
  <script src="../../js/notificaciones_timlid.js" charset="utf-8"></script>
  <link rel="stylesheet" href="../../css/notificaciones.css">

  <script src="../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../vendor/datatables/dataTables.responsive.js"></script>
  <script src="../../vendor/datatables/dataTables.buttons.js"></script>
  <script src="../../vendor/datatables/buttons.html5.min.js"></script>
  <style type="text/css">
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
  .bar-title{
    background-color:#006dd9;
    color:white;
    padding:0.75rem;
    font-size:18px;
  }
  table thead th,table thead td{
    border: 0;
    border-top: 0;
    padding: 0.75rem;
    text-align: center;
    position: relative;
    vertical-align: middle !important;
    color: var(--color-claro);
    background-color: var(--color-primario);
    border-bottom: 1px solid var(--gris-oscuro);
    font-weight: 400;
    box-sizing: content-box;
    
  }
  table{
    width: 100%;
    margin: 0 auto;
    clear: both;
    border-collapse: separate;
    border-spacing: 0;
    text-indent: initial;
    }
.table {
    width: 100%;
    margin-bottom: 1rem;
}
table thead{
    height: 3.5rem;
}
table tr {
    vertical-align: middle !important;
    padding: 2rem;
}
  </style>
</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">
    <!-- Sidebar -->
    <?php
$ruta = "../";
$ruteEdit = $ruta . "central_notificaciones/";
require_once '../menu3.php';
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
$rutatb = "../";
$icono = 'ICONO-COTIZACIONES-AZUL.svg';
$titulo = "Nueva Cotización";
$backIcon = true;
require_once '../topbar.php';
?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <div class="row">

            <div class="col-lg-12">

              <!-- Basic Card Example -->
              <div class="card shadow mb-4">
                <div class="card-body">
                  
                  <div class="row">
                    <div class="col-lg-12">
                      <form action="" method="post" id="form-cotizacion">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-12">
                                    <p class="bar-title">Información de venta</p>
                                </div>
                            </div>  
                          <div class="row">
                            <div class="col-lg-3">
                              <label for="usr">Sucursal:</label>
                              <select name="cmbSucursal" id="chosenSucursal" required>                         
                              </select>
                              <span style="color: #d9534f;display: none;position: absolute;"
                                id="alertaSucursal">Selecciona la sucursal</span>
                              <div class="invalid-feedback" id="invalid-sucursal">La cotización debe tener una sucursal.</div>
                            </div>
                            <!--  -->
                            <div class="col-lg-3">
                              <label for="usr">Cliente:</label>
                              <select name="cmbCliente" id="chosen" required>
                              </select>
                              <span style="color: #d9534f;display: none;position: absolute;"
                                id="alertaClientes">Selecciona el cliente</span>
                              <div class="invalid-feedback" id="invalid-cliente">La cotización debe tener un cliente.</div>
                              <br><br>
                            </div>
                            <div class="col-lg-2">
                              <label for="usr">Nombre comercial:</label>
                              <input type="text" id="cmbRazon" disabled class="form-control">
                              <!-- <div id="cmbRazon"></div> -->
                            </div>
                            <div class="col-lg-2">
                              <label for="cmbCondicionPago">Condición de pago:*</label>
                              <select name="cmbCondicionPago" id="cmbCondicionPago" required>
                                <option value="0" disabled selected hidden>Seleccione una condición...</option>
                              </select>
                              <div class="invalid-feedback" id="invalid-condicionPago">La cotización debe tener una condición de pago.</div>
                            </div>
                            <div class="col-lg-2">
                              <label for="cmbMoneda">Moneda:*</label>
                              <select name="cmbMoneda" id="cmbMoneda" required>
                                <option value="0" disabled selected hidden>Seleccione una moneda...</option>
                                <option value="49"  selected >EUR</option>
                                <option value="149"  selected >USD</option>
                                <option value="100"  selected >MXN</option>
                              </select>
                              <div class="invalid-feedback" id="invalid-moneda">El cliente debe tener una moneda.</div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-lg-3">
                              <label for="usr">Fecha de generación:</label>
                              <?php date_default_timezone_set('America/Mexico_City');
                                $fechaGeneracion = date('Y-m-d\TH:i:s');?>
                              <input type="datetime-local" class="form-control" name="txtFechaGeneracion"
                                id="txtFechaGeneracion" value="<?=$fechaGeneracion?>" readonly>
                            </div>
                            <div class="col-lg-3">
                              <label for="usr">Fecha de vencimiento:</label>
                              <?php
                                $stmt = $conn->prepare('SELECT cantidad FROM parametros WHERE descripcion = "Dias_Vencimiento" and empresa_id = :empresa_id');
                                $stmt->bindValue("empresa_id", $_SESSION['IDEmpresa']);
                                $stmt->execute();
                                $cantVenc = $stmt->rowCount();
                                if ($cantVenc > 0) {
                                    $dv = $stmt->fetch();
                                    $dias_vencimiento = $dv['cantidad'];
                                } else {
                                    $stmt = $conn->prepare('INSERT INTO parametros (descripcion, cantidad, empresa_id) VALUES ("Dias_Vencimiento" , :cantidad, :empresa_id)');
                                    $stmt->bindValue("cantidad", 15);
                                    $stmt->bindValue("empresa_id", $_SESSION['IDEmpresa']);
                                    $stmt->execute();
                                    $dias_vencimiento = 15;
                                }

                                $fechaVencimiento = date('Y-m-d');
                                $fechaVencimientoF = date('Y-m-d', strtotime($fechaVencimiento . ' + ' . $dias_vencimiento . ' days'));
                                ?>
                              <input type="date" class="form-control" name="txtFechaVencimiento"
                                id="txtFechaVencimiento" value="<?=$fechaVencimientoF?>" min="<?=$fechaVencimiento?>"
                                 required>
                            </div>
                            <div class="col-lg-3">
                                <label for="cmbVendedor">Vendedor:*</label>
                                <select name="cmbVendedor" id="cmbVendedor" required>
                                </select>
                                <div class="invalid-feedback" id="invalid-vendedor">La cotización debe tener un vendedor.</div>                                
                            </div>
                            <div class="col-lg-3">
                              <label for="cmbDireccionEntrega">Dirección de envío:*</label>
                              <select name="cmbDireccionEntrega" id="cmbDireccionEntrega" required>
                                <option value="0" disabled selected hidden>Seleccione una dirección de envío...</option>
                              </select>
                              <div class="invalid-feedback" id="invalid-direccionEntrega">El cliente debe tener una dirección de envío.</div>
                            </div>

                          </div>
                          <br>
                            <div class="row">
                                <div class="col-lg-12">
                                    <p class="bar-title">Agregar productos o servicios</p>
                                </div>
                            </div>   
                          <div class="row">
                            <div class="col-lg-6">
                              <label for="usr">Producto:</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <select name="cmbProducto" id="chosenProducto" style="width: 90%;" required>
                                  </select>
                                  <button style="width: 45%;font-size: 14px;" type="button" class="btn-custom btn-custom--border-blue mt-2" id="mostrar_todos" disabled>Mostrar todos los productos</button>
                                </div>
                              </div>
                              <span style="color: #d9534f;display: none;position: absolute;" id="alertaProducto">Ingresa un producto</span>
                            </div>

                            <div class="col-lg-4">
                              <div>
                                <div class="row" id="divCantidad">
                                  <div class="col-lg-6">
                                    <label for="usr">Cantidad<span id="actualizarUnidad"></span>:</label>
                                    <input type='text' value='' name="txtPiezas" id="txtPiezas"
                                      class='form-control numeric-only' disabled>
                                    <span style="color: #d9534f;display: none;position: absolute;" id="alertaPiezas"
                                      onkeydown="insertProduct(event)">Ingresa la cantidad de piezas</span>
                                  </div>
                                  <div class="col-lg-6">
                                    <label for="usr">Precio:</label>
                                    <input type='text' value='' name="txtPrecio" id="txtPrecio"
                                      class='form-control txtPrecio numericDecimal-only' maxlength="19" readonly>
                                    <span style="color: #d9534f;display: none;position: absolute;"
                                      id="alertaPrecio">Ingresa un precio válido</span>
                                  </div>
                                </div>

                              </div>
                            </div>
                            <div class="col-lg-2 d-flex justify-content-start align-items-center">
                              <input type="hidden" name="txtImpuestos" id="txtImpuestos" value="" />
                              <input type="hidden" name="txtClaveUnidad" id="txtClaveUnidad" value="" />
                              <input type="hidden" name="NuevoProducto" id="NuevoProducto" value="" />
                              <input type="hidden" name="TipoProducto" id="TipoProducto" value="" />
                              <button type="button" class="btn-custom btn-custom--border-blue" id="agregarProducto"
                                disabled>Agregar</button>
                            </div>
                          </div>
                        </div>

                        <br><br>
                        <div class="table-responsive redondear">
                          <table class="table table-sm" id="cotizacion">
                            <thead class="text-center header-color">
                              <tr>
                                <th>Clave/Producto</th>
                                <th>Cantidad</th>
                                <th>Unidad de medida</th>
                                <th>Precio unitario</th>
                                <th>Impuestos</th>
                                <th>Importe</th>
                                <th>Existencias</th>
                                <th></th>
                              </tr>
                            </thead>
                            <tbody id="lstProductos">
                            </tbody>
                            <tr><td colspan="8"><br></td></tr>
                            <tr>
                              <th colspan="5"></th>
                              <th style="color: var(--color-primario)">Subtotal:</th>
                              <td style="color: var(--color-primario)" colspan="2">$ <span id="Subtotal">0.00</span></td>
                            </tr>
                            <tr>
                              <th colspan="5"></th>
                              <th style="color: var(--color-primario)">Impuestos:</th>
                              <th colspan="2"></th>
                            </tr>
                            <tbody id="lstimpuestos">

                            </tbody>
                            <tr class="total">
                              <th colspan="5" class="redondearAbajoIzq"></th>
                              <th style="color: var(--color-primario)">Total:</th>
                              <td colspan="2" style="color: var(--color-primario)"><b>$ <span id="Total">0.00</span></b></td>
                            </tr>
                          </table>
                        </div>

                        <div class="row">
                          <div class="col-lg-12" style="color:#d9534f;display: none;text-align: center;"
                            id="mostrarMensaje">
                            <h2>Ingresa un producto al menos.</h2>
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-lg-6">
                            <label for="usr">Notas visibles al cliente:</label>
                            <textarea class="form-control" cols="10" rows="3" name="NotasClientes" id="NotasClientes"
                              placeholder="Aquí puedes colocar la descripción de tu cotización o datos importantes solo para el cliente" maxlength="500"></textarea>
                          </div>
                          <div class="col-lg-6">
                            <label for="usr">Notas internas:</label>
                            <textarea class="form-control" cols="10" rows="3" name="NotasInternas" id="NotasInternas"
                              placeholder="Aquí puedes colocar la descripción de tu cotización o datos importantes solo para uso interno" maxlength="500"></textarea>
                          </div>
                        </div>
                        <br>
                        <input type="hidden" name="csr_token_7ALF1" id="csr_token_7ALF1" value="<?=$token?>">
                        <input type="hidden" name="TipoProductoGeneral" id="TipoProductoGeneral" value=0>
                        <button type="button" class="btn-custom btn-custom--blue float-right" name="btnAgregar" id="btnAgregar">Guardar</button>
                      </form>
                    </div>
                  </div>

                </div>
              </div>

            </div>
          </div>

        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

      <!-- Footer -->
      <?php
$rutaf = "../";
require_once '../footer.php';
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
                onkeyup="escribirClave()" style="text-transform:uppercase" required>
              <div class="invalid-feedback" id="invalid-clave">El producto debe tener clave interna.</div>
              <a href="#" class="btn-custom btn-custom--blue mt-3" id="btnGenerarClave">Generar</a>
            </div>

            <div class="form-group">
              <div class="row">
                <div class="col-lg-12">
                  <label>Existencia:</label>
                  <div class="input-group">
                      <input class="form-control cantidadProducto" type="text" maxlength="13" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" name="txtCostoUniFabri" id="txtCostoUniFabri" placeholder="Ej. 10.00" style="float:left;" onkeyup="validEmptyInput('txtCostoUniFabri', 'invalid-costoFabrProd', 'El producto debe tener un costo de fabricación.')">
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
              id="btnCancelar_newProducto"><span class="ajusteProyecto">Cancelar</span></button>
            <button type="button" class="btnesp espAgregar float-right" name="btnAgregarPersonal" id="btnAgregarProducto"><span
                class="ajusteProyecto">Agregar</span></button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!--END MODAL PRODUCTO-->

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
              <input type="text" class="form-control" maxlength="50" name="txtNombre" id="txtNombre" required
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
              <select name="cmbEstado" id="cmbEstado" required>
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
                    if($r['id'] == 1){
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
              id="btnCancelar_newVendedor"><span class="ajusteProyecto">Cancelar</span></button>
            <button type="button" class="btnesp espAgregar float-right" name="btnAgregarPersonal" id="btnAgregarPersonal"><span
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
              <input type="text" id="txtarea" class="form-control" maxlength="40" name="txtLocacion" required
                onchange="validarUnicaSucursal(this)" placeholder="Ej. México">
              <div class="invalid-feedback" id="invalid-nombreSuc">La sucursal debe tener un nombre.</div>
            </div>
            <div class="form-group">
              <label for="usr">Calle:</label>
              <input type="text" id="txtarea2" class="form-control" name="txtCalle" maxlength="50"
                onkeyup="validEmptyInput(this)" placeholder="Ej. Av. Morelos">
            </div>
            <div class="form-group">
              <label for="usr">Número exterior:</label>
              <input type="text" id="txtarea3" class="form-control numeric-only" name="txtNe"
                onkeyup="validEmptyInput(this)" placeholder="Ej. 237">
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
              <input type="text" id="txtarea5" class="form-control" size="40" name="txtColonia"
                onkeyup="validEmptyInput(this)" placeholder="Ej. Vallarta">
            </div>
            <div class="form-group">
              <label for="usr">Municipio:</label>
              <input type="text" id="txtarea7" class="form-control alphaNumeric-only" size="40" name="txtMunicipio"
                onkeyup="validEmptyInput(this)" placeholder="Ej. Guadalajara">
            </div>
            <div class="form-group">
              <label for="usr">País:</label>
              <select name="cmbPais" class="" id="txtarea8" onchange="validEmptyInput(this)">
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
              <select name="cmbEstados" class="" id="txtarea6" onchange="validEmptyInput(this)">
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
              <input type="text" id="txtarea10" minlength="7" maxlength="10" class="form-control numeric-only" name="txtTelefono"
              onkeyup=" return validaNumTelefono(event,this)" placeholder="Ej. 3323025669">
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
              id="btnCancelar_newSucursal"><span class="ajusteProyecto">Cancelar</span></button>
            <button type="button" class="btnesp espAgregar float-right" name="btnAgregar" id="btnAgregarLocacion"><span
                class="ajusteProyecto">Agregar</span></button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- END SUCURSAL MODAL -->

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
                <input class="form-control" type="text" name="txtRazonSocial" id="txtRazonSocial" autofocus="" required="" maxlength="100" placeholder="Ej. GH Medic" style="text-transform: uppercase">
                <div class="invalid-feedback" id="invalid-razon">El cliente debe tener razón social.</div>
                <div class="invalid-feedback" id="invalid-razonTipoSociedad">La razón social no debe tener el tipo de sociedad.</div>
              </div>
              <div class="form-group">
                <label for="txtTelefono">Teléfono:</label>
                <input class="form-control numeric-only" type="text" name="txtTelefono" id="txtTelefono" maxlength="10" placeholder="Ej. 3365651001">
              </div>
              <div class="d-none" id="show">
                <div class="form-group">
                  <label for="usr">Nombre comercial:</label>
                  <input class="form-control" type="text" name="txtNombreComercial" id="txtNombreComercial" autofocus="" maxlength="255" placeholder="Ej. GH Medic" onkeyup="escribirNombre()" style="text-transform: uppercase">
                </div>
                <div class="form-group">
                  <label for="usr">RFC:*</label>
                  <input class="form-control alphaNumeric-only" type="text" name="txtRFC" id="txtRFC" required maxlength="13" placeholder="Ej. GHMM100101AA1" onkeyup="validarInput()" oninput="let p=this.selectionStart;this.value=this.value.toUpperCase();this.setSelectionRange(p, p);">
                  <div class="invalid-feedback" id="invalid-rfc">El cliente debe tener RFC.</div>
                </div>
                <div class="form-group">
                  <label for="usr">Régimen fiscal:*</label>
                  <select name="cmbRegimen" id="cmbRegimen" onchange="validInput('cmbRegimen', 'invalid-regimen', 'El cliente debe tener régimen fiscal.')" required>
                  </select>
                  <div class="invalid-feedback" id="invalid-regimen">El cliente debe tener régimen fiscal.</div>
                </div>
                <div class="form-group">
                  <label for="usr">Medio de contacto:</label>
                  <select name="cmbMedioContactoCliente" id="cmbMedioContactoCliente">
                  </select>
                </div>
                <div class="form-group">
                  <label for="usr">Vendedor:</label>
                  <select name="cmbVendedorNC" id="cmbVendedorNC">
                  </select>
                </div>
                <div class="form-group">
                  <label for="usr">E-mail:</label>
                  <input class="form-control" type="email" name="txtEmail" id="txtEmail" maxlength="100" placeholder="Ej. ejemplo@dominio.com" onchange="validarCorreo(this.value, 'txtEmail', 'invalid-email')">
                </div>
                <div class="form-group">
                  <label for="usr">Código postal:*</label>
                  <input class="form-control numeric-only" type="text" name="txtCP" id="txtCP" autofocus="" required maxlength="5" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" placeholder="Ej. 52632" onkeyup="validarCP('txtCP', 'invalid-cp');"">
                  <div class="invalid-feedback" id="invalid-cp">El cliente debe tener un codigo postal.</div>
                </div>
                <div class="form-group">
                  <label for="usr">País:</label>
                  <select name="cmbPais" class="" id="cmbPais" onchange="validInput('cmbPais', 'invalid-paisFisc', 'El cliente debe tener un país.')">
                  <option data-placeholder="true"></option>
                  <?php
                    $stmt = $conn->prepare("SELECT * FROM paises");
                    $stmt->execute();
                    $row = $stmt->fetchAll();

                    if (count($row) > 0) {
                      foreach ($row as $r) { //Mostrar paises
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
                </div>
                <div class="form-group">
                  <label for="usr">Estado:</label>
                  <select name="cmbEstado" class="" id="cmbEstadoC" onchange="validInput('cmbEstado', 'invalid-paisEstadoFisc', 'El cliente debe tener un estado.')">
                    <option data-placeholder="true"></option>
                    <?php
                    $stmt = $conn->prepare("SELECT * FROM estados_federativos WHERE FKPais = 146");
                    $stmt->execute();
                    $row = $stmt->fetchAll();

                    if (count($row) > 0) {
                      foreach ($row as $r) { //Mostrar estados
                          echo '<option value="' . $r['PKEstado'] . '">' . $r['Estado'] . '</option>';
                      }
                    } else {
                      echo '<option value="" disabled>No hay registros para mostrar.</option>';
                    }
                    ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <div class="row">
                  <div class="col-lg-12">
                    <div class="form-check form-switch">
                      <input class="form-check-input" type="checkbox" id="cbxClienteFacturacion"
                        name="cbxClienteFacturacion" onchange="mostrarInputs(this)">
                      <label class="form-check-label" for="cbxClienteFacturacion">Cliente para facturación</label>
                    </div>
                  </div>
                </div>
              </div>
              <br>
              <div>
                <label for="usr">Campos requeridos *</label>
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

  <span id="modal_envio"></span>
  <!-- Core plugin JavaScript-->
  <script src="../../js/bootstrap-clockpicker.min.js"></script>
  <script src="../../js/jquery.number.min.js"></script>
  <script src="../../js/numeral.min.js"></script>
  <script src="../../js/Cleave.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="../../js/sb-admin-2.min.js"></script>
  <script src="../../js/lobibox.min.js"></script>
  <script src="../../js/scripts.js"></script>
  <script src="../../js/validaciones.js"></script>
  <script>
  var destinatarios;
  var cuenta = 0;
  var idProductoG;
  var cuentaIVAexento = 0, cuentaISRExento = 0, cuentaIEPSExento = 0;
  var cantidadAnterior = 0;
  let gSucursal = 0;
  let gCliente = 0;
  let gVendedor = 0;
  let gDireccionEnvio = 0;
  let gCondicionPago = 0;
  let gtipoProducto = 0; /// 1 para todos los productos(todos menos servicios), 2 solo para servicios
  let ban_chosen_product = 0;

  $("#chosen")[0].reportValidity();
  $("#chosen")[0].setCustomValidity('Completa este campo.');

  $("#chosenProducto").prop('disabled', true);

  cargarCMBTipo("cmbTipoProducto");
  cargarCMBCondicionPago("", "cmbCondicionPago");

  $("#btnAgregarLocacion").click(function () {
  if ($("#agregarLocacion")[0].checkValidity()) {
    $("#btnAgregarLocacion").prop("disabled", true);
    var badNombreSuc =
      $("#invalid-nombreSuc").css("display") === "block" ? false : true;
    if (
      badNombreSuc
    ) {
      var estado = document.getElementById("txtarea6");
      var cmbEstado = estado.options[estado.selectedIndex].value;
      var nombreSucursal = $("#txtarea").val().trim();
      var calle = $("#txtarea2").val();
      var numExterior = $("#txtarea3").val();
      var prefijo = $("#txtarea9").val();
      var numInterior = $("#txtarea4").val();
      var colonia = $("#txtarea5").val();
      var municipio = $("#txtarea7").val();
      var estado = $("#txtarea6").val();
      var pais = $("#txtarea8").val();
      var telefono = $("#txtarea10").val();
      var actInventario = 0;
      console.log(telefono);
      var zonaSalarioMinimo = $("#radioZonaSalarioMinimo").val();

      if ($("#cbxActivarInventario").is(":checked")) {
        actInventario = 1;
      } else {
        actInventario = 0;
      }

      if (nombreSucursal.length < 1) {
        $("#txtarea")[0].reportValidity();
        $("#txtarea")[0].setCustomValidity("Completa este campo.");
        return;
      } else {
        $.ajax({
          url: "functions/agregar_Locacion.php",
          type: "POST",
          data: {
            txtLocacion: nombreSucursal,
            txtCalle: calle,
            txtNe: numExterior,
            prefijo: prefijo,
            txtNi: numInterior,
            txtColonia: colonia,
            txtMunicipio: municipio,
            cmbEstados: estado,
            cmbPais: pais,
            telefono: telefono,
            actInventario: actInventario,
            zonaSalarioMinimo: zonaSalarioMinimo
          },
          success: function (data, status, xhr) {
            if (data.trim() == "exito") {
              $("#agregar_Locacion").modal("toggle");
              $("#agregarLocacion").trigger("reset");
              loadComboSucursal();
              Lobibox.notify("success", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top", //or 'center bottom'
                icon: true,
                //img: '<i class="fas fa-check-circle"></i>',
                img: "../../../img/timdesk/checkmark.svg",
                msg: "¡Registro agregado!",
              });
            } else {
              $("#btnAgregarLocacion").prop("disabled", false);
              Lobibox.notify("warning", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../../img/timdesk/warning_circle.svg",
                img: null,
                msg: "Ocurrió un error al agregar",
              });
            }
          },
        });
      }
    }
  } else {
    $("#btnAgregarLocacion").prop("disabled", false);
    if (!$("#txtarea").val()) {
      $("#invalid-nombreSuc").css("display", "block");
      $("#txtarea").addClass("is-invalid");
    }
  }
});

$("#btnCancelar_newSucursal").on("click", ()=>{
  $("#agregarLocacion").trigger("reset");
  $("#invalid-nombreSuc").css("display", "none");
  $("#txtarea").removeClass("is-invalid");
  cmbNoInt.set(0);
  cmbPaisSuc.set(0);
  cmbEstadoSuc.set(0);
  $("#btnAgregarLocacion").prop("disabled", false);
});

$("#agregar_Locacion").on("hidden.bs.modal", ()=>{
  $("#agregarLocacion").trigger("reset");
  $("#invalid-nombreSuc").css("display", "none");
  $("#txtarea").removeClass("is-invalid");
  cmbNoInt.set(0);
  cmbPaisSuc.set(0);
  cmbEstadoSuc.set(0);
  $("#btnAgregarLocacion").prop("disabled", false);
});

$("#txtarea").change(()=> {
  $("#invalid-nombreSuc").css("display", "none");
  $("#txtarea").removeClass("is-invalid");
});

function validEmptyInput(item, invalid = null) {
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

function validarUnicaSucursal(item) {
  var valor = item.value;
  $.ajax({
    url: "functions/validarSucursal.php",
    data: {data: valor },
    dataType: "json",
    success: function (data) {
    },
    error: function(data) {
      console.log(data);
      if (data.responseText != 'exito') {
        item.nextElementSibling.innerText =
          "La sucursal ya esta en el registro.";
        item.nextElementSibling.style.display = "block";
        item.classList.add("is-invalid");
      } else {
        item.nextElementSibling.innerText = "La sucursal debe tener un nombre.";
        item.nextElementSibling.style.display = "none";
        item.classList.remove("is-invalid");
      }
    }
  });
}

function validaNumTelefono(evt, input) {
  var key = window.Event ? evt.which : evt.keyCode;
  if (key == 8 || key == 46) {
    $("#result1").val($("#txtarea10").val().length);
    $("#result1").addClass("mui--is-not-empty");
    var valor = $("#result1").val();
    if (valor < 7 || valor > 10) {
      $("#invalid-invalid-telSuc").css("display", "block");
      $("#txtarea10").addClass("is-invalid");
    } else {
      $("#invalid-invalid-telSuc").css("display", "block");
      $("#txtarea10").removeClass("is-invalid");
    }
  } else {
    $("#result1").val($("#txtarea10").val().length);
    $("#result1").addClass("mui--is-not-empty");
    var valor = $("#result1").val();
    if (valor < 7 || valor > 10) {
      $("#invalid-invalid-telSuc").css("display", "block");
      $("#txtarea10").addClass("is-invalid");
    } else {
      $("#invalid-invalid-telSuc").css("display", "block");
      $("#txtarea10").removeClass("is-invalid");
      return false;
    }
  }
}

// AGREGA PERSONAL
$("#btnAgregarPersonal").on("click", function () {
  roles = cmbRoles.selected();
  if ($("#agregarEmpleado")[0].checkValidity() && roles.length != 0) {
    $("#btnAgregarPersonal").prop("disabled", true);
    var badNombre =
      $("#invalid-nombre").css("display") === "block" ? false : true;
    var badPrimerApellido =
      $("#invalid-primerApellido").css("display") === "block" ? false : true;
    var badGenero =
      $("#invalid-genero").css("display") === "block" ? false : true;
    var badEstado =
        $("#invalid-estado").css("display") === "block" ? false : true;
    if (
      badNombre &&
      badPrimerApellido &&
      badGenero &&
      badEstado
    ) {
      var nombre = $("#txtNombre").val().trim();
      var apellido = $("#txtPrimerApellido").val().trim();
      var genero = $("#cmbGenero").val();
      var estado = $("#cmbEstado").val();
      
      if (!$("#txtNombre").val()) {
        console.log('nombre');
        $("#txtNombre")[0].reportValidity();
        $("#txtNombre")[0].setCustomValidity("Completa este campo.");
        $("#btnAgregarPersonal").prop("disabled", false);
        return;
      } else if (!$("#txtPrimerApellido").val()) {
        console.log('ape');
        $("#txtPrimerApellido")[0].reportValidity();
        $("#txtPrimerApellido")[0].setCustomValidity("Completa este campo.");
        $("#btnAgregarPersonal").prop("disabled", false);
        return;
      } else if (!$("#cmbGenero").val()) {
        console.log('gen');
        $("#cmbGenero")[0].reportValidity();
        $("#cmbGenero")[0].setCustomValidity("Completa este campo.");
        $("#btnAgregarPersonal").prop("disabled", false);
        return;
      } else if (!$("#cmbEstado").val()) {
        console.log('est');
        $("#cmbGenero")[0].reportValidity();
        $("#cmbGenero")[0].setCustomValidity("Completa este campo.");
        $("#btnAgregarPersonal").prop("disabled", false);
        return;
      } else {
        console.log('bien');
        $.ajax({
          url: "functions/agregarEmpleado.php",
          data: {
            nombre: nombre,
            apellido: apellido,
            genero: genero,
            roles: roles,
            estado: estado
          },
          success: function (data, status, xhr) {
            $("#agregar_Empleado").modal("toggle");
            $("#agregarEmpleado").trigger("reset");
            loadComboVendedor();
            Lobibox.notify("success", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top", //or 'center bottom'
              icon: true,
              //img: '<i class="fas fa-check-circle"></i>',
              img: "../../../img/timdesk/checkmark.svg",
              msg: "¡Registro agregado!",
            });
          },
          error: function (error) {
            $("#btnAgregarPersonal").prop("disabled", false);
            Lobibox.notify("warning", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../img/timdesk/warning_circle.svg",
              img: null,
              msg: error,
            });
          },
        });
      }
    }
  } else {
    $("#btnAgregarPersonal").prop("disabled", false);
    if (!$("#txtNombre").val()) {
      $("#invalid-nombre").css("display", "block");
      $("#txtNombre").addClass("is-invalid");
    }
    if (!$("#txtPrimerApellido").val()) {
      $("#invalid-primerApellido").css("display", "block");
      $("#txtPrimerApellido").addClass("is-invalid");
    }
    if (!$("#cmbGenero").val()) {
      $("#invalid-genero").css("display", "block");
    }
    if (!$("#cmbEstado").val()) {
      $("#invalid-estado").css("display", "block");
    }
    if (cmbRoles.selected().length === 0) {
      $("#invalid-roles").css("display", "block");
    }
  }
});

$("#btnCancelar_newVendedor").on("click", ()=>{
  $("#agregarEmpleado").trigger("reset");
  $("#invalid-nombre").css("display", "none");
  $("#txtNombre").removeClass("is-invalid");
  $("#invalid-primerApellido").css("display", "none");
  $("#txtPrimerApellido").removeClass("is-invalid");
  $("#invalid-genero").css("display", "none");
  $("#invalid-estado").css("display", "none");
  cmbRoles.set([1]);
  cmbGenero.set(0);
  cmbEstadoE.set(0);
  $("#btnAgregarPersonal").prop("disabled", false);
});

$("#agregar_Empleado").on("hidden.bs.modal", ()=>{
  $("#agregarEmpleado").trigger("reset");
  $("#invalid-nombre").css("display", "none");
  $("#txtNombre").removeClass("is-invalid");
  $("#invalid-primerApellido").css("display", "none");
  $("#txtPrimerApellido").removeClass("is-invalid");
  $("#invalid-genero").css("display", "none");
  $("#invalid-estado").css("display", "none");
  cmbRoles.set([1]);
  cmbGenero.set(0);
  cmbEstadoE.set(0);
  $("#btnAgregarPersonal").prop("disabled", false);
});

$("#txtNombre").on("change", ()=>{
  $("#invalid-nombre").css("display", "nonoe");
  $("#txtNombre").removeClass("is-invalid");
});

$("#txtPrimerApellido").on("change", ()=>{
  $("#invalid-primerApellido").css("display", "none");
  $("#txtPrimerApellido").removeClass("is-invalid");
});

// AGREGA PRODUCTOS
$("#btnAgregarProducto").on("click", function () {
  if ($("#agregarProductoForm")[0].checkValidity()) {
    $("#btnAgregarProducto").prop("disabled", true);
    var badProducto =
      $("#invalid-nombreProducto").css("display") === "block" ? false : true;
    var badTipo =
      $("#invalid-clave").css("display") === "block" ? false : true;
    var badClave =
      $("#invalid-clave").css("display") === "block" ? false : true;
    if (
      badProducto &&
      badClave &&
      badTipo
      ) {
      var producto = $("#txtProducto").val().trim();
      var clave = $("#txtClave").val().trim();
      var tipo = $("#cmbTipoProducto").val();
      let contadorImpuesto = 0;

      let existenciaFabricacion = $("#txtCostoUniFabri").val();
      let unidadSAT = $("#txtIDUnidadSATAAA").val();
      let idSucursal = $("#chosenSucursal").val();

      if(unidadSAT == null || unidadSAT == ''){
        unidadSAT = 1;
      }

      var idImpuestosArray = {};
      var tasaImpuestosArray = {};
      $.each($("#agregarProductoForm").serializeArray(), function (i, element) {

        if(element.name.substring(0, 11) == 'idimpuesto_'){
          idImpuestosArray[element.name] = element.value;
          contadorImpuesto++;
        }

        if(element.name.substring(0, 13) == 'tasaimpuesto_'){
          tasaImpuestosArray[element.name] = element.value;
        }

      });
      
      if (!$("#txtProducto").val()) {
        console.log('producto');
        $("#txtProducto")[0].reportValidity();
        $("#txtProducto")[0].setCustomValidity("Completa este campo.");
        $("#btnAgregarProducto").prop("disabled", false);
        return;
      } else if (!$("#txtClave").val()) {
        console.log('clave');
        $("#txtClave")[0].reportValidity();
        $("#txtClave")[0].setCustomValidity("Completa este campo.");
        $("#btnAgregarProducto").prop("disabled", false);
        return;
      }  else if (!$("#cmbTipoProducto").val()) {
        console.log('clave');
        $("#cmbTipoProducto")[0].reportValidity();
        $("#cmbTipoProducto")[0].setCustomValidity("Completa este campo.");
        $("#btnAgregarProducto").prop("disabled", false);
        return;
      } else {
        console.log('bien');
        $.ajax({
          url: "functions/agregarProducto.php",
          data: {
            nombre: producto,
            clave: clave,
            tipo: tipo,
            cliente: $("#chosen").val(),
            existenciaFabricacion: existenciaFabricacion,
            unidadSat: unidadSAT,
            idSucursal: idSucursal,
            idImpuestosArray: idImpuestosArray,
            tasaImpuestosArray: tasaImpuestosArray
          },
          success: function (data, status, xhr) {
            console.log(data);
            console.log(status);
            console.log(xhr);
            $("#agregar_Producto").modal("toggle");
            $("#btnAgregarProducto").trigger("reset");
            $("#addImpuesto").html("");
            loadComboProducto($("#chosen").val());
            Lobibox.notify("success", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top", //or 'center bottom'
              icon: true,
              //img: '<i class="fas fa-check-circle"></i>',
              img: "../../img/timdesk/checkmark.svg",
              msg: "¡Registro agregado!",
            });
          },
          error: function (error) {
            $("#btnAgregarProducto").prop("disabled", false);
            $(this).prop("disabled", false);
            Lobibox.notify("warning", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../img/timdesk/warning_circle.svg",
              img: null,
              msg: error,
            });
          },
        });
      }
    }
  } else {
    $("#btnAgregarProducto").prop("disabled", false);
    if (!$("#txtProducto").val()) {
      $("#invalid-nombreProducto").css("display", "block");
      $("#txtProducto").addClass("is-invalid");
    }
    if (!$("#txtClave").val()) {
      $("#invalid-clave").css("display", "block");
      $("#txtClave").addClass("is-invalid");
    }
    if (!$("#cmbTipoProducto").val()) {
      $("#invalid-tipoProd").css("display", "block");
      $("#cmbTipoProducto").addClass("is-invalid");
    }
  }
});

function escribirNombreProd() {
  var valor = document.getElementById("txtProducto").value;
  console.log("Valor nombre: " + valor);
  $.ajax({
    url: "functions/validarNombreProducto.php",
    data: { data: valor },
    dataType: "json",
    success: function (data) {
      console.log("respuesta nombre valida: ", data);
      /* Validar si ya existe el identificador con ese nombre*/
      if (data.responseText != 'exito') {
        $("#invalid-nombreProd").css("display", "block");
        $("#invalid-nombreProd").text("El nombre ya esta en el registro.");
        $("#txtNombre").addClass("is-invalid");
      } else {
        if (!valor) {
          $("#invalid-nombreProd").css("display", "block");
          $("#invalid-nombreProd").text("El producto debe tener un nombre.");
          $("#txtNombre").addClass("is-invalid");
        } else {
          $("#invalid-nombreProd").css("display", "none");
          $("#txtNombre").removeClass("is-invalid");
        }
      }
    },
    error: function(data) {
      console.log(data);
      /* Validar si ya existe el identificador con ese nombre*/
      if (data.responseText != 'exito') {
        $("#invalid-nombreProducto").css("display", "block");
        $("#invalid-nombreProducto").text("El nombre ya esta en el registro.");
        $("#txtProducto").addClass("is-invalid");
      } else {
        if (!valor) {
          $("#invalid-nombreProducto").css("display", "block");
          $("#invalid-nombreProducto").text("El producto debe tener un nombre.");
          $("#txtProducto").addClass("is-invalid");
        } else {
          $("#invalid-nombreProducto").css("display", "none");
          $("#txtProducto").removeClass("is-invalid");
        }
      }
    }
  });
}

$("#btnCancelar_newProducto").on("click", ()=>{
  $("#agregarProductoForm").trigger("reset");
  $("#invalid-nombreProducto").css("display", "none");
  $("#txtProducto").removeClass("is-invalid");
  $("#invalid-clave").css("display", "none");
  $("#txtClave").removeClass("is-invalid");
  $("#invalid-tipoProd").css("display", "none");
  cmbTipoProducto.set(0);
  $("#btnAgregarProducto").prop("disabled", false);
});

let inactivarEvento = 0;
$("#agregar_Producto").on("hidden.bs.modal", ()=>{
  if(inactivarEvento == 0){
    $("#agregarProductoForm").trigger("reset");
    $("#invalid-nombreProducto").css("display", "none");
    $("#txtProducto").removeClass("is-invalid");
    $("#invalid-clave").css("display", "none");
    $("#txtClave").removeClass("is-invalid");
    $("#invalid-tipoProd").css("display", "none");
    cmbTipoProducto.set(0);
    $("#btnAgregarProducto").prop("disabled", false);
  }
  inactivarEvento = 0;
});

$("#txtProducto").change(()=>{
  $("#invalid-nombreProducto").css("display", "none");
  $("#txtProducto").removeClass("is-invalid");
});

$("#txtClave").change(()=>{
  $("#invalid-clave").css("display", "none");
  $("#txtClave").removeClass("is-invalid");
});

function escribirClave() {
  var valor = $("#txtClave").val();
  $.ajax({
    url: "functions/validarClaveProducto.php",
    data: { data: valor.toUpperCase() },
    dataType: "json",
    success: function (data) {
      console.log("respuesta clave interna valida: ", data);
      /* Validar si ya existe el identificador con ese nombre*/
      if (data.responseText != 'exito') {
        $("#invalid-clave").css("display", "block");
        $("#invalid-clave").text(
          "La clave interna ya existe."
        );
        $("#txtClave").addClass("is-invalid");
      } else {
        if (!valor) {
          $("#invalid-clave").css("display", "block");
          $("#invalid-clave").text("El producto debe tener un nombre.");
          $("#txtClave").addClass("is-invalid");
        } else {
          $("#invalid-clave").css("display", "none");
          $("#txtClave").removeClass("is-invalid");
        }
      }
    },
    error: function(data) {
      console.log(data);
      /* Validar si ya existe el identificador con ese nombre*/
      if (data.responseText != 'exito') {
        $("#invalid-clave").css("display", "block");
        $("#invalid-clave").text(
          "La clave interna ya existe"
        );
        $("#txtClave").addClass("is-invalid");
      } else {
        if (!valor) {
          $("#invalid-clave").css("display", "block");
          $("#invalid-clave").text("El producto debe tener un nombre.");
          $("#txtClave").addClass("is-invalid");
        } else {
          $("#invalid-clave").css("display", "none");
          $("#txtClave").removeClass("is-invalid");
        }
      }
    }
  });
}

  $("#chosen").change(function() {
    gCliente = $("#chosen").val();
    if (gCliente != 'add' && gCliente > 0) {
      $.ajax({
        type: 'POST',
        url: 'functions/razon_social.php',
        data: {
          idCliente: gCliente
        },
        success: function(data) {
          console.log(data);
          $("#cmbRazon").val(data);
        }
      });
      validaClienteSucursal();
      cargarCMBDireccionesEnvio("cmbDireccionEntrega",gCliente);
      loadComboProducto(gCliente);
    }

    if(gCliente == 'add'){
      $("#agregar_Cliente_50").modal("toggle");
      selectCliente.set(0);
    }
  });

  $("#chosenSucursal").change(function() {
    gSucursal = $("#chosenSucursal").val();
    console.log(gSucursal);
    if(gSucursal =='add'){
    $('#agregar_Locacion').modal('show');
    selectSucursal.set(0);
  } 
    validaClienteSucursal();
  });

$("#cmbVendedor").change(function() {
  empleado = $("#cmbVendedor").val();
  console.log(empleado);
  if(empleado =='add'){
  $('#agregar_Empleado').modal('show');
  selectVendedor.set(0);
} 
});

  //1 cliente, 2 sucursal
  function validaClienteSucursal() {
    if (gCliente > 0 && (gSucursal > 0 || gSucursal == 'add')) {
      $("#txtPiezas").prop("disabled", false);
      $("#agregarProducto").prop("disabled", false);
      $("#chosenProducto").prop('disabled', false);
      $('#mostrar_todos').prop('disabled', false);
      selectProductos.enable();
    } else {
      $("#txtPiezas").prop("disabled", true);
      $("#agregarProducto").prop("disabled", true);
      $("#chosenProducto").prop('disabled', true);
      $('#mostrar_todos').prop('disabled', true);
      selectProductos.disable();
    }
    $("#txtPrecio").val("");
  }

  
  $("#chosenProducto").change(function() {
    if(ban_chosen_product === 0){
        var idProducto = $("#chosenProducto").val();
        var idCliente = $("#chosen").val();

        $("#txtPrecio").prop("readonly", false);

        if(idProducto =='add'){
        $('#agregar_Producto').modal('show');
        selectProductos.set(0);
        cargarCMBImpuestos("1", "cmbImpuestos");
        cargarCMBTasaImpuestos("1","cmbTasaImpuestos");
        }else{
        $.ajax({
            type: 'POST',
            url: 'functions/valoresCotizacion.php',
            data: {
            idProducto: idProducto,
            idCliente: idCliente
            },
            success: function(data) {
            var datos = JSON.parse(data);

            $("#NuevoProducto").val("0");
            if ($.trim(datos.Precio) == '') {
                //$("#txtPrecio").prop("readonly", false);
                $("#NuevoProducto").val("1");
            }
            $("#txtPrecio").val(datos.Precio);
            $("#txtImpuestos").val(datos.Impuestos);
            $("#txtClaveUnidad").val(datos.ClaveUnidad);
            if(idProducto.trim() != ""){
                $("#actualizarUnidad").html(" - " + datos.ClaveUnidad);
            }
            $("#TipoProducto").val(datos.tipoProducto);
            
            }
        });
        }
    }
  });


  //url: "../catalogos/inventarios_productos/php/funciones.php",
function cargarCMBImpuestos(data, input) {
  var html = "";
  var selected;
  $.ajax({
    url: "../inventarios_productos/php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_impuestos" },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta impuestos: ", respuesta);

      $.each(respuesta, function (i) {
        if (data === respuesta[i].PKImpuesto) {
          selected = "selected";
        } else {
          selected = "";
        }

        html +=
          '<option value="' +
          respuesta[i].PKImpuesto +
          '" ' +
          selected +
          ' data-tipo="' +
          respuesta[i].FKTipoImpuesto +
          '" data-importe="' +
          respuesta[i].FKTipoImporte +
          '">' +
          respuesta[i].Nombre +
          "</option>";
      });

      CargarSlimImpuestos();

      $("#" + input + "").html(html);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function cargarCMBTasaImpuestos(data, input) {
  var valor = data;
  console.log("PKImpuestos: " + valor);

  var html = "";
  var selected;

  $.ajax({
    url: "../inventarios_productos/php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_tasa_impuestos", data: valor },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta tasas: ", respuesta);

      $.each(respuesta, function (i) {
        if (data === respuesta[i].PKImpuesto_tasas) {
          selected = "selected";
        } else {
          selected = "";
        }

        html +=
          '<option value="' +
          respuesta[i].PKImpuesto_tasas +
          '" ' +
          selected +
          ">" +
          respuesta[i].Tasa +
          "</option>";
      });

      $("#" + input + "").html(html);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

  function cambiarImpuestoValores(idImpuesto) {
    $.ajax({
      type: 'POST',
      url: 'functions/valoresImpuesto.php',
      data: {
        idImpuesto: idImpuesto
      },
      success: function(data) {
        var datos = JSON.parse(data);

        if (datos.tipoImpuesto == 1) {
          $("#trasladado").css("display", "block");
          $("#retenciones").css("display", "none");
          $("#local").css("display", "none");
          $("#txtTipoImpuesto").val("1");
        }
        if (datos.tipoImpuesto == 2) {
          $("#trasladado").css("display", "none");
          $("#retenciones").css("display", "block");
          $("#local").css("display", "none");
          $("#txtTipoImpuesto").val("2");
        }
        if (datos.tipoImpuesto == 3) {
          $("#trasladado").css("display", "none");
          $("#retenciones").css("display", "none");
          $("#local").css("display", "block");
          $("#txtTipoImpuesto").val("3");
        }

        $("#txtOperacion").val(datos.Operacion);

        $("#txtImporteImpuesto").attr("readonly", false);

        $("#areaimpuestos").html(datos.TasasImpuestos);

        if (datos.tipoImporte == 1) {
          $("#etiquetaImpuesto").text("Tasa:");
        }
        if (datos.tipoImporte == 2) {
          $("#etiquetaImpuesto").text("Importe:");
        }
        if (datos.tipoImporte == 3) {
          $("#etiquetaImpuesto").html("Tasa:");
          $("#txtImporteImpuesto").attr("readonly", true);
        }

        $("#txtTipoTasa").val(datos.tipoImporte);

      }
    });
  }

  let cmbImpuestos;
  function CargarSlimImpuestos() {
  cmbImpuestos = new SlimSelect({
    select: "#cmbImpuestos",
    deselectLabel: '<span class="">✖</span>',
  });

  CargarSlimTasaImpuestos();
}

let tasaImpuestos;
function CargarSlimTasaImpuestos() {
  tasaImpuestos = new SlimSelect({
    select: "#cmbTasaImpuestos",
    deselectLabel: '<span class="">✖</span>',
  });
}

function cambioImpuesto(producto) {
  var FKImpuesto = document.getElementById("cmbImpuestos").value;
  cargarCMBTasaImpuestos(FKImpuesto, "cmbTasaImpuestos");

  var tipo =
    document.getElementById("cmbImpuestos").options[
      document.getElementById("cmbImpuestos").selectedIndex
    ].dataset.tipo;
  var importe =
    document.getElementById("cmbImpuestos").options[
      document.getElementById("cmbImpuestos").selectedIndex
    ].dataset.importe;

  console.log("Tipo:" + tipo);
  console.log("Importe:" + importe);

  if (tipo == 1) {
    $("#trasladado").css("display", "block");
    $("#retenciones").css("display", "none");
    $("#local").css("display", "none");
    $("#txtTipoImpuesto").val("1");
  }
  if (tipo == 2) {
    $("#trasladado").css("display", "none");
    $("#retenciones").css("display", "block");
    $("#local").css("display", "none");
    $("#txtTipoImpuesto").val("2");
  }
  if (tipo == 3) {
    $("#trasladado").css("display", "none");
    $("#retenciones").css("display", "none");
    $("#local").css("display", "block");
    $("#txtTipoImpuesto").val("3");
  }

  $("#cmbTasaImpuestos").attr("readonly", false);

  var select = `<select class="cmbSlim" name="cmbTasaImpuestos" id="cmbTasaImpuestos" required="">
              </select> `;

  var inputNumber = `<input type='number' min='0' value='' name='cmbTasaImpuestos' id='cmbTasaImpuestos' class='form-control'>`;

  if (importe == 1) {
    $("#etiquetaImpuesto").text("Tasa:");
    $("#areaimpuestos").html(select);
    CargarSlimTasaImpuestos();
  }
  if (importe == 2) {
    $("#etiquetaImpuesto").text("Importe:");
    $("#areaimpuestos").html(inputNumber);
  }
  if (importe == 3) {
    $("#etiquetaImpuesto").html("Tasa:");
    $("#areaimpuestos").html(inputNumber);
    $("#cmbTasaImpuestos").attr("readonly", true);
  }

  $("#txtTipoTasa").val(importe);

  /*console.log("Valor impuesto" + FKImpuesto);
  $.ajax({
    url: "../../../inventarios_productos/php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "validar_impuestoProducto",
      data: producto,
      data2: FKImpuesto,
    },
    dataType: "json",
    success: function (data) {
      console.log("respuesta impuesto validado: ", data);

      if (parseInt(data[0]["existe"]) == 1) {

        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/notificacion_error.svg",
          msg: "El impuesto ya ha sido agregado.",
          sound: '../../../../../sounds/sound4'
        });
        console.log("¡Ya existe!");
      } else if (parseInt(data[0]["existe"]) == 2) {
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/notificacion_error.svg",
          msg: "No es posible añadir el impuesto excento.",
          sound: '../../../../../sounds/sound4'
        });
      } else if (parseInt(data[0]["existe"]) == 3) {
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/notificacion_error.svg",
          msg: "Ya se posee un impuesto de tipo excento.",
          sound: '../../../../../sounds/sound4'
        });
      } else {
        var nota = document.getElementById("notaImpuesto");
        nota.setAttribute("type", "hidden");

        console.log("¡No existe!");
      }
    },
  });*/
}

let contadorImpuestos = 1;
function validarImpuesto(){

  let fila;
  let tipoImpuesto = $("#txtTipoImpuesto").val();
  let idImpuesto = $("#cmbImpuestos").val();
  let nombreImpuesto = $("#cmbImpuestos").find('option:selected').text();

  var elementType = $("#cmbTasaImpuestos").get(0).tagName;

  let tasaImpuesto;
  if(elementType === "SELECT"){
    tasaImpuesto = $("#cmbTasaImpuestos").find('option:selected').text();
  }
  if(elementType === "INPUT"){
    tasaImpuesto = $("#cmbTasaImpuestos").val();

    if((tasaImpuesto.trim() == '' || tasaImpuesto.trim() == 0) && idImpuesto != 5 && idImpuesto != 16){
      Lobibox.notify("warning", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../img/timdesk/notificacion_error.svg",
        msg: "Necesitas agregar un valor al importe.",
        sound: '../../../sounds/sound4'
      });
      return;
    }
  }


  let idsImpuestos = document.querySelectorAll('.getIDImpuesto')

  let id, encontrados = 0, band1 = 0, band2 = 0;
  idsImpuestos.forEach((item) => {
    id = item.id.split('_');
    //console.log(id[1]);

    if(id[1] == idImpuesto){
      encontrados++;
    }

    if(id[1] == 1 && idImpuesto == 5){
      band1 = 1;
    }
    if(id[1] == 5 && idImpuesto == 1){
      band1 = 1;
    }

    if((id[1] == 2 || id[1] == 3) && idImpuesto == 16){
      band2 = 1;
    }
    if(id[1] == 16 && idImpuesto == 2){
      band2 = 1;
    }
    if(id[1] == 16 && idImpuesto == 3){
      band2 = 1;
    }

  });

  if(encontrados > 0){
      Lobibox.notify("warning", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../img/timdesk/notificacion_error.svg",
        msg: "No puedes volver a agregar el mismo impuesto.",
        sound: '../../../sounds/sound4'
      });
      return;
  }

  if(band1 > 0){
      Lobibox.notify("warning", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../img/timdesk/notificacion_error.svg",
        msg: "No es posible añadir el impuesto exento.",
        sound: '../../../sounds/sound4'
      });
      return;
  }

  if(band2 > 0){
      Lobibox.notify("warning", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../img/timdesk/notificacion_error.svg",
        msg: "No es posible añadir el impuesto exento.",
        sound: '../../../sounds/sound4'
      });
      return;
  }

  let nombreTipoImpuesto = '';
  if(tipoImpuesto == 1){
    nombreTipoImpuesto = 'Trasladado';
  }
  if(tipoImpuesto == 2){
    nombreTipoImpuesto = 'Retención';
  }
  if(tipoImpuesto == 3){
    nombreTipoImpuesto = 'Local';
  }
  
  fila = '<tr id="fila_' + idImpuesto + '" class="getIDImpuesto">' +
            '<td>' + nombreImpuesto + '</td>' +
            '<td>' + nombreTipoImpuesto + '</td>' +
            '<td>' + tasaImpuesto + '</td>' +
            '<td><img class="btnEdit" src="../../img/timdesk/delete.svg" id="btnEliminarImpuesto" onclick="eliminarImpuesto(' + idImpuesto + ');">' +
            '<input type="hidden" value="' + idImpuesto + '" id="idimpuesto_' + contadorImpuestos + '" name="idimpuesto_' + contadorImpuestos + '" />' +
            '<input type="hidden" value="' + tasaImpuesto + '" id="tasaimpuesto_' + contadorImpuestos + '" name="tasaimpuesto_' + contadorImpuestos + '" />' +
            '</td>'
          '</tr>'
  $("#addImpuesto").append(fila);
  contadorImpuestos++;
}


function eliminarImpuesto(fila){
  $("#fila_"+fila).closest('tr').remove();
}

  function cargarCMBCondicionPago(data, input) {
    var html = "", selected = "";

    $.ajax({
      url: "php/funciones.php",
      data: { clase: "get_data", funcion: "get_cmb_condicionPago" },
      dataType: "json",
      success: function (respuesta) {
        html += '<option data-placeholder="true"></option>';

        $.each(respuesta, function (i) {
          if(data === respuesta[i].PKCondicion){
            selected = 'selected';
          }else{
            selected = '';
          }
          html += '<option value="'+respuesta[i].PKCondicion+'" '+selected+'>'+respuesta[i].Condicion+'</option>';
        });

        $("#" + input + "").html(html);
      },
      error: function (error) {
        console.log(error);
      },
    });
  }

  function cargarCMBDireccionesEnvio(input, cliente) {
    var html = "";
    var selected, direccionEnvio;
    setTimeout(function(){
      $.ajax({
          url: "php/funciones.php",
          data: {
            clase: "get_data",
            funcion: "get_DireccionesEnviosCliente",
            data: cliente,
          },
          dataType: "json",
          success: function (data) {
            direccionEnvio = data[0].idPredeterminada;
          },
        })
      },100
    );

    setTimeout(function(){
        $.ajax({
          url: "php/funciones.php",
          data: { clase: "get_data", funcion: "get_cmb_direccionesEnvio", data: cliente },
          dataType: "json",
          success: function (respuesta) {
            html += '<option data-placeholder="true"></option>';
            if(respuesta.pop() != 6){
              html += '<option value="1">Pendiente de confirmar</option>';
            }

            $.each(respuesta, function (i) {
              if(respuesta[i].sucursal.substr(-4) == " -  "){
                html +=
                `<option value="${respuesta[i].id}">${respuesta[i].sucursal+"Desconocido"}</option>`;
              }else{
                html +=
                `<option value="${respuesta[i].id}">${respuesta[i].sucursal}</option>`;
              }
              
            });
            html += '<option value="add" style="background-color: #15589B;  color:white; text-align:center; width:100%;">Añadir dirección</option>';
            $("#" + input + "").html(html);
            $("#" + input + "").val(direccionEnvio);
          },
          error: function (error) {
            console.log(error);
          },
        });
      },200
    );
  }

  $("#cmbImpuestos").change(function() {
    var idImpuesto = $("#cmbImpuestos").val();
    cambiarImpuestoValores(idImpuesto);
  });

  //Previene que se use enter para ingresar el formulario.
  jQuery(function($) { // DOM ready

    $('form').on('keydown', function(e) {
      if (e.which === 13 && !$(e.target).is('textarea')) {
        e.preventDefault();
      }
    });

    loadComboSucursal();
    loadComboCliente();
    loadComboVendedor();
    

  });

  function contarDecimales(n){
   var splited = n.split(".");
    ///Si trae decimales devuelve el numero de decimales que trae n
    if(splited.length>1){
      return(splited[1].length);
    }else{
      ///Si no trae decimales regresa 2 por defecto 2 decimales.
      return 2;
    }
  }

  let nDecimales = 2;
  $("#agregarProducto").click(function() {
    ban_chosen_product = 1;
    var idProducto = parseInt($("#chosenProducto").val());
    var Producto = $("#chosenProducto").children("option:selected").text();
    var Piezas = parseInt($("#txtPiezas").val());
    let PrecioObj = numeral($("#txtPrecio").val());
    let Precio = PrecioObj.value();
    var TotalProducto, nuevo_elemento, Piezas_old, TotalProducto_old = 0,
      nuevo_impuesto;
    var SubtotalNum = numeral($("#Subtotal").html());
    var Subtotal, Operacion, DetalleImpuesto;
    var PrecioF, TotalProductoF, TotalProducto_format;
    var IVAProducto, IVAProductoF, IVATotalProductoF, IVAant, IVATotalProducto;
    var ImpuestosCompleto = $("#txtImpuestos").val();
    let NuevoProducto = $("#NuevoProducto").val();
    let UnidadMedida = $("#txtClaveUnidad").val();
    let tipoProducto = $("#TipoProducto").val();
    let PKsucursal = $("#chosenSucursal").val();
    let existencia = 0;
    let arrayImpuestos = [];
    let indexImpuestos = 0;
    let ctaIVA = 0, ctaIEPSFijo = 0, ctaIEPSTasa = 0;

    let aux = Precio.toString();
    Precio = validarMoneda(aux);

    ///Si el umero de ecimales es mayor al anterior se cambia.
    nDecimales = nDecimales < contarDecimales(Precio) ? contarDecimales(Precio) : nDecimales;

    console.log("Decimales: " + nDecimales);
    Precio = parseFloat(Precio).toFixed(nDecimales);
    if (isNaN(idProducto)) {
      Lobibox.notify("error", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../img/timdesk/notificacion_error.svg",
        msg: "¡El producto es necesario!",
      });
      return;
    }

    if (Piezas < 1 || isNaN(Piezas)) {
      Lobibox.notify("error", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../img/timdesk/notificacion_error.svg",
        msg: "¡El número de piezas no puede ser menor a 0!",
      });
      return;
    }

    //inactivar controles de sucursal y cliente
    if (gSucursal < 1) {
      Lobibox.notify("error", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../img/timdesk/notificacion_error.svg",
        msg: "¡Es necesario añadir una sucursal!",
      });
      return;
    }

    if (gCliente < 1) {
      Lobibox.notify("error", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../img/timdesk/notificacion_error.svg",
        msg: "¡El cliente es necesario!",
      });
      return;
    }

    /* if(gtipoProducto == 0){

        if(tipoProducto == 5){
          gtipoProducto = 2;
          $("#TipoProductoGeneral").val(2);
        }
        else{
          gtipoProducto = 1;
          $("#TipoProductoGeneral").val(1);
        }
    } */

    /* modificación: se quitó la validación que evita ingresar productos con servicios 
    if(gtipoProducto == 1){
      
      if(tipoProducto == 5){
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/notificacion_error.svg",
            msg: "Sólo puedes agregar productos de tipo: activo fijo, compuesto, consumible, materia prima o producto.",
          });
          return;
      }

    }

    if(gtipoProducto == 2){
      
      if(tipoProducto != 5){
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/notificacion_error.svg",
            msg: "Sólo puedes agregar servicios.",
          });
          return;
      }

    } */

    selectCliente.disable();
    selectSucursal.disable();

      $.ajax({
      url:"php/funciones.php",
      data:{clase:"get_data", funcion:"get_InventarioSucursal",data:PKsucursal, data2:idProducto},
      dataType:"json",
      success:function(respuesta){
        if (respuesta[0].isServicio == '5'){
          existencia = 'N/A';
        }else{
          existencia = respuesta[0].StockExistencia;
        }

        var impuestoXProductoIVA, impuestoCantidadIVA, idImpuestoIVA, cantidadAdicionalIVA = 0;

        if ($('#idProducto_' + idProducto).length) {
         
          //cuando ya se agregó el producto 2
          Piezas_old = parseInt($("#piezasUnic_" + idProducto).val());
          TotalProducto_format = numeral($("#totalproducto_" + idProducto).html());
          TotalProducto_old = TotalProducto_format.value();

          console.log("Actualizando producto");
          var PiezasImp = Piezas;
          //Piezas = Piezas + Piezas_old;
          TotalProducto = (Piezas * Precio).toFixed(nDecimales);

          PrecioF = $.number(Precio, nDecimales, '.', '');
          TotalProductoF = $.number(TotalProducto, nDecimales, '.', ',');

          var impuestosOld = $(".impuestos_" + idProducto).html();

          var impuestoXProducto, impuestoCantidad, arrayImp, TipoTasa;
          
          var idImpuestoOld, totImpIndividual, impuestoTotalant, impuestoTotNuevo, impuestoTotNuevoF;
          //console.log("Ahora Impuestos");
          /*
          $('#idProducto_' + idProducto).empty();
          nuevo_elemento = "<td id='nombreproducto_" + idProducto + "'>" + Producto + "</td>" +
            "<td id='piezas_" + idProducto + "'><input type='number' id='piezasUnic_" + idProducto +
            "' name='inp_piezas[]' value='" + Piezas + "' class='modificarnumero numeros-solo form-control' min='1' >" + "</td>" +
            "<input type='hidden' id='piezaAnt_" + idProducto + "' value='" + Piezas + "' />" +
            "<input type='hidden' name='inp_productos[]' value='" + idProducto + "' />" +
            "<td>" + UnidadMedida + "</td>" +
            "<td id='precio_" + idProducto + "'> <input type='number' name='inp_precio[]' id='precioUnic_" + idProducto +
            "' value='" + PrecioF + "' class='modificarprecio numeros-solo form-control' min='0' >" + "</td>" +
            "<input type='hidden' id='precionAnt_" + idProducto + "' value='" + PrecioF + "' />" +
            "<input type='hidden' name='inp_precio[]' value='" + Precio + "' />" +
            "<td>" +
            "<span class='impuestos_" + idProducto + "'> " +
            impuestosOld +
            "</span>" +
            "</td>" +
            "<td id='totalproducto_" + idProducto + "'>" + TotalProductoF + "</td>" +
            "<td >" + existencia + "</td>"+
            "<input type='hidden' name='inp_total_producto[]' value='" + TotalProducto + "' />" +
            "<td><button type='button' class='btn eliminarProductos' id='" + idProducto +
            "'><img src='../../img/timdesk/delete.svg' width='20px' /></button></td>";
          $('#idProducto_' + idProducto).append(nuevo_elemento);

          $('.impuestos_' + idProducto + ' > span').each(function(index, span) {
            impuestoXProducto = $(this).attr("id");
            arrayImp = impuestoXProducto.split("_");
            idImpuestoOld = arrayImp[3];
            TipoTasa = arrayImp[2];
            impuestoCantidad = parseFloat($("#impAgregado_" + idProducto + "_" + idImpuestoOld).val());
            var newPieza, newPrecio;
            newPieza = $("#piezasUnic_" + idProducto).val();
            newPrecio = $("#precioUnic_" + idProducto).val();

            if (TipoTasa == 1){
              //totImpIndividual = parseFloat((PiezasImp * Precio) * (impuestoCantidad / 100));
              totImpIndividual = parseFloat(((newPieza) * (newPrecio)) * (impuestoCantidad / 100));
            }
            if (TipoTasa == 2){
              //totImpIndividual = parseFloat(PiezasImp * impuestoCantidad);
              totImpIndividual = parseFloat((newPieza) * impuestoCantidad)
            }
            if (TipoTasa == 3){
              totImpIndividual = 0.00;
            }

            impuestoTotalant = numeral($("#Impuesto_" + idImpuestoOld).html());
            impuestoTotNuevo = totImpIndividual;//totImpIndividual + impuestoTotalant.value();
            impuestoTotNuevoF = $.number(impuestoTotNuevo, 2, '.', ',');
            $("#Impuesto_" + idImpuestoOld).html(impuestoTotNuevoF);

          });
           */
          var oldPieza = $("#piezasUnic_" + idProducto).val();
          var newPiezas = Piezas + parseInt(oldPieza);
          
          $("#piezasUnic_" + idProducto).val(newPiezas);
          $("#piezasUnic_" + idProducto ).trigger( "change" );
          $("#precioUnic_" + idProducto).val(Precio);
          $("#precioUnic_" + idProducto ).trigger( "change" );
            console.log("Confirmada Edicion");
        } else {
          //cuando se ingresa un nuevo producto

          ///Redondea a 4 decimales
          TotalProducto = (Piezas * Precio).toFixed(nDecimales);
          descuento = "";

          PrecioF = $.number(Precio, nDecimales, '.', '');
          TotalProductoF = $.number(TotalProducto, nDecimales, '.', ',');

          nuevo_elemento = "<tr id='idProducto_" + idProducto + "' class='text-center'>" +
            "<td id='nombreproducto_" + idProducto + "'>" + Producto + "</td>" +
            "<td id='piezas_" + idProducto + "'><input type='number' name='inp_piezas[]' id='piezasUnic_" + idProducto +
            "' value='" + Piezas + "' class='modificarnumero numeros-solo form-control' min='1' >" + "</td>" +
            "<input type='hidden' id='piezaAnt_" + idProducto + "' value='" + Piezas + "' />" +
            "<input type='hidden' name='inp_productos[]' value='" + idProducto + "' />" +
            "<td>" + UnidadMedida + "</td>" +
            "<td id='precio_" + idProducto + "'> <input type='number' name='inp_precio[]' id='precioUnic_" + idProducto +
            "' value='" + Precio + "' class='modificarprecio decimales form-control textTable border-0' min='0' >" + "</td>" +
            "<input type='hidden' id='precionAnt_" + idProducto + "' value='" + PrecioF + "' />" +
            //"<input type='hidden' name='inp_precio[]' value='" + Precio + "' />" +
            "<td>" +
            ImpuestosCompleto +
            "</td>";

          nuevo_elemento += "<td class='decimales' id='totalproducto_" + idProducto + "'>" + TotalProductoF + "</td>" +
            "<td>" +existencia + "</td>"+
            "<input type='hidden' name='inp_total_producto[]' value='" + TotalProducto + "' />" +
            "<td><button type='button' class='btn eliminarProductos' id='" + idProducto +
            "'><img src='../../img/timdesk/delete.svg' width='20px' /></button></td>" +
            "</tr>";

          $('#lstProductos').append(nuevo_elemento);

          $('.impuestos_' + idProducto + ' > span').each(function(index, span) {

            impuestoXProducto = $(this).attr("id");
            arrayImp = impuestoXProducto.split("_");
            idImpuestoOld = arrayImp[3];

            /*arrayImpuestos[indexImpuestos] = idImpuestoOld;
            indexImpuestos++;*/


            if(idImpuestoOld == 1){

                $('.impuestos_' + idProducto + ' > span').each(function(indexIVA, spanIVA) {

                    impuestoXProductoIVA = $(this).attr("id");
                    arrayImpIVA = impuestoXProductoIVA.split("_");
                    idImpuestoIVA = arrayImpIVA[3];
                    impuestoCantidad = parseFloat($("#impAgregado_" + idProducto + "_" + idImpuestoIVA).val());
    //alert("elementos " + Piezas + " precio " + Precio  + " impuesto " + impuestoCantidad) ;
                    if(idImpuestoIVA == 2){
                      cantidadAdicionalIVA = cantidadAdicionalIVA + ((Piezas * Precio) * (impuestoCantidad / 100));
                    }
                    if(idImpuestoIVA == 3){
                      cantidadAdicionalIVA = cantidadAdicionalIVA + (Piezas * impuestoCantidad);
                    }
                });

            }
           
            /*
            arrayImpuestos.forEach(function(index) {
              
              if(index == 1){
                ctaIVA++;  
              }
              if(index == 2){
                ctaIEPSTasa++;  
              }
              if(index == 3){
                ctaIEPSFijo++;  
              }
            });

            if(ctaIVA > 0 && ctaIEPSTasa > 0){
              alert("calculo 1");
            }

            if(ctaIVA > 0 && ctaIEPSFijo > 0){
              alert("calculo 2");
            }*/

            TipoTasa = arrayImp[2];
            impuestoCantidad = parseFloat($("#impAgregado_" + idProducto + "_" + idImpuestoOld).val());

            if (TipoTasa == 1)
              totImpIndividual = parseFloat((PiezasImp * Precio) * (impuestoCantidad / 100));
            if (TipoTasa == 2 || TipoTasa == 3)
              totImpIndividual = 0.00;

            if (TipoTasa == 1) {
              var TotalImpuesto = (parseFloat(TotalProducto) + parseFloat(cantidadAdicionalIVA)) * (impuestoCantidad / 100);
               //alert("cantidad" + cantidadAdicionalIVA + "  TotalImpuesto " + TotalImpuesto + " TotalProducto " + TotalProducto);
            }
            if (TipoTasa == 2) {
              var TotalImpuesto = impuestoCantidad * Piezas;
            }
            if (TipoTasa == 3) {
              var TotalImpuesto = 0.00;
              
              if(idImpuestoOld == 5){
                cuentaIVAexento++;
              }
              if(idImpuestoOld == 13){
                cuentaISRExento++;
              }
              if(idImpuestoOld == 16){
                cuentaIEPSExento++;
              }
            }

            cantidadAdicionalIVA = 0;

            var TotalImpuestoF;
            var TotalImpuestoGen;
            Operacion = $("#OperacionUnica_" + idProducto + "_" + idImpuestoOld).val();
            DetalleImpuesto = $("#ImpuestoUnico_" + idProducto + "_" + idImpuestoOld).val();
            if ($('#lstimpuestos').find('#' + arrayImp[0] + "_" + idImpuestoOld).length == 1) {

              var sumaImpuesto = numeral($('#Impuesto_' + idImpuestoOld).html());

              TotalImpuestoGen = TotalImpuesto + parseFloat(sumaImpuesto.value());
              TotalImpuestoF = $.number(TotalImpuestoGen, 2, '.', ',');
              nuevo_impuesto = "<th colspan='5'></th>" +
                "<th style='text-align: right; color: var(--color-primario)'>" + DetalleImpuesto + "</th>" +
                "<td colspan='2' style='color: var(--color-primario)'>$ <span id='Impuesto_" + idImpuestoOld + "' name='" +
                arrayImp[1] + "_" + Operacion + "' class='ImpuestoTot'>" + TotalImpuestoF + "</span></td>";
              $('#' + arrayImp[0] + '_' + idImpuestoOld).empty();
              $('#' + arrayImp[0] + '_' + idImpuestoOld).append(nuevo_impuesto);
            } else {
              //nuevo impuesto
              TotalImpuestoF = $.number(TotalImpuesto, 2, '.', ',');
              nuevo_impuesto = "<tr id='" + arrayImp[0] + "_" + idImpuestoOld + "'>" +
                "<th colspan='5'></th>" +
                "<th style='text-align: right; color: var(--color-primario)'>" + DetalleImpuesto + "</th>" +
                "<td colspan='2' style='color: var(--color-primario)'>$ <span id='Impuesto_" + idImpuestoOld + "' name='" +
                arrayImp[1] + "_" + Operacion + "' class='ImpuestoTot'>" + TotalImpuestoF + "</span></td>" +
                "</tr>";
              $('#lstimpuestos').append(nuevo_impuesto);

            }

          });
          Subtotal = SubtotalNum.value() + parseFloat(TotalProducto) - TotalProducto_old;
          var SubtotalF = $.number(Subtotal, 2, '.', ',');
          $('#Subtotal').empty();
          $('#Subtotal').append(SubtotalF);
        }

        

        //calculo de impuestos
        var suma = 0.00,
          cantidadImp, tipoimp, arrayTipoImp;
        $('#lstimpuestos > tr').each(function(index, tr) {
          cantidadImp = numeral($(this).find(".ImpuestoTot").html());
          tipoimp = $(this).find(".ImpuestoTot").attr("name");
          arrayTipoImp = tipoimp.split("_");

          if (arrayTipoImp[0] == 1) {
            suma = suma + cantidadImp.value();
          }
          if (arrayTipoImp[0] == 2) {
            suma = suma - cantidadImp.value();
          }
          if (arrayTipoImp[0] == 3) {

            if (arrayTipoImp[1] == 1)
              suma = suma + cantidadImp.value();

            if (arrayTipoImp[1] == 2)
              suma = suma - cantidadImp.value();

          }

        });
        var subt = $("#Subtotal").text();
        subt = subt.replace(/,/g, "");
        subt = parseFloat(subt);
        //var subt = document.getElementById("Subtotal").value;
        var Total = subt + suma; /* Subtotal + suma; */
        var TotalF = $.number(Total, 2, '.', ',');
        $('#Total').empty();
        $('#Total').append(TotalF);


        //Recorre todos los elementos de la clase decimal(Precio Unitario e Importe) y le pone los decimales maximos para formatear
        $(".decimales").each(function (index, element) {
          // element == this

          /// Si Es el Importe
          if(!(this.value)){
            let precioAnt = this.textContent;
            let precioDes = ((parseFloat(precioAnt.replace(',',''))).toFixed(nDecimales));
            this.innerText = (precioDes);
          }else{
            ///Si es el precio Unitario.
            let precioAnt = this.value;
            let precioDes = (parseFloat(precioAnt)).toFixed(nDecimales);
            this.value = (precioDes);
          }
          

        });

        //AGREGAR EL PRECIO DEL PRODUCTO AL COSTO GENERAL
        //if(NuevoProducto == 1){
            $.ajax({
              type: 'POST',
              url: 'functions/agregarNuevoProducto.php',
              data: {
                idProducto: idProducto,
                idCliente: gCliente,
                costo: Precio
              },
              success: function(data) {

              },
              error: function() {

              }
            });
        //}

        //FIN AGREGAR EL PRECIO DEL PRODUCTO AL COSTO GENERAL

        selectProductos.set("");
        $('#txtPiezas').val("");
        $('#txtPrecio').val("");
        $("#actualizarUnidad").html("");
        cuenta++;
        console.log(arrayImpuestos);
        ban_chosen_product = 0;
      },
      error:function(error){
        console.log(error);
      }
    });

  });


  $("#txtPiezas,#txtPrecio").on('keydown', function(e) {
    if (e.keyCode == 13) {
      $("#agregarProducto").trigger( "click");
    /*   var idProducto = parseInt($("#chosenProducto").val());
      var Producto = $("#chosenProducto").children("option:selected").text();
      var Piezas = parseInt($("#txtPiezas").val());
      let PrecioObj = numeral($("#txtPrecio").val());
      let Precio = PrecioObj.value();
      var TotalProducto, nuevo_elemento, Piezas_old, TotalProducto_old = 0,
        nuevo_impuesto;
      var SubtotalNum = numeral($("#Subtotal").html());
      var Subtotal, Operacion, DetalleImpuesto;
      var PrecioF, TotalProductoF, TotalProducto_format;
      var IVAProducto, IVAProductoF, IVATotalProductoF, IVAant, IVATotalProducto;
      var ImpuestosCompleto = $("#txtImpuestos").val();
      let NuevoProducto = $("#NuevoProducto").val();
      let UnidadMedida = $("#txtClaveUnidad").val();
      let tipoProducto = $("#TipoProducto").val();
      let PKsucursal = $("#chosenSucursal").val();

      if (isNaN(idProducto)) {
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/notificacion_error.svg",
          msg: "¡El producto es necesario!",
        });
        return;
      }

      if (Piezas < 1 || isNaN(Piezas)) {
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/notificacion_error.svg",
          msg: "¡El número de piezas no puede ser menor a 0!",
        });
        return;
      }


      //inactivar controles de sucursal y cliente
      if (gSucursal < 1) {
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/notificacion_error.svg",
          msg: "¡Es necesario añadir una sucursal!",
        });
        return;
      }

      if (gCliente < 1) {
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/notificacion_error.svg",
          msg: "¡El cliente es necesario!",
        });
        return;
      }

      if(gtipoProducto == 0){

          if(tipoProducto == 5){
            gtipoProducto = 2;
            $("#TipoProductoGeneral").val(2);
          }
          else{
            gtipoProducto = 1;
            $("#TipoProductoGeneral").val(1);
          }
      }

      if(gtipoProducto == 1){
      
          if(tipoProducto == 5){
              Lobibox.notify("error", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../img/timdesk/notificacion_error.svg",
                msg: "Sólo puedes agregar productos de tipo: activo fijo, compuesto, consumible, materia prima o producto.",
              });
              return;
          }

        }

        if(gtipoProducto == 2){
          
          if(tipoProducto != 5){
              Lobibox.notify("error", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../img/timdesk/notificacion_error.svg",
                msg: "Sólo puedes agregar servicios.",
              });
              return;
          }

        }

      selectCliente.disable();
      selectSucursal.disable();

      $.ajax({
        url:"php/funciones.php",
        data:{clase:"get_data", funcion:"get_InventarioSucursal",data:PKsucursal, data2:idProducto},
        dataType:"json",
        success:function(respuesta){
          if (respuesta[0].isServicio == '5'){
            existencia = 0;
          }else{
            existencia = respuesta[0].StockExistencia;
          }

          if ($('#idProducto_' + idProducto).length) {
            //cuando ya se agregó el producto
            Piezas_old = parseInt($("#piezasUnic_" + idProducto).val());
            TotalProducto_format = numeral($("#totalproducto_" + idProducto).html());
            TotalProducto_old = TotalProducto_format.value();

            
            var PiezasImp = Piezas;
            Piezas = Piezas + Piezas_old;
            TotalProducto = (Piezas * Precio).toFixed(2);

            PrecioF = $.number(Precio, 2, '.', ',');
            TotalProductoF = $.number(TotalProducto, 2, '.', ',');

            var impuestosOld = $(".impuestos_" + idProducto).val();

            var impuestoXProducto, impuestoCantidad, arrayImp, TipoTasa;
            var idImpuestoOld, totImpIndividual, impuestoTotalant, impuestoTotNuevo, impuestoTotNuevoF;
            console.log("Actualizando producto");
            $('.impuestos_' + idProducto + ' > span').each(function(index, span) {
              impuestoXProducto = $(this).attr("id");
              arrayImp = impuestoXProducto.split("_");
              idImpuestoOld = arrayImp[3];
              TipoTasa = arrayImp[2];
              impuestoCantidad = parseFloat($("#impAgregado_" + idProducto + "_" + idImpuestoOld).val());

              if (TipoTasa == 1){
                totImpIndividual = parseFloat((PiezasImp * Precio) * (impuestoCantidad / 100));
              }
              if (TipoTasa == 2){
                totImpIndividual = parseFloat(PiezasImp * impuestoCantidad);
              }
              if (TipoTasa == 3){
                totImpIndividual = 0.00;
              }

              impuestoTotalant = numeral($("#Impuesto_" + idImpuestoOld).html());
              impuestoTotNuevo = totImpIndividual + impuestoTotalant.value();
              impuestoTotNuevoF = $.number(impuestoTotNuevo, 2, '.', ',');
              $("#Impuesto_" + idImpuestoOld).html(impuestoTotNuevoF);

            });

            $('#idProducto_' + idProducto).empty();
            nuevo_elemento = "<td id='nombreproducto_" + idProducto + "'>" + Producto + "</td>" +
              "<td id='piezas_" + idProducto + "'><input type='number' id='piezasUnic_" + idProducto +
              "' name='inp_piezas[]' value='" + Piezas + "' class='modificarnumero numeros-solo form-control' min='1' >" + "</td>" +
              "<input type='hidden' id='piezaAnt_" + idProducto + "' value='" + Piezas + "' />" +
              "<input type='hidden' name='inp_productos[]' value='" + idProducto + "' />" +
              "<td>" + UnidadMedida + "</td>" +
              "<td id='precio_" + idProducto + "'> <input type='number' name='inp_precio[]' id='precioUnic_" + idProducto +
            "' value='" + PrecioF + "' class='modificarprecio form-control textTable border-0' min='0' >" + "</td>" +
            "<input type='hidden' id='precionAnt_" + idProducto + "' value='" + PrecioF + "' />" +
              //"<input type='hidden' name='inp_precio[]' value='" + Precio + "' />" +
              "<td>" +
              "<span class='impuestos_" + idProducto + "'> " +
              impuestosOld +
              "</span>" +
              "</td>" +
              "<td id='totalproducto_" + idProducto + "'>" + TotalProductoF + "</td>" +
              "<td >" + existencia + "</td>"+
              "<input type='hidden' name='inp_total_producto[]' value='" + TotalProducto + "' />" +
              "<td><button type='button' class='btn eliminarProductos' id='" + idProducto +
              "'><img src='../../img/timdesk/delete.svg' width='20px' /></button></td>";
            $('#idProducto_' + idProducto).append(nuevo_elemento);

          } else {
            //cuando se ingresa un nuevo producto
            TotalProducto = (Piezas * Precio).toFixed(2);
            descuento = "";

            PrecioF = $.number(Precio, 2, '.', ',');
            TotalProductoF = $.number(TotalProducto, 2, '.', ',');

            nuevo_elemento = "<tr id='idProducto_" + idProducto + "' class='text-center'>" +
              "<td id='nombreproducto_" + idProducto + "'>" + Producto + "</td>" +
              "<td id='piezas_" + idProducto + "'><input type='number' name='inp_piezas[]' id='piezasUnic_" + idProducto +
              "' value='" + Piezas + "' class='modificarnumero numeros-solo form-control' min='1' >" + "</td>" +
              "<input type='hidden' id='piezaAnt_" + idProducto + "' value='" + Piezas + "' />" +
              "<input type='hidden' name='inp_productos[]' value='" + idProducto + "' />" +
              "<td>" + UnidadMedida + "</td>" +
              "<td id='precio_" + idProducto + "'> <input type='number' name='inp_precio[]' id='precioUnic_" + idProducto +
            "' value='" + PrecioF + "' class='modificarprecio form-control textTable border-0' min='0' >" + "</td>" +
            "<input type='hidden' id='precionAnt_" + idProducto + "' value='" + PrecioF + "' />" +
              //"<input type='hidden' name='inp_precio[]' value='" + Precio + "' />" +
              "<td>" +
              ImpuestosCompleto +
              "</td>";

            nuevo_elemento += "<td id='totalproducto_" + idProducto + "'>" + TotalProductoF + "</td>" +
              "<td>" + existencia + "</td>"+
              "<input type='hidden' name='inp_total_producto[]' value='" + TotalProducto + "' />" +
              "<td><button type='button' class='btn eliminarProductos' id='" + idProducto +
              "'><img src='../../img/timdesk/delete.svg' width='20px' /></button></td>" +
              "</tr>";

            $('#lstProductos').append(nuevo_elemento);

            $('.impuestos_' + idProducto + ' > span').each(function(index, span) {

              impuestoXProducto = $(this).attr("id");
              arrayImp = impuestoXProducto.split("_");
              idImpuestoOld = arrayImp[3];
              TipoTasa = arrayImp[2];
              impuestoCantidad = parseFloat($("#impAgregado_" + idProducto + "_" + idImpuestoOld).val());

              if (TipoTasa == 1)
                totImpIndividual = parseFloat((PiezasImp * Precio) * (impuestoCantidad / 100));
              if (TipoTasa == 2 || TipoTasa == 3)
                totImpIndividual = 0.00;

              if (TipoTasa == 1) {
                var TotalImpuesto = TotalProducto * (impuestoCantidad / 100);
              }
              if (TipoTasa == 2) {
                var TotalImpuesto = impuestoCantidad * Piezas;
              }
              if (TipoTasa == 3) {
                var TotalImpuesto = 0.00;

                if(idImpuestoOld == 5){
                  cuentaIVAexento++;
                }
                if(idImpuestoOld == 13){
                cuentaISRExento++;
                }
                if(idImpuestoOld == 16){
                  cuentaIEPSExento++;
                }
              }

              var TotalImpuestoF;
              var TotalImpuestoGen;
              Operacion = $("#OperacionUnica_" + idProducto + "_" + idImpuestoOld).val();
              DetalleImpuesto = $("#ImpuestoUnico_" + idProducto + "_" + idImpuestoOld).val();
              if ($('#lstimpuestos').find('#' + arrayImp[0] + "_" + idImpuestoOld).length == 1) {

                var sumaImpuesto = numeral($('#Impuesto_' + idImpuestoOld).html());

                TotalImpuestoGen = TotalImpuesto + parseFloat(sumaImpuesto.value());
                TotalImpuestoF = $.number(TotalImpuestoGen, 2, '.', ',');
                nuevo_impuesto = "<th colspan='3'></th>" +
                  "<th style='text-align: right;'>" + DetalleImpuesto + "</th>" +
                  "<th colspan='2' style='text-align: right;'>$ <span id='Impuesto_" + idImpuestoOld + "' name='" +
                  arrayImp[1] + "_" + Operacion + "' class='ImpuestoTot'>" + TotalImpuestoF + "</span></th>" +
                  "<th></th>";
                $('#' + arrayImp[0] + '_' + idImpuestoOld).empty();
                $('#' + arrayImp[0] + '_' + idImpuestoOld).append(nuevo_impuesto);
              } else {
                //nuevo impuesto
                TotalImpuestoF = $.number(TotalImpuesto, 2, '.', ',');
                nuevo_impuesto = "<tr id='" + arrayImp[0] + "_" + idImpuestoOld + "'>" +
                  "<th colspan='3'></th>" +
                  "<th style='text-align: right;'>" + DetalleImpuesto + "</th>" +
                  "<th colspan='2' style='text-align: right;'>$ <span id='Impuesto_" + idImpuestoOld + "' name='" +
                  arrayImp[1] + "_" + Operacion + "' class='ImpuestoTot'>" + TotalImpuestoF + "</span></th>" +
                  "<th></th>" +
                  "</tr>";
                $('#lstimpuestos').append(nuevo_impuesto);

              }

            });

          }

          Subtotal = SubtotalNum.value() + parseFloat(TotalProducto) - TotalProducto_old;
          var SubtotalF = $.number(Subtotal, 2, '.', ',');
          $('#Subtotal').empty();
          $('#Subtotal').append(SubtotalF);

          //calculo de impuestos
          var suma = 0.00,
            cantidadImp, tipoimp, arrayTipoImp;
          $('#lstimpuestos > tr').each(function(index, tr) {
            cantidadImp = numeral($(this).find(".ImpuestoTot").html());
            tipoimp = $(this).find(".ImpuestoTot").attr("name");
            arrayTipoImp = tipoimp.split("_");

            if (arrayTipoImp[0] == 1) {
              suma = suma + cantidadImp.value();
            }
            if (arrayTipoImp[0] == 2) {
              suma = suma - cantidadImp.value();
            }
            if (arrayTipoImp[0] == 3) {

              if (arrayTipoImp[1] == 1)
                suma = suma + cantidadImp.value();

              if (arrayTipoImp[1] == 2)
                suma = suma - cantidadImp.value();
            }

          });

          var Total = Subtotal + suma;
          var TotalF = $.number(Total, 2, '.', ',');
          $('#Total').empty();
          $('#Total').append(TotalF);

          //AGREGAR EL PRECIO DEL PRODUCTO AL COSTO GENERAL
          //if(NuevoProducto == 1){
              $.ajax({
                type: 'POST',
                url: 'functions/agregarNuevoProducto.php',
                data: {
                  idProducto: idProducto,
                  idCliente: gCliente,
                  costo: Precio
                },
                success: function(data) {

                },
                error: function() {

                }
              });
          //}
          //FIN AGREGAR EL PRECIO DEL PRODUCTO AL COSTO GENERAL

          selectProductos.set("");
          $('#txtPiezas').val("");
          $('#txtPrecio').val("");
          $("#actualizarUnidad").html("");
          cuenta++;
          
        },
        error:function(error){
          console.log(error);
        }
      }); */
    }
  });

  //Eliminar productos
  $(document).on("click", ".eliminarProductos", function() {
    var idProducto = this.id;
    var TotalProductoFormat, SubtotalFormat, IVAFormat;
    TotalProductoFormat = numeral($("#totalproducto_" + idProducto).html());
    var TotalProducto = TotalProductoFormat.value();
    SubtotalFormat = numeral($("#Subtotal").html());
    var Subtotal_old = SubtotalFormat.value();
    var totalPiezas = parseInt($("#piezasUnic_" + idProducto).val());
    var impuestoXProductoIVA, impuestoCantidadIVA, idImpuestoIVA, cantidadAdicionalIVA = 0;
    var precio = $("#precioUnic_" + idProducto).val();

    var Subtotal = Subtotal_old - parseFloat(TotalProducto);
    var SubtotalF = $.number(Subtotal, 4, '.', ',');
    $('#Subtotal').empty();
    $('#Subtotal').append(SubtotalF);

    var impuestoXProducto, impuestoCantidad, arrayImp, TipoTasa, Impuesto;
    var idImpuestoOld, totImpIndividual, impuestoTotalant, impuestoTotNuevo, impuestoTotNuevoF;

    $('.impuestos_' + idProducto + ' > span').each(function(index, span) {
      impuestoXProducto = $(this).attr("id");
      arrayImp = impuestoXProducto.split("_");
      Impuesto = arrayImp[0];
      TipoTasa = arrayImp[2];
      idImpuestoOld = arrayImp[3];

      if(idImpuestoOld == 1){

          $('.impuestos_' + idProducto + ' > span').each(function(indexIVA, spanIVA) {

              impuestoXProductoIVA = $(this).attr("id");
              arrayImpIVA = impuestoXProductoIVA.split("_");
              idImpuestoIVA = arrayImpIVA[3];
              impuestoCantidad = parseFloat($("#impAgregado_" + idProducto + "_" + idImpuestoIVA).val());
              //alert("elementos " + totalPiezas + " precio " + precio  + " impuesto " + impuestoCantidad) ;
              
              if(idImpuestoIVA == 2){
                cantidadAdicionalIVA = cantidadAdicionalIVA + ((totalPiezas * precio) * (impuestoCantidad / 100));
              }
              if(idImpuestoIVA == 3){
                cantidadAdicionalIVA = cantidadAdicionalIVA + (totalPiezas * impuestoCantidad);
              }
          });

      }

      impuestoCantidad = parseFloat($("#impAgregado_" + idProducto + "_" + idImpuestoOld).val());

      if (TipoTasa == 1){
        totImpIndividual = parseFloat((TotalProducto + cantidadAdicionalIVA) * (impuestoCantidad / 100));
      }
      if (TipoTasa == 2){
        totImpIndividual = impuestoCantidad * totalPiezas;
      }
      if (TipoTasa == 3){
        totImpIndividual = 0.00;
      }

      cantidadAdicionalIVA = 0;
      impuestoTotalant = numeral($("#Impuesto_" + idImpuestoOld).html());
      impuestoTotNuevo = impuestoTotalant.value() - totImpIndividual;
      impuestoTotNuevoF = $.number(impuestoTotNuevo, 2, '.', ',');
      $("#Impuesto_" + idImpuestoOld).html(impuestoTotNuevoF);

      if (impuestoTotNuevo <= 0 && TipoTasa != 3) {
        $("#" + Impuesto + "_" + idImpuestoOld).remove();
      } else {
        $("#Impuesto_" + idImpuestoOld).html(impuestoTotNuevoF);
      }

      if (TipoTasa == 3) {

        if(idImpuestoOld == 5){
           cuentaIVAexento--;
            
           if (cuentaIVAexento == 0){
              $("#" + Impuesto + "_" + idImpuestoOld).remove();
           }

        }

        if(idImpuestoOld == 13){
          cuentaISRExento--;

          if (cuentaISRExento == 0){
              $("#" + Impuesto + "_" + idImpuestoOld).remove();
          }
        }

        if(idImpuestoOld == 16){
          cuentaIEPSExento--;

          if (cuentaIEPSExento == 0){
              $("#" + Impuesto + "_" + idImpuestoOld).remove();
          }
        }
        
      }


    });

    //calculo de impuestos
    var suma = 0.00,
      cantidadImp, tipoimp, arrayTipoImp;
    $('#lstimpuestos > tr').each(function(index, tr) {
      cantidadImp = numeral($(this).find(".ImpuestoTot").html());
      tipoimp = $(this).find(".ImpuestoTot").attr("name");
      arrayTipoImp = tipoimp.split("_");

      if (arrayTipoImp[0] == 1) {
        suma = suma + cantidadImp.value();
      }
      if (arrayTipoImp[0] == 2) {
        suma = suma - cantidadImp.value();
      }
      if (arrayTipoImp[0] == 3) {

        if (arrayTipoImp[1] == 1)
          suma = suma + cantidadImp.value();

        if (arrayTipoImp[1] == 2)
          suma = suma - cantidadImp.value();
      }

    });

    var Total = Subtotal + suma;
    var TotalF = $.number(Total, 4, '.', ',');
    $('#Total').empty();
    $('#Total').append(TotalF);

    $('#idProducto_' + idProducto).remove();
    cuenta--;

    if (cuenta == 0) {
      selectCliente.enable();
      selectSucursal.enable();

      gtipoProducto = 0;
      $("#TipoProductoGeneral").val(0);

    }

    $("#catalogoImpuestos").css("display", "none");
  });

  $(document).on("keyup", ".modificarnumero", function() {
    this.value = this.value.replace(/[^0-9]/g, '');
  });


  ///Validar el numero 12 parte entera 6 parte decimal.
  function validarMoneda(numero){
      //valida que la cantidad no sea mayor a 12 enteros y 6 decimales
      aux = numero.toString().split(".");
      var ValorAux="";
      flag = false;

      if(aux.length > 0){
        if(aux.length == 1 && aux[0].length > 12){
          flag = true;
          ValorAux = aux[0].substring(0,12);
          }else if(aux.length >= 2 && (aux[0].length > 12 || aux[1].length > 6)){
            flag = true;
            ValorAux = aux[0].substring(0,12) + "." + aux[1].substring(0,6);
          }else if(aux.length == 1){
            ValorAux = numero.toString() + ".00";
          }else{
            ValorAux = numero;
          }
      }
      if(flag){
        Lobibox.notify("warning", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/warning_circle.svg",
          msg: 'El precio solo admite hasta 12 enteros y 6 decimales',
          sound: '../../../../../sounds/sound4'
        });
        //$('#precio-'+id).val(ValorAux);
      }
      return ValorAux;

  }

  var cmbNoInt = new SlimSelect({
    select: '#txtarea9'
  });
  var cmbPaisSuc = new SlimSelect({
    select: '#txtarea8'
  });
  var cmbEstadoSuc = new SlimSelect({
    select: '#txtarea6'
  });

  var cmbGenero = new SlimSelect({
    select: '#cmbGenero',
    onChange: (info) => {
    $("#invalid-genero").css("display", "none");
    }
  });
  var cmbEstadoE = new SlimSelect({
    select: '#cmbEstado',
    onChange: (info) => {
    $("#invalid-estado").css("display", "none");
    }
  });
  var cmbRoles = new SlimSelect({
    select: '#cmbRoles'
  });

  var cmbTipoProducto = new SlimSelect({
    select: "#cmbTipoProducto",
    deselectLabel: '<span class="">✖</span>',
    onChange: (info) => {
      $("#invalid-tipoProd").css("display", "none");
    }
  });

  //Modificar cantidad de  productos
  $(document).on("change", ".modificarnumero", function() {

    this.value = this.value.replace('.', '');
    var cantidadNueva;
    var impuestoXProductoIVA, impuestoCantidadIVA, idImpuestoIVA, cantidadAdicionalIVA = 0;

    if (isNaN(this.value) || $.trim(this.value) == '') {
      cantidadNueva = parseInt(1);
      this.value = 1;
    } else {
      cantidadNueva = parseInt(this.value);
    }

    var arrayImp = this.id.split("_");
    var idProducto = arrayImp[1];
    cantidadAnterior = $("#piezaAnt_" + idProducto).val();
    $("#piezaAnt_" + idProducto).val(cantidadNueva);
    var cantidad = cantidadNueva - cantidadAnterior;

    var PrecioProducto = numeral($("#precioUnic_" + idProducto).val());

    var TotalProducto = (cantidad * PrecioProducto.value()).toFixed(2);

    var TotalProductoAnt = numeral($("#totalproducto_" + idProducto).html());
    var TotalProductoFinFormat = parseFloat(TotalProducto) + parseFloat(TotalProductoAnt.value());
    var TotalProductoFin = $.number(TotalProductoFinFormat, 6, '.', ',');

    $('#totalproducto_' + idProducto).html(TotalProductoFin);


    var SubtotalNum = numeral($("#Subtotal").html());
    Subtotal = SubtotalNum.value() + parseFloat(TotalProducto);
    var SubtotalF = $.number(Subtotal, 2, '.', ',');
    $('#Subtotal').empty();
    $('#Subtotal').append(SubtotalF);

    var impuestoXProducto, impuestoCantidad, arrayImp, TipoTasa;
    var idImpuestoOld, totImpIndividual, impuestoTotalant, impuestoTotNuevo, impuestoTotNuevoF;

    $('.impuestos_' + idProducto + ' > span').each(function(index, span) {
      impuestoXProducto = $(this).attr("id");
      arrayImp = impuestoXProducto.split("_");
      idImpuestoOld = arrayImp[3];
      TipoTasa = arrayImp[2];

      if(idImpuestoOld == 1){

          $('.impuestos_' + idProducto + ' > span').each(function(indexIVA, spanIVA) {

              impuestoXProductoIVA = $(this).attr("id");
              arrayImpIVA = impuestoXProductoIVA.split("_");
              idImpuestoIVA = arrayImpIVA[3];
              impuestoCantidad = parseFloat($("#impAgregado_" + idProducto + "_" + idImpuestoIVA).val());
              //alert("elementos " + cantidad + " precio " + PrecioProducto.value()  + " impuesto " + impuestoCantidad) ;
              
              if(idImpuestoIVA == 2){
                cantidadAdicionalIVA = cantidadAdicionalIVA + ((cantidad * PrecioProducto.value()) * (impuestoCantidad / 100));
              }
              if(idImpuestoIVA == 3){
                cantidadAdicionalIVA = cantidadAdicionalIVA + (cantidad * impuestoCantidad);
              }
          });

      }

      impuestoCantidad = parseFloat($("#impAgregado_" + idProducto + "_" + idImpuestoOld).val());

      if (TipoTasa == 1) {
        totImpIndividual = (parseFloat(cantidad * PrecioProducto.value()) + cantidadAdicionalIVA) * (impuestoCantidad / 100);
        //console.log(cantidad + "//" + PrecioProducto.value() + "//" + impuestoCantidad);
      }
      if (TipoTasa == 2) {
        totImpIndividual = cantidad * impuestoCantidad;
      }
      if (TipoTasa == 3) {
        totImpIndividual = 0.00;
      }

      cantidadAdicionalIVA = 0;

      impuestoTotalant = numeral($("#Impuesto_" + idImpuestoOld).html());
      //console.log(totImpIndividual + "/////" + impuestoTotalant.value());
      impuestoTotNuevo = totImpIndividual + impuestoTotalant.value();
      impuestoTotNuevoF = $.number(impuestoTotNuevo, 2, '.', ',');
      $("#Impuesto_" + idImpuestoOld).html(impuestoTotNuevoF);

    });

    //calculo de impuestos
    var suma = 0.00,
      cantidadImp, tipoimp, arrayTipoImp;
    $('#lstimpuestos > tr').each(function(index, tr) {
      cantidadImp = numeral($(this).find(".ImpuestoTot").html());
      tipoimp = $(this).find(".ImpuestoTot").attr("name");
      arrayTipoImp = tipoimp.split("_");

      if (arrayTipoImp[0] == 1) {
        suma = suma + cantidadImp.value();
      }
      if (arrayTipoImp[0] == 2) {
        suma = suma - cantidadImp.value();
      }
      if (arrayTipoImp[0] == 3) {

        if (arrayTipoImp[1] == 1)
          suma = suma + cantidadImp.value();

        if (arrayTipoImp[1] == 2)
          suma = suma - cantidadImp.value();
      }

    });

    var Total = Subtotal + suma;
    var TotalF = $.number(Total, 4, '.', ',');
    $('#Total').empty();
    $('#Total').append(TotalF);
  });


  
////
//// Si cambia el precio en la tabla.
////
/* $(document).on("keyup", ".modificarprecio", function() {
    this.value = this.value.replace(/[^\d.]/g, '');
    //this.value = this.value.replace(/[^0-9]/g, '');
    //var regexp = /[^\d.]/g;

  }); */
//Modificar el precio del  productos
$(document).on("change", ".modificarprecio", function() {

  //this.value = this.value.replace('.', '');
  //var cantidadNueva;
  var precioNuevo;
  var impuestoXProductoIVA, impuestoCantidadIVA, idImpuestoIVA, cantidadAdicionalIVA = 0;

  if (isNaN(this.value) || $.trim(this.value) == '') {
    precioNuevo = parseFloat(1);
    this.value = 1;
  } else {
    precioNuevo = parseFloat(this.value);
  }

  let aux = precioNuevo.toString();
  precioNuevo = validarMoneda(aux);
  this.value=precioNuevo;

    ///Si el umero de ecimales es mayor al anterior se cambia.
    nDecimales = nDecimales < contarDecimales(precioNuevo) ? contarDecimales(precioNuevo) : nDecimales;

    console.log("Decimales: " + nDecimales);
    precioNuevo = parseFloat(precioNuevo).toFixed(nDecimales);

  var arrayImp = this.id.split("_");
  var idProducto = arrayImp[1];
  precioAnterior = $("#precionAnt_" + idProducto).val();
  $("#precionAnt_" + idProducto).val(precioNuevo);

  let PrecioNuevoObj = numeral(precioNuevo);
  precioNuevo = PrecioNuevoObj.value();

  let PrecioAnteriorObj = numeral(precioAnterior);
  precioAnterior = PrecioAnteriorObj.value();

  var precio = precioNuevo - precioAnterior;

  var CantidadProducto = numeral($("#piezasUnic_" + idProducto).val());// numeral($("#precio_" + idProducto).html());

  var TotalProducto = (precio * CantidadProducto.value()).toFixed(nDecimales);

  var TotalProductoAnt = numeral($("#totalproducto_" + idProducto).html());
  var TotalProductoFinFormat = parseFloat(TotalProducto) + parseFloat(TotalProductoAnt.value());
  var TotalProductoFin = $.number(TotalProductoFinFormat, nDecimales, '.', ',');

  $('#totalproducto_' + idProducto).html(TotalProductoFin);

   //TotalProducto = numeral($("#totalproducto_" + idProducto).val());

  var SubtotalNum = numeral($("#Subtotal").html());
  Subtotal = SubtotalNum.value() + parseFloat(TotalProducto);
  var SubtotalF = $.number(Subtotal, 2, '.', ',');
  $('#Subtotal').empty();
  $('#Subtotal').append(SubtotalF);

  var impuestoXProducto, impuestoCantidad, arrayImp, TipoTasa;
  var idImpuestoOld, totImpIndividual, impuestoTotalant, impuestoTotNuevo, impuestoTotNuevoF;

  $('.impuestos_' + idProducto + ' > span').each(function(index, span) {
    impuestoXProducto = $(this).attr("id");
    arrayImp = impuestoXProducto.split("_");
    idImpuestoOld = arrayImp[3];
    TipoTasa = arrayImp[2];


    if(idImpuestoOld == 1){

        $('.impuestos_' + idProducto + ' > span').each(function(indexIVA, spanIVA) {

            impuestoXProductoIVA = $(this).attr("id");
            arrayImpIVA = impuestoXProductoIVA.split("_");
            idImpuestoIVA = arrayImpIVA[3];
            impuestoCantidad = parseFloat($("#impAgregado_" + idProducto + "_" + idImpuestoIVA).val());
            //alert("elementos " + CantidadProducto.value() + " precio " + precio  + " impuesto " + impuestoCantidad) ;
            
            if(idImpuestoIVA == 2){
              cantidadAdicionalIVA = cantidadAdicionalIVA + ((CantidadProducto.value() * precio) * (impuestoCantidad / 100));
              //alert(precio);
            }
            /*if(idImpuestoIVA == 3){
              cantidadAdicionalIVA = cantidadAdicionalIVA + (CantidadProducto.value() * impuestoCantidad);
            }*/
        });

    }


    impuestoCantidad = parseFloat($("#impAgregado_" + idProducto + "_" + idImpuestoOld).val());

    if (TipoTasa == 1) {
      totImpIndividual = parseFloat(((precio * CantidadProducto.value()) + cantidadAdicionalIVA) * (impuestoCantidad / 100));
      //console.log(cantidad + "//" + PrecioProducto.value() + "//" + impuestoCantidad);
    }
    if (TipoTasa == 2) {
      totImpIndividual = 0;//CantidadProducto.value() * impuestoCantidad;
    }
    if (TipoTasa == 3) {
      totImpIndividual = 0.00;
    }

    cantidadAdicionalIVA = 0;

    impuestoTotalant = numeral($("#Impuesto_" + idImpuestoOld).html());
    //console.log(totImpIndividual + "/////" + impuestoTotalant.value());
    impuestoTotNuevo = totImpIndividual + impuestoTotalant.value();
    impuestoTotNuevoF = $.number(impuestoTotNuevo, 2, '.', ',');
    $("#Impuesto_" + idImpuestoOld).html(impuestoTotNuevoF);

  });

  //calculo de impuestos
  var suma = 0.00,
    cantidadImp, tipoimp, arrayTipoImp;
  $('#lstimpuestos > tr').each(function(index, tr) {
    cantidadImp = numeral($(this).find(".ImpuestoTot").html());
    tipoimp = $(this).find(".ImpuestoTot").attr("name");
    arrayTipoImp = tipoimp.split("_");

    if (arrayTipoImp[0] == 1) {
      suma = suma + cantidadImp.value();
    }
    if (arrayTipoImp[0] == 2) {
      suma = suma - cantidadImp.value();
    }
    if (arrayTipoImp[0] == 3) {

      if (arrayTipoImp[1] == 1)
        suma = suma + cantidadImp.value();

      if (arrayTipoImp[1] == 2)
        suma = suma - cantidadImp.value();
    }

});


        //Recorre todos los elementos de la clase decimal(Precio Unitario e Importe) y le pone los decimales maximos para formatear
        $(".decimales").each(function (index, element) {
          // element == this

          /// Si Es el Importe
          if(!(this.value)){
            let precioAnt = this.textContent;
            let precioDes = ((parseFloat(precioAnt.replace(',',''))).toFixed(nDecimales));
            this.innerText = (precioDes);
          }else{
            ///Si es el precio Unitario.
            let precioAnt = this.value;
            let precioDes = (parseFloat(precioAnt)).toFixed(nDecimales);
            this.value = (precioDes);
          }
          

        });


var Total = Subtotal + suma;
var TotalF = $.number(Total, 2, '.', ',');
$('#Total').empty();
$('#Total').append(TotalF);
});


  $("#txtFechaVencimiento")[0].reportValidity();
  $("#txtFechaVencimiento")[0].setCustomValidity(
    'La fecha de vencimiento no puede ser menor a la fecha de generación.');

  $("#btnAgregar").click(function() {

    var fechaVencimiento = $("#txtFechaVencimiento").val();
    var fechaGeneracion = "<?=$fechaVencimiento?>";
    var fechaVencimientoF = "<?=$fechaVencimientoF?>";
    gVendedor = $("#cmbVendedor").val();
    let notasClientes = $("#NotasClientes").val().trim();
    let notasInternas= $("#NotasInternas").val().trim();
    gDireccionEnvio = $("#cmbDireccionEntrega").val();
    gCondicionPago = $("#cmbCondicionPago").val();

    if (gSucursal < 1) {
      Lobibox.notify("error", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../img/timdesk/notificacion_error.svg",
        msg: "¡Es necesario añadir una sucursal!",
      });
      return;
    }

    if (gCliente < 1) {
      Lobibox.notify("error", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../img/timdesk/notificacion_error.svg",
        msg: "¡El cliente es necesario!",
      });
      return;
    }

    if (gVendedor < 1) {
      Lobibox.notify("error", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../img/timdesk/notificacion_error.svg",
        msg: "¡El vendedor es necesario!",
      });
      return;
    }

    if (gDireccionEnvio < 1) {
      Lobibox.notify("error", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../img/timdesk/notificacion_error.svg",
        msg: "¡La dirección de envío es necesaria!",
      });
      return;
    }

    if (gCondicionPago < 1) {
      Lobibox.notify("error", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../img/timdesk/notificacion_error.svg",
        msg: "¡La condición de pago es necesaria!",
      });
      return;
    }

    if (new Date(fechaGeneracion).getTime() > new Date(fechaVencimiento).getTime()) {
      Lobibox.notify("error", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../img/timdesk/notificacion_error.svg",
        msg: "¡La fecha de vencimiento no puede ser menor a la fecha de generación!",
      });
      return;
    }

    //validacion para no permitir una fecha mayor al rango permitido
    if (new Date(fechaVencimiento).getTime() > new Date(fechaVencimientoF).getTime()) {
      Lobibox.notify("error", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../img/timdesk/notificacion_error.svg",
        msg: "¡La fecha de vencimiento no puede ser mayor del rango permitido!",
      });
      return;
    }

    if (cuenta < 1) {
      Lobibox.notify("error", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../img/timdesk/notificacion_error.svg",
        msg: "¡Debes ingresar por lo menos un producto!",
      });
      return;
    }

    if(notasClientes.length > 500 ){
      Lobibox.notify("error", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../img/timdesk/notificacion_error.svg",
        msg: "No puedes ingresar una nota del cliente de más de 500 caracteres. Nota: Saltos de línea cuentan como un carácter.",
      });
      return;
    }

    if(notasInternas.length > 500 ){
      Lobibox.notify("error", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../img/timdesk/notificacion_error.svg",
        msg: "No puedes ingresar una nota interna de más de 500 caracteres. Nota: Saltos de línea cuentan como un carácter.",
      });
      return;
    }

    $("#btnAgregar").prop("disabled", true);

    var tabla_cotizacion = {
      html: $('#cotizacion').html()
    };
    var Total = numeral($("#Total").html());
    var Subtotal = numeral($("#Subtotal").html());

    $('#cotizacion').append("<input type='hidden' name='tabla_cotizacion'  id='tabla_cotizacion' value='" +
      tabla_cotizacion.html + "' />");
    $('#cotizacion').append("<input type='hidden' name='Total'  id='Total' value='" +
      Total.value() + "' />");
    $('#cotizacion').append("<input type='hidden' name='Subtotal'  id='Subtotal' value='" +
      Subtotal.value() + "' />");


    selectCliente.enable();
    selectSucursal.enable();

    $.ajax({
      type: 'post',
      url: 'functions/cotizacionSubmit.php',
      data: $('#form-cotizacion').serialize(),
      success: function(data) {
        var datos = JSON.parse(data);

        if(datos.estatus == "error-notacliente"){
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/notificacion_error.svg",
            msg: "No puedes ingresar una nota del cliente de más de 500 caracteres. Nota: Saltos de línea cuentan como un carácter.",
          });
          $("#btnAgregar").prop("disabled", false);
          selectCliente.disabled();
          selectSucursal.disabled();
        }

        if(datos.estatus == "error-notainterna"){
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/notificacion_error.svg",
            msg: "No puedes ingresar una nota interna de más de 500 caracteres. Nota: Saltos de línea cuentan como un carácter.",
          });
          $("#btnAgregar").prop("disabled", false);
          selectCliente.disabled();
          selectSucursal.disabled();
        }

        if(datos.estatus == "error-general"){
          Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/notificacion_error.svg",
          msg: "¡Algo salio mal :(!",
          });
          $("#btnAgregar").prop("disabled", false);
          selectCliente.disabled();
          selectSucursal.disabled();
        }
        
        if(datos.estatus == "exito"){
 
                Swal.fire({
                  icon: "success",
                  title: "Cotización registrada",
                  html: "La referencia es: <b>" + datos.idcotizacionempresa + "</b><br> ¿Deseas enviarle un correo electrónico al cliente?",
                  type: "question",
                  showConfirmButton: true,
                  showCancelButton: true,
                  confirmButtonText:
                    'Si <i class="far fa-arrow-alt-circle-right"></i>',
                  cancelButtonText: 'No <i class="far fa-times-circle"></i>',
                  buttonsStyling: false,
                  allowEnterKey: false,
                  customClass: {
                    actions: "d-flex justify-content-around",
                    confirmButton: "btn-custom btn-custom--blue",
                    cancelButton: "btn-custom btn-custom--border-blue",
                  },
                }).then((result) => {
                  if (result.isConfirmed) {
                    $("#modal_envio").load(
                      "functions/modal_envio.php?idCotizacion=" + datos.idcotizacion + "&idCliente=" + gCliente + "&estatus=0",
                      function () {
                        $("#datos_envio").modal("show");
                        emailRegex = /^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i;
                        destinatarios = new SlimSelect({
                          select: '#txtDestino',
                          placeholder: 'Seleccione el/los destinatarios',
                          addable: function (value) {
                            if (!emailRegex.test(value)) {return 'Escribe un correo válido'}
                            return {
                              text: value,
                              value: value.toLowerCase()
                            }
                          }
                        });
                      }
                    );
                    
                  } else {
                    let token = $("#csr_token_7ALF1").val();

                    setTimeout(
                        function() {
                            window.location.href = "functions/descargar_Cotizacion.php?idCotizacion="+datos.idcotizacion +'&csr_token_7ALF1=' + token;
                            // window.open(
                            // "functions/descargar_Cotizacion.php?idCotizacion="+datos.idcotizacion +'&csr_token_7ALF1=' + token);
                        },
                    500,
                    );

                    setTimeout(function(){window.location.href = 'detalleCotizacion.php?id=' + datos.idcotizacion},1500);
                    // descargarCotizacion(datos.idcotizacion);
                    // $(location).attr('href', 'detalleCotizacion.php?id=' + datos.idcotizacion);
                    //$(location).attr('href', './');
                  }
                });

        }
      },
      error: function() {
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/notificacion_error.svg",
          msg: "¡Algo salio mal :(!",
        });
        $("#btnAgregar").prop("disabled", false);
        selectCliente.disabled();
        selectSucursal.disabled();
      }
    });
  });

  function isEmail(email) {
    var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(email);
  }

$("#btnAgregarNC").click(function () {
  if($("#txtRFC").hasClass("d-none") && $("#cmbRegimen").hasClass("d-none") && $("#txtCP").hasClass("d-none")){
    console.log($("#txtRFC"));
  }
  if(!$("#txtCP").val() && !$("#show").hasClass("d-none")){
    $("#invalid-cp").css("display", "block");
    $("#txtCP").addClass("is-invalid");
  }
  if(!$("#cmbRegimen").val() && !$("#show").hasClass("d-none")){
    $("#invalid-regimen").css("display", "block");
    $("#cmbRegimen").addClass("is-invalid")
  }
  if(!$("#txtRFC").val() && !$("#show").hasClass("d-none")){
    $("#invalid-rfc").css("display", "block");
      $("#txtRFC").addClass("is-invalid");
  }
  if(!$("#txtRazonSocial").val()){
    $("#invalid-razon").css("display", "block");
    $("#txtRazonSocial").addClass("is-invalid");
  }

  var badRazon =
    $("#invalid-razon").css("display") === "block" ? false : true;
  var badRFC = $("#invalid-rfc").css("display") === "block" ? false : true;
  var badRegimen = $("#invalid-regimen").css("display") === "block" ? false : true;
  var badCP = $("#invalid-cp").css("display") === "block" ? false : true;
  if (
    badRazon &&
    badCP &&
    badRFC &&
    badRegimen
  ) {
    $("#btnAgregarNC").prop("disabled", true);

    var nombreComercial = $("#txtNombreComercial").val();
    if(!$("#txtNombreComercial").val()){
      nombreComercial = $("#txtRazonSocial").val().toUpperCase();
    }
    var medioContactoCliente = $("#cmbMedioContactoCliente").val();
    if(!$("#cmbMedioContactoCliente").val()){
      medioContactoCliente = 1;
    }
    var vendedor = $("#cmbVendedorNC").val();
    if(!$("#cmbVendedorNC").val()){
      vendedor = '';
    }
    var email = $("#txtEmail").val();
    if(!$("#txtEmail").val()){
      email = 'N/A';
    }

    var razonSocial = $("#txtRazonSocial").val().toUpperCase();
    var rfc = $("#txtRFC").val();
    if(!$("#txtRFC").val()){
      rfc = 'N/A';
    }
    var regimen = $("#cmbRegimen").val();
    if(!$("#cmbRegimen").val()){
      regimen = 1;
    }
    var pais = $("#cmbPais").val();
    if(!$("#cmbPais").val()){
      pais = 146;
    }
    var estado = $("#cmbEstadoC").val();
    if(!$("#cmbEstadoC").val()){
      estado = 14;
    }
    var cp = $("#txtCP").val();
    if(!$("#txtCP").val()){
      cp = 0;
    }
    var telefono = $("#txtTelefono").val();
    if(!$("#txtTelefono").val()){
      telefono = '';
    }

    $.ajax({
      url: "php/funciones.php",
      data: {
        clase: "save_data",
        funcion: "save_datosClienteCotizacion",
        datos: nombreComercial,
        datos2: medioContactoCliente,
        datos3: vendedor,
        datos4: email,
        datos5: razonSocial,
        datos6: rfc,
        datos7: pais,
        datos8: estado,
        datos9: cp,
        datos10: regimen,
        datos11: telefono
      },
      success: function (data, respuesta, xhr) {
        $("#btnAgregarNC").prop("disabled", false);

        console.log(
          "respuesta agregar datos generales del cliente:",
          respuesta
        );
        console.log(data);
        console.log(xhr);
        $("#agregar_Cliente_50").modal("toggle");
        $("#agregarCliente").trigger("reset");
        loadComboCliente();

        /* if (respuesta[0].status) {
          Lobibox.notify("success", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../../../img/timdesk/checkmark.svg",
            msg: "¡Datos generales registrados correctamente!",
            sound: '../../../../../sounds/sound4'
          });
          $("#btnCancelar_newCliente").click();
          loadCombo('','cmbCliente','cliente','','cliente');
          resetForm_NC();
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
        } */
      },
      error: function (data, error, xhr) {
        console.log(data);
        console.log(error);
        console.log(xhr);
        $("#btnAgregarNC").prop("disabled", false);
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top", //or 'center bottom'
          icon: true,
          //img: '<i class="fas fa-check-circle"></i>',
          img: "../../../../img/timdesk/notificacion_error.svg",
          msg: "¡Algo salio mal :(!",
          sound: '../../../../../sounds/sound4'
        });
      },
    });
  }
});

$("#btnCancelar_newCliente").on("click", ()=>{
  $("#agregarCliente").trigger("reset");
  $("#txtCP").removeClass("is-invalid");
  $("#invalid-cp").css("display", "none");
  $("#invalid-regimen").css("display", "none");
  $("#txtRFC").removeClass("is-invalid");
  $("#invalid-rfc").css("display", "none");
  $("#txtRazonSocial").removeClass("is-invalid");
  $("#invalid-razon").css("display", "none");
  cmbRegimen.set(0);
  cmbMedioContactoCliente.set(0);
  cmbVendedorNC.set(0);
  cmbPais.set(0);
  cmbEstadoC.set(0);
  $("#btnAgregarNC").prop("disabled", false);
});

$("#agregar_Cliente_50").on("hidden.bs.modal", ()=>{
  $("#agregarCliente").trigger("reset");
  $("#txtCP").removeClass("is-invalid");
  $("#invalid-cp").css("display", "none");
  $("#invalid-regimen").css("display", "none");
  $("#txtRFC").removeClass("is-invalid");
  $("#invalid-rfc").css("display", "none");
  $("#txtRazonSocial").removeClass("is-invalid");
  $("#invalid-razon").css("display", "none");
  cmbRegimen.set(0);
  cmbMedioContactoCliente.set(0);
  cmbVendedorNC.set(0);
  cmbPais.set(0);
  cmbEstadoC.set(0);
  $("#btnAgregarNC").prop("disabled", false);
});

$("#txtRazonSocial").on("change", ()=>{
  $("#invalid-razon").css("display", "none");
  $("#txtRazonSocial").removeClass("is-invalid");
});

$("#txtRFC").on("change", ()=>{
  $("#invalid-rfc").css("display", "none");
  $("#txtRFC").removeClass("is-invalid");
});

$("#cmbRegimen").on("change", ()=>{
  $("#invalid-regimen").css("display", "none");
  $("#cmbRegimen").removeClass("is-invalid");
});

$("#txtCP").on("change", ()=>{
  $("#invalid-cp").css("display", "none");
  $("#txtCP").removeClass("is-invalid");
});

$("#txtDestino").on("change", ()=>{
    $("#invalid-emailDestino").css("display", "none");
});

$(document).on("click", "#btnEnviar",()=>{
    var id = $("#txtId").val();
    var emailOrigen = $("#txtOrigen").val();
    var emailDestino = destinatarios.selected();
    console.log(emailDestino.length);
    var asunto = $("#txtAsunto").val();
    var mensaje = $("#txaMensaje").val();
    let token = "<?php echo $token; ?>";

    if (contadorEnviar == 0) {

      $("#txtOrigen")[0].reportValidity();
      $("#txtOrigen")[0].setCustomValidity('Ingresa el correo electrónico de origen.');

      $("#txtDestino")[0].reportValidity();
      $("#txtDestino")[0].setCustomValidity('Ingresa un correo electrónico válido.');

      $("#txtAsunto")[0].reportValidity();
      $("#txtAsunto")[0].setCustomValidity('Ingresa el asunto del correo.');

      $("#txaMensaje")[0].reportValidity();
      $("#txaMensaje")[0].setCustomValidity('Ingresa un mensaje del correo.');
      contadorEnviar = 1;
    }

    if (emailOrigen.trim() == "") {
      $("#txtOrigen")[0].reportValidity();
      $("#txtOrigen")[0].setCustomValidity('Ingresa el correo electrónico de origen.');
      return;
    }
    var validarEmailOrigen = isEmail(emailOrigen);
    if (validarEmailOrigen == false) {
      $("#txtOrigen")[0].reportValidity();
      $("#txtOrigen")[0].setCustomValidity('Ingresa un correo electrónico válido.');
      return;
    }

    if (emailDestino.length == 0) {
      $("#invalid-emailDestino").text("Ingresa el correo electrónico de destino.");
      $("#invalid-emailDestino").css("display", "block");
      setTimeout(function(){
        $("#invalid-emailDestino").fadeOut("slow");
      }, 2000)
      return;
    }

    var validarEmailDestino = isEmail(emailDestino);
    if (validarEmailDestino == false) {
      $("#invalid-emailDestino").text("Ingresa un correo electrónico válido.");
      $("#invalid-emailDestino").css("display", "block");
      return;
    }

    if (asunto.trim() == "") {
      $("#txtAsunto")[0].reportValidity();
      $("#txtAsunto")[0].setCustomValidity('Ingresa el asunto del correo.');
      return;
    }

    if (mensaje.trim() == "") {
      $("#txaMensaje")[0].reportValidity();
      $("#txaMensaje")[0].setCustomValidity('Ingresa un mensaje del correo.');
      return;
    }

    $("#btnCerrar").prop("disabled",true);
    $("#btnEnviar").prop("disabled",true);
    $("#loading").css("display", "flex");

    emailDestino.forEach( (indexEmailDestino)=>{
      $.ajax({
      type: 'POST',
      url: 'functions/enviar_Cotizacion.php',
      data: {
        txtId: id,
        txtOrigen: emailOrigen,
        txtDestino: indexEmailDestino,
        txtAsunto: asunto,
        txaMensaje: mensaje,
        csr_token_7ALF1: token
      },
      success: function(data) {
        if (data == "exito") {
          
          Lobibox.notify("success", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/checkmark.svg",
            msg: "¡Se ha enviado la cotización al correo seleccionado!",
          });
          
          setTimeout(function() {
            if(estatus == 0){
              $(location).attr('href', './');
            }
            else{
              $(location).attr('href', 'detalleCotizacion.php?id=' + id);
            }
          }, 3000);
          
          
        } else {
          Swal.fire("Error", 
            "No se realizó el envío del correo con la cotización, ¡Favor de intentarlo más tarde!", 
            "warning"
          );
          $("#btnCerrar").prop("disabled",false);
          $("#btnEnviar").prop("disabled",false);
          $("#loading").css("display", "none");
        }
      },
      error: function(){
        $("#btnCerrar").prop("disabled",false);
        $("#btnEnviar").prop("disabled",false);
        $("#loading").css("display", "none");
      }
    });
    });

});

  var todos = 0;
  $("#mostrar_todos").click(function() {

    if (todos == 0) {
      $.ajax({
        type: 'post',
        url: 'functions/actualizarProductos.php',
        data: {
          actualizar: todos
        },
        success: function(data) {
          $('#chosenProducto').html(data+'<option value="add" style="background-color: #15589B;  color:white; text-align:center; width:100%;">Añadir producto</option>');
        }
      });
      todos = 1;
      $("#mostrar_todos").html("Mostrar productos para venta");
    } else {
      $.ajax({
        type: 'post',
        url: 'functions/actualizarProductos.php',
        data: {
          actualizar: todos
        },
        success: function(data) {
          $('#chosenProducto').html(data+'<option value="add" style="background-color: #15589B;  color:white; text-align:center; width:100%;">Añadir producto</option>');
        }
      });
      todos = 0;
      $("#mostrar_todos").html("Mostrar todos los productos");
    }

  });

function cargarCMBTipo(input) {
  var html = "";
  var tipo;
  $.ajax({
    url: "functions/getCmbTipo.php",
    success: function (respuesta) {
      console.log("respuesta tipo producto: ", JSON.parse(respuesta));
      tipo = JSON.parse(respuesta);
      html += '<option data-placeholder="true"></option>';

      for(var i=0;i < tipo.length; i++) {
        html +=
          '<option value="' +
          tipo[i].PKTipoProducto +
          '" >' +
          tipo[i].TipoProducto +
          "</option>";
      };

      /*html += '<option value="add" style="background-color: #15589B;  color:white; text-align:center; width:100%;">Configurar tipos de producto</option>';*/

      $("#" + input + "").html(html);
    },
    error: function (error, data) {
      console.log(data);
    },
  });
}

function rfcValido(rfc, aceptarGenerico = true) {
  const re =
    /^([A-ZÑ&]{3,4}) ?(?:- ?)?(\d{2}(?:0[1-9]|1[0-2])(?:0[1-9]|[12]\d|3[01])) ?(?:- ?)?([A-Z\d]{2})([A\d])$/;
  var validado = rfc.match(re);

  if (!validado)
    //Coincide con el formato general del regex?
    return false;

  //Separar el dígito verificador del resto del RFC
  const digitoVerificador = validado.pop(),
    rfcSinDigito = validado.slice(1).join(""),
    len = rfcSinDigito.length,
    //Obtener el digito esperado
    diccionario = "0123456789ABCDEFGHIJKLMN&OPQRSTUVWXYZ Ñ",
    indice = len + 1;
  var suma, digitoEsperado;

  if (len == 12) suma = 0;
  else suma = 481; //Ajuste para persona moral

  for (var i = 0; i < len; i++)
    suma += diccionario.indexOf(rfcSinDigito.charAt(i)) * (indice - i);
  digitoEsperado = 11 - (suma % 11);
  if (digitoEsperado == 11) digitoEsperado = 0;
  else if (digitoEsperado == 10) digitoEsperado = "A";

  //El dígito verificador coincide con el esperado?
  // o es un RFC Genérico (ventas a público general)?
  if (
    digitoVerificador != digitoEsperado &&
    (!aceptarGenerico || rfcSinDigito + digitoVerificador != "XAXX010101000")
  )
    return false;
  else if (
    !aceptarGenerico &&
    rfcSinDigito + digitoVerificador == "XEXX010101000"
  )
    return false;
  return rfcSinDigito + digitoVerificador;
}

//Handler para el evento cuando cambia el input
// -Lleva la RFC a mayúsculas para validarlo
// -Elimina los espacios que pueda tener antes o después
function validarInput() {
  var vRFC = $("#txtRFC").val();
  var rfc = vRFC.trim().toUpperCase();
  var rfcCorrecto = rfcValido(rfc); // ⬅️ Acá se comprueba
  console.log("Correcto: " + rfcCorrecto);
  if (rfcCorrecto) {
    console.log("Válido");
    $("#invalid-rfc").css("display", "none");
    $("#invalid-rfc").text("El cliente debe tener un RFC.");
    $("#txtRFC").removeClass("is-invalid");
    escribirRFC();
  } else {
    console.log("No válido");
    $("#invalid-rfc").css("display", "block");
    $("#invalid-rfc").text("El RFC ingresado no es valido.");
    $("#txtRFC").addClass("is-invalid");
  }
}

function escribirRFC() {
  var rfc = $("#txtRFC").val();
  $.ajax({
    url: "../clientes/php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "validar_rfc_Cliente",
      data: rfc
    },
    dataType: "json",
    success: function (data) {
      console.log("respuesta RFC validado: ", data);
      // Validar si ya existe el identificador con ese nombre
      if (parseInt(data.existe) == 1) {
        $("#invalid-rfc").css("display", "block");
        $("#invalid-rfc").text("El RFC ingresado ya existe en el sistema.");
        $("#txtRFC").addClass("is-invalid");
        console.log("¡Ya existe!");
      } else {
        $("#invalid-rfc").css("display", "none");
        $("#invalid-rfc").text("El cliente debe tener un RFC.");
        $("#txtRFC").removeClass("is-invalid");
        console.log("¡No existe!");
      }
    },
  });
}

function validarCP(inpt, invalid_card) {
  var value = $("#"+inpt).val();
  var ercp = /(^([0-9]{5,5})|^)$/;
  $.ajax({
    url: "../clientes/php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "valid_cp",
      data: value
    },
    dataType: "json",
    success: function (respuesta) {
      if (!ercp.test(value) || !value || respuesta == false) {
        $("#"+invalid_card).css("display", "block");
        $("#"+invalid_card).text("El CP ingresado no es valido.");
        $("#"+inpt).addClass("is-invalid");
      } else {
        $("#"+invalid_card).css("display", "none");
        $("#"+invalid_card).text("El codigo postal.");
        $("#"+inpt).removeClass("is-invalid");
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
}

$(document).on("click", "#btnGenerarClave", function () {

  var categoria = $("#cmbTipoProducto").val();
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
  } else {
    limpieza = "N";
  }

  if (limpieza != "N") {
    $.ajax({
      url: "../inventarios_productos/php/funciones.php",
      data: { clase: "get_data", funcion: "get_claveReferencia" },
      dataType: "json",
      success: function (respuesta) {
        $("#txtClave").val(limpieza + "" + respuesta);
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

$(document).on("click", "#btnAgregarD", function () {
  if ($("#agregarDireccionCL")[0].checkValidity()) {
    var badSucursal =
      $("#invalid-sucursalD").css("display") === "block" ? false : true;
    var badCalle =
      $("#invalid-calleDire").css("display") === "block" ? false : true;
    var badNumExt =
      $("#invalid-numExt").css("display") === "block" ? false : true;
    var badEmail =
      $("#invalid-emailDire").css("display") === "block" ? false : true;
    var badColonia =
      $("#invalid-colonia").css("display") === "block" ? false : true;
    var badMunicipio = 
      $("#invalid-municipioDire").css("display") === "block" ? false : true;
    var badCP = 
      $("#invalid-cpDire").css("display") === "block" ? false : true;
    var badPais =
      $("#invalid-paisDire").css("display") === "block" ? false : true;
    var badEstado =
      $("#invalid-estadoDire").css("display") === "block" ? false : true;
    if (
      badSucursal &&
      badCalle &&
      badNumExt &&
      badEmail &&
      badColonia &&
      badCP &&
      badMunicipio &&
      badPais &&
      badEstado
    ) {
      if(!$("#chosen").val() || $("#chosen").val() == 0 || $("#chosen").val() == ''){
        Lobibox.notify("warning", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/warning_circle.svg",
          msg: "¡No se ha seleccionado un cliente!",
          sound: '../../../../../sounds/sound4'
        });
        return;
      }

      $("#btnAgregarD").prop("disabled", true);

      var sucursal = $("#txtSucursalD").val();
      var email = $("#txtEmailD").val();
      var calle = $("#txtCalle").val();
      var numExt = $("#txtNumExt").val();
      var numInt = "S/N";
      var colonia = $("#txtColonia").val();
      var municipio = $("#txtMunicipio").val();
      var pais = $("#cmbPaisD").val();
      var estado = $("#cmbEstadoD").val();
      var cp = $("#txtCPD").val();
      var pkDireccion = 0;
      var contacto = '';
      var telefono = '';

      $.ajax({
        url: "../clientes/php/funciones.php",
        data: {
          clase: "save_data",
          funcion: "save_direccionEnvio_Cliente",
          datos: sucursal,
          datos3: email,
          datos4: calle,
          datos5: numExt,
          datos6: numInt,
          datos7: colonia,
          datos8: municipio,
          datos9: pais,
          datos10: estado,
          datos11: cp,
          datos12: $("#chosen").val(),
          datos13: pkDireccion,
          datos14: contacto,
          datos15: telefono,
        },
        success: function (respuesta, status, xhr) {
          console.log(JSON.parse(respuesta)[0].status);
          console.log(status);
          console.log(xhr);
          $("#btnAgregarD").prop("disabled", false);
          $('#agregar_direccion_50').modal('hide');

            Lobibox.notify("success", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../../img/timdesk/checkmark.svg",
              msg: "¡Se guardó la dirección de envío con éxito!",
              sound: '../../../../../sounds/sound4'
            });
            $("#btnCancelar_newDire").click();
            cargarCMBDireccionesEnvio("cmbDireccionEntrega", $("#chosen").val());
        },
        error: function (error) {
          console.log(error);
          $("#btnAgregarD").prop("disabled", false);
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
    console.log("Faltan campos");
    if (!$("#txtSucursalD").val()) {
      $("#invalid-sucursalD").css("display", "block");
      $("#txtSucursalD").addClass("is-invalid");
    }
    if (!$("#txtEmailD").val()) {
      $("#invalid-emailDire").css("display", "block");
      $("#txtEmailD").addClass("is-invalid");
    }
    if (!$("#txtCalle").val()) {
      $("#invalid-calleDire").css("display", "block");
      $("#txtCalle").addClass("is-invalid");
    }
    if (!$("#txtNumExt").val()) {
      $("#invalid-numExt").css("display", "block");
      $("#txtNumExt").addClass("is-invalid");
    }
    if (!$("#txtColonia").val()) {
      $("#invalid-colonia").css("display", "block");
      $("#txtColonia").addClass("is-invalid");
    }
    if (!$("#txtMunicipio").val()) {
      $("#invalid-municipioDire").css("display", "block");
      $("#txtMunicipio").addClass("is-invalid");
    }
    if (!$("#cmbPaisD").val()) {
      $("#invalid-paisDire").css("display", "block");
      $("#cmbPaisD").addClass("is-invalid");
    }
    if (!$("#cmbEstadoD").val()) {
      $("#invalid-estadoDire").css("display", "block");
      $("#cmbEstadoD").addClass("is-invalid");
    }
    if (!$("#txtCPD").val()) {
      $("#invalid-cpDire").css("display", "block");
      $("#txtCPD").addClass("is-invalid");
    }
  }
});

$("#btnCancelar_newDire").on("click", ()=>{
  $("#agregarDireccionCL").trigger("reset");
  $("#invalid-sucursalD").css("display", "none");
  $("#txtSucursalD").removeClass("is-invalid");
  $("#invalid-emailDire").css("display", "none");
  $("#txtEmailD").removeClass("is-invalid");
  $("#invalid-calleDire").css("display", "none");
  $("#txtCalle").removeClass("is-invalid");
  $("#invalid-numExt").css("display", "none");
  $("#txtNumExt").removeClass("is-invalid");
  $("#invalid-colonia").css("display", "none");
  $("#txtColonia").removeClass("is-invalid");
  $("#invalid-municipioDire").css("display", "none");
  $("#txtMunicipio").removeClass("is-invalid");
  $("#invalid-paisDire").css("display", "none");
  $("#cmbPaisD").removeClass("is-invalid");
  $("#invalid-estadoDire").css("display", "none");
  $("#cmbEstadoD").removeClass("is-invalid");
  $("#invalid-cpDire").css("display", "none");
  $("#txtCPD").removeClass("is-invalid");
});

$("#agregar_direccion_50").on("hidden.bs.modal", ()=>{
  $("#agregarDireccionCL").trigger("reset");
  $("#invalid-sucursalD").css("display", "none");
  $("#txtSucursalD").removeClass("is-invalid");
  $("#invalid-emailDire").css("display", "none");
  $("#txtEmailD").removeClass("is-invalid");
  $("#invalid-calleDire").css("display", "none");
  $("#txtCalle").removeClass("is-invalid");
  $("#invalid-numExt").css("display", "none");
  $("#txtNumExt").removeClass("is-invalid");
  $("#invalid-colonia").css("display", "none");
  $("#txtColonia").removeClass("is-invalid");
  $("#invalid-municipioDire").css("display", "none");
  $("#txtMunicipio").removeClass("is-invalid");
  $("#invalid-paisDire").css("display", "none");
  $("#cmbPaisD").removeClass("is-invalid");
  $("#invalid-estadoDire").css("display", "none");
  $("#cmbEstadoD").removeClass("is-invalid");
  $("#invalid-cpDire").css("display", "none");
  $("#txtCPD").removeClass("is-invalid");
});

$("#txtSucursalD").change(()=>{
    $("#invalid-sucursalD").css("display", "none");
    $("#txtSucursalD").removeClass("is-invalid");
});

$("#txtEmailD").change(()=>{
    $("#invalid-emailDire").css("display", "none");
    $("#txtEmailD").removeClass("is-invalid");
});

$("#txtCalle").change(()=>{
    $("#invalid-calleDire").css("display", "none");
    $("#txtCalle").removeClass("is-invalid");
});

$("#txtNumExt").change(()=>{
    $("#invalid-numExt").css("display", "none");
    $("#txtNumExt").removeClass("is-invalid");
});

$("#txtColonia").change(()=>{
    $("#invalid-colonia").css("display", "none");
    $("#txtColonia").removeClass("is-invalid");
});

$("#txtMunicipio").change(()=>{
    $("#invalid-municipioDire").css("display", "none");
    $("#txtMunicipio").removeClass("is-invalid");
});

$("#cmbDireccionEntrega").change(()=>{
  if($("#cmbDireccionEntrega").val() == 'add'){
    selectDireccionEntrega.set(0);
    $("#agregar_direccion_50").modal('show');
  }
});

$(document).on("change", "#cmbPaisD", function(){
  let html = "";
  let PKPais = $("#cmbPaisD").val();
  $.ajax({
    url: "../ventas_directas/php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_estados", pais: PKPais },
    dataType: "json",
    success: function (respuesta) {
      html += '<option data-placeholder="true"></option>';
      $.each(respuesta, function (i) {
        html +=
          '<option value="' +
          respuesta[i].PKEstado +
          '" ' +
          ">" +
          respuesta[i].Estado +
          "</option>";
      });

      $("#cmbEstadoD").html(html);
    },
    error: function (error) {
      console.log(error);
    },
  });
});

function validInput(inputID, invalidDivID, textInvalidDiv) {
  if (!$("#" + inputID).val() || $("#" + inputID).val() == 0) {
    $("#" + inputID).addClass("is-invalid");
    $("#" + invalidDivID).css("display", "block");
    $("#" + invalidDivID).text(textInvalidDiv);
  } else {
    $("#" + inputID).removeClass("is-invalid");
    $("#" + invalidDivID).css("display", "none");
    $("#" + invalidDivID).text(textInvalidDiv);
    if(inputID == 'txtRFC'){
        let vRFC = $("#txtRFC").val()
        let rfc = vRFC.trim().toUpperCase();
        let rfcCorrecto = rfcValido(rfc); // ⬅️ Acá se comprueba
        if (rfcCorrecto) {
          $("#invalid-rfc").css("display", "none");
          $("#invalid-rfc").text("El cliente debe tener un RFC.");
          $("#txtRFC").removeClass("is-invalid");
          escribirRFC();
        } else {
          $("#invalid-rfc").css("display", "block");
          $("#invalid-rfc").text("El RFC ingresado no es valido.");
          $("#txtRFC").addClass("is-invalid");
        }
    }
  }
}

function escribirSucursal() {
  let sucursal = $("#txtSucursalD").val();
  let id = $("#chosen").val();

  $.ajax({
    url: "../clientes/php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "validar_sucursal_Cliente",
      data: sucursal,
      data2: id,
    },
    dataType: 'json',
    success: function (data) {
      console.log(data);
      /* Validar si ya existe el identificador con ese nombre*/
      if (parseInt(data[0]["existe"]) == 1) {
        $("#invalid-sucursalD").css("display", "block");
        $("#invalid-sucursalD").text(
          "La sucursal ya esta registrada en el sistema."
        );
        $("#txtSucursalD").addClass("is-invalid");
      } else {
        $("#invalid-sucursalD").css("display", "none");
        $("#invalid-sucursalD").text(
          "La dirección debe tener un nombre de sucursal."
        );
        $("#txtSucursalD").removeClass("is-invalid");
        if (!sucursal) {
          $("#invalid-sucursalD").css("display", "block");
          $("#invalid-sucursalD").text(
            "La dirección debe tener un nombre de sucursal."
          );
          $("#txtSucursalD").addClass("is-invalid");
        }
      }
    },
  });
}

function validarCorreo(value, inpt, invalid_card) {
  var reg =
    /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

  var regOficial =
    /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/;

  //Se muestra un texto a modo de ejemplo, luego va a ser un icono
  if (reg.test(value) && regOficial.test(value)) {
    $("#"+invalid_card).css("display", "none");
    $("#"+invalid_card).text("E-mail inválido.");
    $("#"+inpt).removeClass("is-invalid");
  } else if (reg.test(value)) {
    $("#"+invalid_card).css("display", "none");
    $("#"+invalid_card).text("E-mail inválido.");
    $("#"+inpt).removeClass("is-invalid");
  } else {
    $("#"+invalid_card).css("display", "block");
    $("#"+invalid_card).text("E-mail inválido.");
    $("#"+inpt).addClass("is-invalid");
  }
}

function resetForm(frm){
  form=document.getElementById(frm);
  form.reset();

  if(frm == "agregarDireccionCL"){
    $("#invalid-sucursalD").css("display", "none");
    $("#txtSucursalD").removeClass("is-invalid");

    $("#invalid-emailDire").css("display", "none");
    $("#txtEmailD").removeClass("is-invalid");

    $("#invalid-numExt").css("display", "none");
    $("#txtNumExt").removeClass("is-invalid");

    $("#invalid-txtMunicipio").css("display", "none");
    $("#municipioDire").removeClass("is-invalid");

    $("#invalid-colonia").css("display", "none");
    $("#txtColonia").removeClass("is-invalid");

    $("#invalid-paisDire").css("display", "none");
    $("#cmbPaisD").removeClass("is-invalid");

    $("#invalid-estadoDire").css("display", "none");
    $("#cmbEstadoD").removeClass("is-invalid");

    $("#invalid-cpDire").css("display", "none");
    $("#txtCPD").removeClass("is-invalid");

    $("#invalid-calleDire").css("display", "none");
    $("#txtCalle").removeClass("is-invalid");

    
  }else if(frm == "agregarCliente"){
    $('.DataClient_invoice').css({'display': 'none','opacity': '0','visibility': 'hidden'});

    $("#cmbMedioContactoCliente").trigger("change");
    $("#cmbVendedorNC").trigger("change");
    SS_cmbPais.set();
    SS_cmbEstado.set();

    $("#invalid-nombreCom").css("display", "none");
    $("#txtNombreComercial").removeClass("is-invalid");

    /* $("#invalid-medioCont").css("display", "none");
    $("#cmbMedioContactoCliente").removeClass("is-invalid"); */

    /* $("#invalid-vendedorNC").css("display", "none");
    $("#cmbVendedorNC").removeClass("is-invalid"); */

    /* $("#invalid-email").css("display", "none");
    $("#txtEmail").removeClass("is-invalid"); */

    $("#invalid-razon").css("display", "none");
    $("#txtRazonSocial").removeClass("is-invalid");

    $("#invalid-rfc").css("display", "none");
    $("#txtRFC").removeClass("is-invalid");

    $("#cmbRegimen").trigger("change");
    $("#invalid-regimen").css("display", "none");
    $("#cmbRegimen").removeClass("is-invalid");

    $("#invalid-cp").css("display", "none");
    $("#txtCP").removeClass("is-invalid");

    /* $("#invalid-paisFisc").css("display", "none");
    $("#cmbPais").removeClass("is-invalid"); */

    /* $("#invalid-paisEstadoFisc").css("display", "none");
    $("#cmbEstado").removeClass("is-invalid"); */
  }else if(frm == "agregarLocacion"){
    $("#invalid-nombreSuc").css("display", "none");
    $("#txtarea").removeClass("is-invalid");

    SS_txtarea6.set();
    SS_txtarea8.set();

    /* $("#invalid-calleSuc").css("display", "none");
    $("#txtarea2").removeClass("is-invalid");

    $("#invalid-noExtSuc").css("display", "none");
    $("#txtarea3").removeClass("is-invalid");

    $("#invalid-coloniaSuc").css("display", "none");
    $("#txtarea5").removeClass("is-invalid");

    $("#invalid-municipioSuc").css("display", "none");
    $("#txtarea7").removeClass("is-invalid");

    $("#invalid-paisSuc").css("display", "none");
    $("#txtarea8").removeClass("is-invalid");

    $("#invalid-estadoSuc").css("display", "none");
    $("#txtarea6").removeClass("is-invalid");

    $("#invalid-telSuc").css("display", "none");
    $("#txtarea10").removeClass("is-invalid"); */
  }else if(frm == "agregarEmpleado"){
    $("#invalid-nombre").css("display", "none");
    $("#txtNombre").removeClass("is-invalid");

    $("#invalid-primerApellido").css("display", "none");
    $("#txtPrimerApellido").removeClass("is-invalid");

    /* $("#invalid-genero").css("display", "none");
    $("#cmbGenero").removeClass("is-invalid"); */

    $("#invalid-cpE").css("display", "none");
    $("#txtCPE").removeClass("is-invalid");

   /*  $("#invalid-estadoNE").css("display", "none");
    $("#cmbEstado_NE").removeClass("is-invalid");

    $("#invalid-roles").css("display", "none");
    $("#cmbRoles").removeClass("is-invalid"); */

    SS_genero.set();
    SS_cmbEstado_NE.set();
    SS_cmbRoles.set(1);
  }else if(frm == "agregarProductoForm"){
    $("#invalid-nombreProducto").css("display", "none");
    $("#txtProducto").removeClass("is-invalid");

    $("#invalid-clave").css("display", "none");
    $("#txtClave").removeClass("is-invalid");

    SS_cmbTipoProducto.set();
    $("#invalid-tipoProd").css("display", "none");
    $("#cmbTipoProducto").removeClass("is-invalid");
  }
}

  var selectProductos = new SlimSelect({
    select: '#chosenProducto',
    deselectLabel: '<span class="">✖</span>',
    addable: function (value) {
      $("#agregar_Producto").modal("toggle");
      cargarCMBImpuestos("1", "cmbImpuestos");
      cargarCMBTasaImpuestos("1","cmbTasaImpuestos");
      return;
    }
  });

  var selectCliente = new SlimSelect({
    select: '#chosen',
    deselectLabel: '<span class="">✖</span>',
    addable: function (value) {
      $("#agregar_Cliente_50").modal("toggle");
      return;
    }
  });

  var selectSucursal = new SlimSelect({
    select: '#chosenSucursal',
    deselectLabel: '<span class="">✖</span>',
    addable: function (value) {
      $('#agregar_Locacion').modal('show');
      return;
    }
  });

  var selectVendedor = new SlimSelect({
    select: '#cmbVendedor',
    deselectLabel: '<span class="">✖</span>',
    addable: function (value) {
      $('#agregar_Empleado').modal('show');
      return;
    }
  });

  var selectDireccionEntrega = new SlimSelect({
    select: '#cmbDireccionEntrega',
    deselectLabel: '<span class="">✖</span>',
    addable: function (value) {
      $('#agregar_direccion_50').modal('show');
      return;
    }
  });

  var selectCondicionPago = new SlimSelect({
    select: '#cmbCondicionPago',
    deselectLabel: '<span class="">✖</span>',
  });

var cmbPaisD = new SlimSelect({
  select: '#cmbPaisD',
  deselectLabel: '<span class="">✖</span>',
  onChange: (info) => {
    $("#invalid-paisDire").css("display", "none");
  }
});

var cmbEstadoD = new SlimSelect({
  select: '#cmbEstadoD',
  deselectLabel: '<span class="">✖</span>',
  onChange: (info) => {
    $("#invalid-estadoDire").css("display", "none");
  }
});

  cmbMoneda = new SlimSelect({
    select: '#cmbMoneda',
    deselectLabel: '<span class="">✖</span>',
  });

  /* new Cleave('.txtPrecio', {
    numeral: true,
    numeralDecimalMark: '.',
    delimiter: ','
  }); */

  $(document).on('input', '.cantidadProducto',  function(){
  
   var regexp = /[^0-9]/g;
    if ($(this).val().match(regexp)) {
      $(this).val($(this).val().replace(regexp, ""));
    }

  });

  function loadComboSucursal(){
    var html = '<option value="0">Elegir opción</option>';
    $.ajax({
      url:"php/funciones.php",
      data:{clase:"get_data", funcion:"get_sucursalCombo"},
      dataType:"json",
      success:function(respuesta){
        if(respuesta !== "" && respuesta !== null && respuesta.length > 0){
          $.each(respuesta,function(i){
            html += '<option value="'+respuesta[i].PKData+'">'+respuesta[i].Data+'</option>';
          });
          html += '<option value="add" style="background-color: #15589B;  color:white; text-align:center; width:100%;">Añadir sucursal</option>';
        }else{
          html += '<option value="vacio">No hay productos que mostrar</option>';
        }

        $('#chosenSucursal').html(html);

      },
      error:function(error){
        console.log(error);
      }

    });
  }
  function loadComboCliente(){
    var html = '<option value="0">Elegir opción</option>';
    $.ajax({
      url:"php/funciones.php",
      data:{clase:"get_data", funcion:"get_clienteCombo"},
      dataType:"json",
      success:function(respuesta){
        if(respuesta !== "" && respuesta !== null && respuesta.length > 0){
          $.each(respuesta,function(i){
            html += '<option value="'+respuesta[i].PKData+'">'+respuesta[i].Data+'</option>';
          });
          html += '<option value="add" style="background-color: #15589B;  color:white; text-align:center; width:100%;">Añadir cliente</option>';
        }else{
          html += '<option value="vacio">No hay productos que mostrar</option>';
        }

        $('#chosen').html(html);

      },
      error:function(error){
        console.log(error);
      }

    });
  }
  function loadComboVendedor(){
    var html = '<option value="0">Elegir opción</option>';
    $.ajax({
      url:"php/funciones.php",
      data:{clase:"get_data", funcion:"get_vendedorCombo"},
      dataType:"json",
      success:function(respuesta){
        if(respuesta !== "" && respuesta !== null && respuesta.length > 0){
          $.each(respuesta,function(i){
            html += '<option value="'+respuesta[i].PKData+'">'+respuesta[i].Data+'</option>';
          });
          html += '<option value="add" style="background-color: #15589B;  color:white; text-align:center; width:100%;">Añadir vendedor</option>';
        }else{
          html += '<option value="vacio">No hay productos que mostrar</option>';
        }

        $('#cmbVendedor').html(html);

      },
      error:function(error){
        console.log(error);
      }

    });
  }
  function loadComboProducto(cliente){
    var html = '<option value="0">Elegir opción</option>';
    $.ajax({
      url:"php/funciones.php",
      data:{clase:"get_data", funcion:"get_productoCombo",value:cliente},
      dataType:"json",
      success:function(respuesta){
        if(respuesta !== "" && respuesta !== null && respuesta.length > 0){
          $.each(respuesta,function(i){
            html += '<option value="'+respuesta[i].PKData+'">'+respuesta[i].Data+'</option>';
          });
          html += '<option value="add" style="background-color: #15589B;  color:white; text-align:center; width:100%;">Añadir producto</option>';
        }else{
          html += '<option value="vacio">No hay productos que mostrar</option>';
        }

        $('#chosenProducto').html(html);

      },
      error:function(error){
        console.log(error);
      }

    });
  }
  function descargarCotizacion(idCotizacion) {

    $().redirect('functions/descargar_Cotizacion.php', {
      'idCotizacion': idCotizacion,
    },"post","_blank");
  }
  </script>
  <script>
  var ruta = "../";
  </script>
  <script>
    loadAlertsNoti('alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
    setInterval(loadAlertsNoti, 10000, 'alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
  </script>
  <script src="js/unidadesSAT.js" charset="utf-8"></script>
</body>

</html>