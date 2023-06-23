function loadGeneralInvoice()
{
  $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
  

  topButtons = [];

  var tblGeneralInvoice = $("#tblGeneralInvoice").DataTable({
    language: setFormatDatatables(),
    info: false,
    scrollX: true,
    pageLength: 15,
    responsive: true,
    lengthChange: false,
    bFilter: false,
    bPaginate: false,
    ordering: false,
    paging: false,
    retrieve: true,
    searching: false,
    destroy: true,
    dom: `<"container-fluid"<"row mb-3"<"col-sm-12 col-md-12 p-1 d-flex justify-content-end align-items-center"B><"col-sm-12 col-md-6 p-0"f>>>rti
    <"container-fluid mt-4"<"row"<"#btn-filters-tblTicket.col-sm-12 col-md-8 p-0"><"col-sm-12 col-md-4 p-0"p>>>`,
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
    data : [],
    // ajax:{
    //   method: "post",
    //   url: "php/funciones.php",
    //   data: {
    //     clase: "get_data",
    //     funcion: "get_productsGeneralInvoice",
    //     value: initialDate,
    //     value1: finalDate
    //   }
    // },
    columns: [
      { data: "no" },
      { data: "folio" },
      { data: "date" },
      { data: "amount" }
    ],
  });
  

  return tblGeneralInvoice;
}

