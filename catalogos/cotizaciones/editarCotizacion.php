<?php
session_start();

$jwt_ruta = "../../";
require_once '../jwt.php';
require_once 'functions/contarNdecimales.php';

if (isset($_SESSION["Usuario"])) {
  require_once '../../include/db-conn.php';

  if (isset($_POST['idCotizacionU'])) {
    $id = $_POST['idCotizacionU'];
    $stmt = $conn->prepare('SELECT c.PKCotizacion, 
                                   c.id_cotizacion_empresa,
                                   c.ImporteTotal, 
                                   c.Subtotal, 
                                   c.FechaIngreso,
                                   c.FechaVencimiento, 
                                   c.FKCliente, 
                                   c.NotaCliente,
                                   c.NotaInterna, 
                                   cl.razon_social, 
                                   c.FKSucursal, 
                                   c.estatus_cotizacion_id, 
                                   c.empleado_id,
                                   ifnull(c.condicion_Pago,0) as condicionPago,
                                   ifnull(c.direccion_entrega_id,0) as direccion_entrega_id,
                                   IFNULL(md.CLAVE,0) as moneda,
                                    md.PKMoneda
                            FROM cotizacion as c
                                  LEFT JOIN clientes as cl ON cl.PKCliente = c.FKCliente
                                  LEFT JOIN usuarios AS u ON c.FKUsuarioCreacion = u.id
                                  left join monedas md on md.PKMoneda = c.FkMoneda_id
                            WHERE c.PKCotizacion = :id');

    $stmt->execute(array(':id' => $id));
    $row = $stmt->fetch();
    $Referencia = $row['PKCotizacion'];
    $ReferenciaPorEmpresa = $row['id_cotizacion_empresa'];
    $Subtotal = $row['Subtotal'];
    $ImporteTotal = $row['ImporteTotal'];
    $nDecimals = contarnDecimales($ImporteTotal);
    $FechaGeneracion = $row['FechaIngreso'];
    $FechaGeneracionF = date('Y-m-d\TH:i:s', strtotime($FechaGeneracion));
    $FechaVencimientoOr = $row['FechaVencimiento'];
    $IDCliente = $row['FKCliente'];
    $NotasClientes = $row['NotaCliente'];
    $NotasInternas = $row['NotaInterna'];
    $RazonSocial = $row['razon_social'];
    $idSucursal = $row['FKSucursal'];
    $empleado_id = $row['empleado_id'];
    $estatus = $row['estatus_cotizacion_id'];
    $id_direccion_envio = $row['direccion_entrega_id'];
    $condicion_pago = $row['condicionPago'];
    $moneda = $row['PKMoneda'];
  } else {
    header("location:/.");
  }
} else {
  header("location:../dashboard.php");
}

if ($estatus != 5) {
  header("location:detalleCotizacion.php?id=" . $id);
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
  <title>Timlid | Editar Cotización</title>

  <!-- ESTILOS -->
  <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../../css/bootstrap-clockpicker.min.css" rel="stylesheet" type="text/css">
  <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../css/lobibox.min.css" rel="stylesheet">
  <link href="../../css/slimselect.min.css" rel="stylesheet">
  <link href="../../css/styles.css" rel="stylesheet">
  <!-- JS -->
  <script src="../../vendor/jquery/jquery.min.js"></script>
  <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../../js/slimselect.min.js"></script>
  <script src="../../js/sweet/sweetalert2.js"></script>
  <script src="js/cotizaciones.js" charset="utf-8"></script>
  <link href="../../css/sweetalert2.css" rel="stylesheet">
  <script src="../../js/notificaciones_timlid.js" charset="utf-8"></script>
  <link href="../../css/notificaciones.css" rel="stylesheet">
  <style>
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
    $titulo = "Modificar cotización";
    $icono = '../../img/icons/ICONO COTIZACIONES-01.svg';
    $ruta = "../";
    $ruteEdit = $ruta . "central_notificaciones/";
    require_once '../menu3.php';
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
        $backRoute = "./detalleCotizacion.php?id=" . $id;
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
                              <label for="usr">Referencia:</label>
                              <input type="text" class="form-control alphaNumeric-only" name="txtReferencia" id="txtReferencia" maxlength="10" value="<?= sprintf("%011d", $ReferenciaPorEmpresa) ?>" readonly>
                            </div>
                            <div class="col-lg-2">
                              <label for="usr">Sucursal:</label>
                              <select name="cmbSucursal" id="chosenSucursal" disabled>
                                <?php
                                $stmt = $conn->prepare('SELECT id, sucursal FROM sucursales WHERE estatus = 1 AND empresa_id = :idempresa');
                                $stmt->bindValue("idempresa", $_SESSION['IDEmpresa']);
                                $stmt->execute();
                                ?>
                                <?php foreach ($stmt as $option) : ?>
                                  <option value="<?php echo $option['id']; ?>" <?php if ($option['id'] == $idSucursal) {
                                                                                  echo "selected";
                                                                                }
                                                                                ?>>
                                    <?php echo $option['sucursal']; ?></option>
                                <?php endforeach; ?>
                              </select>
                            </div>
                            <div class="col-lg-3">
                              <label for="cmbDireccionEntrega">Dirección de envío:*</label>
                              <select name="cmbDireccionEntrega" id="cmbDireccionEntrega">
                                <option value="0" disabled selected hidden>Seleccione una dirección de envío...</option>
                              </select>
                              <div class="invalid-feedback" id="invalid-direccionEntrega">El cliente debe tener una dirección de envío.</div>
                            </div>
                            <div class="col-lg-2">
                              <label for="cmbCondicionPago">Condición de pago:*</label>
                              <select name="cmbCondicionPago" id="cmbCondicionPago" required>
                                <option value="0" disabled selected hidden>Seleccione una condición...</option>
                              </select>
                              <div class="invalid-feedback" id="invalid-condicionPago">La cotización debe tener una condición de pago.</div>
                            </div>
                            <div class="col-lg-2">
                              <label for="cmbMoneda" style="float: rigth;">Moneda:*</label>
                              <select name="cmbMoneda" id="cmbMoneda" required>
                                <option value="0" disabled hidden>Seleccione una moneda...</option>
                                <option value="49" <?php if($moneda == 49){echo "selected";} ?> >EUR</option>
                                <option value="149" <?php if($moneda == 149){echo "selected";} ?>  >USD</option>
                                <option value="100" <?php if($moneda == 100){echo "selected";} ?> >MXN</option>
                              </select>
                              <div class="invalid-feedback" id="invalid-moneda">El cliente debe tener una moneda.</div>
                            </div>
                          </div>
                          <br>
                          <div class="row">
                            <div class="col-lg-3">
                              <label for="usr">Cliente:</label>
                              <select name="cmbCliente" id="chosen" required disabled>
                                <option value="">Elegir opción</option>
                                <?php
                                $stmt = $conn->prepare('SELECT PKCliente, NombreComercial FROM clientes WHERE estatus = 1 AND empresa_id = :idempresa');
                                $stmt->bindValue("idempresa", $_SESSION['IDEmpresa']);
                                $stmt->execute();
                                ?>
                                <?php foreach ($stmt as $option) : ?>
                                  <option value="<?php echo $option['PKCliente']; ?>" <?php if ($option['PKCliente'] == $IDCliente) {
                                                                                        echo "selected";
                                                                                      }
                                                                                      ?>><?php echo $option['NombreComercial']; ?></option>
                                <?php endforeach; ?>
                              </select>
                            </div>
                            <div class="col-lg-3">
                              <label for="usr">Razón social:</label>
                              <div id="cmbRazon">
                                <?php
                                echo $RazonSocial;
                                ?>
                              </div>
                            </div>
                            <div class="col-lg-2">
                              <label for="cmbVendedor">Vendedor:*</label>
                              <select name="cmbVendedor" id="cmbVendedor" required>
                                <?php
                                $PKEmpresa = $_SESSION["IDEmpresa"];

                                $query = sprintf('SELECT e.PKEmpleado as PKVendedor, 
                                                     concat(e.Nombres," ",e.PrimerApellido," ",e.SegundoApellido) as Nombre 
                                                from empleados e
                                                  inner join relacion_tipo_empleado rte on e.PKEmpleado = rte.empleado_id
                                                  where rte.tipo_empleado_id = "1" and e.empresa_id = ? and (e.estatus = 1)
                                                  order by concat(e.Nombres," ",e.PrimerApellido," ",e.SegundoApellido) asc');
                                $stmt = $conn->prepare($query);
                                $stmt->execute(array($PKEmpresa));
                                $array = $stmt->fetchAll();

                                echo '<option value="0">Elegir opción</option>';

                                foreach ($array as $a) {
                                  echo "<option value='" . $a['PKVendedor'] . "' ";

                                  if ($a['PKVendedor'] == $empleado_id) {
                                    echo "selected ";
                                  }

                                  echo ">" . $a['Nombre'] . "</option>";
                                }
                                ?>
                              </select>
                            </div>

                            <div class="col-lg-2">
                              <label for="usr">Fecha de generación:</label>
                              <input type="datetime-local" class="form-control" name="txtFechaGeneracion" id="txtFechaGeneracion" value="<?= $FechaGeneracionF ?>" readonly required>
                            </div>
                            <div class="col-lg-2">
                              <?php
                              $stmt = $conn->prepare('SELECT cantidad FROM parametros  WHERE descripcion = "Dias_Vencimiento" AND empresa_id = :empresa_id');
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
                              $dias_vencimientoF = $dias_vencimiento + 10;

                              $fechaVencimientoF = date('Y-m-d', strtotime($FechaGeneracion . ' + ' . $dias_vencimientoF . ' days'));
                              $FechaGeneracionCampo = date('Y-m-d', strtotime($FechaGeneracion));
                              ?>
                              <label for="usr">Fecha de vencimiento:</label>
                              <input type="date" class="form-control" name="txtFechaVencimiento" id="txtFechaVencimiento" value="<?= $FechaVencimientoOr ?>" required min="<?= $FechaGeneracionCampo ?>">
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
                                    <option value="">Elegir opción</option>
                                    <?php
                                    $stmt = $conn->prepare('SELECT p.PKProducto,p.Nombre,p.ClaveInterna FROM productos as p INNER JOIN operaciones_producto as op ON p.PKProducto = op.FKProducto WHERE op.Venta = 1 AND p.empresa_id = :idempresa AND p.estatus = 1 ORDER BY p.Nombre ASC');
                                    $stmt->bindValue("idempresa", $_SESSION['IDEmpresa']);
                                    $stmt->execute();
                                    ?>
                                    <?php foreach ($stmt as $option) : ?>
                                      <option value="<?php echo $option['PKProducto']; ?>">
                                        <?php echo $option['ClaveInterna'] . " " . $option['Nombre']; ?></option>
                                    <?php endforeach; ?>
                                  </select>
                                  <button style="width: 45%;font-size: 14px;" type="button" class="btn-custom btn-custom--border-blue mt-2" id="mostrar_todos">Mostrar todos los productos</button>
                                </div>
                              </div>
                              <span style="color: #d9534f;display: none;position: absolute;" id="alertaProducto">Ingresa un producto</span>
                            </div>
                            <div class="col-lg-4">
                              <div>
                                <div class="row" id="divCantidad">
                                  <div class="col-lg-6">
                                    <label for="usr">Cantidad<span id="actualizarUnidad"></span>:</label>
                                    <input type='text' value='' name="txtPiezas" id="txtPiezas" class='form-control numeric-only'>
                                    <span style="color: #d9534f;display: none;position: absolute;" id="alertaPiezas" onkeydown="insertProduct(event)">Ingresa la cantidad de piezas</span>
                                  </div>
                                  <div class="col-lg-6">
                                    <label for="usr">Precio:</label>
                                    <input type='text' value='' name="txtPrecio" id="txtPrecio" class='form-control txtPrecio numericDecimal-only' maxlength="14">
                                    <span style="color: #d9534f;display: none;position: absolute;" id="alertaPrecio">Ingresa un precio válido</span>
                                  </div>
                                </div>

                              </div>
                            </div>
                            <div class="col-lg-2 d-flex justify-content-start align-items-center">
                              <input type="hidden" name="txtImpuestos" id="txtImpuestos" value="" />
                              <input type="hidden" name="txtClaveUnidad" id="txtClaveUnidad" value="" />
                              <input type="hidden" name="NuevoProducto" id="NuevoProducto" value="" />
                              <input type="hidden" name="TipoProducto" id="TipoProducto" value="" />
                              <button type="button" class="btn-custom btn-custom--border-blue" id="agregarProducto">Agregar</button>
                            </div>
                          </div>
                        </div>


                        <br><br>
                        <div class="table-responsive redondear">
                          <table class="table table-sm" id="cotizacion">
                            <thead class="header-color">
                              <tr>
                                <th>Clave/Producto</th>
                                <th style="width: 10%;">Cantidad</th>
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
                              $cuentaIVAexento = 0;
                              $cuentaISRExento = 0;
                              $cuentaIEPSExento = 0;
                              $stmt = $conn->prepare('SELECT dc.FKProducto, 
                                                             dc.Cantidad, 
                                                             dc.Precio as Precio,
                                                             p.ClaveInterna, 
                                                             p.Nombre, 
                                                             csu.Descripcion, 
                                                             p.FKTipoProducto,
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

                              if ($rowp[0]['FKTipoProducto'] == 5) {
                                $tipoProducto = 2;
                              } else {
                                $tipoProducto = 1;
                              }


                              foreach ($rowp as $rp) {

                                $stmt = $conn->prepare('SELECT i.PKImpuesto, i.Nombre, i.FKTipoImpuesto as TipoImpuesto, i.FKTipoImporte as TipoImporte, i.Operacion, di.FKProducto, di.Tasa FROM detalleimpuesto  as di INNER JOIN impuesto as i ON i.PKImpuesto = di.FKImpuesto WHERE di.FKCotizacion= :id AND FKProducto = :idProducto');
                                $stmt->execute(array(':id' => $id, ':idProducto' => $rp['FKProducto']));
                                $rowi = $stmt->fetchAll();

                                if ($rp['Descripcion'] == "") {
                                  $ClaveUnidad = "Sin unidad";
                                } else {
                                  $ClaveUnidad = $rp['Descripcion'];
                                }
                                //print_r($rowi);

                                $Existencia = $rp['existencia'];

                                $totalProducto = $rp['Cantidad'] * $rp['Precio'];
                                echo "<tr id='idProducto_" . $rp['FKProducto'] . "' class='text-center'>" .
                                  "<td id='nombreproducto_" . $rp['FKProducto'] . "'>" . $rp['ClaveInterna'] . " - " . $rp['Nombre'] . "</td>" .
                                  "<td id='piezas_" . $rp['FKProducto'] . "'><input type='number' name='inp_piezas[]' id='piezasUnic_" . $rp['FKProducto'] . "' value='" . $rp['Cantidad'] . "' class='form-control modificarnumero textTable' min='1'></td>" .
                                  "<input type='hidden' id='piezaAnt_" . $rp['FKProducto'] . "' value='" . $rp['Cantidad'] . "' />" .
                                  "<input type='hidden' name='inp_productos[]' value='" . $rp['FKProducto'] . "' />" .
                                  "<td>" . $ClaveUnidad . "</td>" .
                                  //"<td id='precio_" . $rp['FKProducto'] . "'>" . $rp['Precio'] . "</td>" .
                                  "<td id='precio_" . $rp['FKProducto'] . "'> <input  type='number' name='inp_precio[]' id='precioUnic_" . $rp['FKProducto'] .
                                  "' value='" . $rp['Precio'] . "' class='decimales modificarprecio form-control textTable border-0'>" . "</td>" .
                                  "<input type='hidden' id='precionAnt_" . $rp['FKProducto'] . "' value='" . $rp['Precio'] . "' />" .
                                  //"<input type='hidden' name='inp_precio[]' value='" . $rp['Precio'] . "' />" .
                                  "<td>" .
                                  "<span class='impuestos_" . $rp['FKProducto'] . "'>";

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

                                  //sumar cantidad de impuestos exentos
                                  if ($ri['PKImpuesto'] == 5) {
                                    $cuentaIVAexento++;
                                  }
                                  if ($ri['PKImpuesto'] == 13) {
                                    $cuentaISRExento++;
                                  }
                                  if ($ri['PKImpuesto'] == 16) {
                                    $cuentaIEPSExento++;
                                  }

                                  //print_r($impuestos);

                                  //echo "id impuesto :".$ri['PKImpuesto']."//";
                                  $found_key = array_search($ri['PKImpuesto'], array_column($impuestos, 0));
                                  /*print_r($found_key);
        echo "fk ".$found_key[0];*/
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

                                  echo "<span id='" . $Identificador . "' >" . $ri['Nombre'] . " " . $ri['Tasa'] . $tas . " <input name='valImp_" . $ri['Tasa'] . "' type='hidden' id='impAgregado_" . $ri['FKProducto'] . "_" . $ri['PKImpuesto'] . "' value='" . $ri['Tasa'] . "' /><input type='hidden' id='OperacionUnica_" . $ri['FKProducto'] . "_" . $ri['PKImpuesto'] . "' value='" . $ri['Operacion'] . "' /><input type='hidden' id='ImpuestoUnico_" . $ri['FKProducto'] . "_" . $ri['PKImpuesto'] . "' value='" . $ri['Nombre'] . "' /></span>";
                                  if ($contImpuestos != $numImpuestos) {
                                    echo "<br>";
                                  }

                                  $contImpuestos++;
                                }
                                //Cuenta los decimales de cada Importe del producto.
                                $nDecimalsT = contarnDecimales($totalProducto);
                                echo "</span>" .
                                  "</td>" .
                                  "<td class='decimales' id='totalproducto_" . $rp['FKProducto'] . "'>" . number_format($totalProducto, $nDecimalsT) . "</td>" .
                                  "<td>".$Existencia."</td>".
                                  "<input type='hidden' name='inp_total_producto[]' value='" . number_format($totalProducto, $nDecimalsT) . "' />" .
                                  "<td><button type='button' class='btn eliminarProductos' id='" . $rp['FKProducto'] . "'><img src='../../img/timdesk/delete.svg' width='20px' /></button></td>" .
                                  "</tr>";
                              }
                              ?>
                            </tbody>
                            <tr><td colspan="8"><br></td></tr>
                            <tr>
                              <th colspan="5"></th>
                              <th style="color: var(--color-primario)">Subtotal:</th>
                              <td colspan="2" style="color: var(--color-primario)"><span id="Subtotal">$ <?= number_format($Subtotal, 2) ?></span></td>
                            </tr>
                            <tr>
                              <th colspan="5"></th>
                              <th style="color: var(--color-primario)">Impuestos:</th>
                              <th colspan="2"></th>
                            </tr>
                            <tbody id="lstimpuestos">
                              <?php
                              foreach ($impuestos as $imp) {
                                $IniImpuesto = explode(" ", $imp[2]);
                                echo "<tr id='" . $IniImpuesto[0] . "_" . $imp[0] . "'>" .
                                  "<th colspan='5'></th>" .
                                  "<th style='text-align:right; color: var(--color-primario)'>" . $imp[2] . "</th>" .
                                  "<td colspan='2' style='color: var(--color-primario)'>$ <span id='Impuesto_" . $imp[0] . "' name='" . $imp[3] . "_" . $imp[4] . "' class='ImpuestoTot'>" . number_format($imp[1], 2) . "</span></td>" .
                                  "</tr>";
                              }
                              ?>
                            </tbody>
                            <tr class="total">
                              <th colspan="5" class="redondearAbajoIzq"></th>
                              <th style="color: var(--color-primario)">Total:</th>
                              <td colspan="2" style="color: var(--color-primario)"><b>$ <span id="Total"><?= number_format($ImporteTotal, $nDecimals) ?></span></b></td>
                            </tr>
                          </table>
                        </div>

                        <div class="row">
                          <div class="col-lg-12" style="color:#d9534f;display: none;text-align: center;" id="mostrarMensaje">
                            <h2>Ingresa un producto al menos.</h2>
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-lg-6">
                            <label for="usr">Notas visibles al cliente:</label>
                            <textarea class="form-control" cols="10" rows="3" name="NotasClientes" id="NotasClientes" placeholder="Aquí puedes colocar la descripción de tu cotización o datos importantes solo para uso interno" maxlength="500"><?= $NotasClientes ?></textarea>
                          </div>
                          <div class="col-lg-6">
                            <label for="usr">Notas internas:</label>
                            <textarea class="form-control" cols="10" rows="3" name="NotasInternas" id="NotasInternas" placeholder="Aquí puedes colocar la descripción de tu cotización o datos importantes solo para uso interno" maxlength="500"><?= $NotasInternas ?></textarea>
                          </div>
                        </div>
                        <br>
                        <input type="hidden" name="idCotizacion" id="idCotizacion" value="<?= $id ?>">
                        <input type="hidden" name="TipoProductoGeneral" id="TipoProductoGeneral" value="">
                        <input type="hidden" name="csr_token_7ALF1" id="csr_token_7ALF1" value="<?= $token ?>">
                        <button type="button" class="btn-custom btn-custom--blue float-right" name="btnModificar" id="btnModificar">Modificar</button>
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

  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">¿En serio quieres salir?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">x</span>
          </button>
        </div>
        <div class="modal-body">Selecciona "Salir" Para cerrar tu sesión</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
          <a class="btn btn-primary" href="../../logout.php">Salir</a>
        </div>
      </div>
    </div>
  </div>

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
    var cuenta = <?= $numero_productos ?>;
    var idProductoG;
    var cuentaIVAexento = <?= $cuentaIVAexento ?>,
      cuentaISRExento = <?= $cuentaISRExento ?>,
      cuentaIEPSExento = <?= $cuentaIEPSExento ?>;
    var cantidadAnterior = 0;
    var idCotizacion = <?= $id ?>;
    let gVendedor = 0;
    let gDireccionEnvio = <?= $id_direccion_envio; ?>;
    let gCondicionPago = <?= $condicion_pago; ?>;
    let gtipoProducto = <?= $tipoProducto ?>; /// 1 para todos los productos(todos menos servicios), 2 solo para servicios
    let gIDCliente = <?= $IDCliente ?>;

    $("#chosen")[0].reportValidity();
    $("#chosen")[0].setCustomValidity('Completa este campo.');

    $(document).ready(function () {
      cargarCMBDireccionesEnvio(gDireccionEnvio, "cmbDireccionEntrega",gIDCliente); 
      cargarCMBCondicionPago(gCondicionPago, "cmbCondicionPago"); 
    });

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

    function cargarCMBDireccionesEnvio(data, input, cliente) {
      var html = "";
      var selected;

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

          $("#" + input + "").html(html);
          $("#" + input + "").val(data);
        },
        error: function (error) {
          console.log(error);
        },
      });
        
    }

    $("#chosenProducto").change(function() {
      var idProducto = $("#chosenProducto").val();
      var idCliente = $("#chosen").val();

      //$("#txtPrecio").prop("readonly", true);

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

          if (idProducto.trim() != "") {
            $("#actualizarUnidad").html(" - " + datos.ClaveUnidad);
          }

          $("#TipoProducto").val(datos.tipoProducto);
        }
      });
    });


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
      let idCliente = $("#chosen").val();
      let UnidadMedida = $("#txtClaveUnidad").val();
      let tipoProducto = $("#TipoProducto").val();
      let PKsucursal = $("#chosenSucursal").val();
      let existencia = 0;
      let arrayImpuestos = [];
      let indexImpuestos = 0;
      let ctaIVA = 0, ctaIEPSFijo = 0, ctaIEPSTasa = 0;

      var impuestoXProductoIVA, impuestoCantidadIVA, idImpuestoIVA, cantidadAdicionalIVA = 0;



      ///Validar parte decimal y parte entera.
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

      if (gtipoProducto == 0) {

        if (tipoProducto == 5) {
          gtipoProducto = 2;
        } else {
          gtipoProducto = 1;
        }
      }

      /* modificación: se quitó la validación que evita ingresar productos con servicios 
      if (gtipoProducto == 1) {

        if (tipoProducto == 5) {
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

      if (gtipoProducto == 2) {

        if (tipoProducto != 5) {
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
            TotalProducto = (Piezas * Precio).toFixed(6);

            PrecioF = $.number(Precio, 4, '.', ',');
            TotalProductoF = $.number(TotalProducto, 6, '.', ',');

            var impuestosOld = $(".impuestos_" + idProducto).html();

            var impuestoXProducto, impuestoCantidad, arrayImp, TipoTasa;
            var idImpuestoOld, totImpIndividual, impuestoTotalant, impuestoTotNuevo, impuestoTotNuevoF;
/* 
            $('.impuestos_' + idProducto + ' > span').each(function(index, span) {
              impuestoXProducto = $(this).attr("id");
              arrayImp = impuestoXProducto.split("_");
              idImpuestoOld = arrayImp[3];
              TipoTasa = arrayImp[2];
              impuestoCantidad = parseFloat($("#impAgregado_" + idProducto + "_" + idImpuestoOld).val());

              if (TipoTasa == 1) {
                totImpIndividual = parseFloat((PiezasImp * Precio) * (impuestoCantidad / 100));
              }
              if (TipoTasa == 2) {
                totImpIndividual = parseFloat(PiezasImp * impuestoCantidad);
              }
              if (TipoTasa == 3) {
                totImpIndividual = 0.00;
              }

              impuestoTotalant = numeral($("#Impuesto_" + idImpuestoOld).html());
              impuestoTotNuevo = totImpIndividual + impuestoTotalant.value();
              impuestoTotNuevoF = $.number(impuestoTotNuevo, 2, '.', ',');
              $("#Impuesto_" + idImpuestoOld).html(impuestoTotNuevoF);

            });
            ///Boton agregar producto////
            ////El proucto ya esta en la tabla////
            $('#idProducto_' + idProducto).empty();
            nuevo_elemento = "<td id='nombreproducto_" + idProducto + "'>" + Producto + "</td>" +
              "<td id='piezas_" + idProducto + "'><input type='number' id='piezasUnic_" + idProducto +
              "' name='inp_piezas[]' value='" + Piezas + "' class='form-control modificarnumero numeros-solo' min='1' >" + "</td>" +
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
              "<td>" +existencia + "</td>"+
              "<input type='hidden' name='inp_total_producto[]' value='" + TotalProducto + "' />" +
              "<td><button type='button' class='btn eliminarProductos' id='" + idProducto +
              "'><img src='../../img/timdesk/delete.svg' width='20px' /></button></td>";
            $('#idProducto_' + idProducto).append(nuevo_elemento); */

            var oldPieza = $("#piezasUnic_" + idProducto).val();
            var newPiezas = Piezas;//Piezas + parseInt(oldPieza);
          
            
            $("#precioUnic_" + idProducto).val(Precio);
            $("#precioUnic_" + idProducto ).trigger( "change" );
            $("#piezasUnic_" + idProducto).val(newPiezas);
            $("#piezasUnic_" + idProducto ).trigger( "change" );
            console.log("Confirmada Edicion");

          } else {
            //cuando se ingresa un nuevo producto
            TotalProducto = (Piezas * Precio).toFixed(6);
            descuento = "";

            PrecioF = $.number(Precio, nDecimales, '.', '');
            TotalProductoF = $.number(TotalProducto, nDecimales, '.', ',');
            ///Boton agregar producto////
            ////El proucto NO esta en la tabla////
            nuevo_elemento = "<tr id='idProducto_" + idProducto + "' class='text-center'>" +
              "<td id='nombreproducto_" + idProducto + "'>" + Producto + "</td>" +
              "<td id='piezas_" + idProducto + "'><input type='number' name='inp_piezas[]' id='piezasUnic_" + idProducto +
              "' value='" + Piezas + "' class='form-control modificarnumero numeros-solo' min='1' >" + "</td>" +
              "<input type='hidden' id='piezaAnt_" + idProducto + "' value='" + Piezas + "' />" +
              "<input type='hidden' name='inp_productos[]' value='" + idProducto + "' />" +
              "<td>" + UnidadMedida + "</td>" +
              "<td id='precio_" + idProducto + "'> <input type='number' name='inp_precio[]' id='precioUnic_" + idProducto +
              "' value='" + Precio + "' class='decimales modificarprecio form-control textTable border-0' min='0' >" + "</td>" +
              "<input type='hidden' id='precionAnt_" + idProducto + "' value='" + PrecioF + "' />" +
              //"<input type='hidden' name='inp_precio[]' value='" + Precio + "' />" +
              "<td>" +
              ImpuestosCompleto +
              "</td>";

            nuevo_elemento += "<td class='decimales' id='totalproducto_" + idProducto + "'>" + TotalProductoF + "</td>" +
              "<input type='hidden' name='inp_total_producto[]' value='" + TotalProducto + "' />" +
              "<td>" +existencia + "</td>"+
              "<td><button type='button' class='btn eliminarProductos' id='" + idProducto +
              "'><img src='../../img/timdesk/delete.svg' width='20px' /></button></td>" +
              "</tr>";

            $('#lstProductos').append(nuevo_elemento);

            $('.impuestos_' + idProducto + ' > span').each(function(index, span) {

              impuestoXProducto = $(this).attr("id");
              arrayImp = impuestoXProducto.split("_");
              idImpuestoOld = arrayImp[3];

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



              TipoTasa = arrayImp[2];
              impuestoCantidad = parseFloat($("#impAgregado_" + idProducto + "_" + idImpuestoOld).val());

              if (TipoTasa == 1)
                totImpIndividual = parseFloat((PiezasImp * Precio) * (impuestoCantidad / 100));
              if (TipoTasa == 2 || TipoTasa == 3)
                totImpIndividual = 0.00;

              if (TipoTasa == 1) {
                var TotalImpuesto = (parseFloat(TotalProducto) + parseFloat(cantidadAdicionalIVA)) * (impuestoCantidad / 100);
              }
              if (TipoTasa == 2) {
                var TotalImpuesto = impuestoCantidad * Piezas;
              }
              if (TipoTasa == 3) {
                var TotalImpuesto = 0.00;

                if (idImpuestoOld == 5) {
                  cuentaIVAexento++;
                }
                if (idImpuestoOld == 13) {
                  cuentaISRExento++;
                }
                if (idImpuestoOld == 16) {
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
            var SubtotalF = $.number(Subtotal, 4, '.', ',');
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
          //AGREGAR EL PRECIO DEL PRODUCTO AL COSTO GENERAL
          $.ajax({
            type: 'POST',
            url: 'functions/agregarNuevoProducto.php',
            data: {
              idProducto: idProducto,
              idCliente: idCliente,
              costo: Precio
            },
            success: function(data) {

            },
            error: function() {

            }
          });

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


    });


    $("#txtPiezas,#txtPrecio").on('keydown', function(e) {
      if (e.keyCode == 13) {
        $("#agregarProducto").trigger( "click");
      /* 
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
        let idCliente = $("#chosen").val();
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

        if (gtipoProducto == 0) {

          if (tipoProducto == 5) {
            gtipoProducto = 2;
          } else {
            gtipoProducto = 1;
          }
        }

        if (gtipoProducto == 1) {

          if (tipoProducto == 5) {
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

        if (gtipoProducto == 2) {

          if (tipoProducto != 5) {
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

              $('.impuestos_' + idProducto + ' > span').each(function(index, span) {
                impuestoXProducto = $(this).attr("id");
                arrayImp = impuestoXProducto.split("_");
                idImpuestoOld = arrayImp[3];
                TipoTasa = arrayImp[2];
                impuestoCantidad = parseFloat($("#impAgregado_" + idProducto + "_" + idImpuestoOld).val());

                if (TipoTasa == 1) {
                  totImpIndividual = parseFloat((PiezasImp * Precio) * (impuestoCantidad / 100));
                }
                if (TipoTasa == 2) {
                  totImpIndividual = parseFloat(PiezasImp * impuestoCantidad);
                }
                if (TipoTasa == 3) {
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
                "' name='inp_piezas[]' value='" + Piezas + "' class='form-control modificarnumero numeros-solo' min='1' >" + "</td>" +
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
                "<td>" + existencia + "</td>"+
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
                "' value='" + Piezas + "' class='form-control modificarnumero numeros-solo' min='1' >" + "</td>" +
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
                "<input type='hidden' name='inp_total_producto[]' value='" + TotalProducto + "' />" +
                "<td>" + existencia + "</td>"+
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

                  if (idImpuestoOld == 5) {
                    cuentaIVAexento++;
                  }
                  if (idImpuestoOld == 13) {
                    cuentaISRExento++;
                  }
                  if (idImpuestoOld == 16) {
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
            $.ajax({
              type: 'POST',
              url: 'functions/agregarNuevoProducto.php',
              data: {
                idProducto: idProducto,
                idCliente: idCliente,
                costo: Precio
              },
              success: function(data) {

              },
              error: function() {

              }
            });

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

        if (TipoTasa == 1) {
          totImpIndividual = parseFloat((TotalProducto + cantidadAdicionalIVA) * (impuestoCantidad / 100));
        }
        if (TipoTasa == 2) {
          totImpIndividual = impuestoCantidad * totalPiezas;
        }
        if (TipoTasa == 3) {
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

          if (idImpuestoOld == 5) {
            cuentaIVAexento--;

            if (cuentaIVAexento == 0) {
              $("#" + Impuesto + "_" + idImpuestoOld).remove();
            }

          }

          if (idImpuestoOld == 13) {
            cuentaISRExento--;

            if (cuentaISRExento == 0) {
              $("#" + Impuesto + "_" + idImpuestoOld).remove();
            }
          }

          if (idImpuestoOld == 16) {
            cuentaIEPSExento--;

            if (cuentaIEPSExento == 0) {
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
      var TotalF = $.number(Total, 2, '.', ',');
      $('#Total').empty();
      $('#Total').append(TotalF);

      $('#idProducto_' + idProducto).remove();
      cuenta--;

      /*if (cuenta == 0){

        gtipoProducto = 0;
      }*/

      $("#catalogoImpuestos").css("display", "none");
    });

    $(document).on("keyup", ".modificarnumero", function() {
      this.value = this.value.replace(/[^0-9]/g, '');
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

      var TotalProducto = (cantidad * PrecioProducto.value()).toFixed(6);

      var TotalProductoAnt = numeral($("#totalproducto_" + idProducto).html());
      var TotalProductoFinFormat = parseFloat(TotalProducto) + parseFloat(TotalProductoAnt.value());
      var TotalProductoFin = $.number(TotalProductoFinFormat, nDecimales, '.', ',');

      $('#totalproducto_' + idProducto).html(TotalProductoFin);


      var SubtotalNum = numeral($("#Subtotal").html());
      Subtotal = SubtotalNum.value() + parseFloat(TotalProducto);
      var SubtotalF = $.number(Subtotal, nDecimales, '.', ',');
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
      var TotalF = $.number(Total, 2, '.', ',');
      $('#Total').empty();
      $('#Total').append(TotalF);


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
            if(aux[1].length > 6){
              nDecimales = 6;
            }
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

      
////
//// Si cambia el precio en la tabla.
////
/* $(document).on("keyup", ".modificarprecio", function() {
    this.value = this.value.replace(/^\d*\.?\d*$/, '');
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
    ///Si el umero de ecimales es mayor al anterior se cambia.
    nDecimales = nDecimales < contarDecimales(this.value) ? contarDecimales(this.value) : nDecimales;
    precioNuevo = parseFloat(this.value);
  }

  

  ///Verifica 6 decimales 12 enteros
  let aux = precioNuevo.toString();
  precioNuevo = validarMoneda(aux);

  this.value=precioNuevo;

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

  var TotalProducto = (precio * CantidadProducto.value()).toFixed(6);

  var TotalProductoAnt = numeral($("#totalproducto_" + idProducto).html());
  var TotalProductoFinFormat = parseFloat(TotalProducto) + parseFloat(TotalProductoAnt.value());
  var TotalProductoFin = $.number(TotalProductoFinFormat, 6, '.', ',');

  $('#totalproducto_' + idProducto).html(TotalProductoFin);

   //TotalProducto = numeral($("#totalproducto_" + idProducto).val());

  var SubtotalNum = numeral($("#Subtotal").html());
  Subtotal = SubtotalNum.value() + parseFloat(TotalProducto);
  var SubtotalF = $.number(Subtotal, 4, '.', ',');
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


    $("#btnModificar").click(function() {

      selectCliente.enable();
      var cliente = parseInt($("#chosen").val());
      selectCliente.disable();
      var referencia = $("#txtReferencia").val();
      var razon_social = parseInt($("#cmbRazon").val());
      var fechaVencimiento = $("#txtFechaVencimiento").val();
      var fechaVencimientoInicial = "<?php echo $fechaVencimientoF; ?>";
      gVendedor = $("#cmbVendedor").val();
      let notasClientes = $("#NotasClientes").val().trim();
      let notasInternas = $("#NotasInternas").val().trim();
      gDireccionEnvio = $("#cmbDireccionEntrega").val().trim();
      gCondicionPago = $("#cmbCondicionPago").val().trim();

      if (cliente < 1) {
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

      /*if(razon_social < 1){
        $("#cmbRazon")[0].reportValidity();
        $("#cmbRazon")[0].setCustomValidity('Completa este campo.');
        return;
      }*/

      ///Valdia que la fecha de vencimiento no sea mayor a la del parametro vencimientos de la empresa
      /* if ((new Date(fechaVencimiento).getTime() > new Date(fechaVencimientoInicial).getTime())) {
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/notificacion_error.svg",
          msg: "¡La fecha de vencimiento es mayor del rango de días permitido!",
        });
        return;
      } */

      if (referencia.trim() == "") {
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/notificacion_error.svg",
          msg: "¡La referencia no es valida!",
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

      if (notasClientes.length > 500) {
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

      if (notasInternas.length > 500) {
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

      $("#btnModificar").prop("disabled", true);

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

      //return;
      if (cuenta > 0) {
        $.ajax({
          type: 'post',
          url: 'functions/cotizacionSubmitModificar.php',
          data: $('#form-cotizacion').serialize(),
          success: function(data) {
            console.log(data);
            var datos = JSON.parse(data);

            if (datos.estatus == "error-notacliente") {
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
              $("#btnModificar").prop("disabled", false);
            } else if (datos.estatus == "error-notainterna") {
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
              $("#btnModificar").prop("disabled", false);
            } else if (datos.estatus == "error-general") {
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

              $("#btnModificar").prop("disabled", false);
            } else if (datos.estatus == "exito") {

              Swal.fire({
                icon: "success",
                title: "Cotización modificada",
                html: "¿Deseas enviarle un correo electrónico al cliente con la modificación de la cotización?",
                type: "question",
                showConfirmButton: true,
                showCancelButton: true,
                confirmButtonText: 'Si <i class="far fa-arrow-alt-circle-right"></i>',
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
                    "functions/modal_envio.php?idCotizacion=" + datos.idcotizacion + "&idCliente=" + cliente + "&estatus=1",
                    function() {
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
                  $(location).attr('href', 'detalleCotizacion.php?id=' + idCotizacion);
                }
              });

            }
          }
        });
      } else {

        $("#btnModificar").prop("disabled", false);

        $("#mostrarMensaje").css("display", "block");
        setTimeout(function() {
          $("#mostrarMensaje").css("display", "none");
        }, 2000);
      }
    });

    function isEmail(email) {
      var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
      return regex.test(email);
    }

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

    $(document).on("click", "#btnEnviar",()=>{
        var id = $("#txtId").val();
        var emailOrigen = $("#txtOrigen").val();
        var emailDestino = destinatarios.selected();
        var valParam = JSON.stringify(emailDestino);
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
            console.log(data);
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

    var selectSucursal = new SlimSelect({
      select: '#chosenSucursal',
      deselectLabel: '<span class="">✖</span>'
    });

    cmbMoneda = new SlimSelect({
    select: '#cmbMoneda',
    deselectLabel: '<span class="">✖</span>',
  });

    var selectProductos = new SlimSelect({
      select: '#chosenProducto',
      deselectLabel: '<span class="">✖</span>'
    });

    var selectCliente = new SlimSelect({
      select: '#chosen',
      deselectLabel: '<span class="">✖</span>'
    });

    var selectVendedor = new SlimSelect({
      select: '#cmbVendedor',
      deselectLabel: '<span class="">✖</span>'
    });

    var selectDireccionEntrega = new SlimSelect({
      select: '#cmbDireccionEntrega',
      deselectLabel: '<span class="">✖</span>'
    });

    var selectCondicionPago = new SlimSelect({
      select: '#cmbCondicionPago',
      deselectLabel: '<span class="">✖</span>',
    });

    /* new Cleave('.txtPrecio', {
      numeral: true,
      numeralDecimalMark: '.',
      delimiter: ','
    }); */
  </script>
  <script>
    var ruta = "../";
  </script>
  <script>
    loadAlertsNoti('alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
    setInterval(loadAlertsNoti, 10000, 'alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
  </script>

</body>

</html>