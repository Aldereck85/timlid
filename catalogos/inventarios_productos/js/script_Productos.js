/* Variables */

let varContainer = ""; //variable que guarda la clase que se va a ocultar con click fuera.
let selectedColumns = []; //Guardará info de las columnas seleccionadas para mostrar (Array de selectedColumns)
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

var _global = {
  rutaServer: "",
};

//Obtener la Lista de las columnas disponibles para la tabla
$(document).ready(function () {
  validate_Permissions(7);

  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "lista_columnasProd" },
    dataType: "json",
    success: function (respuesta) {
      console.log("Respuesta lista_columnasProd: ", respuesta);

      //Variables de bloque para conocer si esta seleccionada la columna y que acción hacer al darle click
      let classCheck;
      let onclick;

      for (i = 0; i < respuesta.length; i++) {
        //Asignar valor a la variable de seleccion
        if (respuesta[i].Seleccionada == 1) {
          //Clase y onclick para check en los primeros 5 valores
          classCheck = "checked-type-column";
          onclick =
            'onclick="deseleccionar(' + respuesta[i].PKColumnasProductos + ')"';

          //Asignar los valores al arreglo
          selectedColumns.push([
            respuesta[i].PKColumnasProductos,
            respuesta[i].FKTipoColumnaProductos,
          ]);

          //Clase y onclick para valores que se mostrarán en la modal
          classCheckColumn = "checked";
          onclickColumn =
            'onclick="deseleccionarModal(' +
            respuesta[i].PKColumnasProductos +
            ')"';
        } else {
          //Clase y onclick en los primeros 5 valores
          classCheck = "check-type-column";
          onclick =
            'onclick="seleccionar(' + respuesta[i].PKColumnasProductos + ')"';

          //Clase y onclick para valores que se mostrarán en la modal
          classCheckColumn = "";
          onclickColumn =
            'onclick="seleccionarModal(' +
            respuesta[i].PKColumnasProductos +
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
    data: { clase: "get_data", funcion: "orden_columnasProd" },
    dataType: "json",
    success: function (respuesta) {
      console.log("Respuesta orden_columnasProd: ", respuesta);

      for (i = 0; i < respuesta.length; i++) {
        if (respuesta[i].FKTipoColumnaProductos != 1) {
          if (respuesta[i].Nombre == "Nombre") {
            $("#sortableColumns").append(
              '<div id="col_' +
                respuesta[i].PKColumnasProductos +
                '" class="col_nombre" data-pos=' +
                respuesta[i].PKColumnasProductos +
                ">" +
                '<div class="columna-producto handle column-header">' +
                '<div class="column-title">' +
                respuesta[i].Nombre +
                '</div><div><a class="column-order" id="sort-' +
                respuesta[i].PKColumnasProductos +
                '" data-sort="0" href="#"><i class="fas fa-sort"></i></a></div>' +
                "</div>" +
                '<div id="column-' +
                respuesta[i].PKColumnasProductos +
                '" class="columna-info"></div>' +
                "</div>"
            );
          } else {
            $("#sortableColumns").append(
              '<div id="col_' +
                respuesta[i].PKColumnasProductos +
                '" data-pos=' +
                respuesta[i].PKColumnasProductos +
                ">" +
                '<div class="columna-producto handle column-header">' +
                '<div class="column-title">' +
                respuesta[i].Nombre +
                '</div><div><a class="column-order" id="sort-' +
                respuesta[i].PKColumnasProductos +
                '" data-sort="0" href="#"><i class="fas fa-sort"></i></a></div>' +
                "</div>" +
                '<div id="column-' +
                respuesta[i].PKColumnasProductos +
                '" class="columna-info"></div>' +
                "</div>"
            );
          }
        } else {
          if (respuesta[i].Nombre == "Nombre") {
            $("#noSortableColumns").append(
              '<div id="col_' +
                respuesta[i].PKColumnasProductos +
                '" class="col_nombre" data-pos=' +
                respuesta[i].PKColumnasProductos +
                ">" +
                '<div class="columna-producto column-header">' +
                '<div class="column-title">' +
                respuesta[i].Nombre +
                '</div><div><a class="column-order" id="sort-' +
                respuesta[i].PKColumnasProductos +
                '" data-sort="0" href="#"><i class="fas fa-sort"></i></a></div>' +
                "</div>" +
                '<div id="column-' +
                respuesta[i].PKColumnasProductos +
                '" class="columna-info"></div>' +
                "</div>"
            );
          } else {
            $("#noSortableColumns").append(
              '<div id="col_' +
                respuesta[i].PKColumnasProductos +
                '" data-pos=' +
                respuesta[i].PKColumnasProductos +
                ">" +
                '<div class="columna-producto column-header">' +
                '<div class="column-title">' +
                respuesta[i].Nombre +
                '</div><div><a class="column-order" id="sort-' +
                respuesta[i].PKColumnasProductos +
                '" data-sort="0" href="#"><i class="fas fa-sort"></i></a></div>' +
                "</div>" +
                '<div id="column-' +
                respuesta[i].PKColumnasProductos +
                '" class="columna-info"></div>' +
                "</div>"
            );
          }
        }
      }

      console.log("selectedColumns en orden_columnasProd", selectedColumns);
    },
    error: function (error) {
      console.log(error);
    },
  });

  //Pintar la fila sobre la que se posiciona
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "obtener_idsProd" },
    dataType: "json",
    success: function (respuesta) {
      $.each(respuesta, function (i) {
        let isActive = respuesta[i].estatus == 1;
        /*$(document).on(
          "mouseenter",
          "#idProducto-" + respuesta[i].PKProducto,
          function () {
            $(".idProducto-" + respuesta[i].PKProducto).css(
              "display",
              "inline-block"
            );
            $(".idProducto-" + respuesta[i].PKProducto).css(
              "text-align",
              "center"
            );
            //$(".idProducto-" + respuesta[i].PKProducto).css("margin-left","40%");
            $("#edit-icon-" + respuesta[i].PKProducto).css(
              "display",
              "inline-block"
            );
            $("#edit-icon-" + respuesta[i].PKProducto).css("float", "right");
            if (isActive) {
              if (_permissions.delete == '1') {
                $("#delete-icon-" + respuesta[i].PKProducto).css(
                  "display",
                  "inline-block"
                );
                $("#delete-icon-" + respuesta[i].PKProducto).css("float", "right");
              }
            }
            
          }
        );
     
        $(document).on(
          "mouseleave",
          "#idProducto-" + respuesta[i].PKProducto,
          function () {
            $(".idProducto-" + respuesta[i].PKProducto).css(
              "display",
              "inline-block"
            );
            $(".idProducto-" + respuesta[i].PKProducto).css(
              "text-align",
              "center"
            );
            $(".idProducto-" + respuesta[i].PKProducto).css(
              "margin-left",
              "0px"
            );
            $("#edit-icon-" + respuesta[i].PKProducto).css("display", "none");
            if (isActive) {
              if (_permissions.delete == '1') {
                $("#delete-icon-" + respuesta[i].PKProducto).css("display", "none");
              }
            } 
          }
        );*/

        $(document).on(
          "mouseenter",
          ".row-" + respuesta[i].PKProducto,
          function () {
            /*$('#idProducto-'+respuesta[i].PKProducto).css('background-color','rgba(192,247,231,3.0)');*/
            $("#Nombre-" + respuesta[i].PKProducto).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $(".Nombretexto-" + respuesta[i].PKProducto).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $("#ClaveInterna-" + respuesta[i].PKProducto).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $(".ClaveInternatexto-" + respuesta[i].PKProducto).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $("#CodigoBarras-" + respuesta[i].PKProducto).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $(".CodigoBarrastexto-" + respuesta[i].PKProducto).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $("#CategoriaProductos-" + respuesta[i].PKProducto).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $(".CategoriaProductostexto-" + respuesta[i].PKProducto).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $("#MarcaProducto-" + respuesta[i].PKProducto).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $(".MarcaProductotexto-" + respuesta[i].PKProducto).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $("#Descripcion-" + respuesta[i].PKProducto).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $(".Descripciontexto-" + respuesta[i].PKProducto).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $("#TipoProducto-" + respuesta[i].PKProducto).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $(".TipoProductotexto-" + respuesta[i].PKProducto).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $("#Imagen-" + respuesta[i].PKProducto).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $(".Imagentexto-" + respuesta[i].PKProducto).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $("#Estatus-" + respuesta[i].PKProducto).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $(".Estatustexto-" + respuesta[i].PKProducto).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
          }
        );

        $(document).on(
          "mouseleave",
          ".row-" + respuesta[i].PKProducto,
          function () {
            /*$(this).css('background-color','white');
					$('#idProducto-'+respuesta[i].PKProducto).css('background-color','white');*/
            $("#Nombre-" + respuesta[i].PKProducto).css(
              "background-color",
              "white"
            );
            $(".Nombretexto-" + respuesta[i].PKProducto).css(
              "background-color",
              "white"
            );
            $("#ClaveInterna-" + respuesta[i].PKProducto).css(
              "background-color",
              "white"
            );
            $(".ClaveInternatexto-" + respuesta[i].PKProducto).css(
              "background-color",
              "white"
            );
            $("#CodigoBarras-" + respuesta[i].PKProducto).css(
              "background-color",
              "white"
            );
            $(".CodigoBarrastexto-" + respuesta[i].PKProducto).css(
              "background-color",
              "white"
            );
            $("#CategoriaProductos-" + respuesta[i].PKProducto).css(
              "background-color",
              "white"
            );
            $(".CategoriaProductostexto-" + respuesta[i].PKProducto).css(
              "background-color",
              "white"
            );
            $("#MarcaProducto-" + respuesta[i].PKProducto).css(
              "background-color",
              "white"
            );
            $(".MarcaProductotexto-" + respuesta[i].PKProducto).css(
              "background-color",
              "white"
            );
            $("#Descripcion-" + respuesta[i].PKProducto).css(
              "background-color",
              "white"
            );
            $(".Descripciontexto-" + respuesta[i].PKProducto).css(
              "background-color",
              "white"
            );
            $("#TipoProducto-" + respuesta[i].PKProducto).css(
              "background-color",
              "white"
            );
            $(".TipoProductotexto-" + respuesta[i].PKProducto).css(
              "background-color",
              "white"
            );
            $("#Imagen-" + respuesta[i].PKProducto).css(
              "background-color",
              "white"
            );
            $(".Imagentexto-" + respuesta[i].PKProducto).css(
              "background-color",
              "white"
            );
            $("#Estatus-" + respuesta[i].PKProducto).css(
              "background-color",
              "white"
            );
            $(".Estatustexto-" + respuesta[i].PKProducto).css(
              "background-color",
              "white"
            );
          }
        );

        $(document).on(
          "click",
          "#edit-tabs-" + respuesta[i].PKProducto,
          function () {
            var data = $(this).data("id");

            window.location.href = "editar_producto.php?p=" + data;

            //
            //alert('Pendiente de desarrollo y diseño\n'+$(this).data("id"));
            //console.log($(this).data("id"));
          }
        );

        $(document).on(
          "click",
          "#edit-tabs2-" + respuesta[i].PKProducto,
          function () {
            var data = $(this).data("id");

            obtenerIdProductoEliminar(data);
            $("#eliminar_Producto").modal("show");

            //
            //alert('Pendiente de desarrollo y diseño\n'+$(this).data("id"));
            //console.log($(this).data("id"));
          }
        );
      });
    },
  });
});

