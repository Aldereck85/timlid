var falg = false;
function cargar_moduloPagos(idCliente, periodo, seleccion) {
  $().redirect("../recepcion_pagos/pagos.php", {
    'rutaFrom':1,
    'idCliente': idCliente,
    'periodo':periodo,
    'seleccion':seleccion,
    });
}

function cargar_moduloNotas(idCliente, periodo, seleccion){
  $().redirect("../notas_credito/agregar.php", {
  'rutaFrom':1,
  'idCliente': idCliente,
  'periodo':periodo,
  'seleccion':seleccion,
  });
}

//valida que la url sea aceptada
function valida(cliente, periodo, seleccion) {
  //obtiene el cliente para verificar que exista
  if (
    periodo == "0" ||
    periodo == "30" ||
    periodo == "60" ||
    periodo == "90"
  ) {
    if (seleccion == "1" || seleccion == "2" || seleccion == "3") {
      flag = false;
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "../cuentas_cobrar/functions/function_verificaCliente.php",
        async: false,
        data: { id: cliente },
        success: function (data) {
          if (data["estatus"] === "ok") {
            flag = true;
          }
        },
      });
      return flag;
    } else {
      return false;
    }
  } else {
    return false;
  }
}

$(document).ready(function () {
  //asignacion de idioma a la tabla y al filtrador por fechas
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

  //recupera variables para la consulta a la base de datos
  var cliente = $("#cliente_id").val();
  var periodo = $("#periodo").val();
  var seleccion = $("#seleccion").val();

  if (valida(cliente, periodo, seleccion)) {
    $("#btnFilterExits").on("click", function (e) {
      fecha_desde = document.getElementById("txtDateFrom").value;
      fecha_hasta = document.getElementById("txtDateTo").value;

      filtra_cuentasCliente(
        cliente,
        periodo,
        seleccion,
        fecha_desde,
        fecha_hasta
      );
    });

    var topButtonsCliente = [
      {
        text: '<a style="cursor:pointer" id="btnPagos"><i class="fas fa-solid fa-link"></i> Registrar pago</a>',
        className: "btn-table-custom--blue",
        action: function () {
          cargar_moduloPagos(cliente, periodo, seleccion);
        },
      },
      {
        text: '<a style="cursor:pointer" id="btnNotas"><i class="fas fa-solid fa-link"></i> Crear nota de crédito</a>',
        className: "btn-table-custom--blue",
        action: function () {
          cargar_moduloNotas(cliente, periodo, seleccion);
        },
      },
    ];

    //verifica si tiene permiso para exportar, segun sea el caso, carga la tabla adecuada
    if ($("#exportar").val() == 1) {
      topButtonsCliente.push({
        extend: "excelHtml5",
        text: '<i class="fas fa-cloud-download-alt"></i> Descargar excel',
        className: "btn-table-custom--turquoise",
      });
    }

    tablac = $("#tblClientes")
          .DataTable({
            language: espanol,
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
              buttons: topButtonsCliente,
            },
            ajax:
              "functions/function_Puestos.php?periodo=" +
              periodo +
              "&cliente_id=" +
              cliente +
              "&seleccion=" +
              seleccion,
            columns: [
              { data: "Folio de Factura" },
              { data: "Cliente" },
              { data: "Fecha de Facturacion" },
              { data: "Fecha de Vencimiento" },
              { data: "Estado" },
              { data: "Monto" },
              { data: "Monto pagado" },
              { data: "Parcialidades", width: "50px" },
              { data: "Monto notas credito" },
              { data: "Monto insoluto" },
              { data: "Complementos" },
              { data: "Notas credito" },
              { data: "Acciones" },
            ],
          });
    //activa los tooltips en datatable
    $('#tblClientes tbody').on('mouseover', 'tr', function () {
      $('[data-toggle="tooltip"]').tooltip({
          trigger: 'hover',
          html: true
      });
      $('[data-toggle="tooltip"]').on("click", function () {
        $(this).tooltip("dispose");
      });
    });

  } else {
    $("#alertUrl").modal("show");
  }

  $(function () {
    $("[data-toggle='tooltip']").tooltip();
  });

  //Comprobamos si tiene permisos
  if ($("#ver").val() !== "1") {
    $("#alert").modal("show");
  }
  //Redireccionamos al Dash cuando se oculta el modal.
  $("#alert").on("hidden.bs.modal", function (e) {
    window.location = href = "../dashboard.php";
  });

  $("#alertUrl").on("hidden.bs.modal", function (e) {
    window.location = href = "../cuentas_cobrar/";
  });
});
