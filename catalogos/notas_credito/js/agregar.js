var tablaF;
var tablaD;
var tablaD2;
var tipoNC;

//Objeto que guarda los conceptos para ser pasados al AJAX
objconceptos = {};
//Objeto que guarda el detalle de la factura consultada {id-clave-serie-lote} 
var objDetallesF = {};
//Contador para sumar mas impustos (Error: si se borra un concepto queda ese espacio)
let countObjs = 1;
//Objeto que guarda los impuestos del concepto
var objImpuestos = {};
//Total facturas Seleccionadas
var TotalFacturas = 0.00;
$(function () {

  $('#tblFacturasCliente').DataTable().on("draw", function(){
    TotalFacturas = 0.00;
    var table = $('#tblFacturasCliente').DataTable();
    var data = table
    .rows()
    .data();
    objTablaFSelected = {};
    for (let index = 0; index < data.length; index++) {
      //console.info(data[index].Monto);
      objTablaFSelected[index] = {Monto : data[index].Monto, Folio : data[index]['Folio factura'], Serie: data[index].Serie, Id: data[index].Id};
      ///Quitar coma y pasar a float.
      ///Guardamos el total de las facturas seleccionadas, para mas tarde compararlo con el total de la nota
      TotalFacturas +=parseFloat((data[index].Monto).replace(",", ""));
    }
    console.log(Object.keys(objTablaFSelected).length);
    if(Object.keys(objTablaFSelected).length>0){
      $("#CargarConceptos").removeClass("disabled");
      $("#CargarConceptos").addClass("parpadea");
      $("#CargarConceptos").css("background-color","#4788e9");
      if(tipoNC == 1){
        $("#conceptos_section").css("display", "block");
        $("#importeVenta_section").css("display", "none");
        CargarCMBFacturas();
      }else{
        $("#conceptos_section").css("display", "none");
        $("#importeVenta_section").css("display", "block");
        CargarCMBVenta();
      }
    }
    //delete objTablaFSelected[(Object.keys(objTablaFSelected).length)];
   // console.info(objTablaFSelected);
    /* cargarCMbClaves(); */
    /* cargarCMBImpustosGen(); */
  });

  $("#cmbFactura").change(function (e) { 
    //e.preventDefault();
    /* cargarCMBImpustosGen(); */
    /* cargarCMbClaves(); */
  });

  $("#ConceptosAJAX").change(function (e) { 
    //e.preventDefault();
    var seleccionado = $("#ConceptosAJAX").find('option:selected').val();
    if(seleccionado != undefined && seleccionado != "undefined"){
      $("#ClaveI").val(conceptos[seleccionado].claveInt);
      $("#ClaveSAT").val(conceptos[seleccionado].id);
      //Si la clave es S/C sin clave, entonces pone el link para ir a editar el producto.
      if(conceptos[seleccionado].id == "S/C"){
        var link = `<input class="form-control disabled" readonly type="text" name="ClaveSAT" value="N/C" id="ClaveSAT" required maxlength="6" placeholder="Ej. ACT" list="listClavesUnidad"> <a href="../inventarios_productos/catalogos/productos/editar_producto.php?p=`+seleccionado+`" target="_blank" rel="noopener noreferrer"> Ir a productos </a></input>`;
        $("#ClaveST").html(link);
      }else{
        var link = `<input class="form-control disabled" readonly type="text" name="ClaveSAT" value="`+conceptos[seleccionado].id+`" id="ClaveSAT" required maxlength="6" placeholder="Ej. ACT" list="listClavesUnidad">`;
        $("#ClaveST").html(link);
      }


      //Si la clave es S/C sin clave, entonces pone el link para ir a editar el producto.
      if(conceptos[seleccionado].ClaveUnit == "S/C"){
        var link = `<input class="form-control disabled" readonly type="text" name="ClaveU" value="N/C" id="ClaveU" required maxlength="6" placeholder="Ej. ACT" onclick="../../inventarios_productos/catalogos/productos" onkeyup="cargarListClaveSat_unidad(this.value)" list="listClavesUnidad"> <a href="../inventarios_productos/catalogos/productos/editar_producto.php?p=`+seleccionado+`" target="_blank" rel="noopener noreferrer"> Ir a productos </a></input>`;
        $("#ClaveUnit").html(link);
      }else{
        var link = `<input class="form-control disabled" readonly type="text" name="ClaveU" value="`+conceptos[seleccionado].ClaveUnit+`" id="ClaveU" required maxlength="6" placeholder="Ej. ACT" onclick="../../inventarios_productos/catalogos/productos" onkeyup="cargarListClaveSat_unidad(this.value)" list="listClavesUnidad">`;
        $("#ClaveUnit").html(link);
        //$("#ClaveU").val(conceptos[seleccionado].ClaveUnit);
      }

      $("#invalid-ClaveU").hide();
      $("#invalid-ClaveI").hide();
      $("#ClaveI").removeClass("is-invalid");
    }

    cargarImpuestos(seleccionado);
  });

  //Pone la serie y el lote que pertenece a la clave en los campos
  $("#cmbDetallesF").change(function (e) { 
 /*    e.preventDefault();
    let idFactura = $("#cmbDetallesF option:selected").val();
    $("#serietxt").val(objDetallesF[idFactura].Serie);
    $("#lotetxt").val(objDetallesF[idFactura].Lote); */
    /* cargarCMBImpustosGen(); */
  });

    crearSelects();
   // loadTblFacturas();
    contructblFacSelected();
    //cargarTblConceptos();
    cargarCMBCliente();
    cargarCMBFdPago();
    cargarCMBRelacion();
    $('#btnAgregarFacturas').click(function (e) { 
        e.preventDefault();
        passSelected();
    });
    $('#btnAgregarVenta').click(function (e) { 
        e.preventDefault();
        passSelected();
    });
    ///
    //Metodo para cargar la tabla tras cargar el modal, arregla el ancho de la cabecera del datatable.
    ///
    //Crea la tabla cada que se muestra el modal.
    $('#mod_agregarFacturas').on('shown.bs.modal', function (e){
        loadTblFacturas();
    });
    //Destruye la tabla cada que se oculta el modal
    $('#mod_agregarFacturas').on('hidden.bs.modal', function (e){
        if(tablaD){
        $("#modal_tblFacturasCliente").DataTable().clear();
        $("#modal_tblFacturasCliente").DataTable().destroy();
        }
    });

    //Cada que la tabla del modal se pinta pone en true los checks que hay en el arreglo objChecksTrue
    tablaD = $('#modal_tblFacturasCliente').DataTable().on("draw", function(){
        let hiddenRows = tablaD.rows().nodes();
        $("input[type='radio']", hiddenRows).prop('checked', false); 
        if(!$.isEmptyObject(objChecksTrue)){
            Object.entries(objChecksTrue).forEach(([key, property]) => {
                $("#"+key , hiddenRows).prop( "checked", true );
            // console.log((id+"-"+ index));
            });
        }
    });

    $('#mod_agregarVenta').on('shown.bs.modal', function (e){
      loadTblVentas();
    });

    //Destruye la tabla cada que se oculta el modal
    $('#mod_agregarVenta').on('hidden.bs.modal', function (e){
        if(tablaD2){
        $("#modal_tblVentasCliente").DataTable().clear();
        $("#modal_tblVentasCliente").DataTable().destroy();
        }
    });

    //Cada que la tabla del modal se pinta pone en true los checks que hay en el arreglo objChecksTrue
    tablaD2 = $('#modal_tblVentasCliente').DataTable().on("draw", function(){
        let hiddenRows = tablaD2.rows().nodes();
        $("input[type='radio']", hiddenRows).prop('checked', false); 
        if(!$.isEmptyObject(objChecksTrue)){
            Object.entries(objChecksTrue).forEach(([key, property]) => {
                $("#"+key , hiddenRows).prop( "checked", true );
            // console.log((id+"-"+ index));
            });
        }
    });

  ///Boton agregar concepto
    $("#addConcepto").click(function (e) { 
      e.preventDefault();
      validarinputs(); 
    });

    $("#btnguardarF").click(function (e) { 
      e.preventDefault();
      if(!($.isEmptyObject(objconceptos))){
        createFactura();
      }else{
        Lobibox.notify("warning", {
          size: "mini",
          rounded: true,
          delay: 3500,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/warning_circle.svg",
          msg: "Alerta, Agregar al menos un concepto!",
        });
      }
      
    });
    $("#btnguardarV").click(function (e) { 
      e.preventDefault();
      if($("#txtDescripcionVenta").val() != "" && $("#txtImporteVenta").val() > 0){
        createNC_venta();
      }else{
        Lobibox.notify("warning", {
          size: "mini",
          rounded: true,
          delay: 3500,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/warning_circle.svg",
          msg: "Datos incompletos",
        });
      }
      
    });
    $("#btnDescargar").click(function (e) { 
      e.preventDefault();
      descargar();
      
    });

  //Recarga tabla del modal con las facturas del cliente seleccionado
  $( "#cmbCliente" ).change(function() {
   objChecksTrue = {};
   //Destruye la tabla anterior
   $("#tblFacturasCliente").DataTable().clear();
   $("#tblFacturasCliente").DataTable().destroy();
   //lleno la tabla con ajax
   contructblFacSelected();
  });

  $("#chkImpuestosInclu").click(function (e) { 
    //e.preventDefault();
    let taxesInclud = ((($('#chkImpuestosInclu').is(":checked")?true:false)));
    //console.log(taxesInclud);
  });
  // Al dar clic en sigueinete simula click en tap
  $("#btnSiguiente").click(function (e) { 
    document.getElementById("CargarConceptos").click();
    if(Object.keys(objTablaFSelected).length<1){
      Lobibox.notify("warning", {
        size: "mini",
        rounded: true,
        delay: 3500,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../img/timdesk/warning_circle.svg",
        msg: "Alerta, Relacione al menos una Factura!",
      });
    }
    console.log(TotalFacturas);
  });
  $("#CargarConceptos").click(function (e) { 
    e.preventDefault();
    $("#CargarConceptos").removeClass("parpadea");
    $("#CargarConceptos").css("background-color","#eaeaea");
    /* cargarCMBImpustosGen(); */
  });
  $("#btnAtras").click(function (e) { 
    e.preventDefault();
    document.getElementById("CargarFacturas").click();
  });
  $("#btnAtrasV").click(function (e) { 
    e.preventDefault();
    document.getElementById("CargarFacturas").click();
  });


});

function cambioImp(){
  ///Cambia texto del precio
    if($('#chkImpuestosInclu').is(":checked")){
      $("#lblPrecio").text("Precio unitario con impuestos:*");
    }else{
      $("#lblPrecio").text("Precio unitario sin impuestos:*");
    }   
}

