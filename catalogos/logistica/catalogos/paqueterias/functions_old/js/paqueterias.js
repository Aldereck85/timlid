$(document).ready(function () {
  loadAlertsNoti(
    "alertaTareas",
    $("#txtUsuario").val(),
    $("#txtRuta").val(),
    $("#txtEdit").val()
  );
  setInterval(
    loadAlertsNoti,
    10000,
    "alertaTareas",
    $("#txtUsuario").val(),
    $("#txtRuta").val(),
    $("#txtEdit").val()
  );

  $("#tblPaqueterias").dataTable({
    language: setFormatDatatables(),
    dom: "Bfrtip",
    buttons: [
      {
        extend: "excelHtml5",
        text: '<img class="readEditPermissions" type="submit" width="50px" src="../../img/excel-azul.svg" />',
        className: "excelDataTableButton",
        titleAttr: "Excel",
      },
    ],
    scrollX: true,
    lengthChange: false,
    info: false,
    ajax: "functions/function_Paqueterias.php",
    columns: [
      {
        data: "No",
      },
      {
        data: "Razon Social",
      },
      {
        data: "Email",
      },
      {
        data: "RFC",
      },
      {
        data: "Calle",
      },
      {
        data: "Numero exterior",
      },
      {
        data: "Interior",
      },
      {
        data: "Colonia",
      },
      {
        data: "Municipio",
      },
      {
        data: "Estado",
      },
      {
        data: "Codigo Postal",
      },
      {
        data: "Acciones",
      },
    ],
  });

  new SlimSelect({ select: "#cmbEstados" });
  new SlimSelect({ select: "#cmbPais" });
  new SlimSelect({ select: "#cmbEstadosU" });
  new SlimSelect({ select: "#cmbPaisU" });

  /* VALIDACIONES */
  /*Permitir solamente letras*/
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

  /* Reiniciar el modal agregar al cerrarlo */
  $("#agregar_paqueteria").on("hidden.bs.modal", function (e) {
    $("#invalid-razonSocial").css("display", "none");
    $("#txtRazonSocial").removeClass("is-invalid");
    $("#txtRazonSocial").val("");

    $("#invalid-nombreComercial").css("display", "none");
    $("#txtNombreComercial").removeClass("is-invalid");
    $("#txtNombreComercial").val("");

    $("#invalid-RFC").css("display", "none");
    $("#txtRFC").removeClass("is-invalid");
    $("#txtRFC").val("");

    $("#invalid-calle").css("display", "none");
    $("#txtCalle").removeClass("is-invalid");
    $("#txtCalle").val("");

    $("#invalid-noExterior").css("display", "none");
    $("#txtNumeroExterior").removeClass("is-invalid");
    $("#txtNumeroExterior").val("");

    $("#invalid-pais").css("display", "none");
    $("#cmbPais").removeClass("is-invalid");

    $("#invalid-estado").css("display", "none");
    $("#cmbEstados").removeClass("is-invalid");

    $("#invalid-municipio").css("display", "none");
    $("#txtMunicipio").removeClass("is-invalid");
    $("#txtMunicipio").val("");

    $("#invalid-CP").css("display", "none");
    $("#txtCodigoPostal").removeClass("is-invalid");
    $("#txtCodigoPostal").val("");

    $("#invalid-colonia").css("display", "none");
    $("#txtColonia").removeClass("is-invalid");
    $("#txtColonia").val("");

    $("#invalid-telefono").css("display", "none");
    $("#txtTelefono").removeClass("is-invalid");
    $("#txtTelefono").val("");
  });

  /* Reiniciar el modal editar al cerrarlo */
  $("#editar_paqueteria").on("hidden.bs.modal", function (e) {
    $("#invalid-razonSocialEdit").css("display", "none");
    $("#txtRazonSocialU").removeClass("is-invalid");
    $("#txtRazonSocialU").val("");

    $("#invalid-nombreComEdit").css("display", "none");
    $("#txtNombreComercialU").removeClass("is-invalid");
    $("#txtNombreComercialU").val("");

    $("#invalid-RFCEdit").css("display", "none");
    $("#txtRFCU").removeClass("is-invalid");
    $("#txtRFCU").val("");

    $("#invalid-calleEdit").css("display", "none");
    $("#txtCalleU").removeClass("is-invalid");
    $("#txtCalleU").val("");

    $("#invalid-noExteriorEdit").css("display", "none");
    $("#txtNumeroExteriorU").removeClass("is-invalid");
    $("#txtNumeroExteriorU").val("");

    $("#invalid-paisEdit").css("display", "none");
    $("#cmbPaisU").removeClass("is-invalid");

    $("#invalid-estadoEdit").css("display", "none");
    $("#cmbEstadosU").removeClass("is-invalid");

    $("#invalid-municipioEdit").css("display", "none");
    $("#txtMunicipioU").removeClass("is-invalid");
    $("#txtMunicipioU").val("");

    $("#invalid-CPEdit").css("display", "none");
    $("#txtCodigoPostalU").removeClass("is-invalid");
    $("#txtCodigoPostalU").val("");

    $("#invalid-coloniaEdit").css("display", "none");
    $("#txtColoniaU").removeClass("is-invalid");
    $("#txtColoniaU").val("");

    $("#invalid-telefonoEdit").css("display", "none");
    $("#txtTelefonoU").removeClass("is-invalid");
    $("#txtTelefonoU").val("");
  });
});

