$(document).ready(function () {
  //Comprobamos si tiene permisos para ver
  if ($("#ver").val() != 1) {
    $("#alert").modal("show");
  }
  //Comprobamos si tiene permisos para agregar
  if ($("#add").val() != 1) {
    $("#btn-add").remove();
  }
  //Redireccionamos al Dash cuando se oculta el modal.
  $("#alert").on("hidden.bs.modal", function (e) {
    window.location = "../dashboard.php";
  });
  $("#pago_add").click(function (e) {
    e.preventDefault();
    $("#tipo_pago").modal("show");
  });

  let espanol = {
    sProcessing: "Procesando...",
    sZeroRecords: "No se encontraron resultados",
    sEmptyTable: "Ningún dato disponible en esta tabla",
    sSearch: '<img src="../../img/timdesk/buscar.svg" width="20px" />',
    sLoadingRecords: "<img src='../../img/timdesk/Preloader.gif' width='100px' style='transition-duration:300ms;' />",
    searchPlaceholder: "Buscar...",
    oPaginate: {
      sFirst: "Primero",
      sLast: "Último",
      sNext: "<i class='fas fa-chevron-right'></i>",
      sPrevious: "<i class='fas fa-chevron-left'></i>",
    },
  };

  var topButtons = [
    /* {
      text: '<span class="d-flex align-items-center"><img src="../../img/icons/ICONO_AGREGAR_DARK.svg" width="20" class="mr-1"> Añadir pago</span>',
      className: "btn-custom--white-dark",
      action: function () {
        window.location.href = "agregar.php";
      },
    }, */ //Este botón se comentó por la modificación de fusionar en el de abajo los anticipos y pagos libres
    {
      text: '<span class="d-flex align-items-center"><img src="../../img/icons/ICONO_AGREGAR_DARK.svg" width="20" class="mr-1"> Añadir pago</span>',
      className: "btn-custom--white-dark",
      action: function () {
        window.location.href = "agregar_anticipo.php";
      },
    },
    {
      extend: "excelHtml5",
      text: '<span class="d-flex align-items-center"><img src="../../img/icons/ICONO DESCARGAR2_Mesa de trabajo 1.svg" width="20" class="mr-1"> Descargar excel</span>',
      className: "btn-custom--white-dark",
      titleAttr: "Excel",
    },
  ];

  tablaD = $("#tblpagos").DataTable({
    language: espanol,
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
      buttons: topButtons,
    },
    ajax: "functions/get_pagos.php?toDo=3",
    columns: [
      { data: "Folio" },
      { data: "Proveedor" },
      { data: "Fecha de registro" },
      { data: "Comentarios" },
      { data: "Total" },
      { data: "Responsable" },
      { data: "Tipo" },
    ],
  });
  
  new $.fn.dataTable.Buttons(tablaD, {
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
        text: '<i class="fas fa-angle-double-up"></i> Completos',
        className: "btn-table-custom--green",
        action: function (e, dt, node, config) {
          consulta = 1;
          tablaD.ajax.url("functions/get_pagos.php?toDo=0").load();
          /* Cambia el titulo de la pagina */
          $("#tabla").css("display", "block");
          $("#tabla_histo").hide();
        },
      },
      {
        text: '<i class="fas fa-angle-double-up"></i> Anticipos',
        className: "btn-table-custom--orange",
        action: function (e, dt, node, config) {
          consulta = 1;
          tablaD.ajax.url("functions/get_pagos.php?toDo=1").load();
          /* Cambia el titulo de la pagina */
          $("#tabla").css("display", "block");
          $("#tabla_histo").hide();
        },
      },
      {
        text: '<i class="fas fa-angle-double-up"></i> Sin relación',
        className: "btn-table-custom--yellow",
        action: function (e, dt, node, config) {
          consulta = 1;
          tablaD.ajax.url("functions/get_pagos.php?toDo=4").load();
          /* Cambia el titulo de la pagina */
          $("#tabla").css("display", "block");
          $("#tabla_histo").hide();
        },
      },
      {
        text: '<i class="fas fa-angle-double-up"></i> Todos',
        className: "btn-table-custom--blue",
        action: function (e, dt, node, config) {
          consulta = 1;
          tablaD.ajax.url("functions/get_pagos.php?toDo=3").load();
          /* Cambia el titulo de la pagina */
          $("#tabla").css("display", "block");
          $("#tabla_histo").hide();
        },
      },
    ],
  });

  tablaD.buttons(1, null).container().appendTo("#btn-filters");
});

jQuery(function ($) {
 // console.log("ready!");
  var notifi = "<?php echo $notifi; ?>";
 //  console.log(notifi);
});