function focusout(val){
 var importe = val.value;

 val.value=validarMoneda(importe);

}

function  cargarImpuestos(producto){
  console.log(producto);
    //here our function should be implemented 
    var html = '<select class="cmbSlim" name="cmbImpuestosp" id="cmbImpuestosp" multiple>';
    let opcion = "";
    let opciong = "";
    /* let id_detalle = $("#cmbDetallesF option:selected").val(); */
    if(producto){
    //Consulta los impuestos del producto y los pone en un select multiple
    $.ajax({
      type:'POST',
      url: "functions/controller.php",
      dataType: "json",
      data: { clase:"get_data",funcion:"get_ImpuestosPrd", id_producto: producto},
      success: function (data) {
        console.log("Impuestos del Producto: ", data);
        $.each(data, function (i) {
          console.log(data[i]);
          
          if(data[i].FKTipoImpuesto != 3){
            if(data[i].Impuesto != null){
              ///Saca el nombre del impuesto
              let tipo = (data[i].Impuesto).split(' ');
              tipo = tipo[0];
              console.log(tipo);

              //Es impuesto trasladado o retenido
              if(data[i].FKTipoImporte == 1){
                // Es porcentaje
                if(data[i].FKTipoImpuesto == 1){
                  console.log(tipo + " Tasa Trasladado: " + data[i].Tasa);
                  opcion = tipo + " Tasa Trasladado: " + data[i].Tasa;
                  opciong = tipo + " Tasa Trasladado-" + data[i].Tasa;
                }else if(data[i].FKTipoImpuesto == 2){
                  console.log(tipo + " Tasa Retenido: " + data[i].Tasa);
                  opcion = tipo + " Tasa Retenido: " + data[i].Tasa;
                  opciong = tipo + " Tasa Retenido-" + data[i].Tasa;
                }else if(data[i].FKTipoImpuesto == 3){
                  console.log(tipo + " Local: " + data[i].Tasa);
                  opcion = tipo + " Local: " + data[i].Tasa;
                  opciong = tipo + " Local-" + data[i].Tasa;
                }
                
              }else if(data[i].FKTipoImporte == 2){
                // Es cantidad
                if(data[i].FKTipoImpuesto == 1){
                  console.log(tipo + " Cuota Trasladado: " + data[i].Tasa);
                  opcion = tipo + " Cuota Trasladado: " + data[i].Tasa;
                  opciong = tipo + " Cuota Trasladado-" + data[i].Tasa;
                }else if(data[i].FKTipoImpuesto == 2){
                  console.log(tipo + " Cuota Retenido: " + data[i].Tasa);
                  opcion = tipo + " Cuota Retenido: " + data[i].Tasa;
                  opciong = tipo + " Cuota Retenido-" + data[i].Tasa;
                }else if(data[i].FKTipoImpuesto == 3){
                  console.log(tipo + " Local: " + data[i].Tasa);
                  opcion = tipo + " Local: " + data[i].Tasa;
                  opciong = tipo + " Local-" + data[i].Tasa;
                }
              }else if(data[i].FKTipoImporte == 3){
                // Es exento
                if(data[i].FKTipoImpuesto == 1){
                  console.log(tipo + " Exento: " + data[i].Tasa);
                  opcion = tipo + " Exento: " + data[i].Tasa;
                  opciong = tipo + " Exento-" + data[i].Tasa;
                }else if(data[i].FKTipoImpuesto == 3){
                  console.log(tipo + " Local: " + data[i].Tasa);
                  opcion = tipo + " Local: " + data[i].Tasa;
                  opciong = tipo + " Local-" + data[i].Tasa;
                }
              }
            }else{
              console.log("Error Nombre de impuesto nulo");
            }
            
          }else{
            tipo = (data[i].Impuesto).replace('(Local)', '')
            //Es impuesto Local
            console.log(tipo + " Local: " + data[i].Tasa);
            opcion = tipo + " Local: " + data[i].Tasa;
            opciong = tipo + " Local-" + data[i].Tasa;
          }
          if(opcion.length > 1){
            html +=
            '<option selected value="' + opciong + '">' + opcion + "</option>";
          }
          
          //Objeto copia del objeto data en la posision i
          /* objAUX = Object.assign({},data[i])
          //Recorre el objeto auxiliar y lo pone 
          for (const property in objAUX) {
            if(objAUX[property]>0){
              html +=
              '<option value="' +
              property + '-'+ objAUX[property]+
              '">' +
              property+': ' + objAUX[property]+
              "</option>";
            }
          } */
          /* console.log(opcion);
          console.log(opciong); */
        });
        html += '</select>';
        //Pone los proveedores en el select
        $("#cmbImpuestosp").html(html);
        console.log(html);
      },
      error: function (error) {
        console.log("Error");
        console.log(error);
      },
    });
    }

}



//Carga el select con los impuestos de la factura seleccionada.
function cargarCMBImpustosGen(){
  //here our function should be implemented 
  var html = '<select class="cmbSlim" name="cmbImpuestosp" id="cmbImpuestosp" multiple>';
  let id_detalle = $("#cmbDetallesF option:selected").val();
  if(id_detalle){
  //Consulta los proveedores de la empresa
  $.ajax({
    type:'POST',
    url: "functions/controller.php",
    dataType: "json",
    data: { clase:"get_data",funcion:"get_ImpGen", id_factura: id_detalle},
    success: function (data) {
      //console.log("data de proveedor: ", data);
      $.each(data, function (i) {
        //Objeto copia del objeto data en la posision i
        objAUX = Object.assign({},data[i])
        //Recorre el objeto auxiliar y lo pone 
        for (const property in objAUX) {
          if(objAUX[property]>0){
            html +=
            '<option value="' +
            property + '-'+ objAUX[property]+
            '">' +
            property+': ' + objAUX[property]+
            "</option>";
          }
        }
      });
      html += '</select>';
      //Pone los proveedores en el select
      $("#cmbImpuestosp").html(html);
    },
    error: function (error) {
      console.log("Error");
      console.log(error);
    },
  });
  }
}
//Pinta o borra los inputs de los impuestos
function cambio(check){
  if(check.checked) {
    var html =  `<div class= "col-sm-4">
    <div class="col-xl-8 col-lg-6 col-md col-sm col-xs">
      <div class="custom-control custom-switch">
        <input checked type="checkbox" class="custom-control-input" id="checkimpuestos" onchange="cambio(this)">
        <label class="custom-control-label" for="checkimpuestos">impuestos</label><br>
      </div>
      <div class="custom-control custom-switch">
        <input type="checkbox" class="custom-control-input" id="chkImpuestosInclu">
        <label class="custom-control-label" for="chkImpuestosInclu">Impuestos Incluidos</label>
      </div>
    </div>
  </div>            
  <div class= "col-sm-4">
    <div class="col-xl-8 col-lg-6 col-md col-sm col-xs">
        <label for="usr">Iva:*</label>
        <input class="form-control numericDecimal-only" type="text" name="txtIva" value="" id="txtIva" required maxlength="100" placeholder="Ej. Bata quirúgica desechable" onkeyup="escribirNombre()">
        <div class="invalid-feedback" id="invalid-nombreProd">El producto debe tener un Folio de Factura.</div>
    </div>
</div>
<div class= "col-sm-4">
    <div class="col-xl-8 col-lg-6 col-md col-sm col-xs">
        <label for="usr">IEPS:*</label>
        <input class="form-control numericDecimal-only" type="text" name="txtIEPS" value="" id="txtIEPS" required maxlength="100" placeholder="Ej. Bata quirúgica desechable" onkeyup="escribirNombre()">
        <div class="invalid-feedback" id="invalid-nombreProd">El producto debe tener un Folio de Factura.</div>
    </div>
</div>`;
  }else{
    var html =  `<div class= "col-sm-4">
    <div class="col-xl-8 col-lg-6 col-md col-sm col-xs">
      <div class="custom-control custom-switch">
        <input type="checkbox" class="custom-control-input" id="checkimpuestos" onchange="cambio(this)">
        <label class="custom-control-label" for="checkimpuestos">impuestos</label><br>
      </div>
    </div>
  </div>`;
  }
  $("#impuestos").html(html);
}

