function loadDetailsTicketsTable(value)
{
  topButtons = [];

  tblDetailsTicketsView = $("#tblDetailsTicketsView").DataTable({
    language: setFormatDatatables(),
    info: false,
    scrollX: true,
    pageLength: 12,
    responsive: true,
    lengthChange: false,
    autoWidth: false,
   
    dom: `<"container-fluid"<"row mb-3"<"col-sm-12 col-md-8 p-0 d-flex align-items-center"B><"col-sm-12 col-md-4 p-0"f>>>rti
    <"container-fluid mt-4"<"row"<"#btn-filters-tblDetailsTicketsView.col-sm-12 col-md-6 p-0"><"col-sm-12 col-md-4 p-0"p>>>`,
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
        d.funcion = "get_detailsTicket",
        d.value = value
      }
    },
    columns: [
      { data: "cantidad" },
      { data: "descripcion" },
      { data: "precio_unitario" },
      { data: "subtotal" }
    ],
    
  });

  tblDetailesTicketsView.columns.adjust();

}