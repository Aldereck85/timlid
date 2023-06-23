
document.addEventListener("DOMContentLoaded", function () {

  txtPrecioUpdateCompra = document.getElementById("txtUpdatePrecioCompra");
  chkPrecioUpdateCompraNeto = document.getElementById("chkUpdatePrecioCompraNeto");
  txtPrecioUpdateCompraSinImpuestos = document.getElementById("txtUpdatePrecioCompraSinImpuestosValue");
  txtUpdateUtilidades = document.getElementsByName("txtUpdateUtilidad");
  txtUpdatePrecioVentaNeto = document.getElementsByName("txtUpdatePrecioVentaNeto");

  document.getElementById("txtUpdatePrecioCompra").addEventListener("keyup", function() {
    ini_value = this.value;
    if(chkPrecioUpdateCompraNeto.checked){
      if(table_tax_update.data().count()){
      } else {
        txtPrecioUpdateCompraSinImpuestos.value = Math.floor(ini_value*100)/100;
      }
    } else {
      txtPrecioUpdateCompraSinImpuestos.value = Math.floor(ini_value*100)/100;
      
    }
    checkUpdateUtilitiesVoid(txtUpdateUtilidades);
  });

  // Cambia precios en cada utilidad según precio de compra y valor agregado en los campos 
  for(i = 0; i < txtUpdateUtilidades.length; i++){
    txtUpdateUtilidades[i].addEventListener("keyup", function() {
    var id_input_text = this.getAttribute('id');
    var id_input = document.getElementById(this.getAttribute('id'));
    row_count_table = table_tax_update.rows().count();
    value = txtPrecioUpdateCompra.value;
    value1 = id_input.value;

    last_id_text = id_input_text.substr(-1);
    input_sale = document.getElementById("txtUpdatePrecioVenta" + last_id_text);
    input_net_sale = document.getElementById("txtUpdatePrecioVentaNeto" + last_id_text);
    price = parseFloat(value) + (parseFloat(value) * (parseFloat(value1) / 100));
    
    if(chkPrecioUpdateCompraNeto.checked){
      input_sale.value = numeral(getUpdatePriceWithTax(price)).format('0.00');
      input_net_sale.value = numeral(price).format('0.00');
    } else {
      input_sale.value = numeral(price).format('0.00');
      input_net_sale.value = numeral(getUpdatePriceWithOutTax(price)).format('0.00');
    }
    });
  }

  for (let i = 0; i < txtUpdatePrecioVentaNeto.length; i++) {
    txtUpdatePrecioVentaNeto[i].addEventListener("keyup", (e) =>{
      var input_ = e.target;
      var id_input_text = input_.getAttribute('id');
      var id_input = document.getElementById(input_.getAttribute('id'));
      var id_input1 = document.getElementById("id_input1")
      value = txtPrecioUpdateCompraSinImpuestos.value;
      value1 = id_input.value;

      last_id_text = id_input_text.substr(-1);
      input_sale = document.getElementById("txtUpdatePrecioVenta" + last_id_text);
      input_utilities = document.getElementById("txtUpdateUtilidad" + last_id_text);

      if(chkPrecioUpdateCompraNeto.checked){
        
        input_sale.value = numeral(getUpdatePriceWithTax(parseFloat(id_input.value))).format('0.00');
        console.log(value);
        input_utilities.value = numeral(((input_sale.value/value) - 1) * 100).format('0.00');
      } else {
        input_sale.value = numeral(getUpdatePriceWithTax(parseFloat(id_input.value))).format('0.00');
        input_utilities.value = numeral(((input_sale.value/value) -1) * 100).format('0.00');
      }
    });
    
  }

  document.getElementById("chkUpdatePrecioCompraNeto").addEventListener("click", function() {
    
    value = txtPrecioUpdateCompra.value;
    value_neto = value;   
    row_count_table = table_tax_update.rows().count();
    if(chkPrecioUpdateCompraNeto.checked){
      txtPrecioUpdateCompraSinImpuestos.value = Math.floor(getUpdatePriceWithTax(value)*100)/100;
      checkUpdateUtilitiesVoid(txtUpdateUtilidades);
    } else {
      txtPrecioUpdateCompraSinImpuestos.value = Math.floor(value*100)/100;
      checkUpdateUtilitiesVoid(txtUpdateUtilidades);
    }
  });

  

  document.getElementById("chkLote").checked === true || document.getElementById("chkSerie").checked ? document.getElementById('button-add-exist').classList.remove('no-visible') : document.getElementById('button-add-exist').classList.add('no-visible');
});

