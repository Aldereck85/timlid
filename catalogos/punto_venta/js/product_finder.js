function loadProductsFinder(){
  $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
  var value = document.getElementById("txtBranchOfficeId").value;
  var filtro = "";

  topButtons = [
    {
      text: '<i class="fas fa-plus-square"></i> Agregar producto',
      className: "btn-table-custom--blue",
      action: function () {
        $("#create_product").modal("show");
        $("#product_finder").modal("hide");
      },
    },
  ]

  $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
    var existencia = parseInt(data[4]); // informacion del estado de la cotizacion
    
    if (filtro == "") {
      return true;
    }
    
    /*if (existencia > parseInt(filtro)) {
      return true;
    } else if(existencia == parseInt(filtro)){
      return true;
    } else {
      return false;
    }*/

    if(filtro === "con_existencia"){
      if(existencia > 0){
        return true;
      }
    } else if(filtro === "sin_existencia"){
      if(existencia == 0){
        return true;
      }
    } else {
      return false;
    }
   
  });

  tblProductsFinder = $("#tblProductsFinder").DataTable({
    language: setFormatDatatables(),
    info: false,
    scrollX: true,
    pageLength: 12,
    responsive: true,
    lengthChange: false,
    autoWidth: false,
    
    
    dom: `<"container-fluid"<"row mb-3"<"col-sm-12 col-md-6 p-0 d-flex align-items-center"B><"col-sm-12 col-md-6 p-0"f>>>rti
    <"container-fluid mt-4"<"row"<"#btn-filters-tblProductsFinder.col-sm-12 col-md-8 p-0"><"col-sm-12 col-md-4 p-0"p>>>`,
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
        d.clase = "get_data";
        d.funcion = "get_productsDatatable";
        d.value = value;
      }
    },
    columns: [
      { data: "id" },
      { data: "clave" },
      { data: "nombre" },
      { data: "codigo_barras" },
      { data: "existencia" },
      { data: "precio_n" },
      { data: "precio_u" },
      { data: "funciones" }
    ],
    order: [2, "asc"],
    columnDefs: 
    [
      {
        "targets":[0,7],
        "orderable": false,
        "visible":false
      },
      {
        "targets":[1],
        "orderable": false,
        
      },
      {
        "targets":[2],
        "orderable": false
      },
      {
        "targets":[3],
        "orderable": false,
        "visible":false
      },
      {
        "targets":[4],
        
        "orderable": false
      },
      {
        "targets":[5],
        
        "orderable": false
      },
      {
        "targets":[6],
        
        "orderable": false
      },
      
    ],
    // keys: true
  });

  

  tblProductsFinder.columns.adjust();

  new $.fn.dataTable.Buttons(tblProductsFinder, {
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
        className: "btn-table-custom--gray",
        action: function (e, dt, node, config) {
          filtro = "";
          $("#tblProductsFinder").DataTable().draw();
        },
      },
      {
        text: '<i class="fas fa-hourglass-half"></i> Con existencia',
        className: "btn-table-custom--yellow",
        action: function (e, dt, node, config) {
          filtro = "con_existencia";
          $("#tblProductsFinder").DataTable().draw();
        },
      },
      {
        text: '<i class="fas fa-hourglass-half"></i> Sin existencia',
        className: "btn-table-custom--yellow",
        action: function (e, dt, node, config) {
          filtro = "sin_existencia";
          $("#tblProductsFinder").DataTable().draw();
        },
      },
    ]
  });

  tblProductsFinder.buttons(1, null).container().appendTo("#btn-filters-tblProductsFinder");

  $.fn.DataTable.ext.pager.numbers_length = 5;

  tblProductsFinder.columns.adjust();

  
  return tblProductsFinder;
  

}