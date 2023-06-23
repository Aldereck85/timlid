let txtFactuacion = $("#txtFacturar").val();

var arrcuentasVentasEstatus = {};
var arrcuentasVentasFolios = {};
var arrcuentasVentasPagado = {};
//var arrcuentasVentasFolioSalida = {};
var arrcuentasVentasTotalFolioSalida = {};

$(document).ready(function(){
  
  cmbFacturarDesde = new SlimSelect({
    select: '#cmbFacturarDesde',
    placeholder: 'Facturar desde...',
  });

  cmbCotizacion = new SlimSelect({
    select: '#cmbCotizacion',
    placeholder: 'Seleccione una cotización...'
  });
  
  cmbUsoCfdi = new SlimSelect({
    select: '#cmbUsoCFDI',
    placeholder: 'Seleccione un uso de cfdi...'
  });

  cmbVentaDirecta = new SlimSelect({
    select: '#cmbVentaDirecta',
    placeholder: 'Seleccione una venta directa...'
  });
  
  cmbRemision = new SlimSelect({
    select: '#cmbRemision',
    placeholder: 'Seleccione una remisión...'
  });
  
  cmbFormasPago = new SlimSelect({
    select: '#cmbFormasPago',
    placeholder: 'Seleccione una forma de pago...'
  });

  cmbCuentaBancaria = new SlimSelect({
    select : "#cmbCuentaBancaria",
    placeholder: 'Seleccione una cuenta bancaria...'
  });

  cmbMetodoPago = new SlimSelect({
    select: '#cmbMetodoPago',
    placeholder: 'Seleccione un método de pago...'
  });

  cmbMoneda = new SlimSelect({
    select: '#cmbMoneda',
    placeholder: 'Seleccione una moneda...'
  });
  
  cmbCliente = new SlimSelect({
    select: '#cmbCliente',
    placeholder: 'Seleccione un cliente...'
  });

  cmbImpuesto = new SlimSelect({
    select: '#cmbImpuesto',
    placeholder: 'Seleccione un impuesto...'
  });

  cmbSucursales = new SlimSelect({
    select: '#cmbSucursales',
    placeholder: 'Seleccione una sucursal...'
  });
/*
  cmbAddressInvoice = new SlimSelect({
    select: '#cmbAddressInvoice',
    placeholder: 'Seleccione una dirección de facturación...'
  })
*/
  if($("#txtPrefactura").val() === ""){
    if($('#txtSelect').val() === ""){

      $(".comboFacturarDesde").css("display", "block");
      $("#espacio-facturar").append("<hr>");

      $('#cmbFacturarDesde').on('change', function(){
        $('.cabecera-cliente').css("display","none");
        $('.cuerpo').css("display","none");
        $('.productos-cliente').css("display","none");

        if($(this).val() === "1"){
        
          $('#comboCliente').css("display","block");
          $('#comboVentaDirecta').css("display","none");
          $('#comboPedido').css("display","none");
          $('#comboVentaDirecta').css("display","none");
          $('#select-pedidos').css("display","none");
          $('#select-salidas').css("display","none");
          $("#btnCargarSalidas").css("display","none");
          $("#comboRemision").css("display","none");
          optionSalidas = new Array();
          optionPedidos = new Array();
       
          loadCombo1("clienteCotizaciones","#cmbCliente","","Seleccione un cliente...");

          $("#cmbCliente").on("change",function(){
          
            if($(this).val().trim() !== "0"){
              $('#comboCotizacion').css("display","block");
              $('#comboVentaDirecta').css("display","none");
              $('#select-pedidos').css("display","none");
              $('#select-salidas').css("display","none");
              $("#btnCargarSalidas").css("display","none");
              $('.cabecera-cliente').css("display","none");
              $('.cuerpo').css("display","none");
              $('.productos-cliente').css("display","none");
              $('.subtotal').css("display","none");
              $('.impuestos').css("display","none");
              $('.total').css("display","none");
              optionSalidas = new Array();
              optionPedidos = new Array();
              loadSerieFolio();
              loadCombo1("cotizaciones","#cmbCotizacion","","Seleccione una cotizacion...",$(this).val());
              loadCombo1("addressInvoiceCombo","#cmbAddressInvoice","","Seleccione una dirección de facturación...",$(this).val());
          }
        });

        $.ajax({
          method: "POST",
          url: "php/funciones.php",
          data: {
            clase: 'get_data',
            funcion: 'get_lastUsoCFDI',
            value: 0
          },
          asyn:false,
          dataType: 'json',
          success: function(respuesta){
            usoCfdi = respuesta[0]['uso_cfdi_id'];
            formaPago = respuesta[0]['forma_pago_id'];
            
            switch (respuesta[0]['metodo_pago']) {
              case "1":
                metodoPago = "PUE";
              break;
              case "2":
                metodoPago = "PPD";
              break;
              default:
                metodoPago = "PUE";
              break;
            }
            loadCombo1("cfdiUse","#cmbUsoCFDI",usoCfdi,"Seleccione un uso de CFDI...");
            cmbMetodoPago.set(metodoPago);
            loadCombo1("formasPago","#cmbFormasPago",formaPago,"Seleccione una forma de pago...");
            loadCombo1("monedas","#cmbMoneda",100,"Seleccione una moneda...");
          },error: function(error){
            console.log(error);
          }
        });
        
        $(document).on("change","#cmbCotizacion",function(){
          $("#notas_pie").css("display","none");
          $("#notas_cliente").css("display","none");
          $("#notas_pie").css("display","none");
          $("#notas_vendedor").css("display","none");
          let data = $(this).val();
          $("#txtDocumento").val(data);
          $.ajax({
            method: "POST",
            url: 'php/funciones.php',
            data: {
              clase: 'get_data',
              funcion: 'get_cotizacion',
              value: data
            },
            datatype: "json",            
            success: function(respuesta){
              var res = JSON.parse(respuesta);
              is_customer_for_billing(res[0].PKCliente, res[0].rfc, res[0].regimen_fiscal_id);
              $('.cabecera-cliente').css("display","block");
              $('.cuerpo').css("display","block");
              $("#nofacturar").html("Referencia:<br>"+res[0].referencia);
              $("#razon_social").html(res[0].razon_social);
              $("#totalFacturar").html("Total: "+res[0].total);
              $("#rfc").html(res[0].rfc);

              if(res[0].nota_cliente !== "" && res[0].nota_cliente !== null && res[0].nota_cliente.trim() !== ""){
                $("#txaNotasCliente").val(res[0].nota_cliente);
                $("#notas_pie").css("display","block");
                $("#notas_cliente").css("display","block");
              }
              
              if(res[0].nota_internas !== "" && res[0].nota_internas !== null && res[0].nota_internas.trim() !== ""){
                $("#txaNotasVendedor").val(res[0].nota_internas);
                $("#notas_pie").css("display","block");
                $("#notas_vendedor").css("display","block");
              }
        
              var idTable = "#tblDetalleProductos";
              var func = "get_productosCotizacionTable";
              
              var rowCountProduct = $(idTable+">tbody>tr");
            
              if(rowCountProduct.length > 0){
                $(idTable).DataTable().clear().destroy();
                loadDataTableProducts(idTable,func,data);
                
              } else {
                loadDataTableProducts(idTable,func,data);
              }
              var value = '["'+data+'"]';
              loadSubtotal(value,1);
            },
            error: function(error){              
              console.log(error);
            }
        
          });
          
        });
        
      } else if($(this).val() === "2"){
        
        $('#comboCliente').css("display","block");
        $('#comboCotizacion').css("display","none");
        $('#comboPedido').css("display","none");
        $('#select-pedidos').css("display","none");
        $('#select-salidas').css("display","none");
        $("#btnCargarSalidas").css("display","none");
        $("#comboRemision").css("display","none");
        optionSalidas = new Array();
        optionPedidos = new Array();
        
        loadCombo1("clienteVentasDirectas","#cmbCliente","","Seleccione un cliente...");
        
        $("#cmbCliente").on("change",function(){
          if($(this).val().trim() !== "0"){
            $('#comboVentaDirecta').css("display","block");
            $('#comboCotizacion').css("display","none");
            $('#select-pedidos').css("display","none");
            $('#select-salidas').css("display","none");
            $("#btnCargarSalidas").css("display","none");
            $("#comboRemision").css("display","none");
            optionSalidas = new Array();
            optionPedidos = new Array();
            
            $('.subtotal').css("display","none");
            $('.impuestos').css("display","none");
            $('.total').css("display","none");
            loadSerieFolio();
            loadCombo1("ventasDirectas","#cmbVentaDirecta","","Seleccione una venta directa...",$(this).val());
          }
        });
        
        $.ajax({
          method: "POST",
          url: "php/funciones.php",
          data: {
            clase: 'get_data',
            funcion: 'get_lastUsoCFDI',
            value: 0
          },
          asyn:false,
          dataType: 'json',
          success: function(respuesta){
            usoCfdi = respuesta[0]['uso_cfdi_id'];
            formaPago = respuesta[0]['forma_pago_id'];
            
            switch (respuesta[0]['metodo_pago']) {
              case "1":
                metodoPago = "PUE";
              break;
              case "2":
                metodoPago = "PPD";
              break;
              default:
                metodoPago = "PUE";
              break;
            }

            loadCombo1("cfdiUse","#cmbUsoCFDI",usoCfdi,"Seleccione un uso de CFDI...");
            cmbMetodoPago.set(metodoPago);
            loadCombo1("formasPago","#cmbFormasPago",formaPago,"Seleccione una forma de pago...");
            loadCombo1("monedas","#cmbMoneda",100,"Seleccione una moneda...");
          },error: function(error){
            console.log(error);
          }
        });

        $('#cmbVentaDirecta').on('change',function(){
          $("#notas_pie").css("display","none");
          $("#notas_cliente").css("display","none");
          $("#notas_pie").css("display","none");
          $("#notas_vendedor").css("display","none");
          let data = $(this).val();
          $("#txtDocumento").val(data);
          buscaPagosVentas(data.split());
          $.ajax({
            method: "POST",
            url: 'php/funciones.php',
            data: {
              clase: 'get_data',
              funcion: 'get_ventaDirecta',
              value: data
            },
            datatype: "json",
            success: function(respuesta){
              var res = JSON.parse(respuesta);
              is_customer_for_billing(res[0].PKCliente, res[0].rfc, res[0].regimen_fiscal_id);
              console.log(res[0].id);
              $('.cabecera-cliente').css("display","block");
              $('.cuerpo').css("display","block");
              $("#nofacturar").html("Referencia:<br>"+res[0].referencia);
              $("#razon_social").html(res[0].razon_social);
              $("#totalFacturar").html("Total: "+res[0].total);
              $("#rfc").html(res[0].rfc);

              if(res[0].nota_cliente !== "" && res[0].nota_cliente !== null && res[0].nota_cliente.trim() !== ""){
                $("#txaNotasCliente").val(res[0].nota_cliente);
                $("#notas_pie").css("display","block");
                $("#notas_cliente").css("display","block");
              }
              
              if(res[0].nota_internas !== "" && res[0].nota_internas !== null && res[0].nota_internas.trim() !== ""){
                console.log("no estoy vacio");
                $("#txaNotasVendedor").val(res[0].nota_internas);
                $("#notas_pie").css("display","block");
                $("#notas_vendedor").css("display","block");
              } else {
                console.log("estoy vacio");
              }
              
              var idTable = "#tblDetalleProductos";
              var func = "get_productosVentasTable";
              
              var rowCountProduct = $(idTable+">tbody>tr");

              if(rowCountProduct.length > 0){
                $(idTable).DataTable().clear().destroy();
                loadDataTableProducts(idTable,func,data);
                
              } else {
                loadDataTableProducts(idTable,func,data);
              }
              var value = '["'+data+'"]';
              loadSubtotal(value,2);
              
            },
            error: function(error){
              console.log(error);
            }
        
          });
        });
      
      } else if($(this).val() === "3"){
        
        $('#comboCliente').css("display","block");

        $('#comboCotizacion').css("display","none");
        $('#comboVentaDirecta').css("display","none");
        $("#comboRemision").css("display","none");
        $("#notas_pie").css("display","none");
        $("#notas_vendedor").css("display","none");

        loadCombo1("clientePedidos","#cmbCliente","","Seleccione un cliente...");

        $("#cmbCliente").on("change",function(){
          $("#cmbPedido .placeholder span").remove("span");
          $("#cmbPedido .placeholder").append('<span class="select-disabled">Seleccione un pedido...</span>');
          $("#cmbSalida .placeholder span").remove("span");
          $("#cmbSalida .placeholder").append('<span class="select-disabled">Seleccione una salida...</span>');
          $("#cmbSalida .select-option").remove();
          $("#comboRemision").css("display","none");
          if($(this).val().trim() !== "0"){
            $('#comboCotizacion').css("display","none");
            $('#comboVentaDirecta').css("display","none");
            $('#select-pedidos').css("display","block");
           
            loadSerieFolio();
            $.ajax({              
              url: 'php/funciones.php',
              method: "post",
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
                   html += ("<div class='select-option' id='pedido_"+res[i].id+"' data-id='"+res[i].id+"'><span>"+res[i].texto+"</span><img class='check-select-option-disabled' data-id='"+res[i].id+"' src='../../img/timdesk/ICONO ESTADO-TERMINADO_Mesa de trabajo 1.svg'></div>");
                  
                  
                  });


                  
                } else {
                  html += ("<div class='select-option'><span>No se encontraron pedidos </span></div>");
                }

                $("#cmbPedido .select-list").html(html);
              },
              error: function(error){
                console.log(error);
              }
            });
            
          }
        });
        
        $.ajax({
          method: "POST",
          url: "php/funciones.php",
          data: {
            clase: 'get_data',
            funcion: 'get_lastUsoCFDI',
            value: 0
          },
          asyn:false,
          dataType: 'json',
          success: function(respuesta){
            usoCfdi = respuesta[0]['uso_cfdi_id'];
            formaPago = respuesta[0]['forma_pago_id'];
            switch (respuesta[0]['metodo_pago']) {
              case "1":
                metodoPago = "PUE"
              break;
              case "2":
                metodoPago = "PPD"
              break;
              default:
                metodoPago = null;
              break;
            }
            loadCombo1("cfdiUse","#cmbUsoCFDI",usoCfdi,"Seleccione un uso de CFDI...");
            cmbMetodoPago.set(metodoPago);
            loadCombo1("formasPago","#cmbFormasPago",formaPago,"Seleccione una forma de pago...");
            loadCombo1("monedas","#cmbMoneda",100,"Seleccione una moneda...");

            
          },error: function(error){
            console.log(error);
          }
        });

      } else if($(this).val() === "4"){
        $('#comboCliente').css("display","block");
        $('#comboCotizacion').css("display","none");
        $('#comboVentaDirecta').css("display","none");
        $('#select-pedidos').css("display","none");
        $('#select-salidas').css("display","none");
        $("#btnCargarSalidas").css("display","none");

        loadCombo1("clienteRemisiones","#cmbCliente","","Seleccione un cliente...");

        $("#cmbCliente").on("change",function(){
          if($(this).val().trim() !== "0"){
            $('#comboRemision').css("display","block");
            $('#comboCotizacion').css("display","none");
            $('#comboVentaDirecta').css("display","none");
            $('#select-pedidos').css("display","none");
            $('#select-salidas').css("display","none");
            $("#btnCargarSalidas").css("display","none");
            
            $('.subtotal').css("display","none");
            $('.impuestos').css("display","none");
            $('.total').css("display","none");
            loadSerieFolio();
            loadCombo1("remisiones","#cmbRemision","","Seleccione una remision...",$(this).val());
          }
        });

        $.ajax({
          method: "POST",
          url: "php/funciones.php",
          data: {
            clase: 'get_data',
            funcion: 'get_lastUsoCFDI',
            value: 0
          },
          asyn:false,
          dataType: 'json',
          success: function(respuesta){
            console.log(respuesta);
            usoCfdi = respuesta[0]['uso_cfdi_id'];
            formaPago = respuesta[0]['forma_pago_id'];
            switch (respuesta[0]['metodo_pago']) {
              case "1":
                metodoPago = "PUE"
              break;
              case "2":
                metodoPago = "PPD"
              break;
              default:
                metodoPago = null;
              break;
            }
            loadCombo1("cfdiUse","#cmbUsoCFDI",usoCfdi,"Seleccione un uso de CFDI...");
            cmbMetodoPago.set(metodoPago);
            loadCombo1("formasPago","#cmbFormasPago",formaPago,"Seleccione una forma de pago...");
            loadCombo1("monedas","#cmbMoneda",100,"Seleccione una moneda...");
          },error: function(error){
            console.log(error);
          }
        });

        $('#cmbRemision').on('change',function(){
          let data = $(this).val();
          $("#txtDocumento").val(data);
          $.ajax({
            method: "POST",
            url: 'php/funciones.php',
            data: {
              clase: 'get_data',
              funcion: 'get_remision',
              value: data
            },
            datatype: "json",
            success: function(respuesta){
              var res = JSON.parse(respuesta);
              is_customer_for_billing(res[0].PKCliente, res[0].rfc, res[0].regimen_fiscal_id);
              $('.cabecera-cliente').css("display","block");
              $('.cuerpo').css("display","block");
              $("#nofacturar").html("Referencia:<br>"+res[0].referencia);
              $("#razon_social").html(res[0].razon_social);
              $("#totalFacturar").html("Total: "+res[0].total);
              $("#rfc").html(res[0].rfc);
              $("#subtotal").html(res[0].subtotal);
              $("#total").html(res[0].total);
              
              var idTable = "#tblDetalleProductos";
              var func = "get_productosRemisionTable";
              
              var rowCountProduct = $(idTable+">tbody>tr");
            
              if(rowCountProduct.length > 0){
                $(idTable).DataTable().clear().destroy();
                loadDataTableProducts(idTable,func,data);
                
              } else {
                loadDataTableProducts(idTable,func,data);
                
              }
              var value = '["'+data+'"]';
              loadSubtotal(value,4);
              
            },
            error: function(error){
              console.log(error);
            }
        
          });
        });
      }
    });

  } else 
    
  if($('#txtSelect').val() === "1"){
    let data = $("#txtFacturar").val();
    $("#txtDocumento").val(data);
    $("#cmbFacturarDesde").val("1");

    $.ajax({
      method: "POST",
      url: "php/funciones.php",
      data: {
        clase: 'get_data',
        funcion: 'get_ifHasQuotationOutputProduct',
        value: data
      },
      asyn:false,
      dataType: 'json',
      success: function(respuesta){
        if(respuesta > 0){
          $("#chkAfectarInventario").attr("disabled", true);
          $("#cmbSucursales").removeAttr("required");
        } else {
          $("#chkAfectarInventario").removeAttr("disabled");
          $("#cmbSucursales").attr("required", true);
        }
      },error: function(error){
          console.log(error);
        }
    });
    
    $.ajax({
      method: "POST",
      url: "php/funciones.php",
      data: {
        clase: 'get_data',
        funcion: 'get_lastUsoCFDI',
        value: 0
      },
      asyn:false,
      dataType: 'json',
      success: function(respuesta){
        
        usoCfdi = respuesta[0]['uso_cfdi_id'];
        formaPago = respuesta[0]['forma_pago_id'];
        switch (respuesta[0]['metodo_pago']) {
          case "1":
            metodoPago = "PUE"
          break;
          case "2":
            metodoPago = "PPD"
          break;
          default:
            metodoPago = null;
          break;
        }
        loadCombo1("cfdiUse","#cmbUsoCFDI",usoCfdi,"Seleccione un uso de CFDI...");
        cmbMetodoPago.set(metodoPago);
        loadCombo1("formasPago","#cmbFormasPago",formaPago,"Seleccione una forma de pago...");
        loadCombo1("monedas","#cmbMoneda",100,"Seleccione una moneda...");
      },error: function(error){
        console.log(error);
      }
    });
    
    $.ajax({
      method: "POST",
      url: 'php/funciones.php',
      data: {
        clase: 'get_data',
        funcion: 'get_cotizacion',
        value: data
      },
      datatype: "json",
      success: function(respuesta){
        var res = JSON.parse(respuesta);
        document.getElementById('chkAfectarInventario').checked = true;
        loadCombo1("sucursales","#cmbSucursales",res[0].sucursal_id,"Seleccione una sucursal...");
        is_customer_for_billing(res[0].PKCliente, res[0].rfc, res[0].regimen_fiscal_id);
        $('.cabecera-cliente').css("display","block");
        $('.cuerpo').css("display","block");
        $("#nofacturar").append("Cotización:<br>"+res[0].referencia);
       
        if(res[0].rfc === 'XAXX010101000'){
          $('#razonSocialPG').css("display","block");
          if($('#razonSocialPG').is(":visible")){
            $('#txtRazonSocialPG').prop("required",true);
          }
          
        } else {
          $("#razon_social").append("<br>"+res[0].razon_social);
        }
        $("#rfc").append("<br>"+res[0].rfc);
       
        loadSerieFolio();
        $("#txtCotizacion").val(res[0].id);

        if(res[0].nota_cliente !== "" && res[0].nota_cliente !== null){
          $("#txaNotasCliente").val(res[0].nota_cliente);
          $("#notas_pie").css("display","block");
          $("#notas_cliente").css("display","block");
        }
        
        if(res[0].nota_internas !== "" && res[0].nota_internas !== null){
          $("#txaNotasVendedor").val(res[0].nota_internas);
          $("#notas_pie").css("display","block");
          $("#notas_vendedor").css("display","block");
        }

        var idTable = "#tblDetalleProductos";
        var func = "get_productosCotizacionTable";
        
        var rowCountProduct = $(idTable+">tbody>tr");
      
        if(rowCountProduct.length > 0){
          $(idTable).DataTable().clear().destroy();
          loadDataTableProducts(idTable,func,data);
          
        } else {
          loadDataTableProducts(idTable,func,data);
          
        }
        var value = '["'+data+'"]';
        loadSubtotal(value,1);
        
      },
      error: function(error){
        console.log(error);
      }

    });
    
    
   
    
  } else if($('#txtSelect').val() === "2"){
    let data = $("#txtFacturar").val();
    $("#txtDocumento").val(data);
    $("#cmbFacturarDesde").val("2");

    $.ajax({
      method: "POST",
      url: "php/funciones.php",
      data: {
        clase: 'get_data',
        funcion: 'get_ifHasSaleOutputProduct',
        value: data
      },
      asyn:false,
      dataType: 'json',
      success: function(respuesta){
        $.ajax({
          method: "POST",
          url: "php/funciones.php",
          data: {
            clase: 'get_data',
            funcion: 'get_countRelationSalesByTicket',
            value: data
          },
          asyn:false,
          dataType: 'json',
          success: function(respuesta1){
            console.log(respuesta1);
            if(respuesta > 0 || respuesta1 > 0){
              $("#chkAfectarInventario").attr("disabled", true);
              $("#chkAfectarInventario").removeAttr("checked");
              $("#cmbSucursales").removeAttr("required");
              cmbSucursales.disable();
            } else {
              $("#chkAfectarInventario").removeAttr("disabled");
              $("#chkAfectarInventario").attr("checked", true);
              $("#cmbSucursales").attr("required", true);
              cmbSucursales.enable();
            }
          },error: function(error){
            console.log(error);
          }
        });
        // if(respuesta.length > 0){
        //   $("#chkAfectarInventario").attr("disabled", true);
        //   $("#chkAfectarInventario").removeAttr("checked");
        //   $("#cmbSucursales").removeAttr("required");
        //   cmbSucursales.disable();
        // } else {
        //   $("#chkAfectarInventario").removeAttr("disabled");
        //   $("#chkAfectarInventario").attr("checked", true);
        //   $("#cmbSucursales").attr("required", true);
        //   cmbSucursales.enable();
        // }
      },error: function(error){
          console.log(error);
        }
    });

    

    $.ajax({
      method: "POST",
      url: "php/funciones.php",
      data: {
        clase: 'get_data',
        funcion: 'get_lastUsoCFDI',
        value: 0
      },
      asyn:false,
      dataType: 'json',
      success: function(respuesta){
        usoCfdi = respuesta[0]['uso_cfdi_id'];
        formaPago = respuesta[0]['forma_pago_id'];
        
        switch (respuesta[0]['metodo_pago']) {
          case "1":
            metodoPago = "PUE"
          break;
          case "2":
            metodoPago = "PPD"
          break;
          default:
            metodoPago = "PUE";
          break;
        }
        
        loadCombo1("cfdiUse","#cmbUsoCFDI",usoCfdi,"Seleccione un uso de CFDI...");
        cmbMetodoPago.set(metodoPago);
        loadCombo1("formasPago","#cmbFormasPago",formaPago,"Seleccione una forma de pago...");
        loadCombo1("monedas","#cmbMoneda",100,"Seleccione una moneda...");
      },error: function(error){
        console.log(error);
      }
    });

    buscaPagosVentas(data.split());

    $.ajax({
      method: "POST",
      url: 'php/funciones.php',
      data: {
        clase: 'get_data',
        funcion: 'get_ventaDirecta',
        value: data
      },
      datatype: "json",
      success: function(respuesta){
        var res = JSON.parse(respuesta);
        loadCombo1("sucursales","#cmbSucursales",res[0].sucursal_id,"Seleccione una sucursal...");
        is_customer_for_billing(res[0].PKCliente, res[0].rfc, res[0].regimen_fiscal_id);
        loadSerieFolio();
        $('.cabecera-cliente').css("display","block");
        $('.cuerpo').css("display","block");
        $("#nofacturar").html("Referencia:<br>"+res[0].referencia);
        $("#razon_social").html(res[0].razon_social);
        $("#totalFacturar").html("Total: "+res[0].total);
        $("#rfc").html(res[0].rfc);

        $("#txtVenta").val(res[0].id);

        if(res[0].rfc === 'XAXX010101000'){
            $('#razonSocialPG').css("display","block");
            if($('#razonSocialPG').is(":visible")){
              $('#txtRazonSocialPG').prop("required",true);
            }
            
          } else {
            $("#razon_social").append("<br>"+res[0].razon_social);
        }

        if(res[0].nota_cliente !== "" && res[0].nota_cliente !== null){
          $("#txaNotasCliente").val(res[0].nota_cliente);
          $("#notas_pie").css("display","block");
          $("#notas_cliente").css("display","block");
        }
        
        if(res[0].nota_internas !== "" && res[0].nota_internas !== null){
          $("#txaNotasVendedor").val(res[0].nota_internas);
          $("#notas_pie").css("display","block");
          $("#notas_vendedor").css("display","block");
        }

        var idTable = "#tblDetalleProductos";
        var func = "get_productosVentasTable";
        
        var rowCountProduct = $(idTable+">tbody>tr");
      
        if(rowCountProduct.length > 0){
          $(idTable).DataTable().clear().destroy();
          loadDataTableProducts(idTable,func,data);
        } else {
          loadDataTableProducts(idTable,func,data);
        }
        var value = '["'+data+'"]';
        loadSubtotal(value,2);
        
      },
      error: function(error){
        console.log(error);
      }
      
    });
    
  } else if($('#txtSelect').val() === "3"){
    $("#nofacturar").append("Pedido:<br>");
  } else if($('#txtSelect').val() === "4"){}
} else {
    /*prefactura*/
    let data = $("#txtPrefactura").val();
    
    $.ajax({
      method: "POST",
      url: 'php/funciones.php',
      data: {
        clase: 'get_data',
        funcion: 'get_dataPreinvoice',
        value: data
      },
      datatype: "json",
      success: function(respuesta){
        var res = JSON.parse(respuesta);
        $('#txtTipoPrefactura').val(res[0].tipo)
        $('#txtReferenciaPrefactura').val(res[0].referencia);

        $('.cabecera-cliente').css("display","block");
        $('.cuerpo').css("display","block");
        switch (parseInt(res[0].tipo)) {
          case 1:
            referencia = "Cotizacion: "+ res[0].referencia;
            break;
          case 2:
            referencia = "Venta directa: "+ res[0].referencia;
            break;
          case 3:
            referencia = "Salidas: "+ res[0].referencia;
            break;
          case 4:
            referencia = "Remision: "+ res[0].referencia;
            break;
        
          default:
            referencia = "Por concepto";
            break;
        }
        //referencia = res[0].referencia !== "0" ? res[0].referencia : "Por concepto";
        $("#nofacturar").append("Referencia:<br>"+referencia);

        $("#razon_social").append("<br>"+res[0].data_cliente[0].razon_social);

        $("#txtClientePrefactura").val(res[0].data_cliente[0].PKCliente);
        
        $("#rfc").append("<br>"+res[0].data_cliente[0].rfc);
        //console.log(res[0].data_cliente[0].metodo_pago);
        metodo_pago = res[0].data_cliente[0].metodo_pago === 1 ? "PUE" : "PPD";
        loadCombo1("cfdiUse","#cmbUsoCFDI",res[0].data_cliente[0].uso_cfdi_id,"Seleccione un uso de CFDI...");
        cmbMetodoPago.set(metodo_pago);
        loadCombo1("formasPago","#cmbFormasPago",res[0].data_cliente[0].forma_pago_id,"Seleccione una forma de pago...");
        loadCombo1("monedas","#cmbMoneda",res[0].data_cliente[0].moneda_id,"Seleccione una moneda...");
        loadSerieFolio();
        $("#txtCotizacion").val(res[0].data_cliente[0].id);
        loadSerieFolioPreinvoice(data);
        
     
        $("#divChkPrefactura").css("display","none");
        
        var idTable = "#tblDetalleProductos";
        var func = "get_productosPrefactura";
        
        var rowCountProduct = $(idTable+">tbody>tr");
      
        if(rowCountProduct.length > 0){
          $(idTable).DataTable().clear().destroy();
          loadDataTableProducts(idTable,func,data);
          
        } else {
          loadDataTableProducts(idTable,func,data);
          
        }
        loadSubtotal(res[0].referencia,res[0].tipo);
        
      },
      error: function(error){
        console.log(error);
      }

    });
    
  }
});

