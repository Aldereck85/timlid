/*funcion para verificar la seleccion del combo
  si es diferene a 3 se utiliza la misma tabla de clientes para pintar los datos
  si no se utiliza la tabla histórico*/
function verifica(selec){
  if (selec != 3) {
    $("#clientes").show();
    $("#historico").hide();
    $(".groupFiltro").hide();
    $(".totalCuentas").show();
    $("#separate").addClass('col-7');
    tablaC.ajax.url("functions/get_periodos.php?combo=" + selec).load();
    $.post("functions/get_totales",{
        selection:selec,
        fecha_desde : "",
        fecha_hasta : ""
        },function(response){
            var res = JSON.parse(response);
            $('#txt_total_facturado').html(res.total_facturado);
            $('#txt_total_noFacturado').html(res.total_noFacturado);
        }
    );
    return true;
  } else {
    $("#clientes").hide();
    $("#historico").show();
    $(".groupFiltro").show();
    $(".totalCuentas").show();
    $("#separate").removeClass('col-7');
    limpiaFiltrador();
    tablaHistorico.ajax
      .url("functions/get_periodos.php?combo=" + selec)
      .load();
    $.post("functions/get_totales",{
        selection:selec,
        fecha_desde : "",
        fecha_hasta : ""
        },function(response){
            var res = JSON.parse(response);
            $('#txt_total_facturado').html(res.total_facturado);
            $('#txt_total_noFacturado').html(res.total_noFacturado);
        }
    );
    return false;
  }
}

function limpiaFiltrador(){
  //$("#lblTotal").text(0);
  //$("#totalCuentas").addClass("oculto");
  $("#txtClientes").val("");
  $("#txtDateFrom").val("");
  $("#txtDateTo").val("");
}

var idioma_espanol = {
  sProcessing: "Procesando...",
  sZeroRecords: "No se encontraron resultados",
  sEmptyTable: "Ningún dato disponible en esta tabla",
  sSearch: "<img src='../../img/timdesk/buscar.svg' width='20px' />",
  sLoadingRecords: "<img src='../../img/timdesk/Preloader.gif' width='100px' style='transition-duration:300ms;' />",
  searchPlaceholder: "Buscar...",
  oPaginate: {
    sFirst: "Primero",
    sLast: "Último",
    sNext: "<i class='fas fa-chevron-right'></i>",
    sPrevious: "<i class='fas fa-chevron-left'></i>",
  },
};
var topButtonsCliente = [
  {
    text: '<span class="d-flex align-items-center"><img src="../../img/icons/ICONO_CHECK_DARK.svg" width="20" class="mr-1"> Cuentas al corriente</span>',
    className: "btn-custom--white-dark",
    action: function (e, dt, node, config) {
      document.getElementById("comboIndex").options.item(0).selected =
        "selected";
      $("#comboIndex").trigger("change");
    },
  },
  {
    text: '<span class="d-flex align-items-center"><img src="../../img/icons/ICONO_CERRAR_DARK.svg" width="20" class="mr-1"> Cuentas vencidas</span>',
    className: "btn-custom--white-dark",
    action: function (e, dt, node, config) {
      document.getElementById("comboIndex").options.item(1).selected =
        "selected";
      $("#comboIndex").trigger("change");
    },
  },
  {
    text: '<span class="d-flex align-items-center"><img src="../../img/icons/ICONO LISTADO DE MARCAS_DARK.svg" width="20" class="mr-1"> Historico</span>',
    className: "btn-custom--white-dark",
    action: function (e, dt, node, config) {
      document.getElementById("comboIndex").options.item(2).selected =
        "selected";
      $("#comboIndex").trigger("change");
    },
  },
];
var topButtonsHistorico = [
  {
    text: '<span class="d-flex align-items-center"><img src="../../img/icons/ICONO_CHECK_DARK.svg" width="20" class="mr-1"> Cuentas al corriente</span>',
    className: "btn-custom--white-dark",
    action: function (e, dt, node, config) {
      document.getElementById("comboIndex").options.item(0).selected =
        "selected";
      $("#comboIndex").trigger("change");
    },
  },
  {
    text: '<span class="d-flex align-items-center"><img src="../../img/icons/ICONO_CERRAR_DARK.svg" width="20" class="mr-1"> Cuentas vencidas</span>',
    className: "btn-custom--white-dark",
    action: function (e, dt, node, config) {
      document.getElementById("comboIndex").options.item(1).selected =
        "selected";
      $("#comboIndex").trigger("change");
    },
  },
  {
    text: '<span class="d-flex align-items-center"><img src="../../img/icons/ICONO LISTADO DE MARCAS_DARK.svg" width="20" class="mr-1"> Historico</span>',
    className: "btn-custom--white-dark",
    action: function (e, dt, node, config) {
      document.getElementById("comboIndex").options.item(2).selected =
        "selected";
      $("#comboIndex").trigger("change");
    },
  },
];

