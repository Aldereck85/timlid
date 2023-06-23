/* Variables */

let varContainer = ""; //variable que guarda la clase que se va a ocultar con click fuera.
let selectedColumns = []; //Guardará info de las columnas seleccionadas para mostrar
let array = [];

/// Enum ASC or DESC
const orderBy = {
  ASC: "ASC",
  DESC: "DESC",
};

var _filterQuery = {
  search: "",
  order: {
    sort: orderBy.DESC,
    column: 1,
  },
};

var _permissions = {
  read: 0,
  add: 0,
  edit: 0,
  delete: 0,
  export: 0,
};

//Obtener la Lista de las columnas disponibles para la tabla
$(document).ready(function () {
  validate_Permissions(10);

  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "lista_columnas" },
    dataType: "json",
    success: function (respuesta) {
      console.log("Respuesta lista_columnas: ", respuesta);

      //Variables de bloque para conocer si esta seleccionada la columna y que acción hacer al darle click
      let classCheck;
      let onclick;

      for (i = 0; i < respuesta.length; i++) {
        //Asignar valor a la variable de seleccion
        if (respuesta[i].Seleccionada == 1) {
          //Clase y onclick para check en los primeros 5 valores
          classCheck = "checked-type-column";
          onclick =
            'onclick="deseleccionar(' + respuesta[i].PKColumnasClientes + ')"';

          //Asignar los valores al arreglo
          selectedColumns.push([
            respuesta[i].PKColumnasClientes,
            respuesta[i].FKTipoColumnaClientes,
          ]);

          //Clase y onclick para valores que se mostrarán en la modal
          classCheckColumn = "checked";
          onclickColumn =
            'onclick="deseleccionarModal(' +
            respuesta[i].PKColumnasClientes +
            ')"';
        } else {
          //Clase y onclick en los primeros 5 valores
          classCheck = "check-type-column";
          onclick =
            'onclick="seleccionar(' + respuesta[i].PKColumnasClientes + ')"';

          //Clase y onclick para valores que se mostrarán en la modal
          classCheckColumn = "";
          onclickColumn =
            'onclick="seleccionarModal(' +
            respuesta[i].PKColumnasClientes +
            ')"';
        }
      }
      get_info();
    },
    error: function (error) {
      console.log(error);
    },
  });

  //Obtener las columnas seleccionadas para mostrarlas en la pantalla
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "orden_columnas" },
    dataType: "json",
    success: function (respuesta) {
      console.log("Respuesta orden_columnas: ", respuesta);

      for (i = 0; i < respuesta.length; i++) {
        if (respuesta[i].FKTipoColumnaClientes != 1) {
          $("#sortableColumns").append(
            '<div id="col_' +
              respuesta[i].PKColumnasClientes +
              '" data-pos=' +
              respuesta[i].PKColumnasClientes +
              ">" +
              '<div class="columna-cliente handle column-header">' +
              '<div class="column-title">' +
              respuesta[i].Nombre +
              '</div><div><a class="column-order" id="sort-' +
              respuesta[i].PKColumnasClientes +
              '" data-sort="0" href="#"><i class="fas fa-sort"></i></a></div>' +
              "</div>" +
              '<div id="column-' +
              respuesta[i].PKColumnasClientes +
              '" class="columna-info"></div>' +
              "</div>"
          );
        } else {
          $("#noSortableColumns").append(
            '<div id="col_' +
              respuesta[i].PKColumnasClientes +
              '" data-pos=' +
              respuesta[i].PKColumnasClientes +
              ">" +
              '<div class="columna-cliente column-header">' +
              '<div class="column-title">' +
              respuesta[i].Nombre +
              '</div><div><a class="column-order" id="sort-' +
              respuesta[i].PKColumnasClientes +
              '" data-sort="0" href="#"><i class="fas fa-sort"></i></a></div>' +
              "</div>" +
              '<div id="column-' +
              respuesta[i].PKColumnasClientes +
              '" class="columna-info"></div>' +
              "</div>"
          );
        }
      }

      console.log("selectedColumns en orden_columnas", selectedColumns);
    },
    error: function (error) {
      console.log(error);
    },
  });

  //Pintar la fila sobre la que se posiciona
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "obtener_ids" },
    dataType: "json",
    success: function (respuesta) {
      $.each(respuesta, function (i) {
        let isActive = respuesta[i].estatus == 1;
        /*$(document).on(
          "mouseenter",
          "#idCliente-" + respuesta[i].PKCliente,
          function () {
            $(".idCliente-" + respuesta[i].PKCliente).css(
              "display",
              "inline-block"
            );
            $(".idCliente-" + respuesta[i].PKCliente).css(
              "text-align",
              "center"
            );
            //(".idCliente-" + respuesta[i].PKCliente).css("margin-left", "40%");
            $("#edit-icon-" + respuesta[i].PKCliente).css(
              "display",
              "inline-block"
            );
            $("#edit-icon-" + respuesta[i].PKCliente).css("float", "right");
            if (isActive) {
              if (_permissions.delete == '1') {
                $("#delete-icon-" + respuesta[i].PKCliente).css(
                "display",
                "inline-block"
                );
                $("#delete-icon-" + respuesta[i].PKCliente).css("float", "right");
              }
            }
          }
        );
        $(document).on(
          "mouseleave",
          "#idCliente-" + respuesta[i].PKCliente,
          function () {
            $(".idCliente-" + respuesta[i].PKCliente).css(
              "display",
              "inline-block"
            );
            $(".idCliente-" + respuesta[i].PKCliente).css(
              "text-align",
              "center"
            );
            $(".idCliente-" + respuesta[i].PKCliente).css("margin-left", "0px");
            $("#edit-icon-" + respuesta[i].PKCliente).css("display", "none");
            if (isActive) { 
              if (_permissions.delete == '1') {
                $("#delete-icon-" + respuesta[i].PKCliente).css("display", "none");
              }
            }
          }
        );*/

        $(document).on(
          "mouseenter",
          ".row-" + respuesta[i].PKCliente,
          function () {
            //$('#idCliente-'+respuesta[i].PKCliente).css('background-color','rgba(192,247,231,3.0)');
            $("#NombreComercial-" + respuesta[i].PKCliente).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $(".NombreComercialtexto-" + respuesta[i].PKCliente).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $("#MedioContacto-" + respuesta[i].PKCliente).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $(".MedioContactotexto-" + respuesta[i].PKCliente).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $("#FechaAlta-" + respuesta[i].PKCliente).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $(".FechaAltatexto-" + respuesta[i].PKCliente).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $("#Telefono-" + respuesta[i].PKCliente).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $(".Telefonotexto-" + respuesta[i].PKCliente).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $("#Email-" + respuesta[i].PKCliente).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $(".Emailtexto-" + respuesta[i].PKCliente).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $("#FechaUltimoContacto-" + respuesta[i].PKCliente).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $(".FechaUltimoContactotexto-" + respuesta[i].PKCliente).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $("#FechaSiguienteContacto-" + respuesta[i].PKCliente).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $(".FechaSiguienteContactotexto-" + respuesta[i].PKCliente).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $("#MontoCredito-" + respuesta[i].PKCliente).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $(".MontoCreditotexto-" + respuesta[i].PKCliente).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $("#DiasCredito-" + respuesta[i].PKCliente).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $(".DiasCreditotexto-" + respuesta[i].PKCliente).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $("#FKEstatusGeneral-" + respuesta[i].PKCliente).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $(".FKEstatusGeneraltexto-" + respuesta[i].PKCliente).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $("#FKVendedor-" + respuesta[i].PKCliente).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $(".FKVendedortexto-" + respuesta[i].PKCliente).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
          }
        );

        $(document).on(
          "mouseleave",
          ".row-" + respuesta[i].PKCliente,
          function () {
            /*$(this).css('background-color','white');
					$('#idCliente-'+respuesta[i].PKCliente).css('background-color','white');*/
            $("#NombreComercial-" + respuesta[i].PKCliente).css(
              "background-color",
              "white"
            );
            $(".NombreComercialtexto-" + respuesta[i].PKCliente).css(
              "background-color",
              "white"
            );
            $("#MedioContacto-" + respuesta[i].PKCliente).css(
              "background-color",
              "white"
            );
            $(".MedioContactotexto-" + respuesta[i].PKCliente).css(
              "background-color",
              "white"
            );
            $("#FechaAlta-" + respuesta[i].PKCliente).css(
              "background-color",
              "white"
            );
            $(".FechaAltatexto-" + respuesta[i].PKCliente).css(
              "background-color",
              "white"
            );
            $("#Telefono-" + respuesta[i].PKCliente).css(
              "background-color",
              "white"
            );
            $(".Telefonotexto-" + respuesta[i].PKCliente).css(
              "background-color",
              "white"
            );
            $("#Email-" + respuesta[i].PKCliente).css(
              "background-color",
              "white"
            );
            $(".Emailtexto-" + respuesta[i].PKCliente).css(
              "background-color",
              "white"
            );
            $("#FechaUltimoContacto-" + respuesta[i].PKCliente).css(
              "background-color",
              "white"
            );
            $(".FechaUltimoContactotexto-" + respuesta[i].PKCliente).css(
              "background-color",
              "white"
            );
            $("#FechaSiguienteContacto-" + respuesta[i].PKCliente).css(
              "background-color",
              "white"
            );
            $(".FechaSiguienteContactotexto-" + respuesta[i].PKCliente).css(
              "background-color",
              "white"
            );
            $("#MontoCredito-" + respuesta[i].PKCliente).css(
              "background-color",
              "white"
            );
            $(".MontoCreditotexto-" + respuesta[i].PKCliente).css(
              "background-color",
              "white"
            );
            $("#DiasCredito-" + respuesta[i].PKCliente).css(
              "background-color",
              "white"
            );
            $(".DiasCreditotexto-" + respuesta[i].PKCliente).css(
              "background-color",
              "white"
            );
            $("#FKEstatusGeneral-" + respuesta[i].PKCliente).css(
              "background-color",
              "white"
            );
            $(".FKEstatusGeneraltexto-" + respuesta[i].PKCliente).css(
              "background-color",
              "white"
            );
            $("#FKVendedor-" + respuesta[i].PKCliente).css(
              "background-color",
              "white"
            );
            $(".FKVendedortexto-" + respuesta[i].PKCliente).css(
              "background-color",
              "white"
            );
          }
        );

        $(document).on(
          "click",
          "#edit-tabs-" + respuesta[i].PKCliente,
          function () {
            var data = $(this).data("id");

            window.location.href = "editar_cliente.php?c=" + data;

            //
            //alert('Pendiente de desarrollo y diseño\n'+$(this).data("id"));
            //console.log($(this).data("id"));
          }
        );

        $(document).on(
          "click",
          "#edit-tabs2-" + respuesta[i].PKCliente,
          function () {
            var data = $(this).data("id");

            obtenerIdClienteEliminar(data);
            $("#eliminar_Cliente").modal("show");

            //
            //alert('Pendiente de desarrollo y diseño\n'+$(this).data("id"));
            //console.log($(this).data("id"));
          }
        );
      });
    },
  });

  $("#importExcel").prop("disabled", true);

  $("#dataexcel").on("change", () => {
    if (!$("#dataexcel").val()) {
      $("#importExcel").prop("disabled", true);
    } else {
      $("#importExcel").prop("disabled", false);
    }
  });
});

