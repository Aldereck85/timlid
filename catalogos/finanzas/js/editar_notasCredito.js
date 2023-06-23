var _permissions = {
  read: 0,
  add: 0,
  edit: 0,
  delete: 0,
  export: 0,
};

var _global = {
  PKNotaCredito: 0,
  TipoNC: 0,
  nameFileXML: '',
  idDetalleDev: 0,
  idDetalleNCTemp: 0,
  rutaPDF: '',
  rutaServer: ''
};

$(document).ready(function(){
    new SlimSelect({
      select: "#cmbTipoGral",
      deselectLabel: '<span class="">✖</span>',
    });

    var _Tipo = 0;
    if(_global.TipoNC == 'M') {
      _Tipo = 1;
    }else if(_global.TipoNC == 'C') {
      _Tipo = 2;
    }
    cargarCMBTipoGral(_Tipo, "cmbTipoGral");

    $('#agregar_Producto').on('shown.bs.modal', cargarTablaProductosDevolucion);
});

function obtenerEliminarNotaCredito() {
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "delete_data",
      funcion: "delete_datosNotaCredito",
      datos: _global.PKNotaCredito,
    },
    dataType: "json",
    success: function (respuesta) {
      if (respuesta[0].status) {
        $("#tblNotasCredito").DataTable().ajax.reload();
        Lobibox.notify("success", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/checkmark.svg",
          msg: "Nota de crédito eliminada correctamente!",
          sound: "../../../../../sounds/sound4",
        });
        setTimeout(function(){window.location.href = "./"},1500);

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
          sound: "../../../../../sounds/sound4",
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
        sound: "../../../../../sounds/sound4",
      });
    },
  });
}

/*botón removido de descarga pdf
  <div class="row">
  <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-xs-6">
    <div class="col-lg-1">
      <label for="usr">PDF:</label>
      <img class="readEditPermissions" type="submit" width="50px" id="btnDownloadPDF" onclick="descargarPDFNC()" src="../../../../img/excel-azul.svg" />
    </div>
  </div>
  </div> */

function mostrarTipoGral1(){
  html = `<div class="form-group">
          </div>
          <div class="form-group">
            <div class="row">
              <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-xs-6">
                <label for="usr">Proveedor:*</label>
                <div class="row">
                  <div class="col-lg-12 input-group">
                    <select name="cmbProveedor" id="cmbProveedor" required disabled>
                    </select>
                    <div class="invalid-feedback" id="invalid-proveedor">La nota debe de tener una cuenta por pagar.</div>
                  </div>
                </div>
              </div>
              <div class="col-lg-4">
                <label for="usr">Folio fiscal:*</label>
                <div class="row">
                  <div class="col-lg-12 input-group">
                    <input class="form-control numeric-only" type="text" name="txtFolioFiscal" id="txtFolioFiscal" required maxlength="5" placeholder="Folio fiscal" onchange="validEmptyInput('txtFolioFiscal', 'invalid-folioFiscal', 'La nota debe tener un folio fiscal.')">
                    <div class="invalid-feedback" id="invalid-folioFiscal">La nota debe tener un folio fiscal.</div>
                  </div>
                </div>
              </div>
              <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-xs-6">
                <div class="providers-disabled">
                  <label for="usr">Documento relacionado*:</label>
                  <div class="row">
                    <div class="col-lg-12 input-group">
                      <select name="cmbCuentaPagar" id="cmbCuentaPagar" required onchange="validEmptyInput('cmbCuentaPagar', 'invalid-cuentaPagar', 'La nota debe de tener una cuenta por pagar.')" disabled>
                          
                      </select>
                      <div class="invalid-feedback" id="invalid-cuentaPagar">La nota debe de tener una cuenta por pagar.</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="form-group"> 
            <div class="row">
              <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-6">
                <div class="providers-disabled">
                  <label for="usr">Serie*:</label>
                  <div class="input-group">
                    <input required class="form-control alphaNumeric-only" type="text" name="txtSerie" id="txtSerie" placeholder="Serie" style="float:left;" onchange="validEmptyInput('txtSerie', 'invalid-serie', 'La nota debe de tener número de serie.')">
                    <div class="invalid-feedback" id="invalid-serie">La nota debe de tener número de serie.</div>
                  </div>
                </div>
              </div>
              <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-6">
                <div class="providers-disabled">
                  <label for="usr">Folio*:</label>
                  <div class="input-group">
                    <input required class="form-control alphaNumeric-only" type="text" name="txtFolio" id="txtFolio" placeholder="Folio" style="float:left;" onchange="validEmptyInput('txtFolio', 'invalid-folio', 'La nota debe de tener un número de folio.')">
                    <div class="invalid-feedback" id="invalid-folio">La nota debe de tener número de folio.</div>
                  </div>
                </div>
              </div>
              <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-6">
                <div class="providers-disabled">
                  <label for="usr">Importe*:</label>
                  <div class="input-group">
                    <input required class="form-control numericDecimal-only" type="number" name="txtImporte" id="txtImporte" placeholder="Ej. 1000.00" style="float:left;" onchange="validEmptyInput('txtImporte', 'invalid-importe', 'La entrada debe de tener importe.')">
                    <div class="invalid-feedback" id="invalid-importe">La entrada debe de tener importe.</div>
                  </div>
                </div>
              </div>
              <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-6">
                <div class="providers-disabled">
                  <label for="usr">Subtotal*:</label>
                  <div class="input-group">
                    <input required class="form-control numericDecimal-only" type="number" name="txtSubtotal" id="txtSubtotal" placeholder="Ej. 1000.00" style="float:left;" onchange="validEmptyInput('txtSubtotal', 'invalid-subtotal', 'La nota debe de tener subtotal.')">
                    <div class="invalid-feedback" id="invalid-subtotal">La nota debe de tener subtotal.</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <div class="col-xl-2 col-lg-2 col-md-2 col-sm-3 col-xs-3">
                <div class="providers-disabled">
                  <label for="usr">IVA (Monto):</label>
                  <div class="input-group">
                    <input class="form-control numericDecimal-only" type="number" name="txtIva" id="txtIva" placeholder="Ej. 1000.00" style="float:left;">
                  </div>
                </div>
              </div>
              <div class="col-xl-2 col-lg-2 col-md-2 col-sm-3 col-xs-3">
                <div class="providers-disabled">
                  <label for="usr">IEPS (Monto):</label>
                  <div class="input-group">
                    <input class="form-control numericDecimal-only" type="number" name="txtIeps" id="txtIeps" placeholder="Ej. 1000.00" style="float:left;">
                  </div>
                </div>
              </div>
              <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-xs-6">
                <div class="providers-disabled">
                  <label for="usr">Fecha de nota*:</label>
                  <div class="input-group">
                    <input required class="form-control" type="date" name="txtFechaNota" id="txtFechaNota" style="float:left;" onchange="validEmptyInput('txtFechaNota', 'invalid-fechaNota', 'La nota debe de tener una fecha.')">
                    <div class="invalid-feedback" id="invalid-fechaNota">La nota debe de una fecha.</div>
                  </div>
                </div>
              </div>
              <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-xs-6">
                <div class="providers-disabled">
                  <label for="usr">Tipo*:</label>
                  <div class="row">
                    <div class="col-lg-12 input-group">
                      <select name="cmbTipoNota" id="cmbTipoNota" required disabled>
                          
                      </select>
                      <div class="invalid-feedback" id="invalid-tipoNota">La nota debe de tener un tipo.</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          `;

  html2 = `<a class="btn-custom btn-custom--blue float-right" id="btnEditarNotaCredito">Guardar cambios</a>
          <a class="btn-custom btn-custom--blue float-right" style="margin-right:25px" data-toggle="modal" data-target="#eliminar_NotaCredito" id="btnEliminarNotaCredito">Eliminar Nota Crédito</a>`;

  /* html3 = ` <div class="form-group">
              <div class="row">
              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <label for="usr">Cargar datos desde XML:*</label>
                <div class="d-flex justify-content-center">
                  <div class="btnesp espAgregar">
                    <input type="file" id="inptFileXML" name="inptFileXML" accept="text/xml" onchange="changefileXML('inptFileXML');">
                  </div>
                </div>
              </div>
              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <label for="usr">Subir PDF:</label>
                <div class="d-flex justify-content-center">
                  <div class="btnesp espAgregar">
                    <input type="file" id="inptFilePDF" name="inptFilePDF" accept="application/pdf">
                  </div>
                </div>
              </div>
            </div>
          </div>`; */

  $("#diseno").html(html);
  $("#addBoton").html(html2);
  //$("#inputsFiles").html(html3);

  cargarDatosNCMonto();
}

