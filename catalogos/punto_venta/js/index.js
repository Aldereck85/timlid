var index;
var tax = [];
var id_categoria;
var id_marca;
var pre_subtot = 0;
var pre_tot = 0;
var tooltip_name = "";
questExistWindows();
$(document).ready( () => {
    
    //document.querySelector("#txtPassAdmin").addEventListener('click',function(e){e.target.type = "password"});
  $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
  checkCashRegister();
  
  document.getElementById("txtSearchProduct").focus();
  
  loadCombo('taxSelect','#cmbTax',"","","Seleccione un impuesto...");
  loadCombo('taxSelect','#cmbUpdateTax',"","","");
  // loadCombo('productCategories',"#cmbCategoria",1,"","");
  // loadCombo('productTradeMark',"#cmbMarca",1,"","");
  loadCombo('branchOffices','#cmbSucursal','','','');
  loadCombo('responsible','#cmbResponsable','','','');
  loadCombo('money','#cmbMoneda',100,'','');

  loadCombo('cfdiUse','#cmbCFDIUse','','','');
  loadCombo('paidType','#cmbPaidType','','','');
  loadCombo('money','#cmbCurrency',100,'','');

  loadCombo('cfdiUse','#cmbCFDIUseGeneral','','','');
  loadCombo('paidType','#cmbPaidTypeGeneral','','','');
  loadCombo('money','#cmbCurrencyGeneral',100,'','');
  
  cmbClient = new SlimSelect({
    select: '#cmbClient',
    deselectLabel: '<span class="">✖</span>',
    
    addable: function (value) {
      loadCombo("cmb_regimen","#cmbRegimen","","","");
      loadCombo("cmb_vendedor","#cmbVendedorNC","","","");
      loadCombo("cmb_mediosContacto","#cmbMedioContactoCliente","","","");
      $("#modal_create_client").modal("toggle");
      return;
    }
  });
  loadCombo('clientSelect','#cmbClient',"","","");

  cmbTax = new SlimSelect({
    select: '#cmbTax',
    placeholder: 'Seleccione un impuesto...'
  });
  
  cmbRateOrFee = new SlimSelect({
    select: '#cmbRateOrFee',
    placeholder: 'Seleccione una tasa'
  });

  cmbUpdateTax = new SlimSelect({
    select: '#cmbUpdateTax',
    placeholder: 'Seleccione un impuesto...'
  });

  cmbUpdateRateOrFee = new SlimSelect({
    select: '#cmbUpdateRateOrFee',
    placeholder: 'Seleccione una tasa'
  });

  cmbDocument = new SlimSelect({
    select: '#cmbDocument'
  })

  cmb_cash_register = new SlimSelect ({
    select: '#cmb_cash_register',
    placeholder: 'Seleccione una caja...',
    addable: function (value) {
        $("#add_cash_register").modal("toggle");
        $("#opening_cash_register").modal("toggle");
        return;
    }
  })

  cmbTipoUsuario = new SlimSelect({
    select: '#cmbTipoUsuario',
    deselectLabel: '<span class="">✖</span>',
    });

  cmb_branchOffice = new SlimSelect({
    select: '#cmb_branchOffice',
    placeholder: 'Seleccion una sucursal...'
  });

  

  new SlimSelect({
    select: '#cmbSucursal',
    placeholder: 'Seleccione una sucursal...'
  })

  new SlimSelect({
    select: '#cmbMoneda',
    placeholder: 'Seleccione una moneda...'
  })

  cmbCategoria = new SlimSelect({
    select: '#cmbCategoria',
    placeholder: 'Seleccione una categoría...',
    addable: function (value) {
      $("#add_category_product").modal("toggle");
      return;
    }
  });
  document.getElementById('btnAddCategoryProduct').addEventListener("click",()=>{
    var value = document.getElementById('txtCategoryProduct').value;
    addProductCategories(value);
    loadCombo('productCategories',"#cmbCategoria",1,"","");
  });
  cmbMarca = new SlimSelect({
    select: '#cmbMarca',
    placeholder: 'Seleccione una marca...',
    addable: function (value) {
      $("#add_mark_product").modal("toggle");
      return;
    }
  });
  document.getElementById('btnAddMarkProduct').addEventListener("click",()=>{
    var value = document.getElementById('txtMarkProduct').value;
    getProductTradeMark(value);
    loadCombo('productTradeMark',"#cmbMarca",1,"","");
  })

  new SlimSelect({
    select: '#cmbUpdateCategoria',
    placeholder: 'Seleccione una categoría...',
    addable: function (value) {
      addProductCategories(value)
      return {
        value:id_categoria,
        text: value
      }
    }
  });

  new SlimSelect({
    select: '#cmbUpdateMarca',
    placeholder: 'Seleccione una marca...',
    addable: function (value) {
      getProductTradeMark(value)
      return {
        value:id_marca,
        text:value
      }
    }
  });

  new SlimSelect({
    select: '#cmbPriceModalUpdateProductTicket',
    placeholder: 'Seleccione un precio'
  })

  cmbMovementType = new SlimSelect({
    select: '#cmbMovementType',
    placeholder: 'Seleccione un tipo de movimiento'
  });

  new SlimSelect({
    select: '#cmbRegimen',
    placeholder: 'Seleccione un régimen...',
    deselectLabel: '<span class="">✖</span>',
  });

  new SlimSelect({
    select: '#cmbMedioContactoCliente',
    placeholder: 'Seleccione un medio...',
    deselectLabel: '<span class="">✖</span>',
  });

  new SlimSelect({
    select: '#cmbVendedorNC',
    placeholder: 'Seleccione un vendedor...',
    deselectLabel: '<span class="">✖</span>',
  });

  SS_cmbPais = new SlimSelect({
    select: '#cmbPais',
    placeholder: 'Seleccione un pais...',
    deselectLabel: '<span class="">✖</span>',
  });

  SS_cmbEstado = new SlimSelect({
    select: '#cmbEstado',
    placeholder: 'Seleccione un estado...',
    deselectLabel: '<span class="">✖</span>',
  });

  cmbCFDIUse = new SlimSelect({
    select: '#cmbCFDIUse',
    placeholder: 'Seleccion un uso CFDI...'
  });

  cmbPaidMethod = new SlimSelect({
    select: '#cmbPaidMethod',
    placeholder: 'Seleccion un método de pago...',
    
    data: [
      {value: 'PUE' , text: 'PUE - Pago en una sola exhibición (de contado)'},
      {value: 'PPD' , text: 'PPD - Pago en parcialidades o diferido (total o parcialmente a crédito)'}
    ]
  });

  cmbPaidType = new SlimSelect({
    select: '#cmbPaidType',
    placeholder: 'Seleccion un método de pago...'
  });

  cmbCurrency = new SlimSelect({
    select: '#cmbCurrency',
    placeholder: 'Seleccion un moneda...'
  });

  cmbCFDIUseGeneral = new SlimSelect({
    select: '#cmbCFDIUseGeneral',
    placeholder: 'Seleccion un uso CFDI...'
  });

  cmbPaidMethodGeneral = new SlimSelect({
    select: '#cmbPaidMethodGeneral',
    placeholder: 'Seleccion un método de pago...',
    
    data: [
      {value: 'PUE' , text: 'PUE - Pago en una sola exhibición (de contado)'},
      {value: 'PPD' , text: 'PPD - Pago en parcialidades o diferido (total o parcialmente a crédito)'}
    ]
  });

  cmbPaidTypeGeneral = new SlimSelect({
    select: '#cmbPaidTypeGeneral',
    placeholder: 'Seleccion un método de pago...'
  });

  cmbCurrencyGeneral = new SlimSelect({
    select: '#cmbCurrencyGeneral',
    placeholder: 'Seleccion un moneda...'
  });

  cmbPeriodicity = new SlimSelect({
    select: '#cmbPeriodicity',
    placeholder: 'Seleccione un periodo...'
  });
  cmbMonth = new SlimSelect({
    select: '#cmbMonth',
    placeholder: 'Seleccione un mes o bimestre...'
  });

  cmbYears = new SlimSelect({
    select: '#cmbYears',
    placeholder: 'Seleccione un año...'
  });

  cmbEmpleado = new SlimSelect({
    select: '#cmbEmpleado',
    placeholder: 'Seleccione un cajero...',
    // addable: function (value) {
    //     $("#agregar_Personal").modal("toggle");
    // }
  });
  //cmbEmpleado
  loadCombo('cajeros','#cmbEmpleado',"","","");

  cmbGenero = new SlimSelect({
      select: '#cmbGenero',
      placeholder: 'Seleccione un genero...'
  });

  cmbEstadoPersonal = new SlimSelect({
      select: '#cmbEstadoPersonal',
      placeholder: 'Seleccione un estado...'
  });

  // cmbRoles = new SlimSelect({
  //     select: '#cmbRoles',
  //     placeholder: 'Seleccione un rol...'
  // });

  topButtons = [
    {
      text: '<div data-toggle="tooltip" data-placement="top" title="Atajo: ctrl + F6"><img src="../../img/punto_venta/ICONO VENTA PENDIENTE-01-01.svg" width="30" > Venta pendiente</div>',
      className: "btn-table-custom--blue",
      action: () => {
        
        if(tblTicket.rows().count())
        {
          updatePendingSale();
        } else {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/warning_circle.svg",
            msg: "No hay productos en la tabla",
          });
        }
        
      },
    },
    {
      text: '<div data-toggle="tooltip" data-placement="top" title="Atajo: ctrl + F7"><img src="../../img/punto_venta/ICONO VER VENTA PENDIENTE-01-01.svg" width="30" > Ver venta pendiente</div>',
      className: "btn-table-custom--blue",
      action: () => {
        $("#modal_pedding_sale").modal("toggle");
      },
    },
    // {
    //   text: '<div data-toggle="tooltip" data-placement="top" title="Atajo: ctrl + Enter"><img src="../../img/punto_venta/ICONO REALIZAR VENTA-01-01.svg" width="30" > Vender</div>',
    //   className: "btn-table-custom--blue",
    //   action: function () {
    //     get_ifProductoPrescription();
        
    //   },
    // },
    // {
    //     text: '<div><img src="../../img/timdesk/delete.svg" width="20"> Limpiar tabla</div>',
    //     className: "btn-table-custom--blue",
    //     action: function () {
    //         var caja_id = $("#txtCashRegisterId").val();
            
    //         document.getElementById("subtotal-ticket").innerHTML = "$0.00";


    //         var elem1 = document.getElementById("ticket-taxes-names");
    //         while(elem1.firstChild) {
    //             elem1.removeChild(elem1.firstChild);
    //         }
            
    //         var elem2 = document.getElementById("ticket-taxes-prices");
    //         while(elem2.firstChild) {
    //             elem2.removeChild(elem2.firstChild);
    //         }
            
    //         document.getElementById("ticket-total-price").innerHTML = "$0.00";
    //         deleteAllProductsTableTemp(caja_id)
    //         getSubtotalsTableTemp(caja_id);
            
    //         tblTicket.clear().draw();

    //     },
    // }
  ]

  $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();
  
  tblTicket = $("#tblTicket").DataTable({
    language: setFormatDatatables(),
    info: false,
    scrollX: true,
    pageLength: 15,
    responsive: true,
    lengthChange: false,
    bFilter: false,
    bPaginate: false,
    ordering: false,
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
    columnDefs: 
    [
      {
        targets:[0],
        visible: false,
        searchable: false
      },
      {
        targets:[1],
        render : function(data,type,full,meta){
          return '<span data-toggle="tooltip" data-placement="top" title="Doble click para modificar cantidad">' + data + '</span>'
        },
        
      }
    ],
    drawCallback: function (settings) {
      $('[data-toggle="tooltip"]').tooltip({
        boundary: 'window',
        container: 'body'
      });
    },
    
    
  });
  
  
  // loadProductServiceKeyTable();
  // loadProductUnitKeyTable();
  
    
});

table_tax = loadTaxTable();
table_tax_update = loadTaxUpdateTable();

$(document).on("change", "#cmbPais", function(){
  let html = "";
  let PKPais = $("#cmbPais").val();
  $.ajax({
    url: "php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_estados", pais: PKPais },
    dataType: "json",
    success: function (respuesta) {
      html += '<option data-placeholder="true"></option>';
      $.each(respuesta, function (i) {
        html +=
          '<option value="' +
          respuesta[i].PKEstado +
          '" ' +
          ">" +
          respuesta[i].Estado +
          "</option>";
      });

      $("#cmbEstado").html(html);
    },
    error: function (error) {
      console.log(error);
    },
  });
});

$(document).on("keyup","#txtCheckProduct",function(e){
  value = $(this).val();
  suc = document.getElementById("txtBranchOfficeId").value;
  console.log(suc);
  var code = e.key;
  if(code === "Enter"){
    e.preventDefault();
  
    $.ajax({
      method: "POST",
      url: "php/funciones.php",
      data: {
        clase: 'get_data',
        funcion: 'get_product',
        value: value,
        value1: "",
        value2: suc
      },
      dataType: 'json',
      success: function(respuesta){
        r = respuesta;
        console.log(r);
        if(r.length === 1)
        {
          if(r.id !== null)
          {
            $("#txtInternalKey").val(r[0].clave);
            $("#txtDescription").val(r[0].nombre);
            $("#txtPrice").val(numeral(parseFloat(r[0].precio_venta_neto1)).format('0,000,000,000.00'));
            $("#txtStock").val(r[0].existencia);
            if(r[0].imagen){
              $("#img-product").attr("src",r[0].imagen);
            }
          } else {
            Lobibox.notify("error", {
              size: "mini",
              rounded: true,
              delay: 3100,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../img/timdesk/warning_circle.svg",
              msg: "No se encontraron coincidencias.",
            });
          }
        } else {
          $("#modal_products_found_seeker_checker").modal("show");

          $('#modal_products_found_seeker_checker').on('shown.bs.modal', function () {
            
            tbl_products_found_seeker_checker = loadProductsFoundSeekerChecker(r);
            tbl_products_found_seeker_checker.columns.adjust().draw();
          });
        }
        $("#txtCheckProduct").val("");
      },
      error: function(error){
        console.log(error);
      }

    });
  }
});

$(document).on("keyup","#txtSearchProduct",function(e){
  
  value = $(this).val();
  suc = document.getElementById("txtBranchOfficeId").value;
  var code = e.key;
  if(code == "Enter"){
    //e.preventDefault();
    
    
    if(value !== null && value !== ""){
        $("#loader2").css("display","block");
        $("#loader2").addClass("loader");
        $.ajax({
            method: "POST",
            url: "php/funciones.php",
            data: {
            clase: 'get_data',
            funcion: 'get_product',
            value: value,
            value1: "",
            value2: suc
            },
            dataType: 'json',
            success: function(respuesta){
            r = respuesta;
            console.log(r);
            cantidad = 0;
            if (r.length > 1) {

                tbl_products_found_seeker = loadProductsFoundSeeker(r);
                tbl_products_found_seeker.columns.adjust().draw();
                $("#modal_products_found_seeker").modal("toggle");

            } else if (r.length == 1) {
                
                if(r.id !== null){
                    if(r[0].existencia > 0){
                        addRowTableTicket(r[0]);
                    } else {
                        producto = r[0].clave + " - " + r[0].nombre;
                        Lobibox.notify("error", {
                            size: "mini",
                            rounded: true,
                            delay: 5000,
                            delayIndicator: false,
                            position: "center top",
                            icon: true,
                            img: "../../img/timdesk/warning_circle.svg",
                            msg: producto+" no tiene exsitencia.",
                        });
                    }
                } else {
                Lobibox.notify("error", {
                    size: "mini",
                    rounded: true,
                    delay: 3100,
                    delayIndicator: false,
                    position: "center top",
                    icon: true,
                    img: "../../img/timdesk/warning_circle.svg",
                    msg: "No se encontraron coincidencias.",
                });
                }
                
            } else {
                Lobibox.notify("error", {
                size: "mini",
                rounded: true,
                delay: 3100,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../img/timdesk/warning_circle.svg",
                msg: "No se encontraron coincidencias.",
                });
            }
            $('#txtSearchProduct').val("");
            $(".loader").fadeOut("slow");
            $("#loader").removeClass("loader");
            },
            error: function(error){
            console.log(error);
            }

        });
    } 
  }
});

$("#tbl_products_found_seeker tbody").on('click', 'tr', function(e){
  var data = $("#tbl_products_found_seeker").DataTable().row( e.currentTarget ).data();
  console.log(data);
  if(data.existencia > 0){
    addRowTableTicket(data);
  } else {
    producto = data.clave + " - " + data.nombre;
    Lobibox.notify("error", {
        size: "mini",
        rounded: true,
        delay: 5000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../img/timdesk/warning_circle.svg",
        msg: producto+" no tiene exsitencia.",
    });
  }
  
  $("#modal_products_found_seeker").modal("toggle");
});

$("#tbl_products_found_seeker_checker tbody").on('click', 'tr', function(e){
  var data = $("#tbl_products_found_seeker_checker").DataTable().row( e.currentTarget ).data();

  document.getElementById("txtInternalKey").value = data.clave;
  document.getElementById("txtDescription").value = data.nombre;
  document.getElementById("txtPrice").value = numeral(data.precio_venta_neto1).format('0,000,000,000.00');
  document.getElementById("txtStock").value = data.existencia;
  //document.getElementById("img-product").src = data.imagen;

  $("#modal_products_found_seeker_checker").modal("hide");
});

$("#tbl_pedding_sales tbody").on("click","tr",(e) => {
  var id = document.getElementById("txtCashRegisterId").value;
  var data = $("#tbl_pedding_sales").DataTable().row( e.currentTarget ).data();
  
  const swalWithBootstrapButtons = Swal.mixin({
    customClass: {
      actions: "d-flex justify-content-around",
      confirmButton: "btn-custom btn-custom--border-blue",
      cancelButton: "btn-custom btn-custom--blue",
    },
    buttonsStyling: false,
  });
  
  if(tblTicket.rows().count() > 0){
    swalWithBootstrapButtons.fire(
      {
        title: "",
        text: "Hay una venta en progreso. ¿Desea añadir los productos de esta venta pendiente?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: '<span class="verticalCenter">Aceptar</span>',
        cancelButtonText: '<span class="verticalCenter">Cancelar</span>',
        reverseButtons: false,
      }
    ).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          method: "POST",
          url: "php/funciones.php",
          data: {
            clase: 'save_data',
            funcion: 'save_peddingProductData',
            value: data.folio,
            value1: id
          },
          dataType: 'json',
          success: function(respuesta){
            addRowTableTicket(respuesta);
            $("#modal_pedding_sale").modal("toggle");
            $('#txtSearchProduct').trigger('focus');
          },
          error: function(error){
            console.log(error);
          }
      
        });

      } else if (result.dismiss === Swal.DismissReason.cancel){
        $("#modal_pedding_sale").modal("toggle");
        $('#txtSearchProduct').trigger('focus');
      } 
    });
  } else {
    $.ajax({
      method: "POST",
      url: "php/funciones.php",
      data: {
        clase: 'save_data',
        funcion: 'save_peddingProductData',
        value: data.folio,
        value1: id
      },
      dataType: 'json',
      success: function(respuesta){
        addRowTableTicket(respuesta);
        $("#modal_pedding_sale").modal("toggle");
        $('#txtSearchProduct').trigger('focus');
      },
      error: function(error){
        console.log(error);
      }
    });
  }

});

document.getElementById("btnCancelarActualizacionOpeningCashRegister").addEventListener("click",() => {
    window.location.href = "../dashboard.php";
//   var caja_id = document.getElementById('txtCashRegisterId').value;
//   var suc = document.getElementById('txtBranchOfficeId').value;
//   console.log("caja",caja_id,"=>","sucursal",suc);
//   if(!caja_id && !suc)
//   {
//     $("#cancel_add_cash_register").modal({
//       show:true,
//       backdrop: 'static',
//       keyboard: false
//     });
//   }
});

$("#tblTicket tbody").on("dblclick","td",function(e){
  row_index = tblTicket.cell(this).index().row;
  cell_index = tblTicket.cell( this ).index().column
  switch(cell_index){
    case 1:
      $("#quantity").html(tblTicket.cell(this).data())
      $("#update_quantity").modal("toggle");
      $("#txtIdRow").val(row_index);
      break;
    case 4:
      alert("descuento");
      break;
    case 5:
      alert("precio");
      break;
    case 7:
      
      break;
  }
});

