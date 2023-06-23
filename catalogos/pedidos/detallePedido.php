<?php
session_start();

$jwt_ruta = "../../";
require_once '../jwt.php';

if (isset($_SESSION["Usuario"])) {
  require_once '../../include/db-conn.php';
  $empresa_id = -1;
  if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare('SELECT ops.id_orden_pedido_empresa, 
                                   so.sucursal as sucursal_origen, 
                                   sd.sucursal as sucursal_destino, 
                                   c.razon_social as cliente, 
                                   c.PKCliente as idCliente,
                                   DATE_FORMAT(ops.fecha_captura, "%d/%m/%Y %H:%i:%s") as fecha_ingreso, 
                                   u.usuario, 
                                   ops.observaciones, 
                                   cot.id_cotizacion_empresa, 
                                   vd.Referencia, 
                                   ops.empresa_id, 
                                   ops.tipo_pedido, 
                                   ops.estatus_factura_id as estatus_factura,
                                   ops.estatus_orden_pedido_id,
                                   decl.Calle as CalleE,
                                   decl.Numero_exterior as NumExtE,
                                   decl.Numero_Interior as NumIntE,
                                   decl.Colonia as ColoniaE,
                                   decl.Municipio as MunicipioE,
                                   efE.Estado as EstadoE,
                                   psE.Pais as PaisE,
                                   ifnull(decl.PKDireccionEnvioCliente,0) as isNulo,
                                   declC.Calle as CalleEC,
                                   declC.Numero_exterior as NumExtEC,
                                   declC.Numero_Interior as NumIntEC,
                                   declC.Colonia as ColoniaEC,
                                   declC.Municipio as MunicipioEC,
                                   efEC.Estado as EstadoEC,
                                   psEC.Pais as PaisEC,
                                   ifnull(declC.PKDireccionEnvioCliente,0) as isNuloC,
                                   ifnull(cot.PKCotizacion,0) as PKCotizacion,
                                   ifnull(vd.PKVentaDirecta,0) as PKVentaDirecta
                            FROM orden_pedido_por_sucursales as ops 
                                LEFT JOIN sucursales as so ON so.id = ops.sucursal_origen_id 
                                LEFT JOIN sucursales as sd ON sd.id = ops.sucursal_destino_id 
                                LEFT JOIN clientes as c ON c.PKCliente = ops.cliente_id 
                                LEFT JOIN usuarios as u ON u.id = ops.usuario_creo_id 
                                LEFT JOIN cotizacion as cot ON cot.PKCotizacion = ops.numero_cotizacion 
                                LEFT JOIN ventas_directas as vd ON vd.PKVentaDirecta = ops.numero_venta_directa 
                                LEFT JOIN direcciones_envio_cliente decl on vd.direccion_entrega_id = decl.PKDireccionEnvioCliente
                                LEFT JOIN paises psE on decl.Pais = psE.PKPais
                                LEFT JOIN estados_federativos efE on decl.Estado = efE.PKEstado 
                                LEFT JOIN direcciones_envio_cliente declC on cot.direccion_entrega_id = declC.PKDireccionEnvioCliente
                                LEFT JOIN paises psEC on declC.Pais = psEC.PKPais
                                LEFT JOIN estados_federativos efEC on declC.Estado = efEC.PKEstado
                            WHERE ops.empresa_id = ' . $_SESSION['IDEmpresa'] . ' AND ops.id = :id');
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch();
    $nopedido = $row['id_orden_pedido_empresa'];
    $FechaIngreso = $row['fecha_ingreso'];
    $cliente = $row['cliente'];
    $idCliente = $row['idCliente'];
    $observaciones = $row['observaciones'];
    $sucursal_origen = $row['sucursal_origen'];
    $sucursal_destino = $row['sucursal_destino'];
    $numero_cotizacion = $row['id_cotizacion_empresa'];
    $numero_venta_directa = $row['Referencia'];
    $empresa_id = $row['empresa_id'];
    $email_envio = $row['usuario'];
    $tipo_pedido = $row['tipo_pedido'];
    $estatus_factura = $row['estatus_factura'];
    $estatus_orden = $row['estatus_orden_pedido_id'];
    if ($row['PKCotizacion'] != 0){
      if ($row['isNuloC'] == '0'){
        $GLOBALS["DireccionE"] = 'S/N';
      }else{
        $GLOBALS["DireccionE"] = $row['CalleEC'].' '.$row['NumExtEC'].' Int.'.$row['NumIntEC'].', '.$row['ColoniaEC'].', '.$row['MunicipioEC'].', '.$row['EstadoEC'].', '.$row['PaisEC'];
      }
    }else if ($row['PKVentaDirecta'] != 0){
      if ($row['isNulo'] == '0'){
        $GLOBALS["DireccionE"] = 'S/N';
      }else{
        $GLOBALS["DireccionE"] = $row['CalleE'].' '.$row['NumExtE'].' Int.'.$row['NumIntE'].', '.$row['ColoniaE'].', '.$row['MunicipioE'].', '.$row['EstadoE'].', '.$row['PaisE'];
      }
    }else{
      $GLOBALS["DireccionE"] = 'S/N';
    }
    
  }
} else {
  header("location:../dashboard.php");
}

