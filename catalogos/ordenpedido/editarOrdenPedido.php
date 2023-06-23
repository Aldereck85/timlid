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
        $stmt = $conn->prepare('SELECT ops.id_orden_pedido_empresa, so.id as id_sucursal_origen, sd.id as id_sucursal_destino, c.PKCliente as id_cliente, DATE_FORMAT(ops.fecha_captura, "%Y-%m-%d %H:%i:%s") as fecha_ingreso, DATE_FORMAT(ops.fecha_entrega, "%Y-%m-%d") as fecha_entrega, ops.observaciones, ops.numero_cotizacion, ops.numero_venta_directa, ops.empresa_id, ops.estatus_orden_pedido_id, ops.vendedor_id, c.razon_social FROM orden_pedido_por_sucursales as ops INNER JOIN sucursales as so ON so.id = ops.sucursal_origen_id LEFT JOIN sucursales as sd ON sd.id = ops.sucursal_destino_id LEFT JOIN clientes as c ON c.PKCliente = ops.cliente_id LEFT JOIN usuarios as u ON u.id = ops.vendedor_id WHERE ops.empresa_id = '.$_SESSION['IDEmpresa'] . ' AND ops.id = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch();
        $id_orden_pedido_empresa = sprintf("%011d", $row['id_orden_pedido_empresa']);
        $vendedor = $row['vendedor_id'];
        $FechaIngreso = $row['fecha_ingreso'];
        $FechaEntrega = $row['fecha_entrega'];
        $id_cliente = $row['id_cliente'];
        $observaciones = $row['observaciones'];
        $id_sucursal_origen = $row['id_sucursal_origen'];
        $id_sucursal_destino = $row['id_sucursal_destino'];
        $numero_cotizacion = $row['numero_cotizacion'];
        $numero_venta = $row['numero_venta_directa'];
        $empresa_id = $row['empresa_id'];  
        $estatus_orden_pedido_id = $row['estatus_orden_pedido_id']; 
        $razon_social = $row['razon_social']; 

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
  <link rel="icon" type="image/png" href="../../img/header/bluTimlid.png">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Timlid | Editar Orden de Pedido</title>

  <!-- Custom fonts for this template-->
  <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../../css/bootstrap-clockpicker.min.css" rel="stylesheet" type="text/css">
  <link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../css/styles.css" rel="stylesheet">

  <link href="../../css/slimselect.min.css" rel="stylesheet">
  <style type="text/css">
  .header-color {
    font-size: 18px;
    color: #fff;
    line-height: 1.4;
    background-color: #006dd9;
  }

  .redondear {
    border-radius: 10px;
  }

  table .btn {
    padding: 0 0;
  }

  .table-input {
    height: 20px;
    width: 80%;
    border: none;
    border-bottom: 1px solid #f2f2f2;
    text-align: center;
    background-color: #f2f2f2;
  }

  .table-input:focus {
    text-align: center;
    background-color: #f2f2f2;
  }

  .total {
    color: white;
    background-color: #006dd9;
    font-size: 24px;
  }

  .redondearAbajoIzq {
    border-radius: 0px 0px 0px 10px;
  }

  .redondearAbajoDer {
    border-radius: 0px 0px 10px 0px;
  }

  .modificarnumero {
    text-align: center;
    border: 0;
  }
  </style>

  <link href="../../css/lobibox.min.css" rel="stylesheet">
  <!-- Bootstrap core JavaScript-->
  <script src="../../vendor/jquery/jquery.min.js"></script>
  <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../../js/slimselect.min.js"></script>
  <script src="../../js/sweet/sweetalert2.js"></script>

  <script src="../../js/lobibox.min.js"></script>
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

      <!-- Main Content -->
      <div id="content">

        <?php
$rutatb = "../";
$icono = '../../img/icons/ICONO COTIZACIONES-01.svg';
$titulo = "Orden de pedido";
require_once '../topbar.php';
?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <div class="row">

            <div class="col-lg-12">

              <!-- Basic Card Example -->
              <div class="card shadow mb-4">
                <div class="card-header">
                  Orden de pedido
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-12">
                      <form action="" method="post" id="form-cotizacion">
                        <div class="form-group">

                          <?php if($numero_cotizacion != "" || $numero_venta != "") { ?>

                              <div class="row">
                                <div class="col-lg-3">
                                  <label for="usr">No. pedido:</label>
                                  <input type="text" name="id_referencia" id="id_referencia" class="form-control" value="<?php echo sprintf("%011d", $id_orden_pedido_empresa);?>" disabled>
                                </div>
                                <div class="col-lg-3">
                                  <label for="usr"><?php if($numero_cotizacion != "") echo "No. Cotización: "; if($numero_venta != "") echo "No. Venta:"; ?></label>
                                  <input type="text" name="numero_referencia" id="numero_referencia" class="form-control" value="<?php if($numero_cotizacion != "") echo sprintf("%011d",$numero_cotizacion); if($numero_venta != "") echo sprintf("%011d",$numero_venta); ?>" disabled>
                                </div>
                                <div class="col-lg-3">
                                    <label for="usr">Sucursal origen:</label>
                                    <select name="cmbSucursalOrigen" id="chosenSucursalOrigen" required>
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
                                <div class="col-lg-3">
                                    <label for="usr">Sucursal destino:</label>
                                    <select name="cmbSucursalDestino" id="chosenSucursalDestino" required>
                                    <option value="0">Elegir opción</option>
                                    <?php
                                      $stmt = $conn->prepare('SELECT id, sucursal FROM sucursales WHERE estatus = 1 AND activar_inventario = 0 AND empresa_id = :idempresa');
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
                                <div class="col-lg-4">
                                    <label for="usr">Sucursal destino:</label>
                                    <select name="cmbSucursalDestino" id="chosenSucursalDestino" required>
                                    <option value="0">Elegir opción</option>
                                    <?php
                                      $stmt = $conn->prepare('SELECT id, sucursal FROM sucursales WHERE estatus = 1 AND activar_inventario = 0 AND empresa_id = :idempresa');
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
                            <div class="col-lg-3">
                              <label for="usr">Clientes:</label>
                              <?php
                                  $stmt = $conn->prepare('SELECT PKCliente, NombreComercial FROM clientes WHERE estatus = 1 AND empresa_id = :idempresa');
                                  $stmt->bindValue("idempresa",$_SESSION['IDEmpresa']);
                                  $stmt->execute();
                                ?>
                              <select name="cmbCliente" id="chosen" required>
                                <option value="0">Elegir opción</option>
                                <?php foreach ($stmt as $option): ?>
                                <option value="<?php echo $option['PKCliente']; ?>"   <?php if($option['PKCliente'] == $id_cliente) echo "selected";?>>
                                  <?php echo $option['NombreComercial']; ?></option>
                                <?php endforeach;?>
                              </select>
                              <span style="color: #d9534f;display: none;position: absolute;" id="alertaClientesSucursal">Selecciona el cliente o una sucursal destino</span>
                            </div>
                            <div class="col-lg-3">
                              <label for="usr">Razón social:</label>
                              <div id="cmbRazon"><?php if($id_cliente != "") echo $razon_social;?></div>
                            </div>
                            <div class="col-lg-6">
                                <label for="usr">Vendedor:</label>
                                <select name="cmbVendedor" id="chosenVendedor" required>
                                  <option value="0">Elegir opción</option>
                                  <?php
                                  //carga los empleados que estan determinados como vendedores
                                    $stmt = $conn->prepare('SELECT e.PKEmpleado, e.Nombres, e.PrimerApellido FROM empleados as e INNER JOIN relacion_tipo_empleado as rte ON rte.empleado_id = e.PKEmpleado WHERE e.estatus = 1 AND empresa_id = :idempresa AND rte.tipo_empleado_id = 1 ORDER BY e.Nombres ASC, e.PrimerApellido ASC');
                                    $stmt->bindValue("idempresa",$_SESSION['IDEmpresa']);
                                    $stmt->execute();
                                  ?>
                                  <?php foreach ($stmt as $option): ?>
                                  <option value="<?php echo $option['PKEmpleado']; ?>"  <?php if($vendedor == $option['PKEmpleado']) echo "selected"; ?>>
                                    <?php echo $option['Nombres']." ".$option['PrimerApellido']; ?></option>
                                  <?php endforeach;?>
                                </select>
                            </div>
                          </div>

                          <br>
                          <div class="row">
                            <div class="col-lg-12 small" style="color: #F00;">Nota: Sólo se puede seleccionar el cliente o la sucursal destino.</div>
                          </div>
                          <br>

                          <div class="row">
                            <div class="col-lg-6">
                              <label for="usr">Fecha de generación:</label>
                              <?php $fechaGeneracion = date('Y-m-d\TH:i:s',strtotime($FechaIngreso)); $fechaGeneracionSinHora = date('Y-m-d', strtotime($FechaIngreso)); ?>
                              <input type="datetime-local" class="form-control" name="txtFechaGeneracion" id="txtFechaGeneracion" value="<?=$fechaGeneracion?>" readonly>
                            </div>
                            <div class="col-lg-6">
                              <label for="usr">Fecha de entrega:</label>
                              <input type="date" class="form-control" name="txtFechaEntrega"
                                id="txtFechaEntrega" value="<?=$FechaEntrega?>"  min="<?=$FechaEntrega?>" required>
                              <span style="color: #d9534f;display: none;position: absolute;" id="alertaFechaEntrega">Selecciona la fecha de entrega</span>
                              <span style="color: #d9534f;display: none;position: absolute;" id="alertaFechaEntregaMenor">La fecha de entrega no puede ser menor a la fecha de generación.</span>
                            </div>
                          </div>
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
                                        $stmt = $conn->prepare('SELECT p.PKProducto,p.Nombre,p.ClaveInterna FROM productos as p INNER JOIN tipos_productos as tp ON p.FKTipoProducto = tp.PKTipoProducto LEFT JOIN rel_acciones_producto as rap ON rap.FKProducto = p.PKProducto WHERE p.empresa_id = :idempresa AND p.estatus = 1 AND rap.FKAccionProducto = 2 ORDER BY p.Nombre ASC');
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
                                                                  <th id="nombreproducto_<?=$rp['PKProducto']?>">
                                                                    <?=$rp['ClaveInterna'] . ' - ' . $rp['Nombre']?>
                                                                  </th>
                                                                  <th><?=$ClaveUnidad?></th>
                                                                  <th id="piezas_<?=$rp['PKProducto']?>">
                                                                    <input type="number" name="inp_piezas[]" id="piezasUnic_<?=$rp['PKProducto']?>" value="<?=$rp['cantidad_pedida']?>" class="modificarnumero numeros-solo" min="1">                                                                    
                                                                  </th>
                                                                  <input type="hidden" id="piezaAnt_<?=$rp['PKProducto']?>" value="<?=$rp['cantidad_pedida']?>" />
                                                                  <input type="hidden" name='inp_productos[]' value="<?=$rp['PKProducto']?>" />
                                                                  <th>
                                                                    <button type="button" class="btn eliminarProductos" id="<?=$rp['PKProducto']?>"><img src="../../img/timdesk/delete.svg" width="20px"></button>
                                                                  </th>
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
                            <label for="usr">Observaciones</label>
                            <textarea class="form-control" cols="10" rows="3" name="txtObservaciones" id="txtObservaciones"
                              placeholder="Agregar observaciones a la orden de pedido"></textarea>
                          </div>
                        </div>
                        <br>
                        <input type="hidden" name="id_orden_pedido" id="id_orden_pedido" = value="<?=$id?>">
                        <input type="hidden" name="csr_token_78L4" id="csr_token_78L4" value="<?=$token?>">
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


  $("#txtFechaEntrega")[0].reportValidity();
  $("#txtFechaEntrega")[0].setCustomValidity(
        'La fecha de entrega no puede ser menor a la fecha de generación.');

  $("#btnAgregar").click(function() {

    var fechaEntrega = $("#txtFechaEntrega").val();
    var fechaGeneracion = "<?=$fechaGeneracionSinHora?>";
    let gCliente = parseInt($("#chosen").val());
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

    if (gCliente < 1 && gSucursalDestino < 1) {
      $("#alertaClientesSucursal").css("display", "block");
        setTimeout(function() {
          $("#alertaClientesSucursal").css("display", "none");
        }, 2000);
      return;
    }

    if(fechaEntrega.trim() == ""){
      $("#alertaFechaEntrega").css("display", "block");
        setTimeout(function() {
          $("#alertaFechaEntrega").css("display", "none");
        }, 2000);
      return;
    }

    if ( new Date(fechaGeneracion).getTime() > new Date(fechaEntrega).getTime()) {
      $("#txtFechaEntrega")[0].reportValidity();
      $("#txtFechaEntrega")[0].setCustomValidity(
        'La fecha de entrega no puede ser menor a la fecha de generación.');
      return;
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
        url: 'functions/ordenpedidoSubmitModificar.php',
        data: $('#form-cotizacion').serialize(),
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
                        $(location).attr('href', 'detalleOrdenPedido.php?id=' + id_orden_pedido);
                      } else if (
                        //Read more about handling dismissals below
                        result.dismiss === Swal.DismissReason.cancel
                      ) {
                      }
                    });

                    setTimeout(function () {
                      $(location).attr('href', 'detalleOrdenPedido.php?id=' + id_orden_pedido);
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

  var selectVendedor = new SlimSelect({
    select: '#chosenVendedor',
    deselectLabel: '<span class="">✖</span>'
  });

  </script>
  <script>
  var ruta = "../";
  </script>

</body>

</html>