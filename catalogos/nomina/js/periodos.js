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
      sNext: "<i class='fas fa-chevron-right'></i>",
      sPrevious: "<i class='fas fa-chevron-left'></i>",
    },
  };

  /*
      {
        text: '<i class="fas fa-plus-square"></i> Añadir registro',
        className: "btn-table-custom--blue",
        action: function () {
            $("#agregar_nomina").modal("show");
        },
      },
  */
  var topButtons = [
    {
      extend: "excelHtml5",
      text: '<i class="fas fa-cloud-download-alt"></i> Descargar excel',
      className: "btn-table-custom--turquoise",
      titleAttr: "Excel",
    },
  ];
  
  $("#tblNominas").dataTable({
    language: idioma_espanol,
    info: false,
    scrollX: true,
    bSort: false,
    pageLength: 60,
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
      url: `functions/function_periodos.php`,
      type: "POST",
      data: {
        "idNomina": idNominaG
      } 
    },
    columns: [
      { data: "no nomina" },
      { data: "fecha inicio" },
      { data: "fecha fin" },
      { data: "fecha pago" },
      { data: "no empleados" },
      { data: "total" },
      { data: "ultima nomina" },
      { data: "autorizada" },
      { data: "estatus" },
      { data: "acciones" },
    ],
  });
});
