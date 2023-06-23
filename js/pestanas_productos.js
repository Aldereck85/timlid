var _permissionsCat = {
  read: 0,
  add: 0,
  edit: 0,
  delete: 0,
  export: 0,
};

var _permissionsMar = {
  read: 0,
  add: 0,
  edit: 0,
  delete: 0,
  export: 0,
};

var _permissions = {
  read: 0,
  add: 0,
  edit: 0,
  delete: 0,
  export: 0,
};

/*----------------------Diseño datos del producto-------------------------------*/

//Cargar pestaña de Datos del producto
function CargarDatosProducto() {
  validate_PermissionsCat(9);
  validate_PermissionsMar(10);
  validate_Permissions(8, "url");

  resetTabs("#CargarDatosProducto");

  // cargarCMBEstatus("", "cmbEstatusProducto");
  cargarCMBTipo("", "cmbTipoProducto");
  cargarCMBCostoUniCompraGral("", "cmbCostoUniCompra");
  cargarCMBCostoUniVentaGral("", "cmbCostoUniVenta");
  cargarCMBCostoUniFabriGral("", "cmbCostoUniFabri");
  cargarCMBCostoUniGastoFijo("", "cmbCostoUniGastoF");

  //cargarCMBAccionesProductoTemp('','cmbAccionesProductoTemp');

  var html = `<div class="card shadow mb-4">
                <div class="card-header">
                  Tarjeta de productos
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-12">
                      <form id="formDatosProducto">
                        <div class="form-group">
                          <div class="row">
                            
                            <div class="col-lg-12">
                              <div class="form-group">
                                <div class="row">
                                  <div class="col-xl-9 col-lg-9 col-md-6 col-sm-3 col-xs-0">
                                    
                                  </div>
                                  <div class="col-xl-1 col-lg-1 col-md-2 col-sm-4 col-xs-6">
                                    <label for="usr">Estatus:*</label>
                                  </div>
                                  <div class="col-xl-2 col-lg-2 col-md-4 col-sm-5 col-xs-6">
                                    <input type="checkbox" id="activeProducto" name="activeProducto" class="check-custom" checked>
                                    <label class="shadow-sm check-custom-label" for="activeProducto">
                                      <div class="circle"></div>
                                      <div class="check-inactivo">Inactivo</div>
                                      <div class="check-activo">Activo</div>
                                    </label>
                                  </div>
                                </div>
                              </div>
                              
                              <div class="form-group">
                                <div class="row">
                                  <div class="col-lg-6">
                                    <label for="usr">Nombre:*</label>
                                    <div class="row">
                                      <div class="col-lg-12 input-group">
                                        <input class="form-control alphaNumeric-onlyB" type="text" name="txtNombre" id="txtNombre" required maxlength="1000" placeholder="Ej. Bata quirúgica desechable" onkeyup="escribirNombre();">
                                        <div class="invalid-feedback" id="invalid-nombreProd">El producto debe tener un nombre.</div>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="col-lg-6">
                                    <label for="usr">Tipo:*</label>
                                    <div class="row">
                                      <div class="col-lg-12 input-group">
                                          <select name="cmbTipoProducto" id="cmbTipoProducto" required="" onchange="cambiarTipoProd()">
                                          </select>
                                          <div class="invalid-feedback" id="invalid-tipoProd">El producto debe tener un tipo.</div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              
                              <div class="form-group">
                                <div class="row">
                                  <div class="col-lg-6"
                                    <label for="usr">Clave interna:*</label>
                                    <div class="row">
                                      <div class="col-lg-12 input-group">
                                        <input type="text" class="form-control alphaNumeric-onlyB" name="txtClaveInterna" id="txtClaveInterna" required maxlength="50" placeholder="Ej. AA - 0001" onkeyup="escribirClave();" style="text-transform:uppercase">
                                        <div class="invalid-feedback" id="invalid-claveProd">El producto debe tener una clave interna.</div>
                                        <a href="#" class="btn-custom btn-custom--blue ml-3" id="btnGenerarClave">Generar</a>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="col-lg-6">
                                  <label for="usr">Código de barras:</label>
                                    <div class="row">
                                      <div class="col-lg-12 input-group">
                                        <input type="text" class="form-control numeric-only" name="txtCodigoBarras" id="txtCodigoBarras" maxlength="50" placeholder="Ej. 7 88492 808274" onkeyup="escribirCodigo()">
                                        <div class="invalid-feedback" id="invalid-codigoProd">El codigo del producto debe ser unico.</div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>

                              <div class="form-group">
                                <div class="row">
                                  <div class="col-lg-6">
                                    <label for="usr">Categoría:</label>
                                    <div class="row">
                                      <div class="col-lg-12 input-group">
                                        <select name="cmbCategoriaProducto" id="cmbCategoriaProducto">
                                        </select>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="col-lg-6">
                                    <label for="usr">Marca:</label>
                                    <div class="row">
                                      <div class="col-lg-12 input-group">
                                        <select name="cmbMarcaProducto" id="cmbMarcaProducto">
                                        </select>
                                        <img  id="notaFMarcaProducto" name="notaFMarcaProducto" style="display: none;"
                                        src="../../../../img/timdesk/alerta.svg" width=30px
                                        title="Campo requerido" readonly>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>

                              <div class="form-group">
                                <div class="row">
                                  <div class="col-lg-12">
                                    <label for="usr">Descripción:</label>
                                    <div class="row">
                                      <div class="col-lg-12 input-group">
                                        <textarea type="text" class="form-control" maxlength="255" id="txtDescripcionLarga"
                                        name="txtDescripcionLarga" cols="30" rows="3" placeholder="Escriba aquí la descripción"
                                        style="resize: none!important;"></textarea>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                                <br>
                                <div class="row">
                                  <div class="col-lg-6">
                                    <label for="usr">Unidad de medida SAT:*</label>
                                    <input  name="txtIDUnidadSAT" id="txtIDUnidadSAT" type="hidden" value="1071" readonly>
                                    <div class="row">
                                      <div class="col-lg-12 input-group">
                                        <input type="text" class="form-control" name="cmbUnidadSAT" id="cmbUnidadSAT" data-toggle="modal" data-target="#agregar_UnidadSAT" 
                                        placeholder="Seleccione una unidad de medida..." value="H87 - Pieza" readonly required="" >
                                        <img  id="notaFUnidadSAT" name="notaFUnidadSAT" style="display: none;"
                                        src="../../../../img/timdesk/alerta.svg" width=30px
                                        title="Campo requerido" readonly>
                                        <div class="invalid-feedback" id="invalid-claveUnidad">El producto debe tener unidad de medida.</div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>

                            </div>
                          </div>
                        </div> 
                        <br>

                        <div class="form-group">
                          <div class="row">
                            <div class="col-lg-12">
                              <span id="areaCompuesto">
                              </span>
                            </div>
                          </div>
                        </div>
                        <input  name="contadorCompuesto" id="contadorCompuesto" type="hidden" readonly value="0">

                        <div class="form-group">
                            <div class="row">
                                <div class="col-3">
                                <p>Operaciones del producto:</p>
                                </div>
                            </div>
                            <div class="row" style="height: 80px">
                                <div class="col-2">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="cbxCompra" name="cbxCompra" onclick="activarCompra()">
                                        <label class="form-check-label" for="flexSwitchCheckDefault">Compra</label>
                                    </div>
                                </div>
                                <div class="col-4 input-group" style="display: none;" id="spanCompra">
                                  <div class="form-group">
                                    <label for="usr">Costo unitario general de compra:*</label>
                                    <div class="input-group">
                                      <input class="form-control numericDecimal-only" maxlength="13" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" type="text" name="txtCostoUniCompra" id="txtCostoUniCompra" placeholder="Ej. 10.00" style="float:left;" onkeyup="validEmptyInput('txtCostoUniCompra', 'invalid-costoUnitProd', 'El crédito debe tener un monto.')">
                                      <span class="input-group-append" >
                                        <select name="cmbCostoUniCompra" id="cmbCostoUniCompra">
                                        </select>
                                      </span>
                                      <div class="invalid-feedback" id="invalid-costoUnitProd">El producto debe tener un costo unitario.</div>
                                    </div>
                                  </div>
                                </div>
                                
                            </div>
                            <div class="row" style="height: 80px">
                                <div class="col-2">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="cbxVenta" name="cbxVenta" onclick="activarVenta()">
                                        <label class="form-check-label" for="flexSwitchCheckDefault">Venta</label>
                                    </div>
                                </div>
                                <div class="col-4 input-group" style="display: none;" id="spanVenta">
                                    <div class="form-group">
                                    <label>Costo unitario general de venta:*</label>
                                    <div class="input-group">
                                        <input class="form-control numericDecimal-only" maxlength="13" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" type="text" name="txtCostoUniVenta" id="txtCostoUniVenta" placeholder="Ej. 10.00" style="float:left;" onkeyup="validEmptyInput('txtCostoUniVenta', 'invalid-costoVentaProd', 'El producto debe tener un costo de venta.')">
                                        <span class="input-group-append">
                                        <select name="cmbCostoUniVenta" id="cmbCostoUniVenta">
                                        </select> 
                                        </span>
                                        <div class="invalid-feedback" id="invalid-costoVentaProd">El producto debe tener un costo de venta.</div>
                                    </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row" style="height: 80px">
                                <div class="col-2">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="cbxFabricacion" name="cbxFabricacion" onclick="activarFabri()">
                                        <label class="form-check-label" for="flexSwitchCheckDefault">Fabricación</label>
                                    </div>
                                </div>
                                <div class="col-4 input-group"  style="display: none;" id="spanFabri">
                                    <div class="form-group">
                                    <label>Costo unitario general de fabricación:*</label>
                                    <div class="input-group">
                                    <input class="form-control numericDecimal-only" maxlength="13" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" type="text" name="txtCostoUniFabri" id="txtCostoUniFabri" placeholder="Ej. 10.00" style="float:left;" onkeyup="validEmptyInput('txtCostoUniFabri', 'invalid-costoFabrProd', 'El producto debe tener un costo de fabricación.')">
                                    <span class="input-group-append">
                                        <select name="cmbCostoUniFabri" id="cmbCostoUniFabri">
                                        </select> 
                                    </span>
                                    <div class="invalid-feedback" id="invalid-costoFabrProd">El producto debe tener un costo de fabricación.</div>
                                    </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row" style="height: 80px">
                                <div class="col-2">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="cbxGastoFijo" name="cbxGastoFijo" onclick="activarGastoF()">
                                        <label class="form-check-label" for="flexSwitchCheckDefault">Gasto fijo</label>
                                    </div>
                                </div>
                                <div class="col-4 input-group"  style="display: none;" id="spanGastoF">
                                    <div class="form-group">
                                    <label>Costo unitario general del gasto fijo:*</label>
                                    <div class="input-group">
                                    <input class="form-control numericDecimal-only" maxlength="13" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" type="text" name="txtCostoUniGastoF" id="txtCostoUniGastoF" placeholder="Ej. 10.00" style="float:left;" onkeyup="validEmptyInput('txtCostoUniGastoF', 'invalid-costoGastoF', 'El gasto fijo debe tener un costo.')">
                                    <span class="input-group-append">
                                        <select name="cmbCostoUniGastoF" id="cmbCostoUniGastoF">
                                        </select> 
                                    </span>
                                    <div class="invalid-feedback" id="invalid-costoGastoF">El gasto fijo debe tener un costo.</div>
                                    </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-9">
                              <div class="row">
                                

                                

                                
                              </div>
                            
                          <br><br>
                          <div class="row">
                            <div class="col-6">
                              <p> </p>
                              <div class="row">
                                <!--<div class="col-4">
                                  <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="cbxSerie" name="cbxSerie" onclick="activarSerie()">
                                    <label class="form-check-label" for="flexSwitchCheckDefault">No. de Serie Quitar</label>
                                  </div>
                                </div>-->
                                <div class="col-4">
                                  <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="cbxLote" name="cbxLote" onclick="activarLote()">
                                    <label class="form-check-label" for="flexSwitchCheckDefault">Lote</label>
                                  </div>
                                </div>
                                <div class="col-4">
                                  <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="cbxCaducidad" name="cbxCaducidad" onclick="activarCaducidad()">
                                    <label class="form-check-label" for="flexSwitchCheckDefault">Fecha de caducidad</label>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-6">
                              <div class="row">
                                <div class="col-6 input-group" style="display: none;" id="spanSerie">
                                  <div class="form-group">
                                    <label for="usr">Número de serie:*</label>
                                    <div class="input-group">
                                      <input class="form-control" type="text" maxlength="50" name="txtSerie" id="txtSerie" placeholder="Ej. 4A185048W" style="float:left;" onkeyup="validEmptyInput('txtSerie', 'invalid-serie', 'El producto debe de tener número de serie.')">
                                      <div class="invalid-feedback" id="invalid-serie">El producto debe de tener número de serie.</div>
                                    </div>
                                  </div>
                                </div>

                                <div class="col-6 input-group" style="display: none;" id="spanLote">
                                  <div class="form-group">
                                    <label>Lote:*</label>
                                    <div class="input-group">
                                      <input class="form-control" type="text" maxlength="50" name="txtLotes" id="txtLotes" placeholder="Ej. LO/33654" style="float:left;" onkeyup="validEmptyInput('txtLotes', 'invalid-lote', 'El producto debe tener un lote.')">
                                      <div class="invalid-feedback" id="invalid-lote">El producto debe tener un lote.</div>
                                    </div>
                                  </div>
                                </div>

                                <div class="col-6 input-group"  style="display: none;" id="spanCaducidad">
                                  <div class="form-group">
                                  <label>Fecha de caducidad:*</label>
                                  <div class="input-group">
                                    <input class="form-control" type="date" name="txtCaducidad" id="txtCaducidad" style="float:left;" onkeyup="validEmptyInput('txtCaducidad', 'invalid-caducidad', 'El producto debe tener una fecha de caducidad.')">
                                    <div class="invalid-feedback" id="invalid-caducidad">El producto debe tener una fecha de caducidad.</div>
                                  </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>

                          <div class="row">
                            <div class="col-lg-12">
                              <!-- DataTales Example -->
                              <!--<div class="card-body">
                                <div class="table-responsive">
                                  <table class="table" id="tblListadoAccionesProductoTemp" width="100%" cellspacing="0">
                                    <thead>
                                      <tr>
                                        <th>Id</th>
                                        <th>Operación de producto</th>
                                      </tr>
                                    </thead>
                                  </table>
                                </div>
                              </div>-->
                            </div>
                          </div>
                        </div>
                        <br>



                        <label for="">* Campos requeridos</label>
                      </form>

                      <a href="#" class="btn-custom btn-custom--blue float-right" id="btnAgregarProducto">Guardar</a>
                    </div>
                  </div>
                </div>
              </div>`;

  $("#datos").append(html);
  new SlimSelect({
    select: "#cmbCategoriaProducto",
    deselectLabel: '<span class="">✖</span>',
    addable: function (value) {
      if (_permissionsCat.read == "1") {
        validarCategoria(value);
      }
    },
  });

  new SlimSelect({
    select: "#cmbMarcaProducto",
    deselectLabel: '<span class="">✖</span>',
    addable: function (value) {
      if (_permissionsMar.read == "1") {
        validarMarca(value);
      }
    },
  });

  new SlimSelect({
    select: "#cmbTipoProducto",
    deselectLabel: '<span class="">✖</span>',
    /*addable: function (value) {
      validarTipoProducto(value);
    }*/
  });

  cargarTablaAccionesTemp($("#PKUsuario").val());
}

function activarCompra() {
  $("#invalid-costoUnitProd").css("display", "none");
  $("#txtCostoUniCompra").removeClass("is-invalid");
  $("#txtCostoUniCompra").val("");
  if ($("#cbxCompra").is(":checked")) {
    $("#spanCompra").css("display", "block");
    $("#txtCostoUniCompra").prop("required", true);
    //cargarCMBCostoUniCompraGral("", "cmbCostoUniCompra");

    $("#pestDatosProveedor").removeAttr(
      "style",
      "pointer-events:none; opacity:0.4;"
    );
  } else {
    $("#spanCompra").css("display", "none");
    $("#txtCostoUniCompra").prop("required", false);

    $("#pestDatosProveedor").attr("style", "pointer-events:none; opacity:0.4;");
  }
}

function activarVenta() {
  $("#invalid-costoVentaProd").css("display", "none");
  $("#txtCostoUniVenta").removeClass("is-invalid");
  $("#txtCostoUniVenta").val("");
  if ($("#cbxVenta").is(":checked")) {
    $("#pestDatosVenta").removeAttr(
      "style",
      "pointer-events:none; opacity:0.4;"
    );
    $("#spanVenta").css("display", "block");
    $("#txtCostoUniVenta").prop("required", true);
  } else {
    $("#pestDatosVenta").attr("style", "pointer-events:none; opacity:0.4;");

    $("#spanVenta").css("display", "none");
    $("#txtCostoUniVenta").prop("required", false);
  }
}

function activarFabri() {
  $("#invalid-costoFabrProd").css("display", "none");
  $("#txtCostoUniFabri").removeClass("is-invalid");
  $("#txtCostoUniFabri").val("");
  if ($("#cbxFabricacion").is(":checked")) {
    $("#spanFabri").css("display", "block");
    $("#txtCostoUniFabri").prop("required", true);
  } else {
    $("#spanFabri").css("display", "none");
    $("#txtCostoUniFabri").prop("required", false);
  }

  $("#txtCostoUniFabri").val(0);
}

function activarGastoF() {
  $("#invalid-costoGastoF").css("display", "none");
  $("#txtCostoUniGastoF").removeClass("is-invalid");
  $("#txtCostoUniGastoF").val("");
  if ($("#cbxGastoFijo").is(":checked")) {
    $("#spanGastoF").css("display", "block");
    $("#txtCostoUniGastoF").prop("required", true);
  } else {
    $("#spanGastoF").css("display", "none");
    $("#txtCostoUniGastoF").prop("required", false);
  }
  $("#txtCostoUniGastoF").val(0);
}

