var _permissions = { 
  read: 0,
  add: 0,
  edit: 0,
  delete: 0,
  export: 0
}

/*----------------------Diseño datos del cliente-------------------------------*/
//Cargar pestaña de Datos del cliente
function CargarDatosCliente() {
  validate_Permissions(10,'url');

  resetTabs("#CargarDatosCliente");
  cargarCMBMedioContactoCliente("", "cmbMedioContactoCliente");
  cargarCMBVendedor("", "cmbVendedor");
  cargarcmbCategoria("","cmbCategoria");
  cargarCMBRegimen("cmbRegimen");
  cargarCMBPaises("241", "cmbPais");
  cargarCMBEstados("241", "cmbEstado");

  var html = `<div class="card shadow mb-4">
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-12">
                      <form id="formDatosCliente" class="needs-validation" novalidate> 
                        <div class="form-group">
                          <div class="row">
                            <div class="col-xl-9 col-lg-9 col-md-6 col-sm-3 col-xs-0">
                              
                            </div>
                            <div class="col-xl-1 col-lg-1 col-md-2 col-sm-4 col-xs-6">
                              <label for="usr">Estatus:*</label>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-4 col-sm-5 col-xs-6">
                              <input type="checkbox" id="active-cliente" class="check-custom" checked>
                              <label class="shadow-sm check-custom-label" for="active-cliente">
                                <div class="circle"></div>
                                <div class="check-inactivo">Inactivo</div>
                                <div class="check-activo">Activo</div>
                              </label>
                            </div>
                          </div>
                        </div>
                        
                        <div class="form-group">
                          <div class="row">
                            <div class="col-lg-4">
                              <label for="usr">Nombre comercial:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control" type="text" name="txtNombreComercial" id="txtNombreComercial" autofocus="" maxlength="255" placeholder="Ej. GH Medic" onkeyup="escribirNombre()" required style="text-transform: uppercase">
                                  <div class="invalid-feedback" id="invalid-nombreCom">El cliente debe tener un nombre comercial.</div>
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <label for="usr">Medio de contacto*:</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                    <select name="cmbMedioContactoCliente" id="cmbMedioContactoCliente" required>
                                    </select>
                                    <div class="invalid-feedback" id="invalid-medioCont">El cliente debe tener un medio de contacto.</div>
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <label for="usr">Vendedor*:</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                    <select name="cmbVendedor" id="cmbVendedor" required>
                                    </select>
                                    <div class="invalid-feedback" id="invalid-vendedor">El cliente debe tener un vendedor.</div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="form-group">
                          <div class="row">
                            <div class="col-lg-3">
                              <label for="usr">Contacto:</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control alphaNumeric-only" type="text" name="txtContactoDir" id="txtContactoDir" maxlength="15" placeholder="Nombre">
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-3">
                              <label for="usr">Teléfono:</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control numeric-only" type="text" name="txtTelefono" id="txtTelefono" autofocus="" minlength="7" maxlength="10" placeholder="Ej. 33 3333 33 33">
                                  <div class="invalid-feedback">El cliente debe tener un número télefonico.</div>
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-3">
                              <label for="usr">E-mail:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control" type="email" name="txtEmail" id="txtEmail" autofocus="" required maxlength="100" placeholder="Ej. ejemplo@dominio.com" onkeyup="validarCorreo(this.value)">
                                  <div class="invalid-feedback" id="invalid-email">El cliente debe tener un email.</div>
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-3">
                              <label for="usr">Categoria:</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                    <select name="cmbCategoria" id="cmbCategoria">
                                    </select>
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
                              <label for="usr">Monto de crédito*:</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input type="text" class="form-control numericDecimal-only" name="txtMontoCredito" id="txtMontoCredito"  maxlength="13"  oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" placeholder="Ej. 1000.00" disabled="disabled" onkeyup="validEmptyInput('txtMontoCredito', 'invalid-montoCred', 'El crédito debe tener un monto.')">
                                  <div class="invalid-feedback" id="invalid-montoCred">El crédito debe tener un monto.</div>
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-5">
                              <label for="usr">Días de crédito*:</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input type="text" class="form-control numeric-only" name="txtDiasCredito" id="txtDiasCredito" ="" maxlength="3"  oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" placeholder="Ej. 30" disabled="disabled" onkeyup="validEmptyInput('txtDiasCredito', 'invalid-diasCred', 'El crédito debe tener un número de días.')">
                                  <div class="invalid-feedback" id="invalid-diasCred">El crédito debe tener un número de días.</div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>

                        <br>
                        
                        <!--
                        <div class="form-group">
                          <div class="row">
                            <div class="col-lg-4">
                              <label for="usr">Razón Social:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control" type="text" name="txtRazonSocial" id="txtRazonSocial" autofocus="" required maxlength="255" placeholder="Ej. GH Medic" onchange="escribirRazonSocial()" style="text-transform: uppercase">
                                  <div class="invalid-feedback" id="invalid-razon">La información fiscal debe tener una razón social.</div>
                                  <div class="invalid-feedback" id="invalid-razonTipoSociedad">La razón social no debe tener el tipo de sociedad.</div>
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <label for="usr">RFC:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control alphaNumeric-only" type="text" name="txtRFC" id="txtRFC" required maxlength="13" placeholder="Ej. GHMM100101AA1" onkeyup="validarInput()" oninput="let p=this.selectionStart;this.value=this.value.toUpperCase();this.setSelectionRange(p, p);">
                                  <div class="invalid-feedback" id="invalid-rfc">La información fiscal debe tener un RFC.</div>
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <label for="usr">Régimen fiscal:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <select name="cmbRegimen" id="cmbRegimen" required>
                                  </select>
                                  <div class="invalid-feedback" id="invalid-regimen">La información fiscal debe tener un régimen fiscal.</div>
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
                                  <input class="form-control" type="text" name="txtNumInt" id="txtNumInt"  maxlength="100" placeholder="Ej. 524">
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="form-group">
                          <div class="row">
                            <div class="col-lg-2">
                              <label for="usr">Código Postal:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control numeric-only" type="text" name="txtCP" id="txtCP" autofocus="" required maxlength="5" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" placeholder="Ej. 52632" onkeyup="validarCP();"">
                                  <div class="invalid-feedback" id="invalid-cp">El cliente debe tener un codigo postal.</div>
                                </div>
                              </div>
                            </div>
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
                                  <input class="form-control" type="text" name="txtMunicipio" id="txtMunicipio" maxlength="255" placeholder="Ej. Guadalajara">
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-2">
                              <label for="usr">País*:</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                    <select name="cmbPais" id="cmbPais"  onchange="cambioPais()" required>
                                    </select>
                                    <div class="invalid-feedback" id="invalid-paisFisc">La información fiscal debe tener un pais.</div>
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-2">
                              <label for="usr">Estado*:</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                    <select name="cmbEstado" id="cmbEstado" required>
                                    </select>
                                    <div class="invalid-feedback" id="invalid-paisEstadoFisc">La información fiscal debe tener un estado.</div>
                                  </span>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        -->
                        <br>
                        <label for="">* Campos requeridos</label>

                        </span> 
                        
                        <input type="hidden" value="0" id="txtEdicion">
                        <input type="hidden" value="0" id="txtRazonSocialHis">
                        <input type="hidden" value="0" id="txtRFCHis">
                        <input type="hidden" value="0" id="txtPKRazon">
                      </form>

                      <a class="btn-custom btn-custom--blue float-right" id="btnAgregarCliente">Continuar</a>
                    </div>
                  </div>
                </div>
              </div>`;

  $("#datos").append(html);
  resetValidations();
}

$(document).on("change", "#cmbMedioContactoCliente", function () {
  $("#invalid-medioCont").css("display", "block");
  $("#cmbMedioContactoCliente").addClass("is-invalid");
  if ($("#cmbMedioContactoCliente").val()) {
    $("#invalid-medioCont").css("display", "none");
    $("#cmbMedioContactoCliente").removeClass("is-invalid");
  }
});

$(document).on("change", "#cmbVendedor", function () {
  $("#invalid-vendedor").css("display", "block");
  $("#cmbVendedor").addClass("is-invalid");
  if ($("#cmbVendedor").val()) {
    $("#invalid-vendedor").css("display", "none");
    $("#cmbVendedor").removeClass("is-invalid");
  }
});

$(document).on("change", "#cmbRegimen", function () {
  if ($("#cmbRegimen").val()) {
    $("#invalid-regimen").css("display", "none");
    $("#cmbRegimen").removeClass("is-invalid");
  }
});

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
        /* if (data === respuesta[i].PKMedioContactoCliente) {
          selected = "selected";
        } else {
          selected = "";
        } */

        html +=
          '<option value="' +
          respuesta[i].PKMedioContactoCliente +
          '" ' +
          ">" +
          respuesta[i].MedioContactoCliente +
          "</option>";
      });

      /*html +=
        '<option value="add" style="background-color: #15589B;  color:white; text-align:center; width:100%;">Configurar medios de contacto</option>';*/

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
        /* if (data === respuesta[i].PKVendedor) {
          selected = "selected";
        } else {
          selected = "";
        } */

        html +=
          '<option value="' +
          respuesta[i].PKVendedor +
          '" ' +
          ">" +
          respuesta[i].Nombre +
          "</option>";
      });

      /*html +=
        '<option value="add" style="background-color: #15589B;  color:white; text-align:center; width:100%;">Configurar vendedores</option>';*/

      $("#" + input + "").html(html);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function cargarcmbCategoria(data, input) {
  var html = '<option value="0"selected> Sin categoria</option>';

  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_CategoriaCliente" },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta vendedor: ", respuesta);

      //html += '<option value="0">Seleccione un vendedor...</option>';

      $.each(respuesta, function (i) {
        if (data === respuesta[i].id) {
          selected = "selected";
        } else {
          selected = "";
        }

        html +=
          '<option value="' +
          respuesta[i].id +
          '" ' +
          selected +
          ">" +
          respuesta[i].categoria +
          "</option>";
      });

      /*html +=
        '<option value="add" style="background-color: #15589B;  color:white; text-align:center; width:100%;">Configurar vendedores</option>';*/

      $("#" + input + "").html(html);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function cargarCMBRegimen(input) {
  var html = "";
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_regimen" },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta de los régimenes fiscales: ", respuesta);

      html += '<option data-placeholder="true"></option>';

      $.each(respuesta, function (i) {

        html +=
          '<option value="' +
          respuesta[i].id +
          '" ' +
          ">" +
          respuesta[i].clave +
          ' - ' +
          respuesta[i].descripcion +
          "</option>";
      });

      $("#" + input + "").html(html);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function validarCorreo(value) {
  console.log(value);
  var reg =
    /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

  var regOficial =
    /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/;

  //Se muestra un texto a modo de ejemplo, luego va a ser un icono
  if (reg.test(value) && regOficial.test(value)) {
    $("#invalid-email").css("display", "none");
    $("#invalid-email").text("El cliente debe tener un email.");
    $("#txtEmail").removeClass("is-invalid");
  } else if (reg.test(value)) {
    $("#invalid-email").css("display", "none");
    $("#invalid-email").text("El cliente debe tener un email.");
    $("#txtEmail").removeClass("is-invalid");
  } else {
    $("#invalid-email").css("display", "block");
    $("#invalid-email").text("El email debe ser valido.");
    $("#txtEmail").addClass("is-invalid");
  }
}
/*----------------------Estilos de funciones-------------------------------*/

//Funciones para los eventos de los elementos de la página
function mostrarColor() {
  if (document.getElementById("cmbEstatusCliente").value == 1) {
    document.getElementById("cmbEstatusCliente").style.background = "#28c67a";
    document.getElementById("cmbEstatusCliente").style.color = "#FFFFFF";
  } else {
    document.getElementById("cmbEstatusCliente").style.background = "#cac8c6";
  }
}

function cambiarColor() {
  //Cambiar de color los combos al abrir por primera vez la página
  $("#opEG-1").css({ "background-color": "#28c67a", color: "#FFFFFF" });
  $("#opEG-2").css({ "background-color": "#cac8c6" });

  if (document.getElementById("cmbEstatusCliente").value == 1) {
    document.getElementById("cmbEstatusCliente").style.background = "#28c67a";
    document.getElementById("cmbEstatusCliente").style.color = "#FFFFFF";
  } else {
    document.getElementById("cmbEstatusCliente").style.background = "#cac8c6";
  }
}

//Funciones para los eventos de los elementos de la página

function escribirNombre() {
  var valor = $("#txtNombreComercial").val();
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
        $("#invalid-nombreCom").css("display", "block");
        $("#invalid-nombreCom").text(
          "El nombre ya esta registrado en el sistema."
        );
        $("#txtNombreComercial").addClass("is-invalid");
        console.log("¡Ya existe!");
      } else {
        $("#invalid-nombreCom").css("display", "none");
        $("#invalid-nombreCom").text(
          "El cliente debe tener un nombre comercial."
        );
        $("#txtNombreComercial").removeClass("is-invalid");
        console.log("¡No existe!");
        if (!valor) {
          $("#invalid-nombreCom").css("display", "block");
          $("#invalid-nombreCom").text(
            "El cliente debe tener un nombre comercial."
          );
          $("#txtNombreComercial").addClass("is-invalid");
        }
      }
    },
  });
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

    $("#invalid-montoCred").css("display", "none");
    $("#txtMontoCredito").removeClass("is-invalid");
    $("#invalid-diasCred").css("display", "none");
    $("#txtDiasCredito").removeClass("is-invalid");
  }
}
/*----------------------Botón agregar cliente-------------------------------*/