//activa diseño de los tooltips, usando elmentos del DOM
function activatetool(n) {
  $('[data-toggle="tooltip"]').tooltip({
    trigger: "hover",
    html: true,
  });
  $('[data-toggle="tooltip"]').on("click", function () {
    $(n).tooltip("dispose");
  });
}

function validarExcel() {
  $("#loaderValidacion").css("display", "block");

  var formData = new FormData();
  formData.append("dataexcel", dataexcel.files[0]);
  $.ajax({
    url: "validarExcel.php",
    type: "POST",
    data: formData,
    dataType: "json",
    processData: false,
    contentType: false,
    success: function (respuesta) {
      console.log(respuesta);
    },
    error: function (error) {
      console.log(error.responseText);
      if (error.responseText.includes("Formato no aceptado") == true) {
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
            $("#dataexcel").val("");
            Swal.close();
          }
          if (result.isDismissed) {
            $("#dataexcel").val("");
            Swal.close();
          }
        });
      } else if (error.responseText.includes("error") == true) {
        $("#loaderValidacion").css("display", "none");
        Swal.fire({
          title: "Error",
          icon: "error",
          html: error.responseText.slice(5),
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
            $("#dataexcel").val("");
            Swal.close();
          }
          if (result.isDismissed) {
            $("#dataexcel").val("");
            Swal.close();
          }
        });
      } else if (error.responseText.includes("Formato incorrecto") == true) {
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
            $("#dataexcel").val("");
            Swal.close();
          }
          if (result.isDismissed) {
            $("#dataexcel").val("");
            Swal.close();
          }
        });
      } else if (
        error.responseText != "" &&
        error.responseText.includes("Formato incorrecto") == false &&
        error.responseText.includes("Formato no aceptado") == false
      ) {
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
            $("#dataexcel").val("");
            Swal.close();
          }
          if (result.isDismissed) {
            $("#dataexcel").val("");
            Swal.close();
          }
        });
      } else if (error.responseText.includes("fail")) {
        $("#loaderValidacion").css("display", "none");
        Swal.fire({
          title: "Error",
          icon: "error",
          html: "No se pudo subir el archivo",
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
            $("#dataexcel").val("");
            Swal.close();
          }
          if (result.isDismissed) {
            $("#dataexcel").val("");
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

  var formData = new FormData();
  formData.append("dataexcel", dataexcel.files[0]);
  $.ajax({
    url: "uploadExcel.php",
    type: "POST",
    data: formData,
    dataType: "json",
    async: false,
    processData: false,
    contentType: false,
    success: function (respuesta) {
      console.log(respuesta);
      $("#tblInventariosIniciales").DataTable().ajax.reload();
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
          location.reload();
        } else if (result.isDismissed) {
          Swal.close();
          location.reload();
        }
      });
    },
  });
}

