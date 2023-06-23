var _permissions = {
  read: 0,
  add: 0,
  edit: 0,
  delete: 0,
  export: 0,
};

function cargarDatosVentas(id) {
  validarEmpresaCliente(id);
  validate_Permissions(11, 1, id);
  resetTabs("#cargarDatosVentas");

  var html = `<div class="row">
      <div class="col-lg-12">
        <div class="container-fluid">
          <!-- DataTales Example -->
          <div class="card mb-4">
            <div class="card-body">
              <div class="table-responsive">
                <table class="table" id="tblListadoVentas" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Id</th>
                      <th>Referencia</th>
                      <th>Fecha emisión</th>
                      <th>Fecha vencimiento</th>
                      <th>Importe</th>
                      <th>Estatus venta</th>
                      <th>Estatus Factura</th>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>
        </div>
      </div>
    </div>`;
  $("#datos").html(html);
}

function cargarDatosCotizacion(id) {
  validate_Permissions(11, 2, id);
  validarEmpresaCliente(id);
  resetTabs("#cargarDatosCotizacion");

  var html = `<div class="row">
                    <div class="col-lg-12">
                      <div class="container-fluid">
                        <!-- DataTales Example -->
                        <div class="card mb-4">
                          <div class="card-body">
                            <div class="table-responsive">
                              <table class="table" id="tblListadoCotizaciones" width="100%" cellspacing="0">
                                <thead>
                                  <tr>
                                    <th>Referencia</th>
                                    <th>Importe Total</th>
                                    <th>Sucursal</th>
                                    <th>Estatus</th>
                                    <th>Estatus factura</th>
                                  </tr>
                                </thead>
                              </table>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>`;
  $("#datos").html(html);
}

function cargarDatosPedidos(id) {
  validate_Permissions(11, 3, id);
  validarEmpresaCliente(id);
  resetTabs("#cargarDatosPedidos");

  var html = `<div class="row">
                    <div class="col-lg-12">
                      <div class="container-fluid">
                        <!-- DataTales Example -->
                        <div class="card mb-4">
                          <div class="card-body">
                            <div class="table-responsive">
                              <table class="table" id="tblListadoPedidos" width="100%" cellspacing="0">
                                <thead>
                                  <tr>
                                    <th>No. Pedido</th>
                                    <th>Sucursal origen</th>
                                    <th>Sucursal destino</th>
                                    <th>Fecha generación</th>
                                    <th>Tipo</th>
                                    <th>Estatus</th>
                                    <th>Estatus factura</th>
                                  </tr>
                                </thead>
                              </table>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>`;

  $("#datos").html(html);
}

function cargarDatosPagos(id) {
  validate_Permissions(11, 4, id);
  validarEmpresaCliente(id);
  resetTabs("#cargarDatosPagos");

  var html = `<BR>
              <div class="form-group" id="groupFiltro">
                <div class="row">
                  <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-xs-6">
                    <label for="txtDateFrom">De:</label>
                    <input class="form-control" type="date" name="txtDateFrom" id="txtDateFrom">
                    <div class="invalid-feedback" id="invalid-txtDateFrom"></div>
                  </div>
                  <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-xs-6">
                    <label for="txtDateTo">Hasta:</label>
                    <input class="form-control" type="date" name="txtDateTo" id="txtDateTo">
                    <div class="invalid-feedback" id="invalid-txtDateTo">.</div>
                  </div>
                  <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-xs-6">
                    <a class="btn-custom btn-custom--blue" id="btnFilterExits" style="margin-top: 10px!important">Filtrar</a>
                  </div>
                  <div class="col-xl-3 col-lg-2 col-md-2 col-sm-6 col-xs-6">
                    <label for="lblTotalSaldo">Total Saldo:</label>
                    <h4 id="lblTotalSaldo"></h4>
                  </div>
                  <div class="col-xl-3 col-lg-2 col-md-2 col-sm-6 col-xs-6" id="totalCuentas">
                    <label for="lblTotalCC">Total Cuentas al Corriente:</label>
                    <h4 id="lblTotalCC"></h4>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-12">
                  <div class="container-fluid">
                    <!-- DataTales Example -->
                    <div class="card mb-4">
                      <div class="card-body">
                        <div class="table-responsive">
                          <table class="table" id="tblListadoPagos" width="100%" cellspacing="0">
                            <thead>
                              <tr>
                              <th>Folio factura</th>
                              <th>Serie</th>
                              <th>F. de expedicion</th>
                              <th>F. de vencimiento</th>
                              <th>Estado</th>
                              <th>Total</th>
                              <th>Importe Pagado</th>
                              <th>Importe Notas de Crédito</th>
                              <th>Saldo Insoluto</th>
                              </tr>
                            </thead>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>`;

  $("#datos").html(html);
}