//Validar que se hayan completado todos los campos antes de pasar a la siguiente pestaña
$(document).on("click", "#btnAgregarCliente", function () {
  console.log("Insertando");
  if ($("#formDatosCliente")[0].checkValidity()) {
    console.log("No falta nada");
    var badNombreCom =
      $("#invalid-nombreCom").css("display") === "block" ? false : true;
    var badMedioContCli =
      $("#invalid-medioCont").css("display") === "block" ? false : true;
    var badVendedorCli =
      $("#invalid-vendedor").css("display") === "block" ? false : true;
    var badEmailCli =
      $("#invalid-email").css("display") === "block" ? false : true;
    var badMonto =
      $("#invalid-montoCred").css("display") === "block" ? false : true;
    var badDiasCred =
      $("#invalid-diasCred").css("display") === "block" ? false : true;
   /*  var badRazon =
      $("#invalid-razon").css("display") === "block" ? false : true;
    var badRFC = $("#invalid-rfc").css("display") === "block" ? false : true;
    var badRegimen = $("#invalid-regimen").css("display") === "block" ? false : true;
    var badCP = $("#invalid-cp").css("display") === "block" ? false : true;
    var badPais =
      $("#invalid-paisFisc").css("display") === "block" ? false : true;
    var badEstado =
      $("#invalid-paisEstadoFisc").css("display") === "block" ? false : true; */
    if (
      badNombreCom &&
      badMedioContCli &&
      badVendedorCli &&
      badEmailCli &&
      badMonto &&
      badDiasCred /* &&
      badRazon &&
      badCP &&
      badRFC &&
      badRegimen &&
      badPais &&
      badEstado */
    ) {
      var data = [];
      //El serializeArray obtiene un id consecutivo de los elementos fiel que posee el formulario
      $.each($("#formDatosCliente").serializeArray(), function (i, field) {
        data.push({ id: i, campos: field.name, datos: field.value });
      });
      console.log(data);
      var pkUsuario = $("#PKUsuario").val();
      var montoCredito, diasCredito;

      if ($("#cbxCredito").is(":checked")) {
        montoCredito = $("#txtMontoCredito").val();
        diasCredito = $("#txtDiasCredito").val();
      } else {
        montoCredito = "0";
        diasCredito = "0";
      }

      var nombreComercial = $("#txtNombreComercial").val();
      var medioContactoCliente = $("#cmbMedioContactoCliente").val();
      var vendedor = $("#cmbVendedor").val();

      var contacto = $("#txtContactoDir").val();
      var telefono = $("#txtTelefono").val();
      var email = $("#txtEmail").val();
      let categoria = $("#cmbCategoria").val();

      var estatus = $("#active-cliente").prop("checked") ? 1 : 0;

      /* var razonSocial = $("#txtRazonSocial").val();
      var rfc = $("#txtRFC").val();
      var regimen = $("#cmbRegimen").val();
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
      var cp = $("#txtCP").val(); */

      var pkRazon = $("#txtPKRazon").val();

      $.ajax({
        url: "../../php/funciones.php",
        data: {
          clase: "save_data",
          funcion: "save_datosCliente",
          datos: data,
          datos2: nombreComercial,
          datos3: medioContactoCliente,
          datos4: vendedor,
          datos5: montoCredito,
          datos6: diasCredito,
          datos7: telefono,
          datos8: email,
          datos9: estatus,
          datos10: categoria,
          /* datos10: razonSocial,
          datos11: rfc,
          datos12: calle,
          datos13: numExt,
          datos14: numInt,
          datos15: colonia,
          datos16: municipio,
          datos17: pais,
          datos18: estado,
          datos19: cp,
          datos20: pkRazon,
          datos21: regimen, */
          datos22: contacto
        },
        dataType: "json",
        success: function (respuesta) {
          console.log(
            "respuesta agregar datos generales del cliente:",
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
              msg: "¡Datos generales registrados correctamente!",
              sound: '../../../../../sounds/sound4'
            });
            SeguirDatosFiscales(respuesta[0].id);
            //SeguirContacto(respuesta[0].id);
          } else {
            Lobibox.notify("error", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../../img/timdesk/notificacion_error.svg",
              msg: "Revisar datos",
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
            position: "center top", //or 'center bottom'
            icon: true,
            //img: '<i class="fas fa-check-circle"></i>',
            img: "../../../../img/timdesk/notificacion_error.svg",
            msg: "Revisar datos",
            sound: '../../../../../sounds/sound4'
          });
        },
      });
    }
  } else {
    console.log("Falta algo");

    if (!$("#txtNombreComercial").val()) {
      $("#invalid-nombreCom").css("display", "block");
      $("#txtNombreComercial").addClass("is-invalid");
    }
    if (!$("#cmbMedioContactoCliente").val()) {
      $("#invalid-medioCont").css("display", "block");
      $("#cmbMedioContactoCliente").addClass("is-invalid");
    }
    if (!$("#cmbVendedor").val()) {
      $("#invalid-vendedor").css("display", "block");
      $("#cmbVendedor").addClass("is-invalid");
    }
    if (!$("#txtEmail").val()) {
      $("#invalid-email").css("display", "block");
      $("#txtEmail").addClass("is-invalid");
    }
    if ($("#cbxCredito").prop("checked")) {
      if (!$("#txtMontoCredito").val()) {
        $("#invalid-montoCred").css("display", "block");
        $("#txtMontoCredito").addClass("is-invalid");
      }
      if (!$("#txtDiasCredito").val()) {
        $("#invalid-diasCred").css("display", "block");
        $("#txtDiasCredito").addClass("is-invalid");
      }
    }
    if (!$("#txtRFC").val()) {
      $("#invalid-rfc").css("display", "block");
      $("#txtRFC").addClass("is-invalid");
    }
    if (!$("#cmbRegimen").val()) {
      $("#invalid-regimen").css("display", "block");
      $("#txtRFC").addClass("is-invalid");
    }
    if (!$("#txtRazonSocial").val()) {
      $("#invalid-razon").css("display", "block");
      $("#txtRazonSocial").addClass("is-invalid");
    }
    if (!$("#txtCP").val()) {
      $("#invalid-cp").css("display", "block");
      $("#txtCP").addClass("is-invalid");
    }
    if (!$("#cmbPais").val()) {
      $("#invalid-paisFisc").css("display", "block");
      $("#cmbPais").addClass("is-invalid");
    }
    if (!$("#cmbEstado").val()) {
      $("#invalid-paisEstadoFisc").css("display", "block");
      $("#cmbEstado").addClass("is-invalid");
    }
  }
  //SeguirDatosFiscales('1');
  //SeguirContacto('1');
});

function regresarDatosCliente() {
  window.location.href = "agregar_cliente.php";
}

