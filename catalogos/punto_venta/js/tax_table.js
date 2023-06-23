function loadTaxTable (){

  topButtons = [
    {
      text: '<i class="fas fa-plus-square"></i> Agregar impuesto',
      className: "btn-table-custom--blue",
      action: function () {
        $("#add_tax_modal").modal("toggle");
        
      },
    },
  ]

  tblTax = $("#tblTax").DataTable({
    language: setFormatDatatables(),
    info: false,
    scrollX: true,
    pageLength: 15,
    responsive: true,
    lengthChange: false,
    bFilter: false,
    bPaginate: false,
    dom: `<"container-fluid"<"row mb-3"<"col-sm-12 col-md-10 p-0 d-flex align-items-center"B><"col-sm-12 col-md-6 p-0"f>>>rti
    <"container-fluid mt-4"<"row"<"#btn-filters-tblProductsSearch.col-sm-12 col-md-8 p-0"><"col-sm-12 col-md-4 p-0"p>>>`,
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

    columnDefs: [
      {
        "targets":[0],
        "visible": false,
        "searchable": false
      }
    ]
    
  });
  tblTax.columns.adjust();

  $("#btnAddTax").on("click",function(){
    tax = $("#cmbTax").val();
    taxText = $("#cmbTax option:selected").text();
    cmbRateOrFeeVal = $("#cmbRateOrFee").val();
    tableCount = tblTax.data().count();
    if(tax !== null && cmbRateOrFeeVal !== null && tax !== "" && cmbRateOrFeeVal !== ""){

      
      if(tableCount > 0){

        var filteredData = tblTax
        .column( 0 )
        .data()
        .filter( function ( value, index ) {
            return value === tax ? true : false;
        } );

        if(filteredData[0] === undefined)
        {
          addDataTaxTable();
        } else {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/warning_circle.svg",
            msg: "El impuesto seleccionado ya se encuentra en la tabla",
          });
        }
      } else {
        addDataTaxTable();
      }

    }
    // cmbTax.set("");
    // cmbRateOrFee.set("");
  });

  $("#cmbTax").on("change",function(){
    tax = $("#cmbTax").val();
    loadCombo('rateOrFeeSelect','#cmbRateOrFee',"",tax,"Seleccione una tasa");
  });

  $("#tblTax tbody").on('click','a',function(){
    //tblTax = $("#tblTax").DataTable();
    tblTax.row( $(this).parents('tr')).remove().draw();
  });

  return tblTax;
}

function addDataTaxTable()
{
  tblTax.row.add([tax,taxText,cmbRateOrFeeVal,"<a href='#' id='delete_tax'><i class='fas fa-times-circle' style='color:red'></i></a>"]).draw(false);

  $("#add_tax_modal").modal("toggle");
  
  if($("#txtPrecioCompra").val() !== ""){
    value = $("#txtPrecioCompra").val();
    if($("#chkPrecioCompraNeto").is(":checked")){
      $("#txtPrecioCompraSinImpuestos").val(numeral(getPriceWithTax(value)).format('0,000,000,000.00'));
      checkUtilitiesVoid();
    } else {
      $("#txtPrecioCompraSinImpuestos").val(numeral(value).format('0,000,000,000.00'));
      checkUtilitiesVoid();
    }
  }
}