function resetTabs(id) {
  $(".nav-link").removeClass("active");
  $(id).addClass("active");
}

function validarEmpresaCliente(pkCliente) {
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "validar_EmpresaCliente",
      data: pkCliente,
    },
    dataType: "json",
    success: function (data) {
      /* Validar si ya existe el identificador con ese nombre*/
      if (parseInt(data[0]["valido"]) != "1") {
        window.location.href = "../clientes";
      }
    },
  });
}

function validate_Permissions(pkPantalla, pestana, id) {
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

      if (pestana == "1") {
        //VENTAS
        cargarTablaVentas(_permissions);
      } else if (pestana == "2") {
        //COTIZACIONES
        cargarTablaContizaciones(_permissions);
      } else if (pestana == "3") {
        //PEDIDOS
        cargarTablaPedidos(_permissions);
      } else if (pestana == "4") {
        //DATOS DE PRODUCTOS
        cargarTablaPagos(_permissions);
      } else if (pestana == "5") {
        //DATOS DE DIRECCIONES DE ENVIO
        cargarTablaDireccionesEnvio(id, _permissions.edit);
      }
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
      sFirst: "Primero",
      sLast: "Último",
      sNext: "<i class='fas fa-chevron-right'></i>",
      sPrevious: "<i class='fas fa-chevron-left'></i>",
    },
  };
  return idioma_espanol;
}

