var _global = {
  IDOP: "",
  InputObservaciones: "",
};

//función para deshabilitar los botones de eliminar y guardar de la salida
function valid_disabledButtons(btn1, btn2){
  if(parseInt(entradaFlag) == 1){
    $("#"+btn1).prop('disabled', true);    
    $("#"+btn2).prop('disabled', true); 
  }
}

$(document).ready(function () {
    console.log("tipoS: ",tipoS);
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
    $(".data-container .invoice-disabled").css({
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


    //deshabilita botones de eliminar y guardar salida
    valid_disabledButtons('btnEditarSalidaCoti', 'btnEliminarSalidaCoti');
    
    loadInfoSalidaCoti(folio);

    setTimeout(function () {
      deleteSalidaOPTemp();
    }, 100);

    setTimeout(function () {
      saveSalidaOPTemp(folio);
    }, 500);
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
    $(".data-container .invoice-disabled").css({
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

    //deshabilita botones de eliminar y guardar salida
    valid_disabledButtons('btnEditarSalidaVenta', 'btnEliminarSalidaVenta');

    loadInfoSalidaVenta(folio);

    setTimeout(function () {
      deleteSalidaOPTemp();
    }, 100);

    setTimeout(function () {
      saveSalidaOPTemp(folio);
    }, 500);
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
    $(".data-container .invoice-disabled").css({
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

    //deshabilita botones de eliminar y guardar salida
    valid_disabledButtons('btnEditarSalidaTras', 'btnEliminarSalidaTras');

    loadInfoSalidaTras(folio);

    setTimeout(function () {
      deleteSalidaOPTemp();
    }, 100);

    setTimeout(function () {
      saveSalidaOPTemp(folio);
    }, 500);
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
    $(".data-container .invoice-disabled").css({
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

    setTimeout(function () {
      deleteSalidaDevolucionTemp();
    }, 100);

    setTimeout(function () {
      saveSalidaDevolucionTemp(folio);
    }, 500);
  } else if (tipoS == 5) {
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
    $(".data-container .quote-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .invoice-disabled").css({
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

    //deshabilita botones de eliminar y guardar salida
    valid_disabledButtons('btnEditarSalidaGral', 'btnEliminarSalidaGral');

    loadInfoSalidaGral(folio);

    setTimeout(function () {
      deleteSalidaOPTemp();
    }, 100);

    setTimeout(function () {
      saveSalidaOPTemp(folio);
    }, 500);
  } else if(tipoS == 6){
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
    $(".data-container .quote-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .orderPedidoGral-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .invoice-disabled").css({
      display: "inline-block",
      visibility: "visible",
      opacity: "1",
      animation: "fade 1s",
    });

    loadInfoSalidaInvoice(folio);

    setTimeout(function () {
      deleteSalidaOPTemp();
    }, 100);

    setTimeout(function () {
      saveSalidaOPTemp(folio);
    }, 500);
  }

  if(parseInt($("#txtIsMovimiento").val()) === 1 || parseInt($("#txtIsFacturado").val()) === 1 || parseInt($("#txtIsFacturadoV").val()) === 1){
    $("#btnEditarSalidaCoti").attr("disabled", true);
    $("#btnEliminarSalidaCoti").prop("disabled", true);
    $("#btnEditarSalidaVenta").attr("disabled", true);
    $("#btnEliminarSalidaVenta").prop("disabled", true);
    $("#btnEditarSalidaTras").attr("disabled", true);
    $("#btnEliminarSalidaTras").prop("disabled", true);
    $("#btnEditarSalidaGral").prop("disabled", true);
    $("#btnEliminarSalidaGral").attr("disabled", true);
  }
  console.log($("#txtIsMovimiento").val());
  console.log($("#txtIsFacturado").val());
  console.log($("#txtIsFacturadoV").val());
 
});

function loadInfoSalidaTras(folio) {
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_DataExitOP", data: folio },
    dataType: "json",
    success: function (data) {
      $("#cmbOrderPedido").val(data[0].folio);
      _global.IDOP = data[0].folio;
      $("#cmbSucursalOrigen").val(data[0].sucursalO);
      $("#cmbSucursalDestino").val(data[0].sucursalD);
      $("#cmbSurtidorSalida").val(data[0].surtidor);
      $("#txtNoBultosPaquetes").val(data[0].observaciones);
      _global.InputObservaciones = "#txtNoBultosPaquetes";
      //loadProductosExits(folio);
    },
  });
}

function loadInfoSalidaGral(folio) {
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_DataExitOP", data: folio },
    dataType: "json",
    success: function (data) {
        console.log();
      $("#cmbOrderPedidoGral").val(data[0].folio);
      _global.IDOP = data[0].folio;
      $("#cmbSucursalOrigenGral").val(data[0].sucursalO);
      $("#cmbDestinoGral").val(data[0].sucursalD);
      $("#cmbSurtidorSalidaGral").val(data[0].surtidor);
      $("#txtNoBultosPaquetesGral").val(data[0].observaciones);
      _global.InputObservaciones = "#txtNoBultosPaquetesGral";
      //loadProductosExits(folio);
    },
  });
}

function loadInfoSalidaInvoice(folio) {
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_DataExitOP", data: folio },
    dataType: "json",
    success: function (data) {
        console.log(data);
      $("#cmbInvoice").val(data[0].folio);
      _global.IDOP = data[0].folio;
      $("#cmbSucursalInvoice").val(data[0].sucursalO);
      $("#cmbDestinoInvoice").val(data[0].sucursalD);
      $("#cmbSurtidorInvoice").val(data[0].surtidor);
      $("#txtNoBultosPaquetesInvoice").val(data[0].observaciones);
      _global.InputObservaciones = "#txtNoBultosPaquetesInvoice";
      //loadProductosExits(folio);
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
      _global.IDOP = data[0].folio;
      $("#cmbSucursalOrigenQuote").val(data[0].sucursalO);
      $("#cmbClienteQuote").val(data[0].cliente);
      $("#cmbSurtidorSalidaQuote").val(data[0].surtidor);
      $("#txtNoBultosPaquetesQuote").val(data[0].observaciones);
      _global.InputObservaciones = "#txtNoBultosPaquetesQuote";
      //loadProductosExits(folio);
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
      _global.IDOP = data[0].folio;
      $("#cmbSucursalOrigenSales").val(data[0].sucursalO);
      $("#cmbClienteSales").val(data[0].cliente);
      $("#cmbSurtidorSalidaSales").val(data[0].surtidor);
      $("#txtNoBultosPaquetesSales").val(data[0].observaciones);
      _global.InputObservaciones = "#txtNoBultosPaquetesSales";
      //loadProductosExits(folio);
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
      _global.IDOP = data[0].folio;
      $("#cmbSucursalOrigenReturn").val(data[0].sucursalO);
      $("#cmbProveedorReturn").val(data[0].proveedor);
      $("#cmbSurtidorSalidaReturn").val(data[0].surtidor);
      $("#txtNoBultosPaquetesReturn").val(data[0].observaciones);
      _global.InputObservaciones = "#txtNoBultosPaquetesReturn";
    },
  });
}

function deleteSalidaOPTemp() {
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "delete_data",
      funcion: "delete_datosSalidaOPTemp",
    },
    dataType: "json",
    success: function (respuesta) {
      if (respuesta[0].status) {
      } else {
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function deleteSalidaDevolucionTemp() {
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "delete_data",
      funcion: "delete_datosSalidaDevolucionTemp",
    },
    dataType: "json",
    success: function (respuesta) {
      if (respuesta[0].status) {
      } else {
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function saveSalidaOPTemp(folioSalida) {
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "save_data",
      funcion: "save_exitOP_TableEdit",
      data: folioSalida,
    },
    dataType: "json",
    success: function (respuesta) {
      if (respuesta[0].status) {
        loadProductosOrderPedido(folioSalida);
      } else {
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function saveSalidaDevolucionTemp(folioSalida) {
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "save_data",
      funcion: "save_exitDevolucion_TableEdit",
      data: folioSalida,
    },
    dataType: "json",
    success: function (respuesta) {
      if (respuesta[0].status) {
        loadProductosDevolucion(folioSalida);
      } else {
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function loadProductosOrderPedido(folioSalida) {
  $("#tblSalidaOrdenPedido").DataTable().destroy();
  $("#tblSalidaOrdenPedido").dataTable({
    language: setFormatDatatables(),
    dom: "Bfrtip",
    searching: false,
    buttons: [],
    scrollX: true,
    lengthChange: false,
    info: false,
    ajax: {
      url: "../../php/funciones.php",
      data: {
        clase: "get_data",
        funcion: "get_productoSalidaOPTempTableEdicion",
        data: folioSalida,
      },
    },
    //"pageLength": 20,
    paging: false,
    order: [1, "asc"],
    columns: [
      { data: "Id" },
      { data: "Producto" },
      { data: "Descripcion" },
      { data: "CantidadPedida" },
      { data: "CantidadSurtida" },
      { data: "CantidadRestante" },
      { data: "Existencias" },
      { data: "CantidadSalida" },
      { data: "Lote" },
      /*{ data: "Serie" },*/
      { data: "um" },
      { data: "cb" },
      { data: "Caducidad" },
      { data: "Acciones" },
    ],
    columnDefs: [
      { orderable: false, targets: 0, visible: false },
      { orderable: false, targets: 1, width: "300px" },
      { orderable: false, targets: 2, width: "300px" },
    ],
  });

  obtenerTotal(folioSalida);
  $("#btnEditarSalidaTras").unbind("click", false);
  $("#btnEditarSalidaGral").unbind("click", false);
  $("#btnEditarSalidaCoti").unbind("click", false);
  $("#btnEditarSalidaVenta").unbind("click", false);
}

function loadProductosDevolucion(folioSalida) {
  $("#tblSalidaDevolucion").DataTable().destroy();
  $("#tblSalidaDevolucion").dataTable({
    language: setFormatDatatables(),
    dom: "Bfrtip",
    searching: false,
    buttons: [],
    scrollX: true,
    lengthChange: false,
    info: false,
    ajax: {
      url: "../../php/funciones.php",
      data: {
        clase: "get_data",
        funcion: "get_productoSalidaDevolucionTempTableEdicion",
        data: folioSalida,
      },
    },
    //"pageLength": 20,
    paging: false,
    order: [1, "asc"],
    columns: [
      { data: "Id" },
      { data: "Producto" },
      { data: "Descripcion" },
      { data: "CantidadEntrada" },
      { data: "CantidadDevuelta" },
      { data: "Existencias" },
      { data: "CantidadSalida" },
      { data: "Lote" },
      //{ data: "Serie" },
      { data: "Caducidad" },
      { data: "Acciones" },
    ],
    columnDefs: [
      { orderable: false, targets: 0, visible: false },
      { orderable: false, targets: 1, width: "20%" },
      { orderable: false, targets: 2, width: "20%" },
      { orderable: false, targets: 3, width: "10%" },
      { orderable: false, targets: 4, width: "10%" },
      { orderable: false, targets: 5, width: "10%" },
      { orderable: false, targets: 6, width: "10%" },
      { orderable: false, targets: 7, width: "10%" },
      { orderable: false, targets: 8, width: "10%" },
    ],
  });

  setTimeout(function () {
    obtenerTotal(folioSalida);
  }, 500);
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
      sNext: "<img src='../../../../img/icons/pagination.svg' width='20px'/>",
      sPrevious:
        "<img src='../../../../img/icons/pagination.svg' width='20px' style='transform: scaleX(-1)'/>",
    },
  };
  return idioma_espanol;
}

function obtenerTotal(folioSalida) {
  //Obtener total
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_totalSalidaOPEdicion",
      datos: folioSalida,
    },
    dataType: "json",
    success: function (respuesta) {
      $("#Total").html(respuesta[0].salida);
      $("#TotalDevolucion").html(respuesta[0].salida);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function configurarProducto(
  pkProducto,
  producto,
  cantidadPedida,
  descripcion,
  faltante
) {
  var html = ``;

  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_DataDatosSalidaCantTempEdicion",
      data: pkProducto,
      data2: folio,
    },
    dataType: "json",
    success: function (data) {
      for (var dato of data) {
        var lote = dato.Lote;
        var serie = dato.Serie;
        var SerieLote = "";
        if (lote == "") {
          SerieLote = serie;
        } else {
          SerieLote = lote;
        }

        html += `<div class="row">
                  <div class="col-md-1">
                    <!--<input class="form-check-input" type="checkbox" id="cbxRepModal-${SerieLote}" name="cbxRepModal-${SerieLote}" onclick="activarLoteSerie()">-->
                  </div>
                  
                  <div class="col-md-3">
                    <label class="form-check-label">${SerieLote}</label>
                  </div>

                  <div class="col-md-2">
                  ${dato.existencia} 
                  </div>
                  
                  <div class="col-md-3">
                    <input class="form-control textTable border-0 cnt-lote-serie" type="number" value="${dato.salida}" onchange="validarCantidadModal('${SerieLote}')" id="cantidadModal-${SerieLote}">
                    <input type="hidden" id="cantidad-modal-old-${SerieLote}" value="${dato.salida}">
                  </div>
                  
                  <div class="col-md-3">
                    <input type="hidden" value="${dato.existencia} " id="cantidadHisModal-${SerieLote}"> 
                    <div class="invalid-feedback" id="invalid-cantidadModal-${SerieLote}">La cantidad es inválida.</div> 
                  </div>
                </div>
              `;
      }
      $("#idProducto").val(pkProducto);
      $("#cantidad-faltante").val(faltante);
      $("#configProducto").html(producto);
      $("#configProductoCant").html(cantidadPedida);
      $("#configProductoCantFalt").html(faltante);
      $("#configProductoCantFaltInput").val(faltante);
      $("#descripcionProducto").html(descripcion);

      $("#listProducto").html(html);

      $("#configurarProducto").modal("show");
    },
  });
}

function disableButtonsAdd() {
  console.log("disabled");
  $("#btnEditarSalidaTras").bind("click", false);
  $("#btnEditarSalidaGral").bind("click", false);
  $("#btnEditarSalidaCoti").bind("click", false);
  $("#btnEditarSalidaVenta").bind("click", false);
}

function validarCantidad(serieLote, pkProducto) {
  escribiendoValidarCantidad(serieLote, pkProducto);
}

var controladorTiempo = "";
function escribiendoValidarCantidad(serieLote, pkProducto) {
  clearTimeout(controladorTiempo);
  //Llamar la busqueda cuando el usuario deje de escribir
  controladorTiempo = setTimeout(function () {
    var cantidad = $("#cantidad-" + serieLote + pkProducto).val();

    if (cantidad != 0) {
      $.ajax({
        url: "../../php/funciones.php",
        data: {
          clase: "get_data",
          funcion: "validar_salida_cantidadModalTempEdicion",
          data: serieLote,
          data2: cantidad,
          data3: folio,
          data4: pkProducto,
        },
        dataType: "json",
        success: function (data) {
          if (parseInt(data[0]["existe"]) == 2) {
            $("#cantidad-" + serieLote + pkProducto).addClass("is-invalid");
            $("#invalid-cantidad-" + serieLote + pkProducto).css(
              "display",
              "block"
            );
            $("#invalid-cantidad-" + serieLote + pkProducto).text(
              "La suma de las cantidades es mayor a la cantidad faltante."
            );
          } else if (parseInt(data[0]["existe"]) == 1) {
            $("#cantidad-" + serieLote + pkProducto).addClass("is-invalid");
            $("#invalid-cantidad-" + serieLote + pkProducto).css(
              "display",
              "block"
            );
            $("#invalid-cantidad-" + serieLote + pkProducto).text(
              "La cantidad es mayor a la existente."
            );
          } else {
            editarCantidad(serieLote, cantidad, folio, pkProducto);

            $("#cantidad-" + serieLote + pkProducto).removeClass("is-invalid");
            $("#invalid-cantidad-" + serieLote + pkProducto).css(
              "display",
              "none"
            );
            $("#invalid-cantidad-" + serieLote + pkProducto).text(
              "La cantidad es válida."
            );
          }
        },
      });
    } else {
      $("#cantidad-" + serieLote + pkProducto).addClass("is-invalid");
      $("#invalid-cantidad-" + serieLote + pkProducto).css("display", "block");
      $("#invalid-cantidad-" + serieLote + pkProducto).text(
        "La cantidad debe de ser mayor a 0."
      );
    }
  }, 150);
}

function validarCantidadModal(serieLote) {
  escribiendoValidarCantidadModal(serieLote);
}

var controladorTiempo = "";
function escribiendoValidarCantidadModal(serieLote) {
  clearTimeout(controladorTiempo);
  var inputCantidad = $(".cnt-lote-serie");
  var suma = 0;
  var cantidadFalt = $("#configProductoCantFaltInput").val() * 1;
  for (let index = 0; index < inputCantidad.length; index++) {
    suma += inputCantidad[index].value * 1;
  }
  console.log({ suma, cantidadFalt });
  if (suma > cantidadFalt) {
    $("#invalid-cantidad-mayor").addClass("d-block");
    $("#cantidadModal-" + serieLote).val(
      $("#cantidad-modal-old-" + serieLote).val()
    );
    return;
  }
  $("#invalid-cantidad-mayor").removeClass("d-block");
  $("#cantidad-modal-old-" + serieLote).val(
    $("#cantidadModal-" + serieLote).val()
  );
  var cantidad = $("#cantidadModal-" + serieLote).val();
  var pkProducto = $("#idProducto").val();

  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "validar_salida_cantidadModalTempEdicion",
      data: serieLote,
      data2: cantidad,
      data3: folio,
      data4: pkProducto,
    },
    dataType: "json",
    success: function (data) {
      if (parseInt(data[0]["existe"]) == 2) {
        //$("#cantidadModal-"+serieLote+pkProducto).val(0);

        $("#cantidadModal-" + serieLote + pkProducto).addClass("is-invalid");
        $("#invalid-cantidadModal-" + serieLote + pkProducto).css(
          "display",
          "block"
        );
        $("#invalid-cantidadModal-" + serieLote + pkProducto).text(
          "La suma de las cantidades es mayor a la cantidad faltante."
        );
      } else if (parseInt(data[0]["existe"]) == 1) {
        //$("#cantidadModal-"+serieLote+pkProducto).val(0);

        $("#cantidadModal-" + serieLote + pkProducto).addClass("is-invalid");
        $("#invalid-cantidadModal-" + serieLote + pkProducto).css(
          "display",
          "block"
        );
        $("#invalid-cantidadModal-" + serieLote + pkProducto).text(
          "La cantidad es mayor a la existente."
        );
      } else {
        editarCantidad(serieLote, cantidad, folio, pkProducto);

        $("#cantidadModal-" + serieLote + pkProducto).removeClass("is-invalid");
        $("#invalid-cantidadModal-" + serieLote + pkProducto).css(
          "display",
          "none"
        );
        $("#invalid-cantidadModal-" + serieLote + pkProducto).text(
          "La cantidad es válida."
        );
      }
    },
  });
}

function validarCantidadDevolucion(salidaTemp, pkProducto, IdCuentaPagar) {
  var cantidad = $("#cantidad-" + salidaTemp).val();

  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "validar_salida_cantidadDevolucionTempEdicion",
      data: salidaTemp,
      data2: cantidad,
      data3: IdCuentaPagar,
      data4: pkProducto,
    },
    dataType: "json",
    success: function (data) {
      if (parseInt(data[0]["existe"]) == 2) {
        $("#cantidad-" + salidaTemp).addClass("is-invalid");
        $("#invalid-cantidad-" + salidaTemp).css("display", "block");
        $("#invalid-cantidad-" + salidaTemp).text(
          "La cantidad es mayor a la cantidad que entró."
        );
      } else if (parseInt(data[0]["existe"]) == 1) {
        $("#cantidad-" + salidaTemp).addClass("is-invalid");
        $("#invalid-cantidad-" + salidaTemp).css("display", "block");
        $("#invalid-cantidad-" + salidaTemp).text(
          "La cantidad es mayor a la existente."
        );
      } else {
        editarCantidadDevolucion(
          salidaTemp,
          cantidad,
          IdCuentaPagar,
          pkProducto
        );

        $("#cantidad-" + salidaTemp).removeClass("is-invalid");
        $("#invalid-cantidad-" + salidaTemp).css("display", "none");
        $("#invalid-cantidad-" + salidaTemp).text("La cantidad es válida.");
      }
    },
  });
}

function editarCantidad(serieLote, cantidad, folio, pkProducto) {
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "edit_data",
      funcion: "edit_salida_cantidad_modal_tempEdicion",
      data: serieLote,
      data2: cantidad,
      data3: folio,
      data4: pkProducto,
    },
    dataType: "json",
    success: function (respuesta) {
      if (respuesta[0].status) {
        loadProductosOrderPedido(folio);
      } else {
        $("#cantidadModal-" + serieLote).val(0);
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function editarCantidadDevolucion(
  salidaTemp,
  cantidad,
  IdCuentaPagar,
  pkProducto
) {
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "edit_data",
      funcion: "edit_salida_cantidad_devolucion_temp",
      data: salidaTemp,
      data2: cantidad,
      data3: IdCuentaPagar,
      data4: pkProducto,
    },
    dataType: "json",
    success: function (respuesta) {
      if (respuesta[0].status) {
        //loadProductosDevolucion(folio);
      } else {
        $("#cantidadModal-" + serieLote).val(0);
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function openModalDelete(idSalidaTemp, idProducto) {
  $("#exitTempIDD").val(idSalidaTemp);
  $("#ProductoTempIDD").val(idProducto);
  $("#eliminar_ProductoSalida").modal("show");
}

function eliminarSalidaAll() {
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "delete_data",
      funcion: "delete_datosSalida_All",
      data: _global.IDOP,
    },
    dataType: "json",
    success: function (respuesta) {
      if (respuesta[0].status) {
        var observaciones = $(`${_global.InputObservaciones}`).val();

        $.ajax({
          url: "../../php/funciones.php",
          data: {
            clase: "edit_data",
            funcion: "edit_datosSalida_OPEdicion",
            data: _global.IDOP,
            data2: observaciones,
          },
          dataType: "json",
          success: function (respuesta) {
            if(respuesta[0].status == 0){
              Lobibox.notify("warning", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../../../img/timdesk/warning_circle.svg",
                msg: "¡No se puede editar una salida con entradas!",
                sound: "../../../../../sounds/sound4",
              });
            }else{
              if (respuesta[0].status) {
                Lobibox.notify("success", {
                  size: "mini",
                  rounded: true,
                  delay: 3000,
                  delayIndicator: false,
                  position: "center top",
                  icon: true,
                  img: "../../../../img/timdesk/checkmark.svg",
                  msg: "¡Se eliminó exitosamente la salida.!",
                  sound: "../../../../../sounds/sound4",
                });

                setTimeout(function () {
                  window.location.href = "../salidas_productos";
                }, 2000);
              }
            }
          },
          error: function (error) {
            Lobibox.notify("error", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../../img/timdesk/notificacion_error.svg",
              msg: "¡Algo salio mal :(!",
              sound: "../../../../../sounds/sound4",
            });
          },
        });
      } else {
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/notificacion_error.svg",
          msg: "¡Algo salio mal :(!",
          sound: "../../../../../sounds/sound4",
        });
      }
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

$(document).on("click", "#btnEditarSalidaTras", function () {
  if(parseInt(entradaFlag) == 1){
    Lobibox.notify("warning", {
      size: "mini",
      rounded: true,
      delay: 3000,
      delayIndicator: false,
      position: "center top",
      icon: true,
      img: "../../../../img/timdesk/warning_circle.svg",
      msg: "¡No se puede editar una salida con entradas!",
      sound: "../../../../../sounds/sound4",
    });
  }else{
    var table = $("#tblSalidaOrdenPedido").DataTable();
    var invalidDivs = document.querySelectorAll(".invalid-feedback");
    var isSomethingInvalid = false;
    invalidDivs.forEach((invalidDiv) => {
      console.log(invalidDiv.style.display);
      if (invalidDiv.style.display === "block") {
        isSomethingInvalid = true;
        return;
      } else {
        isSomethingInvalid = false;
      }
    });
    if (!isSomethingInvalid) {
      if (table.data().count()) {
        var observaciones = $("#txtNoBultosPaquetes").val();
  
        $.ajax({
          url: "../../php/funciones.php",
          data: {
            clase: "edit_data",
            funcion: "edit_datosSalida_OPEdicion",
            data: folio,
            data2: observaciones,
          },
          dataType: "json",
          success: function (respuesta) {
            if(respuesta[0].status == 0){
              Lobibox.notify("warning", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../../../img/timdesk/warning_circle.svg",
                msg: "¡No se puede editar una salida con entradas!",
                sound: "../../../../../sounds/sound4",
              });
            }else{
              if (respuesta[0].status) {
                imprimirPDFSalida(respuesta[0].folio, 0);
              }
            }
          },
          error: function (error) {
            console.log(error);
          },
        });
      } else {
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/notificacion_error.svg",
          msg: "¡No hay productos agregados!",
          sound: "../../../../../sounds/sound4",
        });
      }
    }
  }
  
  //imprimirPDFSalida('5-1','29');
});

