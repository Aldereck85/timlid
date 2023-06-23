var ids = [];
$(document).ready((e)=>{
    tblSales = $("#tblSales").DataTable({
        language: setFormatDatatables(),
        dom: "lrti",
        scrollX: true,
        scrollCollapse: false,
        lengthChange: false,
        info: false,
        bSort: false,
        paging: false,
        columnDefs: 
        [
            {
                targets:[0,5],
                visible: false,
                searchable: false
            }
        ]
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

    loadCombo('cfdiUse','#cmbCFDIUseGeneral','','','');
    loadCombo('formasPago','#cmbPaidTypeGeneral','','','');
    loadCombo('monedas','#cmbCurrencyGeneral',100,'','');
});

// $("#tblSales tbody").on("click","td",function(e){
//     var sum = 0;
//     row_index = tblSales.cell(this).index().row;
//     cell_index = tblSales.cell( this ).index().column
//     row_data = tblSales.row( row_index ).data();

//     switch (cell_index) {
//         case 6:
//             if($(".sales-checked").is(":checked")){
//                 getTotals();
//             }
            
            
//         break;
//     }
// });

$(document).on("click",".sales-checked",getTotals);

$(document).on("click","#sales-checked-all",()=>{
    if($('#sales-checked-all').is(":checked")){
        $(".sales-checked").prop('checked', true);
    } else {
        $(".sales-checked").prop('checked', false);
    }
    
});

function getDataFilters()
{
    var initialDate = $("#txtInitialDate").val();
    var finalDate = $("#txtFinalDate").val();
    $("#summary_subtotal").html("");
    $("#summary_taxes").html("");
    $("#summary_total").html("");
    if($('#sales-checked-all').is(":checked")){
        $("#sales-checked-all").prop('checked', false);
    }
    $.ajax({
        method: "POST",
        url: "php/funciones.php",
        data: {
        clase: "get_data",
        funcion: "get_salesData",
        initialDate: initialDate,
        finalDate: finalDate
        },
        dataType: "json",
        success: function (respuesta) {
            //console.log(respuesta.data);
            if(tblSales.rows().count() > 0) { 
                tblSales.clear().draw()
                
            };
            if(respuesta.data.length > 0 ){
                $("#sales-checked-all").prop("disabled",false);
            }
            respuesta.data.forEach((i)=>{
                tblSales.row.add(
                    [
                        i.id,
                        i.folio,
                        i.fecha,
                        i.cliente,
                        i.total,
                        i.total_,
                        i.funciones
                    ]
                ).draw(false);
            })
            
        },
        error: function (error) {
        console.log(error);
        },
    })
}

function showModalGlobalInvoice()
{
    var arr = getIdsTable();
    if(arr.length > 0){
        $("#modal_fiscal_data_general").modal('show');
    }else{
        Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/warning_circle.svg",
            msg: "No hay ventas seleccionadas.",
        });
    }
}

function saveGlobalInvoice()
{
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
            var arr = JSON.stringify(getIdsTable());
            var cfdiUse = document.getElementById('cmbCFDIUseGeneral').value;
            var paidMethod = document.getElementById('cmbPaidMethodGeneral').value;
            var paidType = document.getElementById('cmbPaidTypeGeneral').value;
            var currency = document.getElementById('cmbCurrencyGeneral').value;
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
            
            $.ajax({
                method: "POST",
                url: "php/funciones.php",
                data: {
                clase: "save_data",
                funcion: "save_globalInvoice",
                value: arr,
                value1: json
                },
                dataType: "json",
                success: function(response){

                }
            });
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
}

function getTotals()
{
    var sum = 0;
    var arr = getIdsTable();
    arr.forEach((i)=>{
        sum += parseFloat(i.subtotal);
    })
   
    
    if(sum > 0){
        txtsum = numeral(sum).format("000,000,000,000.00")
    } else {
        txtsum = numeral(sum).format("0.00")
    }
    if(arr.length > 0){
        var html = 
            '<div class="row">'+
                
                '<div class="col-4">'+
                    '<h5><b class="textBlue">Subtotal:</b></h5>'+
                '</div>'+
                '<div class="col-4"></div>'+
                '<div class="col-4 text-right">'+
                    '<h5 class="textBlue">$ <span id="subtotal">'+txtsum+'</span></h5>'+
                '</div>'+   
            '</div>';
    } else {
        html = "";
    }


    $("#summary_subtotal").html(html);
    getTaxes(arr,sum);
    
}

function getTaxes(arr,subtotal)
{
    $("#summary_taxes").html("");
    $("#summary_total").html("");
    if(arr.length > 0){
        
        $.ajax({
            method: "POST",
            url: "php/funciones.php",
            data: {
            clase: "get_data",
            funcion: "get_taxSummary",
            sales: arr
            },
            dataType: "json",
            success: function(response){
                
                $("#summary_taxes").html(response.texto);
                if(arr.length > 0){
                    var total = parseFloat(response.total) + parseFloat(subtotal);
                    if(total > 0){
                        txtTotal = numeral(total).format("000,000,000,000.00")
                    } else {
                        txtTotal = numeral(total).format("0.00")
                    }

                    var html = 
                        '<div class="row">'+
                            
                            '<div class="col-4">'+
                                '<h5><b class="textBlue">Total:</b></h5>'+
                            '</div>'+
                            '<div class="col-4"></div>'+
                            '<div class="col-4 text-right">'+
                                '<h5 class="textBlue">$ <span id="subtotal">'+txtTotal+'</span></h5>'+
                            '</div>'+   
                        '</div>';
                } else {
                    html = "";
                }
                $("#summary_total").html(html);
            }
        });
    } else {
        $("#summary_taxes").html("");
        console.log("ids está vacio");
    }
        
   
}

function getIdsTable()
{
    var ids = [];
    
    $(".sales-checked:checked").map(function(_, el) {
        ids.push({id:$(el).data("id"),subtotal:$(el).data("total")});
    }).get();

    return ids;
}

function setFormatDatatables() 
{
    return {
      sProcessing: "Procesando...",
      sZeroRecords: "No se encontraron resultados",
      sEmptyTable: "Ningún dato disponible en esta tabla",
      sSearch: "<img src='../../img/timdesk/buscar.svg' width='20px' />",
      sLoadingRecords: "Cargando...",
      searchPlaceholder: "Buscar...",
    };
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