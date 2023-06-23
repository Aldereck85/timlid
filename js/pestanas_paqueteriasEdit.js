var _permissions = {
  read: 0,
  add: 0,
  edit: 0,
  delete: 0,
  export: 0,
};

var _global = {
  pkPaqueteria: 0,
  razonSocialHis: "",
  rfcHis: "",
  pkContacto: 0,
  emailContactoHis: "",
  pkCuentaBancaria: 0,
  cuentaHis: 0,
  clabeHis: 0,
  pkSucursal: 0,
  sucursalHis: "",
  nombreComercialHis: "",
};

$(document).ready(function () {
  CargarDatosPaqueteria();
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
      sNext: "<i class='fas fa-chevron-right'></i>",
      sPrevious: "<i class='fas fa-chevron-left'></i>",
    },
  };
  return idioma_espanol;
}

//Cargar pestaña de Datos de la paquetería
function CargarDatosPaqueteria() {
  validate_Permissions(41, "url");
  cargarCMBPaises(241, "cmbPais");
  cargarCMBEstados(241, "cmbEstado", "");
  resetTabs("#CargarDatosPaqueteria");

  var html = `<div class="card shadow mb-4">
                <div class="card-header">
                  Tarjeta de paquetería
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-12">
                      <form id="formDatosPaqueteria" class="needs-validation" novalidate> 
                        <div class="form-group">
                          <div class="row">
                            <div class="col-xl-9 col-lg-9 col-md-6 col-sm-3 col-xs-0">
                              
                            </div>
                            <div class="col-xl-1 col-lg-1 col-md-2 col-sm-4 col-xs-6">
                              <label for="usr">Estatus:*</label>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-4 col-sm-5 col-xs-6">
                              <input type="checkbox" id="activePaqueteria" class="check-custom" checked>
                              <label class="shadow-sm check-custom-label" for="activePaqueteria">
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
                                  <input class="form-control" type="text" name="txtNombreComercial" id="txtNombreComercial" autofocus="" maxlength="255" onchange="escribirNombre(); validEmptyInput('txtNombreComercial', 'invalid-nombreCom', 'La paquetería debe tener un nombre comercial.')" required>
                                  <div class="invalid-feedback" id="invalid-nombreCom">La paquetería debe tener un nombre comercial.</div>
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <label for="usr">Teléfono:</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control numeric-only" type="text" name="txtTelefono" id="txtTelefono" autofocus="" minlength="7" maxlength="10" placeholder="Ej. 33 3333 33 33">
                                  <div class="invalid-feedback">La paquetería debe tener un número télefonico.</div>
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <label for="usr">E-mail:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control" type="email" name="txtEmail" id="txtEmail" autofocus="" required maxlength="100" placeholder="Ej. ejemplo@dominio.com" onchange="validarCorreo(this.value, 'txtEmail', 'invalid-email');">
                                  <div class="invalid-feedback" id="invalid-email">La paquetería debe tener un email.</div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        
                        <div class="form-group">
                          <div class="row">
                            <div class="col-lg-6">
                              <label for="usr">Razón Social:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control" type="text" name="txtRazonSocial" id="txtRazonSocial" autofocus="" required maxlength="100" placeholder="Ej. GH Medic S.A. de C.V." onchange="escribirRazonSocial('txtRazonSocial','invalid-razon'); validEmptyInput('txtRazonSocial', 'invalid-razon', 'La paquetería debe tener una razón social.')">
                                  <div class="invalid-feedback" id="invalid-razon">La paquetería debe tener una razón social.</div>
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-6">
                              <label for="usr">RFC:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control alphaNumeric-only" type="text" name="txtRFC" id="txtRFC" required maxlength="13" placeholder="Ej. GHMM100101AA1" onchange="validarInput('txtRFC','invalid-rfc');" oninput="let p=this.selectionStart;this.value=this.value.toUpperCase();this.setSelectionRange(p, p);">
                                  <div class="invalid-feedback" id="invalid-rfc">La paquetería debe tener un RFC.</div>
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
                                  <input class="form-control" type="text" name="txtNumExt" id="txtNumExt" maxlength="10" placeholder="Ej. 2353 A">
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-3">
                              <label for="usr">Número interior:</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control" type="text" name="txtNumInt" id="txtNumInt"  maxlength="10" placeholder="Ej. 524">
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
                                  <input class="form-control" type="text" name="txtMunicipio" id="txtMunicipio" maxlength="255" placeholder="Ej. Guadalajara">
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-2">
                              <label for="usr">País*:</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                    <select name="cmbPais" id="cmbPais"  onchange="cambioPais('cmbPais','invalid-pais','cmbEstado');" required>
                                    </select>
                                    <div class="invalid-feedback" id="invalid-pais">La paquetería debe tener un pais.</div>
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-2">
                              <label for="usr">Estado*:</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                    <select name="cmbEstado" id="cmbEstado" required>
                                    </select>
                                    <div class="invalid-feedback" id="invalid-paisEstado">La paquetería debe tener un estado.</div>
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-2">
                              <label for="usr">Código Postal:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control numeric-only" type="text" name="txtCP" id="txtCP" autofocus="" required maxlength="5" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" placeholder="Ej. 52632" onchange="validarCP('txtCP','invalid-cp');">
                                  <div class="invalid-feedback" id="invalid-cp">La paquetería debe tener un codigo postal.</div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <br>
                        <label for="">* Campos requeridos</label>
                      </form>

                      <a class="btn-custom btn-custom--blue float-right" id="btnEditarPaqueteria">Guardar</a>
                    </div>
                  </div>
                </div>
              </div>`;

  $("#datos").append(html);

  setTimeout(function () {
    new SlimSelect({
      select: "#cmbPais",
      deselectLabel: '<span class="">✖</span>',
    });

    new SlimSelect({
      select: "#cmbEstado",
      deselectLabel: '<span class="">✖</span>',
      addable: function (value) {
        var pkPais = $("#cmbPais").val();
        validarEstado(value, pkPais);
      },
    });
  }, 500);

  CargarDatosGeneralesPaqueteria(_global.pkPaqueteria);
}

