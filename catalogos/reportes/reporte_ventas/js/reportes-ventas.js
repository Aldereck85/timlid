$(document).ready(function () {
  loadChartSales();
  var slimMesV;
  /* CREATE SELECTS */
  new SlimSelect({
    select: "#vendedor_input",
  });
  new SlimSelect({
    select: "#cliente_input",
  });
  new SlimSelect({
    select: "#estado_input",
  });
  slimMesV = new SlimSelect({
    select: "#cmbMesV",
    deselectLabel: '<span class="">✖</span>',
    data: [
      /* { value: "000", text: "Todos" }, */
      { text: "Enero", value: "01" },
      { text: "Febrero", value: "02" },
      { text: "Marzo", value: "03" },
      { text: "Abril", value: "04" },
      { text: "Mayo", value: "05" },
      { text: "Junio", value: "06" },
      { text: "Julio", value: "07" },
      { text: "Agosto", value: "08" },
      { text: "Septiembre", value: "09" },
      { text: "Octubre", value: "10" },
      { text: "Noviembre", value: "11" },
      { text: "Diciembre", value: "12" },
      { value: "010", text: "Rango de fechas", disabled: true },
    ],
  });

  /* Si se selecciona un data range quita el mes seleccionado */
  $(".dateRange").change(function () {
    var date = $(this).val();
    slimMesV.destroy();
    //slimMes.set('');
    $("#cmbMesV option:selected").prop("selected", false);
    slimMesV = new SlimSelect({
      select: "#cmbMesV",
    });
  });

  /* Si selecciona un mes deselecciona el rango de fechas */
  $("#cmbMesV").change(function (e) {
    e.preventDefault();
    var mes = $(this).val();
    console.log(mes, "change");
    if (mes != "010") {
      $(".dateRange").val(null);
      if (mes == "000") {
      } else {
      }
    }
  });
  createTableBegin();

});

function validEmptyInput(item, invalid = null) {
  const val = item.value;
  const parent = item.parentNode;
  let invalidDiv;
  if (invalid) {
    invalidDiv = document.getElementById(invalid);
  } else {
    for (let i = 0; i < parent.children.length; i++) {
      if (parent.children[i].classList.contains("invalid-feedback")) {
        invalidDiv = parent.children[i];
        break;
      }
    }
  }
  if (!val) {
    //item.classList.add("is-invalid");
    invalidDiv.style.display = "block";
  } else {
    //item.classList.remove("is-invalid");
    invalidDiv.style.display = "none";
  }
}

$(document).on("click","#fitro_reportes",function () {
    
    var vendedor = $("#vendedor_input").val();
    var cliente = $("#cliente_input").val();
    var estado = $("#estado_input").val();
    /*  var fechaInic = $("#fechaInic_input").val();
    var fechaFin = $("#fechaFin_input").val(); */
    let fechaInic = $("#fechaInic_input").val();
    let fechaFin = $("#fechaFin_input").val();
    if (fechaInic == "") {
      fechaInic = "000";
    }
    if (fechaFin == "") {
      fechaFin = "000";
    }
    var mes = $("#cmbMesV").val();
    if (mes.length === 0) {
      mes = "010";
    }

    if (!vendedor) {
      $("#invalid-vendedor").css("display", "block");
      //$("#vendedor_input").addClass("is-invalid");
    }
    if (!cliente) {
      $("#invalid-cliente").css("display", "block");
      //$("#cliente_input").addClass("is-invalid");
    }
    if (!estado) {
      $("#invalid-estado").css("display", "block");
      //$("#estado_input").addClass("is-invalid");
    }
    /*     if (!fechaInic) {
      $("#invalid-fechaInic").css("display", "block");
      $("#fechaInic_input").addClass("is-invalid");
    }
    if (!fechaFin) {
      $("#invalid-fechaFin").css("display", "block");
      $("#fechaFin_input").addClass("is-invalid");
    } */

    var badVendedor =
      $("#invalid-vendedor").css("display") === "block" ? false : true;
    var badCliente =
      $("#invalid-cliente").css("display") === "block" ? false : true;
    var badEstado =
      $("#invalid-estado").css("display") === "block" ? false : true;
    /*     var badFechaInic =
      $("#invalid-fechaInic").css("display") === "block" ? false : true;
    var badFechaFin =
      $("#invalid-fechaFin").css("display") === "block" ? false : true; */

    if (
      badVendedor &&
      badCliente &&
      badEstado &&
      /* badFechaInic &&
      badFechaFin && */

      vendedor &&
      cliente &&
      estado 
/*       fechaInic &&
      fechaFin */
    ) {
      $.ajax({
        method: "POST",
        url: "../../ventas_directas/php/funciones.php",
        data: {
          clase: "get_data",
          funcion: "get_reportes_Table",
          vendedor,
          estado,
          cliente,
          fechaInic,
          fechaFin,
          mes,
        },
        dataType: "json",
        success: function (response) {
          if (response) {
            createDataTable(response.datatable);
            getTotals(vendedor,estado,cliente,fechaInic,fechaFin,mes);
            createChart(response.chart);
          }
        },
        error: function (error) {},
      });
    }
  });