$("#tblTicketsView tbody").on("click","td",function(e){
  var tblTicketsView = $("#tblTicketsView").DataTable();
  row_index = tblTicketsView.cell(this).index().row;
  cell_index = tblTicketsView.cell( this ).index().column
  row_data = tblTicketsView.row( row_index ).data();
  console.log(e.target);
  switch (cell_index) {
    case 1:
      if(e.target.getAttribute("id") === "folio_ticket"){
        var data = e.target.getAttribute("data-id");
        $("#modal_tickets_view").modal("hide")
        if($("#tblDetailsTicketsView tbody tr").length > 0){
          $("#tblDetailsTicketsView").DataTable().destroy();
          loadDetailsTicketsTable(data);
        } else {
          loadDetailsTicketsTable(data);
        }
      }
      
      break;
    case 5:
      if(e.target.getAttribute("id") === "detalle_factura"){
        
        var data = e.target.getAttribute("data-id");
        
        var url = "../facturacion/detalle_factura.php";

        $.redirect(url, {
            idFactura: data,
          },
         "POST",
         "_blank"
         );
         $('#detalle_factura').tooltip("hide");
      }
      break;
  
    case 6:
        if(e.target.getAttribute("id") === "pdf_factura"){
            $('#pdf_factura').tooltip("hide");
        }
        if(e.target.getAttribute("id") === "xml_factura"){
            $('#xml_factura').tooltip("hide");
        }
        if(e.target.getAttribute("id") === "cancel"){
            $('[data-toggle="tooltip"]').tooltip("hide");
            id_ticket = e.target.getAttribute("data-id");
            folio_ticket = row_data.folio;
            cancelTicket(id_ticket,folio_ticket);
            
        }
        if(e.target.getAttribute("id") === "print_ticket")
        {
            id_ticket = e.target.getAttribute("data-id");
            console.log(id_ticket);
            getPrintTicket(id_ticket,"","","","");
        }
    break;
  }
})

$("#tblTicket tbody").on("click","td",function(e){
  var caja_id = $("#txtCashRegisterId").val();
  row_index = tblTicket.cell(this).index().row;
  cell_index = tblTicket.cell( this ).index().column
  row_data = tblTicket.row( row_index ).data();
  
  switch(cell_index){
    case 1:
      break;
    case 2:
      
      if(e.target.getAttribute("id") === "edit_quantity"){
        console.log(tblTicket.row(row_index).data()[1]);
        $("#quantity").text(tblTicket.row(row_index).data()[1]);
        $("#update_quantity").modal("toggle");
        $("#txtIdRow").val(row_index);
      }
     break;
    case 8:
      if(e.target.getAttribute("id") === "edit"){
        $('[data-toggle="tooltip"]').tooltip("hide");
        var id = e.target.getAttribute("data-id");
        
        $("#modal_update_product_ticket").modal('toggle');
        document.getElementById("txtRowIdUpdate").value = row_index;
        document.getElementById("txtQuantityModalUpdateProductTicket").value = row_data[1];
        document.getElementById("txtDiscountModalUpdateProductTicket").value = row_data[5];
        var precio_unitario = row_data[6].split("$");
        
        document.getElementById("cmbPriceModalUpdateProductTicket").value = numeral(precio_unitario[1]).format('0,000,000,000.00');
        document.getElementById("txtProductUpdateTicketId").value = id;
        document.getElementById("txtProductNameUpdateModal").innerHTML = row_data[3];

        $.ajax({
          method: "POST",
          url: "php/funciones.php",
          data: {
            clase: 'get_data',
            funcion: 'get_productPrice',
            value: row_data[0]
          },
          dataType: 'json',
          success: function(respuesta){
            html = "<option data-placeholder='true'></option>";
            respuesta.forEach( (i) => {
              if(numeral(i).format('0,000,000,000.00') === numeral(precio_unitario[1]).format('0,000,000,000.00')){
                html += '<option value="'+i+'" selected>'+numeral(i).format('0,000,000,000.00')+'</option>';
              } else {
                html += '<option value="'+i+'">'+numeral(i).format('0,000,000,000.00')+'</option>';
              }
              
            });
            $("#cmbPriceModalUpdateProductTicket").html(html);
          },
          error: function(error){
            console.log(error);
          }
        });
      }
      if(e.target.getAttribute("id") === "delete"){
        
        $('[data-toggle="tooltip"]').tooltip("hide");
        var caja_id = document.getElementById("txtCashRegisterId").value;
        var id = e.target.getAttribute("data-id");
        tblTicket.row( $(this).parents('tr') ).remove().draw();
        document.getElementById("subtotal-ticket").innerHTML = "$0.00";


          var elem1 = document.getElementById("ticket-taxes-names");
          while(elem1.firstChild) {
            elem1.removeChild(elem1.firstChild);
          }
          
          var elem2 = document.getElementById("ticket-taxes-prices");
          while(elem2.firstChild) {
            elem2.removeChild(elem2.firstChild);
          }
          
          document.getElementById("ticket-total-price").innerHTML = "$0.00";
          deleteProductTableTemp(id,caja_id);
          getSubtotalsTableTemp(caja_id);
        
        
      }
      break;
  }
});

$("#tblProductsFinder").on("click","a",function(){
  var suc = document.getElementById("txtBranchOfficeId").value
  var product_id = $(this).data("id");
  
  document.getElementById("txtProductUpdateId").value = product_id;
  $("#product_finder").modal("hide");

});

$("#tblProductsFinderAll").on("click","a",function(){
  var suc = document.getElementById("txtBranchOfficeId").value
  var product_id = $(this).data("id");
  
  document.getElementById("txtProductAllUpdateId").value = product_id;

  
  $("#product_finder").modal("hide");

});

$(document).on("click","#btnSearchProduct",function(){
  value = $("#txtCheckProduct").val();
  suc = document.getElementById("txtBranchOfficeId").value
  $.ajax({
    method: "POST",
    url: "php/funciones.php",
    data: {
      clase: 'get_data',
      funcion: 'get_product',
      value: value,
      value1: "",
      value2: suc
    },
    dataType: 'json',
    success: function(respuesta){
      r = respuesta;
      
      if(r.length === 1)
      {
        if(r.id !== null)
        {
          $("#txtInternalKey").val(r[0].clave);
          $("#txtDescription").val(r[0].nombre);
          $("#txtPrice").val(numeral(parseFloat(r[0].precio_venta_neto1)).format('0,000,000,000.00'));
          $("#txtStock").val(r[0].existencia);
          if(r[0].imagen){
            $("#img-product").attr("src",r[0].imagen);
          }
        } else {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3100,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/warning_circle.svg",
            msg: "No se encontraron coincidencias.",
          });
        }
      } else {
        $("#modal_products_found_seeker_checker").modal("show");

        $('#modal_products_found_seeker_checker').on('shown.bs.modal', function () {
          tbl_products_found_seeker_checker = loadProductsFoundSeekerChecker(r);
          tbl_products_found_seeker_checker.columns.adjust().draw();
        });
      }
    },
    error: function(error){
      console.log(error);
    }

  });
});

$(document).on('shown.bs.modal',function (e) {
  target = e.target.id;
  e.preventDefault();
  $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
  switch (target) {
    case "product_finder":
        getCountBranchOffice();
        if ($("#tblProductsFinder tbody tr").length === 0 ) {
            $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
            tblProductsFinder = loadProductsFinder();
            tblProductsFinderAll = loadProductsFinderAll();
        } else {
            $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();

            $("#tblProductsFinder").DataTable().ajax.reload();
            
            $("#tblProductsFinderAll").DataTable().ajax.reload();

        }
    //   tblProductsFinder.ajax.reload();
    //   tblProductsFinder.columns.adjust();
    //   tblProductsFinder.on( 'key-focus',  ( e, datatable, cell, originalEvent ) => {
        
    //   var rowData = datatable.row( cell.index().row ).data(); 
    //   console.log(rowData.id);
    //   document.addEventListener('keyup', (e) =>{
        
    //     if($("#product_finder").is(":visible") && e.key === "Enter"){
          
    //       $("#modal_update_product").modal("show");
          
    //       loadUpdateProductoData(rowData.id,suc);
          
    //       $("#product_finder").modal("hide");
    //     }
    //   })
        
    //   });
      break;
    case "create_product":
      loadCombo('productCategories',"#cmbCategoria",1,"","");
      loadCombo('productTradeMark',"#cmbMarca",1,"","");

      tblTax.columns.adjust();
      $("#txtClaveSat").val("Clic para asignar clave de producto/servicio");
      $("#txtUnidadMedida").val("Clic para asignar clave unidades de medida");
      $("#general-data-product-tab").removeClass("is-invalid-tab");
      $("#additional-data-product-tab").removeClass("is-invalid-tab");
      cmbMarca.set("");
      document.getElementById("cmbMarca").removeAttribute("disabled");
      loadProductServiceKeyTable();
      loadProductUnitKeyTable();
      break;
    case "opening_cash_register":
        if($("#opening_cash_register").is(":visible")){
            $("#opening_cash_register").on("keyup",function(e){
                var key = e.key;
                if(key === 'Enter')
                {
                    addOpeningCashRegister();
                }
            });
        }
        document.getElementById('div_cmbCashRegister').classList.add("no-visible");
        document.getElementById('div_cmbUserType').classList.add("no-visible");
        document.getElementById('div_passAdmin').classList.add("no-visible");
        document.getElementById('div_cmbEmployer').classList.add("no-visible");
        if(!document.getElementById("txtBranchOfficeId").value){
            loadCombo('branchOffices','#cmb_branchOffice','','','Seleccione una sucursal...');
            //loadCombo('cash_registers','#cmb_cash_register','','','Seleccione una caja...');
            
        } else {
            // cmb_cash_register.set(document.getElementById("txtCashRegisterId").value);
            document.getElementById('div_cmbUserType').classList.remove("no-visible");
            //document.getElementById('div_passAdmin').classList.remove("no-visible");
            var value = document.getElementById('cmb_branchOffice').value;
            cmb_branchOffice.set(document.getElementById("txtBranchOfficeId").value);
            loadCombo('cash_registers','#cmb_cash_register',document.getElementById("txtCashRegisterId").value,value,'Seleccione una caja...');
        }

      
      break;
    case 'update_quantity':
      //$("#update_quantity").on("keydown",function(e){
        
        // var val = parseInt($("#quantity").text());
        
        // if(e.key === 'ArrowUp'){
        //   console.log(val + 1 );
        //   $("#quantity").text(val + 1);
        // }
        // if(e.key === 'ArrowDown'){
        //   if(val !== "1"){
        //     $("#quantity").text(val - 1);
        //   }
        // }
        // if(e.key === "Enter"){
        //   updateQuantityProduct();
        //   $("#update_quantity").modal("hide");
        //   $("#quantity").text("");
        // }
      //});
      break;
    case 'modal_update_product':
      table_tax_update.columns.adjust();
      var suc = document.getElementById("txtBranchOfficeId").value
      var product_id = "";
      if(document.getElementById("txtProductUpdateId").value !== "" && document.getElementById("txtProductUpdateId").value !== undefined){
        product_id = document.getElementById("txtProductUpdateId").value;
        loadUpdateProductoData(product_id,suc);
      } else if(document.getElementById("txtProductAllUpdateId").value !== "" && document.getElementById("txtProductAllUpdateId").value !== undefined){
        product_id = document.getElementById("txtProductAllUpdateId").value;
        
        loadUpdateProductoData(product_id,'');
      }
      
      $.ajax({
        method: "POST",
        url: "php/funciones.php",
        data: {
          clase: 'get_data',
          funcion: 'get_productTaxesTable',
          value: product_id,
        },
        dataType: 'json',
        success: function(respuesta){
          r = respuesta.data;
          
          if(r.length > 0){
            r.forEach((e) => {
              table_tax_update.row.add([e.id,e.nombre,e.tasa,e.funciones]).draw(false);
            })
          }

        },
        error: function(error){
          console.log(error);
        }
      });
      break;
    case 'modal_pedding_sale':
      var caja_id = document.getElementById('txtCashRegisterId').value;
      tbl_pedding_sales = loadPeddingSales(caja_id);
      tbl_pedding_sales.columns.adjust();
      tbl_pedding_sales.ajax.reload();
      break;
    case 'modal_product_sales':
        $(this).find('#txtMontoRecibido').focus();
      break;
    case 'add_cash_register_movements':
      //getCurrentBalance();
      break;
    case 'modal_tickets_view':

      break;
    
    case 'modal_invoice_general':
      loadGeneralInvoice();
      $("#modal_tickets_view").modal("hide");
      break;
    case 'product_checker':
        $("#product_checker").find('#txtCheckProduct').focus();
    break;
    case 'modal_add_professional_license':
        $("#txtProfessionalLicense").trigger('focus');
        if($("#modal_add_professional_license").is(":visible")){
            document.getElementById('modal_add_professional_license').addEventListener("keyup",(e)=>{
                var key = e.key;
                if(key === 'Enter')
                {
                    saveProfesionalLicencse();
                }
            })
        }
    break;
    
  }
});



if($("#txtPassAdmin").is(":visible")){
    $("#txtPassAdmin").trigger('focus');
}


document.getElementById('cmb_branchOffice').addEventListener("change",() =>{
    var value = document.getElementById('cmb_branchOffice').value;
    loadCombo('cash_registers','#cmb_cash_register','',value,'Seleccione una caja...');
    document.getElementById('div_cmbCashRegister').classList.remove("no-visible");
   // document.getElementById('div_cmbUserType').classList.remove("no-visible");
    
});
document.getElementById("cmbTipoUsuario").addEventListener("change",()=>{
    var val = cmbTipoUsuario.selected();
    if(parseInt(val) === 1){
        
        document.getElementById('div_passAdmin').classList.remove("no-visible");
        document.getElementById('txtPassAdmin').setAttribute("required", true);
        if($("#txtPassAdmin").is(":visible")){
            $("#txtPassAdmin").trigger('focus');
        }
        document.getElementById('div_cmbEmployer').classList.add("no-visible");
        document.getElementById('cmbEmpleado').removeAttribute("required"); 
    } else {
        document.getElementById('div_passAdmin').classList.add("no-visible");
        document.getElementById('txtPassAdmin').removeAttribute("required"); 
        document.getElementById('div_cmbEmployer').classList.remove("no-visible");
        document.getElementById('cmbEmpleado').setAttribute("required", true);
    }
});

$(document).on('change','#txtQuantityCashRegister',function(){
  this.value = parseFloat(this.value).toFixed(2);
  if(this.value < 0){
    this.value = parseFloat(0.00).toFixed(2);
  }
});

$(document).on('hidden.bs.modal', function (e) {
  target = e.target.id;
  e.preventDefault();
  $('#txtSearchProduct').trigger('focus');
  switch (target) {
    case "product_finder":
      
      tblProductsFinder.search("").draw();
      $('#txtSearchProduct').trigger('focus');
      break;
    case "product_checker":
      document.getElementById("txtCheckProduct").value = "";
      document.getElementById("txtInternalKey").value = "";
      document.getElementById("txtDescription").value = "";
      document.getElementById("txtPrice").value = "";
      document.getElementById("txtStock").value = "";
      $('#txtSearchProduct').trigger('focus');
      document.getElementById("img-product").src="../../img/bienvenido.png";
      break;
    case "create_product":
      tblTax = $("#tblTax").DataTable();
      tblTax.clear().draw();
      $(".data_fiscal_product").css("display","none");
      resetNavbar("btn-controls-product-modal");
      resetNavbarContent("nav-tabContent");
      $("#txtClaveSat").val("Clic para asignar clave de producto/servicio");
      $("#txtUnidadMedida").val("Clic para asignar clave unidades de medida");
      clearAlertsInput("#txtClave",'',"productKey");
      clearAlertsInput("#txtNombre",'',"productName");
      clearAlertsInput("#cmbCategoria",'',"productCategory");
      clearAlertsInput("#cmbMarca",'',"productBrand");
      clearAlertsInput("#txtPrecioCompra",'',"productPurchasePrice");
      clearAlertsInput("#txtUtilidad1",'',"productUtility");
      clearAlertsInput("#chkProduct",'#chkService',"productType");
      clearAlertsInput("#txtClaveSatId",'',"productServiceKey");
      clearAlertsInput("#txtUnidadMedidaId",'',"productUnitKey");
      getAllInputsModal("form-data-product");
      getAllInputsModal("form_tax");
      document.getElementById("btnAddProduct").removeAttribute("disabled");
      $('#txtSearchProduct').trigger('focus');

      var elmtTable = document.getElementById('tblExistProduct');
      
      var rowCount = elmtTable.rows.length;
      
      for (var x=1; x<rowCount; x++) {
        elmtTable.deleteRow(x);
      }

      document.getElementById("tblExistProduct").classList.remove('yes-visible-table');
      document.getElementById("tblExistProduct").classList.add('no-visible');

      document.getElementById("input-text-exist").classList.remove('yes-visible-inputs');
      document.getElementById("input-text-exist").classList.add('no-visible');

      document.getElementById("button-add-exist").classList.remove('yes-visible');
      document.getElementById("button-add-exist").classList.add('no-visible');

      document.getElementById("txtExistencia").removeAttribute("readonly");
      document.getElementById("txtExistencia").value = "";

      $("#tabla_body_medida").html("");
      $("#tabla_body_sat").html("");

      
      break;
    case "add_tax_modal":
      getAllInputsModal('form_tax');
      $('#txtSearchProduct').trigger('focus');
      cmbTax.set("");
      cmbRateOrFee.set("");
      break;
    case "add_cash_register":
      getAllInputsModal('form-data-cash-register');
      $('#txtSearchProduct').trigger('focus');
      if(document.getElementById('txtCashRegisterId').value === "")
      {
        $("#opening_cash_register").modal({
            show: true,
            backdrop: 'static',
            keyboard: false
          });
      }
      break;
    case "modal_products_found_seeker":
      $("#tbl_products_found_seeker").DataTable().destroy();
      $('#txtSearchProduct').trigger('focus');
      break;
    case "modal_products_found_seeker_checker":
      $("#tbl_products_found_seeker_checker").DataTable().destroy();
      $('#txtSearchProduct').trigger('focus');
      break;
    case "modal_update_product":
      table_tax_update.clear().draw();
      $('#txtSearchProduct').trigger('focus');
      break;
    case "modal_pedding_sale":
      $('#txtSearchProduct').trigger('focus');
      break;
    case "modal_product_sales":
      if(!$("#modal_fiscal_data").is(":visible")){
        document.getElementById("txtMontoRecibido").value = "";
        document.getElementById("txtApprovedCredit").value = "";
        document.getElementById("txtApprovedTransfer").value = "";
        document.getElementById("txtMontoCambio").value = "";
      }
      break;
    case "add_cash_closing":
      getAllInputsModal("form-data-cash-closing");
      resetHTMLCashClosing();
      break;
    case 'modal_invoice_general':
      $("#tblGeneralInvoice").DataTable().destroy();
      document.getElementById("txtInitialDate").value = "";
      document.getElementById("txtFinalDate").value = "";
      document.getElementById("generalInvoice-subtotal-ticket").innerHTML = "$0.00";
      document.getElementById("generalInvoice-taxes").innerHTML = "";
      document.getElementById("generalInvoice-total-price").innerHTML = "$0.00";
      break;
    case 'modal_details_tickets_view':
      $("#modal_tickets_view").modal("show");
    break; 
    case 'update_tax_modal':
      cmbUpdateTax.set("");
      cmbUpdateRateOrFee.set("");
    break;
  }
});

$(document).on("click","#btnUpdateQuantity",updateQuantityProduct);

$(document).on("click","#plus-quantity",function(){
  var val = $("#quantity").text();
  var quantity_plus = parseInt(val) + 1;
  $("#quantity").text(quantity_plus);
});

$(document).on("click","#minus-quantity",function(){
  var val = $("#quantity").text();
  var quantity_minus = parseInt(val) - 1
  if(val !== "1"){
    $("#quantity").text(quantity_minus);
  }
});

$(document).on("click","#btnAddQuantityCashRegister",function(){
  $("#opening_cash_register").modal("toggle");
});

$(document).on("click","#txtClaveSat",function(){
  $('#buscar_clave_sat').val("");
  $("#add_producto_service_key").modal('show');
});

$(document).on("click","#txtUnidadMedida",function(){
  $('#buscar_clave_unidad_medida').val("");
  $("#add_unit_product_key").modal('show');
});

$(document).on("change","#cmbDocument",function(){
  val = parseInt(this.value);

  if(val === 2 || val === 3 || val === 4){
    $("#comboClient").css("display","block");
    $("#cmbClient").prop("required",true);
  } else{
    if($("#comboClient").is(":visible")){
      $("#comboClient").css("display","none");
      $("#cmbClient").prop("required",false);
    }
  }
});

$(document).on("change","#cmbClient",(e)=>{
  var id_input = e.target.id;
  const element = document.querySelector("#"+id_input+"");
  
  if(element.classList.contains('is-invalid')){
    element.classList.remove('is-invalid');
    document.getElementById("invalid-ticketClient").style.display = 'none';
  }

});

$(document).on("click","#btnCancelarRegistroCaja",function(){
  $("#cancel_add_cash_register").modal({
    show:true,
    backdrop: 'static',
    keyboard: false
  });
 
});

$(document).on("click","#btnCancelAddCashRegister",function(){
 
  $("#redirect_dashboard").modal({
    show:true,
    backdrop: 'static',
    keyboard: false
  });

  window.location.href = "../dashboard.php";
})