$(document).on("click", "#btn_modal_agregar_paqueteria", function () {
  $("#agregar_paqueteria").modal("toggle");
  loadCombo(146, "cmbPais", "pais", "", "countries");
  loadCombo("", "cmbEstados", "estado", 146, "states");
  $("#txtRFC").val().toUpperCase();
});

$(document).on("input", "#txtRFC", function () {
  this.value = this.value.toUpperCase();
});

$(document).on("input", "#txtRFCU", function () {
  this.value = this.value.toUpperCase();
});

$(document).on("change", "#cmbPais", function () {
  var pais = $("#cmbPais").val();
  loadCombo("", "cmbEstados", "estado", pais, "states");
});

$(document).on("change", "#cmbPaisU", function () {
  var pais = $("#cmbPaisU").val();
  loadCombo("", "cmbEstadosU", "estado", pais, "states");
});

$(document).on("click", "#btn_agregar_paqueteria", function () {
  if ($("#form-paqueteria")[0].checkValidity()) {
    var badRazonSocial =
      $("#invalid-razonSocial").css("display") === "block" ? false : true;
    var badNomCom =
      $("#invalid-nomCom").css("display") === "block" ? false : true;
    var badRfc = $("#invalid-rfc").css("display") === "block" ? false : true;
    var badCalle =
      $("#invalid-calle").css("display") === "block" ? false : true;
    var badNumExt =
      $("#invalid-numExt").css("display") === "block" ? false : true;
    var badPais = $("#invalid-pais").css("display") === "block" ? false : true;
    var badEstado =
      $("#invalid-estado").css("display") === "block" ? false : true;
    var badMunicipio =
      $("#invalid-municipio").css("display") === "block" ? false : true;
    var badCp = $("#invalid-cp").css("display") === "block" ? false : true;
    var badColonia =
      $("#invalid-colonia").css("display") === "block" ? false : true;
    var badTelefono =
      $("#invalid-telefono").css("display") === "block" ? false : true;

    if (
      badRazonSocial &&
      badNomCom &&
      badRfc &&
      badCalle &&
      badNumExt &&
      badPais &&
      badEstado &&
      badMunicipio &&
      badCp &&
      badColonia &&
      badTelefono
    ) {
      var data = [];

      form = $("#form-paqueteria").serializeArray();
      $.each(form, function (i) {
        data.push({ input: form[i].name, value: form[i].value });
      });

      datajson = JSON.stringify(data);

      $.ajax({
        url: "php/funciones",
        data: {
          clase: "save_data",
          funcion: "save_paqueteria",
          value: datajson,
          empresa: $("#emp_id").val(),
          usuario: $("#txtUsuario").val(),
        },
        dataType: "json",
        success: function (respuesta) {
          $("#agregar_paqueteria").modal("toggle");
          $("#txtRazonSocial").val("");
          $("#txtNombreComercial").val("");
          $("#txtRFC").val("");
          $("#txtCalle").val("");
          $("#txtNumeroExterior").val("");
          $("#cmbPais").val("");
          $("#cmbEstados").val("");
          $("#txtMunicipio").val("");
          $("#txtCodigoPostal").val("");
          $("#txtColonia").val("");
          $("#txtTelefono").val("");

          if (respuesta === 2) {
            Lobibox.notify("success", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../img/timdesk/checkmark.svg",
              msg: "¡Se ha registrado con éxito!",
            });
            //alert("Se ha registrado con éxito");
            $("#tblPaqueterias").DataTable().ajax.reload();
          } else {
            Lobibox.notify("error", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../img/timdesk/notificacion_error.svg",
              msg: "¡No se pudo registrar!. " + respuesta,
            });
            console.log("No se pudo registrar. Error: " + respuesta);
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
            img: "../../img/timdesk/notificacion_error.svg",
            msg: "¡Algo salio mal :(!",
          });
        },
      });
    }
  } else {
    if (!$("#txtRazonSocial").val()) {
      $("#invalid-razonSocial").css("display", "block");
      $("#txtRazonSocial").addClass("is-invalid");
    }
    if (!$("#txtNombreComercial").val()) {
      $("#invalid-nomCom").css("display", "block");
      $("#txtNombreComercial").addClass("is-invalid");
    }
    if (!$("#txtRFC").val()) {
      $("#invalid-rfc").css("display", "block");
      $("#txtRFC").addClass("is-invalid");
    }
    if (!$("#txtCalle").val()) {
      $("#invalid-calle").css("display", "block");
      $("#txtCalle").addClass("is-invalid");
    }
    if (!$("#txtNumeroExterior").val()) {
      $("#invalid-numExt").css("display", "block");
      $("#txtNumeroExterior").addClass("is-invalid");
    }
    if (!$("#cmbPais").val()) {
      $("#invalid-pais").css("display", "block");
      $("#cmbPais").addClass("is-invalid");
    }
    if (!$("#cmbEstado").val()) {
      $("#invalid-estado").css("display", "block");
      $("#cmbEstado").addClass("is-invalid");
    }
    if (!$("#txtMunicipio").val()) {
      $("#invalid-municipio").css("display", "block");
      $("#txtMunicipio").addClass("is-invalid");
    }
    if (!$("#txtCodigoPostal").val()) {
      $("#invalid-cp").css("display", "block");
      $("#txtCodigoPostal").addClass("is-invalid");
    }
    if (!$("#txtColonia").val()) {
      $("#invalid-colonia").css("display", "block");
      $("#txtColonia").addClass("is-invalid");
    }
    if (!$("#txtTelefono").val()) {
      $("#invalid-telefono").css("display", "block");
      $("#txtTelefono").addClass("is-invalid");
    }
  }
});

