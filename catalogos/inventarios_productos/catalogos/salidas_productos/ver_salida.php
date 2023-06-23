<?php
session_start();
require_once '../../../../include/db-conn.php';
$user = $_SESSION["Usuario"];
$PKEmpresa = $_SESSION["IDEmpresa"];

$folio = $_GET['f'];

$stmt = $conn->prepare("SELECT * from (
  SELECT distinct 
            isps.orden_pedido_id as pk,
                if(isps.orden_pedido_id is null or isps.orden_pedido_id = 0, 
                  if(isps.devolucion_id is null or isps.devolucion_id = 0, 
                  0,
                    pr.empresa_id
                  ),
                  opps.empresa_id
                ) as empresa,
                if(ieps.inventario_salida_id is null or ieps.inventario_salida_id = null, 0, 1) as is_movimiento,
                if( ( ifnull(c.estatus_factura_id,0) != 1 and ifnull(c.estatus_factura_id,0) != 2 /*and ifnull(c.estatus_factura_id,0) != 4*/),0,1)as is_facturado, 
                if( ( ifnull(vd.FKEstatusVenta,0) != 2 and ifnull(vd.FKEstatusVenta,0) != 5),0,1)as is_facturadoV,
                isps.tipo_salida as tipoId,
                  (if (opps.numero_cotizacion is not null and  opps.numero_cotizacion != '' and  opps.numero_cotizacion != 0 and opps.tipo_pedido = 3,
                  '1',
                  if (opps.numero_venta_directa is not null and  opps.numero_venta_directa != '' and  opps.numero_venta_directa != 0 and opps.tipo_pedido = 4,
                    '2',
                    if(opps.sucursal_destino_id is not null and opps.sucursal_destino_id != ''  and opps.sucursal_destino_id != 0 and opps.tipo_pedido = 3,
                    '3',
                    if(isps.devolucion_id is not null and isps.devolucion_id != ''  and isps.devolucion_id != 0,
                      '4',
                      if( (opps.sucursal_destino_id is not null and opps.sucursal_destino_id != ''  and opps.sucursal_destino_id != 0) or (opps.cliente_id is not null and opps.cliente_id != ''  and opps.cliente_id != 0) and opps.tipo_pedido = 2,
                      '5',
                              '0'
                            )
                    )
                    )
                  )
                  )
                  ) as tipo,
                  (if (opps.numero_cotizacion is not null and  opps.numero_cotizacion != '' and  opps.numero_cotizacion != 0 and opps.tipo_pedido = 3,
                  '(Cotización)',
                  if (opps.numero_venta_directa is not null and  opps.numero_venta_directa != '' and  opps.numero_venta_directa != 0 and opps.tipo_pedido = 4,
                    '(Venta)',
                    if(opps.sucursal_destino_id is not null and opps.sucursal_destino_id != ''  and opps.sucursal_destino_id != 0 and opps.tipo_pedido = 1,
                    '(Traspaso)',
                    if(isps.devolucion_id is not null and isps.devolucion_id != ''  and isps.devolucion_id != 0,
                      '(Devolución)',
                      if( (opps.sucursal_destino_id is not null and opps.sucursal_destino_id != ''  and opps.sucursal_destino_id != 0) or (opps.cliente_id is not null and opps.cliente_id != ''  and opps.cliente_id != 0) and opps.tipo_pedido = 2,
                      ' (General)',
                      ''
                      )
                    )
                    )
                  )
                  )
                  ) as Ttipo
                FROM inventario_salida_por_sucursales isps
                  left join inventario_entrada_por_sucursales ieps on isps.id = ieps.inventario_salida_id
                  left join orden_pedido_por_sucursales opps on isps.orden_pedido_id = opps.id
                  left join cotizacion c on opps.numero_cotizacion = c.PKCotizacion
                  left join ventas_directas vd on opps.numero_venta_directa = vd.PKVentaDirecta
                  left join devolucion_por_sucursales dps on isps.devolucion_id = dps.id
                  left join proveedores pr on dps.proveedor_id = pr.PKProveedor
                  left join clientes clg on opps.cliente_id = clg.PKCliente
                  inner join sucursales s on isps.sucursal_id = s.id 
                WHERE isps.folio_salida = :id and s.empresa_id = :empresa
                
                union 
                
                SELECT distinct 
                msssi.FKOrdenPedido as pk,
                if(msssi.FKOrdenPedido is null or msssi.FKOrdenPedido = 0, 
                  0,
                  opps.empresa_id
                ) as empresa,
                0 as is_movimiento,
                if( ( ifnull(c.estatus_factura_id,0) != 1 and ifnull(c.estatus_factura_id,0) != 2 /*and ifnull(c.estatus_factura_id,0) != 4*/),0,1)as is_facturado, 
                if( ( ifnull(vd.FKEstatusVenta,0) != 2 and ifnull(vd.FKEstatusVenta,0) != 5),0,1)as is_facturadoV,
                1 as tipoId,
                  (if (opps.numero_cotizacion is not null and  opps.numero_cotizacion != '' and  opps.numero_cotizacion != 0 and opps.tipo_pedido = 3,
                  '1',
                  if (opps.numero_venta_directa is not null and  opps.numero_venta_directa != '' and  opps.numero_venta_directa != 0 and opps.tipo_pedido = 4,
                    '2',
                    if(opps.sucursal_destino_id is not null and opps.sucursal_destino_id != ''  and opps.sucursal_destino_id != 0 and opps.tipo_pedido = 3,
                    '3',
                      if( (opps.sucursal_destino_id is not null and opps.sucursal_destino_id != ''  and opps.sucursal_destino_id != 0) or (opps.cliente_id is not null and opps.cliente_id != ''  and opps.cliente_id != 0) and opps.tipo_pedido = 2,
                      '5',
                              '0'
                            )
                    )
                  )
                  )
                  ) as tipo,
                  (if (opps.numero_cotizacion is not null and  opps.numero_cotizacion != '' and  opps.numero_cotizacion != 0 and opps.tipo_pedido = 3,
                  '(Cotización)',
                  if (opps.numero_venta_directa is not null and  opps.numero_venta_directa != '' and  opps.numero_venta_directa != 0 and opps.tipo_pedido = 4,
                    '(Venta)',
                    if(opps.sucursal_destino_id is not null and opps.sucursal_destino_id != ''  and opps.sucursal_destino_id != 0 and opps.tipo_pedido = 1,
                    '(Traspaso)',
                      if( (opps.sucursal_destino_id is not null and opps.sucursal_destino_id != ''  and opps.sucursal_destino_id != 0) or (opps.cliente_id is not null and opps.cliente_id != ''  and opps.cliente_id != 0) and opps.tipo_pedido = 2,
                      ' (General)',
                      ''
                      )
                    )
                  )
                  )
                  ) as Ttipo
                FROM movimientos_salidas_servicios_sin_inventario msssi
                  left join orden_pedido_por_sucursales opps on msssi.FKOrdenPedido = opps.id
                  left join cotizacion c on opps.numero_cotizacion = c.PKCotizacion
                  left join ventas_directas vd on opps.numero_venta_directa = vd.PKVentaDirecta
                  left join clientes clg on opps.cliente_id = clg.PKCliente
                  inner join sucursales s on opps.sucursal_origen_id = s.id 
                WHERE msssi.FKSalida = :id2 and s.empresa_id = :empresa2
    ) as tabla
    group by pk;");
