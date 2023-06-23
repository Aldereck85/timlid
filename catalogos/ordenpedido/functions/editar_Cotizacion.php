<?php
session_start();
if (isset($_SESSION["Usuario"])) {
    require_once '../../../include/db-conn.php';

    if (isset($_POST['idCotizacionU'])) {
        $id = $_POST['idCotizacionU'];
        $stmt = $conn->prepare('SELECT * FROM cotizacion WHERE PKCotizacion= :id');
        $stmt->execute(array(':id' => $id));
        $row = $stmt->fetch();
        $Referencia = $row['PKCotizacion'];
        $Subtotal = $row['Subtotal'];
        $ImporteTotal = $row['ImporteTotal'];
        $FechaGeneracion = $row['FechaIngreso'];
        $FechaVencimiento = $row['FechaVencimiento'];
        $IDCliente = $row['FKCliente'];
        $NotasClientes = $row['NotaCliente'];
        $NotasInternas = $row['NotaInterna'];
        $DomicilioFIscal = $row['FKDomicilioFiscal'];
    } else {
        header("location:../");
    }
} else {
    header("location:../../dashboard.php");
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <link rel="icon" type="image/png" href="../../../img/header/bluTimlid.png">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Timlid | Editar Cotización</title>

  <!-- Custom fonts for this template-->
  <link href="../../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../../../css/bootstrap-clockpicker.min.css" rel="stylesheet" type="text/css">
  <link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="../../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../../css/styles.css" rel="stylesheet">

  <link href="../../../css/slimselect.min.css" rel="stylesheet">
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

  <!-- Bootstrap core JavaScript-->
  <script src="../../../vendor/jquery/jquery.min.js"></script>
  <script src="../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../../../js/slimselect.min.js"></script>
</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <?php
$titulo = "Cambiar";
$ruta = "../../";
$ruteEdit = $ruta . "central_notificaciones/";
require_once '../../menu3.php';
?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <?php
$rutatb = "../../";
require_once '../../topbar.php';
?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Cotización</h1>
          </div>


          <div class="row">

            <div class="col-lg-12">

              <!-- Basic Card Example -->
              <div class="card shadow mb-4">
                <div class="card-header">
                  Editar cotización
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-12">
                      <form action="" method="post" id="form-cotizacion">
                        <div class="form-group">
                          <div class="row">
                            <div class="col-lg-3">
                              <label for="usr">Clientes:</label>
                              <select name="cmbCliente" id="chosen" required disabled>
                                <option value="">Elegir opción</option>
                                <?php
$stmt = $conn->prepare('SELECT PKCliente, NombreComercial FROM clientes WHERE FKEstatus = 4');
$stmt->execute();
?>
                                <?php foreach ($stmt as $option): ?>
                                <option value="<?php echo $option['PKCliente']; ?>" <?php if ($option['PKCliente'] == $IDCliente) {
    echo "selected";
}
?>><?php echo $option['PKCliente'] . " - " . $option['NombreComercial']; ?></option>
                                <?php endforeach;?>
                              </select>
                            </div>
                            <div class="col-lg-3">
                              <label for="usr">Razón social:</label>
                              <select name="cmbRazon" id="cmbRazon" required>
                                <?php
$stmt = $conn->prepare("SELECT PKDomicilioFiscalCliente , Razon_Social FROM domicilio_fiscal_cliente WHERE FKCliente = :idCliente");
$stmt->bindValue(":idCliente", $IDCliente);
$stmt->execute();
$rowdomicilio = $stmt->fetchAll();

echo "<option value='0' >Escoge una razón social</option>";

foreach ($rowdomicilio as $rd) {
    echo '<option value="' . $rd['PKDomicilioFiscalCliente'] . '" ';

    if ($rd['PKDomicilioFiscalCliente'] == $DomicilioFIscal) {
        echo "selected";
    }

    echo '>' . $rd['Razon_Social'] . '</option>';
}
?>
                              </select>
                            </div>
                            <div class="col-lg-2">
                              <label for="usr">Referencia:</label>
                              <input type="text" class="form-control alphaNumeric-only" name="txtReferencia"
                                id="txtReferencia" maxlength="10" value="<?=$Referencia?>" readonly>
                            </div>
                            <div class="col-lg-2">
                              <label for="usr">Fecha de generación:</label>
                              <input type="date" class="form-control" name="txtFechaGeneracion" id="txtFechaGeneracion"
                                value="<?=$FechaGeneracion?>" readonly required>
                            </div>
                            <div class="col-lg-2">
                              <?php
$stmt = $conn->prepare('SELECT cantidad FROM parametros  WHERE descripcion = "Dias_Vencimiento"');
$stmt->execute();
$dv = $stmt->fetch();
$dias_vencimiento = $dv['cantidad'];

$fechaVencimiento = date('Y-m-d');
$fechaVencimientoF = date('Y-m-d', strtotime($fechaVencimiento . ' + ' . $dias_vencimiento . ' days'));
?>
                              <label for="usr">Fecha de vencimiento:</label>
                              <input type="date" class="form-control" name="txtFechaVencimiento"
                                id="txtFechaVencimiento" value="<?=$FechaVencimiento?>" required>
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
$stmt = $conn->prepare('SELECT p.PKProducto,p.Nombre,p.ClaveInterna FROM productos as p INNER JOIN tipos_productos as tp ON p.FKTipoProducto = tp.PKTipoProducto LEFT JOIN rel_acciones_producto as rap ON rap.FKProducto = p.PKProducto WHERE rap.FKAccionProducto = 2 ORDER BY p.Nombre ASC');
$stmt->execute();
?>
                                      <?php foreach ($stmt as $option): ?>
                                      <option value="<?php echo $option['PKProducto']; ?>">
                                        <?php echo $option['ClaveInterna'] . " " . $option['Nombre']; ?></option>
                                      <?php endforeach;?>
                                    </select>
                                  </span>
                                  <button style="width: 30%;font-size: 14px;" type="button"
                                    class="btn-custom btn-custom--border-blue" id="mostrar_todos">Mostrar todos los
                                    productos</button>
                                </div>
                              </div>
                              <span style="color: #d9534f;display: none;position: absolute;" id="alertaProducto">Ingresa
                                un producto</span>
                            </div>
                            <div class="col-lg-4">
                              <div>
                                <div class="row" id="divCantidad">
                                  <div class="col-lg-6">
                                    <label for="usr">Piezas:</label>
                                    <input type='number' value='' name="txtPiezas" id="txtPiezas"
                                      class='form-control numeric-only'>
                                    <span style="color: #d9534f;display: none;position: absolute;" id="alertaPiezas"
                                      onkeydown="insertProduct(event)">Ingresa la cantidad de piezas</span>
                                  </div>
                                  <div class="col-lg-6">
                                    <label for="usr">Precio:</label>
                                    <input type='number' value='' name="txtPrecio" id="txtPrecio" class='form-control'
                                      readonly>
                                  </div>
                                </div>

                              </div>
                            </div>
                            <div class="col-lg-2 d-flex justify-content-start align-items-end">
                              <input type="hidden" name="txtImpuestos" id="txtImpuestos" value="" />
                              <button type="button" class="btn-custom btn-custom--border-blue"
                                id="agregarProducto">Agregar</button>
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
                                <th></th>
                              </tr>
                            </thead>
                            <tbody id="lstProductos">
                              <?php
$stmt = $conn->prepare('SELECT dc.FKProducto, dc.Cantidad, dc.Precio, p.ClaveInterna, p.Nombre FROM detallecotizacion as dc INNER JOIN productos as p ON p.PKProducto = dc.FKProducto WHERE dc.FKCotizacion = :id');
$stmt->execute(array(':id' => $id));
$numero_productos = $stmt->rowCount();
$rowp = $stmt->fetchAll();
$impuestos = array();
$x = 0;

foreach ($rowp as $rp) {

    $stmt = $conn->prepare('SELECT i.PKImpuesto, i.Nombre, i.FKTipoImpuesto as TipoImpuesto, i.FKTipoImporte as TipoImporte, i.Operacion, di.FKProducto, di.Tasa FROM detalleimpuesto  as di INNER JOIN impuesto as i ON i.PKImpuesto = di.FKImpuesto WHERE di.FKCotizacion= :id AND FKProducto = :idProducto');
    $stmt->execute(array(':id' => $id, ':idProducto' => $rp['FKProducto']));
    $rowi = $stmt->fetchAll();
    //print_r($rowi);

    $totalProducto = $rp['Cantidad'] * $rp['Precio'];
    echo "<tr id='idProducto_" . $rp['FKProducto'] . "' class='text-center'>" .
        "<th id='nombreproducto_" . $rp['FKProducto'] . "'>" . $rp['ClaveInterna'] . " - " . $rp['Nombre'] . "</th>" .
        "<th id='piezas_" . $rp['FKProducto'] . "'><input type='number' name='inp_piezas[]' id='piezasUnic_" . $rp['FKProducto'] . "' value='" . $rp['Cantidad'] . "' class='modificarnumero numeros-solo' min='1'></th>" .
        "<input type='hidden' id='piezaAnt_" . $rp['FKProducto'] . "' value='" . $rp['Cantidad'] . "' />" .
        "<input type='hidden' name='inp_productos[]' value='" . $rp['FKProducto'] . "' />" .
        "<th>Pieza</th>" .
        "<th id='precio_" . $rp['FKProducto'] . "'>" . $rp['Precio'] . "</th>" .
        "<input type='hidden' name='inp_precio[]' value='" . $rp['Precio'] . "' />" .
        "<th>" .
        "<span id='impuestos_" . $rp['FKProducto'] . "'>";

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

        //echo "id impuesto :".$ri['PKImpuesto']."//";
        $found_key = array_search($ri['PKImpuesto'], array_column($impuestos, 0));
        /*print_r($found_key);
        echo "fk ".$found_key[0];*/
        if ($found_key > -1) {
            $impuestos[$found_key][0] = $ri['PKImpuesto'];

            if ($ri['TipoImporte'] == 1) {
                $impuestos[$found_key][1] = $impuestos[$found_key][1] + (($rp['Cantidad'] * $rp['Precio']) * ($ri['Tasa'] / 100));
            } else {
                $impuestos[$found_key][1] = $impuestos[$found_key][1] + $ri['Tasa'];
            }

            $impuestos[$found_key][2] = $ri['Nombre'];
            $impuestos[$found_key][3] = $ri['TipoImpuesto'];
            $impuestos[$found_key][4] = $ri['Operacion'];
        } else {
            $impuestos[$x][0] = $ri['PKImpuesto'];
            if ($ri['TipoImporte'] == 1) {
                $impuestos[$x][1] = ($rp['Cantidad'] * $rp['Precio']) * ($ri['Tasa'] / 100);
            } else {
                $impuestos[$x][1] = $ri['Tasa'];
            }

            $impuestos[$x][2] = $ri['Nombre'];
            $impuestos[$x][3] = $ri['TipoImpuesto'];
            $impuestos[$x][4] = $ri['Operacion'];
            $x++;
        }

        echo "<span id='" . $Identificador . "' >" . $ri['Nombre'] . " " . $ri['Tasa'] . $tas . " <input name='valImp_" . $ri['Tasa'] . "' type='hidden' id='impAgregado_" . $ri['FKProducto'] . "_" . $ri['PKImpuesto'] . "' value='" . $ri['Tasa'] . "' /><input type='hidden' id='OperacionUnica_" . $ri['FKProducto'] . "_" . $ri['PKImpuesto'] . "' value='" . $ri['Operacion'] . "' /></span>";
        if ($contImpuestos != $numImpuestos) {
            echo "<br>";
        }

        $contImpuestos++;
    }

    echo "</span>" .
    "</th>" .
    "<th id='totalproducto_" . $rp['FKProducto'] . "'>" . number_format($totalProducto, 2) . "</th>" .
    "<input type='hidden' name='inp_total_producto[]' value='" . number_format($totalProducto, 2) . "' />" .
        "<th><button type='button' class='btn eliminarProductos' id='" . $rp['FKProducto'] . "'>X</button></th>" .
        "</tr>";
}