$(document).on("click","#chkAfectarInventario", (e)=>{
  const target = e.target;
  const suc = document.getElementById('cmbSucursales'); 
  if(!target.disabled){
    
    if(target.checked === true){
      suc.setAttribute('required', 'true');
      cmbSucursales.enable();
    } else {
      suc.setAttribute('required', 'false');
      cmbSucursales.disable();
    }
    
  }
});

function is_customer_for_billing(cliente_id, rfc, regimen_fiscal_id){
  if(
    rfc == 'N/A' ||
    regimen_fiscal_id == 0 
  ){
    $("#link").html('<a class="btn btn-light" href="../clientes/catalogos/clientes/editar_cliente.php?c='+cliente_id+'">Editar</a>');
    $("#alert_custumer_no_billing").modal("show");
  }
}

  //Redireccionamos al Dash cuando se oculta el modal de usuario incompleto.
  $("#alert_custumer_no_billing").on("hidden.bs.modal", function (e) {
    window.location = href = "./";
  });

function loadCombo(funcion,input,data,texto,value){
  $.ajax({
    method: "POST",
    url: "php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_"+funcion,
      value: value
    },
    datatype: "json",
    success: function(respuesta){
      if(respuesta > -1){
        var res = JSON.parse(respuesta);
        var cont = 0;
        html = [{value:null,text:texto}];
        $.each(res,function(i){
          cont++;
          if(res[i].id === parseInt(data)){
            html.push({value:+res[i].id,text:res[i].texto,selected:'true'});
          } else {
            html.push({value:+res[i].id,text:res[i].texto});
          }
          
        });
        
        input.setData(html);
        html = null;
        return cont;
      }
    },
    error: function(error){
      console.log(error);
    }
  });
}

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
  

  $('.modal').on('hidden.bs.modal', function () {
    if($('.modal:visible').length){
      $('body').addClass('modal-open');
    }
    
  });
  $("#editarProducto").modal('show');
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
  

  $('.modal').on('hidden.bs.modal', function () {
    if($('.modal:visible').length){
      $('body').addClass('modal-open');
    }
    
  });
  $("#editarProducto").modal('show');
});

