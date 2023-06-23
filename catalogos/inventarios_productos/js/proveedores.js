function setFormatDatatables() {
  var idioma_espanol = {
    sProcessing: "Procesando...",
    sZeroRecords: "No se encontraron resultados",
    sEmptyTable: "Ningún dato disponible en esta tabla",
    sSearch: "<img src='../../../../img/timdesk/buscar.svg' width='20px' />",
    sLoadingRecords: "<img src='../../../../img/timdesk/Preloader.gif' width='100px' style='transition-duration:300ms;' />.",
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

$(document).ready(function () {
  var tableProveedores = $("#tblListadoProveedores").DataTable({
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
          text: '<span class="d-flex align-items-center"><img src="../../../../img/icons/ICONO_AGREGAR_DARK.svg" width="20" class="mr-1"> Añadir registro</span>',
          className: "btn-custom--white-dark",
          action: function () {
            window.location.href = "agregar_proveedor.php";
          },
        },
        {
          extend: "excelHtml5",
          text: '<span class="d-flex align-items-center"><img src="../../../../img/icons/ICONO DESCARGAR2_Mesa de trabajo 1.svg" width="20" class="mr-1"> Descargar excel</span>',
          className: "btn-custom--white-dark",
          titleAttr: "Excel",
          exportOptions: {
            columns: ":visible",
          },
        },
        {
          text: '<span class="d-flex align-items-center"><img src="../../../../img/icons/ICONO SUBIR ARCHIVO-01.svg" width="20" class="mr-1"> Importar excel</span>',
          className: "btn-custom--white-dark",
          action: function () {
            $("#excelmodal").modal("show");
          },
        },
        {
          text: '<span class="d-flex align-items-center btn-filtros"><img src="../../../../img/icons/ICONO FILTRAR-01.svg" width="20" class="mr-1 btn-filtros"> Filtrar columnas</span>',
          className: "btn-custom--white-dark",
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
      data: { clase: "get_data", funcion: "get_proveedoresTable" },
    },
    columns: [
      { data: "NombreComercial" },
      { data: "Movil" },
      { data: "Telefono" },
      { data: "Email" },
      { data: "EmailSecundario" },
      { data: "Giro" },
      { data: "RazonSocial" },
      { data: "DiasDeCredito" },
      { data: "EstatusDelProveedor" },
      { data: "Vendedor" },
      { data: "RFC" },
      { data: "Calle" },
      { data: "NumeroExterior", visible: false },
      { data: "NumeroInterior", visible: false },
      { data: "Municipio", visible: false },
      { data: "Colonia", visible: false },
      { data: "CodigoPostal", visible: false },
      { data: "Pais", visible: false },
      { data: "Estado", visible: false },
      { data: "Localidad", visible: false },
      { data: "Referencia", visible: false },
    ],
  });

  $(".filtro-columna").on("click", function (e) {
    var item = e.target;
    var column = tableProveedores.column($(this).attr("data-column"));
    column.visible(!column.visible());
    if (item.tagName === "DIV") {
      item.classList.contains("checked-type-column")
        ? item.classList.remove(
            "checked-type-column",
            "checked-type-column-imgProveedores"
          )
        : item.classList.add(
            "checked-type-column",
            "checked-type-column-imgProveedores"
          );
    }
  });
});

function obtenerIdProveedorEditar(id) {
  window.location.href = "editar_proveedor.php?p=" + id;
}

function obtenerIdProveedorEliminar(id) {
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_datos_generales_proveedor",
      datos: id,
    },
    dataType: "json",
    success: function (data) {
      console.log("respuesta de datos de proveedor: ", data);
      /* Validar si ya existe el identificador con ese nombre*/

      $("#txtNombre").val(data[0].NombreComercial);
      $("#txtProveedorD").val(id);
    },
  });
}

function eliminarProveedor() {
  var PKProveedor = $("#txtProveedorD").val();

  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "delete_data",
      funcion: "delete_ProveedorTable",
      data: PKProveedor,
    },
    dataType: "json",
    success: function (data) {
      console.log("respuesta de eliminar proveedor: ", data);
      /* Validar si ya existe el identificador con ese nombre*/

      if (data[0].status) {
        $("#tblListadoProveedoresProducto").DataTable().ajax.reload();
        //Swal.fire('Eliminación exitosa',"Se eliminó el proveedor con exito","success");
      } else {
        //Swal.fire('Error',"No se eliminó el proveedor con exito","warning");
      }
    },
  });
}

function cargarTablaProveedores(id, _permissionsEdit) {
  $("#tblListadoProveedoresProducto").dataTable({
    language: setFormatDatatables(),
    info: false,
    scrollX: true,
    bSort: false,
    pageLength: 15,
    responsive: true,
    lengthChange: false,
    columnDefs: [{ orderable: false, targets: [0,6], visible: false }],
    dom: `<"container-fluid"<"row mb-3"<"col-sm-12 col-md-9 p-0 d-flex align-items-center"B><"col-sm-12 col-md-3 p-0"f>>>rti
    <"container-fluid mt-4"<"row"<"#btn-filters.col-sm-12 col-md-10 p-0"><"col-sm-12 col-md-2 p-0"p>>>`,
    buttons: {
      dom: {
        button: {
          tag: "button",
          className: "",//btn-table-custom
        },
        buttonLiner: {
          tag: null,
        },
      },
      buttons: [
        {
          extend: "excelHtml5",
          text: '<span class="d-flex align-items-center"><img src="../../../../img/icons/ICONO DESCARGAR2_Mesa de trabajo 1.svg" width="20" class="mr-1"> Descargar Excel</span>',
          className: "btn-custom--white-dark btn-custom",
          titleAttr: "Excel",
          exportOptions: {
            columns: ":visible",
          },
        },
      ],
    },
    ajax: {
      url: "../../php/funciones.php",
      data: {
        clase: "get_data",
        funcion: "get_proveedorTable",
        data: id,
        data2: _permissionsEdit,
      },
    },
    columns: [
      { data: "Id" },
      { data: "Proveedor" },
      { data: "Producto" },
      { data: "Clave" },
      { data: "Precio" },
      { data: "DiasEntrega" },
      { data: "Acciones", width: "15%" },
    ],
  });
}

/*$(document).ready(function(){

  $( window ).on( "load", function() {
    setTimeout(ocultar, 1000);
  });

  function ocultar(){
    $("#loader").fadeOut("slow");
  }

});
*/