$(document).on("click", "#btnEditarSalidaGral", function () {
  if(parseInt(entradaFlag) == 1){
    Lobibox.notify("warning", {
      size: "mini",
      rounded: true,
      delay: 3000,
      delayIndicator: false,
      position: "center top",
      icon: true,
      img: "../../../../img/timdesk/warning_circle.svg",
      msg: "¡No se puede editar una salida con entradas!",
      sound: "../../../../../sounds/sound4",
    });
  }else{
    var table = $("#tblSalidaOrdenPedido").DataTable();
    var invalidDivs = document.querySelectorAll(".invalid-feedback");
    var isSomethingInvalid = false;
    invalidDivs.forEach((invalidDiv) => {
      console.log(invalidDiv.style.display);
      if (invalidDiv.style.display === "block") {
        isSomethingInvalid = true;
        return;
      } else {
        isSomethingInvalid = false;
      }
    });
    if (!isSomethingInvalid) {
      if (table.data().count()) {
        var observaciones = $("#txtNoBultosPaquetesGral").val();
        $.ajax({
          url: "../../php/funciones.php",
          data: {
            clase: "edit_data",
            funcion: "edit_datosSalida_OPEdicion",
            data: folio,
            data2: observaciones,
          },
          dataType: "json",
          success: function (respuesta) {
          if(respuesta[0].status == 0){
            Lobibox.notify("warning", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../../img/timdesk/warning_circle.svg",
              msg: "¡No se puede editar una salida con entradas!",
              sound: "../../../../../sounds/sound4",
            });
          }else{  
            if (respuesta[0].status) {
              imprimirPDFSalidaGral(respuesta[0].folio, 0);
            }
          }
          },
          error: function (error) {
            console.log(error);
          },
        });
      } else {
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/notificacion_error.svg",
          msg: "¡No hay productos agregados!",
          sound: "../../../../../sounds/sound4",
        });
      }
    }
  }

  //imprimirPDFSalidaGral('5-1','29');
});