/* Seleccionar y deseleccionar las columnas cambiando sus colores y clases */
function deseleccionar(pkColumnaCliente) {
  console.log("Deseleccionar en primera vista");

  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "edit_data",
      funcion: "update_check_column",
      data: pkColumnaCliente,
      flag: 0,
    },
    dataType: "json",
    success: function (respuesta) {
      console.log("Respuesta :" + respuesta[0].status);
      if (respuesta[0].status) {
        //Asignar clases para activar y desactivar checks en las modales de selección
        $("#checkType-" + pkColumnaCliente).removeClass("checked-type-column");
        $("#checkType-" + pkColumnaCliente).addClass("check-type-column");

        $("#checkType-" + pkColumnaCliente).removeAttr("onclick");
        $("#checkType-" + pkColumnaCliente).attr(
          "onclick",
          "seleccionar(" + pkColumnaCliente + ")"
        );

        //Quitar columna de la tabla
        $("#col_" + pkColumnaCliente).remove();
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function deseleccionarModal(pkColumnaCliente) {
  console.log("Deseleccionar en modal");

  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "edit_data",
      funcion: "update_check_column",
      data: pkColumnaCliente,
      flag: 0,
    },
    dataType: "json",
    success: function (respuesta) {
      console.log(respuesta[0].status);
      if (respuesta[0].status) {
        //Asignar clases para activar y desactivar checks en las modales de selección
        $("#checkType-" + pkColumnaCliente).removeClass("checked");
        //$("#checkType-" + pkColumnaCliente).addClass("check-type-column-modal");

        $("#checkType-" + pkColumnaCliente).removeAttr("onclickColumn");
        $("#checkType-" + pkColumnaCliente).attr(
          "onclick",
          "seleccionarModal(" + pkColumnaCliente + ")"
        );

        //Quitar columna de la tabla
        $("#col_" + pkColumnaCliente).remove();
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function seleccionar(pkColumnaCliente) {
  console.log("Seleccionar en primera vista");
  mostrar();
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "edit_data",
      funcion: "update_check_column",
      data: pkColumnaCliente,
      flag: 1,
    },
    dataType: "json",
    success: function (respuesta) {
      if (respuesta[0].status) {
        //Asignar clases para activar y desactivar checks en las modales de selección
        $("#checkType-" + pkColumnaCliente).removeClass("check-type-column");
        $("#checkType-" + pkColumnaCliente).addClass("checked-type-column");

        $("#checkType-" + pkColumnaCliente).removeAttr("onclick");
        $("#checkType-" + pkColumnaCliente).attr(
          "onclick",
          "deseleccionar(" + pkColumnaCliente + ")"
        );

        //Mostrar columna
        $("#sortableColumns").append(
          '<div id="col_' +
            pkColumnaCliente +
            '" data-pos=' +
            pkColumnaCliente +
            ">" +
            '<div class="columna-cliente handle column-header">' +
            '<div class="column-title">' +
            respuesta[0].array[0].columnaAfectada +
            '</div><div><a class="column-order" id="sort-' +
            pkColumnaCliente +
            '" data-sort="0" href="#"><i class="fas fa-sort"></i></a></div>' +
            "</div>" +
            '<div id="column-' +
            pkColumnaCliente +
            '" class="columna-info"></div>' +
            "</div>"
        );
        selectedColumns.push([
          pkColumnaCliente,
          respuesta[0].array[0].tipoColumna,
        ]);

        console.log("SE SELECCIONO COLUMNA", selectedColumns);

        get_info();

        let coord = $("#col_" + pkColumnaCliente).offset();

        document.getElementById("boardContent").scrollLeft += coord.left;
        ocultar();
      } else {
        Swal.fire({
          title: "Datos no cargan",
          html: "No hay datos en la base de datos.",
          icon: "error",
          showConfirmButton: true,
          confirmButtonText: 'Salir  <i class="far fa-times-circle"></i>',
          buttonsStyling: false,
          allowEnterKey: false,
        });
      }
    },
    error: function (error) {
      console.log(error);
    },
    complete: function (_, __) {
      ocultar();
    },
  });
}

function seleccionarModal(pkColumnaCliente) {
  console.log("Seleccionar en modal");
  mostrar();
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "edit_data",
      funcion: "update_check_column",
      data: pkColumnaCliente,
      flag: 1,
    },
    dataType: "json",
    success: function (respuesta) {
      if (respuesta[0].status) {
        //$("#checkType-" + pkColumnaCliente).removeClass("check-type-column-modal");
        $("#checkType-" + pkColumnaCliente).addClass("checked");

        $("#checkType-" + pkColumnaCliente).removeAttr("onclickColumn");
        $("#checkType-" + pkColumnaCliente).attr(
          "onclick",
          "deseleccionarModal(" + pkColumnaCliente + ")"
        );

        //Mostrar columna
        $("#sortableColumns").append(
          '<div id="col_' +
            pkColumnaCliente +
            '" data-pos=' +
            pkColumnaCliente +
            ">" +
            '<div class="columna-cliente handle column-header">' +
            '<div class="column-title">' +
            respuesta[0].array[0].columnaAfectada +
            '</div><div><a class="column-order" id="sort-' +
            pkColumnaCliente +
            '" data-sort="0" href="#"><i class="fas fa-sort"></i></a></div>' +
            "</div>" +
            '<div id="column-' +
            pkColumnaCliente +
            '" class="columna-info"></div>' +
            "</div>"
        );
        selectedColumns.push([
          pkColumnaCliente,
          respuesta[0].array[0].tipoColumna,
        ]);

        console.log("SE SELECCIONO COLUMNA", selectedColumns);

        get_info();

        let coord = $("#col_" + pkColumnaCliente).offset();

        document.getElementById("boardContent").scrollLeft += coord.left;
        ocultar();
      }
    },
    error: function (error) {
      console.log(error);
    },
    complete: function (_, __) {
      ocultar();
    },
  });
}