function cargarCMBPaises(data, input) {
  var html = "";
  var selected;
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_paises" },
    dataType: "json",
    success: function (respuesta) {
      html += '<option data-placeholder="true"></option>';
      $.each(respuesta, function (i) {
        if (data === respuesta[i].PKPais) {
          selected = "selected";
        } else {
          selected = "";
        }

        html += `<option value="${respuesta[i].PKPais}" ${selected}> 
                  ${respuesta[i].Pais}
                </option>`;
      });

      $(`#${input}`).html(html);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function cargarCMBEstados(data, input, select) {
  var valor = data;

  var html = "";
  var selected;

  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_estados", data: valor },
    dataType: "json",
    success: function (respuesta) {
      $.each(respuesta, function (i) {
        if (select === respuesta[i].PKEstado) {
          selected = "selected";
        } else {
          selected = "";
        }
        html += `<option value="${respuesta[i].PKEstado}" ${selected}>${respuesta[i].Estado}</option>`;
      });

      $(`#${input}`).html(html);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function CargarDatosGeneralesPaqueteria(id) {
  _global.pkPaqueteria = id;
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_datos_paqueteria_generales",
      datos: id,
    },
    dataType: "json",
    success: function (respuesta) {
      if (respuesta[0].FKEstatusGeneral == 1) {
        $("#activePaqueteria").prop("checked", true);
      } else {
        $("#activePaqueteria").prop("checked", false);
      }
      $("#txtNombreComercial").val(respuesta[0].NombreComercial);
      _global.nombreComercialHis = respuesta[0].NombreComercial;
      $("#txtTelefono").val(respuesta[0].Telefono);
      $("#txtEmail").val(respuesta[0].Email);
      $("#txtRazonSocial").val(respuesta[0].razon_social);
      $("#txtRFC").val(respuesta[0].rfc);
      $("#txtCalle").val(respuesta[0].Calle);
      $("#txtNumExt").val(respuesta[0].Numero_exterior);
      $("#txtNumInt").val(respuesta[0].Numero_Interior);
      $("#txtColonia").val(respuesta[0].Colonia);
      $("#txtMunicipio").val(respuesta[0].Municipio);
      cargarCMBPaises(respuesta[0].Pais, "cmbPais");
      cargarCMBEstados(respuesta[0].Pais, "cmbEstado", respuesta[0].Estado);
      $("#txtCP").val(respuesta[0].cp);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function escribirNombre() {
  var valor = $("#txtNombreComercial").val();

  if (valor != _global.nombreComercialHis) {
    $.ajax({
      url: "../../php/funciones.php",
      data: {
        clase: "get_data",
        funcion: "validar_Paqueteria_nombreComercial",
        data: valor,
      },
      dataType: "json",
      success: function (data) {
        /* Validar si ya existe el identificador con ese nombre*/
        if (parseInt(data[0]["existe"]) == 1) {
          $("#invalid-nombreCom").text(
            "El nombre ya se encuentra registrado en el sistema."
          );
          $("#invalid-nombreCom").css("display", "block");
          $("#txtNombreComercial").addClass("is-invalid");
        } else {
          $("#invalid-nombreCom").text(
            "La paquetería debe tener un nombre comercial."
          );
          $("#invalid-nombreCom").css("display", "none");
          $("#txtNombreComercial").removeClass("is-invalid");
        }
      },
    });
  }
}

function validarCorreo(value, inputID, invalidDivID) {
  var reg =
    /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

  var regOficial =
    /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/;

  //Se muestra un texto a modo de ejemplo, luego va a ser un icono
  if (value == "" || value == null) {
    $("#" + inputID).addClass("is-invalid");
    $("#" + invalidDivID).css("display", "block");
    $("#" + invalidDivID).text("La paquetería debe tener un email válido.");
  } else if (reg.test(value) && regOficial.test(value)) {
    $("#" + invalidDivID).text("La paquetería debe tener un email.");
    $("#" + invalidDivID).css("display", "none");
    $("#" + inputID).removeClass("is-invalid");
  } else if (reg.test(value)) {
    $("#" + invalidDivID).text("La paquetería debe tener un email.");
    $("#" + invalidDivID).css("display", "none");
    $("#" + inputID).removeClass("is-invalid");
  } else {
    $("#" + inputID).addClass("is-invalid");
    $("#" + invalidDivID).css("display", "block");
    $("#" + invalidDivID).text("La paquetería debe tener un email válido.");
  }
}

function escribirRazonSocial(inputID, invalidDivID) {
  var razonSocial = $("#txtRazonSocial").val();

  if (razonSocial != _global.razonSocialHis) {
    $.ajax({
      url: "../../php/funciones.php",
      data: {
        clase: "get_data",
        funcion: "validar_Paqueteria_razonSocial",
        data: razonSocial,
        data2: _global.pkPaqueteria,
      },
      dataType: "json",
      success: function (data) {
        /* Validar si ya existe el identificador con ese nombre*/
        if (parseInt(data[0]["existe"]) == 1) {
          $("#" + invalidDivID).text(
            "La razón social ya se encuentra registrada en el sistema."
          );
          $("#" + invalidDivID).css("display", "block");
          $("#" + inputID).addClass("is-invalid");
        } else {
          $("#" + invalidDivID).text(
            "La paquetería debe tener una razón social."
          );
          $("#" + invalidDivID).css("display", "none");
          $("#" + inputID).removeClass("is-invalid");
        }
      },
    });
  }
}

function validarInput(inputID, invalidDivID) {
  var vRFC = $("#txtRFC").val();
  var rfc = vRFC.trim().toUpperCase();

  var rfcCorrecto = rfcValido(rfc); // ⬅️ Acá se comprueba

  if (rfcCorrecto) {
    escribirRFC(inputID, invalidDivID);
  } else {
    $("#invalid-rfc").text("El RFC debe ser válido.");
    $("#invalid-rfc").css("display", "block");
    $("#txtRFC").addClass("is-invalid");
  }
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

function escribirRFC(inputID, invalidDivID) {
  var rfc = $("#txtRFC").val();

  if (rfc != _global.rfcHis) {
    $.ajax({
      url: "../../php/funciones.php",
      data: {
        clase: "get_data",
        funcion: "validar_Paqueteria_rfc",
        data: rfc,
        data2: _global.pkPaqueteria,
      },
      dataType: "json",
      success: function (data) {
        /* Validar si ya existe el identificador con ese nombre*/
        if (parseInt(data[0]["existe"]) == 1) {
          $("#" + invalidDivID).text("El RFC ya esta registado en el sistema.");
          $("#" + invalidDivID).css("display", "block");
          $("#" + inputID).addClass("is-invalid");
        } else {
          $("#" + invalidDivID).text("La paquetería debe tener un RFC.");
          $("#" + invalidDivID).css("display", "none");
          $("#" + inputID).removeClass("is-invalid");
        }
      },
    });
  }
}

function cambioPais(inputID, invalidDivID, cmbEstado) {
  var PKPais = $("#" + inputID).val();
  if (PKPais) {
    $("#" + invalidDivID).css("display", "none");
    $("#" + inputID).removeClass("is-invalid");
    cargarCMBEstados(PKPais, cmbEstado, "");
  } else {
    $("#" + invalidDivID).css("display", "block");
    $("#" + inputID).addClass("is-invalid");
  }
}

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
      if (parseInt(data[0]["existe"]) == 1) {
      } else {
        anadirEstado(estado, pkPais);
      }
    },
  });
}

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
          sound: "../../../../../sounds/sound4",
        });
        cargarCMBEstados(pkPais, "cmbEstado", "");
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

function validarCP(inputID, invalidDivID) {
  var value = $("#" + inputID).val();
  var ercp = /(^([0-9]{5,5})|^)$/;
  if (!ercp.test(value)) {
    $("#" + invalidDivID).text("El CP debe ser valido.");
    $("#" + invalidDivID).css("display", "block");
    $("#" + inputID).addClass("is-invalid");
  } else {
    $("#" + invalidDivID).text("La paquetería debe tener un CP.");
    $("#" + invalidDivID).css("display", "none");
    $("#" + inputID).removeClass("is-invalid");
  }
}

