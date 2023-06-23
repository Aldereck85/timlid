$(document).ready(function(){
  getStatusCancel();
  new SlimSelect({
    select: "#cmbMotivoCancelacion",
    placeholder: "Seleccione un motivo de cancelación..."
  })

  new SlimSelect({
    select: "#cmbRelationInvoice",
    placeholder: "Seleccione una factura..."
  })
  
  $.ajax({
	method: "post",
    url: "php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_invoiceDetailTable",
      value:$("#txtIdFactura").val()
    },
    dataType: 'json',
    success: function(response){
      html = "";
      
      $.each(response.data,function(i){
        html += '<tr>'+
                  '<td>' + response.data[i].clave + '</td>'+
                  '<td style="text-align: center;">' + response.data[i].descripcion + '</td>'+
                  '<td style="text-align: center;">' + response.data[i].unidad_medida + '</td>'+
                  '<td style="text-align: center;">' + response.data[i].cantidad + '</td>'+
                  '<td style="text-align: center;">' + response.data[i].precio + '</td>'+
                  '<td style="text-align: center;">' + response.data[i].importe + '</td>'+
                '</tr>';
      });

      $("#tblDetalleFactura tbody").append(html);
      $("#tblDetalleFactura").dataTable({
        info: false,
        scrollX: true,
        bSort: false,
        pageLength: 15,
        responsive: true,
        lengthChange: false,
        dom: `<"container-fluid"<"row mb-3"<"col-sm-12 col-md-9 p-0 d-flex align-items-center"B><"col-sm-12 col-md-3 p-0"f>>>rti
        <"container-fluid mt-4"<"row"<"#btn-filters.col-sm-12 col-md-10 p-0"><"col-sm-12 col-md-2 p-0"p>>>`,
        "columns": [{
            "data": "Clave"
          },
          {
            "data": "descripcion"
          },
          {
            "data": "Unidad"
          },
          {
            "data": "cantidad"
          },
          {
            "data": "precio"
          },
          {
            "data": "Importe"
          }
        ],
        responsive: true
      });
    },
    error:function(error){
      console.log(error);
    }
  });

  $.ajax({
	  method: "post",
    url: "php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_invoiceDetail",
      value:$("#txtIdFactura").val()
    },
    dataType: 'json',
    success: function(response){
      //res = JSON.parse(response);
      expired_date = response.fecha_vencimiento;
      $("#serie_folio").text(response.serie_folio);
      $("#fecha_timbrado").text(response.fecha_timbrado);
      $("#razon_social").append(response.razon_social);
      $("#rfc").text(" "+response.rfc);
      $("#estatus").text("Estatus: "+response.estatus);
      $("#subtotales #subtotal").append(" " + response.subtotal);
      $("#vendedor").text(response.vendedor);
      $("#referencia").html(response.referencia);
      $("#fecha_vencimiento").html(expired_date);
      $("#txtClient_id").val(response.cliente_id);
      $("#enable_edit_expiration_date").html('<div class="form-check" style="display:inline-block;font-size:16px" float-left>'+
        '<input style="display:inline-block class="form-check-input" type="checkbox" value="" id="chkFechaVencimiento">  '+
        '<label class="form-check-label" for="chkFechaVencimiento">Editar</label>'+
        '<div id="editar_fecha_vencimiento_habilitar" style="display:inline-block"></div>'+
      '</div>');
      $("#enable_edit_seller").html('<div class="form-check" style="display:inline-block;font-size:16px" float-left>'+
        '<input style="display:inline-block class="form-check-input" type="checkbox" value="" id="chkVendedor">  '+
        '<label class="form-check-label" for="chkVendedor">Editar</label>'+
        '<div id="editar_vendedor_habilitar" style="display:inline-block"></div>'+
      '</div>');

      
      $("#estado").text(response.estado);

      if(response.fecha_cancelacion !== null && response.fecha_cancelacion !== "" && response.fecha_cancelacion !== "null"){
        $("#fech_canc").css("display","block");
        $("#fecha_cancelacion").css("display","block");
        $("#fecha_cancelacion").text(response.fecha_cancelacion);
      }
      
      $("#subtotal").html(response.subtotal);
      $("#impuestos").html(response.impuestos);
      $("#total").html('<b>'+response.total+'</b>');
      $("#totalFactura").text(response.total);

      btn_com_pago = '<a href="#" id="com_pago" class="btn-table-custom btn-table-custom--turquoise float-right"><i class="fas fa-receipt"></i> Aplicar complemento de pago</a>';
      btn_pago = '<a href="#" id="pago" class="btn-table-custom btn-table-custom--turquoise float-right"><img style="width:1.5rem; vertical-align: top;" src="../../img/facturacion/aplicar_pago.svg"> Aplicar Pago</a>';
      btn_nota_credito = ' <a href="#" id="nota_credito" class="btn-table-custom btn-table-custom--turquoise float-right"><img style="width:1.5rem; vertical-align: top;" src="../../img/cuentas_cobrar/aplicar_nota_credito.svg"> Aplicar Nota de crédito</a>';
      if(response.id_estatus === 1){
        $("#btnModalCancelacion").css("display","inline-block");
        $(".btn-downloads").append(btn_pago);
      }

      if(response.id_estatus === 3){
        if(parseInt(response.metodo_pago) === 3){
          $(".btn-downloads").append(btn_com_pago);
          $(".btn-downloads").append(btn_nota_credito);
        } else {
          $(".btn-downloads").append(btn_nota_credito);
        }
        
      }
      
      $("#chkFechaVencimiento").on("click",function(){
        
        if($(this).is(":checked")){
          $("#fecha_vencimiento").html('<input style="display:inline-block" class="form-control col-lg-4" type="date" value="'+response.fecha_vencimiento+'" id="txtFechaVencimiento">');
          $("#editar_fecha_vencimiento_habilitar").html('<a style="display:inline-block;font-size:16px" class="btn-table-custom--blue mt-auto p-2" href="#" id="btn_editarFechaVencimiento"><i class="fas fa-plus-square"></i> Guardar</a>');
          
        } else {
          console.log("no checked");
          $("#fecha_vencimiento").html(expired_date);
          $("#editar_fecha_vencimiento_habilitar").html('');
          
        }
        
      });

      $("#chkVendedor").on("click",function(){
        if($(this).is(":checked")){
          $("#vendedor").html('<select class="col-lg-8" style="display:inline-block;font-size: 1rem;" id="cmbVendedor"><select>')
          $("#editar_vendedor_habilitar").html('<a style="display:inline-block;font-size:16px" class="btn-table-custom--blue mt-auto p-2" href="#" id="btn_editarVendedor"><i class="fas fa-plus-square"></i> Guardar</a>');
          new SlimSelect({
            select: "#cmbVendedor",
            placeholder: "Seleccione un vendedor..."
          })
          loadCombo1("vendedores","#cmbVendedor",response.vendedor_id,"Seleccione un vendedor...","");
        } else {
          $("#vendedor").html(response.vendedor);
          $("#editar_vendedor_habilitar").html('');
        }
      })


      $(document).on("click","#btn_editarFechaVencimiento",function(){
        expired_date_update = $("#txtFechaVencimiento").val();
        $.ajax({
          method: "post",
          url: "php/funciones.php",
          data: {
            clase: "edit_data",
            funcion: "update_expiredDate",
            value:$("#txtIdFactura").val(),
            expired_date: expired_date_update
          },
          dataType: 'json',
          success: function(response){
            
            $("#chkFechaVencimiento").prop("checked",false);
            $("#fecha_vencimiento").html(moment(expired_date_update).format("DD-MM-YYYY"));
            $("#editar_fecha_vencimiento_habilitar").html('');
          },
          error:function(error){
            console.log(error);
          }
        })
      });

      $(document).on("click","#btn_editarVendedor",function(){
        seller = $("#cmbVendedor option:selected").text();
        seller_id = $("#cmbVendedor").val();
        $.ajax({
          method: "post",
          url: "php/funciones.php",
          data: {
            clase: "edit_data",
            funcion: "update_seller",
            value:$("#txtIdFactura").val(),
            seller: seller_id
          },
          dataType: 'json',
          success: function(response){
            $("#chkVendedor").prop("checked",false);
            $("#vendedor").html(seller);
            $("#editar_vendedor_habilitar").html('');
          },
          error:function(error){
            console.log(error);
          }
        });
      });
      

    },
    error:function(error){
      console.log(error);
    }
  });
  
});