function cargarTablaVentas(permisos) {
  var clienteId = $("#txtPKCliente").val();
  var filtro = "";
  /* $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
    if (settings.nTable.id !== 'tblListadoVentas' ){
      return true;
    }
    var estatus = data[5]; // informacion del estado de la cotizacion

    if (filtro == "") {
      return true;
    }

    if (estatus == filtro) {
      return true;
    } else {
      return false;
    }
    
    
  }); */
  var tableVentas = $("#tblListadoVentas").DataTable({
    destroy: true,
    language: setFormatDatatables(),
    info: false,
    scrollX: true,
    bSort: false,
    pageLength: 15,
    responsive: true,
    lengthChange: false,
    columnDefs: [{ orderable: false, targets: 0, visible: false }],
    dom: `<"container-fluid"<"row mb-3"<"col-sm-12 col-md-9 p-0 d-flex align-items-center"B><"col-sm-12 col-md-3 p-0"f>>>rti
    <"container-fluid mt-4"<"row"<"#btn-filters-ventas.col-sm-12 col-md-10 p-0"><"col-sm-12 col-md-2 p-0"p>>>`,
    buttons: [],
    ajax: {
      url: "../../php/funciones.php",
      data: {
        clase: "get_data",
        funcion: "get_datos_ventas_cliente",
        read: permisos.read,
        clienteId,
      },
    },
    columns: [
      { data: "Id" },
      { data: "Referencia" },
      { data: "FechaEmision" },
      { data: "FechaVencimiento" },
      { data: "Importe" },
      { data: "EstatusVenta" },
      { data: "EstatusFactura", class:"text-center"},
    ],
  });

  /* new $.fn.dataTable.Buttons(tableVentas, {
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
          $("#tblListadoVentas").DataTable().draw();
        },
      },
      {
        text: '<i class="fas fa-archive"></i> Nueva',
        className: "btn-table-custom--turquoise",
        action: function (e, dt, node, config) {
          filtro = "Nueva";
          $("#tblListadoVentas").DataTable().draw();
          filtro = "";
        },
      },
      {
        text: '<i class="fas fa-book-open"></i> Parcialmente surtida',
        className: "btn-table-custom--yellow",
        action: function (e, dt, node, config) {
          filtro = "Parcialmente surtida";
          $("#tblListadoVentas").DataTable().draw();
          filtro = "";
        },
      },
      {
        text: '<i class="fas fa-check-circle"></i> Surtida completa',
        className: "btn-table-custom--green",
        action: function (e, dt, node, config) {
          filtro = "Surtida completa";
          $("#tblListadoVentas").DataTable().draw();
          filtro = "";
        },
      },
      {
        text: '<i class="fas fa-archive"></i> Nueva FD',
        className: "btn-table-custom--turquoise",
        action: function (e, dt, node, config) {
          filtro = "Nueva FD";
          $("#tblListadoVentas").DataTable().draw();
          filtro = "";
        },
      },
      {
        text: '<i class="fas fa-book-open"></i> Parcialmente surtida FD',
        className: "btn-table-custom--yellow",
        action: function (e, dt, node, config) {
          filtro = "Parcialmente surtida FD";
          $("#tblListadoVentas").DataTable().draw();
          filtro = "";
        },
      },
      {
        text: '<i class="fas fa-check-circle"></i> Surtida completa FD',
        className: "btn-table-custom--green",
        action: function (e, dt, node, config) {
          filtro = "Surtida completa FD";
          $("#tblListadoVentas").DataTable().draw();
          filtro = "";
        },
      },
      {
        text: '<i class="fas fa-times"></i> Cerrada',
        className: "btn-table-custom--red",
        action: function (e, dt, node, config) {
          filtro = "Cerrada";
          $("#tblListadoVentas").DataTable().draw();
          filtro = "";
        },
      },
      {
        text: '<i class="far fa-arrow-alt-circle-up"></i> Facturada',
        className: "btn-table-custom--turquoise",
        action: function (e, dt, node, config) {
          filtro = "Facturada";
          $("#tblListadoVentas").DataTable().draw();
          filtro = "";
        },
      },
    ],
  });

  tableVentas.buttons(1, null).container().appendTo("#btn-filters-ventas"); */
}

function cargarTablaContizaciones(permisos) {
  var clienteId = $("#txtPKCliente").val();
  var filtro = "";
 /*  $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
    if (settings.nTable.id !== 'tblListadoCotizaciones' ){
      return true;
    }
    
      var estatus = data[3]; // informacion del estado de la cotizacion

      if (filtro == "") {
        return true;
      }
  
      if (estatus == filtro) {
        return true;
      } else {
        return false;
      }
    
  }); */
  
  var tableCotizaciones = $("#tblListadoCotizaciones").DataTable({
    destroy: true,
    language: setFormatDatatables(),
    info: false,
    scrollX: true,
    bSort: false,
    pageLength: 15,
    responsive: true,
    lengthChange: false,
    //columnDefs: [{ orderable: false, targets: 0, visible: false }],
    dom: `<"container-fluid"<"row mb-3"<"col-sm-12 col-md-9 p-0 d-flex align-items-center"B><"col-sm-12 col-md-3 p-0"f>>>rti
    <"container-fluid mt-4"<"row"<"#btn-filters-cotizaciones.col-sm-12 col-md-10 p-0"><"col-sm-12 col-md-2 p-0"p>>>`,
    buttons: [],
    ajax: {
      url: "../../php/funciones.php",
      data: {
        clase: "get_data",
        funcion: "get_datos_cotizaciones_cliente",
        read: permisos.read,
        clienteId,
      },
    },
    columns: [
      { data: "Referencia" },
      { data: "Importe" },
      { data: "Sucursal" },
      { data: "Estatus" },
      { data: "Estatus factura", class:"text-center"},
    ],
  });

  /* new $.fn.dataTable.Buttons(tableCotizaciones, {
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
        className: "btn-table-custom--blue",
        action: function (e, dt, node, config) {
          filtro = "";
          $("#tblListadoCotizaciones").DataTable().draw();
        },
      },
      {
        text: '<i class="far fa-clock"></i> Pendiente',
        className: "btn-table-custom--yellow",
        action: function (e, dt, node, config) {
          filtro = "Pendiente";
          $("#tblListadoCotizaciones").DataTable().draw();
          filtro = "";
        },
      },
      {
        text: '<i class="fas fa-angle-double-up"></i> Aceptada',
        className: "btn-table-custom--green",
        action: function (e, dt, node, config) {
          filtro = "Aceptada";
          $("#tblListadoCotizaciones").DataTable().draw();
          filtro = "";
        },
      },
      {
        text: '<i class="fas fa-file-invoice"></i> Facturada',
        className: "btn-table-custom--turquoise",
        action: function (e, dt, node, config) {
          filtro = "Facturada";
          $("#tblListadoCotizaciones").DataTable().draw();
          filtro = "";
        },
      },
      {
        text: '<i class="fas fa-ban"></i> Cancelada',
        className: "btn-table-custom--red",
        action: function (e, dt, node, config) {
          filtro = "Cancelada";
          $("#tblListadoCotizaciones").DataTable().draw();
          filtro = "";
        },
      },
      {
        text: '<i class="far fa-calendar-times"></i> Vencida',
        className: "btn-table-custom--red",
        action: function (e, dt, node, config) {
          filtro = "Vencida";
          $("#tblListadoCotizaciones").DataTable().draw();
          filtro = "";
        },
      },
    ],
  });

  tableCotizaciones.buttons(1, null).container().appendTo("#btn-filters-cotizaciones"); */
}

