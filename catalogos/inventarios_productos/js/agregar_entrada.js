var _permissionsOC = { 
  read: 0,
  add: 0,
  edit: 0,
  delete: 0,
  export: 0
}

var _global = {
  idEntradaED: 0
}

$(document).ready(function(){

  //loadTypeEntries('','cmbTipoEntrada');

  //loadProductosOrderPurchase(0);

  /* new SlimSelect({
    select: '#cmbTipoEntrada',
    deselectLabel: '<span class="">✖</span>',
  }); */

  $('#agregar_ProductoEDBranch').on('shown.bs.modal', cargarTablaProductosEDBranch);
  $('#agregar_ProductoEDProvider').on('shown.bs.modal', cargarTablaProductosEDProvider);
  $('#agregar_ProductoEDCustomer').on('shown.bs.modal', cargarTablaProductosEDCustomer);

  deleteEntradaDirectaTemp();

  $("#div2").html(`<div class="directEntry-disabled">
                      <label for="cmbSucursalEntrada">Sucursal de destino*:</label>
                      <div class="input-group">
                        <select name="cmbSucursalEntrada" id="cmbSucursalEntrada" required></select>
                        <div class="invalid-feedback" id="invalid-sucursalEntrada">La entrada debe de tener una sucursal de destino.</div>
                      </div>
                    </div>`);

    $("#div3").html(`<div class="originDirectEntry-disabled">
                      <label for="cmbTypeDirectEntry">Tipo de entrada directa*:</label>
                      <select name="cmbTypeDirectEntry" id="cmbTypeDirectEntry" required>
                        
                      </select>
                    </div>`);

    $("#div4").html(`<span id="div19">
                    </span>
                    `);

    $("#div5").html(`<div class="row">
                      <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-6">
                        <span id="div20">
                        </span>
                      </div>
                      <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-6">
                        <span id="div21">
                        </span>
                      </div>
                      <div class="col-xl-6 col-lg-6 col-md-4 col-sm-6 col-xs-6">
                        <span id="div22">
                        </span>
                      </div>
                    </div>`);

    $("#div6").html(``);

    $("#div7").html(``);

    $("#div8").html(``);

    $("#div9").html(`<span id="div25">
                    </span>
                    `);

    $("#div10").html(``);
  
    loadBranchEntry("","cmbSucursalEntrada");

    new SlimSelect({
      select: '#cmbSucursalEntrada',
      deselectLabel: '<span class="">✖</span>',
    });

    new SlimSelect({
      select: '#cmbTypeDirectEntry',
      deselectLabel: '<span class="">✖</span>',
    });

    $('.data-container .ocproviders-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
    $('.data-container .branchOrigin-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
    $('.data-container .typeOrderPedido-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});  
    $('.data-container .orderPedido-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
    $('.data-container .brancheDestination-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
    $('.data-container .directEntry-disabled').css({'display':'inline-block','visibility': 'visible','opacity': '1','animation': 'fade 1s'});
});

//Cargar Combo de Tipo Entrada
function loadTypeEntries(data,input){
  var html ='<option value="" selected>Seleccione un tipo de entrada</option>';
  var selected;
  $.ajax({
    url:"../../php/funciones.php",
  	data:{clase:"get_data", funcion:"get_typeEntries"},
  	dataType:"json",
    success:function(respuesta){
      console.log("respuesta tipo de entradas combo:",respuesta);

      $.each(respuesta,function(i){
        if(data === respuesta[i].PKTipoEntrada){
          selected = 'selected';
        }else{
          selected = '';
        }
        html += '<option value="'+respuesta[i].PKTipoEntrada+'" '+selected+'>'+respuesta[i].TipoEntrada+'</option>';
      });

      $('#'+input+'').html(html);
    },
    error:function(error){
      console.log(error);
    }

  });
}

/*begins combo Tipo Entrada selections*/
/* $(document).on('change','#cmbTipoEntrada',function(){
  $('.data-container .purchases-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
  $('.data-container .providers-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
  $('.data-container .products-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
  $('.data-container .parcial-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
  $('.data-container .branchOrigin-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
  $('.data-container .typeOrderPedido-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
  $('.data-container .orderPedido-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
  $('.data-container .brancheDestination-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
  $('.data-container .directEntry-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
  $('.data-container .originDirectEntry-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});

  if($('#cmbTipoEntrada').val() === ""){
    $('.data-container .purchases-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
    $('.data-container .providers-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
    $('.data-container .products-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
    $('.data-container .parcial-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
    $('.data-container .branchOrigin-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
    $('.data-container .typeOrderPedido-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
    $('.data-container .orderPedido-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
    $('.data-container .brancheDestination-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
    $('.data-container .directEntry-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
    $('.data-container .originDirectEntry-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
  }

  if($('#cmbTipoEntrada').val() === "1"){
    //loadPurchaseOrder("","cmbOrdenCompra");
    $("#div2").html(`<div class="ocproviders-disabled">
                      <label for="cmbProveedor">Proveedor:</label>
                      <select name="cmbProveedor" id="cmbProveedor" required></select>
                    </div>`);

    $("#div3").html(`<div class="purchases-disabled">
                      <label for="cmbOrdenCompra">Orden de compra:</label>
                      <select name="cmbOrdenCompra" id="cmbOrdenCompra" required></select>
                    </div>`);

    $("#div4").html(`<div class="providers-disabled">
                      <label for="cmbSucursal">Sucursal:</label>
                      <select name="cmbSucursal" id="cmbSucursal" required></select>
                    </div>`);

    $("#div5").html(`<div class="row">
                      <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-6">
                        <div class="providers-disabled">
                          <label for="usr">No. de documento*:</label>
                          <div class="input-group">
                            <input required class="form-control alphaNumeric-only" type="text" name="txtSerie" id="txtSerie" placeholder="Serie" style="float:left;" onchange="validEmptyInput('txtSerie', 'invalid-serie', 'La entrada debe de tener número de serie.')">
                            <div class="invalid-feedback" id="invalid-serie">La entrada debe de tener número de serie.</div>
                          </div>
                        </div>
                      </div>
                      <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-6">
                        <div class="providers-disabled">
                          <label for="usr">Folio de factura*:</label>
                          <div class="input-group">
                            <input required class="form-control alphaNumeric-only" type="text" name="txtNoDocumento" id="txtNoDocumento" placeholder="Folio" style="float:left;" onchange="validEmptyInput('txtNoDocumento', 'invalid-noDocumento', 'La entrada debe de tener un número de folio.')">
                            <div class="invalid-feedback" id="invalid-noDocumento">La entrada debe de tener número de serie.</div>
                          </div>
                        </div>
                      </div>
                      <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-xs-6">
                        <div class="providers-disabled">
                          <label for="usr">Subtotal*:</label>
                          <div class="input-group">
                            <input required class="form-control numericDecimal-only" type="number" name="txtSubtotal" id="txtSubtotal" placeholder="Ej. 1000.00" style="float:left;" onchange="validEmptyInput('txtSubtotal', 'invalid-subtotal', 'La entrada debe de tener subtotal.')">
                            <div class="invalid-feedback" id="invalid-subtotal">La entrada debe de tener subtotal.</div>
                          </div>
                        </div>
                      </div>
                      <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-xs-6">
                        <div class="providers-disabled">
                          <label for="usr">IVA (Monto):</label>
                          <div class="input-group">
                            <input class="form-control numericDecimal-only" type="number" name="txtIva" id="txtIva" placeholder="Ej. 1000.00" style="float:left;" value="0" onchange="validEmptyInput('txtIva', 'invalid-iva', 'La entrada debe de tener IVA.')">
                            <div class="invalid-feedback" id="invalid-iva">La entrada debe de tener IVA.</div>
                          </div>
                        </div>
                      </div>
                      <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-xs-6">
                        <div class="providers-disabled">
                          <label for="usr">IEPS (Monto):</label>
                          <div class="input-group">
                            <input class="form-control numericDecimal-only" type="number" name="txtIEPS" id="txtIEPS" placeholder="Ej. 1000.00" style="float:left;" value="0" onchange="validEmptyInput('txtIEPS', 'invalid-ieps', 'La entrada debe de tener IEPS.')">
                            <div class="invalid-feedback" id="invalid-ieps">La entrada debe de tener IEPS.</div>
                          </div>
                        </div>
                      </div>
                    </div>`);

    $("#div6").html(`<div class="row">
                      <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-xs-6">
                        <div class="providers-disabled">
                          <label for="usr">Importe factura*:</label>
                          <div class="input-group">
                            <input required class="form-control numericDecimal-only" type="number" name="txtImporte" id="txtImporte" placeholder="Ej. 1000.00" style="float:left;" onchange="validEmptyInput('txtImporte', 'invalid-importe', 'La entrada debe de tener importe.')">
                            <div class="invalid-feedback" id="invalid-importe">La entrada debe de tener importe.</div>
                          </div>
                        </div>
                      </div>
                      <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-xs-6">
                        <div class="providers-disabled">
                          <label for="usr">Descuento (Monto):</label>
                          <div class="input-group">
                            <input class="form-control numericDecimal-only" type="number" name="txtDescuento" id="txtDescuento" placeholder="Ej. 1000.00" style="float:left;">
                          </div>
                        </div>
                      </div>
                      <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-xs-6">
                        <div class="providers-disabled">
                          <label for="usr">Fecha de factura*:</label>
                          <div class="input-group">
                            <input required class="form-control" type="date" name="txtFechaFactura" id="txtFechaFactura" style="float:left;" onchange="validEmptyInput('txtFechaFactura', 'invalid-fechaFactura', 'La entrada debe de tener una fecha de factura.')">
                            <div class="invalid-feedback" id="invalid-fechaFactura">La entrada debe de una fecha de factura.</div>
                          </div>
                        </div>
                      </div>
                    </div>`);

    $("#div7").html(`<div class="row">
                      <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-xs-6" style="margin-left:20px!important">
                        <div class="providers-disabled">
                          <input class="form-check-input" type="checkbox" id="cbxRemision" name="cbxRemision">
                          <label class="form-check-label" for="cbxRemision">Remisión</label>
                        </div>
                      </div>
                    </div>`);

    $("#div8").html(`<div class="row">
                      <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="providers-disabled">
                          <label for="">Notas:</label>
                          <textarea class="form-control" name="txaNotaEntrada" id="txaNotaEntrada" placeholder="Escribe las notas aquí..." rows="2" cols="200"></textarea>
                        </div>
                      </div>
                    </div>`);

    $("#div9").html(`<div class="products-disabled">
                    <table class="table-sm tblCoti dataTable no-footer" id="tblProductosOrdenCompraParcial" width="100%" cellspacing="0">
                      <thead>
                        <tr>
                          <th>ID</th>
                          <th>Clave/Producto</th>
                          <th>Cantidad recibida</th>
                          <th>Cantidad posible a entrar</th>
                          <th>Unidad de medida</th>
                          <th>Lote</th>
                          <th>Serie</th>
                          <th>Fecha de caducidad</th>
                          <th>Precio unitario</th>
                          <th>Impuestos</th>
                          <th>Importe</th>
                        </tr>
                      </thead>
                    </table>
                    <table class="table opacidad">
                      <tfoot>
                        <tr>
                          <th style="text-align: right;">Subtotal:</th>
                          <th style="text-align: right; width:400px!important">$ <span id="Subtotal">0.00</span>
                          </th>
                          <th style="width:60px;"></th>
                        </tr>
                        <tr>
                          <th style="text-align: right;">Impuestos:</th>
                          <th id="impuestos"></th>
                          <th></th>
                        </tr>
                        <tr class="total redondearAbajoIzq">
                          <th style="text-align: right;" class="redondearAbajoIzq">Total:</th>
                          <th style="text-align: right;">$ <span id="Total">0.00</span></th>
                          <th></th>
                        </tr>
                      </tfoot>
                    </table>
                    <label for="">Los productos donde su cantidad sea igual a 0 o su cantidad en sumatoria con el resto sea mayor a la cantidad pedida no serán registrados.</label>
                    <div class="">
                      <div class="products-disabled">
                        <br>
                        <br>
                        <a class="btn-custom btn-custom--blue float-right" id="btnAgregarEntradaOC" style="margin-right: 10px!important">Registrar entrada</a>
                        <span id="sCerrarOC">
                        </span>
                      </div>
                    </div>
                  </div>`);
                    
    $("#div10").html(``);

    new SlimSelect({
      select: '#cmbOrdenCompra',
      deselectLabel: '<span class="">✖</span>',
    });
  
    new SlimSelect({
      select: '#cmbProveedor',
      deselectLabel: '<span class="">✖</span>',
    });
  
    new SlimSelect({
      select: '#cmbSucursal',
      deselectLabel: '<span class="">✖</span>',
    });

    loadProvider("","cmbProveedor");
    $('.data-container .ocproviders-disabled').css({'display':'inline-block','visibility': 'visible','opacity': '1','animation': 'fade 1s'});
    $('.data-container .branchOrigin-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
    $('.data-container .typeOrderPedido-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});  
    $('.data-container .orderPedido-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
    $('.data-container .brancheDestination-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});


  }else if($('#cmbTipoEntrada').val() === "2"){
    
    $("#div2").html(`<div class="brancheDestination-disabled">
                      <label for="cmbSucursalDestino">Sucursal de destino*:</label>
                      <div class="input-group">
                        <select name="cmbSucursalDestino" id="cmbSucursalDestino" required></select>
                        <div class="invalid-feedback" id="invalid-sucursalDestino">La salida debe de tener una sucursal de destino.</div>
                      </div>
                    </div>`);

    $("#div3").html(`<div class="typeOrderPedido-disabled">
                      <label for="cmbTypeOrderPedido">Tipo de pedido*:</label>
                      <select name="cmbTypeOrderPedido" id="cmbTypeOrderPedido" required>
                        
                      </select>
                    </div>`);
    
    $("#div4").html(`<div class="orderPedido-disabled">
                      <label for="cmbOrderPedido">Pedido*:</label>
                      <div class="input-group">
                        <select name="cmbOrderPedido" id="cmbOrderPedido" required></select>
                        <div class="invalid-feedback" id="invalid-ordenPedido">La salida debe de tener un pedido.</div>
                      </div>
                    </div>`);
                    
    $("#div5").html(`<div class="branchOrigin-disabled">
                      <label for="cmbSucursalOrigen">Sucursal de origen*:</label>
                      <select name="cmbSucursalOrigen" id="cmbSucursalOrigen" required></select>
                    </div>`);
    $("#div6").html(``);
    $("#div7").html(``);
    $("#div8").html(``);

    $("#div9").html(`<div class="branchOrigin-disabled">
                      <table class="table opacidad" id="tblProductosTraspaso" width="100%" cellspacing="0">
                        <thead>
                          <tr>
                            <th>ID</th>
                            <th>Clave/Producto</th>
                            <th>Cantidad</th>
                            <th>Unidad de medida</th>
                            <th>Lote</th>
                            <th>Serie</th>
                            <th>Fecha de caducidad</th>
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
                            <th id="impuestos"><span id="TotalTras">0</span></th>
                            <th></th>
                          </tr>
                          <tr class="total redondearAbajoIzq">
                            <th style="text-align: right;" class="redondearAbajoIzq"></th>
                            <th style="text-align: right;"></th>
                            <th></th>
                          </tr>
                        </tfoot>
                      </table>
                      <label for="">Los productos donde su cantidad sea igual a 0 o su cantidad en sumatoria con el resto sea mayor a la cantidad pedida no serán registrados.</label>
                      <div class="">
                        <div class="products-disabled">
                          <br>
                          <br>
                          <a class="btn-custom btn-custom--blue float-right" id="btnAgregarEntradaOC" style="margin-right: 10px!important">Registrar entrada</a>
                          <span id="sCerrarOC">
                          </span>
                        </div>
                        <div class="branchOrigin-disabled">
                          <br>
                          <br>
                          <a class="btn-custom btn-custom--blue float-right" id="btnAgregarEntradaTraspaso" style="margin-right: 10px!important">Registrar entrada</a>
                          <span id="sCerrarTraspaso">
                          </span>
                        </div>
                      </div>
                    </div>`);

    $("#div10").html(``);

    new SlimSelect({
      select: '#cmbSucursalDestino',
      deselectLabel: '<span class="">✖</span>',
    });

    new SlimSelect({
      select: '#cmbSucursalOrigen',
      deselectLabel: '<span class="">✖</span>',
    });
  
    new SlimSelect({
      select: '#cmbTypeOrderPedido',
      deselectLabel: '<span class="">✖</span>',
    });
  
    new SlimSelect({
      select: '#cmbOrderPedido',
      deselectLabel: '<span class="">✖</span>',
    });

    loadBranchDestination($('#cmbOrderPedido').val(),"cmbSucursalDestino");
    $('.data-container .brancheDestination-disabled').css({'display':'inline-block','visibility': 'visible','opacity': '1','animation': 'fade 1s'});
    $('.data-container .ocproviders-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
  }else if($('#cmbTipoEntrada').val() === "4"){

    $("#div2").html(`<div class="directEntry-disabled">
                      <label for="cmbSucursalEntrada">Sucursal de destino*:</label>
                      <div class="input-group">
                        <select name="cmbSucursalEntrada" id="cmbSucursalEntrada" required></select>
                        <div class="invalid-feedback" id="invalid-sucursalEntrada">La entrada debe de tener una sucursal de destino.</div>
                      </div>
                    </div>`);

    $("#div3").html(`<div class="originDirectEntry-disabled">
                      <label for="cmbTypeDirectEntry">Tipo de entrada directa*:</label>
                      <select name="cmbTypeDirectEntry" id="cmbTypeDirectEntry" required>
                        
                      </select>
                    </div>`);

    $("#div4").html(`<span id="div19">
                    </span>
                    `);

    $("#div5").html(`<div class="row">
                      <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-6">
                        <span id="div20">
                        </span>
                      </div>
                      <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-6">
                        <span id="div21">
                        </span>
                      </div>
                      <div class="col-xl-6 col-lg-6 col-md-4 col-sm-6 col-xs-6">
                        <span id="div22">
                        </span>
                      </div>
                    </div>`);

    $("#div6").html(``);

    $("#div7").html(``);

    $("#div8").html(``);

    $("#div9").html(`<span id="div25">
                    </span>
                    `);

    $("#div10").html(``);
  
    loadBranchEntry("","cmbSucursalEntrada");

    new SlimSelect({
      select: '#cmbSucursalEntrada',
      deselectLabel: '<span class="">✖</span>',
    });

    new SlimSelect({
      select: '#cmbTypeDirectEntry',
      deselectLabel: '<span class="">✖</span>',
    });

    $('.data-container .ocproviders-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
    $('.data-container .branchOrigin-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
    $('.data-container .typeOrderPedido-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});  
    $('.data-container .orderPedido-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
    $('.data-container .brancheDestination-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
    $('.data-container .directEntry-disabled').css({'display':'inline-block','visibility': 'visible','opacity': '1','animation': 'fade 1s'});
  }else{
    $('.data-container .ocproviders-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
    $('.data-container .purchases-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
    $('.data-container .providers-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
    $('.data-container .products-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
    $('.data-container .parcial-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
    $('.data-container .branchOrigin-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});  
    $('.data-container .typeOrderPedido-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
    $('.data-container .orderPedido-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
    $('.data-container .brancheDestination-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
  }
}); */

/*begins combo Orden compra selections*/
$(document).on('change','#cmbOrderPedido',function(){
  $('.data-container .branchOrigin-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});

  if($('#cmbOrderPedido').val() === ""){
    $('.data-container .branchOrigin-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
  }else{
    $('.data-container .branchOrigin-disabled').css({'display':'inline-block','visibility': 'visible','opacity': '1','animation': 'fade 1s'});
  }

  deleteEntradaTraspasoTemp();

  loadBranchOrigin($("#cmbOrderPedido").val(),"cmbSucursalOrigen");
  
  //saveEntradaTraspaso($("#cmbOrderPedido").val());
});

/*begins combo Proveedores selections*/
$(document).on('change','#cmbProveedor',function(){
  $('.data-container .purchases-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
  $('.data-container .providers-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
  $('.data-container .products-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
  $('.data-container .parcial-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
  
  console.log($('#cmbProveedor').val());


  if($('#cmbProveedor').val() === ""){
    $('.data-container .purchases-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
    $('.data-container .providers-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
    $('.data-container .products-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
    $('.data-container .parcial-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
  }else{
    loadPurchaseOrder($('#cmbProveedor').val(),"cmbOrdenCompra");
    $('.data-container .purchases-disabled').css({'display':'inline-block','visibility': 'visible','opacity': '1','animation': 'fade 1s'});
  }
  
});

/*begins combo Orden compra selections*/
$(document).on('change','#cmbOrdenCompra',function(){
  $('.data-container .providers-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
  $('.data-container .products-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
  deleteEntradaOCTemp();

  if($('#cmbOrdenCompra').val() === ""){
    $('.data-container .providers-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
    $('.data-container .products-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
  }else{
    $('.data-container .providers-disabled').css({'display':'inline-block','visibility': 'visible','opacity': '1','animation': 'fade 1s'});
    $('.data-container .products-disabled').css({'display':'inline-block','visibility': 'visible','opacity': '1','animation': 'fade 1s'});
  }

  //loadProvider($('#cmbOrdenCompra').val(),"cmbProveedor");
  loadBranch($('#cmbOrdenCompra').val(),"cmbSucursal");
  loadProducts($('#cmbOrdenCompra').val(),"cmbProductoOrdenCompra");
  
  validate_Permissions(22);

  saveEntradaOCTemp($('#cmbOrdenCompra').val());

  $("#txtNoDocumento").val("");
  $("#txtSerie").val("");
  $("#txtSubtotal").val("0.00");
  $("#txtIva").val("0.00");
  $("#txtIEPS").val("0.00");
  $("#txtImporte").val("0.00");
  $("#txtDescuento").val("0.00");
  $("#txtFechaFactura").val("");
  $("#cbxRemision").prop("checked", false);
  $("#txaNotaEntrada").val("");
  $("#txaNotaEntrada").prop("required", false) 

  $("#invalid-noDocumento").css("display", "none");
  $("#invalid-noDocumento").text("");
  $("#txtNoDocumento").removeClass("is-invalid");
  
  $("#invalid-serie").css("display", "none");
  $("#invalid-serie").text("");
  $("#txtSerie").removeClass("is-invalid");

  $("#invalid-subtotal").css("display", "none");
  $("#invalid-subtotal").text("");
  $("#txtSubtotal").removeClass("is-invalid");

  $("#invalid-importe").css("display", "none");
  $("#invalid-importe").text("");
  $("#txtImporte").removeClass("is-invalid");

  $("#invalid-fechaFactura").css("display", "none");
  $("#invalid-fechaFactura").text("");
  $("#txtFechaFactura").removeClass("is-invalid");
  
});

/*begins combo orden pedido selections*/
$(document).on('change','#cmbSucursalDestino',function(){
  $('.data-container .typeOrderPedido-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
  $('.data-container .orderPedido-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
  $('.data-container .branchOrigin-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});  

  if($('#cmbSucursalDestino').val() === ""){
    $('.data-container .typeOrderPedido-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
    $('.data-container .orderPedido-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
    $('.data-container .branchOrigin-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
  }else{
    $('.data-container .typeOrderPedido-disabled').css({'display':'inline-block','visibility': 'visible','opacity': '1','animation': 'fade 1s'});
  }

  loadTypeOrderPedido("","cmbTypeOrderPedido");
});

$(document).on('change','#cmbTypeOrderPedido',function(){
  $('.data-container .orderPedido-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
  $('.data-container .branchOrigin-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});

  if($('#cmbTypeOrderPedido').val() === ""){
    $('.data-container .orderPedido-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
    $('.data-container .branchOrigin-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
  }else if ($('#cmbTypeOrderPedido').val() === "1"){
    $('.data-container .orderPedido-disabled').css({'display':'inline-block','visibility': 'visible','opacity': '1','animation': 'fade 1s'});
  }else{
    $('.data-container .orderPedido-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
    $('.data-container .branchOrigin-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
  }

  loadOrderPedido($('#cmbSucursalDestino').val(),"cmbOrderPedido");

});

$(document).on('change','#cmbSucursalEntrada',function(){
  $('.data-container .originDirectEntry-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});  
  $('.data-container .entryDirectBranch-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
  $('.data-container .entryDirectBranchContent-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
  $('.data-container .entryDirectProvider-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
  $('.data-container .entryDirectProviderContent-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
  $('.data-container .entryDirectProviderContent-disabledED').css({'display':'none','opacity': '0','visibility': 'hidden'});
  $("#div66").html('');
  $('.data-container .entryDirectCustomer-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
  $('.data-container .entryDirectCustomerContent-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});

  if($('#cmbSucursalEntrada').val() === ""){
    $('.data-container .originDirectEntry-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
    $('.data-container .entryDirectBranch-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
    $('.data-container .entryDirectBranchContent-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
    $('.data-container .entryDirectProvider-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
    $('.data-container .entryDirectProviderContent-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
    $('.data-container .entryDirectProviderContent-disabledED').css({'display':'none','opacity': '0','visibility': 'hidden'});
    $("#div66").html('');
    $('.data-container .entryDirectCustomer-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
    $('.data-container .entryDirectCustomerContent-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
  }else{
    loadTypeDirectEntry("","cmbTypeDirectEntry");
    $('.data-container .originDirectEntry-disabled').css({'display':'inline-block','visibility': 'visible','opacity': '1','animation': 'fade 1s'});
  }
});

$(document).on('change','#cmbTypeDirectEntry',function(){
  $('.data-container .entryDirectBranch-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
  $('.data-container .entryDirectBranchContent-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
  $('.data-container .entryDirectProvider-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
  $('.data-container .entryDirectProviderContent-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
  $('.data-container .entryDirectProviderContent-disabledED').css({'display':'none','opacity': '0','visibility': 'hidden'});
  $("#div66").html('');
  $('.data-container .entryDirectCustomer-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});

  if($('#cmbTypeDirectEntry').val() === ""){
    $('.data-container .entryDirectBranch-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
    $('.data-container .entryDirectBranchContent-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
    $('.data-container .entryDirectProviderContent-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
    $('.data-container .entryDirectProviderContent-disabledED').css({'display':'none','opacity': '0','visibility': 'hidden'});
    $("#div66").html('');
    $('.data-container .entryDirectProvider-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
    $('.data-container .entryDirectCustomer-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
  }/* else if ($('#cmbTypeDirectEntry').val() === "1"){
    
    html19 = `<div class="entryDirectBranch-disabled">
                <label for="cmbSucursalOrigenED">Sucursal de origen*:</label>
                <div class="input-group">
                  <select name="cmbSucursalOrigenED" id="cmbSucursalOrigenED" required></select>
                  <div class="invalid-feedback" id="invalid-sucursalOrigenED">La entrada debe de tener una sucursal de origen.</div>
                </div>
              </div>`;
    $("#div19").html(html19);
    
    $('.data-container .entryDirectBranch-disabled').css({'display':'inline-block','visibility': 'visible','opacity': '1','animation': 'fade 1s'});
    $('.data-container .entryDirectProvider-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
    $('.data-container .entryDirectCustomer-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});

    html20 = `<div class="entryDirectBranchContent-disabled">
                <label for="usr">Folio de entrada*:</label>
                <div class="input-group">
                  <input required class="form-control alphaNumeric-only" type="text" name="txtFolioEntradaED" id="txtFolioEntradaED" placeholder="ED000001" style="float:left;" onchange="validEmptyInput('txtFolioEntradaED', 'invalid-folioEntradaED', 'La entrada debe de tener un folio.')" readonly>
                  <div class="invalid-feedback" id="invalid-folioEntradaED">La entrada debe de tener un folio.</div>
                </div>
              </div>`;
    
    html21 = `<div class="entryDirectBranchContent-disabled">
                <label for="usr">Referencia*:</label>
                <div class="input-group">
                  <input required class="form-control alphaNumeric-only" type="text" name="txtReferenciaED" id="txtReferenciaED" placeholder="Folio" style="float:left;" onchange="validEmptyInput('txtReferenciaED', 'invalid-referenciaED', 'La entrada debe de tener un número de referencia.')">
                  <div class="invalid-feedback" id="invalid-referenciaED">La entrada debe de tener número de referencia.</div>
                </div>
              </div>`;

    html22 = `<div class="entryDirectBranchContent-disabled">
                <label for="usr">Notas:</label>
                <textarea class="form-control" name="txtNotaEntradaED" id="txtNotaEntradaED" placeholder="Escribe las notas aquí..." rows="2" cols="200"></textarea>
              </div>`;

    html23 = ``;
    html24 = ``;
    html25 = `<div class="form-group entryDirectBranchContent-disabled">
                <div class="row">
                  <div class="col-lg-6">
                    <i data-toggle=\"modal\" data-target=\"#agregar_ProductoEDBranch\"><img src=\"../../../../img/timdesk/ICONO AGREGAR2_Mesa de trabajo 1.svg\" width="30px" id="addProducto"></i>
                    <label>  Añadir producto</label>
                  </div>
                </div>
              </div>
              <div class="entryDirectBranchContent-disabled">
                <table class="table opacidad" id="tblProductosEntradaDCSucursal" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Clave/Producto</th>
                      <th>Cantidad a entrar</th>
                      <th>Unidad de medida</th>
                      <th>Lote</th>
                      <th>Serie</th>
                      <th>Fecha de caducidad</th>
                      <th></th>
                    </tr>
                  </thead>
                </table>
                <br><br>
                <label for="">Los productos donde su cantidad sea igual a 0 no serán registrados.</label>
                <div class="">
                  <div class="entryDirectBranchContent-disabled">
                    <br>
                    <br>
                    <a class="btn-custom btn-custom--blue float-right" id="btnAgregarEntradaED" style="margin-right: 10px!important">Registrar entrada</a>
                    <span id="sCerrarOC">
                    </span>
                  </div>
                </div>
              </div>`;
            
    $("#div20").html(html20);
    $("#div21").html(html21);
    $("#div22").html(html22);
    $("#div6").html(html23);
    $("#div7").html(html24);
    $("#div25").html(html25);

    new SlimSelect({
      select: '#cmbSucursalOrigenED',
      deselectLabel: '<span class="">✖</span>',
    });

    loadBranchEntryOriginED("","cmbSucursalOrigenED");
  } */else if ($('#cmbTypeDirectEntry').val() === "2"){
    html19 = `<div class="entryDirectProvider-disabled">
                <label for="cmbProveedorED">Proveedor*:</label>
                <div class="input-group">
                  <select name="cmbProveedorED" id="cmbProveedorED" required></select>
                  <div class="invalid-feedback" id="invalid-proveedorED">La entrada debe de tener un proveedor.</div>
                </div>
              </div>`;

    $("#div19").html(html19); 

    $('.data-container .entryDirectProvider-disabled').css({'display':'inline-block','visibility': 'visible','opacity': '1','animation': 'fade 1s'});
    $('.data-container .entryDirectProviderContent-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
    $('.data-container .entryDirectProviderContent-disabledED').css({'display':'none','opacity': '0','visibility': 'hidden'});
    $("#div66").html('');
    $('.data-container .entryDirectBranch-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
    $('.data-container .entryDirectBranchContent-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
    $('.data-container .entryDirectCustomer-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});

    html20 = `<div class="entryDirectProviderContent-disabled">
                <h2>
                <label for="usr" style="color: var(--azul-mas-oscuro);"><b>Folio de entrada:</b></label>
                <div id="usr">
                  <b><span id="txtFolioEntradaProviderED"></span></b>
                </div>
                </h2>
              </div>`;

    html21 = ''/* `<div class="entryDirectProviderContent-disabled">
                <label for="usr">Serie*:</label>
                <div class="input-group">
                  <input required class="form-control alphaNumeric-only" type="text" name="txtSerieProviderED" id="txtSerieProviderED" placeholder="Serie" style="float:left;" onchange="validEmptyInputSLProvider('txtSerieProviderED', 'invalid-serieProviderED', 'La entrada debe de tener número de serie.')">
                  <div class="invalid-feedback" id="invalid-serieProviderED">La entrada debe de tener número de serie.</div>
                </div>
              </div>` */; 
              
    html22 = `<div class="form-group">
                <div class="row">`+/* `
                  <div class="col-xl-6 col-lg-6 col-md-4 col-sm-6 col-xs-6">
                    <div class="entryDirectProviderContent-disabled">
                      <label for="usr">Folio*:</label>
                      <div class="input-group">
                        <input required class="form-control alphaNumeric-only" type="text" name="txtNoDocumentoProviderED" id="txtNoDocumentoProviderED" placeholder="Folio" style="float:left;" onchange="validEmptyInputSLProvider('txtNoDocumentoProviderED', 'invalid-noDocumentoProviderED', 'La entrada debe de tener un número de folio.')">
                        <div class="invalid-feedback" id="invalid-noDocumentoProviderED">La entrada debe de tener número de folio.</div>
                      </div>
                    </div>
                  </div>` */`
                  <div class="col-xl-12 col-lg-6 col-md-4 col-sm-6 col-xs-6">
                    <div class="entryDirectProviderContent-disabled">
                      <label for="cmbTipoProviderED">Tipo*:</label>
                      <div class="input-group">
                        <select name="cmbTipoProviderED" id="cmbTipoProviderED" required onchange="validEmptyInputSLProvider('cmbTipoProviderED', 'invalid-tipoProviderED', 'La entrada debe de tener un tipo.')"></select>
                        <div class="invalid-feedback" id="invalid-tipoProviderED">La entrada debe de tener un tipo.</div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>`;

    html23 = ``/* `<div class="row">
                <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-xs-6">
                  <div class="entryDirectProviderContent-disabled">
                    <label for="usr">Subtotal*:</label>
                    <div class="input-group">
                      <input required class="form-control numericDecimal-only" type="text" name="txtSubtotalProviderED" id="txtSubtotalProviderED" placeholder="Ej. 1000.00" style="float:left;" onchange="validEmptyInputSLProvider('txtSubtotalProviderED', 'invalid-subtotalProviderED', 'La entrada debe de tener subtotal.')">
                      <div class="invalid-feedback" id="invalid-subtotalProviderED">La entrada debe de tener subtotal.</div>
                    </div>
                  </div>
                </div>
                <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-xs-6">
                  <div class="entryDirectProviderContent-disabled">
                    <label for="usr">IVA (Monto):</label>
                    <div class="input-group">
                      <input class="form-control numericDecimal-only" type="text" name="txtIvaProviderED" id="txtIvaProviderED" placeholder="Ej. 1000.00" style="float:left;" value="0" onchange="validEmptyInputSLProvider('txtIvaProviderED', 'invalid-ivaProviderED', 'La entrada debe de tener IVA.')">
                      <div class="invalid-feedback" id="invalid-ivaProviderED">La entrada debe de tener IVA.</div>
                    </div>
                  </div>
                </div>
                <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-xs-6">
                  <div class="entryDirectProviderContent-disabled">
                    <label for="usr">IEPS (Monto):</label>
                    <div class="input-group">
                      <input class="form-control numericDecimal-only" type="text" name="txtIEPSProviderED" id="txtIEPSProviderED" placeholder="Ej. 1000.00" style="float:left;" value="0" onchange="validEmptyInputSLProvider('txtIEPSProviderED', 'invalid-iepsProviderED', 'La entrada debe de tener IEPS.')">
                      <div class="invalid-feedback" id="invalid-iepsProviderED">La entrada debe de tener IEPS.</div>
                    </div>
                  </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-xs-6">
                  <div class="entryDirectProviderContent-disabled">
                    <label for="usr">Importe factura*:</label>
                    <div class="input-group">
                      <input required class="form-control numericDecimal-only" type="text" name="txtImporteProviderED" id="txtImporteProviderED" placeholder="Ej. 1000.00" style="float:left;" onchange="validEmptyInputSLProvider('txtImporteProviderED', 'invalid-importeProviderED', 'La entrada debe de tener importe.')">
                      <div class="invalid-feedback" id="invalid-importeProviderED">La entrada debe de tener importe.</div>
                    </div>
                  </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-xs-6">
                  <div class="entryDirectProviderContent-disabled">
                    <label for="usr">Fecha de factura*:</label>
                    <div class="input-group">
                      <input required class="form-control" type="date" name="txtFechaFacturaProviderED" id="txtFechaFacturaProviderED" style="float:left;" onchange="validEmptyInputSLProvider('txtFechaFacturaProviderED', 'invalid-fechaFacturaProviderED', 'La entrada debe de tener una fecha de factura.')">
                      <div class="invalid-feedback" id="invalid-fechaFacturaProviderED">La entrada debe de una fecha de factura.</div>
                    </div>
                  </div>
                </div>
              </div>` */; 

    html24 = ``;

    html25 = `<div class="form-group entryDirectProviderContent-disabled">
                <div class="row">
                  <div class="col-lg-6">
                    <i data-toggle=\"modal\" data-target=\"#agregar_ProductoEDProvider\"><img src=\"../../../../img/timdesk/ICONO AGREGAR2_Mesa de trabajo 1.svg\" width="30px" id="addProducto"></i>
                    <label>Añadir producto</label>
                  </div>
                </div>
              </div>
              <div class="entryDirectProviderContent-disabled">
                <table class="table opacidad" id="tblProductosEntradaDProvider" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Clave/Producto</th>
                      <th>Cantidad a entrar</th>
                      <th>Unidad de medida</th>
                      <th>Lote</th>
                      <th>Fecha de caducidad</th>
                      <th></th>
                    </tr>
                  </thead>
                </table>
                <br><br>
                <label for="">Los productos donde su cantidad sea igual a 0 no serán registrados.</label>
                <div class="">
                  <br>
                    <input type="checkbox" id="check_IsCuentaCobrar" onclick="activarDataCuentaPagar(this)"> Agregar cuenta por pagar
                    <div id="dataCuentaPagar"></div>
                  <br><br>
                  <div class="col-xl-12 col-lg-6 col-md-6 col-sm-6 col-xs-6">
                    <div class="entryDirectProviderContent-disabled">
                      <label for="usr">Notas:</label>
                      <textarea class="form-control" name="txtNotaEntradaEDProvider" id="txtNotaEntradaEDProvider" placeholder="Escribe las notas aquí..." rows="2" cols="200"></textarea>
                    </div>
                  </div>
                  <div class="entryDirectProviderContent-disabled">
                    <br>
                    <a class="btn-custom btn-custom--blue float-right" id="btnAgregarEntradaEDProvider" style="margin-right: 10px!important">Registrar entrada</a>
                    <span id="sCerrarOC">
                    </span>
                  </div>
                </div>
              </div>`;     
    $("#div55").html(html20);  
    $("#div20").html('');  
    $("#div21").html('');
    $("#div22").html('');
    //$("#div6").html(html23);
    $("#div7").html(html24);
    $("#div25").html(html25);
    //$("#divTipo").html(html22)

    new SlimSelect({
      select: '#cmbProveedorED',
      deselectLabel: '<span class="">✖</span>',
    });


    loadProviderEntryED("","cmbProveedorED");
  }else if ($('#cmbTypeDirectEntry').val() === "3"){

    html19 = `<div class="entryDirectCustomer-disabled">
                <label for="cmbClienteED">Cliente*:</label>
                <div class="input-group">
                  <select name="cmbClienteED" id="cmbClienteED" required></select>
                  <div class="invalid-feedback" id="invalid-clienteED">La entrada debe de tener un cliente.</div>
                </div>
              </div>`;

    $("#div19").html(html19); 

    $('.data-container .entryDirectCustomer-disabled').css({'display':'inline-block','visibility': 'visible','opacity': '1','animation': 'fade 1s'});
    $('.data-container .entryDirectProvider-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
    $('.data-container .entryDirectProviderContent-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
    $('.data-container .entryDirectProviderContent-disabledED').css({'display':'none','opacity': '0','visibility': 'hidden'});
    $("#div66").html('');
    $('.data-container .entryDirectBranch-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
    $('.data-container .entryDirectBranchContent-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});

    html20 = `<div class="entryDirectCustomerContent-disabled">
                <label for="usr">Folio de entrada*:</label>
                <div class="input-group">
                  <input required class="form-control alphaNumeric-only" type="text" name="txtFolioEntradaCustomerED" id="txtFolioEntradaCustomerED" placeholder="ED000001" style="float:left;" onchange="validEmptyInputSLProvider('txtFolioEntradaCustomerED', 'invalid-folioEntradaCustomerED', 'La entrada debe de tener un folio.')" readonly>
                  <div class="invalid-feedback" id="invalid-folioEntradaCustomerED">La entrada debe de tener un folio.</div>
                </div>
              </div>`;

    html21 = `<div class="entryDirectCustomerContent-disabled">
              <label for="usr">Referencia*:</label>
              <div class="input-group">
                <input required class="form-control alphaNumeric-only" type="text" name="txtReferenciaCustomerED" id="txtReferenciaCustomerED" placeholder="Folio" style="float:left;" onchange="validEmptyInput('txtReferenciaCustomerED', 'invalid-referenciaCustomerED', 'La entrada debe de tener un número de referencia.')">
                <div class="invalid-feedback" id="invalid-referenciaCustomerED">La entrada debe de tener número de referencia.</div>
              </div>
            </div>`;  

    html22 = `<div class="entryDirectCustomerContent-disabled">
              <label for="usr">Notas:</label>
              <textarea class="form-control" name="txtNotaEntradaCustomerED" id="txtNotaEntradaCustomerED" placeholder="Escribe las notas aquí..." rows="2" cols="200"></textarea>
            </div>`;

    html23 = ``;
    html24 = ``;

    html25 = `<div class="form-group entryDirectCustomerContent-disabled">
              <div class="row">
                <div class="col-lg-6">
                  <i data-toggle=\"modal\" data-target=\"#agregar_ProductoEDCustomer\"><img src=\"../../../../img/timdesk/ICONO AGREGAR2_Mesa de trabajo 1.svg\" width="30px" id="addProducto"></i>
                  <label>  Añadir producto</label>
                </div>
              </div>
            </div>
            <div class="entryDirectCustomerContent-disabled">
              <table class="table opacidad" id="tblProductosEntradaDCustomer" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Clave/Producto</th>
                    <th>Cantidad a entrar</th>
                    <th>Unidad de medida</th>
                    <th>Lote</th>
                    <th>Fecha de caducidad</th>
                    <th></th>
                  </tr>
                </thead>
              </table>
              <br><br>
              <label for="">Los productos donde su cantidad sea igual a 0 no serán registrados.</label>
              <div class="">
                <div class="entryDirectCustomerContent-disabled">
                  <br>
                  <br>
                  <a class="btn-custom btn-custom--blue float-right" id="btnAgregarEntradaEDCustomer" style="margin-right: 10px!important">Registrar entrada</a>
                  <span id="sCerrarOC">
                  </span>
                </div>
              </div>
            </div>`;  

    $("#div20").html(html20);
    $("#div21").html(html21);
    $("#div22").html(html22);
    $("#div6").html(html23);
    $("#div7").html(html24);
    $("#div25").html(html25);

    new SlimSelect({
      select: '#cmbClienteED',
      deselectLabel: '<span class="">✖</span>',
    });

    loadCustomerEntryED("","cmbClienteED");
  }
  
});

function activarDataCuentaPagar(sender){
  let html='';
  if(sender.checked){
    var data = document.getElementById('cmbOCProveedor').value;
    html = `<br>
              <div class="form-row">
                <div class="form-group col-12 col-md-3">
                  <label for="usr">Fecha de documento:*</label>
                  <input class="form-control" type="date" name="txtFechaFacturaProviderED" id="txtFechaFacturaProviderED">
                  <div class="invalid-feedback" id="invalid-fechaFacturaProviderED">Se debe tener un fecha.</div>
                </div>
                <div class="form-group col-12 col-md-3">
                  <label for="usr">F.vencimiento:</label>
                  <input class="form-control" type="date" name="txtfechavenci" id="txtfechavenci">
                </div>
                <div class="form-group col-12 col-md-3">
                  <label for="usr">Folio de documento*:</label>
                  <input class="form-control alphaNumeric-only" type="text" name="txtNoDocumentoProviderED" id="txtNoDocumentoProviderED" placeholder="Folio" style="float:left;" onchange="validEmptyInputSLProvider('txtNoDocumentoProviderED', 'invalid-noDocumentoProviderED', 'La entrada debe de tener un número de folio.')">
                  <div class="invalid-feedback" id="invalid-noDocumentoProviderED">La entrada debe de tener número de folio.</div>
                </div>
                <div class="form-group col-12 col-md-3">
                  <label for="cmbTipoProviderED">Tipo*:</label>
                  <div class="input-group">
                    <select name="cmbTipoProviderED" id="cmbTipoProviderED" required onchange="validEmptyInputSLProvider('cmbTipoProviderED', 'invalid-tipoProviderED', 'La entrada debe de tener un tipo.')"></select>
                    <div class="invalid-feedback" id="invalid-tipoProviderED">La entrada debe de tener un tipo.</div>
                  </div>
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-12 col-md-3">
                  <label for="usr">Categoria*:</label>
                  <select class="form-select" name="cmbCategoriaCuenta" id="cmbCategoriaCuenta" aria-label="Default select example"></select>
                  <div class="invalid-feedback" id="invalid-categoriaCuenta">El campo es obligatorio.</div>
                </div>
                <div class="form-group col-12 col-md-3">
                  <label for="cmbCategoriaCuenta">Subcategoria:*</label>
                  <select class="form-select" name="cmbSubcategoriaCuenta" id="cmbSubcategoriaCuenta"></select>
                  <div class="invalid-feedback" id="invalid-subcategoriaCuenta">El campo es obligatorio.</div>
                </div>
                <div class="form-group col-12 col-md-2">
                  <label for="usr">Subtotal*:</label>
                  <div class="d-flex align-items-center">
                    $ <input class="form-control numericDecimal-only" type="text" name="txtSubtotalProviderED" id="txtSubtotalProviderED" placeholder="Ej. 1000.00" style="float:left;" onchange="validEmptyInputSLProvider('txtSubtotalProviderED', 'invalid-subtotalProviderED', 'La entrada debe de tener subtotal.')">
                    <div class="invalid-feedback" id="invalid-subtotalProviderED">La entrada debe de tener subtotal.</div>
                  </div>
                </div>  
                <div class="form-group col-12 col-md-3">
                  <label for="usr">Importe total*:</label>
                  <div class="d-flex align-items-center">
                    $ <input class="form-control numericDecimal-only" type="text" name="txtImporteProviderED" id="txtImporteProviderED" placeholder="Ej. 1000.00" style="float:left;" onchange="validEmptyInputSLProvider('txtImporteProviderED', 'invalid-importeProviderED', 'La entrada debe de tener importe.')">
                    <div class="invalid-feedback" id="invalid-importeProviderED">La entrada debe de tener importe.</div>
                  </div>
                </div>
              </div>`;
  }
  $("#dataCuentaPagar").html(html);
  if(sender.checked){
    new SlimSelect({
      select: '#cmbTipoProviderED',
      deselectLabel: '<span class="">✖</span>',
    });
    new SlimSelect({
      select: '#cmbCategoriaCuenta',
      deselectLabel: '<span class="">✖</span>',
    });
    cmbSubcategoria = new SlimSelect({
      select: '#cmbSubcategoriaCuenta',
      deselectLabel: '<span class="">✖</span>',
    });
    loadTypeEntryDirectProvider("","cmbTipoProviderED");    
    cargarCMBCategorias('');
    cmbSubcategoria.disable();
    $(document).on('change','#cmbCategoriaCuenta',(e)=>{
        const target = e.target;
        cargarCMBSubcategorias(target.value,'');
        cmbSubcategoria.enable();
    }); 
  }
  $("#txtFechaFacturaProviderED").prop('max', new Date().toISOString().split("T")[0]);
  $("#txtFechaFacturaProviderED").val(new Date().toISOString().split("T")[0]);
  

}

$(document).on('change','#cmbSucursalOrigenED',function(){
  $('.data-container .entryDirectBranchContent-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
  $('.data-container .entryDirectProviderContent-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
  $('.data-container .entryDirectProviderContent-disabledED').css({'display':'none','opacity': '0','visibility': 'hidden'});
  $("#div66").html('');
  $('.data-container .entryDirectCustomerContent-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});

  if($('#cmbSucursalOrigenED').val() === ""){
    $('.data-container .entryDirectBranchContent-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
    $('.data-container .entryDirectProviderContent-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
    $('.data-container .entryDirectProviderContent-disabledED').css({'display':'none','opacity': '0','visibility': 'hidden'});
    $("#div66").html('');
    $('.data-container .entryDirectCustomerContent-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
  }else{
    $('.data-container .entryDirectBranchContent-disabled').css({'display':'inline-block','visibility': 'visible','opacity': '1','animation': 'fade 1s'});

    getFolioEntrada();
    loadProductosEntryDirect();
  }
    
});

$(document).on('change','#cmbProveedorED',function(){
  $('.data-container .entryDirectBranchContent-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
  $('.data-container .entryDirectProviderContent-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
  $('.data-container .entryDirectProviderContent-disabledED').css({'display':'none','opacity': '0','visibility': 'hidden'});
  $("#div66").html('');
  $('.data-container .entryDirectCustomerContent-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});

  if($('#cmbProveedorED').val() === ""){
    $('.data-container .entryDirectBranchContent-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
    $('.data-container .entryDirectProviderContent-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
    $('.data-container .entryDirectProviderContent-disabledED').css({'display':'none','opacity': '0','visibility': 'hidden'});
    $("#div66").html('');
    $('.data-container .entryDirectCustomerContent-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
  }else{
    $('.data-container .entryDirectProviderContent-disabled').css({'display':'inline-block','visibility': 'visible','opacity': '1','animation': 'fade 1s'});
    getFolioEntradaProvider();
    loadProductosEntryDirectProvider();
    loadOCProvider($('#cmbProveedorED').val());
  }
    
  /* if($("#txtNoDocumentoProviderED").val() != ''){
    validarFolioEDProvider();
  }

  if($("#txtSerieProviderED").val() != ''){
    validarSerieEDProvider();
  } */
  
  validaciones();
});

$(document).on('change','#cmbClienteED',function(){
  $('.data-container .entryDirectBranchContent-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
  $('.data-container .entryDirectProviderContent-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
  $('.data-container .entryDirectCustomerContent-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
  $('.data-container .entryDirectProviderContent-disabledED').css({'display':'none','opacity': '0','visibility': 'hidden'});
  $("#div66").html('');

  if($('#cmbClienteED').val() === ""){
    $('.data-container .entryDirectBranchContent-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
    $('.data-container .entryDirectProviderContent-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
    $('.data-container .entryDirectCustomerContent-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
    $('.data-container .entryDirectProviderContent-disabledED').css({'display':'none','opacity': '0','visibility': 'hidden'});
    $("#div66").html('');
  }else{
    $('.data-container .entryDirectCustomerContent-disabled').css({'display':'inline-block','visibility': 'visible','opacity': '1','animation': 'fade 1s'});

    getFolioEntradaCustomer();
    loadProductosEntryDirectCustomer();
  }
  
  validaciones();
});

function loadOCProvider(id){
  let html = `<div class="entryDirectProviderContent-disabledED" style="display:none">
                <label for="usr">Orden de compra:</label>
                <div class="input-group">
                  <select name="cmbOCProveedor" id="cmbOCProveedor"></select>
                </div>
              </div>`;

  $("#div66").html(html);

  new SlimSelect({
    select: '#cmbOCProveedor',
    deselectLabel: '<span class="">✖</span>',
  });

  var htmlSelect ='<option value="" selected>Seleccione una orden de compra...</option>';
  let sucursalDestino = $("#cmbSucursalEntrada").val();

  $.ajax({
    url:"../../php/funciones.php",
  	data:{clase:"get_data", funcion:"get_oc_provedoor", data:id, data2:sucursalDestino},
  	dataType:"json",
    success:function(respuesta){
      if(respuesta.length>0){
        $.each(respuesta,function(i){
          htmlSelect += '<option value="'+respuesta[i].PKOrdenCompra+'">'+respuesta[i].Referencia+'</option>';
        });
        $('.data-container .entryDirectProviderContent-disabledED').css({'display':'inline-block','visibility': 'visible','opacity': '1','animation': 'fade 1s'});
      }else{
        $('.data-container .entryDirectProviderContent-disabledED').css({'display':'none','opacity': '0','visibility': 'hidden'});
      }

      $('#cmbOCProveedor').html(htmlSelect);
    },
    error:function(error){
      console.log(error);
    }
  });
}

$(document).on('change','#cmbOCProveedor',function(){
  deleteEntradaDirectaTemp();
  if($('#cmbOCProveedor').val() === "" && $("#check_IsCuentaCobrar").is(':checked')){
    $("#check_IsCuentaCobrar").prop("disabled", false);
    $("#check_IsCuentaCobrar").click();
  }else if($('#cmbOCProveedor').val() != "" && !$("#check_IsCuentaCobrar").is(':checked')){
    $("#check_IsCuentaCobrar").click();
    $("#check_IsCuentaCobrar").prop("disabled", true);
  }
  
  validaciones();
  //carga productos de la orden de compra en la tabla
  if($('#cmbOCProveedor').val() != ""){
    $.ajax({
      url: "../../php/funciones.php",
      data: {
        clase: "save_data",
        funcion: "save_entry_partial_ocTempTable_EntradaDirecta",
        data:$('#cmbOCProveedor').val(),
      },
      dataType: "json",
      success: function (respuesta) {
        console.log(respuesta);
        if (respuesta[0].status) {
          $("#tblProductosEntradaDProvider").DataTable().ajax.reload();
          getExpenseCategory($('#cmbOCProveedor').val());
        } 
      },
      error: function (error) {
        console.log(error);
      },
    });
  }else{
    $("#tblProductosEntradaDProvider").DataTable().ajax.reload();
  }
});

function validaciones(){
  $(".alpha-only").on("input", function () {
    var regexp = /[^a-zA-Z ]/g;
    if ($(this).val().match(regexp)) {
      $(this).val($(this).val().replace(regexp, ""));
    }
  });

  /*Permitir solamente letras y numeros*/
  $(".alphaNumeric-only").on("input", function () {
    var regexp = /[^a-zA-Z0-9 @.]/g;
    if ($(this).val().match(regexp)) {
      $(this).val($(this).val().replace(regexp, ""));
    }
  });

  /*Permitir solamente letras y numeros sin punto*/
  $(".alphaNumericNDot-only").on("input", function () {
    var regexp = /[^a-zA-Z0-9 @]/g;
    if ($(this).val().match(regexp)) {
      $(this).val($(this).val().replace(regexp, ""));
    }
  });
  /*Permitir solamente numeros*/
  $(".numeric-only").on("input", function () {
    var regexp = /[^0-9]/g;
    if ($(this).val().match(regexp)) {
      $(this).val($(this).val().replace(regexp, ""));
    }
  });
  /*Permitir solamente numeros y ":" reloj*/
  $(".time-only").on("input", function () {
    var regexp = /[^0-9:]/g;
    if ($(this).val().match(regexp)) {
      $(this).val($(this).val().replace(regexp, ""));
    }
  });

  /*Permitir numero decimales */
  $(".numericDecimal-only").on("input", function () {
    var regexp = /[^\d.]/g;
    if ($(this).val().match(regexp)) {
      $(this).val($(this).val().replace(regexp, ""));
    }
  });
}

function saveEntradaOCTemp(pkOrden){
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "save_data",
      funcion: "save_entry_partial_ocTempTable",
      data:pkOrden,
    },
    dataType: "json",
    success: function (respuesta) {
      console.log(respuesta);
      if (respuesta[0].status) {
        
        loadProductosOrderPurchase(pkOrden);
        
      } else {
       
      }
    },
    error: function (error) {
      console.log(error);
      
    },
  });
}

function saveEntradaTraspaso(folioSalida){
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "save_data",
      funcion: "save_entry_transfer_TempTable",
      data:folioSalida,
    },
    dataType: "json",
    success: function (respuesta) {
      console.log(respuesta);
      if (respuesta[0].status) {
        
        loadProductosTransfer(folioSalida);
        
      } else {
       
      }
    },
    error: function (error) {
      console.log(error);
      
    },
  });
}

function deleteEntradaOCTemp(){
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "delete_data",
      funcion: "delete_datosEntradaOCTemp"
    },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta eliminar entrada oc temp:", respuesta);

      if (respuesta[0].status) {
        console.log("Entrada OC. Eliminado todos");
      } else {
        console.log("Error al eliminar todos");
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function deleteEntradaTraspasoTemp(){
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "delete_data",
      funcion: "delete_entry_traslade_tempTable"
    },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta eliminar entrada oc temp:", respuesta);

      if (respuesta[0].status) {
        console.log("Entrada traspasos Eliminado todos");
        saveEntradaTraspaso($("#cmbOrderPedido").val());
      } else {
        console.log("Error al eliminar todos");
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function openModalDeleteProdTraspasoTemp(idEntradaTemp){
  $("#entryTempIDTrasD").val(idEntradaTemp);
  $('#eliminar_ProductoEntTras').modal('show');
}

function deleteLoteSerieTras(idEntradaTemp){
  /*$.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "delete_data",
      funcion: "delete_entry_transferRemove_TempTable",
      data:idEntradaTemp
    },
    dataType: "json",
    success: function (respuesta) {
      console.log(respuesta);
      if (respuesta[0].status) {
        console.log("Eliminado...");

        $("#tblProductosTraspaso").DataTable().ajax.reload();
        
      } else {
       
      }
    },
    error: function (error) {
      console.log(error);
      
    },
  });*/

  $('#eliminar_ProductoEntTras').modal('toggle');

  updateCantidadTras(0, idEntradaTemp);
}

/* Load Ordenes de compra de la empresa */
function loadPurchaseOrder(data,input){
  var html ='<option value="" selected>Seleccione una orden de compra...</option>';
  $.ajax({
    url:"../../php/funciones.php",
  	data:{clase:"get_data", funcion:"get_cmb_purchaseOrderEntry", data: data},
  	dataType:"json",
    success:function(respuesta){
      console.log("respuesta:",respuesta);

      if(respuesta !== "" && respuesta !== null){
        $.each(respuesta,function(i){
          if(data === respuesta[i].PKOrdenCompra){
            selected = 'selected';
          }else{
            selected = '';
          }
          html += '<option value="'+respuesta[i].PKOrdenCompra+'" '+selected+'>'+respuesta[i].Referencia+'</option>';
        });

      }else{
        html += '<option value="vacio">No hay órdenes de compra que mostrar</option>';
      }

      $('#'+input+'').html(html);
    },
    error:function(error){
      console.log(error);
    }

  });

  $('#'+input+'').html(html);
}

function loadProvider(data,input){
  var html ='<option value="" selected>Seleccione un proveedor...</option>';

  $.ajax({
    url:"../../php/funciones.php",
  	data:{clase:"get_data", funcion:"get_cmb_providerEntry"},
  	dataType:"json",
    success:function(respuesta){
      console.log("respuesta:",respuesta);

      if(respuesta !== "" && respuesta !== null){
        $.each(respuesta,function(i){
          if(data === respuesta[i].PKProveedor){
            selected = 'selected';
          }else{
            selected = '';
          }
          html += '<option value="'+respuesta[i].PKProveedor+'" '+selected+'>'+respuesta[i].Razon_Social+'</option>';
        });

      }else{
        html += '<option value="vacio">No hay proveedores que mostrar</option>';
      }

      $('#'+input+'').html(html);
    },
    error:function(error){
      console.log(error);
    }

  });

  $('#'+input+'').html(html);

  $('#'+input+' option:not(:selected)').remove();
}

function loadBranch(data,input){
  var html ='';

  $.ajax({
    url:"../../php/funciones.php",
  	data:{clase:"get_data", funcion:"get_cmb_branchEntry", data:data},
  	dataType:"json",
    success:function(respuesta){
      console.log("respuesta orden de fabricacion combo:",respuesta);

      if(respuesta !== "" && respuesta !== null){
        $.each(respuesta,function(i){
          if(data === respuesta[i].PKSucursal){
            selected = 'selected';
          }else{
            selected = '';
          }
          html += '<option value="'+respuesta[i].PKSucursal+'" '+selected+'>'+respuesta[i].Sucursal+'</option>';
        });

      }else{
        html += '<option value="vacio">No hay sucursales que mostrar</option>';
      }

      $('#'+input+'').html(html);
    },
    error:function(error){
      console.log(error);
    }

  });

  $('#'+input+'').html(html);

  $('#'+input+' option:not(:selected)').remove();
}

/* Load Sucursales (Origen) de la empresa */
function loadBranchOrigin(data,input){

  console.log("DATOS... " + data);

  var html ='';
  $.ajax({
    url:"../../php/funciones.php",
  	data:{
      clase:"get_data", 
      funcion:"get_cmb_branch_origin_exit",
      data: data
    },
  	dataType:"json",
    success:function(respuesta){
      console.log("respuesta:",respuesta);

      if(respuesta !== "" && respuesta !== null){
        $.each(respuesta,function(i){
          if(data === respuesta[i].PKSucursal){
            selected = 'selected';
          }else{
            selected = '';
          }
          html += '<option value="'+respuesta[i].PKSucursal+'" '+selected+'>'+respuesta[i].Sucursal+'</option>';
        });

      }else{
        html += '<option value="vacio">No hay sucursales que mostrar</option>';
      }

      $('#'+input+'').html(html);
    },
    error:function(error){
      console.log(error);
    }

  });

  $('#'+input+'').html(html);
}

function loadTypeOrderPedido(data,input){
  var html =`<option value="" selected>Seleccione un tipo de pedido...</option>
            <option value="1">Traspasos</option>
            `;
        
  $('#'+input+'').html(html);
}

/* Load Orden de pedido de la empresa */
function loadOrderPedido(data,input){
  $.ajax({
    url:"../../php/funciones.php",
  	data:{
      clase:"get_data", 
      funcion:"get_cmb_traspaso_entrada",
      data: data
    },
  	dataType:"json",
    success:function(respuesta){
      console.log("respuesta:",respuesta);

      if(respuesta != "" && respuesta != null){
        var html ='<option value="" selected>Seleccione una orden de pedido...</option>';
        $.each(respuesta,function(i){
          if(data === respuesta[i].FolioSalida){
            selected = 'selected';
          }else{
            selected = '';
          }
          html += '<option value="'+respuesta[i].FolioSalida+'" '+selected+'>'+respuesta[i].FolioSalida+'</option>';
        });

      }else{
        html += '<option value="vacio">No hay ordenes de pedido que mostrar</option>';
      }

      $('#'+input+'').html(html);
    },
    error:function(error){
      console.log(error);
    }

  });
}

/* Load Sucursales (Destino) de la empresa */
function loadBranchDestination(data,input){
  var html ='<option value="" selected>Seleccione una sucursal...</option>';
  $.ajax({
    url:"../../php/funciones.php",
  	data:{clase:"get_data", funcion:"get_cmb_branch_origin"},
  	dataType:"json",
    success:function(respuesta){
      console.log("respuesta:",respuesta);

      if(respuesta !== "" && respuesta !== null){
        $.each(respuesta,function(i){
          if(data === respuesta[i].PKSucursal){
            selected = 'selected';
          }else{
            selected = '';
          }
          html += '<option value="'+respuesta[i].PKSucursal+'" '+selected+'>'+respuesta[i].Sucursal+'</option>';
        });

      }else{
        html += '<option value="vacio">No hay sucursales que mostrar</option>';
      }

      $('#'+input+'').html(html);
    },
    error:function(error){
      console.log(error);
    }

  });

  $('#'+input+'').html(html);
}

function loadBranchEntry(data,input){
  var html ='<option value="" selected>Seleccione una sucursal...</option>';
  $.ajax({
    url:"../../php/funciones.php",
  	data:{clase:"get_data", funcion:"get_cmb_branch_directEntry"},
  	dataType:"json",
    success:function(respuesta){
      console.log("respuesta:",respuesta);

      if(respuesta !== "" && respuesta !== null){
        $.each(respuesta,function(i){
          if(data === respuesta[i].PKSucursal){
            selected = 'selected';
          }else{
            selected = '';
          }
          html += '<option value="'+respuesta[i].PKSucursal+'" '+selected+'>'+respuesta[i].Sucursal+'</option>';
        });

      }else{
        html += '<option value="vacio">No hay sucursales que mostrar</option>';
      }

      $('#'+input+'').html(html);
    },
    error:function(error){
      console.log(error);
    }

  });

  $('#'+input+'').html(html);
}

function loadBranchEntryOriginED(data,input){
  var html ='<option value="" selected>Seleccione una sucursal...</option>';
  var sucursalDestino = $("#cmbSucursalEntrada").val();
  $.ajax({
    url:"../../php/funciones.php",
  	data:{clase:"get_data", funcion:"get_cmb_branch_directEntry_OriginED", data:sucursalDestino},
  	dataType:"json",
    success:function(respuesta){
      console.log("respuesta:",respuesta);

      if(respuesta !== "" && respuesta !== null){
        $.each(respuesta,function(i){
          if(data === respuesta[i].PKSucursal){
            selected = 'selected';
          }else{
            selected = '';
          }
          html += '<option value="'+respuesta[i].PKSucursal+'" '+selected+'>'+respuesta[i].Sucursal+'</option>';
        });

      }else{
        html += '<option value="vacio">No hay sucursales que mostrar</option>';
      }

      $('#'+input+'').html(html);
    },
    error:function(error){
      console.log(error);
    }

  });

  $('#'+input+'').html(html);
}

function loadProviderEntryED(data,input){
  var html ='<option value="" selected>Seleccione un proveedor...</option>';

  var sucursalDestino = $("#cmbSucursalEntrada").val();

  $.ajax({
    url:"../../php/funciones.php",
  	data:{clase:"get_data", funcion:"get_cmb_provider_directEntry_ED", data:sucursalDestino},
  	dataType:"json",
    success:function(respuesta){
      console.log("respuesta:",respuesta);

      if(respuesta !== "" && respuesta !== null){
        $.each(respuesta,function(i){
          if(data === respuesta[i].PKProveedor){
            selected = 'selected';
          }else{
            selected = '';
          }
          html += '<option value="'+respuesta[i].PKProveedor+'" '+selected+'>'+respuesta[i].Razon_Social+'</option>';
        });

      }else{
        html += '<option value="vacio">No hay proveedores que mostrar</option>';
      }

      $('#'+input+'').html(html);
    },
    error:function(error){
      console.log(error);
    }

  });

  $('#'+input+'').html(html);
}

function loadCustomerEntryED(data,input){
  var html ='<option value="" selected>Seleccione un cliente...</option>';

  var sucursalDestino = $("#cmbSucursalEntrada").val();

  $.ajax({
    url:"../../php/funciones.php",
  	data:{clase:"get_data", funcion:"get_cmb_customer_directEntry_ED", data:sucursalDestino},
  	dataType:"json",
    success:function(respuesta){
      console.log("respuesta:",respuesta);

      if(respuesta !== "" && respuesta !== null){
        $.each(respuesta,function(i){
          if(data === respuesta[i].PKCliente){
            selected = 'selected';
          }else{
            selected = '';
          }
          html += '<option value="'+respuesta[i].PKCliente+'" '+selected+'>'+respuesta[i].Razon_Social+'</option>';
        });

      }else{
        html += '<option value="vacio">No hay clientes que mostrar</option>';
      }

      $('#'+input+'').html(html);
    },
    error:function(error){
      console.log(error);
    }

  });

  $('#'+input+'').html(html);
}

function loadTypeEntryDirectProvider(data,input){
  var html ='<option value="" selected>Seleccione un tipo...</option>';

  $.ajax({
    url:"../../php/funciones.php",
  	data:{clase:"get_data", funcion:"get_cmb_tipo_directEntry_Provider_ED"},
  	dataType:"json",
    success:function(respuesta){
      console.log("respuesta:",respuesta);

      if(respuesta !== "" && respuesta !== null){
        $.each(respuesta,function(i){
          if(data === respuesta[i].PKTipo){
            selected = 'selected';
          }else{
            selected = '';
          }
          html += '<option value="'+respuesta[i].PKTipo+'" '+selected+'>'+respuesta[i].Tipo+'</option>';
        });

      }else{
        html += '<option value="vacio">No hay tipos que mostrar</option>';
      }

      $('#'+input+'').html(html);
    },
    error:function(error){
      console.log(error);
    }

  });

  $('#'+input+'').html(html);
}

function getFolioEntrada(){
  $.ajax({
    url:"../../php/funciones.php",
    data:{clase:"get_data", funcion:"get_folioEntradaED"},
    dataType:"json",
    success:function(respuesta){
      $('#txtFolioEntradaED').val(respuesta);
    },
    error:function(error){
      console.log(error);
    }
  });
}

function getFolioEntradaProvider(){
  $.ajax({
    url:"../../php/funciones.php",
    data:{clase:"get_data", funcion:"get_folioEntradaED"},
    dataType:"json",
    success:function(respuesta){
      $('#txtFolioEntradaProviderED').text(respuesta);
    },
    error:function(error){
      console.log(error);
    }
  });
}

function getFolioEntradaCustomer(){
  $.ajax({
    url:"../../php/funciones.php",
    data:{clase:"get_data", funcion:"get_folioEntradaED"},
    dataType:"json",
    success:function(respuesta){
      $('#txtFolioEntradaCustomerED').val(respuesta);
    },
    error:function(error){
      console.log(error);
    }
  });
}

function loadTypeDirectEntry(data,input){
  var html ='<option value="" selected>Seleccione un tipo de entrada...</option>';
  $.ajax({
    url:"../../php/funciones.php",
  	data:{clase:"get_data", funcion:"get_cmb_branch_typeOrigin_directEntry"},
  	dataType:"json",
    success:function(respuesta){
      console.log("respuesta:",respuesta);

      if(respuesta !== "" && respuesta !== null){
        $.each(respuesta,function(i){
          if(respuesta[i].PKTipo != 1){
            if(data === respuesta[i].PKTipo){
              selected = 'selected';
            }else{
              selected = '';
            }
            html += '<option value="'+respuesta[i].PKTipo+'" '+selected+'>'+respuesta[i].Tipo+'</option>';
          }
        });

      }else{
        html += '<option value="vacio">No hay tipo que mostrar</option>';
      }

      $('#'+input+'').html(html);
    },
    error:function(error){
      console.log(error);
    }

  });

  $('#'+input+'').html(html);
}

function loadProductosOrderPurchase(pkOrden){
  $("#tblProductosOrdenCompraParcial").DataTable().destroy();
  $("#tblProductosOrdenCompraParcial").dataTable({
    language: setFormatDatatables(),
    dom: "Bfrtip",
    buttons: [
    ],
    scrollX: true,
    lengthChange: false,
    info: false,
    ajax: {
      url: "../../php/funciones.php",
      data: { clase: "get_data", funcion: "get_productosEntradaOCTempTable", data:pkOrden },
    },
    //"pageLength": 20,
    "paging": false,
    "order": [ [ 0, "desc" ], [ 1, "desc" ] ],
    columns: [
      { data: "Id" },
      { data: "Producto" },
      { data: "CantidadRecibida" },
      { data: "Cantidad" },
      { data: "UnidadMedida" },
      { data: "Lote" },
      { data: "Serie" },
      { data: "FechaCaducidad" },
      { data: "PrecioUnitario" },
      { data: "Impuestos" },
      { data: "Importe" }
    ],
    columnDefs: [
      { orderable: false, targets: 0, visible: false },
    ],
  });

  obtenerDatos(pkOrden);

  obtenerTotal(pkOrden); 
}

function loadProductosTransfer(folioSalida){
  $("#tblProductosTraspaso").DataTable().destroy();
  $("#tblProductosTraspaso").dataTable({
    language: setFormatDatatables(),
    dom: "Bfrtip",
    buttons: [
    ],
    scrollX: true,
    lengthChange: false,
    info: false,
    ajax: {
      url: "../../php/funciones.php",
      data: { clase: "get_data", funcion: "get_productosTraspasoTempTable", data:folioSalida },
    },
    //"pageLength": 20,
    "paging": false,
    "order": [ [ 0, "desc" ], [ 1, "desc" ] ],
    columns: [
      { data: "Id" },
      { data: "Producto" },
      { data: "Cantidad" },
      { data: "UnidadMedida" },
      { data: "Lote" },
      { data: "Serie" },
      { data: "FechaCaducidad" }
    ],
    columnDefs: [
      { orderable: false, targets: 0, visible: false },
    ],
  });

  obtenerTotalEntradaTraspaso(0, folioSalida); 
}

function loadProductosEntryDirect(){
  var sucOrigen = $("#cmbSucursalOrigenED").val();
  $("#tblProductosEntradaDCSucursal").DataTable().destroy();
  $("#tblProductosEntradaDCSucursal").dataTable({
    language: setFormatDatatables(),
    dom: "Bfrtip",
    buttons: [
    ],
    scrollX: true,
    lengthChange: false,
    info: false,
    ajax: {
      url: "../../php/funciones.php",
      data: { clase: "get_data", funcion: "get_productosEntradaDirectaTempTable", data: sucOrigen},
    },
    "paging": false,
    "order": [ [ 1, "desc" ] ],
    columns: [
      { data: "Id" },
      { data: "Producto" },
      { data: "Cantidad" },
      { data: "UnidadMedida" },
      { data: "Lote" },
      { data: "Serie" },
      { data: "FechaCaducidad" },
      { data: "Acciones" }
    ],
    columnDefs: [
      { orderable: false, targets: 0, visible: false },
    ],
  });

  //obtenerTotalEntradaTraspaso(0, folioSalida); 
}

function loadProductosEntryDirectProvider(){
  var proveedor = $("#cmbProveedorED").val();
  $("#tblProductosEntradaDProvider").DataTable().destroy();
  $("#tblProductosEntradaDProvider").dataTable({
    language: setFormatDatatables(),
    dom: "Bfrtip",
    buttons: [
    ],
    scrollX: true,
    lengthChange: false,
    info: false,
    ajax: {
      url: "../../php/funciones.php",
      data: { clase: "get_data", funcion: "get_productosEntradaDirectaTempTableProvider", data: proveedor},
    },
    "paging": false,
    "order": [ [ 1, "desc" ] ],
    columns: [
      { data: "Id" },
      { data: "Producto" },
      { data: "Cantidad" },
      { data: "UnidadMedida" },
      { data: "Lote" },
      { data: "FechaCaducidad" },
      { data: "Acciones" }
    ],
    columnDefs: [
      { orderable: false, targets: 0, visible: false },
      { orderable: false, targets: 6, width: '7%' },
    ],
  });
  //obtenerTotalEDProvider();
  console.log('aqui 8');
}

function loadProductosEntryDirectCustomer(){
  var cliente = $("#cmbClienteED").val();
  $("#tblProductosEntradaDCustomer").DataTable().destroy();
  $("#tblProductosEntradaDCustomer").dataTable({
    language: setFormatDatatables(),
    dom: "Bfrtip",
    buttons: [
    ],
    scrollX: true,
    lengthChange: false,
    info: false,
    ajax: {
      url: "../../php/funciones.php",
      data: { clase: "get_data", funcion: "get_productosEntradaDirectaTempTableCustomer", data: cliente},
    },
    "paging": false,
    "order": [ [ 1, "desc" ] ],
    columns: [
      { data: "Id" },
      { data: "Producto" },
      { data: "Cantidad" },
      { data: "UnidadMedida" },
      { data: "Lote" },
      { data: "FechaCaducidad" },
      { data: "Acciones" }
    ],
    columnDefs: [
      { orderable: false, targets: 0, visible: false },
    ],
  });
}

function cargarTablaProductosEDBranch(){
  var sucOrigen = $("#cmbSucursalOrigenED").val();

  $("#tblListadoProductosEDBranch").DataTable().destroy();
  $("#tblListadoProductosEDBranch").dataTable({
    language: setFormatDatatables(),
    scrollX: true,
    info: false,
    ajax: {
      url: "../../php/funciones.php",
      data: { clase: "get_data", 
              funcion: "get_productosSucursalEDTable",
              data: sucOrigen
            },
    },
    //"pageLength": 20,
    "paging": false,
    columns: [
      { data: "Id" },
      { data: "Clave" },
      { data: "Producto" },
    ],
    columnDefs: [
      { orderable: false, targets: 0, visible: false },
    ],
  });
}

function cargarTablaProductosEDProvider(){
  var proveedor = $("#cmbProveedorED").val();

  $("#tblListadoProductosEDProvider").DataTable().destroy();
  $("#tblListadoProductosEDProvider").dataTable({
    language: setFormatDatatables(),
    scrollX: true,
    info: false,
    ajax: {
      url: "../../php/funciones.php",
      data: { clase: "get_data", 
              funcion: "get_productosProveedorEDTable",
              data: proveedor
            },
    },
    //"pageLength": 20,
    "paging": false,
    columns: [
      { data: "Id" },
      { data: "Clave" },
      { data: "Producto" },
    ],
    columnDefs: [
      { orderable: false, targets: 0, visible: false },
    ],
  });
}

function cargarTablaProductosEDCustomer(){
  var cliente = $("#cmbClienteED").val();

  $("#tblListadoProductosEDCustomer").DataTable().destroy();
  $("#tblListadoProductosEDCustomer").dataTable({
    language: setFormatDatatables(),
    scrollX: true,
    info: false,
    ajax: {
      url: "../../php/funciones.php",
      data: { clase: "get_data", 
              funcion: "get_productosCustomerEDTable",
              data: cliente
            },
    },
    //"pageLength": 20,
    "paging": false,
    columns: [
      { data: "Id" },
      { data: "Clave" },
      { data: "Producto" },
    ],
    columnDefs: [
      { orderable: false, targets: 0, visible: false },
    ],
  });
}

function setFormatDatatables() {
  var idioma_espanol = {
    sProcessing: "Procesando...",
    sZeroRecords: "No se encontraron resultados",
    sEmptyTable: "Ningún dato disponible en esta tabla",
    sSearch: "<img src='../../../../img/timdesk/buscar.svg' width='20px' />",
    sLoadingRecords: "Cargando...",
    searchPlaceholder: "Buscar...",
    oPaginate: {
      sFirst: "",
      sLast: "",
      sNext: "<img src='../../../../img/icons/pagination.svg' width='20px'/>",
      sPrevious:
        "<img src='../../../../img/icons/pagination.svg' width='20px' style='transform: scaleX(-1)'/>",
    },
  };
  return idioma_espanol;
}

function seleccionarProductoED(id){
  var sucDestino = $("#cmbSucursalEntrada").val();

  $.ajax({
    url: "../../php/funciones.php",
    data: { 
            clase: "save_data", 
            funcion: "save_datosProductoED", 
            data: id,
            data2: sucDestino
          },
    dataType: "json",
    success: function (respuesta) {
      if (respuesta[0].status) {
        if(respuesta[0].insertado == '1'){
          $("#tblProductosEntradaDCSucursal").DataTable().ajax.reload();
          //obtenerTotal();
          Lobibox.notify("success", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../../../img/timdesk/checkmark.svg",
            msg: "!Producto registrado correctamente!",
            sound: '../../../../../sounds/sound4'
          });
        }else{
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../../../img/timdesk/notificacion_error.svg",
            msg: "¡No se puede añadir otro lote o serie al producto hasta completar los actuales!",
            sound: '../../../../../sounds/sound4'
          });
        }
      } else {
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/notificacion_error.svg",
          msg: "¡Algo salio mal :(!",
          sound: '../../../../../sounds/sound4'
        });
      }
    },
    error: function (error) {
      Lobibox.notify("error", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../../../img/timdesk/notificacion_error.svg",
        msg: "¡Algo salio mal :(!",
        sound: '../../../../../sounds/sound4'
      });
    },
  });

  $("#agregar_ProductoEDBranch").modal("hide");
}

function seleccionarProductoEDProvider(id){
  var sucDestino = $("#cmbSucursalEntrada").val();

  $.ajax({
    url: "../../php/funciones.php",
    data: { 
            clase: "save_data", 
            funcion: "save_datosProductoED", 
            data: id,
            data2: sucDestino
          },
    dataType: "json",
    success: function (respuesta) {
      if (respuesta[0].status) {
        if(respuesta[0].insertado == '1'){
          $("#tblProductosEntradaDProvider").DataTable().ajax.reload();
          //obtenerTotalEDProvider();
          $("#invalid-totalED").css('display', 'none');
          $("#invalid-importeProviderED").css('display', 'none');
          Lobibox.notify("success", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../../../img/timdesk/checkmark.svg",
            msg: "¡Producto registrado correctamente!",
            sound: '../../../../../sounds/sound4'
          });
        }else{
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../../../img/timdesk/notificacion_error.svg",
            msg: "¡No se puede añadir otro lote o serie al producto hasta completar los actuales!",
            sound: '../../../../../sounds/sound4'
          });
        }
      } else {
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/notificacion_error.svg",
          msg: "¡Algo salio mal :(!",
          sound: '../../../../../sounds/sound4'
        });
      }
    },
    error: function (error) {
      Lobibox.notify("error", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../../../img/timdesk/notificacion_error.svg",
        msg: "¡Algo salio mal :(!",
        sound: '../../../../../sounds/sound4'
      });
    },
  });

  $("#agregar_ProductoEDProvider").modal("hide");
}

function seleccionarProductoEDCustomer(id){
  var sucDestino = $("#cmbSucursalEntrada").val();

  $.ajax({
    url: "../../php/funciones.php",
    data: { 
            clase: "save_data", 
            funcion: "save_datosProductoED", 
            data: id,
            data2: sucDestino
          },
    dataType: "json",
    success: function (respuesta) {
      if (respuesta[0].status) {
        if(respuesta[0].insertado == '1'){
          $("#tblProductosEntradaDCustomer").DataTable().ajax.reload();
          Lobibox.notify("success", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../../../img/timdesk/checkmark.svg",
            msg: "¡Producto registrado correctamente!",
            sound: '../../../../../sounds/sound4'
          });
        }else{
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../../../img/timdesk/notificacion_error.svg",
            msg: "¡No se puede añadir otro lote o serie al producto hasta completar los actuales!",
            sound: '../../../../../sounds/sound4'
          });
        }
      } else {
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/notificacion_error.svg",
          msg: "¡Algo salio mal :(!",
          sound: '../../../../../sounds/sound4'
        });
      }
    },
    error: function (error) {
      Lobibox.notify("error", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../../../img/timdesk/notificacion_error.svg",
        msg: "¡Algo salio mal :(!",
        sound: '../../../../../sounds/sound4'
      });
    },
  });

  $("#agregar_ProductoEDCustomer").modal("hide");
}

function obtenerDatos(pkOrden){
  
  $.ajax({
    url:"../../php/funciones.php",
    data:{clase:"get_data", funcion:"get_datos_OrdenCompra", data:pkOrden},
    dataType:"json",
    success:function(respuesta){
      
      if(respuesta.length>0){
        $('#txtReferencia').html(respuesta[0].Referencia);
        $('#txtFechaEmision').html(respuesta[0].FechaCreacion);
        $('#txtFechaEstimada').html(respuesta[0].FechaEstimada);
        $('#txtProveedor').html(respuesta[0].NombreComercial);
        $('#NotasInternas').html(respuesta[0].NotasInternas);
        $('#txtDireccion').html("Sucursal:"+respuesta[0].Sucursal +". "+
                              respuesta[0].Calle +" "+
                              respuesta[0].NumExt +", "+
                              respuesta[0].Prefijo +", "+
                              respuesta[0].NumInt +", "+
                              respuesta[0].Colonia +", "+
                              respuesta[0].Municipio +", "+
                              respuesta[0].Estado +", "+
                              respuesta[0].Pais +" ");
      }
      
    },
    error:function(error){
      console.log(error);
    }
  });
}

function obtenerTotal(pkOrden){
  var PKOrden = pkOrden;
  //Obtener subtotal
  $.ajax({
    
    url:"../../php/funciones.php",
    data:{clase:"get_data", funcion:"get_subTotalEntradaOCTemp",datos:PKOrden},
    dataType:"json",
    success:function(respuesta){
      $('#Subtotal').html(dosDecimales(respuesta[0].subtotal))
    },
    error:function(error){
      console.log(error);
    }
  });

  var html='';
  $('#impuestos').html(html);
  //Obtener impuestos
  $.ajax({
    
    url:"../../php/funciones.php",
    data:{clase:"get_data", funcion:"get_impuestoEntradaOC",datos:PKOrden},
    dataType:"json",
    success:function(respuesta){
      //Recorrer las respuestas de la consulta
      var tasa = '';
      $.each(respuesta,function(i){    
        if (respuesta[i].tasa == "" || respuesta[i].tasa == null){
          tasa = respuesta[i].tasa;
        }else{
          tasa = respuesta[i].tasa + "%"
        }
        if(!$('#impuestos-head-'+respuesta[i].id).length){
        html += '<tr>'+
                  /*'<th style="text-align: right;">'+respuesta[i].producto+'</th>'+*/
                  '<td style="text-align: right;" id="impuestos-head-'+respuesta[i].id+'">'+respuesta[i].nombre+'</td>'+
                  '<td style="text-align: right;">'+tasa+' </td>'+
                  '<td style="text-align: right;">.....</td>'+
                  '<td style="text-align: right;"> $ '+ dosDecimales(respuesta[i].totalImpuesto) +'</td>'+
                '</tr>';
        }
         

      });
      
      $('#impuestos').html(html);
      
    },
    error:function(error){
      console.log(error);
    }
  });

  //Obtener total
  $.ajax({
    
    url:"../../php/funciones.php",
    data:{clase:"get_data", funcion:"get_totalEntradaOC",datos:PKOrden},
    dataType:"json",
    success:function(respuesta){
      $('#Total').html(dosDecimales(respuesta[0].Total))
    },
    error:function(error){
      console.log(error);
    }
  });
}

function loadProducts(data,input){
  var html ='';
  $.ajax({
    url:"../../php/funciones.php",
  	data:{clase:"get_data", funcion:"get_cmb_productsEntry", data:data},
  	dataType:"json",
    success:function(respuesta){
      console.log("respuesta:",respuesta);

      html += '<option data-placeholder="true"></option>';

      if(respuesta !== "" && respuesta !== null){
        $.each(respuesta,function(i){
          if(data === respuesta[i].PKProducto){
            selected = 'selected';
          }else{
            selected = '';
          }
          html += '<option value="'+respuesta[i].PKProducto+'" '+selected+'>'+respuesta[i].Nombre+'</option>';
        });

      }else{
        html += '<option value="vacio">No hay productos que mostrar</option>';
      }

      $('#'+input+'').html(html);
    },
    error:function(error){
      console.log(error);
    }

  });

  $('#'+input+'').html(html);

  $('#'+input+' option:not(:selected)').remove();

  obtenerDatosParcial(data);
}

function obtenerDatosParcial(pkOrden){
  
  $.ajax({
    url:"../../php/funciones.php",
    data:{clase:"get_data", funcion:"get_datos_OrdenCompra", data:pkOrden},
    dataType:"json",
    success:function(respuesta){
      $('#txtReferenciaP').html(respuesta[0].Referencia);
      $('#txtFechaEmisionP').html(respuesta[0].FechaCreacion);
      $('#txtFechaEstimadaP').html(respuesta[0].FechaEstimada);
      $('#txtProveedorP').html(respuesta[0].NombreComercial);
      $('#NotasInternasP').html(respuesta[0].NotasInternas);
      $('#txtDireccionP').html("Sucursal:"+respuesta[0].Sucursal +". "+
                            respuesta[0].Calle +" "+
                            respuesta[0].NumExt +", "+
                            respuesta[0].Prefijo +", "+
                            respuesta[0].NumInt +", "+
                            respuesta[0].Colonia +", "+
                            respuesta[0].Municipio +", "+
                            respuesta[0].Estado +", "+
                            respuesta[0].Pais +" ");
    },
    error:function(error){
      console.log(error);
    }
  });
}

$(document).on('change','#cmbProductoOrdenCompra',function(){
  
  var idProducto = $('#cmbProductoOrdenCompra').val();
  var idOrdenC = $('#cmbOrdenCompra').val();
  
  getCantidadInicialOC (idProducto,idOrdenC);

  validEmptyInput('cmbProductoOrdenCompra', 'invalid-producto', 'Se requiere el producto a recibir.')
});

function getCantidadInicialOC (idProducto,idOrdenC){
  $.ajax({
    url:"../../php/funciones.php",
    data:{clase:"get_data", funcion:"get_info_CantidadProductoOC", data:idProducto, data2:idOrdenC},
    dataType:"json",
    success:function(respuesta){
      if(respuesta.length >0){
        $('#txtCantidad').val(respuesta[0].Cantidad);
        $('#txtCantidadHis').val(respuesta[0].Cantidad);
        $('#txtIdDetalle').val(respuesta[0].idDetalle);

        validEmptyInput('txtCantidad', 'invalid-cantidad', 'Se requiere la cantidad a recibir.');
      }else{
        $('#txtCantidad').val('');
        $('#txtCantidadHis').val('');
        $('#txtIdDetalle').val('');
      }
    },
    error:function(error){
      console.log(error);
    }
  });
}

$(document).on("click", "#btnAnadirProductoParcial", function () {


  if ($("#frmEntradaParcial")[0].checkValidity()) {
    var badProducto =
      $("#invalid-producto").css("display") === "block" ? false : true;
    var badCantidad =
      $("#invalid-cantidad").css("display") === "block" ? false : true;

    if (
      badProducto &&
      badCantidad 
    ) {
      var idDetalle = $("#txtIdDetalle").val();
      var cantidad = $("#txtCantidad").val();
      var idOrdenCompra = $('#cmbOrdenCompra').val();

      $.ajax({
        url: "../../php/funciones.php",
        data: {
          clase: "get_data",
          funcion: "validar_productoEntradaOC",
          data: idDetalle,
        },
        dataType: "json",
        success: function (data) {
          console.log("respuesta nombre valida: ", data);
          /* Validar si ya existe el identificador con ese nombre*/
          if (parseInt(data[0]["existe"]) == 1) {
            
            Swal.fire({
              title:
                '<h3 style="arialRoundedEsp;">El producto ya se encuentra agregado<h3>',
              html:
                '<h5 style="arialRoundedEsp;">¿Desea agregar la nueva cantidad a la ya existente?.<h5>',
              icon: "success",
              showConfirmButton: true,
              focusConfirm: false,
              showCloseButton: false,
              showCancelButton: true,
              confirmButtonText:
                'Si   <i class="far fa-arrow-alt-circle-right"></i>',
              cancelButtonText: 'No   <i class="far fa-times-circle"></i>',
              buttonsStyling: false,
              allowEnterKey: false,
            }).then((result) => {
              if (result.isConfirmed) {
                var element = document.getElementById("content");
                element.scrollIntoView();

                //actualización de datos a tabla
                $.ajax({
                  url: "../../php/funciones.php",
                  data: {
                    clase: "edit_data",
                    funcion: "edit_entrada_OCTemp",
                    data: idDetalle, data2: cantidad
                  },
                  dataType: "json",
                  success: function (respuesta) {
                    console.log("respuesta agregar orden de compra:", respuesta);
              
                    if (respuesta[0].status) {
                      $("#tblProductosOrdenCompraParcial").DataTable().ajax.reload();
                      obtenerTotal(idOrdenCompra);
                      var idProducto = $('#cmbProductoOrdenCompra').val();
                      var idOrdenC = $('#cmbOrdenCompra').val();
                      
                      getCantidadInicialOC (idProducto,idOrdenC);
                      Swal.fire(
                        "Actualización exitosa",
                        "Se actualizó la cantidad del producto en la entrada con exito",
                        "success"
                      );
                      
                    } else {
                      Swal.fire("Error", "No se actualizó la cantidad del producto en la orden de compra con exito", "warning");
                    }
                  },
                  error: function (error) {
                    console.log(error);
                  },
                });
                
              } else if (result.dismiss === Swal.DismissReason.cancel) {
                /*No hacer nada*/
              } else {
                /*No hacer nada*/
              }
            });
    
            console.log("¡Ya existe!");
          } else {
            /*Agregar producto a la entrada*/
            guardarProductoEntradaParcial();
    
            console.log("¡No existe!");
          }          
        },
      });
    }
  } else {
    console.log("No validados");
    if (!$("#cmbProductoOrdenCompra").val()) {
      $("#invalid-producto").css("display", "block");
      $("#cmbProductoOrdenCompra").addClass("is-invalid");
    }
    if (!$("#txtCantidad").val()) {
      $("#invalid-cantidad").css("display", "block");
      $("#txtCantidad").addClass("is-invalid");
    }
  }
});

function guardarProductoEntradaParcial(){
  var datos = {
    idProducto: $("#cmbProductoOrdenCompra").val(),
    cantidad: $("#txtCantidad").val(),
    idDetalle: $("#txtIdDetalle").val(),
  };

  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "save_data",
      funcion: "save_entry_partial_oc",
      datos,
    },
    dataType: "json",
    success: function (respuesta) {
      console.log(respuesta);
      if (respuesta[0].status) {
        $("#tblProductosOrdenCompraParcial").DataTable().ajax.reload();
        var idOrdenC = $('#cmbOrdenCompra').val();
        var pkProducto = $("#cmbProductoOrdenCompra").val();
        
        getCantidadInicialOC(pkProducto,idOrdenC);
        
      } else {
       
      }
    },
    error: function (error) {
      console.log(error);
      
    },
  });
}

$(document).on("click", "#btnAgregarEntradaOC", function () {

  var IVAs, IEPs, Descuentos
  if ($("#txtIEPS").val() == null || $("#txtIEPS").val() == ''){
    IEPs= 0;
  }else{
    IEPs = $("#txtIEPS").val();
  }

  if ($("#txtIva").val() == null || $("#txtIva").val() == ''){
    IVAs= 0;
  }else{
    IVAs = $("#txtIva").val();
  }

  if ($("#txtDescuento").val() == null || $("#txtDescuento").val() == ''){
    Descuentos= 0;
  }else{
    Descuentos = $("#txtDescuento").val();
  }

  var emptyTable = document.querySelectorAll(".invalid-empty");
  var emptyTableCount = document.querySelectorAll(".invalid-emptyCount");
  
  emptyTable.forEach(element => {
    if (!element.value) {
      element.classList.add("is-invalid");
    }
  });

  emptyTableCount.forEach(element => {
    if (!element.value) {
      element.classList.add("is-invalid");
    }

    if(element.value <= 0 ){
      element.classList.add("is-invalid");
    }
  });

  if ($("#frmEntradaOC")[0].checkValidity()) {
    var badNoDocumento =
      $("#invalid-noDocumento").css("display") === "block" ? false : true;
    var badSerie =
      $("#invalid-serie").css("display") === "block" ? false : true;
    var badSubtotal =
      $("#invalid-subtotal").css("display") === "block" ? false : true;
    var badImporte =
      $("#invalid-importe").css("display") === "block" ? false : true;
    var badFechaFactura =
      $("#invalid-fechaFactura").css("display") === "block" ? false : true;  

    if (
      badNoDocumento &&
      badSerie &&
      badSubtotal &&
      badImporte &&
      badFechaFactura
    ) {
      var datos = {
        ordenCompra: $("#cmbOrdenCompra").val(),
        proveedor: $("#cmbProveedor").val(),
        sucursal: $("#cmbSucursal").val(),
        noDocumento: $("#txtNoDocumento").val(),
        serie: $("#txtSerie").val(),
        subtotal: $("#txtSubtotal").val(),
        iva: IVAs,
        ieps: IEPs,
        importe: $("#txtImporte").val(),
        descuento: Descuentos,
        fechaFactura: $("#txtFechaFactura").val(),
        remision: {
          active: $("#cbxRemision").is(":checked") ? 2 : 1,
        },
        notas: $("#txaNotaEntrada").val(),
      }

      $.ajax({
        url: "../../php/funciones.php",
        data: {
          clase: "save_data",
          funcion: "save_entry_partial_ocTable",
          datos,
        },
        dataType: "json",
        success: function (respuesta) {
          console.log(respuesta);
          if (respuesta[0].status) {

            //window.location.href= "../entradas_productos";
            $("#folio_entrada").val(respuesta[0].folio_entrada);
            $("#id_cuenta_pagar").val(respuesta[0].id_cuenta_pagar);
            $("#folio_doc").val($("#txtNoDocumento").val());
            $("#serie_doc").val('N/A'/* $("#txtSerie").val() */);
            $('#fullInvoice').modal('show');
          } else {
           
          }
        },
        error: function (error) {
          console.log(error);
          
        },
      });
      
    }
  } else {
    console.log("No validados 3");
    if (!$("#cmbTipoEntrada").val()) {
      console.log("1...");
    }

    if (!$("#cmbOrdenCompra").val()) {
      console.log("2...");
    }

    if (!$("#cmbProveedor").val()) {
      console.log("8...");
    }

    if (!$("#cmbSucursal").val()) {
      console.log("9...");
    }

    if (!$("#txtDescuento").val()) {
      console.log("10...");
    }

    if (!$("#txtNoDocumento").val()) {
      $("#invalid-noDocumento").css("display", "block");
      $("#txtNoDocumento").addClass("is-invalid");
      console.log("3...");
    }
    if (!$("#txtSerie").val()) {
      $("#invalid-serie").css("display", "block");
      $("#txtSerie").addClass("is-invalid");
      console.log("4...");
    }
    if (!$("#txtSubtotal").val()) {
      $("#invalid-subtotal").css("display", "block");
      $("#txtSubtotal").addClass("is-invalid");
      console.log("5...");
    }
    if (!$("#txtImporte").val()) {
      $("#invalid-importe").css("display", "block");
      $("#txtImporte").addClass("is-invalid");
      console.log("6...");
    }
    if (!$("#txtFechaFactura").val()) {
      $("#invalid-fechaFactura").css("display", "block");
      $("#txtFechaFactura").addClass("is-invalid");
      console.log("7...");
    }
  }

  /*$("#folio_entrada").val("EC000017");
  $("#id_cuenta_pagar").val("147");
  $("#folio_doc").val("JULIO210730");
  $("#serie_doc").val("101219AM");
  $('#fullInvoice').modal('show');*/
});

$(document).on("click", "#btnAgregarEntradaTraspaso", function () {

  var emptyTable = document.querySelectorAll(".invalid-empty");
  var emptyTableCount = document.querySelectorAll(".invalid-emptyCount");
  
  emptyTable.forEach(element => {
    if (!element.value) {
      element.classList.add("is-invalid");
    }
  });

  emptyTableCount.forEach(element => {
    if (!element.value) {
      element.classList.add("is-invalid");
    }

    if(element.value <= 0 ){
      element.classList.add("is-invalid");
    }
  });

    var datos = {
      ordenPedido: $("#cmbOrderPedido").val()
    }

    $.ajax({
      url: "../../php/funciones.php",
      data: {
        clase: "save_data",
        funcion: "save_entry_tranfer_Table",
        datos,
      },
      dataType: "json",
      success: function (respuesta) {
        console.log(respuesta);
        if (respuesta[0].status) {
          Lobibox.notify("success", {
            size: "mini",
            rounded: true,
            delay: 2000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../../../img/timdesk/checkmark.svg",
            msg: "¡Se ha registrado la entrada éxito!",
            sound: '../../../../../sounds/sound4'
          });
          descargarPDFEntradaTraspaso(respuesta[0].folioEntrada);
        } else {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../../../img/timdesk/notificacion_error.svg",
            msg: "¡Algo salio mal :(!",
            sound: '../../../../../sounds/sound4'
          });
        }
      },
      error: function (error) {
        console.log(error);
        
      },
    });
  
  /*descargarPDFEntradaTraspaso("EC000020");*/
  
});

$(document).on("click", "#btnAgregarEntradaED", function () {

  var emptyTable = document.querySelectorAll(".invalid-empty");
  var emptyTableCount = document.querySelectorAll(".invalid-emptyCount");
  
  emptyTable.forEach(element => {
    if (!element.value) {
      element.classList.add("is-invalid");
    }
  });

  emptyTableCount.forEach(element => {
    if (!element.value) {
      element.classList.add("is-invalid");
    }

    if(element.value <= 0 ){
      element.classList.add("is-invalid");
    }
  });

  if ($("#frmEntradaOC")[0].checkValidity()) {
    var badReferenciaED =
      $("#invalid-referenciaED").css("display") === "block" ? false : true;

    if (
      badReferenciaED
    ) {
      var datos = {
        referencia: $("#txtReferenciaED").val(),
        sucursalEntrada: $("#cmbSucursalEntrada").val(),
        sucursalOrigen: $("#cmbSucursalOrigenED").val(),
        notas: $("#txtNotaEntradaED").val(),
      }
      
      $.ajax({
        url: "../../php/funciones.php",
        data: {
          clase: "save_data",
          funcion: "save_entry_partial_edTable",
          datos,
        },
        dataType: "json",
        success: function (respuesta) {
          if (respuesta[0].status) {
            Lobibox.notify("success", {
              size: "mini",
              rounded: true,
              delay: 2000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../../img/timdesk/checkmark.svg",
              msg: "¡Se ha registrado la entrada éxito!",
              sound: '../../../../../sounds/sound4'
            });
            setTimeout(function(){
              window.location.href= "../entradas_productos";
            },1000);
          } else {
            Lobibox.notify("error", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../../img/timdesk/notificacion_error.svg",
              msg: "¡Algo salio mal :(!",
              sound: '../../../../../sounds/sound4'
            });
          }
        },
        error: function (error) {
          console.log(error);
        },
      });
      
    }
  } else {
    console.log("No validados 3");

    if (!$("#txtReferenciaED").val()) {
      $("#invalid-noDocumento").css("display", "block");
      $("#txtReferenciaED").addClass("is-invalid");
      console.log("3...");
    }
  }
});

$(document).on("click", "#btnAgregarEntradaEDProvider", function () {

  //si esta checkeado crear una cuenta por pagar, hace requeridos a los campos
  if($("#check_IsCuentaCobrar").is(':checked')){
    $("#txtfecha").prop('required', true);
    $("#txtNoDocumentoProviderED").prop('required', true);
    $("#txtSerieProviderED").prop('required', true);
    $("#txtSubtotalProviderED").prop('required', true);
    $("#txtImporteProviderED").prop('required', true);
    $("#cmbCategoriaCuenta").prop('required', true);
    $('#cmbSubcategoriaCuenta').prop('required', true);
  }else{
    $("#txtfecha").prop('required', false);
    $("#txtNoDocumentoProviderED").prop('required', false);
    $("#txtSerieProviderED").prop('required', false);
    $("#txtSubtotalProviderED").prop('required', false);
    $("#txtImporteProviderED").prop('required', false);
    $("#cmbCategoriaCuenta").prop('required', false);
    $('#cmbSubcategoriaCuenta').prop('required', false);
  }

  var emptyTable = document.querySelectorAll(".invalid-empty");
  var emptyTableCount = document.querySelectorAll(".invalid-emptyCount");
  
  emptyTable.forEach(element => {
    if (!element.value) {
      element.classList.add("is-invalid");
    }
  });

  emptyTableCount.forEach(element => {
    if (!element.value) {
      element.classList.add("is-invalid");
    }

    if(element.value <= 0 ){
      element.classList.add("is-invalid");
    }
  });

  if ($("#frmEntradaOC")[0].checkValidity()) {
    let badTipoED = true;
      /* $("#invalid-tipoProviderED").css("display") === "block" ? false : true;
    let badSerieED = true; */
    let badFolioED = true;
    let badSubtotalED = true;
    let badImporteED = true;
    let badFechaED = true;
    let badCategoria = true;
    let badSubcategoria = true;

    if($("#check_IsCuentaCobrar").is(':checked')){
      /* badSerieED =
        $("#invalid-serieProviderED").css("display") === "block" ? false : true; */
      badFolioED =
        $("#invalid-noDocumentoProviderED").css("display") === "block" ? false : true;
      badSubtotalED =
        $("#invalid-subtotalProviderED").css("display") === "block" ? false : true;
      badImporteED =
        $("#invalid-importeProviderED").css("display") === "block" ? false : true;
      badFechaED =
        $("#invalid-fechaFacturaProviderED").css("display") === "block" ? false : true;
      badTipoED =
        $("#invalid-tipoProviderED").css("display") === "block" ? false : true;
      badCategoria =
        $("#invalid-categoriaCuenta").css("display") === "block" ? false : true;
      badSubcategoria = 
        $("#invalid-subcategoriaCuenta").css("display") === "block" ? false : true;
    }

    if (
      /* badSerieED && */
      badFolioED &&
      badTipoED &&
      badSubtotalED &&
      badImporteED &&
      badFechaED &&
      badCategoria &&
      badSubcategoria
    ) {
      let datos ;
      let OrdenCompra = $("#cmbOCProveedor").val() ? $("#cmbOCProveedor").val() : 0;

      if($("#check_IsCuentaCobrar").is(':checked')){
        datos= {
          sucEntrada: $("#cmbSucursalEntrada").val(),
          proveedor: $("#cmbProveedorED").val(),
          /* serie: $("#txtSerieProviderED").val(), */
          folio: $("#txtNoDocumentoProviderED").val(),
          tipoEntradas: $("#cmbTipoProviderED").val(),
          subtotal: $("#txtSubtotalProviderED").val(),
          iva: $("#txtIvaProviderED").val(),
          ieps: $("#txtIEPSProviderED").val(),
          importe: $("#txtImporteProviderED").val(),
          fecha: $("#txtFechaFacturaProviderED").val(),
          fechaVenci: $("#txtfechavenci").val() ? $("#txtfechavenci").val() : '0000-00-00',
          notas: $("#txtNotaEntradaEDProvider").val(),
          addCuentaPagar:  1,
          ordenCompra: OrdenCompra,
          categoria: $("#cmbCategoriaCuenta").val(),
          subcategoria: $("#cmbSubcategoriaCuenta").val()
        }
      }else{
        datos= {
          sucEntrada: $("#cmbSucursalEntrada").val(),
          proveedor: $("#cmbProveedorED").val(),
          notas: $("#txtNotaEntradaEDProvider").val(),
          addCuentaPagar:  0,
          ordenCompra: OrdenCompra
        }
      } 

      /* var importe = 0;
      if($("#txtImporteProviderED").val() != ''){
        importe = $("#txtImporteProviderED").val();
      }else{
        importe = 0;
      } */
      //Obtener total
      /* $.ajax({
        url:"../../php/funciones.php",
        data:{clase:"get_data", funcion:"get_totalEntradaEDProviderTemp"},
        dataType:"json",
        success:function(respuesta){
          $('#Total').html(dosDecimales(respuesta[0].Total));
          if(dosDecimales(importe) != dosDecimales(respuesta[0].Total)){
            $("#invalid-totalED").css("display", "block");
            $("#Total").addClass("is-invalid");

            var diferencia = respuesta[0].Total - importe;
            Lobibox.notify("error", {
              size: "mini",
              rounded: true,
              delay: 5000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../../img/timdesk/notificacion_error.svg",
              msg: "¡El total de la entrada y el importe de la factura posee una diferncia de: $"+ dosDecimales(Math.abs(diferencia)) +"!",
              sound: '../../../../../sounds/sound4'
            });
          }else{
            $("#invalid-totalED").css("display", "none");
            $("#Total").removeClass("is-invalid");
            $("#invalid-importeProviderED").css("display", "none");
            $("#importeProviderED").removeClass("is-invalid");
            $.ajax({
              url: "../../php/funciones.php",
              data: {
                clase: "save_data",
                funcion: "save_entry_partial_edProviderTable",
                datos,
              },
              dataType: "json",
              success: function (respuesta) {
                if (respuesta[0].status) {
                  Lobibox.notify("success", {
                    size: "mini",
                    rounded: true,
                    delay: 2000,
                    delayIndicator: false,
                    position: "center top",
                    icon: true,
                    img: "../../../../img/timdesk/checkmark.svg",
                    msg: "¡Se ha registrado la entrada éxito!",
                    sound: '../../../../../sounds/sound4'
                  });
                  setTimeout(function(){
                    window.location.href= "../entradas_productos";
                  },1000);
                } else {
                  Lobibox.notify("error", {
                    size: "mini",
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: "center top",
                    icon: true,
                    img: "../../../../img/timdesk/notificacion_error.svg",
                    msg: "¡Algo salio mal :(!",
                    sound: '../../../../../sounds/sound4'
                  });
                }
              },
              error: function (error) {
                console.log(error);
              },
            });
          }
        },
        error:function(error){
          console.log(error);
        }
      }); */
    
      //registra datos
      $.ajax({
        url: "../../php/funciones.php",
        data: {
          clase: "save_data",
          funcion: "save_entry_partial_edProviderTable",
          datos,
        },
        dataType: "json",
        success: function (respuesta) {
          if (respuesta[0].status) {
            if(datos.addCuentaPagar == 0){
              Lobibox.notify("success", {
                size: "mini",
                rounded: true,
                delay: 2000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../../../img/timdesk/checkmark.svg",
                msg: "¡Se ha registrado la entrada éxito!",
                sound: '../../../../../sounds/sound4'
              });
              setTimeout(function(){
                window.location.href= "../entradas_productos";
              },1000);
            }else{
              $("#folio_entrada").val(respuesta[0].insertado);
              $("#id_cuenta_pagar").val(respuesta[0].cuentaPagar);
              $("#folio_doc").val($("#txtNoDocumentoProviderED").val());
              $("#serie_doc").val('N/A'/* $("#txtSerieProviderED").val() */);
              $('#fullInvoice').modal('show');
            }
          } else {
            Lobibox.notify("error", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../../img/timdesk/notificacion_error.svg",
              msg: "¡Algo salio mal :(!",
              sound: '../../../../../sounds/sound4'
            });
          }
        },
        error: function (error) {
          console.log(error);
        },
      });
    }
  } else {
    $("#frmEntradaOC")[0].classList.add('was-validated');

    if($("#check_IsCuentaCobrar").is(':checked')){
      if (!$("#txtSerieProviderED").val()) {
        $("#invalid-serieProviderED").css("display", "block");
        $("#txtSerieProviderED").addClass("is-invalid");
      }else{
        $("#invalid-serieProviderED").css("display", "none");
        $("#txtSerieProviderED").removeClass("is-invalid");
      }
  
      if (!$("#txtNoDocumentoProviderED").val()) {
        $("#invalid-noDocumentoProviderED").css("display", "block");
        $("#txtNoDocumentoProviderED").addClass("is-invalid");
      }else{
        $("#invalid-noDocumentoProviderED").css("display", "none");
        $("#txtNoDocumentoProviderED").removeClass("is-invalid");
      }
  
      if (!$("#txtSubtotalProviderED").val()) {
        $("#invalid-subtotalProviderED").css("display", "block");
        $("#txtSubtotalProviderED").addClass("is-invalid");
      }else{
        $("#invalid-subtotalProviderED").css("display", "none");
        $("#txtSubtotalProviderED").removeClass("is-invalid");
      }
  
      if (!$("#txtImporteProviderED").val()) {
        $("#invalid-importeProviderED").css("display", "block");
        $("#txtImporteProviderED").addClass("is-invalid");
      }else{
        $("#invalid-importeProviderED").css("display", "none");
        $("#txtImporteProviderED").removeClass("is-invalid");
      }
  
      if (!$("#txtFechaFacturaProviderED").val()) {
        $("#invalid-fechaFacturaProviderED").css("display", "block");
        $("#txtFechaFacturaProviderED").addClass("is-invalid");
      }else{
        $("#invalid-fechaFacturaProviderED").css("display", "none");
        $("#txtFechaFacturaProviderED").removeClass("is-invalid");
      }

      if (!$("#cmbTipoProviderED").val()) {
        $("#invalid-tipoProviderED").css("display", "block");
        $("#cmbTipoProviderED").addClass("is-invalid");
      }else{
        $("#invalid-tipoProviderED").css("display", "none");
        $("#cmbTipoProviderED").removeClass("is-invalid");
      }
    }
  }
});

$(document).on("click", "#btnAgregarEntradaEDCustomer", function () {

  var emptyTable = document.querySelectorAll(".invalid-empty");
  var emptyTableCount = document.querySelectorAll(".invalid-emptyCount");
  
  emptyTable.forEach(element => {
    if (!element.value) {
      element.classList.add("is-invalid");
    }
  });

  emptyTableCount.forEach(element => {
    if (!element.value) {
      element.classList.add("is-invalid");
    }

    if(element.value <= 0 ){
      element.classList.add("is-invalid");
    }
  });

  if ($("#frmEntradaOC")[0].checkValidity()) {
    var badReferenciaED =
      $("#invalid-referenciaCustomerED").css("display") === "block" ? false : true;

    if (
      badReferenciaED
    ) {
      var datos = {
        referencia: $("#txtReferenciaCustomerED").val(),
        sucursalEntrada: $("#cmbSucursalEntrada").val(),
        cliente: $("#cmbClienteED").val(),
        notas: $("#txtNotaEntradaCustomerED").val(),
      }
      
      $.ajax({
        url: "../../php/funciones.php",
        data: {
          clase: "save_data",
          funcion: "save_entry_partial_edCustomerTable",
          datos,
        },
        dataType: "json",
        success: function (respuesta) {
          if (respuesta[0].status) {
            Lobibox.notify("success", {
              size: "mini",
              rounded: true,
              delay: 2000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../../img/timdesk/checkmark.svg",
              msg: "¡Se ha registrado la entrada éxito!",
              sound: '../../../../../sounds/sound4'
            });
            setTimeout(function(){
              window.location.href= "../entradas_productos";
            },1000);
          } else {
            Lobibox.notify("error", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../../img/timdesk/notificacion_error.svg",
              msg: "¡Algo salio mal :(!",
              sound: '../../../../../sounds/sound4'
            });
          }
        },
        error: function (error) {
          console.log(error);
        },
      });
      
    }
  } else {

    if (!$("#txtReferenciaCustomerED").val()) {
      $("#invalid-referenciaCustomerED").css("display", "block");
      $("#txtReferenciaCustomerED").addClass("is-invalid");
    }else{
      $("#invalid-referenciaCustomerED").css("display", "none");
      $("#txtReferenciaCustomerED").removeClass("is-invalid");
    }
  }
});

$(document).on("click", "#btnCerrarOC", function () {
  $('#cerrar_OrdenCompra').modal('show');
});

function cerrar_OrdenCompra(){
  var idOrden = $("#cmbOrdenCompra").val();

  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "edit_data",
      funcion: "edit_estatusOC",
      data: idOrden,
    },
    dataType: "json",
    success: function (respuesta) {

      if (respuesta[0].status) {
        
        Lobibox.notify("success", {
          size: "mini",
          rounded: true,
          delay: 2000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/checkmark.svg",
          msg: "¡Se ha cerrado la orden de compra con éxito!",
          sound: '../../../../../sounds/sound4'
        });

        setTimeout(function(){window.location.href = "../entradas_productos"},3000);
      } else {
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/notificacion_error.svg",
          msg: "¡Algo salio mal :(!",
          sound: '../../../../../sounds/sound4'
        });
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function dosDecimales(n) {
  return Number.parseFloat(n).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
}

var controladorTiempo = "";
function escribiendo(inputID, invalidDivID, textInvalidDiv) {
  clearTimeout(controladorTiempo);
  //Llamar la busqueda cuando el usuario deje de escribir
  controladorTiempo = setTimeout(
    function() {
      validEmptyInput(inputID, invalidDivID, textInvalidDiv)
    },
    100,
  );
}

function validEmptyInput(inputID, invalidDivID, textInvalidDiv) {
  if (!$("#" + inputID).val()) {
    $("#" + inputID).addClass("is-invalid");
    $("#" + invalidDivID).css("display", "block");
    $("#" + invalidDivID).text(textInvalidDiv);
  } else {
    $("#" + inputID).removeClass("is-invalid");
    $("#" + invalidDivID).css("display", "none");
    $("#" + invalidDivID).text(textInvalidDiv);
  }

  if(inputID.substr(0,11) == "txtCantidad"){
    var cantEntrada = parseInt($("#" + inputID).val());
    var idEntradaTemp = inputID.substr(12);

    if(cantEntrada < 0){
      $("#"+invalidDivID).css("display", "block");
      $("#"+invalidDivID).text("La cantidad no puede ser menor a 0");
      $("#"+inputID).addClass("is-invalid");
    }else{
      $.ajax({
        url: "../../php/funciones.php",
        data: {
          clase: "get_data",
          funcion: "validar_cantidadProd_entradaOC",
          data: idEntradaTemp,
          data2: cantEntrada,
        },
        dataType: "json",
        success: function (data) {
          if (parseInt(data[0]["existe"]) == 1) {
            $("#"+invalidDivID).css("display", "block");
            $("#"+invalidDivID).text(
              "La suma de cantidades de recepción del producto no puede ser mayor a la cantidad restante: "+data[0]["Restante"]+"."
            );
            $("#"+inputID).addClass("is-invalid");
            console.log("¡Cantidad no válida!");
          } else {
            $("#"+invalidDivID).css("display", "none");
            $("#"+invalidDivID).text(
              ""
            );
            $("#"+inputID).removeClass("is-invalid");
            console.log("¡Cantidad válida!");

            $.ajax({
              url: "../../php/funciones.php",
              data: {
                clase: "edit_data",
                funcion: "edit_amount_entry_partial_temp",
                data: cantEntrada, data2:idEntradaTemp,
              },
              dataType: "json",
              success: function (respuesta) {
                console.log(respuesta);
                if (respuesta[0].status) {
                  console.log("Actualizando importe");
                  $("#tblProductosOrdenCompraParcial").DataTable().ajax.reload();
                  obtenerTotal($('#cmbOrdenCompra').val());
                } else {
                
                }
              },
              error: function (error) {
                console.log(error);
                
              },
            });
          }
        },
      });
    }
  }

  if(inputID.substr(0,7) == "txtLote"){
    var lote = $("#" + inputID).val();
    var idEntradaTemp = inputID.substr(8);

    $.ajax({
      url: "../../php/funciones.php",
      data: {
        clase: "get_data",
        funcion: "validar_loteProd_entradaOC",
        data: idEntradaTemp,
        data2: lote,
      },
      dataType: "json",
      success: function (data) {
        if (parseInt(data[0]["existe"]) == 1) {
          $("#"+invalidDivID).css("display", "block");
          $("#"+invalidDivID).text(
            "El lote ingresado ya se encuentra registrado."
          );

          $("#"+inputID).addClass("is-invalid");
          console.log("Lote no válido!");
        } else {
          $("#"+invalidDivID).css("display", "none");
          $("#"+invalidDivID).text(
            ""
          );
          $("#"+inputID).removeClass("is-invalid");
          console.log("Lote válido!");

          $.ajax({
            url: "../../php/funciones.php",
            data: {
              clase: "edit_data",
              funcion: "edit_lot_entry_partial_temp",
              data: lote, data2:idEntradaTemp,
            },
            dataType: "json",
            success: function (respuesta) {
              console.log(respuesta);
              if (respuesta[0].status) {
                console.log("Actualizando...");
                $("#tblProductosOrdenCompraParcial").DataTable().ajax.reload();
                obtenerTotal($('#cmbOrdenCompra').val());
              } else {
               
              }
            },
            error: function (error) {
              console.log(error);
              
            },
          });
        }
      },
    });
  }

  if(inputID.substr(0,8) == "txtSerie"){
    var serie = $("#" + inputID).val();
    var idEntradaTemp = inputID.substr(9);

    $.ajax({
      url: "../../php/funciones.php",
      data: {
        clase: "get_data",
        funcion: "validar_serieProd_entradaOC",
        data: idEntradaTemp,
        data2: serie,
      },
      dataType: "json",
      success: function (data) {
        if (parseInt(data[0]["existe"]) == 1) {
          $("#"+invalidDivID).css("display", "block");
          $("#"+invalidDivID).text(
            "La serie ingresada ya se encuentra registrada."
          );

          $("#"+inputID).addClass("is-invalid");
          console.log("Serie no válida!");
        } else {
          $("#"+invalidDivID).css("display", "none");
          $("#"+invalidDivID).text(
            ""
          );
          $("#"+inputID).removeClass("is-invalid");
          console.log("Serie válida!");

          $.ajax({
            url: "../../php/funciones.php",
            data: {
              clase: "edit_data",
              funcion: "edit_serie_entry_partial_temp",
              data: serie, data2:idEntradaTemp,
            },
            dataType: "json",
            success: function (respuesta) {
              console.log(respuesta);
              if (respuesta[0].status) {
                console.log("Actualizando...");
                $("#tblProductosOrdenCompraParcial").DataTable().ajax.reload();
                obtenerTotal($('#cmbOrdenCompra').val());
              } else {
               
              }
            },
            error: function (error) {
              console.log(error);
              
            },
          });
        }
      },
    });
  }

  if(inputID.substr(0,12) == "txtCaducidad"){
    var caducidad = $("#" + inputID).val();
    var idEntradaTemp = inputID.substr(13);

    var loteSerie = $("#txtLote-" + idEntradaTemp).val();

    if (loteSerie == ''){
      loteSerie = null;
    }

    $.ajax({
      url: "../../php/funciones.php",
      data: {
        clase: "get_data",
        funcion: "validar_caducidadProd_entradaOC",
        data: idEntradaTemp,
        data2: caducidad,
        data3: loteSerie,
      },
      dataType: "json",
      success: function (data) {
        if (parseInt(data[0]["existe"]) == 1) {
          $("#"+invalidDivID).css("display", "block");
          $("#"+invalidDivID).text(
            "La caducidad ingresada ya se encuentra registrada para este producto."
          );

          $("#"+inputID).addClass("is-invalid");
          console.log("Caducidad no válida!");
        } else {
          $("#"+invalidDivID).css("display", "none");
          $("#"+invalidDivID).text(
            ""
          );
          $("#"+inputID).removeClass("is-invalid");
          console.log("Caducidad válida!");

          $.ajax({
            url: "../../php/funciones.php",
            data: {
              clase: "edit_data",
              funcion: "edit_caducidad_entry_partial_temp",
              data: caducidad, data2:idEntradaTemp,
            },
            dataType: "json",
            success: function (respuesta) {
              console.log(respuesta);
              if (respuesta[0].status) {
                console.log("Actualizando...");
                $("#tblProductosOrdenCompraParcial").DataTable().ajax.reload();
                obtenerTotal($('#cmbOrdenCompra').val());
              } else {
               
              }
            },
            error: function (error) {
              console.log(error);
              
            },
          });
        }
      },
    });
  }

  if(inputID == "txtNoDocumento"){
    validarFolio();
  }

  if(inputID == "txtSerie"){
    validarSerie();
  }
}

function validEmptyInputSL(inputID, invalidDivID, textInvalidDiv) {
  if (!$("#" + inputID).val()) {
    $("#" + inputID).addClass("is-invalid");
    $("#" + invalidDivID).css("display", "block");
    $("#" + invalidDivID).text(textInvalidDiv);
  } else {
    $("#" + inputID).removeClass("is-invalid");
    $("#" + invalidDivID).css("display", "none");
    $("#" + invalidDivID).text(textInvalidDiv);
  }

  if(inputID.substr(0,13) == "txtCantidadED"){
    var cantEntrada = parseInt($("#" + inputID).val());
    var idEntradaTemp = inputID.substr(14);

    if(cantEntrada <= 0){
      $("#"+invalidDivID).css("display", "block");
      $("#"+invalidDivID).text("La cantidad no puede ser menor o igual a 0");
      $("#"+inputID).addClass("is-invalid");
    }else{
      $("#"+invalidDivID).css("display", "none");
      $("#"+invalidDivID).text("");
      $("#"+inputID).removeClass("is-invalid");

      $.ajax({
        url: "../../php/funciones.php",
        data: {
          clase: "edit_data",
          funcion: "edit_cantidad_entryDirect_temp",
          data: cantEntrada, data2:idEntradaTemp,
        },
        dataType: "json",
        success: function (respuesta) {
          console.log(respuesta);
          if (respuesta[0].status) {
            $("#tblProductosEntradaDCSucursal").DataTable().ajax.reload();
          } else {
            
          }
        },
        error: function (error) {
          console.log(error);
          
        },
      });
    }
  }

  if(inputID.substr(0,7) == "txtLote"){
    var lote = $("#" + inputID).val();
    var idEntradaTemp = inputID.substr(8);

    if ($("#" + inputID).val()){
      $.ajax({
        url: "../../php/funciones.php",
        data: {
          clase: "get_data",
          funcion: "validar_loteProd_entradaED",
          data: idEntradaTemp,
          data2: lote,
        },
        dataType: "json",
        success: function (data) {
          if (parseInt(data[0]["existe"]) == 1) {
            $("#"+invalidDivID).css("display", "block");
            $("#"+invalidDivID).text("El lote ingresado ya se encuentra registrado.");
            $("#"+inputID).addClass("is-invalid");
          } else {
            $("#"+invalidDivID).css("display", "none");
            $("#"+invalidDivID).text("");
            $("#"+inputID).removeClass("is-invalid");
  
            $.ajax({
              url: "../../php/funciones.php",
              data: {
                clase: "edit_data",
                funcion: "edit_lot_entryDirect_temp",
                data: lote, data2:idEntradaTemp,
              },
              dataType: "json",
              success: function (respuesta) {
                console.log(respuesta);
                if (respuesta[0].status) {
                  $("#tblProductosEntradaDCSucursal").DataTable().ajax.reload();
                } else {
                 
                }
              },
              error: function (error) {
                console.log(error);
                
              },
            });
          }
        },
      });
    }else{
      $("#"+invalidDivID).css("display", "block");
      $("#"+invalidDivID).text("El lote no puede estar vacío.");
      $("#"+inputID).addClass("is-invalid");
    }
  }

  if(inputID.substr(0,8) == "txtSerie"){
    var serie = $("#" + inputID).val();
    var idEntradaTemp = inputID.substr(9);

    if ($("#" + inputID).val()){
      $.ajax({
        url: "../../php/funciones.php",
        data: {
          clase: "get_data",
          funcion: "validar_serieProd_entradaED",
          data: idEntradaTemp,
          data2: serie,
        },
        dataType: "json",
        success: function (data) {
          if (parseInt(data[0]["existe"]) == 1) {
            $("#"+invalidDivID).css("display", "block");
            $("#"+invalidDivID).text("La serie ingresada ya se encuentra registrada.");
            $("#"+inputID).addClass("is-invalid");
          } else {
            $("#"+invalidDivID).css("display", "none");
            $("#"+invalidDivID).text("");
            $("#"+inputID).removeClass("is-invalid");

            $.ajax({
              url: "../../php/funciones.php",
              data: {
                clase: "edit_data",
                funcion: "edit_serie_entryDirect_temp",
                data: serie, data2:idEntradaTemp,
              },
              dataType: "json",
              success: function (respuesta) {
                console.log(respuesta);
                if (respuesta[0].status) {
                  console.log("Actualizando...");
                  $("#tblProductosEntradaDCSucursal").DataTable().ajax.reload();
                  //obtenerTotal($('#cmbOrdenCompra').val());
                } else {
                
                }
              },
              error: function (error) {
                console.log(error);
                
              },
            });
          }
        },
      });
    }else{
      $("#"+invalidDivID).css("display", "block");
      $("#"+invalidDivID).text("La serie no puede estar vacía.");
      $("#"+inputID).addClass("is-invalid");
    }
  }

  if(inputID.substr(0,12) == "txtCaducidad"){
    var caducidad = $("#" + inputID).val();
    var idEntradaTemp = inputID.substr(13);

    $.ajax({
      url: "../../php/funciones.php",
      data: {
        clase: "edit_data",
        funcion: "edit_caducidad_entryDirect_temp",
        data: caducidad, data2:idEntradaTemp,
      },
      dataType: "json",
      success: function (respuesta) {
        console.log(respuesta);
        if (respuesta[0].status) {
          $("#tblProductosEntradaDCSucursal").DataTable().ajax.reload();
        } else {
          
        }
      },
      error: function (error) {
        console.log(error);
        
      },
    });
  
  }
}

function validEmptyInputSLProvider(inputID, invalidDivID, textInvalidDiv) {
  if (!$("#" + inputID).val()) {
    $("#" + inputID).addClass("is-invalid");
    $("#" + invalidDivID).css("display", "block");
    $("#" + invalidDivID).text(textInvalidDiv);
  } else {
    $("#" + inputID).removeClass("is-invalid");
    $("#" + invalidDivID).css("display", "none");
    $("#" + invalidDivID).text(textInvalidDiv);
  }

  let flag = false;

  if(inputID.substr(0,13) == "txtCantidadED"){

    //si hay una orden de compra cargada valida si el producto modificado es parte de ella, si si, valida que no exceda la cantidad ordenada
    if($("#cmbOCProveedor").val() != ''){
      let cantEntrada = parseInt($("#" + inputID).val());
      let idEntradaTemp = inputID.substr(14);
      let OrdenCompra = $("#cmbOCProveedor").val();
      $.ajax({
        url: "../../php/funciones.php",
        async:false,
        data: {
          clase: "get_data",
          funcion: "get_validation_cantidadOC",
          data: cantEntrada, data2:idEntradaTemp, data3: OrdenCompra
        },
        dataType: "json",
        success: function (respuesta) {
          if(respuesta[0].status == 'ok'){
            flag = true;
          }else if(respuesta[0].status == 'no'){
            $("#" + inputID).val(respuesta[0].limite);
            Lobibox.notify("warning", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../../img/timdesk/warning_circle.svg",
              msg: "¡No se puede exceder la cantidad ordenada!",
            });
          }else if(respuesta[0].status == 'err'){
            Lobibox.notify("error", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../../img/timdesk/error.svg",
              msg: "¡Error al validar cantidad!",
            });
          }
        },
        error: function (error) {
          console.log(error);
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../../../img/timdesk/error.svg",
            msg: "¡Error al validar cantidad!",
          });
        },
      });
    }else{
      flag = true;
    }

    var cantEntrada = parseInt($("#" + inputID).val());
    var idEntradaTemp = inputID.substr(14);

    if(cantEntrada <= 0){
      $("#"+invalidDivID).css("display", "block");
      $("#"+invalidDivID).text("La cantidad no puede ser menor o igual a 0");
      $("#"+inputID).addClass("is-invalid");
    }else if(flag){
      $("#"+invalidDivID).css("display", "none");
      $("#"+invalidDivID).text("");
      $("#"+inputID).removeClass("is-invalid");

      $.ajax({
        url: "../../php/funciones.php",
        data: {
          clase: "edit_data",
          funcion: "edit_cantidad_entryDirect_temp",
          data: cantEntrada, data2:idEntradaTemp,
        },
        dataType: "json",
        success: function (respuesta) {
          console.log(respuesta);
          if (respuesta[0].status) {
            $("#tblProductosEntradaDProvider").DataTable().ajax.reload();
            //obtenerTotalEDProvider();
            $("#invalid-totalED").css('display', 'none');
            $("#invalid-importeProviderED").css('display', 'none');
          } else {
            
          }
        },
        error: function (error) {
          console.log(error);
          
        },
      });
    }
  }

  if(inputID.substr(0,7) == "txtLote"){
    //elimina espacios y saltos de línea
    var lote = $("#" + inputID).val().trim();
    let aux = lote.split("\t").join(" ");
    aux = aux.split("\n").join(" ");
    aux = aux.split(" ");
    lote ='';
    for (let i = 0; i < aux.length; i++) {
      if(aux[i] !== ""){
        lote += aux[i] + " ";
      }
    }

    var idEntradaTemp = inputID.substr(8);

    if ($("#" + inputID).val()){
      $.ajax({
        url: "../../php/funciones.php",
        data: {
          clase: "get_data",
          funcion: "validar_loteProd_entradaED",
          data: idEntradaTemp,
          data2: lote,
        },
        async:false,
        dataType: "json",
        success: function (data) {
          if (parseInt(data[0]["existe"]) == 1) {
            $("#"+invalidDivID).css("display", "block");
            $("#"+invalidDivID).text("El lote ingresado ya se encuentra registrado.");
            $("#"+inputID).addClass("is-invalid");
          } else {
            $("#"+invalidDivID).css("display", "none");
            $("#"+invalidDivID).text("");
            $("#"+inputID).removeClass("is-invalid");
  
            $.ajax({
              url: "../../php/funciones.php",
              data: {
                clase: "edit_data",
                funcion: "edit_lot_entryDirect_temp",
                data: lote, data2:idEntradaTemp,
              },
              dataType: "json",
              success: function (respuesta) {
                console.log(respuesta);
                if (respuesta[0].status) {
                  $("#tblProductosEntradaDProvider").DataTable().ajax.reload();
                } else {
                 
                }
              },
              error: function (error) {
                console.log(error);
                
              },
            });
          }
        },
      });
    }else{
      $("#"+invalidDivID).css("display", "block");
      $("#"+invalidDivID).text("El lote no puede estar vacío.");
      $("#"+inputID).addClass("is-invalid");
    }
  }

  /* if(inputID.substr(0,8) == "txtSerie"){
    var serie = $("#" + inputID).val();
    var idEntradaTemp = inputID.substr(9);

    if ($("#" + inputID).val()){
      $.ajax({
        url: "../../php/funciones.php",
        data: {
          clase: "get_data",
          funcion: "validar_serieProd_entradaED",
          data: idEntradaTemp,
          data2: serie,
        },
        dataType: "json",
        success: function (data) {
          if (parseInt(data[0]["existe"]) == 1) {
            $("#"+invalidDivID).css("display", "block");
            $("#"+invalidDivID).text("La serie ingresada ya se encuentra registrada.");
            $("#"+inputID).addClass("is-invalid");
          } else {
            $("#"+invalidDivID).css("display", "none");
            $("#"+invalidDivID).text("");
            $("#"+inputID).removeClass("is-invalid");

            $.ajax({
              url: "../../php/funciones.php",
              data: {
                clase: "edit_data",
                funcion: "edit_serie_entryDirect_temp",
                data: serie, data2:idEntradaTemp,
              },
              dataType: "json",
              success: function (respuesta) {
                console.log(respuesta);
                if (respuesta[0].status) {
                  console.log("Actualizando...");
                  $("#tblProductosEntradaDProvider").DataTable().ajax.reload();
                } else {
                
                }
              },
              error: function (error) {
                console.log(error);
                
              },
            });
          }
        },
      });
    }else{
      $("#"+invalidDivID).css("display", "block");
      $("#"+invalidDivID).text("La serie no puede estar vacía.");
      $("#"+inputID).addClass("is-invalid");
    }
  } */

  if(inputID.substr(0,12) == "txtCaducidad"){
    var caducidad = $("#" + inputID).val();
    var idEntradaTemp = inputID.substr(13);

    $.ajax({
      url: "../../php/funciones.php",
      data: {
        clase: "edit_data",
        funcion: "edit_caducidad_entryDirect_temp",
        data: caducidad, data2:idEntradaTemp,
      },
      dataType: "json",
      success: function (respuesta) {
        console.log(respuesta);
        if (respuesta[0].status) {
          $("#tblProductosEntradaDProvider").DataTable().ajax.reload();
        } else {
          
        }
      },
      error: function (error) {
        console.log(error);
        
      },
    });
  }

  if(inputID.substr(0,8) == "txtImpED"){
    var impuesto = $("#" + inputID).val();
    var idEntradaTemp = inputID.substr(9);

    if (impuesto < 0){
      $("#"+invalidDivID).css("display", "block");
      $("#"+invalidDivID).text("El impuesto no puede ser menor a 0.");
      $("#"+inputID).addClass("is-invalid");
    }else{
      $("#"+invalidDivID).css("display", "none");
      $("#"+invalidDivID).text("Se requiere el monto del impuesto.");
      $("#"+inputID).removeClass("is-invalid");

      $.ajax({
        url: "../../php/funciones.php",
        data: {
          clase: "edit_data",
          funcion: "edit_impuesto_entryDirect_temp",
          data: impuesto, data2:idEntradaTemp,
        },
        dataType: "json",
        success: function (respuesta) {
          console.log(respuesta);
          if (respuesta[0].status) {
            $("#tblProductosEntradaDProvider").DataTable().ajax.reload();
            //obtenerTotalEDProvider();
            $("#invalid-totalED").css('display', 'none');
            $("#invalid-importeProviderED").css('display', 'none');
          } else {
            
          }
        },
        error: function (error) {
          console.log(error);
          
        },
      });
    }
  }

  if(inputID.substr(0,11) == "txtImpIVAED"){
    var impuesto = $("#" + inputID).val();
    var idEntradaTemp = inputID.substr(12);

    if (impuesto == null){
      $("#"+invalidDivID).css("display", "block");
      $("#"+invalidDivID).text("Se debe de seleccionar una tasa.");
      $("#"+inputID).addClass("is-invalid");
    }else{
      $("#"+invalidDivID).css("display", "none");
      $("#"+invalidDivID).text("Se debe de seleccionar una tasa");
      $("#"+inputID).removeClass("is-invalid");

      $.ajax({
        url: "../../php/funciones.php",
        data: {
          clase: "edit_data",
          funcion: "edit_impuestoIVA_entryDirect_temp",
          data: impuesto, data2:idEntradaTemp,
        },
        dataType: "json",
        success: function (respuesta) {
          console.log(respuesta);
          if (respuesta[0].status) {
            $("#tblProductosEntradaDProvider").DataTable().ajax.reload();
            //obtenerTotalEDProvider();
            $("#invalid-totalED").css('display', 'none');
            $("#invalid-importeProviderED").css('display', 'none');
          } else {
            
          }
        },
        error: function (error) {
          console.log(error);
          
        },
      });
    }
  }

  if(inputID.substr(0,11) == "txtPrecioED"){
    var precio = $("#" + inputID).val();
    var idEntradaTemp = inputID.substr(12);

    if (precio < 0){
      $("#"+invalidDivID).css("display", "block");
      $("#"+invalidDivID).text("El precio no puede ser menor a 0.");
      $("#"+inputID).addClass("is-invalid");
    }else{
      $("#"+invalidDivID).css("display", "none");
      $("#"+invalidDivID).text("Se requiere el precio del producto.");
      $("#"+inputID).removeClass("is-invalid");

      $.ajax({
        url: "../../php/funciones.php",
        data: {
          clase: "edit_data",
          funcion: "edit_precio_entryDirect_temp",
          data: precio, data2:idEntradaTemp,
        },
        dataType: "json",
        success: function (respuesta) {
          console.log(respuesta);
          if (respuesta[0].status) {
            $("#tblProductosEntradaDProvider").DataTable().ajax.reload();
            //obtenerTotalEDProvider();
            $("#invalid-totalED").css('display', 'none');
            $("#invalid-importeProviderED").css('display', 'none');
          } else {
            
          }
        },
        error: function (error) {
          console.log(error);
          
        },
      });
    }
  }

  if(inputID == "txtNoDocumentoProviderED"){
    validarFolio_serieEDProvider();
    $("#invalid-totalED").css('display', 'none');
    $("#invalid-importeProviderED").css('display', 'none');
  }

  if($("#txtSerieProviderED").val() != ''){
    validarFolio_serieEDProvider();
    $("#invalid-totalED").css('display', 'none');
    $("#invalid-importeProviderED").css('display', 'none');
  }

  if($("#txtImporteProviderED").val() != ''){
    //obtenerTotalEDProvider();
    $("#invalid-totalED").css('display', 'none');
    $("#invalid-importeProviderED").css('display', 'none');
  }
}

function validEmptyInputSLCustomer(inputID, invalidDivID, textInvalidDiv) {
  if (!$("#" + inputID).val()) {
    $("#" + inputID).addClass("is-invalid");
    $("#" + invalidDivID).css("display", "block");
    $("#" + invalidDivID).text(textInvalidDiv);
  } else {
    $("#" + inputID).removeClass("is-invalid");
    $("#" + invalidDivID).css("display", "none");
    $("#" + invalidDivID).text(textInvalidDiv);
  }

  if(inputID.substr(0,21) == "txtCantidadEDCustomer"){
    var cantEntrada = parseInt($("#" + inputID).val());
    var idEntradaTemp = inputID.substr(22);

    if(cantEntrada <= 0){
      $("#"+invalidDivID).css("display", "block");
      $("#"+invalidDivID).text("La cantidad no puede ser menor o igual a 0");
      $("#"+inputID).addClass("is-invalid");
    }else{
      $("#"+invalidDivID).css("display", "none");
      $("#"+invalidDivID).text("");
      $("#"+inputID).removeClass("is-invalid");

      $.ajax({
        url: "../../php/funciones.php",
        data: {
          clase: "edit_data",
          funcion: "edit_cantidad_entryDirect_temp",
          data: cantEntrada, data2:idEntradaTemp,
        },
        dataType: "json",
        success: function (respuesta) {
          console.log(respuesta);
          if (respuesta[0].status) {
            $("#tblProductosEntradaDCustomer").DataTable().ajax.reload();
          } else {
            
          }
        },
        error: function (error) {
          console.log(error);
          
        },
      });
    }
  }

  if(inputID.substr(0,7) == "txtLote"){
    var lote = $("#" + inputID).val();
    var idEntradaTemp = inputID.substr(8);

    if ($("#" + inputID).val()){
      $.ajax({
        url: "../../php/funciones.php",
        data: {
          clase: "get_data",
          funcion: "validar_loteProd_entradaED",
          data: idEntradaTemp,
          data2: lote,
        },
        dataType: "json",
        success: function (data) {
          if (parseInt(data[0]["existe"]) == 1) {
            $("#"+invalidDivID).css("display", "block");
            $("#"+invalidDivID).text("El lote ingresado ya se encuentra registrado.");
            $("#"+inputID).addClass("is-invalid");
          } else {
            $("#"+invalidDivID).css("display", "none");
            $("#"+invalidDivID).text("");
            $("#"+inputID).removeClass("is-invalid");
  
            $.ajax({
              url: "../../php/funciones.php",
              data: {
                clase: "edit_data",
                funcion: "edit_lot_entryDirect_temp",
                data: lote, data2:idEntradaTemp,
              },
              dataType: "json",
              success: function (respuesta) {
                console.log(respuesta);
                if (respuesta[0].status) {
                  $("#tblProductosEntradaDCustomer").DataTable().ajax.reload();
                } else {
                 
                }
              },
              error: function (error) {
                console.log(error);
                
              },
            });
          }
        },
      });
    }else{
      $("#"+invalidDivID).css("display", "block");
      $("#"+invalidDivID).text("El lote no puede estar vacío.");
      $("#"+inputID).addClass("is-invalid");
    }
  }

  if(inputID.substr(0,8) == "txtSerie"){
    var serie = $("#" + inputID).val();
    var idEntradaTemp = inputID.substr(9);

    if ($("#" + inputID).val()){
      $.ajax({
        url: "../../php/funciones.php",
        data: {
          clase: "get_data",
          funcion: "validar_serieProd_entradaED",
          data: idEntradaTemp,
          data2: serie,
        },
        dataType: "json",
        success: function (data) {
          if (parseInt(data[0]["existe"]) == 1) {
            $("#"+invalidDivID).css("display", "block");
            $("#"+invalidDivID).text("La serie ingresada ya se encuentra registrada.");
            $("#"+inputID).addClass("is-invalid");
          } else {
            $("#"+invalidDivID).css("display", "none");
            $("#"+invalidDivID).text("");
            $("#"+inputID).removeClass("is-invalid");

            $.ajax({
              url: "../../php/funciones.php",
              data: {
                clase: "edit_data",
                funcion: "edit_serie_entryDirect_temp",
                data: serie, data2:idEntradaTemp,
              },
              dataType: "json",
              success: function (respuesta) {
                console.log(respuesta);
                if (respuesta[0].status) {
                  console.log("Actualizando...");
                  $("#tblProductosEntradaDCustomer").DataTable().ajax.reload();
                } else {
                
                }
              },
              error: function (error) {
                console.log(error);
                
              },
            });
          }
        },
      });
    }else{
      $("#"+invalidDivID).css("display", "block");
      $("#"+invalidDivID).text("La serie no puede estar vacía.");
      $("#"+inputID).addClass("is-invalid");
    }
  }

  if(inputID.substr(0,12) == "txtCaducidad"){
    var caducidad = $("#" + inputID).val();
    var idEntradaTemp = inputID.substr(13);

    $.ajax({
      url: "../../php/funciones.php",
      data: {
        clase: "edit_data",
        funcion: "edit_caducidad_entryDirect_temp",
        data: caducidad, data2:idEntradaTemp,
      },
      dataType: "json",
      success: function (respuesta) {
        console.log(respuesta);
        if (respuesta[0].status) {
          $("#tblProductosEntradaDCustomer").DataTable().ajax.reload();
        } else {
          
        }
      },
      error: function (error) {
        console.log(error);
        
      },
    });
  
  }
}

function obtenerTotalEDProvider(){
  //Obtener subtotal
  $.ajax({
    
    url:"../../php/funciones.php",
    data:{clase:"get_data", funcion:"get_subTotalEntradaEDProviderTemp"},
    dataType:"json",
    success:function(respuesta){
      $('#Subtotal').html(dosDecimales(respuesta[0].subtotal))
    },
    error:function(error){
      console.log(error);
    }
  });

  var html='';
  $('#impuestos').html(html);
  //Obtener impuestos
  $.ajax({
    
    url:"../../php/funciones.php",
    data:{clase:"get_data", funcion:"get_impuestoEntradaEDProviderTemp"},
    dataType:"json",
    success:function(respuesta){
      //Recorrer las respuestas de la consulta
      var tasa = '';
      $.each(respuesta,function(i){    
        if (respuesta[i].tipoImpuesto == "1"){
          tasa = respuesta[i].tasa;
        }else{
          tasa = respuesta[i].tasa + "%"
        }
        if(!$('#impuestos-head-'+respuesta[i].id).length){
        html += '<tr>'+
                  //'<th style="text-align: right;">'+respuesta[i].producto+'</th>'+
                  '<td style="text-align: right;" id="impuestos-head-'+respuesta[i].id+'">'+respuesta[i].nombre+'</td>'+
                  '<td style="text-align: right;">'+tasa+' </td>'+
                  '<td style="text-align: right;">.....</td>'+
                  '<td style="text-align: right;"> $ '+ dosDecimales(respuesta[i].totalImpuesto) +'</td>'+
                '</tr>';
        }
         

      });
      
      $('#impuestos').html(html);
      
    },
    error:function(error){
      console.log(error);
    }
  });
}

function validEmptyInput2(inputID, invalidDivID, textInvalidDiv) {
  if (!$("#" + inputID).val()) {
    $("#" + inputID).addClass("is-invalid");
    $("#" + invalidDivID).css("display", "block");
    $("#" + invalidDivID).text(textInvalidDiv);
  } else {
    $("#" + inputID).removeClass("is-invalid");
    $("#" + invalidDivID).css("display", "none");
    $("#" + invalidDivID).text(textInvalidDiv);
  }

  if(inputID.substr(0,15) == "txtCantidadTras"){
    var cantEntrada = parseInt($("#" + inputID).val());
    var idEntradaTemp = inputID.substr(16);

    console.log("ID Entrada: "+idEntradaTemp);

    if (cantEntrada < 0 ){
      $("#"+invalidDivID).css("display", "block");
      $("#"+invalidDivID).text( "La cantidad no puede ser menor a 0");
      $("#"+inputID).addClass("is-invalid");
    }else{
      $.ajax({
        url: "../../php/funciones.php",
        data: {
          clase: "get_data",
          funcion: "validar_cantidadProd_entrada_traspaso",
          data: idEntradaTemp,
          data2: cantEntrada,
        },
        dataType: "json",
        success: function (data) {
          if (parseInt(data[0]["existe"]) == 1) {
            $("#"+invalidDivID).css("display", "block");
            $("#"+invalidDivID).text(
              "La suma de cantidades de recepción del producto no puede ser mayor a la cantidad restante: "+data[0]["Restante"]+"."
            );
            $("#"+inputID).addClass("is-invalid");
            console.log("¡Cantidad no válida!");
          } else {
            $("#"+invalidDivID).css("display", "none");
            $("#"+invalidDivID).text(
              ""
            );
            $("#"+inputID).removeClass("is-invalid");
            console.log("¡Cantidad válida!");
  
            updateCantidadTras(cantEntrada, idEntradaTemp);
          }
        },
      });
    }
  }
}

function updateCantidadTras(cantEntrada, idEntradaTemp){
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "edit_data",
      funcion: "edit_amount_entry_tranfer_temp",
      data: cantEntrada, data2:idEntradaTemp,
    },
    dataType: "json",
    success: function (respuesta) {
      console.log(respuesta);
      if (respuesta[0].status) {
        $("#tblProductosTraspaso").DataTable().ajax.reload();

        obtenerTotalEntradaTraspaso(idEntradaTemp, 0);
      } else {
       
      }
    },
    error: function (error) {
      console.log(error);
      
    },
  });
}

function obtenerTotalEntradaTraspaso(idEntradaTemp, folio){
  $.ajax({
    
    url:"../../php/funciones.php",
    data:{clase:"get_data", funcion:"get_total_entrada_traspasoTemp",data:idEntradaTemp, data2: folio},
    dataType:"json",
    success:function(respuesta){
      $('#TotalTras').html(respuesta[0].cantidad)
    },
    error:function(error){
      console.log(error);
    }
  });
}

function activarImpuestos(idImpuestoOC){

  var idOrdenCompra = $('#cmbOrdenCompra').val();
  var impuesto = $("#cbxImpuestos-"+idImpuestoOC).data("imp");

  if ($("#cbxImpuestos-"+idImpuestoOC).is(":checked")) {
    $("#lblImpuesto-" + impuesto).removeClass("textTableInactivo");
    $("#lblImpuesto-" + impuesto).addClass("textTable");

    $.ajax({
      url: "../../php/funciones.php",
      data: {
        clase: "edit_data",
        funcion: "edit_isImpuesto_entry_partial_temp",
        data:impuesto, data2:1,
      },
      dataType: "json",
      success: function (respuesta) {
        console.log(respuesta);
        if (respuesta[0].status) {
          loadProductosOrderPurchase(idOrdenCompra);
          //obtenerTotal(idOrdenCompra);
        } else {
         
        }
      },
      error: function (error) {
        console.log(error);
        
      },
    });
    console.log("Activar: "+ impuesto);
    
  }else{
    $("#lblImpuesto-" + idImpuestoOC).removeClass("textTable");
    $("#lblImpuesto-" + idImpuestoOC).addClass("textTableInactivo");

    $.ajax({
      url: "../../php/funciones.php",
      data: {
        clase: "edit_data",
        funcion: "edit_isImpuesto_entry_partial_temp",
        data:impuesto, data2:0,
      },
      dataType: "json",
      success: function (respuesta) {
        console.log(respuesta);
        if (respuesta[0].status) {
          loadProductosOrderPurchase(idOrdenCompra);
          //obtenerTotal(idOrdenCompra);
        } else {
         
        }
      },
      error: function (error) {
        console.log(error);
        
      },
    });
    console.log("Desactivar: "+ impuesto);
    
  }
}

function descargarPDFEntrada(){
  var folio_entrada = $("#folio_entrada").val();
  var id_cuenta_pagar = $("#id_cuenta_pagar").val();

  setTimeout(
    function() {
      window.location.href = "functions/descargar_Entrada.php?folio="+folio_entrada+"&id_cuenta="+id_cuenta_pagar;
    },
    100,
  );

  setTimeout(
    function() {
      window.location.href = "../entradas_productos";
    },
    2000,
  );
  
}

function descargarPDFEntradaTraspaso(folioEntrada){

  setTimeout(
    function() {
      window.location.href = "functions/descargar_EntradaTraspaso.php?folio="+folioEntrada;
    },
    100,
  );

  setTimeout(
    function() {
      window.location.href = "../entradas_productos";
    },
    2000,
  );
  
}

function descargarPDFFactura(){
  var folio_doc = $("#folio_doc").val();
  const serie_doc = 'N/A'; //$("#serie_doc").val();
  var folio_entrada = $("#folio_entrada").val();
  var id_cuenta_pagar = $("#id_cuenta_pagar").val();
  var ordenCompra = $('#cmbOCProveedor').val() ? $('#cmbOCProveedor').val() : 0; /* $('#cmbOrdenCompra').val(); */

  
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "edit_data",
      funcion: "edit_entradaOC_estatusFactura",
      data: folio_doc, 
      data2: 'N/A',
      data3: ordenCompra,
      data4: id_cuenta_pagar
    },
    dataType: "json",
    success: function (respuesta) {

      if (respuesta[0].status) {      
        window.location.href = "../entradas_productos";
      } else {
        Swal.fire("Error", "No se actualizaron los cambios.", "warning");
      }
    },
    error: function (error) {
      console.log(error);
    },
  });

  setTimeout(
    function() {
      window.location.href = "functions/descargar_Entrada.php?folio="+folio_entrada+"&id_cuenta="+id_cuenta_pagar;
    },
    100,
  );

  /* setTimeout(
    function() {
      window.location.href = "functions/descargar_FacturaCompleta.php?folio="+folio_doc+"&serie="+serie_doc;
    },
    500,
  ); */

  /* setTimeout(
    function() {
      window.location.href = "../entradas_productos";
    },
    2000,
  ); */
}

function validarFolio(){
  var folio = $("#txtNoDocumento").val();
  var ordenCompra = $('#cmbOrdenCompra').val();

  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "validar_folio_entradaOC",
      data: folio,
      data2: ordenCompra,
    },
    dataType: "json",
    success: function (data) {

      if (parseInt(data[0]["existe"]) == 1) {
        $("#invalid-noDocumento").css("display", "block");
        $("#invalid-noDocumento").text(
          "El folio no se encuentra disponible para la orden de compra."
        );
        $("#txtNoDocumento").addClass("is-invalid");
        console.log("¡Ya existe!");
      } else {
        $("#invalid-noDocumento").css("display", "none");
        $("#invalid-noDocumento").text(
          ""
        );
        $("#txtNoDocumento").removeClass("is-invalid");
        console.log("¡No existe!");

        if (!folio) {
          $("#invalid-noDocumento").css("display", "block");
          $("#invalid-noDocumento").text(
            "La entrada debe de tener un número de folio."
          );
          $("#txtNoDocumento").addClass("is-invalid");
        }
      }
    },
  });
}

function validarFolio_serieEDProvider(){
  let folio = $("#txtNoDocumentoProviderED").val();
  const serie = 'N/A';

  if($("#txtNoDocumentoProviderED").val().trim() == '' /* || $("#txtSerieProviderED").val().trim() == '' */){
    return;
  }

  var proveedor = $('#cmbProveedorED').val();
  var sucursal = $('#cmbSucursalEntrada').val();

  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "validar_folio_entradaEDProvider",
      data: folio,
      data2: proveedor,
      data3: sucursal,
      data4: serie
    },
    dataType: "json",
    success: function (data) {

      if (parseInt(data[0]["existe"]) == 1) {
        $("#invalid-noDocumentoProviderED").css("display", "block");
        $("#invalid-noDocumentoProviderED").text("El folio no se encuentra disponible para la entrada.");
        $("#txtNoDocumentoProviderED").addClass("is-invalid");
        console.log("¡Ya existe!");
      } else {
        $("#invalid-noDocumentoProviderED").css("display", "none");
        $("#invalid-noDocumentoProviderED").text("");
        $("#txtNoDocumentoProviderED").removeClass("is-invalid");
        console.log("¡No existe!");

        if (!folio) {
          $("#invalid-noDocumentoProviderED").css("display", "block");
          $("#invalid-noDocumentoProviderED").text("La entrada debe de tener un número de folio.");
          $("#txtNoDocumentoProviderED").addClass("is-invalid");
        }
      }
    },
  });
}

function validarSerie(){
  var serie = $("#txtSerie").val();
  var ordenCompra = $('#cmbOrdenCompra').val();

  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "validar_serie_entradaOC",
      data: serie,
      data2: ordenCompra,
    },
    dataType: "json",
    success: function (data) {

      if (parseInt(data[0]["existe"]) == 1) {
        $("#invalid-serie").css("display", "block");
        $("#invalid-serie").text(
          "El número de serie no se encuentra disponible para la orden de compra."
        );
        $("#txtSerie").addClass("is-invalid");
        console.log("¡Ya existe!");
      } else {
        $("#invalid-serie").css("display", "none");
        $("#invalid-serie").text(
          ""
        );
        $("#txtSerie").removeClass("is-invalid");
        console.log("¡No existe!");

        if (!serie) {
          $("#invalid-serie").css("display", "block");
          $("#invalid-serie").text(
            "La entrada debe de tener un número de serie."
          );
          $("#txtSerie").addClass("is-invalid");
        }
      }
    },
  });
}

function validarSerieEDProvider(){
  var serie = $("#txtSerieProviderED").val();
  var proveedor = $('#cmbProveedorED').val();
  var sucursal = $('#cmbSucursalEntrada').val();

  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "validar_serie_entradaEDProvider",
      data: serie,
      data2: proveedor,
      data3: sucursal
    },
    dataType: "json",
    success: function (data) {

      if (parseInt(data[0]["existe"]) == 1) {
        $("#invalid-serieProviderED").css("display", "block");
        $("#invalid-serieProviderED").text("El número de serie no se encuentra disponible para la entrada.");
        $("#txtSerieProviderED").addClass("is-invalid");
        console.log("¡Ya existe!");
      } else {
        $("#invalid-serieProviderED").css("display", "none");
        $("#invalid-serieProviderED").text("");
        $("#txtSerieProviderED").removeClass("is-invalid");
        console.log("¡No existe!");

        if (!serie) {
          $("#invalid-serieProviderED").css("display", "block");
          $("#invalid-serieProviderED").text("La entrada debe de tener un número de serie.");
          $("#txtSerieProviderED").addClass("is-invalid");
        }
      }
    },
  });
}

function addLoteSerie(idEntradaTemp){
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "save_data",
      funcion: "save_entry_partialAdd_ocTempTable",
      data:idEntradaTemp
    },
    dataType: "json",
    success: function (respuesta) {
      console.log(respuesta);
      if (respuesta[0].status) {
        
        loadProductosOrderPurchase(respuesta[0].pkOrden);
        
      } else {
       
      }
    },
    error: function (error) {
      console.log(error);
      
    },
  });
}

function openModalDelete(idEntradaTemp){
  $("#entryTempIDD").val(idEntradaTemp);
  $('#eliminar_ProductoEnt').modal('show');
}

function openModalDeleteED(idEntradaTemp){
  _global.idEntradaED = idEntradaTemp
  $('#eliminar_ProductoEntED').modal('show');
}

function openModalDeleteEDProvider(idEntradaTemp){
  _global.idEntradaED = idEntradaTemp
  $('#eliminar_ProductoEntEDProvider').modal('show');
}

function openModalDeleteEDCustomer(idEntradaTemp){
  _global.idEntradaED = idEntradaTemp
  $('#eliminar_ProductoEntEDCustomer').modal('show');
}

$(document).on("click", "#btndeleteLoteSerieED", function () {
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "delete_data",
      funcion: "delete_entry_Remove_EDTempTable",
      data:_global.idEntradaED 
    },
    dataType: "json",
    success: function (respuesta) {
      console.log(respuesta);
      if (respuesta[0].status) {
        $("#tblProductosEntradaDCSucursal").DataTable().ajax.reload();
        Lobibox.notify("success", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/checkmark.svg",
          msg: "¡Producto eliminado correctamente!",
          sound: '../../../../../sounds/sound4'
        });
      } else {
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/notificacion_error.svg",
          msg: "¡Algo salio mal :(!",
          sound: '../../../../../sounds/sound4'
        });
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
});

$(document).on("click", "#btndeleteLoteSerieEDProvider", function () {
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "delete_data",
      funcion: "delete_entry_Remove_EDTempTable",
      data:_global.idEntradaED 
    },
    dataType: "json",
    success: function (respuesta) {
      console.log(respuesta);
      if (respuesta[0].status) {
        $("#tblProductosEntradaDProvider").DataTable().ajax.reload();
        Lobibox.notify("success", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/checkmark.svg",
          msg: "¡Producto eliminado correctamente!",
          sound: '../../../../../sounds/sound4'
        });
      } else {
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/notificacion_error.svg",
          msg: "¡Algo salio mal :(!",
          sound: '../../../../../sounds/sound4'
        });
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
});

$(document).on("click", "#btndeleteLoteSerieEDCustomer", function () {
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "delete_data",
      funcion: "delete_entry_Remove_EDTempTable",
      data:_global.idEntradaED 
    },
    dataType: "json",
    success: function (respuesta) {
      console.log(respuesta);
      if (respuesta[0].status) {
        $("#tblProductosEntradaDCustomer").DataTable().ajax.reload();
        Lobibox.notify("success", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/checkmark.svg",
          msg: "¡Producto eliminado correctamente!",
          sound: '../../../../../sounds/sound4'
        });
      } else {
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/notificacion_error.svg",
          msg: "¡Algo salio mal :(!",
          sound: '../../../../../sounds/sound4'
        });
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
});

function deleteLoteSerie(idEntradaTemp){
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "delete_data",
      funcion: "delete_entry_partialRemove_ocTempTable",
      data:idEntradaTemp
    },
    dataType: "json",
    success: function (respuesta) {
      console.log(respuesta);
      if (respuesta[0].status) {
        
        loadProductosOrderPurchase($('#cmbOrdenCompra').val());
        
      } else {
       
      }
    },
    error: function (error) {
      console.log(error);
      
    },
  });
}

function validate_Permissions(pkPantalla){
  $.ajax({
    url: '../../php/funciones.php',
    data:{clase:"get_data", 
          funcion:"validar_Permisos", 
          data:pkPantalla},
    dataType:"json",
    success: function(data) {
      _permissionsOC.read = data[0].isRead;
      _permissionsOC.add = data[0].isAdd;
      _permissionsOC.edit = data[0].isEdit;
      _permissionsOC.delete = data[0].isDelete;
      _permissionsOC.export = data[0].isExport;

      //PRODUCTOS
      if (pkPantalla == "22"){
        var html = '';
        if (_permissionsOC.edit == "1"){
          html = `<a class="btn-custom btn-custom--border-blue float-right" id="btnCerrarOC" style="margin-right: 10px!important">Cerrar orden de compra</a>`;
          $("#sCerrarOC").html(html);
        }else{
          html = ``;
          $("#sCerrarOC").html(html);
        }
      }
    }
  });

}

function deleteEntradaDirectaTemp(){
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "delete_data",
      funcion: "delete_datosEntradaDirectaTemp"
    },
    async:false,
    dataType: "json",
    success: function (respuesta) {

      if (respuesta[0].status) {
      } else {
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function cargarCMBCategorias(name)
{
  var html = '<option disabled value="f" selected>Seleccione una categoria</option>';
  $.ajax({
    type:'POST',
    url: "../../php/funciones.php",
    dataType: "json",
    data: { clase:"get_data",funcion:"get_categorias"},
    success: function (data) {
      if (data !== "" && data !== null && data.length > 0) {
        $.each(data, function (i) {
          if (data[i].PKCategoria === name) {
            html += '<option value="' +
              data[i].PKCategoria +
              '" selected>' +
              data[i].Nombre+
              "</option>";
          } else {
            html +=
              '<option value="' +
              data[i].PKCategoria +
              '">' +
              data[i].Nombre+
              "</option>";
          }
        });
        //html += '<option value="add" style="background-color: #15589B;  color:white; text-align:center; width:100%;">Añadir categoría</option>';
      } else {
        //html += '<option value="vacio">No hay datos que mostrar</option><option value="add" style="background-color: #15589B;  color:white; text-align:center; width:100%;">Añadir categoría</option>';
      }
      $("#cmbCategoriaCuenta").html(html);
    },
    error: function (error) {
      console.log("Error");
      console.log(error);
    },
  });
}

function cargarCMBSubcategorias(subCat,name)
{
  var html = '<option disabled value="f" selected>Seleccione una subcategoria</option>';
  $.ajax({
    type:'POST',
    url: "../../php/funciones.php",
    dataType: "json",
    data: { clase:"get_data",funcion:"get_subcategorias",subCat:subCat},
    cache: false,
    success: function (data) {
      if (data !== "" && data !== null && data.length > 0) {
        
        $.each(data, function (i) {
            
            if(data[i].PKSubcategoria === name){
            html +=
                '<option value="' +
                data[i].PKSubcategoria +
                '" selected>' +
                data[i].Nombre+
                "</option>";
            } else {
            html +=
                '<option value="' +
                data[i].PKSubcategoria +
                '">' +
                data[i].Nombre+
                "</option>";
            }
        });
        //html += '<option value="add" style="background-color: #15589B;  color:white; text-align:center; width:100%;">Añadir subcategoría</option>';
      } else {
        //html += '<option value="vacio">No hay datos que mostrar</option><option value="add" style="background-color: #15589B;  color:white; text-align:center; width:100%;">Añadir subcategoría</option>';
      }
      
      $("#cmbSubcategoriaCuenta").html(html);
    },
    error: function (error) {
      console.log("Error");
      console.log(error);
    },
  });
}

function getExpenseCategory(value)
{
    $.ajax({
        type:'POST',
        url: "../../php/funciones.php",
        dataType: "json",
        data: { clase:"get_data",funcion:"get_expenseCategory",compra_id:value},
        cache: false,
        success: function (data) {
            if(data.length > 0){
                cargarCMBCategorias(data[0].categoria_id)
                cargarCMBSubcategorias(data[0].categoria_id,data[0].subcategoria_id)
            }
        },
        error: function (error) {
          console.log("Error");
          console.log(error);
        },
    });
}