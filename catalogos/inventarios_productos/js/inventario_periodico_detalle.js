var table;

$(document).ready(function () {
    cargarTblInventarioPeriodico();
    cargarCMBProductos();
});

function cargarTblInventarioPeriodico() {
table = $("#tblInventarioPeriodico")
    .DataTable({
    language: setFormatDatatables(),
    dom: "Bfrtip",
    scrollX: true,
    lengthChange: true,
    info: false,
    buttons: {
      dom: {
        button: {
          tag: "button",
          className: "btn-custom mr-2",
        },
        buttonLiner: {
          tag: null,
        },
      },
      buttons: [
        {
          text: '<span class="d-flex align-items-center"><img src="../../../../img/icons/ICONO SUBIR ARCHIVO-01.svg" width="20" class="mr-1"> Importar excel</span>',
          className: "btn-custom--white-dark",
          titleAttr: "Excel",
          action: function (e, dt, node, config) {
            $("#excelmodal").modal("show");
          },
        },
      ],
    },
    ajax: {
        url: "../../php/funciones.php",
        data: {
        clase: "get_data",
        funcion: "get_detalleInventarioPeriodico",
        sucursal: $("#txtSucursal").val(),
        },
    },
    pageLength: 20,
    paging: true,
    order: [],
    columns: [
        { data: "IdDetalle" },
        { data: "IdProducto" },
        { data: "Clave" },
        { data: "Nombre" },
        { data: "Existencia" },
        { data: "Cantidad" },
        { data: "Lote" },
        { data: "Caducidad" },
        { data: "Acciones" },
    ],
    columnDefs: [{ orderable: false, targets: [0, 1], visible: false }],
    });

    $("#tblInventarioPeriodico tbody")
      .off("click")
      .on("click", "img", function () {
        var identificador = $(this).attr("id").split("_")[1];
        var tipoBoton = $(this).attr("id").split("_")[0];
  
        if (tipoBoton == "btnAgregar") {
          var id = $("#id_" + identificador).val();
  
          $.ajax({
            url: "../../php/funciones.php",
            data: {
              clase: "save_data",
              funcion: "save_DuplicarEliminarProductoInventarioPeriodico",
              data: 0,
              data2: $("#txtSucursal").val(),
              data3: id,
              data4: 0,
            },
            dataType: "json",
            success: function (respuesta) {
              console.log(
                "respuesta de guardar un producto vacio de inventario: ",
                respuesta
              );
              table.ajax.reload(null, false);
            },
            error: function (error) {
              console.log(error);
            },
          });
  
          Lobibox.notify("success", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../../../img/timdesk/checkmark.svg",
            msg: "¡Se agregó un producto.!",
            sound: "../../../../../sounds/sound4",
          });
  
          $(".tooltip").tooltip("hide");
        } else {  
          $.ajax({
            url: "../../php/funciones.php",
            data: {
              clase: "save_data",
              funcion: "save_DuplicarEliminarProductoInventarioPeriodico",
              data: identificador,
              data2: 0,
              data3: 0,
              data4: 1,
            },
            dataType: "json",
            success: function (respuesta) {
              console.log(
                "respuesta de eliminar un producto de inventario: ",
                respuesta
              );
              table.ajax.reload(null, false);
            },
            error: function (error) {
              console.log(error);
            },
          });
  
          Lobibox.notify("success", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../../../img/timdesk/checkmark.svg",
            msg: "¡Se eliminó un producto.!",
            sound: "../../../../../sounds/sound4",
          });
  
          $(".tooltip").tooltip("hide");
        }
      });
}
  
