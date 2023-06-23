var _permissions = {
  read: 0,
  add: 0,
  edit: 0,
  delete: 0,
  export: 0,
};

function cargarTablaOrdenesCompra() {
  var pkUsuario = $("#txtUsuario").val();

  $("#tblListadoOrdenesCompra").dataTable({
    language: setFormatDatatables(),
    dom: "Bfrtip",
    buttons: [],
    scrollX: true,
    lengthChange: false,
    info: false,
    ajax: {
      url: "../../php/funciones.php",
      data: {
        clase: "get_data",
        funcion: "get_ordenesCompraTable",
        data: pkUsuario,
      },
    },
    pageLength: 50,
    responsive: true,
    order: [0, "desc"],
    columns: [
      { data: "Id" },
      { data: "Producto" },
      { data: "Cantidad" },
      { data: "UnidadMedida" },
      { data: "PrecioUnitario" },
      { data: "Impuestos" },
      { data: "Importe" },
    ],
    columnDefs: [{ orderable: false, targets: 0, visible: false }],
  });
}

function cargarTablaOrdenesCompraEdit(pkOrden) {
  $("#tblListadoOrdenesCompra").dataTable({
    language: setFormatDatatables(),
    dom: "Bfrtip",
    buttons: [],
    scrollX: true,
    lengthChange: false,
    info: false,
    ajax: {
      url: "../../php/funciones.php",
      data: {
        clase: "get_data",
        funcion: "get_ordenesCompraTableEdit",
        data: pkOrden,
      },
    },
    //"pageLength": 20,
    paging: false,
    order: [0, "desc"],
    columns: [
      { data: "Id" },
      { data: "Producto" },
      { data: "Cantidad" },
      { data: "UnidadMedida" },
      { data: "PrecioUnitario" },
      { data: "Impuestos" },
      { data: "Importe" },
    ],
    columnDefs: [{ orderable: false, targets: 0, visible: false }],
  });
}

$(document).ready(function () {
  validate_Permissions(22);
});

function setFormatDatatables() {
  var idioma_espanol = {
    sProcessing: "Procesando...",
    sZeroRecords: "No se encontraron resultados",
    sEmptyTable: "Ningún dato disponible en esta tabla",
    sSearch: "<img src='../../../../img/timdesk/buscar.svg' width='20px' />",
    sLoadingRecords: "<img src='../../../../img/timdesk/Preloader.gif' width='100px' style='transition-duration:300ms;' />",
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

function obtenerEditar(id) {
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "validar_estadoOrdenCompra",
      data: id,
    },
    dataType: "json",
    success: function (data) {
      console.log("respuesta estado validado: ", data);
      if (parseInt(data[0]["existe"]) == 1) {
        window.location.href = "editarOrdenCompra.php?oc=" + id;

        console.log("¡Se encuentra en espera!");
      } else {
        obtenerVer(id);
        console.log(
          "¡Su estado ha cambiado, sólo puede ver la orden de compra!"
        );
      }
    },
  });
}

function obtenerVer(id, isNueva) {
  var idEncrip = $("#inp-" + id).val();
  window.location.href = "verOrdenCompra.php?oc=" + id;
}

