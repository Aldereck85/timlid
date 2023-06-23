var _permissions = {
  read: 0,
  add: 0,
  edit: 0,
  delete: 0,
  export: 0,
};

var _globalG = {
  pkGuia: 0,
};

$(document).ready(function () {
  validate_Permissions(42);

  new SlimSelect({
    select: "#cmbTipoPago",
    deselectLabel: '<span class="">✖</span>',
  });

  new SlimSelect({
    select: "#cmbPaqueteria",
    deselectLabel: '<span class="">✖</span>',
  });

  new SlimSelect({
    select: "#cmbTipoPagoEdit",
    deselectLabel: '<span class="">✖</span>',
  });

  new SlimSelect({
    select: "#cmbPaqueteriaEdit",
    deselectLabel: '<span class="">✖</span>',
  });
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
      sFirst: "Primero",
      sLast: "Último",
      sNext: "<i class='fas fa-chevron-right'></i>",
      sPrevious: "<i class='fas fa-chevron-left'></i>",
    },
  };
  return idioma_espanol;
}

function validate_Permissions(pkPantalla) {
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "validar_Permisos", data: pkPantalla },
    dataType: "json",
    success: function (data) {
      var topButtons = [];
      _permissions.read = data[0].isRead;
      _permissions.add = data[0].isAdd;
      _permissions.edit = data[0].isEdit;
      _permissions.delete = data[0].isDelete;
      _permissions.export = data[0].isExport;

      //PRODUCTOS
      if (pkPantalla == "42") {
        var html = "";
        if (_permissions.add == "1") {
          topButtons.push({
            text: '<i class="fas fa-plus-square"></i> Añadir registro',
            className: "btn-table-custom--blue",
            action: function () {
              $("#agregar_Guia").modal("show");
              CargarAddGuia();
            },
          });
        }
        if (_permissions.export == "1") {
          topButtons.push({
            extend: "excelHtml5",
            text: '<i class="fas fa-cloud-download-alt"></i> Descargar excel',
            className: "btn-table-custom--turquoise",
            titleAttr: "Excel",
          });
        }
      }

      $("#tblGuias").dataTable({
        language: setFormatDatatables(),
        info: false,
        scrollX: true,
        bSort: false,
        pageLength: 50,
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
          buttons: topButtons,
        },
        ajax: {
          url: "../../php/funciones.php",
          data: {
            clase: "get_data",
            funcion: "get_guiasTable",
            data: _permissions.edit,
            data2: _permissions.delete,
          },
        },
        columns: [
          { data: "Id" },
          { data: "Estatus" },
          { data: "Numero" },
          { data: "Descripcion" },
          { data: "TipoPago" },
          { data: "Paqueteria" },
          { data: "Acciones" },
        ],
        columnDefs: [
          { orderable: false, targets: 0, visible: false },
          { orderable: false, targets: 1, visible: false },
        ],
        rowCallback: function (row, data) {
          if (data.Estatus.substr(25, 1) == "0") {
            $($(row).find("td span.textTable")[0]).addClass("left-dot gray-dot");
          } else {
            $($(row).find("td span.textTable")[0]).addClass("left-dot green-dot");
          }
        },
      });
    },
  });
}

function isExport() {
  if (_permissions.export == "1") {
    return '<img class="readEditPermissions" type="submit" width="50px" id="btnExportPermissions" onclick="exportarPDF()" src="../../../../img/excel-azul.svg" />';
  } else {
    return "";
  }
}

function CargarAddGuia() {
  cargarCMBTipoPago("", "cmbTipoPago");
  cargarCMBPaqueteria("", "cmbPaqueteria");
}

