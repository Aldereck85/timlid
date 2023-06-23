document.addEventListener("DOMContentLoaded", function () {
  txtPrecioCompra = document.getElementById("txtPrecioCompra");
  chkPrecioCompraNeto = document.getElementById("chkPrecioCompraNeto");
  txtPrecioCompraSinImpuestos = document.getElementById("txtPrecioCompraSinImpuestos");
  txtUtilidades = document.getElementsByName("txtUtilidad");
  chkLote = document.getElementById(chkLote);
  chkSerie = document.getElementById(chkSerie);
  chkCaducidad = document.getElementById(chkCaducidad);
  txtPrecioVentaNeto = document.getElementsByName("txtPrecioVentaNeto");

  document.getElementById("txtPrecioCompra").addEventListener("keyup", function() {
    ini_value = this.value;
    if(chkPrecioCompraNeto.checked){
      if(table_tax.data().count()){
      } else {
        txtPrecioCompraSinImpuestos.value = numeral(ini_value).format('0,000,000,000.00');
      }
    } else {
      txtPrecioCompraSinImpuestos.value = numeral(ini_value).format('0,000,000,000.00');
      
    }
    checkUtilitiesVoid(txtUtilidades);
  });

  /* Cambia precios en cada utilidad según precio de compra y valor agregado en los campos */

  for(i = 0; i < txtUtilidades.length; i++){
    txtUtilidades[i].addEventListener("keyup", function() {
      var id_input_text = this.getAttribute('id');
      var id_input = document.getElementById(this.getAttribute('id'));
      row_count_table = table_tax.rows().count();
      value = txtPrecioCompra.value;
      value1 = id_input.value;

      last_id_text = id_input_text.substr(-1);
      input_sale = document.getElementById("txtPrecioVenta" + last_id_text);
      input_net_sale = document.getElementById("txtPrecioVentaNeto" + last_id_text);
      price = parseFloat(value) + (parseFloat(value) * (parseFloat(value1) / 100));

      if(chkPrecioCompraNeto.checked){
        input_sale.value = numeral(getPriceWithTax(price)).format('0,000,000,000.00');
        input_net_sale.value = numeral(price).format('0,000,000,000.00');
      } else {
        input_sale.value = numeral(price).format('0,000,000,000.00');
        input_net_sale.value = numeral(getPriceWithOutTax(price)).format('0,000,000,000.00');
      }
    });
  }

  for (let i = 0; i < txtPrecioVentaNeto.length; i++) {
    txtPrecioVentaNeto[i].addEventListener("keyup", (e) =>{
      var input_ = e.target;
      var id_input_text = input_.getAttribute('id');
      var id_input = document.getElementById(input_.getAttribute('id'));
      var id_input1 = document.getElementById("id_input1")
      value = txtPrecioCompraSinImpuestos.value;
      value1 = id_input.value;

      last_id_text = id_input_text.substr(-1);
      input_sale = document.getElementById("txtPrecioVenta" + last_id_text);
      input_utilities = document.getElementById("txtUtilidad" + last_id_text);

      if(chkPrecioUpdateCompraNeto.checked){
        
        input_sale.value = numeral(getPriceWithTax(parseFloat(id_input.value))).format('0.00');
        console.log(value);
        input_utilities.value = numeral(((input_sale.value/value) - 1) * 100).format('0.00');
      } else {
        input_sale.value = numeral(getPriceWithTax(parseFloat(id_input.value))).format('0.00');
        input_utilities.value = numeral(((input_sale.value/value) -1) * 100).format('0.00');
      }
    });
    
  }
  
  document.getElementById("chkPrecioCompraNeto").addEventListener("click", function() {
    
    value = txtPrecioCompra.value;
    value_neto = value;   
    row_count_table = table_tax.rows().count();
    if(chkPrecioCompraNeto.checked){
      txtPrecioCompraSinImpuestos.value = numeral(getPriceWithTax(value)).format('0,000,000,000.00');
      checkUtilitiesVoid(txtUtilidades);
    } else {
      txtPrecioCompraSinImpuestos.value = numeral(value).format('0,000,000,000.00');
      checkUtilitiesVoid(txtUtilidades);
    }
  });

  document.getElementById("btnAddProduct").addEventListener("click", function() 
  {
    var total_stock = parseFloat(document.getElementById('txtExistencia').value);
    var quantities_stock = 0
    var rows = document.querySelectorAll("#tblExistProduct tbody tr");
    
    if(rows.length > 0){
      rows.forEach(function(row) {
        quantities_stock += parseFloat(row.querySelector('input[name="txtExistProductQuantity"]').value);
      });
      if(total_stock === quantities_stock){
        saveDataProduct();
      } else {
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/warning_circle.svg",
          msg: "La cantidad total de la existencia no es igual a la suma de las cantidades ingresadas",
          sound:false,
        });
      }
    } else {
      saveDataProduct();
    }
    
  });
  
  document.getElementById("chkLote").checked === true || document.getElementById("chkSerie").checked ? document.getElementById('button-add-exist').classList.remove('no-visible') : document.getElementById('button-add-exist').classList.add('no-visible');
});