function validate_Permissions(pkPantalla) {
  var filtro = "";
  var topButtons = [
    {
      extend: "excelHtml5",
      text: '<span class="d-flex align-items-center"><img src="../../img/icons/ICONO DESCARGAR2_Mesa de trabajo 1.svg" width="20" class="mr-1"> Descargar excel</span>',
      className: "btn-custom--white-dark",
      titleAttr: "Excel",
    },
  ];

  $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
    var estatus = data[7]; // informacion del estado de la cotizacion

    if (filtro == "") {
      return true;
    }

    if (estatus == filtro) {
      return true;
    } else {
      return false;
    }
  });

  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "validar_Permisos", data: pkPantalla },
    dataType: "json",
    success: function (data) {
      _permissions.read = data[0].isRead;
      _permissions.add = data[0].isAdd;
      _permissions.edit = data[0].isEdit;
      _permissions.delete = data[0].isDelete;
      _permissions.export = data[0].isExport;

      //PRODUCTOS
      if (pkPantalla == "22") {
        if (_permissions.add == "1") {
          topButtons = [
            {
              text: '<span class="d-flex align-items-center"><img src="../../../../img/icons/ICONO_AGREGAR_DARK.svg" width="20" class="mr-1"> Añadir registro</span>',
              className: "btn-custom--white-dark",
              action: function () {
                window.location.href = "agregarOrdenCompra.php";
              },
            },
            {
              extend: "excelHtml5",
              text: '<span class="d-flex align-items-center"><img src="../../../../img/icons/ICONO DESCARGAR2_Mesa de trabajo 1.svg" width="20" class="mr-1"> Descargar excel</span>',
              className: "btn-custom--white-dark",
              titleAttr: "Excel",
            },
          ];
        }

        var tableOrdenCompra = $("#tblOrdenesCompra").DataTable({
          language: setFormatDatatables(),
          info: false,
          scrollX: true,
          bSort: false,
          pageLength: 50,
          responsive: true,
          lengthChange: false,
          columnDefs: [
            { orderable: false, targets: 0, visible: false },
            { orderable: false, targets: 4, visible: false },
          ],
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
          ajax: {
            url: "../../php/funciones.php",
            data: {
              clase: "get_data",
              funcion: "get_purchaseOrders",
              data: _permissions.edit,
            },
          },
          columns: [
            { data: "Id" },
            { data: "Referencia" },
            { data: "FechaEmision" },
            { data: "FechaEstimadaEntrega" },
            { data: "FechaEntrega" },
            { data: "Proveedor" },
            { data: "Importe" },
            { data: "EstatusOrden" },
            { data: "Acciones" },
          ],
        });

        new $.fn.dataTable.Buttons(tableOrdenCompra, {
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
              text: '<i class="fas fa-globe"></i> Todos',
              className: " btn-table-custom--blue",
              action: function (e, dt, node, config) {
                filtro = "";
                $("#tblOrdenesCompra").DataTable().draw();
              },
            },
            {
              text: '<i class="fas fa-file-invoice"></i> Aceptada',
              className: " btn-table-custom--blue-lightest",
              action: function (e, dt, node, config) {
                filtro = "Aceptada";
                $("#tblOrdenesCompra").DataTable().draw();
              },
            },
            {
              text: '<i class="far fa-clock"></i> En espera',
              className: " btn-table-custom--gray",
              action: function (e, dt, node, config) {
                filtro = "En espera";
                $("#tblOrdenesCompra").DataTable().draw();
              },
            },
            {
              text: '<i class="far fa-check-circle"></i> Completa',
              className: " btn-table-custom--green",
              action: function (e, dt, node, config) {
                filtro = "Completa";
                $("#tblOrdenesCompra").DataTable().draw();
              },
            },
            {
              text: '<i class="far fa-calendar-check"></i> Aceptada-Demorada',
              className: " btn-table-custom--yellow",
              action: function (e, dt, node, config) {
                filtro = "Aceptada-Demorada";
                $("#tblOrdenesCompra").DataTable().draw();
              },
            },
            {
              text: '<i class="far fa-calendar-times"></i> Vencida',
              className: " btn-table-custom--red",
              action: function (e, dt, node, config) {
                filtro = "Vencida";
                $("#tblOrdenesCompra").DataTable().draw();
              },
            },
            {
              text: '<i class="fas fa-ban"></i> Cancelada',
              className: " btn-table-custom--red",
              action: function (e, dt, node, config) {
                filtro = "Cancelada";
                $("#tblOrdenesCompra").DataTable().draw();
              },
            },
            {
              text: '<i class="far fa-times-circle"></i> Cerrada',
              className: " btn-table-custom--red",
              action: function (e, dt, node, config) {
                filtro = "Cerrada";
                $("#tblOrdenesCompra").DataTable().draw();
              },
            },
            {
              text: '<i class="far fa-calendar-times"></i> Rechazada',
              className: " btn-table-custom--red",
              action: function (e, dt, node, config) {
                filtro = "Rechazada";
                $("#tblOrdenesCompra").DataTable().draw();
              },
            },
          ],
        });

        tableOrdenCompra.buttons(1, null).container().appendTo("#btn-filters");
      }
    },
  });
}

function isExport() {
  if (_permissions.export == "1") {
    return '<img class="readEditPermissions" type="submit" width="50px" id="btnExportPermissions" onclick="exportarPDF()" src="../../../../img/excel-azul.svg" />';
  } else {
    return "";
  }
}