$(document).on("click", "#btnEditarSalidaCoti", function () {
  if(parseInt(entradaFlag) == 1){
    Lobibox.notify("warning", {
      size: "mini",
      rounded: true,
      delay: 3000,
      delayIndicator: false,
      position: "center top",
      icon: true,
      img: "../../../../img/timdesk/warning_circle.svg",
      msg: "¡No se puede editar una salida con entradas!",
      sound: "../../../../../sounds/sound4",
    });
  }else{
    var table = $("#tblSalidaOrdenPedido").DataTable();
    var invalidDivs = document.querySelectorAll(".invalid-feedback");
    var isSomethingInvalid = false;
    invalidDivs.forEach((invalidDiv) => {
      console.log(invalidDiv.style.display);
      if (invalidDiv.style.display === "block") {
        isSomethingInvalid = true;
        return;
      } else {
        isSomethingInvalid = false;
      }
    });
    if (!isSomethingInvalid) {
      if (table.data().count()) {
        var observaciones = $("#txtNoBultosPaquetesQuote").val();
  
        $.ajax({
          url: "../../php/funciones.php",
          data: {
            clase: "edit_data",
            funcion: "edit_datosSalida_OPEdicion",
            data: folio,
            data2: observaciones,
          },
          dataType: "json",
          success: function (respuesta) {
            if(respuesta[0].status == 0){
              Lobibox.notify("warning", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../../../img/timdesk/warning_circle.svg",
                msg: "¡No se puede editar una salida con entradas!",
                sound: "../../../../../sounds/sound4",
              });
            }else{
              if (respuesta[0].status) {
                imprimirPDFSalidaCoti(respuesta[0].folio, 0);
              }
            }
          },
          error: function (error) {
            console.log(error);
          },
        });
      } else {
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/notificacion_error.svg",
          msg: "¡No hay productos agregados!",
          sound: "../../../../../sounds/sound4",
        });
      }
    }
  }

  //imprimirPDFSalidaCoti('5-1','29');
});