function saveDataProduct(){
  document.getElementById("btnAddProduct").setAttribute("disabled",'');
  if($("#form-data-product")[0].checkValidity())
  {
    bad_productType = 
      $("#invalid-productType").css("display") === "block" ? false : true;
    bad_productKey =
      $("#invalid-productKey").css("display") === "block" ? false : true;
    bad_productName =
      $("#invalid-productName").css("display") === "block" ? false : true;
    bad_productCategory =
      $("#invalid-productCategory").css("display") === "block" ? false : true;
    bad_productBrand = 
      $("#invalid-productBrand").css("display") === "block" ? false : true;
    bad_productPurchasePrice = 
      $("#invalid-productPurchasePrice").css("display") === "block" ? false : true;
    bad_productUtility = 
      $("#invalid-productUtility").css("display") === "block" ? false : true;
    bad_productServiceKey = 
      $("#invalid-productServiceKey").css("display") === "block" ? false : true;
    bad_productUnitKey = 
      $("#invalid-productUnitKey").css("display") === "block" ? false : true;
  
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
      
      general_data = getGeneralData();
      tax_product = getTaxProduct();
      fiscal_data = getFiscalData();
      stock_data = getStockData();

      json = 
      '{' +
          '"data" : [{' + general_data + '},{' + tax_product + '},{' + fiscal_data + '},{' + stock_data + '}]' +
      '}';
      $("#loader2").css("display","block");
      $("#loader2").addClass("loader");
      $.ajax({
        method: "POST",
        url: "php/funciones.php",
        data: {
          clase:'save_data',
          funcion:'save_product',
          value:json
        },
        dataType: 'json',
        success: function(respuesta){
          //tblProductsFinder.ajax.reload();
          const file = document.querySelector('#flUploadImage').files[0];
          if(file !== null && file !== "" && file !== undefined){
            var form = new FormData();
            form.append("id",respuesta.id);
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
            msg: "El producto se guardó con éxito",
            sound:false,
          });

          $("#create_product").modal("hide");
          document.getElementById("btnAddProduct").removeAttribute("disabled");
          
          // var elmtTable = document.getElementById('tblExistProduct');
          // var tbl = elmtTable.parentNode.parentNode.parentNode;
          // var row = elmtTable.parentNode.parentNode.rowIndex;
    
          // var rowCount = elmtTable.rows.length;
          
          // elmtTable.parentElement.parentElement.remove();

          $("#tblExistProduct tbody").children().remove();

          document.getElementById("tblExistProduct").classList.remove('yes-visible-table');
          document.getElementById("tblExistProduct").classList.add('no-visible');
          
          document.getElementById("input-text-exist").classList.remove('yes-visible');
          document.getElementById("input-text-exist").classList.add('no-visible');
          document.getElementById("button-add-exist").classList.remove('yes-visible');
          document.getElementById("button-add-exist").classList.add('no-visible');
          document.getElementById("txtExistencia").removeAttribute("readonly");
          document.getElementById("txtExistencia").value = "";
          
        },
        error: function(error){
          
        }

      });
    }
  } else {
    
    if (!$("input:radio[name='producto_type']").is(":checked")) {
      $("#invalid-productType").css("display", "block");
      $("#general-data-product-tab").addClass("is-invalid-tab");
      $("#chkProduct").addClass("is-invalid");
      $("#chkService").addClass("is-invalid");
      document.getElementById("btnAddProduct").setAttribute("disabled",'');
    }
    
    if(!$("#txtClave").val()) {
      $("#general-data-product-tab").addClass("is-invalid-tab");
      $("#invalid-productKey").css("display", "block");
      $("#txtClave").addClass("is-invalid");
      document.getElementById("btnAddProduct").setAttribute("disabled",'');
    }

    if(!$("#txtNombre").val()) {
      $("#general-data-product-tab").addClass("is-invalid-tab");
      $("#invalid-productName").css("display", "block");
      $("#txtNombre").addClass("is-invalid");
      document.getElementById("btnAddProduct").setAttribute("disabled",'');
    }

    if(!$("#cmbCategoria").val()) {
      $("#general-data-product-tab").addClass("is-invalid-tab");
      $("#invalid-productCategory").css("display", "block");
      $("#cmbCategoria").addClass("is-invalid");
      document.getElementById("btnAddProduct").setAttribute("disabled",'');
    }

    if(!$("#cmbMarca").val() && $("#cmbMarca").attr('required')) {
      $("#general-data-product-tab").addClass("is-invalid-tab");
      $("#invalid-productBrand").css("display", "block");
      $("#cmbMarca").addClass("is-invalid");
      document.getElementById("btnAddProduct").setAttribute("disabled",'');
    }

    if(!$("#txtPrecioCompra").val()) {
      $("#general-data-product-tab").addClass("is-invalid-tab");
      $("#invalid-productPurchasePrice").css("display", "block");
      $("#txtPrecioCompra").addClass("is-invalid");
      document.getElementById("btnAddProduct").setAttribute("disabled",'');
    }

    if(!$("#txtUtilidad1").val()){
      $("#general-data-product-tab").addClass("is-invalid-tab");
      $("#invalid-productUtility").css("display", "block");
      $("#txtUtilidad1").addClass("is-invalid");
      document.getElementById("btnAddProduct").setAttribute("disabled",'');
    }

    if (!$("#txtClaveSatId").val()) {
      $("#additional-data-product-tab").addClass("is-invalid-tab");
      $("#invalid-productServiceKey").css("display", "block");
      $("#txtClaveSatId").addClass("is-invalid");
      document.getElementById("btnAddProduct").setAttribute("disabled",'');
    }

    if (!$("#txtUnidadMedidaId").val()) {
      $("#additional-data-product-tab").addClass("is-invalid-tab");
      $("#invalid-productUnitKey").css("display", "block");
      $("#txtUnidadMedidaId").addClass("is-invalid");
      document.getElementById("btnAddProduct").setAttribute("disabled",'');
    }
  }
}