var impuestos =[];
//Validar cuando se manda a guardar todos los inputs
function validarinputs(){
  let redFlag0 = 0;
  let redFlag1 = 0;
  let redFlag2 = 0;
  let redFlag3 = 0;
  let redFlag4 = 0;
  let redFlag5 = 0;
  let redFlag6 = 0;
  let redFlag7 = 0;

    var seleccionado = $("#ConceptosAJAX").find('option:selected').val();
    let claveP = $("#ClaveSAT").val();
    let ClaveU = $("#ClaveU").val();
    let txtDescripcion = $("#txtDescripcion").val();
    let txtValorUn = $("#txtValorUn").val();
    let txtImporte = $("#txtImporte").val();
    let txtDescuento = $("#txtDescuento").val();
    let txtIva = ((($("#txtIva").val())==""||($("#txtIva").val())==undefined)?"0":($("#txtIva").val()));
    let txtIEPS = ((($("#txtIEPS").val())==""||($("#txtIEPS").val())==undefined)?"0":($("#txtIEPS").val()));
    let taxIncludes = ((($("#chkImpuestosInclu").val())==""||($("#txtIEPS").val())==undefined)?false:true);
    inputID= "ClaveI";
    invalidDivID = "invalid-ClaveI";
    if (($('#'+inputID).val()=="") ||($('#'+inputID).val()==null)) {
      $("#" + inputID).addClass("is-invalid");
      $("#" + invalidDivID).show();
      $("#" + invalidDivID).text("La clave no puede estar vacia");
    } else {
      if(conceptos[seleccionado].id == "S/C"){
        $("#" + inputID).addClass("is-invalid");
        $("#" + invalidDivID).text("El producto no tiene clave de producto");
        $("#" + invalidDivID).show();
      }else{
        $("#" + inputID).removeClass("is-invalid");
        $("#" + invalidDivID).hide();
        $("#" + invalidDivID).text("La clave no puede estar vacia");
        redFlag0 = 1;
      }
      
    }
    inputID= "ClaveSAT";
    invalidDivID = "invalid-ClaveSAT";
    if (($('#'+inputID).val()=="") ||($('#'+inputID).val()==null)) {
      $("#" + inputID).addClass("is-invalid");
      $("#" + invalidDivID).show();
      $("#" + invalidDivID).text("La clave no puede estar vacia");
    } else {
      if(conceptos[seleccionado].id == "S/C"){
        $("#" + inputID).addClass("is-invalid");
        $("#" + invalidDivID).text("El producto no tiene clave de producto");
        $("#" + invalidDivID).show();
      }else{
        $("#" + inputID).removeClass("is-invalid");
        $("#" + invalidDivID).hide();
        $("#" + invalidDivID).text("La clave no puede estar vacia");
        redFlag1 = 1;
      }
      
    }
    inputID= "ClaveU";
    invalidDivID = "invalid-ClaveU";
    if (($('#'+inputID).val()=="") ||($('#'+inputID).val()==null)) {
      $("#" + inputID).addClass("is-invalid");
      $("#" + invalidDivID).text("La clave no puede estar vacia");
      $("#" + invalidDivID).show();
    } else {
      if(conceptos[seleccionado].ClaveUnit == "S/C"){
        $("#" + inputID).addClass("is-invalid");
        $("#" + invalidDivID).text("Error el producto no tiene clave de unidad");
        $("#" + invalidDivID).show();
      }else{
        $("#" + inputID).removeClass("is-invalid");
        $("#" + invalidDivID).hide();
        $("#" + invalidDivID).text("La clave no puede estar vacia");
        redFlag2 = 1;
      }
      
    }
    inputID= "txtDescripcion";
    invalidDivID = "invalid-txtDescripcion";
    if (($('#'+inputID).val()=="") ||($('#'+inputID).val()==null)) {
      $("#" + inputID).addClass("is-invalid");
      $("#" + invalidDivID).show();
      $("#" + invalidDivID).text("La unidad no puede estar vacia");
    } else {
      $("#" + inputID).removeClass("is-invalid");
      $("#" + invalidDivID).hide();
      $("#" + invalidDivID).text("La unidad no puede estar vacia");
      redFlag5 = 1;
    }
    inputID= "txtImporte";
    invalidDivID = "invalid-txtImporte";
    if (($('#'+inputID).val()=="") ||($('#'+inputID).val()==null)) {
      $("#" + inputID).addClass("is-invalid");
      $("#" + invalidDivID).show();
      $("#" + invalidDivID).text("La unidad no puede estar vacia");
    } else {
      $("#" + inputID).removeClass("is-invalid");
      $("#" + invalidDivID).hide();
      $("#" + invalidDivID).text("La unidad no puede estar vacia");
      redFlag7 = 1;
    }
    if((redFlag1 && redFlag2 && redFlag5 && redFlag7 && redFlag0)){
      let claveP = $("#ClaveSAT").val();
      let facturaid = $("#cmbFactura").val();
      let ClaveU = $("#ClaveU").val();
      let Cantidad = $("#txtCantidads").val();
          ClaveU = ClaveU.split(' ');
      ClaveU = ClaveU[0];
      console.log(ClaveU);
      let txtDescripcion = ($("#ConceptosAJAX option:selected").text()) + " - " + $("#ClaveU").val() + ": " +  $("#txtDescripcion").val();
      let txtValorUn = $("#txtValorUn").val();
      let txtImporte = $("#txtImporte").val();
      let txtIva = ((($("#txtIva").val())==""||($("#txtIva").val())==undefined)?"0":($("#txtIva").val()));
      let txtIEPS = ((($("#txtIEPS").val())==""||($("#txtIEPS").val())==undefined)?"0":($("#txtIEPS").val()));
      let taxesInclud = ((($('#chkImpuestosInclu').is(":checked")?true:false)));
      //Construir el objeto de los values 
      if((claveP!= "" && ClaveU!= "" && txtDescripcion!= "" && txtImporte!= "" && Cantidad != "" ) ){
        objImpuestos ={};
        let Impuestos = $("#cmbImpuestosp").val();
        //Guarda los impuestos en un objeto
        if(Impuestos.length == 0){
          objImpuestos = 0;
        }else{
          $.each(Impuestos, function (indexInArray, valueOfElement) { 
            let valueSplit = valueOfElement.split("-");
            objImpuestos[valueSplit[0]] = valueSplit[1];
          });
        }

        let cadenaImpuestos = "";
        for (const property in objImpuestos) {
          console.log(`${property}: ${objImpuestos[property]}`);
          cadenaImpuestos += (`${property}: ${objImpuestos[property]} <br>`);
        }

        var subtotal= 0.00;
        subtotal = (parseFloat(txtImporte) * parseInt(Cantidad));

        //Cantidad='<input disabled class=\"form-control numericDecimal-only\" type=\"text\" name=\"inputs_facturas\" value=\"'.$MP.'\" data-id=\"'.$row['tipo_CuentaCobrar'].'\" placeholder=\"0\" onchange=\"sumarInputs(this)\" id=\"'.$row['id'].'-'.$MF.'\" min=\"1\" maxlength=\"18\"> <div class=\"invalid-feedback\" id=\"invalid-input\">gg</div>';      

        objconceptos[countObjs] = {id_Factura: facturaid, C_Producto_Servicio	: claveP, C_Unidad: ClaveU, TaxesInclud:taxesInclud, Cantidad_p: Cantidad, Subtotal: subtotal,
          Descripcion: txtDescripcion, Importe: txtImporte, 
          Acciones: "<a id=\"deletePago\"><img src=\"../../img/timdesk/delete.svg\" style=\"cursor: pointer;\" width=\"20px\" heigth=\"20px\" float=\"center\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Eliminar\" onclick=\"eliminarConcept("+countObjs+")\"/></a>"
          , strImpuestos: cadenaImpuestos};

          console.log("Impuestos " + JSON.stringify(objImpuestos));
          var htmlreset = '<select name="ConceptosAJAX" class="form-select" id="ConceptosAJAX" aria-label="Default select example"></select>';
         /*  objconceptos[countObjs].taxesGen = objImpuestos; */
         if(objImpuestos == 0 ){
          objconceptos[countObjs].taxesGen = 0;
         }else{
          objconceptos[countObjs].taxesGen = Object.assign({},objImpuestos);
         }
          console.log("Impuestos " + JSON.stringify(objconceptos));


          //Poner en la tabla lo del objeto
          cargarTblConceptosTest("");

          $("#ClaveU").val("");
          $("#ClaveI").val("");
         /*  $("#ConceptosAJAX").text("");
          $("#ConceptosAJAX").val(""); */
          $('#chkImpuestosInclu').prop('checked', false);
          selectConcepto.set('');
          selectConcepto.search('');
          
          $("#ClaveSAT").val("");
          $("#txtDescripcion").val("");
          $("#txtValorUn").val("");
          $("#txtImporte").val("");
          $("#txtCantidads").val("");
          $("#txtDescuento").val("");
          $("#txtIva").val("");
          $("#txtIEPS").val("");
          //Click en la X para deseleccionar el impuesto
          $(".ss-value-delete").click();
          slectImpuestos.set('');
          countObjs++;
        }else{

      }
    }

}
//Validar cada que se preciona una tecla si ya es valido.
function thisIsValid(In){
    if($(In).val()){
      inputID= (In.getAttribute("id"));
      invalidDivID = "invalid-"+(In.getAttribute("id"));
      if (($('#'+inputID).val()=="") ||($('#'+inputID).val()==null)) {
        $("#" + inputID).addClass("is-invalid");
        $("#" + invalidDivID).show();
        $("#" + invalidDivID).text("La clave no puede estar vacia");
      } else {
        $("#" + inputID).removeClass("is-invalid");
        $("#" + invalidDivID).hide();
        $("#" + invalidDivID).text("La clave no puede estar vacia");
        redFlag5 = 1;
      }
    }
    
  
}

//Carga el combo con los detalles de la factura, los detalles se guardan en un objeto para luego pasar a los imputs.
function cargarCMbClaves(){
  var html = "";
  let idFactura = $("#cmbFactura option:selected").val();
  if(idFactura){
    //Consulta los proveedores de la empresa
    $.ajax({
      type:'POST',
      url: "functions/controller.php",
      dataType: "json",
      data: { clase:"get_data",funcion:"get_detalle_factura", id_factura: idFactura},
      success: function (data) {
       // console.log("data de Detalles: ", data);
        $.each(data, function (i) {
          //Crea el html para ser mostrado
          data[i].numero_serie = (data[i].numero_serie==null)?"Concepto sin serie":data[i].numero_serie;
          data[i].numero_lote = (data[i].numero_lote==null)?"Concepto sin lote": data[i].numero_lote;
          data[i].clave = (data[i].clave == null)?"Concepto sin clave":data[i].clave;
          if (i == 0) {
            html +=
              '<select class="cmbSlim" name="cmbDetallesF" id="cmbDetallesF" ><option disabled selected value="0">Seleccione</option>';
              html +=
              '<option value="' +
              data[i].id +
              '">' +
              data[i].clave +
              "</option>";
          }else if(i==data.length){
            html +=
              '<option value="' +
              data[i].id +
              '">' +
              data[i].clave +
              "</option></select>";
          }
          else {
            html +=
              '<option value="' +
              data[i].id +
              '">' +
              data[i].clave +
              "</option>";
          }
          objDetallesF[data[i].id] = {"Serie": data[i].numero_serie, "Lote": data[i].numero_lote};
        });
        //Pone los proveedores en el select
        $("#cmbDetallesF").html(html);
      },
      error: function (error) {
        console.log("Error");
        console.log(error);
      },
  });
  }

}

function cargarCMBCliente() {
    //here our function should be implemented 
    var html = "";
    //Consulta los proveedores de la empresa
    $.ajax({
      type:'POST',
      url: "functions/controller.php",
      dataType: "json",
      data: { clase:"get_data",funcion:"get_Cliente"},
      success: function (data) {
        //console.log("data de proveedor: ", data);
        $.each(data, function (i) {
          //Crea el html para ser mostrado
          if (i == 0) {
              html +=
              '<option value="' +
              data[i].PKCliente +
              '">' +
              data[i].NombreComercial +
              "</option>";
          } else {
            html +=
              '<option value="' +
              data[i].PKCliente +
              '">' +
              data[i].NombreComercial +
              "</option>";
          }
        });
        //Pone los proveedores en el select
        $("#cmbCliente").append(html);

        if ($("#idClienteFrom").attr("value") != undefined) {
          cambiarCliente();
        }
        if ($("#idFacturaFrom").attr("value") != undefined) {
          factura=$("#idFacturaFrom").val();
          //se añade al objeto la factura y se carga en pantalla
          objChecksTrue[factura] = factura;
          passSelected();
        }
      },
      error: function (error) {
        console.log("Error");
        console.log(error);
      },
    
});
}

function cambiarCliente() {
  let clienteFrom = $("#idClienteFrom").attr("value");
  document.querySelector(
    '#cmbCliente [value="' + clienteFrom + '"]'
  ).selected = true;
  }

