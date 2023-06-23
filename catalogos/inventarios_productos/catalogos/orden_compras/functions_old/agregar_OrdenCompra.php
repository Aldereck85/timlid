<?php
session_start();

  if(isset($_SESSION["Usuario"])){
    require_once('../../../../../include/db-conn.php');

  }else {
    header("location:../../../../dashboard.php");
  }
  /*$stmt = $conn->prepare("SELECT PKOrdenCompra FROM orden_compra ORDER BY PKOrdenCompra DESC LIMIT 1");
  $stmt->execute();
  $OCReferencia = $stmt->fetch();
  $numcotizaciones = $stmt->rowCount();

  if($numcotizaciones > 0){
    $numReferencia  = $OCReferencia['PKOrdenCompra'] + 1;
    $Referencia = "OC".str_pad($numReferencia, 6, "0", STR_PAD_LEFT);
  }
  else{
    $Referencia = "OC000001";
  }*/
 ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <link rel="icon" type="image/png" href="../../../../../img/header/bluTimlid.png">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Timlid | Agregar Orden de compra</title>

  <!-- Bootstrap core JavaScript-->
  <script src="../../../../../vendor/jquery/jquery.min.js"></script>
  <script src="../../../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../../../../js/validaciones.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="../../../../vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="../../../../js/sb-admin-2.min.js"></script>

  <script src="../../../../js/bootstrap-clockpicker.min.js"></script>

  <!-- Custom fonts for this template-->
  <link href="../../../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../../../../css/bootstrap-clockpicker.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="../../../../../css/sb-admin-2.css" rel="stylesheet">
  <script src="../../../../../js/numeral.min.js"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js" integrity="sha256-c4gVE6fn+JRKMRvqjoDp+tlG4laudNYrXI1GncbfAYY=" crossorigin="anonymous"></script>
  <link href="../../../../../css/chosen.css" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css" integrity="sha256-EH/CzgoJbNED+gZgymswsIOrM9XhIbdSJ6Hwro09WE4=" crossorigin="anonymous" />

  <style type="text/css">
    .header-color {
    font-size: 18px;
    color: #fff;
    line-height: 1.4;
    background-color: #01dbb5;

  }
  .redondear{
    border-radius: 10px;
  }

  table .btn{
    padding: 0 0;
    color:#d9534f;
  }
  .table-input{
    height: 20px;
    width: 80%;
    border: none;
    border-bottom: 1px solid #f2f2f2;
    text-align: center;background-color: #f2f2f2;
  }
  .table-input:focus{
    text-align: center;background-color: #f2f2f2;
  }
  .total{
    color:white;
    background-color: #01a269;
    font-size: 24px;
  }
  .redondearAbajoIzq{
    border-radius: 0px 0px 0px 10px;
  }
  .redondearAbajoDer{
    border-radius: 0px 0px 10px 0px;
  }
  </style>

