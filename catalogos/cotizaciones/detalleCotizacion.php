<?php
session_start();

$jwt_ruta = "../../";
require_once '../jwt.php';
require_once 'functions/contarNdecimales.php';

require_once '../jwt.php';

if (isset($_SESSION["Usuario"])) {
  require_once '../../include/db-conn.php';
  $empresa_id = -1;
  if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare('SELECT c.PKCotizacion, 
                                   c.id_cotizacion_empresa,
                                   c.ImporteTotal, c.Subtotal, 
                                   DATE_FORMAT(c.FechaIngreso, "%d/%m/%Y %H:%i:%s") as FechaIngreso, 
                                   DATE_FORMAT(c.FechaVencimiento, "%d/%m/%Y") as FechaVencimiento, 
                                   c.NotaCliente,c.NotaInterna, 
                                   cl.razon_social as NombreComercial, 
                                   c.FKCliente,
                                   cl.Email, 
                                   u.nombre, 
                                   c.condicion_Pago as condicionPago,
                                   c.estatus_cotizacion_id as Estatus, 
                                   c.estatus_factura_id as estatus_factura, 
                                   u.usuario as EmailEnvio, 
                                   c.FKUsuarioCreacion, 
                                   c.empresa_id, 
                                   s.activar_inventario, 
                                   c.facturacion_directa, 
                                   CONCAT(e.Nombres," ",e.PrimerApellido," ", e.SegundoApellido) as nombre_vendedor, 
                                   s.sucursal, c.modificar, ops.id_orden_pedido_empresa, ops.id as id_pedido,
                                   decl.PKDireccionEnvioCliente,
                                   decl.Calle as CalleE,
                                   decl.Numero_exterior as NumExtE,
                                   decl.Numero_Interior as NumIntE,
                                   decl.Colonia as ColoniaE,
                                   decl.Contacto as ContactoE,
                                   decl.Telefono as TelefonoE,
                                   decl.Municipio as MunicipioE,
                                   efE.Estado as EstadoE,
                                   psE.Pais as PaisE,
                                   ifnull(decl.PKDireccionEnvioCliente,0) as isNulo,
                                   IFNULL(md.CLAVE,0) as moneda,
                                    md.PKMoneda
                              FROM cotizacion as c
                                  LEFT JOIN clientes as cl ON cl.PKCliente = c.FKCliente
                                  LEFT JOIN usuarios AS u ON c.FKUsuarioCreacion = u.id
                                  LEFT JOIN sucursales as s ON s.id = c.FKSucursal
                                  LEFT JOIN empleados as e ON e.PKEmpleado = c.empleado_id
                                  LEFT JOIN orden_pedido_por_sucursales as ops ON ops.numero_cotizacion = c.PKCotizacion
                                  LEFT JOIN direcciones_envio_cliente decl on c.direccion_entrega_id = decl.PKDireccionEnvioCliente
                                  LEFT JOIN paises psE on decl.Pais = psE.PKPais
                                  LEFT JOIN estados_federativos efE on decl.Estado = efE.PKEstado
                                  left join monedas md on md.PKMoneda = c.FkMoneda_id
                              WHERE c.PKCotizacion = :id');
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch();
    $referencia = $row['PKCotizacion'];
    $Subtotal = $row['Subtotal'];
    $ImporteTotal = "$ " . number_format($row['ImporteTotal'], 2);
    $FechaIngreso = $row['FechaIngreso'];
    $FechaVencimiento = $row['FechaVencimiento'];

    $More1month = date('Y-m-d', strtotime(' + 15 days'));

    $NotaCliente = $row['NotaCliente'];
    $NotaInterna = $row['NotaInterna'];
    $NombreComercial = $row['NombreComercial'];
    $FKCliente = $row['FKCliente'];
    $Email = $row['Email'];
    $EmailEnvio = $row['EmailEnvio'];
    $idVendedor = $row['FKUsuarioCreacion'];
    $idCotizacionEmpresa = $row['id_cotizacion_empresa'];
    $idOrdenPedidoEmpresa = $row['id_orden_pedido_empresa'];
    $pkOrdenPedidoEmpresa = $row['id_pedido'];
    $empresa_id = $row['empresa_id'];
    $activar_inventario = $row['activar_inventario'];
    $facturacion_directa = $row['facturacion_directa'];
    $sucursal = $row['sucursal'];
    $validar_modificar = $row['modificar'];
    $no_pedido = $row['id_orden_pedido_empresa'];
    $id_pedido = $row['id_pedido'];
    $estatus_factura = $row['estatus_factura'];
    $EstatusCotizacion = $row['Estatus'];
    $Moneda = $row['moneda'] == "0" ? "No seleccionada" : $row['moneda'];

    $condicionPago;
    if($row['condicionPago'] == '1'){
      $condicionPago='Contado';
    }else if ($row['condicionPago'] == '2'){
      $condicionPago='Crédito';
    }else{
      $condicionPago='Sin especificar';
    }

    $Vendedor = $row['nombre_vendedor'];
    if ($row['PKDireccionEnvioCliente'] != 1){
      if ($row['isNulo'] == '0'){
        $GLOBALS["DireccionE"] = 'S/N';
      }else{
        if($row['ContactoE'] == null || $row['ContactoE'] == ''){
          $row['ContactoE']= "Desconocido";
        }
        $GLOBALS["DireccionE"] = $row['CalleE'].' '.$row['NumExtE'].' Int.'.$row['NumIntE'].', '.$row['ColoniaE'].', '.$row['MunicipioE'].', '.$row['EstadoE'].', '.$row['PaisE'].', Atención: '.$row['ContactoE'].' '.$row['TelefonoE'];
      }
    }else{
      $GLOBALS["DireccionE"] = 'Pendiente de confirmar';
    }
  }
} else {
  header("location:../dashboard.php");
}

if ($empresa_id != $_SESSION['IDEmpresa']) {
  header("location:./");
}

$token = $_SESSION['token_ld10d'];