function cargarTablaPedidos(permisos) {
  var clienteId = $("#txtPKCliente").val();
  var filtro = "";
  /* $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
    if (settings.nTable.id !== 'tblListadoPedidos' ){
      return true;
    }
      var estatus = data[5]; // informacion del estado de la cotizacion

      if (filtro == "") {
        return true;
      }
  
      if (estatus == filtro) {
        return true;
      } else {
        return false;
      }
    
  }); */

  var tablePedidos = $("#tblListadoPedidos").DataTable({
    destroy: true,
    language: setFormatDatatables(),
    info: false,
    scrollX: true,
    bSort: false,
    pageLength: 15,
    responsive: true,
    lengthChange: false,
    //columnDefs: [{ orderable: false, targets: 0, visible: false }],
    dom: `<"container-fluid"<"row mb-3"<"col-sm-12 col-md-9 p-0 d-flex align-items-center"B><"col-sm-12 col-md-3 p-0"f>>>rti
    <"container-fluid mt-4"<"row"<"#btn-filters-pedidos.col-sm-12 col-md-10 p-0"><"col-sm-12 col-md-2 p-0"p>>>`,
    buttons: [],
    ajax: {
      url: "../../php/funciones.php",
      data: {
        clase: "get_data",
        funcion: "get_datos_pedidos_cliente",
        read: permisos.read,
        clienteId,
      },
    },
    columns: [
      { data: "No Pedido" },
      { data: "Sucursal origen" },
      { data: "Sucursal destino" },
      { data: "Fecha generacion" },
      { data: "Tipo pedido" },
      { data: "Estatus" },
      { data: "Estatus factura", class:"text-center"},
    ],
  });

  /* new $.fn.dataTable.Buttons(tablePedidos, {
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
          $("#tblListadoPedidos").DataTable().draw();
        },
      },
      {
        text: '<i class="fas fa-archive"></i> Nueva',
        className: "btn-table-custom--turquoise",
        action: function (e, dt, node, config) {
          filtro = "Nuevo";
          $("#tblListadoPedidos").DataTable().draw();
          filtro = "";
        },
      },
      {
        text: '<i class="fas fa-book-open"></i> Nuevo-FD',
        className: "btn-table-custom--turquoise",
        action: function (e, dt, node, config) {
          filtro = "Nuevo-FD";
          $("#tblListadoPedidos").DataTable().draw();
          filtro = "";
        },
      },
      {
        text: '<i class="fas fa-check-circle"></i> Parcialmente surtido',
        className: "btn-table-custom--yellow",
        action: function (e, dt, node, config) {
          filtro = "Parcialmente surtido";
          $("#tblListadoPedidos").DataTable().draw();
          filtro = "";
        },
      },
      {
        text: '<i class="fas fa-archive"></i> Parcialmente surtido-FD',
        className: "btn-table-custom--yellow",
        action: function (e, dt, node, config) {
          filtro = "Parcialmente surtido-FD";
          $("#tblListadoPedidos").DataTable().draw();
          filtro = "";
        },
      },
      {
        text: '<i class="fas fa-book-open"></i> Surtido completo',
        className: "btn-table-custom--green",
        action: function (e, dt, node, config) {
          filtro = "Surtido completo";
          $("#tblListadoPedidos").DataTable().draw();
          filtro = "";
        },
      },
      {
        text: '<i class="fas fa-check-circle"></i> Surtido completo-FD',
        className: "btn-table-custom--green",
        action: function (e, dt, node, config) {
          filtro = "Surtido completo-FD";
          $("#tblListadoPedidos").DataTable().draw();
          filtro = "";
        },
      },
      {
        text: '<i class="fas fa-times"></i> Cerrado',
        className: "btn-table-custom--red",
        action: function (e, dt, node, config) {
          filtro = "Cerrado";
          $("#tblListadoPedidos").DataTable().draw();
          filtro = "";
        },
      },
      {
        text: '<i class="far fa-arrow-alt-circle-up"></i> Cancelado',
        className: "btn-table-custom--red",
        action: function (e, dt, node, config) {
          filtro = "Cancelado";
          $("#tblListadoPedidos").DataTable().draw();
          filtro = "";
        },
      },
      {
        text: '<i class="far fa-arrow-alt-circle-up"></i> Facturado-directo',
        className: "btn-table-custom--turquoise",
        action: function (e, dt, node, config) {
          filtro = "Facturado-directo";
          $("#tblListadoPedidos").DataTable().draw();
          filtro = "";
        },
      },
      {
        text: '<i class="far fa-arrow-alt-circle-up"></i> Facturado-almacen',
        className: "btn-table-custom--turquoise",
        action: function (e, dt, node, config) {
          filtro = "Facturado-almacen";
          $("#tblListadoPedidos").DataTable().draw();
          filtro = "";
        },
      },
    ],
  });

  tablePedidos.buttons(1, null).container().appendTo("#btn-filters-pedidos"); */
}