if ($empresa_id != $_SESSION['IDEmpresa']) {
  header("location:./");
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
  <title>Timlid | Ver pedido</title>

  <!-- ESTILOS -->
  <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../../css/bootstrap-clockpicker.min.css" rel="stylesheet" type="text/css">
  <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="../../vendor/datatables/responsive.dataTables.css" rel="stylesheet">
  <link rel="stylesheet" href="../../vendor/datatables/buttons.dataTables.css">
  <link href="../../css/stylesNewTable.css" rel="stylesheet">
  <link href="../../css/lobibox.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../../css/notificaciones.css">
  <link href="../../css/timeline.css" rel="stylesheet">
  <link href="../../css/slimselect.min.css" rel="stylesheet">
  <link href="../../css/styles.css" rel="stylesheet">
  <link href="style/detallepedido.css" rel="stylesheet">
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
  <script src="../../vendor/datatables/buttons.html5.min.js"></script>
  <script src="../../vendor/jszip/jszip.min.js"></script>
  <script src="../../js/jquery.redirect.min.js"></script>
  <script src="../../js/lobibox.min.js"></script>
  <script src="../../js/sweet/sweetalert2.js"></script>
  <script src="../../js/notificaciones_timlid.js" charset="utf-8"></script>
  <script src="js/detallepedido.js" charset="utf-8"></script>
  <script src="../../js/slimselect.min.js"></script>
</head>

<body id="page-top" class="sidebar-toggled">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <?php
    $icono = 'ICONO-PEDIDOS-AZUL.svg';
    $titulo = 'Pedido';

    if ($tipo_pedido == 1) {
      $titulo .= " - Traspaso";
    }
    if ($tipo_pedido == 2) {
      $titulo .= " - General";
    }
    if ($tipo_pedido == 3) {
      $titulo .= " - Cotización";
    }
    if ($tipo_pedido == 4) {
      $titulo .= " - Venta";
    }

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
          <!-- Basic Card Example -->
          <div class="card shadow mb-4">
            <div class="card-body">
              <div class="d-flex">
                <div>
                  <span data-toggle="modal" data-target="#datos_envio" class="btn-table-custom btn-table-custom--blue-lightest"><img style="width:1.5rem; vertical-align: top;" src="../../img/cotizaciones_nuevos_iconos/ICONO-ENVIAR AZUL CLARO NVO-01.svg"></img>Enviar</span>
                </div>
                <div>
                  <span class="btn-table-custom custom-color-green" name="btnEditarOP" onclick="editarPedido(<?= $id ?>);">
                  <img style="width:1.5rem; vertical-align: top;" src="../../img/cotizaciones_nuevos_iconos/ICONO-EDITAR VERDE OSCURO NVO-01.svg"></img>Editar</span>
                </div>
                <div id="actualizarBoton">
                  <span class="btn-table-custom btn-table-custom--orange" name="btnChat" onclick="mostrarBitacora()"><img style="width:1.5rem; vertical-align: top;" src="../../img/cotizaciones_nuevos_iconos/ICONO-BITACORA NARANJA CLARO NVO-01.svg"></img>Bitacora</span>
                </div>
                <div id="generarSalida">

                </div>
                <div>
                  <span data-toggle="modal" class="btn-table-custom btn-table-custom--turquoise" name="btnDescargar" onclick="descargarOrdenPedido(<?= $id ?>);"><img style="width:1.5rem; vertical-align: top;" src="../../img/cotizaciones_nuevos_iconos/ICONO-DESCARGAR AZUL OSCURO NVO-01.svg"></img> Descargar</span>
                </div>
                <?php
                if ($tipo_pedido == 1 || $tipo_pedido == 2) {
                  echo '
                              <span data-toggle="modal" class="btn-table-custom btn-table-custom--red" onclick="cancelarOrdenPedidoF(' . $id . ');"><img style="width:1.5rem; vertical-align: top;" src="../../img/cotizaciones_nuevos_iconos/ICONO-CANCELAR ROJO NVO-01.svg"></img> Cancelar</span>';
                } else {
                  echo '
                              <span data-toggle="modal" class="btn-table-custom btn-table-custom--red" onclick="cancelarOrdenPedidoF(' . $id . ');"><img style="width:1.5rem; vertical-align: top;" src="../../img/cotizaciones_nuevos_iconos/ICONO-CANCELAR ROJO NVO-01.svg"></img> Cancelar</span>
                              <span data-toggle="modal" class="btn-table-custom custom-color-blue" name="btnDescargar" onclick="cerrarOrdenPedidoF(' . $id . ');"><img style="width:1rem; height:1.5rem; vertical-align: top;" src="../../img/cotizaciones_nuevos_iconos/ICONO-CERRAR_PEDIDO-AZUL-NVO.svg"></img> Cerrar</span>';
                }
                ?>
                <div>
                  <span data-toggle="modal" data-target="#reimprimir" class="btn-table-custom custom-color-blue" name="btnReimprimir"><img style="width:1.5rem; vertical-align: top;" src="../../img/cotizaciones_nuevos_iconos/ICONO-IMPRIMIR_AZUL NVO-01.svg"></img> Reimprimir parcialidades</span>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="row my-3">
                <div class="col-lg-3 textData">
                  <p><b class="textBlue">Estatus del pedido:</b>
                  <?php 
                    switch ($estatus_orden) {
                      case 1:
                        echo '<span class="btn-table-custom--gray">Nuevo</span>';
                        break;
                      case 2:
                        echo '<span class="btn-table-custom--gray">Nuevo-FD</span>';
                        break;
                      case 3:
                        echo '<span class="btn-table-custom--yellow">Parcialmente surtido</span>';
                        break;
                      case 4:
                        echo '<span class="btn-table-custom--yellow">Parcialmente surtido-FD</span>';
                        break;
                      case 5:
                        echo '<span class="btn-table-custom--turquoise">Surtido completo</span>';
                        break;
                      case 6:
                        echo '<span class="btn-table-custom--green">Surtido completo-FD</span>';
                        break;
                      case 7:
                        echo '<span class="btn-table-custom--red">Cerrado</span>';
                        break;
                      case 8:
                        echo '<span class="btn-table-custom--red">Cancelado</span>';
                        break;
                      case 9:
                        echo '<span class="btn-table-custom--turquoise">Facturado-directo</span>';
                        break;
                      case 10:
                        echo '<span class="btn-table-custom--turquoise">Facturado-almacen</span>';
                        break;
                      case 11:
                        echo '<span class="btn-table-custom--orange">Remisionado parcial</span>';
                        break;
                      case 12:
                        echo '<span class="btn-table-custom--orange">Remisionado completo</span>';
                        break;
                    }
                  ?></p><br>
                  <p><b class="textBlue">No. Pedido:</b> <?php echo sprintf("%011d", $nopedido); ?></p><br>
                  <?php
                  if (trim($numero_cotizacion) != "") {
                    echo "<b>Numero cotización: </b>" . sprintf("%011d", $numero_cotizacion);
                  }
                  if (trim($numero_venta_directa) != "") {
                    echo "<p><b class='textBlue'>Núm. venta directa: </b>" . $numero_venta_directa . "</p>";
                  }
                  ?>
                </div>
                <div class="col-lg-3 textData">
                <?php
                  echo "<div>";
                  echo "<p><b class='textBlue'>Estatus facturación:</b> ";
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
                    case 7:
                      echo '<span class="btn-table-custom--orange">Remisionado parcial</span>';
                      break;
                    case 8:
                      echo '<span class="btn-table-custom--orange">Remisionado completo</span>';
                      break;
                    case 9:
                      echo '<span class="btn-table-custom--gray">Facturado de remision parcial</span>';
                      break;
                    case 10:
                      echo '<span class="btn-table-custom--dark">Facturado de remision completo</span>';
                      break;
                  }
                  echo "</p></div>";
                  ?>
                  <?php

                  if ($tipo_pedido == 1) {
                    //traspaso
                    echo "<p><b class='textBlue'>Sucursal origen:</b> " . $sucursal_origen . "</p><br>" .
                      "<p><b class='textBlue'>Sucursal destino:</b> " . $sucursal_destino . "</p><br>";
                  } elseif ($tipo_pedido == 2) {
                    //muestras
                    echo "<p><b class='textBlue'>Sucursal origen:</b> " . $sucursal_origen . "</p><br>";

                    if ($sucursal_destino != "") {
                      echo "<p><b class='textBlue'>Sucursal destino:</b> " . $sucursal_destino . "</p>";
                    }

                    if ($cliente != "") {
                      "<p><b class='textBlue'>Cliente:</b><a style='cursor:pointer;' href='../clientes/catalogos/clientes/detalles_cliente.php?c=" . $idCliente . "'>" . $cliente . "</a></p>";
                    }
                  } elseif ($tipo_pedido == 3 || $tipo_pedido == 4) {
                    //cotizacion
                    echo "<p><b class='textBlue'>Sucursal origen:</b> " . $sucursal_origen . "</p><br>" .
                      "<p><b class='textBlue'>Cliente:</b><a style='cursor:pointer;' href='../clientes/catalogos/clientes/detalles_cliente.php?c=" . $idCliente . "'> " . $cliente . "</a></p><br>";
                  }

                  ?>



                </div>
                <div class="col-lg-3 textData">
                  <b class="textBlue">Dirección de entrega:</b> <?= $GLOBALS["DireccionE"]; ?>
                </div>
                <div class="col-lg-3 textData">
                  <b class="textBlue">Fecha de ingreso:</b> <?= $FechaIngreso; ?>
                </div>
                <hr class="my-3" style="width: 100%">
              </div>
            </div>

            <div class="card-body" id="mostrarOrdenPedido">
              <div class="row">
                <div class="col-lg-12">
                  <div class="table-responsive redondear">
                    <table class="table table-sm" id="cotizacion">
                      <thead class="header-color">
                        <tr>
                          <th>Clave/Producto</th>
                          <th>Unidad de medida</th>
                          <th>Cantidad</th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody id="lstProductos">
                        <?php
                        $stmt = $conn->prepare('SELECT p.PKProducto, p.Nombre, p.ClaveInterna, dop.cantidad_pedida, csu.Descripcion FROM detalle_orden_pedido_por_sucursales as dop INNER JOIN productos as p ON p.PKProducto = dop.producto_id LEFT JOIN info_fiscal_productos as ifp ON ifp.FKProducto = p.PKProducto LEFT JOIN claves_sat_unidades as csu ON csu.PKClaveSATUnidad = ifp.FKClaveSATUnidad WHERE dop.orden_pedido_id = :id GROUP BY p.PKProducto');
                        $stmt->execute(array(':id' => $id));
                        $numero_productos = $stmt->rowCount();
                        $rowp = $stmt->fetchAll();

                        foreach ($rowp as $rp) {

                          if ($rp['Descripcion'] == "") {
                            $ClaveUnidad = "Sin unidad";
                          } else {
                            $ClaveUnidad = $rp['Descripcion'];
                          }

                        ?>

                          <tr id="idProducto_<?= $rp['PKProducto'] ?>">
                            <td style="text-align: left;" id="nombreproducto_<?= $rp['PKProducto'] ?>">
                              <?= $rp['ClaveInterna'] . ' - ' . $rp['Nombre'] ?>
                            </td>
                            <td class="text-center"><?= $ClaveUnidad ?></td>
                            <td class="text-center" id="piezas_<?= $rp['PKProducto'] ?>"><?= $rp['cantidad_pedida'] ?></td>
                            <input type="hidden" id="piezaAnt_<?= $rp['PKProducto'] ?>" value="<?= $rp['cantidad_pedida'] ?>" />
                            <input type="hidden" name='inp_productos[]' value="<?= $rp['PKProducto'] ?>" />
                            <td></td>
                          </tr>
                        <?php

                        } ?>
                      </tbody>
                    </table>
                  </div>

                  <div class="row">
                    <div class="col-lg-12" style="color:#d9534f;display: none;text-align: center;" id="mostrarMensaje">
                      <h2>Ingresa un producto al menos.</h2>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row my-3">
                <div class="col-lg-12">
                  <b>Observaciones:</b> <br><?= $observaciones; ?>
                </div>
              </div>
            </div>



            <div id="mostrarBitacora" style="display:none;">
              <?php
              $stmt = $conn->prepare('SELECT u.nombre,b.created_at,m.Mensaje
                        FROM bitacora_orden_pedido AS b
                        LEFT JOIN usuarios AS u ON b.usuario_id = u.id
                        LEFT JOIN mensajes_acciones AS m ON b.mensaje_id = m.PKMensajesAcciones
                        WHERE b.orden_pedido_id = :id');
              $stmt->bindValue(':id', $id);
              $stmt->execute();
              while ($row = $stmt->fetch()) {
                $fecha = new DateTime($row['created_at']);
                $usuario = $row['nombre'];

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
          <!-- End of Main Content -->

          <!-- End of Content Wrapper -->

        </div>
        <!-- End of Page Wrapper -->

        <!-- Modal Datos envio -->
        <div id="datos_envio" class="modal fade">
          <div class="modal-dialog">
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
                      <input class="form-control" type="email" name="txtOrigen" id="txtOrigen" value="<?= $email_envio; ?>" required>
                    </div>
                  </div>
                  <div class="row">
                    <div class="form-group col-lg-12">
                      <label for="txtEmail">Para: </label>
                      <input class="form-control" type="email" name="txtDestino" id="txtDestino" value="" required>
                    </div>
                  </div>
                  <div class="row">
                    <div class="form-group col-lg-12">
                      <label for="">Asunto: </label>
                      <input class="form-control" type="text" name="txtAsunto" id="txtAsunto" value="Pedido <?php echo sprintf("%011d", $nopedido); ?>" required>
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
                  <img src="../../img/chat/loading.gif" id="loading" width="30px" style="position: absolute; bottom: 70px;left: 50%;text-align: center;display: none;">
                </div>
                <div class="modal-footer">
                  <input type="hidden" name="csr_token_8UY8N" id="csr_token_8UY8N" value="<?= $token ?>">
                  <button type="button" class="btn-custom btn-custom--border-blue" data-dismiss="modal" name="button" id="cancelarOrdenPedido"><i class="fas fa-times"></i> Cancelar</button>
                  <button class="btn-custom btn-custom--blue" type="button" name="button" id="enviarPedido"><i class="fas fa-envelope"></i> Enviar</button>
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
                  <h4 class="modal-title">Portal de clientes</h4>
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

        <!-- Reimprimir parcialidades-->
        <div id="reimprimir" class="modal fade" style="z-index: 100000000">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title">Reimprimir parcialidades</h4>
                <button type="button" class="close text-light" data-dismiss="modal" aria-hidden="true">x</button>
              </div>
              <br>
              <div class="row">
                <div class="col-lg-8" style="text-align: center;position:relative;left: 16%;">
                  <select class="form-select" name="parcialidades" id="parcialidades">
                    <option data-placeholder="true"></option>
                    <?php 
                      foreach($rowParcialidades as $salida){
                        echo '<option value="1">Hola mundo!!!</option>';
                      }
                    ?>
                  </select>
                </div>
              </div>
              <br>
              <div class="modal-footer">
                <input type="button" class="btn-custom btn-custom--border-blue" data-dismiss="modal" value="Cancelar" id="cancelarMensaje">
                <input type="button" class="btn-custom btn-custom--blue" id="btnDescargarParcialidad" value="Descargar">
              </div>
            </div>
          </div>
        </div>

        <!-- Footer -->
        <?php
          $rutaf = "../";
          require_once '../footer.php';
          ?>
          <!-- End of Footer -->
      </div>
    </div>
  </div>
  <style>
    .textBlue{
      color: var(--azul-mas-oscuro);
    }

    .textData{
      font-size:large;
    }
    
    .custom-color-blue{
      color: #76a5f3;
    }

    .custom-color-green{
      color: #1b8482;
    }
  </style>

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
      $("#tblProductos").dataTable({
          "ajax": "function_VerCotizacion.php?id=" + id,
          "columns": [{
              "data": "No"
            },
            {
              "data": "Clave"
            },
            {
              "data": "Cantidad"
            },
            {
              "data": "Unidad medida"
            },
            {
              "data": "Precio unitario"
            },
            {
              "data": "Importe"
            }
          ],
          "language": idioma_espanol,
          columnDefs: [{
            orderable: false,
            targets: 2
          }],
          responsive: true
        }

      );

      let cmbParcialidades = new SlimSelect({
        select: '#parcialidades',
        placeholder: 'Selecciona salida'
      });

      
      //Obtener las salidas del pedido y cargarlas en el combo correspondiente   
      let htmlCmbParcialidades = '';
      fetch("php/funciones.php?clase=get_data&funcion=get_ParcialidadesPedido&data=<?php echo $id ?>")
      .then( respuesta => {
          //console.log(respuesta)
          return respuesta.json()
      })
      .then( datos => {
          datos.forEach(element => {
            htmlCmbParcialidades +=
                      '<option value="' +
                      element.folio_salida +
                      '">' +
                      element.folio_salida +
                      ' / ' + 
                      element.fecha_salida +
                      "</option>";
          });
          document.getElementById('parcialidades').innerHTML = htmlCmbParcialidades;
      })
      .catch( error => {
          //console.log(error)
      });

      document.getElementById('btnDescargarParcialidad').addEventListener('click', ()=>{
        let folio = cmbParcialidades.selected();

        //Obtener el tipo de salida seleccionada 
        fetch("php/funciones.php?clase=get_data&funcion=get_TipoParcialidadesPedido&data=" + folio)
        .then( respuesta => {
            //console.log(respuesta)
            return respuesta.json()
        })
        .then( datos => {
          console.log(datos[0].tipo);
          switch (datos[0].tipo) {
            case '1':
              window.location.href = "../inventarios_productos/catalogos/salidas_productos/functions/descargar_SalidaCoti.php?folio="+folio+"&orden="+0;
            break;
            case '2':
              window.location.href = "../inventarios_productos/catalogos/salidas_productos/functions/descargar_SalidaVenta.php?folio="+folio+"&orden="+0;
              console.log('si llega');
            break;
            case '3':
              window.location.href = "../inventarios_productos/catalogos/salidas_productos/functions/descargar_Salida.php?folio="+folio+"&orden="+0;
            break;
            case '4':
              window.location.href = "../inventarios_productos/catalogos/salidas_productos/functions/descargar_SalidaDevolucion.php?folio="+folio+"&cuenta="+0;
            break;
            case '5':
              window.location.href = "../inventarios_productos/catalogos/salidas_productos/functions/descargar_SalidaGral.php?folio="+folio+"&orden="+0;
            break;
            case '6':
              window.location.href = "../inventarios_productos/catalogos/salidas_productos/functions/descargar_SalidaGral.php?folio="+folio+"&orden="+0;
            break;
          }
        })
        .catch( error => {
            //console.log(error)
        });
      });
      
    });
  </script>

  <script>
    function editarPedido(idOrdenPedido) {

      $.ajax({
        type: 'POST',
        url: 'functions/verificarEstadoPedido.php',
        data: {
          idOrdenPedido: idOrdenPedido
        },
        success: function(data) {
          if (data == 1 || data == 2) {
            $().redirect('editarPedido.php', {
              'idOrdenPedidoU': idOrdenPedido
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
              msg: "No se pueden modificar pedidos surtidos, facturados, cancelados o cerrados."
            });
          }

        }
      });

    }

    function descargarOrdenPedido(idOrdenPedido) {

      let empresa_id = <?php echo $empresa_id; ?>;
      let session_empresa = <?php echo $_SESSION['IDEmpresa']; ?>;
      let token = $("#csr_token_8UY8N").val();
      if (empresa_id == session_empresa) {
        $().redirect('functions/descargar_Pedido.php', {
          'idOrdenPedido': idOrdenPedido,
          'csr_token_8UY8N': token
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
          msg: "No tienes permiso para descargar el pedido."
        });
      }

    }


    function isEmail(email) {
      var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
      return regex.test(email);
    }

    let contadorEnviar = 0;
    $("#enviarPedido").click(function() {
      var id = $("#txtId").val();
      var emailOrigen = $("#txtOrigen").val();
      var emailDestino = $("#txtDestino").val();
      var asunto = $("#txtAsunto").val();
      var mensaje = $("#txaMensaje").val();
      let token = $("#csr_token_8UY8N").val();


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

      if (emailDestino.trim() == "") {
        $("#txtDestino")[0].reportValidity();
        $("#txtDestino")[0].setCustomValidity('Ingresa el correo electrónico de destino.');
        return;
      }

      var validarEmailDestino = isEmail(emailDestino);
      if (validarEmailDestino == false) {
        $("#txtDestino")[0].reportValidity();
        $("#txtDestino")[0].setCustomValidity('Ingresa un correo electrónico válido.');
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

      $("#enviarPedido").attr("disabled", true);
      $("#cancelarOrdenPedido").attr("disabled", true);
      $("#loading").css("display", "flex");

      $.ajax({
        type: 'POST',
        url: 'functions/enviar_Pedido.php',
        data: {
          txtId: id,
          txtOrigen: emailOrigen,
          txtDestino: emailDestino,
          txtAsunto: asunto,
          txaMensaje: mensaje,
          csr_token_8UY8N: token
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
              msg: "Se envio el pedido al correo."
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
              msg: "Ocurrió un error al enviar, vuelva intentarlo."
            });
          }

          $("#enviarPedido").attr("disabled", false);
          $("#cancelarOrdenPedido").attr("disabled", false);
          $("#loading").css("display", "none");
        }
      });

    });


    function mostrarBitacora() {
      $("#mostrarBitacora").show();
      $("#mostrarOrdenPedido").hide();

      /* $("#actualizarBoton").html('<button type="button" class="btn-custom btn-custom--yellow" name="btnChat" onclick="mostrarOrdenPedido()"><i class="fas fa-receipt"></i> Pedido</button>'); */
    }

    function mostrarOrdenPedido() {
      $("#mostrarOrdenPedido").show();
      $("#mostrarBitacora").hide();

      $("#actualizarBoton").html('<button type="button" class="btn-custom btn-custom--green" name="btnChat" onclick="mostrarBitacora()"><i class="fas fa-book"></i> Bitácora</button>');

    }

    function generarSalida(idPedido) {
      let token = $("#csr_token_8UY8N").val();
      $().redirect('../inventarios_productos/catalogos/salidas_productos/agregar_salida.php', {
        'idPedido': idPedido,
        'csr_token_8UY8N': token
      });
    }

    function cancelarOrdenPedidoF(idOrdenPedido) {

      let FKUsuario = <?= $_SESSION['PKUsuario'] ?>;
      let token = $("#csr_token_8UY8N").val();

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
          text: "Se cancelará el pedido.",
          icon: "warning",
          showCancelButton: true,
          confirmButtonText: '<span class="verticalCenter">Cancelar pedido</span>',
          cancelButtonText: '<span class="verticalCenter">Cerrar</span>',
          reverseButtons: false,
        })
        .then((result) => {
          if (result.isConfirmed) {

            $.ajax({
              type: 'POST',
              url: 'functions/cancelarPedido.php',
              data: {
                idOrdenPedido: idOrdenPedido,
                FKUsuario: FKUsuario,
                csr_token_8UY8N: token
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
                    msg: "Se ha cancelado el pedido"
                  });

                  setTimeout(function() {
                    $(location).attr('href', './');
                  }, 4000);

                } else if (data == "fallo-cancelacion") {
                  Lobibox.notify("error", {
                    size: 'mini',
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: 'center top', //or 'center bottom'
                    icon: false,
                    img: '../../img/timdesk/warning_circle.svg',
                    msg: "No puedes cancelar un pedido surtido o facturado."
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
                    msg: "Ocurrió un error, vuelva intentarlo."
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


    function cerrarOrdenPedidoF(idOrdenPedido) {

      let FKUsuario = <?= $_SESSION['PKUsuario'] ?>;
      let token = $("#csr_token_8UY8N").val();

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
          text: "Se cerrará el pedido y ya no se podrá modificar.",
          icon: "warning",
          showCancelButton: true,
          confirmButtonText: '<span class="verticalCenter">Cerrar pedido</span>',
          cancelButtonText: '<span class="verticalCenter">Cerrar ventana</span>',
          reverseButtons: false,
        })
        .then((result) => {
          if (result.isConfirmed) {

            $.ajax({
              type: 'POST',
              url: 'functions/cerrarPedido.php',
              data: {
                idOrdenPedido: idOrdenPedido,
                FKUsuario: FKUsuario,
                csr_token_8UY8N: token
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
                    msg: "Se ha cerrado el pedido"
                  });

                  setTimeout(function() {
                    $(location).attr('href', './');
                  }, 4000);

                } else if (data == "fallo-cancelacion") {
                  Lobibox.notify("error", {
                    size: 'mini',
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: 'center top', //or 'center bottom'
                    icon: false,
                    img: '../../img/timdesk/warning_circle.svg',
                    msg: "No puedes cerrar un pedido surtido o facturado."
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
                    msg: "Ocurrió un error, vuelva intentarlo."
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
  </script>
  <script>
    loadAlertsNoti('alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
    setInterval(loadAlertsNoti, 10000, 'alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
  </script>
  <script>
    var OrdenPedidoID = '<?php echo $id; ?>';
  </script>
</body>

</html>