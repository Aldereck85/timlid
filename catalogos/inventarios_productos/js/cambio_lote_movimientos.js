$(document).ready(function () {
  cargarMovimientosCambio();

  $("img[alt='Regresar']").on("click", function(){
    var cmbsucursal = $("#inputCmbSucursal").val();
    var cmbtipo = $("#inputCmbTipo").val();
    var cmbfolio = $("#inputCmbFolio").val();

    $().redirect("index.php", {
      data1: cmbsucursal,
      data2: cmbtipo,
      data3: cmbfolio
    });
  });

});

function cargarMovimientosCambio() {
  var cambio = $("#inputCambio").val();

  $("#tblMovimientosCambio")
    .DataTable({
      language: setFormatDatatables(),
      info: false,
      scrollX: true,
      bSort: false,
      pageLength: 15,
      responsive: true,
      lengthChange: false,
      columnDefs: [{ orderable: false, targets: 0, visible: false }],
      dom: `<"container-fluid"<"row mb-3"<"col-sm-12 col-md-9 p-0 d-flex align-items-center"B><"col-sm-12 col-md-3 p-0"f>>>rti
    <"container-fluid mt-4"<"row"<"#btn-filters.col-sm-12 col-md-10 p-0"><"col-sm-12 col-md-2 p-0"p>>>`,
      buttons: {
        dom: {
          button: {
            tag: "button",
            className: "",//btn-table-custom
          },
          buttonLiner: {
            tag: null,
          },
        },
        buttons: [
          {
            extend: "excelHtml5",
            text: '<span &nbsp; class="d-flex align-items-center"><img src="../../../../img/icons/ICONO DESCARGAR2_Mesa de trabajo 1.svg" width="20" class="mr-1"> Descargar Excel</span>',
            className: "btn-custom--white-dark btn-custom",
            titleAttr: "Excel",
            exportOptions: {
              columns: [1, 2, 3, 4, 5, 6, 7],
            },
          },
          {
            text: '<span class="d-flex align-items-center"><img src="../../../../img/icons/ICONO DESCARGAR2_Mesa de trabajo 1.svg" width="20" class="mr-1"> Descargar PDF</span>',
            className: "btn-custom--white-dark btn-custom",
            action: function () {
              descargarPDF();
            },
          }
        ],
      },
      ajax: {
        url: "../../php/funciones.php",
        data: {
          clase: "get_data",
          funcion: "get_MovimientosCambioLoteSerie",
          data1: cambio,
        },
      },
      columns: [
        { data: "IdDetalleCambio" },
        { data: "Clave" },
        { data: "Nombre" },
        { data: "Cantidad" },
        //{ data: "SerieAntigua" },
        { data: "LoteAntiguo" },
        //{ data: "SerieNueva" },
        { data: "LoteNuevo" },
        { data: "Caducidad" },
        { data: "Comentarios" },
      ],
    })
    .on("xhr.dt", function () {
      $(function () {
        $('[data-toggle="tooltip"]').tooltip({
          container: "body",
        });
      });
    });
}

function descargarPDF() {
  var cambio = $("#inputCambio").val();

  window.location.href = "funciones/descargar_Cambio.php?data=" + cambio;
} 

function setFormatDatatables() {
  var idioma_espanol = {
    sProcessing: "Procesando...",
    sZeroRecords: "No se encontraron resultados",
    sEmptyTable: "Ningún dato disponible en esta tabla",
    sSearch: "<img src='../../../../img/timdesk/buscar.svg' width='20px' />",
    sLoadingRecords: "Cargando...",
    searchPlaceholder: "Buscar...",
    lengthMenu: `Mostrar: 
                    <select class="mb-n2 mt-1">
                        <option value="20">20</option>
                        <option value="100">100</option>
                        <option value="200">200</option>
                    <select>`,
    oPaginate: {
      sFirst: "",
      sLast: "",
      sNext: "<i class='fas fa-chevron-right'></i>",
      sPrevious: "<i class='fas fa-chevron-left'></i>",
    },
  };
  return idioma_espanol;
}