$(document).on("click", "#btn_editar_paqueteria", function () {
  if ($("#form-paqueteria-edit")[0].checkValidity()) {
    var badRazonSocialEdit =
      $("#invalid-razonSocialEdit").css("display") === "block" ? false : true;
    var badNomComEdit =
      $("#invalid-nombreComEdit").css("display") === "block" ? false : true;
    var badRfcEdit =
      $("#invalid-RFCEdit").css("display") === "block" ? false : true;
    var badCalleEdit =
      $("#invalid-calleEdit").css("display") === "block" ? false : true;
    var badNumExtEdit =
      $("#invalid-noExteriorEdit").css("display") === "block" ? false : true;
    var badPaisEdit =
      $("#invalid-paisEdit").css("display") === "block" ? false : true;
    var badEstadoEdit =
      $("#invalid-estadoEdit").css("display") === "block" ? false : true;
    var badMunicipioEdit =
      $("#invalid-municipioEdit").css("display") === "block" ? false : true;
    var badCpEdit =
      $("#invalid-CPEdit").css("display") === "block" ? false : true;
    var badColoniaEdit =
      $("#invalid-coloniaEdit").css("display") === "block" ? false : true;
    var badTelefonoEdit =
      $("#invalid-telefonoEdit").css("display") === "block" ? false : true;

    if (
      badRazonSocialEdit &&
      badNomComEdit &&
      badRfcEdit &&
      badCalleEdit &&
      badNumExtEdit &&
      badPaisEdit &&
      badEstadoEdit &&
      badMunicipioEdit &&
      badCpEdit &&
      badColoniaEdit &&
      badTelefonoEdit
    ) {
      var data = [];

      form = $("#form-paqueteria-edit").serializeArray();

      $.each(form, function (i) {
        data.push({ input: form[i].name, value: form[i].value });
      });

      datajson = JSON.stringify(data);

      $.ajax({
        url: "php/funciones",
        data: {
          clase: "edit_data",
          funcion: "edit_paqueteria",
          value: datajson,
          usuario: $("#txtUsuario").val(),
        },
        dataType: "json",
        success: function (respuesta) {
          $("#editar_paqueteria").modal("toggle");

          if (respuesta === 1) {
            Lobibox.notify("success", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../img/timdesk/checkmark.svg",
              msg: "¡Se ha actualizado con éxito!",
            });

            $("#tblPaqueterias").DataTable().ajax.reload();
          } else {
            Lobibox.notify("error", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../img/timdesk/notificacion_error.svg",
              msg: "¡No se pudo actualizar! " + respuesta,
            });

            console.log("No se pudo actualizar. " + respuesta);
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
            img: "../../img/timdesk/notificacion_error.svg",
            msg: "¡No se pudo actualizar!",
          });

          console.log(error);
        },
      });
    }
  } else {
    if (!$("#txtRazonSocialU").val()) {
      $("#invalid-razonSocialEdit").css("display", "block");
      $("#txtRazonSocialU").addClass("is-invalid");
    }
    if (!$("#txtNombreComercialU").val()) {
      $("#invalid-nombreComEdit").css("display", "block");
      $("#txtNombreComercialU").addClass("is-invalid");
    }
    if (!$("#txtRFCU").val()) {
      $("#invalid-RFCEdit").css("display", "block");
      $("#txtRFCU").addClass("is-invalid");
    }
    if (!$("#txtCalleU").val()) {
      $("#invalid-calleEdit").css("display", "block");
      $("#txtCalleU").addClass("is-invalid");
    }
    if (!$("#txtNumeroExteriorU").val()) {
      $("#invalid-noExteriorEdit").css("display", "block");
      $("#txtNumeroExteriorU").addClass("is-invalid");
    }
    if (!$("#cmbPaisU").val()) {
      $("#invalid-paisEdit").css("display", "block");
      $("#cmbPaisU").addClass("is-invalid");
    }
    if (!$("#cmbEstadosU").val()) {
      $("#invalid-estadoEdit").css("display", "block");
      $("#cmbEstadosU").addClass("is-invalid");
    }
    if (!$("#txtMunicipioU").val()) {
      $("#invalid-municipioEdit").css("display", "block");
      $("#txtMunicipioU").addClass("is-invalid");
    }
    if (!$("#txtCodigoPostalU").val()) {
      $("#invalid-CPEdit").css("display", "block");
      $("#txtCodigoPostalU").addClass("is-invalid");
    }
    if (!$("#txtColoniaU").val()) {
      $("#invalid-coloniaEdit").css("display", "block");
      $("#txtColoniaU").addClass("is-invalid");
    }
    if (!$("#txtTelefonoU").val()) {
      $("#invalid-telefonoEdit").css("display", "block");
      $("#txtTelefonoU").addClass("is-invalid");
    }
  }
});

