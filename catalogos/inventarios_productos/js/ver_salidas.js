$(document).ready(function () {
  if (tipoS == 1) {
    $(".data-container .orderPedido-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .sales-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .return-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .orderPedidoGral-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .quote-disabled").css({
      display: "inline-block",
      visibility: "visible",
      opacity: "1",
      animation: "fade 1s",
    });

    loadInfoSalidaCoti(folio);
  } else if (tipoS == 2) {
    $(".data-container .quote-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .orderPedido-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .return-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .orderPedidoGral-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .sales-disabled").css({
      display: "inline-block",
      visibility: "visible",
      opacity: "1",
      animation: "fade 1s",
    });

    loadInfoSalidaVenta(folio);
  } else if (tipoS == 3) {
    $(".data-container .quote-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .sales-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .return-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .orderPedidoGral-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .orderPedido-disabled").css({
      display: "inline-block",
      visibility: "visible",
      opacity: "1",
      animation: "fade 1s",
    });

    loadInfoSalidaTras(folio);
  } else if (tipoS == 4) {
    $(".data-container .quote-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .sales-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .orderPedido-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .orderPedidoGral-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .return-disabled").css({
      display: "inline-block",
      visibility: "visible",
      opacity: "1",
      animation: "fade 1s",
    });

    loadInfoSalidaDevolucion(folio);
  } else if (tipoS == 5) {
    $(".data-container .quote-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .sales-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .orderPedido-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .return-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .orderPedidoGral-disabled").css({
      display: "inline-block",
      visibility: "visible",
      opacity: "1",
      animation: "fade 1s",
    });

    loadInfoSalidaGral(folio);
  }
});

function loadInfoSalidaTras(folio) {
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_DataExitOP", data: folio },
    dataType: "json",
    success: function (data) {
      $("#cmbOrderPedido").val(data[0].folio);
      $("#cmbSucursalOrigen").val(data[0].sucursalO);
      $("#cmbSucursalDestino").val(data[0].sucursalD);
      $("#cmbSurtidorSalida").val(data[0].surtidor);
      $("#txtNoBultosPaquetes").val(data[0].observaciones);

      loadProductosExits(folio);
    },
  });
}

function loadInfoSalidaGral(folio) {
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_DataExitOP", data: folio },
    dataType: "json",
    success: function (data) {
      $("#cmbOrderPedidoGral").val(data[0].folio);
      $("#cmbSucursalOrigenGral").val(data[0].sucursalO);
      $("#cmbDestinoGral").val(data[0].sucursalD);
      $("#cmbSurtidorSalidaGral").val(data[0].surtidor);
      $("#txtNoBultosPaquetesGral").val(data[0].observaciones);

      loadProductosExits(folio);
    },
  });
}

function loadInfoSalidaCoti(folio) {
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_DataExitCoti", data: folio },
    dataType: "json",
    success: function (data) {
      $("#cmbOrderPedidoQuote").val(data[0].folio);
      $("#cmbSucursalOrigenQuote").val(data[0].sucursalO);
      $("#cmbClienteQuote").val(data[0].cliente);
      $("#cmbSurtidorSalidaQuote").val(data[0].surtidor);
      $("#txtNoBultosPaquetesQuote").val(data[0].observaciones);

      loadProductosExits(folio);
    },
  });
}

function loadInfoSalidaVenta(folio) {
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_DataExitVenta", data: folio },
    dataType: "json",
    success: function (data) {
      $("#cmbOrderPedidoSales").val(data[0].folio);
      $("#cmbSucursalOrigenSales").val(data[0].sucursalO);
      $("#cmbClienteSales").val(data[0].cliente);
      $("#cmbSurtidorSalidaSales").val(data[0].surtidor);
      $("#txtNoBultosPaquetesSales").val(data[0].observaciones);

      loadProductosExits(folio);
    },
  });
}

function loadInfoSalidaDevolucion(folio) {
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_DataExitDevolucion", data: folio },
    dataType: "json",
    success: function (data) {
      $("#cmbOrderPedidoReturn").val(data[0].folio);
      $("#cmbSucursalOrigenReturn").val(data[0].sucursalO);
      $("#cmbProveedorReturn").val(data[0].proveedor);
      $("#cmbSurtidorSalidaReturn").val(data[0].surtidor);
      $("#txtNoBultosPaquetesReturn").val(data[0].observaciones);

      loadProductosExits(folio);
    },
  });
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

function loadProductosExits(folioSalida) {
  $("#tblSalidaOrdenPedido").dataTable({
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
    buttons: [],
    ajax: {
      url: "../../php/funciones.php",
      data: {
        clase: "get_data",
        funcion: "get_productoSalidaOPTableEdit",
        data: folioSalida,
      },
    },
    columns: [
      { data: "Id" },
      { data: "Producto" },
      { data: "Descripcion" },
      { data: "CantidadPedida" },
      { data: "CantidadSurtida" },
      { data: "CantidadRestante" },
      { data: "Existencias" },
      { data: "Lote" },
      { data: "Serie" },
      { data: "um" },
      { data: "cb" },
      { data: "Caducidad" },
    ]
  });

  obtenerTotal(folioSalida);
}

function obtenerTotal(folioSalida) {
  //Obtener total
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_totalSalidaOPVer",
      datos: folioSalida,
    },
    dataType: "json",
    success: function (respuesta) {
      $("#Total").html(respuesta[0].Total);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function dosDecimales(n) {
  return Number.parseFloat(n)
    .toFixed(2)
    .replace(/\d(?=(\d{3})+\.)/g, "$&,");
}
