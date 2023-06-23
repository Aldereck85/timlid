$(document).ready(function () {
  var rol = document.getElementById("rol").value;
  var mesVentasDona = document.getElementById("inputMesEmpleados");

  $("#notas-usuario").change(function () {
    $.ajax({
      method: "POST",
      url: "../php_dashboard/funciones.php",
      data: {
        clase: "set_data",
        funcion: "set_nota",
        nota: $("#notas-usuario").val(),
      },
      success: function (res) {},
      error: function (e) {},
    });
  });

  $.ajax({
    url: "../php_dashboard/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_nota",
    },
    success: function (res) {
      var result = JSON.parse(res);
      $("#notas-usuario").val(result.Notas);
    },
  });

  $.ajax({
    url: "../php_dashboard/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_ventas_anio",
    },
    success: function (res) {
      var ctx = document.getElementById("myBarChart");
      var result = JSON.parse(res);
      var color = getColor();
      var meses = [];
      var cantidad = [];

      result.forEach((item) => {
        cantidad.push(parseInt(item["ImporteTotal"]));
        switch (item["Mes"]) {
          case 1:
            meses.push("Enero");
            break;
          case 2:
            meses.push("Febrero");
            break;
          case 3:
            meses.push("Marzo");
            break;
          case 4:
            meses.push("Abril");
            break;
          case 5:
            meses.push("Mayo");
            break;
          case 6:
            meses.push("Junio");
            break;
          case 7:
            meses.push("Julio");
            break;
          case 8:
            meses.push("Agosto");
            break;
          case 9:
            meses.push("Septiembre");
            break;
          case 10:
            meses.push("Octubre");
            break;
          case 11:
            meses.push("Noviembre");
            break;
          case 12:
            meses.push("Diciembre");
            break;
          default:
            break;
        }
      });

      new Chart(ctx, {
        type: "bar",
        data: {
          labels: meses,
          datasets: [
            {
              label: "Ventas",
              backgroundColor: color,
              borderColor: color,
              data: cantidad,
            },
          ],
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              position: "top",
            },
            title: {
              display: true,
              text: "Ventas por del año",
            },
          },
        },
      });
    },
  });

  $.ajax({
    url: "../php_dashboard/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_ventas_anio_empleados",
    },
    success: function (res) {
      var ctx = document.getElementById("myBarChart2");
      var result = JSON.parse(res);
      var labels = [];
      var colors = [];
      var datos = [];

      result.forEach((item, index) => {
        labels.push(item["nombre"]);
        datos.push(parseFloat(item["ImporteTotal"]));
        colors.push(getColor(index));
      });

      new Chart(ctx, {
        type: "doughnut",
        data: {
          labels,
          datasets: [
            {
              label: "Ventas",
              data: datos,
              borderColor: colors,
              backgroundColor: colors,
            },
          ],
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              position: "top",
            },
            title: {
              display: true,
              text: "Ventas por del año",
            },
          },
        },
      });
    },
  });

  function loadVentasMesDona(mes = "") {
    $.ajax({
      url: "../php_dashboard/funciones.php",
      data: {
        mes,
        clase: "get_data",
        funcion: "get_ventas_mes_empleados",
      },
      success: function (res) {
        var canvasContainer = document.getElementById("canvas-container");
        canvasContainer.removeChild(canvasContainer.firstChild);
        var canv = document.createElement("canvas");
        canvasContainer.appendChild(canv);
        var result = JSON.parse(res);
        var labels = [];
        var colors = [];
        var datos = [];

        result.forEach((item, index) => {
          labels.push(item["nombre"]);
          datos.push(parseFloat(item["ImporteTotal"]));
          colors.push(getColor(index));
        });

        new Chart(canv, {
          type: "doughnut",
          data: {
            labels,
            datasets: [
              {
                label: "Ventas",
                data: datos,
                borderColor: colors,
                backgroundColor: colors,
              },
            ],
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
              legend: {
                position: "top",
              },
              title: {
                display: true,
                text: "Ventas del mes",
              },
            },
          },
        });
      },
    });
  }

  mesVentasDona.addEventListener("change", function (e) {
    loadVentasMesDona(e.target.value);
  });

  (function () {
    loadVentasMesDona();
  })();

  function generateRandomColor() {
    var maxVal = 0x51cbd1; // 16777215
    var randomNumber = Math.random() * maxVal;
    randomNumber = Math.floor(randomNumber);
    randomNumber = randomNumber.toString(16);
    var randColor = randomNumber.padStart(6, 0);
    return "#" + randColor.toUpperCase();
  }

  function generateDarkColorHex() {
    let color = "#";
    for (let i = 0; i < 3; i++)
      color += (
        "a" + Math.floor((Math.random() * Math.pow(16, 2)) / 2).toString(16)
      ).slice(-2);
    return color;
  }
  function getColor(index = 0) {
    var colors = [
      "bbe2f9",
      "ffd237",
      "ef7f58",
      "00a4d7",
      "33368b",
      "826ea8",
      "1e7496",
      "d3aa81",
      "c65e42",
      "114c56",
      "181e4c",
      "4a2975",
    ];
    var index = index < 12 ? index : Math.floor(Math.random() * colors.length);
    var color = colors[index];
    return "#" + color;
    return (
      "hsl(" +
      360 * Math.random() +
      "," +
      (35 + 70 * Math.random()) +
      "%," +
      (70 + 10 * Math.random()) +
      "%)"
    );
  }
});