$(document).on("click","#btnModalCancelacion",function(){

  $.ajax({
    method: "post",
    url: "php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_creditNote",
      value:$("#txtIdFactura").val()
    },
    dataType: 'json',
    success: function(response){
      res = JSON.parse(response);
      if(parseInt(res) > 0){
        //alert("No se puede cancelar porque se ha generado una nota de crédito para esta factura");
        
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/warning_circle.svg",
          msg: "No se puede cancelar porque se ha generado una nota de crédito para esta factura"
        });

      } else {
        $('#modalCancelacion').modal('toggle');
      }
    },
    error:function(error){
      console.log(error);
    }
  });
  
});

function setFormatDatatables(){
  return {
    "sProcessing": "Procesando...",
    "sZeroRecords": "No se encontraron resultados",
    "sEmptyTable": "Ningún dato disponible en esta tabla",
    "sSearch": "<img src='../../img/timdesk/buscar.svg' width='20px' />",
    "sLoadingRecords": "Cargando...",
    "searchPlaceholder": "Buscar...",
    "oPaginate": {
      "sFirst": "Primero",
      "sLast": "Último",
      "sNext": "<img src='../../img/icons/pagination.svg' width='20px'/>",
      "sPrevious": "<img src='../../img/icons/pagination.svg' width='20px' style='transform: scaleX(-1)'/>"
    },
  }
}
var nDestinos = 0;


