var _permissionsOC = { 
  read: 0,
  add: 0,
  edit: 0,
  delete: 0,
  export: 0
}

var _global = {
  idEntradaED: 0,
  referencia: 0,
  cliente: 0,
  proveedor: 0,
  sucOrigen: 0,
  id_CuentaPagar:0,
  id_OCProve:0,
  isVer:0
}

$(document).ready(function(){
  $('#agregar_ProductoEDBranch').on('shown.bs.modal', cargarTablaProductosEDBranch);
  $('#agregar_ProductoEDProvider').on('shown.bs.modal', cargarTablaProductosEDProvider);
  $('#agregar_ProductoEDCustomer').on('shown.bs.modal', cargarTablaProductosEDCustomer);

  //recupera variable define funcionalidad de pantalla
  _global.isVer = $("#IsVer").val();
    

  if (tipoE == 1){
    html1 = `<div class="purchases-disabled">
              <label for="cmbOrdenCompra">Orden de compra:</label>
              <input required class="form-control" name="cmbOrdenCompra" id="cmbOrdenCompra" readonly>
            </div>`;
    html2 = `<div class="purchases-disabled">
              <label for="cmbProveedor">Proveedor:</label>
              <input required class="form-control" name="cmbProveedor" id="cmbProveedor" readonly>
            </div>`;
    html3 = `<div class="purchases-disabled">
              <label for="cmbSucursal">Sucursal:</label>
              <input required class="form-control" name="cmbSucursal" id="cmbSucursal" readonly>
            </div>`;
    html4 = `<div class="purchases-disabled">
              <label for="usr">No. de documento*:</label>
              <div class="input-group">
                <input required class="form-control alphaNumeric-only" type="text" name="txtNoDocumento" id="txtNoDocumento" placeholder="Folio" style="float:left;" onchange="validEmptyInput('txtNoDocumento', 'invalid-noDocumento', 'La entrada debe de tener un número de folio.')">
                <div class="invalid-feedback" id="invalid-noDocumento">La entrada debe de tener número de serie.</div>
              </div>
            </div>`;
    html5 = `<div class="purchases-disabled">
              <label for="usr">Serie de factura*:</label>
              <div class="input-group">
                <input required class="form-control alphaNumeric-only" type="text" name="txtSerie" id="txtSerie" placeholder="Serie" style="float:left;" onchange="validEmptyInput('txtSerie', 'invalid-serie', 'La entrada debe de tener número de serie.')">
                <div class="invalid-feedback" id="invalid-serie">La entrada debe de tener número de serie.</div>
              </div>
            </div>`;
    html6 = `<div class="purchases-disabled">
              <label for="usr">Subtotal*:</label>
              <div class="input-group">
                <input required class="form-control numericDecimal-only" type="number" name="txtSubtotal" id="txtSubtotal" placeholder="Ej. 1000.00" style="float:left;" onchange="validEmptyInput('txtSubtotal', 'invalid-subtotal', 'La entrada debe de tener subtotal.')">
                <div class="invalid-feedback" id="invalid-subtotal">La entrada debe de tener subtotal.</div>
              </div>
            </div>`;
    html7 = `<div class="purchases-disabled">
              <label for="usr">IVA (Monto):</label>
              <div class="input-group">
                <input class="form-control numericDecimal-only" type="number" name="txtIva" id="txtIva" placeholder="Ej. 1000.00" style="float:left;" value="0" onchange="validEmptyInput('txtIva', 'invalid-iva', 'La entrada debe de tener IVA.')">
                <div class="invalid-feedback" id="invalid-iva">La entrada debe de tener IVA.</div>
              </div>
            </div>`;
    html8 = `<div class="purchases-disabled">
              <label for="usr">IEPS (Monto):</label>
              <div class="input-group">
                <input class="form-control numericDecimal-only" type="number" name="txtIEPS" id="txtIEPS" placeholder="Ej. 1000.00" style="float:left;" value="0" onchange="validEmptyInput('txtIEPS', 'invalid-ieps', 'La entrada debe de tener IEPS.')">
                <div class="invalid-feedback" id="invalid-ieps">La entrada debe de tener IEPS.</div>
              </div>
            </div>`;
    html9 = `<div class="purchases-disabled">
              <label for="usr">Importe factura*:</label>
              <div class="input-group">
                <input required class="form-control numericDecimal-only" type="number" name="txtImporte" id="txtImporte" placeholder="Ej. 1000.00" style="float:left;" onchange="validEmptyInput('txtImporte', 'invalid-importe', 'La entrada debe de tener importe.')">
                <div class="invalid-feedback" id="invalid-importe">La entrada debe de tener importe.</div>
              </div>
            </div>`;
    html10 = `<div class="purchases-disabled">
              <label for="usr">Descuento (Monto):</label>
              <div class="input-group">
                <input class="form-control numericDecimal-only" type="number" name="txtDescuento" id="txtDescuento" placeholder="Ej. 1000.00" style="float:left;">
              </div>
            </div>`;
    html11 = `<div class="purchases-disabled">
              <label for="usr">Fecha de factura*:</label>
              <div class="input-group">
                <input required class="form-control" type="date" name="txtFechaFactura" id="txtFechaFactura" style="float:left;" onchange="validEmptyInput('txtFechaFactura', 'invalid-fechaFactura', 'La entrada debe de tener una fecha de factura.')">
                <div class="invalid-feedback" id="invalid-fechaFactura">La entrada debe de una fecha de factura.</div>
              </div>
            </div>`;
    html12 = `<div class="purchases-disabled">
              <input class="form-check-input" type="checkbox" id="cbxRemision" name="cbxRemision">
              <label class="form-check-label" for="cbxRemision">Remisión</label>
            </div>`;
    html13 = `<div class="purchases-disabled">
              <label for="">Notas:</label>
              <textarea class="form-control" name="txaNotaEntrada" id="txaNotaEntrada" placeholder="Escribe las notas aquí..." rows="2" cols="200"></textarea>
            </div>`;
    html14 = `<div class="form-group purchases-disabled opacidad">
              <div class="row">
                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-6">
                  <label for="usr">Referencia:</label>
                  <div class="input-group">
                    <span name="txtReferenciaP" id="txtReferenciaP"></span>
                  </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-6">
                  <label for="usr">Proveedor:</label>
                  <div class="input-group">
                    <span name="txtProveedorP" id="txtProveedorP"></span>
                  </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-6">
                  <label for="usr">Fecha de emisión:</label>
                  <div class="input-group">
                    <span name="txtFechaEmisionP" id="txtFechaEmisionP"></span>
                  </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-6">
                  <label for="usr">Fecha estimada de entrega:</label>
                  <div class="input-group">
                    <span name="txtFechaEstimadaP" id="txtFechaEstimadaP"></span>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6">
                  <label for="usr">Dirección de entrega:</label>
                  <div class="input-group">
                    <span name="txtDireccionP" id="txtDireccionP"></span>
                  </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6">
                  <label for="usr">Notas:</label>
                  <div class="input-group">
                    <span name="NotasInternasP" id="NotasInternasP"></span>
                  </div>
                </div>
              </div>
            </div>`;
    html15 = `<table class="table-sm tblCoti dataTable no-footer" id="tblProductosOrdenCompraParcial" width="100%" cellspacing="0">
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
                <a class="btn-custom btn-custom--blue float-right" id="btnEditarEntradaOC" style="margin-right: 10px!important">Guardar cambios</a>
                <a class="btn-custom btn-custom--blue float-right" id="btnVerEntradaOC" style="margin-right: 10px!important"><i class="fa fa-eye" aria-hidden="true"></i> Ver entrada</a>
                <span id="sCerrarOC">
                </span>
              </div>
            </div>`;

    $("#div1").html(html1);
    $("#div2").html(html2);
    $("#div3").html(html3);
    $("#div4").html(html4);
    $("#div5").html(html5);
    $("#div6").html(html6);
    $("#div7").html(html7);
    $("#div8").html(html8);
    $("#div9").html(html9);
    $("#div10").html(html10);
    $("#div11").html(html11);
    $("#div12").html(html12);
    $("#div13").html(html13);
    $("#div14").html(html14);
    $("#div15").html(html15);

    $('.data-container .directBranch-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
    $('.data-container .directProvider-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
    $('.data-container .directCustomer-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
    $('.data-container .transfer-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
    $('.data-container .purchases-disabled').css({'display':'inline-block','visibility': 'visible','opacity': '1','animation': 'fade 1s'});

    deleteEntradaOCTemp();
    cargarDatosEntradaOC(folio);
  }else if (tipoE == 2){

    html1 = `<div class="transfer-disabled">
              <label for="cmbOrderPedido">Pedido:</label>
              <input class="form-control" name="cmbOrderPedido" id="cmbOrderPedido" readonly>
            </div>`;
    html2 = `<div class="transfer-disabled">
              <label for="cmbSucursalDestino">Sucursal de destino:</label>
              <input class="form-control" name="cmbSucursalDestino" id="cmbSucursalDestino" readonly>
            </div>`;
    html3 = `<div class="transfer-disabled">
              <label for="cmbSucursalOrigen">Sucursal de origen:</label>
              <input class="form-control" name="cmbSucursalOrigen" id="cmbSucursalOrigen" readonly>
            </div>`;
    html4 = ``;
    html5 = ``;
    html6 = ``;
    html7 = ``;
    html8 = ``;
    html9 = ``;
    html10 = ``;
    html11 = ``;
    html12 = ``;
    html13 = ``;
    html14 = ``;
    html16 = `<table class="table opacidad" id="tblProductosTraspaso" width="100%" cellspacing="0">
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
                  <a class="btn-custom btn-custom--blue float-right" id="btnEditarEntradaTraspaso" style="margin-right: 10px!important">Guardar cambios</a>
                  <a class="btn-custom btn-custom--blue float-right" id="btnVerEntradaTraspaso" style="margin-right: 10px!important"><i class="fa fa-eye" aria-hidden="true"></i> Ver entrada</a>
                  <span id="sCerrarOC">
                  </span>
                </div>
              </div>`;

    $("#div1").html(html1);
    $("#div2").html(html2);
    $("#div3").html(html3);
    $("#div4").html(html4);
    $("#div5").html(html5);
    $("#div6").html(html6);
    $("#div7").html(html7);
    $("#div8").html(html8);
    $("#div9").html(html9);
    $("#div10").html(html10);
    $("#div11").html(html11);
    $("#div12").html(html12);
    $("#div13").html(html13);
    $("#div14").html(html14);
    $("#div16").html(html16);

    $('.data-container .directBranch-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
    $('.data-container .directProvider-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
    $('.data-container .directCustomer-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
    $('.data-container .purchases-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
    $('.data-container .transfer-disabled').css({'display':'inline-block','visibility': 'visible','opacity': '1','animation': 'fade 1s'});
  
    deleteEntradaTransferTemp();
    //cargarDatosEntradaTransfer(folio); 
  }else if (tipoE == 4){

    if (_global.sucOrigen != 0) {
      html1 = `<div class="directBranch-disabled">
                <label for="txtRefereciaEDBranch">Referencia:</label>
                <input class="form-control" name="txtRefereciaEDBranch" id="txtRefereciaEDBranch" readonly>
              </div>`;
      html2 = `<div class="directBranch-disabled">
                <label for="txtSucDestinoEDBranch">Sucursal de destino:</label>
                <input class="form-control" name="txtSucDestinoEDBranch" id="txtSucDestinoEDBranch" readonly>
              </div>`;
      html3 = `<div class="directBranch-disabled">
                <label for="txtOrigenEDBranch">Sucursal de origen:</label>
                <input class="form-control" name="txtOrigenEDBranch" id="txtOrigenEDBranch" readonly>
              </div>`;
      html4 = `<div class="directBranch-disabled">
                <label for="txtNotasEDBranch">Notas:</label>
                <textarea class="form-control" name="txtNotasEDBranch" id="txtNotasEDBranch"></textarea>
              </div>`;
      html5 = ``;
      html6 = ``;
      html7 = ``;
      html8 = ``;
      html9 = ``;
      html10 = ``;
      html11 = ``;
      html12 = ``;
      html13 = ``;
      html14 = `<div class="form-group directBranch-disabled">
                  <div class="row">
                    <div class="col-lg-6">
                      <i data-toggle="modal" data-target="#agregar_ProductoEDBranch"><img src="../../../../img/timdesk/ICONO AGREGAR2_Mesa de trabajo 1.svg" width="30px" id="addProducto"></i>
                      <label>  Añadir producto</label>
                    </div>
                  </div>
                </div>`;
      html17 = `<table class="table opacidad" id="tblProductosEntradaDCSucursal" width="100%" cellspacing="0">
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
                <label for="">Los productos donde su cantidad sea igual a 0 no serán registrados.</label>
                <div class="">
                  <div class="directBranch-disabled">
                    <br>
                    <br>
                    <a class="btn-custom btn-custom--blue float-right" id="btnEditarEntradaDBranch" style="margin-right: 10px!important">Guardar cambios</a>
                    <a class="btn-custom btn-custom--blue float-right" id="btnVerEntradaDBranch" style="margin-right: 10px!important"><i class="fa fa-eye" aria-hidden="true"></i> Ver entrada</a>
                    <span id="sCerrarOC">
                    </span>
                  </div>
                </div>`;

      $("#div1").html(html1);
      $("#div2").html(html2);
      $("#div3").html(html3);
      $("#div4").html(html4);
      $("#div5").html(html5);
      $("#div6").html(html6);
      $("#div7").html(html7);
      $("#div8").html(html8);
      $("#div9").html(html9);
      $("#div10").html(html10);
      $("#div11").html(html11);
      $("#div12").html(html12);
      $("#div13").html(html13);
      $("#div14").html(html14);
      $("#div17").html(html17);

      $('.data-container .purchases-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
      $('.data-container .directProvider-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
      $('.data-container .directCustomer-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
      $('.data-container .transfer-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
      $('.data-container .directBranch-disabled').css({'display':'inline-block','visibility': 'visible','opacity': '1','animation': 'fade 1s'});
    
      deleteEntradaDirectaTemp();
      cargarDatosEntradaDirecta(folio);
    }else if (_global.proveedor != 0) {
      html1 = `<div class="directProvider-disabled">
                <label for="txtRefereciaEDProvider">Referencia:</label>
                <input class="form-control" name="txtRefereciaEDProvider" id="txtRefereciaEDProvider" readonly>
              </div>`;
      html2 = `<div class="directProvider-disabled">
                <label for="txtSucDestinoEDProvider">Sucursal de destino:</label>
                <input class="form-control" name="txtSucDestinoEDProvider" id="txtSucDestinoEDProvider" readonly>
              </div>`;
      html3 = `<div class="directProvider-disabled">
                <label for="txtProveedorEDProvider">Proveedor:</label>
                <input class="form-control" name="txtProveedorEDProvider" id="txtProveedorEDProvider" readonly>
              </div>`;
      html4 = `<div class="directProvider-disabled">
                <label for="usr">Serie*:</label>
                <div class="input-group">
                  <input required class="form-control alphaNumeric-only" type="text" name="txtSerieProviderED" id="txtSerieProviderED" placeholder="Serie" style="float:left;" onchange="validEmptyInputSLProvider('txtSerieProviderED', 'invalid-serieProviderED', 'La entrada debe de tener número de serie.')">
                  <div class="invalid-feedback" id="invalid-serieProviderED">La entrada debe de tener número de serie.</div>
                </div>
              </div>`;
      html5 = `<div class="directProvider-disabled">
                <label for="usr">Folio*:</label>
                <div class="input-group">
                  <input required class="form-control alphaNumeric-only" type="text" name="txtNoDocumentoProviderED" id="txtNoDocumentoProviderED" placeholder="Folio" style="float:left;" onchange="validEmptyInputSLProvider('txtNoDocumentoProviderED', 'invalid-noDocumentoProviderED', 'La entrada debe de tener un número de folio.')">
                  <div class="invalid-feedback" id="invalid-noDocumentoProviderED">La entrada debe de tener número de folio.</div>
                </div>
              </div>`;
      html6 = `<div class="directProvider-disabled">
                <label for="usr">Subtotal*:</label>
                <div class="input-group">
                  <input required class="form-control numericDecimal-only" type="text" name="txtSubtotalProviderED" id="txtSubtotalProviderED" placeholder="Ej. 1000.00" style="float:left;" onchange="validEmptyInputSLProvider('txtSubtotalProviderED', 'invalid-subtotalProviderED', 'La entrada debe de tener subtotal.')">
                  <div class="invalid-feedback" id="invalid-subtotalProviderED">La entrada debe de tener subtotal.</div>
                </div>
              </div>`;
      html7 = `<div class="directProvider-disabled">
                <label for="usr">IVA (Monto):</label>
                <div class="input-group">
                  <input class="form-control numericDecimal-only" type="text" name="txtIvaProviderED" id="txtIvaProviderED" placeholder="Ej. 1000.00" style="float:left;" value="0" onchange="validEmptyInputSLProvider('txtIvaProviderED', 'invalid-ivaProviderED', 'La entrada debe de tener IVA.')">
                  <div class="invalid-feedback" id="invalid-ivaProviderED">La entrada debe de tener IVA.</div>
                </div>
              </div>`;
      html8 = `<div class="directProvider-disabled">
                <label for="usr">IEPS (Monto):</label>
                <div class="input-group">
                  <input class="form-control numericDecimal-only" type="text" name="txtIEPSProviderED" id="txtIEPSProviderED" placeholder="Ej. 1000.00" style="float:left;" value="0" onchange="validEmptyInputSLProvider('txtIEPSProviderED', 'invalid-iepsProviderED', 'La entrada debe de tener IEPS.')">
                  <div class="invalid-feedback" id="invalid-iepsProviderED">La entrada debe de tener IEPS.</div>
                </div>
              </div>`;
      html9 = `<div class="directProvider-disabled">
                <label for="usr">Importe factura*:</label>
                <div class="input-group">
                  <input required class="form-control numericDecimal-only" type="text" name="txtImporteProviderED" id="txtImporteProviderED" placeholder="Ej. 1000.00" style="float:left;" onchange="validEmptyInputSLProvider('txtImporteProviderED', 'invalid-importeProviderED', 'La entrada debe de tener importe.')">
                  <div class="invalid-feedback" id="invalid-importeProviderED">La entrada debe de tener importe.</div>
                </div>
              </div>`;
      html10 = `<div class="directProvider-disabled">
                <label for="cmbTipoProviderED">Tipo*:</label>
                <div class="input-group">
                  <select name="cmbTipoProviderED" id="cmbTipoProviderED" required onchange="validEmptyInputSLProvider('cmbTipoProviderED', 'invalid-tipoProviderED', 'La entrada debe de tener un tipo.')"></select>
                  <div class="invalid-feedback" id="invalid-tipoProviderED">La entrada debe de tener un tipo.</div>
                </div>
              </div>`;
      html11 = `<div class="directProvider-disabled">
                <label for="usr">Fecha de factura*:</label>
                <div class="input-group">
                  <input required class="form-control" type="date" name="txtFechaFacturaProviderED" id="txtFechaFacturaProviderED" style="float:left;" onchange="validEmptyInputSLProvider('txtFechaFacturaProviderED', 'invalid-fechaFacturaProviderED', 'La entrada debe de tener una fecha de factura.')">
                  <div class="invalid-feedback" id="invalid-fechaFacturaProviderED">La entrada debe de una fecha de factura.</div>
                </div>
              </div>`;
      html12 = `<div class="directProvider-disabled">
                <label for="txtNotasEDProvider">Notas:</label>
                <textarea class="form-control" name="txtNotasEDProvider" id="txtNotasEDProvider"></textarea>
              </div>`;
      html13 = ``;
      html14 = `<div class="form-group directProvider-disabled">
                  <div class="row">
                    <div class="col-lg-6">
                      <i data-toggle=\"modal\" data-target=\"#agregar_ProductoEDProvider\"><img src=\"../../../../img/timdesk/ICONO AGREGAR2_Mesa de trabajo 1.svg\" width="30px" id="addProducto"></i>
                      <label>Añadir producto</label>
                    </div>
                  </div>
                </div>`;

      if(parseInt(_global.isVer) == 0){
        disabled = "";
      }else{
        disabled = "disabled";
      }

      html18 = `<table class="table opacidad" id="tblProductosEntradaDProvider" width="100%" cellspacing="0">
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
                <br>
                <div id="dataCuentaPagar"></div>
                <br>
                <div class="form-group">
                    <div class="row">
                      <div class="col-xl-12 col-lg-6 col-md-3 col-sm-2 col-xs-1">
                        <span id = "div12">
                          <div class="directProvider-disabled">
                            <label for="txtNotasEDProvider">Notas:</label>
                            <textarea class="form-control" name="txtNotasEDProvider" id="txtNotasEDProvider"></textarea>
                          </div>
                        </span>
                      </div>
                    </div>
                  </div>
                <div class="">
                  <div class="directProvider-disabled">
                    <a class="btn-custom btn-custom--blue float-right" id="btnEditarEntradaEDProvider" style="margin-right: 10px!important" `+disabled+`>Guardar cambios</a>
                    <a class="btn-custom btn-custom--blue float-right" id="btnVerEntradaEDProvider" style="margin-right: 10px!important"><i class="fa fa-file-pdf" aria-hidden="true"></i> Ver entrada</a>
                    <span id="sCerrarOC">
                    </span>
                  </div>`;

      $("#div1").html(html1);
      $("#div2").html(html2);
      $("#div3").html(html3);
      /* $("#div4").html(html4);
      $("#div5").html(html5);
      $("#div6").html(html6);
      $("#div7").html(html7);
      $("#div8").html(html8);
      $("#div9").html(html9); */
      //$("#div10").html(html10);
      //$("#div11").html(html11);
      //$("#div12").html(html12);
      $("#div13").html(html13);
      $("#div14").html(html14);
      $("#div18").html(html18);


      $('.data-container .purchases-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
      $('.data-container .directBranch-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
      $('.data-container .directCustomer-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
      $('.data-container .transfer-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
      $('.data-container .directProvider-disabled').css({'display':'inline-block','visibility': 'visible','opacity': '1','animation': 'fade 1s'}); 

      deleteEntradaDirectaTemp();
      cargarDatosEntradaDirectaProvider(folio);
    }else if (_global.cliente != 0) {
      html1 = `<div class="directCustomer-disabled">
                <label for="txtRefereciaEDCustomer">Referencia:</label>
                <input class="form-control" name="txtRefereciaEDCustomer" id="txtRefereciaEDCustomer" readonly>
              </div>`;
      html2 = `<div class="directCustomer-disabled">
                <label for="txtSucDestinoEDCustomer">Sucursal de destino:</label>
                <input class="form-control" name="txtSucDestinoEDCustomer" id="txtSucDestinoEDCustomer" readonly>
              </div>`;
      html3 = `<div class="directCustomer-disabled">
                <label for="txtClienteEDCustomer">Cliente:</label>
                <input class="form-control" name="txtClienteEDCustomer" id="txtClienteEDCustomer" readonly>
              </div>`;
      html4 = `<div class="directCustomer-disabled">
                <label for="txtNotasEDCustomer">Notas:</label>
                <textarea class="form-control" name="txtNotasEDCustomer" id="txtNotasEDCustomer"></textarea>
              </div>`;
      html5 = ``;
      html6 = ``;
      html7 = ``;
      html8 = ``;
      html9 = ``;
      html10 = ``;
      html11 = ``;
      html12 = ``;
      html13 = ``;
      html14 = `<div class="form-group directCustomer-disabled">
                  <div class="row">
                    <div class="col-lg-6">
                      <i data-toggle=\"modal\" data-target=\"#agregar_ProductoEDCustomer\"><img src=\"../../../../img/timdesk/ICONO AGREGAR2_Mesa de trabajo 1.svg\" width="30px" id="addProducto"></i>
                      <label>  Añadir producto</label>
                    </div>
                  </div>
                </div>`;
      html19 = `<table class="table opacidad" id="tblProductosEntradaDCCliente" width="100%" cellspacing="0">
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
                  <div class="directCustomer-disabled">
                    <br>
                    <br>
                    <a class="btn-custom btn-custom--blue float-right" id="btnEditarEntradaEDCustomer" style="margin-right: 10px!important">Guardar cambios</a>
                    <a class="btn-custom btn-custom--blue float-right" id="btnVerEntradaEDCustomer" style="margin-right: 10px!important"><i class="fa fa-eye" aria-hidden="true"></i> Ver entrada</a>
                    <span id="sCerrarOC">
                    </span>
                  </div>
                </div>`;

      $("#div1").html(html1);
      $("#div2").html(html2);
      $("#div3").html(html3);
      $("#div4").html(html4);
      $("#div5").html(html5);
      $("#div6").html(html6);
      $("#div7").html(html7);
      $("#div8").html(html8);
      $("#div9").html(html9);
      $("#div10").html(html10);
      $("#div11").html(html11);
      $("#div12").html(html12);
      $("#div13").html(html13);
      $("#div14").html(html14);
      $("#div19").html(html19);

      $('.data-container .purchases-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
      $('.data-container .directBranch-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
      $('.data-container .directProvider-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
      $('.data-container .transfer-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
      $('.data-container .directCustomer-disabled').css({'display':'inline-block','visibility': 'visible','opacity': '1','animation': 'fade 1s'});
    
      deleteEntradaDirectaTemp();
      cargarDatosEntradaDirectaCustomer(folio);
    }
  }
});

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

function deleteEntradaTransferTemp(){
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "delete_data",
      funcion: "delete_datosEntradaTransferTemp"
    },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta eliminar entrada oc temp:", respuesta);

      if (respuesta[0].status) {
        console.log("Entrada Transpaso. Eliminado todos");
        cargarDatosEntradaTransfer(folio);
      } else {
        console.log("Error al eliminar todos");
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function deleteEntradaDirectaTemp(){
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "delete_data",
      funcion: "delete_datosEntradaDirectaTemp"
    },
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

function openModalDelete(idEntradaTemp){
  $("#entryTempIDD").val(idEntradaTemp);
  $('#eliminar_ProductoEnt').modal('show');
}

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
        
        loadProductosOrderPurchase(folio);
        
      } else {
       
      }
    },
    error: function (error) {
      console.log(error);
      
    },
  });
}

function cargarDatosEntradaTransfer(folio){
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_DataEntryTransferEdit", data: folio },
    dataType: "json",
    success: function (data) {
      $("#cmbOrderPedido").val(data[0].pedido);
      $("#cmbSucursalDestino").val(data[0].sucursalD);
      $("#cmbSucursalOrigen").val(data[0].sucursalO);
      
      editEntradaTransferTemp(folio);   
    },
  });
}

function cargarDatosEntradaDirecta(folio){
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_DataEntryDirectEdit", data: folio },
    dataType: "json",
    success: function (data) {
      $("#txtRefereciaEDBranch").val(data[0].referencia);
      $("#txtSucDestinoEDBranch").val(data[0].sucursalD);
      $("#txtOrigenEDBranch").val(data[0].sucursalO);
      $("#txtNotasEDBranch").val(data[0].notas);
      
      saveEntradaDirectTemp(folio);   

      _global.referencia = data[0].referencia;
    },
  });
}

function cargarDatosEntradaDirectaProvider(folio){
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_DataEntryDirectProviderEdit", data: folio },
    dataType: "json",
    success: function (data) {
      $("#txtRefereciaEDProvider").val(data[0].referencia);
      $("#txtSucDestinoEDProvider").val(data[0].sucursalD);
      $("#txtProveedorEDProvider").val(data[0].proveedor);
      $("#txtNotasEDProvider").val(data[0].notas);
      /* $("#txtIvaProviderED").val(data[0].iva);
      $("#txtIEPSProviderED").val(data[0].ieps); */
      cargarCMBCategorias(data[0].categoria_id);
      cargarCMBSubcategorias(data[0].categoria_id,data[0].subcategoria_id);
      if(data[0].ordenCompra != 0){
        _global.id_OCProve = data[0].ordenCompra; 
        let html = `<div class="directProvider-disabled">
                <label for="txtOrdenEDProvider">Orden de compra:</label>
                <input class="form-control" name="txtOrdenEDProvider" id="txtOrdenEDProvider" readonly>
              </div>`;

        $("#div33").html(html);
        $("#txtOrdenEDProvider").val(data[0].RefOC);
        $('.data-container .directProvider-disabled').css({'display':'inline-block','visibility': 'visible','opacity': '1','animation': 'fade 1s'});
      }

      if(data[0].cuentaPagar_id != 0){
        _global.id_CuentaPagar = data[0].cuentaPagar_id; 
        let html = `<br>
              <div class="form-row">
                <div class="form-group col-12 col-md-3">
                  <label for="usr">Fecha de documento:*</label>
                  <div class="input-group">
                    <input required class="form-control" type="date" name="txtFechaFacturaProviderED" id="txtFechaFacturaProviderED" style="float:left;" onchange="validEmptyInputSLProvider('txtFechaFacturaProviderED', 'invalid-fechaFacturaProviderED', 'La entrada debe de tener una fecha de factura.')">
                    <div class="invalid-feedback" id="invalid-fechaFacturaProviderED">La entrada debe de una fecha de factura.</div>
                  </div>
                </div>
                <div class="form-group col-12 col-md-3">
                  <label for="usr">Folio de documento*:</label>
                  <div class="input-group">
                    <input required class="form-control alphaNumeric-only" type="text" name="txtNoDocumentoProviderED" id="txtNoDocumentoProviderED" placeholder="Folio" style="float:left;" onchange="validEmptyInputSLProvider('txtNoDocumentoProviderED', 'invalid-noDocumentoProviderED', 'La entrada debe de tener un número de folio.')">
                    <div class="invalid-feedback" id="invalid-noDocumentoProviderED">La entrada debe de tener número de folio.</div>
                  </div>
                </div>
                <div class="form-group col-12 col-md-3">
                  <label for="cmbTipoProviderED">Tipo*:</label>
                  <div class="input-group">
                    <select name="cmbTipoProviderED" id="cmbTipoProviderED" required onchange="validEmptyInputSLProvider('cmbTipoProviderED', 'invalid-tipoProviderED', 'La entrada debe de tener un tipo.')"></select>
                    <div class="invalid-feedback" id="invalid-tipoProviderED">La entrada debe de tener un tipo.</div>
                  </div>
                </div>
                <div class="form-group col-12 col-md-3">
                  <label for="usr">Categoria*:</label>
                  <select class="form-select" name="cmbCategoriaCuentaED" id="cmbCategoriaCuentaED" aria-label="Default select example"></select>
                  <div class="invalid-feedback" id="invalid-categoriaCuenta">El campo es obligatorio.</div>
                </div>
                <div class="form-group col-12 col-md-3">
                  <label for="cmbCategoriaCuenta">Subcategoria:*</label>
                  <select class="form-select" name="cmbSubcategoriaCuentaED" id="cmbSubcategoriaCuentaED"></select>
                  <div class="invalid-feedback" id="invalid-subcategoriaCuenta">El campo es obligatorio.</div>
                </div>
                <div class="form-group col-12 col-md-3">
                  <label for="usr">Subtotal*:</label>
                  <div class="input-group">
                    <input required class="form-control numericDecimal-only" type="text" name="txtSubtotalProviderED" id="txtSubtotalProviderED" placeholder="Ej. 1000.00" style="float:left;" onchange="validEmptyInputSLProvider('txtSubtotalProviderED', 'invalid-subtotalProviderED', 'La entrada debe de tener subtotal.')">
                    <div class="invalid-feedback" id="invalid-subtotalProviderED">La entrada debe de tener subtotal.</div>
                  </div>
                </div> 
                <div class="form-group col-12 col-md-3">
                  <label for="usr">Importe total*:</label>
                  <div class="input-group">
                    <input required class="form-control numericDecimal-only" type="text" name="txtImporteProviderED" id="txtImporteProviderED" placeholder="Ej. 1000.00" style="float:left;" onchange="validEmptyInputSLProvider('txtImporteProviderED', 'invalid-importeProviderED', 'La entrada debe de tener importe.')">
                    <div class="invalid-feedback" id="invalid-importeProviderED">La entrada debe de tener importe.</div>
                  </div>
                </div>
              </div>
              <div class="form-row">
                
              </div>`;

        $("#dataCuentaPagar").html(html);
        //$("#txtSerieProviderED").val(data[0].serie);
        $("#txtNoDocumentoProviderED").val(data[0].folio);
        $("#txtSubtotalProviderED").val(data[0].subtotal);
        $("#txtImporteProviderED").val(data[0].importe);
        $("#txtFechaFacturaProviderED").val(data[0].fechaFactura);

        new SlimSelect({
          select: '#cmbTipoProviderED',
          deselectLabel: '<span class="">✖</span>',
        }); 
        new SlimSelect({
          select: '#cmbCategoriaCuentaED',
          deselectLabel: '<span class="">✖</span>',
        }); 
        new SlimSelect({
          select: '#cmbSubcategoriaCuentaED',
          deselectLabel: '<span class="">✖</span>',
        }); 
  
        loadTypeEntryDirectProvider(data[0].tipo,"cmbTipoProviderED");
      }
      
      saveEntradaDirectTempProvider(folio);   

      _global.referencia = data[0].referencia;
    },
  });
}

function cargarDatosEntradaDirectaCustomer(folio){
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_DataEntryDirectCustomerEdit", data: folio },
    dataType: "json",
    success: function (data) {
      $("#txtRefereciaEDCustomer").val(data[0].referencia);
      $("#txtSucDestinoEDCustomer").val(data[0].sucursalD);
      $("#txtClienteEDCustomer").val(data[0].cliente);
      $("#txtNotasEDCustomer").val(data[0].notas);
      
      saveEntradaDirectTempCustomer(folio);   

      _global.referencia = data[0].referencia;
    },
  });
}

function cargarDatosEntradaOC(folio){
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_DataEntryOCEdit", data: folio },
    dataType: "json",
    success: function (data) {
      $("#cmbOrdenCompra").val(data[0].referenciaOC);
      $("#cmbProveedor").val(data[0].proveedor);
      $("#cmbSucursal").val(data[0].sucursal);
      $("#txtNoDocumento").val(data[0].folioFactura);
      $("#txtSerie").val(data[0].serieFactura);
      $("#txtSubtotal").val(data[0].subtotal);
      $("#txtIva").val(data[0].iva);
      $("#txtIEPS").val(data[0].ieps);
      $("#txtImporte").val(data[0].importe);
      $("#txtDescuento").val(data[0].descuento);
      $("#txtFechaFactura").val(data[0].fechaFactura);

      if(data[0].isRemision == "1"){
        $("#cbxRemision").prop("checked", true);
      }else{
        $("#cbxRemision").prop("checked", false);
      }

      $("#txaNotaEntrada").val(data[0].notas);
      $("#txtReferenciaP").html(data[0].referenciaOC);
      $("#txtProveedorP").html(data[0].proveedor);
      $("#txtFechaEmisionP").html(data[0].fechaCreacionOC);
      $("#txtFechaEstimadaP").html(data[0].fechaEstimadaOC);
      $("#NotasInternasP").html(data[0].notasInternasOC);
      
      $('#txtDireccionP').html("Sucursal:"+data[0].sucursal +". "+
                            data[0].Calle +" "+
                            data[0].NumExt +", "+
                            data[0].Prefijo +", "+
                            data[0].NumInt +", "+
                            data[0].Colonia +", "+
                            data[0].Municipio +", "+
                            data[0].Estado +", "+
                            data[0].Pais +" ");
      
      saveEntradaOCTemp(folio);
    },
  });
}

function saveEntradaOCTemp(folio){
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "save_data",
      funcion: "save_entry_partial_ocTempTableEdit",
      data:folio
    },
    dataType: "json",
    success: function (respuesta) {
      console.log(respuesta);
      if (respuesta[0].status) {
        
        loadProductosOrderPurchase(folio);
        
      } else {
       
      }
    },
    error: function (error) {
      console.log(error);
      
    },
  });
}

function editEntradaTransferTemp (folio){
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "save_data",
      funcion: "save_entry_partial_transferTempTableEdit",
      data:folio
    },
    dataType: "json",
    success: function (respuesta) {
      console.log(respuesta);
      if (respuesta[0].status) {
        
        loadProductosTranfer(folio);
        
      } else {
       
      }
    },
    error: function (error) {
      console.log(error);
      
    },
  });
}

