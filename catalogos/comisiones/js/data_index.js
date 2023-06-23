//Se declara una variable global para saber si se ha aplicado un filtro para el estatus o no
var fEstatus = 0; 


//Función para eliminar el cálculo 
function eliminaCalculo(idCom) {
  $("#mdleliminarcalculo").modal('show');

  $("#btnModEliminarCal").off("click").click(function () {
    $("#mdleliminarcalculo").modal('hide');
    $.ajax({
      async: false,
      type: "POST",
      url: "../comisiones/functions/function_eliminarCalculo.php",
      dataType: "json",
      data: {
        idComision:idCom,
      },
      success: function (data) {
        if(data["result"] == 1){
          Lobibox.notify("success", {
            size: "mini",
            rounded: true,
            delay: 2000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/checkmark.svg",
            msg: "¡Cálculo de comisión eliminado con éxito!",
          });
          location.reload();
        } else {
          Lobibox.notify("warning", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/warning_circle.svg",
            msg: "¡Algo salió mal al eliminar el cálculo!, " + data["result"],
          });
        }
      },
      error: function (error) {
        console.log("Error: "+error);
      },
    });
  });
}


//Función para cargar el select de los vendedores
function carga_cmbVendedores() {
  html='';
  $.ajax({
    async: false,
    type: "POST",
    url: "../comisiones/functions/function_cmbVendedor.php",
    dataType: "json",
    success: function (data) {
      $.each(data, function (i) {
        html +=
            '<option value="' +
            data[i].PKVendedor +
            '">' +
            data[i].Nombre +
            "</option>";
      });
      $("#chosenVendedores").append(html);
    },
    error: function (error) {
      console.log("Error: "+error);
    },
  });
}

//Función para darle formato a las cantidades
function formatoCantidad(cantidad) {
  let fc = parseFloat(cantidad);
  let redon = Math.round((fc + Number.EPSILON)*100)/100;
  var validarNumero = (redon.toFixed(2)).toString().split(".");
  //Si no tenía ningún decimal le agrega 00
  validarNumero[1] = validarNumero[1] == undefined ? "00" : validarNumero[1];
  // Si tenía solo un decimal le agrega un 0 al final
  validarNumero[1] = validarNumero[1].length == 1 ? validarNumero[1] + "0" : validarNumero[1];
  //Le da cierto formato a la cantidad
  validarNumero[0] = validarNumero[0].replace(/\B(?=(\d{3})+(?!\d))/g, " ");
  return validarNumero.join(".");
}