$(document).on("click","#btnCancelarRegistroCaja1",function(){

  $.ajax({
    url: "php/funciones.php",
    method:"POST",
    data: {
      clase: "get_data",
      funcion: "get_countCashRegisterAccounts"
    },
    datatype: "json",
    success: function(respuesta){
      
      r = parseInt(respuesta);

      if(r === 0){
        $("#add_cash_register_question").modal({
          show:true,
          backdrop: 'static',
          keyboard: false
        });
      } else if(r === 1){
        $.ajax({
          url: "php/funciones.php",
          method:"POST",
          data: {
            clase: "get_data",
            funcion: "get_cash_register"
          },
          datatype: "json",
          success: function(respuesta){
            
            r = JSON.parse(respuesta)
            
            document.getElementById("labelCaja").innerHTML = r[0].nombre;
            document.getElementById("labelSucursal").innerHTML = r[0].sucursal;
            document.getElementById("txtCashRegisterId").value = r[0].caja_id
            document.getElementById("txtBranchOfficeId").value =  r[0].sucursal_id;

            document.querySelector("#idBoxSuc").classList.remove("no-visible");
            document.querySelector("#idBoxSuc").classList.add("yes-visible");

            document.querySelector("#itemsBoxSuc").classList.remove("no-visible");
            document.querySelector("#itemsBoxSuc").classList.add("yes-visible");
            deleteAllProductsTableTemp(r[0].caja_id);
           
            document.getElementById("txtPrinterNameUpdate").value = r[0].nombre_impresora;

            if(r[0].activar_inventario !== "Sin inventario"){
              document.querySelector("#txtActiveInventory").classList.add("left-dot","green-dot");
              document.getElementById("txtActiveInventory").innerHTML = r[0].activar_inventario;
            } else {
              document.querySelector("#txtActiveInventory").classList.add("left-dot","yellow-dot");
              document.getElementById("txtActiveInventory").innerHTML = r[0].activar_inventario;
            }
            $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
            tblProductsFinder = loadProductsFinder();
            tblProductsFindelAll = loadProductsFinderAll();
            $(".loader").fadeOut("slow");
            $("#loader").removeClass("loader");
          },
          error: function(error){
            console.log(error);
          }
          
        });
      } else {
        $("#opening_cash_register").modal({
          show: true,
          backdrop: 'static',
          keyboard: false
        });
        $(".loader").fadeOut("slow");
        $("#loader").removeClass("loader");
      }
    },
    error: function(error){
      console.log(error);
    }
  });
  
})

document.getElementById('list_tickets').addEventListener("click",list_tickets);

document.getElementById('btnCancelarRegistroCaja2').addEventListener("click", () =>{
  checkCashRegister();
})

document.getElementById('btnSaveDataCashRegister').addEventListener("click", () =>{
  if($("#form-data-cash-register")[0].checkValidity()){
    bad_nameAccount = 
        $("#invalid-nameAccount").css("display") === "block" ? false : true;
    bad_descriptionAccount = 
        $("#invalid-descriptionAccount").css("display") === "block" ? false : true;
    bad_branchOfficeAccount = 
        $("#invalid-branchOfficeAccount").css("display") === "block" ? false : true;
    bad_moneyAccount = 
        $("#invalid-moneyAccount").css("display") === "block" ? false : true;
    bad_initialBalanceAccount = 
        $("#invalid-initialBalanceAccount").css("display") === "block" ? false : true;
    bad_savePassAdmin = 
        $("#invalid-savePassAdmin").css("display") === "block" ? false : true;
    if(
      bad_nameAccount &&
      bad_descriptionAccount &&
      bad_branchOfficeAccount &&
      bad_moneyAccount &&
      bad_initialBalanceAccount && 
      bad_savePassAdmin
    ){
      var txtNameCashRegister = document.getElementById('txtNameCashRegister').value;
      var cmbSucursal = document.getElementById('cmbSucursal').value;
      var txtSaldoInicial = document.getElementById('txtSaldoInicial').value;
      var cmbMoneda = document.getElementById('cmbMoneda').value;
      var pass_admin = document.getElementById('txtSavePassAdmin').value;

      var json = 
      '{' + 
          '"name":"' + txtNameCashRegister + '",' +
          '"description":"' + txtNameCashRegister + '",' +
          '"branch_office":"' + cmbSucursal + '",' +
          '"type_money":"' + cmbMoneda + '",' +
          '"initial_balance":"' + txtSaldoInicial + '",' +
          '"pass_admin":"'+pass_admin+'"'+
      '}'
      
      $.ajax({
        url: "php/funciones.php",
        method:"POST",
        data: {
          clase: "save_data",
          funcion: "save_cashRegisterAccount",
          value: json
        },
        datatype: "json",
        success: function(respuesta){
          if(respuesta === "true"){
            checkCashRegister();
            $("#add_cash_register").modal("toggle");
            loadCombo('get_branchOffices','#cmb_branchOffice','','','Seleccione una sucursal...');
            loadCombo('cash_registers','#cmb_cash_register','','','Seleccione una caja...');
            Lobibox.notify("success", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../img/timdesk/checkmark.svg",
              msg: "Se guardó la caja con éxito",
              sound:false,
            });
          }
      },
        error: function(error){
          console.log(error);
        }
      });
    }
    
  } else {
    if (!$("#txtNameCashRegister").val()) {
      $("#invalid-nameAccount").css("display", "block");
      $("#txtNameCashRegister").addClass("is-invalid");
    }
    if (!$("#cmbSucursal").val()) {
      $("#invalid-branchOfficeAccount").css("display", "block");
      $("#cmbSucursal").addClass("is-invalid");
    }
    if (!$("#txtSaldoInicial").val()) {
      $("#invalid-initialBalanceAccount").css("display", "block");
      $("#txtSaldoInicial").addClass("is-invalid");
    }
    if (!$("#cmbMoneda").val()) {
      $("#invalid-moneyAccount").css("display", "block");
      $("#cmbMoneda").addClass("is-nameAccount");
    }
    if (!$("#txtSavePassAdmin").val()) {
        $("#invalid-savePassAdmin").css("display", "block");
        $("#txtSavePassAdmin").addClass("is-nameAccount");
      }

  }
});

document.getElementById('txtNameCashRegister').addEventListener("focusout",(e) => {
  json = '{"nombre_caja":"'+document.getElementById('txtNameCashRegister').value+'","sucursal_id":"'+document.getElementById('cmbSucursal').value+'"}';
  $.ajax({
    url: "php/funciones.php",
    method:"POST",
    data: {
      clase: "get_data",
      funcion: "get_checkedIfExistNameCashRegister",
      value: json
    },
    datatype: "json",
    success: function(response){
      r = JSON.parse(response);
      if(parseInt(r[0].count) > 0)
      {
        $("#invalid-nameAccount").css("display","none");
        $("#invalid-nameAccountExist").css("display", "block");
        $("#txtNameCashRegister").addClass("is-invalid");

      } else {
        if($("#invalid-nameAccountExist").is(":visible"))
        {
          $("#invalid-nameAccountExist").css("display", "none");
          $("#txtNameCashRegister").removeClass("is-invalid");
        }
      }
    },
    error: function(error){
      console.log(error);
    }
  });
});

document.getElementById('txtNameCashRegister').addEventListener("keyup", (e) => {
  clearAlertsInput("#txtNameCashRegister",'',"nameAccount");
});

document.getElementById('cmbSucursal').addEventListener("change", (e) => {
  clearAlertsInput("#cmbSucursal",'',"branchOfficeAccount");
});

document.getElementById('txtSaldoInicial').addEventListener("keyup", (e) => {
  clearAlertsInput("#txtSaldoInicial",'',"initialBalanceAccount");
});

document.getElementById('cmbMoneda').addEventListener("change", (e) => {
  clearAlertsInput("#cmbMoneda",'',"moneyAccount");
});

document.getElementById('cmbMoneda').addEventListener("keyup", (e) => {
    clearAlertsInput("#txtSavePassAdmin",'',"savePassAdmin");
  });


$(document).on("click","#btnAddCashRegister",function(){
  
  $("#add_cash_register").modal({
    show:true,
    backdrop: 'static',
    keyboard: false
  });
});

document.getElementById('txtDiscountModalUpdateProductTicket').addEventListener("keyup",(e)=>{
  input = document.getElementById('txtDiscountModalUpdateProductTicket')
  value = input.value;
  if(parseFloat(value) > 100){
    input.value = 100;
  }
});

document.getElementById("btnUpdateProductTicket").addEventListener("click", () => {
  var cantidad = document.getElementById("txtQuantityModalUpdateProductTicket").value;
  var descuento = document.getElementById("txtDiscountModalUpdateProductTicket").value;
  var precio_unitario = document.getElementById("cmbPriceModalUpdateProductTicket").value;
  var caja_id = document.getElementById("txtCashRegisterId").value;
  var producto_id = document.getElementById("txtProductUpdateTicketId").value;
  var nombre_producto = document.getElementById("txtProductNameUpdateModal").innerHTML;
  var row = document.getElementById("txtRowIdUpdate").value;
  var subtotal = cantidad * precio_unitario;
  
  var json = 
  '{'+
      '"cantidad" : "' + cantidad + '",' + 
      '"descuento" : "' + descuento + '",' +
      '"precio_unitario" : "' + precio_unitario + '",' +
      '"caja_id" : "' + caja_id + '",' +
      '"subtotal" : "' + subtotal + '",' +
      '"producto_id":"' + producto_id + '"' +
  '}';

  $.ajax({
    url: "php/funciones.php",
    method:"POST",
    data: {
      clase: "update_data",
      funcion: "update_productTicket",
      value: json
    },
    datatype: "json",
    success: function(response){
        console.log(response);
      r = JSON.parse(response);
      
      if(r){
        $("#modal_update_product_ticket").modal("hide");
        
        Lobibox.notify("success", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/checkmark.svg",
          msg: "Se actualizó los datos de " + nombre_producto + " con éxito",
          sound:false,
        });
        var row_i =  tblTicket.row(row);
        var row_data = row_i.data();
        row_data[1] = cantidad;
        row_data[5] = descuento;
        row_data[6] = "$" + numeral(precio_unitario).format('0,000,000,000.00');
        row_data[7] = "$" + numeral(precio_unitario * cantidad).format('0,000,000,000.00');
        row_i.data( row_data ).draw();
        getSubtotalsTableTemp(caja_id);
      }
    },
    error: function(error){
      console.log(error);
    }
  });

});

document.getElementById("txtMontoRecibido").addEventListener("keyup",() =>{
  var monto_total = parseFloat(document.getElementById("txtImporteTotalVenta").value);
  var monto_recibido = parseFloat(document.getElementById("txtMontoRecibido").value);

  var cambio = monto_recibido - monto_total;
  document.getElementById("txtMontoCambio").value = numeral(cambio).format('0,000,000,000.00');

  if($("#invalid-amount_received").is(":visible")){
    $("#invalid-amount_received").css("display","none");
    $("#txtMontoRecibido").removeClass("is-invalid");
  }

  if($("#btnSaveTicket").is(':disabled')){
    $("#btnSaveTicket").prop('disabled',false);
  }
});

document.getElementById("cash-payment").addEventListener("click", (e) => {
  
  document.getElementById("cash-payment-data").style.display = "block";
  document.getElementById("credit-payment-data").style.display = "none";
  document.getElementById("bank-transfer-details").style.display = "none";
  document.getElementById("txtMontoRecibido").value = "";
  document.getElementById("txtApprovedCredit").value = "";
  document.getElementById("txtApprovedTransfer").value = "";
  document.getElementById("txtMontoCambio").value = "";
  document.getElementById("txtMontoRecibido").focus();
});

document.getElementById("credit-payment").addEventListener("click", (e) => {
  
  document.getElementById("cash-payment-data").style.display = "none";
  document.getElementById("credit-payment-data").style.display = "block";
  document.getElementById("bank-transfer-details").style.display = "none";
  document.getElementById("txtMontoRecibido").value = "";
  document.getElementById("txtApprovedCredit").value = "";
  document.getElementById("txtApprovedTransfer").value = "";
  document.getElementById("txtMontoCambio").value = "";
  document.getElementById("txtApprovedCredit").focus();
});

document.getElementById("bank-transfer").addEventListener("click", (e) => {
  
  document.getElementById("cash-payment-data").style.display = "none";
  document.getElementById("credit-payment-data").style.display = "none";
  document.getElementById("bank-transfer-details").style.display = "block";
  document.getElementById("txtMontoRecibido").value = "";
  document.getElementById("txtApprovedCredit").value = "";
  document.getElementById("txtApprovedTransfer").value = "";
  document.getElementById("txtMontoCambio").value = "";
  document.getElementById("txtApprovedTransfer").focus();
});

document.getElementById("cmb_cash_register").addEventListener("change",()=>{
    document.getElementById("div_cmbUserType").classList.remove('no-visible');

    $("#invalid-selectCashRegister").css("display", "none");
    $("#cmb_cash_register").removeClass("is-invalid");
});

document.getElementById("cmbTipoUsuario").addEventListener("change",(e)=>{
    $("#invalid-passAdmin").css("display", "none");
    $("#txtPassAdmin").removeClass("is-invalid");
    $("#invalid-user_type").css("display", "none");
    $("#cmb_user_type").removeClass("is-invalid");
    $("#invalid-employer_cash").css("display", "none");
    $("#cmbEmpleado").removeClass("is-invalid");
});

document.getElementById("cmbEmpleado").addEventListener("change",(e)=>{
    $("#invalid-passAdmin").css("display", "none");
    $("#txtPassAdmin").removeClass("is-invalid");
    $("#invalid-user_type").css("display", "none");
    $("#cmb_user_type").removeClass("is-invalid");
    $("#invalid-employer_cash").css("display", "none");
    $("#cmbEmpleado").removeClass("is-invalid");
});

document.getElementById("txtPassAdmin").addEventListener("keyup",()=>{
    $("#invalid-user_type").css("display", "none");
    $("#cmb_user_type").removeClass("is-invalid");
    $("#invalid-passAdmin").css("display", "none");
    $("#txtPassAdmin").removeClass("is-invalid");
    $("#invalid-employer_cash").css("display", "none");
    $("#cmbEmpleado").removeClass("is-invalid");
});

document.getElementById("txtPassAdmin").addEventListener("change",()=>{
  $("#invalid-user_type").css("display", "none");
  $("#cmb_user_type").removeClass("is-invalid");
  $("#invalid-passAdmin").css("display", "none");
  $("#txtPassAdmin").removeClass("is-invalid");
  $("#invalid-employer_cash").css("display", "none");
  $("#cmbEmpleado").removeClass("is-invalid");
});

document.getElementById("cash-payment").addEventListener("click", () => {
  document.getElementById("txtPaymentType").value = 1;
});

document.getElementById("credit-payment").addEventListener("click", () => {
  document.getElementById("txtPaymentType").value = 2;
});

document.getElementById("bank-transfer").addEventListener("click", () => {
  document.getElementById("txtPaymentType").value = 3;
});

document.getElementById("btnSaveTicket").addEventListener("click",save_allDataTicket);

document.getElementById("btnSaveDataFiscal").addEventListener("click",saveTicketProductDataFiscal);

document.getElementById("btn_close_cash_register").addEventListener("click",() => {
  showModalCashClosing();
});

document.getElementById("txtApprovedTransfer").addEventListener("keypress",() => {
  if($("#invalid-approved_transfer").is(":visible")){
    $("#invalid-approved_transfer").css("display", "none");
    $("#txtApprovedTransfer").removeClass("is-invalid");
  }
});

document.getElementById("txtApprovedCredit").addEventListener("keypress",() => {
  if($("#invalid-approved_credit").is(":visible")){
    $("#invalid-approved_credit").css("display", "none");
    $("#txtApprovedCredit").removeClass("is-invalid");
  }
});

$(document).on("focusout",".decimal",function(){
  var num = $(this).val();
  $(this).val(Math.floor(num*100)/100);
});

document.getElementById("btnSaveMovementCashRegister").addEventListener("click", () => 
{
  tipo_movimiento = cmbMovementType.selected();
  monto = document.getElementById("txtMovementAmount").value;
  comentario = document.getElementById("txaComments").value;
  caja_id = document.getElementById("txtCashRegisterId").value;
  saldo_actual = document.getElementById("txtCurrentBalanceHide").value;

  
  if($("#form-data-regsiter-movements")[0].checkValidity())
  {
    bad_MovementType = 
    $("#invalid-MovementType").css("display") === "block" ? false : true;
    bad_MovementAmount = 
    $("#invalid-MovementAmount").css("display") === "block" ? false : true;

    if(bad_MovementType && bad_MovementAmount)
    {
      
      switch(parseInt(tipo_movimiento)){
        case 1:
          if(parseFloat(saldo_actual) >= parseFloat(monto))
          {
            saveMovemementAccount();
          } else {
            Lobibox.notify("error", {
              size: "mini",
              rounded: true,
              delay: 3100,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../img/timdesk/warning_circle.svg",
              msg: "El monto es mayor al saldo actual.",
            });
          }
          break;
        case 2:
          saveMovemementAccount();
          break;
      }
      
    }
  } else {
    if (!$("#cmbMovementType").val()) {
      $("#invalid-MovementType").css("display", "block");
      $("#cmbMovementType").addClass("is-invalid");
    }
    if (!$("#txtMovementAmount").val()) {
      $("#invalid-MovementAmount").css("display", "block");
      $("#txtMovementAmount").addClass("is-invalid");
    }
  }
  
});

document.getElementById("btnSaveDataCashClosing").addEventListener("click", () => {
  saveAllDataCashRegisterCut()
});



document.getElementById("txtProfessionalLicense").addEventListener("keyup", () => {
   const a = document.getElementById("txtProfessionalLicense").classList;
   if(a.contains("is-invalid")){
    a.remove("is-invalid");
    $("#invalid-professionalLicense").css("display", "none");
   }
});

document.getElementById("btnFilterDataInvoice").addEventListener("click",()=>{
    $("#tblGeneralInvoice").DataTable().destroy();
    var tblGeneralInvoice = loadGeneralInvoice();
    var initial_date = document.getElementById("txtInitialDate");
    var final_date = document.getElementById("txtFinalDate");
  
    if(initial_date.value && final_date.value){
      tblGeneralInvoice.clear();
      if(tblGeneralInvoice.data().count() > 0){

        tblGeneralInvoice.ajax.reload();
        
      } else {
        tblGeneralInvoice.ajax.url({
          method: "post",
          url: "php/funciones.php",
          data: function(d){
            d.clase = "get_data";
            d.funcion = "get_generalInvoice";
            d.value = initial_date.value;
            d.value1= final_date.value;
          }
        }).load();
      }

      $.ajax({
        method: "POST",
        url: "php/funciones.php",
        data: {
          clase: 'get_data',
          funcion: 'get_totalGeneralInvoice',
          value: initial_date.value,
          value1: final_date.value,
          
        },
        dataType: 'json',
        success: function(response){
          var r = response;
          console.log(r);

          document.getElementById("generalInvoice-subtotal-ticket").innerHTML = "$" + numeral(r[0].subtotal_neto).format('0,000,000,000.00');
          document.getElementById("generalInvoice-subtotal-ticket-hidden").value = r[0].subtotal_neto;
          
          document.getElementById("generalInvoice-total-price").innerHTML = "$" + numeral(r[0].total_neto).format('0,000,000,000.00');
          document.getElementById("generalInvoice-total-price-hidden").value = r[0].total_neto;
          
        },
        error: function(error){
          console.log(error);
        }
      });

        $.ajax({
        method: "POST",
        url: "php/funciones.php",
        data: {
          clase: 'get_data',
          funcion: 'get_taxGeneralInvoice',
          value: initial_date.value,
          value1: final_date.value,
          
        },
        dataType: 'json',
        success: function(response){
          //console.log(response);
          var tax = "";
          $.each(response, function(index,value){
            tax += value + "</br>";
          });
    
          document.getElementById("generalInvoice-taxes").innerHTML = tax;
         
        },
        error: function(error){
          console.log(error);
        }
      });

      
      

      
    } else {
  
    }
    
  });
  
// $(document).on("click", "#detalle_factura", function (e) {

//   var data = $(this).data("id");
//   var prefactura = $(this).data("prefactura");

//   var url = "../facturacion/detalle_factura.php";

//   $.redirect(url, {
//     idFactura: data,
//   },
//   "POST",
//   "_blank"
//   );
//   $('[data-toggle="tooltip"]').tooltip("hide");
// });

$(document).on("click", "#pdf_factura_i", (e) =>{
  console.log(e.target);
  $('#pdf_factura[data-toggle="tooltip"]').tooltip("hide");
});

$(document).on("click", "#xml_factura_i", () =>{
  $('[data-toggle="tooltip"]').tooltip("hide");
});

