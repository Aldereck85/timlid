$(document).ready(function () {
  $("#nav-tab-inactivos").click(function () {
    $('#nav-tab-inactivos').one('shown.bs.tab', function (e) {
      $("#tblContactosInactivos").DataTable().clear().draw();
      $("#tblContactosInactivos").DataTable().ajax.reload();
    })
  });

  initDataTableInactivos();
});

function initDataTableInactivos() {
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

  let idioma_espanol = {
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

  let table = $("#tblContactosInactivos").DataTable({
    language: idioma_espanol,
    info: false,
    scrollX: true,
    bSort: false,
    pageLength: 50,
    responsive: true,
    lengthChange: false,
    columnDefs: [{ targets: 0, visible: false }],
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
          extend: "excelHtml5",
          text: '<span class="d-flex align-items-center"><img src="../../img/icons/ICONO DESCARGAR2_Mesa de trabajo 1.svg" width="20" class="mr-1"> Descargar excel</span>',
          className: "btn-custom--white-dark",
          titleAttr: "Excel",
        },
      ],
    },
    ajax: {
      type: "POST",
      url: "app/controladores/ContactoController.php",
      data: { accion: "verClientes" },
      dataSrc: "",
    },
    columns: [
      { data: "id" },
      {
        data: "nombre",
        render: function (data, type, row, meta) {
          var url = "calendario_cliente.php?id=" + row.id + "";
          return '<a href="' + url + '">' + row.nombre + "</a>";
        },
      },
      { data: "email" },
      { data: "razon_social" },
      { data: "rfc" },
      { data: "acciones" },
    ],
  });

  //reset_listeners();
}

/* function reset_listeners() {
  delete_searchbuilder();
  left_right_searchbuilder();
  add_searchbuilder();
} */