function cargarCMBFdPago() {
  //here our function should be implemented 
  var html = "";
  //Consulta los proveedores de la empresa
  $.ajax({
    type:'POST',
    url: "functions/controller.php",
    dataType: "json",
    data: { clase:"get_data",funcion:"get_fd_pago"},
    success: function (data) {
      //console.log("data de formas de pago: ", data);
      $.each(data, function (i) {
        //Crea el html para ser mostrado
        if (i == 0) {
            html +=
            '<option value="' +
            data[i].id +
            '">' +
            data[i].descripcion +
            "</option>";
        } else {
          html +=
            '<option value="' +
            data[i].id +
            '">' +
            data[i].descripcion +
            "</option>";
        }
      });
      //Pone los proveedores en el select
      $("#cmbFMPago").append(html);
    },
    error: function (error) {
      console.log("Error");
      console.log(error);
    },
  
});



}
//ABANDONADO
//Crea una lista para el imput de clave de producto o servicio con las primeras 15 concidencias de la entrada 
function cargarListClaveSat(palabraS){
  if(palabraS.length>3){
    //here our function should be implemented 
    var html = "";
    //Consulta los proveedores de la empresa
    $.ajax({
      type:'POST',
      url: "functions/controller.php",
      dataType: "json",
      data: { clase:"get_data",funcion:"get_claveSat", palabra:palabraS},
      success: function (data) {
        //console.log("data de formas de pago: ", data);
        $.each(data, function (i) {
          //Crea el html para ser mostrado
          if (i == 0) {
              html +=
              '<datalist id="listClaves"><option value="' +
              data[i].Clave +
              '">' +
              data[i].Descripcion +
              "</option>";
          }else if(i==data.length){
            html +=
            '<option value="' +
            data[i].Clave +
            '">' +
            data[i].Descripcion +
            "</option></datalist>";
          }
          else {
            html +=
              '<option value="' +
              data[i].Clave +
              '">' +
              data[i].Descripcion +
              "</option>";
          }
        });
        //Pone los proveedores en el select
        $("#lista").html(html);
      },
      error: function (error) {
        console.log("Error");
        console.log(error);
      },
    });
  }
  
}
//Crea una lista html para el imput de clave de unidad con las primeras 15 concidencias de la entrada 
function cargarListClaveSat_unidad(palabraU){
  if(palabraU.length>1){
    //here our function should be implemented 
    var html = "";
    //Consulta los proveedores de la empresa
    $.ajax({
      type:'POST',
      url: "functions/controller.php",
      dataType: "json",
      data: { clase:"get_data",funcion:"get_claveSat_unit", palabra:palabraU},
      success: function (data) {
        //console.log("data de formas de pago: ", data);
        $.each(data, function (i) {
          //Crea el html para ser mostrado
          if (i == 0) {
              html +=
              '<datalist id="listClavesUnidad"><option value="' +
              data[i].Clave + ' (' + data[i].Descripcion + ')' +
              '">' +
              "</option>";
          }else if(i==data.length){
            html +=
            '<option value="' +
            data[i].Clave + ' (' + data[i].Descripcion + ')' +
            '">' +
            "</option></datalist>";
          }
          else {
            html +=
              '<option value="' +
              data[i].Clave + ' (' + data[i].Descripcion + ')' +
              '">' +
              "</option>";
          }
        });
        //Pone los proveedores en el select
        $("#listaUnidad").html(html);
      },
      error: function (error) {
        console.log("Error");
        console.log(error);
      },
    });
  }
  
}

function cargarListConceptos(palabraC){
  console.log(palabraC);
  if(palabraC.length>1){
    //here our function should be implemented 
    var html = "";
    //Consulta los proveedores de la empresa
    $.ajax({
      type:'POST',
      url: "functions/controller.php",
      dataType: "json",
      data: { clase:"get_data",funcion:"get_Concept", palabra:palabraC},
      success: function (data) {
        //console.log("data de formas de pago: ", data);
        $.each(data, function (i) {
          //Crea el html para ser mostrado
          if (i == 0) {
              html +=
              '<datalist id="listConceptos"><option onclick="seleccionarConcepto("'+data[i].PKProducto +'" , "'+ data[i].id_api +'" , "'+ data[i].ClaveInterna +'"'+ '") value="' +
              data[i].ClaveInterna + ' (' + data[i].Nombre + ')' +
              '">' +
              "</option>";
          }else if(i==data.length){
            html +=
            '<option onclick="seleccionarConcepto("'+data[i].PKProducto +'" , "'+ data[i].id_api +'" , "'+ data[i].ClaveInterna +'"'+ '") value="' +
            data[i].ClaveInterna + ' (' + data[i].Nombre + ')' +
            '">' +
            "</option></datalist>";
          }
          else {
            html +=
              '<option onclick="seleccionarConcepto('+data[i].PKProducto +' , '+ data[i].id_api +' , '+ data[i].ClaveInterna + ')" value="' +
              data[i].ClaveInterna + ' (' + data[i].Nombre + ')' +
              '">' +
              "</option>";
          }
        });
        //Pone los proveedores en el select
        $("#listConceptos").html(html);

      },
      error: function (error) {
        console.log("Error");
        console.log(error);
      },
    });
  }
  
}

function seleccionarConcepto(PKProducto,id_api,ClaveInterna){
  console.log(PKProducto + id_api + ClaveInterna);
}

var slectImpuestos;
var selectConcepto;
var conceptos = {}; //<--Objeto
function crearSelects(){
    new SlimSelect({
        select: '#cmbCliente', 
        deselectLabel: '<span class="">✖</span>'
    });
    new SlimSelect({
      select: '#cmbFMPago', 
      deselectLabel: '<span class="">✖</span>'
    });
    new SlimSelect({
        select: '#cmbRelacion', 
        deselectLabel: '<span class="">✖</span>'
    });
    new SlimSelect({
      select: '#cmbFactura', 
      deselectLabel: '<span class="">✖</span>'
    });
    new SlimSelect({
      select : '#cmbVenta',
      deselectLabel: '<span class="">✖</span>'
    });
    slectImpuestos = 
    new SlimSelect({
      select: '#cmbImpuestosp', 
      deselectLabel: '<span class="">✖</span>'
    });
     
/*     new SlimSelect({
      select: '#cmbDetallesF', 
      deselectLabel: '<span class="">✖</span>'
      }); */

      //SlimSelect con uscador Ajax 
      //Busca en la base de datos el texto que se escribe, Guarda los resultados en un objeto y pone en el value del option el idProducto y en text el Nombre
        //Luego cuando se selecciona se jalan los datos del id seleccionado del objeto y se ponen en los inputs 
    selectConcepto =
    new SlimSelect({
      select: '#ConceptosAJAX',
      placeholder: 'Buscar concepto',
      searchingText: 'Buscando...',
      ajax: function (search, callback) {
        // Check search value. If you dont like it callback(false) or callback('Message String')
        if (search.length < 3) {
          callback('Minimo 4 caracteres')
          return
        }
        
        // Perform your own ajax request here
        fetch('functions/conceptos_get.php?palabra='+ search)
        .then(function (response) {
          return response.json()
        })
        .then(function (json) {
          let data = []
          conceptos = {};
          for (let i = 0; i < json.length; i++) {
            data.push({text:(json[i].ClaveInterna + ': ' +json[i].Nombre) , value: json[i].PKProducto});
            conceptos[json[i].PKProducto] = {id: json[i].Clave, claveInt: json[i].ClaveInterna, ClaveUnit: json[i].ClaveUnidad};
        /*     console.log(conceptos[json[i].PKProducto].claveInt); */
          }
    
          // Upon successful fetch send data to callback function.
          // Be sure to send data back in the proper format.
          // Refer to the method setData for examples of proper format.
          callback(data)
        })
        .catch(function(error) {
          // If any erros happened send false back through the callback
          callback(false)
        })
      }
    });
}
function contructblFacSelected(){
    $("#tblFacturasCliente").DataTable().destroy();
    let espanol = {
      sProcessing: "Procesando...",
      sZeroRecords: "No se encontraron resultados",
      sEmptyTable: "Ningún dato disponible en esta tabla",
      sSearch: '<img src="../../img/timdesk/buscar.svg" width="20px" />',
      sLoadingRecords: "Cargando...",
      searchPlaceholder: "Buscar...",
      oPaginate: {
        sFirst: "Primero",
        sLast: "Último",
        sNext: "<i class='fas fa-chevron-right'></i>",
        sPrevious: "<i class='fas fa-chevron-left'></i>",
      },
    };

    tablaF = $("#tblFacturasCliente").DataTable({
      language: espanol,
      info: false,
      scrollX: true,
      bSort: false,
      pageLength: 15,
      responsive: true,
      lengthChange: false,
      dom: `<"container-fluid"<"row mb-3"<"col-sm-12 col-md-9 p-0 d-flex align-items-center"B><"col-sm-12 col-md-3 p-0"f>>>rti
            <"container-fluid mt-4"<"row"<"#btn-filters.col-sm-12 col-md-10 p-0"><"col-sm-12 col-md-2 p-0"p>>>`,
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
        buttons: [
          {
            
          }
        ],
      },
      columns: [
        { data: "Folio factura" },
        { data: "Serie" },
        { data: "Fecha de timbrado" },
        { data: "Monto" },
        { data: "Eliminar" },
        { data: "Id", className: "hide_column"}, 
      ],
      //Poner la columna de id oculta
      columnDefs: [
        {
          targets: [5],
          visible: false,
          searchable: false,
          className: "hide_column",
        },
      ],
    });
}
function loadTblFacturas(){
  tipoNC = 1;
  let cliente = $("#cmbCliente option:selected").val();
  $("#txtProveeModal").val($("#cmbCliente option:selected").text());
  console.log($("#cmbCliente option:selected").text());
    $("#modal_tblFacturasCliente").DataTable().destroy();
    let espanol = {
      sProcessing: "Procesando...",
      sZeroRecords: "No se encontraron resultados",
      sEmptyTable: "Ningún dato disponible en esta tabla",
      sSearch: '<img src="../../img/timdesk/buscar.svg" width="20px" />',
      sLoadingRecords: "Cargando...",
      searchPlaceholder: "Buscar...",
      oPaginate: {
        sFirst: "Primero",
        sLast: "Último",
        sNext: "<i class='fas fa-chevron-right'></i>",
        sPrevious: "<i class='fas fa-chevron-left'></i>",
      },
    };

    tablaD = $("#modal_tblFacturasCliente").DataTable({
      language: espanol,
      info: false,
      scrollX: true,
      bSort: false,
      pageLength: 15,
      responsive: true,
      lengthChange: false,
      dom: `<"container-fluid"<"row mb-3"<"col-sm-12 col-md-9 p-0 d-flex align-items-center"B><"col-sm-12 col-md-3 p-0"f>>>rti
            <"container-fluid mt-4"<"row"<"#btn-filters.col-sm-12 col-md-10 p-0"><"col-sm-12 col-md-2 p-0"p>>>`,
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
        buttons: [
          {
            
          }
        ],
      },
      ajax: "functions/getFActuras.php?id_cliente="+cliente,
      columns: [
        { data: "Folio factura" },
        { data: "Serie" },
        { data: "Fecha de timbrado" },
        { data: "Estado" },
        { data: "Monto" },
        { data: "Seleccionar" },
        { data: "Id" ,className: "hide_column"}, 
      ],
      //Poner la columna de id oculta
      columnDefs: [
        {
          targets: [6],
          visible: false,
          searchable: false,
        },
      ],
    });
}