document.getElementById("btnSaveGeneralInvoice").addEventListener("click", ()=>{
  if($('#tblGeneralInvoice').DataTable().data().count() > 0){
    $("#modal_fiscal_data_general").modal("show");
    $("#modal_invoice_general").modal("hide");
    $("#txtInitialDate_hide").val($("#txtInitialDate").val())
    $("#txtFinalDate_hide").val($("#txtFinalDate").val())
    
  } else {
    Lobibox.notify("error", {
      size: "mini",
      rounded: true,
      delay: 3100,
      delayIndicator: false,
      position: "center top",
      icon: true,
      img: "../../img/timdesk/warning_circle.svg",
      msg: "No hay tickets en la tabla.",
    });
  }
  
});

document.getElementById("btnSaveDataFiscalGeneral").addEventListener("click",()=>{
  if($("#form-fiscal-data-general")[0].checkValidity())
  {
    bad_globalInvoiceCFDIUse = $("#invalid-globalInvoiceCFDIUse").css("display") === "block" ? false : true;
    bad_globalInvoicePaidType = $("#invalid-globalInvoicePaidType").css("display") === "block" ? false : true;
    bad_globalInvoicePaidMethod = $("#invalid-globalInvoicePaidMethod").css("display") === "block" ? false : true;
    bad_globalInvoiceCurrency = $("#invalid-globalInvoiceCurrency").css("display") === "block" ? false : true;
    bad_globalInvoicePeriodicity = $("#invalid-globalInvoicePeriodicity").css("display") === "block" ? false : true;
    bad_globalInvoiceMonth = $("#invalid-globalInvoiceMonth").css("display") === "block" ? false : true;
    bad_globalInvoiceYear = $("#invalid-globalInvoiceYear").css("display") === "block" ? false : true;
    
    if(bad_globalInvoiceCFDIUse && bad_globalInvoicePaidType && bad_globalInvoicePaidMethod && bad_globalInvoiceCurrency && bad_globalInvoicePeriodicity && bad_globalInvoiceMonth && bad_globalInvoiceYear)
    {
      var cfdiUse = document.getElementById('cmbCFDIUseGeneral').value;
      var paidMethod = document.getElementById('cmbPaidMethodGeneral').value;
      var paidType = document.getElementById('cmbPaidTypeGeneral').value;
      var currency = document.getElementById('cmbCurrencyGeneral').value;
      var txtInitialDate = document.getElementById('txtInitialDate_hide').value;
      var txtFinalDate = document.getElementById('txtFinalDate_hide').value;
      var periodicity = document.getElementById('cmbPeriodicity').value;
      var month = document.getElementById('cmbMonth').value;
      var year = document.getElementById('cmbYears').value;
      
      var json = 
      '{'+
        '"cfdiUse":"'+cfdiUse+'",'+
        '"paidMethod":"'+paidMethod+'",'+
        '"paidType":"'+paidType+'",'+
        '"currency":"'+currency+'",'+
        '"periodicity":"'+ periodicity +'",'+
        '"month":"'+ month +'",'+
        '"year":"'+ year +'"'+
      '}';
      txtInitialDate
      console.log(json,txtInitialDate,txtFinalDate);
      saveAllDataGeneralInvoice(json,txtInitialDate,txtFinalDate);
    }
  } else {
    if(!$('#cmbCFDIUseGeneral').val()){
      $("#invalid-globalInvoiceCFDIUse").css("display","block");
      $('#cmbCFDIUseGeneral').addClass("is-invalid");
    }
    if(!$('#cmbPaidTypeGeneral').val()){
      $("#invalid-globalInvoicePaidType").css("display","block");
      $('#cmbPaidTypeGeneral').addClass("is-invalid");
    }
    if(!$('#cmbPaidMethodGeneral').val()){
      $("#invalid-globalInvoicePaidMethod").css("display","block");
      $('#cmbPaidMethodGeneral').addClass("is-invalid");
    }
    if(!$('#cmbCurrencyGeneral').val()){
      $("#invalid-globalInvoiceCurrency").css("display","block");
      $('#cmbCurrencyGeneral').addClass("is-invalid");
    }
    if(!$('#cmbPeriodicity').val()){
      $("#invalid-globalInvoicePeriodicity").css("display","block");
      $('#cmbPeriodicity').addClass("is-invalid");
    }
    if(!$('#cmbMonth').val()){
      $("#invalid-globalInvoiceMonth").css("display","block");
      $('#cmbMonth').addClass("is-invalid");
    }
    if(!$('#cmbYears').val()){
      $("#invalid-globalInvoiceYear").css("display","block");
      $('#cmbYears').addClass("is-invalid");
    }
  }
});
document.getElementById('cmbCFDIUseGeneral').addEventListener("change",(e)=>{
  const target = e.target;
  console.log(target.id);
  if(target.classList.contains('is-invalid')){
    target.classList.remove('is-invalid');
    document.getElementById('invalid-globalInvoiceCFDIUse').style.display = "none";
  }
})
document.getElementById('cmbPaidTypeGeneral').addEventListener("change",(e)=>{
  const target = e.target;
  if(target.classList.contains('is-invalid')){
    target.classList.remove('is-invalid');
    document.getElementById('invalid-globalInvoicePaidType').style.display = "none";
  }
})
document.getElementById('cmbPaidMethodGeneral').addEventListener("change",(e)=>{
  const target = e.target;
  if(target.classList.contains('is-invalid')){
    target.classList.remove('is-invalid');
    document.getElementById('invalid-globalInvoicePaidMethod').style.display = "none";
  }
})
document.getElementById('cmbCurrencyGeneral').addEventListener("change",(e)=>{
  const target = e.target;
  if(target.classList.contains('is-invalid')){
    target.classList.remove('is-invalid');
    document.getElementById('invalid-globalInvoiceCurrency').style.display = "none";
  }
})
document.getElementById('cmbPeriodicity').addEventListener("change",(e)=>{
  const target = e.target;
  if(target.classList.contains('is-invalid')){
    target.classList.remove('is-invalid');
    document.getElementById('invalid-globalInvoicePeriodicity').style.display = "none";
  }
})
document.getElementById('cmbMonth').addEventListener("change",(e)=>{
  const target = e.target;
  if(target.classList.contains('is-invalid')){
    target.classList.remove('is-invalid');
    document.getElementById('invalid-globalInvoiceMonth').style.display = "none";
  }
})
document.getElementById('cmbYears').addEventListener("change",(e)=>{
  const target = e.target;
  if(target.classList.contains('is-invalid')){
    target.classList.remove('is-invalid');
    document.getElementById('invalid-globalInvoiceYear').style.display = "none";
  }
})

function get_productsFormatTable(value,value1,value2,value3,value4)
{
  
  $.ajax({
    method: "POST",
    url: "php/funciones.php",
    data: {
      clase: 'get_data',
      funcion: 'get_productsFormatTable',
      value: value,
      value1: value1,
      value2: value2,
      value3: value3,
      value4: value4
    },
    dataType: 'json',
    success: function(respuesta){
      
      getSubtotalsTableTemp(value3);
    },
    error: function(error){
      console.log(error);
    }
  });
}

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

function loadCombo(funcion,input,data,value,texto){
  var text = texto !== null && texto !== "" ? texto : "Seleccione una opcion...";
  var html = "<option value='' disabled selected>" + text + "</option>";
  $.ajax({
    url: "php/funciones.php",
    method:"POST",
    data: {
      clase: "get_data",
      funcion: "get_"+funcion,
      value: value
    },
    datatype: "json",
    success: function(respuesta){
      var res = JSON.parse(respuesta);
      
      if(res.length > 0){
        $.each(res,function(i){
          if(parseInt(res[i].id) === parseInt(data)){
            html += "<option value='"+res[i].id+"' selected>"+res[i].texto+"</option>";
          } else {
            
            html += "<option value='"+res[i].id+"'>"+res[i].texto+"</option>";
          }
        });
      } else {
        html += "<option value='0'>No hay registros.</option>";
      }
      
      $(input).html(html);
    },
    error: function(error){
      console.log(error);
    }
  });
  
}

function getProductKeyEnter(input)
{
  value = document.getElementById(input).value;
  suc = document.getElementById("txtBranchOfficeId").value;
  var code = e.key;
  if(code === "Enter"){
    e.preventDefault();
  
    $.ajax({
      method: "POST",
      url: "php/funciones.php",
      data: {
        clase: 'get_data',
        funcion: 'get_product',
        value: value,
        value1: "",
        value2: suc
      },
      dataType: 'json',
      success: function(respuesta){
        r = respuesta;
        console.log("hola",r.length);
        if(r.id !== null){
          $("#txtInternalKey").val(r.clave);
          $("#txtDescription").val(r.nombre);
          $("#txtPrice").val(numeral(r.precio_venta_neto1).format('0,000,000,000.00'));
          $("#txtStock").val(r.existencia);
          if(r.imagen){
            $("#img-product").attr("src",r.imagen);
          }
        } else {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3100,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/warning_circle.svg",
            msg: "No se encontraron coincidencias.",
          });
        }
        
      },
      error: function(error){
        console.log(error);
      }

    });
    
  }
}

function resetNavbar(navBar)
{
  var nav = document.getElementById(navBar).getElementsByTagName("a");
  var index;

  for (let i = 0; i < nav.length; i++) {
    if(nav[i].classList.contains("active"))
    {
      index = i;
    }
  }

  if(index !== 0){
    nav[index].classList.remove("active");
    nav[0].classList.add("active");
  }
  
}

function resetNavbarContent(navContent)
{
  var nav_content = document.getElementById(navContent).getElementsByTagName("div");
  var index;

  for (let i = 0; i < nav_content.length; i++) {
    if(
      nav_content[i].classList.contains("active") && 
      nav_content[i].classList.contains("show"))
    {
      index = i;
    }
  }

  if(index !== 0){
    nav_content[index].classList.remove("active");
    nav_content[index].classList.remove("show");
    nav_content[0].classList.add("active");
    nav_content[0].classList.add("show");
  }
}

function addRowTableTicket(data){
    var caja_id = $("#txtCashRegisterId").val();
    var suc = $("#txtBranchOfficeId").val();
    
    if(data.length === undefined){
        existencia = data.existencia !== null ? data.existencia : 0;
        var edit_quantity = '<a href="#" data-toggle="tooltip" data-placement="top" title="Editar cantidad"> <img  id="edit_quantity" data-id="'+data.id+'" src="../../img/timdesk/edit.svg" width="20"> </a>';
        var edited = '<a href="#" data-toggle="tooltip" data-placement="top" title="Editar"> <img id="edit" data-id="'+data.id+'"  src="../../img/timdesk/edit.svg" width="20"> </a>';
        var deleted = '<a href="#" data-toggle="tooltip" data-placement="top" title="Eliminar"> <img id="delete" data-id="'+data.id+'" src="../../img/timdesk/delete.svg" width="20"> </a>';
        var functions = edited + deleted;

        if(tblTicket.data().count()) 
        {
      
            var indexes = tblTicket.rows().indexes().filter(function (value, index){
                return data.id === tblTicket.row(value).data()[0];
            });
            
            if(indexes[0] !== undefined){
                console.log(data);
                var row = tblTicket.row(indexes[0]);
                var data_Table = row.data();
                data_Table[1] = data_Table[1] + 1;
                data_Table[7] = "$" + numeral(parseInt(data_Table[1]) * data.precio_venta1).format('0,000,000,000.00');
                row.data( data_Table ).draw();
                cantidad = data_Table[1];
        
            } else {
                cantidad = 1;
                precio_unitario = data.precio_venta1 * cantidad;
                tblTicket.row.add(
                [
                    data.id,
                    cantidad,
                    edit_quantity,
                    data.nombre,
                    existencia,
                    0,
                    "$" + numeral(data.precio_venta1).format('0,000,000,000.00'),
                    "$" + numeral(precio_unitario).format('0,000,000,000.00'),
                    functions
                ]
                ).draw(false);
            }

        } else {

            if(data !== "" && data !== null){
                cantidad = 1;
                precio_unitario = data.precio_venta1 * cantidad;
                pre_subtot += precio_unitario;
                tblTicket.row.add(
                [
                    data.id, 
                    cantidad,
                    edit_quantity,
                    data.nombre,
                    existencia,
                    0,
                    "$" + numeral(data.precio_venta1).format('0,000,000,000.00'),
                    "$" + numeral(precio_unitario).format('0,000,000,000.00'),
                    functions
                ]
                ).draw(false);
            }
        }
        get_productsFormatTable(data.id,suc,cantidad,caja_id,data.clave);
    } else {
        data.forEach((i)=>{
            var edit_quantity = '<a href="#" data-toggle="tooltip" data-placement="top" title="Editar cantidad"> <img  id="edit_quantity" data-id="'+i.id+'" src="../../img/timdesk/edit.svg" width="20"> </a>';
            var edited = '<a href="#"style="padding-right: 1em;" data-toggle="tooltip" data-placement="top" title="Editar"><i id="edit" data-id="'+i.id+'" class="fas fa-pen"></i></a>';
            var deleted = '<a href="#"  data-toggle="tooltip" data-placement="top" title="Eliminar"><i data-id="'+i.id+'" id="delete" style="color:red" class="fas fa-trash-alt"></i></a>';
            var functions = edited + deleted;
            tblTicket.row.add(
                [
                i.id,
                i.cantidad,
                edit_quantity,
                i.nombre,
                i.existencia,
                0,
                "$" + numeral(i.precio_venta1).format('0,000,000,000.00'),
                "$" + numeral(i.subtotal).format('0,000,000,000.00'),
                functions
                ]
            ).draw(false);
        });
        getSubtotalsTableTemp(caja_id);
  }
  
  
}

function deleteAllProductsTableTemp(value){
  //var caja_id = $("#txtCashRegisterId").val();
  $.ajax({
    method: "POST",
    url: "php/funciones.php",
    data: {
      clase: 'delete_data',
      funcion: 'delete_allProductsTableTemp',
      value: value
    },
    dataType: 'json',
    success: function(response){
      //console.log("delete products:",response);
    },
    error: function(error){
      console.log(error);
    }
  });
}

function deleteProductTableTemp(value,value1)
{
  $.ajax({
    method: "POST",
    url: "php/funciones.php",
    data: {
      clase: 'delete_data',
      funcion: 'delete_productTableTemp',
      value: value,
      value1: value1
    },
    dataType: 'json',
    success: function(respuesta){

    },
    error: function(error){
      console.log(error);
    }
  });
}

function getSubtotalsTableTemp(value){
  $.ajax({
    method: "POST",
    url: "php/funciones.php",
    data: {
      clase: 'get_data',
      funcion: 'get_subtotalsTableTemp',
      value: value,
    },
    dataType: 'json',
    success: function(respuesta){
      a = respuesta['impuestos'];
      discount = respuesta.total * respuesta.descuento/100;
      total = respuesta.total - discount;
      document.getElementById("ticket-taxes-names").innerHTML = "";
      document.getElementById("ticket-taxes-prices").innerHTML = "";
      document.getElementById("discont-porcent").innerHTML = "";
      document.getElementById("subtotal-ticket").innerHTML = "$" + numeral("0").format('0,000,000,000.00');
      document.getElementById("ticket-discount").innerHTML = "$" + numeral("0").format('0,000,000,000.00');
      document.getElementById("ticket-total-price").innerHTML = "$" + numeral("0").format('0,000,000,000.00');
      tax_name = "";
      tax_balance = "";

      if(respuesta.descuento > 0){

        document.getElementById("discont-porcent").innerHTML = respuesta.descuento + "%";
        document.getElementById("ticket-discount").innerHTML = "$" +numeral(discount).format('0,000,000,000.00');
      }
      
      document.getElementById("subtotal-ticket").innerHTML = "$" + numeral(respuesta.subtotal).format('0,000,000,000.00');
      document.getElementById("subtotal-ticket-hidden").value = respuesta.subtotal;
      
      $.each(a, function(index,value){
        
        if(index.split("_").length === 2){
          tax_name += "<div style='padding-bottom:2px;'>" + (index.split("_")[0]) + ": " + index.split("_")[1] + "%</div>";
        } else if(index.split("_").length === 3){
          tax_name += "<div style='padding-bottom:2px;'>" + (index.split("_")[0]) + " " + index.split("_")[1] + ": " + index.split("_")[2] + "%</div>";
        } else if(index.split("_").length === 4){
          if(index.split("_")[1] !== "Monto"){
            tax_name += "<div style='padding-bottom:2px;'>" + (index.split("_")[0]) + " " + index.split("_")[1] + " " + index.split("_")[2] + ": " + index.split("_")[3] + "%</div>";
          } else {
            tax_name += "<div style='padding-bottom:2px;'>" + (index.split("_")[0]) + " " + index.split("_")[1] + " " + index.split("_")[2] + ": $" + numeral(index.split("_")[3]).format('0,000,000,000.00') + "</div>";
          }
          
        } else if(index.split("_").length === 5){
          if(index.split("_")[2] !== "Monto"){
            tax_name += "<div style='padding-bottom:2px;'>" + (index.split("_")[0]) + " " + index.split("_")[1] + " " + index.split("_")[2] + " " + index.split("_")[3] + ": " + index.split("_")[4] + "%</div>";
          } else {
            tax_name += "<div style='padding-bottom:2px;'>" + (index.split("_")[0]) + " " + index.split("_")[1] + " " + index.split("_")[2] + " " + index.split("_")[3] + ": $" + numeral(index.split("_")[4]).format('0,000,000,000.00') + "</div>";
          }
          
        }
        
        tax_balance += "<div style='padding-bottom:2px;'>$" + numeral(value.importe).format('0,000,000,000.00') + "</div>";
      });
      if(tax_balance !== ""){
        document.getElementById("ticket-taxes-names").innerHTML = tax_name;
        document.getElementById("ticket-taxes-prices").innerHTML = tax_balance;
        } else {
        document.getElementById("ticket-taxes-prices").innerHTML ="<div style='padding-bottom:2px;'>$" + numeral('0').format('0,000,000,000.00') + "</div>";
      }

      document.getElementById("ticket-total-price").innerHTML = "$" + numeral(total).format('0,000,000,000.00');
      document.getElementById("ticket-total-price-hidden").value = total;
    },
    error: function(error){
      console.log(error);
    }
  });
}

function updatePendingSale(){
  const value = document.getElementById("txtCashRegisterId").value;
  $.ajax({
    method: "POST",
    url: "php/funciones.php",
    data: {
      clase: 'save_data',
      funcion: 'save_productsPedding',
      value: value,
    },
    dataType: 'json',
    success: function(response){
      if(response){
        Lobibox.notify("success", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/checkmark.svg",
          msg: "Se guardaron los productos en la sección pendientes",
          sound:false,
        });
      }

      tblTicket.clear().draw();

      document.getElementById("subtotal-ticket").innerHTML = "$0.00";
      deleteChild("#ticket-taxes-names");
      deleteChild("#ticket-taxes-prices");
      document.getElementById("ticket-total-price").innerHTML = "$0.00";
      
    },
    error: function(error){
      console.log(error);
    }
  });
}

function deleteChild(input)
{
  var e = document.querySelector(input);

  var child = e.lastElementChild;

  while(child){
    e.removeChild(child);
    child = e.lastElementChild;
  }

}

function detect_visibility(input) 
{
  var is_visible = false;
  var element = document.getElementById(input);

  var top_of_element = element.offsetTop;
  var bottom_of_element = element.offsetTop + element.offsetHeight + element.style.marginTop;
  var bottom_of_screen = window.scrollY + window.innerHeight;
  var top_of_screen = window.scrollY;

  if ((bottom_of_screen > top_of_element) && (top_of_screen < bottom_of_element)) {
    is_visible = true
  }

  return is_visible;

}

function checkCashRegister(){
  $("#loader").addClass("loader");
  $.ajax({
    url: "php/funciones.php",
    method:"POST",
    data: {
      clase: "get_data",
      funcion: "get_countCashRegisterAccounts"
    },
    datatype: "json",
    success: function(respuesta){
      r = parseInt(respuesta);
      if(r === 0){
        
        $("#add_cash_register_question").modal({
          show: true,
          backdrop: 'static',
          keyboard: false
        });
        $(".loader").fadeOut("slow");
        $("#loader").removeClass("loader");
    //   } else if(r === 1){
        
    //     $.ajax({
    //       url: "php/funciones.php",
    //       method:"POST",
    //       data: {
    //         clase: "get_data",
    //         funcion: "get_cash_register"
    //       },
    //       datatype: "json",
    //       success: function(respuesta){
    //         r = JSON.parse(respuesta)
    //         document.getElementById("labelCaja").innerHTML = r[0].nombre;
    //         document.getElementById("labelSucursal").innerHTML = r[0].sucursal;
    //         document.getElementById("txtCashRegisterId").value = r[0].caja_id
    //         document.getElementById("txtBranchOfficeId").value =  r[0].sucursal_id;

    //         document.querySelector("#idBoxSuc").classList.remove("no-visible");
    //         document.querySelector("#idBoxSuc").classList.add("yes-visible");

    //         document.querySelector("#itemsBoxSuc").classList.remove("no-visible");
    //         document.querySelector("#itemsBoxSuc").classList.add("yes-visible");
    //         deleteAllProductsTableTemp(r[0].caja_id);

    //         console.log(r[0].nombre_impresora);
    //         document.getElementById("txtPrinterNameUpdate").value = r[0].nombre_impresora;

    //         if(r[0].activar_inventario !== "Sin inventario"){
    //           document.querySelector("#txtActiveInventory").classList.add("left-dot","green-dot");
    //           document.getElementById("txtActiveInventory").innerHTML = r[0].activar_inventario;
    //         } else {
    //           document.querySelector("#txtActiveInventory").classList.add("left-dot","yellow-dot");
    //           document.getElementById("txtActiveInventory").innerHTML = r[0].activar_inventario;
    //         }
    //         $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
    //         tblProductsFinder = loadProductsFinder();
    //         tblProductsFinderAll = loadProductsFinderAll();
    //         $(".loader").fadeOut("slow");
    //         $("#loader").removeClass("loader");
    //       },
    //       error: function(error){
    //         console.log(error);
    //       }
          
    //     });

        
      } else {
        
        $("#opening_cash_register").modal({
          show: true,
          backdrop: 'static',
          keyboard: false
        });
        $(".loader").fadeOut("slow");
        $("#loader").removeClass("loader");

        
      }
      
    },
    error: function(error){
      console.log(error);
    }
  });
}