$(document).on("click", "#btnEditarSalidaVenta", function () {
  if(parseInt(entradaFlag) == 1){
    Lobibox.notify("warning", {
      size: "mini",
      rounded: true,
      delay: 3000,
      delayIndicator: false,
      position: "center top",
      icon: true,
      img: "../../../../img/timdesk/warning_circle.svg",
      msg: "¡No se puede editar una salida con entradas!",
      sound: "../../../../../sounds/sound4",
    });
  }else{
    var table = $("#tblSalidaOrdenPedido").DataTable();
    var invalidDivs = document.querySelectorAll(".invalid-feedback");
    var isSomethingInvalid = false;
    invalidDivs.forEach((invalidDiv) => {
      console.log(invalidDiv.style.display);
      if (invalidDiv.style.display === "block") {
        isSomethingInvalid = true;
        return;
      } else {
        isSomethingInvalid = false;
      }
    });
    if (!isSomethingInvalid) {
      if (table.data().count()) {
        var observaciones = $("#txtNoBultosPaquetesSales").val();
  
        $.ajax({
          url: "../../php/funciones.php",
          data: {
            clase: "edit_data",
            funcion: "edit_datosSalida_OPEdicion",
            data: folio,
            data2: observaciones,
          },
          dataType: "json",
          success: function (respuesta) {
            if(respuesta[0].status == 0){
              Lobibox.notify("warning", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../../../img/timdesk/warning_circle.svg",
                msg: "¡No se puede editar una salida con entradas!",
                sound: "../../../../../sounds/sound4",
              });
            }else{
              if (respuesta[0].status) {
                imprimirPDFSalidaVenta(respuesta[0].folio, 0);
              }
            }
          },
          error: function (error) {
            console.log(error);
          },
        });
      } else {
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/notificacion_error.svg",
          msg: "¡No hay productos agregados!",
          sound: "../../../../../sounds/sound4",
        });
      }
    }
  }
  
  //imprimirPDFSalidaVenta('5-1','29');
});

