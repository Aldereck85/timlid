var cantidad_a_consumir = [];
var lotes_existencia = [];
var values = [];
var workgroup = [];
var colectivos = [];

$(document).ready(function(){
  
  cmbSucursal = new SlimSelect({
    select: "#cmbSucursal",
    placeholder: "Seleccione una sucursal...",
  });

  cmbProducto = new SlimSelect({
    select: "#cmbProducto",
    placeholder: "Seleccione un producto...",
  });

  cmbResponsable = new SlimSelect({
    select: "#cmbResponsable",
    placeholder: "Seleccione un responsable...",
  });

  cmbGrupoTrabajo = new SlimSelect({
    select: "#cmbGrupoTrabajo",
    placeholder: "Seleccione un grupo de trabajo...",
    addable : function(value){
      addWorkGroup(value);
      return value;
    }

    });
    new SlimSelect({
      select: "#cmbLot",
      placeholder: "Seleccione un lote...",

  });
  
  loadCombo("sucursales","#cmbSucursal","","","Seleccione una sucursal...");
  loadCombo("responsable","#cmbResponsable","","","Seleccione un responsable...");
  loadCombo("grupoTrabajo","#cmbGrupoTrabajo","","","Seleccione un grupo de trabajo...");
  

  $("#cmbSucursal").on("change",function(){
    var sucursal = $(this).val();
    if(sucursal !== null && sucursal !== ""){
      cmbProducto.enable();
      loadCombo("productos","#cmbProducto","","","Seleccione un producto...");
      $("#txtFechaPrevista").prop("disabled",false);
      
      $("#cmbProducto").on("change",function(){
        var val = $(this).val();
        loadProductCompoundsTable("tblMateriales",val,sucursal);
        $("#txtCantidad").prop("disabled",false);
        cmbResponsable.enable();
        cmbGrupoTrabajo.enable();
        //cmbMaterial.enable();
        $("#agregar_material").removeClass("disabled_link");
        $("#agregar_material").addClass("enabled_link");
        $("#guardar_ordenProduccion").removeClass("disabled_link");
        $("#guardar_ordenProduccion").addClass("enabled_link");
        $("#chkMoreMaterials_enabled").css("display","block");

      });
    }
  });

  
  
  $("#txtCantidad").on("keyup",function(){

    if(values.length !== 0) { values = [] };
    var cantidad = $(this).val();
    
    var countSelect = $("#tblMateriales tbody tr").length;
    
    var a_consumir_table;
    var stock_table;
    var ban = false;
    $("#tblMateriales tbody tr").each(function(i){
      var currentRow = $(this);
      console.log(cantidad_a_consumir[i]);
      if(colectivos[i] != ''){
        currentRow.find("td:eq(4)").text(((cantidad / colectivos[i]) * cantidad_a_consumir[i]).toFixed(3))
      }else{
        currentRow.find("td:eq(4)").text((cantidad * cantidad_a_consumir[i]).toFixed(3))
      }
      
      var id = currentRow.find("td:eq(0)").text();
      if(colectivos[i] != ''){
        a_consumir_table = currentRow.find("td:eq(4)").text();
      }else{
        a_consumir_table = currentRow.find("td:eq(4)").text();
      }
      stock_table = parseInt(currentRow.find("td:eq(5)").text());
      var clave = currentRow.find("td:eq(1)").text();
      var descripcion = currentRow.find("td:eq(2)").text();
      var producto = clave + " - " + descripcion;
      values.push({id:id,quantity:a_consumir_table});
      if(a_consumir_table > stock_table){
        currentRow.find("td:eq(5)").css({"background-color":"#ff4444","color":"white"});
        ban = false;
      } else {
        currentRow.find("td:eq(5)").css({"background-color":"#00C851","color":"white"});
        ban = true;
      }
      
    });
  });

  $("#txaNotas").keyup(function(){
    var maxLengthNotas = 255;
    var textLength = $(this).val().length;
   
    var ada = maxLengthNotas - textLength;

    $("#caracter_limit").html("Limite de caracteres: " + ada);
  });

});

$(document).on("focus","input[type=number]",function(){
  $(this).select();
});