document.getElementById('txtClave').addEventListener('focusout', (e) => {
  var value = e.target.value;
  if(value === "")
  {
    $("#invalid-productKey").css("display", "block");
    $("#invalid-productKeyRepeat").css("display", "none");
    $("#txtClave").addClass("is-invalid");
  } else {
    getIfProductoKeyExist(value);
  }
})

document.getElementById('txtClave').addEventListener("keyup", (e) => {
  var value = e.target.value;
  getAllInputsInvalid("form-data-product");
  clearAlertsInput("#txtClave",'',"productKey");
  getIfProductoKeyExist(value);
  checkAlertForm();
});

document.getElementById('txtNombre').addEventListener("keyup", (e) =>{
  getAllInputsInvalid("form-data-product")
  clearAlertsInput("#txtNombre",'',"productName");
  checkAlertForm();
});

document.getElementById('cmbCategoria').addEventListener("change", (e) =>{
  getAllInputsInvalid("form-data-product")
  clearAlertsInput("#cmbCategoria",'',"productCategory");
  checkAlertForm();
});

document.getElementById('cmbMarca').addEventListener("change", (e) =>{
  getAllInputsInvalid("form-data-product")
  clearAlertsInput("#cmbMarca",'',"productBrand");
  checkAlertForm();
});

document.getElementById('txtPrecioCompra').addEventListener("keyup", (e) =>{
  getAllInputsInvalid("form-data-product")
  clearAlertsInput("#txtPrecioCompra",'',"productPurchasePrice");
  checkAlertForm();
});

document.getElementById('txtUtilidad1').addEventListener("keyup", (e) =>{
  getAllInputsInvalid("form-data-product")
  clearAlertsInput("#txtUtilidad1",'',"productUtility");
  checkAlertForm();
});

document.getElementById('chkProduct').addEventListener('click' ,() =>{
  getAllInputsInvalid("form-data-product")
  clearAlertsInput("#chkProduct",'#chkService',"productType");
  checkAlertForm();
});

document.getElementById('txtClaveSatId').addEventListener('change', (e) => {
  getAllInputsInvalid("form-data-product")
  clearAlertsInput("#txtClaveSatId",'',"productServiceKey");
  checkAlertForm();
})

document.getElementById('txtUnidadMedidaId').addEventListener('change', () => {
  getAllInputsInvalid("form-data-product")
  clearAlertsInput("#txtUnidadMedidaId",'',"productUnitKey");
  checkAlertForm();
})

document.getElementById("chkLote").addEventListener("click", (e) => {
  e.currentTarget.checked ? document.getElementById("chkSerie").setAttribute('disabled','') : document.getElementById("chkSerie").removeAttribute('disabled');
  e.currentTarget.checked ? document.getElementById("chkCaducidad").removeAttribute('disabled') : document.getElementById("chkCaducidad").setAttribute('disabled','');
  
  if(e.currentTarget.checked && document.getElementById('chkExistencia').checked){
    // document.getElementById('input-text-exist').classList.add('yes-visible-inputs');
    document.getElementById('button-add-exist').classList.remove('no-visible');
  } else {
    // document.getElementById('input-text-exist').classList.remove('yes-visible-inputs');
    document.getElementById('button-add-exist').classList.add('no-visible');
  }
  
})

