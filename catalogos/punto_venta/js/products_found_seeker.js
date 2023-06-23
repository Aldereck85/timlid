function loadProductsFoundSeeker(data){
    
    tbl_products_found_seeker = $("#tbl_products_found_seeker").DataTable({
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
        keys: true,
        data: data,
        columns: [
        { data: 'clave' },
        { data: 'nombre' },
        { data: 'codigo_barras' },
        { data: 'functions' }
        ],
        "defaultContent": "-", "targets": "_all" 
    });
    return tbl_products_found_seeker;
}