$(document).on("click","#guardar_ordenProduccion",function(){

  const swalWithBootstrapButtons = getHeadAlertSwan();

  rowsTable =$('#tblMateriales tbody tr').length;

  contador = 0;
  var val = "";
  var aux_values_sum = [];
  var no_stock = [];
  var ban = false;
  
  $("#tblMateriales tbody tr").each(function(i){
    var currentRow = $(this);
    
    val = parseInt(currentRow.find("td:eq(5)").text());
    if(val === 0){
      ban = true;
      no_stock.push(
        {
          id: parseInt(currentRow.find("td:eq(0)").text()),
          text: currentRow.find("td:eq(1)").text() + " - " +  currentRow.find("td:eq(2)").text()
        }
      );
    }

  });
  compound_no_stock = "";
  cont = 1;
  no_stock.forEach(function(i){
    if(no_stock.length > 1){
      if(cont < no_stock.length){
        compound_no_stock += i.text + ", ";
      } else {
        compound_no_stock = compound_no_stock.substring(0,compound_no_stock.length-2);
        compound_no_stock += " y " + i.text;
      }
      
    } else {
      compound_no_stock += i.text;
    }
    cont++;
  });
  if(no_stock.length > 1){
    no_stock_itmes = "Los compuestos " + compound_no_stock + " no tienen stock en el inventario.";
  } else {
    no_stock_itmes = "El compuesto " + compound_no_stock + " no tiene stock en el inventario.";
  }

  lotes_existencia.forEach( index => {
    const data = aux_values_sum.find( i => i.id === index.id);
    if(!data){
      aux_values_sum.push({id:index.id,cantidad: parseFloat(index.cantidad),product: index.producto});
    } else {
      data.cantidad = parseFloat(data.cantidad) + parseFloat(index.cantidad);
    }
  });
  mensaje = "";
  
  if ($("#order_production-data")[0].checkValidity()){
    var badBranch =
    $("#invalid-branch").css("display") === "block" ? false : true;
    var badExpectedDate =
      $("#invalid-expectedDate").css("display") === "block" ? false : true;
    var badProduct = 
      $("#invalid-product").css("display") === "block" ? false : true;
    var badQuantity = 
      $("#invalid-quantity").css("display") === "block" ? false : true;
    var badResponsable =
    $("#invalid-responsable").css("display") === "block" ? false : true;
    var badWorkGroup =
    $("#invalid-workGroup").css("display") === "block" ? false : true;
    
    if(badBranch &&
      badExpectedDate &&
      badProduct &&
      badQuantity &&
      badResponsable &&
      badWorkGroup)
    {
    
      if(ban === true){
        
        swalWithBootstrapButtons.fire({
          title: "Aviso",
          html: no_stock_itmes + "<br><br>¿Desea crear una notificación para que se compré lo que hace falta?",
          icon: "warning",
          showCancelButton: true,
          confirmButtonText: '<span class="verticalCenter">Aceptar</span>',
          cancelButtonText: '<span class="verticalCenter">Cancelar</span>',
          reverseButtons: false,
        }).then((result) => {
          if (result.isConfirmed) {
            console.log(lotes_existencia);
            saveOrderProduction(swalWithBootstrapButtons,lotes_existencia);
          } else {
            console.log(lotes_existencia);
            saveOrderProduction(swalWithBootstrapButtons,lotes_existencia);
          }
        });

      } else {
        console.log(lotes_existencia);
        saveOrderProduction(swalWithBootstrapButtons,lotes_existencia);
      }
       
    } 
  } else {
    
    if (!$("#cmbSucursal").val()) {
      $("#invalid-branch").css("display", "block");
      $("#cmbSucursal").addClass("is-invalid");
    }
      
    if (!$("#txtFechaPrevista").val()) {
      $("#invalid-expectedDate").css("display", "block");
      $("#txtFechaPrevista").addClass("is-invalid");
    }

    if (!$("#cmbProducto").val()) {
      $("#invalid-product").css("display", "block");
      $("#cmbProducto").addClass("is-invalid");
    }

    if (!$("#txtCantidad").val()) {
      $("#invalid-quantity").css("display", "block");
      $("#txtCantidad").addClass("is-invalid");
    }

    if (!$("#cmbResponsable").val()) {
      $("#invalid-responsable").css("display", "block");
      $("#cmbResponsable").addClass("is-invalid");
    }
    //console.log($("#cmbGrupoTrabajo option:selected").length);
    if ($("#cmbGrupoTrabajo option:selected").length === 0) {
      $("#invalid-workGroup").css("display", "block");
      $("#cmbGrupoTrabajo").addClass("is-invalid");
    }
  }
});

