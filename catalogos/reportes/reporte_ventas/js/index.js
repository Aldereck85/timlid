var slimMes;
$(document).ready(function (e) {
  let activa = "facturas";
  const ver = $("#ver").val();
  ///Verifica los permisos
  if ($("#ver").val() !== "1") {
    $("#alert").modal("show");
  } else {
    loadSlimSelect();
    loadcmbEstados();
    loadcmbProductos();
    loadcmbMarcas();
    loadcmbVendedor();
    ///Si tiene el permiso para exportar pone el boton
    if ($("#exportar").val() == "1") {
      const html = `<button data-toggle="tooltip" data-placement="top" disabled="true" title="Descargar Reporte" class="btn-custom btn-custom--blue" id="btnFilterExits" style="margin-top: 10px!important">Descargar</button>`;
      $("#container-buttons").append(html);
    }

    /* Define en que tav está y muestra o oculta el filtro de productos segun corresponda */
    $("#CargarConceptos").click(function (e) {
      /* Click en tab conceptos */
      activa = "productos";
      document.getElementById("divProducto").style.display = "block";
      document.getElementById("divFiltros").style.display = "block";
      document.getElementById("divRelleno").style.display = "none";
      document.getElementById("filtros_historico").style.display = "none";
    });

    $("#CargarFacturas").click(function (e) {
      /* Click en tab facturas */
      console.log(e);
      activa = "facturas";
      document.getElementById("divProducto").style.display = "none";
      document.getElementById("divRelleno").style.display = "block";
      document.getElementById("divFiltros").style.display = "block";
      document.getElementById("filtros_historico").style.display = "none";
    });
    $("#reportVentas").click(function (e) {
      /* Click en tab facturas */
      activa = "ventas";
      document.getElementById("divFiltros").style.display = "none";
      document.getElementById("filtros_historico").style.display = "none";
    });
    $("#CargarHistorico").click(function (e) {
      /* Click en tab historico */
      activa = "historico";
      document.getElementById("divFiltros").style.display = "none";
      document.getElementById("filtros_historico").style.display = "block";
    });
  }
  //Redireccionamos al Dash cuando se oculta el modal.
  $("#alert").on("hidden.bs.modal", function (e) {
    window.location = "../../dashboard.php";
  });

  //cargarTablaIndex();

  $("#btnFilterExits").click(function (e) {
    //e.preventDefault();
    if (activa == "facturas") {
      filtrar(activa);
    } else if (activa == "productos") {
      filtrar(activa);
    }
  });

  /* Detecta el clic y manda a llamar a la funcion de acuerdo a que tav este activa */
  $("#btnFiltertable").click(function (e) {
    //e.preventDefault();
    if (ver == "1") {
      if (activa == "facturas") {
        cargarTablaIndex();
      } else if (activa == "productos") {
        LoadProductos(activa);
      }
    }
  });

  /* Si se selecciona un data range quita el mes seleccionado */
  $(".dateRange").change(function () {
    var date = $(this).val();
    slimMes.destroy();
    //slimMes.set('');
    $("#cmbMes option:selected").prop("selected", false);
    slimMes = new SlimSelect({
      select: '#cmbMes'
    });
  });

  
  /* Si selecciona un mes deselecciona el rango de fechas */
  $("#cmbMes").change(function (e) { 
    e.preventDefault();
    var mes = $(this).val();
    console.log(mes, "change");
    if(mes != "010"){
      
      $(".dateRange").val(null);
      if (mes == "000") {
  
      } else {
  
      }
    }
    
    
  });
});