document.getElementById("chkSerie").addEventListener("click", (e) => {
  e.currentTarget.checked ? document.getElementById("chkLote").setAttribute('disabled','') : document.getElementById("chkLote").removeAttribute('disabled');
  e.currentTarget.checked ? document.getElementById("chkCaducidad").removeAttribute('disabled') : document.getElementById("chkCaducidad").setAttribute('disabled','');

  if(e.currentTarget.checked && document.getElementById('chkExistencia').checked === true){
    //document.getElementById('input-text-exist').classList.add('yes-visible-inputs');
    document.getElementById('button-add-exist').classList.remove('no-visible');
  } else {
    //document.getElementById('input-text-exist').classList.remove('yes-visible-inputs');
    document.getElementById('button-add-exist').classList.add('no-visible');
  }
})

document.getElementById('chkExistencia').addEventListener("click", function(e){

  if(e.currentTarget.checked){
    document.getElementById('input-text-exist').classList.add('yes-visible-inputs');
    if(document.getElementById('chkLote').checked === true || document.getElementById('chkSerie').checked === true){
      document.getElementById('button-add-exist').classList.remove('no-visible');
    }
  } else {
    document.getElementById('input-text-exist').classList.remove('yes-visible-inputs');
    document.getElementById('button-add-exist').classList.add('no-visible');
  }
});

cont_exist = 1;
document.getElementById("addExistsProduct").addEventListener("click",function(){
  
  check_expired_date = document.getElementById("chkCaducidad"); 
  if(document.getElementById("txtExistencia").value !== ""){
    //document.getElementById('txtExistencia').setAttribute("readonly",true);
    tblExistProduct = document.getElementById("tblExistProduct");
    document.getElementById("tblExistProduct").classList.add('yes-visible-table');
    document.getElementById("tblExistProduct").classList.remove('no-visible');
    chkLote = document.getElementById("chkLote");
    chkCaducidad = document.getElementById("chkCaducidad");
    chkSerie = document.getElementById("chkSerie");
    total_stock = parseFloat(document.getElementById("txtExistencia").value);

    label_exists_quantity = '<label for="txtExistProduct">Cantidad</label>';
    label_exists_lot = '<label for="txtExistProductLot">Lote</label>';
    label_exists_expired = '<label for="txtExistProductLot">Fecha de Caducidad</label>';
    label_exists_serie = '<label for="txtExistProductSerie">Serie</label>';

    input_exists_quantity = '<input type="number" class="form-control" name="txtExistProductQuantity" id="txtExistProductQuantity'+cont_exist+'" value="0" step="0.01">';
    input_exists_lot = '<input type="text" class="form-control" name="txtExistProductLot" id="txtExistProductLot'+cont_exist+'">';
    input_exists_serie = '<input type="text" class="form-control" name="txtExistProductSerie" id="txtExistProductSerie'+cont_exist+'">';
    if(check_expired_date.checked){
      input_exists_expired = '<input type="date" class="form-control" name="txtExistProductExpired" id="txtExistProductExpired'+cont_exist+'">';
    } else {
      input_exists_expired = '<input type="date" class="form-control" name="txtExistProductExpired" id="txtExistProductExpired'+cont_exist+'" readonly>';
    }
    total = 0;
    for(let i = 1; i < cont_exist; i++ ) {
      
      total += parseFloat(document.getElementById('txtExistProductQuantity'+i).value);
    }
    console.log(total);
    // console.log(tblExistProduct.tBodies[0].rows.length);
    // for(let i = 0; i > tblExistProduct.tBodies[0].rows.length; i++ ) {

    // }
    
    btn_delete_exist = '<a href="#" data-row="'+cont_exist+'"><i class="fas fa-minus-circle" style="color:red"></i></a>';
     
    if(total < total_stock){
      var row = tblExistProduct.children[1].insertRow(tblExistProduct.children[1].length);
      var cell1 = row.insertCell(0);
      var cell2 = row.insertCell(1);
      var cell3 = row.insertCell(2);
      var cell4 = row.insertCell(3);
      
      cell1.innerHTML = input_exists_quantity;
      cell2.innerHTML = input_exists_lot;
      cell3.innerHTML = input_exists_expired;
      cell4.innerHTML = btn_delete_exist;
      cont_exist++;
    } else {
      Lobibox.notify("error", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../img/timdesk/warning_circle.svg",
        msg: "La Existencia rebasa la cantidad descrita",
        sound:false,
      });
    }
  } else {
    Lobibox.notify("error", {
      size: "mini",
      rounded: true,
      delay: 3000,
      delayIndicator: false,
      position: "center top",
      icon: true,
      img: "../../img/timdesk/warning_circle.svg",
      msg: "Existencia es un campo obligatorio",
      sound:false,
    });
  }

  
})