$(document).on("click","#chkMoreMaterials",function(){
  if($(this).is(":checked")){
    $("#add_more_materials").css("display","block");
  } else {
    $("#add_more_materials").css("display","none");
  }
});

$(document).on("change","#cmbSucursal",function(){
  if($(this).hasClass("is-invalid")){
    $("#invalid-branch").css("display", "none");
    $("#cmbSucursal").removeClass("is-invalid");
  }
});

$(document).on("change","#txtFechaPrevista",function(){
  if($(this).hasClass("is-invalid")){
    $("#invalid-expectedDate").css("display", "none");
    $("#txtFechaPrevista").removeClass("is-invalid");
  }
});

$(document).on("change","#cmbProducto",function(){
  if($(this).hasClass("is-invalid")){
    $("#invalid-product").css("display", "none");
    $("#cmbProdcuto").removeClass("is-invalid");
  }
});

$(document).on("keyup","#txtCantidad",function(){
  if($(this).hasClass("is-invalid")){
    $("#invalid-quantity").css("display", "none");
    $("#txtCantidad").removeClass("is-invalid");
  }
});

$(document).on("change","#cmbResponsable",function(){
  if($(this).hasClass("is-invalid")){
    $("#invalid-responsable").css("display", "none");
    $("#cmbResponsable").removeClass("is-invalid");
  }
});

$(document).on("change","#cmbGrupoTrabajo",function(){
  if($(this).hasClass("is-invalid")){
    $("#invalid-workGroup").css("display", "none");
    $("#cmbGrupoTrabajo").removeClass("is-invalid");
  }
});

$(document).on("change",".workgroup-select",function(e){
  var val = $(this).val();

  for (let i = 0; i < val.length; i++) {
    
    if(!workgroup.find( e => e.id === val[i])){
      workgroup.push(
        {
          id:val[i],
        }
      );
    }
  }
});

$(document).on("mousedown",".workgroup-select .ss-value-delete",function(e){
  val1 = e.target.previousElementSibling.innerHTML
  value = $(".workgroup-select option").filter(function(){
    return $(this).text() === val1;
    }).first().attr("value");
  removeItemFromArr(workgroup,value);
});

$(document).on("focus","input[type=text]",function(){
  $(this).select();
});

$(document).on("click",".add_lot",function(){

  cantidad =  $("#txtCantidad").val();
  if(cantidad !== ""){
    material_id = $(this).data("id");
    sucursal_id = $("#cmbSucursal").val();
    txtRowIndex = $(this).closest("tr").index();
    $("#txtCantidadModal").val("");
    $("#tblMaterialsLot tbody").html("");
    $.ajax({
      url: "../../php/funciones_copy.php",
      method: "POST",
      data: {
        clase: "get_data",
        funcion: "get_compoundsData",
        value: material_id,
      },
      datatype: "json",
      success : function(response){
        res = JSON.parse(response);
        $("#txtMaterialId").val(material_id);
        $("#txtRowIndex").val(txtRowIndex);
        data_send = '{"material_id":"'+material_id+'","sucursal_id":"'+sucursal_id+'"}'
        $("#add_lot_modal").modal("show");
        $("#txtProductModal").val(res[0].producto);
        loadCombo("lots","#cmbLot","",data_send,"Selecion un lote...");

        console.log(lotes_existencia);

        if(lotes_existencia.length > 0){
          
          lotes_existencia.forEach(function(i){
            
            if(parseInt(i.id) === parseInt(material_id)){
              if(i.lote !== null && i.cantidad !== null){
                $("#tblMaterialsLot tbody").
                append(
                  '<tr>'+
                    '<td>' + i.lote + '</td>' +
                    '<td>' + i.cantidad + '</td>' +
                    '<td><a class="btn-table-custom--blue delete_lot" href="#"><i class="fas fa-trash-alt"></i></a></td>'+
                  '</tr>'
                );
              }
            }
          });
          
        }
      },
      error: function(error){
        console.log(error);
      }
    });
  } else {
    Lobibox.notify("error", {
      size: "mini",
      rounded: true,
      delay: 3500,
      delayIndicator: false,
      position: "center top",
      icon: true,
      img: "../../../../img/timdesk/checkmark.svg",
      msg: "No ingresó una cantidad a producir",
      sound:false,
    });
  }
});

countClic = 0;