//Carga tabla de tav reporte de productos
function LoadProductos(nav) {
  var cmbCliente = $("#cmbCliente option:selected").val();
  var cmbVendedor = $("#cmbVendedor option:selected").val();
  var cmbEstado = $("#cmbEstado option:selected").val();
  var cmbMarcas = $("#cmbMarcas option:selected").val();
  var cmbProductos = $("#cmbProductos option:selected").val();

  let _fecha_de = $("#txtDateFrom").val();
  let _fecha_to = $("#txtDateTo").val();
  if (_fecha_de == "") {
    _fecha_de = "000";
  }
  if (_fecha_to == "") {
    _fecha_to = "000";
  }
  var mes = $("#cmbMes").val();
  if(mes.length ===0){
     mes = "010";
   }
  $("#tblreportP").DataTable().destroy();

  if ($("#exportar").val() == 1) {
    topButtons = [
      {
        extend: "excelHtml5",
        text: '<span class="d-flex align-items-center"><img src="../../../img/icons/ICONO DESCARGAR2_Mesa de trabajo 1.svg" width="20" class="mr-1"> Descargar excel</span>',
        className: "btn-custom--white-dark",
        action: function () {
          filtrar(nav);
        },
        titleAttr: "Excel",
      },
    ];
  } else {
    topButtons = [];
  }

  let espanol = {
    sProcessing: "Procesando...",
    sZeroRecords: "No se encontraron resultados",
    sEmptyTable: "Ningún dato disponible en esta tabla",
    sSearch: '<img src="../../../img/timdesk/buscar.svg" width="20px" />',
    sLoadingRecords: "Cargando...",
    searchPlaceholder: "Buscar...",
    oPaginate: {
      sFirst: "Primero",
      sLast: "Último",
      sNext: "<i class='fas fa-chevron-right'></i>",
      sPrevious: "<i class='fas fa-chevron-left'></i>",
    },
  };

  tablaRP = $("#tblreportP").DataTable({
    language: espanol,
    info: false,
    /* autoWidth: false, */
    scrollX: true,
    bSort: false,
    pageLength: 10,
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
      buttons: topButtons,
    },
    ajax: {
      url: "php/controller.php",
      data: {
        clase: "get_data",
        funcion: "loadDataReportP",
        cliente_id: cmbCliente,
        vendedor_id: cmbVendedor,
        estado_id: cmbEstado,
        producto_id: cmbProductos,
        marca_id: cmbMarcas,
        date_from: _fecha_de,
        date_to: _fecha_to,
        mes_select: mes,
      },
    },
    columns: [
      { data: "Producto" },
      { data: "Marca", className: "text-center" },
      { data: "Piezas" },
      { data: "Total", className: "text-left" },
    ],
    //Poner la columna de id oculta
    columnDefs: [
      {
        targets: [],
        visible: false,
        searchable: false,
      },
    ],
  });
}

//VEr en pagina la tabla
function cargarTablaIndex() {
  var cmbCliente = $("#cmbCliente option:selected").val();
  var cmbVendedor = $("#cmbVendedor option:selected").val();
  var cmbEstado = $("#cmbEstado option:selected").val();
  var cmbMarcas = $("#cmbMarcas option:selected").val();
  var cmbProductos = $("#cmbProductos option:selected").val();
  var mes = $("#cmbMes").val();


  let _fecha_de = $("#txtDateFrom").val();
  let _fecha_to = $("#txtDateTo").val();
  if (_fecha_de == "") {
    _fecha_de = "000";
  }
  if (_fecha_to == "") {
    _fecha_to = "000";
  }
  if(mes.length ===0){
    mes = "010";
  }
  $("#tblreport").DataTable().destroy();

  if ($("#exportar").val() == 1) {
    topButtons = [
      {
        extend: "excelHtml5",
        text: '<i class="fas fa-cloud-download-alt"></i> Descargar excel',
        className: "btn-table-custom--turquoise",
        action: function () {
          filtrar();
        },
        titleAttr: "Excel",
      },
    ];
  } else {
    topButtons = [];
  }

  let espanol = {
    sProcessing: "Procesando...",
    sZeroRecords: "No se encontraron resultados",
    sEmptyTable: "Ningún dato disponible en esta tabla",
    sSearch: '<img src="../../../img/timdesk/buscar.svg" width="20px" />',
    sLoadingRecords: "Cargando...",
    searchPlaceholder: "Buscar...",
    oPaginate: {
      sFirst: "Primero",
      sLast: "Último",
      sNext: "<i class='fas fa-chevron-right'></i>",
      sPrevious: "<i class='fas fa-chevron-left'></i>",
    },
  };

  tablaR = $("#tblreport").DataTable({
    language: espanol,
    info: false,
    /* autoWidth: false, */
    scrollX: true,
    bSort: false,
    pageLength: 10,
    responsive: true,
    lengthChange: false,
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
      buttons: topButtons,
    },
    ajax: {
      url: "php/controller.php",
      data: {
        clase: "get_data",
        funcion: "loadDataReport",
        cliente_id: cmbCliente,
        vendedor_id: cmbVendedor,
        estado_id: cmbEstado,
        producto_id: cmbProductos,
        marca_id: cmbMarcas,
        date_from: _fecha_de,
        date_to: _fecha_to,
        mes_select: mes,
      },
    },
    columns: [
      { data: "factura" },
      { data: "folio", className: "text-center" },
      { data: "estado" },
      { data: "cliente", className: "text-center" },
      { data: "asesor" },
      { data: "fecha" },
    ],
    //Poner la columna de id oculta
    columnDefs: [
      {
        targets: [],
        visible: false,
        searchable: false,
      },
    ],
  });
}