?>
                            </tbody>
                            <tr>
                              <th colspan="3"></th>
                              <th>Subtotal:</th>
                              <th colspan="2" style="text-align: right;">$ <span
                                  id="Subtotal"><?=number_format($Subtotal, 2)?></span></th>
                              <th></th>
                            </tr>
                            <tr>
                              <th colspan="3"></th>
                              <th>Impuestos:</th>
                              <th colspan="2"></th>
                              <th></th>
                            </tr>
                            <tbody id="lstimpuestos">
                              <?php
foreach ($impuestos as $imp) {
    $IniImpuesto = explode(" ", $imp[2]);
    echo "<tr id='" . $IniImpuesto[0] . "_" . $imp[0] . "'>" .
    "<th colspan='3'></th>" .
    "<th style='text-align: right;'>" . $imp[2] . "</th>" .
    "<th colspan='2' style='text-align: right;'>$ <span id='Impuesto_" . $imp[0] . "' name='" . $imp[3] . "_" . $imp[4] . "' class='ImpuestoTot'>" . number_format($imp[1], 2) . "</span></th>" .
        "<th></th>" .
        "</tr>";
}
?>
                            </tbody>
                            <tr class="total">
                              <th colspan="3" class="redondearAbajoIzq"></th>
                              <th>Total:</th>
                              <th colspan="2" style="text-align: right;">$ <span
                                  id="Total"><?=number_format($ImporteTotal, 2)?></span></th>
                              <th class="redondearAbajoDer"></th>
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
                            <label for="usr">Notas visibles al cliente</label>
                            <textarea class="form-control" cols="10" rows="3" name="NotasClientes" id="NotasClientes"
                              placeholder="Aquí puedes colocar la descripción de tu cotización o datos importantes solo para uso interno"><?=$NotasClientes?></textarea>
                          </div>
                          <div class="col-lg-6">
                            <label for="usr">Notas internas</label>
                            <textarea class="form-control" cols="10" rows="3" name="NotasInternas" id="NotasInternas"
                              placeholder="Aquí puedes colocar la descripción de tu cotización o datos importantes solo para uso interno"><?=$NotasInternas?></textarea>
                          </div>
                        </div>
                        <br>
                        <input type="hidden" name="idCotizacion" id="idCotizacion" value="<?=$id?>">
                        <button type="button" class="btn-custom btn-custom--blue float-right" name="btnModificar"
                          id="btnModificar">Modificar</button>
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
$rutaf = "../../";
require_once '../../footer.php';
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

  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
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

  <!-- Core plugin JavaScript-->
  <script src="../../../js/bootstrap-clockpicker.min.js"></script>
  <script src="../../../js/jquery.number.min.js"></script>
  <script src="../../../js/numeral.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="../../../js/sb-admin-2.min.js"></script>
  <script src="../../../js/scripts.js"></script>
  <script src="../../../js/validaciones.js"></script>

  <script>
  var cuenta = <?=$numero_productos?>;
  var idProductoG;
  var cuentaIVAexento = 0;
  var idCotizacion = <?=$id?>;
  var cantidadAnterior = 0;

  $("#cmbRazon")[0].reportValidity();
  $("#cmbRazon")[0].setCustomValidity('Completa este campo.');

  $("#txtFechaVencimiento")[0].reportValidity();
  $("#txtFechaVencimiento")[0].setCustomValidity('La fecha de vencimiento es mayor del rango de días permitido.');

  $(document).ready(function() {
    $("#alertaTareas").load('../../alerta_Tareas_Nuevas.php?user=' + <?=$_SESSION['PKUsuario'];?> + '&ruta=' +
      '<?=$ruta;?>');
    setInterval(refrescar, 5000);
  });

  function refrescar() {
    $("#alertaTareas").load('../../alerta_Tareas_Nuevas.php?user=' + <?=$_SESSION['PKUsuario'];?> + '&ruta=' +
      '<?=$ruta;?>');
  }

  $("#chosenProducto").change(function() {
    var idProducto = $("#chosenProducto").val();
    var idCliente = $("#chosen").val();

    $.ajax({
      type: 'POST',
      url: 'valoresCotizacion.php',
      data: {
        idProducto: idProducto,
        idCliente: idCliente
      },
      success: function(data) {
        var datos = JSON.parse(data);
        $("#txtPrecio").val(datos.Precio);
        $("#txtImpuestos").val(datos.Impuestos);
      }
    });
  });


  function cambiarImpuestoValores(idImpuesto) {
    $.ajax({
      type: 'POST',
      url: 'valoresImpuesto.php',
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


  $("#agregarProducto").click(function() {

    var idProducto = parseInt($("#chosenProducto").val());
    var Producto = $("#chosenProducto").children("option:selected").text();
    var Piezas = parseInt($("#txtPiezas").val());
    var Precio = parseFloat($("#txtPrecio").val());
    var TotalProducto, nuevo_elemento, Piezas_old, TotalProducto_old = 0,
      nuevo_impuesto;
    var SubtotalNum = numeral($("#Subtotal").html());
    var Subtotal, Operacion;
    var PrecioF, TotalProductoF, TotalProducto_format;
    var IVAProducto, IVAProductoF, IVATotalProductoF, IVAant, IVATotalProducto;
    var ImpuestosCompleto = $("#txtImpuestos").val();

    selectCliente.disable();

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
      TotalProducto_format = numeral($("#totalproducto_" + idProducto).html());
      TotalProducto_old = TotalProducto_format.value();

      var PiezasImp = Piezas;
      Piezas = Piezas + Piezas_old;
      TotalProducto = (Piezas * Precio).toFixed(2);

      PrecioF = $.number(Precio, 2, '.', ',');
      TotalProductoF = $.number(TotalProducto, 2, '.', ',');

      var impuestosOld = $("#impuestos_" + idProducto).html();

      var impuestoXProducto, impuestoCantidad, arrayImp, TipoTasa;
      var idImpuestoOld, totImpIndividual, impuestoTotalant, impuestoTotNuevo, impuestoTotNuevoF;

      $('#impuestos_' + idProducto + ' > span').each(function(index, span) {
        impuestoXProducto = $(this).attr("id");
        arrayImp = impuestoXProducto.split("_");
        idImpuestoOld = arrayImp[3];
        TipoTasa = arrayImp[2];
        impuestoCantidad = parseFloat($("#impAgregado_" + idProducto + "_" + idImpuestoOld).val());

        if (TipoTasa == 1)
          totImpIndividual = parseFloat((PiezasImp * Precio) * (impuestoCantidad / 100));
        if (TipoTasa == 2 || TipoTasa == 3)
          totImpIndividual = 0.00;

        impuestoTotalant = numeral($("#Impuesto_" + idImpuestoOld).html());
        impuestoTotNuevo = totImpIndividual + impuestoTotalant.value();
        impuestoTotNuevoF = $.number(impuestoTotNuevo, 2, '.', ',');
        $("#Impuesto_" + idImpuestoOld).html(impuestoTotNuevoF);

      });

      $('#idProducto_' + idProducto).empty();
      nuevo_elemento = "<th id='nombreproducto_" + idProducto + "'>" + Producto + "</th>" +
        "<th id='piezas_" + idProducto + "'><input type='number' id='piezasUnic_" + idProducto +
        "' name='inp_piezas[]' value='" + Piezas + "' class='modificarnumero numeros-solo' min='1' >" + "</th>" +
        "<input type='hidden' id='piezaAnt_" + idProducto + "' value='" + Piezas + "' />" +
        "<input type='hidden' name='inp_productos[]' value='" + idProducto + "' />" +
        "<th>Pieza</th>" +
        "<th id='precio_" + idProducto + "'>" + PrecioF + "</th>" +
        "<input type='hidden' name='inp_precio[]' value='" + Precio + "' />" +
        "<th>" +
        "<span id='impuestos_" + idProducto + "'> " +
        impuestosOld +
        "</span>" +
        "</th>" +
        "<th id='totalproducto_" + idProducto + "'>" + TotalProductoF + "</th>" +
        "<input type='hidden' name='inp_total_producto[]' value='" + TotalProducto + "' />" +
        "<th><button type='button' class='btn eliminarProductos' id='" + idProducto + "'>X</button></th>";
      $('#idProducto_' + idProducto).append(nuevo_elemento);

    } else {
      //cuando se ingresa un nuevo producto
      TotalProducto = (Piezas * Precio).toFixed(2);
      descuento = "";

      PrecioF = $.number(Precio, 2, '.', ',');
      TotalProductoF = $.number(TotalProducto, 2, '.', ',');

      nuevo_elemento = "<tr id='idProducto_" + idProducto + "' class='text-center'>" +
        "<th id='nombreproducto_" + idProducto + "'>" + Producto + "</th>" +
        "<th id='piezas_" + idProducto + "'><input type='number' name='inp_piezas[]' id='piezasUnic_" + idProducto +
        "' value='" + Piezas + "' class='modificarnumero numeros-solo' min='1' >" + "</th>" +
        "<input type='hidden' id='piezaAnt_" + idProducto + "' value='" + Piezas + "' />" +
        "<input type='hidden' name='inp_productos[]' value='" + idProducto + "' />" +
        "<th>Pieza</th>" +
        "<th id='precio_" + idProducto + "'>" + PrecioF + "</th>" +
        "<input type='hidden' name='inp_precio[]' value='" + Precio + "' />" +
        "<th>" +
        ImpuestosCompleto +
        "</th>";

      nuevo_elemento += "<th id='totalproducto_" + idProducto + "'>" + TotalProductoF + "</th>" +
        "<input type='hidden' name='inp_total_producto[]' value='" + TotalProducto + "' />" +
        "<th><button type='button' class='btn eliminarProductos' id='" + idProducto + "'>X</button></th>" +
        "</tr>";

      $('#lstProductos').append(nuevo_elemento);

      $('#impuestos_' + idProducto + ' > span').each(function(index, span) {

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
          var TotalImpuesto = impuestoCantidad;
        }
        if (TipoTasa == 3) {
          var TotalImpuesto = 0.00;
        }

        var TotalImpuestoF;
        var TotalImpuestoGen;
        Operacion = $("#OperacionUnica_" + idProducto + "_" + idImpuestoOld).val();
        if ($('#lstimpuestos').find('#' + arrayImp[0] + "_" + idImpuestoOld).length == 1) {

          var sumaImpuesto = numeral($('#Impuesto_' + idImpuestoOld).html());

          TotalImpuestoGen = TotalImpuesto + parseFloat(sumaImpuesto.value());
          TotalImpuestoF = $.number(TotalImpuestoGen, 2, '.', ',');
          nuevo_impuesto = "<th colspan='3'></th>" +
            "<th style='text-align: right;'>" + arrayImp[0] + "</th>" +
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
            "<th style='text-align: right;'>" + arrayImp[0] + "</th>" +
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

    selectProductos.set("");
    $('#txtPiezas').val("");
    $('#txtPrecio').val("");
    cuenta++;

  });



  $("#txtPiezas").on('keydown', function(e) {
    if (e.keyCode == 13) {

      var idProducto = parseInt($("#chosenProducto").val());
      var Producto = $("#chosenProducto").children("option:selected").text();
      var Piezas = parseInt($("#txtPiezas").val());
      var Precio = parseFloat($("#txtPrecio").val());
      var TotalProducto, nuevo_elemento, Piezas_old, TotalProducto_old = 0,
        nuevo_impuesto;
      var SubtotalNum = numeral($("#Subtotal").html());
      var Subtotal, Operacion;
      var PrecioF, TotalProductoF, TotalProducto_format;
      var IVAProducto, IVAProductoF, IVATotalProductoF, IVAant, IVATotalProducto;
      var ImpuestosCompleto = $("#txtImpuestos").val();

      selectCliente.disable();

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
        TotalProducto_format = numeral($("#totalproducto_" + idProducto).html());
        TotalProducto_old = TotalProducto_format.value();

        var PiezasImp = Piezas;
        Piezas = Piezas + Piezas_old;
        TotalProducto = (Piezas * Precio).toFixed(2);

        PrecioF = $.number(Precio, 2, '.', ',');
        TotalProductoF = $.number(TotalProducto, 2, '.', ',');

        var impuestosOld = $("#impuestos_" + idProducto).html();

        var impuestoXProducto, impuestoCantidad, arrayImp, TipoTasa;
        var idImpuestoOld, totImpIndividual, impuestoTotalant, impuestoTotNuevo, impuestoTotNuevoF;

        $('#impuestos_' + idProducto + ' > span').each(function(index, span) {
          impuestoXProducto = $(this).attr("id");
          arrayImp = impuestoXProducto.split("_");
          idImpuestoOld = arrayImp[3];
          TipoTasa = arrayImp[2];
          impuestoCantidad = parseFloat($("#impAgregado_" + idProducto + "_" + idImpuestoOld).val());

          if (TipoTasa == 1)
            totImpIndividual = parseFloat((PiezasImp * Precio) * (impuestoCantidad / 100));
          if (TipoTasa == 2 || TipoTasa == 3)
            totImpIndividual = 0.00;

          impuestoTotalant = numeral($("#Impuesto_" + idImpuestoOld).html());
          impuestoTotNuevo = totImpIndividual + impuestoTotalant.value();
          impuestoTotNuevoF = $.number(impuestoTotNuevo, 2, '.', ',');
          $("#Impuesto_" + idImpuestoOld).html(impuestoTotNuevoF);

        });

        $('#idProducto_' + idProducto).empty();
        nuevo_elemento = "<th id='nombreproducto_" + idProducto + "'>" + Producto + "</th>" +
          "<th id='piezas_" + idProducto + "'><input type='number' id='piezasUnic_" + idProducto +
          "' name='inp_piezas[]' value='" + Piezas + "' class='modificarnumero numeros-solo' min='1' >" + "</th>" +
          "<input type='hidden' id='piezaAnt_" + idProducto + "' value='" + Piezas + "' />" +
          "<input type='hidden' name='inp_productos[]' value='" + idProducto + "' />" +
          "<th>Pieza</th>" +
          "<th id='precio_" + idProducto + "'>" + PrecioF + "</th>" +
          "<input type='hidden' name='inp_precio[]' value='" + Precio + "' />" +
          "<th>" +
          "<span id='impuestos_" + idProducto + "'> " +
          impuestosOld +
          "</span>" +
          "</th>" +
          "<th id='totalproducto_" + idProducto + "'>" + TotalProductoF + "</th>" +
          "<input type='hidden' name='inp_total_producto[]' value='" + TotalProducto + "' />" +
          "<th><button type='button' class='btn eliminarProductos' id='" + idProducto + "'>X</button></th>";
        $('#idProducto_' + idProducto).append(nuevo_elemento);

      } else {
        //cuando se ingresa un nuevo producto
        TotalProducto = (Piezas * Precio).toFixed(2);
        descuento = "";

        PrecioF = $.number(Precio, 2, '.', ',');
        TotalProductoF = $.number(TotalProducto, 2, '.', ',');

        nuevo_elemento = "<tr id='idProducto_" + idProducto + "' class='text-center'>" +
          "<th id='nombreproducto_" + idProducto + "'>" + Producto + "</th>" +
          "<th id='piezas_" + idProducto + "'><input type='number' name='inp_piezas[]' id='piezasUnic_" +
          idProducto + "' value='" + Piezas + "' class='modificarnumero numeros-solo' min='1' >" + "</th>" +
          "<input type='hidden' id='piezaAnt_" + idProducto + "' value='" + Piezas + "' />" +
          "<input type='hidden' name='inp_productos[]' value='" + idProducto + "' />" +
          "<th>Pieza</th>" +
          "<th id='precio_" + idProducto + "'>" + PrecioF + "</th>" +
          "<input type='hidden' name='inp_precio[]' value='" + Precio + "' />" +
          "<th>" +
          ImpuestosCompleto +
          "</th>";

        nuevo_elemento += "<th id='totalproducto_" + idProducto + "'>" + TotalProductoF + "</th>" +
          "<input type='hidden' name='inp_total_producto[]' value='" + TotalProducto + "' />" +
          "<th><button type='button' class='btn eliminarProductos' id='" + idProducto + "'>X</button></th>" +
          "</tr>";

        $('#lstProductos').append(nuevo_elemento);

        $('#impuestos_' + idProducto + ' > span').each(function(index, span) {

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
            var TotalImpuesto = impuestoCantidad;
          }
          if (TipoTasa == 3) {
            var TotalImpuesto = 0.00;
          }

          var TotalImpuestoF;
          var TotalImpuestoGen;
          Operacion = $("#OperacionUnica_" + idProducto + "_" + idImpuestoOld).val();
          if ($('#lstimpuestos').find('#' + arrayImp[0] + "_" + idImpuestoOld).length == 1) {

            var sumaImpuesto = numeral($('#Impuesto_' + idImpuestoOld).html());

            TotalImpuestoGen = TotalImpuesto + parseFloat(sumaImpuesto.value());
            TotalImpuestoF = $.number(TotalImpuestoGen, 2, '.', ',');
            nuevo_impuesto = "<th colspan='3'></th>" +
              "<th style='text-align: right;'>" + arrayImp[0] + "</th>" +
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
              "<th style='text-align: right;'>" + arrayImp[0] + "</th>" +
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

      selectProductos.set("");
      $('#txtPiezas').val("");
      $('#txtPrecio').val("");
      cuenta++;
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

    var Subtotal = Subtotal_old - parseFloat(TotalProducto);
    var SubtotalF = $.number(Subtotal, 2, '.', ',');
    $('#Subtotal').empty();
    $('#Subtotal').append(SubtotalF);

    var impuestoXProducto, impuestoCantidad, arrayImp, TipoTasa, Impuesto;
    var idImpuestoOld, totImpIndividual, impuestoTotalant, impuestoTotNuevo, impuestoTotNuevoF;

    $('#impuestos_' + idProducto + ' > span').each(function(index, span) {
      impuestoXProducto = $(this).attr("id");
      arrayImp = impuestoXProducto.split("_");
      Impuesto = arrayImp[0];
      TipoTasa = arrayImp[2];
      idImpuestoOld = arrayImp[3];
      impuestoCantidad = parseFloat($("#impAgregado_" + idProducto + "_" + idImpuestoOld).val());

      if (TipoTasa == 1)
        totImpIndividual = parseFloat(TotalProducto * (impuestoCantidad / 100));
      if (TipoTasa == 2)
        totImpIndividual = impuestoCantidad;
      if (TipoTasa == 3)
        totImpIndividual = 0.00;
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
        cuentaIVAexento--;
        if (cuentaIVAexento == 0)
          $("#" + Impuesto + "_" + idImpuestoOld).remove();
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

    if (cuenta == 0)
      selectCliente.enable();

    $("#catalogoImpuestos").css("display", "none");
  });

  $(document).on("keyup", ".modificarnumero", function() {
    this.value = this.value.replace(/[^0-9]/g, '');
  });

  //Modificar cantidad de  productos
  $(document).on("change", ".modificarnumero", function() {

    this.value = this.value.replace('.', '');
    var cantidadNueva;

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

    var PrecioProducto = numeral($("#precio_" + idProducto).html());

    var TotalProducto = (cantidad * PrecioProducto.value()).toFixed(2);

    var TotalProductoAnt = numeral($("#totalproducto_" + idProducto).html());
    var TotalProductoFinFormat = parseFloat(TotalProducto) + parseFloat(TotalProductoAnt.value());
    var TotalProductoFin = $.number(TotalProductoFinFormat, 2, '.', ',');

    $('#totalproducto_' + idProducto).html(TotalProductoFin);


    var SubtotalNum = numeral($("#Subtotal").html());
    Subtotal = SubtotalNum.value() + parseFloat(TotalProducto);
    var SubtotalF = $.number(Subtotal, 2, '.', ',');
    $('#Subtotal').empty();
    $('#Subtotal').append(SubtotalF);

    var impuestoXProducto, impuestoCantidad, arrayImp, TipoTasa;
    var idImpuestoOld, totImpIndividual, impuestoTotalant, impuestoTotNuevo, impuestoTotNuevoF;

    $('#impuestos_' + idProducto + ' > span').each(function(index, span) {
      impuestoXProducto = $(this).attr("id");
      arrayImp = impuestoXProducto.split("_");
      idImpuestoOld = arrayImp[3];
      TipoTasa = arrayImp[2];
      impuestoCantidad = parseFloat($("#impAgregado_" + idProducto + "_" + idImpuestoOld).val());

      if (TipoTasa == 1) {
        totImpIndividual = parseFloat((cantidad * PrecioProducto.value()) * (impuestoCantidad / 100));
        //console.log(cantidad + "//" + PrecioProducto.value() + "//" + impuestoCantidad);
      }
      if (TipoTasa == 2 || TipoTasa == 3) {
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

    if (cliente < 1) {
      $("#chosen")[0].reportValidity();
      $("#chosen")[0].setCustomValidity('Completa este campo.');
      return;
    }

    /*if(razon_social < 1){
      $("#cmbRazon")[0].reportValidity();
      $("#cmbRazon")[0].setCustomValidity('Completa este campo.');
      return;
    }*/

    if ((new Date(fechaVencimiento).getTime() > new Date(fechaVencimientoInicial).getTime())) {
      $("#txtFechaVencimiento")[0].reportValidity();
      $("#txtFechaVencimiento")[0].setCustomValidity(
        'La fecha de vencimiento es mayor del rango de días permitido.');
      return;
    }

    if (referencia.trim() == "") {
      $("#txtReferencia")[0].reportValidity();
      $("#txtReferencia")[0].setCustomValidity('Completa este campo.');
      return;
    }

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
        url: 'cotizacionSubmitModificar.php',
        data: $('#form-cotizacion').serialize(),
        success: function() {
          $(location).attr('href', 'ver_Cotizacion.php?id=' + idCotizacion);
        }
      });
    } else {
      $("#mostrarMensaje").css("display", "block");
      setTimeout(function() {
        $("#mostrarMensaje").css("display", "none");
      }, 2000);
    }
  });

  var todos = 0;
  $("#mostrar_todos").click(function() {

    if (todos == 0) {
      $.ajax({
        type: 'post',
        url: 'actualizarProductos.php',
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
        url: 'actualizarProductos.php',
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

  var selectRazon = new SlimSelect({
    select: '#cmbRazon',
    deselectLabel: '<span class="">✖</span>',
  });
  </script>
  <script>
  var ruta = "../../";
  </script>

</body>

</html>