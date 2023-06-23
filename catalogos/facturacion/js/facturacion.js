
$(document).ready(function () {
  getStatusCancel();
  $('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
    $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();
  });
  
  var filtro = "";

  $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
    var estatus = data[5]; // informacion del estado de la cotizacion
    var tipo = data[9];
    var vencimiento = data[8];
   
    if (filtro == "") {
      return true;
    }

    if (estatus == filtro) {
      return true;
    } else if(tipo == filtro){
      return true;
    } else if(vencimiento == filtro){
      return true;
    } else {
      return false;
    }
   
  });

  var screen = $("body").data("screen");
  var topButtons = [
    {
      extend: "excelHtml5",
      text: '<i class="fas fa-cloud-download-alt"></i> Descargar excel',
      className: "btn-table-custom--turquoise",
      titleAttr: "Excel",
      exportOptions: {
        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8],
      },
    },
  ];

  var topButtons_preinvoices = [
    {
      extend: "excelHtml5",
      text: '<i class="fas fa-cloud-download-alt"></i> Descargar excel',
      className: "btn-table-custom--turquoise",
      titleAttr: "Excel",
      exportOptions: {
        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8],
      },
    },
  ];
  
  $.ajax({
    method: "POST",
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
            text: '<span class="d-flex align-items-center"><img src="../../img/icons/ICONO_AGREGAR_DARK.svg" width="20" class="mr-1"> Nueva Factura</span>',
            className: "btn-custom--white-dark",
            action: function () {
              window.location.href = "agregar_facturacion_concepto.php";
            },
          },
          {
            text: '<span class="d-flex align-items-center"><img src="../../img/icons/ICONO_AGREGAR_DARK.svg" width="20" class="mr-1"> Factura Venta/Cotización</span>',
            className: "btn-custom--white-dark",
            action: function () {
              window.location.href = "agregar_facturacion.php";
            },
          },
          {
            text: '<span class="d-flex align-items-center"><img src="../../img/icons/ICONO_AGREGAR_DARK.svg" width="20" class="mr-1"> Factura global</span>',
            className: "btn-custom--white-dark",
            action: function () {
              window.location.href = "agregar_facturacion_global.php";
            },
          },
          {
            extend: "excelHtml5",
            text: '<span class="d-flex align-items-center"><img src="../../img/icons/ICONO DESCARGAR2_Mesa de trabajo 1.svg" width="20" class="mr-1"> Descargar excel</span>',
            className: "btn-custom--white-dark",
            titleAttr: "Excel",
          },
        ];
        
        topButtons_preinvoices = [

          {
            extend: "excelHtml5",
            text: '<span class="d-flex align-items-center"><img src="../../img/icons/ICONO DESCARGAR2_Mesa de trabajo 1.svg" width="20" class="mr-1"> Descargar excel</span>',
            className: "btn-custom--white-dark",
            titleAttr: "Excel",
          }
        ];
      }
      var tableCFDI = $("#tblCFDI").DataTable({
        language: setFormatDatatables(),
        
        info: false,
        scrollX: true,
        pageLength: 50,
        responsive: true,
        lengthChange: false,
        dom: `<"container-fluid"<"row mb-3"<"col-sm-12 col-md-9 p-0 d-flex align-items-center"B><"col-sm-12 col-md-3 p-0"f>>>rti
        <"container-fluid mt-4"<"row"<"#btn-filters-tblCFDI.col-sm-12 col-md-10 p-0"><"col-sm-12 col-md-2 p-0"p>>>`,
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
          method: "POST",
          url: "php/funciones.php",
          data: {
            clase: "get_data",
            funcion: "get_invoicesTable",
			      value: 0
          },
        },
        order: [1, "desc"],
        columns: [
          { data: "id" },
          { data: "folio" },
          { data: "serie" },
          { data: "Razon social" },
          { data: "Total facturado" },
          { data: "estatus" },
          { data: "Fecha de timbrado" },
          { data: "fecha_vencimiento" },
          { data: "estatus_vencimiento" },
          { data: "Vendedor" }
        ],
        columnDefs: [
          {
            targets: 1, 
            width: '5%'
          },
          { 
            orderable: false, 
            targets: 0, 
            visible: false 
          },
          
          { 
            orderable: false, 
            targets: 8, 
            visible: false 
          },
          { 
            orderable: true, 
            targets: 7, 
            render: $.fn.dataTable.render.moment('DD-MM-YYYY','DD-MM-YYYY'),
            createdCell:function (td, cellData, rowData, row, col,data) {
              
              if(rowData.estatus_vencimiento === ''){
                $(td).css({'font-size':'1rem','font-family': 'Montserrat, sans-serif'});
                $(td).html('<span class="left-dot red-dot" style="font-size:1rem;font-family: Montserrat, sans-serif">Sin Fecha</span>');
              }

              if(rowData.estatus_vencimiento === 'Vencida'){
                if(rowData.estatus === "Pagada"){
                  $(td).css({'font-size':'1rem','font-family': 'Montserrat, sans-serif'});
                  $(td).html('<span class="left-dot green-dot" style="font-size:1rem;font-family: Montserrat, sans-serif">'+cellData+'</span>');
                } else 
                if(rowData.estatus === "Pendiente de pago"){
                  $(td).css({'font-size':'1rem','font-family': 'Montserrat, sans-serif'});
                  $(td).html('<span class="left-dot yellow-dot" style="font-size:1rem;font-family: Montserrat, sans-serif">'+cellData+'</span>');
                } else {
                  $(td).css({'font-size':'1rem','font-family': 'Montserrat, sans-serif'});
                  $(td).html('<span class="left-dot red-dot" style="font-size:1rem;font-family: Montserrat, sans-serif">'+cellData+'</span>');
                }
              }

              if(rowData.estatus_vencimiento === 'Al corriente'){
                if(rowData.estatus === "Pagada"){
                  $(td).css({'font-size':'1rem','font-family': 'Montserrat, sans-serif'});
                  $(td).html('<span class="left-dot green-dot" style="font-size:1rem;font-family: Montserrat, sans-serif">'+cellData+'</span>');
                } else 
                if(rowData.estatus === "Pendiente de pago"){
                  $(td).css({'font-size':'1rem','font-family': 'Montserrat, sans-serif'});
                  $(td).html('<span class="left-dot yellow-dot" style="font-size:1rem;font-family: Montserrat, sans-serif">'+cellData+'</span>');
                } else {
                  $(td).css({'font-size':'1rem','font-family': 'Montserrat, sans-serif'});
                  $(td).html('<span class="left-dot green-dot" style="font-size:1rem;font-family: Montserrat, sans-serif">'+cellData+'</span>');
                }
              }

              
            }
          }
        ],
      });

    //   $("#txtFechaMinima , #txtFechaMaxima").on("change",function(){
    //     dateMax = $("#txtFechaMaxima").val();
    //     dateMin = $("#txtFechaMinima").val();

    //     tableCFDI.clear().draw();

    //     tableCFDI1 = $("#tblCFDI").DataTable({
    //       language: setFormatDatatables(),
    //       bDestroy: true,
    //       info: false,
    //       scrollX: true,
    //       pageLength: 50,
    //       responsive: true,
    //       lengthChange: false,
    //       dom: `<"container-fluid"<"row mb-3"<"col-sm-12 col-md-9 p-0 d-flex align-items-center"B><"col-sm-12 col-md-3 p-0"f>>>rti
    //       <"container-fluid mt-4"<"row"<"#btn-filters-tblCFDI.col-sm-12 col-md-10 p-0"><"col-sm-12 col-md-2 p-0"p>>>`,
    //       buttons: {
    //         dom: {
    //           button: {
    //             tag: "button",
    //             className: "btn-custom mr-2",
    //           },
    //           buttonLiner: {
    //             tag: null,
    //           },
    //         },
    //         buttons: topButtons,
    //       },
    //       ajax: {
    //         method: "POST",
    //         url: "php/funciones.php",
    //         data: {
    //           clase: "get_data",
    //           funcion: "get_tableInvoiceFilterDate",
    //           value: 0,
    //           dateMin: dateMin,
    //           dateMax: dateMax
    //         },
    //       },
    //       order: [2, "desc"],
    //       columns: [
    //         { data: "id" },
    //         { data: "serie" },
    //         { data: "folio" },
    //         { data: "Razon social" },
    //         { data: "Total facturado" },
    //         { data: "estatus" },
    //         { data: "Fecha de timbrado" },
    //         { data: "fecha_vencimiento" },
    //         { data: "estatus_vencimiento" },
    //         { data: "Vendedor" }
    //       ],
    //       columnDefs: [
    //         {
    //           targets: 1, 
    //           width: '5%'
    //         },
    //         { 
    //           orderable: false, 
    //           targets: 0, 
    //           /*visible: false */
    //         },
    //         { 
    //           orderable: false, 
    //           targets: 2, 
    //           visible: false 
    //         },
    //         /*{ 
    //           orderable: false, 
    //           targets: 8, 
    //           visible: false 
    //         },*/
    //         { 
    //           orderable: true, 
    //           targets: 7, 
    //           render: $.fn.dataTable.render.moment('DD-MM-YYYY','DD-MM-YYYY'),
    //           createdCell:function (td, cellData, rowData, row, col,data) {
                
    //             if(rowData.estatus_vencimiento === ''){
    //               $(td).css({'font-size':'1rem','font-family': 'Montserrat, sans-serif'});
    //               $(td).html('<span class="left-dot red-dot" style="font-size:1rem;font-family: Montserrat, sans-serif">Sin Fecha</span>');
    //             }

    //             if(rowData.estatus_vencimiento === 'Vencida'){
    //               if(rowData.estatus === "Pagada"){
    //                 $(td).css({'font-size':'1rem','font-family': 'Montserrat, sans-serif'});
    //                 $(td).html('<span class="left-dot green-dot" style="font-size:1rem;font-family: Montserrat, sans-serif">'+cellData+'</span>');
    //               } else 
    //               if(rowData.estatus === "Pendiente de pago"){
    //                 $(td).css({'font-size':'1rem','font-family': 'Montserrat, sans-serif'});
    //                 $(td).html('<span class="left-dot yellow-dot" style="font-size:1rem;font-family: Montserrat, sans-serif">'+cellData+'</span>');
    //               } else {
    //                 $(td).css({'font-size':'1rem','font-family': 'Montserrat, sans-serif'});
    //                 $(td).html('<span class="left-dot red-dot" style="font-size:1rem;font-family: Montserrat, sans-serif">'+cellData+'</span>');
    //               }
    //             }

    //             if(rowData.estatus_vencimiento === 'Al corriente'){
    //               if(rowData.estatus === "Pagada"){
    //                 $(td).css({'font-size':'1rem','font-family': 'Montserrat, sans-serif'});
    //                 $(td).html('<span class="left-dot green-dot" style="font-size:1rem;font-family: Montserrat, sans-serif">'+cellData+'</span>');
    //               } else 
    //               if(rowData.estatus === "Pendiente de pago"){
    //                 $(td).css({'font-size':'1rem','font-family': 'Montserrat, sans-serif'});
    //                 $(td).html('<span class="left-dot yellow-dot" style="font-size:1rem;font-family: Montserrat, sans-serif">'+cellData+'</span>');
    //               } else {
    //                 $(td).css({'font-size':'1rem','font-family': 'Montserrat, sans-serif'});
    //                 $(td).html('<span class="left-dot green-dot" style="font-size:1rem;font-family: Montserrat, sans-serif">'+cellData+'</span>');
    //               }
    //             }
    //           }
    //         }
    //       ],
    //     });

    //     // new $.fn.dataTable.Buttons(tableCFDI1, {
    //     //   dom: {
    //     //     button: {
    //     //       tag: "button",
    //     //       className: "btn-table-custom",
    //     //     },
    //     //     buttonLiner: {
    //     //       tag: null,
    //     //     },
    //     //   },
    //     //   buttons: [
    //     //     {
    //     //       text: '<i class="fas fa-globe"></i> Todos',
    //     //       className: "btn-table-custom--gray",
    //     //       action: function (e, dt, node, config) {
    //     //         filtro = "";
    //     //         $("#tblCFDI").DataTable().draw();
    //     //       },
    //     //     },
    //     //     {
    //     //       text: '<i class="fas fa-hourglass-half"></i> Pendiente de pago',
    //     //       className: "btn-table-custom--yellow",
    //     //       action: function (e, dt, node, config) {
    //     //         filtro = "Pendiente de pago";
    //     //         $("#tblCFDI").DataTable().draw();
    //     //       },
    //     //     },
    //     //     {
    //     //       text: '<i class="fas fa-check-double"></i> Pagada',
    //     //       className: "btn-table-custom--green",
    //     //       action: function (e, dt, node, config) {
    //     //         filtro = "Pagada";
    //     //         $("#tblCFDI").DataTable().draw();
    //     //       },
    //     //     },
    //     //     {
    //     //       text: '<i class="fas fa-ban"></i> Cancelada',
    //     //       className: "btn-table-custom--red",
    //     //       action: function (e, dt, node, config) {
    //     //         filtro = "Cancelada";
    //     //         $("#tblCFDI").DataTable().draw();
    //     //       },
    //     //     },
    //     //     {
    //     //       text: '<i class="fas fa-file-invoice"></i> Por documento',
    //     //       className: "btn-table-custom--blue-lightest",
    //     //       action: function (e, dt, node, config) {
    //     //         filtro = "Por documento";
    //     //         $("#tblCFDI").DataTable().draw();
    //     //       },
    //     //     },
    //     //     {
    //     //       text: '<i class="fas fa-angle-double-up"></i> Por concepto',
    //     //       className: "btn-table-custom--yellow",
    //     //       action: function (e, dt, node, config) {
    //     //         filtro = "Por concepto";
    //     //         $("#tblCFDI").DataTable().draw();
    //     //       },
    //     //     },
    //     //     {
    //     //       text: '<i class="far fa-clock"></i> Vencidas',
    //     //       className: "btn-table-custom--red",
    //     //       action: function (e, dt, node, config) {
    //     //         filtro = "Vencida";
    //     //         $("#tblCFDI").DataTable().draw();
    //     //       },
    //     //     },
    //     //     {
    //     //       text: '<i class="fas fa-angle-double-right"></i> Al corriente',
    //     //       className: "btn-table-custom--blue",
    //     //       action: function (e, dt, node, config) {
    //     //         filtro = "Al corriente";
    //     //         $("#tblCFDI").DataTable().draw();
    //     //       },
    //     //     }
    //     //   ],
    //     // });
        
    //     // tableCFDI1.buttons(1, null).container().appendTo("#btn-filters-tblCFDI");
        
    //   });
      
      var tablepreinvoices = $("#tblpreinvoices").DataTable({
        language: setFormatDatatables(),
        info: false,
        scrollX: true,
        pageLength: 50,
        responsive: true,
        lengthChange: false,
        dom: `<"container-fluid"<"row mb-3"<"col-sm-12 col-md-9 p-0 d-flex align-items-center"B><"col-sm-12 col-md-3 p-0"f>>>rti
        <"container-fluid mt-4"<"row"<"#btn-filters-tblpreinvoices.col-sm-12 col-md-10 p-0"><"col-sm-12 col-md-2 p-0"p>>>`,
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
          buttons: topButtons_preinvoices,
        },
        ajax: {
          method: "post",
          url: "php/funciones.php",
          data: {
            clase: "get_data",
            funcion: "get_invoicesTable",
            value: 1
          },
        },
        order: [0, "desc"],
        columns: [
          { data: "id" },
          { data: "folio" },
          { data: "serie" },
          { data: "Razon social" },
          { data: "Total facturado" },
          { data: "estatus" },
          { data: "Fecha de timbrado" },
          { data: "fecha_vencimiento" },
          { data: "estatus_vencimiento" },
          { data: "Vendedor" },
          { data: "operaciones" }
        ],
        columnDefs: [
          { 
            orderable: false, 
            targets: [0,10], 
            visible: false 
          },
          { 
            orderable: false, 
            targets: 7, 
            visible: false 
          },
          { 
            orderable: false, 
            targets: 8, 
            visible: false 
          },
          { 
            orderable: false, 
            targets: 5, 
            visible: false 
          }
        ],
      });
      
    //   new $.fn.dataTable.Buttons(tableCFDI, {
    //     dom: {
    //       button: {
    //         tag: "button",
    //         className: "btn-table-custom",
    //       },
    //       buttonLiner: {
    //         tag: null,
    //       },
    //     },
    //     buttons: [
    //       {
    //         text: '<i class="fas fa-globe"></i> Todos',
    //         className: "btn-table-custom--gray",
    //         action: function (e, dt, node, config) {
    //           filtro = "";
    //           $("#tblCFDI").DataTable().draw();
    //         },
    //       },
    //       {
    //         text: '<i class="fas fa-hourglass-half"></i> Pendiente de pago',
    //         className: "btn-table-custom--yellow",
    //         action: function (e, dt, node, config) {
    //           filtro = "Pendiente de pago";
    //           $("#tblCFDI").DataTable().draw();
    //         },
    //       },
    //       {
    //         text: '<i class="fas fa-check-double"></i> Pagada',
    //         className: "btn-table-custom--green",
    //         action: function (e, dt, node, config) {
    //           filtro = "Pagada";
    //           $("#tblCFDI").DataTable().draw();
    //         },
    //       },
    //       {
    //         text: '<i class="fas fa-ban"></i> Cancelada',
    //         className: "btn-table-custom--red",
    //         action: function (e, dt, node, config) {
    //           filtro = "Cancelada";
    //           $("#tblCFDI").DataTable().draw();
    //         },
    //       },
    //       {
    //         text: '<i class="fas fa-file-invoice"></i> Por documento',
    //         className: "btn-table-custom--blue-lightest",
    //         action: function (e, dt, node, config) {
    //           filtro = "Por documento";
    //           $("#tblCFDI").DataTable().draw();
    //         },
    //       },
    //       {
    //         text: '<i class="fas fa-angle-double-up"></i> Por concepto',
    //         className: "btn-table-custom--yellow",
    //         action: function (e, dt, node, config) {
    //           filtro = "Por concepto";
    //           $("#tblCFDI").DataTable().draw();
    //         },
    //       },
    //       {
    //         text: '<i class="far fa-clock"></i> Vencidas',
    //         className: "btn-table-custom--red",
    //         action: function (e, dt, node, config) {
    //           filtro = "Vencida";
    //           $("#tblCFDI").DataTable().draw();
    //         },
    //       },
    //       {
    //         text: '<i class="fas fa-angle-double-right"></i> Al corriente',
    //         className: "btn-table-custom--blue",
    //         action: function (e, dt, node, config) {
    //           filtro = "Al corriente";
    //           $("#tblCFDI").DataTable().draw();
    //         },
    //       }
    //     ],
    //   });
      
    //   tableCFDI.buttons(1, null).container().appendTo("#btn-filters-tblCFDI");
    //   /*$('#txtFechaMinima, #txtFechaMaxima').on('change', function () {
    //     tableCFDI.draw();
    //   });*/
      
    //   new $.fn.dataTable.Buttons(tablepreinvoices, {
    //     dom: {
    //       button: {
    //         tag: "button",
    //         className: "btn-table-custom",
    //       },
    //       buttonLiner: {
    //         tag: null,
    //       },
    //     },
    //     buttons: [
    //       {
    //         text: '<i class="fas fa-globe"></i> Todos',
    //         className: "btn-table-custom--gray",
    //         action: function (e, dt, node, config) {
    //           filtro = "";
    //           $("#tblpreinvoices").DataTable().draw();
    //         },
    //       },
    //       {
    //         text: '<i class="fas fa-check-double"></i></i> PAgada',
    //         className: "btn-table-custom--green",
    //         action: function (e, dt, node, config) {
    //           filtro = "Timbrada";
    //           $("#tblpreinvoices").DataTable().draw();
    //         },
    //       },
    //       {
    //         text: '<i class="fas fa-ban"></i> Cancelada',
    //         className: "btn-table-custom--red",
    //         action: function (e, dt, node, config) {
    //           filtro = "Cancelada";
    //           $("#tblpreinvoices").DataTable().draw();
    //         },
    //       },
    //       {
    //         text: '<i class="fas fa-file-invoice"></i> Por documento',
    //         className: "btn-table-custom--blue-lightest",
    //         action: function (e, dt, node, config) {
    //           filtro = "Por documento";
    //           $("#tblpreinvoices").DataTable().draw();
    //         },
    //       },
    //       {
    //         text: '<i class="fas fa-angle-double-up"></i> Por concepto',
    //         className: "btn-table-custom--yellow",
    //         action: function (e, dt, node, config) {
    //           filtro = "Por concepto";
    //           $("#tblpreinvoices").DataTable().draw();
    //         },
    //       },
    //       {
    //         text: '<i class="far fa-clock"></i> Vencidas',
    //         className: "btn-table-custom--red",
    //         action: function (e, dt, node, config) {
    //           filtro = "Vencidas";
    //           $("#tblpreinvoices").DataTable().draw();
    //         },
    //       },
    //       {
    //         text: '<i class="fas fa-angle-double-right"></i> Al corriente',
    //         className: "btn-table-custom--blue",
    //         action: function (e, dt, node, config) {
    //           filtro = "Al corriente";
    //           $("#tblpreinvoices").DataTable().draw();
    //         },
    //       }
    //     ]
    //   });
      
    //   tablepreinvoices.buttons(1, null).container().appendTo("#btn-filters-tblpreinvoices");
  
    },

    error: function (error) {
      console.log(error);
    },
  });
  

});