document.getElementById("chkCaducidad").addEventListener("click", function(){
  data = document.getElementsByName("txtExistProductExpired");
  if(this.checked){
    for (let i = 0; i < data.length; i++) {
      data[i].readOnly = false;
    }
  } else {
    for (let i = 0; i < data.length; i++) {
      data[i].readOnly = true;
    }
    
  }
});

const table = document.querySelector('#tblExistProduct tbody');

table.addEventListener('click', (event) => {
  const rows = table.querySelectorAll('tr');
  const rowsArray = Array.from(rows);

  const rowIndex = rowsArray.findIndex(row => row.contains(event.target));
  const columns = Array.from(rowsArray[rowIndex].querySelectorAll('td'));
  const columnIndex = columns.findIndex(column => column.contains(event.target));
  console.log(rows.length);
  if(columnIndex === 3){
    table.deleteRow(rowIndex);
    if((rows.length - 1) === 0){
      document.getElementById("chkLote").disabled = false;
      document.getElementById("chkSerie").disabled = false;
      document.getElementById("chkCaducidad").disabled = false;
      document.getElementById("tblExistProduct").style.display = "none";
      document.getElementById("chkLote").checked = false;
      document.getElementById("chkSerie").checked = false;
      document.getElementById('chkExistencia').checked = false;
      //document.getElementById('input-text-exist').classList.remove('yes-visible-inputs');
      //document.getElementById('button-add-exist').classList.add('no-visible');
      //document.getElementById('input-missing-stock').classList.add('no-visible');
      document.getElementById('txtExistencia').value = "";
      document.getElementById('txtExistencia').setAttribute("readonly",false);
    }
  }
  
});
document. getElementById("btnGenerarClave").addEventListener("click",() =>{
  var categoria = "";
  if($("input:radio[name=producto_type]").is(":checked")){
    categoria = $("input:radio[name=producto_type]:checked").val();
  }

  var limpieza = "";

  switch (parseInt(categoria)) {
    case 1:
      limpieza = "Cmp";
      break;
    case 2:
      limpieza = "Cns";
      break;    
    case 3:
      limpieza = "MP";
      break;
    case 4:
      limpieza = "P";
      break;
    case 5:
      limpieza = "S";
      break;
    case 6:
      limpieza = "AF";
      break; 
    case 7:
      limpieza = "A";
      break;
    case 8: 
      limpieza = "SI";
      break;
    default:
      limpieza = "N";
      break;
  }

  if (limpieza != "N") {
    $.ajax({
      method: "post",
      url: "php/funciones.php",
      data: { clase: "get_data", funcion: "get_claveReferencia" },
      dataType: "json",
      success: function (respuesta) {
        $("#txtClave").val(limpieza + "" + respuesta);
        
        $("#invalid-productKey").css("display", "none");
        $("#invalid-productKeyRepeat").css("display", "none");
        $("#txtClave").removeClass("is-invalid");
      },
      error: function (error) {
        console.log(error);
      },
    });
  } else {
    $("#invalid-productType").css("display", "block");
    $("input:radio[name=producto_type]").addClass("is-invalid");
  }
});

$("input:radio[name=producto_type]").on("click",(e)=>{
  if($("#invalid-productType").is(":visible")){
    $("#invalid-productType").css("display", "none");
    $("input:radio[name=producto_type]").removeClass("is-invalid");
  }
});

document.getElementById("chkService").addEventListener("click",(e) =>{
  cmbMarca.set(1);
  document.querySelector("#cmbMarca").slim.disable();
  //$("#cmbMarca").css("display","none");
});

document.getElementById("chkProduct").addEventListener("click",(e) =>{
  cmbMarca.set(1);
  document.querySelector("#cmbMarca").slim.enable();
});

