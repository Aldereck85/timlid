var _global = {
  cliente: 0,
  proveedor: 0,
  sucOrigen: 0
}

$(document).ready(function () {
  if (tipoE == 1){
    $('.data-container .directBranch-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
    $('.data-container .directProvider-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
    $('.data-container .directCustomer-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
    $('.data-container .transfer-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
    $('.data-container .purchases-disabled').css({'display':'inline-block','visibility': 'visible','opacity': '1','animation': 'fade 1s'});

    $.ajax({
      url: "../../php/funciones.php",
      data: { clase: "get_data", funcion: "get_DataEntryOC", data: folio },
      dataType: "json",
      success: function (data) {
        $("#cmbOrdenCompra").val(data[0].referenciaOC);
        $("#cmbProveedor").val(data[0].proveedor);
        $("#cmbSucursal").val(data[0].sucursal);
        $("#txtNoDocumento").val(data[0].folioFactura);
        $("#txtSerie").val(data[0].serieFactura);
        $("#txtSubtotal").val(data[0].subtotal);
        $("#txtIva").val(data[0].iva);
        $("#txtIEPS").val(data[0].ieps);
        $("#txtImporte").val(data[0].importe);
        $("#txtDescuento").val(data[0].descuento);
        $("#txtFechaFactura").val(data[0].fechaFactura);
  
        if(data[0].isRemision == "1"){
          $("#cbxRemision").prop("checked", true);
        }else{
          $("#cbxRemision").prop("checked", false);
        }
  
        $("#txaNotaEntrada").val(data[0].notas);
        $("#txtReferenciaP").html(data[0].referenciaOC);
        $("#txtProveedorP").html(data[0].proveedor);
        $("#txtFechaEmisionP").html(data[0].fechaCreacionOC);
        $("#txtFechaEstimadaP").html(data[0].fechaEstimadaOC);
        $("#NotasInternasP").html(data[0].notasInternasOC);
        
        $('#txtDireccionP').html("Sucursal:"+data[0].sucursal +". "+
                              data[0].Calle +" "+
                              data[0].NumExt +", "+
                              data[0].Prefijo +", "+
                              data[0].NumInt +", "+
                              data[0].Colonia +", "+
                              data[0].Municipio +", "+
                              data[0].Estado +", "+
                              data[0].Pais +" ");
        
        loadProductosOrderPurchase(data[0].PKOrdenCompra, folio);
      },
    });
  }else if (tipoE == 2){
    $('.data-container .directBranch-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
    $('.data-container .directProvider-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
    $('.data-container .directCustomer-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
    $('.data-container .purchases-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
    $('.data-container .transfer-disabled').css({'display':'inline-block','visibility': 'visible','opacity': '1','animation': 'fade 1s'});

    $.ajax({
      url: "../../php/funciones.php",
      data: { clase: "get_data", funcion: "get_DataEntryTransfer", data: folio },
      dataType: "json",
      success: function (data) {
        $("#cmbOrderPedido").val(data[0].pedido);
        $("#cmbSucursalDestino").val(data[0].sucursalD);
        $("#cmbSucursalOrigen").val(data[0].sucursalO);
        
        loadProductosTransfer(data[0].ordenId, folio);        
      },
    });
  }else if (tipoE == 4){
    if (_global.sucOrigen != 0) {
      $('.data-container .purchases-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
      $('.data-container .directProvider-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
      $('.data-container .directCustomer-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
      $('.data-container .transfer-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
      $('.data-container .directBranch-disabled').css({'display':'inline-block','visibility': 'visible','opacity': '1','animation': 'fade 1s'});
    
      deleteEntradaDirectaTemp();
      cargarDatosEntradaDirecta(folio);
    }else if (_global.proveedor != 0) {
      $('.data-container .purchases-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
      $('.data-container .directBranch-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
      $('.data-container .directCustomer-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
      $('.data-container .transfer-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
      $('.data-container .directProvider-disabled').css({'display':'inline-block','visibility': 'visible','opacity': '1','animation': 'fade 1s'});
    
      new SlimSelect({
        select: '#cmbTipoProviderED',
        deselectLabel: '<span class="">✖</span>',
      });  

      deleteEntradaDirectaTemp();
      cargarDatosEntradaDirectaProvider(folio);
    }else if (_global.cliente != 0) {
      $('.data-container .purchases-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
      $('.data-container .directBranch-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
      $('.data-container .directProvider-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
      $('.data-container .transfer-disabled').css({'display':'none','opacity': '0','visibility': 'hidden'});
      $('.data-container .directCustomer-disabled').css({'display':'inline-block','visibility': 'visible','opacity': '1','animation': 'fade 1s'});
    
      deleteEntradaDirectaTemp();
      cargarDatosEntradaDirectaCustomer(folio);
    }
  }
});

function loadProductosOrderPurchase(pkOrden, folio){
  $("#tblProductosEntradaOC").DataTable().destroy();
  $("#tblProductosEntradaOC").dataTable({
    language: setFormatDatatables(),
    dom: "Bfrtip",
    buttons: [
    ],
    scrollX: true,
    lengthChange: false,
    info: false,
    ajax: {
      url: "../../php/funciones.php",
      data: { clase: "get_data", funcion: "get_productosEntradaOCTable", data:folio },
    },
    //"pageLength": 20,
    "paging": false,
    "order": [ [ 0, "desc" ], [ 1, "desc" ] ],
    columns: [
      { data: "Id" },
      { data: "Producto" },
      { data: "CantidadRecibida" },
      { data: "UnidadMedida" },
      { data: "Lote" },
      { data: "Serie" },
      { data: "FechaCaducidad" },
      { data: "PrecioUnitario" },
      { data: "Impuestos" },
      { data: "Importe" }
    ],
    columnDefs: [
      { orderable: false, targets: 0, visible: false },
      { orderable: false, targets: 8, visible: false },
    ],
  });

  obtenerTotalOC(folio); 
}

function loadProductosTransfer(pedidoId, folio){
  $("#tblProductosTraspaso").DataTable().destroy();
  $("#tblProductosTraspaso").dataTable({
    language: setFormatDatatables(),
    dom: "Bfrtip",
    buttons: [
    ],
    scrollX: true,
    lengthChange: false,
    info: false,
    ajax: {
      url: "../../php/funciones.php",
      data: { clase: "get_data", funcion: "get_productosEntradaTransferTable", data:folio },
    },
    //"pageLength": 20,
    "paging": false,
    "order": [ [ 0, "desc" ], [ 1, "desc" ] ],
    columns: [
      { data: "Id" },
      { data: "Producto" },
      { data: "CantidadRecibida" },
      { data: "UnidadMedida" },
      { data: "Lote" },
      { data: "Serie" },
      { data: "FechaCaducidad" }
    ],
    columnDefs: [
      { orderable: false, targets: 0, visible: false },
    ],
  });

  obtenerTotalTransfer(folio); 
}

function setFormatDatatables() {
  var idioma_espanol = {
    sProcessing: "Procesando...",
    sZeroRecords: "No se encontraron resultados",
    sEmptyTable: "Ningún dato disponible en esta tabla",
    sSearch: "<img src='../../../../img/timdesk/buscar.svg' width='20px' />",
    sLoadingRecords: "Cargando...",
    searchPlaceholder: "Buscar...",
    oPaginate: {
      sFirst: "",
      sLast: "",
      sNext: "<img src='../../../../img/icons/pagination.svg' width='20px'/>",
      sPrevious:
        "<img src='../../../../img/icons/pagination.svg' width='20px' style='transform: scaleX(-1)'/>",
    },
  };
  return idioma_espanol;
}

function obtenerTotalOC(folio){
  //Obtener total
  $.ajax({
    
    url:"../../php/funciones.php",
    data:{clase:"get_data", funcion:"get_totalEntradaOCVer",datos:folio},
    dataType:"json",
    success:function(respuesta){
      $('#Total').html(respuesta[0].Total)
    },
    error:function(error){
      console.log(error);
    }
  });
}

function obtenerTotalTransfer(folio){
  //Obtener total
  $.ajax({
    
    url:"../../php/funciones.php",
    data:{clase:"get_data", funcion:"get_totalEntradaTranferVer",datos:folio},
    dataType:"json",
    success:function(respuesta){
      $('#TotalTras').html(respuesta[0].Total)
    },
    error:function(error){
      console.log(error);
    }
  });
}

function dosDecimales(n) {
  return Number.parseFloat(n)
    .toFixed(2)
    .replace(/\d(?=(\d{3})+\.)/g, "$&,");
}

function deleteEntradaDirectaTemp(){
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "delete_data",
      funcion: "delete_datosEntradaDirectaTemp"
    },
    dataType: "json",
    success: function (respuesta) {

      if (respuesta[0].status) {
      } else {
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function cargarDatosEntradaDirecta(folio){
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_DataEntryDirectEdit", data: folio },
    dataType: "json",
    success: function (data) {
      $("#txtRefereciaEDBranch").val(data[0].referencia);
      $("#txtSucDestinoEDBranch").val(data[0].sucursalD);
      $("#txtOrigenEDBranch").val(data[0].sucursalO);
      $("#txtNotasEDBranch").val(data[0].notas);
      
      saveEntradaDirectTemp(folio);   

      _global.referencia = data[0].referencia;
    },
  });
}

function cargarDatosEntradaDirectaProvider(folio){
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_DataEntryDirectProviderEdit", data: folio },
    dataType: "json",
    success: function (data) {
      $("#txtRefereciaEDProvider").val(data[0].referencia);
      $("#txtSucDestinoEDProvider").val(data[0].sucursalD);
      $("#txtProveedorEDProvider").val(data[0].proveedor);
      $("#txtNotasEDProvider").val(data[0].notas);
      $("#txtSerieProviderED").val(data[0].serie);
      $("#txtNoDocumentoProviderED").val(data[0].folio);
      $("#txtSubtotalProviderED").val(data[0].subtotal);
      $("#txtIvaProviderED").val(data[0].iva);
      $("#txtIEPSProviderED").val(data[0].ieps);
      $("#txtImporteProviderED").val(data[0].importe);
      $("#txtFechaFacturaProviderED").val(data[0].fechaFactura);

      loadTypeEntryDirectProvider(data[0].tipo,"cmbTipoProviderED");
      
      saveEntradaDirectTempProvider(folio);   

      _global.referencia = data[0].referencia;
    },
  });
}

function cargarDatosEntradaDirectaCustomer(folio){
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_DataEntryDirectCustomerEdit", data: folio },
    dataType: "json",
    success: function (data) {
      $("#txtRefereciaEDCustomer").val(data[0].referencia);
      $("#txtSucDestinoEDCustomer").val(data[0].sucursalD);
      $("#txtClienteEDCustomer").val(data[0].cliente);
      $("#txtNotasEDCustomer").val(data[0].notas);
      
      saveEntradaDirectTempCustomer(folio);   

      _global.referencia = data[0].referencia;
    },
  });
}

function saveEntradaDirectTempProvider(folio){
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "save_data",
      funcion: "save_entry_directProviderTempTableEdit",
      data:folio
    },
    dataType: "json",
    success: function (respuesta) {
      console.log(respuesta);
      if (respuesta[0].status) {
        
        loadProductosDProvider(folio);
        
      } else {
       
      }
    },
    error: function (error) {
      console.log(error);
      
    },
  });
}

function saveEntradaDirectTemp(folio){
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "save_data",
      funcion: "save_entry_directTempTableEdit",
      data:folio
    },
    dataType: "json",
    success: function (respuesta) {
      console.log(respuesta);
      if (respuesta[0].status) {
        
        loadProductosDBranch(folio);
        
      } else {
       
      }
    },
    error: function (error) {
      console.log(error);
      
    },
  });
}

function saveEntradaDirectTempCustomer(folio){
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "save_data",
      funcion: "save_entry_directTempTableEdit",
      data:folio
    },
    dataType: "json",
    success: function (respuesta) {
      console.log(respuesta);
      if (respuesta[0].status) {
        
        loadProductosDCustomer(folio);
        
      } else {
       
      }
    },
    error: function (error) {
      console.log(error);
      
    },
  });
}

function loadProductosDBranch(folio){
  $("#tblProductosEntradaDCSucursal").DataTable().destroy();
  $("#tblProductosEntradaDCSucursal").dataTable({
    language: setFormatDatatables(),
    dom: "Bfrtip",
    buttons: [
    ],
    scrollX: true,
    lengthChange: false,
    info: false,
    ajax: {
      url: "../../php/funciones.php",
      data: { clase: "get_data", funcion: "get_productosEntradaDirectaTempTableNoEdit", data: 0},
    },
    "paging": false,
    "order": [ [ 1, "desc" ] ],
    columns: [
      { data: "Id" },
      { data: "Producto" },
      { data: "Cantidad" },
      { data: "UnidadMedida" },
      { data: "Lote" },
      { data: "Serie" },
      { data: "FechaCaducidad" },
      { data: "Acciones" }
    ],
    columnDefs: [
      { orderable: false, targets: 0, visible: false },
      { orderable: false, targets: 1, width: '15%' },
      { orderable: false, targets: 2, width: '15%' },
      { orderable: false, targets: 3, width: '20%' },
      { orderable: false, targets: 4, width: '15%' },
      { orderable: false, targets: 5, width: '15%' },
      { orderable: false, targets: 6, width: '15%' },
      { orderable: false, targets: 7, width: '5%' },
    ],
  });
}

function loadProductosDProvider(folio){
  $("#tblProductosEntradaDProvider").DataTable().destroy();
  $("#tblProductosEntradaDProvider").dataTable({
    language: setFormatDatatables(),
    dom: "Bfrtip",
    buttons: [
    ],
    scrollX: true,
    lengthChange: false,
    info: false,
    ajax: {
      url: "../../php/funciones.php",
      data: { clase: "get_data", funcion: "get_productosEntradaDirectaTempTableProviderNoEdit", data: 0},
    },
    "paging": false,
    "order": [ [ 1, "desc" ] ],
    columns: [
      { data: "Id" },
      { data: "Producto" },
      { data: "Cantidad" },
      { data: "UnidadMedida" },
      { data: "Precio" },
      { data: "Lote" },
      { data: "Serie" },
      { data: "FechaCaducidad" },
      { data: "Impuestos" },
      { data: "Importe" },
      { data: "Acciones" }
    ],
    columnDefs: [
      { orderable: false, targets: 0, visible: false },
      { orderable: false, targets: 1, width: '12%' },
      { orderable: false, targets: 2, width: '7%' },
      { orderable: false, targets: 3, width: '12%' },
      { orderable: false, targets: 4, width: '7%' },
      { orderable: false, targets: 5, width: '12%' },
      { orderable: false, targets: 6, width: '12%' },
      { orderable: false, targets: 7, width: '10%' },
      { orderable: false, targets: 8, width: '14%' },
      { orderable: false, targets: 9, width: '7%' },
      { orderable: false, targets: 10, width: '7%' },
    ],
  });

  obtenerTotalEDProvider();
}

function loadProductosDCustomer(folio){
  $("#tblProductosEntradaDCCliente").DataTable().destroy();
  $("#tblProductosEntradaDCCliente").dataTable({
    language: setFormatDatatables(),
    dom: "Bfrtip",
    buttons: [
    ],
    scrollX: true,
    lengthChange: false,
    info: false,
    ajax: {
      url: "../../php/funciones.php",
      data: { clase: "get_data", funcion: "get_productosEntradaDirectaTempTableCustomerNoEdit", data: 0},
    },
    "paging": false,
    "order": [ [ 1, "desc" ] ],
    columns: [
      { data: "Id" },
      { data: "Producto" },
      { data: "Cantidad" },
      { data: "UnidadMedida" },
      { data: "Lote" },
      { data: "Serie" },
      { data: "FechaCaducidad" },
      { data: "Acciones" }
    ],
    columnDefs: [
      { orderable: false, targets: 0, visible: false },   
      { orderable: false, targets: 1, width: '15%' },
      { orderable: false, targets: 2, width: '15%' },
      { orderable: false, targets: 3, width: '20%' },
      { orderable: false, targets: 4, width: '15%' },
      { orderable: false, targets: 5, width: '15%' },
      { orderable: false, targets: 6, width: '15%' },
      { orderable: false, targets: 7, width: '5%' },
    ],
  });
}

function loadTypeEntryDirectProvider(data,input){
  var html ='<option value="" selected>Seleccione un tipo...</option>';

  $.ajax({
    url:"../../php/funciones.php",
  	data:{clase:"get_data", funcion:"get_cmb_tipo_directEntry_Provider_ED"},
  	dataType:"json",
    success:function(respuesta){
      console.log("respuesta:",respuesta);

      if(respuesta !== "" && respuesta !== null){
        $.each(respuesta,function(i){
          if(data === respuesta[i].PKTipo){
            selected = 'selected';
          }else{
            selected = '';
          }
          html += '<option value="'+respuesta[i].PKTipo+'" '+selected+'>'+respuesta[i].Tipo+'</option>';
        });

      }else{
        html += '<option value="vacio">No hay tipos que mostrar</option>';
      }

      $('#'+input+'').html(html);
    },
    error:function(error){
      console.log(error);
    }

  });

  $('#'+input+'').html(html);
}

function obtenerTotalEDProvider(){
  //Obtener subtotal
  $.ajax({
    
    url:"../../php/funciones.php",
    data:{clase:"get_data", funcion:"get_subTotalEntradaEDProviderTemp"},
    dataType:"json",
    success:function(respuesta){
      $('#Subtotal').html(dosDecimales(respuesta[0].subtotal))
    },
    error:function(error){
      console.log(error);
    }
  });

  var html='';
  $('#impuestos').html(html);
  //Obtener impuestos
  $.ajax({
    
    url:"../../php/funciones.php",
    data:{clase:"get_data", funcion:"get_impuestoEntradaEDProviderTemp"},
    dataType:"json",
    success:function(respuesta){
      //Recorrer las respuestas de la consulta
      var tasa = '';
      $.each(respuesta,function(i){    
        if (respuesta[i].tipoImpuesto == "1"){
          tasa = respuesta[i].tasa;
        }else{
          tasa = respuesta[i].tasa + "%"
        }
        if(!$('#impuestos-head-'+respuesta[i].id).length){
        html += '<tr>'+
                  //'<th style="text-align: right;">'+respuesta[i].producto+'</th>'+
                  '<td style="text-align: right;" id="impuestos-head-'+respuesta[i].id+'">'+respuesta[i].nombre+'</td>'+
                  '<td style="text-align: right;">'+tasa+' </td>'+
                  '<td style="text-align: right;">.....</td>'+
                  '<td style="text-align: right;"> $ '+ dosDecimales(respuesta[i].totalImpuesto) +'</td>'+
                '</tr>';
        }
         

      });
      
      $('#impuestosEDProvider').html(html);
      
    },
    error:function(error){
      console.log(error);
    }
  });

  var importe = 0;
  if($("#txtImporteProviderED").val() != ''){
    importe = $("#txtImporteProviderED").val();
  }else{
    importe = 0;
  }
  //Obtener total
  $.ajax({
    
    url:"../../php/funciones.php",
    data:{clase:"get_data", funcion:"get_totalEntradaEDProviderTemp"},
    dataType:"json",
    success:function(respuesta){
      $('#TotalEDProvider').html(dosDecimales(respuesta[0].Total))

      if(dosDecimales(importe) != dosDecimales(respuesta[0].Total)){
        $("#invalid-totalED").css("display", "block");
        $("#TotalEDProvider").addClass("is-invalid");
        $("#invalid-importeProviderED").css("display", "block");
        $("#importeProviderED").addClass("is-invalid");

        var diferencia = respuesta[0].Total - importe;
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 5000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/notificacion_error.svg",
          msg: "¡El total de la entrada y el importe de la factura posee una diferncia de: $"+ dosDecimales(Math.abs(diferencia)) +"!",
          sound: '../../../../../sounds/sound4'
        });
      }else{
        $("#invalid-totalED").css("display", "none");
        $("#TotalEDProvider").removeClass("is-invalid");
        $("#invalid-importeProviderED").css("display", "none");
        $("#importeProviderED").removeClass("is-invalid");
      }
    },
    error:function(error){
      console.log(error);
    }
  });
}