$(document).on('click','#btnAgregarImpuesto',function(){
  var tipo_documento = $("#cmbFacturarDesde").val();
  var id_document;
  
  switch (tipo_documento) {
    case "1":
      id_document = $("#cmbCotizacion").val() !== "" && $("#cmbCotizacion").val() !== null ? $("#cmbCotizacion").val() : $("#txtCotizacion").val();
      cliente = $("#cmbCliente").val() !== "" && $("#cmbCliente").val() !== null ? $("#cmbCliente").val() : $("#txtCotizacion").val();
      break;
    case "2":
      id_document = $("#cmbVentaDirecta").val() !== "" && $("#cmbVentaDirecta").val() !== null ? $("#cmbVentaDirecta").val() : $("#txtVenta").val();
      cliente = $("#cmbCliente").val() !== "" && $("#cmbCliente").val() !== null ? $("#cmbCliente").val() : $("#txtVenta").val();
      break;
    case "3":
      id_document = $("#cmbPedido").val();
      break;
    case "4":
      id_document = $("#cmbRemision").val();
    break;
  }
  
  var tableTax = $('#tblDetalleImpuestosModal').DataTable();
  var counRows = tableTax.rows().count();
  
  if($("#cmbImpuesto").val() !== "" && $("#txtTax").val() !== 0){
    data1 = tableTax.rows().data();
    
    let ban = 0;
    
    if(counRows > 0){
      for (let i = 0; i < data1.length; i++) {
        if(data1[i]['id'] === $("#cmbImpuesto").val()){
          ban++;
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
          cot: id_document,
          producto: $("#txtIdProd").val(),
          value: $("#cmbImpuesto").val(),
          tasa: $("#txtTax").val(),
          id: $("#txtIdReferencia").val(),
          tipo: tipo_documento
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

  var tipo_documento = $("#cmbFacturarDesde").val();
  var documento;
  var cliente;
  switch (tipo_documento) {
    case "1":
      documento = $("#cmbCotizacion").val() !== "" && $("#cmbCotizacion").val() !== null ? $("#cmbCotizacion").val() : $("#txtCotizacion").val();
      cliente = $("#cmbCliente").val() !== "" && $("#cmbCliente").val() !== null ? $("#cmbCliente").val() : $("#txtCotizacion").val();
      break;
    case "2":
      documento = $("#cmbVentaDirecta").val() !== "" && $("#cmbVentaDirecta").val() !== null ? $("#cmbVentaDirecta").val() : $("#txtVenta").val();
      cliente = $("#cmbCliente").val() !== "" && $("#cmbCliente").val() !== null ? $("#cmbCliente").val() : $("#txtVenta").val();
      break;
    case "3":
     
      break;
    case "4":
      documento = $("#cmbRemision").val();
    break;
  }
  
  if($("#txtPrefactura").val() !== ""){
    documento = $("#txtReferenciaPrefactura").val();
    tipo_documento = $("#txtTipoPrefactura").val();
  }
  var id_row = $("#txtIdReferencia").val();
  var producto_id = $("#txtIdProd").val();
  var sat_id = $("#txtClaveSatId").val();
  var id_unidad_medida = $("#txtUnidadMedidaId").val();
  var cantidad = $("#txtCantidad").val();
  var precio_unitario = $("#txtPrecioUnitario").val();
  var subtotal = parseInt(cantidad) * parseFloat(precio_unitario);
  var descuento_tasa = "";
  var descuento_fijo = "";
  var predial = $("#txtPredial").val();
  var impuestos = "[";
  
  if($("#txtDescuento").val() !== "" && $("#txtDescuento").val() !== 0 && $("#txtDescuento").val() !== "0"){
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
    if(precio_unitario !== "" && precio_unitario !== 0 && precio_unitario !== "0"  && precio_unitario !== "0.00"){
      var data = '{'+
                    '"id":"'+id_row+'",'+
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
					          '"factura_concepto":"0",'+
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
          loadDataTableProducts(id,func,data,id_row);
          loadSubtotal(documento,tipo_documento);
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

//comprueba si una venta tiene pagos parciales y es PUE, para avisar que se registrará el pago completo
function validaPagosCuentasCobrar(){
  let flag = false;
  let string = "";
  let tipo_documento = $("#cmbFacturarDesde").val();
  let msg="";
  if(tipo_documento == "2"){
    //si el metodo de pago es PUE y la venta tiene pagos parciales arroja alerta
    if($("#cmbMetodoPago").val() == "PUE"){
      for (const property in arrcuentasVentasEstatus) { 
        if(arrcuentasVentasEstatus[property] == 2){
          flag = true;
          string += arrcuentasVentasFolios[property]+', '
        }
      }
      string = string.substring(0, string.length - 2);
      msg = "La(s) Venta(s): " + string + " están pagadas parcialmente, para generar la factura es necesario que el pago esté completo <br><br> ¿Deseas registrar el pago? ";
    }
  }else if(tipo_documento == "3"){
    //cuando sea factura por pedido, la venta tenga pagos parciales, el metodo sea PUE y la suma de los pagos parciales 
    //no alcanza el total del monto del pedido, arroja alerta
    if($("#cmbMetodoPago").val() == "PUE"){
      for (const property in arrcuentasVentasPagado) { 
        if(parseFloat(arrcuentasVentasPagado[property]) < parseFloat(arrcuentasVentasTotalFolioSalida[property])){
          flag = true;
  
          string += arrcuentasVentasFolios[property]+', '
        }
      }
      string = string.substring(0, string.length - 2);
      msg = "El importe pagado de la(s) Venta(s): " + string + " no cubre el importe de su(s) salida(s) <br><br> ¿Deseas registrar el importe faltante? ";
    }
  }

  

  if(flag && !$("#chkPrefactura").is(":checked") && !$("#chkPagoContado").is(":checked")){
    Swal.fire({
      icon: "warning",
      title: "Pagos parciales",
      html: msg,
      type: "question",
      showConfirmButton: true,
      showCancelButton: true,
      confirmButtonText:
        'Si <i class="far fa-arrow-alt-circle-right"></i>',
      cancelButtonText: 'No <i class="far fa-times-circle"></i>',
      buttonsStyling: false,
      allowEnterKey: false,
      customClass: {
        actions: "d-flex justify-content-around",
        confirmButton: "btn-custom btn-custom--blue",
        cancelButton: "btn-custom btn-custom--border-blue",
      },
    }).then((result) => {
      if (result.isConfirmed) {

        AgregaFactura(1);
      } else {
        return false;        
      }
    });
  }else{
    if($("#chkAfectarInventario").is(":checked")){
      checkStockAllProduct();
    } else {
      AgregaFactura(0);
    }
  } 
}

function AgregaFactura(fromConfirmation){
  var payment_form = $("#cmbFormasPago").val();
  var payment_method = $("#cmbMetodoPago").val();
  if(validatePayment_form(payment_form,payment_method)){
    $("#agregarFactura").prop('disabled',true);
    $("#loader").css('display','block');
    var tipo_documento = $("#cmbFacturarDesde").val();
    var id_document;
    var cliente;

    switch (tipo_documento) {
      case "1":
        id_document = $("#cmbCotizacion").val() !== "" && $("#cmbCotizacion").val() !== null ? '["'+$("#cmbCotizacion").val()+'"]' : '["'+$("#txtCotizacion").val()+'"]';
        cliente = $("#cmbCliente").val() !== "" && $("#cmbCliente").val() !== null ? '["'+$("#cmbCliente").val()+'"]' : '["'+$("#txtCotizacion").val()+'"]';
        break;
      case "2":
        id_document = $("#cmbVentaDirecta").val() !== "" && $("#cmbVentaDirecta").val() !== null ? '["'+$("#cmbVentaDirecta").val()+'"]' : '["'+$("#txtVenta").val()+'"]';
        cliente = $("#cmbCliente").val() !== "" && $("#cmbCliente").val() !== null ? '["'+$("#cmbCliente").val()+'"]' : '["'+$("#txtVenta").val()+'"]';
        break;
      case "3":
        id_document = JSON.stringify(optionSalidas);
        cliente = $("#cmbCliente").val();
        break;
      case "4":
        id_document =  $("#cmbRemision").val();
        cliente = $("#cmbCliente").val();
      break;
    }

    if($("#razonSocialPG").is(":visible"))
    if($("#txtPrefactura").val() !== "" && $("#txtPrefactura").val() !== null){
      tipo_documento = $("#txtTipoPrefactura").val();
      id_document = $("#txtReferenciaPrefactura").val();
      cliente = '["'+$("#txtClientePrefactura").val()+'"]';
    }
    
    $.ajax({
      method: "POST",
      url: "php/funciones.php",
      data: {
        clase: 'get_data',
        funcion: 'get_productsAllSat',
        value: 0,
        tipo: tipo_documento,
        ref: id_document
      },
      dataType: 'json',
      success: function(respuesta){
        if(respuesta === 1){
          if ($("#datos-factura")[0].checkValidity()) {
            var badUsoCfdi =
              $("#invalid-usoCFDI").css("display") === "block" ? false : true;
            var badFormaPago = 
              $("#invalid-formasPago").css("display") === "block" ? false : true;
            var badMetodoPago =
              $("#invalid-metodosPago").css("display") === "block" ? false : true;
            var badMoneda = 
              $("#invalid-moneda").css("display") === "block" ? false : true;
            var badCuentaBancaria = 
              $("#invalid-cuentaBancaria").css("display") === "block" ? false : true;
            var badFechaVencimiento =
              $("#invalid-fechaVencimiento").css("display") === "block" ? false : true;
            var babAddressInvoice =
              $("#invalid-addressInvoice").css("display") === "block" ? false : true;
            var badRazonSocialPG = 
            $("#invalid-razonSocialPG").css("display") === "block" ? false : true;   
            var badSucursal = 
            $("#invalid-afectarInventario").css("display") === "block" ? false : true;
            if(badUsoCfdi &&
              badFormaPago &&
              badMetodoPago &&
              badMoneda &&
              badCuentaBancaria &&
              badFechaVencimiento &&
              babAddressInvoice && 
              badRazonSocialPG && 
              badSucursal)
            {
              
              $("#loader").addClass("loader");
              
              var datetime = $("#txtFechaEmision").val();

              if($("#chkPrefactura").is(":checked")){
                prefactura = 1;
              } else {
                prefactura = 0;
              }
              
                let ref = $("#txaReferencia").val();
                let aux = ref.split("\t").join(" ");
                aux = aux.split("\n").join(" ");
                aux = aux.split(" ");
                ref1 = "";
                for (let i = 0; i < aux.length; i++) {
                  if(aux[i] !== ""){
                    ref1 += aux[i] + " ";
                  }
                  
                }

                var id_prefactura = $("#txtPrefactura").val();
                
                var json = '{'+
                                '"idDocumento":'+id_document+','+
                                '"fechaEmision":"'+datetime+'",'+
                                '"tipoDocumento":"'+tipo_documento+'",'+
                                '"serie":"'+$("#txtSerie").val()+'",'+
                                '"folio":"'+$("#txtFolio").val()+'",'+
                                '"usoCfdi":"'+$("#cmbUsoCFDI").val()+'",'+
                                '"formaPago":"'+$("#cmbFormasPago").val()+'",'+
                                '"metodoPago":"'+$("#cmbMetodoPago").val()+'",'+
                                '"moneda":"'+$("#cmbMoneda").val()+'",'+
                                '"cliente":'+cliente+','+
                                '"predial":"'+$("#txtPredial").val()+'",'+
                                '"cuenta_bancaria":"' + $("#cmbCuentaBancaria").val() + '",'+
                                '"fecha_vencimiento":"'+ $("#txtFechaVencimiento").val() +'",'+
                                '"referencia":"'+ ref1 +'",'+
                                '"prefactura":"'+ prefactura +'",'+
                                '"fromConfirmation":"'+ fromConfirmation +'",'+
                                '"id_prefactura":"'+ id_prefactura +'"';
                var razon_social = $("#txtRazonSocialPG").val();
                $("#razonSocialPG").is(":visible") ? json += ',"razon_social":"'+razon_social+'"' : '';
                $("#chkAfectarInventario").is(":checked") ? json += ',"afectar_inventario":"1"' : json += ',"afectar_inventario":"0"';
                $("#cmbSucursales").val() !== "" && $("#cmbSucursales").val() !== null ? json += ',"sucursal":"'+$("#cmbSucursales").val()+'"' : "";
                json += '}';
                
                $.ajax({
                  method: "POST",
                  url: "php/funciones.php",
                  data: {
                    clase: "save_data",
                    funcion: "save_factura",
                    data: json,
                    data1: "",
                    value: 0
                  },
                  success: function(respuesta){
                      
                    //$("#agregarFactura").prop('disabled',true);
                    res = JSON.parse(respuesta);
                  
                    $(".loader").fadeOut("slow");
                    $("#loader").removeClass("loader");
                    
                    if(res.status === 0){

                      // if(tipo_documento == "1"){
                      //   $.ajax({
                      //     url: "functions/agregarPedidoCotizacion.php",
                      //     data: {
                      //       idCotizacion: txtFactuacion
                      //     },
                      //     success: function(respuesta){
                      //       console.log(respuesta);
                      //     },
                      //     error: function(error){
                      //       console.log(error);
                      //     },
                      //   });
                      // }                  
                      if(prefactura === 1){
                      
                        data = $("#tblDetalleProductos").DataTable();
                        aux = data.rows().data();
                        
                        $.redirect(
                          'ver_prefactura.php', 
                          {
                            'id_invoice' : res.id_invoice,
                            'folio': $("#txtFolio").val(),
                            'serie':$("#txtSerie").val(),
                            'razon_social':$("#razon_social").text(),
                            'fecha':$("#txtFechaEmision").val(),
                            'cfdi':$("#cmbUsoCFDI option:selected").text(),
                            'forma_pago':$("#cmbFormasPago option:selected").text(),
                            'metodo_pago':$("#cmbMetodoPago option:selected").text(),
                            'moneda':$("#cmbMoneda option:selected").text(),
                            'rfc':$("#rfc").text(),
                            'productos':aux.toArray(),
                            'subtotal':$("#subtotal").text(),
                            'impuestos':$("#impuestos").html(),
                            'total':$("#total").text(),
                            'notas_cliente':$("#notas_cliente").val(),
                            'direccion_envio':$("#direccion_envio").val(),
                            'contacto':$("#contacto").val(),
                            'telefono':$("#telefono").val()
                          },
                          "POST",
                          "_blank"
                        );

                      }
                      truncateTablePRoducts(id_document,tipo_documento);
                      Lobibox.notify("success", {
                        size: "mini",
                        rounded: true,
                        delay: 3000,
                        delayIndicator: false,
                        position: "center top",
                        icon: true,
                        img: "../../img/timdesk/checkmark.svg",
                        msg: res.message,
                        sound:false,
                      });
                      setTimeout(function() {
                          window.location.href = 'php/download_zip.php?value='+res.id_factura;
                          
                      }, 500);
                      setTimeout(function() {
                        window.location.href = 'detalle_factura.php?idFactura='+res.id_factura;
                      }, 1500);
                      
                    } else {
                      Lobibox.notify("error", {
                        size: "mini",
                        rounded: true,
                        delay: 3100,
                        delayIndicator: false,
                        position: "center top",
                        icon: true,
                        img: "../../img/timdesk/warning_circle.svg",
                        msg: res.message,
                      })
                      $("#agregarFactura").prop('disabled',false);
                      optionSalidas = new Array();
                    
                    }
                    
                  },
                  error: function(error){
                    console.log(JSON.stringify(error));
                  }

                });
              
            }
          } else {
            $("#agregarFactura").prop('disabled',false);
            if (!$("#cmbUsoCFDI").val()) {
              $("#invalid-usoCFDI").css("display", "block");
              $("#cmbUsoCFDI").addClass("is-invalid");
            }

            if (!$("#cmbFormasPago").val()) {
              $("#invalid-formasPago").css("display", "block");
              $("#cmbFormasPago").addClass("is-invalid");
            }

            if (!$("#cmbMetodoPago").val()) {
              $("#invalid-metodosPago").css("display", "block");
              $("#cmbMetodoPago").addClass("is-invalid");
            }

            if (!$("#cmbMoneda").val()) {
              $("#invalid-moneda").css("display", "block");
              $("#cmbMoneda").addClass("is-invalid");
            }

            if($("#cmbCuentaBancaria").attr('required')){
              if (!$("#cmbCuentaBancaria").val()) {
                $("#invalid-cuentaBancaria").css("display", "block");
                $("#cmbCuentaBancaria").addClass("is-invalid");
              }
            }

            if($("#txtFechaVencimiento").attr('required')){
              if (!$("#txtFechaVencimiento").val()) {
                $("#invalid-fechaVencimiento").css("display", "block");
                $("#txtFechaVencimiento").addClass("is-invalid");
              }
            }
            if($("#cmbAddressInvoice").attr('required')){
              if (!$("#cmbAddressInvoice").val()) {
                $("#invalid-addressInvoice").css("display", "block");
                $("#cmbAddressInvoice").addClass("is-invalid");
              }
            }
            if($("#txtRazonSocialPG").attr('required')){
              if(!$("#txtRazonSocialPG").val()){
                $("#invalid-razonSocialPG").css("display", "block");
                $("#txtRazonSocialPG").addClass("is-invalid")
              }
            }
            if($("#cmbSucursales").attr('required')){
              if(!$("#cmbSucursales").val()){
                $("#invalid-afectarInventario").css("display", "block");
                $("#cmbSucursales").addClass("is-invalid")
              }
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
            msg: "Uno o más productos no tienen una clave del SAT",
          });
        }
      },
      error:function(error){
        console.log(JSON.stringify(error));
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
      msg: 'El método de pago en parcialidades debe tener la forma de pago 99 - por definir',
    });
  }
}


$(document).on("click","#agregarFactura",function(){
  validaPagosCuentasCobrar();
});

$(document).on("change","#cmbUsoCFDI",function(){
  if($(this).hasClass("is-invalid")){
    $("#invalid-usoCFDI").css("display", "none");
    $("#cmbUsoCFDI").removeClass("is-invalid");
  }
});

$(document).on("change","#cmbFormasPago",function(){
  if($(this).hasClass("is-invalid")){
    $("#invalid-formasPago").css("display", "none");
    $("#cmbFormasPago").removeClass("is-invalid");
  }
});

$(document).on("change","#cmbMetodoPago",function(){
  if($(this).hasClass("is-invalid")){
    $("#invalid-metodosPago").css("display", "none");
    $("#cmbMetodoPago").removeClass("is-invalid");
  }
});

$(document).on("change","#cmbMoneda",function(){
  if($(this).hasClass("is-invalid")){
    $("#invalid-moneda").css("display", "none");
    $("#cmbMoneda").removeClass("is-invalid");
  }
});

$(document).on("change","#cmbCuentaBancaria",function(){
  if($(this).hasClass("is-invalid")){
    $("#invalid-cuentaBancaria").css("display", "none");
    $("#cmbCuentaBancaria").removeClass("is-invalid");
  }
});

$(document).on("change","#txtFechaVencimiento",function(){
  if($(this).hasClass("is-invalid")){
    $("#invalid-fechaVencimiento").css("display", "none");
    $("#txtFechaVencimiento").removeClass("is-invalid");
  }
});

$(document).on("change","#cmbAddressInvoice",function(){
  if($(this).hasClass("is-invalid")){
    $("#invalid-addressInvoice").css("display", "none");
    $("#cmbAddressInvoice").removeClass("is-invalid");
  }
});

$(document).on("keyup","#txtRazonSocialPG",(e)=>{
  const target = e.target;
  if(target.classList.contains('is-invalid')){
    $("#invalid-razonSocialPG").css("display", "none");
    $("#txtRazonSocialPG").removeClass("is-invalid");
  }
});

$(document).on("change","#cmbSucursales",(e)=>{
  const target = e.target;
  if(target.classList.contains('is-invalid')){
    $("#invalid-afectarInventario").css("display", "none");
    $("#cmbSucursales").removeClass("is-invalid");
  }
})

function setFormatDatatables(){
  return {
    "sProcessing": "Procesando...",
    "sZeroRecords": "No se encontraron resultados",
    "sEmptyTable": "Ningún dato disponible en esta tabla",
    "sSearch": "<img src='../../img/timdesk/buscar.svg' width='20px' />",
    "sLoadingRecords": "Cargando...",
    "searchPlaceholder": "Buscar...",
    
  }
  
}

function loadDataTableTax(id,func,product,id_row){
  $(id).css('display','table');
  const table = $(id).DataTable({
    "language": setFormatDatatables(),
    "dom":"lrti",
    "scrollX": false,
    "scrollCollapse": false,
    "lengthChange": false,
    "info": false,
    "bSort": true,
    "ajax":{
      method: "POST",
      url: "php/funciones.php",
      data: { 
        clase: "get_data", 
        funcion: func,
        producto: product,
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

function loadDataTableProducts(id,func,value,id_row){
  $(".productos-cliente").css("display","block");
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
      method: "POST",
      url: "php/funciones.php",
      data: {
        clase: "get_data",
        funcion: func,
        value:value,
        id_row:id_row
      },
      async:false
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
      }
    },error:function(error){
      console.log(error);
    }

 });
}

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

function loadDataProducto(idProducto,id_row){
  $("#txtIdProd").val(idProducto);
  $.ajax({
    method: "POST",
    url: 'php/funciones.php',
    data: {
      clase: 'get_data',
      funcion: 'get_dataProduct',
      id_row: id_row
    },
    datatype: "json",
    success: function(respuesta){
      var res = JSON.parse(respuesta);
      
      $("#editarProducto").modal('show');
      var claveSat = (res['clave_sat'] !== "" && res['clave_sat'] !== null && res['clave_sat'] !== 1) ? res['clave_sat'] : "";
     
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
      
      var idTable ="#tblDetalleImpuestosModal";
      var func = "get_impuestoTable";
      var tipo_doc = $("#cmbFacturarDesde").val();
      
      var rowCountTax = $("#tblDetalleImpuestosModal>tbody>tr");
      
      if(rowCountTax.length > 0){
        $(idTable).DataTable().clear().destroy();
        loadDataTableTax(idTable,func,idProducto,id_row);
      } else {
        loadDataTableTax(idTable,func,idProducto,id_row);
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

$(document).on('click','#tblDetalleImpuestosModal a',function(){
  var deleteTaxPos = $(this).closest('tr').index();
  var deleteTaxId = $(this).data("id");
  var tableTax = $('#tblDetalleImpuestosModal').DataTable();
  var tipo_documento = $("#cmbFacturarDesde").val();
  var id_document;
  var aux = $(this).closest('tr').index();
  var tasa = tableTax.row(aux).data()['tasa'];
  switch (tipo_documento) {
    case "1":
      id_document = $("#cmbCotizacion").val() !== "" && $("#cmbCotizacion").val() !== null ? $("#cmbCotizacion").val() : $("#txtCotizacion").val();
      cliente = $("#cmbCliente").val() !== "" && $("#cmbCliente").val() !== null ? $("#cmbCliente").val() : $("#txtCotizacion").val();
      break;
    case "2":
      id_document = $("#cmbVentaDirecta").val() !== "" && $("#cmbVentaDirecta").val() !== null ? $("#cmbVentaDirecta").val() : $("#txtVenta").val();
      cliente = $("#cmbCliente").val() !== "" && $("#cmbCliente").val() !== null ? $("#cmbCliente").val() : $("#txtVenta").val();
      break;
  }
 
  $.ajax({
    method: "POST",
    url: 'php/funciones.php',
    data: {
      clase: 'delete_data',
      funcion: 'delete_taxProducto',
      ref: id_document,
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

$(document).on("keyup","#txtTax",function(){
  value = $(this).val();
  data = $('input[type=radio][name=tipoImpuesto]').val();
  console.log(data);
  /*
  if($('#tipoImpuesto1').is(':checked')){
    data = 1;
  }
  if($('#tipoImpuesto1').is(':checked')){
    data = 2;
  }
  if($('#tipoImpuesto1').is(':checked')){
    data = 3;
  }
  */
  
  switch (data) {
    case 1:
      if($("#cmbImpuesto").val() !== 2){
        tasa_mayor = 150;
      } else {
        tasa_mayor = 100;
      }
      
        if(value > tasa_mayor){
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3100,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/warning_circle.svg",
            msg: "La tasa debe ser menor o igual al "+tasa_mayor+"%",
          });
          
          $(this).val(numeral("0").format("0"));
        } else if(value < 0){
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3100,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/warning_circle.svg",
            msg: text + "debe ser mayor o igual a 0",
          });
          $(this).val(numeral("0").format("0"));
        }
      
    break;
    case 2:
      $("#txtTax").on("keyup",function(){
        var num = $(this).val();
        if(num < 0){
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3100,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/warning_circle.svg",
            msg: "El monto debe ser mayor o igual a 0",
          });
          
          $("#txtDescuento").val(numeral("0").format("0"));
        }
      });
    break;
    case 3:
      if(value > 100){
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3100,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/warning_circle.svg",
          msg: "La tasa debe ser menor o igual al 100%",
        });
       
        
      } else if(value < 0){
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3100,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/warning_circle.svg",
          msg: text + " debe ser mayor o igual a 0",
        });
        
        
      }
    break;
  }
});

$(document).on("focus","input[type=text]",function(){
  $(this).select();
});
$(document).on("focus","input[type=number]",function(){
  $(this).select();
});

$(document).on("focusout","#txtDescuento",function(){
  var num = $(this).val();
  if($('#tipoDescuento1').is(':checked')){
    $(this).val(numeral(num).format("0"));
  }
  if($('#tipoDescuento2').is(':checked')){
    $(this).val(numeral(num).format("0,000,000.00"));
  }
});

$(document).on("click",'input[type=radio][name=tipoImpuesto]',function(){
  tipoImpuestos = $(this).val();
  
  loadCombo1("impuestos","#cmbImpuesto","","Seleccione un impuesto...",tipoImpuestos);
    
});

$(document).on("change","#cmbImpuesto",function(){
  impuesto = $(this).val();
 
  $.ajax({
    method: "POST",
    url: "php/funciones.php",
    data: {
      clase: 'get_data',
      funcion: 'get_factorImpuestos',
      value: impuesto
    },
    dataType: 'json',
    success: function(response){
      
      switch (response[0]['id']) {
      case 1:
        $("#txtLabelTax").html("Tasa:");
        $("#txtTax").val("0");
        
      break;
      case 2:
        $("#txtLabelTax").html("Cuota:");
        $("#txtTax").val("0.00");
      
      break;
      case 3:
        $("#txtLabelTax").html("Exento:");
        $("#txtTax").val("0");
      break;

    }
    },error:function(error){
      console.log(error);
    }
  });

});

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

$(document).on("focusout","#txtPrecioUnitario",function(){
  var num = $(this).val();
  $(this).val(numeral(num).format("0,000,000.00"));
});

$(document).on("keyup","#txtDescuento",function(){

  value = $(this).val();
  if($('#tipoDescuento1').is(':checked')){
    data = 1;
  }
  if($('#tipoDescuento2').is(':checked')){
    data = 2;
  }
  var regexp_numeric = /[^0-9:]/g;
  var regexp_decimal = /[^\d.]/g;
  
  if(data === 1){
    if(value > 100){
      Lobibox.notify("error", {
        size: "mini",
        rounded: true,
        delay: 3100,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../img/timdesk/warning_circle.svg",
        msg: "El descuento debe ser menor o igual al 100%",
      });
      
      if ($(this).val().match(regexp_numeric)) {
        $(this).val($(this).val().replace(regexp_numeric, ""));
        $(this).val(numeral(res['descuento']).format("0"));
      }
     
    } else if(value < 0){
      Lobibox.notify("error", {
        size: "mini",
        rounded: true,
        delay: 3100,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../img/timdesk/warning_circle.svg",
        msg: "El descuento debe ser mayor o igual al 0%",
      });
      
      if ($(this).val().match(regexp_numeric)) {
        $(this).val($(this).val().replace(regexp_numeric, ""));
        $(this).val(numeral(res['descuento']).format("0"));
      }
    } else{
      descuento = value / 10;
    }
      
  } else {
    if(value < 0){
      Lobibox.notify("error", {
        size: "mini",
        rounded: true,
        delay: 3100,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../img/timdesk/warning_circle.svg",
        msg: "El descuento debe ser mayor o igual a 0",
      });
      
      if ($(this).val().match(regexp_decimal)) {
        $(this).val($(this).val().replace(regexp_decimal, ""));
        $("#txtDescuento").val(numeral(res['descuento']).format("0,000,000.00"));
      }
      
    } else {
      descuento = value;
      
    }
  }
  
});

$(document).on("click","#tblDetalleProductos a",function(){
  
  var id = $(this).data('id');
  var ref = $(this).data('ref');
  
  $("#txtIdReferencia").val(ref);
  
  var tipo_doc = $("#cmbFacturarDesde").val();
  var doc = $("#txtDocumento").val();
  
  const tblProductos = $("#tblDetalleProductos").DataTable();
  var data = tblProductos.rows(ref).data();
  var indexTableProd = tblProductos.row(id).index();
  $("#rowIndex").val(indexTableProd);
  var idProducto = id;
  $("#txtIdProd").val(idProducto);

  validateSAT(idProducto);
  validateUnidadMedida(idProducto);
  loadDataProducto(idProducto,ref);

});

function loadSerieFolio(){
  $.ajax({
    method: "POST",
    url: "php/funciones.php",
    data: {
      clase: 'get_data',
      funcion: 'get_folioSerie'
    },
    dataType: 'json',
    success: function(respuesta){
      $("#txtSerie").val(respuesta['serie']);
      $("#txtFolio").val(respuesta['folio']);
    },error:function(error){
      console.log(error);
    }

  });
}

function loadSerieFolioPreinvoice(value){
  $.ajax({
    method: "POST",
    url: "php/funciones.php",
    data: {
      clase: 'get_data',
      funcion: 'get_folioSeriePreinvoice',
      value: value
    },
    dataType: 'json',
    success: function(respuesta){
      if( respuesta[0].folio_prefactura !== null &&  respuesta[0].folio_prefactura !== ""){

        //<a id='show_pdf_preinvoice' href='#' data-id='" . $r['id'] . "'><i class='far fa-file-pdf'></i></a>
        a = '<a class="btn-table-custom btn-table-custom--turquoise" href="#" id="show_pdf_preinvoice" data-id="' + value + '"><img style="width:1.5rem; vertical-align: top;" src="../../img/cotizaciones_nuevos_iconos/ICONO-DESCARGAR AZUL OSCURO NVO-01.svg"> Descargar</a>';
        folio_serie = "PRF " + respuesta[0].folio_prefactura + "<br>" + a;
        $("#preinvoice_exist_span").html(folio_serie);
        $("#preinvoice_exist").css("display","block");
      }
    },error:function(error){
      console.log(error);
    }

  });
}

var htmlSalidas = "";

//modificacion cuentas cobrar: al facturar una venta con pagos recibidos, recupera las ventas para saber si estan pagadas completas o parcialmente 
function get_ventasOrigen(salidas){
  $.ajax({
    method: "POST",
    url: "php/funciones.php",
    data: {
      clase: 'get_data',
      funcion: 'get_ventasOrigen',
      value: salidas
    },
    dataType: 'json',
    success: function(respuesta){
      
      if(respuesta['salidas'].length>0){
        buscaPagosVentas(respuesta['salidas']);
      }

      /* for (const property in respuesta['fs']) {        
        arrcuentasVentasFolioSalida[property] = respuesta['fs'][property];
      } */

      arrcuentasVentasTotalFolioSalida = {}

      for (const property in respuesta['totalFS']) {        
        arrcuentasVentasTotalFolioSalida[property] = respuesta['totalFS'][property];
      }

    },error:function(error){
      console.log(error);
      Lobibox.notify("error", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../img/timdesk/warning_circle.svg",
        msg: "Error al recuperar datos para validacion de ventas",
      })
    }
  });
}

//modificacion cuentas cobrar: recupera las ventas que tienen pagos (parciales o completos)
function buscaPagosVentas(ventas){
  ventas = JSON.stringify(ventas);
  $.ajax({
    method: "POST",
    url: "php/funciones.php",
    data: {
      clase: 'get_data',
      funcion: 'get_ventasPagos',
      value: ventas
    },
    dataType: 'json',
    success: function(respuesta){
      arrcuentasVentasEstatus = {}
      arrcuentasVentasFolios = {}
      arrcuentasVentasPagado = {}

      for (let i = 0; i<respuesta.length; i++) {
        !arrcuentasVentasEstatus.hasOwnProperty(respuesta[i]['PK']) ? arrcuentasVentasEstatus[respuesta[i]['PK']] = respuesta[i]['Estatus'] : null;
        !arrcuentasVentasFolios.hasOwnProperty(respuesta[i]['PK']) ? arrcuentasVentasFolios[respuesta[i]['PK']] = respuesta[i]['Folio'] : null;
        !arrcuentasVentasPagado.hasOwnProperty(respuesta[i]['PK']) ? arrcuentasVentasPagado[respuesta[i]['PK']] = respuesta[i]['Pagado'] : null;
      }

    },error:function(error){
      console.log(error);
    }
  });
}

$(document).on("click",function(event){
  if(!$(event.target).closest(".select-timlid").length){
    $(".select-arrow>span").addClass("select-arrow-down");
    $(".select-arrow>span").removeClass("select-arrow-up");
    $(".select-content").removeClass("select-open");
  } 

});

$(document).on("click","#cargarProductosPedidos",function(){
  
  if(optionSalidas.length > 0){
    $("#loader1").addClass("loader");
    $("#notas_pie").css("display","none");
    $("#notas_cliente").css("display","none");
    $("#notas_pie").css("display","none");
    $("#notas_vendedor").css("display","none");
    var value = JSON.stringify(optionSalidas);
    get_ventasOrigen(value);
    $.ajax({
      method: "POST",
      url: "php/funciones.php",
      data: {
        clase: 'get_data',
        funcion: 'get_productosSalidas',
        value: value
      },
      dataType: 'json',
      success: function(respuesta){
        
        $.ajax({
          method: "POST",
          url: 'php/funciones.php',
          data: {
            clase: 'get_data',
            funcion: 'get_ordenPedido',
            value: value
          },
          datatype: "json",
          success: function(respuesta){
            $(".loader1").fadeOut("slow");
            $("#loader1").removeClass("loader");
            var res1 = JSON.parse(respuesta);
            is_customer_for_billing(res1[0].PKCliente, res1[0].rfc, res1[0].regimen_fiscal_id);
            
            $('.cabecera-cliente').css("display","block");
            $('.cuerpo').css("display","block");
            $("#nofacturar").html("Referencia:<br>"+ref);
            $("#razon_social").html(res1[0].razon_social);
            $("#rfc").html(res1[0].rfc);
            $("#totalFacturar").css("display","none");
            loadSubtotal(value,3);

            if(res1[0].nota_cliente !== "" && res1[0].nota_cliente !== null){
              $("#txaNotasCliente").val(res1[0].nota_cliente);
              $("#notas_pie").css("display","block");
              $("#notas_cliente").css("display","block");
            }
            
            if(res1[0].nota_internas !== "" && res1[0].nota_internas !== null){
              $("#txaNotasVendedor").val(res1[0].nota_internas);
              $("#notas_pie").css("display","block");
              $("#notas_vendedor").css("display","block");
            }

          },
          error: function(error){
            console.log(error);
          }
        
        });
        
        var ref = "";
        ref = optionSalidas.join(", ");
        $("#nofacturar").html("Referencia:<br>"+ref);

        var idTable = "#tblDetalleProductos";
        var func = "get_productosSalidas";
        
        var rowCountProduct = $(idTable+">tbody>tr");
        data = JSON.stringify(optionSalidas)
        if(rowCountProduct.length > 0){
          $(idTable).DataTable().clear().destroy();
          loadDataTableProducts(idTable,func,data);
          
        } else {
          loadDataTableProducts(idTable,func,data);
          
        }
        a = '<span class="select-disabled">Seleccione un pedido...</span>';

      },error:function(error){
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
      msg: "No ha selecciona al menos una salida",
    })
  }
});

function truncateTablePRoducts(ref,type){
  $.ajax({
    method: "POST",
    url: "php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_truncateTableProducts",
      value: 0,
      type:type,
      ref: ref
    },
    success: function(){
    },
    error: function(error){
      console.log(error);
    }
  });
}

$(document).on("click","#chkPredial",function(){
  if($(this).is(':checked')){
    $("#predial").css("display","block");
  } else {
    $("#predial").css("display","none");
  }
});

function loadSubtotal(ref,type){
  
  $.ajax({
    method: "POST",
    url: "php/funciones.php",
    data: {
      clase: 'get_data',
      funcion: 'get_totalSubtotalSalidas',
      value: 0,
      type:type,
      ref: ref
    },
    asyn:false,
    dataType: 'json',
    success: function(respuesta){
      var res = respuesta;
      $('#subtotal').html(res.subtotal);
      $('#impuestos').html(res.impuestos);
      $('#total').html(res.total);
      $('.subtotal').css("display","block");
      $('.impuestos').css("display","block");
      $('.total').css("display","block");

    },error: function(error){
      console.log(error);
    }

  });
}

$(document).on("click","#verPrefactura",function(){
  arr = [];
  data = $("#tblDetalleProductos").DataTable();
  aux = data.rows().data();
  
  $.redirect(
    'ver_prefactura.php', 
    {
      'folio': $("#txtFolio").val(),
      'serie':$("#txtSerie").val(),
      'razon_social':$("#razon_social").text(),
      'fecha':$("#txtFechaEmision").val(),
      'cfdi':$("#cmbUsoCFDI option:selected").text(),
      'forma_pago':$("#cmbFormasPago option:selected").text(),
      'metodo_pago':$("#cmbMetodoPago option:selected").text(),
      'moneda':$("#cmbMoneda option:selected").text(),
      'rfc':$("#rfc").text(),
      'productos':aux.toArray(),
      'subtotal':$("#subtotal").text(),
      'impuestos':$("#impuestos").html(),
      'total':$("#total").text()
    },
    "POST",
    "_blank"
  );
});

$(document).on("click","#btnDescargarPrefactura",function(){
  arr = [];
  data = $("#tblDetalleProductos").DataTable();
  aux = data.rows().data();
  
  $.redirect(
    'php/download_prefactura.php', 
    {
      'folio': $("#txtFolio").val(),
      'serie':$("#txtSerie").val(),
      'razon_social':$("#razon_social").text(),
      'fecha':$("#txtFechaEmision").val(),
      'cfdi':$("#cmbUsoCFDI option:selected").text(),
      'forma_pago':$("#cmbFormasPago option:selected").text(),
      'metodo_pago':$("#cmbMetodoPago option:selected").text(),
      'moneda':$("#cmbMoneda option:selected").text(),
      'rfc':$("#rfc").text(),
      'productos':aux.toArray(),
      'subtotal':$("#subtotal").text(),
      'impuestos':$("#impuestos").html(),
      'total':$("#total").text()
    },
    "POST",
    "_blank"
  );
})


$(document).on("click","#chkPagoContado",function(){
  if($(this).is(":checked")){
    loadCombo1("cuentasBancarias","#cmbCuentaBancaria","","Seleccione una cuenta bancaria...");
    $("#comboCuentaBancaria").css("display","block");
    $("#cmbCuentaBancaria").prop('required',true);
    $("#txaReferencia").val('');
  } else {
    loadCombo1("cuentasBancarias","#cmbCuentaBancaria","","Seleccione una cuenta bancaria...");
    $("#comboCuentaBancaria").css("display","none");
    $("#cmbCuentaBancaria").prop('required',false);
    $("#invalid-cuentaBancaria").css("display", "none");
    $("#cmbCuentaBancaria").removeClass("is-invalid");
    $("#txaReferencia").val('');
  }
});

$(document).on("click","#chkFechaVencimiento",function(){
  if($(this).is(":checked")){
    $("#txtFechaVencimiento").css("display","block");
    $("#txtFechaVencimiento").prop('required',true);
  } else {
    $("#txtFechaVencimiento").css("display","none");
    $("#txtFechaVencimiento").prop('required',false);
    $("#invalid-fechaVencimiento").css("display", "none");
    $("#txtFechaVencimiento").removeClass("is-invalid");
  } 
});

$(document).on("click","#chkAddressInvoice",function(){
  if($(this).is(":checked")){
    $("#comboAddressInvoice").css("display","block");
    $("#cmbAddressInvoice").prop('required',true);
  } else {
    $("#comboAddressInvoice").css("display","none");
    $("#cmbAddressInvoice").prop('required',false);
    $("#invalid-addressInvoice").css("display", "none");
    $("#cmbAddressInvoice").removeClass("is-invalid");
  }
})

$(document).on("change","#cmbFormasPago",function(){
  if(parseInt($(this).val()) === 22){
    $("#chkPagoContado").attr("disabled",true);
  } else {
    $("#chkPagoContado").attr("disabled",false);
  }
});

$(document).on("click","#divChkPagoContado",function(){
  if($("#chkPagoContado").prop("disabled")){
	if($("#chkPrefactura").is(":checked")){
      msj = "Se ha selecciona la opción de prefactura";
    } else {
      msj = "Debe de elegir una forma de pago diferente"
    }    
    Lobibox.notify("error", {
      size: "mini",
      rounded: true,
      delay: 3100,
      delayIndicator: false,
      position: "center top",
      icon: true,
      img: "../../img/timdesk/warning_circle.svg",
      msg: "Debe de elegir una forma de pago diferente",
    })
  }

});

$(document).on("click","#chkPrefactura",function(){
  if($(this).is(":checked")){
    $("#chkPagoContado").attr("disabled",true);
    $("#chkFechaVencimiento").attr("disabled",true);
  } else {
    $("#chkPagoContado").attr("disabled",false);
    $("#chkFechaVencimiento").attr("disabled",false);
  }
});

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

  function checkStockAllProduct()
  {
    const table = $("#tblDetalleProductos").DataTable();
    var pos = table.rows().data();
    var sucursal = $("#cmbSucursales").val();
    var ban = 0;
    var products_id = [];
    for (let i = 0; i < pos.length; i++) {
      products_id.push({"id":pos[i].id,"cant": pos[i].cantidad});
    }
    
    $.ajax({
      url: "php/funciones.php",
      method: "post",
      data: {
        clase: "get_data",
        funcion: "get_ifProductHasStock",
        value:products_id,
        value1: sucursal
      },
      dataType: 'json',
      success: function(response){
        if(response > 0){
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 4000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/warning_circle.svg",
            msg: "No hay existencias de uno de los productos",
          });
        } else {
          AgregaFactura(0);
        }
      },
      error:function(error){
        console.log(error);
      }
    })
  }

function validatePayment_form(payment_form,payment_method)
{
let ban = false;
if(payment_method === 'PPD')
{
    if(parseInt(payment_form) === 22)
    {
    ban = true;
    } else {
    bar = false;
    }
} else {
    ban = true;
}

return ban;
}