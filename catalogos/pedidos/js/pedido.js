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

function filtrarTablaPedido() {
  var filtro = "";
  $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
    var estatus = data[6]; // informacion del estado de la cotizacion

    if (filtro == "") {
      return true;
    }

    if (estatus == filtro) {
      return true;
    } else {
      return false;
    }
  });
  var fromDate = $("#txtDateFrom").val()
    ? $("#txtDateFrom").val()
    : "";
  var toDate = $("#txtDateTo").val() ? $("#txtDateTo").val() : "";
  console.log({fromDate});
  console.log({toDate});
  $("#tblPedido").DataTable().destroy();
  var tablePedidos = $("#tblPedido").DataTable({
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
          text: '<span class="d-flex align-items-center"><img src="../../img/icons/ICONO_AGREGAR_DARK.svg" width="20" class="mr-1"> Añadir registro</span>',
          className: "btn-custom--white-dark",
          action: function () {
            crearPedido();
          },
        },
        {
          extend: "excelHtml5",
          text: '<span class="d-flex align-items-center"><img src="../../img/icons/ICONO DESCARGAR2_Mesa de trabajo 1.svg" width="20" class="mr-1"> Descargar excel</span>',
          className: "btn-custom--white-dark",
          titleAttr: "Excel",
        },
      ],
    },
    ajax: {
      url: "php/funciones.php",
      data: {
        clase: "get_data",
        funcion: "get_Pedido",
        data3: fromDate,
        data4: toDate,
      },
    },
    columns: [
      { data: "No Pedido", with: "10%" },
      { data: "Sucursal origen", with: "10%" },
      { data: "Sucursal destino", with: "10%" },
      { data: "Cliente" },
      { data: "Fecha generacion" },
      { data: "Tipo pedido" },
      { data: "Estatus" },
      { data: "Estatus factura" },
      { data: "Acciones" },
    ],
  });

  new $.fn.dataTable.Buttons(tablePedidos, {
    dom: {
      button: {
        tag: "button",
        className: "btn-table-custom",
      },
      buttonLiner: {
        tag: null,
      },
    },
  });

  tablePedidos.buttons(1, null).container().appendTo("#btn-filters");
}

$(document).ready(function () {
  filtrarTablaPedido();
});

$(document).on("click", "#btnFilterEntries", function () {
  filtrarTablaPedido();
});