$(document).ready(function () {
  

  if ($("#exportar").val() == 1) {
    topButtonsCliente.unshift({
      extend: "excelHtml5",
      text: '<span class="d-flex align-items-center"><img src="../../img/icons/ICONO DESCARGAR2_Mesa de trabajo 1.svg" width="20" class="mr-1"> Descargar excel</span>',
      className: "btn-custom--white-dark",
    });
    topButtonsHistorico.unshift({
      extend: "excelHtml5",
      text: '<span class="d-flex align-items-center"><img src="../../img/icons/ICONO DESCARGAR2_Mesa de trabajo 1.svg" width="20" class="mr-1"> Descargar excel</span>',
      className: "btn-custom--white-dark",
    });
  }

  tablaC = $("#tblcliente").DataTable({
    language: idioma_espanol,
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
      buttons: topButtonsCliente,
    },
    columns: [
      { data: "Cliente" },
      { data: "De 0-30 Dias" },
      { data: "De 31-60 Dias" },
      { data: "De 61-60 Dias" },
      { data: "Mas de 90 Dias" },
      { data: "Id" },
    ],
    columnDefs: [
      {
        targets: [5],
        visible: false,
        searchable: false,
      },
      {
        targets: [1, 2, 3, 4],
        searchable: false,
      },
    ],
  });

  tablaHistorico = $("#tblHistorico").DataTable({
    destroy: true,
    language: idioma_espanol,
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
      buttons: topButtonsHistorico,
    },
    columns: [
      { data: "Folio factura" },
      { data: "Cliente", width: "500px" },
      { data: "F de expedicion" },
      { data: "F de vencimiento" },
      { data: "Estado" },
      { data: "Monto total" },
      { data: "Monto pagado" },
      { data: "Parcialidades"},
      { data: "Monto notas credito" },
      { data: "Monto insoluto" },
      { data: "Complementos" },
      { data: "Notas credito" },
      { data: "Ver", width: "20px"},
      { data: "Id" },
    ],
    columnDefs: [
      {
        targets: [13],
        visible: false,
        searchable: false,
      },
      {
        targets: [2, 4, 5, 6, 7, 8, 9, 12],
        searchable: false,
      },
      
    ],
  });

  //activa los tooltips en datatable
  $('#tblHistorico tbody').on('mouseover', 'tr', function () {
    $('[data-toggle="tooltip"]').tooltip({
        trigger: 'hover',
        html: true
    });
    $('[data-toggle="tooltip"]').on("click", function () {
      $(this).tooltip("dispose");
    });
  });

//   $(document).on("mouseover", "#tblHistorico tbody tr", function () {
//     $(this).css("cursor", "pointer");
//   });
  
//   $(document).on("click", "#tblHistorico tbody tr", function () {
//     var tableCuentas = $("#tblHistorico").DataTable();
//     var rowData = tableCuentas.row(this).data();
//     var Ver = rowData.Ver;
//     var idpos1 = Ver.indexOf('o-');
//     var idpos2 = Ver.indexOf('-I');
//     var idpos3 = Ver.indexOf('d-');
//     var idpos4 = Ver.indexOf('\">');
//     var tipo = Ver.slice(idpos1 + 2, idpos2);
//     var id = Ver.slice(idpos3 + 2, idpos4);
//     window.location.href= "detalle_factura.php?"+tipo+"="+id;
//   });

  $(function () {
    $("[data-toggle='tooltip']").tooltip();
  });

  //carga_cmbClientes();

  $("#combo").hide();
  verifica(document.getElementById("comboIndex").value);

  /* //cuando cambia la selección del cliente se filtra
  $("#chosenClientes").on("change", function () {
    $("#btnFilterExits").click();
  }); */

  //filtra tabla
  $("#btnFilterExits").on("click", function (e) {
    //seleccion = document.getElementById("chosenClientes").value;
    seleccion = document.getElementById("txtClientes").value;
    fecha_desde = document.getElementById("txtDateFrom").value;
    fecha_hasta = document.getElementById("txtDateTo").value;

    filtra_historicoCPC(seleccion, fecha_desde, fecha_hasta);
  });

  //cada que el combio se modifique vuelve a verificarlo y a recargar la tabla segun la seleccion
  $("#comboIndex").on("change", function () {
    verifica(document.getElementById("comboIndex").value);
  });

  $(function () {
    $("[data-toggle='tooltip']").tooltip();
  });

  //Comprobamos si tiene permisos para ver
  if ($("#ver").val() !== "1") {
    $("#alert").modal("show");
  }
  //Redireccionamos al Dash cuando se oculta el modal.
  $("#alert").on("hidden.bs.modal", function (e) {
    window.location = href = "../dashboard.php";
  });
});
