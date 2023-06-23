$(document).ready(function () {
  /* En #edit.val Tengo el valor binario de la tabla funciones_permisos->funcion_editar-> En Mi Pantalla (60) en el rol actual*/
  /* En el If() ocultaremos la columna de editar dependiendo de su rol*/
  /* Ocultamos la columna cargando dos tablas diferentes donde en el else agregamos la columna de editar al target de ocultar */
  var idFactura = $("#idFactura").val();
  var idioma_espanol = {
    sProcessing: "<img src='../../img/timdesk/Preloader.gif' width='100px' style='transition-duration:300ms;' />.",
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
  var topButtons = [];
  if ($("#exportar").val() == 1) {
    topButtons.push({
      extend: "excelHtml5",
      text: '<img class="readEditPermissions" float="left" type="submit" width="50px" src="../../img/excel-azul.svg" />',
      className: "excelDataTableButton",
      titleAttr: "Exportar",
    });
  }

  $("#tbldetalle")
    .DataTable({
      language: idioma_espanol,
      info: false,
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
      ajax: "functions/get_detalle_factura.php?idFactura=" + idFactura,
      columns: [
        { data: "Clave" },
        { data: "Producto" },
        { data: "Descripcion" },
        { data: "Cantidad" },
        { data: "Precio" },
        { data: "Lote" },
        { data: "Serie" },
        { data: "Caducidad" },
      ],
    })
    .on("xhr.dt", function () {
      $(function () {
        $("[data-toggle='tooltip']").tooltip();
      });
    });

    $(function () {
      $("[data-toggle='tooltip']").tooltip();
    });

  //Comprobamos si tiene permisos para editar
  if ($("#edit").val() !== "1") {
    $("#mod").hide();
  }

  //Comprobamos si tiene permisos para ver
  if ($("#ver").val() !== "1") {
    $("#alert").modal("show");
  }
  //Redireccionamos al Dash cuando se oculta el modal.
  $("#alert").on("hidden.bs.modal", function (e) {
    window.location = href = "../dashboard.php";
  });

  /* Obtenemos los valores de la cuenta por pagar y los ponemos en los campos de la pantalla editar */
  var idFactura = $("#idFactura").val();
  $.ajax({
    type: "POST",
    url: "../cuentas_cobrar/functions/get_ajax_detalle_factura.php",
    dataType: "json",
    data: { idFactura: idFactura, funcion: "1" },
    success: function (data) {
      if (data.status == "ok") {
        $("#alertInvoice").modal("hide");
        $("#nombre").val(data.result.NombreComercial);
        $("#txtfolio").val(data.result.folio);
        $("#txtserie").val(data.result.serie);
        $("#txtimporte").val(data.result.total_facturado);
        $("#txtfechaF").val(data.result.fecha_timbrado);
        $("#txtfechaV").val(data.result.fechaVencimiento);
      } else {
        //mostramos el modal de alerta
        $("#alertInvoice").modal("show");

        //Redireccionamos al modulo cuando se oculta el modal.
        $("#alertInvoice").on("hidden.bs.modal", function (e) {
          window.location = href = "../cuentas_cobrar";
        });
      }
    },
  });
});