$(document).on("click", "#btn_eliminar_paqueteria", function () {
  var id = $("#idPaqueteriaD").val();
  console.log(id);
  $.ajax({
    url: "php/funciones",
    data: {
      clase: "delete_data",
      funcion: "delete_paqueteria",
      value: id,
      usuario: $("#txtUsuario").val(),
    },
    dataType: "json",
    success: function (respuesta) {
      if (respuesta == 1) {
        Lobibox.notify("success", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/checkmark.svg",
          msg: "¡Se a eliminado con éxito!",
        });
        $("#tblPaqueterias").DataTable().ajax.reload();
        $("#editar_paqueteria").modal("toggle");
        $("#eliminar_Paqueteria").modal("toggle");
      } else {
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/notificacion_error.svg",
          msg: "¡No se pudo eliminar!",
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
        img: "../../img/timdesk/notificacion_error.svg",
        msg: "¡No se pudo eliminar!",
      });
    },
  });
});

function setFormatDatatables() {
  var idioma_espanol = {
    sProcessing: "Procesando...",
    sZeroRecords: "No se encontraron resultados",
    sEmptyTable: "Ningún dato disponible en esta tabla",
    sSearch: "<img src='../../img/timdesk/buscar.svg' width='20px' />",
    sLoadingRecords: "Cargando...",
    searchPlaceholder: "Buscar...",
    oPaginate: {
      sFirst: "Primero",
      sLast: "Último",
      sNext: "<img src='../../img/icons/pagination.svg' width='20px'/>",
      sPrevious:
        "<img src='../../img/icons/pagination.svg' width='20px' style='transform: scaleX(-1)'/>",
    },
  };
  return idioma_espanol;
}

