var _permissions = {
    read: 0,
    add: 0,
    edit: 0,
    delete: 0,
    export: 0,
  };
  
  $(document).ready(function(){

  });

  function cargarDatosOrdenes(id) {
    validate_Permissions(11, 3, id);
    validarEmpresaProveedor(id);
    resetTabs("#cargarDatosOrdenes");
  
    var html = `<div class="row">
                      <div class="col-lg-12">
                        <div class="container-fluid">
                          <!-- DataTales Example -->
                          <div class="card mb-4">
                            <div class="card-body">
                              <div class="table-responsive">
                                <table class="table" id="tblListadoOrdenesCompra" width="100%" cellspacing="0">
                                  <thead>
                                    <tr>
                                    <th>Id</th>
                                    <th>Referencia</th>
                                    <th>Fecha de emisión</th>
                                    <th>Fecha estimada de entrega</th>
                                    <th>Importe</th>
                                    <th>Estado de la orden</th>
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
    validarEmpresaProveedor(id);
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
                    <div class="col-xl-3 col-lg-2 col-md-2 col-sm-6 col-xs-6 oculto">
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
                            <table class="table" id="tblHistoricoPorProveedor" width="100%" cellspacing="0">
                              <thead>
                                <tr>
                                <th>Id</th>
                                <th>Folio factura</th>
                                <th>Fecha de factura</th>
                                <th>F. de vencimiento</th>
                                <th>Vencimiento</th>
                                <th>Importe</th>
                                <th>Saldo insoluto</th>
                                <th>Estatus</th>
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
  
  function validarEmpresaProveedor(PKProveedor) {
    $.ajax({
      url: "../../php/funciones.php",
      data: {
        clase: "get_data",
        funcion: "validar_EmpresaProveedor",
        data: PKProveedor,
      },
      dataType: "json",
      success: function (data) {
        /* Validar si ya existe el identificador con ese nombre*/
        if (parseInt(data[0]["valido"]) != "1") {
          window.location.href = "../proveedores";
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
          //cargarTablaVentas(_permissions);
        } else if (pestana == "2") {
          //COTIZACIONES
          //cargarTablaContizaciones(_permissions);
        } else if (pestana == "3") {
          //PEDIDOS
          cargarTablaOrdenes();
        } else if (pestana == "4") {
          //DATOS DE PRODUCTOS
          cargarTablaPagos();
        } else if (pestana == "5") {
          //DATOS DE DIRECCIONES DE ENVIO
          //cargarTablaDireccionesEnvio(id, _permissions.edit);
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
  
  function cargarTablaOrdenes() {
    var proveedor_id = $("#hddPKProveedor").val();
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
  
    var tablePedidos = $("#tblListadoOrdenesCompra").DataTable({
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
      <"container-fluid mt-4"<"row"<"#btn-filters-pedidos.col-sm-12 col-md-10 p-0"><"col-sm-12 col-md-2 p-0"p>>>`,
      buttons: [],
      ajax: {
        type: "POST", 
        url: "get_ordenesCompra.php",
        data: {
          proveedor_id: proveedor_id,
        },
      },
      columns: [
        { data: "Id" },
        { data: "Referencia" },
        { data: "FechaEmision" },
        { data: "FechaEstimadaEntrega" },
        { data: "Importe" },
        { data: "EstatusOrden" },
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
  
  function cargarTablaPagos() {
    var proveedor_id = $("#hddPKProveedor").val();
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
      url: "get_historico.php",
      data:{
        proveedor_id: proveedor_id,
      }, 
      async: true, 
      success: function(respuesta){
        var json = JSON.parse(respuesta);
        $("#tblHistoricoPorProveedor").DataTable({
          restrieve: true,
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
          <"container-fluid mt-4"<"row"<"#btn-filters-pagos.col-sm-12 col-md-10 p-0"><"col-sm-12 col-md-2 p-0"p>>>`,
          buttons: [],
          columns: [
            { data: "Id"},
            { data: "Folio de Factura" },
            { data: "Fecha de Factura", class:"text-center" },
            { data: "Fecha de Vencimiento", class:"text-center" },
            { data: "Vencimiento", class:"text-center"},
            { data: "Importe" },
            { data: "saldo_insoluto"},
            { data: "Estatus", class:"text-center" },
          ],
          data : json.data
        });
        //se muestra el total
        $("#lblTotal").text(json.total);
        $("#lblTotalCA").text(json.creditoA);
        $("#lblTotalCD").text(json.creditoD);
        $("#lblTotalCuV").text(json.cuentasV);
        $("#lblTotalCC").text(json.cuentasC);
      },
      error: function (request, error) {
                alertify.success(error);
      }
    });
  }
  
  $(document).on("click", "#btnFilterExits", function () {
    filtra_historicoCPC($("#txtDateFrom").val(), $("#txtDateTo").val(), $("#hddPKProveedor").val());
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
  
  function filtra_historicoCPC(fecha_desde, fecha_hasta, proveedor_id){
    validarImputs();
    if(validarImputs()){
        if(fecha_desde==""){
            fecha_desde="f"
        }
        if(fecha_hasta==""){
            fecha_hasta="f"
        }
        $.ajax({
          type: "POST", 
          url: "historico_filtro.php",
          data:{
            proveedor_id: proveedor_id,
            Ffrom: fecha_desde,
            Fto: fecha_hasta,
          }, 
          async: true, 
          success: function(respuesta){
            var json = JSON.parse(respuesta);
            $("#tblHistoricoPorProveedor").DataTable({
              restrieve: true,
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
              <"container-fluid mt-4"<"row"<"#btn-filters-pagos.col-sm-12 col-md-10 p-0"><"col-sm-12 col-md-2 p-0"p>>>`,
              buttons: [],
              columns: [
                { data: "Id" },
                { data: "Folio de Factura" },
                { data: "Fecha de Factura", class:"text-center" },
                { data: "Fecha de Vencimiento", class:"text-center" },
                { data: "Vencimiento", class:"text-center"},
                { data: "Importe" },
                { data: "saldo_insoluto" },
                { data: "Estatus", class:"text-center" },
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
  
  function obtenerIdProveedorEditar(id) {
      //window.location.href = "editar_proveedor.php?p=" + id;
      window.open("editar_proveedor.php?p=" + id, "_blank")
  }

  function obtenerVer(id) {
    window.location.href = "../orden_compras/verOrdenCompra.php?oc=" + id;
  }