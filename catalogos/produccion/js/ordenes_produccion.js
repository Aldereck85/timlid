$(document).ready(function(){

  var filtro = "";
  $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
    var estatus = data[4]; // informacion del estado de la cotizacion
    var tipo = data[6];
    if (filtro == "") {
      return true;
    }

    if (estatus == filtro) {
      return true;
    } else if(tipo == filtro){
      return true;
    }
    else {
      return false;
    }
  });

  var screen = $("body").data("screen");

  var topButtons = [
    /*{
      extend: "excelHtml5",
      text: '<i class="fas fa-cloud-download-alt"></i> Descargar excel',
      className: "btn-table-custom--turquoise",
      titleAttr: "Excel",
      exportOptions: {
        columns: [0, 1, 2, 3, 4, 5],
      },
    },*/
  ];
  
  $.ajax({
    url: "../../../../php_permisos/funciones.php",
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
        topButtons.push({
          text: '<span class="d-flex align-items-center"><img src="../../../../img/icons/ICONO_AGREGAR_DARK.svg" width="20" class="mr-1"> Añadir registro</span>',
          className: "btn-custom--white-dark",
          action: function () {
            window.location.href = "agregar_ordenProduccion.php";
          },
        });
      }

      if(respuesta[0].funcion_exportar === 1){
        topButtons.push({
          extend: "excelHtml5",
          text: '<span class="d-flex align-items-center"><img src="../../../../img/icons/ICONO DESCARGAR2_Mesa de trabajo 1.svg" width="20" class="mr-1"> Descargar excel</span>',
          className: "btn-custom--white-dark",
          titleAttr: "Excel",
          exportOptions: {
            columns: [0, 1, 2, 3, 4],
          }
        })
      }

      var tblOrdenesProduccion = $("#tblOrdenesProduccion").DataTable({
        language: setFormatDatatables(),
        info: false,
        scrollX: true,
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
          method: "post",
          url: "../../php/funciones_copy.php",
          data: {
            clase: "get_data",
            funcion: "get_productionOrderTable",
          },
        },
        order: [0, "desc"],
        columns: [
          { data: "Folio" },
          { data: "Sucursal" },
          { data: "Producto" },
          { data: "Fecha inicio" },
          { data: "Fecha estimada" },
          { data: "Fecha termino" },
          { data: "Encargado" },
          { data: "Estatus" }
          
        ],
        
      });

      new $.fn.dataTable.Buttons(tblOrdenesProduccion, {
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
            text: '<i class="fas fa-globe"></i> Todos',
            className: "btn-table-custom--blue",
            action: function (e, dt, node, config) {
              filtro = "";
              $("#tblOrdenesProduccion").DataTable().draw();
            },
          },
          {
            text: '<i class="far fa-clock"></i> Pendiente',
            className: "btn-table-custom--yellow",
            action: function (e, dt, node, config) {
              filtro = "Pendiente";
              $("#tblOrdenesProduccion").DataTable().draw();
            },
          },
          {
            text: '<i class="fas fa-angle-double-up"></i> Aceptada',
            className: "btn-table-custom--green",
            action: function (e, dt, node, config) {
              filtro = "Aceptada";
              $("#tblOrdenesProduccion").DataTable().draw();
            },
          },
          {
            text: '<i class="fas fa-angle-double-right"></i> En progreso',
            className: "btn-table-custom--green",
            action: function (e, dt, node, config) {
              filtro = "En progreso";
              $("#tblOrdenesProduccion").DataTable().draw();
            },
          },
          {
            text: '<i class="fas fa-check-double"></i> Terminada',
            className: "btn-table-custom--blue",
            action: function (e, dt, node, config) {
              filtro = "Terminada";
              $("#tblOrdenesProduccion").DataTable().draw();
            },
          },
          {
            text: '<i class="fas fa-ban"></i> Cancelada',
            className: "btn-table-custom--red",
            action: function (e, dt, node, config) {
              filtro = "Cancelada";
              $("#tblOrdenesProduccion").DataTable().draw();
            },
          },
          {
            text: '<i class="fas fa-hourglass-half"></i> En progreso atrasada',
            className: "btn-table-custom--orange",
            action: function (e, dt, node, config) {
              filtro = "En progreso atrasada";
              $("#tblOrdenesProduccion").DataTable().draw();
            },
          },
          {
            text: '<i class="fas fa-ban"></i> Cerrada parcial',
            className: "btn-table-custom--red",
            action: function (e, dt, node, config) {
              filtro = "Cerrada parcial";
              $("#tblOrdenesProduccion").DataTable().draw();
            },
          },
        ]
      });
      tblOrdenesProduccion.buttons(1, null).container().appendTo("#btn-filters");
    },
    error: function (error) {
      console.log(error);
    }
  });

  
});

function setFormatDatatables() {
  var idioma_espanol = {
    sProcessing: "Procesando...",
    sZeroRecords: "No se encontraron resultados",
    sEmptyTable: "Ningún dato disponible en esta tabla",
    sSearch: "<img src='../../../../img/timdesk/buscar.svg' width='20px' />",
    sLoadingRecords: "Cargando...",
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

$(document).on("click","#detalle_ordenProducion",function(){
  var id = $(this).data("id");

  console.log(id);
  
  $.redirect(
    'detalle_ordenProduccion.php',
    {
    'id': id
    },
  "POST")
})