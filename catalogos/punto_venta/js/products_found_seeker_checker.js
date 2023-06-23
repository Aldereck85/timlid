function loadProductsFoundSeekerChecker(data){
  tbl_products_found_seeker_checker = $("#tbl_products_found_seeker_checker").DataTable({
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
    data: data,
    columns: [
      { data: 'clave' },
      { data: 'codigo_barras' },
      { data: 'nombre' },
      { data: 'functions' }
  ]
  });
  return tbl_products_found_seeker_checker;
}