function saveEntradaDirectTemp(folio){
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "save_data",
      funcion: "save_entry_directTempTableEdit",
      data:folio
    },
    dataType: "json",
    success: function (respuesta) {
      console.log(respuesta);
      if (respuesta[0].status) {
        
        loadProductosDBranch(folio);
        
      } else {
       
      }
    },
    error: function (error) {
      console.log(error);
      
    },
  });
}

function saveEntradaDirectTempProvider(folio){
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "save_data",
      funcion: "save_entry_directProviderTempTableEdit",
      data:folio
    },
    dataType: "json",
    success: function (respuesta) {
      console.log(respuesta);
      if (respuesta[0].status) {
        
        loadProductosDProvider(folio);
        
      } else {
       
      }
    },
    error: function (error) {
      console.log(error);
      
    },
  });
}

function saveEntradaDirectTempCustomer(folio){
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "save_data",
      funcion: "save_entry_directTempTableEdit",
      data:folio
    },
    dataType: "json",
    success: function (respuesta) {
      console.log(respuesta);
      if (respuesta[0].status) {
        
        loadProductosDCustomer(folio);
        
      } else {
       
      }
    },
    error: function (error) {
      console.log(error);
      
    },
  });
}

function loadProductosOrderPurchase(folio){
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
      data: { clase: "get_data", funcion: "get_productosEntradaOCTempTableEdit", data:folio },
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
      { orderable: false, targets: 1, width: '10%' },
      { orderable: false, targets: 2, width: '9%' },
      { orderable: false, targets: 3, width: '8%' },
      { orderable: false, targets: 4, width: '8%' },
      { orderable: false, targets: 5, width: '11%' },
      { orderable: false, targets: 6, width: '11%' },
      { orderable: false, targets: 7, width: '9%' },
      { orderable: false, targets: 8, width: '9%' },
      { orderable: false, targets: 9, width: '12%' },
      { orderable: false, targets: 10, width: '13%' },
    ],
  });

  obtenerTotal(folio); 
}