document.addEventListener('keyup', (e) =>{

  if(e.ctrlKey && e.key === 'F1'){
    $("#create_product").modal("show");

    $("#add_cash_register_question").modal("hide");
    $("#add_cash_register").modal("hide");
    $("#add_tax_modal").modal("hide");
    $("#cancel_add_cash_register").modal("hide");
    $("#add_cash_closing").modal("hide");
    $("#add_cash_register_movements").modal("hide");
    // $("#create_product").modal("hide");
    $("#opening_cash_register").modal("hide");
    $("#modal_pedding_sale").modal("hide");
    $("#product_checker").modal("hide");
    $("#product_finder").modal("hide");
    $("#modal_product_sales").modal("hide");
    $("#add_producto_service_key").modal("hide");
    $("#add_unit_product_key").modal("hide");
    $("#modal_products_found_seeker_checker").modal("hide");
    $("#modal_products_found_seeker").modal("hide");
    $("#redirect_dashboard").modal("hide");
    $("#modal_update_product_ticket").modal("hide");
    $("#modal_update_product").modal("hide");
    $("#update_quantity").modal("hide");
    $("#update_tax_modal").modal("hide");
  }

  if(e.ctrlKey && e.key === 'F2'){
    $("#product_finder").modal("show");

    $("#add_cash_register_question").modal("hide");
    $("#add_cash_register").modal("hide");
    $("#add_tax_modal").modal("hide");
    $("#cancel_add_cash_register").modal("hide");
    $("#add_cash_closing").modal("hide");
    $("#add_cash_register_movements").modal("hide");
    $("#create_product").modal("hide");
    $("#opening_cash_register").modal("hide");
    $("#modal_pedding_sale").modal("hide");
    $("#product_checker").modal("hide");
    // $("#product_finder").modal("hide");
    $("#modal_product_sales").modal("hide");
    $("#add_producto_service_key").modal("hide");
    $("#add_unit_product_key").modal("hide");
    $("#modal_products_found_seeker_checker").modal("hide");
    $("#modal_products_found_seeker").modal("hide");
    $("#redirect_dashboard").modal("hide");
    $("#modal_update_product_ticket").modal("hide");
    $("#modal_update_product").modal("hide");
    $("#update_quantity").modal("hide");
    $("#update_tax_modal").modal("hide");
  }

  if(e.ctrlKey && e.key === 'F3'){
    $("#product_checker").modal("show");

    $("#add_cash_register_question").modal("hide");
    $("#add_cash_register").modal("hide");
    $("#add_tax_modal").modal("hide");
    $("#cancel_add_cash_register").modal("hide");
    $("#add_cash_closing").modal("hide");
    $("#add_cash_register_movements").modal("hide");
    $("#create_product").modal("hide");
    $("#opening_cash_register").modal("hide");
    $("#modal_pedding_sale").modal("hide");
    // $("#product_checker").modal("hide");
    $("#product_finder").modal("hide");
    $("#modal_product_sales").modal("hide");
    $("#add_producto_service_key").modal("hide");
    $("#add_unit_product_key").modal("hide");
    $("#modal_products_found_seeker_checker").modal("hide");
    $("#modal_products_found_seeker").modal("hide");
    $("#redirect_dashboard").modal("hide");
    $("#modal_update_product_ticket").modal("hide");
    $("#modal_update_product").modal("hide");
    $("#update_quantity").modal("hide");
    $("#update_tax_modal").modal("hide");
  }

  if(e.ctrlKey && e.key === 'F5'){
    window.open("../clientes/catalogos/clientes/agregar_cliente.php","_blank")
  }

  if(e.ctrlKey && e.key === 'F6'){
    if(tblTicket.rows().count())
        {
          updatePendingSale();
        } else {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/warning_circle.svg",
            msg: "No hay productos en la tabla",
          });
        }
  }

  if(e.ctrlKey && e.key === 'F7'){
    $("#modal_pedding_sale").modal("show");

    $("#add_cash_register_question").modal("hide");
    // $("#add_cash_register").modal("hide");
    $("#add_tax_modal").modal("hide");
    $("#cancel_add_cash_register").modal("hide");
    $("#add_cash_closing").modal("hide");
    $("#add_cash_register_movements").modal("hide");
    $("#create_product").modal("hide");
    $("#opening_cash_register").modal("hide");
    // $("#modal_pedding_sale").modal("hide");
    $("#product_checker").modal("hide");
    $("#product_finder").modal("hide");
    $("#modal_product_sales").modal("hide");
    $("#add_producto_service_key").modal("hide");
    $("#add_unit_product_key").modal("hide");
    $("#modal_products_found_seeker_checker").modal("hide");
    $("#modal_products_found_seeker").modal("hide");
    $("#redirect_dashboard").modal("hide");
    $("#modal_update_product_ticket").modal("hide");
    $("#modal_update_product").modal("hide");
    $("#update_quantity").modal("hide");
    $("#update_tax_modal").modal("hide");
  }

  
    if(e.ctrlKey && e.key === 'Enter'){
        get_ifProductoPrescription();
    }
  
  

  if(e.ctrlKey && e.altKey && e.key === '1'){
    $("#add_cash_register").modal("show");
    $("#add_cash_register_question").modal("hide");
    // $("#add_cash_register").modal("hide");
    $("#add_tax_modal").modal("hide");
    $("#cancel_add_cash_register").modal("hide");
    $("#add_cash_closing").modal("hide");
    $("#add_cash_register_movements").modal("hide");
    $("#create_product").modal("hide");
    $("#opening_cash_register").modal("hide");
    $("#modal_pedding_sale").modal("hide");
    $("#product_checker").modal("hide");
    $("#product_finder").modal("hide");
    $("#modal_product_sales").modal("hide");
    $("#add_producto_service_key").modal("hide");
    $("#add_unit_product_key").modal("hide");
    $("#modal_products_found_seeker_checker").modal("hide");
    $("#modal_products_found_seeker").modal("hide");
    $("#redirect_dashboard").modal("hide");
    $("#modal_update_product_ticket").modal("hide");
    $("#modal_update_product").modal("hide");
    $("#update_quantity").modal("hide");
    $("#update_tax_modal").modal("hide");
  }

  if(e.ctrlKey && e.altKey && e.key === '2'){
    $("#opening_cash_register").modal("show");

    $("#add_cash_register_question").modal("hide");
    $("#add_cash_register").modal("hide");
    $("#add_tax_modal").modal("hide");
    $("#cancel_add_cash_register").modal("hide");
    $("#add_cash_closing").modal("hide");
    $("#add_cash_register_movements").modal("hide");
    $("#create_product").modal("hide");
    // $("#opening_cash_register").modal("hide");
    $("#modal_pedding_sale").modal("hide");
    $("#product_checker").modal("hide");
    $("#product_finder").modal("hide");
    $("#modal_product_sales").modal("hide");
    $("#add_producto_service_key").modal("hide");
    $("#add_unit_product_key").modal("hide");
    $("#modal_products_found_seeker_checker").modal("hide");
    $("#modal_products_found_seeker").modal("hide");
    $("#redirect_dashboard").modal("hide");
    $("#modal_update_product_ticket").modal("hide");
    $("#modal_update_product").modal("hide");
    $("#update_quantity").modal("hide");
    $("#update_tax_modal").modal("hide");

  }

  if(e.ctrlKey && e.altKey && e.key === '3'){
    $("#add_cash_register_movements").modal("show")

    $("#add_cash_register_question").modal("hide");
    $("#add_cash_register").modal("hide");
    $("#add_tax_modal").modal("hide");
    $("#cancel_add_cash_register").modal("hide");
    $("#add_cash_closing").modal("hide");
    //$("#add_cash_register_movements").modal("hide");
    $("#create_product").modal("hide");
    $("#opening_cash_register").modal("hide");
    $("#modal_pedding_sale").modal("hide");
    $("#product_checker").modal("hide");
    $("#product_finder").modal("hide");
    $("#modal_product_sales").modal("hide");
    $("#add_producto_service_key").modal("hide");
    $("#add_unit_product_key").modal("hide");
    $("#modal_products_found_seeker_checker").modal("hide");
    $("#modal_products_found_seeker").modal("hide");
    $("#redirect_dashboard").modal("hide");
    $("#modal_update_product_ticket").modal("hide");
    $("#modal_update_product").modal("hide");
    $("#update_quantity").modal("hide");
    $("#update_tax_modal").modal("hide");
  }

  if(e.ctrlKey && e.altKey && e.key === '4'){
    showModalCashClosing();
  }

  if( $("#add_cash_closing").is(":visible")){
    if(e.key === 'Enter'){
      saveAllDataCashRegisterCut();
    }
  }

  if($("#modal_product_sales").is(":visible")){
    if(e.key === 'Enter'){
      //document.getElementById("txtMontoRecibido").focus();
      save_allDataTicket();
    }
  }

  if($("#update_quantity").is(":visible")){
    var val = parseInt($("#quantity").text());
    if(e.key === 'ArrowUp'){
      $("#quantity").text(val + 1);
    }
    if(e.key === 'ArrowDown'){
      if(val !== 1){
        $("#quantity").text(val - 1);
      }
    }
    if(e.key === "Enter"){
      updateQuantityProduct();
      $("#update_quantity").modal("hide");
    }
  }

});

function showModalSellMerchandise()
{
  if($("#form-general-data-ticket")[0].checkValidity())
  {
    var bad_ticketClient = $("#invalid-ticketClient").css("display") === "block" ? false : true;

    if(bad_ticketClient)
    {
      if(tblTicket.rows().count() > 0){
        
        $("#modal_product_sales").modal("show");
        
        importe_total_aux = document.getElementById("ticket-total-price").innerHTML;
        importe_total = importe_total_aux.split("$");
        importe_total_aux1 = importe_total[1].split(",");
        importe_total_tss = "";
        for (let i = 0; i < importe_total_aux1.length; i++) {
            importe_total_tss += importe_total_aux1[i];
            
        }
        document.getElementById("txtImporteTotalVenta").value = parseFloat(importe_total_tss);
        document.getElementById("txtImporteTotalVentaH4").innerHTML = numeral(importe_total[1]).format('0,000,000,000.00');

        $("#add_cash_register_question").modal("hide");
        // $("#add_cash_register").modal("hide");
        $("#add_tax_modal").modal("hide");
        $("#cancel_add_cash_register").modal("hide");
        $("#add_cash_closing").modal("hide");
        $("#add_cash_register_movements").modal("hide");
        $("#create_product").modal("hide");
        $("#opening_cash_register").modal("hide");
        $("#modal_pedding_sale").modal("hide");
        $("#product_checker").modal("hide");
        $("#product_finder").modal("hide");
        // $("#modal_product_sales").modal("hide");
        $("#add_producto_service_key").modal("hide");
        $("#add_unit_product_key").modal("hide");
        $("#modal_products_found_seeker_checker").modal("hide");
        $("#modal_products_found_seeker").modal("hide");
        $("#redirect_dashboard").modal("hide");
        $("#modal_update_product_ticket").modal("hide");
        $("#modal_update_product").modal("hide");
        $("#update_quantity").modal("hide");
        $("#update_tax_modal").modal("hide");
      } else {
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3100,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/warning_circle.svg",
          msg: "No hay productos en la tabla.",
        });
      }
    }
  } else {
    if (!$("#cmbClient").val()) {
      $("#invalid-ticketClient").css("display", "block");
      $("#cmbClient").addClass("is-invalid");
    }
  }
}


function get_ifProductoPrescription()
{
    caja_id = document.getElementById("txtCashRegisterId").value;

    $.ajax({
        method: "POST",
        url: "php/funciones.php",
        data: {
            clase: 'get_data',
            funcion: 'check_priceZero',
            value: caja_id,
        },
        dataType: 'json',
        success: function(response){
            if(response === 0){
                
                $.ajax({
                    method: "POST",
                    url: "php/funciones.php",
                    data: {
                    clase: 'get_data',
                    funcion: 'get_ifProductoPrescription',
                    value: caja_id,
                    },
                    dataType: 'json',
                    success: function(response){
                    
                    if(response > 0){
                        document.getElementById("txtHasPrescription").value = true;
                        $("#modal_add_professional_license").modal({
                        show:true,
                        backdrop: 'static',
                        keyboard: false
                        });

                    } else {
                        document.getElementById("txtHasPrescription").value = false;
                        showModalSellMerchandise();
                    }
                    },
                        error: function(error){
                        console.log(error);
                    }
                });
            } else {
                Lobibox.notify("error", {
                    size: "mini",
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: "center top",
                    icon: true,
                    img: "../../img/timdesk/warning_circle.svg",
                    msg: "Hay productos con el precio unitario en cero. Favor de editar el precio para continuar.",
                  });
            }
        },
            error: function(error){
            console.log(error);
        }
    });
}

function loadUpdateProductoData(value,value1)
{
  $.ajax({
    method: "POST",
    url: "php/funciones.php",
    data: {
      clase: 'get_data',
      funcion: 'get_product',
      value: "",
      value1: value,
      value2: value1
    },
    dataType: 'json',
    success: function(respuesta){
      r = respuesta[0];
      
      if(parseInt(r.tipo_id) === 4){
        document.getElementById("chkUpdateProduct").checked = true;
        document.getElementById("chkUpdateService").checked = false;
      } else if(parseInt(r.tipo_id) === 5){
        document.getElementById("chkUpdateProduct").checked = false;
        document.getElementById("chkUpdateService").checked = true;
      }
      document.getElementById("txtUpdateClave").value = r.clave;
      document.getElementById("txtUpdateCodigoBarras").value = r.codigo_barras;
      document.getElementById("txtUpdateNombre").value = r.nombre;
      
      loadCombo('productCategories',"#cmbUpdateCategoria",r.categoria_id,"","");
      loadCombo('productTradeMark',"#cmbUpdateMarca",r.marca_id,"","");

      document.getElementById("txtUpdatePrecioCompra").value = Math.floor(r.precio_compra*100)/100;

      
      r.precio_compra_neto === 1 ? document.getElementById("chkUpdatePrecioCompraNeto").checked = true :document.getElementById("chkUpdatePrecioCompraNeto").checked = false;

      document.getElementById("txtUpdatePrecioCompraSinImpuestos").value = r.precio_compra_sin_impuesto;
      document.getElementById("txtUpdatePrecioCompraSinImpuestosValue").value = r.precio_compra_sin_impuesto;

      document.getElementById("txtUpdateUtilidad1").value = r.utilidad1;
      document.getElementById("txtUpdateUtilidad2").value = r.utilidad2;
      document.getElementById("txtUpdateUtilidad3").value = r.utilidad3;
      document.getElementById("txtUpdateUtilidad4").value = r.utilidad4;

      document.getElementById("txtUpdatePrecioVenta1").value = getPriceWithTax(r.precio_venta1);
      document.getElementById("txtUpdatePrecioVenta2").value = getPriceWithTax(r.precio_venta2);
      document.getElementById("txtUpdatePrecioVenta3").value = getPriceWithTax(r.precio_venta3);
      document.getElementById("txtUpdatePrecioVenta4").value = getPriceWithTax(r.precio_venta4);

      document.getElementById("txtUpdatePrecioVentaNeto1").value = getPriceWithTax(r.precio_venta_neto1);
      document.getElementById("txtUpdatePrecioVentaNeto2").value = getPriceWithTax(r.precio_venta_neto2);
      document.getElementById("txtUpdatePrecioVentaNeto3").value = getPriceWithTax(r.precio_venta_neto3);
      document.getElementById("txtUpdatePrecioVentaNeto4").value = getPriceWithTax(r.precio_venta_neto4);

      if(r.imagen !== null && r.imagen !== ""){
        document.getElementById("lbl-file-upload-image-update-product").src = r.imagen;
        
      }
      
      if(r.lote === 1){
        document.getElementById("chkUpdateLote").checked = true;
        document.getElementById("chkUpdateSerie").checked = false;
      }

      if(r.serie === 1){
        document.getElementById("chkUpdateLote").checked = false;
        document.getElementById("chkUpdateSerie").checked = true;
        document.getElementById("chkUpdateCaducidad").setAttribute('disabled','');
      }

      if(r.fecha_caducidad === 1){
        document.getElementById("chkUpdateCaducidad").checked = true;
      }

      if(r.receta === 1){
        document.getElementById("chkUpdateReceta").checked = true;
      }
      document.getElementById("txtUpdateStockMinimo").value = r.stock_minimo;
      document.getElementById("txtUpdateStockMaximo").value = r.stock_maxima;
      document.getElementById("txtUpdatePuntoReorden").value = r.punto_reorden;

      document.getElementById("txaUpdateDescriptionProduct").value = r.descripcion;

      document.getElementById("txtUpdateClaveSatId").value = r.clave_sat_id;
      document.getElementById("txtUpdateUnidadMedidaId").value = r.clave_sat_unidad_id;
      document.getElementById("txtUpdateClaveSat").value = r.clave_sat;
      document.getElementById("txtUpdateUnidadMedida").value = r.clave_sat_unidad;

      
    },
    error: function(error){
      console.log(error);
    }
  })
}

function getFormatDataTicket(){
  tipo_movimiento = 0;
  var tipo_documento = cmbDocument.selected();
  var cliente = cmbClient.selected();
  var monto_recibido = parseFloat(document.getElementById("txtMontoRecibido").value);
  var importeTotalVenta = parseFloat(document.getElementById("txtImporteTotalVenta").value);
  var subtotal = document.getElementById("subtotal-ticket-hidden").value;
  var total = document.getElementById("ticket-total-price-hidden").value;
  var caja_id = document.getElementById("txtCashRegisterId").value;
  var cash_payment = detect_visibility("cash-payment-data");
  var credit_payment = detect_visibility("credit-payment-data");
  var bank_transfer = detect_visibility("bank-transfer-details");
  var amount_received = document.getElementById("txtMontoRecibido").value;
  var approved_credit = document.getElementById("txtApprovedCredit").value;
  var approved_transfer = document.getElementById("txtApprovedTransfer").value;
  var tipo_pago = document.getElementById("txtPaymentType").value;
  var cajero = document.getElementById("txtEmployeId").value;
  var sucursal = document.getElementById("txtBranchOfficeId").value;

  if(parseInt(tipo_documento) !== 2) {document.getElementById("btnSaveTicket").setAttribute("disabled", true)};

  if(cash_payment){
    if(amount_received){
      referencia = "NULL";
    } else {
      $("#invalid-amount_received").css("display", "block");
      $("#txtMontoRecibido").addClass("is-invalid");
    }
  }

  if(credit_payment){
    if(approved_credit){
      referencia = approved_credit;
    } else {
      $("#invalid-approved_credit").css("display", "block");
      $("#txtApprovedCredit").addClass("is-invalid");
    }
  }

  if(bank_transfer){
    if(approved_transfer){
      referencia = approved_transfer;
    } else {
      $("#invalid-approved_transfer").css("display", "block");
      $("#txtApprovedTransfer").addClass("is-invalid");
    }
  }
  

  var json = 
  '{'+
    '"tipo_movimiento":"2",'+
    '"tipo_documento" : "' + tipo_documento + '",' +
    '"cliente_id" : "'+ cliente + '",' +
    '"referencia" : "' + referencia +  '",' +
    '"tipo_pago" : "' + tipo_pago + '",' +
    '"subtotal" : "' + subtotal +  '",' +
    '"total" : "' + total + '",' +
    '"caja_id" : "' + caja_id + '",' +
    '"estatus" : "1",' +
    '"comentario" : "NULL",'+
    '"cajero" : "' + cajero + '",'+
    '"sucursal" : "' + sucursal + '"'+
  '}';

  return json;
}