function loadTblVentas(){
  tipoNC = 2;
  let cliente = $("#cmbCliente option:selected").val();
  $("#txtProveeModalVentas").val($("#cmbCliente option:selected").text());
  console.log($("#cmbCliente option:selected").text());
    $("#modal_tblVentasCliente").DataTable().destroy();
    let espanol = {
      sProcessing: "Procesando...",
      sZeroRecords: "No se encontraron resultados",
      sEmptyTable: "Ningún dato disponible en esta tabla",
      sSearch: '<img src="../../img/timdesk/buscar.svg" width="20px" />',
      sLoadingRecords: "Cargando...",
      searchPlaceholder: "Buscar...",
      oPaginate: {
        sFirst: "Primero",
        sLast: "Último",
        sNext: "<i class='fas fa-chevron-right'></i>",
        sPrevious: "<i class='fas fa-chevron-left'></i>",
      },
    };

    tablaD = $("#modal_tblVentasCliente").DataTable({
      language: espanol,
      info: false,
      scrollX: true,
      bSort: false,
      pageLength: 15,
      responsive: true,
      lengthChange: false,
      dom: `<"container-fluid"<"row mb-3"<"col-sm-12 col-md-9 p-0 d-flex align-items-center"B><"col-sm-12 col-md-3 p-0"f>>>rti
            <"container-fluid mt-4"<"row"<"#btn-filters.col-sm-12 col-md-10 p-0"><"col-sm-12 col-md-2 p-0"p>>>`,
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
        buttons: [
          {
            
          }
        ],
      },
      ajax: "functions/getVentas.php?id_cliente="+cliente,
      columns: [
        { data: "Folio venta" },
        { data: "Fecha" },
        { data: "Estado" },
        { data: "Monto" },
        { data: "Seleccionar" },
        { data: "Id" ,className: "hide_column"}, 
      ],
      //Poner la columna de id oculta
      columnDefs: [
        {
          targets: [5],
          visible: false,
          searchable: false,
        },
      ],
    });
}
//Id de facturas seleccionadas en el modal
objChecksTrue = {};
function get_ids(sender){
    /* cuentas = cuentas_Copy.slice(); */
    if(sender.checked){
      ///Se borra el objeto para dejar solo el radio seleccionado.
        objChecksTrue = {};
        objChecksTrue[(sender.getAttribute('value'))] = ((sender.getAttribute('value')));
        console.info( objChecksTrue );
    }else{
      delete objChecksTrue[(sender.getAttribute('value'))];
    }
}
//Eliminar factura seleccionada
function deleteFact(factura){
    if(Object.keys(objChecksTrue).length>1){
      delete objChecksTrue[factura];
      //Elimina la factura del objeto con los datos de la tabla.
      $.each(objTablaFSelected, function (i) {
        if(objTablaFSelected[i].Id == factura){
          delete objTablaFSelected[i];
        }
      });
      passSelected();
      //CargarCMBFacturas();
    }else{
      Lobibox.notify("error", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../img/timdesk/notificacion_error.svg",
        msg: "¡Alerta, Se requiere al menos un documento",
      });
    }
}
//Objeto con los datos de la tabla
objTablaFSelected= {};
function passSelected(){
    if(!($.isEmptyObject(objChecksTrue))){
        /// Codigo para pasar el array de values de checks a un string 
        let string = "";
        Object.entries(objChecksTrue).forEach(([key, property]) => {
          string = string+=key+",";
        });
        let cadena = string.substring(0, string.length - 1);
        string = "";
        if(tipoNC == 1){
          tablaF.ajax.url("functions/load_facturasSelected_add?cadenaids="+cadena).load();
          $('#mod_agregarFacturas').modal('hide'); 
        }else{
          tablaF.ajax.url("functions/load_ventasSelected_add?cadenaids="+cadena).load();
          $('#mod_agregarVenta').modal('hide'); 
        }
        console.log(objChecksTrue);
      }
}

function CargarCMBFacturas(){
    //here our function should be implemented 
    var html = "";
    //Consulta los proveedores de la empresa
        $.each(objTablaFSelected, function (i) {
          //Crea el html para ser mostrado
          if (i == 0) {
              html += '<select name="cmbFactura" class="form-select" id="cmbFactura" aria-label="Default select example">' +
              '<option value="' +
              objTablaFSelected[i].Id +
              '">' +
              objTablaFSelected[i].Folio + ' - ' + objTablaFSelected[i].Serie + ' ($' + objTablaFSelected[i].Monto + ')'
              "</option>";
          } else if(i == Object.keys(objTablaFSelected).length) {
            html +=
            '<option value="' +
            objTablaFSelected[i].Id +
            '">' +
            objTablaFSelected[i].Folio + ' - ' + objTablaFSelected[i].Serie + ' ($' + objTablaFSelected[i].Monto + ')'
            "</option> </select>";
          }else{
            html +=
            '<option value="' +
            objTablaFSelected[i].Id +
            '">' +
            objTablaFSelected[i].Folio + ' - ' + objTablaFSelected[i].Serie + ' ($' + objTablaFSelected[i].Monto + ')'
            "</option>";
          }
        });
        //Pone los proveedores en el select
        $("#cmbFactura").html(html);

}

function CargarCMBVenta(){
  var html = "";
      $.each(objTablaFSelected, function (i) {
        //Crea el html para ser mostrado
        if (i == 0) {
            html += '<select name="cmbVenta" class="form-select" id="cmbVenta" aria-label="Default select example">' +
            '<option value="' +
            objTablaFSelected[i].Id +
            '">' +
            objTablaFSelected[i].Folio  + ' ($' + objTablaFSelected[i].Monto + ')'
            "</option>";
        } else if(i == Object.keys(objTablaFSelected).length) {
          html +=
          '<option value="' +
          objTablaFSelected[i].Id +
          '">' +
          objTablaFSelected[i].Folio + ' ($' + objTablaFSelected[i].Monto + ')'
          "</option> </select>";
        }else{
          html +=
          '<option value="' +
          objTablaFSelected[i].Id +
          '">' +
          objTablaFSelected[i].Folio + ' ($' + objTablaFSelected[i].Monto + ')'
          "</option>";
        }
      });
      //Pone los proveedores en el select
      $("#cmbVenta").html(html);
}

////Formato de moneda
const options2 = { style: 'currency', currency: 'USD' };
const numberFormat2 = new Intl.NumberFormat('en-US', options2);