function loadProductosTranfer(folio){
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
      data: { clase: "get_data", funcion: "get_productosEntradaTransferTempTableEdit", data:folio },
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
      { data: "FechaCaducidad" },
    ],
    columnDefs: [
      { orderable: false, targets: 0, visible: false },
    ],
  });

  obtenerTotalTransfer(folio);
}

function loadProductosDBranch(folio){
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
      data: { clase: "get_data", funcion: "get_productosEntradaDirectaTempTable", data: 0},
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
      { orderable: false, targets: 1, width: '15%' },
      { orderable: false, targets: 2, width: '15%' },
      { orderable: false, targets: 3, width: '20%' },
      { orderable: false, targets: 4, width: '15%' },
      { orderable: false, targets: 5, width: '15%' },
      { orderable: false, targets: 6, width: '15%' },
      { orderable: false, targets: 7, width: '5%' },
    ],
  });
}

function loadProductosDProvider(folio){
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
      data: { clase: "get_data", funcion: "get_productosEntradaDirectaTempTableProvider", data: 0},
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
}

function loadProductosDCustomer(folio){
  $("#tblProductosEntradaDCCliente").DataTable().destroy();
  $("#tblProductosEntradaDCCliente").dataTable({
    language: setFormatDatatables(),
    dom: "Bfrtip",
    buttons: [
    ],
    scrollX: true,
    lengthChange: false,
    info: false,
    ajax: {
      url: "../../php/funciones.php",
      data: { clase: "get_data", funcion: "get_productosEntradaDirectaTempTableCustomer", data: 0},
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
      { orderable: false, targets: 1, width: '15%' },
      { orderable: false, targets: 2, width: '15%' },
      { orderable: false, targets: 3, width: '20%' },
      { orderable: false, targets: 4, width: '15%' },
      { orderable: false, targets: 5, width: '15%' },
      { orderable: false, targets: 6, width: '5%' },
    ],
  });
}