$(document).on("click","#agregar_destinatarios",function(){
  nDestinos++;
  $("#eliminar_destinatarios").css("display","block");
  
  html = '<div class="form-group" id="form-group-'+nDestinos+'">'+
            '<label for="txtDestino'+nDestinos+'">Destinatario '+nDestinos+':</label>'+
            '<input type="text" class="form-control" name="txtDestino" id="txtDestino'+nDestinos+'">'+
          '</div>';
  if(nDestinos === 10){
    $("#agregar_destinatarios").css("display","none");
  }
  $("#enviarDestinatarios").append(html);
  
  
});

$(document).on("click","#eliminar_destinatarios",function(){
  $("#form-group-"+nDestinos).remove();
  $("#agregar_destinatarios").css("display","block");
  nDestinos--;
  if(nDestinos === 0){
    $("#eliminar_destinatarios").css("display","none");
  }
});

$(document).on("click","#btnEnviarFactura",function(){

  if($("#formEnviarEmail")[0].checkValidity()){
    
    var badDestinatario =
    $("#invalid-destino").css("display") === "block" ? false : true;

    if(badDestinatario){
      var a = $("input[type=text][name=txtDestino]");
      var arr = Array(
        $("#txtDestino").val()
      );
      console.log("no falta nada");
      if(a.length > 1){
        for (let index = 1; index < a.length; index++) {
          var value = $("#txtDestino"+index).val()
          if(value !== "")
          arr.push(value);
        }
      }

      $.ajax({
		method: "post",
        url: "php/funciones.php",
        data: {
          clase: "send_data",
          funcion: "send_email",
          value:$("#txtIdFactura").val(),
          destinos:arr
        },
        dataType: 'json',
        success: function(response){
          console.log(response.ok);
          if(response.ok){
            Lobibox.notify("success", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../img/timdesk/checkmark.svg",
              msg: "Se ha envíado el archivo pdf y el xml",
            });
            $("#enviarFactura").modal("toggle");
            
          }
          
        },
        error:function(error){
          console.log(error);
        }
      });
    } 
  }else {
    if (!$("#txtDestino").val()) {
      $("#invalid-destino").css("display", "block");
      $("#txtDestino").addClass("is-invalid");
    }
  }
});

$('#enviarFactura').on('hidden.bs.modal', function () {
  var a = $("input[type=text][name=txtDestino]");
  if(a.length > 1){
    for (let index = 1; index < a.length; index++) {
      $("#txtDestino").val("");
      $("#form-group-"+index).remove();
    }
  }
});

$('#modalCancelacion').on('hidden.bs.modal', function () {
  $("#txtDestinoCancel").val("");
  $("#cmbMotivoCancelacion").val("");
});


$(document).on("click","#cancelar_factura",function(){
      
  if($("#dataCancelacion")[0].checkValidity()){
    var badDestinatario =
      $("#invalid-destinoCancel").css("display") === "block" ? false : true;
    var badMotivo = 
      $("#invalid-motivoCancelacion").css("display") === "block" ? false : true;

    if(badDestinatario && badMotivo){

      $("#loader").addClass("loader");
      $.ajax({
        method:"post",
        url: "php/funciones.php",
        data: {
          clase: "get_data",
          funcion: "get_cancelInvoice",
          value:$("#txtIdFactura").val(),
          email:$("#txtDestinoCancel").val(),
          motivo:$("#cmbMotivoCancelacion").val(),
          factura_relacion:$("#cmbRelationInvoice").val()
        },
        dataType: 'json',
        success: function(response){
          $("#cancelar_factura").prop('disabled',true);
          $("#btnCancelarActualizacion").prop('disabled',true);
          $(".loader").fadeOut("slow");
          $("#loader").removeClass("loader");
          if(response === 1){
            Lobibox.notify("success", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../img/timdesk/checkmark.svg",
              msg: "Se ha cancelado con éxito",
            });
            $("#modalCancelacion").modal("toggle");
            $("#btnModalCancelacion").css("display","none");
            $("#estatus").html("Estatus: Proceso de cancelación");
            cancelDocumentsInvoice($("#txtIdFactura").val());
          } else if(response === 2){
            Lobibox.notify("success", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../img/timdesk/checkmark.svg",
              msg: "En proceso de cancelación.",
            });
            $("#modalCancelacion").modal("toggle");
            $("#btnModalCancelacion").css("display","none");
            $("#estatus").html("Estatus: Proceso de cancelación");
            $("#cancelar_factura").prop('disabled',false);
            $("#btnCancelarActualizacion").prop('disabled',false);
            cancelDocumentsInvoice($("#txtIdFactura").val());
          } else {
            Lobibox.notify("error", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../img/timdesk/warning_circle.svg",
              msg: response,
            });
          }

        },
          error:function(error){
            console.log(error);
          }
      });
    }
  } else {
    if (!$("#txtDestinoCancel").val()) {
      $("#invalid-destinoCancel").css("display", "block");
      $("#txtDestinoCancel").addClass("is-invalid");
    }
    if (!$("#cmbMotivoCancelacion").val()) {
      $("#invalid-motivoCancelacion").css("display", "block");
      $("#cmbMotivoCancelacion").addClass("is-invalid");
    }
  }
      
});

