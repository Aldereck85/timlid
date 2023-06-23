<?php
session_start();
require_once '../../../../include/db-conn.php';
$user = $_SESSION["Usuario"];
$PKEmpresa = $_SESSION["IDEmpresa"];

$PKVenta = $_GET["vd"];

$stmt = $conn->prepare("SELECT vd.empresa_id, vd.FKEstatusVenta, s.activar_inventario as IsInventario, tp.numTiposProd, tp.FKTipoProducto 
                        FROM ventas_directas vd 
                        INNER JOIN sucursales s on vd.FKSucursal = s.id 
                        left join (select PKVentaDirecta, count(FKTipoProducto) as numTiposProd, FKTipoProducto from (   
                          select DISTINCT vd.PKVentaDirecta, p.FKTipoProducto 
                          from productos p 
                          inner join detalle_venta_directa dv on p.PKProducto = dv.FKProducto
                          inner join ventas_directas as vd on vd.PKVentaDirecta = dv.FKVentaDirecta
                          where vd.empresa_id = 1	and vd.PKVentaDirecta = :id		
                        ) as tipoProds group by PKVentaDirecta) as tp on tp.PKVentaDirecta = vd.PKVentaDirecta
                        WHERE vd.PKVentaDirecta = :id2");
$stmt->bindValue(':id', $PKVenta, PDO::PARAM_INT);
$stmt->bindValue(':id2', $PKVenta, PDO::PARAM_INT);
$stmt->execute();
$row = $stmt->fetch();

if($row['numTiposProd'] == 1 && $row['FKTipoProducto'] == 5){
  $continue=0;
}else{
  $continue=1;
}

$GLOBALS["PKVentaDirecta"] = $row['empresa_id'];
$GLOBALS["PKEstatusVenta"] = $row['FKEstatusVenta'];
$GLOBALS["IsInventario"] = $row['IsInventario'];

if (isset($_SESSION["Usuario"])) {
  require_once '../../../../include/db-conn.php';
  $user = $_SESSION["Usuario"];

  if ($GLOBALS["PKVentaDirecta"] != $PKEmpresa) {
    header("location:../../../ventas_directas/catalogos/ventas/");
  } else if ($GLOBALS["PKEstatusVenta"] != 1 && $GLOBALS["IsInventario"] != 0 && $continue == 1) {
    header("location:../../../ventas_directas/catalogos/ventas/ver_ventas.php?vd=" . $PKVenta);
  }
} else {
  header("location:../../../dashboard.php");
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
  <title>Timlid | Editar Venta</title>

  <!-- ESTILOS -->
  <link href="../../../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../../../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../../../css/slimselect.min.css" rel="stylesheet">
  <link href="../../style/agregar_entrada.css" rel="stylesheet">
  <link href="../../../../css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="../../../../vendor/datatables/responsive.dataTables.css" rel="stylesheet">
  <link href="../../../../vendor/datatables/buttons.dataTables.css" rel="stylesheet">
  <link href="../../../../css/stylesNewTable.css" rel="stylesheet">
  <link href="../../../../css/styles.css" rel="stylesheet">
  <link href="../../../../css/croppie.css" rel="stylesheet">
  <link href="../../../../css/sweetalert2.css" rel="stylesheet">
  <link href="../../../../css/notificaciones.css" rel="stylesheet">
  <link href="../../style/ventas.css" rel="stylesheet">
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
  <script src="../../js/editar_ventas.js" charset="utf-8"></script>
  <script src="../../../../js/numeral.min.js"></script>
  <link href="../../../../css/lobibox.min.css" rel="stylesheet">
  <script src="../../../../js/jquery.redirect.min.js"></script>
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
        $icono = 'ICONO-NOTA-DE-VENTA-AZUL.svg';
        $titulo = 'Editar venta';
        $backIcon = true;
        require_once $rutatb . 'topbar.php';
        ?>

        <input type="hidden" id="txtUsuario" value="<?= $_SESSION['PKUsuario']; ?>">
        <input type="hidden" id="txtRuta" value="<?= $ruta; ?>">
        <input type="hidden" id="txtEdit" value="<?= $ruteEdit; ?>">
        <input type="hidden" id="txtPantalla" value="13">
        <input type="hidden" id="txtPKVenta" value="<?= $PKVenta ?>">
        <input type="hidden" id="txtPKVentaEncrip" value="">

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
                      <form action="" method="post" id="frmVentaDirectaEdit">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-12">
                                    <p class="bar-title">Información de venta</p>
                                </div>
                            </div>  
                          <div class="row">
                            <div class="col-lg-12">
                              <span class="btn-table-custom btn-table-custom--blue" name="btnCancelar" id="btnCancelar" onclick="cambiarEstatusVentaDirecta(5)"><i class="fa fa-times-circle" aria-hidden="true"></i> Cerrar venta</span>
                              <span class="btn-table-custom btn-table-custom--blue" name="btnComentar" id="btnComentar" onclick="verVentaDirecta()"><i class="fa fa-eye" aria-hidden="true"></i> Ver venta</span>
                              <span id="isPermissionsFacturar">
                              </span>
                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="row">
                            <div class="col-lg-4">
                              <label for="usr">Referencia:*</label>
                              <input type="text" class="form-control alphaNumeric-only" maxlength="20" name="txtReferencia" id="txtReferencia" readonly>
                            </div>
                            <div class="col-lg-4">
                              <label for="usr">Fecha de emisión:*</label>
                              <input type="date" class="form-control" maxlength="20" id="txtFechaEmision" readonly>
                            </div>
                            <div class="col-lg-2">
                              <label for="usr">Fecha de vencimiento:*</label>
                              <input type="date" class="form-control" maxlength="20" name="txtFechaEstimada" id="txtFechaEstimada" required min="">
                              <input type="date" class="form-control" maxlength="20" name="txtFechaEstimadaMin" id="txtFechaEstimadaMin" style="display:none">
                            </div>
                            <div class="col-lg-2">
                              <label for="cmbMoneda">Moneda:*</label>
                              <select name="cmbMoneda" id="cmbMoneda" required>
                                <option value="0" disabled hidden>Seleccione una moneda...</option>
                                <option value="49">EUR</option>
                                <option value="149">USD</option>
                                <option value="100">MXN</option>
                              </select>
                              <div class="invalid-feedback" id="invalid-moneda">El cliente debe tener una moneda.</div>
                              </div>
                          </div>
                          <div class="form-group">
                            <div class="row">
                              <div class="col-lg-3">
                                <label for="usr">Cliente:*</label>
                                <select name="cmbCliente" id="cmbCliente" onchange="cambioCliente()" disabled="disabled"></select>
                              </div>
                              <div class="col-lg-3">
                                <label for="cmbDireccionEnvio">Sucursal de entrega:*</label>
                                <select name="cmbDireccionEnvio" id="cmbDireccionEnvio" onchange="cambioSucursal()"></select>
                              </div>
                              <div class="col-lg-3">
                                <label for="cmbDireccionEnvio">Vendedor:*</label>
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
                                    <label for="usr">Producto:*</label>
                                  </div>
                                  <div class="col-lg-9 noVer">
                                    <input type="checkbox" id="chkcmbTodoProducto" disabled="disabled"> <label for="">Cargar todos los productos</label>
                                  </div>
                                </div>
                                <select name="cmbProducto" id="cmbProducto"></select>
                                <select name="cmbTodoProducto" id="cmbTodoProducto" style="display:none;"></select>
                                <div class="row">
                                  <div class="col-lg-12">
                                    <button style="width: 100%;font-size: 14px;" type="button" class="btn-custom btn-custom--border-blue mt-2" id="mostrarTodos" disabled=""><span id="textoMos">Mostrar todos los productos</span></button>
                                  </div>
                                </div>
                              </div>
                              <div class="col-lg-6" id="inventarioStock">
                                <div class="form-group">
                                  <div class="row">
                                    <div class="col-lg-6">
                                      <label for="usr">Cantidad:*</label>
                                      <input type="number" class="form-control numeric-only txtCantidad" maxlength="8" name="txtCantidad" id="txtCantidad" value="0">
                                      <div class="invalid-feedback" id="invalid-productoCnt">El producto debe tener una cantidad.</div>
                                      <input type="hidden" name="txtCantidadHis" id="txtCantidadHis">
                                    </div>
                                    <div class="col-lg-6">
                                      <label for="usr">Precio unitario:*</label>
                                      <div class="input-group">
                                        <div class="input-group-prepend">
                                          <span class="input-group-text">$</span>
                                        </div>
                                        <input type="text" class="form-control numericDecimal-only" maxlength="10" name="txtPrecioUnitario" id="txtPrecioUnitario">
                                        <div class="invalid-feedback" id="invalid-precioUnit">El producto debe tener un precio unitario.</div>
                                      </div>
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
                                <button class="btn-custom btn-custom--blue" style="position: relative; top: 32px;width: 100%;" type="button" id="agregarProducto" name="agregarProducto" onclick="agregarProd()"><i class="fa fa-plus-circle" aria-hidden="true"></i> Agregar producto</button>
                              </div>
                              <label for="">* Campos requeridos</label>
                            </div>
                          </div>

                          <div class="form-group">
                            <!-- DataTales Example -->
                            <div class="mb-4">
                              <div class="">
                                <div class="table-responsive">
                                  <table class="table" id="tblListadoVentasDirectasEdit" width="100%" cellspacing="0">
                                    <thead>
                                      <tr>
                                        <th>ID</th>
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
                                  <td style="color: var(--color-primario)">$ <span id="Subtotal">0.00</span>
                                  </td>
                                  <th style="width:60px;"></th>
                                </tr>
                                <tr>
                                  <th style="color: var(--color-primario)"><b>Impuestos: </b></th>
                                  <td id="impuestos"></td>
                                  <th></th>
                                </tr>
                                <tr class="total redondearAbajoIzq">
                                  <th style="color: var(--color-primario)" class="redondearAbajoIzq"><b>Total: </b></th>
                                  <td style="color: var(--color-primario)"><b>$ <span id="Total">0.00</span></b></td>
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

                          <button type="button" class="btn-custom btn-custom--blue" name="btnAgregar" id="btnAgregar" onclick="enviarVentaDirecta()" style="float:right">Guardar cambios</button>
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
  <script src="../../../../js/lobibox.min.js"></script>
  <script>
    loadAlertsNoti('alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
    setInterval(loadAlertsNoti, 10000, 'alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit')
      .val());
  </script>
</body>

</html>