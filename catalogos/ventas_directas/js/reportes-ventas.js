$(document).ready(function () {
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

  $("#fitro_reportes").click(function () {
    var vendedor = $("#vendedor_input").val();
    var cliente = $("#cliente_input").val();
    var estado = $("#estado_input").val();
    var fechaInic = $("#fechaInic_input").val();
    var fechaFin = $("#fechaFin_input").val();

    if (!vendedor) {
      $("#invalid-vendedor").css("display", "block");
      $("#vendedor_input").addClass("is-invalid");
    }
    if (!cliente) {
      $("#invalid-cliente").css("display", "block");
      $("#cliente_input").addClass("is-invalid");
    }
    if (!estado) {
      $("#invalid-estado").css("display", "block");
      $("#estado_input").addClass("is-invalid");
    }
    if (!fechaInic) {
      $("#invalid-fechaInic").css("display", "block");
      $("#fechaInic_input").addClass("is-invalid");
    }
    if (!fechaFin) {
      $("#invalid-fechaFin").css("display", "block");
      $("#fechaFin_input").addClass("is-invalid");
    }

    var badVendedor =
      $("#invalid-vendedor").css("display") === "block" ? false : true;
    var badCliente =
      $("#invalid-cliente").css("display") === "block" ? false : true;
    var badEstado =
      $("#invalid-estado").css("display") === "block" ? false : true;
    var badFechaInic =
      $("#invalid-fechaInic").css("display") === "block" ? false : true;
    var badFechaFin =
      $("#invalid-fechaFin").css("display") === "block" ? false : true;

    if (
      badVendedor &&
      badCliente &&
      badEstado &&
      badFechaInic &&
      badFechaFin &&
      vendedor &&
      cliente &&
      estado &&
      fechaInic &&
      fechaFin
    ) {
      $.ajax({
        method: "POST",
        url: "../../php/funciones.php",
        data: {
          clase: "get_data",
          funcion: "get_reportes_Table",
          vendedor,
          estado,
          cliente,
          fechaInic,
          fechaFin,
        },
        dataType: "json",
        success: function (response) {
          if (response) {
            createDataTable(response.datatable);
            createChart(response.chart);
          }
        },
        error: function (error) {
        },
      });
    }
  });

  function createDataTable(response) {
    if ($("#tblReporteVentas").hasClass("d-none")) {
      $("#tblReporteVentas").removeClass("d-none");
    }

    $("#tblReporteVentas").DataTable().destroy();
    $("#tblReporteVentas").DataTable({
      language: {
        sProcessing: "Procesando...",
        sZeroRecords: "No se encontraron resultados",
        sEmptyTable: "Ningún dato disponible en esta tabla",
        sSearch:
          "<img src='../../../../img/timdesk/buscar.svg' width='20px' />",
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
            className: "btn-table-custom",
          },
          buttonLiner: {
            tag: null,
          },
        },
        buttons: [
          {
            extend: "excelHtml5",
            text: '<i class="fas fa-cloud-download-alt"></i> Descargar excel',
            className: "btn-table-custom--turquoise",
            titleAttr: "Excel",
          },
        ],
      },
      data: response,
      columns: [
        { data: "vendedor" },
        { data: "cliente" },
        { data: "importe" },
        { data: "acciones", width: "5%" },
      ],
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
      col.className = "col-6";
      containerCharts.insertAdjacentElement("beforeend", col);
      var canvas = document.createElement("canvas");
      col.insertAdjacentElement("beforeend", canvas);
      
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
              text: "Ventas por " + key,
            },
          },
        },
      });
    }
  }

  (function () {
    var today = new Date();
    var lastMonth = new Date();
    lastMonth.setMonth(today.getMonth() - 1);
    var fechaInic = lastMonth
      .toLocaleDateString()
      .split("/")
      .reverse()
      .join("-");
    var fechaFin = today.toLocaleDateString().split("/").reverse().join("-");
    $.ajax({
      method: "POST",
      url: "../../php/funciones.php",
      data: {
        clase: "get_data",
        funcion: "get_reportes_Table",
        vendedor: "todos",
        cliente: "todos",
        estado: "todos",
        fechaInic,
        fechaFin,
      },
      dataType: "json",
      success: function (response) {
        if (response) {
          createDataTable(response.datatable);
          createChart(response.chart);
        }
      },
      error: function (error) {
      },
    });
  })();
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
    item.classList.add("is-invalid");
    invalidDiv.style.display = "block";
  } else {
    item.classList.remove("is-invalid");
    invalidDiv.style.display = "none";
  }
}