function loadSlimSelect() {
  new SlimSelect({
    select: "#cmbCliente",
    deselectLabel: '<span class="">✖</span>',
  });
  new SlimSelect({
    select: "#cmbVendedor",
    deselectLabel: '<span class="">✖</span>',
  });
  new SlimSelect({
    select: "#cmbEstado",
    deselectLabel: '<span class="">✖</span>',
  });
  new SlimSelect({
    select: "#cmbMarcas",
    deselectLabel: '<span class="">✖</span>',
  });

  new SlimSelect({
    select: "#cmbProductos",
    deselectLabel: '<span class="">✖</span>',
  });
 slimMes = new SlimSelect({
    select: "#cmbMes",
    deselectLabel: '<span class="">✖</span>',
    data: [
      { value: "000", text: "Todos" },
      { text: "Enero", value: "1" },
      { text: "Febrero", value: "2" },
      { text: "Marzo", value: "3" },
      { text: "Abril", value: "4" },
      { text: "Mayo", value: "5" },
      { text: "Junio", value: "6" },
      { text: "Julio", value: "7" },
      { text: "Agosto", value: "8" },
      { text: "Septiembre", value: "9" },
      { text: "Octubre", value: "10" },
      { text: "Noviembre", value: "11" },
      { text: "Diciembre", value: "12" },
      { value: "010", text: "Rango de fechas", disabled: true },
    ],
  });
}

function filterTable() {
  var cmbCliente = $("#cmbCliente option:selected").val();
  var cmbVendedor = $("#cmbVendedor option:selected").val();
  var cmbEstado = $("#cmbEstado option:selected").val();
  var cmbMarcas = $("#cmbMarcas option:selected").val();
  var cmbProductos = $("#cmbProductos option:selected").val();

  let _fecha_de = $("#txtDateFrom").val();
  let _fecha_to = $("#txtDateTo").val();
  if (_fecha_de == "") {
    _fecha_de = "000";
  }
  if (_fecha_to == "") {
    _fecha_to = "000";
  }

  //Retornar algo
  $.ajax({
    type: "POST",
    url: "php/controller.php",
    dataType: "json",
    data: {
      clase: "get_data",
      funcion: "loadDataReport",
      cliente_id: cmbCliente,
      vendedor_id: cmbVendedor,
      estado_id: cmbEstado,
      producto_id: cmbProductos,
      marca_id: cmbMarcas,
      date_from: _fecha_de,
      date_to: _fecha_to,
    },
    success: function (data) {
      console.log("data de Reporte: ", data);
      $.each(data, function (i) {
        //Crea el html para ser mostrado
        if (i == 0) {
          html +=
            '<option selected value="' + "000" + '">' + "Todas" + "</option>";

          html +=
            '<option value="' +
            data[i].PKMarcaProducto +
            '">' +
            data[i].MarcaProducto +
            "</option>";
        } else {
          html +=
            '<option value="' +
            data[i].PKMarcaProducto +
            '">' +
            data[i].MarcaProducto +
            "</option>";
        }
      });
    },
    error: function (error) {
      console.log("Error");
      console.log(error);
    },
  });

  console.log(
    cmbCliente,
    cmbVendedor,
    cmbEstado,
    cmbMarcas,
    cmbProductos,
    _fecha_de,
    _fecha_to
  );
}