$(document).on("click", "#btnEditarSalidaDevolucion", function () {
  var observaciones = $("#txtNoBultosPaquetesReturn").val();

  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "edit_data",
      funcion: "edit_datosSalida_DevolucionEdicion",
      data: folio,
      data2: observaciones,
    },
    dataType: "json",
    success: function (respuesta) {
      if (respuesta[0].status) {
        imprimirPDFSalidaDevolucion(folio);
      }
    },
    error: function (error) {
      console.log(error);
    },
  });

  //imprimirPDFSalidaDevolucion('DV000002-1');
});

function imprimirPDFSalida(folio, ordenPedido) {
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "validar_noSalidas",
      data: folio,
    },
    dataType: "json",
    success: function (data) {
      if (parseInt(data[0]["existe"]) == 1) {
        Lobibox.notify("success", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/checkmark.svg",
          msg: "¡Se registró exitosamente la salida.!",
          sound: "../../../../../sounds/sound4",
        });
        setTimeout(function () {
          window.location.href =
            "functions/descargar_Salida.php?folio=" +
            folio +
            "&orden=" +
            ordenPedido;
        }, 500);

        setTimeout(function () {
          window.location.href = "../salidas_productos";
        }, 2000);
      } else {
        Lobibox.notify("success", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/checkmark.svg",
          msg: "¡Se eliminó exitosamente la salida.!",
          sound: "../../../../../sounds/sound4",
        });

        setTimeout(function () {
          window.location.href = "../salidas_productos";
        }, 2000);
      }
    },
  });
}