document.getElementById("btnUpdateProductModal").addEventListener("click", (e) => {
  var product_id = document.getElementById("txtProductUpdateId").value;
  $("#loader2").css("display","block");
  $("#loader2").addClass("loader");
  if($(".form-data-update-product")[0].checkValidity())
  {
    bad_productType = 
      $("#invalid-productUpdateType").css("display") === "block" ? false : true;
    bad_productKey =
      $("#invalid-productUpdateKey").css("display") === "block" ? false : true;
    bad_productName =
      $("#invalid-productUpdateName").css("display") === "block" ? false : true;
    bad_productCategory =
      $("#invalid-productUpdateCategory").css("display") === "block" ? false : true;
    bad_productBrand = 
      $("#invalid-productUpdateBrand").css("display") === "block" ? false : true;
    bad_productPurchasePrice = 
      $("#invalid-productUpdatePurchasePrice").css("display") === "block" ? false : true;
    bad_productUtility = 
      $("#invalid-productUpdateUtility").css("display") === "block" ? false : true;
    bad_productServiceKey = 
      $("#invalid-productUpdateServiceKey").css("display") === "block" ? false : true;
    bad_productUnitKey = 
      $("#invalid-productUpdateUnitKey").css("display") === "block" ? false : true;

    if(
      bad_productType &&
      bad_productKey &&
      bad_productName &&
      bad_productCategory &&
      bad_productBrand &&
      bad_productPurchasePrice &&
      bad_productUtility &&
      bad_productServiceKey &&
      bad_productUnitKey
    )
    {
      general_data = getGeneralUpdateData();
      tax_product = getTaxUpdateProduct(product_id);
      fiscal_data = getFiscalUpdateData();
      
      json = 
      '{' +
          '"data" : [{' + general_data + '},{' + tax_product + '},{' + fiscal_data + '}]' +
      '}';
        
      $.ajax({
        method: "POST",
        url: "php/funciones.php",
        data: {
          clase:'update_data',
          funcion:'update_product',
          value:json,
          value1:product_id
        },
        dataType: 'json',
        success: function(respuesta){
          tblProductsFinder.ajax.reload();
          const file = document.querySelector('#flUpdateUploadImage').files[0];
          var caja_id = document.getElementById("txtCashRegisterId").value;
          
          if(file !== null && file !== "" && file !== undefined){
            if(file.name !== "agregar.svg")
            {
              var form = new FormData();
              form.append("id",product_id);
              form.append("image", file);
              
              $.ajax({
                method: "POST",
                url: "php/upload_img.php",
                data: form,
                cache: false,
                contentType: false,
                processData: false,
                success: function(respuesta){
                  console.log(respuesta);
                },
                error: function(error){
                  console.log(error);
                }

              });
            }
          }
          if ($("#tblProductsFinder tbody tr").length === 0 ) {
            $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
            tblProductsFinder = loadProductsFinder();
            tblProductsFinderAll = loadProductsFinderAll();
          } else {
            $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
  
            $("#tblProductsFinder").DataTable().ajax.reload();
            
            $("#tblProductsFinderAll").DataTable().ajax.reload();
  
          }
          $(".loader").fadeOut("slow");
          $("#loader2").removeClass("loader");
          Lobibox.notify("success", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/checkmark.svg",
            msg: "El producto se actualizó con éxito",
            sound:false,
          });
          $("#modal_update_product").modal("hide");
        },
        error: function(error){
          tblProductsFinder.ajax.reload();
        }

      });
      
    }
  } else {

    if (!$("input[name='producto_update_type]'").val()) {
      $("#invalid-productUpdateType").css("display", "block");
      $("#chkUpdateProduct").addClass("is-invalid");
      $("#chkUpdateService").addClass("is-invalid");
    }

    if(!$("#txtUpdateClave").val()) {
      $("#invalid-productUpdateKey").css("display", "block");
      $("#txtUpdateClave").addClass("is-invalid");
    }

    if(!$("#txtUpdateNombre").val()) {
      $("#invalid-productUpdateName").css("display", "block");
      $("#txtUpdateNombre").addClass("is-invalid");
    }

    if(!$("#cmbUpdateCategoria").val()) {
      $("#invalid-productUpdateCategory").css("display", "block");
      $("#cmbUpdateCategoria").addClass("is-invalid");
    }

    if(!$("#cmbUpdateMarca").val()) {
      $("#invalid-productUpdateBrand").css("display", "block");
      $("#cmbUpdateMarca").addClass("is-invalid");
    }

    if(!$("#txtUpdatePrecioCompra")) {
      $("#invalid-productUpdatePurchasePrice").css("display", "block");
      $("#txtUpdatePrecioCompra").addClass("is-invalid");
    }

    if(!$("#txtUpdateUtilidad1").val()){
      $("#invalid-productUpdateUtility").css("display", "block");
      $("#txtUpdateUtilidad1").addClass("is-invalid");
    }

    if (!$("#txtUpdateClaveSatId").val()) {
      $("#invalid-productUpdateServiceKey").css("display", "block");
      $("#txtUpdateClaveSatId").addClass("is-invalid");
    }

    if (!$("#txtUpdateUnidadMedidaId").val()) {
      $("#invalid-productUpdateUnitKey").css("display", "block");
      $("#txtUpdateUnidadMedidaId").addClass("is-invalid");
    }
  }
});

