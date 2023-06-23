$(document).ready(function(){
  new SlimSelect({
    select: '#cmbCliente',
    placeholder: 'Seleccione un cliente...'
  });
  new SlimSelect({
    select: '#cmbImpuesto',
    placeholder: 'Seleccione un impuesto...'
  });
  loadCombo1("cliente","#cmbCliente","","Seleccione un cliente...");
});

$(document).on("change", "#cmbCliente", function(){
  $("#cmbPedido .placeholder span").remove("span");
  $("#cmbPedido .placeholder").append('<span class="select-disabled">Seleccione un pedido...</span>');
  $("#cmbPedido .select-option").remove();
  $("#cmbSalida .placeholder span").remove("span");
  $("#cmbSalida .placeholder").append('<span class="select-disabled">Seleccione una salida...</span>');
  $("#cmbSalida .select-option").remove();
  $(".productos-cliente").css('display','none');
  truncateTablePRoducts();
  optionSalidas = [];
  optionPedidos = [];
  if($(this).val().trim() !== "0"){
    $.ajax({
      url: 'php/funciones.php',
      method: "POST",
      data: {
        clase: 'get_data',
        funcion: 'get_ordenesPedido',
        value: $(this).val()
      },
      datatype: "json",
      success: function(respuesta){
        res = JSON.parse(respuesta);
        html = "";
        if(res.length > 0){
          $.each(res,function(i){
            $("#cmbPedido .select-list").append("<div class='select-option' id='pedido_"+res[i].id+"' data-id='"+res[i].id+"'><span>"+res[i].texto+"</span><img class='check-select-option-disabled' data-id='"+res[i].id+"' src='../../img/timdesk/ICONO ESTADO-TERMINADO_Mesa de trabajo 1.svg'></div>");
          });
          //$("#select-salidas").css("display","flex");
        } else {
          $("#cmbPedido .select-list").html("<div class='select-option'><span>No se encontraron pedidos </span></div>");
        }
       
      },
      error: function(error){
        console.log(error);
      }
    });
  }
});

function loadCombo1(funcion,input,data,texto,value){
  $.ajax({
    url: "php/funciones.php",
    method: "POST",
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
     //html = null;
      
    },
    error: function(error){
      console.log(error);
    }
  });
}

$(document).on("click","#cargarProductosPedidos",function(){
  var value = JSON.stringify(optionSalidas);

  /*$.ajax({
    url: "php/funciones.php",
    method: "POST",
    data: {
      clase: 'get_data',
      funcion: 'get_productosSalidas',
      value: value
    },
    dataType: 'json',
    success: function(respuesta){*/

      $.ajax({
        url: 'php/funciones.php',
        method: "POST",
        data: {
          clase: 'get_data',
          funcion: 'get_ordenPedido',
          value: $("#cmbCliente").val()
        },
        datatype: "json",
        success: function(respuesta){
          var res1 = JSON.parse(respuesta);

          if($("#select-salidas .select-arrow>span").hasClass("select-arrow-up")){
            $("#select-salidas .select-arrow>span").addClass("select-arrow-down");
            $("#select-salidas .select-arrow>span").removeClass("select-arrow-up");
            $("#select-salidas .select-content").removeClass("select-open");
            
            /*$("#select-pedidos .select-arrow>span").addClass("select-arrow-down");
            $("#select-pedidos .select-arrow>span").removeClass("select-arrow-up");
            $("#select-pedidos .select-content").removeClass("select-open");*/
          }
          var ref = "";
          for (let index = 0; index < optionSalidas.length; index++) {
            ref += optionSalidas + "<br>";
          }
          
          $('.cabecera-cliente').css("display","block");
          $('.cuerpo').css("display","block");
          $("#nofacturar").html("Referencia:<br>"+ref);
          $("#razon_social").html("Razon social:<br>"+res1[0].razon_social);
          $("#rfc").html("RFC:<br>"+res1[0].rfc);
          $("#totalFacturar").css("display","none");

          $.ajax({
            url: "php/funciones.php",
            method: "POST",
            data: {
              clase: 'get_data',
              funcion: 'get_totalSubtotalSalidas',
            },
            dataType: 'json',
            success: function(respuesta){
              var res = respuesta;
              $('#subtotal').html("$ " + res.subtotal);
              $('#impuestos').html(res.impuestos);
              $('#total').html("$ " + res.total);
              $('.subtotal').css("display","block");
              $('.impuestos').css("display","block");
              $('.total').css("display","block");
              
            },error: function(error){
              console.log(error);
            }
        
          });

        },
        error: function(error){
          console.log(error);
        }
      
      });
      
      /*
      if($(".select-option img").hasClass("check-select-option-enabled")){
        $(".select-option img").addClass("check-select-option-disabled");
        $(".select-option img").removeClass("check-select-option-enabled");
      }*/

      /*a = '<span class="select-disabled">Seleccione una salida...</span>';
      $("#cmbSalida .placeholder span").remove("span");
      $("#cmbSalida .placeholder").append(a);*/

      $('.cabecera-cliente').css("display","block");
      $('.cuerpo').css("display","block");
      $("#nofacturar").html("Referencia:<br>"+res[0].referencia);
      $("#razon_social").html("Razon social:<br>"+res[0].razon_social);
      $("#totalFacturar").html("Total: "+res[0].total);
      $("#rfc").html("RFC:<br>"+res[0].rfc);

      var idTable = "#tblDetalleProductos";
      var func = "get_productosSalidas";
      
      var rowCountProduct = $(idTable+">tbody>tr");
      data = JSON.stringify(optionSalidas);

      if(rowCountProduct.length > 0){
        $(idTable).DataTable().clear().destroy();
        loadDataTableProducts(idTable,func,data);
        
      } else {
        loadDataTableProducts(idTable,func,data);
        
      }

      //optionSalidas = new Array();

    /*},error:function(error){
      console.log(error);
    }*/
  //});
});