function imprimirPDFSalidaGral(folio, ordenPedido) {
  Lobibox.notify("success", {
    size: "mini",
    rounded: true,
    delay: 3000,
    delayIndicator: false,
    position: "center top",
    icon: true,
    img: "../../../../img/timdesk/checkmark.svg",
    msg: "¡Se registró exitosamente la salida.!",
    sound: "../../../../../sounds/sound4",
  });

  setTimeout(function () {
    window.location.href =
      "functions/descargar_SalidaGral.php?folio=" +
      folio +
      "&orden=" +
      ordenPedido;
  }, 500);

  setTimeout(function () {
    window.location.href = "../salidas_productos";
  }, 2000);
}

function imprimirPDFSalidaCoti(folio, ordenPedido) {
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "validar_noSalidas",
      data: folio,
    },
    dataType: "json",
    success: function (data) {
      if (parseInt(data[0]["existe"]) == 1) {
        Lobibox.notify("success", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/checkmark.svg",
          msg: "¡Se registró exitosamente la salida.!",
          sound: "../../../../../sounds/sound4",
        });
        setTimeout(function () {
          window.location.href =
            "functions/descargar_SalidaCoti.php?folio=" +
            folio +
            "&orden=" +
            ordenPedido;
        }, 500);

        setTimeout(function () {
          window.location.href = "../salidas_productos";
        }, 2000);
      } else {
        Lobibox.notify("success", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/checkmark.svg",
          msg: "¡Se eliminó exitosamente la salida.!",
          sound: "../../../../../sounds/sound4",
        });

        setTimeout(function () {
          window.location.href = "../salidas_productos";
        }, 2000);
      }
    },
  });
}

