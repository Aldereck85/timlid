function cargarTablaContactos(id, _permissionsEdit) {
  $("#tblListadoDatosContactoCliente").DataTable({
    language: setFormatDatatables(),
    info: false,
    scrollX: true,
    bSort: false,
    pageLength: 15,
    responsive: true,
    lengthChange: false,
    columnDefs: [
      { orderable: false, targets: [0,8], visible: false },
      { orderable: false, targets: 6, width: "200px" },
      { orderable: false, targets: 7, width: "5%" },
      
    ],
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
        funcion: "get_contacto_clientesTable",
        data: id,
        data2: _permissionsEdit,
      },
    },
    columns: [
      { data: "Id" },
      { data: "Nombre" },
      { data: "Apellido" },
      { data: "Puesto" },
      { data: "TelefonoFijo" },
      { data: "Celular" },
      { data: "RecibirCorreos" },
      { data: "Email" },
      { data: "Acciones", width: "5%" },
    ],
  });

  //$("#tblListadoDatosContactoCliente").DataTable().ajax.reload();
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
      sFirst: "Primero",
      sLast: "Último",
      sNext: "<i class='fas fa-chevron-right'></i>",
      sPrevious: "<i class='fas fa-chevron-left'></i>",
    },
  };
  return idioma_espanol;
}