function mostrar() {
  $("#loader").fadeIn("slow");
}

function ocultar() {
  $("#loader").fadeOut("slow");
}

/* Obtener Información de las columnas */
function get_info() {
  if (selectedColumns !== null && selectedColumns.length !== "") {
    console.log("Array columnas: ", selectedColumns);
    $.ajax({
      url: "../../php/funciones.php",
      data: {
        clase: "get_data",
        funcion: "orden_datos",
        sort: _filterQuery.order.sort,
        search: _filterQuery.search,
        indice: _filterQuery.order.column,
      },
      dataType: "json",
      beforeSend: (_, __) => mostrar(),
      success: _onSuccess,
      error: function (error) {
        console.log(error);
      },
      complete: (_, __) => ocultar(),
    });
  } else {
    console.log("El array no se cargo...");
  }
  getSortable();
  dataSortMain();
}

/// Llamada a paginacion
function _onSuccess(respuesta) {
  console.log("respuesta desde get info: ", respuesta);

  $("#pagination-bar").pagination({
    dataSource: respuesta,
    pageSize: 25,
    pageRange: 1,
    prevText: "",
    nextText: "",
    className: "paginationjs-theme-timlid paginationjs-big",
    //autoHidePrevious: true,
    //autoHideNext: true,
    callback: function (data, _) {
      $("#sin-coincidencias").remove();
      var html = loopTemplates(data);
      console.log("lslectedColumn:", selectedColumns);

      $.each(selectedColumns, function (i) {
        console.log("longitud respuesta get info:", respuesta.length);

        if (respuesta.length > 0) {
          if (selectedColumns[i][1] == 1) {
            //ID del producto
            $("#column-" + selectedColumns[i][0]).html(html.idCliente);
          }
          if (selectedColumns[i][1] == 2) {
            //Nombre del producto
            $("#column-" + selectedColumns[i][0]).html(html.nombreComercial);
          }
          if (selectedColumns[i][1] == 3) {
            //Nombre del producto
            $("#column-" + selectedColumns[i][0]).html(html.medioContacto);
          }
          if (selectedColumns[i][1] == 4) {
            //Clave interna del producto
            $("#column-" + selectedColumns[i][0]).html(html.fechaAlta);
          }
          if (selectedColumns[i][1] == 5) {
            //Codigo de barras del producto
            $("#column-" + selectedColumns[i][0]).html(html.telefono);
          }
          if (selectedColumns[i][1] == 6) {
            //CategoriaProductos del producto
            $("#column-" + selectedColumns[i][0]).html(html.email);
          }
          if (selectedColumns[i][1] == 7) {
            //Marca del producto
            $("#column-" + selectedColumns[i][0]).html(
              html.fechaUltimoContacto
            );
          }
          if (selectedColumns[i][1] == 8) {
            //Descripcion del producto
            $("#column-" + selectedColumns[i][0]).html(
              html.fechaSiguienteContacto
            );
          }
          if (selectedColumns[i][1] == 9) {
            //Tipo de del producto
            $("#column-" + selectedColumns[i][0]).html(html.montoCredito);
          }
          if (selectedColumns[i][1] == 10) {
            //Imagen del producto
            $("#column-" + selectedColumns[i][0]).html(html.diasCredito);
          }
          if (selectedColumns[i][1] == 11) {
            //Estatus del producto
            $("#column-" + selectedColumns[i][0]).html(html.estatusGeneral);
          }
          if (selectedColumns[i][1] == 12) {
            //Estatus del producto
            $("#column-" + selectedColumns[i][0]).html(html.vendedor);
          }
          if (selectedColumns[i][1] == 13) {
            //Estatus del producto
            $("#column-" + selectedColumns[i][0]).html(html.razonSocial);
          }
          if (selectedColumns[i][1] == 14) {
            //Estatus del producto
            $("#column-" + selectedColumns[i][0]).html(html.rfc);
          }
        } else {
          $(".hideEmployer").hide();
          if (_filterQuery.search === null || _filterQuery.search === "") {
            $("#noSearch").html(
              '<div id="sin-coincidencias" style="margin:50px 0 100px 0;" class="text-center"><h1 class="h5 text-blutTim">Ningún dato disponible en esta  tabla</h1></div>'
            );
          } else {
            $("#noSearch").html(
              '<div id="sin-coincidencias" style="margin:170px 0 100px 0;" class="text-center"><img src="../../../tareas/timDesk/img/icons/fail.svg" width="80" height="80"></br></br><h1 class="h5 text-blutTim">No se encontraron coincidencias en la búsqueda</h1></div>'
            );
          }
        }
      });
      var prev = $(".paginationjs-prev a");
      var next = $(".paginationjs-next a");
      prev.html(
        "<img src='../../../../img/icons/pagination.svg' width='15px' style='-webkit-transform: scaleX(-1); transform=scaleX(-1);'>"
      );
      next.html(
        "<img src='../../../../img/icons/pagination.svg' width='15px'>"
      );
    },
  });
}

//Funciones con el diseño de la columas y sus respectivos datos