//function activarSerie() {
  /*$("#invalid-serie").css("display", "none");
  $("#txtSerie").removeClass("is-invalid");
  $("#txtSerie").val("");*/

  //if ($("#cbxSerie").is(":checked")) {
    /*$("#spanSerie").css("display", "block");
    $("#txtSerie").prop("required", true);

    $("#spanLote").css("display", "none");*/
    //$("#cbxLote").prop("checked", false);
    /*$("#txtLotes").prop("required", false);
    $("#txtLotes").val("");*/
  //} else {
    /*$("#spanSerie").css("display", "none");
    $("#txtSerie").prop("required", false);*/
  //}
//}

function activarLote() {
  /*$("#invalid-lote").css("display", "none");
  $("#txtLotes").removeClass("is-invalid");
  $("#txtLotes").val("");*/

  if ($("#cbxLote").is(":checked")) {
    /*$("#spanLote").css("display", "block");
    $("#txtLotes").prop("required", true);

    $("#spanSerie").css("display", "none");*/
    $("#cbxSerie").prop("checked", false);
    /*$("#txtSerie").prop("required", false);
    $("#txtSerie").val("");*/
  } else {
    /*$("#spanLote").css("display", "none");
    $("#txtLotes").prop("required", false);*/
  }
}

function activarCaducidad() {
  /*$("#invalid-caducidad").css("display", "none");
  $("#txtCaducidad").removeClass("is-invalid");
  $("#txtCaducidad").val("");

  if ($("#cbxCaducidad").is(":checked")) {
    $("#spanCaducidad").css("display", "block");
    $("#txtCaducidad").prop("required", true);
  } else {
    $("#spanCaducidad").css("display", "none");
    $("#txtCaducidad").prop("required", false);
  }*/
}

/*----------------------Funciones de carga de datos de los combos-------------------------------*/
function cargarCMBEstatus(data, input) {
  var html = "";
  var selected;
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_estatusGral" },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta estatus: ", respuesta);

      $.each(respuesta, function (i) {
        if (data === respuesta[i].PKEstatusGeneral) {
          selected = "selected";
        } else {
          selected = "";
        }

        html +=
          '<option id="opEG-' +
          respuesta[i].PKEstatusGeneral +
          '" value="' +
          respuesta[i].PKEstatusGeneral +
          '" ' +
          selected +
          ">" +
          respuesta[i].Estatus +
          "</option>";
      });

      $("#" + input + "").append(html);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function cargarCMBCategoria(data, input) {
  var html = "";
  var html2 = "";
  var selected;
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_categoria" },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta categoría: ", respuesta);

      //html += '<option value="0">Seleccione una categoría...</option>';

      $.each(respuesta, function (i) {
        if (data === respuesta[i].PKCategoriaProducto) {
          selected = "selected";
        } else {
          selected = "";
        }

        if ("Sin categoría" == respuesta[i].CategoriaProductos) {
          html2 =
            '<option value="' +
            respuesta[i].PKCategoriaProducto +
            '" ' +
            selected +
            ">" +
            respuesta[i].CategoriaProductos +
            "</option>";
        } else {
          html +=
            '<option value="' +
            respuesta[i].PKCategoriaProducto +
            '" ' +
            selected +
            ">" +
            respuesta[i].CategoriaProductos +
            "</option>";
        }
      });

      if (_permissionsCat.read == "1") {
        html +=
          '<option value="add" style="background-color: #15589B;  color:white; text-align:center; width:100%;">Configurar categorías</option>';
      }

      $("#" + input + "").html(html2 + html);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function cargarCMBMarca(data, input) {
  var html = "";
  var html2 = "";
  var selected;
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_marca" },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta marca: ", respuesta);

      //html += '<option value="0">Seleccione una marca...</option>';

      $.each(respuesta, function (i) {
        if (data === respuesta[i].PKMarcaProducto) {
          selected = "selected";
        } else {
          selected = "";
        }

        if ("Sin marca" == respuesta[i].MarcaProducto) {
          html2 =
            '<option value="' +
            respuesta[i].PKMarcaProducto +
            '" ' +
            selected +
            ">" +
            respuesta[i].MarcaProducto +
            "</option>";
        } else {
          html +=
            '<option value="' +
            respuesta[i].PKMarcaProducto +
            '" ' +
            selected +
            ">" +
            respuesta[i].MarcaProducto +
            "</option>";
        }
      });

      if (_permissionsMar.read == "1") {
        html +=
          '<option value="add" style="background-color: #15589B;  color:white; text-align:center; width:100%;">Configurar marcas</option>';
      }

      $("#" + input + "").html(html2 + html);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function cargarCMBTipo(data, input) {
  var html = "";
  var selected;
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_tipo" },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta tipo producto: ", respuesta);

      html += '<option data-placeholder="true"></option>';

      $.each(respuesta, function (i) {
        if (data === respuesta[i].PKTipoProducto) {
          selected = "selected";
        } else {
          selected = "";
        }

        html +=
          '<option value="' +
          respuesta[i].PKTipoProducto +
          '" ' +
          selected +
          ">" +
          respuesta[i].TipoProducto +
          "</option>";
      });

      /*html += '<option value="add" style="background-color: #15589B;  color:white; text-align:center; width:100%;">Configurar tipos de producto</option>';*/

      $("#" + input + "").html(html);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function cargarCMBAccionesProductoTemp(data, input) {
  var html = "";
  var selected;
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_acciones_producto" },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta acciones del producto: ", respuesta);

      $.each(respuesta, function (i) {
        if (data === respuesta[i].PKAccionProducto) {
          selected = "selected";
        } else {
          selected = "";
        }

        html +=
          '<option value="' +
          respuesta[i].PKAccionProducto +
          '" ' +
          selected +
          ">" +
          respuesta[i].AccionProducto +
          "</option>";
      });

      $("#" + input + "").html(html);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function cargarCMBCostoUniCompraGral(data, input) {
  var html = "";
  var selected;
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_costouni_venta" },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta tipo de moneda: ", respuesta);

      $.each(respuesta, function (i) {
        if (data === respuesta[i].PKTipoMoneda) {
          selected = "selected";
        } else {
          selected = "";
        }

        html +=
          '<option value="' +
          respuesta[i].PKTipoMoneda +
          '" ' +
          selected +
          ">" +
          respuesta[i].TipoMoneda +
          "</option>";
      });

      $("#" + input + "").append(html);
      $("#cmbCostoUniCompra").val("100");

      cargarCMBCompra();
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function cargarCMBCostoUniVentaGral(data, input) {
  var html = "";
  var selected;
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_costouni_venta" },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta tipo de moneda: ", respuesta);

      $.each(respuesta, function (i) {
        if (data === respuesta[i].PKTipoMoneda) {
          selected = "selected";
        } else {
          selected = "";
        }

        html +=
          '<option value="' +
          respuesta[i].PKTipoMoneda +
          '" ' +
          selected +
          ">" +
          respuesta[i].TipoMoneda +
          "</option>";
      });

      $("#" + input + "").append(html);
      $("#cmbCostoUniVenta").val("100");

      cargarCMBVenta();
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function cargarCMBCostoUniFabriGral(data, input) {
  var html = "";
  var selected;
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_costouni_venta" },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta tipo de moneda: ", respuesta);

      $.each(respuesta, function (i) {
        if (data === respuesta[i].PKTipoMoneda) {
          selected = "selected";
        } else {
          selected = "";
        }

        html +=
          '<option value="' +
          respuesta[i].PKTipoMoneda +
          '" ' +
          selected +
          ">" +
          respuesta[i].TipoMoneda +
          "</option>";
      });

      $("#" + input + "").append(html);
      $("#cmbCostoUniFabri").val("100");

      cargarCMBFabri();
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function cargarCMBCostoUniGastoFijo(data, input) {
  var html = "";
  var selected;
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_costouni_venta" },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta tipo de moneda: ", respuesta);

      $.each(respuesta, function (i) {
        if (data === respuesta[i].PKTipoMoneda) {
          selected = "selected";
        } else {
          selected = "";
        }

        html +=
          '<option value="' +
          respuesta[i].PKTipoMoneda +
          '" ' +
          selected +
          ">" +
          respuesta[i].TipoMoneda +
          "</option>";
      });

      $("#" + input + "").append(html);
      $("#cmbCostoUniGastoF").val("100");

      cargarCMBGastoF();
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function cargarCMBCompra() {
  new SlimSelect({
    select: "#cmbCostoUniCompra",
    deselectLabel: '<span class="">✖</span>',
    /*addable: function (value) {
      validarTipoProducto(value);
    }*/
  });
}

function cargarCMBGastoF() {
  new SlimSelect({
    select: "#cmbCostoUniGastoF",
    deselectLabel: '<span class="">✖</span>',
  });
}

function cargarCMBVenta() {
  new SlimSelect({
    select: "#cmbCostoUniVenta",
    deselectLabel: '<span class="">✖</span>',
  });
}

function cargarCMBFabri() {
  new SlimSelect({
    select: "#cmbCostoUniFabri",
    deselectLabel: '<span class="">✖</span>',
  });
}

/*----------------------------Botón añadir tipo producto ---------------------------*/
/* VALIAR QUE NO SE REPITA EL TIPO (Accion) DE PRODUCTO AGREGADO POR EL USUARIO EN AGREGAR */
function verificarAccionProductoTemp(id) {
  var valor = document.getElementById("cmbAccionesProductoTemp").value;
  console.log("Valor tipo producto" + valor);
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "validar_accionProducto_temp",
      data: valor,
      data2: id,
    },
    dataType: "json",
    success: function (data) {
      console.log("respuesta tipo producto validado: ", data);
      /* Validar si ya existe el identificador con ese nombre*/
      if (parseInt(data[0]["existe"]) == 1) {
        $("#notaTipoProductoTemp").css("display", "block");

        console.log("¡Ya existe!");
      } else {
        $("#notaTipoProductoTemp").css("display", "none");

        console.log("¡No existe!");
      }
    },
  });
}

function validarAccionProductoTemp(id) {
  var valor = document.getElementById("cmbAccionesProductoTemp").value;
  console.log("Valor tipo producto" + valor);
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "validar_accionProducto_temp",
      data: valor,
      data2: id,
    },
    dataType: "json",
    success: function (data) {
      console.log("respuesta tipo producto validado: ", data);
      /* Validar si ya existe el identificador con ese nombre*/
      if (parseInt(data[0]["existe"]) == 1) {
        $("#notaTipoProductoTemp").css("display", "block");

        console.log("¡Ya existe!");
      } else {
        $("#notaTipoProductoTemp").css("display", "none");

        anadirAccionProductoTemp(id);

        console.log("¡No existe!");
      }
    },
  });
}

/* Añadir el impuesto */
function anadirAccionProductoTemp(id) {
  var valor = document.getElementById("cmbAccionesProductoTemp").value;
  console.log("Valor tipo producto" + valor);

  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "save_data",
      funcion: "save_datosTipoProducto_Temp",
      data: valor,
      data2: id,
    },
    dataType: "json",
    success: function (respuesta) {
      console.log(
        "respuesta agregar datos tipo producto (acción) del producto:",
        respuesta
      );

      if (respuesta[0].status) {
        $("#tblListadoAccionesProductoTemp").DataTable().ajax.reload();
        Lobibox.notify("success", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/checkmark.svg",
          msg: "¡Se guardó el tipo de producto con éxito!",
          sound: "../../../../../sounds/sound4",
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
          sound: "../../../../../sounds/sound4",
        });
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
}

/* Eliminar el tipo de producto (acción) */
function eliminarAccionProductoTemp(tipoProducto) {
  console.log("ID del tipo producto: " + tipoProducto);
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "delete_data",
      funcion: "delete_accion_producto_temp",
      datos: tipoProducto,
    },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta eliminar tipo producto:", respuesta);

      if (respuesta[0].status) {
        $("#tblListadoAccionesProductoTemp").DataTable().ajax.reload();
        Lobibox.notify("success", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/checkmark.svg",
          msg: "¡Se eliminó el tipo de producto con éxito!",
          sound: "../../../../../sounds/sound4",
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
          sound: "../../../../../sounds/sound4",
        });
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function cambiarTipoProd() {
  var tipoProd = $("#cmbTipoProducto").val();
  if (tipoProd) {
    $("#invalid-tipoProd").css("display", "none");
    $("#cmbTipoProducto").removeClass("is-invalid");
    if (parseInt(tipoProd) == 1) {
      var body = `<div class="form-group">
                  <div class="row">
                    <div class="col-lg-12">
                      <label for="usr">Productos que lo componen:</label>
                    </div>
                  </div>
                </div>
                <input  name="txtSeleccion" id="txtSeleccion" type="hidden" readonly>
                <div class="form-group">
                  <!-- DataTales Example -->
                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table" id="tablaprueba" width="100%" cellspacing="0">
                        <thead>
                          <tr>
                            <th style="width:45%">Clave/Producto*</th>
                            <th>Cantidad y unidad de medida</th>
                            <th>Costo</th>
                            <th></th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td>
                              <input  name="txtProductos1" id="txtProductos1" type="hidden" readonly>
                              <input type="text" class="form-control" name="cmbProductos1" id="cmbProductos1" data-toggle="modal" data-target="#agregar_Producto" 
                              placeholder="Seleccione un producto..." readonly required="" onclick="clickSeleccionarProd(1)">
                              <img  id="notaFProdComp" name="notaFProdComp" style="display: none;"
                              src="../../../../img/timdesk/alerta.svg" width=30px
                              title="Seleccione por lo menos un producto" readonly>
                            </td>
                            <td>
                              <div class="row">
                                <div class="col-lg-6">
                                  <input class="form-control" type="number" name="txtCantidadCompuesta1" id="txtCantidadCompuesta1" min="0" maxlength="12" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" autofocus="" required="" placeholder="Ej. 10" onkeyup="guardarCantidadProdCompTemp(1)">
                                </div>
                                <div class="col-lg-6">
                                  <label  for="usr"><span id="lblUnidadMedida1"> </span></label>
                                </div>
                              </div>
                            </td>
                            <td>
                              <div class="row">
                                <div class="col-lg-12">
                                  <label  for="usr"><span id="lblCosto1"> </span><input type="hidden" id="txtCosto1"><input type="hidden" id="txtMoneda1"></label>
                                </div>
                              </div>
                            </td>
                            <td>
                              *
                            </td>
                          </tr>
                          <tr>
                            <td>
                              <input  name="txtProductos2" id="txtProductos2" type="hidden" readonly>
                              <input type="text" class="form-control" name="cmbProductos2" id="cmbProductos2" data-toggle="modal" data-target="#agregar_Producto" 
                              placeholder="Seleccione un producto..." readonly required="" onclick="clickSeleccionarProd(2)">
                            </td>
                            <td>
                              <div class="row">
                                <div class="col-lg-6">
                                  <input class="form-control" type="number" name="txtCantidadCompuesta2" id="txtCantidadCompuesta2" min="0" maxlength="12" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" autofocus="" required="" placeholder="Ej. 10" onkeyup="guardarCantidadProdCompTemp(2)">
                                </div>
                                <div class="col-lg-6">
                                  <label  for="usr"><span id="lblUnidadMedida2"> </span></label>
                                </div>
                              </div>
                            </td>
                            <td>
                              <div class="row">
                                <div class="col-lg-12">
                                  <label  for="usr"><span id="lblCosto2"> </span><input type="hidden" id="txtCosto2"><input type="hidden" id="txtMoneda2"></label>
                                </div>
                              </div>
                            </td>
                            <td>
                              <i><img class=\"btnEdit\" src=\"../../../../img/timdesk/delete.svg\" onclick="eliminarCompTemp(2); event.preventDefault(); $(this).closest('tr').remove(); "></i>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                
                <div class="form-group">
                  <div class="row">
                    <div class="col-lg-6">
                      <i><img  src=\"../../../../img/timdesk/ICONO AGREGAR2_Mesa de trabajo 1.svg\" onclick="agregarFila()" width="30px">  </i>
                      <label>  Añadir producto</label>
                    </div>
                  </div>
                </div>
                `;
      //$("#areaCompuesto").html(body);

      //cargarCMBProductos(0);

      //Al dar click por primera vez que se eliminen los que se hayan quedado de acción anterior no completada
      eliminarRegistrosTempProdComp();

      $("#cbxLote").prop("checked", true);
      setTimeout(function () {
        $("#cbxLote").prop("disabled", true);
        //$("#cbxSerie").prop("disabled", true);
        activarLote();
      }, 100);
    } else {
      if (parseInt(tipoProd) == 3) {
        $("#cbxFabricacion").prop("checked", true);
        $("#cbxFabricacion").prop("disabled", true);
        activarFabri();

        $("#cbxLote").prop("checked", true);
        setTimeout(function () {
          $("#cbxLote").prop("disabled", true);
          //$("#cbxSerie").prop("disabled", true);
          activarLote();
        }, 100);
      } else {
        $("#cbxFabricacion").prop("checked", false);
        $("#cbxFabricacion").prop("disabled", false);
        activarFabri();

        $("#cbxLote").prop("checked", false);
        setTimeout(function () {
          $("#cbxLote").prop("disabled", false);
          //$("#cbxSerie").prop("disabled", false);
          activarLote();
        }, 100);
      }

      if (parseInt(tipoProd) == 6) {
        //$("#cbxSerie").prop("checked", true);
        //$("#cbxSerie").prop("disabled", true);

        $("#cbxLote").prop("disabled", true);
        //activarSerie();
      } else {
        //$("#cbxSerie").prop("checked", false);
        //$("#cbxSerie").prop("disabled", false);

        $("#cbxLote").prop("disabled", false);
        //activarSerie();
      }

      if (parseInt(tipoProd) == 9) {
        $("#cbxLote").prop("checked", true);
        setTimeout(function () {
          $("#cbxLote").prop("disabled", true);
          //$("#cbxSerie").prop("disabled", true);
          activarLote();
        }, 100);
      }

      if(parseInt(tipoProd) == 10){
        $("#cbxGastoFijo").prop("checked", true);
        $("#cbxGastoFijo").prop("disabled", true);
        activarGastoF();
      }

      var body = ``;
      $("#areaCompuesto").html(body);

      //Al cambiar eliminar los registros
      eliminarRegistrosTempProdComp();
    }
  } else {
    $("#invalid-tipoProd").css("display", "block");
    $("#cmbTipoProducto").addClass("is-invalid");
  }
}