$(document).on("click","#add_lote",function(){
  html = "";
  aux_values_sum = [];
  rowIndex = $("#txtRowIndex").val();
  cantidad_consumir = parseFloat($("#tblMateriales tbody tr:eq("+rowIndex+")").find("td:eq(4)").text());
  txtlote = $("#cmbLot option:selected").text();
  lote = $("#cmbLot option:selected").val();
  aux = lote.split(",");
  cantidad = parseFloat($("#txtCantidadModal").val());
  ban = true;
  cantidad_producir = parseFloat($("#txtCantidad").val());
  material_id =  $("#txtMaterialId").val();
  countClic++;
  $("#txtNoInsert").val(countClic);
  cantidad_total = 0;
  cantidad_total_x_lote=0;
  sucursal_id = $("#cmbSucursal").val();
  console.log($("#tblMaterialsLot tbody tr").length);
  if($("#tblMaterialsLot tbody tr").length > 0){
    for(var i = 0; i < $("#tblMaterialsLot tbody tr").length; i++){
      //console.log(parseFloat($("#tblMaterialsLot tbody tr:eq(" + i + ")").find("td:eq(1)").text()));
      if(parseFloat($("#tblMaterialsLot tbody tr:eq(" + i + ")").find("td:eq(1)").text()) !== 0){
        cantidad_total += parseFloat($("#tblMaterialsLot tbody tr:eq(" + i + ")").find("td:eq(1)").text());
      } else {
        cantidad_total = 0;
      }
      if($("#tblMaterialsLot tbody tr:eq(" + i + ")").find("td:eq(0)").text() == aux[1]){
        cantidad_total_x_lote += parseFloat($("#tblMaterialsLot tbody tr:eq(" + i + ")").find("td:eq(1)").text());
      } else {
        cantidad_total_x_lote = 0;
      }
    };
    
    if((cantidad_total + cantidad) > cantidad_consumir){
      ban = false;
    }
  } else {
    if(cantidad > cantidad_consumir)
    ban = false;
  }

  if(ban){
    //console.log(lotes_existencia);
    
    if(parseInt(aux[2]) >= parseFloat(cantidad + cantidad_total_x_lote)){
      //if(lotes_existencia.length > 0){
        
        const lote_void = lotes_existencia.find( i => i.lote === null && i.id === aux[0]);
        const data = lotes_existencia.find( i => i.lote === aux[1] && parseInt(i.id) === parseInt(aux[0]));
        
        if(!data){
          if(!lote_void){
            lotes_existencia.push
              (
                {
                  id: aux[0],
                  lote: aux[1],
                  cantidad: parseFloat(cantidad),
                  a_consumir: cantidad_consumir
                }
              );
            } else {
              lote_void.lote = aux[1];
              lote_void.cantidad = parseFloat(cantidad);
              lote_void.a_consumir = cantidad_consumir
            }
          
        } else {
          data.cantidad = parseFloat(cantidad) + parseFloat(data.cantidad);
        }
      console.log("después",lotes_existencia);
      lotes_existencia.forEach(function(i){

        if(parseInt(i.id) === parseInt(material_id)){
          html += '<tr>'+
                    '<td>' + i.lote + '</td>' +
                    '<td>' + i.cantidad + '</td>' +
                    '<td><a class="btn-table-custom--blue delete_lot" href="#"><i class="fas fa-trash-alt"></i></a></td>'+
                  '</tr>';
          }
      })
    
      $("#tblMaterialsLot tbody").html(html);

      document.querySelector("#cmbLot").slim.set("");
      $("#txtCantidadModal").val("");
      
    } else {
      Lobibox.notify("error", {
        size: "mini",
        rounded: true,
        delay: 3500,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../../../img/timdesk/checkmark.svg",
        msg: "La cantidad es mayor a la cantidad en existencia",
        sound:false,
      });
    }

  }else{
    Lobibox.notify("error", {
      size: "mini",
      rounded: true,
      delay: 3500,
      delayIndicator: false,
      position: "center top",
      icon: true,
      img: "../../../../img/timdesk/checkmark.svg",
      msg: "La cantidad es mayor a la cantidad a producir",
      sound:false,
    });
  }
});

$(document).on("click","#tblMaterialsLot .delete_lot",function(){
  $(this).closest("tr").remove();
  val1 = $(this).closest("tr").find("td:eq(0)").text();
  val2 = $("#txtMaterialId").val();
  
  removeItemFromArrLot(lotes_existencia, val1, val2);
});

