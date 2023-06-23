optionSalidas = [];
optionPedidos = [];

$(document).on("click",function(event){
  if(!$(event.target).closest(".select-timlid").length){
    $(".select-arrow>span").addClass("select-arrow-down");
    $(".select-arrow>span").removeClass("select-arrow-up");
    $(".select-content").removeClass("select-open");
  } 

});

$(document).on("click","#select-pedidos .select-body",function(){
  
  if($("#select-pedidos .select-arrow>span").hasClass("select-arrow-down")){
    $("#select-pedidos .select-arrow>span").addClass("select-arrow-up");
    $("#select-pedidos .select-arrow>span").removeClass("select-arrow-down");
    $("#select-pedidos .select-content").addClass("select-open");
    $("#select-salidas .select-arrow>span").addClass("select-arrow-down");
    $("#select-salidas .select-arrow>span").removeClass("select-arrow-up");
    $("#select-salidas .select-content").removeClass("select-open");
    if($("#cmbPedido .select-list>div").length === 0){
      console.log("lenght:",$("#cmbPedido .select-list").length);
      $("#cmbPedido .select-list").html("<div class='select-option'><span>No se encontraron pedidos </span>");
    }
  } else {
    $(".select-arrow>span").addClass("select-arrow-down");
    $(".select-arrow>span").removeClass("select-arrow-up");
    $(".select-content").removeClass("select-open");
  }
  
});

$(document).on("click","#select-salidas .select-body",function(){
  
  if($("#select-salidas .select-arrow>span").hasClass("select-arrow-down")){
    $("#select-salidas .select-arrow>span").addClass("select-arrow-up");
    $("#select-salidas .select-arrow>span").removeClass("select-arrow-down");
    $("#select-salidas .select-content").addClass("select-open");
    $("#select-pedidos .select-arrow>span").addClass("select-arrow-down");
    $("#select-pedidos .select-arrow>span").removeClass("select-arrow-up");
    $("#select-pedidos .select-content").removeClass("select-open");
  } else {
    $(".select-arrow>span").addClass("select-arrow-down");
    $(".select-arrow>span").removeClass("select-arrow-up");
    $(".select-content").removeClass("select-open");
  }
  
});

$(document).on("click","#cmbSalida .select-option",function(){
  var data = $(this);
  var data1 = $(".select-option>img");
  var element = $("#" + data.attr("id") + " img");
  
  if(element.hasClass('check-select-option-disabled'))
  {
    element.addClass("check-select-option-enabled");
    element.removeClass("check-select-option-disabled");
    optionSalidas.push(data.data("id"));

    $("#cmbSalida .placeholder span").remove(".select-disabled");
    $("#cmbSalida .placeholder").append("<span id='option-select-placeholder-salida-"+data.data("id")+"' class='placeholder-text-select'>" + data.text() + "<img class='option-discart' data-id='"+data.data("id")+"' src='../../img/letra-x.svg'></span>");

  } else {
    element.addClass("check-select-option-disabled");
    element.removeClass("check-select-option-enabled");
    $("#option-select-placeholder-salida-"+data.data("id")).remove();
    if($("#cmbSalida .placeholder span").length === 0){
      a = '<span class="select-disabled">Seleccione una salida...</span>';
      $("#cmbSalida .placeholder span").remove("span");
      $("#cmbSalida .placeholder").append(a);
    }
    optionSalidas.splice($.inArray(data.data("id"),optionSalidas),1);
  }
});

$(document).on("click","#cmbPedido .select-option",function(){
  var data = $(this);
  
  var element = $("#" + data.attr("id") + " img");
  
  if(element.hasClass('check-select-option-disabled'))
  {
    element.addClass("check-select-option-enabled");
    element.removeClass("check-select-option-disabled");
    optionPedidos.push(data.data("id"));

    $("#cmbPedido .placeholder span").remove(".select-disabled");
    $("#cmbPedido .placeholder").append("<span id='option-select-placeholder-"+data.data("id")+"' class='placeholder-text-select'>" + data.text() + "<img class='option-discart' data-id='"+data.data("id")+"' src='../../img/letra-x.svg'></span>");

  } else {
    element.addClass("check-select-option-disabled");
    element.removeClass("check-select-option-enabled");

    $("#option-select-placeholder-"+data.data("id")).remove();
    if($("#cmbPedido .placeholder span").length === 0){
      a = '<span class="select-disabled">Seleccione una salida...</span>';
      $("#cmbPedido .placeholder span").remove("span");
      $("#cmbPedido .placeholder").append(a);
    }
    optionPedidos.splice($.inArray(data.data("id"),optionPedidos),1);
  }
  
  
  $.ajax({
    method: "POST",
    url: 'php/funciones.php',
    data: {
      clase: 'get_data',
      funcion: 'get_salidas',
      value: data.data("id")
    },
    datatype: "json",
    success: function(respuesta){
      $("#select-salidas").css("display","flex");
      $("#btnCargarSalidas").css("display","flex");
      res1 = JSON.parse(respuesta);
      if(res1.length > 0){
        $.each(res1,function(i){
          if($("#cmbSalida #pedido-salida-"+res1[i].texto).length === 0){
            $("#cmbSalida .select-list").append("<div class='select-option remove-option-discart-"+data.data("id")+"' id='pedido-salida-"+res1[i].texto+"' data-id='"+res1[i].texto+"' data-delete='"+data.data("id")+"'><span>"+res1[i].texto+"</span><img class='check-select-option-disabled' data-id='"+res1[i].texto+"' src='../../img/timdesk/ICONO ESTADO-TERMINADO_Mesa de trabajo 1.svg'></div>");
            
          } else {
            $("#cmbSalida #pedido-salida-"+res1[i].texto).remove();
           
          }
        });
      } else {
        $("#cmbSalida .select-list").html("<div class='select-option'><span>No se encontraron salidas.</span></div>");
      }
    },
    error: function(error){
      console.log(error);
    }
  });
  
});

$(document).on("click","#cmbPedido .option-discart",function(){
  
  var id = $(this).data("id");
  
  $("#option-select-placeholder-"+id).remove();
  $("#pedido_"+id+" img").addClass("check-select-option-disabled");
  $("#pedido_"+id+" img").removeClass("check-select-option-enabled");

  $("#cmbSalida .remove-option-discart-"+id).remove();
  
  /*if($("#cmbSalida .select-list .select-option").length === 0){
    $("#select-salidas").css("display","none");
  }*/
  if($("#cmbSalida .placeholder span").length === 0){
    a = '<span class="select-disabled">Seleccione una salida...</span>';
    $("#cmbSalida .placeholder span").remove("span");
    $("#cmbSalida .placeholder").append(a);
  }
    
  

  if($("#cmbPedido .placeholder span").length === 0){
    a = '<span class="select-disabled">Seleccione un pedido...</span>';
    $("#cmbPedido .placeholder span").remove("span");
    $("#cmbPedido .placeholder").append(a);
  }
});

$(document).on("click","#cmbSalida .option-discart",function(){
  var id = $(this).data("id");
  
  $("#option-select-placeholder-salida-"+id).remove();
  $("#pedido-salida-"+id+" img").addClass("check-select-option-disabled");
  $("#pedido-salida-"+id+" img").removeClass("check-select-option-enabled");

  if($("#cmbSalida .placeholder span").length === 0){
    a = '<span class="select-disabled">Seleccione una salida...</span>';
    $("#cmbSalida .placeholder span").remove("span");
    $("#cmbSalida .placeholder").append(a);
  }
  optionSalidas.splice($.inArray(id,optionSalidas),1);
});