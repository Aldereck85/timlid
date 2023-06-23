function loadPeddingSales(value){
  tbl_pedding_sales = $("#tbl_pedding_sales").DataTable({
    language: setFormatDatatables(),
    info: false,
    scrollX: true,
    scrollY: '300px',
    scrollCollapse: true,
    pageLength: 5,
    responsive: true,
    lengthChange: false,
    autoWidth: true,
    retrieve: true,
    paging: false,
    ajax:{
      method: "post",
      url: "php/funciones.php",
      data: {
        clase: "get_data",
        funcion: "get_peddingSales",
        value: value
      }
    },
    columns: [
      { data: 'folio' },
      { data: 'total_productos'},
      { 
        data: 'costo',
        render: (data,type,row) => {
          return '$' + numeral(data).format('0,000,000,000.00')
        }
      },
      { data: 'functions' }
    ],
   
  });
  return tbl_pedding_sales;
}