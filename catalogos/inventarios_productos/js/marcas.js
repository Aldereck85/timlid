$(document).ready(function () {
  var filtro = "";
  $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
    var estatus = data[2]; // informacion del estado de la cotizacion

    if (filtro == "") {
      return true;
    }

    if (estatus == filtro) {
      return true;
    } else {
      return false;
    }
  });
  var idemp = $("#txtEmpresa").val();
  var tableMarcas = $("#tblListadoMarcas").DataTable({
    language: setFormatDatatables(),
    info: false,
    scrollX: true,
    bSort: false,
    pageLength: 50,
    responsive: true,
    lengthChange: false,
    columnDefs: [{ orderable: false, targets: 0, visible: false }],
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
          text: '<i class="fas fa-plus-square"></i> Añadir registro',
          className: "btn-table-custom--blue",
          action: function () {
            $('#agregar_Marca').modal('show');
          },
        },
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
      data: { clase: "get_data", funcion: "get_marcasTable", data: idemp },
    },
    columns: [
      { data: "Id" },
      { data: "MarcaProducto" },
      { data: "Estatus" },
      { data: "Acciones", width: "15%" },
    ],
  });

  new $.fn.dataTable.Buttons(tableMarcas, {
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
        text: '<i class="fas fa-globe"></i> Todas',
        className: "btn-table-custom--blue",
        action: function (e, dt, node, config) {
          filtro = "";
          $("#tblListadoMarcas").DataTable().draw();
        },
      },
      {
        text: '<i class="fas fa-check-circle"></i> Activa',
        className: "btn-table-custom--green",
        action: function (e, dt, node, config) {
          filtro = "Activo";
          $("#tblListadoMarcas").DataTable().draw();
        },
      },
      {
        text: '<i class="fas fa-times"></i> Inactiva',
        className: "btn-table-custom--red",
        action: function (e, dt, node, config) {
          filtro = "Inactivo";
          $("#tblListadoMarcas").DataTable().draw();
        },
      },
    ],
  });

  tableMarcas.buttons(1, null).container().appendTo("#btn-filters");

  $("#agregar_Marca").on("hidden.bs.modal", function (event) {
    $("#invalid-nombreMarca").css("display", "none");
    $("#txtMarca").removeClass("is-invalid");
    $("#txtMarca").val("");
  });
});

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
