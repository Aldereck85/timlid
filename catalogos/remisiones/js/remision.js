$(document).ready(function(){
  var screen = $("body").data("screen");
  var topButtons = [
    {
      extend: "excelHtml5",
      text: '<i class="fas fa-cloud-download-alt"></i> Descargar excel',
      className: "btn-table-custom--turquoise",
      titleAttr: "Excel",
      exportOptions: {
        columns: [0, 1, 2, 3, 4, 5],
      },
    },
  ];

  $.ajax({
    url: "../../php_permisos/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_permissions",
      value: screen,
    },
    dataType: "json",
    success: function (respuesta) {
      var pantalla = quitarAcentos(respuesta[0].pantalla.replace(/\s+/g, ""));
      var modal = pantalla + "_" + screen;

      if (respuesta[0].funcion_agregar === 1) {
        topButtons = [
          {
            text: '<span class="d-flex align-items-center"><img src="../../img/icons/ICONO_AGREGAR_DARK.svg" width="20" class="mr-1"> Añadir registro</span>',
            className: "btn-custom--white-dark",
            action: function () {
              window.location.href = "agregar_remision.php";
            },
          },
          {
            extend: "excelHtml5",
            text: '<span class="d-flex align-items-center"><img src="../../img/icons/ICONO DESCARGAR2_Mesa de trabajo 1.svg" width="20" class="mr-1"> Descargar excel</span>',
            className: "btn-custom--white-dark",
            titleAttr: "Excel",
          },
        ];
      }
      $("#tblRemisiones").DataTable({
        language: setFormatDatatables(),
        info: false,
        scrollX: true,
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
        ajax:{
          url: "php/funciones.php",
          data: {
            clase: "get_data",
            funcion: "get_remissionsTable"
          },
        },
        order: [0, "desc"],
        columns:[
          {"data":"id"},
          {"data":"Folio"},
          {"data":"Fecha"},
          {"data":"Razon social"},
          {"data":"Subtotal"},
          {"data":"Total"},
          {"data":"Estatus"}
        ],
        columnDefs: [
          { "targets": 0,
            "visible": false,
            "searchable": false
          },
          {
            "width":"8%",
            "targets":1
          },
          {
            "width":"8%",
            "targets":2
          },
          {
            "width":"15%",
            "targets":3
          },
          {
            "width":"8%",
            "targets":4
          },
          {
            "width":"8%",
            "targets":5
          },
          {
            "width":"8%",
            "targets":6
          }
  
        ]
      });
    },error: function (error) {
      console.log(error);
    },
  });
})

$(document).on("click","#agregar_remision",function(){
  window.location.href= "agregar_remision.php"
});

function setFormatDatatables() {
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
  return idioma_espanol;
}

$(document).on("click","#detalle_remision",function(){
  var data = $(this).data('id');

  $().redirect('detalle_remision.php', {
    'idRemision': data
  });
});