function cargarCMBProductos() {
  var html = '<option value="0" selected disabled>Seleccionar un producto</option>';
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_productosInvPeriodico" },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta de productos: ", respuesta);

      $.each(respuesta, function (i) {
        html +=
          '<option value="' +
          respuesta[i].PKProducto +
          '">' +
          respuesta[i].ClaveInterna +
          ' - ' +
          respuesta[i].Nombre +
          "</option>";
      });

      $("#cmbProductos").append(html);
    },
    error: function (error) {
      console.log(error);
    },
  });

  new SlimSelect({
    select: "#cmbProductos",
    deselectLabel: '<span class="">✖</span>',
    onChange: (newVal) => {
      console.log(newVal.value);
      $.ajax({
        url: "../../php/funciones.php",
        data: {
          clase: "get_data",
          funcion: "get_ValidProductNuevoInvPeriodico",
          data: $("#txtSucursal").val(),
          data2: newVal.value,
        },
        dataType: "json",
        success: function (respuesta) {
          console.log(
            "respuesta de validar agregar producto nuevo: ",
            respuesta
          );
          if(respuesta['existeSinL'] == 1){
            Lobibox.notify("error", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../../img/timdesk/notificacion_error.svg",
              msg: "¡No se pueden duplicar productos sin lote!",
              sound: "../../../../../sounds/sound4",
            });
          }else{
            $.ajax({
              url: "../../php/funciones.php",
              data: {
                clase: "save_data",
                funcion: "save_DuplicarEliminarProductoInventarioPeriodico",
                data: 0,
                data2: $("#txtSucursal").val(),
                data3: newVal.value,
                data4: 0,
              },
              dataType: "json",
              success: function (respuesta) {
                console.log(
                  "respuesta de agregar un producto de inventario: ",
                  respuesta
                );
                table.ajax.reload(null, false);
              },
              error: function (error) {
                console.log(error);
              },
            });
          }
        },
        error: function (error) {
          console.log(error);
        },
      }); 
    }
  });
}

function limitarLongitud(input) {
  if (input.value.length > 21) {
    input.value = input.value.substring(0, 21);
  }
}

function validarRepeticion(item) {
  var sucursal = $("#txtSucursal").val();
  var campo = $(item).attr("id").split("_")[0];
  var identificador = $(item).attr("id").split("_")[1];

  if (!$("#id_" + identificador).val()) {
    var idDetalle = 0;
  } else {
    var idDetalle = $("#id_" + identificador).val();
  }
  var clave = $("#clave_" + identificador).val();
  if (campo == "lot") {
    var lote = $(item).val();

    $.ajax({
      url: "../../php/funciones.php",
      data: {
        clase: "get_data",
        funcion: "get_validationInvPerio",
        data: sucursal,
        data2: idDetalle,
        data3: clave,
        data4: lote,
      },
      dataType: "json",
      success: function (respuesta) {
        console.log("respuesta de la validacion: ", respuesta);
        if (respuesta[0].mensaje != null) {
          $("#invalid_campo_rep_" + campo + "_" + identificador).remove();
          $(
            "<div class='invalid-feedback d-block' id='invalid_campo_rep_" +
              campo +
              "_" +
              identificador +
              "'>" +
              respuesta[0].mensaje +
              "</div>"
          ).insertAfter($(item));
        } else {
          $("#invalid_campo_rep_" + campo + "_" + identificador).remove();
        }
      },
      error: function (error) {
        console.log(error);
      },
    });
  }
}

function inserPrevio(item) {
  validarRepeticion(item);

  var sucursal = $("#txtSucursal").val();

  var campo = $(item).attr("id").split("_")[0];
  var identificador = $(item).attr("id").split("_")[1];
  var id = $("#id_" + identificador).val();
  var clave = $("#clave_" + identificador).val();

  switch (campo) {
    case "cant":
      var cantidad = $(item).val();
      var cantidad2 = cantidad.toString();

      $.ajax({
        url: "../../php/funciones.php",
        data: {
          clase: "save_data",
          funcion: "save_detailInvPerio",
          data: sucursal,
          data2: identificador,
          data3: id,
          data4: clave,
          data5: cantidad2,
          data6: "cantidad",
        },
        dataType: "json",
        success: function (respuesta) {
          console.log(
            "respuesta de guardar el detalle del inventario periodico: ",
            respuesta
          );
        },
        error: function (error) {
          console.log(error);
        },
      });
      validarCantidad(item);
      break;
    case "lot":
      var lote = $(item).val();
      console.log(lote);
      $.ajax({
        url: "../../php/funciones.php",
        data: {
          clase: "save_data",
          funcion: "save_detailInvPerio",
          data: sucursal,
          data2: identificador,
          data3: id,
          data4: clave,
          data5: lote,
          data6: "lote",
        },
        dataType: "json",
        success: function (respuesta) {
          console.log(
            "respuesta de guardar el detalle del inventario periodico: ",
            respuesta
          );
        },
        error: function (error) {
          console.log(error);
        },
      });
      validarCampo(item);
      break;
    case "fech":
      var caducidad = $(item).val();
      var caducidad2 = caducidad.toString();

      if (!$(item).val() || $(item).val() == "") {
        var caducidad2 = "0000-00-00";
      }

      $.ajax({
        url: "../../php/funciones.php",
        data: {
          clase: "save_data",
          funcion: "save_detailInvPerio",
          data: sucursal,
          data2: identificador,
          data3: id,
          data4: clave,
          data5: caducidad2,
          data6: "caducidad",
        },
        dataType: "json",
        success: function (respuesta) {
          console.log(
            "respuesta de guardar el detalle del inventario periodico: ",
            respuesta
          );
        },
        error: function (error) {
          console.log(error);
        },
      });
      validarCampo(item);
      break;
  }
}