$(document).on('click','#tblDetalleProductos a',function(){
  var id = $(this).data('id');
  var ref = $(this).data('ref');
  
  $("#txtIdReferencia").val(ref);
  
  var tipo_doc = "";
  var doc = "";
  
  const tblProductos = $("#tblDetalleProductos").DataTable();
  var data = tblProductos.rows(id).data();
  var indexTableProd = tblProductos.row(id).index();
  $("#rowIndex").val(indexTableProd);
  var idProducto = id;
  $("#txtIdProd").val(idProducto);

  validateSAT(idProducto);
  validateUnidadMedida(idProducto);
  loadDataProducto(doc,idProducto,tipo_doc);
});

function loadDataTableProducts(id,func,value,type_doc){
  $('.productos-cliente').css("display","table");
  const productos = $(id).DataTable({
    "language": setFormatDatatables(),
    "dom":"lrti",
    "scrollX": true,
    "scrollCollapse": false,
    "lengthChange": false,
    "info": false,
    "bSort": false,
    "paging": false,
    "ajax":{
      url: "php/funciones.php",
      method: "POST",
      data: {
        clase: "get_data",
        funcion: func,
        value: value,
        tipo_doc: type_doc
      },
    },
    "columns":[
      {"data":"id"},
      {"data":"edit"},
      {"data":"clave"},
      {"data":"descripcion"},
      {"data":"sat_id"},
      {"data":"id_unidad_medida"},
      {"data":"unidad_medida"},
      {"data":"cantidad"},
      {"data":"precio"},
      {"data":"subtotal"},
      {"data":"impuestos"},
      {"data":"descuento"},
      {"data":"importe_total"},
      {"data":"alerta"}
    ],

    "columnDefs": [
      {
        "targets":[0,4,5],
        "visible": false,
        "searchable": false
      }
    ],
  });
}

function loadDataTableTax(id,func,value,product,type_doc,id_row){
  $(id).css('display','table');
  const table = $(id).DataTable({
    "language": setFormatDatatables(),
    "dom":"lrti",
    "scrollX": false,
    "scrollCollapse": false,
    "lengthChange": false,
    "info": false,
    "bSort": false,
    "ajax":{
      url: "php/funciones.php",
      method: "POST",
      data: { 
        clase: "get_data", 
        funcion: func,
        value: value,
        producto: product,
        tipo_doc: type_doc,
        id: id_row
      },
    },

    "columns":[
      {"data":"id"},
      {"data":"tipo"},
      {"data":"nombre"},
      {"data":"tasa"},
      {"data":"delete"}
    ],

    "columnDefs": [
      {
        "targets":[0,1],
        "visible": false,
        "searchable": false
      },
      {
        "targets":[1],
        "width": "100%"
      },
      
    ],
  });
  return table;
}