document.getElementById('txtUpdateClave').addEventListener("keyup", (e) => {
  clearAlertsInput("#txtUpdateClave",'',"productUpdateKey");
});

document.getElementById('txtUpdateNombre').addEventListener("keyup", (e) =>{
  clearAlertsInput("#txtUpdateNombre",'',"productUpdateName");
});

document.getElementById('cmbUpdateCategoria').addEventListener("change", (e) =>{
  clearAlertsInput("#cmbUpdateCategoria",'',"productUpdateCategory");
});

document.getElementById('cmbUpdateMarca').addEventListener("change", (e) =>{
  clearAlertsInput("#cmbUpdateMarca",'',"productUpdateBrand");
});

document.getElementById('txtUpdatePrecioCompra').addEventListener("keyup", (e) =>{
  clearAlertsInput("#txtUpdatePrecioCompra",'',"productUpdatePurchasePrice");
});

document.getElementById('txtUpdateUtilidad1').addEventListener("keyup", (e) =>{
  clearAlertsInput("#txtUpdateUtilidad1",'',"productUpdateUtility");
});

document.getElementById('chkUpdateProduct').addEventListener('click' ,() =>{
  clearAlertsInput("#chkUpdateProduct",'#chkUpdateService',"productUpdateType");
});

document.getElementById('txtUpdateClaveSatId').addEventListener('change', (e) => {
  clearAlertsInput("#txtUpdateClaveSatId",'',"productUpdateServiceKey");
})

document.getElementById('txtUpdateUnidadMedidaId').addEventListener('change', () => {
  clearAlertsInput("#txtUpdateUnidadMedidaId",'',"productUpdateUnitKey");
});

document.getElementById("chkUpdateLote").addEventListener("click", (e) => {
  document.getElementById("chkUpdateCaducidad").removeAttribute('disabled');
  if(document.getElementById('chkUpdateExistencia').checked === true){
    document.getElementById('input-text-exist').classList.add('yes-visible');
    document.getElementById('button-add-exist').classList.remove('no-visible');
  } else {
    document.getElementById('input-text-exist').classList.remove('yes-visible');
    document.getElementById('button-add-exist').classList.add('no-visible');
  }
  
})

document.getElementById("chkUpdateSerie").addEventListener("click", (e) => {
  document.getElementById("chkUpdateCaducidad").setAttribute('disabled','');
  if(document.getElementById('chkUpdateExistencia').checked === true){
    document.getElementById('input-text-exist').classList.add('yes-visible');
    document.getElementById('button-add-exist').classList.remove('no-visible');
  } else {
    document.getElementById('input-text-exist').classList.remove('yes-visible');
    document.getElementById('button-add-exist').classList.add('no-visible');
  }
});



