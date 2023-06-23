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

$sucursalOrigen;
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

  <title>Timlid | Agregar Traspaso</title>

  <!-- Custom fonts for this template-->
  <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../../css/bootstrap-clockpicker.min.css" rel="stylesheet" type="text/css">
  <link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../css/slimselect.min.css" rel="stylesheet">
  <link href="../../css/styles.css" rel="stylesheet">
  <style type="text/css">
  .bar-title{
    background-color:#006dd9;
    color:white;
    padding:0.75rem;
    font-size:18px;
  }
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
  .input-group-addon {
    margin-top: 0px !important;
    margin-left: 0px !important;
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
  <script src="../../js/notificaciones_timlid.js" charset="utf-8"></script>
  <script src="js/agregartraspaso.js" charset="utf-8"></script>
  <link rel="stylesheet" href="../../css/notificaciones.css">
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
$titulo = "Traspaso";
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
                                    <p class="bar-title">Información del traspaso</p>
                                </div>
                            <div class="col-lg-6">
                                <label for="usr">Sucursal origen:</label>
                                <select name="cmbSucursalOrigen" id="chosenSucursalOrigen" required onchange="cambioOrigen()">
                                <option value="0">Elegir opción</option>
                                <?php
                                  $stmt = $conn->prepare('SELECT id, sucursal FROM sucursales WHERE estatus = 1 AND activar_inventario = 1 AND empresa_id = :idempresa');
                                  $stmt->bindValue("idempresa",$_SESSION['IDEmpresa']);
                                  $stmt->execute();
                                ?>
                                <?php foreach ($stmt as $option): ?>
                                <option value="<?php echo $option['id']; ?>">
                                  <?php echo $option['sucursal']; ?></option>
                                <?php endforeach;?>
                              </select>
                              <span style="color: #d9534f;display: none;position: absolute;" id="alertaSucursal">Selecciona la sucursal de origen</span>
                            </div>
                            <div class="col-lg-6">
                              <label for="usr">Sucursal destino:</label>
                                <select name="cmbSucursalDestino" id="chosenSucursalDestino" required>
                                <option value="0">Elegir opción</option>
                                <?php
                                #activar_inventario siempre 1
                                  $stmt = $conn->prepare('SELECT id, sucursal FROM sucursales WHERE estatus = 1 AND activar_inventario = 1 AND empresa_id = :idempresa');
                                  $stmt->bindValue("idempresa",$_SESSION['IDEmpresa']);
                                  $stmt->execute();
                                ?>
                                <?php foreach ($stmt as $option): ?>
                                <option value="<?php echo $option['id']; ?>">
                                  <?php echo $option['sucursal']; ?></option>
                                <?php endforeach;?>
                              </select>
                              <span style="color: #d9534f;display: none;position: absolute;" id="alertaSucursalDestino">Selecciona la sucursal destino</span>
                            </div>
                          </div>
                          <br>
                          <div class="row">
                            <div class="col-lg-6">
                              <label for="usr">Fecha de generación:</label>
                              <?php date_default_timezone_set('America/Mexico_City'); $fechaGeneracion = date('Y-m-d\TH:i:s'); $fechaGeneracionSinHora = date('Y-m-d');?>
                              <input type="datetime-local" class="form-control" name="txtFechaGeneracion" id="txtFechaGeneracion"
                                value="<?=$fechaGeneracion?>" readonly>
                            </div>
                            <div class="col-lg-6"></div>
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
                                  <span class="input-group-addon" style="width:70%">
                                    <select name="cmbProducto" id="chosenProducto" style="width: 90%;" required>
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
                          <table class="table table-sm" id="traspaso">
                            <thead class="text-center header-color">
                              <tr>
                                <th>Clave/Producto</th>
                                <th>Unidad de medida</th>
                                <th>Cantidad</th>
                                <th>Lote</th>
                                <th></th>
                              </tr>
                            </thead>
                            <tbody id="lstProductos">
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
                        <input type="hidden" name="csr_token_78L4" id="csr_token_78L4" value="<?=$token?>">
                        <button type="button" class="btn-custom btn-custom--blue float-right" name="btnAgregar"
                          id="btnAgregar">Guardar</button>
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

  <!--Set up MODAL SLIDE Products-->
  <div class="modal fade" id="configurarProducto" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form action="" method="POST" id="form-lotes">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Configurar lotes</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true" class="text-light">x</span>
            </button>
          </div>
          <div class="modal-body">
            <input type="hidden" id="idProducto" name="idProducto">
            <div class="row">
              <div class="form-group col-md-6">
                <label for="usr">Producto:</label> <span id="configProducto"></span>
              </div>
              <div class="form-group col-md-6">
                <label for="usr">Cantidad:</label> <span id="configProductoCantFalt"></span>
                <input type="hidden" id="configProductoCantFaltInput" />
              </div>
            </div>
            <div class="row">
              <div class="col-12">
              <span style="color: #d9534f;display: none;position: absolute; font-size: 12px;" id="alertaCantidad">La suma de la cantidad de los lotes excede la cantidad total</span>
              </div>
            </div>
            <div class="form-group col-md-12">
              <hr>
              <div class="row">
                <div class="col-md-3">
                  Lote:
                </div>

                <div class="col-md-3">
                  Existencias
                </div>

                <div class="col-md-2">
                  Cantidad
                </div>
                
                <div class="col-md-2">
                </div>
              </div>
              <hr>

              <span id="listProducto">

              </span>
            </div>
          </div>
          <div class="modal-footer">
            <button class="btn-custom btn-custom--border-blue btnCancelarActualizacion" type="button" onclick="serializeFormLotes()"><span class="ajusteProyecto">Agregar</span></button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Core plugin JavaScript-->
  <script src="../../js/bootstrap-clockpicker.min.js"></script>
  <script src="../../js/jquery.number.min.js"></script>
  <script src="../../js/numeral.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="../../js/sb-admin-2.min.js"></script>
  <script src="../../js/scripts.js"></script>
  <script src="../../js/validaciones.js"></script>
  <script>
  var cuenta = 0;
  var idProductoG;
  var cuentaIVAexento = 0;
  var cantidadAnterior = 0;


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

    $("#lstProductos").empty();

    $.ajax({
          type: 'post',
          url: 'functions/deleteProductosTemp.php',
          success: function(data) {
            //console.log(data);
          },
          error: function(error){
            //console.log(error);
          }
    });

    cuenta = 0;

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
  
  function validarCantidadExistencia(item, existencia){
    if(item.value > existencia){
      $("#alertaExistenciaLote-" + item.name.split('-')[1]).css('display', 'block');
      $("#alertaExistenciaLote-" + item.name.split('-')[1]).addClass('invalid-feedback');
    }else{
      $("#alertaExistenciaLote-" + item.name.split('-')[1]).css('display', 'none');
      $("#alertaExistenciaLote-" + item.name.split('-')[1]).removeClass('invalid-feedback');
    }
    //$("#alertaCantidad").text('No se ha completado la cantidad');
    $("#alertaCantidad").css('display', 'none');
    $("#alertaCantidad").removeClass('invalid-feedback');
  }

  function serializeFormLotes(){
    var x = $("#form-lotes").serializeArray();
    var cantidadTotal = 0;
    $.each(x, function(i, field){
      if(field.name.startsWith('cantidadModal')){
        cantidadTotal += parseInt(field.value);
      }
      if((i+1) === x.length){
        if(cantidadTotal < $("#configProductoCantFaltInput").val()){
          /* $("#alertaCantidad").css('display', 'block');
        $("#alertaCantidad").addClass('invalid-feedback'); */
        }else if(cantidadTotal > $("#configProductoCantFaltInput").val()){
          //$("#alertaCantidad").text('La suma de la cantidad de los lotes excede la cantidad total');
          $("#alertaCantidad").css('display', 'block');
        $("#alertaCantidad").addClass('invalid-feedback');
        }
      }
    });
    if(!$("div").hasClass('invalid-feedback') && !$("span").hasClass('invalid-feedback')){
    var x2 = $("#form-lotes").serializeArray();
    $.each(x2, function(i, field){
      if(field.name.startsWith('cantidadModal')){
        if(parseInt(field.value) != 0){
          insertarFila(field.name.split('-')[1],field.value)
        }
      }
    });
    $("#configurarProducto").modal('hide');
    }
  }

  $("#agregarProducto").click(function() {
    var Piezas = $("#txtPiezas").val();
    if (Piezas < 1 || isNaN(Piezas)) {
      $("#alertaPiezas").css("display", "block");
      setTimeout(function() {
        $("#alertaPiezas").css("display", "none");
      }, 2000);
      return;
    }else{
      configurarProducto();
    }
  });

    function insertarFila(lote, cantidad){
        var idProducto = parseInt($("#chosenProducto").val());
        var Producto = $("#chosenProducto").children("option:selected").text();
        var nuevo_elemento, Piezas_old;
        let UnidadMedida = $("#txtClaveUnidad").val();

        if (isNaN(idProducto)) {
        $("#alertaProducto").css("display", "block");
        setTimeout(function() {
            $("#alertaProducto").css("display", "none");
        }, 2000);
        return;
        }

        if (cantidad < 1 || isNaN(cantidad)) {
        $("#alertaPiezas").css("display", "block");
        setTimeout(function() {
            $("#alertaPiezas").css("display", "none");
        }, 2000);
        return;
        }
        if ($('#idProducto_' + idProducto + "_" + lote).length) {
        //cuando ya se agregó el producto
        Piezas_old = parseInt($("#piezasUnic_" + idProducto + "_" + lote).val());

        cantidad = parseInt(cantidad) + parseInt(Piezas_old);

        $('#idProducto_' + idProducto + "_" + lote).empty();
        nuevo_elemento = "<th id='nombreproducto_" + idProducto + "_" + lote + "'>" + Producto + "</th>" +
            "<th>" + UnidadMedida  + "</th>" +
            "<th id='piezas_" + idProducto + "_" + lote + "'>" + cantidad + "</th>" +
            "<th id='loteproducto_" + idProducto + "_" + lote + "'>" + lote + "</th>" +
            "<input type='hidden' id='piezaAnt_" + idProducto + "_" + lote + "' value='" + cantidad + "' />" +
            "<input type='hidden' id='piezasUnic_" + idProducto + "_" + lote + "' name='inp_piezas[]' value='" + cantidad + "' />" +
            "<input type='hidden' name='inp_productos[]' value='" + idProducto + "' />" +
            "<input type='hidden' name='inp_lotes[]' value='" + lote + "' />" +
            "<th><button type='button' class='btn eliminarProductos' id='" + idProducto + "_" + lote + "'><img src='../../img/timdesk/delete.svg' width='20px'/></button></th>";
        $('#idProducto_' + idProducto + "_" + lote).append(nuevo_elemento);

        } else {
        //cuando se ingresa un nuevo producto

        nuevo_elemento = "<tr id='idProducto_" + idProducto + "_" + lote + "' class='text-center'>" +
            "<th id='nombreproducto_" + idProducto + "_" + lote + "'>" + Producto + "</th>" +
            "<th>" + UnidadMedida  + "</th>" +
            "<th id='piezas_" + idProducto + "_" + lote + "'>" + cantidad + "</th>" +
            "<th id='loteproducto_" + idProducto + "_" + lote + "'>" + lote + "</th>" +
            "<input type='hidden' id='piezaAnt_" + idProducto + "_" + lote + "' value='" + cantidad + "' />" +
            "<input type='hidden' id='piezasUnic_" + idProducto + "_" + lote + "' name='inp_piezas[]' value='" + cantidad + "' />" +
            "<input type='hidden' name='inp_productos[]' value='" + idProducto + "' />" +
            "<input type='hidden' name='inp_lotes[]' value='" + lote + "' />" +
            "<th><button type='button' class='btn eliminarProductos' id='" + idProducto + "_" + lote + "'><img src='../../img/timdesk/delete.svg' width='20px'/></button></th>" +
            "</tr>";

        $('#lstProductos').append(nuevo_elemento);

        }

        $('#txtPiezas').val("");
        cuenta++;

        $.ajax({
          type: 'post',
          url: 'functions/agregarProductoTemp.php',
          data: {data1: lote, data2: cantidad, data3: idProducto},
          success: function(data) {
            console.log(data);
              if(data == 'error'){
                $("#btnAgregar").prop("disabled", false);
                Lobibox.notify("error", {
                  size: 'mini',
                  rounded: true,
                  delay: 3000,
                  delayIndicator: false,
                  position: 'center top', //or 'center bottom'
                  icon: false,
                  img: '../../img/timdesk/warning_circle.svg',
                  msg: "Ocurrió un error, vuelvelo a intentar."
                });
              }

          },
          error: function(){
            $("#btnAgregar").prop("disabled", false);
          }
        });
    }


  $("#txtPiezas").on('keydown', function(e) {
    if (e.keyCode == 13) {
    var Piezas = $("#txtPiezas").val();
    if (Piezas < 1 || isNaN(Piezas)) {
      $("#alertaPiezas").css("display", "block");
      setTimeout(function() {
        $("#alertaPiezas").css("display", "none");
      }, 2000);
      return;
    }else{
      configurarProducto();
    }
    }
  });

  $(document).ready(function(){
    $.ajax({
          type: 'post',
          url: 'functions/deleteProductosTemp.php',
          success: function(data) {
            //console.log(data);
          },
          error: function(error){
            //console.log(error);
          }
    });

    cuenta = 0;
  });

  //Eliminar productos
  $(document).on("click", ".eliminarProductos", function() {
    var idProducto = this.id.split('_')[0];
    var lote = this.id.split('_')[1];

    $.ajax({
            type: 'post',
            url: 'functions/deleteUnPrroductoTemp.php',
            data: {idProducto: idProducto, lote: lote},
            success: function(data) {
              console.log(data);
            },
            error: function(error){
              console.log(error);
            }
    });

    $('#idProducto_' + idProducto + '_' + lote).remove();
    cuenta--;

  });

  $(document).on("keyup", ".modificarnumero", function() {
    this.value = this.value.replace(/[^0-9]/g, '');
  });

  $("#btnAgregar").click(function() {

    var fechaGeneracion = "<?=$fechaGeneracionSinHora?>";
    let gSucursal = parseInt($("#chosenSucursalOrigen").val());
    let gSucursalDestino = parseInt($("#chosenSucursalDestino").val());

    if (gSucursal < 1) {
      $("#alertaSucursal").css("display", "block");
        setTimeout(function() {
          $("#alertaSucursal").css("display", "none");
        }, 2000);
      return;
    }

    if (gSucursalDestino < 1) {
      $("#alertaSucursalDestino").css("display", "block");
        setTimeout(function() {
          $("#alertaSucursalDestino").css("display", "none");
        }, 2000);
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

    var tabla_traspaso = {
      html: $('#traspaso').html()
    };

    $('#traspaso').append("<input type='hidden' name='tabla_traspaso'  id='tabla_traspaso' value='" +
      tabla_traspaso.html + "' />");

      $.ajax({
        type: 'post',
        url: 'functions/traspasoSubmit.php',
        data: $('#form-cotizacion').serialize(),
        success: function(data) {
          json = JSON.parse(data);
          console.log(JSON.parse(data));

            if(data == 'error-general'){
              $("#btnAgregar").prop("disabled", false);
              Lobibox.notify("error", {
                size: 'mini',
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: 'center top', //or 'center bottom'
                icon: false,
                img: '../../img/timdesk/warning_circle.svg',
                msg: "Ocurrió un error, vuelvelo a intentar."
              });
            }
            else{
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
                  title: "Traspaso realizado con éxito",
                  html: "Referencia: <b>" + json.numeroOrdenEmpresa + "</b>",
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
                    $(location).attr('href', 'functions/descargarSalida.php?folio=' + (parseInt(json.numeroOrdenEmpresa)).toString() + '-1');
                    setTimeout(function () {
                      $(location).attr('href', 'detalleTraspaso.php?id=' + json.idOrden);
                    }, 4000);
                  } else if (
                    //Read more about handling dismissals below
                    result.dismiss === Swal.DismissReason.cancel
                  ) {
                  }
                });
            }
        },
        error: function(){
          $("#btnAgregar").prop("disabled", false);
        }
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

function configurarProducto() {
  var pkProducto = $("#chosenProducto").val();
  //var producto  = $("#chosenProducto").text();
  var producto  = $('#chosenProducto option:selected').html()
  var cantidad = $("#txtPiezas").val();
  var sucursalOrigen = $("#chosenSucursalOrigen").val();

  var html = ``;

  $.ajax({
    url: "php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_LotesTraspaso",
      data: pkProducto,
      data2: sucursalOrigen
    },
    dataType: "json",
    success: function (data) {
      //console.log("respuesta de datos de producto: ", data);
      if(data.length < 1){
        Lobibox.notify("error", {
          size: 'mini',
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: 'center top', //or 'center bottom'
          icon: false,
          img: '../../img/chat/notificacion_error.svg',
          msg: "Este producto no tienes existencias para la sucursal de origen."
        });
      }else{
        for (var dato of data) {
          var lote = dato.Lote;

          html += `
                  <div class="row">
                    <div class="col-md-3">
                      <label class="form-check-label">${lote}</label>
                    </div>

                    <div class="col-md-3">
                    ${dato.existencia} 
                    </div>
                    
                    <div class="col-md-3">
                      <input class="form-control textTable border-0 cnt-lote-serie" type="number" value="0" id="cantidadModal-${lote}-${dato.existencia}" name="cantidadModal-${lote}-${dato.existencia}" data-serieLote="${lote}" onchange="validarCantidadExistencia(this, ${dato.existencia})">
                      <input type="hidden" id="cantidad-modal-old-${lote}" value="${
                      dato.salida
                    }">
                    </div>
                    
                    <div class="col-md-3">
                      <span style="color: #d9534f; display: none; position: absolute; font-size: 12px;" id="alertaExistenciaLote-${lote}">Existencia insuficiente</span>
                      <input type="hidden" value="${
                        dato.existencia
                      } " id="cantidadHisModal-${lote}" name="cantidadHisModal-${lote}">
                    </div>
                  </div>
                `;
        }
        $("#idProducto").val(pkProducto);
        $("#configProducto").html(producto);
        $("#configProductoCantFalt").html(cantidad);
        $("#configProductoCantFaltInput").val(cantidad);

        $("#listProducto").html(html);

        $("#configurarProducto").modal("show");
      }
    },
  });
}

  var selectProductos = new SlimSelect({
    select: '#chosenProducto',
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
    loadAlertsNoti('alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
    setInterval(loadAlertsNoti, 10000, 'alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
  </script>

</body>

</html>