function setFormatDatatables(){
  var idioma_espanol = {
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
  return idioma_espanol;
}

function loadDataProducto(document,idProducto,tipo_doc){
  $.ajax({
    url: 'php/funciones.php',
    method: "POST",
    data: {
      clase: 'get_data',
      funcion: 'get_dataProduct',
      id_row: $("#txtIdReferencia").val()
    },
    datatype: "json",
    success: function(respuesta){
      var res = JSON.parse(respuesta);
      
      $("#editarProducto").modal('show');
      var claveSat = (res['clave_sat'] !== "" && res['clave_sat'] !== null && res['clave_sat'] !== 1) ? res['clave_sat'] : "";
      //$("#txtClaveSatId").val(res['clave_sat']);
      $("#txtClave").val(res['clave']);
      $("#txtDescripcion").val(res['nombre']);
      var idUnidadMedida = (res['unidad_medida'] !== "" && res['unidad_medida'] !== null && res['unidad_medida'] !== 1) ? res['unidad_medida'] : "";

      var cantidad = parseInt(res['cantidad']);
      var precioUnitario = parseFloat(res['precio_unitario']);
      var importe = cantidad * precioUnitario;

      if(idUnidadMedida !== ""){
        $("#txtUnidadMedida").val(res['unidad_medida_texto']);
        $("#txtUnidadMedidaId").val(idUnidadMedida);
      } else {
        $("#txtUnidadMedida").val("Clic para asignar una unidad de medida");
      }

      if(claveSat !== ""){
        $("#txtClaveSat").val(res['clave_sat']);
      } else {
        $("#txtClaveSat").val("Clic para asignar clave SAT");
      }

      $("#txtCantidad").val(res['cantidad']);
      $("#txtPrecioUnitario").val(res['precio_unitario']);

      $("#txtCantidad").on("keyup",function(){
        if($(this).val() > res['limite_cantidad']){
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3100,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/warning_circle.svg",
            msg: "La cantidad no puede ser mayor a la descripta en la tabla",
          });
          
          $("#txtCantidad").val(res['cantidad']);
        }
        if($(this).val() < 0){
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3100,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/warning_circle.svg",
            msg: "La cantidad no puede ser menor a cero",
          });
          
          $("#txtCantidad").val(res['cantidad']);
        }
      });

      $('#tipoDescuento'+res['tipo_descuento']).attr("checked", true);
      
      $("#txtDescuento").val(res['descuento']);
      $('input[type=radio][name=tipoDescuento]').on("click",function(){
        if($(this).val() === "1"){
          $("#txtDescuento").val(numeral(res['descuento']).format("0"));
        } else {
          $("#txtDescuento").val(numeral(res['descuento']).format("0,000,000.00"));
        }
      });
      var id_row = $("#txtIdReferencia").val();
      var idTable ="#tblDetalleImpuestosModal";
      var func = "get_impuestoTable";
      var tipo_doc = "";
      
      var rowCountTax = $("#tblDetalleImpuestosModal>tbody>tr");
      
      if(rowCountTax.length > 0){
        $(idTable).DataTable().clear().destroy();
        loadDataTableTax(idTable,func,document,idProducto,tipo_doc,id_row);
      } else {
        loadDataTableTax(idTable,func,document,idProducto,tipo_doc,id_row);
      }
      

      loadCombo1("impuestos","#cmbImpuesto","","Seleccione un impuesto...","1");
      $("#txtLabelTax").html("Tasa:");
      $("#txtTax").val("0");

      
    },
    error: function(error){
      console.log(error);
    }

  });
}

$(document).on("click",'input[type=radio][name=tipoImpuesto]',function(){
  tipoImpuestos = $(this).val();
  
  loadCombo1("impuestos","#cmbImpuesto","","Seleccione un impuesto...",tipoImpuestos);
    
});