$(document).on("click", "#btnEditarPaqueteria", function () {
  if ($("#formDatosPaqueteria")[0].checkValidity()) {
    var badNombreCom =
      $("#invalid-nombreCom").css("display") === "block" ? false : true;
    var badEmail =
      $("#invalid-email").css("display") === "block" ? false : true;
    var badRazonSocial =
      $("#invalid-razon").css("display") === "block" ? false : true;
    var badRFC = $("#invalid-rfc").css("display") === "block" ? false : true;
    var badPais = $("#invalid-pais").css("display") === "block" ? false : true;
    var badEstado =
      $("#invalid-paisEstado").css("display") === "block" ? false : true;
    var badCP = $("#invalid-cp").css("display") === "block" ? false : true;

    if (
      badNombreCom &&
      badEmail &&
      badRazonSocial &&
      badRFC &&
      badPais &&
      badEstado &&
      badCP
    ) {
      var datos = {
        estatus: $("#activePaqueteria").is(":checked") ? 1 : 0,
        nombreComercial: $("#txtNombreComercial").val(),
        telefono: $("#txtTelefono").val(),
        email: $("#txtEmail").val(),
        razonSocial: $("#txtRazonSocial").val(),
        rfc: $("#txtRFC").val(),
        calle: $("#txtCalle").val(),
        numeroExt: $("#txtNumExt").val(),
        numeroInt: $("#txtNumInt").val(),
        colonia: $("#txtColonia").val(),
        municipio: $("#txtMunicipio").val(),
        pais: $("#cmbPais").val(),
        estado: $("#cmbEstado").val(),
        cp: $("#txtCP").val(),
        pkPaqueteria: _global.pkPaqueteria,
      };

      $.ajax({
        url: "../../php/funciones.php",
        data: {
          clase: "edit_data",
          funcion: "edit_datosPaqueteria",
          datos,
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
              msg: "¡Datos generales registrados correctamente!",
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
    if (!$("#txtNombreComercial").val()) {
      $("#invalid-nombreCom").css("display", "block");
      $("#txtNombreComercial").addClass("is-invalid");
    } else {
      $("#invalid-nombreCom").css("display", "none");
      $("#txtNombreComercial").removeClass("is-invalid");
    }
    if (!$("#txtEmail").val()) {
      $("#invalid-email").css("display", "block");
      $("#txtEmail").addClass("is-invalid");
    } else {
      $("#invalid-email").css("display", "none");
      $("#txtEmail").removeClass("is-invalid");
    }
    if (!$("#txtRazonSocial").val()) {
      $("#invalid-razon").css("display", "block");
      $("#txtRazonSocial").addClass("is-invalid");
    } else {
      $("#invalid-razon").css("display", "none");
      $("#txtRazonSocial").removeClass("is-invalid");
    }
    if (!$("#txtRFC").val()) {
      $("#invalid-rfc").css("display", "block");
      $("#txtRFC").addClass("is-invalid");
    } else {
      $("#invalid-rfc").css("display", "none");
      $("#txtRFC").removeClass("is-invalid");
    }
    if (!$("#cmbPais").val()) {
      $("#invalid-pais").css("display", "block");
      $("#cmbPais").addClass("is-invalid");
    } else {
      $("#invalid-pais").css("display", "none");
      $("#cmbPais").removeClass("is-invalid");
    }
    if (!$("#cmbEstado").val()) {
      $("#invalid-paisEstado").css("display", "block");
      $("#cmbEstado").addClass("is-invalid");
    } else {
      $("#invalid-paisEstado").css("display", "none");
      $("#cmbEstado").removeClass("is-invalid");
    }
    if (!$("#txtCP").val()) {
      $("#invalid-cp").css("display", "block");
      $("#txtCP").addClass("is-invalid");
    } else {
      $("#invalid-cp").css("display", "none");
      $("#txtCP").removeClass("is-invalid");
    }
  }
});

function SeguirDatosContacto(id) {
  _global.pkPaqueteria = id;

  validarEmpresaPaqueteria(id);
  resetTabs("#CargarDatosContacto");

  var html = `<div class="card shadow mb-4">
                <div class="card-header">
                  Tarjeta de contacto
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-12">
                      <form id="formDatosContacto"> 
                        <div class="form-group">
                          <div class="row">
                            <div class="col-lg-4">
                              <label for="usr">Nombre(s) del contacto:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control" type="text" name="txtNombreContacto" id="txtNombreContacto" autofocus="" required="" maxlength="50" placeholder="Ej. José María" onchange="validEmptyInput('txtNombreContacto', 'invalid-nombreCont', 'El contacto debe tener un nombre.')">
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
                                  <input class="form-control numeric-only" type="text" name="txtTelefono" id="txtTelefono" minlength="7" maxlength="10" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" placeholder="Ej. 33 3333 3333">
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <label for="usr">Celular:</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control numeric-only" type="text" name="txtCelular" id="txtCelular" minlength="10" maxlength="10" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" placeholder="Ej. 33 3333 3333">
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <label for="usr">E-mail:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                    <input class="form-control" type="email" name="txtEmailContacto" id="txtEmailContacto" required="" maxlength="100" placeholder="Ej. ejemplo@dominio.com" onchange="validarCorreo(this.value, 'txtEmailContacto', 'invalid-emailCont');">
                                  <div class="invalid-feedback" id="invalid-emailCont">El contacto debe tener un email.</div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="row">
                            <div class="col-lg-10">
                            </div>
                            <div class="col-lg-2" style="text-align:center!important; margin-top:35px;">
                              <a href="#" class="btn-custom btn-custom--border-blue" id="btnAnadirContacto">Añadir contacto</a>
                            </div>
                          </div>
                        </div>
                        <label for="">* Campos requeridos</label>

                        <div class="form-group">
                          <!-- DataTales Example -->
                          <div class="card mb-4">
                            <div class="card-body">
                              <div class="table-responsive">
                                <table class="table" id="tblListadoDatosContactoPaqueteria" width="100%" cellspacing="0">
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
                      </form>
                    </div>
                  </div>
                </div>
              </div>`;

  $("#datos").html(html);

  cargarTablaContactosPaqueteria(id, _permissions.edit, _permissions.delete);
  resetValidations();
}

function cargarTablaContactosPaqueteria(
  id,
  _permissionsEdit,
  _permissionsDelete
) {
  _global.pkPaqueteria = id;
  $("#tblListadoDatosContactoPaqueteria").dataTable({
    language: setFormatDatatables(),
    info: false,
    scrollX: true,
    bSort: false,
    pageLength: 15,
    responsive: true,
    lengthChange: false,
    columnDefs: [{ orderable: false, targets: 0, visible: false }],
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
      buttons: [
        {
          extend: "excelHtml5",
          text: '<i class="fas fa-cloud-download-alt"></i> Descargar excel',
          className: "btn-table-custom--turquoise",
          titleAttr: "Excel",
        },
      ],
    },
    ajax: {
      url: "../../php/funciones.php",
      data: {
        clase: "get_data",
        funcion: "get_contactoPaqueteriaTable",
        data: id,
        data2: _permissionsEdit,
        data3: _permissionsDelete,
      },
    },
    columns: [
      { data: "Id" },
      { data: "Nombre" },
      { data: "Apellido" },
      { data: "Puesto" },
      { data: "TelefonoFijo" },
      { data: "Celular" },
      { data: "Email" },
      { data: "Acciones", width: "5%" },
    ],
  });
}

$(document).on("click", "#btnAnadirContacto", function () {
  var email = $("#txtEmailContacto").val();
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "validar_Paqueteria_contacto",
      data: email,
      data2: _global.pkPaqueteria,
    },
    dataType: "json",
    success: function (data) {
      if (parseInt(data[0]["existe"]) == 1) {
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/notificacion_error.svg",
          msg: "¡El contacto ya se encuentra registrado en el sistema!",
          sound: "../../../../../sounds/sound4",
        });
      } else {
        anadirContacto();
      }
    },
  });
});

$(document).on("click", "#btnEditarContacto", function () {
  var email = $("#txtEmailContactoEdit").val();
  var emailOld = _global.emailContactoHis;
  if (email != emailOld) {
    $.ajax({
      url: "../../php/funciones.php",
      data: {
        clase: "get_data",
        funcion: "validar_Paqueteria_contacto",
        data: email,
        data2: _global.pkPaqueteria,
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
            msg: "¡El email ya esta registrado en el sistema!",
            sound: "../../../../../sounds/sound4",
          });
        } else {
          editarContacto();
        }
      },
    });
  } else {
    editarContacto();
  }
});

