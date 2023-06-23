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

$(document).ready(function () {
  var tableProductos = $("#tblProductos").DataTable({
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
          className: "btn-custom mr-2",
        },
        buttonLiner: {
          tag: null,
        },
      },
      buttons: [
        {
          text: '<span class="d-flex align-items-center"><img src="../../../../img/icons/ICONO_AGREGAR_DARK.svg" width="20" class="mr-1 color-blue-svg"> Añadir registro</span>',
          className: "btn-custom--border-blue",
          action: function () {
            window.location.href = "agregar_producto.php";
          },
        },
        {
          extend: "excelHtml5",
          text: '<span class="d-flex align-items-center"><img src="../../../../img/icons/ICONO DESCARGAR2_Mesa de trabajo 1.svg" width="20" class="mr-1 color-blue-svg"> Descargar excel</span>',
          className: "btn-custom--border-blue",
          titleAttr: "Excel",
          exportOptions: {
            columns: ":visible",
          },
        },
        {
          text: '<span class="d-flex align-items-center"><img src="../../../../img/icons/ICONO SUBIR ARCHIVO-01.svg" width="20" class="mr-1 color-blue-svg"> Importar excel</span>',
          className: "btn-custom--border-blue",
          action: function () {
            $("#excelmodal").modal("show");
          },
        },
        {
          text: '<span class="d-flex align-items-center btn-filtros"><img src="../../../../img/icons/ICONO FILTRAR-01.svg" width="20" class="mr-1 btn-filtros color-blue-svg"> Filtrar columnas</span>',
          className: "btn-custom--border-blue",
          action: function (e) {
            window.addEventListener("click", function (e) {
              if (
                e.target.classList.contains("btn-filtros") ||
                e.target.classList.contains("filtro-columna")
              ) {
                document
                  .getElementById("listaColumnas")
                  .classList.remove("d-none");
                return;
              }
              document.getElementById("listaColumnas").classList.add("d-none");
            });
          },
        },
      ],
    },
    ajax: {
      url: "../../php/funciones.php",
      data: { clase: "get_data", funcion: "get_productsTable" },
    },
    columns: [
      { data: "Nombre" },
      { data: "ClaveInterna" },
      { data: "CodigoBarras" },
      { data: "CategoriaProductos" },
      { data: "MarcaProducto" },
      { data: "Descripcion" },
      { data: "TipoProducto" },
      { data: "Imagen" },
      { data: "Estatus" },
    ],
  });

  $(".filtro-columna").on("click", function (e) {
    console.log("holis");
    var item = e.target;
    var column = tableProductos.column($(this).attr("data-column"));
    column.visible(!column.visible());
    if (item.tagName === "DIV") {
      item.classList.contains("checked-type-column")
        ? item.classList.remove(
            "checked-type-column",
            "checked-type-column-imgProductos"
          )
        : item.classList.add(
            "checked-type-column",
            "checked-type-column-imgProductos"
          );
    }
  });

  new SlimSelect({
    select: "#tipoImportacion",
    showContent: "up",
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

function obtenerIdProductoEditar(id) {
  window.location.href = "editar_producto.php?p=" + id;
}

function obtenerIdProductoEliminar(id) {
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_DataDatosProducto", data: id },
    dataType: "json",
    success: function (data) {
      console.log("respuesta de datos de producto: ", data);
      /* Validar si ya existe el identificador con ese nombre*/

      $("#txtNombre").val(data[0].Nombre);
      $("#txtPKProductoD").val(id);
    },
  });
}

function eliminarProducto() {
  var PKProducto = $("#txtPKProductoD").val();

  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "delete_data",
      funcion: "delete_Producto",
      data: PKProducto,
    },
    dataType: "json",
    success: function (data) {
      console.log("respuesta de eliminar producto: ", data);
      /* Validar si ya existe el identificador con ese nombre*/

      if (data[0].status) {
        //window.location.href = "../productos";
        $("#tblProductos").DataTable().ajax.reload();
        Swal.fire(
          "Eliminación exitosa",
          "Se eliminó el producto con exito",
          "success"
        );
      } else {
        //Swal.fire('Error',"No se eliminó el producto con exito","warning");
      }
    },
  });
}

function validarExcel() {
  $("#loaderValidacion").css("display", "block");

  var formData = new FormData();
  formData.append("dataexcel", dataexcel.files[0]);
  formData.append("tipo", $("#tipoImportacion").val());
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
  formData.append("tipo", $("#tipoImportacion").val());
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

/*$(document).ready(function(){

  $( window ).on( "load", function() {
    setTimeout(ocultar, 1000);
  });

  function ocultar(){
    $("#loader").fadeOut("slow");
  }

});*/