$(document).on("click","#agregarFactura",function(){
  $("#agregarFactura").prop('disabled',true);
  pedidos = JSON.stringify(optionPedidos);
  salidas = JSON.stringify(optionSalidas);
  cliente = $("#cmbCliente").val();
  $("#loader").addClass("loader");
  
  $.ajax({
    url: "php/funciones.php",
    method: "POST",
    data: {
      clase: "save_data",
      funcion: "save_remision",
      value: cliente,
      pedidos: pedidos,
      salidas: salidas
    },
    success: function(respuesta){
      res = JSON.parse(respuesta);
      $(".loader").fadeOut("slow");
      $("#loader").removeClass("loader");
      if(res === true){
        $("#agregarFactura").prop('disabled',true);
        Lobibox.notify("success", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/checkmark.svg",
          msg: "Los datos se han guardado correctamente",
          sound:false,
        });
        optionSalidas = new Array();
        optionPedidos = new Array();
        setTimeout(function() {
          window.location.href = 'index.php';
        }, 1000);
          
      } else {
        $("#agregarFactura").prop('disabled',false);
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3100,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/warning_circle.svg",
          msg: "Los datos no se pudieron guardar",
        })
      }
    },
    error: function(error){
      console.log(error);
    }

  });
  
});

$(document).on('click','#btnAgregarImpuesto',function(){
  
  var tableTax = $('#tblDetalleImpuestosModal').DataTable();
  var counRows = tableTax.rows().count();
  
  if($("#cmbImpuesto").val() !== "" && $("#txtTax").val() !== 0){
    data1 = tableTax.rows().data();
    
    let ban = 0;
    let ivaExento = 0;
    let ivaBan = 0;
    let isrExento = 0;
    let isrBan = 0;
    let iepsExento = 0;
    let iepsBan = 0;
    
    if(counRows > 0){
      for (let i = 0; i < data1.length; i++) {
        if(data1[i]['id'] === $("#cmbImpuesto").val()){
          ban++;
        }
        if(data1[i]['id'] === "5" && ($("#cmbImpuesto").val() === "1" || $("#cmbImpuesto").val() === "6")){
          ivaBan++;
        }
        if($("#cmbImpuesto").val() === "5" && (data1[i]['id'] === "1" || data1[i]['id'] === "6")){
          ivaExento++;
        }
        if(data1[i]['id'] === "13" && ($("#cmbImpuesto").val() === "7" || $("#cmbImpuesto").val() === "15" || $("#cmbImpuesto").val() === "14" || $("#cmbImpuesto").val() === "17")){
          isrBan++;
        }
        if($("#cmbImpuesto").val() === "13" && (data1[i]['id'] === "7" || data1[i]['id'] === "15" || data1[i]['id'] === "14" || data1[i]['id'] === "17")){
          isrExento++;
        }
        if(data1[i]['id'] === "16" && ($("#cmbImpuesto").val() === "2" || $("#cmbImpuesto").val() === "12" || $("#cmbImpuesto").val() === "3" || $("#cmbImpuesto").val() === "18")){
          iepsBan++;
        }
        if($("#cmbImpuesto").val() === "16" && (data1[i]['id'] === "2" || data1[i]['id'] === "12" || data1[i]['id'] === "3" || data1[i]['id'] === "18")){
          iepsExento++;
        }
      }
    }

    if(ban > 0){
      Lobibox.notify("error", {
        size: "mini",
        rounded: true,
        delay: 3100,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../img/timdesk/warning_circle.svg",
        msg: "Ya se ingresó este impuesto",
      });
      
    } else {
      if(ivaBan > 0){
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3100,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/warning_circle.svg",
          msg: "Hay un IVA exento registrado para este producto",
        });
      } else if(ivaExento > 0){
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3100,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/warning_circle.svg",
          msg: "Hay un IVA de tipo trasladado y/o retenido registrado para este producto",
        });
      } else if(isrBan > 0){
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3100,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/warning_circle.svg",
          msg: "Hay un ISR exento registrado para este producto",
        });
      } else if(isrExento > 0){
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3100,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/warning_circle.svg",
          msg: "Hay un ISR de tipo trasladado y/o retenido registrado para este producto",
        });
      } else if(iepsExento > 0){
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3100,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/warning_circle.svg",
          msg: "Hay un IEPS exento registrado para este producto",
        });
      } else if(iepsBan > 0){
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3100,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/warning_circle.svg",
          msg: "Hay un IEPS de tipo trasladado y/o retenido registrado para este producto",
        });
      } else {
        tableTax.row.add({
          "id":$("#cmbImpuesto").val(),
          "tipo":$('input[type=radio][name=tipoImpuesto]').val(),
          "nombre":$("#cmbImpuesto option:selected").text(),
          "tasa":$("#txtTax").val(),
          "delete":"<a id='deleteImp"+$("#cmbImpuesto").val()+"' data-pos='"+counRows+"' data-id='"+$("#cmbImpuesto").val()+"' href='#'><i class='fas fa-trash-alt'></i></a>"
        }).draw();

        $.ajax({
          method: "POST",
          url: 'php/funciones.php',
          data: {
            clase: 'save_data',
            funcion: 'save_dataTaxes',
            producto: $("#txtIdProd").val(),
            value: $("#cmbImpuesto").val(),
            tasa: $("#txtTax").val(),
            id: $("#txtIdReferencia").val(),
          },
          datatype: "json",
          success: function(respuesta){
            loadCombo1("impuestos","#cmbImpuesto","","Seleccione un impuesto...",1);
            $("#tipoImpuesto1").prop("checked",true);
            $("#tipoImpuesto2").prop("checked",false);
            $("#tipoImpuesto3").prop("checked",false);
            $("#txtTax").val("0");

          },
          error: function(error){
            console.log(error);
          }
        });
      }
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
      msg: "Debe seleccionar un impuesto con una tasa",
    })
    
  }
    
 
});