/* Seleccionar y deseleccionar las columnas cambiando sus colores y clases */
function deseleccionar(pkColumnaProducto) {
  console.log("Deseleccionar en primera vista");

  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "edit_data",
      funcion: "update_check_columnProd",
      data: pkColumnaProducto,
      flag: 0,
    },
    dataType: "json",
    success: function (respuesta) {
      console.log("Respuesta :" + respuesta[0].status);
      if (respuesta[0].status) {
        //Asignar clases para activar y desactivar checks en las modales de selección
        $("#checkType-" + pkColumnaProducto).removeClass("checked-type-column");
        $("#checkType-" + pkColumnaProducto).addClass("check-type-column");

        $("#checkType-" + pkColumnaProducto).removeAttr("onclick");
        $("#checkType-" + pkColumnaProducto).attr(
          "onclick",
          "seleccionar(" + pkColumnaProducto + ")"
        );

        //Quitar columna de la tabla
        $("#col_" + pkColumnaProducto).remove();
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function deseleccionarModal(pkColumnaProducto) {
  console.log("Deseleccionar en modal");

  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "edit_data",
      funcion: "update_check_columnProd",
      data: pkColumnaProducto,
      flag: 0,
    },
    dataType: "json",
    success: function (respuesta) {
      console.log(respuesta[0].status);
      if (respuesta[0].status) {
        //Asignar clases para activar y desactivar checks en las modales de selección
        $("#checkType-" + pkColumnaProducto).removeClass("checked");
        /* $("#checkType-" + pkColumnaProducto).addClass(
          "check-type-column-modal"
        ); */

        $("#checkType-" + pkColumnaProducto).removeAttr("onclickColumn");
        $("#checkType-" + pkColumnaProducto).attr(
          "onclick",
          "seleccionarModal(" + pkColumnaProducto + ")"
        );

        //Quitar columna de la tabla
        $("#col_" + pkColumnaProducto).remove();
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function seleccionar(pkColumnaProducto) {
  console.log("Seleccionar en primera vista");
  mostrar();
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "edit_data",
      funcion: "update_check_columnProd",
      data: pkColumnaProducto,
      flag: 1,
    },
    dataType: "json",
    success: function (respuesta) {
      if (respuesta[0].status) {
        //Asignar clases para activar y desactivar checks en las modales de selección
        $("#checkType-" + pkColumnaProducto).removeClass("check-type-column");
        $("#checkType-" + pkColumnaProducto).addClass("checked-type-column");

        $("#checkType-" + pkColumnaProducto).removeAttr("onclick");
        $("#checkType-" + pkColumnaProducto).attr(
          "onclick",
          "deseleccionar(" + pkColumnaProducto + ")"
        );

        //Mostrar columna
        $("#sortableColumns").append(
          '<div id="col_' +
            pkColumnaProducto +
            '" data-pos=' +
            pkColumnaProducto +
            ">" +
            '<div class="columna-producto handle column-header">' +
            '<div class="column-title">' +
            respuesta[0].array[0].columnaAfectada +
            '</div><div><a class="column-order" id="sort-' +
            pkColumnaProducto +
            '" data-sort="0" href="#"><i class="fas fa-sort"></i></a></div>' +
            "</div>" +
            '<div id="column-' +
            pkColumnaProducto +
            '" class="columna-info"></div>' +
            "</div>"
        );

        selectedColumns.push([
          pkColumnaProducto,
          respuesta[0].array[0].tipoColumna,
        ]);

        console.log("SE SELECCIONO COLUMNA", selectedColumns);

        get_info();

        let coord = $("#col_" + pkColumnaProducto).offset();

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

function seleccionarModal(pkColumnaProducto) {
  console.log("Seleccionar en modal");
  mostrar();
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "edit_data",
      funcion: "update_check_columnProd",
      data: pkColumnaProducto,
      flag: 1,
    },
    dataType: "json",
    success: function (respuesta) {
      if (respuesta[0].status) {
        /* $("#checkType-" + pkColumnaProducto).removeClass(
          "check-type-column-modal"
        ); */
        $("#checkType-" + pkColumnaProducto).addClass("checked");

        $("#checkType-" + pkColumnaProducto).removeAttr("onclickColumn");
        $("#checkType-" + pkColumnaProducto).attr(
          "onclick",
          "deseleccionarModal(" + pkColumnaProducto + ")"
        );

        //Mostrar columna
        $("#sortableColumns").append(
          '<div id="col_' +
            pkColumnaProducto +
            '" data-pos=' +
            pkColumnaProducto +
            ">" +
            '<div class="columna-producto handle column-header">' +
            '<div class="column-title">' +
            respuesta[0].array[0].columnaAfectada +
            '</div><div><a class="column-order" id="sort-' +
            pkColumnaProducto +
            '" data-sort="0" href="#"><i class="fas fa-sort"></i></a></div>' +
            "</div>" +
            '<div id="column-' +
            pkColumnaProducto +
            '" class="columna-info"></div>' +
            "</div>"
        );
        selectedColumns.push([
          pkColumnaProducto,
          respuesta[0].array[0].tipoColumna,
        ]);

        console.log("SE SELECCIONO COLUMNA", selectedColumns);

        get_info();

        let coord = $("#col_" + pkColumnaProducto).offset();

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
        funcion: "orden_datosProd",
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
  //getSortable();
  //dataSortMain();
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
            $("#column-" + selectedColumns[i][0]).html(html.idProducto);
          }
          if (selectedColumns[i][1] == 2) {
            //Nombre del producto
            $("#column-" + selectedColumns[i][0]).html(html.Nombre);
          }
          if (selectedColumns[i][1] == 3) {
            //Clave interna del producto
            $("#column-" + selectedColumns[i][0]).html(html.ClaveInterna);
          }
          if (selectedColumns[i][1] == 4) {
            //Codigo de barras del producto
            $("#column-" + selectedColumns[i][0]).html(html.CodigoBarras);
          }
          if (selectedColumns[i][1] == 5) {
            //CategoriaProductos del producto
            $("#column-" + selectedColumns[i][0]).html(html.CategoriaProductos);
          }
          if (selectedColumns[i][1] == 6) {
            //Marca del producto
            $("#column-" + selectedColumns[i][0]).html(html.MarcaProducto);
          }
          if (selectedColumns[i][1] == 7) {
            //Descripcion del producto
            $("#column-" + selectedColumns[i][0]).html(html.Descripcion);
          }
          if (selectedColumns[i][1] == 8) {
            //Tipo de del producto
            $("#column-" + selectedColumns[i][0]).html(html.TipoProducto);
          }
          if (selectedColumns[i][1] == 9) {
            //Imagen del producto
            $("#column-" + selectedColumns[i][0]).html(html.Imagen);
          }
          if (selectedColumns[i][1] == 10) {
            //Estatus del producto
            $("#column-" + selectedColumns[i][0]).html(html.Estatus);
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
    idProducto: "",
    Nombre: "",
    ClaveInterna: "",
    CodigoBarras: "",
    CategoriaProductos: "",
    MarcaProducto: "",
    Descripcion: "",
    TipoProducto: "",
    Imagen: "",
    Estatus: "",
  };

  for (var j = 0; j < data.length; j++) {
    html.idProducto += templateIdProducto(data[j]);
    html.Nombre += templateNombre(data[j]);
    html.ClaveInterna += templateClaveInterna(data[j]);
    html.CodigoBarras += templateCodigoBarras(data[j]);
    html.CategoriaProductos += templateCategoriaProductos(data[j]);
    html.MarcaProducto += templateMarcaProducto(data[j]);
    html.Descripcion += templateDescripcion(data[j]);
    html.TipoProducto += templateTipoProducto(data[j]);
    html.Imagen += templateImagen(data[j]);
    html.Estatus += templateEstatus(data[j]);
  }
  //console.log(html);
  return html;
}

//PKProducto para la paginación
function templateIdProducto(singleObj) {
  var btEliminar = "";
  var btEditar = "";

  if (_permissions.delete == "1") {
    btEliminar = `<i class="fas fa-trash-alt color-primary" id="delete-icon-${singleObj.PKProducto}"></i>`;
  } else {
    btEliminar = "";
  }

  if (_permissions.edit == "1") {
    btEditar = `<i class="fas fa-edit color-primary" id="edit-icon-${singleObj.PKProducto}"></i>`;
  } else {
    btEditar = "";
  }

  if (singleObj.Estatus == "Inactivo") {
    return `<div class="hideEmployer show-icon-edit b-bottom row-${singleObj.PKProducto}" id="idProducto-${singleObj.PKProducto}" style="height: 36px; color:black; border-left: 5px solid #cac8c6; color: #FFFFFF;" title="Producto inactivo">      
              <span class="idProducto-${singleObj.PKProducto}" style="margin-left: auto; margin-right: auto; display: block;">
                <a id="edit-tabs-${singleObj.PKProducto}" data-id="${singleObj.PKProducto}" href="#">
                  ${btEditar}
                </a>
                <a id="edit-tabs2-${singleObj.PKProducto}" data-id="${singleObj.PKProducto}" href="#">
                  ${btEliminar}
                </a>
              </span>
            </div>`;
  } else {
    return `<div class="hideEmployer show-icon-edit b-bottom row-${singleObj.PKProducto}" id="idProducto-${singleObj.PKProducto}" style="height: 36px; color:black; border-left: 5px solid #28c67a;; color: #FFFFFF;" title="Producto activo">
              <div class="col-lg-12 input-group">
                <span class="idProducto-${singleObj.PKProducto}" style="margin-left: auto; margin-right: auto; display: block;">
                  <a id="edit-tabs-${singleObj.PKProducto}" data-id="${singleObj.PKProducto}" href="#">
                    ${btEditar}
                  </a>
                  <a id="edit-tabs2-${singleObj.PKProducto}" data-id="${singleObj.PKProducto}" href="#">
                    ${btEliminar}
                  </a>
                </span>
              </div>
            </div>`;
  }
}

//Nombre para la paginación
function templateNombre(singleObj) {
  if (singleObj.Nombre == null) {
    return (
      '<div class="hideEmployer b-bottom row-' +
      singleObj.PKProducto +
      '" id="Nombre-' +
      singleObj.PKProducto +
      '"><input class="text-center border-n Nombretexto-' +
      singleObj.PKProducto +
      '" type="text" ondblclick="editar_elemento(' +
      singleObj.PKProducto +
      ')" value="" readonly style="width: 470px;white-space: nowrap; text-overflow: ellipsis; overflow: hidden;" title="Sin información"></div>'
    );
  } else {
    return (
      '<div class="hideEmployer b-bottom row-' +
      singleObj.PKProducto +
      '" id="Nombre-' +
      singleObj.PKProducto +
      '"><input class="text-center border-n Nombretexto-' +
      singleObj.PKProducto +
      '" type="text" ondblclick="editar_elemento(' +
      singleObj.PKProducto +
      ')" value="' +
      singleObj.Nombre +
      '" readonly style="width: 470px;white-space: nowrap; text-overflow: ellipsis; overflow: hidden;" title="' +
      singleObj.Nombre +
      '"></div>'
    );
  }
}

//Clave Interna para la paginación
function templateClaveInterna(singleObj) {
  if (singleObj.ClaveInterna == null) {
    return `<div class="hideEmployer b-bottom row-${singleObj.PKProducto}" id="ClaveInterna-${singleObj.PKProducto}">
              <input class="text-center border-n ClaveInternatexto-${singleObj.PKProducto}" type="text" ondblclick="editar_elemento(${singleObj.PKProducto})" value="" readonly style="width: 230px;white-space: nowrap; text-overflow: ellipsis; overflow: hidden;" title="Sin información">
            </div>`;
  } else {
    return `<div class="hideEmployer b-bottom row-${singleObj.PKProducto}" id="ClaveInterna-${singleObj.PKProducto}">
              <input class="text-center border-n ClaveInternatexto-${singleObj.PKProducto}" type="text" ondblclick="editar_elemento(${singleObj.PKProducto})" value="${singleObj.ClaveInterna}" readonly style="width: 230px;white-space: nowrap; text-overflow: ellipsis; overflow: hidden;" title="${singleObj.ClaveInterna}">
            </div>`;
  }
}

//Código de Barras para la paginación
function templateCodigoBarras(singleObj) {
  if (singleObj.CodigoBarras == null) {
    return (
      '<div class="hideEmployer b-bottom row-' +
      singleObj.PKProducto +
      '" id="CodigoBarras-' +
      singleObj.PKProducto +
      '"><input class="text-center border-n CodigoBarrastexto-' +
      singleObj.PKProducto +
      '" type="text" ondblclick="editar_elemento(' +
      singleObj.PKProducto +
      ',5)" value="" readonly style="width: 230px;white-sce: nowrap; text-overflow: ellipsis; overflow: hidden;" title="Sin información"></div>'
    );
  } else {
    return (
      '<div class="hideEmployer b-bottom row-' +
      singleObj.PKProducto +
      '" id="CodigoBarras-' +
      singleObj.PKProducto +
      '"><input class="text-center border-n CodigoBarrastexto-' +
      singleObj.PKProducto +
      '" type="text" ondblclick="editar_elemento(' +
      singleObj.PKProducto +
      ',5)" value="' +
      singleObj.CodigoBarras +
      '" readonly style="width: 230px;white-space: nowrap; text-overflow: ellipsis; overflow: hidden;" title="' +
      singleObj.CodigoBarras +
      '"></div>'
    );
  }
}

//Categoría para la paginación
function templateCategoriaProductos(singleObj) {
  if (
    singleObj.CategoriaProductos == null ||
    singleObj.CategoriaProductos == ""
  ) {
    return (
      '<div class="hideEmployer b-bottom row-' +
      singleObj.PKProducto +
      '" id="CategoriaProductos-' +
      singleObj.PKProducto +
      '"><input class="text-center border-n CategoriaProductostexto-' +
      singleObj.PKProducto +
      '" type="text" ondblclick="editar_elemento(' +
      singleObj.PKProducto +
      ',5)" value="" readonly style="width: 230px;white-ace: nowrap; text-overflow: ellipsis; overflow: hidden;" title="Sin información"></div>'
    );
  } else {
    return (
      '<div class="hideEmployer b-bottom row-' +
      singleObj.PKProducto +
      '" id="CategoriaProductos-' +
      singleObj.PKProducto +
      '"><input class="text-center border-n CategoriaProductostexto-' +
      singleObj.PKProducto +
      '" type="text" ondblclick="editar_elemento(' +
      singleObj.PKProducto +
      ',5)" value="' +
      singleObj.CategoriaProductos +
      '" readonly style="width: 230px;white-space: nowrap; text-overflow: ellipsis; overflow: hidden;" title="' +
      singleObj.CategoriaProductos +
      '"></div>'
    );
  }
}

//Marca para la paginación
function templateMarcaProducto(singleObj) {
  if (singleObj.MarcaProducto == null) {
    return (
      '<div class="hideEmployer b-bottom row-' +
      singleObj.PKProducto +
      '" id="MarcaProducto-' +
      singleObj.PKProducto +
      '"><input class="text-center border-n MarcaProductotexto-' +
      singleObj.PKProducto +
      '" type="text" ondblclick="editar_elemento(' +
      singleObj.PKProducto +
      ',5)" value="" readonly style="widt 230px;white-space: nowrap; text-overflow: ellipsis; overflow: hidden;" title="Sin información"></div>'
    );
  } else {
    return (
      '<div class="hideEmployer b-bottom row-' +
      singleObj.PKProducto +
      '" id="MarcaProducto-' +
      singleObj.PKProducto +
      '"><input class="text-center border-n MarcaProductotexto-' +
      singleObj.PKProducto +
      '" type="text" ondblclick="editar_elemento(' +
      singleObj.PKProducto +
      ',5)" value="' +
      singleObj.MarcaProducto +
      '" readonly style="width: 230px;white-space: nowrap; text-overflow: ellipsis; overflow: hidden;" title="' +
      singleObj.MarcaProducto +
      '"></div>'
    );
  }
}

//Descripción para la paginación
function templateDescripcion(singleObj) {
  if (singleObj.Descripcion == null) {
    return (
      '<div class="hideEmployer b-bottom row-' +
      singleObj.PKProducto +
      '" id="Descripcion-' +
      singleObj.PKProducto +
      '"><input class="text-center border-n Descripciontexto-' +
      singleObj.PKProducto +
      '" type="text" ondblclick="editar_elemento(' +
      singleObj.PKProducto +
      ')" value="" readonly style="width: 230px;white-space: nowrap; text-overflow: ellipsis; overflow: hidden;" title="Sin información"></div>'
    );
  } else if (singleObj.Descripcion == "0000-00-00") {
    return (
      '<div class="hideEmployer b-bottom row-' +
      singleObj.PKProducto +
      '" id="Descripcion-' +
      singleObj.PKProducto +
      '"><input class="text-center border-n Descripciontexto-' +
      singleObj.PKProducto +
      '" type="text" ondblclick="editar_elemento(' +
      singleObj.PKProducto +
      ')" value="No agendado" readonly style="width: 230px;white-space: nowrap; text-overflow: ellipsis; overflow: hidden;" title="' +
      singleObj.Descripcion +
      '"></div>'
    );
  } else {
    return (
      '<div class="hideEmployer b-bottom row-' +
      singleObj.PKProducto +
      '" id="Descripcion-' +
      singleObj.PKProducto +
      '"><input class="text-center border-n Descripciontexto-' +
      singleObj.PKProducto +
      '" type="text" ondblclick="editar_elemento(' +
      singleObj.PKProducto +
      ')" value="' +
      singleObj.Descripcion +
      '" readonly style="width: 230px;white-space: nowrap; text-overflow: ellipsis; overflow: hidden;" title="' +
      singleObj.Descripcion +
      '"></div>'
    );
  }
}

//Fecha del Siguiente Contacto para la paginación
function templateTipoProducto(singleObj) {
  if (singleObj.TipoProducto == null) {
    return (
      '<div class="hideEmployer b-bottom row-' +
      singleObj.PKProducto +
      '" id="TipoProducto-' +
      singleObj.PKProducto +
      '"><input class="text-center border-n TipoProductotexto-' +
      singleObj.PKProducto +
      '" type="text" ondblclick="editar_elemento(' +
      singleObj.PKProducto +
      ')" value="" readonly style="width: 230px;white-space: nowrap; text-overflow: ellipsis; overflow: hidden;" title="Sin información"></div>'
    );
  } else if (singleObj.TipoProducto == "0000-00-00") {
    return (
      '<div class="hideEmployer b-bottom row-' +
      singleObj.PKProducto +
      '" id="TipoProducto-' +
      singleObj.PKProducto +
      '"><input class="text-center border-n TipoProductotexto-' +
      singleObj.PKProducto +
      '" type="text" ondblclick="editar_elemento(' +
      singleObj.PKProducto +
      ')" value="No agendado" readonly style="width: 230px;white-space: nowrap; text-overflow: ellipsis; overflow: hidden;" title="' +
      singleObj.TipoProducto +
      '"></div>'
    );
  } else {
    return (
      '<div class="hideEmployer b-bottom row-' +
      singleObj.PKProducto +
      '" id="TipoProducto-' +
      singleObj.PKProducto +
      '"><input class="text-center border-n TipoProductotexto-' +
      singleObj.PKProducto +
      '" type="text" ondblclick="editar_elemento(' +
      singleObj.PKProducto +
      ')" value="' +
      singleObj.TipoProducto +
      '" readonly style="width: 230px;white-space: nowrap; text-overflow: ellipsis; overflow: hidden;" title="' +
      singleObj.TipoProducto +
      '"></div>'
    );
  }
}

//Monto de credito para la paginación
function templateImagen(singleObj) {
  if (singleObj.Imagen == null) {
    return (
      '<div class="hideEmployer b-bottom row-' +
      singleObj.PKProducto +
      '" id="Imagen-' +
      singleObj.PKProducto +
      '"><input class="text-center border-n Imagentexto-' +
      singleObj.PKProducto +
      '" type="text" ondblclick="editar_elemento(' +
      singleObj.PKProducto +
      ',5)" value="" readonly style="width: 230px;white-space: nowrap; text-overflow: ellipsis; overflow: hidden;" title="Sin información"></div>'
    );
  } else if (singleObj.Imagen == "agregar.svg") {
    return (
      '<div class="hideEmployer b-bottom row-' +
      singleObj.PKProducto +
      '" id="Imagen-' +
      singleObj.PKProducto +
      '"><img src="../../../../imgProd/' +
      singleObj.Imagen +
      '" width="24.5px" class="Imagentexto-' +
      singleObj.PKProducto +
      '" ondblclick="editar_elemento(' +
      singleObj.PKProducto +
      ',5)" title="Sin imagen"></div>'
    );
  } else {
    return (
      '<div class="hideEmployer b-bottom row-' +
      singleObj.PKProducto +
      '" id="Imagen-' +
      singleObj.PKProducto +
      '"><img src="' +
      _global.rutaServer +
      singleObj.Imagen +
      '" width="24.5px" class="zoom Imagentexto-' +
      singleObj.PKProducto +
      '" ondblclick="editar_elemento(' +
      singleObj.PKProducto +
      ',5)" title="Imagen del producto ' +
      singleObj.PKProducto +
      '"></div>'
    );
  }
}

//Días de credito para la paginación
function templateEstatus(singleObj) {
  if (singleObj.Estatus == null) {
    return (
      '<div class="hideEmployer b-bottom row-' +
      singleObj.PKProducto +
      '" id="Estatus-' +
      singleObj.PKProducto +
      '"><input class="text-center border-n Estatustexto-' +
      singleObj.PKProducto +
      '" type="text" ondblclick="editar_elemento(' +
      singleObj.PKProducto +
      ',5)" value="" readonly style="width: 230px;white-space: nowrap; text-overflow: ellipsis; overflow: hidden;" title="Sin información"></div>'
    );
  } else {
    return (
      '<div class="hideEmployer b-bottom row-' +
      singleObj.PKProducto +
      '" id="Estatus-' +
      singleObj.PKProducto +
      '"><input class="text-center border-n Estatustexto-' +
      singleObj.PKProducto +
      '" type="text" ondblclick="editar_elemento(' +
      singleObj.PKProducto +
      ',5)" value="' +
      singleObj.Estatus +
      '" readonly style="width: 230px;white-space: nowrap; text-overflow: ellipsis; overflow: hidden;" title="' +
      singleObj.Estatus +
      '"></div>'
    );
  }
}

function editar_elemento(id) {
  window.location.href = "editar_producto.php?p=" + id;
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
            funcion: "column_orderProd",
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
    data: { clase: "get_data", funcion: "orden_columnasProd" },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta desde sort: ", respuesta);

      $.each(respuesta, function (i) {
        $("#sort-" + respuesta[i].PKColumnasProductos)
          .off("click")
          .on("click", function () {
            var idDom = $("#sort-" + respuesta[i].PKColumnasProductos);
            _filterQuery.order.column = respuesta[i].PKColumnasProductos;

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
                $("#sort-" + respuesta[j].PKColumnasProductos).data("sort") +
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
    if (array[i].PKColumnasProductos !== _filterQuery.order.column) {
      $("#sort-" + array[i].PKColumnasProductos).data("sort", 0);
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
    "export_productos?sort=" + sort + "&search=" + search + "&indice=" + indice;
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
      if (pkPantalla == "7") {
        var html = "",
          html2 = "";
        if (_permissions.add == "1") {
          html = `<a href="agregar_producto.php" class="btn-table-custom buttons-excel buttons-html5 btn-table-custom--blue" title="Agregar producto"><i class="fas fa-plus-square"></i> Agregar producto</a>`;
          $("#btnAddPermissions").html(html);
          $("#btnImportarexcel").html(
            `<button class="btn-table-custom btn-table-custom--turquoise" id="exportProductos" data-toggle="modal" data-target="#excelmodal"><i class="fas fa-cloud-upload-alt"></i> Importar excel</button>`
          );
        } else {
          html = ``;
          $("#btnAddPermissions").html(html);
        }

        if (_permissions.export == "1") {
          html2 = `<button class="btn-table-custom btn-table-custom--turquoise" id="exportProductos" onclick="exportarPDF()" title="Excel"><i class="fas fa-cloud-download-alt"></i> Descargar excel</button>`;
          $("#btnExportPermissions").html(html2);
        } else {
          html2 = ``;
          $("#btnExportPermissions").html(html2);
        }
      }
    },
  });
}