$(document).on("click","#btnAddLot",function(){
  $("#add_lot_modal").modal("hide");
  $("#tblMaterialsLot tbody").html("");
  cantidad = $("#txtCantidadModal").val();
  var rowIndex = parseInt($("#txtRowIndex").val());
  var cadena = "";
  material_id = $("#txtMaterialId").val();
  lotes_existencia.forEach(function(i){
    if(i.id === material_id){
      cadena += "Lote: " +i.lote + " - Cantidad: " + i.cantidad + "<br>";
    } 
    
  });
  
  $("#tblMateriales tbody tr:eq("+rowIndex+")").find("td:eq(6)").html(cadena);
  countClic = 0;
  
});

$(document).on("click","#btnCancelarActualizacion",function(){
  for (let i = 0; i < countClic; i++) {
    lotes_existencia.pop();
  }
  $("#txtCantidadModal").val("");
  
});

$(document).on("click","#btn-add_more_materials",function(){
  product_id = $("#cmbProducto").val()
  url = "../lista_materiales/editar_material.php?lstm=631";
  $().redirect(url,{
    lstm:product_id
  },'GET');
  
});

function loadCombo(funcion,input,data,value,texto){

  $.ajax({
    url: "../../php/funciones_copy.php",
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

function loadCombo1(funcion,input,data,value,texto){

  $.ajax({
    url: "../../php/funciones_copy.php",
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

function setFormatDatatables() {
  var idioma_espanol = {
    sProcessing: "Procesando...",
    sZeroRecords: "No se encontraron resultados",
    sEmptyTable: "Ningún dato disponible en esta tabla",
    sSearch: "",
    sLoadingRecords: "Cargando...",
    searchPlaceholder: "",
    oPaginate: "",
  };
  return idioma_espanol;
}



function loadProductCompoundsTable(table,value,sucursal){
  $.ajax({
    url: "../../php/funciones_copy.php",
    method: "POST",
    data: {
      clase: "get_data",
      funcion: "get_productCompoundsTable",
      value: value,
      id: sucursal
    },
    datatype: "json",
    success : function(response){
      
      html = "";
      tableData = JSON.parse(response);
      tableData = tableData.data;
      cantidad_a_consumir.splice(0,cantidad_a_consumir.length);
      colectivos.splice(0,colectivos.length);
       
     
      $.each(tableData,function(i){

        if(tableData[i].stock < tableData[i].a_consumir){
          style_background = 'background-color:#ff4444;color:white';
        } else {
          style_background = 'background-color:#00C851;color:white';
        }
        
        html += '<tr>'+
                  '<td style="display:none">' + tableData[i].id +'</td>'+
                  '<td>' + tableData[i].clave +'</td>'+
                  '<td>' + tableData[i].descripcion + '</td>'+
                  '<td>' + tableData[i].unidad_medida + '</td>'+
                  '<td>' + tableData[i].a_consumir + '</td>'+
                  '<td style="' + style_background + '">' + tableData[i].stock + '</td>'+
                  '<td>' + tableData[i].lote + '</td>'+
                  '<td>' + tableData[i].funciones +'</td>'+
                '</tr>';
        cantidad_a_consumir.push(tableData[i].a_consumir);
        colectivos.push(tableData[i].colectivos); 
        // if(tableData[i].lote !== "Sin lote"){
          lotes_existencia.push
          (
            {
              id: tableData[i].id,
              lote: null,
              cantidad: null,
              a_consumir: tableData[i].a_consumir
            }
          );
        // }

      });
     
      $("#"+table + " tbody").html(html);

    },
    error : function(error){
      console.log(error);
    }
  });
  

  
}

function removeItemFromArr ( arr, item ) {
  var i = arr.findIndex( e => e.texto === item );
  if ( i !== -1 ) {
      arr.splice( i, 1 );
  }
}

function removeItemFromArrLot ( arr, item, item2 ) {
  var i = arr.findIndex( e => e.lote === item && e.id === item2 );
  if (i === -1) return;
  arr.splice(i, 1);
}

function addWorkGroup(value){
  $.ajax({
    url: "../../php/funciones_copy.php",
    method: "POST",
    data: {
      clase: "save_data",
      funcion: "save_workgroup",
      value: value,
    },
    datatype: "json",
    success : function(response){
      workgroup.push({
        id:response
      })
    },
    error: function(error){
      console.log(error);
    }
  });
}



function getHeadAlertSwan(){
  return Swal.mixin({
    customClass: {
      actions: "d-flex justify-content-around",
      confirmButton: "btn-custom btn-custom--border-blue",
      cancelButton: "btn-custom btn-custom--blue",
    },
    buttonsStyling: false,
  });
}

function getConcatJson(text){
  var val_multi = [];
  if(text !== null){
    text.forEach(function(i){
      val_multi.push(parseInt(i.id));removeItemFromArr(workgroup,value);
    });
  }
  return val_multi;
}

function saveOrderProduction(swalWithBootstrapButtons,lotes_existencia){
  $("#guardar_ordenProduccion").removeClass("enabled_link");
  $("#guardar_ordenProduccion").addClass("disabled_link");
  $(".add_lot").addClass("disabled_link");
  $("#btn-add_more_materials").addClass("disabled_link");
  document.querySelector("#cmbProducto").slim.disable();
  $("#txtCantidad").attr("readonly",true);
  document.querySelector("#cmbResponsable").slim.disable();
  $("#txtFechaPrevista").attr("readonly",true);
  document.querySelector("#cmbSucursal").slim.disable();
  $("#txaNotas").attr("readonly",true);
  document.querySelector("#cmbGrupoTrabajo").slim.disable();
  
  swalWithBootstrapButtons.fire({
    title: "Aviso",
    html: "Se van a guardar las cantidad a consumir de los componentes para posteriormente hacer el ajuste en inventario cuando la orden de producción sea aceptada.<br><br>¿Desea continuar?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: '<span class="verticalCenter">Aceptar</span>',
    cancelButtonText: '<span class="verticalCenter">Cancelar</span>',
    reverseButtons: false,
  }).then((result) => {
    if (result.isConfirmed) {
      aux = [];
      producto = $("#cmbProducto").val();
      cantidad = $("#txtCantidad").val();
      responsable = $("#cmbResponsable").val();
      grupo_trabajo = JSON.stringify(workgroup);
      fecha_prevista = $("#txtFechaPrevista").val();
      sucursal = $("#cmbSucursal").val();
      notas = $("#txaNotas").val();
      
      a_consumir = JSON.stringify(values);
      
      json = '{"producto_id":"' + producto + '",'+
            '"cantidad":"'+cantidad+'",'+
            '"fecha_prevista":"'+fecha_prevista+'",'+
            '"sucursal":"'+sucursal+'",'+
            '"responsable":"'+responsable+'",'+
            '"grupo_trabajo":'+grupo_trabajo+','+
            '"notas":"'+notas+'",'+
            '"data_materiales":['
      ;
      
      
      if(lotes_existencia.length > 0){
        lotes_existencia.forEach(e => {
          values.forEach(j =>{
            if(e.id === j.id){
              json += '{"id":"'+e.id+'",'+
                  '"lote":"'+e.lote+'",'+
                  '"cantidad":"'+e.cantidad+'",'+
                  '"a_consumir":"'+j.quantity+'"'+
                  '},'
            }
          });
          
        });

        json = json.substring(0,json.length-1);
      }

      json += "]}";

      
      $.ajax({
        url: "../../php/funciones_copy.php",
        method:"POST",
        data: {
          clase: "save_data",
          funcion: "save_productionOrder",
          value: json
        },
        datatype: "json",
        success: function(respuesta){
          
          if(respuesta === "1"){
            Lobibox.notify("success", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../../img/timdesk/checkmark.svg",
              msg: "La orden de producción se ha guardado con éxito",
              sound:false,
            });
            
             setTimeout(function() {
               window.location.href = 'index.php';
             }, 1000);
          } else {
            Lobibox.notify("error", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../../img/timdesk/checkmark.svg",
              msg: "Hubo un problema al guardar la orden de producción",
              sound:false,
            });
            
            
          }
        },
        error: function(error){
          console.log(error);
        }
      });

    } else {
      $("#guardar_ordenProduccion").removeClass("disabled_link");
      $("#guardar_ordenProduccion").addClass("enabled_link");
      $(".add_lot").removeClass("disabled_link");
      $(".add_lot").addClass("enabled_link");
      $("#btn-add_more_materials").removeClass("disabled_link");
      $("#btn-add_more_materials").addClass("enabled_link");

      document.querySelector("#cmbProducto").slim.enable();
      $("#txtCantidad").attr("readonly",false);
      document.querySelector("#cmbResponsable").slim.enable();
      $("#txtFechaPrevista").attr("readonly",false);
      document.querySelector("#cmbSucursal").slim.enable();
      $("#txaNotas").attr("readonly",false);
      document.querySelector("#cmbGrupoTrabajo").slim.enable();
    }
  });
}