function loopTemplates(data) {
  var html = {
    idCliente: "",
    nombreComercial: "",
    medioContacto: "",
    fechaAlta: "",
    telefono: "",
    email: "",
    fechaUltimoContacto: "",
    fechaSiguienteContacto: "",
    montoCredito: "",
    diasCredito: "",
    estatusGeneral: "",
    vendedor: "",
    razonSocial: "",
    rfc: "",
  };

  for (var j = 0; j < data.length; j++) {
    html.idCliente += templateIdCliente(data[j]);
    html.nombreComercial += templateNombreComercial(data[j]);
    html.medioContacto += templateMedioContacto(data[j]);
    html.fechaAlta += templateFechaAlta(data[j]);
    html.telefono += templateTelefono(data[j]);
    html.email += templateEmail(data[j]);
    html.fechaUltimoContacto += templateFechaUltimoContacto(data[j]);
    html.fechaSiguienteContacto += templateFechaSiguienteContacto(data[j]);
    html.montoCredito += templateMontoCredito(data[j]);
    html.diasCredito += templateDiasCredito(data[j]);
    html.estatusGeneral += templateEstatusGeneral(data[j]);
    html.vendedor += templateVendedor(data[j]);
    html.razonSocial += templateRazonSocial(data[j]);
    html.rfc += templateRFC(data[j]);
  }
  //console.log(html);
  return html;
}

//PKCliente para la paginación
function templateIdCliente(data) {
  var btEliminar = "";
  var btEditar = "";
  var btDetalles = "";

  if (_permissions.delete == "1") {
    btEliminar = `<i class="fas fa-trash-alt color-primary" tyle="cursor:pointer; padding-right: 5px;"  width="30px" height="30px" data-toggle="tooltip" title="Eliminar cliente" id="delete-icon-${data.PKCliente}"></i>`;
  } else {
    btEliminar = "";
  }

  if (_permissions.edit == "1") {
    btEditar = `<i class="fas fa-edit color-primary" tyle="cursor:pointer; padding-right: 5px;"  width="30px" height="30px" data-toggle="tooltip" title="Editar cliente" onmouseover="activatetool(this)" id="edit-icon-${data.PKCliente}"></i>`;
  } else {
    btEditar = "";
  }
  btDetalles = `<i class="fas fa-clipboard-list color-primary pointer" style="cursor:pointer; padding-right: 5px;"  width="30px" height="30px" data-toggle="tooltip" title="Ver detalle" id="detalle-icon-${data.PKCliente}"></i>`;

  if (data.FKEstatusGeneral == "Inactivo") {
    return `<div class="hideEmployer show-icon-edit b-bottom row-${data.PKCliente}" id="idCliente-${data.PKCliente}" style="height: 36px; color:black; border-left: 5px solid #cac8c6; color: #FFFFFF;" title="Cliente inactivo">
              <span class="idCliente-${data.PKCliente}" style="margin-left: auto; margin-right: auto; display: block;">
                <a id="det-tabs-${data.PKCliente}" data-id="${data.PKCliente}" href="#">
                  ${btDetalles}
                </a>
                <a id="edit-tabs-${data.PKCliente}" data-id="${data.PKCliente}" href="#">
                  ${btEditar}
                </a>
                <a id="edit-tabs2-${data.PKCliente}" data-id="${data.PKCliente}" href="#">
                  ${btEliminar}
                </a>
              </span>
            </div>
            `;
  } else {
    return `<div class="hideEmployer show-icon-edit b-bottom row-${data.PKCliente}" id="idCliente-${data.PKCliente}" style="height: 36px; color:black; border-left: 5px solid #28c67a;; color: #FFFFFF;" title="Cliente activo">
              <div class="col-lg-12 input-group">
                <span class="idCliente-${data.PKCliente}" style="margin-left: auto; margin-right: auto; display: block;">
                  <a id="det-tabs-${data.PKCliente}" data-id="${data.PKCliente}" href="detalles_cliente.php?c=${data.PKCliente}">
                    ${btDetalles}
                  </a>
                  <a id="edit-tabs-${data.PKCliente}" data-id="${data.PKCliente}" href="#">
                    ${btEditar}
                  </a>
                  <a id="edit-tabs2-${data.PKCliente}" data-id="${data.PKCliente}" href="#">
                    ${btEliminar}
                  </a>
                </span>
              </div>
            </div>
            `;
  }
}