$(document).on("click","#btnEditarProducto",function(){
  const tableTax = $('#tblDetalleImpuestosModal').DataTable();
  var taxes = tableTax.rows().data();

  var tipo_documento = "";
  var documento = "";
  var cliente;
  switch (tipo_documento) {
    case "1":
      documento = $("#cmbCotizacion").val() !== "" && $("#cmbCotizacion").val() !== null ? $("#cmbCotizacion").val() : $("#txtCotizacion").val();
      cliente = $("#cmbCliente").val() !== "" && $("#cmbCliente").val() !== null ? $("#cmbCliente").val() : $("#txtCotizaciones").val();
      break;
    case "2":
      documento = $("#cmbVentaDirecta").val() !== "" && $("#cmbVentaDirecta").val() !== null ? $("#cmbVentaDirecta").val() : $("#txtVenta").val();
      cliente = $("#cmbCliente").val() !== "" && $("#cmbCliente").val() !== null ? $("#cmbCliente").val() : $("#txtVenta").val();
      break;
    case "3":
      documento = $("#cmbPedido").val();
      break;
  }
  var id = $("#txtIdReferencia").val();
  var producto_id = $("#txtIdProd").val();
  var sat_id = $("#txtClaveSatId").val();
  var id_unidad_medida = $("#txtUnidadMedidaId").val();
  var cantidad = $("#txtCantidad").val();
  var precio_unitario = $("#txtPrecioUnitario").val();
  var subtotal = parseInt(cantidad) * parseFloat(precio_unitario);
  var descuento_tasa;
  var descuento_fijo;
  var predial = $("#txtPredial").val();
  var impuestos = "[";
  
  if($("#txtDescuento").val() !== ""){
    var descuentoValor = $("#txtDescuento").val();
    var descuentoTipo = $('input[type=radio][name=tipoDescuento]').val();
    switch(descuentoTipo){
      case "1":
        descuentoTotal = subtotal * (parseInt(descuentoValor) / 100); 
        descuento_tasa = descuentoValor;
      break;
      case "2":
        descuento_fijo = descuentoValor;
      break;
    }
  } else {
    descuento_tasa = "";
    descuento_fijo = "";
    descuentoTotal = "";
  }

  $.each(taxes,function(i){
    impuestos += '{'+
                    '"id":"'+taxes[i].id+'",'+
                    '"tipo":"'+taxes[i].tipo+'",'+
                    '"tasa":"'+taxes[i].tasa+'"'+
                 '},';
  });

  impuestos = impuestos.substring(0, impuestos.length-1);

  impuestos += "]";

  if(impuestos === "]"){
    impuestos = "[]";
  }
  if(sat_id !== "" && sat_id !== 1 && sat_id !== "1"){
    if(precio_unitario !== "" && precio_unitario !== 0 && precio_unitario !== "0" && precio_unitario !== "0.00"){
      var data = '{'+
                    '"id":"'+id+'",'+
                    '"referencia":"'+documento+'",'+
                    '"tipo_referencia":"'+tipo_documento+'",'+
                    '"producto_id":"'+producto_id+'",'+
                    '"sat_id":"'+sat_id+'",'+
                    '"unidad_medida_id":"'+id_unidad_medida+'",'+
                    '"cantidad":"'+cantidad+'",'+
                    '"precio_unitario":"'+precio_unitario+'",'+
                    '"subtotal":"'+subtotal+'",'+
                    '"descuento_tasa":"'+descuento_tasa+'",'+
                    '"importe_descuento_tasa":"'+descuentoTotal+'",'+
                    '"descuento_monto_fijo":"'+descuento_fijo+'",'+
                    '"predial":"'+predial+'",'+
                    '"impuestos":'+impuestos+
                    
                '}';

      $.ajax({
        method: "POST",
        url: "php/funciones.php",
        data: {
          clase: "edit_data",
          funcion: "edit_dataProducto",
          value: data
        },
        datatype: "json",
        success: function(respuesta){
          
          $("#editarProducto").modal("hide");

          var id = "#tblDetalleProductos";
          var func = "get_productosEditTable";
          
          $(id).DataTable().clear().destroy();
          loadDataTableProducts(id,func,documento,tipo_documento);
          loadSubtotal();
        },
        error: function(error){
          console.log(error);
        }

      });
    } else {
      Lobibox.notify("error", {
        size: "mini",
        rounded: true,
        delay: 3100,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../img/timdesk/warning_circle.svg",
        msg: "El precio unitario debe ser mayor a cero",
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
      msg: "Debe selecionar una clave SAT",
    });
  }

});

$(document).on('click','#tblDetalleImpuestosModal a',function(){
  var deleteTaxPos = $(this).closest('tr').index();
  var deleteTaxId = $(this).data("id");
  var tableTax = $('#tblDetalleImpuestosModal').DataTable();
  var tipo_documento ="";
  var id_document = "";
  var aux = $(this).closest('tr').index();
  var tasa = tableTax.row(aux).data()['tasa'];
  switch (tipo_documento) {
    case "1":
      id_document = $("#cmbCotizacion").val() !== "" && $("#cmbCotizacion").val() !== null ? $("#cmbCotizacion").val() : $("#txtCotizacion").val();
      cliente = $("#cmbCliente").val() !== "" && $("#cmbCliente").val() !== null ? $("#cmbCliente").val() : $("#txtCotizaciones").val();
      break;
    case "2":
      id_document = $("#cmbVentaDirecta").val() !== "" && $("#cmbVentaDirecta").val() !== null ? $("#cmbVentaDirecta").val() : $("#txtVenta").val();
      cliente = $("#cmbCliente").val() !== "" && $("#cmbCliente").val() !== null ? $("#cmbCliente").val() : $("#txtVenta").val();
      break;
    case "3":
      id_document = $("#cmbPedido").val();
      break;
  }
 
  $.ajax({
    method: "POST",
    url: 'php/funciones.php',
    data: {
      clase: 'delete_data',
      funcion: 'delete_taxProducto',
      cot: id_document,
      producto: $("#txtIdProd").val(),
      value: deleteTaxId,
      tipo: tipo_documento,
      id: $("#txtIdReferencia").val(),
      tasa: tasa
    },
    datatype: "json",
    success: function(respuesta){
      if(respuesta){
        tableTax.rows(deleteTaxPos).remove().draw();
      } else{
        console.log("hubo un error");
      }
      
    },
    error: function(error){
      console.log(error);
    }
  });
  
});

function validateSAT(prod){
  $.ajax({
    method: "POST",
    url: "php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_claveSat",
      value: prod
    },
    success: function(respuesta){
      
      res = JSON.parse(respuesta);
      switch (res.mensaje) {
        case 0:
          $("#sec_clave_sat").css("display","block");
          break;
        case 1:
          $("#sec_clave_sat").css("display","none");
          break;
      }
      $("#txtClaveSatId").val(res.clave_sat_id);
    },
    error: function(error){
      console.log(error);
    }
  });
}