</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <?php
      $ruta = "../../../../";
      $ruteEdit = $ruta."central_notificaciones/";
      require_once('../../../../menu3.php');
    ?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

      <?php
            $rutatb = "../../../../";
            require_once('../../../../topbar.php');
          ?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Agregar orden de compra</h1>
          </div>


          <div class="row">

            <div class="col-lg-12">

              <!-- Basic Card Example -->
              <div class="card shadow mb-4">
                <div class="card-header">
                  Tarjeta de orden de compra
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-12" id="alertas"></div>
                  </div>
                    <div class="row">
                      <div class="col-lg-12">
                        <form action="" method="post" id="frmOrdenCompra">
                          <input type="hidden" name="txtEstatus" value="0">
                          <div class="form-group">
                            <div class="row">
                              <div class="col-lg-4">
                                <label for="usr">Referencia:*</label>
                                <input type="text" class="form-control alphaNumeric-only" maxlength="20"  name="txtReferencia" id="txtReferencia" value="<?=$Referencia; ?>">
                              </div>
                              <div class="col-lg-4">
                                <label for="usr">Fecha de emision:*</label>
                                <input type="date" class="form-control" maxlength="20" id="txtFechaEmision" disabled required>
                              </div>
                                <input type="hidden" name="txtFechaEmision" id="txtFechaEmision2" value="">
                              <div class="col-lg-4">
                                <label for="usr">Fecha estimada de entrega:*</label>
                                <input type="date" class="form-control" maxlength="20" name="txtFechaEstimada" id="txtFechaEstimada" required>
                              </div>
                            </div>
                            </div>
                            <div class="form-group">
                              <div class="row">
                                <div class="col-lg-6">
                                  <label for="usr">Proveedor:*</label>
                                  <select class="form-control" name="cmbProveedor" id="cmbProveedor">
                                    <option value="">Seleccione una opcion...</option>
                                    <?php
                                      $stmt = $conn->prepare('SELECT PKProveedor, Razon_Social FROM proveedores');
                                      $stmt->execute();
                                      while($row = $stmt->fetch()){
                                          if($row['PKProveedor'] != 1){
                                        ?>
                                        <option value="<?=$row['PKProveedor']; ?>"><?=$row['Razon_Social']; ?></option>
                                      <?php }} ?>
                                  </select>
                                </div>
                                <div class="col-lg-6">
                                  <label for="cmbDireccionEnvio">Direccion de entrega:*</label>
                                  <select class="form-control" name="cmbDireccionEnvio" id="cmbDireccionEnvio">
                                    <option value="">Seleccione un opcion...</option>
                                    <?php
                                      $stmt = $conn->prepare('SELECT PKDomicilio,Calle, Numero_exterior, Numero_Interior,Colonia,CP,Municipio,Estado FROM domicilio_de_envio_proveedores WHERE FKProveedor = :id');
                                      $stmt->bindValue(':id',1);
                                      $stmt->execute();
                                      while($row = $stmt->fetch()){
                                        ?>
                                        <option value="<?=$row['PKDomicilio']; ?>"><?=$row['Calle']." No. ".$row['Numero_exterior']." ".$row['Numero_Interior']." Colonia ".$row['Colonia']." C.P. ".$row['CP']." ".$row['Municipio']." ".$row['Estado']; ?></option>
                                    <?php } ?>
                                  </select>
                                </div>
                              </div>
                            </div>
                            <div class="form-group">
                              <div class="row">
                                <div class="col-lg-4">
                                  <label for="usr">Producto:*</label>
                                  <select class="form-control" name="cmbProducto" id="cmbProducto">
                                    <option value="">Seleccione una opcion...</option>
                                    <?php
                                      $stmt = $conn->prepare('SELECT p.PKProducto, p.Descripcion, p.Clave, u.Unidad_de_Medida, p.PrecioUnitario FROM productos AS p INNER JOIN unidad_medida AS u ON p.FKUnidadMedida = u.PKUnidadMedida');
                                      $stmt->execute();
                                      while($row = $stmt->fetch()){?>
                                        <option value="<?=$row['PKProducto']; ?>"><?=$row['Clave']." ".$row['Descripcion']." ".$row['Unidad_de_Medida']; ?></option>

                                      <?php } echo '<input type="hidden" id="unidad_medida" value="'.$row['Unidad_de_Medida'].'"'?>

                                  </select>
                                </div>
                                <div class="col-lg-3">
                                  <label for="usr">Precio unitario:*</label>
                                  <div class="input-group">
                                    <div class="input-group-prepend">
                                      <span class="input-group-text">$</span>
                                    </div>
                                    <input type="text" class="form-control numericDecimal-only" maxlength="10" name="txtPrecioUnitario" id="txtPrecioUnitario">
                                  </div>
                                </div>
                                <div class="col-lg-3">
                                  <label for="usr">Cantidad:*</label>
                                  <input type="text" class="form-control numeric-only txtCantidad"  maxlength="10" name="txtCantidad">
                                </div>
                                <div class="col-lg-2">
                                  <button class="btn btn-info" style="position: relative; top: 32px;width: 100%;" type="button" id="agregarProducto" name="agregarProducto">Agregar producto</button>
                                </div>
                              </div>
                            </div>
                            <hr style="border: 2px solid green;border-radius: 10px;">
                            <div class="table-responsive redondear">
                              <table class="table table-hover table-sm">
                                <thead class="text-center header-color">
                                  <tr>
                                    <th>Clave/Producto</th>
                                    <th>Cantidad</th>
                                    <th>Unidad de medida</th>
                                    <th>Precio unitario</th>
                                    <th>Importe</th>
                                    <th></th>
                                  </tr>
                                </thead>
                                <tbody id="lstProductos">
                                </tbody>
                                <tfoot >
                                  <tr>
                                    <th colspan="2"></th>
                                    <th style="text-align: right;">Subtotal:</th>
                                    <th colspan="2" style="text-align: right;">$ <span id="Subtotal">0.00</span></th>
                                    <th>&nbsp</th>
                                  </tr>
                                  <tr>
                                    <th colspan="2"></th>
                                    <th style="text-align: right;">Impuestos:</th>
                                    <th colspan="2"style="text-align: right;"><span id="IVA"></span></th>
                                    <th>&nbsp</th>
                                  </tr>
                                  <tr class="total">
                                    <th colspan="2" class="redondearAbajoIzq"></th>
                                    <th style="text-align: right;">Total:</th>
                                    <th colspan="2" style="text-align: right;">$ <span id="Total">0.00</span></th>
                                    <th class="redondearAbajoDer">&nbsp</th>
                                  </tr>
                                </tfoot>

                              </table>
                            </div>
                            <div class="form-group">
                              <div class="row">
                                <div class="col-lg-12">
                                  <label for="">Observaciones:</label>
                                  <textarea class="form-control alphaNumeric-only" maxlength="100" name="txaObservaciones" rows="3" cols="80"></textarea>
                                </div>
                              </div>
                            </div>
                            <label for="">* Campos requeridos</label>
                          <button type="button" class="btn btn-success float-right" name="btnAgregar" id="btnAgregar"><i class="fas fa-envelope"></i> Enviar orden de compra</button>
                        </form>
                      </div>
                    </div>


                </div>
              </div>

            </div>
          </div>

        </div>
        <!-- /.container-fluid -->
        <div id="modal_envio"></div>
      </div>
      <!-- End of Main Content -->

      <!-- Footer -->
      <?php
        $rutaf = "../../";
        require_once('../../footer.php');
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

  <?php
    $ref = [];
    $stmt = $conn->prepare('SELECT Referencia FROM orden_compra');
    $stmt->execute();
    while($row = $stmt->fetch()){

      $ref[] = $row['Referencia'];
    }
    ?>

  <script>


  var importe = 0;
  var importe1 = 0;
  var subtotal = 0;
  var suma = 0;
  window.onload = function(){
    var n = new Date();

    var y = n.getFullYear();
    var m = n.getMonth()+1;
    var d = n.getDate();
    if(m < 10 && d < 10){
      document.getElementById('txtFechaEmision').value = y+"-0"+m+"-0"+d;
      document.getElementById('txtFechaEmision2').value = y+"-0"+m+"-0"+d;
    }else if(m < 10){
      document.getElementById('txtFechaEmision').value = y+"-0"+m+"-"+d;
      document.getElementById('txtFechaEmision2').value = y+"-0"+m+"-"+d;
    }else if(d < 10){
      document.getElementById('txtFechaEmision').value = y+"-"+m+"-0"+d;
      document.getElementById('txtFechaEmision2').value = y+"-"+m+"-0"+d;
    }else{
      document.getElementById('txtFechaEmision').value = y+"-"+m+"-0"+d;
      document.getElementById('txtFechaEmision2').value = y+"-"+m+"-0"+d;
    }
  }
  $(document).ready(function(){
    $("#alertaTareas").load('../../alerta_Tareas_Nuevas.php?user='+<?=$_SESSION['PKUsuario'];?>+'&ruta='+'<?=$ruta;?>');
      setInterval(refrescar, 5000);
    });
    function refrescar(){
      $("#alertaTareas").load('../../alerta_Tareas_Nuevas.php?user='+<?=$_SESSION['PKUsuario'];?>+'&ruta='+'<?=$ruta;?>');
    }

    $(document).ready(function() {
      $("#cmbProveedor").chosen();
    });
    $(document).ready(function() {
      $("#cmbProducto").chosen();
    });
    $(document).ready(function() {
      $("#cmbDireccionEnvio").chosen();
    });
    $('#cmbProducto').change(function(){
      var valor = $('#cmbProducto').val();
      var cadena = "id="+valor;
      $.ajax({
        type:'POST',
        url:'obtener_Precio.php',
        data: cadena,
        success:function(r){
          $('#txtPrecioUnitario').val(r);
        }
      });
    });


    $(document).ready(function() {
      $('#agregarProducto').click(function(){
        var idproducto = $('#cmbProducto').val();
        var producto = $("#cmbProducto").children("option:selected").text();
        var precio = parseFloat($('#txtPrecioUnitario').val());
        var cantidad = parseInt($('.txtCantidad').val());
        var Piezas_old, request, subf =0;
        var aux = numeral($('#Subtotal').html());
        var temp = parseFloat(aux.value());
        var x = producto.split(' ');
        var unidad = "";
        //for para obtener unidad de medida
        for (var i = 0; i < x.length; i++) {
          unidad = x[x.length-1];
        }
        //inicio alertas
        var alerta = "";
        if(!$('#cmbProducto').val()){
          alerta ='<div class="alert alert-warning" role="alert">'+
                  'Debe seleccionar un producto'+
                  '</div>';
        }else if(!$('#txtPrecioUnitario').val()){
          alerta ='<div class="alert alert-warning" role="alert">'+
                  'Debe ingresar por lo menos un producto'+
                  '</div>';
        }else if(!$('.txtCantidad').val()){
          alerta ='<div class="alert alert-warning" role="alert">'+
                  'Debe ingresar la cantidad'+
                  '</div>';
        }else if($('.txtCantidad').val() < 1){
          alerta ='<div class="alert alert-warning" role="alert">'+
                  'Debe ingresar una cantidad mayor a cero'+
                  '</div>';
        }else{
          //insercion de datos a tabla
          if($('#producto_' + idproducto).length){
            piezas_old = parseInt($("#cantidad_" + idproducto).html());
            aux = numeral($("#importe_" + idproducto).html());
            importe_old = parseFloat(aux.value());
            cantidad = cantidad + piezas_old;

            importe = parseFloat(precio * cantidad);
            $('#producto_' + idproducto).empty();
            request = '<td id="descripcion_'+idproducto+'">'+producto+'</td>'+
            '<input type="hidden" name="txtIdProductos[]" value="'+idproducto+'">'+
            '<input type="hidden" name="txtDescripciones[]" value="'+producto+'">'+
            '<td id="cantidad_'+idproducto+'">'+cantidad+'</td>'+
            '<input type="hidden" name="txtCantidades[]" value="'+cantidad+'">'+
            '<td id="unidad_'+idproducto+'">'+unidad+'</td>'+
            '<input type="hidden" name="txtUnidades[]" value="'+unidad+'">'+
            '<td id="precio_'+idproducto+'">$'+precio.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')+'</td>'+
            '<input type="hidden" name="txtPrecios[]" value="'+precio+'">'+
            '<td id="importe_'+idproducto+'">$'+importe.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')+'</td>'+
            '<input type="hidden" name="txtImportes[]" value="'+importe+'">'+
            '<td><button type="button" class="btn eliminarProductos" id="'+idproducto+'" value="'+idproducto+'">X</button></td>';

            $('#producto_' + idproducto).append(request);
            subtotal = (subtotal - importe_old) + importe;
          }else{
            importe = parseFloat(precio * cantidad);
            request = '<tr id="producto_'+idproducto+'" style="text-align:center">'+
            '<input type="hidden" name="txtIdProductos[]" value="'+idproducto+'">'+
            '<td id="descripcion_'+idproducto+'">'+producto+'</td>'+
            '<input type="hidden" name="txtDescripciones[]" id="idProducto" value="'+idproducto+'">'+
            '<td id="cantidad_'+idproducto+'">'+cantidad+'</td>'+
            '<input type="hidden" name="txtCantidades[]" value="'+cantidad+'">'+
            '<td id="unidad_'+idproducto+'">'+unidad+'</td>'+
            '<input type="hidden" name="txtUnidades[]" value="'+unidad+'">'+
            '<td id="precio_'+idproducto+'">$'+precio.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')+'</td>'+
            '<input type="hidden" name="txtPrecios[]" value="'+precio+'">'+
            '<td id="importe_'+idproducto+'">$'+importe.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')+'</td>'+
            '<input type="hidden" name="txtImportes[]" value="'+importe+'">'+
            '<th><button type="button" class="btn eliminarProductos" id="'+idproducto+'" value="'+idproducto+'">X</button></th>'+
            '</tr>';

            $('#lstProductos').append(request);
            subtotal += importe;
          }
          //termina insercion de datos
        }
        $('#alertas').html(alerta);
          //fin alertas

        //calculo de iva y total neto
        $('#IVA').empty();
        aux = numeral($('#IVA').html());
        iva = aux.value();
        iva += subtotal * 0.16;
        total = subtotal + iva;

        //se muestra los resultados en pantalla
        $('#Subtotal').empty();
        $('#Subtotal').html(subtotal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
        $('#IVA').empty();
        $('#IVA').html(iva.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
        $('#Total').empty();
        $('#Total').html(total.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));

        $("#cmbProducto").val('').trigger("chosen:updated");
        $('#txtPrecioUnitario').val('');
        $('.txtCantidad').val('');


      });
    });

    $(document).on("click",".eliminarProductos",function(){
      var idProducto = this.id;
      var aux = numeral($('#Subtotal').html());

      var subtotal = aux.value();
      aux = numeral($('#precio_'+idProducto).html());
      var precio = aux.value();
      aux = numeral($('#cantidad_'+idProducto).html());
      var cantidad = aux.value();
      subtotal = parseFloat(subtotal)-(precio * cantidad);

      aux = numeral($('#IVA').html());
      var iva = aux.value();
      iva = parseFloat(iva)-(precio * cantidad * 0.16);

      var total = aux.value();
      total = (subtotal + iva);

      $('#Subtotal').empty();
      $('#Subtotal').html(subtotal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
      $('#IVA').empty();
      $('#IVA').html(iva.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
      $('#Total').empty()
      $('#Total').html(total.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
      $('#producto_'+idProducto).remove();
    });

    $(document).ready(function(){
      var alerta = "";

      var ref1 = <?=json_encode($ref)?>;

      $('#btnAgregar').click(function(){
        var count = 0;
        for(var i=0;i<ref1.length;i++){
            if(ref1[i] == $('#txtReferencia').val()){
              count++;
            }
        }
        if(!$('#txtReferencia').val()){
          alerta ='<div class="alert alert-warning" role="alert">'+
                  'Debe ingresar una referencia'+
                  '</div>';
        }else if(!$('#txtFechaEstimada').val()){
          alerta ='<div class="alert alert-warning" role="alert">'+
                  'Debe ingresar una fecha estimada de entrega'+
                  '</div>';

        }else if(!$('#cmbProveedor').val()){
          alerta ='<div class="alert alert-warning" role="alert">'+
                  'Debe ingresar un proveedor'+
                  '</div>';
        }else if(!$('#cmbDireccionEnvio').val()){
          alerta ='<div class="alert alert-warning" role="alert">'+
                  'Debe ingresar una dirección de entrega'+
                  '</div>';
        }else if($('#lstProductos > tr').length < 1){
          alerta ='<div class="alert alert-warning" role="alert">'+
                  'Debe ingresar por lo menos un producto'+
                  '</div>';
        }else if(count > 0){
          alerta = '<div class="alert alert-warning" role="alert">'+
                  'Referencia duplicada. Debe de ingresar una referencia diferente.'+
                  '</div>';
        }else{
          $.ajax({
            type: 'POST',
            url: 'darAlta_OrdenCompra.php',
            data: $('#frmOrdenCompra').serialize(),
            success:function(r){
                $('#modal_envio').load('modal_envio.php?id='+$('#cmbProveedor').val()+'&txtId='+r+'&estatus=0', function(){
                  $('#datos_envio').modal('show');
                });
            }
          });

        }
        $('#alertas').html(alerta);
      });

    });
  </script>
  <script> var ruta = "../../";</script>
  <script src="../../../js/sb-admin-2.min.js"></script>

</body>

</html>