function validarCampo(item) {
  var id = $(item).attr("id").split("_")[1];
  var campo = $(item).attr("id").split("_")[0];

  var cantidad = $("#cant_" + id);

  if (!cantidad.val()) {
    $("#invalid_" + id).remove();
    $("#mensaje_invalido").remove();
    $(
      "<div class='invalid-feedback d-block' id='invalid_" +
        id +
        "'>Introduce también la cantidad para guardar este producto</div>"
    ).insertAfter($("#cant_" + id));
    $("#div_invalido").append(
      "<div class='invalid-feedback d-block' id='mensaje_invalido'>No dejes productos incompletos</div>"
    );
  }
  if ($(item).val() != null) {
    $("#invalid_campo_" + campo + "_" + id).remove();
    $("#mensaje_invalido").remove();
  } else {
    $("#invalid_campo_" + campo + "_" + id).remove();
    $("#mensaje_invalido").remove();
    $(
      "<div class='invalid-feedback d-block' id='invalid_campo_" +
        campo +
        "_" +
        id +
        "'>Introduce también este campo</div>"
    ).insertAfter($("#" + campo + "_" + id));
    $("#div_invalido").append(
      "<div class='invalid-feedback d-block' id='mensaje_invalido'>No dejes productos incompletos</div>"
    );
  }
}

function validarCantidad(item) {
  var id = $(item).attr("id").split("_")[1];
  if ($(item).val() != null) {
    $("#invalid_" + id).remove();
    $("#mensaje_invalido").remove();
  } else {
    $("#invalid_" + id).remove();
    $("#mensaje_invalido").remove();
    $(
      "<div class='invalid-feedback d-block' id='invalid_" +
        id +
        "'>Introduce también la cantidad para guardar este producto</div>"
    ).insertAfter($(item));
    $("#div_invalido").append(
      "<div class='invalid-feedback d-block' id='mensaje_invalido'>No dejes productos incompletos</div>"
    );
  }

  if (!$("#ser_" + id).prop("disabled") && !$("#ser_" + id).val()) {
    $("#invalid_campo_ser_" + id).remove();
    $("#mensaje_invalido").remove();
    $(
      "<div class='invalid-feedback d-block' id='invalid_campo_ser_" +
        id +
        "'>Introduce también este campo</div>"
    ).insertAfter($("#ser_" + id));
    $("#div_invalido").append(
      "<div class='invalid-feedback d-block' id='mensaje_invalido'>No dejes productos incompletos</div>"
    );
  }
  if (!$("#lot_" + id).prop("disabled") && !$("#lot_" + id).val()) {
    $("#invalid_campo_lot_" + id).remove();
    $("#mensaje_invalido").remove();
    $(
      "<div class='invalid-feedback d-block' id='invalid_campo_lot_" +
        id +
        "'>Introduce también este campo</div>"
    ).insertAfter($("#lot_" + id));
    $("#div_invalido").append(
      "<div class='invalid-feedback d-block' id='mensaje_invalido'>No dejes productos incompletos</div>"
    );
  }
  if (!$("#fech_" + id).prop("disabled") && !$("#fech_" + id).val()) {
    $("#invalid_campo_fech_" + id).remove();
    $("#mensaje_invalido").remove();
    $(
      "<div class='invalid-feedback d-block' id='invalid_campo_fech_" +
        id +
        "'>Introduce también este campo</div>"
    ).insertAfter($("#fech_" + id));
    $("#div_invalido").append(
      "<div class='invalid-feedback d-block' id='mensaje_invalido'>No dejes productos incompletos</div>"
    );
  }
}

function campoCaducidad(item) {
  inserPrevio(item);
  validarCampo(item);
}