//Descargar
function filtrar(tav) {
  var cmbCliente = $("#cmbCliente option:selected").val();
  var cmbVendedor = $("#cmbVendedor option:selected").val();
  var cmbEstado = $("#cmbEstado option:selected").val();
  var cmbMarcas = $("#cmbMarcas option:selected").val();
  var cmbProductos = $("#cmbProductos option:selected").val();
  var mes = $("#cmbMes").val();

  let _fecha_de = $("#txtDateFrom").val();
  let _fecha_to = $("#txtDateTo").val();
  if (_fecha_de == "") {
    _fecha_de = "000";
  }
  if (_fecha_to == "") {
    _fecha_to = "000";
  }

  if(mes.length ===0){
    mes = "010";
  }


  if (tav == "facturas") {
    ///Usado para descargar el archivo de facturas
    window.location.href =
      "php/descargar_excel?cliente_id=" +
      cmbCliente +
      "&vendedor_id=" +
      cmbVendedor +
      "&estado_id=" +
      cmbEstado +
      "&producto_id=" +
      cmbProductos +
      "&marca_id=" +
      cmbMarcas +
      "&date_from=" +
      _fecha_de +
      "&date_to= " +
      _fecha_to +
      "&mes=" +
      mes;
  } else if (tav == "productos") {
    ///Usado para descargar el archivo de productos
    window.location.href =
      "php/descargar_excelProductos?cliente_id=" +
      cmbCliente +
      "&vendedor_id=" +
      cmbVendedor +
      "&estado_id=" +
      cmbEstado +
      "&producto_id=" +
      cmbProductos +
      "&marca_id=" +
      cmbMarcas +
      "&date_from=" +
      _fecha_de +
      "&date_to= " +
      _fecha_to +
      "&mes=" +
      mes;
  }

  //Retornar algo
  /* $.ajax({
    type: "POST",
    url: "php/controller.php",
    dataType: "json",
    data: { clase: "get_data", funcion: "loadDataReport", cliente_id: cmbCliente, vendedor_id: cmbVendedor, estado_id: cmbEstado, producto_id: cmbProductos, marca_id: cmbMarcas, date_from: _fecha_de, date_to: _fecha_to  },
    success: function (data) {
       console.log("data de Reporte: ", data);
      $.each(data, function (i) { */

  //Crea el html para ser mostrado
  /* if (i == 0) {
          html +=
            '<option selected value="' + "000" + '">' + "Todas" + "</option>";

          html +=
            '<option value="' +
            data[i].PKMarcaProducto +
            '">' +
            data[i].MarcaProducto +
            "</option>";
        } else {
          html +=
            '<option value="' +
            data[i].PKMarcaProducto +
            '">' +
            data[i].MarcaProducto +
            "</option>";
        } */
  /* });
    },
    error: function (error) {
      console.log("Error");
      console.log(error);
    },
  }); */

  console.log(
    cmbCliente,
    cmbVendedor,
    cmbEstado,
    cmbMarcas,
    cmbProductos,
    _fecha_de,
    _fecha_to,
    mes
  );
}

function loadcmbCliente() {
  return new Promise((resolve) => {
    var html = "";
    //Consulta los CLIENTES de la empresa
    $.ajax({
      type: "POST",
      url: "php/controller.php",
      dataType: "json",
      data: { clase: "get_data", funcion: "get_ClienteCombo" },
      success: function (data) {
        // console.log("data de proveedor: ", data);
        $.each(data, function (i) {
          //Crea el html para ser mostrado
          if (i == 0) {
            html +=
              '<option selected value="' + "000" + '">' + "Todos" + "</option>";
            html +=
              '<option value="' +
              data[i].PKData +
              '">' +
              data[i].Data +
              "</option>";
          } else {
            html +=
              '<option value="' +
              data[i].PKData +
              '">' +
              data[i].Data +
              "</option>";
          }
        });
        //Pone los proveedores en el select
        $("#cmbCliente").append(html);
        resolve();
      },
      error: function (error) {
        console.log("Error");
        console.log(error);
      },
    });
  });
}

