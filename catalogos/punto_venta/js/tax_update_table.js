function loadTaxUpdateTable(){

  topButtons = [
    {
      text: '<i class="fas fa-plus-square"></i> Agregar impuesto',
      className: "btn-table-custom--blue",
      action: function () {
        $("#update_tax_modal").modal('toggle');
        
      },
    },
  ]

  tblUpdateTax = $("#tblUpdateTax").DataTable({
    language: setFormatDatatables(),
    info: false,
    scrollX: true,
    pageLength: 15,
    responsive: true,
    lengthChange: false,
    bFilter: false,
    bPaginate: false,
    retrieve: true,
    paging: false,
    deferRender: true,
    dom: `<"container-fluid"<"row mb-3"<"col-sm-12 col-md-10 p-0 d-flex align-items-center"B><"col-sm-12 col-md-6 p-0"f>>>rti
    <"container-fluid mt-4"<"row"<"#btn-filters-tblUpdateTax.col-sm-12 col-md-8 p-0"><"col-sm-12 col-md-4 p-0"p>>>`,
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
      },
      {
        "targets":[1],
        "width":"15%"
      }
    ]
    
  });
  tblUpdateTax.columns.adjust();

  $("#btnUpdateTax").on("click",function(){
    tax = $("#cmbUpdateTax").val();
    taxText = $("#cmbUpdateTax option:selected").text();
    cmbRateOrFee = $("#cmbUpdateRateOrFee").val();
    tableCount = tblUpdateTax.data().count();
    if(tax !== null && cmbRateOrFee !== null && tax !== "" && cmbRateOrFee !== ""){
      if(tableCount > 0){

        var filteredData = tblUpdateTax
        .column( 0 )
        .data()
        .filter( function ( value, index ) {
            return value === tax ? true : false;
        } );
        console.log(filteredData[0]);
        if(filteredData[0] === undefined)
        {
          addDataTaxTableUpdate();
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
        addDataTaxTableUpdate();
      }
    }
  });

  $("#cmbUpdateTax").on("change",function(){
    tax = $("#cmbUpdateTax").val();
    loadCombo('rateOrFeeSelect','#cmbUpdateRateOrFee',"",tax,"");
  });

  $("#tblUpdateTax tbody").on('click','a',function(){
    
    tblUpdateTax.row( $(this).parents('tr')).remove().draw();
  });

  return tblUpdateTax;
}

function addDataTaxTableUpdate()
{
  tblUpdateTax.row.add([tax,taxText,cmbRateOrFee,"<a href='#' id='delete_taxUpdate'><i class='fas fa-times-circle' style='color:red'></i></a>"]).draw(false);
  $("#update_tax_modal").modal("toggle");
  
  if($("#txtUpdatePrecioCompra").val() !== ""){
    value = $("#txtUpdatePrecioCompra").val();
    if($("#chkUpdatePrecioCompraNeto").is(":checked")){
      $("#txtUpdatePrecioCompraSinImpuestos").val(numeral(getPriceWithTax(value)).format('0,000,000,000.00'));
      checkUtilitiesVoid();
    } else {
      $("#txtUpdatePrecioCompraSinImpuestos").val(numeral(value).format('0,000,000,000.00'));
      checkUtilitiesVoid();
    }
  }
}