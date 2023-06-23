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

  var topButtons = [
    {
      text: '<span class="d-flex align-items-center"><img src="../../img/icons/ICONO_AGREGAR_DARK.svg" width="20" class="mr-1"> Añadir registro</span>',
      className: "btn-custom--white-dark",
      action: function () {

          selectSucursal.set(['0']);
          selectPeriodo.set(['0']);
          selectTipo.set(['0']);

          $("#txtFechaPago").val("");
          $("#txtFechaInicio").val("");
          $("#txtFechaFin").val("");
          $( "#ajustarMesCalendario" ).prop( "checked", false );
          $("#ajustarMesCalendarioMostrar").css('display','none');
          $("#agregar_nomina").modal("show");

      },
    },
    {
      extend: "excelHtml5",
      text: '<span class="d-flex align-items-center"><img src="../../img/icons/ICONO DESCARGAR2_Mesa de trabajo 1.svg" width="20" class="mr-1"> Descargar excel</span>',
      className: "btn-custom--white-dark",
      titleAttr: "Excel",
    },
  ];

  $("#tblNominas").dataTable({
    language: idioma_espanol,
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
      buttons: topButtons,
    },
    ajax: `functions/function_nomina_principal.php`,
    columns: [
      { data: "no nomina" },
      { data: "sucursal" },
      { data: "periodicidad" },
      { data: "tipo" },
      { data: "fecha inicio" },
      { data: "confidencial" },
      { data: "acciones" },
    ],
  });
});