function cargarCMBTipoPago(data, input) {
  var html = "";
  var selected;
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_guia_tipoPago" },
    dataType: "json",
    success: function (respuesta) {
      $.each(respuesta, function (i) {
        if (data === respuesta[i].PKTipoPago) {
          selected = "selected";
        } else {
          selected = "";
        }

        html += `<option value="${respuesta[i].PKTipoPago}" ${selected}> 
                  ${respuesta[i].TipoPago}
                </option>`;
      });

      $(`#${input}`).html(html);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function cargarCMBPaqueteria(data, input) {
  var html = "";
  var selected;
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_guia_paqueteria" },
    dataType: "json",
    success: function (respuesta) {
      $.each(respuesta, function (i) {
        if (data === respuesta[i].PKPaqueteria) {
          selected = "selected";
        } else {
          selected = "";
        }

        html += `<option value="${respuesta[i].PKPaqueteria}" ${selected}> 
                  ${respuesta[i].Razon_Social}
                </option>`;
      });

      $(`#${input}`).html(html);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

$(document).on("click", "#btnAgregarGuia", function () {
  if ($("#formDatosGuia")[0].checkValidity()) {
    var badNumero =
      $("#invalid-numero").css("display") === "block" ? false : true;
    var badDescripcion =
      $("#invalid-descripcion").css("display") === "block" ? false : true;
    var badTipoPago =
      $("#invalid-tipoPago").css("display") === "block" ? false : true;
    var badPaqueteria =
      $("#invalid-paqueteria").css("display") === "block" ? false : true;

    if (badNumero && badDescripcion && badTipoPago && badPaqueteria) {
      var datos = {
        estatus: $("#activeGuia").is(":checked") ? 1 : 0,
        numero: $("#txtNumero").val(),
        descripcion: $("#txtDescripcion").val(),
        tipoPago: $("#cmbTipoPago").val(),
        paqueteria: $("#cmbPaqueteria").val(),
        isEdit: 0,
      };

      $.ajax({
        url: "../../php/funciones.php",
        data: { clase: "save_data", funcion: "save_datosGuiaGeneral", datos },
        dataType: "json",
        success: function (respuesta) {
          if (respuesta[0].status) {
            $("#tblGuias").DataTable().ajax.reload();
            Lobibox.notify("success", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../../img/timdesk/checkmark.svg",
              msg: "Guía agregada correctamente!",
              sound: "../../../../../sounds/sound4",
            });

            $("#txtNumero").val("");
            $("#txtDescripcion").val("");
            $("#cmbTipoPago").val("");
            $("#cmbPaqueteria").val("");
            $("#activeGuia").prop("checked", true);
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

      $("#agregar_Guia").modal("toggle");
    }
  } else {
    if (!$("#txtNumero").val()) {
      $("#invalid-numero").css("display", "block");
      $("#txtNumero").addClass("is-invalid");
    } else {
      $("#invalid-numero").css("display", "none");
      $("#txtNumero").removeClass("is-invalid");
    }

    if (!$("#txtDescripcion").val()) {
      $("#invalid-descripcion").css("display", "block");
      $("#txtDescripcion").addClass("is-invalid");
    } else {
      $("#invalid-descripcion").css("display", "none");
      $("#txtDescripcion").removeClass("is-invalid");
    }

    if (!$("#cmbTipoPago").val()) {
      $("#invalid-tipoPago").css("display", "block");
      $("#cmbTipoPago").addClass("is-invalid");
    } else {
      $("#invalid-tipoPago").css("display", "none");
      $("#cmbTipoPago").removeClass("is-invalid");
    }

    if (!$("#cmbPaqueteria").val()) {
      $("#invalid-paqueteria").css("display", "block");
      $("#cmbPaqueteria").addClass("is-invalid");
    } else {
      $("#invalid-paqueteria").css("display", "none");
      $("#cmbPaqueteria").removeClass("is-invalid");
    }
  }
});

$(document).on("click", "#btnEditarGuia", function () {
  if ($("#formDatosGuiaEdit")[0].checkValidity()) {
    var badNumero =
      $("#invalid-numeroEdit").css("display") === "block" ? false : true;
    var badDescripcion =
      $("#invalid-descripcionEdit").css("display") === "block" ? false : true;
    var badTipoPago =
      $("#invalid-tipoPagoEdit").css("display") === "block" ? false : true;
    var badPaqueteria =
      $("#invalid-paqueteriaEdit").css("display") === "block" ? false : true;

    if (badNumero && badDescripcion && badTipoPago && badPaqueteria) {
      var datos = {
        estatus: $("#activeGuiaEdit").is(":checked") ? 1 : 0,
        numero: $("#txtNumeroEdit").val(),
        descripcion: $("#txtDescripcionEdit").val(),
        tipoPago: $("#cmbTipoPagoEdit").val(),
        paqueteria: $("#cmbPaqueteriaEdit").val(),
        isEdit: _globalG.pkGuia,
      };

      $.ajax({
        url: "../../php/funciones.php",
        data: { clase: "save_data", funcion: "save_datosGuiaGeneral", datos },
        dataType: "json",
        success: function (respuesta) {
          if (respuesta[0].status) {
            $("#tblGuias").DataTable().ajax.reload();
            Lobibox.notify("success", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../../img/timdesk/checkmark.svg",
              msg: "Guía actualizada correctamente!",
              sound: "../../../../../sounds/sound4",
            });

            $("#txtNumeroEdit").val("");
            $("#txtDescripcionEdit").val("");
            $("#cmbTipoPagoEdit").val("");
            $("#cmbPaqueteriaEdit").val("");
            $("#activeGuiaEdit").prop("checked", true);
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

      $("#editar_Guia").modal("toggle");
    }
  } else {
    if (!$("#txtNumeroEdit").val()) {
      $("#invalid-numeroEdit").css("display", "block");
      $("#txtNumeroEdit").addClass("is-invalid");
    } else {
      $("#invalid-numeroEdit").css("display", "none");
      $("#txtNumeroEdit").removeClass("is-invalid");
    }

    if (!$("#txtDescripcionEdit").val()) {
      $("#invalid-descripcionEdit").css("display", "block");
      $("#txtDescripcionEdit").addClass("is-invalid");
    } else {
      $("#invalid-descripcionEdit").css("display", "none");
      $("#txtDescripcionEdit").removeClass("is-invalid");
    }

    if (!$("#cmbTipoPagoEdit").val()) {
      $("#invalid-tipoPagoEdit").css("display", "block");
      $("#cmbTipoPagoEdit").addClass("is-invalid");
    } else {
      $("#invalid-tipoPagoEdit").css("display", "none");
      $("#cmbTipoPagoEdit").removeClass("is-invalid");
    }

    if (!$("#cmbPaqueteriaEdit").val()) {
      $("#invalid-paqueteriaEdit").css("display", "block");
      $("#cmbPaqueteriaEdit").addClass("is-invalid");
    } else {
      $("#invalid-paqueteriaEdit").css("display", "none");
      $("#cmbPaqueteriaEdit").removeClass("is-invalid");
    }
  }
});

function obtenerDatosEditarGuia(id) {
  _globalG.pkGuia = id;

  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_datos_guia_general",
      datos: _globalG.pkGuia,
    },
    dataType: "json",
    success: function (respuesta) {
      $("#txtNumeroEdit").val(respuesta[0].numero);
      $("#txtDescripcionEdit").val(respuesta[0].descripcion);
      cargarCMBTipoPago(respuesta[0].tipoPago, "cmbTipoPagoEdit");
      cargarCMBPaqueteria(respuesta[0].paqueteria, "cmbPaqueteriaEdit");

      if (respuesta[0].estatus == "1") {
        $("#activeGuiaEdit").prop("checked", true);
      } else {
        $("#activeGuiaEdit").prop("checked", false);
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function obtenerEliminarGuia() {
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "edit_data",
      funcion: "edit_datosGuiaEstatus",
      datos: _globalG.pkGuia,
    },
    dataType: "json",
    success: function (respuesta) {
      if (respuesta[0].status) {
        $("#tblGuias").DataTable().ajax.reload();
        Lobibox.notify("success", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/checkmark.svg",
          msg: "Guía inactivada correctamente!",
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