function imprimirPDFSalidaVenta(folio, ordenPedido) {
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "validar_noSalidas",
      data: folio,
    },
    dataType: "json",
    success: function (data) {
      if (parseInt(data[0]["existe"]) == 1) {
        Lobibox.notify("success", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/checkmark.svg",
          msg: "¡Se registró exitosamente la salida.!",
          sound: "../../../../../sounds/sound4",
        });
        setTimeout(function () {
          window.location.href =
            "functions/descargar_SalidaVenta.php?folio=" +
            folio +
            "&orden=" +
            ordenPedido;
        }, 500);

        setTimeout(function () {
          window.location.href = "../salidas_productos";
        }, 2000);
      } else {
        Lobibox.notify("success", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/checkmark.svg",
          msg: "¡Se eliminó exitosamente la salida.!",
          sound: "../../../../../sounds/sound4",
        });

        setTimeout(function () {
          window.location.href = "../salidas_productos";
        }, 2000);
      }
    },
  });
}

function imprimirPDFSalidaDevolucion(folio) {
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "validar_noSalidas",
      data: folio,
    },
    dataType: "json",
    success: function (data) {
      if (parseInt(data[0]["existe"]) == 1) {
        Lobibox.notify("success", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/checkmark.svg",
          msg: "¡Se registró exitosamente la salida.!",
          sound: "../../../../../sounds/sound4",
        });
        setTimeout(function () {
          window.location.href =
            "functions/descargar_SalidaDevolucion.php?folio=" +
            folio +
            "&cuenta=0";
        }, 500);

        setTimeout(function () {
          window.location.href = "../salidas_productos";
        }, 2000);
      } else {
        Lobibox.notify("success", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/checkmark.svg",
          msg: "¡Se eliminó exitosamente la salida.!",
          sound: "../../../../../sounds/sound4",
        });

        setTimeout(function () {
          window.location.href = "../salidas_productos";
        }, 2000);
      }
    },
  });
}

$(document).on("click", "#btnVerSalidaTras", function () {
  window.location.href = "../../../inventarios_productos/catalogos/salidas_productos/functions/descargar_Salida.php?folio="+folio+"&orden="+0;
});

$(document).on("click", "#btnVerSalidaCoti", function () {
  window.location.href = "../../../inventarios_productos/catalogos/salidas_productos/functions/descargar_SalidaCoti.php?folio="+folio+"&orden="+0;
});

$(document).on("click", "#btnVerSalidaVenta", function () {
  window.location.href = "../../../inventarios_productos/catalogos/salidas_productos/functions/descargar_SalidaVenta.php?folio="+folio+"&orden="+0;
});

$(document).on("click", "#btnVerSalidaDevolucion", function () {
  window.location.href = "../../../inventarios_productos/catalogos/salidas_productos/functions/descargar_SalidaDevolucion.php?folio="+folio+"&cuenta="+0;
});

$(document).on("click", "#btnVerSalidaGral", function () {
  window.location.href = "../../../inventarios_productos/catalogos/salidas_productos/functions/descargar_SalidaGral.php?folio="+folio+"&orden="+0;
});