function obtenerTotal(folio){
  //Obtener subtotal
  $.ajax({
    
    url:"../../php/funciones.php",
    data:{clase:"get_data", funcion:"get_subTotalEntradaOCTempEdit",datos:folio},
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
    data:{clase:"get_data", funcion:"get_impuestoEntradaOCEdit",datos:folio},
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

  //Obtener total
  $.ajax({
    
    url:"../../php/funciones.php",
    data:{clase:"get_data", funcion:"get_totalEntradaOCEdit",datos:folio},
    dataType:"json",
    success:function(respuesta){
      $('#Total').html(dosDecimales(respuesta[0].Total))
    },
    error:function(error){
      console.log(error);
    }
  });
}

function obtenerTotalTransfer(folio){
  //Obtener total
  $.ajax({
    
    url:"../../php/funciones.php",
    data:{clase:"get_data", funcion:"get_totalEntradaTransferEdit",datos:folio},
    dataType:"json",
    success:function(respuesta){
      $('#TotalTras').html(respuesta[0].Total)
    },
    error:function(error){
      console.log(error);
    }
  });
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
          funcion: "validar_cantidadProd_entradaOCEdit",
          data: idEntradaTemp,
          data2: cantEntrada,
          data3: folio,
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
                  obtenerTotal(folio);
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
                obtenerTotal(folio);
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
                obtenerTotal(folio);
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
                obtenerTotal(folio);
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

    if(cantEntrada < 0){
      $("#"+invalidDivID).css("display", "block");
      $("#"+invalidDivID).text("La cantidad no puede ser menor a 0");
      $("#"+inputID).addClass("is-invalid");
    }else{
      $.ajax({
        url: "../../php/funciones.php",
        data: {
          clase: "get_data",
          funcion: "validar_cantidadProd_entrada_traspasoEdit",
          data: idEntradaTemp,
          data2: cantEntrada,
          data3: folio
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
  
            updateCantidadTras(cantEntrada,idEntradaTemp);
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

        obtenerTotalTransfer(folio);
      } else {
       
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

function deleteLoteSerieTras(idEntradaTemp){

  $('#eliminar_ProductoEntTras').modal('toggle');

  updateCantidadTras(0, idEntradaTemp);
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
          loadProductosOrderPurchase(folio);
          //obtenerTotal(folio);
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
          loadProductosOrderPurchase(folio);
          //obtenerTotal(folio);
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
        
        loadProductosOrderPurchase(folio);
        
      } else {
       
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

$(document).on("click", "#btnEditarEntradaOC", function () {

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
        folioEntrada: folio
      }

      $.ajax({
        url: "../../php/funciones.php",
        data: {
          clase: "edit_data",
          funcion: "edit_entry_partial_ocTable",
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
    $("#frmEntradaOC")[0].classList.add('was-validated');
    console.log("No validados edición");

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

  /*$("#folio_entrada").val("EC000015");
  $("#id_cuenta_pagar").val("161");
  $("#folio_doc").val("AAAA1234BBBB5678");
  $("#serie_doc").val("321654987A123");
  $('#fullInvoice').modal('show');*/
});

$(document).on("click", "#btnEditarEntradaTraspaso", function () {
  console.log("HEYYYYYY");
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
      folioEntrada: folio
    }
    $.ajax({
      url: "../../php/funciones.php",
      data: {
        clase: "edit_data",
        funcion: "edit_entry_tranfer_Table",
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
          
        }
      },
      error: function (error) {
        console.log(error);
      },
    });

});

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
    1000,
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

  
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "edit_data",
      funcion: "edit_entradaOC_estatusFacturaEdit",
      data: folio_doc, 
      data2: serie_doc,
      data3: folio_entrada,
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

$(document).on("click", "#btnVerEntradaOC", function () {
  window.location.href = "ver_entrada.php?f="+folio+"&ed=1";
});

$(document).on("click", "#btnVerEntradaTraspaso", function () {
  descargarPDFEntradaTraspaso(folio);
  //window.location.href = "ver_entrada.php?f="+folio+"&ed=1";
});

$(document).on("click", "#btnVerEntradaDBranch", function () {
  window.location.href = "ver_entrada.php?f="+folio+"&ed=1";
});

$(document).on("click", "#btnVerEntradaEDCustomer", function () {
  window.location.href = "ver_entrada.php?f="+folio+"&ed=1";
});

$(document).on("click", "#btnVerEntradaEDProvider", function () {
  //window.location.href = "ver_entrada.php?f="+folio+"&ed=1";
  window.location.href = "functions/descargar_Entrada.php?folio="+folio+"&id_cuenta="+idCuentaPagarVer;
});

function cargarTablaProductosEDBranch(){
  $("#tblListadoProductosEDBranch").DataTable().destroy();
  $("#tblListadoProductosEDBranch").dataTable({
    language: setFormatDatatables(),
    scrollX: true,
    info: false,
    ajax: {
      url: "../../php/funciones.php",
      data: { clase: "get_data", 
              funcion: "get_productosSucursalEDTable",
              data: 0
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
  $("#tblListadoProductosEDProvider").DataTable().destroy();
  $("#tblListadoProductosEDProvider").dataTable({
    language: setFormatDatatables(),
    scrollX: true,
    info: false,
    ajax: {
      url: "../../php/funciones.php",
      data: { clase: "get_data", 
              funcion: "get_productosProveedorEDTable",
              data: 0
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
  $("#tblListadoProductosEDCustomer").DataTable().destroy();
  $("#tblListadoProductosEDCustomer").dataTable({
    language: setFormatDatatables(),
    scrollX: true,
    info: false,
    ajax: {
      url: "../../php/funciones.php",
      data: { clase: "get_data", 
              funcion: "get_productosCustomerEDTable",
              data: 0
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

function seleccionarProductoED(id){
  $.ajax({
    url: "../../php/funciones.php",
    data: { 
            clase: "save_data", 
            funcion: "save_datosProductoED", 
            data: id,
            data2: 0
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
  $.ajax({
    url: "../../php/funciones.php",
    data: { 
            clase: "save_data", 
            funcion: "save_datosProductoED", 
            data: id,
            data2: 0
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
  $.ajax({
    url: "../../php/funciones.php",
    data: { 
            clase: "save_data", 
            funcion: "save_datosProductoED", 
            data: id,
            data2: 0
          },
    dataType: "json",
    success: function (respuesta) {
      if (respuesta[0].status) {
        if(respuesta[0].insertado == '1'){
          $("#tblProductosEntradaDCCliente").DataTable().ajax.reload();
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
    if(_global.id_OCProve != 0){
      let cantEntrada = parseInt($("#" + inputID).val());
      let idEntradaTemp = inputID.substr(14);
      $.ajax({
        url: "../../php/funciones.php",
        async:false,
        data: {
          clase: "get_data",
          funcion: "get_validation_cantidadOC",
          data: cantEntrada, data2:idEntradaTemp, data3: _global.id_OCProve, data4:_global.id_CuentaPagar
        },
        dataType: "json",
        success: function (respuesta) {
          if(respuesta[0].status == 'ok'){
            flag = true;
          }else if(respuesta[0].status == 'no'){
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
    }else{
      $("#tblProductosEntradaDProvider").DataTable().ajax.reload();
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
  }

  if($("#txtSerieProviderED").val() != ''){
    validarFolio_serieEDProvider();
  }

  if($("#txtImporteProviderED").val() != ''){
    obtenerTotalEDProvider();
  }
}

function validarFolio_serieEDProvider(){
  let folio = $("#txtNoDocumentoProviderED").val().trim();
  const serie = 'N/A';

  if($("#txtNoDocumentoProviderED").val().trim() == ''){
    return;
  }

  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "validar_folio_entradaEDProviderEdit",
      data: folio,
      data1: serie,
      data2: _global.referencia
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
        $("#tblProductosEntradaDCCliente").DataTable().ajax.reload();
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

$(document).on("click", "#btnEditarEntradaDBranch", function () {
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
    var datos = {
      notas: $("#txtNotasEDBranch").val(),
      referencia: _global.referencia,
    }

    $.ajax({
      url: "../../php/funciones.php",
      data: {
        clase: "edit_data",
        funcion: "edit_entry_partial_edTable",
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
  }else{
    $("#frmEntradaOC")[0].classList.add('was-validated');
  }
});

$(document).on("click", "#btnEditarEntradaEDCustomer", function () {
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
    var datos = {
      notas: $("#txtNotasEDCustomer").val(),
      referencia: _global.referencia,
    }

    $.ajax({
      url: "../../php/funciones.php",
      data: {
        clase: "edit_data",
        funcion: "edit_entry_partial_edCustomerTable",
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
  }else{
    $("#frmEntradaOC")[0].classList.add('was-validated');
  }
});

$(document).on("click", "#btnEditarEntradaEDProvider", function () {

  if(parseInt(_global.isVer) != 0){
    Lobibox.notify("warning", {
      size: "mini",
      rounded: true,
      delay: 5000,
      delayIndicator: false,
      position: "center top",
      icon: true,
      img: "../../../../img/timdesk/warning_circle.svg",
      msg: "No es posible editar ésta entrada",
      sound: '../../../../../sounds/sound4'
    });
    return;
  }

  //si esta checkeado crear una cuenta por pagar, hace requeridos a los campos
  if(_global.id_CuentaPagar != 0){
    $("#txtFechaFacturaProviderED").prop('required', true);
    $("#txtNoDocumentoProviderED").prop('required', true);
    //$("#txtSerieProviderED").prop('required', true);
    $("#txtSubtotalProviderED").prop('required', true);
    $("#txtImporteProviderED").prop('required', true);
    $("#cmbTipoProviderED").prop('required', true);
  }else{
    $("#txtFechaFacturaProviderED").prop('required', false);
    $("#txtNoDocumentoProviderED").prop('required', false);
    //$("#txtSerieProviderED").prop('required', false);
    $("#txtSubtotalProviderED").prop('required', false);
    $("#txtImporteProviderED").prop('required', false);
    $("#cmbTipoProviderED").prop('required', false);
  }

  var emptyTable = document.querySelectorAll(".invalid-empty");
  var emptyTableCount = document.querySelectorAll(".invalid-emptyCount");
  
  emptyTable.forEach(element => {
    if (!element.value) {
      element.classList.addClass("is-invalid");
    }
  });

  emptyTableCount.forEach(element => {
    if (!element.value) {
      element.classList.addClass("is-invalid");
    }

    if(element.value <= 0 ){
      element.classList.addClass("is-invalid");
    }
  });

  if ($("#frmEntradaOC")[0].checkValidity()) {
    let badTipoED = true;
    //let badSerieED = true;
    let badFolioED = true;
    let badSubtotalED = true;
    let badImporteED = true;
    let badFechaED = true;
    let badCategoria = true;
    let badSubcategoria = true;

    if(_global.id_CuentaPagar != 0){
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
      let datos;

      if(_global.id_CuentaPagar != 0){
        datos= {
          serie: $("#txtSerieProviderED").val(),
          folio: $("#txtNoDocumentoProviderED").val(),
          tipoEntradas: $("#cmbTipoProviderED").val(),
          subtotal: $("#txtSubtotalProviderED").val(),
          importe: $("#txtImporteProviderED").val(),
          fecha: $("#txtFechaFacturaProviderED").val(),
          notas: $("#txtNotasEDProvider").val(),
          referencia: _global.referencia,
          addCuentaPagar:  1,
          categoria: $("#cmbCategoriaCuentaED").val(),
          subcategoria: $("#cmbSubcategoriaCuentaED").val()
        }
      }else{
        datos= {
          tipoEntradas: 0,
          notas: $("#txtNotasEDProvider").val(),
          referencia: _global.referencia,
          addCuentaPagar:  0,
          categoria: $("#cmbCategoriaCuentaED").val(),
          subcategoria: $("#cmbSubcategoriaCuentaED").val()
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
          $('#Total').html(dosDecimales(respuesta[0].Total))

          if(dosDecimales(importe) != dosDecimales(respuesta[0].Total)){
            $("#invalid-totalED").css("display", "block");
            $("#Total").addClass("is-invalid");
            $("#invalid-importeProviderED").css("display", "block");
            $("#importeProviderED").addClass("is-invalid");

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
                clase: "edit_data",
                funcion: "edit_entry_partial_edProviderTable",
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
    
      //edita datos
      $.ajax({
        url: "../../php/funciones.php",
        data: {
          clase: "edit_data",
          funcion: "edit_entry_partial_edProviderTable",
          datos,
        },
        dataType: "json",
        success: function (respuesta) {
          if (respuesta[0].status) {
            if(respuesta[0].status == 'no'){
              Lobibox.notify("warning", {
                size: "mini",
                rounded: true,
                delay: 5000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../../../img/timdesk/warning_circle.svg",
                msg: "No es posible editar ésta entrada",
                sound: '../../../../../sounds/sound4'
              });
              return;
            }
            if(_global.id_CuentaPagar != 0){
              $("#folio_entrada").val(respuesta[0].insertado);
              $("#id_cuenta_pagar").val(respuesta[0].cuentaPagar);
              $("#folio_doc").val($("#txtNoDocumentoProviderED").val());
              //$("#serie_doc").val($("#txtSerieProviderED").val());
              $('#fullInvoice').modal('show');
            }else{
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

    if(_global.id_CuentaPagar != 0){
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
      $("#cmbCategoriaCuentaED").html(html);
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
      
      $("#cmbSubcategoriaCuentaED").html(html);
    },
    error: function (error) {
      console.log("Error");
      console.log(error);
    },
  });
}