function anadirContacto() {
  if ($("#formDatosContacto")[0].checkValidity()) {
    var badNombreCont =
      $("#invalid-nombreCont").css("display") === "block" ? false : true;
    var badEmailCont =
      $("#invalid-emailCont").css("display") === "block" ? false : true;

    if (badNombreCont && badEmailCont) {
      var datos = {
        nombreContacto: $("#txtNombreContacto").val(),
        apellidoContacto: $("#txtApellidoContacto").val(),
        puesto: $("#txtPuesto").val(),
        telefono: $("#txtTelefono").val(),
        celular: $("#txtCelular").val(),
        email: $("#txtEmailContacto").val(),
        pkPaqueteria: _global.pkPaqueteria,
        isEdit: 0,
      };

      $.ajax({
        url: "../../php/funciones.php",
        data: {
          clase: "save_data",
          funcion: "save_datosPaqueteria_Contacto",
          datos,
        },
        dataType: "json",
        success: function (respuesta) {
          if (respuesta[0].status) {
            $("#tblListadoDatosContactoPaqueteria").DataTable().ajax.reload();
            Lobibox.notify("success", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../../img/timdesk/checkmark.svg",
              msg: "¡Datos de contacto registrados correctamente!",
              sound: "../../../../../sounds/sound4",
            });

            $("#txtNombreContacto").val("");
            $("#txtApellidoContacto").val("");
            $("#txtPuesto").val("");
            $("#txtTelefono").val("");
            $("#txtCelular").val("");
            $("#txtEmailContacto").val("");
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
    if (!$("#txtNombreContacto").val()) {
      $("#invalid-nombreCont").css("display", "block");
      $("#txtNombreContacto").addClass("is-invalid");
    } else {
      $("#invalid-nombreCont").css("display", "none");
      $("#txtNombreContacto").removeClass("is-invalid");
    }
    if (!$("#txtEmailContacto").val()) {
      $("#invalid-emailCont").css("display", "block");
      $("#txtEmailContacto").addClass("is-invalid");
    } else {
      $("#invalid-emailCont").css("display", "none");
      $("#txtEmailContacto").removeClass("is-invalid");
    }
  }
}

function editarContacto() {
  if ($("#formDatosContactoEdit")[0].checkValidity()) {
    var badNombreCont =
      $("#invalid-nombreContEdit").css("display") === "block" ? false : true;
    var badEmailCont =
      $("#invalid-emailContEdit").css("display") === "block" ? false : true;

    if (badNombreCont && badEmailCont) {
      var datos = {
        nombreContacto: $("#txtNombreContactoEdit").val(),
        apellidoContacto: $("#txtApellidoContactoEdit").val(),
        puesto: $("#txtPuestoEdit").val(),
        telefono: $("#txtTelefonoEdit").val(),
        celular: $("#txtCelularEdit").val(),
        email: $("#txtEmailContactoEdit").val(),
        pkPaqueteria: _global.pkPaqueteria,
        isEdit: _global.pkContacto,
      };

      $.ajax({
        url: "../../php/funciones.php",
        data: {
          clase: "save_data",
          funcion: "save_datosPaqueteria_Contacto",
          datos,
        },
        dataType: "json",
        success: function (respuesta) {
          if (respuesta[0].status) {
            $("#tblListadoDatosContactoPaqueteria").DataTable().ajax.reload();
            Lobibox.notify("success", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../../img/timdesk/checkmark.svg",
              msg: "¡Datos de contacto actualizados correctamente!",
              sound: "../../../../../sounds/sound4",
            });

            $("#txtNombreContactoEdit").val("");
            $("#txtApellidoContactoEdit").val("");
            $("#txtPuestoEdit").val("");
            $("#txtTelefonoEdit").val("");
            $("#txtCelularEdit").val("");
            $("#txtEmailContactoEdit").val("");
            _global.pkContacto = 0;

            $("#editar_Contacto").modal("toggle");
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
    if (!$("#txtNombreContactoEdit").val()) {
      $("#invalid-nombreContEdit").css("display", "block");
      $("#txtNombreContactoEdit").addClass("is-invalid");
    } else {
      $("#invalid-nombreContEdit").css("display", "none");
      $("#txtNombreContactoEdit").removeClass("is-invalid");
    }
    if (!$("#txtEmailContactoEdit").val()) {
      $("#invalid-emailContEdit").css("display", "block");
      $("#txtEmailContactoEdit").addClass("is-invalid");
    } else {
      $("#invalid-emailContEdit").css("display", "none");
      $("#txtEmailContactoEdit").removeClass("is-invalid");
    }
  }
}

function modalDatosEditContacto(pkContacto) {
  _global.pkContacto = pkContacto;

  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_datos_paqueteria_contacto",
      datos: _global.pkContacto,
    },
    dataType: "json",
    success: function (respuesta) {
      $("#txtNombreContactoEdit").val(respuesta[0].Nombres);
      $("#txtApellidoContactoEdit").val(respuesta[0].Apellidos);
      $("#txtPuestoEdit").val(respuesta[0].Puesto);
      $("#txtTelefonoEdit").val(respuesta[0].Telefono);
      $("#txtCelularEdit").val(respuesta[0].Celular);
      $("#txtEmailContactoEdit").val(respuesta[0].Email);
      _global.emailContactoHis = respuesta[0].Email;
    },
    error: function (error) {
      console.log(error);
    },
  });
}

$(document).on("click", "#btnEliminarContacto", function () {
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "delete_data",
      funcion: "delete_paqueteria_contacto",
      datos: _global.pkContacto,
    },
    dataType: "json",
    success: function (respuesta) {
      if (respuesta[0].status) {
        $("#tblListadoDatosContactoPaqueteria").DataTable().ajax.reload();
        Lobibox.notify("success", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/checkmark.svg",
          msg: "¡Se eliminó el contacto con éxito!",
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
});

$(document).on("click", "#btnContinuarCuentasBancarias", function () {
  SeguirCuentasBancarias(_global.pkPaqueteria);
});

function SeguirCuentasBancarias(id) {
  _global.pkPaqueteria = id;
  validarEmpresaPaqueteria(id);

  resetTabs("#CargarDatosCuentasBancarias");

  cargarCMBBanco("", "cmbBanco");
  cargarCMBMonedaCostoUnitario(100, "cmbCostoUniVentaEspecial");

  var html = `<div class="card shadow mb-4">
                <div class="card-header">
                  Tarjeta de cuentas bancarias
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-12">
                      <form id="formDatosCuentasBancarias"> 
                        <div class="form-group">
                          <div class="row">
                            <div class="col-lg-3">
                              <label for="usr">Banco:*</label>
                              <div class="col-lg-12 input-group">
                                  <select name="cmbBanco" id="cmbBanco" required="" onchange="validEmptyInput('cmbBanco', 'invalid-banco', 'La cuenta debe tener un banco.')">
                                  </select>
                                  <div class="invalid-feedback" id="invalid-banco">La cuenta debe tener un banco.</div>
                              </div> 
                            </div>
                            <div class="col-lg-3">
                              <label for="usr">No. de cuenta:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control numeric-only" type="text" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" minlength="10" maxlength="20" name="txtNoCuenta" id="txtNoCuenta" autofocus="" required="" placeholder="Ej. 0000000000" onchange="validarNoCuenta('txtNoCuenta','invalid-noCuenta')">
                                  <div class="invalid-feedback" id="invalid-noCuenta">La cuenta debe tener un número.</div>
                                </div>
                              </div>  
                            </div>
                            <div class="col-lg-3">
                              <label for="usr">CLABE:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control numeric-only" type="text" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" name="txtCLABE" id="txtCLABE" minlength="18" maxlength="18" autofocus="" required="" placeholder="Ej. 000 000 0000000000 0" onchange="validarCLABE('txtCLABE','invalid-clabe')">
                                  <div class="invalid-feedback" id="invalid-clabe">La cuenta debe tener una clabe.</div>
                                </div>
                              </div>      
                            </div>
                            <div class="col-lg-3">
                              <label for="usr">Moneda:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <select name="cmbCostoUniVentaEspecial" id="cmbCostoUniVentaEspecial" required=""  onchange="validEmptyInput('cmbCostoUniVentaEspecial', 'invalid-moneda', 'La cuenta debe tener un tipo de moneda.')">
                                  </select>
                                  <div class="invalid-feedback" id="invalid-moneda">La cuenta debe tener un tipo de moneda.</div>
                                </div>
                              </div> 
                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="row">
                            <div class="col-lg-10">
                            </div>
                            <div class="col-lg-2" style="text-align:center!important; margin-top:35px;">
                              <a href="#" class="btn-custom btn-custom--border-blue" id="btnAnadirCuentaBancaria">Añadir cuenta bancaria</a>
                            </div>
                          </div>
                        </div>
                        <label for="">* Campos requeridos</label>
                        <div class="form-group">
                          <!-- DataTales Example -->
                          <div class="card mb-4">
                            <div class="card-body">
                              <div class="table-responsive">
                                <table class="table" id="tblListadoDatosBancariosPaqueteria" width="100%" cellspacing="0">
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
                      </form>
                    </div>
                  </div>
                </div>
              </div>`;

  $("#datos").html(html);

  setTimeout(function () {
    new SlimSelect({
      select: `#cmbBanco`,
      deselectLabel: '<span class="">✖</span>',
    });

    new SlimSelect({
      select: `#cmbCostoUniVentaEspecial`,
      deselectLabel: '<span class="">✖</span>',
    });

    new SlimSelect({
      select: `#cmbBancoEdit`,
      deselectLabel: '<span class="">✖</span>',
    });

    new SlimSelect({
      select: `#cmbCostoUniVentaEspecialEdit`,
      deselectLabel: '<span class="">✖</span>',
    });
  }, 500);

  cargarTablaBancos(id, _permissions.edit, _permissions.delete);
  resetValidations();
}

function cargarCMBBanco(data, input) {
  var html = "";
  var selected;
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_banco" },
    dataType: "json",
    success: function (respuesta) {
      $.each(respuesta, function (i) {
        if (data === respuesta[i].PKBanco) {
          selected = "selected";
        } else {
          selected = "";
        }

        html += `<option value="${respuesta[i].PKBanco}" ${selected}>${respuesta[i].Banco}</option>`;
      });
      $(`#${input}`).html(html);
    },
    error: function (error) {
      console.log(error);
    },
  });
}
function cargarCMBMonedaCostoUnitario(data, input) {
  var html = "";
  var selected;
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_vehiculo_monedaCU" },
    dataType: "json",
    success: function (respuesta) {
      $.each(respuesta, function (i) {
        if (data === respuesta[i].PKTipoMoneda) {
          selected = "selected";
        } else {
          selected = "";
        }

        html += `<option value="${respuesta[i].PKTipoMoneda}" ${selected}> 
                  ${respuesta[i].TipoMoneda}
                </option>`;
      });

      $(`#${input}`).html(html);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function cargarTablaBancos(id, _permissionsEdit, _permissionsDelete) {
  $("#tblListadoDatosBancariosPaqueteria").dataTable({
    language: setFormatDatatables(),
    info: false,
    scrollX: true,
    bSort: false,
    pageLength: 15,
    responsive: true,
    lengthChange: false,
    columnDefs: [{ orderable: false, targets: 0, visible: false }],
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
      buttons: [
        {
          extend: "excelHtml5",
          text: '<i class="fas fa-cloud-download-alt"></i> Descargar excel',
          className: "btn-table-custom--turquoise",
          titleAttr: "Excel",
        },
      ],
    },
    ajax: {
      url: "../../php/funciones.php",
      data: {
        clase: "get_data",
        funcion: "get_cuentaBancariaPaqueteriaTable",
        data: id,
        data2: _permissionsEdit,
        data3: _permissionsDelete,
      },
    },
    columns: [
      { data: "Id" },
      { data: "Banco" },
      { data: "NoCuenta" },
      { data: "CLABE" },
      { data: "Moneda" },
      { data: "Acciones", width: "15%" },
    ],
  });
}

function validarNoCuenta(inputID, invalidDivID) {
  var noCuenta = $("#txtNoCuenta").val();
  if (noCuenta != "") {
    if (validaCCC(noCuenta)) {
      $.ajax({
        url: "../../php/funciones.php",
        data: {
          clase: "get_data",
          funcion: "validar_Paqueteria_noCuenta",
          data: noCuenta,
        },
        dataType: "json",
        success: function (data) {
          if (parseInt(data[0]["existe"]) == 1) {
            $("#" + invalidDivID).text(
              "El número de cuenta ya esta registrado."
            );
            $("#" + invalidDivID).css("display", "block");
            $("#" + inputID).addClass("is-invalid");
          } else {
            $("#" + invalidDivID).text("La cuenta debe tener un número.");
            $("#" + invalidDivID).css("display", "none");
            $("#" + inputID).removeClass("is-invalid");
          }
        },
      });
    } else {
      $("#" + invalidDivID).text("El número de cuenta no es valido.");
      $("#" + invalidDivID).css("display", "block");
      $("#" + inputID).addClass("is-invalid");
    }
  } else {
    $("#" + invalidDivID).text("La cuenta debe tener un número.");
    $("#" + invalidDivID).css("display", "block");
    $("#" + inputID).addClass("is-invalid");
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

function validarCLABE(inputID, invalidDivID) {
  var clabe = $("#txtCLABE").val();
  if (clabe != "") {
    if (validaBBB(clabe)) {
      $.ajax({
        url: "../../php/funciones.php",
        data: {
          clase: "get_data",
          funcion: "validar_Paqueteria_CLABE",
          data: clabe,
        },
        dataType: "json",
        success: function (data) {
          if (parseInt(data[0]["existe"]) == 1) {
            $("#" + invalidDivID).text("La clabe ya esta registrado.");
            $("#" + invalidDivID).css("display", "block");
            $("#" + inputID).addClass("is-invalid");
          } else {
            $("#" + invalidDivID).text("La cuenta debe tener una clabe.");
            $("#" + invalidDivID).css("display", "none");
            $("#" + inputID).removeClass("is-invalid");
          }
        },
      });
    } else {
      $("#" + invalidDivID).text("La clabe debe ser valida.");
      $("#" + invalidDivID).css("display", "block");
      $("#" + inputID).addClass("is-invalid");
    }
  } else {
    $("#" + invalidDivID).text("La cuenta debe tener una clabe.");
    $("#" + invalidDivID).css("display", "block");
    $("#" + inputID).addClass("is-invalid");
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

$(document).on("click", "#btnAnadirCuentaBancaria", function () {
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
      funcion: "validar_Paqueteria_datosBancarios",
      data: pkBanco,
      data2: noCuenta,
      data3: clabe,
      data4: _global.pkPaqueteria,
    },
    dataType: "json",
    success: function (data) {
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
          sound: "../../../../../sounds/sound4",
        });
      } else {
        anadirBanco();
      }
    },
  });
});

$(document).on("click", "#btnEditarCuentaBancaria", function () {
  var pkBanco = 0;
  if ($("#cmbBancoEdit").val() == "" || $("#cmbBancoEdit").val() == null) {
    pkBanco = 0;
  } else {
    pkBanco = $("#cmbBancoEdit").val();
  }

  var noCuenta = $("#txtNoCuentaEdit").val();
  var noCuentaOld = _global.cuentaHis;
  var clabe = $("#txtCLABEEdit").val();
  var clabeOld = _global.clabeHis;

  if (noCuenta != noCuentaOld || clabe != clabeOld) {
    $.ajax({
      url: "../../php/funciones.php",
      data: {
        clase: "get_data",
        funcion: "validar_Paqueteria_datosBancarios",
        data: pkBanco,
        data2: noCuenta,
        data3: clabe,
        data4: _global.pkPaqueteria,
      },
      dataType: "json",
      success: function (data) {
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
            sound: "../../../../../sounds/sound4",
          });
        } else {
          editarBanco();
        }
      },
    });
  } else {
    editarBanco();
  }
});

function anadirBanco() {
  if ($("#formDatosCuentasBancarias")[0].checkValidity()) {
    var badBanco =
      $("#invalid-cmbBanco").css("display") === "block" ? false : true;
    var badNoCuenta =
      $("#invalid-noCuenta").css("display") === "block" ? false : true;
    var badClabe =
      $("#invalid-clabe").css("display") === "block" ? false : true;
    var badMoneda =
      $("#invalid-moneda").css("display") === "block" ? false : true;

    if (badBanco && badNoCuenta && badClabe && badMoneda) {
      var datos = {
        banco: $("#cmbBanco").val(),
        noCuenta: $("#txtNoCuenta").val(),
        clabe: $("#txtCLABE").val(),
        moneda: $("#cmbCostoUniVentaEspecial").val(),
        pkPaqueteria: _global.pkPaqueteria,
        isEdit: 0,
      };

      $.ajax({
        url: "../../php/funciones.php",
        data: {
          clase: "save_data",
          funcion: "save_datosPaqueteria_CuentaBancaria",
          datos,
        },
        dataType: "json",
        success: function (respuesta) {
          if (respuesta[0].status) {
            $("#tblListadoDatosBancariosPaqueteria").DataTable().ajax.reload();
            Lobibox.notify("success", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../../img/timdesk/checkmark.svg",
              msg: "¡Datos de cuenta registrados correctamente!",
              sound: "../../../../../sounds/sound4",
            });

            cargarCMBBanco(1, "cmbBanco");
            $("#txtNoCuenta").val("");
            $("#txtCLABE").val("");
            cargarCMBMonedaCostoUnitario(100, "cmbCostoUniVentaEspecial");
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
    if (!$("#cmbBanco").val()) {
      $("#invalid-banco").css("display", "block");
      $("#cmbBanco").addClass("is-invalid");
    } else {
      $("#invalid-banco").css("display", "none");
      $("#cmbBanco").removeClass("is-invalid");
    }
    if (!$("#txtNoCuenta").val()) {
      $("#invalid-noCuenta").css("display", "block");
      $("#txtNoCuenta").addClass("is-invalid");
    } else {
      $("#invalid-noCuenta").css("display", "none");
      $("#txtNoCuenta").removeClass("is-invalid");
    }
    if (!$("#txtCLABE").val()) {
      $("#invalid-clabe").css("display", "block");
      $("#txtCLABE").addClass("is-invalid");
    } else {
      $("#invalid-clabe").css("display", "none");
      $("#txtCLABE").removeClass("is-invalid");
    }
    if (!$("#cmbCostoUniVentaEspecial").val()) {
      $("#invalid-moneda").css("display", "block");
      $("#cmbCostoUniVentaEspecial").addClass("is-invalid");
    } else {
      $("#invalid-moneda").css("display", "none");
      $("#cmbCostoUniVentaEspecial").removeClass("is-invalid");
    }
  }
}

function editarBanco() {
  if ($("#formDatosCuentasBancariasEdit")[0].checkValidity()) {
    var badBanco =
      $("#invalid-cmbBancoEdit").css("display") === "block" ? false : true;
    var badNoCuenta =
      $("#invalid-noCuentaEdit").css("display") === "block" ? false : true;
    var badClabe =
      $("#invalid-clabeEdit").css("display") === "block" ? false : true;
    var badMoneda =
      $("#invalid-monedaEdit").css("display") === "block" ? false : true;

    if (badBanco && badNoCuenta && badClabe && badMoneda) {
      var datos = {
        banco: $("#cmbBancoEdit").val(),
        noCuenta: $("#txtNoCuentaEdit").val(),
        clabe: $("#txtCLABEEdit").val(),
        moneda: $("#cmbCostoUniVentaEspecialEdit").val(),
        pkPaqueteria: _global.pkPaqueteria,
        isEdit: _global.pkCuentaBancaria,
      };

      $.ajax({
        url: "../../php/funciones.php",
        data: {
          clase: "save_data",
          funcion: "save_datosPaqueteria_CuentaBancaria",
          datos,
        },
        dataType: "json",
        success: function (respuesta) {
          if (respuesta[0].status) {
            $("#tblListadoDatosBancariosPaqueteria").DataTable().ajax.reload();
            Lobibox.notify("success", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../../img/timdesk/checkmark.svg",
              msg: "¡Datos de cuenta registrados correctamente!",
              sound: "../../../../../sounds/sound4",
            });

            cargarCMBBanco(1, "cmbBancoEdit");
            $("#txtNoCuentaEdit").val("");
            $("#txtCLABEEdit").val("");
            cargarCMBMonedaCostoUnitario(100, "cmbCostoUniVentaEspecialEdit");
            _global.pkCuentaBancaria = 0;
            $("#editar_CuentaBancancaria").modal("toggle");
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
    if (!$("#cmbBancoEdit").val()) {
      $("#invalid-bancoEdit").css("display", "block");
      $("#cmbBancoEdit").addClass("is-invalid");
    } else {
      $("#invalid-bancoEdit").css("display", "none");
      $("#cmbBancoEdit").removeClass("is-invalid");
    }
    if (!$("#txtNoCuentaEdit").val()) {
      $("#invalid-noCuentaEdit").css("display", "block");
      $("#txtNoCuentaEdit").addClass("is-invalid");
    } else {
      $("#invalid-noCuentaEdit").css("display", "none");
      $("#txtNoCuentaEdit").removeClass("is-invalid");
    }
    if (!$("#txtCLABEEdit").val()) {
      $("#invalid-clabeEdit").css("display", "block");
      $("#txtCLABEEdit").addClass("is-invalid");
    } else {
      $("#invalid-clabeEdit").css("display", "none");
      $("#txtCLABEEdit").removeClass("is-invalid");
    }
    if (!$("#cmbCostoUniVentaEspecialEdit").val()) {
      $("#invalid-monedaEdit").css("display", "block");
      $("#cmbCostoUniVentaEspecialEdit").addClass("is-invalid");
    } else {
      $("#invalid-monedaEdit").css("display", "none");
      $("#cmbCostoUniVentaEspecialEdit").removeClass("is-invalid");
    }
  }
}

function modalDatosEditCuentaBancaria(pkCuentaBancaria) {
  _global.pkCuentaBancaria = pkCuentaBancaria;

  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_datos_paqueteria_cuentaBancaria",
      datos: _global.pkCuentaBancaria,
    },
    dataType: "json",
    success: function (respuesta) {
      cargarCMBBanco(respuesta[0].FKBanco, "cmbBancoEdit");
      $("#txtNoCuentaEdit").val(respuesta[0].NoCuenta);
      $("#txtCLABEEdit").val(respuesta[0].CLABEs);
      cargarCMBMonedaCostoUnitario(
        respuesta[0].FKMoneda,
        "cmbCostoUniVentaEspecialEdit"
      );

      _global.cuentaHis = respuesta[0].NoCuenta;
      _global.clabeHis = respuesta[0].CLABEs;
    },
    error: function (error) {
      console.log(error);
    },
  });
}

$(document).on("click", "#btnEliminarCuentaBancaria", function () {
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "delete_data",
      funcion: "delete_paqueteria_cuentaBancaria",
      datos: _global.pkCuentaBancaria,
    },
    dataType: "json",
    success: function (respuesta) {
      if (respuesta[0].status) {
        $("#tblListadoDatosBancariosPaqueteria").DataTable().ajax.reload();
        Lobibox.notify("success", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/checkmark.svg",
          msg: "¡Se eliminó la cuenta con éxito!",
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
});

$(document).on("click", "#btnContinuarSucursales", function () {
  SeguirSucursales(_global.pkPaqueteria);
});

function SeguirSucursales(id) {
  _global.pkPaqueteria = id;

  validarEmpresaPaqueteria(id);

  resetTabs("#CargarSucursales");

  cargarCMBPaises(241, "cmbPaisSucursal");
  cargarCMBEstados(241, "cmbEstadoSucursal", "");

  var html = `<div class="card shadow mb-4">
                <div class="card-header">
                  Tarjeta de direcciones de envío
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-12">
                      <form id="formDatosSucursales"> 
                        <span id="areaDiseno">
                        <div class="form-group">
                          <div class="row">
                            <div class="col-lg-4">
                              <label for="usr">Sucursal:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control" type="text" name="txtSucursal" id="txtSucursal" autofocus="" required="" maxlength="255" placeholder="Ej. Nogales" onchange="escribirSucursal('txtSucursal','invalid-sucursal')">
                                  <div class="invalid-feedback" id="invalid-sucursal">La dirección debe tener un nombre de sucursal.</div>
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <label for="usr">Contacto:</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control" type="text" name="txtContacto" id="txtContacto" maxlength="255" placeholder="Ej. José María Lopéz Pérez" >
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <label for="usr">Teléfono:</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control numeric-only" type="text" name="txtTelefono" id="txtTelefono" minlength="7" maxlength="10" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" placeholder="Ej. 123 456 7890">
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
                                  <input class="form-control alphaNumeric-only" type="email" name="txtEmailSucursal" id="txtEmailSucursal" required maxlength="100" placeholder="Ej. ejemplo@dominio.com" onchange="validarCorreo(this.value, 'txtEmailSucursal', 'invalid-emailSucursal');">
                                  <div class="invalid-feedback" id="invalid-emailSucursal">La dirección debe tener un email.</div>
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <label for="usr">Calle:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control" type="text" name="txtCalleSucursal" id="txtCalleSucursal" required maxlength="255" placeholder="Ej. Av. México" onkeyup="validEmptyInput('txtCalleSucursal', 'invalid-calleSucursal', 'La dirección debe tener una calle.')">
                                  <div class="invalid-feedback" id="invalid-calleSucursal">La dirección debe tener una calle.</div>
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-2">
                              <label for="usr">Número exterior:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control alphaNumeric-only" type="text" name="txtNumExtSucursal" id="txtNumExtSucursal" required maxlength="10" placeholder="Ej. 2353 A" onkeyup="validEmptyInput('txtNumExtSucursal', 'invalid-numExtSucursal', 'La dirección debe tener un número exterior.')">
                                  <div class="invalid-feedback" id="invalid-numExtSucursal">La dirección debe tener un número exterior.</div>
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-2">
                              <label for="usr">Número interior:</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control alphaNumeric-only" type="text" name="txtNumIntSucursal" id="txtNumIntSucursal" maxlength="10" placeholder="Ej. 524">
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
                                  <input class="form-control" type="text" name="txtColoniaSucursal" id="txtColoniaSucursal" required maxlength="255" placeholder="Ej. Los Agaves" onkeyup="validEmptyInput('txtColoniaSucursal', 'invalid-coloniaSucursal', 'La dirección debe tener una colonia.')">
                                  <div class="invalid-feedback" id="invalid-coloniaSucursal">La dirección debe tener una colonia.</div>
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-3">
                              <label for="usr">Municipio:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control" type="text" name="txtMunicipioSucursal" id="txtMunicipioSucursal" required maxlength="255" placeholder="Ej. Guadalajara" onkeyup="validEmptyInput('txtMunicipioSucursal', 'invalid-municipioSucursal', 'La direccion debe tener un municipio.')">
                                  <div class="invalid-feedback" id="invalid-municipioSucursal">La direccion debe tener un municipio.</div>
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-2">
                              <label for="usr">País:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <select name="cmbPaisSucursal" id="cmbPaisSucursal" required onchange="cambioPais('cmbPaisSucursal','invalid-paisSucursal','cmbEstadoSucursal')">
                                  </select>
                                  <div class="invalid-feedback" id="invalid-paisSucursal">La dirección debe tener un pais.</div>
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-2">
                              <label for="usr">Estado:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <select name="cmbEstadoSucursal" id="cmbEstadoSucursal" required>
                                  </select>
                                  <div class="invalid-feedback" id="invalid-estadoSucursal">La dirección debe tener un estado.</div>
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-2">
                              <label for="usr">Código Postal:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control numeric-only" type="text" name="txtCPSucursal" id="txtCPSucursal" required maxlength="5" placeholder="Ej. 52632" onchange="validarCP('txtCPSucursal','invalid-cpSucursal');">
                                  <div class="invalid-feedback" id="invalid-cpSucursal">La dirección debe tener un CP.</div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="row">
                            <div class="col-lg-10">
                            </div>
                            <div class="col-lg-2" style="text-align:center!important; margin-top:35px;">
                              <a href="#" class="btn-custom btn-custom--border-blue" id="btnAnadirSucursal">Añadir sucursal</a>
                            </div>
                          </div>
                        </div>
                        <label for="">* Campos requeridos</label>
                        <div class="form-group">
                          <div class="row">
                            <div class="col-lg-10">
                            </div>
                            <div class="col-lg-2" style="text-align:center!important; margin-top:35px;">
                              <a href="#" class="btn-custom btn-custom--blue float-right" id="btnTerminarRegistroPaqueteria">Terminar </a>
                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <!-- DataTales Example -->
                          <div class="card mb-4">
                            <div class="card-body">
                              <div class="table-responsive">
                                <table class="table" id="tblListadoDatosSucursalesPaqueteria" width="100%" cellspacing="0">
                                  <thead>
                                    <tr>
                                      <th>Id</th>
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
  cargarTablaSucursales(id, _permissions.edit, _permissions.delete);
  resetValidations();

  setTimeout(function () {
    new SlimSelect({
      select: "#cmbPaisSucursal",
      deselectLabel: '<span class="">✖</span>',
    });

    new SlimSelect({
      select: "#cmbEstadoSucursal",
      deselectLabel: '<span class="">✖</span>',
      addable: function (value) {
        var pkPais = $("#cmbPaisSucursal").val();
        validarEstado(value, pkPais);
      },
    });

    new SlimSelect({
      select: "#cmbPaisSucursalEdit",
      deselectLabel: '<span class="">✖</span>',
    });

    new SlimSelect({
      select: "#cmbEstadoSucursalEdit",
      deselectLabel: '<span class="">✖</span>',
      addable: function (value) {
        var pkPais = $("#cmbPaisSucursal").val();
        validarEstado(value, pkPais);
      },
    });
  }, 500);
}

function cargarTablaSucursales(id, _permissionsEdit, _permissionsDelete) {
  $("#tblListadoDatosSucursalesPaqueteria").dataTable({
    language: setFormatDatatables(),
    info: false,
    scrollX: true,
    bSort: false,
    pageLength: 15,
    responsive: true,
    lengthChange: false,
    columnDefs: [{ orderable: false, targets: 0, visible: false }],
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
      buttons: [
        {
          extend: "excelHtml5",
          text: '<i class="fas fa-cloud-download-alt"></i> Descargar excel',
          className: "btn-table-custom--turquoise",
          titleAttr: "Excel",
        },
      ],
    },
    ajax: {
      url: "../../php/funciones.php",
      data: {
        clase: "get_data",
        funcion: "get_sucursalesPaqueteriaTable",
        data: id,
        data2: _permissionsEdit,
        data3: _permissionsDelete,
      },
    },
    columns: [
      { data: "Id" },
      { data: "Sucursal" },
      { data: "Email" },
      { data: "Calle" },
      { data: "NumeroExt" },
      { data: "NumeroInt" },
      { data: "Colonia" },
      { data: "Municipio" },
      { data: "Estado" },
      { data: "Pais" },
      { data: "CP" },
      { data: "Acciones", width: "15%" },
    ],
  });
}

function escribirSucursal(inputID, invalidDivID) {
  var sucursal = $("#" + inputID).val();

  if (sucursal != _global.sucursalHis) {
    $.ajax({
      url: "../../php/funciones.php",
      data: {
        clase: "get_data",
        funcion: "validar_Paqueteria_sucursal",
        data: sucursal,
        data2: _global.pkPaqueteria,
      },
      dataType: "json",
      success: function (data) {
        if (parseInt(data[0]["existe"]) == 1) {
          $("#" + invalidDivID).css("display", "block");
          $("#" + invalidDivID).text(
            "La sucursal ya esta registrada en el sistema."
          );
          $("#" + inputID).addClass("is-invalid");
        } else {
          $("#" + invalidDivID).css("display", "none");
          $("#" + invalidDivID).text(
            "La sucursal debe tener un nombre de sucursal."
          );
          $("#" + inputID).removeClass("is-invalid");
          if (!sucursal) {
            $("#" + invalidDivID).css("display", "block");
            $("#" + invalidDivID).text(
              "La sucursal debe tener un nombre de sucursal."
            );
            $("#" + inputID).addClass("is-invalid");
          }
        }
      },
    });
  }
}

$(document).on("click", "#btnAnadirSucursal", function () {
  if ($("#formDatosSucursales")[0].checkValidity()) {
    var badSucursal =
      $("#invalid-sucursal").css("display") === "block" ? false : true;
    var badEmail =
      $("#invalid-emailSucursal").css("display") === "block" ? false : true;
    var badCalle =
      $("#invalid-calleSucursal").css("display") === "block" ? false : true;
    var badNumExt =
      $("#invalid-numExtSucursal").css("display") === "block" ? false : true;
    var badColinia =
      $("#invalid-coloniaSucursal").css("display") === "block" ? false : true;
    var badMunicipio =
      $("#invalid-municipioSucursal").css("display") === "block" ? false : true;
    var badPais =
      $("#invalid-paisSucursal").css("display") === "block" ? false : true;
    var badEstado =
      $("#invalid-estadoSucursal").css("display") === "block" ? false : true;
    var badCP =
      $("#invalid-cpSucursal").css("display") === "block" ? false : true;

    if (
      badSucursal &&
      badEmail &&
      badCalle &&
      badNumExt &&
      badColinia &&
      badMunicipio &&
      badPais &&
      badEstado &&
      badCP
    ) {
      var datos = {
        sucursal: $("#txtSucursal").val(),
        contacto: $("#txtContacto").val(),
        telefono: $("#txtTelefono").val(),
        email: $("#txtEmailSucursal").val(),
        calle: $("#txtCalleSucursal").val(),
        numeroExt: $("#txtNumExtSucursal").val(),
        numeroInt: $("#txtNumIntSucursal").val(),
        colonia: $("#txtColoniaSucursal").val(),
        municipio: $("#txtMunicipioSucursal").val(),
        pais: $("#cmbPaisSucursal").val(),
        estado: $("#cmbEstadoSucursal").val(),
        cp: $("#txtCPSucursal").val(),
        pkPaqueteria: _global.pkPaqueteria,
        isEdit: 0,
      };

      $.ajax({
        url: "../../php/funciones.php",
        data: {
          clase: "save_data",
          funcion: "save_datosPaqueteria_Sucursal",
          datos,
        },
        dataType: "json",
        success: function (respuesta) {
          if (respuesta[0].status) {
            $("#tblListadoDatosSucursalesPaqueteria").DataTable().ajax.reload();
            Lobibox.notify("success", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../../img/timdesk/checkmark.svg",
              msg: "¡Datos de sucursal registrados correctamente!",
              sound: "../../../../../sounds/sound4",
            });

            $("#txtSucursal").val("");
            $("#txtContacto").val("");
            $("#txtTelefono").val("");
            $("#txtEmailSucursal").val("");
            $("#txtCalleSucursal").val("");
            $("#txtNumExtSucursal").val("");
            $("#txtNumIntSucursal").val("");
            $("#txtColoniaSucursal").val("");
            $("#txtMunicipioSucursal").val("");
            cargarCMBPaises(241, "cmbPaisSucursal");
            cargarCMBEstados(241, "cmbEstadoSucursal", "");
            $("#txtCPSucursal").val("");
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
    if (!$("#txtSucursal").val()) {
      $("#invalid-sucursal").css("display", "block");
      $("#txtSucursal").addClass("is-invalid");
    } else {
      $("#invalid-sucursal").css("display", "none");
      $("#txtSucursal").removeClass("is-invalid");
    }
    if (!$("#txtEmailSucursal").val()) {
      $("#invalid-emailSucursal").css("display", "block");
      $("#txtEmailSucursal").addClass("is-invalid");
    } else {
      $("#invalid-emailSucursal").css("display", "none");
      $("#txtEmailSucursal").removeClass("is-invalid");
    }
    if (!$("#txtCalleSucursal").val()) {
      $("#invalid-calleSucursal").css("display", "block");
      $("#txtCalleSucursal").addClass("is-invalid");
    } else {
      $("#invalid-calleSucursal").css("display", "none");
      $("#txtCalleSucursal").removeClass("is-invalid");
    }
    if (!$("#txtNumExtSucursal").val()) {
      $("#invalid-numExtSucursal").css("display", "block");
      $("#txtNumExtSucursal").addClass("is-invalid");
    } else {
      $("#invalid-numExtSucursal").css("display", "none");
      $("#txtNumExtSucursal").removeClass("is-invalid");
    }
    if (!$("#txtColoniaSucursal").val()) {
      $("#invalid-coloniaSucursal").css("display", "block");
      $("#txtColoniaSucursal").addClass("is-invalid");
    } else {
      $("#invalid-coloniaSucursal").css("display", "none");
      $("#txtColoniaSucursal").removeClass("is-invalid");
    }
    if (!$("#txtMunicipioSucursal").val()) {
      $("#invalid-municipioSucursal").css("display", "block");
      $("#txtMunicipioSucursal").addClass("is-invalid");
    } else {
      $("#invalid-municipioSucursal").css("display", "none");
      $("#txtMunicipioSucursal").removeClass("is-invalid");
    }
    if (!$("#cmbPaisSucursal").val()) {
      $("#invalid-paisSucursal").css("display", "block");
      $("#cmbPaisSucursal").addClass("is-invalid");
    } else {
      $("#invalid-paisSucursal").css("display", "none");
      $("#cmbPaisSucursal").removeClass("is-invalid");
    }
    if (!$("#cmbEstadoSucursal").val()) {
      $("#invalid-estadoSucursal").css("display", "block");
      $("#cmbEstadoSucursal").addClass("is-invalid");
    } else {
      $("#invalid-estadoSucursal").css("display", "none");
      $("#cmbEstadoSucursal").removeClass("is-invalid");
    }
    if (!$("#txtCPSucursal").val()) {
      $("#invalid-cpSucursal").css("display", "block");
      $("#txtCPSucursal").addClass("is-invalid");
    } else {
      $("#invalid-cpSucursal").css("display", "none");
      $("#txtCPSucursal").removeClass("is-invalid");
    }
  }
});

function modalDatosEditSucursal(pkSucursal) {
  _global.pkSucursal = pkSucursal;

  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_datos_paqueteria_sucursal",
      datos: _global.pkSucursal,
    },
    dataType: "json",
    success: function (respuesta) {
      $("#txtSucursalEdit").val(respuesta[0].Sucursal);
      $("#txtContactoEdit").val(respuesta[0].Contacto);
      $("#txtTelefonoEdit2").val(respuesta[0].Telefono);
      $("#txtEmailSucursalEdit").val(respuesta[0].Email);
      $("#txtCalleSucursalEdit").val(respuesta[0].Calle);
      $("#txtNumExtSucursalEdit").val(respuesta[0].Numero_exterior);
      $("#txtNumIntSucursalEdit").val(respuesta[0].Numero_Interior);
      $("#txtColoniaSucursalEdit").val(respuesta[0].Colonia);
      $("#txtMunicipioSucursalEdit").val(respuesta[0].Municipio);
      cargarCMBPaises(respuesta[0].Pais, "cmbPaisSucursalEdit");
      cargarCMBEstados(
        respuesta[0].Pais,
        "cmbEstadoSucursalEdit",
        respuesta[0].Estado
      );
      $("#txtCPSucursalEdit").val(respuesta[0].CPs);
      _global.sucursalHis = respuesta[0].Sucursal;
    },
    error: function (error) {
      console.log(error);
    },
  });
}

$(document).on("click", "#btnEditarSucursal", function () {
  if ($("#formDatosSucursalesEdit")[0].checkValidity()) {
    var badSucursal =
      $("#invalid-sucursalEdit").css("display") === "block" ? false : true;
    var badEmail =
      $("#invalid-emailSucursalEdit").css("display") === "block" ? false : true;
    var badCalle =
      $("#invalid-calleSucursalEdit").css("display") === "block" ? false : true;
    var badNumExt =
      $("#invalid-numExtSucursalEdit").css("display") === "block"
        ? false
        : true;
    var badColinia =
      $("#invalid-coloniaSucursalEdit").css("display") === "block"
        ? false
        : true;
    var badMunicipio =
      $("#invalid-municipioSucursalEdit").css("display") === "block"
        ? false
        : true;
    var badPais =
      $("#invalid-paisSucursalEdit").css("display") === "block" ? false : true;
    var badEstado =
      $("#invalid-estadoSucursalEdit").css("display") === "block"
        ? false
        : true;
    var badCP =
      $("#invalid-cpSucursalEdit").css("display") === "block" ? false : true;

    if (
      badSucursal &&
      badEmail &&
      badCalle &&
      badNumExt &&
      badColinia &&
      badMunicipio &&
      badPais &&
      badEstado &&
      badCP
    ) {
      var datos = {
        sucursal: $("#txtSucursalEdit").val(),
        contacto: $("#txtContactoEdit").val(),
        telefono: $("#txtTelefonoEdit2").val(),
        email: $("#txtEmailSucursalEdit").val(),
        calle: $("#txtCalleSucursalEdit").val(),
        numeroExt: $("#txtNumExtSucursalEdit").val(),
        numeroInt: $("#txtNumIntSucursalEdit").val(),
        colonia: $("#txtColoniaSucursalEdit").val(),
        municipio: $("#txtMunicipioSucursalEdit").val(),
        pais: $("#cmbPaisSucursalEdit").val(),
        estado: $("#cmbEstadoSucursalEdit").val(),
        cp: $("#txtCPSucursalEdit").val(),
        pkPaqueteria: _global.pkPaqueteria,
        isEdit: _global.pkSucursal,
      };

      $.ajax({
        url: "../../php/funciones.php",
        data: {
          clase: "save_data",
          funcion: "save_datosPaqueteria_Sucursal",
          datos,
        },
        dataType: "json",
        success: function (respuesta) {
          if (respuesta[0].status) {
            $("#tblListadoDatosSucursalesPaqueteria").DataTable().ajax.reload();
            Lobibox.notify("success", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../../img/timdesk/checkmark.svg",
              msg: "¡Datos de sucursal actualizados correctamente!",
              sound: "../../../../../sounds/sound4",
            });

            $("#txtSucursalEdit").val("");
            $("#txtContactoEdit").val("");
            $("#txtTelefonoEdit2").val("");
            $("#txtEmailSucursalEdit").val("");
            $("#txtCalleSucursalEdit").val("");
            $("#txtNumExtSucursalEdit").val("");
            $("#txtNumIntSucursalEdit").val("");
            $("#txtColoniaSucursalEdit").val("");
            $("#txtMunicipioSucursalEdit").val("");
            cargarCMBPaises(241, "cmbPaisSucursalEdit");
            cargarCMBEstados(241, "cmbEstadoSucursalEdit", "");
            $("#txtCPSucursalEdit").val("");
            _global.pkSucursal = 0;
            $("#editar_Sucursal").modal("toggle");
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
    if (!$("#txtSucursalEdit").val()) {
      $("#invalid-sucursalEdit").css("display", "block");
      $("#txtSucursalEdit").addClass("is-invalid");
    } else {
      $("#invalid-sucursalEdit").css("display", "none");
      $("#txtSucursalEdit").removeClass("is-invalid");
    }
    if (!$("#txtEmailSucursalEdit").val()) {
      $("#invalid-emailSucursalEdit").css("display", "block");
      $("#txtEmailSucursalEdit").addClass("is-invalid");
    } else {
      $("#invalid-emailSucursalEdit").css("display", "none");
      $("#txtEmailSucursalEdit").removeClass("is-invalid");
    }
    if (!$("#txtCalleSucursalEdit").val()) {
      $("#invalid-calleSucursalEdit").css("display", "block");
      $("#txtCalleSucursalEdit").addClass("is-invalid");
    } else {
      $("#invalid-calleSucursalEdit").css("display", "none");
      $("#txtCalleSucursalEdit").removeClass("is-invalid");
    }
    if (!$("#txtNumExtSucursalEdit").val()) {
      $("#invalid-numExtSucursalEdit").css("display", "block");
      $("#txtNumExtSucursalEdit").addClass("is-invalid");
    } else {
      $("#invalid-numExtSucursalEdit").css("display", "none");
      $("#txtNumExtSucursalEdit").removeClass("is-invalid");
    }
    if (!$("#txtColoniaSucursalEdit").val()) {
      $("#invalid-coloniaSucursalEdit").css("display", "block");
      $("#txtColoniaSucursalEdit").addClass("is-invalid");
    } else {
      $("#invalid-coloniaSucursalEdit").css("display", "none");
      $("#txtColoniaSucursalEdit").removeClass("is-invalid");
    }
    if (!$("#txtMunicipioSucursalEdit").val()) {
      $("#invalid-municipioSucursalEdit").css("display", "block");
      $("#txtMunicipioSucursalEdit").addClass("is-invalid");
    } else {
      $("#invalid-municipioSucursalEdit").css("display", "none");
      $("#txtMunicipioSucursalEdit").removeClass("is-invalid");
    }
    if (!$("#cmbPaisSucursalEdit").val()) {
      $("#invalid-paisSucursalEdit").css("display", "block");
      $("#cmbPaisSucursalEdit").addClass("is-invalid");
    } else {
      $("#invalid-paisSucursalEdit").css("display", "none");
      $("#cmbPaisSucursalEdit").removeClass("is-invalid");
    }
    if (!$("#cmbEstadoSucursalEdit").val()) {
      $("#invalid-estadoSucursalEdit").css("display", "block");
      $("#cmbEstadoSucursalEdit").addClass("is-invalid");
    } else {
      $("#invalid-estadoSucursalEdit").css("display", "none");
      $("#cmbEstadoSucursalEdit").removeClass("is-invalid");
    }
    if (!$("#txtCPSucursalEdit").val()) {
      $("#invalid-cpSucursalEdit").css("display", "block");
      $("#txtCPSucursalEdit").addClass("is-invalid");
    } else {
      $("#invalid-cpSucursalEdit").css("display", "none");
      $("#txtCPSucursalEdit").removeClass("is-invalid");
    }
  }
});

$(document).on("click", "#btnEliminarSucursales", function () {
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "delete_data",
      funcion: "delete_paqueteria_sucursales",
      datos: _global.pkSucursal,
    },
    dataType: "json",
    success: function (respuesta) {
      if (respuesta[0].status) {
        $("#tblListadoDatosSucursalesPaqueteria").DataTable().ajax.reload();
        Lobibox.notify("success", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/checkmark.svg",
          msg: "¡Se eliminó la sucursal con éxito!",
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
});

$(document).on("click", "#btnTerminarRegistroPaqueteria", function () {
  window.location.href = "../paqueterias";
});

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

function validarEmpresaPaqueteria(pkGuia) {
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "validar_EmpresaPaqueteria",
      data: pkGuia,
    },
    dataType: "json",
    success: function (data) {
      /* Validar si ya existe el identificador con ese nombre*/
      if (parseInt(data[0]["valido"]) == "1") {
        //return true;
      } else {
        window.location.href = "../paqueterias";
        //return false;
      }
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
          window.location.href = "../paqueterias";
        }
      }
    },
  });
}
