<?php
session_start();

$jwt_ruta = "../../";
require_once '../jwt.php';

if (isset($_SESSION["Usuario"])) {
    require_once '../../include/db-conn.php';
} else {
    header("location:../dashboard.php");
}

if (isset($_POST['idOrdenPedidoU'])) {
        $id = $_POST['idOrdenPedidoU'];
        $stmt = $conn->prepare('SELECT ops.id_orden_pedido_empresa, 
                                       so.id as id_sucursal_origen, 
                                       sd.id as id_sucursal_destino, 
                                       c.PKCliente as id_cliente, 
                                       DATE_FORMAT(ops.fecha_captura, "%Y-%m-%d %H:%i:%s") as fecha_ingreso, 
                                       ops.observaciones, 
                                       cot.id_cotizacion_empresa as numero_cotizacion, 
                                       vd.Referencia as numero_venta_directa, 
                                       ops.empresa_id, 
                                       ops.estatus_orden_pedido_id, 
                                       c.razon_social, ops.tipo_pedido,
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
                                      INNER JOIN sucursales as so ON so.id = ops.sucursal_origen_id 
                                      LEFT JOIN sucursales as sd ON sd.id = ops.sucursal_destino_id 
                                      LEFT JOIN clientes as c ON c.PKCliente = ops.cliente_id 
                                      LEFT JOIN cotizacion as cot ON cot.PKCotizacion = ops.numero_cotizacion 
                                      LEFT JOIN ventas_directas as vd ON vd.PKVentaDirecta = ops.numero_venta_directa
                                      LEFT JOIN direcciones_envio_cliente decl on vd.direccion_entrega_id = decl.PKDireccionEnvioCliente
                                      LEFT JOIN paises psE on decl.Pais = psE.PKPais
                                      LEFT JOIN estados_federativos efE on decl.Estado = efE.PKEstado  
                                      LEFT JOIN direcciones_envio_cliente declC on cot.direccion_entrega_id = declC.PKDireccionEnvioCliente
                                      LEFT JOIN paises psEC on declC.Pais = psEC.PKPais
                                      LEFT JOIN estados_federativos efEC on declC.Estado = efEC.PKEstado 
                                  WHERE ops.empresa_id = '.$_SESSION['IDEmpresa'] . ' AND ops.id = :id');
        
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch();
        $id_orden_pedido_empresa = sprintf("%011d", $row['id_orden_pedido_empresa']);
        $FechaIngreso = $row['fecha_ingreso'];
        $id_cliente = $row['id_cliente'];
        $observaciones = $row['observaciones'];
        $id_sucursal_origen = $row['id_sucursal_origen'];
        $id_sucursal_destino = $row['id_sucursal_destino'];
        $numero_cotizacion = $row['numero_cotizacion'];
        $numero_venta = $row['numero_venta_directa'];
        $empresa_id = $row['empresa_id'];  
        $estatus_orden_pedido_id = $row['estatus_orden_pedido_id']; 
        $razon_social = $row['razon_social']; 
        $tipo_pedido = $row['tipo_pedido']; 
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
} else {
    header("location:../dashboard.php");
}


if($empresa_id != $_SESSION['IDEmpresa']){
  header("location:./");
}

if($estatus_orden_pedido_id != 1 && $estatus_orden_pedido_id != 2){
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
  <title>Timlid | Editar Pedido</title>

  <!-- ESTILOS -->
  <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../../css/bootstrap-clockpicker.min.css" rel="stylesheet" type="text/css">
  <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../css/slimselect.min.css" rel="stylesheet">
  <link href="../../css/styles.css" rel="stylesheet">
  <link href="../../css/lobibox.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../../css/notificaciones.css">
  <!-- JS -->
  <script src="../../vendor/jquery/jquery.min.js"></script>
  <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../../js/slimselect.min.js"></script>
  <script src="../../js/sweet/sweetalert2.js"></script>
  <script src="../../js/lobibox.min.js"></script>
  <script src="../../js/notificaciones_timlid.js" charset="utf-8"></script>
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
$icono = 'ICONO-PEDIDOS-AZUL.svg';
$titulo = "Pedido";

if($tipo_pedido == 1){
  $titulo.= " - Traspaso";
}
if($tipo_pedido == 2){
  $titulo.= " - General";
}
if($tipo_pedido == 3){
  $titulo.= " - Cotización";
}
if($tipo_pedido == 4){
  $titulo.= " - Venta";
}
$backIcon = true;
$backRoute = './detallePedido.php?id='.$id;
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

                          <?php if($tipo_pedido == 3 || $tipo_pedido == 4) { ?>

                              <div class="row">
                                <div class="col-lg-2">
                                  <label for="usr">No. pedido:</label>
                                  <input type="text" name="id_referencia" id="id_referencia" class="form-control" value="<?php echo sprintf("%011d", $id_orden_pedido_empresa);?>" disabled>
                                </div>
                                <div class="col-lg-2">
                                  <label for="usr"><?php if($tipo_pedido == 3) echo "No. Cotización: "; if($tipo_pedido == 4) echo "No. Venta:"; ?></label>
                                  <input type="text" name="numero_referencia" id="numero_referencia" class="form-control" value="<?php if($numero_cotizacion != "") echo sprintf("%011d",$numero_cotizacion); if($numero_venta != "") echo $numero_venta; ?>" disabled>
                                </div>
                                <div class="col-lg-3">
                                    <label for="usr">Sucursal origen:</label>
                                    <select name="cmbSucursalOrigen" id="chosenSucursalOrigen" disabled>
                                    <option value="0">Elegir opción</option>
                                    <?php
                                      $stmt = $conn->prepare('SELECT id, sucursal FROM sucursales WHERE estatus = 1 AND empresa_id = :idempresa');
                                      $stmt->bindValue("idempresa",$_SESSION['IDEmpresa']);
                                      $stmt->execute();
                                    ?>
                                    <?php foreach ($stmt as $option): ?>
                                    <option value="<?php echo $option['id']; ?>"  <?php if($option['id'] == $id_sucursal_origen) echo "selected"; ?>>
                                      <?php echo $option['sucursal']; ?></option>
                                    <?php endforeach;?>
                                  </select>
                                  <span style="color: #d9534f;display: none;position: absolute;" id="alertaSucursal">Selecciona la sucursal de origen</span>
                                </div>
                                <div class="col-lg-5">
                                  <label for="usr">Dirección de envío:</label>
                                  <input type="text" name="direccion_envio" id="direccion_envio" class="form-control" value="<?php echo $GLOBALS["DireccionE"]; ?>" disabled>
                                </div>
                              </div>
                              <br>

                          <?php } else{     ?>
                              <div class="row">
                                <div class="col-lg-4">
                                  <label for="usr">No. pedido:</label>
                                  <input type="text" name="id_referencia" id="id_referencia" class="form-control" value="<?=$id_orden_pedido_empresa?>" disabled>
                                </div>
                                <div class="col-lg-4">
                                    <label for="usr">Sucursal origen:</label>
                                    <select name="cmbSucursalOrigen" id="chosenSucursalOrigen" required>
                                    <option value="0">Elegir opción</option>
                                    <?php
                                      $stmt = $conn->prepare('SELECT id, sucursal FROM sucursales WHERE estatus = 1 AND activar_inventario = 1 AND empresa_id = :idempresa');
                                      $stmt->bindValue("idempresa",$_SESSION['IDEmpresa']);
                                      $stmt->execute();
                                    ?>
                                    <?php foreach ($stmt as $option): ?>
                                    <option value="<?php echo $option['id']; ?>"  <?php if($option['id'] == $id_sucursal_origen) echo "selected"; ?>>
                                      <?php echo $option['sucursal']; ?></option>
                                    <?php endforeach;?>
                                  </select>
                                  <span style="color: #d9534f;display: none;position: absolute;" id="alertaSucursal">Selecciona la sucursal de origen</span>
                                </div>
                                <div class="col-lg-4">
                                    <label for="usr">Sucursal destino:</label>
                                    <select name="cmbSucursalDestino" id="chosenSucursalDestino" required>
                                    <option value="0">Elegir opción</option>
                                    <?php

                                      if($tipo_pedido == 1 || $tipo_pedido == 3 || $tipo_pedido == 4){
                                        $activar_inventario = 1;
                                      }
                                      else{
                                        $activar_inventario = 0;
                                      }

                                      $stmt = $conn->prepare('SELECT id, sucursal FROM sucursales WHERE estatus = 1 AND activar_inventario = :activar_inventario AND empresa_id = :idempresa');
                                      $stmt->bindValue("activar_inventario",$activar_inventario);
                                      $stmt->bindValue("idempresa",$_SESSION['IDEmpresa']);
                                      $stmt->execute();
                                    ?>
                                    <?php foreach ($stmt as $option): ?>
                                    <option value="<?php echo $option['id']; ?>"  <?php if($option['id'] == $id_sucursal_destino) echo "selected";?>>
                                      <?php echo $option['sucursal']; ?></option>
                                    <?php endforeach;?>
                                  </select>
                                  <span style="color: #d9534f;display: none;position: absolute;" id="alertaSucursal">Selecciona la sucursal destino</span>
                                </div>
                              </div>
                              <br>

                          <?php }           ?>
                          
                          <div class="row">
                              <div class="col-lg-3" <?php if($tipo_pedido == 1) echo "style='display:none;'" ?> >
                                <label for="usr">Clientes:</label>
                                <?php
                                    $stmt = $conn->prepare('SELECT PKCliente, NombreComercial FROM clientes WHERE estatus = 1 AND empresa_id = :idempresa');
                                    $stmt->bindValue("idempresa",$_SESSION['IDEmpresa']);
                                    $stmt->execute();
                                  ?>
                                <select name="cmbCliente" id="chosen" <?php if($tipo_pedido == 3 || $tipo_pedido == 4) echo "disabled"; ?> required>
                                  <option value="0">Elegir opción</option>
                                  <?php foreach ($stmt as $option): ?>
                                  <option value="<?php echo $option['PKCliente']; ?>"   <?php if($option['PKCliente'] == $id_cliente) echo "selected";?>>
                                    <?php echo $option['NombreComercial']; ?></option>
                                  <?php endforeach;?>
                                </select>
                                <span style="color: #d9534f;display: none;position: absolute;" id="alertaClientesSucursal">Selecciona el cliente o una sucursal destino</span>
                              </div>
                            <div class="col-lg-3" <?php if($tipo_pedido == 1) echo "style='display:none;'" ?>>
                              <label for="usr">Razón social:</label>
                              <div id="cmbRazon"><?php if($id_cliente != "") echo $razon_social;?></div>
                            </div>
                            <div class="col-lg-6">
                              <label for="usr">Fecha de generación:</label>
                              <?php $fechaGeneracion = date('Y-m-d\TH:i:s',strtotime($FechaIngreso)); $fechaGeneracionSinHora = date('Y-m-d', strtotime($FechaIngreso)); ?>
                              <input type="datetime-local" class="form-control" name="txtFechaGeneracion" id="txtFechaGeneracion" value="<?=$fechaGeneracion?>" readonly>
                            </div>
                          </div>

                    <?php if($tipo_pedido == 2){ ?>
                          <br>
                          <div class="row">
                            <div class="col-lg-12 small" style="color: #F00;">Nota: Sólo se puede seleccionar el cliente o la sucursal destino.</div>
                          </div>
                    <?php } ?>

                          <br>
                          <div class="row">
                            <div class="col-lg-6">
                              <label for="usr">Producto:</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <span class="input-group-addon" style="width:70%">
                                    <select name="cmbProducto" id="chosenProducto" style="width: 90%;" required>
                                      <option value="">Elegir opción</option>
                                      <?php
                                        $stmt = $conn->prepare('SELECT p.PKProducto,p.Nombre,p.ClaveInterna FROM productos as p INNER JOIN operaciones_producto as op ON p.FKTipoProducto = op.FKProducto WHERE op.Venta = 1 AND p.empresa_id = :idempresa AND p.estatus = 1 ORDER BY p.Nombre ASC');
                                        $stmt->bindValue("idempresa",$_SESSION['IDEmpresa']);
                                        $stmt->execute();
                                      ?>
                                      <?php foreach ($stmt as $option): ?>
                                      <option value="<?php echo $option['PKProducto']; ?>">
                                        <?php
                                          if (trim($option['ClaveInterna']) == "") {
                                              echo $option['Nombre'] . "</option>";
                                          } else {
                                              echo $option['ClaveInterna'] . " - " . $option['Nombre'] . "</option>";
                                          }
                                        ?>
                                        <?php endforeach;?>
                                    </select>
                                  </span>
                                  <button style="width: 25%;font-size: 14px;" type="button"
                                    class="btn-custom btn-custom--border-blue" id="mostrar_todos">Mostrar todos
                                    los productos</button>
                                </div>
                              </div>
                              <span style="color: #d9534f;display: none;position: absolute;" id="alertaProducto">Ingresa
                                un producto</span>
                            </div>

                            <div class="col-lg-4">
                              <div>
                                <div class="row" id="divCantidad">
                                  <div class="col-lg-12">
                                    <label for="usr">Cantidad:</label>
                                    <input type='number' value='' name="txtPiezas" id="txtPiezas"
                                      class='form-control numeric-only'>
                                    <span style="color: #d9534f;display: none;position: absolute;" id="alertaPiezas"
                                      onkeydown="insertProduct(event)">Ingresa la cantidad de piezas</span>
                                  </div>
                                </div>

                              </div>
                            </div>
                            <div class="col-lg-2 d-flex justify-content-start align-items-end">
                              <input type="hidden" name="txtClaveUnidad" id="txtClaveUnidad" value="" />
                              <button type="button" class="btn-custom btn-custom--border-blue" id="agregarProducto">Agregar</button>
                            </div>
                          </div>
                        </div>

                        <br><br>
                        <div class="table-responsive redondear">
                          <table class="table table-sm" id="ordenpedido">
                            <thead class="text-center header-color">
                              <tr>
                                <th>Clave/Producto</th>
                                <th>Unidad de medida</th>
                                <th>Cantidad</th>
                                <th></th>
                              </tr>
                            </thead>
                            <tbody id="lstProductos">
                                <?php
                                    $stmt = $conn->prepare('SELECT p.PKProducto, p.Nombre, p.ClaveInterna, dop.cantidad_pedida, csu.Descripcion FROM detalle_orden_pedido_por_sucursales as dop INNER JOIN productos as p ON p.PKProducto = dop.producto_id LEFT JOIN info_fiscal_productos as ifp ON ifp.FKProducto = p.PKProducto LEFT JOIN claves_sat_unidades as csu ON csu.PKClaveSATUnidad = ifp.FKClaveSATUnidad WHERE dop.orden_pedido_id = :id');
                                    $stmt->execute(array(':id' => $id));
                                    $numero_productos = $stmt->rowCount();
                                    $rowp = $stmt->fetchAll();
                                    $cuenta = 0;

                                    foreach ($rowp as $rp) {

                                        if($rp['Descripcion'] == ""){
                                          $ClaveUnidad = "Sin unidad";
                                        }
                                        else{
                                          $ClaveUnidad = $rp['Descripcion'];
                                        }
                                        
                                        ?>

                                                                <tr id="idProducto_<?=$rp['PKProducto']?>" class="text-center">
                                                                  <td id="nombreproducto_<?=$rp['PKProducto']?>">
                                                                    <?=$rp['ClaveInterna'] . ' - ' . $rp['Nombre']?>
                                                                  </td>
                                                                  <td><?=$ClaveUnidad?></td>
                                                                  <td id="piezas_<?=$rp['PKProducto']?>">
                                                                    <input type="number" name="inp_piezas[]" id="piezasUnic_<?=$rp['PKProducto']?>" value="<?=$rp['cantidad_pedida']?>" class="form-control modificarnumero numeros-solo" min="1">                                                                    
                                                                  </td>
                                                                  <input type="hidden" id="piezaAnt_<?=$rp['PKProducto']?>" value="<?=$rp['cantidad_pedida']?>" />
                                                                  <input type="hidden" name='inp_productos[]' value="<?=$rp['PKProducto']?>" />
                                                                  <td>
                                                                    <button type="button" class="btn eliminarProductos" id="<?=$rp['PKProducto']?>"><img src="../../img/timdesk/delete.svg" widtd="20px"></button>
                                                                  </td>
                                                                </tr>
                                                                <?php
                                          $cuenta++;
                                    }?>
                            </tbody>
                          </table>
                        </div>

                        <div class="row">
                          <div class="col-lg-12" style="color:#d9534f;display: none;text-align: center;"
                            id="mostrarMensaje">
                            <h2>Ingresa un producto al menos.</h2>
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-lg-12">
                            <label for="usr">Observaciones:</label>
                            <textarea class="form-control" cols="10" rows="3" name="txtObservaciones" id="txtObservaciones"
                              placeholder="Agregar observaciones a la orden de pedido"></textarea>
                          </div>
                        </div>
                        <br>
                        <input type="hidden" name="id_orden_pedido" id="id_orden_pedido" = value="<?=$id?>">
                        <input type="hidden" name="csr_token_78L4" id="csr_token_78L4" value="<?=$token?>">
                        <input type="hidden" name="tipo_pedido" id="tipo_pedido" = value="<?=$tipo_pedido?>">
                        <button type="button" class="btn-custom btn-custom--blue float-right" name="btnAgregar"
                          id="btnAgregar">Modificar</button>
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

  <!-- Core plugin JavaScript-->
  <script src="../../js/bootstrap-clockpicker.min.js"></script>
  <script src="../../js/jquery.number.min.js"></script>
  <script src="../../js/numeral.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="../../js/sb-admin-2.min.js"></script>
  <script src="../../js/scripts.js"></script>
  <script src="../../js/validaciones.js"></script>
  <script>
  var cuenta = <?php echo $cuenta;?>;
  var idProductoG;
  var cuentaIVAexento = 0;
  var cantidadAnterior = 0;
  var token = $("#csr_token_78L4").val();

  $("#chosen")[0].reportValidity();
  $("#chosen")[0].setCustomValidity('Completa este campo.');

  $("#chosen").change(function() {
     let clientel = parseInt($("#chosen").val());

     if(clientel > 0){
      selectSucursalDestino.set(0);
     }

     $.ajax({
      type: 'POST',
      url: 'functions/razon_social.php',
      data: {
        idCliente: clientel
      },
      success: function(data) {
        $("#cmbRazon").html(data);
      }
    });
    
  });

  $("#chosenSucursalOrigen").change(function() {
    SucursalOrigenL = parseInt($("#chosenSucursalOrigen").val());
    SucursalDestinoL = parseInt($("#chosenSucursalDestino").val());

    if(SucursalOrigenL == SucursalDestinoL && SucursalOrigenL > 0 && SucursalDestinoL > 0){

      selectSucursalOrigen.set(0);
      Lobibox.notify("warning", {
        size: 'mini',
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: 'center top', //or 'center bottom'
        icon: false,
        img: '../../img/timdesk/warning_circle.svg',
        msg: "No puedes seleccionar la misma sucursal origen y destino."
      });
    }

  });

  $("#chosenSucursalDestino").change(function() {
    SucursalOrigenL = parseInt($("#chosenSucursalOrigen").val());
    SucursalDestinoL = parseInt($("#chosenSucursalDestino").val());

    if(SucursalOrigenL == SucursalDestinoL && SucursalOrigenL > 0 && SucursalDestinoL > 0){

      selectSucursalDestino.set(0);
      Lobibox.notify("warning", {
        size: 'mini',
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: 'center top', //or 'center bottom'
        icon: false,
        img: '../../img/timdesk/warning_circle.svg',
        msg: "No puedes seleccionar la misma sucursal origen y destino."
      });

      return;
    }

    if(SucursalDestinoL > 0){
      selectCliente.set(0);
    }

  });

  $("#chosenProducto").change(function() {
    var idProducto = $("#chosenProducto").val();

    $.ajax({
      type: 'POST',
      url: 'functions/valoresProducto.php',
      data: {
        idProducto: idProducto
      },
      success: function(data) {
        var datos = JSON.parse(data);

        $("#txtClaveUnidad").val(datos.ClaveUnidad);
      }
    });
  });

  //Previene que se use enter para ingresar el formulario.
  jQuery(function($) { // DOM ready

    $('form').on('keydown', function(e) {
      if (e.which === 13 && !$(e.target).is('textarea')) {
        e.preventDefault();
      }
    });

  });


  $("#agregarProducto").click(function() {

    var idProducto = parseInt($("#chosenProducto").val());
    var Producto = $("#chosenProducto").children("option:selected").text();
    var Piezas = parseInt($("#txtPiezas").val());
    var nuevo_elemento, Piezas_old;
    var Operacion;
    let UnidadMedida = $("#txtClaveUnidad").val();

    if (isNaN(idProducto)) {
      $("#alertaProducto").css("display", "block");
      setTimeout(function() {
        $("#alertaProducto").css("display", "none");
      }, 2000);
      return;
    }

    if (Piezas < 1 || isNaN(Piezas)) {
      $("#alertaPiezas").css("display", "block");
      setTimeout(function() {
        $("#alertaPiezas").css("display", "none");
      }, 2000);
      return;
    }

    if ($('#idProducto_' + idProducto).length) {
      //cuando ya se agregó el producto
      Piezas_old = parseInt($("#piezasUnic_" + idProducto).val());

      var PiezasImp = Piezas;
      Piezas = Piezas + Piezas_old;

      $('#idProducto_' + idProducto).empty();
      nuevo_elemento = "<th id='nombreproducto_" + idProducto + "'>" + Producto + "</th>" +
        "<th>" + UnidadMedida  + "</th>" +
        "<th id='piezas_" + idProducto + "'><input type='number' id='piezasUnic_" + idProducto +
        "' name='inp_piezas[]' value='" + Piezas + "' class='modificarnumero numeros-solo' min='1' >" + "</th>" +
        "<input type='hidden' id='piezaAnt_" + idProducto + "' value='" + Piezas + "' />" +
        "<input type='hidden' name='inp_productos[]' value='" + idProducto + "' />" +
        "<th><button type='button' class='btn eliminarProductos' id='" + idProducto + "'><img src='../../img/timdesk/delete.svg' width='20px' /></button></th>";
      $('#idProducto_' + idProducto).append(nuevo_elemento);

    } else {
      //cuando se ingresa un nuevo producto

      nuevo_elemento = "<tr id='idProducto_" + idProducto + "' class='text-center'>" +
        "<th id='nombreproducto_" + idProducto + "'>" + Producto + "</th>" +
        "<th>" + UnidadMedida  + "</th>" +
        "<th id='piezas_" + idProducto + "'><input type='number' name='inp_piezas[]' id='piezasUnic_" + idProducto +
        "' value='" + Piezas + "' class='modificarnumero numeros-solo' min='1' >" + "</th>" +
        "<input type='hidden' id='piezaAnt_" + idProducto + "' value='" + Piezas + "' />" +
        "<input type='hidden' name='inp_productos[]' value='" + idProducto + "' />" +
        "<th><button type='button' class='btn eliminarProductos' id='" + idProducto + "'><img src='../../img/timdesk/delete.svg' width='20px' /></button></th>" +
        "</tr>";

      $('#lstProductos').append(nuevo_elemento);

    }


    selectProductos.set("");
    $('#txtPiezas').val("");
    cuenta++;

  });

  $("#btnAgregar").click(function() {
    var fechaGeneracion = "<?=$fechaGeneracionSinHora?>";
    let tipo_pedido = <?php echo $tipo_pedido; ?>;
    let gCliente = 0;

    if(tipo_pedido != 1){
      gCliente = parseInt($("#chosen").val());
    }
    
    let gSucursal = parseInt($("#chosenSucursalOrigen").val());
    let gSucursalDestino = parseInt($("#chosenSucursalDestino").val());
    let id_orden_pedido = $("#id_orden_pedido").val();

    if (gSucursal < 1) {
      $("#alertaSucursal").css("display", "block");
        setTimeout(function() {
          $("#alertaSucursal").css("display", "none");
        }, 2000);
      return;
    }

    if(tipo_pedido != 1){
      if (gCliente < 1 && gSucursalDestino < 1) {
        $("#alertaClientesSucursal").css("display", "block");
          setTimeout(function() {
            $("#alertaClientesSucursal").css("display", "none");
          }, 2000);
        return;
      }
    }

    if (cuenta < 1) {
        $("#mostrarMensaje").css("display", "block");
        setTimeout(function() {
          $("#mostrarMensaje").css("display", "none");
        }, 2000);
        return;
    }

    $("#btnAgregar").prop("disabled", true);

    var tabla_ordenpedido = {
      html: $('#ordenpedido').html()
    };

    $('#ordenpedido').append("<input type='hidden' name='tabla_ordenpedido'  id='tabla_ordenpedido' value='" +
      tabla_ordenpedido.html + "' />");

      $.ajax({
        type: 'post',
        url: 'functions/pedidoSubmitModificar.php',
        data: {
          cmbSucursalOrigen: $('#chosenSucursalOrigen').val(),
          cmbSucursalDestino: $("#chosenSucursalDestino").val(),
          cmbCliente: $("#chosen").val(),
          txtFechaGeneracion: $("#txtFechaGeneracion").val(),
          txtObservaciones: $("#txtObservaciones").val(),
          id_orden_pedido: id_orden_pedido,
          tipo_pedido: tipo_pedido,
          form: $("#form-cotizacion").serializeArray(),
          token: token
        },
        success: function(data) {
            if(data != "error-general"){
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
                      title: "Orden de pedido",
                      html: "Se ha modificado tu orden de pedido.",
                      icon: "success",
                      showCancelButton: false,
                      confirmButtonText:
                        '<span class="verticalCenter">OK</span>',
                      cancelButtonText:
                        '<span class="verticalCenter">Cancelar</span>',
                      reverseButtons: false,
                    })
                    .then((result) => {
                      if (result.isConfirmed) {
                        $(location).attr('href', 'detallePedido.php?id=' + id_orden_pedido);
                      } else if (
                        //Read more about handling dismissals below
                        result.dismiss === Swal.DismissReason.cancel
                      ) {
                      }
                    });

                    setTimeout(function () {
                      $(location).attr('href', 'detallePedido.php?id=' + id_orden_pedido);
                    }, 4000);
            }
            else{
                Lobibox.notify("error", {
                  size: 'mini',
                  rounded: true,
                  delay: 3000,
                  delayIndicator: false,
                  position: 'center top', //or 'center bottom'
                  icon: false,
                  img: '../../img/timdesk/warning_circle.svg',
                  msg: "Algo salió mal, vuelve a intentarlo."
                });
                $("#btnAgregar").prop("disabled", false);
            }
        },
        error: function(){
          $("#btnAgregar").prop("disabled", false);
        }
      });
  });


  $("#txtPiezas").on('keydown', function(e) {
    if (e.keyCode == 13) {

      var idProducto = parseInt($("#chosenProducto").val());
      var Producto = $("#chosenProducto").children("option:selected").text();
      var Piezas = parseInt($("#txtPiezas").val());
      var nuevo_elemento, Piezas_old;
      var Operacion;
      let UnidadMedida = $("#txtClaveUnidad").val();

      if (isNaN(idProducto)) {
        $("#alertaProducto").css("display", "block");
        setTimeout(function() {
          $("#alertaProducto").css("display", "none");
        }, 2000);
        return;
      }

      if (Piezas < 1 || isNaN(Piezas)) {
        $("#alertaPiezas").css("display", "block");
        setTimeout(function() {
          $("#alertaPiezas").css("display", "none");
        }, 2000);
        return;
      }

      if ($('#idProducto_' + idProducto).length) {
        //cuando ya se agregó el producto
        Piezas_old = parseInt($("#piezasUnic_" + idProducto).val());

        var PiezasImp = Piezas;
        Piezas = Piezas + Piezas_old;

        $('#idProducto_' + idProducto).empty();
        nuevo_elemento = "<th id='nombreproducto_" + idProducto + "'>" + Producto + "</th>" +
          "<th>" + UnidadMedida  + "</th>" +
          "<th id='piezas_" + idProducto + "'><input type='number' id='piezasUnic_" + idProducto +
          "' name='inp_piezas[]' value='" + Piezas + "' class='modificarnumero numeros-solo' min='1' >" + "</th>" +
          "<input type='hidden' id='piezaAnt_" + idProducto + "' value='" + Piezas + "' />" +
          "<input type='hidden' name='inp_productos[]' value='" + idProducto + "' />" +
          "<th><button type='button' class='btn eliminarProductos' id='" + idProducto + "'><img src='../../img/timdesk/delete.svg' width='20px' /></button></th>";
        $('#idProducto_' + idProducto).append(nuevo_elemento);

      } else {
        //cuando se ingresa un nuevo producto

        nuevo_elemento = "<tr id='idProducto_" + idProducto + "' class='text-center'>" +
          "<th id='nombreproducto_" + idProducto + "'>" + Producto + "</th>" +
          "<th>" + UnidadMedida  + "</th>" +
          "<th id='piezas_" + idProducto + "'><input type='number' name='inp_piezas[]' id='piezasUnic_" + idProducto +
          "' value='" + Piezas + "' class='modificarnumero numeros-solo' min='1' >" + "</th>" +
          "<input type='hidden' id='piezaAnt_" + idProducto + "' value='" + Piezas + "' />" +
          "<input type='hidden' name='inp_productos[]' value='" + idProducto + "' />" +
          "<th><button type='button' class='btn eliminarProductos' id='" + idProducto + "'><img src='../../img/timdesk/delete.svg' width='20px' /></button></th>" +
          "</tr>";

        $('#lstProductos').append(nuevo_elemento);

      }


      selectProductos.set("");
      $('#txtPiezas').val("");
      cuenta++;
    }
  });

  //Eliminar productos
  $(document).on("click", ".eliminarProductos", function() {
    var idProducto = this.id;

    $('#idProducto_' + idProducto).remove();
    cuenta--;

  });

  $(document).on("keyup", ".modificarnumero", function() {
    this.value = this.value.replace(/[^0-9]/g, '');
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
          $('#chosenProducto').html(data);
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
          $('#chosenProducto').html(data);
        }
      });
      todos = 0;
      $("#mostrar_todos").html("Mostrar todos los productos");
    }

  });

  var selectProductos = new SlimSelect({
    select: '#chosenProducto',
    deselectLabel: '<span class="">✖</span>'
  });

  var selectCliente = new SlimSelect({
    select: '#chosen',
    deselectLabel: '<span class="">✖</span>'
  });

  var selectSucursalOrigen = new SlimSelect({
    select: '#chosenSucursalOrigen',
    deselectLabel: '<span class="">✖</span>'
  });

  var selectSucursalDestino = new SlimSelect({
    select: '#chosenSucursalDestino',
    deselectLabel: '<span class="">✖</span>'
  });

  </script>
  <script>
    /* $(document).ready(function() {
      $('#ordenpedido').DataTable();
    }); */
    loadAlertsNoti('alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
    setInterval(loadAlertsNoti, 10000, 'alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
  </script>

</body>

</html>