function SeguirDatosFiscales(id) {
  //$('#datos').load('datos_impuestos.php',{idProducto : idProd});
  resetTabs("#CargarDatosEdicionFiscal");

  console.log("Id recien agregado:" + id);

  cargarCMBPaises("146", "cmbPais");
  //cargarCMBEstados("146", "cmbEstado");

  var html = `<div class="card shadow mb-4">
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-12">
                      <form id="formDatosFiscales"> 
                        <input type='hidden' value='` + id + `' name="txtPKCliente" id="txtPKCliente">
                        <span id="areaDiseno">
                        
                        <div class="form-group">
                          <div class="row">
                            <div class="col-lg-4">
                              <label for="usr">Razón Social:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control alphaNumeric-only" type="text" name="txtRazonSocial" id="txtRazonSocial" autofocus="" required="" maxlength="255" placeholder="Ej. GH Medic S.A. de C.V." onkeyup="escribirRazonSocial(` + id + `)" style="text-transform: uppercase">
                                  <div class="invalid-feedback" id="invalid-razon">La información fiscal debe tener una razón social.</div>
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <label for="usr">RFC:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control alphaNumeric-only" type="text" name="txtRFC" id="txtRFC" autofocus="" required="" maxlength="13" placeholder="Ej. GHMM100101AA1" onkeyup="validarInput(` + id + `)" oninput="let p=this.selectionStart;this.value=this.value.toUpperCase();this.setSelectionRange(p, p);">
                                  <div class="invalid-feedback" id="invalid-rfc">La información fiscal debe tener un RFC.</div>
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <label for="usr">Régimen fiscal:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <select name="cmbRegimenInfoFiscal" id="cmbRegimenInfoFiscal" required>
                                  </select>
                                  <div class="invalid-feedback" id="invalid-regimen">La información fiscal debe tener un régimen fiscal.</div>
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
                                  <input class="form-control alphaNumeric-only" type="text" name="txtCalle" id="txtCalle" autofocus="" required="" maxlength="255" placeholder="Ej. Av. México">
                                  
                                  <img  id="notaFCalle" name="notaFCalle" style="display: none;"
                                  src="../../../../img/timdesk/alerta.svg" width=30px
                                  title="Campo requerido" readonly>
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-3">
                              <label for="usr">Número exterior:</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control alphaNumeric-only" type="text" name="txtNumExt" id="txtNumExt" autofocus="" required="" maxlength="10" placeholder="Ej. 2353 A">

                                  <img  id="notaNumExt" name="notaNumExt" style="display: none;"
                                  src="../../../../img/timdesk/alerta.svg" width=30px
                                  title="Campo requerido" readonly>
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-3">
                              <label for="usr">Número interior:</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control alphaNumeric-only" type="text" name="txtNumInt" id="txtNumInt" autofocus="" required="" maxlength="10" placeholder="Ej. 524">

                                  <img  id="notaNumInt" name="notaNumInt" style="display: none;"
                                  src="../../../../img/timdesk/alerta.svg" width=30px
                                  title="Campo requerido" readonly>
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
                                  <input class="form-control alphaNumeric-only" type="text" name="txtColonia" id="txtColonia" autofocus="" required="" maxlength="25" placeholder="Ej. Los Agaves">
                                  
                                  <img  id="notaFColonia" name="notaFColonia" style="display: none;"
                                  src="../../../../img/timdesk/alerta.svg" width=30px
                                  title="Campo requerido" readonly>
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-3">
                              <label for="usr">Municipio:</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control alphaNumeric-only" type="text" name="txtMunicipio" id="txtMunicipio" autofocus="" required="" maxlength="25" placeholder="Ej. Guadalajara">

                                  <img  id="notaMunicipio" name="notaMunicipio" style="display: none;"
                                  src="../../../../img/timdesk/alerta.svg" width=30px
                                  title="Campo requerido" readonly>
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-2">
                              <label for="usr">País*:</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <select name="cmbPais" id="cmbPais" required="" onchange="cambioPais()">
                                  </select>
                                  <div class="invalid-feedback" id="invalid-paisFisc">La información fiscal debe tener un pais.</div>
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-2">
                              <label for="usr">Estado*:</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <select name="cmbEstado" id="cmbEstado" required="">
                                  </select>
                                  <div class="invalid-feedback" id="invalid-paisEstadoFisc">La información fiscal debe tener un estado.</div>
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-2">
                              <label for="usr">Código Postal:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control alphaNumeric-only" type="text" name="txtCP" id="txtCP" autofocus="" required="" maxlength="5" placeholder="Ej. 52632" onkeyup="validarCP();">
                                  <div class="invalid-feedback" id="invalid-cp">El cliente debe tener un codigo postal.</div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <label for="">* Campos requeridos</label>
                        <div class="form-group">
                          <div class="row">
                            <div class="col-lg-9">
                            </div>
                            <div class="col-lg-3 d-flex justify-content-end" style="text-align:center!important; margin-top:25px;">
                              <a href="#" class="btn-custom btn-custom--blue" id="btnAnadirProveedor" onclick="anadirRazonSocial(` + id + `)">Añadir Razón Social</a>  
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
 
  cargarCMBRegimenInfoFiscal("", "cmbRegimenInfoFiscal");

  cargarTablaRazonesSociales(id);
}

function cargarCMBRegimenInfoFiscal(data, input) {
  var html = "";
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_regimen" },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta de los régimenes fiscales: ", respuesta);

      html += '<option data-placeholder="true"></option>';

      $.each(respuesta, function (i) {
        if (data === respuesta[i].id) {
          selected = "selected";
        } else {
          selected = "";
        }

        html +=
          '<option value="' +
          respuesta[i].id +
          '" ' +
          selected +
          ">" +
          respuesta[i].clave +
          ' - ' +
          respuesta[i].descripcion +
          "</option>";
      });

      $("#" + input + "").html(html);
    },
    error: function (error) {
      console.log(error);
    },
  });
  CargarSlimRegimenInfoFiscal();
}

function escribirRazonSocial(id) {
  var razonSocial = $("#txtRazonSocial").val();
  var razonSocialHis = $("#txtRazonSocialHis").val();

  if (razonSocial != razonSocialHis) {
    $.ajax({
      url: "../../php/funciones.php",
      data: {
        clase: "get_data",
        funcion: "validar_razonSocial_Cliente",
        data: razonSocial,
        data2: id,
      },
      dataType: "json",
      success: function (data) {
        console.log("respuesta razón social validado: ", data);
        // Validar si ya existe el identificador con ese nombre
        if (parseInt(data[0]["existe"]) == 1) {
          console.log("¡Ya existe!");
          $("#txtRazonSocial").addClass("is-invalid");
          $("#invalid-razon").css("display", "block");
          $("#invalid-razon").text("La razón social ya existe en el sistema.");
        } else {
          console.log("¡No existe!");
          $("#txtRazonSocial").removeClass("is-invalid");
          $("#invalid-razon").css("display", "none");
          $("#invalid-razon").text("El cliente debe tener una razón social.");
          if (razonSocial === "") {
            $("#txtRazonSocial").addClass("is-invalid");
            $("#invalid-razon").css("display", "block");
            $("#invalid-razon").text("El cliente debe tener una razón social.");
          }
        }
      },
    });
  }
}

function anadirRazonSocial(id) {
  if (!$("#txtRFC").val() || !$("#txtRazonSocial").val() || !$("#txtCP").val() || !$("#cmbPais").val() || !$("#cmbEstado").val()) {
    console.log("Faltan campos");
    if (!$("#txtRFC").val()) {
      $("#invalid-rfc").css("display", "block");
      $("#txtRFC").addClass("is-invalid");
    }
    if (!$("#txtRazonSocial").val()) {
      $("#invalid-razon").css("display", "block");
      $("#txtRazonSocial").addClass("is-invalid");
    }
    if (!$("#txtCP").val()) {
      $("#invalid-cp").css("display", "block");
      $("#txtCP").addClass("is-invalid");
    }
    if (!$("#cmbPais").val()) {
      $("#invalid-paisFisc").css("display", "block");
      $("#cmbPais").addClass("is-invalid");
    }
    if (!$("#cmbEstado").val()) {
      $("#invalid-paisEstadoFisc").css("display", "block");
      $("#cmbEstado").addClass("is-invalid");
    }

  } else {
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

    var pkRazon = $("#txtPKRazon").val();
    var regimen = $("#cmbRegimenInfoFiscal").val();

    $.ajax({
      url: "../../php/funciones.php",
      data: {
        clase: "save_data",
        funcion: "save_razonSocial_Cliente",
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
        datos14: regimen
      },
      dataType: "json",
      success: function (respuesta) {
        console.log("respuesta añadir razon social:", respuesta);

        if (respuesta[0].status) {
          //-->$("#tblListadoDatosFiscalesCliente").DataTable().ajax.reload();
          $("#txtRazonSocial").val("");
          $("#txtRFC").val("");
          $("#txtCalle").val("");
          $("#txtNumExt").val("");
          $("#txtNumInt").val("");
          $("#txtColonia").val("");
          $("#txtMunicipio").val("");
          cargarCMBEstados("146", "cmbEstado");
          $("#cmbPais").val("146");
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
            msg: "¡Se guardó la razón social con éxito!",
            sound: '../../../../../sounds/sound4'
          });
          SeguirContacto(id);
        } else {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../../../img/timdesk/notificacion_error.svg",
            msg: "¡No se guardó la razón social con éxito :(!",
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

/* Eliminar el impuesto */
function obtenerIdRazonSocialClienteEliminar(pkRazonSocialCliente) {
  console.log("ID de la razon social : " + pkRazonSocialCliente);
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "delete_data",
      funcion: "delete_razonSocial_Cliente",
      datos: pkRazonSocialCliente,
    },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta eliminar razon social:", respuesta);

      if (respuesta[0].status) {
        $("#tblListadoDatosFiscalesCliente").DataTable().ajax.reload();
        Swal.fire(
          "Eliminación éxitosa",
          "Se eliminó la razón social con éxito",
          "success"
        );
      } else {
        Swal.fire("Error", "No se eliminó la razón con éxito", "warning");
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
}

/* Eliminar el impuesto */
function obtenerIdRazonSocialClienteEditar(pkRazonSocialCliente) {
  console.log("ID de la razon social : " + pkRazonSocialCliente);
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_datos_fiscal_cliente",
      datos: pkRazonSocialCliente,
    },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta datos de la razón social del cliente", respuesta);

      cargarCMBPaisesEdit("", "cmbPais", respuesta[0].Pais);
      cargarCMBEstadosEdit(respuesta[0].Pais, "cmbEstado", respuesta[0].Estado);

      $("#txtRazonSocial").val(respuesta[0].Razon_Social);
      $("#txtRFC").val(respuesta[0].RFCs);
      $("#txtCalle").val(respuesta[0].Calle);
      $("#txtNumInt").val(respuesta[0].Numero_Interior);
      $("#txtNumExt").val(respuesta[0].Numero_exterior);
      $("#txtColonia").val(respuesta[0].Colonia);
      $("#txtMunicipio").val(respuesta[0].Municipio);

      $("#txtCP").val(respuesta[0].CPs);

      $("#txtEdicion").val("1");
      $("#txtRazonSocialHis").val(respuesta[0].Razon_Social);
      $("#txtRFCHis").val(respuesta[0].RFCs);
      $("#txtPKRazon").val(respuesta[0].PKDomicilioFiscalCliente);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

//Validar que se hayan completado todos los campos antes de pasar a la siguiente pestaña
function guardarDatosFiscales(id) {
  Swal.fire({
    title: '<h3 style="arialRoundedEsp;">Datos agregados éxitosamente<h3>',
    html:
      '<h5 style="arialRoundedEsp;">Los datos fiscales del cliente fueron agregados con éxito.<h5>',
    icon: "success",
    showConfirmButton: true,
    focusConfirm: false,
    showCloseButton: false,
    showCancelButton: true,
    confirmButtonText: 'Seguir  <i class="far fa-arrow-alt-circle-right"></i>',
    cancelButtonText: 'Regresar  <i class="far fa-times-circle"></i>',
    buttonsStyling: false,
    allowEnterKey: false,
    customClass: {
      actions: "d-flex justify-content-around",
      confirmButton: "btn-custom btn-custom btn-custom--blue",
      cancelButton: "btn-custom btn-custom--border-blue",
    },
  }).then((result) => {
    if (result.isConfirmed) {
      var element = document.getElementById("content");
      element.scrollIntoView();
      SeguirContacto(id);
    } else if (result.dismiss === Swal.DismissReason.cancel) {
      //No hacer nada
    } else {
      //No hacer nada
    }
  });

  //SeguirContacto(id);
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
        /* if (data === respuesta[i].PKPais) {
          selected = "selected";
        } else {
          selected = "";
        } */

        html +=
          '<option value="' +
          respuesta[i].PKPais +
          '">' +
          respuesta[i].Pais +
          "</option>";
      });

      CargarSlimPaises();

      $("#" + input + "").html(html);
      //$("#cmbPais").val("241");
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
        /* if (data === respuesta[i].PKEstado) {
          selected = "selected";
        } else {
          selected = "";
        } */

        html +=
          '<option value="' +
          respuesta[i].PKEstado +
          '" ' +
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

/*----------------------Plugin Slim para la búsqueda dentro del-------------------------------*/
function CargarSlimPaises() {
  new SlimSelect({
    select: "#cmbPais",
    placeholder: "Seleciona un pais",
    allowDeselect: true,
    deselectLabel: '<span class="">✖</span>',
  });

  CargarSlimEstados();
}

function CargarSlimEstados() {
  new SlimSelect({
    select: "#cmbEstado",
    placeholder: "Seleciona un estado",
    allowDeselect: true,
    deselectLabel: '<span class="">✖</span>',
    addable: function (value) {
      var pkPais = $("#cmbPais").val();
      validarEstado(value, pkPais);
    },
  });
}

//Handler para el evento cuando cambia el input
// -Lleva la RFC a mayúsculas para validarlo
// -Elimina los espacios que pueda tener antes o después
function validarInput() {
  var vRFC = $("#txtRFC").val();
  var rfc = vRFC.trim().toUpperCase();
  var rfcCorrecto = rfcValido(rfc); // ⬅️ Acá se comprueba
  console.log("Correcto: " + rfcCorrecto);
  if (rfcCorrecto) {
    console.log("Válido");
    $("#invalid-rfc").css("display", "none");
    $("#invalid-rfc").text("El cliente debe tener un RFC.");
    $("#txtRFC").removeClass("is-invalid");
    escribirRFC();
  } else {
    console.log("No válido");
    $("#invalid-rfc").css("display", "block");
    $("#invalid-rfc").text("El RFC ingresado no es valido.");
    $("#txtRFC").addClass("is-invalid");
  }
}

function validarCP() {
  var value = $("#txtCP").val();
  var ercp = /(^([0-9]{5,5})|^)$/;
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "valid_cp",
      data: value
    },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta de validar CP:", respuesta);
      if (!ercp.test(value) || !value || respuesta == false) {
        $("#invalid-cp").css("display", "block");
        $("#invalid-cp").text("El CP ingresado no es valido.");
        $("#txtCP").addClass("is-invalid");
      } else {
        $("#invalid-cp").css("display", "none");
        $("#invalid-cp").text("El cliente debe tener un codigo postal.");
        $("#txtCP").removeClass("is-invalid");
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
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

function escribirRFC() {
  var rfc = $("#txtRFC").val();
  var rfcHis = $("#txtRFCHis").val();

  if (rfc != rfcHis) {
    $.ajax({
      url: "../../php/funciones.php",
      data: {
        clase: "get_data",
        funcion: "validar_rfc_Cliente",
        data: rfc
      },
      dataType: "json",
      success: function (data) {
        console.log("respuesta RFC validado: ", data);
        // Validar si ya existe el identificador con ese nombre
        if (parseInt(data.existe) == 1) {
          $("#invalid-rfc").css("display", "block");
          $("#invalid-rfc").text("El RFC ingresado ya existe en el sistema.");
          $("#txtRFC").addClass("is-invalid");
          console.log("¡Ya existe!");
        } else {
          $("#invalid-rfc").css("display", "none");
          $("#invalid-rfc").text("El cliente debe tener un RFC.");
          $("#txtRFC").removeClass("is-invalid");
          console.log("¡No existe!");
        }
      },
    });
  }
}

function escribirRazonSocial() {
  var valor = $("#txtRazonSocial").val();
  var valorHis = $("#txtRazonSocialHis").val();

  if (valor != valorHis) {
    console.log("Valor nombre" + valor);
    $.ajax({
      url: "../../php/funciones.php",
      data: {
        clase: "get_data",
        funcion: "validar_razonSocial_Cliente",
        data: valor,
      },
      dataType: "json",
      success: function (data) {
        console.log("respuesta nombre valida: ", data);
        /* Validar si ya existe el identificador con ese nombre*/
        if(parseInt(data[0]["existe"]) == 1){
          $("#invalid-razon").css("display", "block");
          $("#invalid-razon").text(
            "La razón social ya esta registrada en el sistema."
          );
          $("#txtRazonSocial").addClass("is-invalid");
          console.log("¡Ya existe!");
        }else if(!valor){
          $("#invalid-razon").css("display", "block");
          $("#invalid-razon").text(
            "El cliente debe tener una razón social."
          );
          $("#txtRazonSocial").addClass("is-invalid");
        }else{
          $("#invalid-razon").css("display", "none");
          $("#invalid-razon").text(
            "El cliente debe tener una razón social."
          );
          $("#txtRazonSocial").removeClass("is-invalid");
          console.log("¡No existe!");
        }
      },
    });
  }

  var razonSocial = $("#txtRazonSocial").val().toLowerCase();
  if(razonSocial.endsWith(' s.a. de c.v.') || razonSocial.endsWith(' sa de cv') || razonSocial.endsWith(' s.a.') || razonSocial.endsWith(' sa') || razonSocial.endsWith(' sociedad anónima') || razonSocial.endsWith(' sociedad anonima') || razonSocial.endsWith(' s. de r.l.') || razonSocial.endsWith(' s de rl') || razonSocial.endsWith(' sociedad de responsabilidad limitada') || razonSocial.endsWith(' s. en c') || razonSocial.endsWith(' s en c') || razonSocial.endsWith(' sociedad en comandita') || razonSocial.endsWith(' socidad civil')){
    $("#txtRazonSocial").addClass("is-invalid");
    $("#invalid-razonTipoSociedad").css("display", "block");
  }else{
    $("#txtRazonSocial").removeClass("is-invalid");
    $("#invalid-razonTipoSociedad").css("display", "none");
  }
}

/*----------------------Cambio de seleccion de pais-------------------------------*/
function cambioPais() {
  var PKPais = $("#cmbPais").val();
  cargarCMBEstados(PKPais, "cmbEstado");
  $("#cmbPais").addClass("is-invalid");
  $("#invalid-paisFisc").css("display", "block");
  $("#invalid-paisFisc").text("La información fiscal debe tener un pais.");
  if (PKPais) {
    $("#cmbPais").removeClass("is-invalid");
    $("#invalid-paisFisc").css("display", "none");
    $("#invalid-paisFisc").text("La información fiscal debe tener un pais.");
  }
}

$(document).on("change", "#cmbEstado", function () {
  $("#invalid-paisEstadoFisc").css("display", "block");
  $("#cmbEstado").addClass("is-invalid");
  if ($("#cmbEstado").val()) {
    $("#invalid-paisEstadoFisc").css("display", "none");
    $("#cmbEstado").removeClass("is-invalid");
    $("#invalid-paisEstadoFisc").text(
      "La información fiscal debe tener un estado."
    );
  }
});

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

function obtenerCP(item) {
  var colonia = $(item).val().normalize("NFD").replace(/[\u0300-\u036f]/g, "");
  console.log(colonia);
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_cp",
      data: colonia
    },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta de obtener CP:", respuesta);

      if (respuesta != false) {
        $("#txtCP").val(respuesta.codigo_postal);
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function SeguirContacto(id) {
  validarEmpresaCliente(id);
  resetTabs("#CargarDatosContacto");

  var html =
    `<div class="card shadow mb-4">
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-12">
                      <form id="formDatosContacto"> 
                        <input type='hidden' value='${id}' name="txtPKCliente" id="txtPKCliente">

                        <div class="form-group">
                          <div class="row">
                            <div class="col-lg-4">
                              <label for="usr">Nombre(s) del contacto:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control" type="text" name="txtNombreContacto" id="txtNombreContacto" onkeyup="validEmptyInput('txtNombreContacto', 'invalid-nombreCont', 'El contacto debe tener un nombre.')" autofocus="" required="" maxlength="50" placeholder="Ej. José María">
                                  <div class="invalid-feedback" id="invalid-nombreCont">El contacto debe tener un nombre.</div>
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <label for="usr">Apellido(s) del contacto:</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input type="text" maxlength="50" class="form-control" name="txtApellidoContacto" id="txtApellidoContacto" placeholder="Ej. López Pérez">
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
                                  <input class="form-control numeric-only" type="text" name="txtTelefono" id="txtTelefono" maxlength="10" placeholder="Ej. 33 3333 3333">
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <label for="usr">Celular:</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control numeric-only" type="text" name="txtCelular" id="txtCelular" maxlength="10" placeholder="Ej. 33 3333 3333">
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <label for="usr">E-mail:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control" type="email" name="txtEmail" id="txtEmail" autofocus="" required="" maxlength="100" placeholder="Ej. ejemplo@dominio.com" onkeyup="validarCorreoContacto(this.value)">
                                  <div class="invalid-feedback" id="invalid-emailCont">El contacto debe tener un email.</div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="form-group">
                          <div class="row">
                            <div class="col-lg-3">
                              <label for="usr">Correos automáticos de:</label>
                            </div>
                            <div class="col-lg-9">
                              
                            </div>
                            <div class="col-lg-3">
                              <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="cbxFacturacion" name="cbxFacturacion">
                                <label class="form-check-label" for="flexSwitchCheckDefault">Facturación</label>
                              </div>

                              <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="cbxComplementoPago" name="cbxComplementoPago">
                                <label class="form-check-label" for="flexSwitchCheckDefault">Complemento de pago</label>
                              </div>

                              <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="cbxAvisosEnvio" name="cbxAvisosEnvio">
                                <label class="form-check-label" for="flexSwitchCheckDefault">Avisos de envío</label>
                              </div>

                              <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="cbxPagos" name="cbxPagos">
                                <label class="form-check-label" for="flexSwitchCheckDefault">Pagos</label>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="form-group">
                          <div class="row">
                            <div class="col-lg-9">
                            </div>
                            <div class="col-lg-12 d-flex justify-content-end">
                              <a class="btn-custom btn-custom--blue mr-4" id="btnAnadirContacto" onclick="validarContacto(${id})">Guardar Contacto</a>
                              <a class="btn-custom btn-custom--border-blue" id="btnAgregarContacto" onclick="guardarDatosContacto(${id})">Continuar</a>
                            </div>
                          </div>
                        </div>
                        <label for="">* Campos requeridos</label>

                        <div class="form-group">
                          <!-- DataTales Example -->
                          <div class="card mb-4 internal-table">
                            <div class="card-body">
                              <div class="table-responsive">
                                <table class="table" id="tblListadoDatosContactoCliente" width="100%" cellspacing="0">
                                  <thead>
                                    <tr>
                                      <th>Id</th>
                                      <th>Nombre(s)</th>
                                      <th>Apellido(s)</th>
                                      <th>Puesto</th>
                                      <th>Teléfono fijo</th>
                                      <th>Celular</th>
                                      <th>Recibir correos automáticos de</th>
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

  cargarTablaContactos(id,1);
  resetValidations();
}

function validarCorreoContacto(value) {
  console.log(value);
  var reg =
    /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

  var regOficial =
    /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/;

  //Se muestra un texto a modo de ejemplo, luego va a ser un icono
  if (reg.test(value) || regOficial.test(value)) {
    $("#invalid-emailCont").css("display", "none");
    $("#txtEmail").removeClass("is-invalid");
    $("#invalid-emailCont").text("El contacto debe tener un email.");
  } else {
    $("#invalid-emailCont").css("display", "block");
    $("#txtEmail").addClass("is-invalid");
    $("#invalid-emailCont").text("Debe ser un email valido.");
  }
}

function validarCorreoContactoModalEdit(value) {
  console.log(value);
  var reg =
    /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

  var regOficial =
    /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/;

  //Se muestra un texto a modo de ejemplo, luego va a ser un icono
  if (reg.test(value) || regOficial.test(value)) {
    $("#invalid-emailContEdit").css("display", "none");
    $("#txtEmailEdit").removeClass("is-invalid");
    $("#invalid-emailContEdit").text("El contacto debe tener un email.");
  } else {
    $("#invalid-emailContEdit").css("display", "block");
    $("#txtEmailEdit").addClass("is-invalid");
    $("#invalid-emailContEdit").text("Debe ser un email valido.");
  }
}