function getFormatFiscalData()
{
  var cfdiUse = document.getElementById('cmbCFDIUse').value;
  var paidMethod = document.getElementById('cmbPaidMethod').value;
  var paidType = document.getElementById('cmbPaidType').value;
  var currency = document.getElementById('cmbCurrency').value;

  var json = 
  '{'+
    '"cfdiUse":"'+cfdiUse+'",'+
    '"paidMethod":"'+paidMethod+'",'+
    '"paidType":"'+paidType+'",'+
    '"currency":"'+currency+'"'+
  '}';

  return json;
}

function save_allDataTicket(){
  var tipo_documento = cmbDocument.selected();
  var tipo_pago = document.getElementById("txtPaymentType").value;
  var monto_recibido = parseFloat(document.getElementById("txtMontoRecibido").value);
  var importeTotalVenta = parseFloat(document.getElementById("txtImporteTotalVenta").value);
  var amount_received = document.getElementById("txtMontoRecibido").value;
  var approved_credit = document.getElementById("txtApprovedCredit").value;
  
  var bank_transfer = detect_visibility("bank-transfer-details");
  var json = getFormatDataTicket();
  
  switch (parseInt(tipo_pago)) {
    case 1:
      if((monto_recibido >= importeTotalVenta && amount_received)){
        saveTicketProductData(tipo_documento,json);
      } else if((monto_recibido < importeTotalVenta)){
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/warning_circle.svg",
          msg: "El valor recibido es menor al valor total de la venta",
        });
      } else if(!amount_received){
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/warning_circle.svg",
          msg: "El campo monto recibido es obligatorio",
        });
      }
    case 2:
      if(approved_credit){
        saveTicketProductData(tipo_documento,json);
      }
      break;
    case 3:
      if(bank_transfer){
        saveTicketProductData(tipo_documento,json);
      }
      break;
  }

  // if(tipo_pago === "1"){
  //   if((monto_recibido >= importeTotalVenta)){
  //     saveTicketProductData(json);
  //   } else {
  //     Lobibox.notify("error", {
  //       size: "mini",
  //       rounded: true,
  //       delay: 3000,
  //       delayIndicator: false,
  //       position: "center top",
  //       icon: true,
  //       img: "../../img/timdesk/warning_circle.svg",
  //       msg: "El valor recibido es menor al valor total de la venta",
  //     });
  //   }
  // } else {

  // }
}

function saveTicketProductDataFiscal()
{
  json = getFormatDataTicket();
  json1 = getFormatFiscalData();
  saveTicketData(json,json1);
}

function saveTicketProductData(type_doc,json){
  //$("#loader1").addClass("loader");
  var json1 = "";
  if(parseInt(type_doc) !== 2){
    
    saveTicketData(json,json1);
    
    document.getElementById("txtMontoRecibido").value = "";
    document.getElementById("txtApprovedCredit").value = "";
    document.getElementById("txtApprovedTransfer").value = "";
    document.getElementById("txtMontoCambio").value = "";
  } else {
    $("#modal_fiscal_data").modal("show");
    $("#modal_product_sales").modal("hide");
  }
}

function saveTicketData(json,json1)
{
  $("#loader2").css("display","block");
  $("#loader2").addClass("loader");
  var sucursal = document.getElementById('txtBranchOfficeId').value;

  $.ajax({
    method: "POST",
    url: "php/funciones.php",
    data: {
      clase: 'save_data',
      funcion: 'save_ticketData',
      value: json,
      value1: json1,
      value2: sucursal
    },
    dataType: 'json',
    success: function(response){
      
      if(response.status){
        Lobibox.notify("success", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/checkmark.svg",
          msg: "Se realizó la venta con éxito",
          sound:false,
        });
        
        $("#modal_product_sales").modal('hide');
        tblTicket.clear().draw();

        if($("#modal_fiscal_data").is(":visible")){$("#modal_fiscal_data").modal("hide");}
        
        document.getElementById("subtotal-ticket").innerHTML = "$0.00";
        deleteChild("#ticket-taxes-names");
        deleteChild("#ticket-taxes-prices");
        document.getElementById("ticket-total-price").innerHTML = "$0.00";
        document.getElementById("btnSaveTicket").removeAttribute("disabled");
        $(".loader").fadeOut("slow");
        $("#loader2").removeClass("loader");
        var monto_recibido = document.getElementById("txtMontoRecibido").value;
        var monto_cambio = document.getElementById("txtMontoCambio").value;

        getPrintTicket(response.id,monto_recibido,monto_cambio,"","");

        if ($("#tblProductsFinder tbody tr").length === 0 ) {
          $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
          tblProductsFinder = loadProductsFinder();
          tblProductsFinderAll = loadProductsFinderAll();
        } else {
          $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();

          $("#tblProductsFinder").DataTable().ajax.reload();
          
          $("#tblProductsFinderAll").DataTable().ajax.reload();

        }
        cmbDocument.set(1);
        cmbClient.set("");
        document.getElementById('comboClient').style.display = "none";
      }
      
    },
    error: function(error){
      console.log(error);
    }
  });
}

function saveAllDataCashRegisterCut(){

  caja_id = document.getElementById("txtCashRegisterId").value;
  efectivo_contado = document.getElementById("txtCashCounted").value !== "" && document.getElementById("txtCashCounted").value !== null && document.getElementById("txtCashCounted").value !== 0 ? document.getElementById("txtCashCounted").value : 0.00;
  efectivo_calculado = document.getElementById("efectivo_calculado_hide").value !== "" && document.getElementById("efectivo_calculado_hide").value !== null && document.getElementById("efectivo_calculado_hide").value !== 0 ? document.getElementById("efectivo_calculado_hide").value : 0.00;
  //efectivo_diferencia = document.getElementById("efectivo_diferencia").innerHTML.split("$ ")[1];
  efectivo_diferencia = document.getElementById("efectivo_diferencia_hide").value !== "" && document.getElementById("efectivo_diferencia_hide").value !== null && document.getElementById("efectivo_diferencia_hide").value !== 0 ? document.getElementById("efectivo_diferencia_hide").value : 0.00;
  credito_contado = document.getElementById("txtCreditCounted").value !== "" && document.getElementById("txtCreditCounted").value !== null && document.getElementById("txtCreditCounted").value !== 0 ? document.getElementById("txtCreditCounted").value : 0.00;
  //credito_calculado = document.getElementById("credito_calculado").innerHTML.split("$ ")[1];
  credito_calculado = document.getElementById("credito_calculado_hide").value !== "" && document.getElementById("credito_calculado_hide").value !== null && document.getElementById("credito_calculado_hide").value !== 0 ? document.getElementById("credito_calculado_hide").value : 0.00;
  //credito_diferencia = document.getElementById("credito_diferencia").innerHTML.split("$ ")[1];
  credito_diferencia = document.getElementById("credito_diferencia_hide").value !== "" && document.getElementById("credito_diferencia_hide").value !== null && document.getElementById("credito_diferencia_hide").value !== 0 ? document.getElementById("credito_diferencia_hide").value :0.00;
  transferencia_contado = document.getElementById("txtTransferCounted").value !== "" && document.getElementById("txtTransferCounted").value !== null && document.getElementById("txtTransferCounted").value !== 0 ? document.getElementById("txtTransferCounted").value : 0.00;
  //transferencia_calculado = document.getElementById("transferencia_calculado").innerHTML.split("$ ")[1];
  transferencia_calculado = document.getElementById("transferencia_calculado_hide").value !== "" && document.getElementById("transferencia_calculado_hide").value !== null && document.getElementById("transferencia_calculado_hide").value !== 0 ? document.getElementById("transferencia_calculado_hide").value : 0.00;
  //transferencia_diferencia = document.getElementById("transferencia_diferencia").innerHTML.split("$ ")[1];
  transferencia_diferencia = document.getElementById("transferencia_diferencia_hide").value !== "" && document.getElementById("transferencia_diferencia_hide").value !== null && document.getElementById("transferencia_diferencia_hide").value !== 0 ? document.getElementById("transferencia_diferencia_hide").value : 0.00;
  efectivo_retirado = document.getElementById("txtCashWithdrawal").value !== "" && document.getElementById("txtCashWithdrawal").value !== null && document.getElementById("txtCashWithdrawal").value !== 0 ? document.getElementById("txtCashWithdrawal").value : 0.00;
  credito_retirado = document.getElementById("txtCreditWithdrawal").value !== "" && document.getElementById("txtCreditWithdrawal").value !== null && document.getElementById("txtCreditWithdrawal").value !== 0 ? document.getElementById("txtCreditWithdrawal").value : 0.00;
  transferencia_retirada = document.getElementById("txtTransferWithdrawal").value !== document.getElementById("txtTransferWithdrawal").value !== null && document.getElementById("txtTransferWithdrawal").value !== 0 ? document.getElementById("txtTransferWithdrawal").value : 0.00;
  //total_contado = document.getElementById("total_contado").innerHTML.split("$ ")[1];
  total_contado = document.getElementById("total_contado_hide").value !== "" && document.getElementById("total_contado_hide").value !== null !== document.getElementById("total_contado_hide").value !== 0 ? document.getElementById("total_contado_hide").value : 0.00;
  //total_calculado = document.getElementById("total_calculado").innerHTML.split("$ ")[1];
  total_calculado = document.getElementById("total_calculado_hide").value !== "" && document.getElementById("total_calculado_hide").value !== null && document.getElementById("total_calculado_hide").value !== 0 ? document.getElementById("total_calculado_hide").value : 0.00;
  //total_diferencia = document.getElementById("total_diferencia").innerHTML.split("$ ")[1];
  total_diferencia = document.getElementById("total_diferencia_hide").value !== "" && document.getElementById("total_diferencia_hide").value !== null && document.getElementById("total_diferencia_hide").value !== 0 ? document.getElementById("total_diferencia_hide").value : 0.00;
  //total_retirado = document.getElementById("txtTotalWithdrawal").value.split("$ ")[1];
  total_retirado = document.getElementById("txtTotalWithdrawal_hide").value !== "" && document.getElementById("txtTotalWithdrawal_hide").value !== null && document.getElementById("txtTotalWithdrawal_hide").value !== 0 ? document.getElementById("txtTotalWithdrawal_hide").value : 0.00;

  json = '{'+
            '"caja_id":"'+caja_id+'",'+
            '"efectivo_contado":"'+efectivo_contado+'",' +
            '"efectivo_calculado":"'+efectivo_calculado+'",' +
            '"efectivo_diferencia":"'+efectivo_diferencia+'",' +
            '"credito_contado":"'+credito_contado+'",' +
            '"credito_calculado":"'+credito_calculado+'",' +
            '"credito_diferencia":"'+credito_diferencia+'",' +
            '"transferencia_contado":"'+transferencia_contado+'",' +
            '"transferencia_calculado":"'+transferencia_calculado+'",' +
            '"transferencia_diferencia":"'+transferencia_diferencia+'",' +
            '"efectivo_retirado":"'+efectivo_retirado+'",'+
            '"credito_retirado":"'+credito_retirado+'",'+
            '"transferencia_retirada":"'+transferencia_retirada+'",'+
            '"total_contado":"'+total_contado+'",'+
            '"total_calculado":"'+total_calculado+'",'+
            '"total_diferencia":"'+total_diferencia+'",'+
            '"total_retirado":"'+total_retirado+'"'+ 
          '}';
  
  const swalWithBootstrapButtons = Swal.mixin({
    customClass: {
      actions: "d-flex justify-content-around",
      confirmButton: "btn-custom btn-custom--border-blue",
      cancelButton: "btn-custom btn-custom--blue",
    },
    buttonsStyling: false,
  });
  
  $.ajax({
    method: "POST",
    url: "php/funciones.php",
    data: {
      clase: 'get_data',
      funcion: 'get_countCashRegisterAccountsStatus',
      value: json
    },
    dataType: 'json',
    success: function(response){
      res = JSON.parse(response);
      if(res > 0){
        swalWithBootstrapButtons.fire(
          {
            title: "",
            text: "¿Desea realizar el corte de caja?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: '<span class="verticalCenter">Aceptar</span>',
            cancelButtonText: '<span class="verticalCenter">Cancelar</span>',
            reverseButtons: false,
          }
        ).then((result) => {
          if (result.isConfirmed) {

            $.ajax({
              method: "POST",
              url: "php/funciones.php",
              data: {
                clase: 'save_data',
                funcion: 'save_allDataCashRegisterCut',
                value: json
              },
              dataType: 'json',
              success: function(response){
                r = JSON.parse(response);
                $("#add_cash_closing").modal("hide");
                if(r)
                {
                  Lobibox.notify("success", {
                    size: "mini",
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: "center top",
                    icon: true,
                    img: "../../img/timdesk/checkmark.svg",
                    msg: "Se realizó el corte de caja con éxito",
                    sound:false,
                  });
                }
              },
      
              error: function(error){
                console.log(error);
              }
            });
      
          } else if (result.dismiss === Swal.DismissReason.cancel){
            $('#txtSearchProduct').trigger('focus');
          } 
        });
      } else {
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/warning_circle.svg",
          msg: "No hay ventas para realizar el corte de caja",
        });
      }
    },

    error: function(error){
      console.log(error);
    }
  });
}
if($("#li_link_informes").is(":visible")){
    document.getElementById("li_link_informes").addEventListener("click", () => {
    console.log(document.getElementById("labelCaja").innerText);
    $.redirect(
        'cortes_caja.php', 
        {
        'caja_id' : document.getElementById("txtCashRegisterId").value,
        'caja_name':document.getElementById("labelCaja").innerText ,
        'sucursal':document.getElementById("labelSucursal").innerText
        },
        "POST",
        "_blank"
    );
    });
} else {
    console.log("ontoy");
}

document.getElementById("txtCashCounted").addEventListener("keyup", () => {
  getFormatQuantitiesCount("efectivo_calculado_hide","txtCashCounted","efectivo_diferencia","total_contado");
});

// document.getElementById("txtCashCounted").addEventListener("focusout", () => {
//   document.getElementById("txtCashCounted").value = numeral(document.getElementById("txtCashCounted").value).format('0,000,000,000.00');
// });

document.getElementById("txtCreditCounted").addEventListener("keyup", () => {
  getFormatQuantitiesCount("credito_calculado_hide","txtCreditCounted","credito_diferencia","total_contado");
});

// document.getElementById("txtCreditCounted").addEventListener("focusout", () => {
//   document.getElementById("txtCreditCounted").value = numeral(document.getElementById("txtCreditCounted").value).format('0,000,000,000.00');
// });

document.getElementById("txtTransferCounted").addEventListener("keyup", () => {
  getFormatQuantitiesCount("transferencia_calculado_hide","txtTransferCounted","transferencia_diferencia","total_contado");
});

// document.getElementById("txtTransferCounted").addEventListener("focusout", () => {
//   document.getElementById("txtTransferCounted").value = numeral(document.getElementById("txtTransferCounted").value).format('0,000,000,000.00');
// });

document.getElementById("txtCashWithdrawal").addEventListener("keyup", () =>{
  document.getElementById("txtTotalWithdrawal").value = "$ " + numeral(calculateTotalThreeValues("txtCashWithdrawal","txtCreditWithdrawal","txtTransferWithdrawal")).format('0,000,000,000.00');
  document.getElementById("txtTotalWithdrawal_hide").value = calculateTotalThreeValues("txtCashWithdrawal","txtCreditWithdrawal","txtTransferWithdrawal");
});

// document.getElementById("txtCashWithdrawal").addEventListener("focusout", () => {
//   document.getElementById("txtCashWithdrawal").value = numeral(document.getElementById("txtCashWithdrawal").value).format('0,000,000,000.00');
// });

document.getElementById("txtCreditWithdrawal").addEventListener("keyup", () =>{
  document.getElementById("txtTotalWithdrawal").value = "$ " + numeral(calculateTotalThreeValues("txtCashWithdrawal","txtCreditWithdrawal","txtTransferWithdrawal")).format('0,000,000,000.00');
});

// document.getElementById("txtCreditWithdrawal").addEventListener("focusout", () => {
//   document.getElementById("txtCreditWithdrawal").value = numeral(document.getElementById("txtCreditWithdrawal").value).format('0,000,000,000.00');
// });

document.getElementById("txtTransferWithdrawal").addEventListener("keyup", () =>{
  document.getElementById("txtTotalWithdrawal").value = "$ " + numeral(calculateTotalThreeValues("txtCashWithdrawal","txtCreditWithdrawal","txtTransferWithdrawal")).format('0,000,000,000.00');
});

// document.getElementById("txtTransferWithdrawal").addEventListener("focusout", () => {
//   document.getElementById("txtTransferWithdrawal").value = numeral(document.getElementById("txtTransferWithdrawal").value).format('0,000,000,000.00');
// });

document.getElementById("chkLoadAllProducts").addEventListener("click",(e)=>{
  var target = e.target;

  if(target.checked){
    
    document.getElementById("tblProductsFinderforBranchOffice").classList.add("no-visible");
    document.getElementById("tblProductsFinderforEnterprise").classList.remove("no-visible");
    $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
    document.getElementById('txtProductUpdateId').value = "";
  } else{
    
    document.getElementById("tblProductsFinderforEnterprise").classList.add("no-visible");
    document.getElementById("tblProductsFinderforBranchOffice").classList.remove("no-visible");
    $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
    document.getElementById('txtProductAllUpdateId').value = "";
  }
});

document.getElementById("btnConfigTicket").addEventListener("click",(e) => {
 
  if($("#form-config-ticket")[0].checkValidity()){
    var bad_printerName = $("#invalid-printerNameUpdate").css("display") === "block" ? false : true;

    if(bad_printerName)
    {
      var printerName = document.getElementById("txtPrinterNameUpdate").value;
      var cash_register_id = document.getElementById("txtCashRegisterId").value;

      json = '{"printerName":"' + printerName + '","cash_register_id":"' + cash_register_id + '"}';
      
      $.ajax({
        method: "POST",
        url: "php/funciones.php",
        data: {
          clase: 'update_data',
          funcion: 'update_printer',
          value: json
        },
        dataType: 'json',
        success: function(response){
          if(response)
          {
            $("#modal_config_tickets").modal("hide");
            Lobibox.notify("success", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../img/timdesk/checkmark.svg",
              msg: "Se actualizó el nombre de la impresora con éxito",
              sound:false,
            });
          } else {
            Lobibox.notify("error", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../img/timdesk/warning_circle.svg",
              msg: "Algo salio mal 😔",
            });
          }
        },
        error: function(error){
          console.log(error);
        }
      });
    } 
  } else {
    if (!$("#txtPrinterNameUpdate").val()) {
      $("#invalid-printerNameUpdate").css("display", "block");
      $("#txtPrinterNameUpdate").addClass("is-invalid");
    }
  }
});

// document.getElementById("txtPrinterName").addEventListener("keyup",(e) => {
//   const target = e.target;
  
//   if(target.classList.contains("is-invalid")){
//     document.getElementById("invalid-printerName").style.display = "none";
//     document.getElementById("txtPrinterName").classList.remove("is-invalid");
//   }
// });

document.getElementById("txtPrinterNameUpdate").addEventListener("keyup",(e) => {
  const target = e.target;
  
  if(target.classList.contains("is-invalid")){
    document.getElementById("invalid-printerNameUpdate").style.display = "none";
    document.getElementById("txtPrinterNameUpdate").classList.remove("is-invalid");
  }
});

function getFormatQuantitiesCount(input,input1,input2,input3){
    //getFormatQuantitiesCount("credito_calculado","txtCreditCounted","credito_diferencia","total_contado")
  var total_count = document.getElementById(input).value;
  aux = parseFloat(total_count);
  cash_count = document.getElementById(input1).value;
  difference = cash_count - aux;
  console.log(difference);

  if(difference >= 0){
    document.getElementById(input2).innerHTML = numeral(difference).format('0.00');
    document.getElementById(input2+"_hide").value = difference;
  } else {
    document.getElementById(input2).innerHTML = numeral(difference).format('0.00');
    document.getElementById(input2+"_hide").value = difference;
  }
  
  document.getElementById(input3).innerHTML = numeral(calculateTotalThreeValues("txtCashCounted","txtCreditCounted","txtTransferCounted")).format('0,000,000,000.00');
  console.log(input3+"_hide");
  document.getElementById(input3+"_hide").value = calculateTotalThreeValues("txtCashCounted","txtCreditCounted","txtTransferCounted");
  total_contado = parseFloat(document.getElementById("total_contado_hide").value);
  total_calculado = parseFloat(document.getElementById("total_calculado_hide").value);
  diferencia = total_contado - total_calculado;
  console.log(total_calculado);
  // if(diferencia >= 0){
    document.getElementById("total_diferencia").innerHTML = numeral(diferencia).format('0.00');
    document.getElementById("total_diferencia_hide").value = diferencia;
  // } else{
  //   document.getElementById("total_diferencia").innerHTML = numeral(diferencia).format('0.00');
  //   document.getElementById("total_diferencia_hide").value = diferencia;
  // }
  
}