$(document).on("click", "#agregar_Facturacion_16", function () {
  window.location.href = "agregar_facturacion.php";
});

function setFormatDatatables() {
  var idioma_espanol = {
    sProcessing: "Procesando...",
    sZeroRecords: "No se encontraron resultados",
    sEmptyTable: "Ningún dato disponible en esta tabla",
    sSearch: "<img src='../../img/timdesk/buscar.svg' width='20px' />",
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

// $(document).on("click", "#detalle_factura", function () {
//     var data = $(this).data("id");
//     var prefactura = $(this).data("prefactura");

//     var url = prefactura === 0 ? "detalle_factura.php" : "agregar_facturacion.php";

//     $.redirect(url, {
//         idFactura: data,
//     },
//     "POST",
//     "_blank");
// });
/*
$(document).on("change","#txtFechaMinima",function(){
  dateMax = $("#txtFechaMaxima").val();
  dateMin = $("#txtFechaMinima").val();

  tableCFDI = $("#tblCFDI").DataTable();

  
  
});
*/
/*
$(document).on("change","#txtFechaMaxima",function(){
  dateMin = $("#txtFechaMinima").val();
  dateMax = $("#txtFechaMaxima").val();

  tableCFDI = $("#tblCFDI").DataTable();
      
  tableCFDI.clear().draw();
  
  
});*/

function getStatusCancel(){
  $.ajax({
    url: "php/funciones.php",
    method: "post",
    data: {
      clase: "get_data",
      funcion: "get_statusCancelInvoice"
    },
    dataType: 'json',
    success: function(){
      
    },
    error:function(error){
      console.log(error);
    }
  });
}

$(document).on("click","#show_pdf_preinvoice",function(){
  id = $(this).data("id");
  
  $.ajax({
    url: "php/funciones.php",
    method: "post",
    data: {
      clase: "get_data",
      funcion: "get_preinvoicePdf",
      value:id
    },
    dataType: 'json',
    success: function(response){

      general_data = response[0].general_data[0];
      products = response[0].products;
      tax = response[0].impuestos;
      footer_pdf = response[0].footer_pdf[0];
      subtotal = 0;
      metodo_pago = general_data.metodo_pago === 1 ? "PUE Pago en una sola exhibición" : "PPD Pago en parcialidades o diferido";

      products.forEach(e => {
        subtotal += parseFloat(e.total);
      });
      
      if(
        footer_pdf.calle !== "" && footer_pdf.calle !== null && footer_pdf.calle !== undefined &&
        footer_pdf.no_exterior !== "" && footer_pdf.no_exterior !== null && footer_pdf.no_exterior !== undefined &&
        footer_pdf.cp !== "" && footer_pdf.cp !== null && footer_pdf.cp !== undefined &&
        footer_pdf.municipio !== "" && footer_pdf.municipio !== null && footer_pdf.municipio !== undefined &&
        footer_pdf.estado !== "" && footer_pdf.estado !== null && footer_pdf.estado !== undefined
      )
      {
        interior = footer_pdf.no_interior !== "" && footer_pdf.no_interior !== null && footer_pdf.no_interior !== 'undefined' ? "Int. " + footer_pdf.no_interior : "";
        direccion_envio = 
          footer_pdf.calle + " " + 
          footer_pdf.no_exterior + " " + 
          interior + " " + " C.P. " + 
          footer_pdf.cp + " " + 
          footer_pdf.municipio + ", " + 
          footer_pdf.estado;
        direccion_envio_pdf = direccion_envio !== "" && direccion_envio !== null ? "Dirección de envío: "+direccion_envio : "";
      } else {
        direccion_envio_pdf = "";
      }
      
      notas_cliente = footer_pdf.notas_cliente !== "" && footer_pdf.notas_cliente !== null && footer_pdf.notas_cliente !== undefined ? "Notas de cliente: " + footer_pdf.notas_cliente : "";
      contacto = footer_pdf.contacto !== "" && footer_pdf.contacto !== null && footer_pdf.contacto !== undefined ? "Contacto: " + footer_pdf.contacto : "";
      telefono = footer_pdf.telefono !== "" && footer_pdf.telefono !== null && footer_pdf.telefono !== undefined ? "Teléfono: " + footer_pdf.telefono : "";
      
      $.redirect(
        'php/download_prefactura.php', 
        {
          'folioyserie' : general_data.serie + " " + general_data.folio,
          'razon_social' : general_data.razon_social,
          'fecha' : general_data.fecha_timbrado,
          'cfdi' : general_data.cfdi,
          'forma_pago' : general_data.forma_pago,
          'metodo_pago' : metodo_pago,
          'moneda' : general_data.moneda,
          'rfc' : general_data.rfc,
          'productos' : JSON.stringify(products),
          'subtotal' : numeral(subtotal).format("0,000,000.00"),
          'impuestos' : tax,
          'total' : general_data.total_facturado,
          'notas_cliente' : notas_cliente,
          'direccion_envio' : direccion_envio_pdf,
          'contacto' : contacto,
          'telefono' : telefono
        },
        "POST",
        "_blank"
      );
      
      /* 
      'folioyserie': $("#folioyserie").val(),
      'razon_social':$("#razon_social").val(),
      'fecha':$("#fecha").val(),
      'cfdi':$("#cfdi").val(),
      'forma_pago':$("#forma_pago").val(),
      'metodo_pago':$("#metodo_pago").val(),
      'moneda':$("#moneda").val(),
      'rfc':$("#rfc").val(),
      'productos':productos,
      'subtotal':$("#subtotal").val(),
      'impuestos':$("#impuestos1").val(),
      'total':$("#total1").val()
      */
    },
    error:function(error){
      console.log(error);
    }
  });
});