function validarExcel() {
  $("#loaderValidacion").css("display", "block");

  var formData = new FormData();
  formData.append("dataexcel", dataexcel.files[0]);
  formData.append("sucursal", $("#txtSucursal").val());
  console.log(formData.get("dataexcel"));
  console.log(formData.get("sucursal"));
  $.ajax({
    url: "validarExcel.php",
    type: "POST",
    data: formData,
    dataType: "json",
    processData: false,
    contentType: false,
    success: function (respuesta) {
      console.log(respuesta);
      if (respuesta.status == "fail") {
        $("#dataexcel").val("");
        $("#loaderValidacion").css("display", "none");
        Swal.fire({
          title: "Error",
          icon: "error",
          html: "<p style='font-size: 15px'>No se pudo subir el archivo</p>",
          showCancelButton: false,
          confirmButtonText: "Aceptar",
          customClass: {
            actions: "d-flex justify-content-around",
            confirmButton: "btn-custom btn-custom--border-blue",
          },
          buttonsStyling: false,
          allowEnterKey: false,
        }).then((result) => {
          if (result.isConfirmed) {
            Swal.close();
          }
        });
      }
    },
    error: function (error) {
      console.log(error.responseText);
      if (error.responseText.includes("Formato no aceptado") == true) {
        $("#dataexcel").val("");
        $("#loaderValidacion").css("display", "none");
        Swal.fire({
          title: "Error",
          icon: "error",
          html: "<p style='font-size: 15px'>Formato no aceptado</p>",
          showCancelButton: false,
          confirmButtonText: "Aceptar",
          customClass: {
            actions: "d-flex justify-content-around",
            confirmButton: "btn-custom btn-custom--border-blue",
          },
          buttonsStyling: false,
          allowEnterKey: false,
        }).then((result) => {
          if (result.isConfirmed) {
            Swal.close();
          }
        });
      } else if (
        error.responseText != "" &&
        error.responseText.includes("Formato incorrecto") == false &&
        error.responseText.includes("Formato no aceptado") == false
      ) {
        $("#dataexcel").val("");
        $("#loaderValidacion").css("display", "none");
        Swal.fire({
          title: "Error",
          icon: "error",
          html: error.responseText,
          showCancelButton: false,
          confirmButtonText: "Aceptar",
          customClass: {
            actions: "d-flex justify-content-around",
            confirmButton: "btn-custom btn-custom--border-blue",
          },
          buttonsStyling: false,
          allowEnterKey: false,
        }).then((result) => {
          if (result.isConfirmed) {
            Swal.close();
          }
        });
      } else if (error.responseText.includes("Formato incorrecto") == true) {
        $("#dataexcel").val("");
        $("#loaderValidacion").css("display", "none");
        Swal.fire({
          title: "Error",
          icon: "error",
          html: "<p style='font-size: 15px'>Formato incorrecto</p>",
          showCancelButton: false,
          confirmButtonText: "Aceptar",
          customClass: {
            actions: "d-flex justify-content-around",
            confirmButton: "btn-custom btn-custom--border-blue",
          },
          buttonsStyling: false,
          allowEnterKey: false,
        }).then((result) => {
          if (result.isConfirmed) {
            Swal.close();
          }
        });
      } else {
        $("#loaderValidacion").css("display", "none");
        importExcel();
      }
    },
  });
}

function importExcel() {
  $("#loaderImportacion").css("display", "block");

  var sucursal = $("#txtSucursal").val();

  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "save_data",
      funcion: "save_headerInitialStock",
      data: sucursal,
    },
    dataType: "json",
    async: false,
    success: function (respuesta) {
      console.log(
        "respuesta de guardar la cabecera del  inventario inicial con archivo de excel: ",
        respuesta
      );
    },
    error: function (error) {
      console.log(error);
    },
  });

  var formData = new FormData();
  formData.append("dataexcel", dataexcel.files[0]);
  formData.append("sucursal", $("#txtSucursal").val());
  console.log(formData.get("dataexcel"));
  console.log(formData.get("sucursal"));
  $.ajax({
    url: "upload.php",
    type: "POST",
    data: formData,
    dataType: "json",
    async: false,
    processData: false,
    contentType: false,
    success: function (respuesta) {
      console.log(respuesta);
      $("#tblInventariosIniciales").DataTable().ajax.reload();
      $("#dataexcel").val("");
    },
    error: function (error) {
      console.log(error);
      $("#loaderImportacion").css("display", "none");

      Swal.fire({
        title: "Datos importados",
        icon: "success",
        showCancelButton: false,
        confirmButtonText: "Aceptar",
        customClass: {
          actions: "d-flex justify-content-around",
          confirmButton: "btn-custom btn-custom--border-blue",
        },
        buttonsStyling: false,
        allowEnterKey: false,
      }).then((result) => {
        if (result.isConfirmed) {
          Swal.close();
        }
      });
      $("#tblInventariosIniciales").DataTable().ajax.reload();
      $("#dataexcel").val("");
    },
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
    lengthMenu: `Mostrar: 
                  <select class="mb-n2 mt-1">
                    <option value="20">20</option>
                    <option value="100">100</option>
                    <option value="200">200</option>
                  <select> productos`,
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