function calculateTotalThreeValues(value,value1,value2){
  cash_count = parseFloat(document.getElementById(value).value);
  credit_count = parseFloat(document.getElementById(value1).value);
  tranfer_count = parseFloat(document.getElementById(value2).value);


  sum = 0;
  if(
    cash_count !== "" && cash_count !== undefined && cash_count !== "0.00" &&
    credit_count !== "" && credit_count !== undefined && credit_count !== "0.00" &&
    tranfer_count !== "" && tranfer_count !== undefined && tranfer_count !== "0.00"
  ){
    sum += cash_count + credit_count + tranfer_count;
  } else 
  if(
    cash_count !== "" && cash_count !== undefined && cash_count !== "0.00" &&
    credit_count !== "" && credit_count !== undefined && credit_count !== "0.00"
    
  ){
    sum += cash_count + credit_count;
  } else 
  if(
    cash_count !== "" && cash_count !== undefined && cash_count !== "0.00" &&
    tranfer_count !== "" && tranfer_count !== undefined && tranfer_count !== "0.00"
  )
  {
    sum += cash_count + tranfer_count;
  } else
  if(
    credit_count !== "" && credit_count !== undefined && credit_count !== "0.00" &&
    tranfer_count !== "" && tranfer_count !== undefined && tranfer_count !== "0.00"
  ) 
  {
    sum += credit_count + tranfer_count;
  } else
  if(
    cash_count !== "" && cash_count !== undefined && cash_count !== "0.00"
  )
  {
    sum += cash_count;
  } else 
  if(
    credit_count !== "" && credit_count !== undefined && credit_count !== "0.00"
  )
  {
    sum += credit_count
  } else 
  if(
    tranfer_count !== "" && tranfer_count !== undefined && tranfer_count !== "0.00"
  )
  {
    sum += tranfer_count;
  }
  
  return sum;
}

function resetHTMLCashClosing(){
  document.getElementById("total_contado").innerHTML = "$ 0.00";
  document.getElementById("efectivo_calculado").innerHTML = "$ 0.00";
  document.getElementById("efectivo_diferencia").innerHTML = "$ 0.00";
  document.getElementById("credito_calculado").innerHTML = "$ 0.00";
  document.getElementById("credito_diferencia").innerHTML = "$ 0.00";
  document.getElementById("transferencia_calculado").innerHTML = "$ 0.00";
  document.getElementById("transferencia_diferencia").innerHTML = "$ 0.00";
  document.getElementById("total_calculado").innerHTML = "$ 0.00";
  document.getElementById("total_diferencia").innerHTML = "$ 0.00";
}

function showModalCashClosing()
{
  total = 0;
  json = '{"caja_id":"'+ document.getElementById("txtCashRegisterId").value +'"}';

  const swalWithBootstrapButtons = Swal.mixin({
    customClass: {
      actions: "d-flex justify-content-around",
      confirmButton: "btn-custom btn-custom--border-blue",
      cancelButton: "btn-custom btn-custom--blue",
    },
    buttonsStyling: false,
  });

  $.ajax({
    method: "POST",
    url: "php/funciones.php",
    data: {
      clase: 'get_data',
      funcion: 'get_countCashRegisterAccountsStatus',
      value: json
    },
    dataType: 'json',
    success: function(response){
      res = JSON.parse(response);
      if(res > 0){
        swalWithBootstrapButtons.fire(
          {
            title: "",
            text: "¿Desea realizar el corte de caja?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: '<span class="verticalCenter">Aceptar</span>',
            cancelButtonText: '<span class="verticalCenter">Cancelar</span>',
            reverseButtons: false,
          }
        ).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              method: "POST",
              url: "php/funciones.php",
              data: {
                clase: 'get_data',
                funcion: 'get_totalsCountCashClosing',
                value: json
              },
              dataType: 'json',
              success: function(response){
                response.forEach((i)=>{
                  total += parseFloat(i.total);
                  switch(parseInt(i.tipo_pago)){
                    case 1:
                        document.getElementById("efectivo_calculado").innerHTML = numeral(i.total).format('0.00');
                        document.getElementById("efectivo_calculado_hide").value = i.total;
                        document.getElementById("efectivo_diferencia").innerHTML = numeral(parseFloat(document.getElementById("txtCashCounted").value) - parseFloat(i.total)).format('0.00');
                        document.getElementById("efectivo_diferencia_hide").value = parseFloat(document.getElementById("txtCashCounted").value) - parseFloat(i.total);
                      break;
                    case 2:
                      document.getElementById("credito_calculado").innerHTML = numeral(i.total).format('0.00');
                      document.getElementById("credito_calculado_hide").value = i.total;
                      document.getElementById("credito_diferencia").innerHTML = numeral(parseFloat(document.getElementById("txtCreditCounted").value) - parseFloat(i.total)).format('0.00');
                      document.getElementById("credito_diferencia_hide").value = parseFloat(document.getElementById("txtCreditCounted").value) - parseFloat(i.total);
                      break;
                    case 3:
                      document.getElementById("transferencia_calculado").innerHTML = numeral(i.total).format('0.00');
                      document.getElementById("transferencia_calculado_hide").value = i.total;
                      document.getElementById("transferencia_diferencia").innerHTML = numeral(parseFloat(document.getElementById("txtTransferCounted").value) - parseFloat(i.total)).format('0.00');
                      document.getElementById("transferencia_diferencia_hide").value = parseFloat(document.getElementById("txtTransferCounted").value) - parseFloat(i.total)
                      break;
                  }
                });
                
                document.getElementById("total_calculado").innerHTML = numeral(total).format('0.00');
                document.getElementById("total_calculado_hide").value = total;
                document.getElementById("total_diferencia").innerHTML = numeral(parseFloat(document.getElementById("total_contado").innerHTML) - parseFloat(total)).format('0.00');
                document.getElementById("total_diferencia_hide").value = parseFloat(document.getElementById("total_contado").innerHTML) - parseFloat(total);
                document.getElementById("name_cash").innerHTML = document.getElementById("labelCaja").innerHTML
                
        
                $("#add_cash_closing").modal("show");

                $("#add_cash_register_question").modal("hide");
                $("#add_cash_register").modal("hide");
                $("#add_tax_modal").modal("hide");
                $("#cancel_add_cash_register").modal("hide");
                // $("#add_cash_closing").modal("hide");
                $("#add_cash_register_movements").modal("hide");
                $("#create_product").modal("hide");
                $("#opening_cash_register").modal("hide");
                $("#modal_pedding_sale").modal("hide");
                $("#product_checker").modal("hide");
                $("#product_finder").modal("hide");
                $("#modal_product_sales").modal("hide");
                $("#add_producto_service_key").modal("hide");
                $("#add_unit_product_key").modal("hide");
                $("#modal_products_found_seeker_checker").modal("hide");
                $("#modal_products_found_seeker").modal("hide");
                $("#redirect_dashboard").modal("hide");
                $("#modal_update_product_ticket").modal("hide");
                $("#modal_update_product").modal("hide");
                $("#update_quantity").modal("hide");
                $("#update_tax_modal").modal("hide");
              },
              error: function(error){
                console.log(error);
              }
            });
          } else if (result.dismiss === Swal.DismissReason.cancel){
            $('#txtSearchProduct').trigger('focus');
          } 
        });
      } else {
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/warning_circle.svg",
          msg: "No hay ventas para realizar el corte de caja",
        });
      }
    },

    error: function(error){
      console.log(error);
    }
  });
}

function updateQuantityProduct()
{
    caja_id = document.getElementById('txtCashRegisterId').value;
    suc = document.getElementById('txtBranchOfficeId').value
    if(tblTicket.data().count()){
        index= parseInt($("#txtIdRow").val());
        
        quantity = parseFloat($("#quantity").text());
        id_product = tblTicket.cell({row:index, column:0}).data();
        tblTicket.cell({row:index, column:1}).data(quantity).draw(false);
        price_aux = tblTicket.cell({row:index, column:6}).data();
        console.log(price_aux);
        price = price_aux.split("$")[1];
        real_price_aux = price.split(",");
        real_price = "";
        for (let i = 0; i < real_price_aux.length; i++) {
            real_price += real_price_aux[i];
            
        }
        
        tblTicket.cell({row:index, column:7}).data("$" + numeral(parseFloat(real_price) * quantity).format('0,000,000,000.00')).draw(false);
        
        $("#update_quantity").modal("toggle");
        
        get_productsFormatTable(id_product,suc,quantity,caja_id,"");
    }
}

function escribirRazonSocial() {
  var valor = $("#txtRazonSocial").val();
  var valorHis = $("#txtRazonSocialHis").val();

  if (valor != valorHis) {
    console.log("Valor nombre" + valor);
    $.ajax({
      url: "../../../clientes/php/funciones.php",
      data: {
        clase: "get_data",
        funcion: "validar_razonSocial_Cliente",
        data: valor,
      },
      dataType: "json",
      success: function (data) {
        console.log("respuesta nombre valida: ", data);
        /* Validar si ya existe el identificador con ese nombre*/
        if(parseInt(data[0]["existe"]) == 1){
          $("#invalid-razon").css("display", "block");
          $("#invalid-razon").text(
            "La razón social ya esta registrada en el sistema."
          );
          $("#txtRazonSocial").addClass("is-invalid");
          console.log("¡Ya existe!");
        }else if(!valor){
          $("#invalid-razon").css("display", "block");
          $("#invalid-razon").text(
            "El cliente debe tener una razón social."
          );
          $("#txtRazonSocial").addClass("is-invalid");
        }else{
          $("#invalid-razon").css("display", "none");
          $("#invalid-razon").text(
            "El cliente debe tener una razón social."
          );
          $("#txtRazonSocial").removeClass("is-invalid");
          console.log("¡No existe!");
        }
      },
    });
  }

  var razonSocial = $("#txtRazonSocial").val().toLowerCase();
  if(razonSocial.endsWith(' s.a. de c.v.') || razonSocial.endsWith(' sa de cv') || razonSocial.endsWith(' s.a.') || razonSocial.endsWith(' sa') || razonSocial.endsWith(' sociedad anónima') || razonSocial.endsWith(' sociedad anonima') || razonSocial.endsWith(' s. de r.l.') || razonSocial.endsWith(' s de rl') || razonSocial.endsWith(' sociedad de responsabilidad limitada') || razonSocial.endsWith(' s. en c') || razonSocial.endsWith(' s en c') || razonSocial.endsWith(' sociedad en comandita') || razonSocial.endsWith(' socidad civil')){
    $("#txtRazonSocial").addClass("is-invalid");
    $("#invalid-razonTipoSociedad").css("display", "block");
  }else{
    $("#txtRazonSocial").removeClass("is-invalid");
    $("#invalid-razonTipoSociedad").css("display", "none");
  }
}

function validaNumTelefono(evt, input, invalid_card, resultHidden) {
  var key = window.Event ? evt.which : evt.keyCode;
  if($("#"+input).val()=='' || $("#"+input).val() == null){
      $("#"+invalid_card).css("display", "none");
      $("#"+input).removeClass("is-invalid");
    return false;
  }
  if (key == 8 || key == 46) {
    $("#"+resultHidden).val($("#"+input).val().length);
    $("#"+resultHidden).addClass("mui--is-not-empty");
    var valor = $("#"+resultHidden).val();
    if (valor < 8 || valor == 9) {
      $("#"+invalid_card).css("display", "block");
      $("#"+input).addClass("is-invalid");
    } else {
      $("#"+invalid_card).css("display", "none");
      $("#"+input).removeClass("is-invalid");
    }
  } else {
    $("#"+resultHidden).val($("#"+input).val().length);
    $("#"+resultHidden).addClass("mui--is-not-empty");
    var valor = $("#"+resultHidden).val();
    if (valor < 8 || valor == 9) {
      $("#"+invalid_card).css("display", "block");
      $("#"+input).addClass("is-invalid");
    } else {
      $("#"+invalid_card).css("display", "none");
      $("#"+input).removeClass("is-invalid");
      return false;
    }
  }
}

function valida_check(sender){
  if (sender.checked) {
    $('.DataClient_invoice').css({'display': 'inline-block','visibility': 'visible','opacity': '1','animation': 'fade 1s'});
  } else {
    $('.DataClient_invoice').css({'display': 'none','opacity': '0','visibility': 'hidden'});
  }
}

function escribirNombre() {
  var valor = $("#txtNombreComercial").val();
  $.ajax({
    url: "../../../clientes/php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "validar_nombreComercial",
      data: valor,
    },
    dataType: "json",
    success: function (data) {
      console.log("respuesta nombre valida: ", data);
      /* Validar si ya existe el identificador con ese nombre*/
      if (parseInt(data[0]["existe"]) == 1) {
        $("#invalid-nombreCom").css("display", "block");
        $("#invalid-nombreCom").text(
          "El nombre ya esta registrado en el sistema."
        );
        $("#txtNombreComercial").addClass("is-invalid");
        console.log("¡Ya existe!");
      } else {
        $("#invalid-nombreCom").css("display", "none");
        $("#invalid-nombreCom").text(
          "El cliente debe tener un nombre comercial."
        );
        $("#txtNombreComercial").removeClass("is-invalid");
        console.log("¡No existe!");
        if (!valor) {
          $("#invalid-nombreCom").css("display", "block");
          $("#invalid-nombreCom").text(
            "El cliente debe tener un nombre comercial."
          );
          $("#txtNombreComercial").addClass("is-invalid");
        }
      }
    },
  });
}

function validInput(inputID, invalidDivID, textInvalidDiv) {
  if (!$("#" + inputID).val() || $("#" + inputID).val() == 0) {
    $("#" + inputID).addClass("is-invalid");
    $("#" + invalidDivID).css("display", "block");
    $("#" + invalidDivID).text(textInvalidDiv);
  } else {
    $("#" + inputID).removeClass("is-invalid");
    $("#" + invalidDivID).css("display", "none");
    $("#" + invalidDivID).text(textInvalidDiv);
    if(inputID == 'txtRFC'){
        let vRFC = $("#txtRFC").val()
        let rfc = vRFC.trim().toUpperCase();
        let rfcCorrecto = rfcValido(rfc); // ⬅️ Acá se comprueba
        if (rfcCorrecto) {
          $("#invalid-rfc").css("display", "none");
          $("#invalid-rfc").text("El cliente debe tener un RFC.");
          $("#txtRFC").removeClass("is-invalid");
          escribirRFC();
        } else {
          $("#invalid-rfc").css("display", "block");
          $("#invalid-rfc").text("El RFC ingresado no es valido.");
          $("#txtRFC").addClass("is-invalid");
        }
    }
  }
}

function validarCorreo(value, inpt, invalid_card) {
  var reg =
    /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

  var regOficial =
    /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/;

  //Se muestra un texto a modo de ejemplo, luego va a ser un icono
  if (reg.test(value) && regOficial.test(value)) {
    $("#"+invalid_card).css("display", "none");
    $("#"+invalid_card).text("E-mail inválido.");
    $("#"+inpt).removeClass("is-invalid");
  } else if (reg.test(value)) {
    $("#"+invalid_card).css("display", "none");
    $("#"+invalid_card).text("E-mail inválido.");
    $("#"+inpt).removeClass("is-invalid");
  } else {
    $("#"+invalid_card).css("display", "block");
    $("#"+invalid_card).text("E-mail inválido.");
    $("#"+inpt).addClass("is-invalid");
  }
}