function cargarTablaPagos(permisos) {
  var clienteId = $("#txtPKCliente").val();
  var filtro = "";
  /* $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
    var estatus = data[6]; // informacion del estado de la cotizacion

    if (filtro == "") {
      return true;
    }

    if (estatus == filtro) {
      return true;
    } else {
      return false;
    }
  }); */
  //se carga la datatable
  $.ajax({
    type: "POST", 
    url: "../../php/funciones.php",
    data:{
      clase: "get_data",
      funcion: "get_datos_pagos_cliente",
      read: permisos.read,
      clienteId,
    }, 
    async: true, 
    success: function(respuesta){
      var json = JSON.parse(respuesta);
      $("#tblListadoPagos").DataTable({
        restrieve: true,
        destroy: true,
        language: setFormatDatatables(),
        info: false,
        scrollX: true,
        bSort: false,
        pageLength: 15,
        responsive: true,
        lengthChange: false,
        dom: `<"container-fluid"<"row mb-3"<"col-sm-12 col-md-9 p-0 d-flex align-items-center"B><"col-sm-12 col-md-3 p-0"f>>>rti
        <"container-fluid mt-4"<"row"<"#btn-filters-pagos.col-sm-12 col-md-10 p-0"><"col-sm-12 col-md-2 p-0"p>>>`,
        buttons: [],
        columns: [
          { data: "Folio factura" },
          { data: "Serie" },
          { data: "F de expedicion" },
          { data: "F de vencimiento" },
          { data: "Estado" },
          { data: "Total", class:"text-center" },
          { data: "Importe Pagado", class:"text-center" },
          { data: "Importe Notas Credito", class:"text-center" },
          { data: "Monto", class:"text-center" },
        ],
        data : json.data
      });
      //se muestra el total
      $("#lblTotal").text(json.total);
      $("#lblTotalCA").text(json.creditoA);
      $("#lblTotalCD").text(json.creditoD);
      $("#lblTotalCuV").text(json.cuentasV);
      $("#lblTotalCC").text(json.cuentasC);
      $("#lblTotalSaldo").text(json.saldo);
    },
    error: function (request, error) {
              alertify.success(error);
    }
  });
}

