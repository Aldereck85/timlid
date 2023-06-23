function loadTicketsTable(value)
{
 
  // <i class="fas fa-print"></i>
  topButtons = [
    {
      text: '<span class="d-flex align-items-center " data-toggle="tooltip" data-placement="top" title="Atajo: ctrl + alt + I"><img  style="margin-right:.75rem" src="../../img/punto_venta/imprimir_azul.svg" width="20">Imprimir último</span>',
      className: "btn-table-custom--blue",
      action: () => {
        getLastTicket();
      },
    },
    {
      text: '<span class="d-flex align-items-center" data-toggle="tooltip" data-placement="top" title="Atajo: ctrl + alt + G"><img  style="margin-right:.60rem" src="../../img/punto_venta/facturar_azul.svg" width="25">Facturar público en general</span>',
      className: "btn-table-custom--blue",
      action: () => {
        $("#modal_invoice_general").modal("show");
      },
    }

  ];

  tblTicketsView = $("#tblTicketsView").DataTable({
    language: setFormatDatatables(),
    info: false,
    scrollX: true,
    pageLength: 12,
    responsive: true,
    lengthChange: false,
    autoWidth: false,
   
    dom: `<"container-fluid"<"row mb-3"<"col-sm-12 col-md-8 p-0 d-flex align-items-center"B><"col-sm-12 col-md-4 p-0"f>>>rti
    <"container-fluid mt-4"<"row"<"#btn-filters-tblTicketsView.col-sm-12 col-md-6 p-0"><"col-sm-12 col-md-4 p-0"p>>>`,
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
      buttons: topButtons
    },
    ajax: {
      method: "post",
      url: "php/funciones.php",
      data: function(d){
        d.clase = "get_data",
        d.funcion = "get_allTickets",
        d.value = value
      }
    },
    columns: [
      { data: "id" },
      { data: "folio" },
      { data: "date" },
      { data: "amount" },
      { data: "status" },
      { data: "invoice" },
      { data: "functions" }
    ],
    columnDefs: 
    [
      {
        "targets":[0],
        "orderable": false,
        "visible":false
      },
      {
        "className": "text-center", "targets": "_all"
      },
      {
        "targets":[4],
        width: "15%"
      }

    ],
    order: [[0, 'desc']],
    drawCallback: function (settings) {
      $('[data-toggle="tooltip"]').tooltip({
        boundary: 'window',
        container: 'body'
      });
    },
  });

  tblTicketsView.columns.adjust();
}