function validarCP(inpt, invalid_card) {
  var value = $("#"+inpt).val();
  var ercp = /(^([0-9]{5,5})|^)$/;
  $.ajax({
    url: "../../../clientes/php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "valid_cp",
      data: value
    },
    dataType: "json",
    success: function (respuesta) {
      if (!ercp.test(value) || !value || respuesta == false) {
        $("#"+invalid_card).css("display", "block");
        $("#"+invalid_card).text("El CP ingresado no es valido.");
        $("#"+inpt).addClass("is-invalid");
      } else {
        $("#"+invalid_card).css("display", "none");
        $("#"+invalid_card).text("El codigo postal.");
        $("#"+inpt).removeClass("is-invalid");
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function resetForm(frm){
  form=document.getElementById(frm);
  form.reset();

  if(frm == "agregarDireccionCL"){
    $("#invalid-sucursalD").css("display", "none");
    $("#txtSucursalD").removeClass("is-invalid");

    $("#invalid-emailDire").css("display", "none");
    $("#txtEmailD").removeClass("is-invalid");

    $("#invalid-numExt").css("display", "none");
    $("#txtNumExt").removeClass("is-invalid");

    $("#invalid-txtMunicipio").css("display", "none");
    $("#municipioDire").removeClass("is-invalid");

    $("#invalid-colonia").css("display", "none");
    $("#txtColonia").removeClass("is-invalid");

    SS_cmbPaisD.set();
    $("#invalid-paisDire").css("display", "none");
    $("#cmbPaisD").removeClass("is-invalid");

    SS_cmbEstadoD.set();
    $("#invalid-estadoDire").css("display", "none");
    $("#cmbEstadoD").removeClass("is-invalid");

    $("#invalid-cpDire").css("display", "none");
    $("#txtCPD").removeClass("is-invalid");

    $("#invalid-calleDire").css("display", "none");
    $("#txtCalle").removeClass("is-invalid");

    
  }else if(frm == "agregarCliente"){
    $('.DataClient_invoice').css({'display': 'none','opacity': '0','visibility': 'hidden'});

    $("#cmbMedioContactoCliente").trigger("change");
    $("#cmbVendedorNC").trigger("change");
    SS_cmbPais.set();
    SS_cmbEstado.set();

    $("#invalid-nombreCom").css("display", "none");
    $("#txtNombreComercial").removeClass("is-invalid");

    /* $("#invalid-medioCont").css("display", "none");
    $("#cmbMedioContactoCliente").removeClass("is-invalid"); */

    /* $("#invalid-vendedorNC").css("display", "none");
    $("#cmbVendedorNC").removeClass("is-invalid"); */

    /* $("#invalid-email").css("display", "none");
    $("#txtEmail").removeClass("is-invalid"); */

    $("#invalid-razon").css("display", "none");
    $("#txtRazonSocial").removeClass("is-invalid");

    $("#invalid-rfc").css("display", "none");
    $("#txtRFC").removeClass("is-invalid");

    $("#cmbRegimen").trigger("change");
    $("#invalid-regimen").css("display", "none");
    $("#cmbRegimen").removeClass("is-invalid");

    $("#invalid-cp").css("display", "none");
    $("#txtCP").removeClass("is-invalid");

    /* $("#invalid-paisFisc").css("display", "none");
    $("#cmbPais").removeClass("is-invalid"); */

    /* $("#invalid-paisEstadoFisc").css("display", "none");
    $("#cmbEstado").removeClass("is-invalid"); */
  }else if(frm == "agregarLocacion"){
    $("#invalid-nombreSuc").css("display", "none");
    $("#txtarea").removeClass("is-invalid");

    SS_txtarea6.set();
    SS_txtarea8.set();

    /* $("#invalid-calleSuc").css("display", "none");
    $("#txtarea2").removeClass("is-invalid");

    $("#invalid-noExtSuc").css("display", "none");
    $("#txtarea3").removeClass("is-invalid");

    $("#invalid-coloniaSuc").css("display", "none");
    $("#txtarea5").removeClass("is-invalid");

    $("#invalid-municipioSuc").css("display", "none");
    $("#txtarea7").removeClass("is-invalid");

    $("#invalid-paisSuc").css("display", "none");
    $("#txtarea8").removeClass("is-invalid");

    $("#invalid-estadoSuc").css("display", "none");
    $("#txtarea6").removeClass("is-invalid");

    $("#invalid-telSuc").css("display", "none");
    $("#txtarea10").removeClass("is-invalid"); */
  }else if(frm == "agregarEmpleado"){
    $("#invalid-nombre").css("display", "none");
    $("#txtNombre").removeClass("is-invalid");

    $("#invalid-primerApellido").css("display", "none");
    $("#txtPrimerApellido").removeClass("is-invalid");

    /* $("#invalid-genero").css("display", "none");
    $("#cmbGenero").removeClass("is-invalid"); */

    $("#invalid-cpE").css("display", "none");
    $("#txtCPE").removeClass("is-invalid");

   /*  $("#invalid-estadoNE").css("display", "none");
    $("#cmbEstado_NE").removeClass("is-invalid");

    $("#invalid-roles").css("display", "none");
    $("#cmbRoles").removeClass("is-invalid"); */

    SS_genero.set();
    SS_cmbEstado_NE.set();
    SS_cmbRoles.set(1);
  }else if(frm == "agregarProductoForm"){
    $("#invalid-nombreProducto").css("display", "none");
    $("#txtProducto").removeClass("is-invalid");

    $("#invalid-clave").css("display", "none");
    $("#txtClave").removeClass("is-invalid");

    SS_cmbTipoProducto.set();
    $("#invalid-tipoProd").css("display", "none");
    $("#cmbTipoProducto").removeClass("is-invalid");
  }
}

function getCurrentBalance()
{
  caja_id = document.getElementById("txtCashRegisterId").value;
  $.ajax({
    method: "POST",
    url: "php/funciones.php",
    data: {
      clase: 'get_data',
      funcion: 'get_currentBalance',
      value: caja_id
    },
    dataType: 'json',
    success: function(response){
      document.getElementById("txtCurrentBalanceHide").value = response[0].saldo_actual;
      document.getElementById("txtCurrentBalance").innerHTML = numeral(response[0].saldo_actual).format('0,000,000,000.00');
    },
    error: function(error){
      console.log(error);
    }
  });
}

function saveMovemementAccount()
{
  json = 
  '{'+
    '"tipo_movimiento" : "' + tipo_movimiento + '",' +
    '"total" : "' + monto + '",' +
    '"tipo_documento" : "0",' +
    '"estatus" : "1",' +
    '"tipo_pago" : "0",' +
    '"comentario" : "' + comentario + '",' +
    '"caja_id" : "' + caja_id + '"'+
  '}';

  $.ajax({
    method: "POST",
    url: "php/funciones.php",
    data: {
      clase: 'save_data',
      funcion: 'save_accountMovementData',
      value: json,
      value1:""
    },
    dataType: 'json',
    success: function(response){
      r = JSON.parse(response);
      if(r) {

        $.ajax({
          method: "POST",
          url: "php/funciones.php",
          data: {
            clase: 'update_data',
            funcion: 'update_currentBalance',
            value: json
          },
          dataType: 'json',
          success: function(response){
            r = JSON.parse(response);

            if(r) {

              Lobibox.notify("success", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../img/timdesk/checkmark.svg",
                msg: "Se realizó el movimiento con éxito",
                sound:false,
              });
              $("#add_cash_register_movements").modal('hide');
              document.getElementById("form-data-regsiter-movements").reset();
              cmbMovementType.set("");
            }
          },
          error: function(error){
            console.log(error);
          }
        });
      }
      
    },
    error: function(error){
      console.log(error);
    }
  });
}

function list_tickets()
{
  var value = document.getElementById('txtCashRegisterId').value;
  
  if ($("#tblTicketsView tbody tr").length > 0 ) {
    $("#tblTicketsView").DataTable().destroy();
   
    tblTicketsView = loadTicketsTable(value);
  } else {
    tblTicketsView = loadTicketsTable(value);
  }
}

function getCountBranchOffice(){
  // get_countBranchOffice

  $.ajax({
    method: "POST",
    url: "php/funciones.php",
    data: {
      clase: 'get_data',
      funcion: 'get_countBranchOffice'
    },
    dataType: 'json',
    success: function(response){
      if(parseInt(response) > 0){
        document.getElementById("loadAllProducts").classList.remove("no-visible");
        document.getElementById("loadAllProducts").classList.add("yes-visible-inputs");
      }
    },
    error: function(error){
      console.log(error);
    }
  });
}

function cancelTicket(id,folio)
{
  var caja_id = document.getElementById('txtCashRegisterId').value;
  $.ajax({
    method: "POST",
    url: "php/funciones.php",
    data: {
      clase: 'update_data',
      funcion: 'update_cancelTicketData',
      value: id,
      value1: 2,
      value2: caja_id
    },
    dataType: 'json',
    success: function(response){
      
      if(response){
        $("#tblTicketsView").DataTable().ajax.reload();
        Lobibox.notify("success", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/checkmark.svg",
          msg: "Se realizó la cancelación del ticket con folio: " + folio + " con éxito",
          sound:false,
        });
      }
    },
    error: function(error){
      console.log(error);
    }
  });
}

function getPrintTicket(id,m_recibido,m_cambio,date,date1)
{
    var caja_id = document.getElementById("txtCashRegisterId").value;
    
    $.redirect("php/print_ticket2.php", {
        value: id,
        value1: m_recibido,
        value2: m_cambio,
        date: date,
        date1: date1,
        value3: caja_id
    },
        "POST",
        "_blank"
    );
}

function getLastTicket()
{
  $.ajax({
    method: "POST",
    url: "php/funciones.php",
    data: {
      clase: 'get_data',
      funcion: 'get_lastTicket'
    },
    dataType: 'json',
    success: function(response){
      getPrintTicket(response[0].id,"","","","");
    },
    error: function(error){
      console.log(error);
    }
  });
}

function get_productsTicketTaxUnique(value)
{
  $.ajax({
    method: "POST",
    url: "php/funciones.php",
    data: {
      clase: 'get_data',
      funcion: 'get_productsTicketTaxUnique',
      value: value
    },
    dataType: 'json',
    success: function(response){
      console.log(response);
    },
    error: function(error){
      console.log(error);
    }
  });
}

function get_productsTicketTaxUnique(value)
{
  $.ajax({
    method: "POST",
    url: "php/funciones.php",
    data: {
      clase: 'get_print',
      funcion: 'get_formatTicketTax',
      value: value
    },
    dataType: 'json',
    success: function(response){
      console.log(response);
    },
    error: function(error){
      console.log(error);
    }
  });
}

function get_formatProductsInvoice(value)
{
  $.ajax({
    method: "POST",
    url: "php/funciones.php",
    data: {
      clase: 'get_data',
      funcion: 'get_formatProductsInvoice',
      value: value
    },
    dataType: 'json',
    success: function(response){
      console.log(response);
    },
    error: function(error){
      console.log(error);
    }
  });
}

function get_formatInvoice(value)
{
  $.ajax({
    method: "POST",
    url: "php/funciones.php",
    data: {
      clase: 'get_data',
      funcion: 'get_formatInvoice',
      value: value
    },
    dataType: 'json',
    success: function(response){
      console.log(response);
    },
    error: function(error){
      console.log(error);
    }
  });
}

function createInvoice(value)
{
  $.ajax({
    method: "POST",
    url: "php/funciones.php",
    data: {
      clase: 'get_invoice',
      funcion: 'create_invoice',
      value: value
    },
    dataType: 'json',
    success: function(response){
      console.log(response);
    },
    error: function(error){
      console.log(error);
    }
  });
}

function loadGeneralInvoice(initialDate,finalDate)
{
  loadGenerealInvoice(initialDate,finalDate);
}

function get_formatClientGeneralInvoice()
{
  $.ajax({
    method: "POST",
    url: "php/funciones.php",
    data: {
      clase: 'get_data',
      funcion: 'get_formatClientGeneralInvoice'
    },
    dataType: 'json',
    success: function(response){
      console.log(response);
    },
    error: function(error){
      console.log(error);
    }
  });
}

function get_formatInvoiceGeneral(value,value1)
{
  $.ajax({
    method: "POST",
    url: "php/funciones.php",
    data: {
      clase: 'get_data',
      funcion: 'get_formatInvoiceGeneral',
      value: value,
      value1: value1
    },
    dataType: 'json',
    success: function(response){
      console.log(response);
    },
    error: function(error){
      console.log(error);
    }
  });
}

function saveAllDataGeneralInvoice(value,value1,value2)
{
  $("#loader2").css("display","block");
  $("#loader2").addClass("loader");

  $.ajax({
    method: "POST",
    url: "php/funciones.php",
    data: {
      clase: 'save_data',
      funcion: 'save_allDataGeneralInvoice',
      value: value,
      value1: value1,
      value2: value2
    },
    dataType: 'json',
    success: function(response){
      $(".loader").fadeOut("slow");
      $("#loader2").removeClass("loader");
      console.log(response);
      if(response)
      {
        $("#modal_fiscal_data_general").modal("hide");

        Lobibox.notify("success", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/checkmark.svg",
          msg: "Se guardó la factura global con éxito",
          sound:false,
        });
        
      }
    },
    error: function(error){
      console.log(error);
    }
  });
}

function get_idTicketGeneralInvoice(value,value1)
{
  $.ajax({
    method: "POST",
    url: "php/funciones.php",
    data: {
      clase: 'get_data',
      funcion: 'get_idTicketGeneralInvoice',
      value: value,
      value1: value1
    },
    dataType: 'json',
    success: function(response){
      console.log(response);
    },
    error: function(error){
      console.log(error);
    }
  });
}

function save_detailsGeneralInvoice(value,value1)
{
  

  $.ajax({
    method: "POST",
    url: "php/funciones.php",
    data: {
      clase: 'save_data',
      funcion: 'save_detailsGeneralInvoice',
      value: value,
      value1: value1
    },
    dataType: 'json',
    success: function(response){
      console.log(response);
    },
    error: function(error){
      console.log(error);
    }
  });
}

function get_formatSaveDetailGeneralInvoice(value,value1)
{
  $.ajax({
    method: "POST",
    url: "php/funciones.php",
    data: {
      clase: 'get_data',
      funcion: 'get_formatSaveDetailGeneralInvoice',
      value: value,
      value1: value1
    },
    dataType: 'json',
    success: function(response){
      console.log(response);
    },
    error: function(error){
      console.log(error);
    }
  });
}
//

function get_productsTicketTaxUnique(value,value1,value2)
{
  $.ajax({
    method: "POST",
    url: "php/funciones.php",
    data: {
      clase: 'get_data',
      funcion: 'get_productsTicketTaxUnique',
      value: value,
      date: value1,
      date1: value2
    },
    dataType: 'json',
    success: function(response){
      console.log(response);
    },
    error: function(error){
      console.log(error);
    }
  });
}

function get_generalInvoiceTaxUnique(value,value1,value2)
{
  $.ajax({
    method: "POST",
    url: "php/funciones.php",
    data: {
      clase: 'get_data',
      funcion: 'get_generalInvoiceTaxUnique',
      value: value,
      date: value1,
      date1: value2
    },
    dataType: 'json',
    success: function(response){
      console.log(response);
    },
    error: function(error){
      console.log(error);
    }
  });
}

function save_dataSale(value)
{
  $.ajax({
    method: "POST",
    url: "php/funciones.php",
    data: {
      clase: 'save_data',
      funcion: 'save_dataSale',
      value: value,
    },
    dataType: 'json',
    success: function(response){
      console.log(response);
    },
    error: function(error){
      console.log(error);
    }
  });
}

function checkPriceZero(value)
{
    $.ajax({
        method: "POST",
        url: "php/funciones.php",
        data: {
            clase: 'get_data',
            funcion: 'check_priceZero',
            value: value,
        },
        dataType: 'json',
        success: function(response){
            var table = $("#tblTicket").DataTable();
            if(parseInt(response) === 0)
            {
                $("#alert_price_zero div").css("display","block");
                table.buttons().disable();
            } else {
                $("#alert_price_zero div").css("display","none");
                table.buttons().enable();
            }
        },
        error: function(error){
          console.log(error);
        }
      });
}

function clearTable(){
    var caja_id = $("#txtCashRegisterId").val();
            
    document.getElementById("subtotal-ticket").innerHTML = "$0.00";


    var elem1 = document.getElementById("ticket-taxes-names");
    while(elem1.firstChild) {
        elem1.removeChild(elem1.firstChild);
    }
    
    var elem2 = document.getElementById("ticket-taxes-prices");
    while(elem2.firstChild) {
        elem2.removeChild(elem2.firstChild);
    }
    
    document.getElementById("ticket-total-price").innerHTML = "$0.00";
    deleteAllProductsTableTemp(caja_id)
    getSubtotalsTableTemp(caja_id);
    
    tblTicket.clear().draw();
}

function getPasswordAdmin(value)
{
    $.ajax({
        method: "POST",
        url: "php/funciones.php",
        data: {
            clase: 'get_data',
            funcion: 'get_passwordAdmin',
            value: value,
        },
        dataType: 'json',
        success: function(response){
            console.log(response);
            if(response[0].length <= 0 && (response[0].password_admin === "" && response[0].password_admin === null)){
                Lobibox.notify("error", {
                    size: "mini",
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: "center top",
                    icon: true,
                    img: "../../img/timdesk/warning_circle.svg",
                    msg: "La contraseña no coincide con la registrada.",
                  });
            }
        },
        error: function(error){
          console.log(error);
        }
    });
}

function getCashRegister(value,value1)
{
    $.ajax({
        url: "php/funciones.php",
        method:"POST",
        data: {
            clase: "get_data",
            funcion: "get_cash_register",
            value: value
        },
        datatype: "json",
        success: function(respuesta){
            document.getElementById("txtHideUserType").value = value1;
            if(value1 === 1){
                document.getElementById("txtUserType").innerHTML = "Administrador";
                getEmployerNameFounder();
                document.getElementById('li_link_informes').innerHTML = 
                    '<a class="nav-link" href="#" id="link_informes">'+
                        '<span data-toggle="tooltip" data-placement="top" title="Atajo: ctrl + alt + I">'+
                            '<img src="../../img/punto_venta/informes.svg" width="30" > Informes'+
                        '</span>'+
                    '</a>'
                ;
                document.getElementById("li_add_cash_register").innerHTML =
                '<a class="nav-link" href="#" id="btn_add_cash_register" data-toggle="modal" data-target="#add_cash_register" > <img src="../../img/punto_venta/ICONO AGREGAR CAJA-01-01.svg" width="30" > Agregar caja</a>'

                $('#th_calculado').show();
                $('#th_diferencia').show();
                $('#th_efectivo_calculado').show();
                $('#th_efectivo_diferencia').show();
                $('#th_credito_calculado').show();
                $('#th_credito_diferencia').show();
                $('#th_transferencia_calculado').show();
                $('#th_transferencia_diferencia').show();
                $('#efectivo_calculado').show();
                $('#efectivo_diferencia').show();
                $('#credito_calculado').show();
                $('#credito_diferencia').show();
                $('#transferencia_calculado').show();
                $('#transferencia_diferencia').show();
                $('#total_calculado').show();
                $('#total_diferencia').show();
            } else {
                nombre_cajero = $("#cmbEmpleado option:selected").text();
                id_cajero = $("#cmbEmpleado").val();
                document.getElementById("txtUserType").innerHTML = "Cajero: ";
                document.getElementById("txtEmployedName").innerHTML = nombre_cajero;
                document.getElementById("txtEmployeId").value = id_cajero;
                $('#th_calculado').hide();
                $('#th_diferencia').hide();
                $('#th_efectivo_calculado').hide();
                $('#th_efectivo_diferencia').hide();
                $('#th_credito_calculado').hide();
                $('#th_credito_diferencia').hide();
                $('#th_transferencia_calculado').hide();
                $('#th_transferencia_diferencia').hide();
                $('#efectivo_calculado').hide();
                $('#efectivo_diferencia').hide();
                $('#credito_calculado').hide();
                $('#credito_diferencia').hide();
                $('#transferencia_calculado').hide();
                $('#transferencia_diferencia').hide();
                $('#total_calculado').hide();
                $('#total_diferencia').hide();
            }
            
            r = JSON.parse(respuesta);
            deleteAllProductsTableTemp(r[0].caja_id);
            document.getElementById("labelCaja").innerHTML = r[0].nombre;
            document.getElementById("labelSucursal").innerHTML = r[0].sucursal;
            document.getElementById("txtCashRegisterId").value = r[0].caja_id;
            document.getElementById("txtBranchOfficeId").value = r[0].sucursal_id;
    
            document.querySelector("#idBoxSuc").classList.remove("no-visible");
            document.querySelector("#idBoxSuc").classList.add("yes-visible");
    
            document.querySelector("#itemsBoxSuc").classList.remove("no-visible");
            document.querySelector("#itemsBoxSuc").classList.add("yes-visible");

            document.getElementById("txtPrinterNameUpdate").value = r[0].nombre_impresora;
            if(r[0].activar_inventario !== "Sin inventario"){
                document.querySelector("#txtActiveInventory").classList.add("left-dot","green-dot");
                document.getElementById("txtActiveInventory").innerHTML = r[0].activar_inventario;
            } else {
                document.querySelector("#txtActiveInventory").classList.add("left-dot","yellow-dot");
                document.getElementById("txtActiveInventory").innerHTML = r[0].activar_inventario;
            }
    
            $("#opening_cash_register").modal("hide");
            
            if ($("#tblProductsFinder tbody tr").length === 0 ) {
                $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
                tblProductsFinder = loadProductsFinder();
                tblProductsFinderAll = loadProductsFinderAll();
            } else {
                $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();

                $("#tblProductsFinder").DataTable().ajax.reload();
                
                $("#tblProductsFinderAll").DataTable().ajax.reload();

            }
        },
            error: function(error){
            console.log(error);
        }
    });
}

function addOpeningCashRegister()
{
    if($("#form-select-cash-register")[0].checkValidity()){
        bad_selectCashRegister =
            $("#invalid-selectCashRegister").css("display") === "block" ? false : true;
        bad_selectUserType =
            $("#invalid-user_type").css("display") === "block" ? false : true;
        bad_passAdmin =
            $("#invalid-passAdmin").css("display") === "block" ? false : true;
        bad_employer_cash = 
            $("#invalid-employer_cash").css("display") === "block" ? false : true;
        if
        (
            bad_selectCashRegister &&
            bad_selectUserType &&
            bad_passAdmin &&
            bad_employer_cash
        ){
            var value = document.getElementById("cmb_cash_register").value;
            var value1 = document.getElementById("cmbTipoUsuario").value;
            var value2 = document.getElementById('txtPassAdmin').value;
            $.ajax({
                url: "php/funciones.php",
                method:"POST",
                data: {
                    clase: "get_data",
                    funcion: "get_passwordAdmin",
                    value: value
                },datatype: "json",
                success: function(response){
                    var r = JSON.parse(response);
                    
                    if(parseInt(value1) === 1){
                        if(parseInt(value2) === parseInt(r[0].password_admin)){
                            getCashRegister(value,parseInt(value1));
                        } else {
                            Lobibox.notify("error", {
                                size: "mini",
                                rounded: true,
                                delay: 3100,
                                delayIndicator: false,
                                position: "center top",
                                icon: true,
                                img: "../../img/timdesk/warning_circle.svg",
                                msg: "La contraseña del administrador es incorrecta.",
                            });
                        }
                    } else {
                        getCashRegister(value,parseInt(value1));
                    }
                    //tblTicket.clear().draw();
                }
            });
        }
      } else {
        if(!$("#cmb_cash_register").val()){
            $("#invalid-selectCashRegister").css("display", "block");
            $("#cmb_cash_register").addClass("is-invalid");
        }
        if(!$("#cmb_user_type").val()){
            $("#invalid-user_type").css("display", "block");
            $("#cmb_user_type").addClass("is-invalid");
        }
        if(!$("#txtPassAdmin").val()){
            $("#invalid-passAdmin").css("display", "block");
            $("#txtPassAdmin").addClass("is-invalid");
        }
        if(!$("#cmbEmpleado").val()){
            $("#invalid-employer_cash").css("display", "block");
            $("#cmbEmpleado").addClass("is-invalid");
        }
      }
}

function clearTicketTable()
{
    tblTicket = $("#tblTicket").DataTable();

    tblTicket.clear().draw();
}

async function loadSearchProductEnter(value,value1,value2)
{
    let result;
    try {
        result = await $.ajax({
            method: "POST",
            url: "php/funciones.php",
            data: {
                clase: 'get_data',
                funcion: 'get_product',
                value: value,
                value1: value1,
                value2: value2
            }
        });
        return result;
    } catch (error) {
        console.error(error);
    }
}

function saveProfesionalLicencse()
{
    if($("#form-add-professional-license")[0].checkValidity())
  {
    
    bad_profesionalLicense = 
      $("#invalid-professionalLicense").css("display") === "block" ? false : true;
      
    if(bad_profesionalLicense)
    {
      document.getElementById('txtHideProfesionalLincese').value = document.getElementById("txtProfessionalLicense").value;
      $("#modal_add_professional_license").modal("hide");
      
      showModalSellMerchandise();

    }
  } else {
    if (!$("#txtProfessionalLicense").val()) {
      $("#invalid-professionalLicense").css("display", "block");
      $("#txtProfessionalLicense").addClass("is-invalid");
    }
  }
}

function questExistWindows()
{
    var bPreguntar = true;
     
    window.onbeforeunload = preguntarAntesDeSalir;
        
    preguntarAntesDeSalir(bPreguntar);
}

function preguntarAntesDeSalir(value)
{
    if (value)
    return "¿Seguro que quieres salir?";
}

$(function() {
    $("form").submit(function() { return false; });
});

function validEmptyInput(item, invalid = null) {
    const val = item.value;
    const parent = item.parentNode;
    let invalidDiv;
    if (invalid) {
      invalidDiv = document.getElementById(invalid);
    } else {
      for (let i = 0; i < parent.children.length; i++) {
        if (parent.children[i].classList.contains("invalid-feedback")) {
          invalidDiv = parent.children[i];
          break;
        }
      }
    }
    if (!val) {
      item.classList.add("is-invalid");
      invalidDiv.style.display = "block";
    } else {
      item.classList.remove("is-invalid");
      invalidDiv.style.display = "none";
    }
  }

  function getEmployerNameFounder()
  {
    $.ajax({
      method: "POST",
      url: "php/funciones.php",
      data: {
          clase: 'get_data',
          funcion: 'get_admin'
      },
      dataType: 'json',
      success: function(response){
        console.log(response);
        document.getElementById("txtEmployeId").value = response[0].PKEmpleado
      },
      error: function(error){
        console.log(error);
      }
    });
  }