function getGeneralUpdateData(){
  clave = document.getElementById("txtUpdateClave").value;
  codigo_barras = document.getElementById("txtUpdateCodigoBarras").value;
  nombre = document.getElementById("txtUpdateNombre").value;
  categoria = document.getElementById("cmbUpdateCategoria").value;
  marca = document.getElementById("cmbUpdateMarca").value;
  precio_compra = document.getElementById("txtUpdatePrecioCompra").value;
  precio_compra_sin_impuesto = document.getElementById("txtUpdatePrecioCompraSinImpuestosValue").value;
  chkprecio_compra_neto = document.getElementById("chkUpdatePrecioCompraNeto").checked === true ? 1 : 0;
  utilidad1 = document.getElementById("txtUpdateUtilidad1").value !== "" && document.getElementById("txtUpdateUtilidad1").value !== null ? document.getElementById("txtUpdateUtilidad1").value : 0; 
  utilidad2 = document.getElementById("txtUpdateUtilidad2").value !== "" && document.getElementById("txtUpdateUtilidad2").value !== null ? document.getElementById("txtUpdateUtilidad2").value : 0;
  utilidad3 = document.getElementById("txtUpdateUtilidad3").value  !== "" && document.getElementById("txtUpdateUtilidad3").value !== null ? document.getElementById("txtUpdateUtilidad3").value : 0;
  utilidad4 = document.getElementById("txtUpdateUtilidad4").value  !== "" && document.getElementById("txtUpdateUtilidad4").value !== null ? document.getElementById("txtUpdateUtilidad4").value : 0;
  precio_venta1 = document.getElementById("txtUpdatePrecioVenta1").value !== "" && document.getElementById("txtUpdatePrecioVenta1").value !== null ? document.getElementById("txtUpdatePrecioVenta1").value : 0;
  precio_venta2 = document.getElementById("txtUpdatePrecioVenta2").value !== "" && document.getElementById("txtUpdatePrecioVenta2").value !== null ? document.getElementById("txtUpdatePrecioVenta2").value : 0;
  precio_venta3 = document.getElementById("txtUpdatePrecioVenta3").value !== "" && document.getElementById("txtUpdatePrecioVenta3").value !== null ? document.getElementById("txtUpdatePrecioVenta3").value : 0;
  precio_venta4 = document.getElementById("txtUpdatePrecioVenta4").value !== "" && document.getElementById("txtUpdatePrecioVenta4").value !== null ? document.getElementById("txtUpdatePrecioVenta4").value : 0;
  precio_venta_neto1 = document.getElementById("txtUpdatePrecioVentaNeto1").value !== "" && document.getElementById("txtUpdatePrecioVentaNeto1").value !== null ? document.getElementById("txtUpdatePrecioVentaNeto1").value : 0;
  precio_venta_neto2 = document.getElementById("txtUpdatePrecioVentaNeto2").value !== "" && document.getElementById("txtUpdatePrecioVentaNeto2").value !== null ? document.getElementById("txtUpdatePrecioVentaNeto2").value : 0;
  precio_venta_neto3 = document.getElementById("txtUpdatePrecioVentaNeto3").value !== "" && document.getElementById("txtUpdatePrecioVentaNeto3").value !== null ? document.getElementById("txtUpdatePrecioVentaNeto3").value : 0;
  precio_venta_neto4 = document.getElementById("txtUpdatePrecioVentaNeto4").value !== "" && document.getElementById("txtUpdatePrecioVentaNeto4").value !== null ? document.getElementById("txtUpdatePrecioVentaNeto4").value : 0;
  chkLote = document.getElementById("chkUpdateLote").checked === true ? 1 : 0;
  chkSerie = document.getElementById("chkUpdateSerie").checked === true ? 1 : 0;
  chk_fecha_caducidad = document.getElementById("chkUpdateCaducidad").checked === true ? 1 : 0;
  chk_tipo_producto = document.querySelector('input[name="producto_type"]').value;
  chk_receta = document.getElementById("chkUpdateReceta").checked === true ? 1 : 0;
  descripcion = document.getElementById('txaUpdateDescriptionProduct').value;
  
  var data_general = 
  '"data_general":' +
  '[{' +
      '"clave" : "' + clave + '",' +
      '"codigo_barras" : "' + codigo_barras + '",' +
      '"nombre" : "' + nombre + '",' +
      '"descripcion" : "'+descripcion+'",' +
      '"categoria" : "' + categoria + '",' +
      '"marca" : "' + marca + '",' +
      '"precio_compra" : "' + precio_compra + '",' +
      '"precio_compra_sin_impuesto" : "' + precio_compra_sin_impuesto + '",' +
      '"precio_compra_neto" : "' + chkprecio_compra_neto + '",' + 
      '"utilidad1":"' + utilidad1 + '",' +
      '"utilidad2":"' + utilidad2 + '",' +
      '"utilidad3":"' + utilidad3 + '",' +
      '"utilidad4":"' + utilidad4 + '",' +
      '"precio_venta1":"' + precio_venta1 + '",' +
      '"precio_venta2":"' + precio_venta2 + '",' +
      '"precio_venta3":"' + precio_venta3 + '",' +
      '"precio_venta4":"' + precio_venta4 + '",' +
      '"precio_venta_neto1":"' + precio_venta_neto1 + '",' +
      '"precio_venta_neto2":"' + precio_venta_neto2 + '",' +
      '"precio_venta_neto3":"' + precio_venta_neto3 + '",' +
      '"precio_venta_neto4":"' + precio_venta_neto4 + '",' +
      '"tipo_producto":"' + chk_tipo_producto + '",' +
      '"chkLote":"' + chkLote + '",' +
      '"chkSerie":"' + chkSerie + '",' +
      '"chkReceta":"' + chk_receta + '",' +
      '"chk_fecha_caducidad":"' + chk_fecha_caducidad + '"' +
  '}]';
 
  return data_general;
}

