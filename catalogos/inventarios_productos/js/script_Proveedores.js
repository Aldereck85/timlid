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
  validate_Permissions(12);

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
            'onclick="deseleccionar(' +
            respuesta[i].PKColumnasProveedores +
            ')"';

          //Asignar los valores al arreglo
          selectedColumns.push([
            respuesta[i].PKColumnasProveedores,
            respuesta[i].FKTipoColumnaProveedores,
          ]);

          //Clase y onclick para valores que se mostrarán en la modal
          classCheckColumn = "checked";
          onclickColumn =
            'onclick="deseleccionarModal(' +
            respuesta[i].PKColumnasProveedores +
            ')"';
        } else {
          //Clase y onclick en los primeros 5 valores
          classCheck = "check-type-column";
          onclick =
            'onclick="seleccionar(' + respuesta[i].PKColumnasProveedores + ')"';

          //Clase y onclick para valores que se mostrarán en la modal
          classCheckColumn = "";
          onclickColumn =
            'onclick="seleccionarModal(' +
            respuesta[i].PKColumnasProveedores +
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
        if (respuesta[i].FKTipoColumnaProveedores != 1) {
          if (respuesta[i].FKTipoColumnaProveedores == 3) {
          } else {
            $("#sortableColumns").append(
              '<div id="col_' +
                respuesta[i].PKColumnasProveedores +
                '" data-pos=' +
                respuesta[i].PKColumnasProveedores +
                ">" +
                '<div class="columna-proveedor handle column-header">' +
                '<div class="column-title">' +
                respuesta[i].Nombre +
                '</div><div><a class="column-order" id="sort-' +
                respuesta[i].PKColumnasProveedores +
                '" data-sort="0" href="#"><i class="fas fa-sort"></i></a></div>' +
                "</div>" +
                '<div id="column-' +
                respuesta[i].PKColumnasProveedores +
                '" class="columna-info"></div>' +
                "</div>"
            );
          }
        } else {
          $("#noSortableColumns").append(
            '<div id="col_' +
              respuesta[i].PKColumnasProveedores +
              '" data-pos=' +
              respuesta[i].PKColumnasProveedores +
              ">" +
              '<div class="columna-proveedor column-header">' +
              '<div class="column-title">' +
              respuesta[i].Nombre +
              '</div><div><a class="column-order" id="sort-' +
              respuesta[i].PKColumnasProveedores +
              '" data-sort="0" href="#"><i class="fas fa-sort"></i></a></div>' +
              "</div>" +
              '<div id="column-' +
              respuesta[i].PKColumnasProveedores +
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
          "#idProveedor-" + respuesta[i].PKProveedor,
          function () {
            $(".idProveedor-" + respuesta[i].PKProveedor).css(
              "display",
              "inline-block"
            );
            $(".idProveedor-" + respuesta[i].PKProveedor).css(
              "text-align",
              "center"
            );
            //$(".idProveedor-" + respuesta[i].PKProveedor).css("margin-left","40%");
            $("#edit-icon-" + respuesta[i].PKProveedor).css(
              "display",
              "inline-block"
            );
            $("#edit-icon-" + respuesta[i].PKProveedor).css("float", "right");
            if (isActive) {
              if (_permissions.delete == '1') {
                $("#delete-icon-" + respuesta[i].PKProveedor).css(
                  "display",
                  "inline-block"
                );
                $("#delete-icon-" + respuesta[i].PKProveedor).css("float", "right");
              }
            }
          }
        );
     
        $(document).on(
          "mouseleave",
          "#idProveedor-" + respuesta[i].PKProveedor,
          function () {
            $(".idProveedor-" + respuesta[i].PKProveedor).css(
              "display",
              "inline-block"
            );
            $(".idProveedor-" + respuesta[i].PKProveedor).css(
              "text-align",
              "center"
            );
            $(".idProveedor-" + respuesta[i].PKProveedor).css(
              "margin-left",
              "0px"
            );
            $("#edit-icon-" + respuesta[i].PKProveedor).css("display", "none");
            if (isActive) {
              if (_permissions.delete == '1') {
                $("#delete-icon-" + respuesta[i].PKProveedor).css("display","none");
              }
            }
          }
        );*/

        $(document).on(
          "mouseenter",
          ".row-" + respuesta[i].PKProveedor,
          function () {
            /*$('#idProveedor-'+respuesta[i].PKProveedor).css('background-color','rgba(192,247,231,3.0)');*/
            $("#NombreComercial-" + respuesta[i].PKProveedor).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $(".NombreComercialtexto-" + respuesta[i].PKProveedor).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            /*$('#MedioContacto-'+respuesta[i].PKProveedor).css('background-color','rgba(192,247,231,3.0)');
					$('.MedioContactotexto-'+respuesta[i].PKProveedor).css('background-color','rgba(192,247,231,3.0)');*/
            $("#Movil-" + respuesta[i].PKProveedor).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $(".Moviltexto-" + respuesta[i].PKProveedor).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $("#Telefono-" + respuesta[i].PKProveedor).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $(".Telefonotexto-" + respuesta[i].PKProveedor).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $("#Email-" + respuesta[i].PKProveedor).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $(".Emailtexto-" + respuesta[i].PKProveedor).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $("#SegundoEmail-" + respuesta[i].PKProveedor).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $(".SegundoEmailtexto-" + respuesta[i].PKProveedor).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $("#Giro-" + respuesta[i].PKProveedor).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $(".Girotexto-" + respuesta[i].PKProveedor).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $("#RazonSocial-" + respuesta[i].PKProveedor).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $(".RazonSocialtexto-" + respuesta[i].PKProveedor).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $("#DiasCredito-" + respuesta[i].PKProveedor).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $(".DiasCreditotexto-" + respuesta[i].PKProveedor).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $("#FKEstatusGeneral-" + respuesta[i].PKProveedor).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $(".FKEstatusGeneraltexto-" + respuesta[i].PKProveedor).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $("#FKVendedor-" + respuesta[i].PKProveedor).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $(".FKVendedortexto-" + respuesta[i].PKProveedor).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $("#RFC-" + respuesta[i].PKProveedor).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $(".RFCtexto-" + respuesta[i].PKProveedor).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $("#Calle-" + respuesta[i].PKProveedor).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $(".Calletexto-" + respuesta[i].PKProveedor).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $("#NumeroExterior-" + respuesta[i].PKProveedor).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $(".NumeroExteriortexto-" + respuesta[i].PKProveedor).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $("#NumeroInterior-" + respuesta[i].PKProveedor).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $(".NumeroInteriortexto-" + respuesta[i].PKProveedor).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $("#Municipio-" + respuesta[i].PKProveedor).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $(".Municipiotexto-" + respuesta[i].PKProveedor).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $("#Colonia-" + respuesta[i].PKProveedor).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $(".Coloniatexto-" + respuesta[i].PKProveedor).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $("#CodigoPostal-" + respuesta[i].PKProveedor).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $(".CodigoPostaltexto-" + respuesta[i].PKProveedor).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $("#Pais-" + respuesta[i].PKProveedor).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $(".Paistexto-" + respuesta[i].PKProveedor).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $("#Estado-" + respuesta[i].PKProveedor).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $(".Estadotexto-" + respuesta[i].PKProveedor).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $("#Localidad-" + respuesta[i].PKProveedor).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $(".Localidadtexto-" + respuesta[i].PKProveedor).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $("#Referencia-" + respuesta[i].PKProveedor).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $(".Referenciatexto-" + respuesta[i].PKProveedor).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
          }
        );

        $(document).on(
          "mouseleave",
          ".row-" + respuesta[i].PKProveedor,
          function () {
            /*$(this).css('background-color','white');
					$('#idProveedor-'+respuesta[i].PKProveedor).css('background-color','white');*/
            $("#NombreComercial-" + respuesta[i].PKProveedor).css(
              "background-color",
              "white"
            );
            $(".NombreComercialtexto-" + respuesta[i].PKProveedor).css(
              "background-color",
              "white"
            );
            /*$('#MedioContacto-'+respuesta[i].PKProveedor).css('background-color','white');
					$('.MedioContactotexto-'+respuesta[i].PKProveedor).css('background-color','white');*/
            $("#Movil-" + respuesta[i].PKProveedor).css(
              "background-color",
              "white"
            );
            $(".Moviltexto-" + respuesta[i].PKProveedor).css(
              "background-color",
              "white"
            );
            $("#Telefono-" + respuesta[i].PKProveedor).css(
              "background-color",
              "white"
            );
            $(".Telefonotexto-" + respuesta[i].PKProveedor).css(
              "background-color",
              "white"
            );
            $("#Email-" + respuesta[i].PKProveedor).css(
              "background-color",
              "white"
            );
            $(".Emailtexto-" + respuesta[i].PKProveedor).css(
              "background-color",
              "white"
            );
            $("#SegundoEmail-" + respuesta[i].PKProveedor).css(
              "background-color",
              "white"
            );
            $(".SegundoEmailtexto-" + respuesta[i].PKProveedor).css(
              "background-color",
              "white"
            );
            $("#Giro-" + respuesta[i].PKProveedor).css(
              "background-color",
              "white"
            );
            $(".Girotexto-" + respuesta[i].PKProveedor).css(
              "background-color",
              "white"
            );
            $("#RazonSocial-" + respuesta[i].PKProveedor).css(
              "background-color",
              "white"
            );
            $(".RazonSocialtexto-" + respuesta[i].PKProveedor).css(
              "background-color",
              "white"
            );
            $("#DiasCredito-" + respuesta[i].PKProveedor).css(
              "background-color",
              "white"
            );
            $(".DiasCreditotexto-" + respuesta[i].PKProveedor).css(
              "background-color",
              "white"
            );
            $("#FKEstatusGeneral-" + respuesta[i].PKProveedor).css(
              "background-color",
              "white"
            );
            $(".FKEstatusGeneraltexto-" + respuesta[i].PKProveedor).css(
              "background-color",
              "white"
            );
            $("#FKVendedor-" + respuesta[i].PKProveedor).css(
              "background-color",
              "white"
            );
            $(".FKVendedortexto-" + respuesta[i].PKProveedor).css(
              "background-color",
              "white"
            );
            $("#RFC-" + respuesta[i].PKProveedor).css(
              "background-color",
              "white"
            );
            $(".RFCtexto-" + respuesta[i].PKProveedor).css(
              "background-color",
              "white"
            );
            $("#Calle-" + respuesta[i].PKProveedor).css(
              "background-color",
              "white"
            );
            $(".Calletexto-" + respuesta[i].PKProveedor).css(
              "background-color",
              "white"
            );
            $("#NumeroExterior-" + respuesta[i].PKProveedor).css(
              "background-color",
              "white"
            );
            $(".NumeroExteriortexto-" + respuesta[i].PKProveedor).css(
              "background-color",
              "white"
            );
            $("#NumeroInterior-" + respuesta[i].PKProveedor).css(
              "background-color",
              "white"
            );
            $(".NumeroInteriortexto-" + respuesta[i].PKProveedor).css(
              "background-color",
              "white"
            );
            $("#Municipio-" + respuesta[i].PKProveedor).css(
              "background-color",
              "white"
            );
            $(".Municipiotexto-" + respuesta[i].PKProveedor).css(
              "background-color",
              "white"
            );
            $("#Colonia-" + respuesta[i].PKProveedor).css(
              "background-color",
              "white"
            );
            $(".Coloniatexto-" + respuesta[i].PKProveedor).css(
              "background-color",
              "white"
            );
            $("#CodigoPostal-" + respuesta[i].PKProveedor).css(
              "background-color",
              "white"
            );
            $(".CodigoPostaltexto-" + respuesta[i].PKProveedor).css(
              "background-color",
              "white"
            );
            $("#Pais-" + respuesta[i].PKProveedor).css(
              "background-color",
              "white"
            );
            $(".Paistexto-" + respuesta[i].PKProveedor).css(
              "background-color",
              "white"
            );
            $("#Estado-" + respuesta[i].PKProveedor).css(
              "background-color",
              "white"
            );
            $(".Estadotexto-" + respuesta[i].PKProveedor).css(
              "background-color",
              "white"
            );
            $("#Localidad-" + respuesta[i].PKProveedor).css(
              "background-color",
              "white"
            );
            $(".Localidadtexto-" + respuesta[i].PKProveedor).css(
              "background-color",
              "white"
            );
            $("#Referencia-" + respuesta[i].PKProveedor).css(
              "background-color",
              "white"
            );
            $(".Referenciatexto-" + respuesta[i].PKProveedor).css(
              "background-color",
              "white"
            );
          }
        );

        $(document).on(
          "click",
          "#edit-tabs-" + respuesta[i].PKProveedor,
          function () {
            var data = $(this).data("id");

            window.location.href = "editar_proveedor.php?p=" + data;

            //
            //alert('Pendiente de desarrollo y diseño\n'+$(this).data("id"));
            //console.log($(this).data("id"));
          }
        );

        $(document).on(
          "click",
          "#edit-tabs2-" + respuesta[i].PKProveedor,
          function () {
            var data = $(this).data("id");

            obtenerIdProveedorEliminar(data);
            $("#eliminar_Proveedor").modal("show");

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
function deseleccionar(pkColumnaProveedor) {
  console.log("Deseleccionar en primera vista");

  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "edit_data",
      funcion: "update_check_column",
      data: pkColumnaProveedor,
      flag: 0,
    },
    dataType: "json",
    success: function (respuesta) {
      console.log("Respuesta :" + respuesta[0].status);
      if (respuesta[0].status) {
        //Asignar clases para activar y desactivar checks en las modales de selección
        $("#checkType-" + pkColumnaProveedor).removeClass(
          "checked-type-column"
        );
        $("#checkType-" + pkColumnaProveedor).addClass("check-type-column");

        $("#checkType-" + pkColumnaProveedor).removeAttr("onclick");
        $("#checkType-" + pkColumnaProveedor).attr(
          "onclick",
          "seleccionar(" + pkColumnaProveedor + ")"
        );

        //Quitar columna de la tabla
        $("#col_" + pkColumnaProveedor).remove();
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function deseleccionarModal(pkColumnaProveedor) {
  console.log("Deseleccionar en modal");

  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "edit_data",
      funcion: "update_check_column",
      data: pkColumnaProveedor,
      flag: 0,
    },
    dataType: "json",
    success: function (respuesta) {
      console.log(respuesta[0].status);
      if (respuesta[0].status) {
        //Asignar clases para activar y desactivar checks en las modales de selección
        $("#checkType-" + pkColumnaProveedor).removeClass("checked");
        //$("#checkType-" + pkColumnaProveedor).addClass("checked");

        $("#checkType-" + pkColumnaProveedor).removeAttr("onclickColumn");
        $("#checkType-" + pkColumnaProveedor).attr(
          "onclick",
          "seleccionarModal(" + pkColumnaProveedor + ")"
        );

        //Quitar columna de la tabla
        $("#col_" + pkColumnaProveedor).remove();
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function seleccionar(pkColumnaProveedor) {
  console.log("Seleccionar en primera vista");
  mostrar();
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "edit_data",
      funcion: "update_check_column",
      data: pkColumnaProveedor,
      flag: 1,
    },
    dataType: "json",
    success: function (respuesta) {
      if (respuesta[0].status) {
        //Asignar clases para activar y desactivar checks en las modales de selección
        $("#checkType-" + pkColumnaProveedor).removeClass("check-type-column");
        $("#checkType-" + pkColumnaProveedor).addClass("checked-type-column");

        $("#checkType-" + pkColumnaProveedor).removeAttr("onclick");
        $("#checkType-" + pkColumnaProveedor).attr(
          "onclick",
          "deseleccionar(" + pkColumnaProveedor + ")"
        );

        //Mostrar columna
        $("#sortableColumns").append(
          '<div id="col_' +
            pkColumnaProveedor +
            '" data-pos=' +
            pkColumnaProveedor +
            ">" +
            '<div class="columna-proveedor handle column-header">' +
            '<div class="column-title">' +
            respuesta[0].array[0].columnaAfectada +
            '</div><div><a class="column-order" id="sort-' +
            pkColumnaProveedor +
            '" data-sort="0" href="#"><i class="fas fa-sort"></i></a></div>' +
            "</div>" +
            '<div id="column-' +
            pkColumnaProveedor +
            '" class="columna-info"></div>' +
            "</div>"
        );
        selectedColumns.push([
          pkColumnaProveedor,
          respuesta[0].array[0].tipoColumna,
        ]);

        console.log("SE SELECCIONO COLUMNA", selectedColumns);

        get_info();

        let coord = $("#col_" + pkColumnaProveedor).offset();

        document.getElementById("boardContent").scrollLeft += coord.left;
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

function seleccionarModal(pkColumnaProveedor) {
  console.log("Seleccionar en modal");
  mostrar();
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "edit_data",
      funcion: "update_check_column",
      data: pkColumnaProveedor,
      flag: 1,
    },
    dataType: "json",
    success: function (respuesta) {
      if (respuesta[0].status) {
        //$("#checkType-" + pkColumnaProveedor).removeClass("check-type-column-modal");
        $("#checkType-" + pkColumnaProveedor).addClass("checked");

        $("#checkType-" + pkColumnaProveedor).removeAttr("onclickColumn");
        $("#checkType-" + pkColumnaProveedor).attr(
          "onclick",
          "deseleccionarModal(" + pkColumnaProveedor + ")"
        );

        //Mostrar columna
        $("#sortableColumns").append(
          '<div id="col_' +
            pkColumnaProveedor +
            '" data-pos=' +
            pkColumnaProveedor +
            ">" +
            '<div class="columna-proveedor handle column-header">' +
            '<div class="column-title">' +
            respuesta[0].array[0].columnaAfectada +
            '</div><div><a class="column-order" id="sort-' +
            pkColumnaProveedor +
            '" data-sort="0" href="#"><i class="fas fa-sort"></i></a></div>' +
            "</div>" +
            '<div id="column-' +
            pkColumnaProveedor +
            '" class="columna-info"></div>' +
            "</div>"
        );
        selectedColumns.push([
          pkColumnaProveedor,
          respuesta[0].array[0].tipoColumna,
        ]);

        console.log("SE SELECCIONO COLUMNA", selectedColumns);

        get_info();

        let coord = $("#col_" + pkColumnaProveedor).offset();

        document.getElementById("boardContent").scrollLeft += coord.left;
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
      console.log("si");

      $.each(selectedColumns, function (i) {
        console.log("longitud respuesta get info:", respuesta.length);

        if (respuesta.length > 0) {
          if (selectedColumns[i][1] == 1) {
            //ID del producto
            $("#column-" + selectedColumns[i][0]).html(html.idProveedor);
          }
          if (selectedColumns[i][1] == 2) {
            //Nombre del producto
            $("#column-" + selectedColumns[i][0]).html(html.nombreComercial);
          }
          if (selectedColumns[i][1] == 4) {
            //Clave interna del producto
            $("#column-" + selectedColumns[i][0]).html(html.movil);
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
            $("#column-" + selectedColumns[i][0]).html(html.emailSegundo);
          }
          if (selectedColumns[i][1] == 8) {
            //Descripcion del producto
            $("#column-" + selectedColumns[i][0]).html(html.giro);
          }
          if (selectedColumns[i][1] == 9) {
            //Tipo de del producto
            $("#column-" + selectedColumns[i][0]).html(html.razonSocial);
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
            $("#column-" + selectedColumns[i][0]).html(html.rfc);
          }
          if (selectedColumns[i][1] == 14) {
            //Estatus del producto
            $("#column-" + selectedColumns[i][0]).html(html.calle);
          }
          if (selectedColumns[i][1] == 15) {
            //Estatus del producto
            $("#column-" + selectedColumns[i][0]).html(html.numeroExterior);
          }
          if (selectedColumns[i][1] == 16) {
            //Estatus del producto
            $("#column-" + selectedColumns[i][0]).html(html.numeroInterior);
          }
          if (selectedColumns[i][1] == 17) {
            //Estatus del producto
            $("#column-" + selectedColumns[i][0]).html(html.municipio);
          }
          if (selectedColumns[i][1] == 18) {
            //Estatus del producto
            $("#column-" + selectedColumns[i][0]).html(html.colonia);
          }
          if (selectedColumns[i][1] == 19) {
            //Estatus del producto
            $("#column-" + selectedColumns[i][0]).html(html.codigoPostal);
          }
          if (selectedColumns[i][1] == 20) {
            //Estatus del producto
            $("#column-" + selectedColumns[i][0]).html(html.pais);
          }
          if (selectedColumns[i][1] == 21) {
            //Estatus del producto
            $("#column-" + selectedColumns[i][0]).html(html.estado);
          }
          if (selectedColumns[i][1] == 22) {
            //Estatus del producto
            $("#column-" + selectedColumns[i][0]).html(html.localidad);
          }
          if (selectedColumns[i][1] == 23) {
            //Estatus del producto
            $("#column-" + selectedColumns[i][0]).html(html.referencia);
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
    idProveedor: "",
    nombreComercial: "",
    movil: "",
    telefono: "",
    email: "",
    emailSegundo: "",
    giro: "",
    razonSocial: "",
    diasCredito: "",
    estatusGeneral: "",
    vendedor: "",
    rfc: "",
    calle: "",
    numeroExterior: "",
    numeroInterior: "",
    municipio: "",
    colonia: "",
    codigoPostal: "",
    pais: "",
    estado: "",
    localidad: "",
    referencia: "",
  };

  for (var j = 0; j < data.length; j++) {
    html.idProveedor += templateIdProveedor(data[j]);
    html.nombreComercial += templateNombreComercial(data[j]);
    html.movil += templateMovil(data[j]);
    html.telefono += templateTelefono(data[j]);
    html.email += templateEmail(data[j]);
    html.emailSegundo += templateEmailSegundo(data[j]);
    html.giro += templateGiro(data[j]);
    html.razonSocial += templateRazonSocial(data[j]);
    html.diasCredito += templateDiasCredito(data[j]);
    html.estatusGeneral += templateEstatusGeneral(data[j]);
    html.vendedor += templateVendedor(data[j]);
    html.rfc += templateRFC(data[j]);
    html.calle += templateCalle(data[j]);
    html.numeroExterior += templateNumeroExterior(data[j]);
    html.numeroInterior += templateNumeroInterior(data[j]);
    html.municipio += templateMunicipio(data[j]);
    html.colonia += templateColonia(data[j]);
    html.codigoPostal += templateCodigoPostal(data[j]);
    html.pais += templatePais(data[j]);
    html.estado += templateEstado(data[j]);
    html.localidad += templateLocalidad(data[j]);
    html.referencia += templateReferencia(data[j]);
  }

  //console.log(html);
  return html;
}

//PKProveedor para la paginación
function templateIdProveedor(data) {
  var btEliminar = "";
  var btEditar = "";

  if (_permissions.delete == "1") {
    btEliminar = `<i class="fas fa-trash-alt color-primary" id="delete-icon-${data.PKProveedor}"></i>`;
  } else {
    btEliminar = "";
  }

  if (_permissions.edit == "1") {
    btEditar = `<i class="fas fa-edit color-primary" id="edit-icon-${data.PKProveedor}"></i>`;
  } else {
    btEditar = "";
  }

  if (data.FKEstatusGeneral == "Inactivo") {
    return `<div class="hideEmployer show-icon-edit b-bottom row-${data.PKProveedor}" id="idProveedor-${data.PKProveedor}" style="height: 36px; color:black; border-left: 5px solid #cac8c6; color: #FFFFFF;" title="Proveedor inactivo">
                <span class="idProveedor-${data.PKProveedor}" style="margin-left: auto; margin-right: auto; display: block;">
                  <a id="edit-tabs-${data.PKProveedor}" data-id="${data.PKProveedor}" href="#">
                    ${btEditar}
                  </a>
                  <a id="edit-tabs2-${data.PKProveedor}" data-id="${data.PKProveedor}" href="#">
                    ${btEliminar}
                  </a>
                </span>
              </div>
            `;
  } else {
    return `<div class="hideEmployer show-icon-edit b-bottom row-${data.PKProveedor}" id="idProveedor-${data.PKProveedor}" style="height: 36px; color:black; border-left: 5px solid #28c67a;; color: #FFFFFF;" title="Proveedor activo">
                <div class="col-lg-12 input-group">
                  <span class="idProveedor-${data.PKProveedor}" style="margin-left: auto; margin-right: auto; display: block;">
                    <a id="edit-tabs-${data.PKProveedor}" data-id="${data.PKProveedor}" href="#">
                      ${btEditar}
                    </a>
                    <a id="edit-tabs2-${data.PKProveedor}" data-id="${data.PKProveedor}" href="#">
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
  if (data.NombreComercial == null) {
    return (
      '<div class="hideEmployer b-bottom row-' +
      data.PKProveedor +
      '" id="NombreComercial-' +
      data.PKProveedor +
      '"><input class="text-center border-n NombreComercialtexto-' +
      data.PKProveedor +
      '" type="text" ondblclick="editar_elemento(' +
      data.PKProveedor +
      ')" value="" readonly style="width: 230px;white-space: nowrap; text-overflow: ellipsis; overflow: hidden;" title="Sin información"></div>'
    );
  } else {
    return (
      '<div class="hideEmployer b-bottom row-' +
      data.PKProveedor +
      '" id="NombreComercial-' +
      data.PKProveedor +
      '"><input class="text-center border-n NombreComercialtexto-' +
      data.PKProveedor +
      '" type="text" ondblclick="editar_elemento(' +
      data.PKProveedor +
      ')" value="' +
      data.NombreComercial +
      '" readonly style="width: 230px;white-space: nowrap; text-overflow: ellipsis; overflow: hidden;" title="' +
      data.NombreComercial +
      '"></div>'
    );
  }
}

//Medio de Contacto para la paginación
/*function templateMedioContacto(data){
	var html = "";
	for (var j = 0; j < data.length; j++) {
		if (data[j].FKMedioContactoProveedor == null){
			html += '<div class="hideEmployer b-bottom row-'+data[j].PKProveedor+'" id="MedioContacto-'+data[j].PKProveedor+'"><input class="text-center border-n MedioContactotexto-'+data[j].PKProveedor+'" type="text" ondblclick="editar_elemento('+
			data[j].PKProveedor+')" value="" readonly style="width: 230px;white-space: nowrap; text-overflow: ellipsis; overflow: hidden;" title="Sin información"></div>';
		}else{
			html += '<div class="hideEmployer b-bottom row-'+data[j].PKProveedor+'" id="MedioContacto-'+data[j].PKProveedor+'"><input class="text-center border-n MedioContactotexto-'+data[j].PKProveedor+'" type="text" ondblclick="editar_elemento('+
			data[j].PKProveedor+')" value="'
			+data[j].FKMedioContactoProveedor+'" readonly style="width: 230px;white-space: nowrap; text-overflow: ellipsis; overflow: hidden;" title="'+data[j].FKMedioContactoProveedor+'"></div>';
		}	
	}
	return html;
}*/

//Fecha alta para la paginación
function templateMovil(data) {
  if (data.Movil == null) {
    return (
      '<div class="hideEmployer b-bottom row-' +
      data.PKProveedor +
      '" id="Movil-' +
      data.PKProveedor +
      '"><input class="text-center border-n Moviltexto-' +
      data.PKProveedor +
      '" type="text" ondblclick="editar_elemento(' +
      data.PKProveedor +
      ',5)" value="" readonly style="width: 230px;white-sce: nowrap; text-overflow: ellipsis; overflow: hidden;" title="Sin información"></div>'
    );
  } else {
    return (
      '<div class="hideEmployer b-bottom row-' +
      data.PKProveedor +
      '" id="Movil-' +
      data.PKProveedor +
      '"><input class="text-center border-n Moviltexto-' +
      data.PKProveedor +
      '" type="text" ondblclick="editar_elemento(' +
      data.PKProveedor +
      ',5)" value="' +
      data.Movil +
      '" readonly style="width: 230px;white-space: nowrap; text-overflow: ellipsis; overflow: hidden;" title="' +
      data.Movil +
      '"></div>'
    );
  }
}

//Telefono para la paginación
function templateTelefono(data) {
  if (data.Telefono == null || data.Telefono == "") {
    return (
      '<div class="hideEmployer b-bottom row-' +
      data.PKProveedor +
      '" id="Telefono-' +
      data.PKProveedor +
      '"><input class="text-center border-n Telefonotexto-' +
      data.PKProveedor +
      '" type="text" ondblclick="editar_elemento(' +
      data.PKProveedor +
      ',5)" value="" readonly style="width: 230px;white-ace: nowrap; text-overflow: ellipsis; overflow: hidden;" title="Sin información"></div>'
    );
  } else {
    return (
      '<div class="hideEmployer b-bottom row-' +
      data.PKProveedor +
      '" id="Telefono-' +
      data.PKProveedor +
      '"><input class="text-center border-n Telefonotexto-' +
      data.PKProveedor +
      '" type="text" ondblclick="editar_elemento(' +
      data.PKProveedor +
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
      data.PKProveedor +
      '" id="Email-' +
      data.PKProveedor +
      '"><input class="text-center border-n Emailtexto-' +
      data.PKProveedor +
      '" type="text" ondblclick="editar_elemento(' +
      data.PKProveedor +
      ',5)" value="" readonly style="widt 230px;white-space: nowrap; text-overflow: ellipsis; overflow: hidden;" title="Sin información"></div>'
    );
  } else {
    return (
      '<div class="hideEmployer b-bottom row-' +
      data.PKProveedor +
      '" id="Email-' +
      data.PKProveedor +
      '"><input class="text-center border-n Emailtexto-' +
      data.PKProveedor +
      '" type="text" ondblclick="editar_elemento(' +
      data.PKProveedor +
      ',5)" value="' +
      data.Email +
      '" readonly style="width: 230px;white-space: nowrap; text-overflow: ellipsis; overflow: hidden;" title="' +
      data.Email +
      '"></div>'
    );
  }
}

//Fecha del Ultimo Contacto para la paginación
function templateEmailSegundo(data) {
  if (data.SegundoEmail == null) {
    return (
      '<div class="hideEmployer b-bottom row-' +
      data.PKProveedor +
      '" id="SegundoEmail-' +
      data.PKProveedor +
      '"><input class="text-center border-n SegundoEmailtexto-' +
      data.PKProveedor +
      '" type="text" ondblclick="editar_elemento(' +
      data.PKProveedor +
      ')" value="" readonly style="width: 230px;white-space: nowrap; text-overflow: ellipsis; overflow: hidden;" title="Sin información"></div>'
    );
  } else if (data.SegundoEmail == "0000-00-00 00:00:00") {
    return (
      '<div class="hideEmployer b-bottom row-' +
      data.PKProveedor +
      '" id="SegundoEmail-' +
      data.PKProveedor +
      '"><input class="text-center border-n SegundoEmailtexto-' +
      data.PKProveedor +
      '" type="text" ondblclick="editar_elemento(' +
      data.PKProveedor +
      ')" value="No agendado" readonly style="width: 230px;white-space: nowrap; text-overflow: ellipsis; overflow: hidden;" title="' +
      data.Fecha_Ultimo_Contacto +
      '"></div>'
    );
  } else {
    return (
      '<div class="hideEmployer b-bottom row-' +
      data.PKProveedor +
      '" id="SegundoEmail-' +
      data.PKProveedor +
      '"><input class="text-center border-n SegundoEmailtexto-' +
      data.PKProveedor +
      '" type="text" ondblclick="editar_elemento(' +
      data.PKProveedor +
      ')" value="' +
      data.SegundoEmail +
      '" readonly style="width: 230px;white-space: nowrap; text-overflow: ellipsis; overflow: hidden;" title="' +
      data.SegundoEmail +
      '"></div>'
    );
  }
}

//Fecha del Siguiente Contacto para la paginación
function templateGiro(data) {
  if (data.Giro == null) {
    return (
      '<div class="hideEmployer b-bottom row-' +
      data.PKProveedor +
      '" id="Giro-' +
      data.PKProveedor +
      '"><input class="text-center border-n Girotexto-' +
      data.PKProveedor +
      '" type="text" ondblclick="editar_elemento(' +
      data.PKProveedor +
      ')" value="" readonly style="width: 230px;white-space: nowrap; text-overflow: ellipsis; overflow: hidden;" title="Sin información"></div>'
    );
  } else if (data.Giro == "0000-00-00 00:00:00") {
    return (
      '<div class="hideEmployer b-bottom row-' +
      data.PKProveedor +
      '" id="Giro-' +
      data.PKProveedor +
      '"><input class="text-center border-n Girotexto-' +
      data.PKProveedor +
      '" type="text" ondblclick="editar_elemento(' +
      data.PKProveedor +
      ')" value="No agendado" readonly style="width: 230px;white-space: nowrap; text-overflow: ellipsis; overflow: hidden;" title="' +
      data.Giro +
      '"></div>'
    );
  } else {
    return (
      '<div class="hideEmployer b-bottom row-' +
      data.PKProveedor +
      '" id="Giro-' +
      data.PKProveedor +
      '"><input class="text-center border-n Girotexto-' +
      data.PKProveedor +
      '" type="text" ondblclick="editar_elemento(' +
      data.PKProveedor +
      ')" value="' +
      data.Giro +
      '" readonly style="width: 230px;white-space: nowrap; text-overflow: ellipsis; overflow: hidden;" title="' +
      data.Giro +
      '"></div>'
    );
  }
}

//Monto de credito para la paginación
function templateRazonSocial(data) {
  if (data.Razon_Social == null) {
    return (
      '<div class="hideEmployer b-bottom row-' +
      data.PKProveedor +
      '" id="RazonSocial-' +
      data.PKProveedor +
      '"><input class="text-center border-n RazonSocialtexto-' +
      data.PKProveedor +
      '" type="text" ondblclick="editar_elemento(' +
      data.PKProveedor +
      ',5)" value="" readonly style="width: 230px;white-space: nowrap; text-overflow: ellipsis; overflow: hidden;" title="Sin información"></div>'
    );
  } else {
    return (
      '<div class="hideEmployer b-bottom row-' +
      data.PKProveedor +
      '" id="RazonSocial-' +
      data.PKProveedor +
      '"><input class="text-center border-n RazonSocialtexto-' +
      data.PKProveedor +
      '" type="text" ondblclick="editar_elemento(' +
      data.PKProveedor +
      ',5)" value="' +
      data.Razon_Social +
      '" readonly style="width: 230px;white-space: nowrap; text-overflow: ellipsis; overflow: hidden;" title="' +
      data.Razon_Social +
      '"></div>'
    );
  }
}

//Días de credito para la paginación
function templateDiasCredito(data) {
  if (data.Dias_credito == null) {
    return (
      '<div class="hideEmployer b-bottom row-' +
      data.PKProveedor +
      '" id="DiasCredito-' +
      data.PKProveedor +
      '"><input class="text-center border-n DiasCreditotexto-' +
      data.PKProveedor +
      '" type="text" ondblclick="editar_elemento(' +
      data.PKProveedor +
      ',5)" value="" readonly style="width: 230px;white-space: nowrap; text-overflow: ellipsis; overflow: hidden;" title="Sin información"></div>'
    );
  } else {
    return (
      '<div class="hideEmployer b-bottom row-' +
      data.PKProveedor +
      '" id="DiasCredito-' +
      data.PKProveedor +
      '"><input class="text-center border-n DiasCreditotexto-' +
      data.PKProveedor +
      '" type="text" ondblclick="editar_elemento(' +
      data.PKProveedor +
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
      data.PKProveedor +
      '" id="FKEstatusGeneral-' +
      data.PKProveedor +
      '"><input class="text-center border-n FKEstatusGeneraltexto-' +
      data.PKProveedor +
      '" type="text" ondblclick="editar_elemento(' +
      data.PKProveedor +
      ')" value="" readonly style="width: 230px;white-space: nowrap; text-overflow: ellipsis; overflow: hidden;" title="' +
      data.FKEstatusGeneral +
      '"></div>'
    );
  } else {
    return (
      '<div class="hideEmployer b-bottom row-' +
      data.PKProveedor +
      '" id="FKEstatusGeneral-' +
      data.PKProveedor +
      '"><input class="text-center border-n FKEstatusGeneraltexto-' +
      data.PKProveedor +
      '" type="text" ondblclick="editar_elemento(' +
      data.PKProveedor +
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
      data.PKProveedor +
      '" id="FKVendedor-' +
      data.PKProveedor +
      '"><input class="text-center border-n FKVendedortexto-' +
      data.PKProveedor +
      '" type="text" ondblclick="editar_elemento(' +
      data.PKProveedor +
      ',5)" value="" readonly style="width: 230px;white-space: nowrap; text-overflow: ellipsis; overflow: hidden;" title="Sin información"></div>'
    );
  } else {
    return (
      '<div class="hideEmployer b-bottom row-' +
      data.PKProveedor +
      '" id="FKVendedor-' +
      data.PKProveedor +
      '"><input class="text-center border-n FKVendedortexto-' +
      data.PKProveedor +
      '" type="text" ondblclick="editar_elemento(' +
      data.PKProveedor +
      ',5)" value="' +
      data.FKVendedor +
      '" readonly style="width: 230px;white-space: nowrap; text-overflow: ellipsis; overflow: hidden;" title="' +
      data.FKVendedor +
      '"></div>'
    );
  }
}

//Vendedor para la paginación
function templateRFC(data) {
  if (data.RFC == null) {
    return (
      '<div class="hideEmployer b-bottom row-' +
      data.PKProveedor +
      '" id="RFC-' +
      data.PKProveedor +
      '"><input class="text-center border-n RFCtexto-' +
      data.PKProveedor +
      '" type="text" ondblclick="editar_elemento(' +
      data.PKProveedor +
      ',5)" value="" readonly style="width: 230px;white-space: nowrap; text-overflow: ellipsis; overflow: hidden;" title="Sin información"></div>'
    );
  } else {
    return (
      '<div class="hideEmployer b-bottom row-' +
      data.PKProveedor +
      '" id="RFC-' +
      data.PKProveedor +
      '"><input class="text-center border-n RFCtexto-' +
      data.PKProveedor +
      '" type="text" ondblclick="editar_elemento(' +
      data.PKProveedor +
      ',5)" value="' +
      data.RFC +
      '" readonly style="width: 230px;white-space: nowrap; text-overflow: ellipsis; overflow: hidden;" title="' +
      data.RFC +
      '"></div>'
    );
  }
}

//Vendedor para la paginación
function templateCalle(data) {
  if (data.Calle == null) {
    return (
      '<div class="hideEmployer b-bottom row-' +
      data.PKProveedor +
      '" id="Calle-' +
      data.PKProveedor +
      '"><input class="text-center border-n Calletexto-' +
      data.PKProveedor +
      '" type="text" ondblclick="editar_elemento(' +
      data.PKProveedor +
      ',5)" value="" readonly style="width: 230px;white-space: nowrap; text-overflow: ellipsis; overflow: hidden;" title="Sin información"></div>'
    );
  } else {
    return (
      '<div class="hideEmployer b-bottom row-' +
      data.PKProveedor +
      '" id="Calle-' +
      data.PKProveedor +
      '"><input class="text-center border-n Calletexto-' +
      data.PKProveedor +
      '" type="text" ondblclick="editar_elemento(' +
      data.PKProveedor +
      ',5)" value="' +
      data.Calle +
      '" readonly style="width: 230px;white-space: nowrap; text-overflow: ellipsis; overflow: hidden;" title="' +
      data.Calle +
      '"></div>'
    );
  }
}

//Vendedor para la paginación
function templateNumeroExterior(data) {
  if (data.Numero_exterior == null) {
    return (
      '<div class="hideEmployer b-bottom row-' +
      data.PKProveedor +
      '" id="NumeroExterior-' +
      data.PKProveedor +
      '"><input class="text-center border-n NumeroExteriortexto-' +
      data.PKProveedor +
      '" type="text" ondblclick="editar_elemento(' +
      data.PKProveedor +
      ',5)" value="" readonly style="width: 230px;white-space: nowrap; text-overflow: ellipsis; overflow: hidden;" title="Sin información"></div>'
    );
  } else {
    return (
      '<div class="hideEmployer b-bottom row-' +
      data.PKProveedor +
      '" id="NumeroExterior-' +
      data.PKProveedor +
      '"><input class="text-center border-n NumeroExteriortexto-' +
      data.PKProveedor +
      '" type="text" ondblclick="editar_elemento(' +
      data.PKProveedor +
      ',5)" value="' +
      data.Numero_exterior +
      '" readonly style="width: 230px;white-space: nowrap; text-overflow: ellipsis; overflow: hidden;" title="' +
      data.Numero_exterior +
      '"></div>'
    );
  }
}

//Vendedor para la paginación
function templateNumeroInterior(data) {
  if (data.Numero_Interior == null) {
    return (
      '<div class="hideEmployer b-bottom row-' +
      data.PKProveedor +
      '" id="NumeroInterior-' +
      data.PKProveedor +
      '"><input class="text-center border-n NumeroInteriortexto-' +
      data.PKProveedor +
      '" type="text" ondblclick="editar_elemento(' +
      data.PKProveedor +
      ',5)" value="" readonly style="width: 230px;white-space: nowrap; text-overflow: ellipsis; overflow: hidden;" title="Sin información"></div>'
    );
  } else {
    return (
      '<div class="hideEmployer b-bottom row-' +
      data.PKProveedor +
      '" id="NumeroInterior-' +
      data.PKProveedor +
      '"><input class="text-center border-n NumeroInteriortexto-' +
      data.PKProveedor +
      '" type="text" ondblclick="editar_elemento(' +
      data.PKProveedor +
      ',5)" value="' +
      data.Numero_Interior +
      '" readonly style="width: 230px;white-space: nowrap; text-overflow: ellipsis; overflow: hidden;" title="' +
      data.Numero_Interior +
      '"></div>'
    );
  }
}

//Vendedor para la paginación
function templateMunicipio(data) {
  if (data.Municipio == null) {
    return (
      '<div class="hideEmployer b-bottom row-' +
      data.PKProveedor +
      '" id="Municipio-' +
      data.PKProveedor +
      '"><input class="text-center border-n Municipiotexto-' +
      data.PKProveedor +
      '" type="text" ondblclick="editar_elemento(' +
      data.PKProveedor +
      ',5)" value="" readonly style="width: 230px;white-space: nowrap; text-overflow: ellipsis; overflow: hidden;" title="Sin información"></div>'
    );
  } else {
    return (
      '<div class="hideEmployer b-bottom row-' +
      data.PKProveedor +
      '" id="Municipio-' +
      data.PKProveedor +
      '"><input class="text-center border-n Municipiotexto-' +
      data.PKProveedor +
      '" type="text" ondblclick="editar_elemento(' +
      data.PKProveedor +
      ',5)" value="' +
      data.Municipio +
      '" readonly style="width: 230px;white-space: nowrap; text-overflow: ellipsis; overflow: hidden;" title="' +
      data.Municipio +
      '"></div>'
    );
  }
}

//Vendedor para la paginación
function templateColonia(data) {
  if (data.Colonia == null) {
    return (
      '<div class="hideEmployer b-bottom row-' +
      data.PKProveedor +
      '" id="Colonia-' +
      data.PKProveedor +
      '"><input class="text-center border-n Coloniatexto-' +
      data.PKProveedor +
      '" type="text" ondblclick="editar_elemento(' +
      data.PKProveedor +
      ',5)" value="" readonly style="width: 230px;white-space: nowrap; text-overflow: ellipsis; overflow: hidden;" title="Sin información"></div>'
    );
  } else {
    return (
      '<div class="hideEmployer b-bottom row-' +
      data.PKProveedor +
      '" id="Colonia-' +
      data.PKProveedor +
      '"><input class="text-center border-n Coloniatexto-' +
      data.PKProveedor +
      '" type="text" ondblclick="editar_elemento(' +
      data.PKProveedor +
      ',5)" value="' +
      data.Colonia +
      '" readonly style="width: 230px;white-space: nowrap; text-overflow: ellipsis; overflow: hidden;" title="' +
      data.Colonia +
      '"></div>'
    );
  }
}

//Vendedor para la paginación
function templateCodigoPostal(data) {
  if (data.CP == null) {
    return (
      '<div class="hideEmployer b-bottom row-' +
      data.PKProveedor +
      '" id="CodigoPostal-' +
      data.PKProveedor +
      '"><input class="text-center border-n CodigoPostaltexto-' +
      data.PKProveedor +
      '" type="text" ondblclick="editar_elemento(' +
      data.PKProveedor +
      ',5)" value="" readonly style="width: 230px;white-space: nowrap; text-overflow: ellipsis; overflow: hidden;" title="Sin información"></div>'
    );
  } else {
    return (
      '<div class="hideEmployer b-bottom row-' +
      data.PKProveedor +
      '" id="CodigoPostal-' +
      data.PKProveedor +
      '"><input class="text-center border-n CodigoPostaltexto-' +
      data.PKProveedor +
      '" type="text" ondblclick="editar_elemento(' +
      data.PKProveedor +
      ',5)" value="' +
      data.CP +
      '" readonly style="width: 230px;white-space: nowrap; text-overflow: ellipsis; overflow: hidden;" title="' +
      data.CP +
      '"></div>'
    );
  }
}

//Vendedor para la paginación
function templatePais(data) {
  if (data.Pais == null) {
    return (
      '<div class="hideEmployer b-bottom row-' +
      data.PKProveedor +
      '" id="Pais-' +
      data.PKProveedor +
      '"><input class="text-center border-n Paistexto-' +
      data.PKProveedor +
      '" type="text" ondblclick="editar_elemento(' +
      data.PKProveedor +
      ',5)" value="" readonly style="width: 230px;white-space: nowrap; text-overflow: ellipsis; overflow: hidden;" title="Sin información"></div>'
    );
  } else {
    return (
      '<div class="hideEmployer b-bottom row-' +
      data.PKProveedor +
      '" id="Pais-' +
      data.PKProveedor +
      '"><input class="text-center border-n Paistexto-' +
      data.PKProveedor +
      '" type="text" ondblclick="editar_elemento(' +
      data.PKProveedor +
      ',5)" value="' +
      data.Pais +
      '" readonly style="width: 230px;white-space: nowrap; text-overflow: ellipsis; overflow: hidden;" title="' +
      data.Pais +
      '"></div>'
    );
  }
}

//Vendedor para la paginación
function templateEstado(data) {
  if (data.Estado == null) {
    return (
      '<div class="hideEmployer b-bottom row-' +
      data.PKProveedor +
      '" id="Estado-' +
      data.PKProveedor +
      '"><input class="text-center border-n Estadotexto-' +
      data.PKProveedor +
      '" type="text" ondblclick="editar_elemento(' +
      data.PKProveedor +
      ',5)" value="" readonly style="width: 230px;white-space: nowrap; text-overflow: ellipsis; overflow: hidden;" title="Sin información"></div>'
    );
  } else {
    return (
      '<div class="hideEmployer b-bottom row-' +
      data.PKProveedor +
      '" id="Estado-' +
      data.PKProveedor +
      '"><input class="text-center border-n Estadotexto-' +
      data.PKProveedor +
      '" type="text" ondblclick="editar_elemento(' +
      data.PKProveedor +
      ',5)" value="' +
      data.Estado +
      '" readonly style="width: 230px;white-space: nowrap; text-overflow: ellipsis; overflow: hidden;" title="' +
      data.Estado +
      '"></div>'
    );
  }
}

//Vendedor para la paginación
function templateLocalidad(data) {
  if (data.Localidad == null) {
    return (
      '<div class="hideEmployer b-bottom row-' +
      data.PKProveedor +
      '" id="Localidad-' +
      data.PKProveedor +
      '"><input class="text-center border-n Localidadtexto-' +
      data.PKProveedor +
      '" type="text" ondblclick="editar_elemento(' +
      data.PKProveedor +
      ',5)" value="" readonly style="width: 230px;white-space: nowrap; text-overflow: ellipsis; overflow: hidden;" title="Sin información"></div>'
    );
  } else {
    return (
      '<div class="hideEmployer b-bottom row-' +
      data.PKProveedor +
      '" id="Localidad-' +
      data.PKProveedor +
      '"><input class="text-center border-n Localidadtexto-' +
      data.PKProveedor +
      '" type="text" ondblclick="editar_elemento(' +
      data.PKProveedor +
      ',5)" value="' +
      data.Localidad +
      '" readonly style="width: 230px;white-space: nowrap; text-overflow: ellipsis; overflow: hidden;" title="' +
      data.Localidad +
      '"></div>'
    );
  }
}

//Vendedor para la paginación
function templateReferencia(data) {
  if (data.Referencia == null) {
    return (
      '<div class="hideEmployer b-bottom row-' +
      data.PKProveedor +
      '" id="Referencia-' +
      data.PKProveedor +
      '"><input class="text-center border-n Referenciatexto-' +
      data.PKProveedor +
      '" type="text" ondblclick="editar_elemento(' +
      data.PKProveedor +
      ',5)" value="" readonly style="width: 230px;white-space: nowrap; text-overflow: ellipsis; overflow: hidden;" title="Sin información"></div>'
    );
  } else {
    return (
      '<div class="hideEmployer b-bottom row-' +
      data.PKProveedor +
      '" id="Referencia-' +
      data.PKProveedor +
      '"><input class="text-center border-n Referenciatexto-' +
      data.PKProveedor +
      '" type="text" ondblclick="editar_elemento(' +
      data.PKProveedor +
      ',5)" value="' +
      data.Referencia +
      '" readonly style="width: 230px;white-space: nowrap; text-overflow: ellipsis; overflow: hidden;" title="' +
      data.Referencia +
      '"></div>'
    );
  }
}

function editar_elemento(id) {
  window.location.href = "editar_proveedor.php?p=" + id;
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
        $("#sort-" + respuesta[i].PKColumnasProveedores)
          .off("click")
          .on("click", function () {
            var idDom = $("#sort-" + respuesta[i].PKColumnasProveedores);
            _filterQuery.order.column = respuesta[i].PKColumnasProveedores;

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
                $("#sort-" + respuesta[j].PKColumnasProveedores).data("sort") +
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
    if (array[i].PKColumnasProveedores !== _filterQuery.order.column) {
      $("#sort-" + array[i].PKColumnasProveedores).data("sort", 0);
    }
  });
}

/*===============================
=            EVENTOS            =
===============================*/

function verModal() {
  varContainer = ".listaColumnas";
  $(".listaColumnas").show();
}

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
    "export_proveedores?sort=" +
    sort +
    "&search=" +
    search +
    "&indice=" +
    indice;
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

      //PROVEEDORES
      if (pkPantalla == "12") {
        var html = "",
          html2 = "";
        if (_permissions.add == "1") {
          html = `<a href="agregar_proveedor.php" class="btn-table-custom buttons-excel buttons-html5 btn-table-custom--blue" title="Agregar cliente"><i class="fas fa-plus-square"></i> Agregar proveedor</a>`;
          $("#btnAddPermissions").html(html);
        } else {
          html = ``;
          $("#btnAddPermissions").html(html);
        }

        if (_permissions.export == "1") {
          html2 = `<button class="btn-table-custom btn-table-custom--turquoise" id="exportProveedores" onclick="exportarPDF()" title="Excel"><i class="fas fa-cloud-download-alt"></i> Descargar excel</button>`;
          $("#btnExportPermissions").html(html2);
        } else {
          html2 = ``;
          $("#btnExportPermissions").html(html2);
        }
      }
    },
  });
}