function validarContacto(id) {
  console.log("id:", id);
  var nombre = $("#txtNombreContacto").val();
  var apellido = $("#txtApellidoContacto").val();
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "validar_contacto_cliente",
      data: nombre,
      data2: apellido,
      data5: id,
    },
    dataType: "json",
    success: function (data) {
      console.log("respuesta estado validado: ", data);
      /* Validar si ya existe el identificador con ese nombre*/
      if (parseInt(data[0]["existe"]) == 1) {
        $("#invalid-nombreCont").css("display", "block");
        $("#txtEmail").addClass("is-invalid");
        $("#invalid-nombreCont").text("El nombre y el apellido ya esta registrado.");
      } else {
        $("#invalid-nombreCont").css("display", "none");
        $("#txtEmail").removeClass("is-invalid");
        $("#invalid-nombreCont").text("El contacto debe tener un nombre y el apellido.");
        anadirContacto(id);
      }
    },
  });
}

function validarContactoEdit(idCliente, idContacto) {
  if ($("#formDatosContactoEdit")[0].checkValidity()) {
    var badNombreCont =
      $("#invalid-nombreContEdit").css("display") === "block" ? false : true;
    var badEmailCont =
      $("#invalid-emailContEdit").css("display") === "block" ? false : true;
    if (badNombreCont && badEmailCont) {
      var nombre = $("#txtNombreContactoEdit").val();
      var nombreOld = $("#nombre-old").val();
      var apellido = $("#txtApellidoContactoEdit").val();
      var apellidoOld = $("#apellido-old").val();
      if ((nombre + apellido) === (nombreOld + apellidoOld)) {
        editarContacto(idContacto);
      } else {
        $.ajax({
          url: "../../php/funciones.php",
          data: {
            clase: "get_data",
            funcion: "validar_contacto_cliente",
            data: nombre,
            data2: apellido,
            data5: idCliente,
          },
          dataType: "json",
          success: function (data) {
            console.log("respuesta estado validado: ", data);
            /* Validar si ya existe el identificador con ese nombre*/
            if (parseInt(data[0]["existe"]) == 1) {
              $("#invalid-nombreContEdit").css("display", "block");
              $("#txtNombreContactoEdit").addClass("is-invalid");
              $("#invalid-nombreContEdit").text("El nombre y el apellido ya esta registrado.");
            } else {
              $("#invalid-nombreContEdit").css("display", "none");
              $("#txtNombreContactoEdit").removeClass("is-invalid");
              $("#invalid-nombreContEdit").text("El contacto debe tener un nombre y el apellido.");
              editarContacto(idContacto);
            }
          },
        });
      }
    }
  } else {
    console.log("Faltan campos");
    if (!$("#txtNombreContactoEdit").val()) {
      $("#invalid-nombreContEdit").css("display", "block");
      $("#txtNombreContactoEdit").addClass("is-invalid");
    }
    if (!$("#txtEmailEdit").val()) {
      $("#invalid-emailContEdit").css("display", "block");
      $("#txtEmailEdit").addClass("is-invalid");
    }
  }
}

//Validar que se hayan completado todos los campos antes de paar a la siguiente pestaña
function anadirContacto(id) {
  if ($("#formDatosContacto")[0].checkValidity()) {
    agregarContacto(id);
  } else {
    console.log("Faltan campos");
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

    var pkContacto = $("#txtPKContacto").val();

    var isFacturacion = "0";
    var isComplementoPago = "0";
    var isAvisosEnvio = "0";
    var isPagos = "0";

    if ($("#cbxFacturacion").is(":checked")) {
      isFacturacion = "1";
    } else {
      isFacturacion = "0";
    }

    if ($("#cbxComplementoPago").is(":checked")) {
      isComplementoPago = "1";
    } else {
      isComplementoPago = "0";
    }

    if ($("#cbxAvisosEnvio").is(":checked")) {
      isAvisosEnvio = "1";
    } else {
      isAvisosEnvio = "0";
    }

    if ($("#cbxPagos").is(":checked")) {
      isPagos = "1";
    } else {
      isPagos = "0";
    }

    $.ajax({
      url: "../../php/funciones.php",
      data: {
        clase: "save_data",
        funcion: "save_contactoCliente",
        datos: nombreContacto,
        datos2: apellidoContacto,
        datos3: puesto,
        datos4: telefonoFijo,
        datos5: celular,
        datos6: email,
        datos7: id,
        datos8: pkContacto,
        datos9: isFacturacion,
        datos10: isComplementoPago,
        datos11: isAvisosEnvio,
        datos12: isPagos,
      },
      dataType: "json",
      success: function (respuesta) {
        console.log("respuesta agregar datos contacto del cliente:", respuesta);

        if (respuesta[0].status) {
          $("#tblListadoDatosContactoCliente").DataTable().ajax.reload();
          $("#txtNombreContacto").val("");
          $("#txtApellidoContacto").val("");
          $("#txtPuesto").val("");
          $("#txtTelefono").val("");
          $("#txtCelular").val("");
          $("#txtEmail").val("");
          $("#txtEdicion").val("0");
          $("#txtPKContacto").val("0");

          $("#cbxFacturacion").prop("checked", false);
          $("#cbxComplementoPago").prop("checked", false);
          $("#cbxAvisosEnvio").prop("checked", false);
          $("#cbxPagos").prop("checked", false);

          $("#notaFNombreContacto").css("display", "none");
          $("#notaFApellidoContacto").css("display", "none");
          $("#notaFPuesto").css("display", "none");
          $("#notaFEmail").css("display", "none");
          Lobibox.notify("success", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../../../img/timdesk/checkmark.svg",
            msg: "¡Contacto registrado correctamente!",
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
            msg: "¡No se guardó el contacto con éxito :(!",
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

$("#txtNombreContacto").on("keyup", function () {
  console.log("cambio");
});

function editarContacto(id) {
  var nombreContacto = $("#txtNombreContactoEdit").val();
  var apellidoContacto = $("#txtApellidoContactoEdit").val();
  var puesto = $("#txtPuestoEdit").val();
  var telefonoFijo = $("#txtTelefonoEdit").val();
  var celular = $("#txtCelularEdit").val();
  var email = $("#txtEmailEdit").val();

  var pkContacto = $("#txtPKContacto").val();

  var isFacturacion = "0";
  var isComplementoPago = "0";
  var isAvisosEnvio = "0";
  var isPagos = "0";

  if ($("#cbxFacturacionEdit").is(":checked")) {
    isFacturacion = "1";
  } else {
    isFacturacion = "0";
  }

  if ($("#cbxComplementoPagoEdit").is(":checked")) {
    isComplementoPago = "1";
  } else {
    isComplementoPago = "0";
  }

  if ($("#cbxAvisosEnvioEdit").is(":checked")) {
    isAvisosEnvio = "1";
  } else {
    isAvisosEnvio = "0";
  }

  if ($("#cbxPagosEdit").is(":checked")) {
    isPagos = "1";
  } else {
    isPagos = "0";
  }

  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "save_data",
      funcion: "save_contactoCliente",
      datos: nombreContacto,
      datos2: apellidoContacto,
      datos3: puesto,
      datos4: telefonoFijo,
      datos5: celular,
      datos6: email,
      datos7: id,
      datos8: pkContacto,
      datos9: isFacturacion,
      datos10: isComplementoPago,
      datos11: isAvisosEnvio,
      datos12: isPagos,
    },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta agregar datos contacto del cliente:", respuesta);

      if (respuesta[0].status) {
        $("#tblListadoDatosContactoCliente").DataTable().ajax.reload();
        $("#txtPKContacto").val("0");
        $("#editar_Contacto").modal("hide");
        Lobibox.notify("success", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/checkmark.svg",
          msg: "¡Se guardó el contacto con éxito!",
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
        msg: "¡No se guardó el contacto con éxito :(!",
        sound: '../../../../../sounds/sound4'
      });
    },
  });
}

function obtenerIdContactoClienteEliminar(id) {
  console.log("ID del contacto : " + id);
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "delete_data",
      funcion: "delete_contacto_Cliente",
      datos: id,
    },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta eliminar contacto:", respuesta);

      if (respuesta[0].status) {
        $("#tblListadoDatosContactoCliente").DataTable().ajax.reload();
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

function obtenerIdContactoClienteEditar(id) {
  console.log("ID de la cliente : " + id);
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_datos_contacto_cliente",
      datos: id,
    },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta datos del contacto del cliente", respuesta);

      $("#txtNombreContactoEdit").val(respuesta[0].Nombres);
      $("#txtApellidoContactoEdit").val(respuesta[0].Apellidos);
      $("#txtPuestoEdit").val(respuesta[0].Puesto);
      $("#txtTelefonoEdit").val(respuesta[0].Telefono);
      $("#txtCelularEdit").val(respuesta[0].Celular);
      $("#txtEmailEdit").val(respuesta[0].Email);
      $("#email-old").val(respuesta[0].Email);
      $("#nombre-old").val(respuesta[0].Nombres);
      $("#apellido-old").val(respuesta[0].Apellidos);

      $("#txtNombreDContacto").val(
        respuesta[0].Nombres + " " + respuesta[0].Apellidos
      );

      if (respuesta[0].isFacturacion == "1") {
        $("#cbxFacturacionEdit").prop("checked", true);
      } else {
        $("#cbxFacturacionEdit").prop("checked", false);
      }

      if (respuesta[0].isComplementoPago == "1") {
        $("#cbxComplementoPagoEdit").prop("checked", true);
      } else {
        $("#cbxComplementoPagoEdit").prop("checked", false);
      }

      if (respuesta[0].isAvisosEnvio == "1") {
        $("#cbxAvisosEnvioEdit").prop("checked", true);
      } else {
        $("#cbxAvisosEnvioEdit").prop("checked", false);
      }

      if (respuesta[0].isPagos == "1") {
        $("#cbxPagosEdit").prop("checked", true);
      } else {
        $("#cbxPagosEdit").prop("checked", false);
      }

      $("#txtEdicion").val("1");
      $("#txtPKContacto").val(respuesta[0].PKContactoCliente);
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
  validarEmpresaCliente(id);
  resetTabs("#CargarDatosCuentasBancarias");
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
                        <input type='hidden' value='${id}' name="txtPKProductoInventario" id="txtPKProductoInventario">

                        <div class="form-group">
                          <div class="row">
                            <div class="col-lg-3">
                              <label for="usr">Banco:*</label>
                              <div class="col-lg-12 input-group">
                                <select name="cmbBanco" id="cmbBanco" required>
                                </select>
                                <div class="invalid-feedback" id="invalid-nombreBanco">La cuenta debe tener un banco.</div>
                              </div>
                            </div>
                            <div class="col-lg-3">
                              <label for="usr">No. de cuenta:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control numeric-only" type="text" minlength="10" maxlength="20" name="txtNoCuenta" id="txtNoCuenta" min="0" placeholder="Ej. 0000000000" onkeyup="validarNoCuenta()">
                                  <div class="invalid-feedback" id="invalid-noCuenta">La cuenta debe tener un número.</div>
                                </div>
                              </div>  
                            </div>
                            <div class="col-lg-3">
                              <label for="usr">CLABE:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control numeric-only" type="text" name="txtCLABE" id="txtCLABE" min="0" minlength="18" maxlength="18" autofocus="" placeholder="Ej. 000 000 0000000000 0" onkeyup="validarCLABE()">
                                  <div class="invalid-feedback" id="invalid-claveCuenta">La cuenta debe tener una clabe.</div>
                                </div>
                              </div>      
                            </div>
                            <div class="col-lg-3">
                              <label for="usr">Moneda:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <select name="cmbCostoUniVentaEspecial" id="cmbCostoUniVentaEspecial" required="">
                                  </select>
                                  <div class="invalid-feedback" id="invalid-monedaCuenta">La cuenta debe tener un tipo de moneda.</div>
                                </div>
                              </div> 
                            </div>
                          </div>
                        </div>
                        <label for="">* Campos requeridos</label>
                        <div class="form-group">
                          <div class="row">
                            <div class="col-lg-9">
                            </div>
                            <div class="col-lg-12 d-flex justify-content-end">
                              <a class="btn-custom btn-custom--blue mr-4" id="btnAnadirContacto" onclick="validarBanco(${id})">Guardar Cuenta Bancaria</a>
                              <a class="btn-custom btn-custom--border-blue" id="btnAgregarCuentasBancarias" onclick="guardarDatosBancarios(${id})">Continuar</a>
                            </div>
                          </div>
                        </div>
                        <br>

                        <div class="form-group">
                          <!-- DataTales Example -->
                          <div class="card mb-4 internal-table">
                            <div class="card-body">
                              <div class="table-responsive">
                                <table class="table" id="tblListadoDatosBancoCliente" width="100%" cellspacing="0">
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
  cargarTablaBancos(id,1);
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
        /* if (data === respuesta[i].PKBanco) {
          selected = "selected";
        } else {
          selected = "";
        } */

        html +=
          '<option value="' +
          respuesta[i].PKBanco +
          '" ' +
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

      html += '<option value="0">Seleccione un banco...</option>';

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

      html += '<option value="0">Seleccione un banco...</option>';

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

function cargarCMBCostoUniVentaEspEditModal(data, input) {
  var html = "";
  var selected;
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_costouni_ventaEsp" },
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

      CargarSlimCostoUniCompraEditModal();
      $("#" + input + "").html(html);
      $("#cmbCostoUniVentaEspecialEdit").val("100");
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

/*----------------------Plugin Slim para la búsqueda dentro del-------------------------------*/
function CargarSlimBanco() {
  new SlimSelect({
    select: "#cmbBanco",
    placeholder: "Seleciona un banco",
    allowDeselect: true,
    deselectLabel: '<span class="">✖</span>',
  });
}

function CargarSlimBancoEditModal() {
  new SlimSelect({
    select: "#cmbBancoEdit",
    deselectLabel: '<span class="">✖</span>',
  });
}

function CargarSlimCostoUniCompraEditModal() {
  new SlimSelect({
    select: "#cmbCostoUniVentaEspecialEdit",
    deselectLabel: '<span class="">✖</span>',
  });
}

function CargarSlimRegimenInfoFiscal() {
  new SlimSelect({
    select: "#cmbRegimenInfoFiscal",
    deselectLabel: '<span class="">✖</span>',
  });
}

function validarNoCuenta() {
  var noCuenta = $("#txtNoCuenta").val();

  console.log("Valor de validación:" + validaCCC(noCuenta));
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
          $("#txtNoCuenta").addClass("is-invalid");
          $("#invalid-noCuenta").css("display", "block");
          $("#invalid-noCuenta").text(
            "El número de cuenta ya existe en el sistema."
          );
          console.log("¡Ya existe!");
        } else {
          $("#txtNoCuenta").removeClass("is-invalid");
          $("#invalid-noCuenta").css("display", "none");
          $("#invalid-noCuenta").text("La cuenta debe tener un número.");
          if (!noCuenta) {
            $("#txtNoCuenta").addClass("is-invalid");
            $("#invalid-noCuenta").css("display", "block");
            $("#invalid-noCuenta").text("La cuenta debe tener un número.");
          }
          console.log("¡No existe!");
        }
      },
    });
  } else {
    $("#txtNoCuenta").addClass("is-invalid");
    $("#invalid-noCuenta").css("display", "block");
    $("#invalid-noCuenta").text("El número de cuenta no es valido.");
    console.log("¡No válida!");
  }
  $("#invalid-claveCuenta").css("display", "none");
  $("#txtCLABE").removeClass("is-invalid");
}

