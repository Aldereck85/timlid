function cargarTablaDireccionesEnvio(id) {
  $("#tblListadoDatosDireccionesEnvioProveedor").dataTable({
    language: setFormatDatatables(),
    language: setFormatDatatables(),
    info: false,
    scrollX: true,
    bSort: false,
    pageLength: 15,
    responsive: true,
    lengthChange: false,
    columnDefs: [
      { orderable: false, targets: 0, visible: false },
      { orderable: false, targets: 10, width: "100px" },
    ],
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
      buttons: [
        {
          extend: "excelHtml5",
          text: '<i class="fas fa-cloud-download-alt"></i> Descargar excel',
          className: "btn-table-custom--turquoise",
          titleAttr: "Excel",
        },
      ],
    },
    ajax: {
      url: "../../php/funciones.php",
      data: {
        clase: "get_data",
        funcion: "get_direccionEnvio_proveedoresTable",
        data: id,
      },
    },
    columns: [
      { data: "Id" },
      { data: "Sucursal" },
      { data: "Email" },
      { data: "Calle" },
      { data: "NumeroExt" },
      { data: "NumeroInt" },
      { data: "Colonia" },
      { data: "Municipio" },
      { data: "Estado" },
      { data: "Pais" },
      { data: "CP" },
    ],
  });

  $("#tblListadoDatosDireccionesEnvioProveedor").DataTable().ajax.reload();
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
      sNext: "<i class='fas fa-chevron-right'></i>",
      sPrevious: "<i class='fas fa-chevron-left'></i>",
    },
  };
  return idioma_espanol;
}