function eliminarRegistrosTempProdComp() {
  var pkUsuario = $("#PKUsuario").val();
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "delete_data",
      funcion: "delete_datosProductoCompTempAll",
      datos: pkUsuario,
    },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta agregar datos generales del producto:", respuesta);

      if (respuesta[0].status) {
        console.log("Prod. Eliminado todos");
      } else {
        console.log("Error al eliminar todos");
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
}

/* --------------------------- Agregar / Eliminar fila de tabla de producto compuesto --------------------------------------- */
function agregarFila() {
  var table = document.getElementById("tablaprueba");
  var rowCount = table.rows.length;
  document.getElementById("tablaprueba").insertRow(-1).innerHTML = `<td>
      <input  name="txtProductos${rowCount}" id="txtProductos${rowCount}" type="hidden" readonly>
      <input type="text" class="form-control" name="cmbProductos${rowCount}" id="cmbProductos${rowCount}" data-toggle="modal" data-target="#agregar_Producto" placeholder="Seleccione un producto..." readonly onclick="clickSeleccionarProd(${rowCount})">
    </td>
    <td>
      <div class="row">
        <div class="col-lg-6">
          <input class="form-control" type="number" name="txtCantidadCompuesta${rowCount}" id="txtCantidadCompuesta${rowCount}" min="0" maxlength="12" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" placeholder="Ej. 10" onkeyup="guardarCantidadProdCompTemp(${rowCount})">
        </div>
        <div class="col-lg-6">
          <label  for="usr"><span id="lblUnidadMedida${rowCount}"> </span></label>
        </div>
      </div>
    </td>
    <td>
      <div class="row">
        <div class="col-lg-12">
          <label  for="usr"><span id="lblCosto${rowCount}"> </span><input type="hidden" id="txtCosto${rowCount}"><input type="hidden" id="txtMoneda${rowCount}"></label>
        </div>
      </div>
    </td>
    <td>
      <i><img class=\"btnEdit\" src=\"../../../../img/timdesk/delete.svg\" onclick="eliminarCompTemp(${rowCount}); event.preventDefault(); $(this).closest('tr').remove(); "></i>
    </td>`;
}

function eliminarCompTemp(elemento) {
  var pkUsuario = $("#PKUsuario").val();

  var seleccion = $("#txtProductos" + elemento + "").val();
  console.log("A eliminar el PKProd:" + seleccion);
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "delete_data",
      funcion: "delete_datosProductoCompTemp",
      datos: pkUsuario,
      datos2: seleccion,
    },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta agregar datos generales del producto:", respuesta);

      if (respuesta[0].status) {
        console.log("Prod. Eliminado unico");
      } else {
        console.log("Error al eliminar unico");
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function guardarCantidadProdCompTemp(elemento) {
  var pkUsuario = $("#PKUsuario").val();
  var seleccion = $("#txtProductos" + elemento).val();
  var cantidad = 0;
  if (
    $("#txtCantidadCompuesta" + elemento).val() == "" ||
    $("#txtCantidadCompuesta" + elemento).val() == null
  ) {
    cantidad = 0;
  } else {
    cantidad = $("#txtCantidadCompuesta" + elemento).val();
  }

  var costo = $("#txtCosto" + elemento).val();
  var moneda = $("#txtMoneda" + elemento).val();

  var newCosto = costo * cantidad;

  $("#lblCosto" + elemento).html(newCosto + " " + moneda);

  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "edit_data",
      funcion: "edit_datosCantidadProductoCompTemp",
      datos: pkUsuario,
      datos2: seleccion,
      datos3: cantidad,
      datos4: newCosto,
      datos5: moneda,
    },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta agregar datos generales del producto:", respuesta);

      if (respuesta[0].status) {
        console.log("Prod. Cant. Actualizada");
      } else {
        console.log("Error al actualizar");
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
}

/*----------------------Estilos de funciones-------------------------------*/

//Funciones para los eventos de los elementos de la página
function mostrarColor() {
  if (document.getElementById("cmbEstatusProducto").value == 1) {
    document.getElementById("cmbEstatusProducto").style.background = "#28c67a";
    document.getElementById("cmbEstatusProducto").style.color = "#FFFFFF";
  } else {
    document.getElementById("cmbEstatusProducto").style.background = "#cac8c6";
  }
}

function cambiarColor() {
  //Cambiar de color los combos al abrir por primera vez la página
  $("#opEG-1").css({ "background-color": "#28c67a", color: "#FFFFFF" });
  $("#opEG-2").css({ "background-color": "#cac8c6" });

  if (document.getElementById("cmbEstatusProducto").value == 1) {
    document.getElementById("cmbEstatusProducto").style.background = "#28c67a";
    document.getElementById("cmbEstatusProducto").style.color = "#FFFFFF";
  } else {
    document.getElementById("cmbEstatusProducto").style.background = "#cac8c6";
  }
}

//Funciones para los eventos de los elementos de la página
/*function mostrarImagen(file){

  html = `<div class="mb-4">
            <img class="z-depth-1-half" src="../../../../img/timdesk/ICONO AGREGAR2_Mesa de trabajo 1.svg"
              alt="example placeholder" id="imgProd" name="imgProd" width="350px" height="350px" style="display:block; margin:auto;">
          </div>`;
  
  $('#espacioImagen').html(html);

  document.getElementById('imgProd').src = window.URL.createObjectURL(file);

}*/

/* VALIAR QUE NO SE REPITA LA CLAVE INTERNA y Nombre AGREGADA POR EL USUARIO EN AGREGAR */
function escribirNombre() {
  var valor = document.getElementById("txtNombre").value;
  console.log("Valor nombre: " + valor);
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "validar_nombre", data: valor },
    dataType: "json",
    success: function (data) {
      console.log("respuesta nombre valida: ", data);
      /* Validar si ya existe el identificador con ese nombre*/
      if (parseInt(data[0]["existe"]) == 1) {
        $("#invalid-nombreProd").css("display", "block");
        $("#invalid-nombreProd").text("El nombre ya esta en el registro.");
        $("#txtNombre").addClass("is-invalid");
      } else {
        if (!valor) {
          $("#invalid-nombreProd").css("display", "block");
          $("#invalid-nombreProd").text("El producto debe tener un nombre.");
          $("#txtNombre").addClass("is-invalid");
        } else {
          $("#invalid-nombreProd").css("display", "none");
          $("#txtNombre").removeClass("is-invalid");
        }
      }
    },
  });
}

function escribirClave() {
  var valor = $("#txtClaveInterna").val();
  console.log("Valor clave" + valor);
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "validar_claveInterna", data: valor },
    dataType: "json",
    success: function (data) {
      console.log("respuesta clave interna valida: ", data);
      /* Validar si ya existe el identificador con ese nombre*/
      if (parseInt(data[0]["existe"]) == 1) {
        $("#invalid-claveProd").css("display", "block");
        $("#invalid-claveProd").text(
          "El producto debe tener una clave interna."
        );
        $("#txtClaveInterna").addClass("is-invalid");
      } else {
        if (!valor) {
          $("#invalid-claveProd").css("display", "block");
          $("#invalid-claveProd").text("El producto debe tener un nombre.");
          $("#txtClaveInterna").addClass("is-invalid");
        } else {
          $("#invalid-claveProd").css("display", "none");
          $("#txtClaveInterna").removeClass("is-invalid");
        }
      }
    },
  });
}

/* VALIAR QUE NO SE REPITA EL CÓDIGO DE BARRAS AGREGADO POR EL USUARIO EN AGREGAR */
function escribirCodigo() {
  var valor = document.getElementById("txtCodigoBarras").value;
  console.log("Valor codigo" + valor);
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "validar_codigoBarras", data: valor },
    dataType: "json",
    success: function (data) {
      console.log("respuesta código de barras valido: ", data);
      /* Validar si ya existe el identificador con ese nombre*/
      if (parseInt(data[0]["existe"]) == 1) {
        $("#invalid-codigoProd").css("display", "block");
        $("#txtCodigoBarras").addClass("is-invalid");
        console.log("¡Ya existe!");
      } else {
        $("#invalid-codigoProd").css("display", "none");
        $("#txtCodigoBarras").removeClass("is-invalid");
        console.log("¡No existe!");
      }
    },
  });
}

function validarCostoVenta() {
  var compra = document.getElementById("txtCostoUniCompra").value;
  var venta = document.getElementById("txtCostoUniVenta").value;

  console.log("Compra:" + compra + " Venta: " + venta);

  if (parseInt(compra) > parseInt(venta)) {
    $("#notaCostoVenta").css("display", "block");
  } else {
    $("#notaCostoVenta").css("display", "none");
  }
}

function validarStockMaximo() {
  var maximo = document.getElementById("txtStockMax").value;
  var minimo = document.getElementById("txtStockMin").value;
  if (maximo && minimo) {
    if (parseInt(minimo) >= parseInt(maximo)) {
      $("#invalid-stockMinProd").css("display", "block");
      $("#invalid-stockMinProd").text(
        "El stock minimo no puede ser mayor o igual al maximo"
      );
      $("#txtStockMin").addClass("is-invalid");
    } else {
      $("#invalid-stockMinProd").css("display", "none");
      $("#invalid-stockMinProd").text(
        "El inventario debe tener un stock minimo."
      );
      $("#txtStockMin").removeClass("is-invalid");
      if (maximo) {
        $("#invalid-stockMaxProd").css("display", "none");
        $("#txtStockMax").removeClass("is-invalid");
      }
    }
  } else {
    if (!minimo) {
      $("#invalid-stockMinProd").css("display", "block");
      $("#invalid-stockMinProd").text(
        "El inventario debe tener un stock minimo."
      );
      $("#txtStockMin").addClass("is-invalid");
    } else {
      $("#invalid-stockMinProd").css("display", "none");
      $("#invalid-stockMinProd").text("");
      $("#txtStockMin").removeClass("is-invalid");
    }
    if (!maximo) {
      $("#invalid-stockMaxProd").css("display", "block");
      $("#invalid-stockMaxProd").css(
        "El inventario debe tener un stock maximo."
      );
      $("#txtStockMax").addClass("is-invalid");
    } else {
      $("#invalid-stockMaxProd").css("display", "none");
      $("#txtStockMax").removeClass("is-invalid");
    }
  }

  validarStockExistencia();
  validarPuntoReorden();
}

function validarStockExistencia() {
  var existencia = document.getElementById("txtStockExi").value;
  if (existencia) {
    $("#invalid-stockProd").css("display", "none");
    $("#invalid-stockProd").text("El inventario debe tener un stock.");
    $("#txtStockExi").removeClass("is-invalid");
  } else {
    $("#invalid-stockProd").css("display", "block");
    $("#invalid-stockProd").text("El inventario debe tener un stock.");
    $("#txtStockExi").addClass("is-invalid");
  }
}

function validarPuntoReorden() {
  var minimo = document.getElementById("txtStockMin").value;
  var reorden = document.getElementById("txtReorden").value;

  if (parseInt(reorden) > parseInt(minimo)) {
    $("#invalid-reordenProd").css("display", "block");
    $("#invalid-reordenProd").text(
      "El punto de reorden no puede ser mayor al stock minimo."
    );
    $("#txtReorden").addClass("is-invalid");
  } else {
    $("#invalid-reordenProd").css("display", "none");
    $("#invalid-reordenProd").text(
      "El inventario debe tener un punto de reorden."
    );
    $("#txtReorden").removeClass("is-invalid");
    if (!reorden) {
      console.log("No hay reorden");
      $("#invalid-reordenProd").css("display", "block");
      $("#invalid-reordenProd").text(
        "El inventario debe tener un punto de reorden."
      );
      $("#txtReorden").addClass("is-invalid");
    }
  }
}

/*----------------------Botón agregar producto-------------------------------*/

//Validar que se hayan completado todos los campos antes de pasar a la siguiente pestaña
$(document).on("click", "#btnGenerarClave", function () {
  /*var palabras = $("#txtNombre").val();
  var array = palabras.split(" ");
  var total = array.length;
  var resultado = "";
  var limpieza = "";
  if (palabras != "") {
    $("#invalid-claveProd").css("display", "none");
    $("#invalid-claveProd").text("El producto debe tener una clave interna.");
    $("#txtClaveInterna").removeClass("is-invalid");
    for (var i = 0; i < total; i++) {
      if (array[i][0] != null && i < 5) {
        resultado += array[i][0];
        console.log(array[i][0]);
      }
    }
    limpieza = resultado.toUpperCase().replace(/[.*+?^${}()|[\]\\]/g, "");

    $.ajax({
      url: "../../php/funciones.php",
      data: { clase: "get_data", funcion: "get_claveReferencia" },
      dataType: "json",
      success: function (respuesta) {
        $("#txtClaveInterna").val(limpieza + "" + respuesta);
        $("#notaFNombre").css("display", "none");
      },
      error: function (error) {
        console.log(error);
      },
    });
  } else {
    $("#invalid-claveProd").css("display", "block");
    $("#invalid-claveProd").text(
      "El campo nombre debe tener un valor valido para generar clave"
    );
    $("#txtClaveInterna").addClass("is-invalid");
  }*/

  var categoria = $("#cmbTipoProducto").val();
  var categoriaTexto = $("#cmbTipoProducto option:selected").html();
  var limpieza = "";

  if (categoria == "1") {
    limpieza = "Cmp";
  } else if (categoria == "2") {
    limpieza = "Cns";
  } else if (categoria == "3") {
    limpieza = "MP";
  } else if (categoria == "4") {
    limpieza = "P";
  } else if (categoria == "5") {
    limpieza = "S";
  } else if (categoria == "6") {
    limpieza = "AF";
  } else if (categoria == "7") {
    limpieza = "A";
  } else if (categoria == "8") {
    limpieza = "SI";
  } else if (categoria == "9") {
    limpieza = "EMP";
  }else if (categoria == "10") {
    limpieza = "GF";
  } else {
    limpieza = "N";
  }

  if (limpieza != "N") {
    $.ajax({
      url: "../../php/funciones.php",
      data: { clase: "get_data", funcion: "get_claveReferencia" },
      dataType: "json",
      success: function (respuesta) {
        $("#txtClaveInterna").val(limpieza + "" + respuesta);
        $("#invalid-claveProd").css("display", "none");
        $("#txtClaveInterna").removeClass("is-invalid");
      },
      error: function (error) {
        console.log(error);
      },
    });
  } else {
    $("#invalid-tipoProd").css("display", "block");
    $("#invalid-tipoProd").text(
      "Debe de seleccionarse un tipo de producto para generar clave"
    );
    $("#cmbTipoProducto").addClass("is-invalid");
  }
});