function createDataTable(response) {
    if ($("#tblReporteVentas").hasClass("d-none")) {
      $("#tblReporteVentas").removeClass("d-none");
    }
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
    });
    $("#tblReporteVentas").DataTable().destroy();
    $("#tblReporteVentas").DataTable({
      language: {
        sProcessing: "Procesando...",
        sZeroRecords: "No se encontraron resultados",
        sEmptyTable: "Ningún dato disponible en esta tabla",
        sSearch: "<img src='../../../img/timdesk/buscar.svg' width='20px' />",
        sLoadingRecords: "Cargando...",
        searchPlaceholder: "Buscar...",
        oPaginate: {
          sFirst: "Primero",
          sLast: "Último",
          sNext: "<i class='fas fa-chevron-right'></i>",
          sPrevious: "<i class='fas fa-chevron-left'></i>",
        },
      },
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
            extend: "excelHtml5",
            text: '<span class="d-flex align-items-center"><img src="../../../img/icons/ICONO DESCARGAR2_Mesa de trabajo 1.svg" width="20" class="mr-1"> Descargar excel</span>',
            className: "btn-custom--white-dark",
            titleAttr: "Excel",
          },
        ],
      },
      data: response,
      columns: [
        { data: "vendedor" },
        { data: "cliente" },
        { data: "importe" },
        { data: "total_pendiente" },
        { data: "total_global" },
        { data: "estado" },
        { data: "mes" },
        { data: "acciones", width: "5%" },
      ],
      "columnDefs": [
        
        { width: "45%", targets: 1 },
        { width: "15%", targets: [0,2,3,4] },
      ]
    });
  }

  function createChart(response) {
    
    var containerCharts = document.getElementById("container-canvas");
    var lastChild = containerCharts.lastElementChild;
    while (lastChild) {
      containerCharts.removeChild(lastChild);
      lastChild = containerCharts.lastElementChild;
    }

    const labels = response.labels;
    for (const key in response.data) {
      var col = document.createElement("div");
      var card = document.createElement("div");
      var card_header = document.createElement("div");
      card_header.innerHTML = '<span class="m-0">Ventas por ' + key+'</span>';
      var card_body = document.createElement("div");
      col.className = "col-6";
      card.className = "card";
      card.style.border = '0.5px solid #006dd9';
      card_header.className = "card-header title-card";
      card_body.className = "card-body"
      containerCharts.insertAdjacentElement("beforeend", col);
      col.insertAdjacentElement("beforeend", card);
      card.insertAdjacentElement("beforeend", card_header);
      card.insertAdjacentElement("beforeend", card_body);
      var canvas = document.createElement("canvas");
      card_body.insertAdjacentElement("beforeend", canvas);

      new Chart(canvas, {
        type: "bar",
        data: {
          labels: labels,
          datasets: response.data[key],
        },
        options: {
          responsive: true,
          maintainAspectRatio: true,
          plugins: {
            legend: {
              position: "top",
            },
            title: {
              display: true,
              text: "",
            },
          },
        },
      });
    }
  }

  function createChartSales(response) {
    
    var containerCharts = document.getElementById("container-canvas1");
    var lastChild = containerCharts.lastElementChild;
    while (lastChild) {
      containerCharts.removeChild(lastChild);
      lastChild = containerCharts.lastElementChild;
    }

    //const labels = response.labels;
    var col = document.createElement("div");
    var card = document.createElement("div");
    var card_header = document.createElement("div");
    card_header.innerHTML = '<span class="m-0">Ventas por mes</span>';
    var card_body = document.createElement("div");
    col.className = "col-6";
    containerCharts.insertAdjacentElement("beforeend", col);
    card.className = "card";
    card.style.border = '0.5px solid #006dd9';
    card_header.className = "card-header title-card";
    card_body.className = "card-body"
    containerCharts.insertAdjacentElement("beforeend", col);
    col.insertAdjacentElement("beforeend", card);
    card.insertAdjacentElement("beforeend", card_header);
    card.insertAdjacentElement("beforeend", card_body);
    var canvas = document.createElement("canvas");
    card_body.insertAdjacentElement("beforeend", canvas);

    new Chart(canvas, {
        type: "bar",
        data: {
          labels: response.labels,
          datasets: response.datos,
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: "top",
                },
                title: {
                    display: true,
                    text: "",
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
    
                            if (label) {
                                label += ': ';
                            }
                            if (context.parsed.y !== null) {
                                label += new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format(context.parsed.y);
                            }
                            return label;
                        }
                    }
                }
            },
        },
      });
    
  }

  function getTotals(vendedor,estado,cliente,fechaInic,fechaFin,mes)
  {
    $.ajax({
        method: "POST",
        url: "../../ventas_directas/php/funciones.php",
        data: {
          clase: "get_data",
          funcion: "get_totals_Table",
          vendedor:vendedor,
          estado:estado,
          cliente:cliente,
          fechaInic:fechaInic,
          fechaFin:fechaFin,
          mes:mes,
        },
        dataType: "json",
        success: function (response) {
          if (response) {
            $(".total_facturado").html(response.total_facturado);
            $(".total_no_facturado").html(response.total_no_facturado);
            $(".total_global").html(response.total_global);
          }
        },
        error: function (error) {},
      });
  }

function createTableBegin(){
   
    var today = new Date();
    var lastMonth = new Date();
    var mes = "010";

    lastMonth.setMonth(today.getMonth() - 1);
    var fechaInic = lastMonth
        .toLocaleDateString()
        .split("/")
        .reverse()
        .join("-");
    var fechaFin = today.toLocaleDateString().split("/").reverse().join("-");
    $.ajax({
        method: "POST",
        url: "../../ventas_directas/php/funciones.php",
        data: {
        clase: "get_data",
        funcion: "get_reportes_Table",
        vendedor: "todos",
        cliente: "todos",
        estado: "todos",
        fechaInic,
        fechaFin,
        mes,
        },
        dataType: "json",
        success: function (response) {
            if (response) {
                createDataTable(response.datatable);
                getTotals("todos","todos","todos",fechaInic,fechaFin,mes);
                createChart(response.chart);
            }
        },
        error: function (error) {},
    });
}

function loadChartSales()
{
    $.ajax({
        method:'post',
        url:'php/controller',
        data: {
            clase:"get_data",
            funcion:'get_charts_sales',
        },
        dataType:"json",
        success: function(response)
        {
            createChartSales(response);
        }
    });
}