function mostrarTipoGral2(){
  html3 = ``;

  /* boton para pdf removido
    <div class="row">
      <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-xs-6">
        <div class="col-lg-1">
          <label for="usr">PDF:</label>
          <img class="readEditPermissions" type="submit" width="50px" id="btnDownloadPDF" onclick="descargarPDFNC()" src="../../../../img/excel-azul.svg" />
        </div>
      </div>
    </div>
  */
    
  html = `<div class="form-group">
          </div>
          <div class="form-group">
            <div class="row">
              <div class="col-xl-3 col-lg-3 col-md-3 col-sm-4 col-xs-6">
                <label for="usr">Proveedor:*</label>
                <div class="row">
                  <div class="col-lg-12 input-group">
                    <select name="cmbProveedorCant" id="cmbProveedorCant" required disabled>
                    </select>
                    <div class="invalid-feedback" id="invalid-proveedorCant">La nota debe de tener una cuenta por pagar.</div>
                  </div>
                </div>
              </div>
              <div class="col-xl-9 col-lg-9 col-md-9 col-sm-8 col-xs-6">
                <span id=disenoDevolucion>
                </span>
              </div>
            </div>
          </div>
          <span id="disenoDevolucion2">
          </span>
          `;
  
  html2 = ``;

  $("#diseno").html(html);
  $("#addBoton").html(html2);
  //$("#inputsFiles").html(html3);

  cargarDatosNCCantidad();
}

function cargarDatosNCMonto(){
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_dataNCMonto", data: _global.PKNotaCredito },
    dataType: "json",
    success: function (data) {
      cargarCMBProveedor(data[0].proveedor, "cmbProveedor");
      $("#txtFolioFiscal").val(data[0].folioFiscal);
      cargarCMBCuentaPagar(data[0].cuentaPorPagar, "cmbCuentaPagar");
      $("#txtSerie").val(data[0].serie);
      $("#txtFolio").val(data[0].folio);
      $("#txtImporte").val(data[0].importe);
      $("#txtSubtotal").val(data[0].subtotal);
      $("#txtIva").val(data[0].iva);
      $("#txtIeps").val(data[0].ieps);
      $("#txtFechaNota").val(data[0].fecha);
      cargarCMBTipoNota(data[0].tipo, "cmbTipoNota");
      _global.rutaPDF = data[0].pdf

      setTimeout(function(){
        new SlimSelect({
          select: "#cmbTipoNota",
          deselectLabel: '<span class="">✖</span>',
        });
        new SlimSelect({
          select: "#cmbCuentaPagar",
          deselectLabel: '<span class="">✖</span>',
        });
        new SlimSelect({
          select: "#cmbProveedor",
          deselectLabel: '<span class="">✖</span>',
        });
      },100);
    },
  });
}