$stmt2 = $conn->prepare("SELECT  c.Email, u.usuario as email, dcc.Email as contacto  FROM cotizacion cz 
inner join clientes c on cz.FKCliente=c.PKCliente 
inner join usuarios u on cz.FKUsuarioCreacion=u.id 
LEFT JOIN dato_contacto_cliente dcc on cz.FKCliente = dcc.FKCliente
WHERE PKCotizacion = :id");
$stmt2->bindValue(':id', $id, PDO::PARAM_INT);
$stmt2->execute();
$row2 = $stmt2->fetchAll();
$Emails=[];
$EmailEnvio;
$EmailIn = false;
foreach($row2 as $r){
  if(isset($r['Email']) && !$EmailIn){
    //$Email = $row2['Email'];
    array_push($Emails,$r['Email'] );
    $EmailIn = true;
  }
  if(isset($r['email'])){
    $EmailEnvio = $r['email'];
    
  }
  if(isset($r['contacto'])){
    //$Email = $row2['contacto'];
    array_push($Emails,$r['contacto'] );
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="icon" type="image/png" href="../../img/header/bluTimlid.png">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <style>
    .textBlue{
      color: var(--azul-mas-oscuro);
    }

    .textData{
      font-size:large;
    }    

    table.dataTable.no-footer {
      border-bottom: 0 !important;
    }
  </style>
  <title>Timlid | Ver cotización</title>

  <!-- ESTILOS -->
  <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../../css/bootstrap-clockpicker.min.css" rel="stylesheet" type="text/css">
  <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="../../vendor/datatables/responsive.dataTables.css" rel="stylesheet">
  <link href="../../vendor/datatables/buttons.dataTables.css" rel="stylesheet">
  <link href="../../css/stylesNewTable.css" rel="stylesheet">
  <link href="../../css/lobibox.min.css" rel="stylesheet">
  <link href="../../css/notificaciones.css" rel="stylesheet">
  <link href="../../css/slimselect.min.css" rel="stylesheet">
  <link href="../../css/styles.css" rel="stylesheet">

  <!-- JS -->
  <script src="../../vendor/jquery/jquery.min.js"></script>
  <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../js/validaciones.js"></script>
  <script src="../../js/sb-admin-2.min.js"></script>
  <script src="../../js/scripts.js"></script>
  <script src="../../js/bootstrap-clockpicker.min.js"></script>
  <script src="../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../vendor/datatables/dataTables.buttons.js"></script>
<!--   <script src="../../vendor/datatables/buttons.html5.min.js"></script>
 -->  <script src="../../vendor/jszip/jszip.min.js"></script>
  <script src="../../js/jquery.redirect.min.js"></script>
  <script src="../../js/lobibox.min.js"></script>
  <script src="../../js/sweet/sweetalert2.js"></script>
  <script src="../../js/notificaciones_timlid.js" charset="utf-8"></script>
  <script src="../../js/slimselect.min.js"></script>
  <script>
    $(document).ready(function() {
      var id = <?= $id; ?>;
      var idioma_espanol = {
        "sProcessing": "Procesando...",
        "sLengthMenu": "Mostrar _MENU_ registros",
        "sZeroRecords": "No se encontraron resultados",
        "sEmptyTable": "Ningún dato disponible en esta tabla",
        "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
        "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
        "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
        "sInfoPostFix": "",
        "sSearch": "Buscar:",
        "sUrl": "",
        "sInfoThousands": ",",
        "sLoadingRecords": "Cargando...",
        "oPaginate": {
          "sFirst": "Primero",
          "sLast": "Último",
          "sNext": "Siguiente",
          "sPrevious": "Anterior"
        },
        "oAria": {
          "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
          "sSortDescending": ": Activar para ordenar la columna de manera descendente"
        }
      }
      $("#cotizacion").dataTable({
          info: false,
          scrollX: true,
          bSort: false,
          pageLength: 15,
          responsive: true,
          lengthChange: false,
          dom: `<"container-fluid"<"row mb-3"<"col-sm-12 col-md-9 p-0 d-flex align-items-center"B><"col-sm-12 col-md-3 p-0"f>>>rti
          <"container-fluid mt-4"<"row"<"#btn-filters.col-sm-12 col-md-10 p-0"><"col-sm-12 col-md-2 p-0"p>>>`,
          "columns": [{
              "data": "Clave"
            },
            {
              "data": "Cantidad"
            },
            {
              "data": "Unidad"
            },
            {
              "data": "Precio"
            },
            {
              "data": "Impuestos"
            },
            {
              "data": "Importe"
            },
            {
              "data": "Existencias"
            },
            {
              "data": "acciones"
            }
          ],
          "language": idioma_espanol,
          responsive: true
        }

      )
    });
  </script>
  <link href="../../css/timeline.css" rel="stylesheet">
</head>

<body id="page-top" class="sidebar-toggled">
  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <?php
    $icono = 'ICONO-COTIZACIONES-AZUL.svg';
    $titulo = 'Cotización';
    $ruta = "../";
    $ruteEdit = "$ruta.central_notificaciones/";
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
        $rutatb = "../";
        $backIcon = true;
        require_once $rutatb . 'topbar.php'; ?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="row">

            <div class="col-lg-12">

              <!-- Basic Card Example -->
              <div class="card shadow mb-4">
                <div class="card-body">
                  <div class="d-flex flex-wrap">
                    <input type="hidden" name="csr_token_7ALF1" id="csr_token_7ALF1" value="<?= $token ?>">
                    <?php
                    $mostrarEstado = "";
                    if ($activar_inventario == 1) {
                      if ($facturacion_directa == 1) {
                        $mostrarEstado = '';
                      } else {
                        if ($EstatusCotizacion == 1) {
                          $mostrarEstado = 'd-none';
                        } else {
                          $mostrarEstado = '';
                        }
                      }
                    }
                    ?>
                    <div id="actualizarFactura" class="<?= $mostrarEstado ?>">
                      <?php
                      if ($EstatusCotizacion == 5) { ?>
                        <span id="aceptarActualizar"><button type="button" class="btn-table-custom btn-table-custom--green" name="btnAgregarProducto" onclick="aceptarCotizacion('<?= $id ?>');" id="aceptar"><img style="width:1.5rem; vertical-align: top;" src="../../img/cotizaciones_nuevos_iconos/ICONO-ACEPTAR VERDE NVO-01.svg"></img>Aceptar</button></span>
                      <?php
                      }
                      if ($EstatusCotizacion == 4) { ?>
                        <span id="aceptarActualizar">
                          <span class="btn-table-custom btn-table-custom--yellow">
                          Cotización vencida
                          </span>
                        </span>
                      <?php
                      }
                      if ($EstatusCotizacion == 3) { ?>
                        <span id="aceptarActualizar">
                          <span class="btn-table-custom btn-table-custom--red">
                          Cotización cancelada
                          </span>
                        </span>
                      <?php
                      }
                      if ($EstatusCotizacion == 1) {
                        if ($activar_inventario == 1) {
                          if ($facturacion_directa == 1) {
                            echo '<span id="aceptarActualizar"> 
                                     <button type="button" class="btn-table-custom btn-table-custom--blue" name="aceptarActualizar" onclick="facturarCotizacion(' . $id . ');"><img style="width:1.5rem; vertical-align: top;" src="../../img/cotizaciones_nuevos_iconos/ICONO-FACTURACION AZUL NVO-01.svg">Facturar</button>
                                  </span>';
                          }
                        } else {
                          echo '<span id="aceptarActualizar"> 
                                     <button type="button" class="btn-table-custom btn-table-custom--blue" name="aceptarActualizar" onclick="facturarCotizacion(' . $id . ');"><img style="width:1.5rem; vertical-align: top;" src="../../img/cotizaciones_nuevos_iconos/ICONO-FACTURACION AZUL NVO-01.svg">Facturar</button>
                                  </span>';
                        }
                      }
                      ?>
                    </div>

                    <div>
                      <span data-toggle="modal" data-target="#datos_envio" class="btn-table-custom btn-table-custom--blue-lightest"><img style="width:1.5rem; vertical-align: top;" src="../../img/cotizaciones_nuevos_iconos/ICONO-ENVIAR AZUL CLARO NVO-01.svg"></img> Enviar</span>
                    </div>
                    <div>
                    <?php
                      if ($EstatusCotizacion == 7) { ?>
                        <span class="btn-table-custom btn-table-custom--dark" onclick="editarCotizacion(<?= $id ?>);"><img style="width:1.5rem; vertical-align: top;" src="../../img/cotizaciones_nuevos_iconos/ICONO-EDITAR VERDE OSCURO NVO-01.svg"></img>Editar</span>
                    <?php
                      } else {
                    ?>
                        <span class="btn-table-custom btn-table-custom--blue-light" onclick="editarCotizacion(<?= $id ?>);"><img style="width:1.5rem; vertical-align: top;" src="../../img/cotizaciones_nuevos_iconos/ICONO-EDITAR VERDE OSCURO NVO-01.svg"></img>Editar</span>
                    <?php
                      }
                    ?>
                    </div>
                    <div id="actualizarVisualizacion">
                      <span class="btn-table-custom btn-table-custom--dark" name="btnChat" onclick="mostrarChat()" id="ver-chat"><img style="width:1.5rem; vertical-align: top;" src="../../img/cotizaciones_nuevos_iconos/ICONO-CHAT COLOR OSCURO NVO-01.svg"></img>Chat</span>
                      <span class="btn-table-custom btn-table-custom--yellow d-none" name="btnChat" onclick="mostrarCotizacion()" id="ver-cotizacion"><i class="fas fa-receipt"></i> Cotización</span>
                    </div>
                    <div>
                      <span class="btn-table-custom btn-table-custom--orange" name="btnChat" onclick="mostrarBitacora()"><img style="width:1.5rem; vertical-align: top;" src="../../img/cotizaciones_nuevos_iconos/ICONO-BITACORA NARANJA CLARO NVO-01.svg"></img>Bitacora</span>
                    </div>
                    <div>
                      <span data-toggle="modal" class="btn-table-custom btn-table-custom--turquoise" onclick="descargarCotizacion(<?= $id ?>);"><img style="width:1.5rem; vertical-align: top;" src="../../img/cotizaciones_nuevos_iconos/ICONO-DESCARGAR AZUL OSCURO NVO-01.svg"></img> Descargar</span>
                    </div>
                    <div>
                    <?php
                      if ($EstatusCotizacion == 7) { ?>
                        <span data-toggle="modal" class="btn-table-custom btn-table-custom--dark" onclick="cancelarCotizacionF(<?= $id ?>);"><img style="width:1.5rem; vertical-align: top;" src="../../img/cotizaciones_nuevos_iconos/ICONO-CANCELAR ROJO NVO-01.svg"></img>Cancelar</span>
                    <?php
                      } else {
                    ?>
                        <span data-toggle="modal" class="btn-table-custom btn-table-custom--red" onclick="cancelarCotizacionF(<?= $id ?>);"><img style="width:1.5rem; vertical-align: top;" src="../../img/cotizaciones_nuevos_iconos/ICONO-CANCELAR ROJO NVO-01.svg"></img>Cancelar</span>
                    <?php
                      }
                    ?>
                    </div>
                    <div>
                      <?php
                        if($estatus_factura == 1 || $estatus_factura==2){
                      ?>
                        <span data-toggle="modal" data-target="#copyVenta" class="btn-table-custom btn-table-custom--blue"><img style="width:1.5rem; vertical-align: top;" src="../../img/cotizaciones_nuevos_iconos/ICONO-COPIAR-AZUL-NVO.svg"></img>Copiar cotización</span>
                      <?php
                        }
                      ?>
                    </div>
                    <div>
                        <b class="btn-table-custom--turquoise">Estatus facturación:</b> 
                        <?php
                          switch ($estatus_factura) {
                            case 1:
                                echo '<span class="btn-table-custom--turquoise">Facturado completo</span>';
                                break;
                            case 2:
                                echo '<span class="btn-table-custom--turquoise">Facturado directo</span>';
                                break;
                            case 3:
                                echo '<span class="btn-table-custom--yellow">Pendiente de facturar</span>';
                                break;
                            case 4:
                                echo '<span class="btn-table-custom--yellow">Pendiente de facturar directo</span>';
                                break;
                            case 5:
                                echo '<span class="btn-table-custom--green">Parcialmente facturado almacén</span>';
                                break;
                            case 6:
                                echo '<span class="btn-table-custom--red">Cancelado</span>';
                                break;
                        }
                          
                        ?>
                    </div>
                  </div>
                  <br>
                  <div class="row my-3">
                    <div class="col-lg-3 textData">
                      <p><b class="textBlue">Referencia:</b> <?php echo sprintf("%011d", $idCotizacionEmpresa); ?></p>
                      <p><b class="textBlue">Sucursal:</b> <?= $sucursal; ?></p>
                      <p><b class="textBlue">Condición de pago:</b> <?= $condicionPago; ?></p>
                      <p><b class="textBlue">Moneda:</b> <?= $Moneda; ?></p>
                    </div>
                    <div class="col-lg-3 textData">
                      <p><b class="textBlue">Cliente:</b> <a style="cursor:pointer" href="../clientes/catalogos/clientes/detalles_cliente.php?c=<?= $FKCliente?>"><?= $NombreComercial; ?> </a></p>
                      <p><b class="textBlue">Domicilio de entrega:</b> <?= $GLOBALS["DireccionE"]; ?> </p>
                      <p><b class="textBlue">Vendedor:</b> <?= $Vendedor; ?></p>                      
                    </div>
                    <div class="col-lg-3 textData">
                      <b class="textBlue" for="fi">Fecha de ingreso:</b> 
                      <div id="fi"><?= $FechaIngreso; ?></div><p></p>
                      <b class="textBlue" for="fv">Fecha de vencimiento:</b> 
                      <?php
                      if($EstatusCotizacion == 4){
                        $FechaVencimientoOld = $FechaVencimiento. ' <a href="#update_vencimiento" data-toggle="modal" data-target="#update_vencimiento" > Actualizar fecha </a>';
                        echo('<div id="fv">'.$FechaVencimientoOld.'</div>');
                      }else{
                        echo('<div id="fv">'.$FechaVencimiento.'</div>');
                      }
                      ?><p></p>
                      <?php
                        if($idOrdenPedidoEmpresa != null || $idOrdenPedidoEmpresa != ''){
                          echo('<b class="textBlue">Pedido: </b><a href="../pedidos/detallePedido.php?id='.$pkOrdenPedidoEmpresa.'">'.sprintf("%011d", $idOrdenPedidoEmpresa).'</a>');
                        }
                      ?>
                    </div>
                    <div class="col-lg-3 textData">
                      <h2><b class="textBlue" fro="totalText">Importe Total:</b> <div id="totalText"><b><?= $ImporteTotal;?></b></div></h2>
                      <?php
                      if ($EstatusCotizacion == 1) { ?>
                        <div class="form-check form-switch mt-3">
                          <input class="form-check-input" type="checkbox" id="cbxMarcarVenta" name="cbxMarcarVenta">
                          <label class="form-check-label" for="cbxMarcarVenta"><b><h5>Marcar como venta</h5></b></label>
                        </div>
                      <?php
                      }?>
                    </div>
                  </div>
                </div>

                <div class="card-body" id="mostrarCotizacion">
                  <div class="row">
                    <div class="col-lg-12">
                      <div class="table-responsive redondear">
                        <table class="table table-sm tblCoti" id="cotizacion">
                          <thead class="text-center">
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
                            <?php
                            $stmt = $conn->prepare('SELECT dc.FKProducto, 
                                                           dc.Cantidad, 
                                                           dc.Precio, 
                                                           p.ClaveInterna, 
                                                           p.Nombre, 
                                                           csu.Descripcion,
                                                           co.FKSucursal,
                                                           if(p.FKTipoProducto = 5 ,
                                                                "N/A",
                                                                (select ifnull(sum(existencia),0) as StockExistencia
                                                                  from existencia_por_productos
                                                                  where producto_id = dc.FKProducto
                                                                    and sucursal_id = co.FKSucursal)
                                                            )as existencia
                                                    FROM detalle_cotizacion as dc 
                                                        INNER JOIN productos as p ON p.PKProducto = dc.FKProducto 
                                                        INNER JOIN cotizacion as co ON dc.FKCotizacion = co.PKCotizacion
                                                        LEFT JOIN info_fiscal_productos as ifp ON ifp.FKProducto = p.PKProducto 
                                                        LEFT JOIN claves_sat_unidades as csu ON csu.PKClaveSATUnidad = ifp.FKClaveSATUnidad 
                                                    WHERE dc.FKCotizacion = :id');
                            $stmt->execute(array(':id' => $id));
                            $numero_productos = $stmt->rowCount();
                            $rowp = $stmt->fetchAll();
                            $impuestos = array();
                            $x = 0;
                            $cantidadAdicionalIVA = 0;

                            foreach ($rowp as $rp) {

                              

                              $stmt = $conn->prepare('SELECT i.PKImpuesto, i.Nombre, i.FKTipoImpuesto as TipoImpuesto, i.FKTipoImporte as TipoImporte, i.Operacion, di.FKProducto, di.Tasa FROM detalleimpuesto as di INNER JOIN impuesto as i ON i.PKImpuesto = di.FKImpuesto WHERE di.FKCotizacion= :id AND FKProducto = :idProducto');
                              $stmt->execute(array(':id' => $id, ':idProducto' => $rp['FKProducto']));
                              $rowi = $stmt->fetchAll();
                              $totalProducto = $rp['Cantidad'] * $rp['Precio'];

                              //Cuenta los decimales de cada Importe del producto.
                              $nDecimalsT = contarnDecimales($totalProducto);

                              $nDecimalsP = contarnDecimales($rp['Precio']);

                              if ($rp['Descripcion'] == "") {
                                $ClaveUnidad = "Sin unidad";
                              } else {
                                $ClaveUnidad = $rp['Descripcion'];
                              }

                              $Existencia = $rp['existencia'];

                            ?>

                              <tr id="idProducto_<?= $rp['FKProducto'] ?>" class="text-center">
                                <td style="text-align: left;" id="nombreproducto_<?= $rp['FKProducto'] ?>">
                                  <?= $rp['ClaveInterna'] . ' - ' . $rp['Nombre'] ?>
                                </td>
                                <td id="piezas_<?= $rp['FKProducto'] ?>"><?= $rp['Cantidad'] ?></td>
                                <input type="hidden" id="piezaAnt_<?= $rp['FKProducto'] ?>" value="<?= $rp['Cantidad'] ?>" />
                                <input type="hidden" name='inp_productos[]' value="<?= $rp['FKProducto'] ?>" />
                                <td><?= $ClaveUnidad ?></td>
                                <td id="precio_<?= $rp['FKProducto'] ?>"><?= number_format($rp['Precio'], $nDecimalsP) ?></td>
                                <input type="hidden" name="inp_precio[]" value="<?= number_format($rp['Precio'], $nDecimalsP)?>" />
                                <td><span id="impuestos_<?= $rp['FKProducto'] ?>">
                                    <?php

                                    $contImpuestos = 1;
                                    $numImpuestos = count($rowi);
                                    foreach ($rowi as $ri) {
                                      $IniImpuesto = explode(" ", $ri['Nombre']);
                                      $Identificador = $IniImpuesto[0] . "_" . $ri['TipoImpuesto'] . "_" . $ri['TipoImporte'] . "_" . $ri['PKImpuesto'] . "_" . $ri['FKProducto'];

                                      if ($ri['TipoImporte'] == 1) {
                                        $tas = "%";
                                      }
                                      if ($ri['TipoImporte'] == 2 || $ri['TipoImporte'] == 3) {
                                        $tas = "";
                                      }

                                      //print_r($impuestos);

                                      $found_key = array_search($ri['PKImpuesto'], array_column($impuestos, 0));

                                      if($ri['PKImpuesto'] == 1){

                                        foreach ($rowi as $riIVA) {

                                          if($riIVA['PKImpuesto'] == 2){
                                            $cantidadAdicionalIVA = $cantidadAdicionalIVA + (($rp['Cantidad'] * $rp['Precio']) * ($riIVA['Tasa'] / 100));
                                          }
                                          if($riIVA['PKImpuesto'] == 3){
                                            $cantidadAdicionalIVA = $cantidadAdicionalIVA + ($riIVA['Tasa'] * $rp['Cantidad']);
                                          }
                                        }

                                      }

                                      if ($found_key > -1) {
                                        $impuestos[$found_key][0] = $ri['PKImpuesto'];
                                        if ($ri['TipoImporte'] == 1) {
                                          $impuestos[$found_key][1] = $impuestos[$found_key][1] + ((($rp['Cantidad'] * $rp['Precio']) + $cantidadAdicionalIVA) * ($ri['Tasa'] / 100));
                                        } 
                                        if ($ri['TipoImporte'] == 2) {
                                          $impuestos[$found_key][1] = $impuestos[$found_key][1] + ($ri['Tasa'] * $rp['Cantidad']);
                                        }
                                        if ($ri['TipoImporte'] == 3) {
                                          $impuestos[$found_key][1] = $impuestos[$found_key][1] + $ri['Tasa'];
                                        }
                                        $impuestos[$found_key][2] = $ri['Nombre'];
                                        $impuestos[$found_key][3] = $ri['TipoImpuesto'];
                                        $impuestos[$found_key][4] = $ri['Operacion'];
                                      } else {
                                        $impuestos[$x][0] = $ri['PKImpuesto'];
                                        if ($ri['TipoImporte'] == 1) {
                                          $impuestos[$x][1] = (($rp['Cantidad'] * $rp['Precio']) + $cantidadAdicionalIVA) * ($ri['Tasa'] / 100);
                                        } 
                                        if ($ri['TipoImporte'] == 2) {
                                          $impuestos[$x][1] = $ri['Tasa'] * $rp['Cantidad'];
                                        }
                                        if ($ri['TipoImporte'] == 3) {
                                          $impuestos[$x][1] = $ri['Tasa'];
                                        }
                                        $impuestos[$x][2] = $ri['Nombre'];
                                        $impuestos[$x][3] = $ri['TipoImpuesto'];
                                        $impuestos[$x][4] = $ri['Operacion'];
                                        $x++;
                                      } 

                                      $cantidadAdicionalIVA = 0;
                                      ?>
                                      <span id="<?= $Identificador ?>">
                                        <?= $ri['Nombre'] ?> <?= $ri['Tasa'] . $tas ?>
                                        <input name="valImp_<?= $ri['Tasa'] ?>" type="hidden" id="impAgregado_<?= $ri['FKProducto'] ?>_<?= $ri['PKImpuesto'] ?>" value="<?= $ri['Tasa'] ?>" />
                                        <input type="hidden" id="OperacionUnica_<?= $ri['FKProducto'] ?>_<?= $ri['PKImpuesto'] ?>" value="<?= $ri['Operacion'] ?>" />
                                      </span>
                                      <?php

                                      if ($contImpuestos != $numImpuestos) { ?>
                                        <br>
                                    <?php

                                      }
                                      $contImpuestos++;
                                    } ?>
                                  </span>
                                </td>
                                <td id="totalproducto_<?= $rp['FKProducto'] ?>"><?= number_format($totalProducto, $nDecimalsT) ?>
                                </td>
                                <input type="hidden" name="inp_total_producto[]" value="<?= number_format($totalProducto, $nDecimalsT) ?>" />
                                <td>
                                  <?= $Existencia; ?>
                                </td>
                                 <td>

                                </td>
                              </tr>
                            <?php

                            } ?>
                          </tbody>
                          <tr><td colspan="8"><br></td></tr>
                          <tr>
                            <th colspan="5">
                            </th>
                            <th style="text-align: left;">Subtotal:</th>
                            <td colspan="2" style="text-align: left;">$ <span id="Subtotal"><?= number_format($Subtotal, 2) ?></span></td>
                          </tr>
                          <tr>
                            <th colspan="5"></th>
                            <th style="text-align: left;">Impuestos:</th>
                            <td style="text-align: left;" colspan="2">
                              <?php
                              foreach ($impuestos as $imp) {
                                $IniImpuesto = explode(" ", $imp[2]); ?>
                                
                                <span><?= $imp[2] ?>: $ <?= number_format($imp[1], 2) ?></span><br>
                                
                              <?php

                              } ?>
                            </td>
                          </tr>
                          <tr class="total">
                            <th colspan="5" class="redondearAbajoIzq"></th>
                            <td style="text-align: left;"><span id="Total"><b>Total:</b></span></td>
                            <th colspan="2"><?= $ImporteTotal ?></th>
                          </tr>
                        </table>
                      </div>

                      <div class="row">
                        <div class="col-lg-12" style="color:#d9534f;display: none;text-align: center;" id="mostrarMensaje">
                          <h2>Ingresa un producto al menos.</h2>
                        </div>
                      </div>
                    </div>
                  </div>
                  <br>
                  <div class="row my-3">
                    <div class="col-lg-6 text-center">
                      <b>Nota Interna:</b> <br><?= $NotaInterna; ?>
                    </div>
                    <div class="col-lg-6 text-center">
                      <b>Nota Cliente:</b> <br><?= $NotaCliente; ?>
                    </div>
                  </div>
                </div>

                <!---SEPARACION BODY--->
                <div class="card shadow mb-4" id="mostrarChat" style="display:none;">
                  <div class="card-header">
                    <a href="#" class="btn btn-success btn-round" style="position: relative; right: 2%;float: right;margin: 5px 0;" data-toggle="modal" data-target="#agregar_Proyecto"><i class="far fa-comment-dots"></i> Agregar mensaje </a>
                  </div>
                  <div class="card-body">


                    <?php
                    $idMensajeFinal = 0;
                    $contador = 0;

                    $stmt = $conn->prepare('SELECT mc.PKMensajes_Cotizacion, mc.TipoUsuario, mc.Mensaje, DATE_FORMAT(mc.FechaAgregado, "%d/%m/%Y %H:%i:%s") as Fecha ,
                              cl.NombreComercial, u.nombre as Nombre_Empleado
                            FROM mensajes_cotizacion as mc
                              INNER JOIN cotizacion as c ON c.PKCotizacion = mc.FKCotizacion
                              INNER JOIN usuarios as u ON u.id = c.FKUsuarioCreacion
                              INNER JOIN clientes as cl ON cl.PKCliente = c.FKCliente
                          WHERE mc.FKCotizacion = :cotizacion ORDER BY  mc.PKMensajes_Cotizacion DESC');
                    $stmt->bindValue(':cotizacion', $id);
                    $stmt->execute();
                    $row = $stmt->fetchAll();
                    if (count($row) > 0) { ?>

                      <ul class="timeline" id="add-timeline">
                        <?php

                        foreach ($row as $r) {

                          if ($contador == 0) {
                            $idMensajeFinal = $r['PKMensajes_Cotizacion'];
                            $contador = 1;
                          }

                          if ($r['TipoUsuario'] == 1) {
                            $nombreMensaje = $r['NombreComercial'];
                            $clase = 'class="timeline-inverted"';
                            $color = 'warning';
                          } else {
                            $nombreMensaje = $r['Nombre_Empleado'];
                            $clase = '';
                            $color = 'info';
                          } ?>
                          <li <?= $clase ?>>
                            <div class="timeline-badge <?= $color ?>"><i class="glyphicon glyphicon-credit-card"></i></div>
                            <div class="timeline-panel">
                              <div class="timeline-heading">
                                <h4 class="timeline-title"><?= $nombreMensaje ?></h4>
                              </div>
                              <div class="timeline-body">
                                <p><?= $r['Mensaje'] ?></p>
                              </div>
                              <hr>
                              <div class="row">
                                <div class="col-md-12" align="right">
                                  <small><?= $r['Fecha'] ?></small>
                                </div>
                              </div>
                            </div>
                          </li>
                        <?php

                        } ?>
                      </ul>
                    <?php

                    } else { ?>
                      <ul id="add-timeline">
                        <h3 id="nuevo_mensaje">
                          <center>AUN NO HAY MENSAJES EN ESTA COTIZACIÓN</center>
                        </h3>
                      </ul>
                    <?php
                    } ?>
                  </div>
                </div>
                <div id="mostrarBitacora" style="display:none;">
                  <?php
                  $stmt = $conn->prepare('SELECT u.nombre,b.Fecha_Movimiento,m.Mensaje, b.aceptada_cliente
                        FROM bitacora_cotizaciones AS b
                        LEFT JOIN usuarios AS u ON b.FKUsuario = u.id
                        LEFT JOIN mensajes_acciones AS m ON b.FKMensaje = m.PKMensajesAcciones
                        WHERE b.FKCotizacion = :id');
                  $stmt->bindValue(':id', $id);
                  $stmt->execute();
                  while ($row = $stmt->fetch()) {
                    $fecha = new DateTime($row['Fecha_Movimiento']);
                    if ($row['aceptada_cliente'] == 0) {
                      $usuario = $row['nombre'];
                    } else {
                      $usuario = "el cliente";
                    }
                    $alerta = $fecha->format('d/m/Y H:i:s') . ": " . $row['Mensaje'] . " por " . $usuario;
                  ?>
                    <!-- bitacora de movimientos en compras-->
                    <div class="row">
                      <div class="alert alert-secondary col-lg-6 text-center text-primary" style="font-weight: bold;margin-left:25%" role="alert">
                        <?= $alerta; ?>
                      </div>
                    </div>
                  <?php } ?>
                </div>


              </div>
            </div>
          </div>
          <!-- End of Main Content -->

        </div>
        <!-- End of Content Wrapper -->

      </div>
      <!-- End of Page Wrapper -->

      <!-- Footer -->
      <?php
      $rutaf = "../";
      require_once '../footer.php';
      ?>
      <!-- End of Footer -->

      <!-- Scroll to Top Button-->
      <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
      </a>

      <!-- Modal Datos envio -->
      <div id="datos_envio" class="modal fade">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <form action="#" method="POST">
              <input type="hidden" name="txtId" id="txtId" value="<?= $id; ?>">
              <div class="modal-header">
                <h4 class="modal-title">Datos de envio</h4>
                <button type="button" class="close text-light" data-dismiss="modal" aria-hidden="true">x</button>
              </div>
              <div class="modal-body">
                <div class="row">
                  <div class="form-group col-lg-12">
                    <label for="">De: </label>
                    <input class="form-control" type="email" name="txtOrigen" id="txtOrigen" value="<?= $EmailEnvio; ?>" required>
                  </div>
                </div>
                <div class="row">
                  <div class="form-group col-lg-12">
                    <label for="txtEmail">Para: </label>
                    <select name="txtDestino" id="txtDestino" required multiple>
                      <option data-placeholder="true"></option>
                      <?php
                    foreach($Emails as $e){
                      //echo ('<input type="text" id="notifi" value="' . $e . '">');
                      echo('<option value='. $e .'>'. $e .'</option>');
                    }
                  ?>
                    </select>
                    <div class="invalid-feedback" id="invalid-emailDestino">Ingresa el correo electrónico de destino.</div>
                  </div>
                </div>
                <div class="row">
                  <div class="form-group col-lg-12">
                    <label for="">Asunto: </label>
                    <input class="form-control" type="text" name="txtAsunto" id="txtAsunto" value="Nueva Cotización" required>
                  </div>
                </div>
                <div class="row">
                  <div class="form-group col-lg-12">
                    <label for="">Mensaje: </label>
                    <textarea class="form-control" name="txaMensaje" id="txaMensaje" rows="5" cols="80"></textarea>
                  </div>
                </div>

              </div>
              <div align="center">
                <img src="../../img/chat/loading.gif" id="loading" width="30px" style="position: absolute; bottom: 73px;left: 50%;text-align: center;display: none;">
              </div>
              <div class="modal-footer">
                <button type="button" class="btn-custom btn-custom--border-blue" data-dismiss="modal" name="button" id="cancelarCotizacion"><i class="fas fa-times"></i> Cancelar</button>
                <button class="btn-custom btn-custom--blue" type="button" name="button" id="enviarCotizacion"><i class="fas fa-envelope"></i> Enviar</button>
              </div>
            </form>
          </div>
        </div>
      </div>


      <!-- Modal Actualizar fecha de vencimiento -->
      <div id="update_vencimiento" class="modal fade">
        <div class="modal-dialog modal-md">
          <div class="modal-content">
            <form action="#" method="POST">
              <input type="hidden" name="txtId" id="txtId" value="<?= $id; ?>">
              <div class="modal-header">
                <h4 class="modal-title">Actualizar fecha de vencimiento</h4>
                <button type="button" class="close text-light" data-dismiss="modal" aria-hidden="true">x</button>
              </div>
              <div class="modal-body">
                <div class="row">
                  <div class="form-group col-lg-12">
                    <label for="">Fecha de vencimiento anterior : </label>
                    <!-- Se le da el formato Y-m-d  a la fecha-->
                    <input type="date" class="form-control" name="txtFechaGeneracion" id="txtFechaGeneracion" value="<?= date('Y-m-d', strtotime(str_replace('/', '-', $FechaVencimiento)  )) ; ?>" readonly="" required="">
                  </div>
                </div>
                <div class="row">
                  <div class="form-group col-lg-12">
                    <label for="txtEmail">Nueva fecha de vencimeinto: </label>
                    <input type="date" class="form-control" name="txtFechaVencimiento" id="txtFechaVencimiento" value="<?= $More1month ; ?>" required="" min="<?= date("Y-m-d"); ?>">
                  </div>
                </div>
              </div>
              <div align="center">
                <img src="../../img/chat/loading.gif" id="loading" width="30px" style="position: absolute; bottom: 73px;left: 50%;text-align: center;display: none;">
              </div>
              <div class="modal-footer">
                <button type="button" class="btn-custom btn-custom--border-blue" data-dismiss="modal" name="button" id="cancelarCotizacion"><i class="fas fa-times"></i> Cancelar</button>
                <button class="btn-custom btn-custom--blue" type="button" value="<?= $id ?>" name="button" id="UpdateVencimiento"><i class="fas fa-envelope"></i> Actualizar</button>
              </div>
            </form>
          </div>
        </div>
      </div>


      <!-- Agregar mensaje-->
      <div id="agregar_Proyecto" class="modal fade" style="z-index: 100000000">
        <div class="modal-dialog">
          <div class="modal-content">
            <form action="" method="POST" id="frProyecto">
              <div class="modal-header">
                <h4 class="modal-title">Chat de cotizaciones</h4>
                <button type="button" class="close text-light" data-dismiss="modal" aria-hidden="true">x</button>
              </div>
              <br>
              <div class="row">
                <div class="col-lg-8" style="text-align: center;position:relative;left: 16%;">
                  <label for="usr">Mensaje:</label>
                  <textarea id="txtMensaje" rows="4" cols="50" class="form-control" maxlength="150" required></textarea>
                </div>
              </div>
              <br>
              <div class="modal-footer">
                <input type="button" class="btn-custom btn-custom--border-blue" data-dismiss="modal" value="Cancelar" id="cancelarMensaje">
                <input type="button" class="btn-custom btn-custom--blue" id="btnGuardar" value="Agregar">
              </div>
            </form>
          </div>
        </div>
      </div>

      <div class="modal fade" id="copyVenta" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <form action="" method="POST">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">¿Desea copiar los datos de esta cotización para crear una nueva?</h5>
                <button class="close text-light" type="button" data-dismiss="modal" aria-label="Close">
                  x
                </button>
              </div>
              <div class="modal-footer d-flex justify-content-end">
                <button class="btn-custom btn-custom--border-blue btnCancelarActualizacion" type="button" data-dismiss="modal"><span class="ajusteProyecto">Cancelar</span></button>
                <button type="submit" onclick="duplicarCotizacion(<?= $referencia?>, <?= $estatus_factura?>);" class="btn-custom btn-custom--blue"><span class="ajusteProyecto" data-dismiss="modal">Copiar</span></button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

<script>
  emailRegex = /^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i;
  var destinatarios = new SlimSelect({
    select: '#txtDestino',
    placeholder: 'Seleccione el/los destinatarios',
    addable: function (value) {
      if (!emailRegex.test(value)) {return false}
      return {
        text: value,
        value: value.toLowerCase()
      }
    }
  }); 
  let activar_inventario = <?php echo $activar_inventario; ?>;
  let facturacion_directa = <?php echo $facturacion_directa; ?>;
  let validar_modificar = <?php echo $validar_modificar; ?>;

  $("#invalid-emailDestino").css("display", "none");

  function isEmail(email) {
    var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(email);
  }

  var contadorEnviar = 0;

  function aceptarCotizacion(idCotizacion) {


    var FKUsuario = <?= $_SESSION['PKUsuario'] ?>;
    let token = $("#csr_token_7ALF1").val();

    const swalWithBootstrapButtons = Swal.mixin({
      customClass: {
        actions: "d-flex justify-content-around",
        confirmButton: "btn-custom btn-custom--border-blue",
        cancelButton: "btn-custom btn-custom--blue",
      },
      buttonsStyling: false,
    });

    swalWithBootstrapButtons
      .fire({
        title: "¿Desea continuar?",
        text: "Se aceptará la cotización.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: '<span class="verticalCenter">Aceptar cotización</span>',
        cancelButtonText: '<span class="verticalCenter">Cancelar</span>',
        reverseButtons: false,
      })
      .then((result) => {
        if (result.isConfirmed) {

          $.ajax({
            type: 'POST',
            url: 'functions/aceptarCotizacion.php',
            data: {
              idCotizacion: idCotizacion,
              FKUsuario: FKUsuario,
              csr_token_7ALF1: token
            },
            success: function(data) {

              if (data == "exito") {

                Lobibox.notify("success", {
                  size: 'mini',
                  rounded: true,
                  delay: 3000,
                  delayIndicator: false,
                  position: 'center top', //or 'center bottom'
                  icon: false,
                  img: '../../img/timdesk/checkmark.svg',
                  msg: "¡Se ha aceptado la cotización!"
                });

                // setTimeout(function() {
                //     $(location).attr('href', './');
                // }, 2000);
                  $("#aceptarActualizar").html(
                    '<button type="button" class="btn-table-custom btn-table-custom--blue" name="actualizarFactura" onclick="facturarCotizacion(' + idCotizacion + ');"><img style="width:1.5rem; vertical-align: top;" src="../../img/cotizaciones_nuevos_iconos/ICONO-FACTURACION AZUL NVO-01.svg">Facturar</button>');

              } else {

                Lobibox.notify("error", {
                  size: 'mini',
                  rounded: true,
                  delay: 3000,
                  delayIndicator: false,
                  position: 'center top', //or 'center bottom'
                  icon: false,
                  img: '../../img/timdesk/warning_circle.svg',
                  msg: "Ocurrio un error, vuelva intentarlo."
                });

              }
            }
          });



        } else if (
          /* Read more about handling dismissals below */
          result.dismiss === Swal.DismissReason.cancel
        ) {}
      });


  }


  $("#enviarCotizacion").click(function() {
    var id = $("#txtId").val();
    var emailOrigen = $("#txtOrigen").val();
    var emailDestino = destinatarios.selected();
    console.log(emailDestino.length);
    var asunto = $("#txtAsunto").val();
    var mensaje = $("#txaMensaje").val();
    let token = $("#csr_token_7ALF1").val();


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

    $("#enviarCotizacion").attr("disabled", true);
    $("#cancelarCotizacion").attr("disabled", true);
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
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: false,
              img: '../../img/timdesk/checkmark.svg',
              msg: "Se envio la cotización al correo."
            });

            $("#txaMensaje").val("");
            $("#datos_envio").modal('toggle');

          } else {
            Lobibox.notify("error", {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: false,
              img: '../../img/timdesk/warning_circle.svg',
              msg: "Ocurrio un error al enviar, vuelva intentarlo."
            });
          }

          $("#enviarCotizacion").attr("disabled", false);
          $("#cancelarCotizacion").attr("disabled", false);
          $("#loading").css("display", "none");
        }
      });
    });


  });

  $("#txtDestino").change(function(){
    $("#invalid-emailDestino").css("display", "none");
  });

  $("#UpdateVencimiento").click(function (e) { 
    e.preventDefault();
    var idCotizacion = this.value;
    var newfecha = $("#txtFechaVencimiento").val();
    $.ajax({
      type: 'POST',
      //
      url: 'functions/UpdateVencimiento.php',
      data: {
        idCotizacion: idCotizacion,
        fecha : newfecha
      },
      dataType: 'json',
      success: function(data) {

        console.log(data);
        console.info(data);
        
        if (data[0].estatus_cotizacion_id == 5 && data[0].estatus_factura_id == 3) {
          window.location.reload();
        } else {
          Lobibox.notify("error", {
            size: 'mini',
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: 'center top', //or 'center bottom'
            icon: false,
            img: '../../img/timdesk/warning_circle.svg',
            msg: "Fallo la actualización"
          });
        }
      },
  error: function (error) {
    console.log(error);
  },
    });
  });



  function editarCotizacion(idCotizacion) {

    $.ajax({
      type: 'POST',
      url: 'functions/verificarEstadoCotizacion.php',
      data: {
        idCotizacion: idCotizacion
      },
      success: function(data) {
        if (data == 5) {
          $().redirect('editarCotizacion.php', {
            'idCotizacionU': idCotizacion
          });

        } else {
          Lobibox.notify("error", {
            size: 'mini',
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: 'center top', //or 'center bottom'
            icon: false,
            img: '../../img/timdesk/warning_circle.svg',
            msg: "No se pueden modificar cotizaciones aceptadas, facturadas, vencidas o vendidas."
          });
        }

      }
    });


  }

  function descargarCotizacion(idCotizacion) {
    let token = $("#csr_token_7ALF1").val();

    $().redirect('functions/descargar_Cotizacion.php', {
      'idCotizacion': idCotizacion,
      'csr_token_7ALF1': token
    });
  }

  function facturarCotizacion(idCotizacion) {

    $().redirect('../facturacion/agregar_facturacion.php', {
      'idCotizacionF': idCotizacion
    });
  }

  function duplicarCotizacion(idCotizacion, estatus_factura) {
    if(estatus_factura == 1 || estatus_factura == 2){
      $().redirect("agregarCotizaciones.php", {
        idCotizacionU: idCotizacion,
      });
    }
  }

  function mostrarChat() {
    $("#mostrarChat").show();
    $("#mostrarBitacora").hide();
    $("#mostrarCotizacion").hide();
    $("#ver-cotizacion").removeClass("d-none");
    $("#ver-chat").addClass("d-none");

    /* $("#actualizarVisualizacion").html('<button type="button" class="btn-custom btn-custom--yellow" name="btnChat" onclick="mostrarCotizacion()"><i class="fas fa-receipt"></i> Cotización</button>'); */
  }

  function mostrarCotizacion() {
    $("#mostrarCotizacion").show();
    $("#mostrarChat").hide();
    $("#mostrarBitacora").hide();
    $("#ver-cotizacion").addClass("d-none");
    $("#ver-chat").removeClass("d-none");
    /* $("#actualizarVisualizacion").html('<button type="button" class="btn-custom btn-custom--dark" name="btnChat" onclick="mostrarChat()"><i class="far fa-comments"></i> Chat</button>'); */
  }

  function mostrarBitacora() {
    $("#mostrarBitacora").show();
    $("#mostrarChat").hide();
    $("#mostrarCotizacion").hide();
  }

  $("#btnGuardar").click(function() {

    var mensaje = $('#txtMensaje').val().trim();
    var cotizacion = <?= $id ?>;
    let token = $("#csr_token_7ALF1").val();

    if (mensaje === '') {
      $("#txtMensaje")[0].reportValidity();
      $("#txtMensaje")[0].setCustomValidity('Completa este campo.');
      return;
    }

    $("#btnGuardar").attr("disabled", true);
    $("#cancelarMensaje").attr("disabled", true);

    <?php date_default_timezone_set('America/Mexico_City'); ?>
    var fecha = "<?php echo date('d/m/Y H:i:s', time()); ?>";
    var nombreVendedor = '<?php echo $Vendedor; ?>';
    var idVendedor = <?php echo $idVendedor; ?>;

    var myData = {
      "Mensaje": mensaje,
      "Cotizacion": cotizacion,
      "Fecha": fecha,
      "csr_token_7ALF1": token
    };

    $.ajax({
      url: "functions/agregarMensaje.php",
      type: "POST",
      data: myData,
      success: function(data, status, xhr) {
        var datos = JSON.parse(data);
        if (datos.estatus == 'exito') {

          var agregarLista = '<li>' +
            '<div class="timeline-badge info"><i class="glyphicon glyphicon-credit-card"></i></div>' +
            '<div class="timeline-panel">' +
            '<div class="timeline-heading">' +
            '<h4 class="timeline-title">' + nombreVendedor + '</h4>' +
            '</div>' +
            '<div class="timeline-"body">' +
            '<p>' + mensaje + '</p>' +
            '</div>' +
            '<hr>' +
            '<div class="row">' +
            '<div class="col-md-12" align="right">' +
            '<small>' + fecha + '</small>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '</li>';

          $("#add-timeline").addClass("timeline");
          $("#nuevo_mensaje").remove();
          $("#add-timeline").prepend(agregarLista);

          Lobibox.notify("success", {
            size: 'mini',
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: 'center top', //or 'center bottom'
            icon: false,
            img: '../../img/timdesk/checkmark.svg',
            msg: "¡Mensaje enviado!"
          });

          idMensajeFinal = datos.idMensaje;
          $('#agregar_Proyecto').modal('toggle');
          $('#txtMensaje').val("");

        } else {

          Lobibox.notify("error", {
            size: 'mini',
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: 'center top', //or 'center bottom'
            icon: false,
            img: '../../img/timdesk/warning_circle.svg',
            msg: "Ocurrio un error, no se envio el mensaje. Lo puede volver a intentar.."
          });

        }

        $("#btnGuardar").attr("disabled", false);
        $("#cancelarMensaje").attr("disabled", false);
      }
    });
  });


  function cancelarCotizacionF(idCotizacion) {


    let FKUsuario = <?= $_SESSION['PKUsuario'] ?>;
    let token = $("#csr_token_7ALF1").val();

    const swalWithBootstrapButtons = Swal.mixin({
      customClass: {
        actions: "d-flex justify-content-around",
        confirmButton: "btn-custom btn-custom--border-blue",
        cancelButton: "btn-custom btn-custom--blue",
      },
      buttonsStyling: false,
    });

    swalWithBootstrapButtons
      .fire({
        title: "¿Desea continuar?",
        text: "Se cancelará la cotización.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: '<span class="verticalCenter">Cancelar cotización</span>',
        cancelButtonText: '<span class="verticalCenter">Cerrar</span>',
        reverseButtons: false,
      })
      .then((result) => {
        if (result.isConfirmed) {

          $.ajax({
            type: 'POST',
            url: 'functions/cancelarCotizacion.php',
            data: {
              idCotizacion: idCotizacion,
              FKUsuario: FKUsuario,
              csr_token_7ALF1: token
            },
            success: function(data) {

              if (data == "exito") {

                Lobibox.notify("success", {
                  size: 'mini',
                  rounded: true,
                  delay: 3000,
                  delayIndicator: false,
                  position: 'center top', //or 'center bottom'
                  icon: false,
                  img: '../../img/timdesk/checkmark.svg',
                  msg: "Se ha cancelado la cotización"
                });

                $("#aceptarActualizar").html(
                  '<span class="btn-table-custom btn-table-custom--red">Cotización cancelada</span>');

              } else if (data == "fallo-cancelacion") {
                Lobibox.notify("error", {
                  size: 'mini',
                  rounded: true,
                  delay: 3000,
                  delayIndicator: false,
                  position: 'center top', //or 'center bottom'
                  icon: false,
                  img: '../../img/timdesk/warning_circle.svg',
                  msg: "No puedes cancelar un cotización aceptada, facturada, vencida o vendida."
                });

              } else {

                Lobibox.notify("error", {
                  size: 'mini',
                  rounded: true,
                  delay: 3000,
                  delayIndicator: false,
                  position: 'center top', //or 'center bottom'
                  icon: false,
                  img: '../../img/timdesk/warning_circle.svg',
                  msg: "Ocurrio un error, vuelva intentarlo."
                });

              }
            }
          });



        } else if (
          /* Read more about handling dismissals below */
          result.dismiss === Swal.DismissReason.cancel
        ) {}
      });


  }

  //FUNCION PARA ACTUALIZAR ESTADO DEL BOTON DE ESTADO DE COTIZACION
  let estadoCotizacion = <?= $EstatusCotizacion ?>;
  let idCotizacionG = <?= $id ?>;
  let nuevoEstatus;
  setInterval(function() {

    $.ajax({
      type: 'POST',
      url: 'functions/verificarEstadoCotizacion.php',
      data: {
        idCotizacion: idCotizacionG
      },
      success: function(data) {

        if (data != estadoCotizacion) {
          if (data == 1) {
            if (activar_inventario == 1) {
              nuevoEstatus =
                '<button type="button" class="btn-table-custom btn-table-custom--blue" name="btnAgregarProducto" onclick="facturarCotizacion(' +
                idCotizacionG + ');"><img style="width:1.5rem; vertical-align: top;" src="../../img/cotizaciones_nuevos_iconos/ICONO-FACTURACION AZUL NVO-01.svg">Facturar</button>';
            }
            else{
              nuevoEstatus = '';
            }
          }
          if (data == 3) {
            nuevoEstatus =
              '<span class="btn-table-custom btn-table-custom--red">Cotización cancelada</span>';
          }
          if (data == 4) {
            nuevoEstatus =
              '<span class="btn-table-custom btn-table-custom--yellow">Cotización vencida</span>';
          }
          if (data == 5) {
            nuevoEstatus =
              '<button type="button" class="btn-table-custom btn-table-custom--green" name="btnAgregarProducto" onclick="aceptarCotizacion(' +
              idCotizacionG + ');" id="aceptar"><i class="far fa-check-square"></i> Aceptar</button>';
          }

          $("#aceptarActualizar").html(nuevoEstatus);
          estadoCotizacion = data;
        }

      }
    });



  }, 2000);



  //FUNCION PARA ACTUALIZAR LOS MENSAJES QUE VAN LLEGANDO
  let idMensajeFinal = <?= $idMensajeFinal ?>;
  let nombreResponsable, ladoMostrar, colorMostrar;

  setInterval(function() {

    $.ajax({
      type: 'POST',
      url: 'functions/verificarMensajesCotizacion.php',
      data: {
        idCotizacion: idCotizacionG,
        idMensajeFinal: idMensajeFinal
      },
      success: function(data) {

        var obj = jQuery.parseJSON(data);
        $.each(obj, function(key, value) {

          if (value.TipoUsuario == 1) {
            nombreResponsable = value.NombreComercial;
            ladoMostrar = 'class="timeline-inverted"';
            colorMostrar = 'warning';
          } else {
            nombreResponsable = value.Nombre_Empleado;
            ladoMostrar = '';
            colorMostrar = 'info';
          }
          var agregarLista = '<li ' + ladoMostrar + '>' +
            '<div class="timeline-badge ' + colorMostrar +
            '"><i class="glyphicon glyphicon-credit-card"></i></div>' +
            '<div class="timeline-panel">' +
            '<div class="timeline-heading">' +
            '<h4 class="timeline-title">' + nombreResponsable + '</h4>' +
            '</div>' +
            '<div class="timeline-"body">' +
            '<p>' + value.Mensaje + '</p>' +
            '</div>' +
            '<hr>' +
            '<div class="row">' +
            '<div class="col-md-12" align="right">' +
            '<small>' + value.Fecha + '</small>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '</li>';

          $("#add-timeline").addClass("timeline");
          $("#nuevo_mensaje").remove();
          $("#add-timeline").prepend(agregarLista);
          idMensajeFinal = value.PKMensajes_Cotizacion;
        });

      }
    });



  }, 2000);

  function verPedido(idPedido){

    window.location.replace("../pedidos/detallePedido.php?id=" + idPedido);


  }

  $("#cbxMarcarVenta").change(function(){
    if(this.checked){
      const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
          actions: "d-flex justify-content-around",
          confirmButton: "btn-custom btn-custom--border-blue",
          cancelButton: "btn-custom btn-custom--blue",
        },
        buttonsStyling: false,
      });

      swalWithBootstrapButtons
        .fire({
          title: "¿Desea continuar?",
          text: 'Marcar como vendida',
          icon: "warning",
          showCancelButton: true,
          confirmButtonText: '<span class="verticalCenter">Aceptar</span>',
          cancelButtonText: '<span class="verticalCenter">Cancelar</span>',
          reverseButtons: false,
        })
        .then((result) => {
          if (result.isConfirmed) {
            
            $.ajax({
              type: 'POST',
              url: 'functions/venderCotizacion.php',
              data: {
                idCotizacion: idCotizacionG
              },
              dataType: "json",
              success: function (data) {
                console.log("respuesta nombre valida: ", data.Referencia);
                const swalWithBootstrapButtonsReferencia = Swal.mixin({
                  customClass: {
                    actions: "d-flex justify-content-around",
                    confirmButton: "btn-custom btn-custom--border-blue",
                    cancelButton: "btn-custom btn-custom--blue",
                  },
                  buttonsStyling: false,
                });

                swalWithBootstrapButtonsReferencia
                .fire({
                  title: "Referencia de la venta",
                  text: 'Ir a la venta: ' + data.Referencia,
                  icon: "info",
                  showCancelButton: true,
                  confirmButtonText: '<span class="verticalCenter">Aceptar</span>',
                  cancelButtonText: '<span class="verticalCenter">Cancelar</span>',
                  reverseButtons: false,
                })
                .then((result) => {
                  if (result.isConfirmed) {
                    window.open('../ventas_directas/catalogos/ventas/ver_ventas.php?vd=' + data.PKVentaDirecta, '_blank');
                    setTimeout(()=>{
                      location.reload();
                    }, 500);
                  } else if (
                    result.dismiss === Swal.DismissReason.cancel
                  ) {
                    location.reload();
                  }
                });      
              },
              error: function (error) {
                console.log("respuesta nombre valida: ", error);
                const swalWithBootstrapButtonsReferencia = Swal.mixin({
                  customClass: {
                    actions: "d-flex justify-content-around",
                    confirmButton: "btn-custom btn-custom--border-blue",
                    cancelButton: "btn-custom btn-custom--blue",
                  },
                  buttonsStyling: false,
                });

                swalWithBootstrapButtonsReferencia
                .fire({
                  title: "Referencia de la venta",
                  text: 'Ir a la venta: ' + error.responseText.Referencia,
                  icon: "info",
                  showCancelButton: true,
                  confirmButtonText: '<span class="verticalCenter"> <a href="../ventas_directas/catalogos/ventas/ver_ventas.php?vd=' + error.responseText.PKVentaDirecta + '" target="_blank">Aceptar</a></span>',
                  cancelButtonText: '<span class="verticalCenter">Cancelar</span>',
                  reverseButtons: false,
                })
                /* .then((result) => {
                  if (result.isConfirmed) {
                    
                    $.ajax({
                      type: 'POST',
                      url: 'functions/venderCotizacion.php',
                      data: {
                        idCotizacion: idCotizacion
                      },
                      dataType: "json",
                      success: function (data) {
                        console.log("respuesta nombre valida: ", data);
                        location.reload();        
                      },
                      error: function (error) {
                        console.log("respuesta nombre valida: ", error);
                        $("#tblCotizacion").DataTable().ajax.reload(); 
                        const swalWithBootstrapButtonsReferencia = Swal.mixin({
                          customClass: {
                            actions: "d-flex justify-content-around",
                            confirmButton: "btn-custom btn-custom--border-blue",
                            cancelButton: "btn-custom btn-custom--blue",
                          },
                          buttonsStyling: false,
                        });
                        
                      }
                    });
                  } else if (
                    result.dismiss === Swal.DismissReason.cancel
                  ) {
                    $("#cbxMarcarVenta-" + idCotizacion).prop("checked", false);
                  }
                }) */;
                
              }
            });
          } else if (
            /* Read more about handling dismissals below */
            result.dismiss === Swal.DismissReason.cancel
          ) {
            $("#cbxMarcarVenta").prop("checked", false);
          }
        });
      }
  });

</script>
    <script>
      loadAlertsNoti('alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
      setInterval(loadAlertsNoti, 10000, 'alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
      window.addEventListener('load', function(){
        var url = new URL(window.location.href);
        var chatBool = !!url.searchParams.get("chat");
        if(chatBool) {
          mostrarChat();
        }
      })
    </script>

</body>

</html>