function cargarTblConceptosTest(){
  //let copyObjconceptos = Object.assign({}, objconceptos); No funciona, Parece que falla si hay objetos hijos.
  let copyObjconceptos = JSON.parse(JSON.stringify(objconceptos));

      /// Acceder a los valores de cada conceptos
      var Subtotal = 0.00;
      var Total = 0.00;
  
      var html = "";
      for (const property in objconceptos) {
         
        var TotalImpuesto = 0.00;
        var tasa = "";
        var nombre = "";
  
        ///Impuestos gravables
        var IEPSCuTA = 0;
        var IEPS = 0;
        var IVA = 0;
        var Tasaretenidos = 0;
        var IEPSCuTAReten = 0;
        var Tasatrasladados = 0;
  
        ///Acceder a el valor de los impuestos
        var objAuxImp ={};
        objAuxImp = objconceptos[property].taxesGen;
  
        if(objconceptos[property].TaxesInclud == false ){
          ///Variable que guardara el IEPS del producto actual
          var IEPSxPRODUCT = 0.00;
          ///Recorrer los impuestos en busca de IEPS
          for (const property2 in objAuxImp) {
            if((property2.indexOf("Tasa") > -1) && (property2.indexOf("IEPS") > -1) && (!(property2.indexOf("Retenido") > -1))){
              tasa = ((parseFloat(objAuxImp[property2])) / 100);
              IEPSxPRODUCT = IEPSxPRODUCT + (objconceptos[property].Subtotal * tasa);
            }else if(property2.indexOf("Cuota") > -1 && (property2.indexOf("IEPS") > -1) && (!(property2.indexOf("Retenido") > -1))){
              tasa = parseFloat(objAuxImp[property2]);
              IEPSxPRODUCT = IEPSxPRODUCT + parseInt(objconceptos[property].Cantidad_p) * tasa;
            }else if(property2.indexOf("Exento") > -1){
              tasa = parseFloat(objAuxImp[property2]);
            }
          }
  
          ///Recorre todos los impuestos
          for (const property2 in objAuxImp) {
              //var arrImpuesto = cadena.split([property2]);
              console.log(`${property2}: ${objAuxImp[property2]}`);
              nombre = property2;
              ///Buscar si es Tasa, cuota o Excento
              if(property2.indexOf("Tasa") > -1){
                tasa = ((parseFloat(objAuxImp[property2])) / 100);
                if((property2.indexOf("IVA") > -1)){
                  TotalImpuesto = ((objconceptos[property].Subtotal + IEPSxPRODUCT) * tasa);
                }else{
                  TotalImpuesto = ((objconceptos[property].Subtotal) * tasa);
                }
                tasa = (tasa * 100) + "%";
                //Buscar si es Retenido o trasladado
                    /// Trasladado suma, Retenido resta
                if(property2.indexOf("Trasladado") > -1){
                  Total = Total + TotalImpuesto;
                }else{
                  Total = Total - TotalImpuesto;
                }
              }else if(property2.indexOf("Cuota") > -1){
                tasa = parseFloat(objAuxImp[property2]);
                TotalImpuesto = parseInt(objconceptos[property].Cantidad_p) * tasa;
                tasa = "$" + tasa;
                //Buscar si es Retenido o trasladado
                    /// Trasladado suma, Retenido resta
                if(property2.indexOf("Trasladado") > -1){
                  Total = Total + TotalImpuesto;
                }else{
                  Total = Total - TotalImpuesto;
                }
              }else if(property2.indexOf("Exento") > -1){
                tasa = parseFloat(objAuxImp[property2]);
              }else{
                ///Entra si es local 
                tasa = ((parseFloat(objAuxImp[property2])) / 100);
                TotalImpuesto = ((objconceptos[property].Subtotal) * tasa);
                tasa = (tasa * 100) + "%";
                Total = Total + TotalImpuesto;
              }
              ///Subtotal = Subtotal + copyObjconceptos[property].Subtotal;
              html += '<tr>'+
                        '<td style="text-align: right;" id="impuestos-head-">'+nombre+'</td>'+
                        '<td style="text-align: right;">'+tasa+' </td>'+
                        '<td style="text-align: right;">.....</td>'+
                        '<td style="text-align: right;"> $ '+ dosDecimales(TotalImpuesto) +'</td>'+
                      '</tr>';
                      /// Si es Retenido TipoImp 2 se resta del Total.
                      /* if(respuesta[i].tipoImp == 2){
                        Total -= respuesta[i].totalImpuesto;
                      }else{
                        Total += respuesta[i].totalImpuesto;
                      } */
            }
        }else{
          var ImportP = (objconceptos[property].Subtotal);
          var precioAntesImpuestos = 0;
          ///Recorre todos los impuestos
          for (const property2 in objAuxImp) {
            //var arrImpuesto = cadena.split([property2]);
            console.log(`${property2}: ${objAuxImp[property2]}`);
            nombre = property2;
            ///Buscar si es Tasa, cuota o Excento
            if(property2.indexOf("Tasa") > -1){
              ///////El IMPUESO ES TASA
              tasa = ((parseFloat(objAuxImp[property2])) / 100);
              if((property2.indexOf("IVA") > -1)){
                if((property2.indexOf("Trasladado") > -1)){
                  IVA = ((parseFloat(objAuxImp[property2])) / 100);
                }else{
                  Tasaretenidos = Tasaretenidos +  ((parseFloat(objAuxImp[property2])) / 100);
                }
                ///Si no es IVA pero si es TASA y es IEPS
              }else if((property2.indexOf("IEPS") > -1)){
                if((property2.indexOf("Trasladado") > -1)){
                  IEPS = ((parseFloat(objAuxImp[property2])) / 100);
                }else{
                  Tasaretenidos = Tasaretenidos +  ((parseFloat(objAuxImp[property2])) / 100);
                }
                ///Cualquier otro impuesto tipo TASA (inluidos los locales)
              }else if((property2.indexOf("Trasladado") > -1)){
                Tasatrasladados = Tasatrasladados + ((parseFloat(objAuxImp[property2])) / 100);
              }else if((property2.indexOf("Retenido") > -1)){
                Tasaretenidos = Tasaretenidos +  ((parseFloat(objAuxImp[property2])) / 100);
              }
              tasa = (tasa * 100) + "%";
              //Buscar si es Retenido o trasladado
                  /// Trasladado suma, Retenido resta
              if(property2.indexOf("Trasladado") > -1){
                Total = Total + TotalImpuesto;
              }else{
                Total = Total - TotalImpuesto;
              }
            }else if(property2.indexOf("Cuota") > -1){
              if((property2.indexOf("IEPS") > -1)){
                if((property2.indexOf("Trasladado") > -1)){
                  IEPSCuTA = ((parseFloat(objAuxImp[property2])) * parseInt(objconceptos[property].Cantidad_p));
                }else{
                  IEPSCuTAReten = IEPSCuTAReten +  ((parseFloat(objAuxImp[property2])) * parseInt(objconceptos[property].Cantidad_p));
                }
                ///Si no es IVA pero si es TASA y es IEPS
              }
            }else{
              //LOCALES
              Tasatrasladados = Tasatrasladados + ((parseFloat(objAuxImp[property2])) / 100);
            }
            
          }
          //console.log("arriba:  " + (ImportP-(IEPSCuTA*IVA)-IEPSCuTA+IEPSCuTAReten));
          //console.log("abajo:  " + (IEPS+(IEPS*IVA)+IVA-Tasaretenidos+Tasatrasladados+1));
          precioAntesImpuestos = (ImportP-(IEPSCuTA*IVA)-IEPSCuTA+IEPSCuTAReten)/(IEPS+(IEPS*IVA)+IVA-Tasaretenidos+Tasatrasladados+1);
  
          copyObjconceptos[property].Subtotal = parseFloat(dosDecimales(precioAntesImpuestos).replace(",",""));
          copyObjconceptos[property].Importe = parseFloat(dosDecimales((precioAntesImpuestos / objconceptos[property].Cantidad_p)).replace(",",""));

          ///Variable que guardara el IEPS del producto actual
          var IEPSxPRODUCT = 0.00;
          ///Recorrer los impuestos en busca de IEPS
          for (const property2 in objAuxImp) {
            if((property2.indexOf("Tasa") > -1) && (property2.indexOf("IEPS") > -1) && (!(property2.indexOf("Retenido") > -1))){
              tasa = ((parseFloat(objAuxImp[property2])) / 100);
              IEPSxPRODUCT = IEPSxPRODUCT + (copyObjconceptos[property].Subtotal * tasa);
            }else if(property2.indexOf("Cuota") > -1 && (property2.indexOf("IEPS") > -1) && (!(property2.indexOf("Retenido") > -1))){
              tasa = parseFloat(objAuxImp[property2]);
              IEPSxPRODUCT = IEPSxPRODUCT + parseInt(copyObjconceptos[property].Cantidad_p) * tasa;
            }else if(property2.indexOf("Exento") > -1){
              tasa = parseFloat(objAuxImp[property2]);
            }
          }
  
          ///Recorre todos los impuestos
          for (const property2 in objAuxImp) {
              //var arrImpuesto = cadena.split([property2]);
              console.log(`${property2}: ${objAuxImp[property2]}`);
              nombre = property2;
              ///Buscar si es Tasa, cuota o Excento
              if(property2.indexOf("Tasa") > -1){
                tasa = ((parseFloat(objAuxImp[property2])) / 100);
                if((property2.indexOf("IVA") > -1)){
                  TotalImpuesto = ((copyObjconceptos[property].Subtotal + IEPSxPRODUCT) * tasa);
                }else{
                  TotalImpuesto = ((copyObjconceptos[property].Subtotal) * tasa);
                }
                tasa = (tasa * 100) + "%";
                //Buscar si es Retenido o trasladado
                    /// Trasladado suma, Retenido resta
                if(property2.indexOf("Trasladado") > -1){
                  Total = Total + TotalImpuesto;
                }else{
                  Total = Total - TotalImpuesto;
                }
              }else if(property2.indexOf("Cuota") > -1){
                tasa = parseFloat(objAuxImp[property2]);
                TotalImpuesto = parseInt(copyObjconceptos[property].Cantidad_p) * tasa;
                tasa = "$" + tasa;
                //Buscar si es Retenido o trasladado
                    /// Trasladado suma, Retenido resta
                if(property2.indexOf("Trasladado") > -1){
                  Total = Total + TotalImpuesto;
                }else{
                  Total = Total - TotalImpuesto;
                }
              }else if(property2.indexOf("Exento") > -1){
                tasa = parseFloat(objAuxImp[property2]);
              }else{
                ///Entra si es local 
                tasa = ((parseFloat(objAuxImp[property2])) / 100);
                TotalImpuesto = ((copyObjconceptos[property].Subtotal) * tasa);
                tasa = (tasa * 100) + "%";
                Total = Total + TotalImpuesto;
              }
  
              html += '<tr>'+
                        '<td style="text-align: right;" id="impuestos-head-">'+nombre+'</td>'+
                        '<td style="text-align: right;">'+tasa+' </td>'+
                        '<td style="text-align: right;">.....</td>'+
                        '<td style="text-align: right;"> $ '+ dosDecimales(TotalImpuesto) +'</td>'+
                      '</tr>';
            }
            
          Subtotal = Subtotal + copyObjconceptos[property].Subtotal;
          copyObjconceptos[property].Subtotal = numberFormat2.format(copyObjconceptos[property].Subtotal);
            copyObjconceptos[property].Importe = numberFormat2.format(copyObjconceptos[property].Importe);
          
          console.log("Precio:  " + precioAntesImpuestos);
        }
        ///Si no trae impuestos no se sumo nunca el subtotal entonces lo sumamos ahora.
        if(copyObjconceptos[property].TaxesInclud == false){
          Subtotal = Subtotal + copyObjconceptos[property].Subtotal;
          copyObjconceptos[property].Subtotal = numberFormat2.format(copyObjconceptos[property].Subtotal);
          ///Borrar lo que está despues del primer punto
          copyObjconceptos[property].Importe = parseFloat(copyObjconceptos[property].Importe);
          copyObjconceptos[property].Importe = numberFormat2.format(copyObjconceptos[property].Importe);
        }
        
        console.log(numberFormat2.format(Subtotal));
        console.log(`${property}: ${objconceptos[property].Subtotal}`);

      }/// Termina recorrido del objconcepts

      $('#Subtotal').html(numberFormat2.format(Subtotal));
      $('#impuestosX').html(html);
      Total = Total + Subtotal;
      $('#Total').html(numberFormat2.format(Total));

    //Destruye la tabla existente
    $("#tblConceptos").DataTable().destroy();
    //Convierte el objeto a array
    let datos = Object.values(copyObjconceptos);
    console.info(datos);

    //Contruye la nueva tabla
      let espanol = {
        sProcessing: "Procesando...",
        sZeroRecords: "No se encontraron resultados",
        sEmptyTable: "Ningún dato disponible en esta tabla",
        sSearch: '<img src="../../img/timdesk/buscar.svg" width="20px" />',
        sLoadingRecords: "Cargando...",
        searchPlaceholder: "Buscar...",
        oPaginate: {
          sFirst: "Primero",
          sLast: "Último",
          sNext: "<i class='fas fa-chevron-right'></i>",
          sPrevious: "<i class='fas fa-chevron-left'></i>",
        },
      };

    tablaD = $("#tblConceptos").DataTable({
      language: espanol,
      info: false,
      scrollX: true,
      bSort: false,
      pageLength: 15,
      responsive: true,
      lengthChange: false,
      dom: `<"container-fluid"<"row mb-3"<"col-sm-12 col-md-9 p-0 d-flex align-items-center"B><"col-sm-12 col-md-3 p-0"f>>>rti
            <"container-fluid mt-4"<"row"<"#btn-filters.col-sm-12 col-md-10 p-0"><"col-sm-12 col-md-2 p-0"p>>>`,
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
        buttons: [
          {
          
          }
        ],
      },
      data: datos,
      //ajax: "functions/.php?id_cliente=5",
      columns: [
        { data: "C_Producto_Servicio" },
        { data: "C_Unidad" },
        { data: "Descripcion" },
        { data: "Importe" }, 
        { data: "Cantidad_p" }, 
        { data: "strImpuestos" },
        { data: "Subtotal" }, 
        { data: "Acciones" },

      ],
      //Poner la columna de id oculta
      columnDefs: [
        {
          targets: [],
          visible: false,
          searchable: false,
        },
      ],
    }); 

}

  ///Validar el numero 12 parte entera 6 parte decimal.
  function validarMoneda(numero){
    //valida que la cantidad no sea mayor a 12 enteros y 6 decimales
    aux = numero.toString().split(".");
    var ValorAux="";
    flag = false;

    if(aux.length > 0){
      if(aux.length == 1 && aux[0].length > 12){
        flag = true;
        ValorAux = aux[0].substring(0,12);
        }else if(aux.length >= 2 && (aux[0].length > 12 || aux[1].length > 6)){
          flag = true;
          ValorAux = aux[0].substring(0,12) + "." + aux[1].substring(0,6);
        }else if(aux.length == 1){
          ValorAux = numero.toString() + ".00";
        }else{
          ValorAux = numero;
        }
    }
    if(flag){
      Lobibox.notify("warning", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../../../img/timdesk/warning_circle.svg",
        msg: 'El precio solo admite hasta 12 enteros y 6 decimales',
        sound: '../../../../../sounds/sound4'
      });
      //$('#precio-'+id).val(ValorAux);
    }
    return ValorAux;

}