$(document).on("change","#txtDestinoCancel",function(){
  if($(this).hasClass("is-invalid")){
    $("#invalid-destinoCancel").css("display", "none");
    $("#txtDestinoCancel").removeClass("is-invalid");
  }
});

$(document).on("change","#cmbMotivoCancelacion",function(){
  data = $(this).val();
  if($(this).hasClass("is-invalid")){
    $("#invalid-motivoCancelacion").css("display", "none");
    $("#cmbMotivoCancelacion").removeClass("is-invalid");
  }

  if(data == "01"){
    
    $("#relation-uuid").css("display","block");
   
    $.ajax({
      url: "php/funciones.php",
      data: {
        clase: "get_data",
        funcion: "get_invoicesRelations",
        value:$("#txtClient_id").val(),
      },
      dataType: 'json',
      success: function(response){
        
        var res = response;
        
        html1 = "<option data-placeholder='true'></option>";
        if(res.length > 0){
          $.each(res,function(i){ 
            html1 += "<option value='"+res[i].id+"'>"+res[i].data+"</option>";
          });
        } else {
          html1 += "<option value='0'>No hay registros.</option>";
        }
        
        $("#cmbRelationInvoice").html(html1);
      },
      error:function(error){
        console.log(error);
      }

    });

  } else {
    $("#relation-uuid").css("display","none");
  }
});

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

$(document).on("click","#pago",function(){
  idFactura= $('#txtIdFactura').val();
  idCliente= $('#txtClient_id').val();

  url = "../recepcion_pagos/pagos.php";
  
  $().redirect(url, {
    idFactura: idFactura,
    idCliente: idCliente
  });
});

$(document).on("click","#nota_credito",function(){
  idFactura= $('#txtIdFactura').val();
  idCliente= $('#txtClient_id').val();

  url = "../notas_credito/agregar.php";
  
  $().redirect(url, {
    idFactura: idFactura,
    idCliente: idCliente
  });
});

$(document).on("click","#com_pago",function(){
  idFactura= $('#txtIdFactura').val();

  $.ajax({
    url: "php/funciones.php",
    method: "post",
    data: {
      clase: "get_data",
      funcion: "get_com_pago",
      value: idFactura
    },
    dataType: 'json',
    success: function(){
      
    },
    error:function(error){
      console.log(error);
    }
  });
});

function loadCombo1(funcion,input,data,texto,value){
  $.ajax({
    method: "POST",
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
      html = "<option data-placeholder='true'></option>";
      if(res.length > 0){
        $.each(res,function(i){
          if(res[i].id === parseInt(data)){
            
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

function cancelDocumentsInvoice(value)
{
    $.ajax({
        method: "POST",
        url: "php/funciones.php",
        method:"POST",
        data: {
          clase: "delete_data",
          funcion: "delete_invoiceDataDoc",
          value: value
        },
        datatype: "json",
        success: function(respuesta){
            console.log("éxito");
    },
        error: function(error){
          console.log(error);
        }
    });

    
}

$("#enviarFactura").on("show.bs.modal",()=>{
    var id = $("#txtIdFactura").val();
    $.ajax({
        method: "POST",
        url: "php/funciones.php",
        method:"POST",
        data: {
          clase: "get_data",
          funcion: "get_clientEmail",
          value: id
        },
        datatype: "json",
        success: function(respuesta){
            r = JSON.parse(respuesta);
            $("#txtDestino").val(r);
        }
    })
})