$(document).ready(function () {
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

  $("#tblConceptos").dataTable({
    language: idioma_espanol,
    dom: "Bfrtip",
    buttons: [
      {
        extend: "excelHtml5",
        text: '<img class="readEditPermissions" type="submit" width="50px" src="../../img/excel-azul.svg" />',
        className: "excelDataTableButton",
        titleAttr: "Excel",
      },
    ],
	order: [[ 1, "desc" ]],
    scrollX: true,
    lengthChange: true,
    info: false,
    ajax: `functions/function_concepto.php`,
    columns: [
      { data: "concepto" },
	  { data: "tipo" },
	  { data: "clave" },
    ],
  });

});