function cargarTblConceptos(){

  let copyObjconceptos;

  if(objconceptos[property].TaxesInclud == false){
      //Destruye la tabla existente
      $("#tblConceptos").DataTable().destroy();
      //Convierte el objeto a array
      let datos = Object.values(objconceptos);
      console.info(datos);

      //Contruye la nueva tabla
        let espanol = {
          sProcessing: "Procesando...",
          sZeroRecords: "No se encontraron resultados",
          sEmptyTable: "Ningún dato disponible en esta tabla",
          sSearch: '<img src="../../img/timdesk/buscar.svg" width="20px" />',
          sLoadingRecords: "Cargando...",
          searchPlaceholder: "Buscar...",
          oPaginate: {
            sFirst: "Primero",
            sLast: "Último",
            sNext: "<i class='fas fa-chevron-right'></i>",
            sPrevious: "<i class='fas fa-chevron-left'></i>",
          },
        };

        tablaD = $("#tblConceptos").DataTable({
          language: espanol,
          info: false,
          scrollX: true,
          bSort: false,
          pageLength: 15,
          responsive: true,
          lengthChange: false,
          dom: `<"container-fluid"<"row mb-3"<"col-sm-12 col-md-9 p-0 d-flex align-items-center"B><"col-sm-12 col-md-3 p-0"f>>>rti
                <"container-fluid mt-4"<"row"<"#btn-filters.col-sm-12 col-md-10 p-0"><"col-sm-12 col-md-2 p-0"p>>>`,
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
            buttons: [
              {
              
              }
            ],
          },
          data: datos,
          //ajax: "functions/.php?id_cliente=5",
          columns: [
            { data: "C_Producto_Servicio" },
            { data: "C_Unidad" },
            { data: "Descripcion" },
            { data: "Importe" }, 
            { data: "Cantidad_p" }, 
            { data: "strImpuestos" },
            { data: "Subtotal" }, 
            { data: "Acciones" },

          ],
          //Poner la columna de id oculta
          columnDefs: [
            {
              targets: [],
              visible: false,
              searchable: false,
            },
          ],
        });
    }else{
      copyObjconceptos = Object.assign({}, objconceptos);
    }
  

    /// Acceder a los valores de cada conceptos
    var Subtotal = 0.00;
    var Total = 0.00;

    var html = "";
    for (const property in objconceptos) {
       
      Subtotal = Subtotal + objconceptos[property].Subtotal;
      var TotalImpuesto = 0.00;
      var tasa = "";
      var nombre = "";

      ///Impuestos gravables
      var IEPSCuTA = 0;
      var IEPS = 0;
      var IVA = 0;
      var Tasaretenidos = 0;
      var IEPSCuTAReten = 0;
      var Tasatrasladados = 0;

      ///Acceder a el valor de los impuestos
      var objAuxImp ={};
      objAuxImp = objconceptos[property].taxesGen;

      if(objconceptos[property].TaxesInclud == false){
        ///Variable que guardara el IEPS del producto actual
        var IEPSxPRODUCT = 0.00;
        ///Recorrer los impuestos en busca de IEPS
        for (const property2 in objAuxImp) {
          if((property2.indexOf("Tasa") > -1) && (property2.indexOf("IEPS") > -1) && (!(property2.indexOf("Retenido") > -1))){
            tasa = ((parseFloat(objAuxImp[property2])) / 100);
            IEPSxPRODUCT = IEPSxPRODUCT + (objconceptos[property].Subtotal * tasa);
          }else if(property2.indexOf("Cuota") > -1 && (property2.indexOf("IEPS") > -1) && (!(property2.indexOf("Retenido") > -1))){
            tasa = parseFloat(objAuxImp[property2]);
            IEPSxPRODUCT = IEPSxPRODUCT + parseInt(objconceptos[property].Cantidad_p) * tasa;
          }else if(property2.indexOf("Exento") > -1){
            tasa = parseFloat(objAuxImp[property2]);
          }
        }

        ///Recorre todos los impuestos
        for (const property2 in objAuxImp) {
            //var arrImpuesto = cadena.split([property2]);
            console.log(`${property2}: ${objAuxImp[property2]}`);
            nombre = property2;
            ///Buscar si es Tasa, cuota o Excento
            if(property2.indexOf("Tasa") > -1){
              tasa = ((parseFloat(objAuxImp[property2])) / 100);
              if((property2.indexOf("IVA") > -1)){
                TotalImpuesto = ((objconceptos[property].Subtotal + IEPSxPRODUCT) * tasa);
              }else{
                TotalImpuesto = ((objconceptos[property].Subtotal) * tasa);
              }
              tasa = (tasa * 100) + "%";
              //Buscar si es Retenido o trasladado
                  /// Trasladado suma, Retenido resta
              if(property2.indexOf("Trasladado") > -1){
                Total = Total + TotalImpuesto;
              }else{
                Total = Total - TotalImpuesto;
              }
            }else if(property2.indexOf("Cuota") > -1){
              tasa = parseFloat(objAuxImp[property2]);
              TotalImpuesto = parseInt(objconceptos[property].Cantidad_p) * tasa;
              tasa = "$" + tasa;
              //Buscar si es Retenido o trasladado
                  /// Trasladado suma, Retenido resta
              if(property2.indexOf("Trasladado") > -1){
                Total = Total + TotalImpuesto;
              }else{
                Total = Total - TotalImpuesto;
              }
            }else if(property2.indexOf("Exento") > -1){
              tasa = parseFloat(objAuxImp[property2]);
            }else{
              ///Entra si es local 
              tasa = ((parseFloat(objAuxImp[property2])) / 100);
              TotalImpuesto = ((objconceptos[property].Subtotal) * tasa);
              tasa = (tasa * 100) + "%";
              Total = Total + TotalImpuesto;
            }

            html += '<tr>'+
                      '<td style="text-align: right;" id="impuestos-head-">'+nombre+'</td>'+
                      '<td style="text-align: right;">'+tasa+' </td>'+
                      '<td style="text-align: right;">.....</td>'+
                      '<td style="text-align: right;"> $ '+ dosDecimales(TotalImpuesto) +'</td>'+
                    '</tr>';
                    /// Si es Retenido TipoImp 2 se resta del Total.
                    /* if(respuesta[i].tipoImp == 2){
                      Total -= respuesta[i].totalImpuesto;
                    }else{
                      Total += respuesta[i].totalImpuesto;
                    } */
          }
      }else{
        var ImportP = (objconceptos[property].Subtotal);
        var precioAntesImpuestos = 0;
        ///Recorre todos los impuestos
        for (const property2 in objAuxImp) {
          //var arrImpuesto = cadena.split([property2]);
          console.log(`${property2}: ${objAuxImp[property2]}`);
          nombre = property2;
          ///Buscar si es Tasa, cuota o Excento
          if(property2.indexOf("Tasa") > -1){
            ///////El IMPUESO ES TASA
            tasa = ((parseFloat(objAuxImp[property2])) / 100);
            if((property2.indexOf("IVA") > -1)){
              if((property2.indexOf("Trasladado") > -1)){
                IVA = ((parseFloat(objAuxImp[property2])) / 100);
              }else{
                Tasaretenidos = Tasaretenidos +  ((parseFloat(objAuxImp[property2])) / 100);
              }
              ///Si no es IVA pero si es TASA y es IEPS
            }else if((property2.indexOf("IEPS") > -1)){
              if((property2.indexOf("Trasladado") > -1)){
                IEPS = ((parseFloat(objAuxImp[property2])) / 100);
              }else{
                Tasaretenidos = Tasaretenidos +  ((parseFloat(objAuxImp[property2])) / 100);
              }
              ///Cualquier otro impuesto tipo TASA (inluidos los locales)
            }else if((property2.indexOf("Trasladado") > -1)){
              Tasatrasladados = Tasatrasladados + ((parseFloat(objAuxImp[property2])) / 100);
            }else if((property2.indexOf("Retenido") > -1)){
              Tasaretenidos = Tasaretenidos +  ((parseFloat(objAuxImp[property2])) / 100);
            }
            tasa = (tasa * 100) + "%";
            //Buscar si es Retenido o trasladado
                /// Trasladado suma, Retenido resta
            if(property2.indexOf("Trasladado") > -1){
              Total = Total + TotalImpuesto;
            }else{
              Total = Total - TotalImpuesto;
            }
          }else if(property2.indexOf("Cuota") > -1){
            if((property2.indexOf("IEPS") > -1)){
              if((property2.indexOf("Trasladado") > -1)){
                IEPSCuTA = ((parseFloat(objAuxImp[property2])) * parseInt(objconceptos[property].Cantidad_p));
              }else{
                IEPSCuTAReten = IEPSCuTAReten +  ((parseFloat(objAuxImp[property2])) * parseInt(objconceptos[property].Cantidad_p));
              }
              ///Si no es IVA pero si es TASA y es IEPS
            }
          }else{
            //LOCALES
            Tasatrasladados = Tasatrasladados + ((parseFloat(objAuxImp[property2])) / 100);
          }
          
        }
        console.log("arriba:  " + (ImportP-(IEPSCuTA*IVA)-IEPSCuTA+IEPSCuTAReten));
        console.log("abajo:  " + (IEPS+(IEPS*IVA)+IVA-Tasaretenidos+Tasatrasladados+1));
        precioAntesImpuestos = (ImportP-(IEPSCuTA*IVA)-IEPSCuTA+IEPSCuTAReten)/(IEPS+(IEPS*IVA)+IVA-Tasaretenidos+Tasatrasladados+1);

        copyObjconceptos[property].Subtotal = precioAntesImpuestos;
        copyObjconceptos[property].Importe = (precioAntesImpuestos / objconceptos[property].Cantidad_p);
        
        console.log("Precio:  " + precioAntesImpuestos);
      }
      
      console.log(numberFormat2.format(Subtotal));
      console.log(`${property}: ${objconceptos[property].Subtotal}`);
                $('#Subtotal').html(numberFormat2.format(Subtotal));
                $('#impuestosX').html(html);
    }/// Termina recorrido del objconcepts

    if(copyObjconceptos[property].TaxesInclud == true){
      //Destruye la tabla existente
      $("#tblConceptos").DataTable().destroy();
      //Convierte el objeto a array
      let datos = Object.values(copyObjconceptos);
      console.info(datos);

      //Contruye la nueva tabla
        let espanol = {
          sProcessing: "Procesando...",
          sZeroRecords: "No se encontraron resultados",
          sEmptyTable: "Ningún dato disponible en esta tabla",
          sSearch: '<img src="../../img/timdesk/buscar.svg" width="20px" />',
          sLoadingRecords: "Cargando...",
          searchPlaceholder: "Buscar...",
          oPaginate: {
            sFirst: "Primero",
            sLast: "Último",
            sNext: "<i class='fas fa-chevron-right'></i>",
            sPrevious: "<i class='fas fa-chevron-left'></i>",
          },
        };

        tablaD = $("#tblConceptos").DataTable({
          language: espanol,
          info: false,
          scrollX: true,
          bSort: false,
          pageLength: 15,
          responsive: true,
          lengthChange: false,
          dom: `<"container-fluid"<"row mb-3"<"col-sm-12 col-md-9 p-0 d-flex align-items-center"B><"col-sm-12 col-md-3 p-0"f>>>rti
                <"container-fluid mt-4"<"row"<"#btn-filters.col-sm-12 col-md-10 p-0"><"col-sm-12 col-md-2 p-0"p>>>`,
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
            buttons: [
              {
              
              }
            ],
          },
          data: datos,
          //ajax: "functions/.php?id_cliente=5",
          columns: [
            { data: "C_Producto_Servicio" },
            { data: "C_Unidad" },
            { data: "Descripcion" },
            { data: "Importe" }, 
            { data: "Cantidad_p" }, 
            { data: "strImpuestos" },
            { data: "Subtotal" }, 
            { data: "Acciones" },

          ],
          //Poner la columna de id oculta
          columnDefs: [
            {
              targets: [],
              visible: false,
              searchable: false,
            },
          ],
        });
    }


    Total = Total + Subtotal;
                $('#Total').html(numberFormat2.format(Total));
}
//Eliminar de la tabla un concepto 
  //Tambien lo saca del objeto 
