const ventas = document.getElementById('ventasTotalNet');
const gastos = document.getElementById('gastosTotalNet');
const total_venta_gasto = document.getElementById('totalNet');
const inventario = document.getElementById('valorInventarioTotalNet');
const cuentas_cobrar = document.getElementById('cuentasCobrarTotalNet');
const cuentas_bancarias = document.getElementById('cuentasBancariasTotalNet');
const cuentas_pagar = document.getElementById('cuentasPagarTotalNet');
const cuentas_credito = document.getElementById('cuentasCreditoTotalNet');
const total_capital = document.getElementById('TotalCapitalTrabajoNet');

$.post('php/functions.php',
    {
        clase: "get_data",
        funcion: "get_charts",
    },
    function(response){
        createChart(response);
    }
);

function generalFilterData()
{
    var initialDate = document.getElementById('txtGeneralInitialDate').value;
    var finalDate = document.getElementById('txtGeneralFinalDate').value;
    
    ventas.value = "";
    $.post('php/functions.php',
        {
            clase: "get_data",
            funcion: "get_generalFilterData",
            initialDate:initialDate,
            finalDate:finalDate
        },
        function(response){
            res = JSON.parse(response);
            ventas.innerHTML = res.total_ventas;
            gastos.innerHTML = res.total_gastos;
            total_venta_gasto.innerHTML = res.total_general;
            inventario.innerHTML = res.inventario;
            cuentas_cobrar.innerHTML = res.cuentas_cobrar;
            cuentas_bancarias.innerHTML = res.cuentasBancarias;
            cuentas_pagar.innerHTML = res.cuentas_pagar;
            cuentas_credito.innerHTML = res.cuentasCredito;
            total_capital.innerHTML = res.total_general_capital;

            document.getElementById('txtGeneralInitialDate').value = "";
            document.getElementById('txtGeneralFinalDate').value = "";       
        }
    );
}

function createChartFilter()
{
    var initialDate = document.getElementById('txtInitialDate').value;
    var finalDate = document.getElementById('txtFinalDate').value;

    $.post('php/functions.php',
        {
            clase: "get_data",
            funcion: "get_chartsFilter",
            initialDate:initialDate,
            finalDate:finalDate
        },
        function(response){
            createChart(response);
        }
    );
}

function createChart(response)
{
    re = JSON.parse(response);
    var containerCharts = document.getElementById("container-canvas");
    var lastChild = containerCharts.lastElementChild;
    while (lastChild) {
        containerCharts.removeChild(lastChild);
        lastChild = containerCharts.lastElementChild;
    }

    const labels = re.labels;

    var col = document.createElement("div");
    col.className = "col-6";
    containerCharts.insertAdjacentElement("beforeend", col);
    var canvas = document.createElement("canvas");
    col.insertAdjacentElement("beforeend", canvas);

    console.log(re.dataset);

    new Chart(canvas, {
        type: "bar",
        data: {
            labels: labels,
            datasets: re.dataset
            
        },
        options: {
            responsive: true,

            plugins: {
                legend: {
                    position: "top",
                },
                title: {
                    display: true,
                    text: "Flujo de efectivo",
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

var flag_dowload_expensiveReport = true;
function validateYear(year){
    let val = year.value;

    let regexp = /^(?:\d{4})$/;
    if (val.match(regexp) || val=='') {
        $("#txtExpensiveReport_year").removeClass("is-invalid");
        $("#invalid-txtYear").text('');
        if(val==''){
            year.value='';
        }
        slimMes.destroy();
        $("#cmbMes option:selected").prop("selected", false);
        slimMes = new SlimSelect({
            select: "#cmbMes",
        });
        $(".dateRange").val(null);
        flag_dowload_expensiveReport = true
    }else{
        $("#txtExpensiveReport_year").addClass("is-invalid");
        $("#invalid-txtYear").text('Ingrese un año válido');
        flag_dowload_expensiveReport = false
    }
}

function disableWarningYear(){
    $("#txtExpensiveReport_year").val('');
    $("#txtExpensiveReport_year").removeClass("is-invalid");
    $("#invalid-txtYear").text('');
    flag_dowload_expensiveReport = true;
}
// cambiar valores

$(".dateRange").change(function () {
    slimMes.destroy();
    $("#cmbMes option:selected").prop("selected", false);
    slimMes = new SlimSelect({
        select: "#cmbMes",
    });

    disableWarningYear();
});

$("#cmbMes").change(function (e) {
    e.preventDefault();
    if ($(this).val()) {
      $(".dateRange").val(null);
    }

    disableWarningYear();
});

function generateExpensiveReport(){
    let initialDate = document.getElementById('txtExpensiveReport_InitialDate').value;
    let finalDate = document.getElementById('txtExpensiveReport_FinalDate').value;
    let year =  document.getElementById('txtExpensiveReport_year').value;
    let month =  $('#cmbMes').val();
    let isContinue = true;

    if( initialDate == '' && finalDate == '' && year == '' && month.length <= 0){
        Lobibox.notify("warning", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/warning_circle.svg",
            msg: "¡Campos vacios!",
          });
          return;
    }

    if(flag_dowload_expensiveReport && isContinue){
     
        $().redirect(
            'php/functions.php',
            {
            'clase': 'get_data',
            'funcion': 'get_generateExpenseReport',
            'year': year,
            'month' : month,
            'initialDate' : initialDate,
            'finalDate' : finalDate
            },
        "POST");
    }else{
        Lobibox.notify("warning", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/warning_circle.svg",
            msg: "¡Ingrese un año válido!",
          });
    }
}

function downloadUtilities()
{
    txtUtilitiesInitialDate = document.getElementById('txtUtilitiesInitialDate').value;
    txtUtilitiesFinalDate = document.getElementById('txtUtilitiesFinalDate').value;
    console.log(txtUtilitiesInitialDate);
    console.log(txtUtilitiesFinalDate);
    
    window.open('php/download_utilities.php?initialDate='+txtUtilitiesInitialDate+'&finalDate='+txtUtilitiesFinalDate, '_blank');
    
    document.getElementById('txtUtilitiesInitialDate').value = "";
    document.getElementById('txtUtilitiesFinalDate').value = "";
}