function getPriceWithTax(value){
  var iva = 0;
  var ieps = 0;
  var ish = 0;
  var cedular = 0;
  var iva_retenido = 0;
  var isr = 0;
  var isn = 0;
  var al_millar = 0;
  var funcion_publica = 0;
  
  
  row_count_table = table_tax.rows().count();
  value_neto = 0
  for(i=0;i < row_count_table; i++){
    id_tax = parseInt(table_tax.row(i).data()[0]);
    rate_tax = table_tax.row(i).data()[2];
    
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
  value_neto = (value / ( (ieps * iva) + iva + ieps + ish + cedular + isn + al_millar + funcion_publica - isr - iva_retenido + 1));
  
  if(value_neto === 0){
    value_neto = value;
  } 
  return value_neto;
}

function getPriceWithOutTax(value){
  var iva = 0;
  var ieps = 0;
  var ish = 0;
  var cedular = 0;
  var iva_rate = 0;
  
  row_count_table = table_tax.rows().count();
  value_neto = 0;
 
  for(i=0;i < row_count_table; i++){
    id_tax = parseInt(table_tax.row(i).data()[0]);
    rate_tax = table_tax.row(i).data()[2];

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

function checkUtilitiesVoid(ini){
  txtUtilidades = document.getElementsByName("txtUtilidad");
  for (let i = 0; i < txtUtilidades.length; i++) {
    if(txtUtilidades[i].value){
      var id_input_text = txtUtilidades[i].getAttribute('id');
      var id_input = document.getElementById(txtUtilidades[i].getAttribute('id'));
      row_count_table = table_tax.rows().count();
      value = txtPrecioCompra.value;
      value1 = id_input.value;

      price = parseFloat(value) + (parseFloat(value) * (parseFloat(value1) / 100));
      last_id_text = id_input_text.substr(-1);
      input_sale = document.getElementById("txtPrecioVenta" + last_id_text);
      input_net_sale = document.getElementById("txtPrecioVentaNeto" + last_id_text);

      if($("#chkPrecioCompraNeto").is(":checked")){
        input_sale.value = numeral(getPriceWithTax(price)).format('0,000,000,000.00');
        input_net_sale.value = numeral(price).format('0,000,000,000.00');
      } else {
        input_sale.value = numeral(price).format('0,000,000,000.00');
        input_net_sale.value = numeral(getPriceWithOutTax(price)).format('0,000,000,000.00');
      }
    }
    
  }
}

function getProductTradeMark(value)
{
  if(value !== null && value !== ''){
    let url = "php/funciones.php";
    var data = new FormData();
    data.append("clase","save_data");
    data.append("funcion","save_productTradeMark");
    data.append("value",value);
    
    fetch(url,
    {
      method: 'POST',
      body: data
    })
    .then(res => res.json())
    .then(data => {
      loadCombo('productTradeMark',"#cmbMarca",data.id,"","");
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
      msg: "La marca debe tener un nombre",
    });
  }
}

function addProductCategories(value)
{
  if(value !== null && value !== ''){
    let url = "php/funciones.php";
    var data = new FormData();
    data.append("clase","save_data");
    data.append("funcion","save_productCategory");
    data.append("value",value);

    fetch(url,
    {
      method: 'POST',
      body: data
    })
    .then(res => res.json())
    .then(data => {
      loadCombo('productCategories',"#cmbCategoria",data.id,"","");
      
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
      msg: "La categoria debe tener un nombre",
    });
  }
}

function getGeneralData(){
  clave = document.getElementById("txtClave").value;
  codigo_barras = document.getElementById("txtCodigoBarras").value;
  nombre = document.getElementById("txtNombre").value;
  categoria = document.getElementById("cmbCategoria").value;
  var marca = document.getElementById("cmbMarca").value;
  precio_compra = document.getElementById("txtPrecioCompra").value;
  precio_compra_sin_impuesto = document.getElementById("txtPrecioCompraSinImpuestos").value;
  chkprecio_compra_neto = document.getElementById("chkPrecioCompraNeto").checked === true ? 1 : 0;
  utilidad1 = document.getElementById("txtUtilidad1").value !== "" && document.getElementById("txtUtilidad1").value !== null ? document.getElementById("txtUtilidad1").value : 0; 
  utilidad2 = document.getElementById("txtUtilidad2").value !== "" && document.getElementById("txtUtilidad2").value !== null ? document.getElementById("txtUtilidad2").value : 0;
  utilidad3 = document.getElementById("txtUtilidad3").value  !== "" && document.getElementById("txtUtilidad3").value !== null ? document.getElementById("txtUtilidad3").value : 0;
  utilidad4 = document.getElementById("txtUtilidad4").value  !== "" && document.getElementById("txtUtilidad4").value !== null ? document.getElementById("txtUtilidad4").value : 0;
  precio_venta1 = document.getElementById("txtPrecioVenta1").value !== "" && document.getElementById("txtPrecioVenta1").value !== null ? document.getElementById("txtPrecioVenta1").value : 0;
  precio_venta2 = document.getElementById("txtPrecioVenta2").value !== "" && document.getElementById("txtPrecioVenta2").value !== null ? document.getElementById("txtPrecioVenta2").value : 0;
  precio_venta3 = document.getElementById("txtPrecioVenta3").value !== "" && document.getElementById("txtPrecioVenta3").value !== null ? document.getElementById("txtPrecioVenta3").value : 0;
  precio_venta4 = document.getElementById("txtPrecioVenta4").value !== "" && document.getElementById("txtPrecioVenta4").value !== null ? document.getElementById("txtPrecioVenta4").value : 0;
  precio_venta_neto1 = document.getElementById("txtPrecioVentaNeto1").value !== "" && document.getElementById("txtPrecioVentaNeto1").value !== null ? document.getElementById("txtPrecioVentaNeto1").value : 0;
  precio_venta_neto2 = document.getElementById("txtPrecioVentaNeto2").value !== "" && document.getElementById("txtPrecioVentaNeto2").value !== null ? document.getElementById("txtPrecioVentaNeto2").value : 0;
  precio_venta_neto3 = document.getElementById("txtPrecioVentaNeto3").value !== "" && document.getElementById("txtPrecioVentaNeto3").value !== null ? document.getElementById("txtPrecioVentaNeto3").value : 0;
  precio_venta_neto4 = document.getElementById("txtPrecioVentaNeto4").value !== "" && document.getElementById("txtPrecioVentaNeto4").value !== null ? document.getElementById("txtPrecioVentaNeto4").value : 0;
  chkLote = document.getElementById("chkLote").checked === true ? 1 : 0;
  chkSerie = document.getElementById("chkSerie").checked === true ? 1 : 0;
  chk_fecha_caducidad = document.getElementById("chkCaducidad").checked === true ? 1 : 0;

  if(document.getElementById('chkProduct').checked === true){
    chk_tipo_producto = 4;
  } else if(document.getElementById('chkService').checked === true){
    chk_tipo_producto = 5;
    marca = 1;
    $("#cmbMarca").removeAttr("required");
  }

  chk_receta = document.getElementById("chkReceta").checked === true ? 1 : 0;
  descripcion = document.getElementById('txaDescriptionProduct').value;

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

function getTaxProduct(){
  tax_product_aux = "";
  var tax_product = '"tax_product" : [';

  if(table_tax.rows().count()){
    res = table_tax.rows().data();
    res.map((row) =>{
      tax_product_aux += '{"tax_id":"'+ row[0] +'","rate":"'+ row[2] +'"},'
      
    })
  }

  tax_product += tax_product_aux.substring(0,tax_product_aux.length-1);
  tax_product += ']';

  return tax_product;
}

function getFiscalData(){
  clave_sat = document.getElementById("txtClaveSatId").value;
  clave_unidad = document.getElementById("txtUnidadMedidaId").value;
  var fiscal_data = 
  '"fiscal_data" :' +
    '[{' +
      '"clave_sat" : "' + clave_sat + '",' +
      '"clave_unidad" : "' + clave_unidad + '"' +
    '}]';

    return fiscal_data;
}

function getStockData(){
  stock_minimo = document.getElementById("txtStockMinimo").value;
  stock_maximo = document.getElementById("txtStockMaximo").value;
  punto_reorden = document.getElementById("txtPuntoReorden").value;
  chkLote = document.getElementById("chkLote").checked === true ? 1 : 0;
  chkSerie = document.getElementById("chkSerie").checked === true ? 1 : 0;
  chk_fecha_caducidad = document.getElementById("chkCaducidad").checked === true ? 1 : 0;
  stock_general = document.getElementById("txtExistencia").value;
  sucursal =  document.getElementById("txtBranchOfficeId").value;
  stocks = "";

  stock_data = 
  '"stock_data" : ' +
    '[{' +
      '"sucursal":"'+ sucursal +'",' +
      '"stock_minimo":"' + stock_minimo + '",' +
      '"stock_maximo":"' + stock_maximo + '",' +
      '"punto_reorden":"' + punto_reorden + '",' +
      '"stock_general":"' + stock_general + '",' +
      '"chkLote":"' + chkLote + '",' +
      '"chkSerie":"' + chkSerie + '",' +
      '"fecha_caducidad":"' + chk_fecha_caducidad + '",' +
      getStocksTable() +
    '}]';

    return stock_data;
}

function getStocksTable(){
  var rows = document.querySelectorAll("#tblExistProduct tbody tr");
  var stocks_input = Array();
  var lots_input = Array();
  var expired_date = Array();
  var stocks_data = '"stocks_data" : [';
  var stocks_data_aux = "";
  rows.forEach(function(row) {
    quantities_stock = row.querySelector('input[name="txtExistProductQuantity"]');
    lots_stock = row.querySelector('input[name="txtExistProductLot"]');
    expired_dates_stock = row.querySelector('input[name="txtExistProductExpired"]')

    stocks_input.push(quantities_stock);
    lots_input.push(lots_stock);
    expired_date.push(expired_dates_stock);
  });

  for (let i = 0; i < stocks_input.length; i++) {
    
    stocks_data_aux += 
    '{' +
      '"quantity":"' + stocks_input[i].value + '",' +
      '"lot":"' + lots_input[i].value + '",' +
      '"expired_date":"' + expired_date[i].value + '"' +
    '},';
  }

  stocks_data += stocks_data_aux.substring(0,stocks_data_aux.length-1);
  stocks_data += ']';

  return stocks_data;
  
}

function uploadImg(){
  const file = document.querySelector('#flUploadImage').files[0];
  if(file !== null && file !== "" && file !== undefined){
    console.log("listo para subir");
  } else {
    console.log("no estoy listo, no he sido seleccionado");
  }
}

function clearAlertsInput(input,input1,text){
  const a = document.querySelector(input);
  //const b = !input1 ? document.querySelector(input1) : "";
  if(a.classList.contains('is-invalid')){
    document.getElementById('invalid-'+text).style.display = "none";
    a.classList.remove('is-invalid');
  }
  if(input1 !== ""){
    const b = document.querySelector(input1);
    if(b.classList.contains('is-invalid')){
      b.classList.remove('is-invalid');
    }
  }
  
}

function getAllInputsModal(form){
  
  f = document.getElementById(form).reset();
  f1 = document.querySelectorAll("#"+form+" input[type='hidden']");
  f2 = document.querySelectorAll("#"+form+" img");

  if(f1.length > 0){
    for (let i = 0; i < f1.length; i++) {
      f1[i].value = "";
    }
  }

  if(f2.length > 0){
    for (let i = 0; i < f2.length; i++) {
      f2[i].setAttribute("src","../../img/Productos/agregar.svg");
    }
  }

}

function getAllInputsInvalid(form){
  const val = document.querySelector('#'+form);
  var arr = val.elements;
  $ban = false; 
  for (let i = 0; i < arr.length; i++) {
    if(!arr[i].classList.contains('is-invalid')){
      ban = true;
    } else {
      ban = false;
      break;
    }
  }
  return ban;
}

function getIfProductoKeyExist(value)
{
  $.ajax({
    method: "POST",
    url: "php/funciones.php",
    data: {
      clase:'get_data',
      funcion:'get_ifProductoKeyExist',
      value:value
    },
    dataType: 'json',
    success: function(response){
      if(parseInt(response) > 0){
        $("#invalid-productKey").css("display", "none");
        $("#invalid-productKeyRepeat").css("display", "block");
        $("#txtClave").addClass("is-invalid");
      } else {
        $("#invalid-productKey").css("display", "none");
        $("#invalid-productKeyRepeat").css("display", "none");
        $("#txtClave").removeClass("is-invalid");
      }
    }

  });
}

function checkAlertForm()
{
  if(
    !$("#chkProduct").hasClass("is-invalid") &&
    !$("#chkService").hasClass("is-invalid") &&
    !$("#txtClave").hasClass("is-invalid") &&
    !$("#txtNombre").hasClass("is-invalid") &&
    !$("#cmbCategoria").hasClass("is-invalid") &&
    !$("#cmbMarca").hasClass("is-invalid") &&
    !$("#txtPrecioCompra").hasClass("is-invalid") &&
    !$("#txtUtilidad1").hasClass("is-invalid") &&
    !$("#txtClaveSatId").hasClass("is-invalid") &&
    !$("#txtUnidadMedidaId").hasClass("is-invalid")
  ){
    $("#general-data-product-tab").removeClass("is-invalid-tab");
    $("#additional-data-product-tab").removeClass("is-invalid-tab");
    document.getElementById("btnAddProduct").removeAttribute("disabled");
  } else if(
    !$("#chkProduct").hasClass("is-invalid") &&
    !$("#chkService").hasClass("is-invalid") &&
    !$("#txtClave").hasClass("is-invalid") &&
    !$("#txtNombre").hasClass("is-invalid") &&
    !$("#cmbCategoria").hasClass("is-invalid") &&
    !$("#cmbMarca").hasClass("is-invalid") &&
    !$("#txtPrecioCompra").hasClass("is-invalid") &&
    !$("#txtUtilidad1").hasClass("is-invalid")
  ){
    $("#general-data-product-tab").removeClass("is-invalid-tab");
  } else if(
    !$("#txtClaveSatId").hasClass("is-invalid") &&
    !$("#txtUnidadMedidaId").hasClass("is-invalid")
  ){
    $("#additional-data-product-tab").removeClass("is-invalid-tab");
  }
}