function eliminarConcept(idobj){
  if(Object.keys(objconceptos).length>1){
    delete objconceptos[idobj];
    //Recarga la tabla
    cargarTblConceptosTest();
  }else{
    Lobibox.notify("error", {
      size: "mini",
      rounded: true,
      delay: 3000,
      delayIndicator: false,
      position: "center top",
      icon: true,
      img: "../../img/timdesk/notificacion_error.svg",
      msg: "¡Alerta, Se requiere al menos un concepto",
    });
  }

}

function cargarCMBRelacion(){
    var html = "";
    //Consulta los proveedores de la empresa
    $.ajax({
      type:'POST',
      url: "functions/controller.php",
      dataType: "json",
      data: { clase:"get_data",funcion:"get_fd_relacion_fact"},
      success: function (data) {
       // console.log("data de formas de pago: ", data);
        $.each(data, function (i) {
          //Crea el html para ser mostrado
          if (i == 0) {
              html +=
              '<option value="' +
              data[i].clave +
              '">' +
              data[i].descripcion +
              "</option>";
          } else {
            html +=
              '<option value="' +
              data[i].clave +
              '">' +
              data[i].descripcion +
              "</option>";
          }
        });
        //Pone los proveedores en el select
        $("#cmbRelacion").append(html);
      },
      error: function (error) {
        console.log("Error");
        console.log(error);
      },
    
  });
}

function createFactura(){
  let cliente = $("#cmbCliente option:selected").val();
  let FdPago = $("#cmbFMPago option:selected").val();
  let relacion = $("#cmbRelacion option:selected").val();
  $("#btnguardarF").attr('disabled', true);
  let value = $('#Total').text();
  value = parseFloat(value.replace('$',''));
  ///Comprobar que el Total de la nota de credito sea menor que el Total de la factura
  if((value) <= TotalFacturas){
      //Si el objeto con las facturas seleccionadas no esta vacio
  if(!($.isEmptyObject(objChecksTrue)) && tipoNC == 1){
    console.info(objChecksTrue);
    let string = "";
    Object.entries(objChecksTrue).forEach(([key, property]) => {
      string = string+=key+",";
    });
    let cadena = string.substring(0, string.length - 1);
    console.log(relacion);
    string = "";
    $.ajax({
      type: "POST",
      async:false,
      url: "functions/CrearFacturaEgreso.php",
      data: {idsF:cadena,clienteId:cliente,FDePago:FdPago,Relacion:relacion,objConceptos:objconceptos,importeTotal:value},
      dataType: "json",
      success: function (response) {
        $("#btnguardarF").attr('disabled', false);
        if(response['status']=='warning'){
          Lobibox.notify("warning", {
            size: "mini",
            rounded: true,
            delay: 3500,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/warning_circle.svg",
            msg: "Alerta: " + response['result']
          });
        }else if(response['status'] == 'error'){
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3500,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/notificacion_error.svg",
            msg: "Error: " + response['result']
          });
        }else{
          verFactura(response);
          setTimeout(function(){ window.location= '../notas_credito';}, 200);
        }
        
      },error: function (error) {
        console.log("Error");
        console.log(error);
        $("#btnguardarF").attr('disabled', false);
        setTimeout(function(){ window.location= '../notas_credito/agregar.php';}, 200);
      },
    });
   // setTimeout(function(){ window.location= '../notas_credito';}, 200);
  }else{
    Lobibox.notify("warning", {
      size: "mini",
      rounded: true,
      delay: 3500,
      delayIndicator: false,
      position: "center top",
      icon: true,
      img: "../../img/timdesk/warning_circle.svg",
      msg: "Alerta, Relacione al menos una Factura!",
    });
    $("#btnguardarF").attr('disabled', false);
    document.getElementById("CargarFacturas").click();
  }
  }else{
    Lobibox.notify("warning", {
      size: "mini",
      rounded: true,
      delay: 4000,
      delayIndicator: false,
      position: "center top",
      icon: true,
      img: "../../img/timdesk/warning_circle.svg",
      msg: "Alerta, El importe de la nota de credito no puede superar el importe de la Factura!",
    });
    $("#btnguardarF").attr('disabled', false);
  }

}

function createNC_venta(){
  let cliente = $("#cmbCliente option:selected").val();
  $("#btnguardarV").attr('disabled', true);
  let descripcion = $('#txtDescripcionVenta').val();
  let value = $('#txtImporteVenta').val();
  value = parseFloat(value.replace('$',''));
  ///Comprobar que el Total de la nota de credito sea menor que el Total de la factura
  if((value) <= TotalFacturas){
      //Si el objeto con las facturas seleccionadas no esta vacio
  if(!($.isEmptyObject(objChecksTrue)) && tipoNC == 2){
    let string = "";
    Object.entries(objChecksTrue).forEach(([key, property]) => {
      string = string+=key+",";
    });
    let cadena = string.substring(0, string.length - 1);
    string = "";
    $.ajax({
      type: "POST",
      async:false,
      url: "functions/CrearNC_Venta.php",
      data: {ids:cadena,clienteId:cliente, descripcion:descripcion, importe:value},
      dataType: "json",
      success: function (response) {
        $("#btnguardarV").attr('disabled', false);
        if(response['status']=='warning'){
          Lobibox.notify("warning", {
            size: "mini",
            rounded: true,
            delay: 3500,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/warning_circle.svg",
            msg: "Alerta: " + response['result']
          });
        }else if(response['status'] == 'error'){
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3500,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/notificacion_error.svg",
            msg: "Error: " + response['result']
          });
        }else{
          setTimeout(function(){ window.location= '../notas_credito';}, 200);
        }
      },error: function (error) {
        console.log(error);
        $("#btnguardarV").attr('disabled', false);
        setTimeout(function(){ window.location= '../notas_credito/agregar.php';}, 200);
      },
    });
   // setTimeout(function(){ window.location= '../notas_credito';}, 200);
  }else{
    Lobibox.notify("warning", {
      size: "mini",
      rounded: true,
      delay: 3500,
      delayIndicator: false,
      position: "center top",
      icon: true,
      img: "../../img/timdesk/warning_circle.svg",
      msg: "Alerta, Relacione al menos una Factura!",
    });
    $("#btnguardarV").attr('disabled', false);
    document.getElementById("CargarFacturas").click();
  }
  }else{
    Lobibox.notify("warning", {
      size: "mini",
      rounded: true,
      delay: 4000,
      delayIndicator: false,
      position: "center top",
      icon: true,
      img: "../../img/timdesk/warning_circle.svg",
      msg: "Alerta, El importe de la nota de credito no puede superar el importe de la Factura!",
    });
    $("#btnguardarV").attr('disabled', false);
  }

}

function verFactura(id) {
  var form = document.createElement("form");
  console.log("ID " + id);
  document.body.appendChild(form);
  form.method = "post";
  form.action = "facturaPago.php";
  form.target = "_blank";
  var input = document.createElement("input");
  input.type = "hidden";
  input.name = "id";
  input.value = id;
  form.appendChild(input);
  form.submit();
}

function descargar(){
  $.ajax({
    url: "functions/DescargarPDF.php",
    data: "data",
    xhrFields: {
      responseType: 'blob'
    },
    success: function (response) {
      if(response!="err"){
        var a = document.createElement('a');
        var url = window.URL.createObjectURL(response);
        a.href = url;
        a.download = 'CP-618d41b2766c09002d14a6e6.pdf';
        a.click();
        window.URL.revokeObjectURL(url);
      }else{
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/notificacion_error.svg",
          msg: "¡Error, no se pudo descargar!",
        });
      }
    }
  });
}

function test(){
  console.log($("#ClaveSAT").val());
}

function dosDecimales(n) {
  n = Math.round((n + Number.EPSILON) * 100) / 100;
  return Number.parseFloat(n).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
}