function cargarDatosNCCantidad(){
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_dataNCCantidad", data: _global.PKNotaCredito },
    dataType: "json",
    success: function (data) {
      
      cargarCMBProveedorDevo(data[0].proveedor, "cmbProveedorCant");
      cargarCMBDevolucionCant(data[0].devolucion, "cmbDevolucionesCant", data[0].proveedor);

      html0 = `<div class="form-group">
                <div class="row">
                  <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-6">
                    <label for="usr">Devoluciones:*</label>
                    <div class="row">
                      <div class="col-lg-12 input-group">
                        <select name="cmbDevolucionesCant" id="cmbDevolucionesCant" required disabled>
                            
                        </select>
                        <div class="invalid-feedback" id="invalid-devolucionesCant">La nota debe de tener una cuenta por pagar.</div>
                      </div>
                    </div>
                  </div>
                  <div class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-xs-6">
                    <span id="diseno3">
                    </span>
                  </div>
                </div>
              </div>`;

      $("#disenoDevolucion").html(html0);

      html1 = `<div class="form-group"> 
                <div class="row">
                  <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <label for="usr">Folio fiscal:*</label>
                    <div class="row">
                      <div class="col-lg-12 input-group">
                        <input class="form-control numeric-only" type="text" name="txtFolioFiscalCant" id="txtFolioFiscalCant" required maxlength="5" placeholder="Folio fiscal" onchange="validEmptyInput('txtFolioFiscalCant', 'invalid-folioFiscalCant', 'La nota debe tener un folio fiscal.')">
                        <div class="invalid-feedback" id="invalid-folioFiscalCant">La nota debe tener un folio fiscal.</div>
                      </div>
                    </div>
                  </div>
                  <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <div class="providers-disabled">
                      <label for="usr">Documento relacionado*:</label>
                      <div class="row">
                        <div class="col-lg-12 input-group">
                          <select name="cmbCuentaPagarCant" id="cmbCuentaPagarCant" required disabled>
                              
                          </select>
                          <div class="invalid-feedback" id="invalid-cuentaPagarCant">La nota debe de tener una cuenta por pagar.</div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>`;

      html2 = `<div class="form-group"> 
                <div class="row">
                  <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-6">
                    <div class="providers-disabled">
                      <label for="usr">Serie*:</label>
                      <div class="input-group">
                        <input required class="form-control alphaNumeric-only" type="text" name="txtSerieCant" id="txtSerieCant" placeholder="Serie" style="float:left;" onchange="validEmptyInput('txtSerieCant', 'invalid-serieCant', 'La nota debe de tener número de serie.')">
                        <div class="invalid-feedback" id="invalid-serieCant">La nota debe de tener número de serie.</div>
                      </div>
                    </div>
                  </div>
                  <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-6">
                    <div class="providers-disabled">
                      <label for="usr">Folio*:</label>
                      <div class="input-group">
                        <input required class="form-control alphaNumeric-only" type="text" name="txtFolioCant" id="txtFolioCant" placeholder="Folio" style="float:left;" onchange="validEmptyInput('txtFolioCant', 'invalid-folioCant', 'La nota debe de tener un número de folio.')">
                        <div class="invalid-feedback" id="invalid-folioCant">La nota debe de tener número de folio.</div>
                      </div>
                    </div>
                  </div>
                  <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-6">
                    <div class="providers-disabled">
                      <label for="usr">Importe*:</label>
                      <div class="input-group">
                        <input required class="form-control numericDecimal-only" type="number" name="txtImporteCant" id="txtImporteCant" placeholder="Ej. 1000.00" style="float:left;" onchange="validEmptyInput('txtImporteCant', 'invalid-importeCant', 'La entrada debe de tener importe.')">
                        <div class="invalid-feedback" id="invalid-importeCant">La entrada debe de tener importe.</div>
                      </div>
                    </div>
                  </div>
                  <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-6">
                    <div class="providers-disabled">
                      <label for="usr">Subtotal*:</label>
                      <div class="input-group">
                        <input required class="form-control numericDecimal-only" type="number" name="txtSubtotalCant" id="txtSubtotalCant" placeholder="Ej. 1000.00" style="float:left;" onchange="validEmptyInput('txtSubtotalCant', 'invalid-subtotalCant', 'La nota debe de tener subtotal.')">
                        <div class="invalid-feedback" id="invalid-subtotalCant">La nota debe de tener subtotal.</div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <div class="row">
                  <div class="col-xl-2 col-lg-2 col-md-2 col-sm-3 col-xs-3">
                    <div class="providers-disabled">
                      <label for="usr">IVA (Monto):</label>
                      <div class="input-group">
                        <input class="form-control numericDecimal-only" type="number" name="txtIvaCant" id="txtIvaCant" placeholder="Ej. 1000.00" style="float:left;">
                      </div>
                    </div>
                  </div>
                  <div class="col-xl-2 col-lg-2 col-md-2 col-sm-3 col-xs-3">
                    <div class="providers-disabled">
                      <label for="usr">IEPS (Monto):</label>
                      <div class="input-group">
                        <input class="form-control numericDecimal-only" type="number" name="txtIepsCant" id="txtIepsCant" placeholder="Ej. 1000.00" style="float:left;">
                      </div>
                    </div>
                  </div>
                  <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-xs-6">
                    <div class="providers-disabled">
                      <label for="usr">Fecha de nota*:</label>
                      <div class="input-group">
                        <input required class="form-control" type="date" name="txtFechaNotaCant" id="txtFechaNotaCant" style="float:left;" onchange="validEmptyInput('txtFechaNotaCant', 'invalid-fechaNotaCant', 'La nota debe de tener una fecha.')">
                        <div class="invalid-feedback" id="invalid-fechaNotaCant">La nota debe de una fecha.</div>
                      </div>
                    </div>
                  </div>
                  <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-xs-6">
                    <div class="providers-disabled">
                      <label for="usr">Tipo*:</label>
                      <div class="row">
                        <div class="col-lg-12 input-group">
                          <select name="cmbTipoNotaCant" id="cmbTipoNotaCant" required disabled>
                              
                          </select>
                          <div class="invalid-feedback" id="invalid-tipoNotaCant">La nota debe de tener un tipo.</div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <br><br>
              <div class="form-group">
                <div class="row">
                  <div class="col-lg-6">
                    <i data-toggle=\"modal\" data-target=\"#agregar_Producto\"><img src=\"../../../../img/timdesk/ICONO AGREGAR2_Mesa de trabajo 1.svg\" width="30px" id="addProducto"></i>
                    <label>  Añadir producto</label>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <div class="card mb-4">
                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table" id="tblListadoNotaCreditoCantidad" width="100%" cellspacing="0">
                        <thead>
                          <tr>
                            <th>Id</th>
                            <th>Clave Interna</th>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Costo entrada</th>
                            <th>Total</th>
                            <th>Acciones</th>
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
                    </div>
                  </div>
                </div>
              </div>`;

      html3 = `<a class="btn-custom btn-custom--blue float-right" id="btnEditarNotaCreditoCant">Guardar cambios</a>
              <a class="btn-custom btn-custom--blue float-right" style="margin-right:25px" data-toggle="modal" data-target="#eliminar_NotaCredito" id="btnEliminarNotaCredito">Eliminar Nota Crédito</a>`;

     /*  html4 = `<div class="form-group">
                  <div class="row">
                  <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6">
                    <label for="usr">Cargar datos desde XML:*</label>
                    <div class="d-flex justify-content-center">
                      <div class="btnesp espAgregar">
                        <input type="file" id="inptFileXMLCant" name="inptFileXMLCant" accept="text/xml" onchange="changefileXML('inptFileXMLCant');">
                      </div>
                    </div>
                  </div>
                  <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6">
                    <label for="usr">Subir PDF:</label>
                    <div class="d-flex justify-content-center">
                      <div class="btnesp espAgregar">
                        <input type="file" id="inptFilePDFCant" name="inptFilePDFCant" accept="application/pdf">
                      </div>
                    </div>
                  </div>
                </div>
              </div>`; */

      $("#diseno3").html(html1);
      $("#disenoDevolucion2").html(html2);
      $("#addBoton").html(html3);
      //$("#inputsFiles").html(html4);

      $("#txtFolioFiscalCant").val(data[0].folioFiscal);
      cargarCMBCuentaPagarCant(data[0].cuentaPorPagar, "cmbCuentaPagarCant",data[0].devolucion);
      $("#txtSerieCant").val(data[0].serie);
      $("#txtFolioCant").val(data[0].folio);
      $("#txtImporteCant").val(data[0].importe);
      $("#txtSubtotalCant").val(data[0].subtotal);
      $("#txtIvaCant").val(data[0].iva);
      $("#txtIepsCant").val(data[0].ieps);
      $("#txtFechaNotaCant").val(data[0].fecha);
      cargarCMBTipoNota(data[0].tipo, "cmbTipoNotaCant");
      _global.rutaPDF = data[0].pdf

      deleteInsertNCCantidadProductosTemp(data[0].devolucion);
      cargarTablaNotaCreditoCantidadProductos(data[0].devolucion);

      setTimeout(function(){
        new SlimSelect({
          select: "#cmbProveedorCant",
          deselectLabel: '<span class="">✖</span>',
        });
        new SlimSelect({
          select: "#cmbDevolucionesCant",
          deselectLabel: '<span class="">✖</span>',
        });
        new SlimSelect({
          select: "#cmbTipoNotaCant",
          deselectLabel: '<span class="">✖</span>',
        });
        new SlimSelect({
          select: "#cmbCuentaPagarCant",
          deselectLabel: '<span class="">✖</span>',
        });
      },100);
    },
  });
}

function descargarPDFNC(){
  if(_global.rutaPDF != ''){
    var link=document.createElement('a');
    link.href = _global.rutaServer+_global.rutaPDF;
    link.download = _global.rutaPDF.substr(_global.rutaPDF.lastIndexOf('/') + 1);
    link.click();
  }else{
    Lobibox.notify("success", {
      size: "mini",
      rounded: true,
      delay: 3000,
      delayIndicator: false,
      position: "center top",
      icon: true,
      img: "../../../../img/timdesk/checkmark.svg",
      msg: "¡No se ha cargado un PDF!",
      sound: '../../../../../sounds/sound4'
    });
  }
  
}

function cargarCMBTipoGral(data, input) {
  var html = "";
  html += '<option data-placeholder="true"></option> ';
  if(data == 1){
    html += `<option value="1" id="cargo" selected>
              Monto
            </option>
            <option value="2" id="credito">
              Cantidad
            </option>`;
    mostrarTipoGral1();
  }else{
    html += `<option value="1" id="cargo">
              Monto
            </option>
            <option value="2" id="credito" selected>
              Cantidad
            </option>`;
    mostrarTipoGral2();
  }

  $(`#${input}`).html(html);
}