$(document).ready(function () {

  document.getElementById("btnMostrarTCalculos").style.display = "none";
  document.getElementById("btnVerTotales").style.display = "none";

  carga_cmbVendedores();
  
  //Cuando cambia la selección del vendedor se filtra
  $("#chosenVendedores").on("change", function () {
    $("#btnFilterExits").click();
  });
   
  $("#btnFilterExits").on("click", function (e) {
    seleccion = document.getElementById("chosenVendedores").value;
    fecha_desde = document.getElementById("txtDateFrom").value;
    fecha_hasta = document.getElementById("txtDateTo").value;
  });
  
  let espanol = {
    sProcessing: "Procesando...",
    sZeroRecords: "No se encontraron resultados",
    sEmptyTable: "Ningún dato disponible en esta tabla",
    sSearch: '<img src="../../img/timdesk/buscar.svg" width="20px" />',
    sLoadingRecords: "Cargando...",
    searchPlaceholder: "Buscar...",
    oPaginate: {
      sFirst: "Primero",
      sLast: "Último",
      sNext: "<i class='fas fa-chevron-right'></i>",
      sPrevious: "<i class='fas fa-chevron-left'></i>",
    },
    searchBuilder: {
      add: "Filtros",
      condition: "Condición",
      conditions: {
        string: {
          contains: "Contiene",
          empty: "Vacio",
          endsWith: "Finaliza con",
          equals: "Igual",
          not: "Diferente",
          notEmpty: "No vacío",
          startsWith: "Comienza con",
        },
        date: {
          after: "Después de",
          before: "Antes de",
          between: "Entre",
          empty: "Vacio",
          equals: "Igual",
          not: "Diferente",
          notBetween: "No está entre",
          notEmpty: "No vacío",
        },
        number: {
          between: "Between",
          empty: "Vacio",
          equals: "Igual",
          gt: "Mayor que",
          gte: "Mayor o igual que",
          lt: "Menor que",
          lte: "Menor o igual que",
          not: "Diferente",
          notBetween: "No está entre",
          notEmpty: "No vacío",
        },
        array: {
          contains: "Contiene",
          empty: "Vacio",
          equals: "Igual",
          not: "Diferente",
          notEmpty: "No vacío",
          without: "Sin",
        },
      },
      clearAll: "Limpiar",
      deleteTitle: "Eliminar",
      data: "Columna",
      leftTitle: "Izquierda",
      logicAnd: "+",
      logicOr: "o",
      rightTitle: "Derecha",
      title: {
        0: "Filtros",
        _: "Filtros (%d)",
      },
      value: "Opción",
      valueJoiner: "et",
    },
  };
  var topButtons = [{
    text: '<span class="d-flex align-items-center"><img src="../../img/icons/ICONO_AGREGAR_DARK.svg" width="20" class="mr-1"> Añadir registro</span>',
    className: "btn-custom--white-dark",
    action: function () {
      window.location.href = "nuevo_calculo.php";
    },
  },];
  topButtons.push({
    extend: "excelHtml5",
    text: '<span class="d-flex align-items-center"><img src="../../img/icons/ICONO DESCARGAR2_Mesa de trabajo 1.svg" width="20" class="mr-1"> Descargar excel</span>',
    className: "btn-custom--white-dark",
  });
  tablaC = $("#tblcomisiones").DataTable({
    language: espanol,
    restrieve: true,
    destroy: true,
    info: false,
    scrollX: true,
    bSort: false,
    pageLength: 15,
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
      url: "functions/getCalculos.php",
      async:false,
      dataType: "json",
    },
    columns: [
      { data: "Folio" },
      { data: "Fecha" },
      { data: "Vendedor" },
      { data: "Monto calculado" },
      { data: "Monto ingresado" },
      { data: "Porcentaje de comision" },
      { data: "Saldo insoluto" },
      { data: "Estado", width: "220px"},
    ],
    columnDefs: [
      {
          targets: [1,4,5,6],
          searchable: false,
      },
      {
        targets: [3,4,5,6],
        className: 'dt-center',
      }
    ],
  });
  
  //activa los tooltips en datatable
  $('#tblcomisiones tbody').on('mouseover', 'tr', function () {
    $('[data-toggle="tooltip"]').tooltip({
        trigger: 'hover',
        html: true
    });
    $('[data-toggle="tooltip"]').on("click", function () {
      $(this).tooltip("dispose");
    });
  });
  
  $(function () {
    $("[data-toggle='tooltip']").tooltip();
  });

  $("#chosenVendedores").on("change", function () {
    $("#btnFilterExits").click();
    document.getElementById("btnVerTotales").style.display = "inline-block";
  });

  $("#btnFilterExits").on("click", function (e) {
    var fVendedor = document.getElementById("chosenVendedores").value;
    var fFechaDesde = document.getElementById("txtDateFrom").value;
    var fFechaHasta = document.getElementById("txtDateTo").value;

    if((fVendedor == "f") && (fFechaDesde == "") && (fFechaHasta == "")){
      Lobibox.notify("warning", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../img/timdesk/warning_circle.svg",
        msg: "¡Ingrese al menos un dato para filtrar!",
      });
    } else {
      tablaC.ajax.url("functions/function_recuperarCalculosFiltrado.php?fVendedor="+fVendedor+"&fFechaDesde="+fFechaDesde+"&fFechaHasta="+fFechaHasta+"&fEstatus="+fEstatus).load();
      document.getElementById("btnMostrarTCalculos").style.display = "inline-block";
    }
  });

  $("#btnMostrarTCalculos").on("click", function (e) {
    $("#chosenVendedores").val("f");
    carga_cmbVendedores();
    $("#txtDateFrom").val("");
    $("#txtDateTo").val("");
    tablaC.ajax.url("functions/getCalculos.php").load();
    document.getElementById("btnMostrarTCalculos").style.display = "none";
    document.getElementById("btnVerTotales").style.display = "none";
    fEstatus=0;
  });
  
  $("#btnVerTotales").on("click", function (e) {
    $("#mdlTotalesVendedor").modal('show');

    var cVendedores = document.getElementById("chosenVendedores");
    var mdlVendedor = cVendedores.options[cVendedores.selectedIndex].text;
    var mdlIDVendedor = cVendedores.options[cVendedores.selectedIndex].value;

    $("#txtMdlVendedor").val(mdlVendedor);

    $.ajax({
      async: false,
      type: "POST",
      url: "../comisiones/functions/function_recuperarTotales.php",
      dataType: "json",
      data: {
        idVendedor:mdlIDVendedor,
      },
      success: function (data) {
        var totalSIPendiente = 0;
        var totalSIParcial = 0;

        if(data["totalComPagadas"]!=null){
          $("#txtModTotalComPagadas").val(formatoCantidad(data["totalComPagadas"]));
        } else {
          $("#txtModTotalComPagadas").val(formatoCantidad(0));
        }

        if(data["totalSIPendiente"]!=null){
          totalSIPendiente = parseFloat(data["totalSIPendiente"]);
        }
        if (data["totalSIParcial"]!=null){
          totalSIParcial = parseFloat(data["totalSIParcial"]);
        }

        var totalSaldoInsoluto = totalSIPendiente + totalSIParcial;
        $("#txtModTotalSaldoInsoluto").val(formatoCantidad(totalSaldoInsoluto));
        
      },
      error: function (error) {
        console.log("Error: "+error);
      },
    });

    var idioma_espanol = {
      sProcessing: "Procesando...",
      sZeroRecords: "No se encontraron resultados",
      sEmptyTable: "Ningún dato disponible en esta tabla",
      sSearch: "<img src='../../img/timdesk/buscar.svg' width='20px' />",
      sLoadingRecords: "Cargando...",
      searchPlaceholder: "Buscar...",
      oPaginate: {
        sFirst: "Primero",
        sLast: "Último",
        sNext: "<i class='fas fa-chevron-right'></i>",
        sPrevious: "<i class='fas fa-chevron-left'></i>",
      },
    };
    
    var topButtons = [];
    tablaCalVendedor=$("#tblModCalVendedor").DataTable({
      language: idioma_espanol,
      destroy: true,
      info: false,
      scrollX: true,
      bSort: false,
      pageLength: 15,
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
        buttons: [],
      },
      ajax: {
        method: "POST",
        url: "functions/function_getCalculosVendedor.php",
        data: {idVendedor:mdlIDVendedor},
      },
      columns: [
        { data: "folio" },
        { data: "fecha"  },
        { data: "monto_calculado" },
        { data: "monto_ingresado" },
        { data: "saldo_insoluto" },
        { data: "estatus", className: "text-left" },
      ],
    });
  });

  $("#btnModCerrar").off("click").click(function () {
    $("#mdlTotalesVendedor").modal('hide');
  });
});