$(document).on("click","#txtClaveSat",function(){
  loadTableSat("#tabla_body_sat","#txtClaveSat");
  $('#buscar_clave_sat').val("");
  $("#editarProducto").modal("hide");

  $('.modal').on('hidden.bs.modal', function () {
    if($('.modal:visible').length){
      $('body').addClass('modal-open');
      
    }
    
  });
  $("#agregar_clave_sat").modal('show');
  
});

function loadTableSat(table,search){
  
  $.ajax({
    method: "POST",
    url: "php/funciones.php",
    data: {
      clase: 'get_data',
      funcion: 'get_claveSatTable',
    },
    dataType: 'json',
    success: function(response){
      html = "";
      
      $.each(response,function(i){
          html += '<tr data-id="'+response[i].id+'">'+
                    '<td>'+response[i].clave+'</td>'+
                    '<td>'+response[i].descripcion+'</td>'+
                  '</tr>';
      });
      
      if($(search).val() !== ""){
        if(response.length > 0){
          $(table).html(html);
        } else if($(search).val() !== "" && html === ""){
          $(table).html('<tr><td colspan="2">No se encontraron coincidencias...</td></tr>');
        } 
      }
    },error:function(error){
      console.log(error);
    }

 });
}

$(document).on('click','#tabla_body_sat tr',function(){
  clave =$(this).children()[0].innerHTML;
  descripcion = $(this).children()[1].innerHTML;
  sat = clave + " - " + descripcion;
  id = $(this).data("id");
  $("#txtClaveSat").val(sat);
  $("#txtClaveSatId").val(id);

  $.ajax({
    method: "POST",
    url: "php/funciones.php",
    data: {
      clase: "edit_data",
      funcion: "edit_claveSat",
      value: id,
      prod: $('#txtIdProd').val()
    },
    datatype: "json",
    success: function(respuesta){

    },
    error: function(error){
      console.log(error);
    }

  })
  
  $("#agregar_clave_sat").modal("hide");
  //$("#editarProducto").modal('handleUpdate');
  //$("#editarProducto").modal("show");

  $('.modal').on('hidden.bs.modal', function () {
    if($('.modal:visible').length){
      $('body').addClass('modal-open');
    }
    
  });
  $("#editarProducto").modal('show');
});