async function loadcmbVendedor() {
  await loadcmbCliente();
  var html = "";
  //Consulta los CLIENTES de la empresa
  $.ajax({
    type: "POST",
    url: "php/controller.php",
    dataType: "json",
    data: { clase: "get_data", funcion: "loadCmbVendedor" },
    success: function (data) {
      // console.log("data de proveedor: ", data);
      $.each(data, function (i) {
        //Crea el html para ser mostrado
        if (i == 0) {
          html +=
            '<option selected value="' + "000" + '">' + "Todos" + "</option>";
          html +=
            '<option value="' +
            data[i].PKVendedor +
            '">' +
            data[i].Nombre +
            "</option>";
        } else {
          html +=
            '<option value="' +
            data[i].PKVendedor +
            '">' +
            data[i].Nombre +
            "</option>";
        }
      });
      //Pone los proveedores en el select
      $("#cmbVendedor").append(html);
      document.getElementById("btnFiltertable").disabled = false;
      document.getElementById("btnFilterExits").disabled = false;
    },
    error: function (error) {
      console.log("Error");
      console.log(error);
    },
  });
}

function loadcmbEstados() {
  var html = "";
  //Consulta los CLIENTES de la empresa
  $.ajax({
    type: "POST",
    url: "php/controller.php",
    dataType: "json",
    data: { clase: "get_data", funcion: "loadCmbEstados" },
    success: function (data) {
      // console.log("data de proveedor: ", data);
      $.each(data, function (i) {
        //Crea el html para ser mostrado
        if (i == 0) {
          html +=
            '<option selected value="' + "000" + '">' + "Todos" + "</option>";
          html +=
            '<option value="' +
            data[i].PKEstado +
            '">' +
            data[i].Estado +
            "</option>";
        } else {
          html +=
            '<option value="' +
            data[i].PKEstado +
            '">' +
            data[i].Estado +
            "</option>";
        }
      });
      //Pone los proveedores en el select
      $("#cmbEstado").append(html);
    },
    error: function (error) {
      console.log("Error");
      console.log(error);
    },
  });
}

function loadcmbProductos() {
  var html = "";
  //Consulta los CLIENTES de la empresa
  $.ajax({
    type: "POST",
    url: "php/controller.php",
    dataType: "json",
    data: { clase: "get_data", funcion: "loadCmbProductos" },
    success: function (data) {
      // console.log("data de proveedor: ", data);
      $.each(data, function (i) {
        //Crea el html para ser mostrado
        if (i == 0) {
          html +=
            '<option selected value="' + "000" + '">' + "Todos" + "</option>";
          html +=
            '<option value="' +
            data[i].PKProducto +
            '">' +
            data[i].Nombre +
            "</option>";
        } else {
          html +=
            '<option value="' +
            data[i].PKProducto +
            '">' +
            data[i].Nombre +
            "</option>";
        }
      });
      //Pone los proveedores en el select
      $("#cmbProductos").append(html);
    },
    error: function (error) {
      console.log("Error");
      console.log(error);
    },
  });
}

function loadcmbMarcas() {
  var html = "";
  //Consulta los CLIENTES de la empresa
  $.ajax({
    type: "POST",
    url: "php/controller.php",
    dataType: "json",
    data: { clase: "get_data", funcion: "loadCmbMarcas" },
    success: function (data) {
      // console.log("data de proveedor: ", data);
      $.each(data, function (i) {
        //Crea el html para ser mostrado
        if (i == 0) {
          html +=
            '<option selected value="' + "000" + '">' + "Todas" + "</option>";

          html +=
            '<option value="' +
            data[i].PKMarcaProducto +
            '">' +
            data[i].MarcaProducto +
            "</option>";
        } else {
          html +=
            '<option value="' +
            data[i].PKMarcaProducto +
            '">' +
            data[i].MarcaProducto +
            "</option>";
        }
      });
      //Pone los proveedores en el select
      $("#cmbMarcas").append(html);
    },
    error: function (error) {
      console.log("Error");
      console.log(error);
    },
  });
}
