var slimPaisModalEdit = new SlimSelect({
  select: "#cmbPaisEdit",
  placeholder: "Selecciona una opción",
});
cargarCMBPaisesEditModal(slimPaisModalEdit);

var slimEstadoModalEdit = new SlimSelect({
  select: "#cmbEstadoEdit",
  deselectLabel: '<span class="">✖</span>',
  placeholder: "Selecciona una opción",
});
//cargarCMBEstadosEditModal(slimEstadoModalEdit)

var slimBancoModalEdit = new SlimSelect({
  select: "#cmbBancoEdit",
  deselectLabel: '<span class="">✖</span>',
  placeholder: "Selecciona una opción",
});

var slimCostoModalEdit = new SlimSelect({
  select: "#cmbCostoUniVentaEspecialEdit",
  deselectLabel: '<span class="">✖</span>',
  placeholder: "Selecciona una opción",
});

var slimProductoModalEdit = new SlimSelect({
  select: "#cmbProveedorProductoEdit",
  deselectLabel: '<span class="">✖</span>',
  placeholder: "Selecciona una opción",
});

var slimMonedaModalEdit = new SlimSelect({
  select: "#cmbMonedaPrecioEdit",
  deselectLabel: '<span class="">✖</span>',
  placeholder: "Selecciona una opción",
});


var _permissions = {
  read: 0,
  add: 0,
  edit: 0,
  delete: 0,
  export: 0,
};

/*----------------------Diseño datos del proveedor-------------------------------*/

//Cargar pestaña de Datos del proveedor
function CargarDatosProveedor(id) {
  validate_Permissions(12, 1, id);

  resetTabs("#CargarDatosEdicionProveedor");

  cargarCMBEstatus("", "cmbEstatusProveedor");
  //cargarCMBMedioContactoCliente('','cmbMedioContactoCliente');
  //cargarCMBVendedor('','cmbVendedor');

  var html = `<div class="card shadow mb-4">
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-12">
                      <form id="formDatosProveedor"> 
                        <div class="form-group">
                          <div class="row">
                            <div class="col-xl-9 col-lg-9 col-md-6 col-sm-3 col-xs-0">
                              
                            </div>
                            <div class="col-xl-1 col-lg-1 col-md-2 col-sm-4 col-xs-6">
                              <label for="usr">Estatus:*</label>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-4 col-sm-5 col-xs-6">
                              <input type="checkbox" id="active-proveedor" class="check-custom" checked>
                              <label class="shadow-sm check-custom-label" for="active-proveedor">
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
                              <label for="usr">Nombre comercial:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control alphaNumeric-only" type="text" name="txtNombreComercial" id="txtNombreComercial" autofocus="" required="" maxlength="255" placeholder="Ej. GH Medic" onkeyup="escribirNombre()">
                                  <div class="invalid-feedback" id="invalid-nombreProv">El proveedor debe tener un nombre.</div>
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-6">
                              <label for="usr">Vendedor:</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control alphaNumeric-only" type="text" name="cmbVendedor" id="cmbVendedor" maxlength="255" placeholder="Ej. José María López Pérez" onkeypress="return soloLetras(event)">
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="row">
                            <div class="col-lg-6">
                              <label for="usr">Teléfono:</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control numeric-only" type="text" name="txtTelefono" id="txtTelefono" minlength="7" maxlength="10" placeholder="Ej. 33 3333 33 33">
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-6">
                              <label for="usr">Móvil:</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                <input class="form-control numeric-only" type="text" name="txtMovil" id="txtMovil" minlength="7" maxlength="10" placeholder="Ej. 33 3333 33 33">
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="row">
                            <div class="col-lg-6">
                              <label for="usr">E-mail principal:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control alphaNumeric-only" type="email" name="txtEmail" id="txtEmail" required="" maxlength="100" placeholder="Ej. ejemplo@dominio.com" onkeyup="validarCorreo(this)">
                                  <div class="invalid-feedback" id="invalid-emailProv">El proveedor debe tener un email.</div>
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-6">
                              <label for="usr">E-mail secundario:</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control alphaNumeric-only" type="email" name="txtEmail2" id="txtEmail2" maxlength="100" placeholder="Ej. ejemplo@dominio.com" onkeyup="validarCorreo(this)">
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        
                        <br>
                        <div class="form-group">
                          <div class="row">
                            <div class="col-lg-2">
                              <label for="usr">Agregar crédito:</label>
                              <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="cbxCredito" name="cbxCredito" onclick="activarCredito()">
                                <label class="form-check-label" for="cbxCredito">Activar crédito</label>
                              </div>
                            </div>
                            
                            <div class="col-lg-5">
                              <label for="usr">Monto de crédito:</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input type="text" class="form-control numeric-only" name="txtMontoCredito" id="txtMontoCredito" maxlength="13"  oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" placeholder="Ej. 1000.00" disabled required onkeyup="validEmptyInput(this)">
                                  <div class="invalid-feedback" id="invalid-montoProv">El credito debe tener un monto.</div>
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-5">
                              <label for="usr">Días de crédito:</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input type="text" class="form-control numeric-only" name="txtDiasCredito" id="txtDiasCredito" maxlength="3" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" max="999" min="0" placeholder="Ej. 90" disabled required onkeyup="validEmptyInput(this)">
                                  <div class="invalid-feedback" id="invalid-diasProv">El credito debe tener un numero de días</div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <br>
                        <div class="form-group">
                          <div class="row">
                            <div class="col-lg-6">
                              <label for="usr">Tipo de persona:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <select class="form-control" name="cmbTipoPersona" id="cmbTipoPersona" placeholder="Seleccionar tipo de persona" onchange="cambioTipoPersona()" required>
                                    <option data-placeholder="true"></option>  
                                    <option value="Física">Física</option>
                                    <option value="Moral">Moral</option>
                                  </select>
                                  <div class="invalid-feedback" id="invalid-tipoPersonaProv">El proveedor debe tener un tipo de persona.</div>
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-6">
                                <label for="usr">Giro:</label>
                                <div class="row">
                                  <div class="col-lg-12 input-group">
                                    <input class="form-control alphaNumeric-only" type="text" name="txtGiro" id="txtGiro" maxlength="100" placeholder="Ej. Plásticos">
                                  </div>
                                </div>
                            </div>
                          </div>
                        </div>
                        <br>
                        <label for="">* Campos requeridos</label>
                      </form>
                      <a href="#" class="btn-custom btn-custom--blue float-right" id="btnAgregarProveedor">Guardar</a>
                    </div>
                  </div>
                </div>
              </div>`;

  $("#datos").append(html);

  mostrardatosGenerales(id);
}

function cambioTipoPersona() {
  var tipoPersona = $("#cmbTipoPersona").val();
  if (tipoPersona) {
    $("#invalid-tipoPersonaProv").css("display", "none");
    $("#cmbTipoPersona").removeClass("is-invalid");
  } else {
    $("#invalid-tipoPersonaProv").css("display", "block");
    $("#cmbTipoPersona").addClass("is-invalid");
  }
}

function soloLetras(e) {
  var key = e.keyCode || e.which,
    tecla = String.fromCharCode(key).toLowerCase(),
    letras = " áéíóúabcdefghijklmnñopqrstuvwxyz",
    especiales = [8, 37, 39, 46],
    tecla_especial = false;

  for (var i in especiales) {
    if (key == especiales[i]) {
      tecla_especial = true;
      break;
    }
  }

  if (letras.indexOf(tecla) == -1 && !tecla_especial) {
    return false;
  }
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
      html += '<option data-placeholder="true"></option>';

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

function cargarCMBMedioContactoCliente(data, input) {
  var html = "";
  var selected;
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_mediosContacto" },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta medios de contacto: ", respuesta);

      html += '<option data-placeholder="true"></option>';

      $.each(respuesta, function (i) {
        if (data === respuesta[i].PKMedioContactoCliente) {
          selected = "selected";
        } else {
          selected = "";
        }

        html +=
          '<option value="' +
          respuesta[i].PKMedioContactoCliente +
          '" ' +
          selected +
          ">" +
          respuesta[i].MedioContactoCliente +
          "</option>";
      });

      html +=
        '<option value="add" style="background-color: #15589B;  color:white; text-align:center; width:100%;">Configurar medios de contacto</option>';

      $("#" + input + "").html(html);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function cargarCMBVendedor(data, input) {
  var html = "";
  var selected;
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_vendedor" },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta vendedor: ", respuesta);

      html += '<option data-placeholder="true"></option>';

      $.each(respuesta, function (i) {
        if (data === respuesta[i].PKVendedor) {
          selected = "selected";
        } else {
          selected = "";
        }

        html +=
          '<option value="' +
          respuesta[i].PKVendedor +
          '" ' +
          selected +
          ">" +
          respuesta[i].Nombre +
          "</option>";
      });

      html +=
        '<option value="add" style="background-color: #15589B;  color:white; text-align:center; width:100%;">Configurar vendedores</option>';

      $("#" + input + "").html(html);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

/*----------------------Estilos de funciones-------------------------------*/

//Funciones para los eventos de los elementos de la página
function mostrarColor() {
  if (document.getElementById("cmbEstatusProveedor").value == 1) {
    document.getElementById("cmbEstatusProveedor").style.background = "#28c67a";
    document.getElementById("cmbEstatusProveedor").style.color = "#FFFFFF";
  } else {
    document.getElementById("cmbEstatusProveedor").style.background = "#cac8c6";
  }
}

function cambiarColor() {
  //Cambiar de color los combos al abrir por primera vez la página
  $("#opEG-1").css({ "background-color": "#28c67a", color: "#FFFFFF" });
  $("#opEG-2").css({ "background-color": "#cac8c6" });

  if (document.getElementById("cmbEstatusProveedor").value == 1) {
    document.getElementById("cmbEstatusProveedor").style.background = "#28c67a";
    document.getElementById("cmbEstatusProveedor").style.color = "#FFFFFF";
  } else {
    document.getElementById("cmbEstatusProveedor").style.background = "#cac8c6";
  }
}

//Funciones para los eventos de los elementos de la página

function escribirNombre() {
  var valor = $("#txtNombreComercial").val();
  var valorHis = $("#txtNombreComercialHis").val();

  if (valor != valorHis) {
    console.log("Valor nombre" + valor);
    $.ajax({
      url: "../../php/funciones.php",
      data: {
        clase: "get_data",
        funcion: "validar_nombreComercial",
        data: valor,
      },
      dataType: "json",
      success: function (data) {
        console.log("respuesta nombre valida: ", data);
        /* Validar si ya existe el identificador con ese nombre*/
        if (parseInt(data[0]["existe"]) == 1) {
          $("#invalid-nombreProv").text(
            "El nombre ya esta registrado en el sistema."
          );
          $("#invalid-nombreProv").css("display", "block");
          $("#txtNombreComercial").addClass("is-invalid");
        } else {
          $("#invalid-nombreProv").text("El proveedor debe tener un nombre.");
          $("#invalid-nombreProv").css("display", "none");
          $("#txtNombreComercial").removeClass("is-invalid");
          console.log("¡No existe!");
        }
      },
    });
  }
}

function validarCorreo(item) {
  console.log(item.value);
  var reg =
    /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

  var regOficial =
    /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/;

  //Se muestra un texto a modo de ejemplo, luego va a ser un icono
  if (reg.test(item.value) && regOficial.test(item.value)) {
    $("#invalid-emailProv").text("El proveedor debe tener un email.");
    $("#invalid-emailProv").css("display", "none");
    $(item).removeClass("is-invalid");
  } else if (reg.test(item.value)) {
    $("#invalid-emailProv").text("El proveedor debe tener un email.");
    $("#invalid-emailProv").css("display", "none");
    $(item).removeClass("is-invalid");
  } else {
    $("#invalid-emailProv").text("Debe ser un email valido.");
    $("#invalid-emailProv").css("display", "none");
    $(item).addClass("is-invalid");
  }
}

function activarCredito() {
  if ($("#cbxCredito").is(":checked")) {
    console.log("Checked");
    $("#txtMontoCredito").prop("disabled", false);
    $("#txtDiasCredito").prop("disabled", false);
  } else {
    console.log("No checked");
    $("#txtMontoCredito").prop("disabled", true);
    $("#txtDiasCredito").prop("disabled", true);

    $("#txtMontoCredito").val("");
    $("#txtDiasCredito").val("");

    $("#invalid-montoProv").css("display", "none");
    $("#txtMontoCredito").removeClass("is-invalid");
    $("#invalid-diasProv").css("display", "none");
    $("#txtDiasCredito").removeClass("is-invalid");
  }
}
/*----------------------Botón agregar cliente-------------------------------*/

//Validar que se hayan completado todos los campos antes de pasar a la siguiente pestaña
$(document).on("click", "#btnAgregarProveedor", function () {
  if ($("#formDatosProveedor")[0].checkValidity()) {
    var badNombreCom =
      $("#invalid-nombreProv").css("display") === "block" ? false : true;
    var badEmail =
      $("#invalid-emailProv").css("display") === "block" ? false : true;
    var badMonto =
      $("#invalid-montoProv").css("display") === "block" ? false : true;
    var badDiasCred =
      $("#invalid-diasProv").css("display") === "block" ? false : true;
    var badTipoPersona =
      $("#invalid-tipoPersonaProv").css("display") === "block" ? false : true;
    if (badNombreCom && badEmail && badMonto && badDiasCred && badTipoPersona) {
      var data = [];
      //El serializeArray obtiene un id consecutivo de los elementos fiel que posee el formulario
      $.each($("#formDatosProveedor").serializeArray(), function (i, field) {
        data.push({ id: i, campos: field.name, datos: field.value });
      });
      var pkProveedor = $("#txtPKProveedor").val();
      var montoCredito, diasCredito;

      if ($("#cbxCredito").is(":checked")) {
        montoCredito = $("#txtMontoCredito").val();
        diasCredito = $("#txtDiasCredito").val();
      } else {
        montoCredito = "0";
        diasCredito = "0";
      }

      var nombreComercial = $("#txtNombreComercial").val();
      var vendedor = $("#cmbVendedor").val();
      var telefono = $("#txtTelefono").val();
      var email = $("#txtEmail").val();
      var tipoPersona = $("#cmbTipoPersona").val();
      var email2 = $("#txtEmail2").val();
      var movil = $("#txtMovil").val();
      var giro = $("#txtGiro").val();

      var estatus = $("#active-proveedor").prop("checked") ? 1 : 0;

      $.ajax({
        url: "../../php/funciones.php",
        data: {
          clase: "edit_data",
          funcion: "edit_datosProveedorTable",
          datos: data,
          datos2: nombreComercial,
          datos4: vendedor,
          datos5: montoCredito,
          datos6: diasCredito,
          datos7: telefono,
          datos8: email,
          datos9: estatus,
          datos10: pkProveedor,
          datos11: tipoPersona,
          datos12: email2,
          datos13: movil,
          datos14: giro
        },
        dataType: "json",
        success: function (respuesta) {
          console.log(
            "respuesta agregar datos generales del proveedor:",
            respuesta
          );

          if (respuesta[0].status) {
            Lobibox.notify("success", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../../img/timdesk/checkmark.svg",
              msg: "¡Información del proveedor registrada correctamente!",
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
  } else {
    if (!$("#txtNombreComercial").val()) {
      $("#invalid-nombreProv").css("display", "block");
      $("#txtNombreComercial").addClass("is-invalid");
    }
    if (!$("#txtEmail").val()) {
      $("#invalid-emailProv").css("display", "block");
      $("#txtEmail").addClass("is-invalid");
    }
    if ($("#cbxCredito").prop("checked")) {
      if (!$("#txtMontoCredito").val()) {
        $("#invalid-montoProv").css("display", "block");
        $("#txtMontoCredito").addClass("is-invalid");
      }
      if (!$("#txtDiasCredito").val()) {
        $("#invalid-diasProv").css("display", "block");
        $("#txtDiasCredito").addClass("is-invalid");
      }
    }
    if (!$("#cmbTipoPersona").val()) {
      $("#invalid-tipoPersonaProv").css("display", "block");
    }
  }
});

function regresarDatosProveedor(id) {
  window.location.href = "editar_proveedor.php?p=" + id;
}

function SeguirDatosFiscales(id) {
  validate_Permissions(12, 2, id);

  validarEmpresaProveedor(id);
  //$('#datos').load('datos_impuestos.php',{idProducto : idProd});
  resetTabs("#CargarDatosEdicionFiscal");

  console.log("Id recien agregado:" + id);

  cargarCMBPaises("241", "cmbPais");
  cargarCMBEstados("241", "cmbEstado");

  //cargarCMBPaisesEditModal("241", "cmbPaisEdit");
  //cargarCMBEstadosEditModal("241", "cmbEstadoEdit");

  var html =
    `<div class="card shadow mb-4">
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-12">
                      <form id="formDatosFiscales"> 
                        <input type='hidden' value='${id}' name="txtPKProveedor" id="txtPKProveedor">
                        <span id="areaDiseno">
                        
                        <div class="form-group">
                          <div class="row">
                            <div class="col-lg-6">
                              <label for="usr">Razón Social:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control" type="text" name="txtRazonSocial" id="txtRazonSocial" autofocus="" required="" maxlength="100" placeholder="Ej. GH Medic S.A. de C.V." onkeyup="escribirRazonSocial(${id})">
                                  <div class="invalid-feedback" id="invalid-razonSoc">El proveedor debe tener una razón social.</div>
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-6">
                              <label for="usr">RFC:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control alphaNumeric-only" type="text" name="txtRFC" id="txtRFC" required="" maxlength="13" placeholder="Ej. GHMM100101AA1" onkeyup="validarInput(${id})" oninput="let p=this.selectionStart;this.value=this.value.toUpperCase();this.setSelectionRange(p, p);">
                                  <div class="invalid-feedback" id="invalid-rfc">El proveedor debe tener un RFC.</div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="row">
                            <div class="col-lg-6">
                              <label for="usr">Calle:</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control" type="text" name="txtCalle" id="txtCalle" maxlength="255" placeholder="Ej. Av. México">
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-3">
                              <label for="usr">Número exterior:</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control" type="text" name="txtNumExt" id="txtNumExt" maxlength="100" placeholder="Ej. 2353 A">
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-3">
                              <label for="usr">Número interior:</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control" type="text" name="txtNumInt" id="txtNumInt" maxlength="30" placeholder="Ej. 524">
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="row">
                            <div class="col-lg-3">
                              <label for="usr">Colonia:</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control" type="text" name="txtColonia" id="txtColonia" maxlength="255" placeholder="Ej. Los Agaves">
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-3">
                              <label for="usr">Municipio:</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control" type="text" name="txtMunicipio" id="txtMunicipio" maxlength="255" placeholder="Ej. Guadalajara" onkeypress="return soloLetras(event)">
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-2">
                              <label for="usr">País:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <select name="cmbPais" id="cmbPais" onchange="cambioPais()" required>
                                  </select>
                                  <div class="invalid-feedback" id="invalid-pais">El proveedor debe tener un pais.</div>
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-2">
                              <label for="usr">Estado:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <select name="cmbEstado" id="cmbEstado" onchange="validEmptyInput(this)" required>
                                  </select>
                                  <div class="invalid-feedback" id="invalid-estado">El proveedor debe tener un estado.</div>
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-2">
                              <label for="usr">Código Postal:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control numeric-only" type="text" name="txtCP" id="txtCP" required="" maxlength="5" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" placeholder="Ej. 52632" onkeyup="validarCP();">
                                  <div class="invalid-feedback" id="invalid-cp">El proveedor debe tener un CP.</div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="row">
                            <div class="col-lg-4">
                              <label for="usr">Localidad:</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control" type="text" name="txtLocalidad" id="txtLocalidad" maxlength="255" placeholder="Ej. Camichines">
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <label for="usr">Referencia:</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control" type="text" name="txtReferencia" id="txtReferencia" maxlength="255" placeholder="Ej. Contacto camichines" onkeypress="return soloLetras(event)">
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-4">
                            </div>
                          </div>
                        </div>
                        <label for="">* Campos requeridos</label>
                        <div class="form-group">
                          <div class="row">
                            <div class="col-12 text-right" id="btnAnadirProveedor2">
                              
                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <!-- DataTales Example -->
                          <div class="card mb-4 internal-table">
                            <div class="card-body">
                              <div class="table-responsive">
                                <table class="table" id="tblListadoDatosFiscalesProveedor" width="100%" cellspacing="0">
                                  <thead>
                                    <tr>
                                      <th>Id</th>
                                      <th>Razón Social</th>
                                      <th>RFC</th>
                                      <th>Calle</th>
                                      <th>Número Exterior</th>
                                      <th>Número Interior</th>
                                      <th>Colonia</th>
                                      <th>Municipio</th>
                                      <th>Estado</th>
                                      <th>Pais</th>
                                      <th>CP</th>
                                      <th>Localidad</th>
                                      <th>Referencia</th>
                                      <th>Acciones</th>
                                    </tr>
                                  </thead>
                                </table>
                              </div>
                            </div>
                          </div>
                        </div>
                        </span>
                        <input type="hidden" value="0" id="txtEdicion">
                        <input type="hidden" value="0" id="txtRazonSocialHis">
                        <input type="hidden" value="0" id="txtRFCHis">
                        <input type="hidden" value="0" id="txtPKRazon">
                      </form>
                    </div>
                  </div>
                </div>
              </div>`;

  $("#datos").html(html);

  resetValidations();
}

function rfcValido(rfc, aceptarGenerico = true) {
  const re =
    /^([A-ZÑ&]{3,4}) ?(?:- ?)?(\d{2}(?:0[1-9]|1[0-2])(?:0[1-9]|[12]\d|3[01])) ?(?:- ?)?([A-Z\d]{2})([A\d])$/;
  var validado = rfc.match(re);

  if (!validado)
    //Coincide con el formato general del regex?
    return false;

  //Separar el dígito verificador del resto del RFC
  const digitoVerificador = validado.pop(),
    rfcSinDigito = validado.slice(1).join(""),
    len = rfcSinDigito.length,
    //Obtener el digito esperado
    diccionario = "0123456789ABCDEFGHIJKLMN&OPQRSTUVWXYZ Ñ",
    indice = len + 1;
  var suma, digitoEsperado;

  if (len == 12) suma = 0;
  else suma = 481; //Ajuste para persona moral

  for (var i = 0; i < len; i++)
    suma += diccionario.indexOf(rfcSinDigito.charAt(i)) * (indice - i);
  digitoEsperado = 11 - (suma % 11);
  if (digitoEsperado == 11) digitoEsperado = 0;
  else if (digitoEsperado == 10) digitoEsperado = "A";

  //El dígito verificador coincide con el esperado?
  // o es un RFC Genérico (ventas a público general)?
  if (
    digitoVerificador != digitoEsperado &&
    (!aceptarGenerico || rfcSinDigito + digitoVerificador != "XAXX010101000")
  )
    return false;
  else if (
    !aceptarGenerico &&
    rfcSinDigito + digitoVerificador == "XEXX010101000"
  )
    return false;
  return rfcSinDigito + digitoVerificador;
}

//Handler para el evento cuando cambia el input
// -Lleva la RFC a mayúsculas para validarlo
// -Elimina los espacios que pueda tener antes o después
function validarInput(id) {
  var vRFC = $("#txtRFC").val();
  var rfc = vRFC.trim().toUpperCase();

  var rfcCorrecto = rfcValido(rfc); // ⬅️ Acá se comprueba

  if (rfcCorrecto) {
    escribirRFC(id);
  } else {
    $("#invalid-rfc").text("El RFC debe ser valido.");
    $("#invalid-rfc").css("display", "block");
    $("#txtRFC").addClass("is-invalid");
  }
}

function validarInputEditModal(id) {
  var vRFC = $("#txtRFCEdit").val();
  var vRFCHis = $("#txtRFCHis").val();
  console.log(vRFCHis);
  console.log(vRFC);

  if (vRFC != vRFCHis) {
    console.log("Distintos");
    var rfc = vRFC.trim().toUpperCase();

    var rfcCorrecto = rfcValido(rfc); // Acá se comprueba

    if (rfcCorrecto) {
      escribirRFCEditModal(id);
    } else {
      $("#invalid-rfcEdit").text("Debe ser un RFC valido.");
      $("#invalid-rfcEdit").css("display", "block");
      $("#txtRFCEdit").addClass("is-invalid");
    }
  } else {
    $("#invalid-rfcEdit").text("El proveedor debe tener un RFC.");
    $("#invalid-rfcEdit").css("display", "none");
    $("#txtRFCEdit").removeClass("is-invalid");
  }
}

function validarCP() {
  var value = $("#txtCP").val();
  var ercp = /(^([0-9]{5,5})|^)$/;
  if (!ercp.test(value)) {
    $("#invalid-cp").text("El CP debe ser valido.");
    $("#invalid-cp").css("display", "block");
    $("#txtCP").addClass("is-invalid");
  } else {
    $("#invalid-cp").text("El proveedor debe tener un CP.");
    $("#invalid-cp").css("display", "none");
    $("#txtCP").removeClass("is-invalid");
  }
}

function validarCPEditModal() {
  var value = $("#txtCPEdit").val();
  var ercp = /(^([0-9]{5,5})|^)$/;
  if (!ercp.test(value)) {
    $("#invalid-cpEdit").text("El CP debe ser valido.");
    $("#invalid-cpEdit").css("display", "block");
    $("#txtCPEdit").addClass("is-invalid");
  } else {
    $("#invalid-cpEdit").text("El proveedor debe tener un CP.");
    $("#invalid-cpEdit").css("display", "none");
    $("#txtCPEdit").removeClass("is-invalid");
  }
}

/*----------------------Funciones de carga de datos de los combos-------------------------------*/
function cargarCMBPaises(data, input) {
  var html = "";
  var selected;
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_paises" },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta paises: ", respuesta);
      html += '<option data-placeholder="true"></option>';
      $.each(respuesta, function (i) {
        if (data === respuesta[i].PKPais) {
          selected = "selected";
        } else {
          selected = "";
        }

        html +=
          '<option value="' +
          respuesta[i].PKPais +
          '">' +
          respuesta[i].Pais +
          "</option>";
      });

      CargarSlimPaises();

      $("#" + input + "").html(html);
      $("#cmbPais").val("241");
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function cargarCMBPaisesEditModal(slim) {
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_paises" },
    dataType: "json",
    success: function (respuesta) {
      var responseFormated = [{'placeholder': true, 'text': 'Selecciona una opción'}];

      respuesta.forEach((item) => {
        responseFormated.push({text: item.Pais, value: item.PKPais});
      });

      slim.setData(responseFormated)
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function cargarCMBPaisesEdit(data, input, value) {
  var html = "";
  var selected;
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_paises" },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta paises: ", respuesta);
      html += '<option data-placeholder="true"></option>';

      $.each(respuesta, function (i) {
        if (data === respuesta[i].PKPais) {
          selected = "selected";
        } else {
          selected = "";
        }

        html +=
          '<option value="' +
          respuesta[i].PKPais +
          '">' +
          respuesta[i].Pais +
          "</option>";
      });

      $("#" + input + "").html(html);
      $("#cmbPais").val(value);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function cargarCMBPaisesEditModalEdit(data, input, value) {
  var html = "";
  var selected;
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_paises" },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta paises: ", respuesta);
      html += '<option data-placeholder="true"></option>';

      $.each(respuesta, function (i) {
        if (data === respuesta[i].PKPais) {
          selected = "selected";
        } else {
          selected = "";
        }

        html +=
          '<option value="' +
          respuesta[i].PKPais +
          '">' +
          respuesta[i].Pais +
          "</option>";
      });

      $("#" + input + "").html(html);
      $("#cmbPaisEdit").val(value);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function cargarCMBEstados(data, input) {
  var valor = data;
  console.log("PKPais: " + valor);

  var html = "";
  var selected;

  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_estados", data: valor },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta estados: ", respuesta);
      html += '<option data-placeholder="true"></option>';

      $.each(respuesta, function (i) {
        if (data === respuesta[i].PKEstado) {
          selected = "selected";
        } else {
          selected = "";
        }

        html +=
          '<option value="' +
          respuesta[i].PKEstado +
          '" ' +
          selected +
          ">" +
          respuesta[i].Estado +
          "</option>";
      });

      $("#" + input + "").html(html);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function cargarCMBEstadosEdit(data, input, value) {
  var valor = data;
  var valor2 = value;
  console.log("PKEstado: " + valor2);

  var html = "";
  var selected;

  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_estados", data: valor },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta estados: ", respuesta);
      html += '<option data-placeholder="true"></option>';

      $.each(respuesta, function (i) {
        if (data === respuesta[i].PKEstado) {
          selected = "selected";
        } else {
          selected = "";
        }

        html +=
          '<option value="' +
          respuesta[i].PKEstado +
          '" ' +
          selected +
          ">" +
          respuesta[i].Estado +
          "</option>";
      });

      $("#" + input + "").html(html);
      $("#cmbEstado").val(value);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function cargarCMBEstadosEditModalEdit(data, input, value) {
  var valor = data;
  var valor2 = value;
  console.log("PKEstado: " + valor2);

  var html = "";
  var selected;

  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_estados", data: valor },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta estados: ", respuesta);
      html += '<option data-placeholder="true"></option>';

      $.each(respuesta, function (i) {
        if (data === respuesta[i].PKEstado) {
          selected = "selected";
        } else {
          selected = "";
        }

        html +=
          '<option value="' +
          respuesta[i].PKEstado +
          '" ' +
          selected +
          ">" +
          respuesta[i].Estado +
          "</option>";
      });

      $("#" + input + "").html(html);
      $("#cmbEstadoEdit").val(value);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function cargarCMBEstadosEditModal(slim, valor) {
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_estados", data: valor },
    dataType: "json",
    success: function (respuesta) {
      var responseFormated = [{'placeholder': true, 'text': 'Selecciona una opción'}];
      respuesta.forEach((item) => {
        responseFormated.push({text: item.Estado, value: item.PKEstado});
      });
      slim.setData(responseFormated)
    },
    error: function (error) {
      console.log(error);
    },
  });
}

/*----------------------Plugin Slim para la búsqueda dentro del-------------------------------*/
function CargarSlimPaises() {
  new SlimSelect({
    select: "#cmbPais",
    deselectLabel: '<span class="">✖</span>',
    placeholder: "Selecciona una opción",
  });

  CargarSlimEstados();
}

/* function CargarSlimPaisesEditModal() {
  new SlimSelect({
    select: "#cmbPaisEdit",
    placeholder: "Selecciona una opción",
  });

  CargarSlimEstadosEditModal();
} */

function CargarSlimEstados() {
  new SlimSelect({
    select: "#cmbEstado",
    deselectLabel: '<span class="">✖</span>',
    placeholder: "Selecciona una opción",
    addable: function (value) {
      var pkPais = $("#cmbPais").val();
      validarEstado(value, pkPais);
    },
  });
}

/* function CargarSlimEstadosEditModal() {
  new SlimSelect({
    select: "#cmbEstadoEdit",
    deselectLabel: '<span class="">✖</span>',
    placeholder: "Selecciona una opción",
    addable: function (value) {
      var pkPais = $("#cmbPaisEdit").val();
      validarEstado(value, pkPais);
    },
  });
} */

/*----------------------Cambio de seleccion de pais-------------------------------*/
function cambioPais() {
  var PKPais = $("#cmbPais").val();
  if (PKPais) {
    $("#invalid-pais").css("display", "none");
    $("#cmbPais").removeClass("is-invalid");
    cargarCMBEstados(PKPais, "cmbEstado");
  } else {
    $("#invalid-pais").css("display", "block");
    $("#cmbPais").addClass("is-invalid");
  }
}

function cambioPaisEditModal() {
  var PKPais = $("#cmbPaisEdit").val();
  if (PKPais) {
    $("#invalid-paisEdit").css("display", "none");
    $("#cmbPaisEdit").removeClass("is-invalid");
    //cargarCMBEstadosEditModal(PKPais, "cmbEstadoEdit");
  } else {
    $("#invalid-paisEdit").css("display", "block");
    $("#cmbPaisEdit").addClass("is-invalid");
  }
}

/*----------------------------Botón añadir razón social ---------------------------*/

function validarEstado(estado, pkPais) {
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "validar_estado",
      data: estado,
      data2: pkPais,
    },
    dataType: "json",
    success: function (data) {
      console.log("respuesta estado validado: ", data);
      /* Validar si ya existe el identificador con ese nombre*/
      if (parseInt(data[0]["existe"]) == 1) {
        //$("#notaEstado").css("display","block");

        console.log("¡Ya existe!");
      } else {
        //$("#notaEstado").css("display","none");
        anadirEstado(estado, pkPais);

        console.log("¡No existe!");
      }
    },
  });
}

/* Añadir el impuesto */
function anadirEstado(estado, pkPais) {
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "save_data",
      funcion: "save_estado_pais",
      data: estado,
      data2: pkPais,
    },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta agregar estado:", respuesta);

      if (respuesta[0].status) {
        Lobibox.notify("success", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/checkmark.svg",
          msg: "¡Se guardó el estado con éxito!",
          sound: '../../../../../sounds/sound4'
        });
        cargarCMBEstados(pkPais, "cmbEstado");
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

function escribirRazonSocial(id) {
  var razonSocial = $("#txtRazonSocial").val();
  var razonSocialHis = $("#txtRazonSocialHis").val();

  if (razonSocial != razonSocialHis) {
    $.ajax({
      url: "../../php/funciones.php",
      data: {
        clase: "get_data",
        funcion: "validar_razonSocial_Proveedor",
        data: razonSocial,
        data2: id,
      },
      dataType: "json",
      success: function (data) {
        console.log("respuesta razón social validado: ", data);
        /* Validar si ya existe el identificador con ese nombre*/
        if (parseInt(data[0]["existe"]) == 1) {
          $("#invalid-razonSoc").text(
            "La razón Social ya esta registrada en el sistema."
          );
          $("#invalid-razonSoc").css("display", "block");
          $("#txtRazonSocial").addClass("is-invalid");
        } else {
          $("#invalid-razonSoc").text(
            "El proveedor debe tener una razón social."
          );
          $("#invalid-razonSoc").css("display", "none");
          $("#txtRazonSocial").removeClass("is-invalid");
        }
      },
    });
  }
}

function escribirRazonSocialEditModal(id) {
  var razonSocial = $("#txtRazonSocialEdit").val();
  var razonSocialHis = $("#txtRazonSocialHis").val();
  console.log("id:", id, "rz:", razonSocial, "rzh:", razonSocialHis);

  if (razonSocial != razonSocialHis) {
    $.ajax({
      url: "../../php/funciones.php",
      data: {
        clase: "get_data",
        funcion: "validar_razonSocial_Proveedor",
        data: razonSocial,
        data2: id,
      },
      dataType: "json",
      success: function (data) {
        console.log("respuesta razón social validado: ", data);
        /* Validar si ya existe el identificador con ese nombre*/
        if (parseInt(data[0]["existe"]) == 1) {
          $("#invalid-razonSocEdit").text(
            "La razón Social ya esta registrada en el sistema."
          );
          $("#invalid-razonSocEdit").css("display", "block");
          $("#txtRazonSocialEdit").addClass("is-invalid");
        } else {
          $("#invalid-razonSocEdit").text(
            "El proveedor debe tener una razón social."
          );
          $("#invalid-razonSocEdit").css("display", "none");
          $("#txtRazonSocialEdit").removeClass("is-invalid");
        }
      },
    });
  } else {
    $("#invalid-razonSoc").text("El proveedor debe tener una razón social.");
    $("#invalid-razonSoc").css("display", "none");
    $("#txtRazonSocialEdit").removeClass("is-invalid");
  }
}

function escribirRFC(id) {
  var rfc = $("#txtRFC").val();
  var rfcHis = $("#txtRFCHis").val();

  if (rfc != rfcHis) {
    $.ajax({
      url: "../../php/funciones.php",
      data: {
        clase: "get_data",
        funcion: "validar_rfc_Proveedor",
        data: rfc,
        data2: id,
      },
      dataType: "json",
      success: function (data) {
        console.log("respuesta RFC validado: ", data);
        /* Validar si ya existe el identificador con ese nombre*/
        if (parseInt(data[0]["existe"]) == 1) {
          $("#invalid-rfc").text("El RFC ya esta registado en el sistema.");
          $("#invalid-rfc").css("display", "block");
          $("#txtRFC").addClass("is-invalid");
        } else {
          $("#invalid-rfc").text("El proveedor debe tener un RFC.");
          $("#invalid-rfc").css("display", "none");
          $("#txtRFC").removeClass("is-invalid");
        }
      },
    });
  }
}

function escribirRFCEditModal(id) {
  var rfc = $("#txtRFCEdit").val();
  var rfcHis = $("#txtRFCHis").val();

  if (rfc != rfcHis) {
    $.ajax({
      url: "../../php/funciones.php",
      data: {
        clase: "get_data",
        funcion: "validar_rfc_Proveedor",
        data: rfc,
        data2: id,
      },
      dataType: "json",
      success: function (data) {
        console.log("respuesta RFC validado: ", data);
        /* Validar si ya existe el identificador con ese nombre*/
        if (parseInt(data[0]["existe"]) == 1) {
          $("#notaRFCEdit").css("display", "block");

          console.log("¡Ya existe!");
        } else {
          $("#notaRFCEdit").css("display", "none");

          console.log("¡No existe!");
        }
      },
    });
  }
}

function anadirRazonSocial(id) {
  if ($("#formDatosFiscales")[0].checkValidity()) {
    agreRZ(id);
  } else {
    if (!$("#txtRazonSocial").val()) {
      $("#invalid-razonSoc").css("display", "block");
      $("#txtRazonSocial").addClass("is-invalid");
    }
    if (!$("#txtRFC").val()) {
      $("#invalid-razonSoc").css("display", "block");
      $("#txtRFC").addClass("is-invalid");
    }
    if (!$("#cmbPais").val()) {
      $("#invalid-pais").css("display", "block");
      $("#cmbPais").addClass("is-invalid");
    }
    if (!$("#cmbEstado").val()) {
      $("#invalid-estado").css("display", "block");
      $("#cmbEstado").addClass("is-invalid");
    }
    if (!$("#txtCP").val()) {
      $("#invalid-cp").css("display", "block");
      $("#txtCP").addClass("is-invalid");
    }
  }
}

function agreRZ(id) {
  var badRazonSoc =
    $("#invalid-razonSoc").css("display") === "block" ? false : true;
  var badRFC = $("#invalid-rfc").css("display") === "block" ? false : true;
  var badCP = $("#invalid-cp").css("display") === "block" ? false : true;
  var badPais = $("#invalid-pais").css("display") === "block" ? false : true;
  var badEstado =
    $("#invalid-estado").css("display") === "block" ? false : true;

  if (badRazonSoc && badRFC && badCP && badPais && badEstado) {
    var razonSocial = $("#txtRazonSocial").val();
    var rfc = $("#txtRFC").val();
    var calle = $("#txtCalle").val();
    var numExt = $("#txtNumExt").val();
    var numInt;
    if ($("#txtNumInt").val() != "") {
      numInt = $("#txtNumInt").val();
    } else {
      numInt = "S/N";
    }
    var colonia = $("#txtColonia").val();
    var municipio = $("#txtMunicipio").val();
    var pais = $("#cmbPais").val();
    var estado = $("#cmbEstado").val();
    var cp = $("#txtCP").val();
    var pkRazon = 0;
    var localidad = $("#txtLocalidad").val();
    console.log(localidad);
    if(!$("#txtLocalidad").val()){
      var localidad = ''
    }
    var referencia = $("#txtReferencia").val();
    console.log(referencia);
    if(!$("#txtReferencia").val()){
      var referencia = ''
    }

    $.ajax({
      url: "../../php/funciones.php",
      data: {
        clase: "save_data",
        funcion: "save_razonSocial_Proveedor",
        datos: razonSocial,
        datos2: rfc,
        datos4: calle,
        datos5: numExt,
        datos6: numInt,
        datos7: colonia,
        datos8: municipio,
        datos9: pais,
        datos10: estado,
        datos11: cp,
        datos12: id,
        datos13: pkRazon,
        datos14: localidad,
        datos15: referencia
      },
      dataType: "json",
      success: function (respuesta) {
        console.log("respuesta añadir razon social:", respuesta);

        if (respuesta[0].status) {
          $("#tblListadoDatosFiscalesProveedor").DataTable().ajax.reload();
          $("#txtRazonSocial").val("");
          $("#txtRFC").val("");
          $("#txtCalle").val("");
          $("#txtNumExt").val("");
          $("#txtNumInt").val("");
          $("#txtColonia").val("");
          $("#txtMunicipio").val("");
          $("#txtLocalidad").val("");
          $("#txtReferencia").val("");
          cargarCMBEstados("241", "cmbEstado");
          $("#cmbPais").val("241");
          $("#txtCP").val("");
          $("#txtEdicion").val("0");
          $("#txtRazonSocialHis").val("");
          $("#txtRFCHis").val("");
          $("#txtPKRazon").val("0");
          Lobibox.notify("success", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../../../img/timdesk/checkmark.svg",
            msg: "¡Información fiscal registrada correctamente!",
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
}

function anadirRazonSocialEdit(id) {
  if ($("#info-fiscal-edit")[0].checkValidity()) {
    editRZ(id);
  } else {
    if (!$("#txtRazonSocialEdit").val()) {
      $("#invalid-razonSocEdit").css("display", "block");
      $("#txtRazonSocialEdit").addClass("is-invalid");
    }
    if (!$("#txtRFCEdit").val()) {
      $("#invalid-rfcEdit").css("display", "block");
      $("#txtRFCEdit").addClass("is-invalid");
    }
    if (!$("#cmbPaisEdit").val()) {
      $("#invalid-paisEdit").css("display", "block");
      $("#cmbPaisEdit").addClass("is-invalid");
    }
    if (!$("#cmbEstadoEdit").val()) {
      $("#invalid-estadoEdit").css("display", "block");
      $("#cmbEstadoEdit").addClass("is-invalid");
    }
    if (!$("#txtCPEdit").val()) {
      $("#invalid-cpEdit").css("display", "block");
      $("#txtCPEdit").addClass("is-invalid");
    }
  }
}

function editRZ(id) {
  var badRazonSocEdit =
    $("#invalid-razonSocEdit").css("display") === "block" ? false : true;
  var badRFCEdit =
    $("#invalid-rfcEdit").css("display") === "block" ? false : true;
  var badCPEdit =
    $("#invalid-cpEdit").css("display") === "block" ? false : true;
  var badPaisEdit =
    $("#invalid-paisEdit").css("display") === "block" ? false : true;
  var badEstadoEdit =
    $("#invalid-estadoEdit").css("display") === "block" ? false : true;

  if (
    badRazonSocEdit &&
    badRFCEdit &&
    badCPEdit &&
    badPaisEdit &&
    badEstadoEdit
  ) {
    var razonSocial = $("#txtRazonSocialEdit").val();
    var rfc = $("#txtRFCEdit").val();
    var calle = $("#txtCalleEdit").val();
    var numExt = $("#txtNumExtEdit").val();
    var numInt;
    if ($("#txtNumIntEdit").val() != "") {
      numInt = $("#txtNumIntEdit").val();
    } else {
      numInt = "S/N";
    }
    var colonia = $("#txtColoniaEdit").val();
    var municipio = $("#txtMunicipioEdit").val();
    var pais = $("#cmbPaisEdit").val();
    var estado = $("#cmbEstadoEdit").val();
    var cp = $("#txtCPEdit").val();
    var pkRazon = $("#txtPKRazon").val();
    var localidad = $("#txtLocalidadEdit").val();
    console.log(localidad);
    if(!$("#txtLocalidadEdit").val()){
      var localidad = ''
    }
    var referencia = $("#txtReferenciaEdit").val();
    console.log(referencia);
    if(!$("#txtReferenciaEdit").val()){
      var referencia = ''
    }

    $.ajax({
      url: "../../php/funciones.php",
      data: {
        clase: "save_data",
        funcion: "save_razonSocial_Proveedor",
        datos: razonSocial,
        datos2: rfc,
        datos4: calle,
        datos5: numExt,
        datos6: numInt,
        datos7: colonia,
        datos8: municipio,
        datos9: pais,
        datos10: estado,
        datos11: cp,
        datos12: id,
        datos13: pkRazon,
        datos14: localidad,
        datos15: referencia
      },
      dataType: "json",
      success: function (respuesta) {
        console.log("respuesta añadir razon social:", respuesta);

        if (respuesta[0].status) {
          $("#tblListadoDatosFiscalesProveedor").DataTable().ajax.reload();
          $("#txtRazonSocialEdit").val("");
          $("#txtRFCEdit").val("");
          $("#txtCalleEdit").val("");
          $("#txtNumExtEdit").val("");
          $("#txtNumIntEdit").val("");
          $("#txtColoniaEdit").val("");
          $("#txtMunicipioEdit").val("");
          $("#txtLocalidadEdit").val("");
          $("#txtReferenciaEdit").val("");
          cargarCMBEstadosEditModal("241", "cmbEstadoEdit");
          $("#cmbPaisEdit").val("241");
          $("#txtCPEdit").val("");
          $("#txtEdicion").val("0");
          $("#txtRazonSocialHis").val("");
          $("#txtRFCHis").val("");
          $("#txtPKRazon").val("0");
          $("#editar_InfoFiscal").modal("hide");
          Lobibox.notify("success", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../../../img/timdesk/checkmark.svg",
            msg: "¡Información fiscal registrada correctamente!",
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
}

$("#editar_InfoFiscal").on("hidden.bs.modal", function (event) {
  $("#invalid-razonSocEdit").text("display", "block");
  $("#invalid-razonSocEdit").css("display", "none");
  $("#txtRazonSocialEdit").removeClass("is-invalid");

  $("#invalid-rfcEdit").text("display", "block");
  $("#invalid-rfcEdit").css("display", "none");
  $("#txtRFCEdit").removeClass("is-invalid");

  $("#invalid-cpEdit").text("display", "block");
  $("#invalid-cpEdit").css("display", "none");
  $("#txtCPEdit").removeClass("is-invalid");
});

/* Eliminar el impuesto */
function obtenerIdRazonSocialProveedorEliminar(pkRazonSocialProveedor) {
  console.log("ID de la razon social : " + pkRazonSocialProveedor);
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "delete_data",
      funcion: "delete_razonSocial_Proveedor",
      datos: pkRazonSocialProveedor,
    },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta eliminar razon social:", respuesta);

      if (respuesta[0].status) {
        $("#tblListadoDatosFiscalesProveedor").DataTable().ajax.reload();
        Lobibox.notify("success", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/checkmark.svg",
          msg: "¡Se eliminó la razón social con éxito!",
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
}

/* Eliminar el impuesto */
function obtenerIdRazonSocialProveedorEditar(pkRazonSocialProveedor) {
  console.log("ID de la razon social : " + pkRazonSocialProveedor);
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_datos_fiscal_proveedor",
      datos: pkRazonSocialProveedor,
    },
    dataType: "json",
    success: function (respuesta) {
      console.log(
        "respuesta datos de la razón social del proveedor",
        respuesta
      );

      cargarCMBPaisesEditModalEdit("", "cmbPaisEdit", respuesta[0].Pais);
      cargarCMBEstadosEditModalEdit(
        respuesta[0].Pais,
        "cmbEstadoEdit",
        respuesta[0].Estado
      );

      $("#txtRazonSocialEdit").val(respuesta[0].Razon_Social);
      $("#txtNombreD").val(respuesta[0].Razon_Social);
      $("#txtRFCEdit").val(respuesta[0].RFCs);
      $("#txtCalleEdit").val(respuesta[0].Calle);
      $("#txtNumIntEdit").val(respuesta[0].Numero_Interior);
      $("#txtNumExtEdit").val(respuesta[0].Numero_exterior);
      $("#txtColoniaEdit").val(respuesta[0].Colonia);
      $("#txtMunicipioEdit").val(respuesta[0].Municipio);
      $("#txtLocalidadEdit").val(respuesta[0].Localidad);
      $("#txtReferenciaEdit").val(respuesta[0].Referencia);

      $("#txtCPEdit").val(respuesta[0].CPs);

      $("#txtEdicion").val("1");
      $("#txtRazonSocialHis").val(respuesta[0].Razon_Social);
      $("#txtRFCHis").val(respuesta[0].RFCs);
      $("#txtPKRazon").val(respuesta[0].PKDomicilioFiscalProveedor);
      $("#txtPkRazonSocialProveedor").val(pkRazonSocialProveedor);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

//Validar que se hayan completado todos los campos antes de pasar a la siguiente pestaña
function guardarDatosFiscales(id) {
  SeguirContacto(id);
}

function SeguirContacto(id) {
  validate_Permissions(12, 3, id);

  validarEmpresaProveedor(id);
  resetTabs("#CargarDatosEdicionContacto");

  var html =
    `<div class="card shadow mb-4">
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-12">
                      <form id="formDatosContacto"> 
                        <input type='hidden' value='` +
    id +
    `' name="txtPKCliente" id="txtPKCliente">
                        <div class="form-group">
                          <div class="row">
                            <div class="col-lg-4">
                              <label for="usr">Nombre(s) del contacto:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control" type="text" name="txtNombreContacto" id="txtNombreContacto" autofocus="" required="" maxlength="50" placeholder="Ej. José María" onkeyup="validEmptyInput(this)">
                                  <div class="invalid-feedback" id="invalid-nombreCont">El cliente debe tener un nombre.</div>
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <label for="usr">Apellido(s) del contacto:</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input type="text" maxlength="50" class="form-control" name="txtApellidoContacto" id="txtApellidoContacto" placeholder="Ej. López Pérez" onkeypress="return soloLetras(event)">
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <label for="usr">Puesto:</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input type="text" class="form-control" name="txtPuesto" id="txtPuesto" maxlength="50" placeholder="Ej. Gerente de ventas">
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="row">
                            <div class="col-lg-4">
                              <label for="usr">Teléfono fijo:</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control numeric-only" type="text" name="txtTelefono" id="txtTelefono" minlength="7" maxlength="10" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" placeholder="Ej. 33 3333 3333">
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <label for="usr">Celular:</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control numeric-only" type="text" name="txtCelular" id="txtCelular"  minlength="10" maxlength="10" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" placeholder="Ej. 33 3333 3333">
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <label for="usr">E-mail:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control" type="email" name="txtEmail" id="txtEmail" required="" maxlength="100" placeholder="Ej. ejemplo@dominio.com"
                                  onkeyup="validarCorreoContacto(this.value)">
                                  <div class="invalid-feedback" id="invalid-emailCont">El contacto debe tener un email.</div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <label for="">* Campos requeridos</label>
                        <div class="form-group">
                          <div class="row">
                            <div class="col-12 text-right" id="btnAnadirContacto2">
                              
                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <!-- DataTales Example -->
                          <div class="card mb-4 internal-table">
                            <div class="card-body">
                              <div class="table-responsive">
                                <table class="table" id="tblListadoDatosContactoProveedor" width="100%" cellspacing="0">
                                  <thead>
                                    <tr>
                                      <th>Id</th>
                                      <th>Nombre(s)</th>
                                      <th>Apellido(s)</th>
                                      <th>Puesto</th>
                                      <th>Teléfono fijo</th>
                                      <th>Celular</th>
                                      <th>E-mail</th>
                                      <th></th>
                                    </tr>
                                  </thead>
                                </table>
                              </div>
                            </div>
                          </div>
                        </div>
                        <input type="hidden" value="0" id="txtEdicion">
                        <input type="hidden" value="0" id="txtPKContacto">
                      </form>
                    </div>
                  </div>
                </div>
              </div>`;

  $("#datos").html(html);

  resetValidations();
}

function validarCorreoContacto(value) {
  console.log(value);
  var reg =
    /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

  var regOficial =
    /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/;

  //Se muestra un texto a modo de ejemplo, luego va a ser un icono
  if (reg.test(value) && regOficial.test(value)) {
    $("#invalid-emailCont").text("El contacto debe tener un email.");
    $("#invalid-emailCont").css("display", "none");
    $("#txtEmail").removeClass("is-invalid");
  } else if (reg.test(value)) {
    $("#invalid-emailCont").text("El contacto debe tener un email.");
    $("#invalid-emailCont").css("display", "none");
    $("#txtEmail").removeClass("is-invalid");
  } else {
    $("#invalid-emailCont").text("El email debe ser valido");
    $("#invalid-emailCont").css("display", "block");
    $("#txtEmail").addClass("is-invalid");
  }
}

function validarCorreoContactoModalEdit(value) {
  console.log(value);
  var reg =
    /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

  var regOficial =
    /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/;

  //Se muestra un texto a modo de ejemplo, luego va a ser un icono
  if (reg.test(value) && regOficial.test(value)) {
    $("#invalid-emailContEdit").text("El contacto debe tener un email.");
    $("#invalid-emailContEdit").css("display", "none");
    $("#txtEmailEdit").removeClass("is-invalid");
  } else if (reg.test(value)) {
    $("#invalid-emailContEdit").text("El contacto debe tener un email.");
    $("#invalid-emailContEdit").css("display", "none");
    $("#txtEmailEdit").removeClass("is-invalid");
  } else {
    $("#invalid-emailContEdit").text("El email debe ser valido");
    $("#invalid-emailContEdit").css("display", "block");
    $("#txtEmailEdit").addClass("is-invalid");
  }
}

function validarContacto(id) {
  console.log(id);
  var email = $("#txtEmail").val();
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "validar_contacto_proveedor",
      data4: email,
      data5: id,
    },
    dataType: "json",
    success: function (data) {
      console.log("respuesta estado validado: ", data);
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
          msg: "¡El contacto ya esta registrado en el sistema!",
          sound: '../../../../../sounds/sound4'
        });
      } else {
        anadirContacto(id);
      }
    },
  });
}

function validarContactoEdit(id) {
  console.log(idCliente);
  var email = $("#txtEmailEdit").val();
  var emailOld = $("#email-old").val();
  var idCliente = $("#txtPKCliente").val();
  if (email !== emailOld) {
    console.log("Son direfentes");
    $.ajax({
      url: "../../php/funciones.php",
      data: {
        clase: "get_data",
        funcion: "validar_contacto_proveedor",
        data4: email,
        data5: idCliente,
      },
      dataType: "json",
      success: function (data) {
        console.log("respuesta estado validado: ", data);
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
            msg: "¡El contacto ya esta registrado en el sistema!",
            sound: '../../../../../sounds/sound4'
          });
        } else {
          editarContacto(id);
        }
      },
    });
  } else {
    editarContacto(id);
  }
}

//Validar que se hayan completado todos los campos antes de pasar a la siguiente pestaña
function anadirContacto(id) {
  if ($("#formDatosContacto")[0].checkValidity()) {
    agregarContacto(id);
  } else {
    if (!$("#txtNombreContacto").val()) {
      $("#invalid-nombreCont").css("display", "block");
      $("#txtNombreContacto").addClass("is-invalid");
    }
    if (!$("#txtEmail").val()) {
      $("#invalid-emailCont").css("display", "block");
      $("#txtEmail").addClass("is-invalid");
    }
  }
}

function agregarContacto(id) {
  var badNombreCont =
    $("#invalid-nombreCont").css("display") === "block" ? false : true;
  var badEmailCont =
    $("#invalid-emailCont").css("display") === "block" ? false : true;
  if (badNombreCont && badEmailCont) {
    var nombreContacto = $("#txtNombreContacto").val();
    var apellidoContacto = $("#txtApellidoContacto").val();
    var puesto = $("#txtPuesto").val();
    var telefonoFijo = $("#txtTelefono").val();
    var celular = $("#txtCelular").val();
    var email = $("#txtEmail").val();
    var pkContacto = 0;

    $.ajax({
      url: "../../php/funciones.php",
      data: {
        clase: "save_data",
        funcion: "save_contactoProveedor",
        datos: nombreContacto,
        datos2: apellidoContacto,
        datos3: puesto,
        datos4: telefonoFijo,
        datos5: celular,
        datos6: email,
        datos7: id,
        datos8: pkContacto,
      },
      dataType: "json",
      success: function (respuesta) {
        console.log("respuesta agregar datos contacto del cliente:", respuesta);

        if (respuesta[0].status) {
          $("#tblListadoDatosContactoProveedor").DataTable().ajax.reload();
          $("#txtNombreContacto").val("");
          $("#txtApellidoContacto").val("");
          $("#txtPuesto").val("");
          $("#txtTelefono").val("");
          $("#txtCelular").val("");
          $("#txtEmail").val("");
          $("#txtEdicion").val("0");
          $("#txtPKContacto").val("0");

          Lobibox.notify("success", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../../../img/timdesk/checkmark.svg",
            msg: "¡ Contacto registrado!",
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
}

function editarContacto(id) {
  if ($("#form-contacto-edit")[0].checkValidity()) {
    var badNombreCont =
      $("#invalid-nombreContEdit").css("display") === "block" ? false : true;
    var badEmailCont =
      $("#invalid-emailContEdit").css("display") === "block" ? false : true;
    if (badNombreCont && badEmailCont) {
      var nombreContacto = $("#txtNombreContactoEdit").val();
      var apellidoContacto = $("#txtApellidoContactoEdit").val();
      var puesto = $("#txtPuestoEdit").val();
      var telefonoFijo = $("#txtTelefonoEdit").val();
      var celular = $("#txtCelularEdit").val();
      var email = $("#txtEmailEdit").val();
      var pkContacto = $("#txtPKContacto").val();

      $.ajax({
        url: "../../php/funciones.php",
        data: {
          clase: "save_data",
          funcion: "save_contactoProveedor",
          datos: nombreContacto,
          datos2: apellidoContacto,
          datos3: puesto,
          datos4: telefonoFijo,
          datos5: celular,
          datos6: email,
          datos7: id,
          datos8: pkContacto,
        },
        dataType: "json",
        success: function (respuesta) {
          console.log(
            "respuesta agregar datos contacto del cliente:",
            respuesta
          );

          if (respuesta[0].status) {
            $("#tblListadoDatosContactoProveedor").DataTable().ajax.reload();
            $("#txtNombreContactoEdit").val("");
            $("#txtApellidoContactoEdit").val("");
            $("#txtPuestoEdit").val("");
            $("#txtTelefonoEdit").val("");
            $("#txtCelularEdit").val("");
            $("#txtEmailEdit").val("");
            $("#txtEdicion").val("0");
            $("#txtPKContacto").val("0");
            Lobibox.notify("success", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../../img/timdesk/checkmark.svg",
              msg: "¡ Contacto actualizo!",
              sound: '../../../../../sounds/sound4'
            });
            $("#editar_Contacto").modal("hide");
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
  }
}

function obtenerIdContactoProveedorEliminar(id) {
  console.log("ID del contacto : " + id);
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "delete_data",
      funcion: "delete_contacto_Proveedor",
      datos: id,
    },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta eliminar contacto:", respuesta);

      if (respuesta[0].status) {
        $("#tblListadoDatosContactoProveedor").DataTable().ajax.reload();
        Lobibox.notify("success", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/checkmark.svg",
          msg: "¡Se eliminó el contacto con éxito!",
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
}

function obtenerIdContactoProveedorEditar(id) {
  console.log("ID de la proveedor : " + id);
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_datos_contacto_proveedor",
      datos: id,
    },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta datos del contacto del proveedor", respuesta);

      $("#txtNombreContactoEdit").val(respuesta[0].Nombres);
      $("#txtApellidoContactoEdit").val(respuesta[0].Apellidos);
      $("#txtPuestoEdit").val(respuesta[0].Puesto);
      $("#txtTelefonoEdit").val(respuesta[0].Telefono);
      $("#txtCelularEdit").val(respuesta[0].Celular);
      $("#txtEmailEdit").val(respuesta[0].Email);
      $("#email-old").val(respuesta[0].Email);

      $("#txtNombreDContacto").val(
        respuesta[0].Nombres + " " + respuesta[0].Apellidos
      );

      $("#txtEdicion").val("1");
      $("#txtPKContacto").val(respuesta[0].PKContactoProveedor);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function guardarDatosContacto(id) {
  SeguirCuentasBancarias(id);
}

function SeguirCuentasBancarias(id) {
  validate_Permissions(12, 4, id);

  validarEmpresaProveedor(id);
  resetTabs("#CargarDatosEdicionCuentasBancarias");

  cargarCMBBanco("", "cmbBanco");
  cargarCMBCostoUniVentaEsp("", "cmbCostoUniVentaEspecial");

  cargarCMBBancoEditModal("", "cmbBancoEdit");
  cargarCMBCostoUniVentaEspEditModal("", "cmbCostoUniVentaEspecialEdit");

  var html =
    `<div class="card shadow mb-4">
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-12">
                      <form id="formDatosProveedor"> 
                        <input type='hidden' value='` +
    id +
    `' name="txtPKProductoInventario" id="txtPKProductoInventario">
                        <div class="form-group">
                          <div class="row">
                            <div class="col-lg-3">
                              <label for="usr">Banco:*</label>
                              
                              <div class="col-lg-12 input-group">
                                <select name="cmbBanco" id="cmbBanco" required="" onchange="validEmptyInput(this, 'invalid-banco')">
                                </select>
                                <div class="invalid-feedback" id="invalid-banco">La cuenta debe tener un banco.</div>
                              </div>  
                            </div>
                            <div class="col-lg-3">
                              <label for="usr">No. de cuenta:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control numeric-only" type="text" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" minlength="10" maxlength="20" name="txtNoCuenta" id="txtNoCuenta" autofocus="" required="" placeholder="Ej. 0000000000" onkeyup="validarNoCuenta()">
                                  <div class="invalid-feedback" id="invalid-noCuenta">La cuenta debe tener un número.</div>
                                </div>
                              </div>  
                            </div>
                            <div class="col-lg-3">
                              <label for="usr">CLABE:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control numeric-only" type="text" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" name="txtCLABE" id="txtCLABE" minlength="18" maxlength="18" autofocus="" required="" placeholder="Ej. 000 000 0000000000 0" onkeyup="validarCLABE()">
                                  <div class="invalid-feedback" id="invalid-clabe">La cuenta debe tener una clabe.</div>
                                </div>
                              </div>      
                            </div>
                            <div class="col-lg-3">
                              <label for="usr">Moneda:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <select name="cmbCostoUniVentaEspecial" id="cmbCostoUniVentaEspecial" required="">
                                  </select>
                                  <div class="invalid-feedback" id="invalid-moneda">La cuenta debe tener un tipo de moneda.</div>
                                </div>
                              </div> 
                            </div>
                          </div>
                        </div>
                        <label for="">* Campos requeridos</label>
                        <div class="form-group">
                          <div class="row">
                            <div class="col-12 text-right" id="btnAnadirContacto2">
                                
                            </div>
                          </div>
                        </div>
                        <br>
                        <div class="form-group">
                          <!-- DataTales Example -->
                          <div class="card mb-4 internal-table">
                            <div class="card-body">
                              <div class="table-responsive">
                                <table class="table" id="tblListadoDatosBancoProveedor" width="100%" cellspacing="0">
                                  <thead>
                                    <tr>
                                      <th>Id</th>
                                      <th>Banco</th>
                                      <th>No. de Cuenta</th>
                                      <th>CLABE</th>
                                      <th>Moneda</th>
                                      <th></th>
                                    </tr>
                                  </thead>
                                </table>
                              </div>
                            </div>
                          </div>
                        </div>
                        <input type="hidden" value="0" id="txtEdicion">
                        <input type="hidden" value="0" id="txtPKCuentaBancaria">
                      </form>
                    </div>
                  </div>
                </div>
              </div>`;

  $("#datos").html(html);

  resetValidations();
}

/*----------------------Funciones de carga de datos de los combos-------------------------------*/

function cargarCMBBanco(data, input) {
  var html = "";
  var selected;
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_banco" },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta bancos de cliente: ", respuesta);

      html += '<option data-placeholder="true"></option>';

      $.each(respuesta, function (i) {
        if (data === respuesta[i].PKBanco) {
          selected = "selected";
        } else {
          selected = "";
        }

        html +=
          '<option value="' +
          respuesta[i].PKBanco +
          '" ' +
          selected +
          ">" +
          respuesta[i].Banco +
          "</option>";
      });

      CargarSlimBanco();
      $("#" + input + "").html(html);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function cargarCMBBancoEditModal(data, input) {
  var html = "";
  var selected;
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_banco" },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta bancos de cliente: ", respuesta);

      html += '<option data-placeholder="true"></option>';

      $.each(respuesta, function (i) {
        if (data === respuesta[i].PKBanco) {
          selected = "selected";
        } else {
          selected = "";
        }

        html +=
          '<option value="' +
          respuesta[i].PKBanco +
          '" ' +
          selected +
          ">" +
          respuesta[i].Banco +
          "</option>";
      });

      CargarSlimBancoEditModal();
      $("#" + input + "").html(html);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function cargarCMBBancoEdit(data, input, value) {
  var html = "";
  var selected;
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_banco" },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta bancos de cliente: ", respuesta);

      hhtml += '<option data-placeholder="true"></option>';

      $.each(respuesta, function (i) {
        if (data === respuesta[i].PKBanco) {
          selected = "selected";
        } else {
          selected = "";
        }

        html +=
          '<option value="' +
          respuesta[i].PKBanco +
          '" ' +
          selected +
          ">" +
          respuesta[i].Banco +
          "</option>";
      });

      $("#" + input + "").html(html);

      $("#cmbBanco").val(value);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function cargarCMBBancoEditModalEdit(data, input, value) {
  var html = "";
  var selected;
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_banco" },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta bancos de cliente: ", respuesta);

      html += '<option data-placeholder="true"></option>';

      $.each(respuesta, function (i) {
        if (data === respuesta[i].PKBanco) {
          selected = "selected";
        } else {
          selected = "";
        }

        html +=
          '<option value="' +
          respuesta[i].PKBanco +
          '" ' +
          selected +
          ">" +
          respuesta[i].Banco +
          "</option>";
      });

      $("#" + input + "").html(html);

      $("#cmbBancoEdit").val(value);
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
    data: { clase: "get_data", funcion: "get_cmb_costouni_ventaEsp" },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta tipo de moneda esp: ", respuesta);
      html += '<option data-placeholder="true"></option>';

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
      $("#" + input + "").html(html);
      $("#cmbCostoUniVentaEspecial").val("100");
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function cargarCMBCostoUniVentaEspEditModal(data, input) {
  var html = "";
  var selected;
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_costouni_ventaEsp" },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta tipo de moneda esp: ", respuesta);
      html += '<option data-placeholder="true"></option>';

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

      CargarSlimCostoUniCompraEditModal();
      $("#" + input + "").html(html);
      $("#cmbCostoUniVentaEspecialEdit").val("100");
    },
    error: function (error) {
      console.log(error);
    },
  });
}

/*----------------------Plugin Slim para la búsqueda dentro del-------------------------------*/
function CargarSlimBanco() {
  new SlimSelect({
    select: "#cmbBanco",
    deselectLabel: '<span class="">✖</span>',
    placeholder: "Selecciona una opción",
  });
}

/* function CargarSlimBancoEditModal() {
  new SlimSelect({
    select: "#cmbBancoEdit",
    deselectLabel: '<span class="">✖</span>',
    placeholder: "Selecciona una opción",
  });
} */

function CargarSlimCostoUniCompra() {
  new SlimSelect({
    select: "#cmbCostoUniVentaEspecial",
    deselectLabel: '<span class="">✖</span>',
    placeholder: "Selecciona una opción",
  });
}

/* function CargarSlimCostoUniCompraEditModal() {
  new SlimSelect({
    select: "#cmbCostoUniVentaEspecialEdit",
    deselectLabel: '<span class="">✖</span>',
    placeholder: "Selecciona una opción",
  });
} */

function validarNoCuenta() {
  var noCuenta = $("#txtNoCuenta").val();

  console.log("Valor de validación:" + validaCCC(noCuenta));
  if (noCuenta != "") {
    if (validaCCC(noCuenta)) {
      console.log("¡Válida!");
      $.ajax({
        url: "../../php/funciones.php",
        data: {
          clase: "get_data",
          funcion: "validar_noCuenta",
          data: noCuenta,
        },
        dataType: "json",
        success: function (data) {
          console.log("respuesta estado validado: ", data);
          /* Validar si ya existe el identificador con ese nombre*/
          if (parseInt(data[0]["existe"]) == 1) {
            $("#invalid-noCuenta").text(
              "El número de cuenta ya esta registrado."
            );
            $("#invalid-noCuenta").css("display", "block");
            $("#txtNoCuenta").addClass("is-invalid");
          } else {
            $("#invalid-noCuenta").text("La cuenta debe tener un número.");
            $("#invalid-noCuenta").css("display", "none");
            $("#txtNoCuenta").removeClass("is-invalid");
          }
        },
      });
    } else {
      $("#invalid-noCuenta").text("El número de cuenta no es valido.");
      $("#invalid-noCuenta").css("display", "block");
      $("#txtNoCuenta").addClass("is-invalid");
      console.log("¡No válida!");
    }
  } else {
    $("#invalid-noCuenta").text("La cuenta debe tener un número.");
    $("#invalid-noCuenta").css("display", "block");
    $("#txtNoCuenta").addClass("is-invalid");
  }
}

function validarNoCuentaEditModal() {
  var noCuenta = $("#txtNoCuentaEdit").val();
  var noCuentaOld = $("#cuenta-old").val();

  if (noCuenta !== noCuentaOld) {
    if (validaCCC(noCuenta)) {
      console.log("¡Válida!");
      $.ajax({
        url: "../../php/funciones.php",
        data: {
          clase: "get_data",
          funcion: "validar_noCuenta",
          data: noCuenta,
        },
        dataType: "json",
        success: function (data) {
          console.log("respuesta estado validado: ", data);
          /* Validar si ya existe el identificador con ese nombre*/
          if (parseInt(data[0]["existe"]) == 1) {
            $("#invalid-noCuentaEdit").text(
              "El número de cuenta ya esta registrado."
            );
            $("#invalid-noCuentaEdit").css("display", "block");
            $("#txtNoCuentaEdit").addClass("is-invalid");
          } else {
            $("#invalid-noCuentaEdit").text("La cuenta debe tener un número.");
            $("#invalid-noCuentaEdit").css("display", "none");
            $("#txtNoCuentaEdit").removeClass("is-invalid");
          }
        },
      });
    } else {
      $("#invalid-noCuentaEdit").text("El número de cuenta no es valido.");
      $("#invalid-noCuentaEdit").css("display", "block");
      $("#txtNoCuentaEdit").addClass("is-invalid");
      console.log("¡No válida!");
    }
  }
}

function validaCCC(val) {
  valu = val.trim();
  if (parseInt(valu.length) > 9) {
    return true;
  } else {
    return false;
  }
}

function validarCLABE() {
  var clabe = $("#txtCLABE").val();
  if (clabe != "") {
    if (validaBBB(clabe)) {
      $.ajax({
        url: "../../php/funciones.php",
        data: { clase: "get_data", funcion: "validar_CLABE", data: clabe },
        dataType: "json",
        success: function (data) {
          console.log("respuesta estado validado: ", data);
          /* Validar si ya existe el identificador con ese nombre*/
          if (parseInt(data[0]["existe"]) == 1) {
            $("#invalid-clabe").text("La clabe ya esta registrado.");
            $("#invalid-clabe").css("display", "block");
            $("#txtCLABE").addClass("is-invalid");
          } else {
            $("#invalid-clabe").text("La cuenta debe tener una clabe.");
            $("#invalid-clabe").css("display", "none");
            $("#txtCLABE").removeClass("is-invalid");
          }
        },
      });
    } else {
      $("#invalid-clabe").text("La clabe debe ser valida.");
      $("#invalid-clabe").css("display", "block");
      $("#txtCLABE").addClass("is-invalid");
    }
  } else {
    $("#invalid-clabe").text("La cuenta debe tener una clabe.");
    $("#invalid-clabe").css("display", "none");
    $("#txtCLABE").removeClass("is-invalid");
  }
}

function validarCLABEEditModal() {
  var clabe = $("#txtCLABEEdit").val();
  var clabeOld = $("#clave-old").val();

  if (clabe !== clabeOld) {
    if (validaBBB(clabe)) {
      $.ajax({
        url: "../../php/funciones.php",
        data: { clase: "get_data", funcion: "validar_CLABE", data: clabe },
        dataType: "json",
        success: function (data) {
          console.log("respuesta estado validado: ", data);
          /* Validar si ya existe el identificador con ese nombre*/
          if (parseInt(data[0]["existe"]) == 1) {
            $("#invalid-clabeEdit").text("La clabe ya esta registrado.");
            $("#invalid-clabeEdit").css("display", "block");
            $("#txtCLABEEdit").addClass("is-invalid");
          } else {
            $("#invalid-clabeEdit").text("La cuenta debe tener una clabe.");
            $("#invalid-clabeEdit").css("display", "none");
            $("#txtCLABEEdit").removeClass("is-invalid");
          }
        },
      });
    } else {
      $("#invalid-clabeEdit").text("La clabe debe ser valida.");
      $("#invalid-clabeEdit").css("display", "block");
      $("#txtCLABEEdit").addClass("is-invalid");
    }
  }
}

function validaBBB(val) {
  valu = val.trim();
  if (parseInt(valu.length) == 18) {
    return true;
  } else {
    return false;
  }
}

function validarBanco(id) {
  var pkBanco = 0;
  if ($("#cmbBanco").val() == "" || $("#cmbBanco").val() == null) {
    pkBanco = 0;
  } else {
    pkBanco = $("#cmbBanco").val();
  }

  var noCuenta = $("#txtNoCuenta").val();
  var clabe = $("#txtCLABE").val();
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "validar_datosBanarios_proveedor",
      data: pkBanco,
      data2: noCuenta,
      data3: clabe,
      data4: id,
    },
    dataType: "json",
    success: function (data) {
      console.log("respuesta estado validado: ", data);
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
          msg: "¡La cuenta ya esta en el sistema!",
          sound: '../../../../../sounds/sound4'
        });
      } else {
        anadirBanco(id);
      }
    },
  });
}

//Validar que se hayan completado todos los campos antes de pasar a la siguiente pestaña
function anadirBanco(id) {
  if ($("#formDatosProveedor")[0].checkValidity()) {
    agregarBanco(id);
  } else {
    if ($("#cmbBanco").val() < 1) {
      $("#invalid-banco").css("display", "block");
      $("#cmbBanco").addClass("is-invalid");
    }
    if (!$("#txtNoCuenta").val()) {
      $("#invalid-noCuenta").css("display", "block");
      $("#txtNoCuenta").addClass("is-invalid");
    }
    if (!$("#txtCLABE").val()) {
      $("#invalid-clabe").css("display", "block");
      $("#txtCLABE").addClass("is-invalid");
    }
    if (!$("#cmbCostoUniVentaEspecial").val()) {
      $("#invalid-clabe").css("display", "block");
      $("#cmbCostoUniVentaEspecial").addClass("is-invalid");
    }
  }
}

function agregarBanco(id) {
  var badBanco = $("#invalid-banco").css("display") === "block" ? false : true;
  var badCuenta =
    $("#invalid-noCuenta").css("display") === "block" ? false : true;
  var badClabe = $("#invalid-clabe").css("display") === "block" ? false : true;
  var badMoneda =
    $("#invalid-moneda").css("display") === "block" ? false : true;

  if (badBanco && badCuenta && badClabe && badMoneda) {
    var pkBanco = $("#cmbBanco").val();
    var noCuenta = $("#txtNoCuenta").val();
    var clabe = $("#txtCLABE").val();
    var pkCuentaBancaria = 0;
    var moneda = $("#cmbCostoUniVentaEspecial").val();

    $.ajax({
      url: "../../php/funciones.php",
      data: {
        clase: "save_data",
        funcion: "save_bancoProveedor",
        datos: pkBanco,
        datos2: noCuenta,
        datos3: clabe,
        datos4: id,
        datos5: pkCuentaBancaria,
        datos6: moneda,
      },
      dataType: "json",
      success: function (respuesta) {
        console.log("respuesta agregar datos banco del proveedor:", respuesta);

        if (respuesta[0].status) {
          $("#tblListadoDatosBancoProveedor").DataTable().ajax.reload();
          cargarCMBBancoEdit("", "cmbBanco", "0");
          $("#txtNoCuenta").val("");
          $("#txtCLABE").val("");

          $("#notaFBanco").css("display", "none");
          $("#notaFNoCuenta").css("display", "none");
          $("#notaFCLABE").css("display", "none");

          $("#txtEdicion").val("0");
          $("#txtPKCuentaBancaria").val("0");

          cargarCMBCostoUniVentaEspEC("", "cmbCostoUniVentaEspecial", 100);
          Lobibox.notify("success", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../../../img/timdesk/checkmark.svg",
            msg: "¡Información bancaria registrada correctamente!",
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
}

function validarBancoEdit(id) {
  var pkBanco = 0;
  if ($("#cmbBancoEdit").val() == "" || $("#cmbBancoEdit").val() == null) {
    pkBanco = 0;
  } else {
    pkBanco = $("#cmbBancoEdit").val();
  }

  var noCuenta = $("#txtNoCuentaEdit").val();
  var noCuentaOld = $("#cuenta-old").val();
  var clabe = $("#txtCLABEEdit").val();
  var clabeOld = $("#clave-old").val();

  if (noCuenta != noCuentaOld || clabe != clabeOld) {
    $.ajax({
      url: "../../php/funciones.php",
      data: {
        clase: "get_data",
        funcion: "validar_datosBanarios_proveedor",
        data: pkBanco,
        data2: noCuenta,
        data3: clabe,
        data4: id,
      },
      dataType: "json",
      success: function (data) {
        console.log("respuesta estado validado: ", data);
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
            msg: "¡La cuenta ya esta en el sistema!",
            sound: '../../../../../sounds/sound4'
          });
        } else {
          editarBanco(id);
        }
      },
    });
  } else {
    editarBanco(id);
  }
}

function editarBanco(id) {
  if ($("#form-cuentabanc-edit")[0].checkValidity()) {
    var badBanco =
      $("#invalid-bancoEdit").css("display") === "block" ? false : true;
    var badCuenta =
      $("#invalid-noCuentaEdit").css("display") === "block" ? false : true;
    var badClabe =
      $("#invalid-clabeEdit").css("display") === "block" ? false : true;
    var badMoneda =
      $("#invalid-monedaEdit").css("display") === "block" ? false : true;
    if (badBanco && badCuenta && badClabe && badMoneda) {
      var pkBanco = $("#cmbBancoEdit").val();
      var noCuenta = $("#txtNoCuentaEdit").val();
      var clabe = $("#txtCLABEEdit").val();
      var pkCuentaBancaria = $("#txtPKCuentaBancaria").val();
      var moneda = $("#cmbCostoUniVentaEspecialEdit").val();

      $.ajax({
        url: "../../php/funciones.php",
        data: {
          clase: "save_data",
          funcion: "save_bancoProveedor",
          datos: pkBanco,
          datos2: noCuenta,
          datos3: clabe,
          datos4: id,
          datos5: pkCuentaBancaria,
          datos6: moneda,
        },
        dataType: "json",
        success: function (respuesta) {
          console.log(
            "respuesta agregar datos banco del proveedor:",
            respuesta
          );

          if (respuesta[0].status) {
            $("#tblListadoDatosBancoProveedor").DataTable().ajax.reload();
            cargarCMBBancoEditModalEdit("", "cmbBancoEdit", "0");
            $("#txtNoCuentaEdit").val("");
            $("#txtCLABEEdit").val("");

            $("#notaFBanco").css("display", "none");
            $("#notaFNoCuenta").css("display", "none");
            $("#notaFCLABE").css("display", "none");

            $("#txtEdicion").val("0");
            $("#txtPKCuentaBancaria").val("0");

            cargarCMBCostoUniVentaEspECEditModal(
              "",
              "cmbCostoUniVentaEspecialEdit",
              100
            );
            $("#editar_CuentaBancancaria").modal("hide");
            Lobibox.notify("success", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../../img/timdesk/checkmark.svg",
              msg: "¡Cuenta actualizada correctamente!",
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
  } else {
    if ($("#cmbBancoEdit").val() < 1) {
      $("#invalid-bancoEdit").css("display", "block");
      $("#cmbBancoEdit").addClass("is-invalid");
    }
    if (!$("#txtNoCuentaEdit").val()) {
      $("#invalid-noCuentaEdit").css("display", "block");
      $("#txtNoCuentaEdit").addClass("is-invalid");
    }
    if (!$("#txtCLABEEdit").val()) {
      $("#invalid-clabeEdit").css("display", "block");
      $("#txtCLABEEdit").addClass("is-invalid");
    }
    if (!$("#cmbCostoUniVentaEspecialEdit").val()) {
      $("#invalid-monedaEdit").css("display", "block");
      $("#cmbCostoUniVentaEspecialEdit").addClass("is-invalid");
    }
  }
}

function obtenerIdBancoProveedorEliminar(id) {
  console.log("ID de la cuenta bancaria : " + id);
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "delete_data",
      funcion: "delete_cuentaBancaria_Proveedor",
      datos: id,
    },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta eliminar cuenta bancaria:", respuesta);

      if (respuesta[0].status) {
        $("#tblListadoDatosBancoProveedor").DataTable().ajax.reload();
        Lobibox.notify("success", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/checkmark.svg",
          msg: "¡Se eliminó la cuenta bancaria con éxito!",
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
}

function obtenerIdBancoProveedorEditar(id) {
  console.log("ID del proveedor : " + id);
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_datos_cuentaBancaria_proveedor",
      datos: id,
    },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta datos del contacto del proveedor", respuesta);

      cargarCMBBancoEditModalEdit("", "cmbBancoEdit", respuesta[0].FKBanco);

      $("#cuenta-old").val(respuesta[0].NoCuenta);
      $("#txtNoCuentaEdit").val(respuesta[0].NoCuenta);
      $("#clave-old").val(respuesta[0].CLABEs);
      $("#txtCLABEEdit").val(respuesta[0].CLABEs);

      cargarCMBCostoUniVentaEspECEditModal(
        "",
        "cmbCostoUniVentaEspecialEdit",
        respuesta[0].FKMoneda
      );

      $("#txtNombreDCuenta").val(respuesta[0].NoCuenta);

      $("#txtEdicion").val("1");
      $("#txtPKCuentaBancaria").val(respuesta[0].PKCuentaBancariaProveedor);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function cargarCMBCostoUniVentaEspEC(data, input, value) {
  var html = "";
  var selected;
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_costouni_ventaEsp" },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta tipo de moneda esp: ", respuesta);
      html += '<option data-placeholder="true"></option>';

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

      $("#" + input + "").html(html);
      $("#cmbCostoUniVentaEspecial").val(value);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function cargarCMBCostoUniVentaEspECEditModal(data, input, value) {
  var html = "";
  var selected;
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_costouni_ventaEsp" },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta tipo de moneda esp: ", respuesta);
      html += '<option data-placeholder="true"></option>';

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

      $("#" + input + "").html(html);
      $("#cmbCostoUniVentaEspecialEdit").val(value);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

//Validar que se hayan completado todos los campos antes de pasar a la siguiente pestaña
function guardarDatosBancarios(id) {
  SeguirListadoProductos(id);
}

function SeguirListadoProductos(id) {
  validate_Permissions(12, 5, id);

  validarEmpresaProveedor(id);
  resetTabs("#CargarEdicionDireccionesEnvio");

  cargarCMBProveedor("1", "cmbProveedorProducto");
  //cargarCMBUnidadMProveedor('1','cmbUnidadMProveedor');
  cargarCMBMonedaPrecio("", "cmbMonedaPrecio");

  cargarCMBProveedorEditModal("1", "cmbProveedorProductoEdit");
  cargarCMBMonedaPrecioEditModal("", "cmbMonedaPrecioEdit");

  var html =
    `<div class="card shadow mb-4">
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-12">
                      <form id="formDatosProveedor"> 
                        <input type='hidden' value='` +
    id +
    `' name="txtPKProductoProveedor" id="txtPKProductoProveedor">
                        <div class="form-group">
                          <div class="row">
                            <div class="col-lg-6">
                              <label for="usr">Producto:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <select name="cmbProveedorProducto" id="cmbProveedorProducto" required="" onchange="cambioProveedor()">
                                  </select>
                                  <img  id="notaFProveedorProducto" name="notaFProveedorProducto" style="display: none;"
                                  src="../../../../img/timdesk/alerta.svg" width=30px
                                  title="Campo requerido" readonly>
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
                                  <input class="form-control" type="text" name="txtNombreProdProve" id="txtNombreProdProve" autofocus="" required="" maxlength="255" placeholder="Ej. Bata quirúgica desechable">
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
                                  <input type="text" class="form-control" name="txtClaveProdProve" id="txtClaveProdProve" required="" maxlength="50" placeholder="Ej. AA - 0001" onkeyup="escribirClaveProveedor()">
                                  <img  id="notaClaveProdProve" name="notaClaveProdProve" style="display: none;"
                                  src="../../../../img/timdesk/alerta.svg" width=30px
                                  title="La clave ya existe para este proveedor, favor de anexar otra" readonly>
                                  <img  id="notaFClaveProdProve" name="notaFClaveProdProve" style="display: none;"
                                  src="../../../../img/timdesk/alerta.svg" width=30px
                                  title="Campo requerido" readonly>
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <label for="usr">Precio*:</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control numericDecimal-only" type="text" name="txtPrecioProdProve" id="txtPrecioProdProve" required min="0" maxlength="13" placeholder="Ej. 30.00" onkeyup="validEmptyInput(this)">
                                  <span class="input-group-addon" style="width:100px">
                                    <select name="cmbMonedaPrecio" id="cmbMonedaPrecio" required="">
                                    </select> 
                                  </span>
                                  <div class="invalid-feedback" id="invalid-precioProdU">El producto debe tener un precio.</div>
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
                                  <input class="form-control numeric-only" type="text" name="txtCantMinProdProve" id="txtCantMinProdProve" autofocus="" required="" min="0" maxlength="12" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" placeholder="Ej. 1000">                   
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
                                  <input class="form-control numeric-only" type="text" name="txtDiasEntregProdProve" id="txtDiasEntregProdProve" autofocus="" required="" min="0" placeholder="Ej. 15" maxlength="3" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);">
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
                                  <input class="form-control" type="text" name="txtUnidadMedida" id="txtUnidadMedida" autofocus="" required="" maxlength="50" placeholder="Ej. Caja de 12 piezas">
                                  <img  id="notaFUnidadMProveedor" name="notaFUnidadMProveedor" style="display: none;"
                                  src="../../../../img/timdesk/alerta.svg" width=30px
                                  title="Campo requerido" readonly>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <label for="">* Campos requeridos</label>
                        <div class="form-group">
                          <div class="row">
                            <div class="col-12 d-flex justify-content-end" margin-top:25px;">
                              <span id="btnAnadirProveedor2">
                                
                              </span>
                              <span id="btnAgregarTipoProducto2">
                                
                              </span>                            
                            </div>
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
                                      <th>Producto</th>
                                      <th>Nombre del producto del proveedor</th>
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
                        <input type="hidden" id="txtPKDatosProductoProveedor" value="0">
                      </form>
                    </div>
                  </div>
                </div>
              </div>`;

  $("#datos").html(html);

  resetValidations();
}

/*----------------------Funciones de carga de datos de los combos-------------------------------*/
function cargarCMBProveedor(data, input) {
  var html = "";
  var selected;
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_producto_proveedor" },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta proveedor: ", respuesta);

      //html += '<option value="0">Seleccione un proveedor...</option>';

      $.each(respuesta, function (i) {
        if (data === respuesta[i].PKProducto) {
          selected = "selected";
        } else {
          selected = "";
        }

        if (respuesta[i].ClaveInterna == "") {
          html +=
            '<option value="' +
            respuesta[i].PKProducto +
            '" ' +
            selected +
            ">" +
            respuesta[i].Nombre +
            "</option>";
        } else {
          html +=
            '<option value="' +
            respuesta[i].PKProducto +
            '" ' +
            selected +
            ">" +
            respuesta[i].ClaveInterna +
            " - " +
            respuesta[i].Nombre +
            "</option>";
        }
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

function cargarCMBProveedorEditModal(data, input) {
  var html = "";
  var selected;
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_producto_proveedor" },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta proveedor: ", respuesta);
      html += '<option data-placeholder="true"></option>';

      //html += '<option value="0">Seleccione un proveedor...</option>';

      $.each(respuesta, function (i) {
        if (data === respuesta[i].PKProducto) {
          selected = "selected";
        } else {
          selected = "";
        }

        if (respuesta[i].ClaveInterna == "") {
          html +=
            '<option value="' +
            respuesta[i].PKProducto +
            '" ' +
            selected +
            ">" +
            respuesta[i].Nombre +
            "</option>";
        } else {
          html +=
            '<option value="' +
            respuesta[i].PKProducto +
            '" ' +
            selected +
            ">" +
            respuesta[i].ClaveInterna +
            " - " +
            respuesta[i].Nombre +
            "</option>";
        }
      });

      //html += '<option value="add" style="background-color: #15589B;  color:white; text-align:center; width:100%;">Configurar proveedores</option>';

      $("#" + input + "").html(html);

      CargarSlimProveedorEditModal();
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function cargarCMBProveedorEditModalEdit(data, input) {
  var html = "";
  var selected;
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_producto_proveedor" },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta proveedor: ", respuesta);
      html += '<option data-placeholder="true"></option>';

      //html += '<option value="0">Seleccione un proveedor...</option>';

      $.each(respuesta, function (i) {
        if (data === respuesta[i].PKProducto) {
          selected = "selected";
        } else {
          selected = "";
        }

        if (respuesta[i].ClaveInterna == "") {
          html +=
            '<option value="' +
            respuesta[i].PKProducto +
            '" ' +
            selected +
            ">" +
            respuesta[i].Nombre +
            "</option>";
        } else {
          html +=
            '<option value="' +
            respuesta[i].PKProducto +
            '" ' +
            selected +
            ">" +
            respuesta[i].ClaveInterna +
            " - " +
            respuesta[i].Nombre +
            "</option>";
        }
      });

      //html += '<option value="add" style="background-color: #15589B;  color:white; text-align:center; width:100%;">Configurar proveedores</option>';

      $("#" + input + "").html(html);
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
      html += '<option data-placeholder="true"></option>';

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
      html += '<option data-placeholder="true"></option>';

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

function cargarCMBMonedaPrecioEditModal(data, input) {
  var html = "";
  var selected;
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_costouni_compra" },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta tipo de moneda: ", respuesta);
      html += '<option data-placeholder="true"></option>';

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

      CargarSlimMonedaPrecioEditModal();
      $("#" + input + "").append(html);
      $("#cmbMonedaPrecioEdit").val("100");
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
    placeholder: "Selecciona una opción",
  });

  //CargarSlimUnidadMProveedor();
}

/* function CargarSlimProveedorEditModal() {
  new SlimSelect({
    select: "#cmbProveedorProductoEdit",
    deselectLabel: '<span class="">✖</span>',
    placeholder: "Selecciona una opción",
  });
} */

function CargarSlimUnidadMProveedor() {
  new SlimSelect({
    select: "#cmbUnidadMProveedor",
    deselectLabel: '<span class="">✖</span>',
    placeholder: "Selecciona una opción",
  });
}

function CargarSlimMonedaPrecio() {
  new SlimSelect({
    select: "#cmbMonedaPrecio",
    deselectLabel: '<span class="">✖</span>',
    placeholder: "Selecciona una opción",
  });
}

/* function CargarSlimMonedaPrecioEditModal() {
  new SlimSelect({
    select: "#cmbMonedaPrecioEdit",
    deselectLabel: '<span class="">✖</span>',
    placeholder: "Selecciona una opción",
  });
} */

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

function cambioProveedorEditModal() {
  escribirClaveProveedorEditModal();
}

/*----------------------------Botón añadir proveedor ---------------------------*/
/* VALIAR QUE NO SE REPITA EL PROVEEDOR POR PRODUCTO AGREGADO POR EL USUARIO EN AGREGAR */
function validarProveedor(id) {
  if (parseInt($("#txtEdicion").val()) == "0") {
    var valor = $("#cmbProveedorProducto").val();
    $.ajax({
      url: "../../php/funciones.php",
      data: {
        clase: "get_data",
        funcion: "validar_proveedorProducto",
        data: valor,
        data2: id,
      },
      dataType: "json",
      success: function (data) {
        console.log("respuesta proveedor validado: ", data);
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
            sound: '../../../../../sounds/sound4'
          });
        } else {
          anadirProveedor();
        }
      },
    });
  } else {
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
          console.log("respuesta proveedor validado: ", data);
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
              sound: '../../../../../sounds/sound4'
            });
            console.log("¡Ya existe!");
          } else {
            editarProveedor(id);

            console.log("¡No existe!");
          }
        },
      });
    } else {
      editarProveedor(id);
    }
  }
}

function escribirClaveProveedor() {
  var proveedor = $("#cmbProveedorProducto").val();
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
        $("#notaClaveProdProve").css("display", "block");

        console.log("¡Ya existe!");
      } else {
        $("#notaClaveProdProve").css("display", "none");

        console.log("¡No existe!");
      }
    },
  });
}

function escribirClaveProveedorEditModal() {
  var proveedor = $("#cmbProveedorProductoEdit").val();
  var clave = $("#txtClaveProdProveEdit").val();
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
        $("#notaClaveProdProveEdit").css("display", "block");

        console.log("¡Ya existe!");
      } else {
        $("#notaClaveProdProveEdit").css("display", "none");

        console.log("¡No existe!");
      }
    },
  });
}

/* Añadir el impuesto */
function anadirProveedor() {
  if (!$("#txtPrecioProdProve").val()) {
    $("#invalid-precioProdU").css("display", "block");
    $("#txtPrecioProdProve").addClass("is-invalid");
  }
  var badPrecioProd =
    $("#invalid-precioProdU").css("display") === "block" ? false : true;
  if (badPrecioProd) {
    var data = [];
    var data = [];
    //El serializeArray obtiene un id consecutivo de los elementos fiel que posee el formulario
    $.each($("#formDatosProveedor").serializeArray(), function (i, field) {
      data.push({ id: i, campos: field.name, datos: field.value });
    });
    $.ajax({
      url: "../../php/funciones.php",
      data: {
        clase: "save_data",
        funcion: "save_datosProveedor2",
        datos: data,
      },
      dataType: "json",
      success: function (respuesta) {
        console.log(
          "respuesta agregar datos de proveedor del producto:",
          respuesta
        );

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
            msg: "¡Se guardó el proveedor con éxito!",
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
  }
}

/* editar el impuesto */
function editarProveedor(id) {
  if (!$("#txtPrecioProdProveEdit").val()) {
    $("#invalid-precioProdEd").css("display", "block");
    $("#txtPrecioProdProveEdit").addClass("is-invalid");
  }
  var badPrecioProd =
    $("#invalid-precioProdEd").css("display") === "block" ? false : true;
  if (badPrecioProd) {
    var pkProducto = $("#cmbProveedorProductoEdit").val();
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

    var diasEnt = '';
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
    var idDetalleProdProv = $("#txtPKDatosProductoProveedor").val();

    $.ajax({
      url: "../../php/funciones.php",
      data: {
        clase: "edit_data",
        funcion: "edit_datosProveedor",
        datos: pkProducto,
        datos2: id,
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
          $("#txtEdicion").val("0");
          $("#cmbProve").val("0");
          $("#txtNombreProdProveEdit").val("");
          $("#txtClaveProdProveEdit").val("");
          $("#txtPrecioProdProveEdit").val("");
          $("#cmbMonedaPrecioEdit").val("100");
          $("#txtCantMinProdProveEdit").val("");
          $("#txtDiasEntregProdProveEdit").val("");
          $("#txtUnidadMedidaEdit").val("");
          $("#invalid-precioProdEd").css("display", "none");
          $("#txtPrecioProdProveEdit").removeClass("is-invalid");
          $('#editar_Producto').modal('hide');
          Lobibox.notify("success", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../../../img/timdesk/checkmark.svg",
            msg: "¡Se editó el proveedor con éxito!",
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
  }
}

/* Traer los datos del proveedor */
function datosEditProveedor(datoProProve) {
  console.log("ID del dato del producto del proveedor: " + datoProProve);
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_datos_producto_proveedor",
      datos: datoProProve,
    },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta datos del proveedor Producto:", respuesta);

      cargarCMBProveedorEditModalEdit(
        respuesta[0].FKProducto,
        "cmbProveedorProductoEdit"
      );
      $("#cmbProveedorProductoEdit").attr("disabled", "true");

      $("#txtNombreProdProveEdit").val(respuesta[0].NombreProducto);
      $("#txtClaveProdProveEdit").val(respuesta[0].Clave);
      $("#txtPrecioProdProveEdit").val(respuesta[0].Precio);
      $("#cmbMonedaPrecioEdit").val(respuesta[0].FKTipoMoneda);
      $("#txtCantMinProdProveEdit").val(respuesta[0].CantidadMinima);

      console.log("DIAS ENTREGA: "+respuesta[0].DiasEntrega);
      if ( respuesta[0].DiasEntrega == 'Sin confirmar'){
        $("#txtDiasEntregProdProveEdit").val("");
      }else{
        $("#txtDiasEntregProdProveEdit").val(respuesta[0].DiasEntrega);
      }
      
      $("#txtUnidadMedidaEdit").val(respuesta[0].UnidadMedida);

      if (respuesta[0].Clave != "") {
        $("#txtNombreDProducto").val(
          respuesta[0].Clave + " - " + respuesta[0].NombreProducto
        );
      } else {
        $("#txtNombreDProducto").val(respuesta[0].NombreProducto);
      }

      $("#txtEdicion").val("1");
      $("#cmbProve").val(respuesta[0].FKProducto);

      $("#txtPKDatosProductoProveedor").val(datoProProve);
    },
    error: function (error) {
      console.log(error);
    },
  });
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
}

//Validar que se hayan completado todos los campos antes de pasar a la siguiente pestaña
function guardarProductosProveedor(id) {
  TerminarGuardadoDatos();
}

function TerminarGuardadoDatos() {
  window.location.href = "../proveedores";
}

function resetTabs(id) {
  $(".nav-link").removeClass("active");
  $(id).addClass("active");
}

function validEmptyInput(item, invalid = null) {
  const val = item.value;
  const parent = item.parentNode;
  let invalidDiv;
  if (invalid) {
    invalidDiv = document.getElementById(invalid);
  } else {
    for (let i = 0; i < parent.children.length; i++) {
      if (parent.children[i].classList.contains("invalid-feedback")) {
        invalidDiv = parent.children[i];
        break;
      }
    }
  }
  if (!val) {
    item.classList.add("is-invalid");
    invalidDiv.style.display = "block";
  } else {
    item.classList.remove("is-invalid");
    invalidDiv.style.display = "none";
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

function validarEmpresaProveedor(pkProveedor) {
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "validar_EmpresaProveedor",
      data: pkProveedor,
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
        window.location.href = "../proveedores";
        //return false;
      }
    },
  });
}

function validate_Permissions(pkPantalla, pestana, id) {
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

      //DATOS GENERALES
      if (pestana == "1") {
        var html = "";
        if (_permissions.edit == "1") {
          html = `<a href="#" class="btn-custom btn-custom--blue float-right" id="btnAgregarProveedor">Guardar</a>`;

          $("#btnAgregarProveedor2").html(html);

          $("#txtNombreComercial").removeClass("readNotEditPermissions");
          $("#txtNombreComercial").addClass("readEditPermissions");

          $("#cmbVendedor").removeClass("readNotEditPermissions");
          $("#cmbVendedor").addClass("readEditPermissions");

          $("#txtTelefono").removeClass("readNotEditPermissions");
          $("#txtTelefono").addClass("readEditPermissions");

          $("#txtEmail").removeClass("readNotEditPermissions");
          $("#txtEmail").addClass("readEditPermissions");

          $("#cbxCredito").removeClass("readNotEditPermissions");
          $("#cbxCredito").addClass("readEditPermissions");

          $("#txtMontoCredito").removeClass("readNotEditPermissions");
          $("#txtMontoCredito").addClass("readEditPermissions");

          $("#txtDiasCredito").removeClass("readNotEditPermissions");
          $("#txtDiasCredito").addClass("readEditPermissions");

          $("#btnDeletePermissions").removeClass("readNotEditPermissions");
          $("#btnDeletePermissions").addClass("readEditPermissions");
        } else {
          html = ``;
          $("#btnAgregarProveedor2").html(html);

          $("#txtNombreComercial").removeClass("readEditPermissions");
          $("#txtNombreComercial").addClass("readNotEditPermissions");

          $("#cmbVendedor").removeClass("readEditPermissions");
          $("#cmbVendedor").addClass("readNotEditPermissions");

          $("#txtTelefono").removeClass("readEditPermissions");
          $("#txtTelefono").addClass("readNotEditPermissions");

          $("#txtEmail").removeClass("readEditPermissions");
          $("#txtEmail").addClass("readNotEditPermissions");

          $("#cbxCredito").removeClass("readEditPermissions");
          $("#cbxCredito").addClass("readNotEditPermissions");

          $("#txtMontoCredito").removeClass("readEditPermissions");
          $("#txtMontoCredito").addClass("readNotEditPermissions");

          $("#txtDiasCredito").removeClass("readEditPermissions");
          $("#txtDiasCredito").addClass("readNotEditPermissions");

          $("#btnDeletePermissions").removeClass("readEditPermissions");
          $("#btnDeletePermissions").addClass("readNotEditPermissions");
        }
      }

      //DATOS FISCALES
      if (pestana == "2") {
        var html = ``;
        if (_permissions.edit == "1") {
          html =
            `<a href="#" class="btn-custom btn-custom--blue" id="btnAnadirProveedor" onclick="anadirRazonSocial(` +
            id +
            `)">Añadir razón social</a>  `;
          $("#btnAnadirProveedor2").html(html);

          $("#txtRazonSocial").removeClass("readNotEditPermissions");
          $("#txtRazonSocial").addClass("readEditPermissions");

          $("#txtRFC").removeClass("readNotEditPermissions");
          $("#txtRFC").addClass("readEditPermissions");

          $("#txtCalle").removeClass("readNotEditPermissions");
          $("#txtCalle").addClass("readEditPermissions");

          $("#txtNumExt").removeClass("readNotEditPermissions");
          $("#txtNumExt").addClass("readEditPermissions");

          $("#txtNumInt").removeClass("readNotEditPermissions");
          $("#txtNumInt").addClass("readEditPermissions");

          $("#txtColonia").removeClass("readNotEditPermissions");
          $("#txtColonia").addClass("readEditPermissions");

          $("#txtMunicipio").removeClass("readNotEditPermissions");
          $("#txtMunicipio").addClass("readEditPermissions");

          $("#cmbPais").removeClass("readNotEditPermissions");
          $("#cmbPais").addClass("readEditPermissions");

          $("#cmbEstado").removeClass("readNotEditPermissions");
          $("#cmbEstado").addClass("readEditPermissions");

          $("#txtCP").removeClass("readNotEditPermissions");
          $("#txtCP").addClass("readEditPermissions");
        } else {
          html = ``;
          $("#btnAnadirProveedor2").html(html);

          $("#txtRazonSocial").removeClass("readEditPermissions");
          $("#txtRazonSocial").addClass("readNotEditPermissions");

          $("#txtRFC").removeClass("readEditPermissions");
          $("#txtRFC").addClass("readNotEditPermissions");

          $("#txtCalle").removeClass("readEditPermissions");
          $("#txtCalle").addClass("readNotEditPermissions");

          $("#txtNumExt").removeClass("readEditPermissions");
          $("#txtNumExt").addClass("readNotEditPermissions");

          $("#txtNumInt").removeClass("readEditPermissions");
          $("#txtNumInt").addClass("readNotEditPermissions");

          $("#txtColonia").removeClass("readEditPermissions");
          $("#txtColonia").addClass("readNotEditPermissions");

          $("#txtMunicipio").removeClass("readEditPermissions");
          $("#txtMunicipio").addClass("readNotEditPermissions");

          $("#cmbPais").removeClass("readEditPermissions");
          $("#cmbPais").addClass("readNotEditPermissions");

          $("#cmbEstado").removeClass("readEditPermissions");
          $("#cmbEstado").addClass("readNotEditPermissions");

          $("#txtCP").removeClass("readEditPermissions");
          $("#txtCP").addClass("readNotEditPermissions");
        }

        cargarTablaRazonesSociales(id, _permissions.edit);
      }

      //DATOS DE CONTACTO
      if (pestana == "3") {
        var html = ``;
        if (_permissions.edit == "1") {
          html =
            `<a href="#" class="btn-custom btn-custom--blue" id="btnAnadirContacto" onclick="validarContacto(` +
            id +
            `)">Añadir Contacto</a>  `;
          $("#btnAnadirContacto2").html(html);

          $("#txtNombreContacto").removeClass("readNotEditPermissions");
          $("#txtNombreContacto").addClass("readEditPermissions");

          $("#txtApellidoContacto").removeClass("readNotEditPermissions");
          $("#txtApellidoContacto").addClass("readEditPermissions");

          $("#txtPuesto").removeClass("readNotEditPermissions");
          $("#txtPuesto").addClass("readEditPermissions");

          $("#txtTelefono").removeClass("readNotEditPermissions");
          $("#txtTelefono").addClass("readEditPermissions");

          $("#txtCelular").removeClass("readNotEditPermissions");
          $("#txtCelular").addClass("readEditPermissions");

          $("#txtEmail").removeClass("readNotEditPermissions");
          $("#txtEmail").addClass("readEditPermissions");
        } else {
          html = ``;
          $("#btnAnadirContacto2").html(html);

          $("#txtNombreContacto").removeClass("readEditPermissions");
          $("#txtNombreContacto").addClass("readNotEditPermissions");

          $("#txtApellidoContacto").removeClass("readEditPermissions");
          $("#txtApellidoContacto").addClass("readNotEditPermissions");

          $("#txtPuesto").removeClass("readEditPermissions");
          $("#txtPuesto").addClass("readNotEditPermissions");

          $("#txtTelefono").removeClass("readEditPermissions");
          $("#txtTelefono").addClass("readNotEditPermissions");

          $("#txtCelular").removeClass("readEditPermissions");
          $("#txtCelular").addClass("readNotEditPermissions");

          $("#txtEmail").removeClass("readEditPermissions");
          $("#txtEmail").addClass("readNotEditPermissions");
        }

        cargarTablaContactos(id, _permissions.edit);
      }

      //DATOS BANCARIOS
      if (pestana == "4") {
        var html = ``;
        if (_permissions.edit == "1") {
          html =
            `<a href="#" class="btn-custom btn-custom--blue" id="btnAnadirContacto" onclick="validarBanco(` +
            id +
            `)">Añadir Cuenta Bancaria</a>`;
          $("#btnAnadirContacto2").html(html);

          $("#cmbBanco").removeClass("readNotEditPermissions");
          $("#cmbBanco").addClass("readEditPermissions");

          $("#txtNoCuenta").removeClass("readNotEditPermissions");
          $("#txtNoCuenta").addClass("readEditPermissions");

          $("#txtCLABE").removeClass("readNotEditPermissions");
          $("#txtCLABE").addClass("readEditPermissions");

          $("#cmbCostoUniVentaEspecial").removeClass("readNotEditPermissions");
          $("#cmbCostoUniVentaEspecial").addClass("readEditPermissions");
        } else {
          html = ``;
          $("#btnAnadirContacto2").html(html);

          $("#cmbBanco").removeClass("readEditPermissions");
          $("#cmbBanco").addClass("readNotEditPermissions");

          $("#txtNoCuenta").removeClass("readEditPermissions");
          $("#txtNoCuenta").addClass("readNotEditPermissions");

          $("#txtCLABE").removeClass("readEditPermissions");
          $("#txtCLABE").addClass("readNotEditPermissions");

          $("#cmbCostoUniVentaEspecial").removeClass("readEditPermissions");
          $("#cmbCostoUniVentaEspecial").addClass("readNotEditPermissions");
        }

        cargarTablaBancos(id, _permissions.edit);
      }

      //DATOS DE PRODUCTOS
      if (pestana == "5") {
        var html = ``,
          html2 = ``;
        if (_permissions.edit == "1") {
          html = `<a href="#" class="btn-custom btn-custom--border-blue" id="btnAnadirProveedor" onclick="validarProveedor(${id})">Añadir producto</a>`;
          html2 = `<a href="#" class="btn-custom btn-custom--blue ml-5" id="btnAgregarTipoProducto" onclick="guardarProductosProveedor(${id})">Volver a proveedores</a>`;
          $("#btnAnadirProveedor2").html(html);

          $("#btnAgregarTipoProducto2").html(html2);

          $("#cmbProveedorProducto").removeClass("readNotEditPermissions");
          $("#cmbProveedorProducto").addClass("readEditPermissions");

          $("#txtNombreProdProve").removeClass("readNotEditPermissions");
          $("#txtNombreProdProve").addClass("readEditPermissions");

          $("#txtClaveProdProve").removeClass("readNotEditPermissions");
          $("#txtClaveProdProve").addClass("readEditPermissions");

          $("#txtPrecioProdProve").removeClass("readNotEditPermissions");
          $("#txtPrecioProdProve").addClass("readEditPermissions");

          $("#cmbMonedaPrecio").removeClass("readNotEditPermissions");
          $("#cmbMonedaPrecio").addClass("readEditPermissions");

          $("#txtCantMinProdProve").removeClass("readNotEditPermissions");
          $("#txtCantMinProdProve").addClass("readEditPermissions");

          $("#txtDiasEntregProdProve").removeClass("readNotEditPermissions");
          $("#txtDiasEntregProdProve").addClass("readEditPermissions");

          $("#txtUnidadMedida").removeClass("readNotEditPermissions");
          $("#txtUnidadMedida").addClass("readEditPermissions");
        } else {
          html = ``;
          html2 = ``;
          $("#btnAnadirProveedor2").html(html);

          $("#btnAgregarTipoProducto2").html(html2);

          $("#cmbProveedorProducto").removeClass("readEditPermissions");
          $("#cmbProveedorProducto").addClass("readNotEditPermissions");

          $("#txtNombreProdProve").removeClass("readEditPermissions");
          $("#txtNombreProdProve").addClass("readNotEditPermissions");

          $("#txtClaveProdProve").removeClass("readEditPermissions");
          $("#txtClaveProdProve").addClass("readNotEditPermissions");

          $("#txtPrecioProdProve").removeClass("readEditPermissions");
          $("#txtPrecioProdProve").addClass("readNotEditPermissions");

          $("#cmbMonedaPrecio").removeClass("readEditPermissions");
          $("#cmbMonedaPrecio").addClass("readNotEditPermissions");

          $("#txtCantMinProdProve").removeClass("readEditPermissions");
          $("#txtCantMinProdProve").addClass("readNotEditPermissions");

          $("#txtDiasEntregProdProve").removeClass("readEditPermissions");
          $("#txtDiasEntregProdProve").addClass("readNotEditPermissions");

          $("#txtUnidadMedida").removeClass("readEditPermissions");
          $("#txtUnidadMedida").addClass("readNotEditPermissions");
        }

        cargarTablaProductos(id, _permissions.edit);
      }
    },
  });
}