$(document).on("click", "#btnAgregarProducto", function () {
  if ($("#formDatosProducto")[0].checkValidity()) {
    var badNombreProd =
      $("#invalid-nombreProd").css("display") === "block" ? false : true;
    var badClaveProd =
      $("#invalid-claveProd").css("display") === "block" ? false : true;
    var badTipoProd =
      $("#invalid-tipoProd").css("display") === "block" ? false : true;
    var badCodigoProd =
      $("#invalid-codigoProd").css("display") === "block" ? false : true;
    var badCostpUnitProd =
      $("#invalid-costoUnitProd").css("display") === "block" ? false : true;
    var badCostoVentaProd =
      $("#invalid-costoVentaProd").css("display") === "block" ? false : true;
    var badCostoFabrProd =
      $("#invalid-costoFabrProd").css("display") === "block" ? false : true;

    var badSerie =
      $("#invalid-serie").css("display") === "block" ? false : true;
    var badLote = $("#invalid-lote").css("display") === "block" ? false : true;
    var badCaducidad =
      $("#invalid-caducidad").css("display") === "block" ? false : true;

    if(parseInt($("#txtIDUnidadSAT").val()) == 1 || isNaN($("#txtIDUnidadSAT").val())){
      $("#invalid-claveUnidad").css("display", "block");
      $("#cmbUnidadSAT").addClass("is-invalid");
    }

    var badUnidad =
      $("#invalid-claveUnidad").css("display") === "block" ? false : true;
  
    if (
      badNombreProd &&
      badClaveProd &&
      badTipoProd &&
      badCodigoProd &&
      badCostpUnitProd &&
      badCostoVentaProd &&
      badCostoFabrProd &&
      badSerie &&
      badLote &&
      badCaducidad && 
      badUnidad
    ) {
      var CostoCompra, CostoVenta, CostoFabricacion, $costoGastoF;

      if (
        $("#txtCostoUniCompra").val() == "" ||
        $("#txtCostoUniCompra").val() == null
      ) {
        CostoCompra = 0;
      } else {
        CostoCompra = $("#txtCostoUniCompra").val();
      }

      if (
        $("#txtCostoUniVenta").val() == "" ||
        $("#txtCostoUniVenta").val() == null
      ) {
        CostoVenta = 0;
      } else {
        CostoVenta = $("#txtCostoUniVenta").val();
      }

      if (
        $("#txtCostoUniFabri").val() == "" ||
        $("#txtCostoUniFabri").val() == null
      ) {
        CostoFabricacion = 0;
      } else {
        CostoFabricacion = $("#txtCostoUniFabri").val();
      }

      if (
        $("#txtCostoUniGastoF").val() == "" ||
        $("#txtCostoUniGastoF").val() == null
      ) {
        costoGastoF = 0;
      } else {
        costoGastoF = $("#txtCostoUniGastoF").val();
      }

      var datos = {
        pkUsuario: $("#PKUsuario").val(),
        nombre: $("#txtNombre").val(),
        claveInterna: $("#txtClaveInterna").val(),
        codigoBarra: $("#txtCodigoBarras").val(),
        categoria: $("#cmbCategoriaProducto").val(),
        marca: $("#cmbMarcaProducto").val(),
        descripcion: $("#txtDescripcionLarga").val(),
        tipo: $("#cmbTipoProducto").val(),
        estatus: $("#activeProducto").is(":checked") ? 1 : 0,
        fotografia: $("#imgFile").val() ? 1 : 0,
        compra: {
          active: $("#cbxCompra").is(":checked") ? 1 : 0,
          costo: CostoCompra,
          moneda: $("#cmbCostoUniCompra").val(),
        },
        venta: {
          active: $("#cbxVenta").is(":checked") ? 1 : 0,
          costo: CostoVenta,
          moneda: $("#cmbCostoUniVenta").val(),
        },
        fabricacion: {
          active: $("#cbxFabricacion").is(":checked") ? 1 : 0,
          costo: CostoFabricacion,
          moneda: $("#cmbCostoUniFabri").val(),
        },
        gastoFijo: {
          active: $("#cbxGastoFijo").is(":checked") ? 1 : 0,
          costo: costoGastoF,
          moneda: $("#cmbCostoUniGastoF").val(),
        },
        serie: {
          active: $("#cbxSerie").is(":checked") ? 1 : 0,
          /*serie: $("#txtSerie").val(),*/
        },
        lote: {
          active: $("#cbxLote").is(":checked") ? 1 : 0,
          /*lote: $("#txtLotes").val(),*/
        },
        caducidad: {
          active: $("#cbxCaducidad").is(":checked") ? 1 : 0,
          /*caducidad: $("#txtCaducidad").val(),*/
        },
        unidadMedida: parseInt($("#txtIDUnidadSAT").val()),
      };

      $.ajax({
        url: "../../php/funciones.php",
        data: {
          clase: "save_data",
          funcion: "save_datosProducto",
          datos,
        },
        dataType: "json",
        success: function (respuesta) {
          console.log(respuesta);
          if (respuesta[0].status) {
            Lobibox.notify("success", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../../img/timdesk/checkmark.svg",
              msg: "¡Datos del producto registrados correctamente!",
              sound: "../../../../../sounds/sound4",
            });
            console.log(respuesta[0].id, $("#cmbTipoProducto").val());
            subirImagen(respuesta[0].id);

            var venta = 0,
              compra = 0;
            if ($("#cbxVenta").is(":checked")) {
              venta = 1;
            } else {
              venta = 0;
            }

            if ($("#cbxCompra").is(":checked")) {
              compra = 1;
            } else {
              compra = 0;
            }

            SeguirDatosImpuestos(respuesta[0].id, venta, compra);
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
            sound: "../../../../../sounds/sound4",
          });
        },
      });
    }
  } else {
    if (!$("#txtNombre").val()) {
      $("#invalid-nombreProd").css("display", "block");
      $("#txtNombre").addClass("is-invalid");
    }
    if (!$("#txtClaveInterna").val()) {
      $("#invalid-claveProd").css("display", "block");
      $("#txtClaveInterna").addClass("is-invalid");
    }
    if (!$("#cmbTipoProducto").val()) {
      $("#invalid-tipoProd").css("display", "block");
      $("#cmbTipoProducto").addClass("is-invalid");
    }
    if ($("#cbxCompra").is(":checked")) {
      if (!$("#txtCostoUniCompra").val()) {
        $("#invalid-costoUnitProd").css("display", "block");
        $("#txtCostoUniCompra").addClass("is-invalid");
      }
    }
    if ($("#cbxVenta").is(":checked")) {
      if (!$("#txtCostoUniVenta").val()) {
        $("#invalid-costoVentaProd").css("display", "block");
        $("#txtCostoUniVenta").addClass("is-invalid");
      }
    }
    if ($("#cbxFabricacion").is(":checked")) {
      if (!$("#txtCostoUniFabri").val()) {
        $("#invalid-costoFabrProd").css("display", "block");
        $("#txtCostoUniFabri").addClass("is-invalid");
      }
    }
    /*if ($("#cbxSerie").is(":checked")) {
      if (!$("#txtSerie").val()) {
        $("#invalid-serie").css("display", "block");
        $("#txtSerie").addClass("is-invalid");
      }
    }
    if ($("#cbxLote").is(":checked")) {
      if (!$("#txtLotes").val()) {
        $("#invalid-lote").css("display", "block");
        $("#txtLotes").addClass("is-invalid");
      }
    }
    if ($("#cbxCaducidad").is(":checked")) {
      if (!$("#txtCaducidad").val()) {
        $("#invalid-caducidad").css("display", "block");
        $("#txtCaducidad").addClass("is-invalid");
      }
    }*/
    if(parseInt($("#txtIDUnidadSAT").val()) == 1 || !isNaN($("#txtIDUnidadSAT").val())){
      $("#invalid-claveUnidad").css("display", "block");
      $("#cmbUnidadSAT").addClass("is-invalid");
    }
  }
});

function regresarDatosProducto() {
  window.location.href = "agregar_producto.php";
}

function subirImagen(id) {
  var imagen = $("#imagenSubir").val();

  $("#imagenSubir").val("");

  console.log("Ruta imagen: " + imagen);
  eliminarImgTemp(imagen, id);
}

function eliminarImgTemp(response, id) {
  $.ajax({
    url: "deleteTemp.php",
    type: "POST",
    data: { image: response, id: id },
    success: function (data) {
      console.log("Temporal borrado");
    },
  });
}

function SeguirDatosImpuestos(id, isVenta, isCompra) {
  validarEmpresaProducto(id);

  //$('#datos').load('datos_impuestos.php',{idProducto : idProd});
  resetTabs("#CargarDatosImpuestos");

  cargarCMBImpuestos("1", "cmbImpuestos");
  cargarCMBTasaImpuestos("1", "cmbTasaImpuestos");

  var html = `<div class="card shadow mb-4">
                <div class="card-header">
                  Tarjeta de impuestos
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-12">
                      <form id="formDatosImpuesto"> 
                        <input type='hidden' value='${id}' name="txtPKProducto" id="txtPKProducto">
                        <span id="areaDiseno">
                        <div class="form-group">
                          <div class="row">
                            <div class="col-lg-5">
                              <label for="usr">Clave SAT:</label>
                              <input  name="txtIDClaveSAT" id="txtIDClaveSAT" type="hidden" value="1" readonly>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input type="text" value="S/C - Sin Clave" class="form-control" name="cmbClaveSAT" id="cmbClaveSAT" data-toggle="modal" data-target="#agregar_ClaveSAT" 
                                  placeholder="Seleccione una clave..." readonly required="" style="margin-right: 1rem!important" >
                                  <img  id="notaFClaveSAT" name="notaFClaveSAT" style="display: none;"
                                  src="../../../../img/timdesk/alerta.svg" width=30px
                                  title="Campo requerido" readonly>
                                  <a href="#" class="btn-custom btn-custom--border-blue" style="display:none" id="btnAgregarImpuesto_ProdAdd" 
                                    onclick="guardarDatosImpuesto(${id})">Guardar clave SAT
                                  </a>
                                </div>
                              </div>
                            </div>
                            <br><br><br><br>
                          </div>
                        </div>

                        <div class="form-group">
                          <div class="row mt-3">
                            <div class="col-lg-3">
                              <label for="usr">Impuesto:</label>
                              <select class="cmbSlim" name="cmbImpuestos" id="cmbImpuestos" required="" onchange="cambioImpuesto(${id})">
                              </select>
                              <input class="form-control" id="notaImpuesto" name="notaImpuesto" type="hidden"
                              style="color: darkred; background-color: transparent!important; border: none;"
                              value="Nota: El impuesto ya ha sido agregado." readonly>
                            </div>
                            <div class="col-lg-3">
                              <label for="usr" id="etiquetaImpuesto">Tasa:</label>
                              <input type='hidden' value='1' name="txtTipoTasa" id="txtTipoTasa">
                              <span id="areaimpuestos">
                                <select class="cmbSlim" name="cmbTasaImpuestos" id="cmbTasaImpuestos" required="">
                                </select> 
                              </span>   
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-lg-3" style="margin-top:20px;">
                              <a href="#" class="btn-custom btn-custom--border-blue" id="btnAnadirImpuesto" onclick="validarImpuesto(${id})">Añadir impuesto</a>
                              <a href="#" class="btn-custom btn-custom--blue float-right" id="btnAgregarImpuesto" 
                                  onclick="SeguirPestanaInventario(${id}, ${isVenta}, ${isCompra})">Continuar
                              </a>
                            </div>
                          </div>
                          <br>
                          <input type='hidden' value='1' name="txtTipoImpuesto" id="txtTipoImpuesto">
                          <label for="">* Campos requeridos</label>
                        </div>
                        <br><br><br>

                        <div class="form-group">
                          <!-- DataTales Example -->
                          <div class="card mb-4 internal-table">
                            <div class="card-body">
                              <div class="table-responsive">
                                <table class="table" id="tblListadoImpuestosProducto" width="100%" cellspacing="0">
                                  <thead>
                                    <tr>
                                      <th>Id</th>
                                      <th>Impuesto</th>
                                      <th>Tipo</th>
                                      <th>Tasa</th>
                                      <th></th>
                                    </tr>
                                  </thead>
                                </table>
                              </div>
                            </div>
                          </div>
                        </div>
                        </span>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
              <input type='hidden' value='${isVenta}' name="txtIsVenta" id="txtIsVenta">
              <input type='hidden' value='${isCompra}' name="txtIsCompra" id="txtIsCompra">
              `;

  $("#datos").html(html);

  cargarTablaImpuestos(id, 1);
}

/*----------------------Funciones de carga de datos de los combos-------------------------------*/
function cargarCMBImpuestos(data, input) {
  var html = "";
  var selected;
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_impuestos" },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta impuestos: ", respuesta);

      $.each(respuesta, function (i) {
        if (data === respuesta[i].PKImpuesto) {
          selected = "selected";
        } else {
          selected = "";
        }

        html +=
          '<option value="' +
          respuesta[i].PKImpuesto +
          '" ' +
          selected +
          ' data-tipo="' +
          respuesta[i].FKTipoImpuesto +
          '" data-importe="' +
          respuesta[i].FKTipoImporte +
          '">' +
          respuesta[i].Nombre +
          "</option>";
      });

      CargarSlimImpuestos();

      $("#" + input + "").html(html);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function cargarCMBTasaImpuestos(data, input) {
  var valor = data;
  console.log("PKImpuestos: " + valor);

  var html = "";
  var selected;

  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_tasa_impuestos", data: valor },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta tasas: ", respuesta);

      $.each(respuesta, function (i) {
        if (data === respuesta[i].PKImpuesto_tasas) {
          selected = "selected";
        } else {
          selected = "";
        }

        html +=
          '<option value="' +
          respuesta[i].PKImpuesto_tasas +
          '" ' +
          selected +
          ">" +
          respuesta[i].Tasa +
          "</option>";
      });

      $("#" + input + "").html(html);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

/*----------------------Plugin Slim para la búsqueda dentro del-------------------------------*/
function CargarSlimImpuestos() {
  new SlimSelect({
    select: "#cmbImpuestos",
    deselectLabel: '<span class="">✖</span>',
  });

  CargarSlimTasaImpuestos();
}

function CargarSlimTasaImpuestos() {
  new SlimSelect({
    select: "#cmbTasaImpuestos",
    deselectLabel: '<span class="">✖</span>',
  });
}

function CargarSlimProductos() {
  new SlimSelect({
    select: "#cmbProductos",
    deselectLabel: '<span class="">✖</span>',
  });
}

/*----------------------Cambio de seleccion de impuesto-------------------------------*/
function cambioImpuesto(producto) {
  var FKImpuesto = document.getElementById("cmbImpuestos").value;
  cargarCMBTasaImpuestos(FKImpuesto, "cmbTasaImpuestos");

  var tipo =
    document.getElementById("cmbImpuestos").options[
      document.getElementById("cmbImpuestos").selectedIndex
    ].dataset.tipo;
  var importe =
    document.getElementById("cmbImpuestos").options[
      document.getElementById("cmbImpuestos").selectedIndex
    ].dataset.importe;

  console.log("Tipo:" + tipo);
  console.log("Importe:" + importe);

  if (tipo == 1) {
   /*  $("#trasladado").css("display", "block");
    $("#retenciones").css("display", "none");
    $("#local").css("display", "none"); */
    $("#txtTipoImpuesto").val("1");
  }
  if (tipo == 2) {
    /* $("#trasladado").css("display", "none");
    $("#retenciones").css("display", "block");
    $("#local").css("display", "none"); */
    $("#txtTipoImpuesto").val("2");
  }
  if (tipo == 3) {
   /*  $("#trasladado").css("display", "none");
    $("#retenciones").css("display", "none");
    $("#local").css("display", "block"); */
    $("#txtTipoImpuesto").val("3");
  } 

  $("#cmbTasaImpuestos").attr("readonly", false);

  var select = `<select class="cmbSlim" name="cmbTasaImpuestos" id="cmbTasaImpuestos" required="">
              </select> `;

  var inputNumber = `<input type='number' min='0' value='' name='cmbTasaImpuestos' id='cmbTasaImpuestos' class='form-control'>`;

  if (importe == 1) {
    $("#etiquetaImpuesto").text("Tasa:");
    $("#areaimpuestos").html(select);
    CargarSlimTasaImpuestos();
  }
  if (importe == 2) {
    $("#etiquetaImpuesto").text("Importe:");
    $("#areaimpuestos").html(inputNumber);
  }
  if (importe == 3) {
    $("#etiquetaImpuesto").html("Tasa:");
    $("#areaimpuestos").html(inputNumber);
    $("#cmbTasaImpuestos").attr("readonly", true);
  }

  $("#txtTipoTasa").val(importe);

  console.log("Valor impuesto" + FKImpuesto);
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "validar_impuestoProducto",
      data: producto,
      data2: FKImpuesto,
    },
    dataType: "json",
    success: function (data) {
      console.log("respuesta impuesto validado: ", data);
      /* Validar si ya existe el identificador con ese nombre*/
      if (parseInt(data[0]["existe"]) == 1) {
        /* var nota = document.getElementById("notaImpuesto");
        nota.setAttribute("type", "text"); */
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/notificacion_error.svg",
          msg: "El impuesto ya ha sido agregado.",
          sound: "../../../../../sounds/sound4",
        });
        console.log("¡Ya existe!");
      } else if (parseInt(data[0]["existe"]) == 2) {
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/notificacion_error.svg",
          msg: "No es posible añadir el impuesto excento.",
          sound: "../../../../../sounds/sound4",
        });
      } else if (parseInt(data[0]["existe"]) == 3) {
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/notificacion_error.svg",
          msg: "Ya se posee un impuesto de tipo excento.",
          sound: "../../../../../sounds/sound4",
        });
      } else {
        var nota = document.getElementById("notaImpuesto");
        nota.setAttribute("type", "hidden");

        console.log("¡No existe!");
      }
    },
  });
}

/*----------------------------Botón añadir impuesto ---------------------------*/
/* VALIAR QUE NO SE REPITA EL IMPUESTO POR PRODUCTO AGREGADO POR EL USUARIO EN AGREGAR */
function validarImpuesto(id) {
  var valor = document.getElementById("cmbImpuestos").value;
  console.log("Valor impuesto" + valor);
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "validar_impuestoProducto",
      data: id,
      data2: valor,
    },
    dataType: "json",
    success: function (data) {
      console.log("respuesta impuesto validado: ", data);
      /* Validar si ya existe el identificador con ese nombre*/
      if (parseInt(data[0]["existe"]) == 1) {
        /* var nota = document.getElementById("notaImpuesto");
        nota.setAttribute("type", "text"); */
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/notificacion_error.svg",
          msg: "El impuesto ya ha sido agregado.",
          sound: "../../../../../sounds/sound4",
        });
      } else if (parseInt(data[0]["existe"]) == 2) {
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/notificacion_error.svg",
          msg: "No es posible añadir el impuesto excento.",
          sound: "../../../../../sounds/sound4",
        });
      } else if (parseInt(data[0]["existe"]) == 3) {
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/notificacion_error.svg",
          msg: "Ya se posee un impuesto de tipo excento.",
          sound: "../../../../../sounds/sound4",
        });
      } else {
        var nota = document.getElementById("notaImpuesto");
        nota.setAttribute("type", "hidden");
        anadirImpuesto();

        console.log("¡No existe!");
      }
    },
  });
}

