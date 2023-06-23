$(document).ready(function(){
  $("#tblCFDI").DataTable({
    language: setFormatDatatables(),
    info: false,
    scrollX: true,
    pageLength: 50,
    responsive: true,
    lengthChange: false,
    columnDefs: [{ orderable: false, targets: 0, visible: false }],
    ajax: {
      url: "php/funciones.php",
      data: {
        clase: "get_data",
        funcion: "get_invoicesTable",
      },
    },
    order: [0, "desc"],
    columns: [
      { data: "id" },
      { data: "Folio" },
      { data: "Razon social" },
      { data: "Total facturado" },
      { data: "Estatus" },
      { data: "Fecha de timbrado" },
    ],
    
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
      sNext: "<i class='fas fa-chevron-right'></i>",
      sPrevious: "<i class='fas fa-chevron-left'></i>",
    },
  };
  return idioma_espanol;
}