function getTaxUpdateProduct(value){
  tax_product_aux = "";
  var tax_product = '"tax_product" : [';
  const table_tax_udpdate = loadTaxUpdateTable(value);
  if(table_tax_udpdate.rows().count()){
    res = table_tax_udpdate.rows().data();
    res.map((row) =>{
      tax_product_aux += '{"tax_id":"'+ row[0] +'","rate":"'+ row[2] +'"},'
      
    })
  }

  tax_product += tax_product_aux.substring(0,tax_product_aux.length-1);
  tax_product += ']';

  return tax_product;
}

function getFiscalUpdateData(){
  clave_sat = document.getElementById("txtUpdateClaveSatId").value;
  clave_unidad = document.getElementById("txtUpdateUnidadMedidaId").value;
  var fiscal_data = 
  '"fiscal_data" :' +
    '[{' +
      '"clave_sat" : "' + clave_sat + '",' +
      '"clave_unidad" : "' + clave_unidad + '"' +
    '}]';

    return fiscal_data;
}

function uploadImg(){
  const file = document.querySelector('#flUpdateUploadImage').files[0];
  if(file !== null && file !== "" && file !== undefined){
    console.log("listo para subir");
  } else {
    console.log("no estoy listo, no he sido seleccionado");
  }
}

function getUpdatePriceWithTax(value){
  var iva = 0;
  var ieps = 0;
  var ish = 0;
  var cedular = 0;
  var iva_retenido = 0;
  var isr = 0;
  var isn = 0;
  var al_millar = 0;
  var funcion_publica = 0;
  
  value_neto = 0
  for(i=0;i < row_count_table; i++){
    id_tax = parseInt(table_tax_update.row(i).data()[0]);
    rate_tax = table_tax_update.row(i).data()[2];
    
    switch(id_tax){
      case 1:
        iva = rate_tax / 100;
      break;
      case 2:
        ieps = rate_tax / 100;
      break;
      
      case 4:
        ish = rate_tax / 100;
        
      break;
      
      case 6:
        iva_retenido = rate_tax / 100;
      break;
      case 7:
        isr = rate_tax / 100;
      break;
      case 8:
        isn = rate_tax / 100;
      break;
      case 9:
        cedular = rate_tax / 100;
      break;
      case 10:
        al_millar = rate_tax / 100;
      case 11:
        funcion_publica = rate_tax / 100;
      break;
      
    }
  }
  
  value_neto = parseInt(value) !== 0 ? (value / ( (ieps * iva) + iva + ieps + ish + cedular + isn + al_millar + funcion_publica - isr - iva_retenido + 1)) : 0;
  
  if(value_neto === 0){
    value_neto = value;
  } 
  return value_neto;
}