/* Añadir el impuesto */
function anadirImpuesto() {
  var data = [];
  //El serializeArray obtiene un id consecutivo de los elementos fiel que posee el formulario
  $.each($("#formDatosImpuesto").serializeArray(), function (i, field) {
    data.push({ id: i, campos: field.name, datos: field.value });
  });

  console.log(data);
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "save_data", funcion: "save_datosImpuesto", datos: data },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta agregar datos generales del producto:", respuesta);

      if (respuesta[0].status) {
        $("#tblListadoImpuestosProducto").DataTable().ajax.reload();
        Lobibox.notify("success", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/checkmark.svg",
          msg: "¡Impuesto registrado correctamente!",
          sound: "../../../../../sounds/sound4",
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

/* recupera id del inpuesto a eliminar para modal */
function get_impuestoProduct(id){
  document.getElementById("txthideidInpuesto").value = id;
}

/* Eliminar el impuesto */
function eliminarImpuesto(impuestoProducto) {
  console.log("ID del impuestoproducto: " + impuestoProducto);
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "delete_data",
      funcion: "delete_impuesto_producto",
      datos: impuestoProducto,
    },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta eliminar impuestoProducto:", respuesta);

      if (respuesta[0].status) {
        $("#tblListadoImpuestosProducto").DataTable().ajax.reload();
        Lobibox.notify("success", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/checkmark.svg",
          msg: "¡Se eliminó el impuesto con éxito!",
          sound: "../../../../../sounds/sound4",
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
          sound: "../../../../../sounds/sound4",
        });
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
}

//Validar que se hayan completado todos los campos antes de pasar a la siguiente pestaña
function guardarDatosImpuesto(id) {
  var fkClave = $("#txtIDClaveSAT").val();
  //var fkUnidad = $("#txtIDUnidadSAT").val();

  if (fkClave == "") {
    fkClave = 0;
  }

  /* if (fkUnidad == "") {
    fkUnidad = 0;
  } */

  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "save_data",
      funcion: "save_datosFiscales",
      datos: fkClave,
      /* datos2: fkUnidad, */
      datos3: id,
    },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta agregar datos generales del producto:", respuesta);

      if (respuesta[0].status) {
        Lobibox.notify("success", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/checkmark.svg",
          msg: "¡Se guardo con éxito los datos SAT!",
          sound: "../../../../../sounds/sound4",
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

  // SeguirTipoProveedor(id);
}

let sucursal = '';
var loteGLB = 0;

function SeguirPestanaInventario(id, isVenta, isCompra) {

  resetTabs("#CargarDatosInventario");

  let nombre = '';
  let tipo = '';
  let clave = '';
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_datosProducto",
      datos: id
    },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta de consultar datos generales del producto: ", respuesta);
      nombre = respuesta[0].Nombre;
      switch(respuesta[0].FKTipoProducto){
        case 1:
          tipo = 'Compuesto'
        break;
        case 2:
          tipo = 'Consumible'
        break;
        case 3:
          tipo = 'Materia prima'
        break;
        case 4:
          tipo = 'Producto'
        break;
        case 5:
          tipo = 'Servicio'
        break;
        case 6:
          tipo = 'Activo fijo'
        break;
        default:
          tipo = 'Producto'
      }
      clave = respuesta[0].ClaveInterna;

      var date = new Date();
      date.setDate(date.getDate() + 1);
      var mes = date.getMonth() + 1;
      var dia = date.getDate();
      switch (dia.toString().length | mes.toString().length) {
        case 1 | 2:
          var fecha =
            date.getFullYear() +
            "-" +
            (date.getMonth() + 1) +
            "-0" +
            date.getDate();
          break;
        case 2 | 1:
          var fecha =
            date.getFullYear() +
            "-0" +
            (date.getMonth() + 1) +
            "-" +
            date.getDate();
          break;
        case 1 | 1:
          var fecha =
            date.getFullYear() +
            "-0" +
            (date.getMonth() + 1) +
            "-0" +
            date.getDate();
          break;
        case 2 | 2:
          var fecha =
            date.getFullYear() +
            "-" +
            (date.getMonth() + 1) +
            "-" +
            date.getDate();
          break;
      }
      
      var html =
      `<div class="card shadow mb-4">
          <div class="card-header">
            Tarjeta de inventario
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-lg-12">
                <form id="formDatosInventarios"> 
                  <input type='hidden' value='` +
                  id +
                  `' name="txtPKProductoInventario" id="txtPKProductoInventario">
                  <div class="form-group">
                    <div class="row">
                      <div class="col-lg-3 textData">
                        <b class="textBlue" for="fe">Nombre: </b><div id="fe"><span id="txtNombre">${nombre}</span></div><p></p>
                      </div>
                      <div class="col-lg-3 textData">
                        <b class="textBlue" for="fe">Tipo: </b><div id="fe"><span id="txtTipo">${tipo}</span></div><p></p>
                      </div>
                      <div class="col-lg-3 textData">
                        <b class="textBlue" for="fe">Clave </b><div id="fe"><span id="txtClave">${clave}</span></div><p></p>
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-lg-3">
                        <label for="usr">Sucursal:*</label>
                        <div class="row">
                          <div class="col-lg-12 input-group">
                            <span style="width:100%; margin-top:-2px;">
                              <select name="cmbSucursal" id="cmbSucursal" required>
                              </select>   
                            </span>
                            <div class="invalid-feedback" id="invalid-sucursalInventario">Elige una sucursal.</div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <br>
                  <div class="form-group">
                    <div class="row">
                      <div class="col-lg-3">
                        <label for="usr">Cantidad:*</label>
                        <div class="row">
                          <div class="col-lg-12 input-group">
                            <input class="form-control numeric-only" type="number" name="txtCantidadInventario" id="txtCantidadInventario" min="0" placeholder="Ej. 5" oninput="limitarLongitud(this)" required>
                            <div class="invalid-feedback" id="invalid-cantidadInventario">Introuce una cantidad.</div>
                          </div>
                        </div>    
                      </div>
                      <div class="col-lg-3">
                        <label for="usr">Lote:</label>
                        <div class="row">
                          <div class="col-lg-12 input-group">
                            <input class="form-control" type="text" name="txtLoteInventario" id="txtLoteInventario" placeholder="Ej. LSF3R5">
                            <div class="invalid-feedback" id="invalid-loteInventario">Introduce un lote.</div>
                          </div>
                        </div>    
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-lg-3">
                        <label for="usr">Caducidad:</label>
                        <div class="row">
                          <div class="col-lg-12 input-group">
                            <input class="form-control" type="date" name="dtpCaducidadInventario" id="dtpCaducidadInventario" min="${fecha}">
                            <div class="invalid-feedback" id="invalid-caducidadInventario">Introduce una caducidad.</div>
                          </div>
                        </div>  
                      </div>
                    </div>
                  </div>
                  <br>
                  <label for="">* Campos requeridos</label>
                  <a class="btn-custom btn-custom--border-blue float-right" onclick="agregarInventario()">Agregar inventario</a>
                  <br>
                  <br>
                  <div class="form-group">
                    <!-- DataTales Example -->
                    <div class="card mb-4 internal-table">
                      <div class="card-body">
                        <div class="table-responsive">
                          <table class="table" id="tblListadoInventario" width="100%" cellspacing="0">
                            <thead>
                              <tr>
                                <th>id</th>
                                <th>Sucursal</th>
                                <th>Cantidad</th>
                                <th>Lote</th>
                                <th>Caducidad</th>
                              </tr>
                            </thead>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                 </form>
                <br>
                <div class="float-right">
                  <a href="#" style="margin-right:10px;" class="btn-custom btn-custom--border-blue" id="btnAgregarTipoProducto" onclick="guardarDatosPestanaInventario(` +
                  id +
                  `)">Guardar</a>
                  <a href="#" class="btn-custom btn-custom--blue float-right" 
                      onclick="validarIsCompra(${id})">Continuar
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
        <input type='hidden' value='${isVenta}' name="txtIsVenta" id="txtIsVenta">
        <input type='hidden' value='${isCompra}' name="txtIsCompra" id="txtIsCompra">`;
  
    $("#datos").html(html);
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
        sNext: "<i class='fas fa-chevron-right'></i>",
        sPrevious: "<i class='fas fa-chevron-left'></i>",
      },
    };
    $("#tblListadoInventario").DataTable({
      language: idioma_espanol,
      info: false,
      scrollX: true,
      paging: false,
      responsive: true,
      lengthChange: false,
      dom: `<"container-fluid"<"row mb-3"<"col-sm-12 col-md-9 p-0 d-flex align-items-center"B><"col-sm-12 col-md-3 p-0"f>>>rti
      <"container-fluid mt-4"<"row"<"#btn-filters.col-sm-12 col-md-10 p-0"><"col-sm-12 col-md-2 p-0"p>>>`,
      buttons: {
        dom: {
          button: {
            tag: "button",
            className: "btn-table-custom",
          },
          buttonLiner: {
            tag: null,
          },
        },
        buttons: [],
      },
      columnDefs: [{ targets: 0, visible: false }],
      columns: [
        { data: "id"},
        { data: "sucursal" },
        { data: "cantidad" },
        { data: "lote" },
        { data: "caducidad" },
      ],
    });

    /* if(respuesta[0].serie == 0){
      $("#txtSerieInventario").prop("disabled", true);
    }else{
      $("#txtSerieInventario").attr("required", true);
    } */
    if(respuesta[0].lote == 0){
      $("#txtLoteInventario").prop("disabled", true);
    }else{
      loteGLB = respuesta[0].lote;
      $("#txtLoteInventario").attr("required", true);
    }
    if(respuesta[0].fecha_caducidad == 0){
      $("#dtpCaducidadInventario").prop("disabled", true);
    }else{
      $("#dtpCaducidadInventario").attr("required", true);
    }
 
    resetValidations();

    sucursal = new SlimSelect({
      select: "#cmbSucursal",
      deselectLabel: '<span class="">✖</span>',
    });

    cargarCMBSucursalesProductos();

    $("#txtCantidadInventario").on("change", ()=>{
      $("#invalid-cantidadInventario").css("display", "none");
      $("#txtCantidadInventario").removeClass("is-invalid");
    });
    $("#txtSerieInventario").on("change", ()=>{
      $("#invalid-serieInventario").css("display", "none");
      $("#txtSerieInventario").removeClass("is-invalid");
    });
    $("#txtLoteInventario").on("change", ()=>{
      $("#invalid-loteInventario").css("display", "none");
      $("#txtLoteInventario").removeClass("is-invalid");
    });
    $("#dtpCaducidadInventario").on("change", ()=>{
      $("#invalid-caducidadInventario").css("display", "none");
      $("#dtpCaducidadInventario").removeClass("is-invalid");
    });

    },
    error: function (error) {
      console.log("error de consultar los datos generales del producto: ", error);
    },
  });
}

//función para validar formulario de agregar inventario
function validaFormularioInventario(){
  if ($("#formDatosInventarios")[0].checkValidity()) {
    var badSucursal =
      $("#invalid-sucursalInventario").css("display") === "block" ? false : true;
    var badCantidad =
      $("#invalid-cantidadInventario").css("display") === "block" ? false : true;
    var badLote = 
      $("#invalid-loteInventario").css("display") === "block" ? false : true;
    var badCaducidad =
      $("#invalid-caducidadInventario").css("display") === "block" ? false : true;

    if (
      badSucursal &&
      badCantidad &&
      badLote &&
      badCaducidad
    ) {
        return true;
      }
    } else { 
      if (!$("#cmbSucursal").val()) {
        $("#invalid-sucursalInventario").css("display", "block");
        $("#cmbSucursal").addClass("is-invalid");
      }

      if (!$("#txtCantidadInventario").val()) {
        $("#invalid-cantidadInventario").css("display", "block");
        $("#txtCantidadInventario").addClass("is-invalid");
      }
      
      if (!$("#txtLoteInventario").val() && $("#txtLoteInventario").prop("disabled") != true) {
        $("#invalid-loteInventario").css("display", "block");
        $("#txtLoteInventario").addClass("is-invalid");
      }

      if (!$("#dtpCaducidadInventario").val() && $("#dtpCaducidadInventario").prop("disabled") != true) {
        $("#invalid-caducidadInventario").css("display", "block");
        $("#dtpCaducidadInventario").addClass("is-invalid");
      }

      return false;
    }
}

// arreglo contenedor de los registros de la pestaña de inventarios al agregar producto
var arrInventarioProd = {};

//función para agregar las existencias al arreglo de inventario
function agregarInventario() {
  if (validaFormularioInventario()) {
      //Obtener valores de los campos
      let sucursal = $("#cmbSucursal").val();
      let cantidad = $("#txtCantidadInventario").val();
      let lote, caducidad ;

      if (!$("#txtLoteInventario").val()) {
        lote = '';
      } else {
        lote = $("#txtLoteInventario").val();
      }

      if (!$("#dtpCaducidadInventario").val()) {
        caducidad = '0000-00-00';
      } else {
        caducidad = $("#dtpCaducidadInventario").val();
      }

      sucursal = sucursal.trim();
      lote = lote.trim();
      let flag = true;

      //valida sucursal y lote para añadir datos a tabla
      for (const property in arrInventarioProd){
        if(arrInventarioProd[property]['sucursal'] == sucursal){
          if(loteGLB != 0){
            if(arrInventarioProd[property]['lote'] == lote){
              Lobibox.notify("warning", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../../../img/timdesk/warning_circle.svg",
                msg: "¡Lote registrado en la sucursal!",
                sound: "../../../../../sounds/sound4",
              });
              flag = false;
              break;
            }
          }else{
            Lobibox.notify("warning", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../../img/timdesk/warning_circle.svg",
              msg: "Sucursal registrada!",
              sound: "../../../../../sounds/sound4",
            });
            flag = false;
            break;
          }
        }
      }; 

      if(!flag){
        return;
      }

      let size = Object.keys(arrInventarioProd).length;

      let arrPropiedadesInventario = {};
      arrPropiedadesInventario["sucursal"] = sucursal;
      arrPropiedadesInventario["cantidad"] = cantidad;
      arrPropiedadesInventario["lote"] = lote;
      arrPropiedadesInventario["caducidad"] = caducidad;

      arrInventarioProd[(size + 1)] = Object.assign({}, arrPropiedadesInventario);

      //agregar row a datatable
      let sucursalText = $('select[name="cmbSucursal"] option:selected').text();
      sucursalText = '<a class="pointer" data-toggle="modal" data-target="#eliminar_inventario" onclick="getExistenciaToDelete('+(size + 1)+')"><span class="textTable">'+ sucursalText +'</span></a>'

      let tableInventarios = $("#tblListadoInventario").DataTable();
      tableInventarios.row.add({
          "id"       :  size + 1,
          "sucursal" : sucursalText,
          "cantidad" : cantidad,
          "lote"     : lote,
          "caducidad": caducidad
      }).draw();

      return;
    }
}

var existenciaToDelete;
function getExistenciaToDelete(id){
  existenciaToDelete = id;
}

//elimina registro del array y de la tabla
function eliminarImventario(){
  delete arrInventarioProd[existenciaToDelete];

  let tableInventarios = $("#tblListadoInventario").DataTable();
  
  tableInventarios
    .rows( function ( idx, data, node ) {
        return data['id'] == existenciaToDelete;
    } )
    .remove()
    .draw();  
    
  existenciaToDelete = 0;
}

function cargarCMBSucursalesProductos(){
  let html = '';
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_cmb_sucursales_productos"
    },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta de consultar las sucursales: ", respuesta);

      $.each(respuesta, function (i) {

        html +=
          '<option value="' +
          respuesta[i].id +
          '">' +
          respuesta[i].sucursal +
          "</option>";
      });

      $("#cmbSucursal").html(html);
    },
    error: function (error) {
      console.log("error de consultar los datos generales del producto: ", error);
    },
  });
}

function limitarLongitud(input){
  if((input.value.length) > 11) { input.value = input.value.substring(0, 11); }
}

//Validar que se hayan completado todos los campos antes de pasar a la siguiente pestaña
function guardarDatosPestanaInventario(id) {
  if (!$.isEmptyObject(arrInventarioProd)){
    $.ajax({
      url: "../../php/funciones.php",
      data: {
        clase: "save_data",
        funcion: "save_datosInventarioProducto",
        data: id,
        data1: arrInventarioProd
      },
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
            msg: "¡Datos del producto registrados correctamente!",
            sound: "../../../../../sounds/sound4",
          });

          validarIsCompra(id);
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
    
  } else {
    Lobibox.notify("warning", {
      size: "mini",
      rounded: true,
      delay: 3000,
      delayIndicator: false,
      position: "center top",
      icon: true,
      img: "../../../../img/timdesk/warning_circle.svg",
      msg: "¡agregar por lo menos una existencia !",
      sound: "../../../../../sounds/sound4",
    });
  }

}

var PKProd;

function validarIsCompra(id) {
  if($.isEmptyObject(arrInventarioProd)){
    $("#continuar_sinGuardar").modal("show");
    PKProd = id;
  }else{
    if ($("#txtIsCompra").val() == 1) {
      SeguirTipoProveedor(id);
    } else {
      if ($("#txtIsVenta").val() == 1) {
        SeguirDatosVenta(id);
      } else {
        TerminarGuardadoDatos();
      }
    }
  } 
}

function continuarSinGuardar(){
  if ($("#txtIsCompra").val() == 1) {
    SeguirTipoProveedor(PKProd);
  } else {
    if ($("#txtIsVenta").val() == 1) {
      SeguirDatosVenta(PKProd);
    } else {
      TerminarGuardadoDatos();
    }
  }
}