$(document).on("keyup",'#buscar_clave_sat',function(){
  $.ajax({
    method: "POST",
    url: "php/funciones.php",
    data: {
      clase: 'get_data',
      funcion: 'get_claveSatTableSearch',
      value: $(this).val()
    },
    dataType: 'json',
    success: function(response){
     
      html = "";
      
      $.each(response,function(i){
          html += '<tr data-id="'+response[i].id+'">'+
                    '<td>'+response[i].clave+'</td>'+
                    '<td>'+response[i].descripcion+'</td>'+
                  '</tr>';
      });
      
      if($('#buscar_clave_sat').val() !== ""){
        if(response.length > 0){
          $("#tabla_body_sat").html(html);
        } else if($('#buscar_clave_sat').val() !== "" && html === ""){
          $("#tabla_body_sat").html('<tr><td colspan="2">No se encontraron coincidencias...</td></tr>');
        }
      } else {
        loadTableSat("#tabla_body_sat","#txtClaveSat");
      }

    },error:function(error){
      console.log(error);
    }

 });
});

$(document).on('click',"#txtUnidadMedida",function(){
  loadTableUnidadMedida("#tabla_body_medida","#txtUnidadMedida")
  $('#buscar_clave_unidad_medida').val("");
  $("#editarProducto").modal("hide");

  $('.modal').on('hidden.bs.modal', function () {
    if($('.modal:visible').length){
      $('body').addClass('modal-open');
      
    }
    
  });

  $("#agregar_unidad_medida").modal('show');
});

$(document).on('click','#tabla_body_medida tr',function(){
  clave =$(this).children()[0].innerHTML;
  descripcion = $(this).children()[1].innerHTML;
  sat = clave + " - " + descripcion;
  id = $(this).data("id");
  $("#txtUnidadMedida").val(sat);
  $("#txtUnidadMedidaId").val(id);

  $.ajax({
    method: "POST",
    url: "php/funciones.php",
    data: {
      clase: "edit_data",
      funcion: "edit_claveSatUnidad",
      value: id,
      prod: $('#txtIdProd').val()
    },
    datatype: "json",
    success: function(respuesta){

    },
    error: function(error){
      console.log(error);
    }

  })

  $("#agregar_unidad_medida").modal("hide");
  //$("#editarProducto").modal('handleUpdate');
  //$("#editarProducto").modal("show");

  $('.modal').on('hidden.bs.modal', function () {
    if($('.modal:visible').length){
      $('body').addClass('modal-open');
    }
    
  });
  $("#editarProducto").modal('show');
});