function obtenerIdPaqueteriaEliminar() {
  //document.getElementById('idPaqueteriaD').value = id;
  $("#eliminar_Paqueteria").modal("toggle");
  $("#idPaqueteriaD").val($("#txtId").val());
}

function obtenerIdPaqueteriaEditar(id) {
  $("#editar_paqueteria").modal("toggle");
  $("#txtId").val(id);
  $.ajax({
    url: "php/funciones.php",
    data: { clase: "get_data", funcion: "get_paqueteria", value: id },
    dataType: "json",
    success: function (respuesta) {
      //console.log(respuesta);

      $("#txtRazonSocialU").val(respuesta[0].Razon_Social);
      $("#txtNombreComercialU").val(respuesta[0].NombreComercial);
      $("#txtEmailU").val(respuesta[0].Email);
      $("#txtRFCU").val(respuesta[0].RFC);
      $("#txtCalleU").val(respuesta[0].Calle);
      $("#txtNumeroExteriorU").val(respuesta[0].Numero_exterior);
      $("#txtInteriorU").val(respuesta[0].Numero_Interior);
      loadCombo(respuesta[0].Pais, "cmbPaisU", "pais", "", "countries");
      loadCombo(
        respuesta[0].Estado,
        "cmbEstadosU",
        "estado",
        respuesta[0].Pais,
        "states"
      );
      $("#txtMunicipioU").val(respuesta[0].Municipio);
      $("#txtCodigoPostalU").val(respuesta[0].CP);
      $("#txtColoniaU").val(respuesta[0].Colonia);
      $("#txtTelefonoU").val(respuesta[0].Telefono);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function loadCombo(data, input, name, value, fun) {
  //data: atributo para seleccionar el valor predeterminado
  //input: id del input select
  //name: nombre del tipo de dato a mostrar
  //value: valor en caso de filtrar por un dato
  //fun: nombre de la funcion php a ejecutar

  var html =
    '<option value="" readonly selected>Seleccione un ' + name + "...</option>";

  $.ajax({
    url: "php/funciones.php",
    data: { clase: "get_data", funcion: "get_" + fun + "Combo", value: value },
    dataType: "json",
    success: function (respuesta) {
      //console.log("respuesta "+name+" combo:",respuesta);
      //console.log("count combo"+name,respuesta.length);
      if (respuesta !== "" && respuesta !== null && respuesta.length > 0) {
        $.each(respuesta, function (i) {
          if (data === respuesta[i].PKData) {
            selected = "selected";
          } else {
            selected = "";
          }
          html +=
            '<option value="' +
            respuesta[i].PKData +
            '" ' +
            selected +
            ">" +
            respuesta[i].Data +
            "</option>";
          if (respuesta[i].Oculto !== "") {
            oculto = respuesta[i].Oculto;
          }
        });
      } else {
        html +=
          '<option value="vacio">No hay ' + name + " que mostrar</option>";
      }

      $("#" + input + "").html(html);
    },
    error: function (error) {
      console.log(error);
    },
  });
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