function cargarCMBTipoNota(data, input) {
  var html = "";
  
  html += '<option data-placeholder="true"></option> ';

  if(data === 1){
    html += `<option value="1" id="cargo" selected>
                Cargo
              </option>
              <option value="2" id="credito">
                Crédito
              </option>`;
  }else if(data === 2){
    html += `<option value="1" id="cargo">
                Cargo
             </option>
             <option value="2" id="credito" selected>
                Crédito
             </option>`;
  }else{
    html += `<option value="1" id="cargo">
                Cargo
             </option>
             <option value="2" id="credito">
                Crédito
             </option>`;
  }

  $(`#${input}`).html(html);
}

function cargarCMBCuentaPagar(data, input) {
    var html = "";
    var selected;
    $.ajax({
      url: "../../php/funciones.php",
      data: { clase: "get_data", funcion: "get_cmb_notaCredito_cuentaPagar" },
      dataType: "json",
      success: function (respuesta) {
        html += '<option data-placeholder="true"></option>';
  
        $.each(respuesta, function (i) {
          if (data === respuesta[i].id) {
            selected = "selected";
          } else {
            selected = "";
          }
          html += `<option value="${respuesta[i].id}" ${selected}> 
                    ${respuesta[i].folioSerie}
                  </option>`;
        });
        $(`#${input}`).html(html);
      },
      error: function (error) {
        console.log(error);
      },
    });
}

function cargarCMBCuentaPagarCant(data, input, devolucion) {
  var html = "";
  var selected;
  $.ajax({
    url: "../../php/funciones.php",
    data: { 
            clase: "get_data", 
            funcion: "get_cmb_notaCreditoCant_cuentaPagar",
            data: devolucion
          },
    dataType: "json",
    success: function (respuesta) {

      $.each(respuesta, function (i) {
        if (data === respuesta[i].id) {
          selected = "selected";
        } else {
          selected = "";
        }
        html += `<option value="${respuesta[i].id}" ${selected}> 
                  ${respuesta[i].folioSerie}
                </option>`;
      });
      $(`#${input}`).html(html);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function cargarCMBProveedor(data, input) {
  var html = "";
  var selected;
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_notaCredito_proveedor" },
    dataType: "json",
    success: function (respuesta) {
      html += '<option data-placeholder="true"></option>';

      $.each(respuesta, function (i) {
        if (data === respuesta[i].id) {
          selected = "selected";
        } else {
          selected = "";
        }
        html += `<option value="${respuesta[i].id}" ${selected}> 
                  ${respuesta[i].proveedor}
                </option>`;
      });
      $(`#${input}`).html(html);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function cargarCMBProveedorDevo(data, input) {
  var html = "";
  var selected;
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_notaCredito_proveedorDevolucion" },
    dataType: "json",
    success: function (respuesta) {
      html += '<option data-placeholder="true"></option>';

      $.each(respuesta, function (i) {
        if (data === respuesta[i].id) {
          selected = "selected";
        } else {
          selected = "";
        }
        html += `<option value="${respuesta[i].id}" ${selected}> 
                  ${respuesta[i].proveedor}
                </option>`;
      });
      $(`#${input}`).html(html);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function cargarCMBDevolucion(data, input, pkProveedor) {
  var html = "";
  var selected;
  $.ajax({
    url: "../../php/funciones.php",
    data: { 
      clase: "get_data", 
      funcion: "get_cmb_notaCredito_devolucion",
      data:  pkProveedor
    },
    dataType: "json",
    success: function (respuesta) {
      html += '<option data-placeholder="true"></option>';
      $.each(respuesta, function (i) {
        if (data === respuesta[i].id) {
          selected = "selected";
        } else {
          selected = "";
        }
        html += `<option value="${respuesta[i].id}" ${selected}> 
                  ${respuesta[i].devolucion}
                </option>`;
      });
      $(`#${input}`).html(html);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function cargarCMBDevolucionCant(data, input, pkProveedor) {
  var html = "";
  var selected;
  $.ajax({
    url: "../../php/funciones.php",
    data: { 
      clase: "get_data", 
      funcion: "get_cmb_notaCredito_devolucionCant",
      data:  pkProveedor
    },
    dataType: "json",
    success: function (respuesta) {
      html += '<option data-placeholder="true"></option>';
      $.each(respuesta, function (i) {
        if (data === respuesta[i].id) {
          selected = "selected";
        } else {
          selected = "";
        }
        html += `<option value="${respuesta[i].id}" ${selected}> 
                  ${respuesta[i].devolucion}
                </option>`;
      });
      $(`#${input}`).html(html);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function changefileXML(input){
  var file = $('#'+input)[0].files[0];

  if($('#'+input).val() !== ""){
      var file = $('#'+input).prop('files')[0];
      var cadena = new FormData();
      cadena.append("file",file);
      $.ajax({
          url:"../../php/guardar_xml.php",
          data:cadena,
          cache: false,
          contentType: false,
          processData: false,
          type: 'POST',
          dataType: "json",
          success:function(response){
              if (response.Valido != 'Si'){
                Lobibox.notify("error", {
                  size: "mini",
                  rounded: true,
                  delay: 3000,
                  delayIndicator: false,
                  position: "center top",
                  icon: true,
                  img: "../../../../img/timdesk/notificacion_error.svg",
                  msg: "¡El archivo "+response.TipoArchivo +" no es un XML!",
                  sound: '../../../../../sounds/sound4'
                });
              }else if (response.TipoComprobante[0] != 'E' && response.TipoComprobante[0] != 'I'){
                Lobibox.notify("error", {
                  size: "mini",
                  rounded: true,
                  delay: 3000,
                  delayIndicator: false,
                  position: "center top",
                  icon: true,
                  img: "../../../../img/timdesk/notificacion_error.svg",
                  msg: "¡El archivo no corresponde a una nota de crédito!",
                  sound: '../../../../../sounds/sound4'
                });

                deleteXMLNoNotaCredito(response.NameFile);
              }else if (response.IdProveedor == 0){
                Lobibox.notify("error", {
                  size: "mini",
                  rounded: true,
                  delay: 3000,
                  delayIndicator: false,
                  position: "center top",
                  icon: true,
                  img: "../../../../img/timdesk/notificacion_error.svg",
                  msg: "¡Los datos no pertenecen al sistema!",
                  sound: '../../../../../sounds/sound4'
                });

                deleteXMLNoNotaCredito(response.NameFile);
              }else{

                /* if(input == 'inptFileXML'){
                  $("#txtSerie").val(response.Serie[0]);
                  $("#txtFolio").val(response.Folio[0]);
                  $("#txtImporte").val(response.Importe[0]);
                  $("#txtSubtotal").val(response.Subtotal[0]);
                  $("#txtIva").val(response.Iva);
                  $("#txtIeps").val(response.Ieps);
                  $("#txtFechaNota").val(response.Fecha[0].substring(0,10));
                  if (response.TipoComprobante[0] == 'E'){
                    cargarCMBTipoNota(2,"cmbTipoNota");
                  }else if (response.TipoComprobante[0] == 'I'){
                    cargarCMBTipoNota(1,"cmbTipoNota");
                  }
                  cargarCMBProveedor(response.IdProveedor[0][0], "cmbProveedor");
                  $("#txtFolioFiscal").val(response.FolioFiscal[0]);

                  if (!$("#txtSerie").val()) {
                    $("#invalid-serie").css("display", "block");
                    $("#txtSerie").addClass("is-invalid");
                  }else{
                    $("#invalid-serie").css("display", "none");
                    $("#txtSerie").removeClass("is-invalid");
                  }
              
                  if (!$("#txtFolio").val()) {
                    $("#invalid-folio").css("display", "block");
                    $("#txtFolio").addClass("is-invalid");
                  }else{
                    $("#invalid-folio").css("display", "none");
                    $("#txtFolio").removeClass("is-invalid");
                  }
              
                  if (!$("#txtImporte").val()) {
                    $("#invalid-importe").css("display", "block");
                    $("#txtImporte").addClass("is-invalid");
                  }else{
                    $("#invalid-importe").css("display", "none");
                    $("#txtImporte").removeClass("is-invalid");
                  }
              
                  if (!$("#txtSubtotal").val()) {
                    $("#invalid-subtotal").css("display", "block");
                    $("#txtSubtotal").addClass("is-invalid");
                  }else{
                    $("#invalid-subtotal").css("display", "none");
                    $("#txtSubtotal").removeClass("is-invalid");
                  }
              
                  if (!$("#txtFechaNota").val()) {
                    $("#invalid-fechaNota").css("display", "block");
                    $("#txtFechaNota").addClass("is-invalid");
                  }else{
                    $("#invalid-fechaNota").css("display", "none");
                    $("#txtFechaNota").removeClass("is-invalid");
                  }
              
                  if (!$("#cmbTipoNota").val()) {
                    $("#invalid-tipoNota").css("display", "block");
                    $("#cmbTipoNota").addClass("is-invalid");
                  }else{
                    $("#invalid-tipoNota").css("display", "none");
                    $("#cmbTipoNota").removeClass("is-invalid");
                  }
              
                  if (!$("#txtFolioFiscal").val()) {
                    $("#invalid-folioFiscal").css("display", "block");
                    $("#txtFolioFiscal").addClass("is-invalid");
                  }else{
                    $("#invalid-folioFiscal").css("display", "none");
                    $("#txtFolioFiscal").removeClass("is-invalid");
                  }
              
                  
                }else if(input == 'inptFileXMLCant'){
                  $("#txtSerieCant").val(response.Serie[0]);
                  $("#txtFolioCant").val(response.Folio[0]);
                  $("#txtImporteCant").val(response.Importe[0]);
                  $("#txtSubtotalCant").val(response.Subtotal[0]);
                  $("#txtIvaCant").val(response.Iva);
                  $("#txtIepsCant").val(response.Ieps);
                  $("#txtFechaNotaCant").val(response.Fecha[0].substring(0,10));
                  if (response.TipoComprobante[0] == 'E'){
                    cargarCMBTipoNota(2,"cmbTipoNotaCant");
                  }else if (response.TipoComprobante[0] == 'I'){
                    cargarCMBTipoNota(1,"cmbTipoNotaCant");
                  }
                  cargarCMBProveedor(response.IdProveedor[0][0], "cmbProveedorCant");
                  $("#txtFolioFiscalCant").val(response.FolioFiscal[0]);

                  if (!$("#txtSerieCant").val()) {
                    $("#invalid-serieCant").css("display", "block");
                    $("#txtSerieCant").addClass("is-invalid");
                  }else{
                    $("#invalid-serieCant").css("display", "none");
                    $("#txtSerieCant").removeClass("is-invalid");
                  }
              
                  if (!$("#txtFolioCant").val()) {
                    $("#invalid-folioCant").css("display", "block");
                    $("#txtFolioCant").addClass("is-invalid");
                  }else{
                    $("#invalid-folioCant").css("display", "none");
                    $("#txtFolioCant").removeClass("is-invalid");
                  }
              
                  if (!$("#txtImporteCant").val()) {
                    $("#invalid-importeCant").css("display", "block");
                    $("#txtImporteCant").addClass("is-invalid");
                  }else{
                    $("#invalid-importeCant").css("display", "none");
                    $("#txtImporteCant").removeClass("is-invalid");
                  }
              
                  if (!$("#txtSubtotalCant").val()) {
                    $("#invalid-subtotalCant").css("display", "block");
                    $("#txtSubtotalCant").addClass("is-invalid");
                  }else{
                    $("#invalid-subtotalCant").css("display", "none");
                    $("#txtSubtotalCant").removeClass("is-invalid");
                  }
              
                  if (!$("#txtFechaNotaCant").val()) {
                    $("#invalid-fechaNotaCant").css("display", "block");
                    $("#txtFechaNotaCant").addClass("is-invalid");
                  }else{
                    $("#invalid-fechaNotaCant").css("display", "none");
                    $("#txtFechaNotaCant").removeClass("is-invalid");
                  }
              
                  if (!$("#cmbTipoNotaCant").val()) {
                    $("#invalid-tipoNotaCant").css("display", "block");
                    $("#cmbTipoNotaCant").addClass("is-invalid");
                  }else{
                    $("#invalid-tipoNotaCant").css("display", "none");
                    $("#cmbTipoNotaCant").removeClass("is-invalid");
                  }
              
                  if (!$("#cmbProveedorCant").val()) {
                    $("#invalid-proveedorCant").css("display", "block");
                    $("#cmbProveedorCant").addClass("is-invalid");
                  }else{
                    $("#invalid-proveedorCant").css("display", "none");
                    $("#cmbProveedorCant").removeClass("is-invalid");
                  }
              
                  if (!$("#cmbDevolucionesCant").val()) {
                    $("#invalid-devolucionesCant").css("display", "block");
                    $("#cmbDevolucionesCant").addClass("is-invalid");
                  }else{
                    $("#invalid-devolucionesCant").css("display", "none");
                    $("#cmbDevolucionesCant").removeClass("is-invalid");
                  }
              
                  if (!$("#txtFolioFiscalCant").val()) {
                    $("#invalid-folioFiscalCant").css("display", "block");
                    $("#txtFolioFiscalCant").addClass("is-invalid");
                  }else{
                    $("#invalid-folioFiscalCant").css("display", "none");
                    $("#txtFolioFiscalCant").removeClass("is-invalid");
                  }
              
                  if (!$("#cmbCuentaPagarCant").val()) {
                    $("#invalid-cuentaPagarCant").css("display", "block");
                    $("#cmbCuentaPagarCant").addClass("is-invalid");
                  }else{
                    $("#invalid-cuentaPagarCant").css("display", "none");
                    $("#cmbCuentaPagarCant").removeClass("is-invalid");
                  }
                } */

                _global.nameFileXML = response.NameFile;

                Lobibox.notify("success", {
                  size: "mini",
                  rounded: true,
                  delay: 3000,
                  delayIndicator: false,
                  position: "center top",
                  icon: true,
                  img: "../../../../img/timdesk/checkmark.svg",
                  msg: "¡Datos cargados correctamente!",
                  sound: '../../../../../sounds/sound4'
                });
              }

              /*console.log(response);
              console.log(
                response.RFCProveedor[0]+'\n'+
                response.NombreProveedor[0]+'\n'+
                response.Ruta+'\n'+
                response.NameFile+'\n'+
                +response.IdProveedor[0][0]
              );*/
          }
      });
  }
}

function deleteXMLNoNotaCredito(nameFile){
  $.ajax({
    url: "deleteFile.php",
    type: "POST",
    data: { url: nameFile},
    success: function (data) {
    },
  });
}

$(document).on("click", "#btnEditarNotaCredito", function () {
  if ($("#formDatosNotaCredito")[0].checkValidity()) {
    var badSerie =
      $("#invalid-serie").css("display") === "block" ? false : true;
    var badFolio =
      $("#invalid-folio").css("display") === "block" ? false : true;
    var badImporte =
      $("#invalid-importe").css("display") === "block" ? false : true;
    var badSubtotal =
      $("#invalid-subtotal").css("display") === "block" ? false : true;
    var badFechaNota =
      $("#invalid-fechaNota").css("display") === "block" ? false : true;
    var badTipoNota =
      $("#invalid-tipoNota").css("display") === "block" ? false : true;
    var badProveedor =
      $("#invalid-proveedor").css("display") === "block" ? false : true;
    var badFolioFiscal =
      $("#invalid-folioFiscal").css("display") === "block" ? false : true;
    var badCuentaPagar =
      $("#invalid-cuentaPagar").css("display") === "block" ? false : true;

    if (
      badSerie &&
      badFolio &&
      badImporte &&
      badSubtotal &&
      badFechaNota &&
      badTipoNota &&
      badProveedor &&
      badFolioFiscal &&
      badCuentaPagar
    ) {

      var datos = {
        tipoGral: $("#cmbTipoGral").val(),
        serie: $("#txtSerie").val(),
        folio: $("#txtFolio").val(),
        importe: $("#txtImporte").val(),
        subtotal: $("#txtSubtotal").val(),
        iva: $("#txtIva").val() != '' ? $("#txtIva").val() : 0,
        ieps: $("#txtIeps").val() != '' ? $("#txtIeps").val() : 0,
        fechaNota: $("#txtFechaNota").val(),
        tipoNota: $("#cmbTipoNota").val(),
        archivoPDF: 0,//$("#inptFilePDF").val() ? 1 : 0,
        archivoXML: 0,//$("#inptFileXML").val() ? 1 : 0,
        proveedor: $("#cmbProveedor").val(),
        folioFiscal: $("#txtFolioFiscal").val(),
        cuentaPagar: $("#cmbCuentaPagar").val(),
        pkNotaCredito: _global.PKNotaCredito
      };

      $.ajax({
        url: "../../php/funciones.php",
        data: { clase: "save_data", funcion: "save_datosNotaCreditoMonto", datos },
        dataType: "json",
        success: function (respuesta) {
          if (respuesta[0].status) {
            
            Lobibox.notify("success", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../../img/timdesk/checkmark.svg",
              msg: "Servicio registrado correctamente!",
              sound: '../../../../../sounds/sound4'
            });
            
            subirArchivoPDF(respuesta[0].id);
            subirArchivoXML(respuesta[0].id);

            setTimeout(function(){
              window.location.href = '../notas_credito';
            },500);
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
    }
  }else {
    if (!$("#txtSerie").val()) {
      $("#invalid-serie").css("display", "block");
      $("#txtSerie").addClass("is-invalid");
    }else{
      $("#invalid-serie").css("display", "none");
      $("#txtSerie").removeClass("is-invalid");
    }

    if (!$("#txtFolio").val()) {
      $("#invalid-folio").css("display", "block");
      $("#txtFolio").addClass("is-invalid");
    }else{
      $("#invalid-folio").css("display", "none");
      $("#txtFolio").removeClass("is-invalid");
    }

    if (!$("#txtImporte").val()) {
      $("#invalid-importe").css("display", "block");
      $("#txtImporte").addClass("is-invalid");
    }else{
      $("#invalid-importe").css("display", "none");
      $("#txtImporte").removeClass("is-invalid");
    }

    if (!$("#txtSubtotal").val()) {
      $("#invalid-subtotal").css("display", "block");
      $("#txtSubtotal").addClass("is-invalid");
    }else{
      $("#invalid-subtotal").css("display", "none");
      $("#txtSubtotal").removeClass("is-invalid");
    }

    if (!$("#txtFechaNota").val()) {
      $("#invalid-fechaNota").css("display", "block");
      $("#txtFechaNota").addClass("is-invalid");
    }else{
      $("#invalid-fechaNota").css("display", "none");
      $("#txtFechaNota").removeClass("is-invalid");
    }

    if (!$("#cmbTipoNota").val()) {
      $("#invalid-tipoNota").css("display", "block");
      $("#cmbTipoNota").addClass("is-invalid");
    }else{
      $("#invalid-tipoNota").css("display", "none");
      $("#cmbTipoNota").removeClass("is-invalid");
    }

    if (!$("#cmbProveedor").val()) {
      $("#invalid-proveedor").css("display", "block");
      $("#cmbProveedor").addClass("is-invalid");
    }else{
      $("#invalid-proveedor").css("display", "none");
      $("#cmbProveedor").removeClass("is-invalid");
    }

    if (!$("#txtFolioFiscal").val()) {
      $("#invalid-folioFiscal").css("display", "block");
      $("#txtFolioFiscal").addClass("is-invalid");
    }else{
      $("#invalid-folioFiscal").css("display", "none");
      $("#txtFolioFiscal").removeClass("is-invalid");
    }

    if (!$("#cmbCuentaPagar").val()) {
      $("#invalid-cuentaPagar").css("display", "block");
      $("#cmbCuentaPagar").addClass("is-invalid");
    }else{
      $("#invalid-cuentaPagar").css("display", "none");
      $("#cmbCuentaPagar").removeClass("is-invalid");
    }
  }

  /*subirArchivoPDF(1);
  subirArchivoXML(1);*/
});

$(document).on("click", "#btnEditarNotaCreditoCant", function () {
  var table = $("#tblListadoNotaCreditoCantidad").DataTable();
  var invalidDivs = document.querySelectorAll(".invalid-feedback");
  var isSomethingInvalid = false;
  invalidDivs.forEach((invalidDiv) => {
    if (invalidDiv.style.display === "block") {
      isSomethingInvalid = true;
      return;
    } else {
      isSomethingInvalid = false;
    }
  });
  if (!isSomethingInvalid) {
    if (table.data().count()) {

      if ($("#formDatosNotaCredito")[0].checkValidity()) {
        var badSerie =
          $("#invalid-serieCant").css("display") === "block" ? false : true;
        var badFolio =
          $("#invalid-folioCant").css("display") === "block" ? false : true;
        var badImporte =
          $("#invalid-importeCant").css("display") === "block" ? false : true;
        var badSubtotal =
          $("#invalid-subtotalCant").css("display") === "block" ? false : true;
        var badFechaNota =
          $("#invalid-fechaNotaCant").css("display") === "block" ? false : true;
        var badTipoNota =
          $("#invalid-tipoNotaCant").css("display") === "block" ? false : true;
        var badProveedor =
          $("#invalid-proveedorCant").css("display") === "block" ? false : true;
        var badDevolucion =
          $("#invalid-devolucionesCant").css("display") === "block" ? false : true;
        var badFolioFiscal =
          $("#invalid-folioFiscalCant").css("display") === "block" ? false : true;
        var badCuentaPagar =
          $("#invalid-cuentaPagarCant").css("display") === "block" ? false : true;

        if (
          badSerie &&
          badFolio &&
          badImporte &&
          badSubtotal &&
          badFechaNota &&
          badTipoNota &&
          badProveedor &&
          badDevolucion &&
          badFolioFiscal &&
          badCuentaPagar
        ) {

          var datos = {
            tipoGral: $("#cmbTipoGral").val(),
            serie: $("#txtSerieCant").val(),
            folio: $("#txtFolioCant").val(),
            importe: $("#txtImporteCant").val(),
            subtotal: $("#txtSubtotalCant").val(),
            iva: $("#txtIvaCant").val(),
            ieps: $("#txtIepsCant").val(),
            fechaNota: $("#txtFechaNotaCant").val(),
            tipoNota: $("#cmbTipoNotaCant").val(),
            archivoPDF: 0, //$("#inptFilePDFCant").val() ? 1 : 0,
            archivoXML: 0, //$("#inptFileXMLCant").val() ? 1 : 0,
            proveedor: $("#cmbProveedorCant").val(),
            devolucion: $("#cmbDevolucionesCant").val(),
            folioFiscal: $("#txtFolioFiscalCant").val(),
            cuentaPagar: $("#cmbCuentaPagarCant").val(),
            pkNotaCredito: _global.PKNotaCredito
          };

          $.ajax({
            url: "../../php/funciones.php",
            data: { clase: "save_data", funcion: "save_datosNotaCreditoCantidad", datos },
            dataType: "json",
            success: function (respuesta) {
              if (respuesta[0].status) {
                
                Lobibox.notify("success", {
                  size: "mini",
                  rounded: true,
                  delay: 3000,
                  delayIndicator: false,
                  position: "center top",
                  icon: true,
                  img: "../../../../img/timdesk/checkmark.svg",
                  msg: "Servicio registrado correctamente!",
                  sound: '../../../../../sounds/sound4'
                });
                
                subirArchivoPDFCant(respuesta[0].id);
                subirArchivoXML(respuesta[0].id);

                setTimeout(function(){
                  window.location.href = '../notas_credito';
                },500);
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
        }
      }else {
        if (!$("#txtSerieCant").val()) {
          $("#invalid-serieCant").css("display", "block");
          $("#txtSerieCant").addClass("is-invalid");
        }else{
          $("#invalid-serieCant").css("display", "none");
          $("#txtSerieCant").removeClass("is-invalid");
        }

        if (!$("#txtFolioCant").val()) {
          $("#invalid-folioCant").css("display", "block");
          $("#txtFolioCant").addClass("is-invalid");
        }else{
          $("#invalid-folioCant").css("display", "none");
          $("#txtFolioCant").removeClass("is-invalid");
        }

        if (!$("#txtImporteCant").val()) {
          $("#invalid-importeCant").css("display", "block");
          $("#txtImporteCant").addClass("is-invalid");
        }else{
          $("#invalid-importeCant").css("display", "none");
          $("#txtImporteCant").removeClass("is-invalid");
        }

        if (!$("#txtSubtotalCant").val()) {
          $("#invalid-subtotalCant").css("display", "block");
          $("#txtSubtotalCant").addClass("is-invalid");
        }else{
          $("#invalid-subtotalCant").css("display", "none");
          $("#txtSubtotalCant").removeClass("is-invalid");
        }

        if (!$("#txtFechaNotaCant").val()) {
          $("#invalid-fechaNotaCant").css("display", "block");
          $("#txtFechaNotaCant").addClass("is-invalid");
        }else{
          $("#invalid-fechaNotaCant").css("display", "none");
          $("#txtFechaNotaCant").removeClass("is-invalid");
        }

        if (!$("#cmbTipoNotaCant").val()) {
          $("#invalid-tipoNotaCant").css("display", "block");
          $("#cmbTipoNotaCant").addClass("is-invalid");
        }else{
          $("#invalid-tipoNotaCant").css("display", "none");
          $("#cmbTipoNotaCant").removeClass("is-invalid");
        }

        if (!$("#cmbProveedorCant").val()) {
          $("#invalid-proveedorCant").css("display", "block");
          $("#cmbProveedorCant").addClass("is-invalid");
        }else{
          $("#invalid-proveedorCant").css("display", "none");
          $("#cmbProveedorCant").removeClass("is-invalid");
        }

        if (!$("#cmbDevolucionesCant").val()) {
          $("#invalid-devolucionesCant").css("display", "block");
          $("#cmbDevolucionesCant").addClass("is-invalid");
        }else{
          $("#invalid-devolucionesCant").css("display", "none");
          $("#cmbDevolucionesCant").removeClass("is-invalid");
        }

        if (!$("#txtFolioFiscalCant").val()) {
          $("#invalid-folioFiscalCant").css("display", "block");
          $("#txtFolioFiscalCant").addClass("is-invalid");
        }else{
          $("#invalid-folioFiscalCant").css("display", "none");
          $("#txtFolioFiscalCant").removeClass("is-invalid");
        }

        if (!$("#cmbCuentaPagarCant").val()) {
          $("#invalid-cuentaPagarCant").css("display", "block");
          $("#cmbCuentaPagarCant").addClass("is-invalid");
        }else{
          $("#invalid-cuentaPagarCant").css("display", "none");
          $("#cmbCuentaPagarCant").removeClass("is-invalid");
        }
      }
    }else {
      Lobibox.notify("error", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../../../img/timdesk/notificacion_error.svg",
        msg: "¡No hay productos agregados!",
        sound: '../../../../../sounds/sound4'
      });
    }
  }

  /*subirArchivoPDFCant(1);
  subirArchivoXMLCant(1);*/
});


function subirArchivoPDF(idNotaCredito){

  //var file = $('#inptFilePDF')[0].files[0];

  /* if($('#inptFilePDF').val() !== ""){
      var file = $('#inptFilePDF').prop('files')[0];
      var dataFile = new FormData();
      dataFile.append("file",file);

      $.ajax({
        url: 'uploadFilePDF.php',
        data: dataFile,
        cache: false,
        contentType: false,
        processData: false,
        type: 'POST',
        success: function(respuesta){
          renameFilePDF(respuesta,idNotaCredito);
        }
      });
  } */
}

function subirArchivoPDFCant(idNotaCredito){

  //var file = $('#inptFilePDFCant')[0].files[0];

  /* if($('#inptFilePDFCant').val() !== ""){
      var file = $('#inptFilePDFCant').prop('files')[0];
      var dataFile = new FormData();
      dataFile.append("file",file);

      $.ajax({
        url: 'uploadFilePDF.php',
        data: dataFile,
        cache: false,
        contentType: false,
        processData: false,
        type: 'POST',
        success: function(respuesta){
          renameFilePDF(respuesta,idNotaCredito);
        }
      });
  } */
}

function renameFilePDF(name, idNotaCredito){
  $.ajax({
    url: "renameFilePDF.php",
    type: "POST",
    data: { url: name, id: idNotaCredito },
    success: function (data) {
    },
  });
}

function subirArchivoXML(idNotaCredito){
  $.ajax({
    url: "removeFileXML.php",
    type: "POST",
    data: { url:  _global.nameFileXML, id: idNotaCredito },
    success: function (data) {
    },
  });
}

function cargarTablaNotaCreditoCantidadProductos(pkDevolucion){

  $("#tblListadoNotaCreditoCantidad").dataTable({
    language: setFormatDatatables(),
    scrollX: true,
    info: false,
    ajax: {
      url: "../../php/funciones.php",
      data: { clase: "get_data", 
              funcion: "get_notaCredito_Productos_Cantidad_Table",
              data: pkDevolucion
            },
    },
    //"pageLength": 20,
    "paging": false,
    "order": [ 0, 'desc' ],
    columns: [
      { data: "Id" },
      { data: "Clave" },
      { data: "Producto" },
      { data: "Cantidad" },
      { data: "Costo" },
      { data: "Total" },
      { data: "Acciones" },
    ],
    columnDefs: [
      { orderable: false, targets: 0, visible: false },
    ],
  });
}

function cargarTablaProductosDevolucion(){
  var pkDevolucion = $("#cmbDevolucionesCant").val();

  $("#tblListadoProductosDevolucion").DataTable().destroy();
  $("#tblListadoProductosDevolucion").dataTable({
    language: setFormatDatatables(),
    scrollX: true,
    info: false,
    ajax: {
      url: "../../php/funciones.php",
      data: { clase: "get_data", 
              funcion: "get_productosDevolucionTable",
              data: pkDevolucion
            },
    },
    //"pageLength": 20,
    "paging": false,
    "order": [ 0, 'desc' ],
    columns: [
      { data: "Id" },
      { data: "Clave" },
      { data: "Producto" },
      { data: "Cantidad" },
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
      sFirst: "Primero",
      sLast: "Último",
      sNext: "<img src='../../../../img/icons/pagination.svg' width='20px'/>",
      sPrevious:
        "<img src='../../../../img/icons/pagination.svg' width='20px' style='transform: scaleX(-1)'/>",
    },
  };
  return idioma_espanol;
}

function deleteInsertNCCantidadProductosTemp(pkDevolucion){
  $.ajax({
    url: "../../php/funciones.php",
    data: { 
      clase: "delete_data", 
      funcion: "delete_InsertdatosNCCantidadAllTemp",
      data: pkDevolucion,
      data2: _global.PKNotaCredito
    },
    dataType: "json",
    success: function (respuesta) {
      if (respuesta[0].status) {
        $("#tblListadoNotaCreditoCantidad").DataTable().ajax.reload();
        obtenerTotal();
      } else {
        console.log("¡Algo salio mal :(!");
      }
    },
    error: function (error) {
      console.log("¡Algo salio mal :(! "+error);
    },
  });
}

function seleccionarProducto(id){
  _global.idDetalleDev = id;
  var pkDevolucion = $("#cmbDevolucionesCant").val();

  $.ajax({
    url: "../../php/funciones.php",
    data: { 
            clase: "save_data", 
            funcion: "save_datosNotaCreditoProductoCant", 
            data: _global.idDetalleDev,
            data2: pkDevolucion
          },
    dataType: "json",
    success: function (respuesta) {
      if (respuesta[0].status) {
        $("#tblListadoNotaCreditoCantidad").DataTable().ajax.reload();
        obtenerTotal();
        Lobibox.notify("success", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/checkmark.svg",
          msg: "Producto registrado correctamente!",
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

  $("#agregar_Producto").modal("hide");
}

function obtenerDatosEliminarNotaCreditoTemp(id){
  _global.idDetalleNCTemp = id;
}

function eliminarNotaCreditoProductoTemp(){
  $.ajax({
    url: "../../php/funciones.php",
    data: { 
      clase: "delete_data", 
      funcion: "delete_datosNotaCreditoProductoTemp", 
      datos:  _global.idDetalleNCTemp
    },
    dataType: "json",
    success: function (respuesta) {
      if (respuesta[0].status) {
        $("#tblListadoNotaCreditoCantidad").DataTable().ajax.reload();
        obtenerTotal();
        Lobibox.notify("success", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/checkmark.svg",
          msg: "Producto eliminado correctamente!",
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

  if(inputID.substr(0,8) == "txtCant-"){
    var cantidad = parseInt($("#" + inputID).val());
    var idDetalleNCTemp = inputID.substr(8);

    if (cantidad <= 0 ){
      $("#"+invalidDivID).css("display", "block");
      $("#"+invalidDivID).text( "La cantidad debe de ser mayor a 0");
      $("#"+inputID).addClass("is-invalid");
    }else{
      $.ajax({
        url: "../../php/funciones.php",
        data: {
          clase: "get_data",
          funcion: "validar_notaCredito_cantidadProd_devolucion",
          data: idDetalleNCTemp,
          data2: cantidad,
        },
        dataType: "json",
        success: function (data) {
          if (parseInt(data[0]["existe"]) == 1) {
            $("#"+invalidDivID).css("display", "block");
            $("#"+invalidDivID).text("La cantidad no puede ser mayor a la cantidad devuelta: "+data[0]["cantidadDev"]+".");
            $("#"+inputID).addClass("is-invalid");
          } else {
            $("#"+invalidDivID).css("display", "none");
            $("#"+invalidDivID).text("");
            $("#"+inputID).removeClass("is-invalid");
            updateCantidadNCTemp(cantidad, idDetalleNCTemp);
          }
        },
      });
    }
  }
}

function updateCantidadNCTemp(cantidad, idDetalleNCTemp){
  $.ajax({
    url: "../../php/funciones.php",
    data: { 
      clase: "edit_data", 
      funcion: "edit_cantidadNotaCreditoProductoTemp", 
      data: idDetalleNCTemp,
      data2: cantidad,
    },
    dataType: "json",
    success: function (respuesta) {
      if (respuesta[0].status) {
        $("#tblListadoNotaCreditoCantidad").DataTable().ajax.reload();
        obtenerTotal();
        Lobibox.notify("success", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/checkmark.svg",
          msg: "Cantidad actualizada correctamente!",
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
}

function obtenerTotal() {
  //Obtener subtotal
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_subTotalNotaCreditoCantTemp",
    },
    dataType: "json",
    success: function (respuesta) {
      $("#Subtotal").html(dosDecimales(respuesta[0].subtotal));
    },
    error: function (error) {
      console.log(error);
    },
  });

  var html = "";
  $("#impuestos").html(html);
  //Obtener impuestos
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_impuestoNotaCreditoCantTemp",
    },
    dataType: "json",
    success: function (respuesta) {
      //Recorrer las respuestas de la consulta
      $.each(respuesta, function (i) {
        var tasa = '';
        if (!$("#impuestos-head-" + respuesta[i].id).length) {
          if(respuesta[i].tasa == '' || respuesta[i].tasa == null){
            tasa = respuesta[i].tasa;
          }else{
            tasa = respuesta[i].tasa+'%';
          }
          html +=
            "<tr>" +
            /*'<th style="text-align: right;">'+respuesta[i].producto+'</th>'+*/
            '<td style="text-align: right;" id="impuestos-head-' +
            respuesta[i].id +
            '">' +
            respuesta[i].impuesto +
            "</td>" +
            '<td style="text-align: right;">' +
            tasa +
            " </td>" +
            '<td style="text-align: right;">.....</td>' +
            '<td style="text-align: right;"> $ ' +
            dosDecimales(respuesta[i].totalImpuesto) +
            "</td>" +
            "</tr>";
        }
      });

      $("#impuestos").html(html);
    },
    error: function (error) {
      console.log(error);
    },
  });

  //Obtener total
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_totalNotaCreditoCantTemp",
    },
    dataType: "json",
    success: function (respuesta) {
      $("#Total").html(dosDecimales(respuesta[0].Total));
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function dosDecimales(n) {
  return Number.parseFloat(n)
    .toFixed(2)
    .replace(/\d(?=(\d{3})+\.)/g, "$&,");
}