//Nombre Comercial para la paginación
function templateNombreComercial(data) {
  var nombreComercial = data.NombreComercial.replace(/"/g, "&quot;");
  if (data.NombreComercial == null) {
    return (
      '<div class="hideEmployer b-bottom row-' +
      data.PKCliente +
      '" id="NombreComercial-' +
      data.PKCliente +
      '"><input class="text-center border-n NombreComercialtexto-' +
      data.PKCliente +
      '" type="text" ondblclick="editar_elemento(' +
      data.PKCliente +
      ')" value="" readonly style="width: 230px;white-space: nowrap; text-overflow: ellipsis; overflow: hidden;" title="Sin información"></div>'
    );
  } else {
    return (
      '<div class="hideEmployer b-bottom row-' +
      data.PKCliente +
      '" id="NombreComercial-' +
      data.PKCliente +
      '"><input class="text-center border-n NombreComercialtexto-' +
      data.PKCliente +
      '" type="text" ondblclick="editar_elemento(' +
      data.PKCliente +
      ')" value="' +
      nombreComercial +
      '" readonly style="width: 230px;white-space: nowrap; text-overflow: ellipsis; overflow: hidden;" title="' +
      nombreComercial +
      '"></div>'
    );
  }
}

//Medio de Contacto para la paginación
function templateMedioContacto(data) {
  if (data.FKMedioContactoCliente == null) {
    return (
      '<div class="hideEmployer b-bottom row-' +
      data.PKCliente +
      '" id="MedioContacto-' +
      data.PKCliente +
      '"><input class="text-center border-n MedioContactotexto-' +
      data.PKCliente +
      '" type="text" ondblclick="editar_elemento(' +
      data.PKCliente +
      ')" value="" readonly style="width: 230px;white-space: nowrap; text-overflow: ellipsis; overflow: hidden;" title="Sin información"></div>'
    );
  } else {
    return (
      '<div class="hideEmployer b-bottom row-' +
      data.PKCliente +
      '" id="MedioContacto-' +
      data.PKCliente +
      '"><input class="text-center border-n MedioContactotexto-' +
      data.PKCliente +
      '" type="text" ondblclick="editar_elemento(' +
      data.PKCliente +
      ')" value="' +
      data.FKMedioContactoCliente +
      '" readonly style="width: 230px;white-space: nowrap; text-overflow: ellipsis; overflow: hidden;" title="' +
      data.FKMedioContactoCliente +
      '"></div>'
    );
  }
}

//Fecha alta para la paginación
function templateFechaAlta(data) {
  if (data.FechaAlta == null) {
    return (
      '<div class="hideEmployer b-bottom row-' +
      data.PKCliente +
      '" id="FechaAlta-' +
      data.PKCliente +
      '"><input class="text-center border-n FechaAltatexto-' +
      data.PKCliente +
      '" type="text" ondblclick="editar_elemento(' +
      data.PKCliente +
      ',5)" value="" readonly style="width: 230px;white-sce: nowrap; text-overflow: ellipsis; overflow: hidden;" title="Sin información"></div>'
    );
  } else {
    return (
      '<div class="hideEmployer b-bottom row-' +
      data.PKCliente +
      '" id="FechaAlta-' +
      data.PKCliente +
      '"><input class="text-center border-n FechaAltatexto-' +
      data.PKCliente +
      '" type="text" ondblclick="editar_elemento(' +
      data.PKCliente +
      ',5)" value="' +
      data.FechaAlta +
      '" readonly style="width: 230px;white-space: nowrap; text-overflow: ellipsis; overflow: hidden;" title="' +
      data.FechaAlta +
      '"></div>'
    );
  }
}

//Telefono para la paginación
function templateTelefono(data) {
  if (data.Telefono == null || data.Telefono == "") {
    return (
      '<div class="hideEmployer b-bottom row-' +
      data.PKCliente +
      '" id="Telefono-' +
      data.PKCliente +
      '"><input class="text-center border-n Telefonotexto-' +
      data.PKCliente +
      '" type="text" ondblclick="editar_elemento(' +
      data.PKCliente +
      ',5)" value="" readonly style="width: 230px;white-ace: nowrap; text-overflow: ellipsis; overflow: hidden;" title="Sin información"></div>'
    );
  } else {
    return (
      '<div class="hideEmployer b-bottom row-' +
      data.PKCliente +
      '" id="Telefono-' +
      data.PKCliente +
      '"><input class="text-center border-n Telefonotexto-' +
      data.PKCliente +
      '" type="text" ondblclick="editar_elemento(' +
      data.PKCliente +
      ',5)" value="' +
      data.Telefono +
      '" readonly style="width: 230px;white-space: nowrap; text-overflow: ellipsis; overflow: hidden;" title="' +
      data.Telefono +
      '"></div>'
    );
  }
}

//Email para la paginación
function templateEmail(data) {
  if (data.Email == null) {
    return (
      '<div class="hideEmployer b-bottom row-' +
      data.PKCliente +
      '" id="Email-' +
      data.PKCliente +
      '"><input class="text-center border-n Emailtexto-' +
      data.PKCliente +
      '" type="text" ondblclick="editar_elemento(' +
      data.PKCliente +
      ',5)" value="" readonly style="widt 230px;white-space: nowrap; text-overflow: ellipsis; overflow: hidden;" title="Sin información"></div>'
    );
  } else {
    return (
      '<div class="hideEmployer b-bottom row-' +
      data.PKCliente +
      '" id="Email-' +
      data.PKCliente +
      '"><input class="text-center border-n Emailtexto-' +
      data.PKCliente +
      '" type="text" ondblclick="editar_elemento(' +
      data.PKCliente +
      ',5)" value="' +
      data.Email +
      '" readonly style="width: 230px;white-space: nowrap; text-overflow: ellipsis; overflow: hidden;" title="' +
      data.Email +
      '"></div>'
    );
  }
}

//Fecha del Ultimo Contacto para la paginación
function templateFechaUltimoContacto(data) {
  if (data.Fecha_Ultimo_Contacto == null) {
    return (
      '<div class="hideEmployer b-bottom row-' +
      data.PKCliente +
      '" id="FechaUltimoContacto-' +
      data.PKCliente +
      '"><input class="text-center border-n FechaUltimoContactotexto-' +
      data.PKCliente +
      '" type="text" ondblclick="editar_elemento(' +
      data.PKCliente +
      ')" value="" readonly style="width: 230px;white-space: nowrap; text-overflow: ellipsis; overflow: hidden;" title="Sin información"></div>'
    );
  } else if (data.Fecha_Ultimo_Contacto == "0000-00-00") {
    return (
      '<div class="hideEmployer b-bottom row-' +
      data.PKCliente +
      '" id="FechaUltimoContacto-' +
      data.PKCliente +
      '"><input class="text-center border-n FechaUltimoContactotexto-' +
      data.PKCliente +
      '" type="text" ondblclick="editar_elemento(' +
      data.PKCliente +
      ')" value="No agendado" readonly style="width: 230px;white-space: nowrap; text-overflow: ellipsis; overflow: hidden;" title="' +
      data.Fecha_Ultimo_Contacto +
      '"></div>'
    );
  } else {
    return (
      '<div class="hideEmployer b-bottom row-' +
      data.PKCliente +
      '" id="FechaUltimoContacto-' +
      data.PKCliente +
      '"><input class="text-center border-n FechaUltimoContactotexto-' +
      data.PKCliente +
      '" type="text" ondblclick="editar_elemento(' +
      data.PKCliente +
      ')" value="' +
      data.Fecha_Ultimo_Contacto +
      '" readonly style="width: 230px;white-space: nowrap; text-overflow: ellipsis; overflow: hidden;" title="' +
      data.Fecha_Ultimo_Contacto +
      '"></div>'
    );
  }
}

//Fecha del Siguiente Contacto para la paginación
function templateFechaSiguienteContacto(data) {
  if (data.Fecha_Siguiente_Contacto == null) {
    return (
      '<div class="hideEmployer b-bottom row-' +
      data.PKCliente +
      '" id="FechaSiguienteContacto-' +
      data.PKCliente +
      '"><input class="text-center border-n FechaSiguienteContactotexto-' +
      data.PKCliente +
      '" type="text" ondblclick="editar_elemento(' +
      data.PKCliente +
      ')" value="" readonly style="width: 230px;white-space: nowrap; text-overflow: ellipsis; overflow: hidden;" title="Sin información"></div>'
    );
  } else if (data.Fecha_Siguiente_Contacto == "0000-00-00") {
    return (
      '<div class="hideEmployer b-bottom row-' +
      data.PKCliente +
      '" id="FechaSiguienteContacto-' +
      data.PKCliente +
      '"><input class="text-center border-n FechaSiguienteContactotexto-' +
      data.PKCliente +
      '" type="text" ondblclick="editar_elemento(' +
      data.PKCliente +
      ')" value="No agendado" readonly style="width: 230px;white-space: nowrap; text-overflow: ellipsis; overflow: hidden;" title="' +
      data.Fecha_Siguiente_Contacto +
      '"></div>'
    );
  } else {
    return (
      '<div class="hideEmployer b-bottom row-' +
      data.PKCliente +
      '" id="FechaSiguienteContacto-' +
      data.PKCliente +
      '"><input class="text-center border-n FechaSiguienteContactotexto-' +
      data.PKCliente +
      '" type="text" ondblclick="editar_elemento(' +
      data.PKCliente +
      ')" value="' +
      data.Fecha_Siguiente_Contacto +
      '" readonly style="width: 230px;white-space: nowrap; text-overflow: ellipsis; overflow: hidden;" title="' +
      data.Fecha_Siguiente_Contacto +
      '"></div>'
    );
  }
}

//Monto de credito para la paginación
function templateMontoCredito(data) {
  if (data.Monto_credito == null) {
    return (
      '<div class="hideEmployer b-bottom row-' +
      data.PKCliente +
      '" id="MontoCredito-' +
      data.PKCliente +
      '"><input class="text-center border-n MontoCreditotexto-' +
      data.PKCliente +
      '" type="text" ondblclick="editar_elemento(' +
      data.PKCliente +
      ',5)" value="" readonly style="width: 230px;white-space: nowrap; text-overflow: ellipsis; overflow: hidden;" title="Sin información"></div>'
    );
  } else {
    return (
      '<div class="hideEmployer b-bottom row-' +
      data.PKCliente +
      '" id="MontoCredito-' +
      data.PKCliente +
      '"><input class="text-center border-n MontoCreditotexto-' +
      data.PKCliente +
      '" type="text" ondblclick="editar_elemento(' +
      data.PKCliente +
      ',5)" value="' +
      data.Monto_credito +
      '" readonly style="width: 230px;white-space: nowrap; text-overflow: ellipsis; overflow: hidden;" title="' +
      data.Monto_credito +
      '"></div>'
    );
  }
}

//Días de credito para la paginación
function templateDiasCredito(data) {
  if (data.Dias_credito == null) {
    return (
      '<div class="hideEmployer b-bottom row-' +
      data.PKCliente +
      '" id="DiasCredito-' +
      data.PKCliente +
      '"><input class="text-center border-n DiasCreditotexto-' +
      data.PKCliente +
      '" type="text" ondblclick="editar_elemento(' +
      data.PKCliente +
      ',5)" value="" readonly style="width: 230px;white-space: nowrap; text-overflow: ellipsis; overflow: hidden;" title="Sin información"></div>'
    );
  } else {
    return (
      '<div class="hideEmployer b-bottom row-' +
      data.PKCliente +
      '" id="DiasCredito-' +
      data.PKCliente +
      '"><input class="text-center border-n DiasCreditotexto-' +
      data.PKCliente +
      '" type="text" ondblclick="editar_elemento(' +
      data.PKCliente +
      ',5)" value="' +
      data.Dias_credito +
      '" readonly style="width: 230px;white-space: nowrap; text-overflow: ellipsis; overflow: hidden;" title="' +
      data.Dias_credito +
      '"></div>'
    );
  }
}

//Estatus General para la paginación
function templateEstatusGeneral(data) {
  if (data.FKEstatusGeneral == null) {
    return (
      '<div class="hideEmployer b-bottom row-' +
      data.PKCliente +
      '" id="FKEstatusGeneral-' +
      data.PKCliente +
      '"><input class="text-center border-n FKEstatusGeneraltexto-' +
      data.PKCliente +
      '" type="text" ondblclick="editar_elemento(' +
      data.PKCliente +
      ')" value="" readonly style="width: 230px;white-space: nowrap; text-overflow: ellipsis; overflow: hidden;" title="' +
      data.FKEstatusGeneral +
      '"></div>'
    );
  } else {
    return (
      '<div class="hideEmployer b-bottom row-' +
      data.PKCliente +
      '" id="FKEstatusGeneral-' +
      data.PKCliente +
      '"><input class="text-center border-n FKEstatusGeneraltexto-' +
      data.PKCliente +
      '" type="text" ondblclick="editar_elemento(' +
      data.PKCliente +
      ')" value="' +
      data.FKEstatusGeneral +
      '" readonly style="width: 230px;white-space: nowrap; text-overflow: ellipsis; overflow: hidden;" title="' +
      data.FKEstatusGeneral +
      '"></div>'
    );
  }
}

//Vendedor para la paginación
function templateVendedor(data) {
  if (data.FKVendedor == null) {
    return (
      '<div class="hideEmployer b-bottom row-' +
      data.PKCliente +
      '" id="FKVendedor-' +
      data.PKCliente +
      '"><input class="text-center border-n FKVendedortexto-' +
      data.PKCliente +
      '" type="text" ondblclick="editar_elemento(' +
      data.PKCliente +
      ',5)" value="" readonly style="width: 230px;white-space: nowrap; text-overflow: ellipsis; overflow: hidden;" title="Sin información"></div>'
    );
  } else {
    return (
      '<div class="hideEmployer b-bottom row-' +
      data.PKCliente +
      '" id="FKVendedor-' +
      data.PKCliente +
      '"><input class="text-center border-n FKVendedortexto-' +
      data.PKCliente +
      '" type="text" ondblclick="editar_elemento(' +
      data.PKCliente +
      ',5)" value="' +
      data.FKVendedor +
      '" readonly style="width: 230px;white-space: nowrap; text-overflow: ellipsis; overflow: hidden;" title="' +
      data.FKVendedor +
      '"></div>'
    );
  }
}

//Razón social para la paginación
function templateRazonSocial(data) {
  var razonSocial = data.NombreComercial.replace(/"/g, "&quot;");
  if (data.razon_social == null) {
    return (
      '<div class="hideEmployer b-bottom row-' +
      data.PKCliente +
      '" id="RazonSocial-' +
      data.PKCliente +
      '"><input class="text-center border-n RazonSocialtexto-' +
      data.PKCliente +
      '" type="text" ondblclick="editar_elemento(' +
      data.PKCliente +
      ',5)" value="" readonly style="width: 230px;white-space: nowrap; text-overflow: ellipsis; overflow: hidden;" title="Sin información"></div>'
    );
  } else {
    return (
      '<div class="hideEmployer b-bottom row-' +
      data.PKCliente +
      '" id="RazonSocial-' +
      data.PKCliente +
      '"><input class="text-center border-n RazonSocialtexto-' +
      data.PKCliente +
      '" type="text" ondblclick="editar_elemento(' +
      data.PKCliente +
      ',5)" value="' +
      razonSocial +
      '" readonly style="width: 230px;white-space: nowrap; text-overflow: ellipsis; overflow: hidden;" title="' +
      razonSocial +
      '"></div>'
    );
  }
}

//RFC para la paginación
function templateRFC(data) {
  if (data.rfc == null) {
    return (
      '<div class="hideEmployer b-bottom row-' +
      data.PKCliente +
      '" id="RFC-' +
      data.PKCliente +
      '"><input class="text-center border-n RFCtexto-' +
      data.PKCliente +
      '" type="text" ondblclick="editar_elemento(' +
      data.PKCliente +
      ',5)" value="" readonly style="width: 230px;white-space: nowrap; text-overflow: ellipsis; overflow: hidden;" title="Sin información"></div>'
    );
  } else {
    return (
      '<div class="hideEmployer b-bottom row-' +
      data.PKCliente +
      '" id="RFC-' +
      data.PKCliente +
      '"><input class="text-center border-n RFCtexto-' +
      data.PKCliente +
      '" type="text" ondblclick="editar_elemento(' +
      data.PKCliente +
      ',5)" value="' +
      data.rfc +
      '" readonly style="width: 230px;white-space: nowrap; text-overflow: ellipsis; overflow: hidden;" title="' +
      data.rfc +
      '"></div>'
    );
  }
}

function editar_elemento(id) {
  window.location.href = "editar_cliente.php?c=" + id;
}

function getSortable() {
  new Sortable(sortableColumns, {
    handle: ".handle",
    animation: 150,
    ghostClass: "blue-background-class",
    dataIdAttr: "data-pos",
    scroll: true,
    bubbleScroll: true,
    store: {
      set: function (sortable) {
        var orden = sortable.toArray();
        console.log("orden array: ");
        console.log(orden);

        $.ajax({
          url: "../../php/funciones.php",
          dataType: "json",
          data: {
            clase: "data_order",
            funcion: "column_order",
            ordenArray: orden,
          },
          success: function (resp) {},
          error: function (error) {
            console.log(error);
          },
        });
      },
    },
    onMove: function (evt) {
      var data = evt.dragged.dataset.pos;
      console.log(data);
      if (data != 1) {
        return true;
      } else {
        return false;
      }
    },
  });
}

function dataSortMain() {
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "orden_columnas" },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta desde sort: ", respuesta);

      $.each(respuesta, function (i) {
        $("#sort-" + respuesta[i].PKColumnasClientes)
          .off("click")
          .on("click", function () {
            var idDom = $("#sort-" + respuesta[i].PKColumnasClientes);
            _filterQuery.order.column = respuesta[i].PKColumnasClientes;

            selectColumnSort(idDom);
            formatColumnSort(respuesta);

            var texto = "";
            for (var j = 0; j < respuesta.length; j++) {
              texto +=
                j +
                1 +
                " Columna: " +
                respuesta[j].Nombre +
                " .- " +
                $("#sort-" + respuesta[j].PKColumnasClientes).data("sort") +
                "\n";
            }
            console.log("data despues de actualizar: \n" + texto);
          });
      });
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function selectColumnSort(idDom) {
  if (idDom.data("sort") === 0) {
    idDom.data("sort", 1);
    _filterQuery.order.sort = orderBy.ASC;
    console.log(
      "Data desde sort-" +
        _filterQuery.order.column +
        ": " +
        _filterQuery.order.sort
    );
  } else {
    idDom.data("sort", 0);
    _filterQuery.order.sort = orderBy.DESC;
    console.log(
      "Data desde sort-" +
        _filterQuery.order.column +
        ": " +
        _filterQuery.order.sort
    );
  }

  console.log("columna para ordenar: " + _filterQuery.order.column);
  get_info();
}

function formatColumnSort(array) {
  console.log(
    "valor de indice en formatear sort: " + _filterQuery.order.column
  );

  $.each(array, function (i) {
    if (array[i].PKColumnasClientes !== _filterQuery.order.column) {
      $("#sort-" + array[i].PKColumnasClientes).data("sort", 0);
    }
  });
}

/*===============================
=            EVENTOS            =
===============================*/

/* function verModal() {
  varContainer = ".listaColumnas";
  $(".listaColumnas").show();
} */

$(document).on("mouseup", function (e) {
  if (!$(e.target).closest(varContainer).length) {
    $(varContainer).hide();
  }

  $("#boardContent").css("overflow", "auto");
});

//Buscador
//evento cuando se presiona una tecla
var controladorTiempo = "";

function escribiendo() {
  clearTimeout(controladorTiempo);
  //Llamar la busqueda cuando el usuario deje de escribir
  controladorTiempo = setTimeout(function () {
    _filterQuery.search = $("#search-input").val();
    get_info();
  }, 500);
}

/*=====  End of EVENTOS  ======*/

function exportarPDF() {
  var sort = _filterQuery.order.sort;
  var search = _filterQuery.search;
  var indice = _filterQuery.order.column;

  window.location.href =
    "export_clientes?sort=" + sort + "&search=" + search + "&indice=" + indice;
}

function validate_Permissions(pkPantalla) {
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

      //PRODUCTOS
      if (pkPantalla == "10") {
        var html = "",
          html2 = "";
        if (_permissions.add == "1") {
          html = `<a href="agregar_cliente.php" class="btn-table-custom btn-table-custom--blue" title="Agregar cliente"><i class="fas fa-plus-square"></i> Agregar cliente</a>`;
          $("#btnAddPermissions").html(html);
        } else {
          html = ``;
          $("#btnAddPermissions").html(html);
        }

        if (_permissions.export == "1") {
          html2 = `<button class="btn-table-custom btn-table-custom--turquoise" id="exportClientes" onclick="exportarPDF()" title="Excel"><i class="fas fa-cloud-download-alt"></i> Descargar excel</button>`;
          $("#btnExportPermissions").html(html2);
        } else {
          html2 = ``;
          $("#btnExportPermissions").html(html2);
        }
      }
    },
  });
}