$stmt->bindValue(':id', $folio, PDO::PARAM_INT);
$stmt->bindValue(':empresa', $PKEmpresa, PDO::PARAM_INT);
$stmt->bindValue(':id2', $folio, PDO::PARAM_INT);
$stmt->bindValue(':empresa2', $PKEmpresa, PDO::PARAM_INT);
$stmt->execute();
$row = $stmt->fetch();


$GLOBALS["PKEmpresaISPS"] = $row['empresa'];
$GLOBALS["IsMovimiento"] = $row['is_movimiento'];
$GLOBALS["TipoSalida"] = $row['tipoId'];
/*1: Cotizacion / 2: Venta directa / 3: Traspaso / 0: Ninguno*/
$GLOBALS["TipoPedidoSalida"] = $row['tipo'];
$GLOBALS["Ttipo"] = $row['Ttipo'];

if (isset($_SESSION["Usuario"])) {
  require_once '../../../../include/db-conn.php';
  $user = $_SESSION["Usuario"];

  if ($GLOBALS["PKEmpresaISPS"] != $PKEmpresa) {
    header("location:../../../inventarios_productos/catalogos/salidas_productos/");
  }
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

  <title>Timlid | Ver salida</title>

  <!-- Bootstrap core JavaScript-->
  <script src="../../../../vendor/jquery/jquery.min.js"></script>
  <script src="../../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="../../../../vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Page level plugins -->
  <script src="../../../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../../../vendor/datatables/dataTables.buttons.js"></script>
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
  <link href="../../style/ver_salidas.css" rel="stylesheet">

  <!-- Custom scripts for all pages-->

  <script src="../../../../js/sb-admin-2.min.js"></script>
  <script src="../../../../js/scripts.js"></script>

  <!-- Custom styles for this page -->
  <link href="../../../../css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="../../../../vendor/datatables/buttons.dataTables.css" rel="stylesheet">
  <link href="../../../../css/styles.css" rel="stylesheet">
  <link href="../../../../css/stylesNewTable.css" rel="stylesheet">
  <link href="../../../../css/croppie.css" rel="stylesheet">

  <script src="../../../../js/numeral.min.js" charset="utf-8"></script>
  <script src="../../../../js/croppie.js"></script>

  <script src="../../../../js/sweet/sweetalert2.js"></script>
  <link href="../../../../css/sweetalert2.css" rel="stylesheet">

  <script src="../../../../js/notificaciones_timlid.js" charset="utf-8"></script>
  <script src="../../js/ver_salidas.js" charset="utf-8"></script>
  <script src="../../../../js/numeral.min.js"></script>
  <link href="../../../../css/lobibox.min.css" rel="stylesheet">

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
        $icono = 'ICONO-SALIDAS-AZUL.svg';
        $titulo = 'Ver salida ' . $GLOBALS["Ttipo"];
        $backIcon = true;
        require_once $rutatb . 'topbar.php';
        ?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- DataTales Example -->
          <div class="card mb-4">
            <div class="card-header py-3" style="background:transparent">
            </div>
            <div class="card-body py-3">
              <div class="data-container">
                <form id="frmSalidaOP">
                  <div class="form-group">
                    <div class="row">
                      <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-6">
                        <div class="orderPedido-disabled">
                          <label for="cmbOrderPedido">Pedido*:</label>
                          <input class="form-control" name="cmbOrderPedido" id="cmbOrderPedido" required readonly>
                        </div>
                        <div class="quote-disabled">
                          <label for="cmbOrderPedidoQuote">Pedido*:</label>
                          <input class="form-control" name="cmbOrderPedidoQuote" id="cmbOrderPedidoQuote" required readonly>
                        </div>
                        <div class="sales-disabled">
                          <label for="cmbOrderPedidoSales">Pedido*:</label>
                          <input class="form-control" name="cmbOrderPedidoSales" id="cmbOrderPedidoSales" required readonly>
                        </div>
                        <div class="return-disabled">
                          <label for="cmbOrderPedidoReturn">Pedido*:</label>
                          <input class="form-control" name="cmbOrderPedidoReturn" id="cmbOrderPedidoReturn" required readonly>
                        </div>
                        <div class="orderPedidoGral-disabled">
                          <label for="cmbOrderPedidoGral">Pedido*:</label>
                          <input class="form-control" name="cmbOrderPedidoGral" id="cmbOrderPedidoGral" required readonly>
                        </div>
                      </div>
                      <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-6">
                        <div class="orderPedido-disabled">
                          <label for="cmbSucursalOrigen">Sucursal de origen:</label>
                          <input class="form-control" name="cmbSucursalOrigen" id="cmbSucursalOrigen" required readonly>
                        </div>
                        <div class="quote-disabled">
                          <label for="cmbSucursalOrigenQuote">Sucursal de origen:</label>
                          <input class="form-control" name="cmbSucursalOrigenQuote" id="cmbSucursalOrigenQuote" required readonly>
                        </div>
                        <div class="sales-disabled">
                          <label for="cmbSucursalOrigenSales">Sucursal de origen:</label>
                          <input class="form-control" name="cmbSucursalOrigenSales" id="cmbSucursalOrigenSales" required readonly>
                        </div>
                        <div class="return-disabled">
                          <label for="cmbSucursalOrigenReturn">Sucursal de origen:</label>
                          <input class="form-control" name="cmbSucursalOrigenReturn" id="cmbSucursalOrigenReturn" required readonly>
                        </div>
                        <div class="orderPedidoGral-disabled">
                          <label for="cmbSucursalOrigenGral">Sucursal de origen:</label>
                          <input class="form-control" name="cmbSucursalOrigenGral" id="cmbSucursalOrigenGral" required readonly>
                        </div>
                      </div>
                      <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-6">
                        <div class="orderPedido-disabled">
                          <label for="cmbSucursalDestino">Sucursal de destino:</label>
                          <input class="form-control" name="cmbSucursalDestino" id="cmbSucursalDestino" required readonly>
                        </div>
                        <div class="quote-disabled">
                          <label for="cmbClienteQuote">Cliente:</label>
                          <input class="form-control" name="cmbClienteQuote" id="cmbClienteQuote" required readonly>
                        </div>
                        <div class="sales-disabled">
                          <label for="cmbClienteSales">Cliente:</label>
                          <input class="form-control" name="cmbClienteSales" id="cmbClienteSales" required readonly>
                        </div>
                        <div class="return-disabled">
                          <label for="cmbProveedorReturn">Proveedor:</label>
                          <input class="form-control" name="cmbProveedorReturn" id="cmbProveedorReturn" required readonly>
                        </div>
                        <div class="orderPedidoGral-disabled">
                          <label for="cmbDestinoGral">Destino:</label>
                          <input class="form-control" name="cmbDestinoGral" id="cmbDestinoGral" required readonly>
                        </div>
                      </div>
                      <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-6">
                        <div class="orderPedido-disabled">
                          <label for="cmbSurtidorSalida">Surtidor:</label>
                          <input class="form-control" id="cmbSurtidorSalida" required readonly>
                        </div>
                        <div class="quote-disabled">
                          <label for="cmbSurtidorSalidaQuote">Surtidor:</label>
                          <input class="form-control" id="cmbSurtidorSalidaQuote" required readonly>
                        </div>
                        <div class="sales-disabled">
                          <label for="cmbSurtidorSalidaSales">Surtidor:</label>
                          <input class="form-control" id="cmbSurtidorSalidaSales" required readonly>
                        </div>
                        <div class="return-disabled">
                          <label for="cmbSurtidorSalidaReturn">Surtidor:</label>
                          <input class="form-control" id="cmbSurtidorSalidaReturn" required readonly>
                        </div>
                        <div class="orderPedidoGral-disabled">
                          <label for="cmbSurtidorSalidaGral">Surtidor:</label>
                          <input class="form-control" id="cmbSurtidorSalidaGral" required readonly>
                        </div>
                      </div>
                      <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="orderPedido-disabled">
                          <label for="usr">No. Bultos / Paquetes / Notas:</label>
                          <input class="form-control alphaNumeric-only" type="text" name="txtNoBultosPaquetes" id="txtNoBultosPaquetes" placeholder="50 Bultos" readonly>
                        </div>
                        <div class="quote-disabled">
                          <label for="usr">No. Bultos / Paquetes / Notas:</label>
                          <input class="form-control alphaNumeric-only" type="text" name="txtNoBultosPaquetesQuote" id="txtNoBultosPaquetesQuote" placeholder="50 Bultos" readonly>
                        </div>
                        <div class="sales-disabled">
                          <label for="usr">No. Bultos / Paquetes / Notas:</label>
                          <input class="form-control alphaNumeric-only" type="text" name="txtNoBultosPaquetesSales" id="txtNoBultosPaquetesSales" placeholder="50 Bultos" readonly>
                        </div>
                        <div class="return-disabled">
                          <label for="usr">No. Bultos / Paquetes / Notas:</label>
                          <input class="form-control alphaNumeric-only" type="text" name="txtNoBultosPaquetesReturn" id="txtNoBultosPaquetesReturn" placeholder="50 Bultos" readonly>
                        </div>
                        <div class="orderPedidoGral-disabled">
                          <label for="usr">No. Bultos / Paquetes / Notas:</label>
                          <input class="form-control alphaNumeric-only" type="text" name="txtNoBultosPaquetesGral" id="txtNoBultosPaquetesGral" placeholder="50 Bultos" readonly>
                        </div>
                      </div>

                      <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 orderPedido-disabled quote-disabled sales-disabled return-disabled orderPedidoGral-disabled">
                        <table class="table opacidad" id="tblSalidaOrdenPedido" width="100%" cellspacing="0">
                          <thead>
                            <tr>
                              <th>ID</th>
                              <th>Clave/Producto</th>
                              <th>Descripción</th>
                              <th>Cantidad salida</th>
                              <th>Cantidad surtida</th>
                              <th>Cantidad faltante</th>
                              <th>Existencias</th>
                              <th>Lote</th>
                              <th>Serie</th>
                              <th>Unidad de medida</th>
                              <th>Código de barras</th>
                              <th>Caducidad</th>
                            </tr>
                          </thead>
                        </table>
                        <table class="table opacidad">
                          <tfoot>
                            <tr>
                              <th style="text-align: right;"></th>
                              <th style="text-align: right; width:400px!important"></th>
                              <th style="width:60px;"></th>
                            </tr>
                            <tr>
                              <th style="text-align: right;">Total:</th>
                              <th id="impuestos"><span id="Total">0</span></th>
                              <th></th>
                            </tr>
                            <tr class="total redondearAbajoIzq">
                              <th style="text-align: right;" class="redondearAbajoIzq"></th>
                              <th style="text-align: right;"></th>
                              <th></th>
                            </tr>
                          </tfoot>
                        </table>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
            </div>
            <div class="card-footer" style="background:transparent">

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
<script>
  var folio = '<?php echo $folio; ?>';
  var tipoS = '<?php echo $GLOBALS["TipoPedidoSalida"]; ?>';
</script>

</html>