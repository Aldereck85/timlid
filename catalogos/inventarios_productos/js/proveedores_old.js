function cargarTablaProveedores (id) {
  $("#tblListadoProveedoresProducto").dataTable({
    language: setFormatDatatables(),
    dom: "Bfrtip",
    buttons: [
    ],
    scrollX: true,
    lengthChange: false,
    info: false,
    ajax: {
      url: "../../php/funciones.php",
      data: { clase: "get_data", funcion: "get_proveedorTable", data:id },
    },
    "pageLength": 20,
    "order": [ 0, 'desc' ],
    columns: [
      { data: "Id" },
      { data: "Proveedor" },
      { data: "Producto" },
      { data: "Clave" },
      { data: "Precio" },
      { data: "DiasEntrega" },
    ],
    columnDefs: [
      { orderable: false, targets: 0, visible: false },
    ],
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