$(document).on("click", "#btnFilterExits", function () {
  filtra_historicoCPC($("#txtDateFrom").val(), $("#txtDateTo").val(),_permissions.read, $("#txtPKCliente").val());
});

function validarImputs(){
  greenFlag=true;
  
  textInvalidDiv = "Se requiere almenos un dato";

  inputID2= "txtDateFrom";
  invalidDivID2 = "invalid-txtDateFrom";

  inputID3= "txtDateTo";
  invalidDivID3 = "invalid-txtDateTo";

      if (((($('#'+inputID2).val()=="") || ($('#'+inputID2).val()==null))) && ((($('#'+inputID3).val()=="") || ($('#'+inputID3).val()==null)))) {
          $("#" + inputID2).addClass("is-invalid");
          $("#" + invalidDivID2).show();
          $("#" + invalidDivID2).text(textInvalidDiv);
    
          $("#" + inputID3).addClass("is-invalid");
          $("#" + invalidDivID3).show();
          $("#" + invalidDivID3).text(textInvalidDiv);
          greenFlag = false;
        } else {
          $("#" + inputID2).removeClass("is-invalid");
          $("#" + invalidDivID2).hide();
          $("#" + invalidDivID2).text(textInvalidDiv);
    
          $("#" + inputID3).removeClass("is-invalid");
          $("#" + invalidDivID3).hide();
          $("#" + invalidDivID3).text(textInvalidDiv);
        }
  
  return greenFlag; 
}

function filtra_historicoCPC(fecha_desde, fecha_hasta, permiso, clienteID){
  validarImputs();
  if(validarImputs()){
      if(fecha_desde==""){
          fecha_desde="no"
      }
      if(fecha_hasta==""){
          fecha_hasta="no"
      }
      $.ajax({
        type: "POST", 
        url: "../../php/funciones.php",
        data:{
          clase: "get_data",
          funcion: "get_datos_pagos_cliente",
          read: permiso,
          clienteId: clienteID,
          fecha_desde,
          fecha_hasta,
          isfiltered : 1,
        }, 
        async: true, 
        success: function(respuesta){
          var json = JSON.parse(respuesta);
          $("#tblListadoPagos").DataTable({
            restrieve: true,
            destroy: true,
            language: setFormatDatatables(),
            info: false,
            scrollX: true,
            bSort: false,
            pageLength: 15,
            responsive: true,
            lengthChange: false,
            dom: `<"container-fluid"<"row mb-3"<"col-sm-12 col-md-9 p-0 d-flex align-items-center"B><"col-sm-12 col-md-3 p-0"f>>>rti
            <"container-fluid mt-4"<"row"<"#btn-filters-pagos.col-sm-12 col-md-10 p-0"><"col-sm-12 col-md-2 p-0"p>>>`,
            buttons: [],
            columns: [
              { data: "Folio factura" },
              { data: "Serie" },
              { data: "F de expedicion" },
              { data: "F de vencimiento" },
              { data: "Estado" },
              { data: "Monto", class:"text-center"},
            ],
            data : json.data
          });
          //se muestra el total
          $("#lblTotal").text(json.total);
        },
        error: function (request, error) {
                  alertify.success(error);
        }
      });
  }
}

function obtenerIdClienteEditar(id) {
    //window.location.href = "editar_cliente.php?c=" + id;
    window.open("editar_cliente.php?c=" + id, "_blank")
}