function getUpdatePriceWithOutTax(value){
  var iva = 0;
  var ieps = 0;
  var ish = 0;
  var cedular = 0;
  var iva_rate = 0;
  
  row_count_table = table_tax_update.rows().count();
  value_neto = 0;
 
  for(i=0;i < row_count_table; i++){
    id_tax = parseInt(table_tax_update.row(i).data()[0]);
    rate_tax = table_tax_update.row(i).data()[2];

    switch(id_tax){
      case 1:
        iva = value * (rate_tax / 100);
        iva_rate = rate_tax;
      break;
      case 2:
        ieps = value * (rate_tax / 100);
        if(iva > 0){
          iva += ieps * (iva_rate / 100);
        } 
      break;
      case 3:
        //value_neto = value_neto / ((rate_tax / 100) + 1);
      break;
      case 4:
        ish = value * ((rate_tax / 100));
      break;
      case 5:
      break;
      case 6:
      break;
      case 7:
      break;
      case 8:
        value_neto = value_neto * ((rate_tax / 100) + 1);
      break;
      case 9:
        cedular = value * ((rate_tax / 100));
      break;
      case 10:
        value_neto = value_neto * ((rate_tax / 100) + 1);
      break;
      case 11:
        value_neto = value_neto * ((rate_tax / 100) + 1);
      break;
      case 12:
      break;
      case 13:
      break;
      case 18:
      break;
      
    }
  }
  value_neto = value + iva + ieps + ish + cedular;
  if(value_neto === 0){
    value_neto = value;
  }

  return value_neto;
}

function getUtility(value){
  
}

function checkUpdateUtilitiesVoid(ini){
  txtUtilidades = document.getElementsByName("txtUpdateUtilidad");
  for (let i = 0; i < txtUtilidades.length; i++) {
    if(
      txtUtilidades[i].value !== 0 && 
      txtUtilidades[i].value !== null && 
      txtUtilidades[i].value !== "" && 
      txtUtilidades[i].value !== "0.00")
    {
      var id_input_text = txtUtilidades[i].getAttribute('id');
      var id_input = document.getElementById(txtUtilidades[i].getAttribute('id'));
      row_count_table = table_tax_update.rows().count();
      value = txtPrecioUpdateCompra.value;
      value1 = id_input.value;

      price =  parseFloat(value) + (parseFloat(value) * (parseFloat(value1) / 100));
      last_id_text = id_input_text.substr(-1);
      input_sale = document.getElementById("txtUpdatePrecioVenta" + last_id_text);
      input_net_sale = document.getElementById("txtUpdatePrecioVentaNeto" + last_id_text);
      
      if($("#chkUpdatePrecioCompraNeto").is(":checked")){
        input_sale.value = numeral(getUpdatePriceWithTax(price)).format('0.00');
        input_net_sale.value = numeral(price).format('0.00');
      } else {
        input_sale.value = numeral(price).format('0.00');
        input_net_sale.value = numeral(getUpdatePriceWithOutTax(price)).format('0.00');
      }
    }
    
  }
}

// function checkUpdateNetoPriceVoid(ini){
  //   txtPrecioVentaNeto = document.getElementsByName("txtUpdatePrecioVentaNeto");
  //   for (let i = 0; i < txtUtilidades.length; i++) {
  //     if(
  //       txtPrecioVentaNeto[i].value !== 0 && 
  //       txtPrecioVentaNeto[i].value !== null && 
  //       txtPrecioVentaNeto[i].value !== "" && 
  //       txtPrecioVentaNeto[i].value !== "0.00")
  //     {
  //       var id_input_text = txtPrecioVentaNeto[i].getAttribute('id');
  //       var id_input = document.getElementById(txtPrecioVentaNeto[i].getAttribute('id'));
  //       console.log(id_input_text);
  //     }
  //   }
  // }