function validarNoCuentaEditModal() {
  var noCuenta = $("#txtNoCuentaEdit").val();
  var cuentaOld = $("#cuenta-old").val();
  if (noCuenta !== cuentaOld) {
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
            $("#txtNoCuentaEdit").addClass("is-invalid");
            $("#invalid-noCuentaEdit").css("display", "block");
            $("#invalid-noCuentaEdit").text(
              "El número de cuenta ya existe en el sistema."
            );
            console.log("¡Ya existe!");
          } else {
            $("#txtNoCuentaEdit").removeClass("is-invalid");
            $("#invalid-noCuentaEdit").css("display", "none");
            $("#invalid-noCuentaEdit").text("La cuenta debe tener un número.");
            if (!noCuenta) {
              $("#txtNoCuentaEdit").addClass("is-invalid");
              $("#invalid-noCuentaEdit").css("display", "block");
              $("#invalid-noCuentaEdit").text(
                "La cuenta debe tener un número."
              );
            }
            console.log("¡No existe!");
          }
        },
      });
    } else {
      $("#txtNoCuentaEdit").addClass("is-invalid");
      $("#invalid-noCuentaEdit").css("display", "block");
      $("#invalid-noCuentaEdit").text("El número de cuenta no es valido.");
      console.log("¡No válida!");
    }
  } else {
    $("#txtNoCuentaEdit").removeClass("is-invalid");
    $("#invalid-noCuentaEdit").css("display", "none");
    $("#invalid-noCuentaEdit").text("La cuenta debe tener un número.");
  }
  $("#invalid-claveCuentaEdit").css("display", "none");
  $("#txtCLABEEdit").removeClass("is-invalid");
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

  console.log("Valor de validación:" + validaBBB(clabe));
  if (validaBBB(clabe)) {
    $.ajax({
      url: "../../php/funciones.php",
      data: { clase: "get_data", funcion: "validar_CLABE", data: clabe },
      dataType: "json",
      success: function (data) {
        console.log("respuesta estado validado: ", data);
        /* Validar si ya existe el identificador con ese nombre*/
        if (parseInt(data[0]["existe"]) == 1) {
          $("#invalid-claveCuenta").text("La clave ya existe en el sistema.");
          $("#invalid-claveCuenta").css("display", "block");
          $("#txtCLABE").addClass("is-invalid");
          console.log("¡Ya existe!");
        } else {
          $("#invalid-claveCuenta").text("La cuenta debe tener una clabe.");
          $("#invalid-claveCuenta").css("display", "none");
          $("#txtCLABE").removeClass("is-invalid");
          if (!clabe) {
            $("#invalid-claveCuenta").text("La cuenta debe tener una clabe.");
            $("#invalid-claveCuenta").css("display", "block");
            $("#txtCLABE").addClass("is-invalid");
          }
          console.log("¡No existe!");
        }
      },
    });
  } else {
    $("#invalid-claveCuenta").text("La cuenta debe ser valida.");
    $("#invalid-claveCuenta").css("display", "block");
    $("#txtCLABE").addClass("is-invalid");
    console.log("¡No válida!");
  }
  $("#txtNoCuenta").removeClass("is-invalid");
  $("#invalid-noCuenta").css("display", "none");
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
            console.log("¡Ya existe!");
            $("#invalid-claveCuentaEdit").text(
              "La clave ya existe en el sistema."
            );
            $("#invalid-claveCuentaEdit").css("display", "block");
            $("#txtCLABEEdit").addClass("is-invalid");
          } else {
            $("#invalid-claveCuentaEdit").text(
              "La cuenta debe tener una clabe."
            );
            $("#invalid-claveCuentaEdit").css("display", "none");
            $("#txtCLABEEdit").removeClass("is-invalid");
            if (!clabe) {
              $("#invalid-claveCuentaEdit").text(
                "La cuenta debe tener una clabe."
              );
              $("#invalid-claveCuentaEdit").css("display", "block");
              $("#txtCLABEEdit").addClass("is-invalid");
            }
            console.log("¡No existe!");
          }
        },
      });
    } else {
      $("#invalid-claveCuentaEdit").text("La cuenta debe ser valida.");
      $("#invalid-claveCuentaEdit").css("display", "block");
      $("#txtCLABEEdit").addClass("is-invalid");
      console.log("¡No válida!");
    }
  } else {
    $("#invalid-claveCuentaEdit").text("La cuenta debe tener una clabe.");
    $("#invalid-claveCuentaEdit").css("display", "none");
    $("#txtCLABEEdit").removeClass("is-invalid");
  }
  $("#txtNoCuentaEdit").removeClass("is-invalid");
  $("#invalid-noCuentaEdit").css("display", "none");
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
  if ($("#cmbBanco").val() == '' || $("#cmbBanco").val() == null){
    pkBanco = 0;
  }else{
    pkBanco = $("#cmbBanco").val();
  }
  var noCuenta = $("#txtNoCuenta").val();
  var clabe = $("#txtCLABE").val();
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "validar_datosBanarios_cliente",
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
        console.log("¡Ya existe!");
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/notificacion_error.svg",
          msg: "¡Los datos ingresados ya existen en el sistema!",
          sound: '../../../../../sounds/sound4'
        });
      } else {
        agregarBanco(id);
        console.log("¡No existe!");
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function validarBancoEdit(id) {
  if ($("#formDatosProveedorEdit")[0].checkValidity()) {
    console.log("Valido");
    var badBanco =
      $("#invalid-nombreBancoEdit").css("display") === "block" ? false : true;
    var badNoCuenta =
      $("#invalid-noCuentaEdit").css("display") === "block" ? false : true;
    var badClave =
      $("#invalid-claveCuentaEdit").css("display") === "block" ? false : true;
    var badMoneda =
      $("#invalid-monedaCuentaEdir").css("display") === "block" ? false : true;
    if (badBanco && badNoCuenta && badClave && badMoneda) {
      console.log("No hay invalids");
      var pkBanco = $("#cmbBanco").val();
      var noCuenta = $("#txtNoCuentaEdit").val();
      var noCuentaOld = $("#cuenta-old").val();
      var clabe = $("#txtCLABEEdit").val();
      var clabeOld = $("#clave-old").val();
      if (noCuenta != noCuentaOld || clabe != clabeOld) {
        $.ajax({
          url: "../../php/funciones.php",
          data: {
            clase: "get_data",
            funcion: "validar_datosBanarios_cliente",
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
              console.log("¡Ya existe!");
              Lobibox.notify("error", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../../../img/timdesk/notificacion_error.svg",
                msg: "¡Los datos ingresados ya existen en el sistema!",
                sound: '../../../../../sounds/sound4'
              });
            } else {
              editarBanco(id);
              console.log("¡No existe!");
            }
          },
        });
      } else {
        editarBanco(id);
      }
    }
  } else {
    console.log("No valido");
    if (!$("#cmbBancoEdit").val()) {
      $("#invalid-nombreBancoEdit").css("display", "block");
      $("#cmbBancoEdit").addClass("is-invalid");
    }
    if (!$("#txtNoCuentaEdit").val() && !$("#txtCLABEEdit").val()) {
      $("#invalid-noCuentaEdit").css("display", "block");
      $("#txtNoCuentaEdit").addClass("is-invalid");
      $("#invalid-claveCuentaEdit").css("display", "block");
      $("#txtCLABEEdit").addClass("is-invalid");
    }
    /*if (!$("#txtCLABEEdit").val()) {
      $("#invalid-claveCuentaEdit").css("display", "block");
      $("#txtCLABEEdit").addClass("is-invalid");
    }*/
    if (!$("#cmbCostoUniVentaEspecialEdit").val()) {
      $("#invalid-monedaCuentaEdir").css("display", "block");
      $("#cmbCostoUniVentaEspecialEdit").addClass("is-invalid");
    }
  }
}

$(document).on("change", "#cmbBanco", function () {
  $("#invalid-nombreBanco").css("display", "block");
  $("#cmbBanco").addClass("is-invalid");
  if ($("#cmbBanco").val()) {
    $("#invalid-nombreBanco").css("display", "none");
    $("#cmbBanco").removeClass("is-invalid");
  }
});

$(document).on("change", "#cmbCostoUniVentaEspecial", function () {
  $("#invalid-monedaCuenta").css("display", "block");
  $("#cmbCostoUniVentaEspecial").addClass("is-invalid");
  if ($("#cmbCostoUniVentaEspecial").val()) {
    $("#invalid-monedaCuenta").css("display", "none");
    $("#cmbCostoUniVentaEspecial").removeClass("is-invalid");
  }
});