function loadTableUnidadMedida(table,search){
  $.ajax({
    method: "POST",
    url: "php/funciones.php",
    data: {
      clase: 'get_data',
      funcion: 'get_claveUnidadMedidaTable',
    },
    dataType: 'json',
    success: function(response){
      html = "";
      
      $.each(response,function(i){
          html += '<tr data-id="'+response[i].id+'">'+
                    '<td>'+response[i].clave+'</td>'+
                    '<td>'+response[i].descripcion+'</td>'+
                  '</tr>';
      });
      
      if($(search).val() !== ""){
        if(response.length > 0){
          $(table).html(html);
        } else if($(search).val() !== "" && html === ""){
          $(table).html('<tr><td colspan="2">No se encontraron coincidencias...</td></tr>');
        } 
      }else {
        //$("#tabla_body_sat").html("<tr><td colspan='2'>Escriba algo en la búsqueda...</td></tr>");
      }
    },error:function(error){
      console.log(error);
    }

 });
}

$(document).on("keyup",'#buscar_clave_unidad_medida',function(){
  $.ajax({
    method: "POST",
    url: "php/funciones.php",
    data: {
      clase: 'get_data',
      funcion: 'get_claveUnidadMedidaTableSearch',
      value: $(this).val()
    },
    dataType: 'json',
    success: function(response){
     
      html = "";
      
      $.each(response,function(i){
          html += '<tr data-id="'+response[i].id+'">'+
                    '<td>'+response[i].clave+'</td>'+
                    '<td>'+response[i].descripcion+'</td>'+
                  '</tr>';
      });
       
      if($('#buscar_clave_unidad_medida').val() !== ""){
        if(response.length > 0){
          $("#tabla_body_medida").html(html);
        } else if($('#buscar_clave_unidad_medida').val() !== "" && html === ""){
          $("#tabla_body_medida").html('<tr><td colspan="2">No se encontraron coincidencias...</td></tr>');
        } 
      } else {
        loadTableUnidadMedida("#tabla_body_medida","#txtUnidadMedida");
      }

    },error:function(error){
      console.log(error);
    }

 });
});

$(document).on('click','#tabla_body_medida tr',function(){
  clave =$(this).children()[0].innerHTML;
  descripcion = $(this).children()[1].innerHTML;
  sat = clave + " - " + descripcion;
  id = $(this).data("id");
  $("#txtUnidadMedida").val(sat);
  $("#txtUnidadMedidaId").val(id);

  $.ajax({
    method: "POST",
    url: "php/funciones.php",
    data: {
      clase: "edit_data",
      funcion: "edit_claveSatUnidad",
      value: id,
      prod: $('#txtIdProd').val()
    },
    datatype: "json",
    success: function(respuesta){

    },
    error: function(error){
      console.log(error);
    }

  })

  $("#agregar_unidad_medida").modal("hide");
  //$("#editarProducto").modal('handleUpdate');
  //$("#editarProducto").modal("show");

  $('.modal').on('hidden.bs.modal', function () {
    if($('.modal:visible').length){
      $('body').addClass('modal-open');
    }
    
  });
  $("#editarProducto").modal('show');
});

function loadSubtotal(){
  $.ajax({
    method: "POST",
    url: "php/funciones.php",
    data: {
      clase: 'get_data',
      funcion: 'get_totalSubtotalSalidas',
    },
    asyn:false,
    dataType: 'json',
    success: function(respuesta){
      var res = respuesta;
      $('#subtotal').html("$ "+res.subtotal);
      $('#impuestos').html(res.impuestos);
      $('#total').html("$ "+res.total);
      $('.subtotal').css("display","block");
      $('.impuestos').css("display","block");
      $('.total').css("display","block");

    },error: function(error){
      console.log(error);
    }

  });
}

function validateUnidadMedida(prod){
  $.ajax({
    method: "POST",
    url: "php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_unidadMedida",
      value: prod
    },
    success: function(respuesta){
      
      res = JSON.parse(respuesta);
     
      switch (res.mensaje) {
        case "0":
          $("#sec_clave_sat").css("display","block");
          break;
        case "1":
          $("#sec_clave_sat").css("display","none");
          break;
      }
      $("#txtUnidadMedidaId").val(res.clave_sat_id);
    },
    error: function(error){
      console.log(error);
    }
  });
}

function truncateTablePRoducts(){
  $.ajax({
    method: "POST",
    url: "php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_truncateTableProducts",
    },
    success: function(){
    },
    error: function(error){
      console.log(error);
    }
  });
}