function SeguirTipoProveedor(id) {
  var isVenta = $("#txtIsVenta").val();
  var isCompra = $("#txtIsCompra").val();

  validarEmpresaProducto(id);

  resetTabs("#CargarDatosProveedor");

  cargarCMBProveedor("1", "cmbProveedorProducto");
  cargarCMBProveedorEdit("1", "cmbProveedorProductoEdit");
  //cargarCMBUnidadMProveedor('1','cmbUnidadMProveedor');
  cargarCMBMonedaPrecio("", "cmbMonedaPrecio");
  cargarCMBMonedaPrecioEdit("", "cmbMonedaPrecioEdit");

  var html = `<div class="card shadow mb-4">
                <div class="card-header">
                  Tarjeta de proveedor
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-12">
                      <form id="formDatosProveedor"> 
                      <input type='hidden' value='${id}' name="txtPKProductoProveedor" id="txtPKProductoProveedor">
                      <input type='hidden' value='${id}' name="txtPKProductoProveedorEdit" id="txtPKProductoProveedorEdit">

                        <div class="form-group">
                          <div class="row">
                            <div class="col-lg-6">
                              <label for="usr">Proveedor:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <select name="cmbProveedorProducto" id="cmbProveedorProducto" required="" onchange="cambioProveedor()">
                                  </select>
                                  <div class="invalid-feedback" id="invalid-proveedorProd">El proveedor debe tener un nombre.</div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="form-group">
                          <div class="row">
                            <div class="col-lg-4">
                              <label for="usr">Nombre del producto del proveedor:</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control" type="text" name="txtNombreProdProve" id="txtNombreProdProve" maxlength="1000" placeholder="Ej. Bata quirúgica desechable">
                                  <img  id="notaFNombreProdProve" name="notaFNombreProdProve" style="display: none;"
                                  src="../../../../img/timdesk/alerta.svg" width=30px
                                  title="Campo requerido" readonly>
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <label for="usr">Clave del producto del proveedor:</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input type="text" class="form-control" name="txtClaveProdProve" id="txtClaveProdProve" maxlength="50" placeholder="Ej. AA - 0001" onkeyup="escribirClaveProveedor()">
                                  <div class="invalid-feedback" id="invalid-claveProdProv">La clave ya existe para otro proveedor.</div>
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <label for="usr">Precio:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control numericDecimal-only" type="text" name="txtPrecioProdProve" id="txtPrecioProdProve" required="" min="0" maxlength="13" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" placeholder="Ej. 30.00" onkeyup="validEmptyInput('txtPrecioProdProve', 'invalid-precioProd', 'El producto debe tener un precio.')">
                                  <span class="input-group-addon" style="width:100px">
                                    <select name="cmbMonedaPrecio" id="cmbMonedaPrecio" required>
                                    </select>
                                  </span>
                                  <div class="invalid-feedback" id="invalid-precioProd">El producto debe tener un precio.</div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="form-group">
                          <div class="row">
                            <div class="col-lg-4">
                              <label for="usr">Cantidad mínima de compra:</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control numeric-only" type="text" name="txtCantMinProdProve" id="txtCantMinProdProve" min="0" maxlength="12" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" placeholder="Ej. 1000">                   
                                  <img  id="notaFCantMinProdProve" name="notaFCantMinProdProve" style="display: none;"
                                  src="../../../../img/timdesk/alerta.svg" width=30px
                                  title="Campo requerido" readonly>
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <label for="usr">Días de entrega:</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control numeric-only" type="text" name="txtDiasEntregProdProve" id="txtDiasEntregProdProve" min="0" maxlength="3" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" placeholder="Ej. 15">
                                  <img  id="notaFDiasEntregProdProve" name="notaFDiasEntregProdProve" style="display: none;"
                                  src="../../../../img/timdesk/alerta.svg" width=30px
                                  title="Campo requerido" readonly>
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <label for="usr">Unidad de medida:</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control" type="text" name="txtUnidadMedida" id="txtUnidadMedida" min="0" maxlength="50" placeholder="Ej. Caja de 12 piezas">
                                  <img  id="notaFUnidadMProveedor" name="notaFUnidadMProveedor" style="display: none;"
                                  src="../../../../img/timdesk/alerta.svg" width=30px
                                  title="Campo requerido" readonly>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="form-group">
                          <div class="row">
                            <div class="col-12 mt-4 d-flex justify-content-end">
                              <a href="#" class="mr-3 btn-custom btn-custom--border-blue" id="btnAnadirProveedor" onclick="validarProveedor(${id})">Añadir proveedor</a>
                              <a href="#" class="btn-custom btn-custom--blue" id="btnAgregarTipoProducto" onclick="guardarDatosProveedor(${id})">Continuar</a>
                            </div>
                            <p>* Campos requeridos</p>
                          </div>
                        </div>
                        <br>

                        <div class="form-group">
                          <!-- DataTales Example -->
                          <div class="card mb-4 internal-table">
                            <div class="card-body">
                              <div class="table-responsive">
                                <table class="table" id="tblListadoProveedoresProducto" width="100%" cellspacing="0">
                                  <thead>
                                    <tr>
                                      <th>Id</th>
                                      <th>Proveedor</th>
                                      <th>Producto</th>
                                      <th>Clave</th>
                                      <th>Precio</th>
                                      <th>Días de entrega</th>
                                      <th></th>
                                    </tr>
                                  </thead>
                                </table>
                              </div>
                            </div>
                          </div>
                        </div>
                        <br>
                        <input type="hidden" id="txtEdicion" value="0">          
                        <input type="hidden" id="cmbProve" value="0">
                      </form>
                    </div>
                  </div>
                </div>
              </div>
              <input type='hidden' value='${isVenta}' name="txtIsVenta" id="txtIsVenta">
              <input type='hidden' value='${isCompra}' name="txtIsCompra" id="txtIsCompra">
              `;

  $("#datos").html(html);

  cargarTablaProveedores(id, 1);
}

/*----------------------Funciones de carga de datos de los combos-------------------------------*/
function cargarCMBProveedor(data, input) {
  var html = "";
  var selected;
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_proveedor" },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta proveedor: ", respuesta);

      //html += '<option value="0">Seleccione un proveedor...</option>';

      $.each(respuesta, function (i) {
        if (data === respuesta[i].PKProveedor) {
          selected = "selected";
        } else {
          selected = "";
        }

        html +=
          '<option value="' +
          respuesta[i].PKProveedor +
          '" ' +
          selected +
          ">" +
          respuesta[i].Razon_Social +
          "</option>";
      });

      //html += '<option value="add" style="background-color: #15589B;  color:white; text-align:center; width:100%;">Configurar proveedores</option>';

      $("#" + input + "").html(html);

      CargarSlimProveedor();
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function cargarCMBProveedorEdit(data, input) {
  var html = "";
  var selected;
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_proveedorEdit" },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta proveedor: ", respuesta);

      //html += '<option value="0">Seleccione un proveedor...</option>';

      $.each(respuesta, function (i) {
        if (data === respuesta[i].PKProveedor) {
          selected = "selected";
        } else {
          selected = "";
        }

        html +=
          '<option value="' +
          respuesta[i].PKProveedor +
          '" ' +
          selected +
          ">" +
          respuesta[i].Razon_Social +
          "</option>";
      });

      $("#cmbProveedorProductoEdit").val(data);
      $("#cmbProveedorProductoEdit").attr("disabled", "true");

      $("#" + input + "").html(html);

      CargarSlimProveedorEdit();
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function cargarCMBUnidadMProveedor(data, input) {
  var valor = data;
  console.log("PKImpuestos: " + valor);

  var html = "";
  var selected;
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_cmb_unidadM_proveedor",
      data: valor,
    },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta proveedor: ", respuesta);

      //html += '<option value="0">Seleccione una unidad de medida...</option>';

      $.each(respuesta, function (i) {
        if (data === respuesta[i].PKUnidadMedida) {
          selected = "selected";
        } else {
          selected = "";
        }

        html +=
          '<option value="' +
          respuesta[i].PKUnidadMedida +
          '" ' +
          selected +
          ">" +
          respuesta[i].UnidadMedida +
          "</option>";
      });

      //html += '<option value="add" style="background-color: #15589B;  color:white; text-align:center; width:100%;">Configurar proveedores</option>';

      $("#" + input + "").html(html);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function cargarCMBMonedaPrecio(data, input) {
  var html = "";
  var selected;
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_costouni_compra" },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta tipo de moneda: ", respuesta);

      $.each(respuesta, function (i) {
        if (data === respuesta[i].PKTipoMoneda) {
          selected = "selected";
        } else {
          selected = "";
        }

        html +=
          '<option value="' +
          respuesta[i].PKTipoMoneda +
          '" ' +
          selected +
          ">" +
          respuesta[i].TipoMoneda +
          "</option>";
      });

      CargarSlimMonedaPrecio();
      $("#" + input + "").append(html);
      $("#cmbMonedaPrecio").val("100");
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function cargarCMBMonedaPrecioEdit(data, input) {
  var html = "";
  var selected;
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_costouni_compraEdit" },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta tipo de moneda: ", respuesta);

      $.each(respuesta, function (i) {
        if (data === respuesta[i].PKTipoMoneda) {
          selected = "selected";
        } else {
          selected = "";
        }

        html +=
          '<option value="' +
          respuesta[i].PKTipoMoneda +
          '" ' +
          selected +
          ">" +
          respuesta[i].TipoMoneda +
          "</option>";
      });

      $("#cmbMonedaPrecioEdit").val(data);

      $("#" + input + "").html(html);

      CargarSlimMonedaPrecioEdit();
    },
    error: function (error) {
      console.log(error);
    },
  });
}

/*----------------------Plugin Slim para la búsqueda dentro del-------------------------------*/
function CargarSlimProveedor() {
  new SlimSelect({
    select: "#cmbProveedorProducto",
    deselectLabel: '<span class="">✖</span>',
  });

  //CargarSlimUnidadMProveedor();
}

function CargarSlimProveedorEdit() {
  new SlimSelect({
    select: "#cmbProveedorProductoEdit",
    deselectLabel: '<span class="">✖</span>',
  });

  //CargarSlimUnidadMProveedor();
}

function CargarSlimUnidadMProveedor() {
  new SlimSelect({
    select: "#cmbUnidadMProveedor",
    deselectLabel: '<span class="">✖</span>',
  });
}

function CargarSlimMonedaPrecio() {
  new SlimSelect({
    select: "#cmbMonedaPrecio",
    deselectLabel: '<span class="">✖</span>',
  });
}

function CargarSlimMonedaPrecioEdit() {
  new SlimSelect({
    select: "#cmbMonedaPrecioEdit",
    deselectLabel: '<span class="">✖</span>',
  });
}

function cambioProveedor() {
  $("#txtEdicion").val("0");
  $("#cmbProve").val("0");
  $("#txtNombreProdProve").val("");
  $("#txtClaveProdProve").val("");
  $("#txtPrecioProdProve").val("");
  $("#cmbMonedaPrecio").val("100");
  $("#txtCantMinProdProve").val("");
  $("#txtDiasEntregProdProve").val("");
  $("#txtUnidadMedida").val("");

  var FKProveedor = document.getElementById("cmbProveedorProducto").value;
  cargarCMBUnidadMProveedor(FKProveedor, "cmbUnidadMProveedor");

  escribirClaveProveedor();
}

/*----------------------------Botón añadir proveedor ---------------------------*/
/* VALIAR QUE NO SE REPITA EL PROVEEDOR POR PRODUCTO AGREGADO POR EL USUARIO EN AGREGAR */
function validarProveedor(id) {
  $("#txtEdicion").val(0);
  var valor = $("#cmbProveedorProducto").val();
  console.log("Valor proveedor" + valor);
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "validar_proveedorProducto",
      data: id,
      data2: valor,
    },
    dataType: "json",
    success: function (data) {
      /* Validar si ya existe el identificador con ese nombre*/
      if (parseInt(data[0]["existe"]) == 1) {
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/notificacion_error.svg",
          msg: "¡El proveedor ya se encuentra registrado para este producto.!",
          sound: "../../../../../sounds/sound4",
        });
      } else {
        anadirProveedor();
      }
    },
  });
}

function validarProveedorEdit(id) {
  $("#txtEdicion").val(1);
  var valor = $("#cmbProveedorProductoEdit").val();
  if (valor != $("#cmbProve").val()) {
    console.log("Valor proveedor" + valor);
    $.ajax({
      url: "../../php/funciones.php",
      data: {
        clase: "get_data",
        funcion: "validar_proveedorProducto",
        data: id,
        data2: valor,
      },
      dataType: "json",
      success: function (data) {
        /* Validar si ya existe el identificador con ese nombre*/
        if (parseInt(data[0]["existe"]) == 1) {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../../../img/timdesk/notificacion_error.svg",
            msg: "El proveedor ya se encuentra registrado para este producto.",
            sound: "../../../../../sounds/sound4",
          });
        } else {
          editarProveedor();
        }
      },
    });
  } else {
    editarProveedor();
  }
}

function escribirClaveProveedor() {
  var proveedor = $("#cmbProveedorProductoEdit").val();
  //var proveedor = $("#cmbProveedorProducto").val();
  var clave = $("#txtClaveProdProve").val();
  console.log("Valor proveedor" + proveedor);
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "validar_claveProveedorProducto",
      data: proveedor,
      data2: clave,
    },
    dataType: "json",
    success: function (data) {
      console.log("respuesta clave proveedor validado: ", data);
      /* Validar si ya existe el identificador con ese nombre*/
      if (parseInt(data[0]["existe"]) == 1) {
        $("#invalid-claveProdProv").css("display", "block");
        $("#txtClaveProdProve").addClass("is-invalid");
        console.log("¡Ya existe!");
      } else {
        $("#invalid-claveProdProv").css("display", "none");
        $("#txtClaveProdProve").removeClass("is-invalid");
        console.log("¡No existe!");
      }
    },
  });
}

function escribirClaveProveedorEdit() {
  var proveedor = $("#cmbProveedorProductoEdit").val();
  var clave = $("#txtClaveProdProveEdit").val();
  var claveHis = $("#txtClaveProdProveHisEdit").val();
  console.log("Valor proveedor" + proveedor);

  if (claveHis != clave) {
    $.ajax({
      url: "../../php/funciones.php",
      data: {
        clase: "get_data",
        funcion: "validar_claveProveedorProducto",
        data: proveedor,
        data2: clave,
      },
      dataType: "json",
      success: function (data) {
        console.log("respuesta clave proveedor validado: ", data);
        /* Validar si ya existe el identificador con ese nombre*/
        if (parseInt(data[0]["existe"]) == 1) {
          $("#notaClaveProdProveEdit").css("display", "block");

          console.log("¡Ya existe!");
        } else {
          $("#notaClaveProdProveEdit").css("display", "none");

          console.log("¡No existe!");
        }
      },
    });
  }
}

/* Añadir el impuesto */
function anadirProveedor() {
  if ($("#formDatosProveedor")[0].checkValidity()) {
    if ($("#formDatosProveedor")[0].checkValidity()) {
      var badProveedorProd =
        $("#invalid-proveedorProd").css("display") === "block" ? false : true;
      var badClaveProdProv =
        $("#invalid-claveProdProv").css("display") === "block" ? false : true;
      var badPrecioProd =
        $("#invalid-precioProd").css("display") === "block" ? false : true;
      if (badProveedorProd && badClaveProdProv && badPrecioProd) {
        var data = [];
        //El serializeArray obtiene un id consecutivo de los elementos fiel que posee el formulario
        $.each($("#formDatosProveedor").serializeArray(), function (i, field) {
          data.push({ id: i, campos: field.name, datos: field.value });
        });

        console.log(data);

        /*if( ($('#notaClaveProdProve').css('display') == 'block') ){

  }else{*/
        $.ajax({
          url: "../../php/funciones.php",
          data: {
            clase: "save_data",
            funcion: "save_datosProveedor",
            datos: data,
          },
          dataType: "json",
          success: function (respuesta) {
            if (respuesta[0].status) {
              $("#tblListadoProveedoresProducto").DataTable().ajax.reload();
              $("#txtEdicion").val("0");
              $("#cmbProve").val("0");
              $("#txtNombreProdProve").val("");
              $("#txtClaveProdProve").val("");
              $("#txtPrecioProdProve").val("");
              $("#cmbMonedaPrecio").val("100");
              $("#txtCantMinProdProve").val("");
              $("#txtDiasEntregProdProve").val("");
              $("#txtUnidadMedida").val("");
              Lobibox.notify("success", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../../../img/timdesk/checkmark.svg",
                msg: "¡Datos del proveedor registrados correctamente!",
                sound: "../../../../../sounds/sound4",
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
    }
  } else {
    $("#invalid-precioProd").css("display", "block");
    $("#txtPrecioProdProve").addClass("is-invalid");
  }
}

/* Eliminar el proveedor */
function eliminarProveedor(datoProProve) {
  console.log("ID del dato del producto del proveedor: " + datoProProve);
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "delete_data",
      funcion: "delete_proveedor_producto",
      datos: datoProProve,
    },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta eliminar proveedor Producto:", respuesta);

      if (respuesta[0].status) {
        $("#tblListadoProveedoresProducto").DataTable().ajax.reload();
        Lobibox.notify("success", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/checkmark.svg",
          msg: "¡Se eliminó el proveedor con éxito!",
          sound: "../../../../../sounds/sound4",
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

/* Traer los datos del proveedor */
function datosEditProveedor(datoProProve) {
  console.log("ID del dato del producto del proveedor: " + datoProProve);
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_datos_proveedor_producto",
      datos: datoProProve,
    },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta datos del proveedor Producto ver :", respuesta);

      /*$("#cmbProveedorProducto").val(respuesta[0].FKProveedor);
      $("#cmbProveedorProducto").attr("disabled", "true");

      $("#txtNombreProdProve").val(respuesta[0].NombreProducto);
      $("#txtClaveProdProve").val(respuesta[0].Clave);
      $("#txtPrecioProdProve").val(respuesta[0].Precio);
      $("#cmbMonedaPrecio").val(respuesta[0].FKTipoMoneda);
      $("#txtCantMinProdProve").val(respuesta[0].CantidadMinima);
      $("#txtDiasEntregProdProve").val(respuesta[0].DiasEntrega);
      $("#txtUnidadMedida").val(respuesta[0].UnidadMedida);

      $("#txtEdicion").val("1");
      $("#cmbProve").val(respuesta[0].FKProveedor);*/

      $("#cmbProveedorProductoEdit").val(respuesta[0].FKProveedor);
      $("#cmbProveedorProductoEdit").attr("disabled", "true");

      $("#txtNombreProdProveEdit").val(respuesta[0].NombreProducto);
      $("#txtClaveProdProveEdit").val(respuesta[0].Clave);
      $("#txtClaveProdProveHisEdit").val(respuesta[0].Clave);
      $("#txtPrecioProdProveEdit").val(respuesta[0].Precio);

      $("#cmbMonedaPrecioEdit").val(respuesta[0].FKTipoMoneda);

      $("#txtCantMinProdProveEdit").val(respuesta[0].CantidadMinima);

      if (respuesta[0].DiasEntrega == "Sin confirmar") {
        $("#txtDiasEntregProdProveEdit").val("");
      } else {
        $("#txtDiasEntregProdProveEdit").val(respuesta[0].DiasEntrega);
      }

      $("#txtUnidadMedidaEdit").val(respuesta[0].UnidadMedida);

      $("#txtPKProductoProveedorEdit").val(datoProProve);
      $("#txtNombreD").val(respuesta[0].NombreProducto);

      $("#txtEdicion").val("1");
      $("#cmbProve").val(respuesta[0].FKProveedor);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

/* editar el proveedor */
function editarProveedor() {
  if ($("#formDatosProveedorEdit")[0].checkValidity()) {
    var badProvEdit =
      $("#invalid-provEdit").css("display") === "block" ? false : true;
    var badPrecioEdit =
      $("#invalid-precioProdEdit").css("display") === "block" ? false : true;
    if (badProvEdit && badPrecioEdit) {
      var pkProducto = $("#txtPKProductoProveedor").val();
      var PKProveedor = $("#cmbProveedorProductoEdit").val();
      var nombre = $("#txtNombreProdProveEdit").val();
      var clave = $("#txtClaveProdProveEdit").val();
      var precio = 0;

      if (
        $("#txtPrecioProdProveEdit").val() == "" ||
        $("#txtPrecioProdProveEdit").val() == null
      ) {
        precio = 0;
      } else {
        precio = $("#txtPrecioProdProveEdit").val();
      }

      var moneda = $("#cmbMonedaPrecioEdit").val();
      var cantmin = 0;
      if (
        $("#txtCantMinProdProveEdit").val() == "" ||
        $("#txtCantMinProdProveEdit").val() == null
      ) {
        cantmin = 0;
      } else {
        cantmin = $("#txtCantMinProdProveEdit").val();
      }

      var diasEnt = "";
      if (
        $("#txtDiasEntregProdProveEdit").val() == "" ||
        $("#txtDiasEntregProdProveEdit").val() == null ||
        $("#txtDiasEntregProdProveEdit").val() == "0"
      ) {
        diasEnt = "Sin confirmar";
      } else {
        diasEnt = $("#txtDiasEntregProdProveEdit").val();
      }

      var unidadMedida = $("#txtUnidadMedidaEdit").val();
      var idDetalleProdProv = $("#txtPKProductoProveedorEdit").val();

      $.ajax({
        url: "../../php/funciones.php",
        data: {
          clase: "edit_data",
          funcion: "edit_datosProveedor",
          datos: pkProducto,
          datos2: PKProveedor,
          datos3: nombre,
          datos4: clave,
          datos5: precio,
          datos6: moneda,
          datos7: cantmin,
          datos8: diasEnt,
          datos9: unidadMedida,
          datos10: idDetalleProdProv,
        },
        dataType: "json",
        success: function (respuesta) {
          console.log(
            "respuesta editar datos de proveedor del producto:",
            respuesta
          );

          if (respuesta[0].status) {
            $("#tblListadoProveedoresProducto").DataTable().ajax.reload();
            Lobibox.notify("success", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../../img/timdesk/checkmark.svg",
              msg: "¡Se editó el proveedor con éxito!",
              sound: "../../../../../sounds/sound4",
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
  } else {
    if (!$("#cmbProveedorProductoEdit").val()) {
      $("#invalid-provEdit").css("display", "block");
      $("#cmbProveedorProductoEdit").addClass("is-invalid");
    }
    if (!$("#txtPrecioProdProveEdit").val()) {
      $("#invalid-precioProdEdit").css("display", "block");
      $("#txtPrecioProdProveEdit").addClass("is-invalid");
    }
  }
}

//Validar que se hayan completado todos los campos antes de pasar a la siguiente pestaña
function guardarDatosProveedor(id) {
  var data = [];
  //El serializeArray obtiene un id consecutivo de los elementos fiel que posee el formulario
  $.each($("#formDatosProveedor").serializeArray(), function (i, field) {
    data.push({ id: i, campos: field.name, datos: field.value });
  });

  console.log(data);
  var element = document.getElementById("content");
  element.scrollIntoView();
  //SeguirInventario(id, $("#txtIsVenta").val());

  if ($("#txtIsVenta").val() == 1) {
    SeguirDatosVenta(id);
  } else {
    TerminarGuardadoDatos();
  }
}

function SeguirInventario(id, isVenta) {
  validarEmpresaProducto(id);

  resetTabs("#CargarDatosInventario");

  cargarCMBTipoOrden("", "cmbTipoInventario");
  //cargarCMBCostoUniCompra('','cmbCostoUniCompra');
  //cargarCMBCostoUniVenta('','cmbCostoUniVenta');

  $("#cmbCostoUniCompra").val("100");
  $("#cmbCostoUniVenta").val("100");

  var html =
    `<div class="card shadow mb-4">
                <div class="card-header">
                  Tarjeta de inventario
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-12">
                      <form id="formDatosProveedor"> 
                        <input type='hidden' value='` +
    id +
    `' name="txtPKProductoInventario" id="txtPKProductoInventario">
                        <div class="form-group">
                          <div class="row">
                            <div class="col-lg-4">
                              <label for="usr">Tipo de orden de inventario:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <span class="input-group-addon" style="width:100%">
                                    <select name="cmbTipoInventario" id="cmbTipoInventario" required>
                                    </select>   
                                  </span>
                                  <div class="invalid-feedback" id="invalid-inventarioProd">El inventario debe tener un tipo de orden.</div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div> 

                        <div class="form-group">
                          <div class="row">
                            <div class="col-lg-3">
                              <label for="usr">Stock en existencia:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control numeric-only" type="number" name="txtStockExi" id="txtStockExi" min="0" autofocus="" required="" placeholder="Ej. 80" onkeyup="validarStockExistencia()">
                                  <div class="invalid-feedback" id="invalid-stockProd">El inventario debe tener un stock.</div>
                                </div>
                              </div>  
                            </div>
                            <div class="col-lg-3">
                              <label for="usr">Stock mínimo:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control numeric-only" type="number" name="txtStockMin" id="txtStockMin" min="0" autofocus="" required="" placeholder="Ej. 20" onkeyup="validarStockMaximo()">
                                  <div class="invalid-feedback" id="invalid-stockMinProd">El inventario debe tener un stock minimo.</div>
                                </div>
                              </div>  
                            </div>
                            <div class="col-lg-3">
                              <label for="usr">Stock máximo:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control numeric-only" type="number" name="txtStockMax" id="txtStockMax" min="0" autofocus="" required="" placeholder="Ej. 100" onkeyup="validarStockMaximo()">
                                  <div class="invalid-feedback" id="invalid-stockMaxProd">El inventario debe tener un stock maximo.</div>
                                </div>
                              </div>      
                            </div>
                            <div class="col-lg-3">
                              <label for="usr">Punto de reorden:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control numeric-only" type="number" name="txtReorden" id="txtReorden" min="0" autofocus="" required="" placeholder="Ej. 5" onkeyup="validarPuntoReorden()">
                                  <div class="invalid-feedback" id="invalid-reordenProd">El inventario debe tener un punto de reorden.</div>
                                </div>
                              </div>    
                            </div>
                          </div>
                        </div>
                        <br>

                        <label for="">* Campos requeridos</label>
                      </form>

                      <a href="#" class="btn-custom btn-custom--blue float-right" id="btnAgregarTipoProducto" onclick="guardarDatosInventario(` +
    id +
    `)">Guardar</a>
                    </div>
                  </div>
                </div>
              </div>
              <input type='hidden' value='` +
    isVenta +
    `' name="txtIsVenta" id="txtIsVenta">`;

  $("#datos").html(html);
  resetValidations();
}

/*----------------------Funciones de carga de datos de los combos-------------------------------*/

function cargarCMBTipoOrden(data, input) {
  var html = "";
  var html2 = "";
  var selected;
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_tipo_orden" },
    dataType: "json",
    success: function (respuesta) {
      console.log(
        "respuesta tipo de orden de producto de inventario: ",
        respuesta
      );

      //html += '<option value="0">Seleccione un tipo de orden...</option>';

      $.each(respuesta, function (i) {
        if (data === respuesta[i].PKTipoOrdenInventario) {
          selected = "selected";
        } else {
          selected = "";
        }

        if ("Sin tipo de orden" == respuesta[i].TipoOrdenInventario) {
          html2 =
            '<option value="' +
            respuesta[i].PKTipoOrdenInventario +
            '" ' +
            selected +
            ">" +
            respuesta[i].TipoOrdenInventario +
            "</option>";
        } else {
          html +=
            '<option value="' +
            respuesta[i].PKTipoOrdenInventario +
            '" ' +
            selected +
            ">" +
            respuesta[i].TipoOrdenInventario +
            "</option>";
        }
      });

      html +=
        '<option value="add" style="background-color: #15589B;  color:white; text-align:center; width:100%;">Configurar tipos de orden</option>';

      CargarSlimTipoInventario();
      $("#" + input + "").html(html2 + html);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

/*----------------------Plugin Slim para la búsqueda dentro del-------------------------------*/
function CargarSlimTipoInventario() {
  new SlimSelect({
    select: "#cmbTipoInventario",
    deselectLabel: '<span class="">✖</span>',
    addable: function (value) {
      validarTipoOrdenInventario(value);
    },
  });
}

$(document).on("change", "#cmbTipoInventario", function () {
  $("#invalid-inventarioProd").css("display", "block");
  $("#cmbTipoInventario").addClass("is-invalid");
  if ($("#cmbTipoInventario").val() > 0) {
    $("#invalid-inventarioProd").css("display", "none");
    $("#cmbTipoInventario").removeClass("is-invalid");
  }
});

//Validar que se hayan completado todos los campos antes de pasar a la siguiente pestaña
function guardarDatosInventario(id) {
  if ($("#formDatosProveedor")[0].checkValidity()) {
    var badInventarioProd =
      $("#invalid-inventarioProd").css("display") === "block" ? false : true;
    var badStockProd =
      $("#invalid-stockProd").css("display") === "block" ? false : true;
    var badStockMinProd =
      $("#invalid-stockMinProd").css("display") === "block" ? false : true;
    var badStockMaxProd =
      $("#invalid-stockMaxProd").css("display") === "block" ? false : true;
    var badReordenoProd =
      $("#invalid-reordenProd").css("display") === "block" ? false : true;

    if (
      badInventarioProd &&
      badStockProd &&
      badStockMinProd &&
      badStockMaxProd &&
      badReordenoProd
    ) {
      var data = [];
      //El serializeArray obtiene un id consecutivo de los elementos fiel que posee el formulario
      $.each($("#formDatosProveedor").serializeArray(), function (i, field) {
        data.push({ id: i, campos: field.name, datos: field.value });
      });
      $.ajax({
        url: "../../php/funciones.php",
        data: {
          clase: "save_data",
          funcion: "save_datosInventario",
          datos: data,
        },
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
              msg: "¡Datos del inventario registrados correctamente!",
              sound: "../../../../../sounds/sound4",
            });
            var element = document.getElementById("content");
            element.scrollIntoView();

            if ($("#txtIsVenta").val() == 1) {
              SeguirDatosVenta(id);
            } else {
              TerminarGuardadoDatos();
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
  } else {
    if ($("#cmbTipoInventario").val() == "0") {
      console.log("es 0");
      $("#invalid-inventarioProd").css("display", "block");
      $("#cmbTipoInventario").addClass("is-invalid");
    }
    if (!$("#txtStockExi").val()) {
      $("#invalid-stockProd").css("display", "block");
      $("#txtStockExi").addClass("is-invalid");
    }
    if (!$("#txtStockMin").val()) {
      $("#invalid-stockMinProd").css("display", "block");
      $("#txtStockMin").addClass("is-invalid");
    }
    if (!$("#txtStockMax").val()) {
      $("#invalid-stockMaxProd").css("display", "block");
      $("#txtStockMax").addClass("is-invalid");
    }
    if (!$("#txtReorden").val()) {
      $("#invalid-reordenProd").css("display", "block");
      $("#txtReorden").addClass("is-invalid");
    }
  }
}

function SeguirDatosVenta(id) {
  validarEmpresaProducto(id);
  resetTabs("#CargarTiposProducto");

  cargarCMBClientesProducto("", "cmbClientesProducto");
  cargarCMBCostoUniVenta("", "cmbCostoUniVenta");
  cargarCMBCostoUniVentaEsp("", "cmbCostoUniVentaEspecial");

  var html =
    `<div class="card shadow mb-4">
                <div class="card-header">
                  Tarjeta de venta
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-12">
                      <form id="formDatosVentaProducto"> 
                        <input type='hidden' value='` +
    id +
    `' name="txtPKProducto2" id="txtPKProducto2">
                        <div class="form-group">
                          <div class="row">
                            <div class="col-lg-3">
                              <label for="usr">Costo unitario general de venta:</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control numericDecimal-onlyl" type="text" name="txtCostoUniVenta" id="txtCostoUniVenta" autofocus="" required="" placeholder="Ej. 10.00" style="float:left;" disabled="disabled">
                                  <span class="input-group-addon" style="width:100px">
                                    <select name="cmbCostoUniVenta" id="cmbCostoUniVenta" required="" disabled="disabled">
                                    </select> 
                                  </span>
                                  <div class="invalid-feedback" id="invalid-costoUnitProd">El producto debe tener un costo unitario general de venta.</div>
                                </div>
                              </div> 
                            </div>
                            <div class="col-lg-9" style="text-align:center!important; margin-top:25px;">
                              
                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="row">
                            <div class="col-lg-4">
                              <label for="usr">Cliente:</label>
                              <select class="cmbSlim" name="cmbClientesProducto" id="cmbClientesProducto" required="" onchange="verificarClienteProducto(` +
    id +
    `)">
                              </select>
                              <div class="invalid-feedback" id="invalid-clienteVenta">El registro debe tener un cliente.</div>
                            </div>
                            <div class="col-lg-4">
                              <label for="usr">Costo especial:</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control" type="text" maxlength="13" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" name="txtCostoEspecialVenta" id="txtCostoEspecialVenta" autofocus="" required="" placeholder="Ej. 10.00" style="float:left;" onkeyup="validEmptyInput('txtCostoEspecialVenta', 'invalid-costoEspProd', 'El producto debe tener un costo especial.')">
                                  <span class="input-group-addon" style="width:100px">
                                    <select name="cmbCostoUniVentaEspecial" id="cmbCostoUniVentaEspecial" required="">
                                    </select> 
                                  </span>
                                  <div class="invalid-feedback" id="invalid-costoEspProd">El producto debe tener un costo especial</div>
                                </div>
                              </div> 
                            </div>
                            <div class="col-lg-4" style="margin-top: 35px">
                              <a href="#" class="btn-custom btn-custom--border-blue" id="btnAnadirCliente" onclick="validarClienteProducto(` +
    id +
    `)">Añadir precio especial</a>
    <a href="#" class="btn-custom btn-custom--blue" id="btnAgregarClienteProducto" onclick="guardarDatosVentaProducto(` +
    id +
    `)">Guardar y volver</a>
                            </div>
                            <label for="">* Campos requeridos</label>
                          </div>
                        </div>
                        <br>

                        <div class="form-group">
                          <!-- DataTales Example -->
                          <div class="card mb-4 internal-table">
                            <div class="card-body">
                              <div class="table-responsive">
                                <table class="table" id="tblListadoClientesProducto" width="100%" cellspacing="0">
                                  <thead>
                                    <tr>
                                      <th>Id</th>
                                      <th>Cliente</th>
                                      <th>Costo especial</th>
                                    </tr>
                                  </thead>
                                </table>
                              </div>
                            </div>
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>`;

  $("#datos").html(html);

  cargarTablaClientes(id, 1);

  cargarDatosVenta(id);
  resetValidations();
}

/*----------------------Funciones de carga de datos de los combos-------------------------------*/
function cargarCMBClientesProducto(data, input) {
  var html = "";
  var selected;
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_clientes_producto" },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta clientes del producto: ", respuesta);

      $.each(respuesta, function (i) {
        if (data === respuesta[i].PKCliente) {
          selected = "selected";
        } else {
          selected = "";
        }

        html +=
          '<option value="' +
          respuesta[i].PKCliente +
          '" ' +
          selected +
          ">" +
          respuesta[i].NombreComercial +
          "</option>";
      });

      if(input == "cmbClientesProducto"){
        CargarSlimClientes();
      }

      $("#" + input + "").html(html);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function cargarCMBCostoUniVenta(data, input) {
  var html = "";
  var selected;
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_costouni_venta" },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta tipo de moneda: ", respuesta);

      $.each(respuesta, function (i) {
        if (data === respuesta[i].PKTipoMoneda) {
          selected = "selected";
        } else {
          selected = "";
        }

        html +=
          '<option value="' +
          respuesta[i].PKTipoMoneda +
          '" ' +
          selected +
          ">" +
          respuesta[i].TipoMoneda +
          "</option>";
      });

      CargarSlimCostoUniVenta();
      $("#" + input + "").append(html);
      $("#cmbCostoUniVenta").val("100");
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function cargarCMBCostoUniVentaEsp(data, input) {
  var html = "";
  var selected;
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_costouni_compra" },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta tipo de moneda esp: ", respuesta);

      $.each(respuesta, function (i) {
        if (data === respuesta[i].PKTipoMoneda) {
          selected = "selected";
        } else {
          selected = "";
        }

        html +=
          '<option value="' +
          respuesta[i].PKTipoMoneda +
          '" ' +
          selected +
          ">" +
          respuesta[i].TipoMoneda +
          "</option>";
      });

      CargarSlimCostoUniCompra();
      $("#" + input + "").append(html);
      $("#cmbCostoUniVentaEspecial").val("100");
    },
    error: function (error) {
      console.log(error);
    },
  });
}

/*----------------------Plugin Slim para la búsqueda dentro del-------------------------------*/
function CargarSlimClientes() {
  new SlimSelect({
    select: "#cmbClientesProducto",
    deselectLabel: '<span class="">✖</span>',
  });
}

$(document).ready(function() {
  new SlimSelect({
    select: "#cmbCliente_edit",
    deselectLabel: '<span class="">✖</span>',
  });
});

function CargarSlimCostoUniVenta() {
  new SlimSelect({
    select: "#cmbCostoUniVenta",
    deselectLabel: '<span class="">✖</span>',
  });
}

function CargarSlimCostoUniCompra() {
  new SlimSelect({
    select: "#cmbCostoUniVentaEspecial",
    deselectLabel: '<span class="">✖</span>',
  });
}

/*----------------------------Botón añadir tipo producto ---------------------------*/
/* VALIAR QUE NO SE REPITA EL CLIENTE POR PRODUCTO AGREGADO POR EL USUARIO EN AGREGAR */
function verificarClienteProducto(id) {
  var valor = $("#cmbClientesProducto").val();
  console.log("Valor cliente id: " + valor);
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "validar_clienteProducto",
      data: valor,
      data2: id,
    },
    dataType: "json",
    success: function (data) {
      console.log("respuesta cliente validado: ", data);
      /* Validar si ya existe el identificador con ese nombre*/
      if (parseInt(data[0]["existe"]) == 1) {
        $("#invalid-clienteVenta").css("display", "block");
        $("#invalid-clienteVenta").text("El cliente ya esta registrado.");
        $("#cmbClientesProducto").addClass("is-invalid");
        console.log("¡Ya existe!");
      } else {
        $("#invalid-clienteVenta").css("display", "none");
        $("#invalid-clienteVenta").text("El registro debe tener un cliente.");
        $("#cmbClientesProducto").removeClass("is-invalid");
        console.log("¡No existe!");
      }
    },
  });
}

function validarClienteProducto(id) {
  var valor = $("#cmbClientesProducto").val();
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "validar_clienteProducto",
      data: valor,
      data2: id,
    },
    dataType: "json",
    success: function (data) {
      /* Validar si ya existe el identificador con ese nombre*/
      if (parseInt(data[0]["existe"]) == 1) {
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/notificacion_error.svg",
          msg: "El cliente ya cuenta con un costo especial registrado para este producto.",
          sound: "../../../../../sounds/sound4",
        });
      } else {
        anadirClienteProducto(id);
      }
    },
  });
}

/* Añadir el impuesto */
function anadirClienteProducto(id) {
  if ($("#formDatosVentaProducto")[0].checkValidity()) {
    var cliente = $("#cmbClientesProducto").val();
    var costoEsp = $("#txtCostoEspecialVenta").val();
    var moneda = $("#cmbCostoUniVentaEspecial").val();
    var costoGral = $("#txtCostoUniVenta").val();
    var monedaGral = $("#cmbCostoUniVenta").val();

    var data = [];
    //El serializeArray obtiene un id consecutivo de los elementos fiel que posee el formulario
    $.each($("#formDatosProveedor").serializeArray(), function (i, field) {
      data.push({ id: i, campos: field.name, datos: field.value });
    });

    if (costoEsp == "" || costoGral == "") {
      if (costoEsp == "") {
        $("#notaCostoVentaEspecial").css("display", "block");
      } else {
        $("#notaCostoVentaEspecial").css("display", "none");
      }

      if (costoGral == "") {
        $("#notaCostoVenta").css("display", "block");
      } else {
        $("#notaCostoVenta").css("display", "none");
      }
      console.log("¡Falta añadir el costo especial!");
    } else {
      console.log(data);
      $.ajax({
        url: "../../php/funciones.php",
        data: {
          clase: "save_data",
          funcion: "save_clienteProducto",
          datos: cliente,
          datos2: costoEsp,
          datos3: moneda,
          datos4: id,
          datos5: costoGral,
          datos6: monedaGral,
        },
        dataType: "json",
        success: function (respuesta) {
          console.log(
            "respuesta agregar cliente producto del producto:",
            respuesta
          );

          if (respuesta[0].status) {
            $("#tblListadoClientesProducto").DataTable().ajax.reload();
            Lobibox.notify("success", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../../img/timdesk/checkmark.svg",
              msg: "¡Se guardó el cliente con éxito!",
              sound: "../../../../../sounds/sound4",
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
  } else {
    if (!$("#txtCostoUniVenta").val()) {
      $("#invalid-costoUnitProd").css("display", "block");
      $("#txtCostoUniVenta").addClass("is-invalid");
    }
    if (!$("#cmbClientesProducto").val()) {
      $("#invalid-clienteVenta").css("display", "block");
      $("#cmbClientesProducto").addClass("is-invalid");
    }
    if (!$("#txtCostoEspecialVenta").val()) {
      $("#invalid-costoEspProd").css("display", "block");
      $("#txtCostoEspecialVenta").addClass("is-invalid");
    }
  }
}

var slim_moneda_modalEdit;
$(document).ready(function() {
  slim_moneda_modalEdit = new SlimSelect({
    select: "#cmbMoneda_edit",
    deselectLabel: '<span class="">✖</span>',
  });
});

function getProductoIdAndName(id)
{
    document.getElementById('txthideidproduct').value = id;

    //carga los datos del producto abierto
    $.ajax({
      url: "../../php/funciones.php",
      data: {
        clase: "buscar_data",
        funcion: "get_Costoproducto",
        data: id
      },
      dataType: "json",
      success: function (respuesta) {
        $("#txtProducto").text(respuesta[0].producto);
        $("#txtCostoEspecialVenta_modalEdit").val(respuesta[0].CostoEspecial);
        slim_moneda_modalEdit.set(respuesta[0].FKTipoMoneda);
        document.getElementById('txthideidCliente').value = respuesta[0].FKCliente;
        document.getElementById('txthideidproduct_PK').value = respuesta[0].FKProducto;

        //carga combo con el cliente del costo
        cargarCMBClientesProducto(respuesta[0].FKCliente, "cmbCliente_edit");
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
          msg: "¡No se recuperaron los datos del producto con éxito :(!",
          sound: '../../../../../sounds/sound4'
        });
      },
    });
}

/* Eliminar el cliente de producto*/
function eliminarCliente(cliente) {
  console.log("ID del cliente producto: " + cliente);
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "delete_data",
      funcion: "delete_cliente_producto",
      datos: cliente,
    },
    dataType: "json",
    success: function (respuesta) {
      if (respuesta[0].status) {
        $("#tblListadoClientesProducto").DataTable().ajax.reload();
        Lobibox.notify("success", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/checkmark.svg",
          msg: "¡Se eliminó el cliente con éxito!",
          sound: "../../../../../sounds/sound4",
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

$(document).on("click", "#btnEditarCosto", function () {
  
  const promise = new Promise((resolve, reject) => {
    let valor = $("#cmbCliente_edit").val();
    let clienteActual = $("#txthideidCliente").val();
    let id = $("#txthideidproduct_PK").val();
    if(parseInt(valor) == parseInt(clienteActual)){
      resolve(true);
    }

    $.ajax({
      url: "../../php/funciones.php",
      data: {
        clase: "get_data",
        funcion: "validar_clienteProducto",
        data: valor,
        data2: id,
      },
      dataType: "json",
      success: function (data) {
        /* Validar si ya existe el identificador con ese nombre*/
        if (parseInt(data[0]["existe"]) == 1) {
          resolve(false);
        } else {
          resolve(true);
        }
      },
    });
  });

  promise.then((res) => {
    if(res){
      if ($("#EditarProductoCliente")[0].checkValidity()) {
        var badCosto =
          $("#invalid-costoProd_edit").css("display") === "block" ? false : true;
        var badMoneda =
          $("#invalid-moneda_edit").css("display") === "block" ? false : true;
        var badCliente =
          $("#invalid-cliente_edit").css("display") === "block" ? false : true;
        
        if (
          badCosto &&
          badMoneda &&
          badCliente
        ) {
          
          let Costo = $("#txtCostoEspecialVenta_modalEdit").val();
          let moneda = $("#cmbMoneda_edit").val();
          let pkRegistro = $("#txthideidproduct").val();
          let cliente = $("#cmbCliente_edit").val();

          $.ajax({
            url: "../../php/funciones.php",
            data: {
              clase: "edit_data",
              funcion: "update_costo_cliente",
              datos: pkRegistro,
              datos2: Costo,
              datos3: moneda,
              datos4: cliente          
            },
            dataType: "json",
            success: function (respuesta) {
              if (respuesta[0].status) {
                $("#tblListadoClientesProducto").DataTable().ajax.reload();
                Lobibox.notify("success", {
                  size: "mini",
                  rounded: true,
                  delay: 3000,
                  delayIndicator: false,
                  position: "center top",
                  icon: true,
                  img: "../../../../img/timdesk/checkmark.svg",
                  msg: "¡Datos actualizados correctamente!",
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
                  msg: "Error al actualizar :c",
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
                msg: "Error al actualizar :c",
                sound: '../../../../../sounds/sound4'
              });
            },
          });
        }
      } else {
        if (!$("#txtCostoEspecialVenta_modalEdit").val()) {
          $("#invalid-costoProd_edit").css("display", "block");
          $("#txtCostoEspecialVenta_modalEdit").addClass("is-invalid");
        }
        if (!$("#cmbMoneda_edit").val()) {
          $("#invalid-moneda_edit").css("display", "block");
          $("#cmbMoneda_edit").addClass("is-invalid");
        }
        if (!$("#cmbCliente_edit").val()) {
          $("#invalid-cliente_edit").css("display", "block");
          $("#cmbCliente_edit").addClass("is-invalid");
        }
      }
    }else{
      Lobibox.notify("warning", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../../../img/timdesk/warning_circle.svg",
        msg: "El cliente ya cuenta con un costo especial registrado para este producto.",
        sound: '../../../../../sounds/sound4'
      });
    }
  });
});

function cargarDatosVenta(id) {
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_DataVentaProducto", data: id },
    dataType: "json",
    success: function (data) {
      console.log("respuesta de inventario de producto: ", data);
      /* Validar si ya existe el identificador con ese nombre*/

      $("#txtCostoUniVenta").val(data[0].CostoGeneral);
      $("#cmbCostoUniVenta").val(data[0].FKTipoMoneda);
    },
  });
}

//Validar que se hayan completado todos los campos antes de pasar a la siguiente pestaña
function guardarDatosVentaProducto(id) {
  var data = [];
  //El serializeArray obtiene un id consecutivo de los elementos fiel que posee el formulario
  $.each($("#formDatosTipoProducto").serializeArray(), function (i, field) {
    data.push({ id: i, campos: field.name, datos: field.value });
  });

  var costoGral = $("#txtCostoUniVenta").val();
  var monedaGral = $("#cmbCostoUniVenta").val();

  console.log(data);

  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "save_data",
      funcion: "save_datosVenta",
      datos: costoGral,
      datos2: monedaGral,
      datos3: id,
    },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta agregar datos venta del producto:", respuesta);

      if (respuesta[0].status) {
        Lobibox.notify("success", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/checkmark.svg",
          msg: "¡Datos agregados éxitosamente!",
          sound: "../../../../../sounds/sound4",
        });
        TerminarGuardadoDatos();
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
      console.log(error);
    },
  });

  //TerminarGuardadoDatos();
}

function TerminarGuardadoDatos() {
  window.location.href = "../productos";
}

/*----------------------Diseño datos de impuestos del producto-------------------------------*/
function CargarDatosImpuestos() {
  $("#CargarDatosProducto").css({ top: "0" });
  $("#CargarDatosImpuestos").css("top", "15px");
  $("#CargarTiposProducto").css("top", "0");

  var html = "<?php include('pestanas/datos_impuestos.php') ?>";

  $("#datos").append(html);
}

/*----------------------Diseño datos de tipos (acciones de compra y venta) del producto-------------------------------*/
function CargarTiposProducto() {
  $("#CargarDatosProducto").css({ top: "0" });
  $("#CargarDatosImpuestos").css("top", "0");
  $("#CargarTiposProducto").css("top", "15px");

  var html = "<label> <?php include('pestanas/datos_tipos.php') ?> <label>";

  $("#datos").append(html);
}

/// edicion
function CargarDatosProductoEdicion(idProd) {
  $("#datos").load("datos_productosedicion.php", { idProductoU: idProd });
  document.getElementById("CargarDatosProductoEdicion").style.background =
    "linear-gradient(#5bc0de,#00acc1,white)";
  document.getElementById("CargarDatosImpuestosEdicion").style.background =
    "#5cb85c";
  document.getElementById("CargarTiposProductoEdicion").style.background =
    "#757575";
}
function CargarDatosImpuestosEdicion(idProd) {
  $("#datos").load("datos_impuestosedicion.php", { idProductoU: idProd });
  document.getElementById("CargarDatosProductoEdicion").style.background =
    "#5bc0de";
  document.getElementById("CargarDatosImpuestosEdicion").style.background =
    "linear-gradient(#5cb85c,#2e7d32,white)";
  document.getElementById("CargarTiposProductoEdicion").style.background =
    "#757575";
}

function CargarTiposProductoEdicion(idProd) {
  $("#datos").load("tipos_productoedicion.php", { idProductoU: idProd });
  document.getElementById("CargarDatosProductoEdicion").style.background =
    "#5bc0de";
  document.getElementById("CargarDatosImpuestosEdicion").style.background =
    "#5cb85c";
  document.getElementById("CargarTiposProductoEdicion").style.background =
    "linear-gradient(#757575,#424242,white)";
}

//fichas

function CargarDatosProductoFicha(idProd) {
  $("#datos").load("datos_productosficha.php", { idProductoU: idProd });
  document.getElementById("CargarDatosProductoFicha").style.background =
    "linear-gradient(#5bc0de,#00acc1,white)";
  document.getElementById("CargarDatosImpuestosFicha").style.background =
    "#5cb85c";
  document.getElementById("CargarTiposProductoFicha").style.background =
    "#757575";
}
function CargarDatosImpuestosFicha(idProd) {
  $("#datos").load("datos_impuestosficha.php", { idProductoU: idProd });
  document.getElementById("CargarDatosProductoFicha").style.background =
    "#5bc0de";
  document.getElementById("CargarDatosImpuestosFicha").style.background =
    "linear-gradient(#5cb85c,#2e7d32,white)";
  document.getElementById("CargarTiposProductoFicha").style.background =
    "#757575";
}

function CargarTiposProductoFicha(idProd) {
  $("#datos").load("tipos_productoficha.php", { idProductoU: idProd });
  document.getElementById("CargarDatosProductoFicha").style.background =
    "#5bc0de";
  document.getElementById("CargarDatosImpuestosFicha").style.background =
    "#5cb85c";
  document.getElementById("CargarTiposProductoFicha").style.background =
    "linear-gradient(#757575,#424242,white)";
}

function resetTabs(id) {
  $(".nav-link").removeClass("active");
  $(id).addClass("active");
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
}

function resetValidations() {
  $(".alpha-only").on("input", function () {
    var regexp = /[^a-zA-Z ]/g;
    if ($(this).val().match(regexp)) {
      $(this).val($(this).val().replace(regexp, ""));
    }
  });

  /*Permitir solamente letras y numeros*/
  $(".alphaNumeric-onlyB").on("input", function () {
    var regexp = /[^a-zA-Z0-9á-ú @.]/g;
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
    console.log($(this).val());
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
  $("#txtCantidad").keypress(function (event) {
    event.preventDefault();
  });
  $("#txtClabeU").keypress(function (event) {
    event.preventDefault();
  });
}

function validarEmpresaProducto(pkProducto) {
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "validar_EmpresaProducto",
      data: pkProducto,
    },
    dataType: "json",
    success: function (data) {
      console.log("respuesta tipo producto validado: ", data);
      /* Validar si ya existe el identificador con ese nombre*/
      if (parseInt(data[0]["valido"]) == "1") {
        console.log("Si se puede");
        //return true;
      } else {
        console.log("No se puede" + data[0]["valido"]);
        window.location.href = "../productos";
        //return false;
      }
    },
  });
}

/* Reiniciar el modal al cerrarlo */
$("#editar_Proveedor").on("hidden.bs.modal", function (e) {
  $("#invalid-provEdit").css("display", "none");
  $("#cmbProveedorProductoEdit").removeClass("is-invalid");

  $("#invalid-precioProdEdit").css("display", "none");
  $("#txtPrecioProdProveEdit").removeClass("is-invalid");
  $("#txtPrecioProdProveEdit").val("");

  $("#txtNombreProdProveEdit").val("");
  $("#txtClaveProdProveEdit").val("");
  $("#txtCantMinProdProveEdit").val("");
  $("#txtDiasEntregProdProveEdit").val("");
  $("#txtUnidadMedidaEdit").val("");
});
function validate_PermissionsCat(pkPantalla) {
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "validar_Permisos_Cat",
      data: pkPantalla,
    },
    dataType: "json",
    success: function (data) {
      _permissionsCat.read = data[0].isRead;
      _permissionsCat.add = data[0].isAdd;
      _permissionsCat.edit = data[0].isEdit;
      _permissionsCat.delete = data[0].isDelete;
      _permissionsCat.export = data[0].isExport;

      cargarCMBCategoria("", "cmbCategoriaProducto");
    },
  });
}

function validate_PermissionsMar(pkPantalla) {
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "validar_Permisos_Mar",
      data: pkPantalla,
    },
    dataType: "json",
    success: function (data) {
      _permissionsMar.read = data[0].isRead;
      _permissionsMar.add = data[0].isAdd;
      _permissionsMar.edit = data[0].isEdit;
      _permissionsMar.delete = data[0].isDelete;
      _permissionsMar.export = data[0].isExport;

      cargarCMBMarca("", "cmbMarcaProducto");
    },
  });
}

function validate_Permissions(pkPantalla, pestana) {
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "validar_Permisos", data: pkPantalla },
    dataType: "json",
    success: function (data) {
      _permissions.read = data[0].isRead;
      _permissions.add = data[0].isAdd;
      _permissions.edit = data[0].isEdit;
      _permissions.delete = data[0].isDelete;
      _permissions.export = data[0].isExport;

      if (pestana == "url") {
        if (_permissions.add == "0") {
          window.location.href = "../productos";
        }
      }
    },
  });
}