function agregarBanco(id) {
  if ($("#formDatosProveedor")[0].checkValidity()) {
    var badBanco =
      $("#invalid-nombreBanco").css("display") === "block" ? false : true;
    var badNoCuenta =
      $("#invalid-noCuenta").css("display") === "block" ? false : true;
    var badClave =
      $("#invalid-claveCuenta").css("display") === "block" ? false : true;
    var badMoneda =
      $("#invalid-monedaCuenta").css("display") === "block" ? false : true;
    if (badBanco && badNoCuenta && badClave && badMoneda) {
      var pkBanco = $("#cmbBanco").val();
      var noCuenta = $("#txtNoCuenta").val();
      var clabe = $("#txtCLABE").val();
      var pkCuentaBancaria = $("#txtPKCuentaBancaria").val();
      var moneda = $("#cmbCostoUniVentaEspecial").val();

      $.ajax({
        url: "../../php/funciones.php",
        data: {
          clase: "save_data",
          funcion: "save_bancoCliente",
          datos: pkBanco,
          datos2: noCuenta,
          datos3: clabe,
          datos4: id,
          datos5: pkCuentaBancaria,
          datos6: moneda,
        },
        dataType: "json",
        success: function (respuesta) {
          console.log("respuesta agregar datos banco del cliente:", respuesta);

          if (respuesta[0].status) {
            $("#tblListadoDatosBancoCliente").DataTable().ajax.reload();
            cargarCMBCostoUniVentaEspEC("", "cmbCostoUniVentaEspecial", 100);
            cargarCMBBancoEdit("", "cmbBanco", "0");
            $("#txtPKCuentaBancaria").val("0");
            Lobibox.notify("success", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../../img/timdesk/checkmark.svg",
              msg: "¡Cuenta bancaria registrada correctamente!",
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
    if (!$("#cmbBanco").val()) {
      $("#invalid-nombreBanco").css("display", "block");
      $("#cmbBanco").addClass("is-invalid");
    }
    if (!$("#txtNoCuenta").val() && !$("#txtCLABE").val()) {
      $("#invalid-noCuenta").css("display", "block");
      $("#txtNoCuenta").addClass("is-invalid");
      $("#invalid-claveCuenta").css("display", "block");
      $("#txtCLABE").addClass("is-invalid");
    }
    /*if (!$("#txtCLABE").val()) {
      $("#invalid-claveCuenta").css("display", "block");
      $("#txtCLABE").addClass("is-invalid");
    }*/
    if (!$("#cmbCostoUniVentaEspecial").val()) {
      $("#invalid-monedaCuenta").css("display", "block");
      $("#cmbCostoUniVentaEspecial").addClass("is-invalid");
    }
  }
}

function editarBanco(id) {
  console.log("Editando");
  if ($("#formDatosProveedorEdit")[0].checkValidity()) {
    var pkBanco = $("#cmbBancoEdit").val();
    var noCuenta = $("#txtNoCuentaEdit").val();
    var clabe = $("#txtCLABEEdit").val();
    var pkCuentaBancaria = $("#txtPKCuentaBancaria").val();
    var moneda = $("#cmbCostoUniVentaEspecialEdit").val();

    $.ajax({
      url: "../../php/funciones.php",
      data: {
        clase: "save_data",
        funcion: "save_bancoCliente",
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
          $("#tblListadoDatosBancoCliente").DataTable().ajax.reload();
          cargarCMBBancoEditModalEdit("", "cmbBancoEdit", "0");
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
            msg: "¡Se guardaron los datos bancarios con éxito!",
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
  } else {
    console.log("Faltan campos");
    if (!$("#cmbBancoEdit").val()) {
      $("#invalid-nombreBancoEdit").css("display", "block");
      $("#cmbBancoEdit").addClass("is-invalid");
    }
    if (!$("#txtNoCuentaEdit").val()) {
      $("#invalid-noCuentaEdit").css("display", "block");
      $("#txtNoCuentaEdit").addClass("is-invalid");
    }
    if (!$("#txtCLABEEdit").val()) {
      $("#invalid-claveCuentaEdit").css("display", "block");
      $("#txtCLABEEdit").addClass("is-invalid");
    }
    if (!$("#cmbCostoUniVentaEspecialEdit").val()) {
      $("#invalid-monedaCuentaEdit").css("display", "block");
      $("#cmbCostoUniVentaEspecialEdit").addClass("is-invalid");
    }
  }
}

function obtenerIdBancoClienteEliminar(id) {
  console.log("ID de la cuenta bancaria : " + id);
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "delete_data",
      funcion: "delete_cuentaBancaria_Cliente",
      datos: id,
    },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta eliminar cuenta bancaria:", respuesta);

      if (respuesta[0].status) {
        $("#tblListadoDatosBancoCliente").DataTable().ajax.reload();
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

function obtenerIdBancoClienteEditar(id) {
  console.log("ID de la cliente : " + id);
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_datos_cuentaBancaria_cliente",
      datos: id,
    },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta datos del contacto del cliente", respuesta);

      cargarCMBBancoEditModalEdit("", "cmbBancoEdit", respuesta[0].FKBanco);

      $("#txtNoCuentaEdit").val(respuesta[0].NoCuenta);
      $("#cuenta-old").val(respuesta[0].NoCuenta);
      $("#txtCLABEEdit").val(respuesta[0].CLABEs);
      $("#clave-old").val(respuesta[0].CLABEs);

      cargarCMBCostoUniVentaEspECEditModal(
        "",
        "cmbCostoUniVentaEspecialEdit",
        respuesta[0].FKMoneda
      );

      $("#txtNombreDCuenta").val(respuesta[0].NoCuenta);

      $("#txtEdicion").val("1");
      $("#txtPKCuentaBancaria").val(respuesta[0].PKCuentaBancariaCliente);
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

//Validar que se hayan completado todos los campos antes de pasar a la siguiente pestaña
function guardarDatosBancarios(id) {
  SeguirListadoProductos(id);
}

function SeguirListadoProductos(id) {
  validarEmpresaCliente(id);
  resetTabs("#CargarListadoProductos");
  cargarCMBProductosCliente("", "cmbProductosCliente");
  cargarCMBCostoUniVenta("", "cmbCostoUniVenta");
  cargarCMBCostoUniVentaEsp("", "cmbCostoUniVentaEspecial");

  var html =
    `<div class="card shadow mb-4">
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-12">
                      <form id="formDatosVentaProducto"> 
                        <input type='hidden' value='${id}' name="txtPKProducto2" id="txtPKProducto2">
                        <div class="form-group">
                          <div class="row">
                            <div class="col-lg-3">
                              <label for="usr">Costo unitario general de venta:</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control numeric-only" type="number" name="txtCostoUniVenta" id="txtCostoUniVenta" autofocus="" required="" placeholder="0.00" style="float:left;" disabled="disabled">
                                  <span class="input-group-addon" style="width:100px">
                                    <select name="cmbCostoUniVenta" id="cmbCostoUniVenta" required="" disabled="disabled">
                                    </select> 
                                  </span>
                                  <img  id="notaCostoVenta" name="notaCostoVenta" style="display: none;"
                                  src="../../../../img/timdesk/alerta.svg" width=30px
                                  title="Campo requerido" readonly>
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
                              <label for="usr">Producto:*</label>
                              <select class="cmbSlim" name="cmbProductosCliente" id="cmbProductosCliente" required="" onchange="mostrarCostoGral(${id})">
                              </select>
                              <div class="invalid-feedback" id="invalid-nombreProd">Se debe seleccionar un producto.</div>
                            </div>
                            <div class="col-lg-4">
                              <label for="usr">Costo especial:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control numericDecimal-only" type="text" name="txtCostoEspecialVenta" id="txtCostoEspecialVenta" maxlength="13" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" required placeholder="Ej. 10.00" onkeyup="validEmptyInput('txtCostoEspecialVenta', 'invalid-costoProd', 'El producto debe tener un costo.')">
                                  <div class="invalid-feedback" id="invalid-costoProd">El producto debe tener un costo.</div>
                                  <span class="input-group-addon">
                                    <select name="cmbCostoUniVentaEspecial" id="cmbCostoUniVentaEspecial" required="">
                                    </select> 
                                    <div class="invalid-feedback" id="invalid-monedaProd">El producto debe tener una moneda.</div>
                                  </span>
                                </div>
                              </div> 
                            </div>
                            <div class="col-12 mt-3"> <label for="">* Campos requeridos</label> </div>
                            <div class="col-lg-12 d-flex justify-content-end">
                              <a class="btn-custom btn-custom--blue mr-4" id="btnAnadirCliente" onclick="validarClienteProducto(${id})">Añadir precio especial</a>
                              <a class="btn-custom btn-custom--border-blue" id="btnAgregarProductoCliente" onclick="guardarDatosListadoProductos(${id})">Continuar</a>
                            </div>
                          </div>
                        </div>
                        <br>

                        <div class="form-group">
                          <!-- DataTales Example -->
                          <div class="card mb-4 internal-table">
                            <div class="card-body">
                              <div class="table-responsive">
                                <table class="table" id="tblListadoProductosCliente" width="100%" cellspacing="0">
                                  <thead>
                                    <tr>
                                      <th>Id</th>
                                      <th>Producto</th>
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
  cargarTablaProductos(id,1);
  resetValidations();
}

/* Obtener Id y Nombre del producto a eliminar */
var slim_moneda_modalEdit;
$(document).ready(function() {
  slim_moneda_modalEdit = new SlimSelect({
    select: "#cmbMoneda_editAdd",
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
        funcion: "get_Costoproducto_Cliente",
        data: id
      },
      dataType: "json",
      success: function (respuesta) {
        $("#txtProducto").text(respuesta[0].producto);
        $("#txtCostoEspecialVenta_modalEdit").val(respuesta[0].CostoEspecial);
        slim_moneda_modalEdit.set(respuesta[0].FKTipoMoneda);
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

$(document).on("click", "#btnEditarCosto", function () {
  if ($("#EditarProductoCliente")[0].checkValidity()) {
    var badCosto =
      $("#invalid-costoProd_edit").css("display") === "block" ? false : true;
    var badMoneda =
      $("#invalid-moneda_edit").css("display") === "block" ? false : true;
    
    if (
      badCosto &&
      badMoneda 
    ) {
      
      let Costo = $("#txtCostoEspecialVenta_modalEdit").val();
      let moneda = $("#cmbMoneda_editAdd").val();
      let pkRegistro = $("#txthideidproduct").val();
      
      $.ajax({
        url: "../../php/funciones.php",
        data: {
          clase: "edit_data",
          funcion: "update_costo_cliente",
          datos: pkRegistro,
          datos2: Costo,
          datos3: moneda          
        },
        dataType: "json",
        success: function (respuesta) {
          if (respuesta[0].status) {
            $("#tblListadoProductosCliente").DataTable().ajax.reload();
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
    if (!$("#cmbMoneda_editAdd").val()) {
      $("#invalid-moneda_edit").css("display", "block");
      $("#cmbMoneda_editAdd").addClass("is-invalid");
    }
  }
});

/*----------------------Funciones de carga de datos de los combos-------------------------------*/
function cargarCMBProductosCliente(data, input) {
  var html = "";
  var selected;
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_producto_cliente" },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta productos del cliente: ", respuesta);

      html += '<option data-placeholder="true"></option>';

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

      CargarSlimProductos();

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

function cargarCMBCostoUniCambio(data, input, value) {
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
      $("#cmbCostoUniVenta").val(value);
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
        /* if (data === respuesta[i].PKTipoMoneda) {
          selected = "selected";
        } else {
          selected = "";
        } */

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
      $("#cmbCostoUniVentaEspecial").val();
    },
    error: function (error) {
      console.log(error);
    },
  });
}

/*----------------------Plugin Slim para la búsqueda dentro del-------------------------------*/
function CargarSlimProductos() {
  new SlimSelect({
    select: "#cmbProductosCliente",
    placeholder: "Seleciona un producto",
    allowDeselect: true,
    deselectLabel: '<span class="">✖</span>',
  });
}

function CargarSlimCostoUniVenta() {
  new SlimSelect({
    select: "#cmbCostoUniVenta",
    deselectLabel: '<span class="">✖</span>',
  });
}

function CargarSlimCostoUniCompra() {
  new SlimSelect({
    select: "#cmbCostoUniVentaEspecial",
    placeholder: "Seleciona una moneda",
    allowDeselect: true,
    deselectLabel: '<span class="">✖</span>',
  });
}

/*----------------------------Botón añadir tipo producto ---------------------------*/

function mostrarCostoGral(id) {
  var pkProducto = $("#cmbProductosCliente").val();
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_datos_costoGral_producto",
      datos: pkProducto,
    },
    dataType: "json",
    success: function (respuesta) {
      console.log(
        "respuesta datos del coosto general de venta del producto",
        respuesta
      );

      $("#txtCostoUniVenta").val(respuesta[0].CostoGeneral);

      cargarCMBCostoUniCambio(
        "",
        "cmbCostoUniVenta",
        respuesta[0].FKTipoMoneda
      );
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function validarClienteProducto(id) {
  if ($("#formDatosVentaProducto")[0].checkValidity()) {
    var valor = $("#cmbProductosCliente").val();
    console.log("Valor producto" + valor);
    $.ajax({
      url: "../../php/funciones.php",
      data: {
        clase: "get_data",
        funcion: "validar_producto_Cliente",
        data: valor,
        data2: id,
      },
      dataType: "json",
      success: function (data) {
        console.log("respuesta producto validado: ", data);
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
            msg: "¡El cliente ya cuenta con un costo especial registrado para este producto!",
            sound: '../../../../../sounds/sound4'
          });
          console.log("¡Ya existe!");
        } else {
          anadirProductoCliente(id);
          console.log("¡No existe!");
        }
      },
    });
  } else {
    console.log("Faltan datos");
    if (!$("#cmbProductosCliente").val()) {
      $("#invalid-nombreProd").css("display", "block");
      $("#cmbProductosCliente").addClass("is-invalid");
    }
    if (!$("#txtCostoEspecialVenta").val()) {
      $("#invalid-costoProd").css("display", "block");
      $("#txtCostoEspecialVenta").addClass("is-invalid");
    }
    if (!$("#cmbCostoUniVentaEspecial").val()) {
      $("#invalid-monedaProd").css("display", "block");
      $("#cmbCostoUniVentaEspecial").addClass("is-invalid");
    }
  }
}

$(document).on("change", "#cmbProductosCliente", function () {
  $("#invalid-nombreProd").css("display", "block");
  $("#cmbProductosCliente").addClass("is-invalid");
  if ($("#cmbProductosCliente").val()) {
    $("#invalid-nombreProd").css("display", "none");
    $("#cmbProductosCliente").removeClass("is-invalid");
  }
});

$(document).on("change", "#cmbCostoUniVentaEspecial", function () {
  $("#invalid-monedaProd").css("display", "block");
  $("#cmbCostoUniVentaEspecial").addClass("is-invalid");
  if ($("#cmbCostoUniVentaEspecial").val()) {
    $("#invalid-monedaProd").css("display", "none");
    $("#cmbCostoUniVentaEspecial").removeClass("is-invalid");
  }
});

/* Añadir el impuesto */
function anadirProductoCliente(id) {
  var badProducto =
    $("#invalid-nombreProd").css("display") === "block" ? false : true;
  var badCosto =
    $("#invalid-costoProd").css("display") === "block" ? false : true;
  var badMOnedaProd =
    $("#invalid-monedaProd").css("display") === "block" ? false : true;
  if (badProducto && badCosto && badMOnedaProd) {
    var pkProducto = $("#cmbProductosCliente").val();
    var costoEsp = $("#txtCostoEspecialVenta").val();
    var moneda = $("#cmbCostoUniVentaEspecial").val();

    $.ajax({
      url: "../../php/funciones.php",
      data: {
        clase: "save_data",
        funcion: "save_producto_Cliente",
        datos: pkProducto,
        datos2: costoEsp,
        datos3: moneda,
        datos4: id,
      },
      dataType: "json",
      success: function (respuesta) {
        console.log(
          "respuesta agregar producto con precio especial al cliente:",
          respuesta
        );

        if (respuesta[0].status) {
          $("#tblListadoProductosCliente").DataTable().ajax.reload();
          $("#notaClienteProducto").css("display", "none");
          $("#notaCostoVentaEspecial").css("display", "none");
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


/* Eliminar el cliente de producto*/
function eliminarProducto(producto) {
  console.log("ID del producto: " + producto);
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "delete_data",
      funcion: "delete_cliente_producto",
      datos: producto,
    },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta eliminar cliente:", respuesta);

      if (respuesta[0].status) {
        $("#tblListadoProductosCliente").DataTable().ajax.reload();
        Lobibox.notify("success", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/checkmark.svg",
          msg: "¡Se eliminó el producto con éxito!",
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

//Validar que se hayan completado todos los campos antes de pasar a la siguiente pestaña
function guardarDatosListadoProductos(id) {
  SeguirDireccionesEnvio(id);
}

function SeguirDireccionesEnvio(id) {
  validarEmpresaCliente(id);
  resetTabs("#CargarDireccionesEnvio");
  cargarCMBPaisesDir("241", "cmbPais");
  cargarCMBEstadosDir("241", "cmbEstado");
  cargarCMBPaisesDirModal("146", "cmbPaisEdit");
  cargarCMBEstadosDirModal("146", "cmbEstadoEdit");

  var html =
    `<div class="card shadow mb-4">
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-12">
                      <form id="formDatosFiscales"> 
                        <input type='hidden' value='` +
    id +
    `' name="txtPKCliente" id="txtPKCliente">
                        <span id="areaDiseno">
                        
                        <div class="form-group">
                          <div class="row">
                            <div class="col-lg-4">
                              <label for="usr">Sucursal:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control" type="text" name="txtSucursal" id="txtSucursal" autofocus="" required="" maxlength="255" placeholder="Ej. Nogales" onkeyup="escribirSucursal(` +
    id +
    `)">
                                  <div class="invalid-feedback" id="invalid-sucursal">La dirección debe tener un nombre de sucursal.</div>
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <label for="usr">Contacto:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control alphaNumeric-only" type="text" name="txtContacto" id="txtContacto" maxlength="255" required="" placeholder="Ej. José María Lopéz Pérez" onkeyup="validEmptyInput('txtContacto', 'invalid-contacto', 'La dirección debe tener un contacto.')" >
                                  <div class="invalid-feedback" id="invalid-contacto">La dirección debe tener un contacto.</div>
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <label for="usr">Teléfono:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control numeric-only" type="text" name="txtTelefono" id="txtTelefono" maxlength="10" required="" placeholder="Ej. 123 456 7890" onkeyup="validEmptyInput('txtTelefono', 'invalid-telefono', 'La dirección debe tener un teléfono.')">
                                  <div class="invalid-feedback" id="invalid-telefono">La dirección debe tener un teléfono.</div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="form-group">
                          <div class="row">
                            <div class="col-lg-4">
                              <label for="usr">E-mail*:</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control alphaNumeric-only" type="email" name="txtEmail" id="txtEmail" required maxlength="100" placeholder="Ej. ejemplo@dominio.com" onkeyup="validarCorreoDire(this.value)">
                                  <div class="invalid-feedback" id="invalid-emailDire">La dirección debe tener un email.</div>
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <label for="usr">Calle:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control" type="text" name="txtCalle" id="txtCalle" required maxlength="255" placeholder="Ej. Av. México" onkeyup="validEmptyInput('txtCalle', 'invalid-calleDire', 'La dirección debe tener una calle.')">
                                  <div class="invalid-feedback" id="invalid-calleDire">La dirección debe tener una calle.</div>
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-2">
                              <label for="usr">Número exterior:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control alphaNumeric-only" type="text" name="txtNumExt" id="txtNumExt" required maxlength="100" placeholder="Ej. 2353 A" onkeyup="validEmptyInput('txtNumExt', 'invalid-numExt', 'La dirección debe tener un número exterior.')">
                                  <div class="invalid-feedback" id="invalid-numExt">La dirección debe tener un número exterior.</div>
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-2">
                              <label for="usr">Número interior:</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control alphaNumeric-only" type="text" name="txtNumInt" id="txtNumInt" maxlength="10" placeholder="Ej. 524">
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="form-group">
                          <div class="row">
                            <div class="col-lg-3">
                              <label for="usr">Colonia:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control" type="text" name="txtColonia" id="txtColonia" required maxlength="255" placeholder="Ej. Los Agaves" onkeyup="validEmptyInput('txtColonia', 'invalid-colonia', 'La dirección debe tener una colonia.')">
                                  <div class="invalid-feedback" id="invalid-colonia">La dirección debe tener una colonia.</div>
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-3">
                              <label for="usr">Municipio:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control" type="text" name="txtMunicipio" id="txtMunicipio" required maxlength="255" placeholder="Ej. Guadalajara" onkeyup="validEmptyInput('txtMunicipio', 'invalid-municipioDire', 'La direccion debe tener un municipio.')">
                                  <div class="invalid-feedback" id="invalid-municipioDire">La direccion debe tener un municipio.</div>
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-2">
                              <label for="usr">País:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <select name="cmbPais" id="cmbPais" required onchange="cambioPaisDir()">
                                  </select>
                                  <div class="invalid-feedback" id="invalid-paisDire">La dirección debe tener un pais.</div>
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-2">
                              <label for="usr">Estado:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <select name="cmbEstado" id="cmbEstado" required>
                                  </select>
                                  <div class="invalid-feedback" id="invalid-estadoDire">La dirección debe tener un estado.</div>
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-2">
                              <label for="usr">Código Postal:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control numeric-only" type="text" name="txtCP" id="txtCP" required maxlength="5" placeholder="Ej. 52632" onkeyup="validarCPEdit()">
                                  <div class="invalid-feedback" id="invalid-cpDire">La dirección debe tener un CP.</div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <label for="">* Campos requeridos</label>
                        <div class="form-group">
                          <div class="row">
                            <div class="col-lg-9">
                            </div>
                            <div class="col-lg-12 d-flex justify-content-end">
                              <a class="btn-custom btn-custom--blue mr-4" id="btnAnadirProveedor" onclick="anadirRazonSocialDir(${id})">Guardar Dirección de envío</a>  
                              <a class="btn-custom btn-custom--border-blue" id="btnAgregarImpuesto" onclick="guardarDireccionesEnvio(${id})">Volver al listado de clientes</a>
                            </div>
                          </div>
                        </div>

                        <div class="form-group">
                          <!-- DataTales Example -->
                          <div class="card mb-4 internal-table">
                            <div class="card-body">
                              <div class="table-responsive">
                                <table class="table" id="tblListadoDatosDireccionesEnvioProveedor" width="100%" cellspacing="0">
                                  <thead>
                                    <tr>
                                      <th>Id</th>
                                      <th>Pred.</th>
                                      <th>Sucursal</th>
                                      <th>E-mail</th>
                                      <th>Calle</th>
                                      <th>Número Exterior</th>
                                      <th>Número Interior</th>
                                      <th>Colonia</th>
                                      <th>Municipio</th>
                                      <th>Estado</th>
                                      <th>Pais</th>
                                      <th>CP</th>
                                      <th></th>
                                    </tr>
                                  </thead>
                                </table>
                              </div>
                            </div>
                          </div>
                        </div>
                        </span> 
                        
                        <input type="hidden" value="0" id="txtEdicion">
                        <input type="hidden" value="0" id="txtSucursalHis">
                        <input type="hidden" value="0" id="txtPKDireccion">
                      </form>
                    </div>
                  </div>
                </div>
              </div>`;

  $("#datos").html(html);
  cargarTablaDireccionesEnvio(id,1);
  resetValidations();
}

/*----------------------Funciones de carga de datos de los combos-------------------------------*/
function validarCorreoDire(value) {
  console.log(value);
  var reg =
    /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

  var regOficial =
    /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/;

  //Se muestra un texto a modo de ejemplo, luego va a ser un icono
  if (reg.test(value) && regOficial.test(value)) {
    $("#invalid-emailDire").css("display", "none");
    $("#invalid-emailDire").text("El cliente debe tener un email.");
    $("#txtEmail").removeClass("is-invalid");
  } else if (reg.test(value)) {
    $("#invalid-emailDire").css("display", "none");
    $("#invalid-emailDire").text("El cliente debe tener un email.");
    $("#txtEmail").removeClass("is-invalid");
  } else {
    $("#invalid-emailDire").css("display", "block");
    $("#invalid-emailDire").text("El email debe ser valido.");
    $("#txtEmail").addClass("is-invalid");
  }
}

function validarCorreoDireModal(value) {
  console.log(value);
  var reg =
    /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

  var regOficial =
    /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/;

  //Se muestra un texto a modo de ejemplo, luego va a ser un icono
  if (reg.test(value) && regOficial.test(value)) {
    $("#invalid-emailDireEdit").css("display", "none");
    $("#invalid-emailDireEdit").text("El cliente debe tener un email.");
    $("#txtEmailEdit2").removeClass("is-invalid");
  } else if (reg.test(value)) {
    $("#invalid-emailDireEdit").css("display", "none");
    $("#invalid-emailDireEdit").text("El cliente debe tener un email.");
    $("#txtEmailEdit2").removeClass("is-invalid");
  } else {
    $("#invalid-emailDireEdit").css("display", "block");
    $("#invalid-emailDireEdit").text("El email debe ser valido.");
    $("#txtEmailEdit2").addClass("is-invalid");
  }
}

$(document).on("change", "#cmbEstado", function () {
  $("#invalid-municipioDire").css("display", "block");
  $("#cmbEstado").addClass("is-invalid");
  if ($("#cmbEstado").val()) {
    $("#invalid-municipioDire").css("display", "none");
    $("#cmbEstado").removeClass("is-invalid");
    $("#invalid-municipioDire").text(
      "La información fiscal debe tener un estado."
    );
  }
});

function cargarCMBPaisesDir(data, input) {
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
        /* if (data === respuesta[i].PKPais) {
          selected = "selected";
        } else {
          selected = "";
        } */

        html +=
          '<option value="' +
          respuesta[i].PKPais +
          '">' +
          respuesta[i].Pais +
          "</option>";
      });

      CargarSlimPaises();

      $("#" + input + "").html(html);
      //$("#cmbPais").val("146");
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function cargarCMBPaisesDirModal(data, input) {
  var html = "";
  var selected;
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_paises" },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta paises: ", respuesta);

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

      CargarSlimPaisesDirEdit();

      $("#" + input + "").html(html);
      $("#cmbPaisEdit").val("146");
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function cargarCMBPaisesDirModalEdit(data, input, value) {
  var html = "";
  var selected;
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_paises" },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta paises: ", respuesta);

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

function cargarCMBPaisesDirEdit(data, input, value) {
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

function cargarCMBEstadosDir(data, input) {
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

function cargarCMBEstadosDirModal(data, input) {
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

function cargarCMBEstadosDirModalEdit(data, input, value) {
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

function cargarCMBEstadosDirEdit(data, input, value) {
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

/*----------------------Plugin Slim para la búsqueda dentro del-------------------------------*/
function CargarSlimPaisesDir() {
  new SlimSelect({
    select: "#cmbPais",
    placeholder: "Seleciona un pais",
    allowDeselect: true,
    deselectLabel: '<span class="">✖</span>',
  });

  CargarSlimEstadosDir();
}

function CargarSlimPaisesDirEdit() {
  new SlimSelect({
    select: "#cmbPaisEdit",
    deselectLabel: '<span class="">✖</span>',
  });

  CargarSlimEstadosDirEdit();
}

function CargarSlimEstadosDir() {
  new SlimSelect({
    select: "#cmbEstado",
    placeholder: "Seleciona un estado",
    allowDeselect: true,
    deselectLabel: '<span class="">✖</span>',
    addable: function (value) {
      var pkPais = $("#cmbPais").val();
      validarEstadoDir(value, pkPais);
    },
  });
}

function CargarSlimEstadosDirEdit() {
  new SlimSelect({
    select: "#cmbEstadoEdit",
    deselectLabel: '<span class="">✖</span>',
    addable: function (value) {
      var pkPais = $("#cmbPaisEdit").val();
      validarEstadoDir(value, pkPais);
    },
  });
}

/*----------------------Cambio de seleccion de pais-------------------------------*/
function cambioPaisDir() {
  var PKPais = $("#cmbPais").val();
  cargarCMBEstados(PKPais, "cmbEstado");
  $("#invalid-paisDire").css("display", "block");
  $("#invalid-paisDire").text("La dirección debe tener un pais.");
  $("#cmbPais").addClass("is-invalid");
  if (PKPais) {
    $("#invalid-paisDire").css("display", "none");
    $("#invalid-paisDire").text("La dirección debe tener un pais.");
    $("#cmbPais").removeClass("is-invalid");
  }
}

function cambioPaisDirModal() {
  var PKPais = $("#cmbPaisEdit").val();
  cargarCMBEstados(PKPais, "cmbEstadoEdit");
  $("#invalid-paisDireEdit").css("display", "block");
  $("#invalid-paisDireEdit").text("La dirección debe tener un pais.");
  $("#cmbPaisEdit").addClass("is-invalid");
  if (PKPais) {
    $("#invalid-paisDireEdit").css("display", "none");
    $("#invalid-paisDireEdit").text("La dirección debe tener un pais.");
    $("#cmbPaisEdit").removeClass("is-invalid");
  }
}

$(document).on("change", "#cmbEstado", function () {
  $("#invalid-nombreBanco").css("display", "block");
  $("#cmbEstado").addClass("is-invalid");
  if ($("#cmbEstado").val()) {
    $("#invalid-estadoDire").css("display", "none");
    $("#cmbEstado").removeClass("is-invalid");
  }
});
/*----------------------------Botón añadir razón social ---------------------------*/

function validarEstadoDir(estado, pkPais) {
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
        anadirEstadoDir(estado, pkPais);

        console.log("¡No existe!");
      }
    },
  });
}

/* Añadir el impuesto */
function anadirEstadoDir(estado, pkPais) {
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

function escribirSucursal(id) {
  var sucursal = $("#txtSucursal").val();
  var sucursalHis = $("#txtSucursalHis").val();

  if (sucursal != sucursalHis) {
    $.ajax({
      url: "../../php/funciones.php",
      data: {
        clase: "get_data",
        funcion: "validar_sucursal_Cliente",
        data: sucursal,
        data2: id,
      },
      dataType: "json",
      success: function (data) {
        console.log("respuesta sucursal validado: ", data);
        /* Validar si ya existe el identificador con ese nombre*/
        if (parseInt(data[0]["existe"]) == 1) {
          $("#invalid-sucursal").css("display", "block");
          $("#invalid-sucursal").text(
            "La sucursal ya esta registrada en el sistema."
          );
          $("#txtSucursal").addClass("is-invalid");
          console.log("¡Ya existe!");
        } else {
          $("#invalid-sucursal").css("display", "none");
          $("#invalid-sucursal").text(
            "La dirección debe tener un nombre de sucursal."
          );
          $("#txtSucursal").removeClass("is-invalid");
          if (!sucursal) {
            $("#invalid-sucursal").css("display", "block");
            $("#invalid-sucursal").text(
              "La dirección debe tener un nombre de sucursal."
            );
            $("#txtSucursal").addClass("is-invalid");
          }
          console.log("¡No existe!");
        }
      },
    });
  }
}

function escribirSucursalModal(id) {
  var sucursal = $("#txtSucursalEdit").val();
  var sucursalHis = $("#txtSucursalHis").val();

  console.log("ID del Cliente: " + id);

  if (sucursal != sucursalHis) {
    $.ajax({
      url: "../../php/funciones.php",
      data: {
        clase: "get_data",
        funcion: "validar_sucursal_Cliente",
        data: sucursal,
        data2: id,
      },
      dataType: "json",
      success: function (data) {
        console.log("respuesta sucursal validado: ", data);
        /* Validar si ya existe el identificador con ese nombre*/
        if (parseInt(data[0]["existe"]) == 1) {
          console.log("¡Ya existe!");
          $("#invalid-sucursalEdit").css("display", "block");
          $("#invalid-sucursalEdit").text(
            "La sucursal ya esta registrada en el sistema."
          );
          $("#txtSucursalEdit").addClass("is-invalid");
        } else {
          $("#invalid-sucursalEdit").css("display", "none");
          $("#invalid-sucursalEdit").text(
            "La dirección debe tener un nombre de sucursal."
          );
          $("#txtSucursalEdit").removeClass("is-invalid");
          if (!sucursal) {
            $("#invalid-sucursalEdit").css("display", "block");
            $("#invalid-sucursalEdit").text(
              "La dirección debe tener un nombre de sucursal."
            );
            $("#txtSucursalEdit").addClass("is-invalid");
          }
          console.log("¡No existe!");
        }
      },
    });
  }
}

function anadirRazonSocialDir(id) {
  if ($("#formDatosFiscales")[0].checkValidity()) {
    var badSucursal =
      $("#invalid-sucursal").css("display") === "block" ? false : true;
    var badEmail =
      $("#invalid-emailDire").css("display") === "block" ? false : true;
    var badCalle =
      $("#invalid-calleDire").css("display") === "block" ? false : true;
    var badNoExt =
      $("#invalid-numExt").css("display") === "block" ? false : true;
    var badColonia =
      $("#invalid-colonia").css("display") === "block" ? false : true;
    var badMunicipio =
      $("#invalid-municipioDire").css("display") === "block" ? false : true;
    var badPais =
      $("#invalid-paisDire").css("display") === "block" ? false : true;
    var badEstado =
      $("#invalid-estadoDire").css("display") === "block" ? false : true;
    var badCP = $("#invalid-cpDire").css("display") === "block" ? false : true;
    var badContacto =
      $("#invalid-contacto").css("display") === "block" ? false : true;
    var badTelefono =
      $("#invalid-telefono").css("display") === "block" ? false : true;

    if (
      badSucursal &&
      badEmail &&
      badCalle &&
      badNoExt &&
      badColonia &&
      badMunicipio &&
      badPais &&
      badEstado &&
      badCP &&
      badContacto&&
      badTelefono
    ) {
      var sucursal = $("#txtSucursal").val();
      var email = $("#txtEmail").val();
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

      var pkDireccion = 0;

      var contacto = $("#txtContacto").val().trim();
      var telefono = $("#txtTelefono").val().trim();

      $.ajax({
        url: "../../php/funciones.php",
        data: {
          clase: "save_data",
          funcion: "save_direccionEnvio_Cliente",
          datos: sucursal,
          datos3: email,
          datos4: calle,
          datos5: numExt,
          datos6: numInt,
          datos7: colonia,
          datos8: municipio,
          datos9: pais,
          datos10: estado,
          datos11: cp,
          datos12: id,
          datos13: pkDireccion,
          datos14: contacto,
          datos15: telefono,
        },
        dataType: "json",
        success: function (respuesta) {
          console.log("respuesta añadir direccion de envio:", respuesta);

          if (respuesta[0].status) {
            if(respuesta[0].status == 0){
              Lobibox.notify("warning", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../../../img/timdesk/warning_circle.svg",
                msg: "¡Datos faltantes!",
                sound: "../../../../../sounds/sound4",
              });
            }else{
              $("#tblListadoDatosDireccionesEnvioProveedor").DataTable().ajax.reload();
              $("#txtSucursal").val("");
              $("#txtEmail").val("");
              $("#txtCalle").val("");
              $("#txtNumExt").val("");
              $("#txtNumInt").val("");
              $("#txtColonia").val("");
              $("#txtMunicipio").val("");
              cargarCMBEstados("241", "cmbEstado");
              $("#cmbPais").val("241");
              $("#txtCP").val("");
              $("#txtEdicion").val("0");
              $("#txtSucursalHis").val("");
              $("#txtPKDireccion").val("0");
              $("#txtContacto").val("");
              $("#txtTelefono").val("");
  
              $("#notaFSucursal").css("display", "none");
              $("#notaFEmail").css("display", "none");
              $("#notaFCalle").css("display", "none");
              $("#notaNumExt").css("display", "none");
              $("#notaFColonia").css("display", "none");
              $("#notaMunicipio").css("display", "none");
              $("#notaFCP").css("display", "none");
              Lobibox.notify("success", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../../../img/timdesk/checkmark.svg",
                msg: "¡Se guardó la dirección de envío con éxito!",
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
              msg: "¡No se guardó la dirección de envío con éxito :(!",
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
    console.log("Faltan campos");
    if (!$("#txtSucursal").val()) {
      $("#invalid-sucursal").css("display", "block");
      $("#txtSucursal").addClass("is-invalid");
    }
    if (!$("#txtEmail").val()) {
      $("#invalid-emailDire").css("display", "block");
      $("#txtEmail").addClass("is-invalid");
    }
    if (!$("#txtCalle").val()) {
      $("#invalid-calleDire").css("display", "block");
      $("#txtCalle").addClass("is-invalid");
    }
    if (!$("#txtNumExt").val()) {
      $("#invalid-numExt").css("display", "block");
      $("#txtNumExt").addClass("is-invalid");
    }
    if (!$("#txtColonia").val()) {
      $("#invalid-colonia").css("display", "block");
      $("#txtColonia").addClass("is-invalid");
    }
    if (!$("#txtMunicipio").val()) {
      $("#invalid-municipioDire").css("display", "block");
      $("#txtMunicipio").addClass("is-invalid");
    }
    if (!$("#cmbPais").val()) {
      $("#invalid-paisDire").css("display", "block");
      $("#cmbPais").addClass("is-invalid");
    }
    if (!$("#cmbEstado").val()) {
      $("#invalid-estadoDire").css("display", "block");
      $("#cmbEstado").addClass("is-invalid");
    }
    if (!$("#txtCP").val()) {
      $("#invalid-cpDire").css("display", "block");
      $("#txtCP").addClass("is-invalid");
    }
    if (!$("#txtContacto").val()) {
      $("#invalid-contacto").css("display", "block");
      $("#txtContacto").addClass("is-invalid");
    }
    if (!$("#txtTelefono").val()) {
      $("#invalid-telefono").css("display", "block");
      $("#txtTelefono").addClass("is-invalid");
    }
  }
}

function anadirRazonSocialDirEdit(id) {
  if ($("#formDatosFiscalesEdit")[0].checkValidity()) {
    var badSucursal =
      $("#invalid-sucursalEdit").css("display") === "block" ? false : true;
    var badEmail =
      $("#invalid-emailDireEdit").css("display") === "block" ? false : true;
    var badCalle =
      $("#invalid-calleDireEdit").css("display") === "block" ? false : true;
    var badNoExt =
      $("#invalid-numExtEdit").css("display") === "block" ? false : true;
    var badColonia =
      $("#invalid-coloniaEdit").css("display") === "block" ? false : true;
    var badMunicipio =
      $("#invalid-municipioDireEdit").css("display") === "block" ? false : true;
    var badPais =
      $("#invalid-paisDireEdit").css("display") === "block" ? false : true;
    var badEstado =
      $("#invalid-estadoDireEdit").css("display") === "block" ? false : true;
    var badCP =
      $("#invalid-cpDireEdit").css("display") === "block" ? false : true;
    var badContacto =
      $("#invalid-contactoEdit").css("display") === "block" ? false : true;
    var badTelefono =
      $("#invalid-telefonoEdit").css("display") === "block" ? false : true;

    if (
      badSucursal &&
      badEmail &&
      badCalle &&
      badNoExt &&
      badColonia &&
      badMunicipio &&
      badPais &&
      badEstado &&
      badCP &&
      badContacto &&
      badTelefono
    ) {
      var sucursal = $("#txtSucursalEdit").val();
      var email = $("#txtEmailEdit2").val();
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

      var pkDireccion = $("#txtPKDireccion").val();

      var contacto = $("#txtContactoEdit").val().trim();
      var telefono = $("#txtTelefonoEdit2").val().trim();

      $.ajax({
        url: "../../php/funciones.php",
        data: {
          clase: "save_data",
          funcion: "save_direccionEnvio_Cliente",
          datos: sucursal,
          datos3: email,
          datos4: calle,
          datos5: numExt,
          datos6: numInt,
          datos7: colonia,
          datos8: municipio,
          datos9: pais,
          datos10: estado,
          datos11: cp,
          datos12: id,
          datos13: pkDireccion,
          datos14: contacto,
          datos15: telefono,
        },
        dataType: "json",
        success: function (respuesta) {
          console.log("respuesta añadir direccion de envio:", respuesta);

          if (respuesta[0].status) {
            if(respuesta[0].status == 0){
              Lobibox.notify("warning", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../../../img/timdesk/warning_circle.svg",
                msg: "¡Datos faltantes!",
                sound: "../../../../../sounds/sound4",
              });
            }else{
              $("#tblListadoDatosDireccionesEnvioProveedor").DataTable().ajax.reload();
              $("#txtSucursalEdit").val("");
              $("#txtEmailEdit2").val("");
              $("#txtCalleEdit").val("");
              $("#txtNumExtEdit").val("");
              $("#txtNumIntEdit").val("");
              $("#txtColoniaEdit").val("");
              $("#txtMunicipioEdit").val("");
              cargarCMBEstadosDirModal("146", "cmbEstadoEdit");
              $("#txtCPEdit").val("");
              $("#txtEdicion").val("0");
              $("#txtSucursalHis").val("");
              $("#txtPKDireccion").val("0");
              $("#txtContactoEdit").val("");
              $("#txtTelefonoEdit2").val("");
  
              $("#notaFSucursal").css("display", "none");
              $("#notaFEmail").css("display", "none");
              $("#notaFCalle").css("display", "none");
              $("#notaNumExt").css("display", "none");
              $("#notaFColonia").css("display", "none");
              $("#notaMunicipio").css("display", "none");
              $("#notaFCP").css("display", "none");
              $("#editar_DireccionEnvio").modal("hide");
              Lobibox.notify("success", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../../../img/timdesk/checkmark.svg",
                msg: "¡Se guardó la dirección de envío con éxito!",
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
              msg: "¡No se guardó la dirección de envío con éxito :(!",
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
    console.log("Faltan campos");
    if (!$("#txtSucursalEdit").val()) {
      $("#invalid-sucursalEdit").css("display", "block");
      $("#txtSucursalEdit").addClass("is-invalid");
    }
    if (!$("#txtEmailEdit2").val()) {
      $("#invalid-emailDireEdit").css("display", "block");
      $("#txtEmailEdit2").addClass("is-invalid");
    }
    if (!$("#txtCalleEdit").val()) {
      $("#invalid-calleDireEdit").css("display", "block");
      $("#txtCalleEdit").addClass("is-invalid");
    }
    if (!$("#txtNumExtEdit").val()) {
      $("#invalid-numExtEdit").css("display", "block");
      $("#txtNumExtEdit").addClass("is-invalid");
    }
    if (!$("#txtColoniaEdit").val()) {
      $("#invalid-coloniaEdit").css("display", "block");
      $("#txtColoniaEdit").addClass("is-invalid");
    }
    if (!$("#txtMunicipioEdit").val()) {
      $("#invalid-municipioDireEdit").css("display", "block");
      $("#txtMunicipioEdit").addClass("is-invalid");
    }
    if (!$("#cmbPaisEdit").val()) {
      $("#invalid-paisDireEdit").css("display", "block");
      $("#cmbPaisEdit").addClass("is-invalid");
    }
    if (!$("#cmbEstadoEdit").val()) {
      $("#invalid-estadoDireEdit").css("display", "block");
      $("#cmbEstadoEdit").addClass("is-invalid");
    }
    if (!$("#txtCPEdit").val()) {
      $("#invalid-cpDireEdit").css("display", "block");
      $("#txtCPEdit").addClass("is-invalid");
    }
    if (!$("#txtContactoEdit").val()) {
      $("#invalid-contactoEdit").css("display", "block");
      $("#txtContactoEdit").addClass("is-invalid");
    }
    if (!$("#txtTelefonoEdit2").val()) {
      $("#invalid-telefonoEdit").css("display", "block");
      $("#txtTelefonoEdit2").addClass("is-invalid");
    }
  }
}

$("#editar_DireccionEnvio").on("hidden.bs.modal", function (e) {
  $("#invalid-sucursalEdit").css("display", "none");
  $("#txtSucursalEdit").removeClass("is-invalid");
  $("#txtSucursalEdit").val("");

  $("#invalid-emailDireEdit").css("display", "none");
  $("#txtEmailEdit2").removeClass("is-invalid");
  $("#txtEmailEdit2").val("");

  $("#invalid-calleDireEdit").css("display", "none");
  $("#txtCalleEdit").removeClass("is-invalid");
  $("#txtCalleEdit").val("");

  $("#invalid-numExtEdit").css("display", "none");
  $("#txtNumExtEdit").removeClass("is-invalid");
  $("#txtNumExtEdit").val("");

  $("#invalid-coloniaEdit").css("display", "none");
  $("#txtColoniaEdit").removeClass("is-invalid");
  $("#txtColoniaEdit").val("");

  $("#invalid-municipioDireEdit").css("display", "none");
  $("#txtMunicipioEdit").removeClass("is-invalid");
  $("#txtMunicipioEdit").val("");

  $("#invalid-paisDireEdit").css("display", "none");
  $("#cmbPaisEdit").removeClass("is-invalid");
  $("#cmbPaisEdit").val("");

  $("#invalid-estadoDireEdit").css("display", "none");
  $("#cmbEstadoEdit").removeClass("is-invalid");
  $("#cmbEstadoEdit").val("");

  $("#invalid-cpDireEdit").css("display", "none");
  $("#txtCPEdit").removeClass("is-invalid");
  $("#txtCPEdit").val("");

  $("#invalid-contactoEdit").css("display", "none");
  $("#txtContactoEdit").removeClass("is-invalid");
  $("#txtContactoEdit").val("");

  $("#invalid-telefonoEdit").css("display", "none");
  $("#txtTelefonoEdit2").removeClass("is-invalid");
  $("#txtTelefonoEdit2").val("");
});

/* Eliminar el impuesto */
function obtenerIdDireccionProveedorEliminar(pkDireccionEnvio) {
  console.log("ID de la direccion : " + pkDireccionEnvio);
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "delete_data",
      funcion: "delete_direccionEnvio_Cliente",
      datos: pkDireccionEnvio,
    },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta eliminar direccion envio:", respuesta);

      if (respuesta[0].status) {
        $("#tblListadoDatosDireccionesEnvioProveedor").DataTable().ajax.reload();
        Lobibox.notify("success", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/checkmark.svg",
          msg: "¡Se eliminó la dirección de envío con éxito!",
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

/* Eliminar el impuesto */
function obtenerIdDireccionProveedorEditar(pkDireccionEnvio) {
  console.log("ID de la direccion : " + pkDireccionEnvio);
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_datos_direccionEnvio_cliente",
      datos: pkDireccionEnvio,
    },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta datos de la direccion del cliente", respuesta);

      cargarCMBPaisesDirModalEdit("", "cmbPaisEdit", respuesta[0].Pais);
      cargarCMBEstadosDirModalEdit(
        respuesta[0].Pais,
        "cmbEstadoEdit",
        respuesta[0].Estado
      );

      $("#txtSucursalEdit").val(respuesta[0].Sucursal);
      $("#txtEmailEdit2").val(respuesta[0].Email);
      $("#txtCalleEdit").val(respuesta[0].Calle);
      $("#txtNumIntEdit").val(respuesta[0].Numero_Interior);
      $("#txtNumExtEdit").val(respuesta[0].Numero_exterior);
      $("#txtColoniaEdit").val(respuesta[0].Colonia);
      $("#txtMunicipioEdit").val(respuesta[0].Municipio);

      $("#txtCPEdit").val(respuesta[0].CPs);

      $("#txtContactoEdit").val(respuesta[0].Contacto);
      $("#txtTelefonoEdit2").val(respuesta[0].Telefono);

      $("#txtNombreDDir2").val(respuesta[0].Sucursal);

      $("#txtEdicion").val("1");
      $("#txtSucursalHis").val(respuesta[0].Sucursal);
      $("#txtPKDireccion").val(respuesta[0].PKDireccionEnvioCliente);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

//Validar que se hayan completado todos los campos antes de pasar a la siguiente pestaña
function guardarDireccionesEnvio(id) {
  TerminarGuardadoDatos();
}

function TerminarGuardadoDatos() {
  window.location.href = "../clientes";
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

function validarCPEditModal() {
  var value = $("#txtCPEdit").val();
  var ercp = /(^([0-9]{5,5})|^)$/;
  if (!ercp.test(value)) {
    $("#txtCPEdit").addClass("is-invalid");
    $("#invalid-cpDireEdit").css("display", "block");
    $("#invalid-cpDireEdit").text("La dirección debe tener un CP válido.");
    //validEmptyInput('txtCPEdit', 'invalid-cpDireEdit', 'La dirección debe tener un CP válido.')
  } else {
    $("#txtCPEdit").removeClass("is-invalid");
    $("#invalid-cpDireEdit").css("display", "none");
    $("#invalid-cpDireEdit").text("La dirección debe tener un CP válido");
  }
}

function validarCPEdit() {
  var value = $("#txtCP").val();
  var ercp = /(^([0-9]{5,5})|^)$/;
  if (!ercp.test(value)) {
    $("#txtCP").addClass("is-invalid");
    $("#invalid-cpDire").css("display", "block");
    $("#invalid-cpDire").text("La dirección debe tener un CP válido.");
    //validEmptyInput('txtCP', 'invalid-cpDire', 'La dirección debe tener un CP válido.')
  } else {
    $("#txtCP").removeClass("is-invalid");
    $("#invalid-cpDire").css("display", "none");
    $("#invalid-cpDire").text("La dirección debe tener un CP válido");
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

function validarEmpresaCliente(pkCliente) {
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "validar_EmpresaCliente",
      data: pkCliente,
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
        window.location.href = "../clientes";
        //return false;
      }
    },
  });
}

function validate_Permissions(pkPantalla,pestana){
  $.ajax({
    url: '../../php/funciones.php',
    data:{clase:"get_data", 
          funcion:"validar_Permisos", 
          data:pkPantalla},
    dataType:"json",
    success: function(data) {
      _permissions.read = data[0].isRead;
      _permissions.add = data[0].isAdd;
      _permissions.edit = data[0].isEdit;
      _permissions.delete = data[0].isDelete;
      _permissions.export = data[0].isExport;
        
      if (pestana == 'url'){
        if (_permissions.add == '0'){
          window.